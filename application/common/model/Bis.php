<?php
namespace app\common\model;

class Bis extends BaseModel
{
    public function getBisByStatus($status = 0){
        $order = [
            "id" => "desc",
        ];
        $data = [
            "status" => $status,
        ];
        return $this->where($data)
            ->order($order)
            ->paginate(1);
    }
}