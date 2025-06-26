<?php

use App\Models\GeneralSetting;

function getSetting($key, $default = null)
{
    static $settings = null;

    if ($settings === null) {
        $settings = \App\Models\GeneralSetting::first();
    }

    if ($settings && isset($settings->$key)) {
        return $settings->$key;
    }

    return $default;
}

function getOrganizationLogo($default = null)
{
    $logo = getSetting('site_logo');

    if ($logo && file_exists(public_path('storage/' . $logo))) {
        return asset('storage/' . $logo);
    }

    return $default ?: asset('assets/img/LogoCompany.png');
}

function getOrganizationName($default = 'Kelurahan')
{
    return getSetting('organization_name', $default);
}

function getOrganizationAddress($default = '')
{
    return getSetting('organization_address', $default);
}

function getSiteTitle($default = 'SILOK')
{
    return getSetting('site_title', $default);
}

function getSiteName($default = 'SILOK')
{
    return getSetting('site_name', $default);
}
