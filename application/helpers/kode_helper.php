<?php 

function ambil_bulan(){
	if (date("m") == "01"){
		$bulan = "I";
	}else if (date("m") == "02"){
		$bulan = "II";
	}else if (date("m") == "03"){
		$bulan = "III";
	}else if (date("m") == "04"){
		$bulan = "IV";
	}else if (date("m") == "05"){
		$bulan = "V";
	}else if (date("m") == "06"){
		$bulan = "VI";
	}else if (date("m") == "07"){
		$bulan = "VII";
	}else if (date("m") == "08"){
		$bulan = "VIII";
	}else if (date("m") == "09"){
		$bulan = "IX";
	}else if (date("m") == "10"){
		$bulan = "X";
	}else if (date("m") == "11"){
		$bulan = "XI";
	}else if (date("m") == "12"){
		$bulan = "XII";
	}
			
	return $bulan;
}

function ambil_tahun(){
	$tahun = date("Y");
	
	return $tahun;
}