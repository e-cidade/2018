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


require_once(modification("classes/db_db_layoutcampos_classe.php"));
require_once(modification("dbforms/db_layouttxt.php"));
require_once(modification("libs/db_libdocumento.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_libpessoal.php"));

/**
 * Classe para geracao de arquivos dos coletores
 * @author dbseller
 *
 */
class clArqExpColetor {

	/**
	 * $iCodExportcao Codigo da exportacao quer será gerado o arquivo
	 * @var integer
	 */
	protected $iCodExportacao;

	/**
	 * Codigo do coletor que o arquivo foi exportado
	 * @var integer
	 */
	protected $iCodigoColetor;
	
	/**
	 * Codigo do leiturista que realizara as leituras
	 * @var integer
	 */
	protected $iCodLeiturista;

	/**
	 * Ano exportacao
	 * @var integer
	 */
	protected $iAnoExportacao;

	/**
	 * Mes Exportacao
	 * @var integer
	 */
	protected $iMesExportacao;

	/**
	 * Numero de registros da exportacao
	 * @var integer
	 */
	protected $iNumMatriculas;
	
	/**
	 * Aciona Termometro. 0 - Não 1 - Sim
	 * @var unknown_type
	 */
	protected $iTermometro;
	
	public $msg_deb_conta;
	
	public $msg_deb_conta2;

  
	
