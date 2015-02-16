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
        <link rel="stylesheet" type="text/css" href="css/main.css" />
        <link rel="stylesheet" type="text/css" href="css/barra_superior.css" />
        <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.dataTables.js"></script>
        
        <script>

            var dataHis;
            var dataCli;
            var idCli;

            function initTabla(){
                //CONVERTIMOS NUESTRO LISTADO DE LA FORMA DEL JQUERY.DATATABLES- PASAMOS EL ID DE LA TABLA
                $('#tablaCli').dataTable({
                    "sPaginationType": "full_numbers" //DAMOS FORMATO A LA PAGINACION(NUMEROS)
                });
            }
            
            function initTablaHis(){
                //CONVERTIMOS NUESTRO LISTADO DE LA FORMA DEL JQUERY.DATATABLES- PASAMOS EL ID DE LA TABLA
                $('#tablaHis').dataTable({
                    "sPaginationType": "full_numbers",
                    "aaSorting": []
                });
            }

            function init(){
                document.getElementById("div_tablaHis2").style['visibility'] = 'hidden';
                document.getElementById("btnADDHis").style['visibility'] = 'hidden';
                listaCli();
                initTablaHis();
            }
            
            function jsonRegistroHis(titulo,observacion,tipo) {
                return JSON.stringify({
                    "titulo": titulo,
                    "observacion": observacion,
                    "tipo" : tipo,
                    "idCliente": idCli
                    });
            }
            
            function registrarHis(){
                var tit = document.getElementsByName("txtTitulo")[0].value;
                var obs = document.getElementsByName("txtObservacion")[0].value;
                var tip = document.getElementsByName("txtTipo")[0].value;
                jQuery.ajax({
                     type: "POST",
                     url: servidor+"historico",
                     dataType: "json",
		     data: jsonRegistroHis(tit,obs,tip),
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                             //mensaje guardado
                             //document.getElementById("divInfoInv").innerHTML = "<label style='color: green;'>Guardado correctamente</label>";
                             $('#tablaHis').dataTable().fnDestroy();
                             listaHis_porCliente();
                             alert("Guardado correctamente");
                        }
                         closeDialog();
                     },
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
            }
            
            function mostrarHis(i){
                idCli = dataCli[i].cedula;
                document.getElementById("lblNombre").innerHTML = dataCli[i].nombre + " " + dataCli[i].apellido;
                $('#tablaHis').dataTable().fnDestroy();
                listaHis_porCliente();
            }
            
            function detalleHis(i){
                //alert(dataHis[i].Observacion);
                document.getElementById("lblObservacion").innerHTML = dataHis[i].Observacion;
                document.getElementById("lblTitulo").innerHTML = dataHis[i].Titulo;
                document.getElementById("lblFecha").innerHTML = dataHis[i].Fecha;
                
                
                openDialog(1);
                
            }
            
            function listaHis_porCliente() {
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"historico/cliente/"+idCli,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         var tb = document.getElementById("tbody_tablaHis");
                         tb.innerHTML = "";
                         if(data != null){
                             dataHis = data;
                             for(var i = 0; i < data.length ; i++){
                                 tb.innerHTML += "<tr>\n\
                                 <td>"+data[i].Fecha+"</td>\n\
                                 <td>"+data[i].Tipo+"</td>\n\
                                 <td>"+data[i].Titulo+"</td>\n\
                                 <td><a onclick='javascript:detalleHis("+i+");' class='btnModi'>Detalle</a></td>\n\
                                 </tr>";
                             }
                         }
                         
                         initTablaHis();
                         document.getElementById("div_tablaHis2").style['visibility'] = 'visible';
                         document.getElementById("btnADDHis").style['visibility'] = 'visible';
                     },
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
            }

            function listaCli() {
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"consultarcliente",
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         var tb = document.getElementById("tbody_tablaCli");
                         tb.innerHTML = "";
                         if(data != null){
                             dataCli = data;
                             for(var i = 0; i < data.length ; i++){
                                 tb.innerHTML += "<tr>\n\
                                 <td>"+data[i].cedula+"</td>\n\
                                 <td>"+data[i].nombre+"</td>\n\
                                 <td>"+data[i].apellido+"</td>\n\
                                 <td>"+data[i].direccion_oficina+"</td>\n\
                                 <td>"+data[i].telefono+"</td>\n\\n\
                                 <td>"+data[i].email+"</td>\n\
                                 <td><a onclick='javascript:mostrarHis("+i+");' class='btnModi'>Ver</a></td>\n\
                                 </tr>";
                             }
                         }
                         initTabla();
                     },
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
            }

            function openDialog(ban) {
                if(ban == 0){
                    Avgrund.show( "#His-popup" );
                }
                if(ban == 1){
                    Avgrund.show("#Observacion-popup");
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
                      <a href="orden_pedido.php"><span>Orden de pedido</span></a>
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
                  <div class="link_title activo">
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
            <div class="rectangle"><h2>Historico</h2></div> 
            <div class="triangle-l"></div>
            
            <div id="div_tablaHis">
                <form class="contact_form" name="frmHis" action="" method="post">
                <table class="display" id="tablaCli">
                      <thead>
                          <tr>
                              <th>Cedula</th>
                              <th>Nombre</th>
                              <th>Apellido</th>
                              <th>Direccion</th>
                              <th>Telefono</th>
                              <th>Email</th>
                              <th></th>
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
            <div id="div_his">
                <button id="btnADDHis" onclick="javascript:openDialog(0);">Agregar Historial</button>
                <h2 style="color:#555555; width: 200px;">Historial</h2>
                <h4 style="margin-top: 15px; color:#759ABE" id="lblNombre"></h4>
                <div id="div_tablaHis2">
                    <form class="contact_form" name="frmHis2" action="" method="post">
                        <table class="display" id="tablaHis">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Titulo</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                            <tbody id="tbody_tablaHis">

                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
            
        </div>
            
            
        
        </article>    
        
        <aside id="His-popup" class="avgrund-popup">
            <form class="contact_form" name="frmHis" action="javascript:registrarHis();" method="post">
                <label style="width: 300px;">Titulo</label><br /><br /> 
                <input type="text" name="txtTitulo"  placeholder="" required />
                <br/>
                
                <label style="width: 300px;">Tipo</label><br /><br /> 
                <input type="text" name="txtTipo"  placeholder="" required />
                
                <label style="width: 300px;">Descripcion</label><br />
                <textarea name="txtObservacion" required></textarea>
                
                <br/><br/>
                <button id="btnCategoria" name="btnHistorial" onclick="javascript:function(){document.frmCat.submit();}" >Registrar</button>
                <br/>
            </form>
        </aside>
        
        <aside id="Observacion-popup" class="avgrund-popup">
            <div id="div_observacion">
                <h2 style="color:#759ABE" id="lblTitulo">Titulo</h2>
                <h3 style="color:#555555" id="lblFecha">fecha</h3>
                <br/>
                <br/>
                <h2 style="color:#759ABE">Descripcion</h2>
                <div id="div_ob_his">
                    <label id="lblObservacion"></label>
                </div>
                <br/>
                <button class="cance" onclick="closeDialog()">Cerrar</button>
            </div>
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