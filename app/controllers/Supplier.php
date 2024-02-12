<?php

class Supplier extends Controller
{
    public function __construct () {

		// Jika belum login maka jangan biarkan user masuk
		if ( !isset($_SESSION["level"]) && !isset($_SESSION["user_session"])) {
            header("Location:" .BASEURL);
			exit;
		}
	}
    
    public function index(){
        $this->view('template/header');

        // Inisialisasi variabel
        $searchTerm = '';
        // Memeriksa apakah ada data pencarian
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
            // Jika ada data pencarian, panggil fungsi untuk mendapatkan data barang berdasarkan pencarian
            $searchTerm = $_POST['search'];
            $data['suppliers'] = $this->model('SupplierModel')->searchDataSupplier($searchTerm);
        } else {
            // Jika tidak ada data pencarian, panggil fungsi untuk mendapatkan semua data supplier
            $data['suppliers'] = $this->model('SupplierModel')->getSuppliers();
        }

        $this->view('supplier/index',$data);
        $this->view('template/footer');
    }

    public function tambahSupplier(){
        if (isset($_POST['submit']) && $_POST['submit'] == 'Simpan') {
            $supplierName = $_POST['nama-supplier'];
            $supplierTelepon = $_POST['telepon'];

            $result = $this->model('SupplierModel')->tambahSupplier($supplierName,$supplierTelepon);

            if ($result) {
                Flasher::setFlash('Data Supplier Berhasil Ditambahkan', 'Sukses', 'fas fa-check-circle', 'success');
                header("Location: ../Supplier");
                exit();
            } else {
                Flasher::setFlash('Data Supplier Gagal Ditambahkan', 'Gagal', 'fas fa-times-circle', 'danger');
                header("Location: ../Supplier");
                exit();
            }
        }
    }

    public function deleteSupplier($id){
        $idSupplierToDelete = $id;

        // Memanggil method untuk menghapus barang
        if ($this->model('SupplierModel')->deleteSuppliers($idSupplierToDelete)) {
            Flasher::setFlash('Data Supplier Berhasil Dihapus', 'Sukses', 'fas fa-check-circle', 'warning');
            header('Location: ' . BASEURL . '/Supplier');
            exit();
        } else {
            Flasher::setFlash('Data Supplier Gagal Ditambahkan', 'Gagal', 'fas fa-times-circle', 'danger');
            header("Location: ../Supplier");
            exit();
        }
    }

    public function updateSupplier(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ambil data dari form
            $id_supplier = $_POST['id_supplier'];
            $nama_supplier = $_POST['nama_supplier'];
            $telepon = $_POST['telepon'];
    
            // Panggil method untuk memperbarui barang
            $this->model('SupplierModel')->updateSupplier($id_supplier, $nama_supplier,$telepon);
    
            Flasher::setFlash('Data Supplier Berhasil Diedit', 'Sukses', 'fas fa-check-circle', 'success');
            header('Location: ' . BASEURL . '/Supplier');
            exit();
        } else {
            Flasher::setFlash('Data Supplier Gagal Diedit', 'Gagal', 'fas fa-times-circle', 'danger');
            header("Location: ../Supplier");
            exit();
        }
    }
}