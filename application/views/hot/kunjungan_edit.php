<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxslider.js"></script>
<script>
    var bmival;
    var pembagi;
    var bb;
    var tb;

    function bmi(){
      bb = $("#bb").val();
      tb = $("#tb").val();
      pembagi = tb/100;
      bmival  = bb / (pembagi*pembagi);
      bmival  = bmival.toFixed(2);

      $("#bmi").val(bmival);
      if(bmival>=40){
        bmival = 'OBESE III';
      } else if(bmival>=35){
        bmival = 'OBESE II';
      } else if(bmival>=30){
        bmival = 'OBESE I';
      } else if(bmival>=25){
        bmival = 'GIZI LEBIH';
      } else if (bmival>=18.5){
        bmival = 'GIZI BAIK';
      } else{
        bmival = 'GIZI KURANG';
      }
      $("#kategori").val(bmival);
    }

    $(function () { 
      var btn = "</br></br><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup()'>";

      $("#tb").jqxNumberInput({ width: '98%', height: 50, value: "150",textAlign: "center" , inputMode: 'simple', spinMode: 'advanced', template: "success", symbolPosition: 'right', decimalDigits: 0, value: '{tb}' });
      $("#bb").jqxNumberInput({ width: '98%', height: 50, value: "150",textAlign: "center" , inputMode: 'simple', spinMode: 'advanced', template: "warning", symbolPosition: 'right', decimalDigits: 0, value: '{bb}' });
      $("#kolesterol").jqxNumberInput({ width: '98%', height: 50, value: "200", textAlign: "center", inputMode: 'simple', spinMode: 'advanced', template: "warning", symbolPosition: 'right', decimalDigits: 0, value: '{kolesterol}' });
      $("#asamurat").jqxNumberInput({ width: '98%', height: 50, value: "7.7", textAlign: "center" , inputMode: 'simple', spinMode: 'advanced', template: "warning", symbolPosition: 'right', decimalDigits: 1, value: '{asamurat}' });

      $("#respiratory_rate").jqxNumberInput({ width: '98%', height: 50, value: "50", textAlign: "center", inputMode: 'simple', spinMode: 'advanced', template: "warning", symbolPosition: 'right', decimalDigits: 0, value: '{respiratory_rate}' });
      $("#heart_rate").jqxNumberInput({ width: '98%', height: 50, value: "50", textAlign: "center", inputMode: 'simple', spinMode: 'advanced', template: "warning", symbolPosition: 'right', decimalDigits: 0, value: '{heart_rate}' });
      
      $("#gdp").jqxNumberInput({ width: '98%', height: 50, value: "50", textAlign: "center", inputMode: 'simple', spinMode: 'advanced', template: "warning", symbolPosition: 'right', decimalDigits: 0, value: '{gdp}' });
      $("#gds").jqxNumberInput({ width: '98%', height: 50, value: "50", textAlign: "center", inputMode: 'simple', spinMode: 'advanced', template: "warning", symbolPosition: 'right', decimalDigits: 0, value: '{gds}' });
      $("#gdpp").jqxNumberInput({ width: '98%', height: 50, value: "50", textAlign: "center", inputMode: 'simple', spinMode: 'advanced', template: "warning", symbolPosition: 'right', decimalDigits: 0, value: '{gdpp}' });

      $('#systolic').jqxSlider({ width: '100%',template: "warning", tooltip: true, mode: 'fixed', min: 100, max: 200, ticksFrequency: 10, value: {systolic}, step: 1 });
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

      $('#diastolic').jqxSlider({ width: '100%',template: "danger", tooltip: true, mode: 'fixed', min: 60, max: 140, ticksFrequency: 10, value: {diastolic}, step: 1 });
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

      $('#pulse').jqxSlider({ width: '100%',template: "success", tooltip: true, mode: 'fixed', min: 60, max: 200, ticksFrequency: 20, value: {pulse}, step: 1 });
      $("#val_pulse").html($('#pulse').jqxSlider('value'));
      $('#pulse').on('change', function (event) {
          $("#val_pulse").html($(this).jqxSlider('value'));
      });

      $("#popup").jqxWindow({
        theme: theme, resizable: false,
        width: 320,
        height: 160,
        isModal: true, autoOpen: false, modalOpacity: 0.4
      });

      $("#btn-sebelum").click(function(){
        $.ajax({
            type : 'GET',
            url : '<?php echo base_url()?>hot/kunjungan/sebelumnya/{username}/{tgl_kunjungan}',
            success : function(response){
                $("#div-sebelumnya").html(response);
                $("#btn-sebelum").hide();
                $("#div-sebelumnya").show();
            }
        });        
      });



    var csource = function (query, response) {
      var dataAdapter = new $.jqx.dataAdapter
        (
          { 
            datatype: "json",
            datafields: [
                { name: 'sdm_id', type: 'string'},
                { name: 'code', type: 'string'},
                { name: 'sdm_nopeg', type: 'string'},
                { name: 'value', type: 'string'}
            ],
            username: 'username',
            url: "<?php echo base_url();?>hot/dokter/dokter_search/",
            type: "post"
          },
          {
              autoBind: true,
              formatData: function (data) {
                data.qr = query;
                return data;
              },
              loadComplete: function (data) {
                if (data.content.length>0){
                  response($.map(data.content, function (item) {
                      return {
                        label: item.value + ', ' + item.sdm_jenis + '<br>NIK: ' + item.sdm_nopeg,
                        value: item.value + '__' + item.sdm_id
                      }
                  }));
          }
            }
           }
      );
    }
    $("#cl_sdm").jqxInput({ source: csource, width: '99%' });
    $("#cl_sdm").focus(function() {
      $(this).select();
    });
    $("#cl_sdm").keyup(function() {
      $("#cl_sdm").change();
    });
    $("#cl_sdm").change(function() {
      if($(this).val()==""){
        $("#cl_sdm_code").val('');
      }
    });
    $("#cl_sdm").on('select', function (event) {
        if (event.args) {
            var item = event.args.item;
            if (item) {
              var value = item.value.split("__");
                $("#cl_sdm").val(value[0]);
                $("#cl_sdm_code").val(value[1]);
            }
        }
    });

      $("#btn-simpan").click(function(){
        var data = new FormData();
        data.append("waktu",        $("#waktu").val());
        data.append("tb",           $("#tb").val());
        data.append("bb",           $("#bb").val());
        data.append("bmi",          $("#bmi").val());
        data.append("kategori",     $("#kategori").val());
        data.append("systolic",     $("#systolic").jqxSlider('value'));
        data.append("diastolic",    $("#diastolic").jqxSlider('value'));
        data.append("pulse",        $("#pulse").jqxSlider('value'));
        data.append("respiratory_rate", $("#respiratory_rate").val());
        data.append("heart_rate",   $("#heart_rate").val());
        data.append("gds",          $("#gds").val());
        data.append("gdp",          $("#gdp").val());
        data.append("gdpp",         $("#gdpp").val());
        data.append("kolesterol",   $("#kolesterol").val());
        data.append("asamurat",     $("#asamurat").val());
        data.append("is_diabetic",  $("#is_diabetic").is(':checked') ? 1:0);
        data.append("is_ckd",       $("#is_ckd").is(':checked') ? 1:0);
        data.append("is_black",     $("#is_black").is(':checked') ? 1:0);
        data.append("kdsadar",      $("#kdsadar").val());
        data.append("cl_sdm_code",  $("#cl_sdm_code").val());
        data.append("status_antri", "periksa");
        data.append("username_op",  "<?php echo $this->session->userdata('username')?>");

        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()?>hot/kunjungan/simpan/{id_kunjungan}',
            data : data,
            success : function(response){
                $("html, body").animate({ scrollTop: 0 }, "slow");
                if(response=="OK"){
                  $("#popup_content").html("<div style='text-align:center'><br><br>Data berhasil disimpan. "+btn+"</div>");
                  $("#hasil").show("fade");
                  $("#hasil").animate({ scrollBottom: 0 }, "slow");
                  $("#popup").jqxWindow('open');
                }else{
                  $("#popup_content").html("<div style='text-align:center'><br><br>Data tidak dapat disimpan. "+btn+"</div>");
                    $("#popup").jqxWindow('open');
                }
            }
        });
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
        <div id="pengukuran">
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
              <div class="col-xs-12 text-center">
                <button type="button" id="btn-sebelum" class="btn btn-warning">
                  <?php echo $sebelumnya > 0 ? "Lihat ".$sebelumnya." Kunjungan Sebelumnya" : " Status : Pasien Baru "; ?>
                </button>
              </div>
            </div>

            <div class="row" style="padding:4px;display:none" id="div-sebelumnya"></div>

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
              <input type="hidden" id="waktu" value="{waktu}">
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-3 text-right" style="padding-top:5px"><b>Tenaga<br>Medis</b></div>
              <div class="col-xs-9">
                <input type="text" id="cl_sdm" class="form-control" value="{cl_sdm}" style="height: 50px" placeholder="Pilih Dokter">
                <input type="hidden" id="cl_sdm_code" value="{cl_sdm_code}">
              </div>
            </div>
            <div class="row tb" style="padding:4px">
              <div class="col-xs-3 text-right" style="padding-top:14px"><b>TB</b></div>
              <div class="col-xs-1"><input type='button' style="height:51px;width:51px" value='-' class='btn btn-danger minus_tb' field='tb' /></div>
              <div class="col-xs-4" style="left:24px"><div class="tb" id="tb"></div></div>
              <div class="col-xs-1"><input type='button' style="height:51px;width:51px" value='+' class='btn btn-warning plus_tb' style="height:48px;width:44px;" field='tb' /></div> 
              <div class="col-xs-3 text-center" style="padding-top:14px;"> &nbsp; cm</div>
            </div>
            <div class="row bb" style="padding:4px">
              <div class="col-xs-3 text-right" style="padding-top:14px"><b>BB</b></div>
              <div class="col-xs-1"><input type='button' style="height:51px;width:51px" value='-' class='btn btn-danger minus_bb' field='bb' /></div>
              <div class="col-xs-4" style="left:24px"><div class="bb" id="bb"></div></div>
              <div class="col-xs-1"><input type='button' style="height:51px;width:51px" value='+' class='btn btn-warning plus_bb' style="height:48px;width:44px;" field='bb' /></div> 
              <div class="col-xs-3 text-center" style="padding-top:14px;"> &nbsp; kg</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-3 text-right" style="padding-top:15px"><b>BMI</b></div>
              <div class="col-xs-4"><input style="height:50px" type="text" class="form-control" id="bmi" value="{bmi}" readonly placeholder="BMI"></div>
              <div class="col-xs-5"><input style="height:50px" type="text" class="form-control" id="kategori" value="{kategori}" readonly placeholder="Kategori"></div>
            </div>

            <div class="row" style="padding:10px 4px 4px 4px">
              <div class="col-xs-3 text-right"><input type="checkbox" id="is_diabetic" value="1" <?php if($is_diabetic) echo "checked"; ?>></div>
              <div class="col-xs-9"><b>Check if Diabetic</b></div>
            </div>
            <div class="row" style="padding:10px 4px 4px 4px">
              <div class="col-xs-3 text-right"><input type="checkbox" id="is_ckd" value="1" <?php if($is_ckd) echo "checked"; ?>></div>
              <div class="col-xs-9"><b>Check if CKD</b></div>
            </div>
            <div class="row" style="padding:10px 4px 4px 4px">
              <div class="col-xs-3 text-right"><input type="checkbox" id="is_black" value="1" <?php if($is_black) echo "checked"; ?>></div>
              <div class="col-xs-9"><b>Check if Black</b></div>
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
              <div class="col-xs-3 text-right" style="padding-top:15px"><b>Kesadaran</b></div>
              <div class="col-xs-9">
                <select name="kdsadar" id="kdsadar" class="form-control" style="height:50px">
                  <option value='01' <?php if(isset($kdsadar) && $kdsadar=="01") echo "selected"; ?>>Compos mentis</option>
                  <option value='02' <?php if(isset($kdsadar) && $kdsadar=="02") echo "selected"; ?>>Somnolence</option>
                  <option value='03' <?php if(isset($kdsadar) && $kdsadar=="03") echo "selected"; ?>>Sopor</option>
                  <option value='04' <?php if(isset($kdsadar) && $kdsadar=="04") echo "selected"; ?>>Coma</option>
                </select>
              </div>
            </div>            
            <div class="row" style="padding:4px">
              <div class="col-xs-3 text-right" style="padding-top:5px"><b>Respiratory<br>Rate</b></div>
              <div class="col-xs-1"><input type='button' style="height:51px;width:51px" value='-' class='btn btn-danger minus_respiratory_rate'/></div>
              <div class="col-xs-3" style="left:24px"><div class="respiratory_rate" id="respiratory_rate"></div></div>
              <div class="col-xs-1"><input type='button' style="height:51px;width:51px" value='+' class='btn btn-warning plus_respiratory_rate' style="height:48px;width:44px;" /></div> 
              <div class="col-xs-4 text-center" style="padding-top:7px;"> &nbsp; per<br>minute</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-3 text-right" style="padding-top:5px"><b>Heart<br>Rate</b></div>
              <div class="col-xs-1"><input type='button' style="height:51px;width:51px" value='-' class='btn btn-danger minus_heart_rate'/></div>
              <div class="col-xs-3" style="left:24px"><div class="heart_rate" id="heart_rate"></div></div>
              <div class="col-xs-1"><input type='button' style="height:51px;width:51px" value='+' class='btn btn-warning plus_heart_rate' style="height:48px;width:44px;" /></div> 
              <div class="col-xs-4 text-center" style="padding-top:14px;"> &nbsp; bpm</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-12"><b>LAB :</b></div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-3 text-right" style="padding-top:15px"><b>GDS</b></div>
              <div class="col-xs-1"><input type='button' style="height:51px;width:51px" value='-' class='btn btn-danger minus_gds'/></div>
              <div class="col-xs-3" style="left:24px"><div class="gds" id="gds"></div></div>
              <div class="col-xs-1"><input type='button' style="height:51px;width:51px" value='+' class='btn btn-warning plus_gds' style="height:48px;width:44px;" /></div> 
              <div class="col-xs-4 text-center" style="padding-top:14px;"> &nbsp; mg/dl</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-3 text-right" style="padding-top:15px"><b>GDP</b></div>
              <div class="col-xs-1"><input type='button' style="height:51px;width:51px" value='-' class='btn btn-danger minus_gdp'/></div>
              <div class="col-xs-3" style="left:24px"><div class="gdp" id="gdp"></div></div>
              <div class="col-xs-1"><input type='button' style="height:51px;width:51px" value='+' class='btn btn-warning plus_gdp' style="height:48px;width:44px;" /></div> 
              <div class="col-xs-4 text-center" style="padding-top:14px;"> &nbsp; mg/dl</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-3 text-right" style="padding-top:15px"><b>GDPP</b></div>
              <div class="col-xs-1"><input type='button' style="height:51px;width:51px" value='-' class='btn btn-danger minus_gdpp'/></div>
              <div class="col-xs-3" style="left:24px"><div class="gdpp" id="gdpp"></div></div>
              <div class="col-xs-1"><input type='button' style="height:51px;width:51px" value='+' class='btn btn-warning plus_gdpp' style="height:48px;width:44px;" /></div> 
              <div class="col-xs-4 text-center" style="padding-top:14px;"> &nbsp; mg/dl</div>
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-3 text-right" style="padding-top:15px"><b>Kolesterol</b></div>
              <div class="col-xs-1"><input type='button' style="height:51px;width:51px" value='-' class='btn btn-danger minus_kolesterol'/></div>
              <div class="col-xs-3" style="left:24px"><div class="kolesterol" id="kolesterol"></div></div>
              <div class="col-xs-1"><input type='button' style="height:51px;width:51px" value='+' class='btn btn-warning plus_kolesterol' style="height:48px;width:44px;" /></div> 
              <div class="col-xs-4 text-center" style="padding-top:14px;"> &nbsp; mg/dl</div>
            </div>
            <div class="row asamurat" style="padding:4px">
              <div class="col-xs-3 text-right" style="padding-top:14px"><b>Asam Urat</b></div>
              <div class="col-xs-1"><input type='button' style="height:51px;width:51px" value='-' class='btn btn-danger minus_asamurat'/></div>
              <div class="col-xs-3" style="left:24px"><div class="asamurat" id="asamurat"></div></div>
              <div class="col-xs-1"><input type='button' style="height:51px;width:51px" value='+' class='btn btn-warning plus_asamurat' style="height:48px;width:44px;" /></div> 
              <div class="col-xs-4 text-center" style="padding-top:14px;"> &nbsp; mg/dl</div>
            </div>
          </div>
          <div class="box-footer text-center" style="padding:15px">
            <button type="button" class="btn btn-primary" id="btn-simpan">Simpan</button>
            <button type="button" class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>hot/kunjungan'">Kembali</button>
          </div>
        </div>

      </div>
  	</div>

    <div class="col-md-6"  class="row" id="hasil" <?php if(!isset($username_op)){ echo "style='display:none'"; } ?>>
      <div class="box box-warning">
        <div class="box-header">
          <h3 class="box-title">Resume Pasien</h3>
        </div>
        <div id="pengukuran">
          <div class="box-body">
          <div class="row" style="padding:4px">
            <div class="col-xs-4 text-right" style="padding-top:15px"><b>Hasil Pengukuran</b></div>
            <div class="col-xs-8"></div>
          </div>
          <div class="row" style="padding:4px">
            <div class="col-xs-4 text-right" style="padding-top:15px"><b>Dengan</b></div>
            <div class="col-xs-8"></div>
          </div>
          <div class="row" style="padding:4px">
            <div class="col-xs-4 text-right" style="padding-top:15px"><b>Saran Pengobatan</b></div>
            <div class="col-xs-8"></div>
          </div>
          <div class="row" style="padding:4px">
            <div class="col-xs-12"><b>OBAT :</b></div>
          </div>
      </div>
    </div>
  </div>

</section>

<script>
	$(function () {	
    bmi();
    $("#menu_dashboard").addClass("active");
    $("#menu_hot_kunjungan").addClass("active");

    function inc_bb(){
        var currentVal = parseInt($("#bb").val(),10);
        if (!isNaN(currentVal)) {
            $("#bb").val(currentVal + 1);
        }
    }

    $('.btn.btn-warning.plus_bb').on('touchstart mousedown', function(e) {    
        e.preventDefault(); //stops propagation
        $bb=$("#bb");
        $bb.data('timer',setInterval(function(){inc_bb();bmi();}, 100));
    });

    $('.btn.btn-warning.plus_bb').on('touchend mouseup', function(e) {    
        e.preventDefault(); //stops propagation
        var $bb, timer;
        $bb=$("#bb");
        timer = $bb.data('timer');
            
        if (timer !== ''){
            clearInterval(timer);
            $bb.data('timer', '');
        }
    }); 

    function dec_bb(){
        var currentVal = parseInt($("#bb").val(),10);
        if (!isNaN(currentVal)) {
            $("#bb").val(currentVal - 1);
        }
    }  

    $('.btn.btn-danger.minus_bb').on('touchstart mousedown', function(e) {    
        e.preventDefault(); //stops propagation
        $bb=$("#bb");
        $bb.data('timer',setInterval(function(){dec_bb();bmi(); }, 100));
    });

    $('.btn.btn-danger.minus_bb').on('touchend mouseup', function(e) {    
        e.preventDefault(); //stops propagation
        var $bb, timer;
        $bb=$("#bb");
        timer = $bb.data('timer');
            
        if (timer !== ''){
            clearInterval(timer);
            $bb.data('timer', '');
        }
    });

    function inc_tb(){
        var currentVal = parseInt($("#tb").val(),10);
        if (!isNaN(currentVal)) {
            $("#tb").val(currentVal + 1);
        }
    }  

    $('.btn.btn-warning.plus_tb').on('touchstart mousedown', function(e) {    
        e.preventDefault(); //stops propagation
        $tb=$("#tb");
        $tb.data('timer',setInterval(function(){inc_tb();bmi();},100));
    });

    $('.btn.btn-warning.plus_tb').on('touchend mouseup', function(e) {    
        e.preventDefault(); //stops propagation
        var $tb, timer;
        $tb=$("#tb");
        timer = $tb.data('timer');
            
        if (timer !== ''){
            clearInterval(timer);
            $tb.data('timer', '');
        }
    });


    function dec_tb(){
        var currentVal = parseInt($("#tb").val(),10);
        if (!isNaN(currentVal)) {
            $("#tb").val(currentVal - 1);
        }
    } 

    $('.btn.btn-danger.minus_tb').on('touchstart mousedown', function(e) {    
        e.preventDefault(); //stops propagation
        $tb=$("#tb");
        $tb.data('timer',setInterval(function(){dec_tb();bmi();},100));
    });

    $('.btn.btn-danger.minus_tb').on('touchend mouseup', function(e) {    
        e.preventDefault(); //stops propagation
        var $tb, timer;
        $tb=$("#tb");
        timer = $tb.data('timer');
            
        if (timer !== ''){
            clearInterval(timer);
            $tb.data('timer', '');
        }
    });


    function dec_heart_rate(){
        var currentVal = parseInt($("#heart_rate").val(),10);
        if (!isNaN(currentVal)) {
            $("#heart_rate").val(currentVal - 1);
        }
        if(currentVal >= 100){
            $("#heart_rate").css('color','red');
        }else{
            $("#heart_rate").css('color','green');
        }
    } 

    $('.btn.btn-danger.minus_heart_rate').on('touchstart mousedown', function(e) {    
        e.preventDefault(); //stops propagation
        $heart_rate=$("#heart_rate");
        $heart_rate.data('timer',setInterval(function(){dec_heart_rate();},100));
    });

    $('.btn.btn-danger.minus_heart_rate').on('touchend mouseup', function(e) {    
        e.preventDefault(); //stops propagation
        var $heart_rate, timer;
        $heart_rate=$("#heart_rate");
        timer = $heart_rate.data('timer');
            
        if (timer !== ''){
            clearInterval(timer);
            $heart_rate.data('timer', '');
        }
    });

    function inc_respiratory_rate(){
        var currentVal = parseInt($("#respiratory_rate").val(),10);
        if (!isNaN(currentVal)) {
            $("#respiratory_rate").val(currentVal + 1);
        }
        if(currentVal >= 100){
            $("#respiratory_rate").css('color','red');
        }else{
            $("#respiratory_rate").css('color','green');
        }
    }  

    $('.btn.btn-warning.plus_respiratory_rate').on('touchstart mousedown', function(e) {    
        e.preventDefault(); //stops propagation
        $respiratory_rate=$("#respiratory_rate");
        $respiratory_rate.data('timer',setInterval(function(){inc_respiratory_rate();},100));
    });

    $('.btn.btn-warning.plus_respiratory_rate').on('touchend mouseup', function(e) {    
        e.preventDefault(); //stops propagation
        var $respiratory_rate, timer;
        $respiratory_rate=$("#respiratory_rate");
        timer = $respiratory_rate.data('timer');
            
        if (timer !== ''){
            clearInterval(timer);
            $respiratory_rate.data('timer', '');
        }
    });

    function dec_respiratory_rate(){
        var currentVal = parseInt($("#respiratory_rate").val(),10);
        if (!isNaN(currentVal)) {
            $("#respiratory_rate").val(currentVal - 1);
        }
        if(currentVal >= 100){
            $("#respiratory_rate").css('color','red');
        }else{
            $("#respiratory_rate").css('color','green');
        }
    } 

    $('.btn.btn-danger.minus_respiratory_rate').on('touchstart mousedown', function(e) {    
        e.preventDefault(); //stops propagation
        $respiratory_rate=$("#respiratory_rate");
        $respiratory_rate.data('timer',setInterval(function(){dec_respiratory_rate();},100));
    });

    $('.btn.btn-danger.minus_respiratory_rate').on('touchend mouseup', function(e) {    
        e.preventDefault(); //stops propagation
        var $respiratory_rate, timer;
        $respiratory_rate=$("#respiratory_rate");
        timer = $respiratory_rate.data('timer');
            
        if (timer !== ''){
            clearInterval(timer);
            $respiratory_rate.data('timer', '');
        }
    });

    function inc_heart_rate(){
        var currentVal = parseInt($("#heart_rate").val(),10);
        if (!isNaN(currentVal)) {
            $("#heart_rate").val(currentVal + 1);
        }
        if(currentVal >= 100){
            $("#heart_rate").css('color','red');
        }else{
            $("#heart_rate").css('color','green');
        }
    }  

    $('.btn.btn-warning.plus_heart_rate').on('touchstart mousedown', function(e) {    
        e.preventDefault(); //stops propagation
        $heart_rate=$("#heart_rate");
        $heart_rate.data('timer',setInterval(function(){inc_heart_rate();},100));
    });

    $('.btn.btn-warning.plus_heart_rate').on('touchend mouseup', function(e) {    
        e.preventDefault(); //stops propagation
        var $heart_rate, timer;
        $heart_rate=$("#heart_rate");
        timer = $heart_rate.data('timer');
            
        if (timer !== ''){
            clearInterval(timer);
            $heart_rate.data('timer', '');
        }
    });


    function dec_gdp(){
        var currentVal = parseInt($("#gdp").val(),10);
        if (!isNaN(currentVal)) {
            $("#gdp").val(currentVal - 1);
        }
        if(currentVal >= 100){
            $("#gdp").css('color','red');
        }else{
            $("#gdp").css('color','green');
        }
    } 

    $('.btn.btn-danger.minus_gdp').on('touchstart mousedown', function(e) {    
        e.preventDefault(); //stops propagation
        $gdp=$("#gdp");
        $gdp.data('timer',setInterval(function(){dec_gdp();},100));
    });

    $('.btn.btn-danger.minus_gdp').on('touchend mouseup', function(e) {    
        e.preventDefault(); //stops propagation
        var $gdp, timer;
        $gdp=$("#gdp");
        timer = $gdp.data('timer');
            
        if (timer !== ''){
            clearInterval(timer);
            $gdp.data('timer', '');
        }
    });

    function inc_gdp(){
        var currentVal = parseInt($("#gdp").val(),10);
        if (!isNaN(currentVal)) {
            $("#gdp").val(currentVal + 1);
        }
        if(currentVal >= 100){
            $("#gdp").css('color','red');
        }else{
            $("#gdp").css('color','green');
        }
    }  

    $('.btn.btn-warning.plus_gdp').on('touchstart mousedown', function(e) {    
        e.preventDefault(); //stops propagation
        $gdp=$("#gdp");
        $gdp.data('timer',setInterval(function(){inc_gdp();},100));
    });

    $('.btn.btn-warning.plus_gdp').on('touchend mouseup', function(e) {    
        e.preventDefault(); //stops propagation
        var $gdp, timer;
        $gdp=$("#gdp");
        timer = $gdp.data('timer');
            
        if (timer !== ''){
            clearInterval(timer);
            $gdp.data('timer', '');
        }
    });

    function dec_gdpp(){
        var currentVal = parseInt($("#gdpp").val(),10);
        if (!isNaN(currentVal)) {
            $("#gdpp").val(currentVal - 1);
        }
        if(currentVal >= 140){
            $("#gdpp").css('color','red');
        }else{
            $("#gdpp").css('color','green');
        }
    } 

    $('.btn.btn-danger.minus_gdpp').on('touchstart mousedown', function(e) {    
        e.preventDefault(); //stops propagation
        $gdpp=$("#gdpp");
        $gdpp.data('timer',setInterval(function(){dec_gdpp();},100));
    });

    $('.btn.btn-danger.minus_gdpp').on('touchend mouseup', function(e) {    
        e.preventDefault(); //stops propagation
        var $gdpp, timer;
        $gdpp=$("#gdpp");
        timer = $gdpp.data('timer');
            
        if (timer !== ''){
            clearInterval(timer);
            $gdpp.data('timer', '');
        }
    });

    function inc_gdpp(){
        var currentVal = parseInt($("#gdpp").val(),10);
        if (!isNaN(currentVal)) {
            $("#gdpp").val(currentVal + 1);
        }
        if(currentVal >= 140){
            $("#gdpp").css('color','red');
        }else{
            $("#gdpp").css('color','green');
        }
    }  

    $('.btn.btn-warning.plus_gdpp').on('touchstart mousedown', function(e) {    
        e.preventDefault(); //stops propagation
        $gdpp=$("#gdpp");
        $gdpp.data('timer',setInterval(function(){inc_gdpp();},100));
    });

    $('.btn.btn-warning.plus_gdpp').on('touchend mouseup', function(e) {    
        e.preventDefault(); //stops propagation
        var $gdpp, timer;
        $gdpp=$("#gdpp");
        timer = $gdpp.data('timer');
            
        if (timer !== ''){
            clearInterval(timer);
            $gdpp.data('timer', '');
        }
    });

    function dec_gds(){
        var currentVal = parseInt($("#gds").val(),10);
        if (!isNaN(currentVal)) {
            $("#gds").val(currentVal - 1);
        }
        if(currentVal >= 180){
            $("#gds").css('color','red');
        }else{
            $("#gds").css('color','green');
        }
    } 

    $('.btn.btn-danger.minus_gds').on('touchstart mousedown', function(e) {    
        e.preventDefault(); //stops propagation
        $gds=$("#gds");
        $gds.data('timer',setInterval(function(){dec_gds();},100));
    });

    $('.btn.btn-danger.minus_gds').on('touchend mouseup', function(e) {    
        e.preventDefault(); //stops propagation
        var $gds, timer;
        $gds=$("#gds");
        timer = $gds.data('timer');
            
        if (timer !== ''){
            clearInterval(timer);
            $gds.data('timer', '');
        }
    });

    function inc_gds(){
        var currentVal = parseInt($("#gds").val(),10);
        if (!isNaN(currentVal)) {
            $("#gds").val(currentVal + 1);
        }
        if(currentVal >= 180){
            $("#gds").css('color','red');
        }else{
            $("#gds").css('color','green');
        }
    }  

    $('.btn.btn-warning.plus_gds').on('touchstart mousedown', function(e) {    
        e.preventDefault(); //stops propagation
        $gds=$("#gds");
        $gds.data('timer',setInterval(function(){inc_gds();},100));
    });

    $('.btn.btn-warning.plus_gds').on('touchend mouseup', function(e) {    
        e.preventDefault(); //stops propagation
        var $gds, timer;
        $gds=$("#gds");
        timer = $gds.data('timer');
            
        if (timer !== ''){
            clearInterval(timer);
            $gds.data('timer', '');
        }
    });



    function dec_kolesterol(){
        var currentVal = parseInt($("#kolesterol").val(),10);
        if (!isNaN(currentVal)) {
            $("#kolesterol").val(currentVal - 1);
        }
        if(currentVal >= 200){
            $("#kolesterol").css('color','red');
        }else{
            $("#kolesterol").css('color','green');
        }
    } 

    $('.btn.btn-danger.minus_kolesterol').on('touchstart mousedown', function(e) {    
        e.preventDefault(); //stops propagation
        $kolesterol=$("#kolesterol");
        $kolesterol.data('timer',setInterval(function(){dec_kolesterol();},100));
    });

    $('.btn.btn-danger.minus_kolesterol').on('touchend mouseup', function(e) {    
        e.preventDefault(); //stops propagation
        var $kolesterol, timer;
        $kolesterol=$("#kolesterol");
        timer = $kolesterol.data('timer');
            
        if (timer !== ''){
            clearInterval(timer);
            $kolesterol.data('timer', '');
        }
    });

    function inc_kolesterol(){
        var currentVal = parseInt($("#kolesterol").val(),10);
        if (!isNaN(currentVal)) {
            $("#kolesterol").val(currentVal + 1);
        }
        if(currentVal >= 200){
            $("#kolesterol").css('color','red');
        }else{
            $("#kolesterol").css('color','green');
        }
    }  

    $('.btn.btn-warning.plus_kolesterol').on('touchstart mousedown', function(e) {    
        e.preventDefault(); //stops propagation
        $kolesterol=$("#kolesterol");
        $kolesterol.data('timer',setInterval(function(){inc_kolesterol();},100));
    });

    $('.btn.btn-warning.plus_kolesterol').on('touchend mouseup', function(e) {    
        e.preventDefault(); //stops propagation
        var $kolesterol, timer;
        $kolesterol=$("#kolesterol");
        timer = $kolesterol.data('timer');
            
        if (timer !== ''){
            clearInterval(timer);
            $kolesterol.data('timer', '');
        }
    });



    function inc_asamurat(){
        var currentVal = parseFloat($("#asamurat").val(),10);
        if (!isNaN(currentVal)) {
            $("#asamurat").val(currentVal + 0.2);
        }
        if(currentVal >= 7.7 || currentVal <= 2.5){
            $("#asamurat").css('color','red');
        }else{
            $("#asamurat").css('color','green');
        }
    }  

    $('.btn.btn-warning.plus_asamurat').on('touchstart mousedown', function(e) {    
        e.preventDefault(); //stops propagation
        $asamurat=$("#asamurat");
        $asamurat.data('timer',setInterval(function(){inc_asamurat();},100));
    });

    $('.btn.btn-warning.plus_asamurat').on('touchend mouseup', function(e) {    
        e.preventDefault(); //stops propagation
        var $asamurat, timer;
        $asamurat=$("#asamurat");
        timer = $asamurat.data('timer');
            
        if (timer !== ''){
            clearInterval(timer);
            $asamurat.data('timer', '');
        }
    });

    function dec_asamurat(){
        var currentVal = parseFloat($("#asamurat").val(),10);
        if (!isNaN(currentVal)) {
            $("#asamurat").val(currentVal - 0.1);
        }
        if(currentVal >= 7.7 || currentVal <= 2.5){
            $("#asamurat").css('color','red');
        }else{
            $("#asamurat").css('color','green');
        }
    } 

    $('.btn.btn-danger.minus_asamurat').on('touchstart mousedown', function(e) {    
        e.preventDefault(); //stops propagation
        $asamurat=$("#asamurat");
        $asamurat.data('timer',setInterval(function(){dec_asamurat();},100));
    });

    $('.btn.btn-danger.minus_asamurat').on('touchend mouseup', function(e) {    
        e.preventDefault(); //stops propagation
        var $asamurat, timer;
        $asamurat=$("#asamurat");
        timer = $asamurat.data('timer');
            
        if (timer !== ''){
            clearInterval(timer);
            $asamurat.data('timer', '');
        }
    });

	});
</script>
