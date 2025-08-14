<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Saldo_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	function add($id, $value, $modul, $date, $updater)
	{

		$data = array(
			'saldo_awal_date' => $date,
			'saldo_awal_user_id' => $updater,
			'saldo_awal_account' => $id,
			$modul => $value
		);

		$account = $this->db->query("SELECT account_majors_id AS majors_id, account_code AS code FROM account
										WHERE account_id = '$data[saldo_awal_account]'")->row_array();

		$account_code = $account['code'];

		if (substr($account_code, 0, 3) == '1-1') {
			$data['saldo_awal_debit'] = $value;
		} else {
			$data['saldo_awal_kredit'] = $value;
		}

		if ($value != '0') {
			$insert_data = $this->db->insert("saldo_awal", $data);
			if ($insert_data) {
				if (substr($account_code, 0, 3) == '1-1') {
					$insert_modal = $this->modify_modal($account['majors_id'], $data);
				}
			}
		}
	}

	function update($id, $value, $modul, $date, $updater)
	{

		$data = array(
			$modul => $value,
			'saldo_awal_date' => $date,
			'saldo_awal_user_id' => $updater
		);

		$account = $this->db->query("SELECT account_majors_id AS majors_id, account_code AS code
										FROM account WHERE account_id = '$id'")->row_array();

		$account_code = $account['code'];

		if (substr($account_code, 0, 3) == '1-1') {
			$data['saldo_awal_debit'] = $value;
		} else {
			$data['saldo_awal_kredit'] = $value;
		}

		$this->db->where(array("saldo_awal_account" => $id));
		$update_data = $this->db->update("saldo_awal", $data);

		if ($update_data) {
			if (substr($account_code, 0, 3) == '1-1') {
				$update_modal = $this->modify_modal($account['majors_id'], $data);
			}
		}

		$saldo = $this->db->query("SELECT saldo_awal_nominal FROM saldo_awal WHERE saldo_awal_account = '$id'")->row_array();

		if ($saldo['saldo_awal_nominal'] == 0) {
			$this->db->delete('saldo_awal', array('saldo_awal_account' => $id));
		}
	}

	function modify_modal(int $major_id = null, array $data = array())
	{
		$modal = $this->db->query("SELECT account_id FROM account
		WHERE account_code LIKE '3-3%'
		AND account_status = 1
		AND account_majors_id = '$major_id'")->row_array();

		$this->db->where('saldo_awal_account', $modal['account_id']);
		$saldo = $this->db->get('saldo_awal')->num_rows();

		if ($saldo < 1) {
			$data = array(
				'saldo_awal_date' 		=> $data['saldo_awal_date'],
				'saldo_awal_user_id' 	=> $data['saldo_awal_user_id'],
				'saldo_awal_account' 	=> $modal['account_id'],
				'saldo_awal_kredit' 	=> $data['saldo_awal_nominal'],
				'saldo_awal_nominal' 	=> $data['saldo_awal_nominal']
			);

			$result = $this->db->insert("saldo_awal", $data);
		} else {


			$aktiva = $this->db->query("SELECT SUM(saldo_awal.saldo_awal_nominal) nominal FROM `account` LEFT JOIN saldo_awal ON saldo_awal.saldo_awal_account = account.account_id LEFT JOIN majors ON majors.majors_id = account.account_majors_id WHERE account_majors_id = '$major_id' AND account_code LIKE '1-1%'")->row_array();

			$this->db->set('saldo_awal_kredit', $aktiva['nominal']);
			$this->db->set('saldo_awal_nominal', $aktiva['nominal']);

			$this->db->where('saldo_awal_account', $modal['account_id']);
			$result = $this->db->update("saldo_awal");
		}

		return $result;
	}
}
