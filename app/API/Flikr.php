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

namespace App\API;

/**
 * Flikr Search API Class
 *
 * Very simple class used to search flikr
 *
 * @package App
 * @subpackage API
 * @author CodinNinja <ninja@codingninja.com.au>
 */
class Flikr implements \IteratorAggregate, \Countable
{

    /**#@+
     * State constants
     */
    const STATE_NOT_SEARCHED = 0;

    const STATE_SEARCHED = 1;
    /**#@-*/

    /**
     * @var The failure code that Flikr sends back
     */
    const STATUS_FAIL = 'fail';

    /**
     * @var array The returned data from Flikr
     */
    protected $data = array ();

    /**
     * @var int The API Key to use
     */
    protected $apiKey = '96012d5b066ca59f0c02e98390f28bcd';

    /**
     * @var string The base API Path to the Flikr API
     */
    protected $apiUri = 'http://api.flickr.com/services/rest/';

    /**
     * @var const The current search state
     */
    protected $state = self::STATE_NOT_SEARCHED;

    /**
     * @var int The max results per page
     */
    protected $maxPerPage = 5;

    /**
     * @var int The page of results to get
     */
    protected $page = 1;

    /**
     * Constructor
     *
     * @throws \RuntimeException Throw when the CURL extension is not loaded
     */
    public function __construct() {
        if (! extension_loaded ( 'curl' )) {
            throw new \RuntimeException ( 'You must enable CURL to access the Flikr API.' );
        }
    }

    /**
     * Get Search term
     *
     * @return string The current term being searched for
     */
    public function getSearchTerm() {
        return $this->term;
    }

    /**
     * Set term
     *
     * Set's the term to search for
     *
     * @param string The term to search for
     * @return self Current instance for fluent API
     */
    public function setSearchTerm($term = '') {
        $this->term = $term;

        return $this;
    }

    /**
     * Get Last Page
     *
     * @return int The last page of results
     */
    public function getLastPage() {
        if ($this->state !== self::STATE_SEARCHED) {
            $this->doSearch ();
        }

        if (isset ( $this->data->photos )) {
            return $this->data->photos->pages;
        }

        return 1;
    }

    /**
     * Set API Key
     *
     * @return self Current instance for fluent API
     */
    public function setApiKey($key) {
        $this->apiKey = $key;

        return $this;
    }

    /**
     * Get API Key
     *
     * @return string Return the current API key being used
     */
    public function getApiKey() {
        return $this->apiKey;
    }

    /**
     * Search
     *
     * Send a request to Flikr to search for the term
     *
     * @param string The term to search for
     * @return self Current instance for fluent API
     */
    public function search($term) {
        $this->setSearchTerm ( $term )->doSearch ();

        return $this;
    }

    /**
     * Get Connection
     *
     * Create a new CURL connection
     *
     * @return resource The CURL handle
     */
    protected function getConnection() {
        $conn = curl_init ();
        curl_setopt ( $conn, CURLOPT_TIMEOUT, 10 );
        curl_setopt ( $conn, CURLOPT_HEADER, 0 );
        curl_setopt ( $conn, CURLOPT_RETURNTRANSFER, 1 );
        return $conn;
    }

    /**
     * Get Maximum results per page
     *
     * @return int The maximum amount of results to show per page
     */
    public function getMaxPerPage() {
        return $this->maxPerPage;
    }

    /**
     * Get Page
     *
     * Get's the current page of results
     *
     * @return int The current page of results
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * Get URI
     *
     * Get's the Flikr URI to send the request to
     *
     * @param string The Flikr API Method to use
     * @param array  The API query options
     * @return string The URI to request
     */
    public function getApiUri($method, array $data = array()) {
        $data = array_merge ( array (
            'api_key' => $this->getApiKey (),
            'method' => $method,
            'format' => 'json',
            'nojsoncallback' => true
        ), $data );

        return $this->apiUri . '?' . http_build_query ( $data );
    }

    /**
     * Do Search
     *
     * Execute a Flikr Search
     *
     * @return array The result of the search
     */
    protected function doSearch() {
        $query = array (
            'text' => $this->getSearchTerm (),
            'per_page' => $this->getMaxPerPage (),
            'page' => $this->getPage ()
        );

        $conn = $this->getConnection ();
        curl_setopt ( $conn, CURLOPT_URL, $this->getApiUri ( 'flickr.photos.search', $query ) );

        $data = curl_exec ( $conn );

        $data = json_decode ( $data );

        if ($data) {
            if ($data->stat === self::STATUS_FAIL) {
                $data = array ();
            }
        } else {
            $data = array ();
        }

        $this->data = $data;

        return $this->data;
    }
    /**
     * Set Page
     *
     * Set's the current page of results
     *
     * @param int The page to ask for
     * @return self Current instance for fluent API
     */
    public function setPage($page) {
        $this->page = $page;

        return $this;
    }
    /**
     * Get Iterator
     *
     * Get a iterable version of the results
     *
     * @return \ArrayIterator An \ArrayIterator object containing the search results
     */
    public function getIterator() {
        if (self::STATE_SEARCHED !== $this->state) {
            $this->doSearch ();
        }

        return new \ArrayIterator ( isset ( $this->data->photos->photo ) ? $this->data->photos->photo : array () );
    }

    /**
     * Count
     *
     * Check how many photos were returned
     *
     * @param string The term to search for
     * @return self Current instance for fluent API
     */
    public function count() {
        if($this->state === self::STATE_NOT_SEARCHED) {
            $this->doSearch();
        }

        return isset($this->data->photos->total) ? $this->data->photos->total : 0;
    }

    /**
     * Uri Helper
     *
     * Get a image path based on the Flikr data
     *
     * @param \stdClass The data to use for the URI
     * @return string A absolute URI to the image on Flikr
     */
    public static function getUriToImage(\stdClass $data, $size = 'q') {
        return sprintf ( 'http://farm%d.static.flickr.com/%d/%s_%s_%s.jpg', $data->farm, $data->server, $data->id, $data->secret, $size );
    }

    /**
     * Title Helper
     *
     * Get the ttle of a Flikr image
     *
     * @param \stdClass The data to use for the URI
     * @return string A title describing the image
     */
    public static function getTitleToImage(\stdClass $data) {
        return $data->title;
    }
}
