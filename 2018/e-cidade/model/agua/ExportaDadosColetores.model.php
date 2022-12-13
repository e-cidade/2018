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


require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");

class clExpDadosColetores {
    
  /**
   * Classe cl_aguacoletorexporta
   * @var objeto
   */
  protected $clACExporta;
  
  /**
   * Classe cl_aguacoletorexportasituacao
   * @var objeto
   */
  protected $clACExportaSituacao;
  
  /**
   * Classe cl_aguacoletorexportadados
   * @var objeto
   */
  protected $clACExportaDados;
  
  /**
   * Classe cl_agualeitura
   * @var objeto
   */
  protected $clAguaLeitura;
  
  /**
   * Classe cl_aguacoletorexportadadosleitura
   * @var objeto
   */
  protected $clACExportaDadosLeitura;
  
  /**
   * Classe cl_aguacoletorexportadadosreceita
   * @var objeto
   */
  protected $clACExportaDadosReceita;
  
  protected $clAguaConsumoTipo;
  
  protected $clAguaCalc;
  
  public $iCodColetorExporta;
  
  public $iCodColetorExportaSituacao;
  
  public $iCodColetor;
  
  public $iInstituicao;
  
  public $iAno;
  
  public $iMes;
  
  public $iSituacao;
  
  public $iUsuario;
  
  public $dData;
  
  public $sHora;
  
  public $sMotivo;
  
  public $sCodRotas;
  
  public $sCodRuas;
  
  public $rsACExportaDados;
  
  public $iMatricula;
  
  public $iErroStatus = 1;
  
  public $sErroMsg;
  
  public $iNumRowsMatriculas;
  
  public $iNumRowsLeituras;
  
  public $iNumRowsArreCad;
  
  public $iCodHidrometro;
  
  public $iNroHidrometro;
  
  public $iCgmLeiturista;
  
  public $iCodLeitura;
  
  public $iConsumoPadrao;
  
  public $iMesesUltimaLeitura;
  
  public $iCodCorrespondencia;
  
  public $iCodColetorExportaDados;
  
  public $sSqlArreMatric;
  
  public $iAnoVencimento;
  
  public $iMesVencimento;
  
  public $iParcela;
  
  public $sSqlArreCad;
  
  public $iArreTipo;
  
  public $iRota;
  
  public $iTipo;
  
  public $iCodLogradouro;
  
  public $iCodBairro;
  
  public $iZona;

  public $iOrdem;
  
  public $sResponsavel;
  
  public $sNomeLogradouro;
  
  public $iNumero;
  
  public $cLetra;
  
  public $sComplemento;
  
  public $sNomeBairro;
  
  public $sCidade;
  
  public $sEstado;
  
  public $iQuadra;
  
  public $iEconomias;
  
  public $sCategoria;
  
  public $fAreaContruida;
  
  public $sNroHidrometro;
  
  public $iNumPre;
  
  public $sNatureza;
  
  public $dDtLeituraAtual;
  
  public $iDiasLeitura;
  
  public $dDtLeituraAnterior;
  
  public $iConsumo;
  
  public $iMediaDiaria;
  
  public $fConsumoPadrao;
  
  public $fConsumoMaximo;
  
  public $dDtVencimento;
  
  public $fValorAcrescimo;
  
  public $fValorDesconto;
  
  public $fValorTotal;
  
  public $sLinhaDigitavel;
  
  public $sCodigoBarras;
  
  public $iImprimeConta;
  
  public $fValorM3Excesso;
  
  public $iLeituraColetada;
  
  public $sObservacao;
  
  public $iCodConsumoTipo;
  
  public $sDescrConsumo;
  
  public $sAvisoLeiturista;
  
