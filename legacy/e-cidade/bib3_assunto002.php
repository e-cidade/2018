<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

include("classes/db_assunto_classe.php");
$classunto = new cl_assunto;
$depto = db_getsession("DB_coddepto");
$sql = "SELECT bi17_codigo,bi17_nome FROM biblioteca WHERE bi17_coddepto = $depto";
$result = db_query($sql);
$linhas = pg_num_rows($result);
if($linhas!=0){
 db_fieldsmemory($result,0);
}
?>

<?
if(isset($_POST["pesquisar"])){
 db_postmemory($HTTP_POST_VARS);
 if($z01_nome==""){
  db_msgbox("Digite algum termo para consulta!");
  ?><script>history.back();</script><?
  exit;
 }
 $where = " ( bi15_assunto like '%$z01_nome%' OR bi06_titulo like '%$z01_nome%' ";
 $exp_termos = explode(" ",trim($z01_nome));
 if(count($exp_termos)>1){
  for($i=0;$i<count($exp_termos);$i++){
   if(strlen($exp_termos[$i])>2){
    $where .= " OR bi15_assunto like '%$exp_termos[$i]%' ";
    $where .= " OR bi06_titulo like '%$exp_termos[$i]%' ";
   }
  }
 }
 $where .= ") AND bi06_biblioteca = $bi17_codigo";
 if($bi05_nome != 0){
  $where .= " and bi06_tipoitem = $bi05_nome";
  $result1 = $cltipoitem->sql_record($cltipoitem->sql_query("","*",""," bi05_codigo = $bi05_nome"));
 }
 if($bi03_classificacao != 0){
  $where .= " and bi06_classiliteraria = $bi03_classificacao";
  $result2 = $clclassiliteraria->sql_record($clclassiliteraria->sql_query("","*",""," bi03_codigo = $bi03_classificacao"));
 }
 $where .= " and bi23_situacao = 'S' order by bi06_titulo,bi23_situacao desc";
 $campos = "bi23_codigo,bi23_situacao,bi23_codbarras,bi06_seq,bi06_titulo,bi06_edicao,bi06_volume,bi05_nome,bi15_assunto,bi03_classificacao";
 $sql = $classunto->sql_query_exemplar("",$campos,"",$where);
 $result = $classunto->sql_record($sql);
//db_criatabela($result);
 //exit;
 ?>
  <table width="100%" border="1" cellspacing="0" cellpading="3" >
  <form method="POST" name="form2">
  <tr>
   <td colspan="7">
    <b>
     Consulta de Acervo por Assunto<br>
     - Termos: <?=$z01_nome?><br>
     - Tipo de Acervo: <?=$bi05_nome!=0?(pg_result($result1,0,"bi05_nome")):"TODOS"?><br>
     - Classe Literária: <?=$bi03_classificacao!=0?(pg_result($result2,0,"bi03_classificacao")):"TODAS"?>
    </b>
   </td>
  </tr>
  <tr bgcolor="#999999">
   <td align="center"><b>Código Exemplar</b></td>
   <td><b>Título</b></td>
  </tr>
  <?
   $cor1 = "#DEB887";
   $cor2 = "#FFE0C1";
   $cor=$cor1;
   if($classunto->numrows!=0){
    for($i=0;$i<$classunto->numrows;$i++){
     db_fieldsmemory($result,$i);
     if($cor==$cor1){
      $cor=$cor2;
     }else{
      $cor=$cor1;
     }
     $sql1 = "SELECT bi23_codigo,bi23_situacao
              FROM exemplar
               inner join acervo on acervo.bi06_seq = exemplar.bi23_acervo
              WHERE not exists(select * from emprestimoacervo
                               where emprestimoacervo.bi19_exemplar = exemplar.bi23_codigo
                               and not exists(select *
                                              from devolucaoacervo
                                              where devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo
                                             )
                               )
              AND bi23_situacao = 'S'
              AND bi06_biblioteca = $bi17_codigo
              AND bi23_codigo = $bi23_codigo
             ";
     $result1 = db_query($sql1);
     $linhas = pg_num_rows($result1);
     ?>
     <tr bgcolor="<?=$cor?>">
      <td align="center"><?=$bi06_seq?></td>
      <td><a href="javascript:js_abreacervo(<?=$bi06_seq?>);"><?=TermoNegrito($z01_nome,$bi06_titulo,"black","");?></td>
      </td>
     </tr>
     <tr bgcolor="<?=$cor?>">
      <td colspan="7">
       Assunto:
       <i>
       <?=TermoNegrito($z01_nome,$bi15_assunto,"black","");?>
       </i>
      </td>
     </tr>
    </form>
    <?}?>
    <table>
   <?}else{?>
    <tr>
      <td colspan="7" align="center" bgcolor="#f3f3f3">Nenhum registro encontrado para estes termos</td>
    </tr>
    </table>
   <?}
  }else{
   echo "<div align='center'><br>Digite os termos, escolha o filtro e clique em pesquisar</div>";
  }
//$termos = termos digitados para a consulta
//$texto = texto a ser comparado com os termos digitados
//$cor = cor que aparecerá na tela os termos digitados
//$fonte = fonte que aparecerá na tela os termos digitados
function TermoNegrito($termos,$texto,$cor,$fonte=null){
 $explode = explode(" ",$texto);
 for($r=0;$r<count($explode);$r++){
  if(strstr($explode[$r],".")){
   $ponto = ".";
  }elseif(strstr($explode[$r],",")){
   $ponto = ",";
  }elseif(strstr($explode[$r],";")){
   $ponto = ";";
  }else{
   $ponto = "";
  }
  $texto_sempontos = str_replace(",","",$explode[$r]);
  $texto_sempontos = str_replace(".","",$texto_sempontos);
  $texto_sempontos = str_replace(";","",$texto_sempontos);
  $explode1 = explode(" ",$termos);
  for($w=0;$w<count($explode1);$w++){
   if(trim($explode1[$w])==trim($texto_sempontos)){
    echo "<font color='$cor' face='$fonte'><b>".$texto_sempontos."</b></font>".@$ponto." ";
    $jafoi = "sim";
   }
  }
  if(@$jafoi==""){
   echo $explode[$r]." ";
  }
  $jafoi = "";
 }
}
?>
<script>
 function js_abreacervo(acervo){
   js_OpenJanelaIframe('','db_iframe_acervo','bib3_acervopesquisa001.php?bi06_seq='+acervo,'Informações do Acervo',true,50,200,800,400);
 }

 function js_mostratitulopop(iCodigoExemplar, sTitulo, sAssunto) {

   db_iframe_acervo.hide();
   location.href= 'bib1_emprestimo001.php?bi23_codigo='+iCodigoExemplar+'&bi06_titulo='+sTitulo+'&assunto='+sAssunto;
 }
</script>