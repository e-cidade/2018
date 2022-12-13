<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/JSON.php");
require_once("libs/db_utils.php");

require_once("classes/db_obrasalvara_classe.php");
require_once("classes/db_obrasender_classe.php");
require_once("classes/db_obras_classe.php");
require_once("classes/db_obrasconstr_classe.php");
require_once("classes/db_obrastec_classe.php");
require_once("classes/db_obrastecnicos_classe.php");

$oDaoObrasAlvara   = new cl_obrasalvara;
$oDaoObrasEnder    = new cl_obrasender;
$oDaoObras         = new cl_obras;
$oDaoObrasConstr   = new cl_obrasconstr;
$oDaoObrasTec      = new cl_obrastec;
$oDaoObrasTecnicos = new cl_obrastecnicos;
$oJson             = new services_json();

$oGet              = db_utils::postMemory($_GET);
$oDadosFormulario  = $oJson->decode( str_replace("\\","",$oGet->sJson) );

$oDaoObrasAlvara->rotulo->label();
$sOrderBy          = $oDadosFormulario->sCampoOrdenacao;
$sLabelOrderBy     = null;
$aWhere            = array();
$sTipoSelecao      = "in";
$sLabelData        = null;


if ( $oDadosFormulario->sTipoSelecao != "I" && $oDadosFormulario->sOpcaoSelecionados == 'N' ) {
  $sTipoSelecao = "not in";
}

/**
 * Caso o tipo de seleção for selecionados,
 * pega o array de seleção e
 * converte em uma lista para ser colocado no argumento "in" do sql
 */
if( $oDadosFormulario->sTipoSelecao == 'S' ) {

  if ( count($oDadosFormulario->aObrasSelecionadas) > 0 ) {

    $sListaObras = implode(',', $oDadosFormulario->aObrasSelecionadas);
    $aWhere[]    = "ob04_codobra {$sTipoSelecao} ( {$sListaObras} ) ";
  }
  /**
   * Caso contrário
   */
} else {

  if( $oDadosFormulario->iCodigoObraInicio <> 0 && $oDadosFormulario->iCodigoObraFim <> 0 ) {

    $aWhere[] = "ob04_codobra between {$oDadosFormulario->iCodigoObraInicio} and {$oDadosFormulario->iCodigoObraFim}";

  } elseif ( $oDadosFormulario->iCodigoObraInicio <> 0 && $oDadosFormulario->iCodigoObraFim == 0 ) {

    $aWhere[] = "ob04_codobra >= {$oDadosFormulario->iCodigoObraInicio}";

  } elseif ( $oDadosFormulario->iCodigoObraInicio <> 0 && $oDadosFormulario->iCodigoObraFim == 0 ) {

    $aWhere[] = "ob04_codobra <= {$oDadosFormulario->iCodigoObraFim}";

  }
}

/**
 * Valida as datas a serem utilizadas no relatorio
 */

if ( !empty( $oDadosFormulario->sDataInicio ) && !empty( $oDadosFormulario->sDataFim ) ) {

  $sDataInicialFormatada = db_formatar($oDadosFormulario->sDataInicio, "d");
  $sDataFinalFormatada   = db_formatar($oDadosFormulario->sDataFim,    "d");

  $aWhere[]              = "ob04_data between '{$oDadosFormulario->sDataInicio}' and '{$oDadosFormulario->sDataFim}'";
  $sLabelData            = "Período entre {$sDataInicialFormatada} e {$sDataFinalFormatada}.";

} elseif ( !empty( $oDadosFormulario->sDataInicio ) && empty( $oDadosFormulario->sDataFim ) ) {

  $sDataInicialFormatada = db_formatar($oDadosFormulario->sDataInicio, "d");
  
  $aWhere[]              = "ob04_data >= '{$oDadosFormulario->sDataInicio}'";
  $sLabelData            = "Período posterior a {$sDataInicialFormatada}";

} elseif ( empty( $oDadosFormulario->sDataInicio ) && !empty( $oDadosFormulario->sDataFim ) ) {

  $sDataFinalFormatada   = db_formatar($oDadosFormulario->sDataFim,    "d");
  
  $aWhere[]              = "ob04_data <= '{$oDadosFormulario->sDataFim}'";
  $sLabelData            = "Período anterior a {$sDataFinalFormatada}";

}


