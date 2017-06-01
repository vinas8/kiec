<?php
/**
 * Created by PhpStorm.
 * User: zn
 * Date: 5/25/17
 * Time: 10:58 AM
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ResultsTest extends WebTestCase
{
    public function testAddResult()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'mokytojas@gmail.com',
            'PHP_AUTH_PW'   => 'mokytojas@gmail.com',
        ));

        $client->followRedirects();

        $crawler = $client->request('GET', '/');
        $link = $crawler->filterXPath("//a[contains(@href, '/result/create')]")->link();
        $crawler = $client->click($link);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());



        $form = $crawler
            ->filter('button:contains("Pridėti")') // find all buttons with the text "Add"
            ->eq(0)
            ->form()// select the first button in the list
        ;
        $form['result[activity]'] = '1';
        // todo: 1
        $form['result[studentInfo]'] = '2';
        $form['result[value]'] = '100';
        $form['result[timestamp][date]'] = '2015-05-21';
        $form['result[timestamp][time]'] = '12:20';
        $crawler = $client->submit($form);

        $form['result[activity]'] = '1';
        // todo: 1
        $form['result[studentInfo]'] = '2';
        $form['result[value]'] = '200';
        $form['result[timestamp][date]'] = '2015-05-21';
        $form['result[timestamp][time]'] = '12:20';
        $crawler = $client->submit($form);

        $crawler = $client->request('GET', '/student/view/2');

        $result = $crawler->filter('html:contains("100 m")')->text();

        $this->assertContains('100 m', $result);

    }
}
