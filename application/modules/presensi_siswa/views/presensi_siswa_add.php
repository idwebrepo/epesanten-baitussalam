

	<!-- Main content -->
	<!-- Main content -->
	<section class="content">
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<!-- /.box-header -->
					<div class="box-body">
                        <div class="col-md-4">
                            <?php echo form_open_multipart(current_url(), array('id'=>'presensiForm')); ?>
                            <?php echo validation_errors(); ?>
                            <div class="form-group">
                                <label>Ambil Foto <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                <div class="row">
                                    <div class="col-md-6">
                                    <div id="my_camera"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="results"></div>
                                    </div>
                                </div>
                                <!-- <p id="location"></p> -->
                                <input type="hidden" name="image" class="image-tag">
                                <input type="hidden" name="longi" id="longi" />
                                <input type="hidden" name="lati" id="lati" />
                            </div>
                            <div class="form-group">
                                <label for="">Scan Kartu <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                <input type="text" class="form-control" name="rfid" id="rfid" autofocus="" placeholder="Scan Kartu Kalian" required  oninput="take_snapshot()">
                            </div>
                            <div class="form-group">
                                <label for="">Masukkan PIN <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                <input type="password" class="form-control" name="pin" id="pin" placeholder="Masukkan PIN Kalian" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
		<!-- /.row -->
	</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
<script language="JavaScript">
    Webcam.set({
		width: 200,
        height: 200,
        image_format:'jpeg',
        jpeg_quality: 90
    });
  
    Webcam.attach( '#my_camera' );
  
    function take_snapshot() {
        Webcam.snap( function(data_uri) {
            document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
			Webcam.upload( data_uri, '<?php echo site_url('rest-api/uploadImageSiswa.php'); ?>', function(code, text) {
				$(".image-tag").val(text);
				// alert(text);
			});
        });
    }
</script>
<script>
// var x = document.getElementById("location");

if (navigator.geolocation) {
	navigator.geolocation.getCurrentPosition(showPosition);
} 
// else { 
	// x.innerHTML = "Geolocation is not supported by this browser.";
// }

function showPosition(position) {
//   x.innerHTML = "Latitude: " + position.coords.latitude + 
//   "<br>Longitude: " + position.coords.longitude;

  $("#longi").val(position.coords.longitude);
  $("#lati").val(position.coords.latitude);
}
</script>

<script>
    document.getElementById('presensiForm').addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            // submitForm();
        }
    });
</script>