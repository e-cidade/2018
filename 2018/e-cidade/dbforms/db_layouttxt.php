<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

class db_layouttxt {

	/**
	 * Constantes para controle da posição de quebra automática de linha
	 */
	const QUEBRA_AUTOMATICA_ANTES = 1;
	const QUEBRA_AUTOMATICA_DEPOIS = 2;

	const TIPO_LINHA_HEADER_ARQUIVO   = 1;
	const TIPO_LINHA_REGISTRO         = 3;
	const TIPO_LINHA_TRAILLER_ARQUIVO = 5;

	// Propriedades
	public $_arquivo;
	public $_formatoArquivo; // 0 - Unix   1 - DOS
	public $_layout;
	public $_usartipo;

	public $_quantLinhasLay;
	public $_brancosLinhasLay;

	public $_codigoLinha;
	public $_tipoLinha;
	public $_tamLinha;
	public $_quebrasAntes;
	public $_quebrasDepois;
	public $_separador;
	public $_compactaLinha;

	public $_codigoCampo;
	public $_nomeCampo;
	public $_posicaoCampo;
	public $_defaultCampo;
	public $_tamanhoCampo;
	public $_identCampo;
	public $_mascaraCampo;
	public $_tipoCampo;
	public $_tamanhoFormatCampo;
	public $_decimaisCampo;
	public $_caracDecCampo;
	public $_imprimirCampo;
	public $_alinhaCampo;
	public $_varquebraLinha;

	public $_arr_campos_nome;
	public $_arr_campos_val;
	public $_campo_tipolinha;
	public $_campo_identlinha;

	public $_erro_gera;
	public $_compara_linha;
	public $_lUsaChar;

  /**
   * Controle do output em memória
   */
  public $sMemoryOut;
  public $iMemoryLimit;
  public $iMemoryCount;
  public $lOutputMemory = false;

  public function enableOutputMemory($iMemoryLimit = 500) {

    $this->lOutputMemory = true;
    $this->iMemoryLimit = $iMemoryLimit;
    $this->iMemoryCount = 0;
    $this->sMemoryOut = '';
  }

  private function output($sContent) {

    if ($this->lOutputMemory) {

      $this->sMemoryOut .= $sContent;

      if ($this->iMemoryCount >= $this->iMemoryLimit) {
        $this->flushMemory();
      }
      return true;
    }

    $this->adicionaLinha($sContent);
  }

  private function flushMemory() {
    $this->adicionaLinha($this->sMemoryOut);
    $this->sMemoryOut = '';
    $this->iMemoryCount = 0;
  }

  /**
   * Controla o posicionamento da quebra automática de linha
   * @var integer
   */
	private $iPosicionamentoQuebraAutomatica = self::QUEBRA_AUTOMATICA_DEPOIS;

	/**
	 * Controla se deve adicionar quebra de linha ao adicionar linhas
	 * @var boolean
	 */
	private $lQuebraAutomatica = true;

	function db_layouttxt($layout = 0, $arquivo = "", $usartipo = "", $formatoArquivo = 1, $lUsaChar = false) {

	  $this->_lUsaChar = $lUsaChar;
		$this->setFormatoArquivo ( $formatoArquivo );
		$this->setUsarTipo ( $usartipo );
		if ($layout != 0) {
			if ($arquivo == "") {
				$arquivo = "/tmp/arqsistema" . date ( "Ymd" ) . ".txt";
			}
			$this->setLayout ( $layout );
			$this->abreArquivo ( $arquivo );
			$this->carregaLayout ();
			$this->setCampoIdentLinha ( "" );
		}
	}

	public function ocorreuErro() {
		return $this->_erro_gera !== null;
	}

	public function getMensagemErro() {
		return $this->_erro_gera;
	}

	/**
	 * Habilita a quebra automatica de linha ao adicionar linhas
	 */
	public function habilitarQuebraAutomatica() {
		$this->lQuebraAutomatica = true;
	}

	/**
	 * Desabilita a quebra automatica de linha ao adicionar linhas
	 */
	public function desabilitarQuebraAutomatica() {
		$this->lQuebraAutomatica = false;
	}

	/**
	 * Seta o posicionamento da quebra automática de linha
	 * @param integer $iPosicionamento
	 */
	public function setPosicionamentoQuebraAutomatica($iPosicionamento) {
		$this->iPosicionamentoQuebraAutomatica = $iPosicionamento;
	}

