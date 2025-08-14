<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					<div class="h3 text-dark">
						Harga Per Malam :
					</div>
					<div class="h1 text-dark" style="font-weight: bold;">
						<?= 'Rp ' . number_format($homestay['homestay_price'], 0, ',', '.') ?>
					</div>
					<button class="btn btn-primary btn-block pull-right" data-toggle="modal" data-target="#bookNow">
						PESAN SEKARANG
					</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="addDonasi" role="dialog">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Data Donasi Anda</h4>
				</div>
				<?php echo form_open('donasi/create_donasi', array('method' => 'post')); ?>
				<div class="modal-body">
					<div class="row">
						<input type="hidden" name="program_program_id" class="form-control" value="<?= $row['program_id'] ?>">
						<input type="hidden" name="program_name" class="form-control" value="<?= $row['program_name'] ?>">
						<div class="col-md-12">
							<div class="form-group">
								<label for="">Nama Anda <small>*</small></label>
								<input type="text" required="" name="donasi_name" class="form-control" placeholder="Masukkan Nama Anda">
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" value="Hamba Allah" name="as_anonim" id="as_anonim">
								<label class="form-check-label" for="as_anonim" style="color:lightslategray">
									Donasi Sebagai Hamba Allah
								</label>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group"><label for="">No. Whatsapp <small>*</small></label>
								<input type="text" required="" name="donasi_hp" class="form-control" placeholder="Masukkan No. Whatsapp Anda">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group"><label for="">Email</label>
								<input type="email" name="donasi_email" class="form-control" placeholder="Masukkan No. Email Anda">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group"><label for="">Nominal Donasi <small>*</small></label>
								<input type="number" name="donasi_nominal" class="form-control" placeholder="Masukkan Nominal Donasi">
							</div>
							<p>*) Nominal Donasi Minimal Rp 10.000</p>
						</div>

					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</section>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>