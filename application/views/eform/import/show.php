<section class="content">
<form action="<?php echo base_url()?>eform/import/dodel_multi" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">{title_form}</h3>
        </div>
        <div class="box-footer">
            <button type="button" class="btn btn-primary" onclick="document.location.href='<?php echo base_url()?>eform/data_kepala_keluarga/import_add'"><i class='fa fa-arrow-circle-o-down'></i> &nbsp; Import *.xlsx</button>
            <button type="button" class="btn btn-success" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
        <div class="box-body">
          <div class="row">
             <div class="col-md-4">
              <label> Kecamatan </label>
              <select name="kecamatan" id="kecamatan" class="form-control">
                <?php foreach ($datakecamatan as $kec ) { ;?>
                <?php $select = $kec->code == substr($this->session->userdata('puskesmas'), 0,7)  ? 'selected=selected' : '' ?>
                  <option value="<?php echo $kec->code; ?>" <?php echo $select ?>><?php echo $kec->nama; ?></option>
                <?php } ;?>
                </select>
             </div>
             <div class="col-md-4">
             <label> Kelurahan </label>
              <select name="kelurahan" id="kelurahan" class="form-control">
                </select>
             </div>
             <div class="col-md-4">
               <div class="row">
                 <div class="col-md-6">
                  <label> Tahun </label>
                  <select name="tahunfilter" id="tahunfilter" class="form-control">
                      <option value="all">All</option>
                    <?php for($tahun=date("Y"); $tahun >=date("Y")-10; $tahun-- ) { 
                      $select = $tahun == date("Y") ? 'selected' : '' ;
                    ?>
                      <option value="<?php echo $tahun; ?>" <?php echo $select ?> ><?php echo $tahun; ?></option>
                    <?php } ;?>
                    </select>
                  </div>
                <div class="col-md-6">
                  <label> Bulan </label>
                  <select name="bulanfilter" id="bulanfilter" class="form-control">
                  <option value="all">All</option>
                    </select>
                  </div>
                 </div>
             </div>
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
</form>
</section>

<script type="text/javascript">
  $(function () { 
      $("#menu_ketuk_pintu").addClass("active");
      $("#menu_eform_import").addClass("active");
  });
     var source = {
      datatype: "json",
      type  : "POST",
      datafields: [
      { name: 'id_import', type: 'string'},
      { name: 'username', type: 'string'},
      { name: 'id_data_keluarga', type: 'string'},
      { name: 'no_anggota', type: 'string'},
      { name: 'keterangan', type: 'string'},
      { name: 'jumlah', type: 'string'},
      { name: 'status', type: 'string'},
      { name: 'jam', type: 'string'},
      { name: 'tanggal', type: 'date'},
      { name: 'edit', type: 'number'},
      { name: 'delete', type: 'number'}
        ],
    url: "<?php echo site_url('eform/import/json'); ?>",
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
     
    $('#btn-refresh').click(function () {
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
        { text: 'Detail', align: 'center', filtertype: 'none', sortable: false, width: '8%', cellsrenderer: function (row) {
            var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
            if(dataRecord.edit==1){
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit(\""+dataRecord.id_data_keluarga+"\",\""+dataRecord.id_import+"\",\""+dataRecord.username+"\",\""+dataRecord.no_anggota+"\");'></a></div>";
          }else{
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
          }
                 }
                },
        { text: 'Tanggal', editable:false ,align: 'center', cellsalign:'center',datafield: 'tanggal', columntype: 'textbox',  width: '20%',filtertype: 'none',cellsformat: 'dd-MM-yyyy' },
        { text: 'Jam', editable:false ,align: 'center', datafield: 'jam', cellsalign:'center', columntype: 'textbox', filtertype: 'none', width: '20%' },
        
        { text: 'Username', editable:false ,datafield: 'username', cellsalign:'center', align:'center',columntype: 'textbox', filtertype: 'textbox', width: '20%' },
        { text: 'Jumlah', editable:false ,align: 'center',cellsalign:'center', datafield: 'jumlah', columntype: 'textbox', filtertype: 'none', width: '32%' },
        ]
    });

  function edit(id_keluarga,id_import,username,no_anggota){
    document.location.href="<?php echo base_url().'eform/import/edit';?>/" + id_keluarga+'/'+id_import+'/'+username+'/'+no_anggota ;
  }

  function del(id){
    var confirms = confirm("Hapus Data Kegiatan ?");
    if(confirms == true){
      $.post("<?php echo base_url().'eform/import/dodel' ?>/"+id,  function(){
        alert('data berhasil dihapus');

        $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
      });
    }
  }
  $("select[name='code_cl_phc']").change(function(){
    $.post("<?php echo base_url().'eform/import/filter' ?>", 'code_cl_phc='+$(this).val(),  function(){
      $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
    });
    });
 
  $('#kecamatan').change(function(){
      var kecamatan = $(this).val();
      $.ajax({
        url : '<?php echo site_url('eform/import/get_kecamatanfilter') ?>',
        type : 'POST',
        data : 'kecamatan=' + kecamatan,
        success : function(data) {
          $('#kelurahan').html(data);
          $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
        }
      });

      return false;
    }).change();
    $('#kelurahan').change(function(){
      var kelurahan = $(this).val();
      $.ajax({
        url : '<?php echo site_url('eform/import/get_kelurahanfilter') ?>',
        type : 'POST',
        data : 'kelurahan=' + kelurahan,
        success : function(data) {
          $('#rukunwarga').html(data);
          $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
        }
      });

      return false;
    }).change();
  
    $('#tahunfilter').change(function(){
      var tahunfilter = $(this).val();
      var bulanfilter = $("#bulanfilter").val();
      if(tahunfilter == "" || tahunfilter === null|| tahunfilter == 'all'|| bulanfilter == 'all'){
        // $("#btn-export").hide();
      }else{
        // $("#btn-export").show('fade');
      }
      $.ajax({
        url : '<?php echo site_url('eform/import/get_filtertahundata') ?>',
        type : 'POST',
        data : 'tahunfilter=' + tahunfilter+'&bulanfilter=' + bulanfilter,
        success : function(data) {
          $('#bulanfilter').html(data);
            $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
        }
      });

      return false;
    }).change();
$('#bulanfilter').change(function(){
      var bulanfilter = $(this).val();
      var tahunfilter = $("#tahunfilter").val();
      if(bulanfilter == "" || bulanfilter === null|| bulanfilter == 'all'|| tahunfilter == 'all'){
        // $("#btn-export").hide();
      }else{
        // $("#btn-export").show('fade');
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
</script>