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
$cldb_projetosclientes->rotulo->label();
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
//     $at64_projeto = "";
		$at64_sequencial = "";
	    $at64_cliente    = "";
    	$at01_nomecli    = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
<?
db_input('at64_sequencial',10,$Iat64_sequencial,true,'hidden',$db_opcao,"")
?>
  <tr>
    <td nowrap title="<?=@$Tat64_projeto?>">
       <?=@$Lat64_projeto?>
    </td>
    <td> 
<?
db_input('at64_projeto',10,$Iat64_projeto,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat64_cliente?>">
       <?
       db_ancora("<b>Cliente:</b>","js_pesquisaat64_cliente(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at64_cliente',4,$Iat64_cliente,true,'text',$db_opcao," onchange='js_pesquisaat64_cliente(false);'")
?>
       <?
db_input('at01_nomecli',40,$Iat01_nomecli,true,'text',3,'')
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
	 $chavepri= array("at64_sequencial"=>@$at64_sequencial,"at64_projeto"=>@$at64_projeto);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $cldb_projetosclientes->sql_query(null,"*","at64_sequencial","at64_projeto=$at64_projeto");
	 $cliframe_alterar_excluir->campos  ="at01_nomecli";
	 $cliframe_alterar_excluir->legenda ="CLIENTES";
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
function js_pesquisaat64_cliente(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_projetosclientes','db_iframe_clientes','func_clientes.php?funcao_js=parent.js_mostraclientes1|at01_codcli|at01_nomecli','Pesquisa',true,'0');
  }else{
     if(document.form1.at64_cliente.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_projetosclientes','db_iframe_clientes','func_clientes.php?pesquisa_chave='+document.form1.at64_cliente.value+'&funcao_js=parent.js_mostraclientes','Pesquisa',false);
     }else{
       document.form1.at01_nomecli.value = ''; 
     }
  }
}
function js_mostraclientes(chave,erro){
  document.form1.at01_nomecli.value = chave; 
  if(erro==true){ 
    document.form1.at64_cliente.focus(); 
    document.form1.at64_cliente.value = ''; 
  }
}
function js_mostraclientes1(chave1,chave2){
  document.form1.at64_cliente.value = chave1;
  document.form1.at01_nomecli.value = chave2;
  db_iframe_clientes.hide();
}
</script>