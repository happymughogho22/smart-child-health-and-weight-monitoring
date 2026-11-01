# Smart Child Health and Weight Monitoring System

## 1. Project Overview

The **Smart Child Health and Weight Monitoring System** is a web-based application developed to support the monitoring of children's health, physical growth and nutritional status.

The system provides a centralised platform for registering children, recording health measurements, assessing growth indicators, visualising growth trends and generating health reports. It incorporates WHO child growth reference standards to support the assessment of children's growth and identify possible nutritional concerns that may require further attention.

The application is developed using the Laravel framework and MySQL database management system.

## 2. Background

Child growth monitoring is an important component of primary healthcare because it helps identify growth abnormalities and possible nutritional problems at an early stage.

Traditional growth-monitoring approaches may involve manual recording, calculations and paper-based record management. These approaches can make it difficult to maintain consistent records, monitor changes over time and generate comprehensive reports.

This project addresses these challenges by introducing a centralised digital platform that facilitates child health record management, growth assessment and visual presentation of measurement trends.

## 3. Problem Statement

Inadequate monitoring and documentation of children's growth can contribute to delays in identifying potential nutritional and growth-related problems.

Manual record-keeping may also result in difficulties retrieving historical measurements, tracking individual growth patterns and producing timely reports.

There is therefore a need for a digital system that improves the organisation, assessment and presentation of child health and growth information.

## 4. Project Aim

To design and develop a web-based child health and weight monitoring system that facilitates the recording, assessment and visualisation of children's growth information to support early identification of potential nutritional concerns.

## 5. Specific Objectives

The project seeks to:

1. Develop a digital platform for registering and managing children's information.
2. Provide functionality for recording children's weight, height, MUAC and other relevant health measurements.
3. Implement growth assessment using WHO child growth reference standards.
4. Visualise children's growth trends through graphical representations.
5. Generate health and nutritional reports to support monitoring and decision-making.
6. Provide secure user authentication and role-based access to system functionalities.

## 6. Key System Features

### Child Management
- Register new children.
- View registered children's information.
- Access individual child profiles.
- Manage child records.

### Health and Weight Monitoring
- Record children's weight measurements.
- Record height and length measurements.
- Capture MUAC and other relevant health indicators.
- Maintain historical health records.

### Growth Assessment
- Assess children's growth using WHO growth reference standards.
- Calculate relevant growth indicators and Z-scores.
- Identify potential growth and nutritional concerns.
- Provide assessment remarks and recommendations.

### Growth Visualisation
- Display graphical representations of growth trends.
- Monitor changes in weight and other measurements.
- Support the review of historical growth information.

### Reporting
- Generate child health reports.
- Generate nutritional reports.
- View report information.
- Support sharing of reports through email.

### User and Access Management
- User authentication.
- User registration.
- Administrative user management.
- Role-based access control.

## 7. Technologies Used

| Technology | Purpose |
|---|---|
| PHP | Backend programming language |
| Laravel 12 | Web application framework |
| MySQL | Relational database management |
| HTML5 | Web page structure |
| CSS3 | Interface styling |
| JavaScript | Client-side functionality |
| Blade | Laravel templating engine |
| Composer | PHP dependency management |
| Node.js and NPM | Frontend asset management |
| Git and GitHub | Version control and source code hosting |
| XAMPP | Local development environment |

## 8. System Architecture

The application follows a client-server architecture.

The main components include:

1. **Presentation Layer:** Provides the user interface through which authorised users interact with the application.
2. **Application Layer:** Handles business logic, authentication, growth assessment, record processing and report generation.
3. **Database Layer:** Stores child information, health records, user accounts, growth assessments and WHO growth reference data.

### General System Workflow

User → Web Interface → Laravel Application → Business Logic and Growth Assessment → MySQL Database → Results and Reports.

## 9. Database Overview

The application uses MySQL to manage its data.

The main database entities include:

- Users
- Children
- Health Records
- Weight Measurements
- Growth Assessments
- WHO Growth References

These entities support the management of child information, health measurements, growth assessments and reference data.

## 10. Installation and Setup

### Prerequisites

Before installing the application, ensure that the following are available:

- PHP 8.2 or later.
- Composer.
- MySQL.
- XAMPP or another compatible local server environment.
- Node.js and NPM.
- Git.

### Step 1: Clone the Repository

```bash
git clone https://github.com/happymughogho22/smart-child-health-and-weight-monitoring.git
```

Navigate into the project directory:

```bash
cd smart-child-health-and-weight-monitoring
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

### Step 3: Configure the Environment

Create your local environment file:

```bash
copy .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

### Step 4: Configure the Database

Create a separate MySQL database for the application through phpMyAdmin.

Update the database configuration in `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=
```

Replace `your_database_name` with the name of the database you created.

Do not use an existing database containing important data for a fresh installation.

### Step 5: Run Database Migrations

```bash
php artisan migrate
```

If the application requires WHO growth reference data, follow the project's reference-data import procedure.

### Step 6: Install Frontend Dependencies

```bash
npm install
```

Compile the frontend assets:

```bash
npm run build
```

### Step 7: Start the Application

```bash
php artisan serve
```

Open the application in your browser:

```text
http://127.0.0.1:8000
```

## 11. Security Considerations

The project incorporates security-related practices, including:

- User authentication.
- Role-based access control.
- Environment-based application configuration.
- Protection of sensitive configuration files.
- Server-side validation of submitted information.

The actual `.env` file and application credentials should never be committed to a public repository.

## 12. Future Enhancements

Potential future improvements include:

- Integration with an IoT-enabled digital weighing scale for automated weight acquisition.
- Real-time transmission of measurement data.
- Automated notifications for concerning growth patterns.
- Expanded reporting and analytics.
- Deployment to a secure cloud-hosting environment.
- Integration with additional healthcare information systems.

## 13. Project Information

**Project Title:** Smart Child Health and Weight Monitoring System

**Project Type:** Academic Final-Year Project

**Development Framework:** Laravel 12

**Database Management System:** MySQL

**Repository:** https://github.com/happymughogho22/smart-child-health-and-weight-monitoring

## 14. Author

**Developed by:** Happy Mughogho

**Institution:** Malawi University of Business and Applied Sciences (MUBAS)

**Programme:** Bachelor of Science in Information Technology

---

*This project is developed for academic purposes and aims to demonstrate the application of information technology in supporting child health monitoring and growth assessment.*