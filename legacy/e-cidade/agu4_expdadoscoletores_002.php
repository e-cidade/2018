<?
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

require_once(modification("libs/db_libpessoal.php"));
require_once(modification("libs/db_utils.php"));

require_once(modification("model/agua/ArquivoExportaColetor.model.php")); // classe para geração de arquivos txt coletores
require_once(modification("model/agua/ExportaDadosColetores.model.php")); // classe com informações de exportação de dados

$geraArquivo    = false;

$oPost = db_utils::postMemory($_POST);

$iCodColetor    = $oPost->x46_sequencial;
$iAnoExportacao = $oPost->x21_exerc;
$iMesExportacao = $oPost->x21_mes;
$sListaDeRotas  = $oPost->listaRotas;
$sListaDeRuas   = $oPost->listaRotaRuas;
$iCgmLeiturista = $oPost->x21_numcgm;

$iDBInstituicao = db_getsession("DB_instit");
$iDBUsuario     = db_getsession("DB_id_usuario");
$iDBModulo      = db_getsession("DB_modulo");
$iDBAnoUsu      = db_getsession("DB_anousu");

$dDataAtual = date("d/m/Y");
$sHoraAtual = date("H:i");

$clExpDadosColetores = new clExpDadosColetores();
$clArqExpColetor     = new clArqExpColetor();

$iCodRecExcesso = $clExpDadosColetores->getCodReceitaExcesso($clExpDadosColetores->getAguaConfExcesso($iAnoExportacao));

if ($oPost->geraDadosArquivos == "t") {
  
  db_inicio_transacao();
  
  $iCodColetorExporta = $clExpDadosColetores->geraACExporta($iCodColetor, $iDBInstituicao, $iAnoExportacao, $iMesExportacao, 1);
  
  if ($clExpDadosColetores->iErroStatus == 0) {
    echo $clExpDadosColetores->sErroMsg;
    exit();
  }
  
  $sMotivo = "Processamento de matriculas para o coletor.";
  $iCodColetorExportaSituacao = $clExpDadosColetores->geraACExportaSituacao(null, $iDBUsuario, $dDataAtual, $sHoraAtual, $sMotivo, 1);
  
  if ($clExpDadosColetores->iErroStatus == 0) {
    echo $clExpDadosColetores->sErroMsg;
    exit();
  }

}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script type="text/javascript">
function js_voltar() {
  window.location = 'agu4_expdadoscoletores_001.php';   
}

</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
#table table {
  border-collapse: collapse;
}

.table_arquivos th {
  background-color: #999;
}

.table_arquivos th,.table_arquivos td {
  border-collapse: collapse;
  border: 1px solid #000;
}

.table_arquivos a {
  color: #0000FF;
}

