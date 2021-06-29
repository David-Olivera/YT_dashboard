$(function(){
    $('#sidebar, #content').toggleClass('active');
    let nav = "yt-es";
    loadLettters(nav);
    
    //Btn x de alert mensaje
    $("#alert-close").click(function(){
        $(".alert-msg").hide('slow');
    });

    
    $('#nav-es-tab').click(function(){
        let nav = "yt-es";
        loadLettters(nav);
    });
    $('#nav-en-tab').click(function(){
        let nav = "yt-en";
        loadLettters(nav);
    });
    $('#nav-po-tab').click(function(){
        let nav = "yt-po";
        loadLettters(nav);
    });
    $('#pills-tu-tab').click(function(){
        let nav = "tu-es";
        loadLettters(nav);
    });
    $('#nav-es-tu-tab').click(function(){
        let nav = "tu-es";
        loadLettters(nav);
    });
    $('#nav-en-tu-tab').click(function(){
        let nav = "tu-en";
        loadLettters(nav);
    });
    $('#nav-po-tu-tab').click(function(){
        let nav = "tu-po";
        loadLettters(nav);
    });
    function loadLettters(nav){
        var postDatas = {
            'nav': nav,
            'letter': true
        };
        $.ajax({
            url: "../../helpers/cartas.php",
            type: "post",
            cache: false,
            data: postDatas,
            beforeSend: function(){
                let template = '';
                template += `
                <div class="row mt-3">
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

                switch (nav) {
                    case 'yt-es':
                        $('#nav-es').html(template);
                        break;
                    case 'yt-en':
                        $('#nav-en').html(template);
                        break;
                    case 'yt-po':
                        $('#nav-po').html(template);
                        break;
                    case 'tu-es':
                        $('#nav-es-tu').html(template);
                        break;
                    case 'tu-en':
                        $('#nav-en-tu').html(template);
                        break;
                    case 'tu-po':
                        $('#nav-po-tu').html(template);
                        break;
                    default:
                        $('#nav-es').html(template);
                        break;
                }
            },
            success: function(res){
                var nav_res = JSON.parse(res);
                let lang = "";
                if (nav == 'yt-es' || nav == "tu-es") {
                    lang = "Carta en Español";
                }
                if (nav == 'yt-en' || nav == "tu-en") {
                    lang = "Carta en Ingles";
                }
                if (nav == 'yt-po' || nav == "tu-po") {
                    lang = "Carta en Portugues";
                }
                if (nav == "yt-es") {
                    let template = "";
                    template += `
                        <form name="letters_yt_es" id="letters_yt_es" action="" method="post" >
                            <fieldset>
                                <label id="title_letter_es">${lang}</label>
                                <fieldset class="pb-2">
                                    <input type="submit" class="btn btn-success btn_letter_save" name="btnEditLetter" id="btnEditLetter_ytes"  value="Guardar">
                                </fieldset>
                                <textarea rows="1" name="esLetter" class="letterEditor" id="letter_editor_es">${nav_res.footer}</textarea>
                                <input type="hidden" name="esLetterID" id="id_letter_es" value="${nav_res.id_letter}">
                                <input type="hidden" name="taLetterID" id="ta_letter_es" value="letter">
                                <input type="hidden" name="esytLetterTy" id="ty_letter_es" value="yt-es">
                            </fieldset>
                        </form>
                    `;
                    $('#nav-es').html(template);
                    ClassicEditor.create(document.getElementById('letter_editor_es'));
                }
                if (nav == "yt-en") {
                    let template = "";
                    template += `
                        <form name="letters_yt_en" id="letters_yt_en" action="" method="post" >
                            <fieldset>
                                <label id="title_letter_en">${lang}</label>
                                <fieldset class="pb-2">
                                    <input type="submit" class="btn btn-success btn_letter_save" name="btnEditLetter" id="btnEditLetter_yten" value="Guardar">
                                </fieldset>
                                <textarea rows="1" name="enytLetter" class="letterEditor" id="letter_editor_en">${nav_res.footer}</textarea>
                                <input type="hidden" name="enytLetterID" id="id_letter_en" value="${nav_res.id_letter}">
                                <input type="hidden" name="enytLetterTa" id="id_letter_en" value="letter">
                                <input type="hidden" name="enytLetterTy" id="ty_letter_en" value="yt-en">
                            </fieldset>
                        </form>
                    `;
                    $('#nav-en').html(template);
                    ClassicEditor.create(document.getElementById('letter_editor_en'));
                }
                if (nav == "yt-po") {
                    let template = "";
                    template += `
                        <form name="letters_yt_po" id="letters_yt_po" action="" method="post" >
                            <fieldset>
                                <label id="title_letter_po">${lang}</label>
                                <fieldset class="pb-2">
                                    <input  type="submit" class="btn btn-success btn_letter_save" name="btnEditLetter" id="btnEditLetter_ytpo" value="Guardar">
                                </fieldset>
                                <textarea rows="1" name="poytLetter" class="letterEditor" id="letter_editor_po">${nav_res.footer}</textarea>
                                <input type="hidden" name="poytLetterID" id="id_letter_po" value="${nav_res.id_letter}">
                                <input type="hidden" name="poytLetterTa" id="id_letter_po" value="letter">
                                <input type="hidden" name="poytLetterTy" id="id_letter_po" value="yt-po">
                            </fieldset>
                        </form>
                    `;
                    $('#nav-po').html(template);
                    ClassicEditor.create(document.getElementById('letter_editor_po'));
                }
                if (nav == "tu-es") {
                    let template = "";
                    template += `
                        <form name="letters_tu_es" id="letters_tu_es" action="" method="post" >
                            <fieldset>
                                <label id="title_letter_es_tu">${lang}</label>
                                <fieldset class="pb-2">
                                    <input  type="submit" class="btn btn-success btn_letter_save" name="btnEditLetter" id="btnEditLetter_tues" value="Guardar">
                                </fieldset>
                                <textarea rows="1" name="estuLetter" class="letterEditor" id="letter_editor_es_tu">${nav_res.footer}</textarea>
                                <input type="hidden" name="estuLetterID" id="id_letter_es_tu_tu" value="${nav_res.id_letter}">
                                <input type="hidden" name="estuLetterTa" id="id_letter_es_tu" value="letter_tureando">
                                <input type="hidden" name="estuLetterTy" id="id_letter_es_tu" value="tu-es">
                            </fieldset>
                        </form>
                    `;
                    $('#nav-es-tu').html(template);
                    ClassicEditor.create(document.getElementById('letter_editor_es_tu'));
                }
                if (nav == "tu-en") {
                    let template = "";
                    template += `
                        <form name="letters_tu_en" id="letters_tu_en" action="" method="post" >
                            <fieldset>
                                <label id="title_letter_en_tu">${lang}</label>
                                <fieldset class="pb-2">
                                    <input  type="submit" class="btn btn-success btn_letter_save" name="btnEditLetter" id="btnEditLetter_tuen" value="Guardar">
                                </fieldset>
                                <textarea rows="1" name="entuLetter" class="letterEditor" id="letter_editor_en_tu">${nav_res.footer}</textarea>
                                <input type="hidden" name="entuLetterID" id="id_letter_en_tu" value="${nav_res.id_letter}">
                                <input type="hidden" name="entuLetterTa" id="id_letter_en_tu" value="letter_tureando">
                                <input type="hidden" name="entuLetterTy" id="id_letter_en_tu" value="tu-en">
                            </fieldset>
                        </form>
                    `;
                    $('#nav-en-tu').html(template);
                    ClassicEditor.create(document.getElementById('letter_editor_en_tu'));
                }
                if (nav == "tu-po") {
                    let template = "";
                    template += `
                        <form name="letters_tu_po" id="letters_tu_po" action="" method="post" >
                            <fieldset>
                                <label id="title_letter_po_tu">${lang}</label>
                                <fieldset class="pb-2">
                                    <input  type="submit" class="btn btn-success btn_letter_save" name="btnEditLetter" id="btnEditLetter_tupo"  value="Guardar">
                                </fieldset>
                                <textarea rows="1" name="potuLetter" class="letterEditor" id="letter_editor_po_tu">${nav_res.footer}</textarea>
                                <input type="hidden" name="potuLetterID" id="id_letter_po_tu" value="${nav_res.id_letter}">
                                <input type="hidden" name="potuLetterTa" id="id_letter_po_tu" value="letter_tureando">
                                <input type="hidden" name="potuLetterTy" id="id_letter_po_tu" value="tu-po">
                            </fieldset>
                        </form>
                    `;
                    $('#nav-po-tu').html(template);
                    ClassicEditor.create(document.getElementById('letter_editor_po_tu'));
                }   
            } 
        });
    };
    // $(document).on('click', '.btn_letter_save', function(){
    //     let id_letter = $(this).data('idl');
    //     let table = $(this).data('type');
    //     let nav = $(this).data('nav');
    //     let footer = "";
    //     if (nav == "yt-es") {
    //         footer = $('#letter_editor_es').val();
    //     }
    //     if (nav == "yt-en") {
    //         footer = $('#letter_editor_en').val();
    //     }
    //     if (nav == "yt-po") {
    //         footer = $('#letter_editor_po').val();
    //     }
    //     if (nav == "tu-es") {
    //         footer = $('#letter_editor_es_tu').val();
    //     }
    //     if (nav == "tu-en") {
    //         footer = $('#letter_editor_en_tu').val();
    //     }
    //     if (nav == "tu-po") {
    //         footer = $('#letter_editor_po_tu').val();
    //     }
    //     console.log(footer);
    //     var postDatas = {
    //         'id_letter': id_letter,
    //         'table': table,
    //         'nav_lang': nav,
    //         'letter': footer,
    //         'action': 'update_letter'
    //     };
    //     $.ajax({
    //         url:"../../helpers/cartas.php",
    //         type: "post",
    //         data: postDatas,
    //         // beforeSend: function(){
    //         //     let template = '';
    //         //     template += `
    //         //     <div class="row mt-3">
    //         //         <div class="col-lg-4 col-md-3">
    //         //         </div>
    //         //         <div class="col-lg-4 col-md-6">
    //         //             <div class="spinner-grow text-dark" role="status">
    //         //                 <span class="sr-only">Loading...</span>
    //         //             </div>
    //         //             <div class="spinner-grow text-secondary" role="status">
    //         //                 <span class="sr-only">Loading...</span>
    //         //             </div>
    //         //             <div class="spinner-grow text-dark" role="status">
    //         //                 <span class="sr-only">Loading...</span>
    //         //             </div>
    //         //         </div>
    //         //         <div class="col-lg-4 col-md-3">
    //         //         </div>
    //         //     </div>
                    
    //         //     `;
    //         //     switch (postDatas.nav) {
    //         //         case 'yt-es':
    //         //             $('#nav-es').html(template);
    //         //             break;
    //         //         case 'yt-en':
    //         //             $('#nav-en').html(template);
    //         //             break;
    //         //         case 'yt-po':
    //         //             $('#nav-po').html(template);
    //         //             break;
    //         //         case 'tu-es':
    //         //             $('#nav-es-tu').html(template);
    //         //             break;
    //         //         case 'tu-en':
    //         //             $('#nav-en-tu').html(template);
    //         //             break;
    //         //         case 'tu-po':
    //         //             $('#nav-po-tu').html(template);
    //         //             break;
    //         //         default:
    //         //             $('#nav-es').html(template);
    //         //             break;
    //         //     }
    //         // },
    //         success: function(res){
    //             console.log(res);
    //         }
    //     });
    // });

    function updateLetter(){
        
    };

});