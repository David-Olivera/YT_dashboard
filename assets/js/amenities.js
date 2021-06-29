$(function(){
    $('#sidebar, #content').toggleClass('active');
    let edit = false;
    $('#result-search').hide();
    loadAmenities();

    
    // Btn cancelar
    $('#cancelButtonModal').on("click", function(e){
        e.preventDefault();
        $('#exampleModal').modal('hide');
        $('#amenityFormModal').trigger('reset');
    });
    // Btn cancelar edit
    $('#cancelButtonModalEdit').on("click", function(e){
        $('#exampleModalEdit').modal('hide');
        $('#amenityFormModalEdit').trigger('reset');
        $('#amenityFormModal').trigger('reset');
        e.preventDefault();
    });
    //Button Nuevo usuario
    $("#formButton").click(function(){
        edit = false;
        $("#crud-form").toggle('slow');
        $("#formButton").hide();
        $('#amenityForm').trigger('reset');
        $('.alert-msg').hide();
        document.getElementById("resultSearch").className = "col-lg-9 pl-4";
    });

    //Button cancelar form
    $("#cancelButton").click(function(){
        $("#crud-form").hide('slow');
        $("#formButton").show();
        $('#amenityForm').trigger('reset');
        document.getElementById("resultSearch").className = "col-lg-12";
    });

    //Button cancelar form edit
    $("#cancelButtonEdit").click(function(){
        $("#crud-form-edit").hide('slow');
        $("#formButton").show();
        $('#amenityForm').trigger('reset');
        document.getElementById("resultSearch").className = "col-lg-12";
    });

    //Btn x de alert mensaje
    $("#alert-close").click(function(){
        $(".alert-msg").hide('slow');
    });

     /* Buscar amenidad */
     $('#search').keyup(function(){
        if($('#search').val()) {
            let data = $(this).val();
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
                url:'../../helpers/amenidades.php',
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
                                    <p>No se encontro ninguna amenidad que coincida.</p>
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
                        <table class='table table-hover table-bordered table-sm' cellspacing='0' id='tablaAmenidades'>
                                <thead class='thead-dark'>
                                    <tr>
                                        <th>Id</th>
                                        <th>Amenidad</th>
                                        <th>Tipo de amenidad</th>
                                        <th>Descripción</th>
                                        <th>Precio MX</th>
                                        <th>Precio US</th>
                                        <th>Imagen</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>`;
                            agencies.forEach(amenities => {
                                template += `
                                    <tr amenity-id='${amenities.id_amenity}'>
                                            <td>${amenities.id_amenity}</td>
                                            <td>${amenities.name_amenity}</td>
                                            <td>${amenities.type_amenity}</td>
                                            <td>${amenities.description}</td>
                                            <td>$ ${amenities.price_mx}</td>
                                            <td>$ ${amenities.price_us}</td>
                                            <td class='text-center'>`;
                                if (amenities.img == "") {
                                    template += `
                                                <a href='#' class='btn btn-sm btn-primary' title='Presiona para subir imagen'  data='${amenities.name_amenity}' class='add_img' id='add_img' data-toggle='modal' data-target='#exampleModal'>Subir <i class='fas fa-upload'></i></a>
                                            </td>
                                            <td class='text-center'>
                                                <a href="#" class="amenity-item  btn btn-primary btn-sm rounded-circle" ><i class="fas fa-edit" ></i></a>
                                            </td>
                                            <td class='text-center'>
                                                <a href="#" class="amenity-delete-search btn btn-danger btn-sm rounded-circle "><i class="fas fa-trash-alt"></i></a>
                                            </td>
                                    </tr>
                                    `;
                                    
                                }else{
                                    template += `
                                            <a href='#' title='Presiona para editar' class='edit_img' id='add_img' data-toggle='modal' data='${amenities.name_amenity}' data-target='#exampleModal'><img src='../../img/amenidades/${amenities.img}' class='img-thumbnail '></a>
                                            </td>
                                            <td class='text-center'>
                                            <a href="#" class="amenity-item  btn btn-primary btn-sm rounded-circle" ><i class="fas fa-edit" ></i></a>
                                            </td>
                                            <td class='text-center'>
                                            <a href="#" class="amenity-delete-search btn btn-danger btn-sm rounded-circle "><i class="fas fa-trash-alt"></i></a>
                                            </td>
                                    </tr>
                                    ` ; 
                                }
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
            $('#amenityForm').trigger('reset');
            $("#formButton").show();
            $("#crud-form-edit").hide('slow');
            document.getElementById("resultSearch").className = "col-lg-12";
        }
    });
    

    /* NEW Listar amenidades */
    function loadAmenities(){
        function loadData(page,value){
            value =document.getElementById('value').value;
            $.ajax({
                url  : "../../model/amenidades_paginacion.php",
                type : "POST",
                cache: false,
                data : {page_no:page, value:value},
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

    /* Agregar amenidad  */
    $('#crud-form').submit(function(e){
        const postDatas = {
            'name_amenity': $('#nombre_amenidad').val(),
            'type_amenity': $('#amenidad').val(),
            'description': $('#descripcion').val(),
            'price_mx': $('#price_mx').val(),
            'price_us': $('#price_us').val(),
            'edit': 'false',
        };
        if (postDatas.name_amenity == null || postDatas.name_amenity.length == 0 || /^\s+$/.test(postDatas.name_amenity)) {
            alert('El nombre de la Amenidad es un campo obligatorio.');
            $('#nombre_amenidad').focus();
            return false;
        }
        if (/'/.test(postDatas.name_amenity)) {
            alert('La amenidad con nombre ' + postDatas.name_amenity +' tiene caracteres no permititdos.');
            $('#nombre_amenidad').focus();
            return false;
        }
        if(postDatas.type_amenity == null || postDatas.type_amenity.length == 0 || /^\s+$/.test(postDatas.type_amenity) ) {
            alert('Es necesario asignarle el tipo de amenidad.');
            $('#role').focus();
			return false; 
        }
        if (/'/.test(postDatas.description)) {
            alert('La amenidad con nombre ' + postDatas.description +' tiene caracteres no permititdos.');
            $('#descripcion').focus();
            return false;
        }
        if (!(/^[0-9]+$/.test(postDatas.price_mx))) {
            alert('Solo se aceptan numeros.');
            $('#price_mx').focus();
            return false;
        }
        if (!(/^[0-9]+$/.test(postDatas.price_us))) {
            alert('Solo se aceptan numeros.');
            $('#price_us').focus();
            return false;
        }
        $.ajax({
            data: postDatas,
            url: '../../helpers/amenidades.php',
            type: 'post',
            success: function(data) {
                var json = $.parseJSON(data);
                $('#amenityForm').trigger('reset');
                $("#crud-form").toggle('slow');
                $("#formButton").show();
                $('#form-search').trigger('reset');
                $('#result-search').hide();
                $('.alert-msg').show();
                $('#text-msg').val(json.message);
                if (json.code == 1) {
                    loadAmenities();
                }
                document.getElementById("resultSearch").className = "col-lg-12";
            }
        });
        e.preventDefault();
    });

    // Abrir modal de agregar img
    $(document).on('click', '#add_img', function(){
        let element = $(this)[0];
        let amenity = $(element).attr('data');
        let newamenity = amenity.replace(/ /g, "");
        let key = generateUUID();
        $('#name_ame_img').val(key  + '_' +newamenity+'.png');
    });

    function generateUUID() {
        var d = new Date().getTime();
        var uuid = 'xxxxxxxxxx4xxxyxxxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            var r = (d + Math.random() * 16) % 16 | 0;
            d = Math.floor(d / 16);
            return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
        return uuid;
    }
    
    /* Agregar img amenidad */
    $("#amenityFormModal").on('submit',(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        let name_ame_img = $('#name_ame_img').val();
        formData.append("name_ame_img", name_ame_img);
        $.ajax({
            url: "../../model/amenidades_img.php",
            type: "POST",
            data:  formData,
            contentType: false,
            cache: false,
            processData:false,
            success: function(data)
            {
                $('#result-search').hide('slow'); 
                $("#table-data").show('slow');
                $('#form-search').trigger('reset');
                loadAmenities();
                $('#amenityFormModal').trigger('reset');
                $('#exampleModal').modal('hide');
                $('.alert-msg').show();
                $('#text-msg').val(data);
            }          
        });
    }));
    
    //traer id para add
    $(document).on('click','#add_img', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('amenity-id');
        let single = 'single_img';
        const postDatas = {
            'id': id,
            'single_img' : single
        }
        $.post('../../helpers/amenidades.php', postDatas, function(response){
            const amenity = JSON.parse(response);
            $('#id_amenity_img').val(amenity.id_amenity);
            if (amenity.img == '' || amenity.img == null) {
                $('.elimined').hide();
            }else{
                let img = new Image();
                img.src = "../../assets/img/amenidades/"+amenity.img;
                $('.elimined').show();
                $('#name_img').val(amenity.img);
                $('#img_ame').attr('src',img.src);

            }
            edit = true;
        });
    });

    
    // Traer valores de Editar icon
    $(document).on('click','.amenity-edit', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('amenity-id');
        let single = 'single';
        const postData = {
            'id': id,
            'single': single,
        }
        $.post('../../helpers/amenidades.php',postData, function(response){
            loadAmenities();
            $("#crud-form-edit").show('slow');
            $("#crud-form").hide();
            $("#formButton").hide();
            document.getElementById("resultSearch").className = "col-lg-9 pl-4";
            const amenity = JSON.parse(response);
            $('#nombre_amenidad_edit').val(amenity.name_amenity);
            $('#amenidad_edit').val(amenity.type_amenity);
            $('#descripcion_edit').val(amenity.description);
            $('#price_mx_edit').val(amenity.price_mx);
            $('#price_us_edit').val(amenity.price_us);
            $('#amenity-id').val(amenity.id_amenity);
            edit = true;
        });
    });
    
    // Traer valores de Editar busqueda
    $(document).on('click','.amenity-item', function(){
            let element = $(this)[0].parentElement.parentElement;
            let id = $(element).attr('amenity-id');
            let single = 'single';
            const postData = {
                'id': id,
                'single': single,
            }
            $.post('../../helpers/amenidades.php',postData, function(response){
                loadAmenities();
                $("#crud-form-edit").show('slow');
                $("#formButton").hide();
                document.getElementById("resultSearch").className = "col-lg-9 pl-4";
                const amenity = JSON.parse(response);
                $('#nombre_amenidad_edit').val(amenity.name_amenity);
                $('#amenidad_edit').val(amenity.type_amenity);
                $('#descripcion_edit').val(amenity.description);
                $('#price_mx_edit').val(amenity.price_mx);
                $('#price_us_edit').val(amenity.price_us);
                $('#amenity-id').val(amenity.id_amenity);
                edit = true;
            });
    });

    /* Edit amenity  */
    $('#crud-form-edit').submit(function(e){
        const postDatas = {
            'id': $('#amenity-id').val(),
            'name_amenity': $('#nombre_amenidad_edit').val(),
            'type_amenity': $('#amenidad_edit').val(),
            'description': $('#descripcion_edit').val(),
            'price_mx': $('#price_mx_edit').val(),
            'price_us': $('#price_us_edit').val(),
            'edit': 'true',
        };
        let url = '../../helpers/amenidades.php';
        $.post(url, postDatas, function(response) {
            $("#table-data").show('slow');
            loadAmenities();
            $('#amenityFormEdit').trigger('reset');
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

    // Delete amenitu
    $(document).on('click', '.amenity-delete', function() {
        if (confirm('¿Esta seguro de querer eliminar la amenidad?')) {
         let element = $(this)[0].parentElement.parentElement;
         console.log(element);
         let id = $(element).attr('amenity-id');
         let delet = 'delete';
         const postData = {
             'id': id,
             'delete': delet,
         }
         $.post('../../helpers/amenidades.php', postData, function(response){
            $("#table-data").show('slow');
             loadAmenities();
             $('#result-search').hide('slow');
             $('#form-search').trigger('reset');
             $('.alert-msg').show();
             $('#text-msg').val(response);
 
         });
            
        }
     });    
     
    // Delete amenidad img
    $(document).on('click', '#deleteImg', function() {
        if (confirm('¿Esta seguro de querer eliminar la amenidad?')) {
         let id = $('#id_amenity_img').val();
         let name = $('#name_img').val();
         let delet = 'delete_img';
         console.log(id);
         const postData = {
             'id': id,
             'name': name,
             'delete': delet,
         }
         $.post('../../helpers/amenidades.php', postData, function(response){
            $("#table-data").show('slow');
            loadAmenities();
            $('#amenityFormModal').trigger('reset');
            $('#exampleModal').modal('hide');
            $('.alert-msg').show();
            $('#text-msg').val(response);
 
         });
            
        }
     });   

    // Delete amenitu busqueda
    $(document).on('click', '.amenity-delete-search', function() {
        if (confirm('¿Esta seguro de querer eliminar la amenidad?')) {
         let element = $(this)[0].parentElement.parentElement;
         console.log(element);
         let id = $(element).attr('amenity-id');
         let delet = 'delete';
         const postData = {
             'id': id,
             'delete': delet,
         }
         $.post('../../helpers/amenidades.php', postData, function(response){
            $("#table-data").show('slow');
             loadAmenities();
             $('#result-search').hide('slow');
             $('#form-search').trigger('reset');
             $('.alert-msg').show();
             $('#text-msg').val(response);
 
         });
            
        }
     });   

});