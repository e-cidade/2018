<?
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_matmater_classe.php");
include("classes/db_matparamconsulta_classe.php");

db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clmatmater         = new cl_matmater;
$clmatparamconsulta = new cl_matparamconsulta;

$clmatmater->rotulo->label("m60_codmater");
$clmatmater->rotulo->label("m60_descr");

// verifica se é o primeiro acesso do usuário na tela, se for
// pega as configurações de procedimentos >> consulta
// caso o usuário tenha alterado algum parâmetro na lookup
// pega as opções que ele definiu e desconsidera as configurações
// dos procedimentos
if( !isset($m38_visualizacaoitens) or !isset($m38_visualizacaomatestoque) ) {
	$sSql     = $clmatparamconsulta->sql_query(db_getsession("DB_instit"));
	$rsResult = $clmatparamconsulta->sql_record($sSql);
	db_fieldsmemory($rsResult,0);
}

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
            <td width="4%" align="right" nowrap title="<?=$Tm60_codmater?>">
              <?=$Lm60_codmater?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		            db_input("m60_codmater",10,$Im60_codmater,true,"text",4,"","chave_m60_codmater");
		          ?>
            </td>
          </tr>

          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tm60_descr?>">
              <?=$Lm60_descr?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		            db_input("m60_descr",40,$Im60_descr,true,"text",4,"","chave_m60_descr");
		          ?>
            </td>
          </tr>

				  <tr>
				    <td align="right" nowrap title="<?=@$Tm38_visualizacaoitens?>">
				      <b> Mostrar apenas itens da instituição: </b>
				    </td>
				    <td>
						<?
							$x = array('1'=>'Não', '2'=>'Sim');
							db_select('m38_visualizacaoitens',$x,true,2,"");
						?>
				    </td>
				  </tr>

				  <tr>
				    <td align="right" nowrap title="<?=@$Tm38_visualizacaomatestoque?>">
				      <b> Mostrar apenas materiais com estoque: </b>
				    </td>
				    <td>
						<?
							$x = array('f'=>'Não','t'=>'Sim');
							db_select('m38_visualizacaomatestoque',$x,true,1);
						?>
				    </td>
				  </tr>

          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_matmater.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      $sWhereConfig = " ";

      if (!isset($pesquisa_chave)) {

        if (isset($campos)==false) {

           if (file_exists("funcoes/db_func_matmater.php")==true) {
             include("funcoes/db_func_matmater.php");
           } else {
             $campos = "matmater.*";
           }
        }

        // visualizar apenas com movimentação na instituição que está sendo acessada
        if ( $m38_visualizacaoitens == 2) {
          $sWhereConfig .= "and ( instit = ". db_getsession("DB_instit") ." or instit is null ) ";
        }

        // apenas materiais com estoque
        if ( $m38_visualizacaomatestoque == "t") {
          $sWhereConfig .= " and matestoque.m70_codmatmater is not null and ( m70_quant > 0 or m70_valor > 0 )";
        }

        if (isset($chave_m60_codmater) && (trim($chave_m60_codmater)!="") ) {

           $sql = $clmatmater->sql_query_config($chave_m60_codmater,$campos,
                                               "m60_codmater",
                                               "m60_codmater=$chave_m60_codmater and
                                                m60_ativo is true {$sWhereConfig}");

        } else if(isset($chave_m60_descr) && (trim($chave_m60_descr)!="") ) {

           $sql = $clmatmater->sql_query_config("",$campos,"m60_descr",
                                                "m60_descr like '$chave_m60_descr%' and
                                                 m60_ativo is true {$sWhereConfig}");
        } else {
           $sql = $clmatmater->sql_query_config("",$campos,"m60_codmater","m60_ativo is true {$sWhereConfig}");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      } else {

        if($pesquisa_chave!=null && $pesquisa_chave!="") {

          $sSql = $clmatmater->sql_query_config($pesquisa_chave,"*",null,
                                                "m60_codmater=$pesquisa_chave and
                                                 m60_ativo is true {$sWhereConfig}");
          $result = $clmatmater->sql_record($sSql);

          if($clmatmater->numrows!=0) {

            db_fieldsmemory($result,0);
            $m60_descr = str_replace(chr(10), " ", $m60_descr);
            $m60_descr=addslashes($m60_descr);
            echo "<script>".$funcao_js."('".$m60_descr."',false,$pesquisa_chave);</script>";
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
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>