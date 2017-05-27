<?php
/**
 * Created by PhpStorm.
 * User: zn
 * Date: 5/25/17
 * Time: 10:58 AM
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClassTest extends WebTestCase
{
    public function testCreateClass()
    {

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'test',
        ));
        $client->followRedirects();
        $crawler = $client->request('GET', '/class/view');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', $crawler->filter('a[class="btn btn-danger btn-block modal-button"]')->attr('href'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());





        $form = $crawler
            ->filter('button:contains("Pridėti")') // find all buttons with the text "Add"
            ->eq(0)
            ->form()// select the first button in the list
        ;
        $form['appbundle_classinfo[name]'] = '5a';
        var_dump($form);
        $crawler = $client->submit($form);


        $container = self::$kernel->getContainer();
        $em = $container->get('doctrine')->getManager();
        $classinfo = $em->getRepository('AppBundle:ClassInfo');

        echo $client->getResponse()->getContent() ;die;

//        $form = $crawler->selectButton('Pridėti')->form();
//        $crawler = $client->submit($form, array(
//        ));
//
//        $form = $crawler->filter('button[type="submit"]')->form(array(
//            'appbundle_classinfo[name]' => '5a'
//        ), 'POST');
//        $client->submit($form);
//
//
//        $crawler = $crawler->selectLink('5a')->link();
//
//        echo $client->request('GET', $crawler->filter('a[class="btn btn-danger btn-block modal-button"]')->attr('href'));

    }
}