if( !empty($oDadosFormulario->sCampoOrdenacao) ){

  if($sOrderBy == "ob04_codobra"){
    $sLabelOrderBy  = "CÓDIGO DA OBRA";
  }else if($sOrderBy == "ob04_data"){
    $sLabelOrderBy  = "DATA";
  }
}




$head4 = "RELATÓRIO DE OBRAS COM ALVARÁ";
$head6 = "Ordernação:  ".$sLabelOrderBy;
$head7 = $sLabelData;

/**
 * Executa query para retornar os dados necessarios para o relatório
 */

$sCamposQuery       = " ob04_codobra,                                        \n";
$sCamposQuery      .= " ob04_alvara,                                         \n";
$sCamposQuery      .= " ob04_data,                                           \n";
$sCamposQuery      .= " ob04_dataexpedicao,                                  \n";
$sCamposQuery      .= " ob01_nomeobra,                                       \n";
$sCamposQuery      .= " cgm_responsavel.z01_nome as nome_responsavel,        \n";
$sCamposQuery      .= " ob08_codconstr,                                      \n";
$sCamposQuery      .= " ob07_numero,                                         \n";
$sCamposQuery      .= " j13_descr,                                           \n";
$sCamposQuery      .= " j14_nome,                                            \n";
$sCamposQuery      .= " cgm_tecnico.z01_nome as nome_tecnico,                \n";
$sCamposQuery      .= " 'R. '                             ||                 \n";
$sCamposQuery      .= " substring(j14_nome from 0 for 30) ||                 \n";
$sCamposQuery      .= " ', '                              ||                 \n";
$sCamposQuery      .= " ob07_numero                       ||                 \n";
$sCamposQuery      .= " ' - Bairro:  '                    ||                 \n";
$sCamposQuery      .= " substring(j13_descr from 0 for 20)  as endereco_obra ";

$sSqlObrasAlvara    = $oDaoObrasAlvara->sql_query_relatorioObrasAlvara( implode("\n and ", $aWhere), $sCamposQuery, $oDadosFormulario->sCampoOrdenacao );
$rsObrasAlvara      = $oDaoObrasAlvara->sql_record($sSqlObrasAlvara);
$iNumRowObrasAlvara = $oDaoObrasAlvara->numrows;
if ($iNumRowObrasAlvara == 0){
  
  $sMsg = _M('tributario.projetos.pro2_obrasalvara002.nenhum_cadastro_encontrado');
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
  exit;
}

$aObrasAlvara       = db_utils::getCollectionByRecord($rsObrasAlvara);



/**
 * Seleciona os formatos de emissao do relatorio
 */