  /**
   * Insere dados na tabela aguacoletorexportadadosleitura
   * @param integer $iCodColetorExportaDados - relacionamento com a tabela aguacoletorexportadados
   * @param integer $iCodLeitura - relacionamento com a tabela agualeitura
   * @param integer $iDias - numero de dias entre a leitura atual e a leitura anterior
   * @param integer $iMeses - numero de meses da ultima leitura valida(fc_agua_mesesultimaleitura)
   * @param integer $iTipo - 1 Importada, 2 - exportada
   */
  public function geraACExportaDadosLeitura($iCodColetorExportaDados, $iCodLeitura, $iDias = "0", $iMeses = "0", $iTipo = "2") {
    require_once("classes/db_aguacoletorexportadadosleitura_classe.php");
    $this->clACExportaDadosLeitura = new cl_aguacoletorexportadadosleitura();
    
    $this->iCodColetorExportaDados = $iCodColetorExportaDados;
    $this->iCodLeitura = $iCodLeitura;
    $this->iDias  = $iDias;
    $this->iMeses = $iMeses;
    $this->iTipo  = $iTipo;
    
    /* verifica campos obrigatorios */
    
    if ($this->iCodLeitura    === "" or 
        $this->iCgmLeiturista === "")
      {
     
      $this->iErroStatus = 0;
      $this->sErroMsg    = "Inclusão na tabela aguacoletorexportadadosleitura não efetuado. Operação abortada. Erro: Campos obrigatórios em branco, Código Leitura: $this->iCodLeitura, $this->iCgmLeiturista";
      return false;
    
        
    } else {
    
      $this->clACExportaDadosLeitura->x51_aguacoletorexportadados = $this->iCodColetorExportaDados;
      $this->clACExportaDadosLeitura->x51_agualeitura             = $this->iCodLeitura;
      $this->clACExportaDadosLeitura->x51_diasultimaleitura       = $this->iDias;
      $this->clACExportaDadosLeitura->x51_mesesultimaleitura      = $this->iMeses;
      $this->clACExportaDadosLeitura->x51_tipoleitura             = $this->iTipo;
      $this->clACExportaDadosLeitura->x51_numcgm                  = $this->iCgmLeiturista;
      $this->clACExportaDadosLeitura->incluir(null);
    
      if($this->clACExportaDadosLeitura->erro_status == "0") {
        $this->iErroStatus = 0;
        $this->sErroMsg    = "Inclusão na tabela aguacoletorexportadadosleitura não efetuado. Operação abortada. Erro: {$this->clACExportaDadosLeitura->erro_msg}";
        return false;
      
      }
    
    }
    
    
  }
  
  /**
   * Retorna ultima seis leituras da matricula informada
   * @param integer $iMatricula
   * @param integer $iAno - ano de referencioa
   * @param integer $iMes - mes de referencia
   */
  public function getLeituras($iMatricula, $iAno = null, $iMes = null) {
    
    require_once("classes/db_aguacoletorexportadados_classe.php");
    
    $this->iMatricula = $iMatricula;
    
    if($iAno != null) {
      $this->iAno = $iAno;
    }
    if($iMes != null) {
      $this->iMes = $iMes;
    }    
    
    $rsLeituras = $this->clACExportaDados->sql_record($this->clACExportaDados->sql_query_leituras_anteriores($this->iMatricula, $this->iAno, $this->iMes));
    
    $this->iNumRowsLeituras = $this->clACExportaDados->numrows;

    return $rsLeituras;
    
  }
  
  /**
   * Verifica se será impressa conta no imovel
   * @param integer $iMatricula
   * @param integer $iCodCorresponcia - se houver codigo de correspondencia n sera impressa conta
   */
  public function getImprimeConta($iMatricula, $iCodCorresponcia = null) {
    
    $this->iMatricula = $iMatricula;
    
    $this->iCodCorrespondencia = $iCodCorresponcia;
    
    if($this->iCodCorrespondencia != "") {
      
      return 1;
      
    } else {
      
      $rsImpConta = db_query("select count(*) as debitoconta from debcontapedidomatric where d68_matric = $this->iMatricula");
    
      $oImpConta  = db_utils::fieldsMemory($rsImpConta, 0);
      
      if($oImpConta->debitoconta > 0) {
        
        return 3;
        
      } else {
        
        return 2;
        
      }
      
    }
    
  }
  
  /**
   * Retorna o consumo padrao do imovel informado 
   * @param integer $iMatricula
   * @param integer $iAno
   * @param integer $iMes
   */
  public function getConsumoPadrao($iMatricula, $iAno = null, $iMes = null) {
    
    $this->iMatricula = $iMatricula;
    
    if($iAno != null) {
      $this->iAno = $iAno;
    }
    if($iMes != null) {
      $this->iMes = $iMes;
    }

    $rsConsumo = db_query("select fc_agua_consumomaximo($this->iAno, $this->iMes, $this->iMatricula) as consumopadrao");
    
    $oConsumo = db_utils::fieldsMemory($rsConsumo, 0);
    
    return $this->iConsumoPadrao = $oConsumo->consumopadrao;
    
  }
  
