<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Donasi extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);

        $this->load->model(array('student/Student_model', 'donasi/Donasi_model', 'setting/Setting_model'));
        $this->load->library('upload');
    }

    // program view in list
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

        $data['donasi'] = $this->Donasi_model->get($params);

        $data['majors'] = $this->Student_model->get_majors();

        $data['title'] = 'Donasi';
        $data['main'] = 'donasi/donasi_list';

        $data['setting_nip_bendahara']      = $this->Setting_model->get(array('id' => 15));
        $data['setting_nama_bendahara']     = $this->Setting_model->get(array('id' => 16));
        $data['setting_logo']               = $this->Setting_model->get(array('id' => 6));
        $data['setting_city']               = $this->Setting_model->get(array('id' => SCHOOL_CITY));
        $data['setting_district']           = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
        $data['setting_school']             = $this->Setting_model->get(array('id' => SCHOOL_NAME));
        $data['setting_address']            = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
        $data['setting_phone']              = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

        $this->load->view('frontend/donasi', $data);
    }

    public function program()
    {

        $this->load->library('pagination');
        // Apply Filter
        // Get $_GET variable
        $f = $this->input->get(NULL, TRUE);
        $s = $this->input->get(NULL, TRUE);

        $data['f'] = $f;
        $data['s'] = $s;

        $params = array();

        $data['donasi'] = $this->Donasi_model->get($params);

        $data['majors'] = $this->Student_model->get_majors();

        $data['title'] = 'Donasi';
        $data['main'] = 'donasi/donasi_list';

        $data['setting_nip_bendahara']      = $this->Setting_model->get(array('id' => 15));
        $data['setting_nama_bendahara']     = $this->Setting_model->get(array('id' => 16));
        $data['setting_logo']               = $this->Setting_model->get(array('id' => 6));
        $data['setting_city']               = $this->Setting_model->get(array('id' => SCHOOL_CITY));
        $data['setting_district']           = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
        $data['setting_school']             = $this->Setting_model->get(array('id' => SCHOOL_NAME));
        $data['setting_address']            = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
        $data['setting_phone']              = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

        $this->load->view('frontend/donasi', $data);
    }

    public function view()
    {
    }

    public function create_donasi()
    {

        $donasi_name        = $this->input->post('donasi_name');
        $donasi_email       = $this->input->post('donasi_email');
        $donasi_hp          = $this->input->post('donasi_hp');
        $program_name       = $this->input->post('program_name');
        $donasi_nominal     = $this->input->post('donasi_nominal');
        $donasi_ref_id      = idate("U");
        $program_program_id = $this->input->post('program_program_id');

        if ($donasi_nominal < 10000) {
            $this->session->set_flashdata('failed', 'Nominal Donasi Minimal Rp 10.000');
            redirect('donasi');
        }

        if ($this->input->post('as_anonim') !== null) {
            $donasi_name = $this->input->post('as_anonim');
        }

        $hp = $this->set_phone($donasi_hp);

        $va           = '0000003851378225';
        $apiKey       = 'SANDBOXE38739E0-C35F-4E62-AA36-DF18A2DD9356-20220509140520';

        $url          = 'https://sandbox.ipaymu.com/api/v2/payment';

        $method       = 'POST';

        $body['product']        = array($program_name);
        $body['qty']            = array('1');
        $body['price']          = array($donasi_nominal);
        $body['notifyUrl']      = base_url() . 'response/callback';
        $body['referenceId']    = strval($donasi_ref_id);
        $body['feeDirection']   = 'BUYER';

        $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody  = strtolower(hash('sha256', $jsonBody));
        $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $apiKey;
        $signature    = hash_hmac('sha256', $stringToSign, $apiKey);
        $timestamp    = Date('YmdHis');


        $ch = curl_init($url);

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'va: ' . $va,
            'signature: ' . $signature,
            'timestamp: ' . $timestamp
        );

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_POST, count($body));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $err = curl_error($ch);
        $ret = curl_exec($ch);
        curl_close($ch);

        if ($err) {
            var_dump($err);
            exit;
        } else {
            $ret = json_decode($ret);
            if ($ret->Status == 200) {
                $url        =  $ret->Data->Url;

                $insert_donasi = array(
                    'program_program_id' => $program_program_id,
                    'donasi_nominal'    => $donasi_nominal,
                    'donasi_status' => 0,
                    'donasi_ref_id' => $donasi_ref_id,
                    'donasi_name' => $donasi_name,
                    'donasi_email' => $donasi_email,
                    'donasi_hp' => $hp,
                    'donasi_datetime' => date('Y-m-d H:i:s'),
                );

                if ($this->db->insert('donasi', $insert_donasi)) {

                    $pesan = "Terima kasih kepada saudara/i " . $donasi_name . " telah memilih untuk berdonasi " . $program_name . " sebesar Rp " . number_format($donasi_nominal, 0, '.', ',') . " Silahkan berdonasi melalui link : " . $url;

                    $this->send_wa($hp, $pesan);

                    header('Location:' . $url);
                } else {
                    echo 'Failed to Insert Data';
                    exit;
                }
            } else {
                var_dump($ret);
                exit;
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
}
