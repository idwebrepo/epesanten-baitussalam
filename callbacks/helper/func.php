<?php	

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

function create_whatsapp_number($no_hp)
{
    $no_hp = str_replace("-", "", $no_hp);
    $no_hp = str_replace(" ", "", $no_hp);
    $no_hp = str_replace("(", "", $no_hp);
    $no_hp = str_replace(")", "", $no_hp);
    $no_hp = str_replace(".", "", $no_hp);

    if (!preg_match('/[^+0-9]/', trim($no_hp))) {
        if (substr(trim($no_hp), 0, 1) == '+') {
            $hp = trim($no_hp);
        } elseif (substr(trim($no_hp), 0, 1) == '0') {
            $hp = '+62' . substr(trim($no_hp), 1);
        } elseif (substr(trim($no_hp), 0, 2) == '62') {
            $hp = '+' . trim($no_hp);
        } elseif (substr(trim($no_hp), 0, 1) == '8') {
            $hp = '+62' . trim($no_hp);
        } else {
            $hp = '+' . trim($no_hp);
        }
    }

    return $hp;
}

?>