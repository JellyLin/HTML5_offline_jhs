<?php
chdir('C:\AppServ\www\jhs');
//chdir('/var/www/jhs');
require_once("becomedrupal.php");

// 取Drupal個人資料
global $user;

// *****************************************************************************
//  找個人最新體適能紀錄資料範例
/*
  $result_A = db_query("
SELECT value
FROM jhs_fitness.physical_fitness_profile
WHERE kind='A' AND time = (
   SELECT timestamp(MAX(time))
   FROM jhs_fitness.physical_fitness_profile
   WHERE kind = 'A'
) ") -> fetchfield();
echo $result;
*/

//資料格式與呈現  
/*
心肺耐力  (２分鐘抬腿）	    →B
上肢耐力(右握力) 	    →FR
下肢肌耐力(三十秒坐站測驗)  →A
平衡感	  (睜眼左腳單足站立)→EOL
柔軟度	  (半坐姿體前彎)    →DH



*/

// *****************************************************************************
// 找個人最新體適能紀錄資料
	
if ( empty($_GET['time']) )
{
   $result_A =   db_query("SELECT value FROM jhs_fitness.physical_fitness_profile WHERE kind='A'  AND uid = $user->uid  AND    time = ( SELECT timestamp(MAX(time)) FROM jhs_fitness.physical_fitness_profile WHERE kind = 'A'   AND uid = $user->uid ) ") -> fetchfield();
   $result_B =   db_query("SELECT value FROM jhs_fitness.physical_fitness_profile WHERE kind='B'   AND uid = $user->uid AND  time = ( SELECT timestamp(MAX(time)) FROM jhs_fitness.physical_fitness_profile WHERE kind = 'B'   AND uid = $user->uid ) ") -> fetchfield();
   $result_DH =  db_query("SELECT value FROM jhs_fitness.physical_fitness_profile WHERE kind='DH'  AND uid = $user->uid AND  time = ( SELECT timestamp(MAX(time)) FROM jhs_fitness.physical_fitness_profile WHERE kind = 'DH'  AND uid = $user->uid ) ") -> fetchfield();
   $result_EOL = db_query("SELECT value FROM jhs_fitness.physical_fitness_profile WHERE kind='EOL' AND uid = $user->uid AND  time = ( SELECT timestamp(MAX(time)) FROM jhs_fitness.physical_fitness_profile WHERE kind = 'EOL' AND uid = $user->uid ) ") -> fetchfield();
   $result_FR =  db_query("SELECT value FROM jhs_fitness.physical_fitness_profile WHERE kind='FR'  AND uid = $user->uid AND  time = ( SELECT timestamp(MAX(time)) FROM jhs_fitness.physical_fitness_profile WHERE kind = 'FR'  AND uid = $user->uid ) ") -> fetchfield();

}
else
{
   $record_time = $_GET['time'] . '';
   $result_A = db_query("SELECT value FROM jhs_fitness.physical_fitness_profile WHERE kind='A' AND time = '$record_time' AND uid = $user->uid ") -> fetchfield();
   $result_B = db_query("SELECT value FROM jhs_fitness.physical_fitness_profile WHERE kind='B' AND time = '$record_time' AND uid = $user->uid ") -> fetchfield();
   $result_DH = db_query("SELECT value FROM jhs_fitness.physical_fitness_profile WHERE kind='DH' AND time = '$record_time' AND uid = $user->uid ") -> fetchfield();
   $result_EOL = db_query("SELECT value FROM jhs_fitness.physical_fitness_profile WHERE kind='EOL'  AND time = '$record_time' AND uid = $user->uid ") -> fetchfield();
   $result_FR = db_query("SELECT value FROM jhs_fitness.physical_fitness_profile WHERE kind='FR' AND time = '$record_time' AND uid = $user->uid ") -> fetchfield();

}

// *****************************************************************************
//  圖形各值長度示意
//           90         70           50         30          10
// 100%       80%       60%       40%        20%     0%


