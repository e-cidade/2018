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
include("classes/db_efetividaderh_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clefetividaderh = new cl_efetividaderh;
$clefetividaderh->rotulo->label("ed98_i_ano");
$clefetividaderh->rotulo->label("ed98_c_tipo");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td height="63" align="center" valign="top">
   <table width="35%" border="0" align="center" cellspacing="0">
    <form name="form2" method="post" action="" >
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Ted98_c_tipo?>">
      <?=@$Led98_c_tipo?>
     </td>
     <td>
      <?
      $x = array(""=>"","P"=>"PROFESSORES","F"=>"FUNCIONÁRIOS");
      db_select('ed98_c_tipo',$x,true,@$db_opcao,"","")
      ?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Ted98_i_ano?>">
      <?=$Led98_i_ano?>
     </td>
     <td width="96%" align="left" nowrap>
      <?
      $arr_anos[""] = "";
      for($y=(date("Y")+1);$y>(date("Y")-30);$y--){
       $arr_anos[$y] = $y;
      }
      $x = $arr_anos;
      db_select('ed98_i_ano',$x,true,@$db_opcao,"");
      ?>
     </td>
    </tr>
    <tr>
     <td colspan="2" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_efetividaderh.hide();">
     </td>
    </tr>
    </form>
   </table>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?
   $escola = db_getsession("DB_coddepto");
   if(!isset($pesquisa_chave)){
    $campos = "ed98_i_codigo,
               case when ed98_c_tipo = 'F'
                then 'FUNCIONÁRIOS'
                else 'PROFESSORES'
               end as ed98_c_tipo,
               case when ed98_c_tipocomp = 'M'
                then 'MENSAL'
                else 'PERIÓDICA'
               end as ed98_c_tipocomp,
               case
                when ed98_i_mes = 1
                 then 'JANEIRO'
                when ed98_i_mes = 2
                 then 'FEVEREIRO'
                when ed98_i_mes = 3
                 then 'MARÇO'
                when ed98_i_mes = 4
                 then 'ABRIL'
                when ed98_i_mes = 5
                 then 'MAIO'
                when ed98_i_mes = 6
                 then 'JUNHO'
                when ed98_i_mes = 7
                 then 'JULHO'
                when ed98_i_mes = 8
                 then 'AGOSTO'
                when ed98_i_mes = 9
                 then 'SETEMBRO'
                when ed98_i_mes = 10
                 then 'OUTUBRO'
                when ed98_i_mes = 11
                 then 'NOVEMBRO'
                when ed98_i_mes = 12
                 then 'DEZEMBRO'
                else
                 null
               end as ed98_i_mes,
               ed98_i_ano,
               ed98_d_dataini,
               ed98_d_datafim
              ";
    $condicao = " ed98_i_escola = $escola";

    if(isset($ed98_i_ano) && (trim($ed98_i_ano)!="") ){
     $condicao .= " AND (ed98_i_ano = $ed98_i_ano OR extract(year from ed98_d_dataini) = '$ed98_i_ano' OR extract(year from ed98_d_datafim) = '$ed98_i_ano')";
    }
    if(isset($ed98_c_tipo) && (trim($ed98_c_tipo)!="") ){
     $condicao .= " AND ed98_c_tipo = '$ed98_c_tipo' ";
    }
    $sql = $clefetividaderh->sql_query("",$campos,"ed98_d_datafim desc,ed98_c_tipo"," $condicao ");
    $repassa = array();
    if(isset($ed98_i_ano)){
     $repassa = array("ed98_i_ano"=>$ed98_i_ano,"ed98_c_tipo"=>$ed98_c_tipo);
    }
    db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
   }else{
    if($pesquisa_chave!=null && $pesquisa_chave!=""){
     $result = $clefetividaderh->sql_record($clefetividaderh->sql_query($pesquisa_chave));
     if($clefetividaderh->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$ed98_i_codigo',false);</script>";
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
<script>
js_tabulacaoforms("form2","ed98_c_tipo",true,1,"ed98_c_tipo",true);
</script>