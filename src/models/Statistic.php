<?php

namespace vladbara705\statistics\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query as DbQuery;
use Yii;

/**
 * Class Statistic
 * @package vladbara705\statistics\models
 */
class Statistic extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%statistics}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['datetime'],
                ],
                'value' => new \yii\db\Expression('NOW()')
            ],
        ];
    }

    /**
     * @param $ip
     * @param $type
     * @return bool
     */
    public static function findByIp($ip, $type)
    {
        $prepareTimezone = '+ INTERVAL ' . self::getParams()['timezoneUTC'] . ' HOUR';
        return self::find()
            ->where([
                'ip' => $ip,
                'type' => $type
            ])
            ->andWhere(['=', 'DATE_FORMAT(datetime ' . $prepareTimezone . ', \'%Y:%m:%d\')', date('Y:m:d')])
            ->exists();
    }

    /**
     * @return array
     */
    public static function getAll()
    {
        $prepareTimezone = '+ INTERVAL ' . self::getParams()['timezoneUTC'] . ' HOUR';

        $query = new DbQuery();
        $query->select([
            'count(*) count',
            'type',
            'extraType',
            'DATE_FORMAT(datetime' . $prepareTimezone . ', \'%Y-%m-%d\') date',
            'DATE_FORMAT(NOW() - INTERVAL if(extraType IS NULL, 1, 0) DAY' . $prepareTimezone . ' , \'%Y-%m-%d\') toDate',
            'DATE_FORMAT(NOW() ' . $prepareTimezone . ' , \'%Y-%m-%d\') fromDate'
        ]);
        $query->from(['{{%statistics}}']);
        $query->andWhere(['between', '`datetime` ' . $prepareTimezone,
            new Expression('DATE_FORMAT(NOW() - INTERVAL if(extraType IS NULL, 1, 0) DAY, \'%Y:%m:%d\')'),
            new Expression('DATE_FORMAT(NOW(), \'%Y:%m:%d %H:%i:%s\')')
        ]);
        $query->groupBy(new Expression('type, if(extraType IS NULL, DATE_FORMAT(datetime' . $prepareTimezone . '  , \'%Y:%m:%d\'), extraType)'));
        return $query->all();
    }

    /**
     * @param $request
     * @return array
     */
    public static function getStatisticsByType($request)
    {
        $prepareTimezone = '+ INTERVAL ' . self::getParams()['timezoneUTC'] . ' HOUR';
        $query = new DbQuery();
        $query->select([
            'count(*) count',
            'type',
            'DATE_FORMAT(datetime ' . $prepareTimezone . ' , \'%Y-%m-%d\') date',
            'extraType'
        ]);
        $query->from(['{{%statistics}}']);
        $query->where(['type' => $request['type']]);
        if (!empty($request['toDate'] && !empty($request['fromDate']))) {
            $query->andWhere(['between', '`datetime` ' . $prepareTimezone,
                new Expression('DATE_FORMAT("' . $request['toDate'] . ' 00:00:00", \'%Y:%m:%d\')'),
                new Expression('DATE_FORMAT("' . $request['fromDate'] . ' 23:59:59", \'%Y:%m:%d %H:%i:%s\')')
            ]);
        } else {
            $query->andWhere(['between', '`datetime` ' . $prepareTimezone,
                new Expression('DATE_FORMAT(NOW(), \'%Y:%m:%d\')'),
                new Expression('DATE_FORMAT(NOW(), \'%Y:%m:%d %H:%i:%s\')')
            ]);
        }
        $query->groupBy(new Expression('type, if(extraType IS NULL, DATE_FORMAT(datetime' . $prepareTimezone . ' , \'%Y:%m:%d\'), extraType)'));
        return $query->all();
    }

    /**
     * @return array
     */
    public static function getParams()
    {
        $params = isset(Yii::$app->params['statistics']) ? Yii::$app->params['statistics'] : null;

        return [
            'blackListIp' => isset($params['blackListIp']) ? $params['blackListIp'] : null,
            'trackRobots' => isset($params['trackRobots']) ? $params['trackRobots'] : false,
            'authentication' => isset($params['authentication']) ? $params['authentication'] : false,
            'authData' => isset($params['authData']) ? $params['authData'] : null,
            'timezoneUTC' => isset($params['timezoneUTC']) ? $params['timezoneUTC'] : 0,
        ];
    }
}
