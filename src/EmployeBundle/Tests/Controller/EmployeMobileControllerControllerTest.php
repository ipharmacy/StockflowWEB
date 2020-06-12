<?php

namespace EmployeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmployeMobileControllerControllerTest extends WebTestCase
{
    public function testFindallemployes()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/findAllEmployes');
    }

}
