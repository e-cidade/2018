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

namespace ECidade\RecursosHumanos\ESocial\Migracao;

interface MigracaoInterface
{
    /**
     * Informa o formulario usado na versуo atual
     *
     * @param \stdClass $formulario
     */
    public function formularioAtual(\stdClass $formulario);

    /**
     * Informa o formulario que serс usado na versуo nova versуo
     *
     * @param \stdClass $formulario
     */
    public function formularioNovo(\stdClass $formulario);

    /**
     * Realiza a migraчуo de todos preenchimentos de um fomulсrio para outro
     *
     * @param \stdClass $formulario
     * @throws \Exception
     */
    public function processar();

    /**
     * Deve retonar o id do ultimo preenchimento e o id registro vinculado
     *
     * @param integer $formulario
     * @return \stdClass[]
     */
    public function buscarUltimoPreenchimento($codigoFormulario);
}