	function setLayout($layout) {
		$this->_layout = $layout;
	}

	function getLayout() {
		return $this->_layout;
	}

	function setUsarTipo($usartipo) {
		$this->_usartipo = $usartipo;
	}

	function getUsarTipo() {
		return $this->_usartipo;
	}

	function setArrCamposNome($_arr_campos_nome) {
		$this->_arr_campos_nome = $_arr_campos_nome;
	}

	function setArrCamposNomeIn($_index, $_campo) {
		$this->_arr_campos_nome [$_index] = $_campo;
	}

	function getArrCamposNome() {
		return $this->_arr_campos_nome;
	}

	function setArrCamposVal($_arr_campos_val) {
		$this->_arr_campos_val = $_arr_campos_val;
	}

	function setArrCamposValIn($_campo, $_valor) {

	  $this->_arr_campos_val [$this->arrIndexaCampos [$_campo]] = "$_valor";
	}

	function getArrCamposVal() {
		return $this->_arr_campos_val;
	}

	function getArrCamposValIn($_index) {
		if (isset ( $this->_arr_campos_val [$_index] )) {
			return $this->_arr_campos_val [$_index];
		} else {
			return false;
		}
	}

	function setCampoTipoLinha($_campo_tipolinha) {
		$this->_campo_tipolinha = $_campo_tipolinha;
	}

	function getCampoTipoLinha() {
		return $this->_campo_tipolinha;
	}

	function setCampoIdentLinha($_campo_identlinha) {
		$this->_campo_identlinha = $_campo_identlinha;
	}

	function getSeparador() {
	  if ( $this->_lUsaChar ) {
	    eval('$sSeparador = '.$this->_separador.';');
	  } else {
	    $sSeparador = $this->_separador;
	  }
		return $sSeparador;
	}

	function getCompactaLinha() {
		return $this->_compactaLinha;
	}

	function getCampoIdentLinha() {
		return $this->_campo_identlinha;
	}

	function abreArquivo($arquivo) {
		$this->_arquivo = fopen ( $arquivo, "w" );
	}

	function fechaArquivo() {

    if ($this->lOutputMemory) {
      $this->flushMemory();
    }

		fclose ( $this->_arquivo );
	}

	function adicionaLinha($valor) {
		fputs ( $this->_arquivo, $valor );
	}

  private function getQuebraLinha($iQuantidade = 1) {

    $quebra = $this->getFormatoArquivo() == 0 ? "\r" : "\r\n";
    return str_repeat($quebra, $iQuantidade);
  }

	function quebraLinha($quebras = 1) {
		$quebra = $this->getFormatoArquivo () == 0 ? "\r" : "\r\n";
		for($i = 0; $i < $quebras; $i ++) {
			$this->adicionaLinha ( $quebra );
		}
	}

	function setFormatoArquivo($formato) {
		$arrFormatos = array (0, 1 );
		if (! in_array ( $formato, $arrFormatos )) {
			$this->_formatoArquivo = 1;
		} else {
			$this->_formatoArquivo = $formato;
		}
	}

	function getFormatoArquivo() {
		return $this->_formatoArquivo;
	}

	var $_layoutMemoria;
	function carregaLayoutMemoria() {

		// Busca Layout
		$sql = "select * from db_layouttxt where db50_codigo = " . $this->getLayout ();

		$this->_layoutMemoria = db_query ( $sql );

		if ($this->_layoutMemoria == false || pg_num_rows ( $this->_layoutMemoria ) == 0) {
			$this->_erro_gera = "ERRO: Layout não encontrado.";
			$this->fechaArquivo ();
			return false;
		}

		$this->_quantLinhasLay = pg_result ( $this->_layoutMemoria, 0, "db50_quantlinhas" );

	}

	function liberaLinhas() {
		unset ( $this->_codigoLinha );
		unset ( $this->_tipoLinha );
		unset ( $this->_tamLinha );
		unset ( $this->_quebrasAntes );
		unset ( $this->_quebrasDepois );
		unset ( $this->_separador );
		unset ( $this->_compactaLinha );
	}

	function liberaCampos() {
		unset ( $this->_codigoCampo );
		unset ( $this->_nomeCampo );
		unset ( $this->_posicaoCampo );
		unset ( $this->_defaultCampo );
		unset ( $this->_tamanhoCampo );
		unset ( $this->_identCampo );
		unset ( $this->_mascaraCampo );
		unset ( $this->_tipoCampo );
		unset ( $this->_tamanhoFormatCampo );
		unset ( $this->_decimaisCampo );
		unset ( $this->_caracDecCampo );
		unset ( $this->_imprimirCampo );
		unset ( $this->_alinhaCampo );
		unset ( $this->_varquebraLinha );
		unset ( $this->_arr_campos_val );
	}

