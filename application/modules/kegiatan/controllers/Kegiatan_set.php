<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kegiatan_set extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('student/Student_model', 'kegiatan/Kegiatan_model', 'setting/Setting_model'));
    $this->load->helper(array('form', 'url'));
  }

// User_customer view in list
  public function index($offset = NULL) {
    $this->load->library('pagination');
// Apply Filter
// Get $_GET variable
    $s = $this->input->get(NULL, TRUE);
    $f = $this->input->get(NULL, TRUE);

    $data['s'] = $s;
    $data['f'] = $f;

    $params = array();
// Nip
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['kegiatan_name'] = $f['n'];
    }
    
    if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
      $params['majors_id'] = $s['m'];
    }else if (isset($s['m']) && !empty($s['m']) && $s['m'] == 'all') {
      //$params['majors_id'] = $s['m'];
    }

    //$paramsPage = $params;
    //$params['limit'] = 10;
    //$params['offset'] = $offset;
    $data['kegiatan'] = $this->Kegiatan_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    //$config['per_page'] = 10;
    //$config['uri_segment'] = 4;
    $config['base_url'] = site_url('manage/kegiatan/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    //$config['total_rows'] = count($this->Kegiatan_model->get_kegiatan($paramsPage));
    //$this->pagination->initialize($config);
    $data['majors'] = $this->Student_model->get_majors();
    
    $data['title'] = 'Kegiatan';
    $data['main'] = 'kegiatan/kegiatan_list';
    $this->load->view('manage/layout', $data);
  }

  public function add_glob(){
    if ($_POST == TRUE) {
      $kegiatanName = $_POST['kegiatan_name'];
      $kegiatanMajorsId = $_POST['kegiatan_majors_id'];
      $cpt = count($_POST['kegiatan_name']);
      for ($i = 0; $i < $cpt; $i++) {
        $params['kegiatan_name'] = $kegiatanName[$i];
        $params['kegiatan_majors_id'] = $kegiatanMajorsId[$i];

        $this->Kegiatan_model->add_kegiatan($params);
      }
    }
    $this->session->set_flashdata('success',' Tambah kegiatan Berhasil');
    redirect('manage/kegiatan');
  }

// Add User_customer and Update
  public function add($id = NULL) {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('kegiatan_name', 'Nama kegiatan', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST AND $this->form_validation->run() == TRUE) {

      if ($this->input->post('kegiatan_id')) {
        $params['kegiatan_id'] = $this->input->post('kegiatan_id');
      }
      $params['kegiatan_name'] = $this->input->post('kegiatan_name');
      $params['kegiatan_majors_id'] = $this->input->post('kegiatan_majors_id');
      $status = $this->Kegiatan_model->add_kegiatan($params);


      $this->session->set_flashdata('success', $data['operation'] . '  kegiatan');
      redirect('manage/kegiatan');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      if ($this->input->post('kegiatan_id')) {
        redirect('manage/kegiatan/edit/' . $this->input->post('kegiatan_id'));
      }

// Edit mode
      if (!is_null($id)) {
        $object = $this->Kegiatan_model->get_kegiatan(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/kegiatan');
        } else {
          $data['kegiatan'] = $object;
        }
      }
      $data['majors'] = $this->Student_model->get_majors();
      $data['title'] = $data['operation'] . '  kegiatan';
      $data['main'] = 'kegiatan/kegiatan_add';
      $this->load->view('manage/layout', $data);
    }
  }


// Delete to database
  public function delete($id = NULL) {
    if ($this->session->userdata('uroleid')!= SUPERUSER){
      redirect('manage');
    }
    // $siswa = $this->Student_model->get(array('kegiatan_id'=>$id));

    if ($_POST) {

      // if (count($siswa) > 0) {
      //   $this->session->set_flashdata('failed', 'Data kegiatan tidak dapat dihapus');
      //   redirect('manage/kegiatan');
      // }

      $this->Kegiatan_model->delete_kegiatan($id);
      // activity log
      $this->load->model('logs/Logs_model');
      $this->Logs_model->add(
        array(
          'log_date' => date('Y-m-d H:i:s'),
          'user_id' => $this->session->userdata('uid'),
          'log_module' => 'user',
          'log_action' => 'Hapus',
          'log_info' => 'ID:' . $id . ';Title:' . $this->input->post('delName')
        )
      );
      $this->session->set_flashdata('success', 'Hapus kegiatan berhasil');
      redirect('manage/kegiatan');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/kegiatan/edit/' . $id);
    }  
  }
}
