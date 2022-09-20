# Example usage

```PHP
<?PHP

use Paxperscientiam\FlightRoutesFilter\FlightRouteFilterBuilder;

require "../vendor/autoload.php";

Flight::map("authRequired", function () {
    echo "authRequired filter applied<br><br>";
    exit;
});

Flight::map("derp", function () {
    echo "derp filter applied<br><br>";
});

Flight::map("greetAbe", function () {
    echo "Hi Abe!<br><br>";
});

Flight::map("curseMolluck", function () {
    echo "Damn you Molluck!<br><br>";
});


$x = new FlightRouteFilterBuilder(Flight::app());

$x
    ->addBeforeFilter("/a", "derp")
    ->addBeforeFilter("/a", "authRequired")
    ->addBeforeFilter("/abe", "greetAbe")
    ->build();


Flight::route('/molluck', function () use ($x) {
    s($x->getFilters()['applied']);
    echo 'rendered';
});

Flight::route('/abe', function () use ($x) {
    s($x->getFilters()['applied']);
    echo 'rendered';
});

Flight::route('/a', function () use ($x) {
    echo 'glad to be authorized';
});

Flight::map('error', function (Exception $ex) {
    echo $ex->getTraceAsString();
});

Flight::start();


```
