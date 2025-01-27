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
            'PHP_AUTH_USER' => 'mokytojas@gmail.com',
            'PHP_AUTH_PW'   => 'mokytojas@gmail.com',
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
        $crawler = $client->submit($form);
    }
}
