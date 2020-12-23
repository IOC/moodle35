M.theme_ioc_clean = {};

M.theme_ioc_clean.init = function(Y) {
    var node = Y.one(".navbar");
    if (node) {
        node.delegate('mouseenter', function(e) {
            if (M.core.actionmenu) {
                $menu = Y.one('.moodle-actionmenu[data-enhance=moodle-core-actionmenu]');
                M.core.actionmenu.instance.showMenu(e, $menu);
            }
        }, '.usermenu');
        node.delegate('mouseleave', function(e) {
            if (M.core.actionmenu) {
                M.core.actionmenu.instance.hideMenu(e);
            }
        }, '.usermenu');
        node.delegate('mouseenter', function(e) {
            this.simulate('click');
        }, '.popover-region-toggle');
        node.delegate('mouseleave', function(e) {
            this.addClass('collapsed')
                .one('.popover-region-container').setAttrs({
                                                            'aria-expanded': 'false',
                                                            'aria-hidden': 'true'});
        }, '.popover-region-notifications');
    }
};
