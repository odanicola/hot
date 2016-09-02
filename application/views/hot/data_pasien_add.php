<?php if(validation_errors()!=""){ ?>
<div class="alert alert-warning alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
  <?php echo validation_errors()?>
</div>
<?php } ?>

<?php if($this->session->flashdata('alert_form')!=""){ ?>
<div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $this->session->flashdata('alert_form')?>
</div>
<?php } ?>


<section class="content">
<form action="<?php echo base_url()?>morganisasi/daftar" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-6">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
        </div><!-- /.box-header -->

          <div class="box-footer pull-right">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="reset" class="btn btn-warning">Ulang</button>
            <button type="button" class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>mst/agama'">Kembali</button>
          </div>
          <div class="box-body">
            <div class="form-group">
              <label>NIK</label>
              <input type="text" class="form-control" name="nik" placeholder="NIK" value="<?php 
                if(set_value('nik')=="" && isset($nik)){
                  echo $nik;
                }else{
                  echo  set_value('nik');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>BPJS</label>
              <input type="text" class="form-control" name="bpjs" placeholder="BPJS" value="<?php 
                if(set_value('bpjs')=="" && isset($bpjs)){
                  echo $bpjs;
                }else{
                  echo  set_value('bpjs');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Password</label>
              <input type="text" class="form-control" name="password" placeholder="Password" value="<?php 
                if(set_value('password')=="" && isset($password)){
                  echo $password;
                }else{
                  echo  set_value('password');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Konfirmasi Password</label>
              <input type="text" class="form-control" name="password2" placeholder="Konfirmasi Password" value="<?php 
                if(set_value('password')=="" && isset($password)){
                  echo $password;
                }else{
                  echo  set_value('password');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Nama</label>
              <input type="text" class="form-control" name="nama" placeholder="Nama" value="<?php 
                if(set_value('nama')=="" && isset($nama)){
                  echo $nama;
                }else{
                  echo  set_value('nama');
                }
                ?>">
            </div>
            <div class="form-group">
              <label> Jenis Kelamin </label>
              <?php
                if(set_value('jenis_kelamin')=="" && isset($jenis_kelamin)){
                  $jenis_kelamin = $jenis_kelamin;
                }else{
                  $jenis_kelamin = set_value('jenis_kelamin');
                }
              ?>
              <div>
                <div class="col-sm-4">
                  <input type="radio" name="jenis_kelamin" value="L" class="iCheck-helper" <?php echo  ('L' == $jenis_kelamin) ? 'checked' : '' ?>> Laki-laki 
                </div>
                <div class="col-sm-4">
                  <input type="radio" name="jenis_kelamin" value="P" class="iCheck-helper" <?php echo  ('P' == $jenis_kelamin) ? 'checked' : '' ?>> Perempuan
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>No Telepon</label>
              <input type="text" class="form-control" name="phone_number" placeholder="No Telepon" value="<?php 
                if(set_value('phone_number')=="" && isset($phone_number)){
                  echo $phone_number;
                }else{
                  echo  set_value('phone_number');
                }
                ?>">
            </div> 

              <div class="form-group">
                <label>Tanggal Lahir</label>
                <div id='tgl_lahir' name="tgl_lahir" value="<?php
                  if(set_value('tgl_lahir')=="" && isset($tgl_lahir)){
                    $tgl_lahir = strtotime($tgl_lahir);
                  }else{
                    $tgl_lahir = strtotime(set_value('tgl_lahir'));
                  }
                  if($tgl_lahir=="") $tgl_lahir = time();
                  echo date("Y-m-d",$tgl_lahir);
                ?>" >
                </div>
             </div>
           
            <div class="form-group">
              <label>Email</label>
              <input type="text" class="form-control" name="email" placeholder="Email" value="<?php 
                if(set_value('email')=="" && isset($email)){
                  echo $email;
                }else{
                  echo  set_value('email');
                }
                ?>">
            </div>            
            <div class="form-group">
              <label>Alamat</label>
              <input type="text" class="form-control" name="alamat" placeholder="Alamat" value="<?php 
                if(set_value('alamat')=="" && isset($alamat)){
                  echo $alamat;
                }else{
                  echo  set_value('alamat');
                }
                ?>">
            </div>            
            <div class="form-group">
              <label>Puskesmas</label>
                <select name="puskesmas" id="puskesmas" class="form-control">
                    <?php foreach ($datapuskesmas as $pus ) { ;?>
                    <?php $select = $pus->code ? 'selected=selected' : '' ?>
                      <option value="<?php echo $pus->code;$pus->value; ?>,<?php echo $pus->value ?>" <?php echo $select ?>><?php echo $pus->value; ?></option>
                    <?php } ;?>
                </select>
            </div>
          </div>
          </div><!-- /.box-body -->
      </div><!-- /.box -->
  	</div><!-- /.box -->
  </div><!-- /.box -->
</form>
</section>

<script>
	$(function () {	
    $("#tgl_lahir").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height:30});

    $("#menu_dashboard").addClass("active");
    $("#menu_hot_pasien").addClass("active");
	});
</script>
