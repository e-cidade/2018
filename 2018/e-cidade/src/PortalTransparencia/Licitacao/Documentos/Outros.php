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

namespace ECidade\PortalTransparencia\Licitacao\Documentos;

class Outros
{


    /**
     * codigo do documento
     * @var array
     */
    private $documentos;


    /**
     * Setter documento
     * @param integer
     */
    public function setDocumento($documentos)
    {
        $this->documentos = $documentos;
    }

    /**
     * Getter documento
     * @param array
     */
    public function getDocumentos()
    {
        return $this->documentos;
    }




    public function __construct()
    {
    }

    private static $documentosNaoEnviados = array ( 4, 6, 7, 8, 9, 10, 11, 12, 13, 15, 32, 33, 34);

    /**
     * retorna os documentos que podem ser configurados para envio ao portal transparencia
     * @return array
     */
    public static function getOutrosDocumentos()
    {

        $todosDocumentos   = \LicitaConTipoDocumento::$aDescricaoTipoDocumento;
        $aDocumentos       = array();
        $aDocumentosSalvos = array();

        $oDaoDocumentos = new \cl_documentolicitacaotransparencia();
        $sSql           = $oDaoDocumentos->sql_query_file();
        $rsDocumentos   = db_query($sSql);

        if (!$rsDocumentos) {
            throw new \DBException("Erro ao buscar os arquivos do tipo Outros.");
        }

        if (pg_num_rows($rsDocumentos) > 0) {
            $aDocumentosSalvos = \db_utils::makeCollectionFromRecord($rsDocumentos, function ($oDados) {
                return $oDados->l48_documento;
            });
        }

        //Remove Documentos que não são do tipo Outros
        foreach ($todosDocumentos as $key => $value) {
            if (in_array($key, self::$documentosNaoEnviados)) {
                unset($todosDocumentos[$key]);
            } else {
                $oTipoDocumento = new \stdClass();
                $oTipoDocumento->iTipoDocumento = $key;
                $oTipoDocumento->sTipoDocumento = $value;
                $oTipoDocumento->lSelecionado   = false;

                if (sizeof($aDocumentosSalvos) > 0) {
                    if (in_array($key, $aDocumentosSalvos)) {
                        $oTipoDocumento->lSelecionado  = true;
                    }
                }
                $aDocumentos[] = $oTipoDocumento;
            }
        }
        return $aDocumentos;
    }

    public static function salvarOutrosDocumentos($aDocumentos)
    {

        $oDaoDocumentos = new \cl_documentolicitacaotransparencia();
        $result = $oDaoDocumentos->deleteAll();

        if (!$result) {
            throw new \DBException('Erro ao excluir os Documentos.');
        }

        if (!empty($aDocumentos)) {
            foreach ($aDocumentos as $documento) {
                $oDaoDocumentos->l48_sequencial = false;
                $oDaoDocumentos->l48_documento  = $documento;
                $oDaoDocumentos->incluir();
            }

            if ($oDaoDocumentos->erro_status == 0) {
                throw new \DBException('Erro ao incluir os Documentos.');
            }
        }
    }
}
