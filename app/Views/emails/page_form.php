<!DOCTYPE html>
<html>
<head>
    <title>Welcome!</title>
</head>
<body>
    <p>Repair Type : <?= $data['os_type']; ?></p>
    <p>Brand : <?= $data['os_brand']; ?></p>
    <p>Model/Part Number : <?= $data['os_model_no']; ?></p>
    <p>Serial Number : <?= $data['os_serial_no']; ?></p>
    <p>Year Purchased : <?= $data['os_year_purchased']; ?></p>
    <p>Problem : <?= $data['os_problem']; ?></p>
    <p>First Name : <?= $data['os_fname']; ?></p>
    <p>Last Name : <?= $data['os_lname']; ?></p>
    <p>Your Suburb or Postcode : <?= $data['os_suburb_postcode']; ?></p>
    <p>Email : <?= $data['os_email']; ?></p>
    <p>Phone Number : <?= $data['os_contact_no']; ?></p>
    <p>Message : <?= $data['os_msg']; ?></p>
</body>
</html>
