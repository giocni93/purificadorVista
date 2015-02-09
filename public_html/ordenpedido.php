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
        <link rel="stylesheet" type="text/css" href="css/main2.css" />
        <link rel="stylesheet" href="css/avgrund.css" />
        <link rel="stylesheet" href="css/contacto_form.css" />
        <link rel="stylesheet" href="css/boton.css" />
        <link rel="stylesheet" type="text/css" href="css/barra_superior.css" />
        
        <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.dataTables.js"></script>
        
        <script>
            
            var idCat;
            var val;
            var inv;
            var idinv;
            var dataCli;  
            var dataRef;
            var dataCon;
            var bandera = false;
           
                
            function initTabla(){
                //CONVERTIMOS NUESTRO LISTADO DE LA FORMA DEL JQUERY.DATATABLES- PASAMOS EL ID DE LA TABLA
                $('#tablaInv').dataTable({
                    "sPaginationType": "full_numbers" //DAMOS FORMATO A LA PAGINACION(NUMEROS)
                });
            }
            
            function initTablaCat(){
                //CONVERTIMOS NUESTRO LISTADO DE LA FORMA DEL JQUERY.DATATABLES- PASAMOS EL ID DE LA TABLA
                $('#tablaCat').dataTable({
                    "sPaginationType": "full_numbers" //DAMOS FORMATO A LA PAGINACION(NUMEROS)
                });
            }
            
            function initTablaCon(){
                $('#tablaCon').dataTable({
                    "sPaginationType": "full_numbers" //DAMOS FORMATO A LA PAGINACION(NUMEROS)
                });
            }
            
            function init(){
                listaCli();
                listaCategorias();
                listaVendedores();
            }
            
            
            /////////////imprimir orden pedido
            function imprimir_orden(){
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"ordenpedido",
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                        
                         if(data != null){
                             cbox.innerHTML = "";
                             
                             for(var i = 0; i < data.length ; i++){
                                
                             }
                             //consultaTipo();
                             //initTablaCat();
                             
                         }
                     },
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
            }
            
            ////lista de categorias
            
            function listaCategorias() {
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"categoria",
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         var cbox = document.getElementsByName("cboxCategoria")[0];
                         
                         if(data != null){
                             cbox.innerHTML = "";
                             
                             for(var i = 0; i < data.length ; i++){
                                 cbox.innerHTML += "<option value = '"+data[i].Id+"' >"+data[i].Nombre+"</option>";
                                 document.getElementsByName("ocultartipo")[0].value = data[i].Nombre;
                                 
                             }
                             
                             consultaTipo();
                             
                             //initTablaCat();
                             
                         }
                     },
                     error: function (jqXHR, status) {
                         alert("error cate");
                     }
                });
            }
            /////////lista inventario
           
            function consultaInvetario(){
                var id = document.getElementsByName("cboxtiponventario")[0].value;
                //alert(val);
                inv = id;
                //alert(inv);
                listaInventario(id);
                
            }
            
            function consultavalor(){
                var id = document.getElementsByName("cboxinventario")[0].value;
                
                precio(id);
            }
            
            /////////consultar precio
            
            function precio(id){
                
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"precioinventario/"+id,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         
                         if(data != null){
                             
                            document.getElementsByName("valorsuma")[0].value = data[0].valor;
                             
                         }
                         
                     },
                     error: function (jqXHR, status) {
                         alert("error precio");
                     }
                });
            }
            
            ///////////
            
            function listaInventario(id){
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"inventario/"+id,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         var cbox = document.getElementsByName("cboxinventario")[0];
                         
                         cbox.innerHTML = "";
                         if(data != null){
                             
                             
                             precio(data[0].id);
                             for(var i = 0; i < data.length ; i++){
                                 idinv = data[i].id;    
                                 cbox.innerHTML += "<option value = '"+data[i].id+"'>"+data[i].nombre+"</option>";
                                  val = data[i].valor;
                                  document.getElementsByName("ocultarmodelo")[0].value = data[i].nombre; 
                                  
                             }
                             //consultaTipo();
                             //initTablaCat();
                         }
                         
                            //alert(id1);
                            
                     },
                     error: function (jqXHR, status) {
                         alert("error inventario");
                     }
                });
                
            }
            
            /////////lista tipo inventario
            
             function consultaTipo(){
                var id = document.getElementsByName("cboxCategoria")[0].value;
                
                listaTipoInve(id);
                
             }
          
            function listaTipoInve(id) {
                
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"tipoInventario/"+id,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         var cbox = document.getElementsByName("cboxtiponventario")[0];
                         cbox.innerHTML = "";
                         if(data != null){
                             
                             
                             for(var i = 0; i < data.length ; i++){
                                 
                                 cbox.innerHTML += "<option value = '"+data[i].Id+"' >"+data[i].Nombre+"</option>";
                                 //document.getElementsByName("valorsuma")[0].value = "";
                                 
                             }
                             consultaInvetario();
                             
                             //consultaInvetario();
                             //initTablaCat();
                         }
                         
                     },
                     error: function (jqXHR, status) {
                         alert("error tipo inventario");
                     }
                });
            }
            ///////////registrar detalles plan pago
            
             /*
            function probar(){
                var txt1 = document.getElementsByName("txtcuotas");
                    for(var i = 0; i<txt1.length; i++){
                        alert(txt1[i].value);
                    }
            }
            */
            
            ///////////////////registrar plan de pago
            
            function jsonRegistroPlanpago(formapago,val){
                //txt1 = document.getElementsByName("txtcuotas");
                if(bandera==1){
                    var cuo = 1;
                    return JSON.stringify({
                        "tipo":formapago,
                        "monto": val,
                        "numero_cuota": cuo,
                        "valorCuota" : val
                    });
                }else{
                    var cuo = document.getElementsByName("txtcuotas");
                    var v = new Array();
                        for(var i = 0; i<cuo.length; i++){
                            ///alert(cuo[i].value);
                            v.push(cuo[i].value);
                        }

                    return JSON.stringify({
                        "tipo":formapago,
                        "monto": val,
                        "numero_cuota": cuo.length,
                        "valorCuota" : v
                    });
                }
            }
            
            function borrarCampos(){
                document.getElementsByName("txtcedula")[0].value="";
                document.getElementsByName("txtnombres")[0].value="";
                document.getElementsByName("txtapellido")[0].value="";
                document.getElementsByName("txtdireccion")[0].value="";
                document.getElementsByName("txtdiroficina")[0].value="";
                document.getElementsByName("txttelefono")[0].value="";
                document.getElementsByName("txtcorreo")[0].value="";
                document.getElementsByName("txtlabor")[0].value="";
                document.getElementsByName("textconyuge")[0].value="";
                document.getElementsByName("txtnit")[0].value="";
                document.getElementsByName("txtconyugedireccion")[0].value="";
                document.getElementsByName("txtconyugetelefono")[0].value="";
                document.getElementsByName("txtreferenciaconyuge")[0].value="";
                document.getElementsByName("txtreferenciaconyugetel")[0].value="";
                document.getElementsByName("txtreferenciafamiliar")[0].value="";
                document.getElementsByName("txtreferenciatelefono")[0].value="";
                document.getElementsByName("txtreferenciafamiliar1")[0].value="";
                document.getElementsByName("txtreferenciatelefono1")[0].value="";
                document.getElementsByName("fecha_instalacion")[0].value="";
                //location.reload();
                //location.href = "http://localhost/purificadorVista/public_html/ordenpedido.php";
            }
            
            function impresion(){
                document.frm_reg.action = "http://localhost/purificadorServidor/ordeninstalacion.php";
                document.frm_reg.submit();
            }
            
            function registrarplanpago(){
                var formpago = document.getElementsByName("cboxcredito")[0].value;
                var val = document.getElementsByName("valorsuma")[0].value;
                
                //alert(jsonRegistroPlanpago(formpago,val));
                //var cuo = document.getElementsByName("numcuotas")[0].value;
                
                jQuery.ajax({
                        
                     type: "POST",
                     url: servidor+"planpago",
                     dataType: "json",
		     data: jsonRegistroPlanpago(formpago,val),
                     
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                             alert("Se ha Registrado este pedido");
                             impresion();
                             borrarCampos();
                             closeDialog(2);
                             //location.reload();
                         }
                         
                     },
                     error: function (jqXHR, status) {
                         alert("error plan");
                     }
                });
                
            }
            var vc = 0;
            
            function calcularCuotas(){
                var monto = parseFloat(document.getElementsByName("valorsuma")[0].value);
                var num = parseFloat(document.getElementsByName("numcuotas")[0].value);
                vc = monto / num ;
                document.getElementsByName("txtvalor")[0].value = vc;
               
            }
            
            
            
            ////////////registrar orden pedido
            
            function registrarordenpedido(){
                var desc = document.getElementsByName("descripcion_color")[0].value;
                var fech_inst = document.getElementsByName("fecha_instalacion")[0].value;
                var ced = document.getElementsByName("txtcedula")[0].value;
                var vend = document.getElementsByName("cboxvendedor")[0].value;
                var inve = idinv; 
                //alert(desc+" "+fech_inst+" "+ced+" "+inve);
                
                jQuery.ajax({
                   
                     type: "POST",
                     url: servidor+"orden_pedido",
                     dataType: "json",
		     data: jsonRegistroOrden(desc,fech_inst,ced,inve,vend),
                     
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                            registrarplanpago();
                         }
                     },
                     error: function (jqXHR, status) {
                         alert("error orden");
                     }
                });
                
            }
            
            function jsonRegistroOrden(desc,fech_inst,ced,inve,vend){
                
                return JSON.stringify({
                    "descripcion": desc,
                    "idcliente": ced,
                    "idinventario":inve,
                    "fechainstalacion": fech_inst,
                    "vendedor": vend
                    });
            }
            
            /////////////Conyuge JSON
            
            function registrarConyuge() {
                
                var nom = document.getElementsByName("textconyuge")[0].value;
                var nit = document.getElementsByName("txtnit")[0].value;
                var dir = document.getElementsByName("txtconyugedireccion")[0].value;
                var tel = document.getElementsByName("txtconyugetelefono")[0].value;
                var ref = document.getElementsByName("txtreferenciaconyuge")[0].value;
                var ref_tel = document.getElementsByName("txtreferenciaconyugetel")[0].value;
                var id = document.getElementsByName("txtcedula")[0].value;
                jQuery.ajax({
                   
                     type: "POST",
                     url: servidor+"codeudor",
                     dataType: "json",
		     data: jsonRegistroConyuge(nom,nit,dir,tel,ref,ref_tel,id),
                     
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                             //Mensaje de guardado
                             //alert(data.estado);
                            
                                
                         }
                     },
                     error: function (jqXHR, status) {
                         alert("error conyuge");
                     }
                });
            }
            
            function jsonRegistroConyuge(nom,nit,dir,tel,ref,ref_tel,id){
                return JSON.stringify({
                    "cedula": nit,
                    "nombre": nom,
                    "direccion_oficina": dir,
                    "telefono": tel,
                    "referencia": ref,
                    "telefono_referencia": ref_tel,
                    "id_cliente": id
                    });
            }
            
            /////////////Referencia JSON
            
            function jsonRegistroReferencia(nom,tel,id,nom1,tel1,id1){
                
                return JSON.stringify([{
                    "nombre":nom,
                    "telefono":tel,
                    "id_cliente":id
                },{
                    "nombre":nom1,
                    "telefono":tel1,
                    "id_cliente":id1
                }]);
                
            }
            
            function registrarReferencia() {
                
                var nom = document.getElementsByName("txtreferenciafamiliar")[0].value;
                var tel = document.getElementsByName("txtreferenciatelefono")[0].value;
                var id = document.getElementsByName("txtcedula")[0].value;
                var nom1 = document.getElementsByName("txtreferenciafamiliar1")[0].value;
                var tel1 = document.getElementsByName("txtreferenciatelefono1")[0].value;
                var id1 = document.getElementsByName("txtcedula")[0].value;
                jQuery.ajax({
                   
                     type: "POST",
                     url: servidor+"referencia",
                     dataType: "json",
		     data: jsonRegistroReferencia(nom,tel,id,nom1,tel1,id1),
                     
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                             //Mensaje de guardado
                             //alert(data.estado);
                            
                            
                         }
                     },
                     error: function (jqXHR, status) {
                         alert("error referencia");
                     }
                });
            }
            
            
            /////////////Conyuge Cliente
            
            function jsonRegistroCliente(ced,nomb,apel,dir,dirof,tel,email,labor){
                return JSON.stringify({
                    "cedula": ced,
                    "nombre": nomb,
                    "apellido": apel,
                    "direccion_casa": dir,
                    "direccion_oficina": dirof,
                    "telefono": tel,
                    "email": email,
                    "labor": labor
                    });
            }
            
            
            ///////////registrar cliente
            function registrarCli() {
                var ced = document.getElementsByName("txtcedula")[0].value;
                var nomb = document.getElementsByName("txtnombres")[0].value;
                var apel = document.getElementsByName("txtapellido")[0].value;
                var dir = document.getElementsByName("txtdireccion")[0].value;
                var dirof = document.getElementsByName("txtdiroficina")[0].value;
                var tel = document.getElementsByName("txttelefono")[0].value;
                var email = document.getElementsByName("txtcorreo")[0].value;
                var labor = document.getElementsByName("txtlabor")[0].value;
                //probar(txt1);
                if(bandera == false){
                    jQuery.ajax({

                         type: "POST",
                         url: servidor+"cliente",
                         dataType: "json",
                         data: jsonRegistroCliente(ced,nomb,apel,dir,dirof,tel,email,labor),

                         success: function (data, status, jqXHR) {
                             if(data.estado == 1){
                                 //Mensaje de guardado
                                 registrarReferencia();
                                 registrarConyuge();
                                 registrarordenpedido();
                                 //alert(data.estado);
                                 //bandera = false;
                             }
                         },
                         error: function (jqXHR, status) {
                             alert("error cliente");
                         }
                    });
                    alert("bandera false");
                }else{
                    alert("bandera true");
                    registrarordenpedido();
                }    
                
            } 
            
            /////////////registrar Vendedores
            
            function jsonRegistroVendedor(ced,nom,ape,tel){
                
                return JSON.stringify({
                    "cedula":ced,
                    "nombres":nom,
                    "apellidos":ape,
                    "telefono":tel
                });
                
            }
            
            function registrarVendedor() {
                
                var ced = document.getElementsByName("txtcedulaven")[0].value;
                var nom = document.getElementsByName("txtnombresven")[0].value;
                var ape = document.getElementsByName("txtapellidosven")[0].value;
                var tel = document.getElementsByName("txttelefonoven")[0].value;
                jQuery.ajax({
                   
                     type: "POST",
                     url: servidor+"vendedor",
                     dataType: "json",
		     data: jsonRegistroVendedor(ced,nom,ape,tel),
                     
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                             alert("Se ha registrado el nuevo vendedor");
                             location.href = "http://localhost/purificadorVista/public_html/ordenpedido.php";
                         }
                     },
                     error: function (jqXHR, status) {
                         alert("error vendedor");
                     }
                });
            }
            ///////////lista de vendedores
            
            function listaVendedores() {
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"vendedor",
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         var cbox = document.getElementsByName("cboxvendedor")[0];
                         
                         if(data != null){
                             cbox.innerHTML = "";
                             
                             for(var i = 0; i < data.length ; i++){
                                 cbox.innerHTML += "<option value = '"+data[i].nombres+" "+data[i].apellidos+"' >"+data[i].nombres+" "+data[i].apellidos+"</option>";
                                 
                             }
                             
                         }
                     },
                     error: function (jqXHR, status) {
                         alert("error lista vendedor");
                     }
                });
            }
            
            function mostrarclientes(){
                openDialog(1);
            }
            
            function mostrarvendedor(){
                openDialog(3);
            }
            
            ////////lista de referencia
            function listaRefe(cedula) {
                
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"referencia/"+cedula,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         
                         if(data != null){
                             dataRef = data;
                            for(var i = 0; i < data.length ; i++){
                                document.getElementsByName("txtreferenciafamiliar")[0].value  =  data[0].nombre;
                                document.getElementsByName("txtreferenciatelefono")[0].value  =  data[0].telefono; 
                                document.getElementsByName("txtreferenciafamiliar1")[0].value =  data[1].nombre;
                                document.getElementsByName("txtreferenciatelefono1")[0].value =  data[1].telefono;
                                
                            }
                              
                         }
                         
                     },
                     error: function (jqXHR, status) {
                         alert("error lista refe");
                     }
                });
                
            }
            
            ////lista clientes
            function listaCli() {
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"consultarcliente",
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         var tb = document.getElementById("tbody_tablaCon");
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
                                 <td>"+data[i].email+"</td>\n\\n\\n\
                                 <td>"+data[i].direccion_casa+"</td>\\n\
                                 <td><a onclick='javascript:enviardatos("+i+");' class='btnModi'>Ver</a></td>\n\
                                 </tr>";
                             }
                         }
                         initTablaCon();
                     },
                     error: function (jqXHR, status) {
                         alert("error lista cli");
                     }
                });
            }
            
            ///lista de conyuge
            
            function listaCon(cedula) {
                
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"codeudor/"+cedula,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         
                         if(data != null){
                             dataCon = data;
                             for(var i = 0; i < data.length ; i++){
                                document.getElementsByName("textconyuge")[0].value = data[0].nombre;
                                document.getElementsByName("txtnit")[0].value = data[0].cedula; 
                                document.getElementsByName("txtconyugedireccion")[0].value = data[0].direccion_oficina;
                                document.getElementsByName("txtconyugetelefono")[0].value = data[0].telefono;
                                document.getElementsByName("txtreferenciaconyuge")[0].value = data[0].referencia;
                                document.getElementsByName("txtreferenciaconyugetel")[0].value = data[0].telefono_referencia;
                             }
                              
                         }
                         
                        
                     },
                     error: function (jqXHR, status) {
                         alert("error lista conyuge");
                     }
                });
                
            }
            
            function CreditoContado(){
                if(document.getElementsByName("cboxcredito")[1].value == "Credito"){
                    mostrarcampos();
                }
            }
            
            function enviardatos(i){
               document.getElementsByName("txtcedula")[0].value = dataCli[i].cedula; 
               document.getElementsByName("txtnombres")[0].value = dataCli[i].nombre;
               document.getElementsByName("txtapellido")[0].value = dataCli[i].apellido;
               document.getElementsByName("txtdireccion")[0].value = dataCli[i].direccion_casa;
               document.getElementsByName("txttelefono")[0].value = dataCli[i].telefono;
               document.getElementsByName("txtcorreo")[0].value = dataCli[i].email;
               document.getElementsByName("txtdiroficina")[0].value = dataCli[i].direccion_oficina;
               document.getElementsByName("txtlabor")[0].value = dataCli[i].labor;
               listaRefe(dataCli[i].cedula);
               listaCon(dataCli[i].cedula);
               bandera = true;
               closeDialog(1);
            }
            
            var bandera = true;
            function credito(){
                if(document.getElementsByName("cboxcredito")[0].value == "Contado"){
                    registrarCli();
                }
                
            }
            
           
            
            function cbocredito(){
               if(document.getElementsByName("fecha_instalacion")[0].value==""){
                   alert("La fecha esta vacia ");
               }else{
                    if(document.getElementsByName("txtcedula")[0].value==""){
                        alert("Los campos estan vacios por favor completelos");
                    }else{
                        if(document.getElementsByName("cboxcredito")[0].value == "Credito"){
                            openDialog(2);
                            document.getElementById("preciotxt").innerHTML = document.getElementsByName("valorsuma")[0].value;
                        }
                    }
                       
                }
            }
            
            
            function verificarcampos(){
                var txt1 = document.getElementsByName("txtcuotas");
                var val  = document.getElementsByName("valorsuma")[0].value;
                var tot = 0;var sum = 0;
                    for(var i = 0; i<txt1.length; i++){
                        //alert(txt1[i].value);
                        sum = txt1[i].value;
                        tot = tot+parseInt(sum);
                        //alert(tot);
                    }   
                    if(tot==val){
                        registrarCli();
                    }else{
                        alert("Error al digitar las cuotas el total de las cuotas no es igual al valor del producto");
                    }
                    
            }
            
            function mostrarcampos(){
                var numcuo = document.getElementsByName("numerosdecuotas")[0].value;
                var i;
                //document.getElementById("preciotxt").innerHTML = document.getElementsByName("valorsuma")[0].value;
                document.getElementById("campos").innerHTML = "";
                    for(i=1;i<=numcuo;i++){
                        document.getElementById("campos").innerHTML += "<div class='div_form'><label style='width:150px;'>Cuota #"+i+"</label>\n\
                        <input name='txtcuotas' style='margin-top:5px; position:relative; left:-50px;' type='number'/></div><br/>";
                        
                    } 
                  //document.getElementById("campos").innerHTML += "";
                  
            }
            
            
            function redireccionar(){
                location.href = "mod_fecha_instalacion.php";
            }
            
            function openDialog(ban) {
                if(ban == 0){
                    Avgrund.show( "#msg-popup" );
                }
                if(ban == 1){
                    Avgrund.show( "#categoria-popup" );
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

        <div id="div_formularios2" >
            <div class="rectangle"><h2> Clientes</h2></div> 
            <div class="triangle-l"></div>
            
            <div class="rectangle1"><h2> Referencias Familiares</h2></div> 
            <div class="triangle-l1"></div>
            
            <div class="rectangle2"><h2> Modelo Del Purificador</h2></div> 
            <div class="triangle-l2"></div>
            
            <div class="rectangle3"><h2> Plan De Pago</h2></div> 
            <div class="triangle-l3"></div>

                <!--<button onclick="javascript:openDialog();">Open Avgrund</button>-->
                <form class="contact_form" name="frm_reg" id="form_id" action="javascript:credito();"  method="post" target="_blank">
                    <div id="div_4">
                        
                        <div class="div_form">
                            <label class="label1">Cedula</label><br /> <br /> 
                            <input name="txtcedula" type="number"   required />
                        </div>

                        <div class="div_form">
                            <label class="">Nombres</label><br /> <br /> 
                            <input name="txtnombres" type="text"   required />
                        </div>
                        
                        <div class="div_form">
                            <label class="">Apellidos</label><br /> <br /> 
                            <input name="txtapellido" type="text"   required />
                        </div>
                        
                        <div class="div_form">
                            <label class="">Direccion Residencia</label><br /> <br /> 
                            <input name="txtdireccion" type="text"   required />
                        </div>
                        
                         <div class="div_form">
                            <label>Telefono</label><br /> <br /> 
                            <input name="txttelefono" type="tel"   required />
                        </div>
                        
                        <div class="div_form">
                            <label>Correo</label><br /> <br /> 
                            <input name="txtcorreo" type="text"   required /><br/>
                        </div>
                        
                        <div class="div_form" >
                            <label>Direccion Oficina</label><br /> <br /> 
                            <input name="txtdiroficina" type="text"  required /><br/>
                        </div>
                        
                        <div class="div_form" >
                            <label>Labor</label><br /> <br /> 
                            <input name="txtlabor" type="text"  required /><br/>
                        </div>
                        
                        <div class="div_form" >
                            <label></label><br /> <br /> 
                            <input  type="hidden"   /><br/><br/>
                        </div>
                        
                        <div class="div_form" >
                            <label>Conyuge - Codeudor</label><br /> <br /> 
                            <input name="textconyuge" type="text"  />
                        </div>
                        
                        <div class="div_form">
                            <label>Cedula O NIT</label><br /> <br /> 
                            <input name="txtnit" type="number"   />
                        </div>
                        
                        <div class="div_form">
                            <label>Direccion Oficina</label><br /> <br /> 
                            <input name="txtconyugedireccion" type="text"   />
                        </div>
                        
                        <div class="div_form">
                            <label>Telefono</label><br /> <br /> 
                            <input name="txtconyugetelefono" type="tel"  />
                        </div>
                        
                        <div class="div_form">
                            <label >Referencia Del Codeudor</label><br /> <br /> 
                            <input name="txtreferenciaconyuge" type="text"    />
                        </div>
                        
                        <div class="div_form">
                            <label>Telefono De La Referencia</label><br /> <br /> 
                            <input name="txtreferenciaconyugetel" type="tel"   /><br/><br/><br/><br/><br/>
                        </div>
                        
                        <div class="div_form">
                            <label>Referencia Familiar</label><br /> <br /><br/> 
                            <input name="txtreferenciafamiliar" type="text"   />
                        </div>
                        
                        <div class="div_form">
                            <label>Telefono</label><br /> <br /><br/> 
                            <input name="txtreferenciatelefono" type="number"   />
                        </div>
                        
                        <div class="div_form" >
                            <label></label><br /> <br /> 
                            <input  type="hidden"  /><br/><br/><br/>
                        </div>
                        
                        <div class="div_form">
                            <label>Referencia Familiar</label><br /> <br /> <br/>
                            <input name="txtreferenciafamiliar1" type="text"   />
                        </div>
                        
                        <div class="div_form">
                            <label>Telefono</label><br /> <br /> <br/>
                            <input name="txtreferenciatelefono1" type="tel"  />
                        </div>
                        
                        <div class="div_form" >
                            <label></label><br /> <br /> 
                            <input  type="hidden" /><br/><br/><br/><br/><br/><br/><br/>
                        </div>
                        
                        <div class="div_form">
                            <label class="">Instalacion De Un:</label><br /><br />
                            <select name="cboxCategoria" class="relleno" onclick="javascript:consultaTipo();">
                                
                            </select>
                        </div>
                        
                        <div class="div_form">
                            <label>REF.:</label><br /> <br /> 
                            <select name="cboxtiponventario" onclick="javascript:consultaInvetario();"></select>
                        </div>
                        
                        <div class="div_form">
                            <label>Ref.:</label><br /> <br /> 
                            <select name="cboxinventario" onclick="javascript:consultavalor();"></select>
                        </div>
                        
                        <div class="div_form">
                            <label>Color</label><br /> <br /> 
                            <input name="descripcion_color" type="text"/>
                        </div>
                        
                        <div class="div_form" >
                            <label></label><br /> <br /> 
                            <input  type="hidden" /><br/><br/><br/><br/><br/><br/><br/>
                        </div>
                        
                        <div class="div_form" >
                            <label></label><br /> <br /> 
                            <input  type="hidden" /><br/><br/><br/><br/><br/><br/><br/>
                        </div>
                        <!--aqui va Fecha instalacion -->
                        <div class="div_form">
                            <label>Fecha Instalacion:</label><br /> <br /> 
                            <input name="fecha_instalacion" type="date" required/><br/><br/>
                        </div>
                        
                        <div class="div_form">
                            <label>Suma De:</label><br /> <br /> 
                            <input name="valorsuma" type="text"/>
                        </div>
                        
                        <div class="div_form">
                            <label>Vendedor:</label><br /> <br /> 
                            <select name="cboxvendedor">
                                
                            </select>
                        </div>
                        
                        <div class="div_form">
                            <label>Forma De Pago:</label><br /> <br />
                            <select name="cboxcredito" onclick="javascript:cbocredito();">
                                <option value="Contado">Contado</option>
                                <option value="Credito">Credito</option>
                            </select>
                        </div>
                        
                        <div class="div_form">
                            <input name="ocultartipo" type="text" style="visibility: hidden" />
                        </div>
                        
                        <div class="div_form">
                            <input name="ocultarmodelo" type="text" style="visibility: hidden" />
                        </div>
                        
                        <button id="btnRegistrarInv3" onsubmit="javascript:function(){document.frm_reg.submit();}" >Registrar</button>
                        
                    </div>
                        
                </form>
                <button id="btnRegistrarInv5" onclick="javascript:mostrarclientes()" >Clientes Registrados</button>
                <button id="btnRegistrarInv33" onclick="javascript:redireccionar();">Modificar Fecha Instalacion</button>
                <!--<button id="btnRegistrarInv8" onclick="javascript:mostrarvendedor()" >Registrar Vendedores</button>-->
                        
        </div>
        
        </article>
            
        <aside id="categoria-popup" class="avgrund-popup">

            <form class="">
                    
                    <div id="div_3"> 
                        
                        <div id="div_tablaInv2">
                            
                            <table class="display" id="tablaCon">
                                <thead>
                                    <tr>
                                        <th>Cedula</th>
                                        <th>Nombres</th>
                                        <th>Apellidos</th>
                                        <th>Direccion</th>
                                        <th>Telefono</th>
                                        <th>Correo</th>
                                        <th>Direccion oficina</th>
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
        
        <aside id="categoria-cliente" class="avgrund-popup9"> 
            <form class="contact_form" action="javascript:mostrarcampos();">
                
                <h2 style="text-align: center">Registrar Cuotas</h2><br/>
                <h3 style="color:#555555; margin-left: 10px;">Costo del producto: </h3>
                <h3 style="color:#555555; margin-left: 195px; margin-top: -22px;" id="preciotxt"></h3>
                <div class="div_form" style="margin-bottom: 50px;">
                    <label># Cuotas</label><br /> <br /> 
                    <input name="numerosdecuotas" type="text"/><br/>
                </div>
                <button id="btnRegistrarInv6">Aceptar</button>
                <div id="campos" style="margin-top: 10px; "> 
                </div>
                 
            </form><br/>
            <button id='btnRegistrarInv7' onclick='javascript:verificarcampos();'>Registrar</button>
            
        </aside>
        
        <aside id="categoria-vendedor" class="avgrund-popup10"> 
            <form class="contact_form" action="javascript:registrarVendedor();">
                <h2 style="text-align: center">Registrar Vendedores</h2>
                <div class="div_form" style="margin-left: 65px;">
                    <label>Cedula</label><br /> <br /> 
                    <input name="txtcedulaven" type="text" required/><br/>
                </div>
                <div class="div_form" style="margin-left: 65px;">
                    <label>Nombres</label><br /> <br /> 
                    <input name="txtnombresven" type="text" required/><br/>
                </div>
                <div class="div_form" style="margin-left: 65px;">
                    <label>Apellido</label><br /> <br /> 
                    <input name="txtapellidosven" type="text" required/><br/>
                </div>
                <div class="div_form" style="margin-left: 65px;">
                    <label>Telefono</label><br /> <br /> 
                    <input name="txttelefonoven" type="text" required/><br/>
                </div>
                <button id="btnRegistrarInv6" style="margin-left: 200px;">Aceptar</button>  
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