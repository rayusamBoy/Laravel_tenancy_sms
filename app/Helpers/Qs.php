<?php

namespace App\Helpers;

use App\Models\ExamAnnounce;
use App\Models\ParentRelative;
use App\Models\Section;
use App\Models\Setting;
use App\Models\StudentRecord;
use App\Models\SubjectRecord;
use App\Models\UserType;
use DateTime;
use Dompdf\Adapter\CPDF;
use Hashids\Hashids;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use NotificationChannels\Fcm\FcmChannel;
use Parsedown;

class Qs
{
    public static function displayError($errors)
    {
        foreach ($errors as $err) {
            $data[] = $err;
        }
        return '<div class="alert alert-danger alert-styled-left alert-dismissible">
				    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
				    <span class="font-weight-semibold">Oops!</span> ' . implode(' ', $data) .
            '</div>';
    }

    public static function getAppCode()
    {
        return self::getSetting('system_title') ?? 'SMS';
    }

    public static function getDefaultUserImage()
    {
        return 'global_assets/images/user.png';
    }

    public static function getPanelOptions()
    {
        return
            '<div class="header-elements print-none">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="remove"></a>
                </div>
            </div>';
    }

    public static function displaySuccess($msg)
    {
        return "<div class=\"alert alert-success alert-bordered\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span>&times;</span><span class=\"sr-only\">Close</span></button> $msg </div>";
    }

    public static function getTeamSA()
    {
        return ['admin', 'super_admin'];
    }

    public static function getParent()
    {
        return ['parent'];
    }

    public static function getParentRelative($user_id)
    {
        return ParentRelative::where('user_id', $user_id)->first();
    }

    public static function getTeamAccount()
    {
        return ['admin', 'super_admin', 'accountant'];
    }

    public static function getTeamSAT()
    {
        return ['admin', 'super_admin', 'teacher'];
    }

    public static function getTeamSATC()
    {
        return ['admin', 'super_admin', 'teacher', 'companion'];
    }

    public static function getTeamSATCL()
    {
        return ['admin', 'super_admin', 'teacher', 'companion', 'librarian'];
    }

    public static function getTeamLibrary()
    {
        return ['super_admin', 'librarian', 'admin'];
    }

    public static function getTeamAcademic()
    {
        return ['admin', 'super_admin', 'teacher', 'student'];
    }

    public static function getTeamAdministrative()
    {
        return ['super_admin', 'accountant', 'admin'];
    }

    protected static function getHashidsInstance()
    {
        $salt = date('dMY') . tenant('id') ?? config('app.name');
        $hash = new Hashids($salt, 10);
        return $hash;
    }

    public static function hash($id)
    {
        return self::getHashidsInstance()->encode($id);
    }

    public static function decodeHash($str, $to_string = true)
    {

        $decoded = self::getHashidsInstance()->decode($str);
        return $to_string ? implode(',', $decoded) : $decoded;
    }

    public static function getUserRecord($remove = [], $values_only = true)
    {
        $data = [
            'Full name' => 'name',
            'Email' => 'email',
            'Phone number' => 'phone',
            'Telephone number' => 'phone2',
            'Date of birth' => 'dob',
            'Gender' => 'gender',
            'Address' => 'address',
            'Blood group' => 'bg_id',
            'Nationality' => 'nal_id',
            'State' => 'state_id',
            'LGA' => 'lga_id',
            'Religion' => 'religion'
        ];

        $array = $remove ? array_diff($data, $remove) : $data;
        return $values_only ? array_values($array) : $array;
    }

    public static function getStaffRecord($remove = [], $values_only = true)
    {
        $data = [
            'Employment date' => 'emp_date',
            'Employment number' => 'emp_no',
            'Confirmation date' => 'confirmation_date',
            'Licence number' => 'licence_number',
            'File number' => 'file_number',
            'Bank account number' => 'bank_acc_no',
            'TIN number' => 'tin_number',
            'Education level' => 'education_level',
            'Year graduated' => 'year_graduated',
            'Social security number' => 'ss_number',
            'College attended' => 'college_attended',
            'Role' => 'role',
            'Subjects studied' => 'subjects_studied',
            'Bank name' => 'bank_name',
            'Number of periods' => 'no_of_periods',
            'Place of living' => 'place_of_living'
        ];

        $array = $remove ? array_diff($data, $remove) : $data;
        return $values_only ? array_values($array) : $array;
    }