	public function arquivoDadosMatricula($iCodExportacao, $iTermometro = 0) {

		$this->iCodExportacao = $iCodExportacao;
		
		$this->iTermometro    = $iTermometro;
		

		require_once(modification("classes/db_aguacoletorexporta_classe.php"));
		require_once(modification("classes/db_aguacoletorexportadados_classe.php"));
		require_once(modification("classes/db_aguacoletorexportadadosleitura_classe.php"));
		require_once(modification("classes/db_aguacoletorexportadadosreceita_classe.php"));

		$clAguaColetorExporta  = new cl_aguacoletorexporta();
		$rsAguaColetorExporta  = $clAguaColetorExporta->sql_record($clAguaColetorExporta->sql_query_file(null, "*", null, "x49_sequencial = $this->iCodExportacao"));
		$oAguaColetorExporta   = db_utils::fieldsMemory($rsAguaColetorExporta, 0);
		$this->iCodigoColetor  = $oAguaColetorExporta->x49_aguacoletor;
		$this->iAnoExportacao  = $oAguaColetorExporta->x49_anousu;
		$this->iMesExportacao  = $oAguaColetorExporta->x49_mesusu;
		
		
		$this->getArretipo($oAguaColetorExporta->x49_anousu);

		$clAguaColetorExportaDados         = new cl_aguacoletorexportadados();
		$clAguaColetorExportaDadosLeitura  = new cl_aguacoletorexportadadosleitura();
		$clAguaColetorExportaDadosReceita  = new cl_aguacoletorexportadadosreceita();

		$sCamposAguaColetorExportaDados  = "x50_sequencial, x50_rota, x49_anousu, x49_mesusu, x50_matric, x50_responsavel, ";
		$sCamposAguaColetorExportaDados .= "x50_codlogradouro, j88_sigla, x50_nomelogradouro, x50_numero, x50_letra, ";
		$sCamposAguaColetorExportaDados .= "x50_complemento, x50_nomebairro, x50_cidade, x50_estado, x50_zona, x50_quadra, x50_economias, x50_numpre, ";
		$sCamposAguaColetorExportaDados .= "x50_natureza, x50_categorias, x50_areaconstruida, x50_nrohidro, x50_dtleituraatual, x50_dtleituraanterior, ";
		$sCamposAguaColetorExportaDados .= "x50_consumo, x50_diasleitura,  x50_mediadiaria, x50_vencimento, x50_valoracrescimo, x50_valordesconto, ";
		$sCamposAguaColetorExportaDados .= "x50_valortotal, x50_linhadigitavel, x50_codigobarras, x50_imprimeconta, x50_consumopadrao, x50_consumomaximo,";
    	$sCamposAguaColetorExportaDados .= "x50_avisoleiturista";
		
		$sSqlAguaColetorExportaDados = $clAguaColetorExportaDados->sql_query_dados(null, $sCamposAguaColetorExportaDados, "x50_sequencial", "x50_aguacoletorexporta = $this->iCodExportacao");
		$rsAguaColetorExportaDados   = $clAguaColetorExportaDados->sql_record($sSqlAguaColetorExportaDados);

		$this->iNumMatriculas         = $clAguaColetorExportaDados->numrows;

		$nomeArquivo = "tmp/".$this->geraNomeArquivo("exportacoletor01");

		$cldb_layouttxt = new db_layouttxt(75, $nomeArquivo, "");

		if($this->iTermometro == 1) {
		  $this->iniciaTermometro();
		}

    $numRowsMatriculas = $clAguaColetorExportaDados->numrows;
		
		for($d = 0; $d < $numRowsMatriculas; $d++) {

			$oAguaColetorExportaDados = db_utils::fieldsMemory($rsAguaColetorExportaDados, $d);
			
			if($this->iTermometro == 1) {
			  db_atutermometro ( $d, $numRowsMatriculas, "termometro", 1, "Processando Matricula $oAguaColetorExportaDados->x50_matric (" . ($d + 1) . "/$numRowsMatriculas) ...   " );
			}
			
			$oLibDocumento  = new libdocumento(32);
			
			if($oAguaColetorExportaDados->x50_imprimeconta == 3) {
			  
			  $oLibDocumento->msg_debconta01 = $this->msg_deb_conta;
			  $oLibDocumento->msg_debconta02 = $this->msg_deb_conta2;
			  
			} else {
			  
			  $oLibDocumento->msg_debconta01 = "";
        $oLibDocumento->msg_debconta02 = "";
        
			}
			
      $paragrafos     = $oLibDocumento->getDocParagrafos();

			$cldb_layouttxt->setCampoTipoLinha(3);
			$cldb_layouttxt->limpaCampos();

			$cldb_layouttxt->setCampo("codleitura"             , $oAguaColetorExportaDados->x50_sequencial);
			
			if ($oAguaColetorExportaDados->x50_rota == 0){
				$cldb_layouttxt->setCampo("rota"                 , ' '.$oAguaColetorExportaDados->x50_rota);
			} else {

				$cldb_layouttxt->setCampo("rota"                 , $oAguaColetorExportaDados->x50_rota);
			}
			$cldb_layouttxt->setCampo("rota"                   , $oAguaColetorExportaDados->x50_rota);
			$cldb_layouttxt->setCampo("anousu"                 , $oAguaColetorExportaDados->x49_anousu);
			$cldb_layouttxt->setCampo("mesusu"                 , $oAguaColetorExportaDados->x49_mesusu);
			$cldb_layouttxt->setCampo("matricula"              , $oAguaColetorExportaDados->x50_matric);
			$cldb_layouttxt->setCampo("nome_usuario"           , $oAguaColetorExportaDados->x50_responsavel);
			$cldb_layouttxt->setCampo("logradouro"             , $oAguaColetorExportaDados->x50_codlogradouro);
			$cldb_layouttxt->setCampo("tipo"                   , $oAguaColetorExportaDados->j88_sigla);
			$cldb_layouttxt->setCampo("logradouro_nome"        , $oAguaColetorExportaDados->x50_nomelogradouro);
			$cldb_layouttxt->setCampo("numero"                 , $oAguaColetorExportaDados->x50_numero);
			$cldb_layouttxt->setCampo("letra"                  , $oAguaColetorExportaDados->x50_letra);
			$cldb_layouttxt->setCampo("complemento"            , $oAguaColetorExportaDados->x50_complemento);
			$cldb_layouttxt->setCampo("bairro"                 , $oAguaColetorExportaDados->x50_nomebairro);
			$cldb_layouttxt->setCampo("cidade"                 , $oAguaColetorExportaDados->x50_cidade);
			$cldb_layouttxt->setCampo("estado"                 , $oAguaColetorExportaDados->x50_estado);
			$cldb_layouttxt->setCampo("zona"                   , $oAguaColetorExportaDados->x50_zona);
			$cldb_layouttxt->setCampo("quadra"                 , $oAguaColetorExportaDados->x50_quadra);
			$cldb_layouttxt->setCampo("economias"              , $oAguaColetorExportaDados->x50_economias);
			$cldb_layouttxt->setCampo("processamento"          , $oAguaColetorExportaDados->x50_numpre);
			$cldb_layouttxt->setCampo("natureza"               , $oAguaColetorExportaDados->x50_natureza);
			$cldb_layouttxt->setCampo("categoria"              , $oAguaColetorExportaDados->x50_categorias);
			$cldb_layouttxt->setCampo("area_construida"        , $oAguaColetorExportaDados->x50_areaconstruida);
			$cldb_layouttxt->setCampo("hidrometro"             , $oAguaColetorExportaDados->x50_nrohidro);
			$cldb_layouttxt->setCampo("dt_leitura_atual"       , $oAguaColetorExportaDados->x50_dtleituraatual);
			$cldb_layouttxt->setCampo("dt_leitura_anterior"    , $oAguaColetorExportaDados->x50_dtleituraanterior);
			$cldb_layouttxt->setCampo("consumo"                , $oAguaColetorExportaDados->x50_consumo);
			$cldb_layouttxt->setCampo("dias_leitura"           , $oAguaColetorExportaDados->x50_diasleitura);
			$cldb_layouttxt->setCampo("media_diaria"           , $oAguaColetorExportaDados->x50_mediadiaria);
			$cldb_layouttxt->setCampo("vencimento"             , $oAguaColetorExportaDados->x50_vencimento);
			$cldb_layouttxt->setCampo("valor_acrescimo"        , $oAguaColetorExportaDados->x50_valoracrescimo);
			$cldb_layouttxt->setCampo("valor_desconto"         , $oAguaColetorExportaDados->x50_valordesconto);
			$cldb_layouttxt->setCampo("valor_total"            , $oAguaColetorExportaDados->x50_valortotal);
			$cldb_layouttxt->setCampo("aviso1"                 , $this->retornaTexto("AVISO1", $paragrafos, 70));
			$cldb_layouttxt->setCampo("aviso2"                 , $this->retornaTexto("AVISO2", $paragrafos, 70));
			$cldb_layouttxt->setCampo("aviso3"                 , $this->retornaTexto("AVISO3", $paragrafos, 70));
			$cldb_layouttxt->setCampo("aviso4"                 , $this->retornaTexto("AVISO4", $paragrafos, 70));
			$cldb_layouttxt->setCampo("aviso5"                 , $this->retornaTexto("AVISO5", $paragrafos, 70));
			$cldb_layouttxt->setCampo("aviso6"                 , $this->retornaTexto("AVISO6", $paragrafos, 70));
			$cldb_layouttxt->setCampo("msg8"                   , $this->retornaTexto("MSG8", $paragrafos, 70));
			$cldb_layouttxt->setCampo("msg9"                   , $this->retornaTexto("MSG9", $paragrafos, 70));
			$cldb_layouttxt->setCampo("msg10"                  , $this->retornaTexto("MSG10", $paragrafos, 70));
			$cldb_layouttxt->setCampo("msg11"                  , $this->retornaTexto("MSG11", $paragrafos, 70));
			$cldb_layouttxt->setCampo("msg12"                  , $this->retornaTexto("MSG12", $paragrafos, 70));
			$cldb_layouttxt->setCampo("msg13"                  , $this->retornaTexto("MSG13", $paragrafos, 70));
			$cldb_layouttxt->setCampo("linha_digitavel"        , $oAguaColetorExportaDados->x50_linhadigitavel);
			$cldb_layouttxt->setCampo("codigo_barras"          , $oAguaColetorExportaDados->x50_codigobarras);
			$cldb_layouttxt->setCampo("imprime_conta"          , $oAguaColetorExportaDados->x50_imprimeconta);
			$cldb_layouttxt->setCampo("consumo_maximo"         , $oAguaColetorExportaDados->x50_consumomaximo);
			$cldb_layouttxt->setCampo("consumo_padrao"         , $oAguaColetorExportaDados->x50_consumopadrao);
			$cldb_layouttxt->setCampo("codigo_coletor"         , $this->iCodigoColetor);
			$cldb_layouttxt->setCampo("aviso_leiturista"       , $oAguaColetorExportaDados->x50_avisoleiturista);

			$cldb_layouttxt->setCampo("titulo_receita_1", "Rec   Descricao        Parcela       Valor  Numpre");
			$cldb_layouttxt->setCampo("titulo_receita_2", "Rec   Descricao        Parcela       Valor  Numpre");
			
			$sSqlAguaColetorExportaDadosLeitura = $clAguaColetorExportaDadosLeitura->sql_query_file(null, "x51_numcgm", null, "x51_aguacoletorexportadados = $oAguaColetorExportaDados->x50_sequencial");
			$rsAguaColetorExportaDadosLeitura   = $clAguaColetorExportaDadosLeitura->sql_record($sSqlAguaColetorExportaDadosLeitura);
			$oAguaColetorExportaDadosLeitura    = db_utils::fieldsMemory($rsAguaColetorExportaDadosLeitura, 0);
      
      if ($oAguaColetorExportaDadosLeitura->x51_numcgm == 0){
        $cldb_layouttxt->setCampo("numcgm", ' '.$oAguaColetorExportaDadosLeitura->x51_numcgm);
      } else {

        $cldb_layouttxt->setCampo("numcgm", $oAguaColetorExportaDadosLeitura->x51_numcgm);
      }
			
			$sSqlAguaLeiturasAnteriores = $clAguaColetorExportaDados->sql_query_leituras_anteriores($oAguaColetorExportaDados->x50_matric, $this->iAnoExportacao, $this->iMesExportacao);
			$rsAguaLeiturasAnteriores   = $clAguaColetorExportaDados->sql_record($sSqlAguaLeiturasAnteriores);
			
			$numRowsLeiturasAnteriores  = $clAguaColetorExportaDados->numrows;
			$iSaldoLeitura = 0;
			if($numRowsLeiturasAnteriores > 0) {

			  $dt_referencia = new DateTime($oAguaColetorExportaDados->x49_anousu."-".$oAguaColetorExportaDados->x49_mesusu."-01");
			  $numero        = 2;
			  for($l = 0; $l <= $numRowsLeiturasAnteriores; $l++) {
			    
			    $oLeiturasAnteriores        = db_utils::fieldsMemory($rsAguaLeiturasAnteriores, $l);
			    $oLeiturasAnterioresProxima = db_utils::fieldsMemory($rsAguaLeiturasAnteriores, $l+1);
			    
			    if($oLeiturasAnterioresProxima->x21_codleitura != '') {
			      $diasUltimaLeitura = db_datedif($oLeiturasAnteriores->x21_dtleitura, $oLeiturasAnterioresProxima->x21_dtleitura);
			    } else {
			      $diasUltimaLeitura = " 0";
			    }
			    
			    if($numero <= 6) {
            $cldb_layouttxt->setCampo("leitura_".$numero."_ano",      $oLeiturasAnteriores->x21_exerc);
            $cldb_layouttxt->setCampo("leitura_".$numero."_mes",      $oLeiturasAnteriores->x21_mes);
            $cldb_layouttxt->setCampo("leitura_".$numero."_situacao", $oLeiturasAnteriores->x21_situacao);
            $cldb_layouttxt->setCampo("leitura_".$numero."_leitura",  $oLeiturasAnteriores->x21_leitura);
            $cldb_layouttxt->setCampo("leitura_".$numero."_consumo",  $oLeiturasAnteriores->x21_consumo);
            $cldb_layouttxt->setCampo("leitura_".$numero."_excesso",  $oLeiturasAnteriores->x21_excesso);
            $cldb_layouttxt->setCampo("leitura_".$numero."_dias",     $diasUltimaLeitura);

            $iMesesDiferenca  = date_diff($oLeiturasAnteriores->x21_dtleitura, $dt_referencia)->m;
            $iMesesDiferenca .= + date_diff($oLeiturasAnteriores->x21_dtleitura, $dt_referencia)->y * 12;
            if ( $iMesesDiferenca <= 6){
              $iSaldoLeitura +=  $oLeiturasAnteriores->x21_saldo;
            }
			    }
          
          $numero++;
          
			    
			  }
			  
			}
			$cldb_layouttxt->setCampo("consumo_saldo", $iSaldoLeitura);
			
			$sCampos = "x52_receita, x52_descricao, x52_numpar, x52_numtot, x52_valor, x52_numpre";
			$sSqlAguaColetorExportaDadosReceita = $clAguaColetorExportaDadosReceita->sql_query(null, $sCampos, "x52_receita", "x52_aguacoletorexportadados = $oAguaColetorExportaDados->x50_sequencial");
			$rsAguaColetorExportaDadosReceita   = $clAguaColetorExportaDadosReceita->sql_record($sSqlAguaColetorExportaDadosReceita);
      
			if ($clAguaColetorExportaDadosReceita->numrows) {
				for($r = 0; $r < $clAguaColetorExportaDadosReceita->numrows; $r++) {

					$oAguaColetorExportaDadosReceita = db_utils::fieldsMemory($rsAguaColetorExportaDadosReceita, $r);

					$cldb_layouttxt->setCampo("receita_".($r+1)."_codigo"    , $oAguaColetorExportaDadosReceita->x52_receita);
					$cldb_layouttxt->setCampo("receita_".($r+1)."_descricao" , $oAguaColetorExportaDadosReceita->x52_descricao);
					$cldb_layouttxt->setCampo("receita_".($r+1)."_parcela"   , $oAguaColetorExportaDadosReceita->x52_numpar."/".$oAguaColetorExportaDadosReceita->x52_numtot);
					$cldb_layouttxt->setCampo("receita_".($r+1)."_valor"     , $oAguaColetorExportaDadosReceita->x52_valor);
					$cldb_layouttxt->setCampo("receita_".($r+1)."_numpre"    , $oAguaColetorExportaDadosReceita->x52_numpre);

				}
			}

			$cldb_layouttxt->geraDadosLinha();
		}
		$cldb_layouttxt->fechaArquivo();

		return $nomeArquivo;

	}//function


	
	public function getArretipo($iAno) {
	  
	  $rsArreTipo = db_query("select fc_agua_confarretipo($iAno) as x18_arretipo");
	  
	  if(pg_num_rows($rsArreTipo) > 0) {
	    
	    $iArretipo = db_utils::fieldsMemory($rsArreTipo, 0)->x18_arretipo;

	    $this->getMsgArretipo($iArretipo);
	  }
    
	}
	
