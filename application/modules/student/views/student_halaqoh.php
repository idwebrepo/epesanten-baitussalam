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
				<div class="alert alert-danger">
					Warning!... !
					Jika ada Santri yang telah dibuatkan tagihan dan dipindah halaqohnya melalui halaman ini, maka tagihan tetap ada di halaqoh sebelumnya!
				</div>
			</div>
		</div>
		<!-- /.box-header -->
		<div class="row">
			<div class="col-md-9">
				<div class="box">
					<div class="box-body">
						<?php echo form_open(current_url(), array('method' => 'get')) ?>
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon alert-info">Halaqoh</div>
								<div id="">
								<select class="form-control" name="pr" onchange="this.form.submit()">
									<option value="">-- Pilih Halaqoh  --</option>
									<?php foreach ($halaqoh as $row): ?>
										<option <?php echo (isset($f['pr']) AND $f['pr'] == $row['halaqoh_id']) ? 'selected' : '' ?> value="<?php echo $row['halaqoh_id'] ?>"><?php echo $row['halaqoh_name'] ?></option>
									<?php endforeach; ?>
								</select>
								</div>
							</div>
						</div>
						<?php echo form_close() ?>
						<form action="<?php echo site_url('manage/student/multiple'); ?>" method="post">
						<input type="hidden" name="action" value="move_halaqoh">
						<table class="table table-hover table-bordered table-responsive">
								<tr>
									<th><input type="checkbox" id="selectall" value="checkbox" name="checkbox"></th> 
									<th>No</th>
									<th>NIS</th>
									<th>Nama</th>
									<th>Kelas</th>
									<th>Halaqoh Saat ini</th>
								</tr>
								<tbody>
									<?php if($this->input->get(NULL)) { ?>
										<?php
										if (!empty($student)) {
											$i = 1;
											foreach ($student as $row):
												?>
												<input type="hidden" name="student_id" id="student_id" value="<?= $row['student_id'] ?>">
												<tr style="<?php echo ($row['student_status']==0) ? 'color:#00E640' : '' ?>">
													<td>
														<input type="checkbox" class="<?php echo ($row['student_status']==0) ? NULL : 'checkbox' ?>" <?php echo ($row['student_status']==0) ? 'disabled' : NULL ?> name="msg[]" value="<?= $row['relation_id'] ?>">
													</td>
													<td><?php echo $i; ?></td>
													<td><?php echo $row['student_nis']; ?></td>
													<td><?php echo $row['student_full_name']; ?></td>	
													<td><?php echo $row['class_name']; ?></td>	
													<td><?php echo $row['halaqoh_name']; ?></td>	
	
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

					<div class="col-md-3">
						<div class="panel panel-info">
							<div class="panel-body">
								<div id="">
									<?php
										if(isset($f['pr'])){
											?>
											<select class="form-control" name="halaqoh_id" id="halaqoh_id">
												<option value="">-- Pilih Halaqoh  --</option>
												<?php foreach ($halaqoh as $row): ?>
													<option value="<?php echo $row['halaqoh_id'] ?>"><?php echo $row['halaqoh_name'] ?></option>
												<?php endforeach; ?>
											</select>
											<?php
										}
									?>
								</div>
								<br>
								<button class="btn btn-danger btn-block" type="submit">Proses Pindah Halaqoh</button>
							</div>
						</div>
					</div>
                    
				</form>
				</div>
			</section>
			<!-- /.content -->
		</div>

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
        	function get_halaqoh(){
        	    var id_majors    = $("#m").val();
                //alert(id_jurusan+id_kelas);
                $.ajax({ 
                    url: '<?php echo base_url();?>manage/student/cari_halaqoh',
                    type: 'POST', 
                    data: {
                            'id_majors': id_majors,
                    },    
                    success: function(msg) {
                            $("#div_halaqoh").html(msg);
                    },
        			error: function(msg){
        					alert('msg');
        			}
                    
                });
        	}
        	
        	function get_ke_halaqoh(){
        	    var id_majors    = $("#m_id").val();
                //alert(id_jurusan+id_kelas);
                $.ajax({ 
                    url: '<?php echo base_url();?>manage/student/cari_ke_halaqoh',
                    type: 'POST', 
                    data: {
                            'id_majors': id_majors,
                    },    
                    success: function(msg) {
                            $("#ke_halaqoh").html(msg);
                    },
        			error: function(msg){
        					alert('msg');
        			}
                    
                });
        	}
		</script>
