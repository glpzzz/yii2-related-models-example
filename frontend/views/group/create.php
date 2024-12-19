<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this 
*/
/**
 * @var common\models\Group $model 
*/

$this->title = 'Create Group';
$this->params['breadcrumbs'][] = ['label' => 'Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-create">

    <h1><?php echo Html::encode($this->title) ?></h1>

    <?php echo $this->render(
        '_form', [
        'model' => $model,
        ]
    ) ?>

</div>

