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
<form action="<?php echo base_url()?>hot/pasien/data_pasien_{action}/2/{username}" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-6">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
        </div><!-- /.box-header -->

          <div class="box-footer pull-right">
            <button type="submit" class="btn btn-primary" id="btn_simpan">Simpan</button>
            <button type="reset"  class="btn btn-warning">Ulang</button>
            <button type="button" class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>hot/pasien'">Kembali</button>
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
              <label>Password*</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="<?php 
                if(set_value('password')=="" && isset($password)){
                  // echo $password;
                }else{
                  echo  set_value('password');
                }
                ?>">
              <span id="confirmMessage1" class="confirmMessage"></span>
            </div>

            <div class="form-group">
              <label>Konfirmasi Password</label>
              <input type="password" class="form-control" id="password2" name="password2" placeholder="Konfirmasi Password">
              <span id="confirmMessage2" class="confirmMessage"></span>
            </div>

          </div>
          </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.box -->
  </div><!-- /.box -->
</form>
</section>

<script>

  $("#password").keyup(function(){
      var pass1 = $("#password").val();
      var pass2 = $("#password2").val();

      if ((pass1 == pass2)&&(pass1!="" && pass2!="")) {
          $("#password2").css("background-color", "#b3b3ff");
          $("#confirmMessage2").text("Passwords Match!");
          $("#btn_simpan").prop('disabled', false);
      
      }else if((pass1 != pass2)&&(pass1!="" && pass2!="")||(pass1==""&&pass2!='')){
          $("#password2").css("background-color", "#ff9999");
          $("#confirmMessage2").text("Passwords Do Not Match!");
          $("#btn_simpan").prop('disabled', true);

      }else if(pass1!=''&& pass2==""){

      }else if (pass1=='' && pass2==''){
          $("#password2").css("background-color", "#ffffff");
          $("#confirmMessage1").text("Passwords Tidak Boleh Kosong!");
          $("#confirmMessage2").text("Passwords Tidak Boleh Kosong!");
          $("#btn_simpan").prop('disabled', true);
      };
  });

  $("#password2").keyup(function(){
      var pass1 = $("#password").val();
      var pass2 = $("#password2").val();

      if ((pass1 == pass2)&&(pass1!="" && pass2!="")) {
          $("#password2").css("background-color", "#b3b3ff");
          $("#confirmMessage2").text("Passwords Match!");
          $("#btn_simpan").prop('disabled', false);

      }else if((pass1 != pass2)&&(pass1!="" && pass2!="")||(pass1!=''&&pass2=="")){
          $("#password2").css("background-color", "#ff9999");
          $("#confirmMessage2").text("Passwords Do Not Match!");
          $("#btn_simpan").prop('disabled', true);

      }else if (pass1=='' && pass2==''){
          $("#password2").css("background-color", "#ffffff");
          $("#confirmMessage1").text("Passwords Tidak Boleh Kosong!");
          $("#confirmMessage2").text("Passwords Tidak Boleh Kosong!");
          $("#btn_simpan").prop('disabled', true);
      };
  });

  $(this).css('background-color', '#FFFFFF');

  $(function () { 
    $("#tgl_lahir").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height:30});

    $("#menu_dashboard").addClass("active");
    $("#menu_hot_pasien").addClass("active");

  });
</script>
