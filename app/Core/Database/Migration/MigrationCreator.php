<?php

namespace App\Core\Database\Migration;

class MigrationCreator
{
    protected string $migrationPath;

    protected string $stubPath;

    public function __construct()
    {
        $this->migrationPath =
            dirname(__DIR__, 4)
            . '/database/migrations';

        $this->stubPath =
            __DIR__
            . '/stubs/migration.stub';
    }

    /**
     * Create migration file.
     */
    public function create(
        string $name
    ): string {

        $timestamp =
            date(
                'Y_m_d_His'
            );

        $filename =
            "{$timestamp}_{$name}.php";

        $path =
            $this->migrationPath
            . '/'
            . $filename;

        $className =
            $this->buildClassName(
                $name
            );

        $stub =
            file_get_contents(
                $this->stubPath
            );

        $content =
            str_replace(
                '{{class}}',
                $className,
                $stub
            );

        file_put_contents(
            $path,
            $content
        );

        return $filename;
    }

    /**
     * Build class name.
     */
    protected function buildClassName(
        string $name
    ): string {

        return str_replace(
            ' ',
            '',
            ucwords(
                str_replace(
                    '_',
                    ' ',
                    $name
                )
            )
        );
    }
}