$(function(){
    loadExchangerate();


    function loadExchangerate(){
        let action = 'get_exchange_rate';
        const postData = {
            'action': action
        };
        $.ajax({
            data: postData,
            url: '../../helpers/exchange_rate.php',
            type: 'post',
            success: function(data){
                $('#inp_change_type').val(data);
            }
        });
    }
    $('#btn_change_type').on('input', function () { 
        this.value = this.value.replace(/[^0-9]./g,'');
    });

    $(document).on('click', '#btn_change_type', function(){
        let new_val = $('#inp_change_type').val();
        const postDatas = {
            'value': new_val,
            'action': 'update_exchange'
        };
        $.ajax({
            data: postDatas,
            url: '../../helpers/exchange_rate.php',
            type: 'post',
            beforeSend: function(){
                $('#btn_change_type').prop('disabled', true);
            },
            success: function(data){
                if (data == 1) {
                    $('.alert-msg').show();
                    $('#text-msg').val('El tipo de cambio a sido actualizado correctamente');
                    loadExchangerate();
                    $('#btn_change_type').prop('disabled', false);
                    
                }else{
                    $('.alert-msg').show();
                    $('#text-msg').val('Error al actualiza el tipo de cambio');
                }
            }
        });
    });
});