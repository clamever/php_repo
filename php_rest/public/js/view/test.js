define([
  'jquery',
  'underscore',
  'backbone',
  'text!template/test.html',
  'model/res',
  'json2',
  'serializeObject'
],function($,_,Backbone,testTemplate,ResModel){
  var TestView = Backbone.View.extend({
    el: '#wrapper',
    template: _.template(testTemplate),
    initialize: function(){
      //this.template = _.template(testTemplate);
    },
    render: function(){
      this.$el.html(this.template);
    },
    events:{
      'click .submit': 'onSubmit'
    },
    onSubmit:function(){
      var parent = this;
      var data = $('#testForm').serializeObject();
      var host = data.host;
      var api = data.api;
      var method = data.method;
      var parameter = data.parameter.split('\r');
      var params = {};
      for(var i in parameter){
        var param = parameter[i].split('=');
        var key   = param[0] || '';
        var value = param[1] || '';
        if(key && value){
          params[key] = value;
        }
      }
      if(method == 'get'){
        var resModel = new ResModel();
        resModel.fetch({
          url: host+api,
          data: $.param(params),
          success:function(model,resp){
            parent.$el.find('#testForm').hide();
            parent.$el.find('#result').show();
            parent.$el.find('#code').html(model.get('code'));
            parent.$el.find('#msg').html(model.get('msg'));
            parent.$el.find('#body').html(JSON.stringify(model.get('body'),null,'\t'));
            $('#testBtn').removeClass('active');
            $('#resultBtn').addClass('active');
          },
          error: function(){
            alert('error');
          }
        });
      }

    }
  });
  return TestView;
});
