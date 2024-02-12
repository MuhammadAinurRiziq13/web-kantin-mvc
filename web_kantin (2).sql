-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Des 2023 pada 17.21
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web_kantin`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `search_history_atas` (IN `p_idx` INT, IN `p_date` DATE)   BEGIN
    SELECT 
        t.tanggal_transaksi, 
        t.id_transaksi,
        CASE
            WHEN LENGTH(GROUP_CONCAT(' ', b.nama_barang)) > 40 
            THEN 
                CONCAT(LEFT(GROUP_CONCAT(' ', b.nama_barang), 40), ' ...')
            ELSE 
                GROUP_CONCAT(' ',b.nama_barang)
        END AS nama_barang,
        SUM(qty * b.harga_jual) AS harga_jual,
        SUM(qty * b.harga_beli) AS harga_beli,
        SUM(dt.qty) AS total_qty
    FROM 
        transaksi t
    INNER JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
    INNER JOIN barang b ON dt.id_barang = b.id_barang
    INNER JOIN supplier s ON s.id_supplier = b.id_supplier
    WHERE 
        (p_date = '' OR DATE(t.tanggal_transaksi) = p_date)
        AND 
        (p_idx = 0 OR s.id_supplier = p_idx)
    GROUP BY t.id_transaksi, t.tanggal_transaksi;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `search_history_bawah` (IN `p_month` INT, IN `p_year` INT)   BEGIN
    SELECT 
        t.tanggal_transaksi, 
        t.id_transaksi,
        LEFT(GROUP_CONCAT(' ', b.nama_barang), 40) AS nama_barang,
        t.total_transaksi,
         SUM(qty * b.harga_jual) AS harga_jual,
        SUM(qty * b.harga_beli) AS harga_beli,
        SUM(dt.qty) AS total_qty
    FROM 
        transaksi t
    INNER JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
    INNER JOIN barang b ON dt.id_barang = b.id_barang
    WHERE 
        (MONTH(t.tanggal_transaksi) = p_month OR p_month = 0)
        AND (YEAR(t.tanggal_transaksi) = p_year OR p_year = 0)
    GROUP BY t.id_transaksi, t.tanggal_transaksi;
END$$

--
-- Fungsi
--
CREATE DEFINER=`root`@`localhost` FUNCTION `hitung_selisih_harga` (`p_idx` INT, `p_date` DATE, `p_month` INT, `p_year` INT) RETURNS DECIMAL(10,2)  BEGIN
    DECLARE selisih DECIMAL(10, 2);

    SELECT 
        SUM(qty * b.harga_jual) - SUM(qty * b.harga_beli) INTO selisih
    FROM 
        transaksi t
    INNER JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
    INNER JOIN barang b ON dt.id_barang = b.id_barang
    INNER JOIN supplier s ON s.id_supplier = b.id_supplier
    WHERE 
        (p_date = 0 OR DATE(t.tanggal_transaksi) = p_date)
        AND 
        (p_idx = 0 OR s.id_supplier = p_idx)
        AND
        (p_month = 0 OR MONTH(t.tanggal_transaksi) = p_month)
        AND
        (p_year = 0 OR YEAR(t.tanggal_transaksi) = p_year);
    -- Jika selisih NULL, set nilai default ke 0
    IF selisih IS NULL THEN
        SET selisih = 0;
    END IF;

    RETURN selisih;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `hitung_total_harga_beli` (`p_idx` INT, `p_date` DATE, `p_month` INT, `p_year` INT) RETURNS DECIMAL(10,2)  BEGIN
    DECLARE total_harga_beli DECIMAL(10,2);

    SELECT COALESCE(SUM(qty * b.harga_beli), 0) INTO total_harga_beli
    FROM transaksi t
    INNER JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
    INNER JOIN barang b ON dt.id_barang = b.id_barang
    INNER JOIN supplier s ON s.id_supplier = b.id_supplier
    WHERE 
        (p_date = 0 OR DATE(t.tanggal_transaksi) = p_date)
        AND 
        (p_idx = 0 OR s.id_supplier = p_idx)
        AND
        (p_month = 0 OR MONTH(t.tanggal_transaksi) = p_month)
        AND
        (p_year = 0 OR YEAR(t.tanggal_transaksi) = p_year);
    RETURN total_harga_beli;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `hitung_total_harga_jual` (`p_idx` INT, `p_date` DATE, `p_month` INT, `p_year` INT) RETURNS DECIMAL(10,2)  BEGIN
    DECLARE total_harga_jual DECIMAL(10,2);

    SELECT COALESCE(SUM(qty * b.harga_jual), 0) INTO total_harga_jual
    FROM transaksi t
    INNER JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
    INNER JOIN barang b ON dt.id_barang = b.id_barang
    INNER JOIN supplier s ON s.id_supplier = b.id_supplier
    WHERE 
        (p_date = 0 OR DATE(t.tanggal_transaksi) = p_date)
        AND 
        (p_idx = 0 OR s.id_supplier = p_idx)
        AND
        (p_month = 0 OR MONTH(t.tanggal_transaksi) = p_month)
        AND
        (p_year = 0 OR YEAR(t.tanggal_transaksi) = p_year);
    RETURN total_harga_jual;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `nama_barang` varchar(25) NOT NULL,
  `harga_beli` int(11) NOT NULL,
  `harga_jual` int(11) NOT NULL,
  `stok_barang` int(11) NOT NULL,
  `gambar` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id_barang`, `id_kategori`, `id_supplier`, `nama_barang`, `harga_beli`, `harga_jual`, `stok_barang`, `gambar`) VALUES
