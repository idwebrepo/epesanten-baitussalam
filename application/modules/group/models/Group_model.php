<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
    //sampai disini
	function get($params = array())
	{
		if(isset($params['id']))
		{
			$this->db->where('group_id', $params['id']);
		}

		if (isset($params['kegitan_id'])) {
			$this->db->where('kegitan_id', $params['kegitan_id']);
		}
		
		if(isset($params['order_by'])){
		    $this->db->order_by($params['order_by'], 'desc');
		}else{
		    $this->db->order_by('group_id', 'desc');
		}
        // $this->db->where('majors_status', '1');

		$this->db->select('group_id, group_name, group_kegiatan_id, group_date, group_date, group_tempat, group_keterangan, group_input_date');
        $this->db->select('kegiatan_name, kegiatan_majors_id');

		$this->db->join('kegiatan', 'tbl_group.group_kegiatan_id = kegiatan.kegiatan_id', 'left');
		
		$res = $this->db->get('tbl_group');

		if(isset($params['id']))
		{
			return $res->row_array();
		}
		else
		{
			return $res->result_array();
		}
	}   

    // Add and update to database
	function add($data = array()) {

		if(isset($data['group_id'])) {
			$this->db->set('group_id', $data['group_id']);
		}

		if(isset($data['group_name'])) {
			$this->db->set('group_name', $data['group_name']);
		}

		if(isset($data['group_kegiatan_id'])) {
			$this->db->set('group_kegiatan_id', $data['group_kegiatan_id']);
		}

		if(isset($data['group_date'])) {
			$this->db->set('group_date', $data['group_date']);
		}

		if(isset($data['group_tempat'])) {
			$this->db->set('group_tempat', $data['group_tempat']);
		}

		if(isset($data['group_keterangan'])) {
			$this->db->set('group_keterangan', $data['group_keterangan']);
		}

		if (isset($data['group_id'])) {
			$this->db->where('group_id', $data['group_id']);
			$this->db->update('tbl_group');
			$id = $data['group_id'];
		} else {
			$this->db->insert('tbl_group');
			$id = $this->db->insert_id();
		}

		$status = $this->db->affected_rows();
		return ($status == 0) ? FALSE : $id;
	}

    // Delete magang to database
	function delete($id) {
		$this->db->where('group_id ', $id);
		$this->db->delete('tbl_group');
	}

    //========================== Peserta model====================

	function get_peserta($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('peserta_id', $params['id']);
        }

        if (isset($params['peserta_group_id'])) {
            $this->db->where('peserta_group_id', $params['peserta_group_id']);
        }

        if (isset($params['limit'])) {
            if (!isset($params['offset'])) {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }
        if (isset($params['order_by'])) {
            $this->db->order_by($params['order_by'], 'desc');
        } else {
            $this->db->order_by('peserta_id', 'asc');
        }

        $this->db->select('peserta_id, peserta_student_id, peserta_group_id, student_full_name, student_halaqoh, student_nis');
		$this->db->join('student', 'student.student_id = peserta_halaqoh.peserta_student_id', 'left');
		$this->db->join('tbl_group', 'tbl_group.group_id = peserta_halaqoh.peserta_group_id', 'left');
		$this->db->join('kegiatan', 'kegiatan.kegiatan_id = tbl_group.group_kegiatan_id', 'left');

        $res = $this->db->get('peserta_halaqoh');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

	function add_peserta($data = array()) {

        if (isset($data['peserta_id'])) {
            $this->db->set('peserta_id', $data['peserta_id']);
        }
        
        if (isset($data['peserta_nim'])) {
            $this->db->set('peserta_nim', $data['peserta_nim']);
        }

        if (isset($data['peserta_name'])) {
            $this->db->set('peserta_name', $data['peserta_name']);
        }

		if (isset($data['peserta_gender'])) {
            $this->db->set('peserta_gender', $data['peserta_gender']);
        }

        if (isset($data['peserta_tanggal_lahir'])) {
            $this->db->set('peserta_tanggal_lahir', $data['peserta_tanggal_lahir']);
        }

		if (isset($data['peserta_tempat_lahir'])) {
            $this->db->set('peserta_tempat_lahir', $data['peserta_tempat_lahir']);
        }

        if (isset($data['peserta_majors_id'])) {
            $this->db->set('peserta_majors_id', $data['peserta_majors_id']);
        }

        if (isset($data['peserta_group_id'])) {
            $this->db->set('peserta_group_id', $data['peserta_group_id']);
        }

		if (isset($data['peserta_phone'])) {
            $this->db->set('peserta_phone', $data['peserta_phone']);
        }

        if (isset($data['peserta_address'])) {
            $this->db->set('peserta_address', $data['peserta_address']);
        }

		if (isset($data['peserta_photo'])) {
            $this->db->set('peserta_photo', $data['peserta_photo']);
        }

        if (isset($data['peserta_status'])) {
            $this->db->set('peserta_status', $data['peserta_status']);
        }

        if (isset($data['peserta_email'])) {
            $this->db->set('peserta_email', $data['peserta_email']);
        }

		if (isset($data['peserta_password'])) {
            $this->db->set('peserta_password', $data['peserta_password']);
        }

        if (isset($data['status_absen'])) {
            $this->db->set('status_absen', $data['status_absen']);
        }

		if (isset($data['status_absen_temp'])) {
            $this->db->set('status_absen_temp', $data['status_absen_temp']);
        }

        if (isset($data['area_absen'])) {
            $this->db->set('area_absen', $data['area_absen']);
        }

        if (isset($data['jarak_radius'])) {
            $this->db->set('jarak_radius', $data['jarak_radius']);
        }

        if (isset($data['peserta_id'])) {
            $this->db->where('peserta_id', $data['peserta_id']);
            $this->db->update('magang_peserta');
            $id = $data['peserta_id'];
        } else {
            $this->db->insert('magang_peserta');
            $id = $this->db->insert_id(); 
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function delete_peserta($id) {
        $this->db->where('peserta_id', $id);
        $this->db->delete('magang_peserta');
    }


}
