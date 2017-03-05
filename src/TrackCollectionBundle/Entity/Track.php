<?php

namespace TrackCollectionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tracks")
 * @ORM\HasLifecycleCallbacks
 */
class Track
{
    public function __construct()
    {
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
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="Singer", inversedBy="tracks")
     * @ORM\JoinColumn(name="singer_id", referencedColumnName="id")
     */
    protected $singer;

    /**
     * @ORM\ManyToOne(targetEntity="Genre", inversedBy="tracks")
     * @ORM\JoinColumn(name="genre_id", referencedColumnName="id")
     */
    protected $genre;

    /**
     * @ORM\ManyToOne(targetEntity="Year", inversedBy="tracks")
     * @ORM\JoinColumn(name="year_id", referencedColumnName="id")
     */
    protected $year;


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
     * Set name
     *
     * @param string $name
     * @return Track
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
     * @return Track
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
     * @return Track
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
     * Set singer
     *
     * @param \TrackCollectionBundle\Entity\Singer $singer
     * @return Track
     */
    public function setSinger(\TrackCollectionBundle\Entity\Singer $singer = null)
    {
        $this->singer = $singer;

        return $this;
    }

    /**
     * Get singer
     *
     * @return \TrackCollectionBundle\Entity\Singer 
     */
    public function getSinger()
    {
        return $this->singer;
    }

    /**
     * Set genre
     *
     * @param \TrackCollectionBundle\Entity\Genre $genre
     * @return Track
     */
    public function setGenre(\TrackCollectionBundle\Entity\Genre $genre = null)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return \TrackCollectionBundle\Entity\Genre 
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set year
     *
     * @param \TrackCollectionBundle\Entity\Year $year
     * @return Track
     */
    public function setYear(\TrackCollectionBundle\Entity\Year $year = null)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return \TrackCollectionBundle\Entity\Year 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Serialize object to array
     *
     * @return array
     */
    public function serialize() {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'singer' => $this->getSinger()->getName(),
            'genre' => $this->getGenre()->getName(),
            'year' => $this->getYear()->getName()
        ];
    }
}
