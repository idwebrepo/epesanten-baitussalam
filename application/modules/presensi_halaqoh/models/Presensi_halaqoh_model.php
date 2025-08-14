<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Presensi_halaqoh_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    function check($param = array()) {
        
        if (isset($param['kegiatan_id'])) {
            $this->db->where('presensi_halaqoh_kegiatan_id', $param['kegiatan_id']);
        }
            
        // if (isset($param['month_id'])) {
        //     $this->db->where('presensi_halaqoh_month_id', $param['month_id']);
        // }
        
        if (isset($param['date'])) {
            $this->db->where('presensi_halaqoh_date', $param['date']);
        }
        
        if (isset($param['halaqoh_id'])) {
            $this->db->where('presensi_halaqoh_halaqoh_id', $param['halaqoh_id']);
        }
        
        $this->db->from('presensi_halaqoh');
        
        $res = $this->db->count_all_results();
        
        return $res;
    }
    
    function add($data = array()) {

        if (isset($data['presensi_halaqoh_id'])) {
            $this->db->set('presensi_halaqoh_id', $data['presensi_halaqoh_id']);
        }
        
        if (isset($data['presensi_halaqoh_halaqoh_id'])) {
            $this->db->set('presensi_halaqoh_halaqoh_id', $data['presensi_halaqoh_halaqoh_id']);
        }
        
        if (isset($data['presensi_halaqoh_kegiatan_id'])) {
            $this->db->set('presensi_halaqoh_kegiatan_id', $data['presensi_halaqoh_kegiatan_id']);
        }
            
        if (isset($data['presensi_halaqoh_teaching_id'])) {
            $this->db->set('presensi_halaqoh_teaching_id', $data['presensi_halaqoh_teaching_id']);
        }
            
        if (isset($data['presensi_halaqoh_tahun'])) {
            $this->db->set('presensi_halaqoh_tahun', $data['presensi_halaqoh_tahun']);
        }
            
        if (isset($data['presensi_halaqoh_month_id'])) {
            $this->db->set('presensi_halaqoh_month_id', $data['presensi_halaqoh_month_id']);
        }
        
        if (isset($data['presensi_halaqoh_date'])) {
            $this->db->set('presensi_halaqoh_date', $data['presensi_halaqoh_date']);
        }
        
        if (isset($data['presensi_halaqoh_student_id'])) {
            $this->db->set('presensi_halaqoh_student_id', $data['presensi_halaqoh_student_id']);
        }
        
        if (isset($data['presensi_halaqoh_period_id'])) {
            $this->db->set('presensi_halaqoh_period_id', $data['presensi_halaqoh_period_id']);
        }
        
        if (isset($data['presensi_halaqoh_status'])) {
            $this->db->set('presensi_halaqoh_status', $data['presensi_halaqoh_status']);
        }

        if (isset($data['presensi_halaqoh_id'])) {
            $this->db->where('presensi_halaqoh_id', $data['presensi_halaqoh_id']);
            $this->db->update('presensi_halaqoh');
            $id = $data['presensi_halaqoh_id'];
        } else {
            $this->db->insert('presensi_halaqoh');
            $id = $this->db->insert_id(); 
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function get_teaching_halaqoh($params = array())
    {
        if(isset($params['id']))
        {
            $this->db->where('teaching_id', $params['id']);
        }

        if(isset($params['lesson_id']))
        {
            $this->db->where('teaching_lesson_id', $params['lesson_id']);
        }

        if(isset($params['employee_id']))
        {
            $this->db->where('teaching_employee_id', $params['employee_id']);
        }

        if(isset($params['class_id']))
        {
            $this->db->where('teaching_class_id', $params['class_id']);
        }

        if(isset($params['semester_id']))
        {
            $this->db->where('teaching_semester_id', $params['semester_id']);
        }

        if(isset($params['month_id']))
        {
            $this->db->where('teaching_month_id', $params['month_id']);
        }

        if(isset($params['limit']))
        {
            if(!isset($params['offset']))
            {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }
        if(isset($params['order_by']))
        {
            $this->db->order_by($params['order_by'], 'desc');
        }
        else
        {
            $this->db->order_by('teaching.teaching_id', 'asc');
        }
        
        $this->db->select('teaching_materi, teaching_date_create');
        $this->db->select('class_name');
        $this->db->select('lesson_id, lesson_name');
        $this->db->select('majors_name, majors_short_name, majors_id');
        $this->db->select('employee_name');
        
        
        $this->db->join('class', 'class.class_id = teaching.teaching_class_id');
        $this->db->join('lesson', 'lesson.lesson_id = teaching.teaching_lesson_id');
        $this->db->join('majors', 'majors.majors_id = class.majors_majors_id');
        $this->db->join('employee', 'employee.employee_id = teaching.teaching_employee_id');
        
        $res = $this->db->get('teaching');

        if(isset($params['id']))
        {
            return $res->row_array();
        }
        else
        {
            return $res->result_array();
        }
    }

    function add_teaching_halaqoh($data = array()) {

        if (isset($data['teaching_halaqoh_id'])) {
            $this->db->set('teaching_halaqoh_id', $data['teaching_halaqoh_id']);
        }
        
        if (isset($data['teaching_halaqoh_employee_id'])) {
            $this->db->set('teaching_halaqoh_employee_id', $data['teaching_halaqoh_employee_id']);
        }
            
        if (isset($data['teaching_halaqoh_month_id'])) {
            $this->db->set('teaching_halaqoh_month_id', $data['teaching_halaqoh_month_id']);
        }
            
        if (isset($data['teaching_halaqoh_tahun'])) {
            $this->db->set('teaching_halaqoh_tahun', $data['teaching_halaqoh_tahun']);
        }
            
        if (isset($data['teaching_halaqoh_halaqoh_id'])) {
            $this->db->set('teaching_halaqoh_halaqoh_id', $data['teaching_halaqoh_halaqoh_id']);
        }
        
        if (isset($data['teaching_halaqoh_kegiatan_id'])) {
            $this->db->set('teaching_halaqoh_kegiatan_id', $data['teaching_halaqoh_kegiatan_id']);
        }
            
        if (isset($data['teaching_halaqoh_date'])) {
            $this->db->set('teaching_halaqoh_date', $data['teaching_halaqoh_date']);
        }
        
        if (isset($data['teaching_halaqoh_materi'])) {
            $this->db->set('teaching_halaqoh_materi', $data['teaching_halaqoh_materi']);
        }

        if (isset($data['teaching_halaqoh_id'])) {
            $this->db->where('teaching_halaqoh_id', $data['teaching_halaqoh_id']);
            $this->db->update('teaching_halaqoh');
            $id = $data['teaching_halaqoh_id'];
        } else {
            $this->db->insert('teaching_halaqoh');
            $id = $this->db->insert_id(); 
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }
    
    

}
