<?php 
if(isset($_SESSION['vpz']) && $_SESSION['vpz'] != 0){
	$array_vpz = get_user_id($_SESSION['vpz']);
	$cnt_try = count_try($_SESSION['vpz']);
?>
<div class="row">
    <div class="col-xs-8 col-sm-6 col-md-8"><h2>
            <?php
             echo ''.$array_vpz[0]['username'].'</h2>';
             //   print_r($array_vpz);
            if($cnt_try[0]['stat_not_0'] == null){
                $stat_not_0 = 0;
            }else{
                $stat_not_0 = $cnt_try[0]['stat_not_0'];
            }
            if($cnt_try[0]['stat0'] == null){
                $stat0 = 0;
            }else{
                $stat0 = $cnt_try[0]['stat0'];
            }
            ?>
    </div>
    <div class="col-xs-4 col-sm-2 col-md-4">
        <div class="vertical-center">
        </div>
    </div>
</div>

    <table id="example"  class="table table-striped table-bordered"  cellspacing="0" width="100%">
        <thead>
        <tr>
            <th width="20px"></th>
            <th>ИИН</th>
            <th>Фамилия</th>
            <th>Имя</th>
            <th>Школа</th>
        </tr>
        </thead>
        <tfoot style="background-color: #ddd;">
        <tr>
            <th style="font-size: 12px"> <?php
                echo 'Активные попытки: '.$stat0.'<br>
                Использованные попытки: '.$stat_not_0.'<br>
                Итого: '.($stat0+$stat_not_0).'<br>';
                ?>
            </th>
            <th style="padding-top:13px;  text-align: right; font-size: 10px"> поиск по учебному заведению:</th>
            <th></th>
            <th style="text-align: right">
                <div class="btn-group-vertical" role="group" >
                    <a type="button" id="addUsers" class="btn btn-primary " data-toggle="modal" data-target="#isUser">
                        добавить тестируемого
                    </a>

                    <a type="button" id="addSchool" class="btn btn-info" data-toggle="modal" data-target="#addSchoolModal">
                        нет учебного заведения в списке
                    </a>
                </div>
            </th>

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
						<?php 
							$array_obl = get_obl();
							foreach($array_obl as $item):
								if($item['id']==$array_vpz[0]['id_obl']) $selected = 'selected'; else $selected = '';
								echo '<option value="'.$item['id'].'" '.$selected.'>'.$item['name_rus'].'</option>';
							endforeach;
						?>
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
					<?php echo $lang['dobavit']; ?>
				</button>
		  </div>
		</div>
	  </div>
	</div>
			
			
	<!-- Modal Insert User-->
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
				<div>
					<label for="school" style="margin-bottom: 1px">учебное заведение</label>
					<div class="row">
						  <div class="col-md-4">
							<select  id="obl_show" class="form-control input-sm">
							<?php 
								$array_obl = get_obl();
								foreach($array_obl as $item):
									if($item['id']==$array_vpz[0]['id_obl']) $selected = 'selected'; else $selected = '';
									echo '<option value="'.$item['id'].'" '.$selected.'>'.$item['name_rus'].'</option>';
								endforeach;
							?>
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
				<button type="button" class="btn btn-default" id="btn_ins"  onclick="insert_user();">
					<?php echo $lang['dobavit']; ?>
				</button>
			</div>	
		  </div>
		</div>
	  </div>
	</div>


	<!-- End Modal Insert User-->
	<!-- Modal update User-->
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
	<!-- End Modal update User -->



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
}
?>
