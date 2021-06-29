$(function(){

    $('#sidebar, #content').toggleClass('active');
    let edit = false;
    loadAgencies();
    loadDiscount();
    $('#result-search').hide();
    $('#content_info').hide();
    $('#alert-msg-user').hide();
    $('#alert_msg').hide();

    
    //Button Nuevo usuario
    $("#formButton").click(function(){
        edit = false;
        $("#crud-form").toggle('slow');
        $("#formButton").hide();
        $('#agencyForm').trigger('reset');
        $('.alert-msg').hide();
        document.getElementById("resultSearch").className = "col-lg-9 col-md-6 col-sm-6";
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
        document.getElementById("resultSearch").className = "col-lg-12 col-md-12 col-sm-12";
    });
    //Button cancelar form edit
    $("#cancelButtonEdit").click(function(){
        $("#crud-form-edit").hide('slow');
        $("#formButton").show();
        $('#agencyForm').trigger('reset');
        document.getElementById("resultSearch").className = "col-lg-12 col-md-12 col-sm-12";
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
                                <div class=' p-1  col-lg-12 text-right'>
                                    <a href="#" class='btn btn-black btn-sm' id='cerrar_results'>X</a>
                                </div>
                                <table class='table table-hover table-bordered table-sm' cellspacing='0' id='tablaUsuarios'>
                                <thead class='thead-dark'>
                                    <tr>
                                        <th>ID</th>
                                        <th>Agencia</th>
                                        <th  class='hidden-sm'>Email Contacto</th>
                                        <th  class='hidden-sm'>Email Pago</th>
                                        <th>Usuario</th>
                                        <th>Teléfono</th>
                                        <th  class='hidden-sm'>Registro</th>
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
											<td  class='hidden-sm'>${agencies.email_agency}</td>
											<td  class='hidden-sm'>${agencies.email_agency_pay}</td>
											<td id='usuario'>${agencies.username}</td>
											<td>${agencies.phone_agency}</td>
											<td  class='hidden-sm'>${agencies.register_date}</td>
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
    $(document).on('click', '#copy_email', function(){
        let email;
        email = $(this).attr('title');
        alert(email);
    });
    $(document).on('click', '#copy_email_pay', function(){
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

    $(document).on('click', '#load_ep', function(){
        let element = $(this)[0];
        let id_agency = $(element).attr('datagen');
        let name_agency = $(element).attr('dataname');

        //asignamos el id de agencia al input
        if (id_agency) {
            $('#id_agen').val(id_agency);
        }
        $('#inp_id_agency').val(id_agency);
        $('#label_name_agency').text('Monedero Electrónico - '+name_agency);
        loadElectronicPurse(id_agency);
    });

    $(document).on('click', '#btn_new_balance', function(){
        $('.content_new_balance').show('slow');
        $('#btn_cancel_b').show('slow');
        $('#btn_new_balance').hide('slow');
    });
    $(document).on('click','.btn_cancel_balance', function(){
        $('.content_new_balance').hide('slow');
        $('#btn_cancel_b').hide('slow');
        $('#btn_new_balance').show('slow');
        $('#inp_monto').val('');
        $('#inp_motivo').val('');
    });

    $(document).on('click', '#btn_add_balance', function(){
        let id_agency = $('#inp_id_agency').val();
        const postDatas = {
            'id_user' : $('#inp_user').val(),
            'id_agency' : id_agency,
            'monto' : $('#inp_monto').val(),
            'motivo' : $('#inp_motivo').val(),
            'action': 'add_balance'
        };
        if (postDatas.monto == null || postDatas.monto.length == 0 || /^\s+$/.test(postDatas.monto)) {
            $('#inp_monto').addClass(" is-invalid");
            $('#inp_monto').focus();
            return false;
        }
        if (postDatas.motivo == null || postDatas.motivo.length == 0 || /^\s+$/.test(postDatas.motivo)) {
            $('#inp_motivo').addClass(" is-invalid");
            $('#inp_motivo').focus();
            return false;
        }
        $.ajax({
            url: '../../helpers/agencias.php',
            data: postDatas,
            type: 'POST',
            beforeSend: function(){
                $('#inp_monto').prop('disabled', true);
                $('#inp_motivo').prop('disabled', true);
                $('#btn_add_balance').html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>'); 
            },
            success: function(res){
                $('#btn_add_balance').html('Agregar'); 
                $('#inp_monto').prop('disabled', false);
                $('#inp_motivo').prop('disabled', false);
                if (res == 1) {
                    $('#alert_msg').addClass(' alert-success');
                    $('#text_alert_msg').html('<strong>Excelente!</strong> Se a agreado correctamente el saldo.');
                    $('#alert_msg').show();
                    loadAgencies();
                    loadElectronicPurse(id_agency);
                }else{
                    $('#alert_msg').addClass(' alert-danger');
                    $('#text_alert_msg').html('<strong>Error!</strong> Hubo un fallo al intentar agregar el saldo.');
                    $('#alert_msg').show();
                    loadElectronicPurse(id_agency);
                }
                $('.content_new_balance').hide('slow');
                $('#btn_cancel_b').hide('slow');
                $('#btn_new_balance').show('slow');
                $('#inp_monto').val('');
                $('#inp_motivo').val('');
                setTimeout(function(){ $('#alert_msg').hide('slow'); }, 2000);
            }
        });
    });

    $(document).on('click', '#delete_balance', function(){
        let element = $(this)[0];
        let id_electronic = $(element).attr('dataie');
        let id_agency = $(element).attr('dataagen');
        const postDatas = {
            'id_electronic' : id_electronic,
            'action': 'delete_balance'
        };
        
        if (confirm('¿Esta seguro de querer eliminar el saldo?')) {
            $.ajax({
                url: '../../helpers/agencias.php',
                data: postDatas,
                type: 'POST',
                beforeSend: function(){
                    $('#delete_balance').prop('disabled', true);
                    $('#delete_balance').html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>'); 
                },
                success: function(res){
                    $('#btn_add_balance').html("<i class='fas fa-trash-alt'></i>"); 
                    $('#btn_add_balance').prop('disabled', false);
                    if (res == 1) {
                        $('#alert_msg').addClass(' alert-success');
                        $('#text_alert_msg').html('<strong>Excelente!</strong> Se a eliminado el saldo correctamente.');
                        $('#alert_msg').show();
                        loadAgencies();
                        loadElectronicPurse(id_agency);
                    }else{
                        $('#alert_msg').addClass(' alert-danger');
                        $('#text_alert_msg').html('<strong>Error!</strong> Hubo un fallo al intentar eliminar el saldo.');
                        $('#alert_msg').show();
                        loadElectronicPurse(id_agency);
                    }
                    setTimeout(function(){ $('#alert_msg').hide('slow'); }, 2000);
                }
            });
        }
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
                        <div class="col-lg-2 col-md-3 text-center pt-3" dataia= ${id_agency} datado="${docs[i].id_doc}" datana="${docs[i].name_doc_complete}">
                            <a href='../../docs/${docs[i].name_doc_complete}'  target="_blank" title='${docs[i].name_doc_complete}' data='' class='edit_img' id='add_img'><img src='../../assets/img/icon/icon_pdf.png' class='img-thumbnail '></a><br>
                            <a href="../../docs/${docs[i].name_doc_complete}"  target="_blank" title='${docs[i].name_doc_complete}'><small>${docs[i].name_doc} ...</small></a><br>
                            <small>${docs[i].date_register}</small><br>
                            <a href='#' id='btn-delete-doc' class='btn btn-danger btn-sm btn-block'>Eliminar</a>
                        </div>
                    `;
                    $('#storaged_documents').append(template);
                }
                if (ext == ".png" || ext == ".jpg" || ext == '.jpeg') {
                    
                    template += `
                        <div class="col-lg-2 col-md-3 text-center pt-3"dataia= ${id_agency}  datado="${docs[i].id_doc}" datana="${docs[i].name_doc_complete}">
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

    function loadElectronicPurse(id_agency){
        const postDatas = {
            'id_agency': id_agency,
            'action': 'load_electronic_purse'
        }
        $.ajax({
            url:'../../helpers/agencias.php',
            data: postDatas,
            type: 'POST',
            success: function(res){
                const ep = JSON.parse(res);
                let template = "";
                if (ep != "") {
                    template += `
                        <div class='row'>
                            <div class='col-lg-9 col-md-8'>
                                <p>Saldo a favor total: <strong class='text-success'>$ ${ep[0].sum_amount_electronic}</strong></p>
                            </div>
                            <div class='col-lg-3 col-md-4'>
                                <a href='#' class='btn btn-sm  btn-block btn-success'  id='btn_new_balance'>Agregar Saldo</a>
                                <a href='#' class='btn btn-sm  btn-block btn-secondary mt-0 btn_cancel_balance' id="btn_cancel_b">Cancelar Saldo</a>
                            </div>
                        </div>
                        <div class='content_new_balance'>
                            <div class='row' >
                                <div class='col-lg-12 col-md-12'>
                                    <form class="form-inline">
                                        <div class="form-group mb-2">
                                            <label for="" class="sr-only">Monto</label>
                                            <input type="text" class="form-control" id="inp_monto" placeholder="Monto">
                                        </div>
                                        <div class="form-group mx-sm-3 mb-2">
                                            <label for="" class="sr-only">Motivo</label>
                                            <input type="text" class="form-control" id="inp_motivo" placeholder="Motivo de la adición del saldo">
                                        </div>
                                            <button type="button" id='btn_add_balance' class="btn btn-primary mb-2">Agregar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <table class="table w-100" >
                            <thead>
                                <tr>
                                <th style="width:30%" scope="col">Agencia</th>
                                <th style="width:10%" scope="col">Reservation</th>
                                <th class='hidden-sm' style="width:10%" scope="col">Usuario</th>
                                <th style="width:10%" scope="col">Motivo</th>
                                <th style="width:10%" scope="col">Saldo</th>
                                <th style="width:20%" scope="col">Fecha</th>
                                <th style="width:10%" scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                    `; 
                    ep.forEach(ep => {
                        template += `
                            
                        <tr dataie=${ep.id_electronic} dataagen="${ep.id_agency}" datause="${ep.id_user}">
                        <td><small>${ep.name_agency}</small></td>
                        <td><small>${ep.code_invoice}</small></td>
                        <td class='hidden-sm'><small>${ep.username}</small></td>
                        <td><small>${ep.descripcion_electronic}</small></td>
                        <td><small>${ep.amount_electronic}</small></td>
                        <td><small>${ep.date_register_electronic}</small></td>
                        <td><a href='#' id='delete_balance'dataie=${ep.id_electronic} dataagen="${ep.id_agency}" title='Eliminar Saldo' class='btn btn-danger btn-sm ' ><i class='fas fa-trash-alt'></i></a></td>
                        </tr>
                         `;
                    });
                    template += `
                              </tbody>
                            </table>
                    `;
                    $('#load_electronic_purse').html(template);
                }else{
                    template += `
                        <div class='row'>
                            <div class='col-lg-9 col-md-8'>
                                <p>Saldo a favor total: <strong class='text-dark'>$0.00</strong></p>
                            </div>
                            <div class='col-lg-3 col-md-4'>
                                <a href='#' id='btn_new_balance' class='btn btn-sm btn-block btn-success'>Agregar Saldo</a>
                                <a href='#' class='btn btn-sm  btn-block btn-secondary mt-0 btn_cancel_balance' id="btn_cancel_b">Cancelar Saldo</a>
                            </div>
                        </div>
                        <div class='content_new_balance'>
                            <div class='row'>
                                <div class='col-lg-12 col-md-12'>
                                    <form class="form-inline">
                                        <div class="form-group mb-2">
                                            <label for="" class="sr-only">Monto</label>
                                            <input type="text" class="form-control" id="inp_monto" placeholder="Monto">
                                        </div>
                                        <div class="form-group mx-sm-3 mb-2">
                                            <label for="" class="sr-only">Motivo</label>
                                            <input type="text" class="form-control" id="inp_motivo" placeholder="Motivo de la adición del saldo">
                                        </div>
                                        <button type="button" id='btn_add_balance' class="btn btn-primary mb-2">Agregar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                      <p><small>La agencia no tiene saldos a favor.</small></p>
                    `;
                    $('#load_electronic_purse').html(template);
        
                }
                $('#btn_cancel_b').hide();
                $('.content_new_balance').hide();
            }
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

     // Change checkbox Yamevi agency
     $(document).on('click', '.settingOperadora', function(){
        var val = $(this).is(':checked') ? 1 : 0;
        var id = $(this).data('op');
        var cash = {
            'id': id,
            'value': val,
            'setopconf': 1
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
    
    $('#inp_descuento_ao').on('input', function () { 
        this.value = this.value.replace(/[^0-9]./g,'');
    });
    $(document).on('click', '#btn_save_discount', function(){
        let val = $('#inp_descuento_ao').val();
        if (val == null || val.length == 0 || !(/^[0-9]/.test(val))) {
            $('#inp_descuento_ao').addClass(" is-invalid");
            $('#inp_descuento_ao').focus();
            return false;
        }
        const postDatas = {
            'value': val,
            'action': 'discount_ao'
        };
        $.ajax({
            data: postDatas,
            url:'../../helpers/agencias.php',
            type:'POST',
            success: function(data){
                let msg = "";
                if (data == 1) {
                    msg = "El descuento fue agregado correctamente a las agencias operadoras.";
                }else{
                    msg = "Error al agregar el descuento para las agencias operadoras";
                }
                loadDiscount();
                $('.alert-msg').show();
                $('#text-msg').val(msg);
                setTimeout(function(){ $('.alert-msg').hide('slow'); }, 2000);
            }
        });
    });
    function loadDiscount(){
        const postData = {
            'action': 'get_discount'
        };
        $.ajax({
            data: postData,
            url: '../../helpers/agencias.php',
            type: 'POST',
            success: function(data){
                if (data) {
                    $('#inp_descuento_ao').val(data);
                }
            }
        });
    }
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
    // Traer usuarios de cada agencia
    $(document).on('click', '#agency-users', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('agency-id');
        $('#inp_edit_user_agency').val(id);
        loadUsersAgency(id);
    });
    $(document).on('click', '#btn_close_usersa', function(){
        $('#modalUsersAgency').modal('hide');
        $("#content_users_agency").html('');
        $('.update_users').hide();
    });
    $(document).on('click', '#btn_edit_user', function(){
        $('.update_users').show('slow');
        $('#data_users_agency :input').prop("readonly", false);
        $('#data_users_agency :input').removeClass("form-control-plaintext");
        $('#data_users_agency :input').addClass("form-control");
        $('#btn_edit_user').hide();
        $('#btn_cancel_edit_user').css('display','block');
    });
    $(document).on('click', '#btn_cancel_edit_user', function(){
        $('.update_users').hide('slow');
        $('#data_users_agency :input').prop("readonly", true);
        $('#data_users_agency :input').addClass("form-control-plaintext");
        $('#data_users_agency :input').removeClass("form-control");
        $('#btn_edit_user').show();
        $('#btn_cancel_edit_user').css('display','none');
        let id_agency = $('#inp_edit_user_agency').val();
        setTimeout(function(){ loadUsersAgency(id_agency) }, 600);
    });
    $(document).on('click', '#btn_update_user', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('user-us');
        let id_agency = $('#inp_edit_user_agency').val();

        let val_name =$('#inp_user_agency_name').val();
        let val_last =$('#inp_user_agency_last').val();
        let val_email =$('#inp_user_agency_email').val();
        let val_phone =$('#inp_user_agency_phone').val();
        let val_username =$('#inp_user_agency_username').val();
        const postDatas = {
            'id': id,
            'val_name' : val_name,
            'val_last' : val_last,
            'val_email' : val_email,
            'val_phone' : val_phone,
            'val_username' : val_username,
            'action': 'update_data_user_agency'
        };
        
        if (postDatas.val_name == null || postDatas.val_name.length == 0 || /^\s+$/.test(postDatas.val_name)) {
            $('#inp_user_agency_name').addClass(" is-invalid");
            $('#inp_user_agency_name').focus();
            return false;
        }
        if (postDatas.val_last == null || postDatas.val_last.length == 0 || /^\s+$/.test(postDatas.val_last)) {
            $('#inp_user_agency_last').addClass(" is-invalid");
            $('#inp_user_agency_last').focus();
            return false;
        }
        if (!(/\w+([-+.']\w+)*@\w+([-.]\w+)/.test(postDatas.val_email))) {
            $('#inp_user_agency_email').addClass(" is-invalid");
            $('#inp_user_agency_email').focus();
			return false;  
        }
        if (postDatas.val_phone == null || postDatas.val_phone.length == 0 || /^\s+$/.test(postDatas.val_phone)) {
            $('#inp_user_agency_phone').addClass(" is-invalid");
            $('#inp_user_agency_phone').focus();
            return false;
        }
        if (postDatas.val_username == null || postDatas.val_username.length == 0 || /^\s+$/.test(postDatas.val_username)) {
            $('#inp_user_agency_username').addClass(" is-invalid");
            $('#inp_user_agency_username').focus();
            return false;
        }
        $.ajax({
            data: postDatas,
            url:'../../helpers/agencias.php',
            type:'POST',
            success: function(data){
                if (data == 1) {
                    loadUsersAgency(id_agency);
                    $('#alert-msg-user').addClass(' alert-info');
                    $('#alert-msg-user').show();
                    $('#text-msg-user').val('Los datos del usuario han sido actualizados correctamente');
                    setTimeout(function(){ $('#alert-msg-user').hide('slow'); }, 1500);
                }else{
                    loadUsersAgency(id_agency);
                    $('#alert-msg-user').addClass(' alert-danger');
                    $('#alert-msg-user').show();
                    $('#text-msg-user').val('Error al actualizar los datos del usuario');
                    setTimeout(function(){ $('#alert-msg-user').hide('slow'); }, 1500);
                }
            }

        });
    });
    $(document).on('click', '#btn_delete_user', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('user-us');
        let id_agency = $('#inp_edit_user_agency').val();
        let element_2 = $(this)[0];
        let name = $(element_2).attr('user-name');
        let lastname = $(element_2).attr('user-last');
        console.log(id);
        console.log(name);
        console.log(lastname);
        const postDatas = {
            'id': id,
            'name': name,
            'lastname': lastname,
            'action': 'delete_data_user_agency'
        };
        $.ajax({
            data: postDatas,
            url:'../../helpers/agencias.php',
            type:'POST',
            success: function(data){
                console.log(data);
                if (data == 1) {
                    loadUsersAgency(id_agency);
                    $('#alert-msg-user').addClass(' alert-info');
                    $('#alert-msg-user').show();
                    $('#text-msg-user').val('El usuario '+name+' '+lastname+' a sido eliminado correctamente');
                    setTimeout(function(){ $('#alert-msg-user').hide('slow'); }, 2000);
                }else{
                    loadUsersAgency(id_agency);
                    $('#alert-msg-user').addClass(' alert-danger');
                    $('#alert-msg-user').show();
                    $('#text-msg-user').val('Error al eliminar al usuario '+name+' '+lastname);
                    setTimeout(function(){ $('#alert-msg-user').hide('slow'); }, 2000);
                }
            }

        });
    });

    function loadUsersAgency(id){
        function loadData(page){
            value = $('#inp_agency').val();
            const postData = {
                'page_no': page,
                'id': id,
                'action': 'get_users_agency'
            };
            $.ajax({
                url  : "../../helpers/agencias.php",
                type : "POST",
                cache: false,
                data : postData,
                success:function(response){
                $('.update_users').hide();
                $('#btn_cancel_edit_user').hide();
                $("#content_users_agency").html(response);
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

    //Removemos class al cambiar de Paso 2
    $(document).on('keyup', '#form-discount :input', function(){
        if ($.trim($(this).val()).length) {
            $(this).removeClass(' is-invalid');
        } else {
            $(this).addClass(' is-invalid');
        }
    });
    //Removemos class al cambiar de Paso 2
    $(document).on('keyup', ' :input', function(){
        if ($.trim($(this).val()).length) {
            $(this).removeClass(' is-invalid');
        } else {
            $(this).addClass(' is-invalid');
        }
    });


    $(document).on('click', '#btn_dowload_report_a', function(){
        const postDatas = {
            'action': 'download_report_agencies'
        };
        $.ajax({
            data: postDatas,
            url:'../../helpers/reports.php',
            type:'POST',
            beforeSend: function(){
                $('#btn_dowload_report_a').prop('disabled', true);    
                $('#btn_dowload_report_a').html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>'); 
            },
            success: function(res){ 
                console.log(res);
                var d = new Date();
                var today = d.getFullYear() + '-' + ('0'+(d.getMonth()+1)).slice(-2) + '-' + ('0'+d.getDate()).slice(-2);
                var time = d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
                let fileName = "Agencias "+today+" "+time+ " Hrs";
                var element = document.createElement('a');
                element.setAttribute('href', 'data:application/vnd.ms-Excel,' + encodeURIComponent(res));
                element.setAttribute('download', fileName);
                element.style.display = 'none';
                document.body.appendChild(element);
                element.click();
                document.body.removeChild(element);
                $('#btn_dowload_report_a').prop('disabled', false);    
                $('#btn_dowload_report_a').html('Generar Reporte'); 
                
            }
        });
    });
});