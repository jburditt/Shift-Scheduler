tinyMCE.init({
    mode : "textareas",
    theme : "advanced",
    plugins : "advimage,advlink,emotions,iespell,paste",
    //theme_advanced_buttons1_add : "cut,copy,paste,pastetext,pasteword,separator,iespell,emotions",
    //theme_advanced_buttons2_add : "separator,forecolor,backcolor",
    //theme_advanced_buttons2_add_before: "search,replace,separator",
    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,bullist,numlist,separator,outdent,indent,separator,forecolor,backcolor",
    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,separator,undo,redo,separator,link,unlink,image,cleanup,help,code,separator,iespell,emotions",
    theme_advanced_buttons3 : "",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_disable : "formatselect,styleselect,sub,sup,charmap,hr,visualaid,removeformat",
    content_css : "css/word.css",
    //external_link_list_url : "example_link_list.js",
    //external_image_list_url : "example_image_list.js",
    //file_browser_callback : "fileBrowserCallBack",
    paste_use_dialog : false,
    theme_advanced_resizing : true,
    theme_advanced_resize_horizontal : false,
    theme_advanced_link_targets : "_something=My somthing;_something2=My somthing2;_something3=My somthing3;",
    paste_auto_cleanup_on_paste : true,
    paste_convert_headers_to_strong : false,
    paste_strip_class_attributes : "all",
    paste_remove_spans : false,
    paste_remove_styles : false        
});

function fileBrowserCallBack(field_name, url, type, win) {
    // This is where you insert your custom filebrowser logic
    alert("Filebrowser callback: field_name: " + field_name + ", url: " + url + ", type: " + type);

    // Insert new URL, this would normaly be done in a popup
    win.document.forms[0].elements[field_name].value = "someurl.htm";
}

