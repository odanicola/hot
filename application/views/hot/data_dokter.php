<?php if(validation_errors()!=""){ ?>
<div class="alert alert-warning alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
  <?php echo validation_errors()?>
</div>
<?php } ?>

<?php if($this->session->flashdata('alert_form')!=""){ ?>
<div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $this->session->flashdata('alert_form')?>
</div>
<?php } ?>

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
			{ name: 'code', type: 'string'},
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
			$("#jqxgrid_dokter").jqxDataTable('updateBoundData', 'filter');
		},
		sort: function(){
			$("#jqxgrid_dokter").jqxDataTable('updateBoundData', 'sort');
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
			$("#jqxgrid_dokter").jqxDataTable('clearfilters');
		});

		$("#jqxgrid_dokter").jqxDataTable(
		{		
			width: '100%', filterHeight: 32, 
			source: dataadapter, theme: theme,showtoolbar: false, 
			filterable: true, sortable: true,  pageable: true,  editable: false,
			columns: [
				{ text: 'Nama', datafield: 'value', align: 'center', filtertype: 'textbox', width: '75%'},
				{ text: 'Status', datafield: 'status', align: 'center', filtertype: 'textbox', width: '25%', cellsrenderer: function (row,column,value) {
					return "<div style='width:100%;padding:7px;text-align:center'>"+(value==1 ? "<i class='icon fa fa-check-square-o'></i>" : "-")+"</div>";
                 }
                }            
            ]
		});

		$("#jqxgrid_dokter").on('rowSelect', function (event) {
			var args = event.args;
			var rowData = args.row;

		    $("#popup_content").html("<div style='padding:5px' align='center'><br>"+rowData.value+"</br><br><div style='text-align:center'><input class='btn btn-primary' style='width:100px' type='button' value='Edit' onClick='btn_edit("+rowData.code+")'></div></div>");
		      $("#popup").jqxWindow({
		        theme: theme, resizable: false,
		        width: 250,
		        height: 150,
		        isModal: true, autoOpen: false, modalOpacity: 0.4
		      });
		      $("#popup").jqxWindow('open');
		});

	function btn_edit(code){
		var code ="" +code;
		var pad  = "000"
		var new_code = pad.substring(0, pad.length - code.length) + code
      	document.location.href="<?php echo base_url()?>hot/dokter/edit/"+new_code;
	}

	$("#btn_syncronize").click(function(){
	    
	});

    $("#puskesmas").change(function(){
		$.post("<?php echo base_url().'hot/dokter/filter_puskesmas' ?>", 'puskesmas='+$(this).val(),  function(){
			$("#jqxgrid_dokter").jqxDataTable('updateBoundData', 'cells');
		});
    });

    

</script>