Route::get('/subjectsfetch', [userController::class, 'fetchsubjects'])->name('subjects.fetch');   public function fetchSubjects(Request $request)
    {
        $dept = session('dept');
        $cid = session('cid');

        try {
            $subjects = DB::table('subjects')
                ->where('dept', $dept)
                ->where('cid', $cid)
                ->get(['subjectcode', 'subjectname', 'fname1', 'fname2']);

            return response()->json([
                'status' => 'success',
                'data' => $subjects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch subjects. Please try again.'
            ]);
        }
    }
