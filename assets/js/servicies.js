$(function(){
    $('#sidebar, #content').toggleClass('active');
    let edit = false;
    $('#result-search').hide();
    $('#fecha_end').hide();
    loadReservations();
    let id = getParameterByName('reservation');
    loadMessagesReservation(id);
    $(document).ready(function(){
        function load(){
            loadMessagesReservation(id);
        }
        setInterval(load, 1000);
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
        let data = $('#search').val();
            
        if (data == null || data.length == 0 || /^\s+$/.test(data)) {
            alert('Debe ingresar algun nombre o ID.');
            $('#search').focus();
            return false;
        }
        if($('#search').val()) {
            let search = 'search';
            const postData = {
                'data': data,
                'search': search,
            }
            $.ajax({
                url:'../../helpers/servicios.php',
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
                    </div>
                    <div class="col-lg-4 col-md-3">
                    </div>  
                    `;
                    $('#container').html(template);
                    $("#table-data").hide('slow');
                    $('#result-search').show('slow');
                },
                success: function(response){
                    $('#search').val('');
                    if (!response.error) {
                        let reservas = JSON.parse(response);
                        if (reservas == '') {  
                            let template = '';
                            template += `
                                <div class='col-lg-11 pt-2'>
                                    <h6 class="p-2 font-weight-bold"> Búsqueda por ID o Cliente ${data} </h6>  
                                </div>
                                <div class='col-lg-1 pt-2 text-right'>
                                    <a href="#" class='btn btn-black btn-sm' id='cerrar_results'>X</a>
                                </div>
                                <div class="col-lg-12 pt-3">
                                    <br/>
                                    <p>No se encontro ningún servicio programado que coincida.</p>
                                </div>
                                ` ;
                                $('#container').html(template);
                                $('#hotel-result').show();
                        }else{
                            let template = '';
                            template += `
                                    <div class='col-lg-11 pt-2'>
                                        <h6 class="p-2 font-weight-bold"> Búsqueda por ID o Cliente ${data} </h6>   
                                    </div>
                                    <div class='col-lg-1 pt-2 text-right'>
                                        <a href="#" class='btn btn-black btn-sm' id='cerrar_results'>X</a>
                                    </div>
                                    <table class='table table-hover table-bordered table-sm' cellspacing='0' id='tablaAmenidades'>
                                        <thead class='thead-light'>
                                            <tr>
                                                <th>ID</th>
                                                <th>ID</th>
                                                <th>Cliente</th>
                                                <th>Destino</th>
                                                <th>Servicio</th>
                                                <th>Traslado</th>
                                                <th>Fecha Llegada</th>
                                                <th>Hora Llegada</th>
                                                <th>Fecha Salida</th>
                                                <th>Hora Salida</th>
                                                <th>Total</th>
                                                <th>Metodo pago</th>
                                                <th>Estado</th>
                                                <th>Proveedor</th>
                                                <th>REP</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                    <tbody> `;
                            reservas.forEach(reservas => {       
                                $newtype = '';
                                $newstatus = ``;
                                $newpayment = ``;
                                $currency = '';
                                $newdateArrvial = '';
                                $newtimeArrival = '';
                                $newdateExit = '';
                                $newtimeExit = '';
                                if (reservas.date_arrival == null || reservas.date_arrival == '') { $newdateArrvial= ''; } else{ $newdateArrvial = reservas.date_arrival;}
                                if (reservas.time_arrival == null || reservas.time_arrival == '') { $newtimeArrival= ''; } else{ $newtimeArrival = reservas.time_arrival + ' Hrs';}
                                if (reservas.date_exit == null || reservas.date_exit == '') { $newdateExit= ''; }else{ $newdateExit = reservas.date_exit;}
                                if (reservas.time_exit == null || reservas.time_exit == '') { $newtimeExit= ''; } else{ $newtimeExit = reservas.time_exit + ' Hrs';}
                                switch (reservas.method_payment) {
                                    case 'oxxo':
                                        $newnamepay = "OXXO";
                                        break;
                                    case 'transfer':
                                        $newnamepay = "TRANSFERENCIA";
                                        break;
                                    case 'airport':
                                        $newnamepay = "AEROPUERTO";
                                        break;
                                    case 'paypal':
                                        $newnamepay = "PAYPAL";
                                        break;
                                    case 'card':
                                        $newnamepay = "TARJETA";
                                        break;
                                    case 'deposit':
                                        $newnamepay = "DEPOSITO";
                                        break;
                                }
                                switch (reservas.status_reservation) {
                                    case 'RESERVED':
                                        $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                            <option value='${reservas.status_reservation}'>${reservas.status_reservation}</option>
                                            <option value='COMPLETED'>COMPLETED</option>
                                            <option value='NO SHOW'>NO SHOW</option>
                                            <option value='CANCELLED'>CANCELLED</option>
                                            <option value='REFUNDED'>REFUNDED</option>
                                        </select>`;
                                        break;
                                    case 'COMPLETED':
                                        $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                            <option value='{${reservas.status_reservation}}'>${reservas.status_reservation}</option>
                                            <option value='RESERVED'>RESERVED</option>
                                            <option value='NO SHOW'>NO SHOW</option>
                                            <option value='CANCELLED'>CANCELLED</option>
                                            <option value='REFUNDED'>REFUNDED</option>
                                        </select>`;
                                        break;
                                    case 'NO SHOW':
                                        $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                            <option value='{${reservas.status_reservation}}'>${reservas.status_reservation}</option>
                                            <option value='RESERVED'>RESERVED</option>
                                            <option value='COMPLETED'>COMPLETED</option>
                                            <option value='CANCELLED'>CANCELLED</option>
                                            <option value='REFUNDED'>REFUNDED</option>
                                        </select>`;
                                        break;
                                    case 'CANCELLED':
                                        $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                            <option value='{${reservas.status_reservation}}'>${reservas.status_reservation}</option>
                                            <option value='RESERVED'>RESERVED</option>
                                            <option value='COMPLETED'>COMPLETED</option>
                                            <option value='NO SHOW'>NO SHOW</option>
                                            <option value='REFUNDED'>REFUNDED</option>
                                        </select>`;
                                        break;
                                    case 'REFUNDED':
                                        $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                            <option value='{${reservas.status_reservation}}'>${reservas.status_reservation}</option>
                                            <option value='RESERVED'>RESERVED</option>
                                            <option value='COMPLETED'>COMPLETED</option>
                                            <option value='NO SHOW'>NO SHOW</option>
                                            <option value='CANCELLED'>CANCELLED</option>
                                        </select>`;
                                        break;
                                    default:
                                        $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                            <option value='deposit'>Sin asignar</option>
                                            <option value='RESERVED'>RESERVED</option>
                                            <option value='COMPLETED'>COMPLETED</option>
                                            <option value='NO SHOW'>NO SHOW</option>
                                            <option value='CANCELLED'>CANCELLED</option>
                                            <option value='REFUNDED'>REFUNDED</option>
                                        </select>`;
                                        break;
                                }
                                switch (reservas.method_payment) {
                                    case 'oxxo':
                                        $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                            <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                            <option value='transfer'>TRANSFERENCIA</option>
                                            <option value='airport'>AEROPUERTO</option>
                                            <option value='paypal'>PAYPAL</option>
                                            <option value='card'>TARJETA</option>
                                            <option value='deposit'>DEPOSITO</option>
                                        </select>`;
                                        break;
                                    case 'transfer':
                                        $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                        <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                            <option value='oxxo'>OXXO</option>
                                            <option value='airport'>AEROPUERTO</option>
                                            <option value='paypal'>PAYPAL</option>
                                            <option value='card'>TARJETA</option>
                                            <option value='deposit'>DEPOSITO</option>
                                        </select>`;
                                        break;
                                    case 'airport':
                                        $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>                                    
                                            <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                            <option value='oxxo'>OXXO</option>
                                            <option value='transfer'>TRANSFERENCIA</option>
                                            <option value='paypal'>PAYPAL</option>
                                            <option value='card'>TARJETA</option>
                                            <option value='deposit'>DEPOSITO</option>
                                        </select>`;
                                        break;
                                    case 'paypal':
                                        $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                        <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                            <option value='oxxo'>OXXO</option>
                                            <option value='transfer'>TRANSFERENCIA</option>
                                            <option value='airport'>AEROPUERTO</option>
                                            <option value='card'>TARJETA</option>
                                            <option value='deposit'>DEPOSITO</option>
                                        </select>`;
                                        break;
                                    case 'card':
                                        $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                        <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                            <option value='oxxo'>OXXO</option>
                                            <option value='transfer'>TRANSFERENCIA</option>
                                            <option value='airport'>AEROPUERTO</option>
                                            <option value='paypal'>PAYPAL</option>
                                            <option value='deposit'>DEPOSITO</option>
                                        </select>`;
                                        break;
                                    case 'deposit':
                                        $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                        <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                            <option value='oxxo'>OXXO</option>
                                            <option value='transfer'>TRANSFERENCIA</option>
                                            <option value='airport'>AEROPUERTO</option>
                                            <option value='paypal'>PAYPAL</option>
                                            <option value='card'>TARJETA</option>
                                        </select>`;
                                        break;
                                    default:
                                        $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                            <option value='deposit'>Sin asignar</option>
                                            <option value='deposit'>DEPOSITO</option>
                                            <option value='oxxo'>OXXO</option>
                                            <option value='transfer'>TRANSFERENCIA</option>
                                            <option value='airport'>AEROPUERTO</option>
                                            <option value='paypal'>PAYPAL</option>
                                            <option value='card'>TARJETA</option>
                                        </select>`;
                                        break;
                                }
                                if (reservas.type_transfer == 'SEN/AH' ) {
                                    $newtype = 'Aeorpuerto - Hotel ';
                                }
                                if (reservas.type_transfer == 'SEN/HA' ) {
                                    $newtype = 'Hotel - Aeorpuerto ';
                                }
                                if (reservas.type_transfer == 'RED' ) {
                                    $newtype = 'Redondo';
                                }
                                if (reservas.type_transfer == 'REDHH' ) {
                                    $newtype = 'Hotel - Hotel ';
                                }
                                if (reservas.type_transfer == 'SEN/HH' ) {
                                    $newtype = 'Hotel - Hotel ';
                                }
                                $total = reservas.total_cost - reservas.agency_commision;
                                if (reservas.type_currency == 'RED') {
                                    $total = (reservas.total_cost - reservas.agency_commision) / 2;
                                }
                                if (reservas.type_currency == 'mx') {
                                    $currency = 'MXN';
                                }
                                if (reservas.type_currency == 'us') {
                                    $currency = 'USD';
                                }
                                if (reservas.type_currency == 'pt') {
                                    $currency = 'USD';
                                }
                                if (reservas.date_arrival == reservas.today) {
                                    template += `
                                                <tr> 
                                                    <td colspan = "17" style="background: #3F80EA; color: #fff;">L L E G A D A</td>
                                                </tr>
                                                <tr reserva-id='${reservas.id_reservation}'>
                                                        <td>${reservas.id_reservation}</td>
                                                        <td class="font-weight-bold">${reservas.code_invoice}</td>
                                                        <td>${reservas.name_client}</td>
                                                        <td>${reservas.transfer_destiny}</td>
                                                        <td>${reservas.type_service}</td>
                                                        <td>${$newtype}</td>
                                                        <td class="font-weight-bold">${$newdateArrvial}</td>
                                                        <td class="font-weight-bold">${$newtimeArrival}</td>
                                                        <td>${$newdateExit}</td>
                                                        <td>${$newtimeExit}</td>
                                                        <td>${$total + ' ' + $currency} </td>
                                                        <td>${$newpayment}</td>
                                                        <td>${$newstatus}</td>
                                                        <td><a href='#' id='select_provider' datare='${reservas.id_reservation}'  data='${reservas.provider}' datatag='entrada' datainvoice='${reservas.code_invoice}' dataaction='${reservas.new_typeaction}' dataservice='A' data-toggle='modal' data-target='#providerModal'> ${reservas.provider}</a></td>
                                                        <td><a href='#' id='select_rep' data-toggle='modal'  datarep='${reservas.rep}' datatag='entrada' datare='${reservas.id_reservation}' dataservice='A' datainvoice='${reservas.code_invoice}' dataaction='${reservas.new_trpeactionrep}' data-target='#repModal'> ${reservas.rep}</a></td>
                                                        <td class='text-center p-2'>
                                                        <a   href='reservation_profile.php?reservation=${reservas.new_id_reservation}&coinv=${reservas.code_invoice}' target='_blank' title='ver detalles' id='view_details'><i class='far fa-eye'></i></a>
                                                        </td>
                                                        <td class='text-center p-2'>
                                                            <a href='#' id='reserva-pay' title='Conciliar' class=''><i class='fas fa-dollar-sign'></i></a>
                                                        </td>
                                                </tr>`;    
                                }else if (reservas.date_exit == reservas.today) {
                                    template += `
                                                <tr> 
                                                    <td colspan = "17" style="background: #495057; color: #fff;">S A L I D A </td>
                                                </tr>
                                                <tr reserva-id='${reservas.id_reservation}'>
                                                        <td>${reservas.id_reservation}</td>
                                                        <td class="font-weight-bold">${reservas.code_invoice}</td>
                                                        <td>${reservas.name_client}</td>
                                                        <td>${reservas.transfer_destiny}</td>
                                                        <td>${reservas.type_service}</td>
                                                        <td>${$newtype}</td>
                                                        <td>${$newdateArrvial}</td>
                                                        <td>${$newtimeArrival} </td>
                                                        <td class="font-weight-bold">${$newdateExit}</td>
                                                        <td class="font-weight-bold">${$newtimeExit}</td>
                                                        <td>${$total + ' ' + $currency} </td>
                                                        <td>${$newpayment}</td>
                                                        <td>${$newstatus}</td>
                                                        <td><a href='#' id='select_provider' datare='${reservas.id_reservation}'  data='${reservas.provider_salida}' datatag='salida' datainvoice='${reservas.code_invoice}' dataaction='${reservas.new_typeaction}' dataservice='D' data-toggle='modal' data-target='#providerModal'> ${reservas.provider_salida}</a></td>
                                                        <td><a href='#' id='select_rep' data-toggle='modal'  datarep='${reservas.rep_salida}' datatag='salida' datare='${reservas.id_reservation}' dataservice='A' datainvoice='${reservas.code_invoice}' dataaction='${reservas.new_trpeactionrep}' data-target='#repModal'> ${reservas.rep_salida}</a></td>
                                                        <td class='text-center p-2'>
                                                        <a   href='reservation_profile.php?reservation=${reservas.new_id_reservation}&coinv=${reservas.code_invoice}' target='_blank' title='ver detalles' id='view_details'><i class='far fa-eye'></i></a>
                                                        </td>
                                                        <td class='text-center p-2'>
                                                            <a href='#' id='reserva-pay' title='Conciliar' class=''><i class='fas fa-dollar-sign'></i></a>
                                                        </td>
                                                </tr>`;    
                                    
                                }else{
                                    template += `
                                    <tr> 
                                        <td colspan = "17" style="background: #E5A54B; color: #fff;">S I N - A S I G N A R</td>
                                    </tr>
                                    <tr reserva-id='${reservas.id_reservation}'>
                                            <td>${reservas.id_reservation}</td>
                                            <td class="font-weight-bold">${reservas.code_invoice}</td>
                                            <td>${reservas.name_client}</td>
                                            <td>${reservas.transfer_destiny}</td>
                                            <td>${reservas.type_service}</td>
                                            <td>${$newtype}</td>
                                            <td class="font-weight-bold" >${$newdateArrvial}</td>
                                            <td class="font-weight-bold" >${$newtimeArrival}</td>
                                            <td class="font-weight-bold">${$newdateExit}</td>
                                            <td class="font-weight-bold">${$newtimeExit}</td>
                                            <td>${$total + ' ' + $currency} </td>
                                            <td>${$newpayment}</td>
                                            <td>${$newstatus}</td>
                                            <td><a href='#' id='select_provider' datare='${reservas.id_reservation}'  data='${reservas.provider_salida}' datatag='salida' datainvoice='${reservas.code_invoice}' dataaction='${reservas.new_typeaction}' dataservice='D' data-toggle='modal' data-target='#providerModal'> ${reservas.provider_salida}</a></td>
                                            <td><a href='#' id='select_rep' data-toggle='modal'  datarep='${reservas.rep_salida}' datatag='salida' datare='${reservas.id_reservation}' dataservice='A' datainvoice='${reservas.code_invoice}' dataaction='${reservas.new_trpeactionrep}' data-target='#repModal'> ${reservas.rep_salida}</a></td>
                                            <td class='text-center p-2'>
                                            <a   href='reservation_profile.php?reservation=${reservas.new_id_reservation}&coinv=${reservas.code_invoice}' target='_blank' title='ver detalles' id='view_details'><i class='far fa-eye'></i></a>
                                            </td>
                                            <td class='text-center p-2'>
                                                <a href='#' id='reserva-pay' title='Conciliar' class=''><i class='fas fa-dollar-sign'></i></a>
                                            </td>
                                    </tr>`;  
                                }
                            });
                            $('#container').html(template);
                            $('#result-search').show('slow');
                        }
                    }
                }
            });
        }else{  
            $('#result-search').hide('slow');
            document.getElementById("resultSearch").className = "col-lg-12";
        }
        e.preventDefault();
    });

    // Buscar reserva por agencia 
    $('#form-search-agency').submit(function(e){
        let data = $('#name_agency').val();
        if (data == null || data.length == 0 || /^\s+$/.test(data)) {
            alert('Debes seleccionar alguna agencia.');
            $('#name_agency').focus();
            return false;
        }
        if($('#name_agency').val()) {
            let search = 'search';
            const postData = {
                'data': data,
                'search': search,
            }
            $.ajax({
                url:'../../helpers/servicios.php',
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
                    </div>
                    <div class="col-lg-4 col-md-3">
                    </div>  
                        
                    `;
                    
                    $('#container').html(template);
                    $("#table-data").hide('slow');
                    $('#result-search').show('slow');
                },
                success: function(response){
                    $('#name_agency').val('');
                    if (!response.error) {
                        let reservas = JSON.parse(response);
                        if (reservas == '') {  
                            let template = '';
                            template += `
                                <div class='col-lg-11 pt-2'>
                                    <h6 class="p-2 font-weight-bold"> Búsqueda por la agencia ${data} </h6>
                                </div>
                                <div class='col-lg-1 pt-2 text-right'>
                                    <a href="#" class='btn btn-black btn-sm' id='cerrar_results'>X</a>
                                </div>
                                <div class="col-lg-12 pt-3">
                                    <br/>
                                    <p>No se encontro ninguna reservación que coincida.</p>
                                </div>
                                ` ;
                                $('#container').html(template);
                                $('#hotel-result').show();
                        }else{
                            let template = '';
                            template += `
                                    <div class='col-lg-11 pt-2'>
                                        <h6 class="p-2 font-weight-bold"> Búsqueda de los servicios de la agencia ${data} del año actual </h6>
                                    </div>
                                    <div class='col-lg-1 pt-2 text-right'>
                                        <a href="#" class='btn btn-black btn-sm' id='cerrar_results'>X</a>
                                    </div>
                                    <table class='table table-hover table-bordered table-sm' cellspacing='0' id='tablaAmenidades'>
                                        <thead class='thead-light'>
                                            <tr>
                                                <th>ID</th>
                                                <th>ID</th>
                                                <th>Cliente</th>
                                                <th>Destino</th>
                                                <th>Servicio</th>
                                                <th>Traslado</th>
                                                <th>Fecha Llegada</th>
                                                <th>Hora Llegada</th>
                                                <th>Fecha Salida</th>
                                                <th>Hora Salida</th>
                                                <th>Total</th>
                                                <th>Metodo pago</th>
                                                <th>Estado</th>
                                                <th>Proveedor</th>
                                                <th>REP</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                    <tbody> `;
                                    reservas.forEach(reservas => {       
                                        $newtype = '';
                                        $newstatus = ``;
                                        $newpayment = ``;
                                        $currency = '';
                                        $newdateArrvial = '';
                                        $newtimeArrival = '';
                                        $newdateExit = '';
                                        $newtimeExit = '';
                                        if (reservas.date_arrival == null || reservas.date_arrival == '') { $newdateArrvial= ''; } else{ $newdateArrvial = reservas.date_arrival;}
                                        if (reservas.time_arrival == null || reservas.time_arrival == '') { $newtimeArrival= ''; } else{ $newtimeArrival = reservas.time_arrival + ' Hrs';}
                                        if (reservas.date_exit == null || reservas.date_exit == '') { $newdateExit= ''; }else{ $newdateExit = reservas.date_exit;}
                                        if (reservas.time_exit == null || reservas.time_exit == '') { $newtimeExit= ''; } else{ $newtimeExit = reservas.time_exit + ' Hrs';}
                                        switch (reservas.method_payment) {
                                            case 'oxxo':
                                                $newnamepay = "OXXO";
                                                break;
                                            case 'transfer':
                                                $newnamepay = "TRANSFERENCIA";
                                                break;
                                            case 'airport':
                                                $newnamepay = "AEROPUERTO";
                                                break;
                                            case 'paypal':
                                                $newnamepay = "PAYPAL";
                                                break;
                                            case 'card':
                                                $newnamepay = "TARJETA";
                                                break;
                                            case 'deposit':
                                                $newnamepay = "DEPOSITO";
                                                break;
                                        }
                                        switch (reservas.status_reservation) {
                                            case 'RESERVED':
                                                $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                                    <option value='${reservas.status_reservation}'>${reservas.status_reservation}</option>
                                                    <option value='COMPLETED'>COMPLETED</option>
                                                    <option value='NO SHOW'>NO SHOW</option>
                                                    <option value='CANCELLED'>CANCELLED</option>
                                                    <option value='REFUNDED'>REFUNDED</option>
                                                </select>`;
                                                break;
                                            case 'COMPLETED':
                                                $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                                    <option value='{${reservas.status_reservation}}'>${reservas.status_reservation}</option>
                                                    <option value='RESERVED'>RESERVED</option>
                                                    <option value='NO SHOW'>NO SHOW</option>
                                                    <option value='CANCELLED'>CANCELLED</option>
                                                    <option value='REFUNDED'>REFUNDED</option>
                                                </select>`;
                                                break;
                                            case 'NO SHOW':
                                                $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                                    <option value='{${reservas.status_reservation}}'>${reservas.status_reservation}</option>
                                                    <option value='RESERVED'>RESERVED</option>
                                                    <option value='COMPLETED'>COMPLETED</option>
                                                    <option value='CANCELLED'>CANCELLED</option>
                                                    <option value='REFUNDED'>REFUNDED</option>
                                                </select>`;
                                                break;
                                            case 'CANCELLED':
                                                $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                                    <option value='{${reservas.status_reservation}}'>${reservas.status_reservation}</option>
                                                    <option value='RESERVED'>RESERVED</option>
                                                    <option value='COMPLETED'>COMPLETED</option>
                                                    <option value='NO SHOW'>NO SHOW</option>
                                                    <option value='REFUNDED'>REFUNDED</option>
                                                </select>`;
                                                break;
                                            case 'REFUNDED':
                                                $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                                    <option value='{${reservas.status_reservation}}'>${reservas.status_reservation}</option>
                                                    <option value='RESERVED'>RESERVED</option>
                                                    <option value='COMPLETED'>COMPLETED</option>
                                                    <option value='NO SHOW'>NO SHOW</option>
                                                    <option value='CANCELLED'>CANCELLED</option>
                                                </select>`;
                                                break;
                                            default:
                                                $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                                    <option value='deposit'>Sin asignar</option>
                                                    <option value='RESERVED'>RESERVED</option>
                                                    <option value='COMPLETED'>COMPLETED</option>
                                                    <option value='NO SHOW'>NO SHOW</option>
                                                    <option value='CANCELLED'>CANCELLED</option>
                                                    <option value='REFUNDED'>REFUNDED</option>
                                                </select>`;
                                                break;
                                        }
                                        switch (reservas.method_payment) {
                                            case 'oxxo':
                                                $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                                    <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                                    <option value='transfer'>TRANSFERENCIA</option>
                                                    <option value='airport'>AEROPUERTO</option>
                                                    <option value='paypal'>PAYPAL</option>
                                                    <option value='card'>TARJETA</option>
                                                    <option value='deposit'>DEPOSITO</option>
                                                </select>`;
                                                break;
                                            case 'transfer':
                                                $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                                <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                                    <option value='oxxo'>OXXO</option>
                                                    <option value='airport'>AEROPUERTO</option>
                                                    <option value='paypal'>PAYPAL</option>
                                                    <option value='card'>TARJETA</option>
                                                    <option value='deposit'>DEPOSITO</option>
                                                </select>`;
                                                break;
                                            case 'airport':
                                                $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>                                    
                                                    <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                                    <option value='oxxo'>OXXO</option>
                                                    <option value='transfer'>TRANSFERENCIA</option>
                                                    <option value='paypal'>PAYPAL</option>
                                                    <option value='card'>TARJETA</option>
                                                    <option value='deposit'>DEPOSITO</option>
                                                </select>`;
                                                break;
                                            case 'paypal':
                                                $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                                <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                                    <option value='oxxo'>OXXO</option>
                                                    <option value='transfer'>TRANSFERENCIA</option>
                                                    <option value='airport'>AEROPUERTO</option>
                                                    <option value='card'>TARJETA</option>
                                                    <option value='deposit'>DEPOSITO</option>
                                                </select>`;
                                                break;
                                            case 'card':
                                                $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                                <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                                    <option value='oxxo'>OXXO</option>
                                                    <option value='transfer'>TRANSFERENCIA</option>
                                                    <option value='airport'>AEROPUERTO</option>
                                                    <option value='paypal'>PAYPAL</option>
                                                    <option value='deposit'>DEPOSITO</option>
                                                </select>`;
                                                break;
                                            case 'deposit':
                                                $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                                <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                                    <option value='oxxo'>OXXO</option>
                                                    <option value='transfer'>TRANSFERENCIA</option>
                                                    <option value='airport'>AEROPUERTO</option>
                                                    <option value='paypal'>PAYPAL</option>
                                                    <option value='card'>TARJETA</option>
                                                </select>`;
                                                break;
                                            default:
                                                $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                                    <option value='deposit'>Sin asignar</option>
                                                    <option value='deposit'>DEPOSITO</option>
                                                    <option value='oxxo'>OXXO</option>
                                                    <option value='transfer'>TRANSFERENCIA</option>
                                                    <option value='airport'>AEROPUERTO</option>
                                                    <option value='paypal'>PAYPAL</option>
                                                    <option value='card'>TARJETA</option>
                                                </select>`;
                                                break;
                                        }
                                        if (reservas.type_transfer == 'SEN/AH' ) {
                                            $newtype = 'Aeorpuerto - Hotel ';
                                        }
                                        if (reservas.type_transfer == 'SEN/HA' ) {
                                            $newtype = 'Hotel - Aeorpuerto ';
                                        }
                                        if (reservas.type_transfer == 'RED' ) {
                                            $newtype = 'Redondo';
                                        }
                                        if (reservas.type_transfer == 'REDHH' ) {
                                            $newtype = 'Hotel - Hotel ';
                                        }
                                        if (reservas.type_transfer == 'SEN/HH' ) {
                                            $newtype = 'Hotel - Hotel ';
                                        }
                                        $total = reservas.total_cost - reservas.agency_commision;
                                        if (reservas.type_currency == 'RED') {
                                            $total = (reservas.total_cost - reservas.agency_commision) / 2;
                                        }
                                        if (reservas.type_currency == 'mx') {
                                            $currency = 'MXN';
                                        }
                                        if (reservas.type_currency == 'us') {
                                            $currency = 'USD';
                                        }
                                        if (reservas.type_currency == 'pt') {
                                            $currency = 'USD';
                                        }
                                        if (reservas.date_arrival == reservas.today) {
                                            template += `
                                                        <tr> 
                                                            <td colspan = "17" style="background: #3F80EA; color: #fff;">L L E G A D A</td>
                                                        </tr>
                                                        <tr reserva-id='${reservas.id_reservation}'>
                                                                <td>${reservas.id_reservation}</td>
                                                                <td class="font-weight-bold">${reservas.code_invoice}</td>
                                                                <td>${reservas.name_client}</td>
                                                                <td>${reservas.transfer_destiny}</td>
                                                                <td>${reservas.type_service}</td>
                                                                <td>${$newtype}</td>
                                                                <td class="font-weight-bold">${$newdateArrvial}</td>
                                                                <td class="font-weight-bold">${$newtimeArrival}</td>
                                                                <td>${$newdateExit}</td>
                                                                <td>${$newtimeExit}</td>
                                                                <td>${$total + ' ' + $currency} </td>
                                                                <td>${$newpayment}</td>
                                                                <td>${$newstatus}</td>
                                                                <td><a href='#' id='select_provider' datare='${reservas.id_reservation}'  data='${reservas.provider}' datatag='entrada' datainvoice='${reservas.code_invoice}' dataaction='${reservas.new_typeaction}' dataservice='A' data-toggle='modal' data-target='#providerModal'> ${reservas.provider}</a></td>
                                                                <td><a href='#' id='select_rep' data-toggle='modal'  datarep='${reservas.rep}' datatag='entrada' datare='${reservas.id_reservation}' dataservice='A' datainvoice='${reservas.code_invoice}' dataaction='${reservas.new_trpeactionrep}' data-target='#repModal'> ${reservas.rep}</a></td>
                                                                <td class='text-center p-2'>
                                                        <a   href='reservation_profile.php?reservation=${reservas.new_id_reservation}&coinv=${reservas.code_invoice}' target='_blank' title='ver detalles' id='view_details'><i class='far fa-eye'></i></a>
                                                                </td>
                                                                <td class='text-center p-2'>
                                                                    <a href='#' id='reserva-pay' title='Conciliar' class=''><i class='fas fa-dollar-sign'></i></a>
                                                                </td>
                                                        </tr>`;    
                                        }else if (reservas.date_exit == reservas.today) {
                                            template += `
                                                        <tr> 
                                                            <td colspan = "17" style="background: #495057; color: #fff;">S A L I D A </td>
                                                        </tr>
                                                        <tr reserva-id='${reservas.id_reservation}'>
                                                                <td>${reservas.id_reservation}</td>
                                                                <td class="font-weight-bold">${reservas.code_invoice}</td>
                                                                <td>${reservas.name_client}</td>
                                                                <td>${reservas.transfer_destiny}</td>
                                                                <td>${reservas.type_service}</td>
                                                                <td>${$newtype}</td>
                                                                <td>${$newdateArrvial}</td>
                                                                <td>${$newtimeArrival} </td>
                                                                <td class="font-weight-bold">${$newdateExit}</td>
                                                                <td class="font-weight-bold">${$newtimeExit}</td>
                                                                <td>${$total + ' ' + $currency} </td>
                                                                <td>${$newpayment}</td>
                                                                <td>${$newstatus}</td>
                                                                <td><a href='#' id='select_provider' datare='${reservas.id_reservation}'  data='${reservas.provider_salida}' datatag='salida' datainvoice='${reservas.code_invoice}' dataaction='${reservas.new_typeaction}' dataservice='D' data-toggle='modal' data-target='#providerModal'> ${reservas.provider_salida}</a></td>
                                                                <td><a href='#' id='select_rep' data-toggle='modal'  datarep='${reservas.rep_salida}' datatag='salida' datare='${reservas.id_reservation}' dataservice='A' datainvoice='${reservas.code_invoice}' dataaction='${reservas.new_trpeactionrep}' data-target='#repModal'> ${reservas.rep_salida}</a></td>
                                                                <td class='text-center p-2'>
                                                        <a   href='reservation_profile.php?reservation=${reservas.new_id_reservation}&coinv=${reservas.code_invoice}' target='_blank' title='ver detalles' id='view_details'><i class='far fa-eye'></i></a>
                                                                </td>
                                                                <td class='text-center p-2'>
                                                                    <a href='#' id='reserva-pay' title='Conciliar' class=''><i class='fas fa-dollar-sign'></i></a>
                                                                </td>
                                                        </tr>`;    
                                            
                                        }else{
                                            template += `
                                            <tr> 
                                                <td colspan = "17" style="background: #E5A54B; color: #fff;">S I N - A S I G N A R</td>
                                            </tr>
                                            <tr reserva-id='${reservas.id_reservation}'>
                                                    <td>${reservas.id_reservation}</td>
                                                    <td class="font-weight-bold">${reservas.code_invoice}</td>
                                                    <td>${reservas.name_client}</td>
                                                    <td>${reservas.transfer_destiny}</td>
                                                    <td>${reservas.type_service}</td>
                                                    <td>${$newtype}</td>
                                                    <td class="font-weight-bold" >${$newdateArrvial}</td>
                                                    <td class="font-weight-bold" >${$newtimeArrival}</td>
                                                    <td class="font-weight-bold">${$newdateExit}</td>
                                                    <td class="font-weight-bold">${$newtimeExit}</td>
                                                    <td>${$total + ' ' + $currency} </td>
                                                    <td>${$newpayment}</td>
                                                    <td>${$newstatus}</td>
                                                    <td><a href='#' id='select_provider' datare='${reservas.id_reservation}'  data='${reservas.provider_salida}' datatag='salida' datainvoice='${reservas.code_invoice}' dataaction='${reservas.new_typeaction}' dataservice='D' data-toggle='modal' data-target='#providerModal'> ${reservas.provider_salida}</a></td>
                                                    <td><a href='#' id='select_rep' data-toggle='modal'  datarep='${reservas.rep_salida}' datatag='salida' datare='${reservas.id_reservation}' dataservice='A' datainvoice='${reservas.code_invoice}' dataaction='${reservas.new_trpeactionrep}' data-target='#repModal'> ${reservas.rep_salida}</a></td>
                                                    <td class='text-center p-2'>
                                                        <a href='#' id='reserva-view' title='ver detalles' class=''><i class='fas fa-eye'></i></a>
                                                    </td>
                                                    <td class='text-center p-2'>
                                                        <a href='#' id='reserva-pay' title='Conciliar' class=''><i class='fas fa-dollar-sign'></i></a>
                                                    </td>
                                            </tr>`;  
                                        }
                                    });
                            $('#container').html(template);
                            $('#result-search').show('slow');
                        }
                    }
                }
            });
        }else{  
            $('#result-search').hide('slow');
            document.getElementById("resultSearch").className = "col-lg-12";
        }
        e.preventDefault();
    });    

    // datepicker
    $( function() {
        var $datepicker2 = $( "#datepicker_end" );
        $('#datepicker_star').datepicker( {
            language: 'es',
            minDate: new Date(),
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
        let star = $('#datepicker_star').val();
        let end = $('#datepicker_end').val();
        
        if (star == null || star.length == 0) {
            alert('Debes seleccionar una fecha');
            $('#datepicker_star').focus();
            return false;
        }
        if (star) {
            if (!end) {
                end = 0;
            }
            let search = 'search_date';
            const postData ={
                'star': star,
                'end': end,
                'search_date': search
            }
            if (postData.star && postData.end != 0) {
                
                if (end < star) {
                    alert('La fecha final no puede ser menor a la inicial');
                    $('#datepicker_end').focus();
                    return false;
                }
                $.ajax({
                    url:'../../helpers/servicios.php',
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
                            let reservas = JSON.parse(response);
                            if (reservas == '') {  
                                let template = '';
                                template += `
                                        <div class='col-lg-11 pt-2'>
                                            <h6 class="p-2 font-weight-bold"> Búsqueda de las fechas ${star} al ${end} </h6>    
                                        </div>
                                        <div class='col-lg-1 pt-2 text-right'>
                                            <a href="#" class='btn btn-black btn-sm' id='cerrar_results'>X</a>
                                        </div>  
                                    <br/>
                                    <div class="col-lg-12 pt-3">
                                        <p>No se encontro ninguna reservación que coincida.</p>
                                    </div>
                                    ` ;
                                    $('#fecha_end').hide();
                                    $('#form-date').trigger('reset');
                                    $('#container').html(template);
                                    $('#hotel-result').show();
                            }else{
                                let template = '';
                                template +=`
                                <div class='col-lg-11 pt-2'>
                                    <h6 class="p-2 font-weight-bold"> Búsqueda de las fechas ${star} al ${end} </h6>    
                                </div>
                                <div class='col-lg-1 pt-2 text-right'>
                                    <a href="#" class='btn btn-black btn-sm' id='cerrar_results'>X</a>
                                </div>  `;
                                template += `
                                        <table class='table table-hover table-bordered table-sm' cellspacing='0' id='tablaAmenidades'>
                                            <thead class='thead-light'>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>ID</th>
                                                    <th>Cliente</th>
                                                    <th>Destino</th>
                                                    <th>Servicio</th>
                                                    <th>Traslado</th>
                                                    <th>Fecha Llegada</th>
                                                    <th>Hora Llegada</th>
                                                    <th>Fecha Salida</th>
                                                    <th>Hora Salida</th>
                                                    <th>Total</th>
                                                    <th>Metodo pago</th>
                                                    <th>Estado</th>
                                                    <th>Proveedor</th>
                                                    <th>REP</th>
                                                    <th></th>
                                                    <th></th>
                                                    </tr>
                                            </thead>
                                        <tbody> `;
                                reservas.forEach(reservas => {       
                                    $newtype = '';
                                    $newstatus = ``;
                                    $newpayment = ``;
                                    $currency = '';
                                    $newdateArrvial = '';
                                    $newtimeArrival = '';
                                    $newdateExit = '';
                                    $newtimeExit = '';
                                    if (reservas.date_arrival == null || reservas.date_arrival == '') { $newdateArrvial= ''; } else{ $newdateArrvial = reservas.date_arrival;}
                                    if (reservas.time_arrival == null || reservas.time_arrival == '') { $newtimeArrival= ''; } else{ $newtimeArrival = reservas.time_arrival + ' Hrs';}
                                    if (reservas.date_exit == null || reservas.date_exit == '') { $newdateExit= ''; }else{ $newdateExit = reservas.date_exit;}
                                    if (reservas.time_exit == null || reservas.time_exit == '') { $newtimeExit= ''; } else{ $newtimeExit = reservas.time_exit + ' Hrs';}
                                    switch (reservas.method_payment) {
                                        case 'oxxo':
                                            $newnamepay = "OXXO";
                                            break;
                                        case 'transfer':
                                            $newnamepay = "TRANSFERENCIA";
                                            break;
                                        case 'airport':
                                            $newnamepay = "AEROPUERTO";
                                            break;
                                        case 'paypal':
                                            $newnamepay = "PAYPAL";
                                            break;
                                        case 'card':
                                            $newnamepay = "TARJETA";
                                            break;
                                        case 'deposit':
                                            $newnamepay = "DEPOSITO";
                                            break;
                                    }
                                    switch (reservas.status_reservation) {
                                        case 'RESERVED':
                                            $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                                <option value='${reservas.status_reservation}'>${reservas.status_reservation}</option>
                                                <option value='COMPLETED'>COMPLETED</option>
                                                <option value='NO SHOW'>NO SHOW</option>
                                                <option value='CANCELLED'>CANCELLED</option>
                                                <option value='REFUNDED'>REFUNDED</option>
                                            </select>`;
                                            break;
                                        case 'COMPLETED':
                                            $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                                <option value='{${reservas.status_reservation}}'>${reservas.status_reservation}</option>
                                                <option value='RESERVED'>RESERVED</option>
                                                <option value='NO SHOW'>NO SHOW</option>
                                                <option value='CANCELLED'>CANCELLED</option>
                                                <option value='REFUNDED'>REFUNDED</option>
                                            </select>`;
                                            break;
                                        case 'NO SHOW':
                                            $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                                <option value='{${reservas.status_reservation}}'>${reservas.status_reservation}</option>
                                                <option value='RESERVED'>RESERVED</option>
                                                <option value='COMPLETED'>COMPLETED</option>
                                                <option value='CANCELLED'>CANCELLED</option>
                                                <option value='REFUNDED'>REFUNDED</option>
                                            </select>`;
                                            break;
                                        case 'CANCELLED':
                                            $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                                <option value='{${reservas.status_reservation}}'>${reservas.status_reservation}</option>
                                                <option value='RESERVED'>RESERVED</option>
                                                <option value='COMPLETED'>COMPLETED</option>
                                                <option value='NO SHOW'>NO SHOW</option>
                                                <option value='REFUNDED'>REFUNDED</option>
                                            </select>`;
                                            break;
                                        case 'REFUNDED':
                                            $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                                <option value='{${reservas.status_reservation}}'>${reservas.status_reservation}</option>
                                                <option value='RESERVED'>RESERVED</option>
                                                <option value='COMPLETED'>COMPLETED</option>
                                                <option value='NO SHOW'>NO SHOW</option>
                                                <option value='CANCELLED'>CANCELLED</option>
                                            </select>`;
                                            break;
                                        default:
                                            $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                                <option value='deposit'>Sin asignar</option>
                                                <option value='RESERVED'>RESERVED</option>
                                                <option value='COMPLETED'>COMPLETED</option>
                                                <option value='NO SHOW'>NO SHOW</option>
                                                <option value='CANCELLED'>CANCELLED</option>
                                                <option value='REFUNDED'>REFUNDED</option>
                                            </select>`;
                                            break;
                                    }
                                    switch (reservas.method_payment) {
                                        case 'oxxo':
                                            $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                                <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                                <option value='transfer'>TRANSFERENCIA</option>
                                                <option value='airport'>AEROPUERTO</option>
                                                <option value='paypal'>PAYPAL</option>
                                                <option value='card'>TARJETA</option>
                                                <option value='deposit'>DEPOSITO</option>
                                            </select>`;
                                            break;
                                        case 'transfer':
                                            $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                            <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                                <option value='oxxo'>OXXO</option>
                                                <option value='airport'>AEROPUERTO</option>
                                                <option value='paypal'>PAYPAL</option>
                                                <option value='card'>TARJETA</option>
                                                <option value='deposit'>DEPOSITO</option>
                                            </select>`;
                                            break;
                                        case 'airport':
                                            $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>                                    
                                                <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                                <option value='oxxo'>OXXO</option>
                                                <option value='transfer'>TRANSFERENCIA</option>
                                                <option value='paypal'>PAYPAL</option>
                                                <option value='card'>TARJETA</option>
                                                <option value='deposit'>DEPOSITO</option>
                                            </select>`;
                                            break;
                                        case 'paypal':
                                            $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                            <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                                <option value='oxxo'>OXXO</option>
                                                <option value='transfer'>TRANSFERENCIA</option>
                                                <option value='airport'>AEROPUERTO</option>
                                                <option value='card'>TARJETA</option>
                                                <option value='deposit'>DEPOSITO</option>
                                            </select>`;
                                            break;
                                        case 'card':
                                            $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                            <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                                <option value='oxxo'>OXXO</option>
                                                <option value='transfer'>TRANSFERENCIA</option>
                                                <option value='airport'>AEROPUERTO</option>
                                                <option value='paypal'>PAYPAL</option>
                                                <option value='deposit'>DEPOSITO</option>
                                            </select>`;
                                            break;
                                        case 'deposit':
                                            $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                            <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                                <option value='oxxo'>OXXO</option>
                                                <option value='transfer'>TRANSFERENCIA</option>
                                                <option value='airport'>AEROPUERTO</option>
                                                <option value='paypal'>PAYPAL</option>
                                                <option value='card'>TARJETA</option>
                                            </select>`;
                                            break;
                                        default:
                                            $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                                <option value='deposit'>Sin asignar</option>
                                                <option value='deposit'>DEPOSITO</option>
                                                <option value='oxxo'>OXXO</option>
                                                <option value='transfer'>TRANSFERENCIA</option>
                                                <option value='airport'>AEROPUERTO</option>
                                                <option value='paypal'>PAYPAL</option>
                                                <option value='card'>TARJETA</option>
                                            </select>`;
                                            break;
                                    }
                                    if (reservas.type_transfer == 'SEN/AH' ) {
                                        $newtype = 'Aeorpuerto - Hotel ';
                                    }
                                    if (reservas.type_transfer == 'SEN/HA' ) {
                                        $newtype = 'Hotel - Aeorpuerto ';
                                    }
                                    if (reservas.type_transfer == 'RED' ) {
                                        $newtype = 'Redondo';
                                    }
                                    if (reservas.type_transfer == 'REDHH' ) {
                                        $newtype = 'Hotel - Hotel ';
                                    }
                                    if (reservas.type_transfer == 'SEN/HH' ) {
                                        $newtype = 'Hotel - Hotel ';
                                    }
                                    $total = reservas.total_cost - reservas.agency_commision;
                                    if (reservas.type_currency == 'RED') {
                                        $total = (reservas.total_cost - reservas.agency_commision) / 2;
                                    }
                                    if (reservas.type_currency == 'mx') {
                                        $currency = 'MXN';
                                    }
                                    if (reservas.type_currency == 'us') {
                                        $currency = 'USD';
                                    }
                                    if (reservas.type_currency == 'pt') {
                                        $currency = 'USD';
                                    }
                                    if (reservas.date_arrival >= star && reservas.date_arrival <= end) {
                                        template += `
                                                    <tr> 
                                                        <td colspan = "17" style="background: #3F80EA; color: #fff;">L L E G A D A</td>
                                                    </tr>
                                                    <tr reserva-id='${reservas.id_reservation}'>
                                                            <td>${reservas.id_reservation}</td>
                                                            <td class="font-weight-bold">${reservas.code_invoice}</td>
                                                            <td>${reservas.name_client}</td>
                                                            <td>${reservas.transfer_destiny}</td>
                                                            <td>${reservas.type_service}</td>
                                                            <td>${$newtype}</td>
                                                            <td class="font-weight-bold">${$newdateArrvial}</td>
                                                            <td class="font-weight-bold">${$newtimeArrival}</td>
                                                            <td>${$newdateExit}</td>
                                                            <td>${$newtimeExit}</td>
                                                            <td>${$total + ' ' + $currency} </td>
                                                            <td>${$newpayment}</td>
                                                            <td>${$newstatus}</td>
                                                            <td><a href='#' id='select_provider' datare='${reservas.id_reservation}'  data='${reservas.provider}' datatag='entrada' datainvoice='${reservas.code_invoice}' dataaction='${reservas.new_typeaction}' dataservice='A' data-toggle='modal' data-target='#providerModal'> ${reservas.provider}</a></td>
                                                            <td><a href='#' id='select_rep' data-toggle='modal'  datarep='${reservas.rep}' datatag='entrada' datare='${reservas.id_reservation}' dataservice='A' datainvoice='${reservas.code_invoice}' dataaction='${reservas.new_trpeactionrep}' data-target='#repModal'> ${reservas.rep}</a></td>
                                                            <td class='text-center p-2'>
                                                                <a href='#' id='reserva-view' title='ver detalles' class=''><i class='fas fa-eye'></i></a>
                                                            </td>
                                                            <td class='text-center p-2'>
                                                                <a href='#' id='reserva-pay' title='Conciliar' class=''><i class='fas fa-dollar-sign'></i></a>
                                                            </td>
                                                    </tr>`;    
                                    }else if (reservas.date_exit >= star && reservas.date_exit <= end) {
                                        template += `
                                                    <tr> 
                                                        <td colspan = "17" style="background: #495057; color: #fff;">S A L I D A </td>
                                                    </tr>
                                                    <tr reserva-id='${reservas.id_reservation}'>
                                                            <td>${reservas.id_reservation}</td>
                                                            <td class="font-weight-bold">${reservas.code_invoice}</td>
                                                            <td>${reservas.name_client}</td>
                                                            <td>${reservas.transfer_destiny}</td>
                                                            <td>${reservas.type_service}</td>
                                                            <td>${$newtype}</td>
                                                            <td>${$newdateArrvial}</td>
                                                            <td>${$newtimeArrival} </td>
                                                            <td class="font-weight-bold">${$newdateExit}</td>
                                                            <td class="font-weight-bold">${$newtimeExit}</td>
                                                            <td>${$total + ' ' + $currency} </td>
                                                            <td>${$newpayment}</td>
                                                            <td>${$newstatus}</td>
                                                            <td><a href='#' id='select_provider' datare='${reservas.id_reservation}'  data='${reservas.provider_salida}' datatag='salida' datainvoice='${reservas.code_invoice}' dataaction='${reservas.new_typeaction}' dataservice='D' data-toggle='modal' data-target='#providerModal'> ${reservas.provider_salida}</a></td>
                                                            <td><a href='#' id='select_rep' data-toggle='modal'  datarep='${reservas.rep_salida}' datatag='salida' datare='${reservas.id_reservation}' dataservice='A' datainvoice='${reservas.code_invoice}' dataaction='${reservas.new_trpeactionrep}' data-target='#repModal'> ${reservas.rep_salida}</a></td>
                                                            <td class='text-center p-2'>
                                                                <a href='#' id='reserva-view' title='ver detalles' class=''><i class='fas fa-eye'></i></a>
                                                            </td>
                                                            <td class='text-center p-2'>
                                                                <a href='#' id='reserva-pay' title='Conciliar' class=''><i class='fas fa-dollar-sign'></i></a>
                                                            </td>
                                                    </tr>`;    
                                        
                                    }
                                });
                                $('#fecha_end').hide();
                                $('#form-date').trigger('reset');
                                $('#radiob').val('0');
                                $('#container').html(template);
                                $('#result-search').show('slow');
                            }
                        }
                    }
                });
            }else if(star){
                $.ajax({
                    url:'../../helpers/servicios.php',
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
                            let reservas = JSON.parse(response);
                            if (reservas == '') {  
                                let template = '';
                                template += `
                                <div class='col-lg-11 pt-2'>
                                    <h6 class="p-2 font-weight-bold"> Búsqueda de la fecha ${star}  </h6>   
                                </div>
                                <div class='col-lg-1 pt-2 text-right'>
                                    <a href="#" class='btn btn-black btn-sm' id="cerrar_results">X</a>
                                </div>  
                                    <div class="col-lg-12 pt-2"> 
                                        <br/>
                                        <p>No se encontro ninguna reservación que coincida.</p>
                                    </div>
                                    ` ;
                                    $('#fecha_end').hide();
                                    $('#form-date').trigger('reset');
                                    $('#container').html(template);
                                    $('#hotel-result').show();
                            }else{
                                let template = '';
                                
                                template +=`
                                <div class='col-lg-11 pt-2'>
                                    <h6 class="p-2 font-weight-bold"> Búsqueda de la fecha ${star}  </h6>   
                                </div>
                                <div class='col-lg-1 pt-2 text-right'>
                                    <a href="#" class='btn btn-black btn-sm' id="cerrar_results">X</a>
                                </div>  `; 
                                template += `
                                        <table class='table table-hover table-bordered table-sm' cellspacing='0' id='tablaAmenidades'>
                                            <thead class='thead-light'>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>ID</th>
                                                    <th>Cliente</th>
                                                    <th>Destino</th>
                                                    <th>Servicio</th>
                                                    <th>Traslado</th>
                                                    <th>Fecha Llegada</th>
                                                    <th>Hora Llegada</th>
                                                    <th>Fecha Salida</th>
                                                    <th>Hora Salida</th>
                                                    <th>Total</th>
                                                    <th>Metodo pago</th>
                                                    <th>Estado</th>
                                                    <th>Proveedor</th>
                                                    <th>REP</th>
                                                    <th></th>
                                                    <th></th>
                                                    </tr>
                                            </thead>
                                        <tbody> `;
                                reservas.forEach(reservas => {       
                                    $newtype = '';
                                    $newstatus = ``;
                                    $newpayment = ``;
                                    $currency = '';
                                    $newdateArrvial = '';
                                    $newtimeArrival = '';
                                    $newdateExit = '';
                                    $newtimeExit = '';
                                    if (reservas.date_arrival == null || reservas.date_arrival == '') { $newdateArrvial= ''; } else{ $newdateArrvial = reservas.date_arrival;}
                                    if (reservas.time_arrival == null || reservas.time_arrival == '') { $newtimeArrival= ''; } else{ $newtimeArrival = reservas.time_arrival + ' Hrs';}
                                    if (reservas.date_exit == null || reservas.date_exit == '') { $newdateExit= ''; }else{ $newdateExit = reservas.date_exit;}
                                    if (reservas.time_exit == null || reservas.time_exit == '') { $newtimeExit= ''; } else{ $newtimeExit = reservas.time_exit + ' Hrs';}
                                    switch (reservas.method_payment) {
                                        case 'oxxo':
                                            $newnamepay = "OXXO";
                                            break;
                                        case 'transfer':
                                            $newnamepay = "TRANSFERENCIA";
                                            break;
                                        case 'airport':
                                            $newnamepay = "AEROPUERTO";
                                            break;
                                        case 'paypal':
                                            $newnamepay = "PAYPAL";
                                            break;
                                        case 'card':
                                            $newnamepay = "TARJETA";
                                            break;
                                        case 'deposit':
                                            $newnamepay = "DEPOSITO";
                                            break;
                                    }
                                    switch (reservas.status_reservation) {
                                        case 'RESERVED':
                                            $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                                <option value='${reservas.status_reservation}'>${reservas.status_reservation}</option>
                                                <option value='COMPLETED'>COMPLETED</option>
                                                <option value='NO SHOW'>NO SHOW</option>
                                                <option value='CANCELLED'>CANCELLED</option>
                                                <option value='REFUNDED'>REFUNDED</option>
                                            </select>`;
                                            break;
                                        case 'COMPLETED':
                                            $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                                <option value='{${reservas.status_reservation}}'>${reservas.status_reservation}</option>
                                                <option value='RESERVED'>RESERVED</option>
                                                <option value='NO SHOW'>NO SHOW</option>
                                                <option value='CANCELLED'>CANCELLED</option>
                                                <option value='REFUNDED'>REFUNDED</option>
                                            </select>`;
                                            break;
                                        case 'NO SHOW':
                                            $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                                <option value='{${reservas.status_reservation}}'>${reservas.status_reservation}</option>
                                                <option value='RESERVED'>RESERVED</option>
                                                <option value='COMPLETED'>COMPLETED</option>
                                                <option value='CANCELLED'>CANCELLED</option>
                                                <option value='REFUNDED'>REFUNDED</option>
                                            </select>`;
                                            break;
                                        case 'CANCELLED':
                                            $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                                <option value='{${reservas.status_reservation}}'>${reservas.status_reservation}</option>
                                                <option value='RESERVED'>RESERVED</option>
                                                <option value='COMPLETED'>COMPLETED</option>
                                                <option value='NO SHOW'>NO SHOW</option>
                                                <option value='REFUNDED'>REFUNDED</option>
                                            </select>`;
                                            break;
                                        case 'REFUNDED':
                                            $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                                <option value='{${reservas.status_reservation}}'>${reservas.status_reservation}</option>
                                                <option value='RESERVED'>RESERVED</option>
                                                <option value='COMPLETED'>COMPLETED</option>
                                                <option value='NO SHOW'>NO SHOW</option>
                                                <option value='CANCELLED'>CANCELLED</option>
                                            </select>`;
                                            break;
                                        default:
                                            $newstatus += `<select class='form-control-sm' name='new_status_reservation' data='${reservas.id_reservation}' id='new_status_reservation'>
                                                <option value='deposit'>Sin asignar</option>
                                                <option value='RESERVED'>RESERVED</option>
                                                <option value='COMPLETED'>COMPLETED</option>
                                                <option value='NO SHOW'>NO SHOW</option>
                                                <option value='CANCELLED'>CANCELLED</option>
                                                <option value='REFUNDED'>REFUNDED</option>
                                            </select>`;
                                            break;
                                    }
                                    switch (reservas.method_payment) {
                                        case 'oxxo':
                                            $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                                <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                                <option value='transfer'>TRANSFERENCIA</option>
                                                <option value='airport'>AEROPUERTO</option>
                                                <option value='paypal'>PAYPAL</option>
                                                <option value='card'>TARJETA</option>
                                                <option value='deposit'>DEPOSITO</option>
                                            </select>`;
                                            break;
                                        case 'transfer':
                                            $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                            <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                                <option value='oxxo'>OXXO</option>
                                                <option value='airport'>AEROPUERTO</option>
                                                <option value='paypal'>PAYPAL</option>
                                                <option value='card'>TARJETA</option>
                                                <option value='deposit'>DEPOSITO</option>
                                            </select>`;
                                            break;
                                        case 'airport':
                                            $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>                                    
                                                <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                                <option value='oxxo'>OXXO</option>
                                                <option value='transfer'>TRANSFERENCIA</option>
                                                <option value='paypal'>PAYPAL</option>
                                                <option value='card'>TARJETA</option>
                                                <option value='deposit'>DEPOSITO</option>
                                            </select>`;
                                            break;
                                        case 'paypal':
                                            $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                            <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                                <option value='oxxo'>OXXO</option>
                                                <option value='transfer'>TRANSFERENCIA</option>
                                                <option value='airport'>AEROPUERTO</option>
                                                <option value='card'>TARJETA</option>
                                                <option value='deposit'>DEPOSITO</option>
                                            </select>`;
                                            break;
                                        case 'card':
                                            $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                            <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                                <option value='oxxo'>OXXO</option>
                                                <option value='transfer'>TRANSFERENCIA</option>
                                                <option value='airport'>AEROPUERTO</option>
                                                <option value='paypal'>PAYPAL</option>
                                                <option value='deposit'>DEPOSITO</option>
                                            </select>`;
                                            break;
                                        case 'deposit':
                                            $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                            <option value='${reservas.method_payment}'>${$newnamepay}</option>
                                                <option value='oxxo'>OXXO</option>
                                                <option value='transfer'>TRANSFERENCIA</option>
                                                <option value='airport'>AEROPUERTO</option>
                                                <option value='paypal'>PAYPAL</option>
                                                <option value='card'>TARJETA</option>
                                            </select>`;
                                            break;
                                        default:
                                            $newpayment += `<select class='form-control-sm' name='new_method_payment' data='${reservas.id_reservation}' id='new_method_payment'>
                                                <option value='deposit'>Sin asignar</option>
                                                <option value='deposit'>DEPOSITO</option>
                                                <option value='oxxo'>OXXO</option>
                                                <option value='transfer'>TRANSFERENCIA</option>
                                                <option value='airport'>AEROPUERTO</option>
                                                <option value='paypal'>PAYPAL</option>
                                                <option value='card'>TARJETA</option>
                                            </select>`;
                                            break;
                                    }
                                    
                                    if (reservas.type_transfer == 'SEN/AH' ) {
                                        $newtype = 'Aeorpuerto - Hotel ';
                                    }
                                    if (reservas.type_transfer == 'SEN/HA' ) {
                                        $newtype = 'Hotel - Aeorpuerto ';
                                    }
                                    if (reservas.type_transfer == 'RED' ) {
                                        $newtype = 'Redondo';
                                    }
                                    if (reservas.type_transfer == 'REDHH' ) {
                                        $newtype = 'Hotel - Hotel ';
                                    }
                                    if (reservas.type_transfer == 'SEN/HH' ) {
                                        $newtype = 'Hotel - Hotel ';
                                    }

                                    $total = reservas.total_cost - reservas.agency_commision;
                                    if (reservas.type_currency == 'RED') {
                                        $total = (reservas.total_cost - reservas.agency_commision) / 2;
                                    }
                                    if (reservas.type_currency == 'mx') {
                                        $currency = 'MXN';
                                    }
                                    if (reservas.type_currency == 'us') {
                                        $currency = 'USD';
                                    }
                                    if (reservas.type_currency == 'pt') {
                                        $currency = 'USD';
                                    }
                                    
                                    if (reservas.date_arrival == star ) {
                                        template += `
                                                    <tr> 
                                                        <td colspan = "17" style="background: #3F80EA; color: #fff;">L L E G A D A</td>
                                                    </tr>
                                                    <tr reserva-id='${reservas.id_reservation}'>
                                                            <td>${reservas.id_reservation}</td>
                                                            <td class="font-weight-bold">${reservas.code_invoice}</td>
                                                            <td>${reservas.name_client}</td>
                                                            <td>${reservas.transfer_destiny}</td>
                                                            <td>${reservas.type_service}</td>
                                                            <td>${$newtype}</td>
                                                            <td class="font-weight-bold">${$newdateArrvial}</td>
                                                            <td class="font-weight-bold">${$newtimeArrival}</td>
                                                            <td>${$newdateExit}</td>
                                                            <td>${$newtimeExit}</td>
                                                            <td>${$total + ' ' + $currency} </td>
                                                            <td>${$newpayment}</td>
                                                            <td>${$newstatus}</td>
                                                            <td><a href='#' id='select_provider' datare='${reservas.id_reservation}'  data='${reservas.provider}' datatag='entrada' datainvoice='${reservas.code_invoice}' dataaction='${reservas.new_typeaction}' dataservice='A' data-toggle='modal' data-target='#providerModal'> ${reservas.provider}</a></td>
                                                            <td><a href='#' id='select_rep' data-toggle='modal'  datarep='${reservas.rep}' datatag='entrada' datare='${reservas.id_reservation}' dataservice='A' datainvoice='${reservas.code_invoice}' dataaction='${reservas.new_trpeactionrep}' data-target='#repModal'> ${reservas.rep}</a></td>
                                                            <td class='text-center p-2'>
                                                                <a href='#' id='reserva-view' title='ver detalles' class=''><i class='fas fa-eye'></i></a>
                                                            </td>
                                                            <td class='text-center p-2'>
                                                                <a href='#' id='reserva-pay' title='Conciliar' class=''><i class='fas fa-dollar-sign'></i></a>
                                                            </td>
                                                    </tr>`;    
                                    }else if (reservas.date_exit == star ) {
                                        template += `
                                                    <tr> 
                                                        <td colspan = "17" style="background: #495057; color: #fff;">S A L I D A </td>
                                                    </tr>
                                                    <tr reserva-id='${reservas.id_reservation}'>
                                                            <td>${reservas.id_reservation}</td>
                                                            <td class="font-weight-bold">${reservas.code_invoice}</td>
                                                            <td>${reservas.name_client}</td>
                                                            <td>${reservas.transfer_destiny}</td>
                                                            <td>${reservas.type_service}</td>
                                                            <td>${$newtype}</td>
                                                            <td>${$newdateArrvial}</td>
                                                            <td>${$newtimeArrival} </td>
                                                            <td class="font-weight-bold">${$newdateExit}</td>
                                                            <td class="font-weight-bold">${$newtimeExit}</td>
                                                            <td>${$total + ' ' + $currency} </td>
                                                            <td>${$newpayment}</td>
                                                            <td>${$newstatus}</td>
                                                            <td><a href='#' id='select_provider' datare='${reservas.id_reservation}'  data='${reservas.provider_salida}' datatag='salida' datainvoice='${reservas.code_invoice}' dataaction='${reservas.new_typeaction}' dataservice='D' data-toggle='modal' data-target='#providerModal'> ${reservas.provider_salida}</a></td>
                                                            <td><a href='#' id='select_rep' data-toggle='modal'  datarep='${reservas.rep_salida}' datatag='salida' datare='${reservas.id_reservation}' dataservice='D' datainvoice='${reservas.code_invoice}' dataaction='${reservas.new_trpeactionrep}' data-target='#repModal'> ${reservas.rep_salida}</a></td>
                                                            <td class='text-center p-2'>
                                                                <a href='#' id='reserva-view' title='ver detalles' class=''><i class='fas fa-eye'></i></a>
                                                            </td>
                                                            <td class='text-center p-2'>
                                                                <a href='#' id='reserva-pay' title='Conciliar' class=''><i class='fas fa-dollar-sign'></i></a>
                                                            </td>
                                                    </tr>`;    
                                        
                                    }  
                                });
                                $('#fecha_end').hide();
                                $('#form-date').trigger('reset');
                                $('#container').html(template);
                                $('#result-search').show('slow');
                            }
                        }
                    }
                });
            }
        }else{
            $('#result-search').hide('slow');
            document.getElementById("resultSearch").className = "col-lg-12";
        }
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
        var payment = {
            'id': id,
            'value': stts,
            'text': text,
            'setstatusmet': 1
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

    // Navs 
    $('#entry-tab').click(function(){
        let reserved = "LLEGADA";
        loadReservations(reserved);
    });

    $('#exit-tab').click(function(){
        let reserved = "SALIDA";
        loadReservations(reserved);
    });
    // End Navs

    // Listar hoteles
    function loadReservations(lets){
        function loadData(page){
            $.ajax({
                url: "../../model/servicios_paginacion.php",
                type: "POST",
                cache: false,
                data: {page_no:page, navs: lets},
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
                    </div>
                    <div class="col-lg-4 col-md-3">
                    </div>  
                        
                    `;
                    if (lets = '') {
                        $("#table-data-re").html(template);
                        
                    }
                    if (lets = 'LLEGADA') {
                        $("#table-data-re").html(template);
                        
                    }
                    if (lets = 'SALIDA') {
                        $("#table-data-co").html(template);
                        
                    }
                },
                success: function(response){
                    if (lets = '') {
                        $("#table-data-re").html(response);
                        
                    }
                    if (lets = 'LLEGADA') {
                        $("#table-data-re").html(response);
                        
                    }
                    if (lets = 'SALIDA') {
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
                    loadReservations(tag);
                    loadMessagesReservation(id_reservation);
                    $('#result-search').hide('slow');
                    $('#entry-tab').removeClass('active');
                    $('#exit-tab').addClass('active');
                    $('#text-msg').val(json.message);
                    
                }else if (type_tag == 'entrada') {
                    
                    tag = 'LLEGADA'
                    $('.alert-msg').show();
                    loadReservations(tag);
                    loadMessagesReservation(id_reservation);
                    $('#result-search').hide('slow');
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
                    loadReservations(tag);
                    $('#result-search').hide('slow');
                    $('#entry-tab').removeClass('active');
                    $('#exit-tab').addClass('active');
                    $('#text-msg').val(json.message);
                    
                }else if (type_tag == 'entrada') {
                    
                    tag = 'LLEGADA'
                    $('.alert-msg').show();
                    loadReservations(tag);
                    $('#result-search').hide('slow');
                    $('#exit-tab').removeClass('active');
                    $('#entry-tab').addClass('active');
                    $('#text-msg').val(json.message);
                } 
            }
        });
        e.preventDefault();
    });


    /* APARTADO DE DETALLES DE RESERVACION / SERVICIO */


    //Enviar mensaje 
    $(document).on('click', '#btn_send_msj', function(){
        let id_reservation = id;
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


});