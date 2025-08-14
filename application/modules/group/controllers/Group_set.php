<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Group_set extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('group/Group_model', 'kegiatan/Kegiatan_model', 'setting/Setting_model', 'period/Period_model', 'student/Student_model'));
    $this->load->helper(array('form', 'url'));
  }

// User_customer view in list
  public function index($offset = NULL) {
    $this->load->library('pagination');
    $s = $this->input->get(NULL, TRUE);
    $f = $this->input->get(NULL, TRUE);

    $data['s'] = $s;
    $data['f'] = $f;

    $params = array();

    $data['group'] = $this->Group_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    //$config['per_page'] = 10;
    //$config['uri_segment'] = 4;
    $config['base_url'] = site_url('manage/group/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    
    $data['title'] = 'Data Group Kegiatan';
    $data['main'] = 'group/group_list';
    $this->load->view('manage/layout', $data);
  }


// Add group and Update
  public function add($id = NULL) {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('group_kegiatan_id', 'Kegiatan', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';
    
    $data['kegiatan'] = $this->Kegiatan_model->get();
    $data['period']   = $this->Period_model->get();
    $data['majors']   = $this->Student_model->get_majors();

    if ($_POST AND $this->form_validation->run() == TRUE) {
      
      if ($this->input->post('group_id')) {
        $params['group_id'] = $this->input->post('group_id');
      }
      $params['group_name']         = $this->input->post('group_name');
      $params['group_kegiatan_id']  = $this->input->post('group_kegiatan_id');
      $params['group_date']         = $this->input->post('group_date');
      $params['group_tempat']       = $this->input->post('group_tempat');
      $params['group_keterangan']   = $this->input->post('group_keterangan');
      $status                       = $this->Group_model->add($params);

      
      $this->session->set_flashdata('success', $data['operation'] . ' Data group');
      redirect('manage/group');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      if ($this->input->post('group_id')) {
        redirect('manage/group/edit/' . $this->input->post('group_id'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Group_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/group');
        } else {
          $data['group'] = $object;
        }
      }
      $data['title'] = $data['operation'] . ' Kelompok Halaqoh';
      $data['main'] = 'group/group_add';
      $this->load->view('manage/layout', $data);
    }
  }


   // View data detail
  public function view($id = NULL) {
    $x = $this->uri->segment('4');

    $f = $this->input->get(NULL, TRUE);
    $data['f'] = $f;
    $params = array();

    if (isset($f['pr']) && !empty($f['pr']) && $f['pr'] != '') {
      $params['halaqoh_id'] = $f['pr'];
      $data['halaqoh_id'] = $f['pr'];
    }

    $query = $this->db->query("SELECT student_nis, student_full_name, class_name, halaqoh_name, student_status
                                FROM student a
                                LEFT JOIN halaqoh_group b ON a.student_id = b.halaqoh_group_student_id 
                                LEFT JOIN class c ON a.class_class_id = c.class_id
                                LEFT JOIN halaqoh d ON a.student_halaqoh = d.halaqoh_id
                                WHERE b.halaqoh_group_group_id = $x");
    $data['student_groups'] = $query->result_array();

    // $paramsPage = $params;
    // $params['offset']       = $offset;
    $data['student']        = $this->Student_model->get($params);
    $data['peserta']        = $this->Group_model->get_peserta(array('peserta_group_id' => $id));
    $data['group']          = $this->Group_model->get(array('id' => $id));
    $data['halaqoh']        = $this->Student_model->get_halaqoh();
    $data['title']          = 'Detail Data Kelompok Halaqoh & Peserta';
    $data['main']           = 'group/group_view';
    $this->load->view('manage/layout', $data);
  }


// Delete to database
  public function delete($id = NULL) {
    if ($this->session->userdata('uroleid')!= SUPERUSER){
      redirect('manage');
    }
    // $satuan = $this->Group_model->get_peserta(array('id'=>$id));

    if ($_POST) {

      // if (count($satuan) > 0) {
      //   $this->session->set_flashdata('failed', 'Data Satuan tidak dapat dihapus');
      //   redirect('manage/group');
      // }

      $this->Group_model->delete_peserta($id);
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
      $this->session->set_flashdata('success', 'Hapus Data Peserta sgroup berhasil');
      redirect('manage/group');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/group/edit/' . $id);
    }  
  }

  // Fungsi menambah data peserta =================================================================================
  public function add_peserta($id = NULL) {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('peserta_name', 'Nama Peserta', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';
    
  
    if ($_POST AND $this->form_validation->run() == TRUE) {
      
      if ($this->input->post('peserta_id')) {
        $params['peserta_id'] = $id;
      } else {
        $params['employee_input_date'] = date('Y-m-d H:i:s');
        $default = "123456";
        $pass = $this->input->post('peserta_password');
        if($pass !=''){
            $params['peserta_password'] = md5($pass);
        } else {
            $params['peserta_password'] = md5($default);
        }
      }
      
      $telepon    = $this->input->post('peserta_phone');

      if(!preg_match('/[^+0-9]/',trim($telepon)))
      {
          if(substr(trim($telepon), 0, 1)=='+')
          {
          $hp = trim($telepon);
          }
          elseif(substr(trim($telepon), 0, 1)=='0')
          {
          $hp = '+62'.substr(trim($telepon), 1);
          }
          elseif(substr(trim($telepon), 0, 2)=='62')
          {
          $hp = '+'.trim($telepon);
          }
          elseif(substr(trim($telepon), 0, 1)=='8')
          {
          $hp = '+62'.trim($telepon);
          }
          else
          {
          $hp = '+'.trim($telepon);
          }		 
      }
      
      $params['peserta_nim']            = $this->input->post('peserta_nim');
      $params['peserta_name']           = $this->input->post('peserta_name');
      $params['peserta_phone']          = $hp;
      $params['peserta_email']          = $this->input->post('peserta_email');
      $params['peserta_group_id']       = $this->input->post('peserta_group_id');
      $params['peserta_gender']         = $this->input->post('peserta_gender');
      $params['peserta_tempat_lahir']   = $this->input->post('peserta_tempat_lahir');
      $params['peserta_last_update']    = date('Y-m-d H:i:s');
      $params['peserta_tanggal_lahir']  = $this->input->post('peserta_tanggal_lahir');
      $params['peserta_majors_id']      = $this->input->post('peserta_majors_id'); 
      $params['peserta_address']        = $this->input->post('peserta_address'); 
      $params['peserta_status']         = $this->input->post('peserta_status');
      $status                           = $this->Group_model->add_peserta($params);
      $id                               = $this->input->post('peserta_group_id');

      if (!empty($_FILES['peserta_photo']['name'])) {
        $paramsupdate['peserta_photo'] = $this->do_upload($name = 'peserta_photo', $fileName= substr(base64_encode($params['peserta_name']), 5));
      } 

      $paramsupdate['peserta_id'] = $status;
      $this->Group_model->add($paramsupdate);
      $this->session->set_flashdata('success', $data['operation'] . ' Peserta ');
      redirect('manage/group/view/'.$id);
  
      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      if ($this->input->post('id_peserta')) {
        redirect('manage/group/view/'.$id);
      }
  
      // Edit mode
      if (!is_null($id)) {
        $object = $this->Group_model->get_peserta(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/group/view/'.$id);
        } else {
          $data['peserta'] = $object;
        }
      }
      $data['id_group'] = $_GET['id_group'];
      $data['majors'] = $this->Student_model->get_majors();
      $data['title'] = $data['operation'] . ' Tambah Peserta';
      $data['main'] = 'group/peserta_add';
      $this->load->view('manage/layout', $data);
    }
  }

  // Delete to database
  public function delete_peserta($id = NULL) {
    if ($this->session->userdata('uroleid')!= SUPERUSER){
      redirect('manage');
    }
    // $satuan = $this->Group_model->get_satuan(array('id_jenis'=>$id));

    if ($_POST) {

      // if (count($satuan) > 0) {
      //   $this->session->set_flashdata('failed', 'Data Satuan tidak dapat dihapus');
      //   redirect('manage/lembaga');
      // }

      $this->Group_model->delete_jenis($id);
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
      $this->session->set_flashdata('success', 'Hapus Jenis berhasil');
      redirect('manage/manage/group/view/'.$id);
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/lembaga/group/view/' . $id);
    }  
  }

  public function report($id = NULL) {
    $x = $this->uri->segment('4');

    $f = $this->input->get(NULL, TRUE);
    $data['f'] = $f;
    $params = array();

    if (isset($f['pr']) && !empty($f['pr']) && $f['pr'] != '') {
      $params['halaqoh_id'] = $f['pr'];
      $data['halaqoh_id'] = $f['pr'];
    }

    $query = $this->db->query("SELECT *
                                FROM student a
                                LEFT JOIN presensi_halaqoh b ON a.student_id = b.presensi_halaqoh_student_id
                                WHERE b.presensi_halaqoh_group_id = $id");

    $data['student'] = $query->result_array();

    // $paramsPage = $params;
    // $params['offset']       = $offset;
    // $data['student']        = $this->Student_model->get($params);
    $data['peserta']        = $this->Group_model->get_peserta(array('peserta_group_id' => $id));
    $data['group']          = $this->Group_model->get(array('id' => $id));
    $data['halaqoh']        = $this->Student_model->get_halaqoh();
    $data['title']          = 'Report Data Presensi Group Kelompok Halaqoh';
    $data['main']           = 'group/group_view_report';
    $this->load->view('manage/layout', $data);
  }

  function cetak_data(){
      ob_start();
        
      $q = $this->input->get(NULL, TRUE);

      $data['q'] = $q;

      // $param = array();
      $params = array();
      
      if (isset($q['ds']) && !empty($q['ds']) && $q['ds'] != '' && $q['ds'] != '0') {
        $params['date_start']  = $q['ds'];
        $data['date_start']	= $q['ds'];
      }	
      
      if (isset($q['de']) && !empty($q['de']) && $q['de'] != '' && $q['de'] != '0') {
        $params['date_end']  = $q['de'];
        $data['date_end']	= $q['de'];
      }	

      // echo $data['date_start'];
      // exit;
      $data['group'] = $this->Group_model->get($params);
      
      $data['period'] 	= $this->db->query("SELECT period_id, period_start, period_end FROM period WHERE period_status='1' ")->row_array();

      $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME)); 
      $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS)); 
      $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE)); 

      $filename = 'Laporan Data group.pdf';
      
      $this->load->view('group/report_print', $data);
      
      $html = ob_get_contents();
      ob_clean();
      
      require_once('./assets/html2pdf/html2pdf.class.php');
      $pdf = new HTML2PDF('P','A4','en', false, 'ISO-8859-15',array(10, 5, 10, 5));
      
      $pdf->setDefaultFont('arial');
      $pdf->setTestTdInOnePage(false);
      $pdf->pdf->SetDisplayMode('fullpage');
      $pdf->WriteHTML($html);
      $pdf->Output($filename, '');
  }

  public function multiple(Type $var = null)
  {
    $group_id   = $this->input->post('group_id');
    $student_id = $this->input->post('student_id');
    $halaqoh_id = $this->input->post('halaqoh_id');

    $data = array();

    // Cek apakah data sudah ada di tabel halaqoh_group sebelumnya
    foreach($student_id as $item) {
        $existing_data = $this->db->get_where('halaqoh_group', array(
            'halaqoh_group_student_id' => $item,
            'halaqoh_group_group_id' => $group_id
        ))->row_array();

        if(!$existing_data){
            $data[] = array(
                'halaqoh_group_student_id' => $item,
                'halaqoh_group_group_id' => $group_id,
            );
        }
    }

    // Insert data ke dalam tabel halaqoh_group
    if (count($data) > 0) {
        $this->db->insert_batch('halaqoh_group', $data);
        $this->session->set_flashdata('success', 'Data berhasil ditambahkan');
    } else {
        $this->session->set_flashdata('warning', 'Data gagal ditambahkan');
    }
    
    redirect('manage/group/view/' . $group_id);
  }
}
