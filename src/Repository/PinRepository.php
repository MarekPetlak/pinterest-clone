<?php

namespace App\Repository;

use App\Entity\Pin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pin|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pin|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pin[]    findAll()
 * @method Pin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pin::class);
    }

    public function findOneByImageName(string $name): ?Pin
    {
        return $this->findOneBy(['imageName' => $name]);
    }

    public function remove(Pin $pin): void
    {
        $this->_em->remove($pin);
        $this->_em->flush();
    }

    public function save(Pin $pin): void
    {
        $this->_em->persist($pin);
        $this->_em->flush();
    }
}