	public function getMsgArretipo($iArretipo) {
	  
	  $sSqlArretipo = " select k00_codbco, 
	                           k00_codage, 
	                           k00_descr, 
	                           k00_hist1, 
	                           k00_hist2, 
	                           k00_hist3, 
	                           k00_hist4, 
	                           k00_hist5,
                             k00_hist6,
                             k00_hist7,
                             k00_hist8,
                             k03_tipo,
                             k00_tipoagrup,
                             k00_tercdigrecnormal 
                            from arretipo 
                           where k00_tipo   = {$iArretipo}
                             and k00_instit = " . db_getsession('DB_instit');
	  
	  $rsArretipo = db_query($sSqlArretipo);
	  
	  if(pg_num_rows($rsArretipo) > 0) {
	    
	    $oArretipo = db_utils::fieldsMemory($rsArretipo, 0);
	    
	    $this->msg_deb_conta  = $oArretipo->k00_hist7;
	    
	    $this->msg_deb_conta2 = $oArretipo->k00_hist8; 
	    
	  }
	  
	}
	
	public function arquivoDadosSitLeitura() {

		require_once(modification("classes/db_aguasitleitura_classe.php"));

		$nomeArquivo      = "tmp/".$this->geraNomeArquivo("exportacoletor02");
		$claguasitleitura = new cl_aguasitleitura();
		$cldb_layouttxt   = new db_layouttxt(77, $nomeArquivo, "");
		$cldb_layouttxt->setCampoTipoLinha(3);
		$cldb_layouttxt->limpaCampos();

		$campos           = "*";
		$rsAguaSitLeitura = $claguasitleitura->sql_record($claguasitleitura->sql_query(null, $campos, "x17_codigo",""));

		if($claguasitleitura->numrows > 0) {

			for($s = 0; $s < $claguasitleitura->numrows; $s++) {

				$oAguaSitLeitura = db_utils::fieldsMemory($rsAguaSitLeitura, $s);

				if(empty($oAguaSitLeitura->x17_codigo)) {

					$cldb_layouttxt->setCampo("codigo", " 0"); //para n imprimir valor em branco qdo o codigo vier 0

				}else {

					$cldb_layouttxt->setCampo("codigo", $oAguaSitLeitura->x17_codigo);

				}

				$cldb_layouttxt->setCampo("descricao", $oAguaSitLeitura->x17_descr);
				$cldb_layouttxt->setCampo("regra",     $oAguaSitLeitura->x17_regra);
				$cldb_layouttxt->geraDadosLinha();

			}
		}
		$cldb_layouttxt->fechaArquivo();
		return $nomeArquivo;

	}

