<!-- Info boxes -->
<div class="row">
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-yellow"><a href="<?php echo base_url()?>morganisasi/profile"><i class="fa fa-user"></i></a></span>
      <div class="info-box-content">
        <span class="info-box-text"><a href="<?php echo base_url()?>morganisasi/profile">Profil</a></span>
        <span class="info-box-number" style="font-size:14px;"><?php echo ucwords($this->session-> userdata('username'));?></span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-blue"><a href="<?php echo base_url()?>kunjungan"><i class="fa fa-th-list"></i></a></span>
      <div class="info-box-content">
        <span class="info-box-text"><a href="<?php echo base_url()?>kunjungan">Data</a></span>
        <span class="info-box-number" style="font-size:14px;">Kunjungan</span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->

  <!-- fix for small devices only -->
  <div class="clearfix visible-sm-block"></div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-green"><a href="<?php echo base_url()?>pasein/resume"><i class="fa fa-list-alt"></i></a></span>
      <div class="info-box-content">
        <span class="info-box-text"><a href="<?php echo base_url()?>pasein/resume">Resume</a></span>
        <span class="info-box-number" style="font-size:14px;">Pribadi</span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-red"><a href="<?php echo base_url()?>pasein/jadwal"><i class="fa fa-calendar"></i></a></span>
      <div class="info-box-content">
        <span class="info-box-text"><a href="<?php echo base_url()?>pasein/jadwal">Jadwal</a></span>
        <span class="info-box-number" style="font-size:14px;">Kunjungan</span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->  <div class="col-md-3 col-sm-6 col-xs-12">
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