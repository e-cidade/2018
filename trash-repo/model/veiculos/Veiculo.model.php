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


/**
 * Controle de Veiculos utilizados pelo municipio
 * Controla manuten��es, retiradas, e a vida do veiculo dentro da administra��o
 * @package Veiculos
 */
class Veiculo {

  /**
   * Codigo de controle do veiculo
   * @var integer
   */
  protected $iCodigo;

  /**
   * Marca do veiculo
   * @var MarcaVeiculo
   */
  protected $oMarca;

  /**
   * Codigo da marca
   * @var integer
   */
  protected $iCodigoMarca = null;

  /**
   * Modelo do ve�culo
   * @var string
   */
  protected $iModelo;

  /**
   * M�todo construtor da classe
   */
  function __construct($iVeiculo = '') {

    if (!empty($iVeiculo) && DBNumber::isInteger($iVeiculo)) {

      $oDaoVeiculo   = new cl_veiculos();
      $sSqlVeiculos  = $oDaoVeiculo->sql_query_file($iVeiculo);
      $rsVeiculos    = $oDaoVeiculo->sql_record($sSqlVeiculos);
      if ($oDaoVeiculo->numrows > 0) {

        $oDadosVeiculo      = db_utils::fieldsMemory($rsVeiculos, 0);
        $this->iCodigo      = $iVeiculo;
        $this->iCodigoMarca = $oDadosVeiculo->ve01_veiccadmarca;
        $this->iModelo      = $oDadosVeiculo->ve01_veiccadmodelo;
      }
    }
  }

  /**
   * Retiradas do Veiculo
   * retorna as Retiradas realizadas pelo Veiculo
   * @return array
   */
  public function getRetidadas() {

    return $aRetiradas;
  }

  /**
   * Retorna os abastecimentos realizados para o veiculo;
   * @return Array com os Abastecimentos Realizados
   */
  public function getAbastecimentos() {

    $aAbastecimentos        = array();
    $oDaoAbastecimento      = new cl_veicabast;
    $sWhere                 = "ve70_veiculos = {$this->iCodigo}";
    $sSqlQueryAbastecimento = $oDaoAbastecimento->sql_query_file(null, "ve07_codigo", "ve70_dtabast", $sWhere);
    $rsAbastecimento        = $oDaoAbastecimento->sql_record($sSqlQueryAbastecimento);
    for ($i = 0; $i < $oDaoAbastecimento->numrows; $i++) {

      $oAbastecimento    = new AbastecimentoVeiculo(db_utils::fieldsMemory($rsAbastecimento, $i)->ve07_codigo);
      $aAbastecimentos[] = $oAbastecimento;
    }
    return $aAbastecimentos;
  }

  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Cancela Manuten��o da Medida de Uso
   *
   * Cancela a medida de uso de um ve�culo
   *
   * @param integer $iManutencao
   * @param string $sMotivo
   * @return boolean
   */
  public function cancelaManutencaoMedidaDeUso($iManutencao, $sMotivo) {

    if (!db_utils::inTransaction()) {
      throw new Exception("N�o encontramos transa��o com o banco de dados.");
    }

  	$oManutencaoMedida  = db_utils::getDao("veicmanutencaomedida");
  	$sWhereManutencao   = "(to_timestamp(ve70_data || ' ' || ve70_hora, 'YYYY-MM-DD HH24:MI:SS') >  ";
  	$sWhereManutencao  .= " to_timestamp(ve66_data || ' ' || ve66_hora, 'YYYY-MM-DD HH24:MI:SS') or ";
  	$sWhereManutencao  .= " to_timestamp(ve62_data || ' ' || ve62_hora, 'YYYY-MM-DD HH24:MI:SS') >  ";
  	$sWhereManutencao  .= " to_timestamp(ve66_data || ' ' || ve66_hora, 'YYYY-MM-DD HH24:MI:SS') or ";
  	$sWhereManutencao  .= " to_timestamp(ve60_data || ' ' || ve60_hora, 'YYYY-MM-DD HH24:MI:SS') >  ";
  	$sWhereManutencao  .= " to_timestamp(ve66_data || ' ' || ve66_hora, 'YYYY-MM-DD HH24:MI:SS') )  ";
  	$sWhereManutencao  .= "and veiculos.ve01_codigo                 = {$this->iCodigo}             ";
  	$sWhereManutencao  .= "and veicmanutencaomedida.ve66_sequencial = {$iManutencao}               ";

  	$sSqlBuscaManutencoes = $oManutencaoMedida->sql_query_movimentos('', "veiculos.*", null, $sWhereManutencao);
  	$rsBuscaManutencao    = $oManutencaoMedida->sql_record($sSqlBuscaManutencoes);

  	if ( $oManutencaoMedida->numrows > 0 ) {
  	  throw new Exception("Existem movimenta��es para o ve�culo selecionado.");
  	}

  	$oManutencaoMedida->ve66_sequencial = $iManutencao;
  	$oManutencaoMedida->ve66_ativo      = "false";
  	$oManutencaoMedida->alterar($iManutencao);
  	if ($oManutencaoMedida->erro_status == "0") {
  	  throw new Exception("N�o foi poss�vel alterar o status da manuten��o.");
  	}

  	$oManutencaoMedidaCancela = db_utils::getDao("veicmanutencaomedidacancela");
    $oManutencaoMedidaCancela->ve67_veicmanutencaomedida = $iManutencao;
    $oManutencaoMedidaCancela->ve67_usuario              = db_getsession('DB_id_usuario');
    $oManutencaoMedidaCancela->ve67_motivo               = $sMotivo;
    $oManutencaoMedidaCancela->ve67_data                 = date("Y-m-d", db_getsession("DB_datausu"));
    $oManutencaoMedidaCancela->ve67_hora                 = date("H:i");
    $oManutencaoMedidaCancela->incluir(null);

    if ($oManutencaoMedidaCancela->erro_status == "0") {
      throw new Exception($oManutencaoMedidaCancela->erro_msg);
    }

    return true;
  }

