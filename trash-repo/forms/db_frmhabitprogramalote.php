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

//MODULO: Habitacao
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clhabitprogramalote->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j34_setor");
$clrotulo->label("ht01_descricao");
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
      $ht05_idbql = "";
      $j34_setor  = "";
    }
} 
?>
<form name="form1" method="post" action="">
  <fieldset>
		<table border="0" align="center">
		  <tr>
		    <td nowrap title="<?=@$Tht05_sequencial?>">
		      <?=@$Lht05_sequencial?>
		    </td>
		    <td> 
					<?
					  db_input('ht05_sequencial',10,$Iht05_sequencial,true,'text',3,"");
            db_input('ht05_habitprograma',10,'',true,'hidden',3);
					?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tht05_idbql?>">
		      <?
		        db_ancora(@$Lht05_idbql,"js_pesquisaht05_idbql(true);",$db_opcao);
		      ?>
		    </td>
		    <td> 
					<?
		  			db_input('ht05_idbql',10,$Iht05_idbql,true,'text',$db_opcao," onchange='js_pesquisaht05_idbql(false);'");
			  		db_input('j34_setor',40,$Ij34_setor,true,'text',3,'');
		      ?>
		    </td>
		  </tr>
		  <tr>
		    <td colspan="2" align="center">
					<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
					<input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
		    </td>
		  </tr>
		</table>
  </fieldset>
	<table>
	  <tr>
	    <td valign="top"  align="center">  
		    <?
					$chavepri= array("ht05_sequencial"=>@$ht05_sequencial);
					
					$sSqlLote = $clhabitprogramalote->sql_query(null,"*",null,"ht05_habitprograma = ".@$ht05_habitprograma);
					
					$cliframe_alterar_excluir->chavepri      = $chavepri;
					$cliframe_alterar_excluir->sql           = $sSqlLote;
					$cliframe_alterar_excluir->campos        = "ht05_sequencial,j34_setor, j34_quadra, j34_lote";
					$cliframe_alterar_excluir->legenda       = "Lote Lançados";
					$cliframe_alterar_excluir->iframe_height = "160";
					$cliframe_alterar_excluir->iframe_width  = "700";
					$cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
		    ?>
	    </td>
	  </tr>
	</table>
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

function js_pesquisaht05_idbql(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_habitprogramalote','db_iframe_lote','func_lote.php?funcao_js=parent.js_mostralote1|j34_idbql|j34_setor','Pesquisa',true);
  }else{
     if(document.form1.ht05_idbql.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_habitprogramalote','db_iframe_lote','func_lote.php?pesquisa_chave='+document.form1.ht05_idbql.value+'&funcao_js=parent.js_mostralote','Pesquisa',false);
     }else{
       document.form1.j34_setor.value = ''; 
     }
  }
}

function js_mostralote(chave,erro){
  document.form1.j34_setor.value = chave; 
  if(erro==true){ 
    document.form1.ht05_idbql.focus(); 
    document.form1.ht05_idbql.value = ''; 
  }
}

function js_mostralote1(chave1,chave2){
  document.form1.ht05_idbql.value = chave1;
  document.form1.j34_setor.value = chave2;
  db_iframe_lote.hide();
}
</script>