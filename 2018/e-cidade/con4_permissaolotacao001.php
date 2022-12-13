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
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

require_once("dbforms/db_funcoes.php");

$oGet  = db_utils::postMemory($_GET);
$oRotulo  = new rotulocampo;
$oRotulo->label('z01_nome');
$oRotulo->label('nome');
$oRotulo->label('id_usuario');
$oRotulo->label('login');

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("estilos.css");
      db_app::load("scripts.js");
      db_app::load("strings.js");
      db_app::load("DBToggleList.widget.js");
      db_app::load("AjaxRequest.js");
      db_app::load("prototype.js"); 
    ?>
  </head>
  <body class="body-default">

    <form class="container" id="form1" name="form1" method="post">

      <fieldset>

        <legend>Lotações:</legend>
        <table>
          <tr>
            <td width="105">
              <label><?php echo $Lnome; ?></label>
            </td>
            <td>
              <?php 
                db_input("iCodigoUsuario", 6, 1, true, 'text', 3) ; 
                db_input("nome",  50, 1, true, 'text', 3) ; 
              ?>
            </td>
          </tr>

          <tr>
            <td>
              <label><strong>Insituição:</strong></label>
            </td>
            <td>
              <?php 

                $sSqlInstituicoes  = "select id_instit,nomeinst from db_config ";
                $sSqlInstituicoes .= " inner join db_userinst on id_instit  = codigo";
                $sSqlInstituicoes .= "                       and id_usuario = {$iCodigoUsuario}";
                                                            
                $rsInstituicoes = db_query($sSqlInstituicoes);

                db_selectrecord('instit', $rsInstituicoes, true, 1, 1);
              ?>
            </td>
          </tr>

          <?php for($iLotacao = 0; $iLotacao < pg_num_rows($rsInstituicoes); $iLotacao++) { 
                  
            $oLotacao = db_utils::fieldsMemory($rsInstituicoes, $iLotacao);
          ?>
            <tr>
              <td colspan="2">
                <div class="instituicoes" id="instit_<?=$oLotacao->id_instit?>"></div>
              </td>
            </tr>
          <?php } ?>

        </table>
      </fieldset>
      <input type="button" value="Alterar" onclick="salvarDados()" />
    </form>
  </body>
</html>

<script>

/**
 * Código do Usuário que esta sendo criado/alterado
 * @type integer
 */
var iCodigoUsuario = <?=$iCodigoUsuario?>;

/**
 * Intancias DBToggleList
 * @type Array
 */
var aInstancias = {};

(function(){

  /**
   * busca as lotações diponíveis e selecionadas para todas 
   * as instituições que o usuário pode ter acesso.
   */
  var oRequisicao = new AjaxRequest('con1_permissaolotacao.RPC.php', {exec: 'carregarLotacoes', iCodigoUsuario: iCodigoUsuario});
  oRequisicao.setCallBack(fCallBackLotacoes);
  oRequisicao.execute();

  /**
   * Tratamento do evento change do  campo Instiuição.
   */
  changeInstituicao($('instit').value);

  $('instit').observe('change', function(){
    changeInstituicao($(this).value);
  });

  $('institdescr').observe('change', function(){
    changeInstituicao($(this).value);
  });

})();

/**
 * Exibe as lotações disponiveis e selecionadas, 
 * de acordo com a intituição selecionada no combo
 * 
 * @param  integer iInstituicao
 * @return boolean
 */
function changeInstituicao(iInstituicao){

  $$('.instituicoes').each(function(obj){
    $(obj).hide();
  });

  $('instit_' + iInstituicao).show();
}

/**
 *
 * Adiciona ao ToggleList as lotações disponíveis para  a instituição informada como parâmetro
 * 
 * @param  Array aLotacoes Lotações disponíveis para esta instituição.
 * @param  integer iInstituicao código da instituição selecionada
 * @return boolean
 */
function montaDisponiveis(aLotacoes, iInstituicao) {

  var oToggleList = aInstancias[iInstituicao];

  for (var iLotacao = 0; iLotacao < aLotacoes.length; iLotacao++) {

    oLotacao = aLotacoes[iLotacao];
    oToggleList.addSelect({estrutural: oLotacao.estrutural, descricao: oLotacao.descricao, id: oLotacao.codigo});
  }
}

