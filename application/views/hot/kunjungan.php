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
	      	<a href="<?php echo base_url()?>hot/kunjungan/daftar">
		 		<button type="button" class="btn btn-primary"><i class='fa fa-plus-square-o'></i> &nbsp; Pendaftaran</button>
		 	</a>
		 	<button type="button" class="btn btn-success" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
		 	<?php if($this->session->userdata('level')!="pasien"){ ?>
			<div class="row" style="padding-top:15px">
			  <div class="col-xs-4">
			  		<select class="form-control" id="tahun">
			  			<?php
			  			foreach($tahun_option as $x=>$y){
			  				echo "<option value='".$x."' ".($x==$filter_tahun ? 'selected':'').">".$y."</option>";
			  			}
			  			?>
			  		</select>
			  </div>
			  <div class="col-xs-5" style="padding-left:2px">
			  		<select class="form-control" id="bulan">
			  			<?php
			  			foreach($bulan_option as $x=>$y){
			  				if($x>0) {
			  					echo "<option value='".$x."'".($x==$filter_bulan ? 'selected':'').">".$y."</option>";
			  				}
			  			}
			  			?>
			  		</select>
			  </div>
			  <div class="col-xs-3" style="padding-left:2px">
			  		<select class="form-control" id="tanggal">
			  			<?php
			  			foreach($tanggal_option as $x=>$y){
			  				if($x>0) {
			  					echo "<option value='".$x."'".($y==$filter_tanggal ? 'selected':'').">".$y."</option>";
			  				}
			  			}
			  			?>
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
			<?php } ?>
			<div class="row" style="padding-top:5px">
			  <div class="col-xs-6" style="text-align:right;padding:5px">Status</div>
			  <div class="col-xs-6">
			  		<select class="form-control" id="status_antri">
			  			<option value="">-</option>
			  			<option value="antri" <?php echo ("antri"==$filter_status_antri ? 'selected':'')?>>Antri</option>
			  			<option value="periksa" <?php echo ("periksa"==$filter_status_antri ? 'selected':'')?>>Periksa</option>
			  			<option value="selesai" <?php echo ("selesai"==$filter_status_antri ? 'selected':'')?>>Selesai</option>
			  			<option value="batal" <?php echo ("batal"==$filter_status_antri ? 'selected':'')?>>Batal</option>
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
		$("#menu_hot_kunjungan").addClass("active");
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
			{ name: 'tgl', type: 'string'},
			{ name: 'waktu', type: 'string'},
			{ name: 'username', type: 'string'},
			{ name: 'jk', type: 'string'},
			{ name: 'usia', type: 'int'},
			{ name: 'nama', type: 'string'},
			{ name: 'bpjs', type: 'string'},
			{ name: 'phone_number', type: 'string'},
			{ name: 'status_antri', type: 'string'},
			{ name: 'cl_pid', type: 'string'},
			{ name: 'reg_id', type: 'string'}
        ],
		url: "<?php echo site_url('hot/kunjungan/json'); ?>",
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
			width: '100%', autorowheight: true,
			selectionmode: 'singlerow',
			source: dataadapter, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50'],
			showfilterrow: false, filterable: false, sortable: false, autoheight: true, pageable: true, virtualmode: false, editable: false,
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
		 	<?php if($this->session->userdata('level')!="pasien"){ ?>
                { text: 'Nama', datafield: 'nama', align: 'center', width: '43%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
					return "<div style='width:100%;padding:7px;'>"+dataRecord.nama+"<br>"+dataRecord.jk+"<br>"+dataRecord.usia+" Tahun"+"</div>";
                 }
                },
		 	<?php }else{ ?>
                { text: 'Tanggal', datafield: 'tgl', align: 'center', width: '43%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.waktu != null){
						return "<div style='width:100%;padding:14px;text-align:center;'>"+dataRecord.tgl+"<br>"+dataRecord.waktu+"</div>";
				    }else{
						return "<div style='width:100%;padding:14px;text-align:center;'>"+dataRecord.tgl+"</div>";
				    }
                 }
                },
		 	<?php } ?>
				{ text: 'Status', datafield: 'bpjs', align: 'center', width: '45%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.cl_pid != null && dataRecord.cl_pid != ""){
				    	var status_mr = "";
				    }else{
				    	var status_mr = "<br><span style='color:red'>MR belum terhubung</span>";
				    }
				    if(dataRecord.bpjs != null && dataRecord.bpjs != ""){
				    	var status_bpjs = "Peserta BPJS";
				    }else{
				    	var status_bpjs = "<span style='color:orange'>Non BPJS</span>";
				    }
				    if(dataRecord.reg_id != null && dataRecord.reg_id != ""){
				    	var status_reg_id = "";
				    }else{
				    	if(dataRecord.cl_pid != null && dataRecord.cl_pid != "" && dataRecord.status_antri=="Antri"){
				    		check_reg_id(dataRecord.cl_pid, dataRecord.id_kunjungan);
			            }

				    	var status_reg_id = "<br><span style='color:red'>Belum daftar ulang</span>";
				    }

				    if(status_mr == "" && status_reg_id == ""){
						return "<div style='width:100%;padding-top:14px;text-align:center;color:green' >"+dataRecord.status_antri+" <br> "+	status_bpjs+"</div>";
				    }else{
						return "<div style='width:100%;padding-top:14px;text-align:center' >"+dataRecord.status_antri+" / "+	status_bpjs+status_mr+status_reg_id+"</div>";
				    }
                 }
                }            
            ]
		});

		$("#jqxgrid").on('rowselect', function (event) {
			var args = event.args;
			var rowData = args.row;

			if(rowData.status_antri != "Batal"){
				if(rowData.cl_pid != null && rowData.cl_pid != ""){
					if(rowData.reg_id != null && rowData.reg_id != ""){
	        			$("#popup_content").html("<div style='padding:5px' align='center'><br>"+rowData.nama+"</br><br><div style='text-align:center'><input class='btn btn-primary' style='width:100px' type='button' value='Pengukuran' onClick='btn_edit(\""+rowData.id_kunjungan+"\")'>&nbsp;&nbsp;<input class='btn btn-warning' style='width:100px' type='button' value='Close' onClick='close_popup();'></div></div>");
	        		}else{
	        			$("#popup_content").html("<div style='padding:5px' align='center'><br>"+rowData.nama+"</br><br><div style='text-align:center'><input class='btn btn-warning' style='width:100px' type='button' value='Close' onClick='close_popup();'></div></div>");
	        		}
	        	}else{
	        		$("#popup_content").html("<div style='padding:5px' align='center'><br>"+rowData.nama+"</br><br><div style='text-align:center'><input class='btn btn-primary' style='width:100px' type='button' value='Edit Profile' onClick='btn_profile(\""+rowData.username+"\")'>&nbsp;&nbsp;<input class='btn btn-warning' style='width:100px' type='button' value='Close' onClick='close_popup();'></div></div>");
	        	}
			}else{
        		$("#popup_content").html("<div style='padding:5px' align='center'><br>"+rowData.nama+"</br><br><div style='text-align:center'><input class='btn btn-danger' style='width:100px' type='button' value='Delete' onClick='btn_delete(\""+rowData.id_kunjungan+"\")'>&nbsp;&nbsp;<input class='btn btn-warning' style='width:100px' type='button' value='Close' onClick='close_popup();'></div></div>");
			}
 			$("html, body").animate({ scrollTop: 0 }, "slow");
			$("#popup").jqxWindow('open');
		});

	function check_reg_id(id, id_kunjungan){
	    $.ajax({
	        dataType : 'json',
	        url : '<?php echo base_url()."epus_api/pasien_reg_update/"?>' + id + '/' + id_kunjungan,
	        success : function(response){
	        	alert(response);
	        }
	    });
	}

	function btn_profile(id){
      	document.location.href="<?php echo base_url()?>hot/pasien/edit/" + id;
	}

	function btn_edit(id){
      	document.location.href="<?php echo base_url()?>hot/kunjungan/edit/" + id;
	}

	function btn_delete(id){
		$("#popup_content").html("<div style='padding:5px' align='center'><br>Hapus Pendaftaran?</br><br><div style='text-align:center'><input class='btn btn-danger' style='width:100px' type='button' value='Ya' onClick='del(\""+id+"\")'>&nbsp;&nbsp;<input class='btn btn-warning' style='width:100px' type='button' value='Tidak' onClick='close_popup();'></div></div>");
	}

	function del(id){
		$.post("<?php echo base_url().'hot/kunjungan/del/' ?>", 'id_kunjungan='+id,  function(res){
			$("#popup_content").html("<div style='padding:5px' align='center'><br>Hapus Data "+res+"</br><br><div style='text-align:center'><input class='btn btn-warning' style='width:100px' type='button' value='Close' onClick='close_popup();'></div></div>");
			$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
		});
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