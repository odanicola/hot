<script type="text/javascript">
    $(document).ready(function(){
        $('#btn_updatePassword').click(function(){
          $.ajax({ 
            type: "POST",
            url: "<?php echo base_url()?>morganisasi/profile_dopasswd",
            data: $('#updatePassword').serialize(),
            success: function(response){
              console.log(response);
               if (response == "1") {
                  $('#notification').html('<div id="information" class="alert alert-warning alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><h4>  <i class="icon fa fa-check"></i> Information!</h4><span></span></div>');
                  $('#notification span').html("Password berhasil disimpan");
                  $('#password').html("");
                  $('#npassword').html("");
                  $('#cpassword').html("");
                      $('html, body').animate({
                          scrollTop: $("#top").offset().top
                      }, 300);
               } else {
                  $('#notification').html('<div id="information" class="alert alert-warning alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><h4>  <i class="icon fa fa-check"></i> Information!</h4><span></span></div>');
                  $('#notification span').html(response);
                      $('html, body').animate({
                          scrollTop: $("#top").offset().top
                      }, 300);
               }
            }
           });    
        });

        $('#btn_updateProfile').click(function(){
          $.ajax({ 
            type: "POST",
            url: "<?php echo base_url()?>morganisasi/profile_doupdate",
            data: $('#updateProfile').serialize(),
            success: function(response){
              $('#notification').html('<div id="information" class="alert alert-warning alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><h4>  <i class="icon fa fa-check"></i> Information!</h4><span></span></div>');
              $('#notification span').html(response);
                  $('html, body').animate({
                      scrollTop: $("#top").offset().top
                  }, 300);
               // if (response == "1") {
               //    $('#notification').html('<div id="information" class="alert alert-warning alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><h4>  <i class="icon fa fa-check"></i> Information!</h4><span></span></div>');
               //    $('#notification span').html("Data berhasil disimpan");
               //        $('html, body').animate({
               //            scrollTop: $("#top").offset().top
               //        }, 300);
               // } else {
               //    $('#notification').html('<div id="information" class="alert alert-warning alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><h4>  <i class="icon fa fa-check"></i> Information!</h4><span></span></div>');
               //    $('#notification span').html(response);
               //        $('html, body').animate({
               //            scrollTop: $("#top").offset().top
               //        }, 300);
               // }
            }
           });    
        });
    });
