$(function(){
    $('.btn_load').hide();
    $(document).ready(function(){
        setTimeout(function(){ $(".loader").fadeOut("slow"); }, 300);
        
    });

    $('#btn_login').on('click',function(e){
        e.preventDefault();
        var login = {
            'username': $('#email').val(),
            'password': $('#password').val()
        };
        if (login.username == null || login.username.length == 0 || /^\s+$/.test(login.username)) {
            $('#email').addClass(' is-invalid');
            return false;
        }
        if (login.password == null || login.password.length == 0 || /^\s+$/.test(login.password)) {
            $('#password').addClass(' is-invalid');
            return false;
        }
        $.ajax({
            data: login,
            url: 'helpers/login.php',
            type: 'POST',
            beforeSend: function(){
                $("#email").prop('disabled', true);
                $("#password").prop('disabled', true);
                $('#btn_login').prop('disabled', true);    
                $('#btn_login').html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
            },
            success: function(data){
                const res = JSON.parse(data);
                console.log(res.status);
                console.log(res.msg);
                if (res.status == 0) {
                    $("#email").prop('disabled', false);
                    $("#password").prop('disabled', false);
                    $('#btn_login').prop('disabled', false);    
                    $('#btn_login').html('Ingresar');
                    $('#error_msg').html(res.msg);
                }
                if (res.status == 1) {
					window.location.href = 'sections/admin/index.php';
                    console.log(window.location.href = 'sections/admin/index.php');
                }
            },
        });
    });
    //Removemos class al cambiar de Paso 2
    $(document).on('change', '#login :input', function(){
      if ($.trim($(this).val()).length) {
          $(this).removeClass(' is-invalid');
      } else {
          $(this).addClass(' is-invalid');
      }
    });

});