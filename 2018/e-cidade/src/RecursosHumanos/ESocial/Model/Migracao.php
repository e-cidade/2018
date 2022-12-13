<?php
namespace ECidade\RecursosHumanos\ESocial\Model;

use ECidade\Configuracao\Formulario\Model\Formulario;

use ECidade\RecursosHumanos\ESocial\Migracao\Servidor as ServidorMigracao;
use ECidade\RecursosHumanos\ESocial\Migracao\Factory;
use BusinessException;
use DBException;
use stdClass;

class Migracao
{
    private $aFormulariosNovos = array();
    private $aFormulariosAtuais = array();

    /**
     * C�digo do usu�rio
     *
     * @var integer
     */
    private $usuario;

    /**
     * Informa o c�digo do usu�rio
     *
     * @param integer $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    private function mapearFormularios($sNovaVersao)
    {
        $oConfiguracao = new Configuracao;
        $aFormulariosNovos = $oConfiguracao->getFormulariosPorVersao($sNovaVersao);
        $aFormulariosAtuais = $oConfiguracao->getFormulariosPorVersao($oConfiguracao->getVersao());
        $aFormularios = array_merge($aFormulariosAtuais, $aFormulariosNovos);

        foreach ($aFormularios as $oFormulario) {
            if ($oFormulario->versao === $sNovaVersao) {
                $this->aFormulariosNovos[$oFormulario->tipo] = $oFormulario;
            } else {
                $this->aFormulariosAtuais[$oFormulario->tipo] = $oFormulario;
            }
        }

        foreach ($this->aFormulariosNovos as $oFormularioNovo) {
            if (!isset($this->aFormulariosAtuais[$oFormularioNovo->tipo])) {
                continue;
            }
            $aFormularioAtual = $this->aFormulariosAtuais[$oFormularioNovo->tipo];

            /*
             * Se n�o houve altera��o no c�digo do formul�rio ignoramos ele.
             */
            if ($aFormularioAtual->formulario === $oFormularioNovo->formulario) {
                unset($this->aFormulariosAtuais[$oFormularioNovo->tipo]);
            }

            /*
             * Se o formul�rio n�o existe na vers�o atual ignoramos ele.
             */
            if (!isset($this->aFormulariosAtuais[$oFormularioNovo->tipo])) {
                unset($this->aFormulariosNovos[$oFormularioNovo->tipo]);
            }
        }

        if (empty($this->aFormulariosNovos) || empty($this->aFormulariosAtuais)) {
            throw new BusinessException('N�o foi poss�vel mapear os formul�rios atuais com os novos.');
        }
    }

    /**
     * Insere a nova vers�o dos layouts em uso
     *
     * @param string $sNovaVersao
     * @throws \Exception
     */
    private function mudarVersao($sNovaVersao)
    {
        $dao = new \cl_esocialversao();
        $dao->rh210_versao = $sNovaVersao;
        $dao->incluir(null);

        if ($dao->erro_status == 0) {
            throw new \Exception("Erro ao definir nova vers�o.");
        }
    }

    /**
     * Realiza a migra��o dos formul�rios conforme o tipo
     *
     * @param string $sNovaVersao
     */
    public function migrar($sNovaVersao, $progressBar)
    {
        $this->mapearFormularios($sNovaVersao);

        foreach ($this->aFormulariosNovos as $iTipo => $oFormulario) {
            $migrar = Factory::get($iTipo);

            if (!empty($migrar)) {                
                $migrar->formularioAtual($this->aFormulariosAtuais[$oFormulario->tipo]);
                $migrar->formularioNovo($oFormulario);
                $migrar->setUsuario($this->usuario);
                $migrar->setProgressBar($progressBar);
                $migrar->processar();
            }
        }

        $this->mudarVersao($sNovaVersao);

        $progressBar->setMessageLog('Processamento finalizado com sucesso.');
    }
}
