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
$clcadvencdesc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k00_descr");
$clrotulo->label("k01_descr");
$clrotulo->label("codbco");
$clrotulo->label("nomebco");
$clrotulo->label("k15_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq92_codigo?>">
       <?=@$Lq92_codigo?>
<?
db_input('tavainclu',5,10,true,'hidden',1);
db_input('duplica',5,"",true,'hidden',3);
db_input('cod_duplic',5,"",true,'hidden',3);

// este campo sera preenchido apenas quando o sistema incluir um registro e for para alteração
?>
    </td>
    <td> 
<?
db_input('q92_codigo',10,$Iq92_codigo,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq92_descr?>">
       <?=@$Lq92_descr?>
    </td>
    <td> 
<?
db_input('q92_descr',54,$Iq92_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_codigo?>">
       <?
       db_ancora(@$Lcodbco,"js_banco(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k15_codigo',10,$Ik15_codigo,true,'text',$db_opcao," onchange='js_banco(false);'","","E6E4F1")
?>
       <?
db_input('nomebco',40,$Inomebco,true,'text',3,'',"","E6E4F1")
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq92_tipo?>">
       <?
       db_ancora(@$Lq92_tipo,"js_pesquisaq92_tipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q92_tipo',10,$Iq92_tipo,true,'text',$db_opcao," onchange='js_pesquisaq92_tipo(false);'")
?>
       <?
db_input('k00_descr',40,$Ik00_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq92_hist?>">
       <?
       db_ancora(@$Lq92_hist,"js_pesquisaq92_hist(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q92_hist',10,$Iq92_hist,true,'text',$db_opcao," onchange='js_pesquisaq92_hist(false);'")
?>
       <?
db_input('k01_descr',40,$Ik01_descr,true,'text',3,'')
       ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tq92_vlrminimo?>">
       <?=$Lq92_vlrminimo?>
    </td>
    <td> 
      <?
        db_input('q92_vlrminimo',10,$Iq92_vlrminimo,true,'text',$db_opcao,'');
      ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tq92_formacalcparcvenc?>">
       <?=$Lq92_formacalcparcvenc?>
    </td>
    <td> 
      <?
        $xw = array(
                     '1'=>"Calcula todas parcelas vencidas",
                     '2'=>"Calcula somente as escolhidas",
                     '3'=>"Não calcula parcelas vencidas"
                   );
        db_select('q92_formacalcparcvenc',$xw,true,$db_opcao,"");
      ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tq92_diasvcto?>">
       <?
       db_ancora(@$Lq92_diasvcto,"",3);
       ?>
		
    </td> 
    <td> 
			<?
			db_input('q92_diasvcto',10,$Iq92_diasvcto,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<?
  if(!(isset($tavainclu) && $tavainclu==true)){
?>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >

<?
  }
if ($db_opcao==1){
?>
<input name="dupli_venc" type="button" id="dupli_venc" value="Duplica Vencimento" onclick="js_pesquisavenc();" >
<?}?>
</form>
<script>
function js_banco(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_cadvencdesc','db_iframe_banco','func_cadban.php?funcao_js=parent.js_mostrabanco1|k15_codigo|z01_nome','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_cadvencdesc','db_iframe_banco','func_cadban.php?pesquisa_chave='+document.form1.k15_codigo.value+'&funcao_js=parent.js_mostrabanco','Pesquisa',false,0);
  }
}
function js_mostrabanco(chave,erro){
  document.form1.nomebco.value = chave;

  if(erro==true){ 
    document.form1.k15_codigo.focus(); 
    document.form1.k15_codigo.value = ''; 
  }
}
function js_mostrabanco1(chave1,chave2){
  document.form1.k15_codigo.value = chave1;
  document.form1.nomebco.value = chave2;
  db_iframe_banco.hide();
}
function js_pesquisaq92_tipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_cadvencdesc','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipo1|k00_tipo|k00_descr','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_cadvencdesc','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.q92_tipo.value+'&funcao_js=parent.js_mostraarretipo','Pesquisa',false,0);
  }
}
function js_mostraarretipo(chave,erro){
  document.form1.k00_descr.value = chave; 
  if(erro==true){ 
    document.form1.q92_tipo.focus(); 
    document.form1.q92_tipo.value = ''; 
  }
}
function js_mostraarretipo1(chave1,chave2){
  document.form1.q92_tipo.value = chave1;
  document.form1.k00_descr.value = chave2;
  db_iframe_arretipo.hide();
}
function js_pesquisaq92_hist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_cadvencdesc','db_iframe_histcalc','func_histcalc.php?funcao_js=parent.js_mostrahistcalc1|k01_codigo|k01_descr','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_cadvencdesc','db_iframe_histcalc','func_histcalc.php?pesquisa_chave='+document.form1.q92_hist.value+'&funcao_js=parent.js_mostrahistcalc','Pesquisa',false,0);
  }
}
function js_mostrahistcalc(chave,erro){
  document.form1.k01_descr.value = chave; 
  if(erro==true){ 
    document.form1.q92_hist.focus(); 
    document.form1.q92_hist.value = ''; 
  }
}
function js_mostrahistcalc1(chave1,chave2){
  document.form1.q92_hist.value = chave1;
  document.form1.k01_descr.value = chave2;
  db_iframe_histcalc.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_cadvencdesc','db_iframe_cadvencdesc','func_cadvencdesc.php?funcao_js=parent.js_preenchepesquisa|q92_codigo','Pesquisa',true,0);
}
function js_preenchepesquisa(chave){
  db_iframe_cadvencdesc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}
function js_pesquisavenc(){
	js_OpenJanelaIframe('top.corpo.iframe_cadvencdesc','db_iframe_cadvencdesc','func_cadvencdesc.php?funcao_js=parent.js_duplic|q92_codigo|q92_descr|q92_vlrminimo','Pesquisa',true,0);
}
function js_duplic(cod,descr,valor){
    db_iframe_cadvencdesc.hide();
  	if(confirm('Duplicar o Vencimento '+cod+' - '+descr+' ?')){
             document.form1.duplica.value='true';
             document.form1.cod_duplic.value=cod;
             document.form1.q92_vlrminimo.value=valor;
             document.form1.submit();
  	}
}
</script>