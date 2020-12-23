M.mod_forum = M.mod_forum || {};
M.mod_forum.favourites = {
    SELECTORS: {
        INPUT_SELECT:          '.discussioncontrol.displaymode',
        TOGGLE_FAVOURITES:     'forum_toggle_favourites',
        FORUMID:               '#page-mod-forum-discuss',
        FORUMPOST:             '.forumpost',
        POST_FAVOURITE:        '.post_favourite',
        CONTROL:               'discussioncontrol forum_toggle_favourites',
        FAVOURITELINK:         '.forumfavouritelink',
        FAVOURITEHIDELINK:     'forumhidefavouritelink'
    },
    LANG: {
        TOGGLE_FAVOURITES:      M.util.get_string('togglefavourites', 'mod_forum')
    },
    init : function() {
        var count = Y.all(this.SELECTORS.POST_FAVOURITE).size();
        if (count) {
            var obj = Y.Node.create(
                '<div class="' + this.SELECTORS.CONTROL + '">' +
                    '<a href="#" id="' + this.SELECTORS.TOGGLE_FAVOURITES + '">' + this.LANG.TOGGLE_FAVOURITES + ' (' + count + ')</a>' +
                '</div>'
            );
            Y.one(this.SELECTORS.INPUT_SELECT).insert(obj, 'after');
            Y.one(this.SELECTORS.FORUMID).delegate('click', this.click_toggle, '#' + this.SELECTORS.TOGGLE_FAVOURITES, this);
            Y.one(this.SELECTORS.FORUMID).delegate('click', this.click_post, this.SELECTORS.FAVOURITELINK, this);
        }
    },
    click_toggle: function(e) {
        e.preventDefault();
        Y.all(this.SELECTORS.FORUMPOST).filter(':not(' + this.SELECTORS.POST_FAVOURITE + ')').toggleView();
        Y.all(this.SELECTORS.FAVOURITELINK).toggleClass(this.SELECTORS.FAVOURITEHIDELINK);
    },
    click_post: function() {
        Y.all(this.SELECTORS.FORUMPOST).filter(':not(' + this.SELECTORS.POST_FAVOURITE + ')').toggleView();
        Y.all(this.SELECTORS.FAVOURITELINK).toggleClass(this.SELECTORS.FAVOURITEHIDELINK);
    }
};
