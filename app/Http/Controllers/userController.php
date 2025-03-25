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
        // Validate input (year, sec, fid must be provided)
        $data = $request->validate([
            'year' => 'required|integer',
            'sec'  => 'required|string',
            'fid'  => 'required|string'
        ]);
        $dept = $request->session()->get('dept');
        // Get the chosen faculty record to retrieve advisor name
        $faculty = DB::table('faculty')
            ->where('fid', $data['fid'])
            ->where('dept', $dept)
            ->first();
        if (!$faculty) {
            return response()->json(['status' => 'error', 'message' => 'Faculty not found.']);
        }
        // Insert advisor record; assume advisor table has columns: dept, year, sec, advisorname
        DB::table('advisor')->updateOrInsert(
            ['dept' => $dept, 'year' => $data['year'], 'sec' => $data['sec']],
            ['advisorname' => $faculty->name]
        );
        // Update faculty table: set advisor = 1 for chosen faculty
        DB::table('faculty')->where('fid', $data['fid'])->update(['advisor' => 1]);
        return response()->json(['status' => 'success', 'message' => 'Advisor updated successfully.']);
    }

    public function updateAdvisor(Request $request)
    {
        $data = $request->validate([
            'year' => 'required|integer',
            'sec'  => 'required|string',
            'fid'  => 'required|string'
        ]);
        $dept = $request->session()->get('dept');

        // Get new advisor faculty record (all faculty regardless of advisor status)
        $newFaculty = DB::table('faculty')
            ->where('fid', $data['fid'])
            ->where('dept', $dept)
            ->first();
        if (!$newFaculty) {
            return response()->json(['status' => 'error', 'message' => 'Faculty not found.']);
        }

        // If a current advisor exists for the class, reset the previous advisor's flag
        $currentAdvisor = DB::table('advisor')
            ->where('dept', $dept)
            ->where('year', $data['year'])
            ->where('sec', $data['sec'])
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
                ->where('year', $existingAdvisorRecord->year)
                ->where('sec', $existingAdvisorRecord->sec)
                ->delete();
            DB::table('faculty')
                ->where('name', $newFaculty->name)
                ->where('dept', $dept)
                ->update(['advisor' => 0]);
        }

        // Update or insert the advisor record with the new faculty name
        DB::table('advisor')->updateOrInsert(
            ['dept' => $dept, 'year' => $data['year'], 'sec' => $data['sec']],
            ['advisorname' => $newFaculty->name]
        );
        // Set the new advisor's column to 1
        DB::table('faculty')
            ->where('fid', $data['fid'])
            ->update(['advisor' => 1]);

        return response()->json(['status' => 'success', 'message' => 'Advisor updated successfully.']);
    }

}
