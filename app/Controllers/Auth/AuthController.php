<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class AuthController extends Controller
{
    public function getLogOut($request, $response)
    {
        $this->c->auth->logout();

        return $response->withRedirect($this->c->router->pathFor('auth.login'));
    }

    public function getLogIn($request, $response)
    {
        return $this->c->view->render($response, 'auth/login.twig');
    }

    public function postLogIn($request, $response)
    {
        $validation = $this->c->validator->validate($request, [
            'email' => v::email()->notEmpty(),
            'password' => v::noWhitespace()->notEmpty(),
        ]);

        if ($validation->failed()) {
            $this->c->flash->addMessage('error', 'Oh no, something went wrong!');

            return $response->withRedirect($this->c->router->pathFor('auth.register'));
        }

        $auth = $this->c->auth->attempt(
            $request->getParam('email'),
            $request->getParam('password')
        );

        if (!$auth) {
            $this->c->flash->addMessage('error', 'Sorry, that wasn\'t quite right...');
            return $response->withRedirect($this->c->router->pathFor('auth.login'));
        }

        $user = User::where('email', $request->getParam('email'))->first();

        return $response->withRedirect($this->c->router->pathFor('dashboard.index', [
            'user' => $user->email,
        ]));
    }

    public function getRegister($request, $response)
    {
        return $this->c->view->render($response, 'auth/register.twig');
    }

    public function postRegister($request, $response)
    {
        $validation = $this->c->validator->validate($request, [
            'email' => v::email()->notEmpty(),
            'password' => v::noWhitespace()->notEmpty(),
        ]);

        if ($validation->failed()) {
            $this->c->flash->addMessage('error', 'Oh no, something went wrong!');

            return $response->withRedirect($this->c->router->pathFor('auth.register'));
        }

        $user = User::create([
            'email' => $request->getParam('email'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
            'role' => 'user',
        ]);

        $this->c->flash->addMessage('info', 'You are now signed up!');

        $this->c->auth->attempt($user->email, $request->getParam('password'));

        return $response->withRedirect($this->c->router->pathFor('dashboard.index', [
            'user' => $user->email,
        ]));
    }
}
