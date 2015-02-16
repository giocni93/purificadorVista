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

            var dataInv;
            var idInv;
            var dataCat;
            var idCat;
            var dataTipo;
            var idTipo;
            var d = new Date(); 
            var NI = d.getDate() + "" + (d.getMonth() +1) + "" + d.getFullYear() + '' +d.getHours()+''+d.getMinutes()+''+d.getSeconds();
            var NomImg ="";
            var NomImg1 ="";
            var NomImgAnt = "";
            
            $(document).ready(function(){

                
                $('#txtImagen').change(function()
                {
                    var fileExtension = "";
                    document.getElementById("barInv1").value = 0;
                    var file = $("#txtImagen")[0].files[0];
                    var fileName = file.name;
                    document.getElementById("barInv1").value = 30;
                    fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
                    document.getElementById("barInv1").value = 70;
                    
                    NomImg1 = NI+"."+fileExtension;
                    
                    if(isImage(fileExtension)){
                        var formData = new FormData($(".frmInv1")[0]);
                        $.ajax({
                            url: servidor+'upload.php?n='+NI+"&e="+fileExtension,  
                            type: 'POST',
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            //una vez finalizado correctamente
                            success: function(data){
                                document.getElementById("barInv1").value = 100;
                                document.getElementById("imgInv1").setAttribute("src",servidor+"imagenes/"+data);
                            },
                            //si ha ocurrido un error
                            error: function(){
                                alert("error img");
                            }
                        });
                    }
                    else{
                        alert("solo se permiten imagenes");
                    }
                });
                
                 $('#txtImagen2').change(function()
                {
                    var fileExtension = "";
                    document.getElementById("barInv2").value = 0;
                    var file = $("#txtImagen2")[0].files[0];
                    var fileName = file.name;
                    document.getElementById("barInv2").value = 30;
                    fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
                    document.getElementById("barInv2").value = 70;
                    
                    if(NomImgAnt == ""){
                        var d = new Date(); 
                        NI = d.getDate() + "" + (d.getMonth() +1) + "" + d.getFullYear() + '' +d.getHours()+''+d.getMinutes()+''+d.getSeconds();
                        NomImgAnt = NI;
                    }else{
                        NomImgAnt = NomImgAnt.substring(0, NomImgAnt.length-4);
                    }
                    
                    NomImg = NomImgAnt+"."+fileExtension;
                    
                    if(isImage(fileExtension)){
                        var formData = new FormData($(".frmInv2")[0]);
                        $.ajax({
                            url: servidor+'upload.php?n='+NomImgAnt+"&e="+fileExtension,  
                            type: 'POST',
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            //una vez finalizado correctamente
                            success: function(data){
                                document.getElementById("barInv2").value = 100;
                                document.getElementById("imgInv2").setAttribute("src",servidor+"imagenes/"+data);
                            },
                            //si ha ocurrido un error
                            error: function(){
                                alert("error img");
                            }
                        });
                    }
                    else{
                        alert("solo se permiten imagenes");
                    }
                });

                function isImage(extension)
                {
                    switch(extension.toLowerCase()) 
                    {
                        case 'jpg': case 'gif': case 'png': case 'jpeg':
                            return true;
                        break;
                        default:
                            return false;
                        break;
                    }
                }

            });
            
            

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
            
            function initTablaTipo(){
                //CONVERTIMOS NUESTRO LISTADO DE LA FORMA DEL JQUERY.DATATABLES- PASAMOS EL ID DE LA TABLA
                $('#tablaTipo').dataTable({
                    "sPaginationType": "full_numbers" //DAMOS FORMATO A LA PAGINACION(NUMEROS)
                });
            }

            function init(){

                listaCategorias();
                listaInv();
                listaTipo();
            }
            
            function consultaTipo(){
                var val = document.getElementsByName("cboxCategoria")[0].value;
                listaTiposInv_id(val);
            }
            
            function jsonRegistroInv(nom,cant,valor,img,idTipo) {
                return JSON.stringify({
                    "nombre": nom,
                    "cantidad": cant,
                    "valor": valor,
                    "imagen": img,
                    "idTipo": idTipo,
                    "imagen": img
                    });
            }
            
            function jsonRegistroCat(nom) {
                return JSON.stringify({
                    "nombre": nom
                    });
            }
            
            function jsonRegistroTip(nom,idCat) {
                return JSON.stringify({
                    "nombre": nom,
                    "idCategoria": idCat
                    });
            }
            
            function modificarInv(){
                var nom = document.getElementsByName("txtNombreInvModi")[0].value;
                var cant = document.getElementsByName("txtCantidadInvModi")[0].value;
                var valor = document.getElementsByName("txtValorInvModi")[0].value;
                var img = NomImg;
                var idTipo = document.getElementsByName("cboxTipoModi")[0].value;
                jQuery.ajax({
                     type: "PUT",
                     url: servidor+"inventario/"+idInv,
                     dataType: "json",
		     data: jsonRegistroInv(nom,cant,valor,img,idTipo),
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                             //Mensaje de guardado
                             //document.getElementById("divInfoInv").innerHTML = "<label style='color: green;'>Modificado correctamente</label>";
                             
                             $('#tablaInv').dataTable().fnDestroy();
                             listaInv();
                             var d = new Date(); 
                             NI = d.getDate() + "" + (d.getMonth() +1) + "" + d.getFullYear() + '' +d.getHours()+''+d.getMinutes()+''+d.getSeconds();
                             document.getElementById("barInv2").value = 0;
                             document.getElementById("imgInv2").setAttribute("src","img/producto_sin_foto.jpg");
                             alert("Modificado Correctamente");
                         }
                         closeDialog();
                     },
                     error: function (jqXHR, status) {
                         alert("error inventario");
                         closeDialog();
                     }
                });
            }
            
            function modificarCat(){
                var nom = document.getElementsByName("txtNombreCat")[0].value;
                jQuery.ajax({
                     type: "PUT",
                     url: servidor+"categoria/"+idCat,
                     dataType: "json",
		     data: jsonRegistroCat(nom),
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                             //mensaje guardado
                             //document.getElementById("divInfoCat").innerHTML = "<label style='color: green;'>Modificado correctamente</label>";
                             
                             cancelarCat();
                             $('#tablaCat').dataTable().fnDestroy();
                             listaCategorias();
                             alert("Modificado correctamente");
                         }
                     },
                     error: function (jqXHR, status) {
                         alert("error categoria");
                     }
                });
            }
            
            function modificarTipo(){
                var nom = document.getElementsByName("txtNombreTipo")[0].value;
                var idCat = document.getElementsByName("cboxCategoria2")[0].value;
                jQuery.ajax({
                     type: "PUT",
                     url: servidor+"tipoInventario/"+idTipo,
                     dataType: "json",
		     data: jsonRegistroTip(nom,idCat),
                     success: function (data, status, jqXHR) {

                         if(data.estado == 1){
                             //mensaje guardado
                             //document.getElementById("divInfoTipo").innerHTML = "<label style='color: green;'>Modificado correctamente</label>";
                             
                             cancelarTipo();
                             $('#tablaTipo').dataTable().fnDestroy();
                             listaTipo();
                             alert("Modificado correctamente");
                         }
                     },
                     error: function (jqXHR, status) {
                         alert("error tipo");
                     }
                });
            }
            
            function registrarTipo(){
                var nom = document.getElementsByName("txtNombreTipo")[0].value;
                var idCat = document.getElementsByName("cboxCategoria2")[0].value;
                jQuery.ajax({
                     type: "POST",
                     url: servidor+"tipoInventario",
                     dataType: "json",
		     data: jsonRegistroTip(nom,idCat),
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                             //mensaje guardado
                             //document.getElementById("divInfoTipo").innerHTML = "<label style='color: green;'>Guardado correctamente</label>";
                             
                             $('#tablaTipo').dataTable().fnDestroy();
                             listaTipo();
                             
                             alert("Guardado correctamente");
                         }
                     },
                     error: function (jqXHR, status) {
                         alert("error Tipo");
                     }
                });
            }
            
            function registrarCat(){
                var nom = document.getElementsByName("txtNombreCat")[0].value;
                jQuery.ajax({
                     type: "POST",
                     url: servidor+"categoria",
                     dataType: "json",
		     data: jsonRegistroCat(nom),
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                             //mensaje guardado
                             //document.getElementById("divInfoCat").innerHTML = "<label style='color: green;'>Guardado correctamente</label>";
                             
                             $('#tablaCat').dataTable().fnDestroy();
                             listaCategorias();
                             alert("Guardado correctamente");
                         }
                     },
                     error: function (jqXHR, status) {
                         alert("error categoria");
                     }
                });
            }
            
            function registrarInv() {
                var nom = document.getElementsByName("txtNombre")[0].value;
                var cant = document.getElementsByName("txtCantidad")[0].value;
                var valor = document.getElementsByName("txtValor")[0].value;
                var img = NomImg1;
                var idTipo = document.getElementsByName("cboxTipo")[0].value;
                jQuery.ajax({
                     type: "POST",
                     url: servidor+"inventario",
                     dataType: "json",
		     data: jsonRegistroInv(nom,cant,valor,img,idTipo),
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                             //Mensaje de guardado
                             //document.getElementById("divInfoInv").innerHTML = "<label style='color: green;'>Guardado correctamente</label>";
                             
                             $('#tablaInv').dataTable().fnDestroy();
                             listaInv();
                             var d = new Date(); 
                             NI = d.getDate() + "" + (d.getMonth() +1) + "" + d.getFullYear() + '' +d.getHours()+''+d.getMinutes()+''+d.getSeconds();
                             document.getElementById("barInv1").value = 0;
                             document.getElementById("imgInv1").setAttribute("src","img/producto_sin_foto.jpg");
                             NomImg1 = "";
                             alert("Guardado correctamente");
                         }
                     },
                     error: function (jqXHR, status) {
                         alert("error inventario");
                     }
                });
            } 
            
            function eliminarInv() {
                jQuery.ajax({
                     type: "DELETE",
                     url: servidor+"inventario/"+idInv,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                             //Mensaje de guardado
                             //document.getElementById("divInfoInv").innerHTML = "<label style='color: green; width: 100%'>Eliminado correctamente</label>";
                             
                             $('#tablaInv').dataTable().fnDestroy();
                             listaInv();
                             alert("Eliminado correctamente");
                         }
                         if(data.estado == -1){
                             //document.getElementById("divInfoInv").innerHTML = "<label style='color: red; width: 100%'>Error, este elemento esta asociado a otro elemento </label>";
                             alert("Error, este elemento esta asociado a otro elemento");
                         }
                         if(data.estado == 0){
                             //document.getElementById("divInfoInv").innerHTML = "<label style='color: red; width: 100%'>Error de conexion, por favor intente mas tarde.</label>";
                             alert("Error de conexion, por favor intente mas tarde.");
                         }
                         closeDialog();
                     },
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
            }
            
            function eliminarCat() {
                jQuery.ajax({
                     type: "DELETE",
                     url: servidor+"categoria/"+idCat,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                             //Mensaje de guardado
                             //document.getElementById("divInfoCat").innerHTML = "<label style='color: green;'>Eliminado correctamente</label>";
                             
                             
                             $('#tablaCat').dataTable().fnDestroy();
                             listaCategorias();
                             cancelarElimi(0);
                             alert("Eliminado correctamente");
                         }
                         if(data.estado == -1){
                             //document.getElementById("divInfoCat").innerHTML = "<label style='color: red;'>Error, esta categoria esta asociada a un elemento \n\
                              //                                                   del 'tipo de inventario'.</label>";
                             alert("Error, esta categoria esta asociada a un elemento del 'tipo de inventario'.");
                         }
                         if(data.estado == 0){
                             //document.getElementById("divInfoCat").innerHTML = "<label style='color: red;'>Error de conexion, por favor intente mas tarde.</label>";
                             alert("Error de conexion, por favor intente mas tarde.");
                         }
                     },
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
            }
            
            function eliminarTipo() {
                jQuery.ajax({
                     type: "DELETE",
                     url: servidor+"tipoInventario/"+idTipo,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         if(data.estado == 1){
                             //Mensaje de guardado
                             //document.getElementById("divInfoTipo").innerHTML = "<label style='color: green;'>Eliminado correctamente</label>";
                             
                             
                             $('#tablaTipo').dataTable().fnDestroy();
                             listaTipo();
                             cancelarElimi(1);
                             alert("Eliminado correctamente");
                         }
                         if(data.estado == -1){
                             //document.getElementById("divInfoTipo").innerHTML = "<label style='color: red;'>Error, este tipo esta asociado a un elemento \n\
                               //                                                  del 'inventario'.</label>";
                             alert("Error, este tipo esta asociado a un elemento del 'inventario'.");
                         }
                         if(data.estado == 0){
                             document.getElementById("divInfoTipo").innerHTML = "<label style='color: red;'>Error de conexion, por favor intente mas tarde.</label>";
                             alert("Error de conexion, por favor intente mas tarde.");
                         }
                     },
                     error: function (jqXHR, status) {
                         alert("error");
                     }
                });
            }
            
            function cancelarTipo(){
                document.getElementsByName("btnCancelarTipo")[0].style.visibility = "hidden";
                document.getElementsByName("txtNombreTipo")[0].value = "";
                var frmCat = document.getElementsByName("frmTipo")[0];
                var btnCat = document.getElementsByName("btnTipo")[0];
                
                btnCat.innerHTML = "Registrar";
                frmCat.setAttribute("action","javascript:registrarTipo();");
            }
            
            function dialogModiTipo(i){
                //alert(dataInv[i].Nombre);
                document.getElementsByName("txtNombreTipo")[0].value = dataTipo[i].Nombre;
                document.getElementsByName("btnCancelarTipo")[0].style.visibility = "visible";
                
                var combo = document.getElementsByName("cboxCategoria2")[0];
                var cantidad = combo.length;
                
                for (var j = 0; j < cantidad; j++) {
                   if (combo[j].text == dataTipo[i].Categoria) { 
                       combo[j].selected = true;
                   }   
                }
                
                var frmCat = document.getElementsByName("frmTipo")[0];
                var btnCat = document.getElementsByName("btnTipo")[0];
                
                btnCat.innerHTML = "Actualizar";
                frmCat.setAttribute("action","javascript:modificarTipo();");

                idTipo= dataTipo[i].Id;
                
                
            }
            
            function cancelarCat(){
                document.getElementsByName("btnCancelarCat")[0].style.visibility = "hidden";
                document.getElementsByName("txtNombreCat")[0].value = "";
                var frmCat = document.getElementsByName("frmCat")[0];
                var btnCat = document.getElementsByName("btnCategoria")[0];
                
                btnCat.innerHTML = "Registrar";
                frmCat.setAttribute("action","javascript:registrarCat();");
            }
            
            function dialogModiCat(i){
                //alert(dataInv[i].Nombre);
                document.getElementsByName("txtNombreCat")[0].value = dataCat[i].Nombre;
                document.getElementsByName("btnCancelarCat")[0].style.visibility = "visible";
                
                var frmCat = document.getElementsByName("frmCat")[0];
                var btnCat = document.getElementsByName("btnCategoria")[0];
                
                btnCat.innerHTML = "Actualizar";
                frmCat.setAttribute("action","javascript:modificarCat();");

                idCat= dataCat[i].Id;
                
                
            }
            
            function dialogModiIn(i){
                //alert(dataInv[i].Nombre);
                document.getElementsByName("txtNombreInvModi")[0].value = dataInv[i].Nombre;
                document.getElementsByName("txtCantidadInvModi")[0].value = dataInv[i].Cantidad;
                document.getElementsByName("txtValorInvModi")[0].value = dataInv[i].Valor;
                idInv = dataInv[i].Id;
                
                var img = "img/producto_sin_foto.jpg";
                NomImgAnt = "";
                if(dataInv[i].Imagen != null && dataInv[i].Imagen != ""){
                    img = servidor+"imagenes/"+dataInv[i].Imagen;
                    NomImgAnt = dataInv[i].Imagen;
                    
                }
                NomImg = NomImgAnt;
                document.getElementById("imgInv2").setAttribute("src",img);
                
                var combo = document.getElementsByName("cboxTipoModi")[0];
                var cantidad = combo.length;
                
                for (var j = 0; j < cantidad; j++) {
                   if (combo[j].text == dataInv[i].Tipo) { 
                       combo[j].selected = true;
                   }   
                }
  
                openDialog(2);
            }
            
            function cancelarElimi(i){
                if(i==0){
                    document.getElementById("divInfoCat").innerHTML = "";
                }
                if(i == 1){
                    document.getElementById("divInfoTipo").innerHTML = "";
                }
            }
            
            function dialogElimTipo(i){
                idTipo = dataTipo[i].Id;
                
                document.getElementById("divInfoTipo").innerHTML = "<label style='font-size: 14px; \n\
                        display: block; text-align: center;'>\n\
                        ¿Deseas eliminar el item seleccionado?</label> <br/><br/>\n\
                        <label style='font-size: 14px; color: #aaa; position: relative; \n\
                        top: -40px; display: block; text-align: center;'>"+dataTipo[i].Nombre+"</label>\n\
                        <a style='background-color: #5CD053; width:80px; margin-left: 60px; \n\
                        padding: 2px; cursor:pointer; position:relative; top: -25px;'\n\
                        onclick='javascript:eliminarTipo();'>Aceptar</a> \n\
                        <a style='background-color: #E74C3C; width:80px; position:relative; top: -25px; left:20px; \n\
                        padding: 2px; cursor:pointer;' \n\
                        onclick='javascript:cancelarElimi(1)'>Cancelar</a>";
            }
            
            function dialogElimCat(i){
                idCat = dataCat[i].Id;
                
                document.getElementById("divInfoCat").innerHTML = "<label style='font-size: 14px; \n\
                        display: block; text-align: center;'>\n\
                        ¿Deseas eliminar el item seleccionado?</label> <br/><br/>\n\
                        <label style='font-size: 14px; color: #aaa; position: relative; \n\
                        top: -40px; display: block; text-align: center;'>"+dataCat[i].Nombre+"</label>\n\
                        <a style='background-color: #5CD053; width:80px; margin-left: 60px; \n\
                        padding: 2px; cursor:pointer; position:relative; top: -25px;'\n\
                        onclick='javascript:eliminarCat();'>Aceptar</a> \n\
                        <a style='background-color: #E74C3C; width:80px; position:relative; top: -25px; left:20px; \n\
                        padding: 2px; cursor:pointer;' \n\
                        onclick='javascript:cancelarElimi(0)'>Cancelar</a>";
            }
            
            function listaInv() {
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"inventario",
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         var tb = document.getElementById("tbody_tablaInv");
                         tb.innerHTML = "";
                         if(data != null){
                             dataInv = data;
                             for(var i = 0; i < data.length ; i++){
                                 tb.innerHTML += "<tr>\n\
                                 <td>"+data[i].Id+"</td>\n\
                                 <td>"+data[i].Nombre+"</td>\n\
                                 <td>"+data[i].Cantidad+"</td>\n\
                                 <td>"+data[i].Valor+"</td>\n\
                                 <td>"+data[i].Tipo+"</td>\n\
                                 <td><a onclick='javascript:dialogModiIn("+i+");' class='btnModi'>Modificar</a></td>\n\
                                 <td><a onclick='javascript:dialogInvEli("+i+");' class='btnElim'>Eliminar</a></td>\n\
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

            function listaTipo() {
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"tipoInventario",
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         var tb = document.getElementById("tbody_tablaTipo");
                         var cbox = document.getElementsByName("cboxTipoModi")[0];
                         var cbox2 = document.getElementsByName("cboxTipo")[0];
                         tb.innerHTML = "";
                         cbox2.innerHTML = "";
                         cbox.innerHTML = "";
                         if(data != null){
                             dataTipo = data;
                             for(var i = 0; i < data.length ; i++){
                                 cbox.innerHTML += "<option value = '"+data[i].Id+"' >"+data[i].Nombre+"</option>";
                                 cbox2.innerHTML += "<option value = '"+data[i].Id+"' >"+data[i].Nombre+"</option>";
                                 tb.innerHTML += "<tr>\n\
                                 <td>"+data[i].Id+"</td>\n\
                                 <td>"+data[i].Nombre+"</td>\n\
                                 <td>"+data[i].Categoria+"</td>\n\
                                 <td><a onclick='dialogModiTipo("+i+")' class='btnModi'>Modificar</a></td>\n\
                                 <td><a onclick='dialogElimTipo("+i+")' class='btnElim'>Eliminar</a></td>\n\
                                 </tr>";
                             }
                         }
                         initTablaTipo();
                     },
                     error: function (jqXHR, status) {
                         alert("erggror");
                     }
                });
            }
            
            function listaCategorias() {
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"categoria",
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
                                 <td><a onclick='javascript:dialogModiCat("+i+");' class='btnModi'>Modificar</a></td>\n\
                                 <td><a onclick='javascript:dialogElimCat("+i+");'class='btnElim'>Eliminar</a></td>\n\
                                 </tr>";
                             }
                             consultaTipo();
                             
                         }
                         initTablaCat();
                     },
                     error: function (jqXHR, status) {
                         alert("erggror");
                     }
                });
            }
            
            function listaTiposInv_id(idCat) {
                jQuery.ajax({
                     type: "GET",
                     url: servidor+"tipoInventario/"+idCat,
                     dataType: "json",
                     success: function (data, status, jqXHR) {
                         var cbox = document.getElementsByName("cboxTipo")[0];
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
            
            function dialogInvEli(i){
                document.getElementById("lblCancelarInv").innerHTML = dataInv[i].Nombre;
                idInv = dataInv[i].Id;
                openDialog(0);
            }
            
            function openDialog(ban) {
                if(ban == 0){
                    Avgrund.show( "#msg-popup" );
                }
                if(ban == 1){
                    cancelarElimi(0);
                    
                    Avgrund.show( "#categoria-popup" );
                }
                if(ban == 2){
                    Avgrund.show( "#modiInv-popup" );
                }
                if(ban == 3){
                    cancelarElimi(1);
                    Avgrund.show( "#tipo-popup" );
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
                  <div class="link_title activo">
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
            <div class="rectangle"><h2>Inventario</h2></div> 
            <div class="triangle-l"></div>

                <!--<button onclick="javascript:openDialog();">Open Avgrund</button>-->
                <button style="position: relative; left: -305px; top: 245px; z-index: 400;" id="btnGesCat" onclick="javascript:openDialog(1);">Gestionar categorias</button><br/>
                <button style="position: relative; left: 85px; top: 243px; z-index: 400;" id="btnGesTipo" onclick="javascript:openDialog(3);">Gestionar Tipo</button>
                <form class="contact_form frmInv1" enctype="multipart/form-data" name="frm_inv" action="javascript:registrarInv();" method="post">
                    <div id="div_" style="position: relative; top: -70px;">
                        <div class="div_form">
                            <label>Nombre</label><br /> <br /> 
                            <input name="txtNombre" type="text"  required />
                        </div>

                        <div class="div_form">
                            <label>Cantidad</label><br /> <br /> 
                            <input name="txtCantidad" type="number"  required />
                        </div>

                        <div class="div_form">
                            <label>Costo</label><br /> <br /> 
                            <input name="txtValor" type="number"   required />
                        </div>
                        
                        <div class="div_form">
                            <label>Categoria</label><br /> <br /> 
                            <select name="cboxCategoria" required onchange="javascript:consultaTipo();">
                            </select>
                        </div>
                        
                        <div class="div_form">
                            <label>Tipo</label><br /> <br /> 
                            <select name="cboxTipo" required>
                            </select>
                        </div>
                        
                        <div class="div_form">
                            <label>Imagen</label><br /> <br /> 
                            <img id="imgInv1" class="imbInv" src="./img/producto_sin_foto.jpg"/> <br />
                            <input  style="margin-top: 10px; border: none; box-shadow:none;" id="txtImagen" name="txtImagen" type="file" />
                            <br />
                            <progress id="barInv1" style="position: relative; left: 15px;" value="0" max="100"></progress>
                        </div>
                        <br/>
                        <button style="position: relative; left: -75px; top: -135px;" id="btnRegistrarInv" onclick="javascript:function(){document.frm_inv.submit();}" >Registrar</button>
                        
                        <div id="divInfoInv">
                            
                        </div>
                        <h2 id="lblDatos"  style="position: relative; top: -40px; color:#555555; background-color: transparent; width: 400px;">Lista inventario</h2>
                        <div id="div_tablaInv">
                          <table class="display" id="tablaInv">
					<thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Nombre</th>
                                                <th>Cantidad</th>
                                                <th>Valor</th>
                                                <th>Tipo</th>
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
					<tbody id="tbody_tablaInv">
                                            
					</tbody>
				</table>
                        </div>
                    </div>
                </form>
                
        </div>
        
        </article>
        
        <aside id="tipo-popup" class="avgrund-popup">
            <h2  style="position: relative;left: 20px; top: 10px; color:#555555; width: 400px;">Gestion Tipos de categorias</h2>
            <div id="div_tipo">
                
                <form class="contact_form" name="frmTipo" action="javascript:registrarTipo();" method="post">
                    <br/> <br/>
                    <div class="div_tip">
                        <label>Categoria</label><br /> <br /> 
                        <select name="cboxCategoria2">
                        </select>
                        <br />
                        <label style="width: 300px;">Nombre tipo</label><br /><br /> 
                        <input name="txtNombreTipo" type="text"  required />
                        <br/>
                        <button id="btnCategoria" name="btnTipo" onclick="javascript:function(){document.frmTipo.submit();}" >Registrar</button>
                        <br/>
                    </div>
                </form>
                <button class="cance" id="btnCancelarTipo" name="btnCancelarTipo" onclick="javascript:cancelarTipo()" style="visibility: hidden; top:80px;">Cancelar</button>
                <div id="divInfoTipo">
                    
                </div>
                
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
                </div>
            </div>
        </aside>
            
        <aside id="categoria-popup" class="avgrund-popup">
            <h2  style="position: relative;left: 20px; top: 10px; color:#555555; width: 400px;">Gestion Categorias</h2>
            <div id="div_categoria">
                <form class="contact_form" name="frmCat" action="javascript:registrarCat();" method="post">
                    <br/> <br/>
                    <div class="div_cat">
                        <label style="width: 300px;">Nombre categoria</label><br /><br /> 
                        <input type="text" name="txtNombreCat" required />
                        <br/>
                        <button id="btnCategoria" name="btnCategoria" onclick="javascript:function(){document.frmCat.submit();}" >Registrar</button>
                        <br/>                        
                    </div>
                </form>
                <button class="cance" id="btnCancelarCat" name="btnCancelarCat" onclick="javascript:cancelarCat()" style="visibility: hidden; top: 45px;">Cancelar</button>
                <div id="divInfoCat">
                    
                </div>
                
                <div class="div_tabla" id="tC">
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
        </aside>
        
        <aside id="msg-popup" class="avgrund-popup">
            <label class="lblEliminar">¿Deseas eliminar el item seleccionado?</label>
            <label id="lblCancelarInv" class="lblEliminar"></label>
            <a onclick="javascript:eliminarInv();" class="aInvElimi">Aceptar</a> <br/>
            <a onclick="javascript:closeDialog();" id="btnCan" class="aInvElimi">Cancelar</a>
        </aside>
        
        <aside id="modiInv-popup" class="avgrund-popup">
            <form class="contact_form frmInv2" name="frmModiInv" action="javascript:modificarInv();" method="post">
                <label style="width: 300px;">Nombre</label><br /> <br />
                <input name="txtNombreInvModi" type="text"  placeholder="Purificador" required />
                <div id="div_modiInv">
                    <label>Imagen</label><br /> <br /> 
                    <img id="imgInv2" class="imbInv"/>
                    <br /> <br /> 
                    <input id="txtImagen2"  style="border: none; box-shadow:none; -moz-transition: none; 
                                    -webkit-transition: none; 
                                    -o-transition: none;
                                    transition: none;  padding-right:0px;" name="txtImagen" type="file" />
                    <br/>
                    <progress id="barInv2" style="position: relative; left: 15px;" value="0" max="100"></progress>
                </div>
                <br /> 
                <label style="width: 300px;">Catidad</label><br /> <br />
                <input name="txtCantidadInvModi" type="number"  placeholder="10" required />
                <br /> 
                <label style="width: 300px;">Valor</label><br /> <br />
                <input name="txtValorInvModi" type="number"  placeholder="10000" required />
                <br /> 
                <label>Tipo</label><br /> <br /> 
                <select name="cboxTipoModi" >
                </select>
                <br/>
                <button id="btnCategoria" onclick="javascript:function(){document.frmModiInv.submit();}" >Registrar</button>
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