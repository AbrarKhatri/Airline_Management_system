<?php
    require_once "dbconnection.php";
    require_once "tcpdf/tcpdf.php"; // Adjust the path as per your setup

    // Fetch data from database
    $query = mysqli_query($con, "SELECT * FROM selected");
    $rows = mysqli_fetch_array($query);
    $flight = $rows['FLIGHT_CODE'];

    $query = mysqli_query($con, "SELECT * FROM pass");
    $rows = mysqli_fetch_array($query);
    $passportno = $rows['PASSPORT_NO'];

    $query = mysqli_query($con, "SELECT * FROM ticket WHERE PASSPORT_NO='$passportno'");
    $rows = mysqli_fetch_array($query);
    $ticketno = $rows['TICKET_NO'];
    $source = $rows['SOURCE'];
    $destination = $rows['DESTINATION'];
    $date = $rows['DATE_OF_TRAVEL'];

    $query = mysqli_query($con, "SELECT * FROM passenger WHERE PASSPORT_NO='$passportno'");
    $rows = mysqli_fetch_array($query);
    $fname = $rows['FNAME'];
    $lname = $rows['LNAME'];
    $age = $rows['AGE'];
    $sex = $rows['SEX'];
    $phone = $rows['PHONE'];
    $address = $rows['ADDRESS'];

    $query = mysqli_query($con, "SELECT * FROM flight WHERE FLIGHT_CODE='$flight'");
    $rows = mysqli_fetch_array($query);
    $arrival = $rows['ARRIVAL'];
    $departure = $rows['DEPARTURE'];
    $duration = $rows['DURATION'];
    $airlineid = $rows['AIRLINE_ID'];

    $query = mysqli_query($con, "SELECT * FROM airline WHERE AIRLINE_ID='$airlineid'");
    $rows = mysqli_fetch_array($query);
    $airlinename = $rows['AIRLINE_NAME'];

    $query = mysqli_query($con, "SELECT PRICE, TYPE FROM price");
    $rows = mysqli_fetch_array($query);
    $price = $rows['PRICE'];
    $type = $rows['TYPE'];

    // Delete records from tables
    $sql1 = mysqli_query($con, "DELETE FROM selected WHERE FLIGHT_CODE='$flight'");
    $sql2 = mysqli_query($con, "DELETE FROM pass WHERE PASSPORT_NO='$passportno'");
    $sql3 = mysqli_query($con, "DELETE FROM price WHERE PRICE='$price'");

    // Create PDF
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle('E-Ticket');

    $pdf->AddPage();

    // Output ticket information in the PDF
    $content = "
        First Name: $fname
        Last Name: $lname
        Age: $age
        Sex: $sex
        Phone: $phone
        Address: $address
        Passport Number: $passportno
        Source: $source
        Destination: $destination
        Arrival: $arrival
        Departure: $departure
        Duration: $duration
        Date: $date
        Price: $price
        Type: $type
        Airline Name: $airlinename
        Flight Code: $flight
        Ticket Number: $ticketno
    ";

    $pdf->writeHTML($content, true, false, true, false, '');

    // Save PDF file
    $pdf->Output('ticket.pdf', 'D'); // D for download, or F for saving to a file

    mysqli_close($con);
?>
