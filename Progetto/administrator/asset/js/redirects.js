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

function insertProd()           {   loadPageWithFX("insert/insertProduct.php");    }
function insertCat()            {   loadPageWithFX("insert/insertCategory.php");   }
function insertSec()            {   loadPageWithFX("insert/insertSection.php");    }
function insertState()          {   loadPageWithFX("insert/insertState.php");      }
function insertExpress()        {   loadPageWithFX("insert/insertExpress.php");    }

function manageProd()           {   loadPageWithFX("manage/manageProduct.php");    }
function manageCat()            {   loadPageWithFX("manage/manageCategory.php");   }
function manageSec()            {   loadPageWithFX("manage/manageSection.php");    }
function manageUserRank()       {   loadPageWithFX("manage/manageUserRank.php");   }
function manageState()          {   loadPageWithFX("manage/manageState.php");      }
function manageExpress()        {   loadPageWithFX("manage/manageExpress.php");    }

function deleteProd()           {   loadPageWithFX("delete/deleteProduct.php");    }
function deleteCat()            {   loadPageWithFX("delete/deleteCategory.php");   }
function deleteSec()            {   loadPageWithFX("delete/deleteSection.php");    }
function deleteState()          {   loadPageWithFX("delete/deleteState.php");      }
function deleteExpress()        {   loadPageWithFX("delete/deleteExpress.php");    }
