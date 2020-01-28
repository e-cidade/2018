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

//MODULO: diversos
$cldiversos->rotulo->label();

$clrotulo = new rotulocampo;

$clrotulo->label("z01_nome");
$clrotulo->label("dv09_descr");
$clrotulo->label("i02_codigo");
$clrotulo->label("dv05_procdiver");

$dia=date('d',db_getsession("DB_datausu"));
$mes=date('m',db_getsession("DB_datausu"));
$ano=date('Y',db_getsession("DB_datausu"));
?>
<script>
function js_trocatotal() {
  
  valor   = new Number(document.form1.dv05_valor.value);  
  numtot  = new Number(document.form1.dv05_numtot.value);
  xx      = new Number(valor*numtot);
  
  if( isNaN(xx) ) {
    
    document.form1.dv05_valor.focus();
    document.getElementById("total").innerHTML=" 0,00";
    
  }else{    
    document.getElementById("total").innerHTML="Valor total:R$ "+xx.toFixed(2);
  } 
   
}

function js_verifica() {
  
  obj = document.form1;
     
  if ( obj.dv05_dtinsc_dia.value == "" || obj.dv05_dtinsc_mes.value == "" || obj.dv05_dtinsc_ano.value == "" ) {
    
    alert(_M("tributario.diversos.db_frmdiversosalt.verifique_data_inscricao"));
    return false;
    
  }
  
  if ( obj.dv05_exerc.value == "" || obj.dv05_exerc.value == 0 ) {
    
   alert(_M("tributario.diversos.db_frmdiversosalt.verifique_ano_origem")); 
   return false;
   
  }
  
  if ( obj.dv05_procdiver.value == "" ) {
    
    alert(_M("tributario.diversos.db_frmdiversosalt.verifique_procedencia")); 
    return false;
    
  } 
   
  if ( obj.dv05_privenc_dia.value == "" || obj.dv05_privenc_mes.value == "" || obj.dv05_privenc_ano.value == "") {
    
    alert(_M("tributario.diversos.db_frmdiversosalt.verifique_data_primeiro_vencimento"));
    return false;
  } 
   
  if ( obj.dv05_vlrhis.value == "" ) {
    alert(_M("tributario.diversos.db_frmdiversosalt.verifique_valor_historico")); 
    return false;
  }
  
  if ( obj.dv05_oper_dia.value == "" || obj.dv05_oper_mes.value == "" || obj.dv05_oper_ano.value == "" ) {
    
    alert(_M("tributario.diversos.db_frmdiversosalt.verifique_data_operacao"));
    return false;
  } 
   
  if ( obj.dv05_valor.value == "" ) {
    
    alert(_M("tributario.diversos.db_frmdiversosalt.verifique_valor_total")); 
    return false;
  }
  
  if ( obj.dv05_numtot.value == "" ) {
    
    alert(_M("tributario.diversos.db_frmdiversosalt.verifique_numero_parcelas")); 
    return false;
  }
  
  if ( obj.dv05_numtot.value>1 ) {
    
    if ( obj.dv05_provenc_dia.value == "" || obj.dv05_provenc_mes.value == "" || obj.dv05_provenc_ano.value == "" ) {
      
      alert(_M("tributario.diversos.db_frmdiversosalt.verifique_data_proximo_vencimento"));
      return false;
    } 
     
    if ( obj.dv05_diaprox.value == "" ) {
      
      alert(_M("tributario.diversos.db_frmdiversosalt.verifique_dia_proximos_vencimentos")); 
      return false;
    }
    
  }
  
  return true;  
}

function js_sub(obj) {
  
  if ( obj.value != 0 ) {
    
    var dia    = new Number(document.form1.dv05_privenc_dia.value); 
    var mes    = new Number(document.form1.dv05_privenc_mes.value); 
    var ano    = new Number(document.form1.dv05_privenc_ano.value);     
    var vlrhis = document.form1.dv05_vlrhis.value;
     
    if ( dia == "" || mes == "" || ano == "" ) { 
      alert(_M("tributario.diversos.db_frmdiversosalt.preencha_data_primeiro_vencimento"));
    } else {
      
      if ( document.form1.dv05_procdiver == "" ) {
        alert(_M("tributario.diversos.db_frmdiversosalt.selecione_procedencia"));
      } else {
                
	      if ( vlrhis == "" && obj.name == "calcula" ) {
	       alert(_M("tributario.diversos.db_frmdiversosalt.preencha_valor_historico"));
	      } else {
       
      	  if ( vlrhis != "" ) {
            
            document.form1.subtes.value = "ok";
            document.form1.submit();  
      	  }
      	}
      }
    }
  } else {
    
     document.form1.i02_codigo.value = "";
     document.form1.dv05_valor.value = "";
  }  
}

