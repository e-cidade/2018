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


require_once('std/DBLargeObject.php');

class ArquivoCobrancaRegistrada {
  
  var $iIdAtosDistribuidores             = "";
  var $iIdTaxaJudiciaria                 = "";
  var $iIdAtosEscrivaesDividaAtiva       = "";
  var $iIdAcrescimo                      = "";
  var $iIdAtosOficiaisJusticaAvaliadores = "";
  function __construct() {
    
      	
  }
  
  /**
   * Metodo utilizado para reemitir arquivos de remessa ao Banco referentes a cobranca registrada
   *
   * @param int    $iCodigoArquivo  - Define o codigo do arquivo para reemissão
   *
   */
  public function reemiteArquivoRemessaBanco($iCodigoArquivo, $sNomeAuxiliar = null) {
    
    $oDaoPartilhaArquivo  = db_utils::getDao('partilhaarquivo');    
    $sSql                 = $oDaoPartilhaArquivo->sql_query_file($iCodigoArquivo);    
    $rsPartilhaArquivo    = $oDaoPartilhaArquivo->sql_record($sSql);    
    $oPartilhaArquivo     = db_utils::fieldsMemory($rsPartilhaArquivo, 0);
    
    $sArquivo = "tmp/".$sNomeAuxiliar.$oPartilhaArquivo->v78_nomearq;
    
    $lReemitiuArquivo = DBLargeObject::leitura($oPartilhaArquivo->v78_arquivo, $sArquivo);
    
    if ($lReemitiuArquivo) {
      return $sArquivo;
    } else {
      throw new Exception("[ 0 ] - Erro ao reemitir o arquivo!");
    }
    
  }
  
