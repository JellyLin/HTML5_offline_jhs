<?php
chdir('C:\AppServ\www\jhs');
//chdir('/var/www/jhs');
require_once("becomedrupal.php");

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

// *****************************************************************************
// 決定圖形各項數值之長度
if ( $result_A >= $mo_A_90 )
   $result_A = 100;
else if ( $result_A >= $mo_A_70 )
   $result_A = 80;
else if ( $result_A >= $mo_A_50 )
   $result_A = 60;
else if ( $result_A >= $mo_A_30 )
   $result_A = 40;
else if ( $result_A >= $mo_A_10 )
   $result_A = 20;
else
   $result_A = 0;

 
if ( $result_B >= $mo_B_90 )
   $result_B = 100;
else if ( $result_B >= $mo_B_70 )
   $result_B = 80;
else if ( $result_B >= $mo_B_50 )
   $result_B = 60;
else if ( $result_B >= $mo_B_30 )
   $result_B = 40;
else if ( $result_B >= $mo_B_10 )
   $result_B = 20;
else
   $result_B = 0;



if ( $result_DH >= $mo_DH_90 )
   $result_DH = 100;
else if ( $result_DH >= $mo_DH_70 )
   $result_DH = 80;
else if ( $result_DH >= $mo_DH_50 )
   $result_DH = 60;
else if ( $result_DH >= $mo_DH_30 )
   $result_DH = 40;
else if ( $result_DH >= $mo_DH_10 )
   $result_DH = 20;
else
   $result_DH = 0;

if ( $result_EOL >= $mo_EOL_90 )
   $result_EOL = 100;
else if ( $result_EOL >= $mo_EOL_70 )
   $result_EOL = 80;
else if ( $result_EOL >= $mo_EOL_50 )
   $result_EOL = 60;
else if ( $result_EOL >= $mo_EOL_30 )
   $result_EOL = 40;
else if ( $result_EOL >= $mo_EOL_10 )
   $result_EOL = 20;
else
   $result_EOL = 0;


if ( $result_FR >= $mo_FR_90 )
   $result_FR = 100;
else if ( $result_FR >= $mo_FR_70 )
   $result_FR = 80;
else if ( $result_FR >= $mo_FR_50 )
   $result_FR = 60;
else if ( $result_FR >= $mo_FR_30 )
   $result_FR = 40;
else if ( $result_FR >= $mo_FR_10 )
   $result_FR = 20;
else
   $result_FR = 0;


