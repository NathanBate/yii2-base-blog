<?php

namespace bb\base;

use bb\helpers\HyiiHelper;
use bb\helpers\UserHelper;
use yii\web\Controller;
use bb\helpers\HyiiHelper as Helper;
use Bb;

class WebController extends Controller
{

    private $twig;

    protected $data = [];

    /**
     * Checks to see if this has been set to app to use templates from the templates folder at the root of the project.
     * If not, it uses the base templates under the src director.
     *
     * @var string
     *
     */
    protected $template_dir = "base";

    public function __construct($id, $module, $config = [])
    {
        //Bb::$app->request->enableCookieValidation = true;
        //Bb::dd(Bb::$app->request);
        //Bb::$app->components['request']['enableCookieValidation'] = true;
        //Bb::dd(Bb::$app->components['request']);
        //Bb::$app->user->enableSession = true;
        //Bb::dd(Bb::$app->user);
        parent::__construct($id, $module, $config);

        $hyii_path = Bb::getAlias("@bb");
        $this->data['site_url'] = Bb::getAlias("@site_url") . "/";

        if (getenv("ENVIRONMENT") == "dev") {
            $this->data['devmode'] = true;
        } else {
            $this->data['devmode'] = false;
        }

        $this->data['site_name'] = getenv("SITE_NAME");

        $this->data['isAdmin'] = UserHelper::isAdmin();
        $this->data['LoggedInUser'] = $user = UserHelper::loadUserInfo();

        if ($this->template_dir == "app") {
            $loader = new \Twig\Loader\FilesystemLoader(Bb::getAlias('@frontendTemplatePath'));
        } else {
            $loader = new \Twig\Loader\FilesystemLoader($hyii_path . '/templates');
        }

        $this->twig = new \Twig\Environment($loader, [
            'cache' => false
        ]);
    }

    /**
     * @param string $template
     * @param array $data
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function renderTemplate($template="", $data=[])
    {
        $data = array_merge($this->data, $data);

        return $this->twig->render($template, $data);
    }

}