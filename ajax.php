<?php
include_once 'front.php';
$act=$_GET['act'];
switch($act){
    case 'pdSearch':
        $hint=trim(strip_tags($_GET['hint']));
        $list=$db->where('active',1)->where('title','%'.$hint.'%','LIKE')
        ->where('code','%'.$hint.'%','LIKE','OR')
        ->orderBy('id')->get('product',10,'id,code,title');        
        echo json_encode($list);
        break;
    default:
        break;
}
?>