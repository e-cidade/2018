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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);
$oDaoTfdAgendaSaida           = new cl_tfd_agendasaida();
$oDaoTfdAgendamentoPrestadora = new cl_tfd_agendamentoprestadora();
$oDaoTfdPedidoRegulado        = new cl_tfd_pedidoregulado();
$oDaoTfdParametros            = new cl_tfd_parametros();
$oDaoCgsUnd                   = new cl_cgs_und();
$oDaoTfdPedidoTfd             = new cl_tfd_pedidotfd();
$oDaoTfdVeiculoDestino        = new cl_tfd_veiculodestino();
$oDaoTfdPassageiroVeiculo     = new cl_tfd_passageiroveiculo();
$db_opcao                     = 1;
$db_botao                     = true;
$db_opcaoNaoMudar             = 1;
$db_indicarVeiculo            = db_permissaomenu(date('Y'), db_getsession('DB_modulo'), 8437) == 'true' ? 1 : 2;
$sListaCgs                    = '';


/*
 * ===================================================
 *    VERIFICA SE JA FOI INDICADA UMA PRESTADORA
 * ===================================================
 */
$sCampos       = ' z01_nome, tf10_i_prestadora, tf25_i_destino, tf03_c_descr,';
$sCampos      .= ' tf16_d_dataagendamento, tf16_c_horaagendamento ';
$sSql          = $oDaoTfdAgendamentoPrestadora->sql_query_destino(null, $sCampos, null,
                                                                  " tf16_i_pedidotfd = $tf17_i_pedidotfd"
                                                                 );
$rsPrestadora  = $oDaoTfdAgendamentoPrestadora->sql_record($sSql);
if ($oDaoTfdAgendamentoPrestadora->numrows > 0) {

  db_fieldsmemory($rsPrestadora, 0);

} else { 
    
  echo "<script>alert('Antes de agendar a saída você deve agendar com a prestadora.');";
  echo "parent.db_iframe_saida.hide();</script>";
  exit;

}

/*
 * ==================================================
 *     VERIFICA SE O PEDIDO JA FOI REGULADO 
 * ==================================================
 */
$sSql = $oDaoTfdPedidoRegulado->sql_query_file(null, 'tf34_i_codigo', null,
                                               " tf34_i_pedidotfd = $tf17_i_pedidotfd"
                                              );
$oDaoTfdPedidoRegulado->sql_record($sSql);
if ($oDaoTfdPedidoRegulado->numrows == 0) {

  echo "<script>alert('Para você agendar a saída, o pedido deve ser regulado.');";
  echo "parent.db_iframe_saida.hide();</script>";
  exit;

}

/*
 * ==================================================
 *   BUSCA PARÂMETROS DE GERENCIAMENTO DE CAPACIDADE
 * ==================================================
 */
$sSql           = $oDaoTfdParametros->sql_query(null, 'tf11_i_utilizagradehorario');
$rsParametros   = $oDaoTfdParametros->sql_record($sSql);
if ($oDaoTfdParametros->numrows > 0) {
  
  db_fieldsmemory($rsParametros, 0);

}
  
/*
 * ==================================================
 *               CADASTRO DE SAÍDA
 * ==================================================
 */
