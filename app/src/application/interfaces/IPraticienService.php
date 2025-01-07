<?php

namespace toubeelib\application\interfaces;

use toubeelib\application\dto\PraticienDTO;

interface IPraticienService
{
    public function getPraticien(string $id): PraticienDTO;
    public function getAllPraticiens(): array;
}
