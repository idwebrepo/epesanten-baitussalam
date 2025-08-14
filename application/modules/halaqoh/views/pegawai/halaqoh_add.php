<?php

if (isset($halaqoh)) {

	$inputHalaqohValue = $halaqoh['halaqoh_name'];
	$inputMusyrifValue = $halaqoh['halaqoh_employee_id'];
	
} else {
	
	$inputHalaqohValue = set_value('halaqoh_name');
	$inputMusyrifValue = set_value('halaqoh_employee_id');
}
?>

<div class="content-wrapper"> 
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('pegawai') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li><a href="<?php echo site_url('pegawai/halaqoh') ?>">Jurusan</a></li>
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
						<?php if (isset($halaqoh)) { ?>
						<input type="hidden" name="halaqoh_id" value="<?php echo $halaqoh['halaqoh_id']; ?>">
						<?php } ?>

						<div class="form-group">
							<label>Nama Halaqoh <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="halaqoh_name" type="text" class="form-control" value="<?php echo $inputHalaqohValue ?>" placeholder="Isi Nama Halaqoh">
						</div>
						
						<label>Musyrif Halaqoh</label>
								<select required="" name="halaqoh_employee_id" class="form-control">
									<option value="" selected >-Pilih Musyrif Halaqoh-</option>
									<?php foreach($employee as $row){?>
										<option value="<?php echo $row['employee_id'] ?> <?php echo ($inputMusyrifValue != NULL ? 'selected' : '' ) ?>"><?php echo $row['employee_name'] ?></option>
									<?php } ?>
								</select>
						
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
						<a href="<?php echo site_url('pegawai/halaqoh'); ?>" class="btn btn-block btn-info">Batal</a>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
		<!-- /.row -->
	</section>
</div>