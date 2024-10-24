# gate
Gate is a PHP library for managing SSH connections.

```bash
use Ostap\Gate\SshManager;
use Ostap\Gate\Config;

$config = new Config(15, 5, 2222);
$sshManager = new SshManager($config);
$sshManager->connectWithPassword('host', 'username', 'password');
$output = $sshManager->executeCommand('ls -la');
$output = $sshManager->executeSudoCommand('apt update', 'sudo_password');



```bash
use Ostap\Gate\UserManager;

$userManager = new UserManager($sshManager);
$userManager->createLimitedUser('limiteduser', 'password');
$sshManager->createSudoUser('newuser', 'newpassword');
