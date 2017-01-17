$('#cmd-import-file').change(function () {
    var $this = $(this)
        ,file = $this[0].files[0]

    Papa.parse(file, {
        encoding: 'gbk',
        complete: function (results) {
            var users = []
            results.data.forEach(function (row) {
                if (row.length == 5 && row[0] != '邮箱') {
                    users.push({
                        email: row[0],
                        role: [],
                        realname: row[2],
                        phone: row[3],
                        remark: row[4]
                    })
                }
            })
            if (users.length == 0) {
                alert('没有有效记录');
            } else {
                mz.post('import', {users: users})
                    .success(function (result) {
                        console.log(result);
                        var msgs = [
                            '导入成功用户数: ' + result.successCount
                        ]
                        if (result.failCount > 0) {
                            msgs.push('导入失败用户数: ' + result.failCount)
                            for (var email in result.errors) {
                                var error = result.errors[email]
                                msgs.push(email + ': ' + JSON.stringify(error))
                            }
                        }
                        alert(msgs.join('\n'))
                        window.location.reload()
                    })
            }
        }
    })

})
