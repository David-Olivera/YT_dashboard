$(function(){

    $('#sidebar, #content').toggleClass('active');
    let edit = false;
    loadProvider();
    $('#result-search').hide();

    
    
    //Button Nuevo usuario
    $("#formButton").click(function(){
        edit = false;
        $("#crud-form").toggle('slow');
        $("#formButton").hide();
        $('#providerForm').trigger('reset');
        $('.alert-msg').hide();
        document.getElementById("resultSearch").className = "col-lg-9";
    });
    
    //Button cancelar form
    $("#cancelButton").click(function(){
        $("#crud-form").hide('slow');
        $("#formButton").show();
        $('#providerForm').trigger('reset');
        document.getElementById("resultSearch").className = "col-lg-12";
    });

    //Button cancelar form edit
    $("#cancelButtonEdit").click(function(){
        $("#crud-form-edit").hide('slow');
        $("#formButton").show();
        $('#providerForm').trigger('reset');
        document.getElementById("resultSearch").className = "col-lg-12";
    });

    //Btn x de alert mensaje
    $("#alert-close").click(function(){
        $(".alert-msg").hide('slow');
    });

    /* Buscar prvider */
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
                url:'../../helpers/proveedores.php',
                type:'POST',
                data:postData,
                beforeSend: function(){
                    let template = '';
                    template += `
                    <div class="col-lg-4 col-md-3">
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <img src="../../img/load.gif" alt="Italian Trulli">
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
                        let provider = JSON.parse(response);
                        if (provider == '') {  
                            let template = '';
                            template += `
                            <div class=' p-2  col-lg-12 text-right'>
                                <a href="#" class='btn btn-black btn-sm' id='cerrar_results'>X</a>
                            </div>
                                <div class="col-lg-12">
                                    <p>No se encontro ningún Proveedor que coincida.</p>
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
                                        <th>Nombre</th>
                                        <th>Contacto</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Registrado</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        </tr>
                                </thead>
                                <tbody>
                            `;
                            provider.forEach(provider => {
                                template += `
                                    <tr provider-id='${provider.id_provider}'>
                                            <td>${provider.id_provider}</td>
                                            <td>${provider.name_provider}</td>
                                            <td>${provider.name_contact}</td>
                                            <td>${provider.email_provider}</td>
                                            <td>${provider.phone_provider}</td>
                                            <td>${provider.register_date}</td>
                                            <td class='text-center text-center'>
                                                <a href="#" class="provider-item btn btn-primary btn-sm " ><i class="fas fa-edit" ></i></a>
                                            </td>
                                            <td class='text-center text-center'>
                                                <a href="#" class="provider-delete-search btn btn-danger btn-sm "><i class="fas fa-trash-alt"></i></a>
                                            </td>
                                            <td class='text-center text-center'>
                                                <a href='providers_rates.php?id_provider=${provider.id_provider}&provider=${provider.name_provider}' target='_blank' id='provider-tarifa' title='Tarifas de proveedor' class=' btn btn-black btn-sm '><i class='fas fa-calculator'></i></a>
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
            $('#providerForm').trigger('reset');
            $("#formButton").show();
            $("#crud-form-edit").hide('slow');
            $('#providerForm').trigger('reset');
            document.getElementById("resultSearch").className = "col-lg-12";
        }
    }); 

    // Listar provider
    function loadProvider(){
        function loadData(page){
            $.ajax({
                url: "../../model/proveedores_paginacion.php",
                type: "POST",
                cache: false,
                data: {page_no:page},
                success: function(response){
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

    //Add provider
    $('#crud-form').submit(function(e){
        var postDatas = {
            'name_provider': $('#nombre_prov').val(),
            'name_contact': $('#name_cont').val(),
            'last_contact': $('#apellido_cont').val(),
            'email_provider': $('#email_prov').val(),
            'phone_provider': $('#telefono_prov').val(),
            'edit': 'false',
        };
        if (postDatas.name_provider == null || postDatas.name_provider.length == 0 || /^\s+$/.test(postDatas.name_provider)) {
            alert('El nombre del Proveedor es un campo obligatorio.');
            $('#nombre_prov').focus();
            return false;
        }
        if (!(/\w+([-+.']\w+)*@\w+([-.]\w+)/.test(postDatas.email_provider))) {
			alert('En necesario ingresar una dirección de correo valida.');
			$('#email_prov').focus();
			return false;  
        }
        if (/'/.test(postDatas.name_provider)) {
            alert('El REP con nombre ' + postDatas.name_provider +' tiene caracteres no permititdos.');
            $('#nombre_prov').focus();
            return false;
        }
        $.ajax({
            data: postDatas,
            url: '../../helpers/proveedores.php',
            type: 'post',
            success: function(data){
                var json = $.parseJSON(data);
                $('#providerForm').trigger('reset');
                $("#crud-form").toggle('slow');
                $("#formButton").show();
                $('#form-search').trigger('reset');
                $('#result-search').hide();
                $('.alert-msg').show();
                $('#text-msg').val(json.message);
                if (json.code == 1) {
                    loadProvider();
                }
                document.getElementById("resultSearch").className = "col-lg-12";
            }
        });
        e.preventDefault();
    });
    
    /* Edit provider  */
    $('#crud-form-edit').submit(function(e){
        const postDatas = {
            'id': $('#provider-id').val(),
            'name_provider': $('#nombre_prov_edit').val(),
            'name_contact': $('#name_cont_edit').val(),
            'last_contact': $('#apellido_cont_edit').val(),
            'email_provider': $('#email_prov_edit').val(),
            'phone_provider': $('#telefono_prov_edit').val(),
            'edit': 'true',

        };
        let url = '../../helpers/proveedores.php';
        $.post(url, postDatas, function(response) {
            $("#table-data").show('slow');
            loadProvider();
            $('#providerFormEdit').trigger('reset');
            $("#crud-form-edit").hide('slow');
            $("#formButton").show();
            $('#form-search').trigger('reset');
            $('#result-search').hide();
            $('.alert-msg').show();
            $('#text-msg').val(response);
            document.getElementById("resultSearch").className = "col-lg-12";
        });
        e.preventDefault();
    });

    // Edit REP trae valores
    $(document).on('click','.provider-edit', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('provider-id');
        let single = 'single';
        let newrole = '';
        const postData = {
            'id': id,
            'single': single,
        }
        $.post('../../helpers/proveedores.php',postData, function(response){
            loadProvider();
            $("#crud-form-edit").show('slow');
            $("#crud-form").hide();
            $("#formButton").hide();
            document.getElementById("resultSearch").className = "col-lg-9";
            const providers = JSON.parse(response);
            $('#nombre_prov_edit').val(providers.name_provider);
            $('#name_cont_edit').val(providers.name_contact);
            $('#apellido_cont_edit').val(providers.last_contact);
            $('#email_prov_edit').val(providers.email_provider);
            $('#telefono_prov_edit').val(providers.phone_provider);
            $('#provider-id').val(providers.id_provider);
            edit = true;
        });
    });

    //Traer valores de rep del buscador Edit
    $(document).on('click', '.provider-item', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('provider-id');
        let single = 'single';
        const postData = {
            'id': id,
            'single': single,
        }
        $.post('../../helpers/proveedores.php',postData, function(response){
            loadProvider();
            $("#crud-form-edit").show('slow');
            $("#formButton").hide();
            document.getElementById("resultSearch").className = "col-lg-9";
            const providers = JSON.parse(response);
            $('#nombre_prov_edit').val(providers.name_provider);
            $('#name_cont_edit').val(providers.name_contact);
            $('#apellido_cont_edit').val(providers.last_contact);
            $('#email_prov_edit').val(providers.email_provider);
            $('#telefono_prov_edit').val(providers.phone_provider);
            $('#provider-id').val(providers.id_provider);
            edit = true;
        });
    });

    //Delete rep
    $(document).on('click', '.provider-delete', function() {
        if (confirm('¿Esta seguro de querer eliminar al Proveedor?')) {
         let element = $(this)[0].parentElement.parentElement;
         console.log(element);
         let id = $(element).attr('provider-id');
         let delet = 'delete';
         const postData = {
             'id': id,
             'delete': delet,
         }
         $.post('../../helpers/proveedores.php', postData, function(response){
             loadProvider();
             $('#form-search').trigger('reset');
             $('.alert-msg').show();
             $('#text-msg').val(response);
           });
            
        }
    });  

    //Delete rep en busqueda
    $(document).on('click', '.provider-delete-search', function() {
       if (confirm('¿Esta seguro de querer eliminar al Proveedor?')) {
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('provider-id');
        let delet = 'delete';
        const postData = {
            'id': id,
            'delete': delet,
        }
        $.post('../../helpers/proveedores.php', postData, function(response){
            $("#table-data").show('slow');
            loadProvider();
            $('#result-search').hide();
            $('#form-search').trigger('reset');
            $('.alert-msg').show();
            $('#text-msg').val(response);
        });
           
       }
    });

    //Actualizar Derecho De Piso
	$('#inpDDP').on('focusout', function(){
        var postData = {
            'current_value': $(this).data('current'),
            'new_value': $(this).val(),
            'id_provider': $(this).data('provider'),
            'action': 'update_flooright'
        };
			$.ajax({
				data:  postData,
				url:   '../../helpers/proveedores.php',
				type:  'post',
												
				beforeSend: function(){
				},
	
				success:  function (data) {
                    $('.alert-msg').show();
                    $('#text-msg').val(data);													
				}

			});
    });

    //Actualizar tarifas de proveedor
    $(document).on('click', '#save_rates_provider', function(e){
        let zona = $(this).data('area');
        var postData = {
            'id_provider': $(this).data('provider'),
            'id_zone': zona,
            'rates': [
				{ 'rate_value' : $('#S'+zona+'1').val(), 'id' : $('#S'+zona+'1').data('update') },
				{ 'rate_value' : $('#P'+zona+'4').val(), 'id' : $('#P'+zona+'4').data('update') },
				{ 'rate_value' : $('#P'+zona+'6').val(), 'id' : $('#P'+zona+'6').data('update') },
				{ 'rate_value' : $('#P'+zona+'8').val(), 'id' : $('#P'+zona+'8').data('update') },
				{ 'rate_value' : $('#P'+zona+'10').val(), 'id' : $('#P'+zona+'10').data('update') },
				{ 'rate_value' : $('#L'+zona+'6').val(), 'id' : $('#L'+zona+'6').data('update') },
				{ 'rate_value' : $('#L'+zona+'8').val(), 'id' : $('#L'+zona+'8').data('update') },
            ],
            'action': 'update_rates'
        };
        
		$.ajax({
			data:  postData,
			url:   '../../helpers/proveedores.php',
			type:  'post',
			success:  function (data) {
                $('.alert-msg').hide('slow');
                $('.alert-msg').show('slow');
                $('#text-msg').val(data);																	
			}
		});
		e.preventDefault();
    });

});