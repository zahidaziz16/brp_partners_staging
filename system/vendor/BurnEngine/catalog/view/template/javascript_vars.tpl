(function(window) {
window.tbApp = window.tbApp || {};
var data = <?php echo json_encode($jsarr); ?>;
for(var key in data) tbApp[key] = data[key];
})(window);