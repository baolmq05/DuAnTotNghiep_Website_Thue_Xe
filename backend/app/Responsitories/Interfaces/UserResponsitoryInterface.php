<?php
namespace App\Responsitories\Interfaces;

interface UserResponsitoryInterface
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
}