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
include("classes/db_liclicita_classe.php");
include("classes/db_liclicitem_classe.php");

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clliclicitem = new cl_liclicitem;
$clliclicita  = new cl_liclicita;

$clliclicita->rotulo->label("l20_codigo");
$clliclicita->rotulo->label("l20_numero");
$clliclicita->rotulo->label("l20_edital");
$clrotulo = new rotulocampo;
$clrotulo->label("l03_descr");

$sWhereContratos = " and 1 = 1 ";
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
            <td width="4%" align="right" nowrap title="<?=$Tl20_codigo?>">
              <?=$Ll20_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("l20_codigo",10,$Il20_codigo,true,"text",4,"","chave_l20_codigo");
		       ?>
            </td>
            </tr>

             <tr>
            <td width="4%" align="right" nowrap title="<?=$Tl20_edital?>">
              <?=$Ll20_edital?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
           db_input("l20_edital",10,$Il20_edital,true,"text",4,"","chave_l20_edital");
           ?>
            </td>
            </tr>

            <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tl20_numero?>">
              <?=$Ll20_numero?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("l20_numero",10,$Il20_numero,true,"text",4,"","chave_l20_numero");
		       ?>
            </td>
          </tr>
           <tr>

          <tr> 
          <td width="4%" align="right" nowrap title="<?=$Tl03_descr?>">
              <?=$Ll03_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
	        db_input("l03_descr",60,$Il03_descr,true,"text",4,"","chave_l03_descr");
                db_input("param",10,"",false,"hidden",3);
	      ?>
            </td>
          </tr>          
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_liclicita.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $and            = "and ";
      $dbwhere        = "";
      if (isset($tipo) && trim($tipo)!=""){
           $dbwhere   = "l08_altera is true and";
      }
			if (isset($situacao) && trim($situacao) != ''){

             $dbwhere .= "l20_licsituacao = $situacao and ";   
         
			}
			$sWhereModalidade = "";
			
			if (isset($iModalidadeLicitacao) && !empty($iModalidadeLicitacao)) {
			  $sWhereModalidade = "and l20_codtipocom = {$iModalidadeLicitacao}";
			}

      $dbwhere_instit = "l20_instit = ".db_getsession("DB_instit"). "{$sWhereModalidade}";

      
      if (isset($lContratos) && $lContratos == 1 ) {
        
        $sWhereContratos .= " and ac24_sequencial is null ";
      }
      
      if(!isset($pesquisa_chave)){
        
        
        if(isset($campos)==false){
          
           if(file_exists("funcoes/db_func_liclicita.php")==true){
             include("funcoes/db_func_liclicita.php");
           }else{
           $campos = "liclicita.*, liclicitasituacao.l11_sequencial";
           }
        }
        
        $campos .= ", (select max(l11_sequencial) as l11_sequencial from liclicitasituacao where l11_liclicita = l20_codigo) as l11_sequencial ";
         
        if(isset($chave_l20_codigo) && (trim($chave_l20_codigo)!="") ){
	         $sql = $clliclicita->sql_queryContratos(null,"distinct " . $campos,"l20_codigo","l20_codigo = $chave_l20_codigo $and $dbwhere $dbwhere_instit $sWhereContratos",$situacao);
        }else if(isset($chave_l20_numero) && (trim($chave_l20_numero)!="") ){
	         $sql = $clliclicita->sql_queryContratos(null,"distinct " .$campos,"l20_codigo","l20_numero=$chave_l20_numero $and $dbwhere $dbwhere_instit $sWhereContratos",$situacao);
	      }else if(isset($chave_l03_descr) && (trim($chave_l03_descr)!="") ){
	         $sql = $clliclicita->sql_queryContratos(null,"distinct " .$campos,"l20_codigo","l03_descr like '$chave_l03_descr%' $and $dbwhere $dbwhere_instit $sWhereContratos",$situacao);
        }else if(isset($chave_l03_codigo) && (trim($chave_l03_codigo)!="") ){
	         $sql = $clliclicita->sql_queryContratos(null,"distinct " .$campos,"l20_codigo","l03_codigo=$chave_l03_codigo $and $dbwhere $dbwhere_instit $sWhereContratos",$situacao);        
        }else if(isset($chave_l20_edital) && (trim($chave_l20_edital)!="")){
         $sql = $clliclicita->sql_queryContratos(null,"distinct " .$campos,"l20_codigo","l20_edital=$chave_l20_edital $and $dbwhere $dbwhere_instit $sWhereContratos",$situacao);
        }else{
                 $sql = $clliclicita->sql_queryContratos("","distinct " .$campos,"l20_codigo","$dbwhere $dbwhere_instit $sWhereContratos",$situacao);
        }

        if (isset($param) && trim($param) != ""){
          
	         $dbwhere = " and (e55_sequen is null or (e55_sequen is not null and e54_anulad is not null))";
           if(isset($chave_l20_codigo) && (trim($chave_l20_codigo)!="") ){
	           $sql = $clliclicitem->sql_query_inf(null,$campos,"l20_codigo","l20_codigo = $chave_l20_codigo$dbwhere");
	         }else if(isset($chave_l20_numero) && (trim($chave_l20_numero)!="") ){
	           $sql = $clliclicitem->sql_query_inf(null,$campos,"l20_codigo","l20_numero=$chave_l20_numero$dbwhere");
	         }else if(isset($chave_l03_descr) && (trim($chave_l03_descr)!="") ){
	           $sql = $clliclicitem->sql_query_inf(null,$campos,"l20_codigo","l03_descr like '$chave_l03_descr%'$dbwhere");
           }else if(isset($chave_l03_codigo) && (trim($chave_l03_codigo)!="") ){
	           $sql = $clliclicitem->sql_query_inf(null,$campos,"l20_codigo","l03_codigo=$chave_l03_codigo$dbwhere");        
           } else {
             $sql = $clliclicitem->sql_query_inf("",$campos,"l20_codigo","1=1$dbwhere");
           }
	      }
	      
        db_lovrot($sql.' desc ',15,"()","",$funcao_js);
        
        
      } else {
        
        
        if ($pesquisa_chave != null && $pesquisa_chave != "") {
          
            if (isset($param) && trim($param) != ""){
             
              $result = $clliclicitem->sql_record($clliclicitem->sql_query_inf($pesquisa_chave));
              
              if ($clliclicitem->numrows!=0) {
                
                db_fieldsmemory($result,0);
                echo "<script>".$funcao_js."('$l20_codigo',false);</script>";
              }else{
  	            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
              }
	          } else {
                 $result = $clliclicita->sql_record($clliclicita->sql_queryContratos(null,"*",null,"l20_codigo = $pesquisa_chave $and $dbwhere $dbwhere_instit"));
                 
                 if($clliclicita->numrows != 0){
                   
                     db_fieldsmemory($result,0);
                     echo "<script>".$funcao_js."('$l20_codigo',false);</script>";
                     
                 } else {
                   
	                 echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
                 }
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