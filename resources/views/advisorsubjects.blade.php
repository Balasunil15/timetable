<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIC</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
            --topbar-height: 60px;
            --footer-height: 60px;
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --dark-bg: #1a1c23;
            --light-bg: #f8f9fc;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* General Styles with Enhanced Typography */

        /* Content Area Styles */
        .content {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        /* Content Navigation */
        .content-nav {
            background: linear-gradient(45deg, #4e73df, #1cc88a);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .content-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 20px;
            overflow-x: auto;
        }

        .content-nav li a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .content-nav li a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .sidebar.collapsed+.content {
            margin-left: var(--sidebar-collapsed-width);
        }

        .breadcrumb-area {
            background: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            margin: 20px;
            padding: 15px 20px;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: #224abe;
        }

        /* Table Styles */
        .gradient-header {
            --bs-table-bg: transparent;
            --bs-table-color: white;
            background: linear-gradient(135deg, #4CAF50, #2196F3) !important;
            text-align: center;
            font-size: 0.9em;
        }

        td {
            text-align: left;
            font-size: 0.9em;
            vertical-align: middle;
            /* For vertical alignment */
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width) !important;
            }

            .sidebar.mobile-show {
                transform: translateX(0);
            }

            .topbar {
                left: 0 !important;
            }

            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }

            .mobile-overlay.show {
                display: block;
            }

            .content {
                margin-left: 0 !important;
            }

            .brand-logo {
                display: block;
            }

            .user-profile {
                margin-left: 0;
            }

            .sidebar .logo {
                justify-content: center;
            }

            .sidebar .menu-item span,
            .sidebar .has-submenu::after {
                display: block !important;
            }

            body.sidebar-open {
                overflow: hidden;
            }

            .footer {
                left: 0 !important;
            }

            .content-nav ul {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 5px;
            }

            .content-nav ul::-webkit-scrollbar {
                height: 4px;
            }

            .content-nav ul::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.3);
                border-radius: 2px;
            }
        }

        .container-fluid {
            padding: 20px;
        }

        /* loader */
        .loader-container {
            position: fixed;
            left: var(--sidebar-width);
            right: 0;
            top: var(--topbar-height);
            bottom: var(--footer-height);
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            /* Changed from 'none' to show by default */
            justify-content: center;
            align-items: center;
            z-index: 1000;
            transition: left 0.3s ease;
        }

        .sidebar.collapsed+.content .loader-container {
            left: var(--sidebar-collapsed-width);
        }

        @media (max-width: 768px) {
            .loader-container {
                left: 0;
            }
        }

        /* Hide loader when done */
        .loader-container.hide {
            display: none;
        }

        /* Loader Animation */
        .loader {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-radius: 50%;
            border-top: 5px solid var(--primary-color);
            border-right: 5px solid var(--success-color);
            border-bottom: 5px solid var(--primary-color);
            border-left: 5px solid var(--success-color);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .breadcrumb-area {
            background-image: linear-gradient(to top, #fff1eb 0%, #ace0f9 100%);
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            margin: 20px;
            padding: 15px 20px;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: #224abe;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    @include ('sidebar')

    <!-- Main Content -->
    <div class="content">

        <div class="loader-container" id="loaderContainer">
            <div class="loader"></div>
        </div>

        <!-- Topbar -->
        @include ('topbar')

        <!-- Breadcrumb -->
        <div class="breadcrumb-area custom-gradient">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Research</li>
                </ol>
            </nav>
        </div>

        <!-- Content Area -->
        <div class="container-fluid">
            <div class="custom-tabs">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="subjects-tab" data-bs-toggle="tab"
                            data-bs-target="#subjects" type="button" role="tab" aria-controls="subjects"
                            aria-selected="true">Select Subjects</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="students-tab" data-bs-toggle="tab" data-bs-target="#students"
                            type="button" role="tab" aria-controls="students" aria-selected="false">Select
                            Students</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="subjects" role="tabpanel" aria-labelledby="subjects-tab">
                        <table id="subjectsTable" class="table table-bordered text-center">
                            <thead class="gradient-header">
                                <tr>
                                    <th class="text-center">Subcode</th>
                                    <th class="text-center">Subname</th>
                                    <th class="text-center">Credits</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Faculty</th> <!-- New column for faculty -->
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courses as $course)
                                                                <tr>
                                                                    <td>{{ $course->subcode }}</td>
                                                                    <td>{{ $course->subname }}</td>
                                                                    <td>{{ $course->credits }}</td>
                                                                    <td>{{ $course->type }}</td>
                                                                    <td>
                                                                        @php
                                                                            $faculty = DB::table('subjects')
                                                                                ->where('subjectcode', $course->subcode)
                                                                                ->where('cid', session('cid'))
                                                                                ->select('fname1', 'fname2')
                                                                                ->first();
                                                                        @endphp
                                                                        @if($faculty)
                                                                            {{ $faculty->fname1 }}{{ $faculty->fname2 ? ', ' . $faculty->fname2 : '' }}
                                                                        @else
                                                                            Not Assigned
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if(
                                                                            \DB::table('subjects')
                                                                                ->where('subjectcode', $course->subcode)
                                                                                ->where('cid', session('cid'))
                                                                                ->exists()
                                                                        )
                                                                                                                <button class="btn btn-danger remove-subject-btn"
                                                                                                                    data-subjectcode="{{ $course->subcode }}">Remove</button>
                                                                        @else
                                                                            <button class="btn btn-primary" data-bs-toggle="modal"
                                                                                data-bs-target="#facultyModal">Choose Faculty</button>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="tab-pane fade" id="students" role="tabpanel" aria-labelledby="students-tab">
                        <table id="studentsTable_{{ uniqid() }}" class="table table-bordered text-center">
                            <thead class="gradient-header">
                                <tr>
                                    <th class="text-center">Subject Code</th>
                                    <th class="text-center">Subject Name</th>
                                    <th class="text-center">faculty </th>
                                    <th class="text-center">Students List</th>
                                </tr>
                            </thead>
                            <tbody id="studentsTableBody">
                                <!-- Dynamic content will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer -->
        @include ('footer')
    </div>
    <script>
        const loaderContainer = document.getElementById('loaderContainer');

        function showLoader() {
            loaderContainer.classList.add('show');
        }

        function hideLoader() {
            loaderContainer.classList.remove('show');
        }

        //    automatic loader
        document.addEventListener('DOMContentLoaded', function () {
            const loaderContainer = document.getElementById('loaderContainer');
            let loadingTimeout;

            function hideLoader() {
                loaderContainer.classList.add('hide');
            }

            function showError() {
                console.error('Page load took too long or encountered an error');
                // You can add custom error handling here
            }

            // Set a maximum loading time (10 seconds)
            loadingTimeout = setTimeout(showError, 10000);

            // Hide loader when everything is loaded
            window.onload = function () {
                clearTimeout(loadingTimeout);

                // Add a small delay to ensure smooth transition
                setTimeout(hideLoader, 500);
            };

            // Error handling
            window.onerror = function (msg, url, lineNo, columnNo, error) {
                clearTimeout(loadingTimeout);
                showError();
                return false;
            };

            // Initialize DataTables for subjectsTable and studentsTable
            $('#subjectsTable').DataTable();
            $('#studentsTable').DataTable();
        });

        document.addEventListener("DOMContentLoaded", function () {
            // Cache DOM elements
            const elements = {
                hamburger: document.getElementById('hamburger'),
                sidebar: document.getElementById('sidebar'),
                mobileOverlay: document.getElementById('mobileOverlay'),
                menuItems: document.querySelectorAll('.menu-item'),
                submenuItems: document.querySelectorAll('.submenu-item') // Add submenu items to cache
            };

            // Set active menu item based on current path
            function setActiveMenuItem() {
                const currentPath = window.location.pathname.split('/').pop();

                // Clear all active states first
                elements.menuItems.forEach(item => item.classList.remove('active'));
                elements.submenuItems.forEach(item => item.classList.remove('active'));

                // Check main menu items
                elements.menuItems.forEach(item => {
                    const itemPath = item.getAttribute('href')?.replace('/', '');
                    if (itemPath === currentPath) {
                        item.classList.add('active');
                        // If this item has a parent submenu, activate it too
                        const parentSubmenu = item.closest('.submenu');
                        const parentMenuItem = parentSubmenu?.previousElementSibling;
                        if (parentSubmenu && parentMenuItem) {
                            parentSubmenu.classList.add('active');
                            parentMenuItem.classList.add('active');
                        }
                    }
                });

                // Check submenu items
                elements.submenuItems.forEach(item => {
                    const itemPath = item.getAttribute('href')?.replace('/', '');
                    if (itemPath === currentPath) {
                        item.classList.add('active');
                        // Activate parent submenu and its trigger
                        const parentSubmenu = item.closest('.submenu');
                        const parentMenuItem = parentSubmenu?.previousElementSibling;
                        if (parentSubmenu && parentMenuItem) {
                            parentSubmenu.classList.add('active');
                            parentMenuItem.classList.add('active');
                        }
                    }
                });
            }

            // Handle mobile sidebar toggle
            function handleSidebarToggle() {
                if (window.innerWidth <= 768) {
                    elements.sidebar.classList.toggle('mobile-show');
                    elements.mobileOverlay.classList.toggle('show');
                    document.body.classList.toggle('sidebar-open');
                } else {
                    elements.sidebar.classList.toggle('collapsed');
                }
            }

            // Handle window resize
            function handleResize() {
                if (window.innerWidth <= 768) {
                    elements.sidebar.classList.remove('collapsed');
                    elements.sidebar.classList.remove('mobile-show');
                    elements.mobileOverlay.classList.remove('show');
                    document.body.classList.remove('sidebar-open');
                } else {
                    elements.sidebar.style.transform = '';
                    elements.mobileOverlay.classList.remove('show');
                    document.body.classList.remove('sidebar-open');
                }
            }

            // Toggle User Menu
            const userMenu = document.getElementById('userMenu');
            const dropdownMenu = userMenu.querySelector('.dropdown-menu');
            userMenu.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdownMenu.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', () => {
                dropdownMenu.classList.remove('show');
            });

            // Enhanced Toggle Submenu with active state handling
            const menuItems = document.querySelectorAll('.has-submenu');
            menuItems.forEach(item => {
                item.addEventListener('click', (e) => {
                    e.preventDefault(); // Prevent default if it's a link
                    const submenu = item.nextElementSibling;

                    // Toggle active state for the clicked menu item and its submenu
                    item.classList.toggle('active');
                    submenu.classList.toggle('active');

                    // Handle submenu item clicks
                    const submenuItems = submenu.querySelectorAll('.submenu-item');
                    submenuItems.forEach(submenuItem => {
                        submenuItem.addEventListener('click', (e) => {
                            // Remove active class from all submenu items
                            submenuItems.forEach(si => si.classList.remove('active'));
                            // Add active class to clicked submenu item
                            submenuItem.classList.add('active');
                            e.stopPropagation(); // Prevent event from bubbling up
                        });
                    });
                });
            });

            // Initialize event listeners
            function initializeEventListeners() {
                // Sidebar toggle for mobile and desktop
                if (elements.hamburger && elements.mobileOverlay) {
                    elements.hamburger.addEventListener('click', handleSidebarToggle);
                    elements.mobileOverlay.addEventListener('click', handleSidebarToggle);
                }
                // Window resize handler
                window.addEventListener('resize', handleResize);
            }

            // Initialize everything
            setActiveMenuItem();
            initializeEventListeners();
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.remove-subject-btn', function () {
            var subjectcode = $(this).data('subjectcode');
            $.ajax({
                url: "{{ route('subject.remove') }}",
                type: "POST",
                data: { subjectcode: subjectcode },
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Removed',
                            text: response.message,
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            confirmButtonText: 'Ok'
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while processing your request.',
                        confirmButtonText: 'Ok'
                    });
                }
            });
        });

        // When a "Select Students" button is clicked, fetch students dynamically
        $(document).on('click', '.btn-primary[data-bs-target="#studentsModal"]', function () {
            const subjectCode = $(this).closest('tr').find('td:first').text().trim();
            const subjectName = $(this).closest('tr').find('td:nth-child(2)').text().trim();

            // Set subject details in the modal
            $('#studentsModalLabel').text(`Select Students for ${subjectName} (${subjectCode})`);

            // Fetch students dynamically based on advisor's department, section, and batch
            $.ajax({
                url: "{{ route('students.fetch') }}", // Define this route in your web.php
                type: "GET",
                data: {
                    dept: "{{ session('dept') }}", // Advisor's department
                    section: "{{ session('sec') }}", // Advisor's section
                    batch: "{{ session('batch') }}" // Advisor's batch
                },
                success: function (response) {
                    if (response.status === 'success') {
                        const studentsList = response.data;
                        const leftColumn = $('#studentsModal .list-group').first();
                        const rightColumn = $('#studentsModal .list-group').last();

                        // Clear existing student lists
                        leftColumn.empty();
                        rightColumn.empty();

                        // Populate students dynamically
                        studentsList.forEach((student, index) => {
                            const studentItem = `
                                <li class="list-group-item">
                                    <input type="checkbox" id="student${student.uid}" name="student${student.uid}">
                                    <label for="student${student.uid}">${student.sname} (${student.sid})</label>
                                </li>
                            `;
                            if (index % 2 === 0) {
                                leftColumn.append(studentItem);
                            } else {
                                rightColumn.append(studentItem);
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            confirmButtonText: 'Ok'
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to fetch students. Please try again.',
                        confirmButtonText: 'Ok'
                    });
                }
            });
        });

        // Fetch subjects dynamically for the students tab
        $.ajax({
            url: "{{ route('subjects.fetch') }}",
            type: "GET",
            success: function (response) {
                if (response.status === 'success') {
                    const subjects = response.data;
                    const tableBody = $('#studentsTableBody');
                    tableBody.empty();

                    subjects.forEach(subject => {
                        const row = `
                            <tr>
                                <td class="text-center">${subject.subjectcode}</td>
                                <td class="text-center">${subject.subjectname}</td>
                                <td class="text-center">${subject.fname1} ${subject.fname2 ? ', ' + subject.fname2 : ''}</td>
                                <td class="text-center">
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#studentsModal" 
                                        data-subjectcode="${subject.subjectcode}" 
                                        data-subjectname="${subject.subjectname}">

                                        Select Students
                                    </button>
                                </td>
                            </tr>
                        `;
                        tableBody.append(row);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonText: 'Ok'
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch subjects. Please try again.',
                    confirmButtonText: 'Ok'
                });
            }
        });

        $(document).on('click', '#assignStudentsButton', function () {
            const selectedStudents = [];
            const subjectCode = $('#studentsModal').data('subjectcode');
            const subjectName = $('#studentsModalLabel').text().split('(')[0].trim();
            const assignedFaculty = $('#facultyDropdown').val();

            // Collect selected student IDs
            document.querySelectorAll('#studentsModal .list-group-item input[type="checkbox"]:checked').forEach(checkbox => {
                selectedStudents.push(checkbox.id.replace('student', ''));
            });

            if (selectedStudents.length === 0 || !assignedFaculty) {
                Swal.fire('Error', 'Please select students and assign a faculty.', 'error');
                return;
            }

            // Send data to the server
            $.ajax({
                url: "{{ route('attendance.map') }}", // Define this route in your web.php
                type: "POST",
                data: {
                    subjectCode,
                    subjectName,
                    students: selectedStudents,
                    faculty: assignedFaculty,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire('Success', response.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'An error occurred while assigning students.', 'error');
                }
            });
        });
    </script>

    @php
        $dept = session('dept');
        $facultyNotAssigned = DB::table('faculty')
            ->where('dept', $dept)
            ->whereNotIn('name', function ($query) use ($dept) {
                $query->select('fname1')
                    ->where('dept', $dept)
                    ->where('semester', session('semester'))
                    ->from('subjects');
            })
            ->get(); 
    @endphp
    <!-- Faculty Modal -->
    <div class="modal fade" id="facultyModal" tabindex="-1" aria-labelledby="facultyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="assignSubjectForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="facultyModalLabel">Select Faculty</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Hidden inputs for subject info; these must be set before showing the modal -->
                        <input type="hidden" name="subjectcode" id="subjectcode">
                        <input type="hidden" name="subjectname" id="subjectname">
                        <input type="hidden" name="subjecttype" id="subjecttype">
                        <label for="facultySelect" class="form-label">Choose Faculty:</label>
                        <div id="facultySelectContainer">
                            <select class="form-select mb-2" id="facultySelect1" name="fid1" required>
                                <option selected disabled>Select a faculty</option>
                                @foreach($facultyNotAssigned as $faculty)
                                    <option value="{{ $faculty->fid }}">{{ $faculty->name }}</option>
                                @endforeach
                            </select>
                            <select class="form-select" id="facultySelect2" name="fid2">
                                <option selected disabled>Select a second faculty </option>
                                @foreach($facultyNotAssigned as $faculty)
                                    <option value="{{ $faculty->fid }}">{{ $faculty->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Students Modal -->
    <div class="modal fade" id="studentsModal" tabindex="-1" aria-labelledby="studentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentsModalLabel">Select Students</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-outline-primary w-100" onclick="mapStudents('all')">Select All</button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-primary w-100" onclick="mapStudents('odd')">Odd Students</button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-primary w-100" onclick="mapStudents('even')">Even Students</button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-primary w-100" onclick="mapStudents('firstHalf')">1st Half Students</button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-primary w-100" onclick="mapStudents('secondHalf')">2nd Half Students</button>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="facultyDropdown" class="form-label">Assign to Faculty:</label>
                        <select class="form-select" id="facultyDropdown">
                            <!-- Dynamic faculty options will be populated here -->
                        </select>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-success" id="assignStudentsButton">Assign Students</button>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group" id="leftStudentList">
                                <!-- Dynamic content -->
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group" id="rightStudentList">
                                <!-- Dynamic content -->
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitStudentMapping()">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function mapStudents(criteria) {
            const checkboxes = document.querySelectorAll('#studentsModal .list-group-item input[type="checkbox"]');
            const total = checkboxes.length;
            checkboxes.forEach((checkbox, index) => {
                switch (criteria) {
                    case 'all':
                        checkbox.checked = true;
                        break;
                    case 'odd':
                        checkbox.checked = index % 2 === 0;
                        break;
                    case 'even':
                        checkbox.checked = index % 2 !== 0;
                        break;
                    case 'firstHalf':
                        checkbox.checked = index < total / 2;
                        break;
                    case 'secondHalf':
                        checkbox.checked = index >= total / 2;
                        break;
                }
            });
        }

        $(document).on('click', '.btn-primary[data-bs-target="#studentsModal"]', function () {
            const subjectCode = $(this).closest('tr').find('td:first').text().trim();
            const subjectName = $(this).closest('tr').find('td:nth-child(2)').text().trim();

            // Set subject details in the modal
            $('#studentsModalLabel').text(`Select Students for ${subjectName} (${subjectCode})`);
            $('#studentsModal').data('subjectcode', subjectCode);

            // Fetch assigned faculty for the subject
            $.ajax({
                url: "{{ route('faculty.fetch') }}",
                type: "GET",
                data: { subjectCode },
                success: function (response) {
                    if (response.status === 'success') {
                        const facultyDropdown = $('#facultyDropdown');
                        facultyDropdown.empty();
                        response.data.forEach(faculty => {
                            facultyDropdown.append(`<option value="${faculty}">${faculty}</option>`);
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Failed to fetch faculty. Please try again.', 'error');
                }
            });

            // Fetch students dynamically
            $.ajax({
                url: "{{ route('students.fetch') }}",
                type: "GET",
                data: {
                    dept: "{{ session('dept') }}",
                    section: "{{ session('sec') }}",
                    batch: "{{ session('batch') }}"
                },
                success: function (response) {
                    if (response.status === 'success') {
                        const studentsList = response.data;
                        const leftColumn = $('#studentsModal .list-group').first();
                        const rightColumn = $('#studentsModal .list-group').last();

                        leftColumn.empty();
                        rightColumn.empty();

                        studentsList.forEach((student, index) => {
                            const studentItem = `
                                <li class="list-group-item">
                                    <input type="checkbox" id="student${student.uid}" name="student${student.uid}">
                                    <label for="student${student.uid}">${student.sname} (${student.sid})</label>
                                </li>
                            `;
                            if (index % 2 === 0) {
                                leftColumn.append(studentItem);
                            } else {
                                rightColumn.append(studentItem);
                            }
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Failed to fetch students. Please try again.', 'error');
                }
            });
        });

        function submitStudentMapping() {
            const selectedStudents = [];
            const subjectCode = $('#studentsModal').data('subjectcode');
            const assignedFaculty = $('#facultyDropdown').val();

            document.querySelectorAll('#studentsModal .list-group-item input[type="checkbox"]:checked').forEach(checkbox => {
                selectedStudents.push(checkbox.id.replace('student', ''));
            });

            $.ajax({
                url: "{{ route('students.map') }}",
                type: "POST",
                data: {
                    subjectCode,
                    students: selectedStudents,
                    faculty: assignedFaculty,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire('Success', response.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'An error occurred while mapping students.', 'error');
                }
            });
        }
    </script>

</body>

</html>