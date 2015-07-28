define(
[
  'jquery',
  'underscore',
  'backbone',
  'view/test'
],function($,_,Backbone,TestView){
    var appRoute = Backbone.Router.extend({
      routes:{
        '': 'onPageLoad',
        'admin/:id': 'adminIndex'
      },
      onPageLoad: function(){
        var testView = new TestView();
        testView.render();
        $('#testBtn').click(function(){
          $('#resultBtn').removeClass('active');
          $(this).addClass('active');
          $('#result').hide();
          $('#testForm').show();
        });
        $('#resultBtn').click(function(){
          $('#testBtn').removeClass('active');
          $(this).addClass('active');
          $('#result').show();
          $('#testForm').hide();
        });
      },
      adminIndex: function(id){
        
      }
    });
    return appRoute;
});
