<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-20
 * Time: 下午1:27
 */

class ContrastFunctionController extends Controller {

    public function conArray(){
        //对比array_map,array_walk,for,foreach四个方法的运行时间以及内存耗费

        $this->display();
    }

    public function conNo(){
        /**
         *   有两个序列a,b，大小都为n,序列元素的值任意整形数，无序；
         *   要求：通过交换a,b中的元素，使[序列a元素的和]与[序列b元素的和]之间的差最小。
         */
        $this->a = array(1,2,3,4,7,35,86,81,46);
        $this->b = array(3,5,8,2,54,7,34,75,65);
        $c = array();
        //第一次求序列a,b 和的差值
        $this->print_AB();
        $item = 0;
        while($this->poor <> 0 && $item < count($this->a)){
            $data = $this->tempAB($item++);print_r('序列a,b被升级后的数组:');print_r($data);
            $this->reAB($data['a'],$data['b'],$this->poor);
        }

        //第二次输出序列a,b 和的差值 === 此次为数组中刚好有两个单一元素的差值为整个差值一半的情况下
        $this->print_AB();
        if($this->poor <> 0){
            //最后没有刚好形成两个数组为相等和的情况则选择最小差时输出
            $min = min($c);
            $key = array_search($min,$c);
            $keys = explode(':',$key);
            $this->resetAB($keys[0],$key[1]);
            $this->print_AB();
        }
    }

    function tempAB($item){
        $a = $this->a;
        $b = $this->b;
        //针对序列内部元素求和 ==== 里面有点麻烦，没想到怎么实现呢 ===例如 a['1-2-3-4'] = 【a[1]到a[4]的和】
        return array('a'=>$a,'b'=>$b);
    }

    function reAB($a,$b,$poor){
        //计算并记录交换后的结果
        foreach($a as $ka=>$i){
            foreach($b as $kb=>$j){
                $c[$ka.':'.$kb] = $i - $j;
                if($c[$ka.':'.$kb] - $poor/2 == 0){
                    $this->resetAB($ka,$kb);
                    $this->poor = 0;
                    break;
                } elseif($poor*$c[$ka.':'.$kb] > 0){
                    unset($c[$ka.':'.$kb]);
                } else {
                    $c[$ka.':'.$kb] = abs($c[$ka.':'.$kb] - $poor/2);
                }
            }
        }
    }

    function resetAB($ka,$kb){
        if(strstr('-',$ka)){
            $ka = explode('-',$ka);
            $kb = explode('-',$kb);
        } else {
            $ka = array($ka);
            $kb = array($kb);
        }
        foreach($ka as $y=>$ik){
            $this->a[$ka[$y]] += $this->b[$kb[$y]];
            $this->b[$kb[$y]] = $this->a[$ka[$y]] - $this->b[$kb[$y]];
            $this->a[$ka[$y]] = $this->a[$ka[$y]] - $this->b[$kb[$y]];
        }
    }

    function print_AB(){
        $sumA = array_sum($this->a);
        $sumB = array_sum($this->b);
        $this->poor = $sumA - $sumB;
        echo $this->poor . '<br/>';
    }

} 