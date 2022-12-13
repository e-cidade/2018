<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");

db_sel_instit(null, "db21_usasisagua, db21_regracgmiptu, db21_regracgmiss");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">

<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<link href="estilos.css" rel="stylesheet" type="text/css">

<style type="text/css">
fieldset {
  text-align: center;
}
fieldset span {
  font-weight: bold;
}
fieldset select {
  width: 300px;
}

#form_select {
  clear: both;
}
.table_arquivo_auxiliar fieldset {
  border: none;
}
</style>

</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1">

<table width="790" align="center">
<tr>
  <td width="50%">
    <fieldset>
      <legend><strong>Situa&ccedil;&otilde;es de Corte</strong></legend>
      <div id="form_select">
      <span class="label_select">Op&ccedil;&otilde;es: </span>
      <?  
        $aSelSituacao = array('c' => 'Com as situa&ccedil;&otilde;es de corte selecionadas',
                              's' => 'Sem as situa&ccedil;&otilde;es de corte selecionadas');
        db_select('situacaocorte', $aSelSituacao, true, 1);
      ?>
      </div>
      <?
        db_montaComboAux('x43_codsituacao', 'x43_descr', 'db_situacaocorte', 'js_situacaocorte', 'js_situacaocorte_hide', 'func_aguacortesituacao.php', 'db_iframe_situacao', 'db_lanca_situacao');
      ?>
    </fieldset>
  </td>

  <td width="50%">
    <fieldset>
      <legend><strong>Zona de Entrega</strong></legend>
      <div id="form_select">
      <span class="label_select">Op&ccedil;&otilde;es: </span>
      <?  
        $aSelSituacao = array('c' => 'Com as zonas de entrega selecionadas',
                              's' => 'Sem as zonas de entrega selecionadas');
        db_select('zonaentrega', $aSelSituacao, true, 1);
      ?>
      </div>
      <?
        db_montaComboAux('j85_codigo', 'j85_descr', 'db_zonaentrega', 'js_zonaentrega', 'js_zonaentrega_hide', 'func_iptucadzonaentrega.php', 'db_iframe_zonaentrega', 'db_lanca_zonaentrega'); 
      ?>
    </fieldset>
  </td>
</tr>

<tr>
  <td>
    <fieldset>
      <legend><strong>Caracter&iacute;sticas da Constru&ccedil;&atilde;o</strong></legend>
      <div id="form_select">
      <span class="label_select">Op&ccedil;&otilde;es: </span>
      <?  
        $aSelSituacao = array('c'=>'Com as carater&iacute;sticas selecionadas',
                              's'=>'Sem as carater&iacute;sticas selecionadas');
        db_select('caracteristica', $aSelSituacao, true, 1);
      ?>
      </div>     
      <? 
        db_montaComboAux('j31_codigo', 'j31_descr', 'db_caracteristica', 'js_caracteristica', 'js_caracteristica_hide', 'func_caracter.php', 'db_iframe_caracteristica', 'db_lanca_caracteristica', '&iGrupo=80');
      ?> 
    </fieldset>  
  </td>

  <td>
    <fieldset>
      <legend><strong>Logradouros</strong></legend>
      <div id="form_select">
      <span class="label_select">Op&ccedil;&otilde;es: </span>
      <?  
        $aSelSituacao = array('c' => 'Com os logradouros selecionados',
                              's' => 'Sem os logradouros selecionados');
        db_select('logradouro', $aSelSituacao, true, 1);
      ?>
      </div>  
      <?
        db_montaComboAux('j14_codigo', 'j14_nome', 'db_ruas', 'js_ruas', 'js_ruas_hide', 'func_ruas.php', 'db_iframe_ruas', 'db_lanca_ruas');
      ?>    
    </fieldset>  
  </td>
</tr>

<tr>
  <td colspan="2" align="center">
   <strong>Gerar notifica&ccedil;&otilde;es para matr&iacute;culas baixadas</strong>
   <?
     db_select('matriculasbaixadas', array('S'=>'Sim', 'N'=>'N&atilde;o'), true, 1);   
   ?>
  </td>
</tr>

<tr>
  <td colspan="2" align="center">
   <strong>Gerar notifica&ccedil;&otilde;es para terrenos</strong>
   <?
     db_select('terrenos', array('S'=>'Sim', 'N'=>'N&atilde;o'), true, 1);
   ?>
  </td>
</tr>

</table>
</form>
</body>
</html>

<?php 
function db_montaComboAux($sCampoCodigo, $sCampoDescr, $sNomeObjeto, $sFuncaoJS, $sFuncaoJSHide, $sArquivoPesquisa, $sNomeFrame, $sNomeBotao, $sQueryString = null) {
  
  echo '<table class="table_arquivo_auxiliar">';

  $clArqAuxiliar = new cl_arquivo_auxiliar();
    
  $clArqAuxiliar->codigo         = $sCampoCodigo;
  $clArqAuxiliar->descr          = $sCampoDescr; 
  $clArqAuxiliar->nomeobjeto     = $sNomeObjeto;
  $clArqAuxiliar->funcao_js      = $sFuncaoJS;
  $clArqAuxiliar->funcao_js_hide = $sFuncaoJSHide;
  $clArqAuxiliar->func_arquivo   = $sArquivoPesquisa;  
  $clArqAuxiliar->nomeiframe     = $sNomeFrame;
  $clArqAuxiliar->nome_botao     = $sNomeBotao;
  
  $clArqAuxiliar->Labelancora    = 'Selecione';
  $clArqAuxiliar->linhas         = 4;
  $clArqAuxiliar->localjan       = '';
  $clArqAuxiliar->tipo           = 2;
  $clArqAuxiliar->vwidth         = 400;
  
  if($sQueryString != null) {
    
    $clArqAuxiliar->passar_query_string_para_func = $sQueryString;
    
  }
  
  $clArqAuxiliar->funcao_gera_formulario();

  echo '</table>';
}

?>