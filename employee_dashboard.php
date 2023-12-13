<?php
session_start();
include 'db_connection.php';
ini_set('display_errors', 1);

// Check if the user is logged in as an employee
if (!isset($_SESSION['employee_id'])) {
    echo "Access Denied. You are not authorized to view this page.";
    exit; // Stop further script execution
}

// Function to delete a rental record
function deleteRentalRecord($conn, $rentalId) {
    // Use prepared statement to delete the record from the database
    $deleteQuery = "DELETE FROM adg_rentalservice WHERE rentalid = ?";
    
    if ($stmt = $conn->prepare($deleteQuery)) {
        $stmt->bind_param("i", $rentalId);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
        
        $stmt->close();
    } else {
        return false;
    }
}

// Check if a rental record should be deleted
if (isset($_GET['delete']) && isset($_GET['rentalId'])) {
    $rentalIdToDelete = $_GET['rentalId'];
    
    // Call the deleteRentalRecord function to delete the record
    if (deleteRentalRecord($conn, $rentalIdToDelete)) {
        echo "Rental record deleted successfully.";
    } else {
        echo "Error deleting rental record.";
    }
}

// Query to get all rental service records
$rentalServiceQuery = "SELECT * FROM adg_rentalservice";
$rentalServiceResult = $conn->query($rentalServiceQuery);

// Query to get all vehicle records
$vehicleQuery = "SELECT * FROM adg_vehicle";
$vehicleResult = $conn->query($vehicleQuery);

$ongoingRentalsQuery = "SELECT v.vehiclemodel, COUNT(r.rentalid) as rental_count 
                        FROM adg_rentalservice r
                        JOIN adg_vehicle v ON r.vin = v.vin
                        WHERE (r.pickup_date <= '2024-01-30' AND r.dropoff_date >= '2024-01-01')
                        GROUP BY v.vehiclemodel
                        ORDER BY rental_count DESC;";

$ongoingRentalsResult = $conn->query($ongoingRentalsQuery);
$rentalData = array();

