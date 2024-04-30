<?php
    
    function rupiah($nominal){
        return 'Rp. '.number_format($nominal, 2, ',', '.');
    }
    
    function thousand_separator($nominal){
        return number_format($nominal, 0, ',', '.');
    }
    
    function thousand_separator_international($nominal){
        return number_format($nominal, 0, '.', ',');
    }
    
    function no_thousand_separator($number) {
        return str_replace('.', '', $number);
    }
    
    function currency_indo($nominal){
        return number_format($nominal, 2, ',', '.');
    }
?>