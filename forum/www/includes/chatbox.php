<?php
	require_once 'db_connect.php';
	require_once 'functions.php';
	
	// Get username
	$user = $_GET['u'];

	//lagre chatten
	if(isset($_POST['text']) && isset($_POST['name'])){
		$text = strip_tags(stripslashes($_POST['text']));
		$name = strip_tags(stripslashes($_POST['name']));

		// må byttes ut med mysqli
		if(!empty($text) && !empty($name)){
			$sql = "INSERT INTO chatbox (msg_id, msg_navn, msg_melding) VALUES(NULL, '$name', '$text')";
			
			if ($conn->query($sql) === TRUE) {
   			 	echo "New record created successfully";
			} 
			else {
   				echo "Error: " . $sql . "<br>" . $conn->error;
			}

			echo "<li class='cn><b>".ucwords($name)."</b> - ".$text."</li>";
		}
	}
?>

<div id="chatbox">
	<div class="chatbox-container pull-left">
		<div class='chatbox-messages'>

		</div>
		<div class='chatbox-bottom'>
			<form action='#' onSubmit='return false;' id='chatForm'>
				<input type='hidden' name='name' id='name' value='<?php echo $user; ?>'/>
				<input type='text' name='text' id='chatbox-msg' value='' placeholder='Skriv en melding til alle sammen!' />
				<input type='submit' name='submit' id="chatbox-send" value='Send' />
			</form>
		</div>
	</div>
</div>

<div class="textarea pull-right hidden-xs hidden-sm">
	<h1>TESTING</h1>
	<p>Mer test her om test og anndre ikke test reaterte tester </p>
</div>
<div class="clearfix seperator"></div>