<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sync extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('banking/Banking_model', 'period/Period_model', 'setting/Setting_model'));

        $this->load->helper(array('wa'));
    }

    public function index()
    {
        echo 'Sorry, Cant Be Accessed';
    }

    public function get_data()
    {

        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents("php://input"));
            $key_toko = $this->Setting_model->get(array('id' => 700));

            if ($data->key === $key_toko['setting_value']) {

                $query = $this->db->query(
                    "SELECT student.student_nis, student.student_full_name, 
                            IFNULL(SUM(banking.banking_debit - banking.banking_kredit), 0) as saldo, 
                            IFNULL(banking_limit.limit, 0) as limit_trx,
                            IFNULL(cards.rfid,'') as nomor_rfid, IFNULL(cards.pin,'') as pin_rfid, 
                            cards.status as status_rfid 
                    FROM student 
                    LEFT JOIN banking ON banking.banking_student_id = student.student_id 
                    LEFT JOIN cards ON cards.nis = student.student_nis 
                    LEFT JOIN banking_limit ON banking_limit.nis = student.student_nis
                    WHERE student_status = 1 
                    GROUP BY student.student_nis 
                    ORDER BY `student`.`student_id` ASC"
                )->result();

                $data = [];

                foreach ($query as $row) {
                    $data[] = [
                        'nis'       => $row->student_nis,
                        'nama'      => $row->student_full_name,
                        'saldo'     => $row->saldo,
                        'limit'     => $row->limit_trx,
                        'rfid'      => $row->nomor_rfid,
                        'pin'       => $row->pin_rfid,
                        'status'    => $row->status_rfid,
                    ];
                }

                $response = array(
                    'status' => 1,
                    'message' => 'Success',
                    'data' => $data
                );

                http_response_code(200);
            } else {
                $response = ["message" => "Invalid Secret Key"];
                http_response_code(400);
            }
        } else {
            $response = ["message" => "Invalid Request Method."];
            http_response_code(405);
        }

        echo json_encode($response);
    }

    public function get_data_first()
    {

        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents("php://input"));
            $key_toko = $this->Setting_model->get(array('id' => 700));



            if ($data->key === $key_toko['setting_value']) {
                $nis = $data->nis;

                $query = $this->db->query("SELECT student.student_nis, student.student_full_name, IFNULL(SUM(banking.banking_debit - banking.banking_kredit), 0) as saldo, IFNULL(cards.rfid,'') as nomor_rfid, IFNULL(cards.pin,'') as pin_rfid, cards.status as status_rfid FROM student LEFT JOIN banking ON banking.banking_student_id = student.student_id LEFT JOIN cards ON cards.nis = student.student_nis WHERE student_nis = '$nis' GROUP BY student.student_nis ORDER BY `student`.`student_id` ASC")->row();

                $data = [
                    'nis'       => $query->student_nis,
                    'nama'      => $query->student_full_name,
                    'saldo'     => $query->saldo,
                    'rfid'      => $query->nomor_rfid,
                    'pin'       => $query->pin_rfid,
                    'status'    => $query->status_rfid,
                ];

                $response = array(
                    'status' => 1,
                    'message' => 'Success',
                    'data' => $data
                );

                http_response_code(200);
            } else {
                $response = ["message" => "Invalid Secret Key"];
                http_response_code(400);
            }
        } else {
            $response = ["message" => "Invalid Request Method."];
            http_response_code(405);
        }

        echo json_encode($response);
    }

    public function use_saving()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents("php://input"));
            $key_toko = $this->Setting_model->get(array('id' => 700));

            if (isset($data->nis) && isset($data->nominal) && $data->key === $key_toko['setting_value']) {

                $period = $this->Period_model->get(array('status' => 1));
                $nis    = $data->nis;

                $student = $this->db->query("SELECT student_id, student_full_name, majors_short_name, majors_school_name, class_name, student_parent_phone FROM student JOIN class ON class.class_id = student.class_class_id JOIN majors ON majors.majors_id = student.majors_majors_id WHERE student.student_nis = '$nis'")->row();

                if (isset($student->student_id)) {

                    $params['banking_date']         = date('Y-m-d');
                    $params['banking_debit']        = '0';
                    $params['banking_kredit']       = $data->nominal;
                    $params['banking_note']         = 'Belanja di toko/kantin';
                    $params['banking_code']         = '2';
                    $params['banking_student_id']   = $student->student_id;
                    $params['banking_period_id']    = $period->period_id;
                    $params['user_user_id']         = NULL;

                    $this->Banking_model->add($params);

                    if (isset($student->student_parent_phone) and $student->student_parent_phone != '+62') {

                        $pesan = 'Penarikan tabungan untuk belanja Ananda ' . $student->student_full_name . ' kelas ' . $student->class_name . ' unit ' . $student->majors_school_name . ' berhasil dilakukan' . "\n\n" .
                            'Berikut Bukti Transaksi Tabungan nya.' . "\n\n" . ' Download Bukti : ' . base_url() . 'banking/cetakBukti?n=' . base64_encode($period->period_id) . '&r=' . base64_encode($nis) . '&d=' . base64_encode(date('Y-m-d'));

                        $no_wa  = $student->student_parent_phone;
                        $psn    = $pesan;

                        send_notif_whatsapp($no_wa, $psn);
                    }

                    $response = array(
                        'status' => 1,
                        'message' => 'Success'
                    );

                    http_response_code(201);
                } else {
                    $response = ["message" => "NIS Not Found"];
                    http_response_code(400);
                }
            } else {
                $response = ["message" => "Invalid Input Request"];
                http_response_code(400);
            }
        } else {
            $response = ["message" => "Invalid Request Method."];
            http_response_code(405);
        }

        echo json_encode($response);
    }
}
