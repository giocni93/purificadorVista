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
            var idMan;
            
            var banImp = false;
            var banGIO = false;
            
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
                    "bFilter": true
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
                document.getElementById("btnAgregarMan").style['visibility'] = "hidden";
                
                initTablaPlan();
                listaCli();
                initTablaHis();
                initTablaOrd();
                
                var id = getGET();
                if(id != null){
                    consulta_idMan(id['id']);
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
            
            function jsonMan(ic) {
                return JSON.stringify({
                    "asesor": document.getElementsByName("txtAsesor")[0].value,
                    "fechaProgramada" : document.getElementsByName("txtFechaPro")[0].value,
                    "ciudad" : document.getElementsByName("txtCiudad")[0].value,
                    "motivo" : document.getElementsByName("txtMotivo")[0].value,
                    "nombreTecnico" : document.getElementsByName("txtNombreTec")[0].value,
                    "idCliente" : ic
                    });
            }

            function mostrarOrd(i){
                idCli = dataCli[i].cedula;
                document.getElementById("lblNombreCli").innerHTML = dataCli[i].nombre + " " + dataCli[i].apellido;
                document.getElementById("lblNombreCli2").innerHTML = dataCli[i].nombre + " " + dataCli[i].apellido;
                $('#tablaOrd').dataTable().fnDestroy();
                listaOrd();
                openDialog(1);
            }
            
            function detalleOrd(id){
                document.getElementById("lblOrden").innerHTML = "Orden de pedido # "+id;
                document.getElementById("btnAgregarMan").style['visibility'] = "visible";
                idOrd = id;
                banManExt = false;
                banGIO = true;
                $('#tablaPlan').dataTable().fnDestroy();
                listaPlan(id);
                closeDialog();
            }

            function listaCli() {
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"cliente/mantenimiento",
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
            
            var banManExt = false;
            
            function listaOrd() {
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"orden_pedido/clienteMan/"+idCli,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         var tb = document.getElementById("tbody_tablaOrd");
                         tb.innerHTML = "";
                         if(data != null){
                             for(var i = 0; i < data.length ; i++){
                                 tb.innerHTML += "<tr>\n\
                                 <td>"+data[i].Id+"</td>\n\
                                 <td>"+data[i].Fecha+"</td>\n\
                                 <td>"+data[i].Estado+"</td>\n\
                                 <td><a onclick='javascript:detalleOrd("+data[i].Id+");' class='btnModi'>Detalle</a></td>\n\
                                 </tr>";
                             }
                             initTablaOrd();
                         }else{
                             document.getElementById("lblOrden").innerHTML = "--";
                            document.getElementById("btnAgregarMan").style['visibility'] = "visible";
                            banManExt = true;
                            $('#tablaPlan').dataTable().fnDestroy();
 
                            closeDialog();
                             listaPlanExt(idCli);
                         }
                    
                     },
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
            }
            
            function activarCampos(){
                //alert(dataHis[i].Observacion);
                document.getElementById("divDetallePlan").style['visibility'] = "visible";
                document.getElementById("div_tablaHis2").style['visibility'] = "visible";
                document.getElementById("btnCanPlan").style['visibility'] = "visible";
                //document.getElementById("lblNombre").innerHTML = document.getElementById("lblNombreCli").innerHTML;
                
            }
            
            function cancelarPlan(){
                document.getElementById("divDetallePlan").style['visibility'] = "hidden";
                document.getElementById("div_tablaHis2").style['visibility'] = "hidden";
                document.getElementById("btnCanPlan").style['visibility'] = "hidden";
                document.getElementById("btnAgregarMan").style['visibility'] = "hidden";
                document.getElementById("lblNombre").innerHTML = "";
                document.getElementById("lblOrden").innerHTML = "";
            }
            
            function actualizarDialogOrd(i){
                //alert(id);
                idDetalle = dataPlan[i].IdDetalle;
                numCuota = i+1;
                val = dataPlan[i].ValorCuota;
                openDialog(2);
            }
            
            function registrarMan(){
                var b = 'null';
                if(banManExt){
                    b = idCli;
                    idOrd = null;
                }
                jQuery.ajax({
                     type: "post",
                     url: servidor+"mantenimiento/"+idOrd,
                     dataType: "json",
                     data: jsonMan(b),
                     success: function (data, status, jqXHR) {
                        
                         if(data == true){
                             //document.getElementById("divInfoInv").innerHTML = "<label style='color: green;'>Guardado correctamente</label>";
                             alert("Guardado correctamente");
                         }
                         if(data == 0){
                             //document.getElementById("divInfoInv").innerHTML = "<label style='color: red;'>Error de conexion, por favor intente mas tarde.</label>";
                             alert("Error de conexion, por favor intente mas tarde.");
                         }
                         $('#tablaPlan').dataTable().fnDestroy();
                        listaPlan(idOrd);
                        closeDialog();
                        if(banImp){
                            datos = {
                                
                            };
                            window.open(servidor+"ordenmantenimiento.php?idOrden="+idOrd);
                        }
                     },
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
            }
            
            function detalleMan(id){
                document.getElementById("lblTitulo3").innerHTML = "Orden de mantenimiento No "+id;
                idMan = id;
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"mantenimiento/"+id,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         if(data != null){
                             document.getElementsByName("txtCiudad2")[0].value = data[0].Ciudad;
                             document.getElementsByName("txtAsesor2")[0].value = data[0].Asesor;
                             document.getElementsByName("txtFechaPro2")[0].value = data[0].FechaProgramada;
                             document.getElementsByName("txtFechaRealizada2")[0].value = data[0].FechaRealizacion;
                             document.getElementsByName("txtTecnico2")[0].value = data[0].NombreTecnico;
                             document.getElementsByName("txtMotivo2")[0].value = data[0].Motivo;
                             document.getElementsByName("txtObservacion")[0].value = data[0].Observacion;
                             openDialog(3);
                         }
                     },
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
            }
            
            function consulta_idMan(id){
                
                idMan = id;
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"mantenimiento/idMan/"+id,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         if(data != null){
                             try{
                             closeDialog();
                         }catch(e){
                             //alert(e);
                         }
                             banGIO = true;
                             document.getElementById("lblTitulo3").innerHTML = "Orden de mantenimiento No "+id;
                             
                             document.getElementsByName("txtCiudad2")[0].value = data[0].Ciudad;
                             document.getElementsByName("txtAsesor2")[0].value = data[0].Asesor;
                             document.getElementsByName("txtFechaPro2")[0].value = data[0].FechaProgramada;
                             document.getElementsByName("txtFechaRealizada2")[0].value = data[0].FechaRealizacion;
                             document.getElementsByName("txtTecnico2")[0].value = data[0].NombreTecnico;
                             document.getElementsByName("txtMotivo2")[0].value = data[0].Motivo;
                             document.getElementsByName("txtObservacion")[0].value = data[0].Observacion;
                             
                             document.getElementById("lblCliente2").innerHTML = data[0].NombreCliente + " " +data[0].ApellidoCliente;
                             document.getElementById("lblFechaSol").innerHTML = data[0].Fecha;
                             document.getElementById("lblDireccion2").innerHTML = data[0].DireccionCliente;
                             document.getElementById("lblTelefono2").innerHTML = data[0].TelefonoCliente;
                             document.getElementById("lblPlanta2").innerHTML = data[0].NombreInv + " / " + data[0].NombreTipo;
                             document.getElementById("lblTarjeta").innerHTML = data[0].idOp;
                             idOrd = data[0].idOp;
                             openDialog(3);
                         
                         }
                         else{
                            consulta_idManExt(id);
                            //document.getElementById("divInfoMan").innerHTML = "<label style='color: red;'>Error, datos no encontrados.</label>";
                         }
                     },
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
            }
            
            function consulta_idManExt(id){
                
                idMan = id;
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"mantenimiento/idManExt/"+id,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         if(data != null){
                             try{
                             closeDialog();
                         }catch(e){
                             //alert(e);
                         }
                         banGIO = false;
                             document.getElementById("lblTitulo3").innerHTML = "Orden de mantenimiento No "+id;
                             
                             document.getElementsByName("txtCiudad2")[0].value = data[0].Ciudad;
                             document.getElementsByName("txtAsesor2")[0].value = data[0].Asesor;
                             document.getElementsByName("txtFechaPro2")[0].value = data[0].FechaProgramada;
                             document.getElementsByName("txtFechaRealizada2")[0].value = data[0].FechaRealizacion;
                             document.getElementsByName("txtTecnico2")[0].value = data[0].NombreTecnico;
                             document.getElementsByName("txtMotivo2")[0].value = data[0].Motivo;
                             document.getElementsByName("txtObservacion")[0].value = data[0].Observacion;
                             
                             document.getElementById("lblCliente2").innerHTML = data[0].NombreCliente + " " +data[0].ApellidoCliente;
                             document.getElementById("lblFechaSol").innerHTML = data[0].Fecha;
                             document.getElementById("lblDireccion2").innerHTML = data[0].DireccionCliente;
                             document.getElementById("lblTelefono2").innerHTML = data[0].TelefonoCliente;
                             document.getElementById("lblPlanta2").innerHTML = data[0].NombreInv + " / " + data[0].NombreTipo;
                             document.getElementById("lblTarjeta").innerHTML = data[0].idOp;
                             idOrd = data[0].idOp;
                             openDialog(3);
                         }
                         else{
                             document.getElementById("divInfoMan").innerHTML = "<label style='color: red;'>Error, datos no encontrados.</label>";
                         }
                     },
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
            }
            
            function imprimirMan(id){
                window.open(servidor+"ordenmantenimiento.php?idMan="+id);
            }
            
            function listaPlan(id) {
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"mantenimiento/idOrden/"+id,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         var tb = document.getElementById("tbody_tablaPlan");
                         tb.innerHTML = "";
                         if(data != null){
                             document.getElementById("lblDatos").innerHTML = "Mantenimiento";
                             document.getElementById("btnAgregarMan").style['top'] = "27px";
                             dataPlan = data;
                             for(var i = 0; i < data.length ; i++){
                                 
                                 var fechaR = "Sin realizar";
                                 if(data[i].FechaRealizacion != null && data[i].FechaRealizacion != '-0001-11-30 00:00 AM'){
                                     fechaR = data[i].FechaRealizacion;
                                 }
                                 
                                 tb.innerHTML += "<tr>\n\
                                 <td>"+data[i].Id+"</td>\n\
                                 <td>"+data[i].Fecha+"</td>\n\
                                 <td>"+data[i].FechaProgramada+"</td>\n\
                                 <td>"+fechaR+"</td>\n\
                                 <td>"+data[i].Asesor+"</td>\n\
                                 <td><a onclick='javascript:detalleMan("+data[i].Id+");' class='btnModi'>Ver</a></td>\n\
                                 <td><a onclick='javascript:imprimirMan("+data[i].Id+");' class='btnModi'>Imprimir</a></td>\n\
                                 </tr>";
                             }
                             activarCampos();
                             var val = "--";
                             if(data[0].Ciudad != null && data[0].Ciudad != ""){
                                 val = data[0].Ciudad;
                             }
                             
                             var tec = "--";
                             if(data[0].NombreTecnico != null && data[0].NombreTecnico != ""){
                                 tec = data[0].NombreTecnico;
                             }
                             
                             
                             document.getElementById("lblCliente").innerHTML = data[0].NombreCliente + " " +data[0].ApellidoCliente;
                             document.getElementById("lblCiudad").innerHTML = val;
                             document.getElementById("lblDireccion").innerHTML = data[0].DireccionCliente;
                             document.getElementById("lblTelefono").innerHTML = data[0].TelefonoCliente;
                             document.getElementById("lblPlanta").innerHTML = data[0].NombreInv + " / " + data[0].NombreTipo;
                             document.getElementById("lblTecnico").innerHTML = tec;
                             
                             document.getElementById("lblCliente2").innerHTML = data[0].NombreCliente + " " +data[0].ApellidoCliente;
                             document.getElementById("lblFechaSol").innerHTML = data[0].Fecha;
                             document.getElementById("lblDireccion2").innerHTML = data[0].DireccionCliente;
                             document.getElementById("lblTelefono2").innerHTML = data[0].TelefonoCliente;
                             document.getElementById("lblPlanta2").innerHTML = data[0].NombreInv + " / " + data[0].NombreTipo;
                             document.getElementById("lblTarjeta").innerHTML = id;
                             document.getElementsByName("txtValorPagado")[0].value = data[0].ValorPagado;
                             
                             //document.getElementById("lblEstado").style['color'] = color;
                             
                         }
                         else{
                             document.getElementById("lblDatos").innerHTML = "No se encontraron datos.";
                             document.getElementById("btnAgregarMan").style['top'] = "-130px";
                             cancelarPlan();
                             document.getElementById("btnAgregarMan").style['visibility'] = "visible";
                         }
                         initTablaPlan();
                     },
                     error: function (jqXHR, status) {
                         alert("error lista Man");
                     }
                });
            }
            
            function listaPlanExt(id) {
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"/mantenimiento/idCliente/"+id,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         var tb = document.getElementById("tbody_tablaPlan");
                         tb.innerHTML = "";
                         if(data != null){
                             document.getElementById("lblDatos").innerHTML = "Mantenimiento";
                             document.getElementById("btnAgregarMan").style['top'] = "27px";
                             dataPlan = data;
                             for(var i = 0; i < data.length ; i++){
                                 
                                 var fechaR = "Sin realizar";
                                 if(data[i].FechaRealizacion != null && data[i].FechaRealizacion != '-0001-11-30 00:00 AM'){
                                     fechaR = data[i].FechaRealizacion;
                                 }
                                 
                                 tb.innerHTML += "<tr>\n\
                                 <td>"+data[i].Id+"</td>\n\
                                 <td>"+data[i].Fecha+"</td>\n\
                                 <td>"+data[i].FechaProgramada+"</td>\n\
                                 <td>"+fechaR+"</td>\n\
                                 <td>"+data[i].Asesor+"</td>\n\
                                 <td><a onclick='javascript:consulta_idManExt("+data[i].Id+");' class='btnModi'>Ver</a></td>\n\
                                 <td><a onclick='javascript:imprimirMan("+data[i].Id+");' class='btnModi'>Imprimir</a></td>\n\
                                 </tr>";
                             }
                             activarCampos();
                             var val = "--";
                             if(data[0].Ciudad != null && data[0].Ciudad != ""){
                                 val = data[0].Ciudad;
                             }
                             
                             var tec = "--";
                             if(data[0].NombreTecnico != null && data[0].NombreTecnico != ""){
                                 tec = data[0].NombreTecnico;
                             }
                             
                             
                             document.getElementById("lblCliente").innerHTML = data[0].NombreCliente + " " +data[0].ApellidoCliente;
                             document.getElementById("lblCiudad").innerHTML = val;
                             document.getElementById("lblDireccion").innerHTML = data[0].DireccionCliente;
                             document.getElementById("lblTelefono").innerHTML = data[0].TelefonoCliente;
                             document.getElementById("lblPlanta").innerHTML = data[0].NombreInv + " / " + data[0].NombreTipo;
                             document.getElementById("lblTecnico").innerHTML = tec;
                             
                             document.getElementById("lblCliente2").innerHTML = data[0].NombreCliente + " " +data[0].ApellidoCliente;
                             document.getElementById("lblFechaSol").innerHTML = data[0].Fecha;
                             document.getElementById("lblDireccion2").innerHTML = data[0].DireccionCliente;
                             document.getElementById("lblTelefono2").innerHTML = data[0].TelefonoCliente;
                             document.getElementById("lblPlanta2").innerHTML = data[0].NombreInv + " / " + data[0].NombreTipo;
                             document.getElementById("lblTarjeta").innerHTML = id;
                             document.getElementsByName("txtValorPagado")[0].value = data[0].ValorPagado;
                             
                             //document.getElementById("lblEstado").style['color'] = color;
                             
                         }
                         else{
                             document.getElementById("lblDatos").innerHTML = "No se encontraron datos.";
                             document.getElementById("btnAgregarMan").style['top'] = "-130px";
                             cancelarPlan();
                             document.getElementById("btnAgregarMan").style['visibility'] = "visible";
                         }
                         initTablaPlan();
                     },
                     error: function (jqXHR, status) {
                         alert("error lista Man");
                     }
                });
            }
            
            function jsonUpdateMan() {

                return JSON.stringify({
                    "asesor": document.getElementsByName("txtAsesor2")[0].value,
                    "fechaProgramada" : document.getElementsByName("txtFechaPro2")[0].value,
                    "ciudad" : document.getElementsByName("txtCiudad2")[0].value,
                    "motivo" : document.getElementsByName("txtMotivo2")[0].value,
                    "observacion" : document.getElementsByName("txtObservacion")[0].value,
                    "fechaRealizacion" : document.getElementsByName("txtFechaRealizada2")[0].value,
                    "nombreTecnico" : document.getElementsByName("txtTecnico2")[0].value,
                    "valorPagado" : document.getElementsByName("txtValorPagado")[0].value
                    });
            }
            
            function jsonManExt() {

                return JSON.stringify({
                    "cedula": document.getElementsByName("txtcedula")[0].value,
                    "nombre" : document.getElementsByName("txtnombre")[0].value,
                    "apellido" : document.getElementsByName("txtapellido")[0].value,
                    "direccion" : document.getElementsByName("txtdireccion")[0].value,
                    "telefono" : document.getElementsByName("txttelefono")[0].value,
                    "correo" : document.getElementsByName("txtcorreo")[0].value,
                    "asesor": document.getElementsByName("txtAsesor3")[0].value,
                    "fechaProgramada" : document.getElementsByName("txtFechaPro3")[0].value,
                    "ciudad" : document.getElementsByName("txtCiudad3")[0].value,
                    "motivo" : document.getElementsByName("txtMotivo3")[0].value,
                    "nombreTecnico" : document.getElementsByName("txtNombreTec3")[0].value
                    });
            }
            
            function registrarManExt(){

                jQuery.ajax({
                     type: "post",
                     url: servidor+"/cliente/mantenimiento",
                     dataType: "json",
                     data: jsonManExt(),
                     success: function (data, status, jqXHR) {
                         if(data == true){
                             //document.getElementById("divInfoInv").innerHTML = "<label style='color: green;'>Guardado correctamente</label>";
                            if(data.estado == 1) {
                                alert("Guardado correctamente");
                            }else{
                                alert("Error , los datos ya estan registrados.");
                            }
                         }
                         if(data == 0){
                             //document.getElementById("divInfoInv").innerHTML = "<label style='color: red;'>Error de conexion, por favor intente mas tarde.</label>";
                             alert("Error de conexion, por favor intente mas tarde.");
                         }
                         
                        closeDialog();
                        if(banImp){
                            datos = {
                                
                            };
                            window.open(servidor+"ordenmantenimiento.php?idOrden="+idOrd);
                        }
                     },
                     error: function (jqXHR, status) {
                         alert("error "+status.code);
                     }
                });
                
            }
            
            function buscarMan(){
                var idM = document.getElementById("txtBuscarMan").value;
                consulta_idMan(idM);
            }
            
            function updateMan(){
                var val = document.getElementsByName("txtValorPagado")[0].value;
                if(val == ""){
                    val = parseInt(val);
                }
                
                if(parseInt(val) < 0){
                    alert("El valor del pago es incorrecto.");
                }
                else{
                    jQuery.ajax({
                         type: "put",
                         url: servidor+"mantenimiento/"+idMan+"/"+idOrd,
                         dataType: "json",
                         data: jsonUpdateMan(),
                         success: function (data, status, jqXHR) {
                             
                             if(data == true){
                                 //document.getElementById("divInfoInv").innerHTML = "<label style='color: green;'>Guardado correctamente</label>";
                                 alert("Guardado correctamente");
                             }
                             if(data == 0){
                                 //document.getElementById("divInfoInv").innerHTML = "<label style='color: red;'>Error de conexion, por favor intente mas tarde.</label>";
                                 alert("Error de conexion, por favor intente mas tarde.");
                             }
                             $('#tablaPlan').dataTable().fnDestroy();
                             if(banGIO){
                                
                                listaPlan(idOrd);
                             }else{
                                 $('#tablaCli').dataTable().fnDestroy();
                                 $('#tablaOrd').dataTable().fnDestroy();
                                 document.getElementById("divDetallePlan").style['visibility'] = "hidden";
                                document.getElementById("div_tablaHis2").style['visibility'] = "hidden";
                                document.getElementById("btnCanPlan").style['visibility'] = "hidden";
                                document.getElementById("btnAgregarMan").style['visibility'] = "hidden";

                                initTablaPlan();
                                listaCli();
                                initTablaHis();
                                initTablaOrd();
                             }
                        
                            closeDialog();
                            if(banImp){
                                imprimirMan(idMan);
                            }
                         },
                         error: function (jqXHR, status) {
                             alert("error");
                         }
                    });
                }
            }
            
            function alterarBanImp(val){
                banImp = val;
                //document.frmMan.submit();
            }

            function openDialog(ban) {
                if(ban == 2){
                    Avgrund.show( "#msg-popup" );
                }
                if(ban == 0){
                    document.getElementsByName("txtNombreTec")[0].value = "";
                    document.getElementsByName("txtMotivo")[0].value = "";
                    document.getElementsByName("txtAsesor")[0].value = "";
                    document.getElementsByName("txtFechaPro")[0].value = "";
                    Avgrund.show( "#Man-popup" );
                }
                if(ban == 1){
                    Avgrund.show("#orden-popup");
                }
                
                if(ban == 3){
                    Avgrund.show("#modiMan-popup");
                }
                
                if(ban == 5){
                    Avgrund.show("#manExt-popup");
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
                  <div class="link_title activo">
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
            <div class="rectangle"><h2>Mantenimiento</h2></div> 
            <div class="triangle-l"></div>
            <button id="btnBuscarMan" onclick="javascript:openDialog(4);">Busqueda especifica</button>
            <button id="btn_manExt" onclick="javascript:openDialog(5);">Mantenimiento externo</button>
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
                <h2 id="lblDatos"  style="color:#555555; width: 400px;">Mantenimiento</h2>
                <button onclick="javascript:cancelarPlan()" id="btnCanPlan" class="aInvElimi" style="margin-top: 10px;">Cancelar</button>
                <h4 style="margin-top: 20px; color:#759ABE" id="lblNombre"></h4>
                <p style="margin-top: 0px; color:#759ABE" id="lblOrden"></p>
                <div id="divDetallePlan" style="height: 110px;">
                    <div class="div_form2">
                        <h3 style="color:#555555;">Cliente</h3>
                        <label id="lblCliente"></label>
                    </div>
                    <div class="div_form2">
                        <h3 style="color:#555555;">Ciudad</h3>
                        <label id="lblCiudad"></label>
                    </div>
                    <div class="div_form2">
                        <h3 style="color:#555555;">Direccion</h3>
                        <label id="lblDireccion"></label>
                    </div>
                    <div class="div_form2">
                        <h3 style="color:#555555;">Telefono</h3>
                        <label id="lblTelefono"></label>
                    </div>
                    <div class="div_form2">
                        <h3 style="color:#555555;">Modelo planta</h3>
                        <label id="lblPlanta"></label>
                    </div>
                    <div class="div_form2">
                        <h3 style="color:#555555;">Tecnico</h3>
                        <label id="lblTecnico"></label>
                    </div>
                </div>
                <button style="position: relative; top: 27px; z-index: 100;" id="btnAgregarMan" onclick="javascript:openDialog(0);">Agregar mantenimiento</button>
                <div id="div_tablaHis2" style="margin-top: -30px;">
                    <form class="contact_form" name="frmHis2" action="" method="post">
                        <table class="display" id="tablaPlan">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Fecha solicitud</th>
                                    <th>Fecha programada</th>
                                    <th>Fecha realizacion</th>
                                    <th>Asesor Programa</th>
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
            <label class="lblEliminar">¿Deseas realizar esta operacion?</label>
            <label id="lblCancelarInv" class="lblEliminar"></label>
            <a onclick="javascript:actualizarOrd();" class="aInvElimi">Aceptar</a> <br/>
            <a onclick="javascript:closeDialog();" id="btnCan" class="aInvElimi">Cancelar</a>
        </aside>
        
        <aside id="manExt-popup" class="avgrund-popup">
            <h2 style="color:#759ABE; background-color: transparent;">Registrar mantenimiento</h2>
            <h4 style="color:#555555; background-color: transparent;">Datos del cliente</h4>
            <br/>
            <form class="contact_form" name="frm_reg" action="javascript:registrarManExt();" >
                
            <div class="div_form">
                <label class="label1">Cedula</label><br /> <br /> 
                <input name="txtcedula" type="number"   required />
            </div>

            <div class="div_form">
                <label class="">Nombres</label><br /> <br /> 
                <input name="txtnombre" type="text"   required />
            </div>

            <div class="div_form">
                <label class="">Apellidos</label><br /> <br /> 
                <input name="txtapellido" type="text"   required />
            </div>

            <div class="div_form">
                <label class="">Direccion</label><br /> <br /> 
                <input name="txtdireccion" type="text"   required />
            </div>

             <div class="div_form">
                <label>Telefono</label><br /> <br /> 
                <input name="txttelefono" type="tel"   required />
            </div>

            <div class="div_form">
                <label>Correo</label><br /> <br /> 
                <input name="txtcorreo" type="text"  /><br/>
            </div>
            <br/><br/>
            <br/><br/>
            <br/><br/>
            <br/> <br/>
            <br/>
            <h4 style="color:#555555; background-color: transparent;">Datos del mantenimiento</h4>
            <br />
            <div class="div_form">
                <label style="width: 200px;">Fecha programada</label><br /><br /> 
                <input type="date" name="txtFechaPro3" required />
            </div>

            <div class="div_form">
                <label style="width: 200px;">Ciudad</label><br /><br /> 
                <input type="text" name="txtCiudad3" value="Valledupar" required />
            </div>

            <div class="div_form">
                <label style="width: 200px;">Asesor</label><br /><br /> 
                    <input type="text" name="txtAsesor3"  />
            </div>

            <div class="div_form">
                <label style="width: 200px;">Nombre tecnico</label><br /><br /> 
                    <input type="text" name="txtNombreTec3"  />
            </div>

             <div class="div_form">
                <label style="width: 200px; margin-bottom: 3px;">Motivo</label><br />
                    <textarea name="txtMotivo3" ></textarea>
            </div>

            <br/><br/>
            <br/><br/>
            <br/><br/>
            <br/> <br/>
            
            
            <button style="position: relative; left: -75px;">Registrar</button>
                   
            </form>
        </aside>
        
        <aside id="buscarMan-popup" class="avgrund-popup">
            <div id="div_buscarMan">
                <form class="contact_form" name="frmMan" action="javascript:buscarMan();" method="post">
                    <label>Numero de mantenimiento</label><br /> <br /> <br />
                        <input name="txtNumMan" id="txtBuscarMan" type="text" required />
                    <br /> 
                    <button style="position: relative; left: -90px; top: -8px;" id="btnBusMan">Buscar</button>
                </form>
                <div id="divInfoMan"></div>
            </div>
        </aside>
        
        <aside id="modiMan-popup" class="avgrund-popup">
            <div id="div_MadModi">
                
                <h2 style="color:#759ABE; background-color: transparent;" id="lblTitulo3"></h2>
                <div id="divDetalleMan" style="height: 110px;">
                    <div class="div_form2">
                        <h3 style="color:#555555;">Cliente</h3>
                        <label id="lblCliente2"></label>
                    </div>
                    <div class="div_form2">
                        <h3 style="color:#555555;">Direccion</h3>
                        <label id="lblDireccion2"></label>
                    </div>
                    <div class="div_form2">
                        <h3 style="color:#555555;">Telefono</h3>
                        <label id="lblTelefono2"></label>
                    </div>
                    <div class="div_form2">
                        <h3 style="color:#555555;">Modelo planta</h3>
                        <label id="lblPlanta2"></label>
                    </div>
                    <div class="div_form2">
                        <h3 style="color:#555555;">Fecha solicitud</h3>
                        <label id="lblFechaSol"></label>
                    </div>
                    <div class="div_form2">
                        <h3 style="color:#555555;">No tarjeta</h3>
                        <label id="lblTarjeta"></label>
                    </div>
                </div>
                <form class="contact_form" name="frmMan" action="javascript:updateMan();" method="post">
                    <div id="div_modiMan1">
                        <label>Fecha programada</label><br /> <br /> <br />
                        <input name="txtFechaPro2" type="date" required />
                        <br /> 
                        <label>Ciudad</label><br /> <br />
                        <input name="txtCiudad2" type="text"  />
                        <br/> 
                        <label style="width: 300px;">Motivo</label><br />
                        <textarea name="txtMotivo2" ></textarea>
                        <br />
                        <label style="width: 300px;">Observacion</label><br />
                        <textarea name="txtObservacion" ></textarea>
                        
                    </div>

                    <div id="div_modiMan2">
                        <label>Fecha Realizada</label><br /> <br /> <br />
                        <input name="txtFechaRealizada2" type="datetime-local" />
                        <br /> 
                        <label>Tecnico</label><br /> <br /> 
                        <input name="txtTecnico2" type="text"  />
                        <br/>
                        <label>Asesor</label><br /> <br /> 
                        <input name="txtAsesor2" type="text"  />
                        
                        <br/>
                        <label style="width: 150px;">Valor pagado</label><br /> <br /> 
                        <input name="txtValorPagado" type="number"  />
                        
                        <br/>
                        <button onclick="javascript:alterarBanImp(true);" style="position: relative; left: -90px;" id="btnModiMan" >Guardar e imprimir</button>
                        <br/>
                        <button onclick="javascript:alterarBanImp(false);" style="position: relative; left: -90px;" id="btnModiMan2" >Solo guardar</button>
                    </div>
                </form>
                <!--
                <table id="tablaManSelect">
                    <tr>
                        <td></td>
                        <td>Bueno</td>
                        <td>Regular</td>
                        <td>Malo</td>
                    </tr>
                    <tr>
                        <td>Mallas</td>
                        <td><input type="radio" name="radioMan1" /></td>
                        <td><input type="radio" name="radioMan1" /></td>
                        <td><input type="radio" name="radioMan1" /></td>
                    </tr>
                    <tr>
                        <td>Electrovalvula</td>
                        <td><input type="radio" name="radioMan2" /></td>
                        <td><input type="radio" name="radioMan2" /></td>
                        <td><input type="radio" name="radioMan2" /></td>
                    </tr>
                    <tr>
                        <td>Circuito</td>
                        <td><input type="checkbox" name="radioMan3" /></td>
                        <td><input type="checkbox" name="radioMan3" /></td>
                        <td><input type="checkbox" name="radioMan3" /></td>
                    </tr>
                </table>
                -->
            </div>
        </aside>

        <aside id="Man-popup" class="avgrund-popup">
            <form class="contact_form" name="frmMan" action="javascript:registrarMan();" method="post">
                <div id="div_Man">
                <h2 style="color:#759ABE; background-color: transparent;" id="lblTitulo2">Nueva orden de mantenimiento</h2>
                <h3 style="color:#555555; margin-left: 5px; margin-top: -5px;" id="lblNombreCli2"></h3>
                </div>
                
                <div class="div_manReg">
                    <label style="width: 300px;">Fecha programada</label><br /><br /> 
                    <input type="date" name="txtFechaPro" required />
                    <br/>
                    <label style="width: 300px;">Ciudad</label><br /><br /> 
                    <input type="text" name="txtCiudad" value="Valledupar" required />
                    <br/>
                    <label style="width: 300px;">Asesor</label><br /><br /> 
                    <input type="text" name="txtAsesor"  />
                    <br/>
                    <label style="width: 300px;">Nombre tecnico</label><br /><br /> 
                    <input type="text" name="txtNombreTec"  />
                    <label style="width: 300px;">Motivo</label><br /><br /> 
                    <textarea name="txtMotivo" ></textarea>
                </div>
                <button onclick="javascript:alterarBanImp(true);">Guardar e imprimir</button> <br/>
                <button onclick="javascript:alterarBanImp(false);">Solo guardar</button>
            </form>
            <br/>
            <!--<button class="cance" onclick="closeDialog()">Cerrar</button>-->
        </aside>
        
        <aside id="orden-popup" class="avgrund-popup">
            <div id="div_observacion">
                <h2 style="color:#759ABE" id="lblTitulo">Orden de pedidos</h2>
                <h3 style="color:#555555" id="lblNombreCli"></h3>
                    <form class="contact_form" name="frmHis" action="" method="post">
                    <table class="display" id="tablaOrd">
                          <thead>
                              <tr>
                                  <th>Orden de pedido</th>
                                  <th>Fecha</th>
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