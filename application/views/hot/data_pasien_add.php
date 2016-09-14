<?php if(validation_errors()!=""){ ?>
<div class="alert alert-warning alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
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
<form action="<?php echo base_url()?>hot/pasien/add" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-6">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
        </div><!-- /.box-header -->

          <div class="box-footer pull-right">
            <button type="button"class="btn btn-primary" id="btn-simpan">Simpan</button>
            <button type="reset" class="btn btn-warning">Ulang</button>
            <button type="button"class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>hot/pasien'">Kembali</button>
          </div>
          <div class="box-body">
            <div class="form-group">
              <label>NIK*</label>
              <input type="text" class="form-control" name="username" placeholder="NIK" <?php if($action == "edit") echo "disabled"?> value="<?php 
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
              <label>Password*</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="<?php 
                if(set_value('password')=="" && isset($password)){
                  echo $password;
                }else{
                  echo  set_value('password');
                }
                ?>">
            </div>

            <div class="form-group">
              <label>Konfirmasi Password</label>
              <input type="password" class="form-control" id="password2" name="password2" placeholder="Konfirmasi Password">
            </div>

            <div class="form-group">
              <label>Nama*</label>
              <input type="text" class="form-control" name="nama" placeholder="Nama" value="<?php 
                if(set_value('nama')=="" && isset($nama)){
                  echo $nama;
                }else{
                  echo  set_value('nama');
                }
                ?>">
            </div>

            <div class="form-group">
                <label>Jenis Kelamin*</label>&nbsp;&nbsp;
                <?php
                  if(set_value('jk')=="" && isset($jk)){
                    $jk = $jk;
                  }else{
                    $jk = set_value('jk');
                  }
                ?>
                <label class="radio-inline">
                  <input type="radio" name="jk" value="L" class="iCheck-helper" <?php echo  ('L' == $jk) ? 'checked' : '' ?>>Pria
                </label>
                <label class="radio-inline">
                  <input type="radio" name="jk" value="P" class="iCheck-helper" <?php echo  ('P' == $jk) ? 'checked' : '' ?>>Wanita
                </label>
            </div>

            <div class="form-group">
              <label>Tinggi Badan</label>
              <input type="number" class="form-control" name="tb" placeholder="Tinggi Badan" value="<?php 
                if(set_value('tb')=="" && isset($tb)){
                  echo $tb;
                }else{
                  echo  set_value('tb');
                }
                ?>">
            </div> 

            <div class="form-group">
              <label>Berat Badan</label>
              <input type="number" class="form-control" name="bb" placeholder="Berat Badan" value="<?php 
                if(set_value('bb')=="" && isset($bb)){
                  echo $bb;
                }else{
                  echo  set_value('bb');
                }
                ?>">
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
              <label>Email*</label>
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
                <select name="code" type="text" class="form-control">
                    <?php foreach($datapuskesmas as $pus) : ?>
                      <?php $select = $pus->code == $code ? 'selected' : '' ?>
                      <option value="<?php echo $pus->code ?>" <?php echo $select ?>><?php echo $pus->value ?></option>
                    <?php endforeach ?>
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

  function close_popup(){
    $("#popup").jqxWindow('close');
  }

  $(function () { 
    $("#tgl_lahir").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height:30});

    $("#menu_dashboard").addClass("active");
    $("#menu_hot_pasien").addClass("active");

    $("#btn-simpan").click(function(){
        var data = new FormData();

        data.append('username',     $("[name='username']").val());
        data.append('bpjs',         $("[name='bpjs']:checked").val());
        data.append('password',     $("[name='password']").val());
        data.append('password2',    $("[name='password2']").val());
        data.append('nama',         $("[name='nama']").val());
        data.append('jk',           $("[name='jk']").val());
        data.append('tb',           $("[name='tb']").val());
        data.append('bb',           $("[name='bb']").val());
        data.append('phone_number', $("[name='phone_number']").val());
        data.append('tgl_lahir',    $("[name='tgl_lahir']").val());
        data.append('email',        $("[name='email']").val());
        data.append('alamat',       $("[name='alamat']").val());
        data.append('code',         $("[name='code']").val());

        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."hot/pasien/add"   ?>',
            data : data,
            success : function(response){
                if(response=="OK"){
                  $("#popup_content").html("<div style='padding:5px'><br><div style='text-align:center'>Data berhasil disimpan.<br><input class='btn btn-danger' style='width:100px' type='button' value='OK' onClick='close_popup()'></div></div>");
                      $("#popup").jqxWindow({
                        theme: theme, resizable: false,
                        width: 250,
                        height: 120,
                        isModal: true, autoOpen: false, modalOpacity: 0.4
                      });
                  $("#popup").jqxWindow('open');
                  window.location.href = "<?php echo base_url().'hot/pasien' ?>";
                }else{
                  $("#popup_content").html("<div style='padding:5px'><br><div style='text-align:center'>Data gagal disimpan<br><input class='btn btn-danger' style='width:100px' type='button' value='OK' onClick='close_popup()'></div></div>");
                      $("#popup").jqxWindow({
                        theme: theme, resizable: false,
                        width: 250,
                        height: 120,
                        isModal: true, autoOpen: false, modalOpacity: 0.4
                      });
                  $("#popup").jqxWindow('open');
                  window.location.href = "<?php echo base_url().'hot/pasien/add' ?>";
                }
            }
        });

        return false;
    });



  });
</script>
