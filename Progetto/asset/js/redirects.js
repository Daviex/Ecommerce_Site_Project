//Load a web page with a fade animation
function loadPageWithFX(pageToLoad) {   $("#section").fadeOut('fast', function() { $("#section").load(pageToLoad); $("#section").fadeIn('fast'); });   }
function loadPageWithFXAjax(msg) {   $("#section").fadeOut('fast', function() { $("#section").html(msg); $("#section").fadeIn('fast'); });   }

//INDEX FUNCTIONS
function homepage()             {   loadPageWithFX("homepage.php");             }
function registration()         {   loadPageWithFX("registration.php");         }
function login()                {   loadPageWithFX("login.php");                }
function logout()               {   loadPageWithFX("logout.php");               }
function myProfile()            {   loadPageWithFX("profile/profile.php");      }

//PRODUCTS FUNCTIONS
function section(idSect)        {   loadPageWithFX("section/section.php?idSect="+idSect);       }
function category(idCat)        {   loadPageWithFX("section/category.php?idCat="+idCat);        }
function product(idProd)        {   loadPageWithFX("products/product.php?idProd="+idProd);      }
function addReview(idProd)      {   loadPageWithFX("products/addReview.php?idProd="+idProd);    }
function removeReview(idProd)   {   loadPageWithFX("products/removeReview.php?idProd="+idProd); }

//PROFILE FUNCTIONS
function myOrders()             {	loadPageWithFX("profile/orders.php");   }
function myCart()               {   loadPageWithFX("profile/cart.php");         }
function updateCart(IDProd, Quantity)     {   loadPageWithFX("products/updateCart.php?IDProd="+IDProd+"&Quantity="+Quantity);   }
function removeCart(IDProd)     {   loadPageWithFX("products/removeCart.php?IDProd="+IDProd);   }
function checkoutCart()         {   loadPageWithFX("profile/checkout.php");     }
function confirmCheckout(IDCorr)    {   loadPageWithFX("profile/confirmCheckout.php?IDCorr="+IDCorr);  }