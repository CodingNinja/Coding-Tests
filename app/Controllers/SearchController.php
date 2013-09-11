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
use \App\Traits\ControllerTrait;

include(dirname(__DIR__) . '/API/Flikr.php');
include(dirname(__DIR__) . '/Traits/ControllerTrait.php');

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

    use ControllerTrait;

    protected $api = false;

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
        $term = $this->getAlphaNum('term');
        $page = $this->getInt('page', '1');
        $api = $this->getApi();

        $results = $api->setPage($page)->setSearchTerm($term);

        $lastPage = $api->getLastPage();

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
}
