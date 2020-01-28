<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: notificacoes

$clparnotificacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db03_descr");
$clrotulo->label("nomeinst");
 
?>

<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Parâmetros do Módulo Notificações</legend>
    <table class="form-container">
      <tr>
        <td title="<?=@$Tk102_anousu?>">
          <?=@$Lk102_anousu?>
        </td>
        <td> 
	      <?
	  	    $k102_anousu = db_getsession('DB_anousu');
	  	    db_input('k102_anousu',10,$Ik102_anousu,true,'text',3,"");
	  	  ?>
        </td>
      </tr>
      <tr>
        <td title="<?=@$Tk102_docnotpadrao?>">
          <?
            db_ancora(@$Lk102_docnotpadrao,"js_pesquisak102_docnotpadrao(true);",$db_opcao);
          ?>
        </td>
        <td> 
	  	  <?
	  	  	db_input('k102_docnotpadrao',10,$Ik102_docnotpadrao,true,'text',$db_opcao," onchange='js_pesquisak102_docnotpadrao(false);'");
	  	  	db_input('db03_descr',40,$Idb03_descr,true,'text',3,'');
	  	  	db_input('k102_instit',10,$Ik102_instit,true,'hidden',$db_opcao,"");
          ?>
        </td>
    	</tr>
      <tr>
        <td title="<?=@$Tk102_tipoemissao?>">
          <?=@$Lk102_tipoemissao?>
        </td>
        <td> 
	      <?
	  	    $aTipoEmissao = array( 1=>"Conforme Lista Gerada",
	  	    					   2=>"Corrigir Históricos Conforme Situação Atual",
	  	    					   3=>"Não Emitir Notificações com Diferença" );
	  	    db_select("k102_tipoemissao",$aTipoEmissao,true,$db_opcao,"");
	  	  ?>
        </td>
      </tr>  	
    </table>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</form>
<script>

function js_pesquisak102_docnotpadrao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_documento','func_db_documento.php?funcao_js=parent.js_mostradb_documento1|db03_docum|db03_descr','Pesquisa',true);
  }else{
     if(document.form1.k102_docnotpadrao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_documento','func_db_documento.php?pesquisa_chave='+document.form1.k102_docnotpadrao.value+'&funcao_js=parent.js_mostradb_documento','Pesquisa',false);
     }else{
       document.form1.db03_descr.value = ''; 
     }
  }
}

function js_mostradb_documento(chave,erro){
  document.form1.db03_descr.value = chave; 
  if(erro==true){ 
    document.form1.k102_docnotpadrao.focus(); 
    document.form1.k102_docnotpadrao.value = ''; 
  }
}

function js_mostradb_documento1(chave1,chave2){
  document.form1.k102_docnotpadrao.value = chave1;
  document.form1.db03_descr.value = chave2;
  db_iframe_db_documento.hide();
}

</script>
<script>

$("k102_anousu").addClassName("field-size2");
$("k102_docnotpadrao").addClassName("field-size2");
$("db03_descr").addClassName("field-size7");

</script>