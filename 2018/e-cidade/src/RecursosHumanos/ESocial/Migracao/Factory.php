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

use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;

/**
 * Retorna a instância da classe de migração acordo com o tipo de formulário informado
 * @package ECidade\RecursosHumanos\ESocial\Migracao
 * @author  Andrio Costa - andrio.costa@dbseller.com.br
 */
class Factory
{
    public static function get($tipo)
    { 
  
        switch ($tipo) {
            case Tipo::EMPREGADOR:
                return new Empregador();
                break;
            case Tipo::RUBRICA:
                return new Rubrica();
                break;
            case Tipo::SERVIDOR:
                return new Servidor();
                break;
            case Tipo::LOTACAO_TRIBUTARIA:
                break;                
            case Tipo::CARGO:
                break;
            case Tipo::FUNCAO:
                break;
            case Tipo::HORARIO:
                break;
            default:
                throw new \Exception('Tipo de fomulário não encontrado.');
        }
    }
}
