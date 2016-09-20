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
      var btn = "</br></br></br><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup()'>";

      $("#tb").jqxNumberInput({ width: '99%', height: 50, value: "150", spinButtons: true, inputMode: 'simple', spinMode: 'advanced', min: 90, max: 200, template: "success", symbolPosition: 'right', symbol: '   ', decimalDigits: 0, value: '{tb}' });
      $("#bb").jqxNumberInput({ width: '99%', height: 50, value: "50", inputMode: 'simple', spinMode: 'advanced', min: 20, max: 120, template: "warning", symbolPosition: 'right', symbol: '   ', decimalDigits: 0, value: '{bb}' });
      
      $('#tb').on('change', function (event) { bmi(); });
      $('#bb').on('change', function (event) { bmi(); });

      $("#gdp").jqxNumberInput({ width: '99%', height: 50, value: "50", spinButtons: true, inputMode: 'simple', spinMode: 'advanced', min: 20, max: 120, template: "warning", symbolPosition: 'right', symbol: '   ', decimalDigits: 0, value: '{gdp}' });
      $("#gds").jqxNumberInput({ width: '99%', height: 50, value: "50", spinButtons: true, inputMode: 'simple', spinMode: 'advanced', min: 20, max: 120, template: "warning", symbolPosition: 'right', symbol: '   ', decimalDigits: 0, value: '{gds}' });
      $("#gdpp").jqxNumberInput({ width: '99%', height: 50, value: "50", spinButtons: true, inputMode: 'simple', spinMode: 'advanced', min: 20, max: 120, template: "warning", symbolPosition: 'right', symbol: '   ', decimalDigits: 0, value: '{gdpp}' });
      $("#kolesterol").jqxNumberInput({ width: '99%', height: 50, value: "50", spinButtons: true, inputMode: 'simple', spinMode: 'advanced', min: 20, max: 120, template: "warning", symbolPosition: 'right', symbol: '   ', decimalDigits: 0, value: '{kolesterol}' });

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

      $("#btn-simpan").click(function(){
        var data = new FormData();
        data.append("waktu", $("#waktu").val());
        data.append("tb", $("#tb").val());
        data.append("bb", $("#bb").val());
        data.append("bmi", $("#bmi").val());
        data.append("kategori", $("#kategori").val());
        data.append("systolic", $("#systolic").jqxSlider('value'));
        data.append("diastolic", $("#diastolic").jqxSlider('value'));
        data.append("pulse", $("#pulse").jqxSlider('value'));
        data.append("gds", $("#gds").val());
        data.append("gdp", $("#gdp").val());
        data.append("gdpp", $("#gdpp").val());
        data.append("kolesterol", $("#kolesterol").val());
        data.append("is_diabetic", $("#is_diabetic").is(':checked') ? 1:0);
        data.append("is_ckd", $("#is_ckd").is(':checked') ? 1:0);
        data.append("is_black", $("#is_black").is(':checked') ? 1:0);
        data.append("status_antri", "periksa");
        data.append("username_op", "<?php echo $this->session->userdata('username')?>");

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
              <input type="hidden" id="waktu" value="{waktu}">
            </div>
            <div class="row" style="padding:4px">
              <div class="col-xs-3 text-right" style="padding-top:15px"><b>TB</b></div>
              <div class="col-xs-6"><div id="tb"></div></div>
              <div class="col-xs-3 text-left" style="padding-top:15px">cm</div>
            </div>
<!--             <div class="row" style="padding:4px">
              <div class="col-xs-3 text-right" style="padding-top:15px"><b>BB</b></div>
              <div class="col-xs-6"><div id="bb"></div></div>
              <div class="col-xs-3 text-left" style="padding-top:15px">kg</div>
            </div> -->
            
            <div class="row" style="padding:4px">
              <div class="col-xs-3 text-right" style="padding-top:2px"><b>BB</b>
                <input type='button' value='-' class='btn btn-success minus_bb' style="height:48px;width:44px" field='bb' />
              </div>
              <div class="col-xs-6">
                <!-- <input type='button' value='-' class='minus_bb' style="height:31px;width:31px" field='bb' /> -->
                <input class='qty' id="bb">
                <!-- <input type='text' name='bb' value='0' size="8" style="padding: 5px" class='qty' /> -->
                <!-- <input type='button' value='+' class='plus_bb'  style="height:31px;width:31px" field='bb' /> -->
              </div>
              <div class="col-xs-3 text-left" style="padding-top:2px">  
                <input type='button' value='+' class='btn btn-success plus_bb' style="height:48px;width:44px;" field='bb' />kg
              </div>
            </div>

<!--             <div class="row" style="padding:4px">
              <div class="col-xs-3 text-right" style="padding-top:15px"><b>BB</b></div>
              <div class="col-xs-6">
                <input type='button' value='-' class='btn btn-success minus_bb' style="height:31px;width:31px" field='bb' />
                <input type='text' name='bb' value='0' size="8" style="padding: 5px" class='qty' />
                <input type='button' value='+' class='btn btn-success plus_bb'  style="height:31px;width:31px" field='bb' />
              </div>
              <div class="col-xs-3 text-left" style="padding-top:15px">&nbsp;kg</div>
            </div> -->

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
          <div class="box-footer text-center" style="padding:15px">
            <button type="button" class="btn btn-primary" id="btn-simpan">Simpan</button>
            <button type="button" class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>hot/kunjungan'">Kembali</button>
          </div>
        </div>


        <div id="hasil" <?php if(!isset($username_op)){ echo "style='display:none'"; } ?>>
          <div style="clear:both"></div>
          <div class="box-body">
            <div class="row" style="padding:4px;">
              <div class="col-xs-12 text-center" style="background:#E3E3E3;padding:5px"><label>Resume Pasien :</label></div>
            </div>
          </div>

          <div class="row" style="padding:4px">
            <div class="col-xs-4 text-right" style="padding-top:15px"><b>Hasil Pengukuran</b></div>
            <div class="col-xs-8"><div id="gds"></div></div>
          </div>
          <div class="row" style="padding:4px">
            <div class="col-xs-4 text-right" style="padding-top:15px"><b>Dengan</b></div>
            <div class="col-xs-8"><div id="gdp"></div></div>
          </div>
          <div class="row" style="padding:4px">
            <div class="col-xs-4 text-right" style="padding-top:15px"><b>Saran Pengobatan</b></div>
            <div class="col-xs-8"><div id="gdpp"></div></div>
          </div>
          <div class="row" style="padding:4px">
            <div class="col-xs-12"><b>OBAT :</b></div>
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

    $('.btn.btn-success.plus_bb').click(function(e){
        e.preventDefault();
        fieldName = $(this).attr('field');
        var currentVal = parseInt($('input[name='+fieldName+']').val());
        
        if (!isNaN(currentVal)) {
            $('input[name='+fieldName+']').val(currentVal + 1);
        } else {
            $('input[name='+fieldName+']').val(0);
        }
    });

    $(".btn.btn-success.minus_bb").click(function(e) {
        e.preventDefault();
        fieldName = $(this).attr('field');
        var currentVal = parseInt($('input[name='+fieldName+']').val());
      
        if (!isNaN(currentVal) && currentVal > 0) {
            $('input[name='+fieldName+']').val(currentVal - 1);
        } else {
            $('input[name='+fieldName+']').val(0);
        }
    });


	});
</script>
