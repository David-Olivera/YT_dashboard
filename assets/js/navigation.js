$(function(){
    loadCountMsj();
    loadCountActivities();
    setTimeout(function(){ 
        loadCountMsj();
        loadCountActivities(); 
    }, 30000);
    $('.alert-msg').hide();
    $('#sidebar, #content').toggleClass('active');
    $(document).ready(function () {
        $('#sidebar, #content').toggleClass('active');
        $("#sidebar").mCustomScrollbar({
            theme: "minimal"
        });
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar, #content').toggleClass('active');
            $('.collapse.in').toggleClass('in');
            $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        });
    });
        
    // Btn cerrar busqueda
    $(document).on('click', '#cerrar_results', function(){
        $('#result-search').hide('slow');
        $('#result-search-ag').hide('slow');
        $('#result-search-tu').hide('slow');
        $('#search').val('');
        $('#search-ag').val('');
        $('#search-tu').val('');
        $("#table-data").show('slow');
        $("#table-data-pu").show('slow');
        $("#table-data-ag").show('slow');
        $("#table-data-tu").show('slow');
        $("#crud-form-edit").hide('slow');
        document.getElementById("resultSearch").className = "col-lg-12";
    });


    
    //Btn x de alert mensaje
    $("#alert-close").click(function(){
        $(".alert-msg").hide('slow');
    });

    //Btn x de alert mensaje
    $(".alert-close").click(function(){
        $(".alert-msg").hide('slow');
        $(".alert-msg-ag").hide('slow');
        $(".alert-msg-tu").hide('slow');
    });

    
    //MOSTRAR MENSAJES BITACORA
    $(document).on('click', '#btn_view_notifications', function(){
        let type = 'load_msj_bitacora';
        loadMsjs(type);
        $("#notification-latest").show();
    });
    //MOSTRAR MENSAJES ACTIVIDADES
    $(document).on('click', '#btn_view_notifications_activity', function(){
            let type = 'load_msj_activity';
            loadMsjs(type);
            $("#notification-latest").show();
    });
    //LOAD MSJS
    function loadMsjs(type){
        const postDatas = {
            'id': $('#inp_user').val(),
            'type': type,
            'action': 'get_all_msjs'
        };
        $.ajax({
            data: postDatas,
            url: '../../model/notificaciones.php',
            type: 'POST',
            cache: false,
            beforeSend: function(){
                let template = '';
                template += `
                <div class="p-2" id="content_load_notify">
                    <div class="row p-2" id="load_notify">
                        
                        <div class="spinner-grow text-dark p-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="spinner-grow text-secondary p-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="spinner-grow text-dark p-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    
                </div>
                    
                `;
                $("#notification-latest").html(template);
            },
            success: function(res){
                var json = $.parseJSON(res);
                $('#icon_notify').removeClass(' notify_news');
                $('#icon_notify_activity').removeClass(' notify_news');
                    if (json.status == 1) {
                        $("#notification-latest").html(json.msj);
                        loadCountMsj();
                        loadCountActivities();
                    }else{
                        
                        $("#notification-latest").html(json.msj);
                        loadCountMsj();
                        loadCountActivities();
                    }

            }
        });
    }
    function loadCountMsj(){
        const postDatas = {
            'id': $('#inp_user').val(),
            'action': 'get_count_msjs'
        };
        $.ajax({
            data: postDatas,
            url: '../../helpers/reservaciones.php',
            type: 'POST',
            cache: false,
            beforeSend: function(){
                let template = '';
                template += ` - `;
                $("#num_notify").html(template);
            },
            success: function(res){
                
                if (res > 0) {
                    $('#icon_notify').addClass(' notify_news');
                    $("#num_notify").html(res);
                }else{
                    
                    $("#num_notify").html(res);
                }
                

            }
        });
    }
    function loadCountActivities(){
        const postDatas = {
            'id': $('#inp_user').val(),
            'action': 'get_count_acts'
        };
        $.ajax({
            data: postDatas,
            url: '../../helpers/reservaciones.php',
            type: 'POST',
            cache: false,
            beforeSend: function(){
                let template = '';
                template += ` - `;
                $("#num_notify_activity").html(template);
            },
            success: function(res){
                
                if (res > 0) {
                    if (res > 99) {
                        $('#icon_notify_activity').addClass(' notify_news');
                        $("#num_notify_activity").html('+99');
                    }else{
                        $('#icon_notify_activity').addClass(' notify_news');
                        $("#num_notify_activity").html(res);

                    }
                }else{
                    
                    $("#num_notify_activity").html(res);
                }
                

            }
        });
    }
    // $('#btn_view_notifications').on('focusout', function(){
    //     $("#notification-latest").html('');
    // });
    
});