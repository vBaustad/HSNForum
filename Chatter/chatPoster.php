<?php
	//chat poster
	$conn = mysqli_connect("localhost","root","","forum");
	if(!$conn){
		die("fail");
	}
	//lagre chatten
	if(isset($_POST['text']) && isset($_POST['name'])){
		$text = strip_tags(stripslashes($_POST['text']));
		$name = strip_tags(stripslashes($_POST['name']));

		if(!empty($text) && !empty($name)){
			//$insert = "INSERT INTO chatbox('msg_id, 'msg_navn, 'msg_melding') VALUES('','".$name."','".$text."')";
			$sql = "INSERT INTO chatbox (msg_id, msg_navn, msg_melding) VALUES('', '$name', '$text')";
			if ($conn->query($sql) === TRUE) {
   			 	echo "New record created successfully";
			} else {
   				 	echo "Error: " . $sql . "<br>" . $conn->error;
					}

			echo "<li class='cn><b>".ucwords($name)."</b> - ".$text."</li>";
		}
	}
	?>