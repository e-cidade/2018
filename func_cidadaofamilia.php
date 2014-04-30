<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_cidadaofamilia_classe.php");
db_postmemory($_POST);
db_postmemory($_GET);

$lNIS = null;

if (isset($sNIS) && $sNIS == "true") {
  $lNIS = true;
} else if (isset($sNIS) && $sNIS == "false") {
  $lNIS = false;
}


$oRotuloCampo     = new rotulocampo();
$oRotuloCampo->label("as15_codigofamiliarcadastrounico");
$clcidadaofamilia = new cl_cidadaofamilia;
$clcidadaofamiliacadastrounico = new cl_cidadaofamiliacadastrounico;
$clcidadaofamilia->rotulo->label("as04_sequencial");
$clcidadaofamiliacadastrounico->rotulo->label("as15_codigofamiliarcadastrounico");
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
            <td width="4%" align="right" nowrap title="<?=$Tas04_sequencial?>">
              <?=$Las04_sequencial?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("as04_sequencial",10,$Ias04_sequencial,true,"text",4,"","chave_as04_sequencial");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tas15_codigofamiliarcadastrounico?>">
              <?=$Las15_codigofamiliarcadastrounico?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("as15_codigofamiliarcadastrounico",10,$Ias15_codigofamiliarcadastrounico,true,"text",4,"","chave_as15_codigofamiliarcadastrounico");
		       ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_cidadaofamilia.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      $campos  = "cidadaofamilia.as04_sequencial, cidadaofamiliacadastrounico.as15_codigofamiliarcadastrounico,";
      $campos .= " cidadaocomposicaofamiliar.as03_cidadaofamilia, cidadao.ov02_nome,";
      $campos .= " cidadaocadastrounico.as02_sequencial, cidadaocadastrounico.as02_nis,";
      $campos .= " cidadao.ov02_sequencial";

      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_cidadaofamilia.php")==true){
             include("funcoes/db_func_cidadaofamilia.php");
           }else{
           $campos = "cidadaofamilia.*";
           }
        }
        if(isset($chave_as04_sequencial) && (trim($chave_as04_sequencial)!="") ) {
	         $sql = $clcidadaofamilia->sql_query_responsavel($chave_as04_sequencial,$campos,"as04_sequencial");
        }else if(isset($chave_as15_codigofamiliarcadastrounico) && (trim($chave_as15_codigofamiliarcadastrounico)!="")) {
          $sql = $clcidadaofamilia->sql_query_responsavel("",$campos,"as15_codigofamiliarcadastrounico"," as15_codigofamiliarcadastrounico like '$chave_as15_codigofamiliarcadastrounico%' ");
        }else{
           $sql = $clcidadaofamilia->sql_query_responsavel("",$campos,"as04_sequencial","");
        }

        $repassa = array();
        if(isset($chave_as15_codigofamiliarcadastrounico)) {

          if (isset($sTipoRetorno) && $sTipoRetorno == 'relatorio') {

            $repassa = array("chave_as04_sequencial"=>$chave_as04_sequencial,
                             "chave_as15_codigofamiliarcadastrounico"=>$chave_as15_codigofamiliarcadastrounico,
                             "chave_ov02_nome"=>$chave_ov02_nome,
                             "chave_as02_nis"=>$chave_as02_nis
                             );
          } else {

            $repassa = array("chave_as04_sequencial"=>$chave_as04_sequencial,
                             "chave_as15_codigofamiliarcadastrounico"=>$chave_as15_codigofamiliarcadastrounico);
          }
        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != "") {

          $sSql = '';
          if (isset($sTipoRetorno) && $sTipoRetorno == "relatorio" && !$lNIS) {
            $sWhere = " as15_codigofamiliarcadastrounico like '%$pesquisa_chave'";
            $sSql   = $clcidadaofamilia->sql_query_responsavel(null, "*", null, $sWhere);

          } else if (isset($sTipoRetorno) && $sTipoRetorno == "relatorio" && $lNIS) {

            $sWhere = " as02_nis = '$pesquisa_chave'";
            $sSql   = $clcidadaofamilia->sql_query_responsavel(null, "*", null, $sWhere);
          } else {
            $sSql = $clcidadaofamilia->sql_query_responsavel($pesquisa_chave);
          }

          $result = $clcidadaofamilia->sql_record($sSql);

          if ($clcidadaofamilia->numrows != 0) {

            db_fieldsmemory($result,0);
            if (isset($sTipoRetorno) && $sTipoRetorno == 'cadunico') {
              echo "<script>".$funcao_js."('$as15_codigofamiliarcadastrounico',false, '$ov02_nome', '$as02_sequencial', '$as03_cidadaofamilia');</script>";
            } else if (isset($sTipoRetorno) && $sTipoRetorno == "relatorio" && !$lNIS) {
              echo "<script>".$funcao_js."('$as15_codigofamiliarcadastrounico',false, '$ov02_nome', '$as04_sequencial', $as02_nis);</script>";
            } else if (isset($sTipoRetorno) && $sTipoRetorno == "relatorio" && $lNIS) {
              echo "<script>".$funcao_js."('$as15_codigofamiliarcadastrounico',false, '$ov02_nome', '$as04_sequencial', '$as02_nis');</script>";
            }else {
              echo "<script>".$funcao_js."('$as15_codigofamiliarcadastrounico',false, '$ov02_nome', '$as04_sequencial');</script>";
            }
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true, '', '');</script>";
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
//js_tabulacaoforms("form2","chave_as15_codigofamiliarcadastrounico",true,1,"chave_as04_codigofamiliarcadastrounico",true);
</script>