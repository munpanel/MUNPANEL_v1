<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

/**
 * Return URL based on environment.
 */
function mp_url($url) {
    return env('APP_ENV') === 'prod' ? secure_url($url) : url($url);
}

//update 0: default, 1: update, 2: don't update
function cdn_url($url, $update = 0) {
    if (!\Config::get('cdn.enabled'))
        return mp_url($url);
    $url = trim($url, '/');
    $key = 'cdndate_'.sha1($url);
    $last = \Cache::get($key);
    //if (isset($last) && $last < time())
    if ($update == 0) {
        if ($lm = filemtime(public_path($url)))
        {
            if ((!isset($last)) || $last < $lm)
            {
                \Cache::forever($key, $lm);
                $update = 1;
            }
        } else
            $update = 1; // We will update if it is not a static resource even if mandatory updating parameter is not specified.
    }
    $url = \Config::get('cdn.prefix').'/'.$url;
    if ($update == 1) { // Do update resources for both Akamai and ChinaNetCenter
        //Akamai
        /*$rs_client = new \OpenCloud\Rackspace(\OpenCloud\Rackspace::US_IDENTITY_ENDPOINT, array(
            'username' => \Config::get('cdn.rackspace_username'),
            'apiKey'   => \Config::get('cdn.rackspace_key')
        ));
        $rs_service = $rs_client->cdnService();
        $akamai = $rs_service->getService(\Config::get('cdn.rackspace_sid'));
        $akamai->purgeAssets($url);*/
        //ChinaNetCenter
        $qiniu_auth = new \Qiniu\Auth(\Config::get('cdn.qiniu_ak'), \Config::get('cdn.qiniu_sk'));
        $bucketMgr = new \Qiniu\Storage\BucketManager($qiniu_auth);
        $bucketMgr->delete(\Config::get('cdn.qiniu_bucket'), $url);
    }
    //if (geoip(\Request::ip())->iso_code == 'CN') //ChinaNetCenter
        $domain = \Config::get('cdn.qiniu_domain');
    //else //Akamai
    //    $domain = \Config::get('cdn.rackspace_domain');
    return 'https://'.$domain.'/'.$url;
}

function textWithBr($text) {
    return nl2br(htmlspecialchars($text, ENT_QUOTES));
}
