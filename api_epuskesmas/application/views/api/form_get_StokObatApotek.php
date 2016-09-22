<title><?php echo $title_form; ?></title>
<form method="post" action="<?php echo base_url(); ?>index.php/api/get_data_stokObatApotek">
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
        <td width='20%'>Nama Obat</td>
        <td width='3%' align='center'>:</td>
        <td><input type="text" size="30" name="nameObat" value=""> *</td>
    </tr>
    <tr>
        <td width='20%'>Kode Obat</td>
        <td width='3%' align='center'>:</td>
        <td><input type="text" size="30" name="kodeObat" value="" /> *</td>
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
            <input type="submit" value="Submit" />
            <input type="reset" value="Reset" />
        </td>
    </tr>
</table>
</form>