	function limpaCampos() {
		$this->setArrCamposVal ( Array () );
	}

	var $_linhasMemoria;
	var $_arrLinhasMemoria;
	var $_arrDadosLinhasMemoria;
	function carregaLinhasMemoria() {

		// Busca Linhas
		$sql = "
      select db_layoutlinha.*
      from db_layouttxt
      inner join db_layoutlinha on db51_layouttxt = db50_codigo
      where db50_codigo = " . $this->getLayout () . "
      order by db51_tipolinha
      ";

		$this->_linhasMemoria = db_query ( $sql );

		if ($this->_linhasMemoria == false || pg_num_rows ( $this->_linhasMemoria ) == 0) {
			$this->_erro_gera = "ERRO: Verifique o cadastro do layout, linhas não encontradas.";
			$this->fechaArquivo ();
			return false;
		}

		$qtdquebras = 0;
		for($i = 0; $i < pg_num_rows ( $this->_linhasMemoria ); $i ++) {

			$codigoLinha    = pg_result ( $this->_linhasMemoria, $i, "db51_codigo" );
			$tipoDaLinha    = pg_result ( $this->_linhasMemoria, $i, "db51_tipolinha" );
			$antesLinhas    = pg_result ( $this->_linhasMemoria, $i, "db51_linhasantes" );
			$depoisLinha    = pg_result ( $this->_linhasMemoria, $i, "db51_linhasdepois" );
			$tamanhoLinha   = pg_result ( $this->_linhasMemoria, $i, "db51_tamlinha" );
			$separadorLinha = pg_result ( $this->_linhasMemoria, $i, "db51_separador" );
			$compactaLinha  = pg_result ( $this->_linhasMemoria, $i, "db51_compacta" );

			$qtdquebras += $antesLinhas + $depoisLinha;

			if (! isset ( $this->_arrLinhasMemoria [$tipoDaLinha] )) {
				$this->_arrLinhasMemoria [$tipoDaLinha] = "";
				$virgula = "";
			} else {
				$virgula = ",";
			}

			$this->_arrLinhasMemoria [$tipoDaLinha] .= $virgula . $codigoLinha;
			$this->_arrDadosLinhasMemoria [$codigoLinha] = Array ((trim ( $tipoDaLinha ) != "" ? $tipoDaLinha : 0),
			                                                       $antesLinhas,
			                                                       $depoisLinha,
			                                                       $tamanhoLinha,
			                                                       $separadorLinha,
			                                                       $compactaLinha );
		}

		$this->_brancosLinhasLay = $qtdquebras;

	}

	var $_camposMemoria;

  private $aDadosCampos;
  private $aCamposNomes;
  private $aCamposIndex;
  private $aValresPadrao;

	function carregaCamposMemoria() {

    $this->aDadosCampos = array();
    $this->aCamposNomes = array();
    $this->aCamposIndex = array();
    $this->aValoresPadrao = array();

		for($i = 0; $i < pg_num_rows ( $this->_linhasMemoria ); $i ++) {

			$codigoLinha = pg_result ( $this->_linhasMemoria, $i, "db51_codigo" );
			// Busca Campos da Linha Corrente
			$sql = " select db_layoutcampos.*, db_layoutformat.*
                 from db_layoutcampos
                      inner join db_layoutformat on db52_layoutformat = db53_codigo
                where db52_layoutlinha = " . $codigoLinha . "
                 order by db52_posicao ";
			$this->_camposMemoria [$codigoLinha] = db_query ( $sql );

      $this->aDadosCampos[$codigoLinha] = array();
      $this->aCamposNomes[$codigoLinha] = array();
      $this->aCamposIndex[$codigoLinha] = array();
      $this->aValoresPadrao[$codigoLinha] = array();

      for ($iRow = 0; $iRow < pg_num_rows($this->_camposMemoria[$codigoLinha]); $iRow++) {
        $oDadosCampo = pg_fetch_object($this->_camposMemoria [$codigoLinha], $iRow);
        $this->aDadosCampos[$codigoLinha][$iRow] = $oDadosCampo;
        $this->aCamposNomes[$codigoLinha][$iRow] = $oDadosCampo->db52_nome;
        $this->aCamposIndex[$codigoLinha][$oDadosCampo->db52_nome] = $iRow;
        $this->aValoresPadrao[$codigoLinha][$oDadosCampo->db52_nome] = $oDadosCampo->db52_default;
      }

			if ($this->_camposMemoria [$codigoLinha] == false) {
				$this->_erro_gera = "ERRO: Problemas ao buscar campos da linha " . $codigoLinha . ".";
				$this->fechaArquivo ();
				return false;
			}

		}

	}

