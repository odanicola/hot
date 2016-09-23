<div id="popup" style="display:none;">
  <div id="popup_title">Hypertension Online Treatment</div><div id="popup_content">{popup}</div>
</div>
<div id="popup_daftar" style="display:none;">
  <div id="popup_title_Daftar">Hypertension Online Treatment</div><div id="popup_content_daftar">{popup}</div>
</div>
<section class="content">
<form action="<?php echo base_url()?>hot/pasien/add" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-6">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
        </div><!-- /.box-header -->

          <div class="box-footer pull-right">
            <button type="button"class="btn btn-primary" id="btn-simpan">Simpan</button>
            <button type="reset" class="btn btn-warning">Ulang</button>
            <button type="button"class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>hot/pasien'">Kembali</button>
          </div>
          <div class="box-body">

            <div class="form-group">
              <label>Nomor RM</label>
              <input type="text" class="form-control" name="cl_pid" id="cl_pid" placeholder="Nomor RM" value="<?php 
                if(set_value('cl_pid')=="" && isset($cl_pid)){
                  echo $cl_pid;
                }else{
                  echo  set_value('cl_pid');
                }
                ?>">
            </div>

            <div class="form-group">
              <label>NIK*</label>
              <input type="text" class="form-control" name="username" id="username" maxlength="16" placeholder="NIK" <?php if($action == "edit") echo "disabled"?> value="<?php 
                if(set_value('username')=="" && isset($username)){
                  echo $username;
                }else{
                  echo  set_value('username');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>BPJS</label>
              <input type="text" class="form-control" name="bpjs" id="bpjs" maxlength="13" placeholder="BPJS" value="<?php 
                if(set_value('bpjs')=="" && isset($bpjs)){
                  echo $bpjs;
                }else{
                  echo  set_value('bpjs');
                }
                ?>">
            </div>

            <div class="form-group">
              <label>Password*</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="<?php 
                if(set_value('password')=="" && isset($password)){
                  echo $password;
                }else{
                  echo  set_value('password');
                }
                ?>">
            </div>

            <div class="form-group">
              <label>Konfirmasi Password</label>
              <input type="password" class="form-control" id="password2" name="password2" placeholder="Konfirmasi Password">
            </div>

            <div class="form-group">
              <label>Nama*</label>
              <input type="text" class="form-control" name="nama" placeholder="Nama" value="<?php 
                if(set_value('nama')=="" && isset($nama)){
                  echo $nama;
                }else{
                  echo  set_value('nama');
                }
                ?>">
            </div>

            <div class="form-group">
                <label>Jenis Kelamin*</label>&nbsp;&nbsp;
                <?php
                  if(set_value('jk')=="" && isset($jk)){
                    $jk = $jk;
                  }else{
                    $jk = set_value('jk');
                  }
                ?>
                <label class="radio-inline">
                  <input type="radio" name="jk" value="L" class="iCheck-helper" <?php echo  ('L' == $jk) ? 'checked' : '' ?>>Pria
                </label>
                <label class="radio-inline">
                  <input type="radio" name="jk" value="P" class="iCheck-helper" <?php echo  ('P' == $jk) ? 'checked' : '' ?>>Wanita
                </label>
            </div>

            <div class="form-group">
              <label>Tinggi Badan</label>
              <input type="number" class="form-control" name="tb" placeholder="Tinggi Badan" value="<?php 
                if(set_value('tb')=="" && isset($tb)){
                  echo $tb;
                }else{
                  echo  set_value('tb');
                }
                ?>">
            </div> 

            <div class="form-group">
              <label>Berat Badan</label>
              <input type="number" class="form-control" name="bb" placeholder="Berat Badan" value="<?php 
                if(set_value('bb')=="" && isset($bb)){
                  echo $bb;
                }else{
                  echo  set_value('bb');
                }
                ?>">
            </div> 

            <div class="form-group">
              <label>No Telepon</label>
              <input type="text" class="form-control" name="phone_number" placeholder="No Telepon" value="<?php 
                if(set_value('phone_number')=="" && isset($phone_number)){
                  echo $phone_number;
                }else{
                  echo  set_value('phone_number');
                }
                ?>">
            </div> 

            <div class="form-group">
                <label>Tanggal Lahir</label>
                <div id='tgl_lahir' name="tgl_lahir" value="<?php
                  if(set_value('tgl_lahir')=="" && isset($tgl_lahir)){
                    $tgl_lahir = strtotime($tgl_lahir);
                  }else{
                    $tgl_lahir = strtotime(set_value('tgl_lahir'));
                  }
                  if($tgl_lahir=="") $tgl_lahir = time();
                  echo date("Y-m-d",$tgl_lahir);
                ?>" >
                </div>
            </div>
           
            <div class="form-group">
              <label>Email*</label>
              <input type="text" class="form-control" name="email" placeholder="Email" value="<?php 
                if(set_value('email')=="" && isset($email)){
                  echo $email;
                }else{
                  echo  set_value('email');
                }
                ?>">
            </div>            
            <div class="form-group">
              <label>Alamat</label>
              <input type="text" class="form-control" name="alamat" placeholder="Alamat" value="<?php 
                if(set_value('alamat')=="" && isset($alamat)){
                  echo $alamat;
                }else{
                  echo  set_value('alamat');
                }
                ?>">
            </div>            
            <div class="form-group">
              <label>Puskesmas</label>
                <select name="code" type="text" class="form-control">
                    <?php foreach($datapuskesmas as $pus) : ?>
                      <?php $select = $pus->code == $code ? 'selected' : '' ?>
                      <option value="<?php echo $pus->code ?>" <?php echo $select ?>><?php echo $pus->value ?></option>
                    <?php endforeach ?>
                </select>
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
    $("#popup").jqxWindow('close');
  }

  function close_popup_daftar(){
      $("#popup_daftar").jqxWindow('close');
  }

  $(function () { 
    $("#tgl_lahir").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height:30});

    $("#menu_dashboard").addClass("active");
    $("#menu_hot_pasien").addClass("active");

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
    $("#cl_pid").jqxInput({ source: csource, width: '99%' });
    $("#cl_pid").focus(function() {
      $(this).select();
    });
    $("#cl_pid").on('select', function (event) {
        if (event.args) {
            var item = event.args.item;
            if (item) {
              var label = item.label.split("<br>");
                $("#cl_pid").val(item.value);
            }
        }
    });

    function validateEmail(email) {
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return expr.test(email);
    };

    $("#btn-simpan").click(function(){
        var data = new FormData();
        var nik  = $("[name='username']").val();
        var bpjs = $("[name='bpjs']").val();

        var btn = "</br></br></br><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup_daftar()'>";


        data.append('username',     $("[name='username']").val());
        data.append('bpjs',         $("[name='bpjs']").val());
        data.append('password',     $("[name='password']").val());
        data.append('password2',    $("[name='password2']").val());
        data.append('nama',         $("[name='nama']").val());
        data.append('jk',           $("[name='jk']:checked").val());
        data.append('tb',           $("[name='tb']").val());
        data.append('bb',           $("[name='bb']").val());
        data.append('phone_number', $("[name='phone_number']").val());
        data.append('tgl_lahir',    $("[name='tgl_lahir']").val());
        data.append('email',        $("[name='email']").val());
        data.append('alamat',       $("[name='alamat']").val());
        data.append('code',         $("[name='code']").val());
        data.append('cl_pid',       $("[name='cl_pid']").val());

        $("#popup_daftar").jqxWindow({
          theme: theme, resizable: false,
          width: 240,
          height: 160,
          isModal: true, autoOpen: false, modalOpacity: 0.2
        });

        if ($("[name='username']").val() == "" || $("[name='bpjs']").val()==""){
            $("#popup_content_daftar").html("<div style='text-align:center'><br>Isi data dengan lengkap."+btn+"</div>");
            $("#popup_daftar").jqxWindow('open');
        }else if($("[name='password']").val() == "" || $("[name='password2']").val()==""){
            $("#popup_content_daftar").html("<div style='text-align:center'><br>Anda belum mengisi password."+btn+"</div>");
            $("#popup_daftar").jqxWindow('open');
        }else if($("[name='password']").val() != $("[name='password2']").val()){
            $("#popup_content_daftar").html("<div style='text-align:center'><br>Password tidak sama."+btn+"</div>");
            $("#popup_daftar").jqxWindow('open');
        }else if($("[name='nama']").val() == ""){
            $("#popup_content_daftar").html("<div style='text-align:center'><br>Anda belum mengisi nama."+btn+"</div>");
            $("#popup_daftar").jqxWindow('open');
        }else if($("[name='phone_number']").val()==""){
            $("#popup_content_daftar").html("<div style='text-align:center'><br>Anda belum mengisi nomor telepon."+btn+"</div>");
            $("#popup_daftar").jqxWindow('open');
        }else if(nik.length!=16){
            $("#popup_content_daftar").html("<div style='text-align:center'><br>Jumlah NIK harus 16 digit."+btn+"</div>");
            $("#popup_daftar").jqxWindow('open');
        }else if(bpjs.length!=13){
            $("#popup_content_daftar").html("<div style='text-align:center'><br>Jumlah BPJS harus 13 digit."+btn+"</div>");
            $("#popup_daftar").jqxWindow('open');
        }else if(!validateEmail($("[name='email']").val())){
            $("#popup_content_daftar").html("<div style='text-align:center'><br>Email tidak valid."+btn+"</div>");
            $("#popup_daftar").jqxWindow('open');
        }else{

        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."hot/pasien/doadd"?>',
            data : data,
            success : function(response){
                if(response=="OK"){
                  $("#popup_content").html("<div style='padding:5px'><br><div style='text-align:center'>Data berhasil disimpan.<br><br><input class='btn btn-danger' style='width:100px' type='button' value='OK' onClick='close_popup()'></div></div>");
                  $("#popup").jqxWindow({
                    theme: theme, resizable: false,
                    width: 250,
                    height: 150,
                    isModal: true, autoOpen: false, modalOpacity: 0.4
                  });
                  $("#popup").jqxWindow('open');
                  window.location.href = "<?php echo base_url().'hot/pasien' ?>";
                }else{
                  $("#popup_content").html("<div style='padding:5px'><br><div style='text-align:center'>"+response+"<br><input class='btn btn-danger' style='width:100px' type='button' value='OK' onClick='close_popup()'></div></div>");
                  $("#popup").jqxWindow({
                    theme: theme, resizable: false,
                    width: 250,
                    height: 150,
                    isModal: true, autoOpen: false, modalOpacity: 0.4
                  });
                  $("#popup").jqxWindow('open');
                }
            }
        });
      }

        return false;
    });

      $("#username").keyup(function(){
        var nik = $("#username").val();
        if(nik.length==16){
          $.get("<?php echo base_url()?>bpjs_api/bpjs_search/nik/"+nik,function(res){
              if(res.metaData.code=="200"){
              
            $("#popup_content").html("<div style='padding:5px'><br>Anda terdaftar sebagai peserta BPJS <br>Faskes : "+res.response.kdProviderPst.nmProvider+" </br> Jenis Peserta "+res.response.jnsPeserta.nama+"</br>Status "+res.response.ketAktif+".</br> Tunggakan Rp. "+res.response.tunggakan+".</br></br><div style='text-align:center'><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup()'></div></div>");
              $("#popup").jqxWindow({
                theme: theme, resizable: false,
                width: 320,
                height: 210,
                isModal: true, autoOpen: false, modalOpacity: 0.4
              });
              $("#popup").jqxWindow('open');

                  $("input[name='bpjs']").val(res.response.noKartu).change();
                  $("input[name='nama']").val(res.response.nama).change();

                  var tgl = res.response.tglLahir.split("-");
                  var date = new Date(tgl[2], (tgl[1]-1), tgl[0]);
                  $("#tgl_lahir").jqxDateTimeInput('setDate', date);

                  if(res.response.noHP!=" " && res.response.noHP!="") $("input[name='phone_number']").val(res.response.noHP).change();
                  if(res.response.sex=="P"){
                    $("select[name='jk']").val("P").change();
                  }else{
                    $("select[name='jk']").val("L").change();
                  }
                $("#pass").focus();
              }
          },"json");
        }

        return false;
      });

      $("#bpjs").keyup(function(){
        var bpjs = $("#bpjs").val();
        if(bpjs.length==13){
          $.get("<?php echo base_url()?>bpjs_api/bpjs_search/bpjs/"+bpjs,function(res){
              if(res.metaData.code=="200"){

            $("#popup_content").html("<div style='padding:5px'><br>Anda terdaftar sebagai peserta BPJS <br>Faskes : "+res.response.kdProviderPst.nmProvider+" </br> Jenis Peserta "+res.response.jnsPeserta.nama+"</br>Status "+res.response.ketAktif+".</br> Tunggakan Rp. "+res.response.tunggakan+".</br></br><div style='text-align:center'><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup()'></div></div>");
              $("#popup").jqxWindow({
                theme: theme, resizable: false,
                width: 320,
                height: 210,
                isModal: true, autoOpen: false, modalOpacity: 0.4
              });
              $("#popup").jqxWindow('open');

                  $("input[name='username']").val(res.response.noKTP).change();
                  $("input[name='nama']").val(res.response.nama).change();

                  var tgl = res.response.tglLahir.split("-");
                  var date = new Date(tgl[2], (tgl[1]-1), tgl[0]);
                  $("#tgl_lahir").jqxDateTimeInput('setDate', date);

                  if(res.response.noHP!=" " && res.response.noHP!="") $("input[name='phone_number']").val(res.response.noHP).change();
                  if(res.response.sex=="P"){
                    $("select[name='jk']").val("P").change();
                  }else{
                    $("select[name='jk']").val("L").change();
                  }
                $("#pass").focus();
              }
          },"json");
        }
    });


  });
</script>
