<?php

class Nlp_Environment
{

    const WordPress = 1; // regular wordpress
    const WordPressMU = 2; // wordpress mu
    const WordPressMS = 3; // wordpress multi-site

}

abstract class Nlp_WPPlugin
{

    protected $environment; // what environment are we in
    protected $options_name; // the name of the options associated with this plugin
    protected $options;

    function Nlp_WPPlugin($options_name)
    {
        $args = func_get_args();
        call_user_func_array(array(&$this, "__construct"), $args);
    }

    function __construct($options_name)
    {
        $this->environment = Nlp_WPPlugin::nlp_determine_environment();
        $this->options_name = $options_name;

        $this->options = get_option($this->options_name);
    }

    // sub-classes determine what actions and filters to hook
    abstract protected function nlp_register_actions();

    abstract protected function nlp_register_filters();

    // environment checking
    static function nlp_determine_environment()
    {
        global $wpmu_version;

        if (function_exists('is_multisite'))
            if (is_multisite())
                return Nlp_Environment::WordPressMS;

        if (!empty($wpmu_version))
            return Nlp_Environment::WordPressMU;

        return Nlp_Environment::WordPress;
    }

    // options
    abstract protected function nlp_register_default_options();

    protected function nlp_is_multi_blog()
    {
        return $this->environment != Nlp_Environment::WordPress;
    }

    // calls the appropriate 'authority' checking function depending on the environment
    protected function nlp_is_authority()
    {
        if ($this->environment == Nlp_Environment::WordPress)
            return is_admin();

        if ($this->environment == Nlp_Environment::WordPressMU)
            return is_site_admin();

        if ($this->environment == Nlp_Environment::WordPressMS)
            return is_super_admin();
    }

    static function add_options($options_name, $options)
    {
        if (Nlp_WPPlugin::determine_environment() == Nlp_Environment::WordPressMU)
            return add_site_option($options_name, $options);
        else
            return add_option($options_name, $options);
    }

}

?>