	var $arrIndexaCampos;
	function carregaCamposRetorno($_codigoLinha) {
		$tipolinha = $this->getCampoTipoLinha ();
		$identlinha = $this->getCampoIdentLinha ();

		$varRetornoCamposArray = Array ();
		$result_CamposDaLinha = $this->_camposMemoria [$_codigoLinha];
		for($x = 0; $x < pg_num_rows ( $result_CamposDaLinha ); $x ++) {

			$valorcampo = pg_result ( $result_CamposDaLinha, $x, "db52_nome" );
			$varRetornoNomesArray [$x] = $valorcampo;
			$this->arrIndexaCampos [$valorcampo] = $x;
			if ($this->getArrCamposValIn ( $x ) === false) {
				$this->setArrCamposValIn ( $valorcampo, "" );
			}

			}
		$this->setArrCamposNome ( $varRetornoNomesArray );
	}

	function setCampo($campo, $valor) {
		$retornoLinhas = $this->retornaDadosLinha ();
		if ($retornoLinhas == true) {

		  $this->carregaCamposRetorno ( $this->_codigoLinha );
			$this->setArrCamposValIn ($campo, $valor );
		}
	}

	var $_linhasPorTipoMemoria;
	function carregaLinhasPorTipo() {

		foreach ( $this->_arrLinhasMemoria as $indexCampos => $valorCampos ) {

			$arr_valorCampos = split ( ",", $valorCampos );

			foreach ( $arr_valorCampos as $indexValores => $valorValores ) {

				$valorDefault = "";
				$result_corrente = $this->_camposMemoria [$valorValores];

				for($i = 0; $i < pg_num_rows ( $result_corrente ); $i ++) {

					$identificador        = pg_result ( $result_corrente, $i, "db52_ident" );
					$defaultidentificador = pg_result ( $result_corrente, $i, "db52_default" );

					if ($identificador == "t" && trim ( $defaultidentificador ) != "") {
						if (trim ( $this->_usartipo ) != "" && ! (strpos ( $this->_usartipo, $defaultidentificador ) === false)) {
							$valorDefault = pg_result ( $result_corrente, $i, "db52_default" );
              break;
            }
          }
        }
        $this->_linhasPorTipoMemoria [$indexCampos] [$valorDefault] = $valorValores;
			}

		}
	}

  private $aIdentificadoresLinhas;

  private function carregarIdentificadores() {

    $this->aIdentificadoresLinhas = array();

    foreach ( $this->_arrLinhasMemoria as $indexCampos => $valorCampos ) {

			$arr_valorCampos = split ( ",", $valorCampos );

			foreach ( $arr_valorCampos as $indexValores => $valorValores ) {

        foreach($this->aDadosCampos[$valorValores] as $oLinha) {

          if ($oLinha->db52_ident == "t" && trim ( $oLinha->db52_default ) != "") {
            $this->aIdentificadoresLinhas[$indexCampos][$oLinha->db52_default] = $valorValores;
          }
        }

        $this->aIdentificadoresLinhas[$indexCampos][''] = $valorValores;
			}

		}
  }

	function carregaLayout() {

		$this->carregaLayoutMemoria ();
		$this->carregaLinhasMemoria ();
		$this->carregaCamposMemoria ();
		$this->carregaLinhasPorTipo ();
    $this->carregarIdentificadores();

		return true;
	}

	function retornaArrayNomeCampos() {

		$varRetornoCamposArray = Array ();
		for($x = 0, $indexArray = 0; $x < pg_num_rows ( $this->_resCampos ); $x ++) {
			$linhacampo = pg_result ( $this->_resCampos, $x, "db52_layoutlinha" );
			$valorcampo = pg_result ( $this->_resCampos, $x, "db52_nome" );
			if ($this->_codigoLinha == $linhacampo && ! in_array ( $valorcampo, $varRetornoCamposArray )) {
				$varRetornoCamposArray [$indexArray] = $valorcampo;
				$indexArray ++;
			}
		}

		$this->setArrCamposNome ( $varRetornoCamposArray );
		return true;
	}

