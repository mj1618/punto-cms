<?php namespace App\AUI\Controllers;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use MJ1618\AdminUI\Controller\Menu;
use Zizaco\Entrust\Entrust;

class AUIMenu implements Menu{


	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function getMenu()
	{
        $items = [];
        $menus = [];

        if(Auth::user()!==null && Auth::user()->hasRole('editor')){
            $items = [
                [
                    'name'=>'Edit Content',
                    'subItems'=>[
                        [
                            'name'=>'Edit Pages',
                            'url'=>'/admin/edit-pages'
                        ]
                    ]
                ]
            ];

            $menus["editor"]=['header'=>'EDITOR MENU','items'=>$items];
        }
        if(Auth::user()!==null && Auth::user()->hasRole('developer')){
            $items = [
                [
                    'name'=>'Structure',
                    'subItems'=>[
                        [
                            'name'=>'Pages',
                            'url'=>'/admin/pages'
                        ],
                        [
                            'name'=>'Templates',
                            'url'=>'/admin/templates'
                        ],
                        [
                            'name'=>'Feeds',
                            'url'=>'/admin/feeds'
                        ],
//                        [
//                            'name'=>'Develop Templates',
//                            'url'=>'/admin/develop-templates'
//                        ],
                    ]
                ]
            ];

            $menus["developer"]=['header'=>'DEVELOPER MENU','items'=>$items];
        }
        if(Auth::user()!==null && Auth::user()->hasRole('admin')){
            $items = [
                [
                    'name'=>'Users',
                    'subItems'=>[
                        [
                            'name'=>'Users',
                            'url'=>'/admin/users'
                        ]
                    ]
                ]
            ];

            $menus["admin"]=['header'=>'ADMIN MENU','items'=>$items];
        }

        return $menus;

	}

}
