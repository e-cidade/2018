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
include("classes/db_rhelementoemp_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clrhelementoemp = new cl_rhelementoemp;
$clrhelementoemp->rotulo->label("rh38_seq");
$clrhelementoemp->rotulo->label("rh38_codele");

$clrotulo = new rotulocampo;
$clrotulo->label("o56_descr");
$clrotulo->label("rh36_pcmater");
$clrotulo->label("pc01_descrmater");

if (isset($chave_rh38_seq) && !DBNumber::isInteger($chave_rh38_seq)) {
  $chave_rh38_seq = '';
}

if (isset($chave_rh38_codele) && !DBNumber::isInteger($chave_rh38_codele)) {
  $chave_rh38_codele = '';
}

if (isset($chave_rh36_pcmater) && !DBNumber::isInteger($chave_rh36_pcmater)) {
  $chave_rh36_pcmater = '';
}

$chave_rh38_codele     = isset($chave_rh38_codele) ? stripslashes($chave_rh38_codele) : '';
$chave_o56_descr       = isset($chave_o56_descr) ? stripslashes($chave_o56_descr) : '';
$chave_pc01_descrmater = isset($chave_pc01_descrmater) ? stripslashes($chave_pc01_descrmater) : '';

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
            <td width="4%" align="right" nowrap title="<?=$Trh38_seq?>">
              <?=$Lrh38_seq?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh38_seq",6,$Irh38_seq,true,"text",4,"","chave_rh38_seq");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh38_codele?>">
              <?=$Lrh38_codele?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh38_codele",6,$Irh38_codele,true,"text",4,"","chave_rh38_codele");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh36_pcmater?>">
              <?=$Lrh36_pcmater?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh36_pcmater",10,$Irh36_pcmater,true,"text",4,"","chave_rh36_pcmater");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To56_descr?>">
              <?=$Lo56_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o56_descr",50,$Io56_descr,true,"text",4,"","chave_o56_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tpc01_descrmater?>">
              <?=$Lpc01_descrmater?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("pc01_descrmater",50,$Ipc01_descrmater,true,"text",4,"","chave_pc01_descrmater");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhelementoemp.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?php 

      $chave_rh38_codele     = addslashes($chave_rh38_codele);
      $chave_o56_descr       = addslashes($chave_o56_descr);
      $chave_pc01_descrmater = addslashes($chave_pc01_descrmater);

      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_rhelementoemp.php")==true){
             include("funcoes/db_func_rhelementoemp.php");
           }else{
           $campos = "rhelementoemp.*";
           }
        }

        if (isset($chave_rh38_seq) && (trim($chave_rh38_seq)!="") ){
	         $sql = $clrhelementoemp->sql_query_pcmater($chave_rh38_seq,$campos,"rh38_seq", " rh38_seq = $chave_rh38_seq and rh38_anousu = ".db_getsession("DB_anousu"));
        }else if (isset($chave_rh38_codele) && (trim($chave_rh38_codele)!="") ){
	         $sql = $clrhelementoemp->sql_query_pcmater("",$campos,"rh38_codele"," rh38_codele like '$chave_rh38_codele%' and rh38_anousu = ".db_getsession("DB_anousu"));
        }else if (isset($chave_o56_descr) && trim(@$chave_o56_descr) != "") {
           $sql = $clrhelementoemp->sql_query_pcmater("",$campos,"o56_descr","o56_descr like '$chave_o56_descr%' and rh38_anousu = ".db_getsession("DB_anousu"));
        }else if (isset($chave_rh36_pcmater) && trim(@$chave_rh36_pcmater) != "") {
           $sql = $clrhelementoemp->sql_query_pcmater("",$campos,"pc01_codmater","rh36_pcmater = $chave_rh36_pcmater and rh38_anousu = ".db_getsession("DB_anousu"));
        }else if (isset($chave_pc01_descrmater) && trim(@$chave_pc01_descrmater) != ""){
           $sql = $clrhelementoemp->sql_query_pcmater("",$campos,"pc01_descrmater","pc01_descrmater like '$chave_pc01_descrmater%' and rh38_anousu = ".db_getsession("DB_anousu"));
        }else {
           $sql = $clrhelementoemp->sql_query_pcmater("",$campos,"rh38_seq desc","rh38_anousu = ".db_getsession("DB_anousu"));
        }

        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if ($pesquisa_chave != null && $pesquisa_chave != "") {
          $result = $clrhelementoemp->sql_record($clrhelementoemp->sql_query_pcmater("","o56_descr, rh38_seq","rh38_codele"," rh38_codele like '$pesquisa_chave%' and rh38_anousu = ".db_getsession("DB_anousu")));

          if($clrhelementoemp->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$o56_descr',false, $rh38_seq);</script>";
          }else{
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