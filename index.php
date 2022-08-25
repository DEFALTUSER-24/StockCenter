<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/settings.php";

    session_start();
    $message = "";
    if (isset($_POST['submit']))
    {
        $username = $_POST['sysUsername'];
        $password = $_POST['sysPassword'];
        
        if ($username !== null && $password !== null)
        {
            $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
            $recaptcha_response = $_POST['recaptcha_response']; 
            $recaptcha = file_get_contents($recaptcha_url . '?secret=' . G_RECAPTCHA_PRIVATE . '&response=' . $recaptcha_response);
            $recaptcha = json_decode($recaptcha); 
            if($recaptcha->score >= 0.7)
            {
                include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/users/usersFunctions.php";
                $message = (new UsersFunctions())->CheckCredentials($username, $password, true);
            }
            else { $message = "Autenticación fallida, tu scoring de reCAPTCHA está por debajo del límite."; }   
        }
        else { $message = "Credenciales incorrectas."; }
    }
    else
    {
        if (isset($_SESSION['uName']))
        {
            include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/main/sysFunctions.php";
            (new SysFunct())->SaveLog($_SESSION['uName'] . " salio del sistema."); 
            session_destroy();
            $_SESSION[] = array();
            $message = "Sesión finalizada.";
        }
    }
?>
   
<html lang="es">
    <head>
        <title>Iniciar sesion</title>
        <link rel="icon" type="image/png" href="img/icon.ico">
        
        <!-- METATAGS -->
		<meta charset='utf-8'/>
        <meta name="robots" content="NOINDEX,NOFOLLOW,NOARCHIVE,NOSNIPPET,NOODP,NOTRANSLATE,NOIMAGEINDEX,NOYDIR">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Ezequiel Castro">
        
        <!-- GOOGLE'S RE-CAPTCHA v3 -->
        <script src="https://www.google.com/recaptcha/api.js?render=<?=G_RECAPTCHA_PUBLIC?>"></script>
        <script>
            grecaptcha.ready(function() {
                grecaptcha.execute('<?=G_RECAPTCHA_PUBLIC?>', {action: 'loginscreen'}).then(function(token) {
                    var recaptchaResponse = document.getElementById('recaptchaResponse');
                    recaptchaResponse.value = token;
                });
            });
        </script>
        
        <!-- STYLES -->
        <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="css/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="css/animate.min.css">
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        <div class="loading" style="display: none;">
            <div class="loader"></div>
        </div>
        <main class="container-fluid h-100 fadeAnim animated fadeIn">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-12 col-sm-10 col-md-8 col-lg-8">
                    <form method="post" enctype="multipart/form-data">
                        <label for="user" class="loginLabel">Usuario:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-dark" type="button" onclick="$('#nombre').focus();" tabindex="-1">
                                    <i class="fas fa-user"></i>
                                </button>
                            </div>
                            <input id="nombre" type="text" class="form-control" name="sysUsername" placeholder="Ingrese su nombre de usuario" required autofocus>
                        </div>

                        <label for="pass" class="loginLabel">Clave:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-dark" type="button" onclick="$('#pass').focus();" tabindex="-1">
                                    <i class="fas fa-key"></i>
                                </button>
                            </div>
                            <input id="pass" type="password" class="form-control" name="sysPassword" placeholder="Ingrese su clave" required>
                        </div>
                        <small class="mt-2" style="display: none;" id="loadingText">Iniciando sesion...</small>
                        <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                        <input type="submit" name="submit" class="btn btn-dark btn-md mt-4" id="loginButton" value="Iniciar sesion" />
                    </form>
                    <?php if ($message != ""): ?>
                    <div class="alert alert-warning alert-dismissible fade show">
                        <strong>Aviso: </strong> <?php echo $message; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php endif ?>
                </div>
            </div>
        </main>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="scripts/boostrap/bootstrap.bundle.min.js"></script>
        <script>$("form").on("submit", function(event) { $("#loginButton").css("display", "none"); $(".loading").css("display", "block"); $("#loadingText").css("display", "block"); });</script>
        <div id="particles-js"></div>
        <script src="scripts/JS/Particles/particles.js"></script>
        <script>particlesJS.load('particles-js', 'scripts/JS/Particles/config.json', function() {});</script>
    </body>
</html>