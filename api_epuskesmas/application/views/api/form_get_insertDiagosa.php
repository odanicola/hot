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
<table width='40%' border='1' cellpadding='5' cellspacing='1'>
    <tr>
        <td width='20%'>Kode Puskesmas</td>
        <td width='3%' align='center'>:</td>
        <td><input type="text" size="30" name="kodepuskesmas" value="P3172010203" /> *</td>
    </tr>
    <tr>
        <td width='20%'>Register Pasien</td>
        <td width='3%' align='center'>:</td>
        <td><input type="text" size="30" name="no_register" value=""> *</td>
    </tr>
    <tr>
        <td width='20%'>No iCD-X</td>
        <td width='3%' align='center'>:</td>
        <td><input type="text" size="30" name="no_icdx" value=""> *</td>
    </tr>
    <tr>
        <td width='20%'>No Urut</td>
        <td width='3%' align='center'>:</td>
        <td><input type="text" size="30" name="no_urut" value=""> *</td>
    </tr>
    <tr>
        <td width='20%'>Nama Diagnosa</td>
        <td width='3%' align='center'>:</td>
        <td><input type="text" size="30" name="nama_diagnosa" value="" /> *</td>
    </tr>
    <tr>
        <td width='20%'>Jenis Kasus</td>
        <td width='3%' align='center'>:</td>
        <td>
            <select class=combo-box style='width:75px' name='jenis_khusus'>
                <option value='0'>baru</option>
                <option value='1'>lama</option>
            </select>
        </td>
    </tr>
    <tr>
        <td width='20%'>Jenis Diagnosa</td>
        <td width='3%' align='center'>:</td>
        <td>
            <select class=combo-box style='width:75px' name='jenis_diagnosa'>
                <option value='0'>primer</option>
                <option value='1'>sekunder</option>
                <option value='2'>komplikasi</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>
            <input type="button" id="btn_lihatdatas" value="Submit" />
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
    $.ajax({
      type   : "POST",
      url    : "<?php echo base_url(); ?>api/insert_data_diagnosa",
      data   : "request_time="+$("[name='request_time']").val()+"&request_token="+$("[name='request_token']").val()+"&client_id="+$("[name='client_id']").val()+"&request_output="+$("[name='request_output']").val()+"&kodepuskesmas="+$("[name='kodepuskesmas']").val()+"&no_icdx="+$("[name='no_icdx']").val()+"&nama_diagnosa="+$("[name='nama_diagnosa']").val()+"&jenis_kasus="+$("[name='jenis_kasus']").val()+"&jenis_diagnosa="+$("[name='jenis_diagnosa']").val()+"&no_register="+$("[name='no_register']").val()+"&no_urut="+$("[name='no_urut']").val(),
      success: function (response, text) {
        $("#showdata").html(response);
      },
      error: function (request, status, error) {
          alert(request+' '+status+' '+error);
      }
    });
});
</script>