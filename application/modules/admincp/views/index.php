<style>
    .refTable td, .refTable th{padding: 5px;text-align: right;}
    .refTable2 td, .refTable2 th{padding: 5px;text-align: right;}
    td.last{color:blue;}
</style>
<?php
// echo "<pre>";
// print_r();
// exit;
$EndDate = date('Y-m-d');
$StartDate  = strtotime($EndDate);
$StartDate = strtotime('-20 day', $StartDate);
$StartDate = date('Y-m-d',$StartDate);
?>
<script>
    $(function () {
        jQuery(".thongke").click(function () {
            var options = {
                url: "<?= PATH_URL ?>admincp/statistics",
                success: function (response) {
                    $('.ajaxthongke').html(response);
                    updateReport();
                } // post-submit callback
            };
            $('#formthongke').ajaxSubmit(options);
        })

    })
</script>
<input type="hidden" value="<?= $module ?>" id="module"/>
<div class="title"><h5>Dashboard</h5></div>
<div style="margin-top: 20px; width: 50%; float: left;">
    - Số tài khoản đăng ký trong ngày: <strong style="font-size: 16px;"><?= $reg_today ?></strong><br/>
    - Tổng số tài khoản đã đăng ký: <strong
        style="font-size: 16px;"><?= number_format($reg_full, 0, '', '.') ?></strong><br/>
    - Tổng địa chỉ IP đăng ký khác nhau: <strong
        style="font-size: 16px;"><?php echo number_format($countIP, 0,'','.');?></strong><br/>
</div>
<div style="margin-top: 20px; width: 50%; float: left;">
    <form action="#" id="formthongke" class="formthongke" method="post">
        - Thời gian thống kê:
        Từ ngày: <input style="width: 70px; padding: 4px;" readonly="readonly"  value="<?=$StartDate?>" name="startDate" id="startDate" class="date-picker"/>
        Đến ngày: <input style="width: 70px; padding: 4px;" readonly="readonly" value="<?=$EndDate?>"  name="endDate" id="endDate" class="date-picker"/>
        <input type="button" id="thongke" class="thongke" value=" Thống kê " style="width: 65px; height: 28px;"/>
    </form>
</div>


<!--Từ ngày:
<input type="text" readonly="readonly" id="calendar"/>
<input type="submit" name="report" value="Report"/-->
<div class="clearAll"></div>

<div class="ajaxthongke">
    <div id="registerChart" style="width: 98%;height: 450px;padding: 10px"></div>
    <script type="text/javascript">

        var days = 20;
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
                    categories: [<?= $days ?>]
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
                        return this.series.name + ': <span style="color: blue">' + this.y + '</span> tài kho?n';
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
$day_num = 20;
$val_1 = array();
if (empty($data[0]))
    $data[0] = array();
for ($i = 0; $i < $day_num; $i++) {
    $flag = FALSE;
    foreach ($data[0] as $key => $item) {
        if ($item->date == date('Y-m-d', strtotime("-" . ($day_num - $i - 1) . " day"))) {
            $flag = TRUE;
            break;
        }
    }
    $val_1[$i] = 0;
    if ($flag)
        $val_1[$i] = $item->total;
    else
        $val_1[$i] = 0;
    echo $val_1[$i];
    if ($i < $day_num - 1)
        echo ',';
}
?>]

                    },
                    {
                        name: 'Giới thiệu',
                        data: [<?php
$val_2 = array();
if (empty($data[1]))
    $data[1] = array();
for ($i = 0; $i < $day_num; $i++) {
    $flag = FALSE;
    foreach ($data[1] as $key => $item) {
        if ($item->date == date('Y-m-d', strtotime("-" . ($day_num - $i - 1) . " day"))) {
            $flag = TRUE;
            break;
        }
    }
    $val_2[$i] = 0;
    if ($flag)
        $val_2[$i] = $item->total;
    else
        $val_2[$i] = 0;
    echo $val_2[$i];
    if ($i < $day_num - 1)
        echo ',';
}
?>]

                    },

                    {
                        name: 'Tổng',
                        data: [<?php
for ($i = 0; $i < $day_num; $i++) {
    echo $val_1[$i] + $val_2[$i];
    if ($i < $day_num - 1)
        echo ',';
}
?>]

                    }
                ]
            });
        });
    </script>



















