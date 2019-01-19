<?php 
	print '
	<h1>Prijava</h1>
	<div id="prijava">';
	
	if ($_POST['_action_'] == FALSE) {
		print '
		<form action="" name="myForm" id="myForm" method="POST">
			<input type="hidden" id="_action_" name="_action_" value="TRUE">

			<label for="kor_ime">Korisničko ime:*</label>
			<input type="text" id="kor_ime" name="kor_ime" value="" pattern=".{5,10}" required>
									
			<label for="lozinka">Lozinka:*</label>
			<input type="password" id="lozinka" name="lozinka" value="" pattern=".{4,}" required>
									
			<input type="submit" value="Prijava">
		</form>';
	}
	else if ($_POST['_action_'] == TRUE) {
		print 'usao';
		$query  = "SELECT * FROM korisnici";
		$query .= " WHERE kor_ime='" .  $_POST['kor_ime'] . "'";
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result, MYSQLI_ASSOC);
		#$hash = $row['lozinka'];
		#$pass_hash = password_hash($_POST['lozinka'], PASSWORD_DEFAULT, ['cost' => 12]);
		
		#if (password_verify($_POST['lozinka'], $hash)) {
		if (($_POST['kor_ime'] == $_POST['lozinka']) && ($row['ime'] != "")) {
			#password_verify https://secure.php.net/manual/en/function.password-verify.php
			
			$_SESSION['user']['valid'] = 'true';
			$_SESSION['user']['id'] = $row['id'];
			$_SESSION['user']['ime'] = $row['ime'];
			$_SESSION['user']['prezime'] = $row['prezime'];
			$_SESSION['message'] = '<p>Dobrodošli, ' . $_SESSION['user']['ime'] . ' ' . $_SESSION['user']['prezime'] . '</p>';
			# Redirect to admin website
			header("Location: index.php?menu=8");
		}
		
		# Bad username or password
		else {
			unset($_SESSION['user']);
			$_SESSION['message'] = '<p>Upisali ste krivo korisničko ime ili lozinku!' . $_SESSION['user']['ime'] . ' ' . $_SESSION['user']['prezime'] . '</p>';
			header("Location: index.php?menu=7");
		}
	}
	print '
	</div>';
?>