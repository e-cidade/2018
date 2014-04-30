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

include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo                 = new rotulocampo;
$clrotulo->label("me35_c_nomealimento");
$clrotulo->label("me36_i_alimento");
$clrotulo->label("me36_i_matmater");
$db_botao1 = false;
if (isset($opcao) && $opcao == "alterar") {
	
  $db_opcao = 2;
  $db_botao1 = true;
  
} else if (isset($opcao) && $opcao == "excluir" || isset($db_opcao) && $db_opcao == 3) {
	
  $db_opcao = 3;
  $db_botao1 = true;
  
} else {
	  
  if(isset($alterar)){
  	
    $db_opcao = 2;
    $db_botao1 = true;
    
  } else {
    $db_opcao = 1;
  }
} 
?>
<form name="form1" method="post" action="<?=basename($_SERVER['PHP_SELF'])?>" >
<table border="0" cellspacing="0" cellpadding="0">
 <br><br>
 <tr>
 <td nowrap title="<?=@$Tme36_i_codigo?>"> 
 <b>Código:</b>
 </td>
   <td nowrap title="<?=@$Tme36_i_codigo?>">
    <?db_input('me36_i_codigo',10,@$Ime36_i_codigo,true,'text',3,"");?>
   </td>
  </tr>
  <tr>
   <td nowrap title="<?=@$Tme36_i_alimento?>">
    <?db_ancora(@$Lme36_i_alimento,"js_pesquisa_alimento(true);",3);?>
   </td>
   <td> 
    <?
     db_input('me36_i_alimento',10,@$Ime36_i_alimento,true,'text',3,"onchange='js_pesquisa_alimento(false);'");
     db_input('me35_c_nomealimento',40,@$Ime35_c_nomealimento,true,'text',3,"");
    ?>
   </td>
  </tr>
  <tr>
   <td nowrap title="<?=@$Tme36_i_matmater?>">
     <?db_ancora(@$Lme36_i_matmater,"js_pesquisam60_codmater(true);",$db_opcao);?>
   </td>
   <td> 
    <?
     if (isset($me36_i_matmater) && trim($me36_i_matmater) != "") {
       $result_pcdescr = $clmer_alimentomatmater->sql_record($clmer_alimentomatmater->sql_query_file($me36_i_matmater,
                                                                                                     "m60_descr"
                                                                                                    )
                                                            ); 
       if ($clmer_alimentomatmater->numrows > 0){
         db_fieldsmemory($result_pcdescr,0);
       }
     }
     db_input('m60_codmater',10,@$Im60_codmater,true,'text',$db_opcao,"onchange='js_pesquisam60_codmater(false);'");
     db_input('m60_descr',40,'',true,'text',3)  
    ?>
   </td>
  </tr>   
  <tr>
   <td colspan=2 align=center>
     <input name="<?=($db_opcao == 1?"incluir":($db_opcao == 2 || $db_opcao == 22?"alterar":"excluir"))?>" type="submit" 
            id="db_opcao" value="<?=($db_opcao == 1?"Incluir":($db_opcao == 2 || $db_opcao == 22?"Alterar":"Excluir"))?>" 
            <?=($db_botao == false?"disabled":"")?>   >
     <input name="lanc_var" type="button" id="db_opcao" 
            value="Selecionar Materiais" <?=($db_botao == false?"disabled":"")?>   
            onclick='js_selecionamat();'>
     <input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >            
   </td>
  </tr>
 </table>
 <table>
  <tr>
    <td valign="top"> 
    <?
     $chavepri                                = array("me36_i_codigo"=>@$me36_i_codigo,
                                                      "me36_i_alimento"=>@$me36_i_alimento, 
                                                      "me35_c_nomealimento"=>@$me35_c_nomealimento,
                                                      "m60_codmater"=>@$m60_codmater,
                                                      "m60_descr"=>@$m60_descr
                                                     );
     $cliframe_alterar_excluir->chavepri      = $chavepri;      
     $cliframe_alterar_excluir->sql           = $clmer_alimentomatmater->sql_query(null,
                                                                                   '*',
                                                                                   null,
                                                                                   "me36_i_alimento =". @$me36_i_alimento
                                                                                  );
     $cliframe_alterar_excluir->campos        = "me36_i_codigo,me36_i_matmater,m60_descr";
     $cliframe_alterar_excluir->legenda       = "Material";
     $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
     $cliframe_alterar_excluir->textocabec    = "darkblue";
     $cliframe_alterar_excluir->textocorpo    = "black";
     $cliframe_alterar_excluir->fundocabec    = "#aacccc";
     $cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
     $cliframe_alterar_excluir->iframe_width  = "710";
     $cliframe_alterar_excluir->iframe_height = "130";
     $lib                                     = 1;
     if ($db_opcao == 3 || $db_opcao == 33) {
       $lib = 4;
     }
     if ($db_opcao == 1 || $db_opcao == 11) {
       $lib = 3;
     }
     $cliframe_alterar_excluir->opcoes  = @$lib;
     $cliframe_alterar_excluir->iframe_alterar_excluir(@$db_opcao);   
     db_input('db_opcao',10,'',true,'hidden',3);
    ?>
   </td>
  </tr>
 </table>
</form>
<script>
function js_pesquisam60_codmater(mostra) {
	
  if (mostra == true) {
	  
	js_OpenJanelaIframe('','db_iframe_matmater','func_matmater.php?funcao_js=parent.js_mostramatmater1|m60_codmater|m60_descr',
			            'Pesquisa',true
			           );
    
  } else {
	  
	if (document.form1.m60_codmater.value != '') {
		 
	  js_OpenJanelaIframe('','db_iframe_matmater',
			              'func_matmater.php?pesquisa_chave='+document.form1.m60_codmater.value+
			              '&funcao_js=parent.js_mostramatmater','Pesquisa',false
			             );
      
	} else {
	  document.form1.m60_descr.value = ''; 
    }
    
  }
  
}

function js_mostramatmater(chave,erro) {
	
  document.form1.m60_descr.value = chave; 
  if (erro == true) {
	   
	document.form1.m60_codmater.focus(); 
	document.form1.m60_codmater.value = '';
	 
  }
  
}

function js_mostramatmater1(chave1,chave2) { 
	
  document.form1.m60_codmater.value = chave1;
  document.form1.m60_descr.value    = chave2;
  db_iframe_matmater.hide();
  
}

function js_selecionamat() {
	
  js_OpenJanelaIframe('','db_iframe_selmatmater','mer4_selmatmater001.php?m60_codmater=<?=@$m60_codmater?>&me36_i_alimento=<?=@$me36_i_alimento?>',
		              'Seleciona Materiais do Compras',true,0
		             );
    
}
</script>