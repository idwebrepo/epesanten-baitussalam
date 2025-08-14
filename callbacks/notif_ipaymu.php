<?php
header("Access-Control-Allow-Origin: *");
include_once("config/config.php");
// include_once("helper/func.php");
header("Content-type:application/json");
date_default_timezone_set('Asia/Jakarta');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

function pretty_date($date = '', $format = '', $timezone = TRUE) {
    $date_str = strtotime($date);

    if (empty($format)) {
        $date_pretty = date('l, d/m/Y H:i', $date_str);
    } else {
        $date_pretty = date($format, $date_str);
    }

    if ($timezone) {
        $date_pretty .= ' WIB';
    }

    $date_pretty = str_replace('Sunday', 'Ahad', $date_pretty);
    $date_pretty = str_replace('Monday', 'Senin', $date_pretty);
    $date_pretty = str_replace('Tuesday', 'Selasa', $date_pretty);
    $date_pretty = str_replace('Wednesday', 'Rabu', $date_pretty);
    $date_pretty = str_replace('Thursday', 'Kamis', $date_pretty);
    $date_pretty = str_replace('Friday', 'Jumat', $date_pretty);
    $date_pretty = str_replace('Saturday', 'Sabtu', $date_pretty);

    $date_pretty = str_replace('January', 'Januari', $date_pretty);
    $date_pretty = str_replace('February', 'Februari', $date_pretty);
    $date_pretty = str_replace('March', 'Maret', $date_pretty);
    $date_pretty = str_replace('April', 'April', $date_pretty);
    $date_pretty = str_replace('May', 'Mei', $date_pretty);
    $date_pretty = str_replace('June', 'Juni', $date_pretty);
    $date_pretty = str_replace('July', 'Juli', $date_pretty);
    $date_pretty = str_replace('August', 'Agustus', $date_pretty);
    $date_pretty = str_replace('September', 'September', $date_pretty);
    $date_pretty = str_replace('October', 'Oktober', $date_pretty);
    $date_pretty = str_replace('November', 'November', $date_pretty);
    $date_pretty = str_replace('December', 'Desember', $date_pretty);

return $date_pretty;
}

function send_whatsapp($no_wa, $psn)
{
    $url    ='http://116.203.92.59/api/send_message';
    $data   = array(
        "phone_no"  => $no_wa,
        "key"       => 'fce9862deac8554e58028c35011e14214b47496d05fbd61c',
        "message"   => $psn
    );
    $data_string = json_encode($data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 360);
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
    //echo $res = curl_exec($ch);
    curl_close($ch);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit();
}

$db_host ='localhost';
$db_user ='epesantr_semua';
$db_pass ='5[RB*SpX,X0)p#mXn2';
$db_name ='epesantr_mobile';
$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

$data = file_get_contents('php://input');
// var_dump($data);
// exit;
$data_json = json_decode($data, true);
//var_dump($data_json);
//exit;
$tag = explode("&", $data);
// var_dump($tag);
// exit;
foreach($tag as $t)
{
    $x=explode("=",$t);
	$a=$x[0];
	$b=$x[1];
	${$a}=$b;
}

$sekolah_siswa = explode("|", $buyer_name);
$student_name = $buyer_name[0];
$code_sekolah = $buyer_name[1];

function GetValue($tablename, $column, $where) {
    global $koneksi;
    $sql = "SELECT $column FROM $tablename WHERE $where";
    $rowult_get_value = mysqli_query($koneksi,$sql);
    $row_get_value = mysqli_fetch_row($rowult_get_value);
    return $row_get_value[0];
}

$nama_sekolah = GetValue("sekolahs","nama_sekolah","kode_sekolah='$code_sekolah'");
$alamat_sekolah = GetValue("sekolahs","alamat_sekolah","kode_sekolah='$code_sekolah'");
$database = GetValue("sekolahs","db","kode_sekolah='$code_sekolah'").".";
$domain = GetValue("sekolahs","folder","kode_sekolah='$code_sekolah'");
$waktuindonesia = GetValue("sekolahs","waktu_indonesia","kode_sekolah='$code_sekolah'");

