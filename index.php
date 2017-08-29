<!DOCTYPE html>
<?php
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$dname = explode('.', $_SERVER['HTTP_HOST'])[1];
$norsk = ($lang == "nb" || $lang == "nn" || $lang == "no" || (isset($_GET['lang']) && substr($_GET['lang'], 0, 2) == "no")) && !(isset($_GET['lang']) && substr($_GET['lang'], 0, 2) == "en");
if($norsk){
  $text = json_decode(file_get_contents("static/js/objects/no-text.json"));
} else {
  $text = json_decode(file_get_contents("static/js/objects/en-text.json"));
}
$js = file_get_contents('static/js/initial.min.js');
$css = file_get_contents('static/css/main.min.css');
$dir = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
preg_match("/\/([\w%]+\/){2,3}([\w%]+)\/?/i", $dir, $output_array);
$ysp = count($output_array)>0;
$location = str_replace("%20"," ",str_replace("%C3%85","Å",str_replace("%C3%98","Ø",str_replace("%C3%86","Æ",str_replace("%C3%A5","å",str_replace("%C3%B8","ø",str_replace("%C3%A6", "æ", $output_array[2])))))))
?>
<html ng-app="app" ng-controller="masterCtrl as master" manifest="/static/manifest.php">

<head>
  <title><?= $text->{'pageTitle'} ?></title>
  <!-- Meta-data -->
  <meta charset="UTF-8">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="apple-mobile-web-app-title" content="<?= $text->{'appName'} ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0">
  <base href="/">
  <!-- href-lang tags: -->
  <? if ($norsk) {?>
  <link rel="alternate" href="<?= $dir ?>?lang=en" hreflang="en">
  <?} else {?>
  <link rel="alternate" href="<?= $dir ?>?lang=no" hreflang="no">
  <?} ?>
  <!-- Icons: -->
  <link rel="apple-touch-icon" href="/static/img/apple-touch-icon.png" />
  <!-- Essential style: -->
  <style><?= $css ?></style>
  <!-- Lazy-loaded stylesheets: -->
  <style ng-bind="master.css"></style>
</head>

