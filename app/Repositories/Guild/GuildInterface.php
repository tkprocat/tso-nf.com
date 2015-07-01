<?php
namespace LootTracker\Repositories\Guild;

interface GuildInterface
{
    public function all();

    public function create($data, $user_id);

    public function byId($guild_id);

    public function byTag($guild_tag);

    public function demoteMember($guild_id, $user_id);

    public function promoteMember($guild_id, $user_id);

    public function addMember($guild_id, $user_id);

    public function removeMember($guild_id, $user_id);

    public function delete($guild_id);

    public function getMembers($guild_id);

    public function getAdmins($guild_id);

    public function addGuildApplication($guild_id, $user_id);
}