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

//MODULO: atendimento
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clatendemail->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("at01_nomecli");
if(isset($db_opcaoal)){
   $db_opcao=33;
    $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    $db_botao=true;
}else{  
    $db_opcao = 1;
    $db_botao=true;
    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
     $at12_seq = "";
     $at12_email = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tat12_codcli?>">
       <?
       db_ancora(@$Lat12_codcli,"js_pesquisaat12_codcli(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at12_codcli',4,$Iat12_codcli,true,'text',$db_opcao," onchange='js_pesquisaat12_codcli(false);'")
?>
       <?
db_input('at01_nomecli',40,$Iat01_nomecli,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat12_seq?>">
       <?=@$Lat12_seq?>
    </td>
    <td> 
<?
db_input('at12_seq',10,$Iat12_seq,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat12_email?>">
       <?=@$Lat12_email?>
    </td>
    <td> 
<?
db_input('at12_email',40,$Iat12_email,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </tr>
    <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("at12_codcli"=>@$at12_codcli,"at12_seq"=>@$at12_seq);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clatendemail->sql_query_file($at12_codcli);
	 $cliframe_alterar_excluir->campos  ="at12_codcli,at12_seq,at12_email";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
  </center>
</form>
<script>
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisaat12_codcli(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_atendemail','db_iframe_clientes','func_clientes.php?funcao_js=parent.js_mostraclientes1|at01_codcli|at01_nomecli','Pesquisa',true);
  }else{
     if(document.form1.at12_codcli.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_atendemail','db_iframe_clientes','func_clientes.php?pesquisa_chave='+document.form1.at12_codcli.value+'&funcao_js=parent.js_mostraclientes','Pesquisa',false);
     }else{
       document.form1.at01_nomecli.value = ''; 
     }
  }
}
function js_mostraclientes(chave,erro){
  document.form1.at01_nomecli.value = chave; 
  if(erro==true){ 
    document.form1.at12_codcli.focus(); 
    document.form1.at12_codcli.value = ''; 
  }
}
function js_mostraclientes1(chave1,chave2){
  document.form1.at12_codcli.value = chave1;
  document.form1.at01_nomecli.value = chave2;
  db_iframe_clientes.hide();
}
</script>