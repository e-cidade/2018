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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));

db_postmemory( $_POST );

$oDaoCgsUnd               = new cl_cgs_und();
$oDaoTfdPedidoTfd         = new cl_tfd_pedidotfd();
$oDaoTfdVeiculoDestino    = new cl_tfd_veiculodestino();
$oDaoTfdPassageiroVeiculo = new cl_tfd_passageiroveiculo();
$oDaoTfdParametros        = new cl_tfd_parametros();

$db_opcao         = 1;
$db_opcaoNaoMudar = 1;

$rsParametros = $oDaoTfdParametros->sql_record($oDaoTfdParametros->sql_query());

if ($oDaoTfdParametros->numrows > 0) {
  $oParametros = db_utils::fieldsmemory($rsParametros, 0);
}

if (isset($confirmar)) {

  db_inicio_transacao();

  $aHoraVet                                  = explode(' ## ', $tf18_c_horasaida);
  $tf18_d_dataretorno                        = substr($tf18_d_dataretorno, 6, 4).'-'.substr($tf18_d_dataretorno, 3, 2).
                                               '-'.substr($tf18_d_dataretorno, 0, 2);
  $tf18_c_horasaida                          = $aHoraVet[0];

  $oDaoTfdVeiculoDestino->tf18_d_dataretorno = $tf18_d_dataretorno;
  $oDaoTfdVeiculoDestino->tf18_c_horasaida   = $tf18_c_horasaida;
  $oDaoTfdVeiculoDestino->incluir(null);

  if ($oDaoTfdVeiculoDestino->erro_status != '0') {

    $aPassageirosSelecionados                        = explode('#', $sPassageirosSelecionados);
    $aPassageirosCGS                                 = explode('#', $sPassageirosCGS);

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
  }

  db_fim_transacao($oDaoTfdVeiculoDestino->erro_status == '0' ? true : false);
} elseif (isset($alterar)) {
  
  db_inicio_transacao();

  if (empty($tf18_i_motorista)) {
    unset($GLOBALS['HTTP_POST_VARS']['tf18_i_motorista']); // deleto pq o default é 0 e isso viola restrição de chave estrangeira
  }

  $aHoraVet                                  = explode(' ## ', $tf18_c_horasaida);
  $tf18_d_dataretorno                        = substr($tf18_d_dataretorno, 6, 4).'-'.substr($tf18_d_dataretorno, 3, 2).
                                               '-'.substr($tf18_d_dataretorno, 0, 2);
  $tf18_c_horasaida                          = $aHoraVet[0]; 
  $oDaoTfdVeiculoDestino->tf18_d_dataretorno = $tf18_d_dataretorno;
  $oDaoTfdVeiculoDestino->tf18_c_horasaida   = $tf18_c_horasaida;
  $oDaoTfdVeiculoDestino->alterar($tf18_i_codigo);

  if ($oDaoTfdVeiculoDestino->erro_status != '0') {

    $aPassageirosSelecionados = explode('#', $sPassageirosSelecionados);
    $aPassageirosCGS          = explode('#', $sPassageirosCGS);
    
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

$sListaCgs = '';
if ((isset($tf18_i_codigo)) && ($tf18_i_codigo != '')) {
   
  $sSql     = $oDaoTfdVeiculoDestino->sql_query2($tf18_i_codigo, '*');
  $rsResult = $oDaoTfdVeiculoDestino->sql_record($sSql);
  if ($oDaoTfdVeiculoDestino->numrows > 0) {

    db_fieldsmemory($rsResult, 0);
    $db_opcao             = 2;
    $db_opcaoNaoMudar     = 3;

    $oDadosGrade          = db_utils::fieldsmemory($rsResult, 0);

    $aData                = explode('-', $oDadosGrade->tf18_d_datasaida);

                                           // somo 1 pq na tabela diasemana domingo é 1
    $iDiasemana           = date('w', mktime(0, 0, 0, $aData[1], $aData[2], $aData[0])) + 1;
    if ($oParametros->tf11_i_utilizagradehorario == 1) {
      $oDaoTfdGradeHorarios = db_utils::getdao('tfd_gradehorarios');
      $sCampos              = 'tf02_c_horario, tf02_c_localsaida, tf02_i_lotacao ';
      $sSql                 = $oDaoTfdGradeHorarios->sql_query(null,  'tf02_i_lotacao as total', null,
                                                               "(('$tf18_d_datasaida' >= tf02_d_validadeini".
                                                               " and tf02_d_validadefim is null) or".
                                                               " (tf02_d_validadefim is not null and ".
                                                               "'$tf18_d_datasaida' between ".
                                                               "tf02_d_validadeini and tf02_d_validadefim)) and".
                                                               " tf02_i_destino = ".$tf18_i_destino.
                                                               " and tf02_i_diasemana = $iDiasemana"
                                                              );
      $rs                   = $oDaoTfdGradeHorarios->sql_record($sSql);
      if ($oDaoTfdGradeHorarios->numrows > 0) {
        db_fieldsmemory($rs, 0);
      }
    } else {
      $total = $ve01_quantcapacidad;
    }

    $sSql      = $oDaoTfdPassageiroVeiculo->sql_query('', ' tf19_i_cgsund,tf19_i_tipopassageiro ', '',
                                                      " tf19_i_veiculodestino = $tf18_i_codigo and tf19_i_valido = 1 "
                                                     );
    $rsResult  = $oDaoTfdPassageiroVeiculo->sql_record($sSql);
    $sSep      = '';
    $numAcomp  = 0;
    $numPac    = 0;
    for ($iCont = 0; $iCont < $oDaoTfdPassageiroVeiculo->numrows; $iCont++) {

      $oDadosPassageiros = db_utils::fieldsmemory($rsResult, $iCont);
      $sListaCgs        .= $sSep.$oDadosPassageiros->tf19_i_cgsund;
      if ($oDadosPassageiros->tf19_i_tipopassageiro == 1) {
        $numPac++;
      } else {
        $numAcomp++;
      }

      $sSep = ',';
    }
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<?php
db_app::load("prototype.js, datagrid.widget.js, strings.js, webseller.js");
db_app::load(" grid.style.css");
?>
</head>
<body class="body-default">
<?php
require_once(modification("forms/db_frmtfd_indveiculo.php"));
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","tf01_i_cgsund",true,1,"tf01_i_cgsund",true);
</script>
<?php
if (isset($confirmar) || isset($alterar)) {

  if ($oDaoTfdVeiculoDestino->erro_status == '0') {

    $oDaoTfdVeiculoDestino->erro(true, false);
    $db_botao = true;
  } else {

    $oDaoTfdVeiculoDestino->erro(true, false);
    db_redireciona("tfd4_indveiculo001.php?tf18_i_codigo=".$oDaoTfdVeiculoDestino->tf18_i_codigo);
  }
}