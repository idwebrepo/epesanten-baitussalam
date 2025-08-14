<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kegiatan_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('kegiatan_id', $params['id']);
        }

        if (isset($params['kegiatan_name'])) {
            $this->db->where('kegiatan_name', $params['kegiatan_name']);
        }

        if (isset($params['majors_id'])) {
            $this->db->where('kegiatan.kegiatan_majors_id', $params['majors_id']);
        }

        if (isset($param['majors_id'])) {
            $this->db->where('kegiatan.kegiatan_majors_id', $params['majors_id']);
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
            $this->db->order_by('kegiatan.kegiatan_name', 'asc');
        }

        $this->db->select('kegiatan_id, kegiatan_name');
        $this->db->select('kegiatan_majors_id, majors_short_name');
        $this->db->join('majors', 'majors.majors_id=kegiatan.kegiatan_majors_id', 'left');
        $res = $this->db->get('kegiatan');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function add_kegiatan($data = array())
    {

        if (isset($data['kegiatan_id'])) {
            $this->db->set('kegiatan_id', $data['kegiatan_id']);
        }

        if (isset($data['kegiatan_name'])) {
            $this->db->set('kegiatan_name', $data['kegiatan_name']);
        }

        if (isset($data['kegiatan_majors_id'])) {
            $this->db->set('kegiatan_majors_id', $data['kegiatan_majors_id']);
        }

        if (isset($data['kegiatan_id'])) {
            $this->db->where('kegiatan_id', $data['kegiatan_id']);
            $this->db->update('kegiatan');
            $id = $data['kegiatan_id'];
        } else {
            $this->db->insert('kegiatan');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function delete_kegiatan($id)
    {
        $this->db->where('kegiatan_id', $id);
        $this->db->delete('kegiatan');
    }

    function get_kegiatan($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('kegiatan_id', $params['id']);
        }

        if (isset($params['kegiatan_name'])) {
            $this->db->where('kegiatan_name', $params['kegiatan_name']);
        }

        if (isset($params['majors_id'])) {
            $this->db->where('kegiatan.kegiatan_majors_id', $params['majors_id']);
        }

        if (isset($param['majors_id'])) {
            $this->db->where('kegiatan.kegiatan_majors_id', $params['majors_id']);
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
            $this->db->order_by('kegiatan.kegiatan_name', 'asc');
        }

        $this->db->select('kegiatan_id, kegiatan_name');
        $this->db->select('kegiatan_majors_id, majors_short_name');
        $this->db->join('majors', 'majors.majors_id=kegiatan.kegiatan_majors_id', 'left');
        $res = $this->db->get('kegiatan');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }
}