  /**
   * Retorna o numero de meses da ultima leitura valida
   * @param integer $iMatricula
   * @param integer $iAno
   * @param integer $iMes
   * @param integer $iCodLeitura
   */
  public function getMesesUltimaLeitura($iMatricula, $iAno = null, $iMes = null, $iCodLeitura = null) {
    
    $this->iMatricula = $iMatricula;
    
    if($iAno != null) {
      $this->iAno = $iAno;
    }
    if($iMes != null) {
      $this->iMes = $iMes;
    }
    if($iCodLeitura != null) {
      $this->iCodLeitura = $iCodLeitura;
    }
    
    $rsMesesUltimaLeitura = db_query("select fc_agua_mesesultimaleitura($this->iAno, $this->iMes, $this->iMatricula, $this->iCodLeitura) as mesesultimaleitura");
    
    $oMesesUltimaLeitura  = db_utils::fieldsMemory($rsMesesUltimaLeitura, 0);
    
    return $this->iMesesUltimaLeitura = $oMesesUltimaLeitura->mesesultimaleitura;
    
  }
  
  /**
   * Insere uma leitura em branco na tabela agualeitura
   * @param integer $iCgmLeiturista - Codigo do leiturista para qual foi designado o coletor
   */
  public function geraLeitura($iCgmLeiturista) {
    require_once("classes/db_agualeitura_classe.php");
    $this->clAguaLeitura = new cl_agualeitura();
    
    $this->iCgmLeiturista = $iCgmLeiturista;
    
    $this->clAguaLeitura->x21_codhidrometro = $this->iCodHidrometro;
    $this->clAguaLeitura->x21_exerc         = $this->iAno;
    $this->clAguaLeitura->x21_mes           = $this->iMes;
    $this->clAguaLeitura->x21_situacao      = "0"; //normal
    $this->clAguaLeitura->x21_numcgm        = $this->iCgmLeiturista;
    $this->clAguaLeitura->x21_dtleitura     = $this->dData;
    $this->clAguaLeitura->x21_usuario       = $this->iUsuario;
    $this->clAguaLeitura->x21_dtinc         = $this->dData;
    $this->clAguaLeitura->x21_leitura       = "0";
    $this->clAguaLeitura->x21_virou         = "false";
    $this->clAguaLeitura->x21_tipo          = "2"; //exportada coletor
    $this->clAguaLeitura->x21_status        = "2"; // inativo
    $this->clAguaLeitura->incluir(null);     
    
    if($this->clAguaLeitura->erro_status == "0") {
      
      $this->iErroStatus = 0;
      $this->sErroMsg    = "Inclusão na tabela agualeitura não efetuada. Operação Abortada. Erro: {$this->clAguaLeitura->erro_msg}.<br>";
      return false;
      
    } else {
      
      return $this->iCodLeitura = $this->clAguaLeitura->x21_codleitura;
      
    }
    
    
  }
  
  /**
   * Funcao que atribui o valor codigo do hidrometro e numero do hidrometro
   * @param integer $iMatricula
   */
  public function getHidrometroAtivo($iMatricula = null) {
    
    $rsHidrometro = $this->clACExportaDados->sql_record($this->clACExportaDados->sql_query_hidrometro_ativo($iMatricula));
    
    $oHidrometro  = db_utils::fieldsMemory($rsHidrometro, 0);
    
    if($this->clACExportaDados->numrows > 0) {
    
      $this->iCodHidrometro = $oHidrometro->x04_codhidrometro;
    
      $this->iNroHidrometro = $oHidrometro->x04_nrohidro;
      
      $this->sAvisoLeiturista = $oHidrometro->x04_avisoleiturista;
      
      
    }
    
  }
  
  /**
   * Informa a categoria do imovel - predio, terreno, etc..
   * @param integer $iMatricula
   */
  public function getCatImovel($iMatricula = null) {
    
    $rsCatImovel    = $this->clACExportaDados->sql_record($this->clACExportaDados->sql_query_categoria_imovel($iMatricula));
    
    $numRowsRetorno = $this->clACExportaDados->numrows;
    
    if($numRowsRetorno > 0) {
      
      $oCatImovel  = db_utils::fieldsMemory($rsCatImovel, 0);
      
      return $oCatImovel->j31_descr;
       
    } else {
      
      return "Terreno";
      
    }
    
  }
  
