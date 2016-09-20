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

<section class="content">
<!-- <form action="<?php echo base_url()?>sms/pbk/{action}/{username}" method="POST" name=""> -->
<form method="POST" id="form-pbk">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
        </div><!-- /.box-header -->

          <div class="box-body">
            <div class="form-group">
              <label>Grup</label>
              <select  name="id_grup" id="id_grup" class="form-control">
                  <option value="">-- Pilih Grup --</option>
                  <?php foreach($grupoption as $row) : ?>
                    <?php 
                    if(isset($id_grup) && $id_grup==$row->id_grup){
                      $select = $row->id_grup == $id_grup ? 'selected' : '';
                    }elseif(set_value('id_grup')!=""){
                      $select = $row->id_grup == set_value('id_grup') ? 'selected' : '';
                    }else{
                      $select ='';
                    } 
                    ?>
                    <option value="<?php echo $row->id_grup ?>" <?php echo $select ?>><?php echo $row->nama ?></option>
                  <?php endforeach ?>
              </select>
            </div>
          </div>
          <div class="box-footer pull-right">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="reset" id="btn-close" class="btn btn-success">Batal</button>
          </div>
      </div><!-- /.box -->
    </div><!-- /.box -->
  </div><!-- /.box -->
</form>
</section>
<script type="text/javascript">
  $(function () {

    $('#form-pbk').submit(function(){
        var data = new FormData();
        $('#notice-content').html('<div class="alert">Mohon tunggu....</div>');
        $('#notice').show();

        data.append('id_grup', $('#id_grup').val());
        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."sms/pbk/edit/".$username?>',
            data : data,
            success : function(response){
              var res  = response.split("|");
              if(res[0]=="OK"){
                  $('#notice').hide();
                  $('#notice-content').html('<div class="alert">'+res[1]+'</div>');
                  $('#notice').show();

                  $("#jqxgrid_pbk").jqxGrid('updatebounddata', 'cells');
                  close_popup();
              }
              else if(res[0]=="Error"){
                  $('#notice').hide();
                  $('#notice-content').html('<div class="alert">'+res[1]+'</div>');
                  $('#notice').show();
              }
              else{
                  $('#popup_content').html(response);
              }
          }
        });

        return false;
    });

    $("#btn-close").click(function(){
      close_popup();
    });

  });

</script>