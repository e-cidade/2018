<?php
/**
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: recursoshumanos
$clportaria->rotulo->label();
$classenta->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("h12_descr");
$clrotulo->label("h12_assent");
$clrotulo->label("z01_nome");
$clrotulo->label("rh136_nome");

$sEsconderNumeracaoPortaria = '';

if ( !$lExibirNumeracaoPortaria ) {
  $sEsconderNumeracaoPortaria = 'style="display:none;"';
} 
?>
<center>
<form id="form1" name="form1" method="post" action="" class="container">

  <?php db_input('lExibirNumeracaoPortaria', 10, 0, true, 'hidden',3); ?>

  <?php db_input('db_opcao',10,$Ih16_codigo,true,'hidden',3,""); ?>
  <?php db_input(($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir")),10,$Ih16_codigo,true,'hidden',3,""); ?>
  <?php db_input('h80_db_cadattdinamicovalorgrupo',10,$Ih16_codigo,true,'hidden',3,""); ?>
  <?php db_input('codigo_assentamento', 10, 0, true, 'hidden',3); ?>

  <fieldset>

    <legend><b>Dados da portaria:</b></Legend>

    <table border="0" class="form-container">

      <tr style="display:none;">
        <td nowrap title="<?=@$Th31_sequencial?>"><b><?=$Lh31_sequencial?></b></td>
        <td> 
          <?php db_input('h31_sequencial',10,$Ih31_sequencial,true,'text',3); ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=@$Th31_portariatipo?>"><b>
        <? 
           db_ancora(@$Lh31_portariatipo,"js_pesquisa_h31_portariatipo(true)",$db_opcao); 
        ?>
        </b></td>
        <td> 
    <?
    db_input('h31_portariatipo',10,$Ih31_portariatipo,true,'text',$db_opcao,"onchange='js_pesquisa_h31_portariatipo(false)';");
    db_input("h12_descr",40,@$Ih12_descr,true,"text",3);
    ?>
        </td>
      </tr>
    <?
    if (!isset($h31_usuario) && trim(@$h31_usuario)==""){
         $h31_usuario = db_getsession('DB_id_usuario');
    }
    db_input('h31_usuario',10,$Ih31_usuario,true,'hidden',3);
    ?>

      <tr <?php echo $sEsconderNumeracaoPortaria; ?>>
        <td nowrap title="<?=@$Th31_numero?>">
           <?=@$Lh31_numero?>
        </td>
        <td> 
          <?php db_input('h31_numero',10,$Ih31_numero,true,'text',$db_opcao_numero," onChange='js_configuraNumeroAto();'") ?>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=@$Lh31_anousu?>
          <?php
            if (!isset($h31_anousu) && trim(@$h31_anousu)==""){
                 $h31_anousu = db_getsession('DB_anousu');
            }
            db_input('h31_anousu',4,$Ih31_anousu,true,'text',$db_opcao_numero," onChange='js_configuraNumeroAto();'")
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=@$Th31_dtportaria?>">
           <?=@$Lh31_dtportaria?>
        </td>
        <td> 
    <?
    db_inputdata('h31_dtportaria',@$h31_dtportaria_dia,@$h31_dtportaria_mes,@$h31_dtportaria_ano,true,'text',$db_opcao,"")
    ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Th31_dtinicio?>">
           <?=@$Lh31_dtinicio?>
        </td>
        <td> 
    <?
    db_inputdata('h31_dtinicio',@$h31_dtinicio_dia,@$h31_dtinicio_mes,@$h31_dtinicio_ano,true,'text',$db_opcao,"")
    ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Th31_dtlanc?>">
           <?=@$Lh31_dtlanc?>
        </td>
        <td> 
    <?
    db_inputdata('h31_dtlanc',@$h31_dtlanc_dia,@$h31_dtlanc_mes,@$h31_dtlanc_ano,true,'text',$db_opcao,"")
    ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Th31_amparolegal?>" colspan="2">
          <fieldset>
            <legend><?php echo $Lh31_amparolegal; ?></legend>
            <?php db_textarea('h31_amparolegal',5,40,$Ih31_amparolegal,true,'text',$db_opcao, 'class="field-size-max"'); ?>
          </fieldset>
        </td>
      </tr>
    </table>

  </fieldset>  

  <fieldset id="assinante">
    <legend>Assinante</legend>

    <table>
    
      <tr>
        <td nowrap title="<?php echo $Th31_portariaassinatura; ?>">
          <?php 
            db_ancora($Lh31_portariaassinatura,"js_pesquisa_Assinaturas(true)",$db_opcao); 
          ?>
        </td>
        <td> 
          <?php
            db_input('h31_portariaassinatura',10,$Ih31_portariaassinatura,true,'text',$db_opcao,"onchange='js_pesquisa_Assinaturas(false)';");
            db_input("rh136_nome",50,$Irh136_nome,true,"text",3);
          ?>
        </td>
      </tr>

    </table>
  </fieldset>

  <fieldset>
    <legend align="left"><b>Dados de assentamento:</b></Legend>
    <table border="0" class="form-container">
      <?
      if (($db_opcao == 1 || $db_opcao == 11) && !isset($h31_sequencial) && trim(@$h31_sequencial)==""){
           $db_opcao_assenta = 1;
      } else {
           $db_opcao_assenta = $db_opcao;
           if ($db_opcao == 1) {
                $campos  = "distinct h12_codigo";  
                $dbwhere = "h30_sequencial = ".@$h31_portariatipo;
           } elseif ( !empty($h31_sequencial) ) {
                $campos  = "h16_codigo,h16_regist,h16_assent,h16_dtconc,h16_histor,h16_nrport,h16_atofic,h16_quant,h16_perc,h16_dtterm,h16_hist2,h16_login,h16_dtlanc,h16_conver";  
                $dbwhere = "h31_sequencial = ".@$h31_sequencial;
                $res_portariaassenta = db_query($clportariaassenta->sql_query_file(null,"h33_assenta",null,"h33_portaria = ".@$h31_sequencial));            
                if ($clportariaassenta->numrows > 0){
                     db_fieldsmemory($res_portariaassenta,0);
                     $dbwhere .= " and h16_codigo = ".@$h33_assenta;
                }
           }
           if(!isset($h33_assenta) || trim($h33_assenta) == ''){
            $h33_assenta = '0';
           }

           $res_assenta = db_query($classenta->sql_query_file(@$h33_assenta));  

           if ($classenta->numrows > 0){
                db_fieldsmemory($res_assenta,0);

                if (isset($h12_codigo) && trim($h12_codigo)!=""){
                     $h16_assent = $h12_codigo;
                }

                $res_rhpessoal = db_query($clrhpessoal->sql_query(null,"z01_nome","rh01_regist"," rh01_regist = $h16_regist"));
                if ($clrhpessoal->numrows > 0){
                     db_fieldsmemory($res_rhpessoal,0);
                }
           }
      }

      /**
       * Esconde campo com código do assentamento quando for inclusao
       * quando campo estiver vazio 
       */
      $sEsconderCodigoAssentamento = '';

      if ( empty($h16_codigo) ) {
        $sEsconderCodigoAssentamento = 'style="display:none;"';
      }
      ?>

      <tr <?php echo $sEsconderCodigoAssentamento; ?>>
        <td nowrap title="<?=@$Th16_codigo?>"><?=$Lh16_codigo?></td>
        <td>
          <?php db_input('h16_codigo',10,$Ih16_codigo,true,'text',3,""); ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=@$Th16_regist?>">
          <?
          db_ancora(@$Lh16_regist,"js_pesquisah16_regist(true);",$db_opcao_assenta);
          ?>
        </td>
        <td> 
          <?php 
            db_input('h16_regist', 10, $Ih16_regist,true,'text',$db_opcao_assenta," onchange='js_pesquisah16_regist(false);'");
            db_input('z01_nome',47,$Iz01_nome,true,'text',3); 
          ?>
        </td>
      </tr>
      <?
          db_input('h16_assent',6,$Ih16_assent,true,'hidden',3,"")
      ?>
      <tr>
        <td nowrap title="<?=@$Th16_dtconc?>">
          <?=@$Lh16_dtconc?>
        </td>
        <td> 
          <?php db_inputdata('h16_dtconc',@$h16_dtconc_dia,@$h16_dtconc_mes,@$h16_dtconc_ano,true,'text',$db_opcao_assenta,"onchange='js_somar_dias(document.form1.h16_quant.value, 0)'","","","parent.js_somar_dias(parent.document.form1.h16_quant.value, 0)"); ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Th16_dtterm?>">
          <?=@$Lh16_dtterm?>
        </td>
        <td> 
          <?php db_inputdata('h16_dtterm',@$h16_dtterm_dia,@$h16_dtterm_mes,@$h16_dtterm_ano,true,'text',$db_opcao_assenta,"onchange='js_somar_dias(0, 3)'","","","parent.js_somar_dias(0, 3)"); ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Th16_quant?>">
          <?=@$Lh16_quant?>
        </td>
        <td> 
          <?php db_input('h16_quant', 10, $Ih16_quant, true, 'text', $db_opcao_assenta, "onchange='js_somar_dias(this.value, 1);'"); ?>
          <?php db_input('h12_natureza', 10, '', true, 'hidden', $db_opcao_assenta, "", ""); ?>
          <?php db_input('h12_codigo', 10, '', true, 'hidden', $db_opcao_assenta, "", ""); ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo $Th16_atofic; ?>">
          <?php echo $Lh16_atofic; ?>
        </td>
        <td> 
          <?php
            $h16_nrport = @$h31_anousu.'/'.@$h31_numero;
            db_input('h16_nrport', 10, $Ih16_nrport, true, 'hidden', 3, "");

            db_input('h16_atofic',12,$Ih16_atofic,true,'text',$db_opcao_assenta,"class='field-size-max'");
          ?>
        </td>
      </tr>
      
           <tr>
             <td>
               Assentamento de:
             </td>
             <td>
               <?php 
                 $aOpcaoAssentamento = array(2=>'Histórico Funcional');
                 $sOpcaoAssentamento = 2;
                 db_select('sOpcaoAssentamento', $aOpcaoAssentamento, true, 3, "", "", "") ?>
             </td>
           </tr>
          <tr>
        <td nowrap title="<?=@$Th16_histor?>" colspan="2">
          <fieldset>
            <legend><?php echo $Lh16_histor; ?></Legend>
            <?php db_textarea('h16_histor',5,47,$Ih16_histor,true,'text',$db_opcao_assenta,"class='field-size-max'"); ?>
          </fieldset>
        </td>
      </tr>
    </table>
    
    <div id="conteudoCamposAdicionais"></div>

    </fieldset>  

    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" onclick="return js_valida();" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >

    <?php if ( $db_opcao != 1 ) : ?>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    <?php else : ?>
      <input name="novo"      type="button" id="novo"      value="Novo"      onclick="js_reLoad();" >
    <?php endif; ?>

    <input name="imprimir"  type="button" id="imprimir"  value="Imprimir"  onclick="js_emite();" <?=($db_opcao==1?"disabled":"")?>>


  </form>
  <div style="width: 475px" id="campos_adicionais"></div>
      
