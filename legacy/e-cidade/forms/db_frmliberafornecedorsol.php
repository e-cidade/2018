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

//MODULO: Compras
include("dbforms/db_classesgenericas.php");
require_once ("classes/db_cgm_classe.php");
$clcgm = new cl_cgm();

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clliberafornecedorsol->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc82_sequencial");
$clrotulo->label("pc10_data");
$clrotulo->label("z01_nome");
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
     $pc83_liberafornecedor = "";
     $pc83_solicita = "";
   }
} 
?>
<form name="form1" method="post" action="">
    <?
    db_input('pc83_sequencial',10,$Ipc83_sequencial,true,'hidden',3,"")
    ?>
    <?
    if(isset($pc82_sequencial) && trim($pc82_sequencial) != ""){
      $pc83_liberafornecedor = $pc82_sequencial;
    }
    db_input('pc83_liberafornecedor',10,$Ipc83_liberafornecedor,true,'hidden',3);
    ?>
<center>
<table border="0" style="margin-top: 15px;">
  <tr>
    <td nowrap title="<?=@$Tpc83_solicita?>">
       <?
       db_ancora(@$Lpc83_solicita,"js_pesquisapc83_solicita(true);",$db_opcao);
       ?>
    </td>
    <td> 
		<?
		db_input('pc83_solicita',10,$Ipc83_solicita,true,'text',$db_opcao," onchange='js_pesquisapc83_solicita(false);'")
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
	 $chavepri= array("pc83_sequencial"=>@$pc82_sequencial);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clliberafornecedorsol->sql_query_file(null,"*",null,"pc83_liberafornecedor = $pc82_sequencial");
	 $cliframe_alterar_excluir->campos  ="pc83_sequencial,pc83_liberafornecedor,pc83_solicita";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
	 $cliframe_alterar_excluir->opcoes = 3;
	 
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
function js_pesquisapc83_liberafornecedor(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_liberafornecedorsol','db_iframe_liberafornecedor','func_liberafornecedor.php?funcao_js=parent.js_mostraliberafornecedor1|pc82_sequencial|pc82_sequencial','Pesquisa',true,'0','1');
  }else{
     if(document.form1.pc83_liberafornecedor.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_liberafornecedorsol','db_iframe_liberafornecedor','func_liberafornecedor.php?pesquisa_chave='+document.form1.pc83_liberafornecedor.value+'&funcao_js=parent.js_mostraliberafornecedor','Pesquisa',false);
     }else{
       document.form1.pc82_sequencial.value = ''; 
     }
  }
}
function js_mostraliberafornecedor(chave,erro){
  document.form1.pc82_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.pc83_liberafornecedor.focus(); 
    document.form1.pc83_liberafornecedor.value = ''; 
  }
}
function js_mostraliberafornecedor1(chave1,chave2){
  document.form1.pc83_liberafornecedor.value = chave1;
  document.form1.pc82_sequencial.value = chave2;
  db_iframe_liberafornecedor.hide();
}
function js_pesquisapc83_solicita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_liberafornecedorsol','db_iframe_solicita','func_solicita.php?funcao_js=parent.js_mostrasolicita1|pc10_numero|pc10_data','Pesquisa',true,'0','1');
  }else{
     if(document.form1.pc83_solicita.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_liberafornecedorsol','db_iframe_solicita','func_solicita.php?pesquisa_chave='+document.form1.pc83_solicita.value+'&funcao_js=parent.js_mostrasolicita','Pesquisa',false);
     }else{
       document.form1.pc10_data.value = ''; 
     }
  }
}
function js_mostrasolicita(chave,erro){
  document.form1.pc10_data.value = chave; 
  if(erro==true){ 
    document.form1.pc83_solicita.focus(); 
    document.form1.pc83_solicita.value = ''; 
  }
}
function js_mostrasolicita1(chave1,chave2){
  document.form1.pc83_solicita.value = chave1;
  document.form1.pc10_data.value = chave2;
  db_iframe_solicita.hide();
}
</script>