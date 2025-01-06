<?php
namespace App\Controller;

use App\Exception\NotFoundException;
use App\Model\Zajecia;
use App\Service\Router;
use App\Service\Templating;

class ZajeciaController
{
    public function indexAction(Templating $templating, Router $router): ?string
    {
        $zajecia = Zajecia::filteredFind("G_pro_01");
        $html = $templating->render('zajecia/index.html.php', [
            'zajecia' => $zajecia,
            'router' => $router,
        ]);
        return $html;
    }
}