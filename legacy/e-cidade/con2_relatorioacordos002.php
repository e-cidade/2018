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


require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_acordo_classe.php"));
require_once(modification("model/Acordo.model.php"));
require_once(modification("model/AcordoComissao.model.php"));
require_once(modification("model/AcordoItem.model.php"));
require_once(modification("model/AcordoPosicao.model.php"));
require_once(modification("model/AcordoRescisao.model.php"));
require_once(modification("model/AcordoMovimentacao.model.php"));
require_once(modification("model/AcordoComissaoMembro.model.php"));
require_once(modification("model/AcordoGarantia.model.php"));
require_once(modification("model/AcordoHomologacao.model.php"));
require_once(modification("model/MaterialCompras.model.php"));
require_once(modification("model/CgmFactory.model.php"));

$oPost               = db_utils::postMemory($_POST);

$clacordo            = new cl_acordo;

$sWhere              = '';
$sAnd                = '';
$sOrder              = 'acordo.ac16_dataassinatura';

$sAcordo             = 'Todos';
$sDataInicio         = '';
$sDataFim            = '';
$sListarItens        = 'Não';
$sListarMovimentacao = 'Não';
$sListarAutorizacao  = 'Não';
$sListaDepartamento  = $oPost->sListaDepto;

$sSituacaoDescricao  = '';
$sOrigemDescricao    = '';
$sTipoDescricao      = '';
$sOrdemDescricao     = '';
$iCategoria          = $oPost->ac50_sequencial;

if (isset($oPost->situacaodescricao)) {
	$sSituacaoDescricao = $oPost->situacaodescricao;
}

if (isset($oPost->origemdescricao)) {
	$sOrigemDescricao = $oPost->origemdescricao;
}

if (isset($oPost->tipodescricao)) {
  $sTipoDescricao = $oPost->tipodescricao;
}

if (isset($oPost->ordemdescricao)) {
  $sOrdemDescricao = $oPost->ordemdescricao;
}

if (isset($oPost->ac16_sequencial)) {
	
	if (!empty($oPost->ac16_sequencial)) {
		
	  $sAcordo = $oPost->ac16_sequencial.' - '.substr($oPost->ac16_resumoobjeto, 0, 40);
    $sWhere .= "{$sAnd} acordo.ac16_sequencial = {$oPost->ac16_sequencial}";
    $sAnd    = " and ";		
	}
}

if (isset($oPost->ac16_datainicio)) {
  
  if (!empty($oPost->ac16_datainicio)) {
    
    $sDataInicio = $oPost->ac16_datainicio;
    $dtIni       = implode("-",array_reverse(explode("/",$sDataInicio)));
    $sWhere     .= "{$sAnd} acordo.ac16_datainicio >= '{$dtIni}'";
    $sAnd        = " and ";    
  }  
}

if (isset($oPost->ac16_datafim)) {
  
  if (!empty($oPost->ac16_datafim)) {
    
    $sDataFim = $oPost->ac16_datafim;
    $dtFim    = implode("-",array_reverse(explode("/",$sDataFim)));
    $sWhere  .= "{$sAnd} acordo.ac16_datafim <= '{$dtFim}'";
    $sAnd     = " and ";  
  }  
}

if (isset($oPost->ac16_datafim) || isset($oPost->ac16_datainicio)) {

  $sWhere     .= "{$sAnd} acordoposicao.ac26_acordoposicaotipo=1";
  $sAnd        = " and ";
}

if (isset($oPost->ac16_acordosituacao)) {
	
  if (!empty($oPost->ac16_acordosituacao)) {
  	
    $sWhere   .= "{$sAnd} acordo.ac16_acordosituacao = {$oPost->ac16_acordosituacao}";
    $sAnd      = " and ";
  }  
}

if (isset($oPost->ac16_origem)) {
	
  if (!empty($oPost->ac16_origem)) {
  	
    $sWhere .= "{$sAnd} acordo.ac16_origem = {$oPost->ac16_origem}";
    $sAnd    = " and ";
  }  
}

if (isset($oPost->acordotipo)) {
	
  if (!empty($oPost->acordotipo)) {
  	
    $sWhere .= "{$sAnd} acordotipo.ac04_sequencial = {$oPost->acordotipo}";
    $sAnd    = " and ";    
  }  
}