(25, 2, 8, 'choco malt', 5000, 6000, 7, 'ABC Choco malt.jpg'),
(26, 2, 8, 'kopi susu', 3000, 4000, 7, 'ABC kopi susu.jpg'),
(27, 2, 8, 'jeruk nipis', 3500, 4500, 2, 'Anget sari jeruk nipis (1).jpg'),
(28, 3, 8, 'aoka', 2000, 2500, 5, 'aoka.jpg'),
(29, 3, 9, 'basreng', 3000, 3500, 8, 'basreng 3.5k.png'),
(30, 3, 8, 'beng beng', 3000, 3500, 8, 'beng-beng.jpg'),
(31, 3, 9, 'donat', 3000, 3500, 6, 'donat-.png');

--
-- Trigger `barang`
--
DELIMITER $$
CREATE TRIGGER `delete_detail_by_barang` BEFORE DELETE ON `barang` FOR EACH ROW DELETE FROM detail_transaksi WHERE id_barang = OLD.id_barang
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_barang` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_barang`, `id_transaksi`, `qty`) VALUES
(25, 49, 1),
(31, 49, 1),
(28, 50, 3),
(26, 50, 1),
(31, 51, 2),
(26, 51, 1),
(26, 52, 1),
(29, 52, 1),
(31, 53, 1),
(26, 53, 1),
(29, 54, 1),
(28, 54, 1),
(29, 55, 1),
(30, 55, 1),
(31, 55, 1),
(30, 56, 1),
(31, 56, 1),
(27, 57, 1),
(26, 58, 1),
(26, 59, 1),
(28, 59, 2),
(29, 63, 2),
(28, 63, 1),
(27, 63, 1),
(31, 64, 1),
(30, 64, 1),
(29, 65, 1),
(31, 66, 1),
(31, 67, 1),
(26, 67, 1),
(25, 68, 1),
(26, 68, 1),
(27, 68, 1),
(26, 69, 2),
(31, 70, 3),
(25, 70, 6),
(30, 71, 3),
(31, 72, 3),
(28, 73, 12),
(29, 74, 3),
(26, 74, 1),
(25, 75, 1),
(31, 75, 1),
(25, 76, 6),
(28, 77, 1),
(27, 77, 1);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `history_view`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `history_view` (
`tanggal_transaksi` datetime
,`id_transaksi` int(11)
,`nama_barang` mediumtext
,`harga_jual` decimal(42,0)
,`harga_beli` decimal(42,0)
,`total_qty` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'makanan'),
(2, 'minuman'),
(3, 'snack');

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `nama_supplier` varchar(25) NOT NULL,
  `telepon` varchar(14) NOT NULL,
  `tanggal_input` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama_supplier`, `telepon`, `tanggal_input`) VALUES
(8, 'pemilik', '081919191', '2023-12-14 14:24:23'),
(9, 'dani', '085738288', '2023-12-06 00:08:09');

--
-- Trigger `supplier`
--
DELIMITER $$
CREATE TRIGGER `delete_barang_by_supplier` BEFORE DELETE ON `supplier` FOR EACH ROW DELETE FROM barang WHERE id_supplier = OLD.id_supplier
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal_transaksi` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_user`, `tanggal_transaksi`) VALUES
(49, 2, '2023-12-16 08:42:04'),
(50, 2, '2023-12-16 08:43:06'),
(51, 2, '2023-12-16 08:46:13'),
(52, 2, '2023-12-16 08:46:56'),
(53, 2, '2023-12-16 11:14:58'),
(54, 2, '2023-12-16 11:17:02'),
(55, 2, '2023-12-16 11:36:42'),
(56, 2, '2023-12-16 11:38:17'),
(57, 2, '2023-12-16 12:59:46'),
(58, 2, '2023-12-16 13:01:39'),
(59, 2, '2023-12-16 13:10:52'),
(60, 2, '2023-12-16 13:27:49'),
(61, 2, '2023-12-16 13:31:43'),
(62, 2, '2023-12-16 13:32:21'),
(63, 2, '2023-12-16 16:01:16'),
(64, 2, '2023-12-16 16:02:34'),
(65, 2, '2023-12-16 16:03:34'),
(66, 2, '2023-12-16 16:21:41'),
(67, 2, '2023-12-16 16:30:37'),
(68, 2, '2023-12-16 16:32:19'),
(69, 2, '2023-12-16 16:35:42'),
(70, 2, '2023-12-16 16:37:17'),
(71, 2, '2023-12-17 04:14:07'),
(72, 2, '2023-12-17 04:14:43'),
(73, 2, '2023-12-17 04:23:25'),
(74, 2, '2023-12-17 04:25:05'),
(75, 2, '2023-12-17 07:30:54'),
(76, 2, '2023-12-17 08:04:18'),
(77, 2, '2023-12-17 17:19:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` char(32) NOT NULL,
  `level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `level`) VALUES
(1, 'pemilik', '58399557dae3c60e23c78606771dfa3d', 1),
(2, 'admin', '21232f297a57a5a743894a0e4a801fc3', 2);

-- --------------------------------------------------------

--
-- Struktur untuk view `history_view`
--
DROP TABLE IF EXISTS `history_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `history_view`  AS SELECT `t`.`tanggal_transaksi` AS `tanggal_transaksi`, `t`.`id_transaksi` AS `id_transaksi`, CASE WHEN octet_length(group_concat(' ',`b`.`nama_barang` separator ',')) > 40 THEN concat(left(group_concat(' ',`b`.`nama_barang` separator ','),40),' ...') ELSE group_concat(' ',`b`.`nama_barang` separator ',') END AS `nama_barang`, sum(`dt`.`qty` * `b`.`harga_jual`) AS `harga_jual`, sum(`dt`.`qty` * `b`.`harga_beli`) AS `harga_beli`, sum(`dt`.`qty`) AS `total_qty` FROM ((`transaksi` `t` join `detail_transaksi` `dt` on(`t`.`id_transaksi` = `dt`.`id_transaksi`)) join `barang` `b` on(`dt`.`id_barang` = `b`.`id_barang`)) GROUP BY `t`.`id_transaksi`, `t`.`tanggal_transaksi` ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `id_supplier` (`id_supplier`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indeks untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`),
  ADD CONSTRAINT `barang_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`);

--
-- Ketidakleluasaan untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`);

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
