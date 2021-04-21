$(function(){

    $('#sidebar, #content').toggleClass('active');
    let edit = false;
    loadAgencies();
    $('#result-search').hide();
    $('#content_info').hide();

    
    //Button Nuevo usuario
    $("#formButton").click(function(){
        edit = false;
        $("#crud-form").toggle('slow');
        $("#formButton").hide();
        $('#agencyForm').trigger('reset');
        $('.alert-msg').hide();
        document.getElementById("resultSearch").className = "col-lg-9";
    });
    
    // Btn cancelar Modal
    $('#cancelButtonModal').on("click", function(e){
        e.preventDefault();
        $('#exampleModal').modal('hide');
        $('#agenciDocsModal').trigger('reset');
        $('#content_info').hide();
        $("#loadedfiles").html('');
        $('#saveButtonDoc').show();
        $('#storaged_documents').html('');
    });

    //Button cancelar form
    $("#cancelButton").click(function(){
        $("#crud-form").hide('slow');
        $("#formButton").show();
        $('#agencyForm').trigger('reset');
        document.getElementById("resultSearch").className = "col-lg-12";
    });
    //Button cancelar form edit
    $("#cancelButtonEdit").click(function(){
        $("#crud-form-edit").hide('slow');
        $("#formButton").show();
        $('#agencyForm').trigger('reset');
        document.getElementById("resultSearch").className = "col-lg-12";
    });


    //Btn x de alert mensaje
    $("#alert-close").click(function(){
        $(".alert-msg").hide('slow');
    });

    /* Buscar agencia */
    $('#search').keyup(function(){
        if($('#search').val()) {
            let data = $('#search').val();
            let search = 'search';
            if (/'/.test(data)) {
                alert('Esta ingresando caracteres no permitidos');
                $('#search').val('');
                $('#result-search').hide('slow');
                $("#table-data").show('slow');
                $('#search').focus();
                return false;
            }
            const postData = {
                'data': data,
                'search': search,
            }
            $.ajax({
                url:'../../helpers/agencias.php',
                type:'POST',
                data:postData,
                beforeSend: function(){
                    let template = '';
                    template += `
                    <div class="col-lg-4 col-md-3">
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="spinner-grow text-dark" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="spinner-grow text-secondary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="spinner-grow text-dark" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    <div class="col-lg-4 col-md-3">
                    </div>
                        
                    `;
                    
                    $('#container').html(template);
                    $("#table-data").hide('slow');
                    $('#result-search').show('slow');
                },
                success: function(response){
                    if (!response.error) {
                        let agencies = JSON.parse(response);
                        if (agencies == '') {  
                            let template = '';
                            template += `
                            <div class=' p-2  col-lg-12 text-right'>
                                <a href="#" class='btn btn-black btn-sm' id='cerrar_results'>X</a>
                            </div>
                                <div class="col-lg-12">
                                    <p>No se encontro ninguna agencia que coincida.</p>
                                </div>
                                ` ;
                                $('#container').html(template);
                                $('#result-search').show();
                        }else{
                            let template = '';
                            template += `
                            <div class=' p-2  col-lg-12 text-right'>
                                <a href="#" class='btn btn-black btn-sm' id='cerrar_results'>X</a>
                            </div>
                            <table class='table table-hover table-bordered table-sm' cellspacing='0' id='tablaUsuarios'>
                            <thead class='thead-dark'>
                                <tr>
                                    <th>ID</th>
                                    <th>Agencia</th>
                                    <th>Email Contacto</th>
                                    <th>Email Pago</th>
                                    <th>Usuario</th>
                                    <th>Teléfono</th>
                                    <th>Fecha registro</th>
                                    <th>Docs</th>
                                    <th>CASH</th>
                                    <th>CARD</th>
                                    <th>PAYPAL</th>
                                    <th>TODAY</th>
                                    <th>Yamevi</th>
                                    <th></th>
                                    <th></th>
                                    </tr>
                            </thead>
                            <tbody>`;
                            agencies.forEach(agencies => {
                                template += `
                                <tr agency-id='${agencies.id_agency}'>
											<td>${agencies.id_agency}</td>
											<td>${agencies.name_agency}</td>
											<td>${agencies.email_agency}</td>
											<td>${agencies.email_agency_pay}</td>
											<td id='usuario'>${agencies.username}</td>
											<td>${agencies.phone_agency}</td>
											<td>${agencies.register_date}</td>
											<td class='text-center'><a href='#' id='load_docs' class='btn btn-sm btn-black' datagen='${agencies.id_agency}' data-toggle='modal' data-target='#exampleModal' title='Subir documentos'><i class='fas fa-file-upload'></i></a></td>
											
                                            <td>
												<div class='form-check '>
													<input type='checkbox' class='settingCash' data-cash='${agencies.id_agency}' ${agencies.checkedCash} ><br /> 
												</div>
											</td>
											<td>
												<div class='form-check'>
												<input type='checkbox' class='settingCard' data-card='${agencies.id_agency}' ${agencies.checkedCard} ><br /> 
												</div>
											</td>
											<td>
												<div class='form-check '>
												<input type='checkbox' class='settingPaypal' data-paypal='${agencies.id_agency}' ${agencies.checkedPaypal}><br /> 
												</div>
											</td>
											<td>
												<div class='form-check '>
                                                <input type='checkbox' class='settingToday' data-today='${agencies.id_agency}' ${agencies.checkedToday} ><br /> 
												</div>
											</td>
											<td>
												<div class='form-check '>
                                                <input type='checkbox' class='settingYT' data-yt='${agencies.id_agency}' ${agencies.checkedYT} ><br /> 
												</div>
											</td>
											<td class='text-center text-center'>
                                                <a href="#" class="agency-item btn btn-primary btn-sm " ><i class="fas fa-edit" ></i>
											</td>
											<td class='text-center text-center'>
                                            <a href="#" class="agency-delete-search btn btn-danger btn-sm "><i class="fas fa-trash-alt"></i></a>
											</td>
                                    </tr>
                                    ` ;  
                            });
                            $('#container').html(template);
                            $("#table-data").hide('slow');
                            $('#result-search').show('slow');
                        }
                    }
                }
            });
        }else{  
            $("#table-data").show('slow');
            $('#result-search').hide('slow'); 
            $("#crud-form").hide();
            $('#agencyForm').trigger('reset');
            $("#formButton").show();
            $("#crud-form-edit").hide('slow');
            $('#agencyForm').trigger('reset');
            document.getElementById("resultSearch").className = "col-lg-12";
        }
    });

    /* Copy email */
    $(document).on('click', '.copy_email', function(){
        let email;
        email = $(this).attr('title');
        alert(email);
    });

    // Add agency
    $('#crud-form').submit(function(e){
        var postDatas = {
            'id': $('#agency-id').val(),
            'name_agency': $('#nombre_agencia').val(),
            'email_agency': $('#email_agencia').val(),
            'email_agency_pay': $('#email_agencia_pay').val(),
            'phone_agency': $('#telefono_agencia').val(),
            'username': $('#usuario_agencia').val(),
            'register_date': $('#fecha_registro').val(),
            'password': $('#password').val(),
            'edit': 'false',

        };
        if (postDatas.name_agency == null || postDatas.name_agency.length == 0 || /^\s+$/.test(postDatas.name_agency)) {
            alert('El nombre de la Agencia es un campo obligatorio.');
            $('#nombre_agencia').focus();
            return false;
        }
        if (!(/\w+([-+.']\w+)*@\w+([-.]\w+)/.test(postDatas.email_agency))) {
			alert('En necesario ingresar una dirección de correo valida.');
			$('#email_agnecia').focus();
			return false;  
        }
        if (postDatas.username == null || postDatas.username.length == 0 || /^\s+$/.test(postDatas.username)) {
			alert('Es necesario contar con un nombre de usuario.');
			$('#usuario_agencia').focus();
			return false;
        }
        if (postDatas.password == null || postDatas.password.length == 0 || /^\s+$/.test(postDatas.password)) {
			alert('Asignar un password es obligatorio.');
			$('#password').focus();
			return false;
		}
        $.ajax({
            data: postDatas,
            url: '../../helpers/agencias.php',
            type: 'post',
            success: function(data){
                var json = $.parseJSON(data);
                $('#agencyForm').trigger('reset');
                $("#crud-form").hide();
                $("#formButton").show();
                $('#form-search').trigger('reset');
                $('#result-search').hide();
                $('.alert-msg').show();
                $('#text-msg').val(json.message);
                if (json.code == 1) {
                    loadAgencies();
                }
                document.getElementById("resultSearch").className = "col-lg-12";
            }
        });
        
        e.preventDefault();
    });

    // // Load docs 
    // $(document).ready(function() {
    //     $('#myfiles').on("change", function() {
    //         var myfiles = document.getElementById("myfiles");
    //         var files = myfiles.files;
    //         var data = new FormData();

    //         for (i = 0; i < files.length; i++) {
    //             data.append('file' + i, files[i]);
    //         }

    //         $.ajax({
    //             url: '../../model/agencias_docs.php', 
    //             type: 'POST',
    //             contentType: false,
    //             data: data,
    //             processData: false,
    //             cache: false,
    //             success: function(data){
    //                 $("#loadedfiles").append(data);
    //             }
    //         });
    //     });
    // });

    /*Modal DOCS */

    $(document).on('click', '#load_docs', function(){
        let element = $(this)[0];
        let id_agency = $(element).attr('datagen');

        //asignamos el id de agencia al input
        if (id_agency) {
            $('#id_agen').val(id_agency);
        }
        loadDocs(id_agency);
    });

    function loadDocs(id_agency){
        $('#storaged_documents').html('');
        let docs = 'add_docs';

        const postDatas = {
            'id_agency': id_agency,
            'add_docs': docs
        }
        $.post('../../helpers/agencias.php', postDatas, function(response){
            const docs = JSON.parse(response);
            $.each(docs, function(i){
                let file = docs[i].name_doc_complete;
                let ext = file.substring(file.lastIndexOf("."));
                let template = '';
                if (ext == ".pdf") {
                    template += `
                        <div class="col-lg-3 col-md-4 text-center pt-3" dataia= ${id_agency} datado="${docs[i].id_doc}" datana="${docs[i].name_doc_complete}">
                            <a href='../../docs/${docs[i].name_doc_complete}'  target="_blank" title='${docs[i].name_doc_complete}' data='' class='edit_img' id='add_img'><img src='../../assets/img/icon/icon_pdf.png' class='img-thumbnail '></a><br>
                            <a href="../../docs/${docs[i].name_doc_complete}"  target="_blank" title='${docs[i].name_doc_complete}'><small>${docs[i].name_doc} ...</small></a><br>
                            <small>${docs[i].date_register}</small><br>
                            <a href='#' id='btn-delete-doc' class='btn btn-danger btn-sm btn-block'>Eliminar</a>
                        </div>
                    `;
                    $('#storaged_documents').append(template);
                }
                if (ext == ".png" || ext == ".jpg" || ext == 'jpeg') {
                    
                    template += `
                        <div class="col-lg-3 col-md-4 text-center pt-3"dataia= ${id_agency}  datado="${docs[i].id_doc}" datana="${docs[i].name_doc_complete}">
                            <a href='../../docs/${docs[i].name_doc_complete}'  target="_blank" title='${docs[i].name_doc_complete}' data='' class='edit_img' id='add_img'><img src='../../assets/img/icon/icon_imge.png' class='img-thumbnail '></a><br>
                            <a href="../../docs/${docs[i].name_doc_complete}"   target="_blank" title='${docs[i].name_doc_complete}'><small>${docs[i].name_doc} ...</small></a><br>
                            <small>${docs[i].date_register}</small><br>
                            <a href='#' id='btn-delete-doc' class='btn btn-danger btn-sm btn-block'>Eliminar</a>
                        </div>
                    `;
                    $('#storaged_documents').append(template);
                }
            });
        });
    }
    // Add docs agencies
    $("#agenciDocsModal").on('submit', (function(e){
        $("#loadedfiles").html('');
        e.preventDefault();
        var myfiles = document.getElementById("myfiles");
            var files = myfiles.files;
            var data = new FormData();
            var id_agency = $('#id_agen').val();
            for (i = 0; i < files.length; i++) {
                data.append('file' + i, files[i]);
            }
            data.append('id_agency', id_agency);
            $.ajax({
                url: '../../model/agencias_docs.php', 
                type: 'POST',
                contentType: false,
                data: data,
                processData: false,
                cache: false,
                success: function(msg) {
                    $("#loadedfiles").append(msg);
                    loadDocs(id_agency);
                    loadAgencies();
                }
            });
    }));

    // Delete docs agencias
    $(document).on('click', '#btn-delete-doc', function(e){
        e.preventDefault();
        let element = $(this)[0].parentElement;
        let id = $(element).attr('datado');
        let id_a = $(element).attr('dataia');
        let name = $(element).attr('datana');
        let delet = 'delete_doc';
        const postData = {
            'id': id,
            'name_doc': name,
            'delete_doc': delet,
        }
        $.post('../../helpers/agencias.php', postData, function(response){
            loadAgencies();
            loadDocs(id_a);
            $("#loadedfiles").html('');
            $("#loadedfiles").append(response);

        });

    });

    // Change checkbox Cash agency
    $(document).on('click', '.settingCash', function(){
            var val = $(this).is(':checked') ? 1 : 0;
            var id = $(this).data('cash');
            var cash = {
                'id': id,
                'value': val,
                'setcashconf': 1
            };
            $.ajax({
                data: cash,
                url: '../../helpers/agencias.php',
                type: 'post',					
                beforeSend: function(){
                },
                success: function(data){
                    loadAgencies();
                    var res = $.parseJSON(data);
                    alert(res.message);
                }

            });
    });

     // Change checkbox card agency
     $(document).on('click', '.settingCard', function(){
        var val = $(this).is(':checked') ? 1 : 0;
        var id = $(this).data('card');
        var cash = {
            'id': id,
            'value': val,
            'setcardconf': 1
        };
        $.ajax({
            data: cash,
            url: '../../helpers/agencias.php',
            type: 'post',					
            beforeSend: function(){
            },
            success: function(data){
                loadAgencies();
                var res = $.parseJSON(data);
                alert(res.message);
            }

        });
    });

     // Change checkbox paypal agency
     $(document).on('click', '.settingPaypal', function(){
        var val = $(this).is(':checked') ? 1 : 0;
        var id = $(this).data('paypal');
        var cash = {
            'id': id,
            'value': val,
            'setpaypalconf': 1
        };
        $.ajax({
            data: cash,
            url: '../../helpers/agencias.php',
            type: 'post',					
            beforeSend: function(){
            },
            success: function(data){
                loadAgencies();
                var res = $.parseJSON(data);
                alert(res.message);
            }

        });
    });

     // Change checkbox today agency
     $(document).on('click', '.settingToday', function(){
        var val = $(this).is(':checked') ? 1 : 0;
        var id = $(this).data('today');
        var cash = {
            'id': id,
            'value': val,
            'settodayconf': 1
        };
        $.ajax({
            data: cash,
            url: '../../helpers/agencias.php',
            type: 'post',					
            beforeSend: function(){
            },
            success: function(data){
                loadAgencies();
                var res = $.parseJSON(data);
                alert(res.message);
            }

        });
    });

     // Change checkbox Yamevi agency
     $(document).on('click', '.settingYT', function(){
        var val = $(this).is(':checked') ? 1 : 0;
        var id = $(this).data('yt');
        var cash = {
            'id': id,
            'value': val,
            'setytconf': 1
        };
        $.ajax({
            data: cash,
            url: '../../helpers/agencias.php',
            type: 'post',					
            beforeSend: function(){
            },
            success: function(data){
                loadAgencies();
                var res = $.parseJSON(data);
                alert(res.message);
            }

        });
    });

    /* Edit agency  */
    $('#crud-form-edit').submit(function(e){
        const postDatas = {
            'id': $('#agency-id').val(),
            'name_agency': $('#nombre_agencia_edit').val(),
            'email_agency': $('#email_agencia_edit').val(),
            'email_agency_pay': $('#email_agencia_edit_pay').val(),
            'phone_agency': $('#telefono_agencia_edit').val(),
            'username': $('#usuario_agencia_edit').val(),
            'password': $('#password_edit').val(),
            'edit': 'true',

        };
        let url = '../../helpers/agencias.php';
        $.post(url, postDatas, function(response) {
            $("#table-data").show('slow');
            loadAgencies();
            $('#agencyFormEdit').trigger('reset');
            $("#crud-form-edit").hide();
            $("#formButton").show();
            $('#form-search').trigger('reset');
            $('#result-search').hide();
            $('.alert-msg').show();
            $('#text-msg').val(response);
            document.getElementById("resultSearch").className = "col-lg-12";
        });
        e.preventDefault();
    });
   
    // $('#crud-form').click(function(e){
        // e.preventDefault();
        // submitForm();
    // });

    // function submitForm(){
        // let url = edit === false ? 'model/user-add.php' : 'model/user-edit.php';
        // const postDatas = {
            //  id: $('#user-id').val(),
            //  nombre_usuario: $('#nombre_usuario').val(),
            //  apellido_paterno: $('#apellido_paterno').val(),
            //  username: $('#username').val(),
            //  email_usuario: $('#email_usuario').val(),
            //  password: $('#password').val(),
            //  telefono_usuario: $('#telefono_usuario').val(),
            //  role: $('#role').val()
        //  };
        //  $.ajax({
            // type: "POST",
            // url: url,
            // data: postDatas,
            // success: function(response){
                // if(!response.error){
                    // fetchUsers();
                    // console.log(response);
                    // $('#crud-form').trigger('reset');
                    // $("#exampleModal").modal('hide'); 
                // }
            // }
        //  });
    // }
    // 
    
    /* Nuevo Listar agencias */
    function loadAgencies(){
        function loadData(page){
            $.ajax({
                url  : "../../model/agencias_paginacion.php",
                type : "POST",
                cache: false,
                data : {page_no:page},
                beforeSend: function(){
                    let template = '';
                    template += `
                    <div class="row">
                        <div class="col-lg-4 col-md-3">
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="spinner-grow text-dark" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <div class="spinner-grow text-secondary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <div class="spinner-grow text-dark" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        <div class="col-lg-4 col-md-3">
                        </div>
                    </div>
                        
                    `;
                    $("#table-data").html(template);
                },
                success:function(response){
                $("#table-data").html(response);
                }
            });
        }
        loadData();
        // Pagination code
        $(document).on("click", ".pagination li a", function(e){
            e.preventDefault();
            var pageId = $(this).attr("id");
            loadData(pageId);
        });
        // New Ordenamiento de tabla
        $(document).on("click", "th", function(){
            var table = $(this).parents('table').eq(0)
            var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
            this.asc = !this.asc
            if (!this.asc) {
              rows = rows.reverse()
            }
            for (var i = 0; i < rows.length; i++) {
              table.append(rows[i])
            }
            setIcon($(this), this.asc);
          })
        
          function comparer(index) {
            return function(a, b) {
              var valA = getCellValue(a, index),
                valB = getCellValue(b, index)
              return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB)
            }
          }
        
          function getCellValue(row, index) {
            return $(row).children('td').eq(index).html()
          }
        
          function setIcon(element, asc) {
            $("th").each(function(index) {
              $(this).removeClass("sorting");
              $(this).removeClass("asc");
              $(this).removeClass("desc");
            });
            element.addClass("sorting");
            if (asc) element.addClass("asc");
            else element.addClass("desc");
          }
    }

    //Delete agencia
    $(document).on('click', '.agency-delete', function() {
       if (confirm('¿Esta seguro de querer eliminar la agencia?')) {
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('agency-id');
        let delet = 'delete';
        const postData = {
            'id': id,
            'delete': delet,
        }
        $.post('../../helpers/agencias.php', postData, function(response){
            loadAgencies();
            $('#form-search').trigger('reset');
            $('.alert-msg').show();
            $('#text-msg').val(response);

        });
           
       }
    });    
    //Delete agencia en busqueda
    $(document).on('click', '.agency-delete-search', function() {
       if (confirm('¿Esta seguro de querer eliminar al usuario?')) {
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('agency-id');
        let delet = 'delete';
        const postData = {
            'id': id,
            'delete': delet,
        }
        $.post('../../helpers/agencias.php', postData, function(response){
            $("#table-data").show('slow');
            loadAgencies();
            $('#result-search').hide();
            $('#form-search').trigger('reset');
            $('.alert-msg').show();
            $('#text-msg').val(response);
        });
           
       }
    });
    // trae valores para edit
    $(document).on('click','.agency-edit', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('agency-id');
        let single = 'single';
        let newrole = '';
        const postData = {
            'id': id,
            'single': single,
        }
        $.post('../../helpers/agencias.php',postData, function(response){
            loadAgencies();
            $("#crud-form-edit").show('slow');
            $("#crud-form").hide();
            $("#formButton").hide();
            document.getElementById("resultSearch").className = "col-lg-9";
            const agency = JSON.parse(response);
            $('#nombre_agencia_edit').val(agency.name_agency);
            $('#email_agencia_edit').val(agency.email_agency);
            $('#email_agencia_edit_pay').val(agency.email_agency_pay);
            $('#telefono_agencia_edit').val(agency.phone_agency);
            $('#usuario_agencia_edit').val(agency.username);
            $('#password_edit').val('');
            $('#agency-id').val(agency.id_agency);
            edit = true;
        });
    });
    //Traer valores de agencia del buscador Edit
    $(document).on('click', '.agency-item', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('agency-id');
        let single = 'single';
        const postData = {
            'id': id,
            'single': single,
        }
        $.post('../../helpers/agencias.php',postData, function(response){
            loadAgencies();
            $("#crud-form-edit").show('slow');
            $("#formButton").hide();
            document.getElementById("resultSearch").className = "col-lg-9";
            const agency = JSON.parse(response);
            $('#nombre_agencia_edit').val(agency.name_agency);
            $('#email_agencia_edit').val(agency.email_agency);
            $('#email_agencia_edit_pay').val(agency.email_agency_pay);
            $('#telefono_agencia_edit').val(agency.phone_agency);
            $('#usuario_agencia_edit').val(agency.username);
            $('#agency-id').val(agency.id_agency);
            edit = true;
        });
    });

});