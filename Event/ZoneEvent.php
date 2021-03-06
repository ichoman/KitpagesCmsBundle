<?php
namespace Kitpages\CmsBundle\Event;

use Kitpages\CmsBundle\Event\AbstractEvent;
use Kitpages\CmsBundle\Entity\Zone;

class ZoneEvent extends AbstractEvent
{
    protected $zone;
    protected $listRenderer;    

    
    public function __construct(Zone $zone, $listRenderer = null)
    {
        $this->zone = $zone;
        $this->listRenderer = $listRenderer;        
    }

    public function getZone()
    {
        return $this->zone;
    }

    public function getListRenderer()
    {
        return $this->listRenderer;
    }
    
}
