<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 17-12-31
 * Time: 下午5:15
 */

class TestController extends Controller {

    public function index()
    {
        $all_list = json_encode($this->initA());
        $this->assign('all_list', $all_list);
        $this->display();
    }

    public function abs18()
    {
        echo '<pre>题目：8个小圆代表八个数字，然后手拉手，围成一个大圆，相邻之间差的绝对值为1到8，他们的和为2000，求这8个数。</pre>';
        echo '<pre> ①--(1)--②--(2)--③--(3)</pre>';
        echo '<pre>  |                     |</pre>';
        echo '<pre> (8)                    ④</pre>';
        echo '<pre>  |                     |</pre>';
        echo '<pre>  ⑧                    |</pre>';
        echo '<pre>  |                    (4)</pre>';
        echo '<pre> (7)                    |</pre>';
        echo '<pre>  |                     ⑤</pre>';
        echo '<pre> ⑦ -- --(6)--⑥--(5)---|</pre>';


        $list = [1,2,3,4,5,6,7,8];
        $data = [];
        foreach ($list as $value) {
            $data = $this->initAbs($data, $value);
        }
        foreach ($data as &$i_data) {
            if (array_sum($i_data)) {
                unset($i_data);
            }
        }
        $i = 200;

        $result = [];
        while ($i < 300) {
            $i++;
            foreach ($data as $row) {
                $i_result = [];
                $as = $i;
                foreach ($row as $v) {
                    $i_result[] = $as;
                    $as = $as + $v;
                }
                if (array_sum($i_result) == 2000 && $i == $as) {
                    $result[] = ['result' => $i_result, 'v' => $row];
                }
            }
        }


        foreach ($result as $row) {
            echo json_encode($row) . '<br/>';
        }
    }

    private function initAbs($data, $value)
    {
        $list = [];
        $add = [$value, -$value];

        foreach ($add as $v) {
            if (!empty($data)) {
                foreach ($data as $item) {
                    $item[] = $v;
                    $list[] = $item;
                }
            } else {
                $item = [];
                $item[] = $v;
                $list[] = $item;
            }
        }
        return $list;
    }

    private function initA()
    {
        $list = [];
        $i = 1;
        while($i < 111){
            ++$i;
            $list[] = $i++;
        }
        return $list;
    }
}