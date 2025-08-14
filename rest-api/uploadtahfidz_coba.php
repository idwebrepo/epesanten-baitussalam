<?php
include('fungsi/tanggal.php');

$db_host='localhost';
$db_user='epesantr_demonstran';
$db_pass='5[RB*SpX,X0)p#mXn2';
$db_name='epesantr_demo';
$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

date_default_timezone_set('Asia/Jakarta');

function GetValue($tablename, $column, $where) {
    global $koneksi;
    $sql = "SELECT $column FROM $tablename WHERE $where";
    $result_get_value = mysqli_query($koneksi,$sql);
    $row_get_value = mysqli_fetch_row($result_get_value);
    return $row_get_value[0];
}

    // $id_pegawai = isset($_REQUEST['id_pegawai']) ? $_REQUEST['id_pegawai'] : '';
    $jumlahhafalan = isset($_REQUEST['jumlah']) ? $_REQUEST['jumlah'] : '';
	$hafalan= isset($_REQUEST['hafal']) ? $_REQUEST['hafal'] : '';
    $murojaah = isset($_REQUEST['murojaah']) ? $_REQUEST['murojaah'] : '';
    $murojaahbaru = isset($_REQUEST['baru']) ? $_REQUEST['baru'] : '';
    // $keterangan = isset($_REQUEST['keterangan']) ? $_REQUEST['keterangan'] : '';
    $created_date  = date("Y-m-d H:i:s");
 
    $response['jumlah'] = $jumlahhafalan;
    $response['hafal'] = $hafalan;
    $response['murojaah'] = $murojaah;
    $response['murojaahbaru'] = $murojaahbaru;
    // $response['keterangan'] = $keterangan;
    // $response['id_pegawai'] = $id_pegawai;
    // $status = "SUKSES_UPLOAD";
	
	echo json_encode($response);
	
	// tulis data ke database dulu
        // $created_date   = date("Y-m-d H:i:s");   
        // $bulan          = date("Y-m");
        // $date           = date("Y-m-d");
        // $time           = date("H:i:s");
        
        // $id_pegawai = $id_pegawai;  
        // $jenis_absen = $type;  
        // $foto = $file_path;  
        // $catatan_absen = $keterangan;  
        // $remark = $status;  
        // $lokasi = $lokasi;
?>