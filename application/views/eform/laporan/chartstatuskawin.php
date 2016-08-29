<div class="row">
  <div class="col-md-6">
    <table class="table table-bordered table-hover">
      <tr>
        <th>Status Kawin</th>
        <th>Jumlah</th>
        <th>Persentase</th>
      </tr>

      <?php 
      $total =0;
      foreach ($showstatuskawin as $rows) { 
        $total = $rows->total;
      ?>
      <tr>
        <td><?php echo $rows->status; ?></td>
        <td align="right"><a onClick="getList('<?php echo "$rows->status"?>')" href="#tampildata"><?php echo $rows->jumlah;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "$rows->status"?>')" href="#tampildata"><?php echo number_format($rows->jumlah/$jumlahorang*100,2); echo " %";?></a></td>
      </tr>
      <?php } ?>
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
      <div class="col-md-3">
          <div class="bux1"></div> &nbsp; <label>Belum Kawin</label>
      </div>
      <div class="col-md-4">
          <div class="bux"></div> &nbsp; <label>Kawin</label>
      </div>
      <div class="col-md-5">
          <div class="bux2"></div> &nbsp; <label>Janda/Duda</label>
      </div>

    </div>
  </div>
</div>
<style type="text/css">

      .bux{
        width: 10px;
        padding: 10px; 
        margin-right: 40%;
        background-color: #0DCF0D;
        margin: 0;
        float: left;
      }
      .bux1{
        width: 10px;
        padding: 10px;
        background-color: #0066FF;
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
<?php  //print_r($color);?>
<script>
  $(function () { 
    
    //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var PieData = [<?php 
           /* $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['jumlah'])) $x = ($row['jumlah']);
              else                          $x = 0;
              if($i>0) echo ",";
              echo "
              {
              value: ".$x.",
              color: \"".$color[$i]."\",
              highlight: \"".$color[$i]."\",
              label: \"".$row['kelamin']."\"
              }";
              $i++;
            }*/
            $i=0;
         foreach ($showstatuskawin as $row) {
            if($i>0) echo ",";
            echo "
              {
              value: ";echo number_format($row->jumlah/$jumlahorang*100,2).",
              color: \"".$color[$i]."\",
              highlight: \"".$color[$i]."\",
              label: \"".$row->status."\"
              }"; 
            $i++;
          }
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