// ***********************************************
// 修改與刪除

  if( !empty($_GET['time']) )
  { 
        // 取pid
        $qq = sprintf("Select pid from jhs_shared.profile where type='_user_profile' AND uid=%d", $user->uid );
        $qq = db_query($qq)->fetchAssoc();
        // 取性別	
        $qq = sprintf("SELECT field_sex_value FROM jhs_shared.field_data_field_sex WHERE bundle='_user_profile' AND entity_id =%d ", $qq['pid'] );
        $qq = db_query($qq)->fetchAssoc();
        $sex = $qq['field_sex_value'];
        //消除錯誤訊息用
        if ( isset($_POST['datepicker']))
           $date = str_replace( '/' , '-' ,$_POST['datepicker']);
        
        // 判斷性別
        if ($sex == '男')
             $sex = 1;
        else if ($sex == '女')
             $sex = 2;
        else
             echo "您尚未填選性別";
   //修改按下去
   if( isset($_POST['modify']) )
   {
      //修改執行
      if ( $_POST['modify'] == 'ensure' )
      {
        // 先刪除
	$query = sprintf("DELETE FROM jhs_fitness.physical_fitness_profile WHERE time = '%s' AND uid = %d AND sex = %s", $date, $user->uid, $sex);
        db_query($query);

        // 再新增
        //A
        if ( !empty( $_POST['A'] ) )
        {
	$query = sprintf("INSERT INTO jhs_fitness.physical_fitness_profile(uid, sex, time, kind, value, input_time) VALUES ( %d, %s, '%s', 'A', %d, now() )", $user->uid, $sex, $date, $_POST['A']);
	db_query($query);
        }
	//B
        if ( !empty( $_POST['B'] ) )
        {
	$query = sprintf("INSERT INTO jhs_fitness.physical_fitness_profile(uid, sex, time, kind, value, input_time) VALUES ( %d, %s, '%s', 'B', %d, now() )", $user->uid, $sex, $date, $_POST['B']);
	db_query($query);
        }

	//DH
        if ( !empty( $_POST['DH'] ) )
        {
	$query = sprintf("INSERT INTO jhs_fitness.physical_fitness_profile(uid, sex, time, kind, value, input_time) VALUES ( %d, %s, '%s', 'DH', %d, now() )", $user->uid, $sex, $date, $_POST['DH']);
	db_query($query);
        }
	//EOL
        if ( !empty( $_POST['EOL'] ) )
        {
	$query = sprintf("INSERT INTO jhs_fitness.physical_fitness_profile(uid, sex, time, kind, value, input_time) VALUES ( %d, %s, '%s', 'EOL', %d, now() )", $user->uid, $sex, $date, $_POST['EOL']);
	db_query($query);
        }
	//FR
        if ( !empty( $_POST['FR'] ) )
        {
	$query = sprintf("INSERT INTO jhs_fitness.physical_fitness_profile(uid, sex, time, kind, value, input_time) VALUES ( %d, %s, '%s', 'FR', %d, now() )", $user->uid, $sex, $date, $_POST['FR']);
	db_query($query);
        }

      }
      //輸入修改數值
      else
      {

echo '<form id="form3" name="form3" method="post" action="">
<p>
    <input type="hidden" name="modify" id="modify" value="ensure"> 
    <input type="hidden" name="time" id="time" value="';
echo $_GET['time'];

echo'"> <br/>
    <label for="B">心肺耐力(２分鐘抬腿)</label>
    <input type="text" name="B" id="B" />
    <label for="FR"><br />上肢耐力 (右手握力)</label>
    <input type="text" name="FR" id="FR" />
     <label for="A"><br />下肢肌耐力(三十秒坐站測驗)</label>
    <input type="text" name="A" id="A" />
    <label for="EOL"><br />平衡感(睜眼左腳單足站立)</label>
    <input type="text" name="EOL" id="EOL" />
    <label for="DH"><br />柔軟度(半坐姿體前彎)</label>
    <input type="text" name="DH" id="DH" />
    <br/><br/>
    <label>量測日期</label>';
    echo $_GET['time'];
    echo '<input type="hidden" name="datepicker" id="datepicker" value="';
    echo $_GET['time'];
    echo'"/></p>
     <input type="submit" name="button" id="button" value="修改">
     </form>';
    
        } // end else
}  // end if

   //刪除按下去
   else if( isset($_POST['delete']) )
   {
      //刪除確認按下去
      if( $_POST['delete'] == 'justdelete')
      {
      echo'確定要刪除這筆紀錄<br/><form id="form4" name="form4" method="post" action="">
       <input type="hidden" name="delete" id="delete" value="ensure"/>
       <input type="hidden" name="datepicker" id="datepicker" value="';
     echo $_GET['time'];
     echo '">
       <input type="submit" name="button" id="button" value="確認"/>
     </form>';
      }
      //刪除
      else if ( $_POST['delete'] == 'ensure' )
      {
	$query = sprintf("DELETE FROM jhs_fitness.physical_fitness_profile WHERE time = '%s' AND uid = %d AND sex = %s", $date, $user->uid, $sex);
        db_query($query);
         echo '已經刪除紀錄了';
      }
   }
   //刪除確認按鈕
   else
    {
    echo '<form id="form1" name="form1" method="post" action="">
       <input type="hidden" name="modify" id="modify" value="justmodify"/>
       <input type="hidden" name="datepicker" id="datepicker" value="';
    echo $_GET['time'];
    echo '">
       <input type="submit" name="button" id="button" value="修改"/>
       </form>';

     echo'<form id="form2" name="form2" method="post" action="">
        <input type="hidden" name="delete" id="delete" value="justdelete"/>
       <input type="hidden" name="datepicker" id="datepicker" value="';
     echo $_GET['time'];
     echo '">
        <input type="submit" name="button" id="button" value="刪除"/>
        </form>';
     }
}

echo "<br/>";


echo "<br/>";



// ****************************************************** 
// 抓當前頁面網址
//to display the retrieved current page Url
function curPageURL() {
   $pageURL = 'http';

   // add HTTPS
   //if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

   $pageURL .= "://";
   if ($_SERVER["SERVER_PORT"] != "80") {
      $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
   } 
   else {
      $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
   }
   return $pageURL;
}


// ****************************************************** 
// 讀取過去資料
$past_record = db_query("SELECT * FROM jhs_fitness.physical_fitness_profile WHERE kind='A' AND uid = $user->uid ORDER BY time DESC") -> fetchAll();
// 畫表格
echo "<html>";
echo "<p>*點選左方日期以修改資料</p>";
echo "<table  border='1'>";
echo "<tr><p> <font id='title' size='8' color='blue'>
<td>資料量測時間</td>
<td>心肺耐力
<br/>(２分鐘抬腿)</td>
<td>上肢耐力 
<br/>(右手握力)</td>
<td>下肢肌耐力
<br/>(三十秒坐站測驗)</td>
<td>平衡感
<br/>(睜眼左腳單足站立) </td>
<td>柔軟度
<br/>(半坐姿體前彎)</td>
</p>
</font>
</tr>";


