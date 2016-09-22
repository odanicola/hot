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
        <td width='20%'>Kode Diagnosa</td>
        <td width='3%' align='center'>:</td>
        <td><input type="text" size="30" name="kode_dianosa" value=""> *</td>
    </tr>
    <tr>
        <td width='20%'>Nama Diagnosa</td>
        <td width='3%' align='center'>:</td>
        <td><input type="text" size="30" name="nama_diagnosa" value="" /> *</td>
    </tr>
    <tr>
        <td width='20%'>Limit Data</td>
        <td width='3%' align='center'>:</td>
        <td><input type="text" size="30" name="limit" value="" /> *</td>
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
      url    : "<?php echo base_url(); ?>api/form_get_pasienByDiagnosa",
      data   : "request_time="+$("[name='request_time']").val()+"&request_token="+$("[name='request_token']").val()+"&client_id="+$("[name='client_id']").val()+"&request_output="+$("[name='request_output']").val()+"&kodepuskesmas="+$("[name='kodepuskesmas']").val()+"&kode_dianosa="+$("[name='kode_dianosa']").val()+"&nama_diagnosa="+$("[name='nama_diagnosa']").val()+"&limit="+$("[name='limit']").val(),
      success: function (response, text) {
        $("#showdata").html(response);
      },
      error: function (request, status, error) {
          alert(request+' '+status+' '+error);
      }
    });
});
</script>