/**

@example

 var paramFilter = [
   {
     chave: 'nome',
     valor: ['matheus']
   },
   {
     chave: 'cpf',
     valor: ['84720331718']
   }
 ];

var request = new HTTPRequest();
request.send('http://dev23.dbseller.com.br:5300/dbportal_prj/api/v1/protocolo/cgm/', {
  fields: ['id', 'nome', 'data_nascimento'],
  filter: paramFilter
  page:{number:1, size:15}
}).then(function (data) {
  console.log(data);
}).catch(function(message) {
  console.info(message)
});

 */

!function(exports) {

  var HTTPRequest = function() {};

  HTTPRequest.prototype = {

    _filter: [],
    _fields: [],
    _page: '',

    filter: function(filter) {

      if (!filter) {
        return this._filter;
      }

      this._filter = filter;

      return this;
    },

    fields: function(fields) {

      if (!fields) {
        return this._fields;
      }

      this._fields = fields;

      return this;
    },
    page: function(page) {
    
      if (!page) {
        return this._page;
      }
          this._page = page;
    
      return this;
    },

    send: function(url, options) {

      var options = options || {};

      var _options = {
        method: options.method || 'GET',
        filter: options.filter || this.filter(),
        fields: options.fields || this.fields(),
        page:   options.page   || this.page()
      };

      var filter = [];
      _options.filter.each(
        function (param) {

          param.valor.each(
            function (valor) {
              filter.push("filter["+param.chave+"]=" + valor);
            }
          );
        }
      );

      var page = '';      
      if (_options.page != '') {
        page = 'page[number]='+_options.page.number+'&page[size]='+_options.page.size;
      }
      var params = [
        'fields=' + _options.fields.join(','),
        filter.join('&'),
        page
      ];

      return fetch(url + '?' + params.join('&'), {
        mode: 'cors',
        credentials: 'include'
      }).then(function(response) {
        return response.json();
      });
    }

  };

  exports.HTTPRequest = HTTPRequest;

}(this);
