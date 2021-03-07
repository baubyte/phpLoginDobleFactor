<?php

  include './templates/header.php';
  
  if (!$userController->isUserLoggedIn()) {
    header('Location: login.php');
  }

  //Segundo Factor
  $user = $userController->getUser();
  
  /**Para poder activar el segundo factor de autenticación */
  $hasTwoFactorActive = true;

  /**Comprobamos si el usuario tiene no tiene activo el segundo factor de autenticación*/
  if ($user['two_factor_key'] === null) {
      $hasTwoFactorActive = false;
      $googleAuthenticator = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
      /**Generamos la clave secreta */
      $secret = $googleAuthenticator->generateSecret();
      /**Generamos el QR */
      $qrCode = \Sonata\GoogleAuthenticator\GoogleQrUrl::generate($user['name'], $secret, "BAUBYTE");
  } 

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Segundo Factor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>

    <?php include './templates/nav.php' ?>

    <?php if (!$hasTwoFactorActive): ?>
        <div class="container mt-5">        
            <h5>Activar Doble Factor de Autenticación</h5><hr />
            <p>1. Para activar el segundo factor de autenticación instale Google Authenticator en su celular y escanee el Código QR</p>
            <img src="<?= $qrCode ?>" alt="Codigo QR">

            <p class="mt-4">2. Escriba el código generado por Google Authenticator y presione activar doble factor</p>
            <div class="row">
                <div class="col-md-4">
                    <form id="activateSecondFactor">                   
                        <div class="form-group">
                            <label for="code">Código</label>
                            <input type="text" class="form-control" id="code">            
                        </div>
                        <button type="submit" class="btn btn-primary">Activar Doble Factor</button>
                    </form>              
                    <div class="alert alert-danger mt-4 d-none" id="errorMessage"></div>  
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="container mt-5">        
            <h5>Desactivar Doble Factor de Autenticación</h5><hr />
            <button type="button" class="btn btn-primary" id="deactivateSecondFactor">Desactivar Doble Factor</button>
        </div>
    <?php endif; ?>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
    <?php if (!$hasTwoFactorActive): ?>
        <script>
            document.getElementById('activateSecondFactor').onsubmit = (e) => {
                e.preventDefault();

                const errorMessage = document.getElementById('errorMessage');
                errorMessage.classList.add('d-none');            
                const code = document.getElementById('code').value;
                const secret = '<?= $secret ?>';
                if (!code || !secret) {
                    return;
                }
                axios.post('api/activateSecondFactor.php', { code: code, secret: secret })
                    .then(res => {                        
                        window.location = 'panelSecondFactor.php';
                    })
                    .catch(err => {
                        errorMessage.innerText = err.response.data;
                        errorMessage.classList.remove('d-none');
                    });

            }
        </script>
    <?php else: ?>
        <script>
            document.getElementById('deactivateSecondFactor').onclick = (e) => {
                e.preventDefault();
                axios.post('api/deactivateSecondFactor.php')
                    .then(res => {
                        window.location = 'panelSecondFactor.php';
                    });
            }
        </script>
    <?php endif; ?>

</body>
</html>