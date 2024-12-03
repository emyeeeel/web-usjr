document.addEventListener("DOMContentLoaded", function() {
    function fetchStudents() {
        axios.get('get-list.php')
            .then(function(response) {
                const students = response.data;

                // Clear any previous data in the table
                const tbody = document.querySelector('table tbody');
                tbody.innerHTML = '';

                if (students && students.length > 0) {
                    students.forEach(student => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${student.studid}</td>
                            <td>${student.studlastname}</td>
                            <td>${student.studfirstname}</td>
                            <td>${student.studmidname}</td>
                            <td>${student.college_name}</td>
                            <td>${student.program_name}</td>
                            <td>${student.studyear}</td>
                            <td class="action-buttons">
                                <button class="edit-btn">Edit</button>
                                <button class="delete-btn">Delete</button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });

                    // Add event listener for Edit buttons
                    const editButtons = document.querySelectorAll('.edit-btn');
                    editButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            const row = button.closest('tr');
                            const studentId = row.cells[0].textContent;  // Correctly target the student ID from the first cell

                            // Store student data in sessionStorage
                            sessionStorage.setItem('studentData', JSON.stringify({
                                student_id: studentId,
                                first_name: row.cells[2].textContent,
                                middle_name: row.cells[3].textContent,
                                last_name: row.cells[1].textContent,
                                college: row.cells[4].textContent,
                                program: row.cells[5].textContent,
                                year: row.cells[6].textContent
                            }));

                            // Redirect to edit-entry.php
                            window.location.href = 'edit-entry.php';
                            console.log("Redirecting to edit-entry.php");  // For debugging
                        });
                    });

                    // Add event listener for Delete buttons (same structure as before)
                    const deleteButtons = document.querySelectorAll('.delete-btn');
                    deleteButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            const row = button.closest('tr');
                            const studentId = row.cells[0].textContent;  // Correctly target the student ID

                            sessionStorage.setItem('studentData', JSON.stringify({
                                student_id: studentId,
                                first_name: row.cells[2].textContent,
                                middle_name: row.cells[3].textContent,
                                last_name: row.cells[1].textContent,
                                college: row.cells[4].textContent,
                                program: row.cells[5].textContent,
                                year: row.cells[6].textContent
                            }));

                            // Redirect to delete-entry.php
                            window.location.href = 'delete-entry.php';
                        });
                    });
                } else {
                    const row = document.createElement('tr');
                    row.innerHTML = '<td colspan="8" style="text-align: center;">No students found</td>';
                    tbody.appendChild(row);
                }
            })
            .catch(function(error) {
                console.error("Error fetching data:", error);
                const tbody = document.querySelector('table tbody');
                tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; color: red;">Failed to fetch student data. Please try again later.</td></tr>';
            });
    }

    fetchStudents();

    const addStudentButton = document.querySelector('.add-student-button');
    if (addStudentButton) {
        addStudentButton.addEventListener('click', redirectToStudentEntry);
    }
});
