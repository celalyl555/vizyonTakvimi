<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="images/ico.png" id="favicon">

	<title>Vizyon Takvimi Admin Giriş</title>
    <link href="style.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>
<body>
	<div class="loginBody">
		<div class="login">
			<h1>Giriş Yap</h1>
			<form action="authenticate" method="post">
				<div class="row nowrap">
					<label for="username">
						<i class="fas fa-user"></i>
					</label>
					<input type="text" name="username" placeholder="Kullanıcı Adı" id="username" required>
				</div>
				<div class="row nowrap">
					<label for="password">
						<i class="fas fa-lock"></i>
					</label>
					<input type="password" name="password" placeholder="Parola" id="password" required>
				</div>
				<input type="submit" value="Giriş">
			</form>
		</div>
	</div>
</body>
</html>