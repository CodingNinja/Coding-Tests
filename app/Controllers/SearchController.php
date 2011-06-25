<?php

/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY
 * KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS
 * OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @author CodinNinja
 */

namespace App\Controllers;

use \App\Api\Flikr;

include(dirname(__DIR__) . '/API/Flikr.php');

/**
 * Search Controller
 *
 * Allows simple searching of Flikr
 *
 * @package App
 * @subpackage Controller
 * @author CodinNinja <ninja@codingninja.com.au>
 */
class SearchController {

    protected $api = false;

    protected $request;

    protected $template = false;

    /**
     * Constructor
     *
     * @author
     */
    public function __construct() {
        $this->request = array_map('htmlentities', $_REQUEST);
    }

    /**
     * Index Action
     *
     * @return array An array of parameters to send to the view
     * @author CodinNinja <ninja@codingninja.com.au>
     */
    public function indexAction() {
        return array();
    }

    /**
     * Search Action
     * @return array An array of parameters to send to the view
     * @author CodinNinja <ninja@codingninja.com.au>
     */
    public function searchAction() {
        $term = $this->getRequestParameter('term');
        $page = $this->getRequestParameter('page', '1');

        $results = $this->getApi()->setPage($page)->setSearchTerm($term);

        $lastPage = $this->getApi()->getLastPage();

        if($this->isAjax()) {
            $this->setTemplate('search.json');
        }

        return array(
            'results'  => $results,
            'term' 	   => $term,
            'page'     => $page,
            'lastPage' => $lastPage,
            'prevPage' => max($page - 1, 1),
            'nextPage' => min($page + 1, $lastPage)
        );
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

	/**
     * Get SearchController::$api
     *
     * @see SearchController::$api
     * @return mixed
     * @author CodinNinja <ninja@codingninja.com.au>
     */
    public function getApi($reset = false) {
        if(!$this->api || $reset) {
            $this->setApi(new Flikr());
        }

        return $this->api;
    }

	/**
     * Set SearchController::$api
     *
     * @see SearchController::$api
     * @return SearchController Refrence to self for fluent interface
     * @author CodinNinja <ninja@codingninja.com.au>
     */
    public function setApi($api) {
        $this->api = $api;

        return $this;
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