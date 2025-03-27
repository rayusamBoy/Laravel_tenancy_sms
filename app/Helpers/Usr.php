<?php

namespace App\Helpers;

use App\Models\BloodGroup;
use App\Models\ClassType;
use App\Models\Lga;
use App\Models\MyClass;
use App\Models\Nationality;
use App\Models\StudentRecord;
use App\Models\Ticket;
use App\User;

class Usr extends Qs
{
    public static function getUsersByIds($ids, $with_trashed = false)
    {
        return $with_trashed ? User::withTrashed()->whereIn("id", $ids)->get() : User::whereIn("id", $ids)->get();
    }

    public static function getEducationLevels()
    {
        return ['Primary', 'Secondary', 'Advanced', 'Certificate', 'Diploma', 'Degree', 'Master', 'Doctoral', 'PhD'];
    }

    public static function getLgas()
    {
        return Lga::all();
    }

    public static function getStaffRoles()
    {
        return [
            'Master',
            'Mistress',
            'BASA',
            'Second Master',
            'Second Mistress',
            'Academic Master',
            'Academic Mistress',
            'Section Leader',
            'Class Teacher',
            'Ordinary Teacher',
            'Part-time Teacher',
            'Volunteer Teacher',
            'Guest Teacher',
            'Statistician',
            'Cashier',
            'Counselor',
            'Registrar',
            'Security Guard',
            'Cook',
            'Waiter',
            'Librarian',
            'An IT person',
            'Patron',
            'Matron',
        ];
    }

    public static function getPredefinedSubjects()
    {
        $subjects = [
            'Qualifying Test',
            'Civics',
            'History',
            'Geography',
            'Bible Knowledge',
            'Elimu ya Dini ya Kiislamu',
            'Fine Art',
            'Music',
            'Physical Education',
            'Theatre Arts',
            'Kiswahili',
            'English Language',
            'French Language',
            'Literature in English',
            'Arabic Language',
            'Chinese Language',
            'Physics',
            'Chemistry',
            'Biology',
            'Agricultural Science',
            'Engineering Science',
            'Information and Computer Studies',
            'Basic Mathematics',
            'Additional Mathematics',
            'Food and Nutrition',
            'Textiles and Dressmaking',
            'Commerce',
            'Book-Keeping',
            'Building Construction',
            'Architectural Draughting',
            'Surveying',
            'Carpentry and Joinery',
            'Brickwork and Masonry',
            'Painting and Sign Writing',
            'Plumbing',
            'Electrical Engineering Science',
            'Radio and Television Servicing',
            'Electrical Draughting',
            'Refrigeration and Air Conditioning',
            'Plant and Equipment Maintenance',
            'Workshop Technology',
            'Motor Vehicle Mechanics',
            'Welding and Metal Fabrication',
            'Fitting and Turning',
            'Auto Electrics',
            'Mechanical Draughting',
            'Foundry and Blacksmith',
            'General Studies',
            'Divinity',
            'Islamic Knowledge',
            'Agriculture',
            'Computer Science',
            'Basic Applied Mathematics',
            'Advanced Mathematics',
            'Economics',
            'Accountancy',
            'Food and Human Nutrition'
        ];

        return $subjects;
    }

    public static function getStudentParentsStatus()
    {
        return [
            'An Orphan',
            'Not Orphan',
            'The Child of a single Mother',
        ];
    }

    public static function getDisabilities()
    {
        return [
            'People who are blind or who have partial vision' => 'Vision Impairment',
            'People who are deaf or hard of hearing' => 'Hearing Impairment',
            'People with illness that affect the mind or brain (the way a person thinks, feels, and acts). Includes, bipolar disorder, depression, schizophrenia, anxiety and personality disorder.' => 'Mental Illness',
            'A person that may have significant limitations in the skills needed to live and work in the community. Including, difficulties with communication, self-care, social skills, safety, and self-direction' => 'Intellectual Disability',
            'Refers to any type of brain damage that occurs after birth. The injury may occur because of infection, disease, lack of oxygen or trauma to the head.' => 'Acquired Brain Injury',
            'It is an umbrella description which includes Autistic disorder, Asperger\'s syndrome and atypical autism. It affects the way information is teken in and stored in the brain.' => 'Autism Spectrum Disorder',
            'That is some aspect of a person\'s pysical functioning, usually either their mobility, dexterity, or stamina, is affected' => 'Physical Disability',
        ];
    }

