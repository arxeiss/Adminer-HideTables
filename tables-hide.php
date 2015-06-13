<?php

/** 
 * Enable hiding tables in menu list
 *
 * Install to Adminer on http://www.adminer.org/plugins/
 * @author Pavel Kutáč, http://www.kutac.cz/
 * 
 * Filter inspiration by Jakub Vrana: https://raw.githubusercontent.com/vrana/adminer/master/plugins/tables-filter.php
 * 
 */
class AdminerTablesHide {
  	
	function tablesPrint($tables) {
		?>
		<style>
		  p.toggleTableVisible{
		    cursor: pointer;
	    }
		  #menu.hiddenVisible .hideT{
		    display: inline;
	    }
		  .hideT, #tables span.hiddenTable, #menu.hiddenVisible .showT{
		    display: none;
	    }
		  #menu.hiddenVisible #tables span.hiddenTable{
		    display: inline;
		    opacity: 0.5;
		  }		  
		  #tables span.filtered, #menu.hiddenVisible #tables span.hiddenTable.filtered{
		    display: none;
		  }
		  #tables a.toggleVisible{
		    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA0AAAAKCAYAAABv7tTEAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAANJJREFUeNp00cEGAkEcx/HYpEN0jei0RKfouqceoOsSsW8QEd16lD31DBG9QDp1WmIpS8QSER2yfSe/ydj057N27fxnfjPjVf5XF7neW9iijp07qIEpDijkiRESZPDdBvNx1MAUYwwQqOGECXq2oa0YpuGGDuYYaiLTEOGBq11t7cSJNXukFVNNEjtjNlUeNSemhzNWeGGpJD/la9k7QsVJFG9Rip9r5U/1tXnbEOggQkUs9M8v30mmhlBHbfdwwQzNckRzWntdot3boLTfb70FGACGeTlEq+2nVAAAAABJRU5ErkJggg==);
		    display: inline-block;
		    height: 10px;
		    visibility: hidden;
		    width: 13px;
	    }
		  #tables span.hiddenTable a.toggleVisible{
		    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA0AAAAKCAYAAABv7tTEAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAALZJREFUeNpiZsAEokCsCcS+UP4fIP7GgAPoAvEKIP4Pxd+Q2Cug8iggBEnROqhtICAB5cMMCYFpsIc6ASRxD4j5gfgwlA+ieYD4BpT/B6qe4TaSM2YCcSAS/z+UPxOJf5sJSCxDcqYcEF8D4l9Q/i8oXxpJDVg9GxBvQbI+BOrpNCjth+T8LVD1YMAMxF1IgQGSnIZk2DeoPEgdAyOWOEoAYhUgVgfim0B8B4gXAPFrBkoAQIABAO6KOXvUtxGwAAAAAElFTkSuQmCC);
	    }
		  #tables.min a.toggleVisible{
		    visibility: visible;
      }
		</style>
		<script type="text/javascript">
		var menuBlock = qs("#menu"), hiddenTables = [], currentDatabase = "<?php echo $_GET[DRIVER]."-".$_GET["db"]; ?>";
		/**
		 * Short for querySearchAll
		 * @param string search Query selector
		 * @param HTMLElement root Element to search for
		 * @returns array All matching elements
		 */
		function qsa(search, root){
		  root = root || document;
		  return root.querySelectorAll(search)
		}
		/**
		 * Short for querySearchAll
		 * @param string search Query selector
		 * @param HTMLElement root Element to search for
		 * @returns HTMLElement First matching element
		 */
		function qs(search, root){
		  root = root || document;
		  return root.querySelector(search)
		}
		/**
		 * Add class to element
		 * @param HTMLElement element Element to add class
		 * @param string cls Class name to add
		 */
		function addClass(element, cls){
		  if(!hasClass(element, cls)){
		    element.className += " " + cls;
		  }
		}
		/**
		 * Remove class from element
		 * @param HTMLElement element Element to remove class
		 * @param string cls Class name to remove
		 */
		function removeClass(element, cls){
		  element.className = element.className.replace(new RegExp("(\\s)*" + cls + "(\\s)*","g")," ");
		}
		/**
		 * Check if element has class
		 * @param HTMLElement element Element check if has class
		 * @param string cls Class name
		 * @returns bool If element has class
		 */
		function hasClass(element, cls){
		  return new RegExp('(\\s)*' + cls + '(\\s)*',"g").test(element.className);
		}
		/**
		 * Click on button for hide/show hidden button
		 */
		function bclick(){
		  if(hasClass(menuBlock,"hiddenVisible")){
		    removeClass(menuBlock,"hiddenVisible")
		  }else{
		    addClass(menuBlock,"hiddenVisible")
		  }
		}
		/**
		 * Toggle visibility of table and save to cookie
		 * @param HTMLElement target Clicked element
		 * @return bool False if clicked to correct element to toggle table visibility
		 */
		function toggleVisible(target){
		  var parent = target.parentElement, tableName = currentDatabase, hashPosition = target.href.indexOf("#");
		  if(hashPosition < 1){
		    return true;
		  }
		  tableName += "-" + target.href.substring(hashPosition + 1);
		  if(hasClass(parent, "hiddenTable")){
		    removeClass(parent,"hiddenTable");
		    var tableIndex = inTables(tableName);
		    if(tableIndex > -1){
		      hiddenTables.splice(tableIndex, 1);
		    }
		  }else{
		    addClass(parent, "hiddenTable");
		    hiddenTables.push(tableName);
		  }
		  var now = new Date();
		  now.setYear(now.getYear() + 1);
		  var value = hiddenTables.join("|");
		  document.cookie = "adminer_tablesHide="+value+"; expires="+now.toGMTString();
		  
		  return false;
		}
		/**
		 * Initialize tables after menu is loaded
		 */
		function initTables() {
		  var nameEQ = "adminer_tablesHide=";
		  var ca = document.cookie.split(';');
		  for(var i=0;i < ca.length;i++) {
		    var c = ca[i];
		    while (c.charAt(0)==' ') c = c.substring(1,c.length);
		    if (c.indexOf(nameEQ) == 0){
		       hiddenTables = c.substring(nameEQ.length,c.length).split("|");
		       var target = qsa("#tables .toggleVisible");
		       for(var it = 0; it < target.length; it++){
		         var hp = target[it].href.indexOf("#");
		         if(hp > 1 && inTables(currentDatabase+"-"+target[it].href.substring(hp+1)) > -1){
		           addClass(target[it].parentElement, "hiddenTable");
		         }
		       }
		    }
		  }
		  addClass(qs("#tables"),"hidingTablesPlugin");
		}
		/**
		 * Filtering tables after typing to input
		 * @param string search String to search
		 */
		function filterTables(search){
		  var target = qsa("#tables span");
		  for(var it = 0; it < target.length; it++){
		    var a = qsa("a", target[it])[2];
		    if(!a){ continue; }
		    var text = a.innerText || a.textContent;
		    if(text.indexOf(search) < 0){
		      a.innerHTML = text;
		      addClass(target[it],"filtered");
		    }else{
		      a.innerHTML = text.replace(search,'<b>' + search + '</b>');
		      removeClass(target[it],"filtered");
		    }
		  }
		}
		/**
		 * Check if table is in array of hidden tables
		 * @param string s Name of table
		 * @returns integer Index in array of table or -1 if isn't there
		 */
		function inTables(s){
		  for(var i = 0; i < hiddenTables.length; i++){
		    if(hiddenTables[i] == s){
		      return i;
		    }
		  }
		  return -1;
		}
    </script>
    <p class="jsonly">Filter: <input onkeyup="filterTables(this.value);"></p>
    <?php
    $adminer = adminer();
    
		echo "<p id='tables' onmouseover='menuOver(this, event);addClass(this,\"min\")' onmouseout='menuOut(this);removeClass(this,\"min\")'>\n";
    foreach ($tables as $table => $status) {
			echo '<span><a href="#'.urlencode($table).'" class="toggleVisible" onclick="return toggleVisible(this,event)"></a> <a href="' . h(ME) . 'select=' . urlencode($table) . '"' . bold($_GET["select"] == $table || $_GET["edit"] == $table, "select") . ">" . lang('select') . "</a> ";
			$name = $adminer->tableName($status);
			echo (support("table") || support("indexes")
				? '<a href="' . h(ME) . 'table=' . urlencode($table) . '"'
					. bold(in_array($table, array($_GET["table"], $_GET["create"], $_GET["indexes"], $_GET["foreign"], $_GET["trigger"], $_GET["select"], $_GET["edit"])), (is_view($status) ? "view" : ""), "structure")
					. " title='" . lang('Show structure') . "'>$name</a>"
				: $name
			) . "<br></span>\n";
		}?>
		</p>
		<p class="toggleTableVisible" onclick="bclick(event)"><span class="showT">Zobrazit</span><span class="hideT">Skrýt</span> skryté tabulky</p>
		<script>initTables();</script>
		<?php 
		return true;
	}	
}
