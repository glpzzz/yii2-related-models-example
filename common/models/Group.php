<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "group".
 *
 * @property int $id
 * @property string $name
 *
 * @property UserGroup[] $userGroups
 * @property User[] $users
 */
#[\AllowDynamicProperties]
class Group extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['users'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }


    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])->viaTable('user_group', ['group_id' => 'id']);
    }

    public function setUsers(array $users)
    {
        $this->users = $users;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        foreach ([
                     [
                         'table' => 'user_group',
                         'relation' => 'users',
                         'parameter' => 'user_id',
                     ],
        ] as $relation) {

            $transaction = Yii::$app->db->beginTransaction();
            try{
                Yii::$app->db->createCommand()->delete(
                    $relation['table'], [
                    'group_id' => $this->id,
                    ]
                )->execute();

                  $relationName = $relation['relation'];
                if (is_array($this->$relationName)) {
                    foreach ($this->$relationName as $id) {
                        if (is_numeric($id)) {
                            Yii::$app->db->createCommand()->insert(
                                $relation['table'], 
                                [ 'group_id' => $this->id, $relation['parameter'] => $id ]
                            )->execute();
                        }
                    }
                }
                $transaction->commit();
            }catch (\Throwable $e){
                $transaction->rollback();
            }

        }
    }

}

