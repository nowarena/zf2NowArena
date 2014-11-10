<?php
namespace Twitter\Model;

class TwitterModel
{
    
    public function formatFavorites($response)
    {
        
        if (!is_object($response)) {
            return false;
        }

        $entArr = array();
        $arr = $response->toValue();
        foreach($arr as $i => $row) {
            printR($row);
            if (!isset($row->entities->media[0]->media_url)) {
                continue;
            }
            $tweetEnt = new \Twitter\Model\TweetEntity;
            $tweetEnt->setTweetId($row->id_str)
                ->setScreenName($row->user->screen_name)
                ->setCreatedAt(date("Y-m-d H:i:s"))
                //->setCreatedAt(date("Y-m-d H:i:s", strtotime($row->created_at)))
                ->setMediaUrl($row->entities->media[0]->media_url)
                ->setMediaWidth($row->entities->media[0]->sizes->medium->w)
                ->setMediaHeight($row->entities->media[0]->sizes->medium->h)
                ->setStatusUrl($row->entities->media[0]->expanded_url);
            $entArr[] = $tweetEnt;
        }
       printR($entArr); 
        return $entArr; 
        
    }
    

	public function formatGalleryEnt(TweetEntity $ent)
	{
        $link = $ent->getStatusUrl();
        if ($link == '') {
            $link = "http://twitter.com/" . $ent->getScreenName() . "/status/" . $ent->getTweetId();
        }
        $socMedEnt = new \Gallery\Model\GalleryEntity();
        $socMedEnt->setUsername($ent->getScreenName())
           ->setSocialId($ent->getTweetId())
           ->setTitle('')
           ->setHeaderText('')
           ->setText($ent->getTweet())
           ->setMediaUrl($ent->getMediaUrl())
           ->setMediaHeight($ent->getMediaHeight())
           ->setMediaWidth($ent->getMediaWidth())
           ->setLink($link)
           ->setSource('twitter')
           ->setDateCreated($ent->getCreatedAt());
        
        return $socMedEnt;
        
	}
	
    
}