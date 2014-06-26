<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/nicksagona/PopPHP
 * @category   Pop
 * @package    Pop_Application
 * @author     Nick Sagona, III <info@popphp.org>
 * @copyright  Copyright (c) 2009-2014 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Application;

/**
 * Application build class
 *
 * @category   Pop
 * @package    Pop_Application
 * @author     Nick Sagona, III <info@popphp.org>
 * @copyright  Copyright (c) 2009-2014 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    2.0.0a
 */
class Build
{

    /**
     * Build the application based on the available config file
     *
     * @param string $buildFile
     * @return void
     */
    public static function build($buildFile)
    {
        // Display instructions to continue
        \Pop\Cli::instructions();
        $dbTables = [];

        $input = self::input();
        if ($input == 'n') {
            echo 'Aborted.' . PHP_EOL . PHP_EOL;
            exit();
        }

        // Get the build config.
        $buildDir = realpath(dirname($buildFile));
        $build    = include $buildFile;

        // Check if a application folder already exists.
        if (file_exists(realpath($build->application->base) . DIRECTORY_SEPARATOR . $build->application->name)) {
            echo PHP_EOL . wordwrap('    The application folder already exists. This may overwrite any application files ' . '
                you may already have under that application folder.', 70, PHP_EOL . '    ') . PHP_EOL . PHP_EOL;
            $input = self::input();
        } else {
            $input = 'y';
        }

        // If 'No', abort
        if ($input == 'n') {
            echo PHP_EOL . '    Aborted.' . PHP_EOL . PHP_EOL;
            exit();
        // Else, continue
        } else {

            // Build base folder and file structure
            Build\Base::build($build);

            // Build application file
            Build\Application::build($build);

            $db        = false;
            $databases = [];

            // Test for a database credentials and schema
            // and ask to test and install the database.
            if (isset($build->databases)) {
                $databases = $build->databases->toArray();
                echo PHP_EOL . '    Database credentials and schema detected.' . PHP_EOL;
                $input = self::input('    Test and install the database(s)?' . ' (Y/N) ');
                $db = ($input == 'n') ? false : true;
            }

            // Handle any databases
            if ($db) {
                // Get current error reporting setting and set
                // error reporting to E_ERROR to suppress warnings
                $oldError = ini_get('error_reporting');
                error_reporting(E_ERROR);

                // Test the databases
                echo PHP_EOL . '    Testing the database(s)...' . PHP_EOL;

                foreach ($databases as $dbname => $db) {
                    echo '      - Testing' . ' \'' . $dbname . '\'...' . PHP_EOL;
                    if (!isset($db['type']) || !isset($db['database'])) {
                        echo PHP_EOL . '    The database type and database name must be set for the database ' .
                            '\'' . $dbname . '\'.' . PHP_EOL . PHP_EOL;
                        exit();
                    }
                    $check = Build\Dbs::check($db);
                    if (null !== $check) {
                        echo PHP_EOL . '    ' . $check . PHP_EOL . PHP_EOL;
                        exit();
                    } else {
                        echo PHP_EOL . '    Database' . ' \'' . $dbname . '\' passed.' . PHP_EOL;
                        echo '      - Installing ' .' \'' . $dbname . '\'...' . PHP_EOL;
                        $tables = Build\Dbs::install($dbname, $db, $buildDir, $build);
                        if (count($tables) > 0) {
                            $dbTables = array_merge($dbTables, $tables);
                        }
                    }
                }
                // Return error reporting to its original state
                error_reporting($oldError);
            }

            // Build table class files
            if (count($dbTables) > 0) {
                Build\Tables::build($build, $dbTables);
            }

            // Build controller class files
            if (isset($build->controllers)) {
                Build\Controllers::build($build, $buildDir);
            }

            // Build form class files
            if (isset($build->forms)) {
                Build\Forms::build($build);
            }

            // Build model class files
            if (isset($build->models)) {
                Build\Models::build($build);
            }

            // Create 'bootstrap.php' file
            Build\Bootstrap::build($build);

            echo PHP_EOL . '    Application build complete.' . PHP_EOL . PHP_EOL;
            exit();
        }
    }

    /**
     * Return the (Y/N) input from STDIN
     *
     * @param  string $msg
     * @return string
     */
    public static function input($msg = null)
    {
        echo ((null === $msg) ? '    Continue?' . ' (Y/N) ' : $msg);
        $input = null;

        while (($input != 'y') && ($input != 'n')) {
            if (null !== $input) {
                echo $msg;
            }
            $prompt = fopen("php://stdin", "r");
            $input  = fgets($prompt, 5);
            $input  = substr(strtolower(rtrim($input)), 0, 1);
            fclose($prompt);
        }

        return $input;
    }

    /**
     * Method to convert the string from under_score to camelCase format
     *
     * @param  string $string
     * @return string
     */
    public static function underscoreToCamelcase($string)
    {
        $strAry = explode('_', $string);
        $camelCase = null;
        $i = 0;

        foreach ($strAry as $word) {
            if ($i == 0) {
                $camelCase .= $word;
            } else {
                $camelCase .= ucfirst($word);
            }
            $i++;
        }

        return $camelCase;
    }

}
