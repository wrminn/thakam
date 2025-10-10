<?php

// if (isset($_POST['save'])) {
// $link = $_POST['t_link'];
// $content = $_POST['t_ccontent'];
// $img = $_POST['t_cimg'];
// $title = $_POST['t_title'];
// $detail = $_POST['t_detail'];

$content = "datanew";
$title = "";
$detail = "table";

libxml_use_internal_errors(true);

$path = "C:/Users/acer/Downloads/thakam/thakam.go.th/activity_detail5675.html"; // เปลี่ยน path ตามจริง
$html = file_get_contents($path);

$dom = new DOMDocument();
$dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
$xpath = new DOMXPath($dom);

$divs = $xpath->query('//div[@class="' . $content . '"]');

if ($divs->length > 0) {
    $datanew = $divs->item(0);

    // ลบ <script> และ <style> เฉพาะภายใน div
    foreach (['script', 'style'] as $tag) {
        $nodes = $datanew->getElementsByTagName($tag);
        for ($i = $nodes->length - 1; $i >= 0; $i--) {
            $node = $nodes->item($i);
            $node->parentNode->removeChild($node);
        }
    }

    // ---------------------------
    // 1. หา <div> ที่เป็นหัวข้อ (ไม่มี class หรือมี class )
    // ---------------------------
    $headers = [];
    foreach ($datanew->getElementsByTagName('div') as $div) {
        $class = $div->getAttribute('class');
        if ($class === '' || $class === $title) {
            $headers[] = trim($div->textContent);
        }
    }

    // ---------------------------
    // 2. หา <table> ที่เป็นเนื้อหา
    // ---------------------------
    $tables = $datanew->getElementsByTagName($detail);
    $contents = [];
    foreach ($tables as $table) {
        $contents[] = trim($table->textContent);
    }

    // ---------------------------
    // 3. แสดงผลแบบจับคู่ หัวข้อ / เนื้อหา
    // ---------------------------
    echo "ผลลัพธ์ :<br>";
    foreach ($headers as $index => $head) {
        echo "หัวข้อ: " . $head . "<br>";
        echo "เนื้อหา:<br>" . ($contents[$index] ?? '(ไม่มีเนื้อหา)') . "<br>";
    }

    // หาภาพในคลาสที่ใส่เข้ามาในตอนแรก
    $imgTags = $xpath->query('.//img[@class]', $datanew);
    echo "✅ รายการภาพ:<br>";
    foreach ($imgTags as $img) {
        $src = $img->getAttribute('src');
        echo $src . "<br>";
        $images[] =  $img->getAttribute('src');
    }

    // เก็บข้อมูลลงไฟล์ 
    $file = 'activityNew.json';
    $oldData = [];
    if (file_exists($file)) {
        $json = file_get_contents($file);
        $oldData = json_decode($json, true) ?? [];
    }

    $keyword = "โพสเมื่อ :";
    $pos = strpos($headers[1], $keyword);
 
    if ($pos !== false) {
        $dateText = trim(substr($headers[1], $pos + strlen($keyword)));
        echo "วันที่ที่ได้: " . $dateText;
    }

    $newData = [
        "post_type_id" => "3",
        "date" => $dateText,
        'title_name'  => $headers[0],
        'details'  => $contents[1],
        'photos' => $images
    ];
    $oldData[] = $newData;

    // file_put_contents('activity.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    file_put_contents($file, json_encode($oldData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
} else {
    echo "ไม่พบ <div class=\"datanew\">\n";
}
/*
} else {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ดึงข้อมูล</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>

    <body>
        <div class="container">
            <h2>เก็บข้อมูล</h2>
            <form class="content-1" action="" method="post">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">ลิงก์</label>
                    <input type="text" class="form-control" name="t_link" required>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="exampleInputEmail1" class="form-label">class content</label>
                        <input type="text" class="form-control" name="t_ccontent">
                    </div>
                    <div class="col">
                        <label for="exampleInputEmail1" class="form-label">class img</label>
                        <input type="text" class="form-control" name="t_cimg">
                        <div id="emailHelp" class="form-text">กรณีที่ img ไม่มี class ปล่อยว่าง</div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="exampleInputEmail1" class="form-label">class หัวข้อ</label>
                        <input type="text" class="form-control" name="t_title">
                    </div>
                    <div class="col">
                        <label for="exampleInputEmail1" class="form-label">class รายละเอียด</label>
                        <input type="text" class="form-control" name="t_detail">
                    </div>
                </div>

                <button type="submit" name="save" class="btn btn-primary">Submit</button>
            </form>
        </div>

    </body>
    <script src="/js/bootstrap.bundle.min.js"></script>

    </html>
<?php
}
?>
*/
