<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Donasi_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }

        $this->load->model(array('student/Student_model', 'donasi/Donasi_model', 'setting/Setting_model'));
        $this->load->library('upload');
        $this->load->helper(array('send_helper'));
    }

    // program view in list
    public function index($offset = NULL)
    {
        $this->load->library('pagination');

        $f = $this->input->get(NULL, TRUE);
        $s = $this->input->get(NULL, TRUE);

        $data['f'] = $f;
        $data['s'] = $s;

        $params = array();

        $data['f']            = $f;
        $donasi               = null;
        $majors_id            = null;
        $params               = array();

        // Siswa
        if (isset($f['r']) && !empty($f['r']) && $f['r'] != '') {
            $donasi    = $f['r'];




            $majors_id = $data['donasi']['majors_id'];

            $data['dataKas']    = $this->db->query("SELECT account_code, account_id, account_description FROM account
                                            WHERE account_category = '2' AND account_code LIKE '1%'  AND account_majors_id = '$majors_id'
                                            AND account_description LIKE '%KAS%' 
                                            AND account_note IN 
                                            (SELECT account_id FROM account WHERE account_code LIKE '1%' AND account_majors_id = '$majors_id'
                                            ORDER BY account_code ASC)")->result_array();

            $kasActive          = $this->db->query("SELECT account_id FROM account
                                            WHERE account_category = '2' AND account_majors_id = '$majors_id' AND account_code LIKE '1%'
                                            AND account_description LIKE '%Tunai%'")->row_array();

            $data['dataKasActive'] = $kasActive['account_id'];
        }

        $data['program'] = $this->Donasi_model->get($params);


        $data['donasi'] = $this->Donasi_model->get(array('id' => $donasi));

        $data['list_donasi'] = $this->db->get_where('donasi', array(
            'program_program_id' => $donasi,
            'donasi_status' => 1
        ))->result_array();


        $data['setting_nip_bendahara']      = $this->Setting_model->get(array('id' => 15));
        $data['setting_nama_bendahara']     = $this->Setting_model->get(array('id' => 16));
        $data['setting_logo']               = $this->Setting_model->get(array('id' => 6));
        $data['setting_city']               = $this->Setting_model->get(array('id' => SCHOOL_CITY));
        $data['setting_district']           = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
        $data['setting_school']             = $this->Setting_model->get(array('id' => SCHOOL_NAME));
        $data['setting_address']            = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
        $data['setting_phone']              = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

        $data['title'] = 'Donasi';
        $data['main'] = 'donasi/donasi_trx';
        $this->load->view('manage/layout', $data);
    }

    public function create_donasi()
    {

        $donasi_name        = $this->input->post('donasi_name');
        $donasi_email       = $this->input->post('donasi_email');
        $donasi_hp          = $this->input->post('donasi_hp');
        $donasi_nominal     = $this->input->post('donasi_nominal');
        $donasi_ref_id      = idate("U");
        $donasi_account_id  = $this->input->post('donasi_account_id');
        $program_program_id = $this->input->post('program_program_id');

        if ($donasi_nominal < 10000) {
            $this->session->set_flashdata('failed', 'Nominal Donasi Minimal Rp 10.000');
            redirect('manage/donasi');
        }

        if ($this->input->post('as_anonim') !== null) {
            $donasi_name = $this->input->post('as_anonim');
        }

        $hp = $this->set_phone($donasi_hp);

        $donasi = array(
            'program_program_id' => $program_program_id,
            'donasi_nominal'    => $donasi_nominal,
            'donasi_status' => 1,
            'donasi_ref_id' => $donasi_ref_id,
            'donasi_name' => $donasi_name,
            'donasi_email' => $donasi_email,
            'donasi_hp' => $hp,
            'donasi_datetime' => date('Y-m-d H:i:s'),
        );

        $insert_donasi = $this->db->insert('donasi', $donasi);

        if ($insert_donasi) {
            $earnProgram = $this->db->query("UPDATE program SET program_earn = program_earn + $donasi_nominal WHERE program_id = '$program_program_id'");

            if ($earnProgram) {

                $program = $this->db->get_where('program', array('program_id' => $program_program_id))->row_array();

                $data_jurnal = array(
                    'note'              => 'Penerimaan Donasi ' . $donasi_name,
                    'nominal'           =>  $donasi_nominal,
                    'account_id'        =>  $program['account_account_id'],
                    'donasi_account_id' =>  $donasi_account_id
                );

                if ($this->insert_jurnal($data_jurnal)) {
                    $no_wa      = $hp;

                    $pesan      = 'Terima kasih kepada saudara/i ' . $donasi_name . ' telah berdonasi sebesar Rp ' . number_format($donasi_nominal, 0, ',', '.') . ' untuk ' . $program['program_name'] . '. Semoga berkah dan menjadi amal jariyah saudara/i sekalian. Aamiin';

                    $this->send_wa($no_wa, $pesan);

                    redirect('manage/donasi?r=' . $program_program_id);
                }
            }
        }
    }

    private function set_phone(String $phone): string
    {

        $phone = str_replace("-", "", $phone);
        $phone = str_replace(" ", "", $phone);
        $phone = str_replace("(", "", $phone);
        $phone = str_replace(")", "", $phone);
        $phone = str_replace(".", "", $phone);

        if (!preg_match('/[^+0-9]/', trim($phone))) {
            if (substr(trim($phone), 0, 1) == '+') {
                $hp = trim($phone);
            } elseif (substr(trim($phone), 0, 1) == '0') {
                $hp = '+62' . substr(trim($phone), 1);
            } elseif (substr(trim($phone), 0, 2) == '62') {
                $hp = '+' . trim($phone);
            } elseif (substr(trim($phone), 0, 1) == '8') {
                $hp = '+62' . trim($phone);
            } else {
                $hp = '+' . trim($phone);
            }
        }

        return $hp;
    }

    private function send_wa($no_wa, $pesan)
    {
        $api_key    = 'yQQcVtMVmE2R532CqXNolvD8rt5hjyEI';
        $sender     = '6281233315959';

        $no_send    = str_replace('+', '', $no_wa);
        $data = [
            'api_key' => $api_key,
            'sender' => $sender,
            'number' => $no_send,
            'message' => $pesan
        ];

        $url    = 'https://indoweb.notifwa.com/send-message';

        $data_string = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );
        return $res = curl_exec($ch);
        curl_close($ch);
    }

    private function insert_jurnal(array $data_jurnal = []): bool
    {

        $note               = $data_jurnal['note'];
        $nominal            = $data_jurnal['nominal'];
        $account_id         = $data_jurnal['account_id'];
        $donasi_account_id  = $data_jurnal['donasi_account_id'];

        $account = $this->get_account_detail($account_id);

        $majors_id = $account['majors_id'];
        $account_code = $account['account_code'];

        $aktiva_code = $this->get_aktiva_code($majors_id, $donasi_account_id);

        $jurnal_umum = array(
            'sekolah_id'         => $majors_id,
            'keterangan'         => $note,
            'tanggal'            => date('Y-m-d'),
            'pencatatan'         => 'auto',
            'waktu_update'       => date('Y-m-d H:i:s'),
            'keterangan_lainnya' => '-'
        );

        $this->db->insert('jurnal_umum', $jurnal_umum);

        $jurum = $this->db->query("SELECT MAX(id) AS last_id FROM jurnal_umum")->row_array();
        $lastJurum = $jurum['last_id'];

        $dataKas = array(
            'id_jurnal' => $lastJurum,
            'account_code' => $aktiva_code,
            'debet' => $nominal,
            'kredit' => 0,
        );

        $this->db->insert('jurnal_umum_detail', $dataKas);

        $dataKas = array(
            'id_jurnal' => $lastJurum,
            'account_code' => $account_code,
            'debet' => 0,
            'kredit' => $nominal,
        );

        $this->db->insert('jurnal_umum_detail', $dataKas);

        return true;
    }

    private function get_aktiva_code(int $majors_id, int $donasi_account_id): string
    {
        $aktiva = $this->db->query("SELECT account_code FROM account WHERE
                                    account_id = '$donasi_account_id'
                                    AND account_majors_id = '$majors_id'")->row_array();

        return $aktiva['account_code'];
    }

    private function get_account_detail(int $id): array
    {
        $akun = $this->db->query("SELECT account_code,account_majors_id FROM account WHERE account_id = '$id'")->row_array();

        $data = array(
            'account_code' => $akun['account_code'],
            'majors_id' => $akun['account_majors_id']
        );

        return $data;
    }
}