    public static function getParentRelativeRecord($remove = [], $values_only = true)
    {
        $data = [
            'User' => 'user_id',
            'Relative Full name' => 'name2',
            'Relative phone' => 'phone4',
            'Relative telephone' => 'phone3',
            'Relation with relative' => 'relation'
        ];

        $array = $remove ? array_diff($data, $remove) : $data;
        return $values_only ? array_values($array) : $array;
    }

    public static function getStudentData($remove = [], $values_only = true): array
    {
        $data = [
            'Class' => 'my_class_id',
            'Section' => 'section_id',
            'Admission number' => 'adm_no',
            'Parent' => 'my_parent_id',
            'Dorm' => 'dorm_id',
            'Dorm room number' => 'dorm_room_no',
            'Date admitted' => 'date_admitted',
            'House number' => 'house_no',
            'Age' => 'age',
            'Primary school name' => 'ps_name',
            'Secondary school name' => 'ss_name',
            'Birth certificate' => 'birth_certificate',
            'Disability' => 'disability',
            'Chronic health problems' => 'chp',
            'Parents status' => 'p_status',
            'Food taboos' => 'food_taboos'
        ];

        $array = $remove ? array_diff($data, $remove) : $data;
        return $values_only ? array_values($array) : $array;
    }

    /**
     * Convert number to corresponding letter (1 -> A, 2 -> B, etc.)
     * @param mixed $number
     * @return string
     */
    public static function numberToLetter($number)
    {
        return chr(64 + $number);
    }

    public static function userIsTeamAccount()
    {
        return in_array(auth()->user()->user_type, self::getTeamAccount());
    }

    public static function userIsTeamSA()
    {
        return in_array(auth()->user()->user_type, self::getTeamSA());
    }

    public static function userIsTeamSA2($user_type)
    {
        return in_array($user_type, self::getTeamSA());
    }

    public static function userIsTeamSAT()
    {
        return in_array(auth()->user()->user_type, self::getTeamSAT());
    }

    public static function userIsTeamSATC()
    {
        return in_array(auth()->user()->user_type, self::getTeamSATC());
    }

    public static function userIsTeamSATCL()
    {
        return in_array(auth()->user()->user_type, self::getTeamSATCL());
    }

    public static function userIsTeamLibrary()
    {
        return in_array(auth()->user()->user_type, self::getTeamLibrary());
    }

    public static function userIsAcademic()
    {
        return in_array(auth()->user()->user_type, self::getTeamAcademic());
    }

    public static function userIsAdministrative()
    {
        return in_array(auth()->user()->user_type, self::getTeamAdministrative());
    }

    public static function userIsAdmin()
    {
        return auth()->user()->user_type == 'admin';
    }

    public static function userIsLibrarian()
    {
        return auth()->user()->user_type == 'librarian';
    }

    public static function userIsAccountant()
    {
        return auth()->user()->user_type == 'accountant';
    }

    public static function userIsCompanion()
    {
        return auth()->user()->user_type == 'companion';
    }

    public static function userIsClassSectionTeacher()
    {
        return self::userIsTeacher() ? Section::where("teacher_id", auth()->id())->exists() : false;
    }

    public static function userIsClassSectionTeacher2($section_id)
    {
        return self::userIsTeacher() ? Section::where(["id" => $section_id, "teacher_id" => auth()->id()])->exists() : false;
    }

    public static function getUserType()
    {
        return auth()->user()->user_type;
    }

    public static function authUserGenderIsMale(): bool
    {
        return auth()->user()->gender == 'Male';
    }

    public static function authUserGenderIsFemale(): bool
    {
        return auth()->user()->gender == 'Female';
    }

    public static function getUserTypeById($type_id)
    {
        return UserType::where('id', $type_id)->get();
    }

