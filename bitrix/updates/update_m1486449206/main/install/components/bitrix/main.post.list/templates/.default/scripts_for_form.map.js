{"version":3,"file":"scripts_for_form.min.js","sources":["scripts_for_form.js"],"names":["window","FCForm","arParams","this","url","lhe","entitiesId","form","BX","handler","LHEPostForm","getHandler","editorName","editorId","windowEvents","OnUCUnlinkForm","delegate","entityId","res","empty","ii","hasOwnProperty","removeCustomEvent","windowEventsSet","OnUCUserQuote","author","safeEdit","loaded","origRes","util","htmlspecialchars","_checkTextSafety","show","exec","oEditor","toolbar","controls","Quote","DoNothing","action","Exec","GetViewMode","replace","id","SetBxTag","tag","params","value","name","message","bbCode","actions","quote","setExternalSelectionFromRange","extSel","getExternalSelection","setExternalSelection","OnUCUserReply","authorId","authorName","BxInsertMention","item","type","formID","bNeedComa","insertHtml","OnUCAfterRecordEdit","data","act","editing","hide","showError","showNote","OnUCUsersAreWriting","authorAvatar","timeL","showAnswering","OnUCRecordHasDrawn","parseInt","hideAnswering","linkEntity","remove","eventNode","addCustomEvent","node","_getPlacehoder","_getSwitcher","focus","firstChild","__content_length","onCustomEvent","clearTimeout","_checkWriteTimeout","clear","obj","_checkWrite","submit","cancel","prototype","Ent","checkObj","join","editorIsLoaded","IsContentChanged","confirm","content","GetContent","func","time","length","setTimeout","quick","style","display","document","body","appendChild","nodes","findChildren","tagName","className","pop","filesForm","findChild","cleanNode","text","busy","showWait","post_data","convertFormToArray","ID","ajax","method","dataType","onsuccess","proxy","closeWait","true_data","ENTITY_XML_ID","OnUCFormResponseData","onfailure","insertBefore","create","attrs","class","html","el","addClass","defer","disabled","removeClass","objAnswering","userId","avatar","_id","placeHolder","switcher","ucAnsweringStorage","localStorage","get","Date","set","adjust","children","title","src","childNodes","parentNode","stop","duration","start","opacity","finish","transition","easing","makeEaseOut","transitions","quart","step","state","animate","t","getAttribute","setAttribute","complete","removeChild","i","_data","n","elements","toLowerCase","push","checked","j","options","selected","current","p","indexOf","substring","rest","pp","onUCUsersAreWriting","ready","captchaIMAGE","captchaHIDDEN","attr","captchaINPUT","captchaDIV","getCaptcha","result"],"mappings":"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"}