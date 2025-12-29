# Rabin-Ticket Project Index

## Project Overview

**Rabin-Ticket** is a comprehensive ticket management system (سامانه تیکت) built for Rahbarian Industrial Holding (گروه صنعتی رهباریان). The system supports Persian/Farsi language with RTL layout and includes both a web application and a REST API.

## Technology Stack

- **Backend**: PHP (Traditional + Modern PDO)
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript (jQuery, Bootstrap)
- **Calendar**: Persian (Jalali) calendar support
- **Architecture**: MVC pattern (for API), traditional PHP (for web app)

---

## Project Structure

### Root Directory

```
Rabin-Ticket/
├── api/                    # REST API v1
├── assets/                 # Static assets (CSS, JS, images, fonts)
├── files/                  # Uploaded files storage
├── inf/                    # Infrastructure/utility files
├── page/                   # Web application pages
├── sal/                    # Date/calendar utilities
├── ticket/                 # Alternative ticket interface (duplicate?)
├── index.php              # Main entry point
├── login.php              # Login page
├── ticket-schema.sql      # Database schema
└── ticket-with-data.sql  # Database with sample data
```

---

## API Structure (`/api/v1/`)

### Architecture

- **Pattern**: MVC (Model-View-Controller)
- **Authentication**: API Key based (X-API-Key header)
- **Rate Limiting**: 6000 requests per 6000 seconds
- **Documentation**: OpenAPI 3.0 (Swagger)

### Directory Structure

```
api/v1/
├── config/
│   ├── config.php         # API configuration, API keys, constants
│   ├── database.php       # Database connection class (PDO)
│   └── jdf.php            # Jalali date functions
├── controllers/
│   └── TicketController.php  # Handles HTTP requests
├── models/
│   └── Ticket.php         # Database operations
├── middleware/
│   └── AuthMiddleware.php # Authentication & rate limiting
├── helpers/
│   └── Response.php       # Standardized JSON responses
├── index.php              # API router/entry point
└── swagger.json           # OpenAPI documentation
```

### API Endpoints

#### Base URL

- Production: `https://request-r.ir/api/v1`
- Development: `http://localhost/api/v1`

#### Endpoints

1. **GET /tickets** - List all tickets
2. **GET /tickets/today** - List today's tickets (Persian calendar)
3. **GET /tickets/{code}** - Get single ticket details
4. **GET /tickets/{code}/responses** - Get ticket responses
5. **GET /tickets/{code}/files** - Get ticket file attachments
6. **GET /health** - Health check endpoint

#### Special Features

- **BPMS Integration**: Special filtering for BPMS API key users
- **User Code Filtering**: BPMS users see only tickets with specific user codes
- **First Refer Tracking**: Tracks first response from BPMS users

---

## Web Application Structure

### Main Entry Points

- **`index.php`** - Main dashboard (requires authentication)
- **`login.php`** - User login page
- **`uiindex.php`** - UI router (includes page based on `?page=` parameter)
- **`uxindex.php`** - UX router (handles actions)
- **`uimodal.php`** - Modal content router

### Page Structure (`/page/`)

#### UI Pages (`/page/ui/`)

User interface pages (view-only):

- **`asli.php`** - Main dashboard
- **`list_ticket.php`** - Ticket list view
- **`info_ticket.php`** - Ticket details view
- **`my_work.php`** - User's work/tasks
- **`history_ticket.php`** - Ticket history
- **`wait_page.php`** - Waiting page
- **`list_pasokh_no.php`** - Unread responses
- **`set_priority.php`** - Set ticket priority
- **`view_priority.php`** - View priorities
- **`list_working_on.php`** - Tickets on desk
- **`gozareshat.php`** - Reports
- **`setting.php`** - Settings
- **`list_mohtava.php`** - Educational content list
- **`new_mohtava.php`** - New educational content
- **`info_mohtava.php`** - Educational content details
- **`profile.php`** - User profile
- **`start_ticket.php`** - Create new ticket
- **`restricted.php`** - Access denied page

#### UX Actions (`/page/ux/`)

Action handlers (process forms, updates):

- **`checklogin.php`** - Login authentication
- **`s_new_ticket.php`** - Create ticket
- **`s_new_pasokh.php`** - Add response
- **`anjam_ticket.php`** - Complete ticket
- **`end_ticket.php`** - Close ticket
- **`erja_ticket.php`** - Assign ticket
- **`hazf_ticket.php`** - Delete ticket
- **`set_working_on.php`** - Set working status
- **`update_priority.php`** - Update priority
- **`change_category.php`** - Change category
- **`s_new_karkerd.php`** - New work log
- **`hazf_karkerd.php`** - Delete work log
- **`s_new_mohtava.php`** - Save educational content
- **`s_new_cat.php`** - New category
- **`s_new_daste.php`** - New department
- **`s_new_sherkat.php`** - New company
- **`s_new_poshtiban.php`** - New support user
- **`s_update_avatar.php`** - Update avatar
- **`exit.php`** - Logout

