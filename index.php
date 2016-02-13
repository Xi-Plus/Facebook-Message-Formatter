<!DOCTYPE html>
<?php
$ori_name = $ori_message = $message = "";
if(isset($_POST["message"])){
	$C["DL"]="----------";
	$C["en"]["month"]=["January","February","March","April","May","June","July","August","September","October","November","December"];
	$C["en"]["day"]=["Today","Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
	$C["zh"]["day"]=["日","一","二","三","四","五","六"];

	$name = $ori_name = $_POST["name"];
	$message = $ori_message = $_POST["message"];

	$name = explode("\r\n", $name);
	foreach ($name as $key => $value) {
		$value = trim($value);
		if ($value =="") unset($name[$key]);
		else $name[$key] = $value;
	}
	$name = array_values($name);
	$size = count($name);
	$message = str_replace("\r\n", "\n", $message);

	for ($i=0; $i < $size; $i++) {
		for ($j=0; $j < $size; $j++) {
			$message = preg_replace("/(".$name[$i].")\n(".$name[$j].")/", "$1\n(photo)\n$2", $message);
		}
	}

	foreach ($C["en"]["day"] as $day) {
		$message = str_replace($day, $C["DL"].$day.$C["DL"], $message);
	}
	foreach ($C["en"]["month"] as $month) {
		$message = preg_replace("/(".$month." \d+, \d+)\n/", $C["DL"]."$1".$C["DL"]."\n", $message);
		$message = preg_replace("/(".$month." \d+)\n/", $C["DL"]."$1".$C["DL"]."\n", $message);
	}
	$message = str_replace("今天\n", $C["DL"]."今天".$C["DL"]."\n", $message);
	foreach ($C["zh"]["day"] as $day) {
		str_replace("星期".$day, $C["DL"]."星期".$day.$C["DL"], $message);
	}
	$message = preg_replace("/(\d+年\d+月\d+日)\n/", $C["DL"]."$1".$C["DL"]."\n", $message);
	$message = preg_replace("/(\d+月\d+日)\n/", $C["DL"]."$1".$C["DL"]."\n", $message);

	for ($i=0; $i < $size; $i++) {
		$message = preg_replace("/(".$name[$i].")\n(\d+-\d+-\d+ \d+:\d+)\n".$name[$i]."\n/", "($2) $1 ", $message);
		$message = preg_replace("/(".$name[$i].")\n(\d+:\d+(?:a|p)m)\n".$name[$i]."\n/", "($2) $1 ", $message);
		$message = preg_replace("/(".$name[$i].")\n(\d+:\d+)\n".$name[$i]."\n/", "($2) $1 ", $message);
		$message = preg_replace("/(\d+-\d+-\d+)(".$name[$i].")/", "($1) $2", $message);
	}
	$message = preg_replace("/(\d+-\d+-\d+)你/", "($1) 你", $message);
	$message = preg_replace("/(\d+-\d+-\d+)妳/", "($1) 妳", $message);

	for ($i=0; $i < $size; $i++) {
		$message = str_replace($name[$i]." ".$C["DL"], $name[$i]." (photo)\n".$C["DL"], $message);
	}

	$time=date("Y_m_d_H_i_s");
	if(!isset($_GET["nolog"])){
		file_put_contents("log/".$time."_0.txt", $ori_name);
		file_put_contents("log/".$time."_1.txt", $ori_message);
		file_put_contents("log/".$time."_2.txt", $message);
	}
}
?>
<html>
<head>
	<title>Facebook Message Formatter</title>
	<meta charset="UTF-8">
	<meta name=viewport content="width=device-width, initial-scale=1">
</head>
<body>
<center>
<h2>Facebook Message Formatter</h2>
<hr>
<form method="post">
	<div style="float: left;">
		name<br>
		<textarea name="name" rows="20" cols="20"><?php
			echo $ori_name;
		?></textarea>
	</div>
	<div style="float: left;">
		input<br>
		<textarea name="message" rows="20" cols="40"><?php
			echo $ori_message;
		?></textarea>
	</div>
	<div style="float: left;">
		<input type="submit" value="format"> output<br>
		<textarea name="message" rows="20" cols="60" disabled><?php
			echo $message;
		?></textarea>
	</div>
</form>
</center>
</body>
</html>