<?php declare(strict_types=1);

namespace App\Privilege\Presentation\Action;

use App\Shared\Presentation\Action;
use App\Privilege\Application\Query\ShowRoleQuery;
use App\Shared\Domain\Bus\QueryBusInterface;
use App\Privilege\Presentation\Responder\ShowRoleResponder;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\WhereUuid;
use Spatie\RouteAttributes\Attributes\Route;
use App\Shared\Presentation\Response\ApiResponse;
use App\Shared\Domain\Id\RoleId;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'auth:api')]
final class ShowRoleAction extends Action
{
    /**
     * @phpstan-var ShowRoleResponder
     */
    private readonly ShowRoleResponder $responder;

    /**
     * @phpstan-param QueryBusInterface<
     *     ShowRoleQuery<RoleId>,
     *     object,
     *     \App\Shared\Application\Result\Result<
     *         \App\Privilege\Domain\Role,
     *         string
     *     >
     * > $queryBus
     */
    public function __construct(
        private QueryBusInterface $queryBus
    ) {
        $this->responder = new ShowRoleResponder();
    }

    /**
     * @phpstan-param RoleId $roleId
     * @phpstan-return ApiResponse
     */
    #[WhereUuid(param: 'roleId')]
    #[Route(methods: 'GET', uri: '/roles/{roleId}')]
    public function __invoke(RoleId $roleId): ApiResponse
    {
        /**
         * @phpstan-var \App\Shared\Application\Result\Result<
         *     \App\Privilege\Domain\Role,
         *     string
         * > $result
         */
        $result = $this->queryBus->ask(
            query: new ShowRoleQuery(roleId: $roleId)
        );

        return $this->responder->respond(
            result: $result
        );
    }
}
