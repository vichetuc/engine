<?php
/**
 * Minds Media API
 *
 * @version 1
 * @author Emi Balbuena
 */
namespace Minds\Controllers\api\v1\media;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class thumbnails implements Interfaces\Api, Interfaces\ApiIgnorePam
{

    /**
     * Forwards to or passes-thru the the entity's thumbnail
     * @param array $pages
     *
     * API:: /v1/media/thumbnails/:guid/:size
     */
    public function get($pages)
    {
        if (!$pages[0]) {
            exit;
        }

        Core\Security\ACL::$ignore = true;

        $size = isset($pages[1]) ? $pages[1] : null;
        $thumbnail = Di::_()->get('Media\Thumbnails')->get($pages[0], $size);

        if ($thumbnail instanceof \ElggFile) {
            $thumbnail->open('read');
            $contents = $thumbnail->read();

            header('Content-type: image/jpeg');
            header('Expires: ' . date('r', strtotime('today + 6 months')), true);
            header('Pragma: public');
            header('Cache-Control: public');
            header('Content-Length: ' . strlen($contents));

            $chunks = str_split($contents, 1024);
            foreach ($chunks as $chunk) {
                echo $chunk;
            }
        } elseif (is_string($thumbnail)) {
            \forward($thumbnail);
        }

        exit;
    }

    /**
     * POST Method
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * PUT Method
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * DELETE Method
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
