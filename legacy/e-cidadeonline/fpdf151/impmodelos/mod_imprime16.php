<?php

$this->objpdf->AliasNbPages();
$this->objpdf->settopmargin(1);
$this->objpdf->line(2, 148.5, 208, 148.5);

if ($this->seq == 0) {
    $xlin = 20;
    $this->objpdf->AddPage();
} else {
    $xlin = 171;
}

$xcol = 4;
$cinza = 225;
$this->objpdf->setfillcolor(225);
$this->objpdf->roundedrect($xcol - 2, $xlin - 18, 206, 144.5, 2, 'DF', '1234');
$this->objpdf->setfillcolor(255, 255, 255);
$this->objpdf->Setfont('Arial', 'B', 11);
$this->objpdf->text(130, $xlin - 13, 'RECIBO DE PAGAMENTO');
$this->objpdf->text(130, $xlin - 8, 'REF. AO MÊS ' . db_formatar($this->mes, 's', '0', 2, 'e', 0) . '/' . $this->ano);
$this->objpdf->text(130, $xlin - 3, $this->qualarquivo);

$this->objpdf->Image('imagens/files/' . $this->logo, 15, $xlin - 17, 12); //.$this->logo
$this->objpdf->Setfont('Arial', 'B', 9);
$this->objpdf->text(30, $xlin - 15, $this->prefeitura);
$this->objpdf->Setfont('Arial', '', 7);
$this->objpdf->text(30, $xlin - 12, $this->enderpref);
$this->objpdf->text(30, $xlin - 9, $this->municpref);
$this->objpdf->text(30, $xlin - 6, $this->telefpref);
$this->objpdf->text(30, $xlin - 3, db_formatar($this->cgcpref, 'cnpj'));

///retangulo da assinatura
$this->objpdf->Roundedrect($xcol + 178, $xlin + 14, $xcol + 20, 110, 2, 'DF', '1234');

//retangulo onde fica no nome do funcionario
$this->objpdf->Roundedrect($xcol, $xlin, $xcol + 198, 12, 2, 'DF', '1234');

$this->objpdf->Roundedrect($xcol, $xlin + 14, $xcol + 172, 82, 2, 'DF', '1234');
$this->objpdf->Roundedrect($xcol, $xlin + 96, $xcol + 172, 28, 2, 'DF', '1234');
$this->objpdf->line($xcol, $xlin + 22, $xcol + 176, $xlin + 22);
$this->objpdf->line($xcol, $xlin + 115, $xcol + 176, $xlin + 115);
$this->objpdf->line($xcol + 130, $xlin + 105, $xcol + 176, $xlin + 105);

$this->objpdf->line($xcol + 153, $xlin + 14, $xcol + 153, $xlin + 115);
$this->objpdf->line($xcol + 130, $xlin + 14, $xcol + 130, $xlin + 115);
$this->objpdf->line($xcol + 115, $xlin + 14, $xcol + 115, $xlin + 96);
$this->objpdf->line($xcol + 15, $xlin + 14, $xcol + 15, $xlin + 96);

$this->objpdf->Setfont('Arial', '', 6);
$this->objpdf->text($xcol + 2, $xlin + 3, 'Matrícula:');
$this->objpdf->Setfont('Arial', 'B', 7);
$this->objpdf->text($xcol + 12, $xlin + 3, $this->registro);

$this->objpdf->Setfont('Arial', '', 6);
$this->objpdf->text($xcol + 21, $xlin + 3, 'Nome:');
$this->objpdf->Setfont('Arial', 'B', 7);
$this->objpdf->text($xcol + 31, $xlin + 3, $this->nome);

$this->objpdf->Setfont('Arial', '', 6);
$this->objpdf->text($xcol + 100, $xlin + 3, 'Função:');
$this->objpdf->Setfont('Arial', 'B', 7);
$this->objpdf->text($xcol + 110, $xlin + 3, $this->descr_funcao);

$this->objpdf->Setfont('Arial', '', 6);
$this->objpdf->text($xcol + 170, $xlin + 3, 'Padrão:');
$this->objpdf->Setfont('Arial', 'B', 7);
$this->objpdf->text($xcol + 180, $xlin + 3, $this->padrao);

$this->objpdf->Setfont('Arial', '', 6);
$this->objpdf->text($xcol + 2, $xlin + 7, 'Lotação:');
$this->objpdf->Setfont('Arial', 'B', 7);
$this->objpdf->text($xcol + 12, $xlin + 7, $this->descr_lota);

$this->objpdf->Setfont('Arial', '', 6);
$this->objpdf->text($xcol + 100, $xlin + 7, 'Bco/Ag/Cta:');
$this->objpdf->Setfont('Arial', 'B', 7);
$this->objpdf->text($xcol + 112, $xlin + 7, $this->banco . ' / ' . $this->agencia . ' / ' . $this->conta);

$this->objpdf->Setfont('Arial', '', 6);
$this->objpdf->text($xcol + 170, $xlin + 7, 'Admissão:');
$this->objpdf->Setfont('Arial', 'B', 7);
$this->objpdf->text($xcol + 180, $xlin + 7, $this->admissao);

