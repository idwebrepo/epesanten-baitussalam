<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Dashboard_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function get_realisasi()
    {
        $wherePeriod = "";
        if (!empty($_REQUEST['period']) && $_REQUEST['period'] != 'all') {
            " AND period_id = $_REQUEST[period]";
        } else {
            " AND period_status = 1";
        }

        $umajors = $this->session->userdata('umajorsid');

        $whereMajors = "";

        if ($umajors != 0) {
            $whereMajors = " AND majors_id = '$umajors'";
        }

        $this->db->select('period_start,period_end,period_id');
        $period = $this->db->get('period')->row_array();

        if ($_REQUEST['period'] != 'all') {
            $wherePeriod = "AND payment.period_period_id = '$period[period_id]'";
        }

        if (!empty($_REQUEST['start']) && !empty($_REQUEST['end'])) {
            $start  = $_REQUEST['start'];
            $end    = $_REQUEST['end'];
        } else {
            $start  = $this->get_month($period['period_start'] . '-' . '07-01');
            $end    = $this->get_month($period['period_end'] . '-' . '06-30');
        }

        $whereDateBulan = "AND bulan.month_month_id BETWEEN '$start' AND '$end'";

        $rp_bulan       = $this->db->query("SELECT
                                    majors.majors_id,
                                    majors.majors_short_name,
                                    CONCAT(pos.pos_name, ' ', majors.majors_short_name) unit,
                                    SUM(CASE WHEN bulan.bulan_status = 1 THEN bulan.bulan_bill ELSE 0 END) AS terbayar,
                                    SUM(CASE WHEN bulan.bulan_status = 0 THEN bulan.bulan_bill ELSE 0 END) AS belum
                                    FROM `bulan` LEFT JOIN `month` ON `month`.`month_id` = `bulan`.`month_month_id` 
                                    LEFT JOIN `student` ON `student`.`student_id` = `bulan`.`student_student_id` 
                                    LEFT JOIN `payment` ON `payment`.`payment_id` = `bulan`.`payment_payment_id` 
                                    LEFT JOIN `pos` ON `pos`.`pos_id` = `payment`.`pos_pos_id` 
                                    LEFT JOIN `period` ON `period`.`period_id` = `payment`.`period_period_id` 
                                    LEFT JOIN `majors` ON `majors`.`majors_id` = `student`.`majors_majors_id` 
                                    WHERE 1 $whereDateBulan $wherePeriod $whereMajors
                                    GROUP BY pos.pos_id")->result_array();

        $rp_bebas       = $this->db->query("SELECT
                                    majors.majors_id,
                                    majors.majors_short_name,
                                    CONCAT(pos.pos_name, ' ', majors.majors_short_name) unit,
                                    SUM(bebas.bebas_total_pay) - SUM(bebas.bebas_diskon) AS terbayar,
                                    SUM(bebas.bebas_bill) - SUM(bebas.bebas_total_pay) - SUM(bebas.bebas_diskon) AS belum
                                    FROM pos 
                                    JOIN payment ON pos.pos_id = payment.pos_pos_id 
                                    JOIN bebas ON payment.payment_id = bebas.payment_payment_id 
                                    JOIN account ON account.account_id = pos.account_account_id 
                                    JOIN majors ON majors.majors_id = account.account_majors_id 
                                    JOIN student on bebas.student_student_id = student.student_id 
                                    WHERE 1 $wherePeriod $whereMajors
                                    GROUP BY pos.pos_id")->result_array();

        $data = array(
            'period'    => $period['period_start'] . '_' . $period['period_end'],
            'rp_bulan'  => $rp_bulan,
            'rp_bebas'  => $rp_bebas
        );

        return json_encode($data);
    }

    private function get_month(String $date = null)
    {
        $month_name = pretty_date($date, 'F', false);

        $this->db->where('month_name', $month_name);
        $month = $this->db->get('month')->row_array();

        return $month['month_id'];
    }
}
