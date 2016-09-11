<?php
//使用ob缓存
ob_start();
echo "hello,world";

$data = ob_get_contents();
//ob_end_clean();

echo $data;
//file_put_contents('ob.txt', $data);