<!DOCTYPE html>
<html>
  <head>
    <title>Simacan</title>
    <link rel="stylesheet" type="text/css" href="../public/assets/style/login.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <link rel="icon" type="image/x-icon" href="../public/assets/image/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
  </head>
  <body>
    <img class="wave" src="../public/assets/image/wave2.png" />
    <div class="container">
      <div class="img">
        <img src="../public/assets/image/logo.png" />
      </div>
      <div class="login-content">
        <div class="content-wrap">
          <form action ="<?= BASEURL; ?>/Login/signIn" method="post">
          <h3 class="title mb-4">Login Simacan</h3>
            <div class="input-div one">
              <div class="i">
                <i class="fas fa-user"></i>
              </div>
              <div class="div">
                <h5 class="label">Username</h5>
                <input type="text" class="input" name="username" required />
              </div>
            </div>
            <div class="input-div pass">
              <div class="i">
                <i class="fas fa-lock"></i>
              </div>
              <div class="div">
                <h5 class="label">Password</h5>
                <input type="password" class="input" type="password" name="password" required />
              </div>
            </div>
            <input class="button" value="Login" type="submit" name="btn-login" />
          </form>
        </div>
      </div>
    </div>
    <script type="text/javascript" src="../public/assets/js/login.js"></script>
  </body>
</html>
