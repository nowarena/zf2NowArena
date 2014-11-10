<?php
namespace FacebookMy\Model;

class FacebookUserEntity
{
    
    protected $user_id;
    protected $is_webmaster;

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getIsWebmaster()
    {
        return $this->is_webmaster;
    }

    public function setIsWebmaster($is_webmaster)
    {
        $this->is_webmaster = $is_webmaster;
    }
}