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

$sql = "insert into data_ipaymu_callback_tabungan set
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

if($rs) 
{

$row = mysqli_fetch_array($rs);

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

// $obj = (object) [
//   'is_correct'  => true,
//   'sql'         => $sql,
//   'message'     => 'OK'
// ];

echo json_encode($obj, JSON_PRETTY_PRINT);
//tutup koneksi ke database
mysqli_close($koneksi);

?>