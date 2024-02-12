<?php
class HistoryModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }


    public function getHistory() {
        $sql = "SELECT * FROM history_view;";
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $result ? $result : array();
    }

    public function searchBySupplierDate($id, $date) {
        // $sql = "CALL search_history_atas(:id, :date);";
        $sql = "CALL search_history_atas($id, '$date');";
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute()) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        } else {
            return array();
        }
    }

    public function searchByMonthYear($month, $year) {
        $sql = "CALL search_history_bawah($month, $year);";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt->execute()) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        } else {
            return array();
        }
    }

    public function getDetailHistoryById($idTransaksi) {
        $sql = "SELECT dt.*, b.nama_barang, b.harga_jual, (dt.qty * b.harga_jual) as total_harga FROM detail_transaksi dt
                INNER JOIN barang b ON dt.id_barang = b.id_barang
                WHERE dt.id_transaksi = :idTransaksi";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':idTransaksi', $idTransaksi, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result ? $result : array();
    }
 
    public function hitungTotal($id, $date, $month, $year) {
        $sql = "
        SELECT 
            hitung_total_harga_beli ($id, '$date', $month, $year) AS total_beli,
            hitung_total_harga_jual ($id, '$date', $month, $year) AS total_jual,
            hitung_selisih_harga ($id, '$date', $month, $year) AS total_keuntungan;
        ";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt->execute()) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        } else {
            return array();
        }
    }
}
?>