</center>
<script type="text/javascript">

function js_valida(){
  var iPortariaAssinatura = $F('h31_portariaassinatura');

  if (iPortariaAssinatura != '' && (parseInt(iPortariaAssinatura) != iPortariaAssinatura)) {
    alert('Campo Código da assinatura deve ser preenchido somente com numeros');
    return false;
  }

  if (document.form1.h16_dtconc.value.trim() == "") {
    alert("Informe a data inicial do assentamento");
    return false;
  }

  return true;
}


function js_imprimeConf(){

  document.form1.imprimir.disabled = false;
  
  if (confirm('Imprimir a Portaria ?')) {
    js_emite();
  }
  
}


function js_emite(){
   
  var sAcao   = "consultaPortarias";
  var sQuery  = "sAcao="+sAcao;
      sQuery += "&iPortariaInicial="+document.form1.h31_numero.value;
      sQuery += "&iPortariaFinal="+document.form1.h31_numero.value;
      sQuery += "&iAnoUsu="+document.form1.h31_anousu.value;
  		
  var url     = "rec1_portariasRPC.php";
  var oAjax   = new Ajax.Request( url, {
                                         method: 'post', 
                                         parameters: sQuery,
                                         onComplete: js_retornoEmite
                                       }
                                );
}

function js_retornoEmite(oAjax){
	
   var aRetorno = eval("("+oAjax.responseText+")");
	
   if (aRetorno.erro == true) {
	   alert(aRetorno.msg.urlDecode());
	   return false;
   } else {
     js_imprimeRelatorio(aRetorno.iModIndividual,js_downloadArquivo,aRetorno.aParametros.toSource());
   }

}


