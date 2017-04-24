<?php
	include('SendMailSmtpClass.php');
	
	$mailSMTP = new SendMailSmtpClass('probnoe@ncgsot.kz', '123456', 'mail.ncgsot.kz', 'test' ); // создаем экземпл¤р класса
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=utf-8\r\n"; // кодировка письма
	$headers .= "From: <probnoe@ncgsot.kz>\r\n"; // от кого письмо !!! тут e-mail, через который происходит авторизаци¤
	$result =  $mailSMTP->send('sultan_1993@list.ru', 'NCT', 'testtt', $headers); // отправл¤ем письмо
     
	if($result === true){                        
		$res = 'true';            
	}else{
		$res = 'false';
	}
	
	echo $result;
	
?>