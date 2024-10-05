<?php
session_start();
session_destroy();
?>
<script>
    localStorage.removeItem("uri");
    // Sayfanın yönlendirilmesini sağla
    window.location.href = 'index'; // Bu, PHP header yerine kullanılacak
</script>
