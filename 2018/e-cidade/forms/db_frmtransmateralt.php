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
$clrotulo->label("m60_codmater");
$clrotulo->label("m60_descr");
$clrotulo->label("m63_codpcmater");
$clrotulo->label("pc01_descrmater");
if(isset($opcao) && $opcao=="alterar"){
  $db_opcao = 2;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
  $db_opcao = 3;
}else{  
  $db_opcao = 1;
} 
?>
<form name="form1" method="post" action="<?=basename($_SERVER['PHP_SELF'])?>" >
      <table border="0" cellspacing="0" cellpadding="0">
      <br><br>
	  <td nowrap title="<?=@$Tm60_codmater?>">
	     <?db_ancora(@$Lm60_codmater,"js_pesquisa_codmater(true);",3);?>
	  </td>
	  <td> 
      <?
      $result_descr=$clmatmater->sql_record($clmatmater->sql_query_file(@$m60_codmater,"m60_descr"));
      if ($clmatmater->numrows>0){
           db_fieldsmemory($result_descr,0);
      }
      db_input('m60_codmater',10,$Im60_codmater,true,'text',3,"onchange='js_pesquisa_codmater(false);'");
      db_input('m60_descr',40,$Im60_descr,true,'text',3,"");
      ?>
	  </td>
	</tr>
	  <tr>
    <td nowrap title="<?=@$Tm63_codpcmater?>">
       <?
       db_ancora(@$Lm63_codpcmater,"js_pesquisam63_codpcmater(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
if(isset($m63_codpcmater)&&trim($m63_codpcmater)!=""){
    $result_pcdescr = $clpcmater->sql_record($clpcmater->sql_query_file($m63_codpcmater,"pc01_descrmater")); 
    if($clpcmater->numrows > 0){
        db_fieldsmemory($result_pcdescr,0);
    }
}

db_input('m63_codpcmater',10,$Im63_codpcmater,true,'text',$db_opcao,"onchange='js_pesquisam63_codpcmater(false);'");
db_input('pc01_descrmater',40,'',true,'text',3)
?>
    </td>
  </tr>
	
	<tr>
	<td colspan=2 align=center>
	      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>   >
          <input name="lanc_var" type="button" id="db_opcao" value="Selecionar Materiais" <?=($db_botao==false?"disabled":"")?>   onclick='js_selecionamat();'>
        </td>
      </tr>
      </table>
      <table>
  <tr>
    <td valign="top"> 
    <?
     $chavepri= array("m63_codmatmater"=>@$m63_codmatmater,"m63_codpcmater"=>@$m63_codpcmater);
     $cliframe_alterar_excluir->chavepri=$chavepri;
     if (isset($m60_codmater)&&@$m60_codmater!=""){        
        $cliframe_alterar_excluir->sql = $cltransmater->sql_query(null,'*',null,"m63_codmatmater=$m60_codmater");
      }
      //$cliframe_alterar_excluir->sql_disabled = "";
      $cliframe_alterar_excluir->campos  ="m63_codpcmater,pc01_descrmater";
      $cliframe_alterar_excluir->legenda="Material Compras";
      $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
      $cliframe_alterar_excluir->textocabec ="darkblue";
      $cliframe_alterar_excluir->textocorpo ="black";
      $cliframe_alterar_excluir->fundocabec ="#aacccc";
      $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
      $cliframe_alterar_excluir->iframe_width ="710";
      $cliframe_alterar_excluir->iframe_height ="130";
      $lib=1;
      if ($db_opcao==3||$db_opcao==33){
           $lib=4;
      }
      // Alterado por Tarcisio
      if ($db_opcao==1||$db_opcao==11){
           $lib=3;
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
    js_OpenJanelaIframe('','db_iframe_mater','func_matmater.php?funcao_js=parent.js_mostra1|m60_codmater|m60_descr','Pesquisa',true);
  }else{
     if(document.form1.m41_codmatmater.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_mater','func_matmater.php?pesquisa_chave='+document.form1.m41_codmatmater.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
     }else{
        document.form1.pc01_descrmater.value = ""; 
     }
  }
}
function js_mostra(chave,erro){
  document.form1.m60_descr.value = chave; 
  if(erro==true){ 
    document.form1.m41_codmatmater.focus(); 
    document.form1.m41_codmatmater.value = ''; 
  }else{
    document.form1.m60_descr.value=chave;
    document.form1.submit();
  }
}
function js_mostra1(chave1,chave2){
  document.form1.m41_codmatmater.value = chave1;
  document.form1.m60_descr.value = chave2;
  db_iframe_mater.hide();
  document.form1.submit();
}
function js_pesquisam63_codpcmater(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_pcmater','func_pcmater.php?funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater<?=$db_opcao==1?"&opcao_bloq=3&opcao=f":"&opcao_bloq=1&opcao=i"?>','Pesquisa',true,0);
  }else{
     if(document.form1.m63_codpcmater.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_pcmater','func_pcmater.php?pesquisa_chave='+document.form1.m63_codpcmater.value+'&funcao_js=parent.js_mostramater<?=$db_opcao==1?"&opcao_bloq=3&opcao=f":"&opcao_bloq=1&opcao=i"?>','Pesquisa',false);
     }else{
        document.form1.pc01_descrmater.value = ""; 
     }
  }
}
function js_mostramater(chave,erro){
  document.form1.pc01_descrmater.value = chave; 
  if(erro==true){ 
    document.form1.m63_codpcmater.focus(); 
    document.form1.m63_codpcmater.value = ''; 
  }
}
function js_mostrapcmater1(chave1,chave2){
  document.form1.m63_codpcmater.value = chave1;
  document.form1.pc01_descrmater.value = chave2;
  db_iframe_pcmater.hide();
}
function js_selecionamat(){
  js_OpenJanelaIframe('','db_iframe_selmat','mat4_selmatcom001.php?m60_codmater=<?=@$m60_codmater?>','Seleciona Materiais do Compras',true,0);	
}

</script>