<!DOCTYPE html>
<html>

<head>
	<title><?php foreach ($siswa as $row) : ?> <?php echo ($f['r'] == $row['student_nis']) ? $row['student_full_name'] : '' ?><?php endforeach; ?></title>
</head>

<style type="text/css">
	@page {
		margin-top: 0.5cm;
		/*margin-bottom: 0.1em;*/
		margin-left: 1cm;
		margin-right: 1cm;
		margin-bottom: 0.1cm;
	}

	.name-school {
		font-size: 12pt;
		font-weight: bold;
		padding-bottom: -15px;
		text-align: center;
	}

	.unit {
		font-weight: bold;
		font-size: 8pt;
		margin-bottom: -10px;
		text-align: center;
	}

	.alamat {
		font-size: 8pt;
		margin-bottom: 10px;
		text-align: center;
	}

	.detail {
		font-size: 8pt;
		font-weight: bold;
		padding-top: -15px;
		padding-bottom: -12px;
	}

	body {
		font-family: sans-serif;
		/*font-family: dotmatrx;*/
	}

	table {
		/*font-family: dotmatrx;*/
		font-family: verdana, arial, sans-serif;
		font-size: 10px;
		color: #333333;
		border-width: none;
		/*border-color: #666666;*/
		border-collapse: collapse;
		width: 100%;
	}

	th {
		padding-bottom: 8px;
		padding-top: 8px;
		border-color: #666666;
		background-color: #ffffff;
		/*border-bottom: solid;*/
		text-align: left;
	}

	td {
		text-align: left;
		border-color: #666666;
		background-color: #ffffff;
	}

	hr {
		border: none;
		height: 1px;
		/* Set the hr color */
		color: #333;
		/* old IE */
		background-color: #333;
		/* Modern Browsers */
	}

	.container {
		position: relative;
	}

	.topright {
		position: absolute;
		top: 0;
		right: 0;
		font-size: 8px;
		border-width: thin;
		padding: 5px;
	}

	.topright2 {
		position: absolute;
		top: 30px;
		right: 50px;
		font-size: 8px;
		border: 1px solid;
		padding: 5px;
		color: red;
	}
</style>

<style>
	img {
		filter: grayscale(100%);
	}
</style>

