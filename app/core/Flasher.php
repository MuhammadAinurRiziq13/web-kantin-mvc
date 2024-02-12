<?php 
class Flasher{
	public static function setFlash($pesan, $ket, $icon, $bg){
		$_SESSION['flash'] = [
			'pesan'	=> $pesan,
			'ket'	=> $ket,
			'icon'	=> $icon,
			'bg'	=> $bg
		];
	}

	public static function flash(){
		if ( isset($_SESSION['flash']) ) {
			echo '
			    <div class="alert alert-' . $_SESSION['flash']['bg'] . ' alert-dismissible fade show my-2" role="alert">
			        <strong><i class="' . $_SESSION['flash']['icon'] . '"></i> ' . $_SESSION['flash']['ket'] . '!</strong> ' . $_SESSION['flash']['pesan'] .'.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			    </div>
			';
			unset($_SESSION['flash']);
		}
	}
}
