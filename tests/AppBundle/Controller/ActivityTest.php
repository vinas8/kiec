<?php
/**
 * Created by PhpStorm.
 * User: zn
 * Date: 5/25/17
 * Time: 10:58 AM
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ActivityTest extends WebTestCase
{
    public function testActivity()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'mokytojas@gmail.com',
            'PHP_AUTH_PW'   => 'mokytojas@gmail.com',
        ));

        $client->followRedirects();

        $crawler = $client->request('GET', 'activities/list');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());


        $form = $crawler->selectButton('Pridėti')->form();
        $form['activity[name]'] = 'kamuoliuko metimas';
        $form['activity[bestResultDetermination]'] = 'max';
        $form['activity[units]'] = 'm';

        $crawler = $client->submit($form);
    }
}
