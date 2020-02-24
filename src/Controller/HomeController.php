<?php

declare(strict_types = 1);

namespace App\Controller;

use Mammoth\Controller\Common\Controller;
use Mammoth\DI\DIClass;
use Mammoth\Http\Entity\Request;
use Mammoth\Http\Entity\Response;
use Mammoth\Http\Factory\ResponseFactory;

/**
 * Controller for homepage
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Controller\User
 */
class HomeController extends Controller
{
    use DIClass;

    /**
     * @inject
     */
    private ResponseFactory $responseFactory;

    /**
     * @inheritDoc
     */
    public function defaultAction(Request $request): Response
    {
        return $this->responseFactory->create($request)->setContentView("home")->setDescription(
            "Webová aplikace slouží jako pomocník žáků středních škol a studentů vysokých škol.
                Po registraci umožňuje vstup do portálu daného žáka/studenta,
                kam si může ukládat školní soubory či poznámky různého druhu
                (písemné práce, testy, domácí úkoly, školní akce a jiné).
                Tato data může následně sdílet se spolužáky v rámci třídy nebo s konkrétními uživateli."
        )->setKeywords(
            "portál, žák, student, střední škola, vysoká škola, studium, soubor, poznámka, sdílení,
            test, písemná práce, domácí úkol, školní akce"
        )->setTitle("Moje Studium - věrný pomocník ke studiu");
    }
}