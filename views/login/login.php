<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-2 col-md-4 col-md-offset-1">
        <div class="login-panel panel">
            <div class="panel-heading" style="background-color: #008888; color: #ffffff"><h4>Вход</h4></div>
            <div class="panel-body">
                <form role="form">
                    <fieldset>
                        <div class="form-group">
                            <input type="login" name="log" class="form-control" id="inputlogin" placeholder="ЛОГИН">
                        </div>
                        <div class="form-group">
                            <input type="password" name="pass" class="form-control" id="inputPassword" placeholder="ПАРОЛЬ">
                        </div>
                        <button type="button" class="btn" style="background-color: #008888; color: #ffffff;" onclick="login();" >войти</button>
                    </fieldset>
                </form>
            </div>
        </div>
    </div><!-- /.col-->
</div><!-- /.row -->


<script type="text/javascript">
    function login(){
        var login = $("#inputlogin").val();
        var pass =  $("#inputPassword").val();
        $.post("app.php", {
                login: login,
                password: pass,
                type: 'login'
            },
            function(data){
                if(data.status == 'error'){
                    alert(data.text);
                }else if(data.status == 'login'){
                    location.reload();
                }
            }, "json"
        );
    }
</script>