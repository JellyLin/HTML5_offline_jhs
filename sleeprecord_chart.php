<?php

	chdir('C:\AppServ\www\jhs');
	require_once("becomedrupal.php");

	// 取Drupal個人資料
	global $user;
?>

<script src="http://hss.weco.net/logic_hss/js/jquery-1.6.2.min.js"></script>
<script src="http://hss.weco.net/logic_hss/modules/Highcharts/js/highcharts.js"></script>
<script src="http://hss.weco.net/logic_hss/modules/Highcharts/js/modules/exporting.js"></script>
<script type="text/javascript">
var chartDIV;
var score=[];
jQuery(document).ready(function($){
	if($('.rowCount').val() > 0 ){
		var rowCount = $('.rowCount').val();
	}
	
	for (i=0;i<rowCount;i++)
	{
	
		if($('#scoreDIV'+i).val() >0){
			score.push([$('#dateDIV'+i).val() - 0 ,$('#scoreDIV'+i).val() - 0]);
		}
		else{
			score.push([$('#dateDIV'+i).val() - 0,0 - 0]);
		}
		
	}

	chartDIV = new Highcharts.Chart({
					chart: {
						renderTo: 'SleepScoreDIV',
						zoomType: 'x',
						spacingRight: 20,
						backgroundColor : "#edf4e2"
					},
				    title: {
						text: '您的睡眠品質分數總覽'
					},
					xAxis: {
						type: 'datetime',
						maxZoom: 31 * 24 * 3600000, // fourteen days
						title: {
							text: null
						}
					},
					yAxis: {
						title: {
							text: '睡眠品質分數'
						},
						min: 0,
						startOnTick: false,
						showFirstLabel: false
					},
					tooltip: {
						shared: true					
					},
					legend: {
						enabled: false
					},
					plotOptions: {
						area: {
							fillColor: {
								linearGradient: [0, 0, 0, 300],
								stops: [
									[0, Highcharts.getOptions().colors[0]],
									[1, 'rgba(2,0,0,0)']
								]
							},
							lineWidth: 1,
							marker: {
								enabled: false,
								states: {
									hover: {
										enabled: true,
										radius: 5
									}
								}
							},
							shadow: false,
							states: {
								hover: {
									lineWidth: 1						
								}
							}
						}
					},
				
					series: [{
						type: 'area',
						name: '分數',
						pointInterval: 24 * 3600 * 1000,
						data: score
					}]
				});
				
	
});
</script>

<div id="SleepScoreDIV" class="SleepScoreDIV">
</div>
<?php
	// ****************************************************** 
	// 抓當前頁面網址
	//to display the retrieved current page Url
	/*function curPageURL() 
	{
		$pageURL = 'http';

		// add HTTPS
		//if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") 
		{
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} 
		else 
		{
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
**/

	// ****************************************************** 
	// 讀取過去資料
	$past_sq = db_query("SELECT * FROM jhs_shared.qualityscore WHERE uid = $user->uid") -> fetchAll();
	//$past_record = db_query("SELECT * FROM sleepinfo_basic_sleep_pattern WHERE device = 'SleepDiary' AND uid = $user->uid") -> fetchAll();
	//$devices = 'ZeoDecoder';
	/*$sleepinfo = db_query("
			SELECT  *
			FROM   sleepinfo_basic_sleep_pattern 
			WHERE uid = :uid AND device = :device",array(':uid' => $user->uid,':device' =>$devices))->fetchAll();**/
	 $records = db_query("SELECT * FROM jhs_hss.sleepinfo_basic_sleep_pattern WHERE device= 'SleepDiary' AND uid = $user->uid  ORDER BY recordTime DESC") -> fetchAll();

	
	
	
	// 畫表格
	//echo "<html>";
	echo "<table  border='1'>";
	echo "<tr><p> <font id='title' size='8' color='blue'>
		<td>資料量測時間</td>
		<td>上床睡覺時間</td>
		<td>起床時間</td>
		<td>總共睡了多久</td>
		<td>SQ分數</td>
		</p>
	</font>
	</tr>";
	
	$i = 0;
	foreach ($records as $my_record)
	{
		echo "<tr>";
			$bedTime = $my_record->bedtime;
			$riseTime = $my_record->risetime;
			$recordTime = $my_record->recordtime;
			echo "<td>".$recordTime."</td>";
			echo "<td>".$bedTime."</td>";
			echo "<td>".$riseTime."</td>";
			$totalTime = strtotime($riseTime)-strtotime($bedTime);
			$hour = $totalTime / 3600;
			$mins = $totalTime % 3600;
			echo "<td>".round($hour,2)."小時"."</td>";
			$srid = $my_record->rid;
			
			foreach($past_sq as $my_grade)
			{
					$grid = $my_grade->rid;
					$recorddate = $my_grade->recorddate;
					if($srid == $grid)
					{
						echo "<td>".$my_grade->sscore."</td>";						
						$Score = $my_grade->sscore;
						$date = $my_grade->recorddate;
						list($Y,$M,$D) =  preg_split("/-/", $date);
						
						$date = '13-09-2010 00:00:00';
						$date = $D . '-' . $M . '-' . $Y . ' 00:00:00';
						date_default_timezone_set('UTC');
						$time = (strtotime($date) * 1000) - (strtotime('01-01-1970 00:00:00') * 1000);
						echo '<input type="hidden" id="dateDIV'.$i.'" value="' . $time . '">';
						echo '<input type="hidden" id="scoreDIV'.$i.'" value="'. $Score .'">';
						$i++;
					}
			}
		echo "</tr>";
			
	}
 echo '<input type="hidden" class="rowCount" value="'.$i.'">';
 echo "</table>";
   //echo "</html>";
	//echo "</html>";
//	print_r($sleepinfo);
	
	
?>