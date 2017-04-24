
<script>
    function group_users(id){
        $.post("app.php",
            {
                id: id,
                type: 'vpz_table'
            },function(data){
                if(data.status == 'error'){
                    alert("error");
                }else{
                    $('#try').html(data.vpz.try);
                    var table = $('#example').DataTable({
                        data: data.users,
                        "columns": [
                            {
                                "className":      'details-control',
                                "orderable":      false,
                                "data":           null,
                                "defaultContent": ''
                            },
                            { "data": "iin" },
                            { "data": "lastname" },
                            { "data": "firstname" },
                            { "data": "patronymic" },
                            { "data": "email" }
                        ],
                        "order": [[1, 'asc']],
                        "scrollY": "400px",
                        "paging": false,
                        initComplete: function () {
                            this.api().columns().every( function () {
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
    function insert_user(){
        var iin = $('#iin').val();
        var fam = $('#fam').val();
        var name = $('#name').val();
        var fath = $('#fath').val();
        var email = $('#email').val();
        var kol_try = $('#kol_try').val();
        var iinchb = false;
        if($('#iinchb').is(':checked')){
            iinchb = true;
        }
        $('#btn_ins').attr('disabled', true);
        $.post("app.php",
            {
                iin: iin,
                fam: fam,
                name: name,
                fath: fath,
                email: email,
                kol_try: kol_try,
                iinchb: iinchb,
                type: 'add_individual_student'
            },function(data){
                $('#btn_ins').attr('disabled', false);
                if(data.status == 'ok'){
                    alert(data.text);
                    $('#isUser').modal('hide');
                    $('#iin').val('');
                    $('#fam').val('');
                    $('#name').val('');
                    $('#fath').val('');
                    $('#email').val('');
                    $('#kol_try').val('');
                    print_user(data.id)
                    location.reload();
                }else{
                    alert(data.text);
                }
            }, "json");
    }


    function show_add_try(id){
        $.post("app.php",
            {
                id: id,
                type: 'show_add_try'
            },function(data){
                if(data.status == 'ok'){
                    $('#body_add_try').html(data.htm);
                    $('#add_try').modal('show');
                }else{
                    alert(data.status);
                }
            }, "json");
    }

    function add_try(){
        var id = $('#id_add_try').val();
        var kol_try = $('#add_kol_try').val();
        $('#btn_add').attr('disabled', true);
        $.post("app.php",
            {
                id: id,
                kol_try: kol_try,
                type: 'add_try'
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

    function show_update_user(id){
        $.post("./app.php",
            {
                id: id,
                type: 'show_update_user'
            },function(data){
                if(data.status == 'ok'){
                    $('#body_up_user').html(data.htm);
                    $('#upd_user').modal('show');
                }else{
                    alert(data.status);
                }
            }, "json");
    }

    function update_user(){
        var id = $('#up_id_user').val();
        var fam = $('#ufam').val();
        var name = $('#uname').val();
        var fath = $('#ufath').val();
        var email = $('#uemail').val();

        $.post("app.php",
            {
                id: id,
                fam: fam,
                name: name,
                fath: fath,
                email: email,
                type: 'update_user'
            },function(data){
                if(data.status == 'ok'){
                    location.reload();
                }else if(data.status == 'error'){
                    alert(data.text);
                }
            }, "json");
    }


    document.addEventListener("DOMContentLoaded", group_users(0));
</script>