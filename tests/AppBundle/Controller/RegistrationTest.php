<?php
//
//namespace Tests\AppBundle\Controller;
//use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
//
//class RegistrationTest extends WebTestCase
//{
//
//    public function testRegister()
//    {
//        $client = static::createClient();
//
//        $container = self::$kernel->getContainer();
//        $em = $container->get('doctrine')->getManager();
//        $userRepo = $em->getRepository('AppBundle:User');
//        $userRepo->createQueryBuilder('u')
//            ->delete()
//            ->getQuery()
//            ->execute()
//        ;
//
//        $crawler = $client->request('GET', '/register');
//        $crawler = $client->followRedirect();
//
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
//
//    }
//}