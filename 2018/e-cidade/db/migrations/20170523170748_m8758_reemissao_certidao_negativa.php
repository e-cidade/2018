<?php

use Classes\PostgresMigration;

class M8758ReemissaoCertidaoNegativa extends PostgresMigration
{
  public function up() {

    $sUpdateDocPadrao  = "update db_paragrafopadrao ";
    $sUpdateDocPadrao .= "   set db61_codparag = 134 , ";
    $sUpdateDocPadrao .= "       db61_descr = 'CERTIDÃO DE DEBITOS - IDENTIFICAÇÃO' , ";
    $sUpdateDocPadrao .= "       db61_texto = 'IDENTIFICAÇÃO DO CONTRIBUINTE\nCGM: #\$z01_cgmpri# - Nome: #\$z01_nome#\nCNPJ/CPF: #\$z01_cgccpf# RG:#\$z01_ident# Insc. Est.:#\$z01_incest#\nEndereço: #\$tipopri# #\$cgmender#, #\$cgmnumero#/#\$cgmcompl#\nBairro: #\$cgmbairro#\nCidade: #\$cgmmunic#/#\$cgmuf# CEP:#\$cgmcep# ' , ";
    $sUpdateDocPadrao .= "       db61_alinha = 0 , ";
    $sUpdateDocPadrao .= "       db61_inicia = 0 , ";
    $sUpdateDocPadrao .= "       db61_espaco = 1 , ";
    $sUpdateDocPadrao .= "       db61_alinhamento = 'J' , ";
    $sUpdateDocPadrao .= "       db61_altura = 0 , ";
    $sUpdateDocPadrao .= "       db61_largura = 0 , ";
    $sUpdateDocPadrao .= "       db61_tipo = 1 ";
    $sUpdateDocPadrao .= " where db61_codparag = 134";

    $sUpdateParagrafo  = "update db_paragrafo ";
    $sUpdateParagrafo .= "   set db02_texto = 'IDENTIFICAÇÃO DO CONTRIBUINTE\nCGM: #\$z01_cgmpri# - Nome: #\$z01_nome#\nCNPJ/CPF: #\$z01_cgccpf# RG:#\$z01_ident# Insc. Est.:#\$z01_incest#\nEndereço: #\$tipopri# #\$cgmender#, #\$cgmnumero#/#\$cgmcompl#\nBairro: #\$cgmbairro#\nCidade: #\$cgmmunic#/#\$cgmuf# CEP:#\$cgmcep# ' ";
    $sUpdateParagrafo .= "  from db_docparag ";
    $sUpdateParagrafo .= " where db04_idparag = db02_idparag ";
    $sUpdateParagrafo .= "   and exists(select 1 ";
    $sUpdateParagrafo .= "                from db_documento ";
    $sUpdateParagrafo .= "               where db03_docum   = db04_docum ";
    $sUpdateParagrafo .= "                 and db03_tipodoc = 1023 ";
    $sUpdateParagrafo .= "                 and db04_ordem = 1)";

    $this->execute($sUpdateDocPadrao);
    $this->execute($sUpdateParagrafo);
  }

  public function down() {}
}