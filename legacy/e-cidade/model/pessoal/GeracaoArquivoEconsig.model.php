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


/**
 * Classe responsavel pelo arquivo E-consig
 * @author  Renan Melo <renan@dbseller.com.br>
 * @package Pessoal
 */
class GeracaoArquivoEconsig{

  private $iAnoUsu;

  private $iMesUsu;

  private $sNomeArquivo;

  private $oHandle;

  const MENSAGEM = 'recursoshumanos.pessoal.GeracaoArquivoEconsig.';

  /**
   * Construtor da classe.
   * @param integer $iAnoUsu Ano Competência
   * @param integer $iMesUsu Mês Competência
   */
  function __construct($iAnoUsu, $iMesUsu){

    if (empty($iAnoUsu)) {
      throw new BusinessException(_M(self::MENSAGEM . 'informe_ano'));
    }

    if (empty($iMesUsu)) {
      throw new BusinessException(_M(self::MENSAGEM . 'informe_mes'));
    }

    $this->iAnoUsu         = $iAnoUsu;
    $this->iMesUsu         = $iMesUsu;
    $this->sCaminhoArquivo = 'tmp/econsig_' . $iAnoUsu . '_' . $iMesUsu . '.txt';
  }

  /**
   * Função responsável por gerar o Arquivo contendo as MArgens de todos os servidores.
   * @return mixed -Se retornar algum servidor retorna o caminho do arquivo
   *               -Se retornar nenhum servidor retorna False
   */
  public function gerarArquivoMargem() {

    $rsMargens     = $this->getMargens();
    $iTotalMargens = pg_num_rows($rsMargens);

    $this->validaMargens();

    if ($iTotalMargens == 0) {
      return false;
    }

    $this->oHandle = fopen($this->sCaminhoArquivo, 'w');

    for ($iMargens = 0; $iMargens < $iTotalMargens; $iMargens++ ) {

      $oMargem = db_utils::fieldsMemory($rsMargens, $iMargens);
      $this->escreveLinha($oMargem);
    }

    return $this->sCaminhoArquivo;
  }

  /**
   * Realiza consulta das margens de todos os Servidores
   * @return Resource recordset da RhPessoal
   */
  private function getMargens() {

    $oDaoRhPessoal = db_utils::getDao('rhpessoal');
    $sSqlRhPessoal = $oDaoRhPessoal->sql_queryMargemEConsig($this->iAnoUsu, $this->iMesUsu, db_getsession('DB_instit'));
    $rsRhPessoal   = db_query($sSqlRhPessoal);

    return $rsRhPessoal;
  }

  /**
   * Função responsável por escrever a linha no arquivo.
   * @param  object $oMargem Dados de margem do servidor.
   */
  private function escreveLinha($oMargem){

    $sDataNascimento = db_formatar($oMargem->z01_nasc   , 'd');
    $sDataAdmissao   = db_formatar($oMargem->rh01_admiss, 'd');
    $sDataRescisao   = db_formatar($oMargem->rh05_recis , 'd');
    $sMargem         = $oMargem->margem;

    if (!empty($oMargem->margem_rescisao)) {
      $sMargem = $oMargem->margem_rescisao;
    }

    $sLinha  = str_pad($oMargem->rh02_regist, 10, 0, STR_PAD_LEFT);                    // Matricula 10 caracteres
    $sLinha .= str_pad($oMargem->z01_cgccpf,  11, ' ', STR_PAD_LEFT);                    // CPF 11 caracteres
    $sLinha .= str_pad($oMargem->z01_nome   , 40, ' ', STR_PAD_RIGHT);                 // Nome Servidor 40 caracteres
    $sLinha .= str_pad(db_getsession('DB_instit'), 3, 0, STR_PAD_LEFT);                // Código da instituição 3 caracteres
    $sLinha .= str_pad($oMargem->nomeinst   , 80, ' ', STR_PAD_RIGHT);                 // Instituicao Servidor 80 caracteres
    $sLinha .= str_pad($oMargem->o40_descr  , 50, ' ', STR_PAD_RIGHT);                 // Orgão Servidor 50 caracteres
    $sLinha .= str_pad($sMargem             , 10, 0  , STR_PAD_LEFT);                  // Margem 10 Caracteres
    $sLinha .= str_pad(str_replace('/', '', $sDataNascimento), 8, ' ', STR_PAD_LEFT);  // Data NAscimento 8 caracteres
    $sLinha .= str_pad(str_replace('/', '', $sDataAdmissao  ), 8, ' ', STR_PAD_LEFT);  // Data Admissao 8 caracteres
    $sLinha .= str_pad(str_replace('/', '', $sDataRescisao  ), 8, ' ', STR_PAD_LEFT);  // Data Rescisao 8 caracteres
    $sLinha .= str_pad($oMargem->rh30_descr , 40 , ' ', STR_PAD_RIGHT);                // Regime de traalho 40 caracteres
    $sLinha .= str_pad($oMargem->r70_descr  , 50, ' ' , STR_PAD_RIGHT);                // Lotacao 50 caracteres
    $sLinha .= str_pad($oMargem->z01_ident  , 25, 0   , STR_PAD_LEFT);                 // RG 15 Caracteres
    $sLinha .= str_pad($oMargem->afastamento, 40, ' ' , STR_PAD_RIGHT);                // Afastamento 40 caracteres
    $sLinha .= "\n" ;

    fputs($this->oHandle, $sLinha);
  }

  /**
   * Valida se possui rubricas configuradas e
   * se possui calculo nesta competência
   * @return boolean
   */
  private function validaMargens(){

    /*
     * Verifica se as Bases estão configuradas.
     */
    $oDaoBasesr = db_utils::getDao('basesr');
    $sWhere     = "r09_anousu = $this->iAnoUsu and r09_mesusu = $this->iMesUsu and r09_base in('B105','B106')";
    $sSqlBasesr = $oDaoBasesr->sql_query_file(null,null,null,null,null, '*', null, $sWhere);
    $rsBases    = db_query($sSqlBasesr);

    if (pg_num_rows($rsBases) == 0) {
      throw new BusinessException(_M(self::MENSAGEM . "erro_bases"));
    }

    /**
     * Verifica se possui ponto de salario calculado
     * para a competência informada
     */
    $oDaoGerfSal = db_utils::getDao('gerfsal');
    $sSqlGerfSal = $oDaoGerfSal->sql_query_file($this->iAnoUsu, $this->iMesUsu);
    $rsGerfSal   = pg_query($sSqlGerfSal);

    if (pg_num_rows($rsGerfSal) == 0) {

      $aCompetencia = array("iAno" => $this->iAnoUsu, "iMes" => $this->iMesUsu);
      throw new BusinessException(_M(self::MENSAGEM . "erro_calculo", (object) $aCompetencia));
    }

    return true;
  }
}

?>