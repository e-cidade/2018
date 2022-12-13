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

class RegraLicitaconModalidadeTipoLicitacaoTipoObjeto extends RegraLicitacon
{

    protected $sMensagem = "A combinação entre Modalidade, Tipo de Licitação e Tipo de Objeto não é válida.\n\nVerifique as combinações possíveis entre as modalidades, os tipos de licitação e os tipos de objeto disponíveis no LicitaCon no Apêndice C.";

    /**
     * @var array
     */
    protected $aRegrasApendiceC = array(
        'CPP' => array(
            'NSA' => array('COM')
        ),
        'CPC' => array(
            'NSA' => array('OUS'),
        ),
        'CHP' => array(
            'MTC' => array('CSE', 'COM', 'OUS'),
            'MPR' => array('CSE', 'COM', 'LOC', 'OUS'),
            'MTX' => array('COM', 'CSE', 'OUS'),
            'TPR' => array('CSE', 'COM', 'OUS')
        ),
        'CNC' => array(
            'MDE' => array('CSE', 'COM', 'LOC', 'OSE', 'OUS'),
            'MLO' => array('ALB', 'CON', 'PER', 'OUS'),
            'MOQ' => array('CON', 'PER'),
            'MOT' => array('CON', 'PER'),
            'MOO' => array('CON', 'PER'),
            'MPP' => array('CON', 'PER'),
            'MTC' => array('ALB', 'CSE', 'OSE', 'OUS'),
            'MPR' => array('CSE', 'COM', 'LOC', 'OSE', 'OUS'),
            'MTX' => array('COM', 'CSE', 'OSE', 'OUS'),
            'MTO' => array('CON', 'PER'),
            'MTT' => array('CON', 'PER'),
            'MVT' => array('CON', 'PER'),
            'TPR' => array('CSE', 'COM', 'OSE', 'OUS'),
        ),
        'CNS' => array(
            'MTC' => array('OSE', 'OUS'),
            'NSA' => array('OUS', 'OSE')
        ),
        'CNV' => array(
            'MLO' => array('PER'),
            'MOQ' => array('PER'),
            'MOT' => array('PER'),
            'MOO' => array('PER'),
            'MPP' => array('PER'),
            'MTC' => array('CSE', 'OSE', 'OUS'),
            'MPR' => array('CSE', 'CON', 'COM', 'OSE', 'OUS'),
            'MTX' => array('COM', 'CSE', 'OSE', 'OUS'),
            'MTO' => array('PER'),
            'MTT' => array('PER'),
            'MVT' => array('PER'),
            'TPR' => array('CSE', 'COM', 'OSE', 'OUS')
        ),
        'ESE' => array(
            'MDE' => array('OUS', 'OSE', 'CSE', 'COM'),
            'MOP' => array('ALB'),
            'MRE' => array('OSE'),
            'MCA' => array('OUS'),
            'MDB' => array('ALB'),
            'MTC' => array('OUS', 'OSE'),
            'MPR' => array('OUS', 'OSE', 'CSE', 'COM'),
            'MTX' => array('COM', 'CSE', 'OSE', 'OUS'),
            'TPR' => array('OUS', 'OSE', 'CSE', 'COM')
        ),
        'EST' => array(
            'MDE' => array('OUS', 'OSE', 'CSE', 'COM'),
            'MOP' => array('ALB'),
            'MRE' => array('OSE'),
            'MCA' => array('OUS'),
            'MDB' => array('ALB'),
            'MTC' => array('OUS', 'OSE'),
            'MPR' => array('OUS', 'OSE', 'CSE', 'COM'),
            'MTX' => array('COM', 'CSE', 'OSE', 'OUS'),
            'TPR' => array('OUS', 'OSE', 'CSE', 'COM')
        ),
        'LEE' => array(
            'MLO' => array('ALB')
        ),
        'LEI' => array(
            'MLO' => array('ALB')
        ),
        'MAI' => array(
            'NSA' => array('OSE', 'OUS')
        ),
        'PRE' => array(
            'MDE' => array('CSE', 'COM', 'LOC', 'OSE', 'OUS'),
            'MLO' => array('ALB', 'CON', 'OUS', 'PER'),
            'MOO' => array('CON', 'PER'),
            'MPR' => array('CSE', 'COM', 'LOC', 'OSE', 'OUS'),
            'MTX' => array('COM', 'CSE', 'OSE', 'OUS')
        ),
        'PRP' => array(
            'MDE' => array('CSE', 'COM', 'LOC', 'OSE', 'OUS'),
            'MLO' => array('ALB', 'CON', 'OUS', 'PER'),
            'MOO' => array('CON', 'PER'),
            'MPR' => array('CSE', 'COM', 'LOC', 'OSE', 'OUS'),
            'MTX' => array('COM', 'CSE', 'OSE', 'OUS')
        ),
        'PRD' => array(
            'NSA' => array('ALB', 'CSE', 'COM', 'CON', 'LOC', 'OSE', 'OUS', 'PER')
        ),
        'PRI' => array(
            'NSA' => array('ALB', 'CSE', 'COM', 'OSE', 'OUS')
        ),
        'RDE' => array(
            'MDE' => array('CSE', 'COM', 'OSE', 'OUS'),
            'MOP' => array('ALB'),
            'MCA' => array('OUS'),
            'MTC' => array('OSE', 'OUS'),
            'MPR' => array('CSE', 'COM', 'OSE', 'OUS'),
            'MTX' => array('COM', 'CSE', 'OSE', 'OUS'),
            'TPR' => array('CSE', 'COM', 'OSE', 'OUS')
        ),
        'RDC' => array(
            'MDE' => array('CSE', 'COM', 'OSE', 'OUS'),
            'MOP' => array('ALB'),
            'MCA' => array('OUS'),
            'MTC' => array('OSE', 'OUS'),
            'MPR' => array('CSE', 'COM', 'OSE', 'OUS'),
            'MTX' => array('COM', 'CSE', 'OSE', 'OUS'),
            'TPR' => array('CSE', 'COM', 'OSE', 'OUS')
        ),
        'RPO' => array(
            'NSA' => array('CSE', 'COM', 'OSE', 'OUS')
        ),
        'RIN' => array(
            'MLO' => array('ALB'),
            'MTC' => array('CSE', 'OSE', 'OUS'),
            'MPR' => array('CSE', 'COM', 'OSE', 'OUS'),
            'MTX' => array('COM', 'CSE', 'OSE', 'OUS'),
            'TPR' => array('CSE', 'COM', 'OSE', 'OUS')
        ),
        'TMP' => array(
            'MLO' => array('PER'),
            'MOQ' => array('PER'),
            'MOT' => array('PER'),
            'MOO' => array('PER'),
            'MPP' => array('PER'),
            'MTC' => array('CSE', 'OSE', 'OUS'),
            'MPR' => array('CSE', 'COM', 'LOC', 'OSE', 'OUS'),
            'MTX' => array('COM', 'CSE', 'OSE', 'OUS'),
            'MTO' => array('PER'),
            'MTT' => array('PER'),
            'MVT' => array('PER'),
            'TPR' => array('CSE', 'COM', 'OSE', 'OUS')
        )
    );

