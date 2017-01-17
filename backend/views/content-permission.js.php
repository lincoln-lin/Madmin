
$('[type="checkbox"]').change(function () {
    var $this = $(this),
        $tr = $this.closest('tr'),
        $table = $tr.closest('table')
    check_child($this, $table, $this.prop('checked'))
})

function check_child($check, $table, checked) {
    var id = $check.closest('tr').data('key'),
        type = $check.closest('td').data('type')
    $table.find('.pid-' + id + ' [data-type="' + type + '"] input').each(function(){
        $(this).prop('checked', checked)
        check_child($(this), $table, checked)
    })
}


