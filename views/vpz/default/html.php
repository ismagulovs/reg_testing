<div class="row">
  
   <div class="col-lg-6 text-right"> 
        <button type="button" class="btn btn-default btn-lg" onclick="individual();">индивидуальные тестирования</button>
    </div>
	
    <div class="col-lg-6 text-left">
        <?php //<button type="button" class="btn btn-default btn-lg" onclick="group();">школьные тестирования</button>
		?>
    </div>
</div>
<script>
    function individual(){
        $.post("app.php",
            {
                route: 'individual'
            },function(){
                location.reload();
            });
    }

    <?php 
//	function group(){
  //      $.post("app.php",
    //        {
     //           route: 'group'
     //       },function(){
     //           location.reload();
     //       });
//    }
	?>
</script>