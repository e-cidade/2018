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
$clrotulo->label("db02_idparag");
$clrotulo->label("db02_descr");
if(isset($opcao) && $opcao=="alterar"){
  $db_opcao = 1;
  echo "<script>
          js_OpenJanelaIframe('','db_iframe_newparag','con4_docparag006.php?chavepesquisa=$db02_idparag','Altera Paragrafo',true,0);
        </script>";
        $db02_idparag="";
        $db02_descr="";
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
  $db_opcao = 3;
}else{  
  $db_opcao = 1;
} 
?>
<form name="form1" method="post" action="<?=basename($_SERVER['PHP_SELF'])?>" >
      <table border="0" cellspacing="0" cellpadding="0">
	
	<tr>
	  <td nowrap title="<?=@$Tdb02_idparag?>">
	     <?db_ancora(@$Ldb02_idparag,"js_pesquisa_codmater(true);",$db_opcao);?>
	  </td>
	  <td> 
      <?
    
      db_input('db02_idparag',10,$Idb02_idparag,true,'text',$db_opcao,"onchange='js_pesquisa_codmater(false);'");
      db_input('db02_descr',40,$Idb02_descr,true,'text',3,"");
      db_input('db03_docum',40,"",true,'hidden',3,"");
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
     $chavepri= array("db02_idparag"=>@$db04_idparag,"db02_descr"=>@$db02_descr);
     $cliframe_alterar_excluir->chavepri=$chavepri;
     if (isset($db03_docum)&&@$db03_docum!=""){
        $cliframe_alterar_excluir->sql = $cldb_docparag->sql_query(null,null,'*',"db04_ordem","db04_docum=$db03_docum");
      }
      //$cliframe_alterar_excluir->sql_disabled = $clmatrequiitem->sql_query_atend(null,'*',null,"m41_codmatrequi=$m40_codigo and m43_codigo is not null");
      $cliframe_alterar_excluir->campos  ="db02_idparag,db02_descr,db02_texto,db04_ordem";
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
    js_OpenJanelaIframe('','db_iframe_mater','func_db_paragrafo.php?funcao_js=parent.js_mostra1|db02_idparag|db02_descr','Pesquisa',true);
  }else{
     if(document.form1.db02_idparag.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_mater','func_db_paragrafo.php?pesquisa_chave='+document.form1.db02_idparag.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
     }else{
        document.form1.pc01_descrmater.value = ""; 
     }
  }
}
function js_mostra(chave,erro){
  document.form1.db02_descr.value = chave; 
  if(erro==true){ 
    document.form1.db02_idparag.focus(); 
    document.form1.db02_idparag.value = ''; 
  }
}
function js_mostra1(chave1,chave2){
  document.form1.db02_idparag.value = chave1;
  document.form1.db02_descr.value = chave2;
  db_iframe_mater.hide();
}

function js_newparag(){
  js_OpenJanelaIframe('','db_iframe_newparag','con4_docparag005.php?funcao_retorno=parent.js_inclui','Inclui Paragrafo',true,0);	
}
function js_selparag(){
  js_OpenJanelaIframe('','db_iframe_selparag','con4_docparag007.php?db03_docum=<?=@$db03_docum?>','Seleciona Paragrafos',true,0);	
}
function js_ordena(){
  js_OpenJanelaIframe('','db_iframe_ordena','con4_docparag009.php?chavepesquisa=<?=@$db03_docum?>','Ordena',true,0);	
}
function js_emite(){
	 jan = window.open('con4_docparag010.php?db03_docum=<?=@$db03_docum?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
     jan.moveTo(0,0);
}
function js_inclui(cod){
	document.form1.db02_idparag.value=cod;
	<?
	if ($db_opcao==1){
	?>
	document.form1.incluir.click();
	<?
    }
	?>
}	
	
</script>