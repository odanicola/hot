<div class="row" style="margin: 10px 0px 0px 0px">
	  <div class="box box-primary">
		  <div class="row" style="padding:10px">
		  	<div class="col-md-10">
		  		<b>Daftar {judul} : {variabel}</b>
		  	</div>
		  	<div class="col-md-2" style="text-align: right">
		  		<button type="button" class="btn btn-success" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
			 </div>
		  </div>
	    <div class="box-body">
		    <div class="div-grid">
		        <div id="jqxgrid_datakepalakeluarga"></div>
			</div>
	    </div>
	  </div>
</div>

<div id="popup_kpldh" style="display:none">
	<div id="popup_title">Nomor Urut Keluarga</div>
	<div id="popup_content">&nbsp;</div>
</div>

<script type="text/javascript">
		
	   var sourcekepala = {
			datatype: "json",
			type	: "POST",
			data : {
				judul 		:"{judul}",
				kecamatan	:"{kecamatan}",
				kelurahan	:"{kelurahan}",
				rw 			:"{rw}",
				rt 			:"{rt}",
				id_judul 	:"{id_judul}",
				variabel 	:"{variabel}"
			},
			datafields: [
			{ name: 'id_data_keluarga', type: 'string'},
			{ name: 'tanggal_pengisian', type: 'date'},
			{ name: 'jam_data', type: 'string'},
			{ name: 'alamat', type: 'string'},
			{ name: 'id_propinsi', type: 'string'},
			{ name: 'id_kota', type: 'string'},
			{ name: 'id_kecamatan', type: 'string'},
			{ name: 'value', type: 'string'},
			{ name: 'rt', type: 'string'},
			{ name: 'rw', type: 'string'},
			{ name: 'norumah', type: 'string'},
			{ name: 'nourutkel', type: 'string'},
			{ name: 'id_kodepos', type: 'string'},
			{ name: 'namadesawisma', type: 'string'},
			{ name: 'id_pkk', type: 'string'},
			{ name: 'namakepalakeluarga', type: 'string'},
			{ name: 'nama_komunitas', type: 'string'},
			{ name: 'notlp', type: 'string'},
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('eform/chart_kpldh/json_laporan_kepala'); ?>",
		cache: false,
		updaterow: function (rowid, rowdata, commit) {
			},
		filter: function(){
			$("#jqxgrid_datakepalakeluarga").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid_datakepalakeluarga").jqxGrid('updatebounddata', 'sort');
		},
		root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){		
			if (data != null){
				sourcekepala.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var jqxgrid_datakepalakeluarga = new $.jqx.dataAdapter(sourcekepala, {
			loadError: function(xhr, status, error){
				alert(error);
			}
		});
     
		$('#btn-refresh').click(function () {
			$("#jqxgrid_datakepalakeluarga").jqxGrid('clearfilters');
		});

		$("#jqxgrid_datakepalakeluarga").jqxGrid({		
			width: '100%',
			selectionmode: 'singlerow',
			source: jqxgrid_datakepalakeluarga, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '50', '100', '200', '500'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: false,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},
			columns: [
				{ text: 'Edit', align: 'center', filtertype: 'none', sortable: false, width: '8%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_datakepalakeluarga").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding:4px;text-align:center' onclick='edit(\""+dataRecord.id_data_keluarga+"\");'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' ></a></div>";
					}else{
						return "<div style='width:100%;padding:4px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'No. Urut', datafield: 'nourutkel', align: 'center', columntype: 'textbox', filtertype: 'textbox', width: '6%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_datakepalakeluarga").jqxGrid('getrowdata', row);
					return "<div style='width:100%;padding:4px;padding-top:6px;text-align:center;font-weight:bold;' onclick='urut(\""+dataRecord.id_data_keluarga+"\");'><a href='javascript:void(0);' style='cursor:pointer;color:#a31919'>"+dataRecord.nourutkel+"</a></div>";
                 }
                },
                { text: 'Tgl Pengisian', datafield: 'tanggal_pengisian', columntype: 'textbox', align:'center', cellsalign:'center', filtertype: 'date',cellsformat: 'dd-MM-yyyy', width: '10%' },
				{ text: 'Kepala Keluarga', datafield: 'namakepalakeluarga', columntype: 'textbox', filtertype: 'textbox', width: '16%' },
				{ text: 'Desa', datafield: 'value', columntype: 'textbox', filtertype: 'textbox', width: '17%' },
				{ text: 'RT', datafield: 'rt', columntype: 'textbox', filtertype: 'textbox', width: '7%' },
				{ text: 'RW', datafield: 'rw', columntype: 'textbox', filtertype: 'textbox', width: '7%' },
				{ text: 'No. Rumah', datafield: 'norumah', columntype: 'textbox', filtertype: 'textbox', width: '9%' },
				{ text: 'Alamat', datafield: 'alamat', columntype: 'textbox', filtertype: 'textbox', width: '20%' }
			]
		});


	function edit(id){
		document.location.href="<?php echo base_url().'eform/data_kepala_keluarga/edit';?>/" + id;
	}

    
</script>