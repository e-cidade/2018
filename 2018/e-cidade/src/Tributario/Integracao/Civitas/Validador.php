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


namespace ECidade\Tributario\Integracao\Civitas;

use ECidade\Tributario\Integracao\Civitas\Model\Situacao;
use \cl_setor as Setor;

/**
 * Classe responsável pela validação de arquivos civitas na importação do Webservice.
 * @author Alysson Zanette <alysson.zanette@dbseller.com.br>
 */
class Validador
{
    /**
     * @var array
     */
    private $aArquivos;

    /**
     *@param array $aArquivos [Array de arquivos a serem validados]
     */
    public function __construct($aArquivos = array())
    {
        $this->aArquivos = $aArquivos;
    }

    /**
     * Valida se os arquivos estão prontos para importação.
     * @return bool
     */
    public function validarArquivosImportacao()
    {
        Situacao::inicializar();
        $this->validarParametrosArquivos();
        $this->validarArquivos();

        if (Situacao::getSituacao()->situacao == 2) {
            return false;
        }

        return true;
    }
    /**
     * Retorna a situação do validador
     * @return \stdClass
     */
    public function getSituacao()
    {
        return Situacao::getSituacao();
    }
    /**
     * Valida se os parametros dos arquivos estão de acordo e alguma arquivo esta vazio.
     * @return void
     */
    private function validarParametrosArquivos()
    {
        if (empty($this->aArquivos)) {
            Situacao::addErro("Nenhum arquivo informado.");
        }

        foreach ($this->aArquivos as $arquivo) {
            if (empty($arquivo["Data"])) {
                Situacao::addErro("Data do arquivo {$arquivo["Nome"]} não informada.");
            }

            $conteudoArquivo = file($arquivo["Caminho"] . $arquivo["Nome"]);

            if (empty($conteudoArquivo) || (count($conteudoArquivo) === 1 && $conteudoArquivo[0] === '')) {
                Situacao::addErro("O arquivo {$arquivo["Nome"]} está vazio.");
            }

            $conteudoArquivo = null;
        }
    }

    /**
     * Valida os arquivos de acordo com o tipo de arquivo 1 - Lote, 2 - Edificacoes, 3 - Testada
     * @return void
     */
    private function validarArquivos()
    {
        foreach ($this->aArquivos as $arquivo) {
            $caminhoArquivo = $arquivo["Caminho"] . $arquivo["Nome"];
            switch ($arquivo['TipoArquivo']) {
                case 1:
                    $this->validarArquivoLote($caminhoArquivo);
                    break;
                case 2:
                    $this->validaArquivoEdificacoes($caminhoArquivo);
                    break;
                case 3:
                    $this->validarArquivoTestada($caminhoArquivo);
                    break;
            }
        }
    }

    /**
     * Valida os dados de cada coluna do arquivo Lote.
     * @param string $caminhoArquivo
     * @return void
     */
    private function validarArquivoLote($caminhoArquivo)
    {
        $oArquivoLote     = $this->getArquivo($caminhoArquivo);

        $aDados[1] = $this->getDadosArquivo($oArquivoLote, 1);
        $aDados[2] = $this->getDadosArquivo($oArquivoLote, 2);
        $aDados[3] = $this->getDadosArquivo($oArquivoLote, 3);
        $aDados[4] = $this->getDadosArquivo($oArquivoLote, 4);
        $aDados[5] = $this->getDadosArquivo($oArquivoLote, 5);
        $aDados[6] = $this->getDadosArquivo($oArquivoLote, 6);
        $aDados[8] = $this->getDadosArquivo($oArquivoLote, 8);

        for ($i=13; $i < 31; $i++) {
            $aDados[$i] = $this->getDadosArquivo($oArquivoLote, $i);
        }

        $this->validarDadosSistema($aDados, 1);
    }

