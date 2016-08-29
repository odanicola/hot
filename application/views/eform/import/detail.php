<div class="box-footer" >
  <div class="row">
    <div class="col-md-6">
      <div >
        <button type="button" class="btn btn-primary" id="btn-refresh-pesertabpjs"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
        <button type="button" id="btn-kembali" class="btn btn-warning"><i class='fa fa-reply'></i> &nbsp; Kembali</button>
      </div>
    </div>
    <div class="col-md-3">  
    </div>  
    <div class="col-md-3">  
      <select name="statushomevisit" id="statushomevisit" class="form-control">
        <?php foreach ($datafilterimport as $stat => $valstat ) { ;?>
        <?php $select = $stat == $this->session->userdata('filter_status_homevisit')  ? 'selected=selected' : '' ?>
          <option value="<?php echo $stat; ?>" <?php echo $select ?>><?php echo $valstat; ?></option>
        <?php } ;?>
        </select>
    </div>
  </div>
</div>
 <div id="jqxgrid"></div>

<script type="text/javascript">
     var source = {
      datatype: "json",
      type  : "POST",
      datafields: [
      { name: 'id_data_keluarga', type: 'string' },
      { name: 'nama', type: 'string' },
      { name: 'no_anggota', type: 'string' },
      { name: 'id_import', type: 'string' },
      { name: 'username', type: 'string' },
      { name: 'bpjs', type: 'string' },
      { name: 'id_pilihan_kelamin', type: 'string' },
      { name: 'tgl_lahir', type: 'date' },
      { name: 'tgl_lahirdata', type: 'string' },
      { name: 'usia', type: 'string' },
      { name: 'jeniskelamin', type: 'string' },
      { name: 'nik', type: 'string' },
      { name: 'status', type: 'string' },
      { name: 'ceklis', type: 'number' },
      { name: 'edit', type: 'number' },
        ],
    url: "<?php echo base_url().'eform/import/json_detail/'; ?>{id_data_keluarga}/{id_import}/{username}",    
    cache: false,
    updateRow: function (rowID, rowData, commit) {
      
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
     
    $('#btn-refresh-pesertabpjs').click(function () {
      $("#jqxgrid").jqxGrid('clearfilters');
    });

    $("#jqxgrid").jqxGrid(
    {   
      width: '100%',
      selectionmode: 'singlerow',
      source: dataadapter, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100', '200'],
      showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: true,
      rendergridrows: function(obj)
      {
        return obj.data;
      },
      columns: [
        { text: 'Status Home-Visit', align: 'center', filtertype: 'none', sortable: false, width: '12%', cellsrenderer: function (row) {
              var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
              if(dataRecord.edit==1 && dataRecord.status=='0'){
              return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><input type='checkbox' onclick='edit(\""+dataRecord.nik+"\",\""+dataRecord.bpjs+"\",\""+dataRecord.id_import+"\",\""+dataRecord.username+"\",\""+dataRecord.id_data_keluarga+"\",\""+dataRecord.no_anggota+"\");'></a></div>";
            }else{
              return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><input type='checkbox' checked disabled></a></div>";
            }
          }
        },
        { text: 'No Kartu', align: 'center',cellsalign: 'center',editable: false, datafield: 'bpjs', columntype: 'textbox', filtertype: 'textbox', width: '16%' },
        { text: 'NIK', align: 'center',cellsalign: 'center',editable: false,datafield: 'nik', columntype: 'textbox', filtertype: 'textbox', width: '16%'},
        { text: 'Nama Peserta ', editable: false,datafield: 'nama', columntype: 'textbox', filtertype: 'textbox', width: '24%'},
        { text: 'Jenis Kelamin ', align: 'center',cellsalign: 'center',editable: false,datafield: 'jeniskelamin', columntype: 'textbox', filtertype: 'textbox', width: '11%'},
        { text: 'Tanggal Lahir',align: 'center',cellsalign: 'center', editable: false,datafield: 'tgl_lahir', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '12%'},
        { text: 'Usia', align: 'center',cellsalign: 'center',editable: false, datafield: 'usia', columntype: 'textbox', filtertype: 'textbox', width: '9%'}
            ]
    });
    
     function edit(nik,bpjs,id_import,username,id_data_keluarga,no_anggota){
       
      if(bpjs.length==13 || nik.length==16){
        if (bpjs.length==13) {
          $.get("<?php echo base_url()?>eform/import/bpjs_search/bpjs/"+bpjs,function(res){
              if(res.metaData.code=="200"){
                if(confirm("Anggota keluarga terdaftar sebagai peserta BPJS "+res.response.kdProviderPst.nmProvider+". \nJenis Peserta "+res.response.jnsPeserta.nama+", Status "+res.response.ketAktif+". \nTunggakan sebesar Rp. "+res.response.tunggakan+"\nGunakan data?")){
                  $.get("<?php echo base_url().'eform/import/addpesetabpjsterdaftar/'; ?>"+nik+'/'+res.response.noKartu+'/'+id_import+'/'+username+'/'+id_data_keluarga+'/'+no_anggota,function(databp){
                      var resd = databp.split("|");
                      if (resd[0]=="OK") {
                        alert(resd[1]);
                      }else{
                        alert(resd[1]);
                      }
                      $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
                  });
                }else{
                  $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
                }
              }else{
                alert("Peserta tidak terdaftar sebagai angota BPJS");
                $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
              }
          },"json");
        }else if (nik.length==16) {
          $.get("<?php echo base_url()?>eform/import/bpjs_search/nik/"+nik,function(res){
              if(res.metaData.code=="200"){
                if(confirm("Nomor terdaftar sebagai peserta \nBPJS "+res.response.kdProviderPst.nmProvider+", \nGunakan data?")){
                  $.get("<?php echo base_url().'eform/import/addpesetabpjsterdaftar/'; ?>"+nik+'/'+res.response.noKartu+'/'+id_import+'/'+username+'/'+id_data_keluarga+'/'+no_anggota,function(databp){
                      var resd = databp.split("|");
                      if (resd[0]=="OK") {
                        alert(resd[1]);
                      }else{
                        alert(resd[1]);
                      }
                      $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
                  });
                }else{
                  $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
                }
              }else{
                alert("Peserta tidak terdaftar sebagai angota BPJS");
                $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
              }
          },"json");
        }
      }else{
        alert("Pastikan Nomor NIK atau BPJS Benar");
        $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
      }
      ambil_data_bpjs();  
   }

  function add_pesertabpjs(data_pesertabpjs){
    
    $.get("<?php echo base_url().'eform/import/add_pesertabpjs/'; ?>"+data_pesertabpjs ,  function(data) {
        res = data.split('|');
        if (res[0]=='OK') {
            $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
        }else{
          alert(res[1]);
        }
          
    });
  }
  
  function doList(){
    var data = $("input[name='pesertaceklis']:checked").val();
    alert(data);
  }
   $('#bulanfilter').change(function(){
      var bulanfilter = $(this).val();
      var tahunfilter = $("#tahunfilter").val();
      if(bulanfilter == "" || bulanfilter === null|| bulanfilter == 'all'|| tahunfilter == 'all'){
      	$("#btn-export").hide();
      }else{
      	$("#btn-export").show('fade');
      }
      $.ajax({
        url : '<?php echo site_url('eform/import/get_filterbulandata') ?>',
        type : 'POST',
        data : 'bulanfilter=' + bulanfilter+'&tahunfilter=' + tahunfilter,
        success : function(data) {
          $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
        }
      });

      return false;
    });
   $('#statushomevisit').change(function(){
      var statushomevisit = $(this).val();
      $.ajax({
        url : '<?php echo site_url('eform/import/get_filterstatushomevisit') ?>',
        type : 'POST',
        data : 'statushomevisit=' + statushomevisit,
        success : function(data) {
            $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
        }
      });

      return false;
    }).change();
   function ambil_data_bpjs()
  {
    $.ajax({
    url: "<?php echo base_url().'eform/import/total_data_bpjs/'.$id_keluarga.'/'.$id_import.'/'.$username.'/'.$no_anggota ?>",
    dataType: "json",
    success:function(data)
    { 
      $.each(data,function(index,elemet){
        $("#jmlbpjs").html(elemet.jmlbpjs);
        $("#jmlhomevisit").html(elemet.sudah);
        $("#jmlblmhomevisit").html(elemet.belum);
      });
    }
    });

    return false;
  }
  $(function(){
      ambil_data_bpjs();  
  });
</script>