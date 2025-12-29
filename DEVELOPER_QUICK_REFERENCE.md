# Developer Quick Reference Guide

## Quick Navigation

### Adding a New Page

1. **Create UI file**: `page/ui/your_page.php`
2. **Add route in `uiindex.php`**:
   ```php
   if($page=="your_page")include('page/ui/your_page.php');
   ```
3. **Add menu item in `index.php`** (if needed):
   ```php
   <li class="<?php echo ($page == 'your_page') ? 'current-page' : ''; ?>">
       <a href="?page=your_page">
           <i class="bi bi-icon-name"></i>
           <span class="menu-text">Your Page Name</span>
       </a>
   </li>
   ```

### Adding a New Action Handler

1. **Create UX file**: `page/ux/your_action.php`
2. **Add route in `uxindex.php`**:
   ```php
   if($page=="your_action")include('page/ux/your_action.php');
   ```
3. **Call from form/JavaScript**:
   ```html
   <form action="?page=your_action" method="post">
   ```

---

## Common Code Patterns

### Database Query (Web App - mysqli)
```php
// Get from session
$code_p = $_SESSION['code_p'];

// Escape input
$code_p_escaped = mysqli_real_escape_string($Link, $code_p);

// Query
$query = "SELECT * FROM ticket WHERE code_p_karbar = '$code_p_escaped'";
$result = mysqli_query($Link, $query);

// Fetch
while($row = mysqli_fetch_assoc($result)) {
    // Process row
}
```

### Database Query (API - PDO)
```php
// Prepared statement
$sql = "SELECT * FROM ticket WHERE code_p_karbar = :code_p";
$stmt = $this->db->prepare($sql);
$stmt->bindParam(':code_p', $code_p, PDO::PARAM_STR);
$stmt->execute();

// Fetch
$tickets = $stmt->fetchAll();
```

### Get Current User Info
```php
$code_p = $_SESSION['code_p'];        // User code
$name = $_SESSION['name'];             // User name
$semat = $_SESSION['semat'];          // Position
$avatar = $_SESSION['avatar'];        // Avatar filename
```

### Persian Date Functions
```php
// Get current Persian date
$tarikh = jdate('Ymd');              // Format: 14030721
$saat = jdate('H:i');                 // Format: 14:30
$zaman = $tarikh . " - " . $saat;    // Combined

// Convert Gregorian to Jalali
$jalali = jdate('Y/m/d', strtotime('2024-01-01'));

// Get today in different formats
$today_ymd = jdate('Ymd');           // 14030721
$today_formatted = jdate('Y/m/d');   // 1403/07/21
```

### Check User Permissions
```php
// Get permissions from session
$let = $_SESSION['let'];  // Comma-separated list

// Check if user has access
$permissions = explode(',', $let);
if (in_array('desired_permission', $permissions)) {
    // User has access
}

// Special view codes
$special_view_codes = ["24277", "25662", "20612", "23056", "22695", "20072", "1100105", "1100056"];
if (in_array($_SESSION['code_p'], $special_view_codes)) {
    // Special access
}
```

### Ticket Status Codes
```php
$status_labels = [
    'a' => 'ثبت اولیه',      // Initial
    'm' => 'درحال بررسی',    // Under review
    'w' => 'روی میز',        // On desk
    'b' => 'بسته شده',       // Closed
    'k' => 'انجام شد',       // Completed
    't' => 'بررسی مجدد',     // Re-review
    'c' => 'کنسل شده'        // Cancelled
];
```

### Priority Codes
```php
$priority_labels = [
    '1' => 'ضروری',    // Urgent
    '2' => 'متوسط',    // Medium
    '3' => 'معمولی',   // Normal
    '4' => 'پایین'     // Low
];
```

### AJAX Request Pattern
```php
// In uxindex.php or action handler
$isAjaxRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                 strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($isAjaxRequest) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => true,
        'message' => 'Operation successful'
    ]);
    exit;
}
```

### File Upload Pattern
```php
// File upload handling
if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $code_file = 'F-' . time() . '-' . rand(100, 999);
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = $code_file . '.' . $extension;
    $upload_path = 'files/peyvast/' . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        // Save to database
        $sql = "INSERT INTO file_pasokh (code_ticket, code_file, titr, kind, hajm, vaziat) 
                VALUES ('$ticket_code', '$code_file', '$title', '$extension', '$size', 'y')";
        mysqli_query($Link, $sql);
    }
}
```

### Response Format (API)
```php
// Success response
Response::success($data, 'Operation successful');

// Error response
Response::error('Error message', 400);

// Not found
Response::notFound('Resource not found');

// Server error
Response::serverError('Internal server error');
```

---

## File Paths Reference

### Static Assets
- **CSS**: `assets/css/main.min.css`
- **JS**: `assets/js/custom.js`
- **Images**: `assets/images/`
- **Fonts**: `assets/fonts/`

### Include Files
- **Main init**: `inf/f1.php`
- **Database (mysqli)**: `inf/configh.php`
- **Database (PDO)**: `api/v1/config/database.php`
- **Date functions**: `inf/jdf.php`
- **Helpers**: `inf/d.php`

