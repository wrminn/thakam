 <?php
 // 20250729
 /*
    // $path = "C:/Users/acer/Downloads/thakam/thakam.go.th/activity_detail5675.html"; // แก้ path ตามจริง
    $path = $link;
    $html = file_get_contents($path);
    $dom = new DOMDocument();
    $dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
    $xpath = new DOMXPath($dom);

    if (!empty($content)) {
        $divs = $xpath->query('//div[@class="' . $content . '"]');
        if ($divs->length > 0) {
            $datanew = $divs->item(0);

            // 2. ลบ <script> และ <style> ที่อยู่ภายใน div นี้
            foreach (['script', 'style'] as $tag) {
                $nodes = $datanew->getElementsByTagName("$tag");
                for ($i = $nodes->length - 1; $i >= 0; $i--) {
                    $node = $nodes->item($i);
                    $node->parentNode->removeChild($node);
                }
            }

            // 3. ดึงข้อความใน <div class="datanew"> หลังลบ script
            $text = trim($datanew->textContent);
            echo "✅ ข้อความภายใน :<br>";
            echo $text . "<br>";
        } else {
            echo "ไม่พบ <div class=\"datanew\">\n";
        }
    }

    // if (!empty($img)) {
        $imgTags = $dom->getElementsByTagName('img');
        echo "✅ รายการภาพ:<br>";
        foreach ($imgTags as $img) {
            $src = $img->getAttribute('src');
            echo $src . "<br>";
        }
    // }*/
    ?>