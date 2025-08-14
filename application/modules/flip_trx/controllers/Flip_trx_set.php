<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Flip_trx_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('setting/Setting_model', 'student/Student_model'));
    $this->load->helper(array('form', 'url'));
  }

  // User_customer view in list
  public function index($offset = NULL)
  {
    $s = $this->input->get(NULL, TRUE);

    $data['s'] = $s;

    $today = date('Y-m-d');

    $whereMajors = "";
    $whereClass = "";
    $whereStatus = "";
    $whereStart = " AND DATE(a.tanggal) = '$today'";
    $whereEnd = " AND DATE(a.tanggal) = '$today'";

    $title = 'Transaksi Pembayaran (FLIP)';
    $table = "flip_transaksi";

    if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
      $whereMajors = " AND d.majors_majors_id = $s[m]";
    }

    if (isset($s['c']) && !empty($s['c']) && $s['c'] != 'all') {
      $whereClass = " AND d.class_id = $s[c]";
    }

    if (isset($s['s']) && !empty($s['s']) && $s['s'] != 'all') {
      $whereStatus = " AND a.status = $s[s]";
    }

    if (isset($s['start']) && !empty($s['start'])) {
      $whereStart = " AND DATE(a.tanggal) >= '$s[start]'";
    }

    if (isset($s['end']) && !empty($s['end'])) {
      $whereEnd = " AND DATE(a.tanggal) <= '$s[end]'";
    }

    if (isset($s['t']) && !empty($s['t']) && $s['t'] == 'bank') {
      $title = 'Transaksi Tabungan (FLIP)';
      $table = "flip_transaksi_tabungan";
    } else if (isset($s['t']) && !empty($s['t']) && $s['t'] == 'donation') {
      $title = 'Transaksi Donasi (FLIP)';
      $table = "flip_donasi";
    } else if (isset($s['t']) && !empty($s['t']) && $s['t'] == 'homestay') {
      $title = 'Transaksi Penginapan (FLIP)';
      $table = "flip_penginapan";
    } else {
      $title = 'Transaksi Pembayaran (FLIP)';
      $table = "flip_transaksi";
    }

    $data['trxs']           = $this->db->query("SELECT a.tanggal, b.student_nis, b.student_full_name,
                                                      e.majors_short_name, d.class_name,
                                                      c.kode, a.va_no, a.status
                                                FROM $table a
                                                JOIN student b ON a.student_id = b.student_id
                                                JOIN class d ON d.class_id = b.class_class_id
                                                JOIN majors e ON e.majors_id = b.majors_majors_id
                                                JOIN data_flip_channel c ON c.payment_channel = CONCAT(a.va_channel,'|', a.va_bank)
                                                WHERE 1 $whereMajors $whereClass $whereStatus $whereStart $whereEnd
                                                ORDER BY tanggal DESC")->result_array();

    $data['majors']         = $this->Student_model->get_majors();
    $data['class']          = $this->db->query("SELECT d.* FROM class AS d WHERE 1 $whereMajors")->result_array();

    $data['title'] = $title;
    $data['main'] = 'flip_trx/flip_trx_list';
    $this->load->view('manage/layout', $data);
  }

  function class_searching()
  {
    $id_majors = $this->input->post('id_majors');
    $dataKelas  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();

    echo '<select style="width: 200px;" id="c" name="c" class="form-control" required>
    			<option value="">--- Pilih Kelas ---</option>
    			<option value="all">Semua Kelas</option>';
    foreach ($dataKelas as $row) {

      echo '<option value="' . $row['class_id'] . '">';

      echo $row['class_name'];

      echo '</option>';
    }
    echo '</select>';
  }
}
