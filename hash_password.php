<?php
$password = '123';
$hash = password_hash($password, PASSWORD_BCRYPT);
echo "Hash for password '$password': $hash\n";
