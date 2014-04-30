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

//MODULO: issqn
require_once("dbforms/db_classesgenericas.php");
$cliframe_seleciona = new cl_iframe_seleciona;

$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("y50_codauto");
$clrotulo->label("q07_databx");
$clrotulo->label("y114_processo");
$clrotulo->label("p58_requer");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td align="center"> 
<fieldset>
<legend><b>Baixa de Auto Infração</b></legend>
<table border="0">
  <tr>   
    <td title="<?=$Ty50_codauto?>" >
    <?
     db_ancora($Ly50_codauto,' js_inscr(true); ',1);
    ?>
    </td>    
    <td title="<?=$Ty50_codauto?>" colspan="4">
    <?
     db_input('y50_codauto',5,$Iy50_codauto,true,'text',1,"onchange='js_inscr(false)'");
     db_input('z01_nome',50,0,true,'text',3);

     if (isset($y50_codauto)&&$y50_codauto!="" ){
	  $result_baixa=$clautotipo->sql_record($clautotipo->sql_query_baixa(null,"*",null,"y59_codauto=$y50_codauto "));
	  if ($clautotipo->numrows!=0){
	    db_fieldsmemory($result_baixa,0);
          }
    }
    ?>
    </td>
  </tr>
    <tr>
      <td nowrap title="<?=@$Ty114_processo?>">
	 <?=@$Ly114_processo?>
      </td>
      <td colspan=3> 
  <?
  db_input('y114_processo',10,$Iy114_processo,true,'text',3," onchange='js_pesquisay114_processo(false);'")
  ?>
  <?db_input('p58_requer',40,$Ip58_requer,true,'text',3,'')
  
  ?>
      <td>
    </tr>
    <td nowrap title="<?=@$Tq07_databx?>" align='left'colspan=3 ><?=@$Lq07_databx?>
    
<?
if (isset($y87_dtbaixa)&&$y87_dtbaixa!=""){
    $data=split('-',$y87_dtbaixa);
    $q07_databx_ano=$data[0];
    $q07_databx_mes=$data[1];
    $q07_databx_dia=$data[2];
}

db_inputdata('q07_databx',@$q07_databx_dia,@$q07_databx_mes,@$q07_databx_ano,true,'text',3)
?>
    </td>
  </tr>
    <?
     if (isset($y50_codauto)&&$y50_codauto!="" ){
       ?>
  <tr>
    <td colspan="3" align="center">
       <input name="cancelabaixa" type="submit" onclick="js_gera_chaves();" id="db_opcao" value="Cancelar Baixa" 
              <?=($db_botao==false?"disabled":"")?> >
    </td> 
  </tr>
</table>
</td>
</tr>
<tr>
<td>  
  <tr>   
    <td align="center" colspan="2"> 
    <?
	  $cliframe_seleciona->campos  = "y59_codigo,y59_codtipo,y29_descr,y29_descr_obs";
	  $cliframe_seleciona->legenda="PROCEDÊNCIAS";
	  $cliframe_seleciona->sql=$clautotipo->sql_query_baixa(null,"*",null,"y59_codauto=$y50_codauto and y87_dtbaixa is not null");
	  $cliframe_seleciona->textocabec ="darkblue";
	  $cliframe_seleciona->textocorpo ="black";
	  $cliframe_seleciona->fundocabec ="#aacccc";
	  $cliframe_seleciona->fundocorpo ="#ccddcc";
	  $cliframe_seleciona->iframe_height ="250";
	  $cliframe_seleciona->iframe_width ="700";
	  $cliframe_seleciona->iframe_nome ="autotipo";
	  $cliframe_seleciona->chaves ="y59_codigo";
    $cliframe_seleciona->dbscript ="";
	  $cliframe_seleciona->marcador =true;
	  $cliframe_seleciona->iframe_seleciona(@$db_opcao);
     }else{ ?>
  <tr>
    <td colspan="3" align="center">
       <input name="cancelabaixa" type="submit" id="db_opcao" value="Cancelar Baixa" disabled <?=($db_botao==false?"disabled":"")?> >
    </td> 
  </tr>
</table>
<?}?>
</fieldset>						      
    </td>
  </tr>
  </table>
</form>
<script>
function js_inscr(mostra){
  var inscr=document.form1.y50_codauto.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_inscr','func_autoalt.php?funcao_js=parent.js_mostrainscr|dl_auto|z01_nome','Pesquisa',true);
  }else{
    if(inscr!=""){
      js_OpenJanelaIframe('top.corpo','db_iframe_inscr','func_autoalt.php?pesquisa_chave='+inscr+'&funcao_js=parent.js_mostrainscr1','Pesquisa',false);
    }else{
      document.form1.z01_nome.value="";
      document.form1.submit();  
    }
  }
}
function js_mostrainscr(chave1,chave2){
  document.form1.y50_codauto.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_inscr.hide();
  document.form1.submit(); 
}
function js_mostrainscr1(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.y50_codauto.focus(); 
    document.form1.y50_codauto.value = ''; 
  }else{
    document.form1.submit();
  }
}
function js_pesquisay114_processo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_processo','func_protprocesso.php?funcao_js=parent.js_mostraprocesso1|p58_codproc|p58_requer','Pesquisa',true);
  }else{
     if(document.form1.y114_processo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_processo','func_protprocesso.php?pesquisa_chave='+document.form1.y114_processo.value+'&funcao_js=parent.js_mostraprocesso','Pesquisa',false);
     }else{
       document.form1.y114_processo.value = ''; 
     }
  }
}
function js_mostraprocesso(chave,chave1,erro){
  document.form1.p58_requer.value = chave1; 
  if(erro==true){ 
    document.form1.y114_processo.focus(); 
    document.form1.y114_processo.value = ''; 
  }
}
function js_mostraprocesso1(chave1,chave2){
  document.form1.y114_processo.value = chave1;
  document.form1.p58_requer.value = chave2;
  db_iframe_processo.hide();
}
</script>