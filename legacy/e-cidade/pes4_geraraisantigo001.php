<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>

    <script type="text/javascript">

      var sMensagens = "recursoshumanos.pessoal.pes4_gerarais001.";

      function js_validaCampos() {

        if ($F('ano_base').trim() == '') {

          alert( _M( sMensagens + "campo_obrigatorio", { sCampo : "Ano/Mês (Base)" }) );
          return false;
        }

        if ($F('ano_base').trim() != '' && $F('ano_base') > 2012) {

          alert( _M( sMensagens + "ano_invalido", { sCampo : "2012" }) );
          $('ano_base').value = '';
          return false;
        }

        if ($F('mes_base').trim() == '') {

          alert( _M( sMensagens + "campo_obrigatorio", { sCampo : "Ano/Mês (Base)" }) );
          return false;
        }

        if ($('containerDataRetificacao').visible() && $F('dataretificacao').trim() == '') {

          alert( _M( sMensagens + "campo_obrigatorio", { sCampo : "Data de Retificação" }) );
          return false;
        }

        if ($F('codmun').trim() == '') {

          $('codmun').value = $F('codmun').trim();
          alert( _M( sMensagens + "campo_obrigatorio", { sCampo : "Código Município" }) );
          return false;
        }

        if ($F('nome_resp').trim() == '') {

          $('nome_resp').value = $F('nome_resp').trim();
          alert( _M( sMensagens + "campo_obrigatorio", { sCampo : "Nome" }) );
          return false;
        }

        if ($F('cpfr').trim() == '') {

          alert( _M( sMensagens + "campo_obrigatorio", { sCampo : "CPF" }) );
          return false;
        }

        if ( !js_verificaCGCCPF( $('cpfr') ) ){

          return false;
        }

        if ($F('datan').trim() == '') {

          alert( _M( sMensagens + "campo_obrigatorio", { sCampo : "Data de Nascimento" }) );
          return false;
        }

        return true;
      }

      function js_emite(){

        if (!js_validaCampos()) {
          return false;
        }

        qry  = 'ano_base='+ document.form1.ano_base.value;
        qry += '&mes_base='+ document.form1.mes_base.value;
        qry += '&obs=' + document.form1.obs.value;
        qry += '&cpfr=' + document.form1.cpfr.value;
        qry += '&cnpj_sind=' + document.form1.cnpj_sind.value;
        qry += '&w_sind=' + document.form1.w_sind.value;
        qry += '&cnpj_asso=' + document.form1.cnpj_asso.value;
        qry += '&w_asso=' + document.form1.w_asso.value;
        qry += '&datan=' + document.form1.datan_dia.value+document.form1.datan_mes.value+document.form1.datan_ano.value ;
        qry += '&w_extras=' + document.form1.w_extras.value;
        qry += '&codmun=' + document.form1.codmun.value;
        qry += '&nome_resp=' + document.form1.nome_resp.value;
        qry += '&r70_numcgm=' + document.form1.r70_numcgm.value;
        qry += '&retificacao=' + document.form1.retificacao.value;
        qry += '&dataretificacao=' + document.form1.dataretificacao.value;

       js_OpenJanelaIframe('top.corpo','db_iframe_gerarais','pes4_geraraisantigo002.php?'+qry,'Gerando Arquivo',true);
      }

      function js_erro(msg){

        top.corpo.db_iframe_gerarais.hide();
        alert(msg);
      }

      function js_fechaiframe(){
        db_iframe_gerarais.hide();
      }

      function js_controlarodape(mostra){

        if(mostra == true){

          document.form1.rodape.value = parent.bstatus.document.getElementById('st').innerHTML;
          parent.bstatus.document.getElementById('st').innerHTML = '&nbsp;&nbsp;<blink><strong><font color="red">GERANDO ARQUIVO</font></strong></blink>' ;
        }else{
          parent.bstatus.document.getElementById('st').innerHTML = document.form1.rodape.value;
        }
      }

      function js_detectaarquivo(arquivo,pdf){

        top.corpo.db_iframe_gerarais.hide();
        listagem = arquivo+"#Download Arquivo TXT |";
        listagem+= pdf+"#Download Relatório";
        js_montarlista(listagem,"form1");
      }

      function js_comportamentoTipo() {

        $('containerDataRetificacao').show()

        if ($F('retificacao') == '2') {

          $('dataretificacao').value = '';
          $('containerDataRetificacao').hide()
        }
      }

      function js_onlyNumbers(oObj) {
        oObj.value = oObj.value.replace(/[^0-9]/g, '')
      }
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

  <body class="body-default">
    <div class="container">

    <form name="form1" method="post" action="" >

        <fieldset>
          <legend>Geração RAIS (Layout Genérico 1976 a 2012)</legend>

          <table>
            <tr>
              <td nowrap title="Digite o Ano Base / Mês Base" >
                <label class="bold" for="ano_mes_base" id="lbl_ano_mes_base">Ano / Mês (Base):</label>
              </td>
              <td>
                <?php
                $ano_base = 2012;
                db_input('ano_base',4,'',true,'text',2, ' onBlur="js_onlyNumbers(this)" onKeyUp="js_onlyNumbers(this)"');
                ?>
                &nbsp;/&nbsp;
                      <?php
                $mes_base = 12;
                db_input('mes_base',2,'',true,'text',2,' onBlur="js_onlyNumbers(this)" onKeyUp="js_onlyNumbers(this)"');
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="RAIS Retificadora" >
                <label class="bold" for="retificacao" id="lbl_retificacao">Tipo:</label>
              </td>
              <td align="left">
                <?php
                  $aRetiricadora = array( "2"=>"Primeira Entrega",
                                          "1"=>"Retificadora" );
                  db_select('retificacao',$aRetiricadora, true, 4, "");
	              ?>
              </td>
            </tr>

            <tr id="containerDataRetificacao">
              <td nowrap>
                <label class="bold" for="dataretificacao" id="lbl_dataretificacao">Data da Retificação:</label>
            </td>
              <td>
                <?php db_inputdata("dataretificacao", '', '', '', true, 'text', 2); ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="Observação">
                <label class="bold" for="obs" id="lbl_obs">Observação:</label>
              </td>
              <td>
                <?php
                  db_input('obs', 15, '', true, 'text', 2, '');
                ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="Código Município" >
              <label class="bold" for="codcomun" id="lbl_codcomun">Código Município:</label>
            </td>
            <td align="left">
              <?php
                db_input('codmun',7,'',true,'text',2,'')
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="CNPJ">
              <label class="bold" for="r70_numcgm" id="lbl_r70_numcgm">CNPJ:</label>
            </td>
            <td>
              <?php
                $instit=db_getsession("DB_instit");

                $sql = "select distinct z01_numcgm,z01_cgccpf||'-'||z01_nome as z01_nome from rhlota inner join cgm on rhlota.r70_numcgm=cgm.z01_numcgm  where r70_instit=$instit;";
                $result= db_query($sql);

                db_selectrecord("r70_numcgm", $result, true, @$db_opcao, "", "", "", "0", "", "2");
              ?>
            </td>
          </tr>
          </table>

          <fieldset>
            <legend>Dados do Responsável</legend>

            <table>
              <tr >
                <td nowrap title="Nome do Responsável">
                  <label class="bold" for="nome_resp" id="lbl_nome_resp">Nome:</label>
               </td>
                <td>
                 <?php
                    db_input('nome_resp', 35, '', true, 'text', 2, '');
	               ?>
                </td>
              </tr>

              <tr>
                <td nowrap title="CPF do Responsável">
                  <label class="bold" for="cpfr" id="lbl_cpfr">CPF:</label>
                </td>
                <td>
                  <?php
                    db_input('cpfr', 11, '', true, 'text', 2, "  onBlur='js_onlyNumbers(this); js_verificaCGCCPF(this);' onKeyDown='return js_controla_tecla_enter(this,event);' onKeyUp='js_onlyNumbers(this)'");
                  ?>
                </td>
              </tr>

              <tr>
                  <td nowrap title="Data de Nascimento">
                    <label class="bold" for="datan" id="lbl_datan">Data de Nascimento:</label>
                  </td>
                  <td>
                   <?php
                      db_inputdata("datan", '', '', '', true, 'text', 2);
        	         ?>
                </td>
              </tr>
            </table>
          </fieldset>

      <fieldset>
            <legend>Rubricas</legend>

            <table>
              <tr>
                  <td nowrap title="Rubrica de Horas Extras" >
                    <label class="bold" for="w_extras" id="lbl_w_extras">Rubrica de Horas Extras:</label>
                  </td>
                  <td>
                    <?php
                      db_input('w_extras', 16, '', true, 'text', 2, '');
  	                ?>
                </td>
              </tr>
        </table>
      </fieldset>

    </td>
    <td>
      <fieldset>
        <legend><strong>Associativa</strong></legend>
        <table width="100%">
         <tr >
            <td nowrap title="Código Nacional de Pessoal Jurídica" >
              <label class="bold" for="cnpj_sind" id="lbl_cnpj_sind">CNPJ Sindical:</label>
         </td>
         <td>
            <?php
              db_input('cnpj_sind', 14, '', true, 'text', 2, " onBlur='js_onlyNumbers(this); js_verificaCGCCPF(this);' onKeyDown='return js_controla_tecla_enter(this,event);' onKeyUp='js_onlyNumbers(this)' ");
            ?>
         </td>

        <td nowrap title="Rubricas de Descontos">
          <label class="bold" for="w_sind" id="lbl_w_sind">Rubricas:</label>
        </td>
        <td>
          <?php
            db_input('w_sind', 16, '', true, 'text', 2, '');
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="Código Nacional de Pessoal Jurídica">
          <label class="bold" for="cnpj_asso" id="lbl_cnpj_asso">CNPJ Associativa:</label>
        </td>
        <td>
            <?php
              db_input('cnpj_asso', 14, '', true, 'text', 2, "  onBlur='js_onlyNumbers(this); js_verificaCGCCPF(this);' onKeyDown='return js_controla_tecla_enter(this,event);' onKeyUp='js_onlyNumbers(this)' ");
            ?>
        </td>

        <td nowrap title="Rubricas de Descontos">
          <label class="bold" for="w_asso" id="lbl_w_asso">Rubricas:</label>
        </td>
        <td>
          <?php
            db_input('w_asso', 16, '', true, 'text', 2, '');
          ?>
        </td>
      </tr>

      </table>

      </fieldset>

      </fieldset>

        <input  name="gera" id="gera" type="button" value="Processar" onclick="js_emite();" >
  </form>
    </div>
    <?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
  <script type="text/javascript">
    (function() {

      $('retificacao').observe('change', function() {
        js_comportamentoTipo();
      });

      js_comportamentoTipo()
    })()
  </script>
</html>