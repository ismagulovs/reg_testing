
<script>
    function group_users(id){
        // var a = id.selectedIndex;
        $.post("app.php",
            {
                id: id,
                type: 'vpz_table_group'
            },function(data){
                if(data.status == 'error'){
                    alert("error");
                }else{
                    $('#try').html(data.vpzGroup.try);
                    $('#obl_show_add_school').html(data.vpzGroup.select);
                    $('#obl_show').html(data.vpzGroup.select);
                    var table = $('#example').DataTable({
                        data: data.otvetsec,
                        "columns": [
                            {
                                "className":      'details-control',
                                "orderable":      false,
                                "data":           null,
                                "defaultContent": ''
                            },
                            { "data": "login" },
                            { "data": "fio" },
                            { "data": "school" }
                        ],
                        "order": [[1, 'asc']],
                        "scrollY": "300px",
                        "paging": false,
                        initComplete: function () {
                            this.api().columns().every( function () {
                                var column = this;
                                if(column[0][0] == 3){
                                    var select = $('<select class="form-control input-sm" style="min-width: 200px; max-width: 500px;"><option value=""> -Учебное заведение- </option></select>')
                                        .appendTo( $(column.footer()).empty() )
                                        .on( 'change', function () {
                                            var val = $.fn.dataTable.util.escapeRegex(
                                                $(this).val()
                                            );

                                            column
                                                .search( val ? '^'+val+'$' : '', true, false )
                                                .draw();
                                        } );

                                    column.data().unique().sort().each( function ( d, j ) {
                                        select.append( '<option value="'+d+'">'+d+'</option>' )
                                    } );
                                }
                            } );
                        }
                    });

                    $('#example tbody').on('click', 'td.details-control', function () {
                        var tr = $(this).closest('tr');
                        var row = table.row( tr );

                        if ( row.child.isShown() ) {
                            // This row is already open - close it
                            row.child.hide();
                            tr.removeClass('shown');
                        }
                        else {
                            // Open this row
                            row.child( format(row.data()) ).show();
                            tr.addClass('shown');
                        }
                    } );
                }
            },"json");
    }

    function format ( d ) {
        // дополнительная таблица
        return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
            '<tr><th colspan="2"><ul class="list-group"> '+
            '<li class="list-group-item list-group-item-info">'+
            '<span class="badge label-primary">'+d.password+'</span>Пароль:'+
            '</li><li class="list-group-item list-group-item-success">'+
            '<span class="badge">'+ d.id + '</span>ID:'+
            '</li>'+
            '</li><li class="list-group-item list-group-item-info">'+
            '<span class="badge">'+ d.cnt1 + '</span>кол-во использованых попыток:'+
            '</li>'+'</li><li class="list-group-item list-group-item-success">'+
            '<span class="badge">'+ d.cnt2 + '</span>кол-во активных попыток:'+
            '</li>'+
            '</ul></th></tr>'+
            '<tr><th></th><td><div class="btn-group" role="group">'+ d.add + d.print + d.update +'</div></td></tr>'+
            '</table>';
    }

    function insert_otvetsec(){
        var login = $('#login').val();
        var fio = $('#fio').val();
        var school = $('#shool_show').val();
        var kol_try = $('#kol_try').val();
        $('#btn_ins').attr('disabled', true);
        $.post("app.php",
            {
                login: login,
                fio: fio,
                school: school,
                kol_try: kol_try,
                type: 'add_otvetsec'
            },function(data){
                $('#btn_ins').attr('disabled', false);
                if(data.status == 'ok'){
                    alert(data.text);
                    $('#isUser').modal('hide');
                    $('#login').val('');
                    $('#fio').val('');
                    $('#school').val('');
                    $('#kol_try').val('');
                  //  window.open('./func/print.php?id='+data.id);
                    location.reload();
                }else{
                    alert(data.text);
                }
            }, "json");
    }



    $('#addUsers').click(function(){
        select_obl();
    });

    $('#obl_show').change(function(){
        select_obl();
    });

    $('#raion_show').change(function(){
        var obl = $('#obl_show').val();
        var raion = $('#raion_show').val();
        if(obl == '0' || raion == '0'){
            $('#shool_show').html('<option value="0">- учебное заведение - </option>');
            $('#shool_show').attr('disabled', true);
            return(false);
        }
        $('#shool_show').attr('disabled', true);
        $('#shool_show').html('<option>-загрузка-</option>');

        $.post("./func/select.php",
            {
                id_obl: obl,
                id_raion: raion,
                type: 'school'
            },function(result){
                if(result.type=='error'){
                    alert('ошибка загрузки данных!!!');
                    return(false);
                }else if(result.type=='error1'){
                    $('#shool_show').html('<option value="0">- нет учебных заведений в базе -</option>');
                    $('#shool_show').attr('disabled', false);
                }else{
                    var options = '';

                    $(result.school).each(function() {
                        options += '<option value="' + $(this).attr('id') + '">' + $(this).attr('name_rus') + '</option>';
                    });
                    $('#shool_show').html('<option value="0">- учебное заведение -</option>'+options);
                    $('#shool_show').attr('disabled', false);
                }
            }, "json");
    });

    function select_obl(){
        var obl = $('#obl_show').val();
        if(obl == '0'){
            $('#raion_show').html('<option>- район - </option>');
            $('#raion_show').attr('disabled', true);

            $('#shool_show').html('<option value="0">- учебное заведение - </option>');
            $('#shool_show').attr('disabled', true);
            return(false);
        }
        $('#raion_show').attr('disabled', true);
        $('#raion_show').html('<option>-загрузка-</option>');

        $('#shool_show').html('<option value="0">- учебное заведение - </option>');
        $('#shool_show').attr('disabled', true);

        $.post("./func/select.php",
            {
                id_obl: obl,
                type: 'raion'
            },function(result){
                if(result.type=='error'){
                    alert('ошибка загрузки данных!!!');
                    return(false);
                }else{
                    var options = '';
                    //alert(result.text);
                    $(result.raion).each(function() {
                        options += '<option value="' + $(this).attr('id_raion') + '">' + $(this).attr('name_rus') + '</option>';
                    });
                    $('#raion_show').html('<option value="0">- район -</option>'+options);
                    $('#raion_show').attr('disabled', false);
                }
            }, "json");
    }

    function obl_show_update(){
        $('#obl_show_update').change(function(){
            select_obl_update();
        });
    }

    function raion_show_update(){
        $('#raion_show_update').change(function(){
            var obl = $('#obl_show_update').val();
            var raion = $('#raion_show_update').val();
            if(obl == '0' || raion == '0'){
                $('#shool_show_update').html('<option value="0">- учебное заведение - </option>');
                $('#shool_show_update').attr('disabled', true);
                return(false);
            }
            $('#shool_show_update').attr('disabled', true);
            $('#shool_show_update').html('<option>-загрузка-</option>');

            $.post("./func/select.php",
                {
                    id_obl: obl,
                    id_raion: raion,
                    type: 'school'
                },function(result){
                    if(result.type=='error'){
                        alert('ошибка загрузки данных!!!');
                        return(false);
                    }else if(result.type=='error1'){
                        $('#shool_show_update').html('<option value="0">- нет учебных заведений в базе -</option>');
                        $('#shool_show_update').attr('disabled', false);
                    }else{
                        var options = '';

                        $(result.school).each(function() {
                            options += '<option value="' + $(this).attr('id') + '">' + $(this).attr('name_rus') + '</option>';
                        });
                        $('#shool_show_update').html('<option value="0">- учебное заведение -</option>'+options);
                        $('#shool_show_update').attr('disabled', false);
                    }
                }, "json");
        });

    }

    function select_obl_update(){
        var obl = $('#obl_show_update').val();
        if(obl == '0'){
            $('#raion_show_update').html('<option>- район - </option>');
            $('#raion_show_update').attr('disabled', true);

            $('#shool_show_update').html('<option value="0">- учебное заведение - </option>');
            $('#shool_show_update').attr('disabled', true);
            return(false);
        }
        $('#raion_show_update').attr('disabled', true);
        $('#raion_show_update').html('<option>-загрузка-</option>');

        $('#shool_show_update').html('<option value="0">- учебное заведение - </option>');
        $('#shool_show_update').attr('disabled', true);

        $.post("./func/select.php",
            {
                id_obl: obl,
                type: 'raion'
            },function(result){
                if(result.type=='error'){
                    alert('ошибка загрузки данных!!!');
                    return(false);
                }else{
                    var options = '';
                    //alert(result.text);
                    $(result.raion).each(function() {
                        options += '<option value="' + $(this).attr('id_raion') + '">' + $(this).attr('name_rus') + '</option>';
                    });
                    $('#raion_show_update').html('<option value="0">- район -</option>'+options);
                    $('#raion_show_update').attr('disabled', false);
                }
            }, "json");
    }

    $('#addSchool').click(function(){
        addObl();
    });

    $('#obl_show_add_school').change(function(){
        addObl();
    });

    function addObl() {
        var obl = $('#obl_show_add_school').val();
        if (obl == '0') {
            $('#raion_show_add_school').html('<option>- район - </option>');
            $('#raion_show_add_school').attr('disabled', true);
            return (false);
        }
        $('#raion_show_add_school').attr('disabled', true);
        $('#raion_show_add_school').html('<option>-загрузка-</option>');

        $.post("./func/select.php",
            {
                id_obl: obl,
                type: 'raion'
            }, function (result) {
                if (result.type == 'error') {
                    alert('ошибка загрузки данных!!!');
                    return (false);
                } else {
                    var options = '';
                    $(result.raion).each(function () {
                        options += '<option value="' + $(this).attr('id_raion') + '">' + $(this).attr('name_rus') + '</option>';
                    });
                    $('#raion_show_add_school').html('<option value="0">- район -</option>' + options);
                    $('#raion_show_add_school').attr('disabled', false);
                }
            }, "json");
    }

    function show_add_try_otvetsec(id){
        $.post("app.php",
            {
                id: id,
                type: 'show_add_try_otvetsec'
            },function(data){
                if(data.status == 'ok'){
                    $('#body_add_try').html(data.htm);
                    $('#add_try').modal('show');
                }else{
                    alert(data.status);
                }
            }, "json");
    }

    function add_try_otvetsec(){
        var id = $('#id_add_try_otvetsec').val();
        var kol_try = $('#add_kol_try_otvetsec').val();
        $('#btn_add').attr('disabled', true);
        $.post("app.php",
            {
                id: id,
                kol_try: kol_try,
                type: 'add_try_otvetsec'
            },function(data){
                $('#btn_add').attr('disabled', false);
                if(data.status == 'ok'){
                    // print_user(id);
                    location.reload();
                }else{
                    alert(data.status);
                }
            }, "json");
    }

    function print_user(id){
        $.post("app.php",
            {
                id: id,
                type: 'print'
            },function(data){
                var WindowObject = window.open('', "PrintWindow", "width=600,height=700,top=200,left=200,toolbars=no,scrollbars=no,status=no,resizable=no");
                WindowObject.document.writeln(data.print);
                WindowObject.document.close();
                WindowObject.focus();
                WindowObject.print();
                WindowObject.close();
            }, "json");
    }

    function show_update_otvetsec(id){
        $.post("app.php",
            {
                id: id,
                type: 'show_update_otvetsec'
            },function(data){
                if(data.status == 'ok'){
                    $('#body_up_user').html(data.htm);

                    $('#upd_user').modal('show');
                }else{
                    alert(data.status);
                }
            }, "json");
    }

    function update_otetsec(){
        var id = $('#up_id_otvetsec').val();
        var fio = $('#ufio').val();
        var school = $('#shool_show_update').val();

        if(school == null){
            school = 0;
        }

        $.post("app.php",
            {
                id: id,
                fio: fio,
                school: school,
                type: 'update_otvetsec'
            },function(data){
                if(data.status == 'ok'){
                    location.reload();
                }else if(data.status == 'error'){
                    alert(data.text);
                }
            }, "json");
    }

    function isAddSchool(){
        var obl = $('#obl_show_add_school').val();
        var raion = $('#raion_show_add_school').val();
        var name_kaz = $('#add_name_school_kaz').val();
        var name_rus = $('#add_name_school_rus').val();
        $.post("app.php",
            {
                obl: obl,
                raion: raion,
                name_kaz: name_kaz,
                name_rus: name_rus,
                type: "add_school"
            },function(data){
                if(data.status == 'ok'){
                    alert(data.text);
                    location.reload();
                }else{
                    alert(data.text);
                }
            }, "json")

    }
	
	function print_otvetsec(id){
        $.post("app.php",
            {
                id: id,
                type: 'print_otvsec'
            },function(data){
                var WindowObject = window.open('', "PrintWindow", "width=600,height=700,top=200,left=200,toolbars=no,scrollbars=no,status=no,resizable=no");
                WindowObject.document.writeln(data.print);
                WindowObject.document.close();
                WindowObject.focus();
                WindowObject.print();
                WindowObject.close();
            }, "json");
    }

    document.addEventListener("DOMContentLoaded", group_users(0));
</script>