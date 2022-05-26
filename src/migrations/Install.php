<?php

namespace bb\migrations;

use bb\models\UserModel;
use yii\db\Migration;
use bb\models\SectionModel;
use bb\models\AssetFolderModel;
use bb\helpers\HyiiHelper;

class Install extends Migration
{

    /**
     * @var string The admin's username
     */
    public $username;

    /**
     * @var string The admin's first name
     */
    public $firstName;

    /**
     * @var string The admin's last name
     */
    public $lastName;

    /**
     * @var string The admin's password
     */
    public $password;

    /**
     * @var bool The admin is an admin!
     */
    public $admin;

    /**
     * @var string The admin's email
     */
    public $email;

    /**
     * @var
     */
    public $active;

    /**
     * Install constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);

        // hide the database output from the screen.
        $this->compact = true;
    }

    /**
     * @return bool
     */
    public function safeUp()
    {
        $this->createInfo();
        $this->createPostsTable();
        $this->createSectionsTable();
        $this->createPostElementsTable();
        $this->createAssetFoldersTable();
        $this->createAssetsTable();
        $this->createUsers();
        return true;
    }

    /**
     * @return bool
     */
    public function safeDown()
    {
        if (getenv('ENVIRONMENT') == "dev") {
            $this->dropTable("{{%info}}");
            $this->dropTable("{{%posts}}");
            $this->dropTable("{{%sections}}");
            $this->dropTable("{{%post_elements}}");
            $this->dropTable("{{%asset_folders}}");
            $this->dropTable("{{%assets}}");
            $this->dropTable("{{%users}}");
            return true;
        } else {
            echo PHP_EOL . PHP_EOL . PHP_EOL . "*** Uninstalling is only available in the dev environment." . PHP_EOL . PHP_EOL . PHP_EOL;
            return false;
        }
    }

    /**
     * Create the Info Table and Populate it
     */
    private function createInfo()
    {
        $this->createTable("{{%info}}", [
            'id' => $this->primaryKey(),
            'version' => $this->string()->notNull(),
            'dateCreated' => $this->timestamp()->defaultExpression("CURRENT_TIMESTAMP"),
            'dateUpdated' => $this->timestamp()->defaultExpression("CURRENT_TIMESTAMP"),
            'currentDataState' => $this->bigInteger(),
            'pendingDataState' => $this->bigInteger(),
            'userManagement' => "ENUM('Y','N') DEFAULT 'N'",
            'blog' => "ENUM('Y','N') DEFAULT 'N'",
            'defaultSection' => $this->integer(),
        ]);

        $info = [
            "version" => "1.0",
            "defaultSection" => 1,
        ];

        $this->insert("{{%info}}", $info);
    }

    /**
     * Create the Posts Table
     */
    private function createPostsTable()
    {
        $this->createTable("{{%posts}}", [
            'id' => $this->primaryKey(),
            'title' => $this->string(225),
            'author' => $this->integer(),
            'section' => $this->integer(),
            'date' => $this->dateTime()->defaultExpression("CURRENT_TIMESTAMP"),
            'published' => "ENUM('Y','N') DEFAULT 'N'",
            'preview' => $this->text(),
            'trashed' => "ENUM('Y','N','S') DEFAULT 'N'",
        ]);
    }

    private function createSectionsTable()
    {
        $this->createTable("{{%sections}}", [
            'id' => $this->primaryKey(),
            'title' => $this->string(225),
            'slug' => $this->string(225),
            'trashed' => "ENUM('Y','N','S') DEFAULT 'N'",
        ]);

        $section = new SectionModel([
            "title" => "General",
            "slug" => "general"
        ]);

        $section->save();

    }

    /**
     * Create the Post Elements Table
     */
    private function createPostElementsTable()
    {
        $this->createTable("{{%post_elements}}", [
            'id' => $this->primaryKey(),
            'post' => $this->integer(),
            'order' => $this->integer(),
            'data' => $this->text(),
            'type' => $this->string(225),
            'trashed' => "ENUM('Y','N','S') DEFAULT 'N'",
        ]);
    }


    /**
     * Create the Asset Folders Table
     */
    private function createAssetFoldersTable()
    {
        $this->createTable("{{%asset_folders}}", [
            'id' => $this->primaryKey(),
            'section' => $this->integer(),
            'folderName' => $this->string(225),
            'type' => "ENUM('Local_Private','Local_Public','Object_Storage_Public') DEFAULT 'Local_Private'",
            'trashed' => "ENUM('Y','N','S') DEFAULT 'N'",
        ]);

        $firstSection = HyiiHelper::query()
            ->select("*")
            ->from("{{%sections}}")
            ->one();

        $assetFolder = new AssetFolderModel([
            'section' => $firstSection['id'],
            'folderName' => 'General Assets',
        ]);
        $assetFolder->save();
    }

    /**
     * Create the Asset Folders Table
     */
    private function createAssetsTable()
    {
        $this->createTable("{{%assets}}", [
            'id' => $this->primaryKey(),
            'post' => $this->integer(),
            'folder' => $this->integer(),
            'label' => $this->string(225),
            'description' => $this->text(),
            'type' => "ENUM('jpg','png','pdf') DEFAULT 'jpg'",
            'trashed' => "ENUM('Y','N','S') DEFAULT 'N'",
        ]);
    }

    /**
     * Create the Users Table and Populate it
     */
    private function createUsers()
    {
        $this->createTable("{{%users}}", [
            'id' => $this->primaryKey(),
            'firstName' => $this->string(100),
            'lastName' => $this->string(100),
            'username' => $this->string(100)->notNull(),
            'email' => $this->string(100),
            'password' => $this->string(),
            'admin' => "ENUM('Y','N','S') DEFAULT 'N'",
            'authKey' => $this->char(255),
            'dateCreated' => $this->dateTime()->defaultExpression("CURRENT_TIMESTAMP"),
            'dateUpdated' => $this->dateTime()->defaultExpression("CURRENT_TIMESTAMP"),
            'photoId' => $this->integer(),
            'trashed' => "ENUM('Y','N','S') DEFAULT 'N'",
            'active' => "ENUM('Y','N','S') DEFAULT 'N'",
            'approved' => "ENUM('Y','N','D') DEFAULT 'N'",
        ]);

        $newUser =  new UserModel ([
            "username" => $this->username,
            "firstName" => $this->firstName,
            "lastName" => $this->lastName,
            "password" => $this->password,
            "admin" => $this->admin,
            "email" => $this->email,
            'active' => 'Y',
            'approved' => 'Y',
        ]);

        $newUser->add();
    }

}