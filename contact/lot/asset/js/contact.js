(function(win, doc) {

    var script = doc.currentScript || doc.getElementsByTagName('script').pop(),
        src = script.src,
        id = src.split('#')[1],
        form = id && doc.getElementById(id);

    if (!form) return;

    win.CONTACT = {
        form: form
    };

})(window, document);