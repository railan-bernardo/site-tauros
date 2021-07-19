<?php
ob_start();

require __DIR__ . "/vendor/autoload.php";

/**
 * BOOTSTRAP
 */

use CoffeeCode\Router\Router;
use Source\Core\Session;

$session = new Session();
$route = new Router(url(), ":");
$route->namespace("Source\App");

/**
 * WEB ROUTES
 */
$route->group(null);
$route->get("/", "Web:home");
$route->get("/sobre", "Web:about");

$route->group("/fornecedor");
$route->get("/", "Web:forn");

$route->group("/cadastro");
$route->get("/", "Web:contact");
$route->post("/", "Web:contactPost");

$route->group("/obrigado");
$route->get("/", "Web:comfirm");

//blog
$route->group("/blog");
$route->get("/", "Web:blog");
$route->get("/p/{page}", "Web:blog");
$route->get("/{uri}", "Web:blogPost");
$route->post("/buscar", "Web:blogSearch");
$route->get("/buscar/{search}/{page}", "Web:blogSearch");
$route->get("/em/{category}", "Web:blogCategory");
$route->get("/em/{category}/{page}", "Web:blogCategory");

//PRODUCTS
$route->group("/produtos");
$route->get("/", "Web:products");
$route->get("/p/{page}", "Web:products");
$route->post("/buscar", "Web:productSearch");
$route->get("/buscar/{search}/{page}", "Web:productSearch");


 $route->group(null);
 $route->get("/categoria/{uri}", "Web:allCategory");
 $route->get("/categoria/s/{uri}/{page}", "Web:allCategory");

  $route->group(null);
 $route->get("/marcas/{uri}", "Web:brands");
 $route->get("/marcas/s/{uri}/{page}", "Web:marcas");
// $route->get("/{uri}", "Web:servicePost");
// $route->get("/category/{category}", "Web:serviceCategory");
// $route->get("/category/{category}/{page}", "Web:serviceCategory");
// $route->get("/category/{category}/{subcategory}", "Web:serviceSubCategory");
// $route->get("/category/{category}/{subcategory}/{page}", "Web:serviceSubCategory");

//PRODUCT
$route->group("/produto");
$route->get("/{uri}", "Web:productDetail");
$route->post("/{uri}", "Web:budgetPost");
//$route->get("/{uri}/{page}", "Web:products");

//BUDGET SITE
$route->group("/orcamento");
$route->get("/", "Web:orcament");
$route->post("/post", "Web:budgetPost");
$route->post("/posts", "Web:budgetsPost");

//BUDGET SITE
$route->group("/vagas");
$route->get("/", "Web:jobHome");
$route->post("/post", "Web:jobPost");


//about
$route->group("/about");
$route->get("/", "Web:about");

//about
$route->group("/gallery");
$route->get("/", "Web:gallery");

//Contact
$route->group("/contato");
$route->get("/", "Web:contact");
$route->post("/post", "Web:contactpost");

//auth
$route->group(null);
$route->get("/entrar", "Web:login");
$route->post("/entrar", "Web:login");
$route->get("/cadastrar", "Web:register");
$route->post("/cadastrar", "Web:register");
$route->get("/recuperar", "Web:forget");
$route->post("/recuperar", "Web:forget");
$route->get("/recuperar/{code}", "Web:reset");
$route->post("/recuperar/resetar", "Web:reset");

//optin
$route->group(null);
$route->get("/confirma", "Web:confirm");
$route->get("/obrigado/{email}", "Web:success");

//services
$route->group(null);
$route->get("/termos", "Web:terms");

/**
 * APP
 */
$route->group("/app");
$route->get("/", "App:home");
$route->get("/receber", "App:income");
$route->get("/receber/{status}/{category}/{date}", "App:income");
$route->get("/pagar", "App:expense");
$route->get("/pagar/{status}/{category}/{date}", "App:expense");
$route->get("/fixas", "App:fixed");
$route->get("/carteiras", "App:wallets");
$route->get("/fatura/{invoice}", "App:invoice");
$route->get("/perfil", "App:profile");
$route->get("/assinatura", "App:signature");
$route->get("/sair", "App:logout");

$route->post("/dash", "App:dash");
$route->post("/launch", "App:launch");
$route->post("/invoice/{invoice}", "App:invoice");
$route->post("/remove/{invoice}", "App:remove");
$route->post("/support", "App:support");
$route->post("/onpaid", "App:onpaid");
$route->post("/filter", "App:filter");
$route->post("/profile", "App:profile");
$route->post("/wallets/{wallet}", "App:wallets");

/**
 * ADMIN ROUTES
 */
$route->namespace("Source\App\Admin");
$route->group("/admin");

//login
$route->get("/", "Login:root");
$route->get("/login", "Login:login");
$route->post("/login", "Login:login");

//dash
$route->get("/dash", "Dash:dash");
$route->get("/dash/home", "Dash:home");
$route->post("/dash/home", "Dash:home");
$route->get("/logoff", "Dash:logoff");

//service
$route->get("/produtos/home", "Services:home");
$route->post("/produtos/home", "Services:home");
$route->get("/produtos/home/{search}/{page}", "Services:home");
$route->get("/produtos/post", "Services:post");
$route->post("/produtos/post/img/{img_id}", "Services:post");
$route->post("/produtos/post", "Services:post");
$route->get("/produtos/post/{post_id}", "Services:post");
$route->post("/produtos/post/{post_id}", "Services:post");






