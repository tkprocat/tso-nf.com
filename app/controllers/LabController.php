<?php
class LabController extends BaseController
{
    protected $layout = 'layouts.default';

    public function labs()
    {
        return View::make('labs.index');
    }

    public function lab3() {
        $lootTypes = AdventureLoot::distinct('type')->orderBy('type')->lists('type');
        return View::make('labs.lab3', compact('lootTypes'));
    }

    public function getTop10BestAdventuresForLootTypeByAvgDrop(){
        $data = Input::all();
        $type = $data['type'];
        $result = DB::table('adventure_loot')
            ->join('user_adventure_loot', 'adventure_loot.id', '=', 'user_adventure_loot.adventure_loot_id')
            ->join('adventure', 'adventure.id', '=', 'adventure_loot.adventure_id')
            ->select(array('adventure.name', 'adventure_loot.type', DB::raw('((COUNT(\'' . \DB::getTablePrefix() . 'user_adventure_loot.*\' ) * ' . \DB::getTablePrefix() . 'adventure_loot.Amount) DIV (SELECT count( * ) FROM ' . \DB::getTablePrefix() . 'user_adventure WHERE adventure_id = ' . \DB::getTablePrefix() . 'adventure_loot.adventure_id GROUP BY adventure_id )) AS avg_drop')))
            ->where('type', $type)
            ->groupBy('adventure_loot.adventure_id')
            ->orderBy('avg_drop', 'desc')->take(10)->get();
        return Response::json($result);
    }

    public function getTop10BestAdventuresForLootTypeByDropChance(){
        $data = Input::all();
        $type = $data['type'];
        $result = DB::table('adventure_loot')
            ->join('user_adventure_loot', 'adventure_loot.id', '=', 'user_adventure_loot.adventure_loot_id')
            ->join('adventure', 'adventure.id', '=', 'adventure_loot.adventure_id')
            ->select(array('adventure.name', 'adventure_loot.type', DB::raw('(COUNT(\'' . \DB::getTablePrefix() . 'user_adventure_loot.*\' ) / (SELECT count( * ) FROM ' . \DB::getTablePrefix() . 'user_adventure WHERE AdventureID = ' . \DB::getTablePrefix() . 'adventure_loot.adventure_id GROUP BY adventure_id ) * 100) AS drop_chance'), DB::raw('(SELECT count( * ) FROM ' . \DB::getTablePrefix() . 'user_adventure WHERE adventure_id = ' . \DB::getTablePrefix() . 'adventure_loot.adventure_id GROUP BY adventure_id) AS played')))
            ->where('type', $type)
            ->groupBy('adventure_loot.adventure_id')
            ->orderBy('drop_chance', 'Desc')->take(10)->get();
        return Response::json($result);
    }
}