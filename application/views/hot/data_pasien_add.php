<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/ajaxupload.3.5.js"></script>
<script>
  $(function() {
        $('#jqxTabs').jqxTabs({ width: '100%', height: '1000'});

        $('#btn-return').click(function(){
            document.location.href="<?php echo base_url()?>kepegawaian/drh";
        });

        var loadPage = function (url, tabIndex) {
            $.get(url, function (data) {
                $('#content' + tabIndex).html(data);
            });
        }

        loadPage('<?php echo base_url()?>hot/pasien/data_pasien/1/{username}', 1);
        $('#jqxTabs').on('selected', function (event) {
            var pageIndex = event.args.item + 1;
            loadPage('<?php echo base_url()?>hot/pasien/data_pasien/'+pageIndex+'/{username}', pageIndex);
        });

        var divalert = '<div class="alert alert-warning alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><div>';

  });
</script>

<section class="content">

<div id='jqxWidget'>
    <div id='jqxTabs'>
        <ul>
            <li style="margin-left: 15px;">
              <div style="height: 20px; margin-top: 5px;">
                  <div style="float: left;">
                      <i class="icon fa fa-list-alt" style="font-size: 18px"></i>
                  </div>
                  <div style="margin-left: 8px; vertical-align: middle; text-align: center; float: left;">
                      Profil Pasien</div>
              </div>
            </li>
            <li style="margin-left: 15px;">
              <div style="height: 20px; margin-top: 5px;">
                  <div style="float: left;">
                      <i class="icon fa fa-list-alt" style="font-size: 18px"></i>
                  </div>
                  <div style="margin-left: 8px; vertical-align: middle; text-align: center; float: left;">
                      Akun Pasien</div>
              </div>
            </li>
        </ul>
        <div id="content1" style="background: #FAFAFA"></div>
        <div id="content2" style="background: #FAFAFA"></div>
    </div>
</div>

</section>

<script>
  $(function () { 
    $("#menu_dashboard").addClass("active");
    $("#menu_hot_pasien").addClass("active");
  });
</script>
