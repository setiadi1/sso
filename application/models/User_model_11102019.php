<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}

	public function users() {
		$this->db->from('users');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function users_count() {
		$this->db->select('count(*) as allcount');
		$this->db->from('users');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result[0]['allcount'];
	}

	public function users_details($rowno, $rowperpage) {
		$this->db->select('user_id, username, name, nip, pangkat, status');
		$this->db->from('users');
		$this->db->limit($rowperpage, $rowno);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function add_role($role, $role_name, $aid, $perms) {
		$arrperms = explode(',', $perms);
		$this->db->select('*');
		$this->db->from('roles');
		$this->db->where('role', $role);
		$this->db->where('app_id', $aid);
		$data = $this->db->get();
		if ($data->num_rows() > 0) {
			echo 'This role already exists';
		} else {
			
			$this->db->trans_begin();

			$role = array(
				'role' => $role,
				'role_name' => $role_name,
				'app_id' => $aid
			);
			// query1
			$this->db->insert('roles', $role);

			$rid = $this->db->insert_id();

			if ($perms != '') {
				foreach ($arrperms as $perm) {
					$settings = array(
						'app_id' => $aid,
						'role_id' => $rid,
						'perm_id' => $perm
					);
					// query2
					$this->db->insert('roles_settings', $settings);
				}
			}
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
			    return "Failed";
			} else {
			    $this->db->trans_commit();
			    return "Succeeded";
			}
		}
	}

	public function roles() {
		$this->db->from('roles');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function roles_count() {
		$this->db->select('count(*) as allcount');
		$this->db->from('roles');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result[0]['allcount'];
	}

	public function roles_details($rowno, $rowperpage) {
		$this->db->select('roles.role_id, roles.role, roles.role_name, roles.app_id, roles.status, apps.app_name, COUNT(users_roles.user_id) active_users');
		$this->db->from('roles');
		$this->db->join('roles_settings', 'roles.role_id = roles_settings.role_id', 'left');
		$this->db->join('apps', 'roles.app_id = apps.id', 'left');
		$this->db->join('users_roles', 'roles_settings.role_set_id = users_roles.role_set_id', 'left');
		$this->db->group_by('roles.role_id');
		$this->db->limit($rowperpage, $rowno);
		$query = $this->db->get();
		return $query->result_array();
	}

	// public function edit_role($role, $role_name, $aid) {
	// 	$this->db->select('*');
	// 	$this->db->from('roles');
	// 	$this->db->where('role', $role);
	// 	$this->db->where('app_id', $aid);
	// 	$data = $this->db->get();
	// 	if ($data->num_rows() > 0) {
	// 		echo 'This role already exists';
	// 	} else {
	// 		$role = array(
	// 			'role' => $role,
	// 			'role_name' => $role_name,
	// 			'app_id' => $aid
	// 		);
	// 		$this->db->insert('roles', $role);
	// 	}
	// }

	public function perms_count() {
		$this->db->select('count(*) as allcount');
		$this->db->from('permissions');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result[0]['allcount'];
	}

	public function perms_details($rowno, $rowperpage) {
		$this->db->select('permissions.perm_id, permissions.perm, permissions.perm_name, permissions.app_id, apps.app_name');
		$this->db->from('permissions');
		$this->db->join('apps', 'permissions.app_id = apps.app_id', 'inner');
		$this->db->limit($rowperpage, $rowno);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function permis_by_aid($aid) {
		$this->db->select('permissions.perm_id, permissions.perm, permissions.perm_name, permissions.perm_group, apps.app_id, apps.app_name');
		$this->db->from('permissions');
		$this->db->join('apps', 'permissions.app_id = apps.app_id', 'inner');
		$this->db->where('apps.app_id', $aid);
		$query = $this->db->get();
		$f = $query->result_array();

		$data = array();
		foreach ($f as $row) {
			$data[$row['perm_id']] = array('value' => $row['perm_name'], 'group' => $row['perm_group']);
		}

		return $data;
	} 

	public function edit_role1($aid, $rid) {
		$sql = $this->db->query("SELECT t.app_id, t.app_name, t.role_id, t.role_name, permissions.perm_name, permissions.perm_group, permissions.perm_id, t.status FROM (SELECT roles_settings.app_id, apps.app_name, roles_settings.role_id, roles.role_name, roles_settings.perm_id, roles_settings.status FROM roles_settings INNER JOIN apps ON roles_settings.app_id = apps.app_id INNER JOIN roles ON roles_settings.role_id = roles.role_id WHERE roles_settings.app_id = '$aid' AND roles_settings.role_id = '$rid') AS t RIGHT JOIN permissions ON t.perm_id = permissions.perm_id WHERE permissions.app_id = '$aid' AND (t.status = 1 OR t.status IS NULL)");
		$arr = $sql->result_array();
		$checkaid = [];
		foreach ($arr as $value) {
			if (isset($value['app_id'])) {
				$checkaid[] = $value['app_id'];
			}
		}
		$data = array();
		if (!empty($checkaid)) { 
			foreach ($arr as $value) {
				if (!isset($value['app_id'])) {
					$data[$value['perm_id']] = array('value' => $value['perm_name'], 'group' => $value['perm_group'], 'selected' => false);
				}
				if (isset($value['app_id'])) {
					$data[$value['perm_id']] = array('value' => $value['perm_name'], 'group' => $value['perm_group'], 'selected' => true);
				}
			}
		} else {
			$data[$value['perm_id']] = array('value' => $value['perm_name'], 'group' => $value['perm_group'], 'selected' => false);
		}
		return $data;
	}

	public function edit_role2($aid, $rid) {
		$sql = $this->db->query("SELECT apps.app_id, roles.role_id, apps.app_name, roles.role, roles.role_name FROM roles INNER JOIN apps ON roles.app_id = apps.app_id WHERE roles.role_id = '$rid' AND apps.app_id = '$aid' LIMIT 1");
		$arr = $sql->result_array();
		return $arr;
	}

	public function edit_role3($aid, $app_name, $rid, $role, $role_name, $perms) {
		$this->db->trans_begin();
		$role = array(
			'role' => $role,
			'role_name' => $role_name,
			'app_id' => $aid
		);
		// query1
		$this->db->where('role_id', $rid);
		$this->db->update('roles', $role);

		// check if perms not empty
		// check if settings exist 
		// if status 1, update 
		// if status 0, enable and update
		
		if ($perms != '') {
			foreach ($arrperms as $perm) {
				$settings = array(
					'app_id' => $aid,
					'role_id' => $rid,
					'perm_id' => $perm
				);
				// query2
				$this->db->insert('roles_settings', $settings);
			}
		}
		if ($this->db->trans_status() === FALSE) {
		    $this->db->trans_rollback();
		    return "Failed";
		} else {
		    $this->db->trans_commit();
		    return "Succeeded";
		}
	}
}