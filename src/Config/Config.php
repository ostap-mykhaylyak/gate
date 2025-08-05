<?php

namespace Ostap\Gate\Config;

use CodeIgniter\Config\BaseConfig;

class Config extends BaseConfig
{
    public $enableCache = true;
    public $cacheTimeout = 3600;
    public $defaultRole = 'user';  
}
