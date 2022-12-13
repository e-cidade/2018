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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_solicita_classe.php");
require_once("classes/db_pcorcamitemsol_classe.php");
require_once("classes/db_pcorcamitemproc_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clsolicita = new cl_solicita;
$clpcorcamitemsol = new cl_pcorcamitemsol;
$clpcorcamitemproc= new cl_pcorcamitemproc;
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
                db_inputdata("pc10_data", "","", "", true,"text",4,"","chave_pc10_data");
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
        <?php
        $where_solicitacao = "";

        $sWhereSolicitacaoAnulada = " not exists (select 1 from solicitaanulada where pc67_solicita = pc10_numero) ";

        if( !empty($orc) && !isset($proc) ) {

          $result_itemsol = $clpcorcamitemsol->sql_record($clpcorcamitemsol->sql_query_solicitem(null,null,"distinct pc11_numero as pesquisa_chave",""," pc22_codorc=$orc and {$sWhereSolicitacaoAnulada}"));
          if($clpcorcamitemsol->numrows>0){
            db_fieldsmemory($result_itemsol,0);
          }
          $where_solicitacao = " and pc81_codprocitem is null and pc10_correto='t'";
          if(isset($departamento) && trim($departamento)!=""){
            $where_solicitacao .= " and pc10_depto=$departamento ";
          }
        }else if(isset($orc) && isset($proc)){
          $result_itemproc = $clpcorcamitemproc->sql_record($clpcorcamitemproc->sql_query_solicitem(null,null,"distinct pc11_numero as pesquisa_chave",""," pc22_codorc=$orc and {$sWhereSolicitacaoAnulada}"));
          if($clpcorcamitemproc->numrows>0){
            db_fieldsmemory($result_itemproc,0);
          }
        }
        if(!isset($pesquisa_chave)){
          if(isset($campos)==false){
            if(file_exists("funcoes/db_func_solicita.php")==true){
              include("funcoes/db_func_solicita.php");
            }else{
              $campos = "solicita.*";
            }
          }
          $campos = " distinct ".$campos;
          if(isset($chave_pc10_numero) && (trim($chave_pc10_numero)!="") ){
            $sql = $clsolicita->sql_query(null,$campos,"pc10_numero desc"," pc10_numero= $chave_pc10_numero $where_solicitacao and {$sWhereSolicitacaoAnulada}");
          }else if(isset($chave_pc10_data) && (trim($chave_pc10_data)!="") ){

            $oData = new DBDate($chave_pc10_data);
            $sql = $clsolicita->sql_query("",$campos,"pc10_data desc"," pc10_data = '{$oData->getDate(DBDate::DATA_PTBR)}' $where_solicitacao and {$sWhereSolicitacaoAnulada} ");
          }else{
            $sql = $clsolicita->sql_query("",$campos,"pc10_numero desc"," 1=1 $where_solicitacao and {$sWhereSolicitacaoAnulada}");
          }

          db_lovrot($sql,15,"()","",$funcao_js);
        }else{
          if($pesquisa_chave!=null && $pesquisa_chave!=""){
            $result = $clsolicita->sql_record($clsolicita->sql_query(null,"*",""," pc10_numero=".$pesquisa_chave.$where_solicitacao." and {$sWhereSolicitacaoAnulada}"));
            if($clsolicita->numrows!=0){
              db_fieldsmemory($result,0);
              echo "<script>".$funcao_js."('$pc10_numero',false);</script>";
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