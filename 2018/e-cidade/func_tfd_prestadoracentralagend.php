<?php
/*
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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_tfd_prestadoracentralagend_classe.php"));

db_postmemory( $_POST );
parse_str( $_SERVER["QUERY_STRING"] );

$oDaotfd_prestadoracentralagend = new cl_tfd_prestadoracentralagend;
$oRotulo = new rotulocampo;
$oRotulo->label('tf25_i_codigo');
$oRotulo->label('z01_nome');
$oRotulo->label('z01_numcgm');
$oRotulo->label('tf10_i_prestadora');

?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
      <form name="form2" method="post" action="" >
        <table width="35%" border="0" align="center" cellspacing="0">
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Ttf25_i_codigo?>">
              <?=$Ltf10_i_prestadora?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		          db_input("tf10_i_prestadora",10,$Itf10_i_prestadora,true,"text",4,"","chave_tf10_i_prestadora");
		          ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tz01_numcgm?>">
              <?='<b>CGM</b>'?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		          db_input("z01_numcgm",10,$Iz01_numcgm,true,"text",4,"","chave_cgm_prestadora");
		          ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
              <?=$Lz01_nome?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		          db_input("z01_nome",50,$Iz01_nome,true,"text",4,"","chave_nome_prestadora");
		          ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_tfd_prestadoracentralagend.hide();">
             </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?php
      if( !isset( $pesquisa_chave ) ) {

        if( isset( $campos ) == false ) {

          if(file_exists("funcoes/db_func_tfd_prestadoracentralagend.php")==true){
            require_once(modification("funcoes/db_func_tfd_prestadoracentralagend.php"));
          } else {
            $campos = "tfd_prestadoracentralagend.*";
          }
        }

        $dData     = date('Y-m-d', db_getsession('DB_datausu'));
        $sValidade = " tf10_d_validadeini <= '$dData' and (tf10_d_validadefim is null or tf10_d_validadefim >= '$dData')";
        $sCentral  = '';
        $sSep      = '';

        if( isset( $chave_tf10_i_centralagend ) ) {

          $sCentral = ' tf10_i_centralagend = '.$chave_tf10_i_centralagend;
          $sSep     = ' and ';
        }

        if( isset( $chave_tf10_i_prestadora ) ) {

          $sCentral = " tf10_i_prestadora = {$chave_tf10_i_prestadora} ";
          $sSep     = ' and ';
        }

        if(isset($chave_tf10_i_prestadora) && (trim($chave_tf10_i_prestadora) != '')) {

          $sWhere = " tf10_i_prestadora = {$chave_tf10_i_prestadora} {$sSep} {$sCentral} and {$sValidade}";
	        $sSql   = $oDaotfd_prestadoracentralagend->sql_query( null, $campos, 'tf10_i_prestadora', $sWhere );

        } else if(isset($chave_cgm_prestadora) && (trim($chave_cgm_prestadora) != '')) {

          $sWhere = " a.z01_numcgm = {$chave_cgm_prestadora} {$sSep} {$sCentral} and {$sValidade}";
	        $sSql   = $oDaotfd_prestadoracentralagend->sql_query(null, $campos, 'tf25_i_codigo', $sWhere);

        } else if(isset($chave_nome_prestadora) && (trim($chave_nome_prestadora) != '')) {

          $sWhere = " a.z01_nome like '{$chave_nome_prestadora}%' {$sSep} {$sCentral} and {$sValidade}";
	        $sSql   = $oDaotfd_prestadoracentralagend->sql_query(null, $campos, 'a.z01_nome', $sWhere);

        } else {
          $sSql = $oDaotfd_prestadoracentralagend->sql_query("", $campos, "tf10_i_codigo", "{$sCentral} {$sSep} {$sValidade}");
        }

        if( isset( $lRetornaDadosPassagem ) ) {

          $sCampos = $campos . ", tf37_valor";
          $sWhere  = "{$sCentral} {$sSep} {$sValidade}";
          $sSql    = $oDaotfd_prestadoracentralagend->sql_query_passagem_destino( null, $sCampos, null, $sWhere );
        }

        if(isset($nao_mostra)) {

          $sSep    = '';
          $aFuncao = explode('|', $funcao_js);
          $rs      = $oDaotfd_prestadoracentralagend->sql_record($sSql);

          if($oDaotfd_prestadoracentralagend->numrows == 0) {
	          die('<script>'.$aFuncao[0]."('','Chave(".$chave_tf10_i_prestadora.") não Encontrado');</script>");
          } else {

            db_fieldsmemory($rs, 0);
            $sFuncao = $aFuncao[0].'(';

            for($iCont = 1; $iCont < count($aFuncao); $iCont++) {

              $sFuncao .= $sSep.'"'.eval('return @$'.$aFuncao[$iCont].';').'"';
              $sSep     = ', ';
            }

            $sFuncao  = substr($sFuncao, 0, strlen($sFuncao));
            $sFuncao .= ');';

            die("<script>".$sFuncao.'</script>');
          }
        }

        $repassa = array();
        if(isset($chave_tf10_i_codigo)){
          $repassa = array("chave_tf10_i_codigo"=>$chave_tf10_i_codigo,"chave_tf10_i_codigo"=>$chave_tf10_i_codigo);
        }

        db_lovrot($sSql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {

        if($pesquisa_chave!=null && $pesquisa_chave!=""){

          $sSql   = $oDaotfd_prestadoracentralagend->sql_query($pesquisa_chave);
          $result = $oDaotfd_prestadoracentralagend->sql_record($sSql);

          if($oDaotfd_prestadoracentralagend->numrows != 0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$tf10_i_codigo',false);</script>";
          } else {
	          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        } else {
	        echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