if (isset($incluir)) {
	
  /*** Cadastrar saída ***/ 
  db_inicio_transacao();
  $aHora = explode(' ## ', @$tf17_c_horasaida);
  $sHora = @$aHora[0];
  $oDaoTfdAgendaSaida->tf17_c_horasaida = $sHora;
  $oDaoTfdAgendaSaida->tf17_i_login = db_getsession('DB_id_usuario');
  $oDaoTfdAgendaSaida->tf17_d_datasistema = date('Y-m-d', db_getsession('DB_datausu'));
  $oDaoTfdAgendaSaida->tf17_c_horasistema = date('H:i');
  $oDaoTfdAgendaSaida->incluir($tf17_i_codigo);
  db_fim_transacao($oDaoTfdAgendaSaida->erro_status == '0' ? true : false);
  
} elseif (isset($alterar)) {
  
  if (isset($lAlterarSaida) && $lAlterarSaida == 1) {
  
    $db_opcao = 2;
    db_inicio_transacao();
    $aHora = explode(' ## ', @$tf17_c_horasaida);
    $sHora = @$aHora[0];
    $oDaoTfdAgendaSaida->tf17_c_horasaida = $sHora;
    $oDaoTfdAgendaSaida->alterar($tf17_i_codigo);
    db_fim_transacao($oDaoTfdAgendaSaida->erro_status == '0' ? true : false);

  }
  
} elseif (isset($excluir) && isset($lAlterarSaida) && $lAlterarSaida == 1) {

  db_inicio_transacao();
  $oDaoTfdAgendaSaida->excluir($tf17_i_codigo);
  db_fim_transacao($oDaoTfdAgendaSaida->erro_status == '0' ? true : false);

}


/*
 * ===========================================================
 *             INDICAR VEÍCULO E PASSAGEIROS
 * ===========================================================
 */ 
if ($db_indicarVeiculo == 1 && isset($sPassageirosCGS) && $sPassageirosCGS != "") {
        
  $aPassageirosSelecionados = explode('#', $sPassageirosSelecionados);
  $aPassageirosCGS          = explode('#', $sPassageirosCGS);
        
}

