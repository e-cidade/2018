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
  $claguahidromatric->rotulo->label();
  $claguahidromatricleitura->rotulo->label();
  $clagualeitura->rotulo->label();

  $clrotulo = new rotulocampo;
  $clrotulo->label("x03_nomemarca");
  $clrotulo->label("x15_diametro");
  $clrotulo->label("z01_nome");
  $clrotulo->label("x17_descr");
  
  /*
    if ($db_opcao == 2) {
      if ($claguahidromatricleitura->numrows == 0) {
        $existe_leitura_posterior = 1;
      } else {
        $existe_leitura_posterior = 3;
      }
    } elseif ($db_opcao == 1) {
      $existe_leitura_posterior = 1;
    } else {
      $existe_leitura_posterior = 3;
    }
*/

?>

<form name="form1" method="post" action="">
  <center>
    <table border="0">
      <tr>
        <td nowrap align="right" title="<?=@$Tx04_codhidrometro?>">
          <?=@$Lx04_codhidrometro?>
        </td>
        <td> 
          <?
            db_input('x04_codhidrometro', 6, $Ix04_codhidrometro, true, 'text', ($db_opcao == 1 ? 1 : 3), "")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap align="right" title="<?=@$Tx04_matric?>">
          <?
            db_ancora(@$Lx04_matric, "js_pesquisax04_matric(true);", $db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('x04_matric', 6, $Ix04_matric, true, 'text', $db_opcao, " onchange='js_pesquisax04_matric(false);'");
            db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, '');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap align="right" title="<?=@$Tx04_nrohidro?>">
          <?=@$Lx04_nrohidro?>
        </td>
        <td> 
          <?
            db_input('x04_nrohidro', 20, $Ix04_nrohidro, true, 'text', $db_opcao, "")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap align="right" title="<?=@$Tx04_coddiametro?>">
          <?
            db_ancora(@$Lx04_coddiametro, "js_pesquisax04_coddiametro(true);", $db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('x04_coddiametro', 6, $Ix04_coddiametro,
                     true, 'text', $db_opcao, " onchange='js_pesquisax04_coddiametro(false);'");
            
            db_input('x15_diametro', 10, $Ix15_diametro, true, 'text', 3, '');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap align="right" title="<?=@$Tx04_codmarca?>">
          <?
            db_ancora(@$Lx04_codmarca, "js_pesquisax04_codmarca(true);", $db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('x04_codmarca', 6, $Ix04_codmarca,
                     true, 'text', $db_opcao, " onchange='js_pesquisax04_codmarca(false);'");
            
            db_input('x03_nomemarca', 40, $Ix03_nomemarca, true, 'text', 3, '');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap align="right" title="<?=@$Tx04_qtddigito?>">
          <?=@$Lx04_qtddigito?>
        </td>
        <td> 
          <?
            db_input('x04_qtddigito', 6, $Ix04_qtddigito, true, 'text', $db_opcao, "")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap align="right" title="<?=@$Tx04_dtinst?>">
          <?=@$Lx04_dtinst?>
        </td>
        <td> 
          <?
            db_inputdata('x04_dtinst', @$x04_dtinst_dia, @$x04_dtinst_mes, @$x04_dtinst_ano, true, 'text', $db_opcao, "")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap align="right" title="<?=@$Tx04_leitinicial?>">
          <?=@$Lx04_leitinicial?>
        </td>
        <td> 
          <?
            //db_input('x04_leitinicial',15,$Ix04_leitinicial,true,'text',$db_opcao==1?1:3,"")
            db_input('x04_leitinicial', 15, $Ix04_leitinicial, true, 'text', $existe_leitura_posterior, "")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tx21_exerc?>" align="right">
          <b><?=@$RLx21_exerc?>&nbsp;/&nbsp;<?=@$RLx21_mes?>:</b>  
        </td>
        <td nowrap> 
          <?
            if (!isset($x21_exerc) || (isset($x21_exerc) && trim($x21_exerc) == "")) {
              $x21_exerc = db_getsession("DB_anousu");
            }
            
            //db_input('x21_exerc',4,$Ix21_exerc,true,'text',$db_opcao==1?1:3,"");
            db_input('x21_exerc', 4, $Ix21_exerc, true, 'text', $existe_leitura_posterior, "");
          ?>
          <b>&nbsp;/&nbsp;</b>  
          <?
            if (!isset($x21_mes) || (isset($x21_mes) && trim($x21_mes) == "")) {
              $x21_mes = date("m",db_getsession("DB_datausu"));
            }
      
            //db_input('x21_mes',2,$Ix21_mes,true,'text',$db_opcao==1?1:3,"");
            db_input('x21_mes', 2, $Ix21_mes, true, 'text', $existe_leitura_posterior, "");
          ?> 
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tx21_situacao?>" align="right">
          <?
            //db_ancora(@$Lx21_situacao,"js_pesquisax21_situacao(true);",$db_opcao);
            db_ancora(@$Lx21_situacao, "js_pesquisax21_situacao(true);", $existe_leitura_posterior);
          ?>
        </td>
        <td nowrap colspan="5"> 
          <?
            //db_input('x21_situacao',8,$Ix21_situacao,true,'text',$db_opcao==1?1:3,"onchange='js_pesquisax21_situacao(false);'","");
            db_input('x21_situacao', 6, $Ix21_situacao,
                     true, 'text', $existe_leitura_posterior, "onchange='js_pesquisax21_situacao(false);'", "");
            
            db_input('x17_descr', 40, $Ix17_descr, true, 'text', 3, "", "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tx04_avisoleiturista?>" align="right">
          <?=@$Lx04_avisoleiturista?>
        </td>
        <td>
          <?
            db_input('x04_avisoleiturista', 48, $Ix04_avisoleiturista,
                     true, 'text', $db_opcao, "", "","","", "198");
          ?>
        </td>
      </tr>  
      <tr>
        <td nowrap title="<?=@$Tx04_observacao?>" align="right">
          <?=@$Lx04_observacao?>
        </td>
        <td>
          <?
            db_textarea('x04_observacao', 4, 48, $Ix04_observacao, true, 'text', $db_opcao);
          ?>
        </td>
      </tr>
    </table>
  </center>

  <input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>"
         type="submit" id="db_opcao"
         value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
           <?=($db_botao == false ? "disabled" : "")?>
  />
  
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" />

</form>

<script>
  function js_pesquisax04_codmarca(mostra){

	  if (mostra == true) {
      js_OpenJanelaIframe('top.corpo', 'db_iframe_aguahidromarca',
    	                    'func_aguahidromarca.php?funcao_js=parent.js_mostraaguahidromarca1|x03_codmarca|x03_nomemarca',
    	                    'Pesquisa', true);
    } else {
      if (document.form1.x04_codmarca.value != '') { 
        js_OpenJanelaIframe('top.corpo', 'db_iframe_aguahidromarca',
                            'func_aguahidromarca.php?pesquisa_chave=' + document.form1.x04_codmarca.value + 
                              '&funcao_js=parent.js_mostraaguahidromarca',
                            'Pesquisa', false);
      } else {
        document.form1.x03_nomemarca.value = ''; 
      }
    }
  }

  
  function js_mostraaguahidromarca(chave, erro){

	  document.form1.x03_nomemarca.value = chave; 

	  if (erro == true) { 
		  document.form1.x04_codmarca.focus(); 
		  document.form1.x04_codmarca.value = ''; 
		}
	}

	
  function js_mostraaguahidromarca1(chave1, chave2) {
	  document.form1.x04_codmarca.value = chave1;
    document.form1.x03_nomemarca.value = chave2;
    db_iframe_aguahidromarca.hide();
  }

  
  function js_pesquisax04_coddiametro(mostra) {
	  if (mostra == true) {
      js_OpenJanelaIframe('top.corpo', 'db_iframe_aguahidrodiametro',
    	                    'func_aguahidrodiametro.php?funcao_js=parent.js_mostraaguahidrodiametro1|x15_coddiametro|x15_diametro',
    	                    'Pesquisa', true);
    }else{
      if (document.form1.x04_coddiametro.value != '') { 
        js_OpenJanelaIframe('top.corpo', 'db_iframe_aguahidrodiametro',
                            'func_aguahidrodiametro.php?pesquisa_chave=' + document.form1.x04_coddiametro.value + 
                              '&funcao_js=parent.js_mostraaguahidrodiametro',
                            'Pesquisa', false);
      }else{
        document.form1.x15_diametro.value = ''; 
      }
    }
  }


  function js_mostraaguahidrodiametro(chave, erro) {
    document.form1.x15_diametro.value = chave; 

    if (erro == true) { 
      document.form1.x04_coddiametro.focus(); 
      document.form1.x04_coddiametro.value = ''; 
    }
  }

  
  function js_mostraaguahidrodiametro1(chave1, chave2) {
	  document.form1.x04_coddiametro.value = chave1;
	  document.form1.x15_diametro.value = chave2;
	  db_iframe_aguahidrodiametro.hide();
	}

	
  function js_pesquisax04_matric(mostra) {
	  if (mostra == true) {
      js_OpenJanelaIframe('top.corpo', 'db_iframe_aguabase',
    	                    'func_aguabase.php?funcao_js=parent.js_mostraaguabase1|x01_matric|z01_nome',
    	                    'Pesquisa', true);
    } else {

      if (document.form1.x04_matric.value != '') { 
        js_OpenJanelaIframe('top.corpo', 'db_iframe_aguabase',
                            'func_aguabase.php?pesquisa_chave=' + document.form1.x04_matric.value + 
                              '&funcao_js=parent.js_mostraaguabase',
                            'Pesquisa', false);
      } else {
        document.form1.z01_nome.value = ''; 
      }
    }
  }

  
  function js_mostraaguabase(chave, erro) {

	  document.form1.z01_nome.value = chave; 

	  if (erro == true) { 
      document.form1.x04_matric.focus(); 
      document.form1.x04_matric.value = ''; 
    }
  }

  
  function js_mostraaguabase1(chave1, chave2) {
	  document.form1.x04_matric.value = chave1;
	  document.form1.z01_nome.value = chave2;
	  db_iframe_aguabase.hide();
	}

	
  function js_pesquisa() {
	  js_OpenJanelaIframe('top.corpo', 'db_iframe_aguahidromatric', 
			                  'func_aguahidromatric.php?funcao_js=parent.js_preenchepesquisa|x04_codhidrometro',
			                  'Pesquisa', true);
	}

	
  function js_preenchepesquisa(chave) {
	  db_iframe_aguahidromatric.hide();
    <?
      if ($db_opcao != 1) {
        echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave";
      }
    ?>
  }

  
  function js_pesquisax21_situacao(mostra){
	  if (mostra == true) {
      js_OpenJanelaIframe('top.corpo', 'db_iframe_aguasitleitura',
    	                    'func_aguasitleituraalt.php?funcao_js=parent.js_mostraaguasitleitura1|x17_codigo|x17_descr|x17_regra',
    	                    'Pesquisa', true);
    } else {
      if (document.form1.x21_situacao.value != '') { 
        js_OpenJanelaIframe('top.corpo', 'db_iframe_aguasitleitura', 
                            'func_aguasitleituraalt.php?pesquisa_chave=' + document.form1.x21_situacao.value +
                              '&funcao_js=parent.js_mostraaguasitleitura',
                            'Pesquisa', false);
      } else {
        document.form1.x17_descr.value = ''; 
      }
    }
  }

  
  function js_mostraaguasitleitura(chave, chave2, erro) {

	  document.form1.x17_descr.value = chave;

	  if (erro == true) { 
      document.form1.x21_situacao.focus(); 
      document.form1.x21_situacao.value = ''; 
    } else {
      if (chave2 == "0") {
        //js_repete_leitura_anterior(false);
      }else{
        //js_repete_leitura_anterior(true);
      }
    }
  }

  
  function js_mostraaguasitleitura1(chave1, chave2, chave3) {

	  document.form1.x21_situacao.value = chave1;
    document.form1.x17_descr.value = chave2;

    if (chave3 == "0") {
      //js_repete_leitura_anterior(false);
    } else {
      //js_repete_leitura_anterior(true);
    }
    
    db_iframe_aguasitleitura.hide();
  }

</script>