<?php
/*
example
$int = 12;
$max = 20;
$min = 10;

$normalized = normalize($int, $min, $max);
echo $normalized . "\n";
echo denormalize($normalized, $min, $max);
*/

function normalize($value, $min, $max) {
	$normalized = ($value - $min) / ($max - $min);
	return $normalized;
}

function denormalize($normalized, $min, $max) {
	$denormalized = ($normalized * ($max - $min) + $min);
	return $denormalized;
}