<?php namespace Stevenmaguire\Foundation;

use Illuminate\Html\HtmlServiceProvider;

class FoundationServiceProvider extends HtmlServiceProvider 
{
	
    /**
     * Register the form builder instance.
     *
     * @return void
     */
    protected function registerFormBuilder()
    {
        $this->app['form'] = $this->app->share(function($app)
        {            
            $form = new FormBuilder(
                $app['html'], 
                $app['url'], 
                $app['session']->getToken(), 
                $app['translator']);
            return $form->setSessionStore($app['session.store']);
        });
    }
}