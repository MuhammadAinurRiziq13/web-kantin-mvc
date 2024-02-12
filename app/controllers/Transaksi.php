<?php

class Transaksi extends Controller
{
    public function __construct () {

		// Jika belum login maka jangan biarkan user masuk
		if ( !isset($_SESSION["level"]) && !isset($_SESSION["user_session"]) && $_SESSION["level"] === '2') {
            header("Location:" .BASEURL);
			exit;
		} else if ($_SESSION["level"] == 1) {
            header('Location: ' . BASEURL . '/Dashboard');
            exit;
        }
	}

    public function index()
    {
        $this->view('template/header');
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

        if (!isset($_SESSION["cart"]) || !is_array($_SESSION["cart"])) {
            $_SESSION["cart"] = array();
        }

        $data['date'] = $this->model('TransaksiModel')->getCurrentTime();
        $data['totalPrice'] = $this->calculateTotalPrice($_SESSION["cart"]);

        // Membuat array baru untuk menyimpan data barang untuk keranjang
        $data['cartItems'] = array();

        // Loop untuk menambahkan informasi barang ke setiap item di keranjang
        foreach ($_SESSION["cart"] as $cartItem) {
            $id_barang = $cartItem["id_barang"];
            $itemQuantity = $cartItem["quantity_" . $id_barang];

            // Query ke database untuk mendapatkan informasi barang
            $item = $this->model('BarangModel')->getBarangById($id_barang);
            $item['quantity'] = $itemQuantity; // Menambahkan jumlah barang ke array

            // Menambahkan data barang ke array
            $data['cartItems'][] = $item;
        }

        $this->view('transaksi/index', $data);

        $this->view('template/footer');
    }

    public function tambahKeranjang(){
      // Handle adding items to the session cart
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
        $id_barang_to_add = $_POST['id_barang'];

        // Check if the item is already in the cart
        $item_exists_in_cart = false;
        foreach ($_SESSION["cart"] as $cartItem) {
            if ($cartItem["id_barang"] == $id_barang_to_add) {
                $item_exists_in_cart = true;
                break;
            }
        }

        // If the item is not in the cart, add it
        if (!$item_exists_in_cart) {
            // Include the product ID in the quantity name
            $quantity_to_add = isset($_POST['quantity_' . $id_barang_to_add]) ? $_POST['quantity_' . $id_barang_to_add] : 1;

            $_SESSION["cart"][] = array("id_barang" => $id_barang_to_add, "quantity_" . $id_barang_to_add => $quantity_to_add);
        }

        header('Location: ' . BASEURL . '/Transaksi');
        exit();
      }

    }

    public function hapusKeranjang(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_cart'])) {
            $id_barang_to_remove = $_POST['id_barang'];
        
            // Ensure $_SESSION["cart"] is initialized as an array
            if (!isset($_SESSION["cart"]) || !is_array($_SESSION["cart"])) {
                $_SESSION["cart"] = array();
            }
        
            // Loop through the cart items and remove the matching item
            foreach ($_SESSION["cart"] as $key => $cartItem) {
                if ($cartItem["id_barang"] == $id_barang_to_remove) {
                    unset($_SESSION["cart"][$key]);
                    break;
                }
            }
        
            header('Location: ' . BASEURL . '/Transaksi');
            exit();
        
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_cart'])) {
            // Clear the entire shopping cart
            $_SESSION["cart"] = array();
            
            header('Location: ' . BASEURL . '/Transaksi');
            exit();
        }        
    }

    public function calculateTotalPrice($cart) {
      $totalPrice = 0;
  
      if (!empty($cart)) {
          foreach ($cart as $cartItem) {
              $id_barang = $cartItem["id_barang"];
              $itemQuantity = $cartItem["quantity_" . $id_barang];
  
              $item = $this->model('BarangModel')->getBarangById($id_barang);
              $itemPrice = $item['harga_jual'] * $itemQuantity;
  
              $totalPrice += $itemPrice;
          }
      }
  
      return $totalPrice;
    }

    public function checkout()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bayar'])) {
            // Get the money paid by the user
            $uangBayar = isset($_POST['uangBayar']) ? $_POST['uangBayar'] : 0;

            // Check if the cart is not empty
            if (!empty($_SESSION["cart"])) {
                // Calculate total price using the function
                $totalPrice = $this->calculateTotalPrice($_SESSION["cart"]);

                // Check if the payment is sufficient
                if ($uangBayar >= $totalPrice) {
                    // Perform the transaction and insert data into the database
                    $idUser = 2; // Replace with the actual user ID

                    // Create an array of data to be passed to the model
                    $transactionData = [
                        'idUser' => $idUser,
                        'uangBayar' => $uangBayar,
                    ];

                    // Call the model method to add the transaction
                    $result = $this->model('TransaksiModel')->addTransaction($transactionData);

                    // Step 2: Insert into 'detail_transaksi' table for each item in the cart
                    foreach ($_SESSION["cart"] as $cartItem) {
                        $idBarang = $cartItem["id_barang"];
                        $quantity = $cartItem["quantity_" . $idBarang];
    
                        // Update the stock in 'barang' table
                        $this->model('BarangModel')->updateStock($idBarang, $quantity);
    
                        // Insert into 'detail_transaksi' table
                        $this->model('TransaksiModel')->addDetailTransaksi($idBarang, $result['idTransaksi'], $quantity);
                    }

                    if ($result['success']) {
                        // Store values in session
                        $_SESSION['last_transaction_id'] = $result['idTransaksi'];
                        $_SESSION['totalTunai'] = $uangBayar;

                        // Clear the entire shopping cart
                        $_SESSION["cart"] = array();

                        // Return a JSON response
                        echo json_encode(['success' => true, 'message' => 'Transaksi Berhasil']);
                    } else {
                        // Return an error JSON response
                        echo json_encode(['success' => false, 'message' => $result['message']]);
                    }
                } else {
                    // Return an error JSON response
                    echo json_encode(['success' => false, 'message' => 'Uang Anda Kurang']);
                }
            } else {
                // Return an error JSON response
                echo json_encode(['success' => false, 'message' => 'Keranjang Anda Kosong']);
            }

            // Ensure that no additional output is sent
            die();
        }
    }

    public function strukTransaksi()
    {
        // Check if 'last_transaction_id' is set in the session
        if (!isset($_SESSION['last_transaction_id'])) {
            // Handle the case where 'last_transaction_id' is not set
            echo json_encode(['error' => 'Transaction ID not found']);
            return;
        }

        $idTransaksi = $_SESSION['last_transaction_id'];

        // Fetch details from the model
        $struk = $this->model('TransaksiModel')->getStrukById($idTransaksi);

        // Return the details as JSON
        echo json_encode($struk);
    }


    


    
}