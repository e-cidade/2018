<?
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

//MODULO: pessoal
//CLASSE DA ENTIDADE pessoal
class cl_pessoal { 
   // cria variaveis de erro 
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
   // cria variaveis do arquivo 
   var $r01_anousu = 0; 
   var $r01_mesusu = 0; 
   var $r01_numcgm = 0; 
   var $r01_regist = 0; 
   var $r01_admiss_dia = null; 
   var $r01_admiss_mes = null; 
   var $r01_admiss_ano = null; 
   var $r01_admiss = null; 
   var $r01_regime = 0; 
   var $r01_lotac = null; 
   var $r01_vincul = 0; 
   var $r01_cbo = 0; 
   var $r01_padrao = null; 
   var $r01_salari = 0; 
   var $r01_tipsal = null; 
   var $r01_folha = null; 
   var $r01_fpagto = 0; 
   var $r01_banco = null; 
   var $r01_agenc = null; 
   var $r01_contac = null; 
   var $r01_ctps = null; 
   var $r01_pis = null; 
   var $r01_fgts_dia = null; 
   var $r01_fgts_mes = null; 
   var $r01_fgts_ano = null; 
   var $r01_fgts = null; 
   var $r01_bcofgt = null; 
   var $r01_agfgts = null; 
   var $r01_ccfgts = null; 
   var $r01_hrssem = 0; 
   var $r01_situac = 0; 
   var $r01_nasc_dia = null; 
   var $r01_nasc_mes = null; 
   var $r01_nasc_ano = null; 
   var $r01_nasc = null; 
   var $r01_nacion = 0; 
   var $r01_anoche = 0; 
   var $r01_instru = 0; 
   var $r01_sexo = null; 
   var $r01_recis_dia = null; 
   var $r01_recis_mes = null; 
   var $r01_recis_ano = null; 
   var $r01_recis = null; 
   var $r01_causa = 0; 
   var $r01_ponto = 0; 
   var $r01_alim = 0; 
   var $r01_digito = null; 
   var $r01_tpvinc = null; 
   var $r01_arredn = 0; 
   var $r01_progr = null; 
   var $r01_carth = 0; 
   var $r01_rubric = null; 
   var $r01_tbprev = 0; 
   var $r01_adia13 = 0; 
   var $r01_anter_dia = null; 
   var $r01_anter_mes = null; 
   var $r01_anter_ano = null; 
   var $r01_anter = null; 
   var $r01_dtafas_dia = null; 
   var $r01_dtafas_mes = null; 
   var $r01_dtafas_ano = null; 
   var $r01_dtafas = null; 
   var $r01_ctpsuf = null; 
   var $r01_dadi13_dia = null; 
   var $r01_dadi13_mes = null; 
   var $r01_dadi13_ano = null; 
   var $r01_dadi13 = null; 
   var $r01_estciv = null; 
   var $r01_funcao = 0; 
   var $r01_trien_dia = null; 
   var $r01_trien_mes = null; 
   var $r01_trien_ano = null; 
   var $r01_trien = null; 
   var $r01_tipadm = 0; 
   var $r01_caub = null; 
   var $r01_aviso_dia = null; 
   var $r01_aviso_mes = null; 
   var $r01_aviso_ano = null; 
   var $r01_aviso = null; 
   var $r01_hrsmen = 0; 
   var $r01_rfi1_dia = null; 
   var $r01_rfi1_mes = null; 
   var $r01_rfi1_ano = null; 
   var $r01_rfi1 = null; 
   var $r01_rfi2_dia = null; 
   var $r01_rfi2_mes = null; 
   var $r01_rfi2_ano = null; 
   var $r01_rfi2 = null; 
   var $r01_rff1_dia = null; 
   var $r01_rff1_mes = null; 
   var $r01_rff1_ano = null; 
   var $r01_rff1 = null; 
   var $r01_rff2_dia = null; 
   var $r01_rff2_mes = null; 
   var $r01_rff2_ano = null; 
   var $r01_rff2 = null; 
   var $r01_rnd1 = 0; 
   var $r01_rnd2 = 0; 
   var $r01_r13i_dia = null; 
   var $r01_r13i_mes = null; 
   var $r01_r13i_ano = null; 
   var $r01_r13i = null; 
   var $r01_r13f_dia = null; 
   var $r01_r13f_mes = null; 
   var $r01_r13f_ano = null; 
   var $r01_r13f = null; 
   var $r01_rnd3 = 0; 
   var $r01_ndres = 0; 
   var $r01_ndsal = 0; 
   var $r01_prores = null; 
   var $r01_matipe = 0; 
   var $r01_dtvinc_dia = null; 
   var $r01_dtvinc_mes = null; 
   var $r01_dtvinc_ano = null; 
   var $r01_dtvinc = null; 
   var $r01_estado = null; 
   var $r01_dtalt_dia = null; 
   var $r01_dtalt_mes = null; 
   var $r01_dtalt_ano = null; 
   var $r01_dtalt = null; 
   var $r01_natura = null; 
   var $r01_tpcont = null; 
   var $r01_titele = null; 
   var $r01_zonael = null; 
   var $r01_secaoe = null; 
   var $r01_taviso = 0; 
   var $r01_cc = null; 
   var $r01_ocorre = null; 
   var $r01_basefo = 0; 
   var $r01_descfo = 0; 
   var $r01_b13fo = 0; 
   var $r01_d13fo = 0; 
   var $r01_equip = 'f'; 
   var $r01_raca = 0; 
   var $r01_mremun = 0; 
   var $r01_reserv = null; 
   var $r01_catres = null; 
   var $r01_propi = 0; 
   var $r01_cargo = 0; 
   var $r01_clas1 = null; 
   var $r01_origp = 0; 
   var $r01_clas2_dia = null; 
   var $r01_clas2_mes = null; 
   var $r01_clas2_ano = null; 
   var $r01_clas2 = null; 
   var $r01_vale = null; 
   var $r01_instit = 0; 
   var $r01_contrato = 0; 
   var $r01_depsf = 0; 
   var $r01_depirf = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r01_anousu = int4 = Ano do Exercicio 
                 r01_mesusu = int4 = Mes do Exercicio 
                 r01_numcgm = int4 = No. CGM 
                 r01_regist = int4 = Matrícula do Servidor 
                 r01_admiss = date = Admissão 
                 r01_regime = int4 = Regime 
                 r01_lotac = char(4) = Lotação 
                 r01_vincul = int4 = Vínculo 
                 r01_cbo = int4 = CBO 
                 r01_padrao = char(10) = Padrão 
                 r01_salari = float8 = Salário 
                 r01_tipsal = char(1) = Tipo de Salário 
                 r01_folha = char(1) = Tipo de Folha 
                 r01_fpagto = int4 = Forma de Pagamento 
                 r01_banco = char(3) = Banco 
                 r01_agenc = char(5) = Agência 
                 r01_contac = char(15) = Conta Corrente 
                 r01_ctps = char(12) = CTPS 
                 r01_pis = char(11) = PIS/PASEP 
                 r01_fgts = date = Opção do FGTS 
                 r01_bcofgt = char(     3) = Codigo do Bco onde paga o FGTS 
                 r01_agfgts = char(     5) = Agencia onde e Cadast. o FGTS 
                 r01_ccfgts = char(11) = Conta  do FGTS 
                 r01_hrssem = int4 = Horas Semanais 
                 r01_situac = int4 = Codigo da Tabela de Situacoes 
                 r01_nasc = date = Nascimento 
                 r01_nacion = int4 = Nacionalidade 
                 r01_anoche = int4 = Ano de Chegado func. no BRASIL 
                 r01_instru = int4 = Grau de Instrução 
                 r01_sexo = char(1) = Sexo 
                 r01_recis = date = Data da Rescisão 
                 r01_causa = int4 = Causa da Rescisao 
                 r01_ponto = int4 = Cartão Ponto 
                 r01_alim = float8 = Percentual Pensao Alimenticia 
                 r01_digito = char(     1) = Digito de Controle do registro 
                 r01_tpvinc = char(1) = Tipo de Vínculo 
                 r01_arredn = float8 = Arredondamento do ponto 
                 r01_progr = char(     1) = Codigo da Tabela de Progressao 
                 r01_carth = int8 = Cart. de Habilitação 
                 r01_rubric = char(     4) = Codigo da Rubrica do Arred. 
                 r01_tbprev = int4 = Tab.  Previdência 
                 r01_adia13 = float8 = valor adto do 13o salario 
                 r01_anter = date = Data Anterior 
                 r01_dtafas = date = data do afastamento 
                 r01_ctpsuf = char(     2) = unidade federativa da ctps 
                 r01_dadi13 = date = data do adto do 13o salario 
                 r01_estciv = char(1) = Estado Civil 
                 r01_funcao = int4 = Cargo 
                 r01_trien = date = Data Triênio 
                 r01_tipadm = int4 = tipo de admissao 
                 r01_caub = char(     2) = subdivisao da causa de rescisa 
                 r01_aviso = date = datade aviso p/celetistas 
                 r01_hrsmen = float8 = nr hrs mensais 
                 r01_rfi1 = date = inicio ferias 1o per rescisao 
                 r01_rfi2 = date = final ferias 1o per rescisao 
                 r01_rff1 = date = inicio ferias 2o per rescisao 
                 r01_rff2 = date = final ferias 2o per rescisao 
                 r01_rnd1 = float8 = nr dias ferias 1o per rescisao 
                 r01_rnd2 = float8 = nr ferias 2o per rescisao 
                 r01_r13i = date = inicio per 13o sal rescisao 
                 r01_r13f = date = final do 13o sal rescisao 
                 r01_rnd3 = float8 = nr dias 13o sal rescisao 
                 r01_ndres = float8 = nr dias saldo sal rescisao 
                 r01_ndsal = float8 = nr dias salarios/rescisao 
                 r01_prores = char(     7) = ano/mes de proc da rescisao 
                 r01_matipe = int8 = Matricula do IPE 
                 r01_dtvinc = date = Data do Vinculo com IPE 
                 r01_estado = char(     2) = Situacao do IPE 
                 r01_dtalt = date = Data da alteracao 
                 r01_natura = char(25) = Naturalidade 
                 r01_tpcont = char(2) = Tipo de Contrato 
                 r01_titele = char(14) = Título 
                 r01_zonael = char(     3) = Zona eleitoral 
                 r01_secaoe = char(     4) = Secao onde o funcionario vota. 
                 r01_taviso = int4 = Tipo de aviso previo 
                 r01_cc = char(     1) = Nr.do cc que o funcion.recebe 
                 r01_ocorre = char(     2) = cod.multiplos vinculos sefip 
                 r01_basefo = float8 = Base INSS outra empresa 
                 r01_descfo = float8 = Desconto Inss outra empresa 
                 r01_b13fo = float8 = Base 13.sal Inss outra empresa 
                 r01_d13fo = float8 = Desc.13.sal.inss outra empresa 
                 r01_equip = boolean = equiparacao salarial 
                 r01_raca = int4 = codigo raca/cor da rais 
                 r01_mremun = float8 = valor maior remun.rescisao 
                 r01_reserv = varchar(15) = C.Reservista 
                 r01_catres = varchar(4) = Categoria 
                 r01_propi = float8 = Proporção 
                 r01_cargo = int4 = Cargo 
                 r01_clas1 = varchar(5) = Opção livre 
                 r01_origp = int4 = Origem 
                 r01_clas2 = date = Opção Livre 
                 r01_vale = varchar(1) = Adiantamento 
                 r01_instit = int4 = codigo da instituicao 
                 r01_contrato = int8 = Contrato 
                 r01_depsf = int4 = Dependentes Sal.Família 
                 r01_depirf = int4 = Dependentes IRRF 
                 ";
   //funcao construtor da classe 
   function cl_pessoal() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pessoal"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
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
       $this->r01_anousu = ($this->r01_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_anousu"]:$this->r01_anousu);
       $this->r01_mesusu = ($this->r01_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_mesusu"]:$this->r01_mesusu);
       $this->r01_numcgm = ($this->r01_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_numcgm"]:$this->r01_numcgm);
       $this->r01_regist = ($this->r01_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_regist"]:$this->r01_regist);
       if($this->r01_admiss == ""){
         $this->r01_admiss_dia = ($this->r01_admiss_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_admiss_dia"]:$this->r01_admiss_dia);
         $this->r01_admiss_mes = ($this->r01_admiss_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_admiss_mes"]:$this->r01_admiss_mes);
         $this->r01_admiss_ano = ($this->r01_admiss_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_admiss_ano"]:$this->r01_admiss_ano);
         if($this->r01_admiss_dia != ""){
            $this->r01_admiss = $this->r01_admiss_ano."-".$this->r01_admiss_mes."-".$this->r01_admiss_dia;
         }
       }
       $this->r01_regime = ($this->r01_regime == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_regime"]:$this->r01_regime);
       $this->r01_lotac = ($this->r01_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_lotac"]:$this->r01_lotac);
       $this->r01_vincul = ($this->r01_vincul == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_vincul"]:$this->r01_vincul);
       $this->r01_cbo = ($this->r01_cbo == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_cbo"]:$this->r01_cbo);
       $this->r01_padrao = ($this->r01_padrao == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_padrao"]:$this->r01_padrao);
       $this->r01_salari = ($this->r01_salari == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_salari"]:$this->r01_salari);
       $this->r01_tipsal = ($this->r01_tipsal == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_tipsal"]:$this->r01_tipsal);
       $this->r01_folha = ($this->r01_folha == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_folha"]:$this->r01_folha);
       $this->r01_fpagto = ($this->r01_fpagto == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_fpagto"]:$this->r01_fpagto);
       $this->r01_banco = ($this->r01_banco == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_banco"]:$this->r01_banco);
       $this->r01_agenc = ($this->r01_agenc == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_agenc"]:$this->r01_agenc);
       $this->r01_contac = ($this->r01_contac == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_contac"]:$this->r01_contac);
       $this->r01_ctps = ($this->r01_ctps == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_ctps"]:$this->r01_ctps);
       $this->r01_pis = ($this->r01_pis == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_pis"]:$this->r01_pis);
       if($this->r01_fgts == ""){
         $this->r01_fgts_dia = ($this->r01_fgts_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_fgts_dia"]:$this->r01_fgts_dia);
         $this->r01_fgts_mes = ($this->r01_fgts_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_fgts_mes"]:$this->r01_fgts_mes);
         $this->r01_fgts_ano = ($this->r01_fgts_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_fgts_ano"]:$this->r01_fgts_ano);
         if($this->r01_fgts_dia != ""){
            $this->r01_fgts = $this->r01_fgts_ano."-".$this->r01_fgts_mes."-".$this->r01_fgts_dia;
         }
       }
       $this->r01_bcofgt = ($this->r01_bcofgt == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_bcofgt"]:$this->r01_bcofgt);
       $this->r01_agfgts = ($this->r01_agfgts == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_agfgts"]:$this->r01_agfgts);
       $this->r01_ccfgts = ($this->r01_ccfgts == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_ccfgts"]:$this->r01_ccfgts);
       $this->r01_hrssem = ($this->r01_hrssem == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_hrssem"]:$this->r01_hrssem);
       $this->r01_situac = ($this->r01_situac == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_situac"]:$this->r01_situac);
       if($this->r01_nasc == ""){
         $this->r01_nasc_dia = ($this->r01_nasc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_nasc_dia"]:$this->r01_nasc_dia);
         $this->r01_nasc_mes = ($this->r01_nasc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_nasc_mes"]:$this->r01_nasc_mes);
         $this->r01_nasc_ano = ($this->r01_nasc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_nasc_ano"]:$this->r01_nasc_ano);
         if($this->r01_nasc_dia != ""){
            $this->r01_nasc = $this->r01_nasc_ano."-".$this->r01_nasc_mes."-".$this->r01_nasc_dia;
         }
       }
       $this->r01_nacion = ($this->r01_nacion == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_nacion"]:$this->r01_nacion);
       $this->r01_anoche = ($this->r01_anoche == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_anoche"]:$this->r01_anoche);
       $this->r01_instru = ($this->r01_instru == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_instru"]:$this->r01_instru);
       $this->r01_sexo = ($this->r01_sexo == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_sexo"]:$this->r01_sexo);
       if($this->r01_recis == ""){
         $this->r01_recis_dia = ($this->r01_recis_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_recis_dia"]:$this->r01_recis_dia);
         $this->r01_recis_mes = ($this->r01_recis_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_recis_mes"]:$this->r01_recis_mes);
         $this->r01_recis_ano = ($this->r01_recis_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_recis_ano"]:$this->r01_recis_ano);
         if($this->r01_recis_dia != ""){
            $this->r01_recis = $this->r01_recis_ano."-".$this->r01_recis_mes."-".$this->r01_recis_dia;
         }
       }
       $this->r01_causa = ($this->r01_causa == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_causa"]:$this->r01_causa);
       $this->r01_ponto = ($this->r01_ponto == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_ponto"]:$this->r01_ponto);
       $this->r01_alim = ($this->r01_alim == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_alim"]:$this->r01_alim);
       $this->r01_digito = ($this->r01_digito == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_digito"]:$this->r01_digito);
       $this->r01_tpvinc = ($this->r01_tpvinc == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_tpvinc"]:$this->r01_tpvinc);
       $this->r01_arredn = ($this->r01_arredn == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_arredn"]:$this->r01_arredn);
       $this->r01_progr = ($this->r01_progr == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_progr"]:$this->r01_progr);
       $this->r01_carth = ($this->r01_carth == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_carth"]:$this->r01_carth);
       $this->r01_rubric = ($this->r01_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_rubric"]:$this->r01_rubric);
       $this->r01_tbprev = ($this->r01_tbprev == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_tbprev"]:$this->r01_tbprev);
       $this->r01_adia13 = ($this->r01_adia13 == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_adia13"]:$this->r01_adia13);
       if($this->r01_anter == ""){
         $this->r01_anter_dia = ($this->r01_anter_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_anter_dia"]:$this->r01_anter_dia);
         $this->r01_anter_mes = ($this->r01_anter_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_anter_mes"]:$this->r01_anter_mes);
         $this->r01_anter_ano = ($this->r01_anter_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_anter_ano"]:$this->r01_anter_ano);
         if($this->r01_anter_dia != ""){
            $this->r01_anter = $this->r01_anter_ano."-".$this->r01_anter_mes."-".$this->r01_anter_dia;
         }
       }
       if($this->r01_dtafas == ""){
         $this->r01_dtafas_dia = ($this->r01_dtafas_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_dtafas_dia"]:$this->r01_dtafas_dia);
         $this->r01_dtafas_mes = ($this->r01_dtafas_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_dtafas_mes"]:$this->r01_dtafas_mes);
         $this->r01_dtafas_ano = ($this->r01_dtafas_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_dtafas_ano"]:$this->r01_dtafas_ano);
         if($this->r01_dtafas_dia != ""){
            $this->r01_dtafas = $this->r01_dtafas_ano."-".$this->r01_dtafas_mes."-".$this->r01_dtafas_dia;
         }
       }
       $this->r01_ctpsuf = ($this->r01_ctpsuf == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_ctpsuf"]:$this->r01_ctpsuf);
       if($this->r01_dadi13 == ""){
         $this->r01_dadi13_dia = ($this->r01_dadi13_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_dadi13_dia"]:$this->r01_dadi13_dia);
         $this->r01_dadi13_mes = ($this->r01_dadi13_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_dadi13_mes"]:$this->r01_dadi13_mes);
         $this->r01_dadi13_ano = ($this->r01_dadi13_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_dadi13_ano"]:$this->r01_dadi13_ano);
         if($this->r01_dadi13_dia != ""){
            $this->r01_dadi13 = $this->r01_dadi13_ano."-".$this->r01_dadi13_mes."-".$this->r01_dadi13_dia;
         }
       }
       $this->r01_estciv = ($this->r01_estciv == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_estciv"]:$this->r01_estciv);
       $this->r01_funcao = ($this->r01_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_funcao"]:$this->r01_funcao);
       if($this->r01_trien == ""){
         $this->r01_trien_dia = ($this->r01_trien_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_trien_dia"]:$this->r01_trien_dia);
         $this->r01_trien_mes = ($this->r01_trien_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_trien_mes"]:$this->r01_trien_mes);
         $this->r01_trien_ano = ($this->r01_trien_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_trien_ano"]:$this->r01_trien_ano);
         if($this->r01_trien_dia != ""){
            $this->r01_trien = $this->r01_trien_ano."-".$this->r01_trien_mes."-".$this->r01_trien_dia;
         }
       }
       $this->r01_tipadm = ($this->r01_tipadm == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_tipadm"]:$this->r01_tipadm);
       $this->r01_caub = ($this->r01_caub == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_caub"]:$this->r01_caub);
       if($this->r01_aviso == ""){
         $this->r01_aviso_dia = ($this->r01_aviso_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_aviso_dia"]:$this->r01_aviso_dia);
         $this->r01_aviso_mes = ($this->r01_aviso_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_aviso_mes"]:$this->r01_aviso_mes);
         $this->r01_aviso_ano = ($this->r01_aviso_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_aviso_ano"]:$this->r01_aviso_ano);
         if($this->r01_aviso_dia != ""){
            $this->r01_aviso = $this->r01_aviso_ano."-".$this->r01_aviso_mes."-".$this->r01_aviso_dia;
         }
       }
       $this->r01_hrsmen = ($this->r01_hrsmen == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_hrsmen"]:$this->r01_hrsmen);
       if($this->r01_rfi1 == ""){
         $this->r01_rfi1_dia = ($this->r01_rfi1_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_rfi1_dia"]:$this->r01_rfi1_dia);
         $this->r01_rfi1_mes = ($this->r01_rfi1_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_rfi1_mes"]:$this->r01_rfi1_mes);
         $this->r01_rfi1_ano = ($this->r01_rfi1_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_rfi1_ano"]:$this->r01_rfi1_ano);
         if($this->r01_rfi1_dia != ""){
            $this->r01_rfi1 = $this->r01_rfi1_ano."-".$this->r01_rfi1_mes."-".$this->r01_rfi1_dia;
         }
       }
       if($this->r01_rfi2 == ""){
         $this->r01_rfi2_dia = ($this->r01_rfi2_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_rfi2_dia"]:$this->r01_rfi2_dia);
         $this->r01_rfi2_mes = ($this->r01_rfi2_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_rfi2_mes"]:$this->r01_rfi2_mes);
         $this->r01_rfi2_ano = ($this->r01_rfi2_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_rfi2_ano"]:$this->r01_rfi2_ano);
         if($this->r01_rfi2_dia != ""){
            $this->r01_rfi2 = $this->r01_rfi2_ano."-".$this->r01_rfi2_mes."-".$this->r01_rfi2_dia;
         }
       }
       if($this->r01_rff1 == ""){
         $this->r01_rff1_dia = ($this->r01_rff1_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_rff1_dia"]:$this->r01_rff1_dia);
         $this->r01_rff1_mes = ($this->r01_rff1_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_rff1_mes"]:$this->r01_rff1_mes);
         $this->r01_rff1_ano = ($this->r01_rff1_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_rff1_ano"]:$this->r01_rff1_ano);
         if($this->r01_rff1_dia != ""){
            $this->r01_rff1 = $this->r01_rff1_ano."-".$this->r01_rff1_mes."-".$this->r01_rff1_dia;
         }
       }
       if($this->r01_rff2 == ""){
         $this->r01_rff2_dia = ($this->r01_rff2_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_rff2_dia"]:$this->r01_rff2_dia);
         $this->r01_rff2_mes = ($this->r01_rff2_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_rff2_mes"]:$this->r01_rff2_mes);
         $this->r01_rff2_ano = ($this->r01_rff2_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_rff2_ano"]:$this->r01_rff2_ano);
         if($this->r01_rff2_dia != ""){
            $this->r01_rff2 = $this->r01_rff2_ano."-".$this->r01_rff2_mes."-".$this->r01_rff2_dia;
         }
       }
       $this->r01_rnd1 = ($this->r01_rnd1 == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_rnd1"]:$this->r01_rnd1);
       $this->r01_rnd2 = ($this->r01_rnd2 == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_rnd2"]:$this->r01_rnd2);
       if($this->r01_r13i == ""){
         $this->r01_r13i_dia = ($this->r01_r13i_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_r13i_dia"]:$this->r01_r13i_dia);
         $this->r01_r13i_mes = ($this->r01_r13i_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_r13i_mes"]:$this->r01_r13i_mes);
         $this->r01_r13i_ano = ($this->r01_r13i_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_r13i_ano"]:$this->r01_r13i_ano);
         if($this->r01_r13i_dia != ""){
            $this->r01_r13i = $this->r01_r13i_ano."-".$this->r01_r13i_mes."-".$this->r01_r13i_dia;
         }
       }
       if($this->r01_r13f == ""){
         $this->r01_r13f_dia = ($this->r01_r13f_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_r13f_dia"]:$this->r01_r13f_dia);
         $this->r01_r13f_mes = ($this->r01_r13f_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_r13f_mes"]:$this->r01_r13f_mes);
         $this->r01_r13f_ano = ($this->r01_r13f_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_r13f_ano"]:$this->r01_r13f_ano);
         if($this->r01_r13f_dia != ""){
            $this->r01_r13f = $this->r01_r13f_ano."-".$this->r01_r13f_mes."-".$this->r01_r13f_dia;
         }
       }
       $this->r01_rnd3 = ($this->r01_rnd3 == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_rnd3"]:$this->r01_rnd3);
       $this->r01_ndres = ($this->r01_ndres == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_ndres"]:$this->r01_ndres);
       $this->r01_ndsal = ($this->r01_ndsal == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_ndsal"]:$this->r01_ndsal);
       $this->r01_prores = ($this->r01_prores == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_prores"]:$this->r01_prores);
       $this->r01_matipe = ($this->r01_matipe == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_matipe"]:$this->r01_matipe);
       if($this->r01_dtvinc == ""){
         $this->r01_dtvinc_dia = ($this->r01_dtvinc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_dtvinc_dia"]:$this->r01_dtvinc_dia);
         $this->r01_dtvinc_mes = ($this->r01_dtvinc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_dtvinc_mes"]:$this->r01_dtvinc_mes);
         $this->r01_dtvinc_ano = ($this->r01_dtvinc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_dtvinc_ano"]:$this->r01_dtvinc_ano);
         if($this->r01_dtvinc_dia != ""){
            $this->r01_dtvinc = $this->r01_dtvinc_ano."-".$this->r01_dtvinc_mes."-".$this->r01_dtvinc_dia;
         }
       }
       $this->r01_estado = ($this->r01_estado == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_estado"]:$this->r01_estado);
       if($this->r01_dtalt == ""){
         $this->r01_dtalt_dia = ($this->r01_dtalt_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_dtalt_dia"]:$this->r01_dtalt_dia);
         $this->r01_dtalt_mes = ($this->r01_dtalt_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_dtalt_mes"]:$this->r01_dtalt_mes);
         $this->r01_dtalt_ano = ($this->r01_dtalt_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_dtalt_ano"]:$this->r01_dtalt_ano);
         if($this->r01_dtalt_dia != ""){
            $this->r01_dtalt = $this->r01_dtalt_ano."-".$this->r01_dtalt_mes."-".$this->r01_dtalt_dia;
         }
       }
       $this->r01_natura = ($this->r01_natura == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_natura"]:$this->r01_natura);
       $this->r01_tpcont = ($this->r01_tpcont == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_tpcont"]:$this->r01_tpcont);
       $this->r01_titele = ($this->r01_titele == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_titele"]:$this->r01_titele);
       $this->r01_zonael = ($this->r01_zonael == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_zonael"]:$this->r01_zonael);
       $this->r01_secaoe = ($this->r01_secaoe == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_secaoe"]:$this->r01_secaoe);
       $this->r01_taviso = ($this->r01_taviso == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_taviso"]:$this->r01_taviso);
       $this->r01_cc = ($this->r01_cc == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_cc"]:$this->r01_cc);
       $this->r01_ocorre = ($this->r01_ocorre == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_ocorre"]:$this->r01_ocorre);
       $this->r01_basefo = ($this->r01_basefo == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_basefo"]:$this->r01_basefo);
       $this->r01_descfo = ($this->r01_descfo == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_descfo"]:$this->r01_descfo);
       $this->r01_b13fo = ($this->r01_b13fo == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_b13fo"]:$this->r01_b13fo);
       $this->r01_d13fo = ($this->r01_d13fo == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_d13fo"]:$this->r01_d13fo);
       $this->r01_equip = ($this->r01_equip == "f"?@$GLOBALS["HTTP_POST_VARS"]["r01_equip"]:$this->r01_equip);
       $this->r01_raca = ($this->r01_raca == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_raca"]:$this->r01_raca);
       $this->r01_mremun = ($this->r01_mremun == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_mremun"]:$this->r01_mremun);
       $this->r01_reserv = ($this->r01_reserv == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_reserv"]:$this->r01_reserv);
       $this->r01_catres = ($this->r01_catres == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_catres"]:$this->r01_catres);
       $this->r01_propi = ($this->r01_propi == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_propi"]:$this->r01_propi);
       $this->r01_cargo = ($this->r01_cargo == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_cargo"]:$this->r01_cargo);
       $this->r01_clas1 = ($this->r01_clas1 == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_clas1"]:$this->r01_clas1);
       $this->r01_origp = ($this->r01_origp == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_origp"]:$this->r01_origp);
       if($this->r01_clas2 == ""){
         $this->r01_clas2_dia = ($this->r01_clas2_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_clas2_dia"]:$this->r01_clas2_dia);
         $this->r01_clas2_mes = ($this->r01_clas2_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_clas2_mes"]:$this->r01_clas2_mes);
         $this->r01_clas2_ano = ($this->r01_clas2_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_clas2_ano"]:$this->r01_clas2_ano);
         if($this->r01_clas2_dia != ""){
            $this->r01_clas2 = $this->r01_clas2_ano."-".$this->r01_clas2_mes."-".$this->r01_clas2_dia;
         }
       }
       $this->r01_vale = ($this->r01_vale == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_vale"]:$this->r01_vale);
       $this->r01_instit = ($this->r01_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_instit"]:$this->r01_instit);
       $this->r01_contrato = ($this->r01_contrato == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_contrato"]:$this->r01_contrato);
       $this->r01_depsf = ($this->r01_depsf == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_depsf"]:$this->r01_depsf);
       $this->r01_depirf = ($this->r01_depirf == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_depirf"]:$this->r01_depirf);
     }else{
       $this->r01_anousu = ($this->r01_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_anousu"]:$this->r01_anousu);
       $this->r01_mesusu = ($this->r01_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_mesusu"]:$this->r01_mesusu);
       $this->r01_regist = ($this->r01_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r01_regist"]:$this->r01_regist);
     }
   }
   // funcao para inclusao
   function incluir ($r01_anousu,$r01_mesusu,$r01_regist){ 
      $this->atualizacampos();
     if($this->r01_numcgm == null ){ 
       $this->erro_sql = " Campo No. CGM nao Informado.";
       $this->erro_campo = "r01_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r01_admiss == null ){ 
       $this->erro_sql = " Campo Admissão nao Informado.";
       $this->erro_campo = "r01_admiss_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r01_regime == null ){ 
       $this->erro_sql = " Campo Regime nao Informado.";
       $this->erro_campo = "r01_regime";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r01_lotac == null ){ 
       $this->erro_sql = " Campo Lotação nao Informado.";
       $this->erro_campo = "r01_lotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r01_vincul == null ){ 
       $this->erro_sql = " Campo Vínculo nao Informado.";
       $this->erro_campo = "r01_vincul";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r01_cbo == null ){ 
       $this->r01_cbo = "0";
     }
     if($this->r01_salari == null ){ 
       $this->r01_salari = "0";
     }
     if($this->r01_fpagto == null ){ 
       $this->r01_fpagto = "0";
     }
     if($this->r01_fgts == null ){ 
       $this->r01_fgts = "null";
     }
     if($this->r01_nasc == null ){ 
       $this->r01_nasc = "null";
     }
     if($this->r01_nacion == null ){ 
       $this->r01_nacion = "0";
     }
     if($this->r01_instru == null ){ 
       $this->r01_instru = "0";
     }
     if($this->r01_recis == null ){ 
       $this->r01_recis = "null";
     }
     if($this->r01_ponto == null ){ 
       $this->r01_ponto = "0";
     }
     if($this->r01_carth == null ){ 
       $this->r01_carth = "0";
     }
     if($this->r01_tbprev == null ){ 
       $this->r01_tbprev = "0";
     }
     if($this->r01_anter == null ){ 
       $this->r01_anter = "null";
     }
     if($this->r01_funcao == null ){ 
       $this->r01_funcao = "0";
     }
     if($this->r01_trien == null ){ 
       $this->r01_trien = "null";
     }
     if($this->r01_propi == null ){ 
       $this->r01_propi = "0";
     }
     if($this->r01_cargo == null ){ 
       $this->r01_cargo = "0";
     }
     if($this->r01_origp == null ){ 
       $this->r01_origp = "0";
     }
     if($this->r01_clas2 == null ){ 
       $this->r01_clas2 = "null";
     }
     if($this->r01_vale == null ){ 
       $this->erro_sql = " Campo Adiantamento nao Informado.";
       $this->erro_campo = "r01_vale";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r01_instit == null ){ 
       $this->r01_instit = "0";
     }
     if($this->r01_contrato == null ){ 
       $this->erro_sql = " Campo Contrato nao Informado.";
       $this->erro_campo = "r01_contrato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r01_depsf == null ){ 
       $this->erro_sql = " Campo Dependentes Sal.Família nao Informado.";
       $this->erro_campo = "r01_depsf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r01_depirf == null ){ 
       $this->erro_sql = " Campo Dependentes IRRF nao Informado.";
       $this->erro_campo = "r01_depirf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r01_anousu = $r01_anousu; 
       $this->r01_mesusu = $r01_mesusu; 
       $this->r01_regist = $r01_regist; 
     if(($this->r01_anousu == null) || ($this->r01_anousu == "") ){ 
       $this->erro_sql = " Campo r01_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r01_mesusu == null) || ($this->r01_mesusu == "") ){ 
       $this->erro_sql = " Campo r01_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r01_regist == null) || ($this->r01_regist == "") ){ 
       $this->erro_sql = " Campo r01_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pessoal(
                                       r01_anousu 
                                      ,r01_mesusu 
                                      ,r01_numcgm 
                                      ,r01_regist 
                                      ,r01_admiss 
                                      ,r01_regime 
                                      ,r01_lotac 
                                      ,r01_vincul 
                                      ,r01_cbo 
                                      ,r01_padrao 
                                      ,r01_salari 
                                      ,r01_tipsal 
                                      ,r01_folha 
                                      ,r01_fpagto 
                                      ,r01_banco 
                                      ,r01_agenc 
                                      ,r01_contac 
                                      ,r01_ctps 
                                      ,r01_pis 
                                      ,r01_fgts 
                                      ,r01_bcofgt 
                                      ,r01_agfgts 
                                      ,r01_ccfgts 
                                      ,r01_hrssem 
                                      ,r01_situac 
                                      ,r01_nasc 
                                      ,r01_nacion 
                                      ,r01_anoche 
                                      ,r01_instru 
                                      ,r01_sexo 
                                      ,r01_recis 
                                      ,r01_causa 
                                      ,r01_ponto 
                                      ,r01_alim 
                                      ,r01_digito 
                                      ,r01_tpvinc 
                                      ,r01_arredn 
                                      ,r01_progr 
                                      ,r01_carth 
                                      ,r01_rubric 
                                      ,r01_tbprev 
                                      ,r01_adia13 
                                      ,r01_anter 
                                      ,r01_dtafas 
                                      ,r01_ctpsuf 
                                      ,r01_dadi13 
                                      ,r01_estciv 
                                      ,r01_funcao 
                                      ,r01_trien 
                                      ,r01_tipadm 
                                      ,r01_caub 
                                      ,r01_aviso 
                                      ,r01_hrsmen 
                                      ,r01_rfi1 
                                      ,r01_rfi2 
                                      ,r01_rff1 
                                      ,r01_rff2 
                                      ,r01_rnd1 
                                      ,r01_rnd2 
                                      ,r01_r13i 
                                      ,r01_r13f 
                                      ,r01_rnd3 
                                      ,r01_ndres 
                                      ,r01_ndsal 
                                      ,r01_prores 
                                      ,r01_matipe 
                                      ,r01_dtvinc 
                                      ,r01_estado 
                                      ,r01_dtalt 
                                      ,r01_natura 
                                      ,r01_tpcont 
                                      ,r01_titele 
                                      ,r01_zonael 
                                      ,r01_secaoe 
                                      ,r01_taviso 
                                      ,r01_cc 
                                      ,r01_ocorre 
                                      ,r01_basefo 
                                      ,r01_descfo 
                                      ,r01_b13fo 
                                      ,r01_d13fo 
                                      ,r01_equip 
                                      ,r01_raca 
                                      ,r01_mremun 
                                      ,r01_reserv 
                                      ,r01_catres 
                                      ,r01_propi 
                                      ,r01_cargo 
                                      ,r01_clas1 
                                      ,r01_origp 
                                      ,r01_clas2 
                                      ,r01_vale 
                                      ,r01_instit 
                                      ,r01_contrato 
                                      ,r01_depsf 
                                      ,r01_depirf 
                       )
                values (
                                $this->r01_anousu 
                               ,$this->r01_mesusu 
                               ,$this->r01_numcgm 
                               ,$this->r01_regist 
                               ,".($this->r01_admiss == "null" || $this->r01_admiss == ""?"null":"'".$this->r01_admiss."'")." 
                               ,$this->r01_regime 
                               ,'$this->r01_lotac' 
                               ,$this->r01_vincul 
                               ,$this->r01_cbo 
                               ,'$this->r01_padrao' 
                               ,$this->r01_salari 
                               ,'$this->r01_tipsal' 
                               ,'$this->r01_folha' 
                               ,$this->r01_fpagto 
                               ,'$this->r01_banco' 
                               ,'$this->r01_agenc' 
                               ,'$this->r01_contac' 
                               ,'$this->r01_ctps' 
                               ,'$this->r01_pis' 
                               ,".($this->r01_fgts == "null" || $this->r01_fgts == ""?"null":"'".$this->r01_fgts."'")." 
                               ,'$this->r01_bcofgt' 
                               ,'$this->r01_agfgts' 
                               ,'$this->r01_ccfgts' 
                               ,$this->r01_hrssem 
                               ,$this->r01_situac 
                               ,".($this->r01_nasc == "null" || $this->r01_nasc == ""?"null":"'".$this->r01_nasc."'")." 
                               ,$this->r01_nacion 
                               ,$this->r01_anoche 
                               ,$this->r01_instru 
                               ,'$this->r01_sexo' 
                               ,".($this->r01_recis == "null" || $this->r01_recis == ""?"null":"'".$this->r01_recis."'")." 
                               ,$this->r01_causa 
                               ,$this->r01_ponto 
                               ,$this->r01_alim 
                               ,'$this->r01_digito' 
                               ,'$this->r01_tpvinc' 
                               ,$this->r01_arredn 
                               ,'$this->r01_progr' 
                               ,$this->r01_carth 
                               ,'$this->r01_rubric' 
                               ,$this->r01_tbprev 
                               ,$this->r01_adia13 
                               ,".($this->r01_anter == "null" || $this->r01_anter == ""?"null":"'".$this->r01_anter."'")." 
                               ,".($this->r01_dtafas == "null" || $this->r01_dtafas == ""?"null":"'".$this->r01_dtafas."'")." 
                               ,'$this->r01_ctpsuf' 
                               ,".($this->r01_dadi13 == "null" || $this->r01_dadi13 == ""?"null":"'".$this->r01_dadi13."'")." 
                               ,'$this->r01_estciv' 
                               ,$this->r01_funcao 
                               ,".($this->r01_trien == "null" || $this->r01_trien == ""?"null":"'".$this->r01_trien."'")." 
                               ,$this->r01_tipadm 
                               ,'$this->r01_caub' 
                               ,".($this->r01_aviso == "null" || $this->r01_aviso == ""?"null":"'".$this->r01_aviso."'")." 
                               ,$this->r01_hrsmen 
                               ,".($this->r01_rfi1 == "null" || $this->r01_rfi1 == ""?"null":"'".$this->r01_rfi1."'")." 
                               ,".($this->r01_rfi2 == "null" || $this->r01_rfi2 == ""?"null":"'".$this->r01_rfi2."'")." 
                               ,".($this->r01_rff1 == "null" || $this->r01_rff1 == ""?"null":"'".$this->r01_rff1."'")." 
                               ,".($this->r01_rff2 == "null" || $this->r01_rff2 == ""?"null":"'".$this->r01_rff2."'")." 
                               ,$this->r01_rnd1 
                               ,$this->r01_rnd2 
                               ,".($this->r01_r13i == "null" || $this->r01_r13i == ""?"null":"'".$this->r01_r13i."'")." 
                               ,".($this->r01_r13f == "null" || $this->r01_r13f == ""?"null":"'".$this->r01_r13f."'")." 
                               ,$this->r01_rnd3 
                               ,$this->r01_ndres 
                               ,$this->r01_ndsal 
                               ,'$this->r01_prores' 
                               ,$this->r01_matipe 
                               ,".($this->r01_dtvinc == "null" || $this->r01_dtvinc == ""?"null":"'".$this->r01_dtvinc."'")." 
                               ,'$this->r01_estado' 
                               ,".($this->r01_dtalt == "null" || $this->r01_dtalt == ""?"null":"'".$this->r01_dtalt."'")." 
                               ,'$this->r01_natura' 
                               ,'$this->r01_tpcont' 
                               ,'$this->r01_titele' 
                               ,'$this->r01_zonael' 
                               ,'$this->r01_secaoe' 
                               ,$this->r01_taviso 
                               ,'$this->r01_cc' 
                               ,'$this->r01_ocorre' 
                               ,$this->r01_basefo 
                               ,$this->r01_descfo 
                               ,$this->r01_b13fo 
                               ,$this->r01_d13fo 
                               ,'$this->r01_equip' 
                               ,$this->r01_raca 
                               ,$this->r01_mremun 
                               ,'$this->r01_reserv' 
                               ,'$this->r01_catres' 
                               ,$this->r01_propi 
                               ,$this->r01_cargo 
                               ,'$this->r01_clas1' 
                               ,$this->r01_origp 
                               ,".($this->r01_clas2 == "null" || $this->r01_clas2 == ""?"null":"'".$this->r01_clas2."'")." 
                               ,'$this->r01_vale' 
                               ,$this->r01_instit 
                               ,$this->r01_contrato 
                               ,$this->r01_depsf 
                               ,$this->r01_depirf 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Funcionarios ($this->r01_anousu."-".$this->r01_mesusu."-".$this->r01_regist) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Funcionarios já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Funcionarios ($this->r01_anousu."-".$this->r01_mesusu."-".$this->r01_regist) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r01_anousu."-".$this->r01_mesusu."-".$this->r01_regist;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r01_anousu,$this->r01_mesusu,$this->r01_regist));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4185,'$this->r01_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4186,'$this->r01_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4225,'$this->r01_regist','I')");
       $resac = db_query("insert into db_acount values($acount,573,4185,'','".AddSlashes(pg_result($resaco,0,'r01_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4186,'','".AddSlashes(pg_result($resaco,0,'r01_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4187,'','".AddSlashes(pg_result($resaco,0,'r01_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4225,'','".AddSlashes(pg_result($resaco,0,'r01_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4188,'','".AddSlashes(pg_result($resaco,0,'r01_admiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4189,'','".AddSlashes(pg_result($resaco,0,'r01_regime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4190,'','".AddSlashes(pg_result($resaco,0,'r01_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4191,'','".AddSlashes(pg_result($resaco,0,'r01_vincul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4192,'','".AddSlashes(pg_result($resaco,0,'r01_cbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4193,'','".AddSlashes(pg_result($resaco,0,'r01_padrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4194,'','".AddSlashes(pg_result($resaco,0,'r01_salari'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4195,'','".AddSlashes(pg_result($resaco,0,'r01_tipsal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4196,'','".AddSlashes(pg_result($resaco,0,'r01_folha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4197,'','".AddSlashes(pg_result($resaco,0,'r01_fpagto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4198,'','".AddSlashes(pg_result($resaco,0,'r01_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4199,'','".AddSlashes(pg_result($resaco,0,'r01_agenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4200,'','".AddSlashes(pg_result($resaco,0,'r01_contac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4201,'','".AddSlashes(pg_result($resaco,0,'r01_ctps'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4202,'','".AddSlashes(pg_result($resaco,0,'r01_pis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4203,'','".AddSlashes(pg_result($resaco,0,'r01_fgts'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4204,'','".AddSlashes(pg_result($resaco,0,'r01_bcofgt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4205,'','".AddSlashes(pg_result($resaco,0,'r01_agfgts'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4206,'','".AddSlashes(pg_result($resaco,0,'r01_ccfgts'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4207,'','".AddSlashes(pg_result($resaco,0,'r01_hrssem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4208,'','".AddSlashes(pg_result($resaco,0,'r01_situac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4209,'','".AddSlashes(pg_result($resaco,0,'r01_nasc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4210,'','".AddSlashes(pg_result($resaco,0,'r01_nacion'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4211,'','".AddSlashes(pg_result($resaco,0,'r01_anoche'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4212,'','".AddSlashes(pg_result($resaco,0,'r01_instru'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4213,'','".AddSlashes(pg_result($resaco,0,'r01_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4214,'','".AddSlashes(pg_result($resaco,0,'r01_recis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4215,'','".AddSlashes(pg_result($resaco,0,'r01_causa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4216,'','".AddSlashes(pg_result($resaco,0,'r01_ponto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4217,'','".AddSlashes(pg_result($resaco,0,'r01_alim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4218,'','".AddSlashes(pg_result($resaco,0,'r01_digito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4219,'','".AddSlashes(pg_result($resaco,0,'r01_tpvinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4220,'','".AddSlashes(pg_result($resaco,0,'r01_arredn'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4221,'','".AddSlashes(pg_result($resaco,0,'r01_progr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4222,'','".AddSlashes(pg_result($resaco,0,'r01_carth'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4223,'','".AddSlashes(pg_result($resaco,0,'r01_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4224,'','".AddSlashes(pg_result($resaco,0,'r01_tbprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4226,'','".AddSlashes(pg_result($resaco,0,'r01_adia13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4227,'','".AddSlashes(pg_result($resaco,0,'r01_anter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4228,'','".AddSlashes(pg_result($resaco,0,'r01_dtafas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4229,'','".AddSlashes(pg_result($resaco,0,'r01_ctpsuf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4230,'','".AddSlashes(pg_result($resaco,0,'r01_dadi13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4231,'','".AddSlashes(pg_result($resaco,0,'r01_estciv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4232,'','".AddSlashes(pg_result($resaco,0,'r01_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4233,'','".AddSlashes(pg_result($resaco,0,'r01_trien'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4234,'','".AddSlashes(pg_result($resaco,0,'r01_tipadm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4235,'','".AddSlashes(pg_result($resaco,0,'r01_caub'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4236,'','".AddSlashes(pg_result($resaco,0,'r01_aviso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4237,'','".AddSlashes(pg_result($resaco,0,'r01_hrsmen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4238,'','".AddSlashes(pg_result($resaco,0,'r01_rfi1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4239,'','".AddSlashes(pg_result($resaco,0,'r01_rfi2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4240,'','".AddSlashes(pg_result($resaco,0,'r01_rff1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4241,'','".AddSlashes(pg_result($resaco,0,'r01_rff2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4242,'','".AddSlashes(pg_result($resaco,0,'r01_rnd1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4243,'','".AddSlashes(pg_result($resaco,0,'r01_rnd2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4244,'','".AddSlashes(pg_result($resaco,0,'r01_r13i'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4245,'','".AddSlashes(pg_result($resaco,0,'r01_r13f'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4246,'','".AddSlashes(pg_result($resaco,0,'r01_rnd3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4247,'','".AddSlashes(pg_result($resaco,0,'r01_ndres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4248,'','".AddSlashes(pg_result($resaco,0,'r01_ndsal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4249,'','".AddSlashes(pg_result($resaco,0,'r01_prores'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4250,'','".AddSlashes(pg_result($resaco,0,'r01_matipe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4251,'','".AddSlashes(pg_result($resaco,0,'r01_dtvinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4252,'','".AddSlashes(pg_result($resaco,0,'r01_estado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4253,'','".AddSlashes(pg_result($resaco,0,'r01_dtalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4254,'','".AddSlashes(pg_result($resaco,0,'r01_natura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4255,'','".AddSlashes(pg_result($resaco,0,'r01_tpcont'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4256,'','".AddSlashes(pg_result($resaco,0,'r01_titele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4257,'','".AddSlashes(pg_result($resaco,0,'r01_zonael'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4258,'','".AddSlashes(pg_result($resaco,0,'r01_secaoe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4259,'','".AddSlashes(pg_result($resaco,0,'r01_taviso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4260,'','".AddSlashes(pg_result($resaco,0,'r01_cc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4261,'','".AddSlashes(pg_result($resaco,0,'r01_ocorre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4262,'','".AddSlashes(pg_result($resaco,0,'r01_basefo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4263,'','".AddSlashes(pg_result($resaco,0,'r01_descfo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4264,'','".AddSlashes(pg_result($resaco,0,'r01_b13fo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4265,'','".AddSlashes(pg_result($resaco,0,'r01_d13fo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4266,'','".AddSlashes(pg_result($resaco,0,'r01_equip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4267,'','".AddSlashes(pg_result($resaco,0,'r01_raca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4268,'','".AddSlashes(pg_result($resaco,0,'r01_mremun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4603,'','".AddSlashes(pg_result($resaco,0,'r01_reserv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4604,'','".AddSlashes(pg_result($resaco,0,'r01_catres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4605,'','".AddSlashes(pg_result($resaco,0,'r01_propi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4606,'','".AddSlashes(pg_result($resaco,0,'r01_cargo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4607,'','".AddSlashes(pg_result($resaco,0,'r01_clas1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4608,'','".AddSlashes(pg_result($resaco,0,'r01_origp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,4609,'','".AddSlashes(pg_result($resaco,0,'r01_clas2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,9395,'','".AddSlashes(pg_result($resaco,0,'r01_vale'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,7468,'','".AddSlashes(pg_result($resaco,0,'r01_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,8869,'','".AddSlashes(pg_result($resaco,0,'r01_contrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,9394,'','".AddSlashes(pg_result($resaco,0,'r01_depsf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,573,9393,'','".AddSlashes(pg_result($resaco,0,'r01_depirf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r01_anousu=null,$r01_mesusu=null,$r01_regist=null) { 
      $this->atualizacampos();
     $sql = " update pessoal set ";
     $virgula = "";
     if(trim($this->r01_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_anousu"])){ 
       $sql  .= $virgula." r01_anousu = $this->r01_anousu ";
       $virgula = ",";
       if(trim($this->r01_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r01_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r01_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_mesusu"])){ 
       $sql  .= $virgula." r01_mesusu = $this->r01_mesusu ";
       $virgula = ",";
       if(trim($this->r01_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r01_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r01_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_numcgm"])){ 
       $sql  .= $virgula." r01_numcgm = $this->r01_numcgm ";
       $virgula = ",";
       if(trim($this->r01_numcgm) == null ){ 
         $this->erro_sql = " Campo No. CGM nao Informado.";
         $this->erro_campo = "r01_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r01_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_regist"])){ 
       $sql  .= $virgula." r01_regist = $this->r01_regist ";
       $virgula = ",";
       if(trim($this->r01_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula do Servidor nao Informado.";
         $this->erro_campo = "r01_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r01_admiss)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_admiss_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r01_admiss_dia"] !="") ){ 
       $sql  .= $virgula." r01_admiss = '$this->r01_admiss' ";
       $virgula = ",";
       if(trim($this->r01_admiss) == null ){ 
         $this->erro_sql = " Campo Admissão nao Informado.";
         $this->erro_campo = "r01_admiss_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r01_admiss_dia"])){ 
         $sql  .= $virgula." r01_admiss = null ";
         $virgula = ",";
         if(trim($this->r01_admiss) == null ){ 
           $this->erro_sql = " Campo Admissão nao Informado.";
           $this->erro_campo = "r01_admiss_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->r01_regime)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_regime"])){ 
       $sql  .= $virgula." r01_regime = $this->r01_regime ";
       $virgula = ",";
       if(trim($this->r01_regime) == null ){ 
         $this->erro_sql = " Campo Regime nao Informado.";
         $this->erro_campo = "r01_regime";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r01_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_lotac"])){ 
       $sql  .= $virgula." r01_lotac = '$this->r01_lotac' ";
       $virgula = ",";
       if(trim($this->r01_lotac) == null ){ 
         $this->erro_sql = " Campo Lotação nao Informado.";
         $this->erro_campo = "r01_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r01_vincul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_vincul"])){ 
       $sql  .= $virgula." r01_vincul = $this->r01_vincul ";
       $virgula = ",";
       if(trim($this->r01_vincul) == null ){ 
         $this->erro_sql = " Campo Vínculo nao Informado.";
         $this->erro_campo = "r01_vincul";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r01_cbo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_cbo"])){ 
        if(trim($this->r01_cbo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_cbo"])){ 
           $this->r01_cbo = "0" ; 
        } 
       $sql  .= $virgula." r01_cbo = $this->r01_cbo ";
       $virgula = ",";
     }
     if(trim($this->r01_padrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_padrao"])){ 
       $sql  .= $virgula." r01_padrao = '$this->r01_padrao' ";
       $virgula = ",";
     }
     if(trim($this->r01_salari)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_salari"])){ 
        if(trim($this->r01_salari)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_salari"])){ 
           $this->r01_salari = "0" ; 
        } 
       $sql  .= $virgula." r01_salari = $this->r01_salari ";
       $virgula = ",";
     }
     if(trim($this->r01_tipsal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_tipsal"])){ 
       $sql  .= $virgula." r01_tipsal = '$this->r01_tipsal' ";
       $virgula = ",";
     }
     if(trim($this->r01_folha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_folha"])){ 
       $sql  .= $virgula." r01_folha = '$this->r01_folha' ";
       $virgula = ",";
     }
     if(trim($this->r01_fpagto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_fpagto"])){ 
        if(trim($this->r01_fpagto)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_fpagto"])){ 
           $this->r01_fpagto = "0" ; 
        } 
       $sql  .= $virgula." r01_fpagto = $this->r01_fpagto ";
       $virgula = ",";
     }
     if(trim($this->r01_banco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_banco"])){ 
       $sql  .= $virgula." r01_banco = '$this->r01_banco' ";
       $virgula = ",";
     }
     if(trim($this->r01_agenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_agenc"])){ 
       $sql  .= $virgula." r01_agenc = '$this->r01_agenc' ";
       $virgula = ",";
     }
     if(trim($this->r01_contac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_contac"])){ 
       $sql  .= $virgula." r01_contac = '$this->r01_contac' ";
       $virgula = ",";
     }
     if(trim($this->r01_ctps)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_ctps"])){ 
       $sql  .= $virgula." r01_ctps = '$this->r01_ctps' ";
       $virgula = ",";
     }
     if(trim($this->r01_pis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_pis"])){ 
       $sql  .= $virgula." r01_pis = '$this->r01_pis' ";
       $virgula = ",";
     }
     if(trim($this->r01_fgts)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_fgts_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r01_fgts_dia"] !="") ){ 
       $sql  .= $virgula." r01_fgts = '$this->r01_fgts' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r01_fgts_dia"])){ 
         $sql  .= $virgula." r01_fgts = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r01_bcofgt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_bcofgt"])){ 
       $sql  .= $virgula." r01_bcofgt = '$this->r01_bcofgt' ";
       $virgula = ",";
     }
     if(trim($this->r01_agfgts)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_agfgts"])){ 
       $sql  .= $virgula." r01_agfgts = '$this->r01_agfgts' ";
       $virgula = ",";
     }
     if(trim($this->r01_ccfgts)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_ccfgts"])){ 
       $sql  .= $virgula." r01_ccfgts = '$this->r01_ccfgts' ";
       $virgula = ",";
     }
     if(trim($this->r01_hrssem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_hrssem"])){ 
        if(trim($this->r01_hrssem)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_hrssem"])){ 
           $this->r01_hrssem = "0" ; 
        } 
       $sql  .= $virgula." r01_hrssem = $this->r01_hrssem ";
       $virgula = ",";
     }
     if(trim($this->r01_situac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_situac"])){ 
        if(trim($this->r01_situac)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_situac"])){ 
           $this->r01_situac = "0" ; 
        } 
       $sql  .= $virgula." r01_situac = $this->r01_situac ";
       $virgula = ",";
     }
     if(trim($this->r01_nasc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_nasc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r01_nasc_dia"] !="") ){ 
       $sql  .= $virgula." r01_nasc = '$this->r01_nasc' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r01_nasc_dia"])){ 
         $sql  .= $virgula." r01_nasc = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r01_nacion)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_nacion"])){ 
        if(trim($this->r01_nacion)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_nacion"])){ 
           $this->r01_nacion = "0" ; 
        } 
       $sql  .= $virgula." r01_nacion = $this->r01_nacion ";
       $virgula = ",";
     }
     if(trim($this->r01_anoche)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_anoche"])){ 
        if(trim($this->r01_anoche)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_anoche"])){ 
           $this->r01_anoche = "0" ; 
        } 
       $sql  .= $virgula." r01_anoche = $this->r01_anoche ";
       $virgula = ",";
     }
     if(trim($this->r01_instru)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_instru"])){ 
        if(trim($this->r01_instru)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_instru"])){ 
           $this->r01_instru = "0" ; 
        } 
       $sql  .= $virgula." r01_instru = $this->r01_instru ";
       $virgula = ",";
     }
     if(trim($this->r01_sexo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_sexo"])){ 
       $sql  .= $virgula." r01_sexo = '$this->r01_sexo' ";
       $virgula = ",";
     }
     if(trim($this->r01_recis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_recis_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r01_recis_dia"] !="") ){ 
       $sql  .= $virgula." r01_recis = '$this->r01_recis' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r01_recis_dia"])){ 
         $sql  .= $virgula." r01_recis = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r01_causa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_causa"])){ 
        if(trim($this->r01_causa)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_causa"])){ 
           $this->r01_causa = "0" ; 
        } 
       $sql  .= $virgula." r01_causa = $this->r01_causa ";
       $virgula = ",";
     }
     if(trim($this->r01_ponto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_ponto"])){ 
        if(trim($this->r01_ponto)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_ponto"])){ 
           $this->r01_ponto = "0" ; 
        } 
       $sql  .= $virgula." r01_ponto = $this->r01_ponto ";
       $virgula = ",";
     }
     if(trim($this->r01_alim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_alim"])){ 
        if(trim($this->r01_alim)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_alim"])){ 
           $this->r01_alim = "0" ; 
        } 
       $sql  .= $virgula." r01_alim = $this->r01_alim ";
       $virgula = ",";
     }
     if(trim($this->r01_digito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_digito"])){ 
       $sql  .= $virgula." r01_digito = '$this->r01_digito' ";
       $virgula = ",";
     }
     if(trim($this->r01_tpvinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_tpvinc"])){ 
       $sql  .= $virgula." r01_tpvinc = '$this->r01_tpvinc' ";
       $virgula = ",";
     }
     if(trim($this->r01_arredn)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_arredn"])){ 
        if(trim($this->r01_arredn)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_arredn"])){ 
           $this->r01_arredn = "0" ; 
        } 
       $sql  .= $virgula." r01_arredn = $this->r01_arredn ";
       $virgula = ",";
     }
     if(trim($this->r01_progr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_progr"])){ 
       $sql  .= $virgula." r01_progr = '$this->r01_progr' ";
       $virgula = ",";
     }
     if(trim($this->r01_carth)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_carth"])){ 
        if(trim($this->r01_carth)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_carth"])){ 
           $this->r01_carth = "0" ; 
        } 
       $sql  .= $virgula." r01_carth = $this->r01_carth ";
       $virgula = ",";
     }
     if(trim($this->r01_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_rubric"])){ 
       $sql  .= $virgula." r01_rubric = '$this->r01_rubric' ";
       $virgula = ",";
     }
     if(trim($this->r01_tbprev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_tbprev"])){ 
        if(trim($this->r01_tbprev)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_tbprev"])){ 
           $this->r01_tbprev = "0" ; 
        } 
       $sql  .= $virgula." r01_tbprev = $this->r01_tbprev ";
       $virgula = ",";
     }
     if(trim($this->r01_adia13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_adia13"])){ 
        if(trim($this->r01_adia13)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_adia13"])){ 
           $this->r01_adia13 = "0" ; 
        } 
       $sql  .= $virgula." r01_adia13 = $this->r01_adia13 ";
       $virgula = ",";
     }
     if(trim($this->r01_anter)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_anter_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r01_anter_dia"] !="") ){ 
       $sql  .= $virgula." r01_anter = '$this->r01_anter' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r01_anter_dia"])){ 
         $sql  .= $virgula." r01_anter = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r01_dtafas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_dtafas_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r01_dtafas_dia"] !="") ){ 
       $sql  .= $virgula." r01_dtafas = '$this->r01_dtafas' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r01_dtafas_dia"])){ 
         $sql  .= $virgula." r01_dtafas = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r01_ctpsuf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_ctpsuf"])){ 
       $sql  .= $virgula." r01_ctpsuf = '$this->r01_ctpsuf' ";
       $virgula = ",";
     }
     if(trim($this->r01_dadi13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_dadi13_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r01_dadi13_dia"] !="") ){ 
       $sql  .= $virgula." r01_dadi13 = '$this->r01_dadi13' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r01_dadi13_dia"])){ 
         $sql  .= $virgula." r01_dadi13 = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r01_estciv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_estciv"])){ 
       $sql  .= $virgula." r01_estciv = '$this->r01_estciv' ";
       $virgula = ",";
     }
     if(trim($this->r01_funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_funcao"])){ 
        if(trim($this->r01_funcao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_funcao"])){ 
           $this->r01_funcao = "0" ; 
        } 
       $sql  .= $virgula." r01_funcao = $this->r01_funcao ";
       $virgula = ",";
     }
     if(trim($this->r01_trien)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_trien_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r01_trien_dia"] !="") ){ 
       $sql  .= $virgula." r01_trien = '$this->r01_trien' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r01_trien_dia"])){ 
         $sql  .= $virgula." r01_trien = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r01_tipadm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_tipadm"])){ 
        if(trim($this->r01_tipadm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_tipadm"])){ 
           $this->r01_tipadm = "0" ; 
        } 
       $sql  .= $virgula." r01_tipadm = $this->r01_tipadm ";
       $virgula = ",";
     }
     if(trim($this->r01_caub)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_caub"])){ 
       $sql  .= $virgula." r01_caub = '$this->r01_caub' ";
       $virgula = ",";
     }
     if(trim($this->r01_aviso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_aviso_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r01_aviso_dia"] !="") ){ 
       $sql  .= $virgula." r01_aviso = '$this->r01_aviso' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r01_aviso_dia"])){ 
         $sql  .= $virgula." r01_aviso = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r01_hrsmen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_hrsmen"])){ 
        if(trim($this->r01_hrsmen)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_hrsmen"])){ 
           $this->r01_hrsmen = "0" ; 
        } 
       $sql  .= $virgula." r01_hrsmen = $this->r01_hrsmen ";
       $virgula = ",";
     }
     if(trim($this->r01_rfi1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_rfi1_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r01_rfi1_dia"] !="") ){ 
       $sql  .= $virgula." r01_rfi1 = '$this->r01_rfi1' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r01_rfi1_dia"])){ 
         $sql  .= $virgula." r01_rfi1 = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r01_rfi2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_rfi2_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r01_rfi2_dia"] !="") ){ 
       $sql  .= $virgula." r01_rfi2 = '$this->r01_rfi2' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r01_rfi2_dia"])){ 
         $sql  .= $virgula." r01_rfi2 = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r01_rff1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_rff1_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r01_rff1_dia"] !="") ){ 
       $sql  .= $virgula." r01_rff1 = '$this->r01_rff1' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r01_rff1_dia"])){ 
         $sql  .= $virgula." r01_rff1 = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r01_rff2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_rff2_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r01_rff2_dia"] !="") ){ 
       $sql  .= $virgula." r01_rff2 = '$this->r01_rff2' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r01_rff2_dia"])){ 
         $sql  .= $virgula." r01_rff2 = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r01_rnd1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_rnd1"])){ 
        if(trim($this->r01_rnd1)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_rnd1"])){ 
           $this->r01_rnd1 = "0" ; 
        } 
       $sql  .= $virgula." r01_rnd1 = $this->r01_rnd1 ";
       $virgula = ",";
     }
     if(trim($this->r01_rnd2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_rnd2"])){ 
        if(trim($this->r01_rnd2)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_rnd2"])){ 
           $this->r01_rnd2 = "0" ; 
        } 
       $sql  .= $virgula." r01_rnd2 = $this->r01_rnd2 ";
       $virgula = ",";
     }
     if(trim($this->r01_r13i)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_r13i_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r01_r13i_dia"] !="") ){ 
       $sql  .= $virgula." r01_r13i = '$this->r01_r13i' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r01_r13i_dia"])){ 
         $sql  .= $virgula." r01_r13i = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r01_r13f)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_r13f_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r01_r13f_dia"] !="") ){ 
       $sql  .= $virgula." r01_r13f = '$this->r01_r13f' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r01_r13f_dia"])){ 
         $sql  .= $virgula." r01_r13f = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r01_rnd3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_rnd3"])){ 
        if(trim($this->r01_rnd3)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_rnd3"])){ 
           $this->r01_rnd3 = "0" ; 
        } 
       $sql  .= $virgula." r01_rnd3 = $this->r01_rnd3 ";
       $virgula = ",";
     }
     if(trim($this->r01_ndres)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_ndres"])){ 
        if(trim($this->r01_ndres)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_ndres"])){ 
           $this->r01_ndres = "0" ; 
        } 
       $sql  .= $virgula." r01_ndres = $this->r01_ndres ";
       $virgula = ",";
     }
     if(trim($this->r01_ndsal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_ndsal"])){ 
        if(trim($this->r01_ndsal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_ndsal"])){ 
           $this->r01_ndsal = "0" ; 
        } 
       $sql  .= $virgula." r01_ndsal = $this->r01_ndsal ";
       $virgula = ",";
     }
     if(trim($this->r01_prores)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_prores"])){ 
       $sql  .= $virgula." r01_prores = '$this->r01_prores' ";
       $virgula = ",";
     }
     if(trim($this->r01_matipe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_matipe"])){ 
        if(trim($this->r01_matipe)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_matipe"])){ 
           $this->r01_matipe = "0" ; 
        } 
       $sql  .= $virgula." r01_matipe = $this->r01_matipe ";
       $virgula = ",";
     }
     if(trim($this->r01_dtvinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_dtvinc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r01_dtvinc_dia"] !="") ){ 
       $sql  .= $virgula." r01_dtvinc = '$this->r01_dtvinc' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r01_dtvinc_dia"])){ 
         $sql  .= $virgula." r01_dtvinc = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r01_estado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_estado"])){ 
       $sql  .= $virgula." r01_estado = '$this->r01_estado' ";
       $virgula = ",";
     }
     if(trim($this->r01_dtalt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_dtalt_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r01_dtalt_dia"] !="") ){ 
       $sql  .= $virgula." r01_dtalt = '$this->r01_dtalt' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r01_dtalt_dia"])){ 
         $sql  .= $virgula." r01_dtalt = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r01_natura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_natura"])){ 
       $sql  .= $virgula." r01_natura = '$this->r01_natura' ";
       $virgula = ",";
     }
     if(trim($this->r01_tpcont)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_tpcont"])){ 
       $sql  .= $virgula." r01_tpcont = '$this->r01_tpcont' ";
       $virgula = ",";
     }
     if(trim($this->r01_titele)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_titele"])){ 
       $sql  .= $virgula." r01_titele = '$this->r01_titele' ";
       $virgula = ",";
     }
     if(trim($this->r01_zonael)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_zonael"])){ 
       $sql  .= $virgula." r01_zonael = '$this->r01_zonael' ";
       $virgula = ",";
     }
     if(trim($this->r01_secaoe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_secaoe"])){ 
       $sql  .= $virgula." r01_secaoe = '$this->r01_secaoe' ";
       $virgula = ",";
     }
     if(trim($this->r01_taviso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_taviso"])){ 
        if(trim($this->r01_taviso)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_taviso"])){ 
           $this->r01_taviso = "0" ; 
        } 
       $sql  .= $virgula." r01_taviso = $this->r01_taviso ";
       $virgula = ",";
     }
     if(trim($this->r01_cc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_cc"])){ 
       $sql  .= $virgula." r01_cc = '$this->r01_cc' ";
       $virgula = ",";
     }
     if(trim($this->r01_ocorre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_ocorre"])){ 
       $sql  .= $virgula." r01_ocorre = '$this->r01_ocorre' ";
       $virgula = ",";
     }
     if(trim($this->r01_basefo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_basefo"])){ 
        if(trim($this->r01_basefo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_basefo"])){ 
           $this->r01_basefo = "0" ; 
        } 
       $sql  .= $virgula." r01_basefo = $this->r01_basefo ";
       $virgula = ",";
     }
     if(trim($this->r01_descfo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_descfo"])){ 
        if(trim($this->r01_descfo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_descfo"])){ 
           $this->r01_descfo = "0" ; 
        } 
       $sql  .= $virgula." r01_descfo = $this->r01_descfo ";
       $virgula = ",";
     }
     if(trim($this->r01_b13fo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_b13fo"])){ 
        if(trim($this->r01_b13fo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_b13fo"])){ 
           $this->r01_b13fo = "0" ; 
        } 
       $sql  .= $virgula." r01_b13fo = $this->r01_b13fo ";
       $virgula = ",";
     }
     if(trim($this->r01_d13fo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_d13fo"])){ 
        if(trim($this->r01_d13fo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_d13fo"])){ 
           $this->r01_d13fo = "0" ; 
        } 
       $sql  .= $virgula." r01_d13fo = $this->r01_d13fo ";
       $virgula = ",";
     }
     if(trim($this->r01_equip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_equip"])){ 
       $sql  .= $virgula." r01_equip = '$this->r01_equip' ";
       $virgula = ",";
     }
     if(trim($this->r01_raca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_raca"])){ 
        if(trim($this->r01_raca)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_raca"])){ 
           $this->r01_raca = "0" ; 
        } 
       $sql  .= $virgula." r01_raca = $this->r01_raca ";
       $virgula = ",";
     }
     if(trim($this->r01_mremun)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_mremun"])){ 
        if(trim($this->r01_mremun)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_mremun"])){ 
           $this->r01_mremun = "0" ; 
        } 
       $sql  .= $virgula." r01_mremun = $this->r01_mremun ";
       $virgula = ",";
     }
     if(trim($this->r01_reserv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_reserv"])){ 
       $sql  .= $virgula." r01_reserv = '$this->r01_reserv' ";
       $virgula = ",";
     }
     if(trim($this->r01_catres)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_catres"])){ 
       $sql  .= $virgula." r01_catres = '$this->r01_catres' ";
       $virgula = ",";
     }
     if(trim($this->r01_propi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_propi"])){ 
        if(trim($this->r01_propi)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_propi"])){ 
           $this->r01_propi = "0" ; 
        } 
       $sql  .= $virgula." r01_propi = $this->r01_propi ";
       $virgula = ",";
     }
     if(trim($this->r01_cargo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_cargo"])){ 
        if(trim($this->r01_cargo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_cargo"])){ 
           $this->r01_cargo = "0" ; 
        } 
       $sql  .= $virgula." r01_cargo = $this->r01_cargo ";
       $virgula = ",";
     }
     if(trim($this->r01_clas1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_clas1"])){ 
       $sql  .= $virgula." r01_clas1 = '$this->r01_clas1' ";
       $virgula = ",";
     }
     if(trim($this->r01_origp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_origp"])){ 
        if(trim($this->r01_origp)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_origp"])){ 
           $this->r01_origp = "0" ; 
        } 
       $sql  .= $virgula." r01_origp = $this->r01_origp ";
       $virgula = ",";
     }
     if(trim($this->r01_clas2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_clas2_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r01_clas2_dia"] !="") ){ 
       $sql  .= $virgula." r01_clas2 = '$this->r01_clas2' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r01_clas2_dia"])){ 
         $sql  .= $virgula." r01_clas2 = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r01_vale)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_vale"])){ 
       $sql  .= $virgula." r01_vale = '$this->r01_vale' ";
       $virgula = ",";
       if(trim($this->r01_vale) == null ){ 
         $this->erro_sql = " Campo Adiantamento nao Informado.";
         $this->erro_campo = "r01_vale";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r01_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_instit"])){ 
        if(trim($this->r01_instit)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r01_instit"])){ 
           $this->r01_instit = "0" ; 
        } 
       $sql  .= $virgula." r01_instit = $this->r01_instit ";
       $virgula = ",";
     }
     if(trim($this->r01_contrato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_contrato"])){ 
       $sql  .= $virgula." r01_contrato = $this->r01_contrato ";
       $virgula = ",";
       if(trim($this->r01_contrato) == null ){ 
         $this->erro_sql = " Campo Contrato nao Informado.";
         $this->erro_campo = "r01_contrato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r01_depsf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_depsf"])){ 
       $sql  .= $virgula." r01_depsf = $this->r01_depsf ";
       $virgula = ",";
       if(trim($this->r01_depsf) == null ){ 
         $this->erro_sql = " Campo Dependentes Sal.Família nao Informado.";
         $this->erro_campo = "r01_depsf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r01_depirf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r01_depirf"])){ 
       $sql  .= $virgula." r01_depirf = $this->r01_depirf ";
       $virgula = ",";
       if(trim($this->r01_depirf) == null ){ 
         $this->erro_sql = " Campo Dependentes IRRF nao Informado.";
         $this->erro_campo = "r01_depirf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r01_anousu!=null){
       $sql .= " r01_anousu = $this->r01_anousu";
     }
     if($r01_mesusu!=null){
       $sql .= " and  r01_mesusu = $this->r01_mesusu";
     }
     if($r01_regist!=null){
       $sql .= " and  r01_regist = $this->r01_regist";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r01_anousu,$this->r01_mesusu,$this->r01_regist));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4185,'$this->r01_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4186,'$this->r01_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4225,'$this->r01_regist','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_anousu"]) || $this->r01_anousu != "")
           $resac = db_query("insert into db_acount values($acount,573,4185,'".AddSlashes(pg_result($resaco,$conresaco,'r01_anousu'))."','$this->r01_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_mesusu"]) || $this->r01_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,573,4186,'".AddSlashes(pg_result($resaco,$conresaco,'r01_mesusu'))."','$this->r01_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_numcgm"]) || $this->r01_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,573,4187,'".AddSlashes(pg_result($resaco,$conresaco,'r01_numcgm'))."','$this->r01_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_regist"]) || $this->r01_regist != "")
           $resac = db_query("insert into db_acount values($acount,573,4225,'".AddSlashes(pg_result($resaco,$conresaco,'r01_regist'))."','$this->r01_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_admiss"]) || $this->r01_admiss != "")
           $resac = db_query("insert into db_acount values($acount,573,4188,'".AddSlashes(pg_result($resaco,$conresaco,'r01_admiss'))."','$this->r01_admiss',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_regime"]) || $this->r01_regime != "")
           $resac = db_query("insert into db_acount values($acount,573,4189,'".AddSlashes(pg_result($resaco,$conresaco,'r01_regime'))."','$this->r01_regime',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_lotac"]) || $this->r01_lotac != "")
           $resac = db_query("insert into db_acount values($acount,573,4190,'".AddSlashes(pg_result($resaco,$conresaco,'r01_lotac'))."','$this->r01_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_vincul"]) || $this->r01_vincul != "")
           $resac = db_query("insert into db_acount values($acount,573,4191,'".AddSlashes(pg_result($resaco,$conresaco,'r01_vincul'))."','$this->r01_vincul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_cbo"]) || $this->r01_cbo != "")
           $resac = db_query("insert into db_acount values($acount,573,4192,'".AddSlashes(pg_result($resaco,$conresaco,'r01_cbo'))."','$this->r01_cbo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_padrao"]) || $this->r01_padrao != "")
           $resac = db_query("insert into db_acount values($acount,573,4193,'".AddSlashes(pg_result($resaco,$conresaco,'r01_padrao'))."','$this->r01_padrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_salari"]) || $this->r01_salari != "")
           $resac = db_query("insert into db_acount values($acount,573,4194,'".AddSlashes(pg_result($resaco,$conresaco,'r01_salari'))."','$this->r01_salari',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_tipsal"]) || $this->r01_tipsal != "")
           $resac = db_query("insert into db_acount values($acount,573,4195,'".AddSlashes(pg_result($resaco,$conresaco,'r01_tipsal'))."','$this->r01_tipsal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_folha"]) || $this->r01_folha != "")
           $resac = db_query("insert into db_acount values($acount,573,4196,'".AddSlashes(pg_result($resaco,$conresaco,'r01_folha'))."','$this->r01_folha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_fpagto"]) || $this->r01_fpagto != "")
           $resac = db_query("insert into db_acount values($acount,573,4197,'".AddSlashes(pg_result($resaco,$conresaco,'r01_fpagto'))."','$this->r01_fpagto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_banco"]) || $this->r01_banco != "")
           $resac = db_query("insert into db_acount values($acount,573,4198,'".AddSlashes(pg_result($resaco,$conresaco,'r01_banco'))."','$this->r01_banco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_agenc"]) || $this->r01_agenc != "")
           $resac = db_query("insert into db_acount values($acount,573,4199,'".AddSlashes(pg_result($resaco,$conresaco,'r01_agenc'))."','$this->r01_agenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_contac"]) || $this->r01_contac != "")
           $resac = db_query("insert into db_acount values($acount,573,4200,'".AddSlashes(pg_result($resaco,$conresaco,'r01_contac'))."','$this->r01_contac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_ctps"]) || $this->r01_ctps != "")
           $resac = db_query("insert into db_acount values($acount,573,4201,'".AddSlashes(pg_result($resaco,$conresaco,'r01_ctps'))."','$this->r01_ctps',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_pis"]) || $this->r01_pis != "")
           $resac = db_query("insert into db_acount values($acount,573,4202,'".AddSlashes(pg_result($resaco,$conresaco,'r01_pis'))."','$this->r01_pis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_fgts"]) || $this->r01_fgts != "")
           $resac = db_query("insert into db_acount values($acount,573,4203,'".AddSlashes(pg_result($resaco,$conresaco,'r01_fgts'))."','$this->r01_fgts',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_bcofgt"]) || $this->r01_bcofgt != "")
           $resac = db_query("insert into db_acount values($acount,573,4204,'".AddSlashes(pg_result($resaco,$conresaco,'r01_bcofgt'))."','$this->r01_bcofgt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_agfgts"]) || $this->r01_agfgts != "")
           $resac = db_query("insert into db_acount values($acount,573,4205,'".AddSlashes(pg_result($resaco,$conresaco,'r01_agfgts'))."','$this->r01_agfgts',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_ccfgts"]) || $this->r01_ccfgts != "")
           $resac = db_query("insert into db_acount values($acount,573,4206,'".AddSlashes(pg_result($resaco,$conresaco,'r01_ccfgts'))."','$this->r01_ccfgts',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_hrssem"]) || $this->r01_hrssem != "")
           $resac = db_query("insert into db_acount values($acount,573,4207,'".AddSlashes(pg_result($resaco,$conresaco,'r01_hrssem'))."','$this->r01_hrssem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_situac"]) || $this->r01_situac != "")
           $resac = db_query("insert into db_acount values($acount,573,4208,'".AddSlashes(pg_result($resaco,$conresaco,'r01_situac'))."','$this->r01_situac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_nasc"]) || $this->r01_nasc != "")
           $resac = db_query("insert into db_acount values($acount,573,4209,'".AddSlashes(pg_result($resaco,$conresaco,'r01_nasc'))."','$this->r01_nasc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_nacion"]) || $this->r01_nacion != "")
           $resac = db_query("insert into db_acount values($acount,573,4210,'".AddSlashes(pg_result($resaco,$conresaco,'r01_nacion'))."','$this->r01_nacion',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_anoche"]) || $this->r01_anoche != "")
           $resac = db_query("insert into db_acount values($acount,573,4211,'".AddSlashes(pg_result($resaco,$conresaco,'r01_anoche'))."','$this->r01_anoche',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_instru"]) || $this->r01_instru != "")
           $resac = db_query("insert into db_acount values($acount,573,4212,'".AddSlashes(pg_result($resaco,$conresaco,'r01_instru'))."','$this->r01_instru',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_sexo"]) || $this->r01_sexo != "")
           $resac = db_query("insert into db_acount values($acount,573,4213,'".AddSlashes(pg_result($resaco,$conresaco,'r01_sexo'))."','$this->r01_sexo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_recis"]) || $this->r01_recis != "")
           $resac = db_query("insert into db_acount values($acount,573,4214,'".AddSlashes(pg_result($resaco,$conresaco,'r01_recis'))."','$this->r01_recis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_causa"]) || $this->r01_causa != "")
           $resac = db_query("insert into db_acount values($acount,573,4215,'".AddSlashes(pg_result($resaco,$conresaco,'r01_causa'))."','$this->r01_causa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_ponto"]) || $this->r01_ponto != "")
           $resac = db_query("insert into db_acount values($acount,573,4216,'".AddSlashes(pg_result($resaco,$conresaco,'r01_ponto'))."','$this->r01_ponto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_alim"]) || $this->r01_alim != "")
           $resac = db_query("insert into db_acount values($acount,573,4217,'".AddSlashes(pg_result($resaco,$conresaco,'r01_alim'))."','$this->r01_alim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_digito"]) || $this->r01_digito != "")
           $resac = db_query("insert into db_acount values($acount,573,4218,'".AddSlashes(pg_result($resaco,$conresaco,'r01_digito'))."','$this->r01_digito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_tpvinc"]) || $this->r01_tpvinc != "")
           $resac = db_query("insert into db_acount values($acount,573,4219,'".AddSlashes(pg_result($resaco,$conresaco,'r01_tpvinc'))."','$this->r01_tpvinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_arredn"]) || $this->r01_arredn != "")
           $resac = db_query("insert into db_acount values($acount,573,4220,'".AddSlashes(pg_result($resaco,$conresaco,'r01_arredn'))."','$this->r01_arredn',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_progr"]) || $this->r01_progr != "")
           $resac = db_query("insert into db_acount values($acount,573,4221,'".AddSlashes(pg_result($resaco,$conresaco,'r01_progr'))."','$this->r01_progr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_carth"]) || $this->r01_carth != "")
           $resac = db_query("insert into db_acount values($acount,573,4222,'".AddSlashes(pg_result($resaco,$conresaco,'r01_carth'))."','$this->r01_carth',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_rubric"]) || $this->r01_rubric != "")
           $resac = db_query("insert into db_acount values($acount,573,4223,'".AddSlashes(pg_result($resaco,$conresaco,'r01_rubric'))."','$this->r01_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_tbprev"]) || $this->r01_tbprev != "")
           $resac = db_query("insert into db_acount values($acount,573,4224,'".AddSlashes(pg_result($resaco,$conresaco,'r01_tbprev'))."','$this->r01_tbprev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_adia13"]) || $this->r01_adia13 != "")
           $resac = db_query("insert into db_acount values($acount,573,4226,'".AddSlashes(pg_result($resaco,$conresaco,'r01_adia13'))."','$this->r01_adia13',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_anter"]) || $this->r01_anter != "")
           $resac = db_query("insert into db_acount values($acount,573,4227,'".AddSlashes(pg_result($resaco,$conresaco,'r01_anter'))."','$this->r01_anter',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_dtafas"]) || $this->r01_dtafas != "")
           $resac = db_query("insert into db_acount values($acount,573,4228,'".AddSlashes(pg_result($resaco,$conresaco,'r01_dtafas'))."','$this->r01_dtafas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_ctpsuf"]) || $this->r01_ctpsuf != "")
           $resac = db_query("insert into db_acount values($acount,573,4229,'".AddSlashes(pg_result($resaco,$conresaco,'r01_ctpsuf'))."','$this->r01_ctpsuf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_dadi13"]) || $this->r01_dadi13 != "")
           $resac = db_query("insert into db_acount values($acount,573,4230,'".AddSlashes(pg_result($resaco,$conresaco,'r01_dadi13'))."','$this->r01_dadi13',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_estciv"]) || $this->r01_estciv != "")
           $resac = db_query("insert into db_acount values($acount,573,4231,'".AddSlashes(pg_result($resaco,$conresaco,'r01_estciv'))."','$this->r01_estciv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_funcao"]) || $this->r01_funcao != "")
           $resac = db_query("insert into db_acount values($acount,573,4232,'".AddSlashes(pg_result($resaco,$conresaco,'r01_funcao'))."','$this->r01_funcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_trien"]) || $this->r01_trien != "")
           $resac = db_query("insert into db_acount values($acount,573,4233,'".AddSlashes(pg_result($resaco,$conresaco,'r01_trien'))."','$this->r01_trien',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_tipadm"]) || $this->r01_tipadm != "")
           $resac = db_query("insert into db_acount values($acount,573,4234,'".AddSlashes(pg_result($resaco,$conresaco,'r01_tipadm'))."','$this->r01_tipadm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_caub"]) || $this->r01_caub != "")
           $resac = db_query("insert into db_acount values($acount,573,4235,'".AddSlashes(pg_result($resaco,$conresaco,'r01_caub'))."','$this->r01_caub',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_aviso"]) || $this->r01_aviso != "")
           $resac = db_query("insert into db_acount values($acount,573,4236,'".AddSlashes(pg_result($resaco,$conresaco,'r01_aviso'))."','$this->r01_aviso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_hrsmen"]) || $this->r01_hrsmen != "")
           $resac = db_query("insert into db_acount values($acount,573,4237,'".AddSlashes(pg_result($resaco,$conresaco,'r01_hrsmen'))."','$this->r01_hrsmen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_rfi1"]) || $this->r01_rfi1 != "")
           $resac = db_query("insert into db_acount values($acount,573,4238,'".AddSlashes(pg_result($resaco,$conresaco,'r01_rfi1'))."','$this->r01_rfi1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_rfi2"]) || $this->r01_rfi2 != "")
           $resac = db_query("insert into db_acount values($acount,573,4239,'".AddSlashes(pg_result($resaco,$conresaco,'r01_rfi2'))."','$this->r01_rfi2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_rff1"]) || $this->r01_rff1 != "")
           $resac = db_query("insert into db_acount values($acount,573,4240,'".AddSlashes(pg_result($resaco,$conresaco,'r01_rff1'))."','$this->r01_rff1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_rff2"]) || $this->r01_rff2 != "")
           $resac = db_query("insert into db_acount values($acount,573,4241,'".AddSlashes(pg_result($resaco,$conresaco,'r01_rff2'))."','$this->r01_rff2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_rnd1"]) || $this->r01_rnd1 != "")
           $resac = db_query("insert into db_acount values($acount,573,4242,'".AddSlashes(pg_result($resaco,$conresaco,'r01_rnd1'))."','$this->r01_rnd1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_rnd2"]) || $this->r01_rnd2 != "")
           $resac = db_query("insert into db_acount values($acount,573,4243,'".AddSlashes(pg_result($resaco,$conresaco,'r01_rnd2'))."','$this->r01_rnd2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_r13i"]) || $this->r01_r13i != "")
           $resac = db_query("insert into db_acount values($acount,573,4244,'".AddSlashes(pg_result($resaco,$conresaco,'r01_r13i'))."','$this->r01_r13i',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_r13f"]) || $this->r01_r13f != "")
           $resac = db_query("insert into db_acount values($acount,573,4245,'".AddSlashes(pg_result($resaco,$conresaco,'r01_r13f'))."','$this->r01_r13f',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_rnd3"]) || $this->r01_rnd3 != "")
           $resac = db_query("insert into db_acount values($acount,573,4246,'".AddSlashes(pg_result($resaco,$conresaco,'r01_rnd3'))."','$this->r01_rnd3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_ndres"]) || $this->r01_ndres != "")
           $resac = db_query("insert into db_acount values($acount,573,4247,'".AddSlashes(pg_result($resaco,$conresaco,'r01_ndres'))."','$this->r01_ndres',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_ndsal"]) || $this->r01_ndsal != "")
           $resac = db_query("insert into db_acount values($acount,573,4248,'".AddSlashes(pg_result($resaco,$conresaco,'r01_ndsal'))."','$this->r01_ndsal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_prores"]) || $this->r01_prores != "")
           $resac = db_query("insert into db_acount values($acount,573,4249,'".AddSlashes(pg_result($resaco,$conresaco,'r01_prores'))."','$this->r01_prores',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_matipe"]) || $this->r01_matipe != "")
           $resac = db_query("insert into db_acount values($acount,573,4250,'".AddSlashes(pg_result($resaco,$conresaco,'r01_matipe'))."','$this->r01_matipe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_dtvinc"]) || $this->r01_dtvinc != "")
           $resac = db_query("insert into db_acount values($acount,573,4251,'".AddSlashes(pg_result($resaco,$conresaco,'r01_dtvinc'))."','$this->r01_dtvinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_estado"]) || $this->r01_estado != "")
           $resac = db_query("insert into db_acount values($acount,573,4252,'".AddSlashes(pg_result($resaco,$conresaco,'r01_estado'))."','$this->r01_estado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_dtalt"]) || $this->r01_dtalt != "")
           $resac = db_query("insert into db_acount values($acount,573,4253,'".AddSlashes(pg_result($resaco,$conresaco,'r01_dtalt'))."','$this->r01_dtalt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_natura"]) || $this->r01_natura != "")
           $resac = db_query("insert into db_acount values($acount,573,4254,'".AddSlashes(pg_result($resaco,$conresaco,'r01_natura'))."','$this->r01_natura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_tpcont"]) || $this->r01_tpcont != "")
           $resac = db_query("insert into db_acount values($acount,573,4255,'".AddSlashes(pg_result($resaco,$conresaco,'r01_tpcont'))."','$this->r01_tpcont',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_titele"]) || $this->r01_titele != "")
           $resac = db_query("insert into db_acount values($acount,573,4256,'".AddSlashes(pg_result($resaco,$conresaco,'r01_titele'))."','$this->r01_titele',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_zonael"]) || $this->r01_zonael != "")
           $resac = db_query("insert into db_acount values($acount,573,4257,'".AddSlashes(pg_result($resaco,$conresaco,'r01_zonael'))."','$this->r01_zonael',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_secaoe"]) || $this->r01_secaoe != "")
           $resac = db_query("insert into db_acount values($acount,573,4258,'".AddSlashes(pg_result($resaco,$conresaco,'r01_secaoe'))."','$this->r01_secaoe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_taviso"]) || $this->r01_taviso != "")
           $resac = db_query("insert into db_acount values($acount,573,4259,'".AddSlashes(pg_result($resaco,$conresaco,'r01_taviso'))."','$this->r01_taviso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_cc"]) || $this->r01_cc != "")
           $resac = db_query("insert into db_acount values($acount,573,4260,'".AddSlashes(pg_result($resaco,$conresaco,'r01_cc'))."','$this->r01_cc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_ocorre"]) || $this->r01_ocorre != "")
           $resac = db_query("insert into db_acount values($acount,573,4261,'".AddSlashes(pg_result($resaco,$conresaco,'r01_ocorre'))."','$this->r01_ocorre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_basefo"]) || $this->r01_basefo != "")
           $resac = db_query("insert into db_acount values($acount,573,4262,'".AddSlashes(pg_result($resaco,$conresaco,'r01_basefo'))."','$this->r01_basefo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_descfo"]) || $this->r01_descfo != "")
           $resac = db_query("insert into db_acount values($acount,573,4263,'".AddSlashes(pg_result($resaco,$conresaco,'r01_descfo'))."','$this->r01_descfo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_b13fo"]) || $this->r01_b13fo != "")
           $resac = db_query("insert into db_acount values($acount,573,4264,'".AddSlashes(pg_result($resaco,$conresaco,'r01_b13fo'))."','$this->r01_b13fo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_d13fo"]) || $this->r01_d13fo != "")
           $resac = db_query("insert into db_acount values($acount,573,4265,'".AddSlashes(pg_result($resaco,$conresaco,'r01_d13fo'))."','$this->r01_d13fo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_equip"]) || $this->r01_equip != "")
           $resac = db_query("insert into db_acount values($acount,573,4266,'".AddSlashes(pg_result($resaco,$conresaco,'r01_equip'))."','$this->r01_equip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_raca"]) || $this->r01_raca != "")
           $resac = db_query("insert into db_acount values($acount,573,4267,'".AddSlashes(pg_result($resaco,$conresaco,'r01_raca'))."','$this->r01_raca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_mremun"]) || $this->r01_mremun != "")
           $resac = db_query("insert into db_acount values($acount,573,4268,'".AddSlashes(pg_result($resaco,$conresaco,'r01_mremun'))."','$this->r01_mremun',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_reserv"]) || $this->r01_reserv != "")
           $resac = db_query("insert into db_acount values($acount,573,4603,'".AddSlashes(pg_result($resaco,$conresaco,'r01_reserv'))."','$this->r01_reserv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_catres"]) || $this->r01_catres != "")
           $resac = db_query("insert into db_acount values($acount,573,4604,'".AddSlashes(pg_result($resaco,$conresaco,'r01_catres'))."','$this->r01_catres',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_propi"]) || $this->r01_propi != "")
           $resac = db_query("insert into db_acount values($acount,573,4605,'".AddSlashes(pg_result($resaco,$conresaco,'r01_propi'))."','$this->r01_propi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_cargo"]) || $this->r01_cargo != "")
           $resac = db_query("insert into db_acount values($acount,573,4606,'".AddSlashes(pg_result($resaco,$conresaco,'r01_cargo'))."','$this->r01_cargo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_clas1"]) || $this->r01_clas1 != "")
           $resac = db_query("insert into db_acount values($acount,573,4607,'".AddSlashes(pg_result($resaco,$conresaco,'r01_clas1'))."','$this->r01_clas1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_origp"]) || $this->r01_origp != "")
           $resac = db_query("insert into db_acount values($acount,573,4608,'".AddSlashes(pg_result($resaco,$conresaco,'r01_origp'))."','$this->r01_origp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_clas2"]) || $this->r01_clas2 != "")
           $resac = db_query("insert into db_acount values($acount,573,4609,'".AddSlashes(pg_result($resaco,$conresaco,'r01_clas2'))."','$this->r01_clas2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_vale"]) || $this->r01_vale != "")
           $resac = db_query("insert into db_acount values($acount,573,9395,'".AddSlashes(pg_result($resaco,$conresaco,'r01_vale'))."','$this->r01_vale',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_instit"]) || $this->r01_instit != "")
           $resac = db_query("insert into db_acount values($acount,573,7468,'".AddSlashes(pg_result($resaco,$conresaco,'r01_instit'))."','$this->r01_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_contrato"]) || $this->r01_contrato != "")
           $resac = db_query("insert into db_acount values($acount,573,8869,'".AddSlashes(pg_result($resaco,$conresaco,'r01_contrato'))."','$this->r01_contrato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_depsf"]) || $this->r01_depsf != "")
           $resac = db_query("insert into db_acount values($acount,573,9394,'".AddSlashes(pg_result($resaco,$conresaco,'r01_depsf'))."','$this->r01_depsf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r01_depirf"]) || $this->r01_depirf != "")
           $resac = db_query("insert into db_acount values($acount,573,9393,'".AddSlashes(pg_result($resaco,$conresaco,'r01_depirf'))."','$this->r01_depirf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Funcionarios nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r01_anousu."-".$this->r01_mesusu."-".$this->r01_regist;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Funcionarios nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r01_anousu."-".$this->r01_mesusu."-".$this->r01_regist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r01_anousu."-".$this->r01_mesusu."-".$this->r01_regist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r01_anousu=null,$r01_mesusu=null,$r01_regist=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r01_anousu,$r01_mesusu,$r01_regist));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4185,'$r01_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4186,'$r01_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4225,'$r01_regist','E')");
         $resac = db_query("insert into db_acount values($acount,573,4185,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4186,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4187,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4225,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4188,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_admiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4189,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_regime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4190,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4191,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_vincul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4192,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_cbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4193,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_padrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4194,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_salari'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4195,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_tipsal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4196,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_folha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4197,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_fpagto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4198,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4199,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_agenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4200,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_contac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4201,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_ctps'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4202,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_pis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4203,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_fgts'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4204,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_bcofgt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4205,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_agfgts'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4206,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_ccfgts'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4207,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_hrssem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4208,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_situac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4209,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_nasc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4210,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_nacion'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4211,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_anoche'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4212,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_instru'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4213,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4214,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_recis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4215,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_causa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4216,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_ponto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4217,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_alim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4218,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_digito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4219,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_tpvinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4220,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_arredn'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4221,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_progr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4222,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_carth'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4223,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4224,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_tbprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4226,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_adia13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4227,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_anter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4228,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_dtafas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4229,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_ctpsuf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4230,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_dadi13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4231,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_estciv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4232,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4233,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_trien'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4234,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_tipadm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4235,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_caub'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4236,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_aviso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4237,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_hrsmen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4238,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_rfi1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4239,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_rfi2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4240,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_rff1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4241,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_rff2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4242,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_rnd1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4243,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_rnd2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4244,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_r13i'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4245,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_r13f'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4246,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_rnd3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4247,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_ndres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4248,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_ndsal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4249,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_prores'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4250,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_matipe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4251,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_dtvinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4252,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_estado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4253,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_dtalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4254,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_natura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4255,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_tpcont'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4256,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_titele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4257,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_zonael'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4258,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_secaoe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4259,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_taviso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4260,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_cc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4261,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_ocorre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4262,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_basefo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4263,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_descfo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4264,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_b13fo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4265,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_d13fo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4266,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_equip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4267,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_raca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4268,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_mremun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4603,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_reserv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4604,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_catres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4605,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_propi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4606,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_cargo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4607,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_clas1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4608,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_origp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,4609,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_clas2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,9395,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_vale'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,7468,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,8869,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_contrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,9394,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_depsf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,573,9393,'','".AddSlashes(pg_result($resaco,$iresaco,'r01_depirf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pessoal
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r01_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r01_anousu = $r01_anousu ";
        }
        if($r01_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r01_mesusu = $r01_mesusu ";
        }
        if($r01_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r01_regist = $r01_regist ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Funcionarios nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r01_anousu."-".$r01_mesusu."-".$r01_regist;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Funcionarios nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r01_anousu."-".$r01_mesusu."-".$r01_regist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r01_anousu."-".$r01_mesusu."-".$r01_regist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:pessoal";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function atualiza_incluir (){
  	 $this->incluir($this->r01_anousu,$this->r01_mesusu,$this->r01_regist);
   }
   function sql_query ( $r01_anousu=null,$r01_mesusu=null,$r01_regist=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pessoal ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = pessoal.r01_instit";
     $sql .= "      inner join funcao  on  funcao.r37_anousu = pessoal.r01_anousu and  funcao.r37_mesusu = pessoal.r01_mesusu and  funcao.r37_funcao = pessoal.r01_funcao";
     $sql .= "      inner join inssirf  on  inssirf.r33_codigo = pessoal.r01_anousu and  inssirf. = pessoal.r01_mesusu and  inssirf. = pessoal.r01_tbprev";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = pessoal.r01_anousu and  lotacao.r13_mesusu = pessoal.r01_mesusu and  lotacao.r13_codigo = pessoal.r01_lotac";
     $sql .= "      inner join cargo  on  cargo.r65_anousu = pessoal.r01_anousu and  cargo.r65_mesusu = pessoal.r01_mesusu and  cargo.r65_cargo = pessoal.r01_cargo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_config  as a on   a.codigo = lotacao.r13_instit";
     $sql .= "      inner join db_config  as b on   b.codigo = lotacao.r13_instit";
     $sql .= "      inner join db_config  as c on   c.codigo = lotacao.r13_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($r01_anousu!=null ){
         $sql2 .= " where pessoal.r01_anousu = $r01_anousu "; 
       } 
       if($r01_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pessoal.r01_mesusu = $r01_mesusu "; 
       } 
       if($r01_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pessoal.r01_regist = $r01_regist "; 
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
   function sql_query_cgm ( $r01_anousu=null,$r01_mesusu=null,$r01_regist=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pessoal ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = pessoal.r01_anousu and  lotacao.r13_mesusu = pessoal.r01_mesusu and  lotacao.r13_codigo = pessoal.r01_lotac";
     $sql2 = "";
     if($dbwhere==""){
       if($r01_anousu!=null ){
         $sql2 .= " where pessoal.r01_anousu = $r01_anousu "; 
       } 
       if($r01_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pessoal.r01_mesusu = $r01_mesusu "; 
       } 
       if($r01_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pessoal.r01_regist = $r01_regist "; 
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
   function sql_query_file ( $r01_anousu=null,$r01_mesusu=null,$r01_regist=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pessoal ";
     $sql2 = "";
     if($dbwhere==""){
       if($r01_anousu!=null ){
         $sql2 .= " where pessoal.r01_anousu = $r01_anousu "; 
       } 
       if($r01_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pessoal.r01_mesusu = $r01_mesusu "; 
       } 
       if($r01_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pessoal.r01_regist = $r01_regist "; 
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
}
?>