# CSV Import Guide

The Employee Management System supports bulk importing employees via CSV files.

## Features

- **File Upload**: Drag-and-drop or click to upload CSV files (max 5MB)
- **Preview**: Review imported data before confirming the import
- **Validation**: Row-by-row validation with detailed error reporting
- **Activity Logging**: All imported employees are logged in the Activity Log
- **Error Handling**: Invalid rows are skipped with detailed error messages

## CSV Format

Your CSV file must contain the following headers (case-insensitive):

```
first_name,last_name,email,phone,department_id,salary,joining_date,address
```

### Column Requirements

| Column | Type | Required | Validation |
|--------|------|----------|-----------|
| first_name | String | Yes | 2+ chars, letters/spaces/apostrophes only |
| last_name | String | Yes | 2+ chars, letters/spaces/apostrophes only |
| email | String | Yes | Valid email format, must be unique |
| phone | String | No | 10-20 chars, alphanumeric + symbols |
| department_id | Integer | Yes | Must exist in departments table |
| salary | Decimal | Yes | 0.00 - 999999.99 |
| joining_date | Date | Yes | YYYY-MM-DD format, cannot be future date |
| address | String | No | 5-500 characters |

## Example CSV

```csv
first_name,last_name,email,phone,department_id,salary,joining_date,address
John,Smith,john.smith@example.com,+1-555-0101,1,75000.00,2024-01-15,123 Main St
Jane,Doe,jane.doe@example.com,+1-555-0102,2,80000.00,2024-02-20,456 Oak Ave
Michael,Johnson,michael.johnson@example.com,+1-555-0103,1,70000.00,2024-03-10,789 Pine Rd
```

## Import Process

1. Go to Employees → Import CSV
2. Drag and drop your CSV file or click to browse
3. Click "Preview & Validate"
4. Review the preview table and any validation errors
5. Click "Confirm Import" to proceed
6. Check Activity Log to see imported employees

## Testing with Sample File

A sample CSV file (`sample_import.csv`) is included in the project root for testing purposes.

## Troubleshooting

### "CSV must contain headers"
- Ensure all required column headers are present in your file
- Check header spelling and format (case-insensitive)

### "Email already exists"
- The email address is already in the system
- Update the email or skip this row

### "Department does not exist"
- Verify the department_id matches an existing department
- Check the departments list in the system

### "Invalid email address"
- Ensure email follows standard format (user@domain.com)
- Check for typos or spaces

### "Joining date cannot be in the future"
- Ensure dates are in the past
- Use format YYYY-MM-DD
