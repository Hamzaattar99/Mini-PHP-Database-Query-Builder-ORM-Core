<?php
// (Future Feature Required -> *(Mapping with Common SQLSTATE Codes)*)
// (Future Feature Required -> *(Determaining and Extracting the type of error from the coming Database Erorr Message)*)
namespace App\Core\Database\Exceptions;

use Exception;
use PDOException;
use Throwable;

class DatabaseException extends Exception
{
    /**
     * Error identifier.
     */
    protected string $errorCodeIdentifier;

    /**
     * Actual Error.
     */
    protected string $actualError;

      /**
     * Suggested Solution.
     */
    protected string $solution;

      /**
     * Timestamp For Error Ouccerance.
     */
    protected string $timestamp;

    public function __construct(string $message, string $errorCodeIdentifier, string $actualError = '', string $solution) {
        
        parent::__construct($message);

        $this->errorCodeIdentifier = $errorCodeIdentifier;

        $this->actualError = $actualError;

        $this->solution = $solution;

       $this->timestamp = date('Y-m-d H:i:s');
    }

    /**
     * Returns framework error code.
     */
    public function getErrorCodeIdentifier(): string
    {
        return $this->errorCodeIdentifier;
    }


    /**
     * Returns actual database error.
     */
    public function getActualError(): string
    {
        return $this->actualError;
    }

    /**
     * Returns actual error solution.
    */
    public function getSolution(): string
    {
        return $this->solution;
    }

    /**
     * Returns actual error timestamp.
    */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

     /**
     * Gets the Debug Report for the Actual Error.
    */
     public function getDebugReport(): string
    {
        return
            PHP_EOL .
            "========================================" . PHP_EOL .
            "DATABASE ERROR REPORT" . PHP_EOL .
            "========================================" . PHP_EOL .
            "Error Code: " . $this->errorCodeIdentifier . PHP_EOL .
            "User Message: " . $this->getMessage() . PHP_EOL .
            "Actual Error: " . $this->actualError . PHP_EOL .
            "Suggested Solution: " . $this->solution . PHP_EOL .
            "Timestamp: " . $this->timestamp . PHP_EOL;
    }





    /**
     * Database connection error.
     */
    public static function connectionError(PDOException $exception): self {
        return new self(
            'Unable to connect to the database. (Error Code: DB-001)',
            'DB-001',
            $exception->getMessage(),
            'Check database credentials and database server status.'
        );
    }

    /**
     * Query execution error.
     */
    public static function queryError(PDOException $exception): self {
        return new self(
            'An error occurred while executing the database query. (Error Code: DB-002)',
            'DB-002',
            $exception->getMessage(),
            'Review the generated SQL query and its parameters.'
        );
    }

    /**
     * Table not found.
     */
    public static function tableNotFound(PDOException $exception): self {
        return new self(
            'The requested database table could not be found. (Error Code: DB-003)',
            'DB-003',
            $exception->getMessage(),
            'Verify that the table exists and migrations have been executed.'
        );
    }

    /**
     * Table Exsisted (FOR CREATING NEW TABLE OPERATION).
     */
    public static function tableExsisted(PDOException $exception): self {
        return new self(
            'The requested database table already exsists. (Error Code: DB-004)',
            'DB-004',
            $exception->getMessage(),
            'Verify that the table exists and migrations have been executed.'
        );
    }

  

    /**
     * Migration error.
     */
    public static function migrationError(Exception $exception): self {
        return new self(
            'The migration process could not be completed. (Error Code: DB-005)',
            'DB-005',
            $exception->getMessage(),
            'Review the migration file and database structure.'
        );
    }


      /**
     * Authentication Erorr.
     */
    public static function authenticationFailed(PDOException $exception): self {
        return new self(
            'Database authentication failed. Please contact support. (Error Code: DB-006)',
            'DB-006',
            $exception->getMessage(),
            'Check username/password and database user privileges.'
        );
    }


     /**
     * Database Not Found
     */
    public static function databaseNotFound(PDOException $exception): self {
        return new self(
            'The requested database could not be found. (Error Code: DB-007)',
            'DB-007',
            $exception->getMessage(),
            'Confirm database name or ensure it exists before connection'
        );
    }


