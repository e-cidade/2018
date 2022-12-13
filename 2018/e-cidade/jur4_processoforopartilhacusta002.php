<?php
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

  require_once ("libs/db_stdlib.php");
  require_once ("libs/db_conecta.php");
  require_once ("libs/db_sessoes.php");
  require_once ("libs/db_usuariosonline.php");
  require_once ("dbforms/db_funcoes.php");
  
  require_once ("libs/db_sql.php");
  require_once ("classes/db_termo_classe.php");
  require_once ("classes/db_cgm_classe.php");
  require_once ("libs/db_app.utils.php");
  require_once ("dbforms/db_classesgenericas.php");
  require_once ("libs/db_utils.php");
  
  require_once ("classes/db_processoforopartilha_classe.php");
  require_once ("classes/db_partilhaarquivoreg_classe.php");
  require_once ("classes/db_processoforopartilhacusta_classe.php");
  require_once ("classes/db_cancrecibopaga_classe.php");
  
  $oPost                       = db_utils::postMemory($_POST);
  $oGet                        = db_utils::postMemory($_GET);
  
  $clprocessoforopartilhacusta = new cl_processoforopartilhacusta;
  $clpartilhaarquivoreg        = new cl_partilhaarquivoreg;
  $clprocessoforopartilha      = new cl_processoforopartilha;
  $clcancrecibopaga            = new cl_cancrecibopaga;
  $clcgm                       = new cl_cgm;
  $clrotulo                    = new rotulocampo;
  
  $instit = db_getsession("DB_instit");
  
  db_postmemory($HTTP_POST_VARS);
  
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("strings.js");
  
  $db_opcao                             = 1;
  $sStyleValorDebito                    = "style ='background-color: white; width: 220px;' ";
  $sStyleProcessar                      = "";
  $situacao                             = "Não Emitido";
  $v76_tipolancamento                   = 0;
  $aTaxas                               = array();
  $bPossuiTipoLancamentoIsentoComRecibo = false;
  
  $sCampos  = " ar37_sequencial,         ";
  $sCampos .= " ar37_descricao,          ";
  $sCampos .= " v76_sequencial,          ";
  $sCampos .= " v76_tipolancamento,      ";
  $sCampos .= " v76_dtpagamento,         ";
  $sCampos .= " v76_obs,                 ";
  $sCampos .= " v77_sequencial,          ";
  $sCampos .= " v77_taxa,                ";
  $sCampos .= " v77_valor,               ";
  $sCampos .= " v77_numnov,              ";
  $sCampos .= " v77_processoforopartilha,";
  $sCampos .= " ar36_sequencial,         ";
  $sCampos .= " ar36_descricao,          ";
  $sCampos .= " ar36_receita             ";
  
  // ordenar pelo sequencial da processoforopartilha pegando o ultimo registro DESC LIMIT 1
  // se encontrar preenche em db opçao 3
  // caso nao encontre executar o $sSqlTaxas
  // 1 - automatico
  // 2 - manual
  // 3 - isento
  
    /*
     * Se o recibo da partilha esteja vencido. k00_dtpaga < Data Atual
     * Se o recibo da partilha esteja cancelado. Numnov presente na tabela cancrecibopaga.
     * Se o recibo da partilha não tenha sido enviado em nenhum arquivo de remessa ao banco
     * Se o tipo de lancamento do recibo seja automático, recibo gerado pela CGF(Consulta Geral Financeira)
     * 
     * Liberamos a rotina para que possar ser realizada manutenção das custas do processo 
     * podendo lançar manualmente o pagamento ou a isenção
     */
  $sOrderPartilha  = "v76_sequencial DESC LIMIT 1 ";
  
  $sWherePartilha  = " v76_processoforo = {$oGet->v70_sequencial}                                                                  ";
  $sWherePartilha .= " and ( case                                                                                                  ";
  $sWherePartilha .= "         when v76_tipolancamento = 1                                                                         ";
  $sWherePartilha .= "           then (     ( select k00_dtpaga                                                                    ";
  $sWherePartilha .= "                          from recibopaga                                                                    ";
  $sWherePartilha .= "                         where recibopaga.k00_numnov = processoforopartilhacusta.v77_numnov                  ";
  $sWherePartilha .= "                         limit 1) >= '" . date("Y-m-d", db_getsession("DB_datausu")) . "' )                  ";
  $sWherePartilha .= "                  and not exists (select 1                                                                   ";
  $sWherePartilha .= "                                     from cancrecibopaga                                                     ";
  $sWherePartilha .= "                                    where cancrecibopaga.k134_numnov = processoforopartilhacusta.v77_numnov) ";
  $sWherePartilha .= "           else true                                                                                         ";
  $sWherePartilha .= "       end)                                                                                                  ";
  
  $sSqlProcessoForoPartilhaCusta = "select * from (".$clprocessoforopartilhacusta->sql_query(null," v77_processoforopartilha,v76_tipolancamento", $sOrderPartilha, $sWherePartilha).") as x order by v77_processoforopartilha desc limit 1";
  
  $rsPartilha                    = $clprocessoforopartilhacusta->sql_record($sSqlProcessoForoPartilhaCusta);
  
  if ($clprocessoforopartilhacusta->numrows > 0) {
    
    $sStyleValorDebito = "style ='background-color: rgb(222, 184, 135); width: 220px;' readonly='readonly'  ";
    
    $oPartilha = db_utils::fieldsMemory($rsPartilha, 0);
  
    $iPartilha           = $oPartilha->v77_processoforopartilha;
    $v76_tipolancamento  = $oPartilha->v76_tipolancamento;
    
    $sSqlProcessoForoPartilhaLancamentos = $clprocessoforopartilha->sql_query(null," DISTINCT v76_tipolancamento", null, "v76_processoforo = {$oGet->v70_sequencial} and v76_tipolancamento in (1, 3)");
    
    $rsProcessoForoPartilhaLancamentos   = $clprocessoforopartilha->sql_record($sSqlProcessoForoPartilhaLancamentos);
    
    if ($clprocessoforopartilha->numrows > 1) {
      
      $bPossuiTipoLancamentoIsentoComRecibo = true;
    }
    
    $sSqlPartilhaCusta   = $clprocessoforopartilhacusta->sql_query_recibo(null, "distinct {$sCampos}", "", "v77_processoforopartilha = {$iPartilha}");
    $rsPartilhaCusta     = $clprocessoforopartilhacusta->sql_record($sSqlPartilhaCusta);
    
    if ($clprocessoforopartilhacusta->numrows > 0) {
      
      db_fieldsmemory($rsPartilhaCusta, 0);
      
      $sSqlPartilhaArquivoReg = $clpartilhaarquivoreg->sql_query(null, "v79_partilhaarquivo", "", "v79_processoforopartilhacusta in ( $v77_sequencial ) ");
      $rsPartilhaArquivoReg   = $clpartilhaarquivoreg->sql_record($sSqlPartilhaArquivoReg);
      
      if ( $oPartilha ->v76_tipolancamento == 1) {
        
        $situacao = "Emitido";
      }
    
      for ($iInd = 0; $iInd < $clprocessoforopartilhacusta->numrows; $iInd++) {
        
        $aTaxas[] = db_utils::fieldsMemory($rsPartilhaCusta, $iInd);
      }
    
    } else {
      
      if ( $oPartilha ->v76_tipolancamento == 3) {
        
        $db_opcao  = 5;
        $situacao  = "Isento de Custas";
      } 
      
    }
         
  } else {
    
    // processo 1539, 1540
    $sSqlTaxas  = " select distinct ar36_sequencial,                                                                                        ";
    $sSqlTaxas .= "        ar36_descricao,                                                                                                  ";
    $sSqlTaxas .= "        ar36_valor as v77_valor,                                                                                         ";
    $sSqlTaxas .= "        ar36_perc,                                                                                                       ";
    $sSqlTaxas .= "        ar36_valormin,                                                                                                   ";
    $sSqlTaxas .= "        ar36_valormax,                                                                                                   ";
    $sSqlTaxas .= "        ar36_receita                                                                                                     ";
    $sSqlTaxas .= "   from modcarnepadraotipo                                                                                               ";
    $sSqlTaxas .= "        inner join modcarnepadrao       on modcarnepadrao.k48_sequencial         = modcarnepadraotipo.k49_modcarnepadrao ";
    $sSqlTaxas .= "        inner join cadconveniogrupotaxa on cadconveniogrupotaxa.ar39_cadconvenio = modcarnepadrao.k48_cadconvenio        ";
    $sSqlTaxas .= "        inner join grupotaxa            on grupotaxa.ar37_sequencial             = cadconveniogrupotaxa.ar39_grupotaxa   ";
    $sSqlTaxas .= "        inner join taxa                 on taxa.ar36_grupotaxa                   = grupotaxa.ar37_sequencial             ";
    $sSqlTaxas .= "        inner join arretipo             on arretipo.k00_tipo                     = modcarnepadraotipo.k49_tipo           ";
    $sSqlTaxas .= "  where k03_tipo in (12,18)                                                                                              ";
    $rsTaxa     = db_query($sSqlTaxas);
    
    if (pg_num_rows($rsTaxa) > 0) {
  
      for ($iTotalTaxa = 0; $iTotalTaxa < pg_num_rows($rsTaxa); $iTotalTaxa++) {
        
        $aTaxas[] = db_utils::fieldsMemory($rsTaxa, $iTotalTaxa);
        
      }
    }
  }
  /*
   * Recebendo dados para Inserção
   * 
   */
  if (isset($incluir)) {
    
    //processoforopartilha
    //processoforopartilhacusta
    
    try {
      
      db_inicio_transacao();
      // Dados Processo do Foro
      $iProcesso   = $oPost->v70_sequencial ;
      $iCodForo    = $oPost->v70_codforo;
      
      // Dados da Partilha
      $iPartilha       = $oPost->ar37_sequencial;
      $sSituacao       = $oPost->situacao;
      $sTipoLancamento = $oPost->v76_tipolancamento;
      $sPagamento      = implode("-", array_reverse(explode("/" , $oPost->v76_dtpagamento)));
      $sObs            = $oPost->v76_obs;
      
      $clprocessoforopartilha->v76_processoforo   = $iProcesso;
      $clprocessoforopartilha->v76_tipolancamento = $sTipoLancamento; 
      $clprocessoforopartilha->v76_dtpagamento    = $sPagamento;
      $clprocessoforopartilha->v76_obs            = $sObs;
  		$clprocessoforopartilha->v76_datapartilha   = date('Y-m-d', db_getsession('DB_datausu'));
  		$clprocessoforopartilha->v76_valorpartilha  = '0';
      $clprocessoforopartilha->incluir(null);
      
      if ($clprocessoforopartilha->erro_status == '0') {
        
        throw new Exception($clprocessoforopartilha->erro_msg);
      }
      
      $iProcessoforopartilha = $clprocessoforopartilha->v76_sequencial;
      // custas 
      //$sValorDebito = $oPost->valor_debito;
      
      // array com taxas dinamicas, ex:
      // ar36_valor[20] = 1,99, o indice [20] é o ID da taxa e o valor 1,99, éo respectivo valor da taxa
      
      foreach ($oPost->ar36_valor as $iIndiceTaxa => $oValorTaxa) {
        
        $clprocessoforopartilhacusta->v77_taxa                 = $iIndiceTaxa;
        $clprocessoforopartilhacusta->v77_processoforopartilha = $iProcessoforopartilha;
        $clprocessoforopartilhacusta->v77_valor                = $oValorTaxa;
        $clprocessoforopartilhacusta->v77_numnov               = "0";
        $clprocessoforopartilhacusta->incluir(null);
        
        if ($clprocessoforopartilhacusta->erro_status == 0) {
          
          throw new Exception($clprocessoforopartilhacusta->erro_msg);
        }
        
      }
      
      db_fim_transacao(false);
      db_msgbox(_M('tributario.juridico.jur4_processoforopartilhacusta002.cadastro_efetuado_sucesso'));
      db_redireciona("jur4_processoforopartilhacusta001.php");
   
    } catch (Exception $eException) {
      
      db_fim_transacao(true);
      db_msgbox($eException->getMessage());
      
    } 
  }
  
  if (isset($alterar)) {
    
    try {
      
      db_inicio_transacao();
      $sCamposAlterar = "v77_sequencial";
      
      // Dados Processo do Foro
      $iProcesso   = $oPost->v70_sequencial ;
      $iCodForo    = $oPost->v70_codforo;
      
      // Dados da Partilha
      $iPartilha       = $oPost->ar37_sequencial;
      $sSituacao       = $oPost->situacao;
      $sTipoLancamento = $oPost->v76_tipolancamento;
      $sPagamento      = implode("-", array_reverse(explode("/" , $oPost->v76_dtpagamento)));
      $sObs            = $oPost->v76_obs;
  
      $clprocessoforopartilha->v76_sequencial     = $v77_processoforopartilha;
      $clprocessoforopartilha->v76_processoforo   = $iProcesso;
      $clprocessoforopartilha->v76_tipolancamento = $sTipoLancamento; 
      $clprocessoforopartilha->v76_dtpagamento    = $sPagamento;
      $clprocessoforopartilha->v76_obs            = $sObs;
      $clprocessoforopartilha->alterar($clprocessoforopartilha->v76_sequencial);
      
      if ($clprocessoforopartilha->erro_status == '0') {
        
        throw new Exception($clprocessoforopartilha->erro_msg);
      }
      
      foreach ($oPost->ar36_valor as $iIndiceTaxa => $oValorTaxa) {
      
        $sSqlAlterar = $clprocessoforopartilhacusta->sql_query(null, $sCamposAlterar, null, "v77_processoforopartilha = {$v77_processoforopartilha} and v77_taxa = {$iIndiceTaxa}");
        $rsAlterar   = $clprocessoforopartilhacusta->sql_record($sSqlAlterar);
        db_fieldsmemory($rsAlterar, 0);
        
        $clprocessoforopartilhacusta->v77_sequencial           = $v77_sequencial;
        $clprocessoforopartilhacusta->v77_taxa                 = $iIndiceTaxa;
        $clprocessoforopartilhacusta->v77_processoforopartilha = $v77_processoforopartilha;
        $clprocessoforopartilhacusta->v77_valor                = $oValorTaxa;
        $clprocessoforopartilhacusta->v77_numnov               = "";
        $clprocessoforopartilhacusta->alterar($clprocessoforopartilhacusta->v77_sequencial);
        if ($clprocessoforopartilhacusta->erro_status == 0) {
          
          throw new Exception($clprocessoforopartilhacusta->erro_msg);
        }
        
      }
      
      db_fim_transacao(false);
      db_msgbox(_M('tributario.juridico.jur4_processoforopartilhacusta002.alteracao_efetuada_sucesso'));
      db_redireciona("jur4_processoforopartilhacusta001.php");
      
    } catch (Exception $eException) {
      
      db_fim_transacao(true);
      db_msgbox($eException->getMessage());
      
    }
    
  }
  