	public function arquivoDadosLeituristas() {

		require_once(modification("classes/db_agualeiturista_classe.php"));

		$nomeArquivo      = "tmp/".$this->geraNomeArquivo("exportacoletor03");
		$clagualeiturista = new cl_agualeiturista();
		$cldb_layouttxt   = new db_layouttxt(76, $nomeArquivo, "");
		$cldb_layouttxt->setCampoTipoLinha(3);
		$cldb_layouttxt->limpaCampos();

		$campos           = "x16_numcgm, z01_nome, x16_senha";
		$rsAguaLeiturista = $clagualeiturista->sql_record($clagualeiturista->sql_query(null, $campos, 'trim(z01_nome)',null));

		if($clagualeiturista->numrows > 0) {

			for($l = 0; $l < $clagualeiturista->numrows; $l++) {

				$oAguaLeiturista = db_utils::fieldsMemory($rsAguaLeiturista, $l);


				if(empty($oAguaLeiturista->x16_numcgm)) {

					$cldb_layouttxt->setCampo("numcgm", " 0"); //para n imprimir valor em branco qdo o codigo vier 0

				}else {

					$cldb_layouttxt->setCampo("numcgm", $oAguaLeiturista->x16_numcgm);

				}

				$cldb_layouttxt->setCampo("nome",  $oAguaLeiturista->z01_nome);
				$cldb_layouttxt->setCampo("senha", $oAguaLeiturista->x16_senha);
				$cldb_layouttxt->geraDadosLinha();

			}

		}
		$cldb_layouttxt->fechaArquivo();
		return $nomeArquivo;

	}

