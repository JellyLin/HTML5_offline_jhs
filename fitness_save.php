<?php
chdir('C:\AppServ\www\jhs');
//chdir('/var/www/jhs');
require_once("becomedrupal.php");

// 取Drupal個人資料
global $user;

if (isset($_POST['textfield'])) {

    global $user;

        // 取pid
        $qq = sprintf("Select pid from jhs_shared.profile where type='_user_profile' AND uid=%d", $user->uid );
        $qq = db_query($qq)->fetchAssoc();
        // 取性別	
        $qq = sprintf("SELECT field_sex_value FROM jhs_shared.field_data_field_sex WHERE bundle='_user_profile' AND entity_id =%d ", $qq['pid'] );
        $qq = db_query($qq)->fetchAssoc();
        $sex = $qq['field_sex_value'];
        $date = str_replace( '/' , '-' ,$_POST['datepicker']);
		
// 沒填過性別會有bug
        // 判斷性別
        if ($sex == '男'){
             $sex = 1;}
        else if ($sex == '女'){
             $sex = 2;}
        else
             {echo "您尚未填選性別";}
    //A
        if ( !empty( $_POST['A'] ) )
        {
		$query = sprintf("INSERT INTO physical_fitness_profile(uid, sex, time, kind, value, input_time) VALUES ( " . $user->uid . " , " . $sex . " , ' " . $date . " ' ,  'A' ,"   . $_POST['A'] ." , now() ) ");
		$result = db_query($query);
        }
        if ( !empty( $_POST['B'] ) )
        {
	//B
		$query = sprintf("INSERT INTO physical_fitness_profile(uid, sex, time, kind, value ,input_time) VALUES ( " . $user->uid . " , " . $sex . " , ' " . $date . " ' , 'B' ,"   . $_POST['B'] . " , now() ) ");
		$result = db_query($query);
        }
	//DH
        if ( !empty( $_POST['DH'] ) )
        {
	$query = sprintf("INSERT INTO physical_fitness_profile(uid, sex, time, kind, value ,input_time) VALUES ( " . $user->uid . " , " . $sex . " , ' " . $date . " ' ,  'DH' ,"   . $_POST['DH'] . " , now() ) ");
	$result = db_query($query);
        }
	//EOL
        if ( !empty( $_POST['EOL'] ) )
        {
	$query = sprintf("INSERT INTO physical_fitness_profile(uid, sex, time, kind, value ,input_time) VALUES ( " . $user->uid . " , " . $sex . " , ' " . $date . " ' ,  'EOL' ,"   . $_POST['EOL'] . " , now() ) ");
	$result = db_query($query);
        }
	//FR
        if ( !empty( $_POST['FR'] ) )
        {
	$query = sprintf("INSERT INTO physical_fitness_profile(uid, sex, time, kind, value ,input_time) VALUES ( " . $user->uid . " , " . $sex . " , ' " . $date . " ',  'FR' ,"   . $_POST['FR'] . " , now() ) ");
	$result = db_query($query);
        }
}

?>
