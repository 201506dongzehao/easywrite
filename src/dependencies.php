<?php
// DIC configuration  我们可以把我们需要的服务[需要使用的类都放到这个容器中, 然后在 路由中 可以通过 $this来直接调用]
use Controller\admin\Factory;
use Illuminate\Pagination\LengthAwarePaginator;

require '../vendor/autoload.php';

LengthAwarePaginator::viewFactoryResolver(function(){
    return new Factory;
});

LengthAwarePaginator::defaultView('/pagination/page.phtml');

$container = $app->getContainer();  //实例化DIC Dependency Injection Container 依赖注册容器

$container['csrf'] = function ($c) {
    return new \Slim\Csrf\Guard;
};



//注册模板视图  $c指$container本身
$container['view'] = function ($c) {
	$settings = $c->get('settings')['render']; //获取 配置 文件中的信息
    // $templateVariables = ["var" => "所有的视图都可以用此变量"];  //此变量在所有的视图里面都可以用
    return Factory::getEngine();
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};
//PDO 数据库连接 定义  
$container['db'] = function($c){
	$db = $c["settings"]["db"];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],$db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$pdo->exec("SET names 'utf8'");
    return $pdo;
};


