<?php 
	print '
	<h1>Registracija</h1>
	<div id="registracija">';
	
	if ($_POST['_action_'] == FALSE) {
		print '
		<form action="" id="registracija" name="registracija" method="POST">
			<input type="hidden" id="_action_" name="_action_" value="TRUE">
			
			<label for="ime">Ime *</label>
			<input type="text" id="ime" name="ime" placeholder="Vaše ime.." required>

			<label for="prezime">Prezime *</label>
			<input type="text" id="prezime" name="prezime" placeholder="Vaše prezime.." required>
				
			<label for="email">E-mail *</label>
			<input type="email" id="email" name="email" placeholder="Vaš e-mail.." required>
			
			<label for="kor_ime">Korisničko ime:* <small>(minimalno 5, maksimalno 10 znakova)</small></label>
			<input type="text" id="kor_ime" name="kor_ime" pattern=".{5,10}" placeholder="Korisničko ime.." required><br>
			
									
			<label for="lozinka">Lozinka:* <small>(Password must have min 4 char)</small></label>
			<input type="password" id="lozinka" name="lozinka" placeholder="Lozinka.." pattern=".{4,}" required>

			<label for="drzave">Država:</label>
			<select name="drzave" id="drzave">
				<option value="">molimo odaberite</option>';
				#Select all countries from database webprog, table countries
				$query  = "SELECT * FROM drzave";
				$result = @mysqli_query($MySQL, $query);
				while($row = @mysqli_fetch_array($result)) {
					print '<option value="' . $row['sifra_drzave'] . '">' . $row['ime_drzave'] . '</option>';
				}
			print '
			</select>

			<input type="submit" value="Predaj">
		</form>';
	}
	else if ($_POST['_action_'] == TRUE) {
		
		$query  = "SELECT * FROM korisnici";
		$query .= " WHERE email='" .  $_POST['email'] . "'";
		$query .= " OR kor_ime='" .  $_POST['kor_ime'] . "'";
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result, MYSQLI_ASSOC);
		
		if ($row['email'] == '' || $row['kor_ime'] == '') {
			# password_hash https://secure.php.net/manual/en/function.password-hash.php
			# password_hash() creates a new password hash using a strong one-way hashing algorithm
			$pass_hash = password_hash($_POST['lozinka'], PASSWORD_DEFAULT, ['cost' => 12]);
			
			$query  = "INSERT INTO korisnici (ime, prezime, email, kor_ime, lozinka, drzava)";
			$query .= " VALUES ('" . $_POST['ime'] . "', '" . $_POST['prezime'] . "', '" . $_POST['email'] . "', '" . $_POST['kor_ime'] . "', '" . $pass_hash . "', '" . $_POST['drzave'] . "')";
			$result = @mysqli_query($MySQL, $query);
			
			# ucfirst() — Make a string's first character uppercase
			# strtolower() - Make a string lowercase
			echo '<p>' . ucfirst(strtolower($_POST['ime'])) . ' ' .  ucfirst(strtolower($_POST['prezime'])) . ', zahvaljujemo na registraciji! </p>
			<hr>';
		}
		else {
			echo '<p>Korisnik sa istim email-om ili korisničkim imenom već postoji!</p>';
		}
	}
	print '
	</div>';
?>