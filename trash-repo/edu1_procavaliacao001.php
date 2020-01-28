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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_procavaliacao_classe.php");
include("classes/db_formaavaliacao_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clprocavaliacao = new cl_procavaliacao;
$clformaavaliacao = new cl_formaavaliacao;
$db_opcao = 1;
$db_opcao1 = 1;
$db_botao = true;
if(isset($incluir)){
 db_inicio_transacao();
 $sql = "SELECT ed41_i_sequencia
          FROM procavaliacao
          WHERE ed41_i_procedimento = $ed41_i_procedimento
         UNION
         SELECT ed43_i_sequencia
          FROM procresultado
          WHERE ed43_i_procedimento = $ed41_i_procedimento
         ORDER BY ed41_i_sequencia
        ";
 $result = db_query($sql);
 $linhas = pg_num_rows($result);
 if($linhas==0){
  $max = 0;
 }else{
  $max = pg_result($result,$linhas-1,"ed41_i_sequencia");
 }
 if(@$vinculadotipo[0]=="A"){
  $procavalvinc = @$vinculado[0];
  $procresultvinc = 0;
 }else{
  $procavalvinc = 0;
  $procresultvinc = @$vinculado[0];
 }
 $clprocavaliacao->ed41_i_procavalvinc = $procavalvinc;
 $clprocavaliacao->ed41_i_procresultvinc = $procresultvinc;
 $clprocavaliacao->ed41_i_sequencia = ($max+1);
 $clprocavaliacao->incluir($ed41_i_codigo);
 db_fim_transacao();

 $db_botao = false;
}
if(isset($alterar)){
 $db_opcao = 2;
 $db_opcao1 = 3;
 db_inicio_transacao();
 if($vinculadotipo[0]=="A"){
  $procavalvinc = $vinculado[0]==''?'0':$vinculado[0];
  $procresultvinc = '0';
 }else{
  $procavalvinc = '0';
  $procresultvinc = $vinculado[0]==''?'0':$vinculado[0];
 }
 $clprocavaliacao->ed41_i_procavalvinc = $procavalvinc;
 $clprocavaliacao->ed41_i_procresultvinc = $procresultvinc;
 $clprocavaliacao->alterar($ed41_i_codigo);
 db_fim_transacao();
}
if(isset($excluir)){
 $db_opcao = 3;
 $db_opcao1 = 3;
 db_inicio_transacao();
 $clprocavaliacao->excluir($ed41_i_codigo);
 db_fim_transacao();
}
if(isset($chavepesquisa) && (!isset($alterar) && !isset($excluir))){
 $db_opcao1 = 3;
 if($tarefa=="alterar"){
  $db_opcao = 2;
 }else{
  $db_opcao = 3;
 }
 $result = $clprocavaliacao->sql_record($clprocavaliacao->sql_query($chavepesquisa));
 db_fieldsmemory($result,0);
 $db_botao = true;
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
  <td valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%">
    <legend>
     <b><?=($db_opcao==1?"Inclusão":($db_opcao==2?"Alteração":"Exclusão"))?> da Avaliação Periódica <?=@$ed09_c_descr?></b>
    </legend>
    <?include("forms/db_frmprocavaliacao.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed41_i_formaavaliacao",true,1,"ed41_i_formaavaliacao",true);
</script>
<?
if(isset($chavepesquisa)){
 ?><script>iframe_aval.location.href = "edu1_procedimento004.php?codigo=<?=$ed41_i_formaavaliacao?>";</script><?
}
if(isset($incluir)){
 if($clprocavaliacao->erro_status=="0"){
  $clprocavaliacao->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clprocavaliacao->erro_campo!=""){
   echo "<script> document.form1.".$clprocavaliacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clprocavaliacao->erro_campo.".focus();</script>";
  };
 }else{
  $clprocavaliacao->erro(true,false);
  ?>
   <script>parent.location.href='edu1_avaliacoes.php?procedimento=<?=$ed41_i_procedimento?>&ed40_c_descr=<?=$ed40_c_descr?>&forma=<?=$forma?>';</script>
  <?
 };
};
if(isset($alterar)){
 if($clprocavaliacao->erro_status=="0"){
  $clprocavaliacao->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clprocavaliacao->erro_campo!=""){
   echo "<script> document.form1.".$clprocavaliacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clprocavaliacao->erro_campo.".focus();</script>";
  };
 }else{
  $clprocavaliacao->erro(true,false);
  ?>
   <script>parent.location.href='edu1_avaliacoes.php?procedimento=<?=$ed41_i_procedimento?>&ed40_c_descr=<?=$ed40_c_descr?>&forma=<?=$forma?>';</script>
  <?
 };
};
if(isset($excluir)){
 if($clprocavaliacao->erro_status=="0"){
  $clprocavaliacao->erro(true,false);
 }else{
  $clprocavaliacao->erro(true,false);
  ?>
   <script>parent.location.href='edu1_avaliacoes.php?procedimento=<?=$ed41_i_procedimento?>&ed40_c_descr=<?=$ed40_c_descr?>&forma=<?=$forma?>';</script>
  <?
 };
};
if(isset($cancelar)){
 echo "<script>location.href='".$clprocavaliacao->pagina_retorno."'</script>";
}
function AvalResultList($nome,$procedimento,$disabled,$sequencia,$avalvinc,$resultvinc){
 if($sequencia!=""){
  $where1 = "AND ed41_i_sequencia < $sequencia";
  $where2 = "AND ed43_i_sequencia < $sequencia";
 }else{
  $where1 = "";
  $where2 = "";
 }
 $sql = "SELECT ed41_i_codigo as codigo,
                ed09_c_descr as avaliacao,
                case
                 when ed41_i_codigo>0 then 'A' end as tipo,
                ed41_i_sequencia
         FROM procavaliacao
          inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao
         WHERE ed41_i_procedimento = $procedimento
         $where1
         UNION
         SELECT ed43_i_codigo as codigo,
                ed42_c_descr as resultado,
                case
                 when ed43_i_codigo>0 then 'R' end as tipo,
                ed43_i_sequencia
         FROM procresultado
          inner join resultado on resultado.ed42_i_codigo = procresultado.ed43_i_resultado
         WHERE ed43_i_procedimento = $procedimento
         $where2
         ORDER BY ed41_i_sequencia desc
        ";
 $query = db_query($sql);
 $query1 = db_query($sql);
 $linhas = pg_num_rows($query);
 ?>
    <select name="<?=$nome?>tipo[]" id="<?=$nome?>tipo" size="1" style="width:40px;" listbox onclick="js_selectum('<?=$nome?>')" <?=$disabled!=""?"$disabled":""?>>
     <option value=''></option>
     <?
     for($i=0;$i<$linhas;$i++){
     $dados = pg_fetch_array($query);
      if($avalvinc==0 && $resultvinc!=0){
       $tipoaval = "R";
      }elseif($resultvinc==0 && $avalvinc!=0){
       $tipoaval = "A";
      }elseif($avalvinc==0 && $resultvinc==0){
       $tipoaval = "";
      }
      $selected = $tipoaval==trim($dados["tipo"])?" selected ":"";
      echo "<option value=\"".trim($dados["tipo"])."\" $selected>".trim($dados["tipo"])."</option>\n";
     }
     ?>
    </select>
    <select name="<?=$nome?>[]" id="<?=$nome?>" size="1" style="width:150px"  onclick="js_selectdois('<?=$nome?>')" <?=$disabled!=""?"$disabled":""?>>
     <option value=''></option>
     <?
     for($i=0;$i<$linhas;$i++){
     $dados1 = pg_fetch_array($query1);
      if($avalvinc==0 && $resultvinc!=0){
       $tipoaval1 = "R";
      }elseif($resultvinc==0 && $avalvinc!=0){
       $tipoaval1 = "A";
      }elseif($avalvinc==0 && $resultvinc==0){
       $tipoaval1 = "";
      }
      $tipoaval = $avalvinc!=0?$avalvinc:$resultvinc;
      $selected1 = trim($tipoaval)==trim($dados1["codigo"])&&$tipoaval1==trim($dados1["tipo"])?" selected ":"";
      echo "<option value=\"".$dados1["codigo"]."\" $selected1>".trim($dados1["avaliacao"])."</option>\n";
     }
     ?>
    </select>
 <?
}
?>