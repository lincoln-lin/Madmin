
var permissionIncludeMap = {
    'is_manage': ['is_write'],
    'is_write': ['is_read']
};

$('body').on('change', '[type="checkbox"]', function () {
    var $this = $(this),
        type = $this.closest('td').data('type'),
        checked = $this.prop('checked')
    if (permissionIncludeMap[type]) {
        permissionIncludeMap[type].forEach(function (type) {
            var $input = $this.closest('tr').find('[data-type="' + type + '"] input');
            if (checked) {
                $input.prop('checked', true);
            } else {
                $input.prop('checked', false);
            }
            $input.change();
        })
    }
})


var $span = $("<span>").addClass('glyphicon glyphicon-question-sign').attr('data-toggle', 'tooltip')
$span.clone().attr('title', '前台浏览文章的权限').appendTo($('th:contains("查看内容权限")'))
$span.clone().attr('title', '后台编辑文章的权限(包括添加,删除,修改文章内容)').appendTo($('th:contains("编辑内容权限")'))
$span.clone().attr('title', '管理该分类的子分类的权限(包括添加,删除,修改子分类)').appendTo($('th:contains("管理员权限")'))

$('[data-toggle="tooltip"]').tooltip()