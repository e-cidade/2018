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

//MODULO: biblioteca
if(empty($bi16_inclusao_dia) || @$pontos>0){
 $bi16_inclusao_dia = date("d",db_getsession("DB_datausu"));
 $bi16_inclusao_mes = date("m",db_getsession("DB_datausu"));
 $bi16_inclusao_ano = date("Y",db_getsession("DB_datausu"));
}
?>
<center>
<?
if(@$pontos<0){
 //$db_opcao = 3;
 ?>
  Leitor possui Carteira Válida, portanto NÃO poderá renovar.<br>
  Para ativar outra carteira, apenas após o vencimento da atual.
 <?
}else{
 if($db_opcao==2){
  $db_opcao1 = 3;
 }else{
  $db_opcao1 = 1;
 }
 ?>
 <?db_input('bi16_leitor',10,$Ibi16_leitor,true,'hidden',"","")?>
 <table border="0">
  <tr>
   <td nowrap title="<?=@$Tbi16_codigo?>">
    <?=@$Lbi16_codigo?>
   </td>
   <td>
    <?db_input('bi16_codigo',10,$Ibi16_codigo,true,'text',3,'')?>
   </td>
  </tr>
  <tr>
   <td nowrap title="<?=@$Tbi16_leitorcategoria?>">
    <?db_ancora(@$Lbi16_leitorcategoria,"js_pesquisabi16_leitorcategoria(true);",$db_opcao);?>
   </td>
   <td>
    <?db_input('bi16_leitorcategoria',10,$Ibi16_leitorcategoria,true,'text',$db_opcao," onchange='js_pesquisabi16_leitorcategoria(false);'")?>
    <?db_input('bi07_nome',50,$Ibi07_nome,true,'text',3,'')?>
   </td>
  </tr>
  <tr>
   <td nowrap title="<?=@$Tbi16_inclusao?>">
    <?=@$Lbi16_inclusao?>
   </td>
   <td>
    <?db_inputdata('bi16_inclusao',@$bi16_inclusao_dia,@$bi16_inclusao_mes,@$bi16_inclusao_ano,true,'text',$db_opcao1,"")?>
   </td>
  </tr>
  <tr>
   <td nowrap title="<?=@$Tbi16_validade?>">
    <?=@$Lbi16_validade?>
   </td>
   <td>
    <?db_inputdata('bi16_validade',@$bi16_validade_dia,@$bi16_validade_mes,@$bi16_validade_ano,true,'text',$db_opcao,"")?>
   </td>
  </tr>
  </table>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="cancelar" type="submit" value="Cancelar" <?=($db_opcao==1?"disabled":"")?>>
</center>
<script>
function js_pesquisabi16_leitorcategoria(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_leitorcategoria','func_leitorcategoria.php?funcao_js=parent.js_mostraleitorcategoria1|bi07_codigo|bi07_nome','Pesquisa',true);
 }else{
  if(document.form1.bi16_leitorcategoria.value != ''){
   js_OpenJanelaIframe('','db_iframe_leitorcategoria','func_leitorcategoria.php?pesquisa_chave='+document.form1.bi16_leitorcategoria.value+'&funcao_js=parent.js_mostraleitorcategoria','Pesquisa',false);
  }else{
   document.form1.bi07_nome.value = '';
  }
 }
}
function js_mostraleitorcategoria(chave,erro){
 document.form1.bi07_nome.value = chave;
 if(erro==true){
  document.form1.bi16_leitorcategoria.focus();
  document.form1.bi16_leitorcategoria.value = '';
 }
}
function js_mostraleitorcategoria1(chave1,chave2){
 document.form1.bi16_leitorcategoria.value = chave1;
 document.form1.bi07_nome.value = chave2;
 db_iframe_leitorcategoria.hide();
}
function somadata(dias){
 var ano = document.form1.bi16_inclusao_ano.value;
 var mes = document.form1.bi16_inclusao_mes.value;
 var dia = document.form1.bi16_inclusao_dia.value;
 var i = dias;
 for(i = 0;i<dias;i++){
  if (mes == "01" || mes == "03" || mes == "05" || mes == "07" || mes == "08" || mes == "10" || mes == "12"){
   if(mes == "12" && dia == "31"){
    mes = "01";
    ano++;
    dia = "00";
   }
   if(dia == "31" && mes != "12"){
    mes++;
    dia = "00";
   }
  }
  if(mes == "04" || mes == "06" || mes == "09" || mes == "11"){
   if(dia == "30"){
    dia =  "00";
    mes++;
   }
  }
  if(mes == "02"){
   if(ano % 4 == 0){
    if(dia == "29"){
     dia = "00";
    }
   }else{
    if(dia == "28"){
     dia = "00";
    }
   }
  }
  dia++;
 }
 if(dia==1){dia="01";}
 if(dia==2){dia="02";}
 if(dia==3){dia="03";}
 if(dia==4){dia="04";}
 if(dia==5){dia="05";}
 if(dia==6){dia="06";}
 if(dia==7){dia="07";}
 if(dia==8){dia="08";}
 if(dia==9){dia="09";}
 if(mes==1){mes="01";}
 if(mes==2){mes="02";}
 if(mes==3){mes="03";}
 if(mes==4){mes="04";}
 if(mes==5){mes="05";}
 if(mes==6){mes="06";}
 if(mes==7){mes="07";}
 if(mes==8){mes="08";}
 if(mes==9){mes="09";}
 document.form1.bi16_validade_ano.value = ano;
 document.form1.bi16_validade_mes.value = mes;
 document.form1.bi16_validade_dia.value = dia;
}
somadata(365);
</script>
<?}?>