<?php

namespace App\Http\Controllers\SupportTeam;

use Dompdf\Dompdf;
use App\Http\Controllers\Controller;
use App\Helpers\Qs;
use App\Http\Requests\QueryBuilderRequest;
use App\Repositories\UserRepo;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class QueryBuilderController extends Controller
{
    protected $user;

    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }

    /**
     * Return the query builder index view to the caller
     */
    public function index()
    {
        $d["queryed"] = false;
        $d["user_types"] = $this->user->getAllNotStudentType()->where('title', '!=', 'parent');

        return view("pages.support_team.query_builder.index", $d);
    }

    /**
     * Generate query selector data based on the request
     * @param QueryBuilderRequest $req
     */
    public function select(QueryBuilderRequest $req)
    {
        $d["user_types"] = $this->user->getAllNotStudentType()->where('title', '!=', 'parent');

        // If is query select
        if ($req->type == "select") {
            $d["queryed"] = true;

            $from = $req->from;
            $select_var = $req->select;

            // Sanitize input - trying to minimize the risk of an attack
            $req["input"] = strip_tags(trim($req->input));
            $req["input_two"] = strip_tags(trim($req->input_two));

            $d["select"] = $select = Qs::getSelectedTableCols($select_var, $from); // Get the actual table columns
            $d["placeholder"] = $placeholder = Qs::getPresentableTableCols($select_var, $from);

            // If table column name not found in the list - deny
            if ($select == NULL)
                return back()->with('flash_danger', __('msg.denied'));

            $expression = DB::raw($select);

            $data = self::getData($req, $expression);

            if (isset($data["msg"]))
                return back()->with("pop_warning", $data["msg"]);

            $d["records"] = $data["records"];

            // The query presentation to the user
            $d["query"] = str_replace("placeholder", implode(", ", str_replace("_", " ", $placeholder)), str_replace(["_", "."], " ", $data["query2"]));
            $d["count"] = collect($data["records"])->count();

            return view("pages.support_team.query_builder.index", $d);
        }

        return back()->with('flash_danger', __('msg.denied'));
    }

    public function getData($req, $expression)
    {
        $from = $req->from;
        $where = $req->where;
        $where_two = $req->where_two == 'none' ? NULL : $req->where_two;
        $condition = $req->condition;
        $condition_two = $req->condition_two;
        $input = $req->input;
        $input_two = $req->input_two;
        $limit = $req->limit;
        // Explode string and take the first part of the space separated parts if any
        // ie., for the string 'users.name as user' we need 'users.name'
        $orderby_column = explode(" ", $req->orderby_column)[0];
        $orderby_direction = explode(" ", $req->orderby_direction)[0];

        // If the condition or condition_two is 'like'. Finds any values that have "input or input_two" text in any position
        if ($condition === "like")
            $input = "%" . $input . "%";
        if ($condition_two === "like")
            $input = "%" . $input_two . "%";

        if ($where != 'none' || $input != NULL) {
            // Explode space separated string. Here the target is for the where columns that uses aliases.
            // Eg., from set column 'users.name as student' it will take 'users.name'
            $exploded = explode(" ", $where);
            $where = $exploded[0]; // Take the first part

            $d["query2"] = "select distinct placeholder from $from where $where $condition '$input'"; // This is used for query presentation to the user

            if ($where_two != NULL or $input_two != NULL) {
                $exploded_two = explode(" ", $where_two); // Explode space separated string. Here the target is for the where columns that uses aliases
                $where_two = $exploded_two[0]; // Take the first part

                $d["query2"] = "select distinct placeholder from $from where $where $condition '$input' and $where_two $condition_two '$input_two'"; // Same as the one above
            }

            switch ($from) {
                case "student_records":
                    $d["records"] = ($where_two != NULL or $input_two != NULL)
                        ? DB::table("student_records")
                            ->join('users', function ($join) {
                                $join->on('users.id', '=', 'student_records.user_id');
                            })
                            ->leftJoin("users as parents", function ($leftjoin) {
                                $leftjoin->on('parents.id', '=', 'student_records.my_parent_id');
                            })
                            ->join('my_classes', function ($join) {
                                $join->on('my_classes.id', '=', 'student_records.my_class_id');
                            })
                            ->join('sections', function ($join) {
                                $join->on('sections.id', '=', 'student_records.section_id');
                            })
                            ->join('dorms', function ($join) {
                                $join->on('dorms.id', '=', 'student_records.dorm_id');
                            })
                            ->where($where, $condition, "?")
                            ->where($where_two, $condition_two, "?")
                            ->setBindings([$input, $input_two])
                            ->select($expression)
                            ->whereNull('users.deleted_at')
                            ->distinct()
                            ->limit($limit)
                            ->orderBy('users.' . $orderby_column, $orderby_direction)
                            ->get()
                        : DB::table("student_records")
                            ->join('users', function ($join) {
                                $join->on('users.id', '=', 'student_records.user_id');
                            })
                            ->leftJoin("users as parents", function ($leftjoin) {
                                $leftjoin->on('parents.id', '=', 'student_records.my_parent_id');
                            })
                            ->join('my_classes', function ($join) {
                                $join->on('my_classes.id', '=', 'student_records.my_class_id');
                            })
                            ->join('sections', function ($join) {
                                $join->on('sections.id', '=', 'student_records.section_id');
                            })
                            ->join('dorms', function ($join) {
                                $join->on('dorms.id', '=', 'student_records.dorm_id');
                            })
                            ->where($where, $condition, "?")
                            ->setBindings([$input])
                            ->select($expression)
                            ->whereNull('users.deleted_at')
                            ->distinct()
                            ->limit($limit)
                            ->orderBy('users.' . $orderby_column, $orderby_direction)
                            ->get();
                case "staff_records":
                    $d["records"] = ($where_two != NULL or $input_two != NULL)
                        ? DB::table("staff_records")
                            ->join('users', function ($join) {
                                $join->on('users.id', '=', 'staff_records.user_id');
                            })
                            ->where($where, $condition, "?")
                            ->where($where_two, $condition_two, "?")
                            ->setBindings([$input, $input_two])
                            ->select($expression)
                            ->distinct()
                            ->limit($limit)
                            ->orderBy($orderby_column, $orderby_direction)
                            ->get()
                        : DB::table("staff_records")
                            ->join('users', function ($join) {
                                $join->on('users.id', '=', 'staff_records.user_id');
                            })
                            ->where($where, $condition, "?")
                            ->setBindings([$input])
                            ->select($expression)
                            ->distinct()
                            ->limit($limit)
                            ->orderBy($orderby_column, $orderby_direction)
                            ->get();
                case "exam_records":
                    $d["records"] = ($where_two != NULL or $input_two != NULL)
                        ? DB::table("exam_records")
                            ->join('users', function ($join) {
                                $join->on('users.id', '=', 'exam_records.student_id');
                            })
                            ->join('my_classes', function ($join) {
                                $join->on('my_classes.id', '=', 'exam_records.my_class_id');
                            })
                            ->join('exams', function ($join) {
                                $join->on('exams.id', '=', 'exam_records.exam_id');
                            })
                            ->join('sections', function ($join) {
                                $join->on('sections.id', '=', 'exam_records.section_id');
                            })
                            ->join('divisions', function ($join) {
                                $join->on('divisions.id', '=', 'exam_records.division_id');
                            })
                            ->join('grades', function ($join) {
                                $join->on('grades.id', '=', 'exam_records.grade_id');
                            })
                            ->where($where, $condition, "?")
                            ->where($where_two, $condition_two, "?")
                            ->setBindings([$input, $input_two])
                            ->select($expression)
                            ->distinct()
                            ->limit($limit)
                            ->orderBy($orderby_column, $orderby_direction)
                            ->get()
                        : DB::table("exam_records")
                            ->join('users', function ($join) {
                                $join->on('users.id', '=', 'exam_records.student_id');
                            })
                            ->join('my_classes', function ($join) {
                                $join->on('my_classes.id', '=', 'exam_records.my_class_id');
                            })
                            ->join('exams', function ($join) {
                                $join->on('exams.id', '=', 'exam_records.exam_id');
                            })
                            ->join('sections', function ($join) {
                                $join->on('sections.id', '=', 'exam_records.section_id');
                            })
                            ->join('divisions', function ($join) {
                                $join->on('divisions.id', '=', 'exam_records.division_id');
                            })
                            ->join('grades', function ($join) {
                                $join->on('grades.id', '=', 'exam_records.grade_id');
                            })
                            ->where($where, $condition, "?")
                            ->setBindings([$input])
                            ->select($expression)
                            ->distinct()
                            ->limit($limit)
                            ->orderBy($orderby_column, $orderby_direction)
                            ->get();
                case "grades":
                    $d["records"] = ($where_two != NULL or $input_two != NULL)
                        ? DB::table("grades")
                            ->join('class_types', function ($join) {
                                $join->on('grades.class_type_id', '=', 'class_types.id');
                            })
                            ->where($where, $condition, "?")
                            ->where($where_two, $condition_two, "?")
                            ->setBindings([$input, $input_two])
                            ->select($expression)
                            ->distinct()
                            ->limit($limit)
                            ->orderBy($orderby_column, $orderby_direction)
                            ->get()
                        : DB::table("grades")
                            ->join('class_types', function ($join) {
                                $join->on('grades.class_type_id', '=', 'class_types.id');
                            })
                            ->where($where, $condition, "?")
                            ->setBindings([$input])
                            ->select($expression)
                            ->distinct()
                            ->limit($limit)
                            ->orderBy($orderby_column, $orderby_direction)
                            ->get();
                case "subjects":
                    $d["records"] = ($where_two != NULL or $input_two != NULL)
                        ? DB::table("subjects")
                            ->join('my_classes', function ($join) {
                                $join->on('subjects.my_class_id', '=', 'my_classes.id');
                            })
                            ->where($where, $condition, "?")
                            ->where($where_two, $condition_two, "?")
                            ->setBindings([$input, $input_two])
                            ->select($expression)
                            ->distinct()
                            ->limit($limit)
                            ->orderBy($orderby_column, $orderby_direction)
                            ->get()
                        : DB::table("subjects")
                            ->join('my_classes', function ($join) {
                                $join->on('subjects.my_class_id', '=', 'my_classes.id');
                            })
                            ->where($where, $condition, "?")
                            ->setBindings([$input])
                            ->select($expression)
                            ->distinct()
                            ->limit($limit)
                            ->orderBy($orderby_column, $orderby_direction)
                            ->get();
                default:
                    $d["records"] = ($where_two != NULL or $input_two != NULL)
                        ? DB::table($from)
                            ->select($expression)
                            ->where($where, $condition, "?")
                            ->where($where_two, $condition_two, "?")
                            ->setBindings([$input, $input_two])
                            ->distinct()
                            ->limit($limit)
                            ->orderBy($orderby_column, $orderby_direction)
                            ->get()
                        : DB::table($from)
                            ->select($expression)
                            ->where($where, $condition, "?")
                            ->setBindings([$input])
                            ->distinct()
                            ->limit($limit)
                            ->orderBy($orderby_column, $orderby_direction)
                            ->get();
            }
        } elseif ($where != 'none' && ($where_two != 'none' || $input_two != NULL))
            return [
                "msg" => "The first where clause is empty. You must use it first before using the second where clause."
            ];
        else {
            switch ($from) {
                case "exam_records":
                    $d["records"] = DB::table("exam_records")
                        ->join('users', function ($join) {
                            $join->on('users.id', '=', 'exam_records.student_id');
                        })
                        ->join('my_classes', function ($join) {
                            $join->on('my_classes.id', '=', 'exam_records.my_class_id');
                        })
                        ->join('exams', function ($join) {
                            $join->on('exams.id', '=', 'exam_records.exam_id');
                        })
                        ->join('sections', function ($join) {
                            $join->on('sections.id', '=', 'exam_records.section_id');
                        })
                        ->join('divisions', function ($join) {
                            $join->on('divisions.id', '=', 'exam_records.division_id');
                        })
                        ->join('grades', function ($join) {
                            $join->on('grades.id', '=', 'exam_records.grade_id');
                        })
                        ->where("exam_records.year", Qs::getCurrentSession()) // Limit only this year records
                        ->select($expression)
                        ->distinct()
                        ->limit($limit)
                        ->orderBy($orderby_column, $orderby_direction)
                        ->get();
                case "student_records":
                    $d["records"] = DB::table("student_records")
                        ->join('users', function ($join) {
                            $join->on('users.id', '=', 'student_records.user_id');
                        })
                        ->leftJoin("users as parents", function ($leftjoin) {
                            $leftjoin->on('parents.id', '=', 'student_records.my_parent_id');
                        })
                        ->join('my_classes', function ($join) {
                            $join->on('my_classes.id', '=', 'student_records.my_class_id');
                        })
                        ->join('sections', function ($join) {
                            $join->on('sections.id', '=', 'student_records.section_id');
                        })
                        ->join('dorms', function ($join) {
                            $join->on('dorms.id', '=', 'student_records.dorm_id');
                        })
                        ->select($expression)
                        ->whereNull('users.deleted_at')
                        ->limit($limit)
                        ->orderBy($orderby_column, $orderby_direction)
                        ->distinct()
                        ->get();
                case "staff_records":
                    $d["records"] = DB::table("staff_records")
                        ->join('users', function ($join) {
                            $join->on('users.id', '=', 'staff_records.user_id');
                        })
                        ->select($expression)
                        ->distinct()
                        ->limit($limit)
                        ->orderBy($orderby_column, $orderby_direction)
                        ->get();
                case "grades":
                    $d["records"] = DB::table("grades")
                        ->join('class_types', function ($join) {
                            $join->on('grades.class_type_id', '=', 'class_types.id');
                        })
                        ->select($expression)
                        ->distinct()
                        ->limit($limit)
                        ->orderBy($orderby_column, $orderby_direction)
                        ->get();
                case "subjects":
                    $d["records"] = DB::table("subjects")
                        ->join('my_classes', function ($join) {
                            $join->on('subjects.my_class_id', '=', 'my_classes.id');
                        })
                        ->select($expression)
                        ->distinct()
                        ->limit($limit)
                        ->orderBy($orderby_column, $orderby_direction)
                        ->get();
                default:
                    $d["records"] = DB::table($from)
                        ->select($expression)
                        ->distinct()
                        ->limit($limit)
                        ->orderBy($orderby_column, $orderby_direction)
                        ->get();
            }

            $orderby_direction_extended = $orderby_direction . "ending";
            $limit = empty($limit) ? '' : 'limit ' . $limit;

            $d["query2"] = "select distinct placeholder from $from orderby $orderby_column $orderby_direction_extended $limit";
        }

        return $d;
    }

    public function print_staff_data(HttpRequest $req)
    {
        Validator::make($req->toArray(), [
            'user_types' => 'required|array',
            'user_types.*' => 'string|in:' . implode(",", Qs::getAllUserTypes(['student', 'parent'])),
            'per_page' => 'integer|min:3|max:15|required',
            'paper_size' => 'required|string|in:' . implode(",", array_keys(Qs::getDompdfSupportedPaperSizes())),
            'paper_orientation' => 'sometimes|nullable|in:landscape'
        ], [], ['per_page' => 'number of data per page', 'user_types.*' => 'user type'])->validate();

        $user_types = $req->user_types;

        $teachers = DB::table('users')
            ->leftJoin('staff_records', function ($leftjoin) {
                $leftjoin->on('users.id', '=', 'staff_records.user_id');
            })
            ->leftJoin('lgas', function ($leftjoin) {
                $leftjoin->on('staff_records.place_of_living', '=', 'lgas.id');
            })
            ->select([
                'users.photo',
                'users.name AS teacher\'s name',
                'staff_records.role AS Teacher\'s role',
                'staff_records.place_of_living',
                'users.dob AS date of birth',
                'users.phone AS phone number',
                'users.email',
                'users.primary_id',
                'users.secondary_id',
                'staff_records.emp_date AS employment date',
                'staff_records.emp_no AS employment number',
                'staff_records.confirmation_date',
                'staff_records.licence_number',
                'staff_records.bank_acc_no AS bank account number',
                'staff_records.bank_name',
                'staff_records.file_number',
                'staff_records.tin_number',
                'staff_records.ss_number',
                'staff_records.education_level',
                'staff_records.college_attended',
                'staff_records.year_graduated',
                'staff_records.subjects_studied',
                'staff_records.no_of_periods AS number of periods',
            ])
            ->whereIn("users.user_type", $user_types)
            ->orderBy('users.name')
            ->get();

        $teachers = $teachers->map(function ($teacher) {
            $teacher->photo = Qs::imgToBase64($teacher->photo); // Update photo from normal url to base64 encoding format required by dompdf.
            return $teacher;
        });

        foreach ($teachers->sortBy('name') as $teacher) {
            foreach ($teacher as $key => $value) {
                $keys[] = $key; // Take table names from the collection
            }
        }

        $d['logo'] = Qs::imgToBase64(Qs::getSetting('logo'));

        // Get unique array keys - the seleted table names
        $d['keys'] = array_unique($keys);

        $teachers_arr = $teachers->toArray();
        $total = count($teachers_arr);
        $per_page = $req->per_page;
        $data_str = '';

        $d['last_page'] = $last_page = max((int) ceil($total / $per_page), 1);

        // Slice teachers data as per page number.
        // Avoid long data that will result to long content when printing (horizontally), such that it will overflow outside of printed page, thus losing data.
        // The slice generages custom pages cocanated as string based on the per page number.
        for ($i = 1; $i <= $last_page; $i++) {
            $point = $i * $per_page - $per_page;
            $starting_point = ($i === $last_page) ? $point - $total : $point;
            $d['staff'] = array_slice($teachers_arr, $starting_point, $per_page, true);
            $d['page_number'] = $i;
            $data_str .= view("pages.support_team.query_builder.print.staff_data.body", $d)->render();
        }

        $data['staff'] = $data_str;
        $paper_size = $req->paper_size ?? 'a3';
        $paper_orientation = $req->paper_orientation ?? 'portrait';

        $dompdfhtml = view("pages.support_team.query_builder.print.staff_data.index", $data)->render();
        $options = [
            'defaultPaperSize' => 'A3',
            'chroot' => public_path(),
        ];
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($dompdfhtml);
        $dompdf->setPaper($paper_size, $paper_orientation);
        $dompdf->render();
        $dompdf->stream(Qs::getAppCode() . ' Staff.pdf');

        return redirect()->route('query_builder.index');
    }
}
