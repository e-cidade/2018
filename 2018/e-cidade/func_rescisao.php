<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
include("classes/db_rescisao_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clrescisao = new cl_rescisao;
$clrescisao->rotulo->label("r59_anousu");
$clrescisao->rotulo->label("r59_mesusu");
$clrescisao->rotulo->label("r59_regime");
$clrescisao->rotulo->label("r59_causa");
$clrescisao->rotulo->label("r59_caub");
$clrescisao->rotulo->label("r59_menos1");
$clrescisao->rotulo->label("r59_descr");

if(!isset($chave_r59_anousu)){
  $chave_r59_anousu = db_anofolha();
}
if(!isset($chave_r59_mesusu)){
  $chave_r59_mesusu = db_mesfolha();
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
            <td align="right" nowrap title="Digite o Ano / Mes de competência" >
              <strong>Ano / Mês :&nbsp;&nbsp;</strong>
            </td>
            <td>
              <?
              db_input('r59_anousu',4,$Ir59_anousu,true,'text',2,'',"chave_r59_anousu")
              ?>
              &nbsp;/&nbsp;
              <?
              db_input('r59_mesusu',2,$Ir59_mesusu,true,'text',2,'',"chave_r59_mesusu")
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tr59_regime?>">
              <?=$Lr59_regime?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("r59_regime",4,$Ir59_regime,true,"text",4,"","chave_r59_regime");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tr59_causa?>">
              <?=$Lr59_causa?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("r59_causa",4,$Ir59_causa,true,"text",4,"","chave_r59_causa");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tr59_caub?>">
              <?=$Lr59_caub?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("r59_caub",4,$Ir59_caub,true,"text",4,"","chave_r59_caub");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tr59_descr?>">
              <?=$Lr59_descr?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("r59_descr",40,$Ir59_descr,true,"text",4,"","chave_r59_descr");
		       ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rescisao.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      if(!isset($chave_r59_regime) || (isset($chave_r59_regime) && trim($chave_r59_regime)=="")){
        $chave_r59_regime = null;
      }

		  $instit = db_getsession("DB_instit");

      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_rescisao.php")==true){
             include("funcoes/db_func_rescisao.php");
           }else{
             $campos = "rescisao.*";
           }
        }
        if(isset($chave_r59_regime) && (trim($chave_r59_regime)!="")){
	         $sql = $clrescisao->sql_query($chave_r59_anousu,$chave_r59_mesusu,$chave_r59_regime,null,null,null,$instit,$campos,"r59_regime");
        }else if(isset($chave_r59_causa) && (trim($chave_r59_causa)!="")){
	         $sql = $clrescisao->sql_query($chave_r59_anousu,$chave_r59_mesusu,$chave_r59_regime,$chave_r59_causa,null,null,$instit,$campos,"r59_causa");
        }else if(isset($chave_r59_caub) && (trim($chave_r59_caub)!="") ){
	         $sql = $clrescisao->sql_query($chave_r59_anousu,$chave_r59_mesusu,null,null,$chave_r59_caub,null,$instit,$campos,"r59_caub");
        }else if(isset($chave_r59_descr) && (trim($chave_r59_descr)!="") ){
	         $sql = $clrescisao->sql_query(null,null,null,null,null,null,$instit,$campos,"r59_descr"," r59_descr like '$chave_r59_descr%' and r59_anousu=$chave_r59_anousu and r59_mesusu=$chave_r59_mesusu ");
        }else{
           $sql = $clrescisao->sql_query($chave_r59_anousu,$chave_r59_mesusu,null,null,null,null,$instit,$campos,"r59_regime,r59_causa");
        }

        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clrescisao->sql_record($clrescisao->sql_query($chave_r59_anousu,$chave_r59_mesusu,$chave_r59_regime,$pesquisa_chave,null,null,$instit));
          if($clrescisao->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$r59_descr',false);</script>";
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