	public function arquivoDadosConfiguracoes() {

		require_once(modification("classes/db_cadarrecadacao_classe.php"));
		require_once(modification("classes/db_arretipo_classe.php"));

		$nomeArquivo      = "tmp/".$this->geraNomeArquivo("exportacoletor04");
		$clCadArrecadacao = new cl_cadarrecadacao();
		$clArretipo       = new cl_arretipo();
		$cldb_layouttxt   = new db_layouttxt(83, $nomeArquivo);
		$cldb_layouttxt->setCampoTipoLinha(3);
		$cldb_layouttxt->limpaCampos();

		$sCampos = "ar16_segmento, ar16_convenio, ar16_formatovenc";
		$sWhere  = "ar16_instit = cast(fc_getsession('DB_instit') as integer)";
		$sSqlCadArrecadacao = $clCadArrecadacao->sql_query(null, $sCampos, null, $sWhere);
		$rsCadArrecadacao   = $clCadArrecadacao->sql_record($sSqlCadArrecadacao);

		$oCadArrecadacao = db_utils::fieldsMemory($rsCadArrecadacao,0);

		$cldb_layouttxt->setCampo("segmento", $oCadArrecadacao->ar16_segmento);
		$cldb_layouttxt->setCampo("convenio", $oCadArrecadacao->ar16_convenio);
		$cldb_layouttxt->setCampo("formato_data", $oCadArrecadacao->ar16_formatovenc);

		$sWhere       = "k00_tipo = fc_agua_confarretipo(cast(fc_getsession('DB_anousu') as integer)) and k00_instit = cast(fc_getsession('DB_instit') as integer)";
		$sSqlArretipo = $clArretipo->sql_query_file(null, "k00_tercdigrecnormal", null, $sWhere);
		$rsArretipo   = $clArretipo->sql_record($sSqlArretipo);

		$oArretipo = db_utils::fieldsMemory($rsArretipo,0);

		$cldb_layouttxt->setCampo("digito_vencimento", isset($oArretipo->k00_tercdigrecnormal) ? $oArretipo->k00_tercdigrecnormal : "");
		$cldb_layouttxt->geraDadosLinha();

		$cldb_layouttxt->fechaArquivo();

		return $nomeArquivo;

	}


