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


class relatorioContabil {

  private $codigo  = null;
  private $linhas  = array();
  /**
   * 
   */
  function __construct($iCodigoRelatorio, $lLoadLinhas=true) {
    
    $this->codigo = $iCodigoRelatorio;
    if ($lLoadLinhas) {
      $this->linhas = $this->getLinhasRelatorio();
    }
  }
 /**
  * Retorna as linhas do Relatorio
  *
  */ 
  public function getLinhasRelatorio($lLoadCols = true, $iInstit = '', $iAnoUsu = '') {
   
    require_once("model/linhaRelatorioContabil.model.php");
    $aLinhas       = array();
    $oDaoLinhasRel = db_utils::getDao("orcparamseq");
    $sSqlLinhas    = $oDaoLinhasRel->sql_query($this->codigo, null, 
                             "o69_codparamrel,
                              o69_codseq,
                              o69_grupo,
                              o69_grupoexclusao,
                              o69_descr,
                              o69_librec,
                              o69_labelrel,
                              o69_libsubfunc,
                              o69_libfunc,
                              o69_totalizador,
                              o69_ordem,
                              o44_nivel as o69_nivel,
                              o44_nivelexclusao as o69_nivelexclusao,
                              o69_libnivel",
                              "o69_ordem",
                              null
                              );
   $rsLinhas      = $oDaoLinhasRel->sql_record($sSqlLinhas); 
   $aCols = db_utils::getColectionByRecord($rsLinhas, 0);

    foreach ($aCols as $oColunaConsulta ) {
       
       $oColuna          = new linhaRelatorioContabil($this->codigo, $oColunaConsulta->o69_codseq);
       $aLinhas[$oColunaConsulta->o69_codseq] = $oColunaConsulta;
       if ($lLoadCols) {
         
         if (empty($iInstit)) {
           $iInstit = db_getsession("DB_instit");
         }
         if (empty($iAnoUsu)) {
           $iAnoUsu = db_getsession("DB_anousu");
         }
         $aLinhas[$oColunaConsulta->o69_codseq]->valoresVariaveis =  $oColuna->getValoresColunas(null,
                                                                                                 null,
                                                                                                 $iInstit, 
                                                                                                 $iAnoUsu 
                                                                                                 );
       }
    }
    return $aLinhas;
  }
  
  function getLinhas() {

    return $this->linhas;
  }

  function getPeriodos() {
    
    $sSqlPeriodo  = "select o114_sequencial,";
    $sSqlPeriodo .= "       o114_descricao, ";
    $sSqlPeriodo .= "       o114_sigla ";
    $sSqlPeriodo .= "  from orcparamrelperiodos  ";
    $sSqlPeriodo .= "       inner join  periodo on o114_sequencial = o113_periodo ";
    $sSqlPeriodo .= " where o113_orcparamrel = {$this->codigo}";
    $sSqlPeriodo .= " order by o114_ordem";
    $rsPeriodo   = db_query($sSqlPeriodo);
    $aPeriodos   = db_utils::getColectionByRecord($rsPeriodo);
    return $aPeriodos;
  }
  
