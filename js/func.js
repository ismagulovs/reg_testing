function exit(){
    document.cookie = 'vpz =; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    document.cookie = 'view =; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    $.post("app.php",
        {
            route: 'exit'
        },function(data){
            if(data.status == 'ok'){
                location.reload();
            }else{
                alert(data.text);
            }
        }, "json");
}

function rout_home_vpz(){
    document.cookie = 'vpz =; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    location.reload();
}