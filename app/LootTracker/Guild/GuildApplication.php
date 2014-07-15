<?php

class GuildApplication extends Eloquent {

    protected $table = 'guild_applications';

    protected $fillable = array('guild_id', 'user_id');

    public function members() {
        return $this->hasMany('User', 'id', 'user_id');
    }
}