function checkCookieName(){
	/*
	var loginId=getCookie("user");
	if(loginId!="empty"&&localStorage.username==null){
		localStorage.username=loginId;
		alert(localStorage.username);
	}
	*/
	if(localStorage.username!=null){
		$('.login_button').hide();
		$('.login_status').show();		
		var onlineStatus;
		if(online)onlineStatus="有";else onlineStatus="無";
		document.getElementById('userStatus').innerHTML = "<h3>"+localStorage.username+"你好!"+"<br>現在為「"+onlineStatus+"」網路連線狀態</h3>";	
	}
	else{
		$('.login_button').show();
		$('.login_status').hide();	
		document.getElementById('userStatus').innerHTML = "<h3>"+"您未登入！<br>"+"現在為「"+onlineStatus+"」網路連線狀態</h3>";
	}
}
function getCookie(c_name){
	var i,x,y,ARRcookies=document.cookie.split(";");
	for(i=0;i<ARRcookies.length;i++){
		x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
		y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
		x=x.replace(/^\s+|\s+$/g,"");
		if(x==c_name){
			return unescape (y);
		}
	}
	y="empty"	
	return y;
}
function checkCookieLogin(){
	var loginStatus=getCookie("hihi");
	if(loginStatus!="empty"){
		localStorage.loginStatus=loginStatus;
	}
}

