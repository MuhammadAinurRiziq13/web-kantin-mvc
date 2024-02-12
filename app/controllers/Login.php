<?php
class Login extends Controller{

    public function __construct () {
		// Jika sudah login maka biarkan user masuk
		if ( isset($_SESSION["level"]) && isset($_SESSION["user_session"])) {
            header("Location:" .BASEURL."/Dashboard");
			exit;
		}
	}

    public function index(){
        $this->view('login/index');
    }

    public function signIn()
    {
        $result = $this->model('UserModel')->getUser($_POST);
            if ($result) {
                $_SESSION['user_session'] = $result['id_user'];
                $_SESSION['level'] = $result['level'];

                echo '<script>alert("Login Sukses");window.location="index.php"</script>'; 
            } else {
                echo '<script>alert("Login Gagal");history.go(-1);</script>';
            }exit;
    }

}

