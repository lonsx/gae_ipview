<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Tip's IP View</title>

<style type="text/css">
html,body{
	margin:0; padding:0;
	/*color:#4d4b4b;*/
	background: #C7EDCC;/*护眼色*/
	/*background: #d8d9d0;*/
	font-size:14px;	
	height: 100%;
}

#container{
	width:100%;
	margin:0px auto;
	text-align:center;
	min-height: 100%;
	height: auto !important;
	height: 100%;
	position: relative;
}

#header {
	padding: 10px;
}

#page {
	width:960px;
	margin: 0 auto;
	padding-bottom: 20px;/*等于footer的高度*/
}

#footer{ 
	position: absolute;/*使footer保持在页面底部*/
	bottom: 0;
	width: 100%;
	height: 20px;/*脚部的高度*/
	/*background: #6cf;*/
	clear:both;
	}

h3{font-size: 23px;}

</style>

</head>
<body>
	<div id="container">
		<div id="header"><center><h3>Tip's IP查询(搜索IP地址的地理位置)</h3></center></div>
<center>
<!--//----------自加代码开始---------->
<?php
$key=demo;//指定ipaddresslabs.com所用的key
//Our public demo license has been retired. Please get your own license.
$iip=$_SERVER["REMOTE_ADDR"];//获取客户端ip
$iurl="http://services.ipaddresslabs.com/iplocation/locateip?key=".$key."&ip=".$iip;//叠加查询url字符串
$il="您的ip：";
filter($iurl,$il,$iip);//显示client的ip信息

//测试代码，看是否支持fopen
/*$u="http://www.google.com/";
$fp=fopen($u,'r');
while(!feof($fp)){
			$result.=fgets($fp,1024);
				 }
echo "url body: ".$result;
printhr();
fclose($fp);*/

//判定字符串是否是 IP 地址
function IsIPAdress($value){

    //if (preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $value)){
    if (preg_match('/^((25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(25[0-5]|2[0-4]\d|[01]?\d\d?)$/', $value)){	//新的ip匹配正则
        return true;
    }
    return false;
}
//Returns the IPv4 address or a string containing the unmodified hostname on failure.
function getAddrByHost($host, $timeout = 3) {
		$query = 'nslookup -timeout='.$timeout.' -retry=1 '.$host." 8.8.8.8";
		$query = shell_exec($query);
		if(preg_match('/\nAddress: (.*)\n/', $query, $matches))
			return trim($matches[1]);
		return $host;
											}
//gethostbyname()和shell_exec()不管用时，通过查询其他的免费网站获取域名对应的ip
function getipbynet($host){
		$url_c="http://www.ip.cn/index.php?ip=".$host;
		error_reporting(7); 
		$file_c = fopen ($url_c, "rb"); //远程打开查询url
		if (!$file_c) { //若不存在，输出错误信息
					echo "<font color=red>Unable to open remote file.</font>\n"; 
					exit; 
					}
		while (!feof ($file_c)) { //逐行遍历$file，查询匹配字符串并输出（出错情况指定）
								$line = fgets ($file_c, 1024); 
								if (eregi ("<code>(.*)</code>",iconv('gbk','UTF-8//IGNORE',$line),$out)) { 
												//echo "ERROR! Worng ip or hostname!"; 
												$m=$out[1];
												$z = true;
												break;
												} 
					 		   }

		/*$file_c = fopen ($url_c, "r");
		while(!feof($file_c)){
			$l=fgets($file_C,1024);
			$result.="\xEF\xBB\xBF".iconv('gbk','utf-8',$l);
				 			}
		echo "url body: ".$result;
		printhr();*/
		fclose($file_c);
		if($z){
			//$r=iconv('UTF-8//IGNORE','gbk',trim($m));
			return trim($m);
			}
			
		return $host;
						  }
