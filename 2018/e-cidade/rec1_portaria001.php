<?php
/**
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);

$imprimir = false;

$clrhparam         = new cl_rhparam;
$clportaria        = new cl_portaria;
$classenta         = new cl_assenta;
$clrhpessoal       = new cl_rhpessoal;
$clportariaassenta = new cl_portariaassenta;
$clportariatipo    = new cl_portariatipo;

$db_opcao = 1;
$db_opcao_numero = 1;
$db_botao = true;

$sqlerro  = false;
$erro_msg = "";

if ( isset($incluir) ) {

  $db_botao = false;

  db_inicio_transacao();

  /**
   * Pesquisa parametro da numeracao da portaria, caso encontre pega proxima numeracao, nextval()
   */
  $sWhereRhParam  = " h36_ultimaportaria > 0 and h36_instit = ".db_getsession("DB_instit");
  $sSqlRhParam    = $clrhparam->sql_query_file(null,"h36_ultimaportaria",null,$sWhereRhParam);
  $rsDadosRhParam = $clrhparam->sql_record($sSqlRhParam);
  $lSeqAutomatico = false;

  if ( $clrhparam->numrows > 0 ) {
    $lSeqAutomatico = true;
  }

  $iNroPort = $h31_numero;

  if(!isset($h31_dtportaria) || empty($h31_dtportaria)) {

    $sqlerro                 = true;
    $clportaria->erro_msg    = "Selecione a data da portaria.";
    $clportaria->erro_status = !(int)$sqlerro;
  }

  if(!$sqlerro && (!isset($h31_dtinicio) || empty($h31_dtinicio))) {

    $sqlerro                 = true;
    $clportaria->erro_msg    = "Selecione a data inicial da portaria.";
    $clportaria->erro_status = !(int)$sqlerro;
  }

  if(!$sqlerro && (!isset($h31_dtlanc) || empty($h31_dtlanc))) {

    $sqlerro                 = true;
    $clportaria->erro_msg    = "Selecione a data de lançamento da portaria.";
    $clportaria->erro_status = !(int)$sqlerro;
  }

  if(!$sqlerro && (!isset($h16_regist) || empty($h16_regist))) {

    $sqlerro                 = true;
    $clportaria->erro_msg    = "Selecione um servidor.";
    $clportaria->erro_status = !(int)$sqlerro;
  }

  if(!$sqlerro && (!isset($h16_dtconc) || empty($h16_dtconc))) {

    $sqlerro                 = true;
    $clportaria->erro_msg    = "Selecione a data inicial do assentamento da portaria.";
    $clportaria->erro_status = !(int)$sqlerro;
  }

  if (!$sqlerro) {

    if($lSeqAutomatico) {

      $sSqlSequence       = " select nextval('rhparam_h36_ultimaportaria_seq') as seq ";
      $rsConsultaSequence = db_query($sSqlSequence);
      $oSeqPortaria       = db_utils::fieldsMemory($rsConsultaSequence,0);
      $iNroPort           = $oSeqPortaria->seq;
    }

    /**
     * Inclui portaria
     */
    if (isset($h31_sequencial) && trim(@$h31_sequencial)==""){

         $clportaria->h31_numero = $iNroPort;
         $clportaria->incluir($h31_sequencial);
         if ($clportaria->erro_status == "0"){
           $sqlerro          = true;
           $erro_msg         = $clportaria->erro_msg;
         } else {
           $h31_sequencial   = $clportaria->h31_sequencial;
           $h31_portariatipo = $clportaria->h31_portariatipo;
           $h31_numero       = $clportaria->h31_numero;
         }
    }

    /**
     * Inclui assentamento
     */
    if (isset($h16_regist) && trim(@$h16_regist) !="" && !$sqlerro){

      $rsPortariaTipo = db_query($clportariatipo->sql_query_file($h31_portariatipo,"h30_tipoasse",null));
      $oPortariaTipo  = db_utils::fieldsMemory($rsPortariaTipo,0);

      $oAssentamento = new Assentamento();
      /**
       * Quando for assentamento funcional salvamos na tabela plugins.assentamentofuncional
       */
      if ( isset($sOpcaoAssentamento) &&  $sOpcaoAssentamento == 2) {
        $oAssentamento = new AssentamentoFuncional();
      }
          
      $oAssentamento->setMatricula($h16_regist);
      $oAssentamento->setTipoAssentamento($oPortariaTipo->h30_tipoasse);
      $oAssentamento->setDataConcessao(new DBDate($h16_dtconc));
      $oAssentamento->setHistorico($h16_histor);
      $oAssentamento->setCodigoPortaria($iNroPort);
      $oAssentamento->setDescricaoAto($h16_atofic);
      $oAssentamento->setDias($h16_quant);
      $oAssentamento->setPercentual("0");

      if(isset($h16_dtterm) && trim($h16_dtterm) != "") {
        $oAssentamento->setDataTermino(new DBDate($h16_dtterm));
      }

      if(!isset($h16_anoato)) {
        $h16_anoato = '';
      }

      $oAssentamento->setSegundoHistorico('');
      $oAssentamento->setLoginUsuario(db_getsession("DB_id_usuario"));
      $oAssentamento->setDataLancamento(date("Y-m-d",db_getsession("DB_datausu")));
      $oAssentamento->setConvertido("false");
      $oAssentamento->setAnoPortaria($h16_anoato);

      
      if ( isset($sOpcaoAssentamento) &&  $sOpcaoAssentamento == 2) {
        $oAssentamentoSalvo = AssentamentoFuncionalRepository::persist($oAssentamento->persist());
      } else {
        $oAssentamentoSalvo = AssentamentoRepository::persist($oAssentamento->persist());
      }
          

      if(!$oAssentamentoSalvo instanceof Assentamento) {
        throw new BusinessException($oAssentamentoSalvo);
      }

      /**
       * Incluimos na tabela assenta e criamos uma relação entre os assentamentos do pessoal e do rh
       * incluendo as chaves na tabela afastaassenta
       */
      $aListaInformacoesExternas = InformacoesExternasTipoAssentamento::getTipoAssentamentoConfiguradosPorCompetencia(DBPessoal::getCompetenciaFolha());

      if(is_array($aListaInformacoesExternas)){

        $aTiposAssentamentoConfigurados = array();
        foreach ($aListaInformacoesExternas as $oInformacoesExternas) {
          $aTiposAssentamentoConfigurados[] = $oInformacoesExternas->getTipoAssentamento()->getCodigo();
        }

        if( in_array($oAssentamento->getTipoAssentamento(), $aTiposAssentamentoConfigurados) ) {

          $oServidor    = ServidorRepository::getInstanciaByCodigo($oAssentamento->getMatricula(),
                                                                   $oInformacoesExternas->getCompetencia()->getAno(),
                                                                   $oInformacoesExternas->getCompetencia()->getMes());

          $oAfastamento = new Afastamento();

          $oAfastamento->setCompetencia($oInformacoesExternas->getCompetencia());
          $oAfastamento->setServidor($oServidor);
          $oAfastamento->setDataAfastamento($oAssentamento->getDataConcessao());
          $oAfastamento->setDataRetorno($oAssentamento->getDataTermino());
          $oAfastamento->setCodigoSituacao($oInformacoesExternas->getSituacaoAfastamento());
          $oAfastamento->setDataLancamento($oAssentamento->getDataLancamento());
          $oAfastamento->setCodigoAfastamentoSefip($oInformacoesExternas->getSefip());
          $oAfastamento->setCodigoRetornoSefip($oInformacoesExternas->getCodigoRetorno());
          $oAfastamento->setObservacao($oAssentamento->getHistorico());

          $oAfastamentoSalvo = AfastamentoRepository::persist($oAfastamento);

          if(!$oAfastamentoSalvo instanceof Afastamento) {
            throw new BusinessException("Erro ao salvar afastamento na base de dados.");
          }

          $oAfastaAssenta      = new AfastaAssenta($oAssentamento, $oAfastamento);
          $oAfastaAssentaSalvo = $oAfastaAssenta->persist();

          if(!$oAfastaAssentaSalvo instanceof AfastaAssenta) {
            throw new BusinessException("Erro ao salvar vínculo entre assentamento e afastamento.");
          }

          /**
           * Realiza a proporcionalização no ponto
           */
          $oProporcionalizacaoPontoSalario = new ProporcionalizacaoPontoSalario($oServidor->getPonto(Ponto::SALARIO), $oInformacoesExternas->getSituacaoAfastamento(), $oAssentamento->getDataTermino());
          $oProporcionalizacaoPontoSalario->processar();
        }
      }


      if ($classenta->erro_status == "0") {
       $sqlerro    = true;
       $erro_msg   = $classenta->erro_msg;
       $clportaria->erro_msg = $erro_msg;
      }

      if (!$sqlerro) {

       $h16_codigo = $oAssentamento->getCodigo();

       $clportariaassenta->h33_portaria = $h31_sequencial;
       $clportariaassenta->h33_assenta  = $h16_codigo;
       $clportariaassenta->incluir(null);

       if ($clportariaassenta->erro_status == "0"){
          $sqlerro  = true;
          $erro_msg = $clportariaassenta->erro_msg;
          $clportaria->erro_msg = $erro_msg;
       }
      }

      if (!$sqlerro && !empty($h80_db_cadattdinamicovalorgrupo)) {

        $oDaoAssentaAttr = new cl_assentadb_cadattdinamicovalorgrupo();
        $oDaoAssentaAttr->h80_assenta                     = $h16_codigo;
        $oDaoAssentaAttr->h80_db_cadattdinamicovalorgrupo = $h80_db_cadattdinamicovalorgrupo;
        $oDaoAssentaAttr->incluir($h16_codigo, $h80_db_cadattdinamicovalorgrupo);

        if ($oDaoAssentaAttr->erro_status == "0") {
         $sqlerro  = true;
         $erro_msg = $oDaoAssentaAttr->erro_msg;
        }
      }

      $imprimir = true;
    }
  }

  /**
   * Altera parametro(h36_ultimaportaria) com numero da ultima portaria
   * - caso sequencial for automatico
   * - caso nao exitir erro
   */
  if (!$sqlerro && $lSeqAutomatico) {

    $sSqlSequence       = " select last_value as seq from rhparam_h36_ultimaportaria_seq";
    $rsConsultaSequence = db_query($sSqlSequence);
    $oSeqPortaria       = db_utils::fieldsMemory($rsConsultaSequence,0);

    $clrhparam->h36_ultimaportaria = $oSeqPortaria->seq;
    $clrhparam->h36_instit         = db_getsession("DB_instit");
    $clrhparam->alterar(db_getsession("DB_instit"));

    if ( $clrhparam->erro_status == "0" ) {
      $sqlerro  = true;
      $erro_msg = $clrhparam->erro_msg;
      $clportaria->erro_msg = $erro_msg;
    }

  }

  if(!$sqlerro) {
    if(isset($rh161_regist)) {

      if (empty($rh161_regist)) {
        $sqlerro  = true;
        $erro_msg = 'Informe a matrícula do servidor substituído';
        db_redireciona("");
      }

      $oAssentamentoSubstituicao = new AssentamentoSubstituicao($oAssentamento->getCodigo());
      $oAssentamentoSubstituicao->setSubstituido(ServidorRepository::getInstanciaByCodigo($rh161_regist, DBPessoal::getAnoFolha(), DBPessoal::getMesFolha()));

      $mResponse = $oAssentamentoSubstituicao->persist();

      if($mResponse !== true) {
        $sqlerro  = true;
        $erro_msg = $mResponse;
      }
    }
  }

  db_fim_transacao($sqlerro);


  if ( $sqlerro ) {
    $db_botao = true;
  }

  if (!$sqlerro) {
    db_msgbox("Incluído com Sucesso.");
  }
}

