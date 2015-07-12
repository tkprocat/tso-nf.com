<?php namespace LootTracker\Repositories\Guild;

interface GuildApplicationInterface
{
    public function all($guild_id);

    public function create($data, $user_id);

    public function byId($guild_id);

    public function delete($guild_id);
}