//--------ip查询-------------->
function geo($a,$key){
//global $ip_address,$continent_name,$country_code,$country_name,$region_code,$region_name,$city,$postal_code,$latitude,$longitude,$isp;
$ip_url = trim($a);
if($ip_url){
			if(IsIPAdress($ip_url)) {
									$ip=$ip_url;
									$url="http://services.ipaddresslabs.com/iplocation/locateip?key=".$key."&ip=".$ip;//叠加查询url字符串
									$l="您查询的ip：";
									filter($url,$l,$ip_url);
									} 
			else {
				  
				  //$ip=getAddrByHost($ip_url, $timeout = 3);
				  //$ip=gethostbyname($ip_url);
				  $ip=getipbynet($ip_url);
				  if(IsIPAdress($ip)){
									 //$url="http://services.ipaddresslabs.com/iplocation/locatehostname?key=".$key."&hostname=".$ip;//叠加查询url字符串
									 $url="http://services.ipaddresslabs.com/iplocation/locateip?key=".$key."&ip=".$ip;//叠加查询url字符串
									 $l="您查询的域名：";
									 filter($url,$l,$ip_url);
									 }
				  else{
					  print "/".$ip."/";
					  echo "ERROR! Worng ip or hostname!";
					  }
				 }
			}
else{
	echo "None!";
	}
				}
//--------ip信息滤出-------------->
function filter($url,$l,$ip_url){
error_reporting(7); 
$file = fopen ($url, "r"); //远程打开查询url
if (!$file) { //若不存在，输出错误信息
			echo "<font color=red>Unable to open remote file.</font>\n"; 
			exit; 
			}
while (!feof ($file)) { //逐行遍历$file，查询匹配字符串并输出（出错情况指定）
		$line = fgets ($file, 1024); 
		if (eregi ("<query_status_code>OK</query_status_code>", $line)) { 
			//echo "ERROR! Worng ip or hostname!"; 
			$p = true;
			break;
			} 
					 }	
if ($p){//滤出匹配字符字段
		//rewind($file);
		while (!feof ($file)) { //逐行遍历$file，查询匹配字符串并输出
				$line = fgets ($file, 1024);  
				if (eregi ("<ip_address>(.*)</ip_address>", $line, $out)) { 
					$ip_address = $out[1]; 
					break;
					} else if(eregi ("<resolved_ip_address>(.*)</resolved_ip_address>", $line, $out)) {
								$ip_address = $out[1]; 
								break;
								}
				} 
		//rewind($file);//重置文件指针位置
		while (!feof ($file)) { 
				$line = fgets ($file, 1024); 
				if (eregi ("<continent_name>(.*)</continent_name>", $line, $out)) { 
					$continent_name = $out[1];  
					break;
					} 
				}			
		//rewind($file);
		while (!feof ($file)) { 
				$line = fgets ($file, 1024); 
				if (eregi ("<country_code_iso3166alpha2>(.*)</country_code_iso3166alpha2>", $line, $out)) { 
				$country_code = $out[1]; 
				break;
					} 
				}
		//rewind($file);
		while (!feof ($file)) { 
				$line = fgets ($file, 1024); 
				if (eregi ("<country_name>(.*)</country_name>", $line, $out)) { 
					$country_name = $out[1]; 
					break;
					} 
				}
		//rewind($file);
		while (!feof ($file)) { 
				$line = fgets ($file, 1024); 
				if (eregi ("<region_code>(.*)</region_code>", $line, $out)) { 
					$region_code = $out[1];  
					break;
					} 
				}
		//rewind($file);
		while (!feof ($file)) { 
				$line = fgets ($file, 1024); 
				if (eregi ("<region_name>(.*)</region_name>", $line, $out)) { 
					$region_name = $out[1]; 
					break;
					} 
				}
		//rewind($file);
		while (!feof ($file)) { 
				$line = fgets ($file, 1024); 
				if (eregi ("<city>(.*)</city>", $line, $out)) { 
					$city = $out[1];  
					break;
					} 
				}
		//rewind($file);
		while (!feof ($file)) { 
				$line = fgets ($file, 1024); 
				if (eregi ("<postal_code>(.*)</postal_code>", $line, $out)) { 
					$postal_code = $out[1]; 
					break;
					} 
				}
		//rewind($file);
		while (!feof ($file)) { 
				$line = fgets ($file, 1024); 
				if (eregi ("<latitude>(.*)</latitude>", $line, $out)) { 
					$latitude = $out[1];  
					break;
					} 
				}
		//rewind($file);
		while (!feof ($file)) { 
				$line = fgets ($file, 1024); 
				if (eregi ("<longitude>(.*)</longitude>", $line, $out)) { 
					$longitude = $out[1]; 
					break;
					} 
				}
		//rewind($file);
		while (!feof ($file)) { 
				$line = fgets ($file, 1024); 
				if (eregi ("<isp>(.*)</isp>", $line, $out)) { 
					$isp = $out[1]; 
					break;
					} 
				}
		}
else{
	$file = fopen ($url, "r");
	while (!feof ($file)) { 
			$line = fgets ($file, 1024); 
			if (eregi ("<query_status_description>(.*)</query_status_description>", $line, $out)) { 
				$status = $out[1]; 
				break;
				} 
						  }
	if ($status){
		echo "Something is wrong ! The error code is".$status.".";
				}
	else{
		global $key;//在function中调用外部全局变量的声明
		echo "<div>The ipaddresslabs key :<font color=red>".$key."</font> can not be used anymore !<br><br></div>\n";
		}
	//echo $url;
	/*rewind($file);
	while(!feof($file)){
			$result.=fgets($file,1024);
				 }
	echo "url body: ".$result;
	printhr();*/
	
	}
fclose($file); 
if ($p){//制表显示ip查询结果
?>
<div width=100% align="center">
<fieldset align="left" style="width:500px;">
<legend><?php echo $l;?> <font color=red><?php echo $ip_url;?></font> 信息如下：<br></legend>
<div align="center">
<table width="500" border="0">
  <tr>
    <th width="84" align="right" scope="row">ip地址：</th>
    <td width="306" align="center"><?php echo $ip_address;?></td>
  </tr>
  <tr>
    <th align="right" scope="row">地域：</th>
    <td align="center"><?php echo $continent_name;?></td>
  </tr>
  <tr>
    <th align="right" scope="row">国家代码：</th>
    <td align="center"><?php echo $country_code;?></td>
  </tr>
  <tr>
    <th align="right" scope="row">国家：</th>
    <td align="center"><?php echo $country_name;?></td>
  </tr>
  <tr>
    <th align="right" scope="row">区域代码：</th>
    <td align="center"><?php echo $region_code;?></td>
  </tr>
  <tr>
    <th align="right" scope="row">区域：</th>
    <td align="center"><?php echo $region_name;?></td>
  </tr>
  <tr>
    <th align="right" scope="row">城市：</th>
    <td align="center"><?php echo $city;?></td>
  </tr>
  <tr>
    <th align="right" scope="row">邮编：</th>
    <td align="center"><?php echo $postal_code;?></td>
  </tr>
  <tr>
    <th align="right" scope="row">经纬度：</th>
    <td align="center"><?php echo $latitude." x ".$longitude;?></td>
  </tr>
  <tr>
    <th align="right" scope="row">ISP：</th>
    <td align="center"><?php echo $isp;?></td>
  </tr>
</table>
</div>
</fieldset>
</div>
<?php
		}
					  }
