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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_contacorrentedetalhe_classe.php");
$oGet = db_utils::postMemory($_GET);
$clcontacorrentedetalhe = new cl_contacorrentedetalhe;
$clcontacorrentedetalhe->rotulo->label("c19_sequencial");

$oRotuloCGM = new rotulo('cgm');
$oRotuloCGM->label('z01_nome');
$oRotuloCGM->label('z01_numcgm');


$campos = "distinct c17_sequencial, cast(cast(c17_contacorrente as varchar) ||' - '||cast(c17_descricao as varchar) as varchar) as dl_Conta_Corrente, z01_numcgm, z01_nome";
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
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tc19_sequencial ?>">
              <?=$Lc19_sequencial ?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
                db_input("c19_sequencial", 10, $Ic19_sequencial, true, "text", 4, "", "chave_c19_sequencial");
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap>
              <b>Código Credor:</b>
            </td>
            <td width="96%" align="left" nowrap>
              <?
                db_input("z01_numcgm", 10, $Iz01_numcgm, true, "text", 4, "", "chave_z01_numcgm");
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap>
              <b>Nome Credor:</b>
            </td>
            <td width="96%" align="left" nowrap>
              <?
                db_input("z01_nome", 50, $Iz01_nome, true, "text", 4, "", "chave_z01_nome");
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_contacorrentedetalhe.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?

$aWhere   = array();

if (isset($oGet->c19_contacorrente) && trim($oGet->c19_contacorrente) != '') {
  $aWhere[] = "c19_contacorrente = {$oGet->c19_contacorrente}";
}

if (isset($oGet->sReduzidos) && trim($oGet->sReduzidos) != "") {

  $aWhere[] = "c19_reduz in ({$oGet->sReduzidos})";
  $aWhere[] = "c19_conplanoreduzanousu = ".db_getsession("DB_anousu");
}

if (!isset($pesquisa_chave)) {

  if (isset($chave_c19_sequencial) && (trim($chave_c19_sequencial) != "")) {
    $aWhere[] = "c19_sequencial = {$chave_c19_sequencial} ";
  } else if (isset($chave_z01_numcgm) && (trim($chave_z01_numcgm) != "")) {
    $aWhere[] = "z01_numcgm like '$chave_z01_numcgm%'";
  } else if (isset($chave_z01_nome) && (trim($chave_z01_nome) != "")) {
    $aWhere[] = "z01_nome like '$chave_z01_nome%'";
  }

  $sql     = $clcontacorrentedetalhe->sql_query_contacorrente_cgm(null, $campos, 1, implode(" and ", $aWhere));
  $repassa = array();
  
  if (isset($chave_c19_sequencial)) {
    $repassa = array("chave_c19_sequencial" => $chave_c19_sequencial, "chave_c19_sequencial" => $chave_c19_sequencial);
  }
  
  db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);

} else {

  if ($pesquisa_chave != null && $pesquisa_chave != "") {

    $where = "c19_contacorrente = {$oGet->c19_contacorrente}";
    if (isset($c17_sequencial)) {
      $where .= " and z01_numcgm = {$pesquisa_chave}";
    }

    $sSql   = $clcontacorrentedetalhe->sql_query_contacorrente_cgm(null, "*", null, $where);
    $result = $clcontacorrentedetalhe->sql_record($sSql);

    if ($clcontacorrentedetalhe->numrows != 0) {

      db_fieldsmemory($result, 0);
      echo "<script>" . $funcao_js . "('$z01_nome',false);</script>";

    } else {

      echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") não Encontrado',true);</script>";
    }

  } else {
    echo "<script>" . $funcao_js . "('',false);</script>";
  }
}
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if (!isset($pesquisa_chave)) {
?>
  <script>
  </script>
  <?
}
  ?>
<script>
js_tabulacaoforms("form2","chave_c19_sequencial",true,1,"chave_c19_sequencial",true);
</script>