?>
<style>

  textarea {
  	width: 100%;
  }
  
  #ctnCustas {
    width: 100%;
    height: 200px;
    overflow: auto;
  }
</style>

<html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC"  >

<center>
  <form name="form1" method="post" action="" onsubmit="return js_valida();">

    <fieldset style="margin-top: 50px;width: 500px;">
      <legend><b>Dados Processo Foro</b></legend>
      
      <table border="0" width="100%" align="left">
        <tr>
          <td align="left" nowrap width="40%">
            <b>Processo do Sistema : </b>
          </td>
          <td align="left">
            <?php
              db_input("v70_sequencial",  30, "",  true, "text", 3, "");
            ?>
          </td>
        </tr>
        
        <tr>
          <td align="left" nowrap  >
            <b>Código Processo Foro : </b>
          </td>
          <td align="left">
            <?php
              db_input("v70_codforo",  30, "",  true, "text", 3, "");
            ?>
          </td>
        </tr>
        
      </table>
    </fieldset> 
    
    <fieldset style="margin-top: 10px;width: 500px;">
      <legend><b>Dados da Partilha</b></legend>
     
      <table border="0" width="100%" align="left" >
        <tr>
          <td align="left" nowrap width="40%"  >
            <b>Partilha : </b>
          </td>
          <td align="left">
            <?php
              db_input("ar37_sequencial",  5, "",  true, "hidden", 3, "");
              db_input("ar37_descricao",  30, "",  true, "text", 3, "");
            ?>
          </td>
        </tr>
        <tr>
          <td align="left" nowrap  >
            <b>Situação : </b>
          </td>
          <td align="left">
            <?php
              db_input("situacao",  30, "", true, "text", 3, "");
            ?>
          </td>
        </tr>
        <tr>
          <td align="left" nowrap>
            <b>Lançamento : </b>
          </td>
          <td align="left">
            <?php
              if ($bPossuiTipoLancamentoIsentoComRecibo or $v76_tipolancamento == 3) {
                
                $v76_tipolancamento = "Isento";
                db_input("v76_tipolancamento",  30, "",  true, "text", "3", "");
              } else {
              
                $aTipoLancamento = array("0"=>"Selecione...", "2"=>"Manual", "3"=>"Isento");
                db_select("v76_tipolancamento", $aTipoLancamento, true, $db_opcao, "onchange='js_lancamento(this.value, false);'", "v76_tipolancamento");
               
              }
            ?>
            
          </td>
        </tr>
        <tr>
          <td align="left" nowrap  >
            <b>Data do Pagamento : </b>
          </td>
          <td align="left">
            <?php
              db_inputdata('v76_dtpagamento', @$v76_dtpagamento_dia, @$v76_dtpagamento_mes, @$v76_dtpagamento_ano, true, 'text', $db_opcao);
            ?>
          </td>
        </tr> 
        
        <tr>
          <td colspan="2">
           <fieldset style="">
           		<legend><b>Observação</b></legend>
              <table width="100%" border="0" align="left">
              	<tr>
              	  <td>
              	  	<?php db_textarea("v76_obs", 3, 50, "", true, "text", $db_opcao, "", "", "", 400); ?>
              	  </td>
              	</tr>
              </table>
           </fieldset>
          </td>
        </tr>
      </table>
    </fieldset>     

    
          
    <?php 
    if ( count($aTaxas) > 0 ) {
              
    ?>
              
      <fieldset style="margin-top: 10px;width: 500px;">
        <legend><b>Custas</b></legend>
                
        <div id="ctnCustas">
                    
        <table border="0" width="100%" align="left" >
              
        <?php 
               
          foreach ($aTaxas as $iIndTaxas => $oValorTaxas) {
               
            echo "<tr>  ";
            echo "  <td align='left' nowrap  > ";
            echo "    <b>";
            echo        $oValorTaxas->ar36_descricao; 
            echo "    </b> ";
            echo "  </td>";
            echo "  <td align='left'> ";
            echo "    <input type='text' onkeyup='js_ValidaCamposText(this,4);' class='taxas'";
            echo "       id='ar36_valor[{$oValorTaxas->ar36_sequencial}]' name='ar36_valor[{$oValorTaxas->ar36_sequencial}]' ";
            echo "         value ='$oValorTaxas->v77_valor' {$sStyleValorDebito} /> ";           
            echo "  </td> ";
            echo "</tr>";
             
          }
        ?>
        </table>
        </div>
      </fieldset>
      
      <?php        
      } else {

        if (isset($oPartilha ->v76_tipolancamento) and $oPartilha ->v76_tipolancamento == 3) {
        ?>
          <input type="button" value="Voltar" onclick="window.location = 'jur4_processoforopartilhacusta001.php'" />
        <?php 
        } else {
          db_msgbox(_M('tributario.juridico.jur4_processoforopartilhacusta002.taxas_nao_configuradas'));
          db_redireciona("jur4_processoforopartilhacusta001.php");
        }
      }
              
  ?>
            
        
  <?php 
    if (empty($v76_tipolancamento ) or $v76_tipolancamento == 1) { 
    ?>
      <input  style='margin-top: 10px;' type='submit' id='incluir' name='incluir'  value='Procesar' <?php echo $sStyleProcessar; ?> />
    <?php 
    } else if ($v76_tipolancamento == 2 && !$bPossuiTipoLancamentoIsentoComRecibo){ 
    ?>
      <input  style='margin-top: 10px;' type='submit' id='alterar' name='alterar'  value='Alterar' /> 
    <?php 
    }
  ?>
  </form>
