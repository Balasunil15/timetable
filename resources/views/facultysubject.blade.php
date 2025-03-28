<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
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

        #timetable td:not(:first-child), #timetable th:not(:first-child) {
            height: 90px; /* Increased height */
        }

        #timetable {
            border-collapse: collapse;
        }

        #timetable th, #timetable td {
            border: 1px solid #dee2e6; /* Ensure consistent border */
        }

        #timetable th:last-child, #timetable td:last-child {
            border-right: 1px solid #dee2e6; /* Fix right-side border */
        }

        #timetable tr:last-child td {
            border-bottom: 1px solid #dee2e6; /* Fix bottom border */
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
        <!-- Topbar -->
        @include ('topbar')

        <!-- Breadcrumb -->
        <div class="breadcrumb-area custom-gradient">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Subjects</li>
                </ol>
            </nav>
        </div>

        <!-- Content Area -->
        <div class="container-fluid">
            <table id="timetable" class="table table-bordered text-center">
                <thead class="gradient-header">
                    <tr>
                        <th class="text-center"></th>
                        <th class="text-center">1st Hr <br> (08.45am - 09.45am)</th>
                        <th class="text-center">2nd Hr <br>(9.45am - 10.45am)</th>
                        <th class="text-center">Break <br>(10.45am - 11.05am)</th>
                        <th class="text-center">3rd Hr <br>(11.05am - 12.05pm)</th>
                        <th class="text-center">4th Hr <br>(12.05pm - 01.05pm)</th>
                        <th class="text-center">Lunch <br>(01.05pm - 01.55pm)</th>
                        <th class="text-center">5th Hr <br>(01.55pm - 02.45pm)</th>
                        <th class="text-center">Break <br>(2.45pm - 3.00pm)</th>
                        <th class="text-center">6th Hr <br>(3.00pm - 03.50pm)</th>
                        <th class="text-center">7th Hr <br>(03.50pm - 04.40pm)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">Monday</td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Monday', '1st Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Monday', '2nd Hr')">Choose Subject</button></td>
                        <td rowspan="6" class="align-middle bg-light">Break</td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Monday', '3rd Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Monday', '4th Hr')">Choose Subject</button></td>
                        <td rowspan="6" class="align-middle bg-light">Lunch</td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Monday', '5th Hr')">Choose Subject</button></td>
                        <td rowspan="6" class="align-middle bg-light">Break</td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Monday', '6th Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Monday', '7th Hr')">Choose Subject</button></td>
                    </tr>
                    <tr>
                        <td class="text-center">Tuesday</td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Tuesday', '1st Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Tuesday', '2nd Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Tuesday', '3rd Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Tuesday', '4th Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Tuesday', '5th Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Tuesday', '6th Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Tuesday', '7th Hr')">Choose Subject</button></td>
                    </tr>
                    <tr>
                        <td class="text-center">Wednesday</td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Wednesday', '1st Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Wednesday', '2nd Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Wednesday', '3rd Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Wednesday', '4th Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Wednesday', '5th Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Wednesday', '6th Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Wednesday', '7th Hr')">Choose Subject</button></td>
                    </tr>
                    <tr>
                        <td class="text-center">Thursday</td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Thursday', '1st Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Thursday', '2nd Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Thursday', '3rd Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Thursday', '4th Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Thursday', '5th Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Thursday', '6th Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Thursday', '7th Hr')">Choose Subject</button></td>
                    </tr>
                    <tr>
                        <td class="text-center">Friday</td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Friday', '1st Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Friday', '2nd Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Friday', '3rd Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Friday', '4th Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Friday', '5th Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Friday', '6th Hr')">Choose Subject</button></td>
                        <td><button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="openSubjectModal('Friday', '7th Hr')">Choose Subject</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Subject Modal -->
        <div class="modal fade" id="subjectModal" tabindex="-1" aria-labelledby="subjectModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="subjectModalLabel">Choose Subject</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="modalInfo"></p>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="subjectSelect" class="form-label">Select Subject 1:</label>
                                <select class="form-select" id="subjectSelect">
                                    <option value="Math">Mathematics</option>
                                    <option value="Physics">Physics</option>
                                    <option value="Chemistry">Chemistry</option>
                                    <option value="Biology">Biology</option>
                                    <option value="English">English</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="facultySelect" class="form-label">Select subject 2:</label>
                                <select class="form-select" id="facultySelect">
                                <option value="Math">Mathematics</option>
                                    <option value="Physics">Physics</option>
                                    <option value="Chemistry">Chemistry</option>
                                    <option value="Biology">Biology</option>
                                    <option value="English">English</option>
                                    </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="saveSubject()">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Faculty Modal -->
        <div class="modal fade" id="facultyModal" tabindex="-1" aria-labelledby="facultyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="facultyModalLabel">Select Faculty</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="facultySelect" class="form-label">Choose Faculty:</label>
                        <select class="form-select" id="facultySelect">
                            <option selected disabled>Select a faculty</option>
                            <option value="faculty1">Faculty 1</option>
                            <option value="faculty2">Faculty 2</option>
                            <option value="faculty3">Faculty 3</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include ('footer')

    <script>
        // Remove unnecessary loader initialization
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-primary[data-bs-target="#facultyModal"]').forEach(button => {
                button.addEventListener('click', () => {
                    const subjectRow = button.closest('tr');
                    const subjectCode = subjectRow.cells[0].textContent.trim();
                    const subjectName = subjectRow.cells[1].textContent.trim();
                    document.getElementById('facultyModalLabel').textContent = `Select Faculty for ${subjectName} (${subjectCode})`;
                });
            });
        });

        let selectedDay = '';
        let selectedHour = '';

        function openSubjectModal(day, hour) {
            selectedDay = day;
            selectedHour = hour;
            document.getElementById('modalInfo').textContent = `Day: ${day}, Hour: ${hour}`;
        }

        function saveSubject() {
            const selectedSubject = document.getElementById('subjectSelect').value;
            const selectedFaculty = document.getElementById('facultySelect').value;

            if (!selectedSubject || !selectedFaculty) {
                alert('Please select both a subject and a faculty.');
                return;
            }

            alert(`Saved ${selectedSubject} with ${selectedFaculty} for ${selectedDay}, Hour ${selectedHour}`);
            // Close the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('subjectModal'));
            modal.hide();
        }
    </script>
</body>

</html>