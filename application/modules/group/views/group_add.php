<?php

if (isset($group)) {

	$inputKegiatanIDValue 	= $group['group_kegiatan_id'];
	$inputNameValue 		= $group['group_name'];
	$inputDateValue 		= $group['group_date'];
	$inputTempatValue 		= $group['group_tempat'];
	$inputKeteranganValue	= $group['group_keterangan'];
	
} else {
		
	$inputKegiatanIDValue 	= set_value('group_kegiatan_id');
	$inputNameValue 		= set_value('group_name');
	$inputDateValue 		= set_value('group_date');
	$inputTempatValue 		= set_value('group_tempat');
	$inputKeteranganValue	= set_value('group_keterangan');
}
?>

<div class="content-wrapper"> 
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li><a href="<?php echo site_url('manage/group') ?>">Data Magang</a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>

	<!-- Main content -->
	<!-- Main content -->
	<section class="content">
		<?php echo form_open_multipart(current_url()); ?>
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-md-9">
				<div class="box box-primary">
					<!-- /.box-header -->
					<div class="box-body">
						<?php echo validation_errors(); ?>
						<?php if (isset($group)) { ?>
							<input type="hidden" name="group_id" value="<?php echo $group['group_id']; ?>">
							<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">							
						<?php } ?>
						
						<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						<div class="form-group">
							<label>Nama group<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="group_name" type="text" class="form-control" value="<?php echo $inputNameValue ?>" placeholder="Nama group kegiatan">
						</div>
						<div class="form-group">
							<label>Kegiatan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select required="" name="group_kegiatan_id" class="form-control">
							    <option value="">-Pilih Kegiatan-</option>
							    <?php foreach($kegiatan as $row){?>
							        <option value="<?php echo $row['kegiatan_id']; ?>" <?php echo ($inputKegiatanIDValue == $row['kegiatan_id']) ? 'selected' : '' ?>><?php echo $row['kegiatan_name'] ?></option>
							    <?php } ?>
							</select>
						</div>
						<div class="form-group">
							<label>Tanggal Kegiatan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="group_date" type="date" class="form-control" value="<?php echo $inputDateValue ?>" placeholder="Tanggal Kegiatan">
						</div>
						<div class="form-group">
							<label>Tempat Kegiatan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="group_tempat" type="text" class="form-control" value="<?php echo $inputTempatValue ?>" placeholder="Tempat Kegiatan">
						</div>
						<div class="form-group">
							<label>Keterangan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="group_keterangan" type="text" class="form-control" value="<?php echo $inputKeteranganValue ?>" placeholder="Keterangan">
						</div>						
						<p class="text-muted">*) Kolom wajib diisi.</p>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
			<div class="col-md-3">
				<div class="box box-primary">
					<!-- /.box-header -->
					<div class="box-body">
						<button type="submit" class="btn btn-block btn-success">Simpan</button>
						<a href="<?php echo site_url('manage/group'); ?>" class="btn btn-block btn-info">Batal</a>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
		</div>
		
		<?php echo form_close(); ?>
		<!-- /.row -->
	</section>
</div>