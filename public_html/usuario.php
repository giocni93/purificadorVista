<?php
session_start();
if(isset($_SESSION['user'])){
?>
<!DOCTYPE html>

<html lang="es">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script type="text/javascript" src="js/servidorConf.js"></script>

        <link rel="stylesheet" type="text/css" href="css/menuVertical.css" />
        
        <link rel="stylesheet" href="css/avgrund.css" />
        <link rel="stylesheet" href="css/input.css" />
        <link rel="stylesheet" href="css/boton.css" />
        <link rel="stylesheet" href="css/demo.css" />
        <link rel="stylesheet" href="css/demo_table.css" />
        <link rel="stylesheet" type="text/css" href="css/barra_superior.css" />
        <link rel="stylesheet" type="text/css" href="css/main.css" />
        <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.dataTables.js"></script>
        
        <script>

            var dataCli;
            var idUsu;

            function initTabla(){
                //CONVERTIMOS NUESTRO LISTADO DE LA FORMA DEL JQUERY.DATATABLES- PASAMOS EL ID DE LA TABLA
                $('#tablaCli').dataTable({
                    "sPaginationType": "full_numbers", //DAMOS FORMATO A LA PAGINACION(NUMEROS)
                    "aaSorting": []
                });
            }
            

            function init(){
                listaCli();
                listaRol();
            }
            
            function formato_numero(numero, decimales, separador_decimal, separador_miles){ // v2007-08-06
                numero=parseFloat(numero);
                if(isNaN(numero)){
                    return "";
                }

                if(decimales!==undefined){
                    // Redondeamos
                    numero=numero.toFixed(decimales);
                }

                // Convertimos el punto en separador_decimal
                numero=numero.toString().replace(".", separador_decimal!==undefined ? separador_decimal : ",");

                if(separador_miles){
                    // Añadimos los separadores de miles
                    var miles=new RegExp("(-?[0-9]+)([0-9]{3})");
                    while(miles.test(numero)) {
                        numero=numero.replace(miles, "$1" + separador_miles + "$2");
                    }
                }

                return numero;
            }
            
            function listaRol() {
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"usuario/rol",
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         var cbox = document.getElementsByName("cboxRol")[0];
                         cbox.innerHTML = "";
                         if(data != null){
                             for(var i = 0; i < data.length ; i++){
                                 cbox.innerHTML += "<option value = '"+data[i].Id+"' >"+data[i].Nombre+"</option>";
                             }
                         }
                     },
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
            }
            
            function jsonConsultas(){
                
                return JSON.stringify({
                    "fi": document.getElementsByName('txtFi')[0].value,
                    "ff": document.getElementsByName('txtFf')[0].value
                    });
            }
            
            function jsonRegistrUsuario(){
                
                return JSON.stringify({
                    "nombre": document.getElementsByName('txtNombre')[0].value,
                    "apellido": document.getElementsByName('txtApellido')[0].value,
                    "user": document.getElementsByName('txtUsuario')[0].value,
                    "pass": document.getElementsByName('txtPass')[0].value,
                    "rol": document.getElementsByName('cboxRol')[0].value
                    });
            }
            
            function jsonRegistrUsuarioModi(){
                
                return JSON.stringify({
                    "nombre": document.getElementsByName('txtNombre2')[0].value,
                    "apellido": document.getElementsByName('txtApellido2')[0].value,
                    "user": document.getElementsByName('txtUsuario2')[0].value,
                    "pass": document.getElementsByName('txtPass2')[0].value
                    });
            }
            
            function cancelarConsulta(){
                document.getElementsByName('txtFi')[0].value = null;
                document.getElementsByName('txtFf')[0].value = null;
                $('#tablaCli').dataTable().fnDestroy();
                listaCli();
            }
            
            function irConsulta(){
                $('#tablaCli').dataTable().fnDestroy();
                listaCli();
            }
            
            function eliminar(id){
                jQuery.ajax({
                     type: "DELETE",
                     url: servidor+"usuario/"+id,
                     dataType: "json",
                     data: jsonRegistrUsuario(),
                     success: function (data, status, jqXHR) {
                         
                         if(data != null){
                             if(data.estado == 1){
                                 alert("Eliminado correctamente.");
                                 
                                 $('#tablaCli').dataTable().fnDestroy();
                                 listaCli();
                             }else{
                                 alert("Error al eliminar");
                             }
                             
                         }
                         
                     },
                     error: function (jqXHR, status) {
                         alert("error eliminar");
                     }
                });
            }

            function listaCli() {
                
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"usuario",
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         var tb = document.getElementById("tbody_tablaCli");
                         tb.innerHTML = "";
                         if(data != null){
                             dataCli = data;
                             for(var i = 0; i < data.length ; i++){

                                 tb.innerHTML += "<tr>\n\
                                 <td>"+data[i].Id+"</td>\n\
                                 <td>"+data[i].Nombre+"</td>\n\
                                 <td>"+data[i].Apellido+"</td>\n\
                                 <td>"+data[i].User+"</td>\n\
                                 <td>"+data[i].Rol+"</td>\n\
                                 <td><a style='cursor: pointer;position:relative; top:0 ;left: 0; width: 20px;'onclick='javascript:dialogModi("+i+")'><img src='./img/edit.png' style=' width: 20px; height: 20px;' /></a></td>\n\
                                 <td><a style='cursor: pointer;position:relative; top:0 ;left: 0; width: 20px;'onclick='javascript:eliminar("+data[i].Id+")'><img src='./img/delete.png' style=' width: 20px; height: 20px;' /></a></td>\n\
                                 </tr>";
                             }
                         }
                         initTabla();
                     },
                     error: function (jqXHR, status) {
                         alert("error cliente");
                     }
                });
            }
            
            function dialogModi(i){
                idUsu = dataCli[i].Id;
                document.getElementsByName('txtNombre2')[0].value = dataCli[i].Nombre;
                document.getElementsByName('txtApellido2')[0].value = dataCli[i].Apellido;
                document.getElementsByName('txtUsuario2')[0].value = dataCli[i].User;
                document.getElementsByName('txtPass2')[0].value = "";
                document.getElementsByName('txtPassCon2')[0].value = "";
                openDialog(0);
            }
            
            function verificarPass(){
                var pass1 = document.getElementsByName("txtPass")[0].value;
                var pass2 = document.getElementsByName("txtPassCon")[0].value;
                if(pass1 != pass2){
                    alert("Las contraseñas no coinciden.");
                }
                else{
                    registrarUSuario();
                }
            }
            
            function registrarUSuario(){
                jQuery.ajax({
                     type: "POST",
                     url: servidor+"usuario/registro",
                     dataType: "json",
                     data: jsonRegistrUsuario(),
                     success: function (data, status, jqXHR) {
                         
                         if(data != null){
                             if(data.estado == 1){
                                 alert("Guardado correctamente.");
                                 
                                 document.getElementsByName('txtNombre')[0].value = "";
                                 document.getElementsByName('txtApellido')[0].value = "";
                                 document.getElementsByName('txtUsuario')[0].value = "";
                                 document.getElementsByName('txtPass')[0].value = "";
                                 document.getElementsByName('txtPassCon')[0].value = "";
                                 
                                 $('#tablaCli').dataTable().fnDestroy();
                                 listaCli();
                             }else{
                                 alert("Error al guardar");
                             }
                             
                         }
                         
                     },
                     error: function (jqXHR, status) {
                         alert("error registro");
                     }
                });
            }
            
            function modUSuario(){
                var pass1 = document.getElementsByName("txtPass2")[0].value;
                var pass2 = document.getElementsByName("txtPassCon2")[0].value;
                if(pass1 != pass2){
                    alert("Las contraseñas no coinciden.");
                }
                else{
                    
                    jQuery.ajax({
                        type: "PUT",
                        url: servidor+"usuario/"+idUsu,
                        dataType: "json",
                        data: jsonRegistrUsuarioModi(),
                        success: function (data, status, jqXHR) {

                            if(data != null){
                                if(data.estado == 1){
                                    alert("Modificado correctamente.");

                                    document.getElementsByName('txtNombre')[0].value = "";
                                    document.getElementsByName('txtApellido')[0].value = "";
                                    document.getElementsByName('txtUsuario')[0].value = "";
                                    document.getElementsByName('txtPass')[0].value = "";
                                    document.getElementsByName('txtPassCon')[0].value = "";

                                    $('#tablaCli').dataTable().fnDestroy();
                                    listaCli();
                                    
                                    closeDialog();
                                }else{
                                    alert("Error al guardar");
                                }

                            }

                        },
                        error: function (jqXHR, status) {
                            alert("error registro");
                        }
                    });
                }
            }
            
            function buscarMan(){
                var idM = document.getElementById("txtBuscarMan").value;
                detalleOrd(idM);
            }

            function openDialog(ban) {
                if(ban == 0){
                    Avgrund.show( "#msg-popup" );
                }

            }
            function closeDialog() {
                    Avgrund.hide();
            }
        </script>
        
    </head>
    <body onload="init()">
        <article class="avgrund-contents">
        <div id="div_cab">
            <img src="img/Transparente.png" id="img_logo"/>
            <div id="div_usuario">
                <img src="img/usuario.png" style="float: right;" />
                <label id="lbl_usuario"><?php echo $_SESSION['user']?></label><br/>
                <label id="lbl_rol"><?php echo $_SESSION['rol']?></label>
            </div>
            <div id="div_controles">
                <a href="out.php" id="a_cerrar">Cerrar sesion</a>
                <?php
                if($_SESSION['id_rol'] == 1){
                ?>
                <a href="vendedor.php" id="a_vendedores">Vendedores</a>
                <a href="usuario.php" id="a_usuarios">Usuarios</a>
                <?php
                }
                ?>
            </div>
        </div>
        
        <div id="div_menuVer">
            <nav>
              <UL>
               <li class="var_nav">
                  <div class="link_bg"></div>
                  <div class="link_title">
                    <div class=icon> 
                        <div id="icon-cliente"></div>
                    </div>
                      <a href="listaclientes.php"><span>Gestion cliente</span></a>
                  </div>
               </li>
               <li class="var_nav">
                  <div class="link_bg"></div>
                  <div class="link_title">
                    <div class=icon> 
                    <div id="icon-pedido"></div>
                    </div>
                      <a href="ordenpedido.php"><span>Orden de pedido</span></a>
                  </div>
               </li>
               <li class="var_nav">
                  <div class="link_bg"></div>
                  <div class="link_title">
                    <div class=icon> 
                    <div id="icon-planPago"></div>
                    </div>
                      <a href="plan_pagos.php"><span>Plan de pagos</span></a>
                  </div>
               </li>
               <li class="var_nav">
                  <div class="link_bg"></div>
                  <div class="link_title">
                    <div class=icon> 
                    <div id="icon-referencias"></div>
                    </div>
                      <a href="listareferencias.php"><span>Lista de referencias</span></a>
                  </div>
               </li>
               <li class="var_nav">
                  <div class="link_bg"></div>
                  <div class="link_title">
                    <div class=icon> 
                    <div id="icon-mantenimiento"></div>
                    </div>
                      <a href="mantenimiento.php"><span>Mantenimiento</span></a>
                  </div>
               </li>
               <li class="var_nav">
                  <div class="link_bg"></div>
                  <div class="link_title">
                    <div class=icon> 
                    <div id="icon-historico"></div>
                    </div>
                      <a href="historico.php"><span>Historico</span></a>
                  </div>
               </li>
               <li class="var_nav">
                  <div class="link_bg"></div>
                  <div class="link_title">
                    <div class=icon> 
                    <div id="icon-inventario"></div>
                    </div>
                      <a href="inventario.php"><span>Inventario</span></a>
                  </div>
               </li>
               <li class="var_nav">
                  <div class="link_bg"></div>
                  <div class="link_title">
                    <div class=icon> 
                    <div id="icon-tareas"></div>
                    </div>
                      <a href="tareashoy.php"><span>Tareas de hoy</span></a>
                  </div>
               </li>
               <li class="var_nav">
                  <div class="link_bg"></div>
                  <div class="link_title">
                    <div class=icon> 
                    <div id="icon-consulta"></div>
                    </div>
                      <a href="consultas.php"><span>Consultas</span></a>
                  </div>
               </li>
              </UL>
            </nav>
        </div>

        <div id="div_formularios">
            <div class="rectangle"><h2>Usuarios</h2></div> 
            <div class="triangle-l"></div>
            <!--<button id="btnBuscarMan" onclick="javascript:openDialog(4);">Busqueda especifica</button>-->
            
            <div id="div_" style="position: relative; top: 0px;">
                <form class="contact_form" action="javascript:verificarPass()" >
                <div class="div_form">
                    <label>Nombres</label><br /> <br /> 
                    <input name="txtNombre" type="text"  required />
                </div>

                <div class="div_form">
                    <label>Apellidos</label><br /> <br /> 
                    <input name="txtApellido" type="text"  required />
                </div>

                <div class="div_form">
                    <label>Usuario</label><br /> <br /> 
                    <input name="txtUsuario" type="text"   required />
                </div>

                <div class="div_form">
                    <label>Rol</label><br /> <br /> 
                    <select name="cboxRol" required onchange="javascript:consultaTipo();">
                    </select>
                </div>
                
                <div class="div_form">
                    <label>Contraseña</label><br /> <br /> 
                    <input name="txtPass" type="password"   required />
                </div>
                    
                <div class="div_form">
                    <label style="width: 200px;">Confirmar contraseña</label><br /> <br /> 
                    <input name="txtPassCon" type="password"   required />
                </div>
                    <button style="position: relative; top: 0px; left: -380px;">Registrar</button>
                </form>
            </div>
            
            <div id="div_tablaHis">
                <form class="contact_form" name="frmHis" action="" method="post">
                <table class="display" id="tablaCli">
                      <thead>
                          <tr>
                              <th>Id</th>
                              <th>Nombres</th>
                              <th>Apellidos</th>
                              <th>Usuario</th>
                              <th>Rol</th>
                              <th>Modificar</th>
                              <th>Eliminar</th>
                          </tr>
                      </thead>
                      <tfoot>
                          <tr>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                          </tr>
                      </tfoot>
                      <tbody id="tbody_tablaCli">

                      </tbody>
                  </table>
                  </form>
                
                    <div id="divInfoInv">
                            
                    </div>
              </div>
            
        </div>
            
            
        
        </article>    
       
        <aside id="msg-popup" class="avgrund-popup" style="top: 32%;">
            <form class="contact_form" name="form_modcod" method="post"  action="javascript:modUSuario();">
                <label style="width: 300px;">Nombres</label><br /> <br />
                <input name="txtNombre2" type="text"  required  />
                
                <label style="width: 300px;">Apellidos</label><br /> <br /><br/>
                <input name="txtApellido2" type="text"  required  />
                
                <label style="width: 300px;">Usuario</label><br /> <br /><br/>
                <input name="txtUsuario2" type="tel"  required />
                
                <label style="width: 300px;">Contraseña</label><br /> <br /><br/>
                <input name="txtPass2" type="password"  required />
                
                <label style="width: 300px;">Confirmar contraseña</label><br /> <br /><br/>
                <input name="txtPassCon2" type="password"  required />
                
                <button style="position: relative; left: -90px; top: -5px;" id="btnRegistrarInv" >Actualizar</button>
                <!--<input type="submit" value="Registrar"/>-->
            </form>
        </aside>
        
        
        <div class="avgrund-cover"></div>
        <script type="text/javascript" src="js/avgrund.js"></script>
        
    </body>
</html>
<?php
    }else{
?>
    <script>location.href = "index.php";</script>
<?php
    }
?>