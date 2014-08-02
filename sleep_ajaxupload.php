<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
	chdir('C:\AppServ\www\jhs');
	require_once("becomedrupal.php");
	// 取Drupal個人資料
	global $user;
	echo "h1";
	$zero=0;
	$bedTime="00:00:00";
	$asleepTime="00:00:00";
	$exceptedTime="00:00:00";
	$wakeupTime="00:00:00";
	$riseTime="00:00:00";
	$awakeTimes=0;
	$quality=0;
	$degree=0;
	$recordTime= date("Y-m-d H:i:s");;
	$wakeBy=0;
	$wakeStatus="null";
	$activities=0;
	$useDrug=0;
	$useCaffeine=0;
	$useAlcohol=0;
	$doexercise=0;
	$daytimesituation=0;
	$drugIndex="null";
	$drugTime="00:00:00";
	$drugName="null";
	$drugCapacity=0;
	$caffeineUsage="null";
	$cafeCapacity=0;
	$alcoholUsage="null";
	$alcoholCapacity=0;
	$startTime="00:00:00";
	$during="00:00:00";
	
	
	echo isset($_POST['uid']);

	if(isset($_POST['uid']) && isset($_POST['record_date'])){
	$uid=$_POST['uid'];
	//$rid = $_POST['exceptedTime'];
	$rid='hss'.strtotime("now").$uid;
	$date=$_POST['record_date'];
	$bedTime = $_POST['bedTime'];
	$asleepTime = $_POST['asleepTime'];
	$awakeTimes = $_POST['awakeTimes'];
	$wakeType =  $_POST['wakeType'];
	$exceptedTime =  $_POST['exceptedTime'];
	$riseTime =  $_POST['riseTime'];
	$wakeupTime=$riseTime;
	$activities =  $_POST['activities'];
	$daytime_naps_num =  $_POST['daytime_naps_num'];
	$naps_startTime =  $_POST['naps_startTime'];
	$naps_interval =  $_POST['naps_interval'];
	$exercise_num =  $_POST['exercise_num'];
	$exercise_name =  $_POST['exercise_name'];
	$exercise_startTime =  $_POST['exercise_startTime'];
	$exercise_endTime =  $_POST['exercise_endTime'];
	$caffeine_usage =  $_POST['caffeine_usage'];
	$caffeine_capacity =  $_POST['caffeine_capacity'];
	$alcohol_usage =  $_POST['alcohol_usage'];
	$alcohol_capacity =  $_POST['alcohol_capacity'];
	$morningFeel =  $_POST['morning_mood'];
	$dayFeel =  $_POST['day_feel'];
	$nightMood =  $_POST['night_mood'];
	$sleepQuality =  $_POST['sleep_quality'];


	if(isset($bedTime)){
		list($Day,$String,$time) =  preg_split("/ /", $bedTime);
		if($String == "下午" || $String == "晚上"){
			list($hour,$min) =  preg_split("/:/", $time);
			$hour = $hour+12;
			$time = $hour.":".$min;
		}
		elseif($String == "凌晨"){
			list($hour,$min) =  preg_split("/:/", $time);
			if($hour==12){
				$hour = $hour-12;
				$time = $hour.":".$min;
			}
		}
		if($Day=="昨天"){
			$bedTimeDate = date( "Y-m-d", strtotime($date)-86400);
			$bedTime=$bedTimeDate." ".$time.":00";
		}
		elseif($Day=="今天"){
			$bedTime=$date." ".$time.":00";
		}
	}
	
	echo 'bedTime'.$bedTime.'<br>';

	if(isset($asleepTime)){
		$total = $asleepTime*60;
		echo 'total : '.$total;
		$asleepTime = date( "Y-m-d H:i:s", strtotime($bedTime)+$total);
		
	}
	echo 'asleepTime : '.$asleepTime;

	if(isset($wakeType)){
		if($wakeType == "alarm_device"){
			$wakeType = "Alarm_device_Name";
		}
		elseif($wakeType == "nature"){
			$wakeType = "Spontaneously";
		}
		elseif($wakeType == "ZEO"){
			$wakeType = "zeo";
		}
		elseif($wakeType == "others"){
			$wakeType = "wakeByOther";
		}
	}
	echo "wakeType : ".$wakeType;
	
	if(isset($riseTime)){
		list($Day,$String,$time) =  preg_split("/ /", $riseTime);
		if($String == "下午" || $String == "晚上"){
			list($hour,$min) =  preg_split("/:/", $time);
			$hour = $hour+12;
			$time = $hour.":".$min;
		}
		elseif($String == "凌晨"){
			list($hour,$min) =  preg_split("/:/", $time);
			if($hour==12){
				$hour = $hour-12;
				$time = $hour.":".$min;
			}
		}
		if($Day=="昨天"){
			$riseTimeDate = date( "Y-m-d", strtotime($date)-86400);
			$riseTime=$riseTimeDate." ".$time.":00";
		}
		elseif($Day=="今天"){
			$riseTime=$date." ".$time.":00";
		}
	}
	echo "riseTime : ".$riseTime;
	$wakeupTime=$riseTime;
	if(isset($exceptedTime)){
		list($Day,$String,$time) =  preg_split("/ /", $_POST['riseTime']);
		
		if($Day=="昨天"){
			$exceptedTimeDate = date( "Y-m-d", strtotime($date)-86400);
			$exceptedTime=$exceptedTimeDate." ".$time.":00";
		}
		elseif($Day=="今天"){
			$exceptedTime=$date." ".$exceptedTime.":00";
			
		}
		
	}
	echo "exceptedTime : ".$exceptedTime;	

	echo "bedTime:".$bedTime;
        echo"date:".$date;
	
	$query = sprintf("INSERT INTO jhs_hss.sleepinfo_basic_sleep_pattern(uid,rid,recordTime,bedTime,asleepTime,awakeTimes,wakeBy,exceptedTime,wakeTime,riseTime,morningFeel,dayFeel,nightMood,sleepQuality,device) VALUES ( %d,'%s','%s','%s','%s',%d,'%s','%s','%s','%s',%d,%d,%d,%d,'%s')",$uid,$rid,$date,$bedTime,$asleepTime,$awakeTimes,$wakeType,$exceptedTime,$wakeupTime,$riseTime,$morningFeel,$dayFeel,$nightMood,$sleepQuality,SleepDiary);
	db_query($query);
	
}
else{
	//echo '尚未登入';

}


?>



</body>
</html>