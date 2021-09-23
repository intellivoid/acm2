<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace acm2\Objects;

    class Schema
    {
        /**
         * @var string
         */
        private $name;

        /**
         * @var array
         */
        private $structure;

        public function __construct()
        {
            $this->name = 'default_schema';
            $this->structure = [];
        }

        /**
         * @param string $name
         * @param mixed $default_value
         */
        public function setDefinition(string $name, $default_value)
        {
            $this->structure[$name] = $default_value;
        }

        /**
         * @param string $name
         */
        public function removeDefinition(string $name)
        {
            if(isset($this->structure[$name]))
                unset($this->structure[$name]);
        }

        /**
         * Returns structure as array
         *
         * @return array
         */
        public function toArray(): array
        {
            return $this->structure;
        }

        /**
         * @return string
         */
        public function getName(): string
        {
            return $this->name;
        }

        /**
         * @param string $name
         */
        public function setName(string $name): void
        {
            $this->name = $name;
        }
    }