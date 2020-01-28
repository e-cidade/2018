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

//MODULO: educação
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clprocedimento      = new cl_procedimento;
$clperiodocalendario = new cl_periodocalendario;
$clprocavaliacao     = new cl_procavaliacao;
$clprocedimento->rotulo->label("ed40_i_codigo");
$clprocedimento->rotulo->label("ed40_c_descr");
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
    <form name="form2" method="post" action="" >
      <table width="55%" border="0" align="center" cellspacing="0">
        <tr>
          <td nowrap title="<?=$Ted40_i_codigo?>">
            <label for="chave_ed40_i_codigo"><?=$Led40_i_codigo?></label>
          </td>
          <td nowrap>
            <?php db_input("ed40_i_codigo",10,$Ied40_i_codigo,true,"text",4,"","chave_ed40_i_codigo");?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Ted40_c_descr?>">
            <label for="chave_ed40_c_descr"><?=$Led40_c_descr?></label>
          </td>
          <td nowrap>
            <?php db_input("ed40_c_descr",30,$Ied40_c_descr,true,"text",4,"","chave_ed40_c_descr");?>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
            <input name="limpar" type="reset" id="limpar" value="Limpar" >
            <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_procedimento.hide();">
          </td>
        </tr>
      </table>
    </form>
   <br>
   <b>Procedimentos de Avaliação compatíveis com o calendário <?=@$caldescr?></b>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?php
    $escola   = db_getsession("DB_coddepto");
    $aWhere   = array();
    $aWhere[] = " ed86_i_escola = {$escola}";
    $aWhere[] = " ed40_desativado is false ";

    $result  = $clperiodocalendario->sql_record($clperiodocalendario->sql_query("","ed53_i_periodoavaliacao","ed09_i_sequencia"," ed53_i_calendario = $calendario"));
    $per_cal = "";
    $sep     = "";
    for($y=0;$y<$clperiodocalendario->numrows;$y++){
      db_fieldsmemory($result,$y);
      $per_cal .= $sep.$ed53_i_periodoavaliacao;
      $sep      = ",";
    }

    if(isset($chave_ed40_i_codigo) && (trim($chave_ed40_i_codigo)!="") ){
      $aWhere[] = " ed40_i_codigo = $chave_ed40_i_codigo";
    }else if(isset($chave_ed40_c_descr) && (trim($chave_ed40_c_descr) != "") ){
      $aWhere[] = " ed40_c_descr like '$chave_ed40_c_descr%'";
    }

    $sWhere  = implode(" and ", $aWhere);
    $sWhere .= " GROUP BY ed40_i_codigo,ed40_c_descr";

    $sSqlProcedimentosCompativeis = $clprocedimento->sql_query_procturma("","ed40_i_codigo,ed40_c_descr","", $sWhere);

    $result = $clprocedimento->sql_record($sSqlProcedimentosCompativeis);
    $clprocedimento->numrows;

    $aPeriodosCompativeis = array();
    if($clprocedimento->numrows>0) {

      for($z=0;$z<$clprocedimento->numrows;$z++){

        db_fieldsmemory($result,$z);
        $result1  = $clprocavaliacao->sql_record($clprocavaliacao->sql_query("","ed41_i_periodoavaliacao,ed09_c_descr","ed09_i_sequencia"," ed41_i_procedimento = $ed40_i_codigo"));
        for($y=0;$y<$clprocavaliacao->numrows;$y++) {

          db_fieldsmemory($result1, $y);
          if(strstr($per_cal, $ed41_i_periodoavaliacao)) {
            $aPeriodosCompativeis[$ed40_i_codigo] = $ed40_i_codigo;
          }
        }
      }
    }

    $compativeis = 0;
    if (count($aPeriodosCompativeis) > 0) {
      $compativeis = implode(', ', $aPeriodosCompativeis);
    }
    $sWhereProcedimentos = " ed40_i_codigo in ($compativeis)";


    $campos = "distinct procedimento.ed40_i_codigo, procedimento.ed40_c_descr, formaavaliacao.ed37_c_descr as ed41_i_formaavaliacao";
    if (!isset($pesquisa_chave)) {

      $sql = $clprocedimento->sql_query("", $campos,"",$sWhereProcedimentos);
      db_lovrot(@$sql,15,"()","",$funcao_js);
    } else {

      if($pesquisa_chave!=null && $pesquisa_chave!=""){

        $sWhereProcedimentos = " AND ed40_i_codigo = {$pesquisa_chave}";

        $sql    = $clprocedimento->sql_query("", "ed40_i_codigo,ed40_c_descr, ed37_c_descr as ed41_i_formaavaliacao", "", $sWhereProcedimentos);
        $result = $clprocedimento->sql_record($sql);
        if($clprocedimento->numrows!=0){
          db_fieldsmemory($result,0);
          echo "<script>".$funcao_js."('$ed40_c_descr',false);</script>";
        }else{
          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
        }
      }else{
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
