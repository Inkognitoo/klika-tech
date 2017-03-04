<?php

namespace TrackCollectionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="years")
 * @ORM\HasLifecycleCallbacks
 */
class Year
{
    /**
     * @ORM\OneToMany(targetEntity="Track", mappedBy="years")
     */
    protected $tracks;

    public function __construct()
    {
        $this->tracks = new ArrayCollection();

        $this->setCreated(new \DateTime());
        $this->setUpdated(new \DateTime());
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedValue()
    {
        $this->setUpdated(new \DateTime());
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $internal_name;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;


    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set internal_name
     *
     * @param string $internalName
     * @return Year
     */
    public function setInternalName($internalName)
    {
        $this->internal_name = $internalName;

        return $this;
    }

    /**
     * Get internal_name
     *
     * @return string
     */
    public function getInternalName()
    {
        return $this->internal_name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Year
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Year
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Year
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Add tracks
     *
     * @param \TrackCollectionBundle\Entity\Track $tracks
     * @return Year
     */
    public function addTrack(\TrackCollectionBundle\Entity\Track $tracks)
    {
        $this->tracks[] = $tracks;

        return $this;
    }

    /**
     * Remove tracks
     *
     * @param \TrackCollectionBundle\Entity\Track $tracks
     */
    public function removeTrack(\TrackCollectionBundle\Entity\Track $tracks)
    {
        $this->tracks->removeElement($tracks);
    }

    /**
     * Get tracks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTracks()
    {
        return $this->tracks;
    }

    /**
     * Serialize object to array
     *
     * @return array
     */
    public function serialize() {
        return [
            'id' => $this->getId(),
            'internal_name' => $this->getInternalName(),
            'name' => $this->getName(),
        ];
    }
}
