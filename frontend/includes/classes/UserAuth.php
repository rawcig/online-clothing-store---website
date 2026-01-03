<?php
class UserAuth {
    private $conn;
    
    public function __construct($database_connection) {
        $this->conn = $database_connection;
    }
    
    /**
     * Register a new user
     * @param string $username
     * @param string $email
     * @param string $password
     * @param string $first_name
     * @param string $last_name
     * @return array Result with success status and message
     */
    public function register($username, $email, $password, $first_name, $last_name) {
        // Validate input
        if (empty($username) || empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Username, email, and password are required'];
        }
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format'];
        }
        
        // Check if user already exists
        if ($this->userExists($email, $username)) {
            return ['success' => false, 'message' => 'User with this email or username already exists'];
        }
        
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            // Try inserting with first_name and last_name - store plain text password to match existing db
            $stmt = $this->conn->prepare("INSERT INTO users (username, email, password, first_name, last_name) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $email, $password, $first_name, $last_name);
            
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Registration successful', 'user_id' => $this->conn->insert_id];
            }
        } catch (mysqli_sql_exception $e) {
            // If there's an error about first_name or last_name columns, try inserting without them
            if (strpos($e->getMessage(), 'first_name') !== false || strpos($e->getMessage(), 'last_name') !== false) {
                try {
                    // Try inserting without first_name and last_name - store plain text password to match existing db
                    $stmt_alt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                    $stmt_alt->bind_param("sss", $username, $email, $password);
                    
                    if ($stmt_alt->execute()) {
                        return ['success' => true, 'message' => 'Registration successful', 'user_id' => $this->conn->insert_id];
                    } else {
                        return ['success' => false, 'message' => 'Registration failed: ' . $this->conn->error];
                    }
                } catch (mysqli_sql_exception $e2) {
                    return ['success' => false, 'message' => 'Registration failed: ' . $e2->getMessage()];
                }
            } else {
                // Some other error occurred
                return ['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()];
            }
        }
    }
    
    /**
     * Login user
     * @param string $email
     * @param string $password
     * @return array Result with success status and message
     */
    public function login($email, $password) {
        // Validate input
        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Email and password are required'];
        }
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format'];
        }
        
        try {
            // Get user from database
            $stmt = $this->conn->prepare("SELECT id, username, email, password FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                
                // Check password - try multiple methods since the db may have plain text passwords
                $isPasswordCorrect = false;
                
                // Check if it's a plain text match
                if ($user['password'] === $password) {
                    $isPasswordCorrect = true;
                }
                // Check if it's an MD5 hash
                else if ($user['password'] === md5($password)) {
                    $isPasswordCorrect = true;
                }
                // Check if it's a password_hash
                else if (password_verify($password, $user['password'])) {
                    $isPasswordCorrect = true;
                }
                
                if ($isPasswordCorrect) {
                    // Password is correct, set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    
                    // Password is correct
                    return [
                        'success' => true, 
                        'message' => 'Login successful'
                    ];
                } else {
                    // Password is incorrect
                    error_log("Login failed: Password verification failed for email: " . $email);
                    return ['success' => false, 'message' => 'Invalid email or password'];
                }
            } else {
                // User not found
                error_log("Login failed: User not found for email: " . $email);
                return ['success' => false, 'message' => 'Invalid email or password'];
            }
        } catch (mysqli_sql_exception $e) {
            error_log("Database error during login: " . $e->getMessage());
            return ['success' => false, 'message' => 'Database error occurred during login'];
        }
    }
    
    /**
     * Check if user exists
     * @param string $email
     * @param string $username
     * @return bool
     */
    private function userExists($email, $username) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
    
    /**
     * Logout user
     * @return void
     */
    public function logout() {
        // Unset all session variables
        $_SESSION = array();
        
        // Destroy the session
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
    
    /**
     * Check if user is logged in
     * @return bool
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Get current user ID
     * @return int|null
     */
    public function getCurrentUserId() {
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }
    
    /**
     * Get current user information
     * @return array|null
     */
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        try {
            // Try to get user with first_name and last_name
            $stmt = $this->conn->prepare("SELECT id, username, email, first_name, last_name FROM users WHERE id = ?");
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows == 1) {
                return $result->fetch_assoc();
            }
        } catch (mysqli_sql_exception $e) {
            // If first_name or last_name columns don't exist, try without them
            if (strpos($e->getMessage(), 'first_name') !== false || strpos($e->getMessage(), 'last_name') !== false) {
                try {
                    $stmt_fallback = $this->conn->prepare("SELECT id, username, email FROM users WHERE id = ?");
                    $stmt_fallback->bind_param("i", $_SESSION['user_id']);
                    $stmt_fallback->execute();
                    $result_fallback = $stmt_fallback->get_result();
                    
                    if ($result_fallback->num_rows == 1) {
                        return $result_fallback->fetch_assoc();
                    }
                } catch (mysqli_sql_exception $e2) {
                    // Log error but don't return yet, just continue to return null
                    error_log("Error in getCurrentUser fallback: " . $e2->getMessage());
                }
            }
        }
        
        return null;
    }
}
?>