### Upload Directories
- **Files**: `files/peyvast/`
- **User avatars**: `assets/images/` (named as `{avatar}.png`)

---

## Session Variables

| Variable | Description |
|----------|-------------|
| `ok_login_user_i` | Login status ('y' or 'n') |
| `code_p` | User code (primary identifier) |
| `name` | User full name |
| `semat` | User position/title |
| `avatar` | Avatar filename (without extension) |
| `let` | Permissions (comma-separated) |
| `mojavez` | Authorization token |

---

## Database Connection

### Web App (mysqli)
```php
// Already available via inf/f1.php
// Connection variable: $Link

// Example query
$query = "SELECT * FROM ticket";
$result = mysqli_query($Link, $query);
```

### API (PDO)
```php
// In API files
$database = new Database();
$db = $database->connect();

// Example query
$stmt = $db->prepare("SELECT * FROM ticket");
$stmt->execute();
```

---

## Common Helper Functions

### Get Request Parameters
```php
// From inf/f1.php
$page = str_g('page');        // GET parameter
$code = str_g('code');
$p = str_p('p');              // POST parameter
```

### Date Functions
```php
// Current Persian date/time
$tarikh = jdate('Ymd');
$saat = jdate('H:i');

// Convert Gregorian to Jalali
$jalali = jdate('Y/m/d', $timestamp);
```

---

## API Development

### Adding New Endpoint

1. **Add route in `api/v1/index.php`**:
   ```php
   case $path === 'your-endpoint':
       $ticketController->yourMethod();
       break;
   ```

2. **Add method in `TicketController.php`**:
   ```php
   public function yourMethod()
   {
       try {
           $data = $this->ticketModel->yourModelMethod();
           Response::success($data, 'Success message');
       } catch (Exception $e) {
           Response::serverError('Error: ' . $e->getMessage());
       }
   }
   ```

3. **Add model method in `Ticket.php`**:
   ```php
   public function yourModelMethod()
   {
       $sql = "SELECT ...";
       $stmt = $this->db->prepare($sql);
       $stmt->execute();
       return $stmt->fetchAll();
   }
   ```

### API Authentication
```php
// API key is automatically validated by AuthMiddleware
// Access API key in model:
$this->apiKey  // Available in Ticket model

// Check if BPMS API key
$isBpms = ($this->apiKey === BPMS_API_KEY);
```

---

## Frontend JavaScript Patterns

### Load Page via AJAX
```javascript
// Using uijindex.js function
openpage('target_div_id', 'page_name', 'param2', 'param3', 'param4', 'param5');
```

### Form Submission
```html
<!-- Standard form -->
<form action="?page=your_action" method="post">
    <input type="text" name="field_name">
    <button type="submit">Submit</button>
</form>

<!-- AJAX form -->
<form id="myForm" onsubmit="submitForm(event)">
    <!-- fields -->
</form>

<script>
function submitForm(e) {
    e.preventDefault();
    // AJAX submission
}
</script>
```

---

## Testing Checklist

### Before Committing
- [ ] Test on Persian date system
- [ ] Test RTL layout
- [ ] Test file uploads
- [ ] Test API endpoints (if modified)
- [ ] Check SQL injection prevention
- [ ] Verify session handling
- [ ] Test permission checks
- [ ] Test on mobile devices

### Common Issues
- **Date mismatch**: Ensure using `jdate()` not `date()`
- **RTL issues**: Check `dir="rtl"` in HTML
- **Session expired**: Check `ok_login_user_i` session variable
- **Permission denied**: Check `let` session variable
- **File not found**: Check file paths (use relative paths)

---

## Debugging Tips

### Enable Error Display
```php
// In development only
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Check Session
```php
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
```

### Log Errors
```php
error_log('Debug: ' . print_r($variable, true));
```

### Database Query Debug
```php
// mysqli
echo mysqli_error($Link);

// PDO
echo $stmt->errorInfo();
```

---

## Git Workflow

### Branch Naming
- `feature/your-feature-name`
- `bugfix/your-bugfix-name`
- `api/your-api-feature`

### Commit Messages
- Use Persian or English (be consistent)
- Format: `[Type] Description`
- Types: `feat`, `fix`, `docs`, `refactor`, `api`

---

## Performance Tips

1. **Use indexes**: Check database indexes on frequently queried columns
2. **Limit queries**: Use `LIMIT` in list queries
3. **Cache**: Consider caching for frequently accessed data
4. **Optimize images**: Compress images in `assets/images/`
5. **Minify assets**: Use minified CSS/JS files

---

## Security Best Practices

1. **Always escape input**: Use `mysqli_real_escape_string()` or prepared statements
2. **Validate input**: Check data types and ranges
3. **Check permissions**: Verify user has access before operations
4. **Use HTTPS**: In production, enforce HTTPS
5. **Sanitize output**: Use `htmlspecialchars()` when displaying user input
6. **Session security**: Regenerate session ID on login

---

*Last Updated: 2025-01-XX*

