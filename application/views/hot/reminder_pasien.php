<div id="popup" style="display:none;">
  <div id="popup_title">Hypertension Online Treatment</div><div id="popup_content">{popup}</div>
</div>
<section class="content">
<form action="<?php echo base_url()?>mst/agama/dodel_multi" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
	    </div>
	    <div class="box-footer">
		 	<button type="button" class="btn btn-success" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
			<div class="row" style="padding-top:15px">
			  <div class="col-xs-2"></div>
			</div>
	    </div>
        <div class="box-body">
		    <div class="div-grid">
		        <div id="jqxgrid"></div>
			</div>
	    </div>
	  </div>
	</div>
  </div>
</form>
</section>

<script type="text/javascript">
	$(function () {	
		$("#menu_hot_reminder").addClass("active");
		$("#menu_dashboard").addClass("active");

		$("#popup").jqxWindow({
			theme: theme, resizable: false,
			width: 250,
			height: 150,
			isModal: true, autoOpen: false, modalOpacity: 0.4
		});
	});
	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_kunjungan', type: 'string'},
			{ name: 'urut', type: 'string'},
			{ name: 'nama', type: 'string'},
			{ name: 'username', type: 'string'},
			{ name: 'kontrol_tgl', type: 'string'},
			{ name: 'anjuran_dokter', type: 'string'},
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('hot/reminder/json_pasien'); ?>",
		cache: false,
		updaterow: function (rowid, rowdata, commit) {
			},
		filter: function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'sort');
		},
		root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){		
			if (data != null){
				source.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var dataadapter = new $.jqx.dataAdapter(source, {
			loadError: function(xhr, status, error){
				alert(error);
			}
		});
     
		$('#btn-refresh').click(function () {
			$("#jqxgrid").jqxGrid('clearfilters');
		});

		$("#jqxgrid").jqxGrid(
		{		
			width: '100%', autoheight: true,autorowheight: true,
			selectionmode: 'singlerow',
			source: dataadapter, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: false, filterable: false, sortable: false, autoheight: true, pageable: true, virtualmode: true, editable: false,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},
			columns: [
				{ text: 'Jadwal', datafield: 'kontrol_tgl', align: 'center', width: '30%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
					return "<div style='width:100%;padding:14px;text-align:center' >"+dataRecord.kontrol_tgl+"</div>";
                 }
                },				
                { text: 'Anjuran Dokter', datafield: 'anjuran_dokter', align: 'center', width: '70%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
					return "<div style='width:100%;padding:7px;'>"+dataRecord.anjuran_dokter+"</div>";
                 }
                }
            ]
		});

		$("#jqxgrid").on('rowselect', function (event) {
			var args = event.args;
			var rowData = args.row;

        	$("#popup_content").html("<div style='padding:5px' align='center'><br>"+rowData.nama+"</br><br><div style='text-align:center'><input class='btn btn-primary' style='width:100px' type='button' value='Detail' onClick='btn_edit(\""+rowData.id_kunjungan+"\")'>&nbsp;&nbsp;<input class='btn btn-warning' style='width:100px' type='button' value='Close' onClick='close_popup();'></div></div>");
 			$("html, body").animate({ scrollTop: 0 }, "slow");
			$("#popup").jqxWindow('open');
		});


	function btn_edit(id){
      	document.location.href="<?php echo base_url()?>hot/kunjungan/edit/" + id;
	}

	function close_popup(){
        $("#popup").jqxWindow('close');
        $("#jqxgrid").jqxGrid('clearselection');
    }

    $("#jenis_kelamin").change(function(){
		$.post("<?php echo base_url().'hot/kunjungan/filter_jenis_kelamin' ?>", 'filter_jenis_kelamin='+$(this).val(),  function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
		});
    });

    $("#tahun").change(function(){
		$.post("<?php echo base_url().'hot/kunjungan/filter_tahun' ?>", 'filter_tahun='+$(this).val(),  function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
		});
    });

    $("#bulan").change(function(){
		$.post("<?php echo base_url().'hot/kunjungan/filter_bulan' ?>", 'filter_bulan='+$(this).val(),  function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
		});
    });


</script>