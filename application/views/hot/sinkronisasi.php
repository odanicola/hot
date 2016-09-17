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
			  <div class="col-xs-4">
	                <select name="tahun" id="tahun" class="form-control">
                        <?php for ($i=date("Y");$i>=date("Y")-10;$i--) { ;?>
                            <?php $select = $i == date("Y") ? 'selected=selected' : '' ?>
                            <option value="<?php echo $i; ?>" <?php echo $select ?>><?php echo $i; ?></option>
                        <?php   } ;?>
                    </select>
			  </div>
			  <div class="col-xs-6" style="padding-left:14px">
	                <select name="bulan" id="bulan" class="form-control">
                        <?php foreach ($bulan as $val=>$key ) { ;?>
                        <?php $select = $val == date("m") ? 'selected=selected' : '' ?>
                            <option value="<?php echo $val; ?>" <?php echo $select ?>><?php echo $key; ?></option>
                        <?php   } ;?>
                    </select>
			  </div>
			</div>
			<div class="row" style="padding-top:5px">
			  <div class="col-xs-6" style="text-align:right;padding:5px">Jenis Kelamin</div>
			  <div class="col-xs-6">
			  		<select class="form-control" id="jenis_kelamin">
			  			<option>-</option>
			  			<option value="L" <?php echo ("L"==$filter_jenis_kelamin ? 'selected':'')?>>L</option>
			  			<option value="P" <?php echo ("P"==$filter_jenis_kelamin ? 'selected':'')?>>P</option>
			  		</select>
			  </div>
			</div>
			<div class="row" style="padding-top:5px">
			  <div class="col-xs-6" style="text-align:right;padding:5px">Status</div>
			  <div class="col-xs-6">
			  		<select class="form-control" id="status_antri">
			  			<option>-</option>
			  			<option>Syncronized</option>
			  			<option>Not Sync</option>
			  		</select>
			  </div>
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
		$("#menu_hot_sinkronisasi").addClass("active");
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
			{ name: 'username', type: 'string'},
			{ name: 'tgl', type: 'string'},
			{ name: 'waktu', type: 'string'},
			{ name: 'jk', type: 'string'},
			{ name: 'usia', type: 'int'},
			{ name: 'nama', type: 'string'},
			{ name: 'bpjs', type: 'string'},
			{ name: 'phone_number', type: 'string'},
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('hot/sinkronisasi/json'); ?>",
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
				{ text: 'No', align: 'center', width: '12%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
					return "<div style='width:100%;padding:7px;text-align:center'><br>"+dataRecord.urut+"<br></div>";
                 }
                },				
                { text: 'Nama', datafield: 'nama', align: 'center', width: '43%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
					return "<div style='width:100%;padding:7px;'>"+dataRecord.nama+"<br>"+dataRecord.jk+" / "+dataRecord.usia+" Tahun<br>BJPS: "+dataRecord.bpjs+"</div>";
                 }
                },
				{ text: 'Kunjungan', datafield: 'tgl', align: 'center', width: '45%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
					return "<div style='width:100%;padding-top:14px;text-align:center'>"+dataRecord.tgl+"<br>"+dataRecord.waktu+"</div>";
                 }
                }            
            ]
		});

		$("#jqxgrid").on('rowselect', function (event) {
			var args = event.args;
			var rowData = args.row;

        	$("#popup_content").html("<div style='padding:5px' align='center'><br>"+rowData.nama+"</br><br><div style='text-align:center'><input class='btn btn-primary' style='width:100px' type='button' value='Kirim' onClick='btn_edit(\""+rowData.id_kunjungan+"\")'>&nbsp;&nbsp;<input class='btn btn-warning' style='width:100px' type='button' value='Close' onClick='close_popup();'></div></div>");
 			$("html, body").animate({ scrollTop: 0 }, "slow");
			$("#popup").jqxWindow('open');
		});


	function btn_edit(id){
        $("#popup").jqxWindow('close');
        $("#jqxgrid").jqxGrid('clearselection');
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

    $("#tanggal").change(function(){
		$.post("<?php echo base_url().'hot/kunjungan/filter_tanggal' ?>", 'filter_tanggal='+$(this).val(),  function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
		});
    });

    $("#status_antri").change(function(){
		$.post("<?php echo base_url().'hot/kunjungan/filter_status_antri' ?>", 'filter_status_antri='+$(this).val(),  function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
		});
    });


</script>