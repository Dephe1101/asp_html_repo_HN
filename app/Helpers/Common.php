<?php

namespace App\Helpers;

use Illuminate\Support\Facades\View;
use Jenssegers\Agent\Agent;

class Common
{
    protected static $_platform = '',
        $_viewNamePrefix = '',
        $_viewShared = [],
        $_routeNamePrefix = '';

    /**
     * @return string
     */
    public static function platform()
    {
        // $isResponsive = config('theme.is_responsive');

        // if (!$isResponsive) {
        //     // $agent = new Agent();
        //     // static::$_platform = $agent->isMobile() ? 'sp' : 'pc';
        //     static::$_platform = '';
        // }
        static::$_platform = '';
        return static::$_platform;
    }

    /**
     * @return boolean
     */
    public static function isPlatformPC()
    {
        return ('pc' === static::$_platform);
    }

    /**
     * @return boolean
     */
    public static function isPlatformSP()
    {
        return ('sp' === static::$_platform);
    }

    /**
     * Simple helper, return view's name with prefix
     * @param string $name
     * @return string
     */
    public static function viewName($name)
    {
        // return (static::platform() . '.' . (static::$_viewNamePrefix ? (static::$_viewNamePrefix . '.') : '') . $name);
        return ((static::$_viewNamePrefix ? (static::$_viewNamePrefix . '.') : '') . $name);
    }

    /**
     * Render view with injected data
     *
     * @param string $viewName
     * @param array $data
     * @return Illuminate\View\View
     */
    public static function view($viewName, array $data = [])
    {
        
        // Inject data
        $viewShared = static::$_viewShared;
        if (\is_array($viewShared)) {
            // route name prefix
            if (!isset($viewShared['routeNamePrefix']) && static::$_routeNamePrefix) {
                $viewShared['routeNamePrefix'] = static::$_routeNamePrefix;
            }
        }
        $data = \array_replace($viewShared, (array) $data);

        
        // View in resource/views
        if(View::exists(Common::viewName($viewName))){
            return view(Common::viewName($viewName), $data);
        }
        
        // View in theme/default/views
        return view($viewName, $data);
    }

    /**
     * Get an instance of the redirector.
     *
     * @param  string|null  $to
     * @param  array  $headers
     * @param  bool|null  $secure
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public static function redirect($to = null, $headers = [], $secure = null)
    {
        return redirect($to, 302, $headers, $secure);
    }
}
