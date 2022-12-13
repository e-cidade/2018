<?php

/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 03/05/16
 * Time: 10:58
 */
 abstract class ImportacaoArquivoConsignado {

   const IMPORTACAO_CONSIGNADO_CAIXA = '104';

   protected $oInstituicao;

   protected $oCompetencia;

   protected $oArquivo;

   /**
    * @var ConfiguracaoConsignado
    */
   protected $oConfiguracao;

   abstract function __construct($sArquivo, DBCompetencia $oCompetencia, Instituicao $oInstituicao);


   abstract function processar();


   abstract function setConfiguracao (ConfiguracaoConsignado $oConfiguracao);

   
   protected function validarAfastamento(RegistroConsignado $oRegistro) {

     $mAfastamento = $oRegistro->getServidor()->isAfastado();
     if ($mAfastamento && !$oRegistro->getServidor()->temRemuneracaoNoPeriodo()) {

       $oRegistro->setMotivo(RegistroConsignado::MOTIVO_SERVIDOR_AFASTADO);
     }

     return $oRegistro;
   }

   protected function validarRescisao(RegistroConsignado $oRegistro) {

     $oServidor = $oRegistro->getServidor();

     if ($oServidor->isRescindido()) {
       $oRegistro->setMotivo(RegistroConsignado::MOTIVO_SERVIDOR_DESLIGADO);
     }
   }

   /**
    * Valida se o servidor informado, esta falecido, se estiver retorna true senão retorna false.
    *
    * @param $oRegistro
    * @return bool
    * @throws \DBException
    */
   protected function validaServidorFalecido(RegistroConsignado $oRegistro) {

     $oDaoRhPesRescisao = new cl_rhpesrescisao();

     /**
      * Valida se o servidor possui uma das causas(60,62,64)
      * se posusir é porque o mesmo esta falecido.
      */
     $sWherePesRescisao = "rh02_regist = {$oRegistro->getServidor()->getMatricula()} and r59_causa in (60, 62, 64)";
     $sSqlRhPesRescisao = $oDaoRhPesRescisao->sql_query_rescisao(null, '*', null, $sWherePesRescisao);
     $rsRhPesRescisao   = db_query($sSqlRhPesRescisao);

     if (!$rsRhPesRescisao) {
       throw new DBException("Não foi possivel validar os dados do servidor");
     }

     if (pg_num_rows($rsRhPesRescisao)) {
       $oRegistro->setMotivo(RegistroConsignado::MOTIVO_FALECIMENTO);
     }
   }
 }