<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Requests\Analytic\GoogleSetup;
use App\Http\Requests\Analytic\Request as AnalyticRequest;
use App\Repositories\SettingRepo;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

class AnalyticController extends Controller
{
    protected $setting;
    public function __construct(SettingRepo $setting)
    {
        $this->setting = $setting;
    }

    public function index($data = [])
    {
        $settings = Qs::getSettings();
        $d['google_analytic_property_id'] = $google_analytic_property_id = $settings->where('type', 'google_analytic_property_id')->value('description');
        $d['google_analytic_tag_id'] = $settings->where('type', 'google_analytic_tag_id')->value('description');

        $credential_file = storage_path('/app/public/' . $settings->where('type', 'google_analytic_service_account_credential_file')->value('description'));
        $gtag_code_structure_file = __DIR__ . '/../../../../storage/app/analytics/google-tag-manager-code-structure.php';

        // These are usually configured statically; meaning they are not expected to change (see the analytic config file)
        // For multitenant scope we will update accordingly during runtime
        config(['analytics.property_id' => $google_analytic_property_id]); // Update analytic property id during runtime
        config(['analytics.service_account_credentials_json' => $credential_file]); // Update credetial file during runtime

        if (file_exists($credential_file))
            $d['credential_file'] = $credential_file;
        if (file_exists($gtag_code_structure_file))
            $d['gtag_code_structure_file'] = $gtag_code_structure_file;

        $d['analytics_enabled'] = (int) $settings->where('type', 'analytics_enabled')->value('description') == 1;

        if (Qs::googleAnalyticsSetUpOkAndEnabled()) {
            $d['google_analytics_setup_ok'] = true;

            $d['data'] = $data = count($data) <= 0 ? ['period_number' => 6, 'period_name' => 'months'] : $data;

            $period = match ($data["period_name"]) {
                'days' => Period::days($data["period_number"]),
                'months' => Period::months($data["period_number"]),
                'years' => Period::years($data["period_number"]),
            };

            try {
                // Visitors and page views
                $d['vstrsAndPgVw'] = Analytics::fetchVisitorsAndPageViews($period);
                // Visitors and page views by date
                $d['vstrsAndPgVwByDt'] = Analytics::fetchVisitorsAndPageViewsByDate($period);
                // Total visitors and pageviews
                $d['ttlVstrsAndPgVw'] = Analytics::fetchTotalVisitorsAndPageViews($period);
                // Most visited pages
                $d['mostVstdPgs'] = Analytics::fetchMostVisitedPages($period);
                // Top referrers
                $d['topReferrers'] = Analytics::fetchTopReferrers($period);
                // User Types
                $d['userTypes'] = Analytics::fetchUserTypes($period);
                // Top browsers
                $d['topBrowsers'] = Analytics::fetchTopBrowsers($period);
                // Top countries
                $d['topCountries'] = Analytics::fetchTopCountries($period);
                // Top operating systems
                $d['topOses'] = Analytics::fetchTopOperatingSystems($period);
            } catch (\Exception $e) {
                unset($d['google_analytics_setup_ok']); // Analytics not OK anymore
                return view('pages.super_admin.analytics', $d)->withErrors([$e->getMessage()]);
            }
        }

        return view('pages.super_admin.analytics', $d);
    }

    public function fetch_data(AnalyticRequest $req)
    {
        $d = $req->only(['period_number', 'period_name']);
        $period_names = array_keys(Qs::getSpatieAnalyticsPackagePeriods());

        if (!in_array($d['period_name'], $period_names))
            return redirect()->route('analytics.index')->with('flash_warning', __('msg.invalid_period_name'));

        return $this->index($d);
    }

    public function google_setup(GoogleSetup $req)
    {
        $google_analytic_property_id = $req->google_analytic_property_id;
        $google_analytic_tag_id = rtrim($req->google_analytic_tag_id, "_"); // Remove any '_' data mask residue characters

        if ($google_analytic_property_id !== null)
            $this->setting->update('google_analytic_property_id', $google_analytic_property_id);
        if ($google_analytic_tag_id !== null)
            $this->setting->update('google_analytic_tag_id', $google_analytic_tag_id);

        if ($req->hasFile('service_account_credential_file')) {
            $file = $req->file('service_account_credential_file');
            $fdata = Qs::getFileMetaData($file);
            $fpath = 'analytics/service-acc-credential-file.' . $fdata['ext'];
            $file->storeAs('public/', $fpath);

            $this->setting->update('google_analytic_service_account_credential_file', $fpath);
        }

        return back()->with('flash_success', __('msg.update_ok'));
    }
}
