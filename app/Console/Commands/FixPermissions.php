<?php namespace LootTracker\Console\Commands;

use DB;
use Illuminate\Console\Command;
use LootTracker\Repositories\Guild\Guild;
use LootTracker\Repositories\User\Role;
use LootTracker\Repositories\User\User;

class FixPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lt:fix-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add basic entrust permissions to users.';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::all();
        $adminRole = Role::whereName('admin')->first();
        $userRole = Role::whereName('user')->first();
        $guildMemberRole = Role::whereName('guild_member')->first();
        $guildAdminRole = Role::whereName('guild_admin')->first();

        foreach($users as $user)
        {
            //Add basic permissions
            if (!$user->hasRole('user'))
              $user->attachRole($userRole);

            //Add guild membership
            if (($user->guild_id > 0) && (!$user->hasRole('guild_member')))
                $user->attachRole($guildMemberRole);
        }

        //Fix guild admin permissions
        $guildAdminGroups = DB::table('groups')->where('name', 'like', '%_Admins')->get();

        foreach($guildAdminGroups as $group)
        {
            $usersInGroup = DB::table('users_groups')->where('group_id', $group->id)->get();
            foreach($usersInGroup as $guildAdmin)
            {
                $tempUser = User::find($guildAdmin->user_id);
                if (($tempUser != null) && (!$tempUser->hasRole('guild_admin')))
                    $tempUser->attachRole($guildAdminRole);
            }
        }

        //Promote the last member in guilds
        $guilds = Guild::all();
        foreach($guilds as $guild)
        {
            if ($guild->members()->count() == 1)
            {
                $tempUser = $guild->members()->first();
                if (!$tempUser->hasRole('guild_admin'))
                    $tempUser->attachRole($guildAdminRole);
            }
        }

        //Add admins
        $user = User::whereUsername('Procat')->first();
        if (!$user->hasRole('admin'))
            $user->attachRole($adminRole);

        $user = User::whereUsername('Notious')->first();
        if (!$user->hasRole('admin'))
            $user->attachRole($adminRole);
    }
}
