<?php

/**
 *          E-cidade Software Publico para Gestao Municipal
 *        Copyright (C) 2014 DBSeller Servicos de Informatica
 *                      www.dbseller.com.br
 *                   e-cidade@dbseller.com.br
 * 
 * Este programa e software livre; voce pode redistribui-lo e/ou
 * modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 * publicada pela Free Software Foundation; tanto a versao 2 da
 * Licenca como (a seu criterio) qualquer versao mais nova.
 * 
 * Este programa e distribuido na expectativa de ser util, mas SEM
 * QUALQUER GARANTIA; sem mesmo a garantia implicita de
 * COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 * PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 * detalhes.
 * 
 * Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 * junto com este programa; se nao, escreva para a Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 * 02111-1307, USA.
 *  
 * Copia da licenca no diretorio licenca/licenca_en.txt
 *                               licenca/licenca_pt.txt
 *
 * @author  $Author: dbjeferson.belmiro $
 * @version $Revision: 1.6 $
 */

class cl_cfpess {

  /**
   * Cria variáveis de erro.
   */ 
  var $rotulo     = null;
  var $query_sql  = null;
  var $numrows    = 0;
  var $numrows_incluir = 0;
  var $numrows_alterar = 0;
  var $numrows_excluir = 0;
  var $erro_status= null;
  var $erro_sql   = null;
  var $erro_banco = null;
  var $erro_msg   = null;
  var $erro_campo = null;
  var $pagina_retorno = null;

  /**
   * Cria variáveis do arquivo.
   */
  var $r11_instit = 0;
  var $r11_anousu = 0;
  var $r11_mesusu = 0;
  var $r11_codaec = null;
  var $r11_natest = null;
  var $r11_cdfpas = 0;
  var $r11_cdactr = 0;
  var $r11_peactr = 0;
  var $r11_pctemp = 0;
  var $r11_pcterc = 0;
  var $r11_fgts12 = 0;
  var $r11_cdcef = null;
  var $r11_cdfgts = null;
  var $r11_ultger_dia = null;
  var $r11_ultger_mes = null;
  var $r11_ultger_ano = null;
  var $r11_ultger = null;
  var $r11_ultfec_dia = null;
  var $r11_ultfec_mes = null;
  var $r11_ultfec_ano = null;
  var $r11_ultfec = null;
  var $r11_arredn = 0;
  var $r11_sald13 = 'f';
  var $r11_datai_dia = null;
  var $r11_datai_mes = null;
  var $r11_datai_ano = null;
  var $r11_datai = null;
  var $r11_dataf_dia = null;
  var $r11_dataf_mes = null;
  var $r11_dataf_ano = null;
  var $r11_dataf = null;
  var $r11_fecha = null;
  var $r11_ultreg = 0;
  var $r11_codipe = 0;
  var $r11_mes13 = 0;
  var $r11_tbprev = 0;
  var $r11_confer = 'f';
  var $r11_valor = 0;
  var $r11_dtipe = 0;
  var $r11_implan = null;
  var $r11_subpes = null;
  var $r11_rubmat = null;
  var $r11_eleina = null;
  var $r11_elepen = null;
  var $r11_rubnat = null;
  var $r11_rubdec = null;
  var $r11_qtdcal = 0;
  var $r11_palime = null;
  var $r11_altfer = null;
  var $r11_ferias = null;
  var $r11_fer13 = null;
  var $r11_ferant = null;
  var $r11_fer13o = null;
  var $r11_fer13a = null;
  var $r11_ferabo = null;
  var $r11_feabot = null;
  var $r11_feradi = null;
  var $r11_fadiab = null;
  var $r11_recalc = 'f';
  var $r11_pagaab = 'f';
  var $r11_fersal = null;
  var $r11_vtprop = 'f';
  var $r11_desliq = null;
  var $r11_propae = 'f';
  var $r11_propac = 'f';
  var $r11_codestrut = 0;
  var $r11_geracontipe = 'f';
  var $r11_13ferias = 'f';
  var $r11_pagarferias = null;
  var $r11_vtfer = 'f';
  var $r11_vtcons = 'f';
  var $r11_vtmpro = 'f';
  var $r11_localtrab = 0;
  var $r11_databaseatra_dia = null;
  var $r11_databaseatra_mes = null;
  var $r11_databaseatra_ano = null;
  var $r11_databaseatra = null;
  var $r11_rubpgintegral = null;
  var $r11_conver = null;
  var $r11_concatdv = 'f';
  var $r11_infla = null;
  var $r11_baseipe = null;
  var $r11_txadm = 0;
  var $r11_modanalitica = 0;
  var $r11_viravalemes = 'f';
  var $r11_histslip = 0;
  var $r11_mensagempadraotxt = null;
  var $r11_recpatrafasta = 'f';
  var $r11_relatoriocontracheque = 0;
  var $r11_relatorioempenhofolha = 0;
  var $r11_relatoriocomprovanterendimentos = 0;
  var $r11_relatoriotermorescisao = 0;
  var $r11_geraretencaoempenho = 'f';
  var $r11_percentualipe = 0;
  var $r11_datainiciovigenciarpps_dia = null;
  var $r11_datainiciovigenciarpps_mes = null;
  var $r11_datainiciovigenciarpps_ano = null;
  var $r11_datainiciovigenciarpps = null;
  var $r11_sistemacontroleponto = null;
  var $r11_baseconsignada = null;
  var $r11_abonoprevidencia = null;
  var $r11_compararferias = 'f';
  var $r11_baseferias = null; 
  var $r11_basesalario = null; 
  var $r11_suplementar = 'f';
  var $r11_rubricasubstituicaoatual = null; 
  var $r11_rubricasubstituicaoanterior = null; 

  /**
   * Cria propriedade com as variáveis do arquivo.
   */
  var $campos = "
    r11_instit = int4 = Cod. Instituição 
    r11_anousu = int4 = Ano do Exercício 
    r11_mesusu = int4 = Mês do Exercício 
    r11_codaec = varchar(5) = CNAE 
    r11_natest = varchar(4) = Natureza 
    r11_cdfpas = int4 = FPAS 
    r11_cdactr = int4 = CAT 
    r11_peactr = float8 = Acid. de Trabalho % 
    r11_pctemp = float8 = INSS % 
    r11_pcterc = float8 = Terceiros % 
    r11_fgts12 = int4 = FGTS 
    r11_cdcef = varchar(5) = Código FGTS 
    r11_cdfgts = varchar(8) = Sequência FGTS 
    r11_ultger = date = Data de Mov. do Gerfxxx.dbf 
    r11_ultfec = date = Último Fechamento 
    r11_arredn = int4 = Arredondamento 
    r11_sald13 = bool = Adiantamento de 13º 
    r11_datai = date = data inicial do periodo da fol 
    r11_dataf = date = data final do periodo da folha 
    r11_fecha = varchar(12) = Indica se folha foi fechada 
    r11_ultreg = int4 = Último Registro 
    r11_codipe = int4 = Codigo do IPE 
    r11_mes13 = int4 = Mês Pagto Saldo 13º 
    r11_tbprev = int4 = Tabela INSS 
    r11_confer = bool = Ignora Férias 
    r11_valor = float8 = Valor Minimo Contr ao IPE 
    r11_dtipe = int4 = Codigo Tabela do IPE 
    r11_implan = varchar(7) = Ano/mes da implantação 
    r11_subpes = varchar(7) = Ano/Mes da Folha 
    r11_rubmat = varchar(4) = Salário maternidade 
    r11_eleina = varchar(12) = Elementos de Inativos 
    r11_elepen = varchar(12) = Elemento de Pensionistas 
    r11_rubnat = varchar(4) = Rubrica do Sal. Maternidade 
    r11_rubdec = varchar(4) = Adiantamento de 13o. 
    r11_qtdcal = int4 = Qtd. de Servidores Cálculo Geral 
    r11_palime = varchar(4) = Rubrica Pensão Alimentícia 
    r11_altfer = varchar(7) = Alt. Férias 
    r11_ferias = varchar(4) = Férias 
    r11_fer13 = varchar(4) = 1/3 de Férias 
    r11_ferant = varchar(4) = Férias Mês Anterior 
    r11_fer13o = varchar(4) = 1/3 de férias 
    r11_fer13a = varchar(4) = 1/3 Abono de Férias 
    r11_ferabo = varchar(4) = Abono de Férias 
    r11_feabot = varchar(4) = Abono Mês Anterior 
    r11_feradi = varchar(4) = Adiantamento de Férias 
    r11_fadiab = varchar(4) = Adiantamento Abono de Férias 
    r11_recalc = bool = recalcula 1/3 ferias mês gozo 
    r11_pagaab = bool = Pagar abono de férias 
    r11_fersal = varchar(1) = Paga como 
    r11_vtprop = bool = Considerar dias afastados 
    r11_desliq = varchar(20) = Códigos Sobre o Líquido 
    r11_propae = bool = Proporcionaliza estatutário 
    r11_propac = bool = Proporcionaliza celetista 
    r11_codestrut = int4 = Estrutural da Lotação 
    r11_geracontipe = bool = Gerar contrato IPE 
    r11_13ferias = bool = Pagar 1/3 férias 
    r11_pagarferias = char(1) = Pagar férias 
    r11_vtfer = bool = Descontar dias de férias 
    r11_vtcons = bool = Apresentar qtd proporcional 
    r11_vtmpro = bool = Proporcionalizar com dias afastados 
    r11_localtrab = int4 = Estrutural do Local 
    r11_databaseatra = date = Base atrasados 
    r11_rubpgintegral = varchar(32) = Pagamento Valor Integral 
    r11_conver = varchar(7) = Ano/Mês de conversão 
    r11_concatdv = bool = Concatenar Dígito 
    r11_infla = varchar(5) = Código do Inflator 
    r11_baseipe = varchar(4) = Base do IPE 
    r11_txadm = float4 = Taxa de Admin. Fundo 
    r11_modanalitica = int4 = Modelo de Impressão 
    r11_viravalemes = bool = Virada Mensal de Vales 
    r11_histslip = int4 = Histórico de SLIP 
    r11_mensagempadraotxt = text = Expressão padrão contra-cheque gráfica 
    r11_recpatrafasta = bool = Recolhe Patronal no Afastamento 
    r11_relatoriocontracheque = int4 = Relatório contra cheque 
    r11_relatorioempenhofolha = int4 = Empenho da folha 
    r11_relatoriocomprovanterendimentos = int4 = Comprovante de rendimentos 
    r11_relatoriotermorescisao = int4 = Termo de Rescisão 
    r11_geraretencaoempenho = bool = Gera Retenção para Empenho 
    r11_percentualipe = numeric = Percentual IPE 
    r11_datainiciovigenciarpps = date = Data de Início de Vigência RPPS 
    r11_sistemacontroleponto = int4 = Tipo de Sistema de Controle de Ponto 
    r11_baseconsignada = char(4) = Base Consignada 
    r11_abonoprevidencia = varchar(4) = Rubrica Abono de Permanência 
    r11_compararferias = bool = Efetuar Comparativo 
	r11_baseferias = varchar(4) = Base de Férias 
    r11_basesalario = varchar(4) = Base de Salário 
    r11_suplementar = bool = Suplementar 
    r11_rubricasubstituicaoatual = varchar(4) = Exercício atual 
    r11_rubricasubstituicaoanterior = varchar(4) = Exercício anterior 
  ";

  /**
   * Função construtor da classe.
   */
  function cl_cfpess() { 
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("cfpess"); 
    $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
  }
  