function js_trocatot(obj) {
  
  var tot = new Number(obj.value);
  var dia = new Number(document.form1.dv05_privenc_dia.value);
  var mes = new Number(document.form1.dv05_privenc_mes.value);
  var ano = new Number(document.form1.dv05_privenc_ano.value);
  
  if ( !isNaN(tot) && tot > 1 ) {
    
    document.getElementById("provenc").style.display = '';
    document.getElementById("diaprox").style.display = '';
    
  	mes--;
    
  	if ( mes == 11 ) {
      
  	  ano++;
  	  mes="0";
      
    } else {
  	  mes++; 
  	}
     
    if(dia!="" && mes!="" && ano!=""){
      
      x                                     = js_retornadata(dia,mes,ano);
  	  document.form1.dv05_provenc_dia.value = x.getDate();
  	  document.form1.dv05_provenc_mes.value = x.getMonth()+1;
  	  document.form1.dv05_provenc_ano.value = x.getFullYear();
  	  document.form1.dv05_diaprox.value     = x.getDate();
  	}
  	  
  } else {
  
    document.form1.dv05_diaprox.value                   = dia;  
    document.form1.dv05_provenc_dia.value               = dia;  
    document.form1.dv05_provenc_mes.value               = mes;  
    document.form1.dv05_provenc_ano.value               = ano;  
    document.getElementById("provenc").style.display    = '';
    document.getElementById("diaprox").style.display    = '';
  }
  
  js_trocatotal();
  
}

