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
include("classes/db_ruas_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clruas = new cl_ruas;
$clruas->rotulo->label("j14_codigo");
$clruas->rotulo->label("j14_nome");
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
        <table width="35%" border="0" align="center" cllspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tj14_codigo?>">
              <?=$Lj14_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("j14_codigo",7,$Ij14_codigo,true,"text",4,"","chave_j14_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tj14_nome?>">
              <?=$Lj14_nome?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("j14_nome",40,$Ij14_nome,true,"text",4,"","chave_j14_nome");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(isset($codrua) && !isset($pesquisar) && !isset($db_lovrot)){
	$chave_j14_codigo = $codrua;
      }
      if(isset($nomerua) && !isset($pesquisar) && !isset($db_lovrot)){
	$chave_j14_nome = $nomerua;
      }
      if(isset($rural)){
	$rural = " 1=1 ";
      }else{
	$rural = " j14_rural = 'f'";
      }
      if(!isset($pesquisa_chave)){
	      $query_dbconfig =  "select munic,uf  from db_config where codigo = 1";
          $result_dbconfig = pg_query($query_dbconfig);
          db_fieldsmemory($result_dbconfig,0);
              
	      $muni = strtoupper($munic);
	      $sigla     = strtoupper($uf);
              
	      $query_ceploca = "select cp05_cepinicial from ceplocalidades where cp05_localidades = '$muni' and cp05_sigla = '$sigla'";
	     
	      $res_ceploca   = pg_query($query_ceploca);
	      db_fieldsmemory($res_ceploca,0);
	      
	       $campos = "distinct case when cp06_cep is null and j29_cep is null then '$cp05_cepinicial'  
                                 else case when cp06_cep is null and j29_cep is not null then j29_cep
                                   else cp06_cep
                                      end
                            end
                            as cep,
                            j14_nome,
			    case when ceplogradouros.cp06_lado = 'I' then 'Lado Impar'
                                 else case when ceplogradouros.cp06_lado = 'P' then 'Lado Par'
                                      else case when ceplogradouros.cp06_lado = 'A' then 'Até o fim'
                                           else 'Toda Rua' 
                                      end
                                 end
                            end as cp06_lado,
			    j14_codigo,
                            cp06_numinicial,
			    cp06_numfinal
			    ";
        if(isset($chave_j14_codigo) && (trim($chave_j14_codigo)!="") ){
	         $sql = $clruas->sql_query_lograd("",$campos,"j14_codigo","$rural and j14_codigo=$chave_j14_codigo" );
        }else if(isset($chave_j14_nome) && (trim($chave_j14_nome)!="") ){
                 $sql = $clruas->sql_query_lograd("",$campos,"j14_nome"," j14_nome like '$chave_j14_nome%' and  $rural");
        }else{
           $sql = $clruas->sql_query_lograd("",$campos,"j14_codigo"," $rural ");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clruas->sql_record($clruas->sql_query_lograd($pesquisa_chave));
          if($clruas->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$j14_nome',false);</script>";
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