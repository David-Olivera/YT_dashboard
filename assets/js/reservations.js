$(function(){
    let edit = false;
    $('#sidebar, #content').toggleClass('active');
    $('#result-search').hide();
    $('#fecha_end').hide();
    $('#content_taxipayment').hide();
    $('#content_taxi_pay').hide();
    $('#alert-msg-user').hide();
    loadReservations(tab ="RESERVED", type_search = "",data_search = "", f_llegada = "", f_salida = "");

    // DESCARGAR 
    $('#content_filter_agency').hide();
    $('#content_filter_zone').hide();
    $('#content_filter_type_service').hide();

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
    // datepicker entrada y salida edit
    $(function() {        
          var $datepicker2 = $( "#datepicker_exit_edit" );
          let daypicket = $('#datepicker_arrival_edit').val();
          
          $('#datepicker_arrival_edit').datepicker( {
              language: 'es'
          });
          $datepicker2.datepicker({
            language: 'es',
            minDate: daypicket
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
    // datepicker pick up edit
    $(function() {        
      var $datepicker_3 = $("#datepicker_pickup_exit_edit");
      $('#datepicker_pickup_arrival_edit').datepicker( {
          language: 'es',
          onSelect: function(fecha) {
              $datepicker_3.datepicker({
                  language: 'es',       
                  

              });
              $datepicker_3.datepicker("option", "disabled", false);
              $datepicker_3.datepicker('setDate', null);
              $datepicker_3.datepicker("option", "minDate", fecha); 
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
    $(document).on('click', '#radiob', function(){
        var val = $(this).is(':checked') ? 1 : 0;
        if (val == 1) {
            $('#fecha_end').show();
        }else if(val == 0){
            $('#fecha_end').hide();
        }
    });

    /* Buscar reserva por nombre de cliente o ID*/
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
    /* Buscar reserva por agencia */
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

    // BTN DESCARGAR EXCEL
    $(document).on('click', '#checkFechaServicio', function(){
        var val = $(this).is(':checked') ? 1 : 0;
        if (val == 1) {
            $('#content_filter_date').show('slide');
        }else if(val == 0){
            $('#content_filter_date').hide('slide');
        }
    });
    $(document).on('click', '#checkAgencia', function(){
        var val = $(this).is(':checked') ? 1 : 0;
        if (val == 1) {
            $('#content_filter_agency').show('slide');
        }else if(val == 0){
            $('#content_filter_agency').hide('slide');
        }
    });
    $(document).on('click', '#checkZona', function(){
        var val = $(this).is(':checked') ? 1 : 0;
        if (val == 1) {
            $('#content_filter_zone').show('slide');
        }else if(val == 0){
            $('#content_filter_zone').hide('slide');
        }
    });
    $(document).on('click', '#checkTypeServicie', function(){
        var val = $(this).is(':checked') ? 1 : 0;
        if (val == 1) {
            $('#content_filter_type_service').show('slide');
        }else if(val == 0){
            $('#content_filter_type_service').hide('slide');
        }
    });
    $(document).on('click', '#btn_dowload_report', function(){
        var seleccion_date = $("#checkFechaServicio")[0].checked;
        var seleccion_agency = $("#checkAgencia")[0].checked;
        var seleccion_zone = $("#checkZona")[0].checked;
        var seleccion_type_servicie = $("#checkTypeServicie")[0].checked;

        const postDatas = {
            'f_date_a': $('#datepicker_star_download').val(),
            'f_date_s': $('#datepicker_end_download').val(),
            'name_agency': $('#inp_acency').val(),
            'name_zone': $('#inp_zone').val(),
            'name_type_service': $('#inp_service').val(),
            'action': 'download_report'
        };
        if(!seleccion_date && !seleccion_agency && !seleccion_zone && !seleccion_type_servicie ){
            $('#alert-msg-user').addClass(' alert-danger');
            $('#alert-msg-user').show();
            $('#text-msg-user').val('Debes seleccionar minimo un filtro');
            setTimeout(function(){ $('#alert-msg-user').hide('slow'); }, 3000);
            return false;
        }
        if (seleccion_date) {
            if (postDatas.f_date_a == null || postDatas.f_date_a.length == 0 || /^\s+$/.test(postDatas.f_date_a)) {
                $('#datepicker_star_download').addClass(" is-invalid");
                $('#datepicker_star_download').focus();
                return false;
            }
            if (postDatas.f_date_s == null || postDatas.f_date_s.length == 0 || /^\s+$/.test(postDatas.f_date_s)) {
                $('#datepicker_end_download').addClass(" is-invalid");
                $('#datepicker_end_download').focus();
                return false;
            }
        }
        if (seleccion_agency) {
            if (postDatas.name_agency == null || postDatas.name_agency.length == 0 || /^\s+$/.test(postDatas.name_agency)) {
                $('#inp_acency').addClass(" is-invalid");
                $('#inp_acency').focus();
                return false;
            }
        }
        if (seleccion_zone) {
            if (postDatas.name_zone == null || postDatas.name_zone.length == 0 || /^\s+$/.test(postDatas.name_zone)) {
                $('#inp_zone').addClass(" is-invalid");
                $('#inp_zone').focus();
                return false;
            }
        }
        if (seleccion_type_servicie) {
            if (postDatas.name_type_service == null || postDatas.name_type_service == "" || postDatas.name_type_service.length == 0 || /^\s+$/.test(postDatas.name_type_service)) {
                $('#inp_service').addClass(" is-invalid");
                $('#inp_service').focus();
                return false;
            }
        }
        // console.log('Todo los datos: '+postDatas.f_date_a+' - '+postDatas.f_date_s+' - '+postDatas.name_agency+' - '+postDatas.name_zone+' - '+postDatas.name_type_service);
        $.ajax({
            data: postDatas,
            url:'../../helpers/reports.php',
            type:'POST',
            beforeSend: function(){
                $('.formDownloadReport :input').prop('disabled', true);
                $('#btn_dowload_report').prop('disabled', true);    
                $('#btn_dowload_report').html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>'); 
            },
            success: function(res){ 
                var d = new Date();
                var today = d.getFullYear() + '-' + ('0'+(d.getMonth()+1)).slice(-2) + '-' + ('0'+d.getDate()).slice(-2);
                var time = d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
                let fileName = "Reservaciones "+today+" "+time+ " Hrs";
                var element = document.createElement('a');
                element.setAttribute('href', 'data:application/vnd.ms-Excel,' + encodeURIComponent(res));
                element.setAttribute('download', fileName);
                element.style.display = 'none';
                document.body.appendChild(element);
                element.click();
                document.body.removeChild(element);
                setTimeout(function(){ $('.btn_close_dowload').click(); }, 1000);
                
            }
        });
    });
    $(document).on('click', '.btn_close_dowload', function(){
        $('#dowloadModal').modal('hide');
        $('.formDownloadReport').trigger('reset');
        $("#checkFechaServicio").prop('checked', true);
        $("#checkAgencia").prop('checked', false);
        $("#checkZona").prop('checked', false);
        $("#checkTypeServicie").prop('checked', false);
        $('#content_filter_agency').hide();
        $('#content_filter_zone').hide();
        $('#content_filter_type_service').hide();
        $('#content_filter_date').show();
        $('.formDownloadReport :input').prop('disabled', false);
        $('#btn_dowload_report').prop('disabled', false);
        $('#btn_dowload_report').html('<span>Descargar</span>'); 

    });

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
                console.log(res.message);
                

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
                loadReservations(tab ="RESERVED", type_search = "",data_search = "", f_llegada = "", f_salida = "");;
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
                //loadReservations(tab ="", type_search = "",data_search = "", f_llegada = "", f_salida = "");
            }

        });
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
                if (data == 1) {
                    $('html, body').animate({scrollTop: 0}, 600);
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
        console.log(postData);
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

    //Removemos class al cambiar de Paso 2
    $(document).on('keyup', 'input', function(){
      if ($.trim($(this).val()).length) {
          $(this).removeClass(' is-invalid');
      } else {
          $(this).addClass(' is-invalid');
      }
    });
    //Removemos class al cambiar de Paso 2
    $(document).on('focusout', 'input', function(){
          $(this).removeClass(' is-invalid');
    });
    /* Navs */
    $('#reserved-tab').click(function(){
        let reserved = "RESERVED";
        loadReservations(reserved, type_search = "",data_search = "", f_llegada = "", f_salida = "");
    });
    $('#completed-tab').click(function(){
        let completed = "COMPLETED";
        loadReservations(completed, type_search = "",data_search = "", f_llegada = "", f_salida = "");
    });
    $('#noshow-tab').click(function(){
        let noshow = "NO SHOW";
        loadReservations(noshow, type_search = "",data_search = "", f_llegada = "", f_salida = "");
    });
    $('#cancelled-tab').click(function(){
        let cancelled = "CANCELLED";
        loadReservations(cancelled, type_search = "",data_search = "", f_llegada = "", f_salida = "");
    });
    $('#refunded-tab').click(function(){
        let refunded = "REFUNDED";
        loadReservations(refunded, type_search = "",data_search = "", f_llegada = "", f_salida = "");
    });
    /* End Navs */
    $(document).on('click','#view_all_reservations', function(){
        let reserved = "RESERVED";
        $('#search').val('');
        $('#name_agency').val('');
        $('#datepicker_star').val('');
        $('#datepicker_end').val('');
        loadReservations(reserved, type_search = "",data_search = "", f_llegada = "", f_salida = "");
    });

    // Listar hoteles
    function loadReservations(lets,type_search,data_search , f_llegada , f_salida){
        $('#content_reservas').show();
        $("#result-search").hide();
        function loadData(page){
            $.ajax({
                url: "../../model/reservaciones_paginacion.php",
                type: "POST",
                cache: false,
                data: {page_no:page, navs: lets, type_search, data_search, f_llegada, f_salida},
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
                        $('#content_reservas').hide();
                        
                    }
                    if (lets == 'RESERVED') {
                        $("#table-data-re").html(template);
                        
                    }
                    if (lets == 'COMPLETED') {
                        $("#table-data-co").html(template);
                        
                    }
                    if (lets == 'NO SHOW') {
                        $("#table-data-no").html(template);
                        
                    }
                    if (lets == 'CANCELLED') {
                        $("#table-data-ca").html(template);
                        
                    }
                    if (lets == 'REFUNDED') {
                        $("#table-data-ref").html(template);
                    }
                },
                success: function(response){
                    $('#content_reservas').show();
                    $("#result-search").html('');
                    if (lets == '') {
                        $("#result-search").html(response);
                        $('#content_reservas').hide();
                        
                    }
                    if (lets == 'RESERVED') {
                        $("#table-data-re").html(response);
                        
                    }
                    if (lets == 'COMPLETED') {
                        $("#table-data-co").html(response);
                        
                    }
                    if (lets == 'NO SHOW') {
                        $("#table-data-no").html(response);
                        
                    }
                    if (lets == 'CANCELLED') {
                        $("#table-data-ca").html(response);
                        
                    }
                    if (lets == 'REFUNDED') {
                        $("#table-data-ref").html(response);
                        
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
    //EDITAR RESERVACION
    $(document).on('click', '#close_alert_edit', function(){
        location.reload();
    });
    $('reservation_profile.php').ready(function(){
        $('#inps_entrada_edit').hide();
        $('#inps_salida_edit').hide();
        $('#inp_pickup_edit').hide();
        $('#content_inps_pickup').hide();
        $('#pick_up_exit').hide();
        $('#content_subtotal').hide();
        $('#content_inp_interhotel').hide();
        $('#inp_time_service_edit').hide();
        $('#content_comission_agency').hide();
        $('#inp_pickup').hide();
        
        let type_transfer = $('#inp_traslado_up').val();
        if (type_transfer == 'RED' || type_transfer == 'SEN/AH') {
            $('#inps_entrada_edit').show();
        }
        if (type_transfer == 'RED' || type_transfer == 'SEN/HA') {
            
            $('#inps_salida_edit').show();
        }
        if (type_transfer == 'REDHH' || type_transfer == 'SEN/HH') {
            
            $('#content_inp_interhotel').show();
            $('#inp_pickup_edit').show();
            $('#content_inps_pickup').show();
            $('#compartido_ts').hide();
        }
        if (type_transfer == 'REDHH' ) {
            $('#pick_up_exit').show();
        }
        if (type_transfer == 'RED' || type_transfer == 'SEN/HA') {
            $('#inp_pickup').show();
        }
        let method_pay = $('#inp_method_payment_edit').val();
        $('#card').hide();
        $('#paypal').hide();
        $('#oxxo').hide();
        $('#airport').hide();
        $('#deposit').hide();
        if (method_pay == 'card') {
            $('#card').show();
        }
        if (method_pay == 'paypal') {
            $('#paypal').show();
        }
        if (method_pay == 'airport') {
            $('#airport').show();
        }
        if (method_pay == 'oxxo') {
            $('#oxxo').show();
        }
        if (method_pay == 'deposit') {
            $('#deposit').show();
        }
        if (method_pay == 'card' || method_pay == 'paypal') {
            $('#content_subtotal').show();
            $('#content_comission_agency').show();
        }
        let type_service = $('#inp_servicio_edit').val();
        if (type_service == 'compartido') {
            $('#inp_time_service_edit').show();
               
        }
        
    });
    // Editar el tipo de traslado
    $(document).on('change', '#inp_traslado_up', function(){
            $('#content_inp_interhotel').hide();
            $('#inps_entrada_edit').hide();
            $('#inps_salida_edit').hide();
            $('#inp_pickup_edit').hide();
            $('#content_inp_interhotel').hide();
            $('#content_comission_agency').hide();
            $('#pick_up_exit').hide();
              let value ="";
              value = $(this).val();
              if (value == 'RED') {
                $('#label_date_star').text('Llegada');
                $('#compartido_ts').show();
                $('#inps_entrada_edit').show("drop", { direction: "left"}, "slow");
                $('#inps_salida_edit').show("drop", { direction: "left"}, "slow");
              }
              if (value == 'SEN/AH') {
                $('#compartido_ts').show();
                $('#label_date_star').text('Llegada');
                $('#inps_entrada_edit').show("drop", { direction: "left"}, "slow");
              }
              if (value == 'SEN/HA') {
                $('#compartido_ts').show();
                $('#label_date_star').text('Salida');
                $('#inps_entrada_edit').show("drop", { direction: "left"}, "slow");
              }
              if (value == 'SEN/HH') {
                $('#inp_servicio_edit').val('privado');
                $('#content_inps_pickup').show();
                $('#content_inps_pickup_exit').hide();
                $('#compartido_ts').hide();
                $('#content_inp_interhotel').show();
                $('#inp_pickup_edit').show("drop", { direction: "left"}, "slow");
              }
              if (value == 'REDHH') {
                $('#inp_servicio_edit').val('privado');
                $('#content_inp_interhotel').show();
                $('#compartido_ts').hide();
                $('#pick_up_exit').show();
                $('#content_inps_pickup').show();
                $('#content_inps_pickup_exit').show();
                $('#content_inp_interhotel').show();
                $('#inp_pickup_edit').show("drop", { direction: "left"}, "slow");
              }
              
    });
    // Editar el tipo de traslado
    $(document).on('change', '#inp_servicio_edit', function(){
            $('#inp_time_service_edit').hide();
              let value ="";
              value = $(this).val();
              if (value == 'compartido') {
                $('.num_px_pri').show();
                $('#inp_time_service_edit').show();
                
              }
              if (value == 'privado') {
                $('.num_px_pri').show();
              }
              if (value == 'lujo') {
                $('.num_px_pri').hide();
              }
              
              
    });
    // Editar el metodo de pago
    $(document).on('change', '#inp_method_payment_edit', function(){
        $('#content_subtotal').hide();
        $('#content_comission_agency').hide();
        total_cost = $('#inp_total_cost_edit').val();
        
        amount_commision = $('#inp_agency_commision_edit').val();
        value_before = $('#inp_total_cost_commesion_edit').val();
        var com = 0.95;
        var commision = 0.05;
        var cost_comision= 0;
        var cost_finally = 0;
        var cost_total_commision = 0;
        cost_comision = total_cost * commision;
        
        cost_finally = (total_cost - cost_comision).toFixed(0);
        cost_total_commision = ((parseFloat(total_cost) / com) + parseFloat(amount_commision)).toFixed(0);
        let value ="";
        value = $(this).val();
        if (value == 'card') {
          $('#content_subtotal').show( "drop", { direction: "right" }, "slow" );
          $('#content_comission_agency').show( "drop", { direction: "right" }, "slow" );
          $('#inp_total_cost_commesion_edit').val(cost_total_commision);
          $('#inp_total_cost_commesion_edit').prop("disabled", true);
        }
        if (value == 'transfer') {
          $('#inp_total_cost_commesion_edit').val(total_cost);
          $('#inp_total_cost_before').val(value_before);
          $('#inp_total_cost_commesion_edit').prop("disabled", true);
        }
        if (value == 'a_pa' || value == 'a_transfer' || value == 'a_paypal' || value == 'a_card')  {
            $('#inp_total_cost_commesion_edit').prop("disabled", false);
        }

    });
    $('.close_content_edit_reserva').click(function(){
            $('#form-content-edit-agencie').trigger('reset');
            $("#content_edit_reserva").hide( "drop", { direction: "right" }, "slow" );
            $.ajax({
              beforeSend: function(){
                let template = '';
                template += `    
                    <div class="loader"></div>
                `;
                $('#loading').html(template);
              },
              complete: function(){
                $("#content_reservs_search").show( "drop", { direction: "left"}, "slow" );
                setTimeout(function(){  $(".loader").fadeOut("slow"); }, 200);
              }
            });
      
    });
    $('#inp_agency_commision_edit').on('keyup', function(e){
            if ($(this).val() == null || $(this).val() == "") {
                $(this).val('0.00');

            }
            var sale = {
                'subtotalmx' : $('#inp_total_cost_edit').val(),
                'commission' : $(this).val()
            };
            if (!(/^([0-9]+\.?[0-9]{0,2})$/.test(sale.commission))) {
                $('#inp_agency_commision_edit').val('');
                $('#inp_agency_commision_edit').focus();
                return false;
            }
                
            sale.total = parseFloat(sale.subtotalmx) + parseFloat(sale.commission);
            $('#inp_total_cost_commesion_edit').val(sale.total);
            console.log(sale.total);
            let method_pay = $('#inp_method_payment_edit').val();
            if(method_pay == 'card' || method_pay == 'paypal'){
                let cargo = 0.95;
                var new_value = (sale.total / cargo).toFixed(0);
                $('#inp_total_cost_commesion_edit').val(new_value);
                console.log(new_value);
            } 
            if( isNaN(parseFloat(sale.commission)) == true ) {
                $('#inp_total_cost_commesion_edit').val(parseFloat(subtotal));
            }
    });
    $(document).on('click', '#update_details_reservation', function(){
            let id_reservation = $('#inp_id_reservation').val();
            let code_invoice = $('#inp_code_invoice').val();
            let id_user = $('#inp_id_user').val();
            let code_client = "";
            let name_asesor = "";
            let of_the_agency = "";
      
            let name_hotel = $('#inp_hotel_edit').val();
            let name_hotel_interhotel= "";
            let type_traslado = $('#inp_traslado_up').val();
            let type_service = $('#inp_servicio_edit').val();
            let num_pasajeros =$('#inp_pasajeros_edit').val();
      
            let date_arrival = ""; 
            let airline_arrival = "";
            let no_fly_arrival = "";
            let time = "";
            let time_exit = "";
            let time_hour_arrival ="";
            let time_minute_arrival = "";
            let date_exit = "";
            let airline_exit = "";
            let no_fly_exit = "";
            let time_hour_exit ="";
            let time_minute_exit = "";
            let time_pickup = "";
            let time_pickup_inter = "";
            let pickup = "";
            let before_pickup = "";
      
      
            let method_payment = $('#inp_method_payment_edit').val();
            let sub_total= $('#inp_total_cost_commesion_edit').val();
            let commission = "";
            let total_cost_comision =0.00;
            let currency ="";
            let time_service = "";
            let name_client = $('#inp_name_client_edit').val();
            let last_name = $('#inp_lastname_client_edit').val();
            let mother_lastname = $('#inp_mother_lastname_edit').val();
            let email_client = $('#inp_email_client_edit').val();
            let phone_client =$('#inp_phone_client_edit').val();
            let special_request = $('#inp_special_requests_edit').val();
      
            console.log('ID RESERVATION');
            console.log(id_reservation);
      
            console.log('CODE INVOICE');
            console.log(code_invoice);
            //RESERVA EXTERNA
            if ($('#inp_code_client_edit').val()) {
              code_client = $('#inp_code_client_edit').val();
            }
            if ($('#inp_asesor_edit').val()) {
              name_asesor = $('#inp_asesor_edit').val();
            }
            let agencie = $('#inp_agency_edit').val();
            if ($('#inp_ofagency_edit').val() != '') {
                of_the_agency = $('#inp_ofagency_edit').val();
            }else{
                of_the_agency = agencie;
            }
            console.log('RESERVA EXTERNA');
            console.log(code_client+' - '+name_asesor+' - '+of_the_agency);
            //DATOS DE TRASLADO
            if (name_hotel == null || name_hotel.length == 0 || /^\s+$/.test(name_hotel)) {
              $('#inp_hotel_edit').addClass(" is-invalid");
              $('#inp_hotel_edit').focus();
              return false;
            }
            if (type_traslado == null || type_traslado.length == 0 || /^\s+$/.test(type_traslado)) {
              $('#inp_traslado_edit').addClass(" is-invalid");
              $('#inp_traslado_edit').focus();
              return false;
            }
            if (type_traslado == 'SEN/HH' || type_traslado == 'REDHH') {
              if ($('#inp_hotel_interhotel_edit').val()) {
                name_hotel_interhotel = $('#inp_hotel_interhotel_edit').val();
                if (name_hotel_interhotel == null || name_hotel_interhotel.length == 0 || /^\s+$/.test(name_hotel_interhotel)) {
                  $('#inp_hotel_interhotel_edit').addClass(" is-invalid");
                  $('#inp_hotel_interhotel_edit').focus();
                  return false;
                }
              }
            }
            if (type_service == null || type_service.length == 0 || /^\s+$/.test(type_service)) {
              $('#inp_servicio_edit').addClass(" is-invalid");
              $('#inp_servicio_edit').focus();
              return false;
            }
            if (num_pasajeros == null || num_pasajeros.length == 0 || /^\s+$/.test(num_pasajeros)) {
              $('#inp_pasajeros_edit').addClass(" is-invalid");
              $('#inp_pasajeros_edit').focus();
              return false;
            }
            if (type_service == 'compartido') {
              time_service = $('#inp_time_service').val();
              
              if (time_service == "" || time_service.length == 0 || /^\s+$/.test(time_service)) {
                $('#inp_time_service').addClass(" is-invalid");
                $('#inp_time_service').focus();
                return false;
              }
            }
            console.log('RESERVA TRASLADO');
            console.log(name_hotel+' - '+type_traslado+' - '+type_service+' - '+num_pasajeros);
            console.log(name_hotel_interhotel);
      
            console.log('DATOS DE VUELO Y/O PICKUP');
            //DATOS DE VUELO Y/O PICKUP
            if (type_traslado == 'SEN/AH' || type_traslado == 'RED') {
                date_arrival = $('#datepicker_arrival_edit').val();
                airline_arrival = $('#inp_airline_entry_edit').val();
                no_fly_arrival = $('#inp_nofly_entry_edit').val();
                time = validateTimeEntry();
                if (date_arrival == null || date_arrival.length == 0 || /^\s+$/.test(date_arrival)) {
                  $('#datepicker_arrival_edit').addClass(" is-invalid");
                  $('#datepicker_arrival_edit').focus();
                  return false;
                }
                if (airline_arrival == null || airline_arrival.length == 0 || /^\s+$/.test(airline_arrival)) {
                    $('#inp_airline_entry_edit').addClass(" is-invalid");
                    $('#inp_airline_entry_edit').focus();
                    return false;
                }
                if (no_fly_arrival == null || no_fly_arrival.length == 0 || /^\s+$/.test(no_fly_arrival)) {
                    $('#inp_nofly_entry_edit').addClass(" is-invalid");
                    $('#inp_nofly_entry_edit').focus();
                    return false;
                }
                if (time == null || time == 0) {
                    $('#inp_hour_entry_edit').addClass(" is-invalid");
                    $('#inp_hour_entry_edit').focus();
                    return false;
                }
                
                console.log(date_arrival+' - '+airline_arrival+' - '+no_fly_arrival+' - '+time);
            }
            if (type_traslado == 'SEN/HA') {
                date_exit = $('#datepicker_exit_edit').val();
                airline_exit = $('#inp_airline_exit_edit').val();
                no_fly_exit = $('#inp_nofly_exit_edit').val();
                time_exit = validateTimeExit();
                pickup = validatePickup();
                before_pickup = $('#inp_before_pickup').val();
                if (date_exit == null || date_exit.length == 0 || /^\s+$/.test(date_exit)) {
                  $('#datepicker_exit_edit').addClass(" is-invalid");
                  $('#datepicker_exit_edit').focus();
                  return false;
                }
                if (airline_exit == null || airline_exit.length == 0 || /^\s+$/.test(airline_exit)) {
                    $('#inp_airline_exit_edit').addClass(" is-invalid");
                    $('#inp_airline_exit_edit').focus();
                    return false;
                }
                if (no_fly_exit == null || no_fly_exit.length == 0 || /^\s+$/.test(no_fly_exit)) {
                    $('#inp_nofly_exit_edit').addClass(" is-invalid");
                    $('#inp_nofly_exit_edit').focus();
                    return false;
                }
                if (time_exit == null || time_exit == 0) {
                    $('#inp_hour_exit_edit').addClass(" is-invalid");
                    $('#inp_hour_exit_edit').focus();
                    return false;
                }
                if (pickup == null || pickup == 0) {
                    $('#inp_hour_pick').addClass(" is-invalid");
                    $('#inp_hour_pick').focus();
                    return false;
                }

                console.log(date_exit+' - '+airline_exit+' - '+no_fly_exit+' - '+time_exit);
                console.log('Pickup '+ pickup);
            }
            if (type_traslado == 'RED') {
              date_exit = $('#datepicker_exit_edit').val();
              airline_exit = $('#inp_airline_exit_edit').val();
              no_fly_exit = $('#inp_nofly_exit_edit').val();
              time_exit = validateTimeExit();
              pickup = validatePickup();
              if (date_exit == null || date_exit.length == 0 || /^\s+$/.test(date_exit)) {
                $('#datepicker_exit_edit').addClass(" is-invalid");
                $('#datepicker_exit_edit').focus();
                return false;
              }
              if (airline_exit == null || airline_exit.length == 0 || /^\s+$/.test(airline_exit)) {
                  $('#inp_airline_exit_edit').addClass(" is-invalid");
                  $('#inp_airline_exit_edit').focus();
                  return false;
              }
              if (no_fly_exit == null || no_fly_exit.length == 0 || /^\s+$/.test(no_fly_exit)) {
                  $('#inp_nofly_exit_edit').addClass(" is-invalid");
                  $('#inp_nofly_exit_edit').focus();
                  return false;
              }
              if (time_exit == null || time_exit == 0) {
                  $('#inp_hour_exit_edit').addClass(" is-invalid");
                  $('#inp_hour_exit_edit').focus();
                  return false;
              }
              if (pickup == null || pickup == 0) {
                  $('#inp_hour_pick').addClass(" is-invalid");
                  $('#inp_hour_pick').focus();
                  return false;
              }
              console.log(date_exit+' - '+airline_exit+' - '+no_fly_exit+' - '+time_exit);
              console.log('Pickup '+ pickup);
            }
            if (type_traslado == 'SEN/HH' || type_traslado == 'REDHH') {
                date_arrival = $('#datepicker_pickup_arrival_edit').val();
                time_pickup = validateTimePickEntry();
                if (date_arrival == null || date_arrival.length == 0 || /^\s+$/.test(date_arrival)) {
                  $('#datepicker_pickup_arrival_edit').addClass(" is-invalid");
                  $('#datepicker_pickup_arrival_edit').focus();
                  return false;
                }
                if (time_pickup == null || time_pickup == 0) {
                    $('#inp_hour_pick_edit').addClass(" is-invalid");
                    $('#inp_hour_pick_edit').focus();
                    return false;
                }
                console.log(date_arrival+' - '+time_pickup);
            }
            if (type_traslado == 'REDHH') {
                date_exit = $('#datepicker_pickup_exit_edit').val();
                time_pickup_inter = validateTimePickExit();
                if (date_exit == null || date_exit.length == 0 || /^\s+$/.test(date_exit)) {
                  $('#datepicker_pickup_exit_edit').addClass(" is-invalid");
                  $('#datepicker_pickup_exit_edit').focus();
                  return false;
                }
                if (time_pickup_inter == null || time_pickup_inter == "") {
                    $('#inp_hour_pick_inter_edit').addClass(" is-invalid");
                    $('#inp_hour_pick_inter_edit').focus();
                    return false;
                }
                console.log(date_exit+' - '+time_pickup_inter);
            }
            //DATOS DE PAGO Y ESTADO
              total_cost_comision = parseFloat($('#inp_total_cost_commesion_edit').val()).toFixed(0);
              inp_total_cost_before = $('#inp_total_cost_before').val();
            if (method_payment == 'card' || method_payment == 'paypal') {
              sub_total= $('#inp_total_cost_commesion_edit').val();
              commission = $('#inp_agency_commision_edit').val();
            }
            currency = $('#currency').text();
            console.log('DATOS DE PAGO Y ESTADO');
            console.log(method_payment+' - '+total_cost_comision);
            console.log(method_payment+' - '+sub_total+' - '+commission+ ' - '+total_cost_comision);
            console.log(currency);
      
            if (name_client == null || name_client.length == 0 || /^\s+$/.test(name_client)) {
                $('#inp_name_client_edit').addClass(" is-invalid");
                $('#inp_name_client_edit').focus();
                return false;
                
            }
            if (last_name == null || last_name.length == 0 || /^\s+$/.test(last_name)) {
                $('#inp_lastname_client_edit').addClass(" is-invalid");
                $('#inp_lastname_client_edit').focus();
                return false;
                
            }
            if (mother_lastname == null || mother_lastname.length == 0 || /^\s+$/.test(mother_lastname)) {
                $('#inp_mother_lastname_edit').addClass(" is-invalid");
                $('#inp_mother_lastname_edit').focus();
                return false;
                
            }
            if (phone_client == null || phone_client.length == 0 || /^\s+$/.test(phone_client)) {
                $('#inp_phone_client_edit').addClass(" is-invalid");
                $('#inp_phone_client_edit').focus();
                return false;
                
            }
            if (!(/\w+([-+.']\w+)*@\w+([-.]\w+)/.test(email_client))) {
                $('#inp_email_client_edit').addClass(" is-invalid");
                $('#inp_email_client_edit').focus();
                return false;
            }
            console.log('DATOS DE CLIENTE');
            console.log(name_client+' - '+last_name+' - '+email_client+' - '+phone_client+' - '+special_request);
            const postDatas = {
              'id_reservation':id_reservation,
              'id_user': id_user,
              'code_invoice':code_invoice,
              'code_client':code_client, 
              'name_asesor':name_asesor,  
              'of_the_agency':of_the_agency,
              'name_hotel':name_hotel,
              'name_hotel_interhotel':name_hotel_interhotel,
              'type_traslado':type_traslado,
              'type_service':type_service ,
              'num_pasajeros':num_pasajeros,
              'date_arrival': date_arrival,
              'airline_arrival': airline_arrival,
              'no_fly_arrival': no_fly_arrival,
              'time': time,
              'time_exit': time_exit,
              'time_hour_arrival': time_hour_arrival,
              'time_minute_arrival': time_minute_arrival,
              'date_exit': date_exit,
              'airline_exit': airline_exit,
              'no_fly_exit': no_fly_exit,
              'time_hour_exit': time_hour_exit,
              'time_minute_exit': time_minute_exit, 
              'time_pickup': time_pickup,
              'time_pickup_inter': time_pickup_inter,
              'time_service': time_service,
              'pickup': pickup,
              'before_pickup': before_pickup,
              'method_payment' :method_payment, 
              'sub_total' :sub_total,
              'commission' :commission,
              'total_cost_comision' :total_cost_comision,
              'currency': currency,
              'name_client' :name_client,
              'last_name':last_name,
              'mother_lastname': mother_lastname,
              'email_client' :email_client, 
              'phone_client' :phone_client,
              'special_request' :special_request,
              'update_traslado': true
            };
            $.ajax({
                data: postDatas,
                url: '../../helpers/reservaciones.php',
                type: 'post',
                beforeSend: function(){
                  $("html, body").animate({scrollTop: 0}, 1000);
                  let template = '';
                  template += `    
                      <div class="loader"></div>
                  `;
                  $('#loading').html(template);
                  $('.btn_load').show();
                },
                success: function(data){
                  const res = JSON.parse(data);
                  $(".loader").fadeOut("slow");
                  console.log('EL NUEVO PRECIO ES');
                  console.log(data);
                  
                  loadCountMsj();
                  loadCountActivities();
                  let new_currency ="";
                  let newdata ="";
                  if (currency == 'mx') {
                    new_currency = 'MXN';
                  }else{
                    new_currency = 'USD';
                  }
                  if (res.error == 1) {

                    if (res.total_cost == total_cost_comision|| res.total_cost_commision == total_cost_comision) {
                        newdata = '<h6>Los datos han sido actualizados correctamente, sin cambios en la tarfia </h6>';
                    }else {
                        newdata = '<h6>La reservación a sido actualizada, el nuevo balance es </h6>'+ '<h4 style="color:#47c9a2;">$'+res.total_cost+ ' '+ new_currency +'</h4> <p> balance anterior</p> <h5 style="color:#E1423B; text-decoration: line-through;">$ '+inp_total_cost_before+ ' '+ new_currency +'</h5>';
                        if (method_payment == 'card' || method_payment == 'paypal') {
                            
                          newdata = '<h6>La reservación a sido actualizada, el nuevo balance es </h6>'+ '<h4 style="color:#47c9a2;">$'+res.total_cost_commision+ ' '+ new_currency +'</h4>'+' <p> ya incluida la comisión</p> <h5 style="color:#47c9a2;">$ '+commission+ ' '+ new_currency +'</h5>' +'<p> balance anterior</p> <h5 style="color:#E1423B; text-decoration: line-through;">$ '+total_cost_comision+ ' '+ new_currency +'</h5>';
                        }
                    }
                    $('#update').modal('show');
                    $('#msj_success').html(newdata);  
                  }else{
                      $('#myModalerror').modal('show'); 
                  }
                }      
            });
    });
    //Removemos class de Date 1
	$('#datepicker_star_ser').on('focusout', function(){
      $(this).removeClass(' is-invalid');
    });
    //Removemos class de Date 2
    $('#datepicker_end_ser').on('focusout', function(){
        $(this).removeClass(' is-invalid');
    });
    //Validar horarios de llegada
    function validateTimeEntry(){
        var time = {
            'hour': $('#inp_hour_entry_edit option:selected').val(),
            'minute': $('#inp_minute_entry_edit option:selected').val(),
            'service': $('#inp_servicio_edit').val()
        };
        if (parseInt(time.hour) < 0 || parseInt(time.hour) > 23) {
            $('#inp_hour_entry_edit').focus();
            return false;
        }
        if (parseInt(time.minute) < 0 || parseInt(time.minute) > 59) {
            $('#inp_minute_entry_edit').focus();
            return false;
        }
        var new_time = 0; 
        if (time.service == 'compartido') {
            if (parseInt(time.hour) == 0) {
                $('#inp_hour_entry_edit').addClass(" is-invalid");
                $('#inp_hour_entry_edit').focus();
                $("#alert-msg").show('slow');
                $("#alert-msg").addClass('alert-danger');
                $msg = "El servicio COMPARTIDO sólo se encuentra disponible para llegadas de 08:00 HRS a 20:00 HRS";
                $('#text-msg').val($msg);
                $("html, body").animate({scrollTop: 0}, 1000);
                return new_time;
            }
            
            if (parseInt(time.hour) >= 8 && parseInt(time.hour) <= 19) {
                $("#alert-msg").hide('slow');
                new_time = time.hour + ':' + time.minute + ' '+ 'Hrs';
                return new_time;
            }
        }
        new_time = time.hour + ':' + time.minute + ' '+ 'Hrs';
        return new_time;
    }
    //Validar horarios de salida
    function validateTimeExit(){
        var time = {
            'hour': $('#inp_hour_exit_edit option:selected').val(),
            'minute': $('#inp_minute_exit_edit option:selected').val(),
            'service': $('#inp_servicio_edit').val()
        };
        if (parseInt(time.hour) < 0 || parseInt(time.hour) > 23) {
            $('#inp_hour_exit_edit').focus();
            return false;
        }
        if (parseInt(time.minute) < 0 || parseInt(time.minute) > 59) {
            $('#inp_hour_exit_edit').focus();
            return false;
        }
        var new_time = 0; 
        if (time.service == 'compartido') {
            if (parseInt(time.hour) <= 7 || parseInt(time.hour) >= 20) {
                $('#inp_hour_exit_edit').addClass(" is-invalid");
                $('#inp_hour_exit_edit').focus();
                $("#alert-msg").show('slow');
                $("#alert-msg").addClass('alert-danger');
                $msg = "El servicio COMPARTIDO sólo se encuentra disponible para salidas de 08:00 HRS a 20:00 HRS";
                $('#text-msg').val($msg);
                $("html, body").animate({scrollTop: 0}, 1000);
                return new_time;
            }
            if (parseInt(time.hour) >= 8 && parseInt(time.hour) <= 19) {
                $("#alert-msg").hide('slow');
                new_time = time.hour + ':' + time.minute + ' '+ 'Hrs';
                return new_time;
            }
            
        }
        new_time = time.hour + ':' + time.minute + ' '+ 'Hrs';
        return new_time;

    }
    //Validar horarios pick up llegada
    function validateTimePickEntry(){
        var time = {
            'hour': $('#inp_hour_pick_edit option:selected').val(),
            'minute': $('#inp_minute_pick_edit option:selected').val(),
            'service': $('#inp_servicio_edit').val()
        };
        
        var new_time = 0; 
        if (parseInt(time.hour) <= 0 || parseInt(time.hour) > 23) {
            $("#alert-msg").show('slow');
            $("#alert-msg").addClass('alert-danger');
            $msg = "Debes ingresar una hora entre 1 - 23";
            $('#text-msg').val($msg);
            $('#inp_hour_pick_edit').focus();
            return new_time;
        }
        if (parseInt(time.minute) < 0 || parseInt(time.minute) > 59) {
            $("#alert-msg").show('slow');
            $("#alert-msg").addClass('alert-danger');
            $msg = "Debes ingresar un minute entre 1 - 59";
            $('#text-msg').val($msg);
            $('#inp_minute_pick_edit').focus();
            return new_time;
        }
        new_time = time.hour + ':' + time.minute + ' '+ 'Hrs';
        return new_time;

    }
    //Validar horarios pick up salida
    function validateTimePickExit(){
        var time = {
            'hour': $('#inp_hour_pick_inter_edit option:selected').val(),
            'minute': $('#inp_minute_pick_inter_edit option:selected').val(),
            'service': $('#_TYPE_TRANSFER').val()
        };
        var new_time = 0; 
        if (parseInt(time.hour) < 0 || parseInt(time.hour) > 23) {
            $('#inp_hour_pick_inter_edit').focus();
            return new_time;
        }
        if (parseInt(time.minute) < 0 || parseInt(time.minute) > 59) {
            $('#inp_minute_pick_inter_edit').focus();
            return new_time;
        }
        new_time = time.hour + ':' + time.minute + ' '+ 'Hrs';
        return new_time;

    }
    //Validar horarios de llegada
    function validatePickup(){
        var time = {
            'hour': $('#inp_hour_pick option:selected').val(),
            'minute': $('#inp_minute_pick option:selected').val(),
        };
        if (parseInt(time.hour) < 0 || parseInt(time.hour) > 23) {
            $('#inp_hour_entry_edit').focus();
            return false;
        }
        if (parseInt(time.minute) < 0 || parseInt(time.minute) > 59) {
            $('#inp_minute_entry_edit').focus();
            return false;
        }
        var new_time = 0; 
        new_time = time.hour + ':' + time.minute + ' '+ 'Hrs';
        return new_time;
    }
    //Removemos class de Date 1
    $(document).on('click', '#datepicker_arrival_edit', function(){
      $(this).removeClass(' is-invalid');
    });
    //Removemos class de Date 2
    $(document).on('click', '#datepicker_exit_edit', function(){
      $(this).removeClass(' is-invalid');
    });
    //Removemos class de Date 1
    $(document).on('click', '#datepicker_pickup_arrival_edit', function(){
      $(this).removeClass(' is-invalid');
    });
    //Removemos class de Date 2
    $(document).on('click', '#datepicker_pickup_exit_edit', function(){
      $(this).removeClass(' is-invalid');
    });
    //Removemos class al cambiar de Paso 2
    $(document).on('change', '#form_reserva :input', function(){
        if ($.trim($(this).val()).length) {
            $(this).removeClass(' is-invalid');
        } else {
            $(this).addClass(' is-invalid');
        }
    });
    //Removemos class al cambiar de Paso 2
    $(document).on('change', '#form_reserva_edit :input', function(){
        if ($.trim($(this).val()).length) {
            $(this).removeClass(' is-invalid');
        } else {
            $(this).addClass(' is-invalid');
        }
    });
    //Removemos class al cambiar de Paso 2
    $(document).on('change', '#inp_service', function(){
        if ($.trim($(this).val()).length) {
            $(this).removeClass(' is-invalid');
        } else {
            $(this).addClass(' is-invalid');
        }
    });
    //Removemos class al cambiar de Paso 3
    $(document).on('change', '.form_details :input', function(){
        if ($.trim($(this).val()).length) {
            $(this).removeClass(' is-invalid');
        } else {
            $(this).addClass(' is-invalid');
        }
    });
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

});