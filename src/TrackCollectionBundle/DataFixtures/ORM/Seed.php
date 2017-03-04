<?php
namespace Blogger\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TrackCollectionBundle\Entity\Year;
use TrackCollectionBundle\Entity\Genre;
use TrackCollectionBundle\Entity\Singer;
use TrackCollectionBundle\Entity\Track;

class Seed implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $years = [];
        $years[] = new Year();
        $years[count($years)-1]->setInternalName(1992);
        $years[count($years)-1]->setName(1992);
        $manager->persist($years[count($years)-1]);

        $years[] = new Year();
        $years[count($years)-1]->setInternalName(1993);
        $years[count($years)-1]->setName(1993);
        $manager->persist($years[count($years)-1]);

        $years[] = new Year();
        $years[count($years)-1]->setInternalName(1993);
        $years[count($years)-1]->setName(1993);
        $manager->persist($years[count($years)-1]);

        $years[] = new Year();
        $years[count($years)-1]->setInternalName(1994);
        $years[count($years)-1]->setName(1994);
        $manager->persist($years[count($years)-1]);

        $years[] = new Year();
        $years[count($years)-1]->setInternalName(1995);
        $years[count($years)-1]->setName(1995);
        $manager->persist($years[count($years)-1]);


        $genres = [];
        $genres[] = new Genre();
        $genres[count($genres)-1]->setInternalName('folk');
        $genres[count($genres)-1]->setName('Folk');
        $manager->persist($genres[count($genres)-1]);

        $genres[] = new Genre();
        $genres[count($genres)-1]->setInternalName('rock');
        $genres[count($genres)-1]->setName('Rock');
        $manager->persist($genres[count($genres)-1]);

        $genres[] = new Genre();
        $genres[count($genres)-1]->setInternalName('jazz');
        $genres[count($genres)-1]->setName('Jazz');
        $manager->persist($genres[count($genres)-1]);

        $genres[] = new Genre();
        $genres[count($genres)-1]->setInternalName('blues');
        $genres[count($genres)-1]->setName('Blues');
        $manager->persist($genres[count($genres)-1]);


        $singers = [];
        $singers[] = new Singer();
        $singers[count($singers)-1]->setInternalName('the_beatles');
        $singers[count($singers)-1]->setName('The Beatles');
        $manager->persist($singers[count($singers)-1]);

        $singers[] = new Singer();
        $singers[count($singers)-1]->setInternalName('biting_elbows');
        $singers[count($singers)-1]->setName('Biting Elbows');
        $manager->persist($singers[count($singers)-1]);

        $singers[] = new Singer();
        $singers[count($singers)-1]->setInternalName('stillste_stund');
        $singers[count($singers)-1]->setName('Stillste Stund');
        $manager->persist($singers[count($singers)-1]);

        $singers[] = new Singer();
        $singers[count($singers)-1]->setInternalName('rolling_stones');
        $singers[count($singers)-1]->setName('Rolling Stones');
        $manager->persist($singers[count($singers)-1]);

        $singers[] = new Singer();
        $singers[count($singers)-1]->setInternalName('three_days_grace ');
        $singers[count($singers)-1]->setName('Three Days Grace');
        $manager->persist($singers[count($singers)-1]);

        for ($i = 0; $i < 100; $i++) {
            $track = new Track();
            $track->setName($this->randomName());
            $track->setGenre($genres[array_rand($genres, 1)]);
            $track->setSinger($singers[array_rand($singers, 1)]);
            $track->setYear($years[array_rand($years, 1)]);
            $manager->persist($track);
        }


        $manager->flush();
    }

    private function randomName() {
        $names1 = ['big', 'old', 'green', 'little', 'young'];
        $names2 = ['dog', 'woman', 'cat', 'car', 'house'];
        $names3 = ['and me', 'was wrong', 'in the night', 'kills', 'love you'];
        return ucfirst($names1[array_rand($names1, 1)].' '.
                        $names2[array_rand($names2, 1)].' '.
                        $names3[array_rand($names3, 1)]);
    }

}