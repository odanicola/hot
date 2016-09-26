<div class="box-body">
	<div class="text-center" style="padding:4px">
	  <b>{sebelumnya} Kunjungan Sebelumnya :</b>
	</div>
    <div class="div-grid">
        <div id="jqxgrid_sebelum"></div>
	</div>
</div>

<script type="text/javascript">
	$(function () {	
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
			{ name: 'tgl', type: 'string'},
			{ name: 'waktu', type: 'string'},
			{ name: 'username', type: 'string'},
			{ name: 'systolic', type: 'number'},
			{ name: 'diastolic', type: 'number'}
        ],
		url: "<?php echo site_url('hot/kunjungan/json_sebelumnya/{username}/{tgl}'); ?>",
		cache: false,
		updaterow: function (rowid, rowdata, commit) {
			},
		filter: function(){
			$("#jqxgrid_sebelum").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid_sebelum").jqxGrid('updatebounddata', 'sort');
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
			$("#jqxgrid_sebelum").jqxGrid('clearfilters');
		});

		$("#jqxgrid_sebelum").jqxGrid(
		{		
			width: '100%', autorowheight: true,
			selectionmode: 'singlerow',
			source: dataadapter, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50'],
			showfilterrow: false, filterable: false, sortable: false, autoheight: true, pageable: true, virtualmode: false, editable: false,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},
			columns: [
                { text: 'Tanggal', datafield: 'tgl', align: 'center', width: '40%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_sebelum").jqxGrid('getrowdata', row);
				    if(dataRecord.waktu != null){
						return "<div style='width:100%;padding:7px;text-align:center;'>"+dataRecord.tgl+"<br>"+dataRecord.waktu+"</div>";
				    }else{
						return "<div style='width:100%;padding:14px;text-align:center;'>"+dataRecord.tgl+"</div>";
				    }
                 }
                },
				{ text: 'Systolic', datafield: 'systolic', align: 'center', width: '30%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_sebelum").jqxGrid('getrowdata', row);
					return "<div style='width:100%;padding-top:14px;text-align:center' >"+dataRecord.systolic+"</div>";
                 }
                },
				{ text: 'Diastolic', datafield: 'diastolic', align: 'center', width: '30%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_sebelum").jqxGrid('getrowdata', row);
					return "<div style='width:100%;padding-top:14px;text-align:center' >"+dataRecord.diastolic+"</div>";
                 }
                }            
            ]
		});

		$("#jqxgrid_sebelum").on('rowselect', function (event) {
			var args = event.args;
			var rowData = args.row;

    		$("#popup_content").html("<div style='padding:5px' align='center'><br>"+rowData.tgl+"</br><br><div style='text-align:center'><input class='btn btn-primary' style='width:100px' type='button' value='Lihat' onClick='btn_edit(\""+rowData.id_kunjungan+"\")'>&nbsp;&nbsp;<input class='btn btn-warning' style='width:100px' type='button' value='Close' onClick='close_popup();'></div></div>");
 			$("html, body").animate({ scrollTop: 0 }, "slow");
			$("#popup").jqxWindow('open');
		});


	function close_popup(){
        $("#popup").jqxWindow('close');
        $("#jqxgrid_sebelum").jqxGrid('clearselection');
    }

</script>