function js_di(){
  
  document.form1.dv05_numtot.value='1';
  document.getElementById("provenc").style.display = 'none';
  document.getElementById("diaprox").style.display = 'none';
}
</script>
<?
if ( $db_opcao == 1 ) {
  $p = 5;
} elseif ( $db_opcao == 2 || $db_opcao == 22 ) {
  $p = 6;
}else{
  $p = 7;
}
?>
<form class="container" name="form1" method="post" action="dvr3_diversos00<?=$p?>.php">

  <input type="hidden" name="tipo" value="<?=@$tipo?>">
  <input type="hidden" name="valor" value="<?=@$valor?>">
  <input type="hidden" name="dv05_numpre" value="<?=@$dv05_numpre?>">
  
  <fieldset>
    <legend>Cadastro de diversos</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tdv05_coddiver?>">
          <?db_input('subtes',10,2,true,'hidden',1)?>
          <?=@$Ldv05_coddiver?>
        </td>
        <td> 
         <?
          db_input('dv05_coddiver', 10, $Idv05_coddiver, true, 'text', 3, "", "")
         ?>
        </td>
      </tr>
    
      <tr>
        <td nowrap title="<?=@$Tdv05_numcgm?>">
          <?
           db_ancora(@$Ldv05_numcgm,"",3);
          ?>
        </td>
        <td nowrap> 
          <?
           db_input('dv05_numcgm', 10,$Idv05_numcgm, true, 'text', 3, " onchange='js_pesquisadv05_numcgm(false);'");
           db_input('z01_nome', 40,$Iz01_nome, true, 'text', 3, '')
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tdv05_dtinsc?>">
          <?=@$Ldv05_dtinsc?>
        </td>
        <td> 
        <?
          if( !isset($dv05_dtinsc_dia) && $db_opcao == 1 ) {
        
            $dv05_dtinsc_dia = $dia;
            $dv05_dtinsc_mes = $mes;
            $dv05_dtinsc_ano = $ano;
          }
      
          db_inputdata('dv05_dtinsc', @$dv05_dtinsc_dia, @$dv05_dtinsc_mes, @$dv05_dtinsc_ano, true, 'text', $db_opcao)
        ?>
      </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tdv05_exerc?>">
           <?=@$Ldv05_exerc?>
        </td>
        <td> 
           <?
           if (!isset($dv05_exerc) && $db_opcao==1) {
             $dv05_exerc = db_getsession("DB_anousu");
           }
           db_input('dv05_exerc',10,$Idv05_exerc,true,'text',"");
           ?>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <fieldset class="separator">
            <Legend>Cálculo do valor total</Legend>
            <table class="form-container">
              <tr>
                <td nowrap title="<?=@$Tdv05_procdiver?>">
                  <? db_ancora($Ldv05_procdiver,"js_pesquisaProcedencia(true)",$db_opcao); ?>                  
                </td>
                <td nowrap>
                  <?
                    db_input('dv05_procdiver',10,$Idv05_vlrhis,true,'text',$db_opcao,"onChange = \"js_pesquisaProcedencia(false);\"");
                    db_input('dv09_descr',44,$Idv05_vlrhis,true,'text',3);
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Tdv05_privenc?>">
                  <?=@$Ldv05_privenc?>
                </td>
                <td>
                  <?
                  if  ( !isset($dv05_privenc_dia) && $db_opcao == 1 ) {
                    $dv05_privenc_dia = $dia;
                    $dv05_privenc_mes = $mes;
                    $dv05_privenc_ano = $ano;
                  }
                  db_inputdata('dv05_privenc', @$dv05_privenc_dia, @$dv05_privenc_mes, @$dv05_privenc_ano, true, 'text', $db_opcao, "");
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Tdv05_vlrhis?>">
                  <?=@$Ldv05_vlrhis?>
                </td>
                <td>
                  <?
                    db_input('dv05_vlrhis',10,$Idv05_vlrhis,true,'text',$db_opcao);
                  ?> 
                  <input type="button" name="calcula" onclick="js_sub(this)" value="Calcular"<?=($db_opcao==22 || $db_opcao==33 || $db_opcao==3?"disabled":"")?>>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Ti02_codigo?>">
                  <?=@$Li02_codigo?>
                </td>
                <td>
                  <?
                  if ( isset($subtes) && $subtes == "ok" && !isset($chavepesquisa) ) {
                    
                    $oper        = $dv05_oper_ano."-".$dv05_oper_mes."-".$dv05_oper_dia;
                    $venc        = $dv05_privenc_ano."-".$dv05_privenc_mes."-".$dv05_privenc_dia;
                    $result03    = db_query("select tabrecjm.k02_corr, 
                                                    procdiver.dv09_receit 
                                               from procdiver 
                                                    inner join tabrec   on tabrec.k02_codigo  = procdiver.dv09_receit 
                                                    inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm 
                                              where procdiver.dv09_procdiver = $dv05_procdiver");
                    
                    db_fieldsmemory($result03, 0);
                    
                    $i02_codigo  = $k02_corr;
                    $result08    = db_query("select fc_corre($dv09_receit, '$oper', $dv05_vlrhis, '".date('Y-m-d',db_getsession("DB_datausu"))."', ".db_getsession("DB_anousu").", '$venc')");
                    db_fieldsmemory($result08, 0);
                    
                    if ( $fc_corre=="-1" ) {
                      $xxx="ok";
                    } else {
                      $dv05_valor = $fc_corre;
                    }
                  }
                  db_input('i02_codigo',10,$Ii02_codigo,true,'text',3)
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Tdv05_oper?>">
                 <?=@$Ldv05_oper?>
                </td>
                <td>
                  <?
                    if(empty($dv05_oper_dia) && $db_opcao==1){
                      $dv05_oper_dia=$dia;
                      $dv05_oper_mes=$mes;
                      $dv05_oper_ano=$ano;
                    }
                    db_inputdata('dv05_oper',@$dv05_oper_dia,@$dv05_oper_mes,@$dv05_oper_ano,true,'text',$db_opcao,"")
                  ?>
                </td>
              </tr>
              
              <tr>
                <td nowrap title="<?=@$Tdv05_valor?>">
                  <?=@$Ldv05_valor?>
                </td>
                <td>
                  <?
                    db_input('dv05_valor',10,$Idv05_valor,true,'text',$db_opcao,"onchange='js_trocatotal();'")
                  ?>
                  <b id="total">&nbsp;</b>
                </td>
              </tr>                  
            </table>
          </fieldset>
        </td>
      </tr>
      
      <tr>
        <td nowrap title="<?=@$Tdv05_numtot?>">
           <?=@$Ldv05_numtot?>
        </td>
        <td> 
          <?
            db_input('dv05_numtot', 10, $Idv05_numtot, true, 'text', $db_opcao, "onchange = 'js_trocatot(this);'");
          ?>
        </td>
      </tr>
      
      <tr id="provenc">
        <td nowrap title="<?=@$Tdv05_provenc?>">
          <?=@$Ldv05_provenc?>
        </td>
        <td> 
           <? db_inputdata('dv05_provenc',@$dv05_provenc_dia,@$dv05_provenc_mes,@$dv05_provenc_ano,true,'text',$db_opcao) ?>
        </td>
      </tr>
      
      <tr id="diaprox">
        <td nowrap title="<?=@$Tdv05_diaprox?>">
          <?=@$Ldv05_diaprox?>    
        </td>
        <td >
          <? db_input('dv05_diaprox', 10, $Idv05_diaprox, true, 'text', $db_opcao) ?>
        </td>
      </tr>
      
      <tr>        
        <td colspan="2" title="<?=@$Tdv05_obs?>">
          <fieldset class="separator">
            <legend><?=@$Ldv05_obs?></legend>
            <? db_textarea('dv05_obs', 10, 73, $Idv05_obs, true, 'text', $db_opcao, "") ?>
          </fieldset>           
        </td>
      </tr>
      
    </table>  
    </fieldset>
  <input name="db_opcao"  type="submit" id="db_opcao"  value="<?=($db_opcao==1?"Incluir":($db_opcao==2 || $db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  <?=($db_opcao!=3?"onclick='return js_verifica();'":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar"    onclick="js_pesquisa();" >
  <input name="voltar"    type="button" id="voltar"    value="Voltar"       onclick="js_volta();" >
  
</form>

<script>

function js_pesquisaProcedencia(lMostra){

  if (lMostra) {
    
     js_OpenJanelaIframe('', 
                         'db_iframe_procedencia', 
                         'func_procdiver.php?funcao_js=parent.js_mostraProcedencia1|dv09_procdiver|dv09_descr',
                         'Pesquisar CGM',
                         true);
  } else {
    
    if($('dv05_procdiver').value != ''){ 
       js_OpenJanelaIframe('',
                           'db_iframe_procedencia',
                           'func_procdiver.php?pesquisa_chave=' + document.getElementById("dv05_procdiver").value + 
                           '&funcao_js=parent.js_mostraProcedencia',
                           'Pesquisa',
                           false);
    } else {
      $("dv05_procdiver").value = ''; 
    }
  }
}

function js_mostraProcedencia(chave, erro){
  if (erro == true) { 
  
    $('dv05_procdiver').focus(); 
    $("dv05_procdiver").value = '';
    $('dv09_descr')    .value = chave;
    
  } else {
    $('dv09_descr').value = chave;
  }
}

function js_mostraProcedencia1(iCodigoProcedencia, sDescricaoProcedencia) {
  
  $('dv05_procdiver').value   = iCodigoProcedencia;
  $('dv09_descr').value       = sDescricaoProcedencia;
  db_iframe_procedencia.hide();
}     
   

function js_volta() {
  location.href = "dvr3_diversos00<?=($db_opcao == 2 ? 6 : 4) ?>.php";
}
<?
echo "js_trocatotal();";

if( isset($xxx) && $xxx == "ok" && !isset($HTTP_POST_VARS["db_opcao"])) {
  $sMsg = _M('tributario.diversos.db_frmdiversosalt.informe_valor_corrigido_total');
  echo "
     function js_xxx(){
    	  document.form1.dv05_valor.value='';
	      document.form1.dv05_valor.focus();
          alert({$sMsg});
	}
	js_xxx();
  ";
}
?>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe','func_diversos.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave) {
  
  db_iframe.hide();
  <? if ( $db_opcao !=1 ){ ?>  
    location.href = '<?= basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa=" + chave;
  <? }  ?> 
}
</script>
<?
if ( (!isset($dv05_numtot) || $dv05_numtot < 2 ) && $db_opcao != 22 && $db_opcao != 33 ){
  echo "<script>js_di();</script>";
}
?>
<script>

$("dv05_coddiver").addClassName("field-size2");
$("dv05_numcgm").addClassName("field-size2");
$("z01_nome").addClassName("field-size7");
$("dv05_dtinsc").addClassName("field-size2");
$("dv05_exerc").addClassName("field-size2");
$("dv05_procdiver").addClassName("field-size2");
$("dv09_descr").addClassName("field-size7");
$("dv05_privenc").addClassName("field-size2");
$("dv05_vlrhis").addClassName("field-size2");
$("i02_codigo").addClassName("field-size2");
$("dv05_oper").addClassName("field-size2");
$("dv05_valor").addClassName("field-size2");
$("dv05_numtot").addClassName("field-size2");
$("dv05_provenc").addClassName("field-size2");
$("dv05_diaprox").addClassName("field-size2");
</script>