<?php namespace LootTracker\Repositories\Guild;

interface GuildApplicationInterface
{
    public function all($guild_id);

    public function byId($application_id);

    public function create($data, $user_id, $guild_id);

    public function delete($application_id);

    public function approve($application_id);

    public function decline($application_id);
}
