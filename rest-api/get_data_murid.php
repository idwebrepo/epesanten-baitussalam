<?php
header("Content-type:application/json");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db_host='localhost';
$db_user='epesantr_demonstran';
$db_pass='5[RB*SpX,X0)p#mXn2';
$db_name='epesantr_demo';
$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (mysqli_connect_errno()){
	echo "Koneksi database gagal : " . mysqli_connect_error();
}


date_default_timezone_set('Asia/Jakarta');

$category  = "";   
if (isset($_REQUEST["category"]))    
  $category=$_REQUEST["category"];


$sql = "SELECT student_nis anggotakode, student_full_name anggotanama, concat('MURID-',student_kelas) anggotainstansi, student_address anggotaalamat FROM student";

$hasil  =mysqli_query($koneksi,$sql);


if(mysqli_num_rows($hasil) > 0 ){
  $response = array();
  $response["data"] = array();
  while($x = mysqli_fetch_array($hasil)){
	$h['anggotakode'] = $x['anggotakode'];
	$h['anggotanama'] = $x['anggotanama'];
	$h['anggotainstansi'] = $x['anggotainstansi'];
	$h['anggotaalamat'] = $x['anggotaalamat'];
    array_push($response["data"], $h);
  }
  echo json_encode($response);
}else {
	$response["data"]=array();
  echo json_encode($response);
}

mysqli_close($koneksi);
?>