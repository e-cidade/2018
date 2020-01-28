<?php

/**
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
require_once("classes/db_bancoagencia_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clbancoagencia = new cl_bancoagencia;
$clbancoagencia->rotulo->label("db89_sequencial");
$clbancoagencia->rotulo->label("db89_codagencia");

$aWhereBancos = array("1=1");
if (isset($db89_db_bancos)) {
  $aWhereBancos[] = "db89_db_bancos = '{$db89_db_bancos}'";
}


$sWhere = implode(" and ", $aWhereBancos);
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
      <form name="form2" method="post" action="" >
        <fieldset style="width: 35%">
          <legend>Pesquisa de Agência</legend>
          <table width="35%" border="0" align="center" cellspacing="0">
            <tr>
              <td width="4%" align="left" nowrap title="<?=$Tdb89_sequencial?>">
                <?=$Ldb89_sequencial?>
              </td>
              <td width="96%" align="left" nowrap>
                <?
             db_input("db89_sequencial",10,$Idb89_sequencial,true,"text",4,"","chave_db89_sequencial");
             ?>
              </td>
            </tr>
            <tr>
              <td width="4%" align="left" nowrap title="<?=$Tdb89_codagencia?>">
                <?=$Ldb89_codagencia?>
              </td>
              <td width="96%" align="left" nowrap>
                <?
             db_input("db89_codagencia",10,$Idb89_codagencia,true,"text",4,"","chave_db89_codagencia");
             ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <table width="35%" border="0" align="center" cellspacing="0">
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_bancoagencia.hide();">
            </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <fieldset>
        <legend>Resultado da Pesquisa</legend>
      <?php
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_bancoagencia.php")==true){
             include("funcoes/db_func_bancoagencia.php");
           }else{
            $campos = "bancoagencia.*";
           }
        }
        if(isset($chave_db89_sequencial) && (trim($chave_db89_sequencial)!="") ){

           $sWhere .= " and db89_sequencial = {$chave_db89_sequencial} ";
	         $sql = $clbancoagencia->sql_query(null,$campos,"db89_sequencial", $sWhere);
        }else if(isset($chave_db89_codagencia) && (trim($chave_db89_codagencia)!="") ){

           $sWhere .= " and db89_codagencia like '{$chave_db89_codagencia}%' ";
	         $sql = $clbancoagencia->sql_query("",$campos,"db89_codagencia", $sWhere);
        }else{
           $sql = $clbancoagencia->sql_query("",$campos,"db89_sequencial", $sWhere);
        }
        $repassa = array();
        if(isset($chave_db89_codagencia)){
          $repassa = array("chave_db89_sequencial"=>$chave_db89_sequencial,"chave_db89_codagencia"=>$chave_db89_codagencia);
        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);

      } else {

    		if($pesquisa_chave!=null && $pesquisa_chave!=""){

    			if ( isset($pesquisaSeq) ) {
    				$sWhere = " db89_sequencial = $pesquisa_chave ";
    			} else {
    				$sWhere = " db89_codagencia = '$pesquisa_chave' ";
    			}

          $result = $clbancoagencia->sql_record($clbancoagencia->sql_query(null,"*",null,$sWhere));
          if($clbancoagencia->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."(false,'$db89_db_bancos','$db90_descr','$db89_codagencia','$db89_digito','$db89_sequencial');</script>";
          } else {
            echo "<script>".$funcao_js."(true,'','','','','');</script>";
          }
        } else {
        	echo "<script>".$funcao_js."(false,'','','','','');</script>";
        }

      }
      ?>
      </fieldset>
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
js_tabulacaoforms("form2","chave_db89_codagencia",true,1,"chave_db89_codagencia",true);
</script>