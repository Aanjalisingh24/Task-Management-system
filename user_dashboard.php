<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Task Management Dashboard</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>


<div id="header" class="d-flex justify-content-between align-items-center">
  <h3>Task Management System</h3>
  <div>
    <strong>Email:</strong> Test@gmail.com &nbsp;&nbsp;
    <strong>Name:</strong> Test &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  </div>
</div>

<!-- Body Content -->
<div class="container-fluid">
  <div class="row">
    <!-- Left Sidebar -->
    <div class="col-md-2" id="left_sidebar">
      <nav class="nav flex-column">
        <a class="nav-link" href="#dashboard" onclick="loadContent('Dashboard')">Dashboard</a>
        <a class="nav-link" href="#update" onclick="loadContent('Update Task')">Update Task</a>
        <a class="nav-link" href="#applyLeave" onclick="loadContent('Apply Leave')">Apply Leave</a>
        <a class="nav-link" href="#leaveStatus" onclick="loadContent('Leave Status')">Leave Status</a>
        <a class="nav-link" href="#logout" onclick="logout()">Logout</a>
      </nav>
    </div>

    <!-- Right Sidebar -->
    <div class="col-md-10" id="right_sidebar">
      <h4>Welcome to the Dashboard</h4>
      <p>Select an option from the left menu to proceed.</p>
      <div id="content_area"></div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

<script>
  function loadContent(section) {
    const content = {
      "Dashboard": "<h5>Dashboard</h5><p>This is your main dashboard where you can see overall status.</p>",
      "Update Task": "<h5>Update Task</h5><p>Here you can update your assigned tasks.</p>",
      "Apply Leave": "<h5>Apply Leave</h5><p>Fill in the leave application form to apply for a leave.</p>",
      "Leave Status": "<h5>Leave Status</h5><p>View the status of your leave applications.</p>"
    };
    document.getElementById('content_area').innerHTML = content[section] || "<p>Feature under development.</p>";
  }

  function logout() {
    alert("You have been logged out!");
    window.location.href = "index.php";
  }
</script>
</body>
</html>