  /**
   * Metodo utilizado para geracao dos arquivos de remessa ao Banco referentes a cobranca registrada
   * 
   * @param date    $dtProcessamento  - Define a data dos registros de recibos que constarao no arquivo
   * @param string  $sNomeArq         - Define o nome do arquivo que será gerado 
   * 
   */
  public function geraArquivoRemessaBanco($dtProcessamento, $sNomeArq) {

    if ( !db_utils::inTransaction() ) {
  		throw new Exception("[ 0 ] - Nenhuma transação encontrada!");
  	}
  	
    $oDaoConvenioCobranca     = db_utils::getDao('conveniocobranca');
    $oDaoPartilhaArquivo      = db_utils::getDao('partilhaarquivo');
    $oDaoPartilhaArquivoReg   = db_utils::getDao('partilhaarquivoreg');
    $oDaoDbConfig             = db_utils::getDao('db_config');
  	$oLayoutTxt               = new db_layouttxt(100, "tmp/".$sNomeArq);
  	
    $aPartilhaCustas          = array();
    $iRegistro = 1;
    
    
  	/*
     * Buscamos as informações referentes ao convênio com o banco
     */
    $rsConvenioCobranca = $oDaoConvenioCobranca->sql_record($oDaoConvenioCobranca->sql_query("","*",null,"ar13_carteira = '17' and ar11_cadtipoconvenio = 7"));
    if ($oDaoConvenioCobranca->numrows == 0) {
      throw new Exception("[ 1 ] - Nenhum cadastro de convênio do tipo Cobrança Registrada com carteira 17 encontrado!");
    }
    $oConvenioCobranca = db_utils::fieldsMemory($rsConvenioCobranca,0);

    //Buscamos os dados da instituição
    $rsDbConfig = $oDaoDbConfig->sql_record($oDaoDbConfig->sql_query(db_getsession("DB_instit"), "*"));
    if ($oDaoDbConfig->numrows == 0) {
      throw new Exception("[ 2 ] - Erro ao buscar dados da instituição!\nErro: {$oDaoDbConfig->erro_msg}");
    }
    $oDbConfig = db_utils::fieldsMemory($rsDbConfig,0);
    
    /*
     * Gera o arquivo oid
     * 
     */
    $iOid         = DBLargeObject::criaOID(true);
    
    /*
     * Cadastramos o registro de geração do arquivo
     */
    $oDaoPartilhaArquivo->v78_dtgeracao = $dtProcessamento; 
    $oDaoPartilhaArquivo->v78_nomearq   = $sNomeArq;
    $oDaoPartilhaArquivo->v78_tipoarq   = 1;
    $oDaoPartilhaArquivo->v78_arquivo   = $iOid;
    $oDaoPartilhaArquivo->incluir(null);
    if ($oDaoPartilhaArquivo->erro_status == "0") {
      throw new Exception("[ 3 ] - Erro ao inserir dados em Partilha Arquivo\n {$oDaoPartilhaArquivo->erro_msg}");
    }
    
    $aDtProcessamento = split("-",$dtProcessamento);
    $sDtProcessamento = $aDtProcessamento[2].$aDtProcessamento[1].substr($aDtProcessamento[0],2,2);
    
    /*
     * Montanos o Header do Arquivo com as informações do convênio e sequencial de geração do arquivo
     */
    $oHeader = new stdClass();
    $oHeader->id                                  = "0"; 
    $oHeader->fixo1                               = "1";
    $oHeader->fixo2                               = str_pad("CBR653",                             7, " ", STR_PAD_RIGHT);
    $oHeader->fixo3                               = "01";
    $oHeader->fixo4                               = "COBRANCA";
    $oHeader->fixo5                               = str_repeat(" ",7); //brancos
    $oHeader->prefixo_agencia                     = str_pad(substr($oConvenioCobranca->db89_codagencia,-4),  4, "0", STR_PAD_LEFT);
    $oHeader->dv_prefixo_agencia                  = str_pad($oConvenioCobranca->db89_digito     ,  1, "0", STR_PAD_LEFT);
    $oHeader->codigo_cedente                      = str_pad($oConvenioCobranca->ar13_cedente    ,  8, "0", STR_PAD_LEFT);
    $oHeader->dv_codigo_cedente                   = str_pad($oConvenioCobranca->ar13_digcedente ,  1, "0", STR_PAD_LEFT);;
    $oHeader->fixo6                               = "000000";  
    $oHeader->nome_cliente                        = str_pad($oDbConfig->nomeinst                , 30, " ", STR_PAD_RIGHT);
    $oHeader->banco                               = "001BANCO DO BRASIL"; //fixo
    $oHeader->data_gravacao                       = $sDtProcessamento;
    $oHeader->sequencial_remessa                  = str_pad($oDaoPartilhaArquivo->v78_sequencial, 7, "0", STR_PAD_LEFT);
    $oHeader->fixo7                               = str_repeat(" ",22); //brancos
    $oHeader->numero_convenio_banco               = str_pad($oConvenioCobranca->ar13_convenio,    7, "0", STR_PAD_LEFT);
    $oHeader->fixo8                               = str_repeat(" ",258); //brancos
    $oHeader->sequencial_registro                 = "000001";
    if ( $oLayoutTxt->setByLineOfDBUtils($oHeader,1,"0") == false ) {
      throw new Exception ("[ 4 ] - Erro ao gerar Header do Arquivo");
    }        
    
    /*
     * Montamos os dados do detalhe do arquivo
     * 
     * Primeiramente buscamos as informações sque irão compor os campos dessas linhas
     * Após percorremos os registros encontrados e montamos a stdClass $oDadosDetalhe para geração das linhas do Detalhe
     * 
     */  
     $oRegistros = $this->getDadosReciboPartilha(1,$dtProcessamento);
     if (count($oRegistros) == 0) {
       throw new Exception("[ 3 ] - Nenhum registro de emissão com cobranca encontrado para a data informada!");
     } 
     foreach ( $oRegistros as $aDados ) {
        
        $iRegistro++;
        
        $sNumeroControleParticipante = $aDados->z01_numcgm.$aDados->numbanco.str_replace("-","",$aDados->k00_dtpaga);
        
        $aDataEmissao    = split("-",$aDados->k00_dtoper);
        $sDataEmissao    = $aDataEmissao[2].$aDataEmissao[1].substr($aDataEmissao[0],2,2);

        $aDataVencimento = split("-",$aDados->k00_dtpaga);
        $sDataVencimento = $aDataVencimento[2].$aDataVencimento[1].substr($aDataVencimento[0],2,2); 
        
        $nValor    = number_format(round($aDados->valor_total_recibo,2), 2, '', '');
        
        $oDetalhe = new stdClass();
        $oDetalhe->id                                   = "7"; 
        $oDetalhe->tipo_inscricao_empresa               = "02";
        $oDetalhe->inscricao_empresa                    = str_pad($oDbConfig->z01_cgccpf                    , 14, "0", STR_PAD_LEFT); 
        $oDetalhe->prefixo_agencia                      = str_pad(substr($oConvenioCobranca->db89_codagencia,-4),  4, "0", STR_PAD_LEFT);
        $oDetalhe->dv_prefixo_agencia                   = str_pad($oConvenioCobranca->db89_digito           ,  1, "0", STR_PAD_LEFT); 
        $oDetalhe->codigo_cedente                       = str_pad($oConvenioCobranca->ar13_cedente          ,  8, "0", STR_PAD_LEFT);
        $oDetalhe->dv_codigo_cedente                    = str_pad($oConvenioCobranca->ar13_digcedente       ,  1, "0", STR_PAD_LEFT);
        $oDetalhe->numero_convenio                      = str_pad($oConvenioCobranca->ar13_convenio         ,  7, "0", STR_PAD_LEFT);
        $oDetalhe->numero_controle_participante         = str_pad($sNumeroControleParticipante              , 25, "0", STR_PAD_LEFT);
        $oDetalhe->nosso_numero                         = str_pad($aDados->numbanco                         , 17, "0", STR_PAD_RIGHT); 
        $oDetalhe->fixo1                                = str_repeat("0",2);  //00         
        $oDetalhe->fixo2                                = str_repeat("0",2);  //00 
        $oDetalhe->fixo3                                = str_repeat(" ",3);  //brancos            
        $oDetalhe->indicativo_sacador                   = str_repeat(" ",1); 
        $oDetalhe->prefixo_titulo                       = str_repeat(" ",3);  //brancos
        $oDetalhe->variacao_carteira                    = "019"; 
        $oDetalhe->fixo4                                = str_repeat("0",1);  //0
        $oDetalhe->fixo5                                = str_repeat("0",6);  //000000
        $oDetalhe->fixo6                                = str_repeat(" ",5);  //brancos 
        $oDetalhe->carteira                             = $oConvenioCobranca->ar13_carteira;
        $oDetalhe->comando                              = "01"; 
        $oDetalhe->seu_numero                           = str_pad($aDados->k00_numnov."000"                 , 10, "0", STR_PAD_LEFT);
        $oDetalhe->data_vencimento                      = $sDataVencimento;
        $oDetalhe->valor_titulo                         = str_pad($nValor, 13, "0", STR_PAD_LEFT);
        $oDetalhe->numero_banco                         = "001"; 
        $oDetalhe->prefixo_agencia_cobradora            = str_repeat("0",4);  //0000
        $oDetalhe->dv_prefixo_agencia_cobradora         = str_repeat(" ",1);     
        $oDetalhe->especie_titulo                       = "27"; 
        $oDetalhe->aceite                               = "N";
        $oDetalhe->data_emissao                         = $sDataEmissao; 
        $oDetalhe->instrucao_codificada1                = str_repeat("0",2);  //00
        $oDetalhe->instrucao_codificada2                = str_repeat("0",2);  //00 
        $oDetalhe->juros_mora_dia                       = str_repeat("0",13); //00000000000
        $oDetalhe->data_limite_concessao_desconto       = str_repeat("0",6);  //000000
        $oDetalhe->valor_desconto                       = str_repeat("0",13); //00000000000
        $oDetalhe->valor_IOF                            = str_repeat("0",13); //00000000000
        $oDetalhe->valor_abatimento                     = str_repeat("0",13); //00000000000
        $oDetalhe->tipo_inscricao_sacado                = str_repeat("0",2);  //00
        $oDetalhe->documento_sacado                     = str_repeat("0",14); //00000000000000           
        $oDetalhe->nome_sacado                          = str_pad($aDados->z01_nome, 37, " ", STR_PAD_RIGHT); 
        $oDetalhe->fixo7                                = str_repeat(" ",3);  //brancos
        $oDetalhe->fixo8                                = str_repeat(" ",15); //brancos

        //Caso o endereço do sacado seja nulo, utilizamos o enderço da prefeitura
        if (empty($aDados->z01_bairro) || empty($aDados->z01_munic) || empty($aDados->z01_ender)) {
          
          $oDetalhe->endereco_sacado                      = $oDbConfig->ender;
          $oDetalhe->cep_endereco_sacado                  = $oDbConfig->cep;
          $oDetalhe->cidade_endereco_sacado               = $oDbConfig->munic; 
          $oDetalhe->uf_cidade_sacado                     = $oDbConfig->uf;
                    
        } else {
          
          $oDetalhe->endereco_sacado                      = $aDados->z01_ender;          
          $oDetalhe->cep_endereco_sacado                  = $aDados->z01_cep;
          $oDetalhe->cidade_endereco_sacado               = $aDados->z01_munic; 
          $oDetalhe->uf_cidade_sacado                     = $aDados->z01_uf;
          
        } 
        
        $oDetalhe->observacoes                          = str_repeat(" ",40); //brancos 
        $oDetalhe->numero_dias_protesto                 = str_repeat("0",2);  //00
        $oDetalhe->fixo9                                = str_repeat(" ",1); 
        $oDetalhe->sequencial_registro                  = str_pad($iRegistro, 6, "0", STR_PAD_LEFT);
        if ( $oLayoutTxt->setByLineOfDBUtils($oDetalhe,3,7) == false ) {
          throw new Exception ("[ 5 ] - Erro ao gerar Detalhe do Arquivo");
        }        
    
        /**
         * Dados ref aos campos da linha Detalhe Auxiliar / Remessa / Registro 2
         * Com os dados dos Favorecidos
         */
        $iRegistro++;
        $oDetalheAuxiliar = new stdClass();
        $oDetalheAuxiliar->id                           = "2";
        $oDetalheAuxiliar->nosso_numero                 = str_pad($aDados->numbanco                         , 17, "0", STR_PAD_RIGHT);

        $oDetalheAuxiliar->banco_credito1               = str_pad($aDados->banco_TJ                         ,  3, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->camara_compensacao1          = str_repeat("0",3);
        $oDetalheAuxiliar->prefixo_agencia_credito1     = str_pad(substr($aDados->codagencia_TJ,-4)         ,  4, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->dv_prefixo_agencia_credito1  = str_pad($aDados->dvagencia_TJ                     ,  1, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->conta_credito1               = str_pad($aDados->conta_TJ                         , 11, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->dv_conta_credito1            = str_pad($aDados->dvconta_TJ                       ,  1, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->nome_favorecido1             = str_pad($aDados->nome_TJ                          , 30, " ", STR_PAD_RIGHT);
        $oDetalheAuxiliar->valor_partilha1              = str_pad(number_format(round($aDados->valor_TJ,2), 2, '', ''), 13, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->brancos1                     = str_repeat(" ",13);

        $oDetalheAuxiliar->banco_credito2               = str_pad($aDados->banco_FUNPERJ                     ,  3, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->camara_compensacao2          = str_repeat("0",3);
        $oDetalheAuxiliar->prefixo_agencia_credito2     = str_pad(substr($aDados->codagencia_FUNPERJ,-4)     ,  4, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->dv_prefixo_agencia_credito2  = str_pad($aDados->dvagencia_FUNPERJ                 ,  1, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->conta_credito2               = str_pad($aDados->conta_FUNPERJ                     , 11, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->dv_conta_credito2            = str_pad($aDados->dvconta_FUNPERJ                   ,  1, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->nome_favorecido2             = str_pad($aDados->nome_FUNPERJ                      , 30, " ", STR_PAD_RIGHT);
        $oDetalheAuxiliar->valor_partilha2              = str_pad(number_format(round($aDados->valor_FUNPERJ,2), 2, '', ''), 13, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->brancos2                     = str_repeat(" ",13);
                
        $oDetalheAuxiliar->banco_credito3               = str_pad($aDados->banco_CAARJ                       ,  3, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->camara_compensacao3          = str_repeat("0",3);
        $oDetalheAuxiliar->prefixo_agencia_credito3     = str_pad(substr($aDados->codagencia_CAARJ,-4)       ,  4, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->dv_prefixo_agencia_credito3  = str_pad($aDados->dvagencia_CAARJ                   ,  1, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->conta_credito3               = str_pad($aDados->conta_CAARJ                       , 11, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->dv_conta_credito3            = str_pad($aDados->dvconta_CAARJ                     ,  1, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->nome_favorecido3             = str_pad($aDados->nome_CAARJ                        , 30, " ", STR_PAD_RIGHT);
        $oDetalheAuxiliar->valor_partilha3              = str_pad(number_format(round($aDados->valor_CAARJ,2), 2, '', ''), 13, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->brancos3                     = str_repeat(" ",13);
       
        $oDetalheAuxiliar->banco_credito4               = str_pad($aDados->banco_FUNDPERJ                    ,  3, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->camara_compensacao4          = str_repeat("0",3);
        $oDetalheAuxiliar->prefixo_agencia_credito4     = str_pad(substr($aDados->codagencia_FUNDPERJ,-4)    ,  4, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->dv_prefixo_agencia_credito4  = str_pad($aDados->dvagencia_FUNDPERJ                ,  1, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->conta_credito4               = str_pad($aDados->conta_FUNDPERJ                    , 11, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->dv_conta_credito4            = str_pad($aDados->dvconta_FUNDPERJ                  ,  1, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->nome_favorecido4             = str_pad($aDados->nome_FUNDPERJ                     , 30, " ", STR_PAD_RIGHT);
        $oDetalheAuxiliar->valor_partilha4              = str_pad(number_format(round($aDados->valor_FUNDPERJ,2), 2, '', ''), 13, "0", STR_PAD_LEFT);
        $oDetalheAuxiliar->brancos4                     = str_repeat(" ",13);
        
        $oDetalheAuxiliar->tipo_documento_favorecido1   = "4";
        $oDetalheAuxiliar->numero_documento_favorecido1 = $aDados->cgccpf_TJ;
        
        $oDetalheAuxiliar->tipo_documento_favorecido2   = "4";
        $oDetalheAuxiliar->numero_documento_favorecido2 = $aDados->cgccpf_FUNPERJ;
        
        $oDetalheAuxiliar->tipo_documento_favorecido3   = "4";
        $oDetalheAuxiliar->numero_documento_favorecido3 = $aDados->cgccpf_CAARJ;
        
        $oDetalheAuxiliar->tipo_documento_favorecido4   = "4";
        $oDetalheAuxiliar->numero_documento_favorecido4 = $aDados->cgccpf_FUNDPERJ;
    
        $oDetalheAuxiliar->sequencial                   = str_pad($iRegistro ,  6, "0", STR_PAD_LEFT);
        if ( $oLayoutTxt->setByLineOfDBUtils($oDetalheAuxiliar,3,2) == false ) {
          throw new Exception ("[ 6 ] - Erro ao gerar Detalhe Auxiliar do Arquivo");
        }
        
        /*
         * Dados ref aos campos da linha Detalhe Auxiliar / Remessa / Registro 2
         * Somente com os dados da prefeitura
         */
        $iRegistro++;
        $oDetalheAuxiliar = new stdClass();
        $oDetalheAuxiliarPref->id                           = "2";
        $oDetalheAuxiliarPref->nosso_numero                 = str_pad($aDados->numbanco                       , 17, "0", STR_PAD_RIGHT);
        
        $oDetalheAuxiliarPref->banco_credito1               = str_pad($aDados->banco_INSTITUICAO                     ,  3, "0", STR_PAD_LEFT);
        $oDetalheAuxiliarPref->camara_compensacao1          = str_repeat("0",3);
        $oDetalheAuxiliarPref->prefixo_agencia_credito1     = str_pad(substr($aDados->codagencia_INSTITUICAO,-4)     ,  4, "0", STR_PAD_LEFT);
        $oDetalheAuxiliarPref->dv_prefixo_agencia_credito1  = str_pad($aDados->dvagencia_INSTITUICAO                 ,  1, "0", STR_PAD_LEFT);
        $oDetalheAuxiliarPref->conta_credito1               = str_pad($aDados->conta_INSTITUICAO                     , 11, "0", STR_PAD_LEFT);
        $oDetalheAuxiliarPref->dv_conta_credito1            = str_pad($aDados->dvconta_INSTITUICAO                   ,  1, "0", STR_PAD_LEFT);
        $oDetalheAuxiliarPref->nome_favorecido1             = str_pad($aDados->nome_INSTITUICAO                      , 30, " ", STR_PAD_RIGHT);
        $oDetalheAuxiliarPref->valor_partilha1              = str_pad(number_format(round($aDados->valor_recibo,2), 2, '', ''), 13, "0", STR_PAD_LEFT);
        $oDetalheAuxiliarPref->brancos1                     = str_repeat(" ",13);
        
        $oDetalheAuxiliarPref->banco_credito2               = str_repeat(" ",3);
        $oDetalheAuxiliarPref->camara_compensacao2          = str_repeat(" ",3);
        $oDetalheAuxiliarPref->prefixo_agencia_credito2     = str_repeat(" ",4);
        $oDetalheAuxiliarPref->dv_prefixo_agencia_credito2  = str_repeat(" ",1);
        $oDetalheAuxiliarPref->conta_credito2               = str_repeat(" ",11);
        $oDetalheAuxiliarPref->dv_conta_credito2            = str_repeat(" ",1);
        $oDetalheAuxiliarPref->nome_favorecido2             = str_repeat(" ",30);
        $oDetalheAuxiliarPref->valor_partilha2              = str_repeat(" ",13);
        $oDetalheAuxiliarPref->brancos2                     = str_repeat(" ",13);
       
        $oDetalheAuxiliarPref->banco_credito3               = str_repeat(" ",3);
        $oDetalheAuxiliarPref->camara_compensacao3          = str_repeat(" ",3);
        $oDetalheAuxiliarPref->prefixo_agencia_credito3     = str_repeat(" ",4);
        $oDetalheAuxiliarPref->dv_prefixo_agencia_credito3  = str_repeat(" ",1);
        $oDetalheAuxiliarPref->conta_credito3               = str_repeat(" ",11);
        $oDetalheAuxiliarPref->dv_conta_credito3            = str_repeat(" ",1);
        $oDetalheAuxiliarPref->nome_favorecido3             = str_repeat(" ",30);
        $oDetalheAuxiliarPref->valor_partilha3              = str_repeat(" ",13);
        $oDetalheAuxiliarPref->brancos3                     = str_repeat(" ",13);
           
        $oDetalheAuxiliarPref->banco_credito4               = str_repeat(" ",3);
        $oDetalheAuxiliarPref->camara_compensacao4          = str_repeat(" ",3);
        $oDetalheAuxiliarPref->prefixo_agencia_credito4     = str_repeat(" ",4);
        $oDetalheAuxiliarPref->dv_prefixo_agencia_credito4  = str_repeat(" ",1);
        $oDetalheAuxiliarPref->conta_credito4               = str_repeat(" ",11);
        $oDetalheAuxiliarPref->dv_conta_credito4            = str_repeat(" ",1);
        $oDetalheAuxiliarPref->nome_favorecido4             = str_repeat(" ",30);
        $oDetalheAuxiliarPref->valor_partilha4              = str_repeat(" ",13);
        $oDetalheAuxiliarPref->brancos4                     = str_repeat(" ",13);
        
        $oDetalheAuxiliarPref->tipo_documento_favorecido1   = "4";
        $oDetalheAuxiliarPref->numero_documento_favorecido1 = $oDbConfig->z01_cgccpf;
        
        $oDetalheAuxiliarPref->tipo_documento_favorecido2   = "4";
        $oDetalheAuxiliarPref->numero_documento_favorecido2 = str_repeat(" ",14);
        
        $oDetalheAuxiliarPref->tipo_documento_favorecido3   = "4";
        $oDetalheAuxiliarPref->numero_documento_favorecido3 = str_repeat(" ",14);
        
        $oDetalheAuxiliarPref->tipo_documento_favorecido4   = "4";
        $oDetalheAuxiliarPref->numero_documento_favorecido4 = str_repeat(" ",14);      
    
        $oDetalheAuxiliarPref->sequencial                   = str_pad($iRegistro, 6, "0", STR_PAD_LEFT);
        if ( $oLayoutTxt->setByLineOfDBUtils($oDetalheAuxiliarPref,3,2) == false ) {
          throw new Exception ("[ 6 ] - Erro ao gerar Detalhe Auxiliar referente aos dados da Prefeitura do Arquivo");
        }
        
    }
    
    $oTrailer = new stdClass();
    $oTrailer->id      = "9";  
    $oTrailer->brancos = str_repeat(" ",393); //fixo
    $oTrailer->sequencial_ultimo_registro = str_pad($iRegistro+1, 6, "0", STR_PAD_LEFT);
    if ( $oLayoutTxt->setByLineOfDBUtils($oTrailer,5,9) == false ) {
      throw new Exception("[ 7 ] Erro ao gerar trailer do Arquivo");
    }
    
    /**
     * Incluímos o registro da custa para o processo do foro gerado no arquivo 
     * na tabela partilhaarquivoreg
     * 
     */
    for ( $iInd=0; $iInd < count($aDados->PartilhaCustas); $iInd++) {
      
      $oDaoPartilhaArquivoReg->v79_partilhaarquivo           = $oDaoPartilhaArquivo->v78_sequencial;
      $oDaoPartilhaArquivoReg->v79_processoforopartilhacusta = $aDados->PartilhaCustas[$iInd];
      $oDaoPartilhaArquivoReg->incluir(null);
      if ( $oDaoPartilhaArquivoReg->erro_status == "0") {
        throw new Exception("[ 6 ] - Erro ao incluir dados em partilhaarquivoreg\n Erro: {$oDaoPartilhaArquivoReg->erro_msg} ");
      }
      
    }
    
    $iArquivo     = DBLargeObject::escrita("tmp/".$sNomeArq, $iOid);

  }
  
  /**
   * Metodo utilizado para geracao dos arquivos de remessa ao TJ referentes a cobranca registrada
   * 
   * @param date    $dtProcessamento  - Define a data dos registros de recibos que constarao no arquivo
   * @param string  $sNomeArq         - Define o nome do arquivo que será gerado 
   * 
   */
  public function geraArquivoRemessaTj($dtProcessamento,$sNomeArq) {

    $oDaoPartilhaArquivo     = db_utils::getDao('partilhaarquivo');
    $oDaoPartilhaArquivoReg  = db_utils::getDao('partilhaarquivoreg');
    $oDaoDbConfig            = db_utils::getDao('db_config');
  	$oLayoutTxt              = new db_layouttxt(101, "tmp/".$sNomeArq);
  	
    $aPartilhaCustas         = array();  
    $iRegistro = 1;
    
  	//Buscamos os dados da instituição
    $rsDbConfig = $oDaoDbConfig->sql_record($oDaoDbConfig->sql_query(db_getsession("DB_instit"), "*"));
    if ($oDaoDbConfig->numrows == 0) {
      throw new Exception("[ 2 ] - Erro ao buscar dados da instituição!\nErro: {$oDaoDbConfig->erro_msg}");
    }
    $oDbConfig = db_utils::fieldsMemory($rsDbConfig,0);    
    /*
     * Cadastramos o registro de geração do arquivo
     */
    $oDaoPartilhaArquivo->v78_dtgeracao = $dtProcessamento; 
    $oDaoPartilhaArquivo->v78_nomearq   = $sNomeArq;
    $oDaoPartilhaArquivo->v78_tipoarq   = 2;
    $oDaoPartilhaArquivo->incluir(null);
    if ($oDaoPartilhaArquivo->erro_status == "0") {
      throw new Exception("[ 3 ] - Erro ao inserir dados em Partilha Arquivo\n {$oDaoPartilhaArquivo->erro_msg}");
    }
    
    $aDtProcessamento = split("-",$dtProcessamento);
    $sDtProcessamento = $aDtProcessamento[2].$aDtProcessamento[1].$aDtProcessamento[0];

    $oHeader = new stdClass();
    $oHeader->id                         = "0";
    $oHeader->data_geracao_arquivo       = date("dmY",db_getsession("DB_datausu"));
    $oHeader->codigo_identificador_TJERJ = str_pad($oDbConfig->db21_codtj, 5, "0",STR_PAD_LEFT) ;
    $oHeader->nome_municipio             = str_pad($oDbConfig->munic, 30, " ", STR_PAD_RIGHT) ;
    $oHeader->versao_layout              = "0300";
    $oHeader->data_movimento             = $sDtProcessamento;
    $oHeader->sequencial_arquivo         = str_pad($oDaoPartilhaArquivo->v78_sequencial, 3, "0", STR_PAD_LEFT);
    $oHeader->brancos                    = str_repeat(" ",236);
    $oHeader->sequencial_registro        = "00001";
    if ( $oLayoutTxt->setByLineOfDBUtils($oHeader,1,"0") == false ) {
      throw new Exception ("[ 4 ] - Erro ao gerar Header do Arquivo");
    }
    
    $oRegistros = $this->getDadosReciboPartilha(2,$dtProcessamento);
    if (count($oRegistros) == 0) {
      throw new Exception("[ 5 ] - Nenhum registro de emissão com cobranca encontrado para a data informada!");
    }
    
    foreach ( $oRegistros as $aDados ) {
      
        $iRegistro++;
        
        $aDataVencimento         = split("-",$aDados->k00_dtpaga);
        $sDataVencimento         = $aDataVencimento[2].$aDataVencimento[1].$aDataVencimento[0];
        
        $aDataUltimaDistribuicao = split("-",$aDados->v70_data);
        $sDataUltimaDistribuicao = $aDataUltimaDistribuicao[2].$aDataUltimaDistribuicao[1].$aDataUltimaDistribuicao[0];
       
        $oDetalheTipo1 = new stdClass();
        $oDetalheTipo1->id                              = "1";
        $oDetalheTipo1->nosso_numero                    = str_pad($aDados->numbanco                                             , 17, "0", STR_PAD_RIGHT);
        $oDetalheTipo1->data_vencimento_boleto_bancario = $sDataVencimento; 
        $oDetalheTipo1->valor_total_boleto_bancario     = str_pad(number_format(round($aDados->valor_total_recibo,2), 2, '', ''), 14, "0", STR_PAD_LEFT);
  
        $oDetalheTipo1->conta_corrente_TJERJ            = str_pad($aDados->conta_TJ                                             , 10, "0", STR_PAD_LEFT);
        $oDetalheTipo1->valor_devido_TJERJ              = str_pad(number_format(round($aDados->valor_TJ,2), 2, '', '')          ,  9, "0", STR_PAD_LEFT);
                                                                                                                                
        $oDetalheTipo1->conta_corrente_CAARJ            = str_pad($aDados->conta_CAARJ                                          , 10, "0", STR_PAD_LEFT);
        $oDetalheTipo1->valor_devido_CAARJ              = str_pad(number_format(round($aDados->valor_CAARJ,2), 2, '', '')       ,  9, "0", STR_PAD_LEFT);
                                                                                                                                
        $oDetalheTipo1->conta_corrente_FUNDPERJ         = str_pad($aDados->conta_FUNDPERJ                                       , 10, "0", STR_PAD_LEFT);
        $oDetalheTipo1->valor_devido_FUNDPERJ           = str_pad(number_format(round($aDados->valor_FUNDPERJ,2), 2, '', '')    ,  9, "0", STR_PAD_LEFT);
                                                                                                                                
        $oDetalheTipo1->conta_corrente_FUNPERJ          = str_pad($aDados->conta_FUNPERJ                                        , 10, "0", STR_PAD_LEFT);
        $oDetalheTipo1->valor_devido_FUNPERJ            = str_pad(number_format(round($aDados->valor_FUNPERJ,2), 2, '', '')     ,  9, "0", STR_PAD_LEFT);
                                                                                                                                
        $oDetalheTipo1->reservado_TJERJ                 = str_repeat(" ",19);
        $oDetalheTipo1->brancos                         = str_repeat(" ",160);
        $oDetalheTipo1->sequencial_registro             = str_pad($iRegistro, 5, "0", STR_PAD_LEFT);
        if ( $oLayoutTxt->setByLineOfDBUtils($oDetalheTipo1,3,1) == false ) {
          throw new Exception ("[ 7 ] - Erro ao gerar Detalhe Tipo 1 do Arquivo");
        }        
    
        
        $iRegistro++;
        $oDetalheTipo3 = new stdClass();
        $oDetalheTipo3->id                                                = "3";
        $oDetalheTipo3->numero_certidao                                   = str_pad($aDados->v51_certidao                           , 10, "0", STR_PAD_LEFT);
        $oDetalheTipo3->numero_processo_tj                                = str_pad($aDados->codforo_antigo                         , 14, " ", STR_PAD_LEFT); //NUMERO ANTIGO DO PROCESSO DO TJ
        $oDetalheTipo3->data_ultima_distribuicao_processo                 = "$sDataUltimaDistribuicao"; 
        $oDetalheTipo3->valor_total_tributo_devido_processo               = str_pad(number_format(round($aDados->v70_valorinicial,2), 2, '', ''), 12, "0", STR_PAD_LEFT);
        $oDetalheTipo3->numero_parcela                                    = "999";
        $oDetalheTipo3->total_parcelas                                    = "001";
        
        $oDetalheTipo3->codigo_receita_CAARJ                              = str_pad($aDados->codigo_receita_CAARJ              , 5, "0", STR_PAD_LEFT);
        $oDetalheTipo3->valor_CAARJ                                       = str_pad(number_format(round($aDados->valor_CAARJ,2), 2, '', ''), 9, "0", STR_PAD_LEFT) ;
        
        $oDetalheTipo3->codigo_receita_atos_oficiais_justica_avaliadores  = str_pad($aDados->codigo_receita_atos_oficiais_justica_avaliadores                      , 5, "0", STR_PAD_LEFT) ;
        $oDetalheTipo3->valor_receita_atos_oficiais_justica_avaliadores   = str_pad(number_format(round($aDados->valor_receita_atos_oficiais_justica_avaliadores,2), 2, '', ''), 9, "0", STR_PAD_LEFT) ;
        
        $oDetalheTipo3->codigo_receita_atos_escrivaes_divida_ativa        = str_pad($aDados->codigo_receita_atos_escrivaes_divida_ativa                             , 5, "0", STR_PAD_LEFT);      
        $oDetalheTipo3->valor_receita_atos_escrivaes_divida_ativa         = str_pad(number_format(round($aDados->valor_receita_atos_escrivaes_divida_ativa,2), 2, '', ''), 9, "0", STR_PAD_LEFT);
         
        $oDetalheTipo3->codigo_receita_taxa_judiciaria                    = str_pad($aDados->codigo_receita_taxa_judiciaria                                        , 5, "0", STR_PAD_LEFT);
        $oDetalheTipo3->valor_receita_taxa_judiciaria                     = str_pad(number_format(round($aDados->valor_receita_taxa_judiciaria,2), 2, '', ''), 9, "0", STR_PAD_LEFT);
              
        $oDetalheTipo3->codigo_receita_atos_distribuidores                = str_pad($aDados->codigo_receita_atos_distribuidores                                    , 10, "0", STR_PAD_LEFT);
        $oDetalheTipo3->valor_receita_atos_distribuidores                 = str_pad(number_format(round($aDados->valor_receita_atos_distribuidores,2), 2, '', ''), 9, "0", STR_PAD_LEFT);
        
        $oDetalheTipo3->conta_corrente_acrescimo_20                       = "";
        $oDetalheTipo3->valor_corrente_acrescimo_20                       = str_pad(number_format(round($aDados->valor_corrente_acrescimo_20,2), 2, '', ''),  9, "0", STR_PAD_LEFT);
        
        $oDetalheTipo3->conta_FUNPERJ                                     = str_pad($aDados->conta_FUNPERJ                                                         , 10, "0", STR_PAD_LEFT);
        $oDetalheTipo3->valor_FUNPERJ                                     = str_pad(number_format(round($aDados->valor_FUNPERJ,2), 2, '', ''),  9, "0", STR_PAD_LEFT);
        
        $oDetalheTipo3->conta_FUNDPERJ                                    = str_pad($aDados->conta_FUNDPERJ                                                        , 10, "0", STR_PAD_LEFT);
        $oDetalheTipo3->valor_FUNDPERJ                                    = str_pad(number_format(round($aDados->valor_FUNDPERJ,2), 2, '', ''),  9, "0", STR_PAD_LEFT);
  
        $oDetalheTipo3->reservado_TJERJ                                   = str_pad($aDados->codforo_novo                                                          , 25, " ", STR_PAD_RIGHT) ; //NUMERO DO PROCESSO DO FORO NOVO
        $oDetalheTipo3->brancos                                           = str_repeat(" ",87);
        $oDetalheTipo3->sequencial_registro                               = str_pad($iRegistro, 5, "0", STR_PAD_LEFT);
        if ( $oLayoutTxt->setByLineOfDBUtils($oDetalheTipo3,3,3) == false ) {
          throw new Exception ("[ 8 ] - Erro ao gerar Detalhe Tipo 2 do Arquivo");
        }

    }
        
    $oTrailer = new stdClass();
    $oTrailer->id                        = "9";
    $oTrailer->brancos                   = str_repeat(" ",294);
    $oTrailer->sequencial_registro       = str_pad($iRegistro+1, 5, "0", STR_PAD_LEFT);
    if ( $oLayoutTxt->setByLineOfDBUtils($oTrailer,5,9) == false ) {
      throw new Exception("[ 9 ] Erro ao gerar trailer do Arquivo");
    }
    
    /*
     * Incluímos o registro da custa para o processo do foro gerado no arquivo 
     * na tabela partilhaarquivoreg
     * 
     */
    for ( $iInd=0; $iInd < count($aDados->PartilhaCustas); $iInd++) {
      
      $oDaoPartilhaArquivoReg->v79_partilhaarquivo           = $oDaoPartilhaArquivo->v78_sequencial;
      $oDaoPartilhaArquivoReg->v79_processoforopartilhacusta = $aDados->PartilhaCustas[$iInd];
      $oDaoPartilhaArquivoReg->incluir(null);
      if ( $oDaoPartilhaArquivoReg->erro_status == "0") {
        throw new Exception("[ 6 ] - Erro ao incluir dados em partilhaarquivoreg\n Erro: {$oDaoPartilhaArquivoReg->erro_msg} ");
      }
    } 
       
  }
  
  /**
   * 
   * Método utilizado para buscar as informações necessárias para a geração dos arquivos
   * @param integer $iTipoArq        - Tipo de busca de informações, 1: Banco 2: TJ
   * @param date    $dtProcessamento - Data da emissão dos recibos
   */
  public function getDadosReciboPartilha($iTipoArq,$dtProcessamento) {

    $oProcessoForoPartilhaCusta = db_utils::getDao('processoforopartilhacusta');
    
    $aRegistrosAgrupados        = array();
    $aPatrilhaCustas            = array();
    
    $sCampos  = " recibopaga.k00_numpre,                                                         \n";
    $sCampos .= " recibopaga.k00_numnov,                                                         \n";
    $sCampos .= " recibopaga.k00_dtvenc,                                                         \n";
    $sCampos .= " recibopagaboleto.k138_data                          as k00_dtoper,             \n";
    $sCampos .= " recibopaga.k00_dtpaga,                                                         \n";
    $sCampos .= " cgmrecibopaga.z01_numcgm,                                                      \n";
    $sCampos .= " cgmrecibopaga.z01_nome,                                                        \n";
    $sCampos .= " cgmrecibopaga.z01_ender,                                                       \n";
    $sCampos .= " cgmrecibopaga.z01_numero,                                                      \n";
    $sCampos .= " cgmrecibopaga.z01_bairro,                                                      \n";
    $sCampos .= " cgmrecibopaga.z01_cep,                                                         \n";
    $sCampos .= " cgmrecibopaga.z01_munic,                                                       \n";
    $sCampos .= " cgmrecibopaga.z01_uf,                                                          \n";
    $sCampos .= " inicialcert.v51_inicial,                                                       \n";
    $sCampos .= " inicialcert.v51_certidao,                                                      \n";
    $sCampos .= " processoforo.v70_sequencial,                                                   \n";
    $sCampos .= " processoforo.v70_data,                                                         \n";
    $sCampos .= " processoforo.v70_valorinicial,                                                 \n";
    $sCampos .= " processoforopartilhacusta.v77_sequencial,                                      \n";
    $sCampos .= " processoforopartilhacusta.v77_processoforopartilha,                            \n";
    $sCampos .= " processoforopartilhacusta.v77_taxa                  as taxa_codigo,            \n";
    $sCampos .= " taxa.ar36_receita                                   as taxa_receita,           \n";
    $sCampos .= " processoforopartilhacusta.v77_valor                 as taxa_valor,             \n";
    $sCampos .= " favorecido.v86_numcgm                               as favorecido_numcgm,      \n";
    $sCampos .= " favorecido.v86_containterna                         as favorecido_containterna,\n";
    $sCampos .= " cgmfavorecido.z01_nome                              as favorecido_nome,        \n";
    $sCampos .= " cgmfavorecido.z01_cgccpf                            as favorecido_cgccpf,      \n";
    $sCampos .= " bancoagencia.db89_db_bancos                         as favorecido_banco,       \n"; 
    $sCampos .= " bancoagencia.db89_codagencia                        as favorecido_agencia,     \n";
    $sCampos .= " bancoagencia.db89_digito                            as favorecido_agenciadv,   \n";
    $sCampos .= " contabancaria.db83_conta                            as favorecido_conta,       \n";
    $sCampos .= " coalesce(contabancaria.db83_dvconta,'0')            as favorecido_contadv      \n";
    
    
    /*
     * Filtramos os registros: 
     *  - somente retornados os recibos gerados na data informada
     *  - somente retornados os registros que não estiverem na tabela partilhaarquivoreg, ou seja, que não foram enviados
     *  para o mesmo tipo de arquivo. 
     */   
    $sWhere    = " recibopagaboleto.k138_data = '{$dtProcessamento}'                                                      \n";
    $sWhere   .= " and processoforopartilha.v76_dtpagamento is null                                                       \n";
    $sWhere   .= " and processoforopartilha.v76_tipolancamento = 1                                                        \n";
    $sWhere   .= " and cancrecibopaga.k134_sequencial is null                                                             \n";
    $sWhere   .= " and not exists ( select 1                                                                              \n"; 
    $sWhere   .= "                    from partilhaarquivoreg as p                                                        \n";
    $sWhere   .= "                   inner join partilhaarquivo on partilhaarquivo.v78_sequencial = p.v79_partilhaarquivo \n";
    $sWhere   .= "                   where partilhaarquivo.v78_tipoarq = {$iTipoArq}                                      \n";
    $sWhere   .= "                     and p.v79_processoforopartilhacusta = processoforopartilhacusta.v77_sequencial)    \n";

    $sOrderBy  = " recibopaga.k00_numnov, cgmrecibopaga.z01_nome, cgmfavorecido.z01_nome";
    $sSqlDados = $oProcessoForoPartilhaCusta->sql_query_recibo_banco(null, "distinct {$sCampos}", $sOrderBy, $sWhere);

    $rsDados   = $oProcessoForoPartilhaCusta->sql_record($sSqlDados);
    if ( $rsDados && pg_num_rows($rsDados) > 0 ) {
      
      //Buscamos os dados da instituição
      $oDaoDbConfig         = db_utils::getDao('db_config');
      $rsDbConfig = $oDaoDbConfig->sql_record($oDaoDbConfig->sql_query(db_getsession("DB_instit"), " z01_numcgm, z01_cgccpf "));
      if ($oDaoDbConfig->numrows == 0) {
        throw new Exception("[ 1 ] - Erro ao buscar dados da instituição!\nErro: {$oDaoDbConfig->erro_msg}");
      }
      $oDbConfig = db_utils::fieldsMemory($rsDbConfig,0);

      $oDaoFavorecido       = db_utils::getDao('favorecido');
      $rsFavorecido = $oDaoFavorecido->sql_record($oDaoFavorecido->sql_query_dados(null,"*",null,"v86_numcgm = {$oDbConfig->z01_numcgm}"));
      if ($oDaoFavorecido->numrows == 0) {
        throw new Exception("[ 2 ] - Não encontrado cadastro de Favorecido para a instituição! ");
      }
      $oDadosInstituicao = db_utils::fieldsMemory($rsFavorecido,0);

      if ($iTipoArq == 2 ) {

        $this->setIdTaxasEspecificasTJ();

      }

      $aRecibo    = array();
      $aRegistros = array();

      for ($iInd = 0; $iInd < pg_num_rows($rsDados); $iInd ++) {

        $oRegistro = db_utils::fieldsMemory($rsDados, $iInd);

        if (!isset($aRegistros[$oRegistro->k00_numnov])) { 
          $oDados     = new stdClass();
          $aTaxas     = array();
        } else {
          $oDados = $aRegistros[$oRegistro->k00_numnov];
        }

        $sSqlArrebanco   = "select k00_numbco                                       ";                       
        $sSqlArrebanco  .= "  from arrebanco                                        ";                       
        $sSqlArrebanco  .= " where arrebanco.k00_numpre = {$oRegistro->k00_numnov}  ";                       
        $sSqlArrebanco  .= "   and round(cast(trim(k00_numbco) as numeric)) <> 0    ";                       
        $sSqlArrebanco  .= " order by round(cast(trim(k00_numbco) as numeric)) desc ";
        $sSqlArrebanco  .= " limit 1                                                ";

        $rsSqlArrebanco  = db_query($sSqlArrebanco);
        if ( $rsSqlArrebanco && pg_num_rows($rsSqlArrebanco) > 0 ){
          $oRegistro->numbanco  = db_utils::fieldsMemory($rsSqlArrebanco, 0)->k00_numbco;
        }else{
          throw new Exception("[ 99 ] - Erro ao buscar dados do banco!\nErro: Numpre do recibo não encontrado na tabela arrebanco");
        }

        $sSqlRecibopaga  = "select coalesce(sum(k00_valor),0) as n_valor_recibo     ";   
        $sSqlRecibopaga .= " from recibopaga as r                                   ";  
        $sSqlRecibopaga .= "where r.k00_numnov ={$oRegistro->k00_numnov}            ";
        $rsSqlRecibopaga = db_query($sSqlRecibopaga);
        if ( $rsSqlRecibopaga && pg_num_rows($rsSqlRecibopaga) > 0 ){
          $oRegistro->valor_recibo = db_utils::fieldsMemory($rsSqlRecibopaga,0)->n_valor_recibo;
        }else{
          throw new Exception("[ 98 ] - Erro ao buscar valor do recibo!\nErro: ");
        }

        $sSqlCustas      = "select sum(v77_valor)                              ";                            
        $sSqlCustas     .= "  from processoforopartilhacusta as p                   ";                            
        $sSqlCustas     .= " where p.v77_numnov = {$oRegistro->k00_numnov}          ";
        $rsSqlCustas     = db_query($sSqlCustas);
        if ( $rsSqlCustas && pg_num_rows($rsSqlCustas) > 0 ){
          $oRegistro->valor_total_taxas = db_utils::fieldsMemory($rsSqlCustas, 0)->sum;
        }else{
          throw new Exception("[ 97 ] - Erro ao buscar dados das taxas!\nErro: ");
        }


        //Dados do Recibo

        $sSqlValorTaxaBancaria = "select k00_txban 
                                    from recibopaga 
                                         inner join arrecad   on recibopaga.k00_numpre = arrecad.k00_numpre 
                                                             and recibopaga.k00_numpar = arrecad.k00_numpar 
                                         inner join arretipo  on arrecad.k00_tipo      = arretipo.k00_tipo 
                                   where recibopaga.k00_numnov = {$oRegistro->k00_numnov} 
                                group by arretipo.k00_tipo;"; 
        $rsValorTaxa           = db_query($sSqlValorTaxaBancaria);


        if ( !$rsValorTaxa ) {     
          throw new Exception("[ 98.5 ] - Erro ao buscar valor da Taxa Bancária\nErro: ");
        }

        if ( pg_num_rows($rsValorTaxa) == 0 ) {
          $nValorTaxaEmissao1 = 0;
        }
        $nValorTaxaEmissao = db_utils::fieldsMemory($rsValorTaxa,0)->k00_txban;

        $oDados->k00_numpre               = $oRegistro->k00_numpre;
        $oDados->k00_numnov               = $oRegistro->k00_numnov;
        $oDados->k00_dtvenc               = $oRegistro->k00_dtvenc;
        $oDados->k00_dtoper               = $oRegistro->k00_dtoper;
        $oDados->k00_dtpaga               = $oRegistro->k00_dtpaga;          
        $oDados->v77_processoforopartilha = $oRegistro->v77_processoforopartilha;
        $oDados->v51_inicial              = $oRegistro->v51_inicial;
        $oDados->v51_certidao             = $oRegistro->v51_certidao;
        $oDados->v70_data                 = $oRegistro->v70_data;
        $oDados->numbanco                 = $oRegistro->numbanco;
        $oDados->valor_recibo             = $oRegistro->valor_recibo + $nValorTaxaEmissao;
        $oDados->valor_total_taxa         = $oRegistro->valor_total_taxas;
        $oDados->valor_total_recibo       = $oDados->valor_recibo + $oRegistro->valor_total_taxas;  

        //Endereço do sacado por cgm
        $oDados->z01_numcgm               = $oRegistro->z01_numcgm;
        $oDados->z01_nome                 = $oRegistro->z01_nome;
        $oDados->z01_ender                = $oRegistro->z01_ender.",".$oRegistro->z01_numero;
        $oDados->z01_cep                  = $oRegistro->z01_cep;
        $oDados->z01_munic                = $oRegistro->z01_munic;
        $oDados->z01_uf                   = $oRegistro->z01_uf;

        /*
         * 
         * Se o tipo de geração for de arquivos de remessa ao Banco
         * Buscamos o endereço do sacado de acordo com a Origem do Numpre.
         * 
         * Por Default essa informação já é preenchida com os dados do CGM do recibo 
         * Verificamos se o numpre possui matricula ou inscrição e utilizamos as informações de acordo essa vinculação
         * 
         */

        if ( $iTipoArq == 1 ) {

          $sSqlOrigemNumpre  = " select fc_origem_numpre as retorno                ";
          $sSqlOrigemNumpre .= "   from fc_origem_numpre({$oRegistro->k00_numpre},1) ";
          $rsOrigemNumpre    = db_query($sSqlOrigemNumpre);
          $oOrigemNumpre     = db_utils::fieldsMemory($rsOrigemNumpre,0)->retorno;
          if (count($oOrigemNumpre) > 0) {

            $sOrigemNumpre = str_replace(":","",str_replace("*",",",$oOrigemNumpre));
            $iPosM = strpos($sOrigemNumpre,"M");
            $iPosI = strpos($sOrigemNumpre,"I");

            if ($iPosM > 0) {

              $sOrigemNumpreMatric = substr(str_replace("M","",$sOrigemNumpre),$iPosM, strlen($sOrigemNumpre));
              $aMatric = split(",",$sOrigemNumpreMatric);

              $sSqlProprietario  = " select z01_numcgm,                 "; 
              $sSqlProprietario .= "        z01_nome,                   ";
              $sSqlProprietario .= "        z01_ender,                  ";
              $sSqlProprietario .= "        z01_numero,                 ";
              $sSqlProprietario .= "        z01_cep,                    ";
              $sSqlProprietario .= "        z01_munic,                  ";
              $sSqlProprietario .= "        z01_uf                      "; 
              $sSqlProprietario .= "   from proprietario                ";  
              $sSqlProprietario .= "  where j01_matric = {$aMatric[0]}  ";
              $rsProprietario    = db_query($sSqlProprietario);
              if (pg_num_rows($rsProprietario) > 0) {
                $oDadosProprietario = db_utils::fieldsMemory($rsProprietario,0);

                $oDados->z01_numcgm = $oDadosProprietario->z01_numcgm;
                $oDados->z01_nome   = $oDadosProprietario->z01_nome;
                $oDados->z01_ender  = $oDadosProprietario->z01_ender.",".$oDadosProprietario->z01_numero;
                $oDados->z01_cep    = $oDadosProprietario->z01_cep;
                $oDados->z01_munic  = $oDadosProprietario->z01_munic;
                $oDados->z01_uf     = $oDadosProprietario->z01_uf;


              }

            }

            if ($iPosI > 0 && $iPosM == 0) {

              $sOrigemNumpreInscr = substr(str_replace("I","",$sOrigemNumpre),$iPosM, strlen($sOrigemNumpre));
              $aInscr = split(",",$sOrigemNumpreInscr);

              $sSqlEmpresa  = " select z01_numcgm,                 "; 
              $sSqlEmpresa .= "        z01_nome,                   ";
              $sSqlEmpresa .= "        z01_ender,                  ";
              $sSqlEmpresa .= "        z01_numero,                 ";                
              $sSqlEmpresa .= "        z01_cep,                    ";
              $sSqlEmpresa .= "        z01_munic,                  ";
              $sSqlEmpresa .= "        z01_uf                      "; 
              $sSqlEmpresa .= "  from empresa                      ";  
              $sSqlEmpresa .= " where q02_inscr = {$aInscr[0]}     ";
              $rsEmpresa    = db_query($sSqlEmpresa);
              if (pg_num_rows($rsEmpresa) > 0) {
                $oDadosEmpresa = db_utils::fieldsMemory($rsEmpresa,0);

                $oDados->z01_numcgm = $oDadosEmpresa->z01_numcgm;
                $oDados->z01_nome   = $oDadosEmpresa->z01_nome;
                $oDados->z01_ender  = $oDadosEmpresa->z01_ender.",".$oDadosEmpresa->z01_numero;
                $oDados->z01_cep    = $oDadosEmpresa->z01_cep;
                $oDados->z01_munic  = $oDadosEmpresa->z01_munic;
                $oDados->z01_uf     = $oDadosEmpresa->z01_uf;

              }

            }

          }

        }

        /*
         * 
         * Se o tipo de geração for de arquivos de remessa ao TJ
         * Buscamos os numeros dos processos do foro antigo e atual
         * 
         * A lógica é a seguinte:
         * 1 - Numero do processo do foro Atualizado (NOVO):
         *     - Se o campo v85_codforo da tabela processoforocodforoant for maior ou igual a 0(zero)
         *         v85_codforo = 0 : Processo do foro cadastrado novo (Já com o numero no novo formato)
         *         v85_codforo > 0 : Processo do foro alterado (Em tese, alterado para o novo numero com o novo formato)
         * 2 - Numero do processo do foro Desatualizado (ANTIGO):
         *     - Se o campo v85_codforo da tabela processoforocodforoant estiver nulo, significa que o processo é 
         *     antigo e não teve seu numero alterado.   
         *     
         */
        if ($iTipoArq == 2) {

          $sSqlProcesso  = "select case                                                                 ";
          $sSqlProcesso .= "         when v85_codforo > 0                                               "; 
          $sSqlProcesso .= "           then v85_codforo                                                 ";
          $sSqlProcesso .= "         else                                                               ";
          $sSqlProcesso .= "           case                                                             ";
          $sSqlProcesso .= "             when v85_codforo is null                                       ";
          $sSqlProcesso .= "               then v70_codforo                                             ";
          $sSqlProcesso .= "             else ''                                                        ";                                                 
          $sSqlProcesso .= "           end                                                              ";
          $sSqlProcesso .= "       end as codforo_antigo,                                               ";
          $sSqlProcesso .= "       case                                                                 ";
          $sSqlProcesso .= "         when v85_codforo >= 0                                              ";   
          $sSqlProcesso .= "           then v70_codforo                                                 ";
          $sSqlProcesso .= "         else ''                                                            ";
          $sSqlProcesso .= "       end as codforo_novo                                                  ";
          $sSqlProcesso .= "  from processoforo                                                         ";
          $sSqlProcesso .= "  left join processoforocodforoant on v70_sequencial = v85_processoforo     "; 
          $sSqlProcesso .= " where v70_sequencial = {$oRegistro->v70_sequencial}                        ";
          $rsProcesso = db_query($sSqlProcesso) ;
          $aProcesso = db_utils::fieldsmemory($rsProcesso,0);
          $oDados->codforo_antigo            = $aProcesso->codforo_antigo;
          $oDados->codforo_novo              = $aProcesso->codforo_novo;

          /*
           * Quando o campo v70_valorinicial for nulo, buscamos o valor dessa inicial dando um "sum()" no campo k00_valor 
           * da tabela arreforo das certidões que compõe essa inicial
           */
          $oDados->v70_valorinicial         = $oRegistro->v70_valorinicial;
          if ( empty($oRegistro->v70_valorinicial)) {

            $sSqlArreforo    = " select sum(k00_valor) as valor_inicial                                     "; 
            $sSqlArreforo   .= "   from arreforo                                                            ";
            $sSqlArreforo   .= " inner join inicialcert on inicialcert.v51_certidao = arreforo.k00_certidao ";
            $sSqlArreforo   .= " where v51_inicial = {$oRegistro->v51_inicial}                              ";
            $rsValorArreforo = db_query($sSqlArreforo);
            $oDados->v70_valorinicial = db_utils::fieldsMemory($rsValorArreforo,0)->valor_inicial;

          }

        }

          //Dados dos Favorecidos
          /*
           * Separamos as informações dos favorecidos através do seu CPF/CNPJ
           * Serão 5 favorecidos:
           * 1 - TJ         - 28538734000148
           * 2 - CAARJ      - 08778206000159
           * 3 - FUNPERJ    - 33755174000113
           * 4 - FUNDPERJ   - 31443526000170
           * 5 - INSTITUICAO - Dados do cadastro da instituição
           */
           
          switch (trim($oRegistro->favorecido_cgccpf)) {
            
            //TJ 
            case "28538734000148":
              
             if (!in_array($oRegistro->taxa_codigo,$aTaxas)) {  
               $oDados->taxa_TJ            = $oRegistro->taxa_codigo;
               $oDados->numcgm_TJ          = $oRegistro->favorecido_numcgm;
               $oDados->containterna_TJ    = $oRegistro->favorecido_containterna;
               $oDados->nome_TJ            = $oRegistro->favorecido_nome;
               $oDados->cgccpf_TJ          = $oRegistro->favorecido_cgccpf;
               $oDados->banco_TJ           = $oRegistro->favorecido_banco;
               $oDados->codagencia_TJ      = substr($oRegistro->favorecido_agencia,-4);
               $oDados->dvagencia_TJ       = $oRegistro->favorecido_agenciadv;
               $oDados->conta_TJ           = $oRegistro->favorecido_conta;
               $oDados->dvconta_TJ         = $oRegistro->favorecido_contadv;
               $oDados->codigo_receita_TJ  = $oRegistro->taxa_receita;
               $oDados->valor_TJ          += $oRegistro->taxa_valor;
               
               
               /*
                * 
                * Informamos estas variaveis no vetor dos dados somente se o tipo de geração for de arquivo do TJ
                * 
                */
               if ($iTipoArq == 2) {
               
                 if ( $oRegistro->taxa_codigo == $this->iIdAtosDistribuidores ) {
                   $oDados->codigo_receita_atos_distribuidores                = $oRegistro->taxa_receita;
                   $oDados->valor_receita_atos_distribuidores                 = $oRegistro->taxa_valor;
                 }  
                 
                 if ( $oRegistro->taxa_codigo == $this->iIdTaxaJudiciaria ) {
                   $oDados->codigo_receita_taxa_judiciaria                    = $oRegistro->taxa_receita;
                   $oDados->valor_receita_taxa_judiciaria                     = $oRegistro->taxa_valor;
                 }  
                 
                 if ( $oRegistro->taxa_codigo == $this->iIdAtosEscrivaesDividaAtiva ) {
                   $oDados->codigo_receita_atos_escrivaes_divida_ativa        = $oRegistro->taxa_receita;      
                   $oDados->valor_receita_atos_escrivaes_divida_ativa         = $oRegistro->taxa_valor;
                 }  
                 
                 if ( $oRegistro->taxa_codigo == $this->iIdAcrescimo ) {
                   $oDados->conta_corrente_acrescimo_20                       = "";
                   $oDados->valor_corrente_acrescimo_20                       = $oRegistro->taxa_valor;
                 }  
                 
                 if ( $oRegistro->taxa_codigo == $this->iIdAtosOficiaisJusticaAvaliadores ) {
                   $oDados->codigo_receita_atos_oficiais_justica_avaliadores  = $oRegistro->taxa_receita;
                   $oDados->valor_receita_atos_oficiais_justica_avaliadores   = $oRegistro->taxa_valor;
                 }                            
                
               }
               
               $aTaxas[] = $oRegistro->taxa_codigo;
             }
               
            break;

            //FUNPERJ
            case "08778206000159":

             $oDados->taxa_FUNPERJ             = $oRegistro->taxa_codigo;
             $oDados->numcgm_FUNPERJ           = $oRegistro->favorecido_numcgm;
             $oDados->containterna_FUNPERJ     = $oRegistro->favorecido_containterna;
             $oDados->nome_FUNPERJ             = $oRegistro->favorecido_nome;
             $oDados->cgccpf_FUNPERJ           = $oRegistro->favorecido_cgccpf;
             $oDados->banco_FUNPERJ            = $oRegistro->favorecido_banco;
             $oDados->codagencia_FUNPERJ       = substr($oRegistro->favorecido_agencia,-4);
             $oDados->dvagencia_FUNPERJ        = $oRegistro->favorecido_agenciadv;
             $oDados->conta_FUNPERJ            = $oRegistro->favorecido_conta;
             $oDados->dvconta_FUNPERJ          = $oRegistro->favorecido_contadv;
             $oDados->codigo_receita_FUNPERJ   = $oRegistro->taxa_receita;
             $oDados->valor_FUNPERJ            = $oRegistro->taxa_valor;
             
            break;
            
            //CAARJ
            case  "33755174000113":
              
             $oDados->taxa_CAARJ               = $oRegistro->taxa_codigo;
             $oDados->numcgm_CAARJ             = $oRegistro->favorecido_numcgm;
             $oDados->containterna_CAARJ       = $oRegistro->favorecido_containterna;
             $oDados->nome_CAARJ               = $oRegistro->favorecido_nome;
             $oDados->cgccpf_CAARJ             = $oRegistro->favorecido_cgccpf;
             $oDados->banco_CAARJ              = $oRegistro->favorecido_banco;
             $oDados->codagencia_CAARJ         = substr($oRegistro->favorecido_agencia,-4);
             $oDados->dvagencia_CAARJ          = $oRegistro->favorecido_agenciadv;
             $oDados->conta_CAARJ              = $oRegistro->favorecido_conta;
             $oDados->dvconta_CAARJ            = $oRegistro->favorecido_contadv;
             $oDados->codigo_receita_CAARJ     = $oRegistro->taxa_receita;
             $oDados->valor_CAARJ              = $oRegistro->taxa_valor;
                           
            break;
            
            //FUNDPERJ
            case "31443526000170":
              
             $oDados->taxa_FUNDPERJ            = $oRegistro->taxa_codigo;
             $oDados->numcgm_FUNDPERJ          = $oRegistro->favorecido_numcgm;
             $oDados->containterna_FUNDPERJ    = $oRegistro->favorecido_containterna;
             $oDados->nome_FUNDPERJ            = $oRegistro->favorecido_nome;
             $oDados->cgccpf_FUNDPERJ          = $oRegistro->favorecido_cgccpf;
             $oDados->banco_FUNDPERJ           = $oRegistro->favorecido_banco;
             $oDados->codagencia_FUNDPERJ      = substr($oRegistro->favorecido_agencia,-4);
             $oDados->dvagencia_FUNDPERJ       = $oRegistro->favorecido_agenciadv;
             $oDados->conta_FUNDPERJ           = $oRegistro->favorecido_conta;
             $oDados->dvconta_FUNDPERJ         = $oRegistro->favorecido_contadv;
             $oDados->codigo_receita_FUNDPERJ  = $oRegistro->taxa_receita;
             $oDados->valor_FUNDPERJ           = $oRegistro->taxa_valor;
                           
            break;
            
          }
          
          /*
           * Montamos as informações referentes a instituição
           */
          $oDados->taxa_INSTITUICAO            = "";
          $oDados->numcgm_INSTITUICAO          = $oDadosInstituicao->v86_numcgm;
          $oDados->containterna_INSTITUICAO    = $oDadosInstituicao->v86_containterna;
          $oDados->nome_INSTITUICAO            = $oDadosInstituicao->z01_nome;
          $oDados->cgccpf_INSTITUICAO          = $oDadosInstituicao->z01_cgccpf;
          $oDados->banco_INSTITUICAO           = $oDadosInstituicao->db90_codban;
          $oDados->codagencia_INSTITUICAO      = substr($oDadosInstituicao->db89_codagencia,-4);
          $oDados->dvagencia_INSTITUICAO       = $oDadosInstituicao->db89_digito;
          $oDados->conta_INSTITUICAO           = $oDadosInstituicao->db83_conta;
          $oDados->dvconta_INSTITUICAO         = $oDadosInstituicao->db83_dvconta;
          $oDados->codigo_receita_INSTITUICAO  = "";
          $oDados->valor_INSTITUICAO           = "";
             
          if (!in_array($oRegistro->v77_sequencial,$aPatrilhaCustas)) {
            $aPatrilhaCustas[] = $oRegistro->v77_sequencial;
            $oDados->PartilhaCustas = $aPatrilhaCustas;
          } 
          $aRegistrosAgrupados[$oDados->k00_numnov] = $oDados;
          if (!in_array($oRegistro->v77_sequencial,$aPatrilhaCustas)) {
            $aPatrilhaCustas[] = $oRegistro->v77_sequencial;
            $oDados->PartilhaCustas = $aPatrilhaCustas;
          }          

          $aRegistros[$oRegistro->k00_numnov] = $oDados;      
      }
      
    }  

    return $aRegistrosAgrupados;      
  }
  
  /**
   * 
   * Método chamado para setar o código das taxas específicas para a geração do arquivo do TJ
   * 
   * O código dessas taxas é informado no valor default dos campos que utilizarao essas informações. 
   * Layout 101
   * Código dos campos: 5900,5902,5904,5906,5909
   * 
   */
  function setIdTaxasEspecificasTJ() {
    
    try {
      
      $oDBLayoutCampos      = db_utils::getDao("db_layoutcampos");
      $rsAtosDistribuidores = $oDBLayoutCampos->sql_record($oDBLayoutCampos->sql_query_file(5906,"db52_default"));
      if ($oDBLayoutCampos->numrows == 0 ) {
        throw new Exception("Nenhum registro da Linha 5906 encontrado no Layout 101!");
      }
          
      $rsTaxaJudiciaria  = $oDBLayoutCampos->sql_record($oDBLayoutCampos->sql_query_file(5904,"db52_default"));
      if ($oDBLayoutCampos->numrows == 0 ) {
        throw new Exception("Nenhum registro da Linha 5904 encontrado no Layout 101!");
      }
          
      $rsAtosEscrivaesDividaAtiva = $oDBLayoutCampos->sql_record($oDBLayoutCampos->sql_query_file(5902,"db52_default"));
      if ($oDBLayoutCampos->numrows == 0 ) {
        throw new Exception("Nenhum registro da Linha 5902 encontrado no Layout 101!");
      }
          
      $rsAcrescimo = $oDBLayoutCampos->sql_record($oDBLayoutCampos->sql_query_file(5909,"db52_default"));
      if ($oDBLayoutCampos->numrows == 0 ) {
        throw new Exception("Nenhum registro da Linha 5909 encontrado no Layout 101!");
      }
          
      $rsAtosOficiaisJusticaAvaliadores = $oDBLayoutCampos->sql_record($oDBLayoutCampos->sql_query_file(5900,"db52_default"));
      if ($oDBLayoutCampos->numrows == 0 ) {
        throw new Exception("Nenhum registro da Linha 5900 encontrado no Layout 101!");
      }
          
      $this->iIdAtosDistribuidores = db_utils::fieldsMemory($rsAtosDistribuidores,0)->db52_default;
      if (empty($this->iIdAtosDistribuidores)){
        throw new Exception("ERRO: Valor Default do campo codigo_receita_atos_distribuidores não configurado no Layout 101 !\n\n".
                            "O valor Default para este campo deve ser o código da taxa referenciada pelo campo");
      }
          
      $this->iIdTaxaJudiciaria = db_utils::fieldsMemory($rsTaxaJudiciaria,0)->db52_default;
      if (empty($this->iIdTaxaJudiciaria)){
        throw new Exception("ERRO: Valor Default do campo codigo_receita_taxa_judiciaria não configurado no Layout 101 !\n\n".
                            "O valor Default para este campo deve ser o código da taxa referenciada pelo campo");          
      }
          
      $this->iIdAtosEscrivaesDividaAtiva = db_utils::fieldsMemory($rsAtosEscrivaesDividaAtiva,0)->db52_default;
      if (empty($this->iIdAtosEscrivaesDividaAtiva)){
        throw new Exception("ERRO: Valor Default do campo codigo_receita_atos_escrivaes_divida_ativa não configurado no Layout 101 !\n\n".
                            "O valor Default para este campo deve ser o código da taxa referenciada pelo campo");          
      }
          
      $this->iIdAcrescimo = db_utils::fieldsMemory($rsAcrescimo,0)->db52_default;
      if (empty($this->iIdAcrescimo)){
        throw new Exception("ERRO: Valor Default do campo conta_corrente_acrescimo_20 não configurado no Layout 101 !\n\n".
                            "O valor Default para este campo deve ser o código da taxa referenciada pelo campo");          
      }
          
      $this->iIdAtosOficiaisJusticaAvaliadores = db_utils::fieldsMemory($rsAtosOficiaisJusticaAvaliadores,0)->db52_default;
      if (empty($this->iIdAtosOficiaisJusticaAvaliadores)){
        throw new Exception("ERRO: Valor Default do campo codigo_receita_atos_oficiais_justica_avaliadores não configurado no Layout 101 !\n\n".
                            "O valor Default para este campo deve ser o código da taxa referenciada pelo campo");          
      }

    } catch (Exception $eException) {
      throw new Exception($eException->getMessage());
    }
    
  }
  
}