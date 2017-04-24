$( document ).ready(function() {

    group_users(0);

	 $('#uchZav_id').change(function(){
			var uchZav_id = $(this).val();
		if (uchZav_id !== '0') {
		    document.getElementById('FormReg').style.display = "block";
			document.getElementById('idUchZav').value = uchZav_id;
		}
	}); 
	$('#raion_show').change(function(){
		    $("#namekaz").attr("disabled", false);
            $("#namerus").attr("disabled", false);
	});


    $('#inlineCheckbox1').click(function(){
        if($(this).is(':checked')){
            $("#iin").val('');
            $("#iin").attr("disabled", true);
        } else {
            $("#iin").attr("disabled", false);
        }
    });

    $('#select_1').change(function(){
        var school = $(this).val();

    });
	
});

function group_users(id){
   // var a = id.selectedIndex;
    $.post("./func/group_school/GroupSchool.php",
        {
            id: 0
        },function(data){
            console.log(data);
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
                    { "data": "firstname" },
                    { "data": "lastname" },
                    { "data": "school" }
                ],
                "order": [[1, 'asc']],
                "scrollY": "300px",
                "paging": false,
                initComplete: function () {
                    this.api().columns().every( function () {
                        var column = this;
                        //console.log(column[0][0]);
                        if(column[0][0] == 2){
                            console.log(column[0]);
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
        },"json");

}

function format ( d ) {
    // дополнительная таблица
    return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
        '<tr><th colspan="2"><ul class="list-group"> '+
        '<li class="list-group-item list-group-item-info">'+
        '<span class="badge label-primary">'+d.patronymic+'</span>Отчество:'+
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

function isAddSchool(){
	var id_obl = $('#obl_show_add_school').val();
	var id_raion = $('#raion_show_add_school').val();
	var name_kaz = $('#add_name_school_kaz').val();
	var name_rus = $('#add_name_school_rus').val();
	$('#btnisAddSchool').attr('disabled', true);
	$.post("./func.php",
	{
		id_obl: id_obl,
		id_raion: id_raion,
		name_kaz: name_kaz,
		name_rus: name_rus,
		type: 'addSchool'
	},function(data){
            $('#btnisAddSchool').attr('disabled', false);
			if(data.status == 'ok'){
				alert(data.text);
				$('#isUser').modal('hide');
                location.reload();
			}else{
				alert(data.text);
			}
		}, "json");
}

function insert_user(){
	var iin = $('#iin').val();
	var fam = $('#fam').val();
	var name = $('#name').val();
	var fath = $('#fath').val();
	var email = $('#email').val();
	var school = $('#shool_show').val();
	var kol_try = $('#kol_try').val();
    var iinchb = false;
    if($('#iinchb').is(':checked')){
        iinchb = true;
    }
    $('#btn_ins').attr('disabled', true);
	$.post("./func.php", 
		{
			iin: iin,
			fam: fam,
			name: name,
			fath: fath, 
			email: email,
			school: school,
			kol_try: kol_try,
            iinchb: iinchb,
			type: 'iuser'
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
				$('#school').val('');
				$('#kol_try').val('');
				window.open('./func/print.php?id='+data.id); 
                location.reload();
			}else{
				alert(data.text);
			}
		}, "json");
}

function add_try(){
    var id = $('#id_add_try').val();
    var kol_try = $('#add_kol_try').val();
    $('#btn_add').attr('disabled', true);
	$.post("./func.php",
		{
            id: id,
            kol_try: kol_try,
			type: 'add_try'
		},function(data){
            $('#btn_add').attr('disabled', false);
			if(data.status == 'ok'){
				print_user(id);
				location.reload();
			}else{
				alert(data.status);
			}
		}, "json");
}

function show_update_user(id){
    $.post("./func.php",
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
function show_add_try(id){
	$.post("./func.php",
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
function update_user(){
	var id = $('#up_id_user').val();
	var fam = $('#ufam').val();
	var name = $('#uname').val();
	var fath = $('#ufath').val();
	var email = $('#uemail').val();
	
	$.post("./func.php",
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

function print_user(id){
    window.open('./func/print.php?id='+id); 
}

function qwert(){
    $.post("./func.php",{
        type: 'close'
    },function(data){
        location.reload();
    })
}


