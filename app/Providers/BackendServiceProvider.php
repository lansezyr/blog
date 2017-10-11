<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use MenuRepository;
use Auth;
class BackendServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        view()->composer('*', function ($view) {
            //共享菜单数据
            $menus = MenuRepository::index();
            $view->with('menus',$menus);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('UserRepository', function($app){
            return new \App\Repositories\admin\UserRepository();
        });

        $this->app->singleton('PermissionRepository', function($app){
            return new \App\Repositories\admin\PermissionRepository();
        });
        $this->app->singleton('RoleRepository', function($app){
            return new \App\Repositories\admin\RoleRepository();
        });
        $this->app->singleton('MenuRepository', function($app){
            return new \App\Repositories\admin\MenuRepository();
        });
        $this->app->singleton('CategoryRepository', function($app){
            return new \App\Repositories\admin\CategoryRepository();
        });
        $this->app->singleton('TagRepository', function($app){
            return new \App\Repositories\admin\TagRepository();
        });
        $this->app->singleton('ArticleRepository', function($app){
            return new \App\Repositories\admin\ArticleRepository();
        });
        $this->app->singleton('AlbumRepository', function($app){
            return new \App\Repositories\admin\AlbumRepository();
        });
        $this->app->singleton('PhotoRepository', function($app){
            return new \App\Repositories\admin\PhotoRepository();
        });

    }
}
