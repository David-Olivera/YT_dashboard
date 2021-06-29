$(function(){
    
    let edit = false;
    $('#sidebar, #content').toggleClass('active');
    $('#result-search').hide();
    $(".alert-msg-ag").hide();
    $(".alert-msg-tu").hide();
    $('#result-search-ag').hide();
    $('#result-search-tu').hide();
    let nav = 'PUBLIC';
    loadZones(nav);



    // Btn cancelar
    $('#cancelButton').on("click", function(e){
        e.preventDefault();
        $('#exampleModal').modal('hide');
        $('#zoneForm').trigger('reset');
        $("#table-data").show('slow');
    });
    // Btn cancelar edit
    $('#cancelButtonEdit').on("click", function(e){
        e.preventDefault();
        $('#exampleModalEdit').modal('hide');
        $('#zoneFormEdit').trigger('reset');
    });

    /* Buscardor Publicas */
    $('#search').keyup(function(){
        if($('#search').val()) {
            let data = $('#search').val();
            let search = 'search';
            if (/'/.test(data)) {
                alert('Esta ingresando caracteres no permitidos');
                $('#search').val('');
                $('#result-search').hide('slow');
                $("#table-data-pu").show('slow');
                $('#search').focus();
                return false;
            }
            const postData = {
                'data': data,
                'search': search,
            }
            $.ajax({
                url:'../../helpers/tarifas.php',
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
                    $("#table-data-pu").hide('slow');
                    $('#result-search').show('slow');
                },
                success: function(response){
                    if (!response.error) {
                        let zones = JSON.parse(response);
                        if (zones == '') {
                            let template = '';
                            template += `
                            <div class=' p-2  col-lg-12 text-right'>
                                <a href="#" class='btn btn-black btn-sm' id='cerrar_results'>X</a>
                            </div>
                                <div class="col-lg-12">
                                    <p>No se encontro ninguna zona que coincida.</p>
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
                                <table class='table table-hover  table-bordered table-sm' cellspacing='0' id='tablaUsuarios'>
                                    <thead class='thead-dark'>
                                        <tr class='text-center'>
                                            <th>Zona</th>
                                            <th>Privado (OW)</th>
                                            <th>Privado (RT)</th>
                                            <th>Compartido  <br/> Minibus (OW)</th>
                                            <th>Compartido  <br/> Minibus (RT)</th>
                                            <th>Compartido  <br/> Premium (OW)</th>
                                            <th>Compartido  <br/> Premium (RT)</th>
                                            <th>Lujo (OW)</th>
                                            <th>Lujo (RT)</th>
                                            <th></th>
                                            <th></th>
                                            </tr>
                                    </thead>
                                    <tbody>
                            `;
                            zones.forEach(zones => {
                                    template += `
                                        <tr class='text-center ' zone-id='${zones.id_zone}'>
                                                <td class='align-middle'>${zones.name_zone}</td>
                                                <td class='align-middle'><small><strong>1-4</strong> </small>$ ${zones.privado_ow_1} <br/> <small><strong>1-6</strong> </small>$ ${zones.privado_ow_2} <br/> <small><strong>1-8</strong> </small>$ ${zones.privado_ow_3} <br/> <small><strong>1-10 </strong></small>$ ${zones.privado_ow_4} <br/> <small><strong>1-11 </strong></small>$ ${zones.privado_ow_5} <br/> <small><strong>1-16 </strong></small>$ ${zones.privado_ow_6}</td>
                                                <td class='align-middle'><small><strong>1-4</strong> </small>$ ${zones.privado_rt_1} <br/> <small><strong>1-6</strong> </small>$ ${zones.privado_rt_2} <br/> <small><strong>1-8</strong> </small>$ ${zones.privado_rt_3} <br/> <small><strong>1-10</strong> </small>$ ${zones.privado_rt_4} <br/> <small><strong>1-11</strong> </small>$ ${zones.privado_rt_5} <br/> <small><strong>1-16</strong> </small>$ ${zones.privado_rt_6}</td>
                                                <td class='align-middle'>$ ${zones.compartido_ow}</td>
                                                <td class='align-middle'>$ ${zones.compartido_rt}</td>
                                                <td class='align-middle'>$ ${zones.compartido_ow_premium}</td>
                                                <td class='align-middle'>$ ${zones.compartido_rt_premium}</td>
                                                <td class='align-middle'><small><strong>1-6</strong> </small>$ ${zones.lujo_ow_1} </td>
                                                <td class='align-middle'><small><strong>1-6</strong> </small>$ ${zones.lujo_rt_1} </td>
                                                <td class='align-middle'>
                                                <a href="#" class="zone-item-pu" ><i class="fas fa-edit" ></i></a></li>
                                                </td>
                                                <td class='align-middle'>
                                                    <a href="#" class="zone-delete-search "><i class="fas fa-trash-alt"></i></a>
                                                </td>
                                        </tr>
                                        ` ;   
                            });
                            $('#container').html(template);
                            $("#table-data-pu").hide('slow');
                            $("#result-search").show('slow');
                        }
                    }
                }
            });
        }else{  
            $('#result-search').hide('slow');
            $("#table-data-pu").show('slow');
            $('#zoneForm').trigger('reset');
            $("#formButton").show();
        }
    });

    /* Buscardor Agencias */
    $('#search-ag').keyup(function(){
        if($('#search-ag').val()) {
            let data = $('#search-ag').val();
            let search = 'search_ag';
            if (/'/.test(data)) {
                alert('Esta ingresando caracteres no permitidos');
                $('#search-ag').val('');
                $('#result-search-ag').hide('slow');
                $("#table-data-ag").show('slow');
                $('#search-ag').focus();
                return false;
            }
            const postData = {
                'data': data,
                'search': search,
            }
            $.ajax({
                url:'../../helpers/tarifas.php',
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
                    
                    $('#container-ag').html(template);
                    $("#table-data-ag").hide('slow');
                    $('#result-search-ag').show('slow');
                },
                success: function(response){
                    if (!response.error) {
                        let zones = JSON.parse(response);
                        if (zones == '') {
                            let template = '';
                            template += `
                            <div class=' p-2  col-lg-12 text-right'>
                                <a href="#" class='btn btn-black btn-sm' id='cerrar_results'>X</a>
                            </div>
                                <div class="col-lg-12">
                                    <p>No se encontro ninguna zona que coincida.</p>
                                </div>
                                ` ;
                                $('#container-ag').html(template);
                                $('#result-search-ag').show();  
                        }else{
                            let template = '';
                            template += `
                            
                             <div class=' p-2  col-lg-12 text-right'>
                                    <a href="#" class='btn btn-black btn-sm' id='cerrar_results'>X</a>
                                </div>
                                <table class='table table-hover  table-bordered table-sm' cellspacing='0' id='tablaUsuarios'>
                                    <thead class='thead-dark'>
                                        <tr class='text-center'>
                                            <th>Zona</th>
                                            <th>Privado (OW)</th>
                                            <th>Privado (RT)</th>
                                            <th>Compartido  <br/> Minibus (OW)</th>
                                            <th>Compartido  <br/> Minibus (RT)</th>
                                            <th>Compartido  <br/> Premium (OW)</th>
                                            <th>Compartido  <br/> Premium (RT)</th>
                                            <th>Lujo (OW)</th>
                                            <th>Lujo (RT)</th>
                                            <th></th>
                                            <th></th>
                                            </tr>
                                    </thead>
                                    <tbody>
                            `;
                            zones.forEach(zones => {
                                    template += `
                                        <tr class='text-center ' zone-id='${zones.id_zone}'>
                                                <td class='align-middle'>${zones.name_zone}</td>
                                                <td class='align-middle'><small><strong>1-4</strong> </small>$ ${zones.privado_ow_1} <br/> <small><strong>1-6</strong> </small>$ ${zones.privado_ow_2} <br/> <small><strong>1-8</strong> </small>$ ${zones.privado_ow_3} <br/> <small><strong>1-10 </strong></small>$ ${zones.privado_ow_4} <br/> <small><strong>1-11 </strong></small>$ ${zones.privado_ow_5} <br/> <small><strong>1-16 </strong></small>$ ${zones.privado_ow_6}</td>
                                                <td class='align-middle'><small><strong>1-4</strong> </small>$ ${zones.privado_rt_1} <br/> <small><strong>1-6</strong> </small>$ ${zones.privado_rt_2} <br/> <small><strong>1-8</strong> </small>$ ${zones.privado_rt_3} <br/> <small><strong>1-10</strong> </small>$ ${zones.privado_rt_4} <br/> <small><strong>1-11</strong> </small>$ ${zones.privado_rt_5} <br/> <small><strong>1-16</strong> </small>$ ${zones.privado_rt_6}</td>
                                                <td class='align-middle'>$ ${zones.compartido_ow}</td>
                                                <td class='align-middle'>$ ${zones.compartido_rt}</td>
                                                <td class='align-middle'>$ ${zones.compartido_ow_premium}</td>
                                                <td class='align-middle'>$ ${zones.compartido_rt_premium}</td>
                                                <td class='align-middle'><small><strong>1-6</strong> </small>$ ${zones.lujo_ow_1} </td>
                                                <td class='align-middle'><small><strong>1-6</strong> </small>$ ${zones.lujo_rt_1} </td>
                                                <td class='align-middle'>
                                                <a href="#" class="zone-item-ag" ><i class="fas fa-edit" ></i></a></li>
                                                </td>
                                                <td class='align-middle'>
                                                    <a href="#" class="zone-delete-search "><i class="fas fa-trash-alt"></i></a>
                                                </td>
                                        </tr>
                                        ` ;   
                            });
                            $('#container-ag').html(template);
                            $("#table-data-ag").hide('slow');
                            $("#result-search-ag").show('slow');
                        }
                    }
                }
            });
        }else{  
            $('#result-search-ag').hide('slow');
            $("#table-data-ag").show('slow');
            $('#zoneForm').trigger('reset');
            $("#formButton").show();
        }
    });

    /* Buscardor Tureando */
    $('#search-tu').keyup(function(){
        if($('#search-tu').val()) {
            let data = $('#search-tu').val();
            let search = 'search_tu';
            if (/'/.test(data)) {
                alert('Esta ingresando caracteres no permitidos');
                $('#search-tu').val('');
                $('#result-search-tu').hide('slow');
                $("#table-data-tu").show('slow');
                $('#search-tu').focus();
                return false;
            }
            const postData = {
                'data': data,
                'search': search,
            }
            $.ajax({
                url:'../../helpers/tarifas.php',
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
                    
                    $('#container-tu').html(template);
                    $("#table-data-tu").hide('slow');
                    $('#result-search-tu').show('slow');
                },
                success: function(response){
                    if (!response.error) {
                        let zones = JSON.parse(response);
                        if (zones == '') {
                            let template = '';
                            template += `
                            <div class=' p-2  col-lg-12 text-right'>
                                <a href="#" class='btn btn-black btn-sm' id='cerrar_results'>X</a>
                            </div>
                                <div class="col-lg-12">
                                    <p>No se encontro ninguna zona que coincida.</p>
                                </div>
                                ` ;
                                $('#container-tu').html(template);
                                $('#result-search-tu').show();  
                        }else{
                            let template = '';
                            template += `
                            
                             <div class=' p-2  col-lg-12 text-right'>
                                    <a href="#" class='btn btn-black btn-sm' id='cerrar_results'>X</a>
                                </div>
                                <table class='table table-hover  table-bordered table-sm' cellspacing='0' id='tablaUsuarios'>
                                    <thead class='thead-dark'>
                                        <tr class='text-center'>
                                            <th>Zona</th>
                                            <th>Privado (OW)</th>
                                            <th>Privado (RT)</th>
                                            <th>Compartido  <br/> Minibus (OW)</th>
                                            <th>Compartido  <br/> Minibus (RT)</th>
                                            <th>Compartido  <br/> Premium (OW)</th>
                                            <th>Compartido  <br/> Premium (RT)</th>
                                            <th>Lujo (OW)</th>
                                            <th>Lujo (RT)</th>
                                            <th></th>
                                            <th></th>
                                            </tr>
                                    </thead>
                                    <tbody>
                            `;
                            zones.forEach(zones => {
                                    template += `
                                        <tr class='text-center ' zone-id='${zones.id_zone}'>
                                                <td class='align-middle'>${zones.name_zone}</td>
                                                <td class='align-middle'><small><strong>1-4</strong> </small>$ ${zones.privado_ow_1} <br/> <small><strong>1-6</strong> </small>$ ${zones.privado_ow_2} <br/> <small><strong>1-8</strong> </small>$ ${zones.privado_ow_3} <br/> <small><strong>1-10 </strong></small>$ ${zones.privado_ow_4} <br/> <small><strong>1-11 </strong></small>$ ${zones.privado_ow_5} <br/> <small><strong>1-16 </strong></small>$ ${zones.privado_ow_6}</td>
                                                <td class='align-middle'><small><strong>1-4</strong> </small>$ ${zones.privado_rt_1} <br/> <small><strong>1-6</strong> </small>$ ${zones.privado_rt_2} <br/> <small><strong>1-8</strong> </small>$ ${zones.privado_rt_3} <br/> <small><strong>1-10</strong> </small>$ ${zones.privado_rt_4} <br/> <small><strong>1-11</strong> </small>$ ${zones.privado_rt_5} <br/> <small><strong>1-16</strong> </small>$ ${zones.privado_rt_6}</td>
                                                <td class='align-middle'>$ ${zones.compartido_ow}</td>
                                                <td class='align-middle'>$ ${zones.compartido_rt}</td>
                                                <td class='align-middle'>$ ${zones.compartido_ow_premium}</td>
                                                <td class='align-middle'>$ ${zones.compartido_rt_premium}</td>
                                                <td class='align-middle'><small><strong>1-6</strong> </small>$ ${zones.lujo_ow_1} </td>
                                                <td class='align-middle'><small><strong>1-6</strong> </small>$ ${zones.lujo_rt_1} </td>
                                                <td class='align-middle'>
                                                <a href="#" class="zone-item-tu" ><i class="fas fa-edit" ></i></a></li>
                                                </td>
                                                <td class='align-middle'>
                                                    <a href="#" class="zone-delete-search "><i class="fas fa-trash-alt"></i></a>
                                                </td>
                                        </tr>
                                        ` ;   
                            });
                            $('#container-tu').html(template);
                            $("#table-data-tu").hide('slow');
                            $("#result-search-tu").show('slow');
                        }
                    }
                }
            });
        }else{  
            $('#result-search-tu').hide('slow');
            $("#table-data-tu").show('slow');
            $('#zoneForm').trigger('reset');
            $("#formButton").show();
            document.getElementById("resultSearch").className = "col-lg-12";
        }
    });

    //Select type rates
    $('#public-tab').click(function(){
        let obj = "PUBLIC";
        loadZones(obj);
    });
    $('#agencie-tab').click(function(){
        let obj = "AGENCIES";
        loadZones(obj);
    });
    $('#tureando-tab').click(function(){
        let obj = "TUREANDO";
        loadZones(obj);
    });


    //Add zone
    $('#zone-form').submit(function(e){
        var postDatas = {
            'name_zone': $('#nombre_zona').val(),
            'privado_ow_1': $('#privado_ow_1').val(),
            'privado_ow_2': $('#privado_ow_2').val(),
            'privado_ow_3': $('#privado_ow_3').val(),
            'privado_ow_4': $('#privado_ow_4').val(),
            'privado_ow_5': $('#privado_ow_5').val(),
            'privado_ow_6': $('#privado_ow_6').val(),
            'privado_rt_1': $('#privado_rt_1').val(),
            'privado_rt_2': $('#privado_rt_2').val(),
            'privado_rt_3': $('#privado_rt_3').val(),
            'privado_rt_4': $('#privado_rt_4').val(),
            'privado_rt_5': $('#privado_rt_5').val(),
            'privado_rt_6': $('#privado_rt_6').val(),
            'compartido_ow': $('#compartido_ow').val(),
            'compartido_rt': $('#compartido_rt').val(),
            'compartido_ow_premium': $('#compartido_ow_premium').val(),
            'compartido_rt_premium': $('#compartido_rt_premium').val(),
            'lujo_ow_1': $('#lujo_ow_1').val(),
            // 'lujo_ow_2': $('#lujo_ow_2').val(),
            'lujo_rt_1': $('#lujo_rt_1').val(),
            // 'lujo_rt_2': $('#lujo_rt_2').val(),
            'edit': 'false',
        };
        if (postDatas.name_zone == null || postDatas.name_zone.length == 0) {
            alert('El nombre de la Zona es un campo obligatorio.');
            $('#nombre_zona').focus();
            return false;
        }
        if (/^\s+$/.test(postDatas.name_zone)) {
            alert('El nombre de la Zona tiene caracteres especiales no aceptados.');
            $('#nombre_zona').focus();
            return false;
        }
        $.ajax({
            data: postDatas,
            url: '../../helpers/tarifas.php',
            type: 'post',
            success: function(data){
                var json = $.parseJSON(data);
            $('#zoneForm').trigger('reset');
            $('#exampleModal').modal('hide');
            $('.alert-msg').show();
            $('#text-msg').val(json.message);
                if (json.code == 1) {
                    nav = 'PUBLIC'
                    loadZones(nav);
                }
            }
        });
        e.preventDefault();
    });


    /*Selecciona el tipo de tarifa que quiere editar P, A o T */
    $(document).on('click', '.zone-edit-pu', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('zone-id');
        let type_e = 'rate_public';
        $('#rate_type').val(type_e);
        $("#nombre_zona_edit").prop('disabled', false);
        getDatasEdit(id, type_e);
    });
    $(document).on('click', '.zone-edit-ag', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('zone-id');
        let type_e = 'rate_agencie';
        $('#rate_type').val(type_e);
        $("#nombre_zona_edit").prop('disabled', true);
        getDatasEdit(id, type_e);
    });
    $(document).on('click', '.zone-edit-tu', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('zone-id');
        let type_e = 'rate_tureando';
        $('#rate_type').val(type_e);
        $("#nombre_zona_edit").prop('disabled', true);
        getDatasEdit(id, type_e);
    });

    // Traer valores Edit zones
    function getDatasEdit(id, type_e){
        const postData = {
            'id': id,
            'single': type_e,
        }
        $.post('../../helpers/tarifas.php', postData, function(response){
            $('#exampleModalEdit').modal('show');
            const zone = JSON.parse(response);
            $('#zone-id-edit').val(zone.id_zone);
            $('#nombre_zona_edit').val(zone.name_zone);
            $('#privado_ow_1_edit').val(zone.privado_ow_1);
            $('#privado_ow_2_edit').val(zone.privado_ow_2);
            $('#privado_ow_3_edit').val(zone.privado_ow_3);
            $('#privado_ow_4_edit').val(zone.privado_ow_4);
            $('#privado_ow_5_edit').val(zone.privado_ow_5);
            $('#privado_ow_6_edit').val(zone.privado_ow_6);
            $('#privado_rt_1_edit').val(zone.privado_rt_1);
            $('#privado_rt_2_edit').val(zone.privado_rt_2);
            $('#privado_rt_3_edit').val(zone.privado_rt_3);
            $('#privado_rt_4_edit').val(zone.privado_rt_4);
            $('#privado_rt_5_edit').val(zone.privado_rt_5);
            $('#privado_rt_6_edit').val(zone.privado_rt_6);
            $('#compartido_ow_edit').val(zone.compartido_ow);
            $('#compartido_rt_edit').val(zone.compartido_rt);
            $('#compartido_ow_premium_edit').val(zone.compartido_ow_premium);
            $('#compartido_rt_premium_edit').val(zone.compartido_rt_premium);
            $('#lujo_ow_1_edit').val(zone.lujo_ow_1);
            $('#lujo_ow_2_edit').val(zone.lujo_ow_2);
            $('#lujo_rt_1_edit').val(zone.lujo_rt_1);
            $('#lujo_rt_2_edit').val(zone.lujo_rt_2);
            edit = true;
        });
    }

    //Edit update datas zone
    $('#zone-form-edit').submit(function(e){
        
        const postDatas = {
            'id': $('#zone-id-edit').val(),
            'type_rate': $('#rate_type').val(),
            'name_zone': $('#nombre_zona_edit').val(),
            'privado_ow_1': $('#privado_ow_1_edit').val(),
            'privado_ow_2': $('#privado_ow_2_edit').val(),
            'privado_ow_3': $('#privado_ow_3_edit').val(),
            'privado_ow_4': $('#privado_ow_4_edit').val(),
            'privado_ow_5': $('#privado_ow_5_edit').val(),
            'privado_ow_6': $('#privado_ow_6_edit').val(),
            'privado_rt_1': $('#privado_rt_1_edit').val(),
            'privado_rt_2': $('#privado_rt_2_edit').val(),
            'privado_rt_3': $('#privado_rt_3_edit').val(),
            'privado_rt_4': $('#privado_rt_4_edit').val(),
            'privado_rt_5': $('#privado_rt_5_edit').val(),
            'privado_rt_6': $('#privado_rt_6_edit').val(),
            'compartido_ow': $('#compartido_ow_edit').val(),
            'compartido_rt': $('#compartido_rt_edit').val(),
            'compartido_ow_premium': $('#compartido_ow_premium_edit').val(),
            'compartido_rt_premium': $('#compartido_rt_premium_edit').val(),
            'lujo_ow_1': $('#lujo_ow_1_edit').val(),
            // 'lujo_ow_2': $('#lujo_ow_2_edit').val(),
            'lujo_rt_1': $('#lujo_rt_1_edit').val(),
            // 'lujo_rt_2': $('#lujo_rt_2_edit').val(),
            'edit': 'true',
        };
        let url = '../../helpers/tarifas.php';
        $.post(url,postDatas,function(response){
            if (postDatas.type_rate == 'rate_public') {
                let obj = "PUBLIC";
                loadZones(obj);
                $('.alert-msg').show();
                $('#result-search').hide('slow'); 
                $('#text-msg').val(response);
                $("#table-data-pu").show('slow');
                $('#search').trigger('reset');
            }
            if (postDatas.type_rate == 'rate_agencie') {
                let obj = "AGENCIES";
                loadZones(obj);
                $('.alert-msg-ag').show();
                $('#result-search-ag').hide('slow'); 
                $('#text-msg-ag').val(response);
                $("#table-data-ag").show('slow');
                $('#search').trigger('reset');
            }
            if (postDatas.type_rate == 'rate_tureando') {
                let obj = "TUREANDO";
                loadZones(obj);
                $('.alert-msg-tu').show();
                $('#result-search-tu').hide('slow'); 
                $('#text-msg-tu').val(response);
                $("#table-data-tu").show('slow');
                $('#search').trigger('reset');
            }
            $('#search').trigger('reset');
            $('#exampleModalEdit').modal('hide');
        });
        e.preventDefault();
    });

    // Visualizacion de datos 
    function loadZones(lets){
        function loadData(page){
            $.ajax({
                url: '../../helpers/tarifas.php',
                type: "post",
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
                        $("#table-data-pu").html(template);
                        
                    }
                    if (lets = 'PUBLIC') {
                        $("#table-data-pu").html(template);
                    }   
                    if (lets = 'AGENCIES') {
                        $("#table-data-ag").html(template);
                        
                    }
                    if (lets = 'TUREANDO') {
                        $("#table-data-tu").html(template);
                        
                    }
                },
                success: function(response){
                    if (lets = '') {
                        $("#table-data-pu").html(response);
                        
                    }
                    if (lets = 'PUBLIC') {
                        $("#table-data-pu").html(response);
                    }  
                    if (lets = 'AGENCIES') {
                        $("#table-data-ag").html(response);
                        
                    }
                    if (lets = 'TUREANDO') {
                        $("#table-data-tu").html(response);
                        
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


    /*Selecciona el tipo de tarifa que quiere editar P, A o T */
    $(document).on('click', '.zone-item-pu', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('zone-id');
        let type_e = 'rate_public';
        $('#rate_type').val(type_e);
        $("#nombre_zona_edit").prop('disabled', false);
        getDatasEdit(id, type_e);
    });
    $(document).on('click', '.zone-item-ag', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('zone-id');
        let type_e = 'rate_agencie';
        $('#rate_type').val(type_e);
        $("#nombre_zona_edit").prop('disabled', true);
        getDatasEdit(id, type_e);
    });
    $(document).on('click', '.zone-item-tu', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('zone-id');
        let type_e = 'rate_tureando';
        $('#rate_type').val(type_e);
        $("#nombre_zona_edit").prop('disabled', true);
        getDatasEdit(id, type_e);
    });
    // Traer valores Edit zones busqueda
    function getDatasEditItem(id, type_e){
        const postData = {
            'id': id,
            'single': type_e,
        }
        $.post('../../helpers/tarifas.php', postData, function(response){
            $('#exampleModalEdit').modal('show');
            const zone = JSON.parse(response);
            $('#zone-id-edit').val(zone.id_zone);
            $('#nombre_zona_edit').val(zone.name_zone);
            $('#privado_ow_1_edit').val(zone.privado_ow_1);
            $('#privado_ow_2_edit').val(zone.privado_ow_2);
            $('#privado_ow_3_edit').val(zone.privado_ow_3);
            $('#privado_ow_4_edit').val(zone.privado_ow_4);
            $('#privado_ow_5_edit').val(zone.privado_ow_5);
            $('#privado_ow_6_edit').val(zone.privado_ow_6);
            $('#privado_rt_1_edit').val(zone.privado_rt_1);
            $('#privado_rt_2_edit').val(zone.privado_rt_2);
            $('#privado_rt_3_edit').val(zone.privado_rt_3);
            $('#privado_rt_4_edit').val(zone.privado_rt_4);
            $('#privado_rt_5_edit').val(zone.privado_rt_5);
            $('#privado_rt_6_edit').val(zone.privado_rt_6);
            $('#compartido_ow_edit').val(zone.compartido_ow);
            $('#compartido_rt_edit').val(zone.compartido_rt);
            $('#compartido_ow_premium_edit').val(zone.compartido_ow_premium);
            $('#compartido_rt_premium_edit').val(zone.compartido_rt_premium);
            $('#lujo_ow_1_edit').val(zone.lujo_ow_1);
            $('#lujo_ow_2_edit').val(zone.lujo_ow_2);
            $('#lujo_rt_1_edit').val(zone.lujo_rt_1);
            $('#lujo_rt_2_edit').val(zone.lujo_rt_2);
            edit = true;
        });
    }

    //Delete zona
    $(document).on('click', '.zone-delete', function(){
        if (confirm('¿Esta seguro de querer eliminar la zona?')) {
            let element = $(this)[0].parentElement.parentElement;
            let id = $(element).attr('zone-id');
            let delet = 'delete';
            const postData = {
                'id': id,
                'delete': delet,
            }
            $.post('../../helpers/tarifas.php', postData, function(response){
                nav = 'PUBLIC'
                loadZones(nav);
                $('.alert-msg').show();
                $('#text-msg').val(response);
            });
        }
    });

    //Delete zona buscador
    $(document).on('click', '.zone-delete-search', function(){
        if (confirm('¿Esta seguro de querer eliminar la zona?')) {
            let element = $(this)[0].parentElement.parentElement;
            let id = $(element).attr('zone-id');
            let delet = 'delete';
            const postData = {
                'id': id,
                'delete': delet,
            }
            $.post('../../helpers/tarifas.php', postData, function(response){
                $("#table-data").show('slow');
                nav = 'PUBLIC'
                loadZones(nav);
                $('#result-search').hide('slow');
                $('#form-search').trigger('reset');
                $('.alert-msg').show();
                $('#text-msg').val(response);
            });
        }
    });
});