/**
 * Created by liu on 8/16/16.
 */
;(function ($) {
    $.fn.gridViewExpand = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.gridViewExpand');
            return false;
        }
    };

    var defaults = {
        parent: undefined,
        expandElementSelector: undefined
    };

    var methods = {
        init: function (options) {
            return this.each(function () {
                var $e = $(this);
                var settings = $.extend({}, defaults, options || {});
                $e.data('gridViewExpand', {
                    settings: settings
                })
                var id = $e.attr('id');

                $e.css('opacity', 1)

                $e.find('.child').hide()
                $e.find('[data-child-of=""], [data-child-of="-1"]').show()

                $e.find('.child').each(function () {
                    var $this = $(this)
                        ,id = $this.data('key')
                    if ($e.find('[data-child-of="'+id+'"]').length > 0) {
                        var $elem = $this.find(settings.expandElementSelector)
                        $('<span>').addClass('cmd-toggle glyphicon glyphicon-plus-sign').appendTo($elem)
                    }
                })

                var selector = '#'+id + ' table tr ' + settings.expandElementSelector + ' .cmd-toggle'

                $(document).off('click.gridViewExpand', selector)
                    .on('click.gridViewExpand ', selector, function (event) {

                        var $this = $(this)
                            ,$tr = $this.closest('tr')
                        if ($this.hasClass('glyphicon-plus-sign')) {
                            methods.expand.call($e, $tr)
                        } else {
                            methods.fold.call($e, $tr)
                        }
                        return false;
                    });
            });
        },
        search: function(s){
            if (s === '' || s === undefined) return;
            var $e = $(this)
                ,settings = $e.data('gridViewExpand').settings

            var foundedTrs = []
            $e.find('.child').each(function () {
                var $tr = $(this)
                if ((new RegExp(s, 'i')).test($tr.find(settings.expandElementSelector).text())) {
                    foundedTrs.push($tr)
                }
            })

            if (foundedTrs.length > 0) {
                methods.reset.call($e)
                foundedTrs.forEach(function ($tr) {
                    $tr.addClass('alert-info')
                    while(true) {
                        var key = $tr.data('child-of')
                            , $tr = $e.find('[data-key="' + key + '"]')
                        methods.expand.call($e, $tr)
                        if ($tr.length == 0) {
                            break;
                        }
                    }
                })
            } else {
                alert('没查询到相关数据')
            }

        },
        reset: function () {
            var $e = $(this)
            $e.find('.alert-info').removeClass('alert-info')
            $e.find('.expanded').each(function () {
                methods.fold.call($e, $(this))
            })
        },
        expand: function ($tr) {
            if ($tr.hasClass('expanded')) return;

            var $btn = $tr.find('.cmd-toggle')
                ,$e = $(this)
                ,key = $tr.data('key')
            $btn.removeClass('glyphicon-plus-sign').addClass('glyphicon-minus-sign')
            $tr.addClass('expanded')
            $e.find('[data-child-of="'+key+'"]').show()
        },
        fold: function ($tr) {
            var $e = $(this)
                ,id = $tr.data('key')
                ,$btn = $tr.find('.cmd-toggle')
            $btn.removeClass('glyphicon-minus-sign').addClass('glyphicon-plus-sign')
            $tr.removeClass('expanded')
            $e.find('[data-child-of="'+id+'"].expanded').each(function(){
                methods.fold.call($e, $(this))
            })
            $e.find('[data-child-of="'+id+'"]').hide()
        }
    };
})(window.jQuery);
