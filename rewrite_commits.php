<?php
$commits = [
    ['hash' => '59de85c', 'msg' => 'feat(ui): implementar correcciones responsive, limpiar codigo muerto y exportacion a excel'],
    ['hash' => '348c8f8', 'msg' => 'chore: limpieza masiva de archivos base sin usar de laravel'],
    ['hash' => '8b291d1', 'msg' => 'docs: agregar manual de usuario institucional y guia de instalacion'],
    ['hash' => '1c5c904', 'msg' => 'docs: agregar versiones Word (.docx) de los manuales'],
    ['hash' => 'af183ef', 'msg' => 'docs: actualizar manuales con instrucciones paso a paso para la base de datos'],
    ['hash' => '40e4b39', 'msg' => 'feat: agregar DemoDataSeeder para poblar 50 registros realistas para la presentacion']
];

echo "Checkout new branch...\n";
exec("git checkout -b main-es 66e75cb");

foreach ($commits as $commit) {
    echo "Cherry-picking {$commit['hash']}...\n";
    exec("git cherry-pick {$commit['hash']}");
    $escapedMsg = escapeshellarg($commit['msg']);
    exec("git commit --amend -m {$escapedMsg}");
}

echo "Overwriting main...\n";
exec("git checkout main");
exec("git reset --hard main-es");
exec("git branch -D main-es");

echo "Force pushing to origin...\n";
exec("git push -f origin main");

echo "Done!\n";
