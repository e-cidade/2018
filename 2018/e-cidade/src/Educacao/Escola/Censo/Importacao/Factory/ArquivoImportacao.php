<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\Educacao\Escola\Censo\Importacao\Factory;

/**
 * Class ArquivoImportacao
 * @package Ecidade\Educacao\Escola\Censo\Importacao\Factory
 */
abstract class ArquivoImportacao {

    /**
     * @param $iAno
     * @param $iCodigoInepEscola
     * @return \importacaoAtualizacaoAluno2010|\importacaoAtualizacaoAluno2011|\ImportacaoCenso2012|\importacaoCenso2015|\importacaoCenso2016|null
     */
    public static function getArquivoPorAno($iAno, $iCodigoInepEscola)
    {

        $oCenso = null;

        switch ($iAno) {
            case 2010:
                $oCenso = new \importacaoAtualizacaoAluno2010($iAno, $iCodigoInepEscola, 98);
                $oCenso->lLayoutComPipe = false;

                break;

            case 2011:
                $oCenso = new \importacaoAtualizacaoAluno2011($iAno, $iCodigoInepEscola, 96);

                break;

            case 2012:
            case 2013:
            case 2014:
                $iCodigoLayout = 184;

                if($iAno == 2013) {
                    $iCodigoLayout = 199;
                }

                if($iAno == 2014) {
                    $iCodigoLayout = 219;
                }

                $oCenso = new \ImportacaoCenso2012($iAno, $iCodigoInepEscola = null, $iCodigoLayout);
                $oCenso->lImportarAluno = true;
                $oCenso->lIncluirAlunoNaoEncontrado = true;
                $oCenso->lImportarAlunoAtivo = false;

                break;

            case 2015:
                $oCenso = new \importacaoCenso2015($iAno, $iCodigoInepEscola, 226);
                $oCenso->lImportarAluno = true;
                $oCenso->lImportarAlunoAtivo = false;
                $oCenso->lModuloEscola = false;

                break;

            case 2016:
            case 2017:
                $iCodigoLayout = $iAno == 2016 ? 255 : 281;
                $oCenso = new \importacaoCenso2016($iAno, $iCodigoInepEscola = null, $iCodigoLayout);

                break;
        }

        return $oCenso;
    }
}