if($database=='epesantr_demo.'){
    $db_host='103.41.206.228';
    $db_user='epesantr_demonstran';
    $db_pass='5[RB*SpX,X0)p#mXn2';
    $db_name='epesantr_demo';
    $koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
}

if($database=='alrifaie_database.'){
    $db_host='103.41.204.234';
    $db_user='alrifaie_user';
    $db_pass='acVVeRG))gaH';
    $db_name='alrifaie_database';
    $koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
}

if($database=='luqmanal_db.'){
    $db_host='103.93.130.71';
    $db_user='luqmanal_adm';
    $db_pass='CfRs{ga%Y@;R17E^3y';
    $db_name='luqmanal_db';
    $koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
}

if($database=='andri.'){
    $db_host='10.122.25.54';
    $db_user='andri';
    $db_pass='andri2022';
    $db_name='andri';
    $koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
}

function GetValue_2($tablename, $column, $where) {
    global $koneksi;
    $sql = "SELECT $column FROM $tablename WHERE $where";
    $rowult_get_value = mysqli_query($koneksi,$sql);
    $row_get_value = mysqli_fetch_row($rowult_get_value);
    return $row_get_value[0];
}

$ip = get_client_ip();

$sql_notif = "insert into '.$database.'data_ipaymu_notif (txt,ip) values ('$data','$ip')";
// echo $sql_notif;
// exit;
$rs = mysqli_query($koneksi,$sql_notif);

//$data_asli = explode("&", $data);

$sql = "insert into ".$database."data_ipaymu_callback set
trx_id = '$trx_id',
sid = '$sid',
status = '$status',
status_code = '$status_code',
via = '$via',
channel = '$channel',
va = '$va',
reference_id = '$reference_id',
total = '$total',
fees = '$fee',
comments = '$comments',
date = '$date',
buyer_name = '$buyer_name',
buyer_email = '$buyer_email',
buyer_phone = '$buyer_phone',
is_escrow = '$is_escrow'";

// echo $sql;
// exit;

$rs = mysqli_query($koneksi,$sql);

// $varjson = json_decode(file_get_contents("php://input"));//Ambil variabel JSON
// print_r($varjson);
// exit;

