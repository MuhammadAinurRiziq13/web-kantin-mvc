<?php

class SupplierModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function tambahSupplier($nama,$telepon)
    {
        $datetime = date('Y-m-d H:i:s');
        $sql = "INSERT INTO supplier (nama_supplier, telepon, tanggal_input) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $nama, PDO::PARAM_STR);
        $stmt->bindParam(2, $telepon, PDO::PARAM_STR);
        $stmt->bindParam(3, $datetime, PDO::PARAM_STR);

        // Eksekusi query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getSuppliers()
    {
        $sql = "SELECT * FROM supplier";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $suppliers = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $suppliers[] = $row;
        }

        return $suppliers;
    }

    public function deleteSuppliers($idSupplier)
    {
        // Hapus barang berdasarkan ID
        $sql = "DELETE FROM supplier WHERE id_supplier = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $idSupplier, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getSupplierById($idSupplier)
    {
        // Ambil data supplier berdasarkan ID
        $sql = "SELECT * FROM supplier WHERE id_supplier = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $idSupplier, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function updateSupplier($idSupplier, $nama,$telepon)
    {
        // Update data supplier berdasarkan ID
        $datetime = date('Y-m-d H:i:s');
        $sql = "UPDATE supplier SET nama_supplier = ?, tanggal_input = ?, telepon = ? WHERE id_supplier = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $nama, PDO::PARAM_STR);
        $stmt->bindParam(2, $datetime, PDO::PARAM_STR);
        $stmt->bindParam(3, $telepon, PDO::PARAM_STR);
        $stmt->bindParam(4, $idSupplier, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function searchDataSupplier($searchTerm)
    {
        // Saring data supplier berdasarkan nama_supplier
        $sql = "SELECT * FROM supplier WHERE nama_supplier LIKE ?";
        $stmt = $this->db->prepare($sql);

        // Tambahkan tanda persen (%) pada awal dan akhir search term untuk mencari nama_supplier yang mengandung
        $searchTerm = "%$searchTerm%";
        $stmt->bindParam(1, $searchTerm, PDO::PARAM_STR);

        // Eksekusi query
        $stmt->execute();

        // Ambil hasil
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getSupplier()
    {
        $query = "SELECT * FROM supplier ORDER BY nama_supplier ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $options;
    }
}