<?php

namespace Tests\AppBundle\Controller;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class LessonOrClassListTest extends WebTestCase
{
    public function testLesson()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadUserData',
        ));

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'test',
        ));

        $crawler = $client->request('GET', '/');
        $crawler = $client->followRedirect();
        $this->isSuccessful($client->getResponse());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        if (basename($crawler->getUri()) === 'view') {
            $this->assertContains(
        'Šiuo metu nėra pamokos',
                $client->getResponse()->getContent()
            );
        }


//        $container = self::$kernel->getContainer();
//        $em = $container->get('doctrine')->getManager();
//        $userRepo = $em->getRepository('AppBundle:User');
//        $userRepo->createQueryBuilder('u')
//            ->delete()
//            ->getQuery()
//            ->execute()
//        ;

//        $form = $crawler->selectButton('Registruotis')->form();
//
//
//
//        $form['fos_user_registration_form[name]'] = 'testas12se3@gmail.com';
//        $form['fos_user_registration_form[email]'] = 'testas12se3@gmail.com';
//        $form['fos_user_registration_form[plainPassword][first]'] = 'testas12se3@gmail.com';
//        $form['fos_user_registration_form[plainPassword][second]'] = 'testas12se3@gmail.com';
//
//        $crawler = $client->submit($form);
//
//        $this->assertTrue($client->getResponse()->isRedirect());
//
//        $client->followRedirect();
//        $client->followRedirect();
//
//        $this->assertContains(
//            'Naudotojas sukurtas',
//            $client->getResponse()->getContent()
//        );
//
//
//        $client->submit($form);
//
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//        $this->assertRegexp(
//            '/El. paštas jau užimtas./',
//            $client->getResponse()->getContent()
//        );

    }
}