	function retornaArrayVariaveisCampos() {
		return true;
	}

	function retornaValorCampo($nomecampo, $linha) {
		// Ler txt
		// Pesquisar Identificador do Campo
		$idcampo = '';
	}

	function comparaLinhas($linhaatual) {
		if (! isset ( $this->_compara_linha ) || (isset ( $this->_compara_linha ) && $this->_compara_linha != $linhaatual)) {
			$this->_compara_linha = $linhaatual;
			return true;
		} else {
			return false;
		}
	}

	function retornaDadosLinha() {

		$tipolinha  = $this->getCampoTipoLinha ();
		$identlinha = $this->getCampoIdentLinha ();

/*		echo "<pre>";
		print_r($this->_linhasPorTipoMemoria);
		echo "</pre>";
		*/
		if (isset ( $this->_linhasPorTipoMemoria [$tipolinha] [$identlinha] )) {
			$linha = $this->_linhasPorTipoMemoria [$tipolinha] [$identlinha];

			if ($this->comparaLinhas ( $linha )) {
				$this->liberaCampos ();
				$this->_codigoLinha   = $linha;
				$this->_tipolinha     = $this->_arrDadosLinhasMemoria [$linha] [0];
				$this->_quebrasAntes  = $this->_arrDadosLinhasMemoria [$linha] [1];
				$this->_quebrasDepois = $this->_arrDadosLinhasMemoria [$linha] [2];
				$this->_tamLinha      = $this->_arrDadosLinhasMemoria [$linha] [3];
				$this->_separador     = $this->_arrDadosLinhasMemoria [$linha] [4];
				$this->_compactaLinha = $this->_arrDadosLinhasMemoria [$linha] [5];
			}

			return true;
		}
		return false;
	}

	function retornaDadosCampo($campo) {

		$linha = $this->_codigoLinha;
		for($x = 0; $x < pg_num_rows ( $this->_camposMemoria [$linha] ); $x ++) {
			$nomeCampo = pg_result ( $this->_camposMemoria [$linha], $x, "db52_nome" );
			$linhaCampo = pg_result ( $this->_camposMemoria [$linha], $x, "db52_layoutlinha" );
			if ($linha == $linhaCampo && $nomeCampo == $campo) {
				$this->_nomeCampo = $nomeCampo;
				$this->_codigoCampo = pg_result ( $this->_camposMemoria [$linha], $x, "db52_codigo" );
				$this->_posicaoCampo = pg_result ( $this->_camposMemoria [$linha], $x, "db52_posicao" );
				$this->_defaultCampo = pg_result ( $this->_camposMemoria [$linha], $x, "db52_default" );
				$this->_tamanhoCampo = pg_result ( $this->_camposMemoria [$linha], $x, "db52_tamanho" );
				$this->_identCampo = pg_result ( $this->_camposMemoria [$linha], $x, "db52_ident" );
				$this->_mascaraCampo = pg_result ( $this->_camposMemoria [$linha], $x, "db53_mascara" );
				$this->_tipoCampo = pg_result ( $this->_camposMemoria [$linha], $x, "db53_tipo" );
				$this->_tamanhoFormatCampo = pg_result ( $this->_camposMemoria [$linha], $x, "db53_tamanho" );
				$this->_decimaisCampo = pg_result ( $this->_camposMemoria [$linha], $x, "db53_decimais" );
				$this->_caracDecCampo = pg_result ( $this->_camposMemoria [$linha], $x, "db53_caracdec" );
				$this->_imprimirCampo = pg_result ( $this->_camposMemoria [$linha], $x, "db52_imprimir" );
				$this->_alinhaCampo = pg_result ( $this->_camposMemoria [$linha], $x, "db52_alinha" );
				$this->_varquebraLinha = pg_result ( $this->_camposMemoria [$linha], $x, "db52_quebraapos" );
				return true;
			}
    }

      return false;
	}