     /**
     * Transaction Failed
     */
    public static function transactionFailed(PDOException $exception): self {
        return new self(
            'The operation could not be completed successfully. (Error Code: DB-008)',
            'DB-008',
            $exception->getMessage(),
            'Use rollback and retry safely if needed'
        );
    }


     /**
     * Transaction Rollback
     */
    public static function transactionRollback(PDOException $exception): self {
        return new self(
            'Changes have been reverted due to an unexpected issue. (Error Code: DB-009)',
            'DB-009',
            $exception->getMessage(),
            'Review transaction flow and simplify complex operations'
        );
    }

      /**
     * Record Not Found
     */
    public static function recordNotFound(PDOException $exception): self {
        return new self(
            'The requested record was not found. (Error Code: DB-010)',
            'DB-010',
            $exception->getMessage(),
            'Validate existence before querying or handle null results properly'
        );
    }

       /**
     * Duplicate Entry
     */
    public static function duplicateEntry(PDOException $exception): self {
        return new self(
            'This record already exists. (Error Code: DB-011)',
            'DB-011',
            $exception->getMessage(),
            'Check existence before insert or use UPSERT logic'
        );
    }

       /**
     * Foreign Key Constraint
     */
    public static function foreignKeyConstraint(PDOException $exception): self {
        return new self(
            'This record cannot be modified because it is linked to other data. (Error Code: DB-012)',
            'DB-012',
            $exception->getMessage(),
            'Ensure related records exist or delete/update dependencies first'
        );
    }

       /**
     * Unique Constraint Violation
     */
    public static function uniqueConstraintViolation(PDOException $exception): self {
        return new self(
            'The provided value must be unique. (Error Code: DB-013)',
            'DB-013',
            $exception->getMessage(),
            'Validate uniqueness before insert/update on server side'
        );
    }

       /**
     * Not Null Violation
     */
    public static function notNullViolation(PDOException $exception): self {
        return new self(
            'A required field is missing. (Error Code: DB-014)',
            'DB-014',
            $exception->getMessage(),
            'Ensure all required fields are provided before insert/update'
        );
    }


     /**
     * Invalid Data Type
     */
    public static function invalidDataType(PDOException $exception): self {
        return new self(
            'One or more values have an invalid format. (Error Code: DB-015)',
            'DB-015',
            $exception->getMessage(),
            'Apply strict input validation and type casting'
        );
    }


     /**
     * Data Too Long
     */
    public static function dataTooLong(PDOException $exception): self {
        return new self(
            'The provided data exceeds the allowed length. (Error Code: DB-016)',
            'DB-016',
            $exception->getMessage(),
            'Truncate input or increase column size in schema'
        );
    }


     /**
     * Numeric Value Out Of Range
     */
    public static function numericValueOutOfRange(PDOException $exception): self {
        return new self(
            'A numeric value is outside the allowed range. (Error Code: DB-017)',
            'DB-017',
            $exception->getMessage(),
            'Validate numeric limits (min/max) before storing data'
        );
    }


     /**
     * Syntax Error In (SQL, etc) 
     */
    public static function syntaxError(PDOException $exception): self {
        return new self(
            'An internal database query error occurred. (Error Code: DB-018)',
            'DB-018',
            $exception->getMessage(),
            'Review SQL query or switch to query builder/ORM'
        );
    }


    /**
     * Column Not Found
     */
    public static function columnNotFound(PDOException $exception): self {
        return new self(
            'The requested data source could not be found. (Error Code: DB-019)',
            'DB-019',
            $exception->getMessage(),
            'Ensure schema and code are synchronized'
        );
    }


    /**
     * Deadlock Detected 
     */
    public static function deadlockDetected(PDOException $exception): self {
        return new self(
            'The operation could not be completed due to a temporary conflict. Please try again. (Error Code: DB-020)',
            'DB-020',
            $exception->getMessage(),
            'Retry transaction and reduce lock duration'
        );
    }


    /**
     * Lock Wait Timeout
     */
    public static function lockWaitTimeout(PDOException $exception): self {
        return new self(
            'The operation timed out while waiting for database resources. (Error Code: DB-021)',
            'DB-021',
            $exception->getMessage(),
            'Optimize queries and reduce long-running'
        );
    }


