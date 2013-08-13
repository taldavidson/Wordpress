/*
 * doubleSuggest
 *
 * @Version: 0.1a
 * @Author: hernantz 
 * @Url: http://www.github.com/hernantz/doubleSuggest
 * @License: MIT - http://www.opensource.org/licenses/mit-license.php
 *
 * This jQuery plugin will set up a UI that suggest results as you type. 
 * It will display two types of suggestions, first (and faster) the local data 
 * and also the results from an ajax search query. 
 * Requires jQuery > v.1.7
 */
(function(a){var h={init:function(d){return this.each(function(){function h(c){c&&clearTimeout(c);c=setTimeout(function(){t()},b.keyDelay)}function t(){var c=a.trim(e.val()).replace(/[\\]+|[\/]+/g,"").replace(/\s+/g," ");j=c;if(c.length>=b.minChars){b.beforeRetrieve&&(c=b.beforeRetrieve.call(this,c));e.addClass("loading");b.loadingText&&g.html('<li class="ds-message">'+b.loadingText+"</li>").show();f.show();if(b.remoteSource){m&&m.abort();var l={};l[b.queryParam]=c;m=a.getJSON(b.remoteSource,a.extend({},
l,b.extraParams),function(b){n(b,c,!1)})}b.localSource&&n(b.localSource,c,!0)}else f.hide()}function q(c){if(0<a("li.ds-result-item:visible",f).length){var l=a("li",f),d="down"===c?l.eq(0):l.filter(":last"),g=a("li.active:first",f);0<g.length&&(d="down"===c?g.next():g.prev());l.removeClass("active");d.addClass("active");0<d.length&&b.onResultFocus.call(e,d.data());c=0<d.length?d.data()[b.selectValue]:j;e.val(c)}}function n(c,d,h){var c=b.retrieveComplete.call(this,c,d,h),j=b.seekValue.split(","),
o=0,k=0;h&&(g.html(""),f.hide());for(var m in c)if(c.hasOwnProperty(m)){str="";for(var i=0;i<j.length;i++)str+=c[k][a.trim(j[i])];b.matchCase||(str=str.toLowerCase(),d=d.toLowerCase());if(-1!==str.search(d)){c[k]._dataSource=h?"local":"remote";c[k]._number=o;var i=a('<li class="ds-result-item" id="ds-result-item-'+k+'"></li>').data(c[k]),p=a.extend({},c[k]),n=RegExp("(?![^&;]+;)(?!<[^<>]*)("+d+")(?![^<>]*>)(?![^&;]+;)",""+(!b.matchCase?"gi":"g")+"");b.resultsHighlight&&(p[b.selectValue]=p[b.selectValue].replace(n,
"<em>$1</em>"));i=b.formatList?b.formatList.call(e,p,i):i.html(p[b.selectValue]);g.append(i);o++;if(b.queryLimit&&b.queryLimit==o)break}k++}e.removeClass("loading");0>=o&&b.emptyText&&g.html('<li class="ds-message">'+b.emptyText+"</li>");f.show();b.resultsComplete.call(this)}var b=a.extend({},a.fn.doubleSuggest.defaultOptions,d),e=a(this).addClass("ds-input"),r=e.attr("id"),s=a('<div class="ds-container" id="ds-container-'+r+'"></div>');e.wrap(s);var f=a('<div class="ds-results" id="ds-results-'+
r+'"></div>').hide();e.after(f);var g=a('<ul class="ds-list"></ul>').css("width",e.outerWidth()).appendTo(f),j="",m=null;e.on({"focus.doubleSuggest":function(){""!==a.trim(e.val())&&f.show()},"keydown.doubleSuggest":function(c){lastKey=c.keyCode;switch(lastKey){case 38:case 40:c.preventDefault();38===lastKey?q("up"):q("down");break;case 8:1===e.val().length&&f.hide();h(lastKey,null);break;case 9:case 188:case 13:var d=a.trim(e.val()).replace(/(,)/g,"");if(""!==d&&d.length>=b.minChars&&(9===lastKey||
13===lastKey)&&0<a("li.ds-result-item:visible",f).length&&0<a("li.active:first",g).length)a("li.active:first",g).trigger("select"),c.preventDefault();break;default:46==lastKey||9<lastKey&&32>lastKey?f.hide():h(null)}},"blur.doubleSuggest":function(){false||(a("li.as-selection-item",s).addClass("blur").removeClass("selected"),f.hide())},"updateOptions.doubleSuggest":function(c,e){b=a.extend(a.fn.doubleSuggest.defaultOptions,d,e)},"destroy.doubleSuggest":function(){f.remove();e.val("").removeClass("ds-input").unbind(".doubleSuggest").unwrap()}});
f.on({click:function(){a(this).trigger("select")},mouseover:function(){a("li",g).removeClass("active");a(this).addClass("active");b.onResultFocus.call(e,a(this).data())},select:function(){var c=a(this),d=c.data();j=d[b.selectValue];e.val(j).focus();b.onSelect.call(e,d,c);f.hide()}},".ds-result-item")})},update:function(d){return this.each(function(){a(this).trigger("updateOptions",d)})},destroy:function(){return this.each(function(){a(this).trigger("destroy")})}};a.fn.doubleSuggest=function(d){if(h[d])return h[d].apply(this,
Array.prototype.slice.call(arguments,1));if("object"===typeof d||!d)return h.init.apply(this,arguments);a.error("Invalid arguments "+d+" on jQuery.doubleSuggest")};a.fn.doubleSuggest.defaultOptions={localSource:!1,remoteSource:!1,emptyText:!1,loadingText:"Loading...",newItem:!1,selectValue:"name",seekValue:"name",queryParam:"q",queryLimit:!1,extraParams:{},matchCase:!1,minChars:1,keyDelay:500,resultsHighlight:!0,onSelect:function(){},onResultFocus:function(){},formatList:!1,beforeRetrieve:function(a){return a},
retrieveComplete:function(a){return a},resultsComplete:function(){}}})(jQuery);