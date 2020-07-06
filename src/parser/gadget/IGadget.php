<?php

namespace SheDied\parser\gadget;

interface IGadget
{

    public function getBrand();

    public function setBrand($brand);

    public function getModel();

    public function setModel($model);

    public function getProductLink();

    public function setProductLink($link);

    public function getProductPhotos();

    public function setProductPhotos($photos);

    public function specsTable();

    public function setProductSpecs($specs);

    public function getProductSpecs();

    public function setProductDesc($desc);

    public function getProductDesc();

    public function setProductSupport($param);

    public function getProductSupport();
}
