var online=true;
$(document).ready(function(){
	window.addEventListener("offline",function(){
		online=false;
		checkStatus();
	},false);

	window.addEventListener("online",function(){
		online=true;
		checkStatus();
	},false);
	// load Tabs
        $(document).ready(function() {
                $("#tabs").tabs();
                resize();
        });//end Tabs



},true);
// 更新體適能記錄
function updateRecord(){
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if(xmlhttp.readyState==4 && xmlhttp.status==200){
			localStorage.radar=xmlhttp.responseText;
		}
	}
	//xmlhttp.open("GET","http://jhs.jelly9.net/offline/radar.php",true);
	xmlhttp.open("GET","http://jhs.weco.net/offline/radar.php",true);
	xmlhttp.send();
}

// 上傳體適能資料
function upload_fitness_record()
{
	$.ajax({
		type: "POST",
		url: "http://jhs.weco.net/offline/upload_fitness.php",
		data: localStorage.radar_add,
		success : function(data, textStatus) {
			$("#return").after("<p>"+data+"</p>");
			if (textStatus == "success")
			{
				localStorage.radar_add = "";
			}
		}
	});
}


function checkStatus()
{
	if(online){
		$('.login_button').show();
		$('.login_status').hide();
 		$('.logout_button').hide();
		$('.offlineContainer').hide();
		checkCookieName();
		checkCookieLogin();
		if( localStorage.username != null || localStorage.username != "" )
		{
			upload_fitness_record();
			updateRecord();
		}
		resize();
	}
	else{
		$('.login_button').hide();
		$('.login_status').show();
 		$('.logout_button').hide();
		$('.offlineContainer').show();
		$('#tabs').hide();
		$('#wrap').hide();
		checkCookieName();
		checkCookieLogin();
	}
}
