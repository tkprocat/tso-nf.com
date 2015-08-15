<?php

namespace LootTracker\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use LootTracker\Repositories\Adventure\Adventure;
use LootTracker\Repositories\Loot\UserAdventure;

class UpdateEstimatedAdventureValue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lt:updated-estimated-adventure-value';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
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
        $this->comment('Updating the estimated value of all adventures.');
        $adventures = Adventure::with('played')->orderBy('name')->get();
        foreach($adventures as $adventure)
        {
            $value = 0;
            $done = 0;
            $this->info('Processing: '.$adventure->name);
            $count = UserAdventure::whereAdventureId($adventure->id)->count();
            UserAdventure::with('loot.loot.item.currentPrice')->whereAdventureId($adventure->id)->chunk(100, function($userAdventures) use (&$value, &$count, &$done){
                foreach($userAdventures as $userAdventure) {
                    $done++;
                    if ($done % 100 == 0)
                        $this->comment($done.'/'.$count);
                    $value += $userAdventure->getEstimatedLootValue();
                }
            });
            if ($count > 0) {
                $value = $value / $count;
                //If the new estimated value is different, save the new value.
                if ($adventure->estimated_value !== $value) {
                    $adventure->estimated_value = $value;
                    $adventure->save();
                }
                $this->info(' Value: ' . $value);
            }
        }
    }
}
