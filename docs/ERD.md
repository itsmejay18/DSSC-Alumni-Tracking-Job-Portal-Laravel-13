# Alumni Tracking & Job Portal ERD

## Text-Based ERD

```
users
  id PK
  role, is_active, is_approved, email_verified_at
  last_login_at, last_login_ip, profile_photo_path
  -> hasOne alumni_profiles
  -> hasOne employers
  -> hasMany job_applications (as alumni_id)
  -> hasMany job_matches (as alumni_id)
  -> hasMany employment_tracking (as alumni_id)
  -> hasMany activity_logs
  -> hasMany reports (as generated_by)
  -> hasMany notifications

colleges
  id PK
  -> hasMany courses

courses
  id PK
  college_id FK -> colleges.id
  -> hasMany alumni_profiles

alumni_profiles
  id PK
  user_id FK -> users.id
  course_id FK -> courses.id

industries
  id PK
  -> hasMany employers

employers
  id PK
  user_id FK -> users.id
  industry_id FK -> industries.id
  verified_by FK -> users.id
  -> hasMany jobs

job_categories
  id PK
  -> hasMany jobs

jobs
  id PK
  employer_id FK -> employers.id
  job_category_id FK -> job_categories.id
  approved_by FK -> users.id
  -> hasMany job_applications
  -> hasMany job_matches

job_applications
  id PK
  job_id FK -> jobs.id
  alumni_id FK -> users.id
  status_updated_by FK -> users.id
  UNIQUE(job_id, alumni_id)

job_matches
  id PK
  job_id FK -> jobs.id
  alumni_id FK -> users.id
  UNIQUE(job_id, alumni_id)

employment_tracking
  id PK
  alumni_id FK -> users.id
  changed_by FK -> users.id

reports
  id PK
  generated_by FK -> users.id

notifications
  id PK
  user_id FK -> users.id

activity_logs
  id PK
  user_id FK -> users.id

settings
  id PK
```

## Mermaid

```mermaid
erDiagram
    USERS ||--o| ALUMNI_PROFILES : has_one
    USERS ||--o| EMPLOYERS : has_one
    USERS ||--o{ JOB_APPLICATIONS : submits
    USERS ||--o{ JOB_MATCHES : receives
    USERS ||--o{ EMPLOYMENT_TRACKING : tracks
    USERS ||--o{ ACTIVITY_LOGS : creates
    USERS ||--o{ REPORTS : generates
    USERS ||--o{ NOTIFICATIONS : receives

    COLLEGES ||--o{ COURSES : offers
    COURSES ||--o{ ALUMNI_PROFILES : belongs_to
    INDUSTRIES ||--o{ EMPLOYERS : groups
    JOB_CATEGORIES ||--o{ JOBS : classifies
    EMPLOYERS ||--o{ JOBS : posts
    JOBS ||--o{ JOB_APPLICATIONS : receives
    JOBS ||--o{ JOB_MATCHES : matches

    USERS {
        bigint id PK
        string name
        string email
        string role
        boolean is_active
        boolean is_approved
        timestamp email_verified_at
        timestamp last_login_at
        string last_login_ip
        string profile_photo_path
        timestamps timestamps
        timestamp deleted_at
    }

    ALUMNI_PROFILES {
        bigint id PK
        bigint user_id FK
        string student_id
        string first_name
        string last_name
        string middle_name
        date birth_date
        string gender
        string contact_number
        bigint course_id FK
        int year_graduated
        date graduation_date
        json skills
        string employment_status
        string current_job_title
        string current_company
        decimal current_salary
        string linkedin_url
        string portfolio_url
        boolean is_verified
        timestamp verification_date
        timestamps timestamps
        timestamp deleted_at
    }

    COLLEGES {
        bigint id PK
        string college_name
        string college_code
        string dean_name
        string dean_email
        boolean is_active
        timestamps timestamps
    }

    COURSES {
        bigint id PK
        bigint college_id FK
        string course_code
        string course_name
        int duration
        boolean is_active
        timestamps timestamps
    }

    INDUSTRIES {
        bigint id PK
        string industry_name
        string industry_code
        boolean is_active
        timestamps timestamps
    }

    EMPLOYERS {
        bigint id PK
        bigint user_id FK
        string company_name
        string company_registration_number
        string company_logo_path
        bigint industry_id FK
        string company_size
        string website
        string phone
        string address_line1
        string address_line2
        string city
        string province
        string country
        string postal_code
        json verification_documents
        boolean is_verified
        timestamp verification_date
        bigint verified_by FK
        timestamps timestamps
        timestamp deleted_at
    }

    JOB_CATEGORIES {
        bigint id PK
        string category_name
        string category_code
        text description
        boolean is_active
        timestamps timestamps
    }

    JOBS {
        bigint id PK
        bigint employer_id FK
        bigint job_category_id FK
        string title
        string slug
        text description
        text requirements
        text responsibilities
        text qualifications
        decimal salary_min
        decimal salary_max
        string salary_type
        string location
        boolean is_remote
        string job_type
        string experience_level
        string education_requirement
        json skills_required
        date application_deadline
        int max_applicants
        string status
        bigint approved_by FK
        timestamp approved_at
        int views_count
        int applications_count
        boolean is_featured
        timestamps timestamps
        timestamp deleted_at
    }

    JOB_APPLICATIONS {
        bigint id PK
        bigint job_id FK
        bigint alumni_id FK
        text cover_letter
        string resume_path
        string portfolio_link
        date availability_date
        decimal expected_salary
        string status
        bigint status_updated_by FK
        text status_notes
        timestamp reviewed_at
        timestamps timestamps
    }

    JOB_MATCHES {
        bigint id PK
        bigint job_id FK
        bigint alumni_id FK
        decimal match_score
        json match_reasons
        boolean is_viewed
        boolean is_applied
        timestamp created_at
    }

    EMPLOYMENT_TRACKING {
        bigint id PK
        bigint alumni_id FK
        string previous_status
        string new_status
        string previous_company
        string new_company
        string previous_title
        string new_title
        bigint changed_by FK
        timestamp change_date
        text notes
    }

    REPORTS {
        bigint id PK
        string report_type
        bigint generated_by FK
        json parameters
        json result_data
        string file_path
        string format
        timestamp generated_at
        timestamps timestamps
    }

    NOTIFICATIONS {
        bigint id PK
        bigint user_id FK
        string type
        string title
        text message
        json data
        boolean is_read
        timestamp sent_at
        boolean email_sent
        timestamps timestamps
    }

    ACTIVITY_LOGS {
        bigint id PK
        bigint user_id FK
        string action
        string module
        text description
        string ip_address
        text user_agent
        json old_data
        json new_data
        timestamp created_at
    }

    SETTINGS {
        bigint id PK
        string setting_key
        text setting_value
        string setting_type
        string group
        text description
        boolean is_public
        timestamps timestamps
    }
```