switch ($oDadosFormulario->sFormatoRelatorio) {
  
  case "pdf":
    
    $iTotalRegistros  = 0;
    $iAlturaLinha     = 4;
    $lQuebraPagina    = true;
    $iTotalRegistros  = 0;
    $iBgColor         = 0;

    $oPdf             = new PDF();
    $oPdf->Open();
    $oPdf->AliasNbPages();
    $oPdf->setfillcolor(235);

    foreach( $aObrasAlvara as $oObrasAlvara ) {

      if ($oPdf->gety() > $oPdf->h - 30 || $lQuebraPagina ) {

        $oPdf->addpage("L");
        $oPdf->setfont('arial','b',8);
        $oPdf->cell(20,$iAlturaLinha,"ALVARÁ"       , 1, 0, "C", 1);
        $oPdf->cell(20,$iAlturaLinha,"EMISSÃO"      , 1, 0, "C", 1);
        $oPdf->cell(20,$iAlturaLinha,"OBRA"         , 1, 0, "C", 1);
        $oPdf->cell(40,$iAlturaLinha,"NOME DA OBRA" , 1, 0, "C", 1);
        $oPdf->cell(90,$iAlturaLinha,"ENDEREÇO"     , 1, 0, "C", 1);
        $oPdf->cell(0 ,$iAlturaLinha,"PROPRIETÁRIO" , 1, 1, "C", 1);
        $lQuebraPagina = false;
      }

      $bord1 = "TL";
      $bord2 = "T";
      $bord3 = "TR";

      $oPdf->setfont('arial','',7);

      $oPdf->cell(20,$iAlturaLinha,$oObrasAlvara->ob04_alvara                            ,$bord1,0,"C",$iBgColor);
      $oPdf->cell(20,$iAlturaLinha,db_formatar($oObrasAlvara->ob04_dataexpedicao, "d")   ,$bord2,0,"C",$iBgColor);
      $oPdf->cell(20,$iAlturaLinha,$oObrasAlvara->ob04_codobra                           ,$bord2,0,"C",$iBgColor);
      $oPdf->cell(40,$iAlturaLinha,substr($oObrasAlvara->ob01_nomeobra,0,25)             ,$bord2,0,"L",$iBgColor);
      $oPdf->cell(90,$iAlturaLinha,$oObrasAlvara->endereco_obra                          ,$bord2,0,"L",$iBgColor);
      $oPdf->cell(0 ,$iAlturaLinha,$oObrasAlvara->nome_responsavel                       ,$bord3,1,"L",$iBgColor);
      $oPdf->cell(0 ,$iAlturaLinha,"Técnico responsável: ".$oObrasAlvara->nome_tecnico   ,"RL"  ,1,"L",$iBgColor);
      $iTotalRegistros++;

      if ($iBgColor == 0) {
        $iBgColor = 1;
      } else {
        $iBgColor = 0;
      }
    }
    $oPdf->setfont('arial','b',8);
    $oPdf->cell(0,$iAlturaLinha,"TOTAL DE OBRAS ENCONTRADAS :  ".$iTotalRegistros,"T",1,"L",0);
    $oPdf->ln(5);

    $oPdf->Output();

  break;
  case "csv":
    
    $sArquivo                        = "tmp/relatorioObrasAlvara.csv";
    $fArquivo                        = fopen($sArquivo, "w");
    $aDadosCSV                       = array();
    $aDadosCSV["ob04_codobra"]       = "Código da Obra";
    $aDadosCSV["ob04_alvara"]        = "Numero Alvara";
    $aDadosCSV["ob04_dataexpedicao"] = "Data Alvará";
    $aDadosCSV["ob01_nomeobra"]      = "Nome da Obra";
    $aDadosCSV["nome_responsavel"]   = "Nome do Responsavel";
    $aDadosCSV["ob08_codconstr"]     = "Condigo da Construção";
    $aDadosCSV["endereco_obra"]      = "Endereço";
    $aDadosCSV["nome_tecnico"]       = "Nome do Técnico";
    
    $lEscritaArquivo = fputcsv($fArquivo, $aDadosCSV, ";");
    
    if ( !$lEscritaArquivo ) {
      
      $sMsg = _M('tributario.projetos.pro2_obrasalvara002.erro_escrever_cvs');
      db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
      exit;
    }
    
    foreach ($aObrasAlvara as $iRegistro => $oObrasAlvara) {

      $aDadosCSV                       = array();
      $aDadosCSV["ob04_codobra"]       = $oObrasAlvara->ob04_codobra;
      $aDadosCSV["ob04_alvara"]        = $oObrasAlvara->ob04_alvara;    
      $aDadosCSV["ob04_dataexpedicao"] = db_formatar($oObrasAlvara->ob04_dataexpedicao, "d"); 
      $aDadosCSV["ob01_nomeobra"]      = $oObrasAlvara->ob01_nomeobra;
      $aDadosCSV["nome_responsavel"]   = $oObrasAlvara->nome_responsavel;
      $aDadosCSV["ob08_codconstr"]     = $oObrasAlvara->ob08_codconstr; 
      $aDadosCSV["endereco_obra"]      = $oObrasAlvara->endereco_obra;  
      $aDadosCSV["nome_tecnico"]       = $oObrasAlvara->nome_tecnico;   
      
      $lEscritaArquivo               = fputcsv($fArquivo, $aDadosCSV,";");
      if ( !$lEscritaArquivo ) {
      
        $oParms = new stdClass();
        $oParms->iRegistro = $iRegistro;
        $sMsg = _M('tributario.projetos.pro2_obrasalvara002.erro_escrever_cvs_linha', $oParms);
        db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
        exit;
      }
    }
    fclose($fArquivo);
    
    db_redireciona($sArquivo);
  break;
}
?>