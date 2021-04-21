$(function(){
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


    
});