// *****************************************************************************
//  抓取常模資料

//依照性別取常模資料  1是男 2是女 
$result_SEX = db_query("SELECT sex FROM jhs_fitness.physical_fitness_profile WHERE uid = $user->uid ") -> fetchfield();
        
// 取pid
$qq = sprintf("Select pid from jhs_shared.profile where type='_user_profile' AND uid=%d", $user->uid );
$qq = db_query($qq)->fetchAssoc();
// 取生日	
$userBithday = sprintf("SELECT field_birthday_value FROM jhs_shared.field_data_field_birthday WHERE bundle='_user_profile' AND entity_id =%d ", $qq['pid'] );
$userBithday = db_query($userBithday)->fetchAssoc();
$result_AGE = $userBithday['field_birthday_value'];
        
$result_AGE = str_split($result_AGE, 4 );
$result_AGE = $result_AGE[0];




$thisYear = date("Y");
$NormAge = $thisYear - $result_AGE;
//年齡相減取資料庫常模
switch(($NormAge/5)-12)
	{
	case 0:$NormAge=6064;	//60-64
	case 1:$NormAge=6569; 	//65-69
	case 2:$NormAge=7079; 	//70-74
	case 3:$NormAge=7579; 	//75-79
	case 4:$NormAge=8084; 	//80-84
	case 5:$NormAge=85; 	//85up
	default :$NormAge=60; 	//60up
	} 

$Norm; 

if($result_SEX==1)
{
	$Norm="jhs_fitness.physical_fitness_norm_man";
}
else 
{
	$Norm="jhs_fitness.physical_fitness_norm_woman";
}


$mo_A = db_query("SELECT p10,p30,p50,p70,p90 FROM $Norm WHERE kind ='A' AND age =$NormAge ");
$record = $mo_A ->fetchAssoc();

$mo_A_90 = $record["p90"];
$mo_A_70 = $record["p70"];
$mo_A_50 = $record["p50"];
$mo_A_30 = $record["p30"];
$mo_A_10 = $record["p10"];

$mo_B = db_query("SELECT p10,p30,p50,p70,p90 FROM $Norm WHERE kind ='B' AND age =$NormAge ");
$record = $mo_B ->fetchAssoc();

$mo_B_90 = $record["p90"];
$mo_B_70 = $record["p70"];
$mo_B_50 = $record["p50"];
$mo_B_30 = $record["p30"];
$mo_B_10 = $record["p10"];


$mo_DH = db_query("SELECT p10,p30,p50,p70,p90 FROM $Norm WHERE kind ='DH' AND age =$NormAge ");
$record = $mo_DH ->fetchAssoc();

$mo_DH_90 = $record["p90"];
$mo_DH_70 = $record["p70"];
$mo_DH_50 = $record["p50"];
$mo_DH_30 = $record["p30"];
$mo_DH_10 = $record["p10"];

$mo_EOL = db_query("SELECT p10,p30,p50,p70,p90 FROM $Norm WHERE kind ='EOL' AND age =$NormAge ");
$record = $mo_EOL->fetchAssoc();

$mo_EOL_90 = $record["p90"];
$mo_EOL_70 = $record["p70"];
$mo_EOL_50 = $record["p50"];
$mo_EOL_30 = $record["p30"];
$mo_EOL_10 = $record["p10"];

$mo_FR= db_query("SELECT p10,p30,p50,p70,p90 FROM $Norm WHERE kind ='FR' AND age =$NormAge ");
$record = $mo_FR->fetchAssoc();

$mo_FR_90 = $record["p90"];
$mo_FR_70 = $record["p70"];
$mo_FR_50 = $record["p50"];
$mo_FR_30 = $record["p30"];
$mo_FR_10 = $record["p10"];

