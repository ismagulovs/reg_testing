<div class="col-md-12">
<div class="panel panel-default">
<div class="panel-heading" style="background-color:#888;color:#fff;"><a class="btn btn-warning" href="?home" >&larr;</a></div> 
<div class="panel-body">
<?php
$pol = [
	null => '<select class="form-control" id="pol"><option> Пол </option>  <option value="1"> Мужской </option> <option value="2"> Женский </option> </select>',
	1 => '<select class="form-control" id="pol"><option value="1"> Мужской </option> <option value="2"> Женский </option> </select>',
	2 => '<select class="form-control" id="pol"><option value="2"> Женский </option> <option value="1"> Мужской </option> </select>',
];
$type_schol = [
	null => '<select class="form-control" id="typ_sch"><option> Тип школы </option>  <option value="1"> Городская </option> <option value="2"> Сельская </option> </select>',
	1 => '<select class="form-control" id="typ_sch"><option value="1"> Городская </option> <option value="2"> Сельская </option> </select>',
	2 => '<select class="form-control" id="typ_sch"><option value="2"> Сельская </option> <option value="1"> Городская </option> </select>',
];
$sql = "SELECT * FROM public.user where id_user = ".$_SESSION['user'].";";
$res = pg_query( $sql ); 
$item = pg_fetch_array( $res );
if($_SESSION['id_admin'] == $item['id_vpz'] && $_SESSION['id_school'] == $item['id_school']){
?>
<table class="table table-bordered">
<tr><td><?php echo $lang['iin'];?></td><td><input type="text" id="up_iin" value="<?php echo $item['iin']; ?>" class="form-control" required></td></tr>
<tr><td><?php echo $lang['fio'];?></td><td><input type="text" id="up_fio" value="<?php echo $item['fio']; ?>" class="form-control" required></td></tr>
<tr><td><?php echo $lang['pol'];?></td><td><?php echo $pol[$item['pol']]; ?></tr>
<tr><td><?php echo $lang['god_roj'];?></td><td><input type="number" min="1930" max="2000" id="god_roj" value="<?php echo $item['god_roj']; ?>" class="form-control" required></tr>
<?php 
$query="select * from school where id_school = ".$item['id_school'].";";
$result = pg_query($query);
$itm = pg_fetch_array($result);
?>
<tr><td><?php echo $lang['rabot'];?></td><td><?php echo $itm['NameRus']; ?></td></tr>
<tr><td><?php echo $lang['typ_sch'];?></td><td><?php echo $type_schol[$item['typ_sch']]; ?></td></tr>
<tr><td><?php echo $lang['kat'];?></td><td><select class="form-control" id="categ" name="categ">
<?php 
	if($item['categ'] != null){
	$sql_cat="select * from sprav_category where id_сat =".$item['categ'].";";
	$res_cat = pg_query($sql_cat);
	$itm_cat = pg_fetch_array( $res_cat );
		echo '<option value='.$itm_cat['id_сat'].'>'.$itm_cat['name_rus'].'</option>';
    $sql_cat_all="select * from sprav_category where id_сat not in (".$item['categ']."); ";
	}else{
	$sql_cat_all="select * from sprav_category;";
	}
	$res_cat_all = pg_query($sql_cat_all);
	while($itm_cat_all = pg_fetch_array($res_cat_all)){
		echo '<option value='.$itm_cat_all['id_сat'].'>'.$itm_cat_all['name_rus'].'</option>';
	}
?>
		</select></td></tr>
<tr><td><?php echo $lang['spec']; ?></td><td><input type="text" id="special" value="<?php echo $item['special']; ?>" class="form-control" required></td></tr>
<tr><td><?php echo $lang['god_end']; ?></td><td><input type="number" min="1930" max="2015" id="god_end" class="form-control" value="<?php echo $item['god_end']; ?>" required></td></tr>
<tr><td rowspan="2"><?php echo $lang['staj']; ?></td><td>  <input type="number" min="1" max="100"  id="staj" class="form-control" value="<?php echo $item['staj']; ?>" required placeholder="_ _ <?php echo $lang['staj1']; ?>" ></td></tr>
<tr><td> <input type="number" min="1" max="100" id="staj_mes" class="form-control" value="<?php echo $item['staj_mes']; ?>" required placeholder="_ _ <?php echo $lang['staj2']; ?>" ></td></tr>
<tr><td>Пароль</td><td><input type="text" id="password" class="form-control" value="<?php echo $item['password']; ?>" required></td></tr>
<tr><td><?php echo $lang['kol-vo']; ?></td><td><input type="number" id="sum_try" class="form-control" value="<?php echo $item['sum_try']; ?>" required></td></tr>
</table>
<input type="submit" class="btn btn-primary" onclick="update(<?php echo $_SESSION['user']; ?>);"><div id="test"></div>
<div id="stat_user" style="display:<?php if($item['ready'] == 1){ echo 'block;'; }else{ echo 'none;'; }?>"><a href="print.php?print=<?php echo $_SESSION['user'];?>"> <span class="glyphicon glyphicon-floppy-disk"> </span> </a> </div>
<?php 
}else{
echo 'error';
}
?>
</div>
</div>
</div>
