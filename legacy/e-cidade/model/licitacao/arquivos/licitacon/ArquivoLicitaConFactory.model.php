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

abstract class ArquivoLicitaConFactory {

  public static function getArquivo($sNomeArquivo, CabecalhoLicitaCon $oCabecalho) {

    switch ($sNomeArquivo) {

      case MembroConsLicitaCon::NOME_ARQUIVO:
        return new MembroConsLicitaCon($oCabecalho);
      break;

      case PessoasLicitaCon::NOME_ARQUIVO:
        return new PessoasLicitaCon($oCabecalho);
      break;

      case ComissaoLicitaCon::NOME_ARQUIVO:
        return new ComissaoLicitaCon($oCabecalho);
      break;

      case MemComissaoLicitaCon::NOME_ARQUIVO:
        return new MemComissaoLicitaCon($oCabecalho);
      break;

      case LicitacaoLicitaCon::NOME_ARQUIVO:
        return new LicitacaoLicitaCon($oCabecalho);
      break;

      case LicitanteLicitaCon::NOME_ARQUIVO:
        return new LicitanteLicitaCon($oCabecalho);
      break;

      case DotacaoLicLicitaCon::NOME_ARQUIVO:
        return new DotacaoLicLicitaCon($oCabecalho);
      break;

      case EventoLicLicitaCon::NOME_ARQUIVO:
        return new EventoLicLicitaCon($oCabecalho);
      break;

      case DocumentoLicLicitaCon::NOME_ARQUIVO:
        return new DocumentoLicLicitaCon($oCabecalho);
      break;

      case LoteLicitaCon::NOME_ARQUIVO:
        return new LoteLicitaCon($oCabecalho);
      break;

      case ItemLicitaCon::NOME_ARQUIVO:
        return new ItemLicitaCon($oCabecalho);
      break;

      case PropostaLicitaCon::NOME_ARQUIVO:
        return new PropostaLicitaCon($oCabecalho);
      break;

      case LotePropLicitaCon::NOME_ARQUIVO:
        return new LotePropLicitaCon($oCabecalho);
      break;

      case ItemPropLicitaCon::NOME_ARQUIVO:
        return new ItemPropLicitaCon($oCabecalho);
      break;

      case ContratoLicitaCon::NOME_ARQUIVO:
        return new ContratoLicitaCon($oCabecalho);
      break;

      case DotacaoConLicitaCon::NOME_ARQUIVO:
        return new DotacaoConLicitaCon($oCabecalho);
      break;

      case EventoConLicitaCon::NOME_ARQUIVO:
        return new EventoConLicitaCon($oCabecalho);
      break;

      case DocumentoConLicitaCon::NOME_ARQUIVO:
        return new DocumentoConLicitaCon($oCabecalho);
      break;

      case ResponsavelConLicitaCon::NOME_ARQUIVO:
        return new ResponsavelConLicitaCon($oCabecalho);
      break;

      case LoteConLicitaCon::NOME_ARQUIVO:
        return new LoteConLicitaCon($oCabecalho);
      break;

      case ItemConLicitaCon::NOME_ARQUIVO:
        return new ItemConLicitaCon($oCabecalho);
      break;

      case AlteracaoLicitaCon::NOME_ARQUIVO:
        return new AlteracaoLicitaCon($oCabecalho);
      break;

      default:
        throw new Exception("Arquivo {$sNomeArquivo} não encontrado.");
        break;
    }
  }
}