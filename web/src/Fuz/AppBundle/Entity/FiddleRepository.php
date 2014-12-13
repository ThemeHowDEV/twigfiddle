<?php

namespace Fuz\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Fuz\AppBundle\Entity\User;
use Fuz\AppBundle\Entity\Fiddle;

/**
 * FiddleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FiddleRepository extends EntityRepository
{

    public function getFiddle($hash, $revision, User $user = null)
    {
        if (!is_null($hash))
        {
            $query = $this->_em->createQuery("
                SELECT f
                FROM Fuz\AppBundle\Entity\Fiddle f
                WHERE f.hash = :hash
                AND f.revision = :revision
                AND (
                    f.visibility <> :private
                    OR f.user = :user
                )
            ");

            $params = array (
                    'hash' => $hash,
                    'revision' => $revision <= 0 ?: $revision,
                    'private' => Fiddle::VISIBILITY_PRIVATE,
                    'user' => $user ? $user->getId() : -1,
            );

            $fiddle = $query
               ->setParameters($params)
               ->getOneOrNullResult()
            ;

            if (is_null($fiddle))
            {
                $fiddle = new Fiddle();
                $fiddle->setHash($hash);
            }
        }
        else
        {
            $fiddle = new Fiddle();
        }
        return $fiddle;
    }

}
