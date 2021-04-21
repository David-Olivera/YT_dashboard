$(function(){
    $('#sidebar, #content').toggleClass('active');
    let edit = false;
    $('#result-search').hide();
    $('#fecha_end').hide();
    loadReservations();

    /* Buscar reserva */
    $('#form-search').submit(function(e){
        if($('#search').val()) {
            let data = $('#search').val();
            let search = 'search';
            const postData = {
                'data': data,
                'search': search,
            }
            $.ajax({
                url:'../../helpers/reservaciones.php',
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
                                <div class="col-lg-11 pt-3">
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
                                                <th>Cliente</th>
                                                <th>Servicio</th>
                                                <th>Traslado</th>
                                                <th>Adultos</th>
                                                <th>Niños</th>
                                                <th>Total pagado</th>
                                                <th>Metodo de pago</th>
                                                <th>Estado</th>
                                                <th>Fecha <br/>Reservación</th>
                                                <th>Agencia</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                </tr>
                                        </thead>
                                    <tbody> `;
                            reservas.forEach(reservas => {       
                                $newtype = '';
                                $newstatus = ``;
                                $newpayment = ``;
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
                                }
                                template += `<tr reserva-id='${reservas.id_reservation}'>
                                                    <td class="font-weight-bold">${reservas.code}</td>
                                                    <td class="font-weight-bold">${reservas.name_client}</td>
                                                    <td>${reservas.type_service}</td>
                                                    <td>${$newtype}</td>
                                                    <td>${reservas.number_adults}</td>
                                                    <td>${reservas.number_children}</td>
                                                    <td>${reservas.total_cost}</td>
                                                    <td>${$newpayment}</td>
                                                    <td>${$newstatus}</td>
                                                    <td>${reservas.date_register_reservation}</td>
                                                    <td class="font-weight-bold">${reservas.name_agency}</td>
                                                    <td class='text-center p-2'>
                                                        <a href='#' id='amenity-delete' title='ver detalles' class='amenity- '><i class='fas fa-eye'></i></a>
                                                    </td>
                                                    <td class='text-center p-2'>
                                                        <a href='#' id='amenity-edit' title='Editar' class=' amenity-edit' ><i class='fas fa-edit' ></i></a>
                                                    </td>
                                                    <td class='text-center p-2'>
                                                        <a href='#' id='amenity-delete' title='Conciliar' class=' amenity-delete '><i class='fas fa-dollar-sign'></i></a>
                                                    </td>
                                            </tr>`;    
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
    /* Buscar reserva por agencia */
    $('#form-search-agency').submit(function(e){
        if($('#name_agency').val()) {
            let data = $('#name_agency').val();
            let search = 'search';
            const postData = {
                'data': data,
                'search': search,
            }
            $.ajax({
                url:'../../helpers/reservaciones.php',
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
                                <div class="col-lg-11 pt-3">
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
                                        <h6 class="p-2 font-weight-bold"> Búsqueda por la agencia ${data} </h6>
                                    </div>
                                    <div class='col-lg-1 pt-2 text-right'>
                                        <a href="#" class='btn btn-black btn-sm' id='cerrar_results'>X</a>
                                    </div>
                                    <table class='table table-hover table-bordered table-sm' cellspacing='0' id='tablaAmenidades'>
                                        <thead class='thead-light'>
                                            <tr>
                                                <th>ID</th>
                                                <th>Cliente</th>
                                                <th>Servicio</th>
                                                <th>Traslado</th>
                                                <th>Adultos</th>
                                                <th>Niños</th>
                                                <th>Total pagado</th>
                                                <th>Metodo de pago</th>
                                                <th>Estado</th>
                                                <th>Fecha <br/>Reservación</th>
                                                <th>Agencia</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                </tr>
                                        </thead>
                                    <tbody> `;
                            reservas.forEach(reservas => {       
                                $newtype = '';
                                
                                $newstatus = ``;
                                $newpayment = ``;
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
                                template += `<tr reserva-id='${reservas.id_reservation}'>
                                                    <td class="font-weight-bold">${reservas.code}</td>
                                                    <td class="font-weight-bold">${reservas.name_client}</td>
                                                    <td>${reservas.type_service}</td>
                                                    <td>${$newtype}</td>
                                                    <td>${reservas.number_adults}</td>
                                                    <td>${reservas.number_children}</td>
                                                    <td>${reservas.total_cost}</td>
                                                    <td>${$newpayment}</td>
                                                    <td>${$newstatus}</td>
                                                    <td>${reservas.date_register_reservation}</td>
                                                    <td class="font-weight-bold">${reservas.name_agency}</td>
                                                    <td class='text-center p-2'>
                                                        <a href='#' id='amenity-delete' title='ver detalles' class='amenity-  '><i class='fas fa-eye'></i></a>
                                                    </td>
                                                    <td class='text-center p-2'>
                                                        <a href='#' id='amenity-edit' title='Editar' class=' amenity-edit ' ><i class='fas fa-edit' ></i></a>
                                                    </td>
                                                    <td class='text-center p-2'>
                                                        <a href='#' id='amenity-delete' title='Conciliar' class=' amenity-delete '><i class='fas fa-dollar-sign'></i></a>
                                                    </td>
                                            </tr>`;    
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
        if (star) {
            let end = $('#datepicker_end').val();
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
                    url:'../../helpers/reservaciones.php',
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
                                    <div class="col-lg-11 pt-3">
                                        <h6 class="p-2 font-weight-bold"> Búsqueda de las fechas ${star} al ${end} </h6>
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
                                template +=`
                                
                                <div class='col-lg-11 pt-2'>
                                <h6 class="p-2 font-weight-bold"> Búsqueda de las fechas ${star} al ${end} </h6>
                                </div>
                                <div class='col-lg-1 pt-2 text-right'>
                                    <a href="#" class='btn btn-black btn-sm' id='cerrar_results'>X</a>
                                </div>`;
                                template += `
                                        <table class='table table-hover table-bordered table-sm' cellspacing='0' id='tablaAmenidades'>
                                            <thead class='thead-light'>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Cliente</th>
                                                    <th>Servicio</th>
                                                    <th>Traslado</th>
                                                    <th>Adultos</th>
                                                    <th>Niños</th>
                                                    <th>Total pagado</th>
                                                    <th>Metodo de pago</th>
                                                    <th>Estado</th>
                                                    <th>Fecha <br/>Reservación</th>
                                                    <th>Agencia</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    </tr>
                                            </thead>
                                        <tbody> `;
                                reservas.forEach(reservas => {       
                                    $newtype = '';
                                    $newstatus = ``;
                                    $newpayment = ``;
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
                                    template += `<tr reserva-id='${reservas.id_reservation}'>
                                                        <td class="font-weight-bold">${reservas.code}</td>
                                                        <td>${reservas.name_client}</td>
                                                        <td>${reservas.type_service}</td>
                                                        <td>${$newtype}</td>
                                                        <td>${reservas.number_adults}</td>
                                                        <td>${reservas.number_children}</td>
                                                        <td>${reservas.total_cost}</td>
                                                        <td>${$newpayment}</td>
                                                        <td>${$newstatus}</td>
                                                        <td class="font-weight-bold">${reservas.date_register_reservation}</td>
                                                        <td>${reservas.name_agency}</td>
                                                        <td class='text-center p-2'>
                                                            <a href='#' id='reserva-view' title='ver detalles' class=''><i class='fas fa-eye'></i></a>
                                                        </td>
                                                        <td class='text-center p-2'>
                                                            <a href='#' id='reserva-edit' title='Editar' class='' ><i class='fas fa-edit' ></i></a>
                                                        </td>
                                                        <td class='text-center p-2'>
                                                            <a href='#' id='reserva-pay' title='Conciliar' class=''><i class='fas fa-dollar-sign'></i></a>
                                                        </td>
                                                </tr>`;    
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
                    url:'../../helpers/reservaciones.php',
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
                                    <div class="col-lg-11 pt-3">
                                        <h6 class="p-2 font-weight-bold"> Búsqueda de la fecha ${star}  </h6>
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
                                
                                template +=`
                                <div class='col-lg-11 pt-2'>
                                <h6 class="p-2 font-weight-bold"> Búsqueda de la fecha ${star}  </h6>
                                </div>
                                <div class='col-lg-1 pt-2 text-right'>
                                    <a href="#" class='btn btn-black btn-sm' id='cerrar_results'>X</a>
                                </div>`; 
                                template += `
                                        <table class='table table-hover table-bordered table-sm' cellspacing='0' id='tablaAmenidades'>
                                            <thead class='thead-light'>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Cliente</th>
                                                    <th>Servicio</th>
                                                    <th>Traslado</th>
                                                    <th>Adultos</th>
                                                    <th>Niños</th>
                                                    <th>Total pagado</th>
                                                    <th>Metodo de pago</th>
                                                    <th>Estado</th>
                                                    <th>Fecha <br/>Reservación</th>
                                                    <th>Agencia</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    </tr>
                                            </thead>
                                        <tbody> `;
                                reservas.forEach(reservas => {       
                                    $newtype = '';
                                    $newstatus = ``;
                                    $newpayment = ``;
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
                                    
                                    template += `<tr reserva-id='${reservas.id_reservation}'>
                                                        <td class="font-weight-bold">${reservas.code}</td>
                                                        <td>${reservas.name_client}</td>
                                                        <td>${reservas.type_service}</td>
                                                        <td>${$newtype}</td>
                                                        <td>${reservas.number_adults}</td>
                                                        <td>${reservas.number_children}</td>
                                                        <td>${reservas.total_cost}</td>
                                                        <td>${$newpayment}</td>
                                                        <td>${$newstatus}</td>
                                                        <td class="font-weight-bold">${reservas.date_register_reservation}</td>
                                                        <td>${reservas.name_agency}</td>
                                                        <td class='text-center p-2'>
                                                            <a href='#' id='amenity-delete' title='ver detalles' class='amenity- '><i class='fas fa-eye'></i></a>
                                                        </td>
                                                        <td class='text-center p-2'>
                                                            <a href='#' id='amenity-edit' title='Editar' class=' amenity-edit ' ><i class='fas fa-edit' ></i></a>
                                                        </td>
                                                        <td class='text-center p-2'>
                                                            <a href='#' id='amenity-delete' title='Conciliar' class=' amenity-delete '><i class='fas fa-dollar-sign'></i></a>
                                                        </td>
                                                </tr>`;    
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
                loadReservations();

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
                loadReservations();
            }

        });
    });
    /* Navs */
    $('#reserved-tab').click(function(){
        let reserved = "RESERVED";
        loadReservations(reserved);
    });
    $('#completed-tab').click(function(){
        let completed = "COMPLETED";
        loadReservations(completed);
    });
    $('#noshow-tab').click(function(){
        let noshow = "NO SHOW";
        loadReservations(noshow);
    });
    $('#cancelled-tab').click(function(){
        let cancelled = "CANCELLED";
        loadReservations(cancelled);
    });
    $('#refunded-tab').click(function(){
        let refunded = "REFUNDED";
        loadReservations(refunded);
    });
    /* End Navs */

    // Listar hoteles
    function loadReservations(lets){
        function loadData(page){
            $.ajax({
                url: "../../model/reservaciones_paginacion.php",
                type: "POST",
                cache: false,
                data: {page_no:page, navs: lets},
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
                    if (lets = '') {
                        $("#table-data-re").html(template);
                        
                    }
                    if (lets = 'RESERVED') {
                        $("#table-data-re").html(template);
                        
                    }
                    if (lets = 'COMPLETED') {
                        $("#table-data-co").html(template);
                        
                    }
                    if (lets = 'NO SHOW') {
                        $("#table-data-no").html(template);
                        
                    }
                    if (lets = 'CANCELLED') {
                        $("#table-data-ca").html(template);
                        
                    }
                    if (lets = 'REFUNDED') {
                        $("#table-data-ref").html(template);
                        
                    }
                },
                success: function(response){
                    if (lets = '') {
                        $("#table-data-re").html(response);
                        
                    }
                    if (lets = 'RESERVED') {
                        $("#table-data-re").html(response);
                        
                    }
                    if (lets = 'COMPLETED') {
                        $("#table-data-co").html(response);
                        
                    }
                    if (lets = 'NO SHOW') {
                        $("#table-data-no").html(response);
                        
                    }
                    if (lets = 'CANCELLED') {
                        $("#table-data-ca").html(response);
                        
                    }
                    if (lets = 'REFUNDED') {
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


});