if (isset($oPost->listaacordonatureza)) {
	
  if (!empty($oPost->listaacordonatureza)) {
  	
    $sWhere .= "{$sAnd} acordonatureza.ac01_sequencial in({$oPost->listaacordonatureza})";
    $sAnd    = " and ";   
  }  
}

if (isset($oPost->listaacordogrupo)) {
	
  if (!empty($oPost->listaacordogrupo)) {
  	
    $sWhere .= "{$sAnd} acordogrupo.ac02_sequencial in({$oPost->listaacordogrupo})";
    $sAnd    = " and ";  
  }  
}

if (isset($oPost->listacontratado)) {
	
  if (!empty($oPost->listacontratado)) {
  	
    $sWhere .= "{$sAnd} gestor.z01_numcgm in({$oPost->listacontratado})";
    $sAnd    = " and "; 
  }  
}

if (isset($oPost->ac08_sequencial)) {
	
  if (!empty($oPost->ac08_sequencial)) {
  	
    $sWhere .= "{$sAnd} acordocomissao.ac08_sequencial in({$oPost->ac08_sequencial})";
    $sAnd    = " and "; 
  }  
}

if (!empty($oPost->ac46_sequencial)  && $oPost->ac46_sequencial != 0 ) {
	
  $sWhere .= "{$sAnd} acordo.ac16_acordoclassificacao = {$oPost->ac46_sequencial}";
  $sAnd    = " and "; 
}

if (isset($oPost->listaitens)) {
	
  if (!empty($oPost->listaitens)) {
  	
    if (trim($oPost->listaitens) == 'S') {
    	$sListarItens = 'Sim';
    }
  }  
} else {
	$oPost->listaitens = 'N';
}

if (isset($oPost->listamovimentacao)) {
	
  if (!empty($oPost->listamovimentacao)) {
    
  	if (trim($oPost->listamovimentacao) == 'S') {
  		$sListarMovimentacao = 'Sim';
  	}
  }  
} else {
	$oPost->listamovimentacao = 'N';
}

if (isset($oPost->listaautorizacao)) {
  
  if (!empty($oPost->listaautorizacao)) {
    
    if (trim($oPost->listaautorizacao) == 'S') {
      $sListarAutorizacao = 'Sim';
    }
  }  
} else {
  $oPost->listaautorizacao = 'N';
}

if (isset($oPost->ordem)) {
  
  if (!empty($oPost->ordem)) {
    
    if (trim($oPost->ordem) == 1) {
      $sOrder = 'acordo.ac16_dataassinatura';
    } else if (trim($oPost->ordem) == 2) {
      $sOrder = 'acordo.ac16_contratado';
    } else if (trim($oPost->ordem) == 3) {
      $sOrder = 'acordo.ac16_numero';
    } else if (trim($oPost->ordem) == 4) {
      $sOrder = 'acordo.ac16_anousu';
    }
  }  
}

if ($iCategoria != '') {
	
	$sWhere     .= "  and ac16_acordocategoria = {$iCategoria} ";
}

if ($sListaDepartamento != '') {
	
  $sWhere      .= " and ac16_coddepto in ({$sListaDepartamento})";
}

$sCampos     = " distinct acordo.ac16_sequencial, acordonatureza.ac01_descricao, acordotipo.ac04_descricao, "; 
$sCampos    .= " acordogrupo.ac02_sequencial, acordogrupo.ac02_descricao, db_depart.descrdepto, "; 
$sCampos    .= " acordo.ac16_dataassinatura, acordo.ac16_contratado, acordo.ac16_numero, acordo.ac16_anousu, ";
$sCampos    .= " acordo.ac16_numeroprocesso, acordo.ac16_qtdperiodo, acordo.ac16_tipounidtempoperiodo, ";
$sCampos    .= " acordo.ac16_acordocategoria";

$sSqlAcordo  = $clacordo->sql_query_completo_posicao(null,$sCampos,$sOrder,$sWhere);
$rsSqlAcordo = $clacordo->sql_record($sSqlAcordo);
if ( $clacordo->numrows == 0  ) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado!");
}

