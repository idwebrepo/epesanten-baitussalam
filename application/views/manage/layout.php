<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $this->config->item('app_name') ?> <?php echo isset($title) ? ' | ' . $title : null; ?></title>
  <link rel="icon" type="image/png" href="<?php echo media_url('ico/favicon.ico') ?>">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo media_url() ?>css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo media_url() ?>css/font-awesome.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo media_url() ?>css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?php echo media_url() ?>css/style.css">
  <link rel="stylesheet" href="<?php echo media_url() ?>css/load-font-googleapis.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
   folder instead of downloading all of them to reduce the load. -->
   <!-- Notyfy JS - Notification -->
   <link rel="stylesheet" href="<?php echo media_url() ?>css/jquery.toast.css">
   <link rel="stylesheet" href="<?php echo media_url() ?>css/_all-skins.min.css">
   <!-- Date Picker -->
   <link rel="stylesheet" href="<?php echo media_url() ?>css/bootstrap-datepicker.min.css">
   <!-- Daterange picker -->
   <link rel="stylesheet" href="<?php echo media_url() ?>css/daterangepicker.css">
    <link rel="stylesheet" href="<?php echo media_url() ?>DataTables/css/dataTables.bootstrap.css">
   <link rel="stylesheet" href="<?php echo media_url() ?>DataTables/css/jquery.dataTables.css">
    
   <link href="<?php echo base_url('/media/js/fullcalendar/fullcalendar.css');?>" rel="stylesheet">

   <script src="<?php echo media_url() ?>js/jquery.min.js"></script>
   <script src="<?php echo media_url() ?>js/dateFormat.js"></script>
   <script src="<?php echo media_url() ?>js/jquery.dateFormat.js"></script>
   <script src="<?php echo media_url() ?>DataTables/js/jquery.dataTables.js"></script>
   <!--<script src="<?php echo media_url() ?>DataTables/plugins/range-date.js"></script>-->
   <script src="<?php echo media_url() ?>js/jquery.ajaxfileupload.js"></script>
   <!-- jQuery UI 1.11.4 -->
   <script src="<?php echo media_url() ?>js/jquery-ui.min.js"></script>
   <script src="<?php echo media_url() ?>js/jquery.inputmask.bundle.js"></script>
   
   <script src="<?php echo base_url('/media/js/fullcalendar/fullcalendar.js');?>"></script>
   <script src="<?php echo base_url('/media/js/Chart.js');?>"></script>
   
   <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.min.js"></script>
   <script type="text/javascript" src="https://emn178.github.io/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>
 </head>
 <body class="hold-transition skin-green fixed sidebar-mini" <?php echo isset($ngapp) ? $ngapp : null; ?>>
  <div class="wrapper">

    <header class="main-header">
      <!-- Logo -->
      <a href="<?php site_url('manage') ?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <?php if (!empty(logo())) { ?>
          <span class="logo-mini"><img src="<?php echo upload_url('school/' . logo()) ?>" style="height: 40px; margin-top: 5px; margin-left:5px;" class="pull-left"></span>
        <?php } else { ?>
          <span class="logo-mini"><img src="<?php echo media_url('img/logo.svg') ?>" style="height: 40px; margin-top: 5px; margin-left:5px;" class="pull-left"></span>
        <?php } ?>
        <?php if (!empty(logo())) { ?>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg pull-left"><img src="<?php echo upload_url('school/' . logo()) ?>" style="height: 40px; margin-top: 5px;" class="pull-left"><b>&nbsp;<?php echo $this->config->item('app_name') ?></b></span>
        <?php } else { ?>
          <span class="logo-lg pull-left"><img src="<?php echo media_url('img/logo.svg') ?>" style="height: 40px; margin-top: 5px;" class="pull-left"><b>&nbsp;<?php echo $this->config->item('app_name') ?></b></span>
        <?php } ?>
      </a>
      
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <?php
            $schoolName = "SELECT setting_value as name FROM `setting` WHERE setting_name = 'setting_school'";
            $resultName = $this->db->query($schoolName)->row();
        ?>
        <?php
            $schoolAddress = "SELECT setting_value as address FROM `setting` WHERE setting_name = 'setting_address'";
            $resultAddress = $this->db->query($schoolAddress)->row();
        ?>
        <?php
            $schoolDistrict = "SELECT setting_value as district FROM `setting` WHERE setting_name = 'setting_district'";
            $resultDistrict = $this->db->query($schoolDistrict)->row();
        ?>
        <?php
            $schoolCity = "SELECT setting_value as city FROM `setting` WHERE setting_name = 'setting_city'";
            $resultCity = $this->db->query($schoolCity)->row();
        ?>
        <?php
            $schoolPhone = "SELECT setting_value as phone FROM `setting` WHERE setting_name = 'setting_phone'";
            $resultPhone = $this->db->query($schoolPhone)->row();
        ?>
        <span class="logo-lg pull-left">
            <!--&nbsp&nbsp&nbsp&nbsp-->
            <font size="2" color="white">
                <b>Admin <?php echo $resultName->name; ?></b>
            </font><br>
            <font size="1" color="white"><?php echo $resultAddress->address.' '.$resultDistrict->district.' - '.$resultCity->city.', Telp. '.$resultPhone->phone ?>
            <!--Jl. Cendrawasih EE 17 Ngasem - Kediri, Telp. 08123585833-->
            </font>
        </span>
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- Messages: style can be found in dropdown.less-->
            

            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <?php if ($this->session->userdata('user_image') != null) { ?>
                  <img src="<?php echo upload_url().'/users/'.$this->session->userdata('user_image'); ?>" class="user-image">
                <?php } else { ?>
                  <img src="<?php echo media_url() ?>img/user.png" class="user-image">
                <?php } ?>
                <span class="hidden-xs"><?php echo ucfirst($this->session->userdata('ufullname')); ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                  <?php if ($this->session->userdata('user_image') != null) { ?>
                    <img src="<?php echo upload_url().'/users/'.$this->session->userdata('user_image'); ?>" class="img-circle">
                  <?php } else { ?>
                    <img src="<?php echo media_url() ?>img/user.png" class="img-circle">
                  <?php } ?>

                  <p>
                    <?php echo ucfirst($this->session->userdata('ufullname')); ?>
                    <small><?php echo ucfirst($this->session->userdata('urolename')); ?></small>
                    <small><?php echo $this->session->userdata('uemail'); ?></small>
                  </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="<?php echo site_url('manage/profile') ?>" class="btn btn-default btn-flat">Profile</a>
                  </div>
                  <div class="pull-right">
                    <a href="<?php echo site_url('manage/auth/logout?location=' . htmlspecialchars($_SERVER['REQUEST_URI'])) ?>" class="btn btn-default btn-flat">Sign out</a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
    </header> 

    <?php $files = glob('media/barcode_student/*');
    foreach($files as $file) { // iterate files
      if(is_file($file))
    unlink($file); // delete file
} ?>

