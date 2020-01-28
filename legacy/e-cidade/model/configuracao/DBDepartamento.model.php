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
 * Classe para definição e controle dos departamentos.
 *
 * @package configuração
 * @author Luiz Marcelo Schmitt
 * @version 1.0
 */
class DBDepartamento
{

    /**
     * Código do departamento.
     *
     * @var integer $iCodigoDepartamento
     */
    protected $iCodigoDepartamento;

    /**
     * Nome do departamento.
     *
     * @var string $sNomeDepartamento
     */
    protected $sNomeDepartamento;

    /**
     * Instituição
     * @var Instituicao
     */
    protected $oInstituicao;

    /**
     * Código sequencial da instituição
     * @var integer
     */
    protected $iCodigoInstituicao;

    /**
     * Telefone do departamento
     * @var string
     */
    protected $sTelefone;

    /**
     * Fax do departamento
     * @var string
     */
    protected $sFax;

    /**
     * Ramal do departamento
     * @var string
     */
    protected $sRamal;

    /**
     * E-mail do departamento
     * @var string
     */
    protected $sEmailDepartamento;

    /**
     * Método construtor da classe.
     *
     * @param integer $iCodigoDepartamento
     * @throws Exception
     */
    public function __construct($iCodigoDepartamento = null)
    {
        if (is_null($iCodigoDepartamento)) {
            return;
        }

        $oDaoDepartamento = db_utils::getDao("db_depart");
        $sSqlDadosDepartamento = $oDaoDepartamento->sql_query_file($iCodigoDepartamento);
        $rsDadosDepartamento = $oDaoDepartamento->sql_record($sSqlDadosDepartamento);

        if ($oDaoDepartamento->erro_status == '0') {
            throw new Exception("Não foi possível localizar o departamento pelo código: $iCodigoDepartamento");
        }

        $oDadosDepartamento = db_utils::fieldsMemory($rsDadosDepartamento, 0);
        $this->iCodigoDepartamento = $oDadosDepartamento->coddepto;
        $this->sNomeDepartamento = $oDadosDepartamento->descrdepto;
        $this->iCodigoInstituicao = $oDadosDepartamento->instit;
        $this->sTelefone = $oDadosDepartamento->fonedepto;
        $this->sRamal = $oDadosDepartamento->ramaldepto;
        $this->sFax = $oDadosDepartamento->faxdepto;
        $this->sEmailDepartamento = $oDadosDepartamento->emaildepto;
        unset($oDadosDepartamento);
    }

    /**
     * metodo criado para retornar as divisoes de um departamento
     * @return array objeto DBDivisaoDepartamento
     */
    public function getDivisoes()
    {
        $oDaoDepartDiv = db_utils::getDao("departdiv");
        $sWhere = "t30_ativo = true and t30_depto= {$this->getCodigo()}";
        $sSql = $oDaoDepartDiv->sql_query_file(null, "t30_codigo", 't30_descr', $sWhere);
        $rsDivisao = $oDaoDepartDiv->sql_record($sSql);
        $aDivisoes = array();

        if ($oDaoDepartDiv->numrows == 0) {
            throw new Exception("Não foram encontrada divisões para o departamento " . $this->getCodigo() . " - " . $this->getNomeDepartamento());
        }
        for ($iDivisao = 0; $iDivisao < $oDaoDepartDiv->numrows; $iDivisao++) {
            $oDadosDivisao = db_utils::fieldsMemory($rsDivisao, $iDivisao);
            $aDivisoes[] = new DBDivisaoDepartamento($oDadosDivisao->t30_codigo);
        }
        return $aDivisoes;
    }

    /**
     * @return string
     */
    public function getRamal()
    {
        return $this->sRamal;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->sFax;
    }

    /**
     * Retorna o nome do departamento.
     * @return $this->sNomeDepartamento
     */
    public function getNomeDepartamento()
    {
        return $this->sNomeDepartamento;
    }

    /**
     * Retorna o codigo
     * @return integer
     */
    public function getCodigo()
    {
        return $this->iCodigoDepartamento;
    }

    /**
     * Retorna a instituição do departamento
     * @return Instituicao
     */
    public function getInstituicao()
    {
        if (!empty($this->iCodigoInstituicao)) {
            $this->oInstituicao = InstituicaoRepository::getInstituicaoByCodigo($this->iCodigoInstituicao);
        }
        return $this->oInstituicao;
    }

    /**
     * Busca os dados referentes ao endereço do departamento e os retorna como um stdClass
     * @return stdClass
     */
    public function getEndereco()
    {
        $oEndereco = new stdClass();
        $oEndereco->iNumero = null;
        $oEndereco->sRua = '';
        $oEndereco->sComplemento = '';
        $oEndereco->sBairro = '';

        if (empty($this->iCodigoDepartamento)) {
            return $oEndereco;
        }

        $oDaoDepartEnder = new cl_db_departender();
        $sCamposDepartEnder = "j14_nome as rua, numero, compl, j13_descr as bairro";
        $sSqlDepartEnder = $oDaoDepartEnder->sql_query($this->iCodigoDepartamento, $sCamposDepartEnder, null, null);
        $rsDepartEnder = db_query($sSqlDepartEnder);

        if (!$rsDepartEnder) {
            throw new Exception("Não foi possível buscar o endereço do Departamento.");
        }

        if (pg_num_rows($rsDepartEnder) > 0) {
            $oDados = db_utils::fieldsMemory($rsDepartEnder, 0);

            $oEndereco->iNumero = $oDados->numero;
            $oEndereco->sRua = $oDados->rua;
            $oEndereco->sComplemento = $oDados->compl;
            $oEndereco->sBairro = $oDados->bairro;
        }

        return $oEndereco;
    }

    /**
     * Retorna o telefone do departamento
     * @return string
     */
    public function getTelefone()
    {
        return $this->sTelefone;
    }

    /**
     * Define o telefone do departamento
     * @param string $sTelefone
     */
    public function setTelefone($sTelefone)
    {
        $this->sTelefone = $sTelefone;
    }

    /**
     * Retorna o e-mail do departamento
     * @return string
     */
    public function getEmailDepartamento()
    {
        return $this->sEmailDepartamento;
    }

    /**
     * Define o e-mail do departamento
     * @param string $sEmailDepartamento
     */
    public function setEmailDepartamento($sEmailDepartamento)
    {
        $this->sEmailDepartamento = $sEmailDepartamento;
    }

    public function setCodigoDepartamento($iCodigoDepartamento)
    {
        $this->iCodigoDepartamento = $iCodigoDepartamento;
    }

    public function setNomeDepartamento($sNomeDepartamento)
    {
        $this->sNomeDepartamento = $sNomeDepartamento;
    }
}