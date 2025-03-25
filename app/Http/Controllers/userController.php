<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                'advisor'=>$faculty->advisor
            ]);

            // Redirect based on role
            if (strtolower($faculty->role) === 'hod') {
                return redirect()->route('hoddashboard');
            } elseif (strtolower($faculty->role) === 'faculty') {
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
        if (!session()->has('fid')) {
            return redirect()->route('login');
        }
        $dept = $request->session()->get('dept');
        $courses = DB::table('courses')
            ->select('subcode as courseCode', 'subname as courseName', 'credits', 'type')
            ->where('dept', $dept)
            ->get();

        return view('subjects', compact('courses'));
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
        if (!session()->has('fid')) {
            return redirect()->route('login');
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
        return view('advisors', compact('sections', 'advisorRecords', 'facultyList'));
    }

    public function storeAdvisor(Request $request)
    {
        // Validate input using batch, sec, sem, and fid
        $data = $request->validate([
            'batch' => 'required|string',
            'sec'   => 'required|string',
            'sem'   => 'required|string',
            'fid'   => 'required|string'
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
            'dept'        => $dept,
            'batch'       => $data['batch'],
            'sec'         => $data['sec'],
            'semester'    => $data['sem'],
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
            'sec'   => 'required|string',
            'sem'   => 'required|string',
            'fid'   => 'required|string'
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

}
