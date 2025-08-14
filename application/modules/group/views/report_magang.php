
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
									<label>Tanggal Awal</label>
									<input type="date" class="form-control" name="ds" id="dari" value="<?php echo $date_start; ?>" placeholder="Dari .." />
								</div>
							</div>
							<div class="col-md-2">  
								<div class="form-group">
									<label>Tanggal Akhir</label>
									<input type="date" class="form-control" name="de" id="sampai" value="<?php echo $date_end; ?>" placeholder="Sampai .." />
								</div>
							</div>
							<div class="col-md-2">
							<div style="margin-top:25px;">
							<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
							<?php if ($q AND !empty($magang)) { ?>
							<a class="btn btn-danger" target="_blank" href="<?php echo site_url('manage/magang/cetak_data' . '/?' . http_build_query($q)) ?>"><i class="fa fa-file-pdf-o" ></i> Cetak</a>
							<?php } ?>
						    </div>
					        </div>
					</div>
					</form>
					</div>
				</div>
				
				<?php if ($q) { 
						
					?>
				<div class="box box-success">
					<div class="box-body table-responsive">
						<?php if(!empty($magang)){
							
						?>
    					<table class="table table-responsive table-bordered" style="white-space: nowrap;">
							<thead>
								<tr>
									<th width="80px">No</th>
									<th>Nama Lembaga</th>
									<th>Nama Penanggung Jaawab</th>
									<th>Telepon PJ (WA/HP)</th>
									<th>Tanggal Mulai Pelaksanaan</th>
									<th>Tanggal Akhir Pelaksanaan</th>
									<th>Jumlah Peserta</th>
								</tr>
							</thead>
							<tbody>
							<?php 
								$no = 1;
								foreach($magang as $row){
							?>
							<tr>
								<td><?= $no++; ?></td>
								<td><?= $row['nama_lembaga'] ?></td>
								<td><?= $row['pj_magang']; ?></td>
								<td><?= $row['telepon_pj_magang']; ?></td>
								<td><?= $row['tanggal_mulai']; ?></td>
								<td><?= $row['tanggal_akhir']; ?></td>
								<?php $peserta = $this->db->query("SELECT count(peserta_id) AS count_peserta FROM magang_peserta WHERE peserta_magang_id=$row[magang_id]")->row_array(); ?>
								<td><?= $peserta['count_peserta'].' Peserta';  ?></td>
							</tr>
							<?php } 
							}else{
								echo "<center>Tidak Ada Data yang di tampilkan...</center>";
							}
							?>
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