    /**
     * Permission Denied
     */
    public static function permissionDenied(PDOException $exception): self {
        return new self(
            'You do not have permission to perform this action. (Error Code: DB-022)',
            'DB-022',
            $exception->getMessage(),
            'Verify user roles and database privileges'
        );
    }


    /**
     * Read Only Database
     */
    public static function readOnlyDatabase(PDOException $exception): self {
        return new self(
            'The database is currently in read-only mode. (Error Code: DB-023)',
            'DB-023',
            $exception->getMessage(),
            'Ensure database is in writable mode or correct replica usage'
        );
    }


    /**
     * Storage Full 
     */
    public static function storageFull(PDOException $exception): self {
        return new self(
            'The system is temporarily unable to store additional data. (Error Code: DB-024)',
            'DB-024',
            $exception->getMessage(),
            'Free up disk space or increase storage capacity'
        );
    }


    /**
     * Connection Lost
     */
    public static function connectionLost(PDOException $exception): self {
        return new self(
            'The connection to the database was interrupted. (Error Code: DB-025)',
            'DB-025',
            $exception->getMessage(),
            'Implement retry mechanism with exponential backoff'
        );
    }


    /**
     * Query Timeout
     */
    public static function queryTimeout(PDOException $exception): self {
        return new self(
            'The operation took too long to complete. (Error Code: DB-026)',
            'DB-026',
            $exception->getMessage(),
            'Optimize query performance and add indexes if needed'
        );
    }


    /**
     * Schema Error
     */
    public static function schemaError(PDOException $exception): self {
        return new self(
            'An error occurred while updating the database structure. (Error Code: DB-027)',
            'DB-027',
            $exception->getMessage(),
            'Ensure consistency between schema, migrations, and models'
        );
    }


    /**
     * Index Creation Failed 
     */
    public static function indexCreationFailed(PDOException $exception): self {
        return new self(
            'Failed to create the required database index. (Error Code: DB-028)',
            'DB-028',
            $exception->getMessage(),
            'Check for duplicate indexes or conflicting constraints'
        );
    }


    /**
     * Constraint Creation Failed 
     */
    public static function constraintCreationFailed(PDOException $exception): self {
        return new self(
            'Failed to create a database constraint. (Error Code: DB-029)',
            'DB-029',
            $exception->getMessage(),
            'Validate existing data before adding constraints'
        );
    }


    /**
     * Unsupported Operation
     */
    public static function unsupportedOperation(PDOException $exception): self {
        return new self(
            'This database operation is not supported. (Error Code: DB-030)',
            'DB-030',
            $exception->getMessage(),
            'Verify database engine compatibility for the operation'
        );
    }


    /**
     * Unknown Database Error
     */
    public static function unknownDatabaseError(PDOException $exception): self {
        return new self(
            'An unexpected database error occurred. (Error Code: DB-031)',
            'DB-031',
            $exception->getMessage(),
            'Log full error details and apply a generic fallback handler.'
        );
    }

    /**
     * InvalidComaprsionOperator (=/!=/>=/<=/==/!=/etc)
     */
    public static function invalidComaprsionOperator(Throwable|string $exception): self {
        return new self(
            'An unexpected Invalid Comaprsion error occurred. (Error Code: DB-032)',
            'DB-032',
            $exception->getMessage(),
            'Make sure to use a valid comparsion operator.'
        );
    }

    /**
     * Invalid Order Direction (DSES / ASC)
     */
    public static function invalidOrderDirection(Throwable|string $exception): self {
        return new self(
            'An unexpected Invalid Direction error occurred. (Error Code: DB-033)',
            'DB-033',
            $exception->getMessage(),
            'Make sure to use a correct directon symbol (DESC/ASC).'
        );
    }

    /**
     * Invalid Indentifier
     */
    public static function InvalidIndentifier(Throwable|string $exception): self {
        return new self(
            'An unexpected Invalid Indentifier(Table/Column) error occurred (Error Code: DB-034)',
            'DB-034',
            $exception->getMessage(),
            'Make sure to use a correct (Column/Table) name.'
        );
    }




}