$this->objpdf->Setfont('Arial', '', 8);
$this->objpdf->text($xcol + 5, $xlin + 18, 'Cód.');
$this->objpdf->text($xcol + 55, $xlin + 18, 'Descrição');
$this->objpdf->text($xcol + 116, $xlin + 18, 'Referência');
$this->objpdf->text($xcol + 135, $xlin + 18, 'Proventos');
$this->objpdf->text($xcol + 157, $xlin + 18, 'Descontos');
$this->objpdf->Setfont('Arial', '', 6);
$this->objpdf->text($xcol + 155, $xlin + 98, 'Total dos Descontos');
$this->objpdf->text($xcol + 131, $xlin + 98, 'Total dos Vencimentos');
$this->objpdf->text($xcol + 133, $xlin + 111, 'Líquido a Receber');
$this->objpdf->setfillcolor(225);
$this->objpdf->rect($xcol + 153, $xlin + 105, 23, 10, 'DF');
$this->objpdf->setfillcolor(255, 255, 255);

$this->objpdf->text($xcol + 5, $xlin + 117, 'Margem Consignavel');
$this->objpdf->text($xcol + 40, $xlin + 117, 'Sal. Base');
$this->objpdf->text($xcol + 70, $xlin + 117, 'Base Previdência');
$this->objpdf->text($xcol + 100, $xlin + 117, 'Base FGTS');
$this->objpdf->text($xcol + 130, $xlin + 117, 'FGTS do Mês');
$this->objpdf->text($xcol + 160, $xlin + 117, 'Base IRRF');

$this->objpdf->sety($xlin + 24);
$maiscol = 0;
$yy = $this->objpdf->gety();
$provento = 0;
$margem_deduz = 0;
$margem_consignada = 0;
$desconto = 0;
$baseprev = 0;
$basefgts = 0;
$baseirrf = 0;
$valor_margem = 0;

$this->objpdf->Setfont('Arial', '', 7);

$rubricasArapiraca = array(
    '0005', '0006', '0007', '0008',
    '0011', '0014', '0017', '0018',
    '0020', '0021', '0023', '0055',
    '0060', '0061', '0062', '0063',
    '0064', '0065', '0098', '0099',
    '0101', '0104', '0105', '0107',
    '0108', '0112', '0116', '0117',
    '0118', '0121', '0122', '0126',
    '0129', '0131', '0132', '0133',
    '0134', '0135', '0136', '0137',
    '0138', '0150', '0151', '0160',
    '0170', '0190'
);

for ($ii = 0; $ii < $this->linhasenvelope; $ii++) {

    if (pg_result($this->recordenvelope, $ii, $this->tipo) == 'P') {

        $this->objpdf->cell(5, 3, trim(pg_result($this->recordenvelope, $ii, $this->rubrica)), 0, 0, "R", 0);
        $this->objpdf->cell(5, 3, "", 0, 0, "L", 0);
        $this->objpdf->cell(93, 3, pg_result($this->recordenvelope, $ii, $this->descr_rub), 0, 0, "L", 0);
        $this->objpdf->cell(20, 3, db_formatar(pg_result($this->recordenvelope, $ii, $this->quantidade), 'f'), 0, 0,
            "R", 0);
        $this->objpdf->cell(22, 3, db_formatar(pg_result($this->recordenvelope, $ii, $this->valor), 'f'), 0, 0, "R", 0);
        $this->objpdf->cell(22, 3, '', 0, 1, "R", 0);
        $provento += pg_result($this->recordenvelope, $ii, $this->valor);
        $rubrica = trim(pg_result($this->recordenvelope, $ii, $this->rubrica));

        if (db_getsession("DB_instit") == 1 && strtoupper($this->municpref) == 'ARAPIRACA' && in_array($rubrica, $rubricasArapiraca)) {
            $margem_consignada += pg_result($this->recordenvelope, $ii, $this->valor);
        }

    } elseif (pg_result($this->recordenvelope, $ii, $this->tipo) == 'D') {

        $this->objpdf->cell(5, 3, trim(pg_result($this->recordenvelope, $ii, $this->rubrica)), 0, 0, "R", 0);
        $this->objpdf->cell(5, 3, "", 0, 0, "L", 0);
        $this->objpdf->cell(93, 3, pg_result($this->recordenvelope, $ii, $this->descr_rub), 0, 0, "L", 0);
        $this->objpdf->cell(20, 3, db_formatar(pg_result($this->recordenvelope, $ii, $this->quantidade), 'f'), 0, 0,
            "R", 0);
        $this->objpdf->cell(22, 3, '', 0, 0, "R", 0);
        $this->objpdf->cell(22, 3, db_formatar(pg_result($this->recordenvelope, $ii, $this->valor), 'f'), 0, 1, "R", 0);
        $desconto += pg_result($this->recordenvelope, $ii, $this->valor);
        $rubrica = trim(pg_result($this->recordenvelope, $ii, $this->rubrica));

        if (db_getsession("DB_instit") == 1 && strtoupper($this->municpref) == 'ARAPIRACA') {

            if ($rubrica == 'R901' || $rubrica == 'R904' || $rubrica == 'R913' || $rubrica == '0333') {
                $margem_consignada -= pg_result($this->recordenvelope, $ii, $this->valor);
            } elseif (
                $rubrica == '0330' ||
                $rubrica == '0334' ||
                $rubrica == '0335' ||
                $rubrica == '0336' ||
                $rubrica == '0337' ||
                $rubrica == '0338' ||
                $rubrica == '0340' ||
                $rubrica == '0341' ||
                $rubrica == '0342' ||
                $rubrica == '0343' ||
                $rubrica == '0344' ||
                $rubrica == '0345'
            ) {
                $margem_deduz += pg_result($this->recordenvelope, $ii, $this->valor);
            }
        }

    } else {

        if (pg_result($this->recordenvelope, $ii, $this->rubrica) == 'R981' || pg_result($this->recordenvelope, $ii, $this->rubrica) == 'R982') {
            $baseirrf += pg_result($this->recordenvelope, $ii, $this->valor);
        } elseif (pg_result($this->recordenvelope, $ii, $this->rubrica) == 'R992') {
            $baseprev += pg_result($this->recordenvelope, $ii, $this->valor);
        } elseif (pg_result($this->recordenvelope, $ii, $this->rubrica) == 'R991') {
            $basefgts += pg_result($this->recordenvelope, $ii, $this->valor);
        } elseif (pg_result($this->recordenvelope, $ii, $this->rubrica) == 'R803') {
            $valor_margem += pg_result($this->recordenvelope, $ii, $this->valor);
        }
        continue;
    }
}

