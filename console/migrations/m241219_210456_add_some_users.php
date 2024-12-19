<?php

use common\models\Group;
use common\models\User;
use yii\db\Migration;

/**
 * Class m241219_210456_add_some_users
 */
class m241219_210456_add_some_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $users = [];
        foreach(range(1, 9) as $index){
            $user = new User(
                [
                'username' => "User #$index",
                'email' => "user$index@test.com",
                'status' => User::STATUS_ACTIVE,
                ]
            );
            $user->setPassword('test');
            $user->generateAuthKey();
            $user->generatePasswordResetToken();
            $user->generateEmailVerificationToken();
            if($user->save()) {
                $users[] = $user;
            }
        }

        foreach (range(1, 9) as $index){
            $group = new Group(['name' => "Group #$index"]);
            $group->save();

            $selected = array_rand($users, rand(2, 9));
            foreach($selected as $selectedIndex){
                $group->link('users', $users[$selectedIndex]);
            }
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("SET FOREIGN_KEY_CHECKS = 0;");
        $this->truncateTable('user_group');
        $this->truncateTable('user');
        $this->truncateTable('group');
        $this->execute("SET FOREIGN_KEY_CHECKS = 1;");
    }
}
