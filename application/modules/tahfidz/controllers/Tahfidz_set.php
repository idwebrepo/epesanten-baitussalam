<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Tahfidz_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct(TRUE);
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }
    $this->load->model(array('student/Student_model', 'tahfidz/Tahfidz_model', 'period/Period_model', 'setting/Setting_model', 'logs/Logs_model'));
  }

  // payment view in list
  public function index($offset = NULL, $id = NULL)
  {
    // Apply Filter
    // Get $_GET variable
    $f = $this->input->get(NULL, TRUE);

    $data['f']  = $f;
    $siswa      = null;
    $periodID   = null;
    $siswaID    = null;
    $params     = array();

    // Tahun Ajaran
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '' && $f['n'] != '0') {
      $params['period_id']      = $f['n'];
      $periodID                 = $f['n'];
    }

    // Siswa
    if (isset($f['r']) && !empty($f['r']) && $f['r'] != '') {
      $params['student_nis']    = $f['r'];
      $siswa = $this->Student_model->get(array('student_nis' => $f['r']));
      $siswaID = $siswa['student_id'];
    }

    $data['siswa'] = $this->Student_model->get(array('student_id' => $siswa['student_id'], 'group' => TRUE));

    $data['periodActive'] = $this->db->query("SELECT period_id FROM period WHERE period_status = '1'")->row_array();
    $data['period']     = $this->Period_model->get($params);
    $data['tahfidz']      = $this->Tahfidz_model->get(array('period_id' => $periodID, 'student_id' => $siswa['student_id'], 'order_by' => 'tahfidz_id'));
    $data['tahfidzSum']      = $this->Tahfidz_model->get_sum(array('period_id' => $periodID, 'student_id' => $siswa['student_id']));

    $data['majors']     = $this->Student_model->get_majors();

    $data['title']      = 'Tahfidz Siswa';
    $data['main']       = 'tahfidz/tahfidz_list';
    $this->load->view('manage/layout', $data);
  }

  public function add()
  {
    if ($_POST == TRUE) {

      $period = $this->input->post('tahfidz_period_id');
      $nis    = $this->input->post('tahfidz_student_nis');

      $params['tahfidz_date']          = $this->input->post('tahfidz_date');
      $params['tahfidz_new']           = $this->input->post('tahfidz_new');
      $params['tahfidz_new_note']      = $this->input->post('tahfidz_new_note');
      $params['tahfidz_murojaah']      = $this->input->post('tahfidz_murojaah');
      $params['tahfidz_murojaah_note'] = $this->input->post('tahfidz_murojaah_note');
      $params['tahfidz_student_id']    = $this->input->post('tahfidz_student_id');
      $params['tahfidz_period_id']     = $this->input->post('tahfidz_period_id');
      $params['tahfidz_user_id']       = $this->session->userdata('uid');

      $this->Tahfidz_model->add($params);
    }

    $this->session->set_flashdata('success', ' Tambah Setoran Tahfidz Berhasil');
    redirect('manage/tahfidz?n=' . $period . '&r=' . $nis);
  }

  public function delete($id = NULL)
  {
    if ($_POST) {
      $period = $this->input->post('period_id');
      $nis    = $this->input->post('student_nis');

      $this->Tahfidz_model->delete($id);
      $this->session->set_flashdata('success', 'Hapus Tahfidz Berhasil');

      redirect('manage/tahfidz?n=' . $period . '&r=' . $nis);
    } elseif (!$_POST) {
      $this->session->set_flashdata('failed', 'Hapus Tahfidz Gagal');

      redirect('manage/tahfidz?n=' . $period . '&r=' . $nis);
    }
  }

  function printBook()
  {
    $this->load->helper(array('dompdf'));
    $this->load->helper(array('tanggal'));
    $this->load->helper(array('terbilang'));
    $this->load->model('student/Student_model');
    $this->load->model('setting/Setting_model');
    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;
    $siswa = null;
    $siswaID = null;
    $periodID = null;
    $params = array();

    // Tahun Ajaran
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '' && $f['n'] != '0') {
      $params['period_id']  = $f['n'];
      $periodID             = $f['n'];
    }

    // Siswa
    if (isset($f['r']) && !empty($f['r']) && $f['r'] != '') {
      $params['student_nis']    = $f['r'];
      $siswa = $this->Student_model->get(array('student_nis' => $f['r']));
      $siswaID = $siswa['student_id'];
    }

    $data['siswa'] = $this->Student_model->get(array('period_id' => $periodID, 'student_id' => $siswa['student_id'], 'group' => TRUE));

    $data['period']     = $this->Period_model->get($params);
    $data['tahfidz']        = $this->Tahfidz_model->get(array('period_id' => $periodID, 'student_id' => $siswa['student_id']));
    $data['tahfidzSum']      = $this->Tahfidz_model->get_sum(array('period_id' => $periodID, 'student_id' => $siswa['student_id']));

    $data['unit']    = $this->Student_model->get_unit(array('status' => 1));
    $data['majors']  = $this->Student_model->get_majors();
    // endtotal
    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));
    $tanggal = date_create($f['d']);
    $dformat = date_format($tanggal, 'dmYHis');
    $this->barcode2('TAHFIDZ' . $siswa['student_nis'], '');
    $html = $this->load->view('tahfidz/tahfidz_cetak_pdf', $data, TRUE);
    $data = pdf_create($html, 'Buku_Tahfidz_' . $siswa['student_full_name'] . '_' . date('Y-m-d'), TRUE, 'A4', TRUE);
  }

  private function barcode2($sparepart_code, $barcode_type = 39, $scale = 6, $fontsize = 1, $thickness = 30, $dpi = 72)
  {

    $this->load->library('upload');
    $config['upload_path'] = FCPATH . 'media/barcode_tahfidz/';

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
    $drawing->setFilename(FCPATH . 'media/barcode_tahfidz/' . $sparepart_code . '.png');
    // proses penyimpanan barcode hasil generate
    $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

    return $filename_img_barcode;
  }

  function class_searching()
  {
    $id_majors = $this->input->post('id_majors');
    $dataKelas  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();

    echo '<div class="col-md-2">  
				<div class="form-group">
				    <label>Kelas</label>
		            <select name="c" id="class_id" class="form-control" onchange="get_siswa()">
                    <option value="">-- Pilih Kelas --</option>
                    <option value="0"> Semua Kelas </option>';
    foreach ($dataKelas as $row) {

      echo '<option value="' . $row['class_id'] . '">';

      echo $row['class_name'];

      echo '</option>';
    }
    echo '</select>
				</div>
			</div>';
  }

  function student_searching()
  {
    $id_majors = $this->input->post('id_majors');
    $id_class  = $this->input->post('id_class');
    $dataStudent  = $this->db->query("SELECT * FROM student WHERE majors_majors_id = '$id_majors' AND class_class_id = '$id_class' ORDER BY student_nis ASC")->result_array();

    echo '<div class="col-md-2">  
				<div class="form-group">
				    <label>Siswa</label>
		            <select name="m" id="student_id" class="form-control">
                    <option value="">-- Pilih Siswa --</option>
                    <option value="0"> Semua Siswa </option>';
    foreach ($dataStudent as $row) {

      echo '<option value="' . $row['student_id'] . '">';

      echo $row['student_full_name'];

      echo '</option>';
    }
    echo '</select>
				</div>
			</div>';
  }

  public function report()
  {
    $q = $this->input->get(NULL, TRUE);

    $data['q'] = $q;

    $params = array();

    if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
      $params['period_id'] = $q['p'];
    }

    if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
      $params['majors_id'] = $q['k'];
    }

    if (isset($q['c']) && !empty($q['c']) && $q['c'] != '' && $q['c'] != '0') {
      $params['class_id']  = $q['c'];
    }

    if (isset($q['m']) && !empty($q['m']) && $q['m'] != '' && $q['m'] != '0') {
      $params['student_id'] = $q['m'];
    }

    $data['period']     = $this->Period_model->get($params);
    $data['class']      = $this->Student_model->get_class($params);
    $data['majors']     = $this->Student_model->get_majors($params);
    $data['student']    = $this->Student_model->get($params);
    $data['tahfidz']    = $this->Tahfidz_model->get_sum($params);

    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $data['title'] = 'Laporan Tahfidz Siswa';
    $data['main'] = 'tahfidz/tahfidz_report';
    $this->load->view('manage/layout', $data);
  }

  public function tahfidz_excel()
  {
    $q = $this->input->get(NULL, TRUE);

    $data['q'] = $q;

    $params = array();

    if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
      $params['period_id'] = $q['p'];
    }

    if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
      $params['majors_id'] = $q['k'];
    }

    if (isset($q['c']) && !empty($q['c']) && $q['c'] != '' && $q['c'] != '0') {
      $params['class_id']  = $q['c'];
    }

    if (isset($q['m']) && !empty($q['m']) && $q['m'] != '' && $q['m'] != '0') {
      $params['student_id'] = $q['m'];
    }

    $data['period']     = $this->Period_model->get($params);
    $data['class']      = $this->Student_model->get_class($params);
    $data['majors']     = $this->Student_model->get_majors($params);
    $data['student']    = $this->Student_model->get($params);
    $data['tahfidz']    = $this->Tahfidz_model->get_sum($params);

    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

    $config['suffix'] = '?' . http_build_query($_GET, '', "&");

    $this->load->view('tahfidz/tahfidz_xls', $data);
  }

  public function import_tahfidz()
  {
    //redirect('manage/student/import');

    $data['majors']     = $this->Student_model->get_majors();
    $data['title']      = 'Import Data Tahfidz';
    $data['main']       = 'tahfidz/tahfidz_import';
    $data['action']     = site_url(uri_string());
    $this->load->view('manage/layout', $data);
  }

  public function download()
  {
    $data = file_get_contents("./media/template_excel/Template_Data_Tahfidz.xls");
    $name = 'Template_Data_Tahfidz.xls';

    $this->load->helper('download');
    force_download($name, $data);
  }

  // public function do_import()
  // {
  //   $this->load->library('excel');

  //   if (isset($_FILES["file"]["name"])) {
  //     $path = $_FILES["file"]["tmp_name"];
  //     $object = PHPExcel_IOFactory::load($path);

  //     $data = array(); // Inisialisasi array untuk menampung data

  //     foreach ($object->getWorksheetIterator() as $worksheet) {
  //       $highestRow = $worksheet->getHighestRow();
  //       $highestColumn = $worksheet->getHighestColumn();

  //       // Menggunakan $highestColumn untuk mendapatkan jumlah kolom, misal: 'F' menjadi 6
  //       $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

  //       for ($row = 3; $row <= $highestRow; $row++) {
  //         // Inisialisasi array untuk menyimpan data pada setiap iterasi baris
  //         $rowData = array();

  //         for ($col = 0; $col < $highestColumnIndex; $col++) {
  //           // Dapatkan nilai sel di kolom dan baris tertentu
  //           $cellValue = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
  //           // Simpan nilai sel ke dalam array rowData
  //           $rowData[] = $cellValue;
  //         }

  //         // Pastikan baris tidak sepenuhnya kosong sebelum menyimpan ke dalam $data
  //         if (!empty(array_filter($rowData))) {
  //           // Lakukan pemrosesan hanya jika baris tidak kosong
  //           $student_nis = isset($rowData[0]) ? $rowData[0] : '';
  //           $period_id = isset($rowData[1]) ? $rowData[1] : '';
  //           // $date = isset($rowData[2]) ? $rowData[2] : '';
  //           $hafalan_baru = isset($rowData[3]) ? $rowData[3] : '';
  //           $ket_hafalan_baru = isset($rowData[4]) ? $rowData[4] : '';
  //           $murojaah = isset($rowData[5]) ? $rowData[5] : '';
  //           $mur_hafalan_baru = isset($rowData[6]) ? $rowData[6] : '';

  //           $q = $this->db->query("SELECT student_id FROM student WHERE student_nis = '$student_nis'")->row_array();
  //           $student_id = $q['student_id'];
  //           // Mengambil nilai tanggal dalam format float dari Excel
  //           $excelDate = $worksheet->getCellByColumnAndRow(2, $row)->getValue();

  //           // Mengonversi nilai float Excel ke format tanggal yang bisa diinterpretasikan oleh PHP
  //           $date = PHPExcel_Shared_Date::ExcelToPHP($excelDate);

  //           // Ubah $date menjadi format tanggal (Y-m-d)
  //           $formattedDate = date("Y-m-d", $date);

  //           $data[] = array(
  //             'tahfidz_period_id' => $period_id,
  //             'tahfidz_student_id' => $student_id,
  //             'tahfidz_date' => $formattedDate,
  //             'tahfidz_new' => $hafalan_baru,
  //             'tahfidz_new_note' => $ket_hafalan_baru,
  //             'tahfidz_murojaah' => $murojaah,
  //             'tahfidz_murojaah_note' => $mur_hafalan_baru,
  //             'tahfidz_user_id' => $this->session->userdata('uid')
  //           );
  //         }
  //       }
  //     }
  //     // var_dump($data); // Melakukan pengecekan sementara
  //     // exit;

  //     $this->db->insert_batch('tahfidz', $data);
  //     $this->session->set_flashdata('success', 'Import Data Siswa Berhasil');
  //     redirect('manage/tahfidz');
  //   }
  // }

  public function do_import()
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

      $highestColumn = $excelSheet->getHighestColumn();
      $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

      $data = array(); // Inisialisasi array untuk menampung data

      for ($row = 2; $row <= $sheetCount; $row++) {
        // Inisialisasi array untuk menyimpan data pada setiap iterasi baris
        $rowData = array();

        for ($col = 0; $col < $highestColumnIndex; $col++) {
          // Dapatkan nilai sel di kolom dan baris tertentu
          // $cellValue = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
          $cellValue =  $spreadSheetAry[$row][$col];
          // Simpan nilai sel ke dalam array rowData
          $rowData[] = $cellValue;
        }

        // Pastikan baris tidak sepenuhnya kosong sebelum menyimpan ke dalam $data
        if (!empty(array_filter($rowData))) {
          // Lakukan pemrosesan hanya jika baris tidak kosong
          $student_nis = isset($rowData[0]) ? $rowData[0] : '';
          $period_id = isset($rowData[1]) ? $rowData[1] : '';
          // $date = isset($rowData[2]) ? $rowData[2] : '';
          $hafalan_baru = isset($rowData[3]) ? $rowData[3] : '';
          $ket_hafalan_baru = isset($rowData[4]) ? $rowData[4] : '';
          $murojaah = isset($rowData[5]) ? $rowData[5] : '';
          $mur_hafalan_baru = isset($rowData[6]) ? $rowData[6] : '';

          $q = $this->db->query("SELECT student_id FROM student WHERE student_nis = '$student_nis'")->row_array();
          $student_id = $q['student_id'];
          // Mengambil nilai tanggal dalam format float dari Excel
          $excelDate = $spreadSheetAry[$row][2];

          // Mengonversi nilai float Excel ke format tanggal yang bisa diinterpretasikan oleh PHP
          // $date = PHPExcel_Shared_Date::ExcelToPHP($excelDate);

          // Ubah $date menjadi format tanggal (Y-m-d)
          // $formattedDate = date("Y-m-d", $excelDate);

          $data[] = array(
            'tahfidz_period_id' => $period_id,
            'tahfidz_student_id' => $student_id,
            'tahfidz_date' => $excelDate,
            'tahfidz_new' => $hafalan_baru,
            'tahfidz_new_note' => $ket_hafalan_baru,
            'tahfidz_murojaah' => $murojaah,
            'tahfidz_murojaah_note' => $mur_hafalan_baru,
            'tahfidz_user_id' => $this->session->userdata('uid')
          );
        }
      }

      // var_dump($data); // Melakukan pengecekan sementara
      // exit;

      $this->db->insert_batch('tahfidz', $data);
      $this->session->set_flashdata('success', 'Import Data Siswa Berhasil');
      redirect('manage/tahfidz');
    }
  }
}
