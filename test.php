<?php
include "init.php";

function main() {
    $testModel = new TestModel();
    $testModel->sampleFun();
}

function dosubmit($args) {
    $testModel = new TestModel();
    $testModel->sampleFunCount($args);
}

function appDetails() {
    $testModel = new TestModel();
    $testModel->appDetails();
}

