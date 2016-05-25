var ProgressItem = Backbone.Model.extend({
    defaults: {
        max: 100,
        value: 0
    }
});


var ProgressView = Backbone.View.extend({
    initialize: function (options) {
        this.$el.empty();

        this.$wrapper = $('<div></div>').addClass('progress');
        this.$el.append(this.$wrapper);

        this.$remaining = $('<div></div>')
            .css({
                'width': '0%'
            })
            .addClass('progress-bar progress-bar-success');

        this.$wrapper.append(this.$remaining);
    },

    progressTo: function (percent) {
        this.$remaining.css('width', percent + '%');
        this.$remaining.text(percent + '%');
    }
});

var ProgressBar = Backbone.Collection.extend({

    model: ProgressItem,

    defaults: {
        el: null
    },

    initialize: function (models, options) {
        options = _.extend({}, this.defaults, options);
        this.view = new ProgressView({
            el: options.el
        });

        this.on('add', this.update);
        this.on('remove', this.update);
        this.on('change', this.update);
    },

    update: function () {
        var max = 0,
            value = 0;

        for (i = 0; i < this.models.length; i++) {
            var item = this.models[i];
            max += item.get('max');
            value += item.get('value');
        }
        progression = value * 100 / max;
        this.view.progressTo(progression);
        this.trigger('progress', progression);
    }
});