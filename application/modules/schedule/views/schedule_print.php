<html>

<head>
	<title>Cetak Jadwal Pelajaran</title>
	<style type="text/css">
		.upper {
			text-transform: uppercase;
		}

		.lower {
			text-transform: lowercase;
		}

		.cap {
			text-transform: capitalize;
		}

		.small {
			font-variant: small-caps;
		}
	</style>
	<style type="text/css">
		@page {
			margin-top: 1cm;
			margin-bottom: 1cm;
			margin-left: 1cm;
			margin-right: 1cm;
		}

		.style12 {
			font-size: 10px
		}

		.style13 {
			font-size: 14pt;
			font-weight: bold;
		}

		.name-school {
			font-size: 15pt;
			font-weight: bold;
			padding-bottom: -15px;
		}

		.unit {
			font-size: 8pt;
			font-weight: bold;
			margin-bottom: -10px;
		}

		.alamat {
			font-size: 8pt;
			margin-bottom: -10px;
		}

		.title {
			font-size: 14pt;
			text-align: center;
			font-weight: bold;
			padding-bottom: -10px;
		}

		.tp {
			font-size: 12pt;
			text-align: center;
			font-weight: bold;
		}

		.unit-siswa {
			font-size: 10pt;
			text-align: center;
			font-weight: bold;
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

		body {
			font-family: sans-serif;
		}

		table {
			border-collapse: collapse;
			font-size: 9pt;
			width: 100%;
		}
	</style>
</head>

<body>

	<p class="name-school"><?php echo $setting_school['setting_value'] ?></p>
	<p class="unit">
		<?php
		$num = count($unit);
		if ($num > 1) {
			foreach ($unit as $row) {
				$data  = $row['majors_short_name'];
				$Pecah = explode(" ", $data);
				for ($i = 0; $i < count($Pecah); $i++) {
					echo $Pecah[$i] . " ";
				}
			}
		} ?></p>
	<p class="alamat"><?php echo $setting_address['setting_value'] ?> <?php echo $setting_phone['setting_value'] ?></p>
	<hr>
	<p class="title">JADWAL PELAJARAN <?php echo $period['period_start'] . '/'  . $period['period_end']  ?></p>

	<table style="font-size: 10pt;" width="100%" border="0">
		<tr>
			<td>Unit</td>
			<td>:</td>
			<td><?php echo $majors['majors_short_name'] ?></td>
			<td width="60%"></td>
		</tr>
		<tr>
			<td>Kelas</td>
			<td>:</td>
			<td><?php echo $class['class_name'] ?></td>
			<td width="60%"></td>
		</tr>
	</table>
	<br>
	<?php
	foreach ($day as $row) {
		if ($row['day_name'] != 'Minggu') {
			$hari[] = $row;
		}
	}
	?>

	<?php
	$id_kelas = $f['r'];
	$senin = $this->db->query("SELECT * FROM schedule JOIN lesson ON lesson.lesson_id = schedule.schedule_lesson_id WHERE schedule_day = '" . $hari[0]['day_id'] . "' AND schedule_class_id = '$id_kelas'")->result_array();
	$selasa = $this->db->query("SELECT * FROM schedule JOIN lesson ON lesson.lesson_id = schedule.schedule_lesson_id WHERE schedule_day = '" . $hari[1]['day_id'] . "' AND schedule_class_id = '$id_kelas'")->result_array();
	$rabu = $this->db->query("SELECT * FROM schedule JOIN lesson ON lesson.lesson_id = schedule.schedule_lesson_id WHERE schedule_day = '" . $hari[2]['day_id'] . "' AND schedule_class_id = '$id_kelas'")->result_array();
	$kamis = $this->db->query("SELECT * FROM schedule JOIN lesson ON lesson.lesson_id = schedule.schedule_lesson_id WHERE schedule_day = '" . $hari[3]['day_id'] . "' AND schedule_class_id = '$id_kelas'")->result_array();
	$jumat = $this->db->query("SELECT * FROM schedule JOIN lesson ON lesson.lesson_id = schedule.schedule_lesson_id WHERE schedule_day = '" . $hari[4]['day_id'] . "' AND schedule_class_id = '$id_kelas'")->result_array();
	$sabtu = $this->db->query("SELECT * FROM schedule JOIN lesson ON lesson.lesson_id = schedule.schedule_lesson_id WHERE schedule_day = '" . $hari[5]['day_id'] . "' AND schedule_class_id = '$id_kelas'")->result_array();

	?>
	<table width="100%">
		<tr>
			<td width="50%" valign="top">
				<h3><?php echo $hari[0]['day_name'] ?></h3>
				<table border="1">
					<thead>
						<tr>
							<th>No</th>
							<th>Mata Pelajaran</th>
							<th>Waktu</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($senin as $item) {
							$id_time = $item['schedule_jampel'];
							$time = $this->db->query("SELECT * FROM jam_pelajaran WHERE jam_pelajaran_id = '$id_time' ")->row_array();
						?>
							<tr>
								<td align="center"><?php echo $no++ ?></td>
								<td><?php echo $item['lesson_name'] ?></td>
								<td align="center"><?php echo $time['jam_pelajaran_start'] . ' s/d ' . $time['jam_pelajaran_end'] ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</td>
			<td width="50%" valign="top">
				<h3><?php echo $hari[1]['day_name'] ?></h3>
				<table border="1">
					<thead>
						<tr>
							<th>No</th>
							<th>Mata Pelajaran</th>
							<th>Waktu</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($selasa as $item) {
							$id_time = $item['schedule_jampel'];
							$time = $this->db->query("SELECT * FROM jam_pelajaran WHERE jam_pelajaran_id = '$id_time' ")->row_array();
						?>
							<tr>
								<td align="center"><?php echo $no++ ?></td>
								<td><?php echo $item['lesson_name'] ?></td>
								<td align="center"><?php echo $time['jam_pelajaran_start'] . ' s/d ' . $time['jam_pelajaran_end'] ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</td>
		</tr>
	</table>
	<table>
		<tr>
			<td width="50%" valign="top">
				<h3><?php echo $hari[2]['day_name'] ?></h3>
				<table border="1">
					<thead>
						<tr>
							<th>No</th>
							<th>Mata Pelajaran</th>
							<th>Waktu</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($rabu as $item) {
							$id_time = $item['schedule_jampel'];
							$time = $this->db->query("SELECT * FROM jam_pelajaran WHERE jam_pelajaran_id = '$id_time' ")->row_array();
						?>
							<tr>
								<td align="center"><?php echo $no++ ?></td>
								<td><?php echo $item['lesson_name'] ?></td>
								<td align="center"><?php echo $time['jam_pelajaran_start'] . ' s/d ' . $time['jam_pelajaran_end'] ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</td>
			<td width="50%" valign="top">
				<h3><?php echo $hari[3]['day_name'] ?></h3>
				<table border="1">
					<thead>
						<tr>
							<th>No</th>
							<th>Mata Pelajaran</th>
							<th>Waktu</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($kamis as $item) {
							$id_time = $item['schedule_jampel'];
							$time = $this->db->query("SELECT * FROM jam_pelajaran WHERE jam_pelajaran_id = '$id_time' ")->row_array();
						?>
							<tr>
								<td align="center"><?php echo $no++ ?></td>
								<td><?php echo $item['lesson_name'] ?></td>
								<td align="center"><?php echo $time['jam_pelajaran_start'] . ' s/d ' . $time['jam_pelajaran_end'] ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</td>
		</tr>
	</table>
	<table>
		<tr>
			<td width="50%" valign="top">
				<h3><?php echo $hari[4]['day_name'] ?></h3>
				<table border="1">
					<thead>
						<tr>
							<th>No</th>
							<th>Mata Pelajaran</th>
							<th>Waktu</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($jumat as $item) {
							$id_time = $item['schedule_jampel'];
							$time = $this->db->query("SELECT * FROM jam_pelajaran WHERE jam_pelajaran_id = '$id_time' ")->row_array();
						?>
							<tr>
								<td align="center"><?php echo $no++ ?></td>
								<td><?php echo $item['lesson_name'] ?></td>
								<td align="center"><?php echo $time['jam_pelajaran_start'] . ' s/d ' . $time['jam_pelajaran_end'] ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</td>
			<td width="50%" valign="top">
				<h3><?php echo $hari[5]['day_name'] ?></h3>
				<table border="1">
					<thead>
						<tr>
							<th>No</th>
							<th>Mata Pelajaran</th>
							<th>Waktu</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($sabtu as $item) {
							$id_time = $item['schedule_jampel'];
							$time = $this->db->query("SELECT * FROM jam_pelajaran WHERE jam_pelajaran_id = '$id_time' ")->row_array();
						?>
							<tr>
								<td align="center"><?php echo $no++ ?></td>
								<td><?php echo $item['lesson_name'] ?></td>
								<td align="center"><?php echo $time['jam_pelajaran_start'] . ' s/d ' . $time['jam_pelajaran_end'] ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</td>
		</tr>
	</table>

</body>

</html>