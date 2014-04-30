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
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

$iInstituicao = db_getsession("DB_instit");

$oRotuloTipoGrupoVinculo = new rotulo("posicaoestoqueprocessamento");
$oRotuloTipoGrupoVinculo->label();
$oRotuloTipoGrupoVinculo->tlabel();
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php db_app::load("estilos.css, scripts.js, prototype.js, strings.js"); ?>
  </head>
  <body bgcolor="#cccccc">

    <div class="container">
      
      <fieldset>
      <legend><b>Relatório Posição Estoque</b></legend>

        <table>
          <tr>
            <td nowrap="nowrap" title="<?php echo $Tm05_data; ?>">
              <?php db_ancora($Lm05_data, 'js_buscarProcessamentos();', 2); ?>
            </td>
            <td title="<?php echo $Tm05_data; ?>">
              <?php db_inputdata('m05_data', null, null, null, true, 'text', 3);?>
            </td>
          </tr>
        </table>

      </fieldset>

      <input type="button" name="btnImprimir" id ="btnImprimir" value="Imprimir" onclick="js_imprimir();" />

    </div>

  </body>
</html>

<?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>

<script type="text/javascript">

/**
 * Abre janela com datas de processamento do estoque
 *
 * @access public
 * @return void
 */
function js_buscarProcessamentos() {

  var sPrograma = 'func_posicaoestoqueprocessamento.php?funcao_js=parent.js_retornoBuscarProcessamentos|m05_data'; 
  js_OpenJanelaIframe('', 'db_iframe_posicaoestoqueprocessamento', sPrograma, 'Pesquisar Datas Processamentos', true);
}

/**
 * Callback da janela aberta pela funcao js_buscarProcessamentos()
 *
 * @param string $sDataProcessamento
 * @access public
 * @return void
 */
function js_retornoBuscarProcessamentos(sDataProcessamento) {

  $('m05_data').value = js_formatar(sDataProcessamento, 'd');
  db_iframe_posicaoestoqueprocessamento.hide();
} 

/**
 * Abre janela com relatorio
 *
 * @access public
 * @return bool
 */
function js_imprimir() {

  if ( empty($('m05_data').value) ) {

    alert('Informe a data do processamento.');
    return false;
  }

  var sQueryString = 'mat2_posicaoestoque002.php?sDataProcessamento=' + $('m05_data').value.urlEncode();
  var janela = window.open(sQueryString, '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');

  janela.moveTo(0, 0);
}
</script>