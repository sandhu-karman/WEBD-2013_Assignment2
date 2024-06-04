<?php

/*******w******** 
    
    Name: Ashbeel Bhatti
    Date: 2024-05-27
    Description: Assignment 2 - WEBD-2013 Web Development 2

****************/



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Thanks for your order!</title>
</head>
<body>
    <!-- Remember that alternative syntax is good and html inside php is bad -->
    <?php
        // Define variables and set them to empty values
        $fullname = $address = $city = $province = $postal = $email = $cardname = $cardnumber = $month = $year = "";
        $fullnameErr = $addressErr = $cityErr = $provinceErr = $postalErr = $emailErr = $cardnameErr = $cardnumberErr = $monthErr = $yearErr = $cardtypeErr = "";

        // Function to clean and trim input data
        function clean_input($data) {
            return trim(htmlspecialchars(stripslashes($data)));
        }

        // Validate POST data
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Full Name
            if (empty($_POST["fullname"])) {
                $fullnameErr = "Full Name is required";
            } else {
                $fullname = clean_input($_POST["fullname"]);
            }
            
            // Address
            if (empty($_POST["address"])) {
                $addressErr = "Address is required";
            } else {
                $address = clean_input($_POST["address"]);
            }

            // City
            if (empty($_POST["city"])) {
                $cityErr = "City is required";
            } else {
                $city = clean_input($_POST["city"]);
            }

            // Province
            if (empty($_POST["province"])) {
                $provinceErr = "Province is required";
            } else {
                $province = clean_input($_POST["province"]);
            }

            // Postal Code
            if (empty($_POST["postal"])) {
                $postalErr = "Postal Code is required";
            } else {
                $postal = clean_input($_POST["postal"]);
                if (!preg_match("/^[A-Za-z]\d[A-Za-z] ?\d[A-Za-z]\d$/", $postal)) {
                    $postalErr = "Invalid Postal Code format";
                }
            }

            // Email
            if (empty($_POST["email"])) {
                $emailErr = "Email is required";
            } else {
                $email = clean_input($_POST["email"]);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "Invalid email format";
                }
            }

            // Card Name
            if (empty($_POST["cardname"])) {
                $cardnameErr = "Name on Card is required";
            } else {
                $cardname = clean_input($_POST["cardname"]);
            }

            // Card Number
            if (empty($_POST["cardnumber"])) {
                $cardnumberErr = "Card Number is required";
            } else {
                $cardnumber = clean_input($_POST["cardnumber"]);
                if (!is_numeric($cardnumber) || strlen($cardnumber) != 10) {
                    $cardnumberErr = "Invalid Card Number";
                }
            }

            // Expiry Month
            if (empty($_POST["month"])) {
                $monthErr = "Expiry Month is required";
            } else {
                $month = clean_input($_POST["month"]);
                if (!is_numeric($month) || $month < 1 || $month > 12) {
                    $monthErr = "Invalid Expiry Month";
                }
            }

            // Expiry Year
            if (empty($_POST["year"])) {
                $yearErr = "Expiry Year is required";
            } else {
                $year = clean_input($_POST["year"]);
                $currentYear = date("Y");
                if (!is_numeric($year) || $year < $currentYear || $year > $currentYear + 5) {
                    $yearErr = "Invalid Expiry Year";
                }
            }

            // Card Type
            if (empty($_POST["cardtype"])) {
                $cardtypeErr = "Card Type is required";
            } else {
                $cardtype = clean_input($_POST["cardtype"]);
            }

            // Order validation
            $itemsOrdered = [];
            for ($i = 1; $i <= 5; $i++) {
                if (!empty($_POST["qty" . $i])) {
                    $quantity = clean_input($_POST["qty" . $i]);
                    if (is_numeric($quantity) && $quantity > 0) {
                        $descriptionKey = "description" . $i;
                        $priceKey = "price" . $i;
                        
                        $itemsOrdered[] = [
                            "description" => isset($_POST[$descriptionKey]) ? clean_input($_POST[$descriptionKey]) : "N/A",
                            "price" => isset($_POST[$priceKey]) ? clean_input($_POST[$priceKey]) : 0,
                            "quantity" => $quantity,
                            "total" => $quantity * (isset($_POST[$priceKey]) ? clean_input($_POST[$priceKey]) : 0)
                        ];
                    }
                }
            }

            // Check for errors
            if (empty($fullnameErr) && empty($addressErr) && empty($cityErr) && empty($provinceErr) && empty($postalErr) && empty($emailErr) && empty($cardnameErr) && empty($cardnumberErr) && empty($monthErr) && empty($yearErr) && empty($cardtypeErr)) {
                // Generate the invoice
                echo "<h2>Thanks for your order $fullname.</h2>";
                echo "<h3>Here's a summary of your order:</h3>";
                echo "<fieldset id='orderSummary'><legend>Order Summary</legend>";
                echo "<div class='left'>";
                echo "<p>Address: $address, $city, $province, $postal</p>";
                echo "<p>Email: $email</p>";
                echo "</div>";
                echo "<div class='right'>";
                echo "<table><tr><th>Quantity</th><th>Description</th><th>Cost</th></tr>";
                $totalCost = 0;
                foreach ($itemsOrdered as $item) {
                    echo "<tr><td>{$item['quantity']}</td><td>{$item['description']}</td><td>\${$item['total']}</td></tr>";
                    $totalCost += $item['total'];
                }
                echo "<tr><td colspan='2'>Total</td><td>\$$totalCost</td></tr>";
                echo "</table></div></fieldset>";
            } else {
                // Display error messages
                echo "<h2>Form could not be processed due to the following errors:</h2>";
                echo "<ul>";
                if (!empty($fullnameErr)) echo "<li>$fullnameErr</li>";
                if (!empty($addressErr)) echo "<li>$addressErr</li>";
                if (!empty($cityErr)) echo "<li>$cityErr</li>";
                if (!empty($provinceErr)) echo "<li>$provinceErr</li>";
                if (!empty($postalErr)) echo "<li>$postalErr</li>";
                if (!empty($emailErr)) echo "<li>$emailErr</li>";
                if (!empty($cardnameErr)) echo "<li>$cardnameErr</li>";
                if (!empty($cardnumberErr)) echo "<li>$cardnumberErr</li>";
                if (!empty($monthErr)) echo "<li>$monthErr</li>";
                if (!empty($yearErr)) echo "<li>$yearErr</li>";
                if (!empty($cardtypeErr)) echo "<li>$cardtypeErr</li>";
                echo "</ul>","</body>","</html>";
            }
        }
    ?>