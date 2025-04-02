<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;



class userController extends Controller
{
    public function login(Request $request)
    {
        $fid = $request->input('facultyId');
        $password = $request->input('password');

        // Query the faculty table using fid and password columns
        $faculty = DB::table('faculty')
            ->where('fid', $fid)
            ->where('password', $password)
            ->first();

        if ($faculty) {
            // Store details in the session
            session([
                'fid' => $faculty->fid,
                'name' => $faculty->name,
                'dept' => $faculty->dept,
                'role' => $faculty->role,
                'advisor' => $faculty->advisor
            ]);

            // Redirect based on role
            if (strtolower($faculty->role) === 'hod') {
                return redirect()->route('hoddashboard');
            } elseif (strtolower($faculty->role) === 'faculty') {
                if ($faculty->advisor == 1) {
                    // Fetch advisor record mapping faculty->name with advisorname
                    $advisorRecord = DB::table('advisor')
                        ->where('dept', $faculty->dept)
                        ->where('advisorname', $faculty->name)
                        ->first();
                    if ($advisorRecord) {
                        // Store advisor details (batch, sec, semester) in session
                        session([
                            'cid' => $advisorRecord->cid,
                            'batch' => $advisorRecord->batch,
                            'sec' => $advisorRecord->sec,
                            'semester' => $advisorRecord->semester
                        ]);
                    }
                }
                return redirect()->route('facultydashboard');
            } else {
                return redirect()->back()->with('error', 'Invalid role.');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid credentials.');
        }
    }

    public function logout(Request $request)
    {
        // Destroy session
        $request->session()->flush();
        return redirect()->route('login');
    }

    public function subjects(Request $request)
    {
        if (session()->get('role') !== 'hod') {
            return redirect()->route('logout');
        }
        $dept = $request->session()->get('dept');
        $courses = DB::table('courses')
            ->select('subcode as courseCode', 'subname as courseName', 'credits', 'type')
            ->where('dept', $dept)
            ->get();

            $response = response()->view('subjects', compact('courses'));
            return $response
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
            
    }

    public function storeCourse(Request $request)
    {
        $dept = $request->session()->get('dept');
        $courseCode = $request->input('courseCode');
        $courseName = $request->input('courseName');
        $credits = $request->input('courseCredits');
        $type = $request->input('courseType');
        $created_by = $request->session()->get('name');

        // Check if a course with the same subcode already exists
        $exists = DB::table('courses')
            ->where('subcode', $courseCode)
            ->where('dept', $dept)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => "Course code {$courseCode} already exists"
            ]);
        }

        DB::table('courses')->insert([
            'subcode' => $courseCode,
            'subname' => $courseName,
            'credits' => $credits,
            'type' => $type,
            'dept' => $dept,
            'createdby' => $created_by,

        ]);

        return response()->json([
            'status' => 'success',
            'message' => "Course {$courseName} successfully created"
        ]);
    }
    public function storeCoursess(Request $request)
    {
        // Validate file input
        $validator = Validator::make($request->all(), [
            'import_file' => 'required|mimes:csv,txt|max:2048', // Validate file type
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid file format. Please upload a CSV file.',
            ]);
        }

        $file = $request->file('import_file');
        $filePath = $file->getRealPath();

        // Read CSV file
        $handle = fopen($filePath, "r");
        $header = fgetcsv($handle);

        // Expected headers
        $expectedHeaders = ['subcode', 'type', 'subname', 'credits'];

        if ($header !== $expectedHeaders) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid file format. Please use the correct template.',
            ]);
        }

        $created_by = $request->session()->get('name');
        $dept = $request->session()->get('dept'); // Example: Use authenticated user ID


        while (($row = fgetcsv($handle)) !== FALSE) {
            if (count($row) == 4) {
                $exists = DB::table('courses')
                    ->where('subcode', $row[0])
                    ->exists();

                if ($exists) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Course code {$row[0]} already exists"
                    ]);
                }
                // Ensure all columns are present
                DB::table('courses')->insert([
                    'subcode' => $row[0],
                    'type' => $row[1],
                    'subname' => $row[2],
                    'credits' => $row[3],
                    'dept' => $dept,
                    'createdby' => $created_by,

                ]);
            }
        }
        fclose($handle);

        return response()->json([
            'status' => 'success',
            'message' => 'Courses imported successfully!',
        ]);
    }

    public function updateCourse(Request $request)
    {
        $dept = $request->session()->get('dept');
        $courseCode = $request->input('courseCode');
        $courseName = $request->input('courseName');
        $credits = $request->input('courseCredits');
        $type = $request->input('courseType');

        // Update course where subcode matches and dept matches session
        $updated = DB::table('courses')
            ->where('subcode', $courseCode)
            ->where('dept', $dept)
            ->update([
                'subname' => $courseName,
                'credits' => $credits,
                'type' => $type
            ]);

        if ($updated) {
            return response()->json([
                'status' => 'success',
                'message' => "Course {$courseCode} updated successfully"
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => "Update failed. Please try again."
            ]);
        }
    }

    public function deleteCourse(Request $request)
    {
        $dept = $request->session()->get('dept');
        $courseCode = $request->input('courseCode');

        $deleted = DB::table('courses')
            ->where('subcode', $courseCode)
            ->where('dept', $dept)
            ->delete();

        if ($deleted) {
            return response()->json([
                'status' => 'success',
                'message' => "Course {$courseCode} deleted successfully"
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => "Deletion failed. Please try again."
            ]);
        }
    }

    public function advisors(Request $request)
    {
        if (session()->get('role') !== 'hod') {
            return redirect()->route('logout');
        }
        $dept = $request->session()->get('dept');
        // Retrieve sections record and decode the JSON sections
        $sectionRecord = DB::table('sections')->where('dept', $dept)->first();
        $sections = $sectionRecord ? json_decode($sectionRecord->sections, true) : [];
        // Fetch current advisor records for this department
        $advisorRecords = DB::table('advisor')->where('dept', $dept)->get();
        // Fetch available faculty (role=faculty and advisor != 1)
        $facultyList = DB::table('faculty')
            ->where('dept', $dept)
            ->where('role', 'faculty')
            ->where('advisor', '!=', 1)
            ->get();
            $response = response()->view('advisors', compact('sections', 'advisorRecords', 'facultyList'));
            return $response
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
            
    }

    public function storeAdvisor(Request $request)
    {
        // Validate input using batch, sec, sem, and fid
        $data = $request->validate([
            'batch' => 'required|string',
            'sec' => 'required|string',
            'sem' => 'required|string',
            'fid' => 'required|string'
        ]);
        $dept = $request->session()->get('dept');

        // Check if an advisor record already exists for the given dept, batch, sec, and semester
        $exists = DB::table('advisor')
            ->where('dept', $dept)
            ->where('batch', $data['batch'])
            ->where('sec', $data['sec'])
            ->where('semester', $data['sem'])
            ->exists();
        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Advisor record already exists for the specified batch, section, and semester.'
            ]);
        }

        // Get the chosen faculty record to retrieve advisor name
        $faculty = DB::table('faculty')
            ->where('fid', $data['fid'])
            ->where('dept', $dept)
            ->first();
        if (!$faculty) {
            return response()->json(['status' => 'error', 'message' => 'Faculty not found.']);
        }

        // Insert advisor record including semester
        DB::table('advisor')->insert([
            'dept' => $dept,
            'batch' => $data['batch'],
            'sec' => $data['sec'],
            'semester' => $data['sem'],
            'advisorname' => $faculty->name
        ]);
        // Update faculty table: set advisor = 1 for chosen faculty
        DB::table('faculty')->where('fid', $data['fid'])->update(['advisor' => 1]);
        return response()->json(['status' => 'success', 'message' => 'Advisor added successfully.']);
    }

    public function updateAdvisor(Request $request)
    {
        // Validate input using batch, sec, sem and fid
        $data = $request->validate([
            'batch' => 'required|string',
            'sec' => 'required|string',
            'sem' => 'required|string',
            'fid' => 'required|string'
        ]);
        $dept = $request->session()->get('dept');

        // Get new advisor faculty record
        $newFaculty = DB::table('faculty')
            ->where('fid', $data['fid'])
            ->where('dept', $dept)
            ->first();
        if (!$newFaculty) {
            return response()->json(['status' => 'error', 'message' => 'Faculty not found.']);
        }

        // If a current advisor exists for the class (matching batch, sec, and semester), reset the previous advisor's flag.
        $currentAdvisor = DB::table('advisor')
            ->where('dept', $dept)
            ->where('batch', $data['batch'])
            ->where('sec', $data['sec'])
            ->where('semester', $data['sem'])
            ->first();
        if ($currentAdvisor) {
            DB::table('faculty')
                ->where('name', $currentAdvisor->advisorname)
                ->where('dept', $dept)
                ->update(['advisor' => 0]);
        }

        // If the new advisor is already allocated to another class, remove that allocation
        $existingAdvisorRecord = DB::table('advisor')
            ->where('dept', $dept)
            ->where('advisorname', $newFaculty->name)
            ->first();
        if ($existingAdvisorRecord) {
            DB::table('advisor')
                ->where('dept', $dept)
                ->where('batch', $existingAdvisorRecord->batch)
                ->where('sec', $existingAdvisorRecord->sec)
                ->where('semester', $existingAdvisorRecord->semester)
                ->delete();
            DB::table('faculty')
                ->where('name', $newFaculty->name)
                ->where('dept', $dept)
                ->update(['advisor' => 0]);
        }

        // Update or insert the advisor record with the new faculty name matching batch, sec and sem
        DB::table('advisor')->updateOrInsert(
            [
                'dept' => $dept,
                'batch' => $data['batch'],
                'sec' => $data['sec'],
                'semester' => $data['sem']
            ],
            ['advisorname' => $newFaculty->name]
        );
        // Mark the new advisor in the faculty table
        DB::table('faculty')
            ->where('fid', $data['fid'])
            ->update(['advisor' => 1]);

        return response()->json(['status' => 'success', 'message' => 'Advisor updated successfully.']);
    }

    public function studentslist(Request $request)
    {
        if (!session()->has('cid')) {
            return redirect()->route('logout');
        }
        $dept = $request->session()->get('dept');
        $batch = $request->session()->get('batch');
        $sec = $request->session()->get('sec');
        $semester = $request->session()->get('semester');

        $students = DB::table('student')
            ->select('sid as regno', 'sname as studentName')
            ->where('dept', $dept)
            ->where('Batch', $batch)
            ->where('section', $sec)
            ->where('semester', $semester)
            ->get();

            $response = response()->view('students', compact('students'));
            return $response
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
            
    }

    public function advisorsubjects(Request $request)
    {
        if (!session()->has('cid')) {
            return redirect()->route('logout');
        }
        $dept = $request->session()->get('dept');
        $courses = DB::table('courses')
            ->select('subcode', 'subname', 'credits', 'type')
            ->where('dept', $dept)
            ->get();
            $response = response()->view('advisorsubjects', compact('courses'));
            return $response
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
             
    }

    public function assignSubject(Request $request)
    {
        $data = $request->validate([
            'subjectcode' => 'required|string',
            'subjectname' => 'required|string',
            'fid1' => 'required|string',
            'fid2' => 'nullable|string'
        ]);

        $cid = session('cid');
        $dept = session('dept');
        $batch = session('batch');
        $sec = session('sec');
        $semester = session('semester');

        // Get faculty names for the provided fids
        $faculty1 = DB::table('faculty')->where('fid', $data['fid1'])->first();
        $faculty2 = $data['fid2'] ? DB::table('faculty')->where('fid', $data['fid2'])->first() : null;

        // Validation: Ensure at least one faculty is selected
        if (!$faculty1) {
            return response()->json(['status' => 'error', 'message' => 'At least one faculty must be selected.'], 400);
        }

        // Insert record into subjects table
        DB::table('subjects')->insert([
            'cid' => $cid,
            'subjectcode' => $data['subjectcode'],
            'subjectname' => $data['subjectname'],
            'fac1id'=>$faculty1->fid,
            'fac2id'=>$faculty2 ?$faculty2->fid:null,
            'fname1' => $faculty1->name,
            'fname2' => $faculty2 ? $faculty2->name : null,
            'semester' => $semester,
            'dept' => $dept,
            'batch' => $batch,
            'sec' => $sec
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "Faculty {$faculty1->name}" . ($faculty2 ? " and {$faculty2->name}" : "") . " assigned to subject {$data['subjectname']}"
        ]);
    }

    public function removeSubject(Request $request)
    {
        $subjectcode = $request->input('subjectcode');
        $cid = session('cid');

        $deleted = DB::table('subjects')
            ->where('subjectcode', $subjectcode)
            ->where('cid', $cid)
            ->delete();

        if ($deleted) {
            return response()->json([
                'status' => 'success',
                'message' => "Subject {$subjectcode} removed successfully"
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => "Subject {$subjectcode} removal failed"
            ]);
        }
    }

    public function fetchStudents(Request $request)
    {
        $dept = $request->input('dept');
        $section = $request->input('section');
        $batch = $request->input('batch');

        try {
            $students = DB::table('student')
                ->where('dept', $dept)
                ->where('section', $section)
                ->where('Batch', $batch)
                ->get(['uid', 'sname', 'sid']);

            return response()->json([
                'status' => 'success',
                'data' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch students. Please try again.'
            ]);
        }
    }

    public function fetchSubjects(Request $request)
    {
        try {
            $subjects = DB::table('subjects')
                ->select('subjectcode', 'subjectname', 'fname1', 'fname2', 'fac1id', 'fac2id')
                ->where('dept', session('dept'))
                ->where('cid', session('cid'))
                ->where('semester', session('semester'))
                ->get();

            if ($subjects->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No subjects found for this class'
                ]);
            }

            return response()->json([
                'status' => 'success',
                'data' => $subjects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch subjects: ' . $e->getMessage()
            ]);
        }
    }

    public function getTimetableData()
    {
        try {
            $cid = session('cid');
            $mappings = DB::table('timetable_map as tm')
                ->join('subjects as s', 'tm.subject_code', '=', 's.subjectcode')
                ->where('tm.cid', $cid)
                ->select('tm.day', 'tm.hour', 'tm.subject_code', 's.subjectname', 's.fname1', 's.fname2')
                ->get()
                ->groupBy(['day', 'hour']);

            return response()->json([
                'status' => 'success',
                'data' => $mappings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch timetable data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function mapTimetable(Request $request)
    {
        try {
            $cid = session('cid');
            
            // Log incoming data for debugging
            Log::info('Incoming request data:', $request->all());

            // Validate input
            $validated = $request->validate([
                'day' => 'required|string',
                'hour' => 'required|string',
                'subject_code1' => 'required|string',
                'fac1_id' => 'required',  // Remove string validation to accept numeric
                'subject_code2' => 'nullable|string',
                'fac2_id' => 'nullable'   // Remove string validation to accept numeric
            ]);

            // Check for faculty scheduling conflicts
            $facultyConflict = DB::table('timetable_map')
                ->where('day', $request->day)
                ->where('hour', $request->hour)
                ->where(function($query) use ($request) {
                    $query->where('fid', intval($request->fac1_id));
                    if ($request->fac2_id) {
                        $query->orWhere('fid', intval($request->fac2_id));
                    }
                })
                ->first();

            if ($facultyConflict) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'One or more faculty members are already scheduled during this time slot'
                ]);
            }

            

            // Insert first subject with explicit type casting
            DB::table('timetable_map')->insert([
                'cid' => $cid,
                'day' => $request->day,
                'hour' => $request->hour,
                'subject_code' => $request->subject_code1,
                'fid' => intval($request->fac1_id)  // Ensure integer conversion
            ]);

            // Insert second subject if provided
            if ($request->subject_code2 && $request->fac2_id) {
                DB::table('timetable_map')->insert([
                    'cid' => $cid,
                    'day' => $request->day,
                    'hour' => $request->hour,
                    'subject_code' => $request->subject_code2,
                    'fid' => intval($request->fac2_id)  // Ensure integer conversion
                ]);
            }

            DB::commit();

            // Log successful insertion
            Log::info('Successfully mapped timetable with fac1_id: ' . $request->fac1_id);

            return response()->json([
                'status' => 'success',
                'message' => 'Timetable mapping saved successfully',
                'reload' => true
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to save timetable mapping: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save timetable mapping: ' . $e->getMessage()
            ], 500);
        }
    }

}