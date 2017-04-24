$(document).ready(function () {
	
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

	
	
	
	$('#addSchool').click(function(){
		addObl();
	});
	
	$('#obl_show_add_school').change(function(){
		addObl();
	});
	
	function addObl(){
		var obl = $('#obl_show_add_school').val();
		if(obl == '0'){
			$('#raion_show_add_school').html('<option>- район - </option>');
			$('#raion_show_add_school').attr('disabled', true);
			return(false);
		}
		$('#raion_show_add_school').attr('disabled', true);
		$('#raion_show_add_school').html('<option>-загрузка-</option>');
		
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
					$(result.raion).each(function() {
						options += '<option value="' + $(this).attr('id_raion') + '">' + $(this).attr('name_rus') + '</option>';
					});
					$('#raion_show_add_school').html('<option value="0">- район -</option>'+options);
					$('#raion_show_add_school').attr('disabled', false);			
				}
			}, "json");
	}

});

