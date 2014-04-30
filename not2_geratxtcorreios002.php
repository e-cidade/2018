<?
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_libpessoal.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_layouttxt.php");

require_once ("model/regraEmissao.model.php");
require_once ("model/convenio.model.php");
require_once ("model/recibo.model.php");

require_once ("classes/db_notificacao_classe.php");
require_once ("classes/db_recibopaga_classe.php");
require_once ("classes/db_arretipo_classe.php");
require_once ("classes/db_notiemissao_classe.php");
require_once ("classes/db_notiemissaoreg_classe.php");

$oGet             = db_utils::postMemory($_GET);
/**
 * Carateres a serem escapados nos textos de mensagem
 */
$aEscape         = array("\r\n", "\n", "\r", ";","\t");
                
$clNotificacao   = new cl_notificacao();
$clArretipo      = new cl_notificacao();

$complementa_nome_arquivo = "_" . ( $tipo == "f"?"producao":"teste" ) . "_base_" . db_getsession("DB_NBASE");

$sNomeArquivo    = '/tmp/notificacao_correios_' . date('YmdHis') . $complementa_nome_arquivo . '.txt';
$oLayoutTxt      = new db_layouttxt(146, $sNomeArquivo);

/**
 * Dados Filtrados no formulário
 */
$iCodLista           = $oGet->lista;
$sOrdenar            = $oGet->ordem;
$iTratamento         = $oGet->tratamento;
$iQuantidade         = $oGet->qtd;
$iTipoFonte          = $oGet->fonte;
$nEspacamento        = $oGet->espacamento;
$sEstiloFonte        = $oGet->estilofonte;
$iTamanhoFonte       = $oGet->tamanhofonte;
$lServicoAr          = isset($oGet->lServAr) ? $oGet->lServAr : '';
$lGeraBoleto         = $oGet->lBoleto == 1 ? true : false;
$dtDtVencimento      = isset($oGet->datavenc) ? $oGet->datavenc : date('Y-m-d', db_getsession('DB_datausu'));
$dtDtVencimentoBanco = implode("-",array_reverse(explode("/",$dtDtVencimento)));
$dtOperacao          = date('d/m/Y', db_getsession('DB_datausu'));
$sLocalPagamento     = $oGet->localpgto;

if($sOrdenar == 'a') {
  $sOrderBy = 'nome_cgm_origem';
} elseif ($sOrdenar == 'n') {
    $sOrderBy = 'codigo_origem_notificacao';
} elseif ($sOrdenar == 'e') {
  $sOrderBy = 'endereco_cgm_origem';
} else if ($sOrdenar == 'c'){
    $sOrderBy = 'cidade_endereco_cgm_origem';
} else {
    $sOrderBy = 'codigo_notificacao';
}
   
if($iTratamento > 1) {
   
    $sSqlTratamento       = "update cfiptu                                            ";
    $sSqlTratamento      .= "   set j18_ordendent = " . ($iTratamento - 10)."         ";
    $sSqlTratamento      .= " where j18_anousu    = " . db_getsession("DB_anousu");
    $rsTratamentoEndereco = db_query($sSqlTratamento) or die('Erro tratamento de endereço.');
     
}
$rsNotificacao   = $clNotificacao->sql_record($clNotificacao->sql_query_lista_notificacoes($iCodLista,
                                                                                           $sOrderBy,
                                                                                           $iQuantidade));

?>
<html>
<head>
<?
  db_app::load('estilos.css');
  db_app::load('scripts.js');
  db_app::load('strings.js');
?>
</head>
<body>
<form name="form1" id="form1">
<?
db_criatermometro("termometro", "Concluido...", "blue", 1);
flush();