$aDadosAcordo = array();
for ($iInd = 0; $iInd < $clacordo->numrows; $iInd++) {

  $oAcordoCompleto = db_utils::fieldsMemory($rsSqlAcordo, $iInd);
  $oAcordo         = new Acordo($oAcordoCompleto->ac16_sequencial);

  /*
   * Monta Novo Objeto StdClass
   */
  $oDadosAcordo    = new stdClass();
  $oDadosAcordo->getCodigo          = $oAcordo->getCodigoAcordo();
  $oDadosAcordo->getTipoAcordo      = $oAcordoCompleto->ac04_descricao;
  $oDadosAcordo->getContratado      = $oAcordo->getContratado()->getCodigo()." - ".$oAcordo->getContratado()->getNome();
  $oDadosAcordo->getAssinatura      = $oAcordo->getDataAssinatura();
  $oDadosAcordo->getVigencia        = $oAcordo->getDataInicial().' a '.$oAcordo->getDataFinal();
  $oDadosAcordo->getValorTotal      = "";
  $oDadosAcordo->getResumoObjeto    = $oAcordo->getResumoObjeto();
  $oDadosAcordo->getSituacao        = $oAcordo->getDescricaoSituacao();
  $oDadosAcordo->getGrupoAcordo     = $oAcordoCompleto->ac02_sequencial.' - '.$oAcordoCompleto->ac02_descricao;
  $oDadosAcordo->getNumeroAcordo    = $oAcordo->getNumero().'/'.$oAcordo->getAno();
  $oDadosAcordo->getDepartamento    = $oAcordo->getDepartamento().' - '.$oAcordoCompleto->descrdepto;
  $oDadosAcordo->aAutorizacoes      = $oAcordo->getAutorizacoes();
  $oDadosAcordo->aMovimentacoes     = $oAcordo->getMovimentacoes();
  $oDadosAcordo->numeroprocesso     = $oAcordo->getProcesso();
  $oDadosAcordo->getCategoriaAcordo = $oAcordo->getCategoriaAcordo();
  $oDadosAcordo->sClassificacao     = $oAcordo->getClassificacao()->getDescricao();
  $oDadosAcordo->sNumero            = $oAcordo->getNumeroAcordo() . '/' . $oAcordo->getAno(); 
  
  $iCategoriaAcordo = 0;
  if ($oAcordo->getCategoriaAcordo() != '') {
  	$iCategoriaAcordo = $oAcordo->getCategoriaAcordo();
  }
  $oDadosAcordo->getDescricaoCategoria = Acordo::getDescricaoCategoriaAcordo($iCategoriaAcordo);
  
  /**
   * Adicionado novos campos no relatório 'Período de vigência' 
   */
  $oDadosAcordo->iQtdPeriodoVigencia       = $oAcordo->getQtdPeriodoVigencia();
  $oDadosAcordo->iTipoUnidadeTempoVigencia = $oAcordo->getTipoUnidadeTempoVigencia();
  $oDadosAcordo->sTipoUnidadeTempoVigencia = "";
  
  if (!empty($oDadosAcordo->iTipoUnidadeTempoVigencia)) {
    $oDadosAcordo->sTipoUnidadeTempoVigencia = $oDadosAcordo->iTipoUnidadeTempoVigencia == 1 ? "Mes(es)" : "Dia(s)";
  }
  
  $oComissao         = $oAcordo->getComissao();
  $sComissao         = $oComissao->getCodigo() . " - " . $oComissao->getDescricao();
  $aMembros          = $oComissao->getMembros();
  $aDescricaoMembros = array();
  
  foreach ($aMembros as $oMembros) {
  
  	$sDescricao = $oMembros->getCodigoCgm() . " - " . $oMembros->getNome() . " - " . $oMembros->getDescricaoResponsabilidade();
  	$aDescricaoMembros[] = $sDescricao;
  }
  $sMembros = implode(", ", $aDescricaoMembros);
  
  $oDadosAcordo->getComissao        = $sComissao;
  $oDadosAcordo->getComissaoMembros = $sMembros;
  
  switch ($oAcordo->getOrigem()) {
  	
    case 1:
        
    	$aProcessoCompra      = $oAcordo->getProcessosDeCompras();
    	$aDadosProcessoCompra = array();
    	foreach ($aProcessoCompra as $oProcessoCompras) {
    		
    		$aDadosProcessoCompra[] = $oProcessoCompras->getCodigo();
    	}
    	$sListaProcesso = implode(", ", $aDadosProcessoCompra);
      $sOrigem = "Processo de Compras {$sListaProcesso}";
    break;

    case 2:
        
    	$aLicitacoes     = $oAcordo->getLicitacoes();
    	$aListaLicitacao = array();
    	
    	foreach ($aLicitacoes as $oLicitacoes) {
    		$aListaLicitacao[] = $oLicitacoes->getCodigo();
    	}
    	$sListaLicitacao = implode(", ", $aListaLicitacao);
      $sOrigem = "Licitação {$sListaLicitacao}";
    break;

    case 3:
        
      $sOrigem = 'Manual';
    break;
      
    case 4:
        
      $sOrigem = 'Interno';
    break;
      
    case 5:
        
      $sOrigem = 'Custo Fixo';
    break;
      
    case 6:
    	
    	$aEmpenhos = $oAcordo->getEmpenhos();
    	$aLista    = array();
    	foreach ($aEmpenhos as $oEmpenho) {
    		$aLista[] = $oEmpenho->getCodigo() . "/" . $oEmpenho->getAnoUso();
    	}
    	$sEmpenhos = implode(", ", $aLista);
    	$sOrigem   = "Empenho   {$sEmpenhos}";
    break;
        
    default:
        
      $sOrigem = '';
    break;
  }
    
  $oDadosAcordo->getOrigem       = $sOrigem;
  $oDadosAcordo->aItens          = array(); 
  /*
   * Agrupa por Itens
   */
  try {

    foreach ($oAcordo->getUltimaPosicao()->getItens() as $oAcordoItens) {

      $oDadosAcordoItens                       = new stdClass();
      $oDadosAcordoItens->getCodigo            = $oAcordoItens->getCodigo();
      $oDadosAcordoItens->getCodigoMaterial    = substr($oAcordoItens->getMaterial()->getMaterial(), 0, 32);
      $oDadosAcordoItens->getDescricaoMaterial = urldecode($oAcordoItens->getMaterial()->getDescricao());
      $oDadosAcordoItens->getUnidade           = $oAcordoItens->getUnidade();
      $oDadosAcordoItens->getQuantidade        = $oAcordoItens->getQuantidade();
      $oDadosAcordoItens->getValorUnitario     = $oAcordoItens->getValorUnitario();
      $oDadosAcordoItens->getValorTotal        = $oAcordoItens->getValorTotal();
      $oDadosAcordoItens->getDotacoes          = $oAcordoItens->getDotacoes();
      $oDadosAcordoItens->getResumo            = urldecode($oAcordoItens->getResumo());

      $oDadosAcordo->getValorTotal += $oAcordoItens->getValorTotal();

      $oDadosAcordo->aItens[] = $oDadosAcordoItens;
    }
    $aDadosAcordo[] = $oDadosAcordo;

  } catch (Exception $e) {

  }
}
 