while($row = $ongoingRentalsResult->fetch_assoc()) {
    $rentalData[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="path_to_your_stylesheet.css"> <!-- Link your CSS file here -->

    <!-- Include the styles from the index page -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.fancybox.min.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
    <link rel="stylesheet" href="css/aos.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Add padding to the left and right sides */
        body {
            padding: 30px;
        }
    </style>
</head>
<body>
<div class="site-wrap" id="home-section">
    <!-- Header Section -->
    
    <div class="site-mobile-menu site-navbar-target">
        <div class="site-mobile-menu-header">
          <div class="site-mobile-menu-close mt-3">
            <span class="icon-close2 js-menu-toggle"></span>
          </div>
        </div>
        <div class="site-mobile-menu-body"></div>
      </div>



      <header class="site-navbar site-navbar-target" role="banner">

        <div class="container">
          <div class="row align-items-center position-relative">

            <div class="col-3">
              <div class="site-logo">
                <a href="index.php"><strong>ZappRental</strong></a>
              </div>
            </div>

            <div class="col-9  text-right">
              
              <span class="d-inline-block d-lg-none"><a href="#" class=" site-menu-toggle js-menu-toggle py-5 "><span class="icon-menu h3 text-black"></span></a></span>

              <nav class="site-navigation text-right ml-auto d-none d-lg-block" role="navigation">
                <ul class="site-menu main-menu js-clone-nav ml-auto">
                  <li class="active"><a href="index.php" class="nav-link">Home</a></li>
                  <li><a href="listing.php" class="nav-link">Listing</a></li>
                  <!-- <li><a href="testimonials.html" class="nav-link">Testimonials</a></li>
                  <li><a href="blog.html" class="nav-link">Blog</a></li> -->
                  <li><a href="about.php" class="nav-link">About</a></li>
                  <li><a href="contact.php" class="nav-link">Contact</a></li>
                </ul>
              </nav>
            </div>
          </div>
        </div>

      </header>

    <!-- Rental Service Records Section -->
    <div class="site-section bg-light margintop">
    <div class="chart-container" style="position: relative; height:40vh; width:80vw">
        <canvas id="rentalChart"></canvas>
    </div>
        <h2>Rental Service Records</h2>
        <table class="table table-bordered"> <!-- Apply Bootstrap table styles -->
            <thead class="thead-dark"> <!-- Apply Bootstrap table header styles -->
                <tr>
                    <th>Rental ID</th>
                    <th>Pickup Date</th>
                    <th>Dropoff Date</th>
                    <th>Start Odometer</th>
                    <th>End Odometer</th>
                    <th>Daily Odometer Limit</th>
                    <th>Pickup Location</th>
                    <th>Dropoff Location</th>
                    <th>Customer ID</th>
                    <th>VIN</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $rentalServiceResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['rentalid']; ?></td>
                    <td><?php echo $row['pickup_date']; ?></td>
                    <td><?php echo $row['dropoff_date']; ?></td>
                    <td><?php echo $row['start_odometer']; ?></td>
                    <td><?php echo $row['end_odometer']; ?></td>
                    <td><?php echo $row['daily_odometer_limit']; ?></td>
                    <td><?php echo $row['pickup_location']; ?></td>
                    <td><?php echo $row['dropoff_location']; ?></td>
                    <td><?php echo $row['custid']; ?></td>
                    <td><?php echo $row['vin']; ?></td>
                    <td>
                        <a href="?delete=true&rentalId=<?php echo $row['rentalid']; ?>" onclick="return confirm('Are you sure you want to delete this record?');">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
    </table>
    <!-- Vehicle Records Section -->
    <div class="site-section bg-light margintop">
        <h2>Vehicle Records</h2>
        <table id="vehicleTable" class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>VIN</th>
                    <th>Vehicle Make</th>
                    <th>Vehicle Model</th>
                    <th>Vehicle Year</th>
                    <th>License Plate</th>
                    <th>Office ID</th>
                    <th>Status ID</th>
                    <th>Class ID</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $vehicleResult->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['vin']; ?></td>
                        <td><?php echo $row['vehiclemake']; ?></td>
                        <td><?php echo $row['vehiclemodel']; ?></td>
                        <td><?php echo $row['vehicleyear']; ?></td>
                        <td><?php echo $row['licenseplate']; ?></td>
                        <td><?php echo $row['office_id']; ?></td>
                        <td><?php echo $row['statusid']; ?></td>
                        <td><?php echo $row['classid']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
                </div>
 </div> 
</div>

<script>
     $(document).ready(function() {
        // AJAX form submission
        $("form").submit(function(event) {
            event.preventDefault(); // Prevent default form submission

            $.ajax({
                url: 'employee_dashboard.php', // Your PHP script URL
                type: 'post',
                data: $(this).serialize(), // Serialize form data
                success: function(response) {
                    // Assuming the response contains the HTML for the new row
                    $("#vehicleTable tbody").append(response);
                },
                error: function() {
                    alert("Error adding new vehicle record.");
                }
            });
        });
    function deleteRecord(rentalId) {
        // Send an AJAX request to delete the record
        $.ajax({
            url: 'delete_record.php', // Replace with the actual URL for the deletion script
            method: 'POST',
            data: { rentalId: rentalId },
            success: function(response) {
                if (response === 'success') {
                    // Remove the row from the table
                    alert('Record deleted successfully.');
                    location.reload(); // Refresh the page to update the table
                } else {
                    alert('Failed to delete record.');
                }
            }
        });
    }
    // Function to add a new column to the table
    function addColumn() {
        const newColumnHTML = `
            <tr>
                <td><input type="text" name="new_rental_id[]"></td>
                <td><input type="text" name="new_pickup_date[]"></td>
                <td><input type="text" name="new_dropoff_date[]"></td>
                <td><input type="text" name="new_start_odometer[]"></td>
                <td><input type="text" name="new_end_odometer[]"></td>
                <td><input type="text" name="new_daily_odometer_limit[]"></td>
                <td><input type="text" name="new_pickup_location[]"></td>
                <td><input type="text" name="new_dropoff_location[]"></td>
                <td><input type="text" name="new_customer_id[]"></td>
                <td><input type="text" name="new_vin[]"></td>
                <td></td>
            </tr>
        `;

        // Append the new column to the table body
        $("#newColumnsBody").append(newColumnHTML);
    }

    // Add event listener for the "Add Column" button
    $("#addColumnBtn").click(addColumn);

    
});
</script>
<script>
        const rentalData = <?php echo json_encode($rentalData); ?>;
        
        const labels = rentalData.map(data => data.vehiclemodel);
        const dataCounts = rentalData.map(data => data.rental_count);

        const ctx = document.getElementById('rentalChart').getContext('2d');
        const rentalChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Ongoing Rentals in January 2024',
                    data: dataCounts,
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
