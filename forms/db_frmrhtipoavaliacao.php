<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: recursoshumanos
$clrhtipoavaliacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("h68_descricao");
?>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<form name="form1" method="post" action="" onsubmit="return js_validaValorPadrao();">
<center>
<fieldset style="margin-top: 20px; ">
	<legend>
		<strong>
		  Cadastro de Tipos de Avaliações
		</strong>
	</legend>
<table border="0" align="left">
  <tr>
    <td nowrap title="<?=@$Th69_sequencial?>">
       <?=@$Lh69_sequencial?>
    </td>
    <td> 
			<?
			 db_input('h69_sequencial',10,$Ih69_sequencial,true,'text', 3,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th69_descricao?>">
       <?=@$Lh69_descricao?>
    </td>
    <td> 
			<?
			 db_input('h69_descricao',60,$Ih69_descricao,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th69_rhgrupotipoavaliacao?>">
       <?
       db_ancora(@$Lh69_rhgrupotipoavaliacao,"js_pesquisah69_rhgrupotipoavaliacao(true);",$db_opcao);
       ?>
       <input type="hidden" id='iGrupo'  />
    </td>
    <td> 
				<?
				 db_input('h69_rhgrupotipoavaliacao',10,$Ih69_rhgrupotipoavaliacao,true,'text',$db_opcao," onchange='js_pesquisah69_rhgrupotipoavaliacao(false);'")
				?>
       <?
        db_input('h68_descricao',48,$Ih68_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  
  <tr id="cntValorMinimo">
    <td nowrap title="<?=@$Th69_quantminima?>">
       <?=@$Lh69_quantminima?>
    </td>
    <td> 
			<?
			 db_input('h69_quantminima',10,$Ih69_quantminima,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr id="cntValorMaximo" >
    <td nowrap title="<?=@$Th69_quantmaxima?>">
       <?=@$Lh69_quantmaxima?>
    </td>
    <td> 
			<?
			 db_input('h69_quantmaxima',10,$Ih69_quantmaxima,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  
  
  <tr id="cntValorPadrao" style="display: none;">
    <td nowrap="nowrap">
      <strong>Valor Padrão:</strong>
    </td>
    <td>
      <!-- <input type='text' id='valorpadrao' maxlength="10" onkeyup="js_ValidaCampos(this,1,'Valor Padrão','f','f',event);" name='valorpadrao' size="10" />-->
      <?
        db_input('valorpadrao',10,$Ih69_quantmaxima,true,'text',$db_opcao,"maxlength='10'");
      ?>   
    </td>
  </tr>
  </table>  


</fieldset>
<br>
  <input  name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</center>
</form>
<script>

function js_validaValorPadrao(){

   var iGrupo       = parseInt($F('iGrupo'));
   var iValorPadrao = parseInt($F('valorpadrao'));
   var iMinimo      = parseInt($F('h69_quantminima'));
   var iMaximo      = parseInt($F('h69_quantmaxima'));
   
   if (iGrupo == 2 && (iValorPadrao == '' || iValorPadrao == null )) {
   
     alert('Campo Valor Padrao Não Informado');
     return false;
   } 

   if (iGrupo != 2 && iMinimo > iMaximo) {
     
     alert('Quantidade Minima não pode ser Maior que a Maxima');
     return false;
   }
   
   if (iGrupo != 2 && iMinimo == iMaximo) {
   
     alert('Quantidade Minima não pode ser igual a Maxima');
     return false;  
   }
     

}


function js_valorPadrao(iTipoLancamento, lAlterar){

  /* validamos o tipo de lançamento
     se for 2 - valor padrao não mostra campos min. e max.
     e estes campos devem receberem no incluir o mesmo valor do padrao
  */
  if (lAlterar == false) {

	  if (iTipoLancamento == 2) {
	  
	    $('cntValorMinimo').style.display = 'none';
	    $('cntValorMaximo').style.display = 'none';
	    $('cntValorPadrao').style.display = 'table-row';
	  } else {
	  
	    $('cntValorMinimo').style.display = 'table-row';
	    $('cntValorMaximo').style.display = 'table-row';
	    $('cntValorPadrao').style.display = 'none';  
	    $('valorpadrao')    .value        = "";    
	  }
	  
  } else {
    
      $('cntValorMinimo').style.display = 'none';
      $('cntValorMaximo').style.display = 'none';
      $('cntValorPadrao').style.display = 'table-row';
      $('h69_quantminima').value        = "";
      $('h69_quantmaxima').value        = "";
  }
  
}



function js_pesquisah69_rhgrupotipoavaliacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhgrupotipoavaliacao','func_rhgrupotipoavaliacao.php?funcao_js=parent.js_mostrarhgrupotipoavaliacao1|h68_sequencial|h68_descricao|h68_tipolancamento','Pesquisa',true);
  }else{
     if(document.form1.h69_rhgrupotipoavaliacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhgrupotipoavaliacao','func_rhgrupotipoavaliacao.php?pesquisa_chave='+document.form1.h69_rhgrupotipoavaliacao.value+'&funcao_js=parent.js_mostrarhgrupotipoavaliacao','Pesquisa',false);
     }else{
       document.form1.h68_descricao.value = ''; 
     }
  }
}
function js_mostrarhgrupotipoavaliacao(chave,erro, chave2){

  document.form1.h68_descricao.value = chave; 
  if(erro==true){ 
    document.form1.h69_rhgrupotipoavaliacao.focus(); 
    document.form1.h69_rhgrupotipoavaliacao.value = ''; 
  } else {
    
    $('iGrupo').value = chave2;
    js_valorPadrao(chave2, false);
  }
}
function js_mostrarhgrupotipoavaliacao1(chave1,chave2, chave3){

  document.form1.h69_rhgrupotipoavaliacao.value = chave1;
  document.form1.h68_descricao.value = chave2;
  db_iframe_rhgrupotipoavaliacao.hide();
  $('iGrupo').value = chave3;
  js_valorPadrao(chave3, false);
  
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhtipoavaliacao','func_rhtipoavaliacao.php?funcao_js=parent.js_preenchepesquisa|h69_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhtipoavaliacao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>

<?PHP

if ($db_opcao == 2 || $db_opcao == 22) {
	
	if (isset($h69_sequencial)) {
		
		$oRhtipoavaliacao      = new cl_rhtipoavaliacao;
		$sSqlRhTipo            = $oRhtipoavaliacao->sql_query($h69_sequencial, "h68_tipolancamento", null, null);
		$rsRhTipo              = $oRhtipoavaliacao->sql_record($sSqlRhTipo);
		$iRhgrupotipoavaliacao = db_utils::fieldsMemory($rsRhTipo, 0)->h68_tipolancamento;
		
		if ($iRhgrupotipoavaliacao == 2) {
			
			echo "<script>";
			echo " js_valorPadrao({$iRhgrupotipoavaliacao}, true);";
			echo " $('valorpadrao').value = {$h69_quantminima} ;  ";
			echo "</script>";
		
		}
	
	}
}





?>