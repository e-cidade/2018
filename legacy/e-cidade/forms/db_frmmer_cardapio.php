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

//MODULO: merenda
$clmer_cardapio->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed15_i_codigo");
$clrotulo->label("me14_i_codigo");
$clrotulo->label("ed18_i_codigo");
$escola     = db_getsession("DB_coddepto");
$sql        = "select descrdepto from db_depart where coddepto=$escola";
$escolanome = pg_result(pg_query($sql),0,0);
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Tme01_i_codigo?>">
   <?=@$Lme01_i_codigo?>
  </td>
  <td>
   <?db_input('me01_i_codigo',10,$Ime01_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme01_c_nome?>">
   <?=@$Lme01_c_nome?>
  </td>
  <td>
   <?db_input('me01_c_nome',50,$Ime01_c_nome,true,'text',$db_opcao,"")?>
   <?=@$Lme01_f_versao?>
   <?db_input('me01_f_versao',5,$Ime01_f_versao,true,'text',3,"")?>
  </td>
 </tr>
  <tr>
  <td nowrap title="<?=@$Tme01_i_tipocardapio?>">
   <?db_ancora(@$Lme01_i_tipocardapio,"js_pesquisame01_i_tipocardapio(true);",($db_opcao!=1?3:1));?>
  </td>
  <td>
   <?db_input('me01_i_tipocardapio',10,$Ime01_i_tipocardapio,true,'text',($db_opcao!=1?3:1),
               "onchange='js_pesquisame01_i_tipocardapio(false);'"
             )
   ?>
   <?db_input('me27_c_nome',40,@$Ime27_c_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap>
   <b>Total de Alunos:</b>
  </td>
  <td>
   <?db_input('me01_i_total',10,@$Ime01_i_total,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme01_i_percapita?>">
   <?=@$Lme01_i_percapita?>
  </td>
  <td>
   <?db_input('me01_i_percapita',10,$Ime01_i_percapita,true,'text',$db_opcao,"onChange=\"js_alunoatend(this.value)\"")?>
  </td>
 </tr>
 <tr>
  <td colspan="2">
   <fieldset style="width:75%"><legend><b>Selecione o Tipo de Refeição:</b> </legend>
    <table border="0">
     <tr>
      <td rowspan="2">
       <?
       if (!isset($chavepesquisa)) {
         $chavepesquisa = 0;
       }
       $result1 = $clmer_tprefeicao->sql_record(
                                                $clmer_tprefeicao->sql_query("",
                                                                             "me03_i_codigo,me03_c_tipo","me03_i_orden",
                                                                             "  not exists(select * from 
                                                                                              mer_cardapiotipo 
                                                                                     where 
                                                                                     me21_i_tprefeicao = me03_i_codigo 
                                                                                  AND me21_i_cardapio = $chavepesquisa)"
                                                                            )
                                               );
       ?>
       <select name="tipos" id="tipos" value="" size="10" 
               style="font-size:12px;width:330px;height:120px;<?=$db_opcao==3||$db_opcao==33?"background:#DEB887":""?>" 
               multiple>
        <?for ($x = 0; $x < $clmer_tprefeicao->numrows; $x++) {
        	
            db_fieldsmemory($result1,$x);?>
            <option value="<?=$me03_i_codigo;?>"><?=$me03_c_tipo?></option>
            
        <?}?>
       </select>
      </td>
      <td><input name="vai" type="button" value=">" onclick="js_incluir();" 
                 <?=$db_opcao == 3 || $db_opcao == 33?"disabled":""?>><td>
      <td rowspan="2">
       <?
       $campos  = " me03_i_codigo as cod_tp,me03_c_tipo as tiponome ";
       $result2 = $clmer_cardapiotipo->sql_record($clmer_cardapiotipo->sql_query("",
                                                                                 $campos,
                                                                                 "me03_i_orden",
                                                                                 " me21_i_cardapio = $chavepesquisa"
                                                                                )
                                                 );
       ?> 
       <select name="tpselec" id="tpselect" value="" size="10" 
               style="font-size:12px;width:330px;height:120px;<?=$db_opcao==3||$db_opcao==33?"background:#DEB887":""?>" 
               multiple>
        <?for ($y = 0; $y < $clmer_cardapiotipo->numrows; $y++) {
        	
            db_fieldsmemory($result2,$y);?>
            <option value="<?=$cod_tp;?>"><?=$tiponome?></option>
            
        <?}?>
       </select>
      </td>
     </tr>
     <tr>
      <td><input name="volta" type="button" value="<" onclick="js_excluir();" 
                 <?=$db_opcao==3||$db_opcao==33?"disabled":""?>></td>
     </tr>
    </table>
   </fieldset>
   <input name="lista" type="hidden" value="">
  </td>
 </tr>
</table>
<input name="<?=($db_opcao == 1?"incluir":($db_opcao == 2 || $db_opcao == 22?"alterar":"excluir"))?>" 
       type="submit" id="db_opcao" value="<?=($db_opcao == 1?"Incluir":($db_opcao == 2 || $db_opcao == 22?"Alterar":"Excluir"))?>" 
       <?=($db_botao == false?"disabled":"")?> onclick="return listartp();">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<input name="novo" id="novo" value="Novo" type="button" onclick="js_novo();">
<?if ($naopode == true && $db_opcao != 3 && $db_opcao != 33) {?>
    <input name="newversao" id="newversao" value="Nova Versão" type="button" 
           onclick="js_NovaVersao(<?=$me01_i_codigo?>);">
<?}?>
</table>
</center>
<iframe name="iframe_newversao" src="" frameborder="0" width="0" heigth="0" style="visibility:hidden;"></iframe>
</form>
<script>
function js_pesquisame01_i_tipocardapio(mostra) {
	
  if (mostra==true) {
		  
	js_OpenJanelaIframe('','db_iframe_tipocardapio',
	    	                'func_mer_tipocardapio.php?funcao_js=parent.js_mostratipocardapio1|me27_i_codigo|me27_c_nome|dl_alunos','Pesquisa',true
	    	               );
  } else {
		  
	if (document.form1.me01_i_tipocardapio.value != '') {
	        
	  js_OpenJanelaIframe('','db_iframe_mer_tipocardapio',
	    	                  'func_mer_tipocardapio.php?pesquisa_chave='+document.form1.me01_i_tipocardapio.value+
	    	                  '&funcao_js=parent.js_mostratipocardapio','Pesquisa',false
	    	                 );
	} else {
		
	  document.form1.me27_c_nome.value = '';
      document.form1.me01_i_total.value = '';
      document.form1.me01_i_percapita.value = '';      
      
	}
	
  }
  
}

function js_mostratipocardapio(chave1,chave2,erro) {
		
  document.form1.me27_c_nome.value = chave1;
  document.form1.me01_i_total.value = chave2;
  document.form1.me01_i_percapita.value = chave2;
  if (erro==true) {
		  
	document.form1.me01_i_tipocardapio.focus();
    document.form1.me01_i_tipocardapio.value = '';
    document.form1.me01_i_total.value = '';
	    
  }
  
}

function js_mostratipocardapio1(chave1,chave2,chave3) {
		
  document.form1.me01_i_tipocardapio.value = chave1;
  document.form1.me27_c_nome.value = chave2;
  document.form1.me01_i_total.value = chave3;
  document.form1.me01_i_percapita.value = chave3;
  db_iframe_tipocardapio.hide();
	  
}

	
function js_pesquisa() {
  js_OpenJanelaIframe('','db_iframe_mer_cardapio',
		              'func_mer_cardapio.php?funcao_js=parent.js_preenchepesquisa|me01_i_codigo','Pesquisa',true
		             );
}

function js_preenchepesquisa(chave) {
	
  db_iframe_mer_cardapio.hide();
  <?
   if ($db_opcao!=1) {
   	
     echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
   }
 ?>
}

//movimentação do tp_refeicao
function js_incluir() {
	
  var Tam = document.form1.tipos.length;
  var F   = document.form1;
  for (x = 0; x < Tam; x++) {
	  
    if (F.tipos.options[x].selected == true) {
        
      F.elements['tpselect'].options[F.elements['tpselect'].options.length] = 
         new Option(F.tipos.options[x].text,F.tipos.options[x].value)
      F.tipos.options[x] = null;
      Tam--;
      x--;
    }
  }
  if (document.form1.tipos.length>0) {
    document.form1.tipos.options[0].selected = true;
  }
}

function js_excluir() {
	
  var F = document.getElementById("tpselect");
  Tam   = F.length;
  for (x=0;x<Tam;x++) {
	  
    if (F.options[x].selected==true) {
        
      document.form1.tipos.options[document.form1.tipos.length] = new Option(F.options[x].text,F.options[x].value);
      F.options[x] = null;
      Tam--;
      x--;
      
    }
  }
  if (document.form1.tpselect.length>0) {
    document.form1.tpselect.options[0].selected = true;
  }
  document.form1.tpselect.focus();
}

function listartp() {
	
  lista = "";
  sep   = "";
  tam   = document.form1.tpselect.length;
  for (x = 0; x < tam; x++) {
	  
    lista += sep+document.form1.tpselect.options[x].value;
    sep = ',';
    
  }
  if (lista == "") {
	  
    alert("Informe algum tipo de refeição para esta refeição!");
    return false;
    
  } else {
	  
    document.form1.lista.value = lista;
    return true;
    
 }
}

function js_novo() {
  parent.location.href='mer1_mer_cardapioabas001.php';
}

function js_NovaVersao(cod_refeicao) {
	
  if (confirm("Confirmar criação de nova versão para esta refeição?")) {
    iframe_newversao.location.href='mer1_mer_cardapio004.php?cod_refeicao='+cod_refeicao;
  }
  
}
function js_alunoatend (valor) {

  if (valor!="") {

	if (document.form1.me01_i_total.value=="") {
		
      alert("Informe o Cardápio desta refeição");
      document.form1.me01_i_tipocardapio.focus();
      document.form1.me01_i_tipocardapio.style.backgroundColor='#99A9AE';
      return false;
     
	} else {
		
       if (parseInt(valor)!=parseInt(document.form1.me01_i_total.value)) {

         if (!confirm("Número de alunos atendidos difere do número total de alunos matriculados. Deseja prosseguir?")) {
           document.form1.me01_i_percapita.value = document.form1.me01_i_total.value;
         }

       }
		
	}
	
  }
  
}
</script>