  /**
   * Retorna a ultima medida de uso do veiculo at� a data/Hora
   * @param string $sData Data da medida no formato YYYY-mm-dd
   * @param string $sHora hora da medida no formado HH:MM
   * @return integer com a medida em horas/Km
   */
  public function getUltimaMedidaUso($sData = '', $sHora = '')  {

  	/*
  	 * caso nao seja setado a data e hora pelo usuario, o sistema buscava a data e hora da se��o,
  	* o que ocorria problema, pois as vezes o veiculo estava devolvido ex:
  	* 18/04/2013 10:00 , e o usuario entraria para proxima devolu��o
  	* 18/04/2013 9:45  o sistema buscava a medida errada.
  	* @todo validar possivel refatoramento no sql para desconsiderar data e hora e buscar diferente a ultima medida.
  	*/
    if (empty($sData)) {
      //$sData = date('Y-m-d', db_getsession("DB_datausu"));
      $sData = "3000-12-31";
    }

    if (empty($sHora)) {
      //$sHora = db_hora();
    	$sHora = "24:59";
    }
    $oDaoVeiculo           = db_utils::getDao("veiculos");
    $nUltimaMedida         = 0;
    $oDaoManutencaoMedida  = db_utils::getDao("veicmanutencaomedida");
    $sWhere                = "ve66_veiculo = {$this->iCodigo} and ve66_ativo is true";
    $sWhere               .= " and to_timestamp((ve66_data||' '||ve66_hora)::text, 'YYYY-MM-DD HH24:MI') <= ";
    $sWhere               .= " to_timestamp(('{$sData} {$sHora}')::text, 'YYYY-MM-DD HH24:MI')";
    $sOrder                = " to_timestamp((ve66_data||' '||ve66_hora)::text, 'YYYY-MM-DD HH24:MI') desc limit 1";
    $sSqlReinicioMedida    = $oDaoManutencaoMedida->sql_query_file(null,'ve66_data, ve66_hora', $sOrder, $sWhere);
    $rsReinicioMedida      = $oDaoManutencaoMedida->sql_record($sSqlReinicioMedida);
    if ($oDaoManutencaoMedida->numrows > 0) {

      $oDadosReinicio  = db_utils::fieldsMemory($rsReinicioMedida, 0);
      $sHoraInicial    = $oDadosReinicio->ve66_hora;
      $sDataInicial    = $oDadosReinicio->ve66_data;
      $sSqlDadosMedida = $oDaoVeiculo->sql_query_medida_between($this->iCodigo, $sDataInicial, $sHoraInicial, $sData, $sHora);
    } else {
      $sSqlDadosMedida = $oDaoVeiculo->sql_query_ultimamedida($this->iCodigo, $sData, $sHora);
    }

    /**
     * Verificamos a ultima medida do veiculo, seja ela em retiradas, abastecimentos, ou Manuten��o.
     */
    $rsMedidaUsu = $oDaoVeiculo->sql_record($sSqlDadosMedida);
    if ($oDaoVeiculo->numrows > 0) {
      $nUltimaMedida = db_utils::fieldsMemory($rsMedidaUsu, 0)->ultimamedida;
    }
    return $nUltimaMedida;
  }

  /**
   * Retorna a marca do veiculo
   * @return MarcaVeiculo
   */
  public function getMarca() {

    if (empty($this->oMarca) && !empty($this->iCodigoMarca)) {
      $this->oMarca = new MarcaVeiculo($this->iCodigoMarca);
    }
    return $this->oMarca;
  }

  /**
   * Retorna o modelo do ve�culo
   * @return string
   */
  public function getModelo() {

    $oModelo    = new cl_veiccadmodelo();
    $sSqlModelo = $oModelo->sql_query_file($this->iModelo, 've22_descr');
    $rsModelo   = $oModelo->sql_record($sSqlModelo);

    return db_utils::fieldsMemory($rsModelo, 0)->ve22_descr;
  }
}

?>