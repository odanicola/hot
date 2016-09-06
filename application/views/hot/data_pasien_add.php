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

<div id="popup" style="display:none;">
  <div id="popup_title">Hypertension Online Treatment</div><div id="popup_content">{popup}</div>
</div>
<section class="content">
<form action="<?php echo base_url()?>hot/pasien/{action}/{id}" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-6">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
        </div><!-- /.box-header -->

          <div class="box-footer pull-right">
            <button type="button"class="btn btn-primary" id="btn_simpan">Simpan</button>
            <button type="reset" class="btn btn-warning">Ulang</button>
            <button type="button"class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>mst/agama?>'">Kembali</button>
          </div>
          <div class="box-body">
            <div class="form-group">
              <label>NIK</label>
              <input type="text" class="form-control" name="nik" placeholder="NIK" value="<?php 
                if(set_value('username')=="" && isset($username)){
                  echo $username;
                }else{
                  echo  set_value('username');
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
              <input type="password" class="form-control" name="password" placeholder="Password" value="<?php 
                if(set_value('password')=="" && isset($password)){
                  echo $password;
                }else{
                  echo  set_value('password');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Konfirmasi Password</label>
              <input type="text" class="form-control" name="password2" placeholder="Konfirmasi Password">
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
                <label> Jenis Kelamin </label>&nbsp;&nbsp;
                <?php
                  if(set_value('jk')=="" && isset($jk)){
                    $jk = $jk;
                  }else{
                    $jk = set_value('jk');
                  }
                ?>
                <label class="radio-inline">
                  <input type="radio" name="jenis_kelamin" value="L" class="iCheck-helper" <?php echo  ('L' == $jk) ? 'checked' : '' ?>>Pria
                </label>
                <label class="radio-inline">
                  <input type="radio" name="jenis_kelamin" value="P" class="iCheck-helper" <?php echo  ('P' == $jk) ? 'checked' : '' ?>>Wanita
                </label>
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

    $("#btn_simpan").click(function(){
            var data = new FormData();
            $('#biodata_notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
            $('#biodata_notice').show();

            var tgl   = $("[name='tgl']").val();
            var bln   = $("[name='bln']").val();
            var thn   = $("[name='thn']").val();
            var tgl_lahir = tgl+"-"+bln+"-"+thn;

            var puskesmas = $("[name='puskesmas']").val();
            var fields  = puskesmas.split(",");
            var kode    = fields[0];
            var namapus = fields[1];

            var kodepus = kode.substring(11, 1);
            var btn = "</br></br></br><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup_daftar()'>";

            data.append('username',               $("[name='nik']").val());
            data.append('bpjs',                   $("[name='bpjs']").val());
            data.append('pass',                   $("#pass").val());
            data.append('nama',                   $("[name='nama']").val());
            data.append('jk',                     $("[name='jk']").val());
            data.append('tgl_lahir',              tgl_lahir);
            data.append('phone_number',           $("[name='phone_number']").val());
            data.append('email',                  $("[name='email']").val());
            data.append('alamat',                 $("[name='alamat']").val());
            data.append('code',                   kodepus);

            $("#popup").jqxWindow({
              theme: theme, resizable: false,
              width: 300,
              height: 180,
              isModal: true, autoOpen: false, modalOpacity: 0.4
            });

            if($("#pass").val() == "" || $("#pass2").val()==""){
                $("#popup_content_daftar").html("<div style='text-align:center'><br>Anda belum mengisi password dengan benar."+btn+"</div>");
                $("#popup").jqxWindow('open');
            }else if($("#pass").val() != $("#pass2").val()){
                $("#popup_content_daftar").html("<div style='text-align:center'><br>Password tidak sama."+btn+"</div>");
                $("#popup").jqxWindow('open');
            }else if($("[name='nama']").val() == ""){
                $("#popup_content_daftar").html("<div style='text-align:center'><br>Anda belum mengisi nama."+btn+"</div>");
                $("#popup").jqxWindow('open');
            }else if($("[name='phone_number']").val()==""){
                $("#popup_content_daftar").html("<div style='text-align:center'><br>Anda belum mengisi nomor telepon."+btn+"</div>");
                $("#popup").jqxWindow('open');
            }else{
                $.ajax({
                    cache : false,
                    contentType : false,
                    processData : false,
                    type : 'POST',
                    url : '<?php echo base_url()."hot/pasien/{action}/{id}" ?>',
                    data : data,
                    success : function(response){
                      if(response=="OK"){
                          $("#tbl-register2").hide();
                          $("#tbl-success").show("fade");
                      }else if(response=="NOTOK"){
                        $("#popup_content_daftar").html("<div style='text-align:center'><br>NIK atau BPJS sudah terdaftar.</br></br></br><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup_daftar()'></div>");
                          $("#popup").jqxWindow('open');
                      }
                    }
                });
            }

            return false;
        });

	});
</script>
