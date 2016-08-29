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
		        <div id="jqxgrid"></div>
			</div>
	    </div>
	  </div>
</div>

<script type="text/javascript">
	   var source = {
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
			{ name: 'no_anggota', type: 'string'},
			{ name: 'nama', type: 'string'},
			{ name: 'nik', type: 'string'},
			{ name: 'tmpt_lahir', type: 'string'},
			{ name: 'tgl_lahir', type: 'date'},
			{ name: 'id_pilihan_hubungan', type: 'string'},
			{ name: 'usia', type: 'string'},
			{ name: 'jeniskelamin', type: 'string'},
			{ name: 'bpjs', type: 'string'},
			{ name: 'hubungan', type: 'string'},
			{ name: 'id_pilihan_kelamin', type: 'string'},
			{ name: 'id_pilihan_agama', type: 'string'},
			{ name: 'id_pilihan_pendidikan', type: 'string'},
			{ name: 'id_pilihan_pekerjaan', type: 'string'},
			{ name: 'id_pilihan_kawin', type: 'string'},
			{ name: 'id_pilihan_jkn', type: 'string'},
			{ name: 'suku', type: 'string'},
			{ name: 'no_hp', type: 'string'},
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('eform/chart_kpldh/json_laporan'); ?>",
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

		$("#jqxgrid").jqxGrid({		
			width: '100%',
			selectionmode: 'singlerow',
			source: dataadapter, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: false,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},
			columns: [
				{ text: 'Detail', align: 'center', filtertype: 'none', sortable: false, width: '8%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
					return "<div style='width:100%;padding:4px;text-align:center' onclick='edit(\""+dataRecord.id_data_keluarga+"\");'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' ></a></div>";
                 }
                },
				{ text: 'NIK', datafield: 'nik', columntype: 'textbox', align:'center', cellsalign:'center', filtertype: 'textbox', width: '20%' },
				{ text: 'Nama', datafield: 'nama', columntype: 'textbox', filtertype: 'textbox', width: '28%',align:'center', cellsalign:'left' },
                { text: 'Tgl Lahir', datafield: 'tgl_lahir', columntype: 'textbox', align:'center', cellsalign:'center', filtertype: 'date',cellsformat: 'dd-MM-yyyy', width: '10%' },
				{ text: 'Usia', datafield: 'usia', columntype: 'textbox', filtertype: 'textbox',align:'center', cellsalign:'right', width: '8%' },
				{ text: 'Jenis Kelamin', datafield: 'jeniskelamin', columntype: 'textbox', filtertype: 'textbox', width: '12%',align:'center', cellsalign:'left' },
				{ text: 'Status', datafield: 'hubungan', columntype: 'textbox', filtertype: 'textbox', width: '14%',align:'center', cellsalign:'left' },
			]
		});

	function edit(id){
		window.open("<?php echo base_url().'eform/data_kepala_keluarga/edit';?>/" + id);
	}

</script>