<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var $model backend\models\Role
 * @var $this  yii\web\View
 */

$this->title = '创建新用户组';

?>


<?= $this->render('_form', [
    'model' => $model,
]) ?>
