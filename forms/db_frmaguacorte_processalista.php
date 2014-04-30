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

  //MODULO: agua
  include("dbforms/db_classesgenericas.php");
  $claguacorte->rotulo->label();
  $clrotulo = new rotulocampo;
  $clrotulo->label("x41_dtprazo");
  $clrotulo->label("x43_codsituacao");
  $clrotulo->label("x43_descr");
  $clrotulo->label("x01_codrua");
  $clrotulo->label("j14_nome");
  $clrotulo->label("x01_zona");
  $clrotulo->label("j50_descr");
  $clrotulo->label("x01_entrega");
  $clrotulo->label("j85_descr");
  $clrotulo->label("dti");
  $clrotulo->label("dtf");
  $db_opcao = 1;
?>
<fieldset style="margin-top: 50px; padding-top: 10px;">
  <legend><b>Imprime lista de corte</b></legend>
  <form name="form1" method="post" action="">
      <table border="0">
        <tr>
          <td nowrap title="<?=@$Tx40_codcorte?>">
            <input name="acao" type= "hidden" value ="<?=$acao ?>" >
            <?
              db_ancora(@$Lx40_codcorte, "js_pesquisax40_codcorte(true);", $db_opcao);
            ?>
          </td>
          <td> 
            <?
              db_input('x40_codcorte', 10, $Ix40_codcorte, true, 'text', $db_opcao,
                " onchange='js_pesquisax40_codcorte(false);'");
              
              db_input('x40_dtinc', 10, $Ix40_dtinc, true, 'text', 3, '');
            ?>
          </td>
        </tr>
        <?
          if (@$acao == 'gerar') {
        ?>
          <tr>
            <td nowrap title="<?=@$Tx41_dtprazo?>">
              <?=@$Lx41_dtprazo?>
            </td>
            <td> 
              <?
							  $x41_dtprazo = db_getsession("DB_datausu");
  							$x41_dtprazo_dia = date("d", $x41_dtprazo);
	  						$x41_dtprazo_mes = date("m", $x41_dtprazo);
		            $x41_dtprazo_ano = date("Y", $x41_dtprazo);

                db_inputdata('x41_dtprazo', @$x41_dtprazo_dia, @$x41_dtprazo_mes, @$x41_dtprazo_ano,
                  true, 'text', $db_opcao,"")
              ?>
            </td>
          </tr>
        <?
          } else {
        ?>
          <tr>
            <td nowrap title="<?=@$Tx43_codsituacao?>">
              <?
                db_ancora(@$acao=='reprocessar'?"<strong>Situa&ccedil;&atilde;o Regulariza&ccedil;&atilde;o:</strong>"
                :@$Lx43_codsituacao, "js_pesquisax43_codsituacao(true);", $db_opcao);
              ?>
            </td>
            <td> 
              <?
                db_input('x43_codsituacao', 5, $Ix43_codsituacao, true, 'text', $db_opcao,
                  " onchange='js_pesquisax43_codsituacao(false);' ");

                db_input('x43_descr',40,$Ix43_descr,true,'text',3,'');
              ?>
            </td>
          </tr>
          <?
            if (@$acao == 'reprocessar') {
          ?>
            <tr>
              <td nowrap title="Situacao Nao Regularizacao">
                <?
                  db_ancora("<strong>Situa&ccedil;&atilde;o N&atilde;o Regulariza&ccedil;&atilde;o:</strong>",
                  "js_pesquisax43_codsituacao2(true);", $db_opcao);
                ?>
              </td>
              <td> 
                <?
                  db_input('x43_codsituacao2', 5, $Ix43_codsituacao, true, 'text', $db_opcao,
                    " onchange='js_pesquisax43_codsituacao2(false);' ");

                  db_input('x43_descr2', 40, $Ix43_descr, true, 'text', 3, '');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="Situacao Recibo Emitido">
                <?
                  db_ancora("<strong>Situa&ccedil;&atilde;o Recibo Emitido:</strong>",
                    "js_pesquisax43_codsituacao3(true);",$db_opcao);
                ?>
              </td>
              <td> 
                <?
                  db_input('x43_codsituacao3', 5, $Ix43_codsituacao, true, 'text', $db_opcao,
                    " onchange='js_pesquisax43_codsituacao3(false);' ");

                  db_input('x43_descr3', 40, $Ix43_descr, true, 'text', 3, '');
                ?>
              </td>
            </tr>
           <?
            }
         }

         if (@$acao == 'imprimir') {
        ?>
          <tr>
            <td nowrap title="<?=@$Tx01_codrua?>">
              <?
                db_ancora(@$Lx01_codrua,"js_pesquisax01_codrua(true);",$db_opcao);
              ?>
            </td>
            <td> 
              <?
                db_input('x01_codrua', 7, $Ix01_codrua, true, 'text', $db_opcao,
                  " onchange='js_pesquisax01_codrua(false);'");
                db_input('j14_nome', 40, $Ij14_nome, true, 'text', 3, '');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tx01_zona?>">
              <?
                db_ancora(@$Lx01_zona, "js_pesquisax01_zona(true);", $db_opcao);
              ?>
            </td>
            <td> 
              <?
                db_input('x01_zona', 7, $Ix01_zona, true, 'text', $db_opcao, " onchange='js_pesquisax01_zona(false);'");
                db_input('j50_descr', 40, $Ij50_descr, true, 'text', 3, '');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tx01_entrega?>">
              <?
                db_ancora(@$Lx01_entrega, "js_pesquisax01_entrega(true);", $db_opcao);
              ?>
            </td>
            <td> 
              <?
                db_input('x01_entrega', 7, $Ix01_entrega, true, 'text',
                  $db_opcao, " onchange='js_pesquisax01_entrega(false);'");
                db_input('j85_descr', 40, $Ij85_descr, true, 'text', 3, '');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap>
              <strong>Data inicial</strong>
            </td>
            <td> 
              <?
                db_inputdata('dti', @$dti_dia, @$dti_mes, @$dti_ano, true, 'text', $db_opcao, "")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap>
              <strong>Data final</strong>
            </td>
            <td> 
              <?
                db_inputdata('dtf', @$dtf_dia, @$dtf_mes, @$dtf_ano, true, 'text', $db_opcao, "")
              ?>
            </td>
          </tr>
          <tr>
            <td align="left" height="25">
              <strong>Histórico:&nbsp;</strong>
            </td>
            <td>
              <? 
                $xy = array ("n" => "Não", "s" => "Sim");
                db_select('historico', $xy, true, 1);
              ?>      
            </td>
          </tr>  
		      <tr>
            <td align="left" height="25">
              <strong>Somente último histórico:&nbsp;</strong>
            </td>
  	        <td>
              <?	
                $xy = array ("s" => "Sim", "n" => "Não");
                db_select('ultimohistorico', $xy, true, 1);
              ?>      
            </td>
          </tr>
          <tr>
            <td align="left" height="25">
              <strong>Última leitura:&nbsp;</strong>
            </td>
            <td>
              <? 
                $xy = array ("n" => "Não", "s" => "Sim");
                db_select('ultimaleitura', $xy, true, 1);
              ?>      
            </td>
          </tr>
		      <tr>
            <td align="left" height="25">
              <strong>Hidrômetros:&nbsp;</strong>
            </td>
  	        <td>
              <?	
                $xy = array ("t" => "Todos", "c" => "Com", "s" => "Sem");
                db_select('hidrometros', $xy, true, 1);
              ?>      
            </td>
          </tr>
		      <tr>
            <td align="left" height="25">
              <strong>Quebrar página por logradouro:&nbsp;</strong>
            </td>
  	        <td>
              <?	
                $xy = array ("n" => "Não", "s" => "Sim");
                db_select('quebrarlograd', $xy, true, 1);
              ?>      
            </td>
          </tr>
          <tr>
            <td align="left" height="25">
              <strong>Quebrar página por zona de entrega:&nbsp;</strong>
            </td>
  	        <td>
              <?	
                $xy = array ("n" => "Não", "s" => "Sim");
                db_select('quebrarentrega', $xy, true, 1);
              ?>      
            </td>
          </tr>
        <?
          }
        ?>
        <tr> 
          <td height="25">&nbsp;</td>
          <td height="25"> 
            <input name="processa"  type="submit" id="processa" value=<?=($acao == 'imprimir')?'"Imprime"':'"Processa"'?> 
              onclick=<?=($acao == 'imprimir')?'"return js_emite();"':'"return js_processa();"'?> > 
          </td>
        </tr>
      </table>
    </form>
  </fieldset>
