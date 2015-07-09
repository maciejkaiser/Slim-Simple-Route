<?php

namespace MK\SimpleRoute;

/**
 * SimpleRoute - simple auto-route system for Slim Framework 2.6
 *
 * @author M.Kaiser
 *
 */
class SimpleRoute {

        /**
         * Slim App Instance
         * @var Slim App Instance
         */
        protected $app = null;

        /**
         * Root directory
         *
         * @var string
         */
        protected $catalog = "";

        /**
         * Route's list
         *
         * @var unknown
         */
        protected $list = [ ];

        /**
         * Blocked route's list
         *
         * @var unknown
         */
        protected $blackList = [ ];

        /**
         * Constructor - get routes location and specific routes to block (optional)
         *
         * @param array $options
         */
        function __construct($routesLocation = "", array $options = [])
        {
                $this->app = \Slim\Slim::getInstance ();
                $this->catalog = $routesLocation;
                $this->blackList = $options;
        }

        /**
         * Run process of requiring routes
         */
        public function run()
        {
                try
                {
                        $app = $this->app;

                        $this->load ( $this->catalog, $this->list );

                        unset ( $this->list ['.'] );
                        unset ( $this->list [$this->catalog] );



                        if ($this->blackList && is_array ( $this->blackList ))
                        {
                                foreach ( $this->blackList as $key => $value )
                                {

                                        if (! is_array ( $value )) {
                                                unset ( $this->list [$this->catalog ."/". $value] );
                                        }

                                        if (is_array ( $value ))
                                        {
                                                foreach ( $value as $k => $v )
                                                {
                                                        unset ( $this->list [$this->catalog ."/". $key] [$v . ".php"] );
                                                }
                                        }
                                }
                        }

                        //dd($this->list);

                        foreach ( $this->list as $key => $value )
                        {
                                foreach ( $value as $v )
                                {
                                        require INC_ROOT . substr ( $key, 2 ) . "/" . $v;
                                }
                        }
                }
                catch ( \Exception $e )
                {
                        // Logs implement here
                        // i.e. $this->app->log->error ( $e->getMessage () );
                        echo "<b>AutoRouter Error: </b>".$e->getMessage ();
                        die();
                }
        }
        /**
         * Loading directories
         *
         * @param string $directoryectory
         * @param array $array
         */
        protected function load($directory, &$array)
        {
                try
                {
                        if($directory)
                        {
                                $files = scandir ( $directory );

                                if (is_array ( $files ))
                                {
                                        foreach ( $files as $value )
                                        {

                                                if ($value == "." || $value == "..")
                                                        continue;

                                                if (is_dir ( $directory . '/' . $value ))
                                                {

                                                        $array [$directory] [] = $value;

                                                        $this->load ( $directory . '/' . $value, $array );

                                                }

                                                else
                                                {
                                                        $array [$directory] [$value] = $value;
                                                }
                                        }
                                }
                        }

                        else
                        {
                                throw new \Exception("Directory cannot be empty!");
                        }
                }
                catch ( \Exception $e )
                {
                        // Logs implement here
                        // i.e. $this->app->log->error ( $e->getMessage () );
                        echo "<b>AutoRouter Error: </b>".$e->getMessage ();
                        die();
                }
        }
}
