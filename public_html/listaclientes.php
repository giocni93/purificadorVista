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
            var idCon;
            var incog;
            var dataCli;
            var idCli;
            var idRef;
            var dataRef;
            var dataCon;
            var dataCat;
            var idCat;
            var dataTipo;
            var idTipo;

            function initTabla(){
                //CONVERTIMOS NUESTRO LISTADO DE LA FORMA DEL JQUERY.DATATABLES- PASAMOS EL ID DE LA TABLA
                $('#tablaCli').dataTable({
                    "sPaginationType": "full_numbers" //DAMOS FORMATO A LA PAGINACION(NUMEROS)
                });
            }
            
            function initTablaRef(){
                $('#tablaRef').dataTable({
                    "sPaginationType": "full_numbers" //DAMOS FORMATO A LA PAGINACION(NUMEROS)
                });
            }
            
            function initTablaCon(){
                $('#tablaCon').dataTable({
                    "sPaginationType": "full_numbers" //DAMOS FORMATO A LA PAGINACION(NUMEROS)
                });
            }
            
            function initTablaCat(){
                //CONVERTIMOS NUESTRO LISTADO DE LA FORMA DEL JQUERY.DATATABLES- PASAMOS EL ID DE LA TABLA
                $('#tablaCat').dataTable({
                    "sPaginationType": "full_numbers" //DAMOS FORMATO A LA PAGINACION(NUMEROS)
                });
            }
            
            function initTablaTipo(){
                //CONVERTIMOS NUESTRO LISTADO DE LA FORMA DEL JQUERY.DATATABLES- PASAMOS EL ID DE LA TABLA
                $('#tablaTipo').dataTable({
                    "sPaginationType": "full_numbers" //DAMOS FORMATO A LA PAGINACION(NUMEROS)
                });
            }

            function init(){
                listaCli();
                listaRefe(null);
            }
            
            
            
           
            ///////lista Clientes
            
            function jsonRegistroCliente(nomb,apel,dir,tel,email){
                return JSON.stringify({
                    "nombre": nomb,
                    "apellido": apel,
                    "direccion_oficina": dir,
                    "telefono": tel,
                    "email": email
                    });
            }
            
            function modCliente(){
                var nom = document.getElementsByName("txtmodnombres")[0].value;
                var ape = document.getElementsByName("txtmodapellidos")[0].value;
                var dir = document.getElementsByName("txtmoddireccion")[0].value;
                var tel = document.getElementsByName("txtmodtelefono")[0].value;
                var ema = document.getElementsByName("txtmodemail")[0].value;
               jQuery.ajax({
                     type: "PUT",
                     url: servidor+"modificarcliente/"+idCli,
                     dataType: "json",
		     data: jsonRegistroCliente(nom,ape,dir,tel,ema),
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                             //Mensaje de guardado
                             
                             $('#tablaCli').dataTable().fnDestroy();
                             
                             listaCli();
                             closeDialog();
                         }
                     },
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
               
           }
            
            function dialoModCli(i){
                document.getElementsByName("txtmodnombres")[0].value = dataCli[i].nombre;
                document.getElementsByName("txtmodapellidos")[0].value = dataCli[i].apellido;
                document.getElementsByName("txtmoddireccion")[0].value = dataCli[i].direccion_oficina;
                document.getElementsByName("txtmodtelefono")[0].value = dataCli[i].telefono;
                document.getElementsByName("txtmodemail")[0].value = dataCli[i].email;
                idCli = dataCli[i].cedula;
                
                openDialog(0);
                
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
                                 <td><a onclick='javascript:dialoModCli("+i+");' class='btnModi'>Modificar</a></td>\n\
                                 <td><a onclick='javascript:dialMosRef("+data[i].cedula+");' style='text-align: center; margin-left: 25px;' class='btnModi'>Ver </a></td>\n\\n\\n\
                                 <td><a onclick='javascript:dialoModCon("+data[i].cedula+")' style='text-align: center; margin-left: 25px;' class='btnModi'>Ver </a></td>\n\n\
                                 </tr>";
                             }
                         }
                         initTabla();
                     },
                     error: function (jqXHR, status) {
                         alert("erggror");
                     }
                });
            }
            /////////lista tabla Referencia
            
            
            function eliminarRef() {
                //alert(idRef);
                jQuery.ajax({
                     type: "DELETE",
                     url: servidor+"referencia/"+idRef,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                             //Mensaje de guardado
                             
                             //$('#tablaInv').dataTable().fnDestroy();
                             //listaRefe();
                             closeDialog();
                         }
                         
                     },
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
                closeDialog();
            }
            
            function jsonRegistroReferencia(nomb,tel){
                return JSON.stringify({
                    "nombre": nomb,
                    "telefono": tel
                    });
            }
            
            function modReferencia(){
                var nom = document.getElementsByName("txtmodnombresref")[0].value;
                var tel = document.getElementsByName("txtmodtelefonoref")[0].value;
                //alert(incog);
               jQuery.ajax({
                     type: "PUT",
                     url: servidor+"referencia/"+idRef,
                     dataType: "json",
		     data: jsonRegistroReferencia(nom,tel),
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                             //Mensaje de guardado
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
            
            function dialoModificarReferencia(i){
               document.getElementsByName("txtmodnombresref")[0].value = dataRef[i].nombre;
               document.getElementsByName("txtmodtelefonoref")[0].value = dataRef[i].telefono;
               idRef = dataRef[i].id;
               closeDialog();
               openDialog(6);
               
            }
            
            function diaEliRef(i){
                idRef = dataRef[i].id;
                document.getElementById("elinombreref").innerHTML = dataRef[i].nombre;
                closeDialog();
                openDialog(8); 
            }
            
            function dialMosRef(cedula){
                $('#tablaRef').dataTable().fnDestroy();
                listaRefe(cedula);
                openDialog(3); 
            }
            
            function listaRefe(cedula) {
                incog = cedula;
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"referencia/"+cedula,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         var tb = document.getElementById("tbody_tablaRef");
                         tb.innerHTML = "";
                         if(data != null){
                             dataRef = data;
                             for(var i = 0; i < data.length ; i++){
                                 data[i].id;
                                 tb.innerHTML += "<tr>\n\
                                 <td>"+data[i].nombre+"</td>\n\
                                 <td>"+data[i].telefono+"</td>\n\
                                 <td><a onclick='javascript:dialoModificarReferencia("+i+")' class='btnModi'><img src='img/modificar.png'/></a></td>\n\
                                 <td><a onclick = 'javascript:diaEliRef("+i+")' class='btnElim'><img src='img/trashcan.png'/></a></td>\n\
                                 </tr>";
                                 
                             }
                              
                         }
                         
                        initTablaRef();
                     },
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
                
            }
            ///////Lista tabla Codeudor
            
            function eliminarCon() {
                //alert(idRef);
                jQuery.ajax({
                     type: "DELETE",
                     url: servidor+"codeudor/"+idCon,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                            closeDialog();
                         }
                         
                     },
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
                closeDialog();
            }
            
            function jsonRegistroCodeudor(nom,dir,tel,refe,refetel){
                return JSON.stringify({
                    "nombre": nom,
                    "direccion_oficina": dir,
                    "telefono": tel,
                    "referencia": refe,
                    "telefono_referencia": refetel
                    });
            }
            
            function modConyuge(){
                var nom = document.getElementsByName("txtmodcodeudor")[0].value;
                var dir = document.getElementsByName("txtmoddireccioncodeudor")[0].value;
                var tel = document.getElementsByName("txtmodtelefonocodeudor")[0].value;
                var refe = document.getElementsByName("txtmodreferenciacodeudor")[0].value;
                var refetel = document.getElementsByName("txtmodtelefonoreferenciacodeudor")[0].value;
                
                jQuery.ajax({
                     type: "PUT",
                     url: servidor+"codeudor/"+idCon,
                     dataType: "json",
		     data: jsonRegistroCodeudor(nom,dir,tel,refe,refetel),
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                             //Mensaje de guardado
                             //$('#tablaRef').dataTable().fnDestroy();
                             
                             //dialoModCon(incog);
                             var a = document.getElementById("arefe");
                             a.innerHTML = "href= 'listaclientes.html'";
                         }
                         closeDialog();
                     },
                     
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
            }
            
            function diaEliCon(i){
                idCon = dataCon[i].id;
                document.getElementById("elinombre").innerHTML = dataCon[i].nombre;
                closeDialog();
                openDialog(9); 
            }
            
            function dialoModificarCodeudor(i){
               document.getElementsByName("txtmodcodeudor")[0].value = dataCon[i].nombre;
               document.getElementsByName("txtmoddireccioncodeudor")[0].value = dataCon[i].direccion_oficina;
               document.getElementsByName("txtmodtelefonocodeudor")[0].value = dataCon[i].telefono;
               document.getElementsByName("txtmodreferenciacodeudor")[0].value = dataCon[i].referencia;
               document.getElementsByName("txtmodtelefonoreferenciacodeudor")[0].value = dataCon[i].telefono_referencia;
               idCon = dataCon[i].id;
               closeDialog();   
               openDialog(7);
            }
            
            function listaCon(cedula) {
                
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"codeudor/"+cedula,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         var tb = document.getElementById("tbody_tablaCon");
                         tb.innerHTML = "";
                         if(data != null){
                             dataCon = data;
                             for(var i = 0; i < data.length ; i++){
                                 tb.innerHTML += "<tr>\n\
                                 <td>"+data[i].cedula+"</td>\n\
                                 <td>"+data[i].nombre+"</td>\n\\n\
                                 <td>"+data[i].direccion_oficina+"</td>\n\\n\
                                 <td>"+data[i].telefono+"</td>\n\\n\
                                 <td>"+data[i].referencia+"</td>\n\\n\
                                 <td>"+data[i].telefono_referencia+"</td>\n\
                                 <td><a style='position:relative; top:0 ;left: 0; width: 20px;' onclick='javascript:dialoModificarCodeudor("+i+")' class='btnModi'><img src='./img/modificar.png' style=' width: 20px; height: 20px;'/></a></td>\n\
                                 <td><a style='position:relative; top:0 ;left: 0; width: 20px;' onclick='javascript:diaEliCon("+i+")' class='btnElim'><img style=' width: 20px; height: 20px;' src='./img/trashcan.png'/></a></td>\n\
                                 </tr>";
                                 
                             }
                              
                         }
                         
                        initTablaCon();
                     },
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
                
            }
            
            function dialoModCon(cedula){
                $('#tablaCon').dataTable().fnDestroy();
                listaCon(cedula);
                openDialog(5);
            }
            
            ////////////////////

            function listaCategorias() {
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"consultarcliente",
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         var cbox = document.getElementsByName("cboxCategoria")[0];
                         var cbox2 = document.getElementsByName("cboxCategoria2")[0];
                         var tb = document.getElementById("tbody_tablaCat");
                         tb.innerHTML = "";
                         if(data != null){
                             dataCat = data;
                             cbox.innerHTML = "";
                             cbox2.innerHTML = "";
                             for(var i = 0; i < data.length ; i++){
                                 cbox.innerHTML += "<option value = '"+data[i].Id+"' >"+data[i].Nombre+"</option>";
                                 cbox2.innerHTML += "<option value = '"+data[i].Id+"' >"+data[i].Nombre+"</option>";
                                 
                                 tb.innerHTML += "<tr>\n\
                                 <td>"+data[i].Id+"</td>\n\
                                 <td>"+data[i].Nombre+"</td>\n\
                                 <td><a onclick='javascript:openDialog(0);' class='btnModi'>Modificar</a></td>\n\
                                 <td><a class='btnElim'>Eliminar</a></td>\n\
                                 </tr>";
                             }
                             
                             initTablaCat();
                         }
                     },
                     error: function (jqXHR, status) {
                         alert("erggror");
                     }
                });
            }
            
            function openDialog(ban) {
                if(ban == 0){
                    Avgrund.show( "#msg-popup" );
                }
                if(ban == 1){
                    Avgrund.show( "#categoria-popup" );
                }
                if(ban == 2){
                    Avgrund.show( "#modiInv-popup" );
                }
                if(ban == 3){
                    Avgrund.show( "#msg-popup1" );
                }
                if(ban == 4){
                    Avgrund.show( "#msg-popup2" );
                }
                if(ban == 5){
                    Avgrund.show( "#msg-popup3" );
                }
                if(ban == 6){
                    Avgrund.show( "#msg-popup4" );
                }
                if(ban == 7){
                    Avgrund.show( "#msg-popup5" );
                }
                if(ban == 8){
                    Avgrund.show( "#msg-popup6" );
                }
                if(ban == 9){
                    Avgrund.show( "#msg-popup7" );
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
                  <div class="link_title activo">
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
            <div class="rectangle"><h2>Gestion cliente</h2></div> 
            <div class="triangle-l"></div>

                <!--<button onclick="javascript:openDialog();">Open Avgrund</button>-->
                <form class="contact_form" name="frm_inv" action="" method="post">
                    <div id="div_"><br/>
                        
                        <div id="div_tablaInv3" style="width: 100%; position: relative; left: -65px; top: -50px;" >
                                <table class="display" id="tablaCli">
					<thead>
                                            <tr>
                                                <th>Cedula</th>
                                                <th>Nombre</th>
                                                <th>Apellido</th>
                                                <th>Direccion</th>
                                                <th>Telefono</th>
                                                <th style="width: 70px;"><a onclick="openDialog(0)"></a></th>
                                                <th onclick="javascript:openDialog(3)">Referencias</th>
                                                <th>Codeudores</th>
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
                                                <th></th>
                                            </tr>
					</tfoot>
					<tbody id="tbody_tablaCli">
                                            
					</tbody>
				</table>
                        </div>
                        
                    </div>
                    
                </form>
                
        </div>
        
        </article>
        
        <!-- modificar cliente -->
        <aside id="msg-popup" class="avgrund-popup1" style="top: 32%;">
            <form class="contact_form" method="post" name="formcliente" action="javascript:modCliente();">
                <label style="width: 300px;">Nombres</label><br /> <br />
                <input name="txtmodnombres" type="text"  placeholder="Purificador"  />
                
                <label style="width: 300px;">Apellidos</label><br /> <br /><br/>
                <input name="txtmodapellidos" type="text"  placeholder="10"  />
                
                <label style="width: 300px;">Direccion</label><br /> <br /><br/>
                <input name="txtmoddireccion" type="text"  placeholder="10" />
                
                <label style="width: 300px;">Telefono</label><br /> <br /><br/>
                <input name="txtmodtelefono" type="tel"  placeholder="10" />
                
                <label style="width: 300px;">Email</label><br /> <br /><br/>
                <input name="txtmodemail" type="email"  placeholder="10" />
                
                <!--<button id="btnCategoria" onclick="javascript:" >Modificar</button>-->
                 <button style="position: relative; left: -90px; top: -5px;" id="btnRegistrarInv" onclick="javascript:function(){document.formcliente.submit();}" >Actualizar</button>
                
                <!--<input type="submit" value="Registrar"/>-->
            </form>
        </aside>
            
        <aside id="categoria-popup" class="avgrund-popup">

            <div id="div_categoria">
                <form class="contact_form" name="frmCat" action="javascript:registrarCat();" method="post">
                    <br/> <br/>
                    <div class="div_cat">
                        <label style="width: 300px;">Nombre categoria</label><br /><br /> 
                        <input type="text" name="txtNombreCat"  placeholder="Vitrio" required />
                        <br/>
                        <input name="btnCategoria" type="submit" value="Registrar" />
                        <br/>
                        <input name="btnCancelarCat" type="button" onclick="javascript:cancelarCat()" value="Cancelar" style="visibility: hidden;" />
                   
                    </div>
                </form>
                
                <div class="div_tabla">
                    <table class="display" id="tablaCat">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nombre</th>
                                <th></th>
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
                        <tbody id="tbody_tablaCat">

                        </tbody>
                    </table>
                </div>
            </div>
            
            <div id="div_tipo">
                <form class="contact_form" name="frmTipo" action="javascript:registrarTipo();" method="post">
                    <br/> <br/>
                    <div class="div_tip">
                        <label>Categoria</label><br /> <br /> 
                        <select name="cboxCategoria2">
                        </select>
                        <br />
                        <label style="width: 300px;">Nombre tipo</label><br /><br /> 
                        <input name="txtNombreTipo" type="text"  placeholder="Vitrio" required />
                        <br/>
                        <input name="btnTipo" type="submit" value="Registrar" />
                        <br/>
                        <input name="btnCancelarTipo" type="button" onclick="javascript:cancelarTipo()" value="Cancelar" style="visibility: hidden;" />
                   
                    </div>
                </form>
                <div class="div_tablaTipo">
                    <table class="display" id="tablaTipo">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Categoria</th>
                                <th></th>
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
                            </tr>
                        </tfoot>
                        <tbody id="tbody_tablaTipo">
                        
                        </tbody>
                    </table>
                    <input type="hidden" name="oculto"/>
                </div>
            </div>

        </aside>
        <!--Tabla Referencia-->
        
        <aside id="msg-popup1" class="avgrund-popup2">
            
            <form class="contact_form">
                    
                    <div id="div_3"> 
                        <h2 style="color:#759ABE; background-color: transparent; position: relative; top: -30px;" id="lblTitulo">Lista de referencias</h2>
                        <div id="div_tablaInv2" style=" position: relative; top:-30px;  border-top:1px solid #aaa; padding-top: 0;">
                            
                            <table class="display" id="tablaRef">
                                <thead>
                                    <tr>
                                        <th>Nombres Referencia</th>
                                        <th>Telefono</th>
                                        <th style="width: 70px;">Modificar</th>
                                        <th>Eliminar</th>
                                        
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
                                <tbody id="tbody_tablaRef">
                                            
				</tbody>
                            </table>
                            
                        </div>
                        
                    </div>
                    
                </form>    
        </aside>
        
        <!-- modificar referencia -->
        <aside id="msg-popup4" class="avgrund-popup4" style="left: 52%; height: 180px;">
            <form class="contact_form" name="form_mod" method="post"  action="javascript:modReferencia();">
                <label style="width: 300px;">Nombres</label><br /> <br />
                <input name="txtmodnombresref" type="text"  placeholder="Purificador"  />
                
                <label style="width: 300px;">Telefono</label><br /> <br /><br/>
                <input name="txtmodtelefonoref" type="text"  placeholder="10"  />
                <a id="arefe"></a>
                <!--<input type="submit" value="Registrar"/>-->
                 <button style="position: relative; left: -90px; top: -5px;" id="btnRegistrarInv2" onclick="javascript:function(){document.form_mod.submit();}" >Actualizar</button>
            </form>
        </aside>
        
        <!--Tabla codeudor --->
        
        <aside id="msg-popup3" class="avgrund-popup3">
            
            <form class="">
                    
                    <div id="div_3"> 
                        <h2 style="color:#759ABE; background-color: transparent; position: relative; top: -30px;" id="lblTitulo">Codeudores</h2>
                        <div id="div_tablaInv2" style=" width: 90%; position: relative; top:-30px;  border-top:1px solid #aaa; padding-top: 0;">
                            
                            <table class="display" id="tablaCon">
                                <thead>
                                    <tr>
                                        <th>Cedula</th>
                                        <th>Codeudor</th>
                                        <th>Direccion</th>
                                        <th>telefono</th>
                                        <th>Referencia</th>
                                        <th>telefono Referencia</th>
                                        <th style="width: 160px;">Modificar</th>
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
        
        <!--  modificar codeudor -->
        <aside id="msg-popup5" class="avgrund-popup1" style="top: 32%;">
            <form class="contact_form" name="form_modcod" method="post"  action="javascript:modConyuge();">
                <label style="width: 300px;">Codeudor</label><br /> <br />
                <input name="txtmodcodeudor" type="text"  placeholder="Purificador"  />
                
                <label style="width: 300px;">Direccion</label><br /> <br /><br/>
                <input name="txtmoddireccioncodeudor" type="text"  placeholder="10"  />
                
                <label style="width: 300px;">Telefono</label><br /> <br /><br/>
                <input name="txtmodtelefonocodeudor" type="tel"  placeholder="10" />
                
                <label style="width: 300px;">Referencia</label><br /> <br /><br/>
                <input name="txtmodreferenciacodeudor" type="text"  placeholder="10" />
                
                <label style="width: 300px;">Telefono Referencia</label><br /> <br /><br/>
                <input name="txtmodtelefonoreferenciacodeudor" type="tel"  placeholder="10" />
                
                <button style="position: relative; left: -90px; top: -5px;" id="btnRegistrarInv" onclick="javascript:function(){document.form_modcod.submit();}" >Actualizar</button>
                <!--<input type="submit" value="Registrar"/>-->
            </form>
        </aside>
        
        <div class="avgrund-cover"></div>
        <script type="text/javascript" src="js/avgrund.js"></script>
        <!--- eliminar referencia -->
        
        <aside id="msg-popup6" class="avgrund-popup">
            <label class="lblEliminar">¿ Deseas eliminar la referencia seleccionada ?</label>
            <h3 style="color:#555555; margin-left: 55px; margin-right: auto" id="elinombreref"></h3><br/>
            <label id="lblCancelarInv" class="lblEliminar"></label>
            <a onclick="javascript:eliminarRef();" class="aInvElimi">Aceptar</a> <br/>
            <a onclick="javascript:closeDialog();" id="btnCan1" class="aInvElimi">Cancelar</a>
        </aside>
        
        <!-- eliminar codeudor -->
        
        <aside id="msg-popup7" class="avgrund-popup">
            <label class="lblEliminar">¿ Deseas eliminar el codeudor seleccionado ?</label>
            <h3 style="color:#555555; margin-left: 55px; margin-right: auto" id="elinombre"></h3><br/>
            <label id="lblCancelarInv" class="lblEliminar"></label>
            <a onclick="javascript:eliminarCon();" class="aInvElimi">Aceptar</a> <br/>
            <a onclick="javascript:closeDialog();" id="btnCan1" class="aInvElimi">Cancelar</a>
        </aside>
        
    </body>
</html>
<?php
    }else{
?>
    <script>location.href = "index.php";</script>
<?php
    }
?>