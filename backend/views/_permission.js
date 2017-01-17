$(document).on('change', '[type="checkbox"]', function(){
    var $this = $(this)
        ,$tr = $this.closest('tr')
    if ($this.prop('checked')) {
            check($tr)
    } else {
        unCheck($tr)
    }
})

var check = function ($tr) {
    $tr.find('[type="checkbox"]').prop('checked', true)
    $('[data-child-of="'+$tr.data('key')+'"]').each(function () {
        check($(this))
    })
    checkIsAllSiblingsChecked($tr)
}
var checkIsAllSiblingsChecked = function($tr) {
    if ($('[data-child-of="'+$tr.data('child-of')+'"] [type="checkbox"]').not(':checked').length == 0
        && $('[data-key="'+$tr.data('child-of')+'"]').length > 0) {
        var $parent = $('[data-key="'+$tr.data('child-of')+'"]')
        $parent.find('[type="checkbox"]').prop('checked', true)
        checkIsAllSiblingsChecked($parent)
    }
}
var unCheck = function ($tr) {
    $tr.find('[type="checkbox"]').prop('checked', false)
    $('[data-key="'+$tr.data('child-of')+'"]').each(function () {
        unCheck($(this))
    })
}