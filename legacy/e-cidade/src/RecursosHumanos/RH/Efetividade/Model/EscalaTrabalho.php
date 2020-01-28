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

namespace ECidade\RecursosHumanos\RH\Efetividade\Model;

use ECidade\RecursosHumanos\RH\Efetividade\Model\Jornada;

/**
 * Classe referente a organização de uma escala de trabalho e suas jornadas
 *
 * Class EscalaTrabalhoJornadas
 * @package ECidade\RecursosHumanos\RH\Efetividade\Model
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class EscalaTrabalho
{
    /**
     * Código da escala de trabalho
     * @var integer
     */
    private $iCodigo;

    /**
     * Descrição da escala de trabalho
     * @var string
     */
    private $sDescricao;

    /**
     * Data base de início da escala
     * @var \DBDate
     */
    private $oDataBase;

    /**
     * Jornadas configuradas para escala
     * @var array
     */
    private $aJornadas = array();

    /**
     * @var bool
     */
    private $lRevezamento = false;

    /**
     * Reotrn o código da escala de trabalho
     * @return int
     */
    public function getCodigo()
    {
        return $this->iCodigo;
    }

    /**
     * Retorn a descrição da escala de trabalho
     * @return string
     */
    public function getDescricao()
    {
        return $this->sDescricao;
    }

    /**
     * Retorna a data base configurada para início da escala de trabalho
     * @return \DBDate
     */
    public function getDataBase()
    {
        return $this->oDataBase;
    }

    /**
     * Retorna as jornadas configuradas para escala de trabalho
     * @return Jornada[]
     */
    public function getJornadas()
    {
        return $this->aJornadas;
    }

    /**
     * Retorna se a escala é de revezamento ou não
     * @return bool
     */
    public function getRevezamento()
    {
        if ($this->lRevezamento == 'f' || $this->lRevezamento == 'false') {
            return false;
        }

        if ($this->lRevezamento == 't' || $this->lRevezamento == 'true') {
            return true;
        }

        return $this->lRevezamento;
    }

    /**
     * @param int $iCodigo
     */
    public function setCodigo($iCodigo)
    {
        $this->iCodigo = $iCodigo;
    }

    /**
     * @param string $sDescricao
     */
    public function setDescricao($sDescricao)
    {
        $this->sDescricao = $sDescricao;
    }

    /**
     * @param \DBDate $oDataBase
     */
    public function setDataBase(\DBDate $oDataBase)
    {
        $this->oDataBase = $oDataBase;
    }

    /**
     * @para bool $lRevezamento
     */
    public function setRevezamento($lRevezamento = false)
    {
        $this->lRevezamento = $lRevezamento;
    }

    /**
     * Organiza as jornadas de trabalho da escala pela ordem de configuração
     *
     * @param \ECidade\RecursosHumanos\RH\Efetividade\Model\Jornada $oJornada
     * @param int $iOrdemHorario
     */
    public function addJornada(Jornada $oJornada, $iOrdemHorario)
    {

        if (!array_key_exists($iOrdemHorario, $this->aJornadas)) {
            $this->aJornadas[$iOrdemHorario] = $oJornada;
        }

        ksort($this->aJornadas);
    }
}
