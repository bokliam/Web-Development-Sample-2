<?php

namespace Noir;

/**
 * View class for the login page
 */
class LoginView extends View {
	/**
	 * LoginView constructor.
	 * @param Site $site Site object
	 */
	public function __construct(Site $site, $cookie) {
		parent::__construct($site);

		$this->setTitle("Ajax Noir Login");

        if(isset($cookie[LOGIN_COOKIE]) && $cookie[LOGIN_COOKIE] != "") {
            $cookie = json_decode($cookie[LOGIN_COOKIE], true);
            $cookies = new Cookies($site);
            $hash = $cookies->validate($cookie['user'], $cookie['token']);
            if($hash !== null) {
                $cookies->delete($hash);
            }

            $expire = time() - 3600;
            setcookie(LOGIN_COOKIE, "", $expire, "/");
        }
	}

	/**
	 * Present the page body
	 * @return string HTML for the page body
	 */
	public function present() {
	    $html = '';

	    if(USER === '????' || PASSWORD === '????') {
	        // Why do I need to put this test here? If you get this message you should
            // certainly be more careful about reading instructions!
            $html .= <<<HTML
<p class="stupid">If you see this text, you have not configured the site correctly. Go back to the 
page <em>Ajax Noir Site</em> and follow the instructions in the red box!</p>
HTML;

        }

		$html .= <<<HTML
<form id="login" class="login" method="post" action="post/login.php">
	<fieldset>
		<p><label for="user">User ID: </label>
			<input type="text" id="user" name="user"></p>
		<p><label for="password">Password: </label>
			<input type="password" id="password" name="password"></p>
		<p class="keep"><input type="checkbox" name="keep" id="keep">
			<label for="keep">Keep me logged on</label></p>
		<p class="buttons"><input type="submit" name="ok" value="Login">
	</fieldset>

	<div class="message"></div>
</form>
HTML;

		return $html;
	}
}