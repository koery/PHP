<?php
$a = json_encode(['a' => 'aaa','b' => 'bbb']);
echo
"<script>
var a = $a;
</script>";

include('ajax.html');