<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
function now()
{
    date_default_timezone_set("Asia/Jakarta");
	return date("Y-m-d H:i:s");
}

function date_now()
{
    date_default_timezone_set("Asia/Jakarta");
	return date("d-m-Y");
}

function date_now_db()
{
    date_default_timezone_set("Asia/Jakarta");
	return date("Y-m-d");
}

function waktu_24($date){
    $waktu   = date('H:i:s', strtotime($date));
    return $waktu;
}

function all_hari($num)
{
	$hari = array('', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu');
	return $hari[$num];
}

function all_bulan()
{
// 	return array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
    return array(
        array('id' => 1, 'nama' => 'JANUARI'),
        array('id' => 2, 'nama' => 'FEBRUARI'),
        array('id' => 3, 'nama' => 'MARET'),
        array('id' => 4, 'nama' => 'APRIL'),
        array('id' => 5, 'nama' => 'MEI'),
        array('id' => 6, 'nama' => 'JUNI'),
        array('id' => 7, 'nama' => 'JULI'),
        array('id' => 8, 'nama' => 'AGUSTUS'),
        array('id' => 9, 'nama' => 'SEPTEMBER'),
        array('id' => 10, 'nama' => 'OKTOBER'),
        array('id' => 11, 'nama' => 'NOVEMBER'),
        array('id' => 12, 'nama' => 'DESEMBER')
    );
}

function all_bulan_short()
{
	return array('', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Okt', 'Nov', 'Des');
}

function generate_years($start_year)
{
    $current_year = intval(date('Y')); // Get the current year as an integer
    $years = array();
    if ($start_year <= $current_year) {
        for ($year = $current_year; $year >= $start_year; $year--) {
            $years[] = $year;
        }
    }
    return $years;
}

function tglwaktu_indo($date, $day = FALSE)
{
	$all_bulan     = all_bulan();
	$tanggal = date('d', strtotime($date));
	$bulan   = date('m', strtotime($date));
	$tahun   = date('Y', strtotime($date));
	$waktu   = date('H:i:s', strtotime($date));
	if ($day) {
		$hari = all_hari(date('N', strtotime($date)));
		return $hari . ', ' . $tanggal . '-' . $bulan . '-' . $tahun;
	}
	return $tanggal . '-' . $bulan . '-' . $tahun . '&nbsp;&nbsp;&nbsp;' . $waktu;
}

function indo_dates($date, $day = FALSE)
{
	$all_bulan     = all_bulan();
	$tanggal = date('d', strtotime($date));
	$bulan   = $all_bulan[date('n', strtotime($date))];
	$tahun   = date('Y', strtotime($date));
	if ($day) {
		$hari = all_hari(date('N', strtotime($date)));
		return $hari . ', ' . $tanggal . '-' . $bulan . '-' . $tahun;
	}
	return $tanggal . ' ' . $bulan . ' ' . $tahun;
}

function tgl_indo($date)
{
	$tanggal = date('d-m-Y', strtotime($date));
	return $tanggal;
}

function date_db_format($tgl)
{
	return date('Y-m-d', strtotime($tgl));
}

function datetime_db_format($tgl)
{
	return date('Y-m-d h:i:s', strtotime($tgl));
}

function date_view_format($tgl)
{
	if ($tgl == '0000-00-00') {
		return '00-00-0000';
	}
	return date('d-m-Y', strtotime($tgl));
}

function getRangeWeekMonth($year, $month, $week)
{

	$thisWeek = 1;

	for ($i = 1; $i < $week; $i++) {
		$thisWeek = $thisWeek + 7;
	}

	$currentDay = date('Y-m-d', mktime(0, 0, 0, $month, $thisWeek, $year));

	$sunday = strtotime('sunday this week', strtotime($currentDay));
	$saturday = strtotime('saturday next week', strtotime($currentDay));

	$data = [
		'year' => $year,
		'month' => $month,
		'week' => $week,
		'week_start' => date('Y-m-d', $sunday),
		'week_end' => date('Y-m-d', $saturday)
	];

	return $data;
}

function hariIndo($hariInggris)
{
	switch ($hariInggris) {
		case 'Sunday':
			return 'Minggu';
		case 'Monday':
			return 'Senin';
		case 'Tuesday':
			return 'Selasa';
		case 'Wednesday':
			return 'Rabu';
		case 'Thursday':
			return 'Kamis';
		case 'Friday':
			return 'Jumat';
		case 'Saturday':
			return 'Sabtu';
		default:
			return 'hari tidak valid';
	}
}

function bulan_indo($num)
{
	if ($num == 1) {
		$month_name = 'Januari';
	} else if ($num == 2) {
		$month_name = 'Februari';
	} else if ($num == 3) {
		$month_name = 'Maret';
	} else if ($num == 4) {
		$month_name = 'April';
	} else if ($num == 5) {
		$month_name = 'Mei';
	} else if ($num == 6) {
		$month_name = 'Juni';
	} else if ($num == 7) {
		$month_name = 'Juli';
	} else if ($num == 8) {
		$month_name = 'Agustus';
	} else if ($num == 9) {
		$month_name = 'September';
	} else if ($num == 10) {
		$month_name = 'Oktober';
	} else if ($num == 11) {
		$month_name = 'November';
	} else if ($num == 12) {
		$month_name = 'Desember';
	}
	return $month_name;
}

function time_passed($timestamp)
{
	//type cast, current time, difference in timestamps
	$timestamp      = (int) strtotime($timestamp);
	$current_time   = time();
	$diff           = $current_time - $timestamp;

	//intervals in seconds
	$intervals      = array(
		'year' => 31556926, 'month' => 2629744, 'week' => 604800, 'day' => 86400, 'hour' => 3600, 'minute' => 60
	);

	//now we just find the difference
	if ($diff == 0) {
		return 'just now';
	}

	if ($diff < 60) {
		return $diff == 1 ? $diff . ' second ago' : $diff . ' seconds ago';
	}

	if ($diff >= 60 && $diff < $intervals['hour']) {
		$diff = floor($diff / $intervals['minute']);
		return $diff == 1 ? $diff . ' minute ago' : $diff . ' minutes ago';
	}

	if ($diff >= $intervals['hour'] && $diff < $intervals['day']) {
		$diff = floor($diff / $intervals['hour']);
		return $diff == 1 ? $diff . ' hour ago' : $diff . ' hours ago';
	}

	if ($diff >= $intervals['day'] && $diff < $intervals['week']) {
		$diff = floor($diff / $intervals['day']);
		return $diff == 1 ? $diff . ' day ago' : $diff . ' days ago';
	}

	if ($diff >= $intervals['week'] && $diff < $intervals['month']) {
		$diff = floor($diff / $intervals['week']);
		return $diff == 1 ? $diff . ' week ago' : $diff . ' weeks ago';
	}

	if ($diff >= $intervals['month'] && $diff < $intervals['year']) {
		$diff = floor($diff / $intervals['month']);
		return $diff == 1 ? $diff . ' month ago' : $diff . ' months ago';
	}

	if ($diff >= $intervals['year']) {
		$diff = floor($diff / $intervals['year']);
		return $diff == 1 ? $diff . ' year ago' : $diff . ' years ago';
	}
}

function countDays($start, $end)
{
	$date1 = new DateTime(date('Y-m-d H:i', strtotime($start)));
	$date2 = new DateTime(date('Y-m-d H:i', strtotime($end)));
	$interval = $date1->diff($date2);
	$countText = '';
	if ($interval->y)
		$countText .= $interval->y . ' Thn ';
	if ($interval->m)
		$countText .= $interval->m . ' Bln ';
	if ($interval->d)
		$countText .= $interval->d . ' hari ';
	if ($interval->h)
		$countText .= $interval->h . ' jam ';
	return $countText;
}

function masaKerja($masuk)
{
	$old = new DateTime($masuk);
	$present = new DateTime('now');
	$interval = $present->diff($old);

	echo $interval->format('%Y Tahun %M Bulan %d Hari');
}

function masaKerjaBulan($masuk)
{

	$date = date("Y-m-d");
	$timeStart = strtotime($masuk);
	$timeEnd = strtotime("$date");
	// Menambah bulan ini + semua bulan pada tahun sebelumnya
	$numBulan = 1 + (date("Y", $timeEnd) - date("Y", $timeStart)) * 12;
	// menghitung selisih bulan
	$numBulan += date("m", $timeEnd) - date("m", $timeStart);

	return $numBulan;
}