  /**
   * Retorna a data de vencimento de contas do imovel
   * @param integer $iMatricula
   * @param integer $iAno
   * @param integer $iMes
   */
  public function getDtVencMatric($iMatricula, $iAno = null, $iMes = null) {
    
    if($iAno != null) {
      $this->iAno = $iAno;
    }
    
    if($iMes != null) {
      $this->iMes = $iMes;
    }
    
    $this->iMatricula = $iMatricula;
    
    
    $rsDtVencimento   = db_query("select fc_agua_datavencimento($this->iAno, $this->iMes, $this->iMatricula) as k00_dtvenc");
    
    $oDtVencimento    = db_utils::fieldsMemory($rsDtVencimento, 0);
      
    return $oDtVencimento->k00_dtvenc;
    
  }
  
  /**
   * Informa o codigo do tipo de arrecadação
   * @param integer $iAno
   */
  public function getArretipo($iAno = null) {
    
    if($iAno != null) {
      $this->iAno = $iAno;
      
    }
    
    $rsArreTipo = db_query("select fc_agua_confarretipo($this->iAno) as x18_arretipo");
    
    $oArreTipo  = db_utils::fieldsMemory($rsArreTipo, 0);
    
    return $oArreTipo->x18_arretipo;
    
  }
  
  /**
   * Retorna as informações das matriculas de rotas e ruas informadas
   * @param string $sCodRotas
   * @param string $sCodRuas
   */
  public function getInformacoesMatriculas($sCodRotas, $sCodRuas) {
    require_once("classes/db_aguacoletorexportadados_classe.php");
    $this->clACExportaDados = new cl_aguacoletorexportadados();
    
    $this->sCodRotas = $sCodRotas;
    
    $this->sCodRuas  = $sCodRuas;
    
    $this->rsACExportaDados   = $this->clACExportaDados->sql_record($this->clACExportaDados->sql_query_dados_matriculas($this->sCodRotas, $this->sCodRuas));
    
    $this->iNumRowsMatriculas = $this->clACExportaDados->numrows;
    
    return $this->rsACExportaDados;
    
  }
  
  /**
   * Insere registro na tabela aguacoletorexportasituacao
   * @param integer $iCodColetorExporta - codigo de referencia da tabela aguacoletorexporta
   * @param integer $iUsuario - usuario que realizou a operação
   * @param integer $dData - data da operção
   * @param integer $sHora - hora da operação
   * @param integer $sMotivo - motivo da operação - 
   * @param integer $iSituacao - situacao da operacao - Importar, exportar, cancelar
   */
  public function geraACExportaSituacao($iCodColetorExporta = null, $iUsuario, $dData, $sHora, $sMotivo, $iSituacao) {
    
    require_once("classes/db_aguacoletorexportasituacao_classe.php");
    $this->clACExportaSituacao = new cl_aguacoletorexportasituacao();
    
    if($iCodColetorExporta != null) {
      $this->iCodColetorExporta = $iCodColetorExporta;  
    }
    
    $this->iUsuario  = $iUsuario;
    
    $this->dData     = $dData;
    
    $this->sHora     = $sHora;
    
    $this->sMotivo   = $sMotivo;
    
    $this->iSituacao = $iSituacao;
    
    $this->clACExportaSituacao->x48_aguacoletorexporta = $this->iCodColetorExporta;
    $this->clACExportaSituacao->x48_usuario            = $this->iUsuario;
    $this->clACExportaSituacao->x48_data               = $this->dData;
    $this->clACExportaSituacao->x48_hora               = $this->sHora;
    $this->clACExportaSituacao->x48_motivo             = $this->sMotivo;
    $this->clACExportaSituacao->x48_situacao           = $this->iSituacao;
    
    $this->clACExportaSituacao->incluir(null);
    
    if($this->clACExportaSituacao->erro_status == "0") {
      
      $this->iErroStatus = 0;
      $this->sErroMsg    = "Inclusão na tabela aguacoletorexportasituacao não efetuada. Operação abortada. Erro: {$this->clACExportaSituacao->erro_msg}<br/>";
      return false;
      
    } else {
      
      $this->iCodColetorExportaSituacao = $this->clACExportaSituacao->x48_sequencial;
      return $this->clACExportaSituacao->x48_sequencial;
      
    }
    
    
  }
  
