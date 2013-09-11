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


function getAppDir() {
    return dirname(__DIR__) .'/App/';
}

function response($view, $data) {
    extract($data);
    include(getAppDir() . 'Resources/Views/' . $view);
}
// Get the controller, defaults to Searchcontroller
$controller = ucfirst((isset($_GET['c']) ? $_GET['c'] : 'search') . 'Controller');

// Get the action, defaults to Index
$action = ucfirst(isset($_GET['a']) ? $_GET['a'] : 'index');
$data = array();

// We don't have routing so it has to be a straight match
if(file_exists($file = (getAppDir() . 'Controllers/' . $controller . '.php'))) {
     // Include the controller class
    include($file);
    // Create the controller
    $controller = 'App\\Controllers\\' . $controller;
    $controller = new $controller();

    $callable = array($controller, $action.'Action');
    $data = array();
    // Wrap in a try catch block so user doesn't get some horrid error
    // if somethign goes wrong
    try {
        if(is_callable($callable)) { // Yay, valid Controller / Action
                $data = call_user_func($callable);
                if($newTemplate = $controller->getTemplate()) {
                    $action = $newTemplate;
                }else{
                    $action .= '.html';
                }
        }else{  // Noez, they have tried to do something that we haven't linked to :(
            header('HTTP/1.0 401 Not Found');
            $action = 'NotFound.html';
        }
    }catch(Exception $e) { // Looks like something bad happened, let's fail gracefully
        header('HTTP/1.0 500 Server Error');
        $action = 'Error.html';
        $data = array('e' => $e);
    }
}else { // No controller found
    header('HTTP/1.0 401 Not Found');
    $action = 'NotFound.html';
}

// Get the view content
$view = $action . '.php';
ob_start();
response($view, $data);
$content = ob_get_contents();
ob_end_clean();

// Fill the layout and were done!
response($controller->getLayout() ?: 'Layout.html.php', array('content' => $content));
