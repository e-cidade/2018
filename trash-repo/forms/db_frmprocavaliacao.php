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

//MODULO: educa��o
$clprocavaliacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed40_i_codigo");
$clrotulo->label("ed09_i_codigo");
$clrotulo->label("ed37_i_codigo");
if(!isset($excluir)){
$result1 = $clprocavaliacao->sql_record($clprocavaliacao->sql_query("","ed41_i_periodoavaliacao as perjacad",""," ed41_i_procedimento = $ed41_i_procedimento"));
if($clprocavaliacao->numrows>0){
 $sep = "";
 $per_cad = "";
 for($c=0;$c<$clprocavaliacao->numrows;$c++){
  db_fieldsmemory($result1,$c);
  $per_cad .= $sep.$perjacad;
  $sep = ",";
 }
}else{
 $per_cad = 0;
}
}
if(isset($ed41_i_formaavaliacao) && $ed41_i_formaavaliacao!=""){
 $result = $clformaavaliacao->sql_record($clformaavaliacao->sql_query("","ed37_c_tipo as formaaval",""," ed37_i_codigo = $ed41_i_formaavaliacao"));
 db_fieldsmemory($result,0);
 if($db_opcao==2){
  $formaaval = trim($formaaval);
 }else{
  $formaaval = "";
 }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0" align="left" width="100%">
 <tr>
  <td>
   <table border="0">
    <tr>
     <td nowrap title="<?=@$Ted41_i_codigo?>">
      <?=@$Led41_i_codigo?>
     </td>
     <td>
      <?db_input('ed41_i_codigo',15,$Ied41_i_codigo,true,'text',3,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted41_i_procedimento?>">
      <b>Procedimento:</b>
     </td>
     <td>
      <?db_input('ed41_i_procedimento',15,$Ied41_i_procedimento,true,'text',3,"")?>
      <?db_input('ed40_c_descr',30,@$Ied40_c_descr,true,'text',3,'')?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted41_i_periodoavaliacao?>">
      <?db_ancora(@$Led41_i_periodoavaliacao,"js_pesquisaed41_i_periodoavaliacao(true);",$db_opcao1);?>
     </td>
     <td>
      <?db_input('ed41_i_periodoavaliacao',15,$Ied41_i_periodoavaliacao,true,'text',$db_opcao1," onchange='js_pesquisaed41_i_periodoavaliacao(false);'")?>
      <?db_input('ed09_c_descr',30,@$Ied09_c_descr,true,'text',3,'')?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted41_i_formaavaliacao?>">
      <?db_ancora(@$Led41_i_formaavaliacao,"js_pesquisaed41_i_formaavaliacao(true);",$db_opcao);?>
     </td>
     <td>
      <?db_input('ed41_i_formaavaliacao',15,$Ied41_i_formaavaliacao,true,'text',$db_opcao," onchange='js_pesquisaed41_i_formaavaliacao(false);'")?>
      <?db_input('ed37_c_descr',30,@$Ied37_c_descr,true,'text',3,'')?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted41_c_boletim?>" valign="top">
      <?=@$Led41_c_boletim?>
      <?
      $x = array('S'=>'SIM','N'=>'N�O');
      db_select('ed41_c_boletim',$x,true,$db_opcao,"");
      ?>
      <br>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
      <input name="forma" type="hidden" value="<?=$forma?>" >
     </td>
     <td align="right">
      <iframe src="" name="iframe_aval" id="iframe_aval" width="170" height="80" frameborder="0" scrolling="no"></iframe>
     </td>
    </tr>
   </table>
  </td>
  <td valign="top" width="220">
   <fieldset style="width:90%"><legend><b>Aproveitamento desta avalia��o</b></legend>
    <table width="100%">
     <?
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
      $desabilitar = "disabled";
     }else{
      if($db_opcao==3){
       $desabilitar = "disabled";
      }else{
       $desabilitar = "";
      }
     }
     ?>
     <tr>
      <td>
       Alunos que n�o obtiveram o aproveitamento m�nimo para:
      </td>
     </tr>
     <tr>
      <td nowrap>
       <?
       if(isset($ed41_i_sequencia)){
        $ed41_i_sequencia = $ed41_i_sequencia;
       }else{
        $ed41_i_sequencia = "";
       }
       $avalvinc   = isset($ed41_i_procavalvinc)?$ed41_i_procavalvinc:"";
       $resultvinc = isset($ed41_i_procresultvinc)?$ed41_i_procresultvinc:"";
       AvalResultList("vinculado",$ed41_i_procedimento,$desabilitar,$ed41_i_sequencia,$avalvinc,$resultvinc);
       ?>
      </td>
     </tr>
     <tr>
       <td>
         N� de Disciplinas Reprovadas:
       </td>
     </tr>
     <tr>
       <td>
          <select id='ed41_numerodisciplinasrecuperacao' name="ed41_numerodisciplinasrecuperacao" style="width: 100%">
            <?php
            $sQuantidadeSelecionada = '';

            if (isset($ed41_numerodisciplinasrecuperacao)) {
              $sQuantidadeSelecionada = $ed41_numerodisciplinasrecuperacao;
            }
            $aQuantidades = array("" => "Todas",
                                  1 => "Uma",
                                  2 => 'Duas',
                                  3 => 'Tr�s',
                                  4 => 'Quatro',
                                  5 => 'Cinco'
            );
            foreach ($aQuantidades as $iQuantidade  => $sLabel) {

              $sSelecionado = '';
              if ($iQuantidade == $sQuantidadeSelecionada) {
                $sSelecionado = ' selected ';
              }
              echo "<option value='{$iQuantidade}' {$sSelecionado}>{$sLabel}</option>";
            }

           ?>
          </select>
       </td>
     </tr>
    </table>
   </fieldset>
  </td>
 </tr>
</table>
</form>
</center>
<script>
function js_pesquisaed41_i_periodoavaliacao(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('parent','db_iframe_periodoavaliacao','func_periodoavaliacao.php?periodos=<?=@$per_cad?>&funcao_js=parent.iframe_avaliacoes.js_mostraperiodoavaliacao1|ed09_i_codigo|ed09_c_descr','Pesquisa de Per�odos de Avalia��o',true,0,0);
 }else{
  if(document.form1.ed41_i_periodoavaliacao.value != ''){
   js_OpenJanelaIframe('parent','db_iframe_periodoavaliacao','func_periodoavaliacao.php?periodos=<?=@$per_cad?>&pesquisa_chave='+document.form1.ed41_i_periodoavaliacao.value+'&funcao_js=parent.iframe_avaliacoes.js_mostraperiodoavaliacao','Pesquisa',false);
  }else{
   document.form1.ed09_c_descr.value = '';
  }
 }
}
function js_mostraperiodoavaliacao(chave,erro){
 document.form1.ed09_c_descr.value = chave;
 if(erro==true){
  document.form1.ed41_i_periodoavaliacao.focus();
  document.form1.ed41_i_periodoavaliacao.value = '';
 }
}
function js_mostraperiodoavaliacao1(chave1,chave2){
 document.form1.ed41_i_periodoavaliacao.value = chave1;
 document.form1.ed09_c_descr.value = chave2;
 parent.db_iframe_periodoavaliacao.hide();
}
function js_pesquisaed41_i_formaavaliacao(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('parent','db_iframe_formaavaliacao','func_formaavaliacao.php?forma=<?=@$formaaval?>&funcao_js=parent.iframe_avaliacoes.js_mostraformaavaliacao1|ed37_i_codigo|ed37_c_descr','Pesquisa de Formas de Avalia��o',true,0,0);
 }else{
  if(document.form1.ed41_i_formaavaliacao.value != ''){
   js_OpenJanelaIframe('parent','db_iframe_formaavaliacao','func_formaavaliacao.php?forma=<?=@$formaaval?>&pesquisa_chave='+document.form1.ed41_i_formaavaliacao.value+'&funcao_js=parent.iframe_avaliacoes.js_mostraformaavaliacao','Pesquisa',false);
  }else{
   document.form1.ed37_c_descr.value = '';
  }
 }
}
function js_mostraformaavaliacao(chave,erro){
 document.form1.ed37_c_descr.value = chave;
 if(erro==true){
  document.form1.ed41_i_formaavaliacao.focus();
  document.form1.ed41_i_formaavaliacao.value = '';
  iframe_aval.location.href = "edu1_procedimento004.php";
 }else{
  iframe_aval.location.href = "edu1_procedimento004.php?codigo="+document.form1.ed41_i_formaavaliacao.value;
 }
}
function js_mostraformaavaliacao1(chave1,chave2){
 document.form1.ed41_i_formaavaliacao.value = chave1;
 document.form1.ed37_c_descr.value = chave2;
 iframe_aval.location.href = "edu1_procedimento004.php?codigo="+chave1;
 parent.db_iframe_formaavaliacao.hide();
}
function js_sobe() {
 var F = document.getElementById("ordenacao");
 var G = document.getElementById("ordenacaotipo");
 if(F.selectedIndex != -1 && F.selectedIndex > 0 || G.selectedIndex != -1 && G.selectedIndex > 0) {
  var SI = F.selectedIndex - 1;
  var SI2 = G.selectedIndex - 1;
  var auxText = F.options[SI].text;
  var auxText2 = G.options[SI2].text;
  var auxValue = F.options[SI].value;
  var auxValue2 = G.options[SI2].value;
  F.options[SI] = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
  G.options[SI2] = new Option(G.options[SI2 + 1].text,G.options[SI2 + 1].value);
  F.options[SI + 1] = new Option(auxText,auxValue);
  G.options[SI2 + 1] = new Option(auxText2,auxValue2);
  F.options[SI].selected = true;
  G.options[SI2].selected = true;
 }
}
function js_desce() {
 var F = document.getElementById("ordenacao");
 var G = document.getElementById("ordenacaotipo");
 if(F.selectedIndex != -1 && F.selectedIndex < (F.length - 1) || G.selectedIndex != -1 && G.selectedIndex < (G.length - 1)) {
  var SI = F.selectedIndex + 1;
  var SI2 = G.selectedIndex + 1;
  var auxText = F.options[SI].text;
  var auxText2 = G.options[SI2].text;
  var auxValue = F.options[SI].value;
  var auxValue2 = G.options[SI2].value;
  F.options[SI] = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
  G.options[SI2] = new Option(G.options[SI2 - 1].text,G.options[SI2 - 1].value);
  F.options[SI - 1] = new Option(auxText,auxValue);
  G.options[SI2 - 1] = new Option(auxText2,auxValue2);
  F.options[SI].selected = true;
  G.options[SI2].selected = true;
 }
}
function js_selecionar() {
 var F = document.getElementById("ordenacao").options;
 var G = document.getElementById("ordenacaotipo").options;
 for(var i = 0;i < F.length;i++) {
   F[i].selected = true;
 }
 for(var i = 0;i < G.length;i++) {
   G[i].selected = true;
 }
 return true;
}
function js_selectum(nome){
 var F = document.getElementById(nome);
 var G = document.getElementById(nome+"tipo");
 for(var i = 0;i < G.options.length;i++){
  if(G.selectedIndex == i){
   F.options[i].selected = true;
  }else{
   F.options[i].selected = false;
  }
 }
}
function js_selectdois(nome){
 var F = document.getElementById(nome);
 var G = document.getElementById(nome+"tipo");
 for(var i = 0;i < F.options.length;i++){
  if(F.selectedIndex == i) {
   G.options[i].selected = true;

  }else{
   G.options[i].selected = false;
  }
 }
}
</script>