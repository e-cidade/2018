<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_utils.php");
require_once modification("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo;
$clrotulo->label("db90_codban");
$clrotulo->label("db90_descr");

$clrotulo->label("db50_codigo");
$clrotulo->label("db50_descr");

$clrotulo->label("rh27_rubric");
$clrotulo->label("rh27_descr");

$oGet    = db_utils::postMemory($_GET);

?>
<html>
  <head>
    <title>DBSeller Informática Ltda</title>
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js");
      db_app::load("prototype.js");
      db_app::load("windowAux.widget.js");
      db_app::load("strings.js");
      db_app::load("AjaxRequest.js");
      db_app::load("dbtextField.widget.js");
      db_app::load("dbViewAvaliacoes.classe.js");
      db_app::load("dbmessageBoard.widget.js");
      db_app::load("dbautocomplete.widget.js");
      db_app::load("dbcomboBox.widget.js");
      db_app::load("datagrid.widget.js");
      db_app::load("widgets/DBHint.widget.js");
      db_app::load("widgets/datagrid/plugins/DBHint.plugin.js");
      db_app::load("widgets/DBLookUp.widget.js");
      db_app::load("widgets/Collection.widget.js");
      db_app::load("widgets/DatagridCollection.widget.js");
      db_app::load("widgets/FormCollection.widget.js");
      db_app::load("estilos.css,grid.style.css");
    ?>
    <style type="text/css">
      .gridConfiguracoes {
        width: 800px; 
        margin-top: 0
      }
    </style>
  </head>
  <body>
    <div class="container">
      <form id="formConfiguracoesArquivosConfignados" method="POST">
        <fieldset>
          <legend>Configurações de Arquivos Consignados:</legend>
          <table class="form-container">

            <tr>
              <td nowrap title="<?php echo $Tdb90_codban; ?>">
                <label id="lbl_db90_codban" for="db90_codban"><a href="#"><?php echo $Ldb90_codban; ?></a></label>
              </td>
              <td>
                <?php 
                  db_input('iCodigoConfiguracao', 10, '', true, "hidden", 1); 
                  db_input('db90_codban', 10, $Idb90_codban, true, "text", 1); 
                  db_input('db90_descr', 50, $Idb90_descr, true, "text", 3); 
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Tdb50_codigo; ?>">
                <label id="lbl_db50_codigo" for="db50_codigo"><a href="#"><?php echo $Ldb50_codigo; ?></a></label>
              </td>
              <td>
                <?php
                  db_input('db50_codigo', 10, $Idb50_codigo, true, "text", 1); 
                  db_input('db50_descr', 50, $Idb50_descr, true, "text", 3); 
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Trh27_rubric; ?>">
                <label id="lbl_rh27_rubric" for="rh27_rubric"><a href="#"><?php echo $Lrh27_rubric; ?></a></label>
              </td>
              <td>
                <?php
                  db_input('rh27_rubric', 10, $Irh27_rubric, true, "text", 1);
                  db_input('rh27_descr', 50, $Irh27_descr, true, "text", 3);
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input type="button" id="salvar"   name="salvar"   value="Salvar" />
        <input type="button" id="excluir"  name="excluir"  value="Excluir" disabled />
        <input type="button" id="cancelar" name="cancelar" value="Limpar" />
      </form>
    </div>
      
    <div class="container" class="gridConfiguracoes">
      <div id="gridConfiguracoes"></div>
    </div>
    <?php db_menu(); ?>
  </body>
</html>
<?php 
  $sMensagem  = "Este menu mudou para:\n";
  $sMensagem .= "Pessoal > Procedimentos > Manutenção de Empréstimos Consignados > Parâmetros > Configuração Consignados\n";
  $sMensagem .= "A partir da próxima atualização o menu atual será retirado.";

  if(isset($oGet->menuDepreciado) && $oGet->menuDepreciado) {
    db_msgbox($sMensagem);
  }
?>
<script>
(function() {

  var oLookUpBanco   = new DBLookUp ($('lbl_db90_codban'), $('db90_codban'), $('db90_descr'), {'sArquivo' : 'func_db_bancos.php'});
  var oLookUpLayout  = new DBLookUp ($('lbl_db50_codigo'), $('db50_codigo'), $('db50_descr'), {'sArquivo' : 'func_db_layouttxt.php'});
  var oLookUpRubrica = new DBLookUp ($('lbl_rh27_rubric'), $('rh27_rubric'), $('rh27_descr'), {'sArquivo' : 'func_rhrubricas.php'});

  try { 
    var oConfiguracoesDatagridCollection = montarGrid();
    carregarDadosGrid(oConfiguracoesDatagridCollection);
    montarFormCollection(oConfiguracoesDatagridCollection);
  } catch (e) {
    console.error(e);
  }

})();

  function adicionarCallbacksCollection(collection) {
    
    collection.setEvent('onAfterUpdate', function(item) {
      salvarConfiguracao(item);
    });

    collection.setEvent('onAfterDelete', function(item) {
      AjaxRequest.create("pes4_configurararquivoconsignado.RPC.php", 
        {
          'exec'                : "removerConfiguracoes", 
          'iCodigoConfiguracao' : item.iCodigoConfiguracao
        }, 
        function(response, erro){
          alert(response.sMessage.urlDecode());
          if(erro) {
            return;
          }
        }
      ).setMessage('Removendo...').execute();
    });

    return collection;
  }

  function montarGrid() {

    var oConfiguracoesDatagridCollection = new DatagridCollection(adicionarCallbacksCollection(new Collection().setId('iCodigoConfiguracao')), 'gridConfiguracoesConsignado');
        oConfiguracoesDatagridCollection.configure({"height":"350", "width":"800", "update":true, "delete":true});

        oConfiguracoesDatagridCollection.addColumn("db90_descr", {"width": "245px"})
                                        .setOption("align","left")
                                        .setOption("label","Banco")
                                        .transform(function(sDescricao, oItemCollecion) {

                                          var sConfiguracaoColunaBanco = sDescricao;
                                          
                                          if(sDescricao.length > 25) {
                                            sConfiguracaoColunaBanco  =     '<div class="hintConfiguracaoColunaBanco"';
                                            sConfiguracaoColunaBanco +=             'id="configuracaoColunaBanco'+ oItemCollecion.iCodigoConfiguracao +'"';
                                            sConfiguracaoColunaBanco += 'data-descricao="'+ sDescricao +'" >'+ sDescricao.substr(0, 25) +'...</div>';
                                          }
                                          return sConfiguracaoColunaBanco;
                                        });

        oConfiguracoesDatagridCollection.addColumn("db50_descr", {"width": "245px"})
                                        .setOption("align","left")
                                        .setOption("label","Layout");

        oConfiguracoesDatagridCollection.addColumn("rh27_descr", {"width": "305px"})
                                        .setOption("align","left")
                                        .setOption("label","Rubrica")
                                        .transform(function(sDescricao, oItemCollecion) {
                                          return oItemCollecion.rh27_rubric +' - '+ sDescricao;
                                        });

        oConfiguracoesDatagridCollection.collection.setEvent('onAfterCreate', function(item) {
          if(!item.iCodigoConfiguracao) {
            salvarConfiguracao(item, this);
          }
        }.bind(oConfiguracoesDatagridCollection));

        oConfiguracoesDatagridCollection.show($('gridConfiguracoes'));

    return oConfiguracoesDatagridCollection;
  }

  function carregarDadosGrid(oConfiguracoesDatagridCollection) {
    this.oConfiguracoesDatagridCollection = oConfiguracoesDatagridCollection;
    AjaxRequest.create("pes4_configurararquivoconsignado.RPC.php", 
      {
        'exec': "getConfiguracoes"
      },
      atualizarDadosGrid.bind(this)
    ).setMessage('Buscando Configurações...').execute();
  }

  function atualizarDadosGrid(response, erro) {

    if(erro) {
      alert(response.sMessage.urlDecode());
      return;
    }

    if(response.configuracoes.length > 0) {

      for(var configuracao of response.configuracoes) {

        this.oConfiguracoesDatagridCollection.collection.add({
          'iCodigoConfiguracao'  : configuracao.iCodigoConfiguracao,
          'db90_codban'          : ""+configuracao.codigo_banco+"",
          'db90_descr'           : configuracao.nome_banco.urlDecode(),
          'db50_codigo'          : configuracao.codigo_layout,
          'db50_descr'           : configuracao.nome_layout.urlDecode(),
          'rh27_rubric'          : configuracao.codigo_rubrica,
          'rh27_descr'           : configuracao.nome_rubrica.urlDecode(),
        });
      }
    } else {
      this.oConfiguracoesDatagridCollection.collection.add({});
    }

    this.oConfiguracoesDatagridCollection.reload();
    adicionarHintColunaBanco(this.oConfiguracoesDatagridCollection.getGrid());
  }

  function montarFormCollection(oConfiguracoesDatagridCollection) {
    
    var oFormCollectionConfiguracoes = FormCollection.create(oConfiguracoesDatagridCollection, $("formConfiguracoesArquivosConfignados"));
        
        oFormCollectionConfiguracoes.makeBehavior($('salvar'),
          'save',
          function(item) {// Validações vão aqui ohh
            if(item.db90_codban == '' || item.db90_descr  == ''  || item.db50_codigo  == ''  || item.db50_descr  == ''  || item.rh27_rubric  == ''  || item.rh27_descr  == '' ) {
              alert("Por favor preencha todos os campos.");
              return false;
            }
          }
        );
        oFormCollectionConfiguracoes.makeBehavior($('excluir'), 'delete');
        oFormCollectionConfiguracoes.makeBehavior($('cancelar'), 'cancel');

    return oFormCollectionConfiguracoes;
  }

  function salvarConfiguracao(item, datagridCollection) {

    AjaxRequest.create("pes4_configurararquivoconsignado.RPC.php", 
      {
        'exec'                : "salvarConfiguracoes",
        'iCodigoConfiguracao' :  item.iCodigoConfiguracao,
        'sBanco'              :  item.db90_codban,
        'iLayout'             :  item.db50_codigo,
        'sRubrica'            :  item.rh27_rubric
      },
      function(response, erro) {
        
        alert(response.sMessage.urlDecode());

        if(erro) {
          datagridCollection.getCollection().itens.pop();
          datagridCollection.reload();
          return false;
        }

        if(datagridCollection) {

          if(response.iCodigoConfiguracao) {

            item.ID                  = String(response.iCodigoConfiguracao);
            item.iCodigoConfiguracao = String(response.iCodigoConfiguracao);
          }
          
          datagridCollection.reload();
        }
        $('iCodigoConfiguracao').value = '';
      }
    ).setMessage('Salvando...').execute();
  }

  function adicionarHintColunaBanco(gridCollection) {

    var aItensDivColunaBanco = document.getElementsByClassName('hintConfiguracaoColunaBanco');

    for(var oItemDivColunaBanco of aItensDivColunaBanco) {

      var aLinhas = oItemDivColunaBanco.parentNode.id.match(/row(\d+)/);

      if(aLinhas) {
        gridCollection.setHint(aLinhas[1], 0, oItemDivColunaBanco.getAttribute('data-descricao'));
      }
    }
  }
</script>
