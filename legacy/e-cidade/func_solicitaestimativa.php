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

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clsolicita = new cl_solicita;
$clsolicita->rotulo->label("pc10_numero");
$clsolicita->rotulo->label("pc10_data");
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
            <td width="4%" align="right" nowrap title="<?=$Tpc10_numero?>">
              <?=$Lpc10_numero?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("pc10_numero",10,$Ipc10_numero,true,"text",4,"","chave_pc10_numero");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tpc10_data?>">
              <?=$Lpc10_data?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
	         db_input("pc10_data",10,$Ipc10_data,true,"text",4,"","chave_pc10_data");
           db_input("param",10,"",false,"hidden",3);
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_solicita.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?


       $where_depart = "";
       if (isset($departamento) && trim($departamento)!="") {
         $where_depart .= " and pc10_depto=".db_getsession("DB_coddepto");
      }

      if (isset($nada)) {
        $where_depart = "";
      }
      if (isset($comcompilacao)) {

        if ($comcompilacao == 1) {
          $sExists = " not ";
        } else {
          $sExists = "  ";
        }
        $where_depart .= "  and {$sExists}
         exists(SELECT abertura.pc10_numero,
			                 estimativa.pc53_solicitafilho,
			                 compilacao.pc53_solicitafilho
			            from solicitavinculo as estimativa
			                  inner join solicita   as     abertura        on abertura.pc10_numero = estimativa.pc53_solicitapai
			                  inner join solicitavinculo as compilacao     on abertura.pc10_numero = compilacao.pc53_solicitapai
			                  inner join solicita   as     dadoscompilacao on compilacao.pc53_solicitafilho = dadoscompilacao.pc10_numero
			                                                              and dadoscompilacao.pc10_solicitacaotipo = 6
                        left join solicitaanulada on dadoscompilacao.pc10_numero = pc67_solicita
			            where estimativa.pc53_solicitafilho = solicita.pc10_numero
			              and pc67_solicita is null

               )";
      }

      if (isset ($anuladas)) {

        if ($anuladas  == 1) {
          $where_depart .= " and pc67_sequencial is null ";
        } else {
          $where_depart .= " and pc67_sequencial is not null ";
        }
      }

      if (!empty($formacontrole)) {

        $where_depart .= " and exists(select *                                                                                     \n";
        $where_depart .= "              from solicitavinculo as estimativa                                                         \n";
        $where_depart .= "                   inner join solicita as abertura on estimativa.pc53_solicitapai = abertura.pc10_numero \n";
        $where_depart .= "                   inner join solicitaregistropreco on pc54_solicita = abertura.pc10_numero              \n";
        $where_depart .= "             where estimativa.pc53_solicitafilho = solicita.pc10_numero                                  \n";
        $where_depart .= "               and pc54_formacontrole = {$formacontrole})                                                \n";
      }

      if (!isset($pesquisa_chave)) {
        if (isset($campos)==false) {
          if (file_exists("funcoes/db_func_solicita.php")==true) {
            include("funcoes/db_func_solicita.php");
          } else {
            $campos = "solicita.*";
          }
        }

        $where_depart .= " and pc10_instit          = ". db_getsession("DB_instit");
        $where_depart .= " and pc10_solicitacaotipo = 4";
        $campos = " distinct ".$campos;
        if (isset($chave_pc10_numero) && (trim($chave_pc10_numero)!="") ) {
          $sql = $clsolicita->sql_query_solicitaanulada(null,$campos,"pc10_numero desc "," pc10_numero=$chave_pc10_numero $where_depart ");
        } else if (isset($chave_pc10_data) && (trim($chave_pc10_data)!="") ) {
          $sql = $clsolicita->sql_query_solicitaanulada("",$campos,"pc10_numero desc "," pc10_data like '$chave_pc10_data%' $where_depart ");
        } else {
          $sql = $clsolicita->sql_query_solicitaanulada("",$campos,"pc10_numero desc "," 1=1 $where_depart");
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",array(),false);
      } else {
        if ($pesquisa_chave!=null && $pesquisa_chave!="") {
          $result = $clsolicita->sql_record($clsolicita->sql_query_solicitaanulada(null,"distinct *",""," pc10_numero=$pesquisa_chave $where_depart "));
          if ($clsolicita->numrows!=0) {
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$pc10_data','false');</script>";
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