<div class="clear"></div>
<div class="ajaxthongkeByIP">
    <div id="registerChartByIP" style="width: 98%;height: 450px;padding: 10px"></div>
    <script type="text/javascript">

        var daysByIP = 20;
        $(function () {
            $("#startDate, #startDateReg").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                yearRange: "1930:2050"
            });

            $("#endDate, #endDateReg").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                yearRange: "1930:2050"
            });

            var chart;
            chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'registerChartByIP',
                    type: 'column'
                },
                title: {
                    text: 'Thống kê tài khoản đăng ký theo IP'
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: [<?= $days ?>]
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
                        return this.series.name + ': <span style="color: blue">' + this.y + '</span> tài kho?n';
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


$day_numByIP = 20;
$val_1 = array();
if (empty($dataByIP[0]))
    $dataByIP[0] = array();
for ($i = 0; $i < $day_numByIP; $i++) {
    $flag = FALSE;
    foreach ($dataByIP[0] as $key => $item) {
        if ($item->date == date('Y-m-d', strtotime("-" . ($day_numByIP - $i - 1) . " day"))) {
            $flag = TRUE;
            break;
        }
    }
    $val_1[$i] = 0;
    if ($flag)
        $val_1[$i] = $item->total;
    else
        $val_1[$i] = 0;
    echo $val_1[$i];
    if ($i < $day_numByIP - 1)
        echo ',';
}
?>]

                    },
                    {
                        name: 'Giới thiệu',
                        data: [<?php
$val_2 = array();
if (empty($dataByIP[1]))
    $dataByIP[1] = array();
for ($i = 0; $i < $day_numByIP; $i++) {
    $flag = FALSE;
    foreach ($dataByIP[1] as $key => $item) {
        if ($item->date == date('Y-m-d', strtotime("-" . ($day_numByIP - $i - 1) . " day"))) {
            $flag = TRUE;
            break;
        }
    }
    $val_2[$i] = 0;
    if ($flag)
        $val_2[$i] = $item->total;
    else
        $val_2[$i] = 0;
    echo $val_2[$i];
    if ($i < $day_numByIP - 1)
        echo ',';
}
?>]

                    },

                    {
                        name: 'Tổng',
                        data: [<?php
for ($i = 0; $i < $day_numByIP; $i++) {
    echo $val_1[$i] + $val_2[$i];
    if ($i < $day_numByIP - 1)
        echo ',';
}
?>]

                    }
                ]
            });
        });
    </script>

    <div class="clearAll"></div>

    <script type="text/javascript">
        $(document).ready(function () {
            var tables = $('.refTable');
            for (k = 0; k < 2; k++) {
                var rows = tables.eq(k).find('tr');
                var size = rows.size();
                for (j = 1; j <= <?=$day_numByIP?>; j++) {
                    $total = 0;
                    for (i = 1; i <= size; i++) {
                        var row = rows.eq(i);
                        $total += Number(row.find('td').eq(j).text());
                    }
                    rows.eq(0).find('th').eq(j).append(' (<span style="color: blue">' + $total + '</span>)');
                }
            }
            updateReport();
        });
    </script>
</div>

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



<script type="text/javascript">
    Highcharts.theme = {
        colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
        chart: {
            backgroundColor: {
                linearGradient: [0, 0, 500, 500],
                stops: [
                    [0, 'rgb(255, 255, 255)'],
                    [1, 'rgb(240, 240, 255)']
                ]
            },
            borderWidth: 2,
            plotBackgroundColor: 'rgba(255, 255, 255, .9)',
            plotShadow: true,
            plotBorderWidth: 1
        },
        title: {
            style: {
                color: '#000',
                font: 'bold 16px "Trebuchet MS", Verdana, sans-serif'
            }
        },
        subtitle: {
            style: {
                color: '#666666',
                font: 'bold 12px "Trebuchet MS", Verdana, sans-serif'
            }
        },
        xAxis: {
            gridLineWidth: 1,
            lineColor: '#000',
            tickColor: '#000',
            labels: {
                style: {
                    color: '#000',
                    font: '11px Trebuchet MS, Verdana, sans-serif'
                }
            },
            title: {
                style: {
                    color: '#333',
                    fontWeight: 'bold',
                    fontSize: '12px',
                    fontFamily: 'Trebuchet MS, Verdana, sans-serif'

                }
            }
        },
        yAxis: {
            minorTickInterval: 'auto',
            lineColor: '#000',
            lineWidth: 1,
            tickWidth: 1,
            tickColor: '#000',
            labels: {
                style: {
                    color: '#000',
                    font: '11px Trebuchet MS, Verdana, sans-serif'
                }
            },
            title: {
                style: {
                    color: '#333',
                    fontWeight: 'bold',
                    fontSize: '12px',
                    fontFamily: 'Trebuchet MS, Verdana, sans-serif'
                }
            }
        },
        legend: {
            itemStyle: {
                font: '9pt Trebuchet MS, Verdana, sans-serif',
                color: 'black'

            },
            itemHoverStyle: {
                color: '#039'
            },
            itemHiddenStyle: {
                color: 'gray'
            }
        },
        labels: {
            style: {
                color: '#99b'
            }
        }
    };

    // Apply the theme
    var highchartsOptions = Highcharts.setOptions(Highcharts.theme);