    public static function getUserTypeId($user_type)
    {
        return UserType::where('title', $user_type)->value('id');
    }

    public static function userIsSuperAdmin(): bool
    {
        return auth()->user()->user_type == 'super_admin';
    }

    public static function userIsHead(): bool
    {
        return in_array(auth()->user()->id, self::getHeadSAIDs());
    }

    // Get ids of super admins heads
    public static function getHeadSAIDs(): array
    {
        return [1];
    }

    public static function userIsItGuy(): bool
    {
        return auth()->user()->user_type == 'it_guy';
    }

    public static function userIsStudent(): bool
    {
        return auth()->user()->user_type == 'student';
    }

    public static function userIsTeacher(): bool
    {
        return auth()->user()->user_type == 'teacher';
    }

    public static function userIsParent(): bool
    {
        return auth()->user()->user_type == 'parent';
    }

    public static function userIsStudent2($user_type): bool
    {
        return $user_type == 'student';
    }

    public static function userIsParent2($user_type): bool
    {
        return $user_type == 'parent';
    }

    public static function userIsStaff(): bool
    {
        return in_array(auth()->user()->user_type, self::getStaff());
    }

    public static function getStaff($remove = [])
    {
        $data = ['super_admin', 'admin', 'teacher', 'accountant', 'librarian', 'companion'];
        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }

    public static function getAllUserTypes($remove = [])
    {
        $data = ['super_admin', 'admin', 'teacher', 'accountant', 'librarian', 'student', 'parent', 'companion'];
        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }

    public static function getExamData($remove = [])
    {
        $data = ['name', 'term', 'category_id', 'number_format', 'class_type_id', 'exam_denominator', 'exam_student_position_by_value', 'ca_student_position_by_value', 'cw_denominator', 'hw_denominator', 'tt_denominator', 'tdt_denominator'];
        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }

    public static function headSA(int $user_id): bool
    {
        return in_array($user_id, self::getHeadSAIDs());
    }

    public static function userIsPTA()
    {
        return in_array(auth()->user()->user_type, self::getPTA());
    }

    public static function userIsPTAC()
    {
        return in_array(auth()->user()->user_type, self::getPTAC());
    }

    public static function userIsPTACL()
    {
        return in_array(auth()->user()->user_type, self::getPTACL());
    }

    public static function userIsPTACLA()
    {
        return in_array(auth()->user()->user_type, self::getPTACLA());
    }

    public static function userIsMyChild($student_id, $parent_id)
    {
        $data = ['user_id' => $student_id, 'my_parent_id' => $parent_id];
        return StudentRecord::where($data)->exists();
    }

    public static function getSRByUserID($user_id)
    {
        return StudentRecord::where('user_id', $user_id)->first();
    }

    public static function getPTA()
    {
        return ['super_admin', 'admin', 'teacher', 'parent'];
    }

    public static function getPTAC()
    {
        return ['super_admin', 'admin', 'teacher', 'parent', 'companion'];
    }

    public static function getPTACL()
    {
        return ['super_admin', 'admin', 'teacher', 'parent', 'companion', 'librarian'];
    }

    public static function getPTACLA()
    {
        return ['super_admin', 'admin', 'teacher', 'parent', 'companion', 'librarian', 'accountant'];
    }

    public static function getPublicUploadPath()
    {
        return 'uploads/';
    }

    public static function getUserUploadPath()
    {
        return 'uploads/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';
    }

    public static function getUploadPath($user_type)
    {
        return "uploads/$user_type";
    }

    public static function getFileMetaData($file)
    {
        $dataFile['name'] = $file->getClientOriginalName();
        $dataFile['ext'] = $file->getClientOriginalExtension();
        $dataFile['type'] = $file->getClientMimeType();
        $dataFile['size'] = self::formatBytes($file->getSize());

        return $dataFile;
    }

    public static function getDelimiter()
    {
        return '-';
    }

    public static function generateUserCode()
    {
        return substr(uniqid(mt_rand()), -7, 7);
    }

