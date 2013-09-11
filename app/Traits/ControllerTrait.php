<?php

namespace App\Traits;

trait ControllerTrait {

    protected $template = false;

    protected $request;

    /**
     * Constructor
     *
     * @author
     */
    public function __construct() {
        $this->setRequest(array_map('htmlentities', $_REQUEST));
    }

    public function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    public function getLayout() {
        return $this->isAjax() ? 'Layout.ajax.php' : 'Layout.html.php';
    }

    public function getRequestParameter($key, $default = null) {
        return isset($this->request[$key]) ? $this->request[$key] : $default;
    }

    public function getAlphaNum($key, $default = null) {
        return preg_replace('/[^a-zA-Z0-9]/', '', $this->getRequestParameter($key, $default));
    }

    public function getInt($key, $default = null) {
        return intval($this->getRequestParameter($key, $default));
    }

    /**
     * Get SearchController::$request
     *
     * @see SearchController::$request
     * @return mixed
     * @author CodinNinja <ninja@codingninja.com.au>
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * Set SearchController::$request
     *
     * @see SearchController::$request
     * @return SearchController Refrence to self for fluent interface
     * @author CodinNinja <ninja@codingninja.com.au>
     */
    public function setRequest($request) {
        $this->request = $request;

        return $this;
    }

    /**
     * Get SearchController::$template
     *
     * @see SearchController::$template
     * @return mixed
     * @author CodinNinja <ninja@codingninja.com.au>
     */
    public function getTemplate() {
        return $this->template;
    }

    /**
     * Set SearchController::$template
     *
     * @see SearchController::$template
     * @return SearchController Refrence to self for fluent interface
     * @author CodinNinja <ninja@codingninja.com.au>
     */
    public function setTemplate($template) {
        $this->template = $template;

        return $this;
    }
}
