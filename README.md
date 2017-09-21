[WIP] This bundle is heavily inspired by awesome Sylius Work ;)

## INSTALLATION via Composer

    composer require nicolassing/sequence-bundle

## CONFIGURATION
Register the bundle:

```php
<?php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Nicolassing\SequenceBundle\NicolassingSequenceBundle(),
    );
    // ...
}
```


Create your Sequence class:

```php
<?php
// src/AppBundle/Entity/Sequence.php

namespace AppBundle\Entity;

use Nicolassing\SequenceBundle\Model\Sequence as BaseSequence;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sequence")
 */
class Sequence extends BaseSequence
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
```

Configure the bundle:

```yaml
# app/config/config.yml
nicolassing_sequence:
    user_class: AppBundle\Entity\Sequence
```

## USAGE

## TESTS

If you want to run tests, please check that you have installed dev dependencies.

```bash
./vendor/bin/phpunit
```