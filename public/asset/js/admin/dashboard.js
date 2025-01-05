document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    let userActivityChart = null;
    let userRolesChart = null;

    // Function to update the dashboard data
    function updateDashboardData() {
        fetch('/admin/dashboard/data')
            .then(response => response.json())
            .then(data => {
                // Update education level statistics
                updateEducationStats(data.education_levels, data.growth_stats);
                // Update organization statistics
                updateOrganizationStats(data.organizations);
                // Update activity chart
                updateActivityChart(data.teachers, data.training_enrollments);
                // Update roles chart
                updateRolesChart(data.user_roles);
                // Update recent activities
                updateRecentActivities(data.recent_activities);
            })
            .catch(error => console.error('Error fetching dashboard data:', error));
    }

    // Function to update education level statistics
    function updateEducationStats(stats, growth) {
        const levels = ['Pre Primary', 'Primary', 'Lower Secondary', 'Upper Secondary'];
        levels.forEach(level => {
            const total = stats[level]?.total || 0;
            const growthRate = growth[level] || 0;
            const elementId = level.toLowerCase().replace(' ', '') + 'Count';
            
            document.getElementById(elementId).textContent = total;
            
            const progressElement = document.getElementById(elementId).parentElement.querySelector('.stat-card-progress');
            const icon = progressElement.querySelector('i');
            const span = progressElement.querySelector('span');
            
            icon.className = `fas fa-arrow-${growthRate >= 0 ? 'up' : 'down'}`;
            span.textContent = `${Math.abs(growthRate)}% ${growthRate >= 0 ? 'increase' : 'decrease'}`;
        });
    }

    // Function to update organization statistics
    function updateOrganizationStats(stats) {
        document.querySelector('[data-stat="total-organizations"] .text-success').textContent = '100%';
        document.querySelector('[data-stat="total-organizations"] .progress-bar').style.width = '100%';

        document.querySelector('[data-stat="organizations-training"] .text-warning').textContent = stats.offering_training_percentage + '%';
        document.querySelector('[data-stat="organizations-training"] .progress-bar').style.width = stats.offering_training_percentage + '%';

        document.querySelector('[data-stat="organizations-no-training"] .text-info').textContent = stats.not_offering_training_percentage + '%';
        document.querySelector('[data-stat="organizations-no-training"] .progress-bar').style.width = stats.not_offering_training_percentage + '%';
    }

    // Function to update the activity chart
    function updateActivityChart(teachers, enrollments) {
        const educationLevels = ['Pre Primary', 'Primary', 'Lower Secondary', 'Upper Secondary'];
        const datasets = [
            {
                label: 'Female Teachers',
                data: educationLevels.map(level => teachers[level]?.female || 0),
                backgroundColor: 'rgba(255, 99, 132, 0.8)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            },
            {
                label: 'Male Teachers',
                data: educationLevels.map(level => teachers[level]?.male || 0),
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Female Teachers in Training',
                data: educationLevels.map(level => enrollments[level]?.female || 0),
                backgroundColor: 'rgba(255, 159, 64, 0.8)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            },
            {
                label: 'Male Teachers in Training',
                data: educationLevels.map(level => enrollments[level]?.male || 0),
                backgroundColor: 'rgba(75, 192, 192, 0.8)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }
        ];

        if (userActivityChart) {
            userActivityChart.data.datasets = datasets;
            userActivityChart.update();
        } else {
            const ctx = document.getElementById('userActivityChart').getContext('2d');
            userActivityChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: educationLevels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Teacher Distribution by Education Level and Training'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Teachers'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Education Level'
                            }
                        }
                    }
                }
            });
        }
    }

    // Function to update the roles chart
    function updateRolesChart(roles) {
        const data = {
            labels: Object.keys(roles).map(role => role.charAt(0).toUpperCase() + role.slice(1)),
            datasets: [{
                data: Object.values(roles),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        };

        if (userRolesChart) {
            userRolesChart.data = data;
            userRolesChart.update();
        } else {
            const ctx = document.getElementById('userRolesChart').getContext('2d');
            userRolesChart = new Chart(ctx, {
                type: 'doughnut',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'User Distribution'
                        }
                    }
                }
            });
        }
    }

    // Function to update recent activities
    function updateRecentActivities(activities) {
        const container = document.querySelector('.info-card-body');
        container.innerHTML = activities.map(activity => `
            <div class="activity-item">
                <div class="activity-content">
                    <div class="activity-title">${activity.title}</div>
                    <small class="text-muted">${activity.name} - ${moment(activity.time).fromNow()}</small>
                    ${activity.training ? `
                        <div class="activity-details">
                            <small class="text-muted">Training: ${activity.training}</small>
                        </div>
                    ` : ''}
                </div>
            </div>
        `).join('');
    }

    // Initial update
    updateDashboardData();

    // Update every 30 seconds
    setInterval(updateDashboardData, 30000);
});
