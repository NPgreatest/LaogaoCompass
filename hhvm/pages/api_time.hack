<?hh // strict

\header('Content-Type: application/json');
echo \json_encode(varray['time' => \date('c')]);
