// Init when in security module form
s('#security .application-form').pageInit(function(parent) {
    // Bind select/un-select checkboxes
    s('.select-checkboxes-list-item input[type=checkbox]').ajaxClick(function(response, cb){
        cb.a('checked') ? cb.a('checked', false) : cb.a('checked', true);
        //s.trace(response);
        //s.trace(cb.a('checked'));
    });
});