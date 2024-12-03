let colleges = {};  // Global variable to store colleges data
let programs = {};  // Global variable to store programs data

// Function to load colleges from the server
function loadColleges() {
    axios.get('get-data.php', { params: { type: 'colleges' } }) 
        .then(function(response) {
            const collegeSelect = document.getElementById('college');
            
            console.log(response.data);
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
                // Organize programs by their college ID
                response.data.forEach(function(program) {
                    if (!programs[program.progcollid]) {
                        programs[program.progcollid] = [];  
                    }
                    programs[program.progcollid].push(program);  // Store programs by their associated college ID
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
    const selectedCollegeId = event.target.value;  // Get the selected college ID
    console.log("Selected college ID:", selectedCollegeId);

    const programSelect = document.getElementById('program');

    // Reset the program dropdown
    programSelect.innerHTML = '<option value="" disabled selected>Select Program</option>';

    if (programs[selectedCollegeId] && programs[selectedCollegeId].length > 0) {
        // Populate the program dropdown with programs specific to the selected college
        programs[selectedCollegeId].forEach(function(program) {
            const option = document.createElement('option');  
            option.value = program.progid;  // Set program ID (progid) as the option value
            option.textContent = program.progfullname;  // Set the program full name as the option text
            programSelect.appendChild(option);  
        });
    } else {
        console.log("No programs available for the selected college.");
        const noProgramsOption = document.createElement('option');
        noProgramsOption.value = '';  
        noProgramsOption.textContent = 'No programs available';  
        programSelect.appendChild(noProgramsOption);  
    }
}

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    loadColleges();  // Load colleges when the page loads
    loadPrograms();  // Load programs when the page loads

    const collegeSelect = document.getElementById('college');
    collegeSelect.addEventListener('change', onCollegeChange);  // Add event listener for college selection
});

