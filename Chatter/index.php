<html>
<head>
	<title>Chatter</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800" rel="stylesheet" type="text/css">
	<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
</head>
<body>
	<div class="chat-box">
		<div class="chatMessages"> </div>
		<div class="text-area">
			<form action="#" onSubmit="return false;" class="chatForm">
				<input type="hidden" class="name" value="<?php echo $user; ?>"/>
				<input type="text" name="text" class="chat-box-msg" value="" placeholder="Skriv din melding..." />
				<input type="submit" name="submit" class="chat-box-send" value="Send" />
			</form>
		</div>
	</div>
</body>
</html>