.table_arquivos a:hover {
  text-decoration: underline;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

<form name="form1">
<?
db_menu($iDBUsuario, $iDBModulo, $iDBAnoUsu, $iDBInstituicao);

if ($oPost->geraDadosArquivos == "t") {
  
  $rsInfMatriculas = $clExpDadosColetores->getInformacoesMatriculas($sListaDeRotas, $sListaDeRuas);
  
  if ($clExpDadosColetores->iNumRowsMatriculas > 0) {
    
    $iArreTipo = $clExpDadosColetores->getArretipo($iAnoExportacao);
    
    $numRowsMatriculas = $clExpDadosColetores->iNumRowsMatriculas;
    
    iniciaTermometro();
    
    montaTelaTempo();
    
    for($i = 0; $i < $numRowsMatriculas; $i ++) {
      
      $oAguaBase = db_utils::fieldsMemory($rsInfMatriculas, $i);
      
      acionaTermometro($i, $numRowsMatriculas, $oAguaBase->x01_matric);
      
      //condição para não exportar matricula que leitura tenha sido realizada em determinado mês 
      if($clExpDadosColetores->statusMesMatricula($iAnoExportacao, $iMesExportacao, $oAguaBase->x01_matric) > 0) {
        continue;
      }else{
      	$geraArquivo = true;
      }
      
      $clExpDadosColetores->iCodColetorExporta      = $iCodColetorExporta;
      $clExpDadosColetores->iMatricula              = $oAguaBase->x01_matric;
      $clExpDadosColetores->iRota                   = $oAguaBase->x07_codrota;
      $clExpDadosColetores->iTipo                   = $oAguaBase->j88_codigo;
      $clExpDadosColetores->iCodLogradouro          = $oAguaBase->x01_codrua;
      $clExpDadosColetores->iCodBairro              = $oAguaBase->x01_codbairro;
      $clExpDadosColetores->iZona                   = $oAguaBase->x01_zona;
      $clExpDadosColetores->iOrdem                  = $i + 1;
      $clExpDadosColetores->sResponsavel            = $oAguaBase->z01_nome;
      $clExpDadosColetores->sNomeLogradouro         = $oAguaBase->j14_nome;
      $clExpDadosColetores->iNumero                 = $oAguaBase->x01_numero;
      $clExpDadosColetores->cLetra                  = $oAguaBase->x01_orientacao;
      $clExpDadosColetores->sComplemento            = $oAguaBase->x99_complemento;
      $clExpDadosColetores->sNomeBairro             = $oAguaBase->x99_bairro;
      $clExpDadosColetores->sCidade                 = "BAGE";
      $clExpDadosColetores->sEstado                 = "RS";
      $clExpDadosColetores->iQuadra                 = $oAguaBase->x01_quadra;
      $clExpDadosColetores->iEconomias              = $oAguaBase->x01_qtdeconomia;
      $clExpDadosColetores->fAreaContruida          = $oAguaBase->x99_areaconstr;
      $clExpDadosColetores->iNumPre                 = $oAguaBase->numpre;
      $clExpDadosColetores->sNatureza               = "AGUA E ESGOTO";
      $clExpDadosColetores->sCategoria              = $clExpDadosColetores->getCatImovel($oAguaBase->x01_matric);
      
      // $clExpDadosColetores->iCodHidrometro, $clExpDadosColetores->iNroHidrometro 
      $clExpDadosColetores->getHidrometroAtivo($oAguaBase->x01_matric);
      
      $clExpDadosColetores->iCodHidrometro  = $clExpDadosColetores->iCodHidrometro;
      $clExpDadosColetores->iNroHidrometro  = $clExpDadosColetores->iNroHidrometro;
      $clExpDadosColetores->sAvisoLeiturista  = $clExpDadosColetores->sAvisoLeiturista;
      

      
      $iCodLeituraGerada = $clExpDadosColetores->geraLeitura($iCgmLeiturista);
      
      if($clExpDadosColetores->iErroStatus == 0) {
        
        echo $clExpDadosColetores->sErroMsg;
        exit();
        
      }
      
      $iConsumoPadrao      = $clExpDadosColetores->getConsumoPadrao($oAguaBase->x01_matric, $iAnoExportacao, $iMesExportacao);
      
      $iMesesUltimaLeitura = $clExpDadosColetores->getMesesUltimaLeitura($oAguaBase->x01_matric, $iAnoExportacao, $iMesExportacao, $iCodLeituraGerada); 
      
      if ($iMesesUltimaLeitura > 0) {
        
        $clExpDadosColetores->fConsumoMaximo = ($iConsumoPadrao * $iMesesUltimaLeitura) - $iConsumoPadrao;
        
      } else {
        
        $clExpDadosColetores->fConsumoMaximo = " 0";
        
      }
        
      $clExpDadosColetores->fConsumoPadrao   = $iConsumoPadrao;
        
      $dDataVencimento = $clExpDadosColetores->getDtVencMatric($oAguaBase->x01_matric, $iAnoExportacao, $iMesExportacao);
      
      $clExpDadosColetores->dDtVencimento    = $dDataVencimento;
      $clExpDadosColetores->iImprimeConta    = $clExpDadosColetores->getImprimeConta($oAguaBase->x01_matric, $oAguaBase->x32_codcorresp);
      
      $rsLeituras = $clExpDadosColetores->getLeituras($oAguaBase->x01_matric, $iAnoExportacao, $iMesExportacao);
      
      $objLeituraUltima = db_utils::fieldsMemory($rsLeituras, 0);
      
      $clExpDadosColetores->dDtLeituraAnterior = $objLeituraUltima->x21_dtleitura;
      
      $iCodColetorExportaDados = $clExpDadosColetores->geraACExportaDados($iCodColetorExporta);
      
      if($clExpDadosColetores->iErroStatus == 0) {
        echo $clExpDadosColetores->sErroMsg;
        exit();
      }
      
      $clExpDadosColetores->geraACExportaDadosLeitura($iCodColetorExportaDados, $iCodLeituraGerada);
      
      if($clExpDadosColetores->iErroStatus == 0) {
        echo $clExpDadosColetores->sErroMsg;
        exit();
      }
      
      /*for($l = 0; $l < $clExpDadosColetores->iNumRowsLeituras; $l++) {
        
        if ($l < 5) {
          $oLeituras     = db_utils::fieldsMemory($rsLeituras, $l);
          $oLeiturasProx = db_utils::fieldsMemory($rsLeituras, $l+1);
          
          if($oLeiturasProx->x21_codleitura != "") {
            $iDiasUltLeitura  = db_datedif($oLeituras->x21_dtleitura, $oLeiturasProx->x21_dtleitura);
            $iMesesUltLeitura = $oLeituras->x99_mesultimaleitura;
            if($iMesesUltLeitura == "") {
              $iMesesUltLeitura = "0";
            }
          }else {
            $iDiasUltLeitura  = "0";
            $iMesesUltLeitura = "0";
          }
          
          $clExpDadosColetores->geraACExportaDadosLeitura($iCodColetorExportaDados, $oLeituras->x21_codleitura, $iDiasUltLeitura, $iMesesUltLeitura);
          
          if($clExpDadosColetores->iErroStatus == 0) {
            echo $clExpDadosColetores->sErroMsg;
            exit();
          }
          
        }
      }*/
                  
      $expDataVenc    = explode("-", $dDataVencimento);
      $iAnoVencimento = $expDataVenc[0];
      $iMesVencimento = $expDataVenc[1];   
      
      $rsArreCad = $clExpDadosColetores->getSqlArreCad($clExpDadosColetores->getSqlArreMatric($oAguaBase->x01_matric, $iDBInstituicao), $iArreTipo, $iAnoVencimento, $iMesVencimento, $iMesExportacao);
      
      $possueExcesso = 0;
      
      for($z = 0; $z < $clExpDadosColetores->iNumRowsArreCad; $z ++) {
        
        $oArreCad = db_utils::fieldsMemory($rsArreCad, $z);
        
        $iCodColetorExportaDadosReceita = $clExpDadosColetores->geraACExportaDadosReceita($oArreCad->k00_receit, $iCodColetorExportaDados, $oArreCad->k02_descr, $oArreCad->k00_numpar, $oArreCad->k00_valor, $oArreCad->k00_numpre, $oArreCad->k00_numtot);
        
        if($oArreCad->k00_receit == $iCodRecExcesso) {
          
          $possueExcesso = 1;
        }
        
        if($clExpDadosColetores->iErroStatus == 0) {
          echo $clExpDadosColetores->sErroMsg;
          exit();
        } 
      
      }
      
      //caso n possua, criar uma linha de excesso
      if($possueExcesso == 0) {
        
        $iNumpreExcesso = $clExpDadosColetores->getNumpreExcesso($oAguaBase->x01_matric, $iAnoExportacao, $iMesExportacao);
        
        $iCodColetorExportaDadosReceita = $clExpDadosColetores->geraACExportaDadosReceita($iCodRecExcesso, $iCodColetorExportaDados, $clExpDadosColetores->sDescrConsumo, $iMesExportacao, "0.00", $iNumpreExcesso, "12");
        
        if($clExpDadosColetores->iErroStatus == 0) {
          echo $clExpDadosColetores->sErroMsg;
          exit();
        }
        
        
      }
  
    } //for matriculas

    if( !$geraArquivo ){
    
    	db_msgbox("Nenhuma matricula sem leitura foi encontrada. Operação Cancelada!");
    	db_fim_transacao(true);
    	db_redireciona("agu4_expdadoscoletores_001.php");
    
    } else {
    
      db_fim_transacao();
    
    }

  } else {
    
    db_msgbox("Nenhuma matricula foi encontrada. Operação Cancelada!");
    db_fim_transacao(true);
    db_redireciona("agu4_expdadoscoletores_001.php");
    
  }  

}

//criando arquivo para o coletor


if ($oPost->geraDadosArquivos == "t") {
  
  $nomearqdados = $clArqExpColetor->arquivoDadosMatricula($iCodColetorExporta, 0);
  $nomearqlayout = $clArqExpColetor->gerarArquivoLayout(261, "01");

}

if ($oPost->geraSituacaoLeitura == "t") {
  
  $arqsitleitura = $clArqExpColetor->arquivoDadosSitLeitura();
  $arqlayoutsitleitura = $clArqExpColetor->gerarArquivoLayout(263, "02");

}

if ($oPost->geraLeiturista == "t") {
  
  $arqleiturista = $clArqExpColetor->arquivoDadosLeituristas();
  $arqlayoutleiturista = $clArqExpColetor->gerarArquivoLayout(262, "03");

}

if ($oPost->geraConfiguracoes == "t") {
  
  $arqconfiguracoes = $clArqExpColetor->arquivoDadosConfiguracoes();
  $arqlayoutconfiguracoes = $clArqExpColetor->gerarArquivoLayout(284, "04");

}

echo "<script> var listagem;";

if ($oPost->geraDadosArquivos == "t") {
  echo "  listagem = '$nomearqdados#Download arquivo TXT (dados dos coletores)|';";
  echo "  listagem+= '$nomearqlayout#Download arquivo TXT (layout dos coletores)|';";
}

if ($oPost->geraSituacaoLeitura == "t") {
  echo " if(listagem == '') listagem = '$arqsitleitura#Download arquivo TXT (dados das situacoes de leitura)|'; else ";
  echo "  listagem+= '$arqsitleitura#Download arquivo TXT (dados das situacoes de leitura)|';";
  echo "  listagem+= '$arqlayoutsitleitura#Download arquivo TXT (layout das situacoes de leitura)|';";
}

if ($oPost->geraLeiturista == "t") {
  echo "if(listagem == '') listagem = '$arqleiturista#Download arquivo TXT (dados dos leituristas)|'; else ";
  echo "  listagem+= '$arqleiturista#Download arquivo TXT (dados dos leituristas)|';";
  echo "  listagem+= '$arqlayoutleiturista#Download arquivo TXT (layout dos leituristas)|';";
}

if ($oPost->geraConfiguracoes == "t") {
  echo "if(listagem == '') listagem = '$arqconfiguracoes#Download arquivo TXT (dados das configuracoes c&oacute;digo de barras)|'; else ";
  echo "  listagem+= '$arqconfiguracoes#Download arquivo TXT (dados das configuracoes c&oacute;digo de barras)|';";
  echo "  listagem+= '$arqlayoutconfiguracoes#Download arquivo TXT (layout das configura&ccedil;&otilde;es c&oacute;digo de barras.)|';";
}

echo "  js_montarlista(listagem,'form1');";

$tempo = "Fim: " . date ( "H:i:s" );

echo " document.getElementById('tempo_final').innerHTML = '$tempo'";
echo "</script>";

//echo "<br>Fim:".date("H:i:s")."<br>";
echo "<table align=\"center\" width=\"300\">";
echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"button\" value=\"Voltar\" onclick=\"js_voltar()\">";
echo "</tr></table>";

?>

</form>
</body>
</html>

<?
function montaTelaTempo() {
  
  echo "<table align=\"center\" width=\"300\"><tr>";
  echo "<td align=\"center\" width=\"50%\">Inicio: " . date ( "H:i:s" ) . "</td>";
  echo "<td align=\"center\" width=\"50%\"><div id=\"tempo_final\"></div></td>";
  echo "</tr></table>";
  
}

function iniciaTermometro() {

  db_criatermometro("termometro", "Concluido...", "blue", 1);
  flush();
  
}

function acionaTermometro($iQtde, $iTotal, $sTexto) {
  
  db_atutermometro($iQtde, $iTotal, "termometro", 1, "Processando Matricula $sTexto (" . ($iQtde + 1) . "/$iTotal) ...   ");
    
}

?>