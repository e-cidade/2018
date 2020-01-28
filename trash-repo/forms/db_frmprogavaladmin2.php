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

//MODULO: educação
$clprogavaladmin->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed112_d_datainicio");
$clrotulo->label("ed112_i_progclasse");
$clrotulo->label("ed112_c_situacao");
if(trim(@$ed112_c_situacao)!="A"){
 $db_botao = false;
}
if($ed110_i_ptavaladmin==0 || $ed110_i_ptgeral==0){
 db_msgbox("Pontuação para Avaliação Administrativa ou Pontuação Geral está com valor zero! (Configurações)");
 $db_opcao = 3;
 $db_opcao1 = 3;
 $db_botao = false;
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted116_i_progmatricula?>">
   <?db_ancora(@$Led116_i_progmatricula,"js_pesquisaed116_i_progmatricula(true);",$db_opcao1);?>
  </td>
  <td>
   <?db_input('ed116_i_progmatricula',10,@$Ied116_i_progmatricula,true,'hidden',3,"")?>
   <?db_input('ed112_i_rhpessoal',10,@$Ied112_i_rhpessoal,true,'text',3,"")?>
   <?db_input('z01_nome',40,@$Iz01_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted112_d_datainicio?>">
   <?=@$Led112_d_datainicio?>
  </td>
  <td>
   <?db_inputdata('ed112_d_datainicio',@$ed112_d_datainicio_dia,@$ed112_d_datainicio_mes,@$ed112_d_datainicio_ano,true,'text',3,"")?>
   <?=@$Led112_i_progclasse?>
   <?db_input('ed107_c_descr',10,@$Ied107_c_descr,true,'text',3,'')?>
   <?if($db_opcao!=1){
    if(@$ed112_c_situacao=="A"){
     $ed112_c_situacao = "ABERTA";
    }elseif(@$ed112_c_situacao=="I"){
     $ed112_c_situacao = "INTERROMPIDA";
    }else{
     $ed112_c_situacao = "ENCERRADA";
    }
    ?>
    <?=@$Led112_c_situacao?>
    <input name="ed112_c_situacao" type="text" value="<?=@$ed112_c_situacao?>" style="background:#DEB887;" readonly>
   <?}?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted116_d_data?>">
   <?=@$Led116_d_data?>
  </td>
  <td>
   <?db_inputdata('ed116_d_data',@$ed116_d_data_dia,@$ed116_d_data_mes,@$ed116_d_data_ano,true,'text',$db_opcao," onchange=\"js_data();\"","","","parent.js_data();","js_data();")?>
   &nbsp;&nbsp;&nbsp;&nbsp;
   <?=@$Led116_i_ano?>
   <?db_input('ed116_i_ano',4,$Ied116_i_ano,true,'text',$db_opcao," onChange='js_valida(this.value);'")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted116_c_tipo?>">
   <?=@$Led116_c_tipo?>
  </td>
  <td>
   <?
   $x = array(''=>'','A'=>'AVALIAÇÃO','U'=>'AUTO-AVALIAÇÃO');
   db_select('ed116_c_tipo',$x,true,$db_opcao1,"");
   ?>
   <b>Pontuação:</b>
   <?
   if(@isset($ed112_i_rhpessoal)){
    $result3 = $clprogavaladmin->sql_record($clprogavaladmin->sql_query("","sum(ed106_f_pontuacao) as pontuacao",""," ed116_i_ano = $ed116_i_ano AND ed116_i_progmatricula = $ed116_i_progmatricula AND ed116_c_tipo = '$ed116_c_tipo' AND ed108_c_tipoaval = 'A'"));
    db_fieldsmemory($result3,0);
   }
   ?>
   <?db_input('pontuacao',4,@$pontuacao,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td colspan="2">
   <table border="1" width="730" cellspacing="0" cellpading="2">
    <tr>
     <td style="background:#444444;color:#DEB887;">
      <b>Questões:</b>
     </td>
    </tr>
    <?
    $cor1 = "#f3f3f3";
    $cor2 = "#DBDBDB";
    $cor = "";
    $result = $clprogavaladmin->sql_record($clprogavaladmin->sql_query("","ed116_i_codigo,ed116_i_opcaoquestao,ed108_i_codigo,ed108_t_descr","ed108_i_sequencia"," ed112_i_rhpessoal = ".@$ed112_i_rhpessoal." AND ed116_i_ano = ".@$ed116_i_ano." AND ed116_c_tipo = '$ed116_c_tipo'"));
    if($clprogavaladmin->numrows>0){
     for($x=0;$x<$clprogavaladmin->numrows;$x++){
      db_fieldsmemory($result,$x);
      if($cor==$cor1){
       $cor = $cor2;
      }else{
       $cor = $cor1;
      }
      ?>
      <input type="hidden" name="ed116_i_questaoaval<?=$x?>" value="<?=$ed108_i_codigo?>">
      <input type="hidden" name="ed116_i_codigo<?=$x?>" value="<?=$ed116_i_codigo?>">
      <tr bgcolor="<?=$cor?>">
       <td style="font-size:10px">
        <?=($x+1)." - ".$ed108_t_descr?><br>
        <?
        $result1 = $clopcaoquestao->sql_record($clopcaoquestao->sql_query("","*","ed106_i_sequencia"," ed106_c_ativo = 'S'"));
        if($clopcaoquestao->numrows>0){
         echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
         for($y=0;$y<$clopcaoquestao->numrows;$y++){
          db_fieldsmemory($result1,$y);
          $checked = $ed106_i_codigo==$ed116_i_opcaoquestao?"checked":"";
          ?>
          <input type="radio" name="ed116_i_opcaoquestao<?=$x?>" value="<?=$ed106_i_codigo?>" <?=$checked?> onclick="js_pontuacao(<?=$clprogavaladmin->numrows?>,<?=$clopcaoquestao->numrows?>)">
          <input type="hidden" name="ed116_i_opcaovalor<?=$x?>" value="<?=$ed106_f_pontuacao?>">
          <?
          echo $ed106_c_descr."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
         }
        }else{
         echo "Nenhuma resposta cadastrada.";
        }
        ?>
       </td>
      </tr>
      <?
     }
    }else{
     ?>
     <tr>
      <td>
       Nenhuma questão cadastrada.
      </td>
     </tr>
     <?
    }
    ?>
   </table>
  </td>
 </tr>
</table>
</center>
<input type="hidden" name="qtdlinha"  value="<?=$clprogavaladmin->numrows?>">
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed116_i_progmatricula(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('top.corpo','db_iframe_progmatricula','func_progmatricula.php?funcao_js=parent.js_mostraprogmatricula1|ed112_i_codigo|ed112_i_rhpessoal|z01_nome','Pesquisa de Matrículas',true);
 }
}
function js_mostraprogmatricula1(chave1,chave2,chave3){
 document.form1.ed116_i_progmatricula.value = chave1;
 document.form1.ed112_i_rhpessoal.value = chave2;
 document.form1.z01_nome.value = chave3;
 db_iframe_progmatricula.hide();
}
function js_data(){
 if(document.form1.ed116_i_progmatricula.value==""){
  alert("Informe a Matrícula!");
  document.form1.ed116_d_data_dia.value = "";
  document.form1.ed116_d_data_mes.value = "";
  document.form1.ed116_d_data_ano.value = "";
  js_pesquisaed116_i_progmatricula(true);
 }else{
  dataini = document.form1.ed112_d_datainicio_ano.value+document.form1.ed112_d_datainicio_mes.value+document.form1.ed112_d_datainicio_dia.value;
  data = document.form1.ed116_d_data_ano.value+document.form1.ed116_d_data_mes.value+document.form1.ed116_d_data_dia.value;
  if(dataini>data && document.form1.ed116_d_data_dia.value!="" && document.form1.ed116_d_data_mes.value!="" && document.form1.ed116_d_data_ano.value!=""){
   alert("Data deve ser maior que a Data de Início na Classe!");
   document.form1.ed116_d_data_dia.value = "";
   document.form1.ed116_d_data_mes.value = "";
   document.form1.ed116_d_data_ano.value = "";
   document.form1.ed116_d_data_dia.focus();
  }
 }
}
function js_valida(ano){
 if(document.form1.ed116_i_progmatricula.value==""){
  alert("Informe a Matrícula!");
  document.form1.ed116_i_ano.value = "";
  js_pesquisaed116_i_progmatricula(true);
 }else{
  if(ano.length<4){
   alert("Ano deve ser digitado com 4 dígitos!");
   document.form1.ed116_i_ano.value = "";
  }else{
   if(document.form1.ed116_i_ano.value<document.form1.ed112_d_datainicio_ano.value){
    alert("Ano Referente deve ser maior ou igual ao ano da Data de Início!");
    document.form1.ed116_i_ano.value = "";
   }else if(document.form1.ed116_i_ano.value!=document.form1.ed116_d_data_ano.value){
    alert("Ano Referente deve igual ao ano da Data!");
    document.form1.ed116_i_ano.value = "";
   }
  }
 }
}
function js_pontuacao(questoes,opcoes){
 soma = 0;
 for(i=0;i<questoes;i++){
  campo = "ed116_i_opcaoquestao"+i;
  campoh = "ed116_i_opcaovalor"+i;
  for(x=0;x<opcoes;x++){
   if(eval("document.form1."+campo+"[x].checked")==true){
    soma += parseInt(eval("document.form1."+campoh+"[x].value"));
   }
  }
 }
 document.form1.pontuacao.value = soma;
}
function js_pesquisa(){
 js_OpenJanelaIframe('top.corpo','db_iframe_progavaladmin','func_progavaladmin.php?funcao_js=parent.js_preenchepesquisa|ed112_i_rhpessoal|ed116_i_ano|ed116_c_tipo','Pesquisa de Avaliação Administrativa',true);
}
function js_preenchepesquisa(chave1,chave2,chave3){
 db_iframe_progavaladmin.hide();
 <?
 if($db_opcao!=1){
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?ed112_i_rhpessoal='+chave1+'&ed116_i_ano='+chave2+'&ed116_c_tipo='+chave3";
 }
 ?>
}
</script>