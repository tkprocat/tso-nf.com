<?php
namespace LootTracker\Repositories\Validation;

interface ValidableInterface
{
    public function with(array $input);

    public function passes();

    public function errors();
}
