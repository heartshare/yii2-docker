<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = 'Images';
$this->params['breadcrumbs'][] = ['label' => 'Images', 'url' => ['/docker/image']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="docker-default-index">

    <div class="panel panel-default">
        <div class="panel-heading">Images</div>
        <div class="panel-body">
            <div class="table-responsive">
                <?php
                    echo GridView::widget([
                        'layout' => '{summary}{pager}{items}{pager}',
                        'dataProvider' => $dataProvider,
                         'columns' => [
                            app\grid\ColumnPreset::linkId('image',12),
                            'repository',
                            'tag',
                        ]
                    ]);
                ?>
            </div>
        </div>
    </div>


</div>
