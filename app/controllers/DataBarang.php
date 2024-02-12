<?php

class DataBarang extends Controller
{
    public function __construct () {
		// Jika belum login maka jangan biarkan user masuk
		if ( !isset($_SESSION["level"]) && !isset($_SESSION["user_session"])) {
            header("Location: http://localhost/mvc/public");
			exit;
		}
	}

    public function index()
    {
        $this->view('template/header');

        $data['suppliers'] = $this->model('SupplierModel')->getSuppliers();
        $searchTerm = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
            $searchTerm = $_POST['search'];
            $data['barang'] = $this->model('BarangModel')->searchDataBarang($searchTerm);
        } elseif (isset($_POST['kategori'])) {
            $kategori = $_POST['kategori'];
            $data['barang'] = $this->model('BarangModel')->getDataBarangByCategory($kategori);
        } else {
            $data['barang'] = $this->model('BarangModel')->getDataBarang();
        }
        
        $this->view('data_barang/index', $data);
        $this->view('template/footer');
    }


    public function tambahBarang(){
        if (isset($_POST['submit']) && $_POST['submit'] == 'simpan') {
            // Ambil data dari form
            $namaBarang = $_POST['nama-barang'];
            $kategori = $_POST['kategori'];
            $hargaBeli = $_POST['harga-beli'];
            $hargaJual = $_POST['harga-jual'];
            $supplier = $_POST['supplier'];
            $stock = $_POST['stock'];
            $fotoBarang = $_FILES['foto-barang']['name'];

            // Simpan foto ke direktori tertentu (misalnya 'uploads/')
            $targetDir = "../public/uploads/";
            $targetFile = $targetDir . basename($_FILES["foto-barang"]["name"]);
            move_uploaded_file($_FILES["foto-barang"]["tmp_name"], $targetFile);

            // Panggil method untuk menambahkan barang
            if ($this->model('BarangModel')->tambahBarang($namaBarang, $kategori,$hargaBeli, $hargaJual,$supplier, $stock, $fotoBarang)) {
                // Redirect atau tampilkan pesan berhasil
                Flasher::setFlash('Data Barang Berhasil Ditambahkan', 'Sukses', 'fas fa-check-circle', 'success');
                header("Location: ../DataBarang");
                exit();
            } else {
                // Tampilkan pesan gagal
                Flasher::setFlash('Data Barang Gagal Ditambahkan', 'Gagal', 'fas fa-times-circle', 'danger');
                header("Location: ../DataBarang");
                exit();
            }
        }
    }

    public function deleteBarang($id){
        $idBarangToDelete = $id;

        // Memanggil method untuk menghapus barang
        if ($this->model('BarangModel')->deleteBarang($idBarangToDelete)) {
            // Redirect atau tampilkan pesan berhasil
            Flasher::setFlash('Data Barang Berhasil Dihapus', 'Sukses', 'fas fa-check-circle', 'warning');
            header('Location: ' . BASEURL . '/DataBarang');
            exit();
        } else {
            // Tampilkan pesan gagal
            Flasher::setFlash('Data Barang Gagal Dihapus', 'Gagal', 'fas fa-times-circle', 'danger');
            header('Location: ' . BASEURL . '/DataBarang');
            exit();
        }
    }

    public function updateBarang(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ambil data dari form
            $idBarang = $_POST['idBarang'];
            $namaBarang = $_POST['nama-barang'];
            $idKategori = $_POST['id_kategori'];
            $hargaBeli = $_POST['harga-beli'];
            $hargaJual = $_POST['harga-jual'];
            $supplier = $_POST['supplier'];
            $stokBarang = $_POST['stock'];
            $gambarBarang = '';
    
            // Cek apakah ada file yang di-upload
            if (isset($_FILES['foto-barang']) && $_FILES['foto-barang']['error'] == 0) {
                // Simpan foto ke direktori tertentu (misalnya 'uploads/')
                $targetDir = "../public/uploads/";
                $gambarBarang = basename($_FILES["foto-barang"]["name"]);
                $targetFile = $targetDir . $gambarBarang;
                
                if (move_uploaded_file($_FILES["foto-barang"]["tmp_name"], $targetFile)) {
                    // File berhasil di-upload
                } else {
                    echo "File upload failed.";
                    exit();
                }
            } else {
                // If no new photo uploaded, retain the existing photo
                $existingData = $this->model('BarangModel')->getBarangById($idBarang);
                $gambarBarang = $existingData['gambar'];
            }
    
            // Panggil method untuk memperbarui barang
            $this->model('BarangModel')->updateBarang($idBarang, $namaBarang, $idKategori, $hargaBeli,$hargaJual,$supplier, $stokBarang, $gambarBarang);
    
            Flasher::setFlash('Data Barang Berhasil Diedit', 'Sukses', 'fas fa-check-circle', 'success');
            header('Location: ' . BASEURL . '/DataBarang');
            exit();
        } else {
            Flasher::setFlash('Data Barang Gagal Diedit', 'Gagal', 'fas fa-times-circle', 'danger');
            header('Location: ' . BASEURL . '/DataBarang');
            exit();
        }
    }


    
}