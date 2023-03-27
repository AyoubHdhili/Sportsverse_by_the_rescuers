<?php

namespace App\Security;

use phpDocumentor\Reflection\PseudoTypes\True_;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        $user = $token->getUser();
        //  dd($user);
        $r = implode($user->getRoles());
        if (strcmp($r, 'ROLE_USER') === 0) {
            if ($user->isBanned == true) {
                $this->urlGenerator->generate('app_logout');
                $url = $this->urlGenerator->generate('app_clientBanned');
            } else
                $url = $this->urlGenerator->generate('app_home', ['id' => $user->getUserIdentifier()]);
        } elseif (strcmp($r, 'ROLE_ADMIN') === 0) {
            $url = $this->urlGenerator->generate('app_users_admin');
        } elseif (strcmp($r, 'ROLE_COACH') === 0) {
            if ($user->isBanned == true) {

                $this->urlGenerator->generate('app_logout');
                $url = $this->urlGenerator->generate('app_coachBanned');
            } else {
                $url = $this->urlGenerator->generate('app_coach_seance', ['id' => $user->getUserIdentifier()]);
            }
        }

        return new RedirectResponse($url);
        // For example:
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