  /**
   * Função erro.
   */
  function erro($mostra,$retorna) { 
    if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
       echo "<script>alert(\"".$this->erro_msg."\");</script>";
       if($retorna==true){
          echo "<script>location.href='".$this->pagina_retorno."'</script>";
       }
    }
  }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->r11_instit = ($this->r11_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_instit"]:$this->r11_instit);
       $this->r11_anousu = ($this->r11_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_anousu"]:$this->r11_anousu);
       $this->r11_mesusu = ($this->r11_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_mesusu"]:$this->r11_mesusu);
       $this->r11_codaec = ($this->r11_codaec == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_codaec"]:$this->r11_codaec);
       $this->r11_natest = ($this->r11_natest == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_natest"]:$this->r11_natest);
       $this->r11_cdfpas = ($this->r11_cdfpas == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_cdfpas"]:$this->r11_cdfpas);
       $this->r11_cdactr = ($this->r11_cdactr == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_cdactr"]:$this->r11_cdactr);
       $this->r11_peactr = ($this->r11_peactr == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_peactr"]:$this->r11_peactr);
       $this->r11_pctemp = ($this->r11_pctemp == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_pctemp"]:$this->r11_pctemp);
       $this->r11_pcterc = ($this->r11_pcterc == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_pcterc"]:$this->r11_pcterc);
       $this->r11_fgts12 = ($this->r11_fgts12 == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_fgts12"]:$this->r11_fgts12);
       $this->r11_cdcef = ($this->r11_cdcef == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_cdcef"]:$this->r11_cdcef);
       $this->r11_cdfgts = ($this->r11_cdfgts == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_cdfgts"]:$this->r11_cdfgts);
       if($this->r11_ultger == ""){
         $this->r11_ultger_dia = ($this->r11_ultger_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_ultger_dia"]:$this->r11_ultger_dia);
         $this->r11_ultger_mes = ($this->r11_ultger_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_ultger_mes"]:$this->r11_ultger_mes);
         $this->r11_ultger_ano = ($this->r11_ultger_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_ultger_ano"]:$this->r11_ultger_ano);
         if($this->r11_ultger_dia != ""){
            $this->r11_ultger = $this->r11_ultger_ano."-".$this->r11_ultger_mes."-".$this->r11_ultger_dia;
         }
       }
       if($this->r11_ultfec == ""){
         $this->r11_ultfec_dia = ($this->r11_ultfec_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_ultfec_dia"]:$this->r11_ultfec_dia);
         $this->r11_ultfec_mes = ($this->r11_ultfec_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_ultfec_mes"]:$this->r11_ultfec_mes);
         $this->r11_ultfec_ano = ($this->r11_ultfec_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_ultfec_ano"]:$this->r11_ultfec_ano);
         if($this->r11_ultfec_dia != ""){
            $this->r11_ultfec = $this->r11_ultfec_ano."-".$this->r11_ultfec_mes."-".$this->r11_ultfec_dia;
         }
       }
       $this->r11_arredn = ($this->r11_arredn == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_arredn"]:$this->r11_arredn);
       $this->r11_sald13 = ($this->r11_sald13 == "f"?@$GLOBALS["HTTP_POST_VARS"]["r11_sald13"]:$this->r11_sald13);
       if($this->r11_datai == ""){
         $this->r11_datai_dia = ($this->r11_datai_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_datai_dia"]:$this->r11_datai_dia);
         $this->r11_datai_mes = ($this->r11_datai_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_datai_mes"]:$this->r11_datai_mes);
         $this->r11_datai_ano = ($this->r11_datai_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_datai_ano"]:$this->r11_datai_ano);
         if($this->r11_datai_dia != ""){
            $this->r11_datai = $this->r11_datai_ano."-".$this->r11_datai_mes."-".$this->r11_datai_dia;
         }
       }
       if($this->r11_dataf == ""){
         $this->r11_dataf_dia = ($this->r11_dataf_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_dataf_dia"]:$this->r11_dataf_dia);
         $this->r11_dataf_mes = ($this->r11_dataf_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_dataf_mes"]:$this->r11_dataf_mes);
         $this->r11_dataf_ano = ($this->r11_dataf_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_dataf_ano"]:$this->r11_dataf_ano);
         if($this->r11_dataf_dia != ""){
            $this->r11_dataf = $this->r11_dataf_ano."-".$this->r11_dataf_mes."-".$this->r11_dataf_dia;
         }
       }
       $this->r11_fecha = ($this->r11_fecha == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_fecha"]:$this->r11_fecha);
       $this->r11_ultreg = ($this->r11_ultreg == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_ultreg"]:$this->r11_ultreg);
       $this->r11_codipe = ($this->r11_codipe == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_codipe"]:$this->r11_codipe);
       $this->r11_mes13 = ($this->r11_mes13 == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_mes13"]:$this->r11_mes13);
       $this->r11_tbprev = ($this->r11_tbprev == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_tbprev"]:$this->r11_tbprev);
       $this->r11_confer = ($this->r11_confer == "f"?@$GLOBALS["HTTP_POST_VARS"]["r11_confer"]:$this->r11_confer);
       $this->r11_valor = ($this->r11_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_valor"]:$this->r11_valor);
       $this->r11_dtipe = ($this->r11_dtipe == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_dtipe"]:$this->r11_dtipe);
       $this->r11_implan = ($this->r11_implan == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_implan"]:$this->r11_implan);
       $this->r11_subpes = ($this->r11_subpes == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_subpes"]:$this->r11_subpes);
       $this->r11_rubmat = ($this->r11_rubmat == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_rubmat"]:$this->r11_rubmat);
       $this->r11_eleina = ($this->r11_eleina == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_eleina"]:$this->r11_eleina);
       $this->r11_elepen = ($this->r11_elepen == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_elepen"]:$this->r11_elepen);
       $this->r11_rubnat = ($this->r11_rubnat == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_rubnat"]:$this->r11_rubnat);
       $this->r11_rubdec = ($this->r11_rubdec == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_rubdec"]:$this->r11_rubdec);
       $this->r11_qtdcal = ($this->r11_qtdcal == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_qtdcal"]:$this->r11_qtdcal);
       $this->r11_palime = ($this->r11_palime == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_palime"]:$this->r11_palime);
       $this->r11_altfer = ($this->r11_altfer == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_altfer"]:$this->r11_altfer);
       $this->r11_ferias = ($this->r11_ferias == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_ferias"]:$this->r11_ferias);
       $this->r11_fer13 = ($this->r11_fer13 == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_fer13"]:$this->r11_fer13);
       $this->r11_ferant = ($this->r11_ferant == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_ferant"]:$this->r11_ferant);
       $this->r11_fer13o = ($this->r11_fer13o == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_fer13o"]:$this->r11_fer13o);
       $this->r11_fer13a = ($this->r11_fer13a == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_fer13a"]:$this->r11_fer13a);
       $this->r11_ferabo = ($this->r11_ferabo == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_ferabo"]:$this->r11_ferabo);
       $this->r11_feabot = ($this->r11_feabot == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_feabot"]:$this->r11_feabot);
       $this->r11_feradi = ($this->r11_feradi == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_feradi"]:$this->r11_feradi);
       $this->r11_fadiab = ($this->r11_fadiab == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_fadiab"]:$this->r11_fadiab);
       $this->r11_recalc = ($this->r11_recalc == "f"?@$GLOBALS["HTTP_POST_VARS"]["r11_recalc"]:$this->r11_recalc);
       $this->r11_pagaab = ($this->r11_pagaab == "f"?@$GLOBALS["HTTP_POST_VARS"]["r11_pagaab"]:$this->r11_pagaab);
       $this->r11_fersal = ($this->r11_fersal == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_fersal"]:$this->r11_fersal);
       $this->r11_vtprop = ($this->r11_vtprop == "f"?@$GLOBALS["HTTP_POST_VARS"]["r11_vtprop"]:$this->r11_vtprop);
       $this->r11_desliq = ($this->r11_desliq == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_desliq"]:$this->r11_desliq);
       $this->r11_propae = ($this->r11_propae == "f"?@$GLOBALS["HTTP_POST_VARS"]["r11_propae"]:$this->r11_propae);
       $this->r11_propac = ($this->r11_propac == "f"?@$GLOBALS["HTTP_POST_VARS"]["r11_propac"]:$this->r11_propac);
       $this->r11_codestrut = ($this->r11_codestrut == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_codestrut"]:$this->r11_codestrut);
       $this->r11_geracontipe = ($this->r11_geracontipe == "f"?@$GLOBALS["HTTP_POST_VARS"]["r11_geracontipe"]:$this->r11_geracontipe);
       $this->r11_13ferias = ($this->r11_13ferias == "f"?@$GLOBALS["HTTP_POST_VARS"]["r11_13ferias"]:$this->r11_13ferias);
       $this->r11_pagarferias = ($this->r11_pagarferias == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_pagarferias"]:$this->r11_pagarferias);
       $this->r11_vtfer = ($this->r11_vtfer == "f"?@$GLOBALS["HTTP_POST_VARS"]["r11_vtfer"]:$this->r11_vtfer);
       $this->r11_vtcons = ($this->r11_vtcons == "f"?@$GLOBALS["HTTP_POST_VARS"]["r11_vtcons"]:$this->r11_vtcons);
       $this->r11_vtmpro = ($this->r11_vtmpro == "f"?@$GLOBALS["HTTP_POST_VARS"]["r11_vtmpro"]:$this->r11_vtmpro);
       $this->r11_localtrab = ($this->r11_localtrab == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_localtrab"]:$this->r11_localtrab);
       if($this->r11_databaseatra == ""){
         $this->r11_databaseatra_dia = ($this->r11_databaseatra_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_databaseatra_dia"]:$this->r11_databaseatra_dia);
         $this->r11_databaseatra_mes = ($this->r11_databaseatra_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_databaseatra_mes"]:$this->r11_databaseatra_mes);
         $this->r11_databaseatra_ano = ($this->r11_databaseatra_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_databaseatra_ano"]:$this->r11_databaseatra_ano);
         if($this->r11_databaseatra_dia != ""){
            $this->r11_databaseatra = $this->r11_databaseatra_ano."-".$this->r11_databaseatra_mes."-".$this->r11_databaseatra_dia;
         }
       }
       $this->r11_rubpgintegral = ($this->r11_rubpgintegral == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_rubpgintegral"]:$this->r11_rubpgintegral);
       $this->r11_conver = ($this->r11_conver == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_conver"]:$this->r11_conver);
       $this->r11_concatdv = ($this->r11_concatdv == "f"?@$GLOBALS["HTTP_POST_VARS"]["r11_concatdv"]:$this->r11_concatdv);
       $this->r11_infla = ($this->r11_infla == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_infla"]:$this->r11_infla);
       $this->r11_baseipe = ($this->r11_baseipe == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_baseipe"]:$this->r11_baseipe);
       $this->r11_txadm = ($this->r11_txadm == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_txadm"]:$this->r11_txadm);
       $this->r11_modanalitica = ($this->r11_modanalitica == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_modanalitica"]:$this->r11_modanalitica);
       $this->r11_viravalemes = ($this->r11_viravalemes == "f"?@$GLOBALS["HTTP_POST_VARS"]["r11_viravalemes"]:$this->r11_viravalemes);
       $this->r11_histslip = ($this->r11_histslip == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_histslip"]:$this->r11_histslip);
       $this->r11_mensagempadraotxt = ($this->r11_mensagempadraotxt == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_mensagempadraotxt"]:$this->r11_mensagempadraotxt);
       $this->r11_recpatrafasta = ($this->r11_recpatrafasta == "f"?@$GLOBALS["HTTP_POST_VARS"]["r11_recpatrafasta"]:$this->r11_recpatrafasta);
       $this->r11_relatoriocontracheque = ($this->r11_relatoriocontracheque == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_relatoriocontracheque"]:$this->r11_relatoriocontracheque);
       $this->r11_relatorioempenhofolha = ($this->r11_relatorioempenhofolha == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_relatorioempenhofolha"]:$this->r11_relatorioempenhofolha);
       $this->r11_relatoriocomprovanterendimentos = ($this->r11_relatoriocomprovanterendimentos == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_relatoriocomprovanterendimentos"]:$this->r11_relatoriocomprovanterendimentos);
       $this->r11_relatoriotermorescisao = ($this->r11_relatoriotermorescisao == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_relatoriotermorescisao"]:$this->r11_relatoriotermorescisao);
       $this->r11_geraretencaoempenho = ($this->r11_geraretencaoempenho == "f"?@$GLOBALS["HTTP_POST_VARS"]["r11_geraretencaoempenho"]:$this->r11_geraretencaoempenho);
       $this->r11_percentualipe = ($this->r11_percentualipe == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_percentualipe"]:$this->r11_percentualipe);
       if($this->r11_datainiciovigenciarpps == ""){
         $this->r11_datainiciovigenciarpps_dia = ($this->r11_datainiciovigenciarpps_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_datainiciovigenciarpps_dia"]:$this->r11_datainiciovigenciarpps_dia);
         $this->r11_datainiciovigenciarpps_mes = ($this->r11_datainiciovigenciarpps_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_datainiciovigenciarpps_mes"]:$this->r11_datainiciovigenciarpps_mes);
         $this->r11_datainiciovigenciarpps_ano = ($this->r11_datainiciovigenciarpps_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_datainiciovigenciarpps_ano"]:$this->r11_datainiciovigenciarpps_ano);
         if($this->r11_datainiciovigenciarpps_dia != ""){
            $this->r11_datainiciovigenciarpps = $this->r11_datainiciovigenciarpps_ano."-".$this->r11_datainiciovigenciarpps_mes."-".$this->r11_datainiciovigenciarpps_dia;
         }
       }
       $this->r11_sistemacontroleponto = ($this->r11_sistemacontroleponto == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_sistemacontroleponto"]:$this->r11_sistemacontroleponto);
       $this->r11_baseconsignada = ($this->r11_baseconsignada == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_baseconsignada"]:$this->r11_baseconsignada);
       $this->r11_abonoprevidencia = ($this->r11_abonoprevidencia == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_abonoprevidencia"]:$this->r11_abonoprevidencia);
       $this->r11_compararferias = ($this->r11_compararferias == "f"?@$GLOBALS["HTTP_POST_VARS"]["r11_compararferias"]:$this->r11_compararferias);
       $this->r11_baseferias = ($this->r11_baseferias == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_baseferias"]:$this->r11_baseferias);
       $this->r11_basesalario = ($this->r11_basesalario == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_basesalario"]:$this->r11_basesalario);
       $this->r11_suplementar = ($this->r11_suplementar == "f"?@$GLOBALS["HTTP_POST_VARS"]["r11_suplementar"]:$this->r11_suplementar);
       $this->r11_rubricasubstituicaoatual = ($this->r11_rubricasubstituicaoatual == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_rubricasubstituicaoatual"]:$this->r11_rubricasubstituicaoatual);
       $this->r11_rubricasubstituicaoanterior = ($this->r11_rubricasubstituicaoanterior == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_rubricasubstituicaoanterior"]:$this->r11_rubricasubstituicaoanterior);
     }else{
       $this->r11_instit = ($this->r11_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_instit"]:$this->r11_instit);
       $this->r11_anousu = ($this->r11_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_anousu"]:$this->r11_anousu);
       $this->r11_mesusu = ($this->r11_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r11_mesusu"]:$this->r11_mesusu);
     }
   }
   // funcao para inclusao
   function incluir ($r11_anousu,$r11_mesusu,$r11_instit){ 
      $this->atualizacampos();
     if($this->r11_cdfpas == null ){ 
       $this->r11_cdfpas = "0";
     }
     if($this->r11_cdactr == null ){ 
       $this->r11_cdactr = "0";
     }
     if($this->r11_peactr == null ){ 
       $this->r11_peactr = "0";
     }
     if($this->r11_pctemp == null ){ 
       $this->r11_pctemp = "0";
     }
     if($this->r11_pcterc == null ){ 
       $this->r11_pcterc = "0";
     }
     if($this->r11_fgts12 == null ){ 
       $this->r11_fgts12 = "0";
     }
     if($this->r11_ultfec == null ){ 
       $this->r11_ultfec = "null";
     }
     if($this->r11_arredn == null ){ 
       $this->r11_arredn = "0";
     }
     if($this->r11_sald13 == null ){ 
       $this->r11_sald13 = "f";
     }
     if($this->r11_ultreg == null ){ 
       $this->r11_ultreg = "0";
     }
     if($this->r11_codipe == null ){ 
       $this->r11_codipe = "0";
     }
     if($this->r11_mes13 == null ){ 
       $this->r11_mes13 = "0";
     }
     if($this->r11_tbprev == null ){ 
       $this->r11_tbprev = "0";
     }
     if($this->r11_confer == null ){ 
       $this->r11_confer = "f";
     }
     if($this->r11_qtdcal == null ){ 
       $this->r11_qtdcal = "0";
     }
     if($this->r11_recalc == null ){ 
       $this->r11_recalc = "f";
     }
     if($this->r11_pagaab == null ){ 
       $this->r11_pagaab = "f";
     }
     if($this->r11_vtprop == null ){ 
       $this->r11_vtprop = "f";
     }
     if($this->r11_propae == null ){ 
       $this->r11_propae = "f";
     }
     if($this->r11_propac == null ){ 
       $this->r11_propac = "f";
     }
     if($this->r11_codestrut == null ){ 
       $this->r11_codestrut = "0";
     }
     if($this->r11_geracontipe == null ){ 
       $this->r11_geracontipe = "f";
     }
     if($this->r11_13ferias == null ){ 
       $this->r11_13ferias = "f";
     }
     if($this->r11_vtfer == null ){ 
       $this->r11_vtfer = "f";
     }
     if($this->r11_vtcons == null ){ 
       $this->r11_vtcons = "f";
     }
     if($this->r11_vtmpro == null ){ 
       $this->r11_vtmpro = "f";
     }
     if($this->r11_localtrab == null ){ 
       $this->r11_localtrab = "0";
     }
     if($this->r11_databaseatra == null ){ 
       $this->r11_databaseatra = "null";
     }
     if($this->r11_concatdv == null ){ 
       $this->r11_concatdv = "f";
     }
     if($this->r11_txadm == null ){ 
       $this->r11_txadm = "0";
     }
     if($this->r11_modanalitica == null ){ 
       $this->r11_modanalitica = "0";
     }
     if($this->r11_viravalemes == null ){ 
       $this->erro_sql = " Campo Virada Mensal de Vales não informado.";
       $this->erro_campo = "r11_viravalemes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r11_histslip == null ){ 
       $this->r11_histslip = "0";
     }
     if($this->r11_mensagempadraotxt == null ){ 
       $this->r11_mensagempadraotxt = "null";
     }
     if($this->r11_recpatrafasta == null ){ 
       $this->r11_recpatrafasta = "f";
     }
     if($this->r11_relatoriocontracheque == null ){ 
       $this->erro_sql = " Campo Relatório contra cheque não informado.";
       $this->erro_campo = "r11_relatoriocontracheque";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r11_relatorioempenhofolha == null ){ 
       $this->erro_sql = " Campo Empenho da folha não informado.";
       $this->erro_campo = "r11_relatorioempenhofolha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r11_relatoriocomprovanterendimentos == null ){ 
       $this->erro_sql = " Campo Comprovante de rendimentos não informado.";
       $this->erro_campo = "r11_relatoriocomprovanterendimentos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r11_relatoriotermorescisao == null ){ 
       $this->erro_sql = " Campo Termo de Rescisão não informado.";
       $this->erro_campo = "r11_relatoriotermorescisao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r11_geraretencaoempenho == null ){ 
       $this->erro_sql = " Campo Gera Retenção para Empenho não informado.";
       $this->erro_campo = "r11_geraretencaoempenho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r11_percentualipe == null ){ 
       $this->erro_sql = " Campo Percentual IPE não informado.";
       $this->erro_campo = "r11_percentualipe";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r11_datainiciovigenciarpps == null ){ 
       $this->r11_datainiciovigenciarpps = "null";
     }
     if($this->r11_sistemacontroleponto == null ){ 
       $this->erro_msg = "Campo Tipo de Sistema de Controle de Ponto é de preenchimento obrigatório.";
       $this->erro_campo = "r11_sistemacontroleponto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r11_compararferias == null ){ 
       $this->erro_sql = " Campo Efetuar Comparativo não informado.";
       $this->erro_campo = "r11_compararferias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r11_suplementar == null ){ 
       $this->erro_sql = " Campo Suplementar não informado.";
       $this->erro_campo = "r11_suplementar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r11_anousu = $r11_anousu; 
       $this->r11_mesusu = $r11_mesusu; 
       $this->r11_instit = $r11_instit; 
     if(($this->r11_anousu == null) || ($this->r11_anousu == "") ){ 
       $this->erro_sql = " Campo r11_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r11_mesusu == null) || ($this->r11_mesusu == "") ){ 
       $this->erro_sql = " Campo r11_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r11_instit == null) || ($this->r11_instit == "") ){ 
       $this->erro_sql = " Campo r11_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cfpess(
                                       r11_instit 
                                      ,r11_anousu 
                                      ,r11_mesusu 
                                      ,r11_codaec 
                                      ,r11_natest 
                                      ,r11_cdfpas 
                                      ,r11_cdactr 
                                      ,r11_peactr 
                                      ,r11_pctemp 
                                      ,r11_pcterc 
                                      ,r11_fgts12 
                                      ,r11_cdcef 
                                      ,r11_cdfgts 
                                      ,r11_ultger 
                                      ,r11_ultfec 
                                      ,r11_arredn 
                                      ,r11_sald13 
                                      ,r11_datai 
                                      ,r11_dataf 
                                      ,r11_fecha 
                                      ,r11_ultreg 
                                      ,r11_codipe 
                                      ,r11_mes13 
                                      ,r11_tbprev 
                                      ,r11_confer 
                                      ,r11_valor 
                                      ,r11_dtipe 
                                      ,r11_implan 
                                      ,r11_subpes 
                                      ,r11_rubmat 
                                      ,r11_eleina 
                                      ,r11_elepen 
                                      ,r11_rubnat 
                                      ,r11_rubdec 
                                      ,r11_qtdcal 
                                      ,r11_palime 
                                      ,r11_altfer 
                                      ,r11_ferias 
                                      ,r11_fer13 
                                      ,r11_ferant 
                                      ,r11_fer13o 
                                      ,r11_fer13a 
                                      ,r11_ferabo 
                                      ,r11_feabot 
                                      ,r11_feradi 
                                      ,r11_fadiab 
                                      ,r11_recalc 
                                      ,r11_pagaab 
                                      ,r11_fersal 
                                      ,r11_vtprop 
                                      ,r11_desliq 
                                      ,r11_propae 
                                      ,r11_propac 
                                      ,r11_codestrut 
                                      ,r11_geracontipe 
                                      ,r11_13ferias 
                                      ,r11_pagarferias 
                                      ,r11_vtfer 
                                      ,r11_vtcons 
                                      ,r11_vtmpro 
                                      ,r11_localtrab 
                                      ,r11_databaseatra 
                                      ,r11_rubpgintegral 
                                      ,r11_conver 
                                      ,r11_concatdv 
                                      ,r11_infla 
                                      ,r11_baseipe 
                                      ,r11_txadm 
                                      ,r11_modanalitica 
                                      ,r11_viravalemes 
                                      ,r11_histslip 
                                      ,r11_mensagempadraotxt 
                                      ,r11_recpatrafasta 
                                      ,r11_relatoriocontracheque 
                                      ,r11_relatorioempenhofolha 
                                      ,r11_relatoriocomprovanterendimentos 
                                      ,r11_relatoriotermorescisao 
                                      ,r11_geraretencaoempenho 
                                      ,r11_percentualipe 
                                      ,r11_datainiciovigenciarpps 
                                      ,r11_sistemacontroleponto 
                                      ,r11_baseconsignada 
                                      ,r11_abonoprevidencia 
                                      ,r11_compararferias 
                                      ,r11_baseferias 
                                      ,r11_basesalario 
                                      ,r11_suplementar 
                                      ,r11_rubricasubstituicaoatual 
                                      ,r11_rubricasubstituicaoanterior 
                       )
                values (
                                $this->r11_instit 
                               ,$this->r11_anousu 
                               ,$this->r11_mesusu 
                               ,'$this->r11_codaec' 
                               ,'$this->r11_natest' 
                               ,$this->r11_cdfpas 
                               ,$this->r11_cdactr 
                               ,$this->r11_peactr 
                               ,$this->r11_pctemp 
                               ,$this->r11_pcterc 
                               ,$this->r11_fgts12 
                               ,'$this->r11_cdcef' 
                               ,'$this->r11_cdfgts' 
                               ,".($this->r11_ultger == "null" || $this->r11_ultger == ""?"null":"'".$this->r11_ultger."'")." 
                               ,".($this->r11_ultfec == "null" || $this->r11_ultfec == ""?"null":"'".$this->r11_ultfec."'")." 
                               ,$this->r11_arredn 
                               ,'$this->r11_sald13' 
                               ,".($this->r11_datai == "null" || $this->r11_datai == ""?"null":"'".$this->r11_datai."'")." 
                               ,".($this->r11_dataf == "null" || $this->r11_dataf == ""?"null":"'".$this->r11_dataf."'")." 
                               ,'$this->r11_fecha' 
                               ,$this->r11_ultreg 
                               ,$this->r11_codipe 
                               ,$this->r11_mes13 
                               ,$this->r11_tbprev 
                               ,'$this->r11_confer' 
                               ,$this->r11_valor 
                               ,$this->r11_dtipe 
                               ,'$this->r11_implan' 
                               ,'$this->r11_subpes' 
                               ,'$this->r11_rubmat' 
                               ,'$this->r11_eleina' 
                               ,'$this->r11_elepen' 
                               ,'$this->r11_rubnat' 
                               ,'$this->r11_rubdec' 
                               ,$this->r11_qtdcal 
                               ,'$this->r11_palime' 
                               ,'$this->r11_altfer' 
                               ,'$this->r11_ferias' 
                               ,'$this->r11_fer13' 
                               ,'$this->r11_ferant' 
                               ,'$this->r11_fer13o' 
                               ,'$this->r11_fer13a' 
                               ,'$this->r11_ferabo' 
                               ,'$this->r11_feabot' 
                               ,'$this->r11_feradi' 
                               ,'$this->r11_fadiab' 
                               ,'$this->r11_recalc' 
                               ,'$this->r11_pagaab' 
                               ,'$this->r11_fersal' 
                               ,'$this->r11_vtprop' 
                               ,'$this->r11_desliq' 
                               ,'$this->r11_propae' 
                               ,'$this->r11_propac' 
                               ,$this->r11_codestrut 
                               ,'$this->r11_geracontipe' 
                               ,'$this->r11_13ferias' 
                               ,'$this->r11_pagarferias' 
                               ,'$this->r11_vtfer' 
                               ,'$this->r11_vtcons' 
                               ,'$this->r11_vtmpro' 
                               ,$this->r11_localtrab 
                               ,".($this->r11_databaseatra == "null" || $this->r11_databaseatra == ""?"null":"'".$this->r11_databaseatra."'")." 
                               ,'$this->r11_rubpgintegral' 
                               ,'$this->r11_conver' 
                               ,'$this->r11_concatdv' 
                               ,'$this->r11_infla' 
                               ,'$this->r11_baseipe' 
                               ,$this->r11_txadm 
                               ,$this->r11_modanalitica 
                               ,'$this->r11_viravalemes' 
                               ,$this->r11_histslip 
                               ,'$this->r11_mensagempadraotxt' 
                               ,'$this->r11_recpatrafasta' 
                               ,$this->r11_relatoriocontracheque 
                               ,$this->r11_relatorioempenhofolha 
                               ,$this->r11_relatoriocomprovanterendimentos 
                               ,$this->r11_relatoriotermorescisao 
                               ,'$this->r11_geraretencaoempenho' 
                               ,$this->r11_percentualipe 
                               ,".($this->r11_datainiciovigenciarpps == "null" || $this->r11_datainiciovigenciarpps == ""?"null":"'".$this->r11_datainiciovigenciarpps."'")." 
                               ,$this->r11_sistemacontroleponto 
                               ,'$this->r11_baseconsignada' 
                               ,'$this->r11_abonoprevidencia' 
                               ,'$this->r11_compararferias' 
                               ,'$this->r11_baseferias' 
                               ,'$this->r11_basesalario' 
                               ,'$this->r11_suplementar' 
                               ,'$this->r11_rubricasubstituicaoatual' 
                               ,'$this->r11_rubricasubstituicaoanterior' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parametros de Configuracao ($this->r11_anousu."-".$this->r11_mesusu."-".$this->r11_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parametros de Configuracao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parametros de Configuracao ($this->r11_anousu."-".$this->r11_mesusu."-".$this->r11_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r11_anousu."-".$this->r11_mesusu."-".$this->r11_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->r11_anousu,$this->r11_mesusu,$this->r11_instit  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3758,'$this->r11_anousu','I')");
         $resac = db_query("insert into db_acountkey values($acount,3759,'$this->r11_mesusu','I')");
         $resac = db_query("insert into db_acountkey values($acount,9892,'$this->r11_instit','I')");
         $resac = db_query("insert into db_acount values($acount,536,9892,'','".AddSlashes(pg_result($resaco,0,'r11_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3758,'','".AddSlashes(pg_result($resaco,0,'r11_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3759,'','".AddSlashes(pg_result($resaco,0,'r11_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3760,'','".AddSlashes(pg_result($resaco,0,'r11_codaec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3761,'','".AddSlashes(pg_result($resaco,0,'r11_natest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3762,'','".AddSlashes(pg_result($resaco,0,'r11_cdfpas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3763,'','".AddSlashes(pg_result($resaco,0,'r11_cdactr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3764,'','".AddSlashes(pg_result($resaco,0,'r11_peactr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3765,'','".AddSlashes(pg_result($resaco,0,'r11_pctemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3766,'','".AddSlashes(pg_result($resaco,0,'r11_pcterc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3767,'','".AddSlashes(pg_result($resaco,0,'r11_fgts12'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3768,'','".AddSlashes(pg_result($resaco,0,'r11_cdcef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3769,'','".AddSlashes(pg_result($resaco,0,'r11_cdfgts'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3770,'','".AddSlashes(pg_result($resaco,0,'r11_ultger'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3771,'','".AddSlashes(pg_result($resaco,0,'r11_ultfec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3772,'','".AddSlashes(pg_result($resaco,0,'r11_arredn'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3773,'','".AddSlashes(pg_result($resaco,0,'r11_sald13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3774,'','".AddSlashes(pg_result($resaco,0,'r11_datai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3775,'','".AddSlashes(pg_result($resaco,0,'r11_dataf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3776,'','".AddSlashes(pg_result($resaco,0,'r11_fecha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3777,'','".AddSlashes(pg_result($resaco,0,'r11_ultreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3778,'','".AddSlashes(pg_result($resaco,0,'r11_codipe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3779,'','".AddSlashes(pg_result($resaco,0,'r11_mes13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3780,'','".AddSlashes(pg_result($resaco,0,'r11_tbprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3781,'','".AddSlashes(pg_result($resaco,0,'r11_confer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3782,'','".AddSlashes(pg_result($resaco,0,'r11_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3783,'','".AddSlashes(pg_result($resaco,0,'r11_dtipe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3784,'','".AddSlashes(pg_result($resaco,0,'r11_implan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3785,'','".AddSlashes(pg_result($resaco,0,'r11_subpes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3786,'','".AddSlashes(pg_result($resaco,0,'r11_rubmat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3787,'','".AddSlashes(pg_result($resaco,0,'r11_eleina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3788,'','".AddSlashes(pg_result($resaco,0,'r11_elepen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3789,'','".AddSlashes(pg_result($resaco,0,'r11_rubnat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3790,'','".AddSlashes(pg_result($resaco,0,'r11_rubdec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3791,'','".AddSlashes(pg_result($resaco,0,'r11_qtdcal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3792,'','".AddSlashes(pg_result($resaco,0,'r11_palime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3793,'','".AddSlashes(pg_result($resaco,0,'r11_altfer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3794,'','".AddSlashes(pg_result($resaco,0,'r11_ferias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3795,'','".AddSlashes(pg_result($resaco,0,'r11_fer13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3796,'','".AddSlashes(pg_result($resaco,0,'r11_ferant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3797,'','".AddSlashes(pg_result($resaco,0,'r11_fer13o'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3798,'','".AddSlashes(pg_result($resaco,0,'r11_fer13a'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3799,'','".AddSlashes(pg_result($resaco,0,'r11_ferabo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3800,'','".AddSlashes(pg_result($resaco,0,'r11_feabot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3801,'','".AddSlashes(pg_result($resaco,0,'r11_feradi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3802,'','".AddSlashes(pg_result($resaco,0,'r11_fadiab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3803,'','".AddSlashes(pg_result($resaco,0,'r11_recalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3804,'','".AddSlashes(pg_result($resaco,0,'r11_pagaab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,3805,'','".AddSlashes(pg_result($resaco,0,'r11_fersal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,4580,'','".AddSlashes(pg_result($resaco,0,'r11_vtprop'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,4581,'','".AddSlashes(pg_result($resaco,0,'r11_desliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,4582,'','".AddSlashes(pg_result($resaco,0,'r11_propae'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,4583,'','".AddSlashes(pg_result($resaco,0,'r11_propac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,5690,'','".AddSlashes(pg_result($resaco,0,'r11_codestrut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,8931,'','".AddSlashes(pg_result($resaco,0,'r11_geracontipe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,8930,'','".AddSlashes(pg_result($resaco,0,'r11_13ferias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,8929,'','".AddSlashes(pg_result($resaco,0,'r11_pagarferias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,8984,'','".AddSlashes(pg_result($resaco,0,'r11_vtfer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,8983,'','".AddSlashes(pg_result($resaco,0,'r11_vtcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,8982,'','".AddSlashes(pg_result($resaco,0,'r11_vtmpro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,9023,'','".AddSlashes(pg_result($resaco,0,'r11_localtrab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,9186,'','".AddSlashes(pg_result($resaco,0,'r11_databaseatra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,9437,'','".AddSlashes(pg_result($resaco,0,'r11_rubpgintegral'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,9438,'','".AddSlashes(pg_result($resaco,0,'r11_conver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,9459,'','".AddSlashes(pg_result($resaco,0,'r11_concatdv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,9484,'','".AddSlashes(pg_result($resaco,0,'r11_infla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,9571,'','".AddSlashes(pg_result($resaco,0,'r11_baseipe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,9631,'','".AddSlashes(pg_result($resaco,0,'r11_txadm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,9633,'','".AddSlashes(pg_result($resaco,0,'r11_modanalitica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,9634,'','".AddSlashes(pg_result($resaco,0,'r11_viravalemes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,14442,'','".AddSlashes(pg_result($resaco,0,'r11_histslip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,15700,'','".AddSlashes(pg_result($resaco,0,'r11_mensagempadraotxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,17102,'','".AddSlashes(pg_result($resaco,0,'r11_recpatrafasta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,18813,'','".AddSlashes(pg_result($resaco,0,'r11_relatoriocontracheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,18814,'','".AddSlashes(pg_result($resaco,0,'r11_relatorioempenhofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,18815,'','".AddSlashes(pg_result($resaco,0,'r11_relatoriocomprovanterendimentos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,18816,'','".AddSlashes(pg_result($resaco,0,'r11_relatoriotermorescisao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,19165,'','".AddSlashes(pg_result($resaco,0,'r11_geraretencaoempenho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,19283,'','".AddSlashes(pg_result($resaco,0,'r11_percentualipe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,20381,'','".AddSlashes(pg_result($resaco,0,'r11_datainiciovigenciarpps'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,20436,'','".AddSlashes(pg_result($resaco,0,'r11_sistemacontroleponto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,20695,'','".AddSlashes(pg_result($resaco,0,'r11_baseconsignada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,20737,'','".AddSlashes(pg_result($resaco,0,'r11_abonoprevidencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,20899,'','".AddSlashes(pg_result($resaco,0,'r11_compararferias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,20900,'','".AddSlashes(pg_result($resaco,0,'r11_baseferias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,20901,'','".AddSlashes(pg_result($resaco,0,'r11_basesalario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,20988,'','".AddSlashes(pg_result($resaco,0,'r11_suplementar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,21170,'','".AddSlashes(pg_result($resaco,0,'r11_rubricasubstituicaoatual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,536,21171,'','".AddSlashes(pg_result($resaco,0,'r11_rubricasubstituicaoanterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($r11_anousu=null,$r11_mesusu=null,$r11_instit=null) { 
      $this->atualizacampos();
     $sql = " update cfpess set ";
     $virgula = "";
     if(trim($this->r11_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_instit"])){ 
       $sql  .= $virgula." r11_instit = $this->r11_instit ";
       $virgula = ",";
       if(trim($this->r11_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição não informado.";
         $this->erro_campo = "r11_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_anousu"])){ 
       $sql  .= $virgula." r11_anousu = $this->r11_anousu ";
       $virgula = ",";
       if(trim($this->r11_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercício não informado.";
         $this->erro_campo = "r11_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_mesusu"])){ 
       $sql  .= $virgula." r11_mesusu = $this->r11_mesusu ";
       $virgula = ",";
       if(trim($this->r11_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês do Exercício não informado.";
         $this->erro_campo = "r11_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_codaec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_codaec"])){ 
       $sql  .= $virgula." r11_codaec = '$this->r11_codaec' ";
       $virgula = ",";
     }
     if(trim($this->r11_natest)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_natest"])){ 
       $sql  .= $virgula." r11_natest = '$this->r11_natest' ";
       $virgula = ",";
     }
     if(trim($this->r11_cdfpas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_cdfpas"])){ 
        if(trim($this->r11_cdfpas)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_cdfpas"])){ 
           $this->r11_cdfpas = "0" ; 
        } 
       $sql  .= $virgula." r11_cdfpas = $this->r11_cdfpas ";
       $virgula = ",";
     }
     if(trim($this->r11_cdactr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_cdactr"])){ 
        if(trim($this->r11_cdactr)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_cdactr"])){ 
           $this->r11_cdactr = "0" ; 
        } 
       $sql  .= $virgula." r11_cdactr = $this->r11_cdactr ";
       $virgula = ",";
     }
     if(trim($this->r11_peactr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_peactr"])){ 
        if(trim($this->r11_peactr)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_peactr"])){ 
           $this->r11_peactr = "0" ; 
        } 
       $sql  .= $virgula." r11_peactr = $this->r11_peactr ";
       $virgula = ",";
     }
     if(trim($this->r11_pctemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_pctemp"])){ 
        if(trim($this->r11_pctemp)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_pctemp"])){ 
           $this->r11_pctemp = "0" ; 
        } 
       $sql  .= $virgula." r11_pctemp = $this->r11_pctemp ";
       $virgula = ",";
     }
     if(trim($this->r11_pcterc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_pcterc"])){ 
        if(trim($this->r11_pcterc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_pcterc"])){ 
           $this->r11_pcterc = "0" ; 
        } 
       $sql  .= $virgula." r11_pcterc = $this->r11_pcterc ";
       $virgula = ",";
     }
     if(trim($this->r11_fgts12)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_fgts12"])){ 
        if(trim($this->r11_fgts12)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_fgts12"])){ 
           $this->r11_fgts12 = "0" ; 
        } 
       $sql  .= $virgula." r11_fgts12 = $this->r11_fgts12 ";
       $virgula = ",";
     }
     if(trim($this->r11_cdcef)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_cdcef"])){ 
       $sql  .= $virgula." r11_cdcef = '$this->r11_cdcef' ";
       $virgula = ",";
     }
     if(trim($this->r11_cdfgts)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_cdfgts"])){ 
       $sql  .= $virgula." r11_cdfgts = '$this->r11_cdfgts' ";
       $virgula = ",";
     }
     if(trim($this->r11_ultger)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_ultger_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r11_ultger_dia"] !="") ){ 
       $sql  .= $virgula." r11_ultger = '$this->r11_ultger' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r11_ultger_dia"])){ 
         $sql  .= $virgula." r11_ultger = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r11_ultfec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_ultfec_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r11_ultfec_dia"] !="") ){ 
       $sql  .= $virgula." r11_ultfec = '$this->r11_ultfec' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r11_ultfec_dia"])){ 
         $sql  .= $virgula." r11_ultfec = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r11_arredn)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_arredn"])){ 
        if(trim($this->r11_arredn)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_arredn"])){ 
           $this->r11_arredn = "0" ; 
        } 
       $sql  .= $virgula." r11_arredn = $this->r11_arredn ";
       $virgula = ",";
     }
     if(trim($this->r11_sald13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_sald13"])){ 
       $sql  .= $virgula." r11_sald13 = '$this->r11_sald13' ";
       $virgula = ",";
     }
     if(trim($this->r11_datai)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_datai_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r11_datai_dia"] !="") ){ 
       $sql  .= $virgula." r11_datai = '$this->r11_datai' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r11_datai_dia"])){ 
         $sql  .= $virgula." r11_datai = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r11_dataf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_dataf_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r11_dataf_dia"] !="") ){ 
       $sql  .= $virgula." r11_dataf = '$this->r11_dataf' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r11_dataf_dia"])){ 
         $sql  .= $virgula." r11_dataf = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r11_fecha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_fecha"])){ 
       $sql  .= $virgula." r11_fecha = '$this->r11_fecha' ";
       $virgula = ",";
     }
     if(trim($this->r11_ultreg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_ultreg"])){ 
        if(trim($this->r11_ultreg)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_ultreg"])){ 
           $this->r11_ultreg = "0" ; 
        } 
       $sql  .= $virgula." r11_ultreg = $this->r11_ultreg ";
       $virgula = ",";
     }
     if(trim($this->r11_codipe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_codipe"])){ 
        if(trim($this->r11_codipe)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_codipe"])){ 
           $this->r11_codipe = "0" ; 
        } 
       $sql  .= $virgula." r11_codipe = $this->r11_codipe ";
       $virgula = ",";
     }
     if(trim($this->r11_mes13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_mes13"])){ 
        if(trim($this->r11_mes13)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_mes13"])){ 
           $this->r11_mes13 = "0" ; 
        } 
       $sql  .= $virgula." r11_mes13 = $this->r11_mes13 ";
       $virgula = ",";
     }
     if(trim($this->r11_tbprev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_tbprev"])){ 
        if(trim($this->r11_tbprev)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_tbprev"])){ 
           $this->r11_tbprev = "0" ; 
        } 
       $sql  .= $virgula." r11_tbprev = $this->r11_tbprev ";
       $virgula = ",";
     }
     if(trim($this->r11_confer)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_confer"])){ 
       $sql  .= $virgula." r11_confer = '$this->r11_confer' ";
       $virgula = ",";
     }
     if(trim($this->r11_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_valor"])){ 
        if(trim($this->r11_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_valor"])){ 
           $this->r11_valor = "0" ; 
        } 
       $sql  .= $virgula." r11_valor = $this->r11_valor ";
       $virgula = ",";
     }
     if(trim($this->r11_dtipe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_dtipe"])){ 
        if(trim($this->r11_dtipe)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_dtipe"])){ 
           $this->r11_dtipe = "0" ; 
        } 
       $sql  .= $virgula." r11_dtipe = $this->r11_dtipe ";
       $virgula = ",";
     }
     if(trim($this->r11_implan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_implan"])){ 
       $sql  .= $virgula." r11_implan = '$this->r11_implan' ";
       $virgula = ",";
     }
     if(trim($this->r11_subpes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_subpes"])){ 
       $sql  .= $virgula." r11_subpes = '$this->r11_subpes' ";
       $virgula = ",";
     }
     if(trim($this->r11_rubmat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_rubmat"])){ 
       $sql  .= $virgula." r11_rubmat = '$this->r11_rubmat' ";
       $virgula = ",";
     }
     if(trim($this->r11_eleina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_eleina"])){ 
       $sql  .= $virgula." r11_eleina = '$this->r11_eleina' ";
       $virgula = ",";
     }
     if(trim($this->r11_elepen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_elepen"])){ 
       $sql  .= $virgula." r11_elepen = '$this->r11_elepen' ";
       $virgula = ",";
     }
     if(trim($this->r11_rubnat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_rubnat"])){ 
       $sql  .= $virgula." r11_rubnat = '$this->r11_rubnat' ";
       $virgula = ",";
     }
     if(trim($this->r11_rubdec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_rubdec"])){ 
       $sql  .= $virgula." r11_rubdec = '$this->r11_rubdec' ";
       $virgula = ",";
     }
     if(trim($this->r11_qtdcal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_qtdcal"])){ 
        if(trim($this->r11_qtdcal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_qtdcal"])){ 
           $this->r11_qtdcal = "0" ; 
        } 
       $sql  .= $virgula." r11_qtdcal = $this->r11_qtdcal ";
       $virgula = ",";
     }
     if(trim($this->r11_palime)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_palime"])){ 
       $sql  .= $virgula." r11_palime = '$this->r11_palime' ";
       $virgula = ",";
     }
     if(trim($this->r11_altfer)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_altfer"])){ 
       $sql  .= $virgula." r11_altfer = '$this->r11_altfer' ";
       $virgula = ",";
     }
     if(trim($this->r11_ferias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_ferias"])){ 
       $sql  .= $virgula." r11_ferias = '$this->r11_ferias' ";
       $virgula = ",";
     }
     if(trim($this->r11_fer13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_fer13"])){ 
       $sql  .= $virgula." r11_fer13 = '$this->r11_fer13' ";
       $virgula = ",";
     }
     if(trim($this->r11_ferant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_ferant"])){ 
       $sql  .= $virgula." r11_ferant = '$this->r11_ferant' ";
       $virgula = ",";
     }
     if(trim($this->r11_fer13o)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_fer13o"])){ 
       $sql  .= $virgula." r11_fer13o = '$this->r11_fer13o' ";
       $virgula = ",";
     }
     if(trim($this->r11_fer13a)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_fer13a"])){ 
       $sql  .= $virgula." r11_fer13a = '$this->r11_fer13a' ";
       $virgula = ",";
     }
     if(trim($this->r11_ferabo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_ferabo"])){ 
       $sql  .= $virgula." r11_ferabo = '$this->r11_ferabo' ";
       $virgula = ",";
     }
     if(trim($this->r11_feabot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_feabot"])){ 
       $sql  .= $virgula." r11_feabot = '$this->r11_feabot' ";
       $virgula = ",";
     }
     if(trim($this->r11_feradi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_feradi"])){ 
       $sql  .= $virgula." r11_feradi = '$this->r11_feradi' ";
       $virgula = ",";
     }
     if(trim($this->r11_fadiab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_fadiab"])){ 
       $sql  .= $virgula." r11_fadiab = '$this->r11_fadiab' ";
       $virgula = ",";
     }
     if(trim($this->r11_recalc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_recalc"])){ 
       $sql  .= $virgula." r11_recalc = '$this->r11_recalc' ";
       $virgula = ",";
     }
     if(trim($this->r11_pagaab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_pagaab"])){ 
       $sql  .= $virgula." r11_pagaab = '$this->r11_pagaab' ";
       $virgula = ",";
     }
     if(trim($this->r11_fersal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_fersal"])){ 
       $sql  .= $virgula." r11_fersal = '$this->r11_fersal' ";
       $virgula = ",";
     }
     if(trim($this->r11_vtprop)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_vtprop"])){ 
       $sql  .= $virgula." r11_vtprop = '$this->r11_vtprop' ";
       $virgula = ",";
     }
     if(trim($this->r11_desliq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_desliq"])){ 
       $sql  .= $virgula." r11_desliq = '$this->r11_desliq' ";
       $virgula = ",";
     }
     if(trim($this->r11_propae)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_propae"])){ 
       $sql  .= $virgula." r11_propae = '$this->r11_propae' ";
       $virgula = ",";
     }
     if(trim($this->r11_propac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_propac"])){ 
       $sql  .= $virgula." r11_propac = '$this->r11_propac' ";
       $virgula = ",";
     }
     if(trim($this->r11_codestrut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_codestrut"])){ 
        if(trim($this->r11_codestrut)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_codestrut"])){ 
           $this->r11_codestrut = "0" ; 
        } 
       $sql  .= $virgula." r11_codestrut = $this->r11_codestrut ";
       $virgula = ",";
     }
     if(trim($this->r11_geracontipe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_geracontipe"])){ 
       $sql  .= $virgula." r11_geracontipe = '$this->r11_geracontipe' ";
       $virgula = ",";
     }
     if(trim($this->r11_13ferias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_13ferias"])){ 
       $sql  .= $virgula." r11_13ferias = '$this->r11_13ferias' ";
       $virgula = ",";
     }
     if(trim($this->r11_pagarferias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_pagarferias"])){ 
       $sql  .= $virgula." r11_pagarferias = '$this->r11_pagarferias' ";
       $virgula = ",";
     }
     if(trim($this->r11_vtfer)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_vtfer"])){ 
       $sql  .= $virgula." r11_vtfer = '$this->r11_vtfer' ";
       $virgula = ",";
     }
     if(trim($this->r11_vtcons)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_vtcons"])){ 
       $sql  .= $virgula." r11_vtcons = '$this->r11_vtcons' ";
       $virgula = ",";
     }
     if(trim($this->r11_vtmpro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_vtmpro"])){ 
       $sql  .= $virgula." r11_vtmpro = '$this->r11_vtmpro' ";
       $virgula = ",";
     }
     if(trim($this->r11_localtrab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_localtrab"])){ 
        if(trim($this->r11_localtrab)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_localtrab"])){ 
           $this->r11_localtrab = "0" ; 
        } 
       $sql  .= $virgula." r11_localtrab = $this->r11_localtrab ";
       $virgula = ",";
     }
     if(trim($this->r11_databaseatra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_databaseatra_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r11_databaseatra_dia"] !="") ){ 
       $sql  .= $virgula." r11_databaseatra = '$this->r11_databaseatra' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r11_databaseatra_dia"])){ 
         $sql  .= $virgula." r11_databaseatra = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r11_rubpgintegral)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_rubpgintegral"])){ 
       $sql  .= $virgula." r11_rubpgintegral = '$this->r11_rubpgintegral' ";
       $virgula = ",";
     }
     if(trim($this->r11_conver)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_conver"])){ 
       $sql  .= $virgula." r11_conver = '$this->r11_conver' ";
       $virgula = ",";
     }
     if(trim($this->r11_concatdv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_concatdv"])){ 
       $sql  .= $virgula." r11_concatdv = '$this->r11_concatdv' ";
       $virgula = ",";
     }
     if(trim($this->r11_infla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_infla"])){ 
       $sql  .= $virgula." r11_infla = '$this->r11_infla' ";
       $virgula = ",";
     }
     if(trim($this->r11_baseipe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_baseipe"])){ 
       $sql  .= $virgula." r11_baseipe = '$this->r11_baseipe' ";
       $virgula = ",";
     }
     if(trim($this->r11_txadm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_txadm"])){ 
        if(trim($this->r11_txadm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_txadm"])){ 
           $this->r11_txadm = "0" ; 
        } 
       $sql  .= $virgula." r11_txadm = $this->r11_txadm ";
       $virgula = ",";
     }
     if(trim($this->r11_modanalitica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_modanalitica"])){ 
        if(trim($this->r11_modanalitica)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_modanalitica"])){ 
           $this->r11_modanalitica = "0" ; 
        } 
       $sql  .= $virgula." r11_modanalitica = $this->r11_modanalitica ";
       $virgula = ",";
     }
     if(trim($this->r11_viravalemes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_viravalemes"])){ 
       $sql  .= $virgula." r11_viravalemes = '$this->r11_viravalemes' ";
       $virgula = ",";
       if(trim($this->r11_viravalemes) == null ){ 
         $this->erro_sql = " Campo Virada Mensal de Vales não informado.";
         $this->erro_campo = "r11_viravalemes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_histslip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_histslip"])){ 
        if(trim($this->r11_histslip)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_histslip"])){ 
           $this->r11_histslip = "0" ; 
        } 
       $sql  .= $virgula." r11_histslip = $this->r11_histslip ";
       $virgula = ",";
     }
     if(trim($this->r11_mensagempadraotxt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_mensagempadraotxt"])){ 
       $sql  .= $virgula." r11_mensagempadraotxt = '$this->r11_mensagempadraotxt' ";
       $virgula = ",";
     }
     if(trim($this->r11_recpatrafasta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_recpatrafasta"])){ 
       $sql  .= $virgula." r11_recpatrafasta = '$this->r11_recpatrafasta' ";
       $virgula = ",";
     }
     if(trim($this->r11_relatoriocontracheque)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_relatoriocontracheque"])){ 
       $sql  .= $virgula." r11_relatoriocontracheque = $this->r11_relatoriocontracheque ";
       $virgula = ",";
       if(trim($this->r11_relatoriocontracheque) == null ){ 
         $this->erro_sql = " Campo Relatório contra cheque não informado.";
         $this->erro_campo = "r11_relatoriocontracheque";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_relatorioempenhofolha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_relatorioempenhofolha"])){ 
       $sql  .= $virgula." r11_relatorioempenhofolha = $this->r11_relatorioempenhofolha ";
       $virgula = ",";
       if(trim($this->r11_relatorioempenhofolha) == null ){ 
         $this->erro_sql = " Campo Empenho da folha não informado.";
         $this->erro_campo = "r11_relatorioempenhofolha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_relatoriocomprovanterendimentos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_relatoriocomprovanterendimentos"])){ 
       $sql  .= $virgula." r11_relatoriocomprovanterendimentos = $this->r11_relatoriocomprovanterendimentos ";
       $virgula = ",";
       if(trim($this->r11_relatoriocomprovanterendimentos) == null ){ 
         $this->erro_sql = " Campo Comprovante de rendimentos não informado.";
         $this->erro_campo = "r11_relatoriocomprovanterendimentos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_relatoriotermorescisao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_relatoriotermorescisao"])){ 
       $sql  .= $virgula." r11_relatoriotermorescisao = $this->r11_relatoriotermorescisao ";
       $virgula = ",";
       if(trim($this->r11_relatoriotermorescisao) == null ){ 
         $this->erro_sql = " Campo Termo de Rescisão não informado.";
         $this->erro_campo = "r11_relatoriotermorescisao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_geraretencaoempenho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_geraretencaoempenho"])){ 
       $sql  .= $virgula." r11_geraretencaoempenho = '$this->r11_geraretencaoempenho' ";
       $virgula = ",";
       if(trim($this->r11_geraretencaoempenho) == null ){ 
         $this->erro_sql = " Campo Gera Retenção para Empenho não informado.";
         $this->erro_campo = "r11_geraretencaoempenho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_percentualipe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_percentualipe"])){ 
       $sql  .= $virgula." r11_percentualipe = $this->r11_percentualipe ";
       $virgula = ",";
       if(trim($this->r11_percentualipe) == null ){ 
         $this->erro_sql = " Campo Percentual IPE não informado.";
         $this->erro_campo = "r11_percentualipe";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_datainiciovigenciarpps)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_datainiciovigenciarpps_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r11_datainiciovigenciarpps_dia"] !="") ){ 
       $sql  .= $virgula." r11_datainiciovigenciarpps = '$this->r11_datainiciovigenciarpps' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r11_datainiciovigenciarpps_dia"])){ 
         $sql  .= $virgula." r11_datainiciovigenciarpps = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r11_sistemacontroleponto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_sistemacontroleponto"])){ 
       $sql  .= $virgula." r11_sistemacontroleponto = $this->r11_sistemacontroleponto ";
       $virgula = ",";
       if(trim($this->r11_sistemacontroleponto) == null ){ 
         $this->erro_msg = "Campo Tipo de Sistema de Controle de Ponto é de preenchimento obrigatório.";
         $this->erro_campo = "r11_sistemacontroleponto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_baseconsignada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_baseconsignada"])){ 
       $sql  .= $virgula." r11_baseconsignada = '$this->r11_baseconsignada' ";
       $virgula = ",";
     }
     if(trim($this->r11_abonoprevidencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_abonoprevidencia"])){ 
       $sql  .= $virgula." r11_abonoprevidencia = '$this->r11_abonoprevidencia' ";
       $virgula = ",";
     }
     if(trim($this->r11_compararferias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_compararferias"])){ 
       $sql  .= $virgula." r11_compararferias = '$this->r11_compararferias' ";
       $virgula = ",";
       if(trim($this->r11_compararferias) == null ){ 
         $this->erro_sql = " Campo Efetuar Comparativo não informado.";
         $this->erro_campo = "r11_compararferias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_baseferias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_baseferias"])){ 
       $sql  .= $virgula." r11_baseferias = '$this->r11_baseferias' ";
       $virgula = ",";
     }
     if(trim($this->r11_basesalario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_basesalario"])){ 
       $sql  .= $virgula." r11_basesalario = '$this->r11_basesalario' ";
       $virgula = ",";
     }
     if(trim($this->r11_suplementar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_suplementar"])){ 
       $sql  .= $virgula." r11_suplementar = '$this->r11_suplementar' ";
       $virgula = ",";
       if(trim($this->r11_suplementar) == null ){ 
         $this->erro_sql = " Campo Suplementar não informado.";
         $this->erro_campo = "r11_suplementar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_rubricasubstituicaoatual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_rubricasubstituicaoatual"])){ 
       $sql  .= $virgula." r11_rubricasubstituicaoatual = '$this->r11_rubricasubstituicaoatual' ";
       $virgula = ",";
     }
     if(trim($this->r11_rubricasubstituicaoanterior)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_rubricasubstituicaoanterior"])){ 
       $sql  .= $virgula." r11_rubricasubstituicaoanterior = '$this->r11_rubricasubstituicaoanterior' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($r11_anousu!=null){
       $sql .= " r11_anousu = $this->r11_anousu";
     }
     if($r11_mesusu!=null){
       $sql .= " and  r11_mesusu = $this->r11_mesusu";
     }
     if($r11_instit!=null){
       $sql .= " and  r11_instit = $this->r11_instit";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->r11_anousu,$this->r11_mesusu,$this->r11_instit));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,3758,'$this->r11_anousu','A')");
           $resac = db_query("insert into db_acountkey values($acount,3759,'$this->r11_mesusu','A')");
           $resac = db_query("insert into db_acountkey values($acount,9892,'$this->r11_instit','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_instit"]) || $this->r11_instit != "")
             $resac = db_query("insert into db_acount values($acount,536,9892,'".AddSlashes(pg_result($resaco,$conresaco,'r11_instit'))."','$this->r11_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_anousu"]) || $this->r11_anousu != "")
             $resac = db_query("insert into db_acount values($acount,536,3758,'".AddSlashes(pg_result($resaco,$conresaco,'r11_anousu'))."','$this->r11_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_mesusu"]) || $this->r11_mesusu != "")
             $resac = db_query("insert into db_acount values($acount,536,3759,'".AddSlashes(pg_result($resaco,$conresaco,'r11_mesusu'))."','$this->r11_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_codaec"]) || $this->r11_codaec != "")
             $resac = db_query("insert into db_acount values($acount,536,3760,'".AddSlashes(pg_result($resaco,$conresaco,'r11_codaec'))."','$this->r11_codaec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_natest"]) || $this->r11_natest != "")
             $resac = db_query("insert into db_acount values($acount,536,3761,'".AddSlashes(pg_result($resaco,$conresaco,'r11_natest'))."','$this->r11_natest',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_cdfpas"]) || $this->r11_cdfpas != "")
             $resac = db_query("insert into db_acount values($acount,536,3762,'".AddSlashes(pg_result($resaco,$conresaco,'r11_cdfpas'))."','$this->r11_cdfpas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_cdactr"]) || $this->r11_cdactr != "")
             $resac = db_query("insert into db_acount values($acount,536,3763,'".AddSlashes(pg_result($resaco,$conresaco,'r11_cdactr'))."','$this->r11_cdactr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_peactr"]) || $this->r11_peactr != "")
             $resac = db_query("insert into db_acount values($acount,536,3764,'".AddSlashes(pg_result($resaco,$conresaco,'r11_peactr'))."','$this->r11_peactr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_pctemp"]) || $this->r11_pctemp != "")
             $resac = db_query("insert into db_acount values($acount,536,3765,'".AddSlashes(pg_result($resaco,$conresaco,'r11_pctemp'))."','$this->r11_pctemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_pcterc"]) || $this->r11_pcterc != "")
             $resac = db_query("insert into db_acount values($acount,536,3766,'".AddSlashes(pg_result($resaco,$conresaco,'r11_pcterc'))."','$this->r11_pcterc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_fgts12"]) || $this->r11_fgts12 != "")
             $resac = db_query("insert into db_acount values($acount,536,3767,'".AddSlashes(pg_result($resaco,$conresaco,'r11_fgts12'))."','$this->r11_fgts12',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_cdcef"]) || $this->r11_cdcef != "")
             $resac = db_query("insert into db_acount values($acount,536,3768,'".AddSlashes(pg_result($resaco,$conresaco,'r11_cdcef'))."','$this->r11_cdcef',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_cdfgts"]) || $this->r11_cdfgts != "")
             $resac = db_query("insert into db_acount values($acount,536,3769,'".AddSlashes(pg_result($resaco,$conresaco,'r11_cdfgts'))."','$this->r11_cdfgts',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_ultger"]) || $this->r11_ultger != "")
             $resac = db_query("insert into db_acount values($acount,536,3770,'".AddSlashes(pg_result($resaco,$conresaco,'r11_ultger'))."','$this->r11_ultger',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_ultfec"]) || $this->r11_ultfec != "")
             $resac = db_query("insert into db_acount values($acount,536,3771,'".AddSlashes(pg_result($resaco,$conresaco,'r11_ultfec'))."','$this->r11_ultfec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_arredn"]) || $this->r11_arredn != "")
             $resac = db_query("insert into db_acount values($acount,536,3772,'".AddSlashes(pg_result($resaco,$conresaco,'r11_arredn'))."','$this->r11_arredn',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_sald13"]) || $this->r11_sald13 != "")
             $resac = db_query("insert into db_acount values($acount,536,3773,'".AddSlashes(pg_result($resaco,$conresaco,'r11_sald13'))."','$this->r11_sald13',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_datai"]) || $this->r11_datai != "")
             $resac = db_query("insert into db_acount values($acount,536,3774,'".AddSlashes(pg_result($resaco,$conresaco,'r11_datai'))."','$this->r11_datai',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_dataf"]) || $this->r11_dataf != "")
             $resac = db_query("insert into db_acount values($acount,536,3775,'".AddSlashes(pg_result($resaco,$conresaco,'r11_dataf'))."','$this->r11_dataf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_fecha"]) || $this->r11_fecha != "")
             $resac = db_query("insert into db_acount values($acount,536,3776,'".AddSlashes(pg_result($resaco,$conresaco,'r11_fecha'))."','$this->r11_fecha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_ultreg"]) || $this->r11_ultreg != "")
             $resac = db_query("insert into db_acount values($acount,536,3777,'".AddSlashes(pg_result($resaco,$conresaco,'r11_ultreg'))."','$this->r11_ultreg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_codipe"]) || $this->r11_codipe != "")
             $resac = db_query("insert into db_acount values($acount,536,3778,'".AddSlashes(pg_result($resaco,$conresaco,'r11_codipe'))."','$this->r11_codipe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_mes13"]) || $this->r11_mes13 != "")
             $resac = db_query("insert into db_acount values($acount,536,3779,'".AddSlashes(pg_result($resaco,$conresaco,'r11_mes13'))."','$this->r11_mes13',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_tbprev"]) || $this->r11_tbprev != "")
             $resac = db_query("insert into db_acount values($acount,536,3780,'".AddSlashes(pg_result($resaco,$conresaco,'r11_tbprev'))."','$this->r11_tbprev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_confer"]) || $this->r11_confer != "")
             $resac = db_query("insert into db_acount values($acount,536,3781,'".AddSlashes(pg_result($resaco,$conresaco,'r11_confer'))."','$this->r11_confer',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_valor"]) || $this->r11_valor != "")
             $resac = db_query("insert into db_acount values($acount,536,3782,'".AddSlashes(pg_result($resaco,$conresaco,'r11_valor'))."','$this->r11_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_dtipe"]) || $this->r11_dtipe != "")
             $resac = db_query("insert into db_acount values($acount,536,3783,'".AddSlashes(pg_result($resaco,$conresaco,'r11_dtipe'))."','$this->r11_dtipe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_implan"]) || $this->r11_implan != "")
             $resac = db_query("insert into db_acount values($acount,536,3784,'".AddSlashes(pg_result($resaco,$conresaco,'r11_implan'))."','$this->r11_implan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_subpes"]) || $this->r11_subpes != "")
             $resac = db_query("insert into db_acount values($acount,536,3785,'".AddSlashes(pg_result($resaco,$conresaco,'r11_subpes'))."','$this->r11_subpes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_rubmat"]) || $this->r11_rubmat != "")
             $resac = db_query("insert into db_acount values($acount,536,3786,'".AddSlashes(pg_result($resaco,$conresaco,'r11_rubmat'))."','$this->r11_rubmat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_eleina"]) || $this->r11_eleina != "")
             $resac = db_query("insert into db_acount values($acount,536,3787,'".AddSlashes(pg_result($resaco,$conresaco,'r11_eleina'))."','$this->r11_eleina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_elepen"]) || $this->r11_elepen != "")
             $resac = db_query("insert into db_acount values($acount,536,3788,'".AddSlashes(pg_result($resaco,$conresaco,'r11_elepen'))."','$this->r11_elepen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_rubnat"]) || $this->r11_rubnat != "")
             $resac = db_query("insert into db_acount values($acount,536,3789,'".AddSlashes(pg_result($resaco,$conresaco,'r11_rubnat'))."','$this->r11_rubnat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_rubdec"]) || $this->r11_rubdec != "")
             $resac = db_query("insert into db_acount values($acount,536,3790,'".AddSlashes(pg_result($resaco,$conresaco,'r11_rubdec'))."','$this->r11_rubdec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_qtdcal"]) || $this->r11_qtdcal != "")
             $resac = db_query("insert into db_acount values($acount,536,3791,'".AddSlashes(pg_result($resaco,$conresaco,'r11_qtdcal'))."','$this->r11_qtdcal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_palime"]) || $this->r11_palime != "")
             $resac = db_query("insert into db_acount values($acount,536,3792,'".AddSlashes(pg_result($resaco,$conresaco,'r11_palime'))."','$this->r11_palime',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_altfer"]) || $this->r11_altfer != "")
             $resac = db_query("insert into db_acount values($acount,536,3793,'".AddSlashes(pg_result($resaco,$conresaco,'r11_altfer'))."','$this->r11_altfer',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_ferias"]) || $this->r11_ferias != "")
             $resac = db_query("insert into db_acount values($acount,536,3794,'".AddSlashes(pg_result($resaco,$conresaco,'r11_ferias'))."','$this->r11_ferias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_fer13"]) || $this->r11_fer13 != "")
             $resac = db_query("insert into db_acount values($acount,536,3795,'".AddSlashes(pg_result($resaco,$conresaco,'r11_fer13'))."','$this->r11_fer13',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_ferant"]) || $this->r11_ferant != "")
             $resac = db_query("insert into db_acount values($acount,536,3796,'".AddSlashes(pg_result($resaco,$conresaco,'r11_ferant'))."','$this->r11_ferant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_fer13o"]) || $this->r11_fer13o != "")
             $resac = db_query("insert into db_acount values($acount,536,3797,'".AddSlashes(pg_result($resaco,$conresaco,'r11_fer13o'))."','$this->r11_fer13o',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_fer13a"]) || $this->r11_fer13a != "")
             $resac = db_query("insert into db_acount values($acount,536,3798,'".AddSlashes(pg_result($resaco,$conresaco,'r11_fer13a'))."','$this->r11_fer13a',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_ferabo"]) || $this->r11_ferabo != "")
             $resac = db_query("insert into db_acount values($acount,536,3799,'".AddSlashes(pg_result($resaco,$conresaco,'r11_ferabo'))."','$this->r11_ferabo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_feabot"]) || $this->r11_feabot != "")
             $resac = db_query("insert into db_acount values($acount,536,3800,'".AddSlashes(pg_result($resaco,$conresaco,'r11_feabot'))."','$this->r11_feabot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_feradi"]) || $this->r11_feradi != "")
             $resac = db_query("insert into db_acount values($acount,536,3801,'".AddSlashes(pg_result($resaco,$conresaco,'r11_feradi'))."','$this->r11_feradi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_fadiab"]) || $this->r11_fadiab != "")
             $resac = db_query("insert into db_acount values($acount,536,3802,'".AddSlashes(pg_result($resaco,$conresaco,'r11_fadiab'))."','$this->r11_fadiab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_recalc"]) || $this->r11_recalc != "")
             $resac = db_query("insert into db_acount values($acount,536,3803,'".AddSlashes(pg_result($resaco,$conresaco,'r11_recalc'))."','$this->r11_recalc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_pagaab"]) || $this->r11_pagaab != "")
             $resac = db_query("insert into db_acount values($acount,536,3804,'".AddSlashes(pg_result($resaco,$conresaco,'r11_pagaab'))."','$this->r11_pagaab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_fersal"]) || $this->r11_fersal != "")
             $resac = db_query("insert into db_acount values($acount,536,3805,'".AddSlashes(pg_result($resaco,$conresaco,'r11_fersal'))."','$this->r11_fersal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_vtprop"]) || $this->r11_vtprop != "")
             $resac = db_query("insert into db_acount values($acount,536,4580,'".AddSlashes(pg_result($resaco,$conresaco,'r11_vtprop'))."','$this->r11_vtprop',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_desliq"]) || $this->r11_desliq != "")
             $resac = db_query("insert into db_acount values($acount,536,4581,'".AddSlashes(pg_result($resaco,$conresaco,'r11_desliq'))."','$this->r11_desliq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_propae"]) || $this->r11_propae != "")
             $resac = db_query("insert into db_acount values($acount,536,4582,'".AddSlashes(pg_result($resaco,$conresaco,'r11_propae'))."','$this->r11_propae',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_propac"]) || $this->r11_propac != "")
             $resac = db_query("insert into db_acount values($acount,536,4583,'".AddSlashes(pg_result($resaco,$conresaco,'r11_propac'))."','$this->r11_propac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_codestrut"]) || $this->r11_codestrut != "")
             $resac = db_query("insert into db_acount values($acount,536,5690,'".AddSlashes(pg_result($resaco,$conresaco,'r11_codestrut'))."','$this->r11_codestrut',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_geracontipe"]) || $this->r11_geracontipe != "")
             $resac = db_query("insert into db_acount values($acount,536,8931,'".AddSlashes(pg_result($resaco,$conresaco,'r11_geracontipe'))."','$this->r11_geracontipe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_13ferias"]) || $this->r11_13ferias != "")
             $resac = db_query("insert into db_acount values($acount,536,8930,'".AddSlashes(pg_result($resaco,$conresaco,'r11_13ferias'))."','$this->r11_13ferias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_pagarferias"]) || $this->r11_pagarferias != "")
             $resac = db_query("insert into db_acount values($acount,536,8929,'".AddSlashes(pg_result($resaco,$conresaco,'r11_pagarferias'))."','$this->r11_pagarferias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_vtfer"]) || $this->r11_vtfer != "")
             $resac = db_query("insert into db_acount values($acount,536,8984,'".AddSlashes(pg_result($resaco,$conresaco,'r11_vtfer'))."','$this->r11_vtfer',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_vtcons"]) || $this->r11_vtcons != "")
             $resac = db_query("insert into db_acount values($acount,536,8983,'".AddSlashes(pg_result($resaco,$conresaco,'r11_vtcons'))."','$this->r11_vtcons',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_vtmpro"]) || $this->r11_vtmpro != "")
             $resac = db_query("insert into db_acount values($acount,536,8982,'".AddSlashes(pg_result($resaco,$conresaco,'r11_vtmpro'))."','$this->r11_vtmpro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_localtrab"]) || $this->r11_localtrab != "")
             $resac = db_query("insert into db_acount values($acount,536,9023,'".AddSlashes(pg_result($resaco,$conresaco,'r11_localtrab'))."','$this->r11_localtrab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_databaseatra"]) || $this->r11_databaseatra != "")
             $resac = db_query("insert into db_acount values($acount,536,9186,'".AddSlashes(pg_result($resaco,$conresaco,'r11_databaseatra'))."','$this->r11_databaseatra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_rubpgintegral"]) || $this->r11_rubpgintegral != "")
             $resac = db_query("insert into db_acount values($acount,536,9437,'".AddSlashes(pg_result($resaco,$conresaco,'r11_rubpgintegral'))."','$this->r11_rubpgintegral',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_conver"]) || $this->r11_conver != "")
             $resac = db_query("insert into db_acount values($acount,536,9438,'".AddSlashes(pg_result($resaco,$conresaco,'r11_conver'))."','$this->r11_conver',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_concatdv"]) || $this->r11_concatdv != "")
             $resac = db_query("insert into db_acount values($acount,536,9459,'".AddSlashes(pg_result($resaco,$conresaco,'r11_concatdv'))."','$this->r11_concatdv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_infla"]) || $this->r11_infla != "")
             $resac = db_query("insert into db_acount values($acount,536,9484,'".AddSlashes(pg_result($resaco,$conresaco,'r11_infla'))."','$this->r11_infla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_baseipe"]) || $this->r11_baseipe != "")
             $resac = db_query("insert into db_acount values($acount,536,9571,'".AddSlashes(pg_result($resaco,$conresaco,'r11_baseipe'))."','$this->r11_baseipe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_txadm"]) || $this->r11_txadm != "")
             $resac = db_query("insert into db_acount values($acount,536,9631,'".AddSlashes(pg_result($resaco,$conresaco,'r11_txadm'))."','$this->r11_txadm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_modanalitica"]) || $this->r11_modanalitica != "")
             $resac = db_query("insert into db_acount values($acount,536,9633,'".AddSlashes(pg_result($resaco,$conresaco,'r11_modanalitica'))."','$this->r11_modanalitica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_viravalemes"]) || $this->r11_viravalemes != "")
             $resac = db_query("insert into db_acount values($acount,536,9634,'".AddSlashes(pg_result($resaco,$conresaco,'r11_viravalemes'))."','$this->r11_viravalemes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_histslip"]) || $this->r11_histslip != "")
             $resac = db_query("insert into db_acount values($acount,536,14442,'".AddSlashes(pg_result($resaco,$conresaco,'r11_histslip'))."','$this->r11_histslip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_mensagempadraotxt"]) || $this->r11_mensagempadraotxt != "")
             $resac = db_query("insert into db_acount values($acount,536,15700,'".AddSlashes(pg_result($resaco,$conresaco,'r11_mensagempadraotxt'))."','$this->r11_mensagempadraotxt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_recpatrafasta"]) || $this->r11_recpatrafasta != "")
             $resac = db_query("insert into db_acount values($acount,536,17102,'".AddSlashes(pg_result($resaco,$conresaco,'r11_recpatrafasta'))."','$this->r11_recpatrafasta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_relatoriocontracheque"]) || $this->r11_relatoriocontracheque != "")
             $resac = db_query("insert into db_acount values($acount,536,18813,'".AddSlashes(pg_result($resaco,$conresaco,'r11_relatoriocontracheque'))."','$this->r11_relatoriocontracheque',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_relatorioempenhofolha"]) || $this->r11_relatorioempenhofolha != "")
             $resac = db_query("insert into db_acount values($acount,536,18814,'".AddSlashes(pg_result($resaco,$conresaco,'r11_relatorioempenhofolha'))."','$this->r11_relatorioempenhofolha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_relatoriocomprovanterendimentos"]) || $this->r11_relatoriocomprovanterendimentos != "")
             $resac = db_query("insert into db_acount values($acount,536,18815,'".AddSlashes(pg_result($resaco,$conresaco,'r11_relatoriocomprovanterendimentos'))."','$this->r11_relatoriocomprovanterendimentos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_relatoriotermorescisao"]) || $this->r11_relatoriotermorescisao != "")
             $resac = db_query("insert into db_acount values($acount,536,18816,'".AddSlashes(pg_result($resaco,$conresaco,'r11_relatoriotermorescisao'))."','$this->r11_relatoriotermorescisao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_geraretencaoempenho"]) || $this->r11_geraretencaoempenho != "")
             $resac = db_query("insert into db_acount values($acount,536,19165,'".AddSlashes(pg_result($resaco,$conresaco,'r11_geraretencaoempenho'))."','$this->r11_geraretencaoempenho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_percentualipe"]) || $this->r11_percentualipe != "")
             $resac = db_query("insert into db_acount values($acount,536,19283,'".AddSlashes(pg_result($resaco,$conresaco,'r11_percentualipe'))."','$this->r11_percentualipe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_datainiciovigenciarpps"]) || $this->r11_datainiciovigenciarpps != "")
             $resac = db_query("insert into db_acount values($acount,536,20381,'".AddSlashes(pg_result($resaco,$conresaco,'r11_datainiciovigenciarpps'))."','$this->r11_datainiciovigenciarpps',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_sistemacontroleponto"]) || $this->r11_sistemacontroleponto != "")
             $resac = db_query("insert into db_acount values($acount,536,20436,'".AddSlashes(pg_result($resaco,$conresaco,'r11_sistemacontroleponto'))."','$this->r11_sistemacontroleponto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_baseconsignada"]) || $this->r11_baseconsignada != "")
             $resac = db_query("insert into db_acount values($acount,536,20695,'".AddSlashes(pg_result($resaco,$conresaco,'r11_baseconsignada'))."','$this->r11_baseconsignada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_abonoprevidencia"]) || $this->r11_abonoprevidencia != "")
             $resac = db_query("insert into db_acount values($acount,536,20737,'".AddSlashes(pg_result($resaco,$conresaco,'r11_abonoprevidencia'))."','$this->r11_abonoprevidencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_compararferias"]) || $this->r11_compararferias != "")
             $resac = db_query("insert into db_acount values($acount,536,20899,'".AddSlashes(pg_result($resaco,$conresaco,'r11_compararferias'))."','$this->r11_compararferias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_baseferias"]) || $this->r11_baseferias != "")
             $resac = db_query("insert into db_acount values($acount,536,20900,'".AddSlashes(pg_result($resaco,$conresaco,'r11_baseferias'))."','$this->r11_baseferias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_basesalario"]) || $this->r11_basesalario != "")
             $resac = db_query("insert into db_acount values($acount,536,20901,'".AddSlashes(pg_result($resaco,$conresaco,'r11_basesalario'))."','$this->r11_basesalario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_suplementar"]) || $this->r11_suplementar != "")
             $resac = db_query("insert into db_acount values($acount,536,20988,'".AddSlashes(pg_result($resaco,$conresaco,'r11_suplementar'))."','$this->r11_suplementar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_rubricasubstituicaoatual"]) || $this->r11_rubricasubstituicaoatual != "")
             $resac = db_query("insert into db_acount values($acount,536,21170,'".AddSlashes(pg_result($resaco,$conresaco,'r11_rubricasubstituicaoatual'))."','$this->r11_rubricasubstituicaoatual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_rubricasubstituicaoanterior"]) || $this->r11_rubricasubstituicaoanterior != "")
             $resac = db_query("insert into db_acount values($acount,536,21171,'".AddSlashes(pg_result($resaco,$conresaco,'r11_rubricasubstituicaoanterior'))."','$this->r11_rubricasubstituicaoanterior',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros de Configuracao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r11_anousu."-".$this->r11_mesusu."-".$this->r11_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Parametros de Configuracao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r11_anousu."-".$this->r11_mesusu."-".$this->r11_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r11_anousu."-".$this->r11_mesusu."-".$this->r11_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   /**
    * Função para alterar com cláusula where
    * @param  [type] $r11_anousu [description]
    * @param  [type] $r11_mesusu [description]
    * @param  [type] $r11_instit [description]
    * @return [type]             [description]
    */
   public function alterarWhere ($where = null, $r11_anousu=null,$r11_mesusu=null,$r11_instit=null) { 

     if($where === null){
       return false;
     }

     $this->atualizacampos();
     $sql = " update cfpess set ";
     $virgula = "";
     if(trim($this->r11_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_instit"])){ 
       $sql  .= $virgula." r11_instit = $this->r11_instit ";
       $virgula = ",";
       if(trim($this->r11_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição não informado.";
         $this->erro_campo = "r11_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_anousu"])){ 
       $sql  .= $virgula." r11_anousu = $this->r11_anousu ";
       $virgula = ",";
       if(trim($this->r11_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercício não informado.";
         $this->erro_campo = "r11_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_mesusu"])){ 
       $sql  .= $virgula." r11_mesusu = $this->r11_mesusu ";
       $virgula = ",";
       if(trim($this->r11_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês do Exercício não informado.";
         $this->erro_campo = "r11_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_codaec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_codaec"])){ 
       $sql  .= $virgula." r11_codaec = '$this->r11_codaec' ";
       $virgula = ",";
     }
     if(trim($this->r11_natest)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_natest"])){ 
       $sql  .= $virgula." r11_natest = '$this->r11_natest' ";
       $virgula = ",";
     }
     if(trim($this->r11_cdfpas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_cdfpas"])){ 
        if(trim($this->r11_cdfpas)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_cdfpas"])){ 
           $this->r11_cdfpas = "0" ; 
        } 
       $sql  .= $virgula." r11_cdfpas = $this->r11_cdfpas ";
       $virgula = ",";
     }
     if(trim($this->r11_cdactr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_cdactr"])){ 
        if(trim($this->r11_cdactr)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_cdactr"])){ 
           $this->r11_cdactr = "0" ; 
        } 
       $sql  .= $virgula." r11_cdactr = $this->r11_cdactr ";
       $virgula = ",";
     }
     if(trim($this->r11_peactr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_peactr"])){ 
        if(trim($this->r11_peactr)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_peactr"])){ 
           $this->r11_peactr = "0" ; 
        } 
       $sql  .= $virgula." r11_peactr = $this->r11_peactr ";
       $virgula = ",";
     }
     if(trim($this->r11_pctemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_pctemp"])){ 
        if(trim($this->r11_pctemp)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_pctemp"])){ 
           $this->r11_pctemp = "0" ; 
        } 
       $sql  .= $virgula." r11_pctemp = $this->r11_pctemp ";
       $virgula = ",";
     }
     if(trim($this->r11_pcterc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_pcterc"])){ 
        if(trim($this->r11_pcterc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_pcterc"])){ 
           $this->r11_pcterc = "0" ; 
        } 
       $sql  .= $virgula." r11_pcterc = $this->r11_pcterc ";
       $virgula = ",";
     }
     if(trim($this->r11_fgts12)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_fgts12"])){ 
        if(trim($this->r11_fgts12)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_fgts12"])){ 
           $this->r11_fgts12 = "0" ; 
        } 
       $sql  .= $virgula." r11_fgts12 = $this->r11_fgts12 ";
       $virgula = ",";
     }
     if(trim($this->r11_cdcef)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_cdcef"])){ 
       $sql  .= $virgula." r11_cdcef = '$this->r11_cdcef' ";
       $virgula = ",";
     }
     if(trim($this->r11_cdfgts)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_cdfgts"])){ 
       $sql  .= $virgula." r11_cdfgts = '$this->r11_cdfgts' ";
       $virgula = ",";
     }
     if(trim($this->r11_ultger)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_ultger_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r11_ultger_dia"] !="") ){ 
       $sql  .= $virgula." r11_ultger = '$this->r11_ultger' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r11_ultger_dia"])){ 
         $sql  .= $virgula." r11_ultger = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r11_ultfec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_ultfec_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r11_ultfec_dia"] !="") ){ 
       $sql  .= $virgula." r11_ultfec = '$this->r11_ultfec' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r11_ultfec_dia"])){ 
         $sql  .= $virgula." r11_ultfec = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r11_arredn)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_arredn"])){ 
        if(trim($this->r11_arredn)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_arredn"])){ 
           $this->r11_arredn = "0" ; 
        } 
       $sql  .= $virgula." r11_arredn = $this->r11_arredn ";
       $virgula = ",";
     }
     if(trim($this->r11_sald13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_sald13"])){ 
       $sql  .= $virgula." r11_sald13 = '$this->r11_sald13' ";
       $virgula = ",";
     }
     if(trim($this->r11_datai)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_datai_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r11_datai_dia"] !="") ){ 
       $sql  .= $virgula." r11_datai = '$this->r11_datai' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r11_datai_dia"])){ 
         $sql  .= $virgula." r11_datai = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r11_dataf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_dataf_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r11_dataf_dia"] !="") ){ 
       $sql  .= $virgula." r11_dataf = '$this->r11_dataf' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r11_dataf_dia"])){ 
         $sql  .= $virgula." r11_dataf = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r11_fecha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_fecha"])){ 
       $sql  .= $virgula." r11_fecha = '$this->r11_fecha' ";
       $virgula = ",";
     }
     if(trim($this->r11_ultreg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_ultreg"])){ 
        if(trim($this->r11_ultreg)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_ultreg"])){ 
           $this->r11_ultreg = "0" ; 
        } 
       $sql  .= $virgula." r11_ultreg = $this->r11_ultreg ";
       $virgula = ",";
     }
     if(trim($this->r11_codipe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_codipe"])){ 
        if(trim($this->r11_codipe)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_codipe"])){ 
           $this->r11_codipe = "0" ; 
        } 
       $sql  .= $virgula." r11_codipe = $this->r11_codipe ";
       $virgula = ",";
     }
     if(trim($this->r11_mes13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_mes13"])){ 
        if(trim($this->r11_mes13)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_mes13"])){ 
           $this->r11_mes13 = "0" ; 
        } 
       $sql  .= $virgula." r11_mes13 = $this->r11_mes13 ";
       $virgula = ",";
     }
     if(trim($this->r11_tbprev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_tbprev"])){ 
        if(trim($this->r11_tbprev)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_tbprev"])){ 
           $this->r11_tbprev = "0" ; 
        } 
       $sql  .= $virgula." r11_tbprev = $this->r11_tbprev ";
       $virgula = ",";
     }
     if(trim($this->r11_confer)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_confer"])){ 
       $sql  .= $virgula." r11_confer = '$this->r11_confer' ";
       $virgula = ",";
     }
     if(trim($this->r11_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_valor"])){ 
        if(trim($this->r11_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_valor"])){ 
           $this->r11_valor = "0" ; 
        } 
       $sql  .= $virgula." r11_valor = $this->r11_valor ";
       $virgula = ",";
     }
     if(trim($this->r11_dtipe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_dtipe"])){ 
        if(trim($this->r11_dtipe)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_dtipe"])){ 
           $this->r11_dtipe = "0" ; 
        } 
       $sql  .= $virgula." r11_dtipe = $this->r11_dtipe ";
       $virgula = ",";
     }
     if(trim($this->r11_implan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_implan"])){ 
       $sql  .= $virgula." r11_implan = '$this->r11_implan' ";
       $virgula = ",";
     }
     if(trim($this->r11_subpes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_subpes"])){ 
       $sql  .= $virgula." r11_subpes = '$this->r11_subpes' ";
       $virgula = ",";
     }
     if(trim($this->r11_rubmat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_rubmat"])){ 
       $sql  .= $virgula." r11_rubmat = '$this->r11_rubmat' ";
       $virgula = ",";
     }
     if(trim($this->r11_eleina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_eleina"])){ 
       $sql  .= $virgula." r11_eleina = '$this->r11_eleina' ";
       $virgula = ",";
     }
     if(trim($this->r11_elepen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_elepen"])){ 
       $sql  .= $virgula." r11_elepen = '$this->r11_elepen' ";
       $virgula = ",";
     }
     if(trim($this->r11_rubnat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_rubnat"])){ 
       $sql  .= $virgula." r11_rubnat = '$this->r11_rubnat' ";
       $virgula = ",";
     }
     if(trim($this->r11_rubdec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_rubdec"])){ 
       $sql  .= $virgula." r11_rubdec = '$this->r11_rubdec' ";
       $virgula = ",";
     }
     if(trim($this->r11_qtdcal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_qtdcal"])){ 
        if(trim($this->r11_qtdcal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_qtdcal"])){ 
           $this->r11_qtdcal = "0" ; 
        } 
       $sql  .= $virgula." r11_qtdcal = $this->r11_qtdcal ";
       $virgula = ",";
     }
     if(trim($this->r11_palime)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_palime"])){ 
       $sql  .= $virgula." r11_palime = '$this->r11_palime' ";
       $virgula = ",";
     }
     if(trim($this->r11_altfer)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_altfer"])){ 
       $sql  .= $virgula." r11_altfer = '$this->r11_altfer' ";
       $virgula = ",";
     }
     if(trim($this->r11_ferias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_ferias"])){ 
       $sql  .= $virgula." r11_ferias = '$this->r11_ferias' ";
       $virgula = ",";
     }
     if(trim($this->r11_fer13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_fer13"])){ 
       $sql  .= $virgula." r11_fer13 = '$this->r11_fer13' ";
       $virgula = ",";
     }
     if(trim($this->r11_ferant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_ferant"])){ 
       $sql  .= $virgula." r11_ferant = '$this->r11_ferant' ";
       $virgula = ",";
     }
     if(trim($this->r11_fer13o)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_fer13o"])){ 
       $sql  .= $virgula." r11_fer13o = '$this->r11_fer13o' ";
       $virgula = ",";
     }
     if(trim($this->r11_fer13a)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_fer13a"])){ 
       $sql  .= $virgula." r11_fer13a = '$this->r11_fer13a' ";
       $virgula = ",";
     }
     if(trim($this->r11_ferabo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_ferabo"])){ 
       $sql  .= $virgula." r11_ferabo = '$this->r11_ferabo' ";
       $virgula = ",";
     }
     if(trim($this->r11_feabot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_feabot"])){ 
       $sql  .= $virgula." r11_feabot = '$this->r11_feabot' ";
       $virgula = ",";
     }
     if(trim($this->r11_feradi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_feradi"])){ 
       $sql  .= $virgula." r11_feradi = '$this->r11_feradi' ";
       $virgula = ",";
     }
     if(trim($this->r11_fadiab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_fadiab"])){ 
       $sql  .= $virgula." r11_fadiab = '$this->r11_fadiab' ";
       $virgula = ",";
     }
     if(trim($this->r11_recalc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_recalc"])){ 
       $sql  .= $virgula." r11_recalc = '$this->r11_recalc' ";
       $virgula = ",";
     }
     if(trim($this->r11_pagaab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_pagaab"])){ 
       $sql  .= $virgula." r11_pagaab = '$this->r11_pagaab' ";
       $virgula = ",";
     }
     if(trim($this->r11_fersal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_fersal"])){ 
       $sql  .= $virgula." r11_fersal = '$this->r11_fersal' ";
       $virgula = ",";
     }
     if(trim($this->r11_vtprop)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_vtprop"])){ 
       $sql  .= $virgula." r11_vtprop = '$this->r11_vtprop' ";
       $virgula = ",";
     }
     if(trim($this->r11_desliq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_desliq"])){ 
       $sql  .= $virgula." r11_desliq = '$this->r11_desliq' ";
       $virgula = ",";
     }
     if(trim($this->r11_propae)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_propae"])){ 
       $sql  .= $virgula." r11_propae = '$this->r11_propae' ";
       $virgula = ",";
     }
     if(trim($this->r11_propac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_propac"])){ 
       $sql  .= $virgula." r11_propac = '$this->r11_propac' ";
       $virgula = ",";
     }
     if(trim($this->r11_codestrut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_codestrut"])){ 
        if(trim($this->r11_codestrut)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_codestrut"])){ 
           $this->r11_codestrut = "0" ; 
        } 
       $sql  .= $virgula." r11_codestrut = $this->r11_codestrut ";
       $virgula = ",";
     }
     if(trim($this->r11_geracontipe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_geracontipe"])){ 
       $sql  .= $virgula." r11_geracontipe = '$this->r11_geracontipe' ";
       $virgula = ",";
     }
     if(trim($this->r11_13ferias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_13ferias"])){ 
       $sql  .= $virgula." r11_13ferias = '$this->r11_13ferias' ";
       $virgula = ",";
     }
     if(trim($this->r11_pagarferias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_pagarferias"])){ 
       $sql  .= $virgula." r11_pagarferias = '$this->r11_pagarferias' ";
       $virgula = ",";
     }
     if(trim($this->r11_vtfer)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_vtfer"])){ 
       $sql  .= $virgula." r11_vtfer = '$this->r11_vtfer' ";
       $virgula = ",";
     }
     if(trim($this->r11_vtcons)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_vtcons"])){ 
       $sql  .= $virgula." r11_vtcons = '$this->r11_vtcons' ";
       $virgula = ",";
     }
     if(trim($this->r11_vtmpro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_vtmpro"])){ 
       $sql  .= $virgula." r11_vtmpro = '$this->r11_vtmpro' ";
       $virgula = ",";
     }
     if(trim($this->r11_localtrab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_localtrab"])){ 
        if(trim($this->r11_localtrab)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_localtrab"])){ 
           $this->r11_localtrab = "0" ; 
        } 
       $sql  .= $virgula." r11_localtrab = $this->r11_localtrab ";
       $virgula = ",";
     }
     if(trim($this->r11_databaseatra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_databaseatra_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r11_databaseatra_dia"] !="") ){ 
       $sql  .= $virgula." r11_databaseatra = '$this->r11_databaseatra' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r11_databaseatra_dia"])){ 
         $sql  .= $virgula." r11_databaseatra = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r11_rubpgintegral)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_rubpgintegral"])){ 
       $sql  .= $virgula." r11_rubpgintegral = '$this->r11_rubpgintegral' ";
       $virgula = ",";
     }
     if(trim($this->r11_conver)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_conver"])){ 
       $sql  .= $virgula." r11_conver = '$this->r11_conver' ";
       $virgula = ",";
     }
     if(trim($this->r11_concatdv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_concatdv"])){ 
       $sql  .= $virgula." r11_concatdv = '$this->r11_concatdv' ";
       $virgula = ",";
     }
     if(trim($this->r11_infla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_infla"])){ 
       $sql  .= $virgula." r11_infla = '$this->r11_infla' ";
       $virgula = ",";
     }
     if(trim($this->r11_baseipe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_baseipe"])){ 
       $sql  .= $virgula." r11_baseipe = '$this->r11_baseipe' ";
       $virgula = ",";
     }
     if(trim($this->r11_txadm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_txadm"])){ 
        if(trim($this->r11_txadm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_txadm"])){ 
           $this->r11_txadm = "0" ; 
        } 
       $sql  .= $virgula." r11_txadm = $this->r11_txadm ";
       $virgula = ",";
     }
     if(trim($this->r11_modanalitica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_modanalitica"])){ 
        if(trim($this->r11_modanalitica)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_modanalitica"])){ 
           $this->r11_modanalitica = "0" ; 
        } 
       $sql  .= $virgula." r11_modanalitica = $this->r11_modanalitica ";
       $virgula = ",";
     }
     if(trim($this->r11_viravalemes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_viravalemes"])){ 
       $sql  .= $virgula." r11_viravalemes = '$this->r11_viravalemes' ";
       $virgula = ",";
       if(trim($this->r11_viravalemes) == null ){ 
         $this->erro_sql = " Campo Virada Mensal de Vales não informado.";
         $this->erro_campo = "r11_viravalemes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_histslip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_histslip"])){ 
        if(trim($this->r11_histslip)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r11_histslip"])){ 
           $this->r11_histslip = "0" ; 
        } 
       $sql  .= $virgula." r11_histslip = $this->r11_histslip ";
       $virgula = ",";
     }
     if(trim($this->r11_mensagempadraotxt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_mensagempadraotxt"])){ 
       $sql  .= $virgula." r11_mensagempadraotxt = '$this->r11_mensagempadraotxt' ";
       $virgula = ",";
     }
     if(trim($this->r11_recpatrafasta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_recpatrafasta"])){ 
       $sql  .= $virgula." r11_recpatrafasta = '$this->r11_recpatrafasta' ";
       $virgula = ",";
     }
     if(trim($this->r11_relatoriocontracheque)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_relatoriocontracheque"])){ 
       $sql  .= $virgula." r11_relatoriocontracheque = $this->r11_relatoriocontracheque ";
       $virgula = ",";
       if(trim($this->r11_relatoriocontracheque) == null ){ 
         $this->erro_sql = " Campo Relatório contra cheque não informado.";
         $this->erro_campo = "r11_relatoriocontracheque";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_relatorioempenhofolha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_relatorioempenhofolha"])){ 
       $sql  .= $virgula." r11_relatorioempenhofolha = $this->r11_relatorioempenhofolha ";
       $virgula = ",";
       if(trim($this->r11_relatorioempenhofolha) == null ){ 
         $this->erro_sql = " Campo Empenho da folha não informado.";
         $this->erro_campo = "r11_relatorioempenhofolha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_relatoriocomprovanterendimentos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_relatoriocomprovanterendimentos"])){ 
       $sql  .= $virgula." r11_relatoriocomprovanterendimentos = $this->r11_relatoriocomprovanterendimentos ";
       $virgula = ",";
       if(trim($this->r11_relatoriocomprovanterendimentos) == null ){ 
         $this->erro_sql = " Campo Comprovante de rendimentos não informado.";
         $this->erro_campo = "r11_relatoriocomprovanterendimentos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_relatoriotermorescisao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_relatoriotermorescisao"])){ 
       $sql  .= $virgula." r11_relatoriotermorescisao = $this->r11_relatoriotermorescisao ";
       $virgula = ",";
       if(trim($this->r11_relatoriotermorescisao) == null ){ 
         $this->erro_sql = " Campo Termo de Rescisão não informado.";
         $this->erro_campo = "r11_relatoriotermorescisao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_geraretencaoempenho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_geraretencaoempenho"])){ 
       $sql  .= $virgula." r11_geraretencaoempenho = '$this->r11_geraretencaoempenho' ";
       $virgula = ",";
       if(trim($this->r11_geraretencaoempenho) == null ){ 
         $this->erro_sql = " Campo Gera Retenção para Empenho não informado.";
         $this->erro_campo = "r11_geraretencaoempenho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_percentualipe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_percentualipe"])){ 
       $sql  .= $virgula." r11_percentualipe = $this->r11_percentualipe ";
       $virgula = ",";
       if(trim($this->r11_percentualipe) == null ){ 
         $this->erro_sql = " Campo Percentual IPE não informado.";
         $this->erro_campo = "r11_percentualipe";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_datainiciovigenciarpps)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_datainiciovigenciarpps_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r11_datainiciovigenciarpps_dia"] !="") ){ 
       $sql  .= $virgula." r11_datainiciovigenciarpps = '$this->r11_datainiciovigenciarpps' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r11_datainiciovigenciarpps_dia"])){ 
         $sql  .= $virgula." r11_datainiciovigenciarpps = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r11_sistemacontroleponto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_sistemacontroleponto"])){ 
       $sql  .= $virgula." r11_sistemacontroleponto = $this->r11_sistemacontroleponto ";
       $virgula = ",";
       if(trim($this->r11_sistemacontroleponto) == null ){ 
         $this->erro_msg = "Campo Tipo de Sistema de Controle de Ponto é de preenchimento obrigatório.";
         $this->erro_campo = "r11_sistemacontroleponto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_baseconsignada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_baseconsignada"])){ 
       $sql  .= $virgula." r11_baseconsignada = '$this->r11_baseconsignada' ";
       $virgula = ",";
     }
     if(trim($this->r11_abonoprevidencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_abonoprevidencia"])){ 
       $sql  .= $virgula." r11_abonoprevidencia = '$this->r11_abonoprevidencia' ";
       $virgula = ",";
     }
     if(trim($this->r11_compararferias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_compararferias"])){ 
       $sql  .= $virgula." r11_compararferias = '$this->r11_compararferias' ";
       $virgula = ",";
       if(trim($this->r11_compararferias) == null ){ 
         $this->erro_sql = " Campo Efetuar Comparativo não informado.";
         $this->erro_campo = "r11_compararferias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r11_baseferias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_baseferias"])){ 
       $sql  .= $virgula." r11_baseferias = '$this->r11_baseferias' ";
       $virgula = ",";
     }
     if(trim($this->r11_basesalario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_basesalario"])){ 
       $sql  .= $virgula." r11_basesalario = '$this->r11_basesalario' ";
       $virgula = ",";
     }
     if(trim($this->r11_suplementar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r11_suplementar"])){ 
       $sql  .= $virgula." r11_suplementar = '$this->r11_suplementar' ";
       $virgula = ",";
       if(trim($this->r11_suplementar) == null ){ 
         $this->erro_sql = " Campo Suplementar não informado.";
         $this->erro_campo = "r11_suplementar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ". $where;
     if($r11_anousu!=null){
       $sql .= " and r11_anousu = $this->r11_anousu";
     }
     if($r11_mesusu!=null){
       $sql .= " and r11_mesusu = $this->r11_mesusu";
     }
     if($r11_instit!=null){
       $sql .= " and r11_instit = $this->r11_instit";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->r11_anousu,$this->r11_mesusu,$this->r11_instit));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,3758,'$this->r11_anousu','A')");
           $resac = db_query("insert into db_acountkey values($acount,3759,'$this->r11_mesusu','A')");
           $resac = db_query("insert into db_acountkey values($acount,9892,'$this->r11_instit','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_instit"]) || $this->r11_instit != "")
             $resac = db_query("insert into db_acount values($acount,536,9892,'".AddSlashes(pg_result($resaco,$conresaco,'r11_instit'))."','$this->r11_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_anousu"]) || $this->r11_anousu != "")
             $resac = db_query("insert into db_acount values($acount,536,3758,'".AddSlashes(pg_result($resaco,$conresaco,'r11_anousu'))."','$this->r11_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_mesusu"]) || $this->r11_mesusu != "")
             $resac = db_query("insert into db_acount values($acount,536,3759,'".AddSlashes(pg_result($resaco,$conresaco,'r11_mesusu'))."','$this->r11_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_codaec"]) || $this->r11_codaec != "")
             $resac = db_query("insert into db_acount values($acount,536,3760,'".AddSlashes(pg_result($resaco,$conresaco,'r11_codaec'))."','$this->r11_codaec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_natest"]) || $this->r11_natest != "")
             $resac = db_query("insert into db_acount values($acount,536,3761,'".AddSlashes(pg_result($resaco,$conresaco,'r11_natest'))."','$this->r11_natest',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_cdfpas"]) || $this->r11_cdfpas != "")
             $resac = db_query("insert into db_acount values($acount,536,3762,'".AddSlashes(pg_result($resaco,$conresaco,'r11_cdfpas'))."','$this->r11_cdfpas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_cdactr"]) || $this->r11_cdactr != "")
             $resac = db_query("insert into db_acount values($acount,536,3763,'".AddSlashes(pg_result($resaco,$conresaco,'r11_cdactr'))."','$this->r11_cdactr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_peactr"]) || $this->r11_peactr != "")
             $resac = db_query("insert into db_acount values($acount,536,3764,'".AddSlashes(pg_result($resaco,$conresaco,'r11_peactr'))."','$this->r11_peactr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_pctemp"]) || $this->r11_pctemp != "")
             $resac = db_query("insert into db_acount values($acount,536,3765,'".AddSlashes(pg_result($resaco,$conresaco,'r11_pctemp'))."','$this->r11_pctemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_pcterc"]) || $this->r11_pcterc != "")
             $resac = db_query("insert into db_acount values($acount,536,3766,'".AddSlashes(pg_result($resaco,$conresaco,'r11_pcterc'))."','$this->r11_pcterc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_fgts12"]) || $this->r11_fgts12 != "")
             $resac = db_query("insert into db_acount values($acount,536,3767,'".AddSlashes(pg_result($resaco,$conresaco,'r11_fgts12'))."','$this->r11_fgts12',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_cdcef"]) || $this->r11_cdcef != "")
             $resac = db_query("insert into db_acount values($acount,536,3768,'".AddSlashes(pg_result($resaco,$conresaco,'r11_cdcef'))."','$this->r11_cdcef',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_cdfgts"]) || $this->r11_cdfgts != "")
             $resac = db_query("insert into db_acount values($acount,536,3769,'".AddSlashes(pg_result($resaco,$conresaco,'r11_cdfgts'))."','$this->r11_cdfgts',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_ultger"]) || $this->r11_ultger != "")
             $resac = db_query("insert into db_acount values($acount,536,3770,'".AddSlashes(pg_result($resaco,$conresaco,'r11_ultger'))."','$this->r11_ultger',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_ultfec"]) || $this->r11_ultfec != "")
             $resac = db_query("insert into db_acount values($acount,536,3771,'".AddSlashes(pg_result($resaco,$conresaco,'r11_ultfec'))."','$this->r11_ultfec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_arredn"]) || $this->r11_arredn != "")
             $resac = db_query("insert into db_acount values($acount,536,3772,'".AddSlashes(pg_result($resaco,$conresaco,'r11_arredn'))."','$this->r11_arredn',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_sald13"]) || $this->r11_sald13 != "")
             $resac = db_query("insert into db_acount values($acount,536,3773,'".AddSlashes(pg_result($resaco,$conresaco,'r11_sald13'))."','$this->r11_sald13',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_datai"]) || $this->r11_datai != "")
             $resac = db_query("insert into db_acount values($acount,536,3774,'".AddSlashes(pg_result($resaco,$conresaco,'r11_datai'))."','$this->r11_datai',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_dataf"]) || $this->r11_dataf != "")
             $resac = db_query("insert into db_acount values($acount,536,3775,'".AddSlashes(pg_result($resaco,$conresaco,'r11_dataf'))."','$this->r11_dataf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_fecha"]) || $this->r11_fecha != "")
             $resac = db_query("insert into db_acount values($acount,536,3776,'".AddSlashes(pg_result($resaco,$conresaco,'r11_fecha'))."','$this->r11_fecha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_ultreg"]) || $this->r11_ultreg != "")
             $resac = db_query("insert into db_acount values($acount,536,3777,'".AddSlashes(pg_result($resaco,$conresaco,'r11_ultreg'))."','$this->r11_ultreg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_codipe"]) || $this->r11_codipe != "")
             $resac = db_query("insert into db_acount values($acount,536,3778,'".AddSlashes(pg_result($resaco,$conresaco,'r11_codipe'))."','$this->r11_codipe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_mes13"]) || $this->r11_mes13 != "")
             $resac = db_query("insert into db_acount values($acount,536,3779,'".AddSlashes(pg_result($resaco,$conresaco,'r11_mes13'))."','$this->r11_mes13',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_tbprev"]) || $this->r11_tbprev != "")
             $resac = db_query("insert into db_acount values($acount,536,3780,'".AddSlashes(pg_result($resaco,$conresaco,'r11_tbprev'))."','$this->r11_tbprev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_confer"]) || $this->r11_confer != "")
             $resac = db_query("insert into db_acount values($acount,536,3781,'".AddSlashes(pg_result($resaco,$conresaco,'r11_confer'))."','$this->r11_confer',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_valor"]) || $this->r11_valor != "")
             $resac = db_query("insert into db_acount values($acount,536,3782,'".AddSlashes(pg_result($resaco,$conresaco,'r11_valor'))."','$this->r11_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_dtipe"]) || $this->r11_dtipe != "")
             $resac = db_query("insert into db_acount values($acount,536,3783,'".AddSlashes(pg_result($resaco,$conresaco,'r11_dtipe'))."','$this->r11_dtipe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_implan"]) || $this->r11_implan != "")
             $resac = db_query("insert into db_acount values($acount,536,3784,'".AddSlashes(pg_result($resaco,$conresaco,'r11_implan'))."','$this->r11_implan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_subpes"]) || $this->r11_subpes != "")
             $resac = db_query("insert into db_acount values($acount,536,3785,'".AddSlashes(pg_result($resaco,$conresaco,'r11_subpes'))."','$this->r11_subpes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_rubmat"]) || $this->r11_rubmat != "")
             $resac = db_query("insert into db_acount values($acount,536,3786,'".AddSlashes(pg_result($resaco,$conresaco,'r11_rubmat'))."','$this->r11_rubmat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_eleina"]) || $this->r11_eleina != "")
             $resac = db_query("insert into db_acount values($acount,536,3787,'".AddSlashes(pg_result($resaco,$conresaco,'r11_eleina'))."','$this->r11_eleina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_elepen"]) || $this->r11_elepen != "")
             $resac = db_query("insert into db_acount values($acount,536,3788,'".AddSlashes(pg_result($resaco,$conresaco,'r11_elepen'))."','$this->r11_elepen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_rubnat"]) || $this->r11_rubnat != "")
             $resac = db_query("insert into db_acount values($acount,536,3789,'".AddSlashes(pg_result($resaco,$conresaco,'r11_rubnat'))."','$this->r11_rubnat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_rubdec"]) || $this->r11_rubdec != "")
             $resac = db_query("insert into db_acount values($acount,536,3790,'".AddSlashes(pg_result($resaco,$conresaco,'r11_rubdec'))."','$this->r11_rubdec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_qtdcal"]) || $this->r11_qtdcal != "")
             $resac = db_query("insert into db_acount values($acount,536,3791,'".AddSlashes(pg_result($resaco,$conresaco,'r11_qtdcal'))."','$this->r11_qtdcal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_palime"]) || $this->r11_palime != "")
             $resac = db_query("insert into db_acount values($acount,536,3792,'".AddSlashes(pg_result($resaco,$conresaco,'r11_palime'))."','$this->r11_palime',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_altfer"]) || $this->r11_altfer != "")
             $resac = db_query("insert into db_acount values($acount,536,3793,'".AddSlashes(pg_result($resaco,$conresaco,'r11_altfer'))."','$this->r11_altfer',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_ferias"]) || $this->r11_ferias != "")
             $resac = db_query("insert into db_acount values($acount,536,3794,'".AddSlashes(pg_result($resaco,$conresaco,'r11_ferias'))."','$this->r11_ferias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_fer13"]) || $this->r11_fer13 != "")
             $resac = db_query("insert into db_acount values($acount,536,3795,'".AddSlashes(pg_result($resaco,$conresaco,'r11_fer13'))."','$this->r11_fer13',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_ferant"]) || $this->r11_ferant != "")
             $resac = db_query("insert into db_acount values($acount,536,3796,'".AddSlashes(pg_result($resaco,$conresaco,'r11_ferant'))."','$this->r11_ferant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_fer13o"]) || $this->r11_fer13o != "")
             $resac = db_query("insert into db_acount values($acount,536,3797,'".AddSlashes(pg_result($resaco,$conresaco,'r11_fer13o'))."','$this->r11_fer13o',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_fer13a"]) || $this->r11_fer13a != "")
             $resac = db_query("insert into db_acount values($acount,536,3798,'".AddSlashes(pg_result($resaco,$conresaco,'r11_fer13a'))."','$this->r11_fer13a',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_ferabo"]) || $this->r11_ferabo != "")
             $resac = db_query("insert into db_acount values($acount,536,3799,'".AddSlashes(pg_result($resaco,$conresaco,'r11_ferabo'))."','$this->r11_ferabo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_feabot"]) || $this->r11_feabot != "")
             $resac = db_query("insert into db_acount values($acount,536,3800,'".AddSlashes(pg_result($resaco,$conresaco,'r11_feabot'))."','$this->r11_feabot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_feradi"]) || $this->r11_feradi != "")
             $resac = db_query("insert into db_acount values($acount,536,3801,'".AddSlashes(pg_result($resaco,$conresaco,'r11_feradi'))."','$this->r11_feradi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_fadiab"]) || $this->r11_fadiab != "")
             $resac = db_query("insert into db_acount values($acount,536,3802,'".AddSlashes(pg_result($resaco,$conresaco,'r11_fadiab'))."','$this->r11_fadiab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_recalc"]) || $this->r11_recalc != "")
             $resac = db_query("insert into db_acount values($acount,536,3803,'".AddSlashes(pg_result($resaco,$conresaco,'r11_recalc'))."','$this->r11_recalc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_pagaab"]) || $this->r11_pagaab != "")
             $resac = db_query("insert into db_acount values($acount,536,3804,'".AddSlashes(pg_result($resaco,$conresaco,'r11_pagaab'))."','$this->r11_pagaab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_fersal"]) || $this->r11_fersal != "")
             $resac = db_query("insert into db_acount values($acount,536,3805,'".AddSlashes(pg_result($resaco,$conresaco,'r11_fersal'))."','$this->r11_fersal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_vtprop"]) || $this->r11_vtprop != "")
             $resac = db_query("insert into db_acount values($acount,536,4580,'".AddSlashes(pg_result($resaco,$conresaco,'r11_vtprop'))."','$this->r11_vtprop',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_desliq"]) || $this->r11_desliq != "")
             $resac = db_query("insert into db_acount values($acount,536,4581,'".AddSlashes(pg_result($resaco,$conresaco,'r11_desliq'))."','$this->r11_desliq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_propae"]) || $this->r11_propae != "")
             $resac = db_query("insert into db_acount values($acount,536,4582,'".AddSlashes(pg_result($resaco,$conresaco,'r11_propae'))."','$this->r11_propae',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_propac"]) || $this->r11_propac != "")
             $resac = db_query("insert into db_acount values($acount,536,4583,'".AddSlashes(pg_result($resaco,$conresaco,'r11_propac'))."','$this->r11_propac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_codestrut"]) || $this->r11_codestrut != "")
             $resac = db_query("insert into db_acount values($acount,536,5690,'".AddSlashes(pg_result($resaco,$conresaco,'r11_codestrut'))."','$this->r11_codestrut',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_geracontipe"]) || $this->r11_geracontipe != "")
             $resac = db_query("insert into db_acount values($acount,536,8931,'".AddSlashes(pg_result($resaco,$conresaco,'r11_geracontipe'))."','$this->r11_geracontipe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_13ferias"]) || $this->r11_13ferias != "")
             $resac = db_query("insert into db_acount values($acount,536,8930,'".AddSlashes(pg_result($resaco,$conresaco,'r11_13ferias'))."','$this->r11_13ferias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_pagarferias"]) || $this->r11_pagarferias != "")
             $resac = db_query("insert into db_acount values($acount,536,8929,'".AddSlashes(pg_result($resaco,$conresaco,'r11_pagarferias'))."','$this->r11_pagarferias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_vtfer"]) || $this->r11_vtfer != "")
             $resac = db_query("insert into db_acount values($acount,536,8984,'".AddSlashes(pg_result($resaco,$conresaco,'r11_vtfer'))."','$this->r11_vtfer',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_vtcons"]) || $this->r11_vtcons != "")
             $resac = db_query("insert into db_acount values($acount,536,8983,'".AddSlashes(pg_result($resaco,$conresaco,'r11_vtcons'))."','$this->r11_vtcons',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_vtmpro"]) || $this->r11_vtmpro != "")
             $resac = db_query("insert into db_acount values($acount,536,8982,'".AddSlashes(pg_result($resaco,$conresaco,'r11_vtmpro'))."','$this->r11_vtmpro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_localtrab"]) || $this->r11_localtrab != "")
             $resac = db_query("insert into db_acount values($acount,536,9023,'".AddSlashes(pg_result($resaco,$conresaco,'r11_localtrab'))."','$this->r11_localtrab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_databaseatra"]) || $this->r11_databaseatra != "")
             $resac = db_query("insert into db_acount values($acount,536,9186,'".AddSlashes(pg_result($resaco,$conresaco,'r11_databaseatra'))."','$this->r11_databaseatra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_rubpgintegral"]) || $this->r11_rubpgintegral != "")
             $resac = db_query("insert into db_acount values($acount,536,9437,'".AddSlashes(pg_result($resaco,$conresaco,'r11_rubpgintegral'))."','$this->r11_rubpgintegral',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_conver"]) || $this->r11_conver != "")
             $resac = db_query("insert into db_acount values($acount,536,9438,'".AddSlashes(pg_result($resaco,$conresaco,'r11_conver'))."','$this->r11_conver',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_concatdv"]) || $this->r11_concatdv != "")
             $resac = db_query("insert into db_acount values($acount,536,9459,'".AddSlashes(pg_result($resaco,$conresaco,'r11_concatdv'))."','$this->r11_concatdv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_infla"]) || $this->r11_infla != "")
             $resac = db_query("insert into db_acount values($acount,536,9484,'".AddSlashes(pg_result($resaco,$conresaco,'r11_infla'))."','$this->r11_infla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_baseipe"]) || $this->r11_baseipe != "")
             $resac = db_query("insert into db_acount values($acount,536,9571,'".AddSlashes(pg_result($resaco,$conresaco,'r11_baseipe'))."','$this->r11_baseipe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_txadm"]) || $this->r11_txadm != "")
             $resac = db_query("insert into db_acount values($acount,536,9631,'".AddSlashes(pg_result($resaco,$conresaco,'r11_txadm'))."','$this->r11_txadm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_modanalitica"]) || $this->r11_modanalitica != "")
             $resac = db_query("insert into db_acount values($acount,536,9633,'".AddSlashes(pg_result($resaco,$conresaco,'r11_modanalitica'))."','$this->r11_modanalitica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_viravalemes"]) || $this->r11_viravalemes != "")
             $resac = db_query("insert into db_acount values($acount,536,9634,'".AddSlashes(pg_result($resaco,$conresaco,'r11_viravalemes'))."','$this->r11_viravalemes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_histslip"]) || $this->r11_histslip != "")
             $resac = db_query("insert into db_acount values($acount,536,14442,'".AddSlashes(pg_result($resaco,$conresaco,'r11_histslip'))."','$this->r11_histslip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_mensagempadraotxt"]) || $this->r11_mensagempadraotxt != "")
             $resac = db_query("insert into db_acount values($acount,536,15700,'".AddSlashes(pg_result($resaco,$conresaco,'r11_mensagempadraotxt'))."','$this->r11_mensagempadraotxt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_recpatrafasta"]) || $this->r11_recpatrafasta != "")
             $resac = db_query("insert into db_acount values($acount,536,17102,'".AddSlashes(pg_result($resaco,$conresaco,'r11_recpatrafasta'))."','$this->r11_recpatrafasta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_relatoriocontracheque"]) || $this->r11_relatoriocontracheque != "")
             $resac = db_query("insert into db_acount values($acount,536,18813,'".AddSlashes(pg_result($resaco,$conresaco,'r11_relatoriocontracheque'))."','$this->r11_relatoriocontracheque',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_relatorioempenhofolha"]) || $this->r11_relatorioempenhofolha != "")
             $resac = db_query("insert into db_acount values($acount,536,18814,'".AddSlashes(pg_result($resaco,$conresaco,'r11_relatorioempenhofolha'))."','$this->r11_relatorioempenhofolha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_relatoriocomprovanterendimentos"]) || $this->r11_relatoriocomprovanterendimentos != "")
             $resac = db_query("insert into db_acount values($acount,536,18815,'".AddSlashes(pg_result($resaco,$conresaco,'r11_relatoriocomprovanterendimentos'))."','$this->r11_relatoriocomprovanterendimentos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_relatoriotermorescisao"]) || $this->r11_relatoriotermorescisao != "")
             $resac = db_query("insert into db_acount values($acount,536,18816,'".AddSlashes(pg_result($resaco,$conresaco,'r11_relatoriotermorescisao'))."','$this->r11_relatoriotermorescisao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_geraretencaoempenho"]) || $this->r11_geraretencaoempenho != "")
             $resac = db_query("insert into db_acount values($acount,536,19165,'".AddSlashes(pg_result($resaco,$conresaco,'r11_geraretencaoempenho'))."','$this->r11_geraretencaoempenho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_percentualipe"]) || $this->r11_percentualipe != "")
             $resac = db_query("insert into db_acount values($acount,536,19283,'".AddSlashes(pg_result($resaco,$conresaco,'r11_percentualipe'))."','$this->r11_percentualipe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_datainiciovigenciarpps"]) || $this->r11_datainiciovigenciarpps != "")
             $resac = db_query("insert into db_acount values($acount,536,20381,'".AddSlashes(pg_result($resaco,$conresaco,'r11_datainiciovigenciarpps'))."','$this->r11_datainiciovigenciarpps',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_sistemacontroleponto"]) || $this->r11_sistemacontroleponto != "")
             $resac = db_query("insert into db_acount values($acount,536,20436,'".AddSlashes(pg_result($resaco,$conresaco,'r11_sistemacontroleponto'))."','$this->r11_sistemacontroleponto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_baseconsignada"]) || $this->r11_baseconsignada != "")
             $resac = db_query("insert into db_acount values($acount,536,20695,'".AddSlashes(pg_result($resaco,$conresaco,'r11_baseconsignada'))."','$this->r11_baseconsignada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_abonoprevidencia"]) || $this->r11_abonoprevidencia != "")
             $resac = db_query("insert into db_acount values($acount,536,20737,'".AddSlashes(pg_result($resaco,$conresaco,'r11_abonoprevidencia'))."','$this->r11_abonoprevidencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_compararferias"]) || $this->r11_compararferias != "")
             $resac = db_query("insert into db_acount values($acount,536,20899,'".AddSlashes(pg_result($resaco,$conresaco,'r11_compararferias'))."','$this->r11_compararferias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_baseferias"]) || $this->r11_baseferias != "")
             $resac = db_query("insert into db_acount values($acount,536,20900,'".AddSlashes(pg_result($resaco,$conresaco,'r11_baseferias'))."','$this->r11_baseferias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_basesalario"]) || $this->r11_basesalario != "")
             $resac = db_query("insert into db_acount values($acount,536,20901,'".AddSlashes(pg_result($resaco,$conresaco,'r11_basesalario'))."','$this->r11_basesalario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r11_suplementar"]) || $this->r11_suplementar != "")
             $resac = db_query("insert into db_acount values($acount,536,20988,'".AddSlashes(pg_result($resaco,$conresaco,'r11_suplementar'))."','$this->r11_suplementar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros de Configuracao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r11_anousu."-".$this->r11_mesusu."-".$this->r11_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Parametros de Configuracao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r11_anousu."-".$this->r11_mesusu."-".$this->r11_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r11_anousu."-".$this->r11_mesusu."-".$this->r11_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   }  
   // funcao para exclusao 
   public function excluir ($r11_anousu=null,$r11_mesusu=null,$r11_instit=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($r11_anousu,$r11_mesusu,$r11_instit));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,3758,'$r11_anousu','E')");
           $resac  = db_query("insert into db_acountkey values($acount,3759,'$r11_mesusu','E')");
           $resac  = db_query("insert into db_acountkey values($acount,9892,'$r11_instit','E')");
           $resac  = db_query("insert into db_acount values($acount,536,9892,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3758,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3759,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3760,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_codaec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3761,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_natest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3762,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_cdfpas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3763,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_cdactr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3764,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_peactr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3765,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_pctemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3766,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_pcterc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3767,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_fgts12'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3768,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_cdcef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3769,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_cdfgts'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3770,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_ultger'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3771,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_ultfec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3772,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_arredn'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3773,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_sald13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3774,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_datai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3775,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_dataf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3776,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_fecha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3777,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_ultreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3778,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_codipe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3779,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_mes13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3780,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_tbprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3781,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_confer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3782,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3783,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_dtipe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3784,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_implan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3785,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_subpes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3786,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_rubmat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3787,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_eleina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3788,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_elepen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3789,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_rubnat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3790,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_rubdec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3791,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_qtdcal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3792,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_palime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3793,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_altfer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3794,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_ferias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3795,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_fer13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3796,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_ferant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3797,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_fer13o'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3798,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_fer13a'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3799,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_ferabo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3800,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_feabot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3801,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_feradi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3802,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_fadiab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3803,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_recalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3804,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_pagaab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,3805,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_fersal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,4580,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_vtprop'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,4581,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_desliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,4582,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_propae'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,4583,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_propac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,5690,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_codestrut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,8931,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_geracontipe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,8930,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_13ferias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,8929,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_pagarferias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,8984,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_vtfer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,8983,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_vtcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,8982,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_vtmpro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,9023,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_localtrab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,9186,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_databaseatra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,9437,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_rubpgintegral'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,9438,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_conver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,9459,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_concatdv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,9484,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_infla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,9571,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_baseipe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,9631,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_txadm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,9633,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_modanalitica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,9634,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_viravalemes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,14442,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_histslip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,15700,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_mensagempadraotxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,17102,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_recpatrafasta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,18813,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_relatoriocontracheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,18814,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_relatorioempenhofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,18815,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_relatoriocomprovanterendimentos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,18816,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_relatoriotermorescisao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,19165,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_geraretencaoempenho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,19283,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_percentualipe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,20381,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_datainiciovigenciarpps'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,20436,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_sistemacontroleponto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,20695,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_baseconsignada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,20737,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_abonoprevidencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,20899,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_compararferias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,20900,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_baseferias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,20901,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_basesalario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,20988,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_suplementar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,21170,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_rubricasubstituicaoatual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,536,21171,'','".AddSlashes(pg_result($resaco,$iresaco,'r11_rubricasubstituicaoanterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cfpess
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($r11_anousu)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " r11_anousu = $r11_anousu ";
        }
        if (!empty($r11_mesusu)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " r11_mesusu = $r11_mesusu ";
        }
        if (!empty($r11_instit)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " r11_instit = $r11_instit ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros de Configuracao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r11_anousu."-".$r11_mesusu."-".$r11_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Parametros de Configuracao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r11_anousu."-".$r11_mesusu."-".$r11_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r11_anousu."-".$r11_mesusu."-".$r11_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   }

   /**
    * Funcao do recordset.
    * @param  String $sql
    * @return Resource
    */
   public function sql_record($sql) {

    $result = db_query($sql);
    if (!$result) {

      $this->numrows     = 0;
      $this->erro_banco  = str_replace("\n","",@pg_last_error());
      $this->erro_sql    = 'Erro ao selecionar os registros.';
      $this->erro_msg    = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = '0';

      return false;
     }

    $this->numrows = pg_num_rows($result);
    if ($this->numrows == 0) {

      $this->erro_banco  = '';
      $this->erro_sql    = 'Record Vazio na Tabela:cfpess';
      $this->erro_msg    = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = '0';

      return false;
    }

    return $result;
  }


  /**
   * Funcao que gera o SQL.
   * 
   * @param  Mixed  $r11_anousu
   * @param  Mixed  $r11_mesusu
   * @param  Mixed  $r11_instit
   * @param  String $campos
   * @param  String $ordem
   * @param  String $dbwhere
   * 
   * @return String
   */
   public function sql_query ($r11_anousu = null,$r11_mesusu = null,$r11_instit = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = 'select ';
     if ($campos != '*') {
 
       $campos_sql = split('#', $campos);
       $virgula = '';
 
       for ($i = 0; $i < sizeof($campos_sql); $i++) {
 
         $sql .= $virgula . $campos_sql[$i];
         $virgula = ', ';
       }
     } else {
       $sql .= $campos;
     }

     $sql .= ' from cfpess';
     $sql .= '      inner join db_config     on db_config.codigo            = cfpess.r11_instit';
     $sql .= '      left  join db_estrutura  on db_estrutura.db77_codestrut = cfpess.r11_codestrut';
     $sql .= '      left  join inflan        on r11_infla                   = i01_codigo';
     $sql .= '      left  join rhbases       on r11_baseipe                 = rh32_base';
     $sql .= '      left  join conhist       on r11_histslip                = c50_codhist';
     $sql .= '      inner join cgm           on cgm.z01_numcgm              = db_config.numcgm';
     $sql .= '      inner join db_tipoinstit on db_tipoinstit.db21_codtipo  = db_config.db21_tipoinstit';
     $sql2 = '';

     if (empty($dbwhere)) {

      if (!empty($r11_anousu)) {
        $sql2 .= " where cfpess.r11_anousu = {$r11_anousu} "; 
      } 

      if (!empty($r11_mesusu)) {

        if (!empty($sql2)) {
           $sql2 .= " and ";
        } else {
           $sql2 .= " where ";
        }

        $sql2 .= " cfpess.r11_mesusu = {$r11_mesusu} "; 
      } 

      if (!empty($r11_instit)) {
        if (!empty($sql2)) {
           $sql2 .= " and ";
        } else {
           $sql2 .= " where ";
        } 
        $sql2 .= " cfpess.r11_instit = {$r11_instit} "; 
      } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where {$dbwhere}";
     }

     $sql .= $sql2;

     if (!empty($ordem)) {

      $sql .= " order by ";
      $campos_sql = split('#', $ordem);
      $virgula = '';

      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql .= $virgula . $campos_sql[$i];
        $virgula = ', ';
      }
     }

     return $sql;
   }
  
   // funcao do sql 
   public function sql_query_file ($r11_anousu = null,$r11_mesusu = null,$r11_instit = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql = 'select ';
     if ($campos != '*' ) {

       $campos_sql = split('#', $campos);
       $virgula = '';

       for ($i = 0; $i < sizeof($campos_sql); $i++) {

         $sql .= $virgula.$campos_sql[$i];
         $virgula = ', ';
       }
     } else {
       $sql .= $campos;
     }

     $sql .= ' from cfpess ';
     $sql2 = '';
     if (empty($dbwhere)) {

       if (!empty($r11_anousu)) {
         $sql2 .= " where cfpess.r11_anousu = {$r11_anousu} "; 
       }

       if (!empty($r11_mesusu)) {

         if (!empty($sql2)) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         }

         $sql2 .= " cfpess.r11_mesusu = {$r11_mesusu} "; 
       }

       if (!empty($r11_instit)) {

         if (!empty($sql2)) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         }

         $sql2 .= " cfpess.r11_instit = {$r11_instit} "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }

     $sql .= $sql2;
     if (!empty($ordem)) {

       $sql .= ' order by ';
       $campos_sql = split('#', $ordem);
       $virgula = '';

       for ($i = 0; $i < sizeof($campos_sql); $i++) {

         $sql .= $virgula.$campos_sql[$i];
         $virgula = ', ';
       }
     }
     return $sql;
  }

   function atualiza_incluir (){
  	 $this->incluir($this->r11_anousu,$this->r11_mesusu);
   }

   function sql_query_rubr ( $r11_anousu=null,$r11_mesusu=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from cfpess ";
     $sql .= "      left join rhrubricas a on a.rh27_rubric = cfpess.r11_rubdec and a.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left join rhrubricas b on b.rh27_rubric = cfpess.r11_ferias and b.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left join rhrubricas c on c.rh27_rubric = cfpess.r11_fer13  and c.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left join rhrubricas d on d.rh27_rubric = cfpess.r11_ferabo and d.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left join rhrubricas e on e.rh27_rubric = cfpess.r11_feradi and e.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left join rhrubricas f on f.rh27_rubric = cfpess.r11_fadiab and f.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left join rhrubricas g on g.rh27_rubric = cfpess.r11_ferant and g.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left join rhrubricas h on h.rh27_rubric = cfpess.r11_feabot and h.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left join rhrubricas i on i.rh27_rubric = cfpess.r11_palime and i.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left join rhrubricas j on j.rh27_rubric = cfpess.r11_fer13a and j.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql2 = " where r11_instit =" . db_getsession("DB_instit");
     if($dbwhere==""){
       if($r11_anousu!=null ){
         $sql2 .= " and cfpess.r11_anousu = $r11_anousu ";
       }
       if($r11_mesusu!=null ){
         $sql2 .= " and cfpess.r11_mesusu = $r11_mesusu ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_parametro ( $r11_anousu=null,$r11_mesusu=null,$r11_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from cfpess ";
     $sql .= "      left join rhrubricas a on a.rh27_rubric = cfpess.r11_rubdec           and a.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left join rhrubricas b on b.rh27_rubric = cfpess.r11_ferias           and b.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left join rhrubricas c on c.rh27_rubric = cfpess.r11_fer13            and c.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left join rhrubricas d on d.rh27_rubric = cfpess.r11_ferabo           and d.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left join rhrubricas e on e.rh27_rubric = cfpess.r11_feradi           and e.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left join rhrubricas f on f.rh27_rubric = cfpess.r11_fadiab           and f.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left join rhrubricas g on g.rh27_rubric = cfpess.r11_ferant           and g.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left join rhrubricas h on h.rh27_rubric = cfpess.r11_feabot           and h.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left join rhrubricas i on i.rh27_rubric = cfpess.r11_palime           and i.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left join rhrubricas j on j.rh27_rubric = cfpess.r11_fer13a           and j.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left join rhrubricas l on l.rh27_rubric = cfpess.r11_abonoprevidencia and l.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left join bases k on k.r08_anousu  = cfpess.r11_anousu and k.r08_mesusu  = cfpess.r11_mesusu and k.r08_codigo = cfpess.r11_baseconsignada and k.r08_instit = cfpess.r11_instit ";
     
     $sql2 = "";
     if($dbwhere==""){
       if($r11_anousu!=null ){
         $sql2 .= " where cfpess.r11_anousu = $r11_anousu ";
       }
       if($r11_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " cfpess.r11_mesusu = $r11_mesusu ";
       }
       if($r11_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " cfpess.r11_instit = $r11_instit "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   /**
   * Busca código do tipo de relatório
   * 
   * @require db_utils.php
   * @param   string  $sTipoRelatorio - (contracheque, empenhofolha, comprovanterendimentos, termorescisao)
   * @param   integer $iAnoUsu
   * @param   integer $iMesUsu
   * @return  integer | boolean
   */   
  function buscaCodigoRelatorio($sTipoRelatorio, $iAnoUsu, $iMesUsu, $iInstituicao = 0) {

    if ( $iInstituicao == 0 ) {
      $iInstituicao = db_getsession('DB_instit');
    }
    
    switch($sTipoRelatorio) {

      case 'contracheque' :
        $sCampo = 'r11_relatoriocontracheque as codigo_relatorio';
      break;

      case 'empenhofolha' :
        $sCampo = 'r11_relatorioempenhofolha as codigo_relatorio';
      break;

      case 'comprovanterendimentos' :
        $sCampo = 'r11_relatoriocomprovanterendimentos as codigo_relatorio';
      break;

      case 'termorescisao' :
        $sCampo = 'r11_relatoriotermorescisao as codigo_relatorio';
      break;

      default:
        return false;
      break;
    }

    $sSql  = " select {$sCampo} from cfpess                              ";
    $sSql .= " where r11_anousu = {$iAnoUsu} and r11_mesusu = {$iMesUsu} ";
    $sSql .= " and r11_instit = {$iInstituicao}                          ";

    $rsRelatorio = db_query($sSql);
    
    if ( !$rsRelatorio &&  pg_num_rows($rsRelatorio) == 0 ) {
      return false;
    }

    $iModeloImpressao = db_utils::fieldsMemory($rsRelatorio, 0)->codigo_relatorio;

    return $iModeloImpressao;
  }
  
  /**
   * Retorna uma query que verifica se o parâmetro da suplementar esta ativada
   * e consulta se existe folhas de pagamentos.
   * 
   * @access public
   * @param Instituicao $oInstituicao
   * @param DBCompetencia $oCompetencia
   * @return String
   */
  public function sql_query_suplementar(Instituicao $oInstituicao, DBCompetencia $oCompetencia) {
    
    $sSql  = "select r11_suplementar::int,                                                              ";
    $sSql .= "       (select count(rh141_sequencial) from rhfolhapagamento                              ";
    $sSql .= "                                      where rh141_instit = {$oInstituicao->getCodigo()})  ";                  
    $sSql .= "        as rhfolhapagamento                                                               ";
    $sSql .= "  from cfpess                                                                             ";
    $sSql .= " where r11_anousu = {$oCompetencia->getAno()} and                                         ";
    $sSql .= "       r11_mesusu = {$oCompetencia->getMes()} and                                         ";
    $sSql .= "       r11_instit = {$oInstituicao->getCodigo()}                                          ";
    
    return $sSql;
  }

  /**
   * Válida se a instituição do servidor está apto a utilizar a nova estrutura da folha de pagamento.
   * 
   * @static
   * @access public
   * @return Boolean
   */
  public static function verificarUtilizacaoEstruturaSuplementar() {
    return db_getsession("DB_COMPLEMENTAR");
  }
  
  /**
   * Método responsável por setar na sessão a estrutura da folha de pagamento.
   * EX.: C/ Suplementar ou S/Suplementar
   * 
   * @static
   * @access public
   * @param Integer $iInstituicao
   * @throws DBException
   */
  public static function declararEstruturaFolhaPagamento($iInstituicao) {
    
    $oDaoCfPess      = new cl_cfpess();
    $sSqlSuplementar = $oDaoCfPess->sql_query_file(null, null, $iInstituicao, "distinct r11_suplementar::int");
    $rsSuplementar   = db_query($sSqlSuplementar);
    
    if (!$rsSuplementar) {
      throw new DBException("Ocorreu um erro ao declarar a estrutura da folha de pagamento.");
    }
    
    $oDadosSuplementar = db_utils::fieldsMemory($rsSuplementar, 0);
    db_putsession("DB_COMPLEMENTAR", (bool)$oDadosSuplementar->r11_suplementar);
  }
  
}
