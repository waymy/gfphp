<?php

/*
 *Author:gf
 *Time:2016-1-4
 *Note:红包生成随机算法
 */ 

header("Content-type:text/html;charset=utf-8");
date_default_timezone_set('PRC');
echo "<pre>";
#红包生成的算法程序
function RandomSplit($total, $length, $min = 1, $max = 200){
    //如果最小值乘以数目大于总金额 不够分，失败 返回null
	if ($min * $length > $total){
		return false;
	}
	if($total/$length == $max){
		return array_fill(0,$length,$max);
	}
    $r = array();
    $totalR = 0;
    for ($i = 0; $i < $length; $i++){
		$r[$i] = mt_rand(1, 1000);
		$totalR += $r[$i];
    }
	$result = array();
	for ($i = 0; $i < $length; $i++){
		$result[$i] = floor($r[$i] / $totalR * 10000) / 10000;
		$result[$i] = floor($result[$i] * $total * 100) / 100;
	}

	if ($total > array_sum($result)){
		$result[mt_rand(0, $length-1)] += bcsub($total,array_sum($result),2);
	}

	for ($i = 0; $i < $length; $i++){
		if ($result[$i] < $min) $result[$i] = $min;
	}
	rsort($result);
	/*	$result = array
	(
		289.97
		,187.2
		,179.92
		,172.8
		,70.11
	)
	;*/
	$balance = '';
    for ($i = 0; $i < $length; $i++){
	    if (array_sum($result) >= $total && $result[$i] > $min){
			$result[$i] = bcsub($result[$i] , bcsub(array_sum($result),$total,2),2);
			if ($result[$i] < $min){ 
				$result[$i] = $min;
			}else if($result[$i] >= $max){
				$balance += $result[$i] - $max;
				$result[$i] = $max;
			}
		}else{
			$result[$i] = $result[$i]+$balance;	
			if($result[$i] >= $max){
				$balance = $result[$i] - $max;
				$result[$i] = $max;
			}else{
				$balance = '';
			}
		}
	}
	shuffle($result);
	return $result;
}
$arr = RandomSplit(400,5);
print_r($arr);
echo array_sum($arr).'<br>';
