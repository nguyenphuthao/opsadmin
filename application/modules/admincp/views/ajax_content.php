<div id="registerChart" style="width: 98%;height: 450px;padding: 10px"></div>
<script type="text/javascript">
    var days = <?=$num?>;
    //alert(days);die;
    $(function () {
        $("#startDate").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            yearRange: "1930:2050"
        });

        $("#endDate").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            yearRange: "1930:2050"
        });

        var chart;
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'registerChart',
                type: 'column'
            },
            title: {
                text: 'Thống kê tài khoản đăng ký'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: [<?=$days?>]
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Tài khoản'
                }
            },
            legend: {
                layout: 'vertical',
                backgroundColor: '#FFFFFF',
                align: 'left',
                verticalAlign: 'top',
                x: 100,
                y: 70,
                floating: true,
                shadow: true
            },
            tooltip: {
                formatter: function () {
                    return this.series.name + ': <span style="color: blue">' + this.y + '</span> tài khoản';
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [
                {
                    name: 'Trực tiếp',
                    data: [<?php
					$val_1 = array();
					if(empty($data[0]))
						$data[0] = array();
					for($i = 0; $i < $num; $i ++){
						$flag = FALSE;
						foreach($data[0] as $key=>$item){
							if($item->date == date('Y-m-d',strtotime($endDate . " -".($num - $i -1)." day"))){
								$flag = TRUE;
								break;
							}
						}
						$val_1[$i] = 0;
						if($flag)
							$val_1[$i] = $item->total;
						else
							$val_1[$i] = 0;
						echo $val_1[$i];	
						if($i < $num-1)
							echo ',';
					} ?>]
                },
                {
                    name: 'Giới thiệu',
                    data: [<?php
					$val_2 = array();
					if(empty($data[1]))
						$data[1] = array();
					for($i = 0; $i < $num; $i ++){
						$flag = FALSE;
						foreach($data[1] as $key=>$item){
							if($item->date == date('Y-m-d',strtotime($endDate . " -".($num - $i -1)." day"))){
								$flag = TRUE;
								break;
							}
						}
						$val_2[$i] = 0;
						if($flag)
							$val_2[$i] = $item->total;
						else
							$val_2[$i] = 0;
						echo $val_2[$i];	
						if($i < $num-1)
							echo ',';
					} ?>]

                },
                {
                    name: 'Tổng',
                    data: [<?php
					for($i = 0; $i < $num; $i ++){
						echo $val_1[$i] + $val_2[$i];
						if($i < $num-1)
							echo ',';
					} ?>]

                }
            ]
        });
    });
</script>

<div class="clearAll"></div>

<!-- Begin of Thong ke new -->
<div class="ajaxthongkeByReg">
    <div id="registerChartByReg" style="width: 98%;height: 450px;padding: 10px"></div>
    <script type="text/javascript">

        var daysByIP = 20;
        $(function () {
            $('#registerChartByReg').highcharts({
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false
                    },
                    title: {
                        text: 'Thống kê kênh đăng ký'
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: false
                            },
                            showInLegend: true
                        }
                    },
                    series: [{
                        type: 'pie',
                        name: 'Thống kê kênh đăng ký',
                        data: [
                            ['Trực tiếp',   <?=$reportReg['tructiep']?>],
                            {
                                name: 'Xã hội',
                                y: <?=$reportReg['xahoi']?>,
                                sliced: true,
                                selected: true
                            }
                            // ['Safari',    8.5],
                            // ['Opera',     6.2],
                            // ['Others',   0.7]
                        ]
                    }]
            });
        })
    </script>
</div>
<!-- End of Thong ke new -->

<div>
    <h3 class="ref-heading" style="margin:20px auto">Thông kê chi tiết giới thiệu</h3>
    <table class="refTable" border="1" cellspacing="0">
        <tr>
            <th class="ref-header">&nbsp;</th>
            <?php
            $a_days = explode(',', str_replace("'", '', $days));
            foreach ($a_days as $item) {
                ?>
                <th class="source-total"><?= $item ?></th>
            <?php } ?>
        </tr>
        <?php

        foreach ($refName as $k => $item) {
            ?>
            <tr>
                <td class="ref-header"><?= $item ?></td>
                <?php

                for ($i = 0; $i < $num; $i++) {
                    $flag = FALSE;
                    $val = 0;
                    foreach ($reportRef as $key => $item2) {
                        // pr(date('d-m',strtotime($key)),1);
                        if (isset($item2->referer)) {
                            if ($a_days[$i] == date('d-m', strtotime($item2->date)) && $item == $item2->referer) {
                                $val = $item2->total;
                            }
                        } elseif (isset($item2->utm_source)) {
                            if ($a_days[$i] == date('d-m', strtotime($item2->date)) && $item == $item2->utm_source) {
                                $val = $item2->total;
                            }
                        }
                    }
                    echo "<td>$val</td>";
                }
                ?>
            </tr>
        <?php
        }
        ?>
    </table>
</div>
<div class="clearAll"></div>

<script type="text/javascript">
    $(document).ready(function () {
        var tables = $('.refTable');
        for (k = 0; k < 2; k++) {
            var rows = tables.eq(k).find('tr');
            var size = rows.size();
            for (j = 1; j <= days; j++) {
                $total = 0;
                for (i = 1; i <= size; i++) {
                    var row = rows.eq(i);
                    $total += Number(row.find('td').eq(j).text());
                }
                rows.eq(0).find('th').eq(j).append(' (<span style="color: blue">' + $total + '</span>)');
            }
        }

    });
</script>