</center>
<?php
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>

<script>

var aBotaoCalendario = document.getElementsByName("dtjs_v76_dtpagamento");

function js_valida(){
  var iLancamento = $F('v76_tipolancamento');

  if (iLancamento == null || iLancamento == '' || iLancamento == '0'){

    alert(_M('tributario.juridico.jur4_processoforopartilhacusta002.selecione_tipo_lancamento'));
    return false;
	} 

  if (iLancamento == 3 && $F("v76_obs").trim() == "") {
	  alert(_M('tributario.juridico.jur4_processoforopartilhacusta002.preencha_observacao'));
	  $("v76_obs").focus();
	  return false;
	}

	if (iLancamento == 2 && $F("v76_dtpagamento").trim() == "") {
		alert(_M('tributario.juridico.jur4_processoforopartilhacusta002.informe_data_pagamento'));
		$("v76_dtpagamento").focus();
		return false;		
	}		
	
}

function js_lancamento(iTipo, lAlteracao){
 
	var aTaxas = $$('input.taxas');

	//Nenhum tipo de lançamento selecionado
	if (iTipo == 0) {
		  aTaxas.each(function(oDado, iInd){
	    	  oDado.style.backgroundColor = "#DEB887";
	    	  oDado.setAttribute('readonly','readonly');
	    });
			$('v76_obs').setAttribute('readonly', 'readonly');
			$('v76_obs').style.backgroundColor = "#DEB887";	
			$('v76_dtpagamento').setAttribute('readonly','readonly');
			$('v76_dtpagamento').style.backgroundColor = "#DEB887";		  
			aBotaoCalendario[0].style.display = 'none';		
	}	
		
	if (iTipo == 3) {  // isento
   
		aTaxas.each(function(oDado, iInd){
    	  oDado.style.backgroundColor = "#DEB887";
    	  oDado.setAttribute('readonly','readonly');
    });
		$('v76_obs').removeAttribute('readonly');
		$('v76_obs').style.backgroundColor = "#FFF";	
		$('v76_dtpagamento').setAttribute('readonly','readonly');
		$('v76_dtpagamento').style.backgroundColor = "#DEB887";		  
		aBotaoCalendario[0].style.display = 'none';

  }

	if (iTipo == 2) {  // manual

		aTaxas.each( function (oDado, iInd) {

			oDado.style.backgroundColor = "#FFF";
			oDado.removeAttribute('readonly');
		});		
		$('v76_obs').removeAttribute('readonly');
		$('v76_obs').style.backgroundColor = "#FFF";
		$('v76_dtpagamento').removeAttribute('readonly');
		$('v76_dtpagamento').style.backgroundColor = "#FFF";
		aBotaoCalendario[0].style.display = 'inline';
  }  
}

</script>
<?php 
     echo "<script>js_lancamento({$v76_tipolancamento}, true); </script>";
?>