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
require_once(modification("classes/db_censoativcompl_classe.php"));

db_postmemory($_POST);

$oGet = db_utils::postMemory($_GET);

$oDaoCalendario    = new cl_calendario();
$sSqlAnoCalendario = $oDaoCalendario->sql_query_file($oGet->iCalendario, "ed52_i_ano");
$rsAnoCalendario   = db_query( $sSqlAnoCalendario );

$iAno = 2014;
if ( $rsAnoCalendario && pg_num_rows($rsAnoCalendario) > 0 ) {
  $iAno = db_utils::fieldsMemory($rsAnoCalendario, 0)->ed52_i_ano;
}

$aFiltroAtividades = array();

if ( $iAno == 2015 ) {
  $aFiltroAtividades = DadosCenso::getAtividadesComplementaresExcluidasCenso2015();
}
$aWhere = array();
if ( count($aFiltroAtividades) > 0) {
  $aWhere[] = " ed133_i_codigo not in (" . implode(", ", $aFiltroAtividades) . ") ";
}

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcensoativcompl = new cl_censoativcompl;
$clcensoativcompl->rotulo->label("ed133_i_codigo");
$clcensoativcompl->rotulo->label("ed133_c_descr");
$clcensoativcompl->rotulo->label("ed133_i_tipo");
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
     <td width="4%" align="right" nowrap title="<?=$Ted133_i_codigo?>">
      <?=$Led133_i_codigo?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("ed133_i_codigo",10,$Ied133_i_codigo,true,"text",4,"","chave_ed133_i_codigo");?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Ted133_c_descr?>">
      <?=$Led133_c_descr?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("ed133_c_descr",50,$Ied133_c_descr,true,"text",4,"","chave_ed133_c_descr");?>
     </td>
    </tr>
     <td width="4%" align="right" nowrap title="<?=$Ted133_i_tipo?>">
      <?=$Led133_i_tipo?>
     </td>
     <td width="96%" align="left" nowrap>
      <?
      $x = array(  ''  => ''
                 ,'31' => 'ACOMPANHAMENTO PEDAGÓGICO (REFORÇO ESCOLAR)'
                 ,'14' => 'ARTES CÊNICAS'
                 ,'12' => 'ARTES PLÁSTICAS'
                 ,'22' => 'ATIVIDADES DESPORTIVAS'
                 ,'89' => 'ATIVIDADES DE INICIAÇÃO PROFISSIONAL'
                 ,'13' => 'CINEMA'
                 ,'41' => 'DIREITOS HUMANOS E CIDADANIA'
                 ,'133'=> 'EDUCAÇÃO AMBIENTAL, DESENVOLVIMENTO SUSTENTÁVEL '
                 ,'61' => 'INCLUSÃO DIGITAL E COMUNICAÇÃO'
                 ,'51' => 'MEIO AMBIENTE E DESENVOLVIMENTO SUSTENTÁVEL'
                 ,'11' => 'MÚSICA'
                 ,'39' => 'OUTRA CATEGORIA DE ACOMPANHAMENTO PEDAGÓGICO'
                 ,'19' => 'OUTRA CATEGORIA DE ARTE E CULTURA'
                 ,'49' => 'OUTRA CATEGORIA DE DIREITOS HUMANOS E CIDADANIA'
                 ,'29' => 'OUTRA CATEGORIA DE ESPORTE E LAZER'
                 ,'69' => 'OUTRA CATEGORIA DE INCLUSÃO DIGITAL E COMUNICAÇÃO'
                 ,'59' => 'OUTRA CATEGORIA DE MEIO AMBIENTE E DESENVOLVIMENTO'
                 ,'99' => 'OUTRA CATEGORIA DE PROGRAMA INTERSETORIAL'
                 ,'79' => 'OUTRA CATEGORIA DE SAÚDE, ALIMENTAÇÃO E PREVENÇÃO'
                 ,'21' => 'RECREAÇÃO/LAZER'
                 ,'71' => 'SAÚDE, ALIMENTAÇÃO E  PREVENÇÃO'
                 ,'91' => 'PROGRAMAS INTERSETORIAIS'
                );
      db_select('chave_ed133_i_tipo',$x,true, 1 ,"");
      ?>
     </td>
    </tr>
    <tr>
     <td colspan="2" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_censoativcompl.hide();">
     </td>
    </tr>
    </form>
   </table>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
    <?php
    if(!isset($pesquisa_chave)) {

      if ( isset($campos)==false) {

        if(file_exists("funcoes/db_func_censoativcompl.php")==true){
          include(modification("funcoes/db_func_censoativcompl.php"));
        }else{
          $campos = "censoativcompl.*";
        }
      }

      if (isset($chave_ed133_i_codigo) && (trim($chave_ed133_i_codigo)!="") ) {
        $aWhere[] = " ed133_i_codigo = {$chave_ed133_i_codigo} ";
      } else if(isset($chave_ed133_c_descr) && (trim($chave_ed133_c_descr)!="") ){
        $aWhere[] = " ed133_c_descr like '{$chave_ed133_c_descr}%' ";
      } else if(isset($chave_ed133_i_tipo) && (trim($chave_ed133_i_tipo)!="") ) {
        $aWhere[] = " ed133_i_tipo = {$chave_ed133_i_tipo} ";
      }

      $sWhere = implode(" and ", $aWhere );
      $sql    = $clcensoativcompl->sql_query("",$campos,"ed133_c_descr", $sWhere);

      $repassa = array();
      if(isset($chave_ed133_i_codigo)){
       $repassa = array("chave_ed133_i_codigo"=>$chave_ed133_i_codigo,"chave_ed133_c_descr"=>$chave_ed133_c_descr,"chave_ed133_i_tipo"=>$chave_ed133_i_tipo);
      }
      db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
    } else {

      if ($pesquisa_chave!=null && $pesquisa_chave!="") {

        $aWhere[] = " ed133_i_codigo = {$pesquisa_chave} ";
        $sWhere   = implode(" and ", $aWhere );
        $sSql     = $clcensoativcompl->sql_query(null, "ed133_c_descr", null, $sWhere);
        $result   = $clcensoativcompl->sql_record($sSql);

        if ($clcensoativcompl->numrows!=0) {

          db_fieldsmemory($result,0);
          echo "<script>".$funcao_js."('$ed133_c_descr',false);</script>";
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
<script>
js_tabulacaoforms("form2","chave_ed133_c_descr",true,1,"chave_ed133_c_descr",true);
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
