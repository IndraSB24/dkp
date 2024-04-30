<?php 
	/**
	 * Grant Access Menu By Kelas Anggota
	 */

	function activeId(){
		$CI = get_instance();
		return $CI->session->userdata('activeUserId');
	}
	
	function activeNama(){
	    $CI = get_instance();
		return $CI->session->userdata('act_nama');
	}
	
	function hak_akses(){
		$CI = get_instance();
		return $CI->session->userdata('login_type');
	}
	
	function role(){
		$CI = get_instance();
		return $CI->session->userdata('id_role');
	}
	
	function active_role(){
		$CI = get_instance();
		$kode_role = $CI->session->userdata('id_role');
		
		switch ($kode_role) {
    		case 0:
                return "super_admin";
                break;
            case 1:
                return "admin_entitas";
                break;
            case 2:
                return "spv_outlet";
                break;
            case 3:
                return "spv_gudang";
                break;
            case 4:
                return "finance_staff";
                break;
            case 5:
                return "entitas_vendor";
                break;
            case 6:
                return "manager";
                break;
            default:
            return "unknown_role";
        }
	}
	
	function activeOutlet(){
		$CI = get_instance();
		return $CI->session->userdata('id_gudang');
	}
	
	function activeKota(){
		$CI = get_instance();
		return $CI->session->userdata('id_kota');
	}
	
	function activeEntitasType(){
		$CI = get_instance();
		return $CI->session->userdata('entitas_type');
	}
	
	function activeLokasi(){
	    $CI = get_instance();
	    $hak_akses = $CI->session->userdata('login_type');
	    if(substr($hak_akses,0,3)=="SPV"){
            $lokasi = substr($hak_akses,4);
        }else if(substr($hak_akses,0,3)=="ADM"){
            $lokasi = substr($hak_akses,6);
        }
        return $lokasi;
	}
	
	function isManager(){
		$CI = get_instance();
		return $CI->session->userdata('login_type')=='MANAGER' ? TRUE : FALSE;
	}
	
	function isAdmin(){
		$CI = get_instance();
		return $CI->session->userdata('login_type')=='ADMINISTRATOR' ? TRUE : FALSE;
	}
	
	function isAdminSupport(){
		$CI = get_instance();
		return $CI->session->userdata('login_type')=='ADMIN SUPPORT' ? TRUE : FALSE;
	}
	
	function isAdminGudangPekanbaru(){
		$CI = get_instance();
		return $CI->session->userdata('login_type')=='ADMIN GUDANG PEKANBARU' ? TRUE : FALSE;
	}
	
	function isAdminGudangMedan(){
		$CI = get_instance();
		return $CI->session->userdata('login_type')=='ADMIN GUDANG MEDAN' ? TRUE : FALSE;
	}
	
	function isAdminProduksiPekanbaru(){
		$CI = get_instance();
		return $CI->session->userdata('login_type')=='ADMIN PRODUKSI PEKANBARU' ? TRUE : FALSE;
	}
	
	function isSpvOutletPekanbaru(){
		$CI = get_instance();
		return $CI->session->userdata('login_type')=='SPV OUTLET PEKANBARU' ? TRUE : FALSE;
	}
	
	function isSpvOutletMedan(){
		$CI = get_instance();
		return $CI->session->userdata('login_type')=='SPV OUTLET MEDAN' ? TRUE : FALSE;
	}
	
	function isSpvGudangPekanbaru(){
		$CI = get_instance();
		return $CI->session->userdata('login_type')=='SPV GUDANG PEKANBARU' ? TRUE : FALSE;
	}
	
	function isSpvGudangMedan(){
		$CI = get_instance();
		return $CI->session->userdata('login_type')=='SPV GUDANG MEDAN' ? TRUE : FALSE;
	}
	
	function isAdminOutletDurian(){
		$CI = get_instance();
		return $CI->session->userdata('login_type')=='ADMIN OUTLET DURIAN' ? TRUE : FALSE;
	}
	
	function isAdminOutletRiau(){
		$CI = get_instance();
		return $CI->session->userdata('login_type')=='ADMIN OUTLET RIAU' ? TRUE : FALSE;
	}
	
	function isAdminOutletPanam(){
		$CI = get_instance();
		return $CI->session->userdata('login_type')=='ADMIN OUTLET PANAM' ? TRUE : FALSE;
	}
	
	function isAdminOutletSumatera(){
		$CI = get_instance();
		return $CI->session->userdata('login_type')=='ADMIN OUTLET SUMATERA' ? TRUE : FALSE;
	}
	
	function isAdminOutletGumik(){
		$CI = get_instance();
		return $CI->session->userdata('login_type')=='ADMIN OUTLET GUMIK' ? TRUE : FALSE;
	}
	
	function isAdminOutletIbnu(){
		$CI = get_instance();
		return $CI->session->userdata('login_type')=='ADMIN OUTLET IBNU' ? TRUE : FALSE;
	}
	
	function isAdminOutletDelima(){
		$CI = get_instance();
		return $CI->session->userdata('login_type')=='ADMIN OUTLET DELIMA' ? TRUE : FALSE;
	}
	
	function isAdminOutletCookies(){
		$CI = get_instance();
		return $CI->session->userdata('login_type')=='ADMIN OUTLET COOKIES' ? TRUE : FALSE;
	}
	
	function isAdminOutletBusan(){
		$CI = get_instance();
		return $CI->session->userdata('login_type')=='ADMIN OUTLET BUSAN' ? TRUE : FALSE;
	}
	
	function isAdminOutletWahidhasyim(){
		$CI = get_instance();
		return $CI->session->userdata('login_type')=='ADMIN OUTLET WAHID HASYIM' ? TRUE : FALSE;
	}
	
	function isAdminOutletMarpoyan(){
		$CI = get_instance();
		return $CI->session->userdata('login_type')=='ADMIN OUTLET MARPOYAN' ? TRUE : FALSE;
	}
