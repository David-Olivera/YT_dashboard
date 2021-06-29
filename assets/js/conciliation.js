$(function(){
    $('#sidebar, #content').toggleClass('active');
    $('.alert-msg').hide();
    $('#alert-msg-c').hide();
    var con = 0;
    let search ="";
    loadConciliations(con, search, f_llegada ="", f_salida ="", type_search = 0);

    // DESCARGAR 
    $('#content_filter_agency_c').hide();
    $('#content_filter_date_c').hide();
    // datepicker
    $( function() {        
        var $datepicker2 = $( ".datepicker_end" );
        $('.datepicker_star').datepicker( {
            language: 'es',
            onSelect: function(fecha) {
                $datepicker2.datepicker({   
                    language: 'es',       
                    minDate: new Date(),

                });
                $datepicker2.datepicker("option", "disabled", false);
                $datepicker2.datepicker('setDate', null);
                $datepicker2.datepicker("option", "minDate", fecha); 
            }
        });
        $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '<Ant',
            nextText: 'Sig>',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
            weekHeader: 'Sm',
            dateFormat: 'yy-mm-dd',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
          };
          $.datepicker.setDefaults($.datepicker.regional['es']);
    });
    // datepicker search
    $(function() {        
      var $datepicker2 = $( "#datepicker_end_ser" );
      $('#datepicker_star_ser').datepicker( {
          language: 'es',
          onSelect: function(fecha) {
              $datepicker2.datepicker({
                  language: 'es',       
                  minDate: new Date(),

              });
              $datepicker2.datepicker("option", "disabled", false);
              $datepicker2.datepicker('setDate', null);
              $datepicker2.datepicker("option", "minDate", fecha); 
          }
      });
      $.datepicker.regional['es'] = {
          closeText: 'Cerrar',
          prevText: '<Ant',
          nextText: 'Sig>',
          currentText: 'Hoy',
          monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
          monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
          dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
          dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
          dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
          weekHeader: 'Sm',
          dateFormat: 'yy-mm-dd',
          firstDay: 1,
          isRTL: false,
          showMonthAfterYear: false,
          yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['es']);
    });
    
    $(function() {        
      $('#datepicker_fp').datepicker({});  
    });
    
    // datepicker search
    $(function() {        
      var $datepicker2 = $( "#datepicker_end_ser_con" );
      $('#datepicker_star_ser_con').datepicker( {
          language: 'es',
          onSelect: function(fecha) {
              $datepicker2.datepicker({
                  language: 'es',       
                  minDate: new Date(),

              });
              $datepicker2.datepicker("option", "disabled", false);
              $datepicker2.datepicker('setDate', null);
              $datepicker2.datepicker("option", "minDate", fecha); 
          }
      });
      $.datepicker.regional['es'] = {
          closeText: 'Cerrar',
          prevText: '<Ant',
          nextText: 'Sig>',
          currentText: 'Hoy',
          monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
          monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
          dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
          dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
          dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
          weekHeader: 'Sm',
          dateFormat: 'yy-mm-dd',
          firstDay: 1,
          isRTL: false,
          showMonthAfterYear: false,
          yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['es']);
    });
    
    // // datepicker search
    // $(function() {        
    //   $('#datepicker_fp').datepicker({});  
    // });

    /* CARGA DE DATOS */
    $('#conciliation-tab').click(function(){
      var con = 1;
      let code = "";
      loadConciliations(con, code, f_llegada ="", f_salida ="", type_search = 0);
    });
    $('#noconciliation-tab').click(function(){
        var con = 0;
        let code = "";
        loadConciliations(con, code, f_llegada ="", f_salida ="", type_search = 0);
    });

    function loadConciliations(type,search,f_llegada, f_salida, type_search){
        function loadData(page){
            $.ajax({
                url  : "../../model/conciliaciones_paginacion.php",
                type : "POST",
                cache: false,
                data : {page_no:page,  type: type, search: search, type_search, f_llegada, f_salida},
                beforeSend:function(){
                  let template = '';
                      template += `
                      <div class="row pt-4">
                          <div class="col-lg-4 col-md-3">
                          </div>
                          <div class="col-lg-4 col-md-6">
                              <div class="spinner-grow text-dark" role="status">
                                  <span class="sr-only">Loading...</span>
                              </div>
                              <div class="spinner-grow text-secondary" role="status">
                                  <span class="sr-only">Loading...</span>
                              </div>
                              <div class="spinner-grow text-dark" role="status">
                                  <span class="sr-only">Loading...</span>
                              </div>
                          </div>
                          <div class="col-lg-4 col-md-3">
                          </div>
                      </div>
                          
                      `;
                      if (type == '') {
                          $("#no_conciliation").html(template);
                          
                      }
                      if (type == 0) {
                        $("#no_conciliations").html(template);
                      }   
                      if (type == 1) {
                        $("#yes_conciliations").html(template);
                      } 
                },
                success:function(response){
                  if (type == '') {
                      $("#no_conciliations").html(response);
                      
                  }
                  if (type == 0) {
                    $("#no_conciliations").html(response);
                  }   
                  if (type == 1) {
                      $("#yes_conciliations").html(response);
                  } 
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
    
    function loadDocs(){
      $('#storaged_documents').html('');
      var id_conciliation = $('#inp_id_conciliation').val();
      const postDatas = {
        'id_conciliation': id_conciliation,
        'action': 'load_docs'
      }
      $.ajax({
        url: '../../helpers/conciliations.php',
        type: 'POST',
        data: postDatas,
        success: function(response){
          const docs = JSON.parse(response);
          let template = '';
          if (docs != '') {
            let template = '';
            template += `
                  <table class="table w-100" >
                      <thead>
                          <tr>
                          <th style="width:10%"  scope="col">#</th>
                          <th style="width:45%" scope="col">Nombre</th>
                          <th style="width:15%" scope="col">Factura</th>
                          <th style="width:15%" scope="col">Fecha</th>
                          <th style="width:15%" scope="col">Conciliación Multiple</th>
                          </tr>
                      </thead>
                      <tbody>
            `;
            docs.forEach(docs => {
              let factura = 'No Factura';
              let file = docs.file_document_completed;
              let ext = file.substring(file.lastIndexOf("."));
              if (docs.facture == 1) {
                factura = 'Factura';
              }
              $cm ="N/A";
              if (docs.conci_mul) {
                $cm = docs.conci_mul;
              }
              if (ext == ".pdf") {
                template += `
                            
                            <tr dataia=${id_conciliation} datagency="${docs.id_agency}" datado="${docs.id_concidocs}" datana="${docs.file_document_completed}">
                            <td><a href='../../../es/assets/docs/conciliaciones/${docs.file_document_completed}'  target="_blank" title='${docs.file_document_completed}' data='' class='edit_img' id='add_img'><img src='../../assets/img/icon/icon_pdf.png' class='img-thumbnail w-100'></a></td>
                            <td><a href="../../../es/assets/docs/conciliaciones/${docs.file_document_completed}"  target="_blank" title='${docs.file_document_completed}'><small>${docs.file_document}...</small></a></td>
                            <td><small>${factura}</small></td>
                            <td><small>${docs.register_date}</small></td>
                            <td><small>${$cm}</small></td>
                            <td><a href="#" id="btn-delete-doc" class="btn btn-danger btn-sm btn-block">Eliminar</a></td>
                            </tr>
                            <tr>
                `;
              }
              if (ext == ".png" || ext == ".jpg" || ext == '.jpeg') {
                template += `
                            
                            <tr dataia=${id_conciliation} datagency="${docs.id_agency}" datado="${docs.id_concidocs}" datana="${docs.file_document_completed}">
                            <td><a href='../../../es/assets/docs/conciliaciones/${docs.file_document_completed}'  target="_blank" title='${docs.file_document_completed}' data='' class='edit_img' id='add_img'><img src='../../assets/img/icon/icon_imge.png' class='img-thumbnail w-100'></a></td>
                            <td><a href="../../../es/assets/docs/conciliaciones/${docs.file_document_completed}"  target="_blank" title='${docs.file_document_completed}'><small>${docs.file_document}...</small></a></td>
                            <td><small>${factura}</small></td>
                            <td><small>${docs.register_date}</small></td>
                            <td><small>${$cm}</small></td>
                            <td><a href="#" id="btn-delete-doc" class="btn btn-danger btn-sm btn-block">Eliminar</a></td>
                            </tr>
                            <tr>
                `;  
              }

            });
            template += `
                      </tbody>
                    </table>
            `;
            $('#storaged_documents').html(template);
          }else{
            template += `
              <p><small>No tiene archivos registrados.</small></p>
            `;
            $('#storaged_documents').html(template);

          }
        }

      });
    }
    //!! CU !!
    // VALIDAR SI HA ESCOGIDO ALGUN ARCHIVO CU
    $("#files_conciliation").change(function(){
      $("#btn_add_file").prop("disabled", this.files.length == 0);
    });
    //BTN AGREGAR ARCHIVO A LA BD CU
    $(document).on('click', '#btn_add_file', function(e){
      e.preventDefault();
      var myfiles = document.getElementById("files_conciliation");
      var files = myfiles.files;
      var data = new FormData();
      var id_reservation = $('#inp_id_reservation').val();
      var id_conciliation = $('#inp_id_conciliation').val();
      var type_conciliation = $('#inp_type_conciliation').val();
      var id_agency = $('#inp_agency').val();
      var code = $('#inp_code_conciliation').val();
      let checked = 0;
      var seleccion = $("#check_facture")[0].checked;
      if(seleccion){
          checked = 1;
      }
      for (i = 0; i < files.length; i++) {
          data.append('file' + i, files[i]);
      }
      data.append('id_reservation', id_reservation);
      data.append('id_conciliation', id_conciliation);
      data.append('facture', checked);
      data.append('id_agency', id_agency);
      data.append('code_invoice', code);
      data.append('action', 'upload_docs');
      $.ajax({
        url: '../../helpers/conciliations.php',
        type: 'POST',
        contentType: false,
            data: data,
            processData: false,
            cache: false,
            beforeSend: function(){
              $('#btn_add_file').prop('disabled', true);    
              $('#btn_add_file').html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
            },
            success: function(res) {
              $('#files_conciliation').val('');
              $("#check_facture").prop('checked', false);
              $("#loadedfiles").append(res);
              console.log(res);
              loadDocs();
              $("#btn_add_file").prop("disabled", true);
              setTimeout(function(){ $("#loadedfiles").html(''); }, 4000);
              $('#btn_add_file').html('Agregar archivo');
            }
      });
    });
    //BTN CANCELAR CONCILIACION UNICA CU
    $(document).on('click', '.btn_close_conci', function(){
      $('#files_conciliation').val('');
      $('#inp_id_conciliation').val('');
      $('#inp_code_conciliation').val('');
      $('#upload_conliation').modal('hide');
      $("#check_facture").prop('checked', false);
      $("#loadedfiles").html('');
      $('#storaged_documents').html('');
      $("#btn_add_file").prop("disabled", true);

    });
    //BTN PARA ELEGIR ARCHIVO CONCI UNICA
    $(document).on('click', '#btn_upload_file', function(){
      $('#storaged_documents').html('');
      let element = $(this)[0];
      var id_agency = $(element).attr('agency');
      let id = $(element).attr('reserva');
      let conciliation = $(element).attr('conciliation');
      let code = $(element).attr('code');
      let type_conciliation = $(element).attr('type_conci');
      $('#inp_id_reservation').val(id);
      $('#inp_id_conciliation').val(conciliation);
      $('#inp_code_conciliation').val(code);
      $('#inp_agency').val(id_agency);
      $('#inp_type_conciliation').val(type_conciliation);
      $('#label_conci_code').text('Reservacíon '+ code);
      loadDocs();
    });
    //ELIMINAR ARCHIVO GENERAL
    $(document).on('click', '#btn-delete-doc', function(e){
      e.preventDefault();
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('datado');
        let id_a = $(element).attr('datagency');
        let name = $(element).attr('datana');
        var type_conciliation = $('#inp_type_conciliation').val();
        let delet = 'delete_doc';
        const postData = {
            'id': id,
            'id_agency': id_a,
            'name_doc': name,
            'action': delet,
        }
        console.log(postData);
        $.post('../../helpers/conciliations.php', postData, function(response){

          loadDocs(id_a);
          $("#loadedfiles").html('');
          $("#loadedfiles").append(response);
          setTimeout(function(){ $("#loadedfiles").html(''); }, 2000);


        });
    });

    // SEARCH NO CON
    $(document).on('click','#search_code_invoice', function(){
      let code_invoice = $('#inp_code_invoice').val();
      var con = 0;
      var type_search = 1;
      if (code_invoice == null || code_invoice.length == 0 || /^\s+$/.test(code_invoice)) {
        $('#inp_code_invoice').addClass(" is-invalid");
        $('#inp_code_invoice').focus();
        return false;
      }
      let checked = 0;
          var seleccion = $("#code_multiple")[0].checked;
          if(seleccion){
              checked = 1;
              type_search = 4;
      }
      loadConciliations(con, code_invoice, f_llegada="", f_salida ="", type_search);
      $("#code_multiple").prop('checked', false);
    });
    $(document).on('click','#search_name_client', function(){
      let name_client = $('#inp_name_client').val();
      var con = 0;
      var type_search = 2;
      if (name_client == null || name_client.length == 0 || /^\s+$/.test(name_client)) {
        $('#inp_name_client').addClass(" is-invalid");
        $('#inp_name_client').focus();
        return false;
      }
      loadConciliations(con, name_client, f_llegada="", f_salida ="", type_search);
    });
    $(document).on('click','#search_date_agency', function(){
      let name_agency = $('#name_agency').val();
      let llegada = $('#datepicker_star_ser').val();
      let salida = $('#datepicker_end_ser').val();
      var con = 0;
      var type_search = 3;
      if (llegada == null || llegada.length == 0 || /^\s+$/.test(llegada)) {
        $('#datepicker_star_ser').addClass(" is-invalid");
        $('#datepicker_star_ser').focus();
        return false;
      }
      if (salida == null || salida.length == 0 || /^\s+$/.test(salida)) {
          $('#datepicker_end_ser').addClass(" is-invalid");
          $('#datepicker_end_ser').focus();
          return false;
      }  
      if (name_agency == null || name_agency.length == 0 || /^\s+$/.test(name_agency)) {
        $('#name_agency').addClass(" is-invalid");
        $('#name_agency').focus();
        return false;
      } 
      loadConciliations(con, name_agency, llegada, salida, type_search);
    });
    $(document).on('click','#search_code_multiple', function(){
      let code_multiple = $('#inp_code_multiple').val();
      var con = 0;
      var type_search = 4;
      if (code_multiple == null || code_multiple.length == 0 || /^\s+$/.test(code_multiple)) {
        $('#name_agency').addClass(" is-invalid");
        $('#name_agency').focus();
        return false;
      } 
      loadConciliations(con, code_multiple,  f_llegada="", f_salida ="", type_search);
    });
    $(document).on('click', '#view_all_conciliations', function(){
      var con = 0;
      let search ="";
      loadConciliations(con, search, f_llegada ="", f_salida ="", type_search = 0);
      $('#inp_code_invoice').val('');
      $('#inp_name_client').val('');
      $('#datepicker_star_ser').val('');
      $('#datepicker_end_ser').val('');
      $('#name_agency').val('');
      $('#inp_code_multiple').val('');

    });

    // SEARCH CON
    $(document).on('click','#search_code_invoice_con', function(){
      let code_invoice = $('#inp_code_invoice_con').val();
      var con = 1;
      var type_search = 1;
      if (code_invoice == null || code_invoice.length == 0 || /^\s+$/.test(code_invoice)) {
        $('#inp_code_invoice_con').addClass(" is-invalid");
        $('#inp_code_invoice_con').focus();
        return false;
      }
      let checked = 0;
          var seleccion = $("#code_multiple_con")[0].checked;
          if(seleccion){
              checked = 1;
              type_search = 4;
      }
      loadConciliations(con, code_invoice, f_llegada="", f_salida ="", type_search);
      $("#code_multiple_con").prop('checked', false);

    });
    $(document).on('click','#search_name_client_con', function(){
      let name_client = $('#inp_name_client_con').val();
      var con = 1;
      var type_search = 2;
      if (name_client == null || name_client.length == 0 || /^\s+$/.test(name_client)) {
        $('#inp_name_client_con').addClass(" is-invalid");
        $('#inp_name_client_con').focus();
        return false;
      }
      loadConciliations(con, name_client, f_llegada="", f_salida ="", type_search);
    });
    $(document).on('click','#search_date_agency_con', function(){
      let name_agency = $('#name_agency_con').val();
      let llegada = $('#datepicker_star_ser_con').val();
      let salida = $('#datepicker_end_ser_con').val();
      var con = 1;
      var type_search = 3;
      if (llegada == null || llegada.length == 0 || /^\s+$/.test(llegada)) {
        $('#datepicker_star_ser_con').addClass(" is-invalid");
        $('#datepicker_star_ser_con').focus();
        return false;
      }
      if (salida == null || salida.length == 0 || /^\s+$/.test(salida)) {
          $('#datepicker_end_ser_con').addClass(" is-invalid");
          $('#datepicker_end_ser_con').focus();
          return false;
      }  
      if (name_agency == null || name_agency.length == 0 || /^\s+$/.test(name_agency)) {
        $('#name_agency_con').addClass(" is-invalid");
        $('#name_agency_con').focus();
        return false;
      } 
      loadConciliations(con, name_agency, llegada, salida, type_search);
    });
    $(document).on('click', '#view_all_conciliations_con', function(){
      var con = 1;
      let search ="";
      loadConciliations(con, search, f_llegada ="", f_salida ="", type_search = 0);
      $('#inp_code_invoice_con').val('');
      $('#inp_name_client_con').val('');
      $('#datepicker_star_ser_con').val('');
      $('#datepicker_end_ser_con').val('');
      $('#name_agency_con').val('');
      $('#inp_code_multiple_con').val('');

    });
    /* ACCIONES */
    $(document).on('click', '#btn_add_deposit', function(){
      let element = $(this)[0];
      let id = $(element).attr('reserva');
      let total_cost = $(element).attr('total-cost');
      let currency = $(element).attr('currency');
      $('#inp_reserva').val(id);
      $('#inp_total_cost').val(total_cost);
      $('#inp_currency').val(currency);
    });

    $(document).on('click', '#btnSetCharge', function(){
        let charge = $('#inp_charge').val();
        let f_pago = $('#datepicker_fp').val();
        let concepto = $('#inp_concept').val();
        let id = $('#inp_reserva').val();
        let total_cost = $('#inp_total_cost').val();
        let id_user = $('#inp_user').val();
        let currency = $('#inp_currency').val();

        if (charge == null || charge.length == 0 || /^\s+$/.test(charge)) {
          $('#inp_charge').addClass(" is-invalid");
          $('#inp_charge').focus();
          return false;
        }
        if (f_pago == null || f_pago.length == 0 || /^\s+$/.test(f_pago)) {
          $('#datepicker_fp').addClass(" is-invalid");
          $('#datepicker_fp').focus();
          return false;
        }
        if (concepto == null || concepto.length == 0 || /^\s+$/.test(concepto)) {
          $('#inp_concept').addClass(" is-invalid");
          $('#inp_concept').focus();
          return false;
        }
        const postData = {
          'id': id,
          'id_user': id_user,
          'total_cost': total_cost,
          'charge': charge,
          'f_pago': f_pago,
          'concepto': concepto,
          'currency': currency,
          'action': 'addPay'
        };
        $.ajax({
          data: postData,
          url: '../../helpers/conciliations.php',
          type: 'POST',
          beforeSend: function(){
            $('#btnSetCharge').prop('disabled', true);
          },
          success: function(res){
            if (res == 1) {
            $('#addPayModal').modal('hide');
            $('.alert-msg').show();
            $('#text-msg').val('Pago registrado correctamente');
            loadConciliations(con=0, search, f_llegada ="", f_salida ="", type_search = 0);
            $("html, body").animate({scrollTop: 0}, 1000);
            }else{
              $('#addPayModal').modal('hide');
              $('.alert-msg').show();
              $('#text-msg').val('Error al registrar el pago');

            }
            
          }
        });
    });


    $(document).on('click', '#close_modal_d', function(){
      $('#frmSetCharge').trigger('reset');
    });
    // Change status reservation
    $(document).on('change', '#new_status_reservation', function(){
      let stts = $(this).val();
      let element = $(this)[0];
      let text = $(this).find('option:selected').text();
      let id = $(element).attr('data');
      let total_cost = $(element).attr('total-cost');
      let currency = $(element).attr('currency');
      let transfer = $(element).attr('transfer');
      let code = $(element).attr('code');
      let id_user = $('#inp_user').val();
      if (text == 'COURTESY') {
        confirm("¿Esta seguro de dar esta reservación en cortesia?");
      }
      if ((text == 'CANCELLED') && (transfer == 'REDHH' || transfer == 'RED')) {
          $('#cancelationModal').modal('show');
          $('#content_type_cs').hide();
          $('#inp_selected').val(text);
          $('#inp_reservation').val(id);
          $('#inp_code').val(code);
          $('#inp_transfer').val(transfer);
    return false;
      }
      var payment = {
          'id': id,
          'value': stts,
          'transfer': transfer,
          'text': text,
          'total_cost': total_cost,
          'currency': currency,
          'user': id_user,
          'code': code,
          'setstatusmet': 1
      };
      $.ajax({
          data: payment,
          url: '../../helpers/conciliations.php',
          type: 'post',					
          beforeSend: function(){
          },
          success: function(data){
              $('html, body').animate({scrollTop: 0}, 600);
              $('.alert-msg').show();
              $('#text-msg').val(data);
              loadConciliations(con=0, search, f_llegada ="", f_salida ="", type_search = 0);
          }
      });
    });
    $(document).on('click', '#cancelButtonCancelation', function(){
        $('#cancelationModal').modal('hide');
        $('#content_type_cs').hide();
        $('#formCancelation').trigger('reset');
    });
    $(document).on('change', '#type_cancelation', function(){
        let val = $(this).val();
        if (val == 'partial') {
            $('#content_type_cs').show('fade');
        }
        if (val == 'full') {
            $('#content_type_cs').hide('fade');
        }

    });
    $(document).on('click', '#btn_form_cancelation', function(){
        let text = $('#type_cancelation').val();
        let id = $('#inp_reservation').val();
        let code = $('#inp_code').val();
        let select = $('#inp_selected').val();
        let ty_cs = "";
        let user = $('#inp_user').val();
        let transfer = $('#inp_transfer').val();
        if (text == 'partial') {
            ty_cs = $('#type_cancelation_service').val();
        }
        var payment = {
            'id': id,
            'value': select,
            'transfer': transfer,
            'text': text,
            'code': code,
            'ty_cs': ty_cs,
            'user': user,
            'setstatusmet': 1
        };        
        $.ajax({
            data: payment,
            url: '../../helpers/reservaciones.php',
            type: 'post',					
            beforeSend: function(){
            },
            success: function(data){
                $('html, body').animate({scrollTop: 0}, 600);
                $('.alert-msg').show();
                $('#text-msg').val(data);
                $('#cancelationModal').modal('hide');
                $('#content_type_cs').hide();
                $('#formCancelation').trigger('reset');
                //loadReservations(tab ="", type_search = "",data_search = "", f_llegada = "", f_salida = "");
            }

        });
    });
    //Removemos class al cambiar de Paso 2
    $(document).on('keyup', 'input', function(){
      if ($.trim($(this).val()).length) {
          $(this).removeClass(' is-invalid');
      } else {
          $(this).addClass(' is-invalid');
      }
    });
    //Removemos class al cambiar de Paso 2
    $(document).on('focusout', 'input', function(){
          $(this).removeClass(' is-invalid');
    });
    //Removemos class de Date 1
    $(document).on('click', '#datepicker_star_ser', function(){
      $(this).removeClass(' is-invalid');
    });
    //Removemos class de Date 2
    $(document).on('click', '#datepicker_end_ser', function(){
      $(this).removeClass(' is-invalid');
    });

    
    // BTN DESCARGAR EXCEL
    $(document).on('click', '#checkConciliations', function(){
      var val = $(this).is(':checked') ? 1 : 0;
      if (val == 1) {
          $('#content_filter_conciliations').show('slide');
      }else if(val == 0){
          $('#content_filter_conciliations').hide('slide');
      }
    });
    $(document).on('click', '#checkFechaServicio_c', function(){
      var val = $(this).is(':checked') ? 1 : 0;
      if (val == 1) {
          $('#content_filter_date_c').show('slide');
      }else if(val == 0){
          $('#content_filter_date_c').hide('slide');
      }
    });
    $(document).on('click', '#checkAgencia_c', function(){
          var val = $(this).is(':checked') ? 1 : 0;
          if (val == 1) {
              $('#content_filter_agency_c').show('slide');
          }else if(val == 0){
              $('#content_filter_agency_c').hide('slide');
          }
    });
    $(document).on('click', '#btn_dowload_report_c', function(){
          var seleccion_date = $("#checkFechaServicio_c")[0].checked;
          var seleccion_agency = $("#checkAgencia_c")[0].checked;
          var seleccion_conci = $("#checkConciliations")[0].checked;

          const postDatas = {
              'f_date_a': $('#datepicker_star_download_c').val(),
              'f_date_s': $('#datepicker_end_download_c').val(),
              'type_conciliation': $('#inp_type_conciliation').val(),
              'name_agency': $('#inp_acency_c').val(),
              'action': 'download_report_c'
          };
          if(!seleccion_date && !seleccion_agency && !seleccion_conci){
              $('#alert-msg-c').addClass(' alert-danger');
              $('#alert-msg-c').show();
              $('#text-msg-c').val('Selecciona al menos un filtro ');
              setTimeout(function(){ $('#alert-msg-c').hide('slow'); }, 3000);
              return false;
          }
          if (seleccion_date) {
              if (postDatas.f_date_a == null || postDatas.f_date_a.length == 0 || /^\s+$/.test(postDatas.f_date_a)) {
                  $('#datepicker_star_download_c').addClass(" is-invalid");
                  $('#datepicker_star_download_c').focus();
                  return false;
              }
              if (postDatas.f_date_s == null || postDatas.f_date_s.length == 0 || /^\s+$/.test(postDatas.f_date_s)) {
                  $('#datepicker_end_download_c').addClass(" is-invalid");
                  $('#datepicker_end_download_c').focus();
                  return false;
              }
          }
          if (seleccion_agency) {
              if (postDatas.name_agency == null || postDatas.name_agency.length == 0 || /^\s+$/.test(postDatas.name_agency)) {
                  $('#inp_acency_c').addClass(" is-invalid");
                  $('#inp_acency_c').focus();
                  return false;
              }
          }
          if (seleccion_conci) {
            if (postDatas.type_conciliation == null || postDatas.type_conciliation.length == 0 || /^\s+$/.test(postDatas.type_conciliation)) {
                $('#inp_type_conciliation').addClass(" is-invalid");
                $('#inp_type_conciliation').focus();
                return false;
            }
          }
          // console.log('Todo los datos: '+postDatas.f_date_a+' - '+postDatas.f_date_s+' - '+postDatas.name_agency+' - '+postDatas.name_zone+' - '+postDatas.name_type_service);
          $.ajax({
              data: postDatas,
              url:'../../helpers/reports.php',
              type:'POST',
              beforeSend: function(){
                  $('.formDownloadReport_c :input').prop('disabled', true);
                  $('#btn_dowload_report_c').prop('disabled', true);    
                  $('#btn_dowload_report_c').html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>'); 
              },
              success: function(res){ 
                  var d = new Date();
                  var today = d.getFullYear() + '-' + ('0'+(d.getMonth()+1)).slice(-2) + '-' + ('0'+d.getDate()).slice(-2);
                  var time = d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
                  let fileName = "Conciliaciones "+today+" "+time+ " Hrs";
                  var element = document.createElement('a');
                  element.setAttribute('href', 'data:application/vnd.ms-Excel,' + encodeURIComponent(res));
                  element.setAttribute('download', fileName);
                  element.style.display = 'none';
                  document.body.appendChild(element);
                  element.click();
                  document.body.removeChild(element);
                  setTimeout(function(){ $('.btn_close_dowload_c').click(); }, 1000);
                  
              }
          });
    });
    $(document).on('click', '.btn_close_dowload_c', function(){
          $('.formDownloadReport_c').trigger('reset');
          $("#checkConciliations").prop('checked', true);
          $("#checkFechaServicio_c").prop('checked', false);
          $("#checkAgencia_c").prop('checked', false);
          $('#content_filter_conciliations').show();
          $('#content_filter_agency_c').hide();
          $('#content_filter_date_c').hide();
          $('.formDownloadReport_c :input').prop('disabled', false);
          $('#btn_dowload_report_c').prop('disabled', false);
          $('#btn_dowload_report_c').html('<span>Descargar</span>'); 

    });
});