	function formatarCampo($tipoFormat, $valor, $tamanho, $mascara) {
		// Implementar máscara
		switch ($tipoFormat) {
			case 1 : // Para String
				$valor = db_formatar ( substr ( strtoupper ( db_translate ( $valor ) ), 0, $tamanho ), "s", " ", $tamanho, $this->_alinhaCampo, 0 );
				break;
			case 2 : // Para Inteiro
				$valor = db_formatar ( substr ( $valor, 0, $tamanho ), "s", "0", $tamanho, $this->_alinhaCampo, 0 );
				break;
			case 3 : // Para Decimal
				$valor = db_formatar ( substr ( trim ( str_replace ( ".", "", str_replace ( ",", "", db_formatar ( $valor, "f" ) ) ) ), 0, $tamanho ), "s", "0", $tamanho, $this->_alinhaCampo, 0 );
				break;
			case 4 : // Para Data
				$arr_data ['Y'] = db_subdata ( $valor, 'a' );
				$arr_data ['y'] = substr ( db_subdata ( $valor, 'a' ), - 2 );
				$arr_data ['m'] = db_subdata ( $valor, 'm' );
				$arr_data ['d'] = db_subdata ( $valor, 'd' );

				$novoValor = "";
				for($ind = 0; $ind < strlen ( $mascara ); $ind ++) {
					$pertence = true;
					$imprimir = true;
					if (isset ( $arr_data [$mascara [$ind]] )) {
						if (isset ( $mascara [($ind - 1)] )) {
							if ($mascara [($ind - 1)] == '\\') {
								$pertence = false;
							}
						}
					} else {
						$pertence = false;
						if (isset ( $mascara [($ind - 1)] )) {
							if ($mascara [$ind] == '\\' && $mascara [($ind - 1)] != '\\') {
								$imprimir = false;
							}
						}
					}
					if ($pertence == true) {
						$novoValor .= $arr_data [$mascara [$ind]];
					} else if ($imprimir == true) {
						$novoValor .= $mascara [$ind];
					}
				}
				if ($valor == '') {
					$valor = str_repeat ( ' ', 8 );
				} else {
					$valor = $novoValor;
				}

				break;
			case 5 : // Para Hora
				$valor = db_formatar ( substr ( str_replace ( ":", "", $valor ), 0, $tamanho ), "s", "0", $tamanho, $this->_alinhaCampo, 0 );
				break;
			case 6 : // Para CEP
				$valor = db_formatar ( substr ( str_replace ( "/", "", str_replace ( "-", "", str_replace ( ".", "", $valor ) ) ), 0, $tamanho ), "s", "0", $tamanho, $this->_alinhaCampo, 0 );
				break;
			case 7 : // Para CGC / CPF
				$valor = db_formatar ( substr ( str_replace ( "/", "", str_replace ( "-", "", str_replace ( ".", "", $valor ) ) ), 0, $tamanho ), "s", "0", $tamanho, $this->_alinhaCampo, 0 );
				break;
			case 8 : // Para Executar Eval
				$valor = eval ( $valor );
				break;
			case 9 : // Para String Livre

			  $valor = db_formatar ( substr ( $valor, 0, $tamanho ), "s", " ", $tamanho, $this->_alinhaCampo, 0 );
				break;

			case 13:
          $valor = db_translate ($valor);
			  break;

			case 10:

			  /**
			   * String como é passada... nao aplicamos nenhuma formatacao
			   */
			    $valor = $valor;
			   break;
			case 16:
					$valor = $valor;
			  break;
			default:

				$valor = db_formatar ( substr ( db_translate ( $valor ), 0, $tamanho ), "s", "0", $tamanho, $this->_alinhaCampo, 0 );

				break;
		}
		return $valor;
	}

