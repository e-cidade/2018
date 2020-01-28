<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/JSON.php");

$sMensagemErro = '';
$sCamposJson   = '';

try { 

  $oJson = new Services_JSON();
  $oGet  = db_utils::postMemory($_GET);

  $sCampos = 'rh120_sequencial as codigo_campo, rotulorel as rotulo_relatorio';

  if ( empty($oGet->rh45_codigo)  ) {
    throw new Exception('Código do relatório não informado.');
  }

  /**
   * Busca os campos selecionados ja cadastrado para o relatorio
   */
  $oDaoRelrubrelrubcampos    = db_utils::getDao('relrubrelrubcampos');
  $sWhereCamposSelecionados  = 'rh121_relrub = ' . $oGet->rh45_codigo;
  $sSqlCamposSelecionados    = $oDaoRelrubrelrubcampos->sql_queryCamposPorRelatorio($sCampos, "rh121_ordem", $sWhereCamposSelecionados);
  $rsCamposSelecionados      = db_query($sSqlCamposSelecionados);

  if ( !$rsCamposSelecionados ) {
    throw new Exception("Erro ao buscar campos já selcionados.\n\nErro tecnico:\n" . pg_last_error());
  }

  $aCamposSelecionados = db_utils::getcollectionbyrecord($rsCamposSelecionados, false, false, true);
  $sCamposSelecionadosJson = urlDecode($oJson->encode($aCamposSelecionados));

  /**
   * Campos para selecao 
   */
  $oDaoRelrubcampos = db_utils::getDao('relrubcampos');
  $sWhereCampos     = 'rh120_sequencial not in ( select rh121_relrubcampos from relrubrelrubcampos where rh121_relrub = ' . $oGet->rh45_codigo . ')';
  $sSqlCampos = $oDaoRelrubcampos->sql_queryCamposRelatorio($sCampos, $sWhereCampos);
  $rsCampos   = db_query($sSqlCampos);

  /**
   * Erro na query 
   */
  if ( !$rsCampos ) {
    throw new Exception("Erro ao buscar campos.\n\nErro tecnico:\n" . pg_last_error());
  }

  $aCampos = db_utils::getcollectionbyrecord($rsCampos, false, false, true);

  $sCamposJson = urlDecode($oJson->encode($aCampos));
} catch (Exception $oErro) {

  /**
   * Mensagem de erro
   * - Variavel chamada no final do script, para somente exibir alert com erro apos ja ter carregado elementos html 
   */
  $sMensagemErro = $oErro->getMessage();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<meta http-equiv="expires" content="0">
<link href="estilos.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="javascript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToggleList.widget.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >

  <center class="container">
    <fieldset style="width:600px;padding-top:10px;">
      <legend>Dados cadastrais: </legend>
      <div id="dadosCadastrais"></div>
    </fieldset>  
    <?php
      if ( $oGet->db_opcao == 1 ) {
        $sLAbel = 'Incluir'; 
      } else if ( $oGet->db_opcao == 2 ) {
        $sLAbel = 'Alterar'; 
      } else {
        $sLAbel = 'Excluir'; 
      }
    ?>
   <input type="button" id="processar" value="<?php echo $sLAbel ?>" onclick="js_processar();" />
   
  </center>

</body>
</html>
<script type="text/javascript">
/**
 * ToggleList 
 */
var oToggle = new DBToggleList([{sId:'rotulo_relatorio', sLabel:'Campo'}]);

/**
 * Para selecionar
 * - Array com campos para selecionar ( grid da esquerda ) 
 */
var aCamposSelecionar = <?php echo $sCamposJson; ?>;

/**
 * Selecionados
 * - Array com campos que por padrao ja irao estar selecionados ( grid da direita ) 
 */
var aCamposSelecionados = <?php echo $sCamposSelecionadosJson; ?>;

/**
 * Id do relatorio 
 */
var iRelatorio = '<?php echo $oGet->rh45_codigo; ?>';

/**
 * Opcao de cadastro
 * 1 - inclusao, 2 - alteracao, 3 - exclusao 
 */
var iDBOpcao = '<?php echo $oGet->db_opcao; ?>';

/**
 * Percorre arrays e adiciona nas grids  
 */
(function() {

  aCamposSelecionar.each(function(oCampo) {
    oToggle.addSelect(oCampo);
  });

  aCamposSelecionados.each(function(oCampo) {
    oToggle.addSelected(oCampo);
  });

  /**
   * Desabilita bootoes de acao do componente
   */
  if ( iDBOpcao == 3 ) {

    oToggle.disable();
    $('processar').disabled = true;
  }

  oToggle.show($('dadosCadastrais'));

})();

/**
 * Processar
 *
 * @access public
 * @return boolean
 */
function js_processar() {

  var aCamposSelecionados = oToggle.getSelected();
  var oParametros = new Object();

  oParametros.acao       = 'salvarCampos'; 
  oParametros.aCampos    = aCamposSelecionados;
  oParametros.iRelatorio = iRelatorio;

  var oAjax = new Ajax.Request( 
    'pes1_relrubcamposRPC.php', 
    {
      method    : 'post', 
      parameters: 'json=' + JSON.stringify(oParametros), 
      onComplete: js_retornoProcessar
    }
  );
}

/**
 * Retorno do js_processar
 *
 * @param oAjax $oAjax
 * @access public
 * @return void
 */
function js_retornoProcessar(oAjax) {

  var oRetorno  = eval("("+oAjax.responseText+")");
  var sMensagem = oRetorno.sMensagem.urlDecode().replace(/\\n/g, "\n");

  /**
   * Erro 
   */
  if ( oRetorno.iStatus > 1 ) {

    alert(sMensagem);
    return false;
  }

  alert(sMensagem);
}

</script>

<?php 
if ( !empty($sMensagemErro) ) {
  db_msgbox(stripslashes(str_replace("\n", '\\\n', $sMensagemErro)));
}
?>