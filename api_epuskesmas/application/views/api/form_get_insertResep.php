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
<table width='80%' border='1' cellpadding='5' cellspacing='1'>
    <tr>
        <td width='20%'>Kode Puskesmas</td>
        <td width='3%' align='center'>:</td>
        <td colspan="4"><input type="text" size="30" name="kodepuskesmas" value="P3172010203" /> *</td>
    </tr>
    <tr>
        <td width='20%'>Pemeriksa</td>
        <td width='3%' align='center'>:</td>
        <td><input type="text" size="30" name="pemeriksa_nama" value="" /> *</td>
        <td width='20%'>NIP</td>
        <td width='3%' align='center'>:</td>
        <td><input type="text" size="30" name="pemeriksa_nip" value="" /> *</td>
    </tr>
    <tr>
       <td width='20%'>Asisten</td>
        <td width='3%' align='center'>:</td>
        <td><input type="text" size="30" name="asisten_nama" value="" /> *</td>
        <td width='20%'>NIP Asistern</td>
        <td width='3%' align='center'>:</td>
        <td><input type="text" size="30" name="asisten_nip" value="" /> *</td>
    </tr>
    <tr>
        <td width='20%'>Anamnesa</td>
        <td width='3%' align='center'>:</td>
        <td><textarea name="anamnesa" rows="2" cols="25"></textarea></td>
        <td width='20%' rowspan="3" colspan="3">
            <table>
                <tr>
                    <td>Sistole</td>
                    <td><input type="text" size="10" name="sistole" value="" /> mmHg</td>
                    <td width="10%"></td>
                    <td>Berat Badan</td>
                    <td><input type="text" size="10" name="berat_badan" value="" /> Kg</td>
                </tr>
                <tr>
                    <td>Diastole</td>
                    <td><input type="text" size="10" name="diastole" value="" /> mmHg</td>
                    <td width="10%"></td>
                    <td>Tinggi Badan</td>
                    <td><input type="text" size="10" name="tinggi_badan" value="" /> Cm</td>
                </tr>
                <tr>
                    <td>Detak Nadi</td>
                    <td><input type="text" size="10" name="detak_nadi" value="" /></td>
                    <td width="10%"></td>
                    <td>Suhu Badan</td>
                    <td><input type="text" size="10" name="suhu_badan" value="" /> Cm</td>
                </tr>
                <tr>
                    <td>Kesadaran</td>
                    <td><input type="text" size="10" name="kesadaran" value="" /></td>
                    <td width="10%"></td>
                    <td>Nafas</td>
                    <td><input type="text" size="10" name="nafas" value="" /> Cm</td>
                </tr>
                <tr>
                    <td>Edukasi</td>
                    <td colspan="4"><textarea name="edukasi" rows="2" cols="25"></textarea></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width='20%'>Terapi</td>
        <td width='3%' align='center'>:</td>
        <td ><textarea name="terapi" rows="2" cols="25"></textarea></td>
    </tr>
    <tr>
        <td width='20%'>Keterangan</td>
        <td width='3%' align='center'>:</td>
        <td ><textarea name="keterangan" rows="2" cols="25"></textarea></td>
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