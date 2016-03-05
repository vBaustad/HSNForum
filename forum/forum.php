<!DOCTYPE html>
<html lang="no">
<head>	
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="#">

		<title>Forum for studenter på HSN avdeling Bø</title>

		<link rel="stylesheet" type="text/css" href="css/stylesheet.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	</head>
<body>

	<?php
		require_once 'php/db_connect.php';
		require_once 'registrer.php';
		require_once 'loginn.php';
		require_once 'header.php';
		require_once 'chatbox.php';
	?>
<br>
	<p>ToDo:</p>
	<ol>
		<li>Fjerne Bootstrap og bygg min egen.</li>
		<li>Fjerne SwiftMail og bruk php mail() i stedet</li>
		<li>Registrering.php</li>
			<ol>
				<li>UTF8 må legges til på en måte. æøå må inn riktig i databasen</li>
				<li>Lage ett felt for feilmeldinger</li>
				<li>Fikse sjekkbruker</li>
				<li>Fikse sjekk for lik passord</li>
				<li>Fikse felt for godjkent regisrering og sendt mail</li>
			</ol>
		<li>Bekreft.php må fullføres</li>
		<li>Login.php må lages</li>
		<li>Resten av forumet fullføres</li>
	</ol>

	<table class="table forum table-striped">
		<thead>
			<tr>
				<th class="cell-stat"></th>
				<th>
					<h3>Kat1</h3>
				</th>
				<th class="cell-stat text-center hidden-xs hidden-sm">Emner</th>
				<th class="cell-stat text-center hidden-xs hidden-sm">Innlegg</th>
				<th class="cell-stat-2x hidden-xs hidden-sm">Siste innlegg</th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<td class="text-center"><i class="fa fa-smile-o fa-2x text-primary"></i></td>
				<td>
					<h4><a href="#">Kat1_1</a><br><small>En beskrivelse</small></h4>
				</td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">1 234</a></td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">4 321</a></td>
				<td class="hidden-xs hidden-sm">
					av <a href="#">Bruker:1</a><br><small><i class="fa fa-clock-o"></i> 1 dag siden</small>
				</td>
			</tr>

			<tr>
				<td class="text-center"><i class="fa fa-smile-o fa-2x"></i></td>
				<td>
					<h4><a href="#">kat1_2</a><br><small>En beskrivelse</small></h4>
				</td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">1 234</a></td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">4 321</a></td>
				<td class="hidden-xs hidden-sm">
					av <a href="#">Bruker:1</a><br><small><i class="fa fa-clock-o"></i> 1 dag siden</small>
				</td>
			</tr>
			
			<tr>
				<td class="text-center"><i class="fa fa-smile-o fa-2x"></i></td>
				<td>
					<h4><a href="#">kat1_3</a><br><small>En beskrivelse</small></h4>
				</td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">1 234</a></td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">4 321</a></td>
				<td class="hidden-xs hidden-sm">
					av <a href="#">Bruker:1</a><br><small><i class="fa fa-clock-o"></i> 1 dag siden</small>
				</td>
			</tr>

			<tr>
				<td class="text-center"><i class="fa fa-smile-o fa-2x"></i></td>
				<td>
					<h4><a href="#">kat1_4</a><br><small>En beskrivelse</small></h4>
				</td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">1 234</a></td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">4 321</a></td>
				<td class="hidden-xs hidden-sm">
					av <a href="#">Bruker:1</a><br><small><i class="fa fa-clock-o"></i> 1 dag siden</small>
				</td>
			</tr>
		</tbody>
	</table>

	<table class="table forum table-striped">
		<thead>
			<tr>
				<th class="cell-stat"></th>
				<th>
					<h3>Kat2</h3>
				</th>
				<th class="cell-stat text-center hidden-xs hidden-sm">Emner</th>
				<th class="cell-stat text-center hidden-xs hidden-sm">Innlegg</th>
				<th class="cell-stat-2x hidden-xs hidden-sm">Siste innlegg</th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<td class="text-center"><i class="fa fa-smile-o fa-2x"></i></td>
				<td>
					<h4><a href="#">Kat2_1</a><br><small>En beskrivelse</small></h4>
				</td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">1 234</a></td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">4 321</a></td>
				<td class="hidden-xs hidden-sm">
					av <a href="#">Bruker:2</a><br><small><i class="fa fa-clock-o"></i> 1 dag siden</small>
				</td>
			</tr>

			<tr>
				<td class="text-center"><i class="fa fa-smile-o fa-2x"></i></td>
				<td>
					<h4><a href="#">kat2_2</a><br><small>En beskrivelse</small></h4>
				</td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">1 234</a></td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">4 321</a></td>
				<td class="hidden-xs hidden-sm">
					av <a href="#">Bruker:1</a><br><small><i class="fa fa-clock-o"></i> 1 dag siden</small>
				</td>
			</tr>

			<tr>
				<td class="text-center"><i class="fa fa-smile-o fa-2x"></i></td>
				<td>
					<h4><a href="#">kat2_3</a><br><small>En beskrivelse</small></h4>
				</td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">1 234</a></td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">4 321</a></td>
				<td class="hidden-xs hidden-sm">
					av <a href="#">Bruker:1</a><br><small><i class="fa fa-clock-o"></i> 1 dag siden</small>
				</td>
			</tr>
		</tbody>
	</table>

	<table class="table forum table-striped">
		<thead>
			<tr>
				<th class="cell-stat"></th>
				<th>
					<h3>Kat3</h3>
				</th>
				<th class="cell-stat text-center hidden-xs hidden-sm">Emner</th>
				<th class="cell-stat text-center hidden-xs hidden-sm">Innlegg</th>
				<th class="cell-stat-2x hidden-xs hidden-sm">Siste innlegg</th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<td class="text-center"><i class="fa fa-smile-o fa-2x"></i></td>
				<td>
					<h4><a href="#">Kat3_1</a><br><small>En beskrivelse</small></h4>
				</td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">1 234</a></td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">4 321</a></td>
				<td class="hidden-xs hidden-sm">
					av <a href="#">Bruker:1</a><br><small><i class="fa fa-clock-o"></i> 1 dag siden</small>
				</td>
			</tr>

			<tr>
				<td class="text-center"><i class="fa fa-smile-o fa-2x"></i></td>
				<td>
					<h4><a href="#">kat3_2</a><br><small>En beskrivelse</small></h4>
				</td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">1 234</a></td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">4 321</a></td>
				<td class="hidden-xs hidden-sm">
					av <a href="#">Bruker:1</a><br><small><i class="fa fa-clock-o"></i> 1 dag siden</small>
				</td>
			</tr>
		</tbody>
	</table>
</div>
</body>
</html>