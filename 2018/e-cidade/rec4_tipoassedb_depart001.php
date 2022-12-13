<?php
/**
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("classes/db_tipoasse_classe.php"));
?>

<html>
<head>
<?php
  db_app::load(array(
    "scripts.js",
    "strings.js",
    "prototype.js",
    "AjaxRequest.js",
    "datagrid.widget.js",
    "widgets/Collection.widget.js",
    "widgets/DatagridCollection.widget.js",
    "estilos.css"
  ));
?>
</head>
<body>
<form name="form1" id="form1">
  <div id="container" class="container">
    <fieldset>
      <legend>Assentamentos / Afastamentos por Departamento</legend>

      <?php
        $iCodigoDepartamento   = db_getsession('DB_coddepto');
        $rsiCodigoDepartamento = db_query("select coddepto, descrdepto from db_depart order by descrdepto");
        db_selectrecord('iCodigoDepartamento', $rsiCodigoDepartamento, true, 1, "", "", "", "", "carregarRegistros()");
      ?>

      <div id="grid_registros" style="margin-top: 10px; width:500px"></div>
    </fieldset>
    <input type="button" name="salvar" id="salvar" value="Salvar" onclick="js_salvar()" />
  </div>  
  <?php
  db_menu();
  ?>
</form>
</body>
</html>

<script>

var sUrl = "rec4_tipoassedb_depart.RPC.php";

var oCollectionAssentamentosDepartamento = Collection.create().setId('iCodigo');
var oGridAssentamentosDepartamento       = montarGrid(oCollectionAssentamentosDepartamento, 'grid_registros');

carregarRegistros(oGridAssentamentosDepartamento);

function montarGrid(oCollection, sIdGrid) {

  var oGrid = new DatagridCollection(oCollection, sIdGrid);

      oGrid.configure({'height':'300', 'width':'500', 'update':false, 'delete':false, 'order':false});
      
      oGrid.addColumn('iCodigo')
           .setOption('width', '50px')
           .setOption('align', 'center')
           .setOption('label', 'Código');

      oGrid.addColumn('sAssentamento')
           .setOption('width', '50px')
           .setOption('align', 'left')
           .setOption('label', 'Sigla');

      oGrid.addColumn('sDescricao')
           .setOption('width', '250px')
           .setOption('align', 'left')
           .setOption('label', 'Descrição');

      oGrid.grid.setCheckbox(0);
      oGrid.show($(sIdGrid));

  return oGrid;
}

function carregarRegistros(oGrid) {

  if(oGrid == null) {
    oGrid = oGridAssentamentosDepartamento;
  }

  var oParametros  = { 'exec' : 'carregarTipos', 'iCodigoDepartamento' : $F('iCodigoDepartamento')};
  var oAjaxRequest = new AjaxRequest( sUrl, oParametros,
    
    function (oAjax, lResposta) {

      oGrid.collection.clear();
      oGrid.clear();
      oGrid.setSelectedItens([]);

      oAjax.aTiposAssentamentos.forEach(function(oRegistro, iLinha) {
        
        oGrid.collection.add(oRegistro);

        if(oRegistro.lMarcado) {
          oGrid.addSelectedItens(oRegistro.iCodigo);
        }
      });
      
      oGrid.reload();
    }

  );

  oAjaxRequest.setMessage('Buscando Tipos...');
  oAjaxRequest.execute();
}

function js_salvar() {

  var aSelecionados = new Array; 
  var oParametros   = new Object();
  var aSelecionados = oGridAssentamentosDepartamento.grid.getSelection();
	
  for ( var iSelecionado = 0; iSelecionado < aSelecionados.length; iSelecionado++ ) {
    
    oSelecionado                = new Object();
    oSelecionado.iCodigo        = aSelecionados[iSelecionado][0];
    aSelecionados[iSelecionado] = oSelecionado;
  }
  
  oParametros.exec                = 'salvar';
  oParametros.iCodigoDepartamento = $F('iCodigoDepartamento');
  oParametros.aSelecionados       = aSelecionados;
  
  var oAjaxRequest = new AjaxRequest(sUrl, oParametros,
    function (oAjax, lErro) {

      if (lErro == false) {
        alert(oAjax.message.urlDecode().replace(/\\n/g, '\n'));
      }
    }
  );

  oAjaxRequest.setMessage("Salvando");
  oAjaxRequest.execute();
}
</script>
