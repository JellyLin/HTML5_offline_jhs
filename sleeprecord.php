<?
chdir('C:\AppServ\www\jhs');
//chdir('/var/www/jhs');
require_once("becomedrupal.php");

// 取Drupal個人資料
global $user;
// ****************************************************** 
// 讀取過去資料
$query = db_query("SELECT * FROM jhs_shared.qualityscore WHERE uid = $user->uid");
$past_record = $query->fetchAll();
// 組合JSON
// 放name
echo '{ "name" : "'. $user->name . '", ';
echo '"sleeprecord" : [ ';

$query = db_query("SELECT * FROM jhs_hss.sleepinfo_basic_sleep_pattern WHERE device= 'SleepDiary' AND uid = $user->uid  ORDER BY recordTime DESC");
$records = $query->fetchAll();
$rowcount = $query->rowCount();

  foreach ($records as $my_record)
  {
	$recordTime = $my_record->recordtime;
	$bedTime = $my_record->bedtime;
	$riseTime = $my_record->risetime;
	echo '{ "recordtime" : "' . $recordTime  . '",';
	echo '"bedtime" : "' . $bedTime  . '",';
	echo '"risetime" : "' . $riseTime  . '",';
	$totalTime = strtotime($riseTime)-strtotime($bedTime);
	$hour = $totalTime / 3600;
	$mins = $totalTime % 3600;
	echo '"sleeplength" : "' . round($hour,2)  . '"';
	//echo "<td>".round($hour,2)."小時"."</td>";
	$srid = $my_record->rid;
	
	foreach($past_record as $my_grade)
	{
		$grid = $my_grade->rid;
		$recorddate = $my_grade->recorddate;
		if($srid == $grid)
		{
			$Score = $my_grade->sscore;
			$date = $my_grade->recorddate;
			list($Y,$M,$D) =  preg_split("/-/", $date);
			
			$date = '13-09-2010 00:00:00';
			$date = $D . '-' . $M . '-' . $Y . ' 00:00:00';
			date_default_timezone_set('UTC');
			$time = (strtotime($date) * 1000) - (strtotime('01-01-1970 00:00:00') * 1000);
			echo ',';
			// 為傳遞正確值 是因為time超過數字的最大值 會自動轉成科學符號,
			echo '"realrecordtime" : "' . $time/10 . '"';
			echo ',';
			echo '"sscore" : "' . $Score . '"';
		}
	}
	if ($rowcount > 1)
	{
		$rowcount--;
		echo " },";
	}
	else 
	{
		echo " }";
	}
}
   echo "] }";
?>