if ($status_code==1){


$period_aktif = "SELECT '.$database.'period_id FROM period WHERE period_status = '1'";

$read_period =  mysqli_query($koneksi,$period_aktif);
$res = mysqli_fetch_assoc($read_period);
$tahun_aktif = $res['period_id'];

$data = "SELECT * FROM '.$database.'`ipaymu_transaksi` 
JOIN `student` ON `ipaymu_transaksi`.`student_id` = `student`.`student_id` 
JOIN `majors` ON `student`.`majors_majors_id` = `majors`.`majors_id` 
JOIN `bulan` ON `bulan`.`ipaymu_no_trans` = `ipaymu_transaksi`.`id_transaksi`
WHERE va_transactionId = '$trx_id'";

// echo $data;
// exit;

$read_data =  mysqli_query($koneksi,$data);
$res_data = mysqli_fetch_assoc($read_data);

$noref = $res_data['noref'];
// $month_id = $res_data['month_month_id'];
// $majors_id = $res_data['majors_id'];
$name = $res_data['student_full_name'];
$bulan_bill = $res_data['nominal'];

$sql_nomorwa = "select student.student_id, student.student_parent_phone
from ".$database."student 
where student_nis='$student_name'";

$read_nomorwa =  mysqli_query($koneksi,$sql_nomorwa);
$sr = mysqli_fetch_assoc($read_nomorwa);
$siswaID = $sr['student_id'];
$no_wa = $sr['student_parent_phone'];

$nomorwa = "SELECT `setting_value` FROM ".$database."`setting` WHERE `setting_id` = '92'";
$pstren = "SELECT `setting_value` FROM ".$database."`setting` WHERE `setting_id` = '1'";

$read_nomorwa =  mysqli_query($koneksi,$nomorwa);
$read_pstren =  mysqli_query($koneksi,$pstren);

$res = mysqli_fetch_assoc($read_nomorwa);
$pes = mysqli_fetch_assoc($read_pstren);

$nomor_wa = $res['setting_value'];
$pesantren = $pes['setting_value'];

$akun_id = "SELECT akun_id, unit_id 
FROM ".$database."akun_ipaymu 
WHERE tipe = 'pembayaran'";

$akun =  mysqli_query($koneksi,$akun_id);
$akun1 = mysqli_fetch_assoc($akun);
$id_akun = $akun1['akun_id'];
$id_unit = $akun1['unit_id'];

$keterangan = 'Pembayaran Santri ' . $name;

$jurnalumum_exist = "SELECT * FROM information_schema.tables WHERE table_schema = '".str_replace('.','',$database)."' AND table_name = 'jurnal_umum' LIMIT 1";

$exist = mysqli_query($koneksi,$jurnalumum_exist);
$existjurnalumum=mysqli_num_rows($exist);

//echo $existjurnalumum;
//exit;
// $Jurum = $existjurnalumum;
if ($existjurnalumum > 0) {
    
$insert_jurnalumum = "INSERT INTO `jurnal_umum`(`sekolah_id`, `keterangan`, `tanggal`, `pencatatan`, `waktu_update`, `keterangan_lainnya`) 
VALUES ('$majors_id','$keterangan','$date','auto','$datedetail','-')";
mysqli_query($koneksi,$insert_jurnalumum);
// echo $insert_jurnalumum;
// exit;

$jurum = "SELECT MAX(id) AS last_id FROM jurnal_umum";
$read_jurum =  mysqli_query($koneksi,$jurum);
$jurum_data = mysqli_fetch_assoc($read_jurum);
$lastJurum = $jurum_data['last_id'];
// echo $lastJurum;
// exit;

}

$account_kode ="SELECT account_code AS kas_akun
FROM ".$database."account
WHERE account_id='$id_akun'";

$read_akuncode =  mysqli_query($koneksi,$account_kode);
$akunkode = mysqli_fetch_assoc($read_akuncode);
$code_akun = $akunkode['kas_akun'];

$jurumdetail_exist = "SELECT * FROM information_schema.tables WHERE table_schema = '".str_replace('.','',$database)."' AND table_name = 'jurnal_umum_detail' LIMIT 1";
$exist = mysqli_query($koneksi,$jurumdetail_exist);
$existjurumdetail=mysqli_num_rows($exist);
// $Jurumdetail = $existjurumdetail;
if ($existjurumdetail > 0) {
    
$jurum_detail = "INSERT INTO ".$database."`jurnal_umum_detail`(`id_jurnal`, `account_code`, `debet`, `kredit`) 
VALUES ('$lastJurum','$code_akun','$total','0')";
mysqli_query($koneksi,$jurum_detail);
// echo $jurum_detail;
// exit;

}

$bulanjurnal_exist = "SELECT * FROM information_schema.tables WHERE table_schema = '".str_replace('.','',$database)."' AND table_name = 'bulan_jurnal' LIMIT 1";
$exist_bulanjurnal = mysqli_query($koneksi,$bulanjurnal_exist);
$existBulanjurnal=mysqli_num_rows($exist_bulanjurnal);
// $Bulanjurnal = $existBulanjurnal;
if ($existBulanjurnal > 0) {

$trxBulans = "SELECT bulan_jurnal_account_code AS trx_akun,
                bulan_jurnal_nominal AS trx_value
                FROM ".$database."bulan_jurnal
                WHERE bulan_jurnal_noref = '$noref'";
                        
$trxBulan=mysqli_query($koneksi,$trxBulans);
$countBulans=mysqli_num_rows($trxBulan);
$arrayBulans=mysqli_fetch_array($trxBulan);

if ($countBulans > 0) {

    for ($i = 0; $i < $countBulans; $i++) {
        
        $arrayMonth = $arrayBulans['trx_akun'];
        $arrayValue = $arrayBulans['trx_value'];
        // var_dump($array);
        $jurumdetail_bulan = "INSERT INTO ".$database."jurnal_umum_detail set
                id_jurnal ='$lastJurum',
                account_code = '$arrayMonth',
                debet = '0',
                kredit = '$arrayValue'";
        // 	echo $jurumdetail_bulan;
        mysqli_query($koneksi,$jurumdetail_bulan);
    }

// exit;

}

}

$bebasjurnal_exist = "SELECT * FROM information_schema.tables WHERE table_schema = '".str_replace('.','',$database)."' AND table_name = 'bebas_jurnal' LIMIT 1";
$exist_bebasjurnal = mysqli_query($koneksi,$bebasjurnal_exist);
$existBulanjurnal=mysqli_num_rows($exist_bebasjurnal);
// $Bulanjurnal = $existBulanjurnal;
if ($existBulanjurnal > 0) {

$trxFrees = "SELECT bebas_jurnal_account_code AS trx_akun,
                bebas_jurnal_nominal AS trx_value
                FROM ".$database."bebas_jurnal
                WHERE bebas_jurnal_noref = '$noref'";
                        
$trxBebas=mysqli_query($koneksi,$trxFrees);
$countBebas=mysqli_num_rows($trxBebas);
// $Frees = $countBebas;
$arrayFrees=mysqli_fetch_array($trxBebas);
//echo $countBebas;
    //exit;
if ($countBebas > 0) {
    for ($i = 0; $i < $countBulans; $i++) {
        
        $arrayFree = $arrayFrees['trx_akun'];
        $arrayValueFree = $arrayFrees['trx_value'];
        // var_dump($array);
        $jurumdetail_bebas = "INSERT INTO ".$database." jurnal_umum_detail set
                id_jurnal ='$lastJurum',
                account_code = '$arrayFree',
                debet = '0',
                kredit = '$arrayValueFree'";
        // 	echo $jurumdetail_bulan;
        mysqli_query($koneksi,$jurumdetail_bebas);
    }
}

}

$mon = pretty_date(date('Y-m-d'), 'F', false);
$month_id = "SELECT month_id FROM ".$database."month WHERE month_name = '$mon'";
$read_month =  mysqli_query($koneksi,$month_id);
$res_month = mysqli_fetch_assoc($read_month);
$id_month = $res_month['month_id'];
$today = date('Y-m-d');

$insert_kas = "INSERT INTO ".$database."kas SET
    kas_noref =  '$noref', 
    kas_period = '$tahun_aktif',
    kas_date = '$today',
    kas_receipt = '',
    kas_tax_receipt = '',
    kas_input_date =  '$today',
    kas_last_update =  '$today',
    kas_month_id = '$id_month',
    kas_account_id = '$id_akun',
    kas_majors_id = '$id_unit', 
    kas_note = CONCAT('Pembayaran Ponpes ','$name'),
    kas_category = '1',
    kas_debit = '$bulan_bill',
    kas_kredit = 0,
    kas_user_id = 0";
$rs_kas = mysqli_query($koneksi,$insert_kas);
//echo $insert_kas;
//exit;
$rs = mysqli_query($koneksi,$sql);
$psn = "Assalamualaikum Warahmatullahi Wabarakatuh" . "\n" . "Tanggal : " . pretty_date(date('Y-m-d'), 'l, d F Y', false). "\n" . "Yth ayah/bunda dari ananda ".$name."," . "\n" . "terima kasih pembayaran sudah berhasil." . "\n" . "Terimakasih" . "\n" . "Wassalamu'alaikum Warahmatullahi Wabarakatuh" . "\n\n" . "WA Center Pesantren : " . $nomor_wa;
$sent = send_whatsapp($no_wa, $psn);

if($rs_kas)
{
$bebasjurnal_delete = "DELETE FROM ".$database."bebas_jurnal WHERE bebas_jurnal_noref = '$noref'";
mysqli_query($koneksi,$bebasjurnal_delete);
$bulanjurnal_delete = "DELETE FROM ".$database."bulan_jurnal WHERE bulan_jurnal_noref = '$noref'";
mysqli_query($koneksi,$bulanjurnal_delete);
$obj = (object) [
    'is_correct' => true,
    'message' => 'OK'
];
} else {
$obj = (object) [
    'is_correct' => false,
    'message' => 'Salah'
];
}
$obj = (object) [
    'is_correct' => true,
    'message' => 'Pembayaran Berhasil'
];
} else {
    $obj = (object) [
        'is_correct' => false,
        'message' => 'Pembayaran Expired'
    ];
}

echo json_encode($obj, JSON_PRETTY_PRINT);
mysqli_close($koneksi);

?>