function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_portaria','func_portaria.php?filtro_lotacao=true&funcao_js=parent.js_preenchepesquisa|h31_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_portaria.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($_SERVER["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_pesquisa_h31_portariatipo(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_portariatipo','func_portariatipodescrato.php?funcao_js=parent.js_mostrah31_portariatipo1|h30_sequencial|h12_descr|h30_amparolegal|h41_descr|h12_natureza|h12_codigo','Pesquisa',true);
  }else{
     if(document.form1.h31_portariatipo.value != ''){ 
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_portariatipo','func_portariatipodescrato.php?pesquisa_chave='+document.form1.h31_portariatipo.value+'&funcao_js=parent.js_mostrah31_portariatipo','Pesquisa',false);
     }else{
       document.form1.h31_portariatipo.value = ''; 
     }
  }
}

function js_mostrah31_portariatipo(chave1,erro,chave2,chave3,chave4,chave5,chave6){

  if(erro==true){ 

    document.form1.h31_portariatipo.value = ''; 
    document.form1.h16_atofic.value       = '';
    document.form1.h31_portariatipo.focus();
    document.form1.h12_natureza.value         = ''; 
  } else {

    document.form1.h31_portariatipo.value                = chave1; 
    document.form1.h12_descr.value                       = chave2;
    document.form1.h16_atofic.value                      = chave4; 
    document.form1.h12_natureza.value       = chave5;
    document.form1.h12_codigo.value         = chave6;

    if (document.form1.h31_amparolegal.value == ""){
      document.form1.h31_amparolegal.value = chave3;
    }

    js_criarCamposAdicionais();
  }
}