if($rsNotificacao && pg_num_rows($rsNotificacao) > 0) {
   
    $aNotificacoes = db_utils::getCollectionByRecord($rsNotificacao);
   
    //gera arquivo
    try {
       
        $clNotiEmissao         = new cl_notiemissao();
    $clNotiEmissaoReg      = new cl_notiemissaoreg();
   
    db_inicio_transacao();
   
    $clNotiEmissao->k136_notificatipogeracao = 1; //1 = NOTIFICAÇÕES TXT CORREIOS
    $clNotiEmissao->k136_recibo              = $lGeraBoleto == true ? 'true' : 'false';
    $clNotiEmissao->k136_data                = date('Y-m-d', db_getsession('DB_datausu'));;
    $clNotiEmissao->k136_usuario             = db_getsession('DB_id_usuario');
    $clNotiEmissao->incluir(null);
   
    if($clNotiEmissao->erro_status == 0) {
      throw new ErrorException($clNotiEmissao->erro_msg);
    }
    $oHeader = new stdClass();
    $oHeader->identificador = '0';
    $oHeader->dados_arquivo = 'ARQUIVOS DE ' . date('d/m/Y' , db_getsession('DB_datausu')) . ' ' . date('H:i:s');
    $oHeader->versao_layout = '0';
   
    if ( $oLayoutTxt->setByLineOfDBUtils($oHeader,1,"0") == false ) {
      throw new ErrorException (_M('tributario.notificacoes.not2_geratxtcorreios002.erro_gerar_header'));
    }
   
    $iQtdeNotificacoes = count($aNotificacoes);
    $iTermometro       = 0;
   
    foreach ($aNotificacoes as $oNotificacao) {
       
        db_atutermometro($iTermometro,
                         $iQtdeNotificacoes,
                         "termometro",
                         1,
                         'Processando notificação ' . $oNotificacao->codigo_notificacao . '. ' .
                         ($iTermometro + 1) . '/' . $iQtdeNotificacoes);
      $iTermometro++;

      /**
       * Busca numpres dos debitos da notificacao
       */
        $sCampos                = 'DISTINCT k53_numpre as numpre';
        $sSqlNumpresNotificacao = $clNotificacao->sql_query_debitos_notificacao($oNotificacao->codigo_notificacao, $sCampos);
      $rsNumpresNotificacao   = $clNotificacao->sql_record($sSqlNumpresNotificacao);
        $aNumpresNotificacao    = db_utils::getCollectionByRecord($rsNumpresNotificacao);
           
        foreach ($aNumpresNotificacao as $oNumpreNotificacao) {
           
            $clNotiEmissaoReg->k137_notiemissao = $clNotiEmissao->k136_sequencial;
          $clNotiEmissaoReg->k137_notificacao = $oNotificacao->codigo_notificacao;
          $clNotiEmissaoReg->k137_numpre      = $oNumpreNotificacao->numpre;
          $clNotiEmissaoReg->incluir(null);
         
          if($clNotiEmissaoReg->erro_status == 0) {
            throw new ErrorException($clNotiEmissaoReg->erro_msg);
          }
         
        }
        /**
         * Busca os débitos (numpre, numpar e tipo) da notificacao para geracao do recibo
         */
        $sSqlDebitosNotificacao = $clNotificacao->sql_query_debitos_notificacao($oNotificacao->codigo_notificacao,
                                                                              "distinct 
                                                                               k53_numpre   as numpre,    
                                                                               k53_numpar   as numpar,    
                                                                               k22_tipo     as tipo_debito");
        $rsDebitosNotificacao   = $clNotificacao->sql_record($sSqlDebitosNotificacao);
        /**
         * Para se houver erro de query
         */
      if ( $clNotificacao->erro_status == "0") {
        
        $oParms = new stdClass();
        $oParms->sqlDebitosNotificacao = $sSqlDebitosNotificacao;
        throw new ErrorException (_M('tributario.notificacoes.not2_geratxtcorreios002.erro_executar_sql', $oParms));
      }
     
      $aDebitosNotificacao = db_utils::getCollectionByRecord($rsDebitosNotificacao);
      /**
       * Cria novo recibo e busca seus dados
       */
      if ( $lGeraBoleto ) {
        $oDadosBoleto = geraBoleto($oNotificacao->codigo_notificacao, $aDebitosNotificacao, $dtDtVencimentoBanco);
        echo "<PRE>";
        //print_r($oDadosBoleto);
      }

      /**
       * Gera dados tipo de registro 1
       */
      $oRegistro_1 = new stdClass();
      $oRegistro_1->identificador         = 1;
      $oRegistro_1->oid                   = $oNotificacao->codigo_notificacao;
      $oRegistro_1->assunto               = 'Notificação de débitos';
      $oRegistro_1->oid_remetente         = $oNotificacao->cgm_prefeitura;
      $oRegistro_1->apelido_r             = $oNotificacao->nome_prefeitura_abreviado;
      $oRegistro_1->titulo_r              = '';
      $oRegistro_1->nome_r                = $oNotificacao->nome_prefeitura;
      $oRegistro_1->cep_r                 = $oNotificacao->cep_prefeitura;
      $oRegistro_1->endereco_r            = $oNotificacao->endereco_prefeitura;
      $oRegistro_1->numero_r              = $oNotificacao->numero_prefeitura;
      $oRegistro_1->complemento_r         = $oNotificacao->complemento_endereco_prefeitura;
      $oRegistro_1->bairro_r              = $oNotificacao->bairro_prefeitura;
      $oRegistro_1->cidade_r              = $oNotificacao->cidade_prefeitura;
      $oRegistro_1->uf_r                  = $oNotificacao->uf_prefeitura;
      $oRegistro_1->ddd_r                 = '';
      $oRegistro_1->telefone_r            = $oNotificacao->telefone_prefeitura;
      $oRegistro_1->email_r               = $oNotificacao->email_prefeitura;
      $oRegistro_1->zero_3                = '0';
      $oRegistro_1->ddd_fax_r             = '';
      $oRegistro_1->numero_fax            = $oNotificacao->fax_prefeitura;
      $oRegistro_1->cep_caixa_postal_r    = '';
      $oRegistro_1->caixa_postal_r        = $oNotificacao->caixa_postal_prefeitura;
      $oRegistro_1->fixo_n_2              = 'N';
      $oRegistro_1->dt_envio              = $oNotificacao->data_operacao;
      $oRegistro_1->dt_cadastro           = $oNotificacao->data_emissao_lista;
      $oRegistro_1->zero_2                = '0';
      $oRegistro_1->fixo_1                = '1';
      $oRegistro_1->srvcc                 = '';
      $oRegistro_1->srvpc                 = '';
      $oRegistro_1->srvpd                 = '';
      $oRegistro_1->srvph                 = '';
      $oRegistro_1->srvdh                 = '';
      $oRegistro_1->data_predatado        = '';
      $oRegistro_1->img_cabecalho         = '';
      $oRegistro_1->img_rodape            = '';
      $oRegistro_1->retorno_servico       = '';
      $oRegistro_1->zero_1                = '0';
      $oRegistro_1->usuario               = '';
      $oRegistro_1->nulo_1                = '';
      $oRegistro_1->br                    = 'BR';
      $oRegistro_1->nulo_2                = '';
      $oRegistro_1->erro                  = '';
      $oRegistro_1->fixo_n_1              = 'N';
      $oRegistro_1->identificador_correio = '';
      $oRegistro_1->documento             = '';
      $oRegistro_1->internacional         = '';
                        
      if ( $oLayoutTxt->setByLineOfDBUtils($oRegistro_1, 3, 1) == false ) {
        throw new ErrorException (_M('tributario.notificacoes.not2_geratxtcorreios002.erro_gerar_detalhe_1'));
      }
       
      /**
       * Texto telegrama
       */
      $oRegistro_2 = new stdClass();
      $oRegistro_2->identificador = 2;
      $oRegistro_2->texto         = str_replace($aEscape, '', $oNotificacao->texto_campo);
     
      if ( $oLayoutTxt->setByLineOfDBUtils($oRegistro_2,3,2) == false ) {
        throw new ErrorException (_M('tributario.notificacoes.not2_geratxtcorreios002.erro_gerar_detalhe_2'));
      }
      /**
       * Dados do(s) Destinatário(s) do Telegrama e carta
       */
      $oRegistro_3   = new stdClass();
      $oRegistro_3->identificador  = 3;
      $oRegistro_3->oid_doc          = $oNotificacao->codigo_notificacao;
      $oRegistro_3->oid_contato      = $oNotificacao->codigo_origem_notificacao;
      $oRegistro_3->fixo_t           = "T";
      $oRegistro_3->fixo_d           = "D";
      $oRegistro_3->apelido          = "";
      $oRegistro_3->titulo           = "";
      $oRegistro_3->nome             = $oNotificacao->nome_cgm_origem;
      $oRegistro_3->cep              = $oNotificacao->cep_cgm_origem;
      $oRegistro_3->endereco         = $oNotificacao->endereco_cgm_origem;
      $oRegistro_3->numero           = $oNotificacao->numero_endereco_cgm_origem;
      $oRegistro_3->complemento      = $oNotificacao->complemento_endereco_cgm_origem;
      $oRegistro_3->bairro           = $oNotificacao->bairro_endereco_cgm_origem;
      $oRegistro_3->cidade           = $oNotificacao->cidade_endereco_cgm_origem;
      $oRegistro_3->uf               = $oNotificacao->uf_endereco_cgm_origem;
      $oRegistro_3->pais             = "BR";
      $oRegistro_3->provincia        = "";
      $oRegistro_3->ddd              = "";
      $oRegistro_3->telefone         = $oNotificacao->telefone_cgm_origem;
      $oRegistro_3->email            = $oNotificacao->email_cgm_origem;
      $oRegistro_3->fixo_1           = "1";
      $oRegistro_3->ddd_fax          = "";
      $oRegistro_3->numero_fax       = $oNotificacao->fax_cgm_origem;
      $oRegistro_3->cep_caixa_postal = "";
      $oRegistro_3->caixa_postal     = $oNotificacao->caixa_postal_endereco_cgm_origem;
      $oRegistro_3->tipo_destino     = "0";
      $oRegistro_3->fixo_n           = "N";

      if ( $oLayoutTxt->setByLineOfDBUtils($oRegistro_3,3,3) == false ) {
        throw new ErrorException (_M('tributario.notificacoes.not2_geratxtcorreios002.erro_gerar_detalhe_3'));
      }     
     
      /**
       * Dados da carta e do remetente
       */
      $oRegistro_4 = new stdClass();
      $oRegistro_4->identificador           = 4;
      $oRegistro_4->oid                     = $oNotificacao->codigo_notificacao;
      $oRegistro_4->assunto                 = 'Notificação de débitos';
      $oRegistro_4->oid_remetente           = $oNotificacao->cgm_prefeitura;
      $oRegistro_4->apelido_r               = $oNotificacao->nome_prefeitura_abreviado;
      $oRegistro_4->titulo_r                = '';
      $oRegistro_4->nome_r                  = $oNotificacao->nome_prefeitura;
      $oRegistro_4->cep_r                   = $oNotificacao->cep_prefeitura;
      $oRegistro_4->endereco_r              = $oNotificacao->endereco_prefeitura;
      $oRegistro_4->numero_r                = $oNotificacao->numero_prefeitura;
      $oRegistro_4->complemento_r           = $oNotificacao->complemento_endereco_prefeitura;
      $oRegistro_4->bairro_r                = $oNotificacao->bairro_prefeitura;
      $oRegistro_4->cidade_r                = $oNotificacao->cidade_prefeitura;
      $oRegistro_4->uf_r                    = $oNotificacao->uf_prefeitura;
      $oRegistro_4->ddd_r                   = '';
      $oRegistro_4->telefone_r              = $oNotificacao->telefone_prefeitura;
      $oRegistro_4->email_r                 = $oNotificacao->email_prefeitura;
      $oRegistro_4->zero_1                  = '0';
      $oRegistro_4->ddd_fax_r               = '';
      $oRegistro_4->numero_fax              = $oNotificacao->fax_prefeitura;
      $oRegistro_4->cep_caixa_postal_r      = '';
      $oRegistro_4->caixa_postal_r          = $oNotificacao->caixa_postal_prefeitura;
      $oRegistro_4->fixo_n_1                = '1';
      $oRegistro_4->dt_envio                = $oNotificacao->data_operacao;
      $oRegistro_4->dt_cadastro             = $oNotificacao->data_emissao_lista;
      $oRegistro_4->zero_2                  = '0';
      $oRegistro_4->ind_nome_fonte          = $iTipoFonte;
      $oRegistro_4->ind_entre_linhas        = $nEspacamento;
      $oRegistro_4->ind_estilo_fonte        = $sEstiloFonte;
      $oRegistro_4->ind_tam_fonte           = $iTamanhoFonte;
      $oRegistro_4->srv_ar                  = $lServicoAr;
      $oRegistro_4->usuario                 = '';
      $oRegistro_4->nulo                    = '';
      $oRegistro_4->br                      = 'BR';
      $oRegistro_4->erro                    = '';
      $oRegistro_4->fixo_n_2                = 'N';
      $oRegistro_4->identificador_correios  = '';
      $oRegistro_4->documento               = '';
      $oRegistro_4->internacional           = '';
      $oRegistro_4->img_cabecalho           = '';
      $oRegistro_4->img_rodape              = '';
    
      if ( $oLayoutTxt->setByLineOfDBUtils($oRegistro_4,3,4) == false ) {
        throw new ErrorException (_M('tributario.notificacoes.not2_geratxtcorreios002.erro_gerar_detalhe_4'));
      }       
     
      /**
       * Texto da carta
       */
      $oRegistro_5 = new stdClass();
      $oRegistro_5->identificador = 5;
      $oRegistro_5->texto         = str_replace($aEscape, '', $oNotificacao->texto_campo);
     
      if ( $oLayoutTxt->setByLineOfDBUtils($oRegistro_5,3, 5) == false ) {
        throw new ErrorException (_M('tributario.notificacoes.not2_geratxtcorreios002.erro_gerar_detalhe_5'));
      }
     
      /**
       * Destinatário da carta
       */
      $oRegistro_6 = new stdClass();
      $oRegistro_6->identificador           = 6;
      $oRegistro_6->oid_doc                 = $oNotificacao->codigo_notificacao;
      $oRegistro_6->oid_contato             = $oNotificacao->codigo_origem_notificacao;
      $oRegistro_6->fixo_c                  = 'C';
      $oRegistro_6->fixo_d                  = 'D';
      $oRegistro_6->apelido                 = '';
      $oRegistro_6->titulo                  = '';
      $oRegistro_6->nome                    = $oNotificacao->nome_cgm_origem;
      $oRegistro_6->cep                     = $oNotificacao->cep_cgm_origem;
      $oRegistro_6->endereco                = $oNotificacao->endereco_cgm_origem;
      $oRegistro_6->numero                  = $oNotificacao->numero_endereco_cgm_origem;
      $oRegistro_6->complemento             = $oNotificacao->complemento_endereco_cgm_origem;
      $oRegistro_6->bairro                  = $oNotificacao->bairro_endereco_cgm_origem;
      $oRegistro_6->cidade                  = $oNotificacao->cidade_endereco_cgm_origem;
      $oRegistro_6->uf                      = $oNotificacao->uf_endereco_cgm_origem;
      $oRegistro_6->pais                    = 'BR';
      $oRegistro_6->pais                    = 'BR';
      $oRegistro_6->ddd                     = '';
      $oRegistro_6->telefone                = $oNotificacao->telefone_cgm_origem;
      $oRegistro_6->email                   = $oNotificacao->email_cgm_origem;
      $oRegistro_6->fixo_1                  = '1';
      $oRegistro_6->ddd_fax                 = '';
      $oRegistro_6->numero_fax              = $oNotificacao->fax_cgm_origem;
      $oRegistro_6->cep_caixa_postal        = '';
      $oRegistro_6->caixa_postal            = $oNotificacao->caixa_postal_endereco_cgm_origem;
      $oRegistro_6->tipo_destino            = '0';
      $oRegistro_6->fixo_n                  = 'N';

      if ( $oLayoutTxt->setByLineOfDBUtils($oRegistro_6,3,6) == false ) {
        throw new ErrorException (_M('tributario.notificacoes.not2_geratxtcorreios002.erro_gerar_detalhe_6'));
      }

      if ( $lGeraBoleto ) {
        $oRegistro_7 = new stdClass();
        $oRegistro_7->identificador          = 7;
        $oRegistro_7->oid_doc                = $oNotificacao->codigo_notificacao;
        $oRegistro_7->cod_banco              = $oDadosBoleto->iCodigoBanco;
        $oRegistro_7->cod_carteira           = $oDadosBoleto->iCodigoCarteira;
        $oRegistro_7->texto_local_pagamento  = $sLocalPagamento;
        $oRegistro_7->cod_agencia_cedente    = $oDadosBoleto->iCodigoAgenciaCedente;
        $oRegistro_7->cod_agencia_cedente_dv = $oDadosBoleto->iDigitoAgenciaCedente;
        $oRegistro_7->cod_cedente            = $oDadosBoleto->iCodigoCedente;
        $oRegistro_7->cod_cedente_dv         = '';

        if ( $oLayoutTxt->setByLineOfDBUtils($oRegistro_7, 3, 7) == false ) {
          throw new ErrorException (_M('tributario.notificacoes.not2_geratxtcorreios002.erro_gerar_detalhe_7'));
        }
       
        $oRegistro_8 = new stdClass();                                                               
        $oRegistro_8->identificador           = 8;                                                   
        $oRegistro_8->instrucoes              = $oDadosBoleto->sInstrucoes;                          
        if ( $oLayoutTxt->setByLineOfDBUtils($oRegistro_8, 3, 8) == false ) {
          throw new ErrorException (_M('tributario.notificacoes.not2_geratxtcorreios002.erro_gerar_detalhe_8'));
        }             
                                                                                                     
        $oRegistro_9 = new stdClass();
        $oRegistro_9->identificador          = 9;
        $oRegistro_9->codigo_linha_digitavel = $oDadosBoleto->sLinhaDigitavel;
        $oRegistro_9->numero_documento       = $oDadosBoleto->sCodigoArrecadacao;
        $oRegistro_9->data_documento         = $oDadosBoleto->dtOperacaoRecibo;
        $oRegistro_9->valor_documento        = $oDadosBoleto->nValorRecibo;
        $oRegistro_9->especie_documento      = $oDadosBoleto->sEspecieDocumento;
        $oRegistro_9->aceite                 = 'N';
       
        if ( $oDadosBoleto->iCadTipoConvenio == 5 ) {
          $aNossoNumero                      = explode("-",$oDadosBoleto->iNossoNumero);
          $oRegistro_9->nosso_numero         = $aNossoNumero[0];
          $oRegistro_9->nosso_numero_dv      = $aNossoNumero[1];
        } else {
          $oRegistro_9->nosso_numero         = $oDadosBoleto->iNossoNumero;
          $oRegistro_9->nosso_numero_dv      = '';
        }
       
        $oRegistro_9->data_processamento     = $dtOperacao;
        $oRegistro_9->data_vencimento        = $oDadosBoleto->dtOperacaoRecibo;
        $oRegistro_9->uso_banco              = '';

        if ( $oLayoutTxt->setByLineOfDBUtils($oRegistro_9, 3, 9) == false ) {
          throw new ErrorException (_M('tributario.notificacoes.not2_geratxtcorreios002.erro_gerar_detalhe_9'));
        }
      }
    }

    db_fim_transacao();
 
    echo "<script>";
    echo "  var listagem;";
    echo "  listagem = '$sNomeArquivo#Download arquivo TXT (notificações para os correios)';";
    echo "  js_montarlista(listagem,'form1');";
    echo "  parent.fechar()";
    echo "</script>";

    } catch (Exception  $oErroMsg) {
     
    db_fim_transacao(true);
    db_msgbox($oErroMsg->getMessage());
     
  }
   
   
} else {

    $oParms = new stdClass();
    $oParms->iCodigoLista = $iCodLista;
    db_msgbox(_M('tributario.notificacoes.not2_geratxtcorreios002.nenhuma_notificacao_encontrada', $oParms));
}

?>
</form>
</body>
</html>
<?
/**
 * Gera Boleto bancario
 * @param integer $iCodigoNotificacao - Codigo da notificação
 * @param array   $aNumpres           - A de objetos com os dados do numpre
 * @param date    $dtVencimento       - Vencimento para o recibo
 */
function geraBoleto($iCodigoNotificacao, $aDebitosNotificacao, $dtVenvimento) {
  /**
   * Caso não encontre nenhum débito da notificação dispara erro
   */
  if ( count($aDebitosNotificacao) == 0 ) {
    throw new ErrorException(_M('tributario.notificacoes.not2_geratxtcorreios002.nenhum_debito_vinculado'));
  } 

  /**
   * Tipo de emissão de recibo de notificação
   * tipo de modelo específico para emissao de notificação em txt
   */
  $iTipoEmissao          = 27;
  $iCadTipoMod           = 6;
  $clReciboPaga          = new cl_recibopaga();
  $clArretipo            = new cl_arretipo();
  $iTipoDebito           = $aDebitosNotificacao[0]->tipo_debito;
                        
  $rsArretipo            = $clArretipo->sql_record($clArretipo->sql_query_file($iTipoDebito," k00_tercdigrecnormal as terceiro_digito_recibo, k00_msgrecibo as msg_recibo"));
  /**
   * Disapara erro quando não acha valor do terceiro digito do recibo
   */
  if ($clArretipo->erro_status == "0") {

    $oParms = new stdClass();
    $oParms->sErro = $clArretipo->erro_banco;
    throw new Exception(_M('tributario.notificacoes.not2_geratxtcorreios002.erro_retornar_valor_terceiro_digito', $oParms));
  }
  $oArretipo             = db_utils::fieldsMemory($rsArretipo,0);
  $iTerceiroDigitoRecibo = $oArretipo->terceiro_digito_recibo;
  $sMsgRecibo            = $oArretipo->msg_recibo;
 
  /**
   * Valida regra de emissao a ser utilizada
   */
  $oRegraEmissao = new regraEmissao(null, $iCadTipoMod, 1, date("Y-m-d",db_getsession("DB_datausu")), db_getsession("DB_ip"));
  $oRegraEmissao->getCadTipoConvenio();

  $oRecibo= new recibo(2, null, 27);
 
  /**
   * Adiciona Numpre ao Recibo
   */
  foreach ($aDebitosNotificacao as $oDebitosNotificacao) {
    $oRecibo->addNumpre($oDebitosNotificacao->numpre, $oDebitosNotificacao->numpar);
  }
  $oRecibo->setNumBco              ($oRegraEmissao->getCodConvenioCobranca());
  $oRecibo->setDataRecibo          ($dtVenvimento);
  $oRecibo->setDataVencimentoRecibo($dtVenvimento);
  $oRecibo->setExercicioRecibo     (substr($dtVenvimento, 0, 4) );
  $oRecibo->emiteRecibo            ();
 
 
  $sSqlDadosRecibosGerado = $clReciboPaga->sql_query_file(null, "sum(k00_valor) as valor_recibo", null, "k00_numnov = {$oRecibo->getNumpreRecibo()}");
  $rsDadosRecibosGerado   = $clReciboPaga->sql_record($sSqlDadosRecibosGerado);
 
  if ( $clReciboPaga->erro_status == "0" ) {

    $oParms = new stdClass();
    $oParms->sErro = $clReciboPaga->erro_sql;
    throw new Exception(_M('tributario.notificacoes.not2_geratxtcorreios002.erro_gerar_recibo', $oParms));
  }
 
 
  $nValorReciboGerado     = db_utils::fieldsMemory($rsDadosRecibosGerado,0)->valor_recibo;
  $sValorCodigoBarras     = str_pad( number_format( $nValorReciboGerado, 2, "", "" ), 11, "0", STR_PAD_LEFT);
  /**
   * Pega dados do convenio 
   */
  $oConvenio = new convenio($oRegraEmissao->getConvenio(),
                            $oRecibo->getNumpreRecibo(),
                            0,
                            $nValorReciboGerado,
                            $sValorCodigoBarras,
                            $dtVenvimento,
                            $iTerceiroDigitoRecibo
                            );
 
  $oRetorno  = new stdClass();
  $oRetorno->sLinhaDigitavel       = str_replace(" ", "", $oConvenio->getLinhaDigitavel());
  $oRetorno->sCodigoBarras         = $oConvenio->getCodigoBarra();
  $oRetorno->iCodigoBanco          = $oConvenio->getCodBanco();
  $oRetorno->iCodigoAgencia        = $oConvenio->getCodAgencia();
  $oRetorno->iCodigoCarteira       = $oConvenio->getCarteira();
  $oRetorno->iCodigoAgenciaCedente = $oConvenio->getAgenciaCedente();
  $oRetorno->iDigitoAgenciaCedente = "";
  $oRetorno->iCodigoCedente        = $oConvenio->getCedente();
  $oRetorno->iDigitoCedente        = $oConvenio->getDigitoCedente() ;
  $oRetorno->sCodigoArrecadacao    = $oConvenio->getConvenioArrecadacao();
  $oRetorno->dtOperacaoRecibo      = implode("/",array_reverse(explode("-", $oRecibo->getDataRecibo())));
  $oRetorno->dtVencimentoRecibo    = implode("/",array_reverse(explode("-", $oRecibo->getDataRecibo())));
  $oRetorno->sInstrucoes           = $sMsgRecibo;
  $oRetorno->nValorRecibo          = $nValorReciboGerado;
  $oRetorno->sEspecieDocumento     = $oConvenio->getEspecieDocumento();
  $oRetorno->nValorRecibo          = $nValorReciboGerado;
  $oRetorno->iNossoNumero          = $oConvenio->getNossoNumero();
  $oRetorno->iNossoNumeroDV        = "";
  $oRetorno->iNumpreRecibo         = $oRecibo->getNumpreRecibo();  
  $oRetorno->iCadTipoConvenio      = $oRegraEmissao->getCadTipoConvenio();
 
  return $oRetorno;
}
?>