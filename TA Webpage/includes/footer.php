<?php
// Programmer Name : 64
// footer of the page
    // close db connection once page is loaded
    if(isset($conn)){
        $conn->close();
    }
?>
</main>
</body>
<script src="./js/script.js"></script>
</html>
