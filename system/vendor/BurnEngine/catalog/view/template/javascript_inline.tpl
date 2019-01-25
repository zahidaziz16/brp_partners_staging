(function(window) {

window.tbApp = window.tbApp || {};

function executeInline(tbApp) {
    <?php if (!empty($js)): ?>
    tbUtils.globalEval(<?php echo json_encode($js . ' tbApp.trigger("inlineScriptsLoaded");'); ?>);
    <?php else: ?>
    tbApp.trigger("inlineScriptsLoaded");
    <?php endif; ?>
}

if (window.tbApp.onScriptLoaded !== undefined) {
    window.tbApp.onScriptLoaded(function() {
        executeInline.call(window, window.tbApp);
    });
} else {
    window.tbApp.executeInline = executeInline;
}
})(window);