$head1 = "Acordo: {$sAcordo}";
$head2 = "Situação: {$sSituacaoDescricao}";
$head3 = "Origem: {$sOrigemDescricao}";
$head4 = "Tipo de Acordo: {$sTipoDescricao}";

$head5 = "Listar Itens: {$sListarItens}";
$head6 = "Listar Movimentações: {$sListarMovimentacao}  Listar Empenhos: {$sListarAutorizacao}";
$head7 = "Ordem: {$sOrdemDescricao}";

$head8 = "";
if (!empty($sDataInicio) && !empty($sDataFim)) {
  $head8 = "Data de Início: {$sDataInicio}  Data de Fim: {$sDataFim}";
}

$oPdf  = new PDF(); 
$oPdf->Open(); 
$oPdf->AliasNbPages(); 
$oPdf->SetTextColor(0,0,0);
$oPdf->SetFillColor(220);
$oPdf->SetAutoPageBreak(false);

$lImprimir = true;
$iFonte    = 9;
$iAlt      = 5 ;

foreach ($aDadosAcordo as $oDadoAcordo) {
	
	imprimirCabecalhoAcordos($oPdf, $oDadoAcordo, $lImprimir, $iFonte, $iAlt);
	$lPreencher = true;
	$lImprimir  = false;
	
	/*
   * Verifica Listar Itens do Acordo
   */
  if (trim($oPost->listaitens) == 'S') {
  	
    if (count($oDadoAcordo->aItens) > 0) {
    
      /*
       * Imprime Cabecalho dos itens de acordo
       */
      imprimirCabecalhoAcordoItens($oPdf, $iFonte, $iAlt);
      
      foreach ($oDadoAcordo->aItens as $oDadoAcordoItem) {
          
        if ($lPreencher == true) {
                  
          $lPreencher   = false;
          $iCorFundo    = 0;    
          $oPdf->SetFillColor(220);
        } else {
            
          $lPreencher   = true;
          $iCorFundo    = 1;
          $oPdf->SetFillColor(240);
        }
          
        /*
         * Itens do Acordo
         */
        $oPdf->SetFont('Arial','',$iFonte-1);
        $oPdf->Cell(28 ,$iAlt,$oDadoAcordoItem->getCodigo                                      ,'TBR',0,'C',$iCorFundo);
        $oPdf->Cell(63 ,$iAlt,substr(urldecode($oDadoAcordoItem->getDescricaoMaterial), 0, 32)     ,1,0,'L',$iCorFundo);
        $oPdf->Cell(28 ,$iAlt,$oDadoAcordoItem->getQuantidade                                      ,1,0,'R',$iCorFundo);
        $oPdf->Cell(28 ,$iAlt,$oDadoAcordoItem->getUnidade                                         ,1,0,'R',$iCorFundo);
        $oPdf->Cell(28 ,$iAlt,trim(db_formatar($oDadoAcordoItem->getValorUnitario,'f'))            ,1,0,'R',$iCorFundo);
        $oPdf->Cell(28 ,$iAlt,trim(db_formatar($oDadoAcordoItem->getValorTotal,'f'))               ,1,0,'R',$iCorFundo);
          
        $sVirgula  = '';
        $sDotacao  = '';
        if (count($oDadoAcordoItem->getDotacoes) > 0) {
            
          foreach ($oDadoAcordoItem->getDotacoes as $oDadoAcordoItemDotacao) {
            $sDotacao .= $sVirgula.$oDadoAcordoItemDotacao->dotacao;
            $sVirgula  = ', ';
          }
        }
          
        $oPdf->SetFont('Arial','',$iFonte-1);
        $oPdf->MultiCell(75,$iAlt,trim($sDotacao)                                                ,'TBL','L',$iCorFundo);
        $oPdf->MultiCell(278,$iAlt,urldecode($oDadoAcordoItem->getResumo)                         ,'TB','L',$iCorFundo);
          
        if ($oPdf->GetY() > ($oPdf->h - 50)) {
            
          $oPdf->AddPage('L');
          $lPreencher = true;
          imprimirCabecalhoAcordoItens($oPdf, $iFonte, $iAlt);
        }
      }
    } else {
      
      $oPdf->SetFont('Arial','',$iFonte-1);
      $oPdf->Cell(278 ,$iAlt-2,''                                                                           ,0,1,'C',0);
      $oPdf->Cell(278 ,$iAlt,"Não há itens para o acordo {$oDadoAcordo->getCodigo}."                        ,0,1,'L',0);
    }
  }

	/*
	 * Rodape Totalizador
	 */
  $oPdf->SetFont('Arial','B',$iFonte);
  if (trim($oPost->listaitens) == 'S') {

    $oPdf->Cell(278 ,$iAlt-3,''                                                                             ,0,1,'C',0);
    $oPdf->Cell(30 ,$iAlt-3,'Total de Itens:'                                                               ,0,0,'L',0);
    $oPdf->Cell(30 ,$iAlt-3,''.count($oDadoAcordo->aItens).''                                               ,0,0,'L',0);
  } else {
    $oPdf->Cell(60 ,$iAlt-3,''                                                                              ,0,0,'C',0);
  }
  
  $oPdf->Cell(148 ,$iAlt-3,''                                                                               ,0,0,'L',0);
  $oPdf->Cell(40 ,$iAlt-3,'Valor Total do Acordo:'                                                          ,0,0,'L',0);
  $oPdf->Cell(30 ,$iAlt-3,trim(db_formatar($oDadoAcordo->getValorTotal, 'f'))                               ,0,1,'R',0);
  
  /*
   * Verifica Listar de Movimentações de Acordo
   */
  if (trim($oPost->listamovimentacao) == 'S') {
  	
    if (count($oDadoAcordo->aMovimentacoes) > 0) {

      if ($oPdf->GetY() > ($oPdf->h - 50)) { 
        $oPdf->AddPage('L');
      }
      
      $lPreencher = true;
      imprimirCabecalhoAcordoMovimentacao($oPdf, $iFonte, $iAlt);
      
      foreach ($oDadoAcordo->aMovimentacoes as $oDadoAcordoMovimentacao) {
        
        if ($lPreencher == true) {
                  
          $lPreencher   = false;
          $iCorFundo    = 0;    
          $oPdf->SetFillColor(220);
        } else {
            
          $lPreencher   = true;
          $iCorFundo    = 1;
          $oPdf->SetFillColor(240);
        }
        
        /*
         * Acordo Movimentacoes
         */
        $oPdf->SetFont('Arial','',$iFonte-1);
        $oPdf->Cell(50 ,$iAlt,$oDadoAcordoMovimentacao->hora                                   ,'TBR',0,'C',$iCorFundo);
        $oPdf->Cell(172 ,$iAlt,urldecode($oDadoAcordoMovimentacao->descricao)                      ,1,0,'L',$iCorFundo);
        $oPdf->Cell(56 ,$iAlt,db_formatar($oDadoAcordoMovimentacao->ac10_datamovimento, 'd')   ,'TBL',1,'C',$iCorFundo);
        $oPdf->MultiCell(278,$iAlt,urldecode($oDadoAcordoMovimentacao->observacao)                ,'TB','L',$iCorFundo);
        
        if ($oPdf->GetY() > ($oPdf->h - 50)) {
            
          $oPdf->AddPage('L');
          $lPreencher = true;
          imprimirCabecalhoAcordoMovimentacao($oPdf, $iFonte, $iAlt);
        }
      }
    } else {
    
      $oPdf->SetFont('Arial','',$iFonte-1);
      $oPdf->Cell(278 ,$iAlt-2,''                                                                           ,0,1,'C',0);
      $oPdf->Cell(278 ,$iAlt,"Não há movimentações para o acordo {$oDadoAcordo->getCodigo}."                ,0,1,'L',0);
    }
  }
  
  /*
   * Verifica Listar de Autorizações de Empenho para o Acordo
   */
  if (trim($oPost->listaautorizacao) == 'S') {
  	
    if (count($oDadoAcordo->aAutorizacoes) > 0) {

      if ($oPdf->GetY() > ($oPdf->h - 50)) { 
        $oPdf->AddPage('L');
      }
        
      $lPreencher = true;
      imprimirCabecalhoAcordoAutorizacao($oPdf, $iFonte, $iAlt);
      
      foreach ($oDadoAcordo->aAutorizacoes as $oDadoAcordoAutorizacao) {
        
        if ($lPreencher == true) {
                  
          $lPreencher   = false;
          $iCorFundo    = 0;    
          $oPdf->SetFillColor(220);
        } else {
            
          $lPreencher   = true;
          $iCorFundo    = 1;
          $oPdf->SetFillColor(240);
        }
        
        /*
         * Acordo Autorizações
         */
        $oPdf->SetFont('Arial','',$iFonte-1);
        $oPdf->Cell(54 ,$iAlt,$oDadoAcordoAutorizacao->codigo                                  ,'TBR',0,'C',$iCorFundo);
        $oPdf->Cell(56 ,$iAlt,trim(db_formatar($oDadoAcordoAutorizacao->valor, 'f'))               ,1,0,'R',$iCorFundo);
        $oPdf->Cell(56 ,$iAlt,db_formatar($oDadoAcordoAutorizacao->dataemissao, 'd')               ,1,0,'C',$iCorFundo);
        $oPdf->Cell(56 ,$iAlt,db_formatar($oDadoAcordoAutorizacao->dataanulacao, 'd')              ,1,0,'C',$iCorFundo);
        $oPdf->Cell(56 ,$iAlt,$oDadoAcordoAutorizacao->empenho                                 ,'TBL',1,'L',$iCorFundo);
        
        if ($oPdf->GetY() > ($oPdf->h - 50)) {
            
          $oPdf->AddPage('L');
          $lPreencher = true;
          imprimirCabecalhoAcordoAutorizacao($oPdf, $iFonte, $iAlt);
        }
      }
    } else {
    
      $oPdf->SetFont('Arial','',$iFonte-1);
      $oPdf->Cell(278 ,$iAlt-2,''                                                                           ,0,1,'C',0);
      $oPdf->Cell(278 ,$iAlt,"Não há empenhos para o acordo {$oDadoAcordo->getCodigo}."                 ,0,1,'L',0);
    }
  }
}
	
