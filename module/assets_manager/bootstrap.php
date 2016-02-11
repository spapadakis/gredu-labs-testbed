<?php
/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

return function (Slim\App $app) {

    $mimeTypes = [
        'txt'  => 'text/plain',
        'htm'  => 'text/html',
        'html' => 'text/html',
        'php'  => 'text/html',
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'json' => 'application/json',
        'xml'  => 'application/xml',
        'swf'  => 'application/x-shockwave-flash',
        'flv'  => 'video/x-flv',

        // images
        'png'  => 'image/png',
        'jpe'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg'  => 'image/jpeg',
        'gif'  => 'image/gif',
        'bmp'  => 'image/bmp',
        'ico'  => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif'  => 'image/tiff',
        'svg'  => 'image/svg+xml',
        'svgz' => 'image/svg+xml',

        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',

        // audio/video
        'mp3' => 'audio/mpeg',
        'qt'  => 'video/quicktime',
        'mov' => 'video/quicktime',

        // adobe
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai'  => 'application/postscript',
        'eps' => 'application/postscript',
        'ps'  => 'application/postscript',

        // ms office
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',

        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    ];

    $container = $app->getContainer();
    $events    = $container['events'];
    $events('on', 'app.bootstrap', function ($stop, $app, $container) use ($mimeTypes) {
            $app->add(function (
                  ServerRequestInterface $req,
                  ResponseInterface $res,
                  callable $next
        ) use ($mimeTypes, $container) {

            $res = $next($req, $res);
            if (404 === $res->getStatusCode()) {
                $settings = $container['settings']['assets'];

                $file = array_reduce($settings['paths'], function ($file, $path) use ($req) {
                    if (false !== $file) {
                        return $file;
                    }

                    $file = $path . $req->getUri()->getPath();
                    if (is_readable($file)) {
                        return $file;
                    }

                    return false;
                }, false);

                if (!$file) {
                    return $res;
                }

                $ext = strtolower(array_pop(explode('.', $file)));
                $mime = array_key_exists($ext, $mimeTypes)
                    ? $mimeTypes[$ext] : mime_content_type($file);
                $contents = file_get_contents($file);

                if (is_writable($settings['web_dir'])) {
                    $destFile = $settings['web_dir'] . $req->getUri()->getPath();
                    $destPath = dirname($destFile);

                    if (!is_dir($destPath)) {
                        mkdir($destPath);
                    }
                    file_put_contents($destFile, $contents);
                }

                $res = new Response(200);
                $res->withHeader('Content-Type', $mime);
                $res->getBody()->write($contents);
            }

            return $res;
        });
    }, -10000);
};
