<div id="popup" style="display:none;">
  <div id="popup_title">Hypertension Online Treatment</div><div id="popup_content">{popup}</div>
</div>
<div id="popup_del" style="display:none;">
  <div id="popup_title_del">Hypertension Online Treatment</div><div id="popup_content_del">{popup}</div>
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
			<div class="row" style="padding-top:5px">
			  <div class="col-xs-6" style="text-align:right;padding:5px">Puskesmas</div>
			  <div class="col-xs-6">
			      <select  id="puskesmas" type="text" class="form-control">
                      <option>-</option>
                      <?php foreach($datapuskesmas as $pus) : ?>
                        <?php
                        if(set_value('code')=="" && isset($code)){
                          $code = $code;
                        }else{
                          $code = set_value('code');
                        }
                        $select = $pus->code == $code ? 'selected' : '' ;
                        ?>
                      	<option value="<?php echo $pus->code;$pus->value; ?>" <?php echo $select ?>><?php echo $pus->value; ?></option>
                      <?php endforeach ?>
                  </select>
			  </div>
			</div>
	    </div>
        <div class="box-body">
		    <div class="div-grid">
		        <div id="jqxgrid_dokter"></div>
			</div>
	    </div>
	  </div>
	</div>
  </div>
</form>
</section>

<script type="text/javascript">
	$(function () {	
		$("#menu_hot_dokter").addClass("active");
		$("#menu_dashboard").addClass("active");
	});

	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'value', type: 'string'},
			{ name: 'status', type: 'int'},
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('hot/dokter/json'); ?>",
		cache: false,
		updaterow: function (rowid, rowdata, commit) {
			},
		filter: function(){
			$("#jqxgrid_dokter").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid_dokter").jqxGrid('updatebounddata', 'sort');
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
			$("#jqxgrid_dokter").jqxGrid('clearfilters');
		});

		$("#jqxgrid_dokter").jqxGrid(
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
				{ text: 'Nama', datafield: 'value', align: 'center', filtertype: 'textbox', width: '55%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_dokter").jqxGrid('getrowdata', row);
					return "<div style='width:100%;padding:7px;' onclick='aksi(\""+dataRecord.code+"\");'>"+dataRecord.value+"</div>";
                 }
                },
				{ text: 'Status', datafield: 'status', align: 'center', filtertype: 'textbox', width: '45%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_dokter").jqxGrid('getrowdata', row);
					return "<div style='width:100%;padding:7px;' onclick='aksi(\""+dataRecord.code+"\");'>"+dataRecord.status==1 ? "<i class='icon fa fa-check-square-o'></i>" : "-"+"</div>";
                 }
                }            
            ]
		});

	$("#btn_syncronize").click(function(){
	    

	});

    $("#puskesmas").change(function(){
		$.post("<?php echo base_url().'hot/dokter/filter_puskesmas' ?>", 'puskesmas='+$(this).val(),  function(){
			$("#jqxgrid_dokter").jqxGrid('updatebounddata', 'cells');
		});
    });

    

</script>