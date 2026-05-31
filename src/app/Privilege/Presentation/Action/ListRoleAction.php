<?php declare(strict_types=1);

namespace App\Privilege\Presentation\Action;

use App\Shared\Presentation\Action;
use App\Privilege\Application\Query\ListRoleQuery;
use App\Shared\Domain\Bus\QueryBusInterface;
use App\Privilege\Presentation\Responder\ListRoleResponder;
use Spatie\RouteAttributes\Attributes\Defaults;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Route;
use App\Shared\Presentation\Response\ApiResponse;
use Illuminate\Http\Request;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'auth:api')]
final class ListRoleAction extends Action
{
    /**
     * @phpstan-var ListRoleResponder
     */
    private readonly ListRoleResponder $responder;

    /**
     * @phpstan-param QueryBusInterface<
     *     ListRoleQuery<int>,
     *     object,
     *     \App\Shared\Application\Result\Result<
     *         \Eryndrix\Paginator\Paginator<\App\Privilege\Domain\Role>,
     *         string
     *     >
     * > $queryBus
     */
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {
        $this->responder = new ListRoleResponder();
    }

    /**
     * @phpstan-param Request $request
     * @phpstan-return ApiResponse
     */
    #[Defaults(key: 'perPage', value: '15')]
    #[Route(methods: 'GET', uri: '/roles')]
    public function __invoke(Request $request): ApiResponse
    {
        $perPage = $request->integer(key: 'perPage', default: 15);

        /**
         * @phpstan-var \App\Shared\Application\Result\Result<
         *     \Eryndrix\Paginator\Paginator<\App\Privilege\Domain\Role>,
         *     string
         * > $result
         */
        $result = $this->queryBus->ask(
            query: new ListRoleQuery(perPage: (int) $perPage)
        );

        return $this->responder->respond(
            result: $result
        );
    }
}