	public function gerarArquivoLayout($iCodLayout, $prefixoArq = null) {

		$this->iCodLayout = $iCodLayout;

		$nomeArquivo       = "tmp/".$this->geraNomeArquivo("exportacoletorlayout".$prefixoArq, true);
		$cldb_layouttxt    = new db_layouttxt(12, $nomeArquivo, "");
		$cldb_layoutcampos = new cl_db_layoutcampos();
		$cldb_layouttxt->setCampoTipoLinha(3); // 3-Registro
		$cldb_layouttxt->limpaCampos();


		$campos  = "db52_nome, db52_descr, db52_layoutformat, db52_posicao as db52_posicao_inicial, ";
		$campos .= "db52_posicao - 1 + (case when db52_tamanho = 0 then db53_tamanho else db52_tamanho end) as db52_posicao_final";
		$dbwhere = "db52_layoutlinha = $this->iCodLayout";
		$result  = $cldb_layoutcampos->sql_record($cldb_layoutcampos->sql_query(null, $campos, "db52_posicao", $dbwhere));

		for ($x=0; $x < $cldb_layoutcampos->numrows; $x++) {

			$oLayoutCampos = db_utils::fieldsMemory($result, $x);

			$cldb_layouttxt->setCampo("posicao_inicial", $oLayoutCampos->db52_posicao_inicial);
			$cldb_layouttxt->setCampo("posicao_final",   $oLayoutCampos->db52_posicao_final);
			$cldb_layouttxt->setCampo("nome_campo",      $oLayoutCampos->db52_nome);
			$cldb_layouttxt->setCampo("descricao",       $oLayoutCampos->db52_descr);
			$cldb_layouttxt->geraDadosLinha();

		}

		$cldb_layouttxt->fechaArquivo();

		return $nomeArquivo;

	}


