<?php

use yii\db\Migration;

/**
 * Class m170306_083010_add_statistic_table
 */
class m170306_083010_add_statistic_table extends Migration
{
    /**
     * @return bool|void
     */
    public function safeUp()
    {
		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

		$this->createTable('{{%statistics}}', [
			'id' => $this->primaryKey(),
			'ip' => $this->string(15)->notNull(),
			'type' => $this->string(100)->notNull(),
            'extraType' => $this->string(100),
            'isRobot' => $this->smallInteger()->defaultValue(0)->notNull(),
            'datetime' => $this->timestamp()->notNull()
		], $tableOptions);

        $this->createIndex('type_idx', '{{%statistics}}', ['type'], false);
        $this->createIndex('ip_type_idx', '{{%statistics}}', ['ip', 'type'], false);
        $this->createIndex('datetime_idx', '{{%statistics}}', ['datetime'], false);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable('{{%statistics}}');
    }
}
