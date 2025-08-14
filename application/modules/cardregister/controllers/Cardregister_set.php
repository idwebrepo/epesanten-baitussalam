<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Cardregister_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('student/Student_model', 'setting/Setting_model'));
    $this->load->helper(array('form', 'url'));
  }

  // User_customer view in list
  public function index($offset = NULL)
  {
    $this->load->library('pagination');

    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;

    $params = array();
    // Nip
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['majors_name'] = $f['n'];
    }

    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));

    $data['title'] = 'Data Siswa';
    $data['main'] = 'cardregister/cardregister_list';
    $this->load->view('manage/layout', $data);
  }

  function get_data()
  {
    $data = $this->db->query("SELECT student.student_nis, student.student_full_name,
    majors.majors_short_name, class.class_name,
    cards.rfid, cards.pin FROM student
    JOIN majors ON majors.majors_id = student.majors_majors_id
    JOIN class ON class.class_id = student.class_class_id
    LEFT JOIN cards ON cards.nis = student.student_nis")->result();

    echo json_encode($data);
  }

  function set_data()
  {
    $nis = $this->input->post('nis');
    $rfid = $this->input->post('rfid');
    $pin = $this->input->post('pin');

    $pin = $pin;

    $student     = $this->db->query("SELECT * FROM cards WHERE nis = '$nis'")->result_array();

    if (count($student) > 0) {

      $data = array(
        'rfid' => $rfid,
        'pin' => md5($pin),
        'updated_at' => date('Y-m-d H:i:s')
      );

      $this->db->where('nis', $nis);
      $data = $this->db->update('cards', $data);
    } else {

      $data = array(
        'rfid' => $rfid,
        'nis' => $nis,
        'pin' => md5($pin),
        'status' => 1
      );

      $data = $this->db->insert('cards', $data);
    }

    echo json_encode($data);
  }


  public function set_rfid()
  {
    $data['majors']     = $this->Student_model->get_majors();
    $data['title']      = 'Import RFID Santri';
    $data['main']       = 'cardregister/cardregister_set';
    $data['action']     = site_url(uri_string());
    $this->load->view('manage/layout', $data);
  }

  public function download_rfid()
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

    $this->load->view('cardregister/cardregister_rfid', $data);
  }

  // public function set_mass()
  // {
  //   $this->load->library('excel');
  //   if (isset($_FILES["file"]["name"])) {
  //     $path = $_FILES["file"]["tmp_name"];
  //     $object = PHPExcel_IOFactory::load($path);
  //     foreach ($object->getWorksheetIterator() as $worksheet) {
  //       $highestRow = $worksheet->getHighestRow();
  //       $highestColumn = $worksheet->getHighestColumn();
  //       for ($row = 3; $row <= $highestRow; $row++) {
  //         $nis            = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
  //         $rfid           = $worksheet->getCellByColumnAndRow(4, $row)->getValue();

  //         $nis            = str_replace("'", "", $nis);
  //         $rfid           = str_replace("'", "", $rfid);

  //         $getRfid       = $this->db->query("SELECT rfid FROM cards WHERE nis = '$nis'")->result_array();

  //         if (count($$getRfid) > 0) {
  //           $data = array(
  //             'rfid'        => $rfid
  //           );

  //           $this->db->where('nis', $nis);
  //           $this->db->update('cards', $data);
  //         } else {
  //           $data = array(
  //             'nis'         => $nis,
  //             'rfid'        => $rfid,
  //             'pin'         => md5('123456')
  //           );

  //           $this->db->insert('cards', $data);
  //         }
  //       }
  //     }

  //     $this->session->set_flashdata('success', 'Set Kartu RFID Berhasil');
  //     redirect('manage/cardregister');
  //   }
  // }

  public function set_mass()
  {
    // $this->load->library('excel');
    if (isset($_FILES["file"]["name"])) {
      $arr_file = explode('.', $_FILES['file']['name']);
      $extension = end($arr_file);

      $reader = NULL;
      if ('xls' == $extension) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
      } elseif ('xlsx' == $extension) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
      }

      $spreadSheet = $reader->load($_FILES['file']['tmp_name']);
      $excelSheet = $spreadSheet->getActiveSheet();
      $spreadSheetAry = $excelSheet->toArray();
      $sheetCount = count($spreadSheetAry);

      for ($i = 2; $i < $sheetCount; $i++) {
        $nis            = $spreadSheetAry[$i][0];
        $rfid           = $spreadSheetAry[$i][4];

        $nis            = str_replace("'", "", $nis);
        $rfid           = str_replace("'", "", $rfid);

        $getRfid       = $this->db->query("SELECT rfid FROM cards WHERE nis = '$nis'")->result_array();

        if (count($getRfid) > 0) {
          $data = array(
            'rfid'        => $rfid
          );

          $this->db->where('nis', $nis);
          $this->db->update('cards', $data);
        } else {
          $data = array(
            'nis'         => $nis,
            'rfid'        => $rfid,
            'pin'         => md5('123456')
          );

          $this->db->insert('cards', $data);
        }
      }


      $this->session->set_flashdata('success', 'Set Kartu RFID Berhasil');
      redirect('manage/cardregister');
    }
  }
}
