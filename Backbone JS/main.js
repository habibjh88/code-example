var Player = Backbone.Model.extend();

var p1 = new Player({
    name: "Player 1",
    number: '10'
});

var PlayerView = Backbone.View.extend({
    render: function () {
        console.log('Working');
        this.$el.html(this.model.get('name'));
    }
});

var pv = new PlayerView({
    el: '#root', //From html id
    model: p1
});

pv.render()

console.log(pv);