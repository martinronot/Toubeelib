<?php
declare(strict_types=1);

namespace toubeelib\application\actions\auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubeelib\application\actions\Action;
use toubeelib\domain\auth\AuthService;

class GetMeAction extends Action
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    protected function action(Request $request): Response
    {
        // L'utilisateur est déjà authentifié grâce au middleware JWT
        $user = $request->getAttribute('user');
        
        return $this->respondWithData($user);
    }
}
