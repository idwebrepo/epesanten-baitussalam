<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Buku_besar_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function get_account($majors)
    {
        $unit = "";
        if ($majors != 'all') {
            $unit = " AND account_majors_id = '$majors'";
        }

        return $this->db->query("SELECT account_code, account_description 
        FROM account 
        WHERE 1 $unit 
        ORDER BY account_code ASC")->result_array();
    }

    public function get_jurnal($majors, $date_start, $date_end)
    {
        $unit = "";
        if ($majors != 'all') {
            $unit = " AND jurnal_umum.sekolah_id = '$majors'";
        }

        $whereDate = '';
        if (!empty($date_start) and !empty($date_end)) {
            $whereDate = " AND jurnal_umum.tanggal BETWEEN '$date_start' AND  '$date_end'";
        }

        return $this->db->query("SELECT tanggal, account_code, keterangan, debet, kredit 
        FROM `jurnal_umum_detail` 
        JOIN jurnal_umum ON jurnal_umum.id = id_jurnal 
        WHERE 1 $unit $whereDate
        ORDER BY jurnal_umum_detail.account_code ASC, 
        jurnal_umum.tanggal ASC")->result_array();
    }

    public function group_jurnal($majors, $date_start, $date_end)
    {
        $unit = "";
        if ($majors != 'all') {
            $unit = " AND jurnal_umum.sekolah_id = '$majors'";
        }
        $whereDate = '';
        if (!empty($date_start) and !empty($date_end)) {
            $whereDate = " AND jurnal_umum.tanggal BETWEEN '$date_start' AND  '$date_end'";
        }

        return $this->db->query("SELECT account_code 
        FROM `jurnal_umum_detail` 
        JOIN jurnal_umum ON jurnal_umum.id = id_jurnal 
        WHERE 1 $unit $whereDate
        GROUP BY jurnal_umum_detail.account_code
        ORDER BY jurnal_umum_detail.account_code ASC, 
        jurnal_umum.tanggal ASC")->result_array();
    }
}
