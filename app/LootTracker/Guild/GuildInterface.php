<?php
namespace LootTracker\Guild;

interface GuildInterface {
    public function all();

    public function create($data);

    public function findPage($page, $guildsPerPage);

    public function findId($id);

    public function demoteMember($guild_id, $user_id);

    public function promoteMember($guild_id, $user_id);

    public function findTag($guild_tag);

    public function addMember($guild_id, $user_id);

    public function removeMember($guild_id, $user_id);

    public function delete($id);

    public function getMembers($id);

    public function getAdmins($id);
}