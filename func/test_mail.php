<?php
	include('SendMailSmtpClass.php');
	
	$mailSMTP = new SendMailSmtpClass('probnoe@ncgsot.kz', '123456', 'mail.ncgsot.kz', 'test' ); // ������� �������� ������
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=utf-8\r\n"; // ��������� ������
	$headers .= "From: <probnoe@ncgsot.kz>\r\n"; // �� ���� ������ !!! ��� e-mail, ����� ������� ���������� ����������
	$result =  $mailSMTP->send('sultan_1993@list.ru', 'NCT', 'testtt', $headers); // ��������� ������
     
	if($result === true){                        
		$res = 'true';            
	}else{
		$res = 'false';
	}
	
	echo $result;
	
?>