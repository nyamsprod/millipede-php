#!/usr/bin/env php
<?php

use Millipede\Millipede;
use Millipede\Renderer;

require 'vendor/autoload.php';

$millipede = (new Millipede())
    ->curve(4)
    ->size(10)
    ->comment('Chaud devant! Mon beau millepatte doit passer!')
    ->opposite(true)
    ->reverse(false)
    ->width(7)
    ->skin('\uD83D\uDC1F')
;

echo new Renderer($millipede);

die(0);
