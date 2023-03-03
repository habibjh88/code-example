// Youtube: https://www.youtube.com/@tushargarg9620
// Playlist: https://www.youtube.com/watch?v=SAVw7h2p6hE&list=PL2tpgEP3OLaB5uSwwuC3YAsOsz0SLvOlx

//Requirement
//=================================
/* 
1. jQuery 
2. Underscore 
3. Backbone  
*/

//Create Model
//=================================
var Team = Backbone.Model.extend({
    initialize: function(){
        console.log("This is a constructor");
    }
});

//Create Collection 
var player1 = new Team({
    name: "Sakib",
    from: "Bangldesh"
});

var player2 = new Team({
    name: "Tamim",
    from: "Bangldesh"
});

var players = Backbone.Collection.extend();
var p = new players([player1, player2]);

//Operation
add() //Ex. p.add(new Team({name:"Mustafiz",from:"BD"}))
push()
pop()
unshift()
shift()
remove()
where()
findwhere()
each()
filter()


//View
//=================================
var content = Backbone.View.extend({
    el: '#demo', //TODO: this is a way (This line or)
    initialize: function () {
        console.log("This is a constructor");
        this.render();
    },
    render: function () {
        this.$el.html("Backbone view") //TODO: this is another a way (This Line)
    }
});
var v1 = new content({
    el: '#demo' //html id
});
// v1.render() //Same as 52 lines