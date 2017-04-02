<?php

namespace AppBundle\Command;

use FOS\OAuthServerBundle\Entity\ClientManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AppCreateRestClientCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:create-rest-client');
        $this->setDescription('Create a new client');
        $this->addOption('redirect-uri', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            'Sets the redirect uri. Use multiple times to set multiple uris.', null);
        $this->addOption('grant-type', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            'Set allowed grant type. Use multiple times to set multiple grant types', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var $manager ClientManager */
        $manager = $this->getContainer()->get('fos_oauth_server.client_manager.default');

        $client = $manager->createClient();
        $client->setRedirectUris($input->getOption('redirect-uri'));
        $client->setAllowedGrantTypes($input->getOption('grant-type'));

        $manager->updateClient($client);

        $output->writeln('<info>Added a new client:</info>');
        $output->writeln(sprintf('client_id = <info>%s</info>', $client->getPublicId()));
        $output->writeln(sprintf('client_secret = <info>%s</info>', $client->getSecret()));
    }
}
