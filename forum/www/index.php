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

<script type="text/javascript">
	$(document).ready(function() {
		// Lukk succes vindu
		$(".popup-registrer-button-lukk").click(function() {
			$(".registrer-box-success").hide();
		});

		// Lukk mail sendt vindu
		$(".popup-registrer-button-lukk").click(function() {
			$(".registrer-box-mail-sendt").hide();
		});

		// Loading...
		$(".registrer-box-loading").hide();
		$(".popup-registrer-button").click(function() {
			$(".registrer-box-loading").show();
			$(window).load(function() {
				$(".registrer-box-loading").hide();
			});
		});

		// Skjul/vis registrer-box
		$("#registrer-box").hide();
		$("#registrer").click(function() {
			$("#registrer-box").show();
		});

		// Lukk registrer-box med knapp
		$("#registrer-avbryt").click(function() {
			$("#registrer-box").hide();
		});
	});
</script>

<?php
	require_once 'includes/db_connect.php';
	require_once 'includes/header.php';
?>

<br>
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
				<td class="center"><span class="icon-error"></span></td>
				<td>
					<h4><a href="#">Kat1_1</a><br><small>En beskrivelse</small></h4>
				</td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">1 234</a></td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">4 321</a></td>
				<td class="hidden-xs hidden-sm">
					av <a href="#">Bruker:1</a><br><small><span class="icon-time"></span> 1 dag siden</small>
				</td>
			</tr>
			<tr>
				<td class="center"><span class="icon-error"></span></td>
				<td>
					<h4><a href="#">Kat1_1</a><br><small>En beskrivelse</small></h4>
				</td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">1 234</a></td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">4 321</a></td>
				<td class="hidden-xs hidden-sm">
					av <a href="#">Bruker:1</a><br><small><span class="icon-time"></span> 1 dag siden</small>
				</td>
			</tr>
		</tbody>
	</table>

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
				<td class="center"><span class="icon-error"></span></td>
				<td>
					<h4><a href="#">Kat1_1</a><br><small>En beskrivelse</small></h4>
				</td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">1 234</a></td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">4 321</a></td>
				<td class="hidden-xs hidden-sm">
					av <a href="#">Bruker:1</a><br><small><span class="icon-time"></span> 1 dag siden</small>
				</td>
			</tr>
			<tr>
				<td class="center"><span class="icon-error"></span></td>
				<td>
					<h4><a href="#">Kat1_1</a><br><small>En beskrivelse</small></h4>
				</td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">1 234</a></td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">4 321</a></td>
				<td class="hidden-xs hidden-sm">
					av <a href="#">Bruker:1</a><br><small><span class="icon-time"></span> 1 dag siden</small>
				</td>
			</tr>
		</tbody>
	</table>

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
				<td class="center"><span class="icon-error"></span></td>
				<td>
					<h4><a href="#">Kat1_1</a><br><small>En beskrivelse</small></h4>
				</td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">1 234</a></td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">4 321</a></td>
				<td class="hidden-xs hidden-sm">
					av <a href="#">Bruker:1</a><br><small><span class="icon-time"></span> 1 dag siden</small>
				</td>
			</tr>
			<tr>
				<td class="center"><span class="icon-error"></span></td>
				<td>
					<h4><a href="#">Kat1_1</a><br><small>En beskrivelse</small></h4>
				</td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">1 234</a></td>
				<td class="text-center hidden-xs hidden-sm"><a href="#">4 321</a></td>
				<td class="hidden-xs hidden-sm">
					av <a href="#">Bruker:1</a><br><small><span class="icon-time"></span> 1 dag siden</small>
				</td>
			</tr>
		</tbody>
	</table>
</div>
</body>
</html>