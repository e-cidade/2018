<?php
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

require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");

$oGet = db_utils::postMemory($_GET);

# Datas configuradas para buscar no banco de dados
list($iDiaInicial, $iMesInicial, $iAnoInicial) = explode ("/", $oGet->sDataInicial);
$dDataInicialBanco = "{$iAnoInicial}-{$iMesInicial}-{$iDiaInicial}";

list($iDiaFinal,   $iMesFinal,   $iAnoFinal)   = explode ("/", $oGet->sDataFinal);
$dDataFinalBanco   = "{$iAnoFinal}-{$iMesFinal}-{$iDiaFinal}";


# Valida configurando variaveis para buscar no banco e apresentar no HEAD do PDF
if ( $oGet->iTipoUsuario == 't' ) {
  
  $sTipoUsuario = '0,1';
  $sHeadUsuario = "Todos";
} else {
  
  $sTipoUsuario = $oGet->iTipoUsuario;
  # Configuracao de cabecalho
  if ($oGet->iTipoUsuario == 1) {
    $sHeadUsuario = "Externos";
  } else {
    $sHeadUsuario = "Internos";
  }
}


$aWhereData = array();
if (trim($oGet->sDataInicial) != '')  {  
  $aWhereData[] = " db_logsacessa.data >= '{$dDataInicialBanco}'::date ";
}

if (trim($oGet->sDataFinal) != '')  {  
  $aWhereData[] = " db_logsacessa.data <= '{$dDataFinalBanco}'::date ";
}

$sWhereData = implode(" and ", $aWhereData);

$sSqlUltimosAcessos  = " SELECT db_usuarios.id_usuario,                                            ";
$sSqlUltimosAcessos .= "        db_usuarios.nome,                                                  ";
$sSqlUltimosAcessos .= "        db_usuarios.login,                                                 ";
$sSqlUltimosAcessos .= "        db_usuacgm.cgmlogin,                                               ";
$sSqlUltimosAcessos .= "                                                                           ";
$sSqlUltimosAcessos .= "        (SELECT data                                                       ";
$sSqlUltimosAcessos .= "           FROM db_logsacessa                                              ";
$sSqlUltimosAcessos .= "          WHERE db_logsacessa.id_usuario = db_usuarios.id_usuario          ";
$sSqlUltimosAcessos .= "            AND {$sWhereData}                                              ";
$sSqlUltimosAcessos .= "            AND db_logsacessa.instit IN ({$oGet->sListaInstit})            ";
$sSqlUltimosAcessos .= "          ORDER BY db_logsacessa.codsequen DESC                            ";
$sSqlUltimosAcessos .= "          LIMIT 1)         AS data_ultimo_acesso,                          ";
$sSqlUltimosAcessos .= "                                                                           ";
$sSqlUltimosAcessos .= "        (SELECT db_config.nomeinst                                         ";
$sSqlUltimosAcessos .= "           FROM db_logsacessa                                              ";
$sSqlUltimosAcessos .= "                JOIN db_config ON db_config.codigo = db_logsacessa.instit  ";
$sSqlUltimosAcessos .= "          WHERE db_logsacessa.id_usuario = db_usuarios.id_usuario          ";
$sSqlUltimosAcessos .= "            AND {$sWhereData}                                              ";
$sSqlUltimosAcessos .= "            AND db_logsacessa.instit IN ({$oGet->sListaInstit})            ";
$sSqlUltimosAcessos .= "          ORDER BY db_logsacessa.codsequen DESC                            ";
$sSqlUltimosAcessos .= "          LIMIT 1)         AS instit_ultimo_acesso,                        ";
$sSqlUltimosAcessos .= "                                                                           ";
$sSqlUltimosAcessos .= "        CASE                                                               ";
$sSqlUltimosAcessos .= "          WHEN db_usuarios.usuext = 1 THEN 'Externo'::TEXT                 ";
$sSqlUltimosAcessos .= "          WHEN db_usuarios.usuext = 0 THEN 'Interno'::TEXT                 ";
$sSqlUltimosAcessos .= "          ELSE 'Todos'::TEXT                                               ";
$sSqlUltimosAcessos .= "        end                AS tipo,                                        ";
$sSqlUltimosAcessos .= "        CASE                                                               ";
$sSqlUltimosAcessos .= "          WHEN usuarioativo = '1' THEN 'Sim'::TEXT                         ";
$sSqlUltimosAcessos .= "          ELSE 'Não'::TEXT                                                 ";
$sSqlUltimosAcessos .= "        end                AS ativo,                                       ";
$sSqlUltimosAcessos .= "        CASE                                                               ";
$sSqlUltimosAcessos .= "          WHEN administrador = 1 THEN 'Sim'::TEXT                          ";
$sSqlUltimosAcessos .= "          ELSE 'Não'::TEXT                                                 ";
$sSqlUltimosAcessos .= "        end                AS admin                                        ";
$sSqlUltimosAcessos .= "   FROM db_usuarios                                                        ";
$sSqlUltimosAcessos .= "        JOIN db_usuacgm  ON db_usuacgm.id_usuario  = db_usuarios.id_usuario";
$sSqlUltimosAcessos .= "        JOIN db_userinst ON db_userinst.id_usuario = db_usuarios.id_usuario";
$sSqlUltimosAcessos .= "  WHERE db_usuarios.usuarioativo = 1                                       ";
$sSqlUltimosAcessos .= "    AND db_usuarios.usuext       IN ($sTipoUsuario)                        ";
$sSqlUltimosAcessos .= "    AND db_userinst.id_instit    IN ({$oGet->sListaInstit})                ";
$sSqlUltimosAcessos .= " ORDER BY db_usuarios.id_usuario,                                          ";
$sSqlUltimosAcessos .= "          db_usuarios.nome,                                                ";
$sSqlUltimosAcessos .= "          db_usuarios.login,                                               ";
$sSqlUltimosAcessos .= "          db_usuacgm.cgmlogin,                                             ";
$sSqlUltimosAcessos .= "          data_ultimo_acesso;                                              ";