?>

<!--//----------自加代码结束---------->
<div id="locaIp"></div><br>
<div id="queryIp"></div>
<br>
<form id="ipform" name="ipform" method="post" action="javascript:void(0)">
<input name="ip_url" type="text" class="socss" id="ip_url" size="28" /> 
<input name="Submit" type="submit" class="btn" value=" 查 询 " onClick="getipdata('queryip','queryIp')"/>
</form>

<!--/*<div align=center>
<?php
//If we submitted the form
if(isset($_POST['Submit'])){
?>
							<form action="<?php $_SERVER['PHP_SELF']?>" method="POST">
							<input type="text" name="ip_url" id="ip_url" size="28">
							<input type="submit" value=" 查 询 " name="Submit">
							</form>
<?php 
							geo($_POST["ip_url"],$key);
							}
//If we haven't submitted the form
else{
?>
    <form action="<?php $_SERVER['PHP_SELF']?>" method="POST">
    <input type="text" name="ip_url" id="ip_url" size="28">
    <input type="submit" value=" 查 询 " name="Submit">
    </form>
<?php
	}
?>
</div>*/-->

		<div id="page"><br>
</center>
<center><?php 
$a=filemtime("qqwry.dat"); 
echo "Update Time:".date("Y-m-d H:i:s",$a)." 
"; 
?></center>
<br>
<!--/*<script type="text/javascript"> 
for(var i=0; i<400; i++){ 
document.write(i+'<br/>'); 
} 
</script>*/-->
		</div>
		<div id="footer"><center>Copyright © 2016. All rights reserved.</center></div>

	</div>
<script language="javascript">
//类似 PHP 中的 trim
function trim(str) {
        var str = str.replace(/^\s\s*/, ''),
            ws = /\s/,
            i = str.length;
        while (ws.test(str.charAt(--i)));
        return str.slice(0, i + 1);
    }
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
  var a=document.getElementById("ip_url").value;
  var ip_url=trim(a);
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
</body>
</html>