document.addEventListener('DOMContentLoaded', function() {
    // Get the chart data from the server
    const chartData = window.dashboardData;

    // User Activity Chart
    const userActivityCtx = document.getElementById('userActivityChart');
    if (userActivityCtx) {
        new Chart(userActivityCtx, {
            type: 'bar',
            data: {
                labels: chartData.chart_data.labels,
                datasets: chartData.chart_data.datasets
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
                        text: 'Teacher Distribution by Education Level'
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

    // User Roles Chart
    const userRolesCtx = document.getElementById('userRolesChart');
    if (userRolesCtx) {
        const userRolesData = window.dashboardData.user_roles;
        
        // Create a mapping for better role display names
        const roleDisplayNames = {
            'teacher': 'Teachers',
            'admin': 'Administrators',
            'organization': 'Organizations',
            'cpd_facilitator': 'CPD Facilitators',
            'super_administrator': 'Super Administrators'
        };

        // Create arrays for labels and data
        const labels = [];
        const data = [];
        const colors = [
            '#009c95',  // green for teachers
            '#dc3545',  // red for admins
            '#800071',  // yellow for organizations
            '#045bb3',  // blue for cpd facilitators
            '#6f42c1'   // purple for super admins
        ];

        // Add data only for roles that have values
        Object.entries(userRolesData).forEach(([role, count]) => {
            if (count > 0) {
                labels.push(roleDisplayNames[role] || role.split('_').map(word => 
                    word.charAt(0).toUpperCase() + word.slice(1)
                ).join(' '));
                data.push(count);
            }
        });

        new Chart(userRolesCtx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors.slice(0, labels.length)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'User Distribution',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: {
                            bottom: 15
                        }
                    }
                }
            }
        });
    }

    setTimeout(() => {
        document.getElementById('totalTeachers').textContent = '1,234';
        document.getElementById('publicTeachers').textContent = '856';
        document.getElementById('privateTeachers').textContent = '378';
        document.getElementById('activeUsers').textContent = '952';
    }, 1000);

    // User Roles Doughnut Chart
    const rolesCtx = document.getElementById('userRolesChart');
    if (rolesCtx) {
        new Chart(rolesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Administrators', 'Teachers', 'Staff', 'Others'],
                datasets: [{
                    data: [15, 45, 30, 10],
                    backgroundColor: [
                        '#009c95',
                        '#00b5ad',
                        '#00c4bc',
                        '#00d3cb'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'User Distribution by Role',
                        font: { size: 16, weight: 'bold' },
                        padding: {
                            bottom: 15
                        }
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            font: {
                                size: 11
                            }
                        }
                    }
                },
                cutout: '65%',
                layout: {
                    padding: {
                        top: 15,
                        bottom: 15
                    }
                }
            }
        });
    }
});