$oPdf->Output();

/*
 * Monta Cabecalho dos Arcordos
 */
function imprimirCabecalhoAcordos($oPdf, $oDado, $lImprime, $iFonte, $iAlt) {
	
	global $oPost;

  if ((($oPost->listaautorizacao != 'N' || $oPost->listamovimentacao != 'N' || $oPost->listaitens != 'N') 
      || $lImprime) || $oPdf->GetY() > ($oPdf->h - 50)) {
     $oPdf->AddPage('L');
  }
  
  $oPdf->SetFillColor(220);
  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(35 ,$iAlt,'Código: ', 0, 0, 'R', 0);
  $oPdf->SetFont('Arial','',$iFonte-1);
  $oPdf->Cell(107 ,$iAlt,$oDado->getCodigo, 0, 0, 'L', 0);

  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(32 ,$iAlt,'Acordo: ' ,0,0,'R',0);
  $oPdf->SetFont('Arial','',$iFonte-1);
  $oPdf->Cell(107 ,$iAlt, $oDado->sNumero ,0,0,'L',0);
  $oPdf->ln();

  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(35 ,$iAlt,'Tipo do Acordo: ',0,0,'R',0);
  $oPdf->SetFont('Arial','',$iFonte-1);
  $oPdf->Cell(107 ,$iAlt,urldecode($oDado->getTipoAcordo),0,0,'L',0);
    
  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(32 ,$iAlt,'Processo: ',0,0,'R',0);
  $oPdf->SetFont('Arial','',$iFonte-1);
  $oPdf->Cell(107 ,$iAlt,$oDado->numeroprocesso, 0,0,'L',0);
  $oPdf->ln(); 
    
  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(32 ,$iAlt,'Data da Assinatura: ',0,0,'R',0);
  $oPdf->SetFont('Arial','',$iFonte-1);
  $oPdf->Cell(107 ,$iAlt,$oDado->getAssinatura,0,0,'L',0);

  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(32 ,$iAlt,'Vigência: ',0,0,'R',0);
  $oPdf->SetFont('Arial','',$iFonte-1);
  $oPdf->Cell(107 ,$iAlt,$oDado->getVigencia ,0,0,'L',0);
  $oPdf->ln();

  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(35 ,$iAlt,'Situação Atual: ',0,0,'R',0);
  $oPdf->SetFont('Arial','',$iFonte-1);
  $oPdf->Cell(107 ,$iAlt,urldecode($oDado->getSituacao),0,0,'L',0);

  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(35 ,$iAlt,'Classificação: ',0,0,'R',0);
  $oPdf->SetFont('Arial','',$iFonte-1);
  $oPdf->Cell(107 ,$iAlt,$oDado->sClassificacao,0,0,'L',0);
  $oPdf->ln();
                                                     
  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(35 ,$iAlt,'Contratado: ',0,0,'R',0);
  $oPdf->SetFont('Arial','',$iFonte-1);
  $oPdf->Cell(107 ,$iAlt,urldecode($oDado->getContratado),0,0,'L',0);

  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(32 ,$iAlt,'Grupo de Acordo: ',0,0,'R',0);
  $oPdf->SetFont('Arial','',$iFonte-1);
  $oPdf->Cell(107 ,$iAlt,urldecode($oDado->getGrupoAcordo),0,0,'L',0);
  $oPdf->ln();
  
  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(35 ,$iAlt,'Período de Vigência: ',0,0,'L',0);
  $oPdf->SetFont('Arial','',$iFonte-1);
  $oPdf->Cell(107 ,$iAlt, "{$oDado->iQtdPeriodoVigencia} - {$oDado->sTipoUnidadeTempoVigencia}",0,0,'L',0);

  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(32 ,$iAlt,'Número: ',0,0,'R',0);
  $oPdf->SetFont('Arial','',$iFonte-1);
  $oPdf->Cell(107 ,$iAlt,$oDado->getNumeroAcordo,0,0,'L',0);
  $oPdf->ln();
  
  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(33 ,$iAlt,'Categoria: ',0,0,'R',0);
  $oPdf->SetFont('Arial','',$iFonte-1);
  
  $sCategoria = 'Não Informado';
  
  if ($oDado->getCategoriaAcordo != '') {
  	$sCategoria = urldecode($oDado->getCategoriaAcordo . " - " . $oDado->getDescricaoCategoria);
  }
  
  $oPdf->Cell(107 ,$iAlt, $sCategoria, 0, 0, 'L', 0);

  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(32 ,$iAlt,'Departamento: ',0,0,'R',0);
  $oPdf->SetFont('Arial','',$iFonte-1);
  $oPdf->Cell(107 ,$iAlt,urldecode($oDado->getDepartamento),0,0,'L',0);
  $oPdf->ln(); 

  $oPdf->SetFont('Arial', 'B', $iFonte);
  $oPdf->Cell(33 ,$iAlt,'Origem: ', 0, 0, 'R', 0);
  $oPdf->SetFont('Arial', '', $iFonte-1);
  $oPdf->Cell(107, $iAlt, urldecode($oDado->getOrigem),0, 0, "L", 0); 
  
  $oPdf->SetFont('Arial', 'B', $iFonte);
  $oPdf->Cell(35 ,$iAlt, 'Resumo do Objeto: ', 0, 0, 'R', 0);
  $oPdf->SetFont('Arial', '', $iFonte-1);
  $oPdf->MultiCell(246, $iAlt, urldecode($oDado->getResumoObjeto), 0, "L", 0);

  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(35 ,$iAlt,'Comissão: ' ,0,0,'R',0);
  $oPdf->SetFont('Arial','',$iFonte-1);
  $oPdf->Cell(107 ,$iAlt,urldecode($oDado->getComissao),0,0,'L',0);
  $oPdf->ln();
  
  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(35 ,$iAlt,'Membros: ',0,0,'R',0);
  $oPdf->SetFont('Arial','',$iFonte-1);
  $oPdf->MultiCell(107,$iAlt,urldecode($oDado->getComissaoMembros),0,"L",0);  
  
  $oPdf->Cell(278,$iAlt-3,'','T',0,"C",0);
  $oPdf->ln();
}

