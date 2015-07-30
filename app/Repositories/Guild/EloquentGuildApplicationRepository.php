<?php namespace LootTracker\Repositories\Guild;

/**
 * Class EloquentGuildApplicationRepository
 * @package LootTracker\Repositories\Guild
 */
class EloquentGuildApplicationRepository implements GuildApplicationInterface
{

    /**
     * @var \LootTracker\Repositories\Guild\GuildInterface
     */
    protected $guildRepo;


    /**
     * EloquentGuildApplicationRepository constructor.
     *
     * @param GuildInterface $guildInterface
     */
    public function __construct(GuildInterface $guildInterface)
    {
        $this->guildRepo = $guildInterface;
    }


    /**
     * @param $guild_id
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all($guild_id)
    {
        return GuildApplication::whereGuildId($guild_id)->get();
    }


    /**
     * @param $data
     * @param $user_id
     * @param $guild_id
     *
     * @return GuildApplication
     */
    public function create($data, $user_id, $guild_id)
    {
        $application = new GuildApplication();
        $application->user_id = $user_id;
        $application->guild_id = $guild_id;
        $application->message = e($data['message']);
        $application->save();

        return $application;
    }


    /**
     * @param $application_id
     *
     * @return mixed
     */
    public function byId($application_id)
    {
        return GuildApplication::findOrFail($application_id);
    }


    /**
     * @param $application_id
     */
    public function delete($application_id)
    {
        $application = $this->byId($application_id);
        $application->delete();
    }


    /**
     * @param $application_id
     */
    public function approve($application_id)
    {
        $application = $this->byId($application_id);
        $this->guildRepo->addMember($application->guild_id, $application->user_id);
        $this->delete($application_id);
    }


    /**
     * @param $application_id
     */
    public function decline($application_id)
    {
        $this->delete($application_id);
    }
}