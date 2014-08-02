<?php
	// 抓記錄
	$str_json = file_get_contents('php://input');
	// 轉JSON
	$records = json_decode($str_json, true);

	chdir('C:\AppServ\www\jhs');
	//chdir('/var/www/jhs');
	require_once("becomedrupal.php");
	global $user;
	// 取pid
if( isset($user->name) )
{
	$qq = sprintf("Select pid from jhs_shared.profile where type='_user_profile' AND uid=%d", $user->uid );
	$qq = db_query($qq)->fetchAssoc();
	// 取性別	
	$qq = sprintf("SELECT field_sex_value FROM jhs_shared.field_data_field_sex WHERE bundle='_user_profile' AND entity_id =%d ", $qq['pid'] );
	$qq = db_query($qq)->fetchAssoc();
	$sex = $qq['field_sex_value'];

	if ($sex == '男'){
		$sex = 1;}
	else if ($sex == '女'){
		$sex = 2;}
	else
		{echo "您尚未填選性別";}

	$i = 0;
	foreach ($records["record"] as $record) {
		$date = str_replace( '/' , '-' ,$records["record"][$i]["time"]);
		foreach($record as $type => $value) {
			if ($type != "time")
			{
				//$query = sprintf("INSERT INTO jhs_fitness.physical_fitness_profile(uid, sex, time, kind, value, input_time) VALUES ( " . $user->uid . " , " . $sex . " , '" . $date . "' , '" . $type . "' ,"   . $value ." , now() ) ");
				$query = sprintf("REPLACE INTO jhs_fitness.physical_fitness_profile(uid, sex, time, kind, value, input_time) VALUES ( " . $user->uid . " , " . $sex . " , '" . $date . "' , '" . $type . "' ,"   . $value ." , now() ) ");
				$result = db_query($query);
			}
		}
		$i++;
	}
	echo "done";
} // end check login status

?>
