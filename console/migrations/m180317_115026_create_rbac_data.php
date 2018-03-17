<?php

use backend\models\User;
use yii\db\Migration;

/**
 * Class m180317_115026_create_rbac_data
 */
class m180317_115026_create_rbac_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        //Define permissions
        $viewComplainsListPermission = $auth->createPermission('viewComplaintsList');
        $auth->add($viewComplainsListPermission);

        $viewPostPermission = $auth->createPermission('viewPost');
        $auth->add($viewPostPermission);

        $deletePostPermission = $auth->createPermission('deletePost');
        $auth->add($deletePostPermission);

        $approvePostPermission = $auth->createPermission('approvePost');
        $auth->add($approvePostPermission);

        $viewUsersListPermission = $auth->createPermission('viewUserList');
        $auth->add($viewUsersListPermission);

        $viewUserPermission = $auth->createPermission('viewUser');
        $auth->add($viewUserPermission);

        $deleteUserPermission = $auth->createPermission('deleteUser');
        $auth->add($deleteUserPermission);

        $updateUserPermission = $auth->createPermission('updateUser');
        $auth->add($updateUserPermission);

        //define roles
        $moderatorRole = $auth->createRole('moderator');
        $auth->add($moderatorRole);

        $adminRole = $auth->createRole('admin');
        $auth->add($adminRole);

        //Define roles-permossions relations
        $auth->addChild($moderatorRole, $viewComplainsListPermission);
        $auth->addChild($moderatorRole, $viewPostPermission);
        $auth->addChild($moderatorRole, $deletePostPermission);
        $auth->addChild($moderatorRole, $approvePostPermission);
        $auth->addChild($moderatorRole, $viewUsersListPermission);
        $auth->addChild($moderatorRole, $viewUserPermission);

        $auth->addChild($adminRole, $moderatorRole);
        $auth->addChild($adminRole, $deleteUserPermission);
        $auth->addChild($adminRole, $updateUserPermission);


        //Create admin user
        $user = new User([
            'email' => 'admin@admin.com',
            'username' => 'admin',
            'password_hash' => '$2y$13$xTAgij7JHo/WW/GM0nQP9uNDn9DSYgjzxA1GfCj99QBa6TPecAu7u',
        ]);
        $user->generateAuthKey();
        $user->save();

        //add adminRole to user
        $auth->assign($adminRole, $user->getId());


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180317_115026_create_rbac_data cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180317_115026_create_rbac_data cannot be reverted.\n";

        return false;
    }
    */
}
