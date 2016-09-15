<div id="popup" style="display:none;">
  <div id="popup_title">Hypertension Online Treatment</div><div id="popup_content">{popup}</div>
</div>

<section class="content">
<form>
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
	    </div>
	      <div class="box-footer">
		 	<button type="button" id="btn_syncronize" class="btn btn-primary"><i class='fa fa-plus-square-o'></i> &nbsp; Sinkronisasi Data</button>
		 	<button type="button" class="btn btn-success" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
	    </div>
        <div class="box-body">
		    <div class="div-grid">
		        <div id="jqxgrid_obat"></div>
			</div>
	    </div>
	  </div>
	</div>
  </div>
</form>
</section>

<script type="text/javascript">
	$(function () {	
		$("#menu_hot_obat").addClass("active");
		$("#menu_dashboard").addClass("active");

		$("#popup").jqxWindow({
			theme: theme, resizable: false,
			width: 250,
			height: 180,
			isModal: true, autoOpen: false, modalOpacity: 0.4
		});
	});

      var btn_confirm = "</br></br><input class='btn btn-danger' style='width:100px' type='button' value='Ya' onClick='sync()'> <input class='btn btn-success' style='width:100px' type='button' value='Tidak' onClick='close_popup()'>";
      var btn_ok = "</br></br><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup()'>";

	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'code', type: 'string'},
			{ name: 'value', type: 'string'},
			{ name: 'sediaan', type: 'int'},
			{ name: 'status', type: 'int'},
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('hot/obat/json'); ?>",
		cache: false,
		updaterow: function (rowid, rowdata, commit) {
			},
		filter: function(){
			$("#jqxgrid_obat").jqxGrid('updateBoundData', 'filter');
		},
		sort: function(){
			$("#jqxgrid_obat").jqxGrid('updateBoundData', 'sort');
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
			$("#jqxgrid_obat").jqxGrid('clearfilters');
		});

		$("#jqxgrid_obat").jqxGrid(
		{		
			width: '100%', autoheight: true,autorowheight: true,
			selectionmode: 'singlerow',
			source: dataadapter, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: false,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},
			columns: [
				{ text: 'Nama', datafield: 'value', align: 'center', filtertype: 'textbox', width: '50%'},
				{ text: 'Sediaan', datafield: 'sediaan', align: 'center', filtertype: 'textbox', width: '25%'},
				{ text: 'Status', datafield: 'status', align: 'center', filtertype: 'textbox', width: '25%', cellsrenderer: function (row,column,value) {
					return "<div style='width:100%;padding:7px;text-align:center'>"+(value==1 ? "<i class='icon fa fa-check-square-o'></i>" : "-")+"</div>";
                 }
                }            
            ]
		});

		$("#jqxgrid_obat").on('rowselect', function (event) {
			var args = event.args;
			var rowData = args.row;

			$("#popup_content").html("<div style='padding:5px' align='center'><br>"+rowData.value+"</br><br><div style='text-align:center'><input class='btn btn-primary' style='width:100px' type='button' value='Edit' onClick='btn_edit("+rowData.code+")'> <input class='btn btn-warning' style='width:100px' type='button' value='Close' onClick='close_popup()'></div></div>");
 			$("html, body").animate({ scrollTop: 0 }, "slow");
			$("#popup").jqxWindow('open');
		});

	function btn_edit(code){
      	document.location.href="<?php echo base_url()?>hot/obat/edit/"+code;
	}

	function sync(){
		$.post("<?php echo base_url().'bpjs_api/get_obat' ?>", 'puskesmas='+$("#puskesmas").val(),  function(res){
			$("#jqxgrid_obat").jqxGrid('updateBoundData', 'filter');
			$("#popup_content").html("<div style='text-align:center'><br><br>Sync data obat sebanyak "+res+" data.<br>"+btn_ok+"</div>");
			$("#popup").jqxWindow('open');
		});
	}

    function close_popup(){
        $("#popup").jqxWindow('close');
        $("#jqxgrid_obat").jqxGrid('clearselection');
    }

	$("#btn_syncronize").click(function(){
		$("#popup_content").html("<div style='text-align:center'><br><br>Sync data obat dengan PCare? <br>"+btn_confirm+"</div>");
		$("#popup").jqxWindow('open');
	});

</script>