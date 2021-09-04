
var updateOutput = function(e) {
    var list = e.length ? e : $(e.target),
        output = list.data('output');
    if (window.JSON) {
        output.val(window.JSON.stringify(list.nestable('serialize')));
        console.log(window.JSON.stringify(list.nestable('serialize')));
        $("#json_order").val(window.JSON.stringify(list.nestable('serialize')));
    } else {
        output.val('JSON browser support required for this demo.');
    }
};

$('#nestable').nestable({
    group: 1,
    maxDepth:2
}).on('change', updateOutput);
updateOutput($('#nestable').data('output', $('#nestable-output')));