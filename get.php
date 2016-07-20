<?php
header('Content-Type: application/json');
$date = $_GET['date'];
if(preg_match("/^2[0-9]{3}-[0-9]{2}-[0-9]{2}$/", $date)){
    $html = file_get_contents('http://www.thaiwater.net/DATA/REPORT/php/rid_bigcm.php?sdate='.$date);
    $html = str_replace('&nbsp;', '', $html);
    $html = str_replace('<tr>', '</tr><tr>', $html);
    $html = strip_tags($html, '<table><td><tr>');
    $html = str_replace("\r\n", "", $html);
    $html = str_replace("\n", "", $html);
    $html = str_replace("<table ", "\n<table ", $html);
    $html = explode("\n", $html);
    foreach($html as $table):
        if(preg_match("/�Ҥ�˹��/", $table)):
            $html = $table; 
            break;
        endif;
    endforeach;

    $html = strip_tags($html, '<td><tr>');
    $html = str_replace("</tr>", "\n", $html);
    $html = str_replace("</td>", ",,", $html);
    $html = strip_tags($html);
    $html = explode("\n", $html);
    $start = 0;
    foreach($html as $index => $cell):
        if(preg_match("/�Ҥ�˹��/", $cell)):
            $start = $index;
            break;      
        endif;
    endforeach;
    $html = array_slice($html, $start);
    $tmp_regionname = "";
    $region = array();
    $summary = array();
    $dam_name = array();
    $dam_name_eng = array();
    $region_name = array();
    $region_name_eng = array();
    $column_name = array(
        "�����ط���дѺ����纡ѡ�ͧ��ҧ (ù�.)",
        "�� 2558 - ����ҵù�����ҧ�",
        "����ҵù�� - ����ҵù�����ҧ� (�Ѩ�غѹ)",
        "% ��º ù�. - ����ҵù�����ҧ� (�Ѩ�غѹ)",
        "����ҵù�� - ����ҵù�����ҧ� (�������ԧ)",
        "% ��º ù�. - ����ҵù�����ҧ� (�������ԧ)",
        "�������� �����駻� - ����ҵù�����ŧ��ҧ�",
        "����ҵù�� - ����ҵù�����ŧ��ҧ� ",
        "����ҵ� - ����ҵù�����ŧ��ҧ� (�����鹻�)",
        "% ��º�Ѻ�������·�駻� - ����ҵù�����ŧ��ҧ� (�����鹻�)",
        "�ѹ��� - ����ҳ����к��",
        "���������鹻� - ����ҳ����к�� ",
    );
    $column_name_eng = array(
        "�����ط���дѺ����纡ѡ�ͧ��ҧ (ù�.)",
        "�� 2558 - ����ҵù�����ҧ�",
        "����ҵù�� - ����ҵù�����ҧ� (�Ѩ�غѹ)",
        "% ��º ù�. - ����ҵù�����ҧ� (�Ѩ�غѹ)",
        "����ҵù�� - ����ҵù�����ҧ� (�������ԧ)",
        "% ��º ù�. - ����ҵù�����ҧ� (�������ԧ)",
        "�������� �����駻� - ����ҵù�����ŧ��ҧ�",
        "����ҵù�� - ����ҵù�����ŧ��ҧ� ",
        "����ҵ� - ����ҵù�����ŧ��ҧ� (��������� 1 �.�. 59)",
        "% ��º�Ѻ�������·�駻� - ����ҵù�����ŧ��ҧ� (��������� 1 �.�. 59)",
        "�ѹ��� - ����ҳ����к��",
        "��������� 1 �.�. 59 - ����ҳ����к�� ",
    );
    $region_english_name = array(
        "�Ҥ�˹��" => "Northern",
        "�Ҥ���ѹ�͡��§�˹��" => "Northeastern",
        "�Ҥ��ҧ" => "Central",
        "�Ҥ���ѹ��" => "Western",
        "�Ҥ���ѹ�͡" => "Eastern",
        "�Ҥ��" => "Southern",
    );
    $dam_english_name = array(
        "���͹���Ծ�" => "Bhumibol Dam",
        "���͹���ԡԵ��" => "Sirikit Dam",
        "���͹���Ѵ" => "Mae Ngat Somboon Chon Dam",
        "���͹������" => "Kiew Lom Dam",
        "���͹���ǧ" => "Mae Kuang Udom Thara",
        "���͹���Ǥ����" => "Kew Kor Mah Dam",
        "���͹�ǹ���" => "Kwae Noi Dam",
        "���͹�ӻ��" => "Lam Pao Dam",
        "���͹�ӵФͧ" => "Lam Takhong Dam",
        "���͹�Ӿ����ԧ" => "Lam Phra Phloeng Dam",
        "���͹����ٹ" => "Nam-oon Dam",
        "���͹�غ��ѵ��" => "Ubol Ratana Dam",
        "���͹���Թ��" => "Sirindhorn Dam",
        "���͹�����ó�" => "Chulabhorn Dam",
        "���͹������ǧ" => "Huai Luang Dam",
        "���͹�ӹҧ�ͧ" => "Lam Nang Rong Dam",
        "���͹��ź�" => "Mun Bon Dam",
        "���͹��Ӿا" => "Nam Pung Dam",
        "���͹����" => "Lam Chae Dam",
        "���͹����ѡ�" => "Pa Sak Jolasid Dam",
        "���͹��������" => "Krasieo Dam",
        "���͹�Ѻ����" => "Tub Salao Dam",
        "���͹��չ��Թ���" => "Srinagarind Dam",
        "���͹Ǫ���ŧ�ó" => "Vajiralongkorn Dam",
        "���͹�ҧ���" => "Bang Pra Dam",
        "���͹˹ͧ������" => "Nong Plalai Dam",
        "���͹��ͧ���Ѵ" => "Si-Yat Dam",
        "���͹�ع��ҹ��ҡ�ê�" => "Khun Dan Prakan Chon Dam",
        "���͹�������" => "Pra Sae Dam",
        "���͹�觡�Шҹ" => "Kaeng Krachan Dam",
        "���͹��ҳ����" => "Pran Buri Dam",
        "���͹�Ѫ������" => "Ratchaprapa Dam",
        "���͹�ҧ�ҧ" => "Bang Lang Dam"
    );
    foreach($html as $index => $cell):
        
        if(!preg_match("/,,/", $cell)){
            $cell = trim($cell);
            if($cell != $tmp_regionname){
                $tmp_regionname = $cell;
                $region[$tmp_regionname] = array();
                $region_name[] = $tmp_regionname;
                $region_name_eng[] = $region_english_name[trim($tmp_regionname)];
            }
            continue;
        }
        $clean = explode(",,", $cell);
        $name  = trim($clean[0]);
        $clean = array_slice($clean, 1, 12);
        $clean = array_combine($column_name, $clean);
        if(preg_match("/^���/", $name)){
            $summary[$name] = $clean;
            continue;
        }
        $dam_name[] = $name;
        if(!empty($dam_english_name[$name])){
            $dam_name_eng[] = $dam_english_name[$name];
        }else{
            $dam_name_eng[] = $name;
        }
        $region[$tmp_regionname][$name] = $clean;
    endforeach;

    echo json_encode(array(
        'region' => $region,
        'summary' => $summary,
        'all_region' => $region_name,
        'all_region_english' => $region_name_eng,
        'all_dam' => $dam_name,
        'all_dam_eng' => $dam_name_eng
    ), JSON_PRETTY_PRINT);
}else{
    echo json_encode(array('message' => 'incorrect date format'), JSON_PRETTY_PRINT);
}