$this->objpdf->text($xcol + 134, $xlin + 102, db_formatar($provento, 'f'));
$this->objpdf->text($xcol + 157, $xlin + 102, db_formatar($desconto, 'f'));
$this->objpdf->Setfont('Arial', 'B', 9);
$this->objpdf->text($xcol + 157, $xlin + 111, db_formatar(($provento - $desconto), 'f'));
$this->objpdf->Setfont('Arial', '', 8);

if (strtoupper($this->municpref) == 'ARAPIRACA') {
    if (db_getsession("DB_instit") == 1) {
        $this->objpdf->text($xcol + 5, $xlin + 121,
            db_formatar(((($margem_consignada * 30 / 100) - $margem_deduz) < 0 ? 0 : (($margem_consignada * 30 / 100) - $margem_deduz)),
                'f'));
    } else {
        $this->objpdf->text($xcol + 5, $xlin + 121, db_formatar($provento * 30 / 100, 'f'));
    }
} else {
    $this->objpdf->text($xcol + 5, $xlin + 121, db_formatar($valor_margem, 'f'));
}

$this->objpdf->text($xcol + 33, $xlin + 121, db_formatar($this->f010, 'f'));
$this->objpdf->text($xcol + 70, $xlin + 121, db_formatar($baseprev, 'f'));
$this->objpdf->text($xcol + 95, $xlin + 121, db_formatar($basefgts, 'f'));
$this->objpdf->text($xcol + 127, $xlin + 121, db_formatar(($basefgts * 8 / 100), 'f'));
$this->objpdf->text($xcol + 155, $xlin + 121, db_formatar($baseirrf, 'f'));

$this->objpdf->SetY($xlin + 97);
$this->objpdf->SetX($xcol + 3);
$this->objpdf->multicell(125, 4, 'MENSAGEM :   ' . $this->mensagem, 0, "J");
$this->objpdf->SetX($xcol + 3);
$this->objpdf->multicell(0, 4, $this->histparcel);
$this->objpdf->Setfont('Arial', '', 6);
$this->objpdf->setx(15);
$this->objpdf->setfillcolor(0);
$this->objpdf->Setfont('Arial', '', 5);
$this->objpdf->TextWithDirection(185, $xlin + 120,
    'DECLARO TER RECEBIDO A IMPORTÂNCIA LÍQUIDA DISCRIMIDA NESTE RECIBO.', 'U'); // texto no canhoto do carne
$this->objpdf->line($xcol + 193, $xlin + 25, $xcol + 193, $xlin + 70);
$this->objpdf->line($xcol + 193, $xlin + 75, $xcol + 193, $xlin + 115);
$this->objpdf->TextWithDirection(200, $xlin + 97, 'DATA', 'U'); // texto no canhoto do carne
$this->objpdf->TextWithDirection(200, $xlin + 60, 'ASSINATURA DO FUNCIONÁRIO', 'U'); // texto no canhoto do carne
$this->objpdf->TextWithDirection(209.7, $xlin, $this->total . ' / ' . $this->numero, 'U'); // numero do contra-cheque
$this->objpdf->TextWithDirection(205, $xlin + 120, "Para Verificar Autenticidade Acesse: " . $this->url, 'U');
$this->objpdf->TextWithDirection(205, $xlin + 60, "Código da Autenticação: ", 'U');
$this->objpdf->Setfont('Arial', 'B', 5);
$this->objpdf->TextWithDirection(205, $xlin + 40, $this->codautent, 'U');

?>
