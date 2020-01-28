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
   * Descri��o dos tipos de doscumentos dispon�veis para o LicitaCon
   * @type array
   */
  public static $aDescricaoTipoDocumento = array(
    1  => "Adjudica��o",
    2  => "Anula��o de of�cio",
    3  => "Anula��o por determina��o judicial",
    4  => "Ata de julgamento credenciamento/lances",
    5  => "Ata de julgamento da impugna��o",
    6  => "Ata de julgamento de recursos",
    7  => "Ata de registro de pre�os",
    8  => "Ata julgamento de recursos credenciamento/lances",
    9  => "Atas do preg�o (propostas/lances/habilita��o)",
    10 => "Ata(s) do procedimento de pr�-qualifica��o",
    11 => "Atas do RDC (propostas/lances/habilita��o)",
    12 => "Atas (habilita��o/propostas)",
    13 => "Atas (habilita��o/propostas/projetos)",
    14 => "Autoriza��o do �rg�o gerenciador",
    15 => "Aviso de altera��o do Edital/Errata",
    16 => "Aviso de rein�cio",
    17 => "Aviso de republica��o de edital",
    18 => "Aviso de suspens�o de licita��o",
    19 => "Comprova��o de exclusividade",
    20 => "Comprova��o de not�ria especializa��o",
    21 => "Comprovante de publica��o do extrato da dispensa",
    22 => "Comprovante de publica��o do extrato da inexigibilidade",
    23 => "Convoca��o-Aviso de edital",
    24 => "Cronograma",
    25 => "Cronograma da proposta vencedora",
    26 => "Detalhamento BDI",
    27 => "Detalhamento do BDI da proposta vencedora",
    28 => "Detalhamento dos encargos sociais da proposta vencedora",
    29 => "Detalhamento encargos sociais",
    30 => "Determina��o judicial",
    31 => "Documentos de habilita��o",
    32 => "Edital de pr�-qualifica��o",
    33 => "Edital e anexos",
    34 => "Edital e anexos da licita��o realizada por outro �rg�o",
    35 => "Esclarecimento",
    36 => "Extrato de ades�o a registro de pre�os",
    37 => "Homologa��o",
    38 => "Impugna��o contra edital",
    39 => "Manifesta��o de interesse",
    40 => "Medida cautelar",
    41 => "Modelos de neg�cio",
    42 => "Or�amento pr�vio com a composi��o dos custos unit�rios",
    43 => "Or�amento-base",
    44 => "Outras propostas (or�amento e pre�o)",
    45 => "Outros documentos",
    46 => "Pesquisa de mercado demonstrando a vantagem econ�mica",
    47 => "Planilha de detalhamento do objeto",
    48 => "Planilha de proposta",
    49 => "Plano de trabalho",
    50 => "Projeto b�sico",
    51 => "Projeto b�sico/Termo de refer�ncia",
    52 => "Projeto funcional preliminar",
    53 => "Projetos e estudos t�cnicos",
    54 => "Proposta do fornecedor contratado (or�amento e pre�o)",
    55 => "Propostas (or�amento e pre�o)",
    56 => "Propostas/Projetos",
    57 => "Recursos contra julgamento credenciamento/lances",
    58 => "Recursos contra julgamento da habilita��o",
    59 => "Recursos contra julgamento da habilita��o/propostas",
    60 => "Recursos contra julgamento das Propostas",
    61 => "Revoga��o de of�cio",
    62 => "Termo de formaliza��o da dispensa de licita��o",
    63 => "Termo de formaliza��o da inexigibilidade de licita��o",
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


