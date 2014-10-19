<?php
$str = file_get_contents("./data/tree/tree_1.txt");

$array = unserialize($str);

echo '<pre>';

print_r($array);