</script>
<section class="content">
<div class="row" style="background:#FAFAFA;margin:1px">
  <div id="notification"></div>
  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#tab_1" data-toggle="tab">Profil Pengguna</a></li>
      <li><a href="#tab_2" data-toggle="tab">Akun Pengguna</a></li>
    </ul>
    <div class="tab-content">


      <div class="tab-pane " id="tab_2">    
        <!-- <form action="<?php echo base_url()?>morganisasi/profile_dopasswd" method="post"> -->
        <form name="updatePassword" id="updatePassword">
        <div class="row">
        <div class="col-md-6 col-md-offset-3">
          <p class="login-box-msg">Berikut adalah informasi akun anda, anda dapat melakukan perubahan password:</p>
            <div class="form-group has-feedback">
              <input type="text" class="form-control" placeholder="Username" name="username" readonly value="<?php 
                      if(set_value('username')=="" && isset($username)){
                        echo $username;
                      }else{
                        echo  set_value('username');
                      }
                      ?>"/>
              <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
              <input type="password" class="form-control" placeholder="Password Lama" name="password" id="password"/>
              <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
              <input type="password" class="form-control" placeholder="Password Baru" name="npassword" id="npassword"/>
              <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
              <input type="password" class="form-control" placeholder="Retype password" name="cpassword" id="cpassword"/>
              <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>
            <br>
            <div class="row">
              <div class="col-xs-5">
                <button type="button" id="btn_updatePassword" class="btn btn-primary btn-block btn-flat">Ubah Password</button>
              </div><!-- /.col -->
            </div>
        </div>
        </div>
        </form>
      </div>


      <div class="tab-pane active" id="tab_1">    
        <!-- <form action="<?php echo base_url()?>morganisasi/profile_doupdate" method="post"> -->
        <form name="updateProfile" id="updateProfile">
        <div class="row">
        <div class="col-md-6 col-md-offset-3">
          <p class="login-box-msg">Silahkan periksa kembali kelengkapan data profil anda :</p>
             <div class="input-group">
              <span class="input-group-addon">
                <div style="width:150px;text-align:left">
                  <i class="fa fa-qrcode" style="width:20px"></i> Username
                </div>
              </span>
              <input type="text" class="form-control" placeholder="username" name="username" readonly value="{username}"/>
            </div>
            <br>
            <div class="input-group">
              <span class="input-group-addon">
                <div style="width:150px;text-align:left">
                  <i class="fa fa-user" style="width:20px"></i> Nama Lengkap
                </div>
              </span>
              <input type="text" class="form-control" placeholder="** Nama Lengkap" name="nama" value="<?php 
                      if(set_value('nama')=="" && isset($nama)){
                        echo $nama;
                      }else{
                        echo  set_value('nama');
                      }
                      ?>"/>
            </div>
            <br>
             <div class="input-group">
              <span class="input-group-addon">
                <div style="width:150px;text-align:left">
                  <i class="fa fa-hospital-o" style="width:20px"></i> BPJS
                </div>
              </span>
              <input type="text" class="form-control" placeholder="BPJS" name="bpjs" value="<?php 
                      if(set_value('bpjs')=="" && isset($bpjs)){
                        echo $bpjs;
                      }else{
                        echo  set_value('bpjs');
                      }
                      ?>"/>
            </div>
            <br>
             <div class="input-group">
              <span class="input-group-addon">
                <div style="width:150px;text-align:left">
                  <i class="fa fa-envelope" style="width:20px"></i> Email
                </div>
              </span>
              <input type="text" class="form-control" placeholder="Email" name="email" value="<?php 
                      if(set_value('email')=="" && isset($email)){
                        echo $email;
                      }else{
                        echo  set_value('email');
                      }
                      ?>"/>
            </div>
            <br>
            <div class="input-group">
              <span class="input-group-addon">
                <div style="width:150px;text-align:left">
                  <i class="fa fa-phone" style="width:20px"></i> Phone
                </div>
              </span>
              <input type="text" class="form-control" placeholder="** No. Tlp" name="phone_number" value="<?php 
                      if(set_value('phone_number')=="" && isset($phone_number)){
                        echo $phone_number;
                      }else{
                        echo  set_value('phone_number');
                      }
                      ?>"/>
            </div>
            <br>
            <div class="input-group">
              <span class="input-group-addon">
                <div style="width:150px;text-align:left">
                  <i class="fa fa-home" style="width:20px"></i>Alamat
                </div>
              </span>
              <input type="text" class="form-control" placeholder="Alamat" name="alamat" value="<?php 
                      if(set_value('alamat')=="" && isset($alamat)){
                        echo $alamat;
                      }else{
                        echo  set_value('alamat');
                      }
                      ?>"/>
            </div>
            <br>
              <div class="input-group">
              <span class="input-group-addon">
                <div style="width:150px;text-align:left">
                  <i class="fa fa-calendar" style="width:20px"></i> Tgl Lahir
                </div>
              </span>
                <div id='tgl_lahir' name="tgl_lahir"></div>
             </div>
            <br>
            <div class="input-group">
              <span class="input-group-addon">
                <div style="width:150px;text-align:left">
                  <i class="fa fa-venus-mars" style="width:20px"></i> Jenis Kelamin
                </div>
              </span>
                <select name="jk" type="text" class="form-control">
                  <?php
                    if(set_value('jk')=="" && isset($jk)){
                      $jk = $jk;
                    }else{
                      $jk = set_value('jk');
                    }
                  ?>
                    <option value="L" <?php echo  ('L' == $jk) ? 'selected' : '' ?> >Laki-laki</option>
                    <option value="P" <?php echo  ('P' == $jk) ? 'selected' : '' ?> >Perempuan</option>
                </select>
            </div>
            <br>
            <div class="input-group">
              <span class="input-group-addon">
                <div style="width:150px;text-align:left">
                  <i class="fa fa-child" style="width:20px"></i>Tinggi Badan
                </div>
              </span>
              <input type="number" class="form-control" placeholder="Tinggi Badan" name="tb" value="<?php 
                      if(set_value('tb')=="" && isset($tb)){
                        echo $tb;
                      }else{
                        echo  set_value('tb');
                      }
                      ?>"/>
            </div>

            <br>
            <div class="input-group">
              <span class="input-group-addon">
                <div style="width:150px;text-align:left">
                  <i class="fa fa-street-view" style="width:20px"></i> Berat Badan
                </div>
              </span>
              <input type="number" class="form-control" placeholder="Berat Badan" name="bb" value="<?php 
                      if(set_value('bb')=="" && isset($bb)){
                        echo $bb;
                      }else{
                        echo  set_value('bb');
                      }
                      ?>"/>
            </div>
            <br>
            <div class="row">
              <div class="col-xs-4">
                <button type="button" id="btn_updateProfile" class="btn btn-primary btn-block btn-flat">Simpan</button>
              </div><!-- /.col -->
            </div>
        </div>
        </div>
        </form>        
      </div>
    </div>
  </div><!-- /.form-box -->
</div><!-- /.register-box -->
</section>
<script type="text/javascript">
$(function(){
    $("#menu_dashboard").addClass("active");
    $("#menu_morganisasi_profile").addClass("active");
    $("#tgl_lahir").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height:30});
    var tgl = "{tgl_lahir}".split("-");
    var date = new Date(tgl[0], (tgl[1]-1), tgl[2]);
    $("#tgl_lahir").jqxDateTimeInput('setDate', date);

  });
</script>
