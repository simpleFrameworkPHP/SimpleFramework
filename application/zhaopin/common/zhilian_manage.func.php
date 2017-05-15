<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/4/20
 * Time: 08:21
 */
class zhilian{

    public static function addData(){
        $url = 'http://sou.zhaopin.com/jobs/searchresult.ashx?jl=%E5%A4%A7%E8%BF%9E&kw=php&isadv=0&we=-1&et=2&isfilter=1&p=1&sf=10001&st=15000';

        expendModel('htmlTree');
        // Create DOM from URL or file
        $dom = file_get_html($url);echo $dom->innertext;exit;
        echo "<pre>";
        $data = array();
        $attach = array('地点'=>'area','公司性质'=>'company_type','公司规模'=>'company_size','学历'=>'education','职位月薪'=>'salary');
        foreach($dom->find("div[class=newlist_detail]") as $div){
            $row = array();
            foreach($div->find('li') as $li){
                print_r($li->class);
            }exit;
            $position = $div->find('li[class=newlist_deatil_first] a',0);
            $row['position_name'] = $position;
            $row['url'] = $position->href.'?'.$position->par;

            $company = $div->find('li[class=newlist_deatil_three] a',0);
            $row['company_name'] = $company->innertext;
            $row['company_url'] = $company->href.'?'.$company->par;
            $attr = $div->find('li[class=newlist_deatil_two] span');

            foreach($attr as $span){
                $span = explode("：",$span->innertext);
                $title = current($span);
                $value = end($span);
                if(isset($attach[$title])){
                    $row[$attach[$title]] = $value;
                } else {
                    Log::write('智联招聘数据抓取',$title."键值不存在",'zhilian');
                }
            }
            $row['remark'] = $div->find('li[class=newlist_deatil_last]',0)->innertext;
            $data[] = $row;
//            print_r($li->outertext);
//                print_r($li->innertext);
        }
        var_dump($data);
    }
}
