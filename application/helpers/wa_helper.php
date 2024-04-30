<?php
	if(!defined('BASEPATH')) exit('No direct script access allowed');

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Menentukan Host Wa -----------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------	
	function hostWa($param){
	    if($param == '1'){
	        $devId = 'qgyzytOgRq1q9p3HHLbhunrSGk0ITehZRJ89XYhtxhP8nz1HkovlVLZ60GBYDxd4'; //device ID Admin Storypie
	    }else if($param == '2'){
	        $devId = '';
	    }
	    return $devId;
	}

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
// List Group Id  -----------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------	
	function groupId($nama_group){
	    switch ($nama_group){
	        case "h1Storypie":
	            $groupId = "120363089307111690";
	        break;
	        case "h1allOutlet":
	            $groupId = "628121327228-1623138054";
	        break;
	        
	    }
	    return $groupId;
	}

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
// format pesan -----------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------		
	function waPermintaanMutasi($data=null){
	    $dataWa['image']    = false;
		$dataWa['devId']	= hostWa('1');
		$dataWa['groupId']	= groupId('h1allOutlet');
		$dataWa['pesan']	=   '*NOTIFIKASI SISTEM*'.
                                '<br><br>*PERMINTAAN MUTASI BARANG*'.
                                '<br>---------------------------------------'.
                                '<br>NO FAKTUR : '.$data['no_faktur'].
                                '<br>---------------------------------------'.
                                '<br>DARI : '.$data['nama_dari_gudang'].
                                '<br>KE : '.$data['nama_ke_gudang'].
                                '<br>---------------------------------------'.
                                '<br>TANGGAL DIMINTA : '.$data['tgl_diminta'].
                                '<br>WAKTU DIMINTA : '.$data['waktu_diminta'].
                                '<br>DIMINTA OLEH : '.$data['nama_peminta'].
                                '<br>---------------------------------------'.
                                '<br>LIST BARANG YANG DIMINTA : '.
                                $data['list_barang_diminta'].
                                '<br>'.
                                '<br>KETERANGAN PERMINTAAN : '.$data['keterangan_minta'].
                                '<br><br><br>*HARAP DIPROSES SEGERA, APABILA ADA KENDALA SEGERA HUBUNGI SUPERVISOR TERKAIT*'.
                                '<br>*TERIMAKASIH*';
		sendGroupV2($dataWa);
		return true;
	}
	
	function waPengirimanMutasi($data=null){
	    $dataWa['image']    = false;
		$dataWa['devId']	= hostWa('1');
		$dataWa['groupId']	= groupId('h1allOutlet');
		$dataWa['pesan']	=   '*NOTIFIKASI SISTEM*'.
                                '<br><br>*PENGIRIMAN MUTASI BARANG*'.
                                '<br>---------------------------------------'.
                                '<br>NO FAKTUR : '.$data['no_faktur'].
                                '<br>---------------------------------------'.
                                '<br>DARI : '.$data['nama_dari_gudang'].
                                '<br>KE : '.$data['nama_ke_gudang'].
                                '<br>---------------------------------------'.
                                '<br>TANGGAL DIMINTA : '.$data['tgl_diminta'].
                                '<br>WAKTU DIMINTA : '.$data['waktu_diminta'].
                                '<br>DIMINTA OLEH : '.$data['nama_peminta'].
                                '<br>'.
                                '<br>TANGGAL DIKIRIM : '.$data['tgl_dikirim'].
                                '<br>WAKTU DIKIRIM : '.$data['waktu_dikirim'].
                                '<br>DIKIRIM OLEH : '.$data['nama_pengirim'].
                                '<br>---------------------------------------'.
                                '<br>LIST BARANG YANG DIMINTA : '.
                                $data['list_barang_diminta'].
                                '<br>'.
                                '<br>KETERANGAN PERMINTAAN : '.$data['keterangan_minta'].
                                '<br>---------------------------------------'.
                                '<br>LIST BARANG YANG DIKIRIM : '.
                                $data['list_barang_dikirim'].
                                '<br>'.
                                '<br>KETERANGAN DIKIRIM : '.$data['keterangan_kirim'].
                                '<br><br><br>*HARAP DICEK DENGAN SEGERA, APABILA ADA KENDALA SEGERA HUBUNGI SUPERVISOR TERKAIT*'.
                                '<br>*TERIMAKASIH*';
		sendGroupV2($dataWa);
		return true;
	}
	
	function waPenerimaanMutasi($data=null){
	    $dataWa['image']    = false;
		$dataWa['devId']	= hostWa('1');
		$dataWa['groupId']	= groupId('h1allOutlet');
		$dataWa['pesan']	=   '*NOTIFIKASI SISTEM*'.
                                '<br><br>*PENERIMAAN MUTASI BARANG*'.
                                '<br>---------------------------------------'.
                                '<br>NO FAKTUR : '.$data['no_faktur'].
                                '<br>---------------------------------------'.
                                '<br>DARI : '.$data['nama_dari_gudang'].
                                '<br>KE : '.$data['nama_ke_gudang'].
                                '<br>---------------------------------------'.
                                '<br>TANGGAL DIMINTA : '.$data['tgl_diminta'].
                                '<br>WAKTU DIMINTA : '.$data['waktu_diminta'].
                                '<br>DIMINTA OLEH : '.$data['nama_peminta'].
                                '<br>'.
                                '<br>TANGGAL DIKIRIM : '.$data['tgl_dikirim'].
                                '<br>WAKTU DIKIRIM : '.$data['waktu_dikirim'].
                                '<br>DIKIRIM OLEH : '.$data['nama_pengirim'].
                                '<br>'.
                                '<br>TANGGAL DITERIMA : '.$data['tgl_diterima'].
                                '<br>WAKTU DITERIMA : '.$data['waktu_diterima'].
                                '<br>DITERIMA OLEH : '.$data['nama_penerima'].
                                '<br>---------------------------------------'.
                                '<br>LIST BARANG YANG DIMINTA : '.
                                $data['list_barang_diminta'].
                                '<br>'.
                                '<br>KETERANGAN PERMINTAAN : '.$data['keterangan_minta'].
                                '<br>---------------------------------------'.
                                '<br>LIST BARANG YANG DIKIRIM : '.
                                $data['list_barang_dikirim'].
                                '<br>'.
                                '<br>KETERANGAN DIKIRIM : '.$data['keterangan_kirim'].
                                '<br>---------------------------------------'.
                                '<br>LIST BARANG YANG DITERIMA : '.
                                $data['list_barang_diterima'].
                                '<br>'.
                                '<br>KETERANGAN DITERIMA : '.$data['keterangan_terima'].
                                '<br><br>*HARAP DICEK DENGAN SEGERA, APABILA ADA KENDALA SEGERA HUBUNGI SUPERVISOR TERKAIT*'.
                                '<br>*TERIMAKASIH*';
		sendGroupV2($dataWa);
		return true;
	}
	
	function waMutasiLangsung($data=null){
	    $dataWa['image']    = false;
		$dataWa['devId']	= hostWa('1');
		$dataWa['groupId']	= groupId('h1allOutlet');
		$dataWa['pesan']	=   '*NOTIFIKASI SISTEM*'.
                                '<br><br>*MUTASI BARANG LANGSUNG*'.
                                '<br>---------------------------------------'.
                                '<br>NO FAKTUR : '.$data['no_faktur'].
                                '<br>---------------------------------------'.
                                '<br>DARI : '.$data['nama_dari_gudang'].
                                '<br>KE : '.$data['nama_ke_gudang'].
                                '<br>---------------------------------------'.
                                '<br>TANGGAL MUTASI : '.$data['tgl_dimutasi'].
                                '<br>WAKTU MUTASI : '.$data['waktu_dimutasi'].
                                '<br>DIMUTASI OLEH : '.$data['nama_pemutasi'].
                                '<br>---------------------------------------'.
                                '<br>LIST BARANG YANG DIMUTASI : '.
                                $data['list_barang_dimutasi'].
                                '<br>'.
                                '<br>KETERANGAN MUTASI : '.$data['keterangan_mutasi'].
                                '<br><br><br>*HARAP DICEK DENGAN SEGERA, APABILA ADA KENDALA SEGERA HUBUNGI SUPERVISOR TERKAIT*'.
                                '<br>*TERIMAKASIH*';
		sendGroupV2($dataWa);
		return true;
	}
	
	
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
// fungsi kirim WA -----------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
	function sendPersonalV2($dataSend){
	    $curl = curl_init();
        $token = $dataSend['devId'];
        $random = true;
        if($dataSend['image']){
            $payload = [
                "data" => [
                    [
                        'phone' => $dataSend['penerima'],
                        'image' => $dataSend['image'],
                        'caption' => $dataSend['caption'],
                        'isGroup' => false
                    ]
                ]
            ];
        }else{
            $payload = [
                "data" => [
                    [
                        'phone' => $dataSend['penerima'],
                        'message' => $dataSend['pesan'],
                        'isGroup' => false
                    ]
                ]
            ];   
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                "Authorization: $token",
                "Content-Type: application/json"
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload) );
        if($dataSend['image']){
            curl_setopt($curl, CURLOPT_URL,  "https://jogja.wablas.com/api/v2/send-image");
        }else{
            curl_setopt($curl, CURLOPT_URL,  "https://jogja.wablas.com/api/v2/send-message");    
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        
        $result = curl_exec($curl);
        curl_close($curl);
        echo "<pre>";
        print_r($result);
	}
	
	function sendGroupV2($dataSend){
	    $curl = curl_init();
        $token = $dataSend['devId'];
        $random = true;
        if($dataSend['image']){
            $payload = [
                "data" => [
                    [
                        'phone' => $dataSend['groupId'],
                        'image' => $dataSend['image'],
                        'caption' => $dataSend['caption'],
                        'isGroup' => true
                    ]
                ]
            ];
        }else{
            $payload = [
                "data" => [
                    [
                        'phone' => $dataSend['groupId'],
                        'message' => $dataSend['pesan'],
                        'isGroup' => true
                    ]
                ]
            ];   
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                "Authorization: $token",
                "Content-Type: application/json"
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload) );
        if($dataSend['image']){
            curl_setopt($curl, CURLOPT_URL,  "https://jogja.wablas.com/api/v2/send-image");
        }else{
            curl_setopt($curl, CURLOPT_URL,  "https://jogja.wablas.com/api/v2/send-message");    
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        
        $result = curl_exec($curl);
        curl_close($curl);
        echo "<pre>";
        print_r($result);
	}
	
	function sendPersonalV2Eu($dataSend){
	    $curl = curl_init();
        $token = $dataSend['devId'];
        $random = true;
        if($dataSend['image']){
            $payload = [
                "data" => [
                    [
                        'phone' => $dataSend['penerima'],
                        'image' => $dataSend['image'],
                        'caption' => $dataSend['caption'],
                        'isGroup' => false
                    ]
                ]
            ];
        }else{
            $payload = [
                "data" => [
                    [
                        'phone' => $dataSend['penerima'],
                        'message' => $dataSend['pesan'],
                        'isGroup' => false
                    ]
                ]
            ];   
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                "Authorization: $token",
                "Content-Type: application/json"
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload) );
        if($dataSend['image']){
            curl_setopt($curl, CURLOPT_URL,  "https://eu.wablas.com/api/v2/send-image");
        }else{
            curl_setopt($curl, CURLOPT_URL,  "https://eu.wablas.com/api/v2/send-message");    
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        
        $result = curl_exec($curl);
        curl_close($curl);
        echo "<pre>";
        print_r($result);
	}
	
	function sendWa($dataSend){
	    $token = $dataSend['devId'];
	    $phone = $dataSend['groupId'];
	    $message = $dataSend['pesan'];
		$result = file_get_contents("https://jogja.wablas.com/api/send-message?phone=$phone&message=$message&token=$token&isGroup='true'");

		return true;
	}
?> 