<?php
/*
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
db_postmemory($HTTP_POST_VARS);

define("CALCULAR_PAGO_SIM", "1");
define("CALCULAR_PAGO_NAO", "2");

/**
 * Constante com o código de retorno da verificacalculo, quando o IPTU está quitado
 * @todo Garantir que este código seja o mesmo retornado pela PL e do cálculo parcial
 */
define("IPTU_QUITADO", 27);

$lMostrarBotaoCalcular = 'visible';
if( isset($calcular) ){
  $lMostrarBotaoCalcular = 'none';
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <form name="form1" action="" method="post" >

      <input type="hidden" id="calcular_pago_sim" value="<?php echo CALCULAR_PAGO_SIM ?>">
      <input type="hidden" id="calcular_pago_nao" value="<?php echo CALCULAR_PAGO_NAO ?>">

      <fieldset>
        <legend>Cálculo Geral</legend>
        <table>
          <? if(isset($calcular)){ ?>
            <tr>
              <td>
                <?php
                  db_criatermometro('termometro','Concluido...','blue',1);
                ?>
              </td>
            </tr>
          <? } else {?>
            <tr>
              <td><strong><label for="calcular_pago">Calcular IPTU Quitado:</label></strong></td>
              <td>
              <?php
                $aOpcoes = array( "0"                => "Selecione",
                                  CALCULAR_PAGO_SIM  => "Sim",
                                  CALCULAR_PAGO_NAO  => "Não" );
                db_select("calcular_pago", $aOpcoes, true, 2, "style=\"width: 75px;\"");
              ?>
              </td>
            </tr>
            <tr>
              <td><strong><label for="financeiro">Calcular Financeiro:</label></strong></td>
              <td>
              <?php
                $x = array("0"=>"Sim","1"=>"Não");
                db_select("financeiro", $x, true, 2, "style=\"width: 75px;\"");
              ?>
              </td>
            </tr>
            <tr>
              <td><strong>Ano:</strong></td>
              <td>
                <?php
                $result = db_query("select distinct j18_anousu from cfiptu order by j18_anousu desc");
                if(pg_numrows($result) > 0){?>
                  <select name="anousu" style="width: 75px;">
                  <? for($i=0;$i<pg_numrows($result);$i++){
                    db_fieldsmemory($result,$i); ?>
                    <option value='<?=$j18_anousu?>'><?=$j18_anousu?></option>
                  <? } ?>
                  </select>
                <? } ?>
              </td>
            </tr>
          <?}?>
        </table>
      </fieldset>
      <input name="calcular" type="submit"  style="display: <?php echo $lMostrarBotaoCalcular;?>;" id="calcular" value="Calcular" onclick="return confirmaCalculo();" />
    </form>
    <?php
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));

      flush();

      if(isset($calcular)){

        /**
         * Verificamos se o calculo do exercicio possui receitas vencidas
         */
        $oDaocfiptu = new cl_cfiptu;
        $sMensagem  = $oDaocfiptu->verificaReceitasInvalidas($anousu);
        if( !empty( $sMensagem ) ){

          $sMensagemRetorno  = "Erro ao executar o Cálculo!\n";
          $sMensagemRetorno .= $sMensagem;
          db_msgbox($sMensagemRetorno);
          db_redireciona('cad4_calciptugeral.php');
        }

        $erro = false;
        set_time_limit(0);
        $result = db_query("begin;");
        echo "<pre>Inicio: ".date("d/m/Y H:i:s") . "<br/>";

        $xInicio = time();
        $sql     = "select j01_matric from iptubase";
        $result  = db_query( $sql );
        $numrows = pg_numrows($result);
        if($numrows == 0){
          echo "<script>alert('Sem matrículas a calcular!');</script>";
        }else{

          $total_reg     = $numrows;
          $sqlnextval    = "select nextval('iptucalclog_j27_codigo_seq') as j27_codigo";
          $resultnextval = db_query($sqlnextval) or die($sqlnextval);

          if ($resultnextval == false) {
            echo "<script>alert('Erro ao gerar sequencia!');</script>";
          } else {

            db_fieldsmemory($resultnextval,0);
            $insert       = "insert into iptucalclog values ($j27_codigo,$anousu,'".date('Y-m-d',db_getsession("DB_datausu"))."','".db_hora()."',".db_getsession('DB_id_usuario').",false," . $numrows . ")";
            $resultinsert = db_query($insert) or die($insert);
            if ($resultinsert == false) {
              echo "<script>alert('Erro do gerar lancamento na tabela iptucalclog!');</script>";
            } else {

              $sListaMatricErro = "";
              $sVirgula         = "";
              $contadorRegistros = 1;
              for($ii = 0; $ii < $total_reg; $ii++){

                db_fieldsmemory($result,$ii);
                db_atutermometro($contadorRegistros,$total_reg,'termometro');
                $contadorRegistros++;

                db_inicio_transacao();

      					$resultcfiptu = db_query("select distinct j18_anousu, j18_permvenc from cfiptu where j18_anousu = $anousu");
      					$j18_permvenc = 1;
      					if(pg_numrows($resultcfiptu) > 0){
      						db_fieldsmemory($resultcfiptu,0);
      					}

                $lCalcula     = true;
                $sMensagemLog = "";
                $iCodigoLog   = 0;

                /**
                 * Se o usuário informou que não deve ser recalculado IPTU quitado,
                 * validamos se o iptu realmente está quitado
                 */
                if ($calcular_pago == CALCULAR_PAGO_NAO) {

                  $sSqlVerifica = "select fc_iptu_verificacalculo($j01_matric::integer,$anousu::integer, 0, 0) as verificacalculo";
                  $rsVerifica   = db_query($sSqlVerifica);

                  if ( empty($rsVerifica) ) {

                    $sMensagemLog = "Erro ao verificar cálculos anteriores do exercício atual";
                    $lCalcula     = false;
                  }

                  $oVerificaCalculo = db_utils::fieldsMemory($rsVerifica, 0);
                  $iCodigoLog       = substr($oVerificaCalculo->verificacalculo, 3, 2);

                  if ($iCodigoLog == IPTU_QUITADO) {

                    $sSqlMensagemLog = "select j62_descr from iptucadlogcalc where j62_codigo = $iCodigoLog";
                    $rsMensagemLog   = db_query($sSqlMensagemLog);

                    if ( empty($rsMensagemLog) ) {

                      $sMensagemLog = "Erro buscar mensagem de log";
                      $lCalcula     = false;
                    } else {

                      $oMensagemLog = db_utils::fieldsMemory($rsMensagemLog, 0);
                      $sMensagemLog = trim( preg_replace( '/^[0-9]*/', '', trim($oMensagemLog->j62_descr) ) );
                      $lCalcula     = false;
                    }
                  }
                }

                /**
                 * De acordo com as validações acima, é verificado se deve ser calculado o iptu desta matrícula;
                 */
                if ($lCalcula) {

                  if ($j18_permvenc == 0) {
                    $j18_permvenc = 1;
                  }

                  if ($j18_permvenc == 1) {
                    $sql = "select fc_calculoiptu($j01_matric::integer,$anousu::integer,".($financeiro==0?"true":"false")."::boolean,false::boolean,false::boolean,false::boolean,false::boolean,array['0','0','0'])";
                  } elseif ($j18_permvenc == 2) {
                    $sql = "select fc_calculoiptu($j01_matric::integer,$anousu::integer,".($financeiro==0?"true":"false")."::boolean,false::boolean,false::boolean,false::boolean,false::boolean,array['0','0'])";
                  }

                  $erro       = false;
                  $resultcalc = db_query($sql);

                  if ($resultcalc) {

                    db_fieldsmemory($resultcalc,0);

                    preg_match('/[0-9]*/', trim($fc_calculoiptu), $aTipoLogCalc);

                    $insert       = "insert into iptucalclogmat values ($j27_codigo,$j01_matric,".$aTipoLogCalc[0].",'".trim(preg_replace('/^[0-9]*/', '',trim($fc_calculoiptu)))."')";
                    $resultinsert = db_query($insert) or die($insert);
                    if ($resultinsert == false) {

                      $erro              = true;
                      $sListaMatricErro .= $sVirgula . $j01_matric;
                      $sVirgula          = ", ";
                    }
                  } else {

                    $erro              = true;
                    $sListaMatricErro .= $sVirgula . $j01_matric;
                    $sVirgula          = ", ";
                  }
                } else {

                  /**
                   * Inserimos a mensagem de log oriunda da validação de recalculo de iptu quitado
                   */
                  $sInsert  = "insert into iptucalclogmat values ($j27_codigo,$j01_matric,$iCodigoLog,'$sMensagemLog')";
                  $rsInsert = db_query($sInsert);

                  if (empty($rsInsert)) {

                    $erro              = true;
                    $sListaMatricErro .= $sVirgula . $j01_matric;
                    $sVirgula          = ", ";
                  }
                }

                db_fim_transacao($erro);
              }

              echo "Fim: ".date("d/m/Y H:i:s") . "<br/>";
              echo "Tempo Decorrido: ".db_formatatempodecorrido($xInicio, time())."<br/>";
              if ($erro == true) {
                echo "<script>alert('Ocorreu um erro durante o processamento! Matricula(s): $sListaMatricErro');</script>";
              } else {
                echo "<script>alert('Cálculo Efetuado.');</script>";
              }
              flush();
            }
          }
        }
      }
?>
  </div>
</body>
<script type="text/javascript">
  function termo(qual,total){
  // document.getElementById('termometro').innerHTML='processando registro... '+qual+' de '+total;
  }

  function confirmaCalculo(){

    var sRPC            = "cad4_calciptu.RPC.php";
    var lExecutaCalculo = true;

    if ($F('calcular_pago') == 0) {

      alert("O Campo Calcular IPTU Quitado é de preenchimento obrigatório.");
      return false;
    }

    if ($F('calcular_pago') == $F('calcular_pago_sim')) {

      var oParametros = {
        "sExecucao"  : "verificaParametros",
        "iAnousu"    : document.form1.anousu.value
      }

      new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro){

        if ( lErro ) {

          alert(oRetorno.sMensagem);
          return false;
        }

        if ( oRetorno.lIrregular ) {

          alert(oRetorno.sMensagem);
          lExecutaCalculo = false;
          return false;
        }
      }).asynchronous(false).execute();
    }

    if ( lExecutaCalculo ) {

      if ( confirm("Você tem certeza que deseja processar o cálculo geral de IPTU?") ) {
        return true;
      }
    }

    return false;
  }
</script>
</html>
