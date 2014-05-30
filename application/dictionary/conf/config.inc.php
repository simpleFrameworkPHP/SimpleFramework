<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午2:59
 */
return array(
    "type" => array(
        0 =>array("name"=>"表目录","value"=>"table"),
        1 =>array("name"=>"全部","value"=>"all"),
        2 =>array("name"=>"预告片","value"=>"prevue"),
    ),
    "relate_table"=>array(
        "all" => "*",
        "prevue" => array("cms_c_film","cms_mdb_film","cms_c_video","cms_rank","cms_rank_film","cms_content"),
    ),
    "relate_sql"=>array(
        "prevue"=>array(
            "banner"=>"select c.contentid,p.pctitle as title,p.pcthumb as pcthumb,p.pcbigphoto as pcbigphoto,c.url,c.description,vi.relatefilm from `cms_content` c,`cms_content_position` p,`cms_c_video` vi where c.status=99 and c.contentid=p.contentid and c.contentid=vi.contentid and p.posid=447 and c.catid=158 and vi.relatefilm!='' order by p.pclistorder desc,c.contentid desc limit 7",
            "独家预告"=>"select c.contentid,p.pctitle as title,p.pcthumb as thumb,c.url,cc.hits,cc.comments,c.inputtime,f.debutnational,f.nationality,f.dialogue from `cms_content` c,`cms_content_position` p,`cms_content_count` cc,`cms_c_video` v, `cms_c_film` as f where c.status=99 and c.contentid=p.contentid and c.contentid=v.contentid and p.contentid=cc.contentid and v.relatefilm=f.contentid and p.posid=448 and c.catid=158 and p.pcthumb!='' order by c.updatetime desc limit 4",
            "热门片段"=>"SELECT c.contentid,c.title,c.url,c.thumb FROM `cms_content` as c, `cms_c_video` as v, `cms_content_count` as cc WHERE c.contentid = v.contentid and c.status=99 and v.contentid = cc.contentid and c.catid=158 ORDER BY cc.hits_week DESC limit 4",
            "欧美预告片"=>"select c.contentid,c.title,c.url,c.thumb,cc.hits,cc.comments from `cms_content` c,`cms_content_count` cc,`cms_c_video` vi,`cms_c_film` f where c.contentid=vi.contentid and vi.contentid=cc.contentid and c.status=99 and vi.relatefilm=f.contentid and f.clime=4 and c.catid=158 order by c.inputtime desc limit 8",
            "华语预告片"=>"select c.contentid,c.title,c.url,c.thumb,cc.hits,cc.comments from `cms_content` c,`cms_content_count` cc,`cms_c_video` vi,`cms_c_film` f where c.contentid=vi.contentid and vi.contentid=cc.contentid and c.status=99 and vi.relatefilm=f.contentid and f.clime in (1,2) and c.catid=158 order by c.inputtime desc limit 8",
            "日韩/其他预告片"=>"select c.contentid,c.title,c.url,c.thumb,cc.hits,cc.comments from `cms_content` c,`cms_content_count` cc,`cms_c_video` vi,`cms_c_film` f where c.contentid=vi.contentid and vi.contentid=cc.contentid and c.status=99 and vi.relatefilm=f.contentid and f.clime in (0,3) and c.catid=158 order by c.inputtime desc limit 8",
            "内地预告片排行榜"=>"select c.contentid,c.title,c.url from `cms_content` c,`cms_content_count` cc,`cms_c_video` vi,`cms_c_film` f where c.contentid=vi.contentid and vi.contentid=cc.contentid and c.status=99 and vi.relatefilm=f.contentid and f.clime=1 and c.catid=158 order by hits_week desc limit 10",
            "北美预告片排行榜"=>"select c.contentid,c.title,c.url from `cms_content` c,`cms_content_count` cc,`cms_c_video` vi,`cms_c_film` f where c.contentid=vi.contentid and vi.contentid=cc.contentid and c.status=99 and vi.relatefilm=f.contentid and f.clime=4 and c.catid=158 order by hits_week desc limit 10",
            "预告片排行榜"=>"SELECT c.contentid,c.title,c.url,c.thumb FROM `cms_content` as c, `cms_c_video` as v, `cms_content_count` as cc WHERE c.contentid = v.contentid and c.status=99 and v.contentid = cc.contentid and c.catid=158 ORDER BY cc.hits_week DESC limit 10",//按热度排行
        ),
    ),
);
?>