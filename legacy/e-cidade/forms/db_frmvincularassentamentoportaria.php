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

$oDaoPortaria->rotulo->label('h31_numero');
$oDaoPortaria->rotulo->label('h31_anousu');
$oDaoPortaria->rotulo->label('h31_portariaassinatura');
$oDaoPortaria->rotulo->label('h31_dtportaria');
$oDaoPortaria->rotulo->label('h31_dtinicio');
$oDaoPortaria->rotulo->label('h31_dtlanc');
$oDaoPortaria->rotulo->label('h31_amparolegal');
$oDaoPortariaassinatura->rotulo->label('rh136_nome');
$oDaoAssentamento->rotulo->label('h16_codigo');

$db_opcao_nro_portaria = 3;
if(trim($sEsconderNumeracaoPortaria) == '') {
  $db_opcao_nro_portaria = $db_opcao;
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, strings.js, prototype.js, AjaxRequest.js, DBLookUp.widget.js, geradorrelatorios.js, estilos.css");
    ?>
    <style>
      .separator > table > tbody > tr > td:first-child {
        width: 160px;
      }
      textarea {
        resize: none
      }
    </style>
  </head>
  <body>

    <form id="form1" name="form1" action="" method="POST" class="container">
     <fieldset>
        <legend>Vinculação de Portaria a Assentamentos</legend>

        <fieldset class="field-portaria separator">

          <legend>Dados da Portaria</legend>
          <table class="form-container">

            <tr <?php echo $sEsconderNumeracaoPortaria; ?>>
              <td nowrap title="<?=@$Th31_numero?>">
                 <?=@$Lh31_numero?>
              </td>
              <td>
                <?php db_input('h31_numero',10,$Ih31_numero,true,'text',$db_opcao_nro_portaria," onChange='js_configuraNumeroAto();'") ?>
              </td>
            </tr>

            <tr <?php echo $sEsconderNumeracaoPortaria; ?>>
              <td nowrap title="<?=@$Th31_anousu?>">
                <?=@$Lh31_anousu?>
              </td>
              <td nowrap title="<?=@$Th31_dtportaria?>">
                <?php
                  if (!isset($h31_anousu) && trim(@$h31_anousu)==""){
                       $h31_anousu = db_getsession('DB_anousu');
                  }
                  db_input('h31_anousu',4,$Ih31_anousu,true,'text',$db_opcao_nro_portaria," onChange='js_configuraNumeroAto();'")
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
              <td nowrap title="<?=@$Th31_amparolegal?>" colspan="6">
                <fieldset class="field-amparo-legal">
                  <legend><?php echo $Lh31_amparolegal; ?></legend>
                  <?php db_textarea('h31_amparolegal',5,40,$Ih31_amparolegal,true,'text',$db_opcao, 'class="field-size-max"'); ?>
                </fieldset>
              </td>
            </tr>
          </table>
        </fieldset>

        <fieldset class="field-assinante separator">
          <legend>Assinante</legend>
          <table class="form-container">
            <tr>
              <td nowrap title="<?php echo $Th31_portariaassinatura; ?>">
                <a href="" id="lbl_h31_portariaassinatura">Código do Assinante:</a>
              </td>
              <td>
                <?php
                  db_input('h31_portariaassinatura',10,$Ih31_portariaassinatura,true,'text',$db_opcao, "lang=rh136_sequencial");
                  db_input("rh136_nome",60,$Irh136_nome,true,"text",3, 'class=field-size8');
                 ?>
              </td>
            </tr>
          </table>
        </fieldset>

        <fieldset class="field-assentamento separator">
          <legend>Assentamento</legend>
          <table class="form-container">
            <tr>
              <td nowrap title="<?php echo $Th16_codigo; ?>">
                <a href="" id="lbl_h16_codigo"><?php echo $Lh16_codigo; ?></a>
              </td>
              <td>
                <?php db_input('h16_codigo', 10, '', true, "text", 1); ?>
                <?php db_input('h12_descr', 60, '', true, "text", 3, 'class=field-size8'); ?>

                <?php
                  $h16_nrport = @$h31_anousu.'/'.@$h31_numero;
                  db_input('h16_nrport', 10, '', true, 'hidden', 3, "");
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="">Servidor:</td>
              <td>
                <?php db_input('h16_regist', 10, '', true, "text", 3, 'class=field-size2'); ?>
                <?php db_input('z01_nome', 60, '', true, "text", 3); ?>
                <?php db_input('h12_tipoasse', 60, '', true, "hidden", 3); ?>
              </td>
            </tr>

          </table>
        </fieldset>
      </fieldset>
      <input type="button" id="processar" name="processar" value="Processar" onclick="js_validacampos();" />
      <input type="button" id="imprimir" name="imprimir" value="Imprimir" onclick="js_emite();" disabled />
      <input type="button" id="novo" name="novo" value="Novo" onclick="location.href=location.href;" disabled />
    </form>

    <script type="text/javascript">

     "inicio";
      function js_validacampos () {

        try {

          if( !$F('h31_numero') ) {
            throw "Portaria não informada";
          }

          if( !$F('h31_dtportaria') ) {
            throw "Data da portaria não informada";
          }

          if( !$F('h31_dtinicio') ) {
            throw "Data de início da portaria não informada";
          }

          if( !$F('h31_dtlanc') ) {
            throw "Data de lançamento da portaria não informada";
          }

          if( !$F('h16_codigo') || !$F('h12_descr') ) {
            throw "Assentamento não selecionado";
          }
        } catch ( sErro ) {

          alert(sErro);
          return false;
        }
        $('form1').submit();
      }

      function js_configuraNumeroAto(){

        var iNumero = document.form1.h31_numero.value;
        var iAno    = document.form1.h31_anousu.value;

        document.form1.h16_nrport.value = iAno+"/"+iNumero;
      }

      function js_emite(){
   
        var sAcao   = "consultaPortarias";
        var sQuery  = "sAcao="+sAcao;
            sQuery += "&iPortariaInicial="+document.form1.h31_numero.value;
            sQuery += "&iPortariaFinal="+document.form1.h31_numero.value;
            
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

      var oAssentamento = new DBLookUp($('lbl_h16_codigo'), $('h16_codigo'), $('h12_descr'), {
        'sArquivo'              : 'func_assenta.php',
        'sObjetoLookUp'         : 'db_iframe_assenta',
        'sLabel'                : 'Pesquisar Assentamento',
        'aCamposAdicionais'     : ['z01_nome', 'h16_regist', 'h30_amparolegal'],
        'aParametrosAdicionais' : [
          'sOpcaoAssentamento=2',
          'retorna_objeto=true',
          'apenas_tipo_portaria=true',
          'vinculo_portaria=true',
          'retorna_amparo_legal=true',
        ]
      });

      oAssentamento.callBackChange = function( oRetorno, lErro ){

        if ( lErro ) {
          $('h16_codigo').value  = '';
          $('h12_descr').value   = '';
          $('z01_nome').value    = oRetorno;
          $('h16_regist').value  = '';
          return;
        }
        $('h16_codigo').value       = oRetorno.h16_codigo;
        $('h12_descr').value        = oRetorno.h12_descr;

        $('z01_nome').value         = oRetorno.z01_nome;
        $('h16_regist').value       = oRetorno.h16_regist;

        $('h31_amparolegal').value  = oRetorno.h30_amparolegal;
      };

      oAssentamento.callBackClick = function(iCodigoAssentamento, sDescricaoTipoAssentamento, sNome, iMatricula, sAmparoLegal ) {

        $('h16_codigo').value       = iCodigoAssentamento;
        $('h12_descr').value        = sDescricaoTipoAssentamento;
        $('z01_nome').value         = sNome;
        $('h16_regist').value       = iMatricula;
        $('h31_amparolegal').value  = sAmparoLegal;
        db_iframe_assenta.hide();
      };

      var oAssinatura = new DBLookUp($('lbl_h31_portariaassinatura'), $('h31_portariaassinatura'), $('rh136_nome'), {
        'sArquivo'              : 'func_portariaassinatura.php',
        'sObjetoLookUp'         : 'db_iframe_portariaassinatura',
        'sLabel'                : 'Pesquisar Assinante',
        'aCamposAdicionais'     : ['rh136_sequencial']
      });

    </script>
    <?php db_menu(); ?>
    <?php 
      if(!empty($_POST) && isset($lStatus) && $lStatus) { 

        echo "<script>

                document.form1.processar.disable();
                document.form1.imprimir.enable();
                document.form1.novo.enable();

                if(confirm(\"Deseja imprimir a portaria?\")) {
                  document.form1.imprimir.click();
                } else {
                  document.form1.novo.click();
                }
              </script>";
      }
    ?>
  </body>
</html>
