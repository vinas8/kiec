<?php

namespace Tests\AppBundle\Controller;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LessonControllerTest extends WebTestCase
{

    public function testShowPost()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');
        $crawler = $client->followRedirect();
//
        $form = $crawler->selectButton('Registruotis')->form();
        $form['fos_user_registration_form[name]'] = 'test2124@gmail.com';
        $form['fos_user_registration_form[email]'] = 'test2124@gmail.com';
        $form['fos_user_registration_form[plainPassword][first]'] = 'test2124@gmail.com';
        $form['fos_user_registration_form[plainPassword][second]'] = 'test2124@gmail.com';


        $client->submit($form);
        $response = $client->getResponse();

        $this->assertContains(' El. paštas jau užimtas.', $response->getContent());
//        $response = $client->getResponse();
//        $this->assertContains('Your data has been saved!', $response->getContent());
    }
}