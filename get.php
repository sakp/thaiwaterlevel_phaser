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
        if(preg_match("/ภาคเหนือ/", $table)):
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
        if(preg_match("/ภาคเหนือ/", $cell)):
            $start = $index;
            break;      
        endif;
    endforeach;
    $html            = array_slice($html, $start);
    $tmp_regionname  = "";
    $region          = array();
    $region_eng      = array();
    $summary         = array();
    $summary_eng     = array();
    $dam_name        = array();
    $dam_name_eng    = array();
    $region_name     = array();
    $region_name_eng = array();
    $column_name     = array(
        "ความจุที่ระดับน้ำเก็บกักของอ่าง (รนก.)",
        "ปีที่แล้ว - ปริมาตรน้ำในอ่างฯ",
        "ปริมาตรน้ำ - ปริมาตรน้ำในอ่างฯ (ปัจจุบัน)",
        "% เทียบ รนก. - ปริมาตรน้ำในอ่างฯ (ปัจจุบัน)",
        "ปริมาตรน้ำ - ปริมาตรน้ำในอ่างฯ (ใช้การได้จริง)",
        "% เทียบ รนก. - ปริมาตรน้ำในอ่างฯ (ใช้การได้จริง)",
        "ค่าเฉลี่ย รวมทั้งปี - ปริมาตรน้ำไหลลงอ่างฯ",
        "ปริมาตรน้ำ - ปริมาตรน้ำไหลลงอ่างฯ ",
        "ปริมาตร - ปริมาตรน้ำไหลลงอ่างฯ (ตั้งแต่ต้นปี)",
        "% เทียบกับค่าเฉลี่ยทั้งปี - ปริมาตรน้ำไหลลงอ่างฯ (ตั้งแต่ต้นปี)",
        "วันนี้ - ปริมาณน้ำระบาย",
        "สะสมตั้งแต่ต้นปี - ปริมาณน้ำระบาย ",
    );
    $column_name_eng = array(
        "Total Capacity",
        "Water Volume - last year",
        "Water Volumn - Storage level",
        "Water Volumn (%) - Storage level)",
        "Usable storage - Storage level",
        "Usable storage (%) - Storage level",
        "Cumulative total this water year - Inflow",
        "Volume - Inflow ",
        "Volume - Inflow (this year)",
        "compare to this water year in average (%) - Inflow (this year)",
        "Today - Outflow",
        "Cumulative total this water year - Outflow",
    );
    $region_english_name = array(
        "ภาคเหนือ"              => "Northern",
        "ภาคตะวันออกเฉียงเหนือ" => "Northeastern",
        "ภาคกลาง"               => "Central",
        "ภาคตะวันตก"            => "Western",
        "ภาคตะวันออก"           => "Eastern",
        "ภาคใต้"                => "Southern",
    );
    $dam_english_name = array(
        "เขื่อนภูมิพล"          => "Bhumibol Dam",
        "เขื่อนสิริกิติ์"       => "Sirikit Dam",
        "เขื่อนแม่งัด"          => "Mae Ngat Somboon Chon Dam",
        "เขื่อนกิ่วลม"          => "Kiew Lom Dam",
        "เขื่อนแม่กวง"          => "Mae Kuang Udom Thara",
        "เขื่อนกิ่วคอหมา"       => "Kew Kor Mah Dam",
        "เขื่อนแควน้อย"         => "Kwae Noi Dam",
        "เขื่อนลำปาว"           => "Lam Pao Dam",
        "เขื่อนลำตะคอง"         => "Lam Takhong Dam",
        "เขื่อนลำพระเพลิง"      => "Lam Phra Phloeng Dam",
        "เขื่อนน้ำอูน"          => "Nam-oon Dam",
        "เขื่อนอุบลรัตน์"       => "Ubol Ratana Dam",
        "เขื่อนสิรินธร"         => "Sirindhorn Dam",
        "เขื่อนจุฬาภรณ์"        => "Chulabhorn Dam",
        "เขื่อนห้วยหลวง"        => "Huai Luang Dam",
        "เขื่อนลำนางรอง"        => "Lam Nang Rong Dam",
        "เขื่อนมูลบน"           => "Mun Bon Dam",
        "เขื่อนน้ำพุง"          => "Nam Pung Dam",
        "เขื่อนลำแซะ"           => "Lam Chae Dam",
        "เขื่อนป่าสักฯ"         => "Pa Sak Jolasid Dam",
        "เขื่อนกระเสียว"        => "Krasieo Dam",
        "เขื่อนทับเสลา"         => "Tub Salao Dam",
        "เขื่อนศรีนครินทร์"     => "Srinagarind Dam",
        "เขื่อนวชิราลงกรณ"      => "Vajiralongkorn Dam",
        "เขื่อนบางพระ"          => "Bang Pra Dam",
        "เขื่อนหนองปลาไหล"      => "Nong Plalai Dam",
        "เขื่อนคลองสียัด"       => "Si-Yat Dam",
        "เขื่อนขุนด่านปราการชล" => "Khun Dan Prakan Chon Dam",
        "เขื่อนประแสร์"         => "Pra Sae Dam",
        "เขื่อนแก่งกระจาน"      => "Kaeng Krachan Dam",
        "เขื่อนปราณบุรี"        => "Pran Buri Dam",
        "เขื่อนรัชชประภา"       => "Ratchaprapa Dam",
        "เขื่อนบางลาง"          => "Bang Lang Dam"
    );
    foreach($html as $index => $cell):
        
        if(!preg_match("/,,/", $cell)){
            $cell = trim($cell);
            if($cell != $tmp_regionname){
                $tmp_regionname                  = $cell;
                $tmp_regionname_eng              = $region_english_name[trim($tmp_regionname)];
                $region[$tmp_regionname]         = array();
                $region_eng[$tmp_regionname_eng] = array();
                $region_name[]                   = $tmp_regionname;
                $region_name_eng[]               = $region_english_name[trim($tmp_regionname)];
            }
            continue;
        }
        $clean     = explode(",,", $cell);
        $name      = trim($clean[0]);
        $clean     = array_slice($clean, 1, 12);
        $clean     = array_combine($column_name, $clean);
        $clean_eng = array_combine($column_name_eng, $clean);
        if(preg_match("/^รวม/", $name)){
            $summary[$name] = $clean;
            $summary_eng[$dam_english_name[$name]] = $clean_eng;
            continue;
        }
        $dam_name[] = $name;
        $name_eng   = $dam_english_name[$name];
        if(!empty($dam_english_name[$name])){
            $dam_name_eng[] = $dam_english_name[$name];
        }else{
            $dam_name_eng[] = $name;
        }
        $region[$tmp_regionname][$name]             = $clean;
        $region_eng[$tmp_regionname_eng][$name_eng] = $clean_eng;
    endforeach;

    echo json_encode(array(
        'region'             => $region,
        'region_eng'         => $region_eng,
        'summary'            => $summary,
        'summary_eng'        => $summary_eng,
        'all_region'         => $region_name,
        'all_region_english' => $region_name_eng,
        'all_dam'            => $dam_name,
        'all_dam_eng'        => $dam_name_eng
    ), JSON_PRETTY_PRINT);
}else{
    echo json_encode(array('message' => 'incorrect date format'), JSON_PRETTY_PRINT);
}
