<script>
    $(function () { 
      var btn = "</br></br></br><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup()'>";
      var btn_reload = "</br></br></br><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup();window.location.reload()'>";
      var btn_batal = "</br></br></br><input class='btn btn-danger' style='width:100px' type='button' value='Ya' onClick='batal()'> <input class='btn btn-success' style='width:100px' type='button' value='Tidak' onClick='close_popup()'>";

      $("#systolic").jqxNumberInput({ width: '99%', height: 50, value: "100", spinButtons: true, inputMode: 'simple', spinMode: 'advanced', min: 70, max: 200, template: "success", symbolPosition: 'right', symbol: '   ', decimalDigits: 0 });
      $("#diastolic").jqxNumberInput({ width: '99%', height: 50, value: "100", spinButtons: true, inputMode: 'simple', spinMode: 'advanced', min: 70, max: 200, template: "warning", symbolPosition: 'right', symbol: '   ', decimalDigits: 0 });
      $("#pulse").jqxNumberInput({ width: '99%', height: 50, value: "100", spinButtons: true, inputMode: 'simple', spinMode: 'advanced', min: 70, max: 200, template: "danger", symbolPosition: 'right', symbol: '   ', decimalDigits: 0 });


      $("#popup").jqxWindow({
        theme: theme, resizable: false,
        width: 320,
        height: 180,
        isModal: true, autoOpen: false, modalOpacity: 0.4
      });

      $("#btn-batal").click(function(){
        $("#popup_content").html("<div style='text-align:center'><br><br>Batalkan pendaftaran anda? "+btn_batal+"</div>");
        $("#popup").jqxWindow('open');
      });

      $("#btn-daftar").click(function(){
        var tgl = $("#tgl").val();
        if(tgl <= "<?php echo date("d-m-Y") ?>"){
            $("#popup_content").html("<div style='text-align:center'><br><br>Pendaftaran berlaku setelah tanggal <br><?php echo date("d M Y") ?>."+btn+"</div>");
            $("#popup").jqxWindow('open');
        }else{  
            var data = new FormData();
            data.append('code', $('#puskesmas').val());
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
                      $("#popup_content").html("<div style='text-align:center'><br><br>Pendaftaran berhasil dilakukan. <br>"+btn_reload+"</div>");
                        $("#popup").jqxWindow('open');
                        $("#daftar").hide();
                    }else{
                      $("#popup_content").html("<div style='text-align:center'><br><br>Pendaftaran tidak dapat dilakukan. <br>"+btn+"</div>");
                        $("#popup").jqxWindow('open');
                    }
                }
            });
        }
      });
    });

    function close_popup(){
        $("#popup").jqxWindow('close');
    }

    function batal(){
      var btn = "</br></br></br><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup()'>";
      var data = new FormData();
      data.append('username', '{nik}');

      $.ajax({
          cache : false,
          contentType : false,
          processData : false,
          type : 'POST',
          url : '<?php echo base_url()?>hot/kunjungan/daftar_batal',
          data : data,
          success : function(response){
              if(response=="OK"){
                $("#popup_content").html("<div style='text-align:center'><br><br>Pendaftaran berhasil dibatalkan. <br>"+btn+"</div>");
                  $("#popup").jqxWindow('open');
                  $("#terdaftar").hide();
                  $("#daftar").show('fade');
              }else{
                $("#popup_content").html("<div style='text-align:center'><br><br>Permintaan anda tidak dapat dilakukan. <br>"+btn+"</div>");
                  $("#popup").jqxWindow('open');
              }
          }
      });
    }
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

        <div>
          <div class="box-body">
            <div class="row" style="padding:4px">
              <div class="col-xs-4"><b>Nama</b></div>
              <div class="col-xs-8">{nama}</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-4"><b>Usia</b></div>
              <div class="col-xs-8">{jk} / {usia} Tahun</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-12"><button type="button" name="" class="btn btn-warning">Data Kunjungan Sebelumnya</button></div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-12"><b>Kunjungan Baru : </b></div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-4"><b>Tanggal</b></div>
              <div class="col-xs-8">{tgl}</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-4"><b>Jam</b></div>
              <div class="col-xs-8">{waktu}</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-4"><b>TB</b></div>
              <div class="col-xs-6"><input type="number" class="form-control" name="tb" value="{tb}" placeholder="Tinggi Badan"></div>
              <div class="col-xs-2 text-left">cm</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-4"><b>BB</b></div>
              <div class="col-xs-6"><input type="number" class="form-control" name="bb" value="{bb}" placeholder="Berat Badan"></div>
              <div class="col-xs-2 text-left">kg</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-4"><b>BMI</b></div>
              <div class="col-xs-4"><input type="text" class="form-control" name="bmi" value="{bmi}" readonly placeholder="BMI"></div>
              <div class="col-xs-4"><input type="text" class="form-control" name="kategori" value="{kategori}" readonly placeholder="Kategori"></div>
            </div>

            <div class="row" style="padding:4px">
              <div class="col-xs-4 text-center"><b>Systolic</b></div>
              <div class="col-xs-4 text-center"><b>Diastolic</b></div>
              <div class="col-xs-4 text-center"><b>Pulse</b></div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-4 text-center"><div id="systolic"></div></div>
              <div class="col-xs-4 text-center"><div id="diastolic"></div></div>
              <div class="col-xs-4 text-center"><div id="pulse"></div></div>
            </div>

            <div class="row" style="padding:4px">
              <div class="col-xs-12"><b>Pemeriksaan Lab :</b></div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-4"><b>GDS</b></div>
              <div class="col-xs-5"><input type="number" class="form-control" name="gds" placeholder="mb/dl" value="{gds}"></div>
              <div class="col-xs-3 text-center">mg/dl</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-4"><b>GDP</b></div>
              <div class="col-xs-5"><input type="number" class="form-control" name="gdp" placeholder="mb/dl" value="gdp"></div>
              <div class="col-xs-3 text-center">mg/dl</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-4"><b>GDPP</b></div>
              <div class="col-xs-5"><input type="number" class="form-control" name="gdpp" placeholder="mb/dl" value="gdpp"></div>
              <div class="col-xs-3 text-center">mg/dl</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-4"><b>Kolesterol</b></div>
              <div class="col-xs-5"><input type="number" class="form-control" name="kolesterol" placeholder="mb/dl" value="kolesterol"></div>
              <div class="col-xs-3 text-center">mg/dl</div>
            </div>

          </div>
          <div class="box-footer pull-right">
            <button type="button" class="btn btn-primary" id="btn-daftar">Simpan</button>
            <button type="button" class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>hot/kunjungan'">Kembali</button>
          </div>
          <div style="clear:both"></div>
        </div>


        <div id="terdaftar" <?php if(!isset($kunjungan['id_kunjungan'])){ echo "style='display:none'"; } ?>>
          <div class="box-footer pull-right">
            <button type="button" class="btn btn-danger" id="btn-batal">Batalkan Pendaftaran</button>
            <button type="button" class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>hot/kunjungan'">Kembali</button>
          </div>
          <div style="clear:both"></div>
          <div class="box-body">
            <div class="form-group" style="padding:40px 0px 20px 0px ">
            <center>
              <label>Anda tendaftar untuk pemeriksaan <br>Pada tanggal 
              <span style="color:red"><?php if(isset($kunjungan['tgl'])) echo date("d M Y",strtotime($kunjungan['tgl']));?></span>
              <br>Nomor Pendaftaran : 
              <span style="color:red"><?php if(isset($kunjungan['id_kunjungan'])) echo substr($kunjungan['id_kunjungan'],10);?></span>
              </label>
              </center>
            </div>
          </div>
        </div>
        </div>
      </div>
  	</div>
  </div>
</section>

<script>
	$(function () {	

    $("#menu_dashboard").addClass("active");
    $("#menu_hot_kunjungan").addClass("active");

	});
</script>
