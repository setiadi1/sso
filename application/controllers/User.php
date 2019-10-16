<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library(array('pagination'));
		$this->load->model('User_model');
	}

	public function index() {
		header("Location: user/list");
	}

	public function list() {
		$data['filecontentname'] = 'users_list';
		$data['users'] = $this->User_model->users();
		$data['method'] = ucfirst($this->router->fetch_method());
		$this->load->view('main', $data);
	}

	public function load_users($rowno = 0) {
		$rowperpage = 5;
		if ($rowno != 0) {
			$rowno = ($rowno - 1) * $rowperpage;
		}

		$allcount = $this->User_model->users_count();
		$users_record = $this->User_model->users_details($rowno, $rowperpage);

		$config['base_url'] = site_url();
		$config['reuse_query_string'] = true;
		$config['use_page_numbers'] = true;
		$config['total_rows'] = $allcount;
		$config['per_page'] = $rowperpage;
		// $config['num_links']= 15;

		$config['query_string_segment'] = 'start';
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = '<i class="fad fa-arrow-to-left arrowsize1"></i>';
		$config['first_tag_open'] = '<li class="page-item page-link">';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = '<i class="fad fa-arrow-to-right arrowsize1"></i>';
		$config['last_tag_open'] = '<li class="page-item page-link">';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = '<i class="fad fa-arrow-alt-right arrowsize1"></i>';
		$config['next_tag_open'] = '<li class="page-item page-link">';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '<i class="fad fa-arrow-alt-left arrowsize1"></i>';
		$config['prev_tag_open'] = '<li class="page-item page-link">';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="page-item page-link">';
		$config['num_tag_close'] = '</li>';

		// initialize
		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();
		$data['result'] = $users_record;
		$data['row'] = $rowno;
		echo json_encode($data);
	}

	public function roles() {
		$data['filecontentname'] = 'users_roles';
		$data['roles'] = $this->User_model->roles();
		$data['method'] = ucfirst($this->router->fetch_method());
		$this->load->view('main', $data);
	}

	public function load_roles($rowno = 0) {
		$rowperpage = 5;
		if ($rowno != 0) {
			$rowno = ($rowno - 1) * $rowperpage;
		}

		$allcount = $this->User_model->roles_count();
		$users_record = $this->User_model->roles_details($rowno, $rowperpage);

		$config['base_url'] = site_url();
		$config['reuse_query_string'] = true;
		$config['use_page_numbers'] = true;
		$config['total_rows'] = $allcount;
		$config['per_page'] = $rowperpage;

		$config['query_string_segment'] = 'start';
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = '<i class="fad fa-arrow-to-left"></i>';
		$config['first_tag_open'] = '<li class="page-item page-link">';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = '<i class="fad fa-arrow-to-right"></i>';
		$config['last_tag_open'] = '<li class="page-item page-link">';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = '<i class="fad fa-arrow-alt-right"></i>';
		$config['next_tag_open'] = '<li class="page-item page-link">';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '<i class="fad fa-arrow-alt-left"></i>';
		$config['prev_tag_open'] = '<li class="page-item page-link">';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="page-item page-link">';
		$config['num_tag_close'] = '</li>';

		// initialize
		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();
		$data['result'] = $users_record;
		$data['row'] = $rowno;
		echo json_encode($data);
	}

	public function add_role() {
		$role = strtolower($_POST['role']);
		$role_name = ucwords($_POST['role_name']);
		$aid = $_POST['aid'];
		$perms = $_POST['perms'];

		$this->load->model('User_model');
		$data = $this->User_model->add_role($role, $role_name, $aid, $perms);
		echo json_encode($data);
		// var_dump($data);
	}

	public function edit_role1() {
		$aid = $_POST['aid']; 
		$rid = $_POST['rid'];
		$data = $this->User_model->edit_role1($aid, $rid);
		echo json_encode($data);
	}

	public function edit_role2() {
		$aid = $_POST['aid']; 
		$rid = $_POST['rid'];
		$data = $this->User_model->edit_role2($aid, $rid);
		echo json_encode($data);
	}

	public function edit_role3() {
	    $aid = $_POST['aid'];
	    $app_name = $_POST['app_name'];
	    $rid = $_POST['rid'];
	    $role = $_POST['role'];
	    $role_name = $_POST['role_name'];
	    $perms = $_POST['perms'];

		$data = $this->User_model->edit_role3($aid, $app_name, $rid, $role, $role_name, $perms);
		echo json_encode($data);
	}

	public function disablerole() {
		print_r($_POST);
	}

	public function add_permission() {
		$perm = strtolower($_POST['perm']);
	    $perm_name = ucwords($_POST['perm_name']);
	    $perm_group = ucwords($_POST['perm_group']);
	    $aid = $_POST['aid'];

	    $this->load->model('User_model');
		$data = $this->User_model->add_permission($perm, $perm_name, $perm_group, $aid);
		echo json_encode($data);
	}

	public function edit_perm1() {
		$pid = $_POST['pid']; 
		$data = $this->User_model->edit_perm1($pid);
		echo json_encode($data);
	}

	public function permissions() {
		$data['filecontentname'] = 'users_perm';
		$data['roles'] = $this->User_model->roles();
		$data['method'] = ucfirst($this->router->fetch_method());
		$this->load->view('main', $data);
	}

	public function load_permissions($rowno = 0) {
		$rowperpage = 5;
		if ($rowno != 0) {
			$rowno = ($rowno - 1) * $rowperpage;
		}

		$allcount = $this->User_model->perms_count();
		$users_record = $this->User_model->perms_details($rowno, $rowperpage);

		$config['base_url'] = site_url();
		$config['reuse_query_string'] = true;
		$config['use_page_numbers'] = true;
		$config['total_rows'] = $allcount;
		$config['per_page'] = $rowperpage;
		// $config['num_links']= 15;

		$config['query_string_segment'] = 'start';
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = '<i class="fad fa-arrow-to-left"></i>';
		$config['first_tag_open'] = '<li class="page-item page-link">';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = '<i class="fad fa-arrow-to-right"></i>';
		$config['last_tag_open'] = '<li class="page-item page-link">';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = '<i class="fad fa-arrow-alt-right"></i>';
		$config['next_tag_open'] = '<li class="page-item page-link">';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '<i class="fad fa-arrow-alt-left"></i>';
		$config['prev_tag_open'] = '<li class="page-item page-link">';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="page-item page-link">';
		$config['num_tag_close'] = '</li>';

		// initialize
		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();
		$data['result'] = $users_record;
		$data['row'] = $rowno;
		echo json_encode($data);
	}

	public function login() {
		$this->load->view('test');
	}

	public function logout() {
		$json = "";

	}

	public function permis_by_aid() {
		$aid = $_POST['aid'];
		$data = $this->User_model->permis_by_aid($aid);
		echo json_encode($data);
	}

	public function user_roles1() {
		$uid = $_POST['uid'];
		$data = $this->User_model->user_roles1($uid);
		echo json_encode($data);
	}

	public function user_roles2() {
		$aid = $_POST['aid'];
		$uid = $_POST['user_id'];
		$data = $this->User_model->user_roles2($aid, $uid);
		echo json_encode($data);
	}

	public function user_roles3() {
		$uid = $_POST['uid'];
		$data = $this->User_model->user_roles3($uid);
		echo json_encode($data);
	}

	public function set_roles() {
		$data = $_POST;
		$data = $this->User_model->set_roles($_POST);
		echo json_encode($data);
	}

}