---

## Database Schema

### Main Tables

#### `ticket`

Main ticket table with fields:

- `code` - Unique ticket identifier
- `titr` - Title
- `matn` - Description/content
- `olaviat` - Priority (1-4)
- `vaziat` - Status (a/m/b/k/c/t)
- `code_p_karbar` - Requester user code
- `code_p_karbar_anjam` - Handler user code
- `daste` - Department ID
- `name_daste` - Department name
- `code_sherkat` - Company code
- `name_sherkat` - Company name
- `tarikh_sabt` - Creation date (Persian)
- `saat_sabt` - Creation time
- `priority_status` - Priority enabled (y/n)
- `priority_order` - Priority order number

#### `pasokh`

Ticket responses/replies:

- `code` - Response code
- `code_ticket` - Ticket code (FK)
- `code_karbar_sabt` - Author user code
- `name_karbar_sabt` - Author name
- `code_karbar2` - Target user code (for mentions)
- `matn` - Response content
- `tarikh_sabt` - Date (Persian)
- `saat_sabt` - Time
- `oksee` - Read status (y/n)
- `tarikh_see` - Read date
- `saat_see` - Read time

#### `karbar`

User accounts:

- `code_p` - User code (primary identifier)
- `name` - Full name
- `semat` - Position/title
- `code_karbar` - Employee code
- `tel` - Phone
- `email` - Email
- `pass` - Password (hashed)
- `name_sherkat` - Company name
- `code_sherkat` - Company code
- `kind_daste` - Department access
- `avatar` - Avatar filename
- `let` - Permissions (comma-separated)
- `vaziat` - Status (active/inactive)
- `gozaresh` - Report access (y/n)

#### `file_pasokh`

File attachments:

- `code_ticket` - Ticket code (FK)
- `code_pasokh` - Response code (FK)
- `code_file` - File identifier
- `titr` - File title
- `kind` - File extension
- `hajm` - File size
- `vaziat` - Status

#### `karkerd`

Work logs/timesheets:

- `code_p` - User code
- `name_karbar` - User name
- `tarikh_s` - Start date
- `saat_s` - Start time
- `tarikh_e` - End date
- `saat_e` - End time
- `daste` - Department
- `matn` - Description
- `zaman` - Duration
- `code` - Ticket code (FK)
- `vaziat` - Status

#### `mohtava`

Educational content:

- `titr` - Title
- `kind` - Type
- `link` - URL
- `name_file` - Filename
- `sherkat` - Company
- `daste` - Department
- `cat1`, `cat2` - Categories
- `matn` - Content
- `vaziat` - Status

#### Other Tables

- `departman` - Departments
- `sherkatha` - Companies
- `daste_mohtava` - Content categories

---

## Status & Priority Codes

### Ticket Status (`vaziat`)

- **`a`** - ثبت اولیه (Initial registration)
- **`m`** - درحال بررسی (Under review)
- **`w`** - روی میز (On desk)
- **`b`** - بسته شده (Closed)
- **`k`** - انجام شد (Completed)
- **`t`** - بررسی مجدد (Re-review)
- **`c`** - کنسل شده (Cancelled)

### Priority Levels (`olaviat`)

- **`1`** - ضروری (Urgent)
- **`2`** - متوسط (Medium)
- **`3`** - معمولی (Normal)
- **`4`** - پایین (Low)

---

## Key Features

### Authentication & Authorization

- Session-based authentication for web app
- API key authentication for API
- Role-based access control
- Special view codes for privileged users
- Department-based permissions

### Notifications

- Real-time unread message count
- Notification dropdown with ticket previews
- Status and priority badges
- Message preview (80 chars)
- Read/unread status tracking

### File Management

- File attachments for tickets
- File storage in `/files/peyvast/`
- File metadata tracking
- Download URLs via API

### Persian Calendar Support

- Jalali (Persian) date functions
- Date conversion utilities
- RTL layout support
- Persian font support (Vazirmatn, IranYekan)

### Priority System

- Manual priority setting
- Priority ordering
- Priority status tracking
- Visual priority indicators

---

## Configuration Files

### `/inf/`

- **`f1.php`** - Main initialization (session, includes, date setup)
- **`configh.php`** - Database connection (mysqli)
- **`d.php`** - Helper functions
- **`date.php`** - Date utilities
- **`jdf.php`** - Jalali date functions
- **`s_email.php`** - Email sending
- **`s_sms.php`** - SMS sending

