<div id="popup" style="display:none;">
  <div id="popup_title">Hypertension Online Treatment</div><div id="popup_content">{popup}</div>
</div>
<section class="content">
<form action="<?php echo base_url()?>hot/obat/{action}/{code}" method="POST" name="">
  <div class="row">
    <div class="col-md-6">
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
        </div>
          <div class="box-footer pull-right">
            <button type="submit"class="btn btn-primary">Simpan</button>
            <button type="reset" class="btn btn-warning">Ulang</button>
            <button type="button"class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>hot/obat'">Kembali</button>
          </div>
          <div class="box-body" style="clear:both">
            <div class="form-group">
              <label>Nama *</label>
              <input type="text" class="form-control" name="value" placeholder="Nama" value="<?php 
                if(set_value('value')=="" && isset($value)){
                  echo $value;
                }else{
                  echo  set_value('value');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Sediaan </label>
              <input type="number" class="form-control" name="sediaan" placeholder="Sediaan" value="<?php 
                if(set_value('sediaan')=="" && isset($sediaan)){
                  echo $sediaan;
                }else{
                  echo  set_value('sediaan');
                }
                ?>">
            </div>
            <div class="form-group">
              <input type="checkbox" value="1" name="status" <?php 
                if(isset($status) && $status==1){
                  echo "checked";
                }
              ?>>
              <label>Status</label>
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

    $("#menu_dashboard").addClass("active");
    $("#menu_hot_obat").addClass("active");

	});
</script>