<?php $this->load->view('manage/sidebar'); ?>
<!-- Content Wrapper. Contains page content -->
<?php isset($main) ? $this->load->view($main) : null; ?>
<!-- Content Wrapper. Contains page content -->


<!-- /.content-wrapper -->
<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <?php echo $this->config->item('app_name').' '.$this->config->item('version') ?>
  </div>
  <?php echo $this->config->item('created') ?>
</footer>

<!-- jQuery 3 -->




<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo media_url() ?>js/bootstrap.min.js"></script>
<script src="<?php echo media_url() ?>js/moment.min.js"></script>

<script src="<?php echo media_url() ?>js/fullcalendar.min.js"></script>


<!-- daterangepicker -->
<script src="<?php echo media_url() ?>js/daterangepicker.js"></script>
<!-- datepicker -->
<script src="<?php echo media_url() ?>js/bootstrap-datepicker.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo media_url() ?>js/jquery.slimscroll.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo media_url() ?>js/adminlte.min.js"></script>
<!-- Notyfy JS -->
<script src="<?php echo media_url() ?>js/jquery.toast.js"></script>

<!-- select2 -->
<link href="<?php echo media_url() ?>select2_css/select2.min.css" rel="stylesheet" />
<script src="<?php echo media_url() ?>select2_js/select2.min.js"></script>

<script>
$('.s2').select2();
</script>

<script>
  $(".years").datepicker({
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
    autoclose: true,
    todayHighlight: true
  });
  $(".input-group.date").datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
    todayHighlight: true
  });
  $('#multidate').datepicker({
      multidate: true,
    	format: "yyyy-mm-dd"
    });
</script>

<?php if ($this->session->flashdata('success')) { ?>
  <script>
    $(document).ready(function() {
      $.toast({
       heading: 'Berhasil',
       text: '<?php echo $this->session->flashdata('success') ?>',
       position: 'top-right',
       loaderBg: '#ff6849',
       icon: 'success',
       hideAfter: 3500,
       stack: 6
     })
    });
  </script>
<?php } ?>

<?php if ($this->session->flashdata('failed')) { ?>
  <script>
    $(document).ready(function() {
      $.toast({
       heading: 'Gagal',
       text: '<?php echo $this->session->flashdata('failed') ?>',
       position: 'top-right',
       loaderBg: '#ff2e2e',
       icon: 'error',
       hideAfter: 3500,
       stack: 6
     })
    });
  </script>
<?php } ?>