</script>





    <div class="clearAll"></div>
    <div>
        <h3 class="ref-heading" style="margin:20px auto">Thông kê chi tiết giới thiệu</h3>
        <table class="refTable" border="1" cellspacing="0">
            <tr>
                <th class="ref-header">&nbsp;</th>
                <?php
                $a_days = explode(',', str_replace("'", '', $days));
                $b_days = $a_days;
                foreach ($a_days as $item) {
                    ?>
                    <th class="source-total"><?= $item ?></th>
                <?php } ?>
                <th>Total</th>
            </tr>
            <?php

            foreach ($refName as $k => $item) {
                ?>
                <tr class="refname">
                    <td class="ref-header"><?= $item ?></td>
                    <?php

                    for ($i = 0; $i < $day_num; $i++) {
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
                        echo "<td class=\"number\">$val</td>";
                    }
                    ?>
                    <td class="last"></td>
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
                for (j = 1; j <= <?=$day_num?>; j++) {
                    $total = 0;
                    for (i = 1; i <= size; i++) {
                        var row = rows.eq(i);
                        $total += Number(row.find('td').eq(j).text());
                    }
                    rows.eq(0).find('th').eq(j).append(' (<span style="color: blue">' + $total + '</span>)');
                }
            }
            updateReport();
        });
    </script>
</div>

<script type="text/javascript">
    Highcharts.theme = {
        colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
        chart: {
            backgroundColor: {
                linearGradient: [0, 0, 500, 500],
                stops: [
                    [0, 'rgb(255, 255, 255)'],
                    [1, 'rgb(240, 240, 255)']
                ]
            },
            borderWidth: 2,
            plotBackgroundColor: 'rgba(255, 255, 255, .9)',
            plotShadow: true,
            plotBorderWidth: 1
        },
        title: {
            style: {
                color: '#000',
                font: 'bold 16px "Trebuchet MS", Verdana, sans-serif'
            }
        },
        subtitle: {
            style: {
                color: '#666666',
                font: 'bold 12px "Trebuchet MS", Verdana, sans-serif'
            }
        },
        xAxis: {
            gridLineWidth: 1,
            lineColor: '#000',
            tickColor: '#000',
            labels: {
                style: {
                    color: '#000',
                    font: '11px Trebuchet MS, Verdana, sans-serif'
                }
            },
            title: {
                style: {
                    color: '#333',
                    fontWeight: 'bold',
                    fontSize: '12px',
                    fontFamily: 'Trebuchet MS, Verdana, sans-serif'

                }
            }
        },
        yAxis: {
            minorTickInterval: 'auto',
            lineColor: '#000',
            lineWidth: 1,
            tickWidth: 1,
            tickColor: '#000',
            labels: {
                style: {
                    color: '#000',
                    font: '11px Trebuchet MS, Verdana, sans-serif'
                }
            },
            title: {
                style: {
                    color: '#333',
                    fontWeight: 'bold',
                    fontSize: '12px',
                    fontFamily: 'Trebuchet MS, Verdana, sans-serif'
                }
            }
        },
        legend: {
            itemStyle: {
                font: '9pt Trebuchet MS, Verdana, sans-serif',
                color: 'black'

            },
            itemHoverStyle: {
                color: '#039'
            },
            itemHiddenStyle: {
                color: 'gray'
            }
        },
        labels: {
            style: {
                color: '#99b'
            }
        }
    };

    // Apply the theme
    var highchartsOptions = Highcharts.setOptions(Highcharts.theme);
</script>



<div class="clearAll"></div>
<div>
    <h3 class="ref-heading" style="margin:20px auto">Thông kê chi tiết giới thiệu (NEW)</h3>
    <table class="refTable2" border="1" cellspacing="0">
        <tr>
            <th class="ref-header">Type</th>
            <th class="ref-header">Source</th>
            <?php
            foreach ($b_days as $item) {
                ?>
                <th class="source-total"><?= $item ?></th>
            <?php } ?>
            <th>Total</th>
        </tr>
        <?php
        $printed = array();
        foreach ($reportPR as $k => $item) {
            //pr($k);
            //pr($item);
            //foreach($item as $key2 => $value)
              //  pr($value);
            //die;
            //die;
            ?>
            <tr class="refname" colspan="<?php echo count($b_days)?>">
                <td class="ref-header" rowspan="<?php echo count($item)+1?>"><?= $k ?></td>
            <?php
            foreach($item as $key2 => $value)
            {
               // pr($value,1);
                $countsum = 0;
                ?>
                <tr class="ga_value">
                    <td ><?php echo $key2;?></td>
                    <?php
                        foreach($b_days as $k => $day)
                        {
                           ?>
                            <td class="source_value<?=$k?>">
                                <?php
                                //echo $day

                                foreach($value as $valueday=>$valuesum)
                                    {
                                        if($valueday == $day)
                                        {
                                            echo $valuesum ? $valuesum : '0';
                                            $countsum += $valuesum;
                                        }
                                    }
                                ?>
                            </td>
                            <?php

                        }
                    ?>
                    <td><?=$countsum ? $countsum : '0'?></td>
                </tr>

            <?php
            }
            ?>
                <td class="last"></td>
            </tr>
        <?php
        }
        ?>
    </table>
</div>

<script type="text/javascript">
    var new_table_size = <?=$k?>;
    $(document).ready(function () {
        for(var i = 0; i <= new_table_size; i++){
            var target = $('.source_value'+i);
            var total = 0;
            target.each(function(index, val){
                total += Number($(val).html());
            });
            $('.refTable2 th.source-total').eq(i).append(' (<span style="color: blue">' + total + '</span>)');
                        
        }
    });
</script> 