<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_avalcompoeres_classe.php");
include("classes/db_rescompoeres_classe.php");
include("classes/db_procresultado_classe.php");
include("classes/db_conceito_classe.php");
include("classes/db_formaavaliacao_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clavalcompoeres = new cl_avalcompoeres;
$clrescompoeres = new cl_rescompoeres;
$clprocresultado = new cl_procresultado;
$clconceito = new cl_conceito;
$clformaavaliacao = new cl_formaavaliacao;
$db_opcao = 1;
$db_opcao1 = 1;
$db_botao = true;
if(isset($incluir)){
 db_inicio_transacao();
 if($forma=="NIVEL"){
  $ed44_i_peso = 1;
 }
 if(trim($ed14_c_descr)=="AVALIAÇÃO PERIÓDICA"){
  $clavalcompoeres->ed44_i_peso = $ed44_i_peso;
  $clavalcompoeres->incluir($ed44_i_codigo);
 }elseif(trim($ed14_c_descr)=="RESULTADO"){
  $clrescompoeres->ed68_i_procresultado = $ed44_i_procresultado;
  $clrescompoeres->ed68_i_procresultcomp = $ed44_i_procavaliacao;
  $clrescompoeres->ed68_i_peso = $ed44_i_peso;
  $clrescompoeres->ed68_c_minimoaprov = $ed44_c_minimoaprov;
  $clrescompoeres->incluir($ed44_i_codigo);
 }else{
  $clavalcompoeres->incluir($ed44_i_codigo);
 }
 db_fim_transacao();
 $db_botao = false;
}
if(isset($alterar)){
 $db_opcao = 2;
 $db_opcao1 = 3;
 db_inicio_transacao();
 if(trim($ed14_c_descr)=="AVALIAÇÃO PERIÓDICA"){
  $clavalcompoeres->alterar($ed44_i_codigo);
 }elseif(trim($ed14_c_descr)=="RESULTADO"){
  $clrescompoeres->ed68_i_procresultado = $ed44_i_procresultado;
  $clrescompoeres->ed68_i_procresultcomp = $ed44_i_procavaliacao;
  $clrescompoeres->ed68_i_peso = @$ed44_i_peso;
  $clrescompoeres->ed68_c_minimoaprov = $ed44_c_minimoaprov;
  $clrescompoeres->ed68_i_codigo = $ed44_i_codigo;
  $clrescompoeres->alterar($ed44_i_codigo);
 }else{
  $clavalcompoeres->alterar($ed44_i_codigo);
 }
 db_fim_transacao();
}
if(isset($excluir)){
 $db_opcao = 3;
 $db_opcao1 = 3;
 db_inicio_transacao();
 if(trim($ed14_c_descr)=="AVALIAÇÃO PERIÓDICA"){
  $clavalcompoeres->excluir($ed44_i_codigo);
 }elseif(trim($ed14_c_descr)=="RESULTADO"){
  $clrescompoeres->excluir($ed44_i_codigo);
 }
 db_fim_transacao();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Elementos para o cálculo de aproveitamento do Resultado <?=@$ed42_c_descr?></b></legend>
    <?include("forms/db_frmavalcompoeres.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
 if($clavalcompoeres->erro_status=="0"){
  $clavalcompoeres->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clavalcompoeres->erro_campo!=""){
   echo "<script> document.form1.".$clavalcompoeres->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clavalcompoeres->erro_campo.".focus();</script>";
  };
 }else{
  if($fobtencao!=""){
   $fobtencao = trim($fobtencao);
   $sql = "UPDATE procresultado SET
            ed43_c_obtencao = '$fobtencao'
           WHERE ed43_i_codigo = $ed44_i_procresultado
          ";
   $query = pg_query($sql);
  }
  if(trim($ed14_c_descr)=="AVALIAÇÃO PERIÓDICA"){
   $clavalcompoeres->erro(true,true);
  }elseif(trim($ed14_c_descr)=="RESULTADO"){
   $clrescompoeres->erro(true,true);
  };
 }
};
if(isset($alterar)){
 if($clavalcompoeres->erro_status=="0"){
  $clavalcompoeres->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clavalcompoeres->erro_campo!=""){
   echo "<script> document.form1.".$clavalcompoeres->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clavalcompoeres->erro_campo.".focus();</script>";
  };
 }else{
  if($fobtencao!=""){
   $fobtencao = trim($fobtencao);
   $sql = "UPDATE procresultado SET
            ed43_c_obtencao = '$fobtencao'
           WHERE ed43_i_codigo = $ed44_i_procresultado
          ";
   $query = pg_query($sql);
  }
  if(trim($ed14_c_descr)=="AVALIAÇÃO PERIÓDICA"){
   $clavalcompoeres->erro(true,true);
  }elseif(trim($ed14_c_descr)=="RESULTADO"){
   $clrescompoeres->erro(true,true);
  }
 };
};
if(isset($excluir)){
 if($clavalcompoeres->erro_status=="0"){
   $clavalcompoeres->erro(true,false);
 }else{
  if(Elementos($ed44_i_procresultado)==0){
   if(trim($ed14_c_descr)=="AVALIAÇÃO PERIÓDICA"){
    $clavalcompoeres->erro(true,false);
   }elseif(trim($ed14_c_descr)=="RESULTADO"){
    $clrescompoeres->erro(true,false);
   }
   ?>
    <script>
     parent.iframe_c1.location='edu1_procresultado002.php?chavepesquisa=<?=$ed44_i_procresultado?>&forma=<?=$forma?>';
     parent.mo_camada('c1');
    </script>
   <?
  }else{
   if(trim($ed14_c_descr)=="AVALIAÇÃO PERIÓDICA"){
    $clavalcompoeres->erro(true,true);
   }elseif(trim($ed14_c_descr)=="RESULTADO"){
    $clrescompoeres->erro(true,true);
   }
  }
 };
};
if(isset($cancelar)){
 echo "<script>location.href='".$clavalcompoeres->pagina_retorno."'</script>";
}
function Elementos($ed44_i_procresultado){
 $sql = "SELECT ed44_i_codigo,
                ed44_i_procavaliacao,
                ed09_c_descr,
                case
                 when ed44_i_codigo>0 then 'AVALIAÇÃO PERIÓDICA' end as ed14_c_descr,
                ed44_i_peso,
                ed44_c_minimoaprov,
                ed44_c_obrigatorio,
                ed41_i_sequencia
         FROM avalcompoeres
          inner join procavaliacao on procavaliacao.ed41_i_codigo = avalcompoeres.ed44_i_procavaliacao
          inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao
         WHERE ed44_i_procresultado = $ed44_i_procresultado
         UNION
         SELECT ed68_i_codigo,
                ed68_i_procresultcomp,
                ed42_c_descr,
                case
                 when ed68_i_codigo>0 then 'RESULTADO' end as ed14_c_descr,
                ed68_i_peso,
                ed68_c_minimoaprov,
                ed43_c_boletim,
                ed43_i_sequencia
         FROM rescompoeres
          inner join procresultado on procresultado.ed43_i_codigo = rescompoeres.ed68_i_procresultcomp
          inner join resultado on resultado.ed42_i_codigo = procresultado.ed43_i_resultado
          WHERE ed68_i_procresultado = $ed44_i_procresultado
         ORDER BY ed41_i_sequencia
        ";
 $query = pg_query($sql);
 $linhas = pg_num_rows($query);
 if($linhas==0){
  $sql = "UPDATE procresultado SET
           ed43_c_obtencao = 'AT'
          WHERE ed43_i_codigo = $ed44_i_procresultado
         ";
  $query = pg_query($sql);
 }
 return $linhas;
}
?>
<script>
document.form1.fobtencao.value = parent.iframe_c1.document.form1.ed43_c_obtencao.value;
if(document.form1.fobtencao.value=="MP"){
 document.form1.ed44_i_peso.style.visibility = "visible";
 document.getElementById("peso").style.visibility = "visible";
}
</script>