    public static function getClasses($id)
    {
        return MyClass::orderBy('name', 'asc')->where('class_type_id', $id)->with(['class_type', 'section'])->get();
    }

    public static function getClassTypes()
    {
        return ClassType::orderBy('id')->get();
    }

    public static function getStudents($data)
    {
        return StudentRecord::with('user')->where($data)->get()->whereNotNull('user');
    }

    public static function getClassStudents($class_id)
    {
        return StudentRecord::where(['my_class_id' => $class_id])->with('user')->get()->whereNotNull('user');
    }

    public static function getStudentRecordByIds($ids, $relationship = [])
    {
        return StudentRecord::whereIn("id", $ids)->with($relationship)->get();
    }

    public static function getStudentRecordByUserId($id, $relationship = [])
    {
        return StudentRecord::where('user_id', $id)->with($relationship)->get();
    }

    // Students Classes
    public static function renderClasses($id)
    {
        $collection = '';
        foreach (self::getClasses($id) as $class) {
            $collection .= '<th colspan="' . count($class->section) . '" class="text-center">' . $class->name . '</th>';
        }
        return $collection;
    }

    // Class Sections
    public static function renderClassSections($id)
    {
        $collection = '';
        foreach (self::getClasses($id) as $class) {
            foreach ($class->section as $section) {
                $collection .= "<td>{$section->name}</td>";
            }
        }
        return $collection;
    }

    // Total Male Students per Section ie., A, B, and C
    public static function renderMaleCountPerSection($id)
    {
        $collection = $counts = '';
        foreach (self::getClasses($id) as $class) {
            foreach ($class->section as $section) {
                $counts .= '<td>' . self::getStudents(['my_class_id' => $class->id, 'section_id' => $section->id, 'grad' => 0])->where('user.gender', 'Male')->count() . '</td>';
            }
            $collection .= $counts;
            $counts = '';
        }
        return $collection;
    }

    // Total Female Students per Section ie., A, B, and C
    public static function renderFemaleCountPerSection($id)
    {
        $collection = $counts = '';
        foreach (self::getClasses($id) as $class) {
            foreach ($class->section as $section) {
                $counts .= '<td>' . self::getStudents(['my_class_id' => $class->id, 'section_id' => $section->id, 'grad' => 0])->where('user.gender', 'Female')->count() . '</td>';
            }
            $collection .= $counts;
            $counts = '';
        }
        return $collection;
    }

    // Total Students (Male and Female) per Section
    public static function renderTotalPerSection($id)
    {
        $collection = $counts = '';
        foreach (self::getClasses($id) as $class) {
            foreach ($class->section as $section) {
                $counts .= '<td>' . self::getStudents(['my_class_id' => $class->id, 'section_id' => $section->id, 'grad' => 0])->count() . '</td>';
            }
            $collection .= $counts;
            $counts = '';
        }
        return $collection;
    }

    // Total Students (Male and Female) per Class
    public static function renderTotalPerClass($id)
    {
        $counts = '';
        foreach (self::getClasses($id) as $class) {
            $counts .= '<td colspan="' . count($class->section) . '">' . self::getStudents(['my_class_id' => $class->id, 'grad' => 0])->count() . '</td>';
        }
        return $counts;
    }

    public static function renderTable($id)
    {
        if (self::getClasses($id)->count() <= 0)
            return 'No record found.';

        return
            '<table class="table table-sm table-responsive text-center">
                <thead>
                    <tr><th>Class</th>' . self::renderClasses($id) . ' </tr>
                    <tr><th>Section</th>' . self::renderClassSections($id) . '</tr>
                </thead>
                <tbody>
                    <tr><td>Male</td>' . self::renderMaleCountPerSection($id) . '</tr>
                    <tr><td>Female</td>' . self::renderFemaleCountPerSection($id) . '</tr>
                </tbody>
                <tfoot>
                    <tr><td>Total per Section</td>' . self::renderTotalPerSection($id) . '</tr>
                    <tr><td>Total per Class</td>' . self::renderTotalPerClass($id) . ' </tr>
                </tfoot>
            </table>';
    }

