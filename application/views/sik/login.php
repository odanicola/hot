<div id="popup" style="display:none;">
  <div id="popup_title">Hypertension Online Treatment</div><div id="popup_content">{popup}</div>
</div>
<div id="popup_daftar" style="display:none;">
  <div id="popup_title_Daftar">Hypertension Online Treatment</div><div id="popup_content_daftar">{popup}</div>
</div>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" id="tbl-login">
  <form action="<?php echo base_url()?>morganisasi/login" method="POST" id="form_puskesmas">
  <tbody><tr>
    <td>
      <table border="0" cellpadding="0" cellspacing="0" width="80%">
      <tbody><tr>
        <td colspan="2" align="center" height="20">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align="center"><img src="<?php echo base_url()?>public/themes/sik/dist/img/epuskesmas2.png">
        </td>
      </tr>      
      <tr>
        <td colspan="2" style="font-family:Calibri;font-size:16pt;color:#FFFFFF;font-style:italic;text-shadow:1px 1px 1px #000;padding:0 0 20px 0px;" align="center" height="30">HYPERTENSION ONLINE TREATMENT</td>
      </tr>
      <tr><td colspan="2" height="10"></td></tr>
      <tr>
        <td class="username-bg">NIK / BPJS</td>
        <td class="textfield-bg"><input placeholder=" NIK / BPJS" name="username" size="20" class="input" autocomplete="off" type="text"></td>
      </tr>
      <tr><td colspan="2" height="10"></td></tr>
      <tr>
        <td class="username-bg">Password</td>
        <td class="textfield-bg"><input  placeholder=" Password" name="password" size="20" class="input" type="password"></td>
      </tr>
      <tr><td colspan="2" height="10"></td></tr>
      <tr>
        <td align="right"><input value="Login" class="btn-green" id="submit" type="submit"></td>
        <td align="right"><input value="Sign Up" class="btn-green" id="signup" type="button"></td>
      </tr>
      <tr>
        <td colspan="2" style="font-size:9pt;font-family:Calibri;color:#FFFFFF;padding:10px 20px;">Silahkan anda login terlebih dahulu, untuk menggunakan fasilitas <i>infoKes</i><br>&nbsp;</td>
      </tr>
      </tbody></table>
    </td>
  </tr>
  </tbody>
  </form>
</table>

<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" id="tbl-register1" style="display:none">
  <tbody><tr>
    <td>
      <table border="0" cellpadding="0" cellspacing="0" width="80%">
      <tbody><tr>
        <td colspan="2" align="center" height="20">&nbsp;</td>
      </tr>
      <tr>
        <td align="center" colspan="2">
          <div style="width:25%;position:relative;float:left;text-align:right">
            <img src="<?php echo base_url()?>public/themes/sik/dist/img/epuskesmas2.png" height="40">
          </div>
          <div style="width:70%;position:relative;float:left;font-family:Calibri;font-size:12pt;color:#FFFFFF;font-style:italic;text-shadow:1px 1px 1px #000;padding-top:10px">HYPERTENSION ONLINE TREATMENT
          </div>
        </td>
      </tr>
      <tr><td colspan="2" height="20">&nbsp;</td></tr>
      <tr>
        <td class="username-bg">NIK</td>
        <td class="textfield-bg"><input placeholder=" NIK" id="nik" maxlength="16" class="input" autocomplete="off" type="text"></td>
      </tr>
      <tr><td colspan="2" height="10"></td></tr>
      <tr>
        <td class="username-bg">BPJS</td>
        <td class="textfield-bg"><input placeholder=" BPJS" id="bpjs" maxlength="13" class="input" autocomplete="off" type="text"></td>
      </tr>
      <tr><td colspan="2" height="10"></td></tr>
      <tr>
        <td align="right"><input value="Kembali" class="btn-green" id="submit2" type="button"></td>
        <td align="right"><input value="Daftar" class="btn-green" id="signup2" type="button"></td>
      </tr>
      <tr><td colspan="2" height="20">&nbsp;</td></tr>
      <tr>
        <td colspan="2" align="center" style="font-size:9pt;font-family:Calibri;color:#FFFFFF;padding:10px 20px;">Silahkan isi Nomor NIK atau Nomor BPJS anda dengan benar.</td>
      </tr>
      </tbody></table>
    </td>
  </tr>
  </tbody>
</table>

