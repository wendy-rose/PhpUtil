<?php

/**
 * 根据数据库自增id生成唯一ID
 * 1.加自定义前缀，用于标识
 *2.格式使用前缀＋字母＋数字组成，数字只保留N位，超过的使用数字求余的方式使用字母对应
例如:
id=1
前缀=F
数字保留3位
则创建的编号为：F-A-001
 */
class UniqueID
{

    public static function createUniqueID($id, $numLength, $prefix)
    {
        $baseNum = pow(10, $numLength);
        //生成字母
        $disivion = (int) ($id/$baseNum);
        $word = '';
        while ($disivion){
            $remainder = fmod($disivion, 26);
            $tmp = chr($remainder + 65);
            $word .= $tmp;
            $disivion = floor($disivion / 26);
        }
        if (empty($word)){
            $rand = rand(65, 90);
            $word = chr($rand);
        }
        $mod = $id % $baseNum;
        $num = str_pad($mod, $numLength, 0, STR_PAD_LEFT);
        $uniqueId = sprintf('%s-%s-%s', $prefix, $word, $num);
        return $uniqueId;
    }
}