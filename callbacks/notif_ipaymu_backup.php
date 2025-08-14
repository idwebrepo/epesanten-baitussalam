<?php

header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set('Asia/Jakarta');

$s_db       = 'epesantr_demo';
$s_host     = 'localhost';
$s_uname    = 'epesantr_demonstran';
$s_upass    = '5[RB*SpX,X0)p#mXn2';

$koneksi = @mysqli_connect($s_host, $s_uname, $s_upass ,$s_db);

if (mysqli_connect_errno()){
	echo "Koneksi database gagal : " . mysqli_connect_error();
}

header("Content-type:application/json");


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
// var_dump($data_json);
// exit;

$ip = get_client_ip();

$sql = "insert into data_ipaymu_notif (txt,ip) values ('$data','$ip')";
$rs = mysqli_query($koneksi,$sql);

//$data_asli = explode("&", $data);

$tag = explode("&", $data);
foreach($tag as $t)
{
    $x=explode("=",$t);
	$a=$x[0];
	$b=$x[1];
	${$a}=$b;
}

$sql = "insert into data_ipaymu_callback set
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

$period_aktif = "SELECT period_id FROM period WHERE period_status = '1'";

$read_period =  mysqli_query($koneksi,$period_aktif);
$res = mysqli_fetch_assoc($read_period);
$tahun_aktif = $res['period_id'];

$data = "SELECT * FROM `ipaymu_transaksi` 
JOIN `student` ON `ipaymu_transaksi`.`student_id` = `student`.`student_id` 
JOIN `majors` ON `student`.`majors_majors_id` = `majors`.`majors_id` 
JOIN `bulan` ON `bulan`.`ipaymu_no_trans` = `ipaymu_transaksi`.`id_transaksi`
WHERE va_transactionId = '$trx_id'";

// echo $data;
// exit;

$read_data =  mysqli_query($koneksi,$data);
$res_data = mysqli_fetch_assoc($read_data);

$noref = $res_data['noref'];
$month_id = $res_data['month_month_id'];
$majors_id = $res_data['majors_id'];
$name = $res_data['student_full_name'];
$bulan_bill = $res_data['bulan_bill'];

$akun_id = "SELECT account_id
FROM account 
WHERE account_majors_id  = '$majors_id' AND account_code LIKE '1-1%' AND
account_description LIKE '%IPAYMU%'";

$read_akun =  mysqli_query($koneksi,$akun_id);
$akun = mysqli_fetch_assoc($read_akun);
$id_akun = $akun['account_id'];

$insert_kas = "INSERT INTO kas SET 
	kas_noref =  '$noref', 
	kas_period = '$tahun_aktif',
	kas_date = SYSDATE(),
	kas_receipt = '',
	kas_tax_receipt = '',
	kas_input_date = SYSDATE(),
	kas_last_update = SYSDATE(),
	kas_month_id = '$month_id',
	kas_account_id = '$id_akun',
	kas_majors_id = '$majors_id', 
	kas_note = CONCAT('Pembayaran Ponpes ','$name'),
	kas_category = '1',
	kas_debit = '$bulan_bill',
	kas_kredit = 0,
	kas_user_id = 0";

// echo $insert_kas;
// exit;

$status_kode = $status_code;

$rs = mysqli_query($koneksi,$sql);

if($status_kode=1)
{
    $rs_kas = mysqli_query($koneksi,$insert_kas);
}
if($rs_kas)
{
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

echo json_encode($obj, JSON_PRETTY_PRINT);
mysqli_close($koneksi);

?>