    /**
     * Valida os dados de cada coluna do arquivo Edificacoes.
     * @param string $caminhoArquivo
     * @return void
     */
    private function validaArquivoEdificacoes($caminhoArquivo)
    {
        $oArquivoLote = $this->getArquivo($caminhoArquivo);

        $aDados[1] = $this->getDadosArquivo($oArquivoLote, 1);
        $aDados[2] = $this->getDadosArquivo($oArquivoLote, 2);
        $aDados[3] = $this->getDadosArquivo($oArquivoLote, 3);
        $aDados[4] = $this->getDadosArquivo($oArquivoLote, 4);
        $aDados[5] = $this->getDadosArquivo($oArquivoLote, 5);
        $aDados[6] = $this->getDadosArquivo($oArquivoLote, 6);
        $aDados[8] = $this->getDadosArquivo($oArquivoLote, 8);
        $aDados[9] = $this->getDadosArquivo($oArquivoLote, 9);

        for ($i=17; $i < 51; $i++) {
            $aDados[$i] = $this->getDadosArquivo($oArquivoLote, $i);
        }

        $this->validarDadosSistema($aDados, 2);
    }

    /**
     *@todo Validar arquivo Testada
     */
    private function validarArquivoTestada($caminhoArquivo)
    {
    }

    /**
     * Retorna o objeto para manipulação de arquivo csv
     * @param string $caminhoArquivo
     * @return \SplFileObject
     */
    private function getArquivo($caminhoArquivo)
    {
        $oArquivo = new \SplFileObject($caminhoArquivo);
        $oArquivo->setFlags(\SplFileObject::READ_CSV);
        $oArquivo->setCsvControl('|');

        return $oArquivo;
    }

    /**
     * Retorna dados distintos em uma coluna do arquivo csv
     * @param \SplFileObject $oArquivo
     * @param int $pos
     * @return array
     */
    private function getDadosArquivo($oArquivo, $pos)
    {
        $aDados = array();

        $aLinhasArquivo = new \LimitIterator($oArquivo, 1);

        foreach ($aLinhasArquivo as $aLinha) {
            if (!in_array($aLinha[$pos], $aDados)) {
                $aDados[] = $aLinha[$pos];
            }
        }
        return $aDados;
    }

    /**
     * Monta a sql para consulta no sistema.
     * @param string $campo
     * @param string $tabela
     * @param string $where
     * @return string
     */
    private function montarSqlDados($tabela, $campo = '*', $where = '')
    {
        return "SELECT {$campo} FROM {$tabela} " . (empty($where) ? '' : "WHERE {$where}");
    }

