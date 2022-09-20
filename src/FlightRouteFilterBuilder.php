<?PHP

namespace Paxperscientiam\FlightRoutesFilter;

use flight\Engine;

class FlightRouteFilterBuilder
{
    protected $beforeFilters = [];
    protected $afterFilters = [];
    protected $appliedFilters = [];

    public function __construct(protected Engine $app)
    {
        $this->request_url = $this->app->request()->url;
    }

    public function addBeforeFilter(string $route, $filter)
    {
        $this->beforeFilters[] = [$route, $filter];

        return $this;
    }

    public function getFilters()
    {
        return [
            'applied' => $this->appliedFilters,
            'before' => $this->beforeFilters,

        ];
    }

    public function build()
    {
        $this->app->before('start', function () {
            array_walk($this->beforeFilters, function (&$item)
            {

                $filter = $item[0];
                $operation = $item[1];
                $route = $this->app->router()->route($this->app->request());

                if (false === $route) {
                    return;
                }

                if (true !== $route->matchUrl($filter)) {
                    return;
                }

                $this->appliedFilters[] = $item;

                return $this->app->$operation();
            }
            );
        }
        );
    }
}
