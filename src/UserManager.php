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
