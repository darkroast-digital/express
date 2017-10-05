<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class PasswordController extends Controller
{
    public function getChangePassword($request, $response)
    {
        return $this->c->view->render($response, '/auth/password/change.twig');
    }

    public function postChangePassword($request, $response)
    {
        $user = User::where('username', $request->getParam('username'))->first();

        $validation = $this->c->validator->validate($request, [
            'username' => v::notEmpty()->alpha(),
            'password_old' => v::noWhitespace()->notEmpty()->matchesPassword($user->password),
            'password' => v::noWhitespace()->notEmpty(),
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->c->router->pathFor('/auth.password.change'));
        }

        $user->setPassword($request->getParam('password'));

        $this->c->flash->addMessage('info', 'Your password was changed.');
        return $response->withRedirect($this->c->router->pathFor('home'));
    }
}
