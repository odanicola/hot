<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body">
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-3">Tanggal</div>
              <div class="col-md-9" style="margin-bottom: 5px">
                <?php echo date("d-m-Y",$id_import);?>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">Jam</div>
              <div class="col-md-9" style="margin-bottom: 5px">
                <?php echo date("H:i:s",$id_import);?>
              </div>  
            </div>
            <div class="row" id="jenisplor">
              <div class="col-md-3" >Username</div>
              <div class="col-md-9" style="margin-bottom: 5px">
                <?php echo $username;?>
              </div>
            </div>
            <div class="row">
                <div class="col-md-3">Jumlah Data</div>
                <div class="col-md-9" style="margin-bottom: 5px">
                  <?php echo $jumlah;?>
                </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-4">Jumlah Peserta BPJS</div>
              <div class="col-md-8" style="margin-bottom: 5px">
                <div id="jmlbpjs"></div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">Sudah Home Visit</div>
              <div class="col-md-8" style="margin-bottom: 5px">
                <div id="jmlhomevisit"></div>
              </div>  
            </div>
            <div class="row" id="jenisplor">
              <div class="col-md-4" >Belum Home Visit</div>
              <div class="col-md-8" style="margin-bottom: 5px">
                <div id="jmlblmhomevisit"></div>
              </div>
            </div>
          </div>
        </div>  
      </div>
    </div>
  </div>
</div>
<div class="box box-success">
  <div class="box-body">
    <div class="div-grid">
        <div id="jqxTabs">
          <?php echo $detailimport;?>
        </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(function(){
  //ambil_data_bpjs()
   $("#menu_ketuk_pintu").addClass("active");
      $("#menu_eform_import").addClass("active");
    $('#btn-send').click(function(){
        $('#btn-send').hide();
        $.ajax({
          url : '<?php echo site_url('eform/kegiatankelompok/send/') ?>',
          type : 'POST',
          data : 'kode={kode}',
          success : function(response) {
            if(response=="ok"){
              alert("Terimakasih, \nData berhasil terkirim ke PCare.");
              $('#btn-resend').show('fade');
            }else{
              alert(response);
              $('#btn-send').show('fade');
            }
          }
        });
    });

    $('#btn-resend').click(function(){
        $('#btn-resend').hide();
        $.ajax({
          url : '<?php echo site_url('eform/kegiatankelompok/resend/') ?>',
          type : 'POST',
          data : 'kode={kode}',
          success : function(response) {
            alert(response);

            $('#btn-resend').show('fade');
          }
        });
    });

    $('#btn-kembali').click(function(){
        window.location.href="<?php echo base_url()?>eform/import";
    });

  });
  
</script>

      