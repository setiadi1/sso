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

	public function add_permission($perm, $perm_name, $perm_group, $aid) {

		$this->db->trans_begin();

		$this->db->select('*');
		$this->db->from('permissions');
		$this->db->where('perm', $perm);
		$this->db->where('app_id', $aid);
		$data = $this->db->get();
		if ($data->num_rows() > 0) {
			echo 'This permission already exists';
		} else {
			$permission = array(
				'perm' => $perm,
				'perm_name' => $perm_name,
				'perm_group' => $perm_group,
				'app_id' => $aid
			);
			$this->db->insert('permissions', $permission);
			return "Succeeded";
		}

		if ($this->db->trans_status() === FALSE) {
		    $this->db->trans_rollback();
		    return "Failed";
		} else {
		    $this->db->trans_commit();
		}
	}

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
		$sql = $this->db->query("SELECT t.app_id, t.app_name, t.role_id, t.role_name, permissions.perm_name, permissions.perm_group, permissions.perm_id, t.status FROM (SELECT roles_settings.app_id, apps.app_name, roles_settings.role_id, roles.role_name, roles_settings.perm_id, roles_settings.status FROM roles_settings INNER JOIN apps ON roles_settings.app_id = apps.app_id INNER JOIN roles ON roles_settings.role_id = roles.role_id WHERE roles_settings.app_id = '$aid' AND roles_settings.role_id = '$rid') AS t RIGHT JOIN permissions ON t.perm_id = permissions.perm_id WHERE permissions.app_id = '$aid' AND (t.status = 1 OR t.status = 0 OR t.status IS NULL)");
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
				if ($value['status'] == 1) {
					$data[$value['perm_id']] = array('value' => $value['perm_name'], 'group' => $value['perm_group'], 'selected' => true);
				} else {
					$data[$value['perm_id']] = array('value' => $value['perm_name'], 'group' => $value['perm_group'], 'selected' => false);
				}
			}
		} else {
			foreach ($arr as $value) {
				$data[$value['perm_id']] = array('value' => $value['perm_name'], 'group' => $value['perm_group'], 'selected' => false);
			}
		}
		return $data;
	}

	public function edit_role2($aid, $rid) {
		$sql = $this->db->query("SELECT apps.app_id, roles.role_id, apps.app_name, roles.role, roles.role_name FROM roles INNER JOIN apps ON roles.app_id = apps.app_id WHERE roles.role_id = '$rid' AND apps.app_id = '$aid' LIMIT 1");
		$arr = $sql->result_array();
		return $arr;
	}

	public function edit_role3($aid, $app_name, $rid, $role, $role_name, $perms) {
		$arrperms = explode(',', $perms);
		$this->db->trans_begin();
		$role = array(
			'role' => $role,
			'role_name' => $role_name,
			'app_id' => $aid
		);
		// query1
		$this->db->where('role_id', $rid);
		$this->db->update('roles', $role);

		if ($perms != '') {
			foreach ($arrperms as $perm) {
				$this->db->select('*');
				$this->db->from('roles_settings');
				$this->db->where('app_id', $aid);
				$this->db->where('role_id', $rid);
				$this->db->where('perm_id', $perm);

				$data = $this->db->get();
				// return $data;
				if ($data->num_rows() > 0) {
					if ($data->result_array()[0]['status'] == 1) {
						$set1 = array(
							'app_id' => $aid,
							'role_id' => $rid,
							'perm_id' => $perm
						);
						$this->db->where('app_id', $aid);
						$this->db->where('role_id', $rid);
						$this->db->where('perm_id', $perm);
						$this->db->update('roles_settings', $set1);
					} else {
						$set2 = array(
							'app_id' => $aid,
							'role_id' => $rid,
							'perm_id' => $perm,
							'status' => 1
						);
						$this->db->where('app_id', $aid);
						$this->db->where('role_id', $rid);
						$this->db->where('perm_id', $perm);
						$this->db->update('roles_settings', $set2);
					}
				} else {
					$set3 = array(
						'app_id' => $aid,
						'role_id' => $rid,
						'perm_id' => $perm
					);
					$this->db->insert('roles_settings', $set3);
				}
			}
			$set4 = array(
				'status' => 0
			);
			$this->db->where('app_id', $aid);
			$this->db->where('role_id', $rid);
			$this->db->where_not_in('perm_id', $arrperms);
			$this->db->update('roles_settings', $set4);
		} else {
			// if nothing selected
			$set4 = array(
				'status' => 0
			);
			$this->db->where('app_id', $aid);
			$this->db->where('role_id', $rid);
			$this->db->update('roles_settings', $set4);
		}
		if ($this->db->trans_status() === FALSE) {
		    $this->db->trans_rollback();
		    return "Failed";
		} else {
		    $this->db->trans_commit();
		    return "Succeeded";
		}
	}

	public function edit_perm1($pid) {
		$sql = $this->db->query("SELECT apps.app_id, t.perm_id, t.perm, t.perm_name, t.perm_group, apps.app_name FROM (SELECT permissions.perm_id, permissions.perm, permissions.perm_name, permissions.perm_group, permissions.app_id FROM permissions WHERE permissions.perm_id = '$pid') AS t RIGHT JOIN apps ON t.app_id = apps.app_id");
		$arr = $sql->result_array();

		// $sqlx = $this->db->query("SELECT * FROM roles_settings WHERE roles_settings.perm_id = '$pid'");
		$this->db->select('*');
		$this->db->from('roles_settings');
		$this->db->where('perm_id', $pid);
		$datax = $this->db->get();

		$data = array();

		if ($datax->num_rows() > 0) {
			foreach ($arr as $val) {
				if (empty($val['perm'])) {
					$data[$val['app_id']] = array('value' => $val['app_name'], 'selected' => false, 'disabled' => true);
				} else {
					$data[$val['app_id']] = array('value' => $val['app_name'], 'selected' => true, 'data' => $arr);
				}
			}
		} else {
			foreach ($arr as $val) {
				if (empty($val['perm'])) {
					$data[$val['app_id']] = array('value' => $val['app_name'], 'selected' => false);
				} else {
					$data[$val['app_id']] = array('value' => $val['app_name'], 'selected' => true, 'data' => $arr);
				}
			}
		}
		
		return $data;
	}

	public function user_roles1($uid) {
		$this->db->select('*');
		$this->db->from('apps');
		$data = $this->db->get();
		$datax = array();
		if ($data->num_rows() > 0) {
			foreach ($data->result_array() as $val) {
				$datax[$val['app_id']] = array('value' => $val['app_name'], 'selected' => false);
			}
		}
		return $datax;
	}

	public function user_roles2($aid, $uid) {
		$sql = $this->db->query("SELECT t.role_id 'selected', roles.* FROM (SELECT role_id FROM users_roles WHERE user_id = '$uid') AS t RIGHT JOIN roles ON t.role_id = roles.role_id WHERE roles.app_id = '$aid'");
		$arr = $sql->result_array();
		$data = array();
		foreach ($arr as $val) {
			if (is_null($val['selected'])) {
				$data[$val['role_id']] = array('value' => $val['role_name'], 'selected' => false);
			} else {
				$data[$val['role_id']] = array('value' => $val['role_name'], 'selected' => true);
			}
		}
		return $data;
	}

	public function user_roles3($uid) {
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('user_id', $uid);
		$data = $this->db->get();
		return $data->result_array();
	}

	public function set_roles($data) {
		$aid = $data['aid'];
		$name = $data['name'];
		$nip = $data['nip'];
		$password = $data['password'];
		$roles = $data['roles'] ? explode(',', $data['roles']) : [];
		$uid = $data['uid'];
		$username = $data['username'];
	
		$this->db->trans_begin();

		$this->db->query("DELETE
			FROM
				users_roles
			WHERE
				user_id = '$uid'
				AND
				role_id IN (
					SELECT
						roles.role_id
					FROM
						roles
					WHERE
						roles.app_id = '$aid'
				)");
		foreach ($roles as $role) {
			$this->db->query("INSERT INTO users_roles (user_id, role_id) VALUES ('$uid', '$role')");	
		}
		if ($this->db->trans_status() === FALSE)
		{
		        $this->db->trans_rollback();
		}
		else
		{
		    $this->db->trans_commit();
		}

		return 'succeed';
	}
}