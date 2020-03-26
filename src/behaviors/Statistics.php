<?php

namespace vladbara705\statistics\behaviors;

use vladbara705\statistics\models\Statistic;
use Yii;
use yii\base\Behavior;
use yii\web\Controller;

/**
 * Class Statistics
 * @package vladbara705\statistics\behaviors
 */
class Statistics extends Behavior
{
    public $actions;

    public $type = null;

    public $uniqueUser;

    /**
     * @return array
     */
    public function events()
    {
        return [
            Controller::EVENT_AFTER_ACTION  => 'onAfterAction',
        ];
    }

    /**
     * @return bool|void
     */
    public function onAfterAction()
    {
        $currentAction = $this->owner->action->id;
        if(in_array($currentAction, $this->actions) === false) return false;

        if (!isset($this->type) || empty($this->type)) {
            return false;
        }

        $params = Statistic::getParams();
        $userIp = Yii::$app->request->userIP;
        if (is_array($params['blackListIp']) && in_array($userIp, $params['blackListIp'])) return false;

        if (isset($this->uniqueUser) && !empty($this->uniqueUser)) {
            if (Statistic::findByIp($userIp)) return;
        }

        $isRobot = $this->isRobot();
        if (empty($params['trackRobots']) && !empty($isRobot)) return false;

        $extraType = null;
        if ((is_array($this->type))) {
            $type = $this->type[0];
            $extraType = count($this->type) > 1 ? $this->type[1] : null;
        } else {
            $type = $this->type;
        }

        $model = new Statistic();
        $model->ip = $userIp;
        $model->type = $type;
        $model->extraType = $extraType;
        $model->isRobot = $isRobot;
        $model->save();
    }

    /**
     * @return bool
     */
    private function isRobot()
    {
        $bots = array(
            'rambler','googlebot','aport','yahoo','msnbot','turtle','mail.ru','omsktele',
            'yetibot','picsearch','sape.bot','sape_context','gigabot','snapbot','alexa.com',
            'megadownload.net','askpeter.info','igde.ru','ask.com','qwartabot','yanga.co.uk',
            'scoutjet','similarpages','oozbot','shrinktheweb.com','aboutusbot','followsite.com',
            'dataparksearch','google-sitemaps','appEngine-google','feedfetcher-google',
            'liveinternet.ru','xml-sitemaps.com','agama','metadatalabs.com','h1.hrn.ru',
            'googlealert.com','seo-rus.com','yaDirectBot','yandeG','yandex',
            'yandexSomething','Copyscape.com','AdsBot-Google','domaintools.com',
            'Nigma.ru','bing.com','dotnetdotcom'
        );

        foreach($bots as $bot) {
            if (stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false) {
                return true;
            }
        }
        return false;
    }
}
