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

//MODULO: pessoal
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clselecaopontorubricas->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("r72_descricao");
$clrotulo->label("rh27_descr");

if (isset($oGet->db_opcaoal)){
  $db_opcao = 33;
  $db_botao = false;
} else if(isset($oPost->opcao) && $oPost->opcao=="alterar"){
  $db_botao = true;
  $db_opcao = 2;
} else if(isset($oPost->opcao) && $oPost->opcao=="excluir"){
  $db_opcao = 3;
  $db_botao = true;
} else{
	  
  $db_opcao = 1;
  $db_botao = true;
  
  if( isset($oPost->novo) || isset($oPost->alterar) || isset($oPost->excluir) || (isset($oPost->incluir) && !$lSqlErro ) ){
  	$r73_sequencial  = "";
    $r73_rubric      = "";
    $r73_tipo        = "";
    $r73_valor       = "";
    $rh27_descr      = "";
  }
  
} 
?>
<center>
<form name="form1" method="post" action="" onSubmit="return js_validaSubmit();">
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tr73_sequencial?>">
      <?=@$Lr73_sequencial?>
    </td>
    <td> 
			<?
			  db_input('r73_sequencial',10,$Ir73_sequencial,true,'text',3,"");
			  db_input('r73_selecaoponto',10,'',true,'hidden',3,"");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr73_rubric?>">
      <?
        db_ancora(@$Lr73_rubric,"js_pesquisar73_rubric(true);",$db_opcao);
      ?>
    </td>
    <td> 
      <?
				db_input('r73_rubric',10,$Ir73_rubric,true,'text',$db_opcao," onchange='js_pesquisar73_rubric(false);'");
				db_input('rh27_descr',50,$Irh27_descr,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr73_tipo?>">
      <?=@$Lr73_tipo?>
    </td>
    <td> 
			<?
			  $sSqlTipoValor = $clselecaopontorubricastipo->sql_query_file();
			  $rsTipoValor   = $clselecaopontorubricastipo->sql_record($sSqlTipoValor);

			  db_selectrecord('r73_tipo',$rsTipoValor,true,$db_opcao,'','','','','js_validaTela()',1);
			?>
    </td>
  </tr>
  <tr id='linhaValor'>
    <td nowrap title="<?=@$Tr73_valor?>">
      <?=@$Lr73_valor?>
    </td>
    <td> 
			<?
			  db_input('r73_valor',10,$Ir73_valor,true,'text',$db_opcao,"")
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
			 $chavepri= array("r73_sequencial"=>@$r73_sequencial);
			 $cliframe_alterar_excluir->chavepri      = $chavepri;
			 $cliframe_alterar_excluir->sql           = $clselecaopontorubricas->sql_query(null,"*",null,"r73_selecaoponto = ".@$r73_selecaoponto);
			 $cliframe_alterar_excluir->campos        = "r73_sequencial,r73_rubric,rh27_descr,r74_descricao,r73_valor";
			 $cliframe_alterar_excluir->legenda       = "Rubricas Lançadas";
			 $cliframe_alterar_excluir->iframe_height = "160";
			 $cliframe_alterar_excluir->iframe_width  = "700";
			 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
</form>
  </center>
<script>

function js_validaSubmit() {
  if ( document.getElementById('db_opcao').value != 'Excluir' ) {
	  if ( document.form1.r73_tipo.value != 3 && document.form1.r73_valor.value.trim() == '' ) {
	    alert('Valor/Quantidade não informado!');
	    return false;
	  } 
    if ( document.form1.r73_tipo.value != 3 && document.form1.r73_valor.value.trim() == 0 ) {
      alert('Valor/Quantidade deve ser diferente de zero!');
      return false;
    } 	  
  }
}

function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}

function js_pesquisar73_rubric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_selecaopontorubricas','db_iframe_rhrubricas','func_rhrubricas.php?fixas=true&datlimit=false&funcao_js=parent.js_mostrarhrubricas1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
     if(document.form1.r73_rubric.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_selecaopontorubricas','db_iframe_rhrubricas','func_rhrubricas.php?fixas=true&datlimit=false&pesquisa_chave='+document.form1.r73_rubric.value+'&funcao_js=parent.js_mostrarhrubricas','Pesquisa',false);
     }else{
       document.form1.rh27_descr.value = ''; 
     }
  }
}
function js_mostrarhrubricas(chave,erro){
  document.form1.rh27_descr.value = chave; 
  if(erro==true){ 
    document.form1.r73_rubric.focus(); 
    document.form1.r73_rubric.value = ''; 
  }
}
function js_mostrarhrubricas1(chave1,chave2){
  document.form1.r73_rubric.value = chave1;
  document.form1.rh27_descr.value = chave2;
  db_iframe_rhrubricas.hide();
}

function js_validaTela(){
  
  var doc = document.form1;
  
  if ( doc.r73_tipo.value == 3  ) {
    doc.r73_valor.value    = '';
    document.getElementById('linhaValor').style.display = 'none';
  } else {
    document.getElementById('linhaValor').style.display = '';
  }  
 
}

js_validaTela();

</script>