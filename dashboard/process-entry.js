let colleges = {};  // Global variable to store colleges data
let programs = {};  // Global variable to store programs data

// Function to load colleges from the server
function loadColleges() {
    axios.get('get-data.php', { params: { type: 'colleges' } })
        .then(function(response) {
            const collegeSelect = document.getElementById('college');
            if (response.data && response.data.length > 0) {
                response.data.forEach(function(college) {
                    colleges[college.collid] = college.collfullname;
                    const option = document.createElement('option');
                    option.value = college.collid;  // Set college ID as the option value
                    option.textContent = college.collfullname;  // Set college name as the option text
                    collegeSelect.appendChild(option);
                });
            } else {
                console.error("No colleges found.");
            }
        })
        .catch(function(error) {
            console.error('Error loading college data:', error);
        });
}

// Function to load programs from the server
function loadPrograms() {
    axios.get('get-data.php', { params: { type: 'programs' } })
        .then(function(response) {
            if (response.data && response.data.length > 0) {
                response.data.forEach(function(program) {
                    if (!programs[program.progcollid]) {
                        programs[program.progcollid] = [];
                    }
                    programs[program.progcollid].push(program);
                });
            } else {
                console.error("No programs found.");
            }
        })
        .catch(function(error) {
            console.error('Error loading program data:', error);
        });
}

// Handle the event when a college is selected
function onCollegeChange(event) {
    const selectedCollegeId = event.target.value;
    const programSelect = document.getElementById('program');

    // Reset the program dropdown
    programSelect.innerHTML = '<option value="" disabled selected>Select Program</option>';

    if (programs[selectedCollegeId] && programs[selectedCollegeId].length > 0) {
        programs[selectedCollegeId].forEach(function(program) {
            const option = document.createElement('option');
            option.value = program.progid;
            option.textContent = program.progfullname;
            programSelect.appendChild(option);
        });
    } else {
        const noProgramsOption = document.createElement('option');
        noProgramsOption.value = '';
        noProgramsOption.textContent = 'No programs available';
        programSelect.appendChild(noProgramsOption);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // This will ensure everything is loaded and event listeners are attached
    loadColleges();
    loadPrograms();

    const collegeSelect = document.getElementById('college');
    collegeSelect.addEventListener('change', onCollegeChange);
});