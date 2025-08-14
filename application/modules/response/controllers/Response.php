<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Response extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);
    }

    public function index()
    {
        redirect(base_url());
    }

    public function callback()
    {
        $data = file_get_contents('php://input');

        if (empty($data)) {
            redirect(base_url());
        }

        $tag = explode("&", $data);

        foreach ($tag as $t) {
            $x = explode("=", $t);
            $a = $x[0];
            $b = $x[1];
            ${$a} = $b;
        }

        $insert = array(
            'trx_id' => $trx_id,
            'sid' => $sid,
            'status' => $status,
            'status_code' => $status_code,
            'via' => $via,
            'channel' => $channel,
            'va' => $va,
            'reference_id' => $reference_id,
            'total' => $total,
            'fees' => $fee,
            'comments' => $comments,
            'date' => $date,
            'buyer_name' => $buyer_name,
            'buyer_email' => $buyer_email,
            'buyer_phone' => $buyer_phone,
            'is_escrow' => $is_escrow,
        );

        $insertCallback = $this->db->insert('callback_donasi', $insert);

        if ($insertCallback) {

            if ($status_code == '1') {

                $nominal = $total - $fee;

                $updateDonasi = $this->db->query("UPDATE donasi SET donasi_status = '1' WHERE donasi_ref_id = '$reference_id'");

                $affected_rows = $this->db->affected_rows();

                if ($affected_rows) {
                    $donasi_id = $this->updated_id($reference_id);

                    $program = $this->db->query("SELECT a.donasi_hp, b.program_id, b.program_name, b.account_account_id FROM donasi a JOIN program b ON a.program_program_id = b.program_id WHERE a.donasi_id = '$donasi_id'")->row_array();

                    $program_id = $program['program_id'];

                    $earnProgram = $this->db->query("UPDATE program SET program_earn = program_earn + $nominal WHERE program_id = '$program_id'");

                    if ($earnProgram) {

                        $data_jurnal = array(
                            'note'       => 'Penerimaan Donasi ' . $program['program_name'],
                            'nominal'    => $nominal,
                            'account_id' => $program['account_account_id']
                        );

                        if ($this->insert_jurnal($data_jurnal)) {
                            $no_wa      = $program['donasi_hp'];

                            $pesan      = 'Terima kasih kepada saudara/i ' . $buyer_name . ' telah berdonasi sebesar Rp ' . number_format($nominal, 0, ',', '.') . ' untuk ' . $program['program_name'] . '. Semoga berkah dan menjadi amal jariyah saudara/i sekalian. Aamiin';

                            $this->send_wa($no_wa, $pesan);

                            $msg = 'berhasil';
                        }
                    } else {
                        echo 'Gagal update pendapatan program';
                        exit;
                    }
                } else {
                    echo 'Gagal update donasi status';
                    exit;
                }
            } else if ($status_code == '2') {
                $msg = 'pembayaran kadaluarsa';
            } else {
                $msg = 'tidak berhasil';
            }

            $obj = (object) [
                'is_correct' => true,
                'message' => $msg
            ];

            echo json_encode($obj, JSON_PRETTY_PRINT);

            $this->db->close();
        } else {
            echo 'Failed to insert callback';
            exit;
        }
    }

    public function booking()
    {
        $data = file_get_contents('php://input');

        if (empty($data)) {
            redirect(base_url());
        }

        $tag = explode("&", $data);

        foreach ($tag as $t) {
            $x = explode("=", $t);
            $a = $x[0];
            $b = $x[1];
            ${$a} = $b;
        }

        $insert = array(
            'trx_id' => $trx_id,
            'sid' => $sid,
            'status' => $status,
            'status_code' => $status_code,
            'via' => $via,
            'channel' => $channel,
            'va' => $va,
            'reference_id' => $reference_id,
            'total' => $total,
            'fees' => $fee,
            'comments' => $comments,
            'date' => $date,
            'buyer_name' => $buyer_name,
            'buyer_email' => $buyer_email,
            'buyer_phone' => $buyer_phone,
            'is_escrow' => $is_escrow,
        );

        $insertCallback = $this->db->insert('callback_booking', $insert);

        if ($insertCallback) {

            if ($status_code == '1') {

                $nominal = $total - $fee;

                $updateReservasi = $this->db->query("UPDATE reservasi SET reservasi_status = 'PAID' WHERE reservasi_ref_id = '$reference_id'");

                $affected_rows = $this->db->affected_rows();

                if ($affected_rows) {

                    $no_wa      = $program['donasi_hp'];

                    $pesan      = 'Terima kasih kepada saudara/i ' . $buyer_name . ' telah berdonasi sebesar Rp ' . number_format($nominal, 0, ',', '.') . ' untuk ' . $donasi['program_name'] . '. Semoga berkah dan menjadi amal jariyah saudara/i sekalian. Aamiin';

                    $this->send_wa($no_wa, $pesan);

                    $msg = 'berhasil';
                } else {
                    echo 'Gagal update donasi status';
                    exit;
                }
            } else if ($status_code == '2') {
                $msg = 'pembayaran kadaluarsa';
            } else {
                $msg = 'tidak berhasil';
            }

            $obj = (object) [
                'is_correct' => true,
                'message' => $msg
            ];

            echo json_encode($obj, JSON_PRETTY_PRINT);

            $this->db->close();
        } else {
            echo 'Failed to insert callback';
            exit;
        }
    }

    private function updated_id($reference_id)
    {
        $donasi = $this->db->query("SELECT donasi_id FROM donasi WHERE donasi_ref_id = '$reference_id'")->row_array();

        return $donasi['donasi_id'];
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

        $note       = $data_jurnal['note'];
        $nominal    = $data_jurnal['nominal'];
        $account_id = $data_jurnal['account_id'];

        $account = $this->get_account_detail($account_id);

        $majors_id = $account['majors_id'];
        $account_code = $account['account_code'];

        $aktiva_code = $this->get_aktiva_code($majors_id, 'IPAYMU');

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

    private function get_aktiva_code(int $majors_id, string $keyword): string
    {
        $aktiva = $this->db->query("SELECT account_code FROM account WHERE account_description LIKE '%$keyword%' AND account_majors_id = '$majors_id'")->row_array();

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
