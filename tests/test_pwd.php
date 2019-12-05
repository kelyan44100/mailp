<?php

$pwd = 'eHgEjYPmxgUPBRFYLvFDfjaMpKjDzhBxyJYVtUNg';

// http://php.net/manual/fr/function.password-verify.php

if (password_verify($pwd, '$2y$10$aXrN7qSG5mYraviTya7NR.rKyEpNeI4hpDI6epQ3hgbP5O.lkQoKu')) {
    echo 'Le mot de passe est valide !';
} else {
    echo 'Le mot de passe est invalide.';
}
?>