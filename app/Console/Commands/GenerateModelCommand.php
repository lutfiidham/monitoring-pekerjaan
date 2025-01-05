<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class GenerateModelCommand extends Command
{
    protected $signature = 'generate:model {name} {--table=}';
    protected $description = 'Generate a model with fillable properties and relationships based on database table columns';

    public function handle()
    {
        $name = $this->argument('name');
        $tableName = $this->option('table') ?? strtolower(Str::plural($name));
        
        if (!Schema::hasTable($tableName)) {
            $this->error("Table '{$tableName}' does not exist.");
            return;
        }

        $columns = Schema::getColumnListing($tableName);
        $excludeColumns = ['id', 'created_at', 'updated_at', 'deleted_at'];
        $fillable = array_diff($columns, $excludeColumns);
        $fillableString = "['" . implode("', '", $fillable) . "']";

        $relations = $this->detectRelations($tableName);

        $modelPath = app_path("Models/{$name}.php");

        $usesSoftDeletes = in_array('deleted_at', $columns) ? "use Illuminate\Database\Eloquent\SoftDeletes;\n" : '';
        $softDeletes = in_array('deleted_at', $columns) ? "use SoftDeletes;\n" : '';

        $modelTemplate = <<<EOT
        <?php

        namespace App\Models;

        use Illuminate\Database\Eloquent\Model;
        {$usesSoftDeletes}

        class {$name} extends Model
        {
            {$softDeletes}
            protected \$table = '{$tableName}';
            protected \$fillable = {$fillableString};

        {$relations}
        }
        EOT;

        if (!file_exists($modelPath)) {
            file_put_contents($modelPath, $modelTemplate);
            $this->info("Model {$name} created successfully.");
        } else {
            $this->warn("Model {$name} already exists.");
        }
    }

    private function detectRelations($tableName)
    {
        $relationMethods = '';
        $columns = Schema::getColumnListing($tableName);

        foreach ($columns as $column) {
            if (Str::endsWith($column, '_id')) {
                $relatedModel = Str::studly(Str::singular(str_replace('_id', '', $column)));
                $methodName = Str::camel(str_replace('_id', '', $column));

                $relationMethods .= <<<EOT

        public function {$methodName}()
        {
            return \$this->belongsTo({$relatedModel}::class);
        }

    EOT;
            }
        }

        return $relationMethods;
    }
}