  /**
   * Registro na tabela aguacoletorexporta 
   * @param integer $iCodColetor
   * @param integer $iInstituicao
   * @param integer $iAno
   * @param integer $iMes
   * @param integer $iSituacao
   */
  public function geraACExporta($iCodColetor, $iInstituicao, $iAno, $iMes, $iSituacao) {

    require_once("classes/db_aguacoletorexporta_classe.php");
    $this->clACExporta = new cl_aguacoletorexporta();

    /* validação de campos obrigatórios */
    if ($iAno === "" or
        $iMes === "" )
      {
     
      $this->iErroStatus = 0;
      $this->sErroMsg    = "Inclusão na tabela aguacoletorexporta não efetuada. Operação abortada. Erro: Campos obrigatórios em branco!";
      return false;
        
    } else {
    
      $this->iCodColetor  = $iCodColetor;
      $this->iInstituicao = $iInstituicao;
      $this->iAno         = $iAno;
      $this->iMes         = $iMes;
      $this->iSituacao    = $iSituacao;
    
      $this->clACExporta->x49_aguacoletor = $this->iCodColetor;
      $this->clACExporta->x49_instit      = $this->iInstituicao;
      $this->clACExporta->x49_anousu      = $this->iAno;
      $this->clACExporta->x49_mesusu      = $this->iMes;
      $this->clACExporta->x49_situacao    = $this->iSituacao;
    
      $this->clACExporta->incluir(null);
    
      if ($this->clACExporta->erro_status == "0") {
      
        $this->iErroStatus = 0;
        $this->sErroMsg    = "Inclusão na tabela aguacoletorexporta não efetuada. Operação abortada. Erro: {$this->clACExporta->erro_msg}<br/>";
        return false;
            
      } else {
      
        $this->iCodColetorExporta = $this->clACExporta->x49_sequencial;
        return $this->clACExporta->x49_sequencial;
      
      }
      
    }
    
  } 

  public function getSqlArreMatric($iMatricula, $iInstituicao) {
    
    $this->iMatricula   = $iMatricula;
    $this->iInstituicao = $iInstituicao;
    
    $this->sSqlArreMatric = "(select distinct 
                                     arrematric.k00_numpre, 
                                     arrematric.k00_matric, 
                                     arrematric.k00_perc    
                                from arrematric 
                               inner join arreinstit  on arreinstit.k00_numpre = arrematric.k00_numpre 
                                                     and arreinstit.k00_instit = {$this->iInstituicao}
                               where arrematric.k00_matric = {$this->iMatricula})";
  