if ($db_indicarVeiculo == 1 && isset($aPassageirosCGS) && count($aPassageirosCGS) > 0)  {

  $sCampos   = " * "; 
  $sWhere    = " tf18_d_datasaida  = '$tf17_d_datasaida' AND ";
  $sWhere   .= " tf18_c_horasaida  = '$sHora' AND ";
  $sWhere   .= " tf18_i_destino    = '$tf25_i_destino' ";
  $sSql      = $oDaoTfdVeiculoDestino->sql_query2(null, $sCampos, null, $sWhere);
  $rsResult  = $oDaoTfdVeiculoDestino->sql_record($sSql);
  
  if ($oDaoTfdVeiculoDestino->numrows == 0) {
    db_inicio_transacao();
    if (empty($tf18_i_motorista)) {
      unset($GLOBALS['HTTP_POST_VARS']['tf18_i_motorista']); 
    }
    $aHoraVet                                  = explode(' ## ', $tf17_c_horasaida);
    $tf18_c_horasaida                          = $aHoraVet[0];
    $tf18_d_datasaida                          = substr($tf17_d_datasaida, 6, 4).'-'.substr($tf17_d_datasaida, 3, 2);
    $tf18_d_datasaida                         .= '-'.substr($tf17_d_datasaida, 0, 2);
    $aHoraVet                                  = explode(' ## ', $tf18_c_horaretorno);
    $tf18_c_horaretorno                        = $aHoraVet[0];
    $aDataretorno 														 = explode('/', $tf18_d_dataretorno);
    $tf18_d_dataretorno                        = $aDataretorno[2].'-'.$aDataretorno[1].'-'.$aDataretorno[0];
    $oDaoTfdVeiculoDestino->tf18_i_veiculo     = $tf18_i_veiculo;
    $oDaoTfdVeiculoDestino->tf18_i_motorista   = ($tf18_i_motorista != '' ? $tf18_i_motorista : null);
    $oDaoTfdVeiculoDestino->tf18_d_datasaida   = $tf18_d_datasaida;
    $oDaoTfdVeiculoDestino->tf18_c_horasaida   = $tf18_c_horasaida;
    $oDaoTfdVeiculoDestino->tf18_d_dataretorno = $tf18_d_dataretorno;
    $oDaoTfdVeiculoDestino->tf18_c_horaretorno = $tf18_c_horaretorno;
    $oDaoTfdVeiculoDestino->tf18_i_destino     = $tf25_i_destino; 
    $oDaoTfdVeiculoDestino->tf18_c_localsaida  = $tf17_c_localsaida; 
    
    $oDaoTfdVeiculoDestino->incluir(null);
    
    if ($oDaoTfdVeiculoDestino->erro_status != '0') {
    
      $oDaoTfdPassageiroVeiculo->tf19_i_veiculodestino = $oDaoTfdVeiculoDestino->tf18_i_codigo;

      for ($iCont = 0;$iCont < count($aPassageirosSelecionados); $iCont++) {
     
        /*
          Cada posição do vetor $aPassageirosSelecionados possui as seguintes informações dispostas da seguinte forma:
          CGS,TFD,TIPO,FICA,COLO
        */
        $aInfoPassageiro                                 = explode(',', $aPassageirosSelecionados[$iCont]);
        $oDaoTfdPassageiroVeiculo->tf19_i_cgsund         = $aInfoPassageiro[0];
        $oDaoTfdPassageiroVeiculo->tf19_i_pedidotfd      = $aInfoPassageiro[1];
        $oDaoTfdPassageiroVeiculo->tf19_i_tipopassageiro = $aInfoPassageiro[2];
        $oDaoTfdPassageiroVeiculo->tf19_i_fica           = $aInfoPassageiro[3];
        $oDaoTfdPassageiroVeiculo->tf19_i_colo           = $aInfoPassageiro[4];
        $oDaoTfdPassageiroVeiculo->tf19_i_valido         = 1;
        $oDaoTfdPassageiroVeiculo->incluir(null);
      
        if ($oDaoTfdPassageiroVeiculo->erro_status == '0') {
      
          $oDaoTfdVeiculoDestino->erro_status = '0';
          $oDaoTfdVeiculoDestino->erro_sql    = $oDaoTfdPassageiroVeiculo->erro_sql;
          $oDaoTfdVeiculoDestino->erro_campo  = $oDaoTfdPassageiroVeiculo->erro_campo;
          $oDaoTfdVeiculoDestino->erro_banco  = $oDaoTfdPassageiroVeiculo->erro_banco;
          $oDaoTfdVeiculoDestino->erro_msg    = $oDaoTfdPassageiroVeiculo->erro_msg;
          break;
      
        }

      }
      db_fim_transacao($oDaoTfdVeiculoDestino->erro_status == '0' ? true : false);
      
    }
 

  } else {
    
    db_inicio_transacao();
    if (empty($tf18_i_motorista)) {
      unset($GLOBALS['HTTP_POST_VARS']['tf18_i_motorista']); 
    }
    $oDaoTfdVeiculoDestino->erro_status     = '0';
    $oDaoTfdVeiculoDestino->erro_sql        = $oDaoTfdPassageiroVeiculo->erro_sql;
    $oDaoTfdVeiculoDestino->erro_campo      = $oDaoTfdPassageiroVeiculo->erro_campo;
    $oDaoTfdVeiculoDestino->erro_banco      = $oDaoTfdPassageiroVeiculo->erro_banco;
    $oDaoTfdVeiculoDestino->erro_msg        = $oDaoTfdPassageiroVeiculo->erro_msg;
    $aHoraVet                                  = explode(' ## ', $tf17_c_horasaida);
    $tf18_c_horasaida                          = $aHoraVet[0];
    $tf18_d_datasaida                          = substr($tf17_d_datasaida, 6, 4).'-'.substr($tf17_d_datasaida, 3, 2).
                                                 '-'.substr($tf17_d_datasaida, 0, 2);
    $aHoraVet                                  = explode(' ## ', $tf18_c_horaretorno);
    $tf18_c_horaretorno                        = $aHoraVet[0];
    $tf18_d_dataretorno                        = substr($tf18_d_dataretorno, 6, 4).'-'.substr($tf18_d_dataretorno, 3, 2).
                                                 '-'.substr($tf18_d_dataretorno, 0, 2);
    $oDaoTfdVeiculoDestino->tf18_i_veiculo     = $tf18_i_veiculo;
    $oDaoTfdVeiculoDestino->tf18_i_motorista   = ($tf18_i_motorista != '' ? $tf18_i_motorista : null);
    $oDaoTfdVeiculoDestino->tf18_d_datasaida   = $tf18_d_datasaida;
    $oDaoTfdVeiculoDestino->tf18_c_horasaida   = $tf18_c_horasaida;
    $oDaoTfdVeiculoDestino->tf18_d_dataretorno = $tf18_d_dataretorno;
    $oDaoTfdVeiculoDestino->tf18_c_horaretorno = $tf18_c_horaretorno;
    $oDaoTfdVeiculoDestino->tf18_i_destino     = $tf25_i_destino; 
    $oDaoTfdVeiculoDestino->tf18_c_localsaida  = $tf17_c_localsaida; 
    $oDaoTfdVeiculoDestino->alterar($tf18_i_codigo);
    if ($oDaoTfdVeiculoDestino->erro_status != '0') {

      /* busco todos os passageiros que já estavam vinculados ao veículo, para poder verificar 
         quais foram incluídos, excluídos, etc.
      */
      $sSql      = $oDaoTfdPassageiroVeiculo->sql_query(null, 'tf19_i_cgsund, tf19_i_tipopassageiro,'.
                                                        ' tf19_i_codigo, tf19_i_pedidotfd', '',
                                                        " tf19_i_veiculodestino = $tf18_i_codigo and tf19_i_valido = 1"
                                                     );
      $rsPassag  = $oDaoTfdPassageiroVeiculo->sql_record($sSql);
      $aListaCgs = Array();
      for ($iCont = 0; $iCont < $oDaoTfdPassageiroVeiculo->numrows; $iCont++) {
         
        $oDadosPassag   = db_utils::fieldsmemory($rsPassag, $iCont);
        $aListaCgs[]    = $oDadosPassag->tf19_i_cgsund;
        $aListaCodigo[] = $oDadosPassag->tf19_i_codigo;

      }
    
      $oDaoTfdPassageiroVeiculo->tf19_i_veiculodestino = $oDaoTfdVeiculoDestino->tf18_i_codigo;
      /* for com a verificação de quais passageiros deverão ser incluídos ou alterados no veículo */
      for ($iCont = 0; $iCont < count($aPassageirosSelecionados); $iCont++) {
        /*
          Cada posição do vetor $aPassageirosSelecionados possui as seguintes informações dispostas da seguinte forma:
          CGS,TFD,TIPO,FICA,COLO
        */
        $aInfoPassageiro                                 = explode(',', $aPassageirosSelecionados[$iCont]);

        $oDaoTfdPassageiroVeiculo->tf19_i_cgsund         = $aInfoPassageiro[0];
        $oDaoTfdPassageiroVeiculo->tf19_i_pedidotfd      = $aInfoPassageiro[1];
        $oDaoTfdPassageiroVeiculo->tf19_i_tipopassageiro = $aInfoPassageiro[2];
        $oDaoTfdPassageiroVeiculo->tf19_i_fica           = $aInfoPassageiro[3];
        $oDaoTfdPassageiroVeiculo->tf19_i_colo           = $aInfoPassageiro[4];
        $oDaoTfdPassageiroVeiculo->tf19_i_valido         = 1;
        // Se o CGS não estiver no $aListaCgs (vetor dos CGS que já estavam vinculados ao veículo), deve ser incluído
        if (!in_array($aPassageirosCGS[$iCont], $aListaCgs)) {
          $oDaoTfdPassageiroVeiculo->incluir(null);
        } else { // Deverá ser alterado

          $iCod = $aListaCodigo[array_search($aPassageirosCGS[$iCont], $aListaCgs)];
          $oDaoTfdPassageiroVeiculo->tf19_i_codigo = $iCod;
          $oDaoTfdPassageiroVeiculo->alterar($iCod);

        }

        if ($oDaoTfdPassageiroVeiculo->erro_status == '0') {

          $oDaoTfdVeiculoDestino->erro_status = '0';
          $oDaoTfdVeiculoDestino->erro_sql    = $oDaoTfdPassageiroVeiculo->erro_sql;
          $oDaoTfdVeiculoDestino->erro_campo  = $oDaoTfdPassageiroVeiculo->erro_campo;
          $oDaoTfdVeiculoDestino->erro_banco  = $oDaoTfdPassageiroVeiculo->erro_banco;
          $oDaoTfdVeiculoDestino->erro_msg    = $oDaoTfdPassageiroVeiculo->erro_msg;
          break;
      
        }

      }

      /* Verifico os passageiros que foram excluídos. Como não há exclusão, somente o status é alterado */
      if ($oDaoTfdVeiculoDestino->erro_status != '0') {

        /* limpo as variáveis da classe */
        $oDaoTfdPassageiroVeiculo->tf19_i_cgsund         = '';
        $oDaoTfdPassageiroVeiculo->tf19_i_pedidotfd      = '';
        $oDaoTfdPassageiroVeiculo->tf19_i_tipopassageiro = '';
        $oDaoTfdPassageiroVeiculo->tf19_i_veiculodestino = '';
        $oDaoTfdPassageiroVeiculo->tf19_i_valido         = '';
        $oDaoTfdPassageiroVeiculo->tf19_i_codigo         = '';
        $oDaoTfdPassageiroVeiculo->tf19_i_fica           = '';
        $oDaoTfdPassageiroVeiculo->tf19_i_colo           = '';

        for ($iCont = 0;$iCont < count($aListaCgs); $iCont++) {
        
          // Se o CGS não está na lista dos CGS marcados no formulário, ele foi excluído (desmarcado)
          if (!in_array($aListaCgs[$iCont], $aPassageirosCGS)) {
  
            // mudo a situação para inválido (2)
            $oDaoTfdPassageiroVeiculo->tf19_i_valido = 2;
            $oDaoTfdPassageiroVeiculo->tf19_i_codigo = $aListaCodigo[$iCont];
            $oDaoTfdPassageiroVeiculo->alterar($aListaCodigo[$iCont]);
            if ($oDaoTfdPassageiroVeiculo->erro_status == '0') {
   
              $oDaoTfdVeiculoDestino->erro_status = '0';
              $oDaoTfdVeiculoDestino->erro_sql    = $oDaoTfdPassageiroVeiculo->erro_sql;
              $oDaoTfdVeiculoDestino->erro_campo  = $oDaoTfdPassageiroVeiculo->erro_campo;
              $oDaoTfdVeiculoDestino->erro_banco  = $oDaoTfdPassageiroVeiculo->erro_banco;
              $oDaoTfdVeiculoDestino->erro_msg    = $oDaoTfdPassageiroVeiculo->erro_msg;
              break;
  
            }
  
          }
 
        } // fim for
  
      } // fim if

    } // fim if verificaco status apos alteracao da tfd_veiculodestino 
    db_fim_transacao($oDaoTfdVeiculoDestino->erro_status == '0' ? true : false);  

  }

}
/* Atualiza dados do formulário */
if (!isset($incluir) && !isset($alterar) && !isset($excluir)) {
  
  $sSql = $oDaoTfdAgendaSaida->sql_query2(null, '*', null, " tf17_i_pedidotfd = $tf17_i_pedidotfd");
  $rs   = $oDaoTfdAgendaSaida->sql_record($sSql);
  if($oDaoTfdAgendaSaida->numrows > 0) {
    
    $db_opcao = 2;
    db_fieldsmemory($rs, 0);
     
    /* Indicação de veiculo */
    $sCampos       = " tf18_i_veiculo, tf18_i_motorista, tf18_d_dataretorno, tf18_c_horaretorno "; 
    $sWhere        = " tf18_d_datasaida  = '$tf17_d_datasaida' AND ";
    $sWhere       .= " tf18_c_horasaida  = '$tf17_c_horasaida' AND ";
    $sWhere       .= " tf18_i_destino    = '$tf25_i_destino' ";
    $sSql          = $oDaoTfdVeiculoDestino->sql_query2(null, $sCampos, null, $sWhere);
    $rsResult      = $oDaoTfdVeiculoDestino->sql_record($sSql);
    $lAlterarSaida = 1;
    if ($oDaoTfdVeiculoDestino->numrows > 0) {
    
      db_fieldsmemory($rsResult, 0);
      if ($tf11_i_utilizagradehorario == 1) {
      
        $oDaoTfdGradeHorarios = db_utils::getdao('tfd_gradehorarios');
        $aData                = explode('-', $tf17_d_datasaida);
        $iDiasemana           = date('w', mktime(0, 0, 0, $aData[1], $aData[2], $aData[0])) + 1;
        $sCampos              = 'tf02_i_lotacao';
        $sWhere               = "(('$tf17_d_datasaida' >= tf02_d_validadeini and";
        $sWhere              .= " tf02_d_validadefim is null) or";
        $sWhere              .= " (tf02_d_validadefim is not null and '$tf17_d_datasaida' between ";
        $sWhere              .= "tf02_d_validadeini and tf02_d_validadefim)) and";
        $sWhere              .= " tf02_i_destino = $tf25_i_destino ";
        $sWhere              .= " and tf02_i_diasemana = $iDiasemana and tf02_c_horario = '$tf17_c_horasaida' ";
        $sWhere              .= " LIMIT 1 ";   
        $sSql                 = $oDaoTfdGradeHorarios->sql_query(null,  $sCampos, null, $sWhere);
        $rsGradeHorarios      = $oDaoTfdGradeHorarios->sql_record($sSql);
        if ($oDaoTfdGradeHorarios->numrows > 0) {
          
          $oDados = db_utils::fieldsmemory($rsGradeHorarios, 0);
          $total  = $oDados->tf02_i_lotacao;
          $livre  = $oDados->tf02_i_lotacao;
        
        }
                                                          
      }
      $lAlterarSaida = 2;
      $db_opcao      = 3;
                           
    }

  } 

}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title> 
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load("prototype.js, datagrid.widget.js, strings.js, webseller.js, scripts.js");
    db_app::load("grid.style.css, estilos.css");
   ?>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
    <center>
      <br><br>
      <table width="700" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
            <center>
              <fieldset style='width: 100%;'> <legend><b>Agendamento de Saída</b></legend>
              <?
              require_once("forms/db_frmtfd_agendasaida.php");
              ?>
              </fieldset>
            </center>
          </td>
        </tr>
      </table>
    </center>
  </body>
</html>
<script>
js_tabulacaoforms("form1", "tf17_i_pedidotfd", true, 1, "tf17_i_pedidotfd", true);
</script>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)) {

  if($oDaoTfdAgendaSaida->erro_status == '0') {

    $oDaoTfdAgendaSaida->erro(true, false);
    db_redireciona('tfd4_tfd_agendasaida001.php?tf17_i_pedidotfd='.
                   $tf17_i_pedidotfd.'&tf01_i_cgsund=\''.
                   '+document.getElementById(\'tf01_i_cgsund\').value+\'&z01_v_nome='.$z01_v_nome
                  );

  } else {

    $oDaoTfdAgendaSaida->erro(true, false);
    db_redireciona('tfd4_tfd_agendasaida001.php?tf17_i_pedidotfd='.
                   $tf17_i_pedidotfd.'&tf01_i_cgsund=\''.
                   '+document.getElementById(\'tf01_i_cgsund\').value+\'&z01_v_nome='.$z01_v_nome
                  );


  }

}
?>