$route->get("/produtos/categories", "Services:categories");
$route->get("/produtos/categories/{page}", "Services:categories");
$route->get("/produtos/category", "Services:category");
$route->post("/produtos/category", "Services:category");
$route->get("/produtos/category/{category_id}", "Services:category");
$route->post("/produtos/category/{category_id}", "Services:category");

$route->get("/produtos/subcategories", "Services:subcategories");
$route->get("/produtos/subcategories/{page}", "Services:subcategories");
$route->get("/produtos/subcategory", "Services:subcategory");
$route->post("/produtos/subcategory", "Services:subcategory");
$route->get("/produtos/subcategory/{subcategory_id}", "Services:subcategory");
$route->post("/produtos/subcategory/{subcategory_id}", "Services:subcategory");

$route->get("/produtos/uploads", "Services:uploads");
$route->get("/produtos/uploads/{page}", "Services:uploads");
$route->get("/produtos/upload", "Services:upload");
$route->post("/produtos/upload", "Services:upload");
$route->get("/produtos/upload/{upload_id}", "Services:upload");
$route->post("/produtos/upload/{upload_id}", "Services:upload");




//brands
$route->get("/brand/home", "Brand:home");
$route->post("/brand/home", "Brand:home");
$route->get("/brand/home/{page}", "Brand:home");
$route->get("/brand/post", "Brand:post");
$route->post("/brand/post", "Brand:post");
$route->get("/brand/post/{post_id}", "Brand:post");
$route->post("/brand/post/{post_id}", "Brand:post");




//whyus
$route->get("/about/home", "About:home");
$route->get("/about/home/{page}", "About:home");
$route->get("/about/post", "About:post");
$route->post("/about/post", "About:post");
$route->get("/about/post/{post_id}", "About:post");
$route->post("/about/post/{post_id}", "About:post");

$route->get("/about/img/delete/{img_id}", "About:img");
$route->post("/about/img/delete/{img_id}", "About:img");

//footer
$route->get("/footer/home", "SiteInfo:home");
$route->get("/footer/home/{page}", "SiteInfo:home");
$route->get("/footer/post", "SiteInfo:post");
$route->post("/footer/post", "SiteInfo:post");
$route->get("/footer/post/{post_id}", "SiteInfo:post");
$route->post("/footer/post/{post_id}", "SiteInfo:post");

//contact
$route->get("/contact/home", "Contact:home");
$route->get("/contact/home/{page}", "Contact:home");
$route->post("/contact/post/{post_id}", "Contact:post");

//budget admin
$route->get("/orcamento/home", "Orcament:home");
$route->get("/orcamento/home/{page}", "Orcament:home");
$route->get("/orcamento/post", "Orcament:post");
$route->post("/orcamento/post", "Orcament:post");
$route->get("/orcamento/post/{post_id}", "Orcament:post");
$route->post("/orcamento/post/{post_id}", "Orcament:post");

//vacancy admin
$route->get("/candidatos/home", "Vacancy:home");
$route->get("/candidatos/home/{page}", "Vacancy:home");
$route->get("/candidatos/post", "Vacancy:post");
$route->post("/candidatos/post", "Vacancy:post");
$route->get("/candidato/post/{post_id}", "Vacancy:post");
$route->post("/candidato/post/{post_id}", "Vacancy:post");

//banner admin
$route->get("/slide/home", "Banner:home");
$route->get("/slide/post", "Banner:posts");
$route->post("/slide/post", "Banner:posts");
$route->get("/slide/post/{post_id}", "Banner:posts");
$route->post("/slide/post/{post_id}", "Banner:posts");

//fornecedor admin
$route->get("/fornecedor/home", "Supplie:home");
$route->get("/fornecedor/post", "Supplie:post");
$route->post("/fornecedor/post", "Supplie:post");
$route->get("/fornecedor/post/{post_id}", "Supplie:post");
$route->post("/fornecedor/post/{post_id}", "Supplie:post");

//banner 
$route->get("/banner/home", "Pub:home");
$route->get("/banner/post", "Pub:posts");
$route->post("/banner/post", "Pub:posts");
$route->get("/banner/post/{post_id}", "Pub:posts");
$route->post("/banner/post/{post_id}", "Pub:posts");

//users
$route->get("/users/home", "Users:home");
$route->post("/users/home", "Users:home");
$route->get("/users/home/{search}/{page}", "Users:home");
$route->get("/users/user", "Users:user");
$route->post("/users/user", "Users:user");
$route->get("/users/user/{user_id}", "Users:user");
$route->post("/users/user/{user_id}", "Users:user");

//notification center
$route->post("/notifications/count", "Notifications:count");
$route->post("/notifications/list", "Notifications:list");

//END ADMIN
$route->namespace("Source\App");

/**
 * PAY ROUTES
 */
$route->group("/pay");
$route->post("/create", "Pay:create");
$route->post("/update", "Pay:update");

/**
 * ERROR ROUTES
 */
$route->group("/ops");
$route->get("/{errcode}", "Web:error");

/**
 * ROUTE
 */
$route->dispatch();

/**
 * ERROR REDIRECT
 */
if ($route->error()) {
    $route->redirect("/ops/{$route->error()}");
}

ob_end_flush();