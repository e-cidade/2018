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

class documentoTemplate  {

  private $sAquivoTemplate = '';
  private $lControle       = false;

  /**
   * Este model busca o arquivo correspondente pelo tipo informado
   * na criação do objeto.
   * Busca pelo documento na DocumentoTemplate
   * se não enontarar busca o documento na DocumentoTemplatePadrao
   */
  public function __construct($iTipo='', $iCodDocumento=null, $sCaminhoArquivo='', $lTemTransacaoAtiva = false) {

    global $conn;

    require_once(modification('dbforms/db_funcoes.php'));

    if((int) $iTipo == "" || (int) $iTipo == 0) {
      throw new Exception('Valor do tipo Informado não válido!');
    }

    if (trim($sCaminhoArquivo) == '') {
      $sArquivoSxw      = "docTemplate" . date("YmdHis") . db_getsession("DB_id_usuario") . ".sxw";
      $sCaminhoTemplate = "tmp/" . $sArquivoSxw;
    } else {
      $sCaminhoTemplate = $sCaminhoArquivo;
    }

    $db82_templatetipo = (int) $iTipo;

    $oDaoDocumentoTemplate    = new cl_db_documentotemplate;
    $sWhereDocumentoTemplate  = "     db82_templatetipo = {$db82_templatetipo}";

    if ( isset($iCodDocumento) && trim($iCodDocumento) != '' ) {
      $sWhereDocumentoTemplate .= " and db82_sequencial   = {$iCodDocumento}    ";
    }

    $sSqlDocumentoTemplate = $oDaoDocumentoTemplate->sql_query_file(null, "db82_arquivo", null, $sWhereDocumentoTemplate);
    $rsDocumentoTemplate   = $oDaoDocumentoTemplate->sql_record($sSqlDocumentoTemplate);
    if($oDaoDocumentoTemplate->numrows == 1) {

      $oArquivoSxw = db_utils::fieldsMemory($rsDocumentoTemplate,0);

      if (!$lTemTransacaoAtiva) {
        db_inicio_transacao();
      }

      $lArquivoExportado = pg_lo_export($oArquivoSxw->db82_arquivo, $sCaminhoTemplate, $conn);

      if (!$lTemTransacaoAtiva) {
        db_fim_transacao();
      }

      if (!$lArquivoExportado) {
        throw new Exception("Erro ao gerar arquivo do template!");
      }
      $this->sAquivoTemplate = $sCaminhoTemplate;

      $this->lControle = true;
    } else if ($oDaoDocumentoTemplate->numrows == 0) {
      $this->lControle = false;
    } else {
      $this->lControle = true;
      throw new Exception('Existe mais de um template cadastrado.');
    }

    if (!$this->lControle) {

      $oDaoDocumentoTemplatePadrao = new cl_db_documentotemplatepadrao;
      $sSqlDocumentoTemplatePadrao = $oDaoDocumentoTemplatePadrao->sql_query_file(null, "db81_nomearquivo", null, "db81_templatetipo = $db82_templatetipo");
      $rsDocumentoTemplatePadrao   = $oDaoDocumentoTemplatePadrao->sql_record($sSqlDocumentoTemplatePadrao);

      if ($oDaoDocumentoTemplatePadrao->numrows == 1) {

        $oArquivoSxw = db_utils::fieldsMemory($rsDocumentoTemplatePadrao,0);
        if(file_exists($oArquivoSxw->db81_nomearquivo)) {
          $this->sAquivoTemplate = $oArquivoSxw->db81_nomearquivo;
        }

      } else if ($oDaoDocumentoTemplatePadrao->numrows == 0) {
        throw new Exception('Nenhum template padrão cadastrado.');
      } else {
        throw new Exception('Existe mais de um template padrão cadastrado.');
      }
    }
  }

  public function getArquivoTemplate() {
    return $this->sAquivoTemplate;
  }
}
