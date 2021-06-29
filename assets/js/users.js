$(function(){

    let edit = false;
    $('#sidebar, #content').toggleClass('active');
    $('#result-search').hide();
    loadUsers();

    //Button Nuevo usuario
    $("#formButton").click(function(){
        edit = false;
        $("#crud-form").toggle('slow');
        $("#formButton").hide();
        $('#userForm').trigger('reset');
        $('.alert-msg').hide();
        document.getElementById("resultSearch").className = "col-lg-9 pl-4";
    });

    //Button cancelar form
    $("#cancelButton").click(function(){
        $("#crud-form").hide('slow');
        $("#formButton").show();
        $('#agencyForm').trigger('reset');
        document.getElementById("resultSearch").className = "col-lg-12";
    });

    //Button cancelar form edit
    $("#cancelButtonEdit").click(function(){
        $("#crud-form-edit").hide('slow');
        $("#formButton").show();
        $('#password_edit').removeClass(' is-invalid');
        $("#checked_pass_user_edit").prop('checked', false);
        $('#agencyForm').trigger('reset');
        document.getElementById("resultSearch").className = "col-lg-12";
    });

    //Btn x de alert mensaje
    $("#alert-close").click(function(){
        $(".alert-msg").hide('slow');
    });

    /* Buscar usuario */
    $('#search').keyup(function(){
        let value = document.getElementById("value").value;
        if($('#search').val()) {
            let data = $('#search').val();
            let search = 'search';
            if (/'/.test(data)) {
                
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
                url:'../../helpers/usuarios.php',
                type:'POST',
                data:postData,
                success: function(response){
                    if (!response.error) {
                        let users = JSON.parse(response);
                        if (users == '') {
                            let template = '';
                            template += `
                            <div class=' p-2  col-lg-12 text-right'>
                                <a href="#" class='btn btn-black btn-sm' id='cerrar_results'>X</a>
                            </div>
                                <div class="col-lg-12">
                                    <p>No se encontro ningún usuario que coincida.</p>
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
                                    <div class="table-responsive">
                                        <table class='table table-hover table-bordered table-sm' cellspacing='0' id='tablaUsuarios'>
                                            <thead class='thead-dark'>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Username</th>
                                                    <th>Email</th>
                                                    <th>Agencia</th>
                                                    <th>Role</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead> `;
                            users.forEach(users => {
                                
                                if (users.role == 1) {
                                    $newrole = 'Administrador';
                                }
                                if (users.role == 2) {
                                    $newrole = 'Operador';
                                }
                                if (users.role == 3) {
                                    $newrole = 'Vendedor';
                                }
                                if (users.role == 5) {
                                    $newrole = 'Usuario de Agencia';
                                }
                                if (users.agencia == null || users.agencia == '' || users.agencia == 0) {
                                    $newagency = 'Sin asignar';
                                }else{
                                    $newagency = users.agencia;
                                }
                                if (users.id_user == value) {
                                    template += `
                                            <tbody>
                                            <tr user-id="${users.id_user}" >
                                                <td>${users.id_user}</td>
                                                <td>${users.username}</td>
                                                <td>${users.email_user}</td>
                                                <td>${$newagency}</td>
                                                <td>${$newrole}</td>
                                                <td class='text-center'><a href="#" class="user-item btn btn-primary btn-sm " ><i class="fas fa-edit" ></i></a></td>
                                                <td></td>
                                            </tr>
                                            </tbody>
                                    ` ;   
                                }else{
                                    template += `
                                            <tbody>
                                            <tr user-id="${users.id_user}">
                                                <td>${users.id_user}</td>
                                                <td>${users.username}</td>
                                                <td>${users.email_user}</td>
                                                <td>${$newagency}</td>
                                                <td>${$newrole}</td>
                                                <td class='text-center'><a href="#" class="user-item btn btn-primary btn-sm " ><i class="fas fa-edit" ></i></a></td>
                                                <td class='text-center'><a href="#" class="user-delete-search btn btn-danger btn-sm "><i class="fas fa-trash-alt"></i></a></td>
                                            </tr>
                                            </tbody>    
                                    ` ;
                                }
                            });
                            $('#container').html(template);
                            $("#table-data").hide('slow');
                            $("#result-search").show('slow');
                        }
                    }
                }
            });
        }else{  
            $('#result-search').hide('slow');
            $("#table-data").show('slow');
            $("#crud-form").hide();
            $('#userForm').trigger('reset');
            $("#formButton").show();
            $("#crud-form-edit").hide('slow');
            $('#agencyForm').trigger('reset');
            document.getElementById("resultSearch").className = "col-lg-12";
        }
    });
    /* Agregar usuario  */
    $('#crud-form').submit(function(e){
        const postDatas = {
            'id': $('#user-id').val(),
            'first_name': $('#nombre_usuario').val(),
            'last_name': $('#apellido_paterno').val(),
            'email_user': $('#email_usuario').val(),
            'username': $('#username').val(),
            'password': $('#password').val(),
            'role': $('#role').val(),
            'edit': 'false',
        };
        if (postDatas.first_name == null || postDatas.first_name.length == 0 || /^\s+$/.test(postDatas.first_name)) {
            alert('El nombre del Usuario es un campo obligatorio.');
            $('#nombre_agencia').focus();
            return false;
        }
        if (!(/\w+([-+.']\w+)*@\w+([-.]\w+)/.test(postDatas.email_user))) {
			alert('En necesario ingresar una dirección de correo valida.');
			$('#email_agnecia').focus();
			return false;  
        }
        if(postDatas.role == null || postDatas.role.length == 0 || /^\s+$/.test(postDatas.role) ) {
            alert('Es necesario asignarle un rol al usuario');
            $('#role').focus();
			return false; 
        }
        $.ajax({
            data: postDatas,
            url: '../../helpers/usuarios.php',
            type: 'post',
            success: function(data) {
                var json = $.parseJSON(data);
                $('#userForm').trigger('reset');
                $("#crud-form").toggle('slow');
                $("#formButton").show();
                $('#form-search').trigger('reset');
                $('#result-search').hide();
                $('.alert-msg').show();
                $('#text-msg').val(json.message);
                console.log(json.sql);
                if (json.code == 1) {
                    loadUsers();
                }
                document.getElementById("resultSearch").className = "col-lg-12";
            }
        });
        e.preventDefault();
    });

    $('#crud-form-edit').submit(function(e){ 
        let checked = 0;
        var seleccion = $("#checked_pass_user_edit")[0].checked; 
        const postDatas = {
            'id': $('#user-id').val(),
            'first_name': $('#nombre_usuario_edit').val(),
            'last_name': $('#apellido_paterno_edit').val(),
            'email_user': $('#email_usuario_edit').val(),
            'username': $('#username_edit').val(),
            'password': $('#password_edit').val(),
            'status': seleccion,
            'role': $('#role_edit').val(),
            'edit': 'true',
        };
        if (postDatas.first_name == null || postDatas.first_name.length == 0 || /^\s+$/.test(postDatas.first_name)) {
            alert('El nombre del Usuario es un campo obligatorio.');
            $('#nombre_agencia').focus();
            return false;
        }
        if (!(/\w+([-+.']\w+)*@\w+([-.]\w+)/.test(postDatas.email_user))) {
			alert('En necesario ingresar una dirección de correo valida.');
			$('#email_agnecia').focus();
			return false;  
        }
        if(postDatas.role == null || postDatas.role.length == 0 || /^\s+$/.test(postDatas.role) ) {
            alert('Es necesario asignarle un rol al usuario');
            $('#role').focus();
			return false; 
        }
        
        if(seleccion){
            checked = 1;
            if ((postDatas.password == null || postDatas.password.length == 0 || postDatas.password.length < 6 || /^\s+$/.test(postDatas.password) || /'/.test(postDatas.password))) {
                $('#password_edit').addClass(" is-invalid");
                $('#password_edit').focus();
                return false;
            }
        }
        alert(postDatas.password+' - '+postDatas.status);
        $.post('../../helpers/usuarios.php', postDatas, function(response){
            loadUsers();
            $('#useerFormEdit').trigger('reset');
            $("#crud-form-edit").hide();
            $("#formButton").show();
            $('#form-search').trigger('reset');
            $('#result-search').hide();
            $('.alert-msg').show();
            $("#checked_pass_user_edit").prop('checked', false);
            $('#text-msg').val(response);
            document.getElementById("resultSearch").className = "col-lg-12";
        });
        e.preventDefault();
    });

    // $('#crud-form').click(function(e){
        // e.preventDefault();
        // submitForm();
    // });

    // function submitForm(){
        // let url = edit === false ? 'model/user-add.php' : 'model/user-edit.php';
        // const postDatas = {
            //  id: $('#user-id').val(),
            //  nombre_usuario: $('#nombre_usuario').val(),
            //  apellido_paterno: $('#apellido_paterno').val(),
            //  username: $('#username').val(),
            //  email_usuario: $('#email_usuario').val(),
            //  password: $('#password').val(),
            //  telefono_usuario: $('#telefono_usuario').val(),
            //  role: $('#role').val()
        //  };
        //  $.ajax({
            // type: "POST",
            // url: url,
            // data: postDatas,
            // success: function(response){
                // if(!response.error){
                    // fetchUsers();
                    // console.log(response);
                    // $('#crud-form').trigger('reset');
                    // $("#exampleModal").modal('hide'); 
                // }
            // }
        //  });
    // }
    // 
    /* NEW Listar usuarios */
    function loadUsers(){
        function loadData(page,value){
            value =document.getElementById('value').value;
            $.ajax({
                url  : "../../model/usuarios_paginacion.php",
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

    //Eliminar usuario
    $(document).on('click', '.user-delete', function() {
       if (confirm('¿Esta seguro de querer eliminar al usuario?')) {
        let element = $(this)[0].parentElement.parentElement;
        console.log(element);
        let id = $(element).attr('user-id');
        let delet = 'delete';
        const postData = {
            'id': id,
            'delete': delet,
        }
        $.post('../../helpers/usuarios.php', postData, function(response){
            loadUsers();
            $('#form-search').trigger('reset');
            $('.alert-msg').show();
            $('#text-msg').val(response);

        });
           
       }
    });    
    //Eliminar usuario en busqueda
    $(document).on('click', '.user-delete-search', function() {
       if (confirm('¿Esta seguro de querer eliminar al usuario?')) {
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('user-id');
        let delet = 'delete';
        const postData = {
            'id': id,
            'delete': delet,
        }
        $.post('../../helpers/usuarios.php', postData, function(response){
            $("#table-data").show('slow');
            loadUsers();
            $('#result-search').hide();
            $('#form-search').trigger('reset');
            $('.alert-msg').show();
            $('#text-msg').val(response);
        });
           
       }
    });
    // Editar un usuario
    $(document).on('click','.user-edit', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('user-id');
        let single = 'single';
        let newrole = '';
        const postData = {
            'id': id,
            'single': single,
        }
        $.post('../../helpers/usuarios.php',postData, function(response){
            
            loadUsers();
            $("#crud-form-edit").show('slow');
            $("#crud-form").hide();
            $("#formButton").hide();
            document.getElementById("resultSearch").className = "col-lg-9 pl-4";
            const user = JSON.parse(response);
            $('#nombre_usuario_edit').val(user.first_name);
            $('#apellido_paterno_edit').val(user.last_name);
            $('#email_usuario_edit').val(user.email_user);
            $('#username_edit').val(user.username);
            // $('#password_edit').val(user.password);
            $('#role_edit').val(user.role);
            $('#user-id').val(user.id_user);
            edit = true;
        });
    });
    //Traer valores de usuario del buscador
    $(document).on('click', '.user-item', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('user-id');
        let single = 'single';
        const postData = {
            'id': id,
            'single': single,
        }
        $.post('../../helpers/usuarios.php',postData, function(response){
            $("#table-data").show('slow');
            loadUsers();
            $("#crud-form-edit").show('slow');
            $("#formButton").hide();
            document.getElementById("resultSearch").className = "col-lg-9 pl-4";
            const user = JSON.parse(response);
            $('#nombre_usuario_edit').val(user.first_name);
            $('#apellido_paterno_edit').val(user.last_name);
            $('#email_usuario_edit').val(user.email_user);
            $('#username_edit').val(user.username);
            $('#password_edit').val(user.password);
            $('#role_edit').val(user.role);
            $('#user-id').val(user.id_user);
            edit = true;
        });
    });
    $(document).on('keyup', '#password_edit', function(){
        if (!$.trim($(this).val()).length || /'/.test($(this).val()) || $(this).val().length < 6) {
          $(this).addClass(' is-invalid');
        } else {
          $(this).removeClass(' is-invalid');
          
        }
    });
});