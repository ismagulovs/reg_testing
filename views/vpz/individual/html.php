<table id="example"  class="table table-striped table-bordered"  cellspacing="0" width="100%">
    <thead>
    <tr>
        <th width="20px"></th>
        <th>ИИН</th>
        <th>Фамилия</th>
        <th>Имя</th>
        <th>Отчество</th>
        <th>email</th>
    </tr>
    </thead>
    <tfoot style="background-color: #ddd;">
    <tr>
        <th ></th>
        <th>
            <div class="btn-group-vertical" role="group" >
                <a type="button" id="addUsers" class="btn btn-primary  btn-lg" data-toggle="modal" data-target="#isUser">
                    добавить тестируемого
                </a>

            </div>
        </th>
        <th style="font-size: 12px;"></th>
        <th></th>
        <th> <span id="try"></span></th>
        <th></th>

    </tr>
    </tfoot>
</table>

<div class="modal fade" id="isUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Добавление тестируемого</h4>
            </div>
            <div class="modal-body">
                <br/>
                <div class="form-group">
                    <div>
                        <label for="iin" style="margin-bottom: 1px">ИИН</label>
                        <input type="text" id="iin" class="form-control input-sm" placeholder="ИИН">
                        <label class="checkbox-inline">
                            <input type="checkbox" id="iinchb" value="1"> нет ИИНа
                        </label>
                    </div>
                    <br>
                    <div>
                        <label for="fam" style="margin-bottom: 1px">Фамилия</label>
                        <input type="text" id="fam" class="form-control input-sm" placeholder="Фамилия">
                    </div>
                    <br>
                    <div>
                        <label for="name" style="margin-bottom: 1px">Имя</label>
                        <input type="text" id="name" class="form-control input-sm" placeholder="Имя">
                    </div>
                    <br>
                    <div>
                        <label for="fath" style="margin-bottom: 1px">Отчество</label>
                        <input type="text" id="fath" class="form-control input-sm" placeholder="Отчество">
                    </div>
                    <br>
                    <div>
                        <label for="email" style="margin-bottom: 1px">email</label>
                        <input type="text" id="email" class="form-control input-sm" placeholder="email">
                    </div>
                    <br>
<!--                    <div>-->
<!--                        <label for="school" style="margin-bottom: 1px">учебное заведение</label>-->
<!--                        <div class="row">-->
<!--                            <div class="col-md-4">-->
<!--                                <select  id="obl_show" class="form-control input-sm">-->
<!--                                </select>-->
<!--                            </div>-->
<!--                            <div class="col-md-4">-->
<!--                                <select id="raion_show" class="form-control input-sm">-->
<!--                                </select>-->
<!--                            </div>-->
<!--                            <div class="col-md-4">-->
<!--                                <select id="shool_show" class="form-control input-sm">-->
<!--                                </select>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                    <br>
                    <div>
                        <label for="kol_try" style="margin-bottom: 1px">количество попыток</label>
                        <input type="number" id="kol_try" class="form-control input-sm" placeholder="количество попыток">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div id="FormReg">
                    <input type="hidden" id="id_uch_zav_2"/>
                    <button type="button" class="btn btn-default" id="btn_ins"  onclick="insert_user();">
                        Добавить
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="add_try" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Добавление попытки тестирования</h4>
            </div>
            <div class="modal-body">
                <div id="body_add_try">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_add" onclick="add_try();">Добавить</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="upd_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Правка</h4>
            </div>
            <div class="modal-body">
                <div id="body_up_user">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_add" onclick="update_user();">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<?php
include('js.php');