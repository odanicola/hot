<div id="popup_akun" style="display:none;">
  <div id="popup_title_akun">Hypertension Online Treatment</div><div id="popup_content_akun">{popup}</div>
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
            <button type="button" class="btn btn-primary" id="btn_simpan_akun">Simpan</button>
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
              <input type="password" class="form-control" id="password" name="password" placeholder="Password">
              <span id="confirmMessage1" class="confirmMessage"></span>
            </div>

            <div class="form-group">
              <label>Konfirmasi Password</label>
              <input type="password" class="form-control" id="password2" name="password2" placeholder="Konfirmasi Password">
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
        $("#popup_akun").jqxWindow('close');
  }
  
  $(function () { 
    tabIndex = 2;

    $("#popup_akun").jqxWindow({
      theme: theme, resizable: false,
      width: 250,
      height: 150,
      isModal: true, autoOpen: false, modalOpacity: 0.4
    });

    $("#btn_simpan_akun").click(function(){
        var data = new FormData();
        $('#biodata_notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
        $('#biodata_notice').show();

        data.append('password',     $("[name='password']").val());
        data.append('password2',    $("[name='password2']").val());

        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."hot/pasien/data_pasien_edit/2/{username}"   ?>',
            data : data,
            success : function(response){
              $("html, body").animate({ scrollTop: 0 }, "slow");
              a = response.split("|");
                if(a[0]=='OK'){
                  $("#popup_content_akun").html("<div style='padding:5px'><br><div style='text-align:center'>Data berhasil diubah.<br><br><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup()'></div></div>");
                  $("#popup_akun").jqxWindow('open');
                }else{
                  $("#popup_content_akun").html("<div style='padding:5px'><br><div style='text-align:center'>Data gagal diubah.<br><br><input class='btn btn-danger' style='width:100px' type='button' value='OK' onClick='close_popup()'></div></div>");
                  $("#popup_akun").jqxWindow('open');
                }
            }
        });

        return false;
    });
  });
</script>
