$(function(){

    let edit = false;
    $('#content_results').hide();
        // datepicker
        $( function() {        
            var $datepicker2 = $( "#datepicker_end" );
            let inp_todaysale = $('#inp_todaysale').val();
            let todaysale = 0;
            if (inp_todaysale == 1 || inp_todaysale == 0) {
                todaysale = $('#inp_todaysale').val();
            }else{
                todaysale = 0;
            }
            var minDateArg = todaysale == 1 ? 0 : '+1d';
            $('#datepicker_star').datepicker( {
                language: 'es',
                minDate: minDateArg,
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
        // datepicker edit
        $( "#datepicker_star_edit" ).datepicker({
            dateFormat: "yy-mm-dd",
            defaultDate: "+1w",
            dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
            monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
            changeMonth: true,
            changeYear: true,
            onClose: function( selectedDate ) {
                $("#datepicker_end_edit").datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#datepicker_end_edit" ).datepicker({
            defaultDate: "+1w",
            dateFormat: "yy-mm-dd",
            dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
            monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
            changeMonth: true,
            changeYear: true,
            onClose: function( selectedDate ) {
                $("#datepicker_star_edit" ).datepicker( "option", "maxDate", selectedDate );
            }
        });

        //Tipo de traslado
        $(document).on('change', '#inp_traslado', function(){
            $('#datepicker_star').val('');
            $('#datepicker_end').val('');
            let value = "";
            value = $(this).val();
            if (value == '') {
                $('#label_date_star').text('Entrada');
                $('#content_date_star').show('slow');
                $('#content_date_end').show('slow');
            }
            if (value == 'SEN/AH' || value == 'SEN/HH') {
                $('#label_date_star').text('Entrada');
                $('#content_date_star').show('slow');
                $('#content_date_end').hide('slow');
            }
            if (value == 'SEN/HA') {
                $('#label_date_star').text('Salida');
                $('#content_date_star').show('slow');
                $('#content_date_end').hide('slow');
            }
            if (value == 'REDHH' || value == 'RED') {
                $('#label_date_star').text('Entrada');
                $('#content_date_star').show('slow');
                $('#content_date_end').show('slow');
            }
        });

        //Tipo de traslado edit
        $(document).on('change', '#inp_traslado_edit', function(){
            $('#datepicker_star_edit').val('');
            $('#datepicker_end_edit').val('');
            let value = "";
            value = $(this).val();
            if (value == '') {
                $('#content_date_star_edit').show('slow');
                $('#content_date_end_edit').show('slow');
            }
            if (value == 'SEN/AH' || value == 'SEN/HH') {
                $('#content_date_star_edit').show('slow');
                $('#content_date_end_edit').hide('slow');
            }
            if (value == 'SEN/HA') {
                $('#content_date_star_edit').show('slow');
                $('#content_date_end_edit').hide('slow');
            }
            if (value == 'REDHH' || value == 'RED') {
                $('#content_btn_search').removeClass('col-md-3 pl-3 pr-3');
                $('#content_btn_search').addClass('col-md-1');
                $('#content_date_star_edit').show('slow');
                $('#content_date_end_edit').show('slow');
            }
        });

        //Search Reservacion
        $(document).on('click', '#btn_search_reserva', function(e){
            e.preventDefault();
            let hotel = $('#inp_hotel').val();
            let pasajeros = $('#inp_pasajeros').val();
            let traslado = $('#inp_traslado').val();
            let f_llegada = $('#datepicker_star').val();
            let f_salida = $('#datepicker_end').val();
            if (hotel == null || hotel.length == 0 || /^\s+$/.test(hotel)) {
                $('#inp_hotel').addClass(" is-invalid");
                $('#inp_hotel').focus();
                return false;
            }
            if (pasajeros == null || pasajeros.length == 0 || /^\s+$/.test(pasajeros)) {
                $('#inp_pasajeros').addClass(" is-invalid");
                $('#inp_pasajeros').focus();
                return false;
            }
            if (traslado == null || traslado.length == 0 || /^\s+$/.test(traslado)) {
                $('#inp_traslado').addClass(" is-invalid");
                $('#inp_traslado').focus();
                return false;
            }
            if (f_llegada == null || f_llegada.length == 0 || /^\s+$/.test(f_llegada)) {
                $('#datepicker_star').addClass(" is-invalid");
                $('#datepicker_star').focus();
                return false;
            }
            if (traslado == 'REDHH' || traslado == 'RED') {
                if (f_salida == null || f_salida.length == 0 || /^\s+$/.test(f_salida)) {
                    $('#datepicker_end').addClass(" is-invalid");
                    $('#datepicker_end').focus();
                    return false;
                }    
            }
            const postDatas = {
                'hotel': hotel,
                'pasajeros': pasajeros,
                'traslado': traslado,
                'date_star': f_llegada,
                'date_end': f_salida,
                'search_traslado': true
            };
            $.ajax({
                data: postDatas,
                url: '../../helpers/traslados.php',
                type: 'post',
                beforeSend: function(){
                    let template = '';
                    template += `    
                        <div class="loader"></div>
                    `;
                    $('#loading').html(template);
                },
                success: function(data){
                    let inp_todaysale = $('#inp_todaysale').val();
                    let todaysale = 0;
                    if (inp_todaysale == 1 || inp_todaysale == 0) {
                        todaysale = $('#inp_todaysale').val();
                    }else{
                        todaysale = 0;
                    }
                    var minDateArg = todaysale == 1 ? 0 : '+1d';
                    $('#inp_hotel_edit').val(hotel);
                    $('#inp_pasajeros_edit').val(pasajeros);
                    $('#inp_traslado_edit').val(traslado);
                    $("#datepicker_star_edit").datepicker('setDate', new Date(f_llegada));
                    $("#datepicker_star_edit").datepicker( "option", "minDate", minDateArg );
                    if (f_salida) {
                        $("#datepicker_end_edit").datepicker( "option", "minDate", f_salida );
                        $("#datepicker_end_edit").datepicker('setDate', new Date(f_salida));
                    }else{
                        $('#content_date_end_edit').hide();
                        $('#content_btn_search').addClass('col-md-3 pl-3 pr-3');
                    }
                    $('.content_card_results').html(data);
                    $(".loader").fadeOut("slow");
                    $("div#content_search").hide( "drop", { direction: "left"}, "slow" );
                    loadResults();
                }
            });

        });

        //Search Reservaion Edit
        $(document).on('click', '#btn_search_reserva_edit', function(e){
            e.preventDefault();
            let hotel = $('#inp_hotel_edit').val();
            let pasajeros = $('#inp_pasajeros_edit').val();
            let traslado = $('#inp_traslado_edit').val();
            let f_llegada = $('#datepicker_star_edit').val();
            let f_salida = $('#datepicker_end_edit').val();
            if (hotel == null || hotel.length == 0 || /^\s+$/.test(hotel)) {
                $('#inp_hotel_edit').addClass(" is-invalid");
                $('#inp_hotel_edit').focus();
                return false;
            }
            if (pasajeros == null || pasajeros.length == 0 || /^\s+$/.test(pasajeros)) {
                $('#inp_pasajeros_edit').addClass(" is-invalid");
                $('#inp_pasajeros_edit').focus();
                return false;
            }
            if (traslado == null || traslado.length == 0 || /^\s+$/.test(traslado)) {
                $('#inp_traslado_edit').addClass(" is-invalid");
                $('#inp_traslado_edit').focus();
                return false;
            }
            if (f_llegada == null || f_llegada.length == 0 || /^\s+$/.test(f_llegada)) {
                $('#datepicker_star_edit').addClass(" is-invalid");
                $('#datepicker_star_edit').focus();
                return false;
            }
            if (traslado == 'REDHH' || traslado == 'RED') {
                if (f_salida == null || f_salida.length == 0 || /^\s+$/.test(f_salida)) {
                    $('#datepicker_end_edit').addClass(" is-invalid");
                    $('#datepicker_end_edit').focus();
                    return false;
                }    
            }
            const postDatas = {
                'hotel': hotel,
                'pasajeros': pasajeros,
                'traslado': traslado,
                'date_star': f_llegada,
                'date_end': f_salida,
                'search_traslado': true
            };
            $.ajax({
                data: postDatas,
                url: '../../helpers/traslados.php',
                type: 'post',
                beforeSend: function(){
                    let template = '';
                    template += `    
                        <div class="loader"></div>
                    `;
                    $('#loading').html(template);
                },
                success: function(data){
                    let inp_todaysale = $('#inp_todaysale').val();
                    let todaysale = 0;
                    if (inp_todaysale == 1 || inp_todaysale == 0) {
                        todaysale = $('#inp_todaysale').val();
                    }else{
                        todaysale = 0;
                    }
                    var minDateArg = todaysale == 1 ? 0 : '+1d';
                    $('#inp_hotel_edit').val(hotel);
                    $('#inp_pasajeros_edit').val(pasajeros);
                    $('#inp_traslado_edit').val(traslado);
                    $("#datepicker_star_edit").datepicker('setDate', new Date(f_llegada));
                    $("#datepicker_star_edit").datepicker( "option", "minDate", minDateArg );
                    if (f_salida) {
                        $("#datepicker_end_edit").datepicker( "option", "minDate", f_salida );
                        $("#datepicker_end_edit").datepicker('setDate', new Date(f_salida));
                    }else{
                        $('#content_date_end_edit').hide();
                        $('#content_btn_search').addClass('col-md-3 pl-3 pr-3');
                    }
                    $('.content_card_results').html(data);
                    $(".loader").fadeOut("slow");
                    $("div#content_search").hide( "drop", { direction: "left"}, "slow" );
                    loadResults();
                }
            });

        });

        function loadResults(){
            $("#content_results").show( "drop", { direction: "right" }, "slow" );
        }

        //Removemos class de Date 1
        $(document).on('click', '#datepicker_star', function(){
                $(this).removeClass(' is-invalid');
        });
        
        //Removemos class de Date 2
        $(document).on('click', '#datepicker_end', function(){
            $(this).removeClass(' is-invalid');
        });
        
        //Removemos class al cambiar
        $(document).on('change', '#form_reserva :input', function(){
            if ($.trim($(this).val()).length) {
                $(this).removeClass(' is-invalid');
            } else {
                $(this).addClass(' is-invalid');
            }
        });
}); 