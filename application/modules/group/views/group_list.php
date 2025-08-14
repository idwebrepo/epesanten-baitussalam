<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
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
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<a href="<?php echo site_url('manage/group/add') ?>" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Tambah</a>
						<!-- <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addSatuan"><i class="fa fa-plus"></i> Tambah</button> -->
						
						<!-- <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get')) ?>
					    <div class="box-body table-responsive">
							<table>
							    <tr>
							        <td>     
    								<select style="width: 200px;" id="m" name="m" class="form-control" required>
    								    <option value="">--- Pilih Unit Sekolah ---</option>
    								    <?php if($this->session->userdata('umajorsid') == '0') { ?>
    								    <option value="all" <?php echo (isset($s['m']) && $s['m']=='all') ? 'selected' : '' ?> >Semua Unit</option>
    								    <?php } ?>
            						    <?php foreach($majors as $row){?>
            						        <option value="<?php echo $row['majors_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
            						    <?php } ?>
    								</select>
							        </td>
							        <td>
							            &nbsp&nbsp
							        </td>
							        <td>
							        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>    
							        </td>
							    </tr>
							</table>
							</div>
							<?php echo form_close(); ?> -->
					</div>
					<!-- /.box-header -->
					<div class="box-body table-responsive">
						<table id="dtable" class="table table-hover">
						    <thead>
							<tr>
								<th>No</th>
								<th>Nama Grup</th>
								<th>Nama Kegiatan</th>
								<th>Tanggal Kegiatan</th>
								<th>Tempat Kegiatan </th>
								<th>Keterangan </th>
								<th>Jumlah Peserta </th>
								<th width="50px">Aksi</th>
							</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($group)) {
									$i = 1;
									foreach ($group as $row):
										?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $row['group_name']; ?></td>
											<td><?php echo $row['kegiatan_name']; ?></td>
											<td><?php echo $row['group_date']; ?></td>
											<td><?php echo $row['group_tempat']; ?></td>
											<td><?php echo $row['group_keterangan']; ?></td>
											<td><?php 
    											$peserta = $this->db->query("SELECT count(halaqoh_group_id) AS count_peserta FROM halaqoh_group WHERE halaqoh_group_group_id=$row[group_id]")->row_array();
												echo $peserta['count_peserta'].' Peserta';  ?></td>
											<td>
												<?php
												$check = $this->db->query("SELECT COUNT(*) AS `numrows` FROM `presensi_halaqoh` WHERE `presensi_halaqoh_date` = '$row[group_date]' AND `presensi_halaqoh_group_id` = '$row[group_id]'")->row_array();

												// echo $check['numrows'];
												if($check['numrows']==0){
													?>
												<a href="<?php echo site_url('manage/group/view/' . $row['group_id']) ?>" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Detail Data Peserta"><i class="fa fa-eye"></i></a>
													<?php
												}else{
													?>
													<a href="<?php echo site_url('manage/group/report/' . $row['group_id']) ?>" class="btn btn-xs btn-success" data-toggle="tooltip" title="Report Data Peserta"><i class="fa fa-bars"></i></a>
													<?php
												}
												?>

												<?php if ($this->session->userdata('uroleid') == '1'){ ?>
													<a href="<?php echo site_url('manage/group/edit/' . $row['group_id']) ?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
												<?php } else { ?>
													
												<?php }?>
												<a href="#delModal<?php echo $row['group_id']; ?>" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i></a>
											</td>	
										</tr>
										<!-- Hapus Arsip -->
										<div class="modal modal-default fade" id="delModal<?php echo $row['group_id']; ?>">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span></button>
															<h3 class="modal-title"><span class="fa fa-warning"></span> Konfirmasi penghapusan</h3>
														</div>
														<div class="modal-body">
															<p>Apakah anda yakin akan menghapus data ini?</p>
														</div>
														<div class="modal-footer">
															<?php echo form_open('manage/group/delete/' . $row['group_id']); ?>
															<input type="hidden" name="delName" value="<?php echo $row['nama_arsip']; ?>">
															<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
															<button type="submit" class="btn btn-danger"><span class="fa fa-check"></span> Hapus</button>
															<?php echo form_close(); ?>
														</div>
													</div>
													<!-- /.modal-content -->
												</div>
												<!-- /.modal-dialog -->
											</div>
											<?php
											$i++;
										endforeach;
									} else {
										?>
										<tr id="row">
											<td colspan="7" align="center">Data Kosong</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
							<!-- /.box-body -->
						</div>
						<div>
							<?php echo $this->pagination->create_links(); ?>
						</div>
						<!-- /.box -->
					</div>
				</div>
			</section>
			<!-- /.content -->
		</div>