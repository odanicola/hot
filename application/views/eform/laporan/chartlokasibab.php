<div class="row">
  <div class="col-md-6">
    <table class="table table-bordered table-hover">
      <tr>
        <th>Fasilitas Jamban</th>
        <th>Jumlah</th>
        <th>Persentase</th>
      </tr>
      <tr>
        <td>Jamban</td>
        <td align="right"><a onClick="getList('<?php echo "Jamban"?>')" href="#tampilda ta"><?php echo $jamban;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Jamban"?>')" href="#tampildata"><?php echo ($jamban>0) ? number_format($jamban/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Kolam/ Sawah/ Selokan</td>
        <td align="right"><a onClick="getList('<?php echo "Kolam/ Sawah/ Selokan"?>')" href="#tampildata"><?php echo $kolam;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Kolam/ Sawah/ Selokan"?>')" href="#tampildata"><?php echo ($kolam>0) ? number_format($kolam/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Sungai/ Danau/ Laut</td>
        <td align="right"><a onClick="getList('<?php echo "Sungai/ Danau/ Laut"?>')" href="#tampildata"><?php echo $sungai;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Sungai/ Danau/ Laut"?>')" href="#tampildata"><?php echo ($sungai>0) ? number_format($sungai/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Lubang tanah</td>
        <td align="right"><a onClick="getList('<?php echo "Lubang tanah"?>')" href="#tampildata"><?php echo $lubang;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Lubang tanah"?>')" href="#tampildata"><?php echo ($lubang>0) ? number_format($lubang/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Pantai/ Tanah Lapangan/ Kebun/ Halaman</td>
        <td align="right"><a onClick="getList('<?php echo "Pantai/ Tanah Lapangan/ Kebun/ Halaman"?>')" href="#tampildata"><?php echo $pantai;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Pantai/ Tanah Lapangan/ Kebun/ Halaman"?>')" href="#tampildata"><?php echo ($pantai>0) ? number_format($pantai/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td style="font-weight:bold">Total</td>
        <td style="font-weight:bold" align="right"><?php echo $jumlahorang; ?></td>
        <td style="font-weight:bold" align="right"><?php echo ($jumlahorang>0) ? $jumlahorang/$jumlahorang*100 : 0; echo " %";?></td>
      </tr>
      
    </table>
  </div>
  <div class="col-md-6">
    <div class="row" id="row1">
      <div class="chart">
        <canvas id="pieChart" height="240" width="511" style="width: 511px; height: 240px;"></canvas>
      </div>
    </div>
  </div>
  </div>
  <div class="row"> 
  <div class="col-md-3"></div>
    <div class="col-md-9">
      <div class="col-md-2">
          <div class="bux"></div> &nbsp; <label>Jamban</label>
      </div>
      <div class="col-md-4">
          <div class="bux1"></div> &nbsp; <label>Kolam/ Sawah/ Selokan</label>
      </div>
      <div class="col-md-3">
          <div class="bux2"></div> &nbsp; <label>Sungai/ Danau/ Laut</label>
      </div>
      <div class="col-md-3">
          <div class="bux3"></div> &nbsp; <label>Lubang tanah</label>
      </div>
    </div>

  </div>   
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-9">
      <div class="col-md-9">
          <div class="bux4"></div> &nbsp; <label>Pantai/ Tanah Lapangan/ Kebun/ Halaman</label>
      </div> 
    </div>
  </div>
<style type="text/css">

      .bux{
        width: 10px;
        padding: 10px; 
        margin-right: 40%;
        background-color: #0066FF;
        margin: 0;
        float: left;
      }
      .bux1{
        width: 10px;
        padding: 10px;
        background-color: #0DCF0D;
        margin: 0;
        float: left;
      }
      .bux2{
        width: 10px;
        padding: 10px;
        background-color: #00FF7F;
        margin: 0;
        float: left;
      }
      .bux3{
        width: 10px;
        padding: 10px;
        background-color: #85ADAD;
        margin: 0;
        float: left;
      }
      .bux4{
        width: 10px;
        padding: 10px;
        background-color: #FFAA0D;
        margin: 0;
        float: left;
      }
      
</style>
<?php // print_r($bar);?>
<script>
  $(function () { 
    
    //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var PieData = [<?php 
            
            echo "
              {
              value: ";echo number_format(($jamban>0) ? $jamban/$jumlahorang*100:0,2).",
              color: \"".'#0066FF'."\",
              highlight: \"".'#0066FF'."\",
              label: \"".'Jamban'."\"
              },
              {
              value: ";echo number_format(($kolam>0) ? $kolam/$jumlahorang*100:0,2).",
              color: \"".'#0DCF0D'."\",
              highlight: \"".'#0DCF0D'."\",
              label: \"".'Kolam/ Sawah/ Selokan'."\"
              },
              {
              value: ";echo number_format(($sungai>0) ? $sungai/$jumlahorang*100:0,2).",
              color: \"".'#00FF7F'."\",
              highlight: \"".'#00FF7F'."\",
              label: \"".'Sungai/ Danau/ Laut'."\"
              },
              {
              value: ";echo number_format(($lubang>0) ? $lubang/$jumlahorang*100:0,2).",
              color: \"".'#85ADAD'."\",
              highlight: \"".'#85ADAD'."\",
              label: \"".'Lubang tanah'."\"
              },
              {
              value: ";echo number_format(($pantai>0) ? $pantai/$jumlahorang*100:0,2).",
              color: \"".'#FFAA0D'."\",
              highlight: \"".'#FFAA0D'."\",
              label:  \"".'Pantai/ Tanah Lapangan/ Kebun/ Halaman'."\"
              }"; 
            ?>

        ];
        var pieOptions = {
          segmentShowStroke: true,
          segmentStrokeColor: "#fff",
          segmentStrokeWidth: 2,
          percentageInnerCutout: 40, // This is 0 for Pie charts
          animationSteps: 100,
          animationEasing: "easeOutBounce",
          animateRotate: true,
          animateScale: false,
          responsive: true,
          maintainAspectRatio: false,
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
        };
        pieChart.Doughnut(PieData, pieOptions);
  });
</script>