<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Thread;
use App\Entity\Comment;
use App\Entity\Technology;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = [];
        for ($i = 0; $i < 100; $i++) {
            $user = new User();
            $user->setUsername($faker->userName());
            $manager->persist($user);
            $users[] = $user;
        }

        $technologys = [];
        $technos = ['PHP', 'JAVASCRIPT', 'PYTHON', 'REACT', 'ANGULAR', 'SYMFONY', 'LARAVEL'];
        foreach ($technos as $techno) {
            $technology = new Technology();
            $technology->setName($techno);
            $manager->persist($technology);
            $technologys[] = $technology;
        }
        
        $threads = [];
        for ($i = 0; $i < 50; $i++) {
            $thread = new Thread();
            $thread->setUser($faker->randomElement($users));
            $thread->setTitle($faker->sentence());
            $thread->setContent($faker->paragraphs(3, true));
            $thread->setPostDate($faker->dateTimeThisYear());
            $thread->setStatus($faker->randomElement(['Active', 'Closed', 'Moderate']));
            $thread->addTechnology($faker->randomElement($technologys));
            $manager->persist($thread);
            $threads[] = $thread;
        }

        $comments = [];
        for ($i = 0; $i < 100; $i++)  {
            $comment = new Comment();
            $comment->setThread($faker->randomElement($threads));
            $comment->setUser($faker->randomElement($users));
            $comment->setCommentDate($faker->dateTimeThisYear());
            $comment->setContent($faker->sentence());
            $manager->persist($comment);
            $comments[] = $comment;
        }
        $manager->flush();
    }
}
