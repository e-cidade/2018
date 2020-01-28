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

  require_once("libs/db_app.utils.php");
  require_once("libs/db_utils.php");
  require_once("fpdf151/pdf.php");
  require_once("classes/db_ouvidoriaatendimento_classe.php");
  
  /**
   * Impressão de relatório
   * 
   * A impressão do relatório consiste com bases nos filtros setados pelo usuário através da rotina abaixo:
   * PATRIMONIAL > OUVIDORIA > RELATÓRIOS > DEPARTAMENTO / TIPO DE PROCESSO
   * 
   * ComboBox - Com ou Sem
   * 0 = COM
   * 1 = SEM
   * 
   */
  $oGet               = db_utils::postMemory($HTTP_GET_VARS);
 
  
  $oDaoOuvAtendimento = new cl_ouvidoriaatendimento();
  $aClausulaWhere     = array();
  /**
   * Configura datas
   */
  $dtInicial = implode('-',array_reverse(explode('/',$oGet->dtInicial)));
  $dtFinal   = implode('-',array_reverse(explode('/',$oGet->dtFinal)));
  
  /*
   * Adiciona no WhereGeral um between entre datas
   */
  if (trim($oGet->dtInicial) != "" && trim($oGet->dtInicial) != "") {
    $aClausulaWhere[] = "ouvidoriaatendimento.ov01_dataatend between '{$dtInicial}' and '{$dtFinal}'";
  }

  /**
   * Verifica se o campo sDepartamento teve filtro inserido
   */
  $sHeadDepartamentos = "Todos";
  if (trim($oGet->sDepartamento) != "") {
    
    $sHeadDepartamentos = $oGet->sDepartamento;
    if ($oGet->iOpcaoDepart == "0") {
      $aClausulaWhere[] = "ouvidoriaatendimento.ov01_depart in ({$oGet->sDepartamento})";
    } else {
      $aClausulaWhere[] = "ouvidoriaatendimento.ov01_depart not in ({$oGet->sDepartamento})";    
    }
  }
  
  /**
   * Verifica se o Tipo de Processo foi informado
   */
  $sHeadTipoProcesso = "Todos";
  if (trim($oGet->sTipoProcesso) != "") {

    $sHeadTipoProcesso = $oGet->sTipoProcesso;
    if ($oGet->iOpcaoTipoProc == "0") {
      $aClausulaWhere[] = "ouvidoriaatendimento.ov01_tipoprocesso in ({$oGet->sTipoProcesso})";
    } else {
      $aClausulaWhere[] = "ouvidoriaatendimento.ov01_tipoprocesso not in ({$oGet->sTipoProcesso})";
    }
  }
  
  /**
   * Verifica se o parâmetro sBairro bem preenchido, caso ele não venha, será efetuada a busca pelos locais selecionados.
   * Caso existe algum bairro selecionado, os locais selecionados serão todos desconsiderados
   */
  $sHeadLocais  = "Todos";
  $sHeadBairros = "Não Selecionado";
  if (trim($oGet->sBairro) == "" && trim($oGet->sLocais) != "") {

    $sHeadLocais = $oGet->sLocais;
    if ($oGet->iOpcaoLocal == "0") {
      $aClausulaWhere[] = "ouvidoriacadlocal.ov25_sequencial in ({$oGet->sLocais}) ";
    } else {
      $aClausulaWhere[] = "ouvidoriacadlocal.ov25_sequencial not in ({$oGet->sLocais}) ";
    }
  } else if (trim($oGet->sBairro) != "") {

    $sHeadBairros = $oGet->sBairro;
    if ($oGet->iOpcaoBairro == "0") {
      $aClausulaWhere[] = "bairro.j13_codi in ({$oGet->sBairro}) ";
    } else {
      $aClausulaWhere[] = "bairro.j13_codi not in ({$oGet->sBairro}) ";
    }
  }
  
  /**
   * Verifica se existe um departamento de DESTINO selecionado. Esta opção busca as ouvidorias que estão somente com
   * este departamento
   */
  $sHeadDepartDestino = "Todos";
  if (trim($oGet->sDepartDestino) != "") {

    $sHeadDepartDestino = $oGet->sDepartDestino;
    if ($oGet->iOpcaoDepartDestino == "0") {
      $aClausulaWhere[] = "procandam.p61_coddepto in ($oGet->sDepartDestino)";
    } else {
      $aClausulaWhere[] = "procandam.p61_coddepto not in ($oGet->sDepartDestino)";
    }
  }

  /**
   * Executa um implode da string "and" entre os arrays para agrupálos na cláusula where
   * 
   */
  $sSqlWhere           = implode(" and ", $aClausulaWhere);
  $sSqlCampos          = "  distinct protprocesso.p58_codproc,                                          ";
  $sSqlCampos         .= "           protprocesso.p58_requer,                                           ";
  
  //incluido campos ov01_numero e ov01_anousu 
  $sSqlCampos         .= "           ouvidoriaatendimento.ov01_numero ,                                 ";
  $sSqlCampos         .= "           ouvidoriaatendimento.ov01_anousu ,                                 ";
  
  $sSqlCampos         .= "           tipoproc.p51_descr,                                                ";
  $sSqlCampos         .= "           case                                                               ";
  $sSqlCampos         .= "             when ouvidoriacadlocalender.ov26_ouvidoriacadlocal is not null   ";
  $sSqlCampos         .= "               then ruas.j14_nome ||', '|| ouvidoriacadlocalender.ov26_numero ";
  $sSqlCampos         .= "             when ouvidoriacadlocaldepart.ov27_ouvidoriacadlocal is not null  ";
  $sSqlCampos         .= "               then 'DEPARTAMENTO'                                            ";
  $sSqlCampos         .= "             else 'GERAL'                                                     ";
  $sSqlCampos         .= "           end as local,                                                      ";
  $sSqlCampos         .= "           ouvidoriaatendimento.ov01_dataatend                                ";
  $sSqlBuscaRelatorio  = $oDaoOuvAtendimento->sql_query_dados_atendimento(null, $sSqlCampos, null, $sSqlWhere);
  $rsBuscaRelatorio    = $oDaoOuvAtendimento->sql_record($sSqlBuscaRelatorio);

  if ( pg_num_rows($rsBuscaRelatorio) == 0 ) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros para os filtros setados.');
    exit;
  }
  
  /**
   * Armazena resultados da query em um array
   */
  $aDadosRelatorio = db_utils::getColectionByRecord($rsBuscaRelatorio);
  
  /**
   * Cria objeto PDF para impressão do relatório
   */
  $oPdf = new PDF("L"); 
  $oPdf->Open(); 
  $oPdf->AliasNbPages(); 
  $oPdf->setfillcolor(235);
  $oPdf->setfont('arial','b',8);
  $iAltura = 4;
  
  /**
   * Headers do Relatório
   */
  $head1 = "Processos por Departamento/Tipo";
  $head2 = "Período: {$oGet->dtInicial} à {$oGet->dtFinal}";
  $head3 = "Departamentos: {$sHeadDepartamentos}";
  $head4 = "Tipos Processo: {$sHeadTipoProcesso}";
  $head5 = "Locais: {$sHeadLocais}";
  $head6 = "Bairros: {$sHeadBairros}";
  $head7 = "Depart. Destino: {$sHeadDepartDestino}";
  
  
  /**
   * Variável de controle para verificar se é o primeiro laço que está sendo executado
   */
  $lPrimeiroLaco = true;

  foreach ($aDadosRelatorio as $oRetorno) {

    if ($oPdf->gety() > $oPdf->h - 30 || $lPrimeiroLaco) {

      montaCabecalho($iAltura, $oPdf);
      $lPrimeiroLaco = false;   
    }

    $oPdf->setfont('arial','',7);
    $dtAtendimento = implode('/', array_reverse(explode('-', $oRetorno->ov01_dataatend)));
    $oPdf->cell(20, $iAltura, $oRetorno->ov01_numero." / ".$oRetorno->ov01_anousu,  0, 0, "C", 0);
    $oPdf->cell(20, $iAltura, $oRetorno->p58_codproc,          0, 0, "C", 0);
    $oPdf->cell(100, $iAltura, $oRetorno->p58_requer,           0, 0, "L", 0); 
    $oPdf->cell(75, $iAltura, $oRetorno->p51_descr,            0, 0, "L", 0); 
    $oPdf->cell(50, $iAltura, substr($oRetorno->local, 0, 20), 0, 0, "C", 0); 
    $oPdf->cell(15, $iAltura, $dtAtendimento,                  0, 1, "C", 0);  
  }

  $oPdf->Output();

  /**
   * Função montaCabecalho
   * Função que imprime o cabeçalho do relatório
   *
   * @param integer $iAltura
   * @param object  $oPdf
   */
  function montaCabecalho($iAltura, $oPdf) {
    
    $oPdf->AddPage();
    $oPdf->setfont('arial','b',8); 
    $oPdf->cell(25, $iAltura, "Atendimento/Ano",    0, 0, "C", 1);
    $oPdf->cell(20, $iAltura, "Processo",      0, 0, "C", 1);
    $oPdf->cell(95, $iAltura, "Requerente",   0, 0, "C", 1); 
    $oPdf->cell(75, $iAltura, "Tipo Processo", 0, 0, "C", 1); 
    $oPdf->cell(50, $iAltura, "Local",         0, 0, "C", 1); 
    $oPdf->cell(15, $iAltura, "Inclusão",      0, 1, "C", 1);
  }
?>