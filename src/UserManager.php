<?php

namespace Ostap\Gate;

class UserManager
{
    private $sshManager;

    public function __construct(SshManager $sshManager)
    {
        $this->sshManager = $sshManager;
    }

    /**
     * Crea un utente sudo.
     */
    public function createSudoUser(string $username, string $password): string
    {
        if (!$this->ssh) {
            $this->logger->error("Tentativo di creare un utente sudo senza connessione");
            throw new \Exception('Non connesso');
        }

        $addUserCmd = "sudo useradd -m -s /bin/bash $username";
        $setPassCmd = "echo '$username:$password' | sudo chpasswd";
        $grantSudoCmd = "sudo usermod -aG sudo $username";

        $this->executeSudoCommand($addUserCmd, $password);
        $this->executeSudoCommand($setPassCmd, $password);
        $this->logger->info("Creato utente sudo: $username");
        return $this->executeSudoCommand($grantSudoCmd, $password);
    }

    /**
     * Crea un utente limitato.
     */
    public function createLimitedUser(string $username, string $password): string
    {
        $this->sshManager->logger->info("Creazione di un utente limitato: $username");

        $addUserCmd = "sudo useradd -m -s /bin/bash $username";
        $setPassCmd = "echo '$username:$password' | sudo chpasswd";
        $lockRootCmd = "sudo usermod -L $username";  // Disabilita login diretto dell'utente
        $restrictShellCmd = "sudo usermod -s /usr/sbin/nologin $username";  // Imposta una shell limitata

        $this->sshManager->executeSudoCommand($addUserCmd, $password);
        $this->sshManager->executeSudoCommand($setPassCmd, $password);
        $this->sshManager->executeSudoCommand($lockRootCmd, $password);
        $this->sshManager->executeSudoCommand($restrictShellCmd, $password);

        $this->sshManager->logger->info("Utente limitato creato: $username");
        return "Utente limitato $username creato con successo.";
    }
}
