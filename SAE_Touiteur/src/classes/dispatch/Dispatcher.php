<?php
namespace App\classes\dispatch;
use App\classes\action\affichageAbonnementAction;
use App\classes\action\affichageProfilAction;
use App\classes\action\affichageTagsAction;
use App\classes\action\clickSurTouiteAction;
use App\classes\action\clickSurUserAction;
use App\classes\action\DefaultAction;
use App\classes\action\Connexion;
use App\classes\action\inscription;
use App\classes\action\pageDefaultAction;
use App\classes\action\seConnecterAction;
use App\classes\action\touiterAction;
use App\classes\action\touiteTagAction;


class Dispatcher{

    protected ?string $action = null;

    public function __construct(){
        $this->action = isset($_GET['action']) ? $_GET['action'] : null;
    }

    public function run(): void
    {
        switch ($this->action) {
            case 'touiteTagAction':
                $action = new touiteTagAction();
                break;
            case 'clickSurTouiteAction':
                $action = new clickSurTouiteAction();
                break;
            case 'clickSurUserAction':
                $action = new clickSurUserAction();
                break;
            case 'affichageAbonnementAction':
                $action = new affichageAbonnementAction();
                break;
            case 'affichageTagsAction':
                $action = new affichageTagsAction();
                break;
            case 'affichageProfilAction':
                $action = new affichageProfilAction();
                break;
            case 'pageDefaultAction':
                $action = new pageDefaultAction();
                break;
            case 'touiterAction':
                $action = new touiterAction();
                break;
            case 'seConnecterAction':
                $action = new seConnecterAction();
                break;
            case 'inscription':
                $action = new inscription();
                break;
            case 'connexion':
                $action = new Connexion();
                break;
            default:
                $action = new DefaultAction();
                break;
        }
        $html = $action->execute();
        $this->renderPage($html);
    }
    private function renderPage(string $html): void {
        $pageContent = $html;
        echo $pageContent;
    }

}
