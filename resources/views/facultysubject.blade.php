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
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
                                <label for="subject1Select" class="form-label">Select Subject 1:</label>
                                <select class="form-select" id="subject1Select">
                                    <option value="" selected disabled>Select Subject</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="subject2Select" class="form-label">Select Subject 2 (Optional):</label>
                                <select class="form-select" id="subject2Select">
                                    <option value="" selected disabled>Select Subject</option>
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

        function openSubjectModal(day, hour, cell = null) {
            selectedDay = day;
            selectedHour = hour;
            currentCell = cell;
            document.getElementById('modalInfo').textContent = `Day: ${day}, Hour: ${hour}`;

            fetch('/subjectsfetch')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        populateSubjectDropdowns(data.data);
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function populateSubjectDropdowns(subjects) {
            const subject1Select = document.getElementById('subject1Select');
            const subject2Select = document.getElementById('subject2Select');
            
            // Clear existing options
            subject1Select.innerHTML = '<option value="" selected disabled>Select Subject</option>';
            subject2Select.innerHTML = '<option value="" selected disabled>Select Subject</option>';
            
            subjects.forEach(subject => {
                const optionText = `${subject.subjectcode} - ${subject.subjectname} (${subject.fname1}${subject.fname2 ? ', ' + subject.fname2 : ''})`;
                const option1 = new Option(optionText, subject.subjectcode);
                const option2 = new Option(optionText, subject.subjectcode);
                
                // Add faculty IDs as data attributes
                option1.dataset.fac1id = subject.fac1id;
                option1.dataset.fac2id = subject.fac2id;
                option2.dataset.fac1id = subject.fac1id;
                option2.dataset.fac2id = subject.fac2id;
                
                subject1Select.add(option1);
                subject2Select.add(option2);
            });
            
            subject1Select.onchange = updateSecondDropdown;
        }

        function updateSecondDropdown() {
            const subject1Select = document.getElementById('subject1Select');
            const subject2Select = document.getElementById('subject2Select');
            const selectedValue = subject1Select.value;
            
            Array.from(subject2Select.options).forEach(option => {
                option.disabled = option.value === selectedValue;
            });
        }

        function saveSubject() {
            const subject1Select = document.getElementById('subject1Select');
            const subject2Select = document.getElementById('subject2Select');

            if (!subject1Select.value) {
                alert('Please select at least Subject 1.');
                return;
            }

            const selected1 = subject1Select.options[subject1Select.selectedIndex];
            const selected2 = subject2Select.value ? subject2Select.options[subject2Select.selectedIndex] : null;

            // Prepare data for saving
            const data = {
                day: selectedDay,
                hour: selectedHour,
                subject_code1: subject1Select.value,
                fac1_id: selected1.dataset.fac1id,
                subject_code2: subject2Select.value || null,
                fac2_id: selected2 ? selected2.dataset.fac1id : null
            };

            // Send data to backend
            fetch('/timetable/map', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('subjectModal'));
                    modal.hide();

                    // Reload the entire timetable data
                    loadTimetableData();

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Timetable updated successfully'
                    });
                } else {
                    // Check if the error is related to faculty scheduling conflict
                    if (result.message && result.message.includes('faculty')) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Scheduling Conflict',
                            text: 'One or more faculty members are already scheduled during this time slot'
                        }).then(() => {
                            location.reload(); // Reload the page after clicking OK
                        });
                    } else {
                        throw new Error(result.message || 'Failed to save timetable mapping');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to save timetable mapping'
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadTimetableData();
        });

        function loadTimetableData() {
            fetch('/timetable/data')
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        updateTimetableCells(result.data);
                    } else {
                        console.error('Failed to load timetable data:', result.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function updateTimetableCells(mappings) {
            const rows = document.querySelectorAll('#timetable tbody tr');
            rows.forEach(row => {
                const day = row.cells[0].textContent.trim();
                const dayData = mappings[day] || {};

                // For each hour cell (skip first column and break/lunch columns)
                for (let i = 1; i < row.cells.length; i++) {
                    const cell = row.cells[i];
                    if (cell.classList.contains('bg-light')) continue; // Skip break/lunch cells

                    const hour = getHourFromIndex(i);
                    const hourData = dayData[hour] || [];

                    if (hourData.length > 0) {
                        let html = '<div class="selected-subjects">';
                        hourData.forEach(subject => {
                            html += `<div class="selected-subject">${subject.subject_code} - ${subject.subjectname} (${subject.fname1}${subject.fname2 ? ', ' + subject.fname2 : ''})</div>`;
                        });
                        html += `<div class="edit-subjects" onclick="openSubjectModal('${day}', '${hour}', this.closest('td'))">
                            <i class="fas fa-edit"></i> Edit
                        </div></div>`;
                        cell.innerHTML = html;
                    }
                }
            });
        }

        function getHourFromIndex(index) {
            const hourMap = {
                1: '1st Hr',
                2: '2nd Hr',
                4: '3rd Hr',
                5: '4th Hr',
                7: '5th Hr',
                9: '6th Hr',
                10: '7th Hr'
            };
            return hourMap[index] || '';
        }
    </script>
</body>

</html>