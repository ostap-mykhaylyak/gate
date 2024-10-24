<?php

namespace Ostap\Gate;

use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\PublicKeyLoader;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class SshManager
{
    private $ssh;
    private $logger;
    private $config;

    public function __construct(Config $config = null)
    {
        $this->config = $config ?? new Config();
        $this->logger = new Logger('SshManager');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/logs/ssh.log', Logger::DEBUG));
    }

    /**
     * Connessione al server con username e password.
     */
    public function connectWithPassword(string $host, string $username, string $password): bool
    {
        $this->logger->info("Tentativo di connessione con username e password a $host sulla porta " . $this->config->getPort());

        $retryAttempts = $this->config->getRetryAttempts();

        while ($retryAttempts > 0) {
            try {
                $this->ssh = new SSH2($host, $this->config->getPort(), $this->config->getTimeout());
                if (!$this->ssh->login($username, $password)) {
                    throw new \Exception('Login fallito');
                }
                $this->logger->info("Connessione riuscita a $host con username e password sulla porta " . $this->config->getPort());
                return true;
            } catch (\Exception $e) {
                $this->logger->error("Errore di connessione a $host: " . $e->getMessage());
                $retryAttempts--;
                if ($retryAttempts == 0) {
                    throw new \Exception("Impossibile connettersi a $host dopo vari tentativi");
                }
            }
        }
        return false;
    }

    /**
     * Connessione al server con chiave SSH.
     */
    public function connectWithKey(string $host, string $username, string $privateKeyPath): bool
    {
        $this->logger->info("Tentativo di connessione con chiave SSH a $host sulla porta " . $this->config->getPort());

        try {
            $this->ssh = new SSH2($host, $this->config->getPort(), $this->config->getTimeout());
            $key = PublicKeyLoader::load(file_get_contents($privateKeyPath));
            if (!$this->ssh->login($username, $key)) {
                throw new \Exception('Login fallito');
            }
            $this->logger->info("Connessione riuscita a $host con chiave SSH sulla porta " . $this->config->getPort());
            return true;
        } catch (\Exception $e) {
            $this->logger->error("Errore di connessione con chiave SSH a $host: " . $e->getMessage());
            throw new \Exception("Errore di connessione con chiave SSH: " . $e->getMessage());
        }
    }

    /**
     * Esegui un comando sul server.
     */
    public function executeCommand(string $command): string
    {
        if (!$this->ssh) {
            $this->logger->error("Tentativo di eseguire un comando senza connessione");
            throw new \Exception('Non connesso');
        }

        $this->logger->info("Esecuzione comando: $command");
        return $this->ssh->exec($command);
    }

    /**
     * Esegui un comando con sudo.
     */
    public function executeSudoCommand(string $command, string $password): string
    {
        if (!$this->ssh) {
            $this->logger->error("Tentativo di eseguire un comando sudo senza connessione");
            throw new \Exception('Non connesso');
        }

        $this->logger->info("Esecuzione comando sudo: $command");
        $this->ssh->write("echo '$password' | sudo -S $command\n");
        return $this->ssh->read();
    }
}
