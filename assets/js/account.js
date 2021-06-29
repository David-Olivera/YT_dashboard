$(function(){
    loadDetailAccount();
    $('#sidebar, #content').toggleClass('active');
    //Get data account details.
    
    function loadDetailAccount(){
        $('#alert_msg').hide();
        let id = $('#inp_user').val();
        const postDatas = {
            'id': id,
            'action': 'get_data_account'
        }
        $.ajax({
           data: postDatas,
           url: '../../helpers/cuenta.php',
           type: "POST",
           success: function(data){
                const res = JSON.parse(data);
                $('#inp_id_user').val(res.id_user)
                $('#inp_name_user').val(res.first_name);
                $('#inp_last_user').val(res.last_name);
                $('#inp_email_user').val(res.email_user);
                $('#inp_phone_user').val(res.phone_user);
                
                $('#inp_username_user').val(res.username);        

           } 
        });
    }
    // Actualizar data account
    $(document).on('click', '#saveButtonData', function(e){
        e.preventDefault();
        const postDatas = {
            id: $('#inp_id_user').val(),
            name_user: $('#inp_name_user').val(),
            last_user: $('#inp_last_user').val(),
            email_user: $('#inp_email_user').val(),
            phone_user: $('#inp_phone_user').val(),
            action: 'update_data'
        };
        if (postDatas.name_user == null || postDatas.name_user.length == 0 || /^\s+$/.test(postDatas.name_user) || /'/.test(postDatas.name_user)) {
            $('#inp_name_user').addClass(" is-invalid");
            $('#inp_name_user').focus();
            return false;
        }
        
        if (postDatas.last_user == null || postDatas.last_user.length == 0 || /^\s+$/.test(postDatas.last_user)  || /'/.test(postDatas.last_user)) {
            $('#inp_last_user').addClass(" is-invalid");
            $('#inp_last_user').focus();
            return false;
        }
        if (!(/\w+([-+.']\w+)*@\w+([-.]\w+)/.test(postDatas.email_user))) {
            $('#inp_email_user').addClass(" is-invalid");
            $('#inp_email_user').focus();
			return false;  
        }
        $.post('../../helpers/cuenta.php', postDatas, function(res){
            if(res == 1){
                $('#alert_msg_account').show('slow');
                loadDetailAccount();
                $('.alert-msg').addClass(' alert-info');
                $('.alert-msg').show();
                $('#text-msg').val('La datos del usuario han sido actualizados correctamente.');
                setTimeout(function(){ $('.alert-msg').hide('slow'); }, 1500);
            }else{
                $('.alert-msg').addClass(' alert-danger');
                $('.alert-msg').show();
                $('#text-msg').val('Error al intentar editar la información del usuario, intentelo más tarde.');
                setTimeout(function(){ $('.alert-msg').hide('slow'); }, 1500);

            }
        });
    });

    // Actualizar credentials
    $(document).on('click', '#saveButtonCreden', function(e){
        e.preventDefault();
        let checked = 0;
        var seleccion = $("#checked_pass_user")[0].checked;

        const postDatas = {
            id: $('#inp_id_user').val(),
            username: $('#inp_username_user').val(),
            password: $('#inp_password_user').val(),
            status: seleccion,
            action: 'update_credentials'
        };
        if ($('#inp_username_user').val() == null || $('#inp_username_user').val().length == 0 || /^\s+$/.test($('#inp_username_user').val()) || /'/.test($('#inp_username_user').val())) {
            $('#inp_username_user').addClass(" is-invalid");
            $('#inp_username_user').focus();
            return false;
        }
        if(seleccion){
            checked = 1;
            if ((postDatas.password == null || postDatas.password.length == 0 || postDatas.password.length < 6 || /^\s+$/.test(postDatas.password) || /'/.test(postDatas.password))) {
                $('#inp_password_user').addClass(" is-invalid");
                $('#inp_password_user').focus();
                return false;
            }
        }
        console.log(postDatas.username);
        console.log(postDatas.password);
        console.log(seleccion);
        $.post('../../helpers/cuenta.php', postDatas, function(res){
            console.log(res);
            if(res == 1){
                loadDetailAccount();
                $('.alert-msg').addClass(' alert-info');
                $('.alert-msg').show();
                $('#text-msg').val(' Las credenciales han sido actualizados correctamente, debera iniciar sesion nuevamente.');
                setTimeout(function(){ $('.alert-msg').hide('slow'); }, 1500);
                setTimeout(function(){ window.location.href = 'http://localhost:8080/yameviTravel/helpers/logout.php'; }, 2500);
                
            }else{
                $('.alert-msg').addClass(' alert-danger');
                $('.alert-msg').show();
                $('#text-msg').val('Error al intentar editar las credenciales, intentelo más tarde.');
                setTimeout(function(){ $('.alert-msg').hide('slow'); }, 1500);

            }
        });
    });    
    
    $(document).on('keyup', '#inp_password_user', function(){
        if (!$.trim($(this).val()).length || /'/.test($(this).val()) || $(this).val().length < 6) {
          $(this).addClass(' is-invalid');
        } else {
          $(this).removeClass(' is-invalid');
          
        }
      });
    $(document).on('keyup', '#agencyDataForm :input', function(){
      if (!$.trim($(this).val()).length || /'/.test($(this).val())) {
        $(this).addClass(' is-invalid');
      } else {
        $(this).removeClass(' is-invalid');
        
      }
    });
    
});