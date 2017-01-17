// 注意 jQuery 还没有加载进来
(function () {
    // 复写 popup 里面的样式
    if (window.opener) {
        document.write('<link href="<?= $popupCssUrl ?>" rel="stylesheet">');
    }
})();