<?php
return array(
    'components'=>array(
        //database of Magento1
        'db1' => array(
            'connectionString' => 'mysql:host=localhost;port=3306;dbname=stu_art_back_m1',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'Typhp$M@!l@19',
            'charset' => 'utf8',
            'tablePrefix' => '',
            'class' => 'CDbConnection'
        ),
        //database of Magento 2 (we use this database for this tool too)
        'db' => array(
            'connectionString' => 'mysql:host=localhost;port=3306;dbname=stu-art-m2',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'Typhp$M@!l@19',
            'charset' => 'utf8',
            'tablePrefix' => '',
            'class' => 'CDbConnection'
        ),
    )
);