// ****************************************************** 
// 讀取過去資料
$query = db_query("SELECT * FROM jhs_fitness.physical_fitness_profile WHERE kind='A' AND uid = $user->uid ORDER BY time DESC");
$past_record = $query->fetchAll();
$rowcount = $query->rowCount();
// 組合JSON
// 放name
echo '{ "name" : "'. $user->name . '", ';
// 放常模
echo '"norm" : [ ';
echo '{';
echo '"sport" : "A",';
echo '"r90" : "' . $mo_A_90  . '",';
echo '"r70" : "' . $mo_A_70  . '",';
echo '"r50" : "' . $mo_A_50  . '",';
echo '"r30" : "' . $mo_A_30  . '",';
echo '"r10" : "' . $mo_A_10  . '"';
echo '},';
echo '{';
echo '"sport" : "B",';
echo '"r90" : "' . $mo_B_90  . '",';
echo '"r70" : "' . $mo_B_70  . '",';
echo '"r50" : "' . $mo_B_50  . '",';
echo '"r30" : "' . $mo_B_30  . '",';
echo '"r10" : "' . $mo_B_10  . '"';
echo '},';
echo '{';
echo '"sport" : "DH",';
echo '"r90" : "' . $mo_DH_90  . '",';
echo '"r70" : "' . $mo_DH_70  . '",';
echo '"r50" : "' . $mo_DH_50  . '",';
echo '"r30" : "' . $mo_DH_30  . '",';
echo '"r10" : "' . $mo_DH_10  . '"';
echo '},';
echo '{';
echo '"sport" : "EOL",';
echo '"r90" : "' . $mo_EOL_90  . '",';
echo '"r70" : "' . $mo_EOL_70  . '",';
echo '"r50" : "' . $mo_EOL_50  . '",';
echo '"r30" : "' . $mo_EOL_30  . '",';
echo '"r10" : "' . $mo_EOL_10  . '"';
echo '},';
echo '{';
echo '"sport" : "FR",';
echo '"r90" : "' . $mo_FR_90  . '",';
echo '"r70" : "' . $mo_FR_70  . '",';
echo '"r50" : "' . $mo_FR_50  . '",';
echo '"r30" : "' . $mo_FR_30  . '",';
echo '"r10" : "' . $mo_FR_10  . '"';
echo '} ],';

// 放record
echo '"record" : [ ';

foreach ($past_record as $last_record) {

  echo '{ "time" : "' . $last_record->time  . '",';
  $query = db_query("SELECT * FROM jhs_fitness.physical_fitness_profile WHERE time='$last_record->time' AND uid = $user->uid ");
  $records = $query->fetchAll();
/*
心肺耐力  (２分鐘抬腿）	    →B
上肢耐力(右握力) 	    →FR
下肢肌耐力(三十秒坐站測驗)  →A
平衡感	  (睜眼左腳單足站立)→EOL
柔軟度	  (半坐姿體前彎)    →DH
*/
  foreach ($records as $my_record)
  {
	if($my_record->kind == 'B')
	{ 
		echo '"B" : "' . $my_record->value  . '",';
		foreach ($records as $my_record){
		if($my_record->kind == 'FR')
		{
			echo '"FR" : "' . $my_record->value  . '",';
		foreach ($records as $my_record){
			if($my_record->kind == 'A')
			{	
				echo '"A" : "' . $my_record->value  . '",';
			foreach ($records as $my_record){
				if($my_record->kind == 'EOL')
				{
					echo '"EOL" : "' . $my_record->value  . '",';
				foreach ($records as $my_record){
					if($my_record->kind == 'DH')
					{
						echo '"DH" : "' . $my_record->value  . '"';				
					}}
				}}
			}}
		}}
	}
  }
  if ($rowcount-- > 1)
  {
     echo " },";
  }
  else 
  {
     echo " }";
  }
  
/* 這樣的寫法可以抓取每筆資料的Value 少了Kind的判斷 不便於排序 
   foreach ($records as $my_record)
  {
  echo "<td>" .  $my_record->value . "</td>" ;
  }
  echo "</tr>";
*/
  $count = 0;
}

   echo "] }";


?>
