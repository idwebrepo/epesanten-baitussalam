<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged') == NULL) {
            header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }

        $this->load->model(array(
            'users/Users_model',
            'holiday/Holiday_model',
            'dashboard/Dashboard_model',
            'student/Student_model',
            'kredit/Kredit_model',
            'debit/Debit_model',
            'bulan/Bulan_model',
            'setting/Setting_model',
            'information/Information_model',
            'rekap_presensi/Rekap_presensi_model',
            'bebas/Bebas_model',
            'bebas/Bebas_pay_model',
            'period/Period_model'
        ));

        $this->load->library('user_agent');
    }

    public function index()
    {

        $id = $this->session->userdata('uid');

        $umajors = $this->session->userdata('umajorsid');

        $whereMajors = "";

        if ($umajors != 0) {
            $whereMajors = " AND majors_id = '$umajors'";
        }

        $data['user']           = count($this->Users_model->get());

        $data['majors']         = $this->db->query("SELECT majors_id, majors_short_name FROM majors WHERE majors_status = 1 $whereMajors")->result_array();

        $period                 = $this->db->query("SELECT period_id FROM period WHERE period_status = 1")->row_array();
        $periodActive           = $period['period_id'];

        $mon                    = pretty_date(date('Y-m-d'), 'F', false);

        $month                  = $this->db->query("SELECT month_id FROM month WHERE month_name = '$mon'")->row_array();
        $data['monthActive']    = $month['month_id'];

        $data['kas']            = $this->db->query("SELECT kas_noref, kas_debit, kas_kredit, kas_month_id, kas_majors_id, kas_input_date FROM kas
                                                    JOIN majors ON majors_id = kas_majors_id
                                                    WHERE kas_period = '$periodActive'
                                                    $whereMajors")->result_array();
        $data['student']        = $this->db->query("SELECT student_status, majors_majors_id FROM student
                                                    JOIN majors ON majors_id = majors_majors_id
                                                    WHERE 1 $whereMajors")->result_array();
        $data['employee']       = $this->db->query("SELECT employee_category FROM employee
                                                    JOIN majors ON majors_id = employee_majors_id
                                                    WHERE 1 $whereMajors")->result_array();
        $data['banking']        = $this->db->query("SELECT banking_debit, banking_kredit, banking_date
                                                    FROM banking
                                                    JOIN student ON student_id = banking_student_id
                                                    JOIN majors ON majors_id = majors_majors_id
                                                    WHERE 1 $whereMajors")->result_array();
        $data['information']    = $this->Information_model->get(array('information_publish' => 1));
        $data['majors']         = $this->db->query("SELECT * FROM majors WHERE 1 $whereMajors")->result_array();
        $data['numStudents']    = $this->db->query("SELECT COUNT(student_id) as numbers
                                                    FROM student
                                                    JOIN majors ON majors_id = majors_majors_id
                                                    WHERE 1 $whereMajors
                                                    GROUP BY majors_majors_id
                                                    ORDER BY majors_majors_id ASC")->result_array();

        $data['lastPayments']   = $this->db->query("SELECT student.student_full_name
                                                    FROM log_trx
                                                    JOIN student ON student.student_id = log_trx.student_student_id
                                                    JOIN majors ON majors_id = majors_majors_id
                                                    WHERE date(log_trx.log_trx_input_date) = CURRENT_DATE $whereMajors
                                                    GROUP BY date(log_trx.log_trx_input_date)
                                                    ORDER BY log_trx.log_trx_input_date DESC
                                                    LIMIT 5")->result_array();

        $data['total_banking_period'] = 0;
        foreach ($data['banking'] as $row) {
            $data['total_banking_period'] += $row['banking_debit'] - $row['banking_kredit'];
        }

        $data['total_banking_month'] = 0;
        foreach ($data['banking'] as $row) {
            $date = date_create($row['banking_date']);
            if (date_format($date, "F") == date('F')) {
                $data['total_banking_month'] += $row['banking_debit'] - $row['banking_kredit'];
            }
        }

        $data['total_banking_today'] = 0;
        foreach ($data['banking'] as $row) {
            if ($row['banking_date'] == date('Y-m-d')) {
                $data['total_banking_today'] += $row['banking_debit'] - $row['banking_kredit'];
            }
        }

        $data['total_pay_period'] = 0;
        foreach ($data['kas'] as $row) {
            if (substr($row['kas_noref'], 0, 2) == 'SP') {
                $data['total_pay_period'] += $row['kas_debit'];
            }
        }

        $data['total_pay_month'] = 0;
        foreach ($data['kas'] as $row) {
            if (substr($row['kas_noref'], 0, 2) == 'SP' && $row['kas_month_id'] == $data['monthActive']) {
                $data['total_pay_month'] += $row['kas_debit'];
            }
        }

        $data['total_pay_today'] = 0;
        foreach ($data['kas'] as $row) {
            if (substr($row['kas_noref'], 0, 2) == 'SP' && $row['kas_input_date'] == date('Y-m-d')) {
                $data['total_pay_today'] += $row['kas_debit'];
            }
        }

        $data['total_debit_period'] = 0;
        foreach ($data['kas'] as $row) {
            $data['total_debit_period'] += $row['kas_debit'];
        }

        $data['total_debit_month'] = 0;
        foreach ($data['kas'] as $row) {
            if ($row['kas_month_id'] == $data['monthActive']) {
                $data['total_debit_month'] += $row['kas_debit'];
            }
        }

        $data['total_debit_today'] = 0;
        foreach ($data['kas'] as $row) {
            if ($row['kas_input_date'] == date('Y-m-d')) {
                $data['total_debit_today'] += $row['kas_debit'];
            }
        }

        $data['total_kredit_period'] = 0;
        foreach ($data['kas'] as $row) {
            $data['total_kredit_period'] += $row['kas_kredit'];
        }

        $data['total_kredit_month'] = 0;
        foreach ($data['kas'] as $row) {
            if ($row['kas_month_id'] == $data['monthActive']) {
                $data['total_kredit_month'] += $row['kas_kredit'];
            }
        }

        $data['total_kredit_today'] = 0;
        foreach ($data['kas'] as $row) {
            if ($row['kas_input_date'] == date('Y-m-d')) {
                $data['total_kredit_today'] += $row['kas_kredit'];
            }
        }

        $data['total_active'] = 0;
        foreach ($data['student'] as $row) {
            if ($row['student_status'] == 1) {
                $data['total_active'] += 1;
            }
        }

        $data['total_alumni'] = 0;
        foreach ($data['student'] as $row) {
            if ($row['student_status'] == 0) {
                $data['total_alumni'] += 1;
            }
        }

        $data['total_other'] = 0;
        foreach ($data['student'] as $row) {
            if ($row['student_status'] != 0 && $row['student_status'] != 1) {
                $data['total_other'] += 1;
            }
        }

        $data['total_tetap'] = 0;
        foreach ($data['employee'] as $row) {
            if ($row['employee_category'] == 1) {
                $data['total_tetap'] += 1;
            }
        }

        $data['total_non_tetap'] = 0;
        foreach ($data['employee'] as $row) {
            if ($row['employee_category'] != 1) {
                $data['total_non_tetap'] += 1;
            }
        }

        $data['total_all'] = 0;
        foreach ($data['employee'] as $row) {
            $data['total_all'] += 1;
        }

        $this->load->library('form_validation');
        if ($this->input->post('add', TRUE)) {
            $this->form_validation->set_rules('date', 'Tanggal', 'required');
            $this->form_validation->set_rules('info', 'Info', 'required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            if ($_POST and $this->form_validation->run() == TRUE) {
                list($tahun, $bulan, $tanggal) = explode('-', $this->input->post('date', TRUE));

                $params['year'] = $tahun;
                $params['date'] = $this->input->post('date');
                $params['info'] = $this->input->post('info');

                $ret = $this->Holiday_model->add($params);

                $this->session->set_flashdata('success', 'Tambah Agenda berhasil');
                redirect('manage');
            }
        } elseif ($this->input->post('del', TRUE)) {
            $this->form_validation->set_rules('id', 'ID', 'required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            if ($_POST and $this->form_validation->run() == TRUE) {
                $id = $this->input->post('id', TRUE);
                $this->Holiday_model->delete($id);

                $this->session->set_flashdata('success', 'Hapus Agenda berhasil');
                redirect('manage');
            }
        }

        $dates['tgl_awal']      = date('Y-m-d');
        $dates['tgl_akhir']     = date('Y-m-d');

        $data['package']        = $this->Setting_model->get(array('name' => 'setting_package'));

        $data['periodActive']   = $periodActive;
        $data['period']         = $this->Period_model->get();
        $data['month']          = $this->Bulan_model->get_month();
        $data['setting_logo']   = $this->Setting_model->get(array('id' => 6));
        $data['rekap_presensi'] = $this->Rekap_presensi_model->get_dashboard($dates);

        $data['holiday']        = $this->Holiday_model->get();
        $data['title']          = 'Dashboard';
        $data['main']           = 'dashboard/dashboard';
        $this->load->view('manage/layout', $data);
    }


    public function realisasi()
    {
        $umajors = $this->session->userdata('umajorsid');

        $whereMajors = "";

        if ($umajors != 0) {
            $whereMajors = " AND majors_id = '$umajors'";
        }

        $majors = $this->db->query("SELECT * FROM majors WHERE 1  $whereMajors")->result_array();

        $getRealisasi = $this->Dashboard_model->get_realisasi();

        $dataRealisasi = json_decode($getRealisasi, true);
        $rBulan = $dataRealisasi['rp_bulan'];
        $rBebas = $dataRealisasi['rp_bebas'];

        $html = '<thead>
                <tr>
                <th>Unit</th>
                <th>Akun Pembayaran</th>
                <th>Tagihan</th>
                <th>Terbayar</th>
                <th>Belum Terbayar</th>
                <th>Pencapaian</th>
                </tr>
                </thead>';

        $html .= '<tbody>';


        $sumTagihanBulan = 0;
        $sumTerbayarBulan = 0;
        $sumBelumBulan = 0;
        $sumPresentaseBulan = 0;

        $sumTagihanBebas = 0;
        $sumTerbayarBebas = 0;
        $sumBelumBebas = 0;
        $sumPresentaseBebas = 0;

        foreach ($majors as $major) {
            foreach ($rBulan as $dBulan) {
                if ($major['majors_id'] == $dBulan['majors_id']) {

                    $totalBulan = $dBulan['terbayar'] + $dBulan['belum'];
                    $pembulatanBulan = $this->pembulatan(($dBulan['terbayar'] / $totalBulan) * 100);

                    $html .= '<tr style="text-align: right;">
                    <td style="text-align: left;">' . $dBulan['majors_short_name'] . '</td>
                    <td style="text-align: left;">' . $dBulan['unit'] . '</td>
                    <td>Rp ' . number_format($totalBulan, '0', ',', '.') . '</td>
                    <td>Rp ' . number_format($dBulan['terbayar'], '0', ',', '.') . '</td>
                    <td>Rp ' . number_format($dBulan['belum'], '0', ',', '.') . '</td>
                    <td>' . $pembulatanBulan  . '% </td>
                    </tr>';

                    $sumTagihanBulan += $totalBulan;
                    $sumTerbayarBulan += $dBulan['terbayar'];
                    $sumBelumBulan += $dBulan['belum'];
                    $sumPresentaseBulan += $pembulatanBulan;
                }
            }
            foreach ($rBebas as $dBebas) {
                if ($major['majors_id'] == $dBebas['majors_id']) {

                    $totalBebas = $dBebas['terbayar'] + $dBebas['belum'];
                    $pembulatanBebas = $this->pembulatan(($dBebas['terbayar'] / $totalBebas) * 100);

                    $html .= '<tr style="text-align: right;">
                    <td style="text-align: left;">' . $dBebas['majors_short_name'] . '</td>
                    <td style="text-align: left;">' . $dBebas['unit'] . '</td>
                    <td>Rp ' . number_format($totalBebas, '0', ',', '.') . '</td>
                    <td>Rp ' . number_format($dBebas['terbayar'], '0', ',', '.') . '</td>
                    <td>Rp ' . number_format($dBebas['belum'], '0', ',', '.') . '</td>
                    <td>' . $pembulatanBebas . '% </td></tr>';

                    $sumTagihanBebas += $totalBebas;
                    $sumTerbayarBebas += $dBebas['terbayar'];
                    $sumBelumBebas += $dBebas['belum'];
                    $sumPresentaseBebas += $pembulatanBebas;
                }
            }
        }

        $html .= '</tbody>';

        $html .= '<tfoot>
                <tr style="font-weight: bold; text-align: right;">
                <td></td>
                <td></td>
                <td>Rp ' . number_format($sumTagihanBulan + $sumTagihanBebas, '0', ',', '.') . '</td>
                <td>Rp ' . number_format($sumTerbayarBulan + $sumTerbayarBebas, '0', ',', '.') . '</td>
                <td>Rp ' . number_format($sumBelumBulan + $sumBelumBebas, '0', ',', '.') . '</td>
                <td>' . $this->pembulatan(($sumTerbayarBulan + $sumTerbayarBebas) / ($sumTagihanBulan + $sumTagihanBebas) * 100) . '%</td></tr>
                </tfoot>';

        echo $html;
    }

    public function realisasi_xls()
    {
        $umajors = $this->session->userdata('umajorsid');

        $whereMajors = "";

        if ($umajors != 0) {
            $whereMajors = " AND majors_id = '$umajors'";
        }

        $getRealisasi   = $this->Dashboard_model->get_realisasi();

        $dataRealisasi  = json_decode($getRealisasi, true);
        $data['rBulan'] = $dataRealisasi['rp_bulan'];
        $data['rBebas'] = $dataRealisasi['rp_bebas'];
        $data['majors'] = $this->db->query("SELECT * FROM majors WHERE 1 $whereMajors")->result_array();

        $start = $this->get_month_name($_REQUEST['start']);
        $end = $this->get_month_name($_REQUEST['end']);

        $data['setting_nip_bendahara']      = $this->Setting_model->get(array('id' => 15));
        $data['setting_nama_bendahara']     = $this->Setting_model->get(array('id' => 16));
        $data['setting_city']               = $this->Setting_model->get(array('id' => SCHOOL_CITY));
        $data['setting_district']           = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
        $data['setting_school']             = $this->Setting_model->get(array('id' => SCHOOL_NAME));
        $data['setting_address']            = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
        $data['setting_phone']              = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

        $data['bulan'] = $start . '_' . $end;
        $data['period'] = $dataRealisasi['period'];

        $this->load->view('dashboard/realisasi_xls', $data);
    }

    private function get_month_name(Int $month = null)
    {

        $this->db->where('month_id', $month);
        $month = $this->db->get('month')->row_array();

        return $month['month_name'];
    }

    public function get()
    {
        $events = $this->Holiday_model->get();
        foreach ($events as $i => $row) {
            $data[$i] = array(
                'id' => $row['id'],
                'title' => strip_tags($row['info']),
                'start' => $row['date'],
                'end' => $row['date'],
                'year' => $row['year'],
                //'url' => event_url($row)
            );
        }
        echo json_encode($data, TRUE);
    }

    private function pembulatan(Float $number = null)
    {

        $round = ceil($number * 10) / 10;

        $round = number_format($round, 1);

        return $round;
    }
}
