<table id="example"  class="table table-striped table-bordered"  cellspacing="0" width="100%">
    <thead>
    <tr>
        <th width="20px"></th>
        <th>Логин</th>
        <th>ФИО</th>
        <th>Школа</th>
    </tr>
    </thead>
    <tfoot style="background-color: #ddd;">
    <tr>
        <th ></th>
        <th>
            <div class="btn-group-vertical" role="group" >
                <a type="button" id="addUsers" class="btn btn-primary " data-toggle="modal" data-target="#isUser">
                    добавить ответственного
                </a>

                <a type="button" id="addSchool" class="btn btn-info" data-toggle="modal" data-target="#addSchoolModal">
                    нет учебного заведения в списке
                </a>
            </div>
        </th>

        <th style="padding-top:13px; text-align: right; font-size: 10px"> поиск по учебному заведению:</th>
        <th style="text-align: left"></th>

    </tr>
    </tfoot>
</table>

<div class="modal fade" id="addSchoolModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Добавление учебного заведения в список</h4>
            </div>
            <div class="modal-body">
                <br/>
                <div class="form-group">
                    <div class="text-center">
                        <label style="margin-bottom: 5px">Местоположение</label>
                        <div class="row">
                            <div class="col-md-6">
                                <select  id="obl_show_add_school" class="form-control input-sm">
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select id="raion_show_add_school" class="form-control input-sm">
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="text-center">
                        <label style="margin-bottom: 5px">Наименование учебного заведения</label>
                        <input type="text" id="add_name_school_kaz" class="form-control input-sm" placeholder="на казахском">
                        <input type="text" id="add_name_school_rus" class="form-control input-sm" placeholder="на русском">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"  id="btnisAddSchool" onclick="isAddSchool();">
                    Добавить
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="isUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Добавление ответственного</h4>
            </div>
            <div class="modal-body">
                <br/>
                <div class="form-group">
                    <div>
                        <label for="iin" style="margin-bottom: 1px">Логин</label>
                        <input type="text" id="login" class="form-control input-sm" placeholder="Логин">
                    </div>
                    <br>
                    <div>
                        <label for="fam" style="margin-bottom: 1px">ФИО</label>
                        <input type="text" id="fio" class="form-control input-sm" placeholder="ФИО">
                    </div>
                    <br>
                    <div>
                        <label for="school" style="margin-bottom: 1px">учебное заведение</label>
                        <div class="row">
                            <div class="col-md-4">
                                <select  id="obl_show" class="form-control input-sm">
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="raion_show" class="form-control input-sm">
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="shool_show" class="form-control input-sm">
                                </select>
                            </div>
                        </div>
                    </div>
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
                    <button type="button" class="btn btn-default" id="btn_ins"  onclick="insert_otvetsec();">
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
                <button type="button" class="btn btn-primary" id="btn_add" onclick="add_try_otvetsec();">Добавить</button>
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
                <button type="button" class="btn btn-primary" id="btn_add" onclick="update_otetsec();">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<?php
include('js.php');