/*
 * Monta Cabecalho dos Itens de Acordo 
 */
function imprimirCabecalhoAcordoItens($oPdf, $iFonte, $iAlt) {
  
  $oPdf->SetFillColor(220);
  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(28 ,$iAlt,'Código do Item'                                                                    ,1,0,'C',1);
  $oPdf->Cell(63 ,$iAlt,'Descrição do Item'                                                                 ,1,0,'C',1);
  $oPdf->Cell(28 ,$iAlt,'Quantidade'                                                                        ,1,0,'C',1);
  $oPdf->Cell(28 ,$iAlt,'Unidade'                                                                           ,1,0,'C',1);
  $oPdf->Cell(28 ,$iAlt,'Valor Unitário'                                                                    ,1,0,'C',1);
  $oPdf->Cell(28 ,$iAlt,'Valor Total'                                                                       ,1,0,'C',1);
  $oPdf->Cell(75 ,$iAlt,'Dotação(ões)'                                                                      ,1,1,'C',1);
  $oPdf->Cell(278 ,$iAlt,'Resumo'                                                                           ,1,1,'L',1);
}

/*
 * Monta Cabecalho das Movimentacoes do Acordo 
 */
function imprimirCabecalhoAcordoMovimentacao($oPdf, $iFonte, $iAlt) {
  
  $oPdf->SetFillColor(220);
  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(278 ,$iAlt-3,''                                                                               ,0,1,'C',0);
  $oPdf->Cell(278 ,$iAlt,'Movimentações:'                                                                   ,0,1,'L',0);
	
  $oPdf->Cell(50 ,$iAlt,'Hora'                                                                              ,1,0,'C',1);
  $oPdf->Cell(172 ,$iAlt,'Descrição'                                                                        ,1,0,'C',1);
  $oPdf->Cell(56 ,$iAlt,'Data Movimentação'                                                                 ,1,1,'C',1);
  $oPdf->Cell(278 ,$iAlt,'Observação'                                                                       ,1,1,'L',1);
}

/*
 * Monta Cabecalho das Autorizações do Acordo 
 */
function imprimirCabecalhoAcordoAutorizacao($oPdf, $iFonte, $iAlt) {
  
  $oPdf->SetFillColor(220);
  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(278 ,$iAlt-3,''                                                                               ,0,1,'C',0);
  $oPdf->Cell(278 ,$iAlt,'Autorizações:'                                                                    ,0,1,'L',0);
	
  $oPdf->Cell(54 ,$iAlt,'Código da Autorização'                                                             ,1,0,'C',1);
  $oPdf->Cell(56 ,$iAlt,'Valor'                                                                             ,1,0,'C',1);
  $oPdf->Cell(56 ,$iAlt,'Data Emissão'                                                                      ,1,0,'C',1);
  $oPdf->Cell(56 ,$iAlt,'Data Anulação'                                                                     ,1,0,'C',1);
  $oPdf->Cell(56 ,$iAlt,'Empenho'                                                                           ,1,1,'C',1);
}
?>