  function getNotaExplicativa ($oPdf, $iPeriodo,$iTam = 190) {
       
    /**
     * Tamanhos das fontes para as Notas Explicativas
     */
    $nTamanhoFonteNota  = 6;
    $nTamanhoFonteDados = 8;
    
    $sSqlNotaPadrao  = "select o42_notapadrao ";
    $sSqlNotaPadrao .= "  from orcparamrel ";
    $sSqlNotaPadrao .= " where o42_codparrel = {$this->codigo}";
    $rsNotaPadrao    = db_query($sSqlNotaPadrao);
    $oNotaPadrao     = db_utils::fieldsMemory($rsNotaPadrao, 0);
    require_once("model/configuracao/DBDepartamento.model.php");
    
    $iDepartamento = db_getsession("DB_coddepto");
    $oDepartamento = new DBDepartamento($iDepartamento);
    /*
     * nas notas explicativas, fonte, sera possivel colocar variaveis de seção se necessario
     * inicial teremos 3
     * [nome_departamento]
     * [data_emissao]
     * [hora_emissao]
    */
     
    $sDepartamento = $oDepartamento->getNomeDepartamento();
    $dtEmissao     = date("d/m/Y", db_getsession("DB_datausu"));
    $hEmissao      = date("H:i:s");
    $aParseVariaveis = array('[nome_departamento]' => $sDepartamento,
                             '[data_emissao]'      => $dtEmissao,
                             '[hora_emissao]'      => $hEmissao
    );    
    
    
    if (isset($oNotaPadrao->o42_notapadrao) && trim($oNotaPadrao->o42_notapadrao) != "") {
      
       if ($oPdf->gety() > $oPdf->h-35) {
         $oPdf->addpage();
       }
       
       $oPdf->setfont('arial','',6);  
       $sNotaPadrao = $oNotaPadrao->o42_notapadrao;

       foreach ($aParseVariaveis as $sIndiceValores => $oParseVariaveis) {
       
         if (str_replace($sIndiceValores, $oParseVariaveis, $sNotaPadrao)) {
       
           $sNotaPadrao = str_replace($sIndiceValores, $oParseVariaveis, $sNotaPadrao);
         }
       
       }
       $oPdf->multicell($iTam,3,$sNotaPadrao,0,"J");
    }
    
    $sSqlNota  = "select orcparamrelnota.*";
    $sSqlNota .= "  from orcparamrelnota  ";
    $sSqlNota .= "       inner join  orcparamrelnotaperiodo on o42_sequencial = o118_orcparamrelnota";
    $sSqlNota .= " where o42_codparrel = {$this->codigo}";
    $sSqlNota .= "   and o42_anousu = ".db_getsession("DB_anousu");
    $sSqlNota .= "   and o42_instit = ".db_getsession("DB_instit");
    $sSqlNota .= "   and o118_periodo = {$iPeriodo}";
    $rsNota   = db_query($sSqlNota);
    $oNotas   = db_utils::fieldsMemory($rsNota, 0);
    $oPdf->setfont('arial','',8);

    /**
     * Seta os tamanhos das fontes setada na tabela orcparamrelnota se ela for maior que zero,
     * Para as Notas Explicativas
     */
    if (isset($oNotas->o42_tamanhofontedados) && $oNotas->o42_tamanhofontedados > 0) {
      $nTamanhoFonteDados = $oNotas->o42_tamanhofontedados ;
    }
    if (isset($oNotas->o42_tamanhofontenota) && $oNotas->o42_tamanhofontenota > 0) {
      $nTamanhoFonteNota  = $oNotas->o42_tamanhofontenota;
    }
    
    if (isset($oNotas->o42_fonte) && trim($oNotas->o42_fonte) != "") {
      

      
      $sFonte = $oNotas->o42_fonte;
      
      /*
       * aqui criamos o array com as variaveis que estarao disponiveis
       * percorremos ele, fazendo um parse pelos valores correto
       */
      
      $sDepartamento = $oDepartamento->getNomeDepartamento();
      $dtEmissao     = date("d/m/Y", db_getsession("DB_datausu"));
      $hEmissao      = date("H:i:s");
      $aParseVariaveis = array('[nome_departamento]' => $sDepartamento,
                               '[data_emissao]'      => $dtEmissao,
                               '[hora_emissao]'      => $hEmissao
                               );
      foreach ($aParseVariaveis as $sIndiceValores => $oParseVariaveis) {
        
        if (str_replace($sIndiceValores, $oParseVariaveis, $sFonte)) {
        
          $sFonte = str_replace($sIndiceValores, $oParseVariaveis, $sFonte);
        }
        
      }
    
      $oPdf->setfont('arial','', 8);
      $oPdf->setfont('arial','',$nTamanhoFonteDados);
      $oPdf->multicell($iTam,3, $sFonte,0,"J");
    
    }
    if (isset($oNotas->o42_nota) && trim($oNotas->o42_nota) != ""){
    
    	if ($oPdf->gety() > $oPdf->h-35) {
        $oPdf->addpage();
      }
       
      $oPdf->ln(2);
      $oPdf->setfont('arial', '', 8); 
      $oPdf->ln(2);
      $oPdf->setfont('arial', '', $nTamanhoFonteNota);
      $oPdf->multicell($iTam, 3, $oNotas->o42_nota, 0, "J");
      
    }
    $oPdf->setfont('arial','',6);
    return true;
   }
   