    return "$this->sSqlArreMatric as arrematric";
    
  }
  
  public function getSqlArreCad($sSqlArreMatric, $iArreTipo, $iAnoVencimento, $iMesVencimento, $iParcela) {
    
    $this->sSqlArreMatric = $sSqlArreMatric;
    $this->iArreTipo      = $iArreTipo;
    $this->iAnoVencimento = $iAnoVencimento;
    $this->iMesVencimento = $iMesVencimento;
    $this->iParcela       = $iParcela;
    
    
    $this->sSqlArreCad = "
    select * from (
        select arrecad.k00_receit, 
               arrecad.k00_numpre, 
               arrecad.k00_numpar, 
               arrecad.k00_numtot,
               arrecad.k00_tipo, 
               arrecad.k00_dtvenc, 
               round(arrecad.k00_valor, 2) as k00_valor, 
               tabrec.k02_descr 
          from {$this->sSqlArreMatric} 
               inner join arrecad  on arrecad.k00_numpre = arrematric.k00_numpre
               inner join tabrec   on tabrec.k02_codigo  = arrecad.k00_receit
         where arrecad.k00_tipo   = {$this->iArreTipo}
           and arrecad.k00_numpar = {$this->iParcela}
           and extract(year from arrecad.k00_dtvenc) = {$this->iAnoVencimento}
        union
          select min(arrecad.k00_receit) as k00_receit, 
                 arrecad.k00_numpre, 
                 arrecad.k00_numpar, 
                 arrecad.k00_numtot,
                 arrecad.k00_tipo, 
                 arrecad.k00_dtvenc, 
                 round(sum(coalesce(arrecad.k00_valor, 0)), 2) as k00_valor, 
                 'PARCELAM DIV TX' as k02_descr 
            from {$this->sSqlArreMatric}  
                 inner join arrecad       on arrecad.k00_numpre       = arrematric.k00_numpre
                 inner join arretipo      on arretipo.k00_tipo        = arrecad.k00_tipo
           where arretipo.k03_tipo                      = 6
             and extract(year from arrecad.k00_dtvenc)  = {$this->iAnoVencimento}
             and extract(month from arrecad.k00_dtvenc) = {$this->iMesVencimento}
             and not exists (select arrenaoagrupa.k00_numpre 
                               from arrenaoagrupa 
                              where arrenaoagrupa.k00_numpre = arrecad.k00_numpre)
        group by arrecad.k00_numpre, 
                 arrecad.k00_numpar, 
                 arrecad.k00_numtot, 
                 arrecad.k00_tipo, 
                 arrecad.k00_dtvenc 
        union 
        select arrecad.k00_receit, 
               arrecad.k00_numpre, 
               arrecad.k00_numpar, 
               arrecad.k00_numtot,
               arrecad.k00_tipo, 
               arrecad.k00_dtvenc, 
               round(arrecad.k00_valor, 2) as k00_valor, 
               tabrec.k02_descr 
          from {$this->sSqlArreMatric}  
               inner join arrecad       on arrecad.k00_numpre       = arrematric.k00_numpre
               inner join arretipo      on arretipo.k00_tipo        = arrecad.k00_tipo
               inner join tabrec        on tabrec.k02_codigo        = arrecad.k00_receit
         where (arrecad.k00_tipo                      <> {$this->iArreTipo} 
           and  arretipo.k03_tipo                     <> 6)
           and extract(year from arrecad.k00_dtvenc)  = {$this->iAnoVencimento}
           and extract(month from arrecad.k00_dtvenc) = {$this->iMesVencimento}
           and not exists (select arrenaoagrupa.k00_numpre
                             from arrenaoagrupa
                            where arrenaoagrupa.k00_numpre = arrecad.k00_numpre)) as x
      order by k00_numpre, 
               k00_receit";
    
    $rsArreCad = db_query($this->sSqlArreCad);
    
    $this->iNumRowsArreCad = pg_num_rows($rsArreCad);
    
    return $rsArreCad;
    
  }
  
  /**
   * Registro de receitas da matricula
   * @param integer $iCodReceita
   * @param integer $iCodColetorExportaDados
   * @param string  $sDescricao
   * @param integer $iParcela
   * @param integer $fValor
   * @param integer $iNumPre
   * @param integer $iNumTotal
   */
  public function geraACExportaDadosReceita($iCodReceita, $iCodColetorExportaDados, $sDescricao, $iParcela, $fValor, $iNumPre, $iNumTotal) {
    require_once("classes/db_aguacoletorexportadadosreceita_classe.php");
    $this->clACExportaDadosReceita = new cl_aguacoletorexportadadosreceita();
    
    /* validação de campos obrigatórios */
    if ($iCodReceita === "" or
        $sDescricao  === "")
      {
     
      $this->iErroStatus = 0;
      $this->sErroMsg    = "Inclusão na tabela aguacoletorexportadadosreceita não efetuada. Operação abortada. Erro: Campos obrigatórios em branco, Numpre: $iNumPre";
      return false;
    
        
    } else {
    
      $this->iCodReceita             = $iCodReceita;
      $this->iCodColetorExportaDados = $iCodColetorExportaDados;
      $this->sDescricao              = $sDescricao;
      $this->iParcela                = $iParcela;
      $this->fValor                  = $fValor;
      $this->iNumPre                 = $iNumPre;
      $this->iNumTotal               = $iNumTotal;
    
      $this->clACExportaDadosReceita->x52_receita                 = $this->iCodReceita;
      $this->clACExportaDadosReceita->x52_aguacoletorexportadados = $this->iCodColetorExportaDados;
      $this->clACExportaDadosReceita->x52_descricao               = $this->sDescricao;
      $this->clACExportaDadosReceita->x52_numpar                  = $this->iParcela;
      $this->clACExportaDadosReceita->x52_valor                   = $this->fValor;
      $this->clACExportaDadosReceita->x52_numpre                  = $this->iNumPre;
      $this->clACExportaDadosReceita->x52_numtot                  = $this->iNumTotal;
      $this->clACExportaDadosReceita->incluir(null);
    
      if($this->clACExportaDadosReceita->erro_status == "0") {
      
        $this->iErroStatus = 0;
        $this->sErroMsg    = "Inclusão na tabela aguacoletorexportadadosreceita não efetuada. Operação abortada. Erro: {$this->clACExportaDadosReceita->erro_msg}<br>";
        return false;
      
      } else {
      
        return $this->clACExportaDadosReceita->x52_sequencial;
      }
    
    }
    
  }
  
  /**
   * REgistro das matriculas exportadadas
   * @param integer $iCodColetorExporta
   * @param integer $iCodColetorExportaDados
   */
  public function geraACExportaDados($iCodColetorExporta, $iCodColetorExportaDados = "null") {
    
    require_once("classes/db_aguacoletorexportadados_classe.php");
    $this->clACExportaDados = new cl_aguacoletorexportadados;
    
    /* validação de campos obrigatórios */
    if ($this->iImprimeConta      === "" or 
        $this->fConsumoMaximo     === "" or
        $this->fConsumoPadrao     === "" or
        $this->iCodColetorExporta === "" or
        $this->iRota              === "" or
        $this->iMatricula         === "" or
        $this->iCodLogradouro     === "" or
        $this->sNomeLogradouro    === "" or
        $this->iEconomias         === "" or
        $this->iNumPre            === "" or
        $this->dDtVencimento      === "" ) 
      {
     
      $this->iErroStatus = 0;
      $this->sErroMsg    = "Inclusão na tabela aguacoletorexportadados não efetuada. Operação abortada.  Erro: Campos obrigatórios em branco, Matricula: $this->iMatricula";
      return false;
        
    } else {
        
      $this->iCodColetorExportaDados = $iCodColetorExportaDados;
      $this->iCodColetorExporta      = $iCodColetorExporta;
    
      $this->clACExportaDados->x50_aguacoletorexportadados = $this->iCodColetorExportaDados;
      $this->clACExportaDados->x50_aguacoletorexporta      = $this->iCodColetorExporta;  
      $this->clACExportaDados->x50_matric                  = $this->iMatricula;
      $this->clACExportaDados->x50_rota                    = $this->iRota;
      $this->clACExportaDados->x50_tipo                    = $this->iTipo;
      $this->clACExportaDados->x50_codlogradouro           = $this->iCodLogradouro;
      $this->clACExportaDados->x50_codbairro               = $this->iCodBairro;
      $this->clACExportaDados->x50_zona                    = $this->iZona;
      $this->clACExportaDados->x50_ordem                   = $this->iOrdem;
      $this->clACExportaDados->x50_responsavel             = $this->sResponsavel;
      $this->clACExportaDados->x50_nomelogradouro          = $this->sNomeLogradouro;
      $this->clACExportaDados->x50_numero                  = $this->iNumero;
      $this->clACExportaDados->x50_letra                   = $this->cLetra;
      $this->clACExportaDados->x50_complemento             = $this->sComplemento;
      $this->clACExportaDados->x50_nomebairro              = $this->sNomeBairro;
      $this->clACExportaDados->x50_cidade                  = $this->sCidade;
      $this->clACExportaDados->x50_estado                  = $this->sEstado;
      $this->clACExportaDados->x50_quadra                  = $this->iQuadra;
      $this->clACExportaDados->x50_economias               = $this->iEconomias;
      $this->clACExportaDados->x50_areaconstruida          = $this->fAreaContruida;
      $this->clACExportaDados->x50_numpre                  = $this->iNumPre;
      $this->clACExportaDados->x50_natureza                = $this->sNatureza;
      $this->clACExportaDados->x50_categorias              = $this->sCategoria;
      $this->clACExportaDados->x50_codhidrometro           = $this->iCodHidrometro;
      $this->clACExportaDados->x50_nrohidro                = $this->iNroHidrometro;
      $this->clACExportaDados->x50_consumomaximo           = $this->fConsumoMaximo;
      $this->clACExportaDados->x50_consumopadrao           = $this->fConsumoPadrao;
      $this->clACExportaDados->x50_vencimento              = $this->dDtVencimento;
      $this->clACExportaDados->x50_imprimeconta            = $this->iImprimeConta;
      $this->clACExportaDados->x50_dtleituraanterior       = $this->dDtLeituraAnterior;
      $this->clACExportaDados->x50_dtleituraanterior       = $this->dDtLeituraAnterior;
      $this->clACExportaDados->x50_avisoleiturista         = $this->sAvisoLeiturista;
    
      $this->clACExportaDados->incluir(null);
    
      if ($this->clACExportaDados->erro_status == "0") {
        
      	$this->iErroStatus = 0;
        $this->sErroMsg    = "Inclusão na tabela aguacoletorexportadados não efetuada. Operação abortada. ERRO: {$this->clACExportaDados->erro_msg}";
        return false;
    
      } else {
        return $this->clACExportaDados->x50_sequencial;
      }
    
    }
  }
  
  public function getAguaConfExcesso($iAno) {
  
    $this->iAno = $iAno;
     
    $this->sqlAguaConf = "select x18_consumoexcesso from aguaconf where x18_anousu = $this->iAno";
    
    $oConsumoExcesso = db_utils::fieldsMemory(db_query($this->sqlAguaConf) , 0);
    
    return $oConsumoExcesso->x18_consumoexcesso;
    
  }
  
  public function getCodReceitaExcesso($iCodConsumoExcesso) {
    
    require_once("classes/db_aguaconsumotipo_classe.php");
    
    $this->clAguaConsumoTipo = new cl_aguaconsumotipo();
    
    $rsAguaConsumoTipo = $this->clAguaConsumoTipo->sql_record($this->clAguaConsumoTipo->sql_query_file($iCodConsumoExcesso));
    
    $oCodConsumoTipo = db_utils::fieldsMemory($rsAguaConsumoTipo, 0);
    
    $this->iCodConsumoTipo = $oCodConsumoTipo->x25_receit;
    
    $this->sDescrConsumo   = $oCodConsumoTipo->x25_descr;
    
    return $this->iCodConsumoTipo;

  }
  
  public function getNumpreExcesso($iMatricula, $iAno, $iMes) {
    require_once("classes/db_aguacalc_classe.php");
    $this->clAguaCalc = new cl_aguacalc();
    
    $this->iMatricula = $iMatricula;
    $this->iAno       = $iAno;
    $this->iMes       = $iMes;
    
    $rsAguaCalc = $this->clAguaCalc->sql_record($this->clAguaCalc->sql_query_file(null, "x22_numpre", null, "x22_mes = $this->iMes and x22_exerc = $this->iAno and x22_matric = $this->iMatricula"));
    
    if($this->clAguaCalc->numrows > 0) {
      
      $oAguaCalc = db_utils::fieldsMemory($rsAguaCalc, 0);
      
      return $oAguaCalc->x22_numpre;
      
    } else {
      
      $this->iErroStatus = 0;
      $this->SErroMsg    = "Numpre do mês e ano de referência não encotrado na tabela aguacalc";
      return false;
      
    }
  }
  
  public function statusMesMatricula($iAno, $iMes, $iMatricula) {
    
    $this->iAno       = $iAno;
    $this->iMes       = $iMes;
    $this->iMatricula = $iMatricula;
    
    $this->sSqlStatusMes = "
    select count(*) 
      from aguacoletorexportadadosleitura
     inner join agualeitura              on  x21_codleitura = x51_agualeitura
     inner join aguacoletorexportadados  on  x50_sequencial = x51_aguacoletorexportadados
     inner join aguacoletorexporta       on  x49_sequencial = x50_aguacoletorexporta
     where x49_anousu = $this->iAno 
       and x49_mesusu = $this->iMes 
       and x50_matric = $this->iMatricula
       and x21_tipo   = 3
       and x21_status = 1";
    
    $rsSqlStatusMes = db_query($this->sSqlStatusMes);
    
    if(pg_num_rows($rsSqlStatusMes) > 0) {
      
      $oStatusMesMatricula = db_utils::fieldsMemory($rsSqlStatusMes, 0);
      
      return $oStatusMesMatricula->count;
      
    }
    
  }
  
  /**
   * Retorna se existe alguma importação pendente da matricula informada
   * @param unknown $iMatricula
   * 
   * @return boolean
   */
  public function getImportacaoPendente($iMatricula) {
  
    $lStatusMesMatricula = false;
  
    $sSql  = " select count(*)                                                                             ";
    $sSql .= "   from aguacoletorexportadadosleitura                                                       ";
    $sSql .= "        inner join agualeitura              on  x21_codleitura = x51_agualeitura             ";
    $sSql .= "        inner join aguacoletorexportadados  on  x50_sequencial = x51_aguacoletorexportadados ";
    $sSql .= "        inner join aguacoletorexporta       on  x49_sequencial = x50_aguacoletorexporta      ";
    $sSql .= "  where x50_matric = {$iMatricula}                                                           ";
    $sSql .= "    and x21_tipo   = 2                                                                       ";
    $sSql .= "    and x21_status = 2                                                                       ";
  
    $rsSqlStatus = db_query($sSql);
  
    if (pg_num_rows($rsSqlStatus) > 0) {
  
      $lStatusMesMatricula = (db_utils::fieldsMemory($rsSqlStatus, 0)->count > 0 ? true : false);
    }
    
    return $lStatusMesMatricula;
  
  }
  
}

?>