<?php

  include './templates/header.php';  

  if ($userController->isUserLoggedIn()) {
    header('Location: panel.php');
  }

  if (!(isset($_SESSION['isLoggedIn']) && isset($_SESSION['email']))) {
    header('Location: login.php');
  }

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>

    <?php include './templates/nav.php' ?>

    <div class="container mt-5">
        <div class="row justify-content-md-center">
            <div class="col col-md-6">
                <h3>Segundo Factor de Autenticación</h3><hr />
                <form id="secondFactorForm">                   
                    <div class="form-group">
                        <label for="code">Código</label>
                        <input type="text" class="form-control" id="code">            
                    </div>                    
                    <button type="submit" class="btn btn-primary">Ingresar</button>
                </form>
                <div class="alert alert-danger mt-4 d-none" id="errorMessage"></div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
    <script>
        document.getElementById('secondFactorForm').onsubmit = (e) => {
            e.preventDefault();

            const errorMessage = document.getElementById('errorMessage');
            errorMessage.classList.add('d-none');            
            const code = document.getElementById('code').value;
            
            if (!code) {
                return;
            }

            axios.post('api/loginSecondFactor.php', { code: code })
                .then(res => {                   
                    window.location = 'panel.php';
                })
                .catch(err => {
                    errorMessage.innerText = err.response.data;
                    errorMessage.classList.remove('d-none');                    
                });

        }
    </script>
    
</body>
</html>


