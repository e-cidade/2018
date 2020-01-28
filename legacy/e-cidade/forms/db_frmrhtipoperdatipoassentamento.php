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
require_once("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo                 = new rotulocampo;
$clrhtipoperdatipoassentamento->rotulo->label();
$clrotulo->label("h70_descricao");
$clrotulo->label("h12_assent");

if (isset($db_opcaoal)) {
	
  $db_opcao = 33;
  $db_botao = false;
}else if (isset($opcao) && $opcao == "alterar") {
	
    $db_botao=true;
    $db_opcao = 2;
}else if (isset($opcao) && $opcao == "excluir") {
	
    $db_opcao = 3;
    $db_botao=true;
} else {
	  
    $db_opcao = 1;
    $db_botao = true;
    if (isset($novo) || isset($alterar) || isset($excluir) || (isset($incluir) && $sqlerro == false)) {
     
     $h71_sequencial       = "";	
     $h71_tipoassentamento = "";
     $h12_descr            = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>

  <fieldset>
    <legend>
      <strong>
        Cadastro de Vínculos de Assentamentos e Tipos de Perda
      </strong>
    </legend>
	  <table border="0" align="left" style="margin-left: 25px;">
		  <tr>
		    <td nowrap title="<?=@$Th71_sequencial?>">
		       <?=@$Lh71_sequencial?>
		    </td>
		    <td> 
				<?
				  db_input('h71_sequencial',10,$Ih71_sequencial,true,'text',3,"")
				?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Th71_rhtipoperda?>">
		       <?
		       //db_ancora(@$Lh71_rhtipoperda,"js_pesquisah71_rhtipoperda(true);",$db_opcao);
		       echo @$Lh71_rhtipoperda;
		       ?>
		    </td>
		    <td> 
						<?
						  db_input('h71_rhtipoperda',10,$Ih71_rhtipoperda,true,'text', 3," onchange='js_pesquisah71_rhtipoperda(false);'")
						?>
		       <?
		          db_input('h70_descricao',60,$Ih70_descricao,true,'text',3,'')
		       ?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Th71_tipoassentamento?>">
		       <?
		         db_ancora(@$Lh71_tipoassentamento,"js_pesquisah71_tipoassentamento(true);",$db_opcao);
		       ?>
		    </td>
		    <td> 
						<?
						  db_input('h71_tipoassentamento',10,$Ih71_tipoassentamento,true,'text',$db_opcao," onchange='js_pesquisah71_tipoassentamento(false);'")
						?>
		       <?
		          db_input('h12_descr',60,"Descrição",true,'text',3,'')
		       ?>
		    </td>
		  </tr>
		  
      </tr>
        <td colspan="2" align="center">
         &nbsp;
        </td>
      </tr>		  
		  </tr>
		    <td colspan="2" align="center">
	      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
	      <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
		    </td>
		  </tr>
	  </table>
  
  
  
 <table style="margin-top: 10px;">
  <tr>
    <td valign="top"  align="center">  
    <?
    
			 $chavepri = array("h71_sequencial"=>@$h71_sequencial);
			 $cliframe_alterar_excluir->chavepri      = $chavepri;
			 $cliframe_alterar_excluir->sql           = $clrhtipoperdatipoassentamento->sql_query(null,"*", null, "h71_rhtipoperda = {$h71_rhtipoperda}");
			 $cliframe_alterar_excluir->campos        = "h71_sequencial, h12_descr";
			 $cliframe_alterar_excluir->legenda       = "ITENS LANÇADOS";
			 $cliframe_alterar_excluir->iframe_height = "160";
			 $cliframe_alterar_excluir->iframe_width  = "700";
			 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
 
  </fieldset>  
 
</center>

</form>
<script>
function js_cancelar(){

  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.getElementById('h71_sequencial').value = '';
  document.form1.submit();
}
function js_pesquisah71_rhtipoperda(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_rhtipoperdatipoassentamento','db_iframe_rhtipoperda','func_rhtipoperda.php?funcao_js=parent.js_mostrarhtipoperda1|h70_sequencial|h70_descricao','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.h71_rhtipoperda.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_rhtipoperdatipoassentamento','db_iframe_rhtipoperda','func_rhtipoperda.php?pesquisa_chave='+document.form1.h71_rhtipoperda.value+'&funcao_js=parent.js_mostrarhtipoperda','Pesquisa',false);
     }else{
       document.form1.h70_descricao.value = ''; 
     }
  }
}
function js_mostrarhtipoperda(chave,erro){
  document.form1.h70_descricao.value = chave; 
  if(erro==true){ 
    document.form1.h71_rhtipoperda.focus(); 
    document.form1.h71_rhtipoperda.value = ''; 
  }
}
function js_mostrarhtipoperda1(chave1,chave2){
  document.form1.h71_rhtipoperda.value = chave1;
  document.form1.h70_descricao.value = chave2;
  db_iframe_rhtipoperda.hide();
}
function js_pesquisah71_tipoassentamento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_rhtipoperdatipoassentamento','db_iframe_tipoasse','func_tipoasse.php?funcao_js=parent.js_mostratipoasse1|h12_codigo|h12_descr','Pesquisa', true);
  }else{
     if(document.form1.h71_tipoassentamento.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_rhtipoperdatipoassentamento','db_iframe_tipoasse','func_tipoasse.php?pesquisa_chave='+document.form1.h71_tipoassentamento.value+'&funcao_js=parent.js_mostratipoasse','Pesquisa',false);
     }else{
       document.form1.h12_descr.value = ''; 
     }
  }
}
function js_mostratipoasse(chave, erro, chave2){

  document.form1.h12_descr.value = chave2; 
  if(erro == true){ 
    document.form1.h71_tipoassentamento.focus(); 
    document.form1.h71_tipoassentamento.value = ''; 
    document.form1.h12_descr.value = chave;
  }
}
function js_mostratipoasse1(chave1,chave2){
  document.form1.h71_tipoassentamento.value = chave1;
  document.form1.h12_descr.value = chave2;
  db_iframe_tipoasse.hide();
}
</script>
<?PHP
if (isset($h71_rhtipoperda)) {
	
	echo "<script>";
  echo "  js_pesquisah71_rhtipoperda(false)";
  echo "</script>";
}
?>