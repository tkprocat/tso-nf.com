<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use LootTracker\Repositories\User\Permission;
use LootTracker\Repositories\User\Role;
use LootTracker\Repositories\User\User;

class EntrustSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        //Default user role
        $userRole = new Role(array('name' => 'user', 'display_name' => 'User'));
        $userRole->save();

        $canSeeLoot = new Permission(array('name' => 'see-loot', 'display_name' => 'Can see loot/stat'));
        $canSeeLoot->save();
        $userRole->attachPermission($canSeeLoot);

        $canAddLootPerm = new Permission(array('name' => 'add-loot', 'display_name' => 'Can add loot'));
        $canAddLootPerm->save();
        $userRole->attachPermission($canAddLootPerm);

        //Admin related roles/permissions
        $adminRole = new Role(array('name' => 'admin', 'display_name' => 'Admin'));
        $adminRole->save();

        $adminPerm = new Permission(array('name' => 'admin', 'display_name' => 'Admin'));
        $adminPerm->save();
        $adminRole->attachPermission($adminPerm);

        //Blog related roles/permissions
        $adminBlogPerm = new Permission(array('name' => 'admin-blog', 'display_name' => 'Administrate blog'));
        $adminBlogPerm->save();
        $adminRole->attachPermission($adminBlogPerm);

        $postBlogCommentPerm = new Permission(array('name' => 'post-blog-comment', 'display_name' => 'Can post/edit blog comments'));
        $postBlogCommentPerm->save();
        $userRole->attachPermission($adminBlogPerm);

        //Guild related roles/permissions
        $guildAdminRole = new Role(array('name' => 'guild_admin', 'display_name' => 'Guild admin'));
        $guildAdminRole->save();
        $guildMemberRole = new Role(array('name' => 'guild_member', 'display_name' => 'Guild member'));
        $guildMemberRole->save();

        $guildAdminGuildPerm = new Permission(array(
            'name' => 'admin-guild',
            'display_name' => 'Can update various guild info'
        ));
        $guildAdminGuildPerm->save();
        $guildAdminRole->attachPermission($guildAdminGuildPerm);

        $guildAdminGuildMembersPerm = new Permission(array(
            'name' => 'admin-guild-members',
            'display_name' => 'Can add/remove/promote/demote members'
        ));
        $guildAdminGuildMembersPerm->save();
        $guildAdminRole->attachPermission($guildAdminGuildMembersPerm);

        //Price related roles/permissions
        $pricesAdminRole = new Role(array('name' => 'prices_admin', 'display_name' => 'Pricelist admin'));
        $pricesAdminRole->save();
        $canSeePrices = new Permission(array('name' => 'see-prices', 'display_name' => 'Can see prices'));
        $canSeePrices->save();
        $userRole->attachPermission($canSeePrices);

        $canAddPricesPerm = new Permission(array('name' => 'add-prices', 'display_name' => 'Can add/update prices'));
        $canAddPricesPerm->save();
        $pricesAdminRole->attachPermission($canAddPricesPerm);
    }
}