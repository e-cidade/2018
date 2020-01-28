<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
include("classes/db_portaria_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrotulo   = new rotulocampo;
$clportaria = new cl_portaria;
$clportaria->rotulo->label("h31_sequencial");
$clportaria->rotulo->label("h31_numero");
$clrotulo->label("h42_descr");
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
            <td width="4%" align="right" nowrap title="Portarias emitidas entre as datas.">
              <b>Emitidas entre : </b>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
              if(!isset($dataini)){
                $dataini = date('d/m/Y',mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
                $datafim = date("d/m/Y");
              }
              //echo $dataini.'   '.$datafim;
		          db_input("dataini",10,$dataini,true,"text",2,"");
		          ?>
              <b> a </b>
              <?
		          db_input("datafim",10,$datafim,true,"text",2,"");
              ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Th31_numero?>">
              <?=$Lh31_numero?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("h31_numero",10,$Ih31_numero,true,"text",4,"","chave_h31_numero");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Th42_descr?>">
              <?=$Lh42_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("h42_descr",40,$Ih42_descr,true,"text",4,"","chave_h42_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_portaria.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_portaria.php")==true){
             include("funcoes/db_func_portaria.php");
           }else{
           $campos = "portaria.*";
           }
        }
        $campos = "distinct ".$campos;

        if(isset($chave_h42_descr) && (trim($chave_h42_descr)!="") ){
	         $sql = $clportaria->sql_query("",$campos,"h31_dtportaria desc,  h31_sequencial desc"," upper(h42_descr) like '$chave_h42_descr%' ");
        }elseif(isset($chave_h31_numero) && (trim($chave_h31_numero)!="") ){
	         $sql = $clportaria->sql_query("",$campos,"h31_numero"," h31_numero like '$chave_h31_numero' ");
        }else if(isset($lcoletiva)) {
        	 $sql = $clportaria->sql_query("",$campos,"h31_sequencial desc"," h31_sequencial in (  select h33_portaria from portariaassenta  group by h33_portaria having  count(h33_portaria) > 1 ) ");
        }else{
             $sql = $clportaria->sql_query("",$campos,"h31_dtportaria desc,  h31_sequencial desc"," h31_dtportaria between to_date('$dataini','dd/mm/yyyy') and to_date('$datafim','dd/mm/yyyy')");
        }
        $repassa = array();
        if(isset($chave_h42_descr)||isset($chave_h31_numero)){
          $repassa = array("chave_h31_numero"=>$chave_h31_numero,"chave_h42_descr"=>$chave_h42_descr);
        }
        //echo $sql;
	      if(isset($sql) && trim($sql) != ""){
           db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa,false);
        }
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clportaria->sql_record($clportaria->sql_query($pesquisa_chave));
          if($clportaria->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$h31_sequencial',false);</script>";
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
js_tabulacaoforms("form2","chave_h31_sequencial",true,1,"chave_h31_sequencial",true);
</script>