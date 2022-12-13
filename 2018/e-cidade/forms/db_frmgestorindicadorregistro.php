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

//MODULO: Gestor BI
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$clgestorindicadorregistro->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("g03_sequencial");
$clrotulo->label("g03_descricao");
$clrotulo->label("g04_sequencial");
$clrotulo->label("g04_descricao");


if (isset($opcao) && $opcao == "alterar") {
	
  $db_opcao = 2;
  
  $sSql     = $clgestorindicadorregistro->sql_query($g05_sequencial, "*");
  $rsRecord = $clgestorindicadorregistro->sql_record($sSql);
  
  db_fieldsmemory($rsRecord, 0);
} else if (isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3) {
	
  $db_opcao = 3;
  
  $sSql     = $clgestorindicadorregistro->sql_query($g05_sequencial, "*");
  $rsRecord = $clgestorindicadorregistro->sql_record($sSql);
  
  db_fieldsmemory($rsRecord,0);
} else {  
  $db_opcao = 1;
}
?>

<form name="form1" method="post" action="">
<center>


<table width=730 style="margin-top:25px;">
<tr><td>

<fieldset>
<legend><b>Registro de Indicadores</b></legend>

<table border="0" align=center>
  <tr>
    <td nowrap title="<?=@$Tg05_sequencial?>">
       <?=@$Lg05_sequencial?>
    </td>
    <td colspan=3> 
       <?
         db_input('g05_sequencial', 10, $Ig05_sequencial, true, 'text', 3);
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg05_gestorgrupoindicador?>">
       <?
       db_ancora(@$Lg05_gestorgrupoindicador,"js_pesquisag05_gestorgrupoindicador(true);",$db_opcao);
       ?>
    </td>
    <td colspan=3> 
			<?
			db_input('g05_gestorgrupoindicador',10,$Ig05_gestorgrupoindicador,true,'text',$db_opcao," onchange='js_pesquisag05_gestorgrupoindicador(false);'")
			?>
       <?
      db_input('g03_descricao',40,$Ig03_sequencial,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg05_gestorindicador?>">
       <?
       db_ancora(@$Lg05_gestorindicador,"js_pesquisag05_gestorindicador(true);",$db_opcao);
       ?>
    </td>
    <td colspan=3> 
			<?
			db_input('g05_gestorindicador',10,$Ig05_gestorindicador,true,'text',$db_opcao," onchange='js_pesquisag05_gestorindicador(false);'")
			?>
       <?
      db_input('g04_descricao',40,$Ig04_sequencial,true,'text',3,'')
       ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tg05_mes?>">
       <b>Mês/Ano</b><!--<?=@$Lg05_mes?>-->
    </td>
    <td colspan=3> 
			<?
			db_input('g05_mes',3,$Ig05_mes,true,'text',$db_opcao,"")
			?>
			/
			<?
      db_input('g05_ano',3,$Ig05_ano,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg05_valor?>">
       <?=@$Lg05_valor?>
    </td>
    <td> 
			<?
			db_input('g05_valor',15,$Ig05_valor,true,'text',$db_opcao,"")
			?>                      
    </td>
    <td nowrap title="<?=@$Tg05_meta?>">
       <?=@$Lg05_meta?>
    </td>
    <td> 
			<?
			db_input('g05_meta',15,$Ig05_meta,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg05_datalimite?>">
       <?=@$Lg05_datalimite?>
    </td>
    <td> 
			<?
			db_inputdata('g05_datalimite',@$g05_datalimite_dia,@$g05_datalimite_mes,@$g05_datalimite_ano,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  </table>
  </fieldset>
  
  </td></tr>
  </table>
  
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" id="db_opcao" onclick="return js_validar();" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> >

<? if($db_opcao!=1 && $db_opcao!=11) { ?>
  <input name="incluir_novo" value="Incluir Novo" type="button" <?=($db_botao==false?"disabled":"")?> 
         onclick="window.location.href='ges1_gestorindicadorregistro001.php'">
<? } ?>
<table>
  <tr>
    <td valign="top"> 
    <?
     $chavepri= array("g05_sequencial"=>@$g05_sequencial);
     $cliframe_alterar_excluir->chavepri=$chavepri;
     
     $sWhere        = "g05_instit = {$iInstit} ";
     $sCampos       = "*, g05_mes||'/'||g05_ano as db_periodo";
     $sSqlRegistros = $clgestorindicadorregistro->sql_query(null, $sCampos, "g05_sequencial", $sWhere);
     
     $cliframe_alterar_excluir->sql           = $sSqlRegistros;      
     $cliframe_alterar_excluir->campos        = " g05_sequencial, g04_descricao, g03_descricao, "; 
     $cliframe_alterar_excluir->campos       .= " db_periodo, g05_valor, g05_meta               ";
     $cliframe_alterar_excluir->legenda       = "Registros";
     $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
     $cliframe_alterar_excluir->textocabec    = "darkblue";
     $cliframe_alterar_excluir->textocorpo    = "black";
     $cliframe_alterar_excluir->fundocabec    = "#aacccc";
     $cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
     $cliframe_alterar_excluir->iframe_width  = "700";
     $cliframe_alterar_excluir->iframe_height = "150";
     $cliframe_alterar_excluir->iframe_alterar_excluir(1);   
     db_input('db_opcao', 10, '' , true, 'hidden', 3);
    ?>
   </td>
 </tr>
</table>
</form>
<script>
function js_validar() {

  if ($('g05_mes').value < 1 || $('g05_mes').value > 12) {
  
    alert('Mês informado está incorreto! Verifique.');
    return false;
  }
}

function js_pesquisag05_gestorgrupoindicador(mostra){

  if(mostra==true){
  
    var sUrl = 'func_gestorgrupoindicador.php?dtlimite=true&funcao_js=parent.js_mostragestorgrupoindicador1|g03_sequencial|g03_descricao';
    js_OpenJanelaIframe('top.corpo','db_iframe_gestorgrupoindicador',sUrl,'Pesquisa',true);
  }else{
     if($F('g05_gestorgrupoindicador') != ''){ 
     
       var sUrl = 'func_gestorgrupoindicador.php?pesquisa_chave='+$F('g05_gestorgrupoindicador')+'&dtlimite=true&funcao_js=parent.js_mostragestorgrupoindicador';
       js_OpenJanelaIframe('top.corpo','db_iframe_gestorgrupoindicador',sUrl,'Pesquisa',false);
     }else{
       $('g03_sequencial').value = ''; 
     }
  }
}

function js_mostragestorgrupoindicador(chave, chave2, erro){
  $('g05_gestorgrupoindicador').value = chave;
  $('g03_descricao').value = chave2; 
  if(erro==true){ 
    $('g05_gestorgrupoindicador').focus(); 
    $('g05_gestorgrupoindicador').value = '';     
  }
}

function js_mostragestorgrupoindicador1(chave1,chave2){
  $('g05_gestorgrupoindicador').value = chave1;
  $('g03_descricao').value = chave2;
  db_iframe_gestorgrupoindicador.hide();
}

function js_pesquisag05_gestorindicador(mostra){
  if(mostra==true){
  
    var sUrl = 'func_gestorindicador.php?dtlimite=true&funcao_js=parent.js_mostragestorindicador1|g04_sequencial|g04_descricao';
    js_OpenJanelaIframe('top.corpo','db_iframe_gestorindicador',sUrl,'Pesquisa',true);
  }else{
     if($F('g05_gestorindicador') != ''){ 
     
        var sUrl = 'func_gestorindicador.php?pesquisa_chave='+$F('g05_gestorindicador')+'&dtlimite=true&funcao_js=parent.js_mostragestorindicador';
        js_OpenJanelaIframe('top.corpo','db_iframe_gestorindicador',sUrl,'Pesquisa',true);
     }else{
       $('g05_gestorindicador').value = ''; 
     }
  }
}

function js_mostragestorindicador(chave,chave2, erro){
  $('g05_gestorindicador').value = chave;
  $('g04_descricao').value = chave2;
   
  if(erro==true){ 
    $('g05_gestorindicador').focus(); 
    $('g05_gestorindicador').value = ''; 
  }
}

function js_mostragestorindicador1(chave1,chave2){
  $('g05_gestorindicador').value = chave1;
  $('g04_descricao').value = chave2;
  db_iframe_gestorindicador.hide();
}
</script>