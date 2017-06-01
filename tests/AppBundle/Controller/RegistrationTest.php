<?php

namespace Tests\AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationTest extends WebTestCase
{

    public function testRegister()
    {
        $client = static::createClient();
        $client->followRedirects();


        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Registruotis')->form();

        //fill teacher
        $form['fos_user_registration_form[name]'] = 'mokytojas@gmail.com';
        $form['fos_user_registration_form[email]'] = 'mokytojas@gmail.com';
        $form['fos_user_registration_form[plainPassword][first]'] = 'mokytojas@gmail.com';
        $form['fos_user_registration_form[plainPassword][second]'] = 'mokytojas@gmail.com';
        $form['fos_user_registration_form[role]'] = 'ROLE_TEACHER';


        $crawler = $client->submit($form);

        $this->assertContains(
            'Naudotojas sukurtas',
            $client->getResponse()->getContent()
        );

        $form['fos_user_registration_form[role]'] = 'ROLE_STUDENT';
        $form['fos_user_registration_form[name]'] = 'mokinys@gmail.com';
        $form['fos_user_registration_form[email]'] = 'mokinys@gmail.com';
        $form['fos_user_registration_form[plainPassword][first]'] = 'mokinys@gmail.com';
        $form['fos_user_registration_form[plainPassword][second]'] = 'mokinys@gmail.com';

        $crawler = $client->submit($form);

//        $this->assertContains(
//            'Naudotojas sukurtas',
//            $client->getResponse()->getContent()
//        );





        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp(
            '/El. paštas jau užimtas./',
            $client->getResponse()->getContent()
        );

    }


}