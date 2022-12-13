<?php

namespace ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Modelo;

use db_utils;
use DBException;
use DBseller\Mensageria\Library\Cliente;
use ECidade\Patrimonial\Protocolo\Repositorio\ProcessoRepositorio;
use Instituicao;

/**
 * Class MensageriaProcesso
 * @package ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Modelo
 */
class MensageriaProcesso
{
    /**
     * @param $codigoProcesso
     * @param bool $cancelamento
     * @return bool|null
     */
    public static function enviar($codigoProcesso, $cancelamento = false)
    {
        try {
            $caminhoPlugin = 'plugins/mensageria';
            $instalado = is_dir($caminhoPlugin) && file_exists($caminhoPlugin . '/Manifest.xml');

            return $instalado ? self::enviarNotificacao($codigoProcesso, $cancelamento) : null;
        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * @param $codigoProcesso
     * @param $cancelamento
     * @return bool
     * @throws DBException
     */
    private static function enviarNotificacao($codigoProcesso, $cancelamento)
    {
        require_once modification('integracao_externa/mensageria/DBSeller/Mensageria/Library/Cliente.php');

        $resultado = db_query("SELECT * FROM mensageriaprocesso LIMIT 1");

        if (!$resultado) {
            throw new DBException('N�o foi poss�vel buscar os dados na notifica��o padr�o.');
        }

        $notificacao = db_utils::fieldsMemory($resultado, 0);

        if ($notificacao->p101_notificarreceberprocesso == 'f') {
            return true;
        }

        $instituicao = new Instituicao();
        $prefeitura = $instituicao->getDadosPrefeitura();
        $sistema = 'e-cidade.' . strtolower($prefeitura->getMunicipio());

        $processo = ProcessoRepositorio::encontrar($codigoProcesso);
        $numero = $processo->getNumero();
        $iAno = $processo->getAno();

        if ($cancelamento) {
            $assunto = 'Cancelamento da Transfer�ncia';
            $mensagemPadrao = 'A transfer�ncia do processo [numero]/[ano] foi cancelada. Desconsidere qualquer notifica��o referente � mesma.';
        } else {
            $assunto = 'Transfer�ncia de Processo';
            $mensagemPadrao = 'O processo [numero]/[ano] foi transferido para voc� e deve ser movimentado em [dias] dias.';
        }

        $conteudo = str_replace(
            array('[numero]', '[ano]', '[dias]'),
            array($numero, $iAno, $notificacao->p101_diasprazo),
            $mensagemPadrao
        );

        $mensagem = array(
            'iTipo' => Cliente::TIPO_NOTIFICACAO,
            'sAssunto' => $assunto,
            'sConteudo' => $conteudo,
            'aDestinatarios' => array(array('sLogin' => $processo->getLogin(), 'sSistema' => $sistema))
        );

        Cliente::enviar(db_getsession('DB_login'), $sistema, $mensagem);

        return true;
    }
}