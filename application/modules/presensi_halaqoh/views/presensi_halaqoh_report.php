
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
			<small>List</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header">
						<?php echo form_open(current_url(), array('method' => 'get')) ?>
						<div class="row">
							<div class="col-md-2">  
								<div class="form-group">
									<label>Tahun Ajaran</label>
									<select class="form-control" name="y" id="years" required="">
										<option value="">-- Pilih Tahun --</option>
										<?php
											for($i=date('Y'); $i>=date('Y')-13; $i-=1){
											?>
											<option <?php echo ($q['y'] == $i) ? 'selected' : '' ?> value='<?= $i ?>'> <?= $i ?> </option>";
											<?php
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-md-2">  
								<div class="form-group">
									<label>Bulan</label>
									<select class="form-control" name="m" id="month_id" required="">
										<option value="">-- Pilih Bulan --</option>
										<?php foreach ($month as $row): ?>
											<option <?php echo (isset($q['m']) AND $q['m'] == $row['month_id']) ? 'selected' : '' ?> value="<?php echo $row['month_id'] ?>"><?php echo $row['month_name'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-md-2">  
								<div class="form-group">
									<label>Kegaitan</label>
									<select class="form-control" name="k" id="kegiatan_id" required="">
										<option value="">-- Pilih Kegiatan --</option>
										<?php foreach ($kegiatan as $row): ?>
											<option <?php echo (isset($q['k']) AND $q['k'] == $row['kegiatan_id']) ? 'selected' : '' ?> value="<?php echo $row['kegiatan_id'] ?>"><?php echo $row['kegiatan_name'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-md-2">  
								<div class="form-group">
									<label>Halaqoh</label>
									<select class="form-control" name="h" id="halaqoh_id" required="">
										<option value="">-- Pilih Halaqoh --</option>
										<?php foreach ($halaqoh as $row): ?>
											<option <?php echo (isset($q['h']) AND $q['h'] == $row['halaqoh_id']) ? 'selected' : '' ?> value="<?php echo $row['halaqoh_id'] ?>"><?php echo $row['halaqoh_name'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-md-2">
							<div style="margin-top:25px;">
							<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
							<?php if ($q AND !empty($student)) { ?>
							<a class="btn btn-success" target="_blank" href="<?php echo site_url('manage/presensi_harian/report_print' . '/?' . http_build_query($q)) ?>"><i class="fa fa-file-excel-o" ></i> Cetak</a>
							<?php } ?>
						    </div>
					        </div>
					</div>
					</form>
					</div>
				</div>
				
				<?php if ($q AND !empty($student)) { ?>
				<div class="box box-success">
					<div class="box-body table-responsive">
    					<table class="table table-responsive table-bordered" style="white-space: nowrap;">
    						    <thead>
    							<tr>
    								<th width='5' rowspan='2'><center>No.</center></th> 
    								<th rowspan='2'><center>NIS</center></th> 
    								<th rowspan='2'><center>Nama</center></th>
    								<th colspan='<?php echo $interval->format("%a")?>'><center><?php echo $namaBulan . ' ' . $namaTahun ?></center></th>
    							</tr>
    							<tr>
    							    
                                <?php
                                    foreach ($daterange as $dt) {
                                        echo '<th>'.$dt->format("d").'</th>';
                                    }
                                ?>
    							</tr>
    							</thead>
    							<tbody>
    							<?php 
    							    $no = 1;
    							    foreach ($student as $row) :
    						    ?>
    						    <tr>
            									<td><?php echo $no++ ?></td>
            									<td><?php echo $row['student_nis']?></td> 
            									<td><?php echo $row['student_full_name']?></td>
                                    <?php
                                        foreach ($daterange as $dt) {
                                            $date = $dt->format("Y-m-d");
                                            $tahun = $q['y'];
                                            $month_id = $q['m'];
                                            $halaqoh_id = $q['h'];
                                            $kegiatan_id = $q['k'];
                                            $student_id = $row['student_id'];
                                            $presensi = $this->db->query("SELECT presensi_halaqoh_status
																			FROM presensi_halaqoh 
																			WHERE presensi_halaqoh_date = '$date' AND presensi_halaqoh_tahun = '$tahun' AND presensi_halaqoh_month_id = '$month_id' AND presensi_halaqoh_kegiatan_id = '$kegiatan_id' AND presensi_halaqoh_halaqoh_id = '$halaqoh_id' AND presensi_halaqoh_student_id = '$student_id'")->row_array();
                                            echo (isset($presensi['presensi_halaqoh_status'])) ? '<td>'.$presensi['presensi_halaqoh_status'].'</td>' : '<td>-</td>';
                                        }
                                    ?>
            								</tr>
    							<?php endforeach ?>
                                </tbody>
    						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<?php 
				}
				?>
				
				
				
				
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>
<script>
    function get_kelas(){
        var id_majors    = $("#majors_id").val();
        //alert(id_jurusan+id_kelas);
        $.ajax({ 
            url: '<?php echo base_url();?>manage/billing/class_searching',
            type: 'POST', 
            data: {
                    'id_majors': id_majors,
            },    
            success: function(msg) {
                    $("#td_kelas").html(msg);
            },
			error: function(msg){
					alert('msg');
			}
            
        });
    }
</script>
<script>
	$(document).ready(function() {
		$("#selectallhadir").click(function() {
            if ($(this).is(':checked'))
                $(".hadir").attr("checked", "checked");
            else
                $(".hadir").removeAttr("checked");
        });
		$("#selectallsakit").click(function() {
            if ($(this).is(':checked'))
                $(".sakit").attr("checked", "checked");
            else
                $(".sakit").removeAttr("checked");
        });
		$("#selectallizin").click(function() {
            if ($(this).is(':checked'))
                $(".izin").attr("checked", "checked");
            else
                $(".izin").removeAttr("checked");
        });
		$("#selectallalpha").click(function() {
            if ($(this).is(':checked'))
                $(".alpha").attr("checked", "checked");
            else
                $(".alpha").removeAttr("checked");
        });
	});
</script>