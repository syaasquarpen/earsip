<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>E-Arsip - Login</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/floating-labels.css" rel="stylesheet">
</head>
<body>
    <form class="form-signin" method="post" action="cek_login.php">
        <div class="text-center mb-4">
            <img class="mb-4" src="assets/logo.png" alt="" width="130" height="130">
            <h1 class="h3 mb-3 font-weight-normal">Login E-Arsip</h1>
            <p>Silakan Masukan Username Dan Password Anda</p>
        </div>

        <div class="form-label-group">
            <input type="text" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
            <label for="username">Username</label>
        </div>

        <div class="form-label-group">
            <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
            <label for="password">Password</label>
        </div>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
        <p class="mt-5 mb-3 text-muted text-center">&copy; Copyright 2025 - <?=date('Y')?> | Lutfi - Passah</p>
    </form>
</body>
</html>