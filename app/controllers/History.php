<?php

class History extends Controller
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

      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filter2'])) {
        $month = $_POST['bulan'];
        $years = $_POST['tahun'];

        $data['history'] = $this->model('HistoryModel')->searchByMonthYear($month, $years);
        $data['total'] = $this->model('HistoryModel')->hitungTotal(0, '', $month, $years);

      } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filter1'])) {
        $id = $_POST['supplier'];
        $date = $_POST['date'];

        $data['history'] = $this->model('HistoryModel')->searchBySupplierDate($id,$date);
        $data['total'] = $this->model('HistoryModel')->hitungTotal($id, "$date", 0, 0);

      } else {
        $data['history'] = $this->model('HistoryModel')->getHistory();
        $data['total'] = $this->model('HistoryModel')->hitungTotal(0, '', 0, 0);
      }

      $data['history2'] = $this->model('HistoryModel')->getHistory();
      $data['suppliers'] = $this->model('SupplierModel')->getSuppliers();
      $this->view('history/index',$data);
      $this->view('template/footer');
  }

    public function detailHistory()
    {
      // Check if the request is an AJAX request
      if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_transaksi'])) {
          $id_transaksi = $_POST['id_transaksi'];

          // Fetch details from the model
          $details = $this->model('HistoryModel')->getDetailHistoryById($id_transaksi);

          // Return the details as JSON
          echo json_encode($details);
      }
    }
}