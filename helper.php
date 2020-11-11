// upload file (base64 or file)

if (!function_exists('uploadFilePro')) {
    function uploadFilePro($file, $name, $address, $type = null, $format = null)
    {
        if (!is_file($file)) {
            $result = null;
            $data = explode(',', $file);
            $fileInfo = explode('/', explode(';', explode(':', $data[0])[1])[0]);
            if (empty($type)) {
                $result['type'] = $fileInfo[0];
            }else{
                $result['type'] = $type;
            }
            if (empty($format)) {
                $result['format'] = $fileInfo[1];
            }else{
                $result['format'] = $format;
            }
            $fileName = date('Ymd_his') . '_' . $name . '.' . $result['format'];
            $image = base64_decode($data[1]);
            $result['root_address'] = public_path($address . '/' . $fileName);
            if (!\File::isDirectory(public_path($address))) {
                \File::makeDirectory(public_path($address), 0777, true, true);
            }
            $file = fopen($result['root_address'], 'wb');
            $result['address'] = '/' . $address . '/' . $fileName;
            fwrite($file, $image);
            fclose($file);
            return $result;
        } else {
            if (!\File::isDirectory(public_path($address))) {
                \File::makeDirectory(public_path($address), 0777, true, true);
            }
            $fileName = '/' . $name;
            $new = $file->move(public_path($address), $fileName);
            $rootAddress = public_path($address) . $fileName;
            $Type = explode('/', mime_content_type($rootAddress));
            if (empty($type)){
                $result['type'] = $Type[0];
            }else{
                $result['type'] = $type;
            }
            if (empty($format)){
                $result['format'] = $Type[1];
            }else{
                $result['format'] = $format;
            }
            $fileName = '/' . date('Ymd_his') . '_' . $name . '.' . $result['format'];
            $new->move(public_path($address), $address . $fileName);
            return [
                'root_address' => public_path($address) . $fileName,
                'address' => '/' . $address . $fileName,
                'type' => $result['type'],
                'format' => $result['format']
            ];
        }

    }
}


// georgian to jalali


if (!function_exists('toJalali')) {
    function toJalali($dateTime)
    {
        $val = strtotime($dateTime);
        $date = DateTime::createFromFormat('U', $val);
        $date->setTimeZone(new DateTimeZone('Asia/Tehran'));
        $newDate = $date->format('Y/m/d');


        $newDate = explode('/', $newDate);
        $newDate = CalendarUtils::toJalali($newDate[0], $newDate[1], $newDate[2]);
        $newDate = implode('/', $newDate);

        return [
            'date' => $newDate,
            'time' => $date->format('H:m:s')
        ];
    }
}

// convert digits (en, fa)
function convertDigits($number, $result = 'fa')
{
    $arr = array();
    switch ($result){
        case 'en':
            $arr = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
            $num = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
            break;
        case 'fa':
            $arr = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
            $num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
            break;
    }
    return str_replace($arr, $num, $number);
}