<body ng-controller="viewCtrl as view" ontouchstart para-back="/static/img/background.jpg" style="background-image:url(/static/img/background.jpg)">

    <header>
      <h1><?= $text->{'header'} ?></h1>
    </header>

    <spacer></spacer>

    <div class="content">
      <div class="nav-menu">
        <ul class="nav nav-tabs">
          <li ng-class="{'active':master.ifHome()}"><a href="/"><span class="glyphicon glyphicon-screenshot"></span><span class="nav-menu-text"> <?= $text->{'yourLocation'} ?></span></a></li>
          <li ng-class="{'active':master.ifSearch()}" ng-if="master.online"><a href="/search"><span class="glyphicon glyphicon-search"></span><span class="top-bar-text"> <?= $text->{'search'} ?></span></a></li>
          <li ng-if="recent.info.url !== '/'" ng-repeat="recent in master.recents" ng-class="{active:master.ifAt(recent.info.url)}" ng-click="master.goTo(recent.info.url)"><a href><span ng-bind="master.shorten(recent.info.location)"><?= $text->{'clickHere'} ?></span></a></li>
        </ul>
      </div>
      <div ng-view></div>
    </div>


  <lazy ng-if="master.lazyModulesLoaded">
    <div ng-include="'/static/html/cookie-box.php'" ng-controller="cookieCtrl as c"></div>
  </lazy>

  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular.min.js"></script>
  <script>
  !function(e,t,r){"use strict";function n(e,r,n){return{restrict:"ECA",terminal:!0,priority:400,transclude:"element",link:function(a,o,i,c,l){function u(){f&&(n.cancel(f),f=null),h&&(h.$destroy(),h=null),$&&(f=n.leave($),f.then(function(){f=null}),$=null)}function s(){var i=e.current&&e.current.locals;if(t.isDefined(i&&i.$template)){var i=a.$new(),c=e.current;$=l(i,function(e){n.enter(e,null,$||o).then(function(){!t.isDefined(p)||p&&!a.$eval(p)||r()}),u()}),h=c.scope=i,h.$emit("$viewContentLoaded"),h.$eval(d)}else u()}var h,$,f,p=i.autoscroll,d=i.onload||"";a.$on("$routeChangeSuccess",s),s()}}}function a(e,t,r){return{restrict:"ECA",priority:-400,link:function(n,a){var o=r.current,i=o.locals;a.html(i.$template);var c=e(a.contents());if(o.controller){i.$scope=n;var l=t(o.controller,i);o.controllerAs&&(n[o.controllerAs]=l),a.data("$ngControllerController",l),a.children().data("$ngControllerController",l)}n[o.resolveAs||"$resolve"]=i,c(n)}}}e=t.module("ngRoute",["ng"]).provider("$route",function(){function e(e,r){return t.extend(Object.create(e),r)}function r(e,t){var r=t.caseInsensitiveMatch,n={originalPath:e,regexp:e},a=n.keys=[];return e=e.replace(/([().])/g,"\\$1").replace(/(\/)?:(\w+)([\?\*])?/g,function(e,t,r,n){return e="?"===n?n:null,n="*"===n?n:null,a.push({name:r,optional:!!e}),t=t||"",""+(e?"":t)+"(?:"+(e?t:"")+(n&&"(.+?)"||"([^/]+)")+(e||"")+")"+(e||"")}).replace(/([\/$\*])/g,"\\$1"),n.regexp=new RegExp("^"+e+"$",r?"i":""),n}var n={};this.when=function(e,a){var o=t.copy(a);if(t.isUndefined(o.reloadOnSearch)&&(o.reloadOnSearch=!0),t.isUndefined(o.caseInsensitiveMatch)&&(o.caseInsensitiveMatch=this.caseInsensitiveMatch),n[e]=t.extend(o,e&&r(e,o)),e){var i="/"==e[e.length-1]?e.substr(0,e.length-1):e+"/";n[i]=t.extend({redirectTo:e},r(i,o))}return this},this.caseInsensitiveMatch=!1,this.otherwise=function(e){return"string"==typeof e&&(e={redirectTo:e}),this.when(null,e),this},this.$get=["$rootScope","$location","$routeParams","$q","$injector","$templateRequest","$sce",function(r,a,i,c,l,u,s){function h(e){var n=g.current;(v=(d=f())&&n&&d.$$route===n.$$route&&t.equals(d.pathParams,n.pathParams)&&!d.reloadOnSearch&&!m)||!n&&!d||r.$broadcast("$routeChangeStart",d,n).defaultPrevented&&e&&e.preventDefault()}function $(){var e=g.current,n=d;v?(e.params=n.params,t.copy(e.params,i),r.$broadcast("$routeUpdate",e)):(n||e)&&(m=!1,(g.current=n)&&n.redirectTo&&(t.isString(n.redirectTo)?a.path(p(n.redirectTo,n.params)).search(n.params).replace():a.url(n.redirectTo(n.pathParams,a.path(),a.search())).replace()),c.when(n).then(function(){if(n){var e,r,a=t.extend({},n.resolve);return t.forEach(a,function(e,r){a[r]=t.isString(e)?l.get(e):l.invoke(e,null,null,r)}),t.isDefined(e=n.template)?t.isFunction(e)&&(e=e(n.params)):t.isDefined(r=n.templateUrl)&&(t.isFunction(r)&&(r=r(n.params)),t.isDefined(r)&&(n.loadedTemplateUrl=s.valueOf(r),e=u(r))),t.isDefined(e)&&(a.$template=e),c.all(a)}}).then(function(a){n==g.current&&(n&&(n.locals=a,t.copy(n.params,i)),r.$broadcast("$routeChangeSuccess",n,e))},function(t){n==g.current&&r.$broadcast("$routeChangeError",n,e,t)}))}function f(){var r,o;return t.forEach(n,function(n,i){var c;if(c=!o){var l=a.path();c=n.keys;var u={};if(n.regexp)if(l=n.regexp.exec(l)){for(var s=1,h=l.length;h>s;++s){var $=c[s-1],f=l[s];$&&f&&(u[$.name]=f)}c=u}else c=null;else c=null;c=r=c}c&&(o=e(n,{params:t.extend({},a.search(),r),pathParams:r}),o.$$route=n)}),o||n[null]&&e(n[null],{params:{},pathParams:{}})}function p(e,r){var n=[];return t.forEach((e||"").split(":"),function(e,t){if(0===t)n.push(e);else{var a=e.match(/(\w+)(?:[?*])?(.*)/),o=a[1];n.push(r[o]),n.push(a[2]||""),delete r[o]}}),n.join("")}var d,v,m=!1,g={routes:n,reload:function(){m=!0;var e={defaultPrevented:!1,preventDefault:function(){this.defaultPrevented=!0,m=!1}};r.$evalAsync(function(){h(e),e.defaultPrevented||$()})},updateParams:function(e){if(!this.current||!this.current.$$route)throw o("norout");e=t.extend({},this.current.params,e),a.path(p(this.current.$$route.originalPath,e)),a.search(e)}};return r.$on("$locationChangeStart",h),r.$on("$locationChangeSuccess",$),g}]});var o=t.$$minErr("ngRoute");e.provider("$routeParams",function(){this.$get=function(){return{}}}),e.directive("ngView",n),e.directive("ngView",a),n.$inject=["$route","$anchorScroll","$animate"],a.$inject=["$compile","$controller","$route"]}(window,window.angular);
</script>
  <script src="/static/js/initial.min.js"></script>

</body>

</html>
