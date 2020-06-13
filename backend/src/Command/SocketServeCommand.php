<?php

namespace App\Command;

use App\Entity\ChatMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SocketServeCommand extends Command
{
    protected static $defaultName = 'socket:serve';

    const WS_HOST = '0.0.0.0';
    const WS_PORT = 8081;
    const WS_WORKERS_COUNT = 4;

    /**
     * Active websocket connections
     *
     * @var array
     */
    private $connections = [];

    /**
     * @var Worker
     */
    private $wm;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    protected function configure()
    {
        $this
            ->setDescription('Runs websocket server');
    }

    /**
     * SocketServeCommand constructor.
     *
     * @param string|null            $name
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(string $name = null, EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;

        parent::__construct($name);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->wm = new \Workerman\Worker(sprintf("websocket://%s:%d", self::WS_HOST, self::WS_PORT));
        $this->wm->count = self::WS_WORKERS_COUNT;

        $connections = [];

        $this->wm->onConnect = function ($connection) use (&$connections) {
            $connection->onWebSocketConnect = function ($connection) use (&$connections) {
                echo "Established new WebSocket connection to room " . $_GET['meeting_id'] . "\n";

                $connections[$_GET['meeting_id']][] = $connection;
            };
        };

        $this->wm->onMessage = function ($connection, $data) use (&$connections) {
            echo "Got message";

            $data = json_decode($data);
            $data->msg = htmlspecialchars($data->msg);
            $data->time = date('H:i d.m.Y');

            $stmt = $this->em
                ->getConnection()
                ->prepare('INSERT INTO chat_message (meeting_id, author_id, text, send_time) VALUES (:m_id, :a_id, :text, :send_time)');
            $stmt->execute([
                'm_id'      => $data->meeting_id,
                'a_id'      => $data->user->id,
                'text'      => $data->msg,
                'send_time' => date('Y-m-d H:i:s'),
            ]);

            foreach ($connections[$data->meeting_id] as $c) {
                $c->send(json_encode($data));
            }
        };

        $this->wm->onClose = function ($connection) {
            echo "Connection closed\n";
        };

        \Workerman\Worker::runAll();
    }
}
