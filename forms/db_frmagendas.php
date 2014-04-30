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
$clagendas->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed09_c_situacao");
$clrotulo->label("ed16_c_computador");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted17_i_codigo?>">
       <?=@$Led17_i_codigo?>
    </td>
    <td> 
<?
db_input('ed17_i_codigo',10,$Ied17_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted17_i_matricula?>">
       <?
       db_ancora(@$Led17_i_matricula,"js_pesquisaed17_i_matricula(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed17_i_matricula',10,$Ied17_i_matricula,true,'text',$db_opcao," onchange='js_pesquisaed17_i_matricula(false);'")
?>
       <?
db_input('ed09_c_situacao',20,$Ied09_c_situacao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted17_i_laboratorio?>">
       <?
       db_ancora(@$Led17_i_laboratorio,"js_pesquisaed17_i_laboratorio(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed17_i_laboratorio',10,$Ied17_i_laboratorio,true,'text',$db_opcao," onchange='js_pesquisaed17_i_laboratorio(false);'")
?>
       <?
db_input('ed16_c_computador',10,$Ied16_c_computador,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted17_d_data?>">
       <?=@$Led17_d_data?>
    </td>
    <td> 
<?
db_inputdata('ed17_d_data',@$ed17_d_data_dia,@$ed17_d_data_mes,@$ed17_d_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted17_c_inicial?>">
       <?=@$Led17_c_inicial?>
    </td>
    <td> 
<?
db_input('ed17_c_inicial',5,$Ied17_c_inicial,true,'text',$db_opcao,"OnKeyUp=\"mascara_hora(this.value,9)\"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted17_c_final?>">
       <?=@$Led17_c_final?>
    </td>
    <td> 
<?
db_input('ed17_c_final',5,$Ied17_c_final,true,'text',$db_opcao,"OnKeyUp=\"mascara_hora(this.value,10)\"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted17_c_status?>">
       <?=@$Led17_c_status?>
    </td>
    <td> 
<?
$x = array('COMPARECEU'=>'Compareceu','NÃO COMPARECEU'=>'Não Compareceu','TRANFERIDO'=>'Transferido para outra data','CANCELADO'=>'Cancelado','AGENDADO'=>'Agendado');
db_select('ed17_c_status',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function mascara_hora(hora,x)
 {
   var myhora = '';
   myhora = myhora + hora;
   if (myhora.length == 2)
   {
    myhora = myhora + ':';
    document.form1[x].value = myhora;
   }
   if (myhora.length == 5)
   {
    verifica_hora(x);
   }
 }

function verifica_hora(x)
{
 hrs = (document.form1[x].value.substring(0,2));
 min = (document.form1[x].value.substring(3,5));

 situacao = "";
// verifica hora
 if ( (hrs < 00 ) || (hrs > 23) || ( min < 00) || ( min > 59) )
  {
   alert("E R R O !!!\n\nHora inválida!\nPreencha corretamente o campo!");
   document.form1[x].value = "";
   document.form1[x].focus();
  }
}

function js_pesquisaed17_i_matricula(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matriculas','func_matriculas.php?funcao_js=parent.js_mostramatriculas1|ed09_i_codigo|ed09_c_situacao','Pesquisa',true);
  }else{
     if(document.form1.ed17_i_matricula.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_matriculas','func_matriculas.php?pesquisa_chave='+document.form1.ed17_i_matricula.value+'&funcao_js=parent.js_mostramatriculas','Pesquisa',false);
     }else{
       document.form1.ed09_c_situacao.value = ''; 
     }
  }
}
function js_mostramatriculas(chave,erro){
  document.form1.ed09_c_situacao.value = chave; 
  if(erro==true){ 
    document.form1.ed17_i_matricula.focus(); 
    document.form1.ed17_i_matricula.value = ''; 
  }
}
function js_mostramatriculas1(chave1,chave2){
  document.form1.ed17_i_matricula.value = chave1;
  document.form1.ed09_c_situacao.value = chave2;
  db_iframe_matriculas.hide();
}
function js_pesquisaed17_i_laboratorio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_laboratorios','func_laboratorios.php?funcao_js=parent.js_mostralaboratorios1|ed16_i_codigo|ed16_c_computador','Pesquisa',true);
  }else{
     if(document.form1.ed17_i_laboratorio.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_laboratorios','func_laboratorios.php?pesquisa_chave='+document.form1.ed17_i_laboratorio.value+'&funcao_js=parent.js_mostralaboratorios','Pesquisa',false);
     }else{
       document.form1.ed16_c_computador.value = ''; 
     }
  }
}
function js_mostralaboratorios(chave,erro){
  document.form1.ed16_c_computador.value = chave; 
  if(erro==true){ 
    document.form1.ed17_i_laboratorio.focus(); 
    document.form1.ed17_i_laboratorio.value = ''; 
  }
}
function js_mostralaboratorios1(chave1,chave2){
  document.form1.ed17_i_laboratorio.value = chave1;
  document.form1.ed16_c_computador.value = chave2;
  db_iframe_laboratorios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_agendas','func_agendas.php?funcao_js=parent.js_preenchepesquisa|ed17_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_agendas.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>