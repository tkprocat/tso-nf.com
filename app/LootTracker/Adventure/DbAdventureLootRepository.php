<?php
namespace LootTracker\Adventure;

use Illuminate\Database\Eloquent\Model;

class DbAdventureLootRepository implements AdventureLootInterface
{
    protected $adventureLoot;

    public function __construct(Model $adventureLoot)
    {
        $this->adventureLoot = $adventureLoot;
    }

    public function all()
    {
        // TODO: Implement all() method.
    }

    public function create($input)
    {
        // TODO: Implement create() method.
    }

    public function find($id)
    {
        // TODO: Implement find() method.
    }
}