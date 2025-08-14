
<html>
<head>
	<title><?php echo 'Laporan per Jenis Anggaran (Kas Tunai) per Tanggal '.pretty_date($date_start, 'd/m/Y', FALSE).' Sampai '.pretty_date($date_start, 'd/m/Y', FALSE) ?></title>
</head>
<body>
    <table style='border-bottom: 1px solid #999999; padding-bottom: 10px; width: 275mm;'>
        <tr valign='top'>
            <td style='width: 190mm;' valign='middle'>
                <div style='font-weight: bold; padding-bottom: 5px; font-size: 14pt;'><?php echo $setting_school['setting_value'] ?>
                </div>
                <span style='font-size: 8pt;'><?php echo $setting_address['setting_value'] ?>, Telp. 
        <?php echo $setting_phone['setting_value'] ?></span>
            </td>
        </tr>
    </table>
    <br>
    <table style='width: 275mm;'>
        <tr>
            <td style='width: 20mm; font-size: 8pt; text-align: center' valign='center'></td>
            <td style='width: 150mm; font-size: 8pt; text-align: center' valign='top' align='center'><b><?php echo 'Laporan Data Magang' ?></b></td>
            <td style='width: 20mm; font-size: 8pt;' align='right'></td>
        </tr>
        <tr>
            <td style='width: 20mm; font-size: 8pt; text-align: center' valign='center'></td>
            <td style='width: 150mm; font-size: 8pt; text-align: center' valign='top' align='center'>Tahun Periode <?php echo $period['period_start'].'/'.$period['period_end'] ?></td>
            <td style='width: 20mm; font-size: 8pt;' align='right'></td>
        </tr>
    </table>
    <br>
    <table cellpadding="0" cellspacing="0" style="width: 275px;">
            <tr valign='top'>
                <th align="center" style="font-size: 10pt; width: 50px; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">No.</th> 
                <th align="center" style="font-size: 10pt; width: 120px; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Nama Lembaga</th>
                <th align="center" style="font-size: 10pt; width: 150px; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Nama Penanggung Jawab</th>
                <th align="center" style="font-size: 10pt; width: 100px; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Telepon PJ (WA/HP)</th>
                <th align="center" style="font-size: 10pt; width: 100px; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Tanggal Mulai Pelaksanaan</th>
                <th align="center" style="font-size: 10pt; width: 100px; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Tanggal Akhir Pelaksanaan</th>
                <th align="center" style="font-size: 10pt; width: 100px; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Jumlah Peserta</th>
            </tr>
            <?php 
                $no = 1;
                foreach($magang as $row){
            ?>
            <tr>
                <td style="padding: 1px; font-size: 9pt; text-align: center; solid #999999;"><?= $no++; ?></td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; solid #999999;"><?= $row['nama_lembaga'] ?></td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; solid #999999;"><?= $row['pj_magang']; ?></td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; solid #999999;"><?= $row['telepon_pj_magang']; ?></td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; solid #999999;"><?= $row['tanggal_mulai']; ?></td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; solid #999999;"><?= $row['tanggal_akhir']; ?></td>
                <?php $peserta = $this->db->query("SELECT count(peserta_id) AS count_peserta FROM magang_peserta WHERE peserta_magang_id=$row[magang_id]")->row_array(); ?>
                <td style="padding: 1px; font-size: 9pt; text-align: center; solid #999999;"><?= $peserta['count_peserta'].' Peserta';  ?></td>
            </tr>
            <?php } ?>
    </table>
</body>