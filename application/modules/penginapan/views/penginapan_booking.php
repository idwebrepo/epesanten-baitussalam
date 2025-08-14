<section class="content">
	<div class="row">
		<div class="col-md-8 ">
			<div class="card">
				<div class="card-body">
					<div class="h3 text-dark font-weight-bold" id="namahomestay">
						<?= $homestay['homestay_name'] ?>
					</div>
					<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
						<div class="carousel-inner">
							<div id="gambarkamar">
							</div>
						</div>
						<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
							<span class="carousel-control-prev-icon" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</a>
						<a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
							<span class="carousel-control-next-icon" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						</a>
					</div>
					<br>
					<div class=" h5 font-weight-light text-dark" id="peta">
						<div class="row">
							<div class="col-md-12">
								<div id="map" style="width: 100%; height: 300px; margin-top: 30px;"></div>
							</div>
						</div>
					</div>
					<br>

					<div class=" h5 font-weight-light text-dark" id="deskripsi">
						<p>
							<?= $homestay['homestay_desc'] ?>
						</p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
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
	<div class="modal fade" id="bookNow" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Pesan Kelas Pondok</h4>

					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<?php echo form_open('penginapan/booking', array('method' => 'post')); ?>
				<div class="modal-body">
					<div class="row">
						<input type="hidden" name="homestay_id" class="form-control" value="<?= $homestay['homestay_id'] ?>">
						<input type="hidden" name="homestay_name" class="form-control" value="<?= $homestay['homestay_name'] ?>">
						<input type="hidden" name="homestay_price" id="homestay_price" class="form-control" value="<?= $homestay['homestay_price'] ?>">
						<div class="col-md-12">
							<div class="form-group">
								<label for="">Nama Anda <small>*</small></label>
								<input type="text" required="" name="reservasi_name" class="form-control" placeholder="Masukkan Nama Anda">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group"><label for="">No. Whatsapp <small>*</small></label>
								<input type="text" required="" name="reservasi_hp" class="form-control" placeholder="Masukkan No. Whatsapp Anda">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="text-dark">Tanggal Chekin <small>*</small></label>
								<input type="text" name="reservasi_checkin" class="form form-control" id="reservasi_checkin" onchange="calculate()">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="text-dark">Tanggal Chekout <small>*</small></label>
								<input type="text" name="reservasi_checkout" class="form form-control" id="reservasi_checkout" onchange="calculate()">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="text-dark">Total Transaksi <small>*</small></label>
								<input type="number" name="reservasi_total" id="reservasi_total" value="<?= $homestay['homestay_price'] ?>" class="form form-control" readonly>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Pesan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</section>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
	var url = '<?= base_url() ?>'
	var arrDetailMap = []
	$.getJSON(url + 'penginapan/get_data_json/?r=' + '<?= $id ?>',
		function(res) {
			res.datagalery.map((x, index) => {
				$('#gambarkamar').append(
					'<div class="carousel-item ' +
					(index == 0 ? 'active' : '') +
					'"><img class="d-block w-100" src="<?= base_url() ?>uploads/gallery/' + x.url + '"></div>'
				)
			})

			res.datamap.map((x) => {
				let datamapnya = {
					latitude: x.latitude,
					longitude: x.longitude,
				}
				arrDetailMap.push(datamapnya)
			})
			tampilkanMap()
		}
	)

	const tampilkanMap = () => {
		let latitude = 0;
		let longitude = 0;
		let point = [];
		let center = [];

		arrDetailMap.map((x, i) => {
			latitude = parseFloat(x.latitude);
			longitude = parseFloat(x.longitude);
		});

		point.push(latitude);
		point.push(longitude);

		for (let i = 0; i < point.length; i++) {
			center.push(point[i].toFixed(3));
		}

		if (isNaN(latitude) == false && isNaN(longitude) == false) {

			var map = L.map('map').setView(center, 14);

			var tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
				maxZoom: 19,
				attribution: '© OpenStreetMap',
				id: 'mapbox/streets-v11',
				tileSize: 512,
				zoomOffset: -1
			}).addTo(map);

			var marker = L.marker(point).bindPopup(namahomestay).addTo(map);
			L.marker([-6.973283, 103.630298]).bindPopup('Pantai Widuri').addTo(map);
		}
	}
</script>

<script>
	/* Fungsi formatRupiah */
	function formatRupiah(angka, prefix) {
		var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split = number_string.split(','),
			sisa = split[0].length % 3,
			rupiah = split[0].substr(0, sisa),
			ribuan = split[0].substr(sisa).match(/\d{3}/gi);

		// tambahkan titik jika yang di input sudah menjadi angka ribuan
		if (ribuan) {
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}

		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
		return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
	}
</script>



<script type="text/javascript">
	var dates = <?php echo json_encode($dates); ?>;

	function DisableDates(date) {
		var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
		return [dates.indexOf(string) == -1];
	}

	$(function() {
		var today = new Date().toISOString().slice(0, 10);

		$('#reservasi_checkin, #reservasi_checkout').datepicker({
			changeYear: true,
			changeMonth: true,
			dateFormat: 'yy-mm-dd',
			minDate: today,
			beforeShowDay: DisableDates,
		});
	});
</script>

<script type="text/javascript">
	function calculate() {
		const reservasi_checkin = $('#reservasi_checkin').val();
		const reservasi_checkout = $('#reservasi_checkout').val();
		const homestay_price = $('#homestay_price').val();

		if (reservasi_checkin != '' && reservasi_checkout != '') {

			const start = new Date(reservasi_checkin);
			const end = new Date(reservasi_checkout);

			let loop = new Date(start);
			const date = [];

			while (loop <= end) {
				date.push(loop);
				let newDate = loop.setDate(loop.getDate() + 1);
				loop = new Date(newDate);
			}

			let reservasi_total = date.length * homestay_price;
			$('#reservasi_total').val(reservasi_total);

		}
	}
</script>