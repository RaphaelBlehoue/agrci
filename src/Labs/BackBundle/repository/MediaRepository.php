<?php

namespace Labs\BackBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MediaRepository extends EntityRepository
{

    /**
     * @param $project
     * @return array
     */
    public function findForPostMedia($project)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->where(
            $qb->expr()->eq('m.project', ':project')
        );
        $qb->setParameter('project', $project);
        return $qb->getQuery()->getResult();
    }


    /**
     * @param $media
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneMedia($media)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->where(
            $qb->expr()->eq('m.id', ':media')
        );
        $qb->setParameter('media', $media);
        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param $id
     * @param $foreignKey
     * @return array
     */
    public function findMediaIsNotMedia($id, $foreignKey)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->where(
            $qb->expr()->eq('m.project', ':foreignKey'),
            $qb->expr()->neq('m.id', ':id')
        );
        $qb->setParameter('foreignKey', $foreignKey);
        $qb->setParameter('id', $id);
        return $qb->getQuery()->getResult();
    }
}