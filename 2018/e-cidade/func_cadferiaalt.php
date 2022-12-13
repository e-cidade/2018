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
include("libs/db_libpessoal.php");
include("dbforms/db_funcoes.php");
include("classes/db_cadferia_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcadferia = new cl_cadferia;
$clrotulo = new rotulocampo;
$clrotulo->label("r30_regist");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");

if(!isset($chave_r30_anousu) || (isset($chave_r30_anousu) && trim($chave_r30_anousu) == "")){
  $chave_r30_anousu = db_anofolha();
}
if(!isset($chave_r30_mesusu) || (isset($chave_r30_mesusu) && trim($chave_r30_mesusu) == "")){
  $chave_r30_mesusu = db_mesfolha();
}
if(isset($valor_testa_rescisao)){
  $chave_rh01_regist = $valor_testa_rescisao;
  $retorno = db_alerta_dados_func($testarescisao,$valor_testa_rescisao,db_anofolha(), db_mesfolha());
  if($retorno != ""){
    db_msgbox($retorno);
  }
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
      <table width="35%" border="0" align="center" cellspacing="0">
        <form name="form2" method="post" action="" >
        <tr> 
          <td width="4%" align="right" nowrap title="<?=$Tr30_regist?>">
            <?=$Lr30_regist?>
          </td>
          <td width="96%" align="left" nowrap> 
            <?
	    db_input("r30_regist",8,$Ir30_regist,true,"text",4,"","chave_r30_regist");
	    ?>
          </td>
        </tr>
        <tr> 
          <td width="4%" align="right" nowrap title="<?=$Tz01_numcgm?>">
            <?=$Lz01_numcgm?>
          </td>
          <td width="96%" align="left" nowrap> 
            <?
	    db_input("z01_numcgm",8,$Iz01_numcgm,true,"text",4,"","chave_z01_numcgm");
	    ?>
          </td>
        </tr>
        <tr> 
          <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
            <?=$Lz01_nome?>
          </td>
          <td width="96%" align="left" nowrap> 
            <?
	    db_input("z01_nome",40,$Iz01_nome,true,"text",4,"","chave_z01_nome");
	    ?>
          </td>
        </tr>
        <tr> 
          <td colspan="2" align="center" nowrap> 
            <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
            <input name="limpar" type="reset" id="limpar" value="Limpar" >
            <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_cadferia.hide();">
          </td>
        </tr>
        </form>
      </table>
    </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $dbwhere = " and (case when trim(r30_proc2) = '' or r30_proc2 is null then r30_proc1 >= '".$chave_r30_anousu."/".$chave_r30_mesusu."' else r30_proc2 >= '".$chave_r30_anousu."/".$chave_r30_mesusu."' end)";
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_cadferiaalt.php")==true){
             include("funcoes/db_func_cadferiaalt.php");
           }else{
             $campos = "cadferia.oid,cadferia.*";
           }
        }
	if(isset($chave_r30_regist) && trim($chave_r30_regist) != ""){
          $sql = $clcadferia->sql_query_pesquisa(null,$campos,"r30_regist","r30_anousu = $chave_r30_anousu and r30_mesusu = $chave_r30_mesusu and r30_regist = $chave_r30_regist $dbwhere");
	}else if(isset($chave_z01_numcgm) && trim($chave_z01_numcgm) != ""){
          $sql = $clcadferia->sql_query_pesquisa(null,$campos,"z01_numcgm","r30_anousu = $chave_r30_anousu and r30_mesusu = $chave_r30_mesusu and z01_numcgm = $chave_z01_numcgm $dbwhere");
	}else if(isset($chave_z01_nome) && trim($chave_z01_nome) != ""){
          $sql = $clcadferia->sql_query_pesquisa(null,$campos,"z01_nome","r30_anousu = $chave_r30_anousu and r30_mesusu = $chave_r30_mesusu and z01_nome like '$chave_z01_nome%' $dbwhere");
	}else{
          // $sql = $clcadferia->sql_query_pesquisa(null,$campos,"r30_regist","r30_anousu = $chave_r30_anousu and r30_mesusu = $chave_r30_mesusu");
            $sql = "";
	}
        db_lovrot($sql,15,"()","",(isset($testarescisao) && !isset($valor_testa_rescisao) ? "js_recebe_click|rh01_regist" : $funcao_js));
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clcadferia->sql_record($clcadferia->sql_query_pesquisa(null,"*","r30_perai desc","r30_anousu = $chave_r30_anousu and r30_mesusu = $chave_r30_mesusu and r30_regist = $pesquisa_chave $dbwhere"));
          if($clcadferia->numrows!=0){
            db_fieldsmemory($result,0);
	    if(isset($testarescisao)){
              $retorno = db_alerta_dados_func($testarescisao,$pesquisa_chave,db_anofolha(), db_mesfolha());
              if($retorno != ""){
                db_msgbox($retorno);
              }
	    }
	    echo "<script>".$funcao_js."('$z01_nome','$r30_perai','$r30_peraf','$r30_proc1','$r30_proc2','$r30_proc1d','$r30_proc2d','$r30_per1i','$r30_per2i','$r30_per1f','$r30_per2f','$r30_tip1',false);</script>";
          }else{
            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado','','','','','','','','','','','',true);</script>";
          }
        }else{
          echo "<script>".$funcao_js."('','','','','','','','','','','','',false);</script>";
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