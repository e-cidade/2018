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
 * Interface AnexoSICONFI
 * Interface para implementa��o de classes de emiss�o de relat�rios SICONFI.
 */
interface AnexoSICONFI {

  /**
   * Constante que identifica o c�digo dos per�odos para os anexos do SICONFI.
   */
  const CODIGO_PERIODO = 1;

  /**
   * Constante que identifica o tipo de arquivo CSV.
   */
  const TIPO_CSV = "csv";

  /**
   * Constante que identifica o tipo de arquivo PDF.
   */
  const TIPO_PDF = "pdf";

  /**
   * Gera o relat�rio em um arquivo no formato informado por par�metro.
   * @param string $sFormato Formado do arquivo a ser gerado.
   *
   * @return string Nome do arquivo gerado.
   */
  public function gerar($sFormato);

  /**
   * Gera o relat�rio no formato CSV e salva o nome do arquivo gerado em um atributo.
   * @return void
   */
  public function gerarCSV();

  /**
   * Gera o relat�rio no formato PDF e salva o nome do arquivo gerado em um atributo.
   * @return void
   */
  public function gerarPDF();

}