<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" id="tbl-register2" style="display:none">
  <tbody><tr>
    <td>
      <form method="POST" id="form_signup">
      <table border="0" cellpadding="0" cellspacing="0" width="80%">
      <tbody><tr>
        <td colspan="2" align="center" height="20">&nbsp;</td>
      </tr>
      <tr>
        <td align="center" colspan="2">
          <div style="width:25%;position:relative;float:left;text-align:right">
            <img src="<?php echo base_url()?>public/themes/sik/dist/img/epuskesmas2.png" height="40">
          </div>
          <div style="width:70%;position:relative;float:left;font-family:Calibri;font-size:12pt;color:#FFFFFF;font-style:italic;text-shadow:1px 1px 1px #000;padding-top:10px">HYPERTENSION ONLINE TREATMENT
          </div>
        </td>
      </tr>
      <tr><td colspan="2" height="20">&nbsp;</td></tr>
      <tr>
        <td class="username-bg">NIK</td>
        <td class="textfield-bg"><input placeholder=" NIK" name="nik" maxlength="16" class="input" autocomplete="off" type="text"></td>
      </tr>
      <tr>
        <td class="username-bg">BPJS</td>
        <td class="textfield-bg"><input placeholder=" BPJS" name="bpjs" maxlength="13" class="input" autocomplete="off" type="text"></td>
      </tr>
      <tr>
        <td class="username-bg">Password</td>
        <td class="textfield-bg"><input  id="pass" class="input" autocomplete="off" type="password"></td>
      </tr>
      <tr>
        <td class="username-bg">Konfirmasi</td>
        <td class="textfield-bg"><input  id="pass2" class="input" autocomplete="off" type="password"></td>
      </tr>
      <tr><td colspan="2" height="10"></td></tr>
      <tr>
        <td class="username-bg">Nama *</td>
        <td class="textfield-bg"><input placeholder=" Nama Lengkap" name="nama" size="20" class="input" autocomplete="off" type="text"></td>
      </tr>
      <tr>
        <td class="username-bg">Jenis Kelamin</td>
        <td class="textfield-bg">
        <select name="jk" class="input">
          <option value="L">Laki-laki</option>
          <option value="P">Perempuan</option>
        </select>
        </td>
      </tr>
      <tr>
        <td class="username-bg">Tgl Lahir</td>
        <td class="textfield-bg">
        <select name="tgl" class="input-tgl">
        <?php
        for ($i=1;$i<=31;$i++) {
          echo "<option value='".$i."'>".$i."</option>";
        }
        ?>
        </select>
        <select name="bln" class="input-bln">
        <?php
        foreach ($bulan as $key=>$val) {
          echo "<option value='".$key."'>".$val."</option>";
        }
        ?>
        </select>
        <select name="thn" class="input-thn">
        <?php
        for ($i=(date("Y")-18);$i>=(date("Y")-100);$i--) {
          echo "<option value='".$i."'>".$i."</option>";
        }
        ?>
        </select>
        </td>
      </tr>
      <tr>
        <td class="username-bg">No Telepon *</td>
        <td class="textfield-bg"><input placeholder=" Nomor Telepon" name="phone_number" size="20" class="input" autocomplete="off" type="text"></td>
      </tr>
      <tr>
        <td class="username-bg">Email</td>
        <td class="textfield-bg"><input placeholder=" Email" name="email" size="20" class="input" autocomplete="off" type="text"></td>
      </tr>
      <tr>
        <td class="username-bg">Alamat</td>
        <td class="textfield-bg"><input placeholder=" Alamat" name="alamat" size="20" class="input" autocomplete="off" type="text"></td>
      </tr>
      <tr>
        <td class="username-bg">Puskesmas</td>
        <td class="textfield-bg">
          <select name="puskesmas" id="puskesmas" class="input">
              <?php foreach ($datapuskesmas as $pus ) { ;?>
              <?php $select = $pus->code ? 'selected=selected' : '' ?>
                <option value="<?php echo $pus->code;$pus->value; ?>,<?php echo $pus->value ?>" <?php echo $select ?>><?php echo $pus->value; ?></option>
              <?php } ;?>
          </select>
      </tr>     
      <tr><td colspan="2" height="10"></td></tr>
      <tr>
        <td align="right"><input value="Batal" class="btn-green" id="batal" type="button"></td>
        <td align="right"><input value="Daftar" class="btn-green" id="signup3" type="button"></td>
      </tr>
      <tr><td colspan="2" height="20">&nbsp;</td></tr>
      <tr>
        <td colspan="2" align="center" style="font-size:9pt;font-family:Calibri;color:#FFFFFF;padding:10px 20px;">Silahkan isi data anda dengan benar.</td>
      </tr>
      </tbody></table>
      </form>
    </td>
  </tr>
  </tbody>
