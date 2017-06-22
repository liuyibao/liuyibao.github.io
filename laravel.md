## laravel日常使用

##### laravel安装器
```
composer global require "laravel/installer"
laravel new blog
```

##### composer create-project
```
composer create-project laravel/laravel your-project-name --prefer-dist "5.1.*"
```


##### 查询构造器 Illuminate\Database\Eloquent\Model::newQuery()
```$xslt
$builder = $this->trailer->newQuery();
foreach($movieCollection as $key=>$obj) {
    if ($key > 0) {
        $builder->orWhere([
            ['provider_id', '=', $obj->provider_id],
            ['provider_movie_id', '=', $obj->provider_movie_id],
        ]);
    } else {
        $builder->where([
            ['provider_id', '=', $obj->provider_id],
            ['provider_movie_id', '=', $obj->provider_movie_id],
        ]);
    }
}
$trailerCollection = $builder->get();
```


##### 打印SQL
```
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DB::listen(function($sql, $bindings, $time) {
            echo $sql, "\n";
            print_r($bindings);
            echo $time, "\n";
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
```

##### queue:work & queue:listen

The most important difference is that queue:work –daemon does not restart the framework on each job, but queue:listen does. In fact, listen starts a whole new Laravel process for each job.

Try for yourself: Open 2 terminals and run work –daemon in one and listen in the other. The work window will execute jobs much faster than listen.

- [stackoverflow.com](http://stackoverflow.com/questions/26048698/what-is-the-difference-between-queuework-daemon-and-queuelisten)
- [reddit.com](https://www.reddit.com/r/laravel/comments/5955q1/queuework_vs_queuelisten/?st=iztkq6cg&sh=087c155b)