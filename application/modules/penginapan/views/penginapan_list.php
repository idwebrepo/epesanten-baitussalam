<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
<?php
function createSummary($text)
{
	$limit = 100;
	$textWithoutTags = strip_tags($text);
	$textWithoutImages = preg_replace('/<img[^>]*>/', '', $textWithoutTags);

	$visibleText = mb_substr($textWithoutImages, 0, $limit);

	$lastPeriodPos = mb_strrpos($visibleText, '.');

	if ($lastPeriodPos !== false) {
		$visibleText = mb_substr($visibleText, 0, $lastPeriodPos + 1);
	}

	return $visibleText;
}
?>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header">
				</div>
				<!-- /.box-header -->
				<div class="box-body table-responsive">
					<div class="row">
						<?php foreach ($penginapan as $row) : ?>
							<div class="col-md-3">
								<div class="card" style="padding: 10px; border: 1pt solid #d2d6de; box-shadow: 5px 5px 10px #d2d6de;">
									<img class="card-img-top img-fluid" src="<?= base_url() . 'uploads/gallery/' . $row['gallery_img'] ?>" width="280px" height="290px" alt="Gambar Program">
									<div class="card-body">
										<h3 class="card-title" style="font-weight: bold;"><?= $row['homestay_name'] ?></h3>
										<p class="card-text"><?= (strlen($row['homestay_desc']) > 300) ? createSummary($row['homestay_desc']) . '...' :  $row['homestay_desc'] ?></p>
										<div class="row">
											<div class="col-md-12">
												<h3 style="font-weight: bold;">Rp <?= number_format($row['homestay_price'], '0', ',', '.') ?></h3>
											</div>
										</div>
										<div class="row" style="margin-top: 20px;">
											<div class="col-md-12">
												<a href="<?= base_url() . 'penginapan/room/?r=' . base64_encode($row['homestay_id']) ?>" class="btn btn-primary btn-block" title="Lihat Penginapan">Lihat</a>
											</div>
										</div>
									</div>
								</div>
							</div>

						<?php endforeach; ?>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
		</div>
</section>