    protected function getRegras()
    {
        return $this->aRegrasApendiceC;
    }

    public function regra()
    {
        $sModalidade = $this->oLicitacao->getModalidade()->getSiglaTipoCompraTribunal();
        $sTipoLicitacao = null;
        $sTipoObjeto = null;
        $aRegras = $this->getRegras();

        if (isset($this->aAtributosDinamicos[LicitacaoAtributosDinamicos::NOME_TIPO_LICITACAO])) {
            $sTipoLicitacao = $this->aAtributosDinamicos[LicitacaoAtributosDinamicos::NOME_TIPO_LICITACAO];
        }

        if (isset($this->aAtributosDinamicos[LicitacaoAtributosDinamicos::NOME_TIPO_OBJETO])) {
            $sTipoObjeto = $this->aAtributosDinamicos[LicitacaoAtributosDinamicos::NOME_TIPO_OBJETO];
        }

        if (empty($sTipoObjeto)) {
            $this->sMensagem = "O campo Tipo de Objeto é de preenchimento obrigatório.";
            return false;
        }

        if (empty($sTipoLicitacao)) {
            $this->sMensagem = "O campo Tipo de Licitação é de preenchimento obrigatório.";
            return false;
        }

        if (!isset($aRegras[$sModalidade]) || !isset($aRegras[$sModalidade][$sTipoLicitacao])) {
            return false;
        }

        if (!in_array($sTipoObjeto, $aRegras[$sModalidade][$sTipoLicitacao])) {
            return false;
        }

        return true;
    }
}