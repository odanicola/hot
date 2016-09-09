<script>
    function close_popup(){
        $("#popup").jqxWindow('close');
    }

    $(function () { 
      var date = new Date(<?php echo date("Y, d, m") ?>);
      var btn = "</br></br></br><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup()'>";

      $("#tgl").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height: '30px'});
      $("#tgl").jqxDateTimeInput('setDate', date);

      $("#popup").jqxWindow({
        theme: theme, resizable: false,
        width: 320,
        height: 180,
        isModal: true, autoOpen: false, modalOpacity: 0.4
      });

      $("#btn-daftar").click(function(){
        var tgl = $("#tgl").val();
        if(tgl <= "<?php echo date("d-m-Y") ?>"){
            $("#popup_content").html("<div style='text-align:center'><br><br>Pendaftaran berlaku setelah tanggal <br><?php echo date("d M Y") ?>."+btn+"</div>");
            $("#popup").jqxWindow('open');
        }else{  
            var data = new FormData();
            data.append('code', '{code}');
            data.append('username', '{nik}');
            data.append('tgl', tgl);

            $.ajax({
                cache : false,
                contentType : false,
                processData : false,
                type : 'POST',
                url : '<?php echo base_url()?>hot/kunjungan/daftar',
                data : data,
                success : function(response){
                    if(response=="OK"){
                      $("#popup_content").html("<div style='text-align:center'><br><br>Pendaftaran berhasil dilakukan. <br>"+btn+"</div>");
                        $("#popup").jqxWindow('open');
                        $("#daftar").hide();
                        $("#terdaftar").show('fade');
                    }else{
                      $("#popup_content").html("<div style='text-align:center'><br><br>Pendaftaran tidak dapat dilakukan. <br>"+btn+"</div>");
                        $("#popup").jqxWindow('open');
                    }
                }
            });
        }
      });
    });
</script>

<div id="popup" style="display:none;">
  <div id="popup_title">Hypertension Online Treatment</div><div id="popup_content">{popup}</div>
</div>
<section class="content">
  <div class="row">
    <div class="col-md-6">
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
        </div>

        <div id="daftar">
          <div class="box-footer pull-right">
            <button type="button" class="btn btn-primary" id="btn-daftar">Daftar</button>
            <button type="button" class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>hot/kunjungan'">Kembali</button>
          </div>
          <div style="clear:both"></div>
          <div class="box-body">
            <div class="form-group">
              <label>Tentukan Tanggal *</label>
               <div id='tgl' name="tgl" >
            </div>
          </div>
        </div>

        <div id="terdaftar" style="display:none">
          <div class="box-footer pull-right">
            <button type="button" class="btn btn-danger" id="btn-batal">Batalkan Pendaftaran</button>
            <button type="button" class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>hot/kunjungan'">Kembali</button>
          </div>
          <div style="clear:both"></div>
          <div class="box-body">
            <div class="form-group">
              <label>Anda sudah mendaftar untuk pemeriksaan<br>pada tanggal {tgl}}<br>Nomor Pendaftaran : {nomor}</label>
            </div>
          </div>
        </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
  	</div><!-- /.box -->
  </div><!-- /.box -->
</section>

<script>
	$(function () {	

    $("#menu_dashboard").addClass("active");
    $("#menu_hot_kunjungan").addClass("active");

	});
</script>
