
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">BAUBYTE</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
      </li>      
    </ul>
    <ul class="navbar-nav ml-auto">
      <?php if ($userController->isUserLoggedIn()): ?>
        <li class="nav-item active">
          <a class="nav-link" href="panelSecondfactor.php">Segundo Factor<span class="sr-only"></span></a>
        </li> 
        <li class="nav-item active">
          <a class="nav-link" href="panel.php"><?= $_SESSION['email'] ?> <span class="sr-only"></span></a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="api/logout.php">Cerrar Sesión<span class="sr-only"></span></a>
        </li>              
      <?php else: ?>
        <li class="nav-item active">
          <a class="nav-link" href="register.php">Registrarme <span class="sr-only"></span></a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="login.php">Iniciar Sesión <span class="sr-only"></span></a>
        </li>   
      <?php endif; ?>   
    </ul>    
  </div>
</nav>