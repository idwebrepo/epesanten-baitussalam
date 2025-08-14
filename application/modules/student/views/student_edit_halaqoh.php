<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo isset($title) ? $title : null; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
            <li><a href="<?php echo site_url('manage/class') ?>">Student</a></li>
            <li class="active"><?php echo isset($title) ? $title : null; ?></li>
        </ol>
    </section>

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
                        <?php
                        $no = 1;
                        foreach ($halaqoh_student as $row) {
                            ?>
                            <div class="form-group">
                                <input type="hidden" value="<?= $row['student_id'] ?>" name="student_id">
                                <input type="hidden" value="<?= $row['relation_id'] ?>" name="relation_id[]">
                                <label> Kelompok Halaqoh <?= $no++ ?> <small data-toggle="tooltip"
                                                                         title="Wajib diisi">*</small></label>
                                <select required="" name="relation_halaqoh_id[]" class="form-control">
                                    <option value="">-Pilih Halaqoh-</option>
                                    <?php foreach ($halaqoh as $item) { ?>
                                        <option value="<?php echo $item['halaqoh_id']; ?>" <?php echo ($item['halaqoh_id'] == $row['halaqoh_id']) ? 'selected' : '' ?>><?php echo $item['halaqoh_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php
                        }
                        ?>

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
                        <a href="<?php echo site_url('manage/student'); ?>" class="btn btn-block btn-info">Batal</a>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
        <!-- /.row -->
    </section>
</div>
