<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace acm2;

    use acm2\Exceptions\ConfigurationFolderNotFoundException;
    use acm2\Exceptions\ConfigurationNotDefinedException;
    use acm2\Objects\Schema;

    class acm2
    {
        /**
         * @var string
         */
        private $VendorName;

        /**
         * @var string
         */
        private $WorkingDirectory;

        /**
         * @var string
         */
        private $MasterConfigurationPath;

        /**
         * @var array|null
         */
        private $Configuration;

        /**
         * @param string $vendor
         * @throws ConfigurationFolderNotFoundException
         */
        public function __construct(string $vendor)
        {
            $vendor = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $vendor);
            $vendor = mb_ereg_replace("([\.]{2,})", '', $vendor);
            $vendor = str_ireplace(' ', '_', $vendor);
            $vendor = strtolower($vendor);
            $this->VendorName = $vendor;

            $this->WorkingDirectory = DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'acm';
            $this->MasterConfigurationPath = $this->WorkingDirectory . DIRECTORY_SEPARATOR . $this->VendorName . '.json';

            if(file_exists($this->WorkingDirectory) == false)
                throw new ConfigurationFolderNotFoundException('The configuration folder \'' . $this->WorkingDirectory . '\' was not found');
        }

        /**
         * Reloads the configuration or creates it if it doesn't exist
         */
        public function reloadConfiguration()
        {
            if($this->Configuration == null)
            {
                $this->Configuration = [
                    'file_type' => 'acm_json',
                    'file_version' => '2.0.0.0',
                    'configuration' => []
                ];
            }

            if(file_exists($this->MasterConfigurationPath) == false)
            {
                file_put_contents($this->MasterConfigurationPath, json_encode($this->Configuration, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                return;
            }

            $this->Configuration = json_decode(file_get_contents($this->MasterConfigurationPath));
        }

        /**
         * Saves the updated configuration to disk
         */
        public function updateConfiguration()
        {
            if($this->Configuration == null)
                $this->reloadConfiguration();

            file_put_contents($this->MasterConfigurationPath, json_encode($this->Configuration, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }

        /**
         * Defines a new schema in the configuration
         *
         * @param Schema $schema
         */
        public function defineSchema(Schema $schema)
        {
            if($this->Configuration == null)
                $this->reloadConfiguration();

            if(isset($this->Configuration['configuration'][$schema->getName()]) == false)
            {
                $this->Configuration['configuration'][$schema->getName()] = $schema->toArray();
            }
            else
            {
                // Add missing values
                foreach($schema->toArray() as $name => $value)
                {
                    if(isset($this->Configuration['configuration'][$schema->getName()][$name]) == false)
                        $this->Configuration['configuration'][$schema->getName()][$name] = $value;
                }
            }

            $this->updateConfiguration();
        }

        /**
         * Returns a configuration
         *
         * @param string $name
         * @return mixed
         * @throws ConfigurationNotDefinedException
         */
        public function getConfiguration(string $name)
        {
            if(isset($this->Configuration['configuration'][$name]) == false)
                throw new ConfigurationNotDefinedException($name . ' is not defined in the configuration');

            return $this->Configuration['configuration'][$name];
        }

        /**
         * @return string
         */
        public function getMasterConfigurationPath(): string
        {
            return $this->MasterConfigurationPath;
        }

        /**
         * @return string
         */
        public function getWorkingDirectory(): string
        {
            return $this->WorkingDirectory;
        }

        /**
         * @return string
         */
        public function getVendorName(): string
        {
            return $this->VendorName;
        }
    }