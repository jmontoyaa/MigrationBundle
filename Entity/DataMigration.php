<?php

namespace Oro\Bundle\MigrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table("migrations", indexes={
 *     @ORM\Index(name="idx_oro_migrations", columns={"bundle"})
 * })
 * @ORM\Entity()
 */
class DataMigration
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="bundle", type="string", length=250)
     */
    protected $bundle;

    /**
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=250)
     */
    protected $version;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="loaded_at", type="datetime")
     */
    protected $loadedAt;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return DataMigration
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * @param string $bundle
     * @return DataMigration
     */
    public function setBundle($bundle)
    {
        $this->bundle = $bundle;
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     * @return DataMigration
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLoadedAt()
    {
        return $this->loadedAt;
    }

    /**
     * @param \DateTime $loadedAt
     * @return DataMigration
     */
    public function setLoadedAt($loadedAt)
    {
        $this->loadedAt = $loadedAt;
        return $this;
    }
}
