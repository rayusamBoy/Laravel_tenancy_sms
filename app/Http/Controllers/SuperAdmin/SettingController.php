<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Requests\SettingUpdate;
use App\Repositories\MyClassRepo;
use App\Repositories\SettingRepo;
use ColorThief\ColorThief;
use Illuminate\Support\Facades\Session;

class SettingController extends Controller
{
    protected $setting, $my_class;

    public function __construct(SettingRepo $setting, MyClassRepo $my_class)
    {
        $this->setting = $setting;
        $this->my_class = $my_class;
    }

    public function index()
    {
        $d['class_types'] = $this->my_class->getTypes();
        $d['settings'] = Qs::getSettings();

        return view('pages.super_admin.settings', $d);
    }

    public function update(SettingUpdate $req)
    {
        $sets = $req->except('_token', '_method', 'logo', 'login_and_related_pages_bg');
        $sets['lock_exam'] = $sets['lock_exam'] == 1 ? 1 : 0;
        foreach ($sets as $key => $value) {
            $this->setting->update($key, $value);
        }

        if ($req->hasFile('logo')) {
            $logo = $req->file('logo');
            $f = Qs::getFileMetaData($logo);
            $f['name'] = 'logo.' . $f['ext'];
            $f['path'] = $logo_path = Qs::getPublicUploadPath() . '/' . $f['name'];
            $logo->storeAs('public/' . $f['path']);
            $this->setting->update('logo', $logo_path);
        }

        if ($req->hasFile('login_and_related_pages_bg')) {
            $login_and_related_pages_bg = $req->file('login_and_related_pages_bg');
            $f = Qs::getFileMetaData($login_and_related_pages_bg);
            $f['name'] = 'login_and_related_pages_bg.' . $f['ext'];
            $f['path'] = $login_page_path = Qs::getPublicUploadPath() . '/' . $f['name'];
            $login_and_related_pages_bg->storeAs('public/' . $f['path']);
            $this->setting->update('login_and_related_pages_bg', $login_page_path);

            switch ($req->texts_and_bg_colors) {
                case 'from_img':
                    $dominant_color = ColorThief::getColor($req->file('login_and_related_pages_bg')->getPathname());
                    $bg_color = "rgb($dominant_color[0],$dominant_color[1],$dominant_color[2])";
                    $texts_color = Qs::get_color_from_color($bg_color);
                    $colors = $texts_color . Qs::getDelimiter() . $bg_color;
                    $this->setting->update('login_and_related_pgs_txts_and_bg_colors', $colors);
                    break;
                default:
                    $this->setting->update('login_and_related_pgs_txts_and_bg_colors', NULL);
                    break;
            }

            if ($req->has('show_login_and_related_pgs_preview'))
                Session::flash('show_login_and_related_pgs_preview', true);
        }

        return redirect()->route('settings.index')->with('flash_success', __('msg.update_ok'));
    }

    public function enable_analytics()
    {
        $this->setting->update('analytics_enabled', 1);
        return back()->with('flash_success', __('msg.update_ok'));
    }

    public function disable_analytics()
    {
        $this->setting->update('analytics_enabled', 0);
        return back()->with('flash_success', __('msg.update_ok'));
    }

    /**
     * Preview the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function preview_login_form()
    {
        $settings = Qs::getSettings();
        $data['colors'] = $colors = $settings->where('type', 'login_and_related_pgs_txts_and_bg_colors')->value('description');

        if ($colors !== null) {
            $colors_exploaded = explode(Qs::getDelimiter(), $colors);
            $data['texts_color'] = $colors_exploaded[0];
            $data['bg_color'] = $colors_exploaded[1];
        }

        return view('auth.login', $data);
    }
}
