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
            var dataPlan;
            var idCli;
            var idDetalle;
            var idOrd;
            var numCuota;
            var val;
            var saldoTotalPen;
            
            function getGET()
            {
                // capturamos la url
                var loc = document.location.href;
                var get = null;
                
                // si existe el interrogante
                if(loc.indexOf('?')>0)
                {
                    // cogemos la parte de la url que hay despues del interrogante
                    var getString = loc.split('?')[1];
                    // obtenemos un array con cada clave=valor
                    var GET = getString.split('&');
                    get = {};

                    // recorremos todo el array de valores
                    for(var i = 0, l = GET.length; i < l; i++){
                        var tmp = GET[i].split('=');
                        get[tmp[0]] = unescape(decodeURI(tmp[1]));
                    }
                }
                return get;
            }

            function initTabla(){
                //CONVERTIMOS NUESTRO LISTADO DE LA FORMA DEL JQUERY.DATATABLES- PASAMOS EL ID DE LA TABLA
                $('#tablaCli').dataTable({
                    "sPaginationType": "full_numbers" //DAMOS FORMATO A LA PAGINACION(NUMEROS)
                });
            }
            
            function initTablaPlan(){
                //CONVERTIMOS NUESTRO LISTADO DE LA FORMA DEL JQUERY.DATATABLES- PASAMOS EL ID DE LA TABLA
                $('#tablaPlan').dataTable({
                    "sPaginationType": "full_numbers",
                    "bLengthChange": false,
                    "bInfo" : false,
                    "bFilter":false
                });
            }
            
            function initTablaHis(){
                //CONVERTIMOS NUESTRO LISTADO DE LA FORMA DEL JQUERY.DATATABLES- PASAMOS EL ID DE LA TABLA
                $('#tablaHis').dataTable({
                    "sPaginationType": "full_numbers" //DAMOS FORMATO A LA PAGINACION(NUMEROS)
                });
            }
            
            function initTablaOrd(){
                //CONVERTIMOS NUESTRO LISTADO DE LA FORMA DEL JQUERY.DATATABLES- PASAMOS EL ID DE LA TABLA
                $('#tablaOrd').dataTable({
                    "sPaginationType": "full_numbers",         
                    "bLengthChange": false,
                    "bInfo" : false,
                    "aaSorting": []
                });
            }

            function init(){
                document.getElementById("divDetallePlan").style['visibility'] = "hidden";
                document.getElementById("div_tablaHis2").style['visibility'] = "hidden";
                document.getElementById("btnCanPlan").style['visibility'] = "hidden";
                
                initTablaPlan();
                listaCli();
                initTablaHis();
                initTablaOrd();
                
                var id = getGET();
                if(id != null){
                    detalleOrd(id['id']);
                }
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
            
            function jsonPlan() {
                return JSON.stringify({
                    "idCliente": idCli,
                    "idOrd" : idOrd,
                    "numCuota" : numCuota,
                    "val" : formato_numero(val,0,".",","),
                    "valorCuota" : val
                    });
            }

            function mostrarOrd(i){
                idCli = dataCli[i].cedula;
                document.getElementById("lblNombreCli").innerHTML = dataCli[i].nombre + " " + dataCli[i].apellido;
                $('#tablaOrd').dataTable().fnDestroy();
                listaOrd();
                openDialog(1);
            }
            
            function detalleOrd(id){
                //alert(dataHis[i].Observacion);
                document.getElementById("divDetallePlan").style['visibility'] = "visible";
                document.getElementById("div_tablaHis2").style['visibility'] = "visible";
                document.getElementById("btnCanPlan").style['visibility'] = "visible";
                document.getElementById("lblNombre").innerHTML = document.getElementById("lblNombreCli").innerHTML;
                document.getElementById("lblOrden").innerHTML = "Orden de pedido # "+id;
                idOrd = id;
                $('#tablaPlan').dataTable().fnDestroy();
                listaPlan(id);
                closeDialog();
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
                                 <td><a onclick='javascript:mostrarOrd("+i+");' class='btnModi'>Ver</a></td>\n\
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
            
            function listaOrd() {
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"orden_pedido/cliente/"+idCli,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         var tb = document.getElementById("tbody_tablaOrd");
                         tb.innerHTML = "";
                         if(data != null){
                             for(var i = 0; i < data.length ; i++){
                                 tb.innerHTML += "<tr>\n\
                                 <td>"+data[i].Id+"</td>\n\
                                 <td>"+data[i].Fecha+"</td>\n\
                                 <td><a onclick='javascript:detalleOrd("+data[i].Id+");' class='btnModi'>Detalle</a></td>\n\
                                 </tr>";
                             }
                         }
                         initTablaOrd();
                     },
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
            }
            
            function cancelarPlan(){
                document.getElementById("divDetallePlan").style['visibility'] = "hidden";
                document.getElementById("div_tablaHis2").style['visibility'] = "hidden";
                document.getElementById("btnCanPlan").style['visibility'] = "hidden";
                document.getElementById("lblNombre").innerHTML = "";
                document.getElementById("lblOrden").innerHTML = "";
            }
            
            function actualizarDialogOrd(i){
                //alert(id);
                idDetalle = dataPlan[i].IdDetalle;
                idCli = dataPlan[i].Cedula;
                numCuota = i+1;
                val = dataPlan[i].ValorCuota;
                $('body').scrollTop(0);
                openDialog(2);
            }
            
            function actualizarOrd(){
                
                val = document.getElementById("txtValorCuota").value;

                if(parseInt(val) > saldoTotalPen){
                    alert("El valor ingresado supera al saldo pendiente");
                }else{
                
                    jQuery.ajax({
                         type: "PUT",
                         url: servidor+"detalle_planpago/"+idDetalle,
                         dataType: "json",
                         data: jsonPlan(),
                         success: function (data, status, jqXHR) {
                             if(data == true){
                                 alert("Guardado correctamente");
                             }
                             $('#tablaPlan').dataTable().fnDestroy();
                            listaPlan(idOrd);
                            closeDialog();
                         },
                         error: function (jqXHR, status) {
                             alert("error");
                         }
                    });
                }
            }
            
            function listaPlan(id) {
                
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"planpago/idOrden/"+id,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         var tb = document.getElementById("tbody_tablaPlan");
                         tb.innerHTML = "";
                         if(data != null){
                             document.getElementById("lblDatos").innerHTML = "Plan de pagos";
                             var sumaPagado = 0;
                             var contPagado = 0;
                             dataPlan = data;
                             for(var i = 0; i < data.length ; i++){
                                 var fp = "--";
                                 var estado = "<p style='color: red;'>Pendiente</p>";
                                 if(data[i].FechaPagado != null){
                                     fp = data[i].FechaPagado;
                                 }
                                 
                                 var accion = "<a onclick='javascript:actualizarDialogOrd("+i+");' class='btnModi'>Pagar</a>";
                                 if(data[i].Estado == 1){
                                     estado = "<p style='color: green;'>Pagado</p>";
                                     sumaPagado += parseFloat(data[i].ValorPagado);
                                     contPagado += 1;
                                     accion = "--";
                                 }
                                 
                                 var c = i +1;
                                 tb.innerHTML += "<tr>\n\
                                 <td>"+c+"</td>\n\
                                 <td>"+data[i].FechaVencimiento+"</td>\n\
                                 <td>"+formato_numero(data[i].ValorCuota,2,".",",")+"</td>\n\
                                 <td>"+fp+"</td>\n\
                                 <td>"+formato_numero(data[i].ValorPagado,2,".",",")+"</td>\n\
                                 <td>"+estado+"</td>\n\
                                 <td>"+accion+"</td>\n\
                                 </tr>";
                             }
                             
                             var saldoPendiente = data[0].Monto - sumaPagado;
                             var estadoG = "Pendiente";
                             var color = "red";
                             saldoTotalPen = saldoPendiente;
                             if(saldoPendiente == 0){
                                 estadoG = "Pagado";
                                 color = "green";
                             }
                             
                             document.getElementById("lblTipo").innerHTML = data[0].Tipo;
                             document.getElementById("lblMonto").innerHTML = "$"+formato_numero(data[0].Monto,2,".",",");
                             document.getElementById("lblFechaCredito").innerHTML = data[0].FechaCredito;
                             document.getElementById("lblCuotas").innerHTML = data[0].NumeroCuota;
                             document.getElementById("lblSaldoP").innerHTML = "$"+formato_numero(saldoPendiente,2,".",",");
                             document.getElementById("lblEstado").innerHTML = estadoG;
                             document.getElementById("lblNombre").innerHTML = data[0].NombreCliente;
                             document.getElementById("lblEstado").style['color'] = color;
                             
                             document.getElementById("txtValorCuota").value = "";
                             
                         }
                         else{
                             document.getElementById("lblDatos").innerHTML = "No se encontraron datos.";
                             cancelarPlan();
                         }
                         initTablaPlan();
                     },
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
            }
            
            function buscarMan(){
                var idM = document.getElementById("txtBuscarMan").value;
                detalleOrd(idM);
            }

            function openDialog(ban) {
                if(ban == 2){
                    Avgrund.show( "#msg-popup" );
                }
                if(ban == 0){
                    Avgrund.show( "#His-popup" );
                }
                if(ban == 1){
                    Avgrund.show("#orden-popup");
                }
                 if(ban == 4){
                    document.getElementById("divInfoMan").innerHTML = "";
                    document.getElementById("txtBuscarMan").value = "";
                    Avgrund.show("#buscarMan-popup");
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
                  <div class="link_title activo">
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
            <div class="rectangle"><h2>Plan de pagos</h2></div> 
            <div class="triangle-l"></div>
            <button id="btnBuscarMan" onclick="javascript:openDialog(4);">Busqueda especifica</button>
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
                <h2 id="lblDatos" style="color:#555555; width: 400px;">Plan de pagos</h2>
                <button onclick="javascript:cancelarPlan()" id="btnCanPlan" class="aInvElimi">Cancelar</button>
                <h4 style="margin-top: 20px; color:#759ABE" id="lblNombre"></h4>
                <p style="margin-top: 0px; color:#759ABE" id="lblOrden"></p>
                <div id="divDetallePlan">
                    <div class="div_form2">
                        <h3 style="color:#555555;">Tipo de pago</h3>
                        <label id="lblTipo"></label>
                    </div>
                    <div class="div_form2">
                        <h3 style="color:#555555;">Monto</h3>
                        <label id="lblMonto"></label>
                    </div>
                    <div class="div_form2">
                        <h3 style="color:#555555;">Fecha credito</h3>
                        <label id="lblFechaCredito"></label>
                    </div>
                    <div class="div_form2">
                        <h3 style="color:#555555;">Numero de cuotas</h3>
                        <label id="lblCuotas"></label>
                    </div>
                    <div class="div_form2">
                        <h3 style="color:#555555;">Saldo pendiente</h3>
                        <label id="lblSaldoP"></label>
                    </div>
                    <div class="div_form2">
                        <h3 style="color:#555555;">Estado</h3>
                        <label id="lblEstado"></label>
                    </div>
                </div>
                <div id="div_tablaHis2" style="margin-top: -30px;">
                    <form class="contact_form" name="frmHis2" action="" method="post">
                        <table class="display" id="tablaPlan">
                            <thead>
                                <tr>
                                    <th>Cuota No</th>
                                    <th>Fecha vencimiento</th>
                                    <th>Valor cuota</th>
                                    <th>Fecha de pago</th>
                                    <th>Valor pagado</th>
                                    <th>Estado</th>
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
                            <tbody id="tbody_tablaPlan">

                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
            
        </div>
            
            
        
        </article>    
        
        <aside id="msg-popup" class="avgrund-popup">
             <div id="div_observacion">
                <h2 style="color:#759ABE">Realizar pago</h2>
                <br />
                <form class="contact_form" name="frmPago" action="javascript:actualizarOrd();" method="post">
                    <label style="width: 150px;" class="lblEliminar">Valor de la cuota</label>
                    <br />
                    <br />
                    <input style="margin-left: 15px;" type="number" id="txtValorCuota" name="txtValorCuota"  placeholder="" required />
                    
                    <button style="width: 100px; position: relative; top: -8px; left: -75px;">Aceptar</button>
                </form>
            </div>
            
            
        </aside>
        
        <aside id="His-popup" class="avgrund-popup">
            <form class="contact_form" name="frmHis" action="javascript:registrarHis();" method="post">
                <label style="width: 300px;">Titulo</label><br /><br /> 
                <input type="text" name="txtTitulo"  placeholder="" required />
                <br/>
                <label style="width: 300px;">Descripcion</label><br /><br /> 
                <textarea name="txtObservacion" required></textarea>
                <br/><br/>
                <button id="btnCategoria" name="btnHistorial" onclick="javascript:function(){document.frmCat.submit();}" >Registrar</button>
                <br/>
            </form>
        </aside>
        
        <aside id="orden-popup" class="avgrund-popup">
            <div id="div_observacion">
                <h2 style="color:#759ABE" id="lblTitulo">Orden de pedidos</h2>
                <h3 style="color:#555555" id="lblNombreCli">fecha</h3>
                    <form class="contact_form" name="frmHis" action="" method="post">
                    <table class="display" id="tablaOrd">
                          <thead>
                              <tr>
                                  <th>Orden de pedido</th>
                                  <th>Fecha</th>
                                  <th></th>
                              </tr>
                          </thead>
                          <tfoot>
                              <tr>
                                  <th></th>
                                  <th></th>
                                  <th></th>
                              </tr>
                          </tfoot>
                          <tbody id="tbody_tablaOrd">

                          </tbody>
                      </table>
                      </form>
                <br/>
                <button class="cance" onclick="closeDialog()">Cerrar</button>
            </div>
        </aside>
        
        <aside id="buscarMan-popup" class="avgrund-popup">
            <div id="div_buscarMan">
                <form class="contact_form" name="frmMan" action="javascript:buscarMan();" method="post">
                    <label style="width: 400px; margin-bottom: 10px;">Orden de pedido</label><br /> <br />
                        <input name="txtNumMan" id="txtBuscarMan" type="text" required />
                    <br /> 
                    <button style="position: relative; left: -90px; top: -8px;" id="btnBusMan">Buscar</button>
                </form>
                <div id="divInfoMan"></div>
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