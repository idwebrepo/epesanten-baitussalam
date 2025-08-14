
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
			<small>List</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('pegawai') ?>"><i class="fa fa-th"></i> Home</a></li>
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
							<div class="col-md-3">  
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
							<div class="col-md-3">  
								<div class="form-group">
									<label>Kegiatan Halaqoh</label>
									<select class="form-control" name="k" id="kegiatan_id" required="">
										<option value="">-- Pilih Kegiatan --</option>
										<?php foreach ($kegiatan as $row): ?>
											<option <?php echo (isset($q['k']) AND $q['k'] == $row['kegiatan_id']) ? 'selected' : '' ?> value="<?php echo $row['kegiatan_id'] ?>"><?php echo $row['kegiatan_name'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-md-3">  
								<div class="form-group">
									<label>Tanggal Kegiatan</label>
									<div class="input-group date" data-date-format="yyyy-mm-dd">
										<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
										<input class="form-control" type="text" name="d" value="<?php echo $q['d']; ?>" readonly="readonly" placeholder="Pilih Tanggal Kegiatan">
									</div>
								</div>
							</div>
							<div class="col-md-3">
							<div style="margin-top:25px;">
							<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
						    </div>
					        </div>
					</div>
					</form>
					</div>
				</div>
				
				<?php if ($q) { ?>
				
				<?php if ($check == 0) { ?>
				
        				<?php if (!empty($student)) { ?>
        				<div class="box box-success">
        					<div class="box-body table-responsive">
            					<?php echo form_open('pegawai/presensi_halaqoh/add_glob', array('method'=>'post')); ?>
            					<input type="hidden" name="halaqoh_id" value="<?php echo $q['h'] ?>" required ></td>
            					<input type="hidden" name="period_id" value="<?= $period['period_id'] ?>" required></td>
            					<input type="hidden" name="tahun" value="<?= date('Y') ?>" required></td>
            					<input type="hidden" name="presensi_date" value="<?php echo $q['d'] ?>" required></td>
            					<input type="hidden" name="kegiatan_id" value="<?php echo $q['k'] ?>" required></td>
            					<input type="hidden" name="employee_id" value="<?php echo $dt_halaqoh['employee_id'] ?>" required></td>
								<table class="table table-responsive" style="white-space: nowrap;">
									<tr>
										<td style="width: 150px;"><label for="">Musyrif Halaqoh</label></td>
										<td style="width: 50px;">:</td>
											<td><?= $dt_halaqoh['employee_name'] ?></td>
									</tr>
									<tr>
										<td><label for="">Tahun </label></td>
										<td>:</td>
										<td><?= date('Y') ?></td>
									</tr>
									<tr>
										<td><label for="">Materi Halaqoh</label></td>
										<td>:</td>
										<td><input class="form-control" type="text" name="materi_halaqoh" placeholder="Isi dengan Materi Yang akan diberikan" required></td>
									</tr>
								</table>
            					<table class="table table-responsive table-bordered" style="white-space: nowrap;">
            						    <thead>
            							<tr>
            								<th width='5' rowspan='2'><center>No.</center></th> 
            								<th rowspan='2'><center>NIS</center></th> 
            								<th rowspan='2'><center>Nama</center></th>
            								<th colspan='4'><center>Status</center></th>
            							</tr>
            							<tr>
            							    <th>Hadir <input name="selectAll" id="selectallhadir" type="radio" value="Y"></th>
            							    <th>Sakit <input name="selectAll" id="selectallsakit" type="radio" value="Y"></th>
            							    <th>Izin <input name="selectAll" id="selectallizin" type="radio" value="Y"></th>
            							    <th>Alpha <input name="selectAll" id="selectallalpha" type="radio" value="Y"></th>
            							</tr>
            							</thead>
            							<tbody>
            							<?php 
            							    $no = 1;
            							    foreach ($student as $row) :
            						    ?>
            								<tr>
            									<td><?php echo $no++ ?><input type="hidden" name="student_id[]" value="<?php echo $row['student_id']?>"></td>
            									<td><?php echo $row['student_nis']?></td> 
            									<td><?php echo $row['student_full_name']?></td>
            									<td>
            										<input type="radio" name="presensi_halaqoh_status<?php echo $row['student_id']?>[]" class="hadir" value="H" requied>
            									</td>
            									<td>
            										<input type="radio" name="presensi_halaqoh_status<?php echo $row['student_id']?>[]" class="sakit" value="S" requied>
            									</td>
            									<td>
            										<input type="radio" name="presensi_halaqoh_status<?php echo $row['student_id']?>[]" class="izin" value="I" requied>
            									</td>
            									<td>
            										<input type="radio" name="presensi_halaqoh_status<?php echo $row['student_id']?>[]" class="alpha" value="A" requied></td>
            								</tr>
            							<?php endforeach ?>
                                        </tbody>
            						</table>
            						<div class="pull-right">
            						    <button type="submit" class="btn btn-success">Simpan</button>
            						</div>
            					<?php echo form_close(); ?>
        					</div>
        					<!-- /.box-body -->
        				</div>
        				<?php 
        				    
        				}else{ ?>
							<div class="box box-success">
								<div class="box-body table-responsive">
									<center>
										<h3>Data siswa tidak ditemukan</h3>
									</center>
								</div>
							</div>
						<?php }
    				
    				} else { ?>
				
				<div class="box box-success">
					<div class="box-body table-responsive">
					    <center>
					        <h3>Presensi Halaqoh Semua Santri Sudah Didata</h3>
					    </center>
				    </div>
				</div>
				
				<?php
				    }
				}
				?>
				
				
				
				
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>
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
		
<script>

  function get_form(){
    var student_id = $('#msg:checked');
    if(student_id.length > 0)
    {
        var student_id_value = [];
        $(student_id).each(function(){
            student_id_value.push($(this).val());
        });

        $.ajax({
            url: '<?php echo base_url();?>pegawai/billing/get_form/',
            method:"POST",
            data: {
                    student_id : student_id_value,
            },
            success: function(msg){
                    $("#fbatch").html(msg);
            },
    		error: function(msg){
    				alert('msg');
    		}
        });
    }
    else
    {
        alert("Belum ada Santri yang dipilih");
    }
  }
  
</script>