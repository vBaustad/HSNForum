<?php
	//chat poster
	$con = mysqli_connect("localhost","root","","forum");
	if(!$con){
		die("fail");
	}else{
		echo("yey");
	}
	//lagre chatten
	if(isset($_POST['text']) && isset($_POST['name'])){
		$text = strip_tags(stripslashes($_POST['text']));
		$name = strip_tags(stripslashes($_POST['name']));

		if(!empty($text) && !empty($name)){
			//$insert = "INSERT INTO chatbox(msg_id, msg_navn, msg_melding) " . "VALUES(1, '$name', '$text');";
			$insert = "INSERT INTO chatbox(`msg_id`, `msg_navn`, `msg_melding`) VALUES('', '$name', '$text');";

			echo "<li class='cn><b>".ucwords($name)."</b> - ".$text."</li>";
		}
	}
	?>