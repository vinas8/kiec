<?php
/**
 * Created by PhpStorm.
 * User: zn
 * Date: 5/25/17
 * Time: 10:58 AM
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JoinCodeTest extends WebTestCase
{
    public function testJoinCode()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'mokytojas@gmail.com',
            'PHP_AUTH_PW'   => 'mokytojas@gmail.com',
        ));

        $client->followRedirects();

//table->tr->3td
        $crawler = $client->request('GET', 'http://127.0.0.1:9999/class/view/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $joinCodeText = $crawler->filterXPath('//table[2]/tr/td[3]')->text();

        $studentJoinCode = trim(preg_replace('/\s+/', ' ', $joinCodeText));

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'mokinys@gmail.com',
            'PHP_AUTH_PW'   => 'mokinys@gmail.com',
        ));
        $client->followRedirects();
        $crawler = $client->request('GET', '/');

        $link = $crawler->filterXPath("//a[contains(@href, '/student/join')]")->link();
        $crawler = $client->click($link);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler
            ->filter('button:contains("Prisijungti")') // find all buttons with the text "Add"
            ->eq(0)
            ->form()// select the first button in the list
        ;
        $form['form[joinCode]'] = $joinCodeText;

        $crawler = $client->submit($form);

        $crawler = $client->request('GET', 'http://127.0.0.1:9999/');


        $link = $crawler->filterXPath("//a[contains(@href, '/result/create')]")->link();
        $crawler = $client->click($link);

        $form = $crawler
            ->filter('button:contains("Pridėti")') // find all buttons with the text "Add"
            ->eq(0)
            ->form()// select the first button in the list
        ;


//        $form['result[activity]'] = '1';
//        // todo: 1
//        $form['result[studentInfo]'] = '2';
//        $form['result[value]'] = '100';
//        $form['result[timestamp][date]'] = '2015-05-21';
//        $form['result[timestamp][time]'] = '12:20';
//        $crawler = $client->submit($form);


//        echo $client->getResponse()->getContent() ;die;


    }
}
