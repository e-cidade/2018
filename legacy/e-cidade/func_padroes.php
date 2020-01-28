<?php

/**
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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_padroes_classe.php");
include("classes/db_rhregime_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clpadroes = new cl_padroes;
$clrotulo = new rotulocampo;
$clrhregime = new cl_rhregime;
$clpadroes->rotulo->label("r02_anousu");
$clpadroes->rotulo->label("r02_mesusu");
$clpadroes->rotulo->label("r02_regime");
$clpadroes->rotulo->label("r02_codigo");
$clpadroes->rotulo->label("r02_descr");
$clrotulo->label("DBtxt23");
$clrotulo->label("DBtxt25");
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
          <legend>Pesquisa de Padrão</legend>
          <table width="35%" border="0" align="center" cellspacing="0">
           <tr>
             <td align="left" nowrap title="Digite o Ano / Mes de competência" >
               <strong>Ano / Mês :&nbsp;&nbsp;</strong>
             </td>
             <td colspan='3'>
             <?
             if(!isset($chave_r02_anousu)){
               $chave_r02_anousu = db_anofolha();
             }
             db_input('DBtxt23',4,$IDBtxt23,true,'text',2,"",'chave_r02_anousu');
             ?>
             &nbsp;/&nbsp;
             <?
             if(!isset($chave_r02_mesusu)){
               $chave_r02_mesusu = db_mesfolha();
             }
             db_input('DBtxt25',2,$IDBtxt25,true,'text',2,"",'chave_r02_mesusu');
             ?>
             </td>
           </tr>
            <tr>
              <td width="4%" align="left" nowrap title="<?=$Tr02_regime?>">
                <?=$Lr02_regime?>
              </td>
              <td width="96%" align="left" nowrap>
                <?
             db_input("r02_regime",12,$Ir02_regime,true,"text",4,"","chave_r02_regime");
             ?>
              </td>
            </tr>
            <tr>
              <td width="4%" align="left" nowrap title="<?=$Tr02_codigo?>">
                <?=$Lr02_codigo?>
              </td>
              <td width="96%" align="left" nowrap>
                <?
             db_input("r02_codigo",12,$Ir02_codigo,true,"text",4,"","chave_r02_codigo");
             ?>
              </td>
            </tr>
            <tr>
              <td width="4%" align="left" nowrap title="<?=$Tr02_descr?>">
                <?=$Lr02_descr?>
              </td>
              <td width="96%" align="left" nowrap>
                <?
             db_input("r02_descr",30,$Ir02_descr,true,"text",4,"","chave_r02_descr");
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
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_padroes.hide();">
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
      <?
      $dbwhere = "";
      if(isset($regime) && trim($regime)!=""){
      	$dbwhere = " and r02_regime = $regime ";
      }
      $where = " r02_instit =".db_getsession("DB_instit");
      // echo $dbwhere;
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_padroes.php")==true){
             include("funcoes/db_func_padroes.php");
           }else{
           $campos = "padroes.*";
           }
        }
        if(isset($chave_r02_regime) && (trim($chave_r02_regime)!="") ){
	         $sql = $clpadroes->sql_query(null,null,null,null,null,$campos,"r02_mesusu"," r02_regime=$chave_r02_regime and r02_anousu=$chave_r02_anousu and r02_mesusu=$chave_r02_mesusu $dbwhere and $where ");
        }else if(isset($chave_r02_codigo) && (trim($chave_r02_codigo)!="") ){
	         $sql = $clpadroes->sql_query(null,null,null,null,null,$campos,"r02_mesusu"," r02_codigo='$chave_r02_codigo' and r02_anousu=$chave_r02_anousu and r02_mesusu=$chave_r02_mesusu $dbwhere and $where");
        }else if(isset($chave_r02_descr) && (trim($chave_r02_descr)!="") ){
	         $sql = $clpadroes->sql_query(null,null,null,null,null,$campos,"r02_descr"," r02_descr like '$chave_r02_descr%' and r02_anousu=$chave_r02_anousu and r02_mesusu=$chave_r02_mesusu $dbwhere and $where");
        }else{
           $sql = $clpadroes->sql_query(null,null,null,null,null,$campos,"r02_regime,r02_codigo","r02_anousu=$chave_r02_anousu and r02_mesusu=$chave_r02_mesusu $dbwhere and $where");
        }
        // echo $sql;
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clpadroes->sql_record($clpadroes->sql_query(null,null,null,null,null,"*","","r02_codigo='$pesquisa_chave' and r02_anousu=$chave_r02_anousu and r02_mesusu=$chave_r02_mesusu $dbwhere and $where"));
          if($clpadroes->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$r02_descr',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
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