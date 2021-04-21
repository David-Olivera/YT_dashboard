$(function(){

    $('#sidebar, #content').toggleClass('active');
    let edit = false;
    loadReps();
    $('#result-search').hide();

    
    //Button Nuevo usuario
    $("#formButton").click(function(){
        edit = false;
        $("#crud-form").toggle('slow');
        $("#formButton").hide();
        $('#repForm').trigger('reset');
        $('.alert-msg').hide();
        document.getElementById("resultSearch").className = "col-lg-9";
    });
    
    //Button cancelar form
    $("#cancelButton").click(function(){
        $("#crud-form").hide('slow');
        $("#formButton").show();
        $('#repForm').trigger('reset');
        document.getElementById("resultSearch").className = "col-lg-12";
    });

    //Button cancelar form edit
    $("#cancelButtonEdit").click(function(){
        $("#crud-form-edit").hide('slow');
        $("#formButton").show();
        $('#repForm').trigger('reset');
        document.getElementById("resultSearch").className = "col-lg-12";
    });

    //Btn x de alert mensaje
    $("#alert-close").click(function(){
        $(".alert-msg").hide('slow');
    });

    /* Buscar rep */
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
                    url:'../../helpers/reps.php',
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
                            let reps = JSON.parse(response);
                            if (reps == '') {  
                                let template = '';
                                template += `
                                <div class=' p-2  col-lg-12 text-right'>
                                    <a href="#" class='btn btn-black btn-sm' id='cerrar_results'>X</a>
                                </div>
                                    <div class="col-lg-12">
                                        <p>No se encontro ningún REP que coincida.</p>
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
                                            <th>Apellido</th>
                                            <th>Email</th>
                                            <th>Teléfono</th>
                                            <th>Notas</th>
                                            <th>Registrado</th>
                                            <th></th>
                                            <th></th>
                                            </tr>
                                    </thead>
                                    <tbody>
                                `;
                                reps.forEach(reps => {
                                    template += `
                                        <tr rep-id='${reps.id_rep}'>
                                                <td>${reps.id_rep}</td>
                                                <td>${reps.name_rep}</td>
                                                <td>${reps.last_name}</td>
                                                <td>${reps.email_rep}</td>
                                                <td>${reps.phone_rep}</td>
                                                <td>${reps.notes_rep}</td>
                                                <td>${reps.date_register}</td>
                                                <td class='text-center text-center'>
                                                    <a href="#" class="rep-item btn btn-primary btn-sm " ><i class="fas fa-edit" ></i></a>
                                                </td>
                                                <td class='text-center text-center'>
                                                    <a href="#" class="rep-delete-search btn btn-danger btn-sm "><i class="fas fa-trash-alt"></i></a>
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
                $('#repForm').trigger('reset');
                $("#formButton").show();
                $("#crud-form-edit").hide('slow');
                $('#repForm').trigger('reset');
                document.getElementById("resultSearch").className = "col-lg-12";
            }
    }); 

    // Listar reps
    function loadReps(){
        function loadData(page){
            $.ajax({
                url: "../../model/reps_paginacion.php",
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

    //Add rep
    $('#crud-form').submit(function(e){
        var postDatas = {
            'name_rep': $('#nombre_rep').val(),
            'last_name': $('#apellido_rep').val(),
            'email_rep': $('#email_rep').val(),
            'phone_rep': $('#telefono_rep').val(),
            'notes_rep': $('#notas_rep').val(),
            'edit': 'false',
        };
        if (postDatas.name_rep == null || postDatas.name_rep.length == 0 || /^\s+$/.test(postDatas.name_rep)) {
            alert('El nombre del REP es un campo obligatorio.');
            $('#nombre_hotel').focus();
            return false;
        }
        if (!(/\w+([-+.']\w+)*@\w+([-.]\w+)/.test(postDatas.email_rep))) {
			alert('En necesario ingresar una dirección de correo valida.');
			$('#email_agnecia').focus();
			return false;  
        }
        if (/'/.test(postDatas.name_rep)) {
            alert('El REP con nombre ' + postDatas.name_rep +' tiene caracteres no permititdos.');
            $('#nombre_rep').focus();
            return false;
        }
        $.ajax({
            data: postDatas,
            url: '../../helpers/reps.php',
            type: 'post',
            success: function(data){
                var json = $.parseJSON(data);
                $('#repForm').trigger('reset');
                $("#crud-form").toggle('slow');
                $("#formButton").show();
                $('#form-search').trigger('reset');
                $('#result-search').hide();
                $('.alert-msg').show();
                $('#text-msg').val(json.message);
                if (json.code == 1) {
                    loadReps();
                }
                document.getElementById("resultSearch").className = "col-lg-12";
            }
        });
        e.preventDefault();
    });

    /* Edit REP  */
    $('#crud-form-edit').submit(function(e){
        const postDatas = {
            'id': $('#rep-id').val(),
            'name_rep': $('#nombre_rep_edit').val(),
            'last_name': $('#apellido_rep_edit').val(),
            'email_rep': $('#email_rep_edit').val(),
            'phone_rep': $('#telefono_rep_edit').val(),
            'notes_rep': $('#notas_rep_edit').val(),
            'edit': 'true',

        };
        let url = '../../helpers/reps.php';
        $.post(url, postDatas, function(response) {
            $("#table-data").show('slow');
            loadReps();
            $('#repFormEdit').trigger('reset');
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
    $(document).on('click','.rep-edit', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('rep-id');
        let single = 'single';
        let newrole = '';
        const postData = {
            'id': id,
            'single': single,
        }
        $.post('../../helpers/reps.php',postData, function(response){
            loadReps();
            $("#crud-form-edit").show('slow');
            $("#crud-form").hide();
            $("#formButton").hide();
            document.getElementById("resultSearch").className = "col-lg-9";
            const reps = JSON.parse(response);
            $('#nombre_rep_edit').val(reps.name_rep);
            $('#apellido_rep_edit').val(reps.last_name);
            $('#email_rep_edit').val(reps.email_rep);
            $('#telefono_rep_edit').val(reps.phone_rep);
            $('#notas_rep_edit').val(reps.notes_rep);
            $('#rep-id').val(reps.id_rep);
            edit = true;
        });
    });

    //Traer valores de rep del buscador Edit
    $(document).on('click', '.rep-item', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('rep-id');
        let single = 'single';
        const postData = {
            'id': id,
            'single': single,
        }
        $.post('../../helpers/reps.php',postData, function(response){
            loadReps();
            $("#crud-form-edit").show('slow');
            $("#formButton").hide();
            document.getElementById("resultSearch").className = "col-lg-9";
            const reps = JSON.parse(response);
            $('#nombre_rep_edit').val(reps.name_rep);
            $('#apellido_rep_edit').val(reps.last_name);
            $('#email_rep_edit').val(reps.email_rep);
            $('#telefono_rep_edit').val(reps.phone_rep);
            $('#notas_rep_edit').val(reps.notes_rep);
            $('#rep-id').val(reps.id_rep);
            edit = true;
        });
    });

    //Delete rep
    $(document).on('click', '.rep-delete', function() {
        if (confirm('¿Esta seguro de querer eliminar al REP?')) {
         let element = $(this)[0].parentElement.parentElement;
         console.log(element);
         let id = $(element).attr('rep-id');
         let delet = 'delete';
         const postData = {
             'id': id,
             'delete': delet,
         }
         $.post('../../helpers/reps.php', postData, function(response){
             loadReps();
             $('#form-search').trigger('reset');
             $('.alert-msg').show();
             $('#text-msg').val(response);
           });
            
        }
    });  

    //Delete rep en busqueda
    $(document).on('click', '.rep-delete-search', function() {
       if (confirm('¿Esta seguro de querer eliminar al REP?')) {
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('rep-id');
        let delet = 'delete';
        const postData = {
            'id': id,
            'delete': delet,
        }
        $.post('../../helpers/reps.php', postData, function(response){
            $("#table-data").show('slow');
            loadReps();
            $('#result-search').hide();
            $('#form-search').trigger('reset');
            $('.alert-msg').show();
            $('#text-msg').val(response);
        });
           
       }
    });
});