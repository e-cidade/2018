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
$clprogconvocacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed112_d_datainicio");
$clrotulo->label("ed112_i_progclasse");
$clrotulo->label("ed112_c_situacao");
if($ed110_i_ptconvocacao==0 || $ed110_i_ptgeral==0){
 db_msgbox("Pontuação da Convocação ou Pontuação Geral está com valor zero (Configurações)!");
 $db_opcao = 3;
 $db_opcao1 = 3;
 $db_botao = false;
}
if(isset($codmatricula)){
 $db_opcao = 1;
 $result = $clprogmatricula->sql_record($clprogmatricula->sql_query("","*",""," ed112_i_codigo = $codmatricula"));
 db_fieldsmemory($result,0);
 $ed115_i_progmatricula = $ed112_i_codigo;
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted115_i_codigo?>">
   <?=@$Led115_i_codigo?>
  </td>
  <td>
   <?db_input('ed115_i_codigo',10,$Ied115_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted115_i_progmatricula?>">
   <?db_ancora(@$Led115_i_progmatricula,"js_pesquisaed115_i_progmatricula(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed115_i_progmatricula',10,$Ied115_i_progmatricula,true,'hidden',3,"")?>
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
    if($ed112_c_situacao=="A"){
     $ed112_c_situacao = "ABERTA";
    }elseif($ed112_c_situacao=="I"){
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
 <?if(isset($codmatricula)){?>
 <tr>
  <td colspan="2">
   <b>Selecione o Ano:</b><br>
   <select name="ano" style="font-size:9px;width:200px;height:18px;" onchange="js_ano(this.value,<?=$codmatricula?>)">
    <option></option>
    <?
    $sql = "SELECT DISTINCT ed111_i_ano
            FROM convocacao
            WHERE ed111_d_data >= '$ed112_d_datainicio'
            ORDER BY ed111_i_ano DESC";
    $sql_result = pg_query($sql);
    while($row=pg_fetch_array($sql_result)){
     $desc_ano=$row["ed111_i_ano"];
     ?>
     <option value="<?=$desc_ano?>" <?=$desc_ano==@$ano_chave?"selected":""?>><?=$desc_ano;?></option>
     <?
    }
    ?>
   </select>
  </td>
 <tr>
 <?}?>
 <?if(isset($ano_chave)){?>
 </tr>
  <td colspan="2">
   <b>Participações já cadastradas no Ano <?=$ano_chave?>:</b>
   <br>
   <?
   $sql = "SELECT ed111_i_codigo,ed111_c_titulo,ed111_d_data
           FROM progconvocacao
            inner join convocacao on ed111_i_codigo = ed115_i_convocacao
           WHERE ed111_i_ano = '$ano_chave'
           AND ed111_d_data >= '$ed112_d_datainicio'
           AND ed115_i_progmatricula = $ed112_i_codigo
           ORDER BY ed111_d_data ASC
           ";
   $sql_result = pg_query($sql);
   $linhas = pg_num_rows($sql_result);
   ?>
   <select name="convocacoes" id="convocacoes" size="18"  multiple style="font-size:9px;width:350px;">
    <?
    if($linhas>0){
     while($row=pg_fetch_array($sql_result)){
      $cod_jatem=$row["ed111_i_codigo"];
      $desc_jatem=$row["ed111_c_titulo"];
      $data_jatem=db_formatar($row["ed111_d_data"],'d');
      ?>
      <option value="<?=$cod_jatem?>"><?=$data_jatem?> - <?=$desc_jatem?></option>
      <?
     }
    }else{
     ?>
      <option value="">Nenhuma participação cadastrada no ano <?=$ano_chave?></option>
     <?
    }
    ?>
   </select>
  </td>
 </tr>
 <?}?>
</table>
</center>
<input name="<?=($db_opcao==1?"excluir":($db_opcao==2||$db_opcao==22?"excluir":"excluir"))?>" type="button" id="db_opcao" value="<?=($db_opcao==1?"Excluir":($db_opcao==2||$db_opcao==22?"Excluir":"Excluir"))?>" <?=($db_botao==false||$linhas==0?"disabled":"")?> onclick="js_selecionar();">
</form>
<script>
function js_pesquisaed115_i_progmatricula(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('top.corpo','db_iframe_progmatricula','func_progmatricula.php?funcao_js=parent.js_preenchepesquisamat|ed112_i_codigo','Pesquisa de Matrículas',true);
 }
}
function js_preenchepesquisamat(chave){
 db_iframe_progmatricula.hide();
 <?
 echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?codmatricula='+chave";
 ?>
}
function js_ano(valor,codmatricula){
 if(valor!=""){
  location.href = "edu1_progconvocacao003.php?codmatricula="+codmatricula+"&ano_chave="+valor;
 }
}
function js_selecionar(){
 var F = document.form1.convocacoes.options;
 convoca = "";
 sep = "";
 for(var i = 0;i < F.length;i++) {
  if(F[i].selected==true){
   convoca += sep+F[i].value;
   sep = ",";
  }
 }
 if(convoca==""){
  alert("Selecione alguma convocação!");
  document.form1.convocacoes.options[0].selected = true;;
 }else{
  location.href = "edu1_progconvocacao003.php?excluir&codmatricula="+document.form1.ed115_i_progmatricula.value+"&ano_chave="+document.form1.ano.value+"&convoca="+convoca;
 }
}
</script>