    public static function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = ['B', 'KB', 'MB', 'GB', 'TB'];

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }

    public static function getSetting($type, $use_central_connection = false)
    {
        if ($use_central_connection) {
            $db_connection = env('DB_CONNECTION', 'mysql');
            $settings = new Setting();
            $settings_table = $settings->getTable();

            return DB::connection($db_connection)->table($settings_table)->where('type', $type)->value('description');
        }

        return Setting::where('type', $type)->value('description');
    }

    public static function getSettings()
    {
        return Setting::all();
    }

    public static function getCurrentSession()
    {
        return self::getSetting('current_session');
    }

    public static function getNextSession()
    {
        $oy = self::getCurrentSession();
        $old_yr = explode('-', $oy);
        return ++$old_yr[0] . '-' . ++$old_yr[1];
    }

    public static function getSystemName()
    {
        return self::getSetting('system_name');
    }

    public static function getStringAbbreviation(string $system_name = NULL)
    {
        $system_name ??= self::getSystemName();

        preg_match_all('/(?<=\b)\w/iu', $system_name, $matches);
        return mb_strtoupper(implode('', $matches[0]));
    }

    public static function findMyChildren($parent_id)
    {
        return StudentRecord::where('my_parent_id', $parent_id)->with(['user', 'my_class'])->get();
    }

    public static function findTeacherSubjectRecs($teacher_id)
    {
        return SubjectRecord::where('teacher_id', $teacher_id)->with('subject')->get();
    }

    public static function findStudentRecord($user_id)
    {
        return StudentRecord::where('user_id', $user_id)->first();
    }

    public static function json($msg, $ok = TRUE, $arr = [])
    {
        return $arr ? response()->json($arr) : response()->json(['ok' => $ok, 'msg' => $msg]);
    }

    public static function jsonStoreOk()
    {
        return self::json(__('msg.store_ok'));
    }

    public static function jsonDownloadOk()
    {
        return self::json(__('msg.download_ok'));
    }

    public static function jsonUpdateOk()
    {
        return self::json(__('msg.update_ok'));
    }

    public static function jsonUpdateDenied()
    {
        return self::json(__('msg.denied'), FALSE);
    }

    public static function jsonOnyWithMsg($msg, $ok = TRUE)
    {
        return response()->json([$msg => $ok]);
    }

    public static function storeOk($routeName)
    {
        return self::goWithSuccess($routeName, __('msg.store_ok'));
    }

    public static function deleteOk($routeName)
    {
        return self::goWithSuccess($routeName, __('msg.del_ok'));
    }

    public static function updateOk($routeName)
    {
        return self::goWithSuccess($routeName, __('msg.update_ok'));
    }

    public static function goToRoute($goto, $status = 302, $headers = [], $secure = null)
    {
        $data = [];
        $to = (is_array($goto) ? $goto[0] : $goto) ?: 'dashboard';
        if (is_array($goto)) {
            array_shift($goto);
            $data = $goto;
        }
        return app('redirect')->to(route($to, $data), $status, $headers, $secure);
    }

    public static function goWithDanger($to = 'dashboard', $msg = NULL)
    {
        $msg ??= __('msg.rnf');
        return self::goToRoute($to)->with('flash_danger', $msg);
    }

    public static function goWithSuccess($to, $msg)
    {
        return self::goToRoute($to)->with('flash_success', $msg);
    }

    public static function getDaysOfTheWeek()
    {
        return ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    }

    public static function getExamAnnounces()
    {
        return ExamAnnounce::all();
    }

    public static function getExamAnnounce($exam_id)
    {
        return ExamAnnounce::where("exam_id", $exam_id)->orderBy("id", "desc")->first();
    }

    public static function getValidExamAnnounces()
    {
        return ExamAnnounce::where("duration", ">=", time())->get();
    }

    public static function examAnnounceDurationIsValid(int $exam_id): bool
    {
        $exam_announce = self::getExamAnnounce($exam_id);
        $diff_in_seconds = strtotime(date("Y-m-d H:i:s")) - strtotime($exam_announce->created_at);
        return ($diff_in_seconds < $exam_announce->duration) ? true : false;
    }

    public static function onlyDateFormat($tiemestamp)
    {
        if ($tiemestamp === null || empty($tiemestamp))
            return;
        $datetime = new DateTime($tiemestamp);
        return $datetime->format('l, j F Y');
    }

    public static function fullDateTimeFormat($tiemestamp)
    {
        if ($tiemestamp === null || empty($tiemestamp))
            return;
        $datetime = new DateTime($tiemestamp);
        return $datetime->format('l, j F Y. h:i A');
    }

    public static function isCurrentRoute($routeName)
    {
        return Route::is($routeName);
    }

    public static function getTableNames()
    {
        return [
            "books" => "books",
            "my_classes" => "classes",
            "class_types" => "class types",
            "dorms" => "dorms",
            "exams" => "exams",
            "exam_categories" => "exam categories",
            "grades" => "grades",
            "payments" => "payments",
            "subjects" => "subjects",
            "student_records" => "students",
            "staff_records" => "staff",
            "users" => "users",
            "exam_records" => "exam records"
        ];
    }

    public static function getTableCols($table_name)
    {
        return match ($table_name) {
            "users" => [
                "photo" => "photo",
                "name" => "full name",
                "religion" => "religion",
                "primary_id" => "primary ID",
                "secondary_id" => "secondary ID",
                "work" => "work",
                "address" => "address",
                "user_type" => "user type",
                "email" => "email",
                "gender" => "gender",
                "dob" => "date of birth",
                "phone" => "mobile number",
                "phone2" => "telephone number"
            ],
            "subjects" => [
                "subjects.name as subject_name" => "full name",
                "slug" => "short name",
                "my_classes.name as class_name" => "class name",
                "core" => "core"
            ],
            "student_records" => [
                "users.name as user" => "full name",
                "users.religion as religion" => "religion",
                "users.dob as dob" => "date of birth",
                "users.gender as gender" => "gender",
                "ps_name" => "primary school",
                "ss_name" => "secondary school",
                "disability" => "disability",
                "food_taboos" => "food taboos",
                "adm_no" => "admission number",
                "house_no" => "house number",
                "my_classes.name as class" => "class name",
                "sections.name as section" => "section name",
                "dorms.name as dorm" => "dormitory name",
                "dorm_room_no" => "dormitory room number",
                "date_admitted" => "date admitted",
                "users.address as address" => "address",
                "p_status" => "parents status",
                "parents.name as parent" => "parent name",
                "parents.phone as phone" => "parent phone",
                "parents.phone2 as phone2" => "parent phone2",
                "session" => "session"
            ],
            "staff_records" => [
                "users.name as user" => "full name",
                "users.username as username" => "username",
                "users.email as email" => "email",
                "users.dob as dob" => "date of birth",
                "users.gender as gender" => "gender",
                "users.religion as religion" => "religion",
                "users.primary_id as primary_id" => "primary_id",
                "users.secondary_id as secondary_id" => "secondary_id",
                "users.phone as phone" => "phone",
                "users.phone2 as phone2" => "phone two",
                "place_of_living" => "place of living",
                "role" => "role",
                "users.address as address" => "address",
                "users.dob as birthday" => "date of birth",
                "users.phone2 as mobile" => "telephone number",
                "emp_date" => "employment date",
                "emp_no" => "employment number",
                "file_number" => "file number",
                "tin_number" => "tin number",
                "licence_number" => "licence number",
                "bank_acc_no" => "bank account number",
                "bank_name" => "bank name",
                "ss_number" => "Social Security number",
                "no_of_periods" => "number of periods",
                "education_level" => "education level",
                "college_attended" => "college attended",
                "year_graduated" => "year graduated",
                "subjects_studied" => "subjects studied"
            ],
            "payments" => [
                "title" => "title",
                "amount" => "amount paid",
                "description" => "description",
                "method" => "payment method",
                "year" => "year"
            ],
            "grades" => [
                "grades.name as grade_name" => "name",
                "class_types.name as class_type_name" => "class type",
                "mark_from" => "mark from",
                "mark_to" => "mark to",
                "point" => "point",
                "remark" => "remark"
            ],
            "exam_categories" => ["name" => "name"],
            "exams" => ["name" => "name", "term" => "term", "year" => "year"],
            "dorms" => ["name" => "name", "description" => "description"],
            "class_types" => ["name" => "name", "code" => "code", "subjects_considered" => "subjects considered"],
            "my_classes" => ["name" => "name"],
            "books" => [
                "name" => "name",
                "author" => "author",
                "description" => "description",
                "book_type" => "boot type",
                "url" => "website address",
                "location" => "location",
                "total_copies" => "total copies",
                "issued_copies" => "issued copies"
            ],
            "exam_records" => [
                "users.name as student" => "student",
                "my_classes.name as class" => "class",
                "sections.name as section" => "section",
                "exams.name as exam" => "exam",
                "total" => "total",
                "ave" => "average",
                "grades.name as grade" => "grade",
                "divisions.name as division" => "division",
                "points" => "points",
                "pos" => "section position",
                "class_pos" => "class position",
                "p_comment" => "principal comment",
                "t_comment" => "teacher comment",
                "exam_records.year as year" => "exam year"
            ],
            default => NULL,
        };
    }

    public static function getSelectedTableCols($columns = [], $name)
    {
        $data = self::getTableCols($name);
        // Flip the array to get the keys. The keys are the actual database column names
        $flipped = array_flip($data);

        if (in_array("*", $columns))
            return implode(",", $flipped);
        else {
            $intersected = array_intersect($columns, $flipped);
            return implode(",", $intersected);
        }
    }

    public static function getPresentableTableCols($columns = [], $name): array
    {
        $data = self::getTableCols($name);
        // Flip the array to get the keys. The keys are the actual database column names
        $flipped = array_flip($data);

        if (in_array("*", $columns)) {
            $cols = array_flip($flipped);
        } else {
            $intersected = array_intersect($flipped, $columns);
            $cols = array_flip($intersected); // Flip again to ge the presentable table columns name
        }

        return $cols;
    }

    public static function getPaginationFromToTotalAsString($paginator)
    {
        $total = $paginator->total();
        $per_page = $paginator->perPage();
        $current_page_no = $paginator->currentPage();
        $to = $per_page * $current_page_no;
        // If it is the first page
        ($current_page_no == 1) ? $from = 1 : $from = $to - ($per_page - 1);
        // If current page is the last - re-assign to as total
        if ($paginator->lastPage() == $current_page_no)
            $to = $total;
        return "$from to $to of $total";
    }

    public static function convertEncoding(string $encoding)
    {
        return mb_convert_encoding($encoding, 'UTF-8', 'HTML-ENTITIES');
    }

    public static function currentSessionMatchesCurrentYear()
    {
        $session = self::getCurrentSession();
        $year = explode('-', $session)[0];
        return $year === date('Y');
    }

    public static function getStudentStatuses()
    {
        return [
            1 => "Active",
            0 => "Graduated"
        ];
    }

    public static function imgToBase64($img_path)
    {
        $type = pathinfo($img_path, PATHINFO_EXTENSION);
        //  $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n', 'timeout' => .5)));

        if (!file_exists($img_path))
            return;
        $data = file_get_contents($img_path, false);

        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }

    // Supported paper sizes by all pdf rendering interfaces
    public static function getDompdfSupportedPaperSizes(): array
    {
        return CPDF::$PAPER_SIZES;
    }

    public static function convertPointsToCm($points)
    {
        $a = $points / 28.346;
        return round($a, 1) . 'cm';
    }

    // Google analytics
    public static function googleAnalyticsSetUpOkAndEnabled(): bool
    {
        $credential_file = storage_path('/app/public/' . self::getSetting('google_analytic_service_account_credential_file'));

        return file_exists($credential_file) && self::getSetting('google_analytic_property_id') !== null && self::getSetting('google_analytic_tag_id') !== null && (int) self::getSetting('analytics_enabled') === 1;
    }

    public static function getSpatieAnalyticsPackagePeriods()
    {
        return [
            'days' => 'Days',
            'months' => 'Months',
            'years' => 'Years'
        ];
    }

    public static function getDbFacadesTable($table_name)
    {
        return DB::table($table_name);
    }

    /**
     * Get color (for texts) based on some other color (usually dominant color of an image).
     * @param string $rgb
     * @return string|null
     */
    public static function get_color_from_color(string $rgb): string|null
    {
        $pattern = '/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/';
        $brightness = 1;
        $color = null;

        if (preg_match($pattern, $rgb, $matches)) {
            // $matches[0] will contain the entire matched string
            // $matches[1], $matches[2], and $matches[3] will contain the captured groups (\d+)
            $red = $matches[1];
            $green = $matches[2];
            $blue = $matches[3];

            $ired = floor((255 - $red) * $brightness);
            $igreen = floor((255 - $green) * $brightness);
            $iblue = floor((255 - $blue) * $brightness);

            $color = "rgb($ired,$igreen,$iblue)";
        }

        return $color;
    }

    public static function getAppIcon(): string
    {
        return asset("images/icons/icon-rounded.png");
    }

    public static function getTenancyAwareIDCardsThemeDir($tenant_id = null)
    {
        $id = $tenant_id ?? tenant('id');
        return resource_path() . '/views/pages/support_team/students/id_cards/themes/' . config('tenancy.database.prefix') . $id . '/';
    }

    public static function getTenantStorageDir($tenant_id = null)
    {
        $id = $tenant_id ?? tenant('id');
        return storage_path() . '/' . config('tenancy.database.prefix') . $id . '/';
    }

    public static function is_serialized($data, $strict = true)
    {
        // If it isn't a string, it isn't serialized.
        if (!is_string($data)) {
            return false;
        }
        $data = trim($data);
        if ('N;' === $data) {
            return true;
        }
        if (strlen($data) < 4) {
            return false;
        }
        if (':' !== $data[1]) {
            return false;
        }
        if ($strict) {
            $lastc = substr($data, -1);
            if (';' !== $lastc && '}' !== $lastc) {
                return false;
            }
        } else {
            $semicolon = strpos($data, ';');
            $brace = strpos($data, '}');
            // Either ; or } must exist.
            if (false === $semicolon && false === $brace) {
                return false;
            }
            // But neither must be in the first X characters.
            if (false !== $semicolon && $semicolon < 3) {
                return false;
            }
            if (false !== $brace && $brace < 4) {
                return false;
            }
        }
        $token = $data[0];
        switch ($token) {
            case 's':
                if ($strict) {
                    if ('"' !== substr($data, -2, 1)) {
                        return false;
                    }
                } elseif (false === strpos($data, '"')) {
                    return false;
                }
            // Or else fall through.
            case 'a':
            case 'O':
                return (bool) preg_match("/^{$token}:[0-9]+:/s", $data);
            case 'b':
            case 'i':
            case 'd':
                $end = $strict ? '$' : '';
                return (bool) preg_match("/^{$token}:[0-9.E+-]+;$end/", $data);
        }

        return false;
    }

    public static function getActiveNotificationChannels($notifiable, $include_database = false, $include_email = false, $include_push_notification = false, $include_sms = false)
    {
        if (!self::is_serialized($notifiable->is_notifiable) || !is_array(unserialize($notifiable->is_notifiable)))
            return [];

        $is_notifiable = unserialize($notifiable->is_notifiable);
        $channels = [];

        // Check the user's status and available notification channels
        switch ($is_notifiable['status']) {
            case 1:
                if ($include_database) {
                    array_push($channels, 'database');
                }

                if ($is_notifiable['on_email_channel'] && $notifiable->hasVerifiedEmail() && self::getSetting('enable_email_notification') == 1 && $include_email) {
                    array_push($channels, 'mail');
                }

                if ($is_notifiable['on_push_channel'] && self::getSetting('enable_push_notification') == 1 && $include_push_notification) {
                    array_push($channels, FcmChannel::class);
                }

                if ($is_notifiable['on_sms_channel'] && self::getSetting('enable_sms_notification') == 1 && $include_sms) {
                    array_push($channels, 'vonage');
                }

                break;
        }

        return $channels;
    }

    public static function parsedown($input)
    {
        $parsedown = new Parsedown();
        return $parsedown->text($input);
    }
}
