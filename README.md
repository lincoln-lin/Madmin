# meizu/yii2-admin

开始本项目前强烈建议完整阅读一遍Yii2的官方文档：http://www.yiiframework.com/doc-2.0/guide-README.html

## 新建项目
可以复制此项目来创建一个新项目，复制代码后，执行如下步骤创建表并初始化数据即可：
```
php yii migrate --migrationPath=@yii/rbac/migrations --interactive=0
php yii migrate --migrationPath=@yii/log/migrations --interactive=0
php yii migrate --migrationPath=@backend/migrations --interactive=0
```

运行如下命令启动服务（当然也可以使用 nginx 或者 apache，本文档不做介绍）：
```
php yii serve -t=@frontend/web -p=4001 127.0.0.1
php yii serve -t=@backend/web -p=4002 127.0.0.1
```

访问项目前台：http://127.0.0.1:4001/
访问项目后台：http://127.0.0.1:4002/

http basic auth
帐号：meizu
密码：meizu

超级管理员
帐号：admin@meizu.com
密码：asdfqwWERxx

## 目录介绍&编码规范
backend 为后台相关
common 存放前后台以及控制台程序通用的东西
console 控制台程序（CLI），计划任务的脚本应该写在这个目录下面
frontend 为前台相关
resource 存放项目相关的文档等非代码数据
yii 控制台程序的入口文件
yii.bat 针对 Windows 系统的控制台程序的入口文件
init 初始化配置文件的脚本
init.bat 针对 Windows 系统的初始化配置文件的脚本
environments 存放存在不同环境的配置文件，供 init 初始化配置的时候使用
tests 存放测试相关的代码数据文件
Vagrantfile vagrant 配置文件
vagrant 存放 vagrant 相关文件
vendor 为 composer 自动生成的依赖目录，请勿更改该目录下任何文件
composer.json composer 相关的文件，请勿动
composer.lock composer 相关的文件，请勿动
requirements.php 执行该文件可以检测系统 PHP 环境是否满足运行 Yii2

对于日常开发来说，我们只需关注 backend（前台）、frontend（后台）、console（命令行）以及 common 这四个目录，backend（前台）、frontend（后台）、console（命令行）是三个独立互不依赖的程序，如果项目只需要进行后台开发，那么只在 backend 目录下进行代码编写就行，如果代码同时被多个程序（如同样的 Model ，backend 和 frontend 都需要），那么可以将这块代码放在 common 目录下。

backend,frontend,console 三个目录的文件结构是一致的，主要目录介绍如下：
assets 前端静态资源相关的目录
controllers 控制器目录
models 模型目录
views 视图目录
web index.php 存放在此，为 webroot 入口目录
runtime 运行时文件夹，存放 Yii2 运行过程中生成的文件

### 规范
开发和线上不一样的配置（比如 DB，Redis 相关配置）统一配置在 main-local.php 里面
以 frontend（前台）为例，阅读 frontend/web/index.php 可以看到
```
$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/main-local.php')
);
```
下面的会覆盖上面的同名配置。
如果是公共配置请在 common/config 下进行配置，frontend(前台）独有的配置放在 frontend/config 目录下。

## Q&A

#### http basic auth 认证相关代码是在哪里实现的？

是在 \backend\controllers\BaseController::init() 里面实现的逻辑，具体实现原理就是监听了 EVENT_BEFORE_ACTION 的事件，会在执行 Action 之前检查 http basic auth。

#### 权限校验是在哪里检查的？

\backend\controllers\BaseController::init() 会根据当前 Controller 的 needPermission 属性值确定是否 attach 相应 Behavior (\backend\components\AccessControl)，\backend\components\AccessControl 具体原理就是监听控制器的 EVENT_BEFORE_ACTION （在执行 Action 之前会触发该事件） ，判断当前登录用户是否有权限访问对应的 Action 。

#### 如何配置某些路由不受权限管理（所有用户都可以访问）？

有两种方法，第一种是 Controller 继承 BaseController 的时候，needPermission 设置为 false，那么该 Controller 的下的所有 Action 都默认不受权限校验限制。第二种是配置 params.php 这个配置文件，（例如，/backend/config/params.php）
\backend\components\AccessControl 会调用 mz\admin\Helper::checkRoute 检查用户是否具有某路由的权限，查看该方法的具体实现代码可以看到会读取 Yii::$app->params 这个数组里面键 'mz.admin.allowActions' 的值，如果当前访问路由在这个数组里面，则所有用户都可以访问。

#### 调用 Model->save() 方法后却没有成功保存到数据库？

有两种可能，第一种，请查看 log 是否存在 Failed to set unsafe attribute 的错误，出现这个错误是因为该 Model 的 rules 配置使得对应的 attribute 不是 safe，那么调用 Model->load 就不会设置该 attribute 的值，save() 的时候自然就没有保存相关值了。
第二种，请查看 log 是否存在 Model not inserted due to validation error 或 Model not updated due to validation error 错误，出现这个错误是因为，save() 的时候，存在 attribute 没有通过 rules 里面的某些规则验证。
Yii2官方文档：http://www.yiiframework.com/doc-2.0/guide-structure-models.html#validation-rules

#### 某某逻辑是在哪里实现的

大多时候Yii2的控制器里面只是类似
```
$model->load(Yii::$app->request->post()) && $model->save()
```
的简单代码，并没有具体的业务实现。请查看对应 Model 的 beforeValidate、beforeSave、save、afterSave 方法，不出意外具体业务逻辑都是在这几个方法里面实现的。

#### 我的某一业务逻辑应该写在哪里？

比如我有一组很复杂的逻辑，设计到多个基本 Model ，这个时候建议可以单独写一个类去处理这些逻辑，假设我有一个游戏，我可以创建 app\service\GameService 类，里面实现具体游戏业务逻辑。
当然，框架并没有限定你把代码写在哪里，实际上只能被访问到的写在哪里都是可以的。但一般我们会根据实际情况来，如果某逻辑只与单条记录相关，那我们完全可以直接写在 Model 里面。如果 Model A 在插入之后需要新增一个 Model B ，类似于这种情况我们可以在 Model A 的 afterSave 里面去进行处理。
一般来说，Controller（控制器）里面尽量少写或不写业务逻辑，逻辑都写在 Model 或者其他的 Class（类）里面，这样至少有两点好处，一是方便代码复用，二是方便代码测试。

#### 后台如何 ajax Post 数据
mz.js 里面封装了一个函数 mz.post
调用 mz.post(url, {'k':'v'}) 会返回一个 jqXhr 的对象，也可以省略第一个参数，默认是当前页面URL，示例代码
```
mz.post(url, {'k':'v'])
    .done(function(body){
        if (json.code == 200) {
            alert('操作成功');
        } else {
            alert('操作失败：'+json.message);
        }
    })
```

对应请求的 PHP 代码可以这么写
```
Yii::$app->response->format = 'json'; // 说明返回 json
return [
    'code' => 100,
    'message' => '因为你太帅，代码拒绝执行',
];
```

#### GridView 操作列如何增加自定义的按钮

请参考已有代码的写法即可，可以深入阅读 GridView 的源码，里面有注释以及示例代码。

#### 后台支持哪些配置

后台支持的配置都在 backend/config/params.php 列出来了，如无必要，不用进行修改。

#### 其它说明的点

如果用户拥有 /user 的权限，那么同时也拥有 /user/index , /user/create 的权限