// die ($sSqlUltimosAcessos);

$rsUltimosAcessos = db_query($sSqlUltimosAcessos);
$iUltimosacessos  = pg_num_rows($rsUltimosAcessos);

if ($iUltimosacessos == 0 ) {
  $sMsg = "Nenhum registro encontrado!";
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
}

/**
 * Configurações para o HEAD do cabecalho
 */
if ($oGet->sDataFinal != "") {
  $sFinalPeriodo = " à {$oGet->sDataFinal}";
} else {
  $sFinalPeriodo = " à ".date("d/m/Y");  
}

if ($oGet->iSomenteAtivo == 1) {
  $sHeadTiposUsuario = "Sim";
} else {
  $sHeadTiposUsuario = "Não";
}

$head2 = " Relatório de Acessos por Usuário ";
$head3 = " Período:  {$oGet->sDataInicial} ".$sFinalPeriodo;
$head4 = " Tipos de Usuário: ".$sHeadUsuario;
$head5 = " Usuários Ativos: ".$sHeadTiposUsuario;
$head6 = " Total de Registros: ".$iUltimosacessos;

$oPdf = new PDF();
$oPdf->Open();

$oPdf->AliasNbPages();
$oPdf->SetFillColor(235);

$iFonte    = 8;
$iAlt      = 5;
$iPreenche = 1;

imprimeCabecalho($oPdf,$iAlt,$iFonte);

for ($iRow = 0; $iRow < $iUltimosacessos; $iRow++) {
  
  $oDadosGerados = db_utils::fieldsMemory($rsUltimosAcessos, $iRow);
  
  if ( $oPdf->gety() > $oPdf->h-40) {
    imprimeCabecalho($oPdf,$iAlt,$iFonte);
    $iPreenche = 1;
  }
     
  if ($iPreenche == 1 ) {
    $iPreenche = 0;
  } else {
    $iPreenche = 1;
  }

  if (!empty($oDadosGerados->data_ultimo_acesso)) {
    
    /**
     * Preenche as células do relatório
     */
    $oPdf->Cell(20, $iAlt, $oDadosGerados->id_usuario                             , 0, 0, 'C', $iPreenche);
    $oPdf->Cell(70, $iAlt, substr($oDadosGerados->nome, 0, 65)                    , 0, 0, 'L', $iPreenche);
    $oPdf->Cell(25, $iAlt, $oDadosGerados->login                                  , 0, 0, 'L', $iPreenche);
    $oPdf->Cell(15, $iAlt, $oDadosGerados->cgmlogin                               , 0, 0, 'C', $iPreenche);
    $oPdf->Cell(25, $iAlt, db_formatar($oDadosGerados->data_ultimo_acesso, 'd')   , 0, 0, 'C', $iPreenche);
    $oPdf->Cell(75, $iAlt, substr($oDadosGerados->instit_ultimo_acesso, 0, 75)    , 0, 0, 'C', $iPreenche);
    $oPdf->Cell(20, $iAlt, $oDadosGerados->tipo                                   , 0, 0, 'C', $iPreenche);
    $oPdf->Cell(15, $iAlt, $oDadosGerados->ativo                                  , 0, 0, 'C', $iPreenche);
    $oPdf->Cell(15, $iAlt, $oDadosGerados->admin                                  , 0, 1, 'C', $iPreenche);
  }
}

$oPdf->Output();

/**
 * Funcao para imprimir o cabecalho
 */
function imprimeCabecalho($oPdf,$iAlt,$iFonte) {
  
  $oPdf->AddPage("L");
  
  $oPdf->SetFont('Arial', 'b', $iFonte);
  
  $oPdf->Cell(20, $iAlt, "Código"        ,1, 0, 'C', 1);
  $oPdf->Cell(70, $iAlt, "Nome"          ,1, 0, 'C', 1);
  $oPdf->Cell(25, $iAlt, "Login"         ,1, 0, 'C', 1);
  $oPdf->Cell(15, $iAlt, "CGM"           ,1, 0, 'C', 1);
  $oPdf->Cell(25, $iAlt, "Último Acesso" ,1, 0, 'C', 1);
  $oPdf->Cell(75, $iAlt, "Instituição"   ,1, 0, 'C', 1);
  $oPdf->Cell(20, $iAlt, "Tipo"          ,1, 0, 'C', 1);
  $oPdf->Cell(15, $iAlt, "Ativo"         ,1, 0, 'C', 1);
  $oPdf->Cell(15, $iAlt, "Admin"         ,1, 1, 'C', 1);
  
  $oPdf->SetFont('Arial', '', $iFonte);    
}

?>