    public static function getNationality($nal_id)
    {
        return Nationality::where('id', $nal_id)->get();
    }

    public static function createAvatar($name, $code, $user_type)
    {
        $words = explode(" ", $name);
        // Assume the names parts (first, middle, surname) are separated by a space.
        // Take the first letter of the name parts in upper case.
        $initials = strtoupper(substr($words[0], 0, 1) . (array_key_exists(1, $words) && $words[1] != "" ? substr($words[1], 0, 1) : (array_key_exists(2, $words) && $words[2] != "" ? substr($words[2], 0, 1) : substr($words[0], 1, 1))));

        // Define a background color and text color for the avatar
        $textColor = '#' . substr(md5($name), 0, 6); // Use a unique text color based on the name
        // $bgColor = '#ffffff'; // White background color

        // Create an image with the initials and colors
        $image = imagecreate(200, 200);

        // $bg = imagecolorallocate($image, hexdec(substr($bgColor, 1, 2)), hexdec(substr($bgColor, 3, 2)), hexdec(substr($bgColor, 5, 2))); // White background color manipulation
        $bg = imagecolorallocatealpha($image, 0, 0, 0, 127); // Tranparent background
        $text = imagecolorallocate($image, hexdec(substr($textColor, 1, 2)), hexdec(substr($textColor, 3, 2)), hexdec(substr($textColor, 5, 2)));

        imagefill($image, 0, 0, $bg);
        imagettftext($image, 60, 0, 40, 130, $text, public_path('global_assets/fonts/CASTELAR.ttf'), $initials);

        // Try to create directory first if does not exists
        $dir_for_db = Qs::getUploadPath($user_type) . $code;
        $dir_for_store = storage_path() . '/app/public/' . Qs::getUploadPath($user_type) . $code;

        if (!file_exists($dir_for_store))
            mkdir($dir_for_store);
        // Save the image to a file
        $avatar_path = "$dir_for_db/photo.png";
        $img_path = "$dir_for_store/photo.png";

        imagepng($image, $img_path);
        imagedestroy($image);

        return $avatar_path;
    }

    public static function getBloodGroups()
    {
        return BloodGroup::all();
    }

    public static function getBookStatuses(): array
    {
        return [
            'Due' => 'This means that the item is being read by another and is due to be returned on the date listed.',
            'In Library' => 'This means that the item is available for a user to borrow.',
            'Lib Use Only' => 'This means that the item may only be used within the library.',
            'Not Available' => 'This means that the item is temporary not available due to special circumstances.',
            'Missing' => 'This means that the item has been reported missing and/or is being searched for by library staff.',
            'Lost' => 'This means that the item it has officially reported as lost and/or is being evaluated for replacement.',
            'Damaged' => 'This means that the item is damaged and/or is being evaluated for replacement.',
            'Billed' => 'This means that the item is not available that is it was not returned and the borrower has been billed for the replacement cost.',
        ];
    }

    public static function tenancyInitilized(): bool
    {
        return tenancy()->initialized;
    }

    public static function getAccountStatuses(array $remove = []): array
    {
        $statuses = ['active', 'inactive', 'suspended', 'blocked'];

        return $remove ? array_values(array_diff($statuses, $remove)) : $statuses;
    }

    public static function getTenantAwarePhoto(string $url): string
    {
        return self::tenancyInitilized() ? (str_contains($url, 'global_assets') ? asset($url) : tenant_asset($url)) : asset($url);
    }

    public static function getStudentExamNumberPlaceholder()
    {
        return "*";
    }

    public static function getGenders()
    {
        return ['Male', 'Female'];
    }

    public static function getReligions()
    {
        return ['Islam', 'Christianity', 'Other'];
    }

    public static function getDateFormat()
    {
        return 'm/d/Y';
    }

    public static function userAssignedTickets($user_id = null)
    {
        $user_id ??= auth()->id();
        return Ticket::where('assigned_to', $user_id)->where('status', '!=', 'closed')->where('is_archived', false)->get();
    }
}