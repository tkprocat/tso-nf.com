<?php namespace LootTracker\Console\Commands;

use App;
use Illuminate\Console\Command;
use LootTracker\Repositories\Adventure\AdventureLoot;
use LootTracker\Repositories\Item\Admin\AdminItemInterface;
use LootTracker\Repositories\Item\Item;

class GenerateItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lt:generate-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates items from old data.';

    /**
     * @var AdminItemInterface
     */
    protected $itemAdminRepo;


    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {

        parent::__construct();
        $this->itemAdminRepo = App::make(AdminItemInterface::class);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $adventureLootItems = AdventureLoot::all();
        foreach($adventureLootItems as $adventureLootItem)
        {
            $item = Item::where('name', e($adventureLootItem->type_old))->first();
            //Check if we found anything.
            if ($item == null)
                $item = $this->itemAdminRepo->create(['name' => $adventureLootItem->type_old, 'category' => 'Unknown', 'user_id' => '1']);
            $adventureLootItem->item_id = $item->id;
            $adventureLootItem->save();
        }
    }
}
