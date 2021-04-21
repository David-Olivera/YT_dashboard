$(function(){
    let edit = false;
    $('#sidebar, #content').toggleClass('active');
    $('#tour-result').hide();



    // Btn cancelar
    $('#cancelButton').on("click", function(e){
        e.preventDefault();
        $('#exampleModal').modal('hide');
        $('#tourForm').trigger('reset');
    });
    // Btn cancelar edit
    $('#cancelButtonEdit').on("click", function(e){
        e.preventDefault();
        $('#exampleModalEdit').modal('hide');
        $('#tourFormEdit').trigger('reset');
    });

    //Btn x de alert mensaje
    $("#alert-close").click(function(){
        $(".alert-msg").hide('slow');
    });
});