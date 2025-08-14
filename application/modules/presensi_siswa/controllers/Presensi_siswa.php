<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Presensi_siswa extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();

    $this->load->model(array('rekap_presensi/Rekap_presensi_model', 'student/Student_model', 'setting/Setting_model'));
    $this->load->helper(array('form', 'url'));
  }

  // Update
  public function index()
  {

    $this->load->library('form_validation');

    $this->form_validation->set_rules('image', 'Foto', 'trim|required|xss_clean');
    $this->form_validation->set_rules('longi', 'Longi', 'trim|xss_clean');
    $this->form_validation->set_rules('lati', 'Lati', 'trim|xss_clean');
    $this->form_validation->set_rules('rfid', 'rfid', 'trim|required|xss_clean');
    $this->form_validation->set_rules('pin', 'pin', 'trim|required|xss_clean');

    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button position="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');

    if ($_POST and $this->form_validation->run() == TRUE) {

      $card = $this->db->get_where('cards', array(
        'rfid' => $this->input->post('rfid'),
        'pin' => md5($this->input->post('pin')),
      ));

      $auth = $card->num_rows();

      if ($auth < 1) {
        $this->session->set_flashdata('failed', ' Nomor Kartu atau PIN Salah. Silahkan Ulangi Lagi.');
        redirect('presensi_siswa');
        exit;
      }

      $cardData = $card->row();

      $this->db->where('student_nis', $cardData->nis);
      $this->db->select('student_id, student_full_name, class_class_id');
      $student = $this->db->get('student')->row();

      $period = $this->db->query("SELECT period_id FROM period WHERE period_status = '1'")->row();

      $thisMonth = pretty_date(date('Y-m-d'), 'F', FALSE);

      $month = $this->db->get_where('month', array('month_name' => $thisMonth))->row();

      $this->db->where('presensi_harian_period_id', $period->period_id);
      $this->db->where('presensi_harian_month_id', $month->month_id);
      $this->db->where('presensi_harian_date', date('Y-m-d'));
      $this->db->where('presensi_harian_student_id', $student->student_id);
      $this->db->where('presensi_harian_month_id', $month->month_id);
      $check = $this->db->get('presensi_harian')->num_rows();

      if ($check > 0) {
        $this->session->set_flashdata('failed', 'Hari Ini Anda Sudah Melakukan Presensi.');
        redirect('presensi_siswa');
        exit;
      }

      $params['presensi_harian_period_id']    = $period->period_id;
      $params['presensi_harian_month_id']     = $month->month_id;
      $params['presensi_harian_date']         = date('Y-m-d');
      $params['presensi_harian_class_id']     = $student->class_class_id;
      $params['presensi_harian_student_id']   = $student->student_id;
      $params['presensi_harian_status']       = 'H';
      $params['presensi_harian_photo']        = $this->input->post('image');
      $params['presensi_harian_lat']          = $this->input->post('longi');
      $params['presensi_harian_lon']          = $this->input->post('lati');

      $result = $this->db->insert('presensi_harian', $params);

      if ($result) {
        // activity log
        $this->load->model('logs/Logs_model');
        $this->Logs_model->add(
          array(
            'log_date' => date('Y-m-d H:i:s'),
            'user_id' => $student->student_id,
            'log_module' => 'Tambah Presensi',
            'log_action' => 'Presensi Harian Santri',
            'log_info' => 'ID:' . date('Ymdhis') . ';Name:' . $student->student_full_name
          )
        );

        $this->session->set_flashdata('success', ' Presensi Harian Santri Berhasil');
      } else {
        $this->session->set_flashdata('failed', ' Presensi Harian Santri Gagal');
      }
      redirect('presensi_siswa');
    } else {

      $data['setting_level']              = $this->Setting_model->get(array('id' => 7));
      $data['setting_nip_bendahara']      = $this->Setting_model->get(array('id' => 15));
      $data['setting_nama_bendahara']     = $this->Setting_model->get(array('id' => 16));
      $data['setting_logo']               = $this->Setting_model->get(array('id' => 6));
      $data['setting_city']               = $this->Setting_model->get(array('id' => SCHOOL_CITY));
      $data['setting_district']           = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
      $data['setting_school']             = $this->Setting_model->get(array('id' => SCHOOL_NAME));
      $data['setting_address']            = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
      $data['setting_phone']              = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

      $data['title'] = 'Presensi Harian Santri';
      $data['main'] = 'presensi_siswa/presensi_siswa_add';
      $this->load->view('frontend/presensi_siswa', $data);
    }
  }
}
