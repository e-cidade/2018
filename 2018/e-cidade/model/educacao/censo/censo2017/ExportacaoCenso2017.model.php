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
 * Classe respons�vel por gerar o censo 2017
 *
 * REGISTROS
 *  00 -> CADASTRO DE ESCOLA - IDENTIFICA��O
 *  10 -> CADASTRO DE ESCOLA - CARACTERIZA��O E INFRAESTRUTURA
 *  20 -> CADASTRO DE TURMA
 *  30 -> CADASTRO DE PROFISSIONAL ESCOLAR EM SALA DE AULA - IDENTIFICA��O
 *  40 -> CADASTRO DE PROFISSIONAL ESCOLAR EM SALA DE AULA - DOCUMENTOS E ENDERE�O
 *  50 -> CADASTRO DE PROFISSIONAL ESCOLAR EM SALA DE AULA - DADOS VARI�VEIS
 *  51 -> CADASTRO DE PROFISSIONAL ESCOLAR EM SALA DE AULA - DADOS DE DOC�NCIA
 *  60 -> CADASTRO DE ALUNO - IDENTIFICA��O
 *  70 -> CADASTRO DE ALUNO - DOCUMENTOS E ENDERE�O
 *  80 -> CADASTRO DE ALUNO - V�NCULO (MATR�CULA)
 *  99 -> FIM DE ARQUIVO
 *
 * @author     Andrio Costa <andrio.costa@dbseller.com.br>
 * @package    educacao
 * @subpackage censo
 * @subpackage censo2017
 *
 * @version   $Revision: 1.1 $
 */
class ExportacaoCenso2017 extends ExportacaoCenso2016 implements IExportacaoCenso {

  public function __construct($iCodigoEscola, $iAnoCenso) {

    $this->iCodigoEscola = $iCodigoEscola;
    $this->iAnoCenso     = $iAnoCenso;
    $this->iCodigoLayout = 281;
  }

}