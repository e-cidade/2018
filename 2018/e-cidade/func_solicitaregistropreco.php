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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_solicitaregistropreco_classe.php");

$oGet  = db_utils::postMemory($_GET,0);
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clsolicitaregistropreco = new cl_solicitaregistropreco;
$clsolicitaregistropreco->rotulo->label("pc54_sequencial");
$clsolicitaregistropreco->rotulo->label("pc54_sequencial");
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
            <td width="4%" align="right" nowrap title="<?=$Tpc54_sequencial?>">
              <?=$Lpc54_sequencial?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("pc54_sequencial",10,$Ipc54_sequencial,true,"text",4,"","chave_pc54_sequencial");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tpc54_sequencial?>">
             <b>Solicitação:</b>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("pc54_solicita",10,$Ipc54_sequencial,true,"text",4,"","chave_pc54_solicita");
		       ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_solicitaregistropreco.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      $sWhereInstituicao = "";

      if (!empty($oGet->lFiltraInstituicao) && $oGet->lFiltraInstituicao == true) {
        $sWhereInstituicao = " and solicita.pc10_instit = ".db_getsession("DB_instit");
      }

      $lPermiteAlterarAbertura = false;
      $aParametrosRegistro = db_stdClass::getParametro("registroprecoparam",array(db_getsession("DB_instit")));

      if (count($aParametrosRegistro) > 0) {
        $lPermiteAlterarAbertura = $aParametrosRegistro[0]->pc08_alteraabertura=="t"?true:false;
      }

      $sWhere = " pc10_solicitacaotipo = 3 ";
      $and    = " and ";
      if (isset($liberado)) {

        $sWhere .= "and pc54_liberado is true";
        $and    = "  ";
      }

      if (isset($trazsemcompilacao)) {

        $sWhere .= "  and not exists(select 1
                                  from solicitavinculo vinculo
                                       inner join solicita filho on pc53_solicitafilho = pc10_numero
                                       left join solicitaanulada on  filho.pc10_numero = pc67_solicita
                                 where vinculo.pc53_solicitapai =  solicita.pc10_numero
                                   and filho.pc10_solicitacaotipo = 6
                                   and pc67_sequencial is null )";
      }

      if (!$lPermiteAlterarAbertura) {

        if (isset($estimativas)) {

        	if ($estimativas == 1) {
        		$sExists = " not ";
        	} else {
        		$sExists = "  ";
        	}
          $sWhere .= "  and {$sExists} exists(select 1
                                    from solicitavinculo vinculo
                                         inner join solicita filho on pc53_solicitafilho = filho.pc10_numero
                                         left join solicitaanulada on  filho.pc10_numero = pc67_solicita
                                   where vinculo.pc53_solicitapai =  solicita.pc10_numero
                                     and filho.pc10_solicitacaotipo = 4
                                     and pc67_sequencial is null)";
        }
      } else {
        if (isset($estimativas)) {

          if ($estimativas == 1) {
            $sExists = " not ";
          } else {
            $sExists = "  ";
          }
          $sWhere .= "  and {$sExists} exists(select 1
                                    from solicitavinculo vinculo
                                         inner join solicita filho on pc53_solicitafilho = filho.pc10_numero
                                         left join solicitaanulada on  filho.pc10_numero = pc67_solicita
                                   where vinculo.pc53_solicitapai   =  solicita.pc10_numero
                                     and filho.pc10_solicitacaotipo = 6
                                     and pc67_sequencial is null)";
         }
      }

      if (isset($noperiodo)) {
        $sWhere .= " and cast('".date("Y-m-d", db_getsession("DB_datausu"))."' as date) between pc54_datainicio and pc54_datatermino";
      }

      if (isset ($anuladas)) {

      	if ($anuladas  == 1) {
      		$sWhere .= " and pc67_sequencial is null ";
      	} else {
      		$sWhere .= " and pc67_sequencial is not null ";
      	}
      }
      if (!empty($formacontrole)) {
        $sWhere .= "and pc54_formacontrole in ({$formacontrole})";
      } else {
        $sWhere .= "and pc54_formacontrole = 1 ";
      }
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_solicitaregistropreco.php")==true){
             include("funcoes/db_func_solicitaregistropreco.php");
           }else{
           $campos = "solicitaregistropreco.*,nomeinst";
           }
        }
        $campos .= ", solicita.pc10_resumo, nomeinst";
        $sWhere  = $sWhere.$sWhereInstituicao;


        if(isset($chave_pc54_sequencial) && (trim($chave_pc54_sequencial)!="") ){
	         $sql = $clsolicitaregistropreco->sql_query_solicitaanulada(null,$campos,
	                                                   "pc54_sequencial",
	                                                   "pc54_sequencial= {$chave_pc54_sequencial} and {$sWhere}"
	                                                   );
        }else if(isset($chave_pc54_solicita) && (trim($chave_pc54_solicita)!="") ){

            if(!empty($sWhere)) {
              $and= " and ";
            }

	         $sql = $clsolicitaregistropreco->sql_query_solicitaanulada("",$campos,
	                                                    "pc54_solicita",
	                                                    "pc54_solicita = {$chave_pc54_solicita}
	                                                   {$and} {$sWhere}"
	                                                   );
        }else{
           $sql = $clsolicitaregistropreco->sql_query_solicitaanulada("",$campos,"pc54_sequencial","{$sWhere}");
        }
        $repassa = array();

        if(isset($chave_pc54_sequencial)){
          $repassa = array("chave_pc54_sequencial"=>$chave_pc54_sequencial,"chave_pc54_solicita"=>$chave_pc54_solicita);
        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clsolicitaregistropreco->sql_record($clsolicitaregistropreco->sql_query($pesquisa_chave));
          if($clsolicitaregistropreco->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$pc54_sequencial',false);</script>";
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
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_pc54_sequencial",true,1,"chave_pc54_sequencial",true);
</script>