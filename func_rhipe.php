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
include("libs/db_libpessoal.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhipe_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clrhipe = new cl_rhipe;
$clrotulo = new rotulocampo;
$clrhipe->rotulo->label("rh14_matipe");
$clrhipe->rotulo->label("rh14_contrato");
$clrotulo->label("rh62_regist");
$clrotulo->label("rh63_numcgm");
$clrotulo->label("z01_nome");
if(isset($valor_testa_rescisao) && trim($valor_testa_rescisao) != ""){
  $chave_rh62_regist = $valor_testa_rescisao;
  $retorno = db_alerta_dados_func($testarescisao,$valor_testa_rescisao,db_anofolha(), db_mesfolha());
  if($retorno != ""){
    db_msgbox($retorno);
  }
}
?>
<html>
<head>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
    function js_recebe_click(value){
      obj = document.createElement('input');
      obj.setAttribute('type','hidden'); 
      obj.setAttribute('name','funcao_js');
      obj.setAttribute('id','funcao_js');
      obj.setAttribute('value','<?=$funcao_js?>');
      document.form2.appendChild(obj);

      obj = document.createElement('input');
      obj.setAttribute('type','hidden'); 
      obj.setAttribute('name','valor_testa_rescisao');
      obj.setAttribute('id','valor_testa_rescisao');
      obj.setAttribute('value',value);
      document.form2.appendChild(obj);

      document.form2.submit();
    }
  </script>
  <?
}
?>
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
            <td width="4%" align="right" nowrap title="<?=$Trh62_regist?>">
              <?=$Lrh62_regist?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh62_regist",6,$Irh62_regist,true,"text",4,"","chave_rh62_regist");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh63_numcgm?>">
              <?=$Lrh63_numcgm?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh63_numcgm",6,$Irh63_numcgm,true,"text",4,"","chave_rh63_numcgm");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
              <B>Nome do Funcionário:</B>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("z01_nome",40,$Iz01_nome,true,"text",4,"","chave_z01_nome");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh14_matipe?>">
              <?=$Lrh14_matipe?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh14_matipe",13,$Irh14_matipe,true,"text",4,"","chave_rh14_matipe");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh14_contrato?>">
              <?=$Lrh14_contrato?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh14_contrato",13,$Irh14_contrato,true,"text",4,"","chave_rh14_contrato");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhipe.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(isset($depend)){
        if($depend == "func"){
	  $entrar = true;
        }else{
	  $entrar = false;
        }
      }
      $sRHIsntituicao =  " rh01_instit = ".db_getsession("DB_instit");
			$db_where       = " rh14_instit = ".db_getsession("DB_instit");
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_rhipe.php")==true){
             include("funcoes/db_func_rhipe.php");
           }else{
             $campos = "rhipe.*,z01_nome";
           }
        }
        if(isset($chave_rh62_regist) && (trim($chave_rh62_regist)!="")){
          $sql = $clrhipe->sql_query_cons(null,$campos,"rh62_regist","rh62_regist = ".$chave_rh62_regist." and $db_where");
        }else if(isset($chave_rh14_matipe) && (trim($chave_rh14_matipe)!="")){
          $sql = $clrhipe->sql_query_cons(null,$campos,"rh14_matipe"," rh14_matipe = '$chave_rh14_matipe'  and $db_where");
        }elseif(isset($chave_rh14_contrato) && (trim($chave_rh14_contrato)!="")){
          $sql = $clrhipe->sql_query_cons(null,$campos,"rh14_contrato","rh14_contrato = $chave_rh14_contrato  and $db_where" );
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="")){
          $dbwhere = " a.z01_nome like '$chave_z01_nome%' or b.z01_nome like '$chave_z01_nome%'  and $db_where";
          if(isset($entrar)){
            if($entrar == true){
              $dbwhere = "a.z01_nome like '$chave_z01_nome%' and {$sRHIsntituicao} and $db_where";
            }else{
              $dbwhere = "b.z01_nome like '$chave_z01_nome%' and $db_where";
            }
          }
          $dbwhere .= " and $db_where";
          $sql = $clrhipe->sql_query_cons(null,$campos,"rh14_sequencia",$dbwhere);
          
        }else if(isset($chave_rh63_numcgm) && (trim($chave_rh63_numcgm)!="")){
          $sql = $clrhipe->sql_query_cons(null,$campos,"rh63_numcgm","rh63_numcgm = ".$chave_rh63_numcgm." and $db_where");
        }else{
          $sql = $clrhipe->sql_query_cons("",$campos,"rh14_sequencia",$db_where);
        }
        db_lovrot($sql,15,"()","",(isset($testarescisao) && !isset($valor_testa_rescisao) ? "js_recebe_click|rh62_regist" : $funcao_js));
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clrhipe->sql_record($clrhipe->sql_query_cons(null,"*",null,"rh14_regist = $pesquisa_chave and $db_where"));
          if($clrhipe->numrows!=0){
            db_fieldsmemory($result,0);
            if(isset($testarescisao)){
              $retorno = db_alerta_dados_func($testarescisao,$rh62_regist,db_anofolha(), db_mesfolha());
              if($retorno != ""){
                db_msgbox($retorno);
              }
            }
            echo "<script>".$funcao_js."('$rh14_matipe',false);</script>";
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