<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxslider.js"></script>
<script>
    var imtval;
    var pembagi;
    var bb;
    var tb;
    function bmi(){
      bb = $("#bb").val();
      tb = $("#tb").val();
      pembagi = tb/100;
      imtval = bb / (pembagi*pembagi);
      imtval = imtval.toFixed(2);

      $("[name='bmi']").val(imtval);
      if(imtval>=40){
        imtval = 'OBESE CLASS III';
      }
      else if(imtval>=35){
        imtval = 'OBESE CLASS II';
      }
      else if(imtval>=30){
        imtval = 'OBESE CLASS I';
      }
      else if(imtval>=25){
        imtval = 'GIZI LEBIH';
      }
      else if (imtval>=18.5){
        imtval = 'GIZI BAIK';
      }
      else{
        imtval = 'GIZI KURANG';
      }
      $("[name='kategori']").val(imtval);
    }

    $(function () { 
      var btn = "</br></br></br><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup()'>";
      var btn_reload = "</br></br></br><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup();window.location.reload()'>";
      var btn_batal = "</br></br></br><input class='btn btn-danger' style='width:100px' type='button' value='Ya' onClick='batal()'> <input class='btn btn-success' style='width:100px' type='button' value='Tidak' onClick='close_popup()'>";

      $("#tb").jqxNumberInput({ width: '99%', height: 50, value: "150", spinButtons: true, inputMode: 'simple', spinMode: 'advanced', min: 90, max: 200, template: "success", symbolPosition: 'right', symbol: '   ', decimalDigits: 0 });
      $("#bb").jqxNumberInput({ width: '99%', height: 50, value: "50", spinButtons: true, inputMode: 'simple', spinMode: 'advanced', min: 20, max: 120, template: "warning", symbolPosition: 'right', symbol: '   ', decimalDigits: 0 });
      
      $('#bb').on('change', function (event) {
          bmi();
      });
      $('#tb').on('change', function (event) {
          bmi();
      });

      $("#gdp").jqxNumberInput({ width: '99%', height: 50, value: "50", spinButtons: true, inputMode: 'simple', spinMode: 'advanced', min: 20, max: 120, template: "warning", symbolPosition: 'right', symbol: '   ', decimalDigits: 0 });
      $("#gds").jqxNumberInput({ width: '99%', height: 50, value: "50", spinButtons: true, inputMode: 'simple', spinMode: 'advanced', min: 20, max: 120, template: "warning", symbolPosition: 'right', symbol: '   ', decimalDigits: 0 });
      $("#gdpp").jqxNumberInput({ width: '99%', height: 50, value: "50", spinButtons: true, inputMode: 'simple', spinMode: 'advanced', min: 20, max: 120, template: "warning", symbolPosition: 'right', symbol: '   ', decimalDigits: 0 });
      $("#kolesterol").jqxNumberInput({ width: '99%', height: 50, value: "50", spinButtons: true, inputMode: 'simple', spinMode: 'advanced', min: 20, max: 120, template: "warning", symbolPosition: 'right', symbol: '   ', decimalDigits: 0 });

      $('#systolic').jqxSlider({ width: '100%',template: "warning", tooltip: true, mode: 'fixed', min: 100, max: 200, ticksFrequency: 10, value: 130, step: 1 });
      $("#val_systolic").html($('#systolic').jqxSlider('value'));
      $('#systolic').on('change', function (event) {
          var val = $(this).jqxSlider('value');
          if(val > 139){
            val = "<span style='color:red'>"+val+"</span>";
          }else{
            val = "<span style='color:green'>"+val+"</span>";
          }
          $("#val_systolic").html(val);
      });

      $('#diastolic').jqxSlider({ width: '100%',template: "danger", tooltip: true, mode: 'fixed', min: 60, max: 140, ticksFrequency: 10, value: 80, step: 1 });
      $("#val_diastolic").html($('#diastolic').jqxSlider('value'));
      $('#diastolic').on('change', function (event) {
          var val = $(this).jqxSlider('value');
          if(val > 89){
            val = "<span style='color:red'>"+val+"</span>";
          }else{
            val = "<span style='color:green'>"+val+"</span>";
          }
          $("#val_diastolic").html(val);
      });

      $('#pulse').jqxSlider({ width: '100%',template: "success", tooltip: true, mode: 'fixed', min: 60, max: 200, ticksFrequency: 20, value: 80, step: 1 });
      $("#val_pulse").html($('#pulse').jqxSlider('value'));
      $('#pulse').on('change', function (event) {
          $("#val_pulse").html($(this).jqxSlider('value'));
      });


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
              <div class="col-xs-3"><b>Nama</b></div>
              <div class="col-xs-9">{nama}</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-3"><b>Usia</b></div>
              <div class="col-xs-9">{jk} / {usia} Tahun</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-12 text-center"><button type="button" name="" class="btn btn-warning">Data Kunjungan Sebelumnya</button></div>
            </div>
            <div class="row" style="padding:4px;">
              <div class="col-xs-12 text-center" style="background:#E3E3E3;padding:5px"><label>Kunjungan Baru :</label></div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-3 text-right"><b>Tanggal</b></div>
              <div class="col-xs-9">{tgl}</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-3 text-right"><b>Jam</b></div>
              <div class="col-xs-9">{waktu}</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-3 text-right" style="padding-top:15px"><b>TB</b></div>
              <div class="col-xs-6"><div id="tb"></div></div>
              <div class="col-xs-3 text-left" style="padding-top:15px">cm</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-3 text-right" style="padding-top:15px"><b>BB</b></div>
              <div class="col-xs-6"><div id="bb"></div></div>
              <div class="col-xs-3 text-left" style="padding-top:15px">kg</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-3 text-right" style="padding-top:15px"><b>BMI</b></div>
              <div class="col-xs-4"><input style="height:50px" type="text" class="form-control" name="bmi" value="{bmi}" readonly placeholder="BMI"></div>
              <div class="col-xs-5"><input style="height:50px" type="text" class="form-control" name="kategori" value="{kategori}" readonly placeholder="Kategori"></div>
            </div>

            <div style="padding-top:20px;padding-bottom:30px;">
              <div class="row" style="padding:10px;">
                <div class="col-xs-12 text-center"><b>Systolic <span id="val_systolic"></span></b></div>
              </div>
              <div class="row" style="padding:4px">
                <div class="col-xs-12 text-center"><div id='systolic'></div></div>
              </div>
              <div class="row" style="padding:10px">
                <div class="col-xs-12 text-center"><b>Diastolic <span id="val_diastolic"></span></b></div>
              </div>
              <div class="row" style="padding:4px">
                <div class="col-xs-12 text-center"><div id='diastolic'></div></div>
              </div>
              <div class="row" style="padding:10px">
                <div class="col-xs-12 text-center"><b>Pulse <span id="val_pulse"></span></b></div>
              </div>
              <div class="row" style="padding:4px">
                <div class="col-xs-12 text-center"><div id='pulse'></div></div>
              </div>
            </div>

            <div class="row" style="padding:4px">
              <div class="col-xs-12"><b>LAB :</b></div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-3 text-right" style="padding-top:15px"><b>GDS</b></div>
              <div class="col-xs-6"><div id="gds"></div></div>
              <div class="col-xs-2 text-center" style="padding-top:15px">mg/dl</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-3 text-right" style="padding-top:15px"><b>GDP</b></div>
              <div class="col-xs-6"><div id="gdp"></div></div>
              <div class="col-xs-2 text-center" style="padding-top:15px">mg/dl</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-3 text-right" style="padding-top:15px"><b>GDPP</b></div>
              <div class="col-xs-6"><div id="gdpp"></div></div>
              <div class="col-xs-2 text-center" style="padding-top:15px">mg/dl</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-3 text-right" style="padding-top:15px"><b>Kolesterol</b></div>
              <div class="col-xs-6"><div id="kolesterol"></div></div>
              <div class="col-xs-2 text-center" style="padding-top:15px">mg/dl</div>
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
