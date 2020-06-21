<?php declare(strict_types=1);

if (! function_exists('is_valid_url')) {
    /**
     * 判断url是否合法
     *
     * @param  string   $url
     * @return boolean
     */
    function is_valid_url($url)
    {
        if (! preg_match('~^(#|//|https?://|mailto:|tel:)~', $url)) {
            return filter_var($url, FILTER_VALIDATE_URL) !== false;
        }

        return true;
    }
}
if (! function_exists('memory_usage')) {
    /**
     * 获取当前内存M
     * @return int|float
     */
    function memory_usage() {
        $memory = (!function_exists('memory_get_usage')) ? 0 : round(memory_get_usage()/1024/1024, 2);
        return $memory;
    }
}
if (! function_exists('dot_to_array_str')) {
    /**
     * 将'a.b'转换为'a[b]'
     * @param string $string
     *
     * @return string
     */
    function dot_to_array_str(string $string): string
    {
        $string = explode('.',$string);
        $ret = $string[0];
        unset($string[0]);
        foreach ($string as $value) {
            if (!empty($value)) {
                $ret .= "[{$value}]";
            }
        }
        return $ret;
    }
}
if (! function_exists('array_to_dot')) {
    /**
     * 将['a' => ['b' => 1]] 转换为 ['a.b' => 1]
     * @param  array       $array   数组
     * @param  int         $length  需要转换到的层数，如a.b即为 2
     * @param  int         $level
     * @param  string      $prepend
     * @return array
     */
    function array_to_dot(array $array, int $length = 0, int $level = 1, string $prepend = ''): array
    {
        $results = [];
        foreach ($array as $key => $value) {
            if ($level >= $length && $length !== 0) {
                $results[$prepend . $key] = $value;
            } elseif (is_array($value) && ! empty($value)) {
                $results = array_merge($results, array_to_dot($value, $length, $level + 1, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }
        return $results;
    }
}