<script>
	$(document).ready(function(){
		$('#dtable').DataTable();
	});
</script>

<script>
    $(document).ready(function(){
		$('#ppdbtable').DataTable({
            "order": [[1, 'asc']]
		});
	});
</script>

<script>

  $(document).ready(function(){
    $('.numeric').inputmask("numeric", {
      removeMaskOnSubmit: true,
      radixPoint: ".",
      groupSeparator: ",",
      digits: 2,
      autoGroup: true,
            prefix: 'Rp ', //Space after $, this will not truncate the first character.
            rightAlign: false,
            // oncleared: function() {
            //   self.Value('');
            // }
          });
  });
</script>

<script>
  $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
  });
</script>

<script>
    // JavaScript New Window
	var win = null;
	function newWindow(mypage,myname,w,h,features) {
		var winl = (screen.width-w)/2;
		var wint = (screen.height-h)/2;
		if (winl < 0) winl = 0;
		if (wint < 0) wint = 0;
		var settings = 'height=' + h + ',';
		settings += 'width=' + w + ',';
		settings += 'top=' + wint + ',';
		settings += 'left=10,';
		settings += features;
		win = window.open(mypage,myname,settings);
		win.window.focus();
	}
</script>

<script>
    // Fungsi untuk format nominal angka
    function number_format(number, decimals, dec_point, thousands_sep) {

    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };

    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
    }

    // Bar Chart TAGIHAN SISWA TIAP UNIT
    var ctx = document.getElementById("Chart_tagihan").getContext('2d');
		var myChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: ["KB/TK", "SD/MI", "SMP/MTs", "SMA/MA"],
				datasets: [
          {
					label: "kurang 3 bulan",
					data: [647000, 5059000, 2400000, 919000],
					backgroundColor: "rgba(123, 103, 238)",
					borderWidth: 1
				  },
          {
            label: "lebih 3 bulan",
            data: [675000, 5059000, 2400000, 1343000],
            backgroundColor: "rgba(60, 179, 113)",
            borderWidth:1
          }
      ]
			},
      options: {
        responsive: true,
        plugins: {
          labels: {
            render: 'value'
          }
        },
        tooltips: {
              callbacks: {
                  label: function(tooltipItem, data) {
                      return tooltipItem.yLabel.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                  }
              }
          },
          scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true,
                    callback: function(value, index, values) {
                        return number_format(value);
                    }
                }
            }]
          }
      }
		});

  // Line Chart LAPORAN AKUNTING
  var ctx = document.getElementById("lap_akunting_line").getContext("2d");
    var data = {
      labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
      datasets: [
        {
          label: "Neraca",
          fill: false,
          lineTension: 0.1,
          backgroundColor: "#29B0D0",
          borderColor: "#29B0D0",
          pointHoverBackgroundColor: "#29B0D0",
          pointHoverBorderColor: "#29B0D0",
          data: [1,2,3,4,5,6,7,8,9,10,11,12]
        },
        {
          label: "Rugi Laba",
          fill: false,
          lineTension: 0.1,
          backgroundColor: "#F07124",
          borderColor: "#F07124",
          pointHoverBackgroundColor: "#F07124",
          pointHoverBorderColor: "#F07124",
          data: [5,4,8,7,7,5,4,8,9,10,10,9]
        },
        {
          label: "Modal",
          fill: false,
          lineTension: 0.1,
          backgroundColor: "#979193",
          borderColor: "#979193",
          pointHoverBackgroundColor: "#979193",
          pointHoverBorderColor: "#979193",
          data: [3,6,5,4,4,5,7,6,8,8,9,9]
        }
      ]
    };
 
  var myBarChart = new Chart(ctx, {
  type: 'line',
  data: data,
  options: {
    legend: {
      display: true
    },
    barValueSpacing: 20,
    scales: {
      yAxes: [{
        ticks: {
          min: 0,
        }
      }],
      xAxes: [{
        gridLines: {
          color: "rgba(0, 0, 0, 0)",
        }
      }]
    },
    plugins: {
      labels: {
        render: 'value'
      }
    }
  }
  });

</script>

<script>
// FORMAT LABEL ANGKA
function number_format(number, decimals, dec_point, thousands_sep) {

    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };

    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
  }

</script>

<script>
    function pilih_paket() {
        var paket = $("#package").val();
    
        $.ajax({ 
            url: '<?= base_url() . 'manage/setting/change_package' ?>',
            type: 'POST', 
            data: {
                    'paket': paket,
            },    
            success: function(msg) {
                    window.location.href = 'https://demo.epesantren.co.id/manage';
            },
        	error: function(msg){
        			alert('msg');
        	}
            
        });
        e.preventDefault();
}
</script>
</body>
</html>
