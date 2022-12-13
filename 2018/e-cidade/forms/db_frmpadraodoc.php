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
$clrotulo = new rotulocampo;
$clrotulo->label("db61_codparag");
$clrotulo->label("db61_descr");
if(isset($opcao) && $opcao=="alterar"){
  $db_opcao = 1;
  echo "<script>
          js_OpenJanelaIframe('','db_iframe_newparag','con4_docpadrao006.php?chavepesquisa=$db61_codparag','Altera Paragrafo',true,0);
        </script>";
        $db61_codparag="";
        $db61_descr="";
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
  $db_opcao = 3;
}else{  
  $db_opcao = 1;
} 
?>
<form name="form1" method="post" action="<?=basename($_SERVER['PHP_SELF'])?>" >
      <table border="0" cellspacing="0" cellpadding="0">
	
	<tr>
	  <td nowrap title="<?=@$Tdb61_codparag?>">
	     <?db_ancora(@$Ldb61_codparag,"js_pesquisa_codmater(true);",$db_opcao);?>
	  </td>
	  <td> 
      <?
    
      db_input('db61_codparag',10,$Idb61_codparag,true,'text',$db_opcao,"onchange='js_pesquisa_codmater(false);'");
      db_input('db61_descr',40,$Idb61_descr,true,'text',3,"");
      db_input('db60_coddoc',40,"",true,'hidden',3,"");
      ?>
	  </td>
	</tr>
	
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td colspan=2 align=center>
	<?
        if(!isset($opcao) && isset($db_opcao) && $db_opcao==3){
           $db_botao=false;	  
	    }
	?>
	  <input name="paragrafo_sel" type="button" id="db_opcao" value="Seleciona Paragrafos"   <?=($db_botao==false?"disabled":"")?> onclick='js_selparag();' >
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?'Incluir':($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
      <input name="paragrafo_novo" type="button" id="db_opcao" value="Paragrafo Novo"   <?=($db_botao==false?"disabled":"")?> onclick='js_newparag();' >
      <input name="ordenar" type="button" id="db_opcao" value="Ordenar"   <?=($db_botao==false?"disabled":"")?> onclick='js_ordena();' >
            <input name="emite" type="button" id="db_opcao" value="Visualizar"   <?=($db_botao==false?"disabled":"")?> onclick='js_emite();' >
        </td>
      </tr>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
      </table>
      <table>
  <tr>
    <td valign="top"> 
    <?
     $chavepri= array("db61_codparag"=>@$db61_codparag,"db61_descr"=>@$db61_descr);
     $cliframe_alterar_excluir->chavepri=$chavepri;
     if (isset($db60_coddoc)&&@$db60_coddoc!=""){
        $cliframe_alterar_excluir->sql = $cldb_docparagpadrao->sql_query(null,null,'*',"db62_ordem","db62_coddoc=$db60_coddoc");
      }
      $cliframe_alterar_excluir->campos  ="db61_codparag,db61_descr,db61_texto,db62_ordem";
      $cliframe_alterar_excluir->legenda="Paragrafos";
      $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
      $cliframe_alterar_excluir->textocabec ="darkblue";
      $cliframe_alterar_excluir->textocorpo ="black";
      $cliframe_alterar_excluir->fundocabec ="#aacccc";
      $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
      $cliframe_alterar_excluir->iframe_width ="900";
      $cliframe_alterar_excluir->iframe_height ="300";
      $lib=1;
      if ($db_opcao==3||$db_opcao==33){
	     $lib=4;
      }
      $cliframe_alterar_excluir->opcoes = @$lib;
      $cliframe_alterar_excluir->iframe_alterar_excluir(@$db_opcao);   
      db_input('db_opcao',10,'',true,'hidden',3);
    ?>
   </td>
 </tr>
 </table>
</form>
<script>
function js_pesquisa_codmater(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_mater','func_db_paragrafopadrao.php?funcao_js=parent.js_mostra1|db61_codparag|db61_descr','Pesquisa',true);
  }else{
     if(document.form1.db61_codparag.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_mater','func_db_paragrafopadrao.php?pesquisa_chave='+document.form1.db61_codparag.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
     }else{
        document.form1.pc01_descrmater.value = ""; 
     }
  }
}
function js_mostra(chave,erro){
  document.form1.db61_descr.value = chave; 
  if(erro==true){ 
    document.form1.db61_codparag.focus(); 
    document.form1.db61_codparag.value = ''; 
  }
}
function js_mostra1(chave1,chave2){
  document.form1.db61_codparag.value = chave1;
  document.form1.db61_descr.value = chave2;
  db_iframe_mater.hide();
}

function js_newparag(){
  js_OpenJanelaIframe('','db_iframe_newparag','con4_docpadrao005.php?funcao_retorno=parent.js_inclui','Inclui Paragrafo',true,0);	
}
function js_selparag(){
  js_OpenJanelaIframe('','db_iframe_selparag','con4_docpadrao007.php?db60_coddoc=<?=@$db60_coddoc?>','Seleciona Paragrafos',true,0);	
}
function js_ordena(){
  js_OpenJanelaIframe('','db_iframe_ordena','con4_docpadrao009.php?chavepesquisa=<?=@$db60_coddoc?>','Ordena',true,0);	
}
function js_emite(){
	 jan = window.open('con4_docpadrao010.php?db60_coddoc=<?=@$db60_coddoc?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
     jan.moveTo(0,0);
}
function js_inclui(cod){
	document.form1.db61_codparag.value=cod;
	<?
	if ($db_opcao==1){
	?>
	document.form1.incluir.click();
	<?
    }
	?>
}	
	
</script>