<div class="row">
  <div class="col-md-6">
    <table class="table table-bordered table-hover">
      <tr>
        <th>Disabilitas</th>
        <th>Jumlah</th>
        <th>Persentase</th>
      </tr>
      <tr>
        <td>Sedang</td>
        <td align="right"><a onClick="getList('<?php echo "Sedang"?>')" href="#tampildata"><?php echo $sedang;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Sedang"?>')" href="#tampildata"><?php echo ($sedang>0) ? number_format($sedang/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Pernah</td>
        <td align="right"><a onClick="getList('<?php echo "Pernah"?>')" href="#tampildata"><?php echo $pernah;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Pernah"?>')" href="#tampildata"><?php echo ($pernah>0) ? number_format($pernah/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Tidak Pernah</td>
        <td align="right"><a onClick="getList('<?php echo "Tidak Pernah"?>')" href="#tampildata"><?php echo $tidakpernah;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Tidak Pernah"?>')" href="#tampildata"><?php echo ($tidakpernah>0) ? number_format($tidakpernah/$jumlahorang*100,2):0; echo " %";?></a></td>
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
  <div class="row"> 
        <div class="col-md-2">
            
        </div>
        <div class="col-md-3">
            <div class="bux"></div> &nbsp; <label>Sedang</label>
        </div>
        <div class="col-md-3">
            <div class="bux1"></div> &nbsp; <label>Pernah</label>
        </div>
        <div class="col-md-4">
            <div class="bux2"></div> &nbsp; <label>Tidak Pernah</label>
        </div>
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
              value: ";echo number_format(($sedang>0) ? $sedang/$jumlahorang*100:0,2).",
              color: \"".'#0066FF'."\",
              highlight: \"".'#0066FF'."\",
              label: \"".'Sedang'."\"
              },
              {
              value: ";echo number_format(($pernah>0) ? $pernah/$jumlahorang*100:0,2).",
              color: \"".'#0DCF0D'."\",
              highlight: \"".'#0DCF0D'."\",
              label: \"".'Pernah'."\"
              },
              {
              value: ";echo number_format(($tidakpernah>0) ? $tidakpernah/$jumlahorang*100:0,2).",
              color: \"".'#00FF7F'."\",
              highlight: \"".'#00FF7F'."\",
              label:  \"".'Tidak Pernah'."\"
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