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

            function initTabla(){
                //CONVERTIMOS NUESTRO LISTADO DE LA FORMA DEL JQUERY.DATATABLES- PASAMOS EL ID DE LA TABLA
                $('#tablaCli').dataTable({
                    "sPaginationType": "full_numbers", //DAMOS FORMATO A LA PAGINACION(NUMEROS)
                    "aaSorting": []
                });
            }
            

            function init(){
                listaCli();
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
            
            function jsonConsultas(){
                
                return JSON.stringify({
                    "fi": document.getElementsByName('txtFi')[0].value,
                    "ff": document.getElementsByName('txtFf')[0].value
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

            function listaCli() {
                
                jQuery.ajax({
                     type: "POST",
                     url: servidor+"consultas",
                     dataType: "json",
                     data: jsonConsultas(),
                     success: function (data, status, jqXHR) {
                         var tb = document.getElementById("tbody_tablaCli");
                         tb.innerHTML = "";
                         if(data != null){
                             dataCli = data;
                             for(var i = 0; i < data.length ; i++){
                                 var accion = "plan_pagos.php";
                                 if(data[i].Tipo == "Mantenimiento"){
                                     accion = "mantenimiento.php";
                                 }
                                 tb.innerHTML += "<tr>\n\
                                 <td>"+data[i].Codigo+"</td>\n\
                                 <td>"+data[i].Fecha+"</td>\n\
                                 <td>"+data[i].Cliente+"</td>\n\
                                 <td>"+data[i].Tipo+"</td>\n\
                                 <td>$"+formato_numero(data[i].Valor,0,",",".")+"</td>\n\
                                 <td><a style='position:relative; top:0 ;left: 0; width: 20px;' href='"+accion+"?id="+data[i].Codigo+"'><img src='./img/lupa.png' style=' width: 20px; height: 20px;' /></a></td>\n\
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
                  <div class="link_title activo">
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
            <div class="rectangle"><h2>Consultas</h2></div> 
            <div class="triangle-l"></div>
            <!--<button id="btnBuscarMan" onclick="javascript:openDialog(4);">Busqueda especifica</button>-->
            
            <div id="div_filtro">
                <label>Filtro avanzado</label><br/><br/>
                <form class="contact_form">
                    <p style=" position: relative; top:-25px; left: 40px;">Fecha inicial</p>
                    <input name="txtFi" style="float:left; position: relative; top:-35px; left: 40px;" type="date" />
                    
                    <p style=" position: relative; top:-56px; left: 80px;">Fecha final</p>
                    <input name="txtFf" style="float:left; position: relative; top:-66px; left: 80px;" type="date" />
                </form>
                <button id="boton1Con" onclick="javascript:irConsulta();">Buscar</button>
                <button id="boton2Con" onclick="javascript:cancelarConsulta();">Cancelar</button>
            </div>
            
            <div id="div_tablaHis">
                <form class="contact_form" name="frmHis" action="" method="post">
                <table class="display" id="tablaCli">
                      <thead>
                          <tr>
                              <th>Codigo</th>
                              <th>Fecha</th>
                              <th>Cliente</th>
                              <th>Tipo</th>
                              <th>Valor</th>
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