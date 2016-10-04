<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>{title}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="icon" href="<?php echo base_url()?>public/themes/login/img/favicon.ico">
    <!-- test -->
    <style type="text/css">
      /* <![CDATA[ */
        @import url(<?php echo base_url()?>public/themes/sik/bootstrap/css/bootstrap.min.css);
        @import url(<?php echo base_url()?>public/themes/login/css/style.css);
        @import url(<?php echo base_url()?>plugins/js/jqwidgets/styles/jqx.base.css);
        @import url(<?php echo base_url()?>plugins/js/jqwidgets/styles/jqx.orange.css);
      /* ]]> */
    </style>
    <script src="<?php echo base_url()?>plugins/js/jquery-1.6.2.min.js"></script>
    <script src="<?php echo base_url()?>plugins/js/jqwidgets/jqxcore.js"></script>
    <script src="<?php echo base_url()?>plugins/js/jqwidgets/jqxwindow.js"></script>
    <script src="<?php echo base_url()?>plugins/js/autocomplete.js"></script>
  </head>
  <!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
  <body class="skin-green layout-top-nav fixed">

<table id="body-main" class="login-bg" border="0" height="100%" width="100%">
<tbody><tr><td height="3%">&nbsp;</td></tr>
<tr><td align="center" >
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody><tr>
      <td width="10%">&nbsp;</td>
      <td style="padding-right:10px;" align="left" width="60%">
        <table>
          <tbody><tr>
            <td><img src="<?php echo base_url()?>public/themes/login/img/31.png" width="80" id="logo-kota"></td>
            <td>
              <font style="font-family:Calibri;font-size:14pt;color:#FFFFFF;">{district}<br>Kecamatan Makasar</font>
            </td>
          </tr>
        </tbody></table>
      </td>
      <td style="padding-right:10px;" align="center" id="td-logo-infokes" width="30%">
        <table>
          <tbody><tr>
            <td><img src="<?php echo base_url()?>public/themes/sik/dist/img/logo_white.png" width="210" id="logo-infokes"></td>
          </tr>
        </tbody></table>
      </td>
    </tr>
  </tbody></table>
  </td>
</tr>
<tr><td>
<table id="body-login-tablemaster" style="margin-top:20px;" align="center" border="0" cellpadding="0" cellspacing="0" height="250">
  <tbody>
  <tr>
    <td class="body-login-table">{content}</td>
  </tr>
  <tr>
    <td>
    </td>
  </tr>
</tbody></table>

</td></tr>
<tr><td style="padding-top:20px;font-family:Calibri;font-size:10pt;color:#FFFFFF;" align="center" height="10%">Powered by &nbsp;<a href="http://infokes.co.id/" style="text-decoration: none;color:white" target="_BLANK">PT Infokes Indonesia</a></td></tr>
<tr><td height="20%"> </td></tr>
</tbody></table>
<div class="ac_results" style="display: none; position: absolute;"></div>

  </body>
</html>
