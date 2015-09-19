<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-2-5
 * Time: 上午11:47
 */

function resultByFront($test) {
    $front = D("Front");
    $fid = 1;
    $resultByFront = $front -> where('fid = '.$fid) -> find();
    $test -> assign('resultByFront', $resultByFront);

    $test -> assign('id', $_SESSION['id']);
    $test -> assign('name', $_SESSION['name']);
    $test -> assign('role', $_SESSION['role']);
}