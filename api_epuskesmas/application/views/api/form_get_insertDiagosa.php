<title><?php echo $title_form; ?></title>
<form method="post" action="">
<table width='40%' border='1' cellpadding='5' cellspacing='1' style="background: yellow;">
    <tr>
        <td colspan="3">Header</td>
    </tr>
    <tr>
        <td width='20%'>Request Time</td>
        <td width='3%' align='center'>:</td>
        <td><input type="text" size="30" name="request_time" value="<?php echo time(); ?>" /> *</td>
    </tr>
    <tr>
        <td width='20%'>Request Token</td>
        <td width='3%' align='center'>:</td>
        <td><input type="text" size="30" name="request_token" value="lggsxl3roze498rqlp8hd90r57nw6c9f" /></td>
    </tr>
    <tr>
        <td width='20%'>Client ID</td>
        <td width='3%' align='center'>:</td>
        <td><input type="text" size="30" name="client_id" value="20160302000001"/></td>
    </tr>
    <tr>
        <td width='20%'>Output</td>
        <td width='3%' align='center'>:</td>
        <td><input type="text" size="30" name="request_output" value="json" /> *</td>
    </tr>
</table>
<table width='80%' border='0' cellpadding='5' cellspacing='1'>
    <tr>
        <td width='20%'>Kode Puskesmas</td>
        <td width='3%' align='center'>:</td>
        <td ><input type="text" size="30" name="kodepuskesmas" value="P3172010203" /> *</td>
    </tr>
    <tr>
        <td width='20%'>Pengguna</td>
        <td width='3%' align='center'>:</td>
        <td colspan="4"><input type="text" size="30" name="pengguna" value="puskesmas" /> *</td>
    </tr>
     <tr>
        <td width='20%'>Register Pasien</td>
        <td width='3%' align='center'>:</td>
        <td ><input type="text" size="30" name="no_register" value="RJ201605100001"></td>
    </tr>
    <tr>
        <td width='20%'>Jumlah Data</td>
        <td width='3%' align='center'>:</td>
        <td ><input type="text" size="30" name="jumlahdata" value="3" /> *</td>
    </tr>
    <tr>
        <td width='20%'>Diagnosa Pasien</td>
        <td width='3%' align='center'>:</td>
        <td>
            <table border="1">
                <tr>
                    <td>No iCD-X *</td>
                    <td>No Urut *</td>
                    <td>Nama Diagnosa *</td>
                    <td>Jenis Kasus *</td>
                    <td>Jenis Diagnos *</td>
                </tr>
                <?php for ($i=1; $i <=3 ; $i++) { ?>
                <tr>
                    <td><input type="text" size="30" name="diagnosa_no_icdx<?php echo $i;?>" value=""></td>
                    <td><input type="text" size="30" name="diagnosa_no_urut<?php echo $i;?>" value=""></td>
                    <td><input type="text" size="30" name="diagnosa_nama_diagnosa<?php echo $i;?>" value="" /></td>
                    <td>
                        <select class=combo-box style='width:75px' name="diagnosa_jenis_kasus<?php echo $i;?>">
                            <option value='0'>baru</option>
                            <option value='1'>lama</option>
                        </select>
                    </td>
                    <td>
                        <select class=combo-box style='width:75px' name="diagnosa_jenis_diagnosa<?php echo $i;?>">
                            <option value='0'>primer</option>
                            <option value='1'>sekunder</option>
                            <option value='2'>komplikasi</option>
                        </select>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>
            <input type="button" id="btn_lihatdatas" value="submit" />
            <input type="reset" id="resetdata" value="Reset" />
        </td>
    </tr>
</table>
</form>

<div id="showdata"></div>
<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/jquery-3.1.0.min.js"></script>
<script type="text/javascript">  
$("#resetdata").click(function(){
    $("#showdata").html('');
});




$("#btn_lihatdatas").click(function(){
    if ($("[name='request_time']").val()=='' || $("[name='request_token']").val()=='' ||$("[name='client_id']").val()=='' ||$("[name='request_output']").val()=='' ||$("[name='kodepuskesmas']").val()==''||$("[name='no_register']").val()=='') {
        alert('tidakbolehkosong');
    }else{
        $.ajax({
          type   : "POST",
          url    : "<?php echo base_url(); ?>api/action_data_diagnosa",
          data   : $("form").serialize(),
          success: function (response, text) {
            $("#showdata").html(response);
          },
          error: function (request, status, error) {
              alert(request+' '+status+' '+error);
          }
        });
    }
});
</script>