<?php

//パターンと商品IDを入力
$pattern = [1, -1, 3];
$items = [2, 1, 2, 3, 1, 2];

//coupon発行の場合はtrue、対象外はfalse
var_export(checkCouponTarget($pattern, $items));


function checkCouponTarget($pattern, $items)
{

    //ショッピングカートが空の場合
    if (empty($items)) {
        return false;
    }

    //ショッピングカートの商品数がパターンのID数以下の場合はfalse
    if (count($items) < count($pattern)) {
        return false;
    }

    //パターンIDが全て−１だった場合はtrue
    if (isset(array_count_values($pattern)[-1]) && count($pattern) === array_count_values($pattern)[-1]) {
        return true;
    }

    //-1以外の最初のIDの文字
    $pattern_first_val = null;

    //何番目にあったか
    $pick_int = null;

    //パターンの-1ではない最初のIDを取得
    foreach ($pattern as $int => $val) {
        if ($val !== -1) {
            $pattern_first_val = $val;
            $pick_int = $int;
            break;
        }
    }

    //-1以外の最初のIDが、$itemの配列の何番目にあるか取得
    $keys_num = array_keys($items, $pattern_first_val);

    foreach ($keys_num as $key_num) {
        //初期化
        $array = array();
        $result_array = array();

        //取得開始位置が-1以外の場合だけ検査する
        if (($key_num - $pick_int) !== -1) {

            //パターンのID数だけ取得
            $array[] = array_slice($items, ($key_num - $pick_int), count($pattern));

            //検査する配列のID数がパターンのID数以下の場合はfalse
            if (count($array[0]) < count($pattern)) {
                return false;
            }

            //検査する配列のIDについて、パターンIDが-1の位置のIDは-1に変換
            foreach ($pattern as $num => $int) {
                if ($int === -1) {
                    $result_array[] = -1;
                } else {
                    $result_array[] = $array[0][$num];
                }
            }
            //同じ配列だったらtrue
            if (empty(array_diff($pattern, $result_array))) {
                return true;
            }
        }
    }
    return false;
}