   /**
    * Salva os filtros do usuário para o relatorio
    *
    * @param stdClass $oFilter Objeto com a definicao da classe
    * @param integer $iUsuario codigo do usuario
    * @return relatorioContabil
    */
   public function salvarParametrosUsuario($oFilter, $iUsuario) {
    
     /*
      * iniciamos a escrita o XML para a montagem do filtro
      */
    
     $oXmlWriter = new XMLWriter();
     $oXmlWriter->openMemory();
     $oXmlWriter->setIndent(true);
     $oXmlWriter->startDocument('1.0','ISO-8859-1');
     $oXmlWriter->endDtd();
     $oXmlWriter->startElement("filter");
    
     /**
      * gravamos os vinculo com o orcamento
      */
     $oXmlWriter->startElement("orgao");
     $oXmlWriter->writeAttribute("operador", $oFilter->orgao->operador);
     $oXmlWriter->writeAttribute("valor", implode(",",$oFilter->orgao->aOrgaos));
     $oXmlWriter->writeAttribute("id", "orgao");
     $oXmlWriter->endElement();
    
     $oXmlWriter->startElement("unidade");
     $oXmlWriter->writeAttribute("operador", $oFilter->unidade->operador);
     $oXmlWriter->writeAttribute("valor", implode(",", $oFilter->unidade->aUnidades));
     $oXmlWriter->writeAttribute("id", "unidade");
     $oXmlWriter->endElement();
     
     $oXmlWriter->startElement("funcao");
     $oXmlWriter->writeAttribute("operador", $oFilter->funcao->operador);
     $oXmlWriter->writeAttribute("valor", implode(",", $oFilter->funcao->aFuncoes));
     $oXmlWriter->writeAttribute("id", "funcao");
     $oXmlWriter->endElement();
    
     $oXmlWriter->startElement("subfuncao");
     $oXmlWriter->writeAttribute("operador", $oFilter->subfuncao->operador);
     $oXmlWriter->writeAttribute("valor", implode(",", $oFilter->subfuncao->aSubFuncoes));
     $oXmlWriter->writeAttribute("id", "subfuncao");
     $oXmlWriter->endElement();
    
     $oXmlWriter->startElement("programa");
     $oXmlWriter->writeAttribute("operador", $oFilter->programa->operador);
     $oXmlWriter->writeAttribute("valor", implode(",", $oFilter->programa->aProgramas));
     $oXmlWriter->writeAttribute("id", "programa");
     $oXmlWriter->endElement();
    
     $oXmlWriter->startElement("projativ");
     $oXmlWriter->writeAttribute("operador", $oFilter->projativ->operador);
     $oXmlWriter->writeAttribute("valor", implode(",", $oFilter->projativ->aProjAtiv));
     $oXmlWriter->writeAttribute("id", "projativ");
     $oXmlWriter->endElement();
     
     $oXmlWriter->startElement("elemento");
     $oXmlWriter->writeAttribute("operador", $oFilter->elemento->operador);
     $oXmlWriter->writeAttribute("valor", implode(",", $oFilter->elemento->aElementos));
     $oXmlWriter->writeAttribute("id", "elemento");
     $oXmlWriter->endElement();
     
     $oXmlWriter->startElement("recurso");
     $oXmlWriter->writeAttribute("operador", $oFilter->recurso->operador);
     $oXmlWriter->writeAttribute("valor",   implode(",", $oFilter->recurso->aRecursos));
     $oXmlWriter->writeAttribute("id", "recurso");
     $oXmlWriter->endElement();
    
     $oXmlWriter->endElement();
     $strBuffer  = $oXmlWriter->outputMemory();
     $oDaoLinhas = db_utils::getDao("orcparamseqfiltrousuario");
     $oDaoLinhas->o72_orcparamrel = $this->codigo;
     $oDaoLinhas->o72_idusuario   = $iUsuario;
     $oDaoLinhas->o72_filtro      = $strBuffer;
    
     $sWhere      = "o72_idusuario = {$iUsuario} and o72_orcparamrel = {$this->codigo}";
     $sSqlFiltro  = $oDaoLinhas->sql_query_file(null,"*", null, $sWhere);
     $rsFiltro    = $oDaoLinhas->sql_record($sSqlFiltro);
     if ($oDaoLinhas->numrows == 0) {
       $oDaoLinhas->incluir(null);
     } else {
      
       $iCodigoLinha               = db_utils::fieldsMemory($rsFiltro, 0)->o72_sequencial;
       $oDaoLinhas->o72_sequencial = $iCodigoLinha;
       $oDaoLinhas->alterar($iCodigoLinha);
      
     }
     if ($oDaoLinhas->erro_status == 0) {
       throw new Exception("Erro ao salvar parametros!\nErro retornado:{$oDaoLinhas->erro_msg}");    
     }
     return $this;
   }
   