	function geraDadosLinha() {

		$tipoLinha = $this->getCampoTipoLinha ();
		$identlinha = $this->getCampoIdentLinha ();

		$arrCampos  = $this->getArrCamposNome ();
		$arrValores = $this->getArrCamposVal();
		$this->quebraLinha ( $this->_quebrasAntes );

		if ($this->lQuebraAutomatica && $this->iPosicionamentoQuebraAutomatica == self::QUEBRA_AUTOMATICA_ANTES) {
			$this->quebraLinha();
		}

		$sSeparador = "";
		foreach ( $arrCampos as $indexCampos => $valorCampos ) {

			$valorCampo = $arrValores [$indexCampos];
			$retorno = $this->retornaDadosCampo ( $valorCampos );

			if ($retorno == false) {
				return false;
			}

			if ($this->_defaultCampo != "" && ($valorCampo == "" || $valorCampo == null)) {
				$valorCampo = $this->_defaultCampo;
			}

			$tamanhoCampo = $this->_tamanhoCampo;
			if ($tamanhoCampo == 0) {
				$tamanhoCampo = $this->_tamanhoFormatCampo;
			}
			$valorCampo = $this->formatarCampo ( $this->_tipoCampo, $valorCampo, $tamanhoCampo, $this->_mascaraCampo );

			if ($this->getCompactaLinha () == "t") {
				$valorCampo = trim ( $valorCampo );
			}
			if ($this->_imprimirCampo == "t") {

				if ($this->getSeparador () != "") {
					$valorCampo = $sSeparador . $valorCampo;
					$sSeparador = $this->getSeparador();
				}


				$this->adicionaLinha ( $valorCampo );
			}

			$this->quebraLinha ( $this->_varquebraLinha );

		}

		if ($this->lQuebraAutomatica && $this->iPosicionamentoQuebraAutomatica == self::QUEBRA_AUTOMATICA_DEPOIS) {
			$this->quebraLinha();
		}

		$this->quebraLinha ( $this->_quebrasDepois );

		return true;
	}

	function carregaTxt($arquivo) {
		// Colocar mais testes (tipo se o arquivo existe...)
		if (file_exists ( $arquivo ) == true) {
			$this->_arquivo = file ( $arquivo );
		}
	}

	function setByLineOfDBUtils($oDBUtils, $tipolinha, $identlinha = "") {

		$this->setCampoTipoLinha ( $tipolinha );
		$this->setCampoIdentLinha ( $identlinha );

		$this->setUsarTipo($identlinha);
		$this->carregaLinhasPorTipo();

		$retornoLinhas = $this->retornaDadosLinha ();
		if (! $retornoLinhas) {
			return false;
		}

		$this->carregaCamposRetorno ( $this->_codigoLinha );
		$aNomeCampos = $this->getArrCamposNome ();

		foreach ( $aNomeCampos as $valorcampo ) {

			$this->setCampo ($valorcampo, $oDBUtils->$valorcampo);
		}

		$this->geraDadosLinha ();
	  return true;
	}

  public function addLine($oDBUtils, $tipolinha, $identlinha = "") {

		$this->setCampoTipoLinha($tipolinha);
		$this->setCampoIdentLinha($identlinha);

		$this->setUsarTipo($identlinha);

		if (!isset($this->aIdentificadoresLinhas[$tipolinha][$identlinha])) {
      return false;
    }

		$linha = $this->aIdentificadoresLinhas[$tipolinha][$identlinha];

		if ($this->comparaLinhas($linha)) {

			$this->liberaCampos();

			$this->_codigoLinha   = $linha;
			$this->_tipolinha     = $this->_arrDadosLinhasMemoria[$linha][0];
			$this->_quebrasAntes  = $this->_arrDadosLinhasMemoria[$linha][1];
			$this->_quebrasDepois = $this->_arrDadosLinhasMemoria[$linha][2];
			$this->_tamLinha      = $this->_arrDadosLinhasMemoria[$linha][3];
			$this->_separador     = $this->_arrDadosLinhasMemoria[$linha][4];
			$this->_compactaLinha = $this->_arrDadosLinhasMemoria[$linha][5];
		}

    $aCamposValores = $this->aValoresPadrao[$this->_codigoLinha];
    $aIndiceCampos = $this->aCamposIndex[$this->_codigoLinha];

		foreach ( $aCamposValores as $sNomeCampo => $sValor ) {
      $aCamposValores[$sNomeCampo] = (property_exists($oDBUtils, $sNomeCampo) && $oDBUtils->$sNomeCampo != '') ? $oDBUtils->$sNomeCampo : $sValor;
		}

    $sLinha = $this->getQuebraLinha( $this->_quebrasAntes );

    if ($this->lQuebraAutomatica && $this->iPosicionamentoQuebraAutomatica == self::QUEBRA_AUTOMATICA_ANTES) {
      $sLinha .= $this->getQuebraLinha();
    }

    $sSeparador = "";
    foreach ( $aCamposValores as $sNomeCampo => $valorCampo ) {

      if (!isset($aIndiceCampos[$sNomeCampo])) {
        return false;
      }

      if (!isset($this->aDadosCampos[$this->_codigoLinha][$aIndiceCampos[$sNomeCampo]])) {
        return false;
      }

      $oDadosCampo = $this->aDadosCampos[$this->_codigoLinha][$aIndiceCampos[$sNomeCampo]];

      $this->_alinhaCampo = $oDadosCampo->db52_alinha;

      $tamanhoCampo = ($oDadosCampo->db52_tamanho != 0) ? $oDadosCampo->db52_tamanho : $oDadosCampo->db53_tamanho;

      $valorCampo = $this->formatarCampo ( $oDadosCampo->db53_tipo, $valorCampo, $tamanhoCampo, $oDadosCampo->db53_mascara );
      if ($this->_compactaLinha == "t") {
        $valorCampo = trim ( $valorCampo );
      }

      if ($oDadosCampo->db52_imprimir == "t") {

        if ($this->_separador != "") {
          $valorCampo = $sSeparador . $valorCampo;
          $sSeparador = $this->_separador;
        }

        $sLinha .= $valorCampo;
      }

      $sLinha .= $this->getQuebraLinha($oDadosCampo->db52_quebraapos);
    }

    if ($this->lQuebraAutomatica && $this->iPosicionamentoQuebraAutomatica == self::QUEBRA_AUTOMATICA_DEPOIS) {
      $sLinha .= $this->getQuebraLinha();
    }

    $sLinha .= $this->getQuebraLinha($this->_quebrasDepois);

    $this->output($sLinha);

    return true;
	}

