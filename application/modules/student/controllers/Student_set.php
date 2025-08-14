<?php

defined('BASEPATH') or exit('No direct script access allowed');



use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Student_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('student/Student_model', 'setting/Setting_model', 'bulan/Bulan_model', 'bebas/Bebas_model'));
    $this->load->helper(array('form', 'url'));
  }

  // User_customer view in list
  public function index($offset = NULL)
  {
    $this->load->library('pagination');
    // Apply Filter
    // Get $_GET variable
    $f = $this->input->get(NULL, TRUE);
    $s = $this->input->get(NULL, TRUE);

    $data['f'] = $f;
    $data['s'] = $s;

    $params = array();
    $param = array();
    // Nip
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['student_search'] = $f['n'];
    }

    if (isset($s['c']) && !empty($s['c']) && $s['c'] != 'all') {
      $params['class_id'] = $s['c'];
    } else if (isset($s['c']) && !empty($s['c']) && $s['c'] == 'all') {
    }

    if (isset($s['d']) && !empty($s['d']) && $s['d'] != 'all') {
      $params['madin_id'] = $s['d'];
    } else if (isset($s['d']) && !empty($s['d']) && $s['d'] == 'all') {
    }

    if (isset($s['m']) && !empty($s['m']) && $s['m'] != '') {
      $params['majors_id'] = $s['m'];

      if ($s['s'] != '1') {
        $params['status'] = $s['s'];
      } else {
        $params['status']   = '1';
      }
    }



    $data['student']    = $this->Student_model->get($params);
    $data['majors']     = $this->Student_model->get_majors();
    $data['class']      = $this->Student_model->get_class($params);
    $data['madin']      = $this->Student_model->get_madin($params);

    $config['base_url'] = site_url('manage/student/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");


    $data['title'] = 'Santri';
    $data['main'] = 'student/student_list';
    $this->load->view('manage/layout', $data);
  }

  function class_searching()
  {
    $id_majors = $this->input->post('id_majors');
    $dataKamar  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();

    echo '<select style="width: 200px;" id="c" name="c" class="form-control" required>
    			<option value="">--- Pilih Kelas ---</option>
    			<option value="all">Semua Kelas</option>';
    foreach ($dataKamar as $row) {

      echo '<option value="' . $row['class_id'] . '">';

      echo $row['class_name'];

      echo '</option>';
    }
    echo '</select>';
  }

  function madin_searching()
  {
    $id_majors = $this->input->post('id_majors');
    $dataMadin  = $this->db->query("SELECT * FROM madin ORDER BY madin_id ASC")->result_array();

    echo '<select style="width: 200px;" id="d" name="d" class="form-control" required>
    			<option value="">--- Pilih Kelas Pondok ---</option>
    			<option value="all">Semua Kelas Pondok</option>';
    foreach ($dataMadin as $row) {

      echo '<option value="' . $row['madin_id'] . '">';

      echo $row['madin_name'];

      echo '</option>';
    }
    echo '</select>';
  }

  function cari_kelas()
  {
    $id_majors   = $this->input->post('id_majors');
    $dataKamar  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();

    echo '<select class="form-control" name="pr" id="pr" onchange="this.form.submit()">
				<option value="">-- Pilih Kelas  --</option>';
    foreach ($dataKamar as $row) {

      echo '<option value="' . $row['class_id'] . '">';

      echo $row['class_name'];

      echo '</option>';
    }

    echo '</select>';
  }

  function cari_ke_kelas()
  {
    $id_majors   = $this->input->post('id_majors');
    $dataKamar  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();

    echo '<select class="form-control" name="c_id" id="c_id" onchange="ambil()">
    			<option value="">-- Ke Kelas  --</option>';
    foreach ($dataKamar as $row) {

      echo '<option value="' . $row['class_id'] . '">';

      echo $row['class_name'];

      echo '</option>';
    }

    echo '</select>';

    echo '
            <script>
                function ambil(){
                    var kls = $("#c_id").val();
                    
                    $("#class_id").val(kls);
                }
            </script>
        ';
  }

  function cari_kamar()
  {
    $id_majors   = $this->input->post('id_majors');
    $dataKamar  = $this->db->query("SELECT * FROM madin ORDER BY madin_name ASC")->result_array();

    echo '<select class="form-control" name="pr" id="pr" onchange="this.form.submit()">
				<option value="">-- Pilih Kelas Pondok  --</option>';
    foreach ($dataKamar as $row) {

      echo '<option value="' . $row['madin_id'] . '">';

      echo $row['madin_name'];

      echo '</option>';
    }

    echo '</select>';
  }

  function cari_ke_kamar()
  {
    $id_majors   = $this->input->post('id_majors');
    $dataKamar  = $this->db->query("SELECT * FROM madin ORDER BY madin_name ASC")->result_array();

    echo '<select class="form-control" name="c_id" id="c_id" onchange="ambil()">
    			<option value="">-- Ke Kelas Pondok  --</option>';
    foreach ($dataKamar as $row) {

      echo '<option value="' . $row['madin_id'] . '">';

      echo $row['madin_name'];

      echo '</option>';
    }

    echo '</select>';

    echo '
            <script>
                function ambil(){
                    var kls = $("#c_id").val();
                    
                    $("#madin_id").val(kls);
                }
            </script>
        ';
  }

  function get_kelas()
  {
    $id_majors   = $this->input->post('id_majors');
    $dataKamar  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();

    echo '<div class="form-group"> 
			<label >Kelas *</label>
            <select name="class_class_id" id="class_class_id" class="form-control">
				<option value=""> -- Pilih Kelas -- </option>';
    foreach ($dataKamar as $row) {

      echo '<option value="' . $row['class_id'] . '">';

      echo $row['class_name'];

      echo '</option>';
    }

    echo '</select>
            </div>';
  }

  function get_madin()
  {
    $id_majors   = $this->input->post('id_majors');
    $dataMadin  = $this->db->query("SELECT * FROM madin ORDER BY madin_id ASC")->result_array();

    echo '<div class="form-group"> 
			<label>Kelas Pondok </label>
            <select name="student_madin" id="student_madin" class="form-control">
				<option value=""> -- Pilih Kelas Pondok-- </option>';
    foreach ($dataMadin as $row) {

      echo '<option value="' . $row['madin_id'] . '">';

      echo $row['madin_name'];

      echo '</option>';
    }

    echo '</select>
            </div>';
  }

  // Add User and Update
  public function add($id = NULL)
  {

    $this->load->library('form_validation');

    if (!$this->input->post('student_id')) {
      $this->form_validation->set_rules('student_nis', 'NIS', 'trim|required|xss_clean|is_unique[student.student_nis]');
    }
    $this->form_validation->set_rules('class_class_id', 'Kelas', 'trim|required|xss_clean');
    $this->form_validation->set_rules('student_full_name', 'Nama lengkap', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button position="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST and $this->form_validation->run() == TRUE) {

      if ($this->input->post('student_id')) {
        $params['student_id'] = $id;
      } else {
        $params['student_input_date'] = date('Y-m-d H:i:s');
        $default = "123456";
        $pass = $this->input->post('student_password');
        if ($pass != '') {
          $params['student_password'] = md5($pass);
        } else {
          $params['student_password'] = md5($default);
        }
      }

      $student_parent_phone = $this->input->post('student_parent_phone');

      $student_parent_phone = str_replace(" ", "", $student_parent_phone);
      $student_parent_phone = str_replace("(", "", $student_parent_phone);
      $student_parent_phone = str_replace(")", "", $student_parent_phone);
      $student_parent_phone = str_replace(".", "", $student_parent_phone);

      if (!preg_match('/[^+0-9]/', trim($student_parent_phone))) {
        if (substr(trim($student_parent_phone), 0, 1) == '+') {
          $hp = trim($student_parent_phone);
        } elseif (substr(trim($student_parent_phone), 0, 1) == '0') {
          $hp = '+62' . substr(trim($student_parent_phone), 1);
        } elseif (substr(trim($student_parent_phone), 0, 2) == '62') {
          $hp = '+' . trim($student_parent_phone);
        } elseif (substr(trim($student_parent_phone), 0, 1) == '8') {
          $hp = '+62' . trim($student_parent_phone);
        } else {
          $hp = '+' . trim($student_parent_phone);
        }
      }

      $params['student_nis'] = $this->input->post('student_nis');
      $params['student_nisn'] = $this->input->post('student_nisn');
      $params['student_gender'] = $this->input->post('student_gender');
      $params['class_class_id'] = $this->input->post('class_class_id');
      $params['student_madin'] = $this->input->post('student_madin');
      $params['majors_majors_id'] = $this->input->post('majors_majors_id');
      $params['student_last_update'] = date('Y-m-d H:i:s');
      $params['student_full_name'] = $this->input->post('student_full_name');
      $params['student_nik'] = $this->input->post('student_nik');
      $params['student_kk'] = $this->input->post('student_kk');
      $params['student_born_place'] = $this->input->post('student_born_place');
      $params['student_born_date'] = $this->input->post('student_born_date');
      $params['student_address'] = $this->input->post('student_address');
      $params['student_name_of_mother'] = $this->input->post('student_name_of_mother');
      $params['student_name_of_father'] = $this->input->post('student_name_of_father');
      $params['father_nik'] = $this->input->post('father_nik');
      $params['mother_nik'] = $this->input->post('mother_nik');
      $params['student_parent_phone'] = $hp;
      $params['student_status'] = $this->input->post('student_status');
      $status = $this->Student_model->add($params);

      if (!empty($_FILES['student_img']['name'])) {
        $paramsupdate['student_img'] = $this->do_upload($name = 'student_img', $fileName = base64_encode($params['student_full_name']));
      }

      $paramsupdate['student_id'] = $status;
      $this->Student_model->add($paramsupdate);

      // activity log
      $this->load->model('logs/Logs_model');
      $this->Logs_model->add(
        array(
          'log_date' => date('Y-m-d H:i:s'),
          'user_id' => $this->session->userdata('uid'),
          'log_module' => 'Student',
          'log_action' => $data['operation'],
          'log_info' => 'ID:' . $status . ';Name:' . $this->input->post('student_full_name')
        )
      );

      $this->session->set_flashdata('success', $data['operation'] . ' Santri Berhasil');
      redirect('manage/student');
    } else {
      if ($this->input->post('student_id')) {
        redirect('manage/student/edit/' . $this->input->post('student_id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Student_model->get_student(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/student');
        } else {
          $data['student'] = $object;
        }
        $id_unit = $object['majors_majors_id'];
        $data['class'] = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_unit'")->result_array();
        $data['madin'] = $this->db->query("SELECT * FROM madin ORDER BY madin_id ASC")->result_array();
      }
      $data['setting_level'] = $this->Setting_model->get(array('id' => 7));
      $data['majors'] = $this->Student_model->get_majors();
      $data['title'] = $data['operation'] . ' Santri';
      $data['main'] = 'student/student_add';
      $this->load->view('manage/layout', $data);
    }
  }

  // View data detail
  public function view($id = NULL)
  {
    $data['student'] = $this->Student_model->get(array('id' => $id));
    $data['title'] = 'Santri';
    $data['mahram'] = $this->db->get('mahram')->result_array();
    $data['guest'] = $this->db->query("SELECT guest_id, guest_name, guest_student_id, mahram_note FROM guest JOIN mahram ON mahram.mahram_id = guest.guest_mahram_id WHERE guest_student_id = '$id'")->result_array();
    $data['main'] = 'student/student_view';
    $this->load->view('manage/layout', $data);
  }

  public function add_mahram()
  {
    if ($_POST == TRUE) {
      $studentID   = $_POST['student_id'];
      $guestName   = $_POST['guest_name'];
      $guestDesc   = $_POST['guest_mahram_id'];
      $cpt = count($_POST['guest_name']);
      for ($i = 0; $i < $cpt; $i++) {
        $params['guest_name']        = $guestName[$i];
        $params['guest_mahram_id']   = $guestDesc[$i];
        $params['guest_student_id']  = $studentID;

        $this->Student_model->add_mahram($params);
      }
    }
    $this->session->set_flashdata('success', ' Tambah Data Mahram Berhasil');
    redirect('manage/student/view/' . $studentID);
  }

  public function delete_mahram($id = NULL)
  {
    if ($_POST) {
      $x = $this->input->post('student_id');
      $this->Student_model->delete_mahram($id);
      $this->session->set_flashdata('success', 'Hapus Data Mahram berhasil');
      redirect('manage/student/view/' . $x);
    } elseif (!$_POST) {
      $x = $this->input->post('student_id');
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/student/view/' . $x);
    }
  }

  // Delete to database
  public function delete($id = NULL)
  {
    if ($this->session->userdata('uroleid') != SUPERUSER) {
      redirect('manage');
    }
    if ($_POST) {

      $bulan = $this->Bulan_model->get(array('student_id' => $this->input->post('student_id')));
      $bebas = $this->Bebas_model->get(array('student_id' => $this->input->post('student_id')));

      if (count($bulan) > 0 or count($bebas) > 0) {
        $this->session->set_flashdata('failed', 'Santri tidak dapat dihapus');
        redirect('manage/student');
      }

      $this->Student_model->delete($this->input->post('student_id'));

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
      $this->session->set_flashdata('success', 'Hapus Santri berhasil');
      redirect('manage/student');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/student/edit/' . $id);
    }
  }

  // Class view in list
  public function clasess($offset = NULL)
  {
    $this->load->library('pagination');

    $data['class'] = $this->Student_model->get_class(array('limit' => 10, 'offset' => $offset));
    $data['title'] = 'Daftar Kelas';
    $data['main'] = 'student/class_list';
    $config['total_rows'] = count($this->Student_model->get_class());
    $this->pagination->initialize($config);

    $this->load->view('manage/layout', $data);
  }

  function do_upload_xls($name = NULL, $fileName = NULL)
  {
    $this->load->library('upload');

    $config['upload_path'] = FCPATH . 'uploads/xls_tmp/';

    /* create directory if not exist */
    if (!is_dir($config['upload_path'])) {
      mkdir($config['upload_path'], 0777, TRUE);
    }

    $config['allowed_types'] = 'xls|xlsx|csv';
    $config['max_size'] = '10240';
    $config['file_name'] = $fileName;
    $this->upload->initialize($config);

    if (!$this->upload->do_upload($name)) {
      $this->session->set_flashdata('success', $this->upload->display_errors('', ''));
      redirect(uri_string());
    }

    $upload_data = $this->upload->data();

    return $upload_data['file_name'];
  }

  // Setting Upload File Requied
  function do_upload($name = NULL, $fileName = NULL)
  {
    $this->load->library('upload');

    $config['upload_path'] = FCPATH . 'uploads/student/';

    /* create directory if not exist */
    if (!is_dir($config['upload_path'])) {
      mkdir($config['upload_path'], 0777, TRUE);
    }

    $config['allowed_types'] = 'gif|jpg|jpeg|png';
    $config['max_size'] = '1024';
    $config['file_name'] = $fileName;
    $this->upload->initialize($config);

    if (!$this->upload->do_upload($name)) {
      $this->session->set_flashdata('success', $this->upload->display_errors('', ''));
      redirect(uri_string());
    }

    $upload_data = $this->upload->data();

    return $upload_data['file_name'];
  }


  // Add User_customer and Update
  public function add_class($id = NULL)
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('class_name', 'Name', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST and $this->form_validation->run() == TRUE) {

      if ($this->input->post('class_id')) {
        $params['class_id'] = $this->input->post('class_id');
      }
      $params['class_name'] = $this->input->post('class_name');
      $status = $this->Student_model->add_class($params);


      $this->session->set_flashdata('success', $data['operation'] . ' Keterangan Kelas Pondok');
      redirect('manage/student/add');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      if ($this->input->post('class_id')) {
        redirect('manage/student/class/edit/' . $this->input->post('class_id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Student_model->get_ket(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/student/class');
        } else {
          $data['class'] = $object;
        }
      }
      $data['title'] = $data['operation'] . ' Keterangan Kelas';
      $data['main'] = 'manage/student/class_add';
      $this->load->view('manage/layout', $data);
    }
  }

  public function import_student()
  {
    //redirect('manage/student/import');
    $data['title'] = 'Import Data Santri';
    $data['main'] = 'student/student_import';
    $data['action'] = site_url(uri_string());
    $this->load->view('manage/layout', $data);
  }

  public function do_import()
  {

    if (isset($_FILES["file"]["name"])) {

      $file = $this->do_upload_xls($name = 'file', $fileName = base64_encode('imstud' . date('ymdHis')));

      if ($file) {

        $path = FCPATH . 'uploads/xls_tmp/';
        $pathFile = $path . $file;

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($pathFile);
        $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $numrow = 1;
        foreach ($sheet as $row) {

          $student_nis            = $row['A'];
          $student_full_name      = $row['B'];
          $class_class_id         = $row['C'];
          $majors_majors_id       = $row['D'];
          $student_madin          = $row['E'];
          $student_gender         = $row['F'];
          $student_name_of_father = $row['G'];
          $student_parent_phone   = $row['H'];
          $student_address        = $row['I'];

          if ($numrow > 2) {


            echo $student_nis;
            $q = $this->db->query("SELECT COUNT(student_nis) as nisNum FROM student WHERE student_nis = '$student_nis'")->row_array();

            $nisNum = $q['nisNum'];

            if (empty($student_nis) || $nisNum > 0) {
              $this->session->set_flashdata('failed', 'Duplikasi No. Induk Santri');
            } else {

              $hp = $this->phone_to_wa($student_parent_phone);

              $data = array(
                'student_nis'             =>  $student_nis,
                'student_full_name'       =>  $student_full_name,
                'student_gender'           =>  $student_gender,
                'student_password'        =>  md5('123456'),
                'class_class_id'          =>  $class_class_id,
                'majors_majors_id'        =>  $majors_majors_id,
                'student_madin'           =>  $student_madin,
                'student_name_of_father'  =>  $student_name_of_father,
                'student_parent_phone'    =>  $hp,
                'student_address'         =>  $student_address
              );

              $this->db->insert('student', $data);


              $this->session->set_flashdata('success', 'Import Data Santri Berhasil');
            }
          }
          $numrow++;
        }

        unlink($pathFile);
      }
    }
    redirect('manage/student');
  }

  public function do_update()
  {

    if (isset($_FILES["file"]["name"])) {
      $file = $_FILES["file"]["tmp_name"];
      $path = FCPATH . 'uploads/xls_update_student/';

      if (!is_dir($path)) {
        mkdir($path, 0777, TRUE);
      }

      $pathFile = $path . $file;

      $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
      $spreadsheet = $reader->load($pathFile);
      $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
      $numrow = 1;
      foreach ($sheet as $row) {

        $student_nis     = $row['A'];
        $student_madin   = $row['C'];

        if ($numrow > 2) {
          $q = $this->db->query("SELECT COUNT(student_nis) as nisNum FROM student WHERE student_nis = '$student_nis'")->row_array();

          $nisNum = $q['nisNum'];

          if (empty($student_nis) || $nisNum == 0) {
            $this->session->set_flashdata('failed', 'Duplikasi No. Induk Santri');
          } else {

            $data = array(
              'student_madin'    =>  $student_madin,
            );

            $this->db->where('student_nis', $student_nis);
            $this->db->update('student', $data);
          }
        }
        $numrow++;
      }

      unlink($pathFile);

      $this->session->set_flashdata('success', 'Update Data Santri Berhasil');
    }

    redirect('manage/student');
  }

  private function phone_to_wa($student_parent_phone = null)
  {


    $student_parent_phone = str_replace(" ", "", $student_parent_phone);
    $student_parent_phone = str_replace("(", "", $student_parent_phone);
    $student_parent_phone = str_replace(")", "", $student_parent_phone);
    $student_parent_phone = str_replace(".", "", $student_parent_phone);

    if (!preg_match('/[^+0-9]/', trim($student_parent_phone))) {
      if (substr(trim($student_parent_phone), 0, 1) == '+') {
        $hp = trim($student_parent_phone);
      } elseif (substr(trim($student_parent_phone), 0, 1) == '0') {
        $hp = '+62' . substr(trim($student_parent_phone), 1);
      } elseif (substr(trim($student_parent_phone), 0, 2) == '62') {
        $hp = '+' . trim($student_parent_phone);
      } elseif (substr(trim($student_parent_phone), 0, 1) == '8') {
        $hp = '+62' . trim($student_parent_phone);
      } else {
        $hp = '+' . trim($student_parent_phone);
      }
    }
    return $hp;
  }

  public function update_whatsapp()
  {
    //redirect('manage/student/import');

    $data['majors']     = $this->Student_model->get_majors();
    $data['title']      = 'Update No. Whatsapp Ortu Santri';
    $data['main']       = 'student/update_whatsapp';
    $data['action']     = site_url(uri_string());
    $this->load->view('manage/layout', $data);
  }

  public function download_wa()
  {
    $params = array();

    $id_majors = $this->input->post('xls_majors');
    $id_kelas  = $this->input->post('xls_class');

    $params['majors_id'] = $id_majors;

    if ($id_kelas != 'all') {
      $params['class_id']  = $id_kelas;
      $data['kelas']   = $this->db->query("SELECT class_name FROM class WHERE class_id = '$id_kelas'")->row_array();
    } else {
      $data['kelas']   = array('class_name' => 'Semua Kelas');
    }

    $data['student'] = $this->Student_model->get($params);
    $data['majors']  = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$id_majors'")->row_array();

    $this->load->view('student/whatsapp_student', $data);
  }

  public function update_phone()
  {
    if (isset($_FILES["file"]["name"])) {
      $file = $this->do_upload_xls($name = 'file', $fileName = base64_encode('imstud' . date('ymdHis')));

      if ($file) {

        $path = FCPATH . 'uploads/xls_tmp/';
        $pathFile = $path . $file;

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($pathFile);
        $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $numrow = 1;
        foreach ($sheet as $row) {

          $student_nis            = $row['A'];
          $student_address        = $row['F'];
          $student_name_of_father = $row['G'];
          $student_name_of_mother = $row['H'];
          $student_parent_phone   = $row['I'];

          if ($numrow > 2) {

            $student_nis = str_replace("'", "", $student_nis);

            $q = $this->db->query("SELECT COUNT(student_nis) as nisNum FROM student
            WHERE student_nis = '$student_nis'")->row_array();

            $nisNum = $q['nisNum'];

            if (empty($student_nis) || $nisNum == 0) {
              $this->session->set_flashdata('failed', 'Duplikasi No. Induk Santri');
            } else {

              $student_nis            = str_replace("'", "", $student_nis);
              $student_parent_phone   = str_replace("'", "", $student_parent_phone);

              $hp = $this->phone_to_wa($student_parent_phone);

              $data = array(
                'student_address'         =>  $student_address,
                'student_name_of_father'  =>  $student_name_of_father,
                'student_name_of_mother'  =>  $student_name_of_mother,
                'student_parent_phone'    =>  $hp,
              );

              $this->db->where('student_nis', $student_nis);
              $this->db->update('student', $data);
            }
          }
          $numrow++;
        }

        unlink($pathFile);

        $this->session->set_flashdata('success', 'Update No. Whatsapp Ortu Santri Berhasil');
      }
    }
    redirect('manage/student');
  }

  function rpw($id = NULL)
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('student_password', 'Password', 'trim|required|xss_clean|min_length[6]');
    $this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required|xss_clean|min_length[6]|matches[student_password]');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    if ($_POST and $this->form_validation->run() == TRUE) {
      $id = $this->input->post('student_id');
      $default = "123456";
      $pass = $this->input->post('student_password');
      if ($pass != '') {
        $params['student_password'] = md5($pass);
      } else {
        $params['student_password'] = md5($default);
      }
      $status = $this->Student_model->change_password($id, $params);

      $this->session->set_flashdata('success', 'Reset Password Berhasil');
      redirect('manage/student');
    } else {
      if ($this->Student_model->get(array('id' => $id)) == NULL) {
        redirect('manage/student');
      }
      $data['student'] = $this->Student_model->get(array('id' => $id));
      $data['title'] = 'Reset Password';
      $data['main'] = 'student/change_pass';
      $this->load->view('manage/layout', $data);
    }
  }

  public function download()
  {
    $data = file_get_contents("./media/template_excel/Template_Data_Santri.xlsx");
    $name = 'Template_Data_Santri.xlsx';

    $this->load->helper('download');
    force_download($name, $data);
  }

  public function pass($offset = NULL)
  {
    $f = $this->input->get(NULL, TRUE);
    $data['f'] = $f;
    $params = array();
    // Nip
    if (isset($f['pr']) && !empty($f['pr']) && $f['pr'] != '') {
      $params['class_id'] = $f['pr'];
    }
    if (isset($f['m']) && !empty($f['m']) && $f['m'] != '') {
      $id = $f['m'];
      $data['class']        = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id' ORDER BY class_name ASC")->result_array();
    }

    $paramsPage = $params;
    $params['status']     = TRUE;
    $params['offset']     = $offset;
    $data['notpass']      = $this->Student_model->get($params);
    $data['pass']         = $this->Student_model->get(array('status' => 0));
    $data['majors']       = $this->Student_model->get_majors();
    $config['base_url']   = site_url('manage/student/index');
    $config['suffix']     = '?' . http_build_query($_GET, '', "&");
    $config['total_rows'] = count($this->Student_model->get($paramsPage));


    $data['title']        = 'Kelulusan Santri';
    $data['main']         = 'student/student_pass';
    $this->load->view('manage/layout', $data);
  }

  public function move($offset = NULL)
  {
    $f = $this->input->get(NULL, TRUE);
    $data['f'] = $f;
    $params = array();
    // Nip
    if (isset($f['pr']) && !empty($f['pr']) && $f['pr'] != '') {
      $params['madin_id'] = $f['pr'];
    }
    if (isset($f['m']) && !empty($f['m']) && $f['m'] != '') {
      $id = $f['m'];
      $data['madin']        = $this->db->query("SELECT * FROM madin ORDER BY madin_name ASC")->result_array();
    }

    $params['status'] = 1;

    $paramsPage = $params;
    $params['offset'] = $offset;
    $data['student'] = $this->Student_model->get($params);
    //$data['class'] = $this->Student_model->get_class($params);
    $data['majors'] = $this->Student_model->get_majors();
    //$data['upgrade'] = $this->Student_model->get_class();
    $config['base_url'] = site_url('manage/student/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    $config['total_rows'] = count($this->Student_model->get($paramsPage));

    $data['title'] = 'Pindah Kelas Pondok';
    $data['main'] = 'student/student_move';
    $this->load->view('manage/layout', $data);
  }

  public function upgrade($offset = NULL)
  {
    $f = $this->input->get(NULL, TRUE);
    $data['f'] = $f;
    $params = array();
    // Nip
    if (isset($f['pr']) && !empty($f['pr']) && $f['pr'] != '') {
      $params['class_id'] = $f['pr'];
    }
    if (isset($f['m']) && !empty($f['m']) && $f['m'] != '') {
      $id = $f['m'];
      $data['class']        = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id' ORDER BY class_name ASC")->result_array();
    }

    $params['status'] = 1;

    $paramsPage = $params;
    $params['offset'] = $offset;
    $data['student'] = $this->Student_model->get($params);
    //$data['class'] = $this->Student_model->get_class($params);
    $data['majors'] = $this->Student_model->get_majors();
    //$data['upgrade'] = $this->Student_model->get_class();
    $config['base_url'] = site_url('manage/student/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    $config['total_rows'] = count($this->Student_model->get($paramsPage));

    $data['title'] = 'Kenaikan Kelas';
    $data['main'] = 'student/student_upgrade';
    $this->load->view('manage/layout', $data);
  }

  public function setting_halaqoh($offset = NULL)
  {
    $f = $this->input->get(NULL, TRUE);
    $data['f'] = $f;
    $params = array();
    // Nip
    if (isset($f['pr']) && !empty($f['pr']) && $f['pr'] != '') {
      $params['class_id'] = $f['pr'];
    }
    if (isset($f['m']) && !empty($f['m']) && $f['m'] != '') {
      $id = $f['m'];
      $data['class']        = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id' ORDER BY class_name ASC")->result_array();
    }
    if (isset($f['hl']) && !empty($f['hl']) && $f['hl'] != '') {
      $halaqoh_id = $f['hl'];
    }

    $paramsPage = $params;
    $params['status']     = TRUE;
    $params['offset']     = $offset;
    $data['notpass']      = $this->Student_model->get($params);
    if ($halaqoh_id != null) {
      $data['pass']         = $this->Student_model->get(array('halaqoh_id' => $halaqoh_id));
    }

    $data['halaqoh']      = $this->db->query("SELECT * FROM halaqoh ORDER BY halaqoh_name ASC")->result_array();
    $data['majors']       = $this->Student_model->get_majors();
    $config['base_url']   = site_url('manage/student/index');
    $config['suffix']     = '?' . http_build_query($_GET, '', "&");
    $config['total_rows'] = count($this->Student_model->get($paramsPage));

    //minta di cek ada nggak file student/student_setting_halaqoh ini mas ya
    $data['title']        = 'Setting Halaqoh Santri';
    $data['main']         = 'student/student_setting_halaqoh';
    $this->load->view('manage/layout', $data);
  }

  public function move_halaqoh($offset = NULL)
  {
    $f = $this->input->get(NULL, TRUE);
    $data['f'] = $f;
    $params = array();
    // Nip
    if (isset($f['pr']) && !empty($f['pr']) && $f['pr'] != '') {
      $params['relation_halaqoh_id'] = $f['pr'];
    }

    $data['halaqoh']        = $this->db->query("SELECT * FROM halaqoh ORDER BY halaqoh_name ASC")->result_array();

    $data['student'] = $this->Student_model->get_student_halaqoh($params);

    $params['status'] = 1;

    $paramsPage = $params;
    $params['offset'] = $offset;
    //$data['class'] = $this->Student_model->get_class($params);
    $data['majors'] = $this->Student_model->get_majors();
    //$data['upgrade'] = $this->Student_model->get_class();
    $config['base_url'] = site_url('manage/student/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    $config['total_rows'] = count($this->Student_model->get($paramsPage));

    $data['title'] = 'Pindah Halaqoh';
    $data['main'] = 'student/student_halaqoh';
    $this->load->view('manage/layout', $data);
  }

  function multiple()
  {
    $action = $this->input->post('action');
    $period = $this->db->query("SELECT period_id AS id FROM period WHERE period_status = 1")->row_array();
    $periodActive = $period['id'];

    $print = array();
    $idcard = array();
    if ($action == "pass") {
      $pass = $this->input->post('msg');
      for ($i = 0; $i < count($pass); $i++) {
        $bebas = $this->db->query("SELECT (bebas_bill - bebas_total_pay) as tagihan FROM bebas WHERE student_student_id = '$pass[$i]'")->row_array();
        $bulan = $this->db->query("SELECT COUNT(bulan_status) as tagihan FROM bulan WHERE bulan_status = 0 AND student_student_id = '$pass[$i]'")->row_array();

        if ($bebas['tagihan'] == 0 && $bulan['tagihan'] == 0) {
          $this->Student_model->add(array('student_id' => $pass[$i], 'student_status' => 0, 'student_last_update' => date('Y-m-d H:i:s')));

          $this->db->query("INSERT INTO alumni
                                (alumni_nis, alumni_nisn, alumni_full_name, alumni_gender, alumni_born_place, alumni_born_date, alumni_img, alumni_phone, alumni_hobby, alumni_address, alumni_name_of_mother, alumni_name_of_father, alumni_parent_phone, alumni_unit, alumni_kelas, alumni_madin, alumni_tahun_id) 
                            SELECT 
                                student_nis, student_nisn, student_full_name, student_gender, student_born_place, student_born_date, student_img, student_phone, student_hobby, student_address, student_name_of_mother, student_name_of_father, student_parent_phone, majors_majors_id, class_class_id, student_madin, '$periodActive'
                                FROM student
                                WHERE student_id = '$pass[$i]'");

          $this->session->set_flashdata('success', 'Proses Lulus berhasil');
        } else {
          $this->session->set_flashdata('failed', 'Belum boleh lulus. Masih memiliki tunggakan biaya sekolah');
        }
      }

      redirect('manage/student/pass');
    } elseif ($action == "notpass") {
      $notpass = $this->input->post('msg');
      for ($i = 0; $i < count($notpass); $i++) {
        $this->Student_model->add(array('student_id' => $notpass[$i], 'student_status' => 1, 'student_last_update' => date('Y-m-d H:i:s')));
        $this->session->set_flashdata('success', 'Proses Kembali berhasil');
      }
      redirect('manage/student/pass');
    } elseif ($action == "upgrade") {
      $upgrade = $this->input->post('msg');
      $majors   = $this->input->post('m_id');
      $kelas   = $this->input->post('class_id');
      //echo $kelas.'<br>';
      for ($i = 0; $i < count($upgrade); $i++) {
        //echo $upgrade[$i];
        $this->Student_model->add(array('student_id' => $upgrade[$i], 'class_class_id' => $kelas, 'student_last_update' => date('Y-m-d H:i:s')));
        $this->session->set_flashdata('success', 'Proses Kenaikan Kelas berhasil');
      }
      redirect('manage/student/upgrade?m=' . $majors . '&pr=' . $kelas);
    } elseif ($action == "move") {
      $upgrade = $this->input->post('msg');
      $majors   = $this->input->post('m_id');
      $madin   = $this->input->post('madin_id');
      //echo $kelas.'<br>';
      for ($i = 0; $i < count($upgrade); $i++) {
        // echo $upgrade[$i].' : '.$madin.'<br>';
        $this->Student_model->add(array('student_id' => $upgrade[$i], 'student_madin' => $madin, 'student_last_update' => date('Y-m-d H:i:s')));
        // echo $this->db->last_query();
        $this->session->set_flashdata('success', 'Proses Pindah Kelas Pondok berhasil');
      }
      // exit;
      redirect('manage/student/move?m=' . $majors . '&pr=' . $madin);
    } elseif ($action == "move_halaqoh") {
      $upgrade      = $this->input->post('msg');
      $halaqoh_id   = $this->input->post('halaqoh_id');

      for ($i = 0; $i < count($upgrade); $i++) {
        $student = $this->db->query("SELECT relation_student_id FROM halaqoh_relation WHERE relation_id=$upgrade[$i];")->result_array();
        foreach ($student as $dt) {
          $student_id = $dt['relation_student_id'];
          $data = array(
            'relation_id' => $upgrade[$i],
            'relation_student_id' => $student_id,
            'relation_halaqoh_id' => $halaqoh_id,
          );
        }
        $this->Student_model->add_halaqoh_relation($data);
      }

      $this->session->set_flashdata('success', 'Proses Pindah Halaqoh berhasil');
      redirect('manage/student/move_halaqoh?pr=' . $halaqoh_id);
    } elseif ($action == "setting_halaqoh") {
      $majors         = $this->input->post('majors_id');
      $kelas          = $this->input->post('class_id');
      $siswa_halaqoh  = $this->input->post('msg');
      $halaqoh_id     = $this->input->post('halaqoh_id');
      $halaqoh        = implode(',', $halaqoh_id);

      for ($i = 0; $i < count($siswa_halaqoh); $i++) {
        $student_student_id = $siswa_halaqoh[$i];
        // echo $student_student_id;

        for ($j = 0; $j < count($halaqoh_id); $j++) {
          $relation_halaqoh_id = $halaqoh_id[$j];

          // Periksa apakah data sudah ada dalam tabel halaqoh_relation
          $cek_student = $this->db->query("SELECT * FROM halaqoh_relation WHERE relation_student_id = $student_student_id AND relation_halaqoh_id = $relation_halaqoh_id")->result_array();

          if (empty($cek_student)) {
            // Jika tidak ada, tambahkan data ke tabel halaqoh_relation
            $params = array(
              'relation_student_id' => $student_student_id,
              'relation_halaqoh_id' => $relation_halaqoh_id,
            );
            $this->Student_model->add_halaqoh_relation($params);
          } else {
            echo 'Data sudah ada'; // Data sudah ada, Anda bisa menambahkan pesan atau tindakan sesuai kebutuhan.
          }
        }
        // exit;

        $this->Student_model->add(array('student_id' => $siswa_halaqoh[$i], 'student_halaqoh' => $halaqoh, 'student_last_update' => date('Y-m-d H:i:s')));
        $this->session->set_flashdata('success', 'Proses Setting Halaqoh berhasil');
      }
      redirect('manage/student/setting_halaqoh?m=' . $majors . '&pr=' . $kelas . '&hl=' . $halaqoh);
    } elseif ($action == "printPdf") {
      $this->load->library('dompdflib');
      $idcard = $this->input->post('msg');
      for ($i = 0; $i < count($idcard); $i++) {
        $print[] = $idcard[$i];
      }

      $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
      $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
      $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));
      $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
      $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
      $data['student'] = $this->Student_model->get(array('multiple_id' => $print));

      for ($i = 0; $i < count($data['student']); $i++) {
        $this->barcode2($data['student'][$i]['student_nis'], '');
      }



      $file_pdf = 'KARTU_' . date('d_m_Y');

      $paper = 'A4';

      $orientation = "potrait";

      $html = $this->load->view('student/student_multiple_pdf', $data, true);
      $this->dompdflib->generate($html, $file_pdf, $paper, $orientation);
    }
  }


  // satuan
  function printPdf($id = NULL)
  {
    $this->load->library('dompdflib');
    $this->load->helper(array('tanggal'));
    $this->load->model('student/Student_model');
    $this->load->model('setting/Setting_model');
    if ($id == NULL)
      redirect('manage/student');

    $data['setting_kartu_depan'] = $this->Setting_model->get(array('id' => 18));
    $data['setting_kartu_belakang'] = $this->Setting_model->get(array('id' => 19));
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['student'] = $this->Student_model->get(array('id' => $id));
    $this->barcode2($data['student']['student_nis'], '');

    $file_pdf = 'Cetak-' . $data['student']['student_full_name'] . '-' . $data['student']['student_full_name'];

    $paper = 'B6';

    $orientation = "potrait";
    $html = $this->load->view('student/student_pdf', $data, true);
    $this->dompdflib->generate($html, $file_pdf, $paper, $orientation);
  }



  private function barcode2($sparepart_code, $barcode_type = 39, $scale = 6, $fontsize = 1, $thickness = 30, $dpi = 72)
  {

    $this->load->library('upload');
    $config['upload_path'] = FCPATH . 'media/barcode_student/';

    /* create directory if not exist */
    if (!is_dir($config['upload_path'])) {
      mkdir($config['upload_path'], 0777, TRUE);
    }
    $this->upload->initialize($config);

    // CREATE BARCODE GENERATOR
    // Including all required classes
    require_once(APPPATH . 'libraries/barcodegen/BCGFontFile.php');
    require_once(APPPATH . 'libraries/barcodegen/BCGColor.php');
    require_once(APPPATH . 'libraries/barcodegen/BCGDrawing.php');

    // Including the barcode technology
    // Ini bisa diganti-ganti mau yang 39, ato 128, dll, liat di folder barcodegen
    require_once(APPPATH . 'libraries/barcodegen/BCGcode39.barcode.php');

    // Loading Font
    // kalo mau ganti font, jangan lupa tambahin dulu ke folder font, baru loadnya di sini
    $font = new BCGFontFile(APPPATH . 'libraries/font/Arial.ttf', $fontsize);

    // Text apa yang mau dijadiin barcode, biasanya kode produk
    $text = $sparepart_code;

    // The arguments are R, G, B for color.
    $color_black = new BCGColor(0, 0, 0);
    $color_white = new BCGColor(255, 255, 255);

    $drawException = null;
    try {
      $code = new BCGcode39(); // kalo pake yg code39, klo yg lain mesti disesuaikan
      $code->setScale($scale); // Resolution
      $code->setThickness($thickness); // Thickness
      $code->setForegroundColor($color_black); // Color of bars
      $code->setBackgroundColor($color_white); // Color of spaces
      $code->setFont($font); // Font (or 0)
      $code->parse($text); // Text
    } catch (Exception $exception) {
      $drawException = $exception;
    }

    /* Here is the list of the arguments
    1 - Filename (empty : display on screen)
    2 - Background color */
    $drawing = new BCGDrawing('', $color_white);
    if ($drawException) {
      $drawing->drawException($drawException);
    } else {
      $drawing->setDPI($dpi);
      $drawing->setBarcode($code);
      $drawing->draw();
    }
    // ini cuma labeling dari sisi aplikasi saya, penamaan file menjadi png barcode.
    $filename_img_barcode = $sparepart_code . '_' . $barcode_type . '.png';
    // folder untuk menyimpan barcode
    $drawing->setFilename(FCPATH . 'media/barcode_student/' . $sparepart_code . '.png');
    // proses penyimpanan barcode hasil generate
    $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

    return $filename_img_barcode;
  }

  public function get_mclass()
  {
    $id_majors = $this->input->post('id_majors');
    $dataKamar  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();

    echo '<select required="" name="modal_class" id="modal_class" class="form-control">';
    echo '<option value="">-Pilih Kelas-</option>';
    foreach ($dataKamar as $row) {
      echo '<option value="' . $row['class_id'] . '">' . $row['class_name'] . '</option>';
    }
    echo '</select>';
  }

  public function get_mmadin()
  {
    $id_majors = $this->input->post('id_majors');
    $dataMadin  = $this->db->query("SELECT * FROM madin ORDER BY madin_id ASC")->result_array();

    echo '<select required="" name="modal_madin" id="modal_madin" class="form-control">';
    echo '<option value="">-Pilih Kelas Pondok-</option>
		  <option value="all">Semua Kelas Pondok</option>';
    foreach ($dataMadin as $row) {
      echo '<option value="' . $row['madin_id'] . '">' . $row['madin_name'] . '</option>';
    }
    echo '</select>';
  }

  public function get_xls_class()
  {

    $id_majors  = $this->input->post('id_majors');
    $dataKamar  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();

    echo '<select required="" name="xls_class" id="xls_class" class="form-control">';
    echo '<option value="">-Pilih Kelas-</option>
		  <option value="all">Semua Kelas</option>';
    foreach ($dataKamar as $row) {
      echo '<option value="' . $row['class_id'] . '">' . $row['class_name'] . '</option>';
    }
    echo '</select>';
  }

  public function get_xls_madin()
  {

    $id_majors  = $this->input->post('id_majors');
    $dataMadin  = $this->db->query("SELECT * FROM madin ORDER BY madin_id ASC")->result_array();

    echo '<select required="" name="xls_madin" id="xls_madin" class="form-control">';
    echo '<option value="">-Pilih Kelas Pondok-</option>
		  <option value="all">Semua Kelas Pondok</option>';
    foreach ($dataMadin as $row) {
      echo '<option value="' . $row['madin_id'] . '">' . $row['madin_name'] . '</option>';
    }
    echo '</select>';
  }

  public function print_students()
  {
    $this->load->library('html2pdflib');

    $params = array();

    $id_majors = $this->input->post('modal_majors');
    $id_kelas  = $this->input->post('modal_class');
    $id_madin  = $this->input->post('modal_madin');

    $data['majors']  = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$id_majors'")->row_array();
    $majors          = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$id_majors'")->row_array();

    if ($id_kelas != 'all') {
      $params['class_id']  = $id_kelas;
      $data['kelas']       = $this->db->query("SELECT class_name FROM class WHERE class_id = '$id_kelas'")->row_array();
      $kelas               = $this->db->query("SELECT class_name FROM class WHERE class_id = '$id_kelas'")->row_array();
    } else {
      $data['kelas']   = array('class_name' => 'Semua Kelas');
      $kelas           = array('class_name' => 'Semua Kelas');
    }

    if ($id_madin != 'all') {
      $params['madin_id']  = $id_madin;
      $data['madin']       = $this->db->query("SELECT madin_name FROM madin WHERE madin_id = '$id_madin'")->row_array();
      $madin               = $this->db->query("SELECT madin_name FROM madin WHERE madin_id = '$id_madin'")->row_array();
    } else {
      $data['madin']   = array('madin_name' => 'Semua Kelas Pondok');
      $madin           = array('madin_name' => 'Semua Kelas Pondok');
    }

    $data['student'] = $this->Student_model->get($params);

    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

    $filename = 'Data_Santri_' . $majors['majors_name'] . '_Kelas Pondok_' . $kelas['class_name'] . '_Kelas Pondok_' . $madin['madin_name'] . '.pdf';

    $html = $this->load->view('student/print_students', $data, TRUE);

    $paper = 'A4';
    $orientation = 'l';
    $download = FALSE;

    $this->html2pdflib->generate($html, $filename, $download, $paper, $orientation);
  }

  public function excel_students()
  {
    $params = array();

    $id_majors = $this->input->post('xls_majors');
    $id_kelas  = $this->input->post('xls_class');
    $id_madin  = $this->input->post('xls_madin');

    $params['majors_id'] = $id_majors;

    if ($id_kelas != 'all') {
      $params['class_id']  = $id_kelas;
      $data['kelas']   = $this->db->query("SELECT class_name FROM class WHERE class_id = '$id_kelas'")->row_array();
    } else {
      $data['kelas']   = array('class_name' => 'Semua Kelas');
    }

    if ($id_madin != 'all') {
      $params['madin_id']  = $id_madin;
      $data['madin']   = $this->db->query("SELECT madin_name FROM madin WHERE madin_id = '$id_madin'")->row_array();
    } else {
      $data['madin']   = array('madin_name' => 'Semua Kelas Pondok');
    }

    $data['student'] = $this->Student_model->get($params);
    $data['majors']  = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$id_majors'")->row_array();

    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

    $this->load->view('student/excel_students', $data);
  }
}
