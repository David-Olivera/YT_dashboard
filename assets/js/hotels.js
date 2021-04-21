$(function(){

    $('#sidebar, #content').toggleClass('active');
    let edit = false;
    loadHotels();
    $('#result-search').hide();
    
    //Button Nuevo usuario
    $("#formButton").click(function(){
        edit = false;
        $("#crud-form").toggle('slow');
        $("#formButton").hide();
        $('#hotelForm').trigger('reset');
        $('.alert-msg').hide();
        document.getElementById("resultSearch").className = "col-lg-9";
    });
    
    //Button cancelar form
    $("#cancelButton").click(function(){
        $("#crud-form").hide('slow');
        $("#formButton").show();
        $('#hotelForm').trigger('reset');
        document.getElementById("resultSearch").className = "col-lg-12";
    });

    //Button cancelar form edit
    $("#cancelButtonEdit").click(function(){
        $("#crud-form-edit").hide('slow');
        $("#formButton").show();
        $('#hotelForm').trigger('reset');
        document.getElementById("resultSearch").className = "col-lg-12";
    });

    //Btn x de alert mensaje
    $("#alert-close").click(function(){
        $(".alert-msg").hide('slow');
    });

    /* Buscar hotel */
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
                url:'../../helpers/hoteles.php',
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
                        let agencies = JSON.parse(response);
                        if (agencies == '') {  
                            let template = '';
                            template += `
                            <div class=' p-2  col-lg-12 text-right'>
                                <a href="#" class='btn btn-black btn-sm' id='cerrar_results'>X</a>
                            </div>
                                <div class="col-lg-12">
                                    <p>No se encontro ninguna hotel que coincida.</p>
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
                                            <th>Hotel</th>
                                            <th>Zona</th>
                                            <th></th>
                                            <th></th>
                                            </tr>
                                    </thead>
                                <tbody>
                            `;
                            agencies.forEach(hoteles => {
                                template += `
                                <tr hotel-id="${hoteles.id_hotel}">
                                    <td>${hoteles.id_hotel}</td>
                                    <td>${hoteles.name_hotel}</td>
                                    <td>${hoteles.name_zone}</td>
                                    <td class='text-center text-center'>
                                        <a href="#" class="hotel-item btn btn-primary btn-sm " ><i class="fas fa-edit" ></i></a>
                                    </td>
                                    <td class='text-center text-center'>
                                        <a href="#" class="hotel-delete-search btn btn-danger btn-sm  "><i class="fas fa-trash-alt"></i></a>
                                    </td>
						        </tr>`;  
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
            $("#formButton").show();
            $("#crud-form-edit").hide('slow');
            $('#hotelForm').trigger('reset');
            document.getElementById("resultSearch").className = "col-lg-12";
        }
    });

    // Listar hoteles
    function loadHotels(){
        function loadData(page){
            $.ajax({
                url: "../../model/hoteles_paginacion.php",
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

    //Add hotel
    $('#crud-form').submit(function(e){
        var postDatas = {
            'name_hotel': $('#nombre_hotel').val(),
            'name_zone': $('#nombre_zona').val(),
            'edit': 'false',
        };
        if (postDatas.name_hotel == null || postDatas.name_hotel.length == 0 || /^\s+$/.test(postDatas.name_hotel)) {
            alert('El nombre del Hotel es un campo obligatorio.');
            $('#nombre_hotel').focus();
            return false;
        }
        
        if (/'/.test(postDatas.name_hotel)) {
            alert('El hotel con nombre ' + postDatas.name_hotel +' tiene caracteres no permititdos.');
            $('#nombre_hotel').focus();
            return false;
        }
        if(postDatas.name_zone == null || postDatas.name_zone.length == 0 || /^\s+$/.test(postDatas.name_zone) ) {
            alert('Es necesario seleccionar una zona');
            $('#nombre_zona').focus();
			return false; 
        }
        $.ajax({
            data: postDatas,
            url: '../../helpers/hoteles.php',
            type: 'post',
            success: function(data){
                var json = $.parseJSON(data);
                $('#hotelForm').trigger('reset');
                $("#crud-form").toggle('slow');
                $("#formButton").show();
                $('#form-search').trigger('reset');
                $('#result-search').hide();
                $('.alert-msg').show();
                $('#text-msg').val(json.message);
                if (json.code == 1) {
                    loadHotels();
                }
                document.getElementById("resultSearch").className = "col-lg-12";
            }
        });
        e.preventDefault();
    });

    // Traer valores de Editar icon
    $(document).on('click','.hotel-edit', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('hotel-id');
        let single = 'single';
        const postData = {
            'id': id,
            'single': single,
        }
        $.post('../../helpers/hoteles.php',postData, function(response){
            loadHotels();
            $("#crud-form-edit").show('slow');
            $("#crud-form").hide();
            $("#formButton").hide();
            document.getElementById("resultSearch").className = "col-lg-9 pl-4";
            const hotel = JSON.parse(response);
            $('#nombre_hotel_edit').val(hotel.name_hotel);
            $('#zona_edit').val(hotel.name_zone);
            $('#hotel-id').val(hotel.id_hotel);
            edit = true;
        });
    });

    // Traer valores de Editar busqueda
    $(document).on('click','.hotel-item', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('hotel-id');
        let single = 'single';
        const postData = {
            'id': id,
            'single': single,
        }
        $.post('../../helpers/hoteles.php',postData, function(response){
            loadHotels();
            $("#crud-form-edit").show('slow');
            $("#formButton").hide();
            document.getElementById("resultSearch").className = "col-lg-9 pl-4";
            const hotel = JSON.parse(response);
            $('#nombre_hotel_edit').val(hotel.name_hotel);
            $('#zona_edit').val(hotel.name_zone);
            $('#hotel-id').val(hotel.id_hotel);
            edit = true;
        });
    });

    /* Edit hotel  */
    $('#crud-form-edit').submit(function(e){
        const postDatas = {
            'id': $('#hotel-id').val(),
            'name_hotel': $('#nombre_hotel_edit').val(),
            'name_zone': $('#zona_edit').val(),
            'edit': 'true',
        };
        let url = '../../helpers/hoteles.php';
        $.post(url, postDatas, function(response) {
            $("#table-data").show('slow');
            loadHotels();
            $('#hotelFormEdit').trigger('reset');
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

    // Delete hotel
    $(document).on('click', '.hotel-delete', function() {
        if (confirm('¿Esta seguro de querer eliminar el hotel?')) {
         let element = $(this)[0].parentElement.parentElement;
         console.log(element);
         let id = $(element).attr('hotel-id');
         let delet = 'delete';
         const postData = {
             'id': id,
             'delete': delet,
         }
         $.post('../../helpers/hoteles.php', postData, function(response){
             loadHotels();
             $('#result-search').hide('slow');
             $('#form-search').trigger('reset');
             $('.alert-msg').show();
             $('#text-msg').val(response);
 
         });
            
        }
     });    

     // Delete hotel de busqueda
    $(document).on('click', '.hotel-delete-search', function() {
        if (confirm('¿Esta seguro de querer eliminar el hotel?')) {
         let element = $(this)[0].parentElement.parentElement;
         console.log(element);
         let id = $(element).attr('hotel-id');
         let delet = 'delete';
         const postData = {
             'id': id,
             'delete': delet,
         }
         $.post('../../helpers/hoteles.php', postData, function(response){
            $("#table-data").show('slow');
             loadHotels();
             $('#result-search').hide('slow');
             $('#form-search').trigger('reset');
             $('.alert-msg').show();
             $('#text-msg').val(response);
 
         });
            
        }
     });    
});