/**
 * Adiciona ao ToggleList as lotações selecionadas para a instituição informada como parâmetro
 * 
 * @param  Array aLotacoes Lotações selecionadas para esta instituição.
 * @param  integer iInstituicao código da instituição selecionada
 * @return boolean
 */
function montaSelecionados(aLotacoes, iInstituicao) {

  var oToggleList = aInstancias[iInstituicao];

  for (var iLotacao = 0; iLotacao < aLotacoes.length; iLotacao++) {
    
    oLotacao = aLotacoes[iLotacao];
    oToggleList.addSelected({estrutural: oLotacao.estrutural, descricao: oLotacao.descricao, id: oLotacao.codigo});
  }
}

/**
 * Callback da requisição Ajax utilizada para montar os 
 * DBToggleList com as lotações para cada uma das intituições.
 * 
 * @param  Object oRetorno retorno da requisição Ajax
 * @return boolean
 */
function fCallBackLotacoes(oRetorno) {

  var aColunas = [
    {
      sWidth : '60px',
      sLabel : 'Estrutural',
      sId    : 'estrutural',
      sAlign : 'left'
    },
    {
      sWidth : '300px',
      sLabel : 'Descrição',
      sId    : 'descricao',
      sAlign : 'left'
    },
    {
      sId    : 'id',
      lVisible: false
    }
  ];

  var sCampos = {'selecao': "Lotações Disponíveis:", "selecionados":"Lotações Selecionadas:"};

  /**
   * Percorre as instituições realizando a criação do DBToggleList quando a mesmo possui lotações cadastradas, 
   * caso não possua é adicionando uma mensagem de aviso.
   */
  for (iInstituicao = 0; iInstituicao < oRetorno.aInstituicoes.length; iInstituicao++) {

    var iCodigoInstituicao = oRetorno.aInstituicoes[iInstituicao],
    aLotacoesInstituicoes  = oRetorno.aLotacoesInstituicoes[iCodigoInstituicao] || [],
    aLotacoesUsuario       = oRetorno.aLotacoesUsuario[iCodigoInstituicao] || [];

    if (aLotacoesInstituicoes.length > 0 || aLotacoesUsuario.length > 0) {

      aInstancias[iCodigoInstituicao] = new DBToggleList( aColunas,  sCampos);
      aInstancias[iCodigoInstituicao].lViewButtonOrder = false;
    } else {
      $('instit_' + iCodigoInstituicao).update('Nenhuma lotação cadastrada para esta instituição.');
    }

    if(aLotacoesInstituicoes.length > 0) {
      montaDisponiveis(oRetorno.aLotacoesInstituicoes[iCodigoInstituicao], iCodigoInstituicao);
    }

    if (aLotacoesUsuario.length > 0) {
      montaSelecionados(oRetorno.aLotacoesUsuario[iCodigoInstituicao], iCodigoInstituicao);
    }

    if (aLotacoesInstituicoes.length > 0 || aLotacoesUsuario.length > 0) {
      aInstancias[iCodigoInstituicao].show($('instit_' + iCodigoInstituicao));
    }
  }
}

/**
 * Salva as lotações selecionadas papra cada uma das instituições.
 * 
 * @return boolean
 */
function salvarDados() {

  aLotacoesSelecionadas = [];
  
  /**
   * Realiza o tratamento de todas as lotações selecionadas nos TogglesLists
   */
  for (iInstituicao in aInstancias) {

    var oInstancia = aInstancias[iInstituicao];
    if (oInstancia.getSelected().length > 0) {

      var aLotacoes         = oInstancia.getSelected();
      for ( var iLotacao = 0; iLotacao < aLotacoes.length; iLotacao++) {
        aLotacoes[iLotacao].descricao = aLotacoes[iLotacao].descricao.urlEncode();
      }
      aLotacoesSelecionadas = aLotacoesSelecionadas.concat(aLotacoes);
    }
  }
  
  var oRequisicao = new AjaxRequest('con1_permissaolotacao.RPC.php', { exec: 'salvarLotacoes', 
                                                                       iCodigoUsuario: iCodigoUsuario,
                                                                       aLotacoesSelecionadas: aLotacoesSelecionadas
                                                                     }
                                   );

  oRequisicao.setCallBack(function(oRetorno, lErro) {

    alert(oRetorno.message.urlDecode());

    if (oRetorno.  erro){
      return false;
    }
    return true;
  });

  oRequisicao.execute();
}
</script>
