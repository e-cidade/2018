<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
$clgestorindicador->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db84_sequencial");
?>

<style type="text/css">

.fieldsetinterno {
	border:0px;
	border-top:2px groove white;
	margin-top:10px;
}

td {
  white-space: nowrap
}

fieldset table td:first-child {
	width: 100px;
	white-space: nowrap
}

</style>


<form name="form1" method="post" action="" onsubmit="return js_valida_faixas();">
<center>

<table border=0 style="margin-top:30px;">
<tr><td>

<fieldset>
<legend><b>Indicadores</b></legend>

<fieldset class="fieldsetinterno">
<legend><b>Dados</b></legend>

<table border="0" >
  <tr>
    <td nowrap title="<?=@$Tg04_sequencial?>" >
       <b>Código:</b>
    </td>
    <td style="width:120px;"> 
			<?
			db_input('g04_sequencial',10,$Ig04_sequencial,true,'text',3,"")
			?>
    </td>
    <td nowrap title="<?=@$Tg04_periodicidade?>" style="width:80px;">
       <b>Periodicidade:</b>
    </td>
    <td> 
       <?
       include("classes/db_db_periodicidade_classe.php");
       $cldb_periodicidade = new cl_db_periodicidade;
       $sSqlPeriodicidade = $cldb_periodicidade->sql_query("","*","db84_sequencial ASC");       
       $result = $cldb_periodicidade->sql_record($sSqlPeriodicidade);
       $aPer = array();
       while ($row = pg_fetch_array($result)) {
         $aPer[$row[0]] = $row[1]; 
       }
       db_select("g04_periodicidade", $aPer, true, $db_opcao, "style='width:123px;'");
       ?>
    </td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Tg04_descricao?>" >
       <?=@$Lg04_descricao?>
    </td>
    <td colspan="3"> 
			<?
			db_input('g04_descricao',45,$Ig04_descricao,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr title="<?=@$Tg04_tipo?>">
    <td nowrap >
       <b>Tipo:</b>
    </td>
    <td> 
			<?
			$x = array('1'=>'Crescente','2'=>'Decrescente');
			db_select('g04_tipo',$x,true,$db_opcao,"onchange='js_limpaCampos();'");
			?>
    </td>
      <td nowrap title="<?=@$Tg04_limite?>" style="width:80px;">
       <b>Data Limite:</b>
    </td>
    <td> 
			<?
			db_inputdata('g04_limite',@$g04_limite_dia,@$g04_limite_mes,@$g04_limite_ano,true,'text',$db_opcao,"");
			?>
    </td>
  </tr>
  <tr title="<?=@$Tg04_link?>">
    <td nowrap="nowrap">
      <?=@$Lg04_link?>
    </td>  
    <td nowrap="nowrap" colspan="3">  
      <?
        db_input('g04_link',45,$Ig04_link,true,'text',$db_opcao,"onkeyup=''");
      ?> 
    </td> 
  </tr>
  <tr title="<?=@$Tg04_definicao?>">
    <td colspan="4">
      <fieldset>
        <legend><b>Definição:</b></legend>
        <?
          db_textarea('g04_definicao',5,60,$Ig04_definicao,true,'text',$db_opcao,"")
        ?>
        
      </fieldset>
    </td> 
  </tr>
</table> 
</fieldset> 

<fieldset class="fieldsetinterno">
<legend><b>Faixas</b></legend>
  
<table border=0>
  <tr>
    <td nowrap title="<?=@$Tg04_faixainicial?>" style="width:100px;">
       <b>Inicial:</b>
    </td>
    <td style="width:180px;"> 
		<?
		db_input('g04_faixainicial',15,$Ig04_faixainicial,true,'text',$db_opcao,"")
		?>
    </td>
    <td nowrap title="<?=@$Tg04_faixafinal?>" style="width:50px;">
       <b>Final:</b>
    </td>
    <td> 
		<?
		db_input('g04_faixafinal',15,$Ig04_faixafinal,true,'text',$db_opcao);
		?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg04_faixaverdeinicial?>"style="width:100px;">
       <b>Verde Inicial:</b>
    </td>
    <td> 
		<?
		db_input('g04_faixaverdeinicial',15,$Ig04_faixaverdeinicial,true,'text',$db_opcao,"")
    ?>
    </td>
    <td nowrap title="<?=@$Tg04_faixaverdefinal?>">
       <b>Final:</b>
    </td>
    <td> 
			<?
			db_input('g04_faixaverdefinal',15,$Ig04_faixaverdefinal,true,'text',$db_opcao);
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg04_faixaamarelainicial?>"style="width:100px;">
       <b>Amarela Inicial:</b>
    </td>
    <td> 
			<?
			db_input('g04_faixaamarelainicial',15,$Ig04_faixaamarelainicial,true,'text',$db_opcao,"")
			?>
    </td>

    <td nowrap title="<?=@$Tg04_faixaamarelafinal?>">
       <b>Final:</b>
    </td>
    <td> 
			<?
			db_input('g04_faixaamarelafinal',15,$Ig04_faixaamarelafinal,true,'text',$db_opcao);
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg04_faixavermelhainicial?>"style="width:100px;">
       <b>Vermelha Inicial:</b>
    </td>
    <td> 
			<?
			db_input('g04_faixavermelhainicial',15,$Ig04_faixavermelhainicial,true,'text',$db_opcao,"")
			?>
    </td>

    <td nowrap title="<?=@$Tg04_faixavermelhafinal?>">
       <b>Final:</b>
    </td>
    <td> 
			<?
			db_input('g04_faixavermelhafinal',15,$Ig04_faixavermelhafinal,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
</table>
</fieldset>  

<fieldset class="fieldsetinterno">
<legend><b>Alerta</b></legend>

<table border=0>
  <tr>
    <td nowrap title="<?=@$Tg04_emitealerta?>" style="width:100px;">
       <b>Alertar:</b>
    </td>
    <td> 
      <?
      $x = array("f"=>"NAO","t"=>"SIM");
      db_select('g04_emitealerta',$x,true,$db_opcao,"onChange='js_show_hide();'");
      ?>
    </td>
  </tr>
</table>


<table border=0 id="alerta" style="display:none;">
  <tr>
    <td nowrap title="<?=@$Tg04_valoralerta?>" style="width:100px;">
       <b>Valor:</b>
    </td>
    <td colspan=3> 
		<?
		db_input('g04_valoralerta',20,$Ig04_valoralerta,true,'text',$db_opcao,"")
		?>
    </td>    
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg04_emailalerta?>" style="width:100px;">
       <?=@$Lg04_emailalerta?>
    </td>
    <td colspan=3> 
			<?
			db_input('g04_emailalerta',40,$Ig04_emailalerta,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  
  <tr>
    <td colspan=4 align=center>
      <fieldset>
      <legend><b>Mensagem</b></legend> 
			<?
			db_textarea('g04_mensagemalerta',5,60,$Ig04_mensagemalerta,true,'text',$db_opcao,"")
			?>
			</fieldset>
    </td>
  </tr>

  </table>
  
  </fieldset>
  
  <td><tr>
  </table>
  
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
    type="submit" id="db_opcao" 
    value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
    <?=($db_botao==false?"disabled":"")?> >    
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>


/**
 * Verifica se o parâmetro iInicial é maior/ menor que o iFinal dependendo do tipo selecionado no form "Crescente/Decrescente"
 * lTesta -> Flag para executar mais uma validação ou não
 */
function js_validaIndicador(iInicial, iFinal, lTesta) {

  var iFaixaFinal   = new Number($F('g04_faixafinal'));
  var iTipo         = $('g04_tipo').value;
  
  /**
   * iRetorno igual == 0 -> Não ocorreu nenhum problema execução do scritp
   * iRetorno igual == 1 -> Valor Inicial é maior/menor (depende do tipo) ou igual que valor final 
   * iRetorno igual == 2 -> Valor Final é maior/menor (depende do tipo) ou igual que valor final do intervalo
   */
  var iRetorno = 0; 
  
  if (iTipo == 1) {
    
    if (iInicial >= iFinal) {
      iRetorno = 1;
    }
    if (lTesta) {
      if (iFinal >= iFaixaFinal){
        iRetorno = 1;
      } 
    }
  }
  
  if (iTipo == 2) {
  
    if (iInicial <= iFinal) {
      iRetorno = 1;
    }
    if (lTesta) {
      if (iFinal <= iFaixaFinal){
        iRetorno = 2;
      }
    }
  }
  
  return iRetorno;

}

/**
 * Disparada depois de setado o valor no campo amarelo final
 */
function js_validaAmareloFinal() {
  
  var iAmareloInicial = new Number($F('g04_faixaamarelainicial'));
  var iAmareloFinal   = new Number($F('g04_faixaamarelafinal'));
  
  var iTipo         = $('g04_tipo').value;
  var sMsg          = "";
  
  var iRetorno = js_validaIndicador(iAmareloInicial, iAmareloFinal, true);
  
  if (iRetorno == 1) {
  
    if (iTipo == 1) {
      sMsg = "No Tipo Crescente a faixa amarela inicial não pode ser maior ou igual que a faixa amarela final";  
    } else {
      sMsg = "No Tipo Decrescente a faixa amarela inicial não pode ser menor ou igual que a faixa amarela final";
    }
  } else if (iRetorno == 2) {
  
    if (iTipo == 1) {
      sMsg = "Faixa amarela final não pode ser maior ou igual que a faixa vermelha final";
    } else {
      sMsg = "Faixa amarela final não pode ser maior ou igual que a faixa vermelha final";
    }
  }
  
  if (iRetorno != 0) {
    $('g04_faixaamarelafinal').clear();
    $('g04_faixavermelhainicial').clear();
    $('g04_faixaamarelafinal').focus();
    alert(sMsg);
  } else {
    if (iTipo == 1) {
      $('g04_faixavermelhainicial').value = $F('g04_faixaamarelafinal');
      $('g04_faixavermelhainicial').setAttribute("readonly", "readonly");
    }
    if (iTipo == 2) {
      $('g04_faixavermelhainicial').value = $F('g04_faixaamarelafinal');
      $('g04_faixavermelhainicial').setAttribute("readonly", "readonly");
    }
  }
  
}


/**
 * Disparada depois de setado o valor no campo verde final
 */
function js_validaVerdeFinal() {
  
  var iVerdeFinal    = new Number($F('g04_faixaverdefinal')); 
  var iVerdeInicial  = new Number($F('g04_faixaverdeinicial'));
  var iTipo         = $('g04_tipo').value;
  var sMsg          = "";

  var iRetorno = js_validaIndicador(iVerdeInicial, iVerdeFinal, true);
  
  if (iRetorno == 1) {
  
    if (iTipo == 1) {
      sMsg = "No Tipo Crescente a faixa verde inicial não pode ser maior ou igual que a faixa verde final";  
    } else {
      sMsg = "No Tipo Decrescente a faixa verde inicial não pode ser menor ou igual que a faixa verde final";
    }
  } else if (iRetorno == 2) {
  
    if (iTipo == 1) {
      sMsg = "Faixa verde final não pode ser maior ou igual que a faixa vermelha final";
    } else {
      sMsg = "Faixa verde final não pode ser maior ou igual que a faixa vermelha final";
    }
  }
  
  if (iRetorno != 0) {
    $('g04_faixaverdefinal').clear();
    $('g04_faixaamarelainicial').clear();
    $('g04_faixaamarelafinal').clear();
    $('g04_faixaverdefinal').focus();
    alert(sMsg);
  } else {
    if (iTipo == 1) {
      $('g04_faixaamarelainicial').value = $F('g04_faixaverdefinal');
      $('g04_faixaamarelainicial').setAttribute("readonly", "readonly");
      $('g04_faixaamarelafinal').focus();
    }
    if (iTipo == 2) {
      $('g04_faixaamarelainicial').value = $F('g04_faixaverdefinal');
      $('g04_faixaamarelainicial').setAttribute("readonly", "readonly");
      $('g04_faixaamarelafinal').focus();
    }
  }
}

/**
 * Disparada depois de setado o valor final do intervalo
 */
function js_verificaFaixaFinal() {
  
  var iFaixaInicial = new Number($F('g04_faixainicial'));
  var iFaixaFinal   = new Number($F('g04_faixafinal'));
  var iTipo         = $('g04_tipo').value;
  var sMsg          = "";
  
  var iRetorno = js_validaIndicador(iFaixaInicial, iFaixaFinal, false);

  if (iRetorno == 1) {
  
    if (iTipo == 1) {
      sMsg = "No Tipo Crescente a faixa inicial não pode ser maior ou igual que a faixa final";  
    } else {
      sMsg = "No Tipo Decrescente a faixa inicial não pode ser menor ou igual que a faixa final";
    }
  } 
  
  if (iRetorno != 0) {
    $('g04_faixafinal').clear();
    $('g04_faixainicial').clear();
    $('g04_faixainicial').focus();
    alert(sMsg);
  } else {
    if (iTipo == 1) {
      $('g04_faixavermelhafinal').value = $F('g04_faixafinal');
      $('g04_faixaverdeinicial').value = $F('g04_faixainicial');
      $('g04_faixaverdeinicial').setAttribute("readonly", "readonly");
      $('g04_faixaverdefinal').focus();
    }
    if (iTipo == 2) {
      $('g04_faixavermelhafinal').value = $F('g04_faixafinal');
      $('g04_faixaverdeinicial').value = $F('g04_faixainicial');
      $('g04_faixaverdeinicial').setAttribute("readonly", "readonly");
      $('g04_faixaverdefinal').focus();
    }
  }
}

/**
 * Limpa os campos de Faixas quando muda-se o tipo
 */
function js_limpaCampos() {

  $('g04_faixainicial').clear();
  $('g04_faixafinal').clear(); 
  $('g04_faixaverdeinicial').clear();
  $('g04_faixaverdefinal').clear();
  $('g04_faixaamarelainicial').clear();
  $('g04_faixaamarelafinal').clear();
  $('g04_faixavermelhainicial').clear();
  $('g04_faixavermelhafinal').clear();
  
  $('g04_faixainicial').focus();
  

}

// deprecated
/*
function js_valida_faixas() {

  var inicial     = parseFloat($F('g04_faixainicial')); 
  var final       = parseFloat($F('g04_faixafinal')); 
  var vinicial    = parseFloat($F('g04_faixaverdeinicial'));
  var vfinal      = parseFloat($F('g04_faixaverdefinal'));
  var ainicial    = parseFloat($F('g04_faixaamarelainicial'));
  var afinal      = parseFloat($F('g04_faixaamarelafinal'));
  var verminicial = parseFloat($F('g04_faixavermelhainicial'));
  var vermfinal   = parseFloat($F('g04_faixavermelhafinal'));
  
  var alerta      = $F('g04_emitealerta');
  var valoralerta = parseFloat($F('g04_valoralerta'));
  
  if (final > inicial) {
    
    if (vfinal > vinicial) {
    
      if (vinicial >= inicial && vfinal <= final) {
      
        if (afinal > ainicial) {
        
          if(ainicial >= inicial && afinal <= final) {
          
            if (vermfinal > verminicial) {
            
              if (verminicial >= inicial && vermfinal <= final) {
              
                if (vinicial > afinal || vfinal < ainicial) {
                
                  if (vinicial > vermfinal || vfinal < verminicial) {
                  
                    if (ainicial > vermfinal || afinal < verminicial) {
                                            
                      if(alerta == 't') {
                        
                        if(valoralerta >= inicial && valoralerta <= final) {
                          return true;
                        } else {
                        
                          alert("O valor para alerta deve estar no periodo da Faixa Geral!");
                          return false;
                        }    
                      
                      } else {
	                      return true;
	                    }  
	                                      
                    } else {
                    
                      alert("O periodo da Faixa Amarela conflita com o periodo da Faixa Vermelha!");
                      return false;
                    }
                  
                  } else {
                  
                    alert("O periodo da Faixa Verde conflita com o periodo da Faixa Vermelha!");
                    return false;
                  }
                
                } else {
                
                  alert("O periodo da Faixa Verde conflita com o periodo da Faixa Amarela!");
                  return false;
                }
              
              } else {
              
                alert("O periodo da Faixa Vermelha deve estar dentro da Faixa Geral");
                return false;
              }
            
            } else {
            
              alert("A Faixa Vermelha Final deve ser maior do que a Faixa Vermelha Inicial!");
              return false;
            }
          
          } else {
          
            alert("O Periodo da Faixa Amarela deve estar dentro da Faixa Geral!");
            return false;
          }                  
        
        } else {
        
          alert("A Faixa Amarela Final deve ser maior do que a Faixa Amarela Inicial!");
          return false;
        }
              
      } else {
      
        alert("O Periodo da Faixa Verde deve estar dentro da Faixa Geral!");
        return false;
      }
    
    } else {
    
      alert("A Faixa Verde Final deve ser maior do que a Faixa Verde Inicial!");
      return false;
    }
      
  } else {
  
    alert("A Faixa Final deve ser maior que a Faixa Inicial");
    return false;
  }  
    
  return false;  
}
*/

function js_pesquisag04_periodicidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_periodicidade','func_db_periodicidade.php?funcao_js=parent.js_mostradb_periodicidade1|db84_sequencial|db84_sequencial','Pesquisa',true);
  }else{
     if(document.form1.g04_periodicidade.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_periodicidade','func_db_periodicidade.php?pesquisa_chave='+document.form1.g04_periodicidade.value+'&funcao_js=parent.js_mostradb_periodicidade','Pesquisa',false);
     }else{
       document.form1.db84_sequencial.value = ''; 
     }
  }
}
function js_mostradb_periodicidade(chave,erro){
  document.form1.db84_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.g04_periodicidade.focus(); 
    document.form1.g04_periodicidade.value = ''; 
  }
}
function js_mostradb_periodicidade1(chave1,chave2){
  document.form1.g04_periodicidade.value = chave1;
  document.form1.db84_sequencial.value = chave2;
  db_iframe_db_periodicidade.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_gestorindicador','func_gestorindicador.php?funcao_js=parent.js_preenchepesquisa|g04_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){

  db_iframe_gestorindicador.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_show_hide() {

  if($F('g04_emitealerta') == 't') {
    $('alerta').style.display = '';
  } else {
    $('alerta').style.display = 'none';
  }
}

js_show_hide();
</script>