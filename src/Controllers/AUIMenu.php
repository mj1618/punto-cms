<?php namespace App\AUI\Controllers;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use MJ1618\AdminUI\Controller\Menu;
use Zizaco\Entrust\Entrust;
use Config;

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
            $si=[];
            $si[] =
                [
                    'name'=>'Manage Page Content',
                    'url'=>'/admin/manage-pages'
                ];

            if(Config::get('punto-cms.store')===true){

                $si[] = [
                    'name'=>'Store Products',
                    'url'=>'/admin/store-products'
                ];

                $si[] = [
                    'name'=>'Store Product Pricing',
                    'url'=>'/admin/store-types'
                ];

                $si[] = [
                    'name'=>'Store Product Categories',
                    'url'=>'/admin/store-categories'
                ];
            }

            $items = [
                [
                    'name'=>'Edit Content',
                    'subItems'=> $si

                ]
            ];

            $menus["editor"]=['header'=>'EDITOR MENU','items'=>$items];
        }
        if(Auth::user()!==null && Auth::user()->hasRole('developer')){
            $items = [
                [
                    'name'=>'Structure',
                    'subItems'=>[
//                        [
//                            'name'=>'Pages',
//                            'url'=>'/admin/pages'
//                        ],
                        [
                            'name'=>'Pages',
                            'url'=>'/admin/edit-pages'
                        ],
                        [
                            'name'=>'Templates + Structure',
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
