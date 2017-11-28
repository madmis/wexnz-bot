<?php

namespace App\Strategy;

use App\Service\WexnzClient;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Strategy
 * @package App\Strategy
 */
abstract class Strategy
{
    /**
     * @var WexnzClient
     */
    protected $client;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @param WexnzClient     $client
     * @param OutputInterface $output
     */
    public function __construct(WexnzClient $client, OutputInterface $output)
    {
        $this->client = $client;
        $this->output = $output;
    }

    /**
     * @return WexnzClient
     */
    public function getClient(): WexnzClient
    {
        return $this->client;
    }

    /**
     * @param string $message
     *
     * @return void
     */
    protected function info(string $message): void
    {
        $this->output->writeln($message);
    }
}