function js_mostrah31_portariatipo1(chave1,chave2,chave3,chave4,chave5, chave6){

   document.form1.h31_portariatipo.value              = chave1; 
   document.form1.h12_descr.value                     = chave2; 
   document.form1.h16_atofic.value                    = chave4;
   document.form1.h12_natureza.value     = chave5;
   document.form1.h12_codigo.value       = chave6;

   if (document.form1.h31_amparolegal.value == ""){
        document.form1.h31_amparolegal.value = chave3;
   }
   
   renderizarFormulario();
   db_iframe_portariatipo.hide();

   js_criarCamposAdicionais();
}
function js_pesquisah16_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?filtro_lotacao=true&funcao_js=parent.js_mostrarhpessoal1|rh01_regist|z01_nome','Pesquisa',true);
  }else{
    if(document.form1.h16_regist.value != ''){ 
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?filtro_lotacao=true&pesquisa_chave='+document.form1.h16_regist.value+'&funcao_js=parent.js_mostrarhpessoal','Pesquisa',false);
    }else{
      document.form1.z01_nome.value = ''; 
    }
  }
}
function js_mostrarhpessoal(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.h16_regist.focus(); 
    document.form1.h16_regist.value = ''; 
  }
}
function js_mostrarhpessoal1(chave1,chave2){
  document.form1.h16_regist.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_rhpessoal.hide();
}
function js_somar_dias(valor, opcao){
  
  /* valaux = new Number(valor);
  alert(valor+' - '+valaux)
  if (valaux == 0) {
    h16_dtterm
    document.form1.h16_dtterm_dia.value = '';
    document.form1.h16_dtterm_mes.value = '';
    document.form1.h16_dtterm_ano.value = '';
    document.form1.h16_dtterm.value = '';
    document.form1.h16_quant.value = '';
//    return false;
  }*/

  diai = new Number(document.form1.h16_dtconc_dia.value);
  mesi = new Number(document.form1.h16_dtconc_mes.value);
  anoi = new Number(document.form1.h16_dtconc_ano.value);

  diaf = new Number(document.form1.h16_dtterm_dia.value);
	diaf++; 
  mesf = new Number(document.form1.h16_dtterm_mes.value);
  anof = new Number(document.form1.h16_dtterm_ano.value);

  if(diai != 0 && mesi != 0 && anoi != 0 && valor != "" && opcao != 3){
    valor = new Number(valor);
    data  = new Date(anoi , (mesi - 1), (diai + valor - 1));

    dia = data.getDate();
    mes = data.getMonth() + 1;
    ano = data.getFullYear();

    document.form1.h16_quant.value = valor;
    document.form1.h16_dtterm_dia.value = dia < 10 ? "0" + dia : dia;
    document.form1.h16_dtterm_mes.value = mes < 10 ? "0" + mes : mes;
    document.form1.h16_dtterm_ano.value = ano;
    document.form1.h16_dtterm.value = document.form1.h16_dtterm_dia.value+'/'+document.form1.h16_dtterm_mes.value+'/'+document.form1.h16_dtterm_ano.value;

		document.form1.h16_dtterm.value = (dia < 10 ? "0" + dia : dia)+'/'+(mes < 10 ? "0" + mes : mes)+'/'+ano;
  }else if(diai != 0 && mesi != 0 && anoi != 0 && diaf != 0 && mesf != 0 && anof != 0 && opcao == 3){
    datai  = new Date(anoi , (mesi - 1), diai);
    dataf  = new Date(anof , (mesf - 1), diaf);

    datad = (dataf - datai) / 86400000;
    document.form1.h16_quant.value = datad.toFixed();
    document.form1.h16_quant.value = datad.toFixed();

		if (datad.toFixed() <= 0){
			alert('A data final nao pode ser menor que a data inicial');			
      document.form1.h16_dtterm_dia.value = '';
      document.form1.h16_dtterm_mes.value = '';
      document.form1.h16_dtterm_ano.value = '';
      document.form1.h16_dtterm.value     = '';
      document.form1.h16_dtterm.focus();
      document.form1.h16_quant.value      = '';
      document.form1.h16_quant.value     = '';
			return false;
		}

    ano = datad / 365;
    ano = ano.toFixed();
    mes = (datad - (ano * 365)) / 30;
    mes = mes.toFixed();
    dia = datad - (ano * 365) - (mes * 30);
    dia = dia.toFixed();

    if(document.form1.valor_dia){
      document.form1.valor_dia.value = dia;
      document.form1.valor_mes.value = mes;
      document.form1.valor_ano.value = ano;
      document.form1.valor.value = dia+'/'+mes+'/'+ano;
    }
  }else if(opcao == 2){
    alert("Informe a data inicial!");
//    document.form1.h16_dtconc_dia.focus();
//    document.form1.h16_dtconc_dia.select();
    document.form1.h16_dtconc.focus();
    document.form1.h16_dtconc.select();
    document.form1.h16_quant.value = "";
  }

  if (document.form1.h16_dtterm.value == '') {
    document.form1.h16_quant.value = "0";
    document.form1.h16_quant.value = "0";
        
  }
  
  quant_dias = new Number(document.form1.h16_quant.value);
  if(quant_dias == 0){
    document.form1.h16_dtterm_dia.value = '';
    document.form1.h16_dtterm_mes.value = '';
    document.form1.h16_dtterm_ano.value = '';
    document.form1.h16_dtterm.value = '';
  }



}
function js_somar_dias_ant(valor, opcao){
  diai = new Number(document.form1.h16_dtconc_dia.value);
  mesi = new Number(document.form1.h16_dtconc_mes.value);
  anoi = new Number(document.form1.h16_dtconc_ano.value);

  diaf = new Number(document.form1.h16_dtterm_dia.value);
  mesf = new Number(document.form1.h16_dtterm_mes.value);
  anof = new Number(document.form1.h16_dtterm_ano.value);
  if(diai != 0 && mesi != 0 && anoi != 0 && valor != "" && opcao != 3){
    valor = new Number(valor);
    data  = new Date(anoi , (mesi - 1), (diai + valor - 1));

    dia = data.getDate();
    mes = data.getMonth() + 1;
    ano = data.getFullYear();

    document.form1.h16_quant.value = valor;
    document.form1.h16_dtterm_dia.value = dia < 10 ? "0" + dia : dia;
    document.form1.h16_dtterm_mes.value = mes < 10 ? "0" + mes : mes;
    document.form1.h16_dtterm_ano.value = ano;
  }else if(diai != 0 && mesi != 0 && anoi != 0 && diaf != 0 && mesf != 0 && anof != 0 && opcao == 3){
    datai  = new Date(anoi , (mesi - 1), diai);
    dataf  = new Date(anof , (mesf - 1), diaf);

    datad = (dataf - datai) / 86400000;
    document.form1.h16_quant.value = datad.toFixed();
    document.form1.h16_quant.value = datad.toFixed();

    ano = datad / 365;
    ano = ano.toFixed();
    mes = (datad - (ano * 365)) / 30;
    mes = mes.toFixed();
    dia = datad - (ano * 365) - (mes * 30);
    dia = dia.toFixed();

    if(document.form1.valor_dia){
      document.form1.valor_dia.value = dia;
      document.form1.valor_mes.value = mes;
      document.form1.valor_ano.value = ano;
    }
  }else if(opcao == 2){
    alert("Informe a data inicial!");
    document.form1.h16_dtconc_dia.focus();
    document.form1.h16_dtconc_dia.select();
    document.form1.h16_quant.value = "";
  }
}

