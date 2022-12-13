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
namespace ECidade\Configuracao\Formulario\Exportacao;
use ECidade\Configuracao\Formulario\Arquivo\DumperInterface;

/**
 * Exporta um
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 */
class Export
{

  /**
   * @var ECidade\Configuracao\Formulario\Arquivo\DumperInterface
   */
  private $dumper;

  function __construct(DumperInterface $dumper)
  {
    $this->dumper = $dumper;
  }

  /**
   * Gera o arquivo da avaliação
   * @param  string $path caminho do arquivo, se nao for informado gera no tmp
   * @return string caminho do arquivo gerado
   */
  public function export($path = null)
  {
    if( empty($path) ) {
      $path = "tmp". DS . 'avaliacao_'. time() . '.yml';
    }

    $data = $this->dumper->dump();
    if (!file_put_contents($path, $data)) {
      throw new Exception("Não foi possível gerar arquivo.");
    }

    return $path;
  }

}
