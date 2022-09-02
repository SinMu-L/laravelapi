# 练习 laravel API

- [x] 数据填充

- [x] 导航栏查询

- [ ] 导航栏修改

- [x] 导航栏删除

- [x] 导航栏添加

-----

需求分析
1. 需要默认是API访问，web不可访问
2. 数据库操作统一通过迁移文件完成，以便数据库统一

-----
我遇到的问题
1. 为啥这2个请求是同样的结果(都是 category的list结果)，delete请求不应该返回error么？
    ```curl
    curl --location --request DELETE 'laravelapi.test/api/v1/category/'
    curl --location --request GET 'laravelapi.test/api/v1/category/'

    # 这个是我的路由
    Route::prefix('v1')->name('api.v1.')->group(function() {
        Route::get('category', [CategoryController::class,'show'])->name('category.index');

        Route::post('category',[CategoryController::class,'store']);
        Route::delete('category/{uuid}',[CategoryController::class,'destroy']);
    });
    ```
> 被截胡了，将两个路由更换位置即可 

```php
    Route::prefix('v1')->name('api.v1.')->group(function() {
        Route::delete('category/{uuid}',[CategoryController::class,'destroy']);
        Route::get('category', [CategoryController::class,'show'])->name('category.index');

        Route::post('category',[CategoryController::class,'store']);
    });
```




2. 我每个请求都有错误返回，有没有是么办法可以统一设置我的错误处理或者统一错误返回结果
> 这个值得一试：https://learnku.com/docs/laravel-api-dev/8.0/unified-interface-return-value-processing/9532
> 
> 也可以直接添加中间件 设置请求头 Accept:application/json

3.  定义这样的路由 `api/v1/category/{category}` 。

    我的请求是 `api/v1/category/:id` ,其中id是我的自增主键，由于隐式模型绑定，CategoryController可以识别出来 category 的一个对象。
    
    但是当我的请求是 `api/v1/category/:uuid` ,其中 uuid 是我表中的一个唯一id，传递到CategoryController不能被识别出来，必须通过路由参数传递到控制器里面，通过DB查询一次。

    我的期望结果是。有没有什么办法 让我可以请求 `api/v1/category/:uuid`，然后在 `CategoryController`里面写这个参数 `CategoryController(Category category)`,而不是 `CategoryController($uuid)`

> 这里可以通过[自定义解析逻辑](https://learnku.com/docs/laravel/9.x/routing/12209#a3b485)来解决问题
> 
> 看文档一定要仔细！！！




---


4. 当前我换成下面的路由时，我的 destroy 方法里面dd的结果 和 我写的 category.index 一致
> 我自定义了解析逻辑
> model下面加了这个
```php
// 自定义解析逻辑
    public function resolveRouteBinding($value, $field = null)
    {
        if(!$field){
            $field = 'uuid';
        }
        return $this->where($field, $value)->firstOrFail();
    }
```


```php
Route::prefix('v1')->name('api.v1.')->group(function() {
    Route::delete('category/{category}',[CategoryController::class,'destroy']);
    Route::get('category',[CategoryController::class,'index'])->name('category.index');
    Route::get('category/{category}', [CategoryController::class,'show'])->name('category.show');

    Route::post('category',[CategoryController::class,'store']);
    Route::put('category/{category}',[CategoryController::class,'update'])->name('category.update');

    // Route::resource('category','CategoryController');
});
```

> 修改了一下 resolveRouteBinding() 方法，默认就是uuid
> 在model里面添加啦 protect $hidden=['id'] 。 这样就可以让返回的json数据不包含id

-----

接下来就是控制权限
category的权限如下
- 无权限的人可以看到 category 的list 和单个的 category 的details
    - 无权限的人是指没有授权 修改、删除、添加的人
- 有权限的人 可以通过 修改、删除、添加 category

laravel中权限如何体现呢？