<body>

	<table>
		<tr>
			<td>
				<p class="name-school"><?php echo $setting_school['setting_value'] ?></p>
				<p class="alamat"><?php echo $setting_address['setting_value'] ?></p>
				<p class="alamat"><?php echo ' Telp. ' . $setting_phone['setting_value'] ?></p>
			</td>
		</tr>
	</table>
	<hr>
	<table style="padding-top: -5px; padding-bottom: 5px">
		<tr>
			<td style="width: 80px;">No. Referensi</td>
			<td style="width: 5px;">:</td>
			<td style="width: 170px;"><?php echo $bcode['kas_noref']; ?></td>
		</tr>
		<tr>
			<td>Tahun Ajaran</td>
			<td>:</td>
			<td><?php foreach ($period as $row) : ?> <?php echo ($f['n'] == $row['period_id']) ? $row['period_start'] . '/' . $row['period_end'] : '' ?><?php endforeach; ?></td>
		</tr>
		<tr>
			<td>Tanggal Bayar</td>
			<td style="width: 5px;">:</td>
			<td><?php echo pretty_date($f['d'], 'd F Y', false) ?></td>
		</tr>
		<tr>
			<td>Akun Kas</td>
			<td style="width: 5px;">:</td>
			<td><?php echo $bcode['account_description'] ?></td>
		</tr>
		<tr>
			<td>NIS</td>
			<td style="width: 5px;">:</td>
			<?php foreach ($siswa as $row) : ?>
				<td><?php echo $row['student_nis'] ?></td>
			<?php endforeach ?>
		</tr>
		<tr>
			<td>Nama</td>
			<td style="width: 5px;">:</td>
			<?php foreach ($siswa as $row) : ?>
				<td><?php echo $row['student_full_name'] ?></td>
			<?php endforeach ?>
		</tr>
		<tr>
			<td>Kelas</td>
			<td style="width: 5px;">:</td>
			<?php foreach ($siswa as $row) : ?>
				<td><?php echo $row['class_name'] ?></td>
			<?php endforeach ?>
		</tr>
		<tr>
			<td>Kelas Pondok</td>
			<td style="width: 5px;">:</td>
			<?php foreach ($siswa as $row) : ?>
				<td><?php echo $row['madin_name'] ?></td>
			<?php endforeach ?>
		</tr>

	</table>
	<table>
		<tr>
			<th style="border-top: 1px solid; border-bottom: 1px solid;">No.</th>
			<th style="border-top: 1px solid; border-bottom: 1px solid;">Pembayaran</th>
			<th colspan="2" style="border-top: 1px solid; border-bottom: 1px solid; text-align: center">Jumlah Pembayaran</th>
		</tr>
		<?php
		$i = 1;
		foreach ($bulan as $row) :
			$namePay = $row['pos_name'] . ' - T.A ' . $row['period_start'] . '/' . $row['period_end'];
			$mont = ($row['month_month_id'] < 7) ? $row['period_start'] : $row['period_end'];
		?>
			<tr>
				<td style="border-bottom: 1px solid;padding-top: 10px; padding-bottom: 10px;" valign="top"><?php echo $i ?></td>
				<td style="border-bottom: 1px solid;" valign="top"><?php echo $namePay . ' - (' . $row['month_name'] . ' ' . $mont . ')' ?></td>
				<td style="border-bottom: 1px solid;" valign="top">Rp. </td>
				<td style="border-bottom: 1px solid; text-align: right;" valign="top"><?php echo number_format($row['bulan_bill'], 0, ',', '.') ?></td>
			</tr>
		<?php
			$i++;
		endforeach ?>
		<?php
		$j = $i;
		foreach ($paket as $row) :
			$namePay = $row['name'] . ' - T.A ' . $row['period_start'] . '/' . $row['period_end'];
			$mont = ($row['month_month_id'] < 7) ? $row['period_start'] : $row['period_end'];
		?>
			<tr>
				<td style="border-bottom: 1px solid;padding-top: 10px; padding-bottom: 10px;" valign="top"><?php echo $j ?></td>
				<td style="border-bottom: 1px solid;" valign="top"><?php echo $namePay . ' - (' . $row['month_name'] . ' ' . $mont . ')' ?></td>
				<td style="border-bottom: 1px solid;" valign="top">Rp. </td>
				<td style="border-bottom: 1px solid; text-align: right;" valign="top"><?php echo number_format($row['total'], 0, ',', '.') ?></td>
			</tr>
		<?php
			$j++;
		endforeach ?>

		<?php
		$k = $j;
		foreach ($free as $row) :
			$namePayFree = $row['pos_name'] . ' - T.A ' . $row['period_start'] . '/' . $row['period_end'];
		?>
			<tr>
				<td style="border-bottom: 1px solid;padding-top: 10px; padding-bottom: 10px;" valign="top"><?php echo $k ?></td>
				<td style="border-bottom: 1px solid;" valign="top"><?php echo $namePayFree . ' - (' . $row['bebas_pay_desc'] . ')' ?></td>
				<td style="border-bottom: 1px solid;" valign="top">Rp. </td>
				<td style="border-bottom: 1px solid; text-align: right;" valign="top"><?php echo number_format($row['bebas_pay_bill'], 0, ',', '.') ?></td>
			</tr>
		<?php
			$k++;
		endforeach ?>
		<tr>
			<td colspan="2" style="background-color: #ffffff; font-weight:bold; border-bottom: 1px solid;">Total Pembayaran</td>
			<td style="background-color: #ffffff;font-weight:bold;border-bottom: 1px solid;">Rp. </td>
			<td style="background-color: #ffffff; font-weight:bold;border-bottom: 1px solid; text-align: right;"><?php echo number_format($sumpaket + $summonth + $sumbeb, 0, ',', '.') ?></td>
		</tr>

		<?php
		$user = $this->db->query("SELECT users.user_full_name AS kasir FROM users JOIN kas ON kas.kas_user_id = users.user_id WHERE kas.kas_noref = '" . $bcode['kas_noref'] . "'")->row_array();
		?>

		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4" style="text-align: center">
				<?php echo $setting_city['setting_value'] ?>, <?php echo pretty_date(date('Y-m-d'), 'd F Y', false) ?>
			</td>
		</tr>
		<tr>
			<td colspan="4" style="text-align: center">Kasir</td>
		</tr>
		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4" style="text-align: center"><b><?php echo ucfirst($user['kasir']); ?></b></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4" style="text-align: center">Simpan Kwitansi Ini Sebagai Bukti Pembayaran yang Sah</td>
		</tr>
	</table>
	<br>





</body>

</html>