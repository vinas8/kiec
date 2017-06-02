<?php
/**
 * Created by PhpStorm.
 * User: zn
 * Date: 5/25/17
 * Time: 10:58 AM
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StudentTest extends WebTestCase
{
    public function testAddStudentToClass()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'mokytojas@gmail.com',
            'PHP_AUTH_PW'   => 'mokytojas@gmail.com',
        ));

        $client->followRedirects();


        $crawler = $client->request('GET', '/class/view');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $link = $crawler
            ->filter('a:contains("5a")') // find all links with the text "Greet"
            ->eq(0) // select the second link in the list
            ->link();

        $crawler = $client->click($link);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $link = $crawler->filterXPath("//a[contains(@href, '/student/create/1')]")->link();

        $crawler = $client->click($link);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());


        $form = $crawler
            ->filter('button:contains("Pridėti")') // find all buttons with the text "Add"
            ->eq(0)
            ->form()// select the first button in the list
        ;
        $form['student[name]'] = 'Tomas Gudauskas';
        $form['student[birthDate]'] = '1993-10-11';
        $form['student[classInfo]'] = '1';

        $crawler = $client->submit($form);
    }
}
