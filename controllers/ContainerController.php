<?php
namespace wartron\yii2docker\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;

class ContainerController extends BaseDockerController
{

    public function actionIndex()
    {
        $all = \Yii::$app->getModule('docker')->ContainersFormated();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $all,
            'sort' => [
                'attributes' => ['id', 'name', 'image', 'created', 'command'],
            ],
            'pagination' => [
                'pageSize' => \Yii::$app->getModule('docker')->containerPageSize,
            ],
        ]);

        return $this->render('index',[
            'dataProvider'  => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $container  =   \Yii::$app->getModule('docker')->Containers()->find($id);
        $config     =   $container->getConfig();
        $info       =   $container->getRuntimeInformations();
        return $this->render('view',[
            'container' =>  $container,
            'config'    =>  $config,
            'info'      =>  $info,
            'envDP'     =>  $this->getEnvDataProvider($info),
            'volDP'     =>  $this->getVolDataProvider($info),
        ]);
    }

    public function getEnvDataProvider($info)
    {
        $env = array();
        foreach($info['Config']['Env'] as $k=>$v)
        {
            $p = explode('=', $v, 2);
            $env[] = [
                'id'    =>  $k,
                'var'   =>  $p[0],
                'value' =>  $p[1]
            ];
        }
        $envDP = new ArrayDataProvider([
            'allModels' =>  $env,
        ]);

        return $envDP;
    }


    public function getVolDataProvider($info)
    {
        $vol = array();
        $volId = 0;
        foreach($info['Volumes'] as $k => $v )
        {
            $vol[] = [
                'id'        =>  $volId++,
                'local'     =>  $k,
                'remote'    =>  $v,
                'rw'        =>  (array_key_exists($k,$info['VolumesRW']) && $info['VolumesRW'][$k] == 1)?'rw':'ro',
            ];
        }
        $volDP = new ArrayDataProvider([
            'allModels' =>  $vol,
        ]);

        return $volDP;
    }
}
