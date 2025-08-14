<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<!-- <div class="alert alert-danger">
					Warning!... !
					Halaman ini digunakan untuk merubah status siswa menjadi lulus. Pastikan siswa yang di proses adalah siswa kelas akhir.
				</div> -->
			</div>
		</div>
		<!-- /.box-header -->
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-body">
						<?php echo form_open(current_url(), array('method' => 'get')) ?>
						<div class="form-group">
							<div class="input-group">
							    <div class="input-group-addon alert-success">Unit</div>
								<select class="form-control" name="m" id="m" onchange="get_kelas()">
									<option value="">-- Pilih Unit Sekolah  --</option>
									<?php foreach ($majors as $row): ?>
										<option <?php echo (isset($f['m']) AND $f['m'] == $row['majors_id']) ? 'selected' : '' ?> value="<?php echo $row['majors_id'] ?>"><?php echo $row['majors_short_name'] ?></option>
									<?php endforeach; ?>
								</select>
								<div class="input-group-addon alert-info">Kelas</div>
								<div id="div_kelas">
								<select class="form-control" name="pr" id="pr" onchange="this.form.submit()">
									<option value="">-- Pilih Kelas  --</option>
									<?php if(isset($f['m'])){ ?>
									<?php foreach ($class as $row): ?>
										<option <?php echo (isset($f['pr']) AND $f['pr'] == $row['class_id']) ? 'selected' : '' ?> value="<?php echo $row['class_id'] ?>"><?php echo $row['class_name'] ?></option>
									<?php endforeach; ?>
									<?php } ?>
								</select>
								<input type="hidden" name="hl" value="<?= $f['hl'] ?>">
								</div>
							</div>
						</div>
						<?php echo form_close() ?>
						<table class="table table-hover table-bordered table-responsive">
							<form action="<?php echo site_url('manage/student/multiple'); ?>" method="post">
								<input type="hidden" name="action" value="setting_halaqoh">
								<input type="hidden" name="majors_id" id="majors_id" value="<?= $f['m'] ?>">
								<input type="hidden" name="class_id" id="class_id" value="<?= $f['pr'] ?>">
								<tr>
									<th><input type="checkbox" id="selectall" value="checkbox" name="checkbox"></th> 
									<th>No</th>
									<th>NIS</th>
									<th>Nama</th>
									<th>Status Halaqoh</th>
								</tr>
								<tbody>
									<?php if($this->input->get(NULL)) { ?>
										<?php
										if (!empty($notpass)) {
											$i = 1;
											foreach ($notpass as $row):
												?>
												<tr style="<?php echo ($row['student_status']==0) ? 'color:#00E640' : '' ?>">
													<td><input type="checkbox" class="<?php echo ($row['student_status']==0) ? NULL : 'checkbox' ?>" <?php echo ($row['student_status']==0) ? 'disabled' : NULL ?> name="msg[]" value="<?php echo $row['student_id']; ?>"></td>
													<td><?php echo $i; ?></td>
													<td><?php echo $row['student_nis']; ?></td>
													<td><?php echo $row['student_full_name']; ?></td>
													<td>
														<?php 
															$data = $this->db->query("SELECT halaqoh_name 
																						FROM halaqoh a 
																						LEFT JOIN halaqoh_relation b ON a.halaqoh_id=b.relation_halaqoh_id 
																						WHERE b.relation_student_id = '$row[student_id]'")->result_array();
															foreach ($data as $string) {
																?>
																<label class="label <?php echo ($row['student_halaqoh']==0) ? 'label-warning' : 'label-success' ?>">
																	<?php echo ($row['student_halaqoh']==0) ? 'Halaqoh Null' : $string['halaqoh_name'];?><br>
																</label>
																<?php
															}
														?>
													</td>
												</tr>
												<?php
												$i++;
											endforeach;
										} else {
											?>
											<tr id="row">
												<td colspan="5" align="center">Data Kosong</td>
											</tr>
											<?php } ?>
											<?php } else {
											?>
											<tr id="row">
												<td colspan="5" align="center">Data Kosong</td>
											</tr>
											<?php } ?>
										</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="col-md-2">
						<div class="panel panel-info">
							<div class="panel-body">
								
								<select class="form-control multiple-select" name="halaqoh_id[]" multiple="multiple" placeholder="Pilih Halaqoh">
								<?php foreach ($halaqoh as $row): ?>
										<option value="<?php echo $row['halaqoh_id'] ?>"><?php echo $row['halaqoh_name'] ?></option>
									<?php endforeach; ?>
								</select>
								<br><br>
								<button class="btn btn-danger btn-block" type="submit"><span class="glyphicon glyphicon glyphicon-chevron-right"></span> Proses Halaqoh</button>
								<br>
							</form>
								<!-- <button class="btn btn-danger btn-block" onclick="$('#kembali').submit();"><span class="glyphicon glyphicon glyphicon-chevron-left"></span> Batal Lulus</button> -->
							</div>
						</div>
					</div>
					<div class="col-md-5">
						<div class="box">
							<div class="box-body">
								<h4>Data Halaqoh</h4>
								<table class="table table-hover table-bordered table-responsive">
									<form action="<?php echo site_url('manage/student/multiple'); ?>" method="post" id="kembali">
										<input type="hidden" name="action" value="notpass">
										<tr>
											<th><input type="checkbox" id="selectall2" value="checkbox" name="checkbox"></th> 
											<th>No</th>
											<th>NIS</th>
											<th>Nama</th>
											<th>Status Halaqoh</th>
										</tr>
										<tbody>
											<?php
											if (!empty($pass)) {
												$i = 1;
												foreach ($pass as $row):
													?>
													<tr style="color:#00E640">
														<td><input type="checkbox" class="checkbox" name="msg[]" value="<?php echo $row['student_id']; ?>"></td>
														<td><?php echo $i; ?></td>
														<td><?php echo $row['student_nis']; ?></td>
														<td><?php echo $row['student_full_name']; ?></td>
														<td>
															<?php 
																$data = $this->db->query("SELECT halaqoh_name 
																							FROM halaqoh a 
																							LEFT JOIN halaqoh_relation b ON a.halaqoh_id=b.relation_halaqoh_id 
																							WHERE b.relation_student_id = '$row[student_id]'")->result_array();
																foreach ($data as $string) {
																	?>
																	<label class="label <?php echo ($row['student_halaqoh']==0) ? 'label-warning' : 'label-success' ?>">
																		<?php echo ($row['student_halaqoh']==0) ? 'Halaqoh Null' : $string['halaqoh_name'];?><br>
																	</label>
																	<?php
																}
															?>
														</td>	
													</tr>
													<?php
													$i++;
												endforeach;
											} else {
												?>
												<tr id="row">
													<td colspan="6" align="center">Data Kosong</td>
												</tr>
												<?php } ?>
											</tbody>
										</form>
									</table>
								</div>
							</form>
						</div>
					</div>

				</div>

			</section>
			<!-- /.content -->
		</div>

		<script>
			$(document).ready(function() {
				$('.multiple-select').select2();
			});
		</script>

		<script>
			$(document).ready(function() {
				$("#selectall").change(function() {
					$(".checkbox").prop('checked', $(this).prop("checked"));
				});
			});
			$(document).ready(function() {
				$("#selectall2").change(function() {
					$(".checkbox").prop('checked', $(this).prop("checked"));
				});
			});
		</script>
		
		<script>
        	function get_kelas(){
        	    var id_majors    = $("#m").val();
                //alert(id_jurusan+id_kelas);
                $.ajax({ 
                    url: '<?php echo base_url();?>manage/student/cari_kelas',
                    type: 'POST', 
                    data: {
                            'id_majors': id_majors,
                    },    
                    success: function(msg) {
                            $("#div_kelas").html(msg);
                    },
        			error: function(msg){
        					alert('msg');
        			}
                    
                });
        	}
		</script>
