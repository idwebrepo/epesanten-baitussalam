<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Presensi_halaqoh_pegawai extends CI_Controller {

    public function __construct() {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged_pegawai') == NULL) {
            header("Location:" . site_url('pegawai/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('presensi_halaqoh/Presensi_halaqoh_model', 'student/Student_model', 'period/Period_model', 'bulan/Bulan_model', 'setting/Setting_model', 'logs/Logs_model','kegiatan/Kegiatan_model','group/Group_model'));
        $this->load->library('upload');
    }

    // pos view in list
    public function index($offset = NULL) {
        $q = $this->input->get(NULL, TRUE);

		$data['q'] = $q;

		$param = array();
		$params = array();

		if (isset($q['d']) && !empty($q['d']) && $q['d'] != '') {
			$params['date'] = $q['d'];
			$param['date'] = $q['d'];
		}

		if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
			$params['kegiatan_id'] = $q['k'];
			$param['kegiatan_id'] = $q['k'];
		}

		if (isset($q['h']) && !empty($q['h']) && $q['h'] != '') {
			$halaqoh_id  = $q['h'];
			$params['halaqoh_id']  = $q['h'];
			$param['halaqoh_id']  = $q['h'];
		}

        $params['halaqoh_employee_id'] = $this->session->userdata('uid_pegawai');
		
		$data['check']      = $this->Presensi_halaqoh_model->check($param);
		
		$data['group']      = $this->Group_model->get($params);
		$data['kegiatan']   = $this->Kegiatan_model->get($params);
		$data['halaqoh']    = $this->Student_model->get_halaqoh($params);
		$data['dt_halaqoh'] = $this->db->query("SELECT employee_id, employee_name FROM halaqoh a
                                                    JOIN employee b ON a.halaqoh_employee_id=b.employee_id WHERE a.halaqoh_id='$halaqoh_id'")->row_array();
		$data['period']     = $this->db->query("SELECT * FROM period WHERE period_status='1'")->row_array();
		$data['student']    = $this->Student_model->get_student_halaqoh(array('halaqoh_id'=>$halaqoh_id));

        
		$config['suffix'] = '?' . http_build_query($_GET, '', "&");
    
        $data['period'] = $this->db->query("SELECT period_id, period_start, period_end FROM period WHERE period_status = '1'")->row_array();
        
        $date = date('Y-m-d');
        $this_month = pretty_date($date, 'F', false);
        
        $data['bulan'] = $this->db->query("SELECT * FROM month WHERE month_name = '$this_month'")->row_array();
        
        $data['title'] = 'Presensi Kegiatan Halaqoh Santri';
        $data['main'] = 'presensi_halaqoh/pegawai/presensi_halaqoh_list';
        $this->load->view('pegawai/layout', $data);
    }
    
    public function report($offset = NULL) {
        
        $q = $this->input->get(NULL, TRUE);

		$data['q'] = $q;

		$param = array();
		$params = array();
		
		$namaBulan  = NULL;
		$bulan      = date('m');
		$period     = NULL;
		$tahun      = date('Y');
		

		if (isset($q['y']) && !empty($q['y']) && $q['y'] != '') {
			$param['tahun']  = $q['y'];
		}

		if (isset($q['m']) && !empty($q['m']) && $q['m'] != '') {
			$param['month_id'] = $q['m'];
		}

		if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
			$params['kegiatan_id'] = $q['k'];
		}

		if (isset($q['h']) && !empty($q['h']) && $q['h'] != '') {
			$params['halaqoh_id']  = $q['h'];
			$param['halaqoh_id']  = $q['h'];
		}
		
		if (isset($q['m']) && !empty($q['m']) && $q['m'] != '') {
		    
		    
            $namaBulan = $this->db->query("SELECT month_name FROM month WHERE month_id = ".$q['m']."")->row_array();
            
		    if($q['m'] == 1) {
                $bulan = '07';
		    } else if($q['m'] == 2) {
                $bulan = '08';
		    } else if($q['m'] == 3) {
                $bulan = '09';
		    } else if($q['m'] == 4) {
                $bulan = '10';
		    } else if($q['m'] == 5) {
                $bulan = '11';
		    } else if($q['m'] == 6) {
                $bulan = '12';
		    } else if($q['m'] == 7) {
                $bulan = '01';
		    } else if($q['m'] == 8) {
                $bulan = '02';
		    } else if($q['m'] == 9) {
                $bulan = '03';
		    } else if($q['m'] == 10) {
                $bulan = '04';
		    } else if($q['m'] == 11) {
                $bulan = '05';
		    } else if($q['m'] == 12) {
                $bulan = '06';
		    }
		    
            // $period = $this->db->query("SELECT period_id, period_start, period_end FROM period WHERE period_id = ".$q['p']."")->row_array();
            
            // if($q['m'] < 7){
            //    $tahun               = $period['period_start'] ;
            //    $data['namaTahun']   = $period['period_start'] ;
            // } else {
            //    $tahun               = $period['period_end'] ; 
            //    $data['namaTahun']   = $period['period_end'] ; 
            // }
            
            $data['namaBulan']      = $namaBulan['month_name'];
		}
		
		$data['check']      = $this->Presensi_halaqoh_model->check($param);
        
		$data['halaqoh']    = $this->Student_model->get_halaqoh($params);
		$data['majors']     = $this->Student_model->get_majors($params);
		$data['student']    = $this->Student_model->get_student_halaqoh($params);
        
		$config['suffix'] = '?' . http_build_query($_GET, '', "&");
    
        $data['period'] = $this->db->query("SELECT period_id, period_start, period_end FROM period")->result_array();
        
        $awalbulan = $tahun . '-' . $bulan . '-01';
        $akhirbulan = date('Y-m-d', strtotime("+1 month", strtotime($awalbulan)));
        
        $begin = new DateTime($awalbulan);
        $end = new DateTime($akhirbulan);
        
        $interval = DateInterval::createFromDateString('1 day');
        $data['daterange']  = new DatePeriod($begin, $interval, $end);
        
        $data['interval']   = date_diff(date_create($awalbulan),date_create($akhirbulan));
        
        $data['month']      = $this->db->query("SELECT * FROM month")->result_array();
        $data['kegiatan']   = $this->db->query("SELECT * FROM kegiatan")->result_array();
        $data['dt_halaqoh'] = $this->db->query("SELECT * FROM halaqoh")->result_array();

        $data['title'] = 'Laporan Presensi Halaqoh Santri';
        $data['main'] = 'presensi_halaqoh/pegawai/presensi_halaqoh_report';
        $this->load->view('pegawai/layout', $data);
    }
	
	function report_print(){
        
        $q = $this->input->get(NULL, TRUE);

		$data['q'] = $q;

		$param = array();
		$params = array();
		
		$namaBulan  = NULL;
		$bulan      = date('m');
		$period     = NULL;
		$tahun      = date('Y');
		

		if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
			$param['period_id']  = $q['p'];
		}

		if (isset($q['m']) && !empty($q['m']) && $q['m'] != '') {
			$param['month_id'] = $q['m'];
		}

		if (isset($q['d']) && !empty($q['d']) && $q['d'] != '') {
			$param['date'] = $q['d'];
		}

		if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
			$params['majors_id'] = $q['k'];
		}

		if (isset($q['c']) && !empty($q['c']) && $q['c'] != '') {
			$params['class_id']  = $q['c'];
			$param['class_id']  = $q['c'];
			$data['namaKelas'] = $this->db->query("SELECT class_name FROM class WHERE class_id = ".$q['c']."")->row_array();
		}
		
		if (isset($q['m']) && !empty($q['m']) && $q['m'] != '') {
		    
		    
            $namaBulan = $this->db->query("SELECT month_name FROM month WHERE month_id = ".$q['m']."")->row_array();
            
		    if($q['m'] == 1) {
                $bulan = '07';
		    } else if($q['m'] == 2) {
                $bulan = '08';
		    } else if($q['m'] == 3) {
                $bulan = '09';
		    } else if($q['m'] == 4) {
                $bulan = '10';
		    } else if($q['m'] == 5) {
                $bulan = '11';
		    } else if($q['m'] == 6) {
                $bulan = '12';
		    } else if($q['m'] == 7) {
                $bulan = '01';
		    } else if($q['m'] == 8) {
                $bulan = '02';
		    } else if($q['m'] == 9) {
                $bulan = '03';
		    } else if($q['m'] == 10) {
                $bulan = '04';
		    } else if($q['m'] == 11) {
                $bulan = '05';
		    } else if($q['m'] == 12) {
                $bulan = '06';
		    }
		    
            $period = $this->db->query("SELECT period_id, period_start, period_end FROM period WHERE period_id = ".$q['p']."")->row_array();
            
            if($q['m'] < 7){
               $tahun               = $period['period_start'] ;
               $data['namaTahun']   = $period['period_start'] ;
            } else {
               $tahun               = $period['period_end'] ; 
               $data['namaTahun']   = $period['period_end'] ; 
            }
            
            $data['namaBulan']      = $namaBulan['month_name'];
		}
		
		$data['check']      = $this->Presensi_halaqoh_model->check($param);
		
		$data['class']      = $this->Student_model->get_class($params);
		$data['majors']     = $this->Student_model->get_majors($params);
		$data['student']    = $this->Student_model->get($params);
        
		$config['suffix'] = '?' . http_build_query($_GET, '', "&");
    
        $data['period'] = $this->db->query("SELECT period_id, period_start, period_end FROM period")->result_array();
        
        $awalbulan = $tahun . '-' . $bulan . '-01';
        $akhirbulan = date('Y-m-d', strtotime("+1 month", strtotime($awalbulan)));
        
        $begin = new DateTime($awalbulan);
        $end = new DateTime($akhirbulan);
        
        $interval = DateInterval::createFromDateString('1 day');
        $data['daterange']  = new DatePeriod($begin, $interval, $end);
        
        $data['interval']   = date_diff(date_create($awalbulan),date_create($akhirbulan));
        
        $data['month'] = $this->db->query("SELECT * FROM month")->result_array();
        
        $data['title'] = 'Laporan Presensi Halaqoh Santri';
		
		$data['setting_nip_bendahara']  = $this->Setting_model->get(array('id' => 15));
        $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
        $data['setting_city']           = $this->Setting_model->get(array('id' => SCHOOL_CITY));
        $data['setting_district']       = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT)); 
        $data['setting_school']         = $this->Setting_model->get(array('id' => SCHOOL_NAME)); 
        $data['setting_address']        = $this->Setting_model->get(array('id' => SCHOOL_ADRESS)); 
        $data['setting_phone']          = $this->Setting_model->get(array('id' => SCHOOL_PHONE));  
	    
	    $this->load->view('presensi_halaqoh/presensi_halaqoh_report_print', $data);
        
	}
    
    public function add_glob(){
        if ($_POST == TRUE) {
            
            $periodID     = $_POST['period_id'];
            $tahun        = $_POST['tahun'];
            $halaqohID    = $_POST['halaqoh_id'];
            $presensiDate = $_POST['presensi_date'];
            $kegiatan_id  = $_POST['kegiatan_id'];
            $studentID    = $_POST['student_id'];
            $materi    	  = $_POST['materi_halaqoh'];
            $employee_id  = $_POST['employee_id'];

            $dateM = date('m');
            
            if($dateM == '01'){
                $month_id = '7';
            } else if($dateM == '02'){
                $month_id = '8';
            } else if($dateM == '03'){
                $month_id = '9';
            } else if($dateM == '04'){
                $month_id = '10';
            } else if($dateM == '05'){
                $month_id = '11';
            } else if($dateM == '06'){
                $month_id = '12';
            } else if($dateM == '07'){
                $month_id = '1';
            } else if($dateM == '08'){
                $month_id = '2';
            } else if($dateM == '09'){
                $month_id = '3';
            } else if($dateM == '10'){
                $month_id = '4';
            } else if($dateM == '11'){
                $month_id = '5';
            } else if($dateM == '12'){
                $month_id = '6';
            }

			$param['teaching_halaqoh_employee_id'] = $employee_id;
			$param['teaching_halaqoh_halaqoh_id'] =  $halaqohID;
			$param['teaching_halaqoh_month_id'] =  $month_id;
			$param['teaching_halaqoh_tahun'] =  $tahun;
			$param['teaching_halaqoh_kegiatan_id'] =  $kegiatan_id;
			$param['teaching_halaqoh_date'] =  $presensiDate;
			$param['teaching_halaqoh_materi'] =  $materi;

			$this->Presensi_halaqoh_model->add_teaching_halaqoh($param);
            
            //Mengambil ID Teaching utk dimasukkan ke presensi pelajaran
			$teaching	  = $this->db->query("SELECT teaching_halaqoh_id FROM teaching_halaqoh WHERE teaching_halaqoh_kegiatan_id= '$kegiatan_id' AND teaching_halaqoh_halaqoh_id='$halaqohID' AND DATE(teaching_date_create)='$presensiDate' ")->row_array();
            $teachingID	  = $teaching['teaching_halaqoh_id'];
            $cpt = count($studentID);
            
            for ($i = 0; $i < $cpt; $i++) {
              
                $student_id         = $studentID[$i];
                $status_kehadiran   = 'presensi_halaqoh_status' . $student_id;
                 
                $status             = $_POST[$status_kehadiran];
                
                $params['presensi_halaqoh_period_id']    = $periodID;
                $params['presensi_halaqoh_tahun']        = $tahun;
                $params['presensi_halaqoh_halaqoh_id']   = $halaqohID;
                $params['presensi_halaqoh_month_id']     = $month_id;
                $params['presensi_halaqoh_teaching_id']  = $teachingID;
                $params['presensi_halaqoh_kegiatan_id']  = $kegiatan_id;
                $params['presensi_halaqoh_date']         = $presensiDate;
                $params['presensi_halaqoh_student_id']   = $student_id;
                $params['presensi_halaqoh_status']       = $status[0];
                $this->Presensi_halaqoh_model->add($params);
            }

           
        }
        
        $this->session->set_flashdata('success',' Input Presensi Berhasil');
        redirect('pegawai/presensi_halaqoh?h=' . $halaqohID . '&k=' . $kegiatan_id . '&d=' . $presensiDate);
    }
    
    function halaqoh_searching(){
	    $id_majors = $this->input->post('id_majors');
        $dataHalaqoh  = $this->db->query("SELECT * FROM halaqoh WHERE halaqoh_majors_id = '$id_majors' ORDER BY halaqoh_name ASC")->result_array();
    
        echo '<div class="col-md-2">  
				<div class="form-group">
				    <label>Halaqoh</label>
		            <select name="c" id="class_id" class="form-control" required="">
                    <option value="">-- Pilih Halaqoh --</option>';
                      foreach($dataHalaqoh as $row){ 
        
                        echo '<option value="'.$row['halaqoh_id'].'">';
                            
                        echo $row['halaqoh_name'];
                            
                        echo '</option>';
                    
                        }
            echo '</select>
				</div>
			</div>';
	}
	
}