/**
 * Nao exise post $incluir, pesquisa proxima numeracao da portaria
 */
else {

  $lExibirNumeracaoPortaria = true;
  $rsConsultaParametros = $clrhparam->sql_record($clrhparam->sql_query_file(null,"h36_ultimaportaria ",null," h36_ultimaportaria > 0 and h36_instit = ".db_getsession("DB_instit")));

  if ($clrhparam->numrows > 0) {

    $oParametros = db_utils::fieldsMemory($rsConsultaParametros,0);
    $h31_numero  = $oParametros->h36_ultimaportaria + 1;
    $lExibirNumeracaoPortaria = false;
  }

  $h80_db_cadattdinamicovalorgrupo = "";
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/geradorrelatorios.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/dates.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <?php include((modification("forms/db_frmportaria.php"))); ?>
  <?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
</body>
</html>
<script>
//js_tabulacaoforms("form1","h31_portariatipo",true,1,"h31_portariatipo",true);
js_criarCamposAdicionais("<?= (!empty($h12_codigo) ? $h16_codigo : null ) ?>");
</script>
<?php
if(isset($incluir)){

  if ($sqlerro) {
    $clportaria->erro(true,false);
  }

}
if ($imprimir == true){
  echo " <script> js_imprimeConf(); </script> ";
}

?>
