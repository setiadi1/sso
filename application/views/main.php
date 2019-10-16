<?php $this->load->view('template/header.php') ?>

<?php $this->load->view('template/nav.php') ?>

<?php $this->load->view('template/sidebar.php') ?>

<!-- Load file contents -->
<?php 
	$filecontent = APPPATH.'views/contents/'.$filecontentname.'.php';
	if (file_exists($filecontent)) {
		$this->load->view('contents/'.$filecontentname.'.php');
	}
?>

<!-- Load default js -->
<?php 
	$themejs = APPPATH.'views/template/templatejs.php';
	if (file_exists($themejs)) {
		$this->load->view('template/templatejs.php');
	}
?>

<!-- Load custom js -->
<?php
	$filejs = APPPATH.'views/contents/'.$filecontentname.'_js.php';
	if (file_exists($filejs)) {
		$this->load->view('contents/'.$filecontentname.'_js.php');
	}
?>

<?php $this->load->view('template/footer.php') ?>