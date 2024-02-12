<?php
class TransaksiModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function addTransaction($data)
    {
        // Insert into 'transaksi' table
        $tanggalTransaksi = date("Y-m-d H:i:s"); // Current date and time

        $insertTransaksiQuery = "INSERT INTO transaksi (id_user, tanggal_transaksi) VALUES (:idUser, :tanggalTransaksi)";
        $stmt = $this->db->prepare($insertTransaksiQuery);
        $stmt->bindParam(':idUser', $data['idUser'], PDO::PARAM_INT);
        $stmt->bindParam(':tanggalTransaksi', $tanggalTransaksi, PDO::PARAM_STR);
        $stmt->execute();

        // Get the last inserted ID (id_transaksi)
        $idTransaksi = $this->db->getLastInsertId();

        // Return success and the ID of the transaction
        return ['success' => true, 'idTransaksi' => $idTransaksi];
    }


    public function addDetailTransaksi($idBarang, $idTransaksi, $quantity) {
        $insertDetailQuery = "INSERT INTO detail_transaksi (id_barang, id_transaksi, qty) VALUES (:idBarang, :idTransaksi, :quantity)";
        $stmt = $this->db->prepare($insertDetailQuery);
        $stmt->bindParam(':idBarang', $idBarang, PDO::PARAM_INT);
        $stmt->bindParam(':idTransaksi', $idTransaksi, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getDetailTransaksiById($idTransaksi)
    {
        $sql = "SELECT dt.*, b.nama_barang, b.harga_jual, (dt.qty * b.harga_jual) as total_harga FROM detail_transaksi dt
                INNER JOIN barang b ON dt.id_barang = b.id_barang
                WHERE dt.id_transaksi = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idTransaksi]);
        $detailTransaksi = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $detailTransaksi;
    }

    public function getTotalTransaksi() {
        $query = "SELECT COUNT(*) as total_rows FROM history_view";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }

    public function getTotalPemasukan() {
        $query = "SELECT SUM(dt.qty * b.harga_jual) as total_rows
                  FROM detail_transaksi dt
                  JOIN barang b ON dt.id_barang = b.id_barang";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $row['total_rows'];
    }
    

    function getCurrentTime() {
        $namaHari = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
        // Format tanggal
        $tanggal = date("j F Y");
        // Mendapatkan indeks hari saat ini (0 untuk Minggu, 1 untuk Senin, dst.)
        $indeksHari = date("w");
        // Mengambil nama hari dari array menggunakan indeks
        $hariIni = $namaHari[$indeksHari];
        // Mengembalikan waktu saat ini dalam format yang diinginkan
        return $hariIni . ', ' . $tanggal;
    }

    public function getStrukById($idTransaksi)
    {
        $sql = "SELECT dt.*, b.nama_barang, b.harga_jual, (dt.qty * b.harga_jual) as total_harga FROM detail_transaksi dt
                INNER JOIN barang b ON dt.id_barang = b.id_barang
                WHERE dt.id_transaksi = :idTransaksi";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':idTransaksi', $idTransaksi, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result ? $result : array();
    }
    
}
?>
