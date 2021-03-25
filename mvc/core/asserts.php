<?php
//********************************************************************************** */
class Asserts
{
    //****************************************************************************** */
    static function setAsserts() {
        if (!headers_sent()) {
            $request = explode('/', $_SERVER['REQUEST_URI']);

            $file = '';
            $path = '';

            if (!empty($request[1])) {
                $path = $request[1];
            }

            if (!empty($request[2])) {
                $file = $request[2];
            }

            if (($path == 'css') || ($path == 'js') || ($path == 'img')) {
                switch ($path) {
                    case 'css':
                        header('Content-Type: text/css');
                        break;
                    case 'js':
                        header('Content-Type: text/javascript');
                        break;
                    case 'img':
                        header('Content-Type: image/png');
                        break;
                }

                if (strpos($file, '.map') !== false) {
                    header('Content-Type: application/json');
                }

                return file_get_contents('mvc/asserts/' . $path . '/' . $file);
            }
        }
        return null;
    }
    //****************************************************************************** */
}
//********************************************************************************** */