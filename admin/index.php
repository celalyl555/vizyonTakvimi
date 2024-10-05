<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Login</title>
    <link href="style.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>
<body>
	<div class="loginBody">
		<div class="login">
			<h1>Giriş Yap</h1>
			<form action="authenticate" method="post">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Kullanıcı Adı" id="username" required>
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Parola" id="password" required>
				<input type="submit" value="Giriş">
			</form>
		</div>
	</div>
</body>
</html>