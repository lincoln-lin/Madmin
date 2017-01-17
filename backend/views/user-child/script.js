
$('.cmd-unlock').click(function () {
    mz.post({'unlock': 1})
        .done(function(){alert('解锁成功');window.close();})
});