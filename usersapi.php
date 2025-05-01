<?php
// Database connection settings
$host = 'localhost';
$db   = 'dev2zyl_dev2';
$user = 'dev2zyl_dev2';
$pass = 'flh5YGOqfCIG!';
$charset = 'utf8mb4';

    // 'username'     => 'dev2zyl_dev2',
      //  'password'     => 'flh5YGOqfCIG!',
      //  'database'     => 'dev2zyl_dev2',
// Set up PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch API data
$apiUrl = "https://dev.zylaxonline.com.au/usersapi.php";
$response = file_get_contents($apiUrl);
$data = json_decode($response, true);

print_r($data);

die;

// Check if API response is valid
if ($data['status'] === 'success' && !empty($data['data'])) {
    foreach ($data['data'] as $user) {
        $email = $user['email'];

        // Check if user already exists by email
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $existing = $stmt->fetch();

        // Prepare data
        $fields = [
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => $user['password'],
            'phone' => $user['phone'],
            'company' => $user['company'],
            'country' => $user['country'],
            'state' => $user['state'],
            'city' => $user['city'],
            'postcode' => $user['postcode'],
            'status' => $user['status'],
            'address' => $user['address'],
            'address1' => $user['address1'],
            'resetPasswordToken' => $user['resetPasswordToken'],
            'resetPasswordExpires' => $user['resetPasswordExpires'],
            'created' => $user['created'],
            'edit_date' => $user['edit_date'],
            'entity_id' => $user['entity_id'],
        ];

        if ($existing) {
            // Update existing user
            $update = $pdo->prepare("UPDATE users SET
                name = :name,
                password = :password,
                phone = :phone,
                company = :company,
                country = :country,
                state = :state,
                city = :city,
                postcode = :postcode,
                status = :status,
                address = :address,
                address1 = :address1,
                resetPasswordToken = :resetPasswordToken,
                resetPasswordExpires = :resetPasswordExpires,
                created = :created,
                edit_date = :edit_date,
                entity_id = :entity_id
                WHERE email = :email
            ");
            $update->execute($fields);
            echo "Updated user: {$email}\n";
        } else {
            // Insert new user
            $columns = implode(', ', array_keys($fields));
            $placeholders = ':' . implode(', :', array_keys($fields));

            $insert = $pdo->prepare("INSERT INTO users ($columns) VALUES ($placeholders)");
            $insert->execute($fields);
            echo "Inserted user: {$email}\n";
        }
    }
} else {
    echo "Failed to fetch or decode data from API.\n";
}