</table>

<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" id="tbl-success" style="display:none">
  <tbody><tr>
    <td>
      <table border="0" cellpadding="0" cellspacing="0" width="80%">
      <tbody><tr>
        <td colspan="2" align="center" height="20">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align="center"><img src="<?php echo base_url()?>public/themes/sik/dist/img/epuskesmas2.png">
        </td>
      </tr>      
      <tr>
        <td colspan="2" style="font-family:Calibri;font-size:16pt;color:#FFFFFF;font-style:italic;text-shadow:1px 1px 1px #000;padding:0 0 20px 0px;" align="center" height="30">HYPERTENSION ONLINE TREATMENT</td>
      </tr>
      <tr><td colspan="2" height="10"></td></tr>
      <tr><td colspan="2" height="10"></td></tr>
      <tr>
        <td align="center" height="20" class="username-bg">Terimakasih, anda berhasil terdaftar.</td>
      </tr>
      <tr><td colspan="2" height="10"></td></tr>
      <tr>
        <td colspan="2" align="right"><input value="Login" class="btn-green" id="login2" type="button"></td>
      </tr>
      <tr><td colspan="2" height="20">&nbsp;</td></tr>
      </tbody></table>
    </td>
  </tr>
  </tbody>
</table>

<script>
    function close_popup(){
        $("#popup").jqxWindow('close');
    }

    function close_popup_daftar(){
        $("#popup_daftar").jqxWindow('close');
    }

    $(document).ready(function(){
      var theme = "bootstrap";

      $('#submit').click(function(){
        $(".body-login-table").hide("fade");

        $("#popup_content").html("<div style='text-align:center'><br><br>. . . . . . . .</div>");
        $("#popup").jqxWindow({
          theme: theme, resizable: false,
          width: 300,
          height: 125,
          isModal: true, autoOpen: false, modalOpacity: 0.4
        });
        $("#popup").jqxWindow('open');
      });

      $('#signup').click(function(){
        $("#tbl-login").hide();
        $("#tbl-register1").show("fade");
      });

      $('#submit2').click(function(){
        $("#tbl-register1").hide();
        $("#tbl-login").show("fade");
      });

      $('#batal').click(function(){
        $("#tbl-register2").hide();
        $("#tbl-login").show("fade");
      });

      $('#login2').click(function(){
        $("#tbl-success").hide();
        $("#tbl-login").show("fade");
      });

      $('#signup2').click(function(){
        var btn = "</br></br></br><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup()'>";

        if($("#nik").val()=="" && $("#bpjs").val()==""){
          $("#popup_content").html("<div style='text-align:center'><br><br>Anda harus mengisi Nomor NIK atau BPJS."+btn+"</div>");
          $("#popup").jqxWindow({
            theme: theme, resizable: false,
            width: 320,
            height: 180,
            isModal: true, autoOpen: false, modalOpacity: 0.4
          });
          $("#popup").jqxWindow('open');
        }else{
          var nik = $("#nik").val();
          var bpjs = $("#bpjs").val();
          if(nik.length==16 || bpjs.length==13){
            $("#tbl-register1").hide();
            $("#tbl-register2").show("fade");
          }else{
            $("#popup_content").html("<div style='text-align:center'><br><br>Anda tidak mengisi Nomor NIK atau BPJS dengan benar."+btn+"</div>");
            $("#popup").jqxWindow({
              theme: theme, resizable: false,
              width: 320,
              height: 180,
              isModal: true, autoOpen: false, modalOpacity: 0.4
            });
            $("#popup").jqxWindow('open');
          }
        }
      });

        $("#signup3").click(function(){
            var data = new FormData();
            $('#biodata_notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
            $('#biodata_notice').show();

            var tgl   = $("[name='tgl']").val();
            var bln   = $("[name='bln']").val();
            var thn   = $("[name='thn']").val();
            var tgl_lahir = tgl+"-"+bln+"-"+thn;

            var puskesmas = $("[name='puskesmas']").val();
            var fields  = puskesmas.split(",");
            var kode    = fields[0];
            var namapus = fields[1];

            var kodepus = kode.substring(11, 1);
            var btn = "</br></br></br><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup_daftar()'>";

            data.append('username',               $("[name='nik']").val());
            data.append('bpjs',                   $("[name='bpjs']").val());
            data.append('pass',                   $("#pass").val());
            data.append('nama',                   $("[name='nama']").val());
            data.append('jk',                     $("[name='jk']").val());
            data.append('tgl_lahir',              tgl_lahir);
            data.append('phone_number',           $("[name='phone_number']").val());
            data.append('email',                  $("[name='email']").val());
            data.append('alamat',                 $("[name='alamat']").val());
            data.append('code',                   kodepus);

            $("#popup_daftar").jqxWindow({
              theme: theme, resizable: false,
              width: 300,
              height: 180,
              isModal: true, autoOpen: false, modalOpacity: 0.4
            });

            if ($("[name='nik']").val() == "" || $("[name='bpjs']").val()==""){
                $("#popup_content_daftar").html("<div style='text-align:center'><br>Isi data dengan lengkap."+btn+"</div>");
                $("#popup_daftar").jqxWindow('open');
            }else if($("#pass").val() == "" || $("#pass2").val()==""){
                $("#popup_content_daftar").html("<div style='text-align:center'><br>Anda belum mengisi password."+btn+"</div>");
                $("#popup_daftar").jqxWindow('open');
            }else if($("#pass").val() != $("#pass2").val()){
                $("#popup_content_daftar").html("<div style='text-align:center'><br>Password tidak sama."+btn+"</div>");
                $("#popup_daftar").jqxWindow('open');
            }else if($("[name='nama']").val() == ""){
                $("#popup_content_daftar").html("<div style='text-align:center'><br>Anda belum mengisi nama."+btn+"</div>");
                $("#popup_daftar").jqxWindow('open');
            }else if($("[name='phone_number']").val()==""){
                $("#popup_content_daftar").html("<div style='text-align:center'><br>Anda belum mengisi nomor telepon."+btn+"</div>");
                $("#popup_daftar").jqxWindow('open');
            }else{
                $.ajax({
                    cache : false,
                    contentType : false,
                    processData : false,
                    type : 'POST',
                    url : '<?php echo base_url()."morganisasi/daftar" ?>',
                    data : data,
                    success : function(response){
                      if(response=="OK"){
                          $("#tbl-register2").hide();
                          $("#tbl-success").show("fade");
                      }else if(response=="NOTOK"){
                        $("#popup_content_daftar").html("<div style='text-align:center'><br>NIK atau BPJS sudah terdaftar.</br></br></br><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup_daftar()'></div>");
                          $("#popup_daftar").jqxWindow('open');
                      }
                    }
                });
            }

            return false;
        });

      $("#nik").keyup(function(){
        var nik = $("#nik").val();
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

                  $("#tbl-register1").hide();
                  $("#tbl-register2").show("fade");

                  $("input[name='nik']").val(nik).change();
                  $("input[name='bpjs']").val(res.response.noKartu).change();
                  $("input[name='nama']").val(res.response.nama).change();

                  var tglLhr = res.response.tglLahir.split("-");

                  var tgl   = tglLhr[2];
                  var bln   = tglLhr[1];
                  var bulan = bln.substring(1, 2);
                  var thn   = tglLhr[0];

                  $("select[name='thn']").val(tglLhr[2]).change();
                  $("select[name='bln']").val(bulan).change();
                  $("select[name='tgl']").val(tglLhr[0]).change();

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

                  $("#tbl-register1").hide();
                  $("#tbl-register2").show("fade");

                  $("input[name='bpjs']").val(bpjs).change();
                  $("input[name='nik']").val(res.response.noKTP).change();
                  $("input[name='nama']").val(res.response.nama).change();

                  var tglLhr = res.response.tglLahir.split("-");

                  var tgl   = tglLhr[2];
                  var bln   = tglLhr[1];
                  var bulan = bln.substring(1, 2);
                  var thn   = tglLhr[0];

                  $("select[name='thn']").val(tglLhr[2]).change();
                  $("select[name='bln']").val(bulan).change();
                  $("select[name='tgl']").val(tglLhr[0]).change();

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


  <?php if(validation_errors() !="" || $this->session->flashdata('notification') !=""){ 
    $err_msg = str_replace("\n", "", validation_errors()."<p>".$this->session->flashdata('notification')."</p>");
  ?>
        $("#popup_content").html("<center><?php echo $err_msg?></center>");
        $("#popup").jqxWindow({
          theme: theme, resizable: false,
          width: 300,
          height: 125,
          isModal: true, autoOpen: false, modalOpacity: 0.4
        });
        $("#popup").jqxWindow('open');
  <?php } ?>

});
</script>