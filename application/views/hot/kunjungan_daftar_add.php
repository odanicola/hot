<script>
    $(function () { 
      var date = new Date(<?php echo date("Y").", ".(date("n")-1).", ".(date("j")+1) ?>);
      var btn = "</br></br></br><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup()'>";
      var btn_reload = "</br></br></br><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup_reload();'>";
      var btn_batal = "</br></br></br><input class='btn btn-danger' style='width:100px' type='button' value='Ya' onClick='batal()'> <input class='btn btn-success' style='width:100px' type='button' value='Tidak' onClick='close_popup()'>";

      $("#tgl").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height: '30px'});
      $("#tgl").jqxDateTimeInput('setDate', date);

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


      <?php if($this->session->userdata('level')!="pasien"){ ?>
      var csource = function (query, response) {
        var dataAdapter = new $.jqx.dataAdapter
          (
            { 
              datatype: "json",
              datafields: [
                  { name: 'username', type: 'string'},
                  { name: 'nama', type: 'string'},
                  { name: 'bpjs', type: 'string'}
              ],
              username: 'username',
              url: "<?php echo site_url('hot/kunjungan/json_autocomplete'); ?>",
              type: "post"
            },
            {
                autoBind: true,
                formatData: function (data) {
                  data.nama = query;
                  return data;
                },
                loadComplete: function (data) {
                  if (data.length>0){
                    response($.map(data, function (item) {
                        return {
                          label: item.nama,
                          name: item.name,
                          value: item.username
                        }
                    }));                                
            }
              }
             }
        );
      }
      $("#username").jqxInput({ source: csource, width: '99%' });
      $("#username").focus(function() {
        $(this).select();
      });
      $("#username").on('select', function (event) {
          if (event.args) {
              var item = event.args.item;
              if (item) {
                var label = item.label.split("<br>");
                  $("#username").val(label[0]);
                  $("#nik").val(item.value);
              }
          }
      });
    <?php } ?>

      $("#btn-daftar").click(function(){
        var tgl = $("#tgl").val();
       <?php if($this->session->userdata('level')!="pasien"){ ?> if($('#nik').val()==""){
            $("#popup_content").html("<div style='text-align:center'><br><br>Tentukan pasien terlebih dahulu."+btn+"</div>");
            $("#popup").jqxWindow('open');
        }
        else <?php } ?>if(tgl <= "<?php echo date("d-m-Y") ?>"){
            $("#popup_content").html("<div style='text-align:center'><br><br>Pendaftaran berlaku setelah tanggal <br><?php echo date("d M Y") ?>."+btn+"</div>");
            $("#popup").jqxWindow('open');
        }else{  
            var data = new FormData();
            data.append('code', $('#puskesmas').val());
           <?php if($this->session->userdata('level')!="pasien"){ ?>
            data.append('username', $('#nik').val());
           <?php }else{ ?>
            data.append('username', '{nik}');
          <?php } ?>
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

    function close_popup_reload(){
        $("#popup").jqxWindow('close');
        window.location.href = "<?php echo base_url()?>hot/kunjungan/daftar";
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

        <div id="daftar" <?php if(isset($kunjungan['id_kunjungan'])){ echo "style='display:none'"; } ?>>
          <div class="box-footer pull-right">
            <button type="button" class="btn btn-primary" id="btn-daftar">Daftar</button>
            <button type="button" class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>hot/kunjungan'">Kembali</button>
          </div>
          <div style="clear:both"></div>
          <div class="box-body">

            <?php if($this->session->userdata('level')!="pasien"){ ?>
              <div class="form-group">
                <label>Tentukan Pasien *</label>
                <input type="text" id="username" class="form-control" autocomplete="off">
                <input type="hidden" id="nik">
              </div>
            <?php } ?>

            <div class="form-group">
              <label>Tentukan Tanggal *</label>
               <div id='tgl' name="tgl" ></div>
            </div>

            <div class="form-group">
              <label>Puskesmas</label>
              <div>
                <select name="puskesmas" id="puskesmas" class="form-control">
                  <?php foreach ($datapuskesmas as $pus ) { ?>
                  <?php $select = substr($pus->code,1)==$code ? 'selected=selected' : '' ?>
                    <option value="<?php echo substr($pus->code,1); ?>" <?php echo $select ?>><?php echo $pus->value; ?></option>
                  <?php } ;?>
                </select>
              </div>
            </div>
          </div>
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
