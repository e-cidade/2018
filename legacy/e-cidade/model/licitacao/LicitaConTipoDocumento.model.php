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
 * Class LicitaConTipoDocumento
 */
class LicitaConTipoDocumento {

  const TIPO_DOCUMENTO_DOCUMENTOS_DE_HABILITACAO    = 'DOH';
  const TIPO_DOCUMENTO_PLANILHA_DE_PROPOSTAS        = 'PPR';
  const TIPO_DOCUMENTO_PROPOSTAS_ORCAMENTO_PRECO    = 'PRO';
  const TIPO_DOCUMENTO_PROPOSTAS_PROJETOS           = 'PRP';
  const TIPO_DOCUMENTO_MANIFESTACAO_DE_INTERESSE    = 'MAI';
  const TIPO_DOCUMENTO_MODELOS_DE_NEGOCIO           = 'MON';
  const TIPO_DOCUMENTO_PLANO_DE_TRABALHO            = 'PLT';
  const TIPO_DOCUMENTO_PROJETO_FUNCIONAL_PRELIMINAR = 'PFP';
  const TIPO_DOCUMENTO_PROJETOS_E_ESTUDOS_TECNICOS  = 'PET';

  /**
   * Descrição dos tipos de doscumentos disponíveis para o LicitaCon
   * @type array
   */
  public static $aDescricaoTipoDocumento = array(
    1  => "Adjudicação",
    2  => "Anulação de ofício",
    3  => "Anulação por determinação judicial",
    4  => "Ata de julgamento credenciamento/lances",
    5  => "Ata de julgamento da impugnação",
    6  => "Ata de julgamento de recursos",
    7  => "Ata de registro de preços",
    8  => "Ata julgamento de recursos credenciamento/lances",
    9  => "Atas do pregão (propostas/lances/habilitação)",
    10 => "Ata(s) do procedimento de pré-qualificação",
    11 => "Atas do RDC (propostas/lances/habilitação)",
    12 => "Atas (habilitação/propostas)",
    13 => "Atas (habilitação/propostas/projetos)",
    14 => "Autorização do órgão gerenciador",
    15 => "Aviso de alteração do Edital/Errata",
    16 => "Aviso de reinício",
    17 => "Aviso de republicação de edital",
    18 => "Aviso de suspensão de licitação",
    19 => "Comprovação de exclusividade",
    20 => "Comprovação de notória especialização",
    21 => "Comprovante de publicação do extrato da dispensa",
    22 => "Comprovante de publicação do extrato da inexigibilidade",
    23 => "Convocação-Aviso de edital",
    24 => "Cronograma",
    25 => "Cronograma da proposta vencedora",
    26 => "Detalhamento BDI",
    27 => "Detalhamento do BDI da proposta vencedora",
    28 => "Detalhamento dos encargos sociais da proposta vencedora",
    29 => "Detalhamento encargos sociais",
    30 => "Determinação judicial",
    31 => "Documentos de habilitação",
    32 => "Edital de pré-qualificação",
    33 => "Edital e anexos",
    34 => "Edital e anexos da licitação realizada por outro órgão",
    35 => "Esclarecimento",
    36 => "Extrato de adesão a registro de preços",
    37 => "Homologação",
    38 => "Impugnação contra edital",
    39 => "Manifestação de interesse",
    40 => "Medida cautelar",
    41 => "Modelos de negócio",
    42 => "Orçamento prévio com a composição dos custos unitários",
    43 => "Orçamento-base",
    44 => "Outras propostas (orçamento e preço)",
    45 => "Outros documentos",
    46 => "Pesquisa de mercado demonstrando a vantagem econômica",
    47 => "Planilha de detalhamento do objeto",
    48 => "Planilha de proposta",
    49 => "Plano de trabalho",
    50 => "Projeto básico",
    51 => "Projeto básico/Termo de referência",
    52 => "Projeto funcional preliminar",
    53 => "Projetos e estudos técnicos",
    54 => "Proposta do fornecedor contratado (orçamento e preço)",
    55 => "Propostas (orçamento e preço)",
    56 => "Propostas/Projetos",
    57 => "Recursos contra julgamento credenciamento/lances",
    58 => "Recursos contra julgamento da habilitação",
    59 => "Recursos contra julgamento da habilitação/propostas",
    60 => "Recursos contra julgamento das Propostas",
    61 => "Revogação de ofício",
    62 => "Termo de formalização da dispensa de licitação",
    63 => "Termo de formalização da inexigibilidade de licitação",
  );

  /**
   * Siglas dos eventos esperados pelo LicitaCon
   * @type array
   */
  public static $aSiglaTipoDocumento = array(
    1  => "ADJ",
    2  => "ANO",
    3  => "AND",
    4  => "ACL",
    5  => "IME",
    6  => "AJR",
    7  => "ARP",
    8  => "AJL",
    9  => "APR",
    10 => "APQ",
    11 => "ARD",
    12 => "AHP",
    13 => "HPP",
    14 => "AOG",
    15 => "AED",
    16 => "REI",
    17 => "REE",
    18 => "SUO",
    19 => "CEX",
    20 => "CNE",
    21 => "EXD",
    22 => "EXI",
    23 => "PUE",
    24 => "CRN",
    25 => "CRO",
    26 => "DBD",
    27 => "BDI",
    28 => "DEN",
    29 => "DES",
    30 => "SDJ",
    31 => "DOH",
    32 => "EPQ",
    33 => "EDI",
    34 => "ELO",
    35 => "ESC",
    36 => "EXA",
    37 => "HOM",
    38 => "ICE",
    39 => "MAI",
    40 => "SUM",
    41 => "MON",
    42 => "OPC",
    43 => "OCB",
    44 => "OUP",
    45 => "OUT",
    46 => "PMV",
    47 => "PDO",
    48 => "PPR",
    49 => "PLT",
    50 => "PJB",
    51 => "PBT",
    52 => "PFP",
    53 => "PET",
    54 => "PFC",
    55 => "PRO",
    56 => "PRP",
    57 => "RCL",
    58 => "RHA",
    59 => "RHP",
    60 => "RPR",
    61 => "REO",
    62 => "TFD",
    63 => "TFI"
  );
}


