<div id="popup" style="display:none;">
  <div id="popup_title">Hypertension Online Treatment</div><div id="popup_content">{popup}</div>
</div>
<section class="content">
<form action="<?php echo base_url()?>hot/pasien/data_pasien_{action}/1/{username}" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-6">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
        </div><!-- /.box-header -->

          <div class="box-footer pull-right">
            <button type="button"class="btn btn-primary" id="btn_simpan">Simpan</button>
            <button type="reset" class="btn btn-warning">Ulang</button>
            <button type="button"class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>hot/pasien'">Kembali</button>
          </div>
          <div class="box-body">
            <div class="form-group">
              <label>NIK*</label>
              <input type="text" class="form-control" name="username" placeholder="NIK" <?php if($action == "edit") echo "disabled"?> value="<?php 
                if(set_value('username')=="" && isset($username)){
                  echo $username;
                }else{
                  echo  set_value('username');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>BPJS</label>
              <input type="text" class="form-control" name="bpjs" placeholder="BPJS" value="<?php 
                if(set_value('bpjs')=="" && isset($bpjs)){
                  echo $bpjs;
                }else{
                  echo  set_value('bpjs');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Nomor RM</label>
              <input type="text" class="form-control" name="cl_pid" id="cl_pid" placeholder="Nomor MR" value="<?php 
                if(set_value('cl_pid')=="" && isset($cl_pid)){
                  echo $cl_pid;
                }else{
                  echo  set_value('cl_pid');
                }
                ?>">
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
              <input type="number" class="form-control" name="tb" placeholder="Berat Badan" value="<?php 
                if(set_value('tb')=="" && isset($tb)){
                  echo $tb;
                }else{
                  echo  set_value('tb');
                }
                ?>">
            </div> 

            <div class="form-group">
              <label>Berat Badan</label>
              <input type="number" class="form-control" name="bb" placeholder="Tinggi Badan" value="<?php 
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
                      <?php 
                      $puskode=substr($pus->code,1);
                      $select = $puskode == $code ? 'selected' : '';;
                       ?>
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

  $(function () { 
    tabIndex = 1;

    $("#popup").jqxWindow({
      theme: theme, resizable: false,
      width: 250,
      height: 150,
      isModal: true, autoOpen: false, modalOpacity: 0.4
    });

    var csource = function (query, response) {
      var dataAdapter = new $.jqx.dataAdapter
        (
          { 
            datatype: "json",
            datafields: [
                { name: 'id', type: 'string'},
                { name: 'username', type: 'string'},
                { name: 'nama', type: 'string'},
                { name: 'bpjs', type: 'string'}
            ],
            username: 'username',
            url: "<?php echo base_url();?>epus_api/pasien_search/",
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
                      var bpjs = item.no_bpjs != null ? ' , BPJS: ' + item.no_bpjs : '';

                      return {
                        label: item.nama_lengkap + ', ' + item.alamat + '<br>NIK: ' + item.nik + bpjs + '<br>' + 'No MR: '+item.id,
                        name: item.nama_lengkap,
                        value: item.id
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

    $("#btn_simpan").click(function(){
          var data = new FormData();
          $('#biodata_notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
          $('#biodata_notice').show();

          data.append('username',     $("[name='username']").val());
          data.append('jk',           $("[name='jk']:checked").val());
          data.append('nama',         $("[name='nama']").val());
          data.append('bpjs',         $("[name='bpjs']").val());
          data.append('tb',           $("[name='tb']").val());
          data.append('bb',           $("[name='bb']").val());
          data.append('phone_number', $("[name='phone_number']").val());
          data.append('tgl_lahir',    $("[name='tgl_lahir']").val());
          data.append('email',        $("[name='email']").val());
          data.append('alamat',       $("[name='alamat']").val());
          data.append('code',         $("[name='code']").val());
          data.append('cl_pid',       $("[name='cl_pid']").val());

          $.ajax({
              cache : false,
              contentType : false,
              processData : false,
              type : 'POST',
              url : '<?php echo base_url()."hot/pasien/data_pasien_edit/1/{username}"   ?>',
              data : data,
              success : function(response){
                a = response.split("|");
                if(a[0]=='OK'){
                  $("#popup_content").html("<div style='padding:5px'><br><div style='text-align:center'>Data berhasil diubah.<br><br><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup()'></div></div>");
                  $("#popup").jqxWindow('open');
                }else{
                  $("#popup_content").html("<div style='padding:5px'><br><div style='text-align:center'>Data gagal diubah.<br><br><br><input class='btn btn-danger' style='width:100px' type='button' value='OK' onClick='close_popup()'></div></div>");
                  $("#popup").jqxWindow('open');
                }
              }
          });

          return false;
    });

    $("#tgl_lahir").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height:30});

    $("#menu_dashboard").addClass("active");
    $("#menu_hot_pasien").addClass("active");

  });
</script>
