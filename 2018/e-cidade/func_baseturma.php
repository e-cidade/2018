<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

//MODULO: educação
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_base_classe.php"));
require_once(modification("classes/db_escolabase_classe.php"));
db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clbase       = new cl_base;
$clescolabase = new cl_escolabase;
$clbase->rotulo->label("ed31_i_codigo");
$clbase->rotulo->label("ed31_c_descr");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td height="63" align="center" valign="top">
   <table width="55%" border="0" align="center" cellspacing="0">
    <form name="form2" method="post" action="" >
    <tr>
      <td width="4%" align="right" nowrap title="<?=$Ted31_i_codigo?>">
        <label for="chave_ed31_i_codigo"><?=$Led31_i_codigo?></label>
      </td>
      <td width="96%" align="left" nowrap>
        <?db_input("ed31_i_codigo",10,$Ied31_i_codigo,true,"text",4,"","chave_ed31_i_codigo");?>
      </td>
    </tr>
    <tr>
      <td width="4%" align="right" nowrap title="<?=$Ted31_c_descr?>">
        <label for="chave_ed31_c_descr"><?=$Led31_c_descr?></label>
      </td>
      <td width="96%" align="left" nowrap>
        <?db_input("ed31_c_descr",40,$Ied31_c_descr,true,"text",4,"","chave_ed31_c_descr");?>
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center">
        <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
        <input name="limpar" type="reset" id="limpar" value="Limpar" >
        <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_base.hide();">
      </td>
    </tr>
    </form>
   </table>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?
   $sWhereBaseAtiva = "";
   if ( isset($lBaseAtiva) && $lBaseAtiva = 'true') {
     $sWhereBaseAtiva = "and  ed31_c_ativo = 'S'";
   }

   $escola = db_getsession("DB_coddepto");
   if(!isset($pesquisa_chave)){
    $campos = "base.ed31_i_codigo,
               base.ed31_c_descr,
               base.ed31_c_turno,
               cursoedu.ed29_c_descr,
               regimemat.ed218_i_codigo,
               regimemat.ed218_c_nome,
               regimemat.ed218_c_divisao,
               base.ed31_c_medfreq,
               cursoedu.ed29_i_codigo,
               tipoensino.ed36_c_abrev,
               cursoedu.ed29_i_ensino,
               cursoedu.ed29_c_historico,
               ensino.ed10_censocursoprofiss
              ";
    if (isset($chave_ed31_i_codigo) && (trim($chave_ed31_i_codigo)!="") ) {

      $sWhere  = " ed31_i_codigo = {$chave_ed31_i_codigo} AND ed71_c_situacao = 'S' AND ed77_i_escola = {$escola} AND ed71_i_escola = {$escola}";
      $sWhere .= $sWhereBaseAtiva ;
      $sql     = $clbase->sql_query_baseturma(""," distinct ".$campos,"ed31_c_descr", $sWhere);
    } else if(isset($chave_ed31_c_descr) && (trim($chave_ed31_c_descr)!="") ) {

      $sWhere  = " ed31_c_descr like '{$chave_ed31_c_descr}%' AND ed71_c_situacao = 'S' AND ed77_i_escola = {$escola} AND ed71_i_escola = {$escola}";
      $sWhere .= $sWhereBaseAtiva ;
      $sql = $clbase->sql_query_baseturma(""," distinct ".$campos,"ed31_c_descr", $sWhere);
    } else {

      $sWhere  = " ed71_c_situacao = 'S' AND ed77_i_escola = {$escola} AND ed71_i_escola = {$escola}";
      $sWhere .= $sWhereBaseAtiva ;
      $sql = $clbase->sql_query_baseturma(""," distinct ".$campos,"ed31_c_descr", $sWhere);
    }

    db_lovrot($sql,15,"()","",$funcao_js);
   } else {

    if ($pesquisa_chave!=null && $pesquisa_chave!="") {

      $sWhere  = " ed31_i_codigo = {$pesquisa_chave} AND ed71_c_situacao = 'S' AND ed77_i_escola = {$escola} AND ed71_i_escola = {$escola}";
      $sWhere .= $sWhereBaseAtiva ;
      $sSql    = $clbase->sql_query_baseturma("","*","", $sWhere);
      $result  = $clbase->sql_record();
      if ($clbase->numrows!=0) {

       db_fieldsmemory($result,0);
       echo "<script>".$funcao_js."('$ed31_c_descr','$ed29_i_codigo','$ed29_c_descr','$ed36_c_abrev','$ed218_i_codigo','$ed218_c_nome','$ed218_c_divisao','$ed31_c_medfreq','$ed29_i_ensino','$ed29_c_historico', '$ed10_censocursoprofiss', false);</script>";
      }else{
       echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado','','','','','','','','',true);</script>";
      }
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