function js_configuraNumeroAto(){
 
  var iNumero = document.form1.h31_numero.value;
  var iAno    = document.form1.h31_anousu.value;
  
  document.form1.h16_nrport.value = iAno+"/"+iNumero;

}

function js_reLoad(){
 <?php echo " location.href = '".basename($_SERVER["REQUEST_URI"])."';"; ?>
}

js_configuraNumeroAto();

var oToggleAssinante = new DBToogle('assinante', false);

function js_pesquisa_Assinaturas(lMostra) {

  var sUrl         = "func_portariaassinatura.php",
      sQueryString = "?funcao_js=parent.js_mostraAssinatura";

  if ( lMostra ) {

    sQueryString += "|rh136_sequencial|rh136_nome";
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_portariaassinatura', sUrl + sQueryString,'Pesquisa',true);
  } else {

    if ( $F("h31_portariaassinatura") != '') { 

      sQueryString += "&pesquisa_chave=" + $F("h31_portariaassinatura");
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_portariaassinatura', sUrl + sQueryString,'Pesquisa',false);
    } else {
      $("rh136_nome").value = "";
    }
  }
}

function js_mostraAssinatura(chave1, chave2) {
  
  var iCodigo = "",
      sNome   = "";

  if (chave1 != "" && typeof chave2 == "string") {

    iCodigo = chave1;
    sNome   = chave2;
  }

  if ( typeof chave1 == "string" && typeof chave2 == "boolean" ) {
    iCodigo = $F("h31_portariaassinatura");
    sNome = chave1;
  }

  $("h31_portariaassinatura").value = iCodigo;
  $("rh136_nome").value = sNome;

  db_iframe_portariaassinatura.hide();
  return;
}

