<?php
$d='absensi_siswa/'.date('YmdHis').'.jpg';
move_uploaded_file($_FILES['webcam']['tmp_name'], '../uploads/'.$d.'');
echo $d;
?>