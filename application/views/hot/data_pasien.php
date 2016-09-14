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
<div id="popup_del1" style="display:none;">
  <div id="popup_title_del1">Hypertension Online Treatment</div><div id="popup_content_del1">{popup}</div>
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
		 	<button type="button" class="btn btn-primary" onclick="document.location.href='<?php echo base_url()?>hot/pasien/add'"><i class='fa fa-plus-square-o'></i> &nbsp; Tambah</button>
		 	<button type="button" class="btn btn-danger"  id="btn-export"><i class='fa fa-file-excel-o'></i> &nbsp; Export</button>
			<button type="button" class="btn btn-danger"  id="export-loader" style="display:none"><i class='fa fa-clock-o'></i> &nbsp; Loading ...</button>
		 	<button type="button" class="btn btn-success" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
			<div class="row" style="padding-top:5px">
			  <div class="col-xs-6" style="text-align:right;padding:5px">Jenis Kelamin</div>
			  <div class="col-xs-6">
			  		<select class="form-control" id="jenis_kelamin">
			  			<option>-</option>
			  			<option value="L">L</option>
			  			<option value="P">P</option>
			  		</select>
			  </div>
			</div>
			<div class="row" style="padding-top:5px">
			  <div class="col-xs-6" style="text-align:right;padding:5px">BPJS</div>
			  <div class="col-xs-6">
			  		<select class="form-control" id="jenis_bpjs">
			  			<option>-</option>
			  			<option value="01">Peserta</option>
			  			<option value="02">Bukan Peserta</option>
			  		</select>
			  </div>
			</div>
			<div class="row" style="padding-top:5px">
			  <div class="col-xs-6" style="text-align:right;padding:5px">Urutan</div>
			  <div class="col-xs-6">
			  		<select class="form-control" id="urutan_usia">
			  			<option>-</option>
			  			<option value="01">Usia Termuda</option>
			  			<option value="02">Usia Tertua</option>
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
		$("#menu_hot_pasien").addClass("active");
		$("#menu_dashboard").addClass("active");
		$("#popup").jqxWindow({
			theme: theme, resizable: false,
			width: 250,
			height: 190,
			isModal: true, autoOpen: false, modalOpacity: 0.4
		});
	});

	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'username', type: 'string'},
			{ name: 'jk', type: 'string'},
			{ name: 'usia', type: 'int'},
			{ name: 'nama', type: 'string'},
			{ name: 'bpjs', type: 'string'},
			{ name: 'phone_number', type: 'string'},
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('hot/pasien/json'); ?>",
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
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: false,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},
			columns: [
				{ text: 'Nama', datafield: 'nama', align: 'center', filtertype: 'textbox', width: '55%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
					return "<div style='width:100%;padding:7px;'>"+dataRecord.nama+"<br>"+dataRecord.jk+"<br>"+dataRecord.usia+" Tahun"+"</div>";
                 }
                },
				{ text: 'BPJS / Telepon', datafield: 'bpjs', align: 'center', filtertype: 'textbox', width: '45%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
					return "<div style='width:100%;padding:7px;'>"+dataRecord.phone_number+"<br>BJPS: "+dataRecord.bpjs+"</div>";
                 }
                }            
            ]
		});

		$("#jqxgrid").on('rowselect', function (event) {
			var args = event.args;
			var rowData = args.row;

	        $("#popup_content").html("<div style='padding:5px' align='center'><br>"+rowData.nama+"</br><br><div style='text-align:center'><input class='btn btn-primary' style='width:100px' type='button' value='Edit' onClick='btn_edit("+rowData.username+")'> <input class='btn btn-danger' style='width:100px' type='button' value='Delete' onClick='btn_del("+rowData.username+")'><br><br><input class='btn btn-warning' style='width:204px' type='button' value='Tutup' onClick='close_popup()'></div></div>");
 			$("html, body").animate({ scrollTop: 0 }, "slow");
			$("#popup").jqxWindow('open');
		});

	function btn_edit(id){
      	document.location.href="<?php echo base_url()?>hot/pasien/edit/" + id;
	}

	function btn_del(id){
		$("#popup").hide();
		$("#popup_content_del").html("<div style='padding:5px'><br><div style='text-align:center'>Hapus Data?<br><br><input class='btn btn-danger' style='width:100px' type='button' value='Delete' onClick='del_pasien("+id+")'>&nbsp;&nbsp;<input class='btn btn-success' style='width:100px' type='button' value='Batal' onClick='close_popup_del()'></div></div>");
          $("#popup_del").jqxWindow({
            theme: theme, resizable: false,
            width: 250,
            height: 150,
            isModal: true, autoOpen: false, modalOpacity: 0.4
          });
        $("#popup_del").jqxWindow('open');
	}

	function del_pasien(id){
		$.post("<?php echo base_url().'hot/pasien/del' ?>/" +id,  function(){
		  $("#popup_content_del1").html("<div style='padding:5px'><br><div style='text-align:center'>Data berhasil dihapus<br><br><input class='btn btn-danger' style='width:100px' type='button' value='OK' onClick='close_popup_del1()'></div></div>");
          $("#popup_del1").jqxWindow({
            theme: theme, resizable: false,
            width: 250,
            height: 150,
            isModal: true, autoOpen: false, modalOpacity: 0.4
          });
        
			$("#popup_del1").jqxWindow('open');
			$("#popup").jqxWindow('close');
			$("#popup_del").jqxWindow('close');
			$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
		});
	}

	function close_popup(){
        $("#jqxgrid").jqxGrid('clearselection');
        $("#popup").jqxWindow('close');
    }

    function close_popup_del(){
        $("#jqxgrid").jqxGrid('clearselection');
        $("#popup").jqxWindow('close');
        $("#popup_del").jqxWindow('close');
    }

    function close_popup_del1(){
        $("#jqxgrid").jqxGrid('clearselection');
        $("#popup").jqxWindow('close');
        $("#popup_del").jqxWindow('close');
        $("#popup_del1").jqxWindow('close');
    }

	$("#jenis_bpjs").change(function(){
		$.post("<?php echo base_url().'hot/pasien/filter_jenis_bpjs' ?>", 'jenis_bpjs='+$(this).val(),  function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
		});
    });

    $("#jenis_kelamin").change(function(){
		$.post("<?php echo base_url().'hot/pasien/filter_jenis_kelamin' ?>", 'jenis_kelamin='+$(this).val(),  function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
		});
    });

    $("#urutan_usia").change(function(){
		$.post("<?php echo base_url().'hot/pasien/filter_urutan_usia' ?>", 'urutan_usia='+$(this).val(),  function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
		});
    });

    $("#btn-export").click(function(){
		$("#btn-export").hide();
		$("#export-loader").show('fade');

		var post = "";
		var getpaginginformation = $("#jqxgrid").jqxGrid('getpaginginformation');
		var pagesize = getpaginginformation.pagesize;
		var pagenum = getpaginginformation.pagenum;
		var filter = $("#jqxgrid").jqxGrid('getfilterinformation');
		for(i=0; i < filter.length; i++){
			var fltr 	= filter[i];
			var value	= fltr.filter.getfilters()[0].value;
			var condition	= fltr.filter.getfilters()[0].condition;
			var filteroperation	= fltr.filter.getfilters()[0].operation;
			var filterdatafield	= fltr.filtercolumn;
			if(filterdatafield=="tgl_lahir"){
				var d = new Date(value);
				var day = d.getDate() < 10 ? "0"+d.getDate() : d.getDate();
				var month = d.getMonth()+1;
				month = month < 10 ? "0"+month : month;
				var year = d.getFullYear();
				value = year+'-'+month+'-'+day;
				
			}
			post = post+'&filtervalue'+i+'='+value;
			post = post+'&filtercondition'+i+'='+condition;
			post = post+'&filteroperation'+i+'='+filteroperation;
			post = post+'&filterdatafield'+i+'='+filterdatafield;
			post = post+'&'+filterdatafield+'operator=and';
		}
		post = post+'&filterscount='+i+'&recordstartindex='+(pagenum * pagesize)+'&pagesize='+pagesize;
		
		var sortdatafield = $("#jqxgrid").jqxGrid('getsortcolumn');
		if(sortdatafield != "" && sortdatafield != null){
			post = post + '&sortdatafield='+sortdatafield;
		}
		if(sortdatafield != null){
			var sortorder = $("#jqxgrid").jqxGrid('getsortinformation').sortdirection.ascending ? "asc" : ($("#jqxgrid").jqxGrid('getsortinformation').sortdirection.descending ? "desc" : "");
			post = post+'&sortorder='+sortorder;
			
		}
		post = post+'&jeniskelamin='+$("#jenis_kelamin option:selected").text()+'&bpjs='+$("#jenis_bpjs option:selected").text()+'&urutan='+$("#urutan_usia option:selected").text();
		
		$.post("<?php echo base_url()?>hot/pasien/export",post,function(response	){
			$("#export-loader").hide();
	      	$("#btn-export").show('fade');
			window.location.href=response;
			// alert(response);
		});
	});
    

</script>