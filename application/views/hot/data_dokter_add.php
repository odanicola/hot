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
<form action="<?php echo base_url()?>hot/dokter/{action}/{code}" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-6">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
        </div><!-- /.box-header -->

          <div class="box-footer pull-right">
            <button type="submit"class="btn btn-primary">Simpan</button>
            <button type="reset" class="btn btn-warning">Ulang</button>
            <button type="button"class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>hot/dokter'">Kembali</button>
          </div>
          <div class="box-body">
            <div class="form-group">
              <label>Nama*</label>
              <input type="text" class="form-control" name="value" placeholder="Nama" value="<?php 
                if(set_value('value')=="" && isset($value)){
                  echo $value;
                }else{
                  echo  set_value('value');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Status*</label>
              <input type="text" class="form-control" name="status" placeholder="BPJS" value="<?php 
                if(set_value('status')=="" && isset($status)){
                  echo $status;
                }else{
                  echo  set_value('status');
                }
                ?>">
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
      
      }else if((pass1 != pass2)&&(pass1!="" && pass2!="")||(pass1==""&&pass2!='')){
          $("#password2").css("background-color", "#ff9999");
          $("#confirmMessage2").text("Passwords Do Not Match!");

      }else if(pass1!=''&& pass2==""){

      }else if (pass1=='' && pass2==''){
          $("#password2").css("background-color", "#ffffff");
          $("#confirmMessage1").text("Passwords Tidak Boleh Kosong!");
          $("#confirmMessage2").text("Passwords Tidak Boleh Kosong!");

      };
  });

  $("#password2").keyup(function(){
      var pass1 = $("#password").val();
      var pass2 = $("#password2").val();

      if ((pass1 == pass2)&&(pass1!="" && pass2!="")) {
          $("#password2").css("background-color", "#b3b3ff");
          $("#confirmMessage2").text("Passwords Match!");
      }else if((pass1 != pass2)&&(pass1!="" && pass2!="")||(pass1!=''&&pass2=="")){
          $("#password2").css("background-color", "#ff9999");
          $("#confirmMessage2").text("Passwords Do Not Match!");
      }else if (pass1=='' && pass2==''){
          $("#password2").css("background-color", "#ffffff");
          $("#confirmMessage1").text("Passwords Tidak Boleh Kosong!");
          $("#confirmMessage2").text("Passwords Tidak Boleh Kosong!");
      };
  });

  $(this).css('background-color', '#FFFFFF');

	$(function () {	

    $("#menu_dashboard").addClass("active");
    $("#menu_hot_dokter").addClass("active");

	});
</script>
