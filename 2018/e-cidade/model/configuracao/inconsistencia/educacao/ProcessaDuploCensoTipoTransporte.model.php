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

require_once 'model/configuracao/inconsistencia/iExcecaoProcessamentoDependencias.interface.php';

/**
 * Trata as inconsistencias do aluno quando utiliza transporte público
 *
 * @package configuracao
 * @subpackage inconsistencia
 * @subpackage educacao
 * @author Andrio Costa <andrio.costa@gmail.com>
 * @version $Revision: 1.1 $
 */
class ProcessaDuploCensoTipoTransporte implements IExcecaoProcessamentoDependencias {

  private $sMsgErro;


  /**
   * A tabela alunocensotipotransporte não pode ter 2 registros do mesmo aluno, portanto
   * devemos remover os registro do aluno iformado como incorreto
   *
   * @param integer $iChaveCorreta código do aluno corréto
   * @param integer $iChaveIncorreta código do aluno que deve ser removido
   * @see IExcecaoProcessamentoDependencias::processar()
   */
  public function processar($iChaveCorreta, $iChaveIncorreta) {

    $oDao = new cl_alunocensotipotransporte();
    $oDao->excluir(null, " ed311_aluno = {$iChaveIncorreta} ");

    if ( $oDao->erro_status == 0 ) {

      $this->sMsgErro = str_replace("\\n", "\n", $oDao->erro_sql);
      return false;
    }
    return true;
  }

  /**
   * @see IExcecaoProcessamentoDependencias::getMensagemErro()
   */
  public function getMensagemErro() {
    return $this->sMsgErro;
  }

}
