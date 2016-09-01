<!-- Info boxes -->
<div class="row">
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-green"><a href="<?php echo base_url()?>kunjungan"><i class="fa fa-list-ol"></i></a></span>
      <div class="info-box-content">
        <span class="info-box-text"><a href="<?php echo base_url()?>kunjungan">Kunjungan</a></span>
        <span class="info-box-number" style="font-size:14px;">2 Antrian</span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-yellow"><a href="<?php echo base_url()?>pasien"><i class="fa fa-user"></i></a></span>
      <div class="info-box-content">
        <span class="info-box-text"><a href="<?php echo base_url()?>pasien">Data Pasien</a></span>
        <span class="info-box-number" style="font-size:14px;">212 Orang</span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-purple"><a href="<?php echo base_url()?>dokter"><i class="fa fa-user-md"></i></a></span>
      <div class="info-box-content">
        <span class="info-box-text"><a href="<?php echo base_url()?>dokter">Data Dokter</a></span>
        <span class="info-box-number" style="font-size:14px;">1 Orang</span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-red"><a href="<?php echo base_url()?>guideline"><i class="fa fa-sitemap"></i></a></span>
      <div class="info-box-content">
        <span class="info-box-text"><a href="<?php echo base_url()?>guideline">Guideline</a></span>
        <span class="info-box-number" style="font-size:14px;">Panduan JNC 8</span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-blue"><a href="<?php echo base_url()?>sync"><i class="fa fa-refresh"></i></a></span>
      <div class="info-box-content">
        <span class="info-box-text"><a href="<?php echo base_url()?>sync">Sinkronisasi</a></span>
        <span class="info-box-number" style="font-size:14px;">PCare</span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->

  <!-- fix for small devices only -->
  <div class="clearfix visible-sm-block"></div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-orange"><a href="<?php echo base_url()?>draft"><i class="fa fa-list-alt"></i></a></span>
      <div class="info-box-content">
        <span class="info-box-text"><a href="<?php echo base_url()?>draft">Draft</a></span>
        <span class="info-box-number" style="font-size:14px;">Laporan</span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-silver"><a href="<?php echo base_url()?>morganisasi/logout"><i class="fa fa-power-off"></i></a></span>
      <div class="info-box-content">
        <span class="info-box-text"><a href="<?php echo base_url()?>morganisasi/logout">Logout</a></span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
</div><!-- /.row -->

<script>
  $(function () { 
    $("#menu_dashboard").addClass("active");
    $("#menu_morganisasi").addClass("active");
  });
</script>