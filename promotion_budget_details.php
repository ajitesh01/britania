<?php
include "init.php";

function insert() {
    $model = new PromotionBudgetDetailsModel();
    $model->doInsert();
}

function readAll($args) {
    $model = new PromotionBudgetDetailsModel();
    $model->readAll($args);
}

function readById($args) {
    $model = new PromotionBudgetDetailsModel();
    $model->readById($args);
}