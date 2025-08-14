<?php if (!defined('BASEPATH')) exit('NO direct script access allowed');

    function br2nl($pesan) {
        // Mengganti tag HTML <br> dengan karakter newline (\n)
        $pesan = str_ireplace('<br>', "\n", $pesan);
        $pesan = str_ireplace('<br/>', "\n", $pesan);
        $pesan = str_ireplace('<br />', "\n", $pesan);
        
        // Mengembalikan string yang sudah diubah
        return $pesan;
    }
?>