  /**
   * Retorna os filtros que o usuario configurou para o relatorio
   *
   * @param integer $iUsuario codigo do usuário
   * @return Colecao com os filtros escolhidos
   */ 
  function getParametrosUsuario($iUsuario) {
    
    $oDaoLinhas = db_utils::getDao("orcparamseqfiltrousuario");
    $sWhere = "o72_idusuario = {$iUsuario} and o72_orcparamrel = {$this->codigo}";
    $sSqlFiltroPadrao  = $oDaoLinhas->sql_query_file(null, "*", null, $sWhere);
    $rsFiltroPadrao    = $oDaoLinhas->sql_record($sSqlFiltroPadrao);
    $oFiltro = new stdClass();
    $oFiltro->orgao->operador = 'in';
    $oFiltro->orgao->valor    = array();
      
    $oFiltro->unidade->operador = 'in';
    $oFiltro->unidade->valor    =  array();
      
    $oFiltro->funcao->operador = 'in';
    $oFiltro->funcao->valor    =  array();
      
    $oFiltro->subfuncao->operador = 'in';
    $oFiltro->subfuncao->valor    =  array();
    
    $oFiltro->programa->operador = 'in';
    $oFiltro->programa->valor    =  array();
    
    $oFiltro->projativ->operador = 'in';
    $oFiltro->projativ->valor    =  array();
    
    $oFiltro->elemento->operador = 'in';
    $oFiltro->elemento->valor    =  array();
    
    $oFiltro->recurso->operador = 'in';
    $oFiltro->recurso->valor    =  array();
    
    if ($oDaoLinhas->numrows > 0) {
      
      $oDadosFiltro = db_utils::fieldsMemory($rsFiltroPadrao, 0);
      $oDomXml  = new DOMDocument();
      $oDomXml->loadXML($oDadosFiltro->o72_filtro);
      
      $oOrgao = $oDomXml->getElementsByTagName("orgao");
      $oFiltro->orgao->operador = $oOrgao->item(0)->getAttribute("operador");
      $aOrgao                   = explode(",", $oOrgao->item(0)->getAttribute("valor"));
      $oFiltro->orgao->valor    = $aOrgao;
      if (count($aOrgao) == 1 && $aOrgao[0] == "") {
        $oFiltro->orgao->valor = array();  
      }
      
      $oUnidade = $oDomXml->getElementsByTagName("unidade");
      $oFiltro->unidade->operador = $oUnidade->item(0)->getAttribute("operador");
      $aUnidade                   = explode(",", $oUnidade->item(0)->getAttribute("valor"));
      $oFiltro->unidade->valor  = $aUnidade;
      if (count($aUnidade) == 1 && $aUnidade[0] == "") {
        $oFiltro->unidade->valor = array();  
      }
        
      $oFuncao = $oDomXml->getElementsByTagName("funcao");
      $oFiltro->funcao->operador = $oFuncao->item(0)->getAttribute("operador");
      $aValores                  = explode(",", $oFuncao->item(0)->getAttribute("valor"));
      $oFiltro->funcao->valor    = $aValores;
      if (count($aValores) == 1 && $aValores[0] == "") {
        $oFiltro->funcao->valor = array();
      }
      $oSubFuncao = $oDomXml->getElementsByTagName("subfuncao");
      $oFiltro->subfuncao->operador = $oSubFuncao->item(0)->getAttribute("operador");
      $aValores                  = explode(",", $oSubFuncao->item(0)->getAttribute("valor"));
      $oFiltro->subfuncao->valor    = $aValores;
      if (count($aValores) == 1 && $aValores[0] == "") {
        $oFiltro->subfuncao->valor = array();
      }
      
      $oPrograma = $oDomXml->getElementsByTagName("programa");
      $oFiltro->programa->operador = $oPrograma->item(0)->getAttribute("operador");
      $aValores                  = explode(",", $oPrograma->item(0)->getAttribute("valor"));
      $oFiltro->programa->valor    = $aValores;
      if (count($aValores) == 1 && $aValores[0] == "") {
        $oFiltro->programa->valor = array();
      }
      
      $oProjAtiv = $oDomXml->getElementsByTagName("projativ");
      $oFiltro->projativ->operador = $oProjAtiv->item(0)->getAttribute("operador");
      $aValores                  = explode(",", $oProjAtiv->item(0)->getAttribute("valor"));
      $oFiltro->projativ->valor    = $aValores;
      if (count($aValores) == 1 && $aValores[0] == "") {
        $oFiltro->projativ->valor = array();
      }
      
      $oRecurso = $oDomXml->getElementsByTagName("recurso");
      $oFiltro->recurso->operador = $oRecurso->item(0)->getAttribute("operador");
      $aRecursos                  = explode(",", $oRecurso->item(0)->getAttribute("valor"));
      $oFiltro->recurso->valor    = $aRecursos;
      if (count($aRecursos) == 1 && $aRecursos[0] == "") {
        $oFiltro->recurso->valor = array();
      }
      
      $oElemento  = $oDomXml->getElementsByTagName("elemento");
      if ($oElemento->item(0)) {

        $oFiltro->elemento->operador = $oElemento->item(0)->getAttribute("operador");
        $aValores                    = explode(",", $oElemento->item(0)->getAttribute("valor"));
        $oFiltro->elemento->valor    = $aValores;
        if (count($aValores) == 1 && $aValores[0] == "") {
          $oFiltro->elemento->valor = array();
        }
      }
    }
    return $oFiltro;
  }
  
