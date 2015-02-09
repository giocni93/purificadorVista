<?php
session_start();
if(!isset($_SESSION['user'])){
?>
<!DOCTYPE html>
<html>

<head>

  <meta charset="UTF-8">

  <title>CodePen - Log-in</title>

  <link rel='stylesheet' href='http://codepen.io/assets/libs/fullpage/jquery-ui.css'>

  <link rel="stylesheet" href="css/style.css" media="screen" type="text/css" />
  <script type="text/javascript" src="js/servidorConf.js"></script>
  <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script> 
  
  <script>
      
        function jsonRegistroUser(user,pass){
                return JSON.stringify({
                    "user": user,
                    "pass": pass
                    });
        }
      
        function login(){
            
          var user = document.getElementsByName("user")[0].value;
          var pass = document.getElementsByName("pass")[0].value;
          
          jQuery.ajax({
                
                type: "POST",
                url: servidor+"usuario",
                dataType: "json",
                data: jsonRegistroUser(user,pass),

                    success: function (data, status, jqXHR) {
                        if(data == 1){
                             location.href = "http://localhost/purificadorVista/public_html/listaclientes.php";
                        }else{
                            alert("Error usuario o contraseña incorrecta");
                        }
                    },
                    error: function (jqXHR, status) {
                        alert("error ");
                    }
          });
          
      }
      
  </script>
  
</head>

<body>

  <div class="login-card">
      <div id="div_logo">
          <img src="img/Transparente.png"/>
      </div>
    <h1>Bienvenido</h1>
  <form name="formulario" method="post" action="javascript:login();">
    <input type="text" name="user" placeholder="Usuario">
    <input type="password" name="pass" placeholder="Contraseña"><br /> <br />
    <input type="submit" name="login" class="login login-submit" onclick="javascript:function(){document.formulario.submit();}" value="Entrar">
  </form>

  
</div>

<!-- <div id="error"><img src="https://dl.dropboxusercontent.com/u/23299152/Delete-icon.png" /> Your caps-lock is on.</div> -->

  <script src='http://codepen.io/assets/libs/fullpage/jquery_and_jqueryui.js'></script>

</body>

</html>
<?php
}
else{
?>
<script>location.href = "listaclientes.php";</script>
<?php
}
?>