foreach ($past_record as $last_record) {
  // Perform operations on $last_record->title, etc. here.
  // in this example the available data would be mapped to object properties:
  // $last_record->nid, $last_record->title, $last_record->created

  $url_thispage = curPageURL();
  $count = strpos($url_thispage, '?');
  if ( $count != 0 )
  {
     $url_thispage = str_split(curPageURL() , $count);
     $url_thispage = $url_thispage[0];
  }
  $url_thispage = sprintf("%s?time=%s" , $url_thispage, $last_record->time);

  echo "<tr>";
/*
  if ( !empty($record_time) && ($record_time == $last_record->time) )
  {
     echo $last_record;
     echo "<td><a href='" . $url_thispage . "'> 編輯 </a></td>";
  }
  else 
  {
     echo "<a href='" . $url_thispage . "' > " . $last_record->time  . " </a>";
  }
*/
  echo "<td><a href='" . $url_thispage . "' > " . $last_record->time  . " </a></td>";
  $records = db_query("SELECT * FROM jhs_fitness.physical_fitness_profile WHERE time='$last_record->time' AND uid = $user->uid ") -> fetchAll();

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
		echo "<td>" .  $my_record->value . "</td>" ;
		foreach ($records as $my_record){
		if($my_record->kind == 'FR')
		{
		echo "<td>" .  $my_record->value . "</td>" ;
		foreach ($records as $my_record){
			if($my_record->kind == 'A')
			{	
			echo "<td>" .  $my_record->value . "</td>" ;
			foreach ($records as $my_record){
				if($my_record->kind == 'EOL')
				{
				echo "<td>" .  $my_record->value . "</td>" ;
				foreach ($records as $my_record){
					if($my_record->kind == 'DH')
					{
					echo "<td>" .  $my_record->value . "</td>" ;
					}}
				}}
			}}
		}}
	}
  }
  
  echo "</tr>";
  
/* 這樣的寫法可以抓取每筆資料的Value 少了Kind的判斷 不便於排序 
   foreach ($records as $my_record)
  {
  echo "<td>" .  $my_record->value . "</td>" ;
  }
  echo "</tr>";
*/
  $count = 0;
}
   echo "</table>";
   echo "</html>";

// *****************************************************************************
// 實作 google chart
// 範例 http://chart.apis.google.com/chart?[參數=數值]&[參數=數值] 

$chxp = "chxp=0,0,20,40,60,80,100";
$chxl = "chxl=1:|心肺耐力(２分鐘抬腿)|上肢耐力 (右手握力)|下肢肌耐力(三十秒坐站測驗)|平衡感(睜眼左腳單足站立)|柔軟度(半坐姿體前彎)";
$chxt = "chxt=y,x";
$chs = "chs=600x500";
$chtt = sprintf("chtt=%s", $user->name);
if ( !empty($_GET['time']) ) {
    $chtt = sprintf("%s %s", $chtt, $record_time);
}
$cht = "cht=r";
$chco = "chco=FF0000";
$chd = sprintf("chd=t:%d,%d,%d,%d,%d,%d", $result_B, $result_FR,$result_A, $result_EOL,$result_DH,$result_B);
$chls = "chls=2,4,0";
$chm = "chm=B,FF000080,0,0,0";

$url = sprintf("http://chart.apis.google.com/chart?%s&%s&%s&%s&%s&%s&%s&%s&%s&%s", $chxl, $chtt, $chxt, $chs,  $cht,  $chco, $chd, $chls, $chxp, $chm );
echo '<img src=" ' . $url . ' " />';


// * 另外一種寫法(備份用)

//header('Content-type: application/x-www-form-urlencoded');

/*
//$url = sprintf("http://chart.apis.google.com/chart?chxl=0:|下肢肌耐力|心肺耐力|柔軟度|平衡感|上肢耐力&chtt=男性A&chxt=x,y&chs=500x500&cht=r&chco=FF0000&chd=t:%d,92.0,62.5,8.0,7.1,29.1,13.0&chls=2,4,0&chm=B,FF000080,0,0,0", $mo_A_90);

//echo '<img src="' . $url . '" />';

//A B DH EOL FR

$chxl = "chxl=0:|心肺耐力(２分鐘抬腿)|上肢耐力 (右手握力)|下肢肌耐力(三十秒坐站測驗)|平衡感(睜眼左腳單足站立)|柔軟度(半坐姿體前彎)";
$chxt = "chxt=x,y";
$cht = sprintf("chd=t:%d,%d,%d,%d,%d,%d", $result_B, $result_FR,$result_A, $result_EOL,$result_DH,$result_B);
$chs = "chs=600x500";
$chtt= sprintf("chtt=%s", $user->name);

//$url = sprintf("http://chart.apis.google.com/chart?chxl=0:|下肢肌耐力|心肺耐力|柔軟度|平衡感|上肢耐肌力&chtt=%s&chxt=x,y&chs=500x500&cht=r&chco=FF0000&chd=t:%d,%d,%d,%d,%d,%d&chls=2,4,0&chm=B,FF000080,0,0,0", $user->name, $result_A, $result_B, $result_DH, $result_EOL,$result_FR,$result_A);
echo '<img src="' . $url . ' " />';
*/

?>
