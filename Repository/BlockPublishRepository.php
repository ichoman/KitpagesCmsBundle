<?php
namespace Kitpages\CmsBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Kitpages\CmsBundle\Entity\ZoneBlock;

class BlockPublishRepository extends EntityRepository
{
   
    public function findByBlockAndRenderer($block, $renderer)
    {      
        $blockPublish = $this->_em
            ->createQuery("
                SELECT bp
                FROM KitpagesCmsBundle:BlockPublish bp
                WHERE bp.block = :block
                  AND bp.renderer = :renderer
            ")
            ->setParameter("block", $block)
            ->setParameter("renderer", $renderer)
            ->getResult();
        if (count($blockPublish) == 1) {
            return $blockPublish[0];
        } else {
            return null;
        }
    }

    public function findBySlugAndRenderer($slug, $renderer)
    {
        $blockPublish = $this->_em
            ->createQuery("
                SELECT bp
                FROM KitpagesCmsBundle:BlockPublish bp
                WHERE bp.slug = :slug
                  AND bp.renderer = :renderer
            ")
            ->setParameter("slug", $slug)
            ->setParameter("renderer", $renderer)
            ->getResult();
        if (count($blockPublish) == 1) {
            return $blockPublish[0];
        } else {
            return null;
        }
    }

    public function findByBlockAndZone($block, $zone)
    {      
        $listBlockPublish = $this->_em
            ->createQuery("
                SELECT bp
                FROM KitpagesCmsBundle:BlockPublish bp
                JOIN bp.block b
                JOIN b.zoneBlockList zb
                WHERE zb.zone = :zone
                  AND bp.block = :block
            ")
            ->setParameter("block", $block)
            ->setParameter("zone", $zone)
            ->getResult();
        return $listBlockPublish;
    }
 
    public function findByZone($zone)
    {      
        $listBlockPublish = $this->_em
            ->createQuery("
                SELECT bp
                FROM KitpagesCmsBundle:BlockPublish bp
                JOIN bp.block b
                JOIN b.zoneBlockList zb
                WHERE zb.zone = :zone
                ORDER BY zb.position ASC
            ")
            ->setParameter("zone", $zone)
            ->getResult();
        return $listBlockPublish;
    }

    public function findByZoneAndRenderer($zone, $renderer)
    {      
        $listBlockPublish = $this->_em
            ->createQuery("
                SELECT bp
                FROM KitpagesCmsBundle:BlockPublish bp
                JOIN bp.block b
                JOIN b.zoneBlockList zb
                WHERE zb.zone = :zone
                AND bp.renderer = :renderer                
                ORDER BY zb.position ASC
            ")
            ->setParameter("zone", $zone)
            ->setParameter("renderer", $renderer)                
            ->getResult();
        return $listBlockPublish;
    }
    
}
