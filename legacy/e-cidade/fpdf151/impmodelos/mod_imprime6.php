<?php
global $db61_texto, $db02_texto;

for ($xxx = 0; $xxx < $this->nvias; $xxx ++) {
  $this->objpdf->AliasNbPages();
  $this->objpdf->AddPage();
  $this->objpdf->settopmargin(1);
  $pagina = 1;
  $xlin = 20;
  $xcol = 4;

  //Inserindo usuario e data no rodape
  $this->objpdf->Setfont('Arial', 'I', 6);
  $this->objpdf->text($xcol +3, $xlin +276, "Emissor: ".db_getsession("DB_login")."  Data: ".date("d/m/Y", db_getsession("DB_datausu"))."");

  $this->objpdf->setfillcolor(245);
  $this->objpdf->rect($xcol -2, $xlin -18, 206, 292, 2, 'DF', '1234');
  $this->objpdf->setfillcolor(255, 255, 255);
  $this->objpdf->Setfont('Arial', 'B', 10);
  $this->objpdf->text(128, $xlin -13, 'NOTA DE EMPENHO N'.CHR(176).': ');
  $this->objpdf->text(175, $xlin -13, db_formatar($this->codemp, 's', '0', 6, 'e'));
  $this->objpdf->text(134, $xlin -8, 'DATA DE EMISSÃO : ');
  $this->objpdf->text(175, $xlin -8, $this->emissao);

  if (strtoupper(trim($this->municpref)) != 'GUAIBA') {
    $this->objpdf->text(159, $xlin -3, 'TIPO : ');
    $this->objpdf->text(175, $xlin -3, $this->emptipo);
  }
  if (isset($this->iPlanoPacto) && $this->iPlanoPacto != "") {

    $this->objpdf->Setfont('Arial', 'B', 8);
    $this->objpdf->text(128,$xlin,'PLANO');
    $this->objpdf->text(140,$xlin,': '.substr($this->iPlanoPacto."-".$this->SdescrPacto,0,40));
  }
  $this->objpdf->Image('imagens/files/'.$this->logo, 15, $xlin -17, 12);
  $this->objpdf->Setfont('Arial', 'B', 9);
  $this->objpdf->text(40, $xlin -15, $this->prefeitura);
  $this->objpdf->Setfont('Arial', '', 7);
  $this->objpdf->text(40, $xlin -11, $this->enderpref);
  $this->objpdf->text(40, $xlin -8, $this->municpref);
  $this->objpdf->text(40, $xlin -5, $this->telefpref);
  $this->objpdf->text(40, $xlin -2, $this->emailpref);
  $this->objpdf->text(40, $xlin +1, db_formatar($this->cgcpref, 'cnpj') . $this->inscricaoestadualinstituicao);

  // retangulo dos dados da dotação
  $this->objpdf->rect($xcol, $xlin +2, $xcol +100, 50, 2, 'DF', '1234');
  $this->objpdf->Setfont('Arial', 'B', 8);
  $this->objpdf->text($xcol +2, $xlin +5, 'Órgão');
  $this->objpdf->text($xcol +2, $xlin +8.5, 'Unidade');
  $this->objpdf->text($xcol +2, $xlin +12, 'Função');
  $this->objpdf->text($xcol +2, $xlin +15.5, 'Subfunção');
  $this->objpdf->text($xcol +2, $xlin +19, 'Programa');
  $this->objpdf->text($xcol +2, $xlin +22.5, 'Proj/Ativ');
  $this->objpdf->text($xcol +2, $xlin +26, 'Rubrica');
  $this->objpdf->text($xcol +2, $xlin +33, 'Recurso');

  if ($this->banco != "") {
    $this->objpdf->text($xcol +2, $xlin +36, 'Banco: ');
    $this->objpdf->text($xcol +30, $xlin +36, 'Agencia:');
    $this->objpdf->text($xcol +60, $xlin +36, 'Conta:');
  }

  $this->objpdf->text($xcol +2, $xlin +39.5, 'Reduzido');
  if (isset($this->contrapartida)  && $this->contrapartida != "") {
    $this->objpdf->text($xcol +30, $xlin +40.5, 'CP');
  }
  $this->objpdf->text($xcol +2, $xlin +43, 'Licitação');
  $this->objpdf->text($xcol +2, $xlin +47, 'Modalidade de Licitação');
  $this->objpdf->text($xcol +2, $xlin +51, "Característica Peculiar");

  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->text($xcol +17, $xlin +5, ':  '.db_formatar($this->orgao, 'orgao').' - '.substr($this->descr_orgao, 0, 46));
  $this->objpdf->text($xcol +17, $xlin +8.5, ':  '.db_formatar($this->unidade, 'unidade').' - '.$this->descr_unidade);
  $this->objpdf->text($xcol +17, $xlin +12, ':  '.db_formatar($this->funcao, 'funcao').' - '.$this->descr_funcao);
  $this->objpdf->text($xcol +17, $xlin +15.5, ':  '.db_formatar($this->subfuncao, 'subfuncao').' - '.$this->descr_subfuncao);
  $this->objpdf->text($xcol +17, $xlin +19, ':  '.db_formatar($this->programa, 'programa').' - '.$this->descr_programa);

  $this->objpdf->text($xcol +17, $xlin +22.5, ':  '.db_formatar($this->projativ, 'projativ').' - '.$this->descr_projativ);

  $this->objpdf->text($xcol +17, $xlin +26, ':  '.db_formatar($this->sintetico, 'elemento_int'));
  $this->objpdf->setxy($xcol +18, $xlin +27);
  $this->objpdf->multicell(90, 3, $this->descr_sintetico, 0, "L");

  $sDescricaoRecurso  = $this->descr_recurso;
  $sDescricaoRecurso2 = null;
  $iTamanhoLinha      = 44;
  /**
   * Se o tamanho da descrição é maior que $iTamanhoLinha, quebra em duas linhas (sem cortar as palavras ao meio)
   */
  if (strlen($this->descr_recurso) > $iTamanhoLinha) {

    $aDescricaoRecurso  = explode("\n", wordwrap($sDescricaoRecurso, $iTamanhoLinha));
    $sDescricaoRecurso  = current($aDescricaoRecurso);
    $sDescricaoRecurso2 = next($aDescricaoRecurso);
  }

  $this->objpdf->text($xcol +17, $xlin +33, ':  ' . $this->recurso . ' - ' . $sDescricaoRecurso);
  if ($sDescricaoRecurso2) {
    $this->objpdf->text($xcol +19, $xlin +36, $sDescricaoRecurso2);
  }

  if ($this->banco != "") {
    $this->objpdf->text($xcol +17, $xlin +36, $this->banco);
    $this->objpdf->text($xcol +47, $xlin +36, $this->agencia);
    $this->objpdf->text($xcol +77, $xlin +36, $this->conta);
  }

  $this->objpdf->text($xcol +17, $xlin +39.5, ':  '.$this->coddot);
  if (isset($this->contrapartida) && $this->contrapartida != "") {
    $this->objpdf->text($xcol +35, $xlin +40.5, ':'.$this->contrapartida);
  }

  $this->objpdf->text($xcol +17, $xlin +43, ':  '. ($this->edital_licitacao != null ? $this->edital_licitacao.'/' : '').$this->ano_licitacao);
  $this->objpdf->text($xcol +35, $xlin +47, ':  '. ($this->num_licitacao != null ? $this->num_licitacao.' - ' : '').$this->descr_licitacao);
  $this->objpdf->text($xcol +35, $xlin +51, ":  ". $this->cod_concarpeculiar." - ".$this->descr_concarpeculiar);

  // retangulo dos dados do credor
  $this->objpdf->rect($xcol +106, $xlin +2, 96, 21, 2, 'DF', '1234');
  $this->objpdf->Setfont('Arial', '', 6);
  $this->objpdf->text($xcol +108, $xlin +4, 'Dados do Credor:');
  $this->objpdf->Setfont('Arial', 'B', 8);
  $this->objpdf->text($xcol +107, $xlin +7, 'Nº Credor');
  $this->objpdf->text($xcol +150, $xlin +7, (strlen($this->cnpj) == 11 ? 'CPF' : 'CNPJ'));
  $this->objpdf->text($xcol +107, $xlin +10, 'Nome');
  $this->objpdf->text($xcol +107, $xlin +13, 'Endereço');
  $this->objpdf->text($xcol +107, $xlin +16, 'Município');

  if ($this->dadosbancoemprenho == 't') {
    $this->objpdf->text($xcol +107, $xlin +22, 'Banco/Ag./Conta');
  }
  $this->objpdf->text($xcol+107,$xlin+19,'Telefone');
  $this->objpdf->text($xcol+150,$xlin+19,'Fax');

  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->text($xcol +124, $xlin +7,  ': '.$this->numcgm);
  $this->objpdf->text($xcol +158, $xlin +7,  ': '. (strlen($this->cnpj) == 11 ? db_formatar($this->cnpj, 'cpf') : db_formatar($this->cnpj, 'cnpj')));
  $this->objpdf->text($xcol +124, $xlin +10,  ': '.$this->nome);
  $this->objpdf->text($xcol +124, $xlin +13, ': '.$this->ender.'  '.$this->compl);
  $this->objpdf->text($xcol +124, $xlin +16, ': '.$this->munic.'-'.$this->ufFornecedor.'    CEP : '.$this->cep);

  if ($this->dadosbancoemprenho == 't') {
    $this->objpdf->text($xcol +131, $xlin +22, ': '.$this->iBancoFornecedor.' / '.$this->iAgenciaForncedor.' / '.$this->iContaForncedor);
  }
  $this->objpdf->text($xcol+124,$xlin+19,': ' . $this->telefone);
  $this->objpdf->text($xcol+157,$xlin+19,': ' . $this->fax);

  // retangulo dos valores
  $this->objpdf->rect($xcol +106, $xlin +24, 96, 9, 2, 'DF', '1234');
  $this->objpdf->rect($xcol +106, $xlin +34.0, 47, 8, 2, 'DF', '1234');
  $this->objpdf->rect($xcol +155, $xlin +34.0, 47, 8, 2, 'DF', '1234');
  $this->objpdf->rect($xcol +106, $xlin +43.5, 47, 8, 2, 'DF', '1234');
  $this->objpdf->rect($xcol +155, $xlin +43.5, 47, 8, 2, 'DF', '1234');
  $this->objpdf->Setfont('Arial', '', 6);
  $this->objpdf->text($xcol +108, $xlin +36.5, 'Valor Orçado');
  $this->objpdf->text($xcol +157, $xlin +36.5, 'Saldo Anterior');
  $this->objpdf->text($xcol +108, $xlin +47, 'Valor Empenhado');
  $this->objpdf->text($xcol +157, $xlin +47, 'Saldo Atual');

  $this->objpdf->Setfont('Arial', '', 7);
  $this->objpdf->text($xcol +108, $xlin +27,'PROCESSO DE COMPRA N'.CHR(176)." ".db_formatar(pg_result($this->recorddositens,0,$this->Snumeroproc),'s','0',6,'e'));
  $this->objpdf->text($xcol +108, $xlin +32,'AUTORIZAÇÃO N'.chr(176).' '.db_formatar($this->numaut, 's', '0', 5, 'e'));
  $this->objpdf->text($xcol +156, $xlin +27,'SEQ. DO EMPENHO N'.chr(176).' '.db_formatar($this->numemp, 's', '0', 6, 'e'));
  $this->objpdf->text($xcol +156, $xlin +32,'PROC. ADMIN (P.A.) : ' . $this->processo_administrativo);
  $this->objpdf->Setfont('Arial', '', 8);

  $this->objpdf->text($xcol +130, $xlin +38.0, db_formatar($this->orcado, 'f'));
  $this->objpdf->text($xcol +180, $xlin +38.0, db_formatar($this->saldo_ant, 'f'));
  $this->objpdf->text($xcol +130, $xlin +47.5, db_formatar($this->empenhado, 'f'));
  $this->objpdf->text($xcol +180, $xlin +47.5, db_formatar($this->saldo_ant - $this->empenhado, 'f'));

  // retangulo do corpo do empenho
  $this->objpdf->rect($xcol, $xlin +60, 15, 100, 2, 'DF', '');
  $this->objpdf->rect($xcol +15, $xlin +60, 137, 100, 2, 'DF', '');
  $this->objpdf->rect($xcol +152, $xlin +60, 25, 123, 2, 'DF', '');
  $this->objpdf->rect($xcol +177, $xlin +60, 25, 123, 2, 'DF', '');
  $this->objpdf->rect($xcol, $xlin +160, 152, 23, 2, 'DF', '');

  // retangulos do titulo do corpo do empenho
  $this->objpdf->Setfont('Arial', 'B', 7);
  $this->objpdf->rect($xcol, $xlin +54, 15, 6, 2, 'DF', '12');
  $this->objpdf->rect($xcol +15, $xlin +54, 137, 6, 2, 'DF', '12');
  $this->objpdf->rect($xcol +152, $xlin +54, 25, 6, 2, 'DF', '12');
  $this->objpdf->rect($xcol +177, $xlin +54, 25, 6, 2, 'DF', '12');

  // título do corpo do empenho
  $this->objpdf->text($xcol +2, $xlin +58, 'QUANT');
  $this->objpdf->text($xcol +70, $xlin +58, 'MATERIAL OU SERVIÇO');
  $this->objpdf->text($xcol +154, $xlin +58, 'VALOR UNITÁRIO');
  $this->objpdf->text($xcol +181, $xlin +58, 'VALOR TOTAL');
  $maiscol = 0;

  // monta os dados para itens do empenho
  $this->objpdf->setleftmargin(4);
  $this->objpdf->sety($xlin +62);
  $this->objpdf->Setfont('Arial', '', 7);
  $ele = 0;
  $xtotal = 0;
  $retorna_obs = 0;

  for ($ii = 0; $ii < $this->linhasdositens; $ii ++) {

    $this->objpdf->SetWidths(array(15, 137, 25, 25));
    $this->objpdf->SetAligns(array('C', 'L', 'R', 'R'));
    db_fieldsmemory($this->recorddositens, $ii);
    $oStdDadosItens = db_utils::fieldsMemory($this->recorddositens, $ii);


    if ($retorna_obs == 0) {

      $this->objpdf->Setfont('Arial', 'B', 7);

      /**
       * Busca a Unidade de Medida a partir da solicitação de compras (se esse vínculo existir)
       */
      $sDescricaoUnidade = null;
      if (!empty($oStdDadosItens->pc11_codigo)) {

        $oDaoMatUnid      = new cl_solicitemunid();
        $sSqlBuscaUnidade = $oDaoMatUnid->sql_query($oStdDadosItens->pc11_codigo, 'm61_descr');
        $rsBuscaUnidade   = $oDaoMatUnid->sql_record($sSqlBuscaUnidade);
        if ($rsBuscaUnidade && $oDaoMatUnid->numrows > 0) {
          $sDescricaoUnidade = "(Unidade: " . db_utils::fieldsMemory($rsBuscaUnidade, 0)->m61_descr . ")";
        }
      }

      /**
       * Se não encontrou unidade através da solicitação de compras tenta buscar da autorização de empenho
       */
      if (empty($sDescricaoUnidade)) {

        $aWhereUnidade = array(
          "empautitem.e55_sequen = {$oStdDadosItens->e62_sequen}",
          "empempitem.e62_numemp = {$oStdDadosItens->e62_numemp}",
          "matunid.m61_codmatunid is not null",
        );
        $oDaoAutorizacao = new cl_empempaut();
        $sSqlBuscaItem   = $oDaoAutorizacao->sql_query_empenho_autorizacao(null, 'distinct m61_descr', null, implode(' and ', $aWhereUnidade));
        $rsBuscaUnidade  = db_query($sSqlBuscaItem);
        if ($rsBuscaUnidade && pg_num_rows($rsBuscaUnidade) > 0) {
          $sDescricaoUnidade = "(Unidade: " . db_utils::fieldsMemory($rsBuscaUnidade, 0)->m61_descr . ")";
        }
      }


      if ($ele != pg_result($this->recorddositens, $ii, $this->analitico)) {
        $this->objpdf->cell(15, 4, '', 0, 0, "C", 0);
        $this->objpdf->cell(137, 4, db_formatar(pg_result($this->recorddositens, $ii, $this->analitico), 'elemento_int').' - '.pg_result($this->recorddositens, $ii, $this->descr_analitico), 0, 1, "L", 0);
        $ele = pg_result($this->recorddositens, $ii, $this->analitico);
      }

      $xtotal       += pg_result($this->recorddositens, $ii, $this->valoritem);
      $quantitem     = pg_result($this->recorddositens, $ii, $this->quantitem);
      $descricaoitem = pg_result($this->recorddositens, $ii, $this->descricaoitem) . " - $sDescricaoUnidade";

      if (pg_result($this->recorddositens,$ii,$this->Snumero)!="") {
        $descricaoitem .= "\n".'SOLICITAÇÃO: '.pg_result($this->recorddositens,$ii,$this->Snumero);
      }

      $obsitem      = pg_result($this->recorddositens, $ii, $this->observacaoitem);

      $valoritemuni = db_formatar(pg_result($this->recorddositens, $ii, $this->valor), 'v', " ", $this->casadec);
      $valoritemtot = db_formatar(pg_result($this->recorddositens, $ii, $this->valoritem), 'f');

      /**
       * Não é uma licitação
       * - nao encontra descricao do item, pcorcamval.pc23_obs
       * - busca descricao do item pela solicitacao ou registro de preco
       */
      if (empty($obsitem) && !empty($this->numcgm)) {

        $iSolicitacao = pg_result($this->recorddositens, $ii, 'pc11_codigo');

        if (!empty($iSolicitacao)) {

          $oDaopcorcamval = new cl_pcorcamval();
          $sSqlDescricaoItem = $oDaopcorcamval->sql_query_observacaoItemOrcamento($iSolicitacao, $this->numcgm);
          $rsDescricaoItem = db_query($sSqlDescricaoItem);

          if ($rsDescricaoItem && pg_num_rows($rsDescricaoItem) > 0) {

            $sObservacaoRegistroPreco = db_utils::fieldsMemory($rsDescricaoItem, 0)->registro_preco;
            $sObservacaoSolicitacao = db_utils::fieldsMemory($rsDescricaoItem, 0)->solicitacao;

            $obsitem = $sObservacaoSolicitacao;
            if (empty($obsitem)) {
              $obsitem = $sObservacaoRegistroPreco;
            }
          }
        }

      }

      $descricaoitem .= "\n".$obsitem."\n";
    } else {

    	$descricaoitem = $descricaoitemimprime;
      //$retorna_obs = 0;
      $quantitem = "";
      $valoritemuni = "";
      $valoritemtot = "";
    }



    $set_altura_row = $this->objpdf->h - 125;
    if ($pagina != 1) {
      $set_altura_row = $this->objpdf->h - 30;
    }


	  /**
	   *
	   * Verifica os casos em que o resumo não tem quebra e é maior que o tamanho restante da página
	   *
	   * - É feito a correção inserindo uma quebra no ponto limite para a impressão do resumo
	   *
	   */
    if ($retorna_obs != 1) {

      // Largura total do multicell
      $iWidthMulticell  = 25;

      // Consulta o total de linhas restantes
      $iLinhasRestantes = ((( $this->objpdf->h - 25 ) - $this->objpdf->GetY()) / 3 );

      // Consulta o total de linhas que será utilizado no multicelll
      $iLinhasMulticell = $this->objpdf->NbLines($iWidthMulticell, $descricaoitem);
      // Verifica se o total de linhas utilizadas no multicell é maior que as linhas restantes
      if ( $iLinhasMulticell > $iLinhasRestantes ) {

        // Total de carateres necessários para a impressão até o fim da página
        $iTotalCaract = ( $iWidthMulticell * $iLinhasRestantes );
        $iLimitString = $iTotalCaract;

        $iTamanhoDaStringEmCaracteres = strlen($descricaoitem);

        // Percorre o resumo do limite de caraceters até um ponto que haja espaço em branco para não quebre alguma palavra
        for ($iInd = $iTotalCaract; $iInd < $iTamanhoDaStringEmCaracteres; $iInd++) {

          if ( $descricaoitem[(int)$iInd] == ' ' || $descricaoitem[(int)$iInd] == ". ") {

            $iLimitString = $iInd;
            break;
          }
        }

        $iLinhasMulticell = $this->objpdf->NbLines($iWidthMulticell, substr($descricaoitem,0,$iLimitString));
        // Insere quebra no ponto informado
        $descricaoitem = substr($descricaoitem,0,$iLimitString)."\n".substr($descricaoitem,$iLimitString,strlen($descricaoitem));
      }
    }

    $this->objpdf->Setfont('Arial', '', 7);
    
    $descricaoitem = str_replace(array("\\n", "\n"), "\n", $descricaoitem);

    $aCampos = array($quantitem, $descricaoitem, $valoritemuni, $valoritemtot);

    $descricaoitemimprime = $this->objpdf->Row_multicell($aCampos, 3, false, 5, 0, true, true, 1, $set_altura_row);

    $retorna_obs = 0;
		if (trim($descricaoitemimprime) != "") {

      $retorna_obs = 1;
      $ii--;
    }

    if (($this->objpdf->gety() > $this->objpdf->h - 110 && $pagina == 1) || ($this->objpdf->gety() > $this->objpdf->h - 30 && $pagina != 1) || !empty($descricaoitemimprime) ) {

      $proxima_pagina = $pagina +1;

      if ($proxima_pagina == 2) {

      $this->objpdf->sety($this->objpdf->h - 125);
      $this->objpdf->Row(array('', "Continua na página $proxima_pagina", '', ''), 3, false, 4);
      } else {

        $this->objpdf->sety($this->objpdf->h - 25);
        $this->objpdf->Row(array('', "Continua na página $proxima_pagina", '', ''), 3, false, 4);
      }

      if ($pagina == 1) {
        $this->objpdf->rect($xcol, $xlin +183, 152, 6, 2, 'DF', '34');
        $this->objpdf->rect($xcol +152, $xlin +183, 25, 6, 2, 'DF', '34');
        $this->objpdf->rect($xcol +177, $xlin +183, 25, 6, 2, 'DF', '34');

        $this->objpdf->SetFont('Arial', '', 7);
        $this->objpdf->text($xcol +2, $xlin +187, 'DESTINO : ', 0, 1, 'L', 0);
        $this->objpdf->text($xcol +30, $xlin +187, $this->destino, 0, 1, 'L', 0);

        $this->objpdf->setxy($xcol +1, $xlin +165);
        $this->objpdf->text($xcol +2, $xlin +164, 'RESUMO : ', 0, 1, 'L', 0);
        $this->objpdf->setxy($xcol +1, $xlin +161.5);
        $this->objpdf->multicell(147, 3.5, $this->resumo,0,'J', 0, 14);

        $this->objpdf->text($xcol +159, $xlin +187, 'T O T A L', 0, 1, 'L', 0);
        $this->objpdf->setxy($xcol +185, $xlin +180);
        $this->objpdf->cell(30, 10, db_formatar($this->empenhado, 'f'), 0, 0, 'f');

        $sqlparag  = "select db02_texto ";
        $sqlparag .= "  from db_documento ";
        $sqlparag .= "       inner join db_docparag on db03_docum = db04_docum ";
        $sqlparag .= "       inner join db_tipodoc on db08_codigo  = db03_tipodoc ";
        $sqlparag .= "       inner join db_paragrafo on db04_idparag = db02_idparag ";
        $sqlparag .= " where db03_tipodoc = 1501 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";

        $resparag = @db_query($sqlparag);

        if (@pg_numrows($resparag) > 0) {
          db_fieldsmemory($resparag,0);
          /**[extensao ordenadordespesa] doc_usuario*/
          @eval($db02_texto);
        } else {
          $sqlparagpadrao  = "select db61_texto ";
          $sqlparagpadrao .= "  from db_documentopadrao ";
          $sqlparagpadrao .= "       inner join db_docparagpadrao  on db62_coddoc   = db60_coddoc ";
          $sqlparagpadrao .= "       inner join db_tipodoc         on db08_codigo   = db60_tipodoc ";
          $sqlparagpadrao .= "       inner join db_paragrafopadrao on db61_codparag = db62_codparag ";
          $sqlparagpadrao .= " where db60_tipodoc = 1501 and db60_instit = " . db_getsession("DB_instit")." order by db62_ordem";

          $resparagpadrao = @db_query($sqlparagpadrao);
          if (@pg_numrows($resparagpadrao) > 0) {
            db_fieldsmemory($resparagpadrao,0);
            /**[extensao ordenadordespesa] doc_padrao*/

            @eval($db61_texto);
          }
        }

        $this->objpdf->SetFont('Arial', '', 4);
        $this->objpdf->Text(2, 296, $this->texto);
        $this->objpdf->setfont('Arial', '', 11);

        $xlin = 169;
      }

      $this->objpdf->addpage();
      $pagina += 1;

      $this->objpdf->settopmargin(1);
      $xlin = 20;
      $xcol = 4;

      //Inserindo usuario e data no rodape
      $this->objpdf->Setfont('Arial', 'I', 6);
      $this->objpdf->text($xcol +3, $xlin +276, "Emissor: ".db_getsession("DB_login")." Data: ".date("d/m/Y", db_getsession("DB_datausu"))."");

      $this->objpdf->setfillcolor(245);
      $this->objpdf->rect($xcol -2, $xlin -18, 206, 292, 2, 'DF', '1234');
      $this->objpdf->setfillcolor(255, 255, 255);
      $this->objpdf->Setfont('Arial', 'B', 10);

      $this->objpdf->text(128, $xlin -13,' NOTA DE EMPENHO N'.CHR(176).': ');
      $this->objpdf->text(175, $xlin -13, db_formatar($this->codemp, 's', '0', 6, 'e'));
      $this->objpdf->text(134, $xlin -8, 'DATA DE EMISSÃO : ');
      $this->objpdf->text(175, $xlin -8, $this->emissao);

      $this->objpdf->text(120, $xlin -3,'PROCESSO DE COMPRA N'.CHR(176).": ");
      $this->objpdf->text(175, $xlin -3,db_formatar(pg_result($this->recorddositens,0,$this->Snumeroproc),'s','0',6,'e'));

      $this->objpdf->Image('imagens/files/'.$this->logo, 15, $xlin -17, 12);
      $this->objpdf->Setfont('Arial', 'B', 9);
      $this->objpdf->text(40, $xlin -15, $this->prefeitura);
      $this->objpdf->Setfont('Arial', '', 9);
      $this->objpdf->text(40, $xlin -11, $this->enderpref);
      $this->objpdf->text(40, $xlin -8, $this->municpref);
      $this->objpdf->text(40, $xlin -5, $this->telefpref);
      $this->objpdf->text(40, $xlin -2, $this->emailpref);
      $xlin = -30;
      $this->objpdf->Setfont('Arial', 'B', 7);

      $this->objpdf->rect($xcol, $xlin +54, 15, 6, 2, 'DF', '12');
      $this->objpdf->rect($xcol +15, $xlin +54, 137, 6, 2, 'DF', '12');
      $this->objpdf->rect($xcol +152, $xlin +54, 25, 6, 2, 'DF', '12');
      $this->objpdf->rect($xcol +177, $xlin +54, 25, 6, 2, 'DF', '12');

      $this->objpdf->rect($xcol, $xlin +60, 15, 262, 2, 'DF', '34');
      $this->objpdf->rect($xcol +15, $xlin +60, 137, 262, 2, 'DF', '34');
      $this->objpdf->rect($xcol +152, $xlin +60, 25, 262, 2, 'DF', '34');
      $this->objpdf->rect($xcol +177, $xlin +60, 25, 262, 2, 'DF', '34');

      $this->objpdf->sety($xlin +66);
      $alt = 4;

      $this->objpdf->text($xcol +0.5, $xlin +58, 'QUANT');
      $this->objpdf->text($xcol +65, $xlin +58, 'MATERIAL OU SERVIÇO');
      $this->objpdf->text($xcol +155, $xlin +58, 'VALOR UNITÁRIO');
      $this->objpdf->text($xcol +179, $xlin +58, 'VALOR TOTAL');
      $this->objpdf->text($xcol +38, $xlin +63, 'Continuação da Página '. ($this->objpdf->PageNo() - 1));

      $maiscol = 0;
    }
  }

  if ($pagina == 1) {
    $this->objpdf->rect($xcol, $xlin +183, 152, 6, 2, 'DF', '34');
    $this->objpdf->rect($xcol +152, $xlin +183, 25, 6, 2, 'DF', '34');
    $this->objpdf->rect($xcol +177, $xlin +183, 25, 6, 2, 'DF', '34');

    $this->objpdf->SetFont('Arial', '', 7);
    $this->objpdf->text($xcol +2, $xlin +187, 'DESTINO : ', 0, 1, 'L', 0);
    $this->objpdf->text($xcol +30, $xlin +187, $this->destino, 0, 1, 'L', 0);

    $this->objpdf->setxy($xcol +1, $xlin +165);
    $this->objpdf->text($xcol +2, $xlin +164, 'RESUMO : ', 0, 1, 'L', 0);
    $this->objpdf->setxy($xcol +1, $xlin +161.5);
    // trata o resumo para que caiba no espaço correto
//  global $texto_resumo, $qtd_string, $tam_string, $spaco_a_acupar;
    $texto_resumo = $this->resumo;
    $qtd_string = strlen($this->resumo);  /// numero de caracteres da string
    $tam_string = $this->objpdf->GetStringWidth($this->resumo); /// espaco ocupado pela string
    $spaco_a_acupar = 825;  /// tamanho do espaco disponivel
    while( $spaco_a_acupar < $tam_string ){
        $qtd_string --;
        $tam_string = $this->objpdf->GetStringWidth(substr($texto_resumo,0, $qtd_string));
    }
    $this->objpdf->multicell(147, 3.5, substr($this->resumo,0,$qtd_string) ,0,'J', 0, 14);

    $this->objpdf->text($xcol +159, $xlin +187, 'T O T A L', 0, 1, 'L', 0);
    $this->objpdf->setxy($xcol +177, $xlin +183);
    $this->objpdf->cell(25, 6, str_pad(db_formatar($this->empenhado, 'f'), (count(db_formatar($this->empenhado, 'f'))+23) ,' ',0)  ,0,"R",'f');

    $sqlparag  = "select db02_texto   ";
    $sqlparag .= "  from db_documento ";
    $sqlparag .= "       inner join db_docparag on db03_docum    = db04_docum   ";
    $sqlparag .= "       inner join db_tipodoc on db08_codigo    = db03_tipodoc ";
    $sqlparag .= "       inner join db_paragrafo on db04_idparag = db02_idparag ";
    $sqlparag .= " where db03_tipodoc = 1501 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";


    $resparag = @db_query($sqlparag);

    if (@pg_numrows($resparag) > 0) {
      db_fieldsmemory($resparag,0);
      /**[extensao ordenadordespesa] doc_usuario*/

      @eval($db02_texto);
    } else {
      $sqlparagpadrao  = "select db61_texto ";
      $sqlparagpadrao .= "  from db_documentopadrao ";
      $sqlparagpadrao .= "       inner join db_docparagpadrao  on db62_coddoc   = db60_coddoc   ";
      $sqlparagpadrao .= "       inner join db_tipodoc         on db08_codigo   = db60_tipodoc  ";
      $sqlparagpadrao .= "       inner join db_paragrafopadrao on db61_codparag = db62_codparag ";
      $sqlparagpadrao .= " where db60_tipodoc = 1501 and db60_instit = " . db_getsession("DB_instit")." order by db62_ordem";

      $resparagpadrao = @db_query($sqlparagpadrao);
      if (@pg_numrows($resparagpadrao) > 0) {
        db_fieldsmemory($resparagpadrao,0);
        /**[extensao ordenadordespesa] doc_padrao*/

        @eval($db61_texto);
      }
    }

    $sHora = date("H:i:s", db_getsession("DB_datausu"));
    $this->objpdf->SetFont('Arial', '', 4);
    $this->objpdf->Text(2, 296, $this->texto);
    $this->objpdf->SetFont('Arial', '', 6);
    $this->objpdf->Text(200, 296, ($xxx +1).'ª via');
    $this->objpdf->SetFont('Arial', 'i', 6);
    $this->objpdf->Text(42, 296, " Hora: {$sHora}");
    $this->objpdf->setfont('Arial', '', 11);

    $xlin = 169;
  }
}
?>