<script>
  function js_validax43_codsituacao() {
	  if (document.form1.x43_codsituacao.value == '') {
		  alert('Voce deve selecionar uma situacao!');
		  document.form1.x43_codsituacao.focus();
		  return false;
		}
	  return true;
  }

  function js_processa() {
	  //
    if(document.form1.x40_codcorte.value == '') {
      alert('Voce deve informar um Procedimento de Corte para Processar!');
    	document.form1.x40_codcorte.focus();

    } else if (document.form1.x43_codsituacao.value == '') {
      alert('Voce deve informar Situação para Regularização!');
      document.form1.x43_codsituacao.focus();

    } else if (document.form1.x43_codsituacao2.value == '') {
      alert('Voce deve informar Situação para Não Regularização!');
      document.form1.x43_codsituacao2.focus();

    } else if (document.form1.x43_codsituacao3.value == '') {
      alert('Voce deve informar Situação para Recibo Emitido!');
      document.form1.x43_codsituacao3.focus();

    } else {
      return confirm('Tem certeza que deseja executar o procedimento?');
    }
    return false;

  }

  function js_emite() {

    if (document.form1.dti_ano.value != '' && document.form1.dti_mes.value != '' && document.form1.dti_dia.value != '') {
      dtini = document.form1.dti_ano.value + '-' + document.form1.dti_mes.value + '-' + document.form1.dti_dia.value;
    } else {
      dtini = '';
    }

    if (document.form1.dtf_ano.value != '' && document.form1.dtf_mes.value != '' && document.form1.dtf_dia.value != '') {
      dtfim = document.form1.dtf_ano.value + '-' + document.form1.dtf_mes.value + '-' + document.form1.dtf_dia.value;
    } else {
      dtfim = '';
    }

    if (js_validax43_codsituacao() == false) {
      return false;
    }

    jan = window.open('agu4_aguacorte_processalista002.php?x40_codcorte=' + document.form1.x40_codcorte.value + 
      '&x43_codsituacao=' + document.form1.x43_codsituacao.value +
      '&x43_descr='       + document.form1.x43_descr.value + 
      '&x01_codrua='      + document.form1.x01_codrua.value +
      '&dtini='           + dtini +
      '&dtfim='           + dtfim +
      '&ultimohistorico=' + document.form1.ultimohistorico.value +
      '&x01_zona='        + document.form1.x01_zona.value +
      '&x01_entrega='     + document.form1.x01_entrega.value +
      '&hidrometros='     + document.form1.hidrometros.value +
      '&quebrarlograd='   + document.form1.quebrarlograd.value +
      '&quebrarentrega='  + document.form1.quebrarentrega.value + 
      '&opcaohistorico='  + document.form1.historico.value +
      '&opcaoleitura='    + document.form1.ultimaleitura.value,
      '','width=' + (screen.availWidth - 5) + ',height='+(screen.availHeight - 40) + ',scrollbars=1,location=0 ');

    jan.moveTo(0,0);

    return true; 
  }

  function js_pesquisax40_codcorte(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo', 'db_iframe_aguacorte', 'func_aguacorte.php?funcao_js=parent.js_mostraaguacorte1' +
        '|x40_codcorte|x40_dtinc', 'Pesquisa', true, 20);

    }else{
      if(document.form1.x40_codcorte.value != ''){ 
        js_OpenJanelaIframe('top.corpo', 'db_iframe_aguacorte', 'func_aguacorte.php?pesquisa_chave=' + 
    	    document.form1.x40_codcorte.value+'&funcao_js=parent.js_mostraaguacorte', 'Pesquisa', false);

      }else{
        document.form1.x40_dtinc.value = ''; 
      }
    }
  }
  
  function js_validax40_codcorte() {
    if (document.form1.x40_codcorte.value = '') {
      alert("E necessario selecionar um Procedimento de Corte");
      document.form1.x40_codcorte.focus();
      document.form1.x40_codcorte.value = '';
    }
  }

  function js_mostraaguacorte(chave, erro) {
    document.form1.x40_dtinc.value = chave; 

    if (erro == true) { 
      document.form1.x40_codcorte.focus(); 
      document.form1.x40_codcorte.value = ''; 
    }
  }
  
  function js_mostraaguacorte1(chave1, chave2) {
    document.form1.x40_codcorte.value = chave1;
    document.form1.x40_dtinc.value = chave2;
    db_iframe_aguacorte.hide();
  }

  function js_mostra_processando() {
    //document.form1.processando.style.visibility='visible';
    if(document.form1.acao.value == 'imprimir') {
      js_emite();
    }
  }

  function js_termo(msg) {
	  document.getElementById('termometro').innerHTML = msg;
  }

  function js_pesquisax43_codsituacao(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo', 'db_iframe_aguacortesituacao',
    	  'func_aguacortesituacao.php?funcao_js=parent.js_mostraaguacortesituacao1|x43_codsituacao|x43_descr',
    	  'Pesquisa', true, 20);

    } else {
      if (document.form1.x43_codsituacao.value != '') { 
        js_OpenJanelaIframe('top.corpo', 'db_iframe_aguacortesituacao',
          'func_aguacortesituacao.php?pesquisa_chave=' + document.form1.x43_codsituacao.value +
          '&funcao_js=parent.js_mostraaguacortesituacao', 'Pesquisa', false);

      } else {
        document.form1.x43_descr.value = ''; 
      }
    } 
  }

  function js_mostraaguacortesituacao(chave, erro) {
    document.form1.x43_descr.value = chave; 
    if (erro == true) { 
      document.form1.x43_codsituacao.focus(); 
      document.form1.x43_codsituacao.value = ''; 
    }
  }

  function js_mostraaguacortesituacao1(chave1, chave2) {
    document.form1.x43_codsituacao.value = chave1;
    document.form1.x43_descr.value = chave2;
    db_iframe_aguacortesituacao.hide();
  }

  function js_pesquisax43_codsituacao2(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo', 'db_iframe_aguacortesituacao2',
    	  'func_aguacortesituacao.php?funcao_js=parent.js_mostraaguacortesituacao21|x43_codsituacao|x43_descr',
    	  'Pesquisa', true, 20);

    } else {
      if (document.form1.x43_codsituacao2.value != '') { 
        js_OpenJanelaIframe('top.corpo', 'db_iframe_aguacortesituacao2',
          'func_aguacortesituacao.php?pesquisa_chave=' + document.form1.x43_codsituacao2.value +
          '&funcao_js=parent.js_mostraaguacortesituacao2', 'Pesquisa', false);

      }else{
        document.form1.x43_descr2.value = ''; 
      }
    }
  }

  function js_mostraaguacortesituacao2(chave, erro) {
    document.form1.x43_descr2.value = chave; 
    if (erro == true) { 
      document.form1.x43_codsituacao2.focus(); 
      document.form1.x43_codsituacao2.value = ''; 
    }
  }

  function js_mostraaguacortesituacao21(chave1, chave2) {
    document.form1.x43_codsituacao2.value = chave1;
    document.form1.x43_descr2.value = chave2;
    db_iframe_aguacortesituacao2.hide();
  }

  function js_pesquisax43_codsituacao3(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo', 'db_iframe_aguacortesituacao3',
    	  'func_aguacortesituacao.php?funcao_js=parent.js_mostraaguacortesituacao31|x43_codsituacao|x43_descr',
    	  'Pesquisa', true, 20);

    } else {
      if (document.form1.x43_codsituacao3.value != '') { 
        js_OpenJanelaIframe('top.corpo', 'db_iframe_aguacortesituacao3',
          'func_aguacortesituacao.php?pesquisa_chave=' + document.form1.x43_codsituacao3.value +
          '&funcao_js=parent.js_mostraaguacortesituacao3', 'Pesquisa', false);

      } else {
        document.form1.x43_descr3.value = ''; 
      }
    }
  }

  function js_mostraaguacortesituacao3(chave, erro) {
    document.form1.x43_descr3.value = chave; 
    if (erro == true) { 
      document.form1.x43_codsituacao3.focus(); 
      document.form1.x43_codsituacao3.value = ''; 
    }
  }

  function js_mostraaguacortesituacao31(chave1, chave2) {
    document.form1.x43_codsituacao3.value = chave1;
    document.form1.x43_descr3.value = chave2;
    db_iframe_aguacortesituacao3.hide();
  }

  function js_pesquisax01_codrua(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo', 'db_iframe_ruas',
    	  'func_ruas.php?funcao_js=parent.js_mostraruas1|j14_codigo|j14_nome',
    	  'Pesquisa', true);

    } else {
      if (document.form1.x01_codrua.value != '') { 
        js_OpenJanelaIframe('top.corpo', 'db_iframe_ruas', 'func_ruas.php?pesquisa_chave=' + 
          document.form1.x01_codrua.value + '&funcao_js=parent.js_mostraruas', 'Pesquisa', false);

      } else {
        document.form1.j14_nome.value = ''; 
      }
    }
  }
  
  function js_mostraruas(chave, erro) {
	  document.form1.j14_nome.value = chave; 
	  if (erro == true) { 
      document.form1.x01_codrua.focus(); 
      document.form1.x01_codrua.value = ''; 
    }
  }
  
  function js_mostraruas1(chave1, chave2) {
    document.form1.x01_codrua.value = chave1;
    document.form1.j14_nome.value = chave2;
    db_iframe_ruas.hide();
  }

  function js_pesquisax01_zona(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo', 'db_iframe_zonas',
    	  'func_zonas.php?funcao_js=parent.js_mostrazonas1|j50_zona|j50_descr', 'Pesquisa', true);
    } else {
      if (document.form1.x01_zona.value != '') { 
        js_OpenJanelaIframe('top.corpo', 'db_iframe_zonas', 'func_zonas.php?pesquisa_chave=' +
          document.form1.x01_zona.value + '&funcao_js=parent.js_mostrazonas', 'Pesquisa', false);
      } else {
        document.form1.j50_descr.value = ''; 
      }
    }
  }

  function js_mostrazonas(chave, erro) {
    document.form1.j50_descr.value = chave; 
    if (erro == true) { 
      document.form1.x01_zona.focus(); 
      document.form1.x01_zona.value = ''; 
    }
  }

  function js_mostrazonas1(chave1, chave2) {
    document.form1.x01_zona.value = chave1;
    document.form1.j50_descr.value = chave2;
    db_iframe_zonas.hide();
  }

  function js_pesquisax01_entrega(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo', 'db_iframe_entrega',
    	  'func_iptucadzonaentrega.php?funcao_js=parent.js_mostraentrega1|j85_codigo|j85_descr', 'Pesquisa', true);

    } else {
      if (document.form1.x01_entrega.value != '') { 
        js_OpenJanelaIframe('top.corpo','db_iframe_entrega',
          'func_iptucadzonaentrega.php?pesquisa_chave=' + document.form1.x01_entrega.value + 
          '&funcao_js=parent.js_mostraentrega', 'Pesquisa', false);

      } else {
        document.form1.j85_descr.value = ''; 
      }
    }
  }
  
  function js_mostraentrega(chave, erro) {
    document.form1.j85_descr.value = chave; 
    if (erro == true) { 
      document.form1.x01_entrega.focus(); 
      document.form1.x01_entrega.value = ''; 
    }
  }

  function js_mostraentrega1(chave1, chave2) {
    document.form1.x01_entrega.value = chave1;
    document.form1.j85_descr.value = chave2;
    db_iframe_entrega.hide();
  }
</script>