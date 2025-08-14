<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Halaqoh_set extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('student/Student_model', 'setting/Setting_model','employees/Employees_model'));
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
      $params['halaqoh_name'] = $f['n'];
    }
    
    if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
      $params['majors_id'] = $s['m'];
    }else if (isset($s['m']) && !empty($s['m']) && $s['m'] == 'all') {
      //$params['majors_id'] = $s['m'];
    }

    //$paramsPage = $params;
    //$params['limit'] = 10;
    //$params['offset'] = $offset;
    $data['halaqoh'] = $this->Student_model->get_halaqoh($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    //$config['per_page'] = 10;
    //$config['uri_segment'] = 4;
    $config['base_url'] = site_url('manage/halaqoh/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    //$config['total_rows'] = count($this->Student_model->get_halaqoh($paramsPage));
    //$this->pagination->initialize($config);
    $data['majors'] = $this->Student_model->get_majors();
    $data['employee'] = $this->Employees_model->get();
    
    $data['title'] = 'Halaqoh';
    $data['main'] = 'halaqoh/halaqoh_list';
    $this->load->view('manage/layout', $data);
  }

  public function add_glob(){
    if ($_POST == TRUE) {
      $halaqohName = $_POST['halaqoh_name'];
      $halaqohMusyrif = $_POST['halaqoh_employee_id'];
      $halaqohMajorsId = $_POST['halaqoh_majors_id'];
      $cpt = count($_POST['halaqoh_name']);
      for ($i = 0; $i < $cpt; $i++) {
        $params['halaqoh_name'] = $halaqohName[$i];
        $params['halaqoh_employee_id'] = $halaqohMusyrif[$i];

        $this->Student_model->add_halaqoh($params);
      }
    }
    $this->session->set_flashdata('success',' Tambah Halaqoh Berhasil');
    redirect('manage/halaqoh');
  }

  // Add User_customer and Update
  public function add($id = NULL) {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('halaqoh_name', 'Nama Halaqoh', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST AND $this->form_validation->run() == TRUE) {

      if ($this->input->post('halaqoh_id')) {
        $params['halaqoh_id'] = $this->input->post('halaqoh_id');
      }
      $params['halaqoh_name'] = $this->input->post('halaqoh_name');
      $params['halaqoh_employee_id'] = $this->input->post('halaqoh_employee_id');
      $status = $this->Student_model->add_halaqoh($params);


      $this->session->set_flashdata('success', $data['operation'] . '  Halaqoh');
      redirect('manage/halaqoh');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      if ($this->input->post('halaqoh_id')) {
        redirect('manage/halaqoh/edit/' . $this->input->post('halaqoh_id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Student_model->get_halaqoh(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/halaqoh');
        } else {
          $data['halaqoh'] = $object;
          $data['employee'] = $this->Employees_model->get();
        }
      }
      $data['majors'] = $this->Student_model->get_majors();
      $data['title'] = $data['operation'] . '  Halaqoh';
      $data['main'] = 'halaqoh/halaqoh_add';
      $this->load->view('manage/layout', $data);
    }
  }


// Delete to database
  public function delete($id = NULL) {
    if ($this->session->userdata('uroleid')!= SUPERUSER){
      redirect('manage');
    }
    $siswa = $this->Student_model->get(array('halaqoh_id'=>$id));

    if ($_POST) {

      if (count($siswa) > 0) {
        $this->session->set_flashdata('failed', 'Data Halaqoh tidak dapat dihapus');
        redirect('manage/halaqoh');
      }

      $this->Student_model->delete_halaqoh($id);
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
      $this->session->set_flashdata('success', 'Hapus Halaqoh berhasil');
      redirect('manage/halaqoh');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/halaqoh/edit/' . $id);
    }  
  }

  public function view_setting($id = NULL, $student_id = NULL) {

    $id_halaqoh = $this->uri->segment(4);
    
    if ($id == NULL) {
      redirect('manage/halaqoh');
    }

    // Apply Filter
        // Get $_GET variable
    $q = $this->input->get(NULL, TRUE);

    $data['q'] = $q;
    $params = array();

        // Kelas
    if (isset($q['pr']) && !empty($q['pr']) && $q['pr'] != '') {
      $params['class_id'] = $q['pr'];
    }

    if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
      $params['majors_id'] = $q['k'];
    }

    $params['majors_id'] = $majors->account_majors_id;
    $data['halaqoh_id'] = $id;
    $params['group'] = TRUE;
    $data['student_id'] = $student_id;

    $data['class'] = $this->Student_model->get_class($params);
    $data['halaqoh'] = $this->Student_model->get_halaqoh($params);
    $data['title'] = 'Setting Santri Halaqoh';
    $data['main'] = 'halaqoh/halaqoh_view_setting';
    $this->load->view('manage/layout', $data);
  }
}