	public function geraNomeArquivo($prefixo, $layout=false) {

		$nomeArquivo            = "$prefixo"; //01 = arquivos rotas e leituras
		$nomeArquivo           .= str_pad($this->iCodigoColetor, 2, "0", STR_PAD_LEFT);
		$nomeArquivo           .= str_pad($this->iAnoExportacao, 4, "0", STR_PAD_LEFT);
		$nomeArquivo           .= str_pad($this->iMesExportacao, 2, "0", STR_PAD_LEFT);
		$nomeArquivo           .= str_pad(db_getsession('DB_id_usuario'), 4, "0", STR_PAD_LEFT);
		$nomeArquivo           .= date("YmdHm");
		if($layout == false){
		  $nomeArquivo           .= str_pad($this->iNumMatriculas, 5, "0", STR_PAD_LEFT);
		}
		$nomeArquivo           .= ".txt";
		
		return $nomeArquivo;

	}
	
	public function iniciaTermometro() {
	  db_criatermometro ( 'termometro', 'Concluido...', 'blue', 1, 'Processando Arquivos Exportacao' );
    flush ();
	}
	
	
	/**
	 * 
   * funcao q percorre o objeto procurando a descricao do paragrafo informado e retorna o texto referente a descricao.
   * @param $descricao  = descricao do paragrafo. ex.: "MSG1"
   * @param $paragrafos = array retornado da função getDocParagrafos da classe libdocumento q contem o conteudo dos paragrafos
   * @param $tamanho    = numero de caracteres da string
	 * @return string
	 */
	
	public function retornaTexto($descricao, $paragrafos, $tamanho) {

		foreach($paragrafos as $oParagrafo) {

			if($oParagrafo->oParag->db02_descr == trim($descricao)) {

				return substr($oParagrafo->oParag->db02_texto, 0, $tamanho);

			}

		}

	}

}//class