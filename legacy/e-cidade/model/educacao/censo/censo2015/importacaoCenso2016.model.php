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
 * Class importacaoCenso2016
 */
class importacaoCenso2016 extends importacaoCenso2015
{

    /**
     * Propriedade que guarda a coluna que contem o nome da escola no registro 00 do layout
     * @var int
     */
    protected $iColunaNomeEscola = 9;

    /**
     * importacaoCenso2016 constructor.
     * @param int $iAnoEscolhido
     * @param null $iCodigoInepEscola
     * @param $iCodigoLayout
     */
    public function __construct($iAnoEscolhido, $iCodigoInepEscola = null, $iCodigoLayout)
    {

        parent::__construct($iAnoEscolhido, $iCodigoInepEscola, $iCodigoLayout);
        $this->lImportarAluno = true;
        $this->lImportarAlunoAtivo = false;
        $this->lModuloEscola = false;
    }

    /**
     * @param array $aLinha
     * @return bool
     * @throws Exception
     */
    protected function validaAnoArquivo($aLinha)
    {
        $sData = $aLinha[7];
        $aData = explode("/", $sData);

        if (!empty($aData[2]) && $this->iAnoEscolhido != $aData[2]) {
            throw new BusinessException("Arquivo informado não pertence ao ano de {$this->iAnoEscolhido}");
        }

        return true;
    }
}
