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
 * Classe responsavel pelo arquivo consignet
 * @author  Renan Melo <renan@dbseller.com.br>
 * @package Pessoal
 */
class GeracaoArquivoConsignet{

  private $iAnoUsu;

  private $iMesUsu;

  private $sNomeArquivo;

  private $oHandle;

  const I_CODIGO_LAYOUT = 223;

  const MENSAGEM = 'recursoshumanos.pessoal.GeracaoArquivoConsignado.';

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
    $this->sCaminhoArquivo = "tmp/consignet_margens_{$this->iAnoUsu}_{$this->iMesUsu}.txt";
  }

  /**
   * Função responsável por gerar o Arquivo contendo as MArgens de todos os servidores.
   * @return mixed -Se retornar algum servidor retorna o caminho do arquivo
   *               -Se retornar nenhum servidor retorna False
   */
  public function gerarArquivoMargem() {

    $oCompetencia      = new DBCompetencia($this->iAnoUsu, $this->iMesUsu);
    $oCompetenciaAtual = new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha());

    if ($oCompetencia->comparar($oCompetenciaAtual, DBCompetencia::COMPARACAO_MAIOR)){
      return 'competencia_informada_ultrapassada';
    }

    $rsMargens     = $this->getMargens();
    $iTotalMargens = pg_num_rows($rsMargens);

    $this->validaSalarioCalculado();

    if ($iTotalMargens == 0) {
      return false;
    }

    $sArquivo        = "tmp/consignet_margens_{$this->iAnoUsu}_{$this->iMesUsu}.txt";
    $oLayoutArquivo  = new db_layouttxt(self::I_CODIGO_LAYOUT, $sArquivo, "");

    if (!is_writable($sArquivo)) {
      throw new Exception("Não é possível gravar o arquivo de retorno");
    }

    for ($iMargens = 0; $iMargens < $iTotalMargens; $iMargens++ ) {

      $oMargem = db_utils::fieldsMemory($rsMargens, $iMargens);

      try {
        $oServidor = ServidorRepository::getInstanciaByCodigo($oMargem->rh02_regist,
                                                              $this->iAnoUsu,
                                                              $this->iMesUsu,
                                                              $oMargem->codigo_instituicao
                                                              );
      } catch ( BusinessException $eException ) {
        $oServidor = new Servidor($oMargem->rh02_regist, $this->iAnoUsu, $this->iMesUsu, $oMargem->codigo_instituicao);
      }

      $mMargem = $oServidor->getMargemConsignavel();

      $oMargem->margem = $mMargem;
      if ( $mMargem === false ){
        $oMargem->margem = 0;
      }

      $oMargem->margem = ProcessamentoArquivoConsignet::formatarCampo(6 , $oMargem->margem, 10);

      $this->escreveLinha($oMargem, $oLayoutArquivo);
    }

    $oLayoutArquivo->fechaArquivo();

    return $this->sCaminhoArquivo;
  }

  /**
   * Realiza consulta das margens de todos os Servidores
   * @return Resource recordset da RhPessoal
   */
  private function getMargens() {

    $oDaoRhPessoal = db_utils::getDao('rhpessoal');
    $sSqlRhPessoal = $oDaoRhPessoal->sql_queryServidoresConsignar($this->iAnoUsu, $this->iMesUsu, db_getsession('DB_instit'), false);
    $rsRhPessoal   = db_query($sSqlRhPessoal);

    return $rsRhPessoal;
  }

  /**
   * Função responsável por escrever a linha no arquivo.
   * @param  object $oMargem Dados de margem do servidor.
   */
  private function escreveLinha($oMargem, db_layouttxt $oLayout){

    $sDataNascimento = $oMargem->z01_nasc;
    $sDataAdmissao   = $oMargem->rh01_admiss;
    $sDataRescisao   = $oMargem->rh05_recis;
    
    $sMargem         = $oMargem->margem;

    if (!empty($oMargem->margem_rescisao)) {
      $sMargem = $oMargem->margem_rescisao;
    }

    $sLinha                 = new stdClass();

    $sLinha->matricula      = str_pad($oMargem->rh02_regist, 10, 0, STR_PAD_LEFT);   // Matricula 10 caracteres
    $sLinha->cpf            = str_pad($oMargem->z01_cgccpf,  11, 0, STR_PAD_LEFT);   // CPF 11 caracteres
    $sLinha->nome_servidor  = $oMargem->z01_nome;                                    // Nome Servidor 40 caracteres
    $sLinha->nome_instit    = $oMargem->nomeinst;                                    // Instituicao Servidor 80 caracteres
    $sLinha->nome_orgao     = $oMargem->o40_descr;                                   // Orgão Servidor 50 caracteres
    $sLinha->margem_valor   = str_pad($sMargem, 10, 0, STR_PAD_LEFT);                // Margem 10 Caracteres
    $sLinha->data_nasc      = str_replace('/', '', $sDataNascimento);                // Data Nascimento 8 caracteres
    $sLinha->data_adm       = str_replace('/', '', $sDataAdmissao);                  // Data Admissao 8 caracteres
    $sLinha->data_recis     = str_replace('/', '', $sDataRescisao);                  // Data Rescisao 8 caracteres  
    $sLinha->vinculo        = $oMargem->rh30_descr;                                  // Vínculo do servidor com o orgão
    $sLinha->lota           = $oMargem->r70_descr;                                   // Lotacao 50 caracteres
    $sLinha->identidade     = str_pad($oMargem->z01_ident, 15 , '0', STR_PAD_LEFT);  // Identidade do servidor
    if(strlen($oMargem->z01_ident) > 15){
      $sLinha->identidade   = str_pad(substr($oMargem->z01_ident, strlen($oMargem->z01_ident)-15, 15) , 15 , '0', STR_PAD_LEFT);   // Identidade do servidor
    }

    $oLayout->setByLineOfDBUtils($sLinha, 3);
  }

  /**
   * Valida se possui rubricas configuradas e
   * se possui calculo nesta competência
   * @return boolean
   */
  private function validaSalarioCalculado(){

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