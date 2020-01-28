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

$clsolicitempcmater->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc01_descrmater");
$clrotulo->label("o56_elemento");
$clrotulo->label("pc10_numero");
$db_opcao = 1;
$queryele = "";
if(isset($pc16_solicitem) && trim($pc16_solicitem)!=""){
  $result_pcdotac = $clpcdotac->sql_record($clpcdotac->sql_query_descrdot($pc16_solicitem,null,null,"o56_elemento"));
  $numrows_pcdotac = $clpcdotac->numrows;
  $arr_pcdotac = Array();
  for($i=0;$i<$numrows_pcdotac;$i++){
  	db_fieldsmemory($result_pcdotac,$i);
  	if(!in_array($o56_elemento,$arr_pcdotac)){
  	  array_push($arr_pcdotac,$o56_elemento);
  	  $elemento = $o56_elemento;
  	}
  }
  if(sizeof($arr_pcdotac)>1){
  	$msg_alert = "Usuário:\\n\\nItem com elementos diferentes nas dotações. \\nNão poderá ser liberado.\\n\\nAdministrador:";
  	$tranca   = true;
  	$db_opcao = 3;
  }else{
    $queryele = "&o56_elemento=$o56_elemento";  	
  }
}
?>
<form name="form1">
<center>
<table height="20" border="0">
  <tr>
    <td nowrap title="<?=@$Tpc16_solicitem?>">
       <?=@$Lpc16_solicitem?>
    </td>
    <td> 
    <?
      db_input('pc16_solicitem',8,$Ipc16_solicitem,true,'text',3);
      db_input('pc10_numero',8,$Ipc10_numero,true,'hidden',3);
      if(isset($libera)){
      	db_input('libera',8,0,true,'hidden',3);
      }
    ?>
    </td>
  </tr>  
  <tr>
    <td nowrap title="<?=@$Tpc16_codmater?>">
	<?db_ancora(@$Lpc16_codmater,"js_pesquisapc16_codmater(true);",$db_opcao);?>
    </td>
    <td> 
    <?
      db_input('pc16_codmater',8,$Ipc16_codmater,true,'text',$db_opcao," onchange='js_pesquisapc16_codmater(false);'");
      db_input('pc01_descrmater',50,$Ipc01_descrmater,true,'text',3,'');
    ?>
    </td>
  </tr>
  <?
  if($db_opcao==1){
    $arr_elementos = Array();
  	$where_elemento = " substr(o56_elemento,1,7)='".substr($elemento,0,7)."' and substr(o56_elemento,7,6)::int > 0 ";
  	// echo($clorcelemento->sql_query_file(null,null," distinct o56_codele,o56_descr,o56_elemento","o56_descr","o56_anousu = ".db_getsession("DB_anousu")." and  ".$where_elemento));
  	
  	//////// Select que estava executando antes
  	// $result_orcelemento = $clorcelemento->sql_record($clorcelemento->sql_query_file(null,null," distinct o56_codele,o56_descr,o56_elemento","o56_descr","o56_anousu = ".db_getsession("DB_anousu")." and  o56_elemento like '".$elemento."%' "));
  	///////////////////////////////////////////

  	$result_orcelemento = $clorcelemento->sql_record($clorcelemento->sql_query_file(null,null," distinct o56_codele,o56_descr,o56_elemento","o56_descr","o56_anousu = ".db_getsession("DB_anousu")." and  ".$where_elemento)); 
	  $numrows_orcelemento = $clorcelemento->numrows;
	  for($i=0;$i<$numrows_orcelemento;$i++){
	    db_fieldsmemory($result_orcelemento,$i);	  
	    $arr_elementos[$o56_codele] = $o56_codele." - ".db_formatar($o56_elemento,"elemento")." - ".$o56_descr;
	  }
  ?>
  <tr>
    <td nowrap title="<?=@$To56_elemento?>">
       <?=@$Lo56_elemento?>
    </td>
    <td> 
    <?
       db_select("o56_codele",$arr_elementos,$Io56_elemento,$db_opcao,"onchange='js_limparmater();'");
    ?>
    </td>
  </tr>    
  <?
  }
  ?>  
  <tr>  
    <td align='center' colspan='2'>
      <input name="incluir" type="submit" id="db_opcao" value="Incluir" 
      <? if(isset($tranca)){
           echo "disabled";
         }
      ?> 
      >
      <input name="voltar" type="button" id="voltar" value="Fechar" onClick="top.corpo.db_iframe_selmater.hide();">
    </td>
  </tr>  
</table>
</center>
</form>
<script>
function js_limparmater(){
  document.form1.pc16_codmater.value = "";
  document.form1.pc01_descrmater.value = "";
}
function js_pesquisapc16_codmater(mostra){
  qry = "&o56_codele="+document.form1.o56_codele.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pcmater','func_pcmater.php?funcao_js=top.corpo.db_iframe_selmater.jan.js_mostrapcmater1|pc01_codmater|pc01_descrmater<?=$db_opcao==1?"&opcao_bloq=3&opcao=f":"&opcao_bloq=1&opcao=i"?>'+qry,'Pesquisa',true);
  }else{
    if(document.form1.pc16_codmater.value != ''){ 
       js_OpenJanelaIframe('top.corpo','db_iframe_pcmater','func_pcmater.php?pesquisa_chave='+document.form1.pc16_codmater.value+'&funcao_js=top.corpo.db_iframe_selmater.jan.js_mostrapcmater<?=$db_opcao==1?"&opcao_bloq=3&opcao=f":"&opcao_bloq=1&opcao=i"?>'+qry,'Pesquisa',false);
    }else{
      document.form1.pc01_descrmater.value = ''; 
    }
  }
}
function js_mostrapcmater(chave,erro){
  document.form1.pc01_descrmater.value = chave; 
  if(erro==true){ 
    document.form1.pc16_codmater.focus(); 
    document.form1.pc16_codmater.value = ''; 
  }  
}
function js_mostrapcmater1(chave1,chave2){
  document.form1.pc16_codmater.value = chave1;  
  document.form1.pc01_descrmater.value = chave2;
  top.corpo.db_iframe_pcmater.hide();
}
</script>