<?php
  session_start();
  if(!isset($_SESSION['username']) || empty($_SESSION['username'])) {
   header('location: index.php');
 } else {
   $link = mysqli_connect("localhost", "root", "", "ESERCIZI_SQL");
     if ($link === false) {
         die("ERROR:Could not connect. " . mysqli_connect_error());
     }
   $username = $_SESSION['username'];
   $sql = "SELECT TipoAccesso FROM UTENTE WHERE Username = '$username'";
   $result = mysqli_query($link, $sql);
    if(!$riga = mysqli_fetch_array($result)){
      header('location: index.php');
    } else {
      if ($riga['TipoAccesso'] != "Libero"){
        header('location: index.php');
      }
    }
 }
 $_SESSION['rispostaAlunno'] = $_POST['risposta'];
 $_SESSION['domanda'] = $_POST['domanda'];
 $_SESSION['nomeDB'] = $_POST['nomeDB'];
?>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="icon" href="img/logo.png"/>
    <title> Basi di Dati - SQL </title>
  </head>
  <body>
    <?php require_once("navbarDocente.php") ?>
    <div class="container">
      <div class="row justify-content-around">
        <h4> <?php echo $_SESSION['domanda']; ?> </h4>
      </div>
      <div class="row justify-content-around">
        <div class="form-group mt-2">
            <textarea class="form-control" cols="70" rows="7" name="queryUtente" readonly> <?php echo trim($_SESSION['rispostaAlunno']); ?> </textarea>
        </div>

        <div class="form-group mt-2">
          <?php
            $link = mysqli_connect("localhost", "root", "", $_SESSION['nomeDB']);
              if ($link === false) {
                  die("ERROR:Could not connect. " . mysqli_connect_error());
              }
            $sql = $_SESSION['rispostaAlunno'];
            if (!$result = mysqli_query($link, $sql)){
              echo "Forma della query errata o operazione non consetita";
            } else {
              if (mysqli_num_rows($result)<1) {
                echo "La query non ha generato nessun risultato";
              } else {
                $rigaRisultato = mysqli_fetch_assoc($result);
                echo "<table border='1'>";
                echo "<tr>";
                echo "<th>".join("</th><th>",array_keys($rigaRisultato))."</th>";
                echo "</tr>";
                while ($rigaRisultato) {
                  echo "<tr>";
                   echo "<td>".join("</td><td>",$rigaRisultato)."</td>";
                   echo "</tr>";
                   $rigaRisultato = mysqli_fetch_assoc($result);
                };
                echo "</table>";
              }
            }
          ?>
        </div>
      </div>
      <div class="row justify-content-around">
        <form action='eseguiQueryRispostaAlunni.php' method='post'>
          <div class="form-group">
            <textarea class="form-control" cols="70" rows="7" name="queryDocente"></textarea>
          </div>
          <div class="row justify-content-around">
            <button type='submit' class='btn btn-primary' name='tipoOperazioneRichiesta' value='1'> Esegui </button>
          </div>
        </form>
      </div>
    </div>
    <?php require_once("footer.php") ?>
  </body>
</html>