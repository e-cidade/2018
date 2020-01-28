<?
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_conplano_classe.php"));
db_postmemory($_POST);
extract($_GET);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clconplano = new cl_conplano;
$clconplano->rotulo->label("c60_codcon");
$clconplano->rotulo->label("c60_descr");
$clconplano->rotulo->label("c60_estrut");
$clrotulo = new rotulocampo;
$clrotulo->label("c61_reduz");

$aWhere = array();
$sAno   = db_getsession("DB_anousu");
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
            <td width="4%" align="right" nowrap title="<?=$Tc60_codcon?>">
              <?=$Lc60_codcon?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("c60_codcon",6,$Ic60_codcon,true,"text",4,"","chave_c60_codcon");
		       ?>
            </td>
            <td width="4%" align="right" nowrap title="<?=$Tc60_estrut?>">
              <?=$Lc60_estrut?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("c60_estrut",15,$Ic60_estrut,true,"text",4,"","chave_c60_estrut");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tc61_reduz?>">
              <?=$Lc61_reduz?>
	    </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("c61_reduz",6,$Ic61_reduz,true,"text",4,"","chave_c61_reduz");
		       ?>
            </td>
            <td width="4%" align="right" nowrap title="<?=$Tc60_descr?>">
              <?=$Lc60_descr?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("c60_descr",50,$Ic60_descr,true,"text",4,"","chave_c60_descr");
		       ?>
            </td>
          </tr>
          <tr>
            <td colspan="4" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_conplano.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      if (!isset($pesquisa_chave)) {
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_conplano.php")==true){
             include(modification("funcoes/db_func_conplano.php"));
           }else{
           $campos = "conplano.*";
           }
        }
        echo @$chave_c60_codcon;

        $aWhere[] = 'c60_anousu='.$sAno;
        $sOrder   = 'c60_estrut';

        if (!empty($regimeCompetencia)) {

           switch ($tipoProgramacao) {

             case '1':
               $aWhere[] = "c60_estrut LIKE '1%'";
               break;

             case '2':
               $aWhere[] = "c60_estrut LIKE '3%'";
               break;
           }
        }

        if(isset($chave_c60_codcon) && (trim($chave_c61_reduz)!="")) {

          $aWhere[] = "c61_reduz=$chave_c61_reduz";
          $sOrder   = 'c60_codcon';
        } elseif (isset($chave_c60_codcon) && (trim($chave_c60_codcon)!="")) {

          $aWhere[] = "c60_codcon = $chave_c60_codcon";
          $sOrder   = 'c60_codcon';
        } elseif (isset($chave_c60_estrut) && (trim($chave_c60_estrut)!="")) {

          $aWhere[] = "c60_estrut like '$chave_c60_estrut%'";
          $sOrder   = 'c60_codcon';
        } elseif (isset($chave_c60_descr) && (trim($chave_c60_descr)!="")) {

          $aWhere[] = "upper(c60_descr) like '$chave_c60_descr%'";
          $sOrder   = 'c60_descr';
        } elseif(isset($tipo_sql)) {//zé... coloquei esta opcao para o formulario do tabrec

          $sOrder   = 'c60_estrut';
          $campos   = $campos . ",c61_reduz as db_c61_reduz,c60_estrut as db_c60_estrut";
        }

        $sWhere = implode(' and ', $aWhere);
        $sSql   = $clconplano->sql_query("", null, $campos, $sOrder, $sWhere);

        db_lovrot($sSql,15,"()","",$funcao_js);
      }else{

        if($pesquisa_chave!=null && $pesquisa_chave!=""){

          if (isset($lPesquisaCodigo) && $lPesquisaCodigo) {

            $sWhereAutocomplete = " c60_anousu = ".db_getsession("DB_anousu")." and c60_codcon = {$pesquisa_chave}";
            if (!empty($regimeCompetencia)) {

              switch ($tipoProgramacao) {

               case '1':
                 $sWhereAutocomplete .= " AND c60_estrut LIKE '1%'";
                 break;

               case '2':
                 $sWhereAutocomplete .= " AND c60_estrut LIKE '3%'";
                 break;
              }
            }

            $result = $clconplano->sql_record($clconplano->sql_query(null, null, "*", null, $sWhereAutocomplete));
            if($clconplano->numrows!=0){
              db_fieldsmemory($result,0);
              echo "<script>".$funcao_js."('$c60_descr',false, '$c60_estrut');</script>";
            }else{
             echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
            }
          } else {

            $result = $clconplano->sql_record($clconplano->sql_query(null,null,"*",null," c60_anousu = ".db_getsession("DB_anousu")." and c61_reduz = {$pesquisa_chave}"));
            if($clconplano->numrows!=0){
              db_fieldsmemory($result,0);
              echo "<script>".$funcao_js."('$c60_descr',false, '$c60_estrut');</script>";
            }else{
             echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
            }
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
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
