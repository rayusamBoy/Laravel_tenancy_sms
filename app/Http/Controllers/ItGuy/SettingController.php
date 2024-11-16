<?php

namespace App\Http\Controllers\ItGuy;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Repositories\MyClassRepo;
use App\Repositories\SettingRepo;
use ColorThief\ColorThief;
use Illuminate\Http\Request;
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
        $d['settings'] = Qs::getSettings();

        return view('pages.it_guy.settings', $d);
    }

    public function update(Request $req)
    {
        $sets = $req->except('_token', '_method', 'logo', 'login_and_related_pages_bg');
        foreach ($sets as $key => $value) {
            $this->setting->update($key, $value);
        }

        if ($req->hasFile('login_and_related_pages_bg')) {
            $login_and_related_pages_bg = $req->file('login_and_related_pages_bg');
            $f = Qs::getFileMetaData($login_and_related_pages_bg);
            $f['name'] = 'login_and_related_pages_bg.' . $f['ext'];
            $login_and_related_pages_bg->storeAs('public/' . Qs::getPublicUploadPath() . $f['name']);
            $login_page_path = 'storage/uploads/' . $f['name'];
            $this->setting->update('login_and_related_pages_bg', $login_page_path);

            if ($req->has('show_login_and_related_pgs_preview')) {
                $dominant_color = ColorThief::getColor($req->file('login_and_related_pages_bg')->getPathname());
                $bg_color = "rgb($dominant_color[0],$dominant_color[1],$dominant_color[2])";
                $texts_color = Qs::get_color_from_color($bg_color);
                $colors = $texts_color . Qs::getDelimiter() . $bg_color;

                if ($req->texts_and_bg_colors === 'default')
                    $this->setting->update('login_and_related_pgs_txts_and_bg_colors', NULL);
                elseif ($req->texts_and_bg_colors === 'from_img')
                    $this->setting->update('login_and_related_pgs_txts_and_bg_colors', $colors);

                Session::flash('show_login_and_related_pgs_preview', true);
            }
        }
        
        return redirect()->route('settings_non_tenancy.index')->with('flash_success', __('msg.update_ok'));
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
