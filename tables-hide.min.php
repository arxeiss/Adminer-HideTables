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
        $jsonTables = array();    
        foreach ($tables as $table => $status) {
          $jsonTables[] = array(
            'table' => $table,
            'isView' => is_view($status),
            'show' => support("table") || support("indexes"),
            'selected' => in_array($table, array($_GET["table"], $_GET["create"], $_GET["indexes"], $_GET["foreign"], $_GET["trigger"], $_GET["select"], $_GET["edit"], $_GET["view"])),
            'fullTableName' => $_GET[DRIVER]."-".$_GET["db"]."-".$table
          );
        }
        ?>
        <style<?php echo nonce(); ?>>p.toggleTableVisible{cursor:pointer}#menu.hiddenVisible .hideT{display:inline}#menu.hiddenVisible .showT,#tables li.hiddenTable,.hideT{display:none}#menu.hiddenVisible #tables li.hiddenTable{display:inline;opacity:.5}#menu.hiddenVisible #tables li.hiddenTable.filtered,#tables li.filtered{display:none}#tables a.toggleVisible{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA0AAAAKCAYAAABv7tTEAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAANJJREFUeNp00cEGAkEcx/HYpEN0jei0RKfouqceoOsSsW8QEd16lD31DBG9QDp1WmIpS8QSER2yfSe/ydj057N27fxnfjPjVf5XF7neW9iijp07qIEpDijkiRESZPDdBvNx1MAUYwwQqOGECXq2oa0YpuGGDuYYaiLTEOGBq11t7cSJNXukFVNNEjtjNlUeNSemhzNWeGGpJD/la9k7QsVJFG9Rip9r5U/1tXnbEOggQkUs9M8v30mmhlBHbfdwwQzNckRzWntdot3boLTfb70FGACGeTlEq+2nVAAAAABJRU5ErkJggg==);display:inline-block;height:10px;visibility:hidden;width:13px}#tables li.hiddenTable a.toggleVisible{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA0AAAAKCAYAAABv7tTEAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAALZJREFUeNpiZsAEokCsCcS+UP4fIP7GgAPoAvEKIP4Pxd+Q2Cug8iggBEnROqhtICAB5cMMCYFpsIc6ASRxD4j5gfgwlA+ieYD4BpT/B6qe4TaSM2YCcSAS/z+UPxOJf5sJSCxDcqYcEF8D4l9Q/i8oXxpJDVg9GxBvQbI+BOrpNCjth+T8LVD1YMAMxF1IgQGSnIZk2DeoPEgdAyOWOEoAYhUgVgfim0B8B4gXAPFrBkoAQIABAO6KOXvUtxGwAAAAAElFTkSuQmCC)}#tables.min a.toggleVisible{visibility:visible}
        </style>

        <p class="jsonly">Filter: <input id="filterInput"></p>
        <ul id="tables"></ul>
        <p class="toggleTableVisible"><span class="showT">Show</span><span class="hideT">Hide</span> hidden tables</p>

        <script<?php echo nonce(); ?>>
            var menuTables = <?php echo json_encode($jsonTables); ?>;
            var baseUrl = <?php echo json_encode(ME); ?>;
            var selectLang = <?php echo json_encode(lang('select')); ?>;
            var structureLang = <?php echo json_encode(lang('Show structure')); ?>;
            var hiddenTables = [];
            var currentDatabase = "<?php echo $_GET[DRIVER]."-".$_GET["db"]; ?>";
            function bclick(){menuBlock.classList.contains("hiddenVisible")?menuBlock.classList.remove("hiddenVisible"):menuBlock.classList.add("hiddenVisible")}function toggleVisible(e){var t=(this.parentElement,currentDatabase),n=this.href.indexOf("#");if(1>n)return!0;t+="-"+this.href.substring(n+1);var l=inTables(t);return l>-1?hiddenTables.splice(l,1):hiddenTables.push(t),localStorage.setItem("adminer_tablesHide",hiddenTables.join("|")),e.preventDefault(),filterTables.call(qs("#filterInput")),!1}function initTables(){hiddenTables=(localStorage.getItem("adminer_tablesHide")||"").split("|").filter(function(e){return 0!=e.length}),tablesEl.classList.add("hidingTablesPlugin"),filterTables()}function inTables(e){for(var t=0;t<hiddenTables.length;t++)if(hiddenTables[t]==e)return t;return-1}function filterTables(){value=this.value||"",tablesEl.innerHTML="";for(var e=0;e<menuTables.length;e++)if(menuTables[e].table.indexOf(value)>=0){var t=document.createElement("li");menuTables[e].selected&&t.classList.add("bold"),inTables(menuTables[e].fullTableName)>=0&&t.classList.add("hiddenTable");var n=document.createElement("a");n.href="#"+encodeURIComponent(menuTables[e].table),n.className="toggleVisible";var l=document.createElement("a");l.appendChild(document.createTextNode(selectLang)),l.href=baseUrl+"select="+encodeURIComponent(menuTables[e].table),l.className="select"+(menuTables[e].isView?" is-view":"");var a=document.createElement("a");a.appendChild(highlightFoundPart(menuTables[e].table,value)),a.href=baseUrl+"table="+encodeURIComponent(menuTables[e].table),a.title=structureLang,t.appendChild(n),t.appendChild(document.createTextNode(" ")),t.appendChild(l),t.appendChild(document.createTextNode(" ")),t.appendChild(a),tablesEl.appendChild(t).appendChild(document.createElement("br"))}}function highlightFoundPart(e,t){if(""==t)return document.createTextNode(e);var n=document.createElement("span");n.className="noBg";var l=e.indexOf(t);l>0&&n.appendChild(document.createTextNode(e.substring(0,l)));var a=document.createElement("strong");return a.appendChild(document.createTextNode(e.substring(l,l+t.length))),n.appendChild(a),e.length>l+t.length&&n.appendChild(document.createTextNode(e.substring(l+t.length))),n}function menuOverATH(e){menuOver.call(this,e),this.classList.add("min")}function menuOutATH(e){menuOut.call(this,e),this.classList.remove("min")}var menuBlock=qs("#menu"),tablesEl=qs("#tables");mixin(qs("#filterInput"),{onkeyup:filterTables}),mixin(qs(".toggleTableVisible"),{onclick:bclick}),mixin(qs("#tables"),{onmouseover:menuOverATH,onmouseout:menuOutATH}),tablesEl.addEventListener("click",function(){for(var e=event.target,t=!0;e&&e!==this;)e.matches("a.toggleVisible")&&toggleVisible.call(e,event)===!1&&(t=!1),e=e.parentNode;return t}),initTables();
        </script>
        <?php 
        return true;
    }   
}
