
<nav class="navbar navbar-static"  style=" background-color: #008888">
        <div class="container-fluid" style="margin:10px;">
            <div class="row">
                <div class="col-md-3">
                        <img src="logo.png">
                </div>
                <div class="col-md-7">
                    <?php
                    if(isset($_SESSION['loginID'])){
                        echo ' <button class="btn btn-lg" style="background-color:rgba(0, 136, 136, 0.75)" onclick="rout_home_vpz();">главная</button>';
                    }
                    ?>

                </div>
                <div class="col-md-2 text-right">
                    <button class="btn btn-lg" style="background-color: rgba(0, 112, 112, 0.75)" onclick="exit();">выход</button>
                </div>
            </div>
        </div>
</nav>