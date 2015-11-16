<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>Pop's IP View</title>
</head>
<center><h3>Pop's IP查询(搜索IP地址的地理位置)</h3></center>
<center>
<div id="locaIp"></div>
<div id="queryIp"></div>
<br>
<form id="ipform" name="ipform" method="post" action="javascript:void(0)">
<input name="ip_url" type="text" class="socss" id="ip_url" size="28" /> 
<input name="Submit" type="submit" class="btn" value=" 查 询 " onClick="getipdata('queryip','queryIp')"/>
</form>
</center>
<script language="javascript">
function myObjRequest(){
	var myhttp=null;
	try {
		myhttp = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(ie) {
			    try{
					myhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch(huohu){
					myhttp = new XMLHttpRequest();
					}
			}
	return myhttp;
}

function getipdata(action,divname){
  var ip_url=document.getElementById("ip_url").value;
  var url="ip.php?action="+action+"&ip_url="+ip_url;
  //alert(url);
  var myObj=myObjRequest();
  myObj.open("GET",url,true);
  myObj.onreadystatechange=function(){
    if (myObj.readyState==4){
	  //alert(myObj.readyState);
	  if (myObj.status==200){ //读取的数据正确
	     document.getElementById(divname).innerHTML=myObj.responseText;
	  }
	  else {
	     document.getElementById(divname).innerHTML="获取本地IP出错,请刷新本页或联系管理员!";
	  }
	}
  }
  myObj.send(null)
  }
getipdata("getip","locaIp");
</script>
<center>Copyright 2011 Pop All rights reserved.</center>
</body>
</html>