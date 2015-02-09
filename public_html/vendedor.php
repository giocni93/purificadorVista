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
        <link rel="stylesheet" type="text/css" href="css/barra_superior.css" />
        <link rel="stylesheet" href="css/avgrund1.css" />
        <link rel="stylesheet" href="css/input.css" />
        <link rel="stylesheet" href="css/boton.css" />
        
        <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.dataTables.js"></script>
        
        <script>
            
            var dataVen;
            var idVen;
            
            function initTablaCon(){
                $('#tablaCon').dataTable({
                    "sPaginationType": "full_numbers" //DAMOS FORMATO A LA PAGINACION(NUMEROS)
                });
            }
            
            function init(){
                listaVen();
            }
            
            function listaVen() {
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"vendedor",
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         var tb = document.getElementById("tbody_tablaCon");
                         tb.innerHTML = "";
                         if(data != null){
                             dataVen = data;
                             for(var i = 0; i < data.length ; i++){
                                 tb.innerHTML += "<tr>\n\
                                 <td>"+data[i].cedula+"</td>\n\
                                 <td>"+data[i].nombres+"</td>\n\
                                 <td>"+data[i].apellidos+"</td>\n\
                                 <td>"+data[i].telefono+"</td>\n\
                                 <td><a onclick='javascript:dialMosVen("+i+");' style='text-align: center; margin-left: 25px;' class='btnModi'>Ver </a></td>\n\\n\\n\
                                 <td><a onclick='javascript:dialoEliVen("+i+")' style='text-align: center; margin-left: 25px;' class='btnModi'>Ver </a></td>\n\n\
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
            
            function dialoEliVen(i){
                idVen = dataVen[i].cedula;
                closeDialog();
                openDialog(1);
            }
            
            function dialMosVen(i){
                document.getElementsByName("txtmodnombres")[0].value = dataVen[i].nombres;
                document.getElementsByName("txtmodapellidos")[0].value = dataVen[i].apellidos;
                document.getElementsByName("txtmodtelefono")[0].value = dataVen[i].telefono;
                idVen = dataVen[i].cedula;
                closeDialog();
                openDialog(0);
            }
            ///////eliminar vendedor
            function eliminarVen() {
                //alert(idVen);
                jQuery.ajax({
                     type: "DELETE",
                     url: servidor+"vendedor/"+idVen,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                            location.href = "http://localhost/purificadorVista/public_html/vendedor.php";
                            closeDialog();
                         }
                         
                     },
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
                closeDialog();
            }
            /////////fin de eliminar vendedor
            ///////////modificar vendedor
            
            function jsonModificarVendedor(nomb,ape,tel){
                return JSON.stringify({
                    "nombres":   nomb,
                    "apellidos": ape,
                    "telefono":  tel
                    });
            }
            
            function modVendedor(){
                var nom = document.getElementsByName("txtmodnombres")[0].value;
                var ape = document.getElementsByName("txtmodapellidos")[0].value;
                var tel = document.getElementsByName("txtmodtelefono")[0].value;
                //alert(incog);
                //alert(jsonModificarVendedor(nom,ape,tel));
               jQuery.ajax({
                     type: "PUT",
                     url: servidor+"vendedor/"+idVen,
                     dataType: "json",
		     data: jsonModificarVendedor(nom,ape,tel),
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                             //Mensaje de guardado
                             location.href = "http://localhost/purificadorVista/public_html/vendedor.php";
                             closeDialog();
                             //$('#tablaRef').dataTable().fnDestroy();
                             
                             //dialMosRef(incog);
                             
                         }
                     },
                     
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
               
           }
            //////////fin de modificar vendedor
            //
            ////Registrar Vendedores
            function jsonRegistroVendedor(ced,nom,ape,tel){
                
                return JSON.stringify({
                    "cedula":ced,
                    "nombres":nom,
                    "apellidos":ape,
                    "telefono":tel
                });
                
            }
            
            function limpiarcampos(){
                document.getElementsByName("txtcedulavendedor")[0].value = "";
                document.getElementsByName("txtvendedor")[0].value = "";
                document.getElementsByName("txtvendedorapellido")[0].value = "";
                document.getElementsByName("txtvendedortelefono")[0].value = "";
            }
            
            function registrarVendedor() {
                
                var ced = document.getElementsByName("txtcedulavendedor")[0].value;
                var nom = document.getElementsByName("txtvendedor")[0].value;
                var ape = document.getElementsByName("txtvendedorapellido")[0].value;
                var tel = document.getElementsByName("txtvendedortelefono")[0].value;
                jQuery.ajax({
                   
                     type: "POST",
                     url: servidor+"vendedor",
                     dataType: "json",
		     data: jsonRegistroVendedor(ced,nom,ape,tel),
                     
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                            alert("Se ha registrado el nuevo vendedor");
                             limpiarcampos();
                         }
                     },
                     error: function (jqXHR, status) {
                         alert("error vendedor");
                     }
                });
            }
            //////////fin de Registrar vendedores
            function mostrarvendedores(){
                openDialog(3);
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

        <div id="div_formularios4">
            
            <div class="rectangle"><h2> Vendendores</h2></div> 
            <div class="triangle-l"></div>
            
            <form class="contact_form frmInv1" enctype="multipart/form-data" name="frm_ven" action="javascript:registrarVendedor();" method="post">
                <div id="div_"><br/><br/><br/>
                        <div class="div_form">
                            <label class="label1">Cedula</label><br /> <br /> 
                            <input name="txtcedulavendedor" type="number"   required />
                        </div>

                        <div class="div_form">
                            <label class="">Nombres</label><br /> <br /> 
                            <input name="txtvendedor" type="text"   required />
                        </div>
                        
                        <div class="div_form">
                            <label class="">Apellidos</label><br /> <br /> 
                            <input name="txtvendedorapellido" type="text"   required />
                        </div>
                        
                        <div class="div_form">
                            <label>Telefono</label><br /> <br /> 
                            <input name="txtvendedortelefono" type="number"   required />
                        </div>
                    
                        
                    <button id="btnRegistrarInv31" onclick="javascript:function(){document.frm_reg.submit();}" >Registrar</button>
                       
                </div>
            </form>
            
            <button id="btnRegistrarInv32" onclick="javascript:mostrarvendedores();" >Lista Vendedores</button>
                    
        </div>
            
        
        
    </article>
        
        <aside id="msg-popup" class="avgrund-popup1" style="top: 32%;">
            <form class="contact_form" method="post" name="formcliente" action="javascript:modVendedor();">
                <h2 style="color:#759ABE; background-color: transparent; position: relative; top: 5px;" id="lblTitulo">Modificar Vendedores</h2>
                <br/><br/>
                <label style="width: 300px;">Nombres</label><br /> <br />
                <input name="txtmodnombres" type="text"    />
                
                <label style="width: 300px;">Apellidos</label><br /> <br /><br/>
                <input name="txtmodapellidos" type="text"    />
                
                <label style="width: 300px;">Telefono</label><br /> <br /><br/>
                <input name="txtmodtelefono" type="tel"   />
                
                <!--<button id="btnCategoria" onclick="javascript:" >Modificar</button>-->
                 <button style="position: relative; left: 10px; top: 60px;" id="btnRegistrarInv" onclick="javascript:function(){document.formcliente.submit();}" >Actualizar</button>
                
                <!--<input type="submit" value="Registrar"/>-->
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