/**
   * Cria campos adicionais na tela conforme natureza do assentamento
   * @return void
   */

  function js_criarCamposAdicionais(iCodigoAssentamento) {

    $('conteudoCamposAdicionais').innerHTML = '';

    require_once("scripts/classes/recursoshumanos/TipoAssentamentoFactory.js");

    if( !$F('h16_codigo') ) {
      var oTipoAssentamento = TipoAssentamentoFactory.createFromTipoPortaria( $F('h31_portariatipo') );
    } else {
      var oTipoAssentamento = TipoAssentamentoFactory.createFromAssentamento( $F('h16_codigo') );
    }
    if ( !oTipoAssentamento ) {
      return;
    }
    oTipoAssentamento.setDestino($('conteudoCamposAdicionais'));
    oTipoAssentamento.show();

    return;
  }


</script>

<script>
  require_once("scripts/classes/DBViewCadastroAtributoDinamico.js");
  require_once("scripts/classes/DBViewLancamentoAtributoDinamico.js");
  require_once("scripts/datagrid.widget.js"); 
  require_once("scripts/widgets/dbcomboBox.widget.js");     
  require_once("scripts/widgets/dbmessageBoard.widget.js"); 
  require_once("scripts/widgets/dbtextField.widget.js");    
  require_once("scripts/widgets/dbtextFieldData.widget.js");
  require_once("scripts/widgets/windowAux.widget.js");      

  function renderizarFormulario() {

    require_once("scripts/AjaxRequest.js");
    
    var oAjaxRequest = new AjaxRequest(
      'rec1_assentamentoatributosdinamicos.RPC.php', 
      {
        sAcao         : 'getDadosPortaria', 
        iTipoPortaria : $F('h31_portariatipo'),
        iCodigoAssentamento: $F('h16_codigo')
      },
      js_retornoAtributos
    );

    oAjaxRequest.setMessage('Definindo Valores Dinâmicos...');
    oAjaxRequest.asynchronous(false);
    oAjaxRequest.execute();
  }

  $('h31_portariatipo').observe("change", renderizarFormulario);
  $('h12_descr').observe("change", renderizarFormulario);

  var fjs_valida = js_valida;

  js_valida = function() {

    if ( !fjs_valida() ) {
      return false;
    }

    if ( oAtributoDinamico ) {

      oAtributoDinamico.setSaveCallBackFunction(salvar);
      oAtributoDinamico.save();
      return false;
    } else {
      return true;
    }
  }

  function js_retornoAtributos( oAjaxResponse ) {

    if ( !oAjaxResponse.iCodigoGrupo && !oAjaxResponse.iCodigoFormulario ) {
      $('campos_adicionais').innerHTML = "";
      oAtributoDinamico = null;
      return;
    }

    oAtributoDinamico = new DBViewLancamentoAtributoDinamico();
    oAtributoDinamico.setAlignForm('left');
    oAtributoDinamico.setParentNode($('campos_adicionais'));


    if ( oAjaxResponse.iCodigoGrupo ) {
      oAtributoDinamico.loadAttribute(oAjaxResponse.iCodigoGrupo);
    } else { 
      oAtributoDinamico.newAttribute(oAjaxResponse.iCodigoFormulario);
    }

    $('codigo_assentamento').value = oAjaxResponse.iAssenta;

    oAtributoDinamico.showForm();
  }

  function salvar(iCodigo) {

    $('h80_db_cadattdinamicovalorgrupo').value = iCodigo;
    document.form1.submit();
  }

  if ( $F('h80_db_cadattdinamicovalorgrupo') ) {
    oAtributoDinamico = new DBViewLancamentoAtributoDinamico();
    oAtributoDinamico.setAlignForm('left'); 
    oAtributoDinamico.setParentNode($('campos_adicionais'));
    oAtributoDinamico.loadAttribute($F('h80_db_cadattdinamicovalorgrupo'));
  }
</script>
    