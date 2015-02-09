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
        
        <link rel="stylesheet" href="css/demo.css" />
        <link rel="stylesheet" href="css/demo_table.css" />
        
        <link rel="stylesheet" type="text/css" href="css/menuVertical.css" />
        <link rel="stylesheet" type="text/css" href="css/main.css" />
        <link rel="stylesheet" type="text/css" href="css/main2.css" />
        <link rel="stylesheet" type="text/css" href="css/contacto_form.css" />
        <link rel="stylesheet" href="css/avgrund1.css" />
        <link rel="stylesheet" href="css/input.css" />
        <link rel="stylesheet" href="css/boton.css" />
        <link rel="stylesheet" type="text/css" href="css/barra_superior.css" />
        
        <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.dataTables.js"></script>
        
        <script>
            
            var dataVen;
            var id;
            
            function initTablaCon(){
                $('#tablaCon').dataTable({
                    "sPaginationType": "full_numbers" //DAMOS FORMATO A LA PAGINACION(NUMEROS)
                });
            }
            
            function init(){
                lista_mod_fecha();
            }
            
            function modFecha(){
                var fec = document.getElementsByName("fecha_instalacion")[0].value;
                alert("entro: "+id);
                //alert(incog);
                //alert(jsonModificarVendedor(nom,ape,tel));
               jQuery.ajax({
                     type: "PUT",
                     url: servidor+"ordenpedido/"+id,
                     dataType: "json",
		     data: jsonModificarFecha(fec),
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                             //Mensaje de guardado
                             alert("Se ha modificado esta fecha de instalacion");
                             location.href = "http://localhost/purificadorVista/public_html/ordenpedido.php";
                             closeDialog();
                             
                             
                         }
                     },
                     
                     error: function (jqXHR, status) {
                         alert("error modfecha");
                     }
                });
               
           }
           
           function jsonModificarFecha(fec){
                return JSON.stringify({
                    "fechaInstalacion": fec
                    });
            }
            
            function lista_mod_fecha(){
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"ordenpedido",
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         var tb = document.getElementById("tbody_tablaCon");
                         tb.innerHTML = "";
                         if(data != null){
                             dataVen = data;
                             for(var i = 0; i < data.length ; i++){
                                 tb.innerHTML += "<tr>\n\
                                 <td>"+data[i].cliente+"</td>\n\
                                 <td>"+data[i].nombre_inv+"</td>\n\
                                 <td>"+data[i].fecha_instalacion+"</td>\n\
                                 <td><a onclick='javascript:dialMosVen("+i+");' style='text-align: center; margin-left: 25px;' class='btnModi'>Modificar </a></td>\n\\n\\n\
                                 </tr>";
                             }
                         }
                         initTablaCon();
                     },
                     error: function (jqXHR, status) {
                         alert("error ");
                     }
                });
            }
            
            function dialMosVen(i){
                id = dataVen[i].id;
                document.getElementsByName("fecha_instalacion")[0].value = "";
                openDialog(0);
            }
            
            
            function openDialog(ban) {
                if(ban == 0){
                    Avgrund.show( "#msg-popup" );
                }
                if(ban == 1){
                    Avgrund.show( "#msg-popup2" );
                }
                if(ban == 2){
                    Avgrund.show( "#categoria-cliente" );
                }
                if(ban == 3){
                    Avgrund.show( "#categoria-vendedor" );
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
                  <div class="link_title activo">
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

        <div id="div_formularios5">
            
            <div class="rectangle"><h2> Modificar Fecha Instalación</h2></div> 
            <div class="triangle-l"></div>
            
            <form class="contact_form frmInv1" enctype="multipart/form-data" name="frm_ven" action="javascript:registrarVendedor();" method="post">
                <div id="div_"><br/><br/><br/>
                        <form class="">
                    
                    <div id="div_3"> 
                        <h2 style="color:#759ABE; background-color: transparent; position: relative; top: -30px;" id="lblTitulo">Lista De Modificación</h2>
                        <div id="div_tablaInv2" style=" width: 90%; position: relative; top:-30px;  border-top:1px solid #aaa; padding-top: 0;">
                            
                            <table class="display" id="tablaCon">
                                <thead>
                                    <tr>
                                        <th>Nombres Y Apellidos</th>
                                        <th>Producto</th>
                                        <th>Fecha Instalacion</th>
                                        <th>Modificar</th>
                                        
                                        
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
                                <tbody id="tbody_tablaCon">
                                            
				</tbody>
                            </table>
                            
                        </div>
                        
                    </div>
                    
                </form>
                </div>
            </form>
            
            
                    
        </div>
            
        
        
    </article>
        
        <aside id="msg-popup" class="avgrund-popup6" style="top: 32%;">
            <form class="contact_form" method="post" name="formcliente" action="javascript:modFecha();">
                <h2 style="color:#759ABE; background-color: transparent; position: relative; top: 5px;" id="lblTitulo">Modificar Fecha Instalación</h2>
                <br/><br/>
                
                <div class="div_form">
                    <label style="width: 200px;">Fecha Instalacion:</label><br /> <br /><br /> 
                    <input name="fecha_instalacion" type="date" required/><br/><br/>
                </div>
                
                <input name="txtmodnombres" type="hidden"    />
                
                
                <button style="position: relative; left: 10px; top: 60px;" id="btnRegistrarInv" onclick="javascript:function(){document.formcliente.submit();}" >Actualizar</button>
                
            </form>
        </aside>
        
        <aside id="msg-popup2" class="avgrund-popup">
            <label class="lblEliminar">¿ Deseas eliminar este vendedor ?</label>
            <label id="lblCancelarInv" class="lblEliminar"></label><br/><br/>
            <a onclick="javascript:eliminarVen();" class="aInvElimi">Aceptar</a> <br/>
            <a onclick="javascript:closeDialog();" id="btnCan1" class="aInvElimi">Cancelar</a>
        </aside>
        
        <aside id="categoria-vendedor" class="avgrund-popup3"> 
            <form class="">
                    
                    <div id="div_3"> 
                        <h2 style="color:#759ABE; background-color: transparent; position: relative; top: -30px;" id="lblTitulo">Lista De Vendedores</h2>
                        <div id="div_tablaInv2" style=" width: 90%; position: relative; top:-30px;  border-top:1px solid #aaa; padding-top: 0;">
                            
                            <table class="display" id="tablaCon">
                                <thead>
                                    <tr>
                                        <th>Cedula</th>
                                        <th>Nombres</th>
                                        <th>Apellidos</th>
                                        <th>telefono</th>
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
                                        
                                     </tr>
                                </tfoot>
                                <tbody id="tbody_tablaCon">
                                            
				</tbody>
                            </table>
                            
                        </div>
                        
                    </div>
                    
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