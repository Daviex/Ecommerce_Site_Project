//Load a web page with a fade animation
function loadPageWithFX(pageToLoad) {
    $("#section").fadeOut('fast', function() {
        $("#section").load(pageToLoad);
        $("#section").fadeIn('fast');
    });
}

function reloadPageWithFX(pageToLoad) {
    $("#section").fadeOut('fast', function() {
        window.location.href = pageToLoad;
        $("#section").fadeIn('fast');
    });
}

function loadPageWithFXAjax(msg) {   $("#section").fadeOut('fast', function() { $("#section").html(msg); $("#section").fadeIn('fast'); });   }

//INDEX FUNCTIONS
function homepage()             {   loadPageWithFX("homepage.php");         }
function logout()               {   loadPageWithFX("../logout.php");        }

function manageOrders()         {   loadPageWithFX("manageOrders.php");         }
function manageDiscounts()      {   loadPageWithFX("manageDiscount.php");       }
function manageReviews()        {   loadPageWithFX("editReview.php");           }
function updateQuantity()       {   loadPageWithFX("updateQuantity.php");       }
