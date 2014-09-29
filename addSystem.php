<?php
define("CONF_DIR", "D:\code\spacewalk-tools");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="../../favicon.ico">

  <title>Theme Template for Bootstrap</title>

  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap theme -->
  <link href="css/bootstrap-theme.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="theme.css" rel="stylesheet">
</head>

<body role="document">

  <!-- Fixed navbar -->
  <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Spacewalk tools</a>
      </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <li class="active"><a href="#">Ajout machine</a></li>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>

  <div class="container theme-showcase" role="main">

    <div class="well">
      <p>1 - Sélectionnez l'environnement dans lequel vous souhaitez ajouter votre machine puis saisissez les informations demandées. Cela va mettre à jour la configuration de l'environnement.</p>
      <p>2 - Dans un second temps, pensez à lancer le script /app/scrips/spacewalk_add_server.sh -y <em>MACHINE_NAME</em></p>
    </div>

    <div class="page-header">
      <h1>Choissisez un fichier de configuration existant</h1>
    </div>

    <?php
    
    $files = array();
    foreach (scandir(CONF_DIR) as $file){
      preg_match('/.*server_(int|rect|vabf|iso|prod|dev).conf/', $file, $matches);
      if(empty($matches) == FALSE)array_push($files, $matches[0]);
    }

    if(sizeof($files) == 0){
      die("Impossible to get conf files.");
    }
    ?>


    <form role="form" action="createMachine.php" id="form-main">
      <div class="btn-group">
        <select class="form-control" id="conf-file-selector">
          <?php
          for ($i=0; $i < sizeof($files); $i++) { 
            echo '<option>'.$files[$i].'</option>';
          }
          ?>
        </select>
      </div>

      <div class="page-header">
        <h1>Saissez les informations de la machine</h1>
      </div>

      <div class="form-group">
        <label for="machineName">Nom de la machine</label>
        <input type="text" class="form-control" name="machineName" placeholder="Entrez le nom de la machine" required aria-required="true" pattern="\S*" title="Pas d'espace">
      </div>
      <div class="form-group">
        <label for="domainName">Nom du domaine</label>
        <input type="text" class="form-control" name="domainName" placeholder="Entrez le domain sur lequel est la machine" required aria-required="true" pattern="\S*" title="Pas d'espace">
      </div>
      <div class="form-group">
        <label for="kickstartProfile">Profile KICKSTART</label>
        <input type="text" class="form-control" name="kickstartProfile" placeholder="Entrez le nom du profil KickStart" required aria-required="true" pattern="\S*" title="Pas d'espace">
      </div>

      <div class="page-header">
        <h1>Réseau</h1>
      </div>

      <div class="form-group">
        <label>Nombre d'interfaces réseau</label>

        <select class="form-control" id="network-number">
          <option>1</option>
          <option>2</option>
          <option>3</option>
          <option>4</option>
          <option>5</option>
        </select> 

        <div name="network-def-1">
          <div class="page-header">
            <h1><small>Carte réseau 1</small></h1>
          </div>

          <div class="checkbox" name="bonding-vlan-1">
            <label>
              <input name="bonding-cb-1" type="checkbox">Bonding
            </label>
          </div>
          <div class="form-group">
            <label for="macAddress-1">MAC</label>
            <input type="text" class="form-control" name="macAddress-1" placeholder="Adresse MAC" required aria-required="true" pattern="(\d|[a-fA-F]){2}:(\d|[a-fA-F]){2}:(\d|[a-fA-F]){2}:(\d|[a-fA-F]){2}:(\d|[a-fA-F]){2}:(\d|[a-fA-F]){2}" title="XX:XX:XX:XX:XX:XX">
          </div>
          <div class="form-group">
            <label for="ipAddress-1">IP</label>
            <input type="text" class="form-control" name="ipAddress-1" placeholder="Adresse IP" required aria-required="true" pattern="\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}" title="Ex: 192.168.0.0">
          </div>
          <div class="form-group">
            <label for="gateway-1">Passerelle par défaut</label>
            <input type="text" class="form-control" name="gateway-1" placeholder="Passerelle par défaut" required aria-required="true" pattern="\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\/\d{1,2}:\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}" title="Ex: 192.168.0.24/24:192.168.0.1">
          </div>
        </div>
        
         <button type="submit" class="btn btn-lg btn-primary">Ajouter la machine</button>
      </form>    

    </div> <!-- /container -->


<!-- Bootstrap core JavaScript
  ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="js/jquery-1.11.1.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="events.js"></script>
  <script src="addEvent.js"></script>
</body>
</html>