  /**
   * Escreve as assinaturas do relatorio
   *
   * @param FPDF $oPdf instancia da classe fpdf
   * @param string $sTipo tipo da assinatura aceita os tipos: 'GF' para relatorios da RGF  e 'LRF' para os relatorios do RREO
   */
  public function assinatura(FPDF $oPdf, $sTipo) {
    
    require_once("fpdf151/assinatura.php");
    $oAssinatura = new cl_assinatura;
    $oPdf->ln(8);
    if ($oPdf->GetY() > $oPdf->h - 35) {
      $oPdf->AddPage();
    }
    assinaturas($oPdf, $oAssinatura, $sTipo);
  }
  
  public function getLinhasCompleto() {
    
    require_once("model/linhaRelatorioContabil.model.php");
    $aLinhas       = array();
    $oDaoLinhasRel = db_utils::getDao("orcparamseq");
    $sSqlLinhas    = $oDaoLinhasRel->sql_query($this->codigo, null, 
                             "o69_codparamrel,
                              o69_codseq,
                              o69_ordem",
                              "o69_ordem",
                              null
                              );
   $rsLinhas = $oDaoLinhasRel->sql_record($sSqlLinhas); 
   $aCols    = db_utils::getColectionByRecord($rsLinhas, 0);

    foreach ($aCols as $oColunaConsulta ) {
       
       $oLinhaRelatorio   = new linhaRelatorioContabil($this->codigo, $oColunaConsulta->o69_codseq);
       $aLinhas[$oColunaConsulta->o69_ordem] = $oLinhaRelatorio;
    }
    return $aLinhas;
  }
  
  /**
   * processa uma formula de coluns
   *
   * @param string $sVarNome nome da Variavel das linhs
   * @param string $sFormula formula das linhas
   * @param integer $iColuna coluna que está sendo calculado a formula
   * @param array $aLinhas array com as linhas, (para verificadao da Variavel F
   * @return string com a formula processada
   */
  function parseFormula($sVarNome, $sFormula, $iColuna, $aLinhas) {
    
    $iTamanhoFormula = strlen(strtolower($sFormula));
    $sFormula        = strtolower($sFormula);
    $sFormulaRetorno = '';
    for ($i = 0; $i < $iTamanhoFormula; $i++) {
      
      $iCaracterAtual    = substr($sFormula, $i,1);
      $iCaracterAnterior = substr($sFormula, $i-1, 1);
      $iCaracterProximo  = substr($sFormula, $i+1, 1);
      if ($iCaracterAtual == "f" || $iCaracterAtual == "l") {
        /**
         * verificamos se o proximo caratecter é um '['
         */
        $iCodigoLinha = null;
        if ($iCaracterProximo == "[") {
          
          $iInicioChave     = strpos($sFormula, "[", $i);
          $iFimChave        = strpos($sFormula, "]", $i);
          $iCodigoLinha     = substr($sFormula, $iInicioChave+1, ($iFimChave-$iInicioChave)-1);
          
          /**
           * verifica se linha 
           */
          if ($iCaracterAtual == "f") {
            
            $sFormulaRetorno .= $this->parseFormula($sVarNome, 
                                                    ${$sVarNome}[$iCodigoLinha]->colunas[$iColuna]->o116_formula, 
                                                    $iColuna,
                                                    $aLinhas
                                                    );
            $sFormula         = substr_replace($sFormula, '', $iInicioChave, ($iFimChave-$iInicioChave)+1);
          } else {
            $sFormulaRetorno .= '$'.$sVarNome;
          }
        } else {
          $sFormulaRetorno .= $iCaracterAtual;
        }
      } else {
        
        $sFormulaRetorno .= $iCaracterAtual;
      }
    }
    return $sFormulaRetorno;
  }
}

?>