$(function(){
    $('#sidebar, #content').toggleClass('active');
    $('.alert-msg').hide();
    let edit = false;
    $('#result-search').hide();
    $('#fecha_end').hide();
    $('#content_taxipayment').hide();
    $('#alert-msg-s').hide();
    $('#alert-msg-o').hide();
    loadReservations(tab ="LLEGADA", type_search = "",data_search = "", f_llegada = "", f_salida = "");
    let id = getParameterByName('reservation');
    loadMessagesReservation(id);
    $(document).ready(function(){
        function load(){
            loadMessagesReservation(id);
        }
        setInterval(load, 1000);
    });
    
    // DESCARGAR 
    $('#content_filter_agency_s').hide();
    $('#content_filter_zone_s').hide();
    $('#content_filter_type_service_s').hide();
    $('#content_filter_date_s').hide();
    $('#content_filter_provider_o').hide();

      
    // datepicker
    $( function() {        
        var $datepicker2 = $( "#datepicker_end" );
        var d = new Date();
        d.setDate(d.getDate() - 3);
        $('#datepicker_star').datepicker( {
            language: 'es',
            onSelect: function(fecha) {
                $datepicker2.datepicker({   
                    language: 'es',       
                    minDate: new Date(),

                });
                $datepicker2.datepicker("option", "disabled", false);
                $datepicker2.datepicker('setDate', null);
                $datepicker2.datepicker("option", "minDate", fecha); 
            }
        });
        $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '<Ant',
            nextText: 'Sig>',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
            weekHeader: 'Sm',
            dateFormat: 'yy-mm-dd',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
          };
          $.datepicker.setDefaults($.datepicker.regional['es']);
    });
    // datepicker
    $( function() {        
            var $datepicker2 = $( ".datepicker_end" );
            $('.datepicker_star').datepicker( {
                language: 'es',
                onSelect: function(fecha) {
                    $datepicker2.datepicker({   
                        language: 'es',       
                        minDate: new Date(),
    
                    });
                    $datepicker2.datepicker("option", "disabled", false);
                    $datepicker2.datepicker('setDate', null);
                    $datepicker2.datepicker("option", "minDate", fecha); 
                }
            });
            $.datepicker.regional['es'] = {
                closeText: 'Cerrar',
                prevText: '<Ant',
                nextText: 'Sig>',
                currentText: 'Hoy',
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
                dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
                weekHeader: 'Sm',
                dateFormat: 'yy-mm-dd',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
              };
              $.datepicker.setDefaults($.datepicker.regional['es']);
    });
    // Btn cancelar
    $('#cancelButton').on("click", function(e){
        e.preventDefault();
        $('#exampleModal').modal('hide');
        // $('#zoneForm').trigger('reset');
    });  

    // Btn cancelar REP
    $('#cancelBtnRep').on("click", function(e){
        e.preventDefault();
        $('#repModal').modal('hide');
        // $('#zoneForm').trigger('reset');
    });

    // Btn cancelar provider
    $('#cancelBtnProvider').on("click", function(e){
        e.preventDefault();
        $('#providerModal').modal('hide');
        $('#providerForm').trigger('reset');
    });

    //Btn x de alert mensaje
    $("#alert-close").click(function(){
        $(".alert-msg").hide('slow');
    });
    /* Copy email */
    $(document).on('click', '.copy_email', function(){
        let email;
        email = $(this).attr('title');
        alert(email);
    });
     // Buscar reserva 
     $('#form-search').submit(function(e){
        let search = $('#search').val();
        let type_search = "";
        if (search == null || search.length == 0 || /^\s+$/.test(search)) {
          $('#search').addClass(" is-invalid");
          $('#search').focus();
          return false;
        }
        type_search = 1;
        loadReservations(tab ="", type_search, search, f_llegada = "", f_salida = "");
        e.preventDefault();
    });

    // Buscar reserva por agencia 
    $('#form-search-agency').submit(function(e){
        let search = $('#name_agency').val();
        let type_search = "";
        if (search == null || search.length == 0 || /^\s+$/.test(search)) {
          $('#search').addClass(" is-invalid");
          $('#search').focus();
          return false;
        }
        type_search = 2;
        loadReservations(tab ="", type_search, search, f_llegada = "", f_salida = "");
        e.preventDefault();
    });    

    $(document).on('click', '#radiob', function(){
        var val = $(this).is(':checked') ? 1 : 0;
        if (val == 1) {
            $('#fecha_end').show();
        }else if(val == 0){
            $('#fecha_end').hide();
        }
    });

    // Busqueda por fechas
    $('#form-date').submit(function(e){
        let f_llegada = $('#datepicker_star').val();
        let f_salida = ""; 
        let type_search = "";     
        let checked = 0;
        var seleccion = $("#radiob")[0].checked;
        if (f_llegada == null || f_llegada.length == 0 || /^\s+$/.test(f_llegada)) {
          $('#datepicker_star').addClass(" is-invalid");
          $('#datepicker_star').focus();
          return false;
        } 
        type_search = 3;
        if(seleccion){
            f_salida = $('#datepicker_end').val(); 
            checked = 1;
            if (f_salida == null || f_salida.length == 0 || /^\s+$/.test(f_salida)) {
                $('#datepicker_end').addClass(" is-invalid");
                $('#datepicker_end').focus();
                return false;
            }
            type_search = 4;
        }
        loadReservations(tab ="", type_search, search="", f_llegada, f_salida);
    
        e.preventDefault();
    });

    // Change metodo de pago
    $(document).on('change', '#new_method_payment', function(){
        let stts = $(this).val();
        let element = $(this)[0];
        let text = $(this).find('option:selected').text();
        let id = $(element).attr('data');
        var payment = {
            'id': id,
            'value': stts,
            'text': text,
            'setmethodpay': 1
        };
        $.ajax({
            data: payment,
            url: '../../helpers/reservaciones.php',
            type: 'post',					
            beforeSend: function(){
            },
            success: function(data){
                var res = $.parseJSON(data);
                $('html, body').animate({scrollTop: 0}, 600);
                $('.alert-msg').show();
                $('#text-msg').val(res.message);

            }

        });
    });

    // Change status reservation
    $(document).on('change', '#new_status_reservation', function(){
        let stts = $(this).val();
        let element = $(this)[0];
        let text = $(this).find('option:selected').text();
        let id = $(element).attr('data');
        let code = $(element).attr('code');
        let transfer = $(element).attr('transfer');
        let user = $('#inp_user').val();
        if ((text == 'CANCELLED') && (transfer == 'REDHH' || transfer == 'RED')) {
            $('#cancelationModal').modal('show');
            $('#content_type_cs').hide();
            $('#inp_selected').val(text);
            $('#inp_reservation').val(id);
            $('#inp_code').val(code);
            $('#inp_transfer').val(transfer);
			return false;
        }
        var payment = {
            'id': id,
            'value': stts,
            'transfer': transfer,
            'text': text,
            'code': code,
            'user': user,
            'setstatusmet': 1
        };
        $.ajax({
            data: payment,
            url: '../../helpers/reservaciones.php',
            type: 'post',					
            beforeSend: function(){
            },
            success: function(data){
                $('html, body').animate({scrollTop: 0}, 600);
                $('.alert-msg').show();
                $('#text-msg').val(data);
            }

        });
    });
    $(document).on('click', '#cancelButtonCancelation', function(){
        $('#cancelationModal').modal('hide');
        $('#content_type_cs').hide();
        $('#formCancelation').trigger('reset');
    });
    $(document).on('change', '#type_cancelation', function(){
        let val = $(this).val();
        if (val == 'partial') {
            $('#content_type_cs').show('fade');
        }
        if (val == 'full') {
            $('#content_type_cs').hide('fade');
        }

    });
    $(document).on('click', '#btn_form_cancelation', function(){
        let text = $('#type_cancelation').val();
        let id = $('#inp_reservation').val();
        let code = $('#inp_code').val();
        let select = $('#inp_selected').val();
        let ty_cs = "";
        let user = $('#inp_user').val();
        let transfer = $('#inp_transfer').val();
        if (text == 'partial') {
            ty_cs = $('#type_cancelation_service').val();
        }
        var payment = {
            'id': id,
            'value': select,
            'transfer': transfer,
            'text': text,
            'code': code,
            'ty_cs': ty_cs,
            'user': user,
            'setstatusmet': 1
        };        
        $.ajax({
            data: payment,
            url: '../../helpers/reservaciones.php',
            type: 'post',					
            beforeSend: function(){
            },
            success: function(data){
                $('html, body').animate({scrollTop: 0}, 600);
                $('.alert-msg').show();
                $('#text-msg').val(data);
                $('#cancelationModal').modal('hide');
                $('#content_type_cs').hide();
                $('#formCancelation').trigger('reset');
                loadReservations(tab ="LLEGADA", type_search = "",data_search = "", f_llegada = "", f_salida = "");
            }

        });
    });

    // Navs 
    $('#entry-tab').click(function(){
        let reserved = "LLEGADA";
        
        loadReservations(reserved, type_search = "",data_search = "", f_llegada = "", f_salida = "");
    });

    $('#exit-tab').click(function(){
        let reserved = "SALIDA";
        loadReservations(reserved, type_search = "",data_search = "", f_llegada = "", f_salida = "");
    });
    // End Navs

    // Listar hoteles
    function loadReservations(lets, type_search, data_search, f_llegada, f_salida){
        $('#content_services').show();
        $("#result-search").hide();
        function loadData(page){
            $.ajax({
                url: "../../model/servicios_paginacion.php",
                type: "POST",
                cache: false,
                data: {page_no:page, navs: lets,type_search, data_search, f_llegada, f_salida },
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
                        </div>
                        <div class="col-lg-4 col-md-3">
                        </div>
                    </div>
                        
                    `;
                    if (lets == '') {
                        $("#result-search").show();
                        $("#result-search").html(template);
                        $('#content_services').hide();
                        
                    }
                    if (lets == 'LLEGADA') {
                        $("#table-data-re").html(template);
                        
                    }
                    if (lets == 'SALIDA') {
                        $("#table-data-co").html(template);
                        
                    }
                },
                success: function(response){
                    $('#content_services').show();
                    $("#result-search").html('');
                    if (lets == '') {
                        $("#result-search").html(response);
                        $('#content_services').hide();
                        
                    }
                    if (lets == 'LLEGADA') {
                        $("#table-data-re").html(response);
                        
                    }
                    if (lets == 'SALIDA') {
                        $("#table-data-co").html(response);
                        
                    }
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
    
    $(document).on('click','#view_all_services', function(){
        $('#search').val('');
        $('#name_agency').val('');
        $('#datepicker_star').val('');
        $('#datepicker_end').val('');
        loadReservations(tab ="LLEGADA", type_search = "",data_search = "", f_llegada = "", f_salida = "");
    });

    // Traer el id de la URL
    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }
    //Abrir modal proveedor
    $(document).on('click', '#select_provider', function(){
        let element = $(this)[0];
        let id = $(element).attr('data');
        let id_re = $(element).attr('datare');
        let code_invoice = $(element).attr('datainvoice');
        let type_action = $(element).attr('dataaction');
        let type_service = $(element).attr('dataservice');
        let type_tag = $(element).attr('datatag');
        if (id && id_re) {
            if (type_action == 'update_provider') {
                $('#note_proveedor').show();
                $('#proveedor_asignado').text(id);
                $('#id_servicee').val(id_re);
                $('#code_invoice').val(code_invoice);
                $('#type_action').val(type_action);
                $('#type_service').val(type_service);
                $('#type_tag').val(type_tag);
            }else{
                $('#note_proveedor').hide();
                $('#proveedor_asignado').text(id);
                $('#id_servicee').val(id_re);
                $('#code_invoice').val(code_invoice);
                $('#type_action').val(type_action);
                $('#type_service').val(type_service);
                $('#type_tag').val(type_tag);

            }
            
        }else{
            $('#proveedor_asignado').text('S/A');
        }
    });

    //Abrir modal REP
    $(document).on('click', '#select_rep', function(){
        let element = $(this)[0];
        let id_re = $(element).attr('datare');
        let type_service = $(element).attr('dataservice');
        let type_action = $(element).attr('dataaction');
        let code_invoice = $(element).attr('datainvoice');
        let id = $(element).attr('datarep');
        let type_tag = $(element).attr('datatag');
        if (id && id_re) {
            if (type_action == 'update_rep') {
                $('#note_rep').show()
                $('#rep_asignado_rep').text(id);
                $('#id_servicee_rep').val(id_re);
                $('#code_invoice_rep').val(code_invoice);
                $('#type_action_rep').val(type_action);
                $('#type_service_rep').val(type_service);
                $('#type_tag_rep').val(type_tag);
            }else{            
                $('#note_rep').hide();
                $('#rep_asignado_rep').text(id);
                $('#id_servicee_rep').val(id_re);
                $('#code_invoice_rep').val(code_invoice);
                $('#type_action_rep').val(type_action);
                $('#type_service_rep').val(type_service);
                $('#type_tag_rep').val(type_tag);
            }
            
        }else{
            $('#rep_asignado').text('S/A');
        }
    });

    // Guardar el proveedor nuevo o actualizado
    $('#providerForm').submit(function(e){
        var type_tag = $('#type_tag').val();
        let id_reservation =$('#id_servicee').val();
        var postData = {
            'id_provider': $('#nombre_proveedor').val(),
            'name_provider': $('#nombre_proveedor option:selected').text(),
            'note_provider': $('#note_proveedor').val(),
            'id_reservation': $('#id_servicee').val(),
            'code_invoice': $('#code_invoice').val(),
            'type_action': $('#type_action').val(),
            'type_service': $('#type_service').val(),
            'inp_user': $('#inp_user').val(),
            'change_provider': 'true',
        };
        if (postData.name_provider == null || postData.name_provider.length == 0 || /^\s+$/.test(postData.name_provider)) {
            alert('Debes seleccionar un proveedor');
            $('#nombre_proveedor').focus();
            return false;
        }
        $.ajax({
            data: postData,
            url: '../../helpers/servicios.php',
            type: 'post',
            success: function(data){
                var json = $.parseJSON(data);
                $('#providerForm').trigger('reset');
                $('html, body').animate({scrollTop: 0}, 600);
                $('#providerModal').modal('hide');
                if (type_tag == 'salida') {
                    tag = 'SALIDA'
                    $('.alert-msg').show();
                    loadReservations(tag, type_search = "",data_search = "", f_llegada = "", f_salida = "");
                    loadMessagesReservation(id_reservation);
                    //$('#result-search').hide('slow');
                    $('#entry-tab').removeClass('active');
                    $('#exit-tab').addClass('active');
                    $('#text-msg').val(json.message);
                    
                }else if (type_tag == 'entrada') {
                    
                    tag = 'LLEGADA'
                    $('.alert-msg').show();
                    loadReservations(tag, type_search = "",data_search = "", f_llegada = "", f_salida = "");
                    loadMessagesReservation(id_reservation);
                    //$('#result-search').hide('slow');
                    $('#exit-tab').removeClass('active');
                    $('#entry-tab').addClass('active');
                    $('#text-msg').val(json.message);
                } 
            }
        });
        e.preventDefault();
    });

    // Guardar el REP nuevo o actualizado
    $('#repForm').submit(function(e){
        var type_tag = $('#type_tag_rep').val();
        var postData = {
            'id_rep': $('#nombre_rep').val(),
            'name_rep': $('#nombre_rep option:selected').text(),
            'id_reservation': $('#id_servicee_rep').val(),
            'code_invoice': $('#code_invoice_rep').val(),
            'type_action': $('#type_action_rep').val(),
            'type_service': $('#type_service_rep').val(),
            'inp_user': $('#inp_user_rep').val(),
            'change_rep': 'true',
        };
        if (postData.name_rep == null || postData.name_rep.length == 0 || /^\s+$/.test(postData.name_rep)) {
            alert('Debes seleccionar un REP');
            $('#nombre_rep').focus();
            return false;
        }
        $.ajax({
            data: postData,
            url: '../../helpers/servicios.php',
            type: 'post',
            success: function(data){
                var json = $.parseJSON(data);
                $('#repForm').trigger('reset');
                $('html, body').animate({scrollTop: 0}, 600);
                $('#repModal').modal('hide');
                if (type_tag == 'salida') {
                    tag = 'SALIDA'
                    $('.alert-msg').show();
                    loadReservations(tag, type_search = "",data_search = "", f_llegada = "", f_salida = "");
                    //$('#result-search').hide('slow');
                    $('#entry-tab').removeClass('active');
                    $('#exit-tab').addClass('active');
                    $('#text-msg').val(json.message);
                    
                }else if (type_tag == 'entrada') {
                    
                    tag = 'LLEGADA'
                    $('.alert-msg').show();
                    loadReservations(tag, type_search = "",data_search = "", f_llegada = "", f_salida = "");
                    //$('#result-search').hide('slow');
                    $('#exit-tab').removeClass('active');
                    $('#entry-tab').addClass('active');
                    $('#text-msg').val(json.message);
                } 
            }
        });
        e.preventDefault();
    });

    $(document).on('click', '#btn_register_pay', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('reserva-id');
        $('#inp_reservation').val(id);
    });
    $(document).on('click', '#btnSetCharge', function(){
        let charge = $('#inp_charge').val();
        let currency = $('#inp_currency').val();
        let concept = $('#inp_concept').val();
        let check_taxipay = 0;
        let taxipayment = 0;
        let check_paleteo = 0;
        let id_reservation = $('#inp_reservation').val();
        let id_user = $('#inp_user').val();

        
		if (charge == null || charge.length == 0 || /^\s+$/.test(charge)) {
            $('#inp_charge').addClass(" is-invalid");
            $('#inp_charge').focus();
			return false;
		}
		if (concept == null || concept.length == 0 || /^\s+$/.test(concept)) {
            $('#inp_concept').addClass(" is-invalid");
            $('#inp_concept').focus();
			return false;
		}

        if ($('#inp_taxi').is(':checked')) {
            check_taxipay = 1;
            taxipayment = $('#inp_taxipayment').val();

        }
        if ($('#inp_paleteo').is(':checked')) {
            check_paleteo = 1;
        }
        const postData = {
            'charge': charge,
            'currency': currency,
            'concept': concept,
            'check_paleteo': check_paleteo,
            'check_taxipay':check_taxipay,
            'taxipayment': taxipayment,
            'id_reservation': id_reservation,
            'id_user': id_user,
            'action': 'charge_register'
        };       
        $.ajax({
            data: postData,
            url: '../../helpers/reservaciones.php',
            type: 'POST',
            success: function(data){
                $('#cancelButtonrg').click();
                if (data == 1) {
                    $('html, body').animate({scrollTop: 0}, 600);

                    $('#frmSetCharge').trigger('reset');
                    $('#exampleModal').modal('hide');
                    $('.alert-msg').show();
                    $('#text-msg').val("El gasto con concepto "+concept+ " a sido registrado correctamente");
                }else{
                    $('html, body').animate({scrollTop: 0}, 600);
                    $('#exampleModal').modal('hide');
                    $('.alert-msg').show();
                    
                    $('#text-msg').val("Error al registrar el gasto con concepto "+concept);
                }
            }
        });
    });
    $(document).on('click', '#cancelButtonrg', function(){
        $('#frmSetCharge').trigger('reset');
        $('#exampleModal').modal('hide');
        $("#inp_paleteo").prop('checked', false);
        $('#inp_concept').val('').removeAttr('disabled');
        $('#content_taxipayment').hide();

    });
    $(document).on('click', '#inp_paleteo', function(){
        if ($('#inp_paleteo').is(':checked')) {
            $('#inp_concept').val('Paleteo').attr('disabled', 'disabled');
            $('#content_taxipayment').hide('fade');
            $("#inp_taxi").prop('checked', false);
        }else{
            $('#inp_concept').val('').removeAttr('disabled');
        }
    });
    $(document).on('click', '#inp_taxi', function(){
        if ($('#inp_taxi').is(':checked')) {
            $('#inp_concept').val('Taxi').attr('disabled', 'disabled');
            $('#content_taxipayment').show('fade');
            $("#inp_paleteo").prop('checked', false);
        }else{
            $('#inp_taxi').val('').removeAttr('disabled');
            $('#content_taxipayment').hide('fade');
        }
    });

    /* APARTADO DE DETALLES DE RESERVACION / SERVICIO */
    //Enviar mensaje 
    $(document).on('click', '#btn_send_msj', function(){
        let id_reservation = $('#inp_id_reservation').val();
        let comment = $('#input_msj').val();
        var postData = {
            'id_reservation': id_reservation,
            'comment': comment,
            'id_user': $('#value').val(),
            'send': 'true',
        };
        if (postData.comment == null || postData.comment.length == 0 || /^\s+$/.test(postData.comment)) {
            $('#input_msj').addClass('is-invalid');
            $('#input_msj').focus();
            return false;
        }
        $.ajax({
            data: postData,
            url: '../../helpers/servicios.php',
            type: 'post',
            success: function(response){
                if (response == 1) {
                    loadMessagesReservation(id);
                    $('#input_msj').val('');
                    var $target = $('#content-messages'); 
                    $target.animate({ scrollTop: $target.prop("scrollHeight")}, 0);
                    loadCountMsj();
                    loadCountActivities();
                }
            }
        }); 
    });

    // Remover alerta de espacio vacio de mensjae
    $(document).on('click', '#input_msj', function(){
        $('#input_msj').removeClass('is-invalid');
    });

    //Carga los mensajes 
    function loadMessagesReservation(id){
        let user_id = $('#value').val();
        const postData = {
            'id_reservation': id,
            'id_user': user_id,
            'messages': 'true'
        }
        $.ajax({
            data: postData,
            url:'../../model/reservaciones_mensajes.php',
            type: 'POST',
            cache: false,
            success: function(response){
                $("#content-messages").html(response);
            }
        });
    }

    //MOSTRAR MENSAJES BITACORA
    $(document).on('click', '#btn_view_notifications', function(){
        let type = 'load_msj_bitacora';
        loadMsjs(type);
        $("#notification-latest").show();
    });
    //MOSTRAR MENSAJES ACTIVIDADES
    $(document).on('click', '#btn_view_notifications_activity', function(){
            let type = 'load_msj_activity';
            loadMsjs(type);
            $("#notification-latest").show();
    });
    //LOAD MSJS
    function loadMsjs(type){
        const postDatas = {
            'id': $('#inp_user').val(),
            'type': type,
            'action': 'get_all_msjs'
        };
        $.ajax({
            data: postDatas,
            url: '../../model/notificaciones.php',
            type: 'POST',
            cache: false,
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
                    </div>
                    <div class="col-lg-4 col-md-3">
                    </div>
                </div>
                    
                `;
                $("#notification-latest").html(template);
            },
            success: function(res){
                var json = $.parseJSON(res);
                $('#icon_notify').removeClass(' notify_news');
                $('#icon_notify_activity').removeClass(' notify_news');
                    if (json.status == 1) {
                        $("#notification-latest").html(json.msj);
                        loadCountMsj();
                    }else{
                        
                        $("#notification-latest").html(json.msj);
                        loadCountMsj();
                    }

            }
        });
    }
    function loadCountMsj(){
        const postDatas = {
            'id': $('#inp_user').val(),
            'action': 'get_count_msjs'
        };
        $.ajax({
            data: postDatas,
            url: '../../helpers/reservaciones.php',
            type: 'POST',
            cache: false,
            beforeSend: function(){
                let template = '';
                template += ` - `;
                $("#num_notify").html(template);
            },
            success: function(res){
                
                if (res > 0) {
                    $('#icon_notify').addClass(' notify_news');
                    $("#num_notify").html(res);
                }else{
                    
                    $("#num_notify").html(res);
                }
                

            }
        });
    }
    function loadCountActivities(){
        const postDatas = {
            'id': $('#inp_user').val(),
            'action': 'get_count_acts'
        };
        $.ajax({
            data: postDatas,
            url: '../../helpers/reservaciones.php',
            type: 'POST',
            cache: false,
            beforeSend: function(){
                let template = '';
                template += ` - `;
                $("#num_notify_activity").html(template);
            },
            success: function(res){
                
                if (res > 0) {
                    if (res > 99) {
                        $('#icon_notify_activity').addClass(' notify_news');
                        $("#num_notify_activity").html('+99');
                    }else{
                        $('#icon_notify_activity').addClass(' notify_news');
                        $("#num_notify_activity").html(res);

                    }
                }else{
                    
                    $("#num_notify_activity").html(res);
                }
                

            }
        });
    }

    // Change metodo de pago
    $(document).on('change', '#new_method_payment', function(){
        let stts = $(this).val();
        let element = $(this)[0];
        let text = $(this).find('option:selected').text();
        let id = $(element).attr('data');
        let code = $(element).attr('code');
        var payment = {
            'id': id,
            'code':code,
            'value': stts,
            'text': text,
            'setmethodpay': 1
        };
        $.ajax({
            data: payment,
            url: '../../helpers/reservaciones.php',
            type: 'post',					
            beforeSend: function(){
            },
            success: function(data){
                var res = $.parseJSON(data);
                $('html, body').animate({scrollTop: 0}, 600);
                $('.alert-msg').show();
                $('#text-msg').val(res.message);
                

            }

        });
    });
    // Change status reservation
    $(document).on('change', '#new_status_reservation', function(){
        let stts = $(this).val();
        let element = $(this)[0];
        let text = $(this).find('option:selected').text();
        let id = $(element).attr('data');
        let code = $(element).attr('code');
        let transfer = $(element).attr('transfer');
        let user = $('#inp_user').val();
        
        if ((text == 'CANCELLED') && (transfer == 'REDHH' || transfer == 'RED')) {
            $('#cancelationModal').modal('show');
            $('#content_type_cs').hide();
            $('#inp_selected').val(text);
            $('#inp_reservation').val(id);
            $('#inp_code').val(code);
            $('#inp_transfer').val(transfer);
			return false;
        }
        var payment = {
            'id': id,
            'value': stts,
            'transfer': transfer,
            'text': text,
            'code': code,
            'user': user,
            'setstatusmet': 1
        };
        $.ajax({
            data: payment,
            url: '../../helpers/reservaciones.php',
            type: 'post',					
            beforeSend: function(){
            },
            success: function(data){
                $('html, body').animate({scrollTop: 0}, 600);
                $('.alert-msg').show();
                $('#text-msg').val(data);
                loadReservations(tab ="LLEGADA", type_search = "",data_search = "", f_llegada = "", f_salida = "");
            }

        });
    });
    // BTN DESCARGAR EXCEL
    $(document).on('click', '#checkFechaServicio_s', function(){
            var val = $(this).is(':checked') ? 1 : 0;
            if (val == 1) {
                $('#content_filter_date_s').show('slide');
            }else if(val == 0){
                $('#content_filter_date_s').hide('slide');
            }
    });
    $(document).on('click', '#checkAgencia_s', function(){
            var val = $(this).is(':checked') ? 1 : 0;
            if (val == 1) {
                $('#content_filter_agency_s').show('slide');
            }else if(val == 0){
                $('#content_filter_agency_s').hide('slide');
            }
    });
    $(document).on('click', '#checkZona_s', function(){
            var val = $(this).is(':checked') ? 1 : 0;
            if (val == 1) {
                $('#content_filter_zone_s').show('slide');
            }else if(val == 0){
                $('#content_filter_zone_s').hide('slide');
            }
    });
    $(document).on('click', '#checkTypeServicie_s', function(){
            var val = $(this).is(':checked') ? 1 : 0;
            if (val == 1) {
                $('#content_filter_type_service_s').show('slide');
            }else if(val == 0){
                $('#content_filter_type_service_s').hide('slide');
            }
    });
    $(document).on('click', '#btn_dowload_report_s', function(){
            var seleccion_date = $("#checkFechaServicio_s")[0].checked;
            var seleccion_agency = $("#checkAgencia_s")[0].checked;
            var seleccion_zone = $("#checkZona_s")[0].checked;
            var seleccion_type_servicie = $("#checkTypeServicie_s")[0].checked;
            let type_translate=  $('#inp_type_translate').val()
    
            const postDatas = {
                'f_date_a': $('#datepicker_star_download_s').val(),
                'f_date_s': $('#datepicker_end_download_s').val(),
                'name_agency': $('#inp_acency_s').val(),
                'name_zone': $('#inp_zone_s').val(),
                'name_type_service': $('#inp_service_s').val(),
                'type_translate': $('#inp_type_translate').val(),
                'action': 'download_report_s'
            };
            if (type_translate == "") {
                $('#alert-msg-s').addClass(' alert-danger');
                $('#alert-msg-s').show();
                $('#text-msg-s').val('Debes seleccionar si servicios de llegadas y/o salidas');
                setTimeout(function(){ $('#alert-msg-s').hide('slow'); }, 3000);
                return false;
            }
            if(!seleccion_date && !seleccion_agency && !seleccion_zone && !seleccion_type_servicie && !type_translate){
                $('#alert-msg-s').addClass(' alert-danger');
                $('#alert-msg-s').show();
                $('#text-msg-s').val('Selecciona un filtro o llegadas y/o salidas');
                setTimeout(function(){ $('#alert-msg-s').hide('slow'); }, 3000);
                return false;
            }
            if (seleccion_date) {
                if (postDatas.f_date_a == null || postDatas.f_date_a.length == 0 || /^\s+$/.test(postDatas.f_date_a)) {
                    $('#datepicker_star_download_s').addClass(" is-invalid");
                    $('#datepicker_star_download_s').focus();
                    return false;
                }
                if (postDatas.f_date_s == null || postDatas.f_date_s.length == 0 || /^\s+$/.test(postDatas.f_date_s)) {
                    $('#datepicker_end_download_s').addClass(" is-invalid");
                    $('#datepicker_end_download_s').focus();
                    return false;
                }
            }
            if (seleccion_agency) {
                if (postDatas.name_agency == null || postDatas.name_agency.length == 0 || /^\s+$/.test(postDatas.name_agency)) {
                    $('#inp_acency_s').addClass(" is-invalid");
                    $('#inp_acency_s').focus();
                    return false;
                }
            }
            if (seleccion_zone) {
                if (postDatas.name_zone == null || postDatas.name_zone.length == 0 || /^\s+$/.test(postDatas.name_zone)) {
                    $('#inp_zone_s').addClass(" is-invalid");
                    $('#inp_zone_s').focus();
                    return false;
                }
            }
            if (seleccion_type_servicie) {
                if (postDatas.name_type_service == null || postDatas.name_type_service == "" || postDatas.name_type_service.length == 0 || /^\s+$/.test(postDatas.name_type_service)) {
                    $('#inp_service_s').addClass(" is-invalid");
                    $('#inp_service_s').focus();
                    return false;
                }
            }
            // console.log('Todo los datos: '+postDatas.f_date_a+' - '+postDatas.f_date_s+' - '+postDatas.name_agency+' - '+postDatas.name_zone+' - '+postDatas.name_type_service);
            $.ajax({
                data: postDatas,
                url:'../../helpers/reports.php',
                type:'POST',
                beforeSend: function(){
                    $('.formDownloadReport_s :input').prop('disabled', true);
                    $('#btn_dowload_report_s').prop('disabled', true);    
                    $('#btn_dowload_report_s').html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>'); 
                },
                success: function(res){ 
                    var d = new Date();
                    var today = d.getFullYear() + '-' + ('0'+(d.getMonth()+1)).slice(-2) + '-' + ('0'+d.getDate()).slice(-2);
                    var time = d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
                    let fileName = "Servicios "+today+" "+time+ " Hrs";
                    var element = document.createElement('a');
                    element.setAttribute('href', 'data:application/vnd.ms-Excel,' + encodeURIComponent(res));
                    element.setAttribute('download', fileName);
                    element.style.display = 'none';
                    document.body.appendChild(element);
                    element.click();
                    document.body.removeChild(element);
                    setTimeout(function(){ $('.btn_close_dowload_s').click(); }, 1000);
                    
                }
            });
    });

    // BTN DESCARGAR EXCEL OPERACIONES
    $(document).on('click', '#checkFechaServicio_o', function(){
        var val = $(this).is(':checked') ? 1 : 0;
        if (val == 1) {
            $('#content_filter_date_o').show('slide');
        }else if(val == 0){
            $('#content_filter_date_o').hide('slide');
        }
    });
    $(document).on('click', '#checkProvider_o', function(){
            var val = $(this).is(':checked') ? 1 : 0;
            if (val == 1) {
                $('#content_filter_provider_o').show('slide');
            }else if(val == 0){
                $('#content_filter_provider_o').hide('slide');
            }
    });
    $(document).on('click', '#btn_dowload_report_o', function(){
            var seleccion_date = $("#checkFechaServicio_o")[0].checked;
            var seleccion_provider = $("#checkProvider_o")[0].checked;
            let f_date_s = "";
            if ($('#datepicker_end_download_o').val()) {
                f_date_s = $('#datepicker_end_download_o').val();
            }
            const postDatas = {
                'f_date_a': $('#datepicker_star_download_o').val(),
                'f_date_s': f_date_s,
                'provider': $('#inp_provider').val(),
                'action': 'download_report_o'
            };
            if(!seleccion_date && !seleccion_provider){
                $('#alert-msg-o').addClass(' alert-danger');
                $('#alert-msg-o').show();
                $('#text-msg-o').val('Selecciona un filtro para generar la operación');
                setTimeout(function(){ $('#alert-msg-o').hide('slow'); }, 3000);
                return false;
            }
            if (seleccion_date) {
                if (postDatas.f_date_a == null || postDatas.f_date_a.length == 0 || /^\s+$/.test(postDatas.f_date_a)) {
                    $('#datepicker_star_download_o').addClass(" is-invalid");
                    $('#datepicker_star_download_o').focus();
                    return false;
                }
            }
            if (seleccion_provider) {
                if (postDatas.provider == null || postDatas.provider.length == 0 || /^\s+$/.test(postDatas.provider)) {
                    $('#inp_provider').addClass(" is-invalid");
                    $('#inp_provider').focus();
                    return false;
                }
            }
            $.ajax({
                data: postDatas,
                url:'../../helpers/reports.php',
                type:'POST',
                beforeSend: function(){
                    $('.formDownloadReport_o :input').prop('disabled', true);
                    $('#btn_dowload_report_o').prop('disabled', true);    
                    $('#btn_dowload_report_o').html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
                },
                success: function(res){ 
                    var d = new Date();
                    var today = d.getFullYear() + '-' + ('0'+(d.getMonth()+1)).slice(-2) + '-' + ('0'+d.getDate()).slice(-2);
                    var time = d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
                    let fileName = "Operaciones "+today+" "+time+ " Hrs";
                    var element = document.createElement('a');
                    element.setAttribute('href', 'data:application/vnd.ms-Excel,' + encodeURIComponent(res));
                    element.setAttribute('download', fileName);
                    element.style.display = 'none';
                    document.body.appendChild(element);
                    element.click();
                    document.body.removeChild(element);
                    setTimeout(function(){ $('.btn_close_dowload_s').click(); }, 1000);
                    
                }
            });
    });

    // BTN CANCELAR DOWNLOAD
    $(document).on('click', '.btn_close_dowload_s', function(){
            $('.formDownloadReport_s').trigger('reset');
            $("#checkFechaServicio_s").prop('checked', false);
            $("#checkAgencia_s").prop('checked', false);
            $("#checkZona_s").prop('checked', false);
            $("#checkTypeServicie_s").prop('checked', false);
            $('#content_filter_agency_s').hide();
            $('#content_filter_zone_s').hide();
            $('#content_filter_type_service_s').hide();
            $('#content_filter_date_s').hide();
            $('.formDownloadReport_s :input').prop('disabled', false);
            $('#btn_dowload_report_s').prop('disabled', false);
            $('#btn_dowload_report_s').html('<span>Descargar</span>'); 

            
            $('.formDownloadReport_o').trigger('reset');
            $('.formDownloadReport_o :input').prop('disabled', false);
            $('#content_filter_provider_o').hide();
            $("#checkProvider_o").prop('checked', false);
            $("#checkFechaServicio_o").prop('checked', true);
            $('#content_filter_date_o').show();
            $('#btn_dowload_report_o').prop('disabled', false);
            $('#btn_dowload_report_o').html('<span>Descargar</span>'); 
    });
    
    //Removemos class de Date 1
	$('.datepicker_star').on('focusout', function(){
        $(this).removeClass(' is-invalid');
    });
    //Removemos class al cambiar de Paso 2
    $(document).on('change', '#inp_provider', function(){
        if ($.trim($(this).val()).length) {
            $(this).removeClass(' is-invalid');
        } else {
            $(this).addClass(' is-invalid');
        }
    });
});