<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Penginapan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);

        $this->load->model(array('setting/Setting_model'));
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

        $data['penginapan'] = $this->db->query("WITH ranked_data AS (
            SELECT
              a.homestay_id,
              a.homestay_name,
              a.homestay_desc,
              a.homestay_price,
              b.gallery_img,
              ROW_NUMBER() OVER (PARTITION BY a.homestay_id ORDER BY b.gallery_create_at DESC) AS row_num
            FROM
              homestay a
              JOIN gallery b ON a.homestay_id = b.gallery_homestay_id
              JOIN users c ON c.user_id = a.homestay_user_id
          )
          SELECT
            homestay_id,
            homestay_name,
            homestay_desc,
            homestay_price,
            gallery_img
          FROM
            ranked_data
          WHERE
            row_num = 1
          ORDER BY homestay_price ASC")->result_array();

        $data['title'] = 'Data Penginapan';
        $data['main'] = 'penginapan/penginapan_list';

        $data['setting_nip_bendahara']      = $this->Setting_model->get(array('id' => 15));
        $data['setting_nama_bendahara']     = $this->Setting_model->get(array('id' => 16));
        $data['setting_logo']               = $this->Setting_model->get(array('id' => 6));
        $data['setting_city']               = $this->Setting_model->get(array('id' => SCHOOL_CITY));
        $data['setting_district']           = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
        $data['setting_school']             = $this->Setting_model->get(array('id' => SCHOOL_NAME));
        $data['setting_address']            = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
        $data['setting_phone']              = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

        $this->load->view('frontend/penginapan', $data);
    }

    public function room()
    {
        if (empty($this->input->get('r'))) {
            redirect(base_url());
        }

        $data['id'] = $this->input->get('r');

        $homestay_id = base64_decode($this->input->get('r'));

        $data['homestay'] = $this->db->get_where('homestay', array('homestay_id' => $homestay_id))->row_array();

        $dates = $this->getDates($homestay_id);
        $tanggals = array();

        foreach ($dates['dates'] as $date) :
            $start_date = $date['reservasi_checkin'];
            $end_date   = $date['reservasi_checkout'];

            while (strtotime($start_date) <= strtotime($end_date)) {
                $tanggals[] = $start_date;
                $start_date = date("Y-m-d", strtotime("+1 days", strtotime($start_date)));
            }

        endforeach;


        $data['dates'] = $tanggals;

        $data['title'] = 'Booking Penginapan';
        $data['main'] = 'penginapan/penginapan_booking';

        $data['setting_nip_bendahara']      = $this->Setting_model->get(array('id' => 15));
        $data['setting_nama_bendahara']     = $this->Setting_model->get(array('id' => 16));
        $data['setting_logo']               = $this->Setting_model->get(array('id' => 6));
        $data['setting_city']               = $this->Setting_model->get(array('id' => SCHOOL_CITY));
        $data['setting_district']           = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
        $data['setting_school']             = $this->Setting_model->get(array('id' => SCHOOL_NAME));
        $data['setting_address']            = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
        $data['setting_phone']              = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

        $this->load->view('frontend/penginapan', $data);
    }

    public function get_data_json()
    {
        if (empty($this->input->get('r'))) {
            redirect(base_url());
        }

        $id = $this->input->get('r');

        $homestay_id = base64_decode($id);
        $datamap = $this->db->select('homestay_latitude as latitude, homestay_longitude as longitude')
            ->from('homestay')
            ->where('homestay_id', $homestay_id)
            ->get()->result();

        $datagalery = $this->db->select('gallery.gallery_img as url')
            ->from('gallery')
            ->where('gallery_homestay_id', $homestay_id)
            ->get()->result();

        $json = array(
            'datamap'  => $datamap,
            'datagalery'  => $datagalery,
        );

        echo json_encode($json);
    }

    public function booking()
    {

        $reservasi_name         = $this->input->post('reservasi_name');
        $reservasi_hp           = $this->input->post('reservasi_hp');
        $reservasi_checkin      = $this->input->post('reservasi_checkin');
        $reservasi_checkout     = $this->input->post('reservasi_checkout');
        $reservasi_total        = $this->input->post('reservasi_total');
        $homestay_name          = $this->input->post('homestay_name');
        $homestay_price         = $this->input->post('homestay_price');
        $reservasi_ref_id       = 'INV' . idate("U");
        $reservasi_homestay_id  = $this->input->post('homestay_id');

        $hp = $this->set_phone($reservasi_hp);

        $va           = '0000003851378225';
        $apiKey       = 'SANDBOXE38739E0-C35F-4E62-AA36-DF18A2DD9356-20220509140520';

        $url          = 'https://sandbox.ipaymu.com/api/v2/payment';

        $method       = 'POST';

        $body['product']        = array($homestay_name);
        $body['qty']            = array('1');
        $body['price']          = array($reservasi_total);
        $body['notifyUrl']      = base_url() . 'response/booking';
        $body['referenceId']    = strval($reservasi_ref_id);
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

                $insert_reservasi = array(
                    'reservasi_homestay_id' => $reservasi_homestay_id,
                    'reservasi_harga'    => $homestay_price,
                    'reservasi_checkin'    => $reservasi_checkin,
                    'reservasi_checkout'    => $reservasi_checkout,
                    'reservasi_total'    => $reservasi_total,
                    'reservasi_ref_id' => $reservasi_ref_id,
                    'reservasi_name' => $reservasi_name,
                    'reservasi_hp' => $hp,
                );

                if ($this->db->insert('reservasi', $insert_reservasi)) {

                    $pesan = "Terima kasih kepada saudara/i " . $reservasi_name . " telah memilih untuk penginapan " . $homestay_name . " silahkan melakukan pembayaran sebesar Rp " . number_format($reservasi_total, 0, '.', ',') . " Melalui link : " . $url;

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

    private function getDates($id)
    {
        $this->db->distinct();
        $this->db->select('reservasi_checkin, reservasi_checkout');
        $this->db->where('reservasi_homestay_id', $id);
        $dates = $this->db->get('reservasi')->result_array();

        return [
            'dates' => $dates,
        ];
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
