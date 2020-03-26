<?php

namespace vladbara705\statistic\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;
use yii\helpers\ArrayHelper;

/**
 * Class InitController
 * @package vladbara705\statistic\commands
 */
class InitController extends Controller
{
    /**
     * @inheritdoc
     */
    public $choice = null;

    /**
     * @inheritdoc
     */
    public $defaultAction = 'index';

    /**
     * @param string $actionID
     * @return array|string[]
     */
    public function options($actionID)
    {
        return ['choice', 'color', 'interactive', 'help'];
    }

    /**
     * @param null $params
     * @return int
     * @throws \yii\base\InvalidRouteException
     * @throws \yii\console\Exception
     */
    public function actionIndex($params = null)
    {
        $module = Yii::$app->controller->module;
        $version = $module->version;
        $welcome =
            '╔═════════════════════════════════════════════════╗'. "\n" .
            '║                                                 ║'. "\n" .
            '║ STATISTIC MODULE, v.'.$version.' by VLADBARA705 ║'. "\n" .
            '║                                                 ║'. "\n" .
            '╚═════════════════════════════════════════════════╝';
        echo $name = $this->ansiFormat($welcome . "\n\n", Console::FG_GREEN);
        echo "Select the operation you want to perform:\n";
        echo "  1) Apply all module migrations\n";
        echo "  2) Revert all module migrations\n";
        echo "Your choice: ";

        if(!is_null($this->choice))
            $selected = $this->choice;
        else
            $selected = trim(fgets(STDIN));

        if ($selected == "1") {
            Yii::$app->runAction('migrate/up', ['migrationPath' => '@vendor/vladbara705/yii2-statistic/src/migrations', 'interactive' => true]);
        } else if($selected == "2") {
            Yii::$app->runAction('migrate/down', ['migrationPath' => '@vendor/vladbara705/yii2-statistic/src/migrations', 'interactive' => true]);
        } else {
            echo $this->ansiFormat("Error! Your selection has not been recognized.\n\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        echo "\n";
        return ExitCode::OK;
    }
}