### API Config (`/api/v1/config/`)

- **`config.php`** - API keys, constants, labels
- **`database.php`** - PDO database class
- **`jdf.php`** - Jalali date functions

---

## Assets Structure

### `/assets/`

- **`css/`** - Stylesheets (main.min.css, theme.css)
- **`js/`** - JavaScript files
  - `jquery.min.js`
  - `bootstrap.bundle.min.js`
  - `custom.js`
  - `uijindex.js` - UI JavaScript
  - `uxjindex.js` - UX JavaScript
  - `validations.js`
- **`fonts/`** - Font files (Bootstrap icons, Vazirmatn, IranYekan)
- **`images/`** - Images (logos, avatars, icons)
- **`vendor/`** - Third-party libraries
  - `apex/` - Charts
  - `calendar/` - Calendar widget
  - `daterange/` - Date range picker
  - `toastify/` - Toast notifications
  - `overlay-scroll/` - Custom scrollbars
  - `clockpicker/` - Time picker

---

## Development Notes

### Database Connection

- **Web App**: Uses `mysqli` via `/inf/configh.php`
- **API**: Uses `PDO` via `/api/v1/config/database.php`
- Both connect to same database: `requestr_rahbarian`

### Session Management

- Sessions started in `inf/f1.php`
- Session variables:
  - `ok_login_user_i` - Login status
  - `code_p` - User code
  - `name` - User name
  - `semat` - User position
  - `avatar` - Avatar filename
  - `let` - Permissions
  - `mojavez` - Authorization token

### Routing

- **Web App**: Query parameter based (`?page=...`)
- **API**: Path-based (`/api/v1/tickets/...`)

### Security

- SQL injection prevention (prepared statements in API, mysqli_real_escape_string in web app)
- API key authentication
- Rate limiting
- CORS headers configured
- Session-based CSRF protection (implicit)

---

## Special User Codes

Special view codes (hardcoded in `index.php`):

- `24277`, `25662`, `20612`, `23056`, `22695`, `20072`, `1100105`, `1100056`, `1064046037`
- These users can see "Tickets on Desk" (`list_working_on`)

### BPMS User Codes (API)

- `1100113`, `26519`, `1100064`, `1100119`, `25662`, `1100116`, `1100100`
- Used for filtering tickets in BPMS API responses

---

## File Uploads

- **Path**: `/files/peyvast/`
- **Naming**: Uses file codes (e.g., `F-1234567890-12.jpg`)
- **Storage**: File metadata in `file_pasokh` table

---

## API Response Format

### Success Response

```json
{
  "status": "success",
  "message": "Tickets retrieved successfully",
  "timestamp": "2024-01-01 12:00:00",
  "data": [...]
}
```

### Error Response

```json
{
  "status": "error",
  "message": "Error description",
  "timestamp": "2024-01-01 12:00:00",
  "details": "Additional details"
}
```

---

## Next Steps for Development

1. **Code Modernization**

   - Migrate web app from mysqli to PDO
   - Implement proper MVC for web app
   - Add Composer for dependency management

2. **Security Enhancements**

   - Implement CSRF tokens
   - Add password hashing (bcrypt/argon2)
   - Implement proper session management
   - Add input validation layer

3. **API Improvements**

   - Add POST/PUT/DELETE endpoints
   - Implement pagination
   - Add filtering and sorting
   - Add webhooks

4. **Frontend Modernization**

   - Consider React/Vue.js migration
   - Implement real-time updates (WebSockets)
   - Add PWA support
   - Improve mobile responsiveness

5. **Testing**

   - Add unit tests
   - Add integration tests
   - Add API tests

6. **Documentation**
   - API documentation (Swagger UI)
   - User manual
   - Developer guide
   - Database ERD

---

## Environment Setup

### Requirements

- PHP 7.4+ (8.4 recommended)
- MySQL/MariaDB 10.6+
- Apache/Nginx web server
- mod_rewrite enabled (for API)

### Database Setup

1. Import `ticket-schema.sql` for structure
2. Import `ticket-with-data.sql` for sample data
3. Update database credentials in:
   - `/inf/configh.php`
   - `/api/v1/config/database.php`

### Configuration

- Update `BASE_URL` in `/api/v1/config/config.php`
- Configure API keys in `/api/v1/config/config.php`
- Set timezone to `Asia/Tehran`

---

## Contact & Support

- **Design & Development**: www.okteam.ir
- **Company**: Rahbarian Industrial Holding (گروه صنعتی رهباریان)
- **Domain**: request-r.ir

---

_Last Updated: 2025-01-XX_
_Project Index Version: 1.0_
