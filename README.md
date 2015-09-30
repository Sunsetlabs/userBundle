### Instalacion

#### via composer

````json
// composer.json

{
    "require": {
        "sunsetlabs/user-bundle"       : "dev-hash_password"
    },
    "repositories": [
        {
            "type" : "vcs",
            "url"  : "https://github.com/Sunsetlabs/userBundle.git"
        }
    ]
}
````

### Configuracion

Regisrarlo en el kernel de la aplicacion

````php
// app/AppKernel.php

$bundles = array(
    ...
    new Sunsetlabs\UserBundle\SunsetlabsUserBundle(),
    ...
);
````

El plugin provee una clase base para un administrador y para un usuario, a continuacion se muestra como el caso base para extender cualquiera de ellas.

````php
// src/AppBundle/Entity/Admin.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Sunsetlabs\UserBundle\Entity\Admin as BaseAdmin;

/**
 * @ORM\Entity
 * @ORM\Table(name="admin")
 * @UniqueEntity("username")
 */
class Admin extends BaseAdmin
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	protected $id;
	/**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank()
     */
	protected $username;
	/**
     * @ORM\Column(type="string")
     */
	protected $password;

     /**
     * @ORM\Column(type="string")
     */
     protected $salt = null;
}
````

Para su uso en un backend a tener en cuenta que para el campo password debe
usarse la propiedad plainPassword de la misma. El bundle provee un listener que
condifica la contrasena con el codificardor configurado en la seguridad del
proyecto.

A la hora de configurar la seguridad, se puede usar la siguiente configuracion
como ejemplo:

````yml
security:
    providers:
        admin:
            id: admin_provider

    encoders:
        AppBundle\Entity\Admin: sha512

    firewalls:
        admin:
            pattern: ^/admin
            form_login:
                provider: admin
                login_path: admin_login_route
                check_path: admin_login_check
                always_use_default_target_path: true
                default_target_path:  /admin
            logout:
                path:   /admin/logout
                target: admin_login_route
            anonymous:    true
    access_control:
        - { path: ^/admin/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin, role: ROLE_ADMIN }
````
