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
    private $ip;
    private $type;
    private $isRobot;
    private $extraType;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%statistic}}';
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
     * @return bool
     */
    public static function findByIp($ip)
    {
        $prepareTimezone = '+ INTERVAL ' . self::getParams()['timezoneUTC'] . ' HOUR';
        return self::find()
            ->where(['=', 'ip', $ip])
            ->andWhere(['=', 'DATE_FORMAT(datetime ' . $prepareTimezone . ', \'%Y:%m:%d\')', date('Y:m:d')])
            ->exists();
    }

    /**
     * @param int $timezone
     * @return array
     */
    public static function getAll()
    {
        $prepareTimezone = '+ INTERVAL ' . self::getParams()['timezoneUTC'] . ' HOUR';
        
        $query = new DbQuery();
        $query->select([
            new Expression('@i:=@i+1 id'),
            'count(*) count',
            'type',
            'extraType',
            'DATE_FORMAT(datetime' . $prepareTimezone . ', \'%Y-%m-%d\') datetime',
            'DATE_FORMAT(NOW() - INTERVAL if(extraType IS NULL, 1, 0) DAY' . $prepareTimezone . ' , \'%Y-%m-%d\') toDate',
            'DATE_FORMAT(NOW() ' . $prepareTimezone . ' , \'%Y-%m-%d\') fromDate'
        ]);
        $query->from(['{{%statistic}}', '(SELECT @i:=0) x']);
        $query->andWhere(['between', '`datetime` ' . $prepareTimezone,
            new Expression('DATE_FORMAT(NOW() - INTERVAL if(extraType IS NULL, 1, 0) DAY, \'%Y:%m:%d\')'),
            new Expression('DATE_FORMAT(NOW(), \'%Y:%m:%d %H:%i:%s\')')
        ]);
        $query->groupBy(new Expression('type, if(extraType IS NULL, DATE_FORMAT(datetime' . $prepareTimezone . '  , \'%Y:%m:%d\'), extraType)'));
        return $query->all();
    }

    /**
     * @param $request
     * @param int $timezone
     * @return array
     */
    public static function getStatisticsByType($request)
    {
        $prepareTimezone = '+ INTERVAL ' . self::getParams()['timezoneUTC'] . ' HOUR';
        $query = new DbQuery();
        $query->select([
            new Expression('@i:=@i+1 id'),
            'count(*) count',
            'type',
            'DATE_FORMAT(datetime ' . $prepareTimezone . ' , \'%Y-%m-%d\') datetime',
            'extraType'
        ]);
        $query->from(['{{%statistic}}', '(SELECT @i:=0) x']);
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

//    public static function getAll($timezone = 0)
//    {
//        $prepareTimezone = '+ INTERVAL ' . $timezone . ' HOUR';
//
//        /**
//         * $queryWithExtraType
//         */
//        $queryWithExtraType = new DbQuery();
//        $queryWithExtraType->select([
//            new Expression('@i:=@i+1 id'),
//            'count(*) count',
//            'type',
//            'extraType',
//            'DATE_FORMAT(datetime, \'%Y-%m-%d\') datetime',
//            'DATE_FORMAT(NOW() ' . $prepareTimezone . ' , \'%Y-%m-%d\') toDate',
//            'DATE_FORMAT(NOW() ' . $prepareTimezone . ' , \'%Y-%m-%d\') fromDate'
//        ]);
//        $queryWithExtraType->from(['{{%statistic}}', '(SELECT @i:=0) x']);
//        $queryWithExtraType->where(['not', ['extraType' => null]]);
//        $queryWithExtraType->andWhere(['between', '`datetime` ' . $prepareTimezone,
//            new Expression('DATE_FORMAT(NOW(), \'%Y:%m:%d\')'),
//            new Expression('DATE_FORMAT(NOW(), \'%Y:%m:%d %H:%i:%s\')')
//        ]);
//        $queryWithExtraType->groupBy('type, extraType');
//
//        /**
//         * $queryWithoutExtraType
//         */
//        $queryWithoutExtraType = new DbQuery();
//        $queryWithoutExtraType->select([
//            new Expression('@i:=@i+1 id'),
//            'count(*) count',
//            'type',
//            'extraType',
//            'DATE_FORMAT(datetime, \'%Y-%m-%d\') datetime',
//            'DATE_FORMAT(NOW() - INTERVAL 1 DAY ' . $prepareTimezone . ' , \'%Y-%m-%d\') toDate',
//            'DATE_FORMAT(NOW() ' . $prepareTimezone . ' , \'%Y-%m-%d\') fromDate'
//        ]);
//        $queryWithoutExtraType->from(['{{%statistic}}', '(SELECT @i:=0) x']);
//        $queryWithoutExtraType->where(['extraType' => null]);
//        $queryWithoutExtraType->andWhere(['between', '`datetime` ' . $prepareTimezone,
//            new Expression('DATE_FORMAT(NOW() - INTERVAL 1 DAY, \'%Y:%m:%d\')'),
//            new Expression('DATE_FORMAT(NOW(), \'%Y:%m:%d %H:%i:%s\')')
//        ]);
//        $queryWithoutExtraType->groupBy(new Expression('type, DATE_FORMAT(datetime , \'%Y:%m:%d\')'));
//
//        $queryWithExtraType->union($queryWithoutExtraType);
//        return $queryWithExtraType->all();
//    }

/*    public static function getAllWithExtraType($timezone = 0)
    {
        $prepareTimezone = '+ INTERVAL ' . $timezone .' HOUR';
        $query = new DbQuery();
        $query->select([
            new Expression('@i:=@i+1 id'),
            'count(*) count',
            'type',
            'extraType',
        ]);
        $query->from(['{{%statistic}}', '(SELECT @i:=0) x']);
        $query->where(['between', 'datetime',
            new Expression('DATE_FORMAT(NOW() ' . $prepareTimezone . ' , \'%Y:%m:%d\')'),
            new Expression('DATE_FORMAT(NOW()' . $prepareTimezone . ' , \'%Y:%m:%d %H:%i:%s\')')
        ]);
        $query->andWhere(['not', ['extraType' => null]]);
        $query->groupBy('type, extraType');
        return $query->all();
    }

    public static function getAllWithoutExtraType($fromDate = 'NOW() - INTERVAL 1 DAY', $toDate = 'NOW()', $timezone = 0)
    {
        $prepareTimezone = '+ INTERVAL ' . $timezone .' HOUR';
        $query = new DbQuery();
        $query->select([
            new Expression('@i:=@i+1 id'),
            'count(*) count',
            'type',
            new Expression('DATE_FORMAT(datetime' . $prepareTimezone . ' , \'%Y:%m:%d\') datetime')
        ]);
        $query->from(['{{%statistic}}', '(SELECT @i:=0) x']);
        $query->where(['between', 'datetime',
            new Expression('DATE_FORMAT(' . $fromDate . ' ' . $prepareTimezone . ' ' . ', \'%Y:%m:%d\')'),
            new Expression('DATE_FORMAT(' . $toDate . ' ' . $prepareTimezone . ' ' . ', \'%Y:%m:%d %H:%i:%s\')')
        ]);
        $query->andWhere(['extraType' => null]);
        $query->groupBy(new Expression('type, DATE_FORMAT(datetime' . $prepareTimezone . ' , \'%Y:%m:%d\')'));
        return $query->all();
    }*/
