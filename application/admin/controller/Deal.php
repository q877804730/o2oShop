<?php
namespace app\admin\controller;

use think\Controller;

class Deal extends Controller
{
    private $obj ;

    public function _initialize(){

        $this->obj = model('Deal');
    }

    public function index()
    {
        $data = input("get.");
        $sdata = [];
        // 时间放前面 查询优化
        if (!empty($data['start_time']) && !empty($data['end_time'])
            && (strtotime($data['end_time']) > strtotime($data['start_time']))){
            $sdata['create_time'] = ['gt', strtotime($data['start_time'])];
            $sdata['create_time'] = ['lt', strtotime($data['end_time'])];
        }
        if (!empty($data['category_id'])){
            $sdata['category_id'] = $data['category_id'];
        }
        if (!empty($data['city_id'])){
            $sdata['city_id'] = $data['city_id'];
        }
        if (!empty($data['name'])){
            $sdata['name'] = ['like', '%' . $data['name'] . '%'];
        }

        $deals = $this->obj->getNormalDeals($sdata);
        $categorys = model('Category')->getNormalCategoryByParentId();
        foreach ($categorys as $category){
            $categoryArrs[$category->id] = $category->name;
        }

        $citys = model('City');
        foreach ($citys as $city){
            $cityArrs[$city->id] = $city->name;
        }

        return $this->fetch('',[
            'categorys' => $categorys,
            'citys' => $citys,
            'deals' => $deals,
            'category_id' => empty($data['category_id']) ? '' : $data['category_id'],
            'city_id' => empty($data['city_id']) ? '' : $data['city_id'],
            'name' => empty($data['name']) ? '' : $data['name'],
            'start_time' => empty($data['start_time']) ? '' : $data['start_time'],
            'end_time' => empty($data['end_time']) ? '' : $data['end_time'],
            '$categoryArrs' => $categoryArrs,
            '$cityArrs' => $cityArrs,
        ]);
    }

    public function status(){
        $data = input('get.');
        $validate = validate('Category');
        if (!$validate->scene('status')->check($data)){
            $this->error($validate->getError());
        }
        $res = $this->obj->save(['status' => $data['status']], ['id' => $data['id']]);
        if ($res){
            $this->success('状态更新成功');
        }else{
            $this->error('状态更新失败');
        }
    }


}