    /**
     * Retorna a tabela e campo no sistema de acordo com o código da coluna no arquivo csv
     * @param int $codigoCampo
     * @return array
     */
    private function getTabelaCampo($codigoCampo, $tipoArquivo)
    {
        $tabelaCampo = array();

        if ($tipoArquivo == 1) {
            switch ($codigoCampo) {
                case 1:
                    $tabelaCampo = array('descr' => 'Matricula', 'tabela' => 'iptubase', 'campo' => 'j01_matric');
                    break;
                case 2:
                    $tabelaCampo = array('descr' => 'Idbql', 'tabela' => 'lote', 'campo' => 'j34_idbql');
                    break;
                case 3:
                    $tabelaCampo = array('descr' => 'Setor', 'tabela' => 'lote', 'campo' => 'j34_setor');
                    break;
                case 4:
                    $tabelaCampo = array('descr' => 'Quadra', 'tabela' => 'lote', 'campo' => 'j34_quadra');
                    break;
                case 5:
                    $tabelaCampo = array('descr' => 'Lote', 'tabela' => 'lote', 'campo' => 'j34_lote');
                    break;
                case 6:
                    $tabelaCampo = array('descr' => 'Rua', 'tabela' => 'ruas', 'campo' => 'j14_codigo');
                    break;
                case 8:
                    $tabelaCampo = array('descr' => 'Bairro', 'tabela' => 'bairro', 'campo' => 'j13_codi');
                    break;
                default:
                    if ($codigoCampo > 12 && $codigoCampo < 31) {
                        $tabelaCampo = array(
                        'descr'  => 'Característica',
                            'tabela' => 'caracter',
                            'campo'  => ($codigoCampo % 2 > 0) ? 'j31_grupo' : 'j31_codigo');
                    }
                    break;
            }
        } else if ($tipoArquivo == 2) {
            switch ($codigoCampo) {
                case 1:
                    $tabelaCampo = array('descr' => 'Matricula', 'tabela' => 'iptubase', 'campo' => 'j01_matric');
                    break;
                case 2:
                    $tabelaCampo = array('descr' => 'Idbql', 'tabela' => 'lote', 'campo' => 'j34_idbql');
                    break;
                case 3:
                    $tabelaCampo = array('descr' => 'Setor', 'tabela' => 'lote', 'campo' => 'j34_setor');
                    break;
                case 4:
                    $tabelaCampo = array('descr' => 'Quadra', 'tabela' => 'lote', 'campo' => 'j34_quadra');
                    break;
                case 5:
                    $tabelaCampo = array('descr' => 'Lote', 'tabela' => 'lote', 'campo' => 'j34_lote');
                    break;
                case 6:
                    $tabelaCampo = array('descr' => 'Rua', 'tabela' => 'ruas', 'campo' => 'j14_codigo');
                    break;
                case 8:
                    $tabelaCampo = array('descr' => 'Bairro', 'tabela' => 'bairro', 'campo' => 'j13_codi');
                    break;
                case 9:
                    $tabelaCampo = array('descr' => 'Construção', 'tabela' => 'iptuconstr', 'campo' => 'j39_idcons');
                    break;
                default:
                    if ($codigoCampo > 16 && $codigoCampo < 51) {
                        $tabelaCampo = array(
                            'descr'  => 'Característica',
                            'tabela' => 'caracter',
                            'campo'  => ($codigoCampo % 2 > 0) ? 'j31_grupo' : 'j31_codigo');
                    }
                    break;
            }
        } else if ($tipoArquivo == 3) {
            /**
             * @todo mapear campos do arquivo testada
             */
        }

        return $tabelaCampo;
    }

    /**
     * Valida se os dados das colunas de um arquivo estão cadastrados no sistema.
     * @param array $aDados
     * @param int $tipoArquivo
     * @return void
     */
    private function validarDadosSistema($aDados, $tipoArquivo)
    {
        foreach ($aDados as $index => $aDadosColuna) {
            $aTabelaCampo      = $this->getTabelaCampo($index, $tipoArquivo);
            $sDescricaoArquivo = $this->getDescicaoArquivo($tipoArquivo);
            $sSql              = $this->montarSqlDados($aTabelaCampo['tabela'], 'distinct ' . $aTabelaCampo['campo']);
            $rs                = \db_query($sSql);

            if (!empty($aTabelaCampo['campo']) && $rs) {
                $aDadosSistema = \db_utils::makeCollectionFromRecord($rs, function ($dados) use ($aTabelaCampo) {
                    return $dados->{$aTabelaCampo['campo']};
                });

                foreach ($aDadosColuna as $dado) {
                    if (!empty($dado) && !in_array($dado, $aDadosSistema)) {
                        Situacao::addErro("{$aTabelaCampo['descr']} com código {$dado} no arquivo {$sDescricaoArquivo}, não existe no sistema.");
                    }
                }
            }
        }
    }

    /**
     * Retorna a descrição do arquivo de acordo com o tipo
     * @param int $tipoArquivo
     * @return string
     */
    private function getDescicaoArquivo($tipoArquivo)
    {
        switch ($tipoArquivo) {
            case 1:
                return 'Lote';
                break;

            case 2:
                return 'Edificacoes';
                break;

            case 3:
                return 'Testada';
                break;
        }
    }
}
