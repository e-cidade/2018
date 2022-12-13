<?php
/**
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


namespace ECidade\Tributario\Integracao\Civitas\Model;

use ECidade\Tributario\Cadastro\Iptu\Recadastramento\Processamento;
use ECidade\Tributario\Cadastro\Iptu\Recadastramento\Arquivo\Civitas\Civitas;
use ECidade\Tributario\Integracao\Civitas\Model\Situacao;

/**
 * Classe que realiza a importação do arquivos de recadastramento
 * @author Alysson Zanette <alysson.zanette@dbseller.com.br>
 */
class Importador
{
    const ARQUIVO_LOTES = 1;
    const ARQUIVO_EDIFICACOES = 2;
    const ARQUIVO_TESTADAS = 3;
    /**
     * Array de arquivos a serem importados
     * @var array
     */
    private $aArquivos;

    public function __construct($aArquivos = array())
    {
        $this->aArquivos = $aArquivos;
    }

    /**
     * Processa os arquivos importados do Civitas
     * @return void
     * @throws ParameterException
     */
    private function importarArquivos()
    {

        if (empty($this->aArquivos)) {
            throw new \ParameterException("Nenhum arquivo informado.");
        }

        if (empty($this->aArquivos[0]["Data"])) {
            throw new \ParameterException("Data do arquivo não informada.");
        }

        $oDataArquivo    = new \DBDate($this->aArquivos[0]["Data"]);
        $sDataNomeSchema = $oDataArquivo->getDia() . $oDataArquivo->getMes() . $oDataArquivo->getAno();
        $sNomeSchema     = "importacao_" . $sDataNomeSchema;

        $aDescricaoArquivoImportado = array();
        $oCivitas       = new Civitas($sNomeSchema, false);
        $oProcessamento = new Processamento($sNomeSchema, false);

        $anousu = db_getsession("DB_anousu");

        foreach ($this->aArquivos as $arquivo) {
            switch ($arquivo["TipoArquivo"]) {
                case self::ARQUIVO_LOTES:
                    $caminhoArquivo = $arquivo["Caminho"] . $arquivo["Nome"];
                    $oCivitas->setArquivoLote($caminhoArquivo);
                    $aDescricaoArquivoImportado[] = "ARQUIVO DE LOTES";
                    break;

                case self::ARQUIVO_EDIFICACOES:
                    $caminhoArquivo = $arquivo["Caminho"] . $arquivo["Nome"];
                    $oCivitas->setArquivoConstrucao($caminhoArquivo);
                    $aDescricaoArquivoImportado[] = "ARQUIVO DE EDIFICAÇÕES";
                    break;
            }
        }

        $oProcessamento->setArquivosImportados($aDescricaoArquivoImportado);
        $oProcessamento->setDataArquivo($oDataArquivo);
        $oProcessamento->processar();
        $oCivitas->processar();
        $oProcessamento->calcularIptu($oCivitas->getMatriculasImportadas(), $anousu);
        $oProcessamento->incluirMatriculasImportadas();
    }

    /**
     * Função que encapsula a execução de importação de arquivos
     * @return sdtClass
     */
    public function processar()
    {
        Situacao::inicializar();

        try {
            $this->importarArquivos();
        }catch(\ParameterException $e){
            Situacao::addErro($e->getMessage());
        }

        return $this->getSituacao();
    }

    /**
     * Retorna a situação da importação
     * @return sdtClass
     */
    public function getSituacao()
    {
        return Situacao::getSituacao();
    }
}