	function setByRecord($rsRecord, $tipolinha, $identlinha = "") {

		$this->setCampoTipoLinha ( $tipolinha );
		$this->setCampoIdentLinha ( $identlinha );
		$this->limpaCampos ();

		$retornoLinhas = $this->retornaDadosLinha ();
		if (! $retornoLinhas) {
			return false;
		}

		$this->carregaCamposRetorno ( $this->_codigoLinha );

		$aNomeCampos = $this->getArrCamposNome ();

		$iNumRows = pg_num_rows ( $rsRecord );

		for($i = 0; $i < $iNumRows; $i ++) {

			foreach ( $aNomeCampos as $indice => $valorcampo ) {

				$sValorVar = pg_result ( $rsRecord, $i, $valorcampo );
				$this->setCampo ( $valorcampo, $sValorVar );

			}

			$this->geraDadosLinha ();

		}

	}

  function gerarArquivoLeiaute($sNomeArquivo,$iCodLinha,$iCodLayout=12){

    $cldb_layouttxt = new db_layouttxt($iCodLayout,$sNomeArquivo,"");

    $sSqlCampos = " select db52_nome    as nome_campo,
										       db52_descr   as descricao,
										       db52_tamanho as tamanho,
										       db52_posicao as posicao_inicial,
										       db52_posicao - 1 + ( case
										                              when db52_tamanho = 0 then db53_tamanho
										                              else db52_tamanho
										                            end ) as posicao_final
										  from db_layoutcampos
										       inner join db_layoutlinha  on db_layoutlinha.db51_codigo  = db_layoutcampos.db52_layoutlinha
										       inner join db_layoutformat on db_layoutformat.db53_codigo = db_layoutcampos.db52_layoutformat
										       inner join db_layouttxt    on db_layouttxt.db50_codigo    = db_layoutlinha.db51_layouttxt
										 where db52_layoutlinha = {$iCodLinha}
										 order by db52_posicao";

    $rsCampos      = db_query($sSqlCampos);
    $iLinhasCampos = pg_num_rows($rsCampos);

    for ( $x=0 ; $x < $iLinhasCampos; $x++ ) {
      $oLinhaArquivo = db_utils::fieldsMemory($rsCampos, $x);
      $cldb_layouttxt->setByLineOfDBUtils($oLinhaArquivo,3);
    }

  }

}

function db_setaPropriedadesLayoutTxt(&$instancia, $tipolinha, $identlinha = "") {

	$instancia->setCampoTipoLinha ( $tipolinha );
	$instancia->setCampoIdentLinha ( $identlinha );

	$retornoLinhas = $instancia->retornaDadosLinha ();
	if ($retornoLinhas == true) {
		$instancia->carregaCamposRetorno ( $instancia->_codigoLinha );
		$nomeCampos = $instancia->getArrCamposNome ();

		$varRetornoCamposArray = Array ();
		foreach ( $nomeCampos as $x => $valorcampo ) {

			$varRetornoCamposArray [$x] = @$GLOBALS ["$valorcampo"];

		}
		$instancia->setArrCamposVal ( $varRetornoCamposArray );
		$instancia->geraDadosLinha ();
	}

}
