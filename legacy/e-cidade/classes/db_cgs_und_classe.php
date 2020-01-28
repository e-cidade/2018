<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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
//MODULO: ambulatorial
//CLASSE DA ENTIDADE cgs_und
class cl_cgs_und {
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
   var $z01_i_cgsund = 0;
   var $z01_v_cgccpf = null;
   var $z01_v_nome = null;
   var $z01_v_ender = null;
   var $z01_i_numero = 0;
   var $z01_v_compl = null;
   var $z01_v_bairro = null;
   var $z01_v_munic = null;
   var $z01_v_uf = null;
   var $z01_v_cep = null;
   var $z01_d_cadast_dia = null;
   var $z01_d_cadast_mes = null;
   var $z01_d_cadast_ano = null;
   var $z01_d_cadast = null;
   var $z01_v_telef = null;
   var $z01_v_ident = null;
   var $z01_i_login = 0;
   var $z01_v_telcel = null;
   var $z01_v_email = null;
   var $z01_d_nasc_dia = null;
   var $z01_d_nasc_mes = null;
   var $z01_d_nasc_ano = null;
   var $z01_d_nasc = null;
   var $z01_v_sexo = null;
   var $z01_o_oid = 0;
   var $z01_c_foto = null;
   var $z01_v_ufcon = null;
   var $z01_v_telcon = null;
   var $z01_v_profis = null;
   var $z01_v_pai = null;
   var $z01_v_muncon = null;
   var $z01_v_mae = null;
   var $z01_v_hora = null;
   var $z01_v_fax = null;
   var $z01_v_endcon = null;
   var $z01_v_emailc = null;
   var $z01_v_cxposcon = null;
   var $z01_v_contato = null;
   var $z01_v_comcon = null;
   var $z01_v_cnh = null;
   var $z01_v_cepcon = null;
   var $z01_v_celcon = null;
   var $z01_v_categoria = null;
   var $z01_i_numcon = 0;
   var $z01_i_nacion = 0;
   var $z01_i_estciv = 0;
   var $z01_d_ultalt_dia = null;
   var $z01_d_ultalt_mes = null;
   var $z01_d_ultalt_ano = null;
   var $z01_d_ultalt = null;
   var $z01_d_dtvencimento_dia = null;
   var $z01_d_dtvencimento_mes = null;
   var $z01_d_dtvencimento_ano = null;
   var $z01_d_dtvencimento = null;
   var $z01_d_dthabilitacao_dia = null;
   var $z01_d_dthabilitacao_mes = null;
   var $z01_d_dthabilitacao_ano = null;
   var $z01_d_dthabilitacao = null;
   var $z01_d_dtemissao_dia = null;
   var $z01_d_dtemissao_mes = null;
   var $z01_d_dtemissao_ano = null;
   var $z01_d_dtemissao = null;
   var $z01_c_passivo = null;
   var $z01_c_bolsafamilia = null;
   var $z01_c_nis = null;
   var $z01_c_certidaodata_dia = null;
   var $z01_c_certidaodata_mes = null;
   var $z01_c_certidaodata_ano = null;
   var $z01_c_certidaodata = null;
   var $z01_c_certidaocart = null;
   var $z01_c_certidaofolha = null;
   var $z01_c_certidaolivro = null;
   var $z01_c_certidaotermo = null;
   var $z01_c_certidaonum = null;
   var $z01_c_certidaotipo = null;
   var $z01_c_zona = null;
   var $z01_c_transporte = null;
   var $z01_t_obs = null;
   var $z01_c_atendesp = null;
   var $z01_c_emailresp = null;
   var $z01_c_nomeresp = null;
   var $z01_c_naturalidade = null;
   var $z01_v_cxpostal = null;
   var $z01_c_raca = null;
   var $z01_v_baicon = null;
   var $z01_i_familiamicroarea = 0;
   var $z01_c_agencia = null;
   var $z01_c_conta = null;
   var $z01_c_banco = null;
   var $z01_c_ufctps = null;
   var $z01_d_dtemissaoctps_dia = null;
   var $z01_d_dtemissaoctps_mes = null;
   var $z01_d_dtemissaoctps_ano = null;
   var $z01_d_dtemissaoctps = null;
   var $z01_c_seriectps = null;
   var $z01_c_numctps = null;
   var $z01_c_ufident = null;
   var $z01_c_escolaridade = null;
   var $z01_c_pis = null;
   var $z01_d_datapais_dia = null;
   var $z01_d_datapais_mes = null;
   var $z01_d_datapais_ano = null;
   var $z01_d_datapais = null;
   var $z01_d_dtemissaocnh_dia = null;
   var $z01_d_dtemissaocnh_mes = null;
   var $z01_d_dtemissaocnh_ano = null;
   var $z01_d_dtemissaocnh = null;
   var $z01_codigoibge = null;
   var $z01_i_cgm = null;
   var $z01_i_cge = null;
   var $z01_i_cidadao = null;
   var $z01_v_municnasc = null;
   var $z01_v_ufnasc = null;
   var $z01_b_faleceu = 0;
   var $z01_d_falecimento_dia = null;
   var $z01_d_falecimento_mes = null;
   var $z01_d_falecimento_ano = null;
   var $z01_d_falecimento = null;
   var $z01_b_descnomemae = null;
   var $z01_i_naturalidade = 0;
   var $z01_i_paisorigem = null;
   var $z01_i_escolaridade = null;
   var $z01_codigoibgenasc = null;
   var $z01_i_codocupacao = null;
   var $z01_b_inativo = false;
   var $z01_orgaoemissoridentidade = null;
   var $z01_registromunicipio = 'f';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 z01_i_cgsund = int4 = CGS
                 z01_v_cgccpf = varchar(14) = CPF
                 z01_v_nome = varchar(255) = Nome
                 z01_v_ender = varchar(40) = Endereço
                 z01_i_numero = int4 = Número
                 z01_v_compl = varchar(20) = Complemento
                 z01_v_bairro = varchar(40) = Bairro
                 z01_v_munic = varchar(40) = Municipio
                 z01_v_uf = varchar(2) = UF
                 z01_v_cep = varchar(8) = CEP
                 z01_d_cadast = date = Cadastro
                 z01_v_telef = varchar(12) = Telefone
                 z01_v_ident = varchar(20) = Identidade
                 z01_i_login = int4 = Login
                 z01_v_telcel = varchar(12) = Celular
                 z01_v_email = varchar(255) = Email
                 z01_d_nasc = date = Nascimento
                 z01_v_sexo = varchar(1) = Sexo
                 z01_o_oid = oid = Imagem
                 z01_c_foto = char(100) = Foto
                 z01_v_ufcon = varchar(2) = UF Comercial
                 z01_v_telcon = varchar(12) = Telefone Comercial
                 z01_v_profis = varchar(40) = Profissão
                 z01_v_pai = varchar(40) = Pai
                 z01_v_muncon = varchar(40) = Município Comercial
                 z01_v_mae = varchar(255) = Mãe
                 z01_v_hora = varchar(5) = Hora do cadastramento
                 z01_v_fax = char(12) = Fax
                 z01_v_endcon = varchar(100) = Endereço Comercial
                 z01_v_emailc = varchar(100) = Email Comercial
                 z01_v_cxposcon = varchar(20) = Caixa Postal
                 z01_v_contato = varchar(40) = Contato
                 z01_v_comcon = varchar(20) = Complemento
                 z01_v_cnh = varchar(20) = CNH
                 z01_v_cepcon = varchar(8) = CEP Comercial
                 z01_v_celcon = varchar(12) = Celular Comercial
                 z01_v_categoria = varchar(2) = Categoria CNH
                 z01_i_numcon = int4 = Número
                 z01_i_nacion = int4 = Nacionalidade
                 z01_i_estciv = int4 = Estado Civil
                 z01_d_ultalt = date = Última Alteração
                 z01_d_dtvencimento = date = Data de Vencimento
                 z01_d_dthabilitacao = date = Data Habilitação
                 z01_d_dtemissao = date = Data de Emissão
                 z01_c_passivo = char(1) = Passivo
                 z01_c_bolsafamilia = char(1) = Bolsa Família
                 z01_c_nis = char(20) = N° NIS
                 z01_c_certidaodata = date = Data da Emisssão
                 z01_c_certidaocart = char(30) = Cartório
                 z01_c_certidaofolha = char(20) = Folha
                 z01_c_certidaolivro = char(20) = Livro
                 z01_c_certidaotermo = char(40) = Termo
                 z01_c_certidaonum = char(20) = Número
                 z01_c_certidaotipo = char(1) = Tipo de Certidão
                 z01_c_zona = char(20) = Zona
                 z01_c_transporte = char(20) = Transporte
                 z01_t_obs = text = Observações
                 z01_c_atendesp = char(30) = Atendimento Especializado
                 z01_c_emailresp = char(50) = Email do responsável
                 z01_c_nomeresp = char(40) = Nome do Responsável
                 z01_c_naturalidade = char(30) = Naturalidade
                 z01_v_cxpostal = varchar(50) = Caixa Postal
                 z01_c_raca = char(20) = Raça
                 z01_v_baicon = varchar(40) = Bairro Comercial
                 z01_i_familiamicroarea = int4 = Familia Micro Área
                 z01_c_agencia = char(40) = Agência
                 z01_c_conta = char(10) = Conta
                 z01_c_banco = char(40) = Banco
                 z01_c_ufctps = char(2) = UF CTPS
                 z01_d_dtemissaoctps = date = Data Emissão CTPS
                 z01_c_seriectps = char(10) = Serie CTPS
                 z01_c_numctps = char(10) = Numero CTPS
                 z01_c_ufident = char(2) = UF
                 z01_c_escolaridade = char(50) = Escolaridade
                 z01_c_pis = char(11) = PIS/PASEP
                 z01_d_datapais = date = Data Entrada
                 z01_d_dtemissaocnh = date = Data de Emissão
                 z01_codigoibge = varchar(50) = Código IBGE
                 z01_orgaoemissoridentidade = varchar(100) = Órgão Emissor da Identidade
                 z01_registromunicipio = bool = CGS do Município
                 ";
   //funcao construtor da classe
   function cl_cgs_und() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cgs_und");
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
       $this->z01_i_cgsund = ($this->z01_i_cgsund == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_i_cgsund"]:$this->z01_i_cgsund);
       $this->z01_v_cgccpf = ($this->z01_v_cgccpf == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_cgccpf"]:$this->z01_v_cgccpf);
       $this->z01_v_nome = ($this->z01_v_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_nome"]:$this->z01_v_nome);
       $this->z01_v_ender = ($this->z01_v_ender == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_ender"]:$this->z01_v_ender);
       $this->z01_i_numero = ($this->z01_i_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_i_numero"]:$this->z01_i_numero);
       $this->z01_v_compl = ($this->z01_v_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_compl"]:$this->z01_v_compl);
       $this->z01_v_bairro = ($this->z01_v_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_bairro"]:$this->z01_v_bairro);
       $this->z01_v_munic = ($this->z01_v_munic == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_munic"]:$this->z01_v_munic);
       $this->z01_v_uf = ($this->z01_v_uf == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_uf"]:$this->z01_v_uf);
       $this->z01_v_cep = ($this->z01_v_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_cep"]:$this->z01_v_cep);
       if($this->z01_d_cadast == ""){
         $this->z01_d_cadast_dia = ($this->z01_d_cadast_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_cadast_dia"]:$this->z01_d_cadast_dia);
         $this->z01_d_cadast_mes = ($this->z01_d_cadast_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_cadast_mes"]:$this->z01_d_cadast_mes);
         $this->z01_d_cadast_ano = ($this->z01_d_cadast_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_cadast_ano"]:$this->z01_d_cadast_ano);
         if($this->z01_d_cadast_dia != ""){
            $this->z01_d_cadast = $this->z01_d_cadast_ano."-".$this->z01_d_cadast_mes."-".$this->z01_d_cadast_dia;
         }
       }
       $this->z01_v_telef = ($this->z01_v_telef == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_telef"]:$this->z01_v_telef);
       $this->z01_v_ident = ($this->z01_v_ident == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_ident"]:$this->z01_v_ident);
       $this->z01_i_login = ($this->z01_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_i_login"]:$this->z01_i_login);
       $this->z01_v_telcel = ($this->z01_v_telcel == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_telcel"]:$this->z01_v_telcel);
       $this->z01_v_email = ($this->z01_v_email == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_email"]:$this->z01_v_email);
       if($this->z01_d_nasc == ""){
         $this->z01_d_nasc_dia = ($this->z01_d_nasc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_nasc_dia"]:$this->z01_d_nasc_dia);
         $this->z01_d_nasc_mes = ($this->z01_d_nasc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_nasc_mes"]:$this->z01_d_nasc_mes);
         $this->z01_d_nasc_ano = ($this->z01_d_nasc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_nasc_ano"]:$this->z01_d_nasc_ano);
         if($this->z01_d_nasc_dia != ""){
            $this->z01_d_nasc = $this->z01_d_nasc_ano."-".$this->z01_d_nasc_mes."-".$this->z01_d_nasc_dia;
         }
       }
       $this->z01_v_sexo = ($this->z01_v_sexo == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_sexo"]:$this->z01_v_sexo);
       $this->z01_o_oid = ($this->z01_o_oid == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_o_oid"]:$this->z01_o_oid);
       $this->z01_c_foto = ($this->z01_c_foto == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_foto"]:$this->z01_c_foto);
       $this->z01_v_ufcon = ($this->z01_v_ufcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_ufcon"]:$this->z01_v_ufcon);
       $this->z01_v_telcon = ($this->z01_v_telcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_telcon"]:$this->z01_v_telcon);
       $this->z01_v_profis = ($this->z01_v_profis == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_profis"]:$this->z01_v_profis);
       $this->z01_v_pai = ($this->z01_v_pai == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_pai"]:$this->z01_v_pai);
       $this->z01_v_muncon = ($this->z01_v_muncon == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_muncon"]:$this->z01_v_muncon);
       $this->z01_v_mae = ($this->z01_v_mae == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_mae"]:$this->z01_v_mae);
       $this->z01_v_hora = ($this->z01_v_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_hora"]:$this->z01_v_hora);
       $this->z01_v_fax = ($this->z01_v_fax == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_fax"]:$this->z01_v_fax);
       $this->z01_v_endcon = ($this->z01_v_endcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_endcon"]:$this->z01_v_endcon);
       $this->z01_v_emailc = ($this->z01_v_emailc == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_emailc"]:$this->z01_v_emailc);
       $this->z01_v_cxposcon = ($this->z01_v_cxposcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_cxposcon"]:$this->z01_v_cxposcon);
       $this->z01_v_contato = ($this->z01_v_contato == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_contato"]:$this->z01_v_contato);
       $this->z01_v_comcon = ($this->z01_v_comcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_comcon"]:$this->z01_v_comcon);
       $this->z01_v_cnh = ($this->z01_v_cnh == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_cnh"]:$this->z01_v_cnh);
       $this->z01_v_cepcon = ($this->z01_v_cepcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_cepcon"]:$this->z01_v_cepcon);
       $this->z01_v_celcon = ($this->z01_v_celcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_celcon"]:$this->z01_v_celcon);
       $this->z01_v_categoria = ($this->z01_v_categoria == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_categoria"]:$this->z01_v_categoria);
       $this->z01_i_numcon = ($this->z01_i_numcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_i_numcon"]:$this->z01_i_numcon);
       $this->z01_i_nacion = ($this->z01_i_nacion == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_i_nacion"]:$this->z01_i_nacion);
       $this->z01_i_estciv = ($this->z01_i_estciv == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_i_estciv"]:$this->z01_i_estciv);
       if($this->z01_d_ultalt == ""){
         $this->z01_d_ultalt_dia = ($this->z01_d_ultalt_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_ultalt_dia"]:$this->z01_d_ultalt_dia);
         $this->z01_d_ultalt_mes = ($this->z01_d_ultalt_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_ultalt_mes"]:$this->z01_d_ultalt_mes);
         $this->z01_d_ultalt_ano = ($this->z01_d_ultalt_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_ultalt_ano"]:$this->z01_d_ultalt_ano);
         if($this->z01_d_ultalt_dia != ""){
            $this->z01_d_ultalt = $this->z01_d_ultalt_ano."-".$this->z01_d_ultalt_mes."-".$this->z01_d_ultalt_dia;
         }
       }
       if($this->z01_d_dtvencimento == ""){
         $this->z01_d_dtvencimento_dia = ($this->z01_d_dtvencimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_dtvencimento_dia"]:$this->z01_d_dtvencimento_dia);
         $this->z01_d_dtvencimento_mes = ($this->z01_d_dtvencimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_dtvencimento_mes"]:$this->z01_d_dtvencimento_mes);
         $this->z01_d_dtvencimento_ano = ($this->z01_d_dtvencimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_dtvencimento_ano"]:$this->z01_d_dtvencimento_ano);
         if($this->z01_d_dtvencimento_dia != ""){
            $this->z01_d_dtvencimento = $this->z01_d_dtvencimento_ano."-".$this->z01_d_dtvencimento_mes."-".$this->z01_d_dtvencimento_dia;
         }
       }
       if($this->z01_d_dthabilitacao == ""){
         $this->z01_d_dthabilitacao_dia = ($this->z01_d_dthabilitacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_dthabilitacao_dia"]:$this->z01_d_dthabilitacao_dia);
         $this->z01_d_dthabilitacao_mes = ($this->z01_d_dthabilitacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_dthabilitacao_mes"]:$this->z01_d_dthabilitacao_mes);
         $this->z01_d_dthabilitacao_ano = ($this->z01_d_dthabilitacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_dthabilitacao_ano"]:$this->z01_d_dthabilitacao_ano);
         if($this->z01_d_dthabilitacao_dia != ""){
            $this->z01_d_dthabilitacao = $this->z01_d_dthabilitacao_ano."-".$this->z01_d_dthabilitacao_mes."-".$this->z01_d_dthabilitacao_dia;
         }
       }
       if($this->z01_d_dtemissao == ""){
         $this->z01_d_dtemissao_dia = ($this->z01_d_dtemissao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_dtemissao_dia"]:$this->z01_d_dtemissao_dia);
         $this->z01_d_dtemissao_mes = ($this->z01_d_dtemissao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_dtemissao_mes"]:$this->z01_d_dtemissao_mes);
         $this->z01_d_dtemissao_ano = ($this->z01_d_dtemissao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_dtemissao_ano"]:$this->z01_d_dtemissao_ano);
         if($this->z01_d_dtemissao_dia != ""){
            $this->z01_d_dtemissao = $this->z01_d_dtemissao_ano."-".$this->z01_d_dtemissao_mes."-".$this->z01_d_dtemissao_dia;
         }
       }
       $this->z01_c_passivo = ($this->z01_c_passivo == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_passivo"]:$this->z01_c_passivo);
       $this->z01_c_bolsafamilia = ($this->z01_c_bolsafamilia == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_bolsafamilia"]:$this->z01_c_bolsafamilia);
       $this->z01_c_nis = ($this->z01_c_nis == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_nis"]:$this->z01_c_nis);
       if($this->z01_c_certidaodata == ""){
         $this->z01_c_certidaodata_dia = ($this->z01_c_certidaodata_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_certidaodata_dia"]:$this->z01_c_certidaodata_dia);
         $this->z01_c_certidaodata_mes = ($this->z01_c_certidaodata_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_certidaodata_mes"]:$this->z01_c_certidaodata_mes);
         $this->z01_c_certidaodata_ano = ($this->z01_c_certidaodata_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_certidaodata_ano"]:$this->z01_c_certidaodata_ano);
         if($this->z01_c_certidaodata_dia != ""){
            $this->z01_c_certidaodata = $this->z01_c_certidaodata_ano."-".$this->z01_c_certidaodata_mes."-".$this->z01_c_certidaodata_dia;
         }
       }
       $this->z01_c_certidaocart = ($this->z01_c_certidaocart == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_certidaocart"]:$this->z01_c_certidaocart);
       $this->z01_c_certidaofolha = ($this->z01_c_certidaofolha == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_certidaofolha"]:$this->z01_c_certidaofolha);
       $this->z01_c_certidaolivro = ($this->z01_c_certidaolivro == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_certidaolivro"]:$this->z01_c_certidaolivro);
       $this->z01_c_certidaotermo = ($this->z01_c_certidaotermo == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_certidaotermo"]:$this->z01_c_certidaotermo);
       $this->z01_c_certidaonum = ($this->z01_c_certidaonum == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_certidaonum"]:$this->z01_c_certidaonum);
       $this->z01_c_certidaotipo = ($this->z01_c_certidaotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_certidaotipo"]:$this->z01_c_certidaotipo);
       $this->z01_c_zona = ($this->z01_c_zona == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_zona"]:$this->z01_c_zona);
       $this->z01_c_transporte = ($this->z01_c_transporte == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_transporte"]:$this->z01_c_transporte);
       $this->z01_t_obs = ($this->z01_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_t_obs"]:$this->z01_t_obs);
       $this->z01_c_atendesp = ($this->z01_c_atendesp == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_atendesp"]:$this->z01_c_atendesp);
       $this->z01_c_emailresp = ($this->z01_c_emailresp == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_emailresp"]:$this->z01_c_emailresp);
       $this->z01_c_nomeresp = ($this->z01_c_nomeresp == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_nomeresp"]:$this->z01_c_nomeresp);
       $this->z01_c_naturalidade = ($this->z01_c_naturalidade == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_naturalidade"]:$this->z01_c_naturalidade);
       $this->z01_v_cxpostal = ($this->z01_v_cxpostal == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_cxpostal"]:$this->z01_v_cxpostal);
       $this->z01_c_raca = ($this->z01_c_raca == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_raca"]:$this->z01_c_raca);
       $this->z01_v_baicon = ($this->z01_v_baicon == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_baicon"]:$this->z01_v_baicon);
       $this->z01_i_familiamicroarea = ($this->z01_i_familiamicroarea == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_i_familiamicroarea"]:$this->z01_i_familiamicroarea);
       $this->z01_c_agencia = ($this->z01_c_agencia == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_agencia"]:$this->z01_c_agencia);
       $this->z01_c_conta = ($this->z01_c_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_conta"]:$this->z01_c_conta);
       $this->z01_c_banco = ($this->z01_c_banco == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_banco"]:$this->z01_c_banco);
       $this->z01_c_ufctps = ($this->z01_c_ufctps == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_ufctps"]:$this->z01_c_ufctps);
       if($this->z01_d_dtemissaoctps == ""){
         $this->z01_d_dtemissaoctps_dia = ($this->z01_d_dtemissaoctps_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_dtemissaoctps_dia"]:$this->z01_d_dtemissaoctps_dia);
         $this->z01_d_dtemissaoctps_mes = ($this->z01_d_dtemissaoctps_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_dtemissaoctps_mes"]:$this->z01_d_dtemissaoctps_mes);
         $this->z01_d_dtemissaoctps_ano = ($this->z01_d_dtemissaoctps_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_dtemissaoctps_ano"]:$this->z01_d_dtemissaoctps_ano);
         if($this->z01_d_dtemissaoctps_dia != ""){
            $this->z01_d_dtemissaoctps = $this->z01_d_dtemissaoctps_ano."-".$this->z01_d_dtemissaoctps_mes."-".$this->z01_d_dtemissaoctps_dia;
         }
       }
       $this->z01_c_seriectps = ($this->z01_c_seriectps == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_seriectps"]:$this->z01_c_seriectps);
       $this->z01_c_numctps = ($this->z01_c_numctps == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_numctps"]:$this->z01_c_numctps);
       $this->z01_c_ufident = ($this->z01_c_ufident == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_ufident"]:$this->z01_c_ufident);
       $this->z01_c_escolaridade = ($this->z01_c_escolaridade == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_escolaridade"]:$this->z01_c_escolaridade);
       $this->z01_c_pis = ($this->z01_c_pis == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_pis"]:$this->z01_c_pis);
       if($this->z01_d_datapais == ""){
         $this->z01_d_datapais_dia = ($this->z01_d_datapais_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_datapais_dia"]:$this->z01_d_datapais_dia);
         $this->z01_d_datapais_mes = ($this->z01_d_datapais_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_datapais_mes"]:$this->z01_d_datapais_mes);
         $this->z01_d_datapais_ano = ($this->z01_d_datapais_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_datapais_ano"]:$this->z01_d_datapais_ano);
         if($this->z01_d_datapais_dia != ""){
            $this->z01_d_datapais = $this->z01_d_datapais_ano."-".$this->z01_d_datapais_mes."-".$this->z01_d_datapais_dia;
         }
       }
       if($this->z01_d_dtemissaocnh == ""){
         $this->z01_d_dtemissaocnh_dia = ($this->z01_d_dtemissaocnh_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_dtemissaocnh_dia"]:$this->z01_d_dtemissaocnh_dia);
         $this->z01_d_dtemissaocnh_mes = ($this->z01_d_dtemissaocnh_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_dtemissaocnh_mes"]:$this->z01_d_dtemissaocnh_mes);
         $this->z01_d_dtemissaocnh_ano = ($this->z01_d_dtemissaocnh_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_dtemissaocnh_ano"]:$this->z01_d_dtemissaocnh_ano);
         if($this->z01_d_dtemissaocnh_dia != ""){
            $this->z01_d_dtemissaocnh = $this->z01_d_dtemissaocnh_ano."-".$this->z01_d_dtemissaocnh_mes."-".$this->z01_d_dtemissaocnh_dia;
         }
       }
       $this->z01_codigoibge = ($this->z01_codigoibge == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_codigoibge"]:$this->z01_codigoibge);
       $this->z01_i_cgm = ($this->z01_i_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_i_cgm"]:$this->z01_i_cgm);
       $this->z01_i_cge = ($this->z01_i_cge == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_i_cge"]:$this->z01_i_cge);
       $this->z01_i_cidadao = ($this->z01_i_cidadao == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_i_cidadao"]:$this->z01_i_cidadao);
       $this->z01_v_municnasc = ($this->z01_v_municnasc == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_municnasc"]:$this->z01_v_municnasc);
       $this->z01_v_ufnasc = ($this->z01_v_ufnasc == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_ufnasc"]:$this->z01_v_ufnasc);
       $this->z01_b_faleceu = ($this->z01_b_faleceu == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_b_faleceu"]:$this->z01_b_faleceu);
       $this->z01_d_falecimento_dia = ($this->z01_d_falecimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_falecimento_dia"]:$this->z01_d_falecimento_dia);
       $this->z01_d_falecimento_mes = ($this->z01_d_falecimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_falecimento_mes"]:$this->z01_d_falecimento_mes);
       $this->z01_d_falecimento_ano = ($this->z01_d_falecimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_d_falecimento_ano"]:$this->z01_d_falecimento_ano);
       if($this->z01_d_falecimento_dia != ""){
         $this->z01_d_falecimento = $this->z01_d_falecimento_ano."-".$this->z01_d_falecimento_mes."-".$this->z01_d_falecimento_dia;
       }
       $this->z01_b_descnomemae = ($this->z01_b_descnomemae == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_b_descnomemae"]:$this->z01_b_descnomemae);
       $this->z01_i_naturalidade = ($this->z01_i_naturalidade == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_i_naturalidade"]:$this->z01_i_naturalidade);
       $this->z01_i_paisorigem = ($this->z01_i_paisorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_i_paisorigem"]:$this->z01_i_paisorigem);
       $this->z01_i_escolaridade = ($this->z01_i_escolaridade == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_i_escolaridade"]:$this->z01_i_escolaridade);
       $this->z01_codigoibgenasc = ($this->z01_codigoibgenasc == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_codigoibgenasc"]:$this->z01_codigoibgenasc);
       $this->z01_i_codocupacao = ($this->z01_i_codocupacao == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_i_codocupacao"]:$this->z01_i_codocupacao);
       $this->z01_b_inativo = ($this->z01_b_inativo == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_b_inativo"]:$this->z01_b_inativo);
       $this->z01_orgaoemissoridentidade = ($this->z01_orgaoemissoridentidade == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_orgaoemissoridentidade"]:$this->z01_orgaoemissoridentidade);
       $this->z01_registromunicipio = ($this->z01_registromunicipio == "f"?@$GLOBALS["HTTP_POST_VARS"]["z01_registromunicipio"]:$this->z01_registromunicipio);
     }else{
       $this->z01_i_cgsund = ($this->z01_i_cgsund == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_i_cgsund"]:$this->z01_i_cgsund);
     }
   }
   // funcao para Inclusão
   function incluir ($z01_i_cgsund){
      $this->atualizacampos();
     if($this->z01_v_cgccpf == null ){
       $this->z01_v_cgccpf = "'||null||'";
     }
     if($this->z01_v_nome == null ){
       $this->erro_sql = " Campo Nome não informado.";
       $this->erro_campo = "z01_v_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z01_v_ender == null ){
       $this->erro_sql = " Campo Endereço não informado.";
       $this->erro_campo = "z01_v_ender";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z01_i_numero == null ){
       $this->z01_i_numero = "0";
     }
     if($this->z01_v_bairro == null ){
       $this->erro_sql = " Campo Bairro não informado.";
       $this->erro_campo = "z01_v_bairro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z01_v_munic == null ){
       $this->erro_sql = " Campo Municipio não informado.";
       $this->erro_campo = "z01_v_munic";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z01_v_uf == null ){
       $this->erro_sql = " Campo UF não informado.";
       $this->erro_campo = "z01_v_uf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z01_d_cadast == null ){
       $this->erro_sql = " Campo Cadastro não informado.";
       $this->erro_campo = "z01_d_cadast_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z01_i_login == null ){
       $this->erro_sql = " Campo Login não informado.";
       $this->erro_campo = "z01_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z01_d_nasc == null ){
       $this->erro_sql = " Campo Nascimento não informado.";
       $this->erro_campo = "z01_d_nasc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z01_v_sexo == null ){
       $this->erro_sql = " Campo Sexo não informado.";
       $this->erro_campo = "z01_v_sexo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z01_o_oid == null ){
       $this->z01_o_oid = "null";
     }
     if($this->z01_v_mae == null && $this->z01_b_descnomemae == null){
       $this->erro_sql = " Campo Mãe não informado.";
       $this->erro_campo = "z01_v_mae";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z01_i_numcon == null ){
       $this->z01_i_numcon = "0";
     }
     if($this->z01_i_nacion == null ){
       $this->z01_i_nacion = "0";
     }
     if($this->z01_i_estciv == null ){
       $this->z01_i_estciv = "0";
     }
     if($this->z01_d_ultalt == null ){
       $this->z01_d_ultalt = "null";
     }
     if($this->z01_d_dtvencimento == null ){
       $this->z01_d_dtvencimento = "null";
     }
     if($this->z01_d_dthabilitacao == null ){
       $this->z01_d_dthabilitacao = "null";
     }
     if($this->z01_d_dtemissao == null ){
       $this->z01_d_dtemissao = "null";
     }
     if($this->z01_c_certidaodata == null ){
       $this->z01_c_certidaodata = "null";
     }
     if($this->z01_i_familiamicroarea == null ){
       $this->z01_i_familiamicroarea = "null";
     }
     if($this->z01_d_dtemissaoctps == null ){
       $this->z01_d_dtemissaoctps = "null";
     }
     if($this->z01_d_datapais == null ){
       $this->z01_d_datapais = "null";
     }
     if($this->z01_d_dtemissaocnh == null ){
       $this->z01_d_dtemissaocnh = "null";
     }
     if($this->z01_i_cgm == null ){
       $this->z01_i_cgm = "null";
     }
     if($this->z01_i_cge == null ){
       $this->z01_i_cge = "null";
     }
     if($this->z01_i_cidadao == null ){
       $this->z01_i_cidadao = "null";
     }

     if($this->z01_v_municnasc == null && $this->z01_i_naturalidade == 0 ){

       $this->erro_sql = " Campo Municipio de nascimento não informado.";
       $this->erro_campo = "z01_v_municnasc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->z01_v_ufnasc == null && $this->z01_i_naturalidade == 0 ){

       $this->erro_sql = " Campo UF de nascimento não informado.";
       $this->erro_campo = "z01_v_ufnasc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z01_b_faleceu == 't' && $this->z01_d_falecimento == null ){

       $this->erro_sql = " Campo data do falecimento não informado.";
       $this->erro_campo = "z01_d_nasc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z01_i_naturalidade == null ){
       $this->z01_i_naturalidade = "0";
     }
     if($this->z01_i_naturalidade > 0 && $this->z01_i_paisorigem == null){

       $this->erro_sql = " Campo país de origem não informado.";
       $this->erro_campo = "z01_i_paisorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z01_i_paisorigem == null) {
       $this->z01_i_paisorigem = 'null';
     }
     if($this->z01_i_naturalidade > 0) {
       $this->z01_codigoibgenasc = "";
       $this->z01_v_municnasc = "";
     }
     if(empty($this->z01_i_escolaridade)) {
       $this->z01_i_escolaridade = 'null';
     }

     if($this->z01_b_inativo == null ){
       $this->z01_b_inativo = "null";
     }

     if( $this->z01_v_email == 'null' ) {
       $this->z01_v_email = '';
     }

     if( $this->z01_v_telcel == 'null' ) {
       $this->z01_v_telcel = '';
     }

     if( $this->z01_v_fax == 'null' ) {
       $this->z01_v_fax = '';
     }

     if($this->z01_registromunicipio == null ){
       $this->erro_sql = " Campo CGS do Município não informado.";
       $this->erro_campo = "z01_registromunicipio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

       $this->z01_i_cgsund = $z01_i_cgsund;
     if(($this->z01_i_cgsund == null) || ($this->z01_i_cgsund == "") ){
       $this->erro_sql = " Campo z01_i_cgsund não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cgs_und(
                                       z01_i_cgsund
                                      ,z01_v_cgccpf
                                      ,z01_v_nome
                                      ,z01_v_ender
                                      ,z01_i_numero
                                      ,z01_v_compl
                                      ,z01_v_bairro
                                      ,z01_v_munic
                                      ,z01_v_uf
                                      ,z01_v_cep
                                      ,z01_d_cadast
                                      ,z01_v_telef
                                      ,z01_v_ident
                                      ,z01_i_login
                                      ,z01_v_telcel
                                      ,z01_v_email
                                      ,z01_d_nasc
                                      ,z01_v_sexo
                                      ,z01_o_oid
                                      ,z01_c_foto
                                      ,z01_v_ufcon
                                      ,z01_v_telcon
                                      ,z01_v_profis
                                      ,z01_v_pai
                                      ,z01_v_muncon
                                      ,z01_v_mae
                                      ,z01_v_hora
                                      ,z01_v_fax
                                      ,z01_v_endcon
                                      ,z01_v_emailc
                                      ,z01_v_cxposcon
                                      ,z01_v_contato
                                      ,z01_v_comcon
                                      ,z01_v_cnh
                                      ,z01_v_cepcon
                                      ,z01_v_celcon
                                      ,z01_v_categoria
                                      ,z01_i_numcon
                                      ,z01_i_nacion
                                      ,z01_i_estciv
                                      ,z01_d_ultalt
                                      ,z01_d_dtvencimento
                                      ,z01_d_dthabilitacao
                                      ,z01_d_dtemissao
                                      ,z01_c_passivo
                                      ,z01_c_bolsafamilia
                                      ,z01_c_nis
                                      ,z01_c_certidaodata
                                      ,z01_c_certidaocart
                                      ,z01_c_certidaofolha
                                      ,z01_c_certidaolivro
                                      ,z01_c_certidaotermo
                                      ,z01_c_certidaonum
                                      ,z01_c_certidaotipo
                                      ,z01_c_zona
                                      ,z01_c_transporte
                                      ,z01_t_obs
                                      ,z01_c_atendesp
                                      ,z01_c_emailresp
                                      ,z01_c_nomeresp
                                      ,z01_c_naturalidade
                                      ,z01_v_cxpostal
                                      ,z01_c_raca
                                      ,z01_v_baicon
                                      ,z01_i_familiamicroarea
                                      ,z01_c_agencia
                                      ,z01_c_conta
                                      ,z01_c_banco
                                      ,z01_c_ufctps
                                      ,z01_d_dtemissaoctps
                                      ,z01_c_seriectps
                                      ,z01_c_numctps
                                      ,z01_c_ufident
                                      ,z01_c_escolaridade
                                      ,z01_c_pis
                                      ,z01_d_datapais
                                      ,z01_d_dtemissaocnh
                                      ,z01_codigoibge
                                      ,z01_orgaoemissoridentidade
                                      ,z01_registromunicipio
                       )
                values (
                                $this->z01_i_cgsund
                               ,'$this->z01_v_cgccpf'
                               ,'$this->z01_v_nome'
                               ,'$this->z01_v_ender'
                               ,$this->z01_i_numero
                               ,'$this->z01_v_compl'
                               ,'$this->z01_v_bairro'
                               ,'$this->z01_v_munic'
                               ,'$this->z01_v_uf'
                               ,'$this->z01_v_cep'
                               ,".($this->z01_d_cadast == "null" || $this->z01_d_cadast == ""?"null":"'".$this->z01_d_cadast."'")."
                               ,'$this->z01_v_telef'
                               ,'$this->z01_v_ident'
                               ,$this->z01_i_login
                               ,'$this->z01_v_telcel'
                               ,'$this->z01_v_email'
                               ,".($this->z01_d_nasc == "null" || $this->z01_d_nasc == ""?"null":"'".$this->z01_d_nasc."'")."
                               ,'$this->z01_v_sexo'
                               ,$this->z01_o_oid
                               ,'$this->z01_c_foto'
                               ,'$this->z01_v_ufcon'
                               ,'$this->z01_v_telcon'
                               ,'$this->z01_v_profis'
                               ,'$this->z01_v_pai'
                               ,'$this->z01_v_muncon'
                               ,'$this->z01_v_mae'
                               ,'$this->z01_v_hora'
                               ,'$this->z01_v_fax'
                               ,'$this->z01_v_endcon'
                               ,'$this->z01_v_emailc'
                               ,'$this->z01_v_cxposcon'
                               ,'$this->z01_v_contato'
                               ,'$this->z01_v_comcon'
                               ,'$this->z01_v_cnh'
                               ,'$this->z01_v_cepcon'
                               ,'$this->z01_v_celcon'
                               ,'$this->z01_v_categoria'
                               ,$this->z01_i_numcon
                               ,$this->z01_i_nacion
                               ,$this->z01_i_estciv
                               ,".($this->z01_d_ultalt == "null" || $this->z01_d_ultalt == ""?"null":"'".$this->z01_d_ultalt."'")."
                               ,".($this->z01_d_dtvencimento == "null" || $this->z01_d_dtvencimento == ""?"null":"'".$this->z01_d_dtvencimento."'")."
                               ,".($this->z01_d_dthabilitacao == "null" || $this->z01_d_dthabilitacao == ""?"null":"'".$this->z01_d_dthabilitacao."'")."
                               ,".($this->z01_d_dtemissao == "null" || $this->z01_d_dtemissao == ""?"null":"'".$this->z01_d_dtemissao."'")."
                               ,'$this->z01_c_passivo'
                               ,'$this->z01_c_bolsafamilia'
                               ,'$this->z01_c_nis'
                               ,".($this->z01_c_certidaodata == "null" || $this->z01_c_certidaodata == ""?"null":"'".$this->z01_c_certidaodata."'")."
                               ,'$this->z01_c_certidaocart'
                               ,'$this->z01_c_certidaofolha'
                               ,'$this->z01_c_certidaolivro'
                               ,'$this->z01_c_certidaotermo'
                               ,'$this->z01_c_certidaonum'
                               ,'$this->z01_c_certidaotipo'
                               ,'$this->z01_c_zona'
                               ,'$this->z01_c_transporte'
                               ,'$this->z01_t_obs'
                               ,'$this->z01_c_atendesp'
                               ,'$this->z01_c_emailresp'
                               ,'$this->z01_c_nomeresp'
                               ,'$this->z01_c_naturalidade'
                               ,'$this->z01_v_cxpostal'
                               ,'$this->z01_c_raca'
                               ,'$this->z01_v_baicon'
                               ,$this->z01_i_familiamicroarea
                               ,'$this->z01_c_agencia'
                               ,'$this->z01_c_conta'
                               ,'$this->z01_c_banco'
                               ,'$this->z01_c_ufctps'
                               ,".($this->z01_d_dtemissaoctps == "null" || $this->z01_d_dtemissaoctps == ""?"null":"'".$this->z01_d_dtemissaoctps."'")."
                               ,'$this->z01_c_seriectps'
                               ,'$this->z01_c_numctps'
                               ,'$this->z01_c_ufident'
                               ,'$this->z01_c_escolaridade'
                               ,'$this->z01_c_pis'
                               ,".($this->z01_d_datapais == "null" || $this->z01_d_datapais == ""?"null":"'".$this->z01_d_datapais."'")."
                               ,".($this->z01_d_dtemissaocnh == "null" || $this->z01_d_dtemissaocnh == ""?"null":"'".$this->z01_d_dtemissaocnh."'")."
                               ,'$this->z01_codigoibge'
                               ,'$this->z01_orgaoemissoridentidade'
                               ,'$this->z01_registromunicipio'
                      )";

    $result = db_query($sql);
    if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cgs_und ($this->z01_i_cgsund) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cgs_und já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cgs_und ($this->z01_i_cgsund) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco  = "";
     $this->erro_sql    = "Inclusão efetuada com Sucesso\\n";
     $this->erro_sql   .= "Valores : ".$this->z01_i_cgsund;
     $this->erro_msg    = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);

    if ( empty($this->z01_d_falecimento) || $this->z01_d_falecimento == 'null') {
      $this->z01_d_falecimento = 'null';
    } else {
      $this->z01_d_falecimento = "'$this->z01_d_falecimento'";
    }

    $this->z01_b_faleceu = 'false';
    if ( $this->z01_d_falecimento != 'null' ) {
      $this->z01_b_faleceu = 'true';
    }

    $this->z01_b_descnomemae = $this->z01_v_mae == 'SEM INFORMAÇÃO' ? 'true' : 'false';

    $sql2 = "INSERT INTO cgs_und_ext (z01_i_cgsund,
                                      z01_b_faleceu,
                                      z01_d_falecimento,
                                      z01_b_descnomemae,
                                      z01_i_naturalidade,
                                      z01_i_paisorigem,
                                      z01_v_municnasc,
                                      z01_v_ufnasc,
                                      z01_codigoibgenasc,
                                      z01_i_escolaridade,
                                      z01_i_cgm,
                                      z01_i_cge,
                                      z01_i_cidadao,
                                      z01_b_inativo)
                          VALUES(
                                      $this->z01_i_cgsund,
                                      $this->z01_b_faleceu,
                                      {$this->z01_d_falecimento},
                                      $this->z01_b_descnomemae,
                                      '$this->z01_i_naturalidade',
                                      ".(empty($this->z01_i_paisorigem) || $this->z01_i_paisorigem == 'null' ? 'null' : "'".$this->z01_i_paisorigem."'").",
                                      ".(empty($this->z01_v_municnasc) || $this->z01_v_municnasc == 'null'  ? 'null' : "'".$this->z01_v_municnasc."'").",
                                      ".(empty($this->z01_v_ufnasc) || $this->z01_v_ufnasc == 'null' ? "''" : "'".$this->z01_v_ufnasc."'").",
                                      ".(empty($this->z01_codigoibgenasc) || $this->z01_codigoibgenasc == 'null' ? 'null' : "'".$this->z01_codigoibgenasc."'").",
                                      ".(empty($this->z01_i_escolaridade) || $this->z01_i_escolaridade == 'null' ? 'null' : "'".$this->z01_i_escolaridade."'").",
                                      ".(empty($this->z01_i_cgm) || $this->z01_i_cgm == 'null' ? 'null' : "'".$this->z01_i_cgm."'").",
                                      ".(empty($this->z01_i_cge) || $this->z01_i_cge == 'null' ? 'null' : "'".$this->z01_i_cge."'").",
                                      ".(empty($this->z01_i_cidadao) || $this->z01_i_cidadao == 'null' ? 'null' : "'".$this->z01_i_cidadao."'").",
                                      ".(empty($this->z01_b_inativo) ? 'false' : "'".$this->z01_b_inativo."'")."
                          )";

     $result2 = db_query($sql2);

     if($result2==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cgs_und_ext ($this->z01_i_cgsund) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cgs_und_ext já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cgs_und_ext ($this->z01_i_cgsund) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->z01_i_cgsund  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008844,'$this->z01_i_cgsund','I')");
         $resac = db_query("insert into db_acount values($acount,1010144,1008844,'','".AddSlashes(pg_result($resaco,0,'z01_i_cgsund'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,1008864,'','".AddSlashes(pg_result($resaco,0,'z01_v_cgccpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,1008845,'','".AddSlashes(pg_result($resaco,0,'z01_v_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,1008846,'','".AddSlashes(pg_result($resaco,0,'z01_v_ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,1008847,'','".AddSlashes(pg_result($resaco,0,'z01_i_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,1008848,'','".AddSlashes(pg_result($resaco,0,'z01_v_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,1008849,'','".AddSlashes(pg_result($resaco,0,'z01_v_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,1008850,'','".AddSlashes(pg_result($resaco,0,'z01_v_munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,1008851,'','".AddSlashes(pg_result($resaco,0,'z01_v_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,1008852,'','".AddSlashes(pg_result($resaco,0,'z01_v_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,1008853,'','".AddSlashes(pg_result($resaco,0,'z01_d_cadast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,1008854,'','".AddSlashes(pg_result($resaco,0,'z01_v_telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,1008855,'','".AddSlashes(pg_result($resaco,0,'z01_v_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,1008856,'','".AddSlashes(pg_result($resaco,0,'z01_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,1008857,'','".AddSlashes(pg_result($resaco,0,'z01_v_telcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,1008858,'','".AddSlashes(pg_result($resaco,0,'z01_v_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,1008859,'','".AddSlashes(pg_result($resaco,0,'z01_d_nasc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,1008860,'','".AddSlashes(pg_result($resaco,0,'z01_v_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11255,'','".AddSlashes(pg_result($resaco,0,'z01_o_oid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11254,'','".AddSlashes(pg_result($resaco,0,'z01_c_foto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11253,'','".AddSlashes(pg_result($resaco,0,'z01_v_ufcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11252,'','".AddSlashes(pg_result($resaco,0,'z01_v_telcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11251,'','".AddSlashes(pg_result($resaco,0,'z01_v_profis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11250,'','".AddSlashes(pg_result($resaco,0,'z01_v_pai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11249,'','".AddSlashes(pg_result($resaco,0,'z01_v_muncon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11248,'','".AddSlashes(pg_result($resaco,0,'z01_v_mae'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11247,'','".AddSlashes(pg_result($resaco,0,'z01_v_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11246,'','".AddSlashes(pg_result($resaco,0,'z01_v_fax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11245,'','".AddSlashes(pg_result($resaco,0,'z01_v_endcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11244,'','".AddSlashes(pg_result($resaco,0,'z01_v_emailc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11243,'','".AddSlashes(pg_result($resaco,0,'z01_v_cxposcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11241,'','".AddSlashes(pg_result($resaco,0,'z01_v_contato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11240,'','".AddSlashes(pg_result($resaco,0,'z01_v_comcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11239,'','".AddSlashes(pg_result($resaco,0,'z01_v_cnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11238,'','".AddSlashes(pg_result($resaco,0,'z01_v_cepcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11237,'','".AddSlashes(pg_result($resaco,0,'z01_v_celcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11236,'','".AddSlashes(pg_result($resaco,0,'z01_v_categoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11235,'','".AddSlashes(pg_result($resaco,0,'z01_i_numcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11234,'','".AddSlashes(pg_result($resaco,0,'z01_i_nacion'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11233,'','".AddSlashes(pg_result($resaco,0,'z01_i_estciv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11232,'','".AddSlashes(pg_result($resaco,0,'z01_d_ultalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11230,'','".AddSlashes(pg_result($resaco,0,'z01_d_dtvencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11229,'','".AddSlashes(pg_result($resaco,0,'z01_d_dthabilitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11228,'','".AddSlashes(pg_result($resaco,0,'z01_d_dtemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11227,'','".AddSlashes(pg_result($resaco,0,'z01_c_passivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11226,'','".AddSlashes(pg_result($resaco,0,'z01_c_bolsafamilia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11225,'','".AddSlashes(pg_result($resaco,0,'z01_c_nis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11224,'','".AddSlashes(pg_result($resaco,0,'z01_c_certidaodata'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11223,'','".AddSlashes(pg_result($resaco,0,'z01_c_certidaocart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11222,'','".AddSlashes(pg_result($resaco,0,'z01_c_certidaofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11221,'','".AddSlashes(pg_result($resaco,0,'z01_c_certidaolivro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11696,'','".AddSlashes(pg_result($resaco,0,'z01_c_certidaotermo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11220,'','".AddSlashes(pg_result($resaco,0,'z01_c_certidaonum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11219,'','".AddSlashes(pg_result($resaco,0,'z01_c_certidaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11218,'','".AddSlashes(pg_result($resaco,0,'z01_c_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11217,'','".AddSlashes(pg_result($resaco,0,'z01_c_transporte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11216,'','".AddSlashes(pg_result($resaco,0,'z01_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11215,'','".AddSlashes(pg_result($resaco,0,'z01_c_atendesp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11214,'','".AddSlashes(pg_result($resaco,0,'z01_c_emailresp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11213,'','".AddSlashes(pg_result($resaco,0,'z01_c_nomeresp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11212,'','".AddSlashes(pg_result($resaco,0,'z01_c_naturalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11209,'','".AddSlashes(pg_result($resaco,0,'z01_v_cxpostal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11208,'','".AddSlashes(pg_result($resaco,0,'z01_c_raca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11256,'','".AddSlashes(pg_result($resaco,0,'z01_v_baicon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11314,'','".AddSlashes(pg_result($resaco,0,'z01_i_familiamicroarea'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11663,'','".AddSlashes(pg_result($resaco,0,'z01_c_agencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11662,'','".AddSlashes(pg_result($resaco,0,'z01_c_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11661,'','".AddSlashes(pg_result($resaco,0,'z01_c_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11660,'','".AddSlashes(pg_result($resaco,0,'z01_c_ufctps'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11659,'','".AddSlashes(pg_result($resaco,0,'z01_d_dtemissaoctps'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11658,'','".AddSlashes(pg_result($resaco,0,'z01_c_seriectps'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11657,'','".AddSlashes(pg_result($resaco,0,'z01_c_numctps'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11656,'','".AddSlashes(pg_result($resaco,0,'z01_c_ufident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11655,'','".AddSlashes(pg_result($resaco,0,'z01_c_escolaridade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11654,'','".AddSlashes(pg_result($resaco,0,'z01_c_pis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,11664,'','".AddSlashes(pg_result($resaco,0,'z01_d_datapais'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,16033,'','".AddSlashes(pg_result($resaco,0,'z01_d_dtemissaocnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,20729,'','".AddSlashes(pg_result($resaco,0,'z01_codigoibge'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,21901,'','".AddSlashes(pg_result($resaco,0,'z01_orgaoemissoridentidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010144,21987,'','".AddSlashes(pg_result($resaco,0,'z01_registromunicipio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($z01_i_cgsund=null) {
      $this->atualizacampos();
     $sql = " update cgs_und set ";
     $virgula = "";
     if(trim($this->z01_i_cgsund)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_i_cgsund"])){
       $sql  .= $virgula." z01_i_cgsund = $this->z01_i_cgsund ";
       $virgula = ",";
       if(trim($this->z01_i_cgsund) == null ){
         $this->erro_sql = " Campo CGS não informado.";
         $this->erro_campo = "z01_i_cgsund";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z01_v_cgccpf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_cgccpf"])){

       if( trim($this->z01_v_cgccpf) == 'null' ) {
         $sql .= $virgula . " z01_v_cgccpf = '' ";
       } else {
       $sql  .= $virgula." z01_v_cgccpf = '$this->z01_v_cgccpf' ";
       }

       $virgula = ",";
     }
     if(trim($this->z01_v_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_nome"])){
       $sql  .= $virgula." z01_v_nome = '$this->z01_v_nome' ";
       $virgula = ",";
       if(trim($this->z01_v_nome) == null ){
         $this->erro_sql = " Campo Nome não informado.";
         $this->erro_campo = "z01_v_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z01_v_ender)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_ender"])){
       $sql  .= $virgula." z01_v_ender = '$this->z01_v_ender' ";
       $virgula = ",";
       if(trim($this->z01_v_ender) == null ){
         $this->erro_sql = " Campo Endereço não informado.";
         $this->erro_campo = "z01_v_ender";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z01_i_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_i_numero"])){
        if(trim($this->z01_i_numero)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z01_i_numero"])){
           $this->z01_i_numero = "0" ;
        }

       if( trim($this->z01_i_numero) == 'null' ) {
         $sql .= $virgula . " z01_i_numero = 0 ";
       } else {
       $sql  .= $virgula." z01_i_numero = $this->z01_i_numero ";
       }

       $virgula = ",";
     }
     if(trim($this->z01_v_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_compl"])){

       if( trim($this->z01_v_compl) == 'null' ) {
         $sql .= $virgula . " z01_v_compl = '' ";
       } else {
       $sql  .= $virgula." z01_v_compl = '$this->z01_v_compl' ";
       }

       $virgula = ",";
     }
     if(trim($this->z01_v_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_bairro"])){
       $sql  .= $virgula." z01_v_bairro = '$this->z01_v_bairro' ";
       $virgula = ",";
       if(trim($this->z01_v_bairro) == null ){
         $this->erro_sql = " Campo Bairro não informado.";
         $this->erro_campo = "z01_v_bairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z01_v_munic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_munic"])){
       $sql  .= $virgula." z01_v_munic = '$this->z01_v_munic' ";
       $virgula = ",";
       if(trim($this->z01_v_munic) == null ){
         $this->erro_sql = " Campo Municipio não informado.";
         $this->erro_campo = "z01_v_munic";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z01_v_uf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_uf"])){
       $sql  .= $virgula." z01_v_uf = '$this->z01_v_uf' ";
       $virgula = ",";
       if(trim($this->z01_v_uf) == null ){
         $this->erro_sql = " Campo UF não informado.";
         $this->erro_campo = "z01_v_uf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z01_v_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_cep"])){

       if( trim($this->z01_v_cep) == 'null' ) {
         $sql .= $virgula . " z01_v_cep = '' ";
       } else {
         $sql  .= $virgula." z01_v_cep = '$this->z01_v_cep' ";
       }

       $virgula = ",";
     }
     if(trim($this->z01_d_cadast)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_d_cadast_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z01_d_cadast_dia"] !="") ){
       $sql  .= $virgula." z01_d_cadast = '$this->z01_d_cadast' ";
       $virgula = ",";
       if(trim($this->z01_d_cadast) == null ){
         $this->erro_sql = " Campo Cadastro não informado.";
         $this->erro_campo = "z01_d_cadast_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["z01_d_cadast_dia"])){
         $sql  .= $virgula." z01_d_cadast = null ";
         $virgula = ",";
         if(trim($this->z01_d_cadast) == null ){
           $this->erro_sql = " Campo Cadastro não informado.";
           $this->erro_campo = "z01_d_cadast_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->z01_v_telef)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_telef"])){

       if( trim($this->z01_v_telef) == 'null' ) {
         $sql .= $virgula . " z01_v_telef = '' ";
       } else {
       $sql  .= $virgula." z01_v_telef = '$this->z01_v_telef' ";
       }

       $virgula = ",";
     }
     if(trim($this->z01_v_ident)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_ident"])){

       if( trim($this->z01_v_ident) == 'null' ) {
         $sql .= $virgula . " z01_v_ident = '' ";
       } else {
         $sql  .= $virgula." z01_v_ident = '$this->z01_v_ident' ";
       }

       $virgula = ",";
     }
     if(trim($this->z01_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_i_login"])){
       $sql  .= $virgula." z01_i_login = $this->z01_i_login ";
       $virgula = ",";
       if(trim($this->z01_i_login) == null ){
         $this->erro_sql = " Campo Login não informado.";
         $this->erro_campo = "z01_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z01_v_telcel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_telcel"])){

       if( trim($this->z01_v_telcel) == 'null' ) {
         $sql .= $virgula . " z01_v_telcel = '' ";
       } else {
         $sql  .= $virgula." z01_v_telcel = '$this->z01_v_telcel' ";
       }

       $virgula = ",";
     }
     if(trim($this->z01_v_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_email"])){

       if( trim($this->z01_v_email) == 'null' ) {
         $sql .= $virgula . " z01_v_email = '' ";
       } else {
         $sql  .= $virgula." z01_v_email = '$this->z01_v_email' ";
       }

       $virgula = ",";
     }
     if(trim($this->z01_d_nasc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_d_nasc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z01_d_nasc_dia"] !="") ){
       $sql  .= $virgula." z01_d_nasc = '$this->z01_d_nasc' ";
       $virgula = ",";
       if(trim($this->z01_d_nasc) == null ){
         $this->erro_sql = " Campo Nascimento não informado.";
         $this->erro_campo = "z01_d_nasc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["z01_d_nasc_dia"])){
         $sql  .= $virgula." z01_d_nasc = null ";
         $virgula = ",";
         if(trim($this->z01_d_nasc) == null ){
           $this->erro_sql = " Campo Nascimento não informado.";
           $this->erro_campo = "z01_d_nasc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->z01_v_sexo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_sexo"])){
       $sql  .= $virgula." z01_v_sexo = '$this->z01_v_sexo' ";
       $virgula = ",";
       if(trim($this->z01_v_sexo) == null ){
         $this->erro_sql = " Campo Sexo não informado.";
         $this->erro_campo = "z01_v_sexo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z01_o_oid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_o_oid"])){
       $sql  .= $virgula." z01_o_oid = $this->z01_o_oid ";
       $virgula = ",";
     }
     if(trim($this->z01_c_foto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_foto"])){

       if( $this->z01_c_foto == 'null' ) {
         $this->z01_c_foto = '';
       }

       $sql  .= $virgula." z01_c_foto = '$this->z01_c_foto' ";
       $virgula = ",";
     }
     if(trim($this->z01_v_ufcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_ufcon"])){
       $sql  .= $virgula." z01_v_ufcon = '$this->z01_v_ufcon' ";
       $virgula = ",";
     }
     if(trim($this->z01_v_telcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_telcon"])){
       $sql  .= $virgula." z01_v_telcon = '$this->z01_v_telcon' ";
       $virgula = ",";
     }
     if(trim($this->z01_v_profis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_profis"])){
       $sql  .= $virgula." z01_v_profis = '$this->z01_v_profis' ";
       $virgula = ",";
     }
     if(trim($this->z01_v_pai)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_pai"])){

       if( trim($this->z01_v_pai) == 'null' ) {
         $sql .= $virgula . " z01_v_pai = '' ";
       } else {
         $sql  .= $virgula." z01_v_pai = '$this->z01_v_pai' ";
       }

       $virgula = ",";
     }
     if(trim($this->z01_v_muncon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_muncon"])){
       $sql  .= $virgula." z01_v_muncon = '$this->z01_v_muncon' ";
       $virgula = ",";
     }

     $sSqlMae = "";
     if(trim($this->z01_v_mae)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_mae"])){

       $sSqlMae = $virgula . " z01_v_mae = '$this->z01_v_mae' ";
       $virgula = ",";

       if(trim($this->z01_v_mae) == null && $this->z01_b_descnomemae == null){

         $this->erro_sql = " Campo Mãe não informado.";
         $this->erro_campo = "z01_v_mae";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

     $this->z01_b_descnomemae = $this->z01_v_mae == 'SEM INFORMAÇÃO' ? 'true' : 'false';

     $sql .= $sSqlMae;

     if(trim($this->z01_v_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_hora"])){
       $sql  .= $virgula." z01_v_hora = '$this->z01_v_hora' ";
       $virgula = ",";
     }
     if(trim($this->z01_v_fax)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_fax"])){

       $sWhere = $virgula." z01_v_fax = '$this->z01_v_fax' ";

       if( trim( $this->z01_v_fax ) == 'null' ) {
         $sWhere = $virgula." z01_v_fax = '' ";
       }

       $sql    .= $sWhere;
       $virgula = ",";
     }
     if(trim($this->z01_v_endcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_endcon"])){
       $sql  .= $virgula." z01_v_endcon = '$this->z01_v_endcon' ";
       $virgula = ",";
     }
     if(trim($this->z01_v_emailc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_emailc"])){
       $sql  .= $virgula." z01_v_emailc = '$this->z01_v_emailc' ";
       $virgula = ",";
     }
     if(trim($this->z01_v_cxposcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_cxposcon"])){
       $sql  .= $virgula." z01_v_cxposcon = '$this->z01_v_cxposcon' ";
       $virgula = ",";
     }
     if(trim($this->z01_v_contato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_contato"])){
       $sql  .= $virgula." z01_v_contato = '$this->z01_v_contato' ";
       $virgula = ",";
     }
     if(trim($this->z01_v_comcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_comcon"])){
       $sql  .= $virgula." z01_v_comcon = '$this->z01_v_comcon' ";
       $virgula = ",";
     }
     if(trim($this->z01_v_cnh)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_cnh"])){
       $sql  .= $virgula." z01_v_cnh = '$this->z01_v_cnh' ";
       $virgula = ",";
     }
     if(trim($this->z01_v_cepcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_cepcon"])){
       $sql  .= $virgula." z01_v_cepcon = '$this->z01_v_cepcon' ";
       $virgula = ",";
     }
     if(trim($this->z01_v_celcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_celcon"])){
       $sql  .= $virgula." z01_v_celcon = '$this->z01_v_celcon' ";
       $virgula = ",";
     }
     if(trim($this->z01_v_categoria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_categoria"])){
       $sql  .= $virgula." z01_v_categoria = '$this->z01_v_categoria' ";
       $virgula = ",";
     }
     if(trim($this->z01_i_numcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_i_numcon"])){
        if(trim($this->z01_i_numcon)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z01_i_numcon"])){
           $this->z01_i_numcon = "0" ;
        }
       $sql  .= $virgula." z01_i_numcon = $this->z01_i_numcon ";
       $virgula = ",";
     }
     if(trim($this->z01_i_nacion)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_i_nacion"])){
        if(trim($this->z01_i_nacion)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z01_i_nacion"])){
           $this->z01_i_nacion = "0" ;
        }
       $sql  .= $virgula." z01_i_nacion = $this->z01_i_nacion ";
       $virgula = ",";
     }
     if(trim($this->z01_i_estciv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_i_estciv"])){
        if(trim($this->z01_i_estciv)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z01_i_estciv"])){
           $this->z01_i_estciv = "0" ;
        }
       $sql  .= $virgula." z01_i_estciv = $this->z01_i_estciv ";
       $virgula = ",";
     }
     if(trim($this->z01_d_ultalt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_d_ultalt_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z01_d_ultalt_dia"] !="") ){
       $sql  .= $virgula." z01_d_ultalt = '$this->z01_d_ultalt' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["z01_d_ultalt_dia"])){
         $sql  .= $virgula." z01_d_ultalt = null ";
         $virgula = ",";
       }
     }
     if(trim($this->z01_d_dtvencimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_d_dtvencimento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z01_d_dtvencimento_dia"] !="") ){
       $sql  .= $virgula." z01_d_dtvencimento = '$this->z01_d_dtvencimento' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["z01_d_dtvencimento_dia"])){
         $sql  .= $virgula." z01_d_dtvencimento = null ";
         $virgula = ",";
       }
     }
     if(trim($this->z01_d_dthabilitacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_d_dthabilitacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z01_d_dthabilitacao_dia"] !="") ){
       $sql  .= $virgula." z01_d_dthabilitacao = '$this->z01_d_dthabilitacao' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["z01_d_dthabilitacao_dia"])){
         $sql  .= $virgula." z01_d_dthabilitacao = null ";
         $virgula = ",";
       }
     }
     if(trim($this->z01_d_dtemissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_d_dtemissao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z01_d_dtemissao_dia"] !="") ){
       $sql  .= $virgula." z01_d_dtemissao = '$this->z01_d_dtemissao' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["z01_d_dtemissao_dia"])){
         $sql  .= $virgula." z01_d_dtemissao = null ";
         $virgula = ",";
       }
     }
     if(trim($this->z01_c_passivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_passivo"])){
       $sql  .= $virgula." z01_c_passivo = '$this->z01_c_passivo' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_bolsafamilia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_bolsafamilia"])){
       $sql  .= $virgula." z01_c_bolsafamilia = '$this->z01_c_bolsafamilia' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_nis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_nis"])){
       $sql  .= $virgula." z01_c_nis = '$this->z01_c_nis' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_certidaodata)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_certidaodata_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z01_c_certidaodata_dia"] !="") ){
       $sql  .= $virgula." z01_c_certidaodata = '$this->z01_c_certidaodata' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["z01_c_certidaodata_dia"])){
         $sql  .= $virgula." z01_c_certidaodata = null ";
         $virgula = ",";
       }
     }
     if(trim($this->z01_c_certidaocart)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_certidaocart"])){
       $sql  .= $virgula." z01_c_certidaocart = '$this->z01_c_certidaocart' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_certidaofolha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_certidaofolha"])){
       $sql  .= $virgula." z01_c_certidaofolha = '$this->z01_c_certidaofolha' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_certidaolivro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_certidaolivro"])){
       $sql  .= $virgula." z01_c_certidaolivro = '$this->z01_c_certidaolivro' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_certidaotermo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_certidaotermo"])){
       $sql  .= $virgula." z01_c_certidaotermo = '$this->z01_c_certidaotermo' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_certidaonum)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_certidaonum"])){
       $sql  .= $virgula." z01_c_certidaonum = '$this->z01_c_certidaonum' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_certidaotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_certidaotipo"])){
       $sql  .= $virgula." z01_c_certidaotipo = '$this->z01_c_certidaotipo' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_zona)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_zona"])){
       $sql  .= $virgula." z01_c_zona = '$this->z01_c_zona' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_transporte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_transporte"])){
       $sql  .= $virgula." z01_c_transporte = '$this->z01_c_transporte' ";
       $virgula = ",";
     }
     if(trim($this->z01_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_t_obs"])){
       $sql  .= $virgula." z01_t_obs = '$this->z01_t_obs' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_atendesp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_atendesp"])){
       $sql  .= $virgula." z01_c_atendesp = '$this->z01_c_atendesp' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_emailresp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_emailresp"])){
       $sql  .= $virgula." z01_c_emailresp = '$this->z01_c_emailresp' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_nomeresp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_nomeresp"])){
       $sql  .= $virgula." z01_c_nomeresp = '$this->z01_c_nomeresp' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_naturalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_naturalidade"])){
       $sql  .= $virgula." z01_c_naturalidade = '$this->z01_c_naturalidade' ";
       $virgula = ",";
     }
     if(trim($this->z01_v_cxpostal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_cxpostal"])){
       $sql  .= $virgula." z01_v_cxpostal = '$this->z01_v_cxpostal' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_raca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_raca"])){
       $sql  .= $virgula." z01_c_raca = '$this->z01_c_raca' ";
       $virgula = ",";
     }
     if(trim($this->z01_v_baicon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_baicon"])){
       $sql  .= $virgula." z01_v_baicon = '$this->z01_v_baicon' ";
       $virgula = ",";
     }
     if(trim($this->z01_i_familiamicroarea)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_i_familiamicroarea"])){

       if(    ( trim($this->z01_i_familiamicroarea)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z01_i_familiamicroarea"]))
           || trim($this->z01_i_familiamicroarea) == 'null'
         ){
         $this->z01_i_familiamicroarea = "null" ;
       }
       $sql  .= $virgula." z01_i_familiamicroarea = $this->z01_i_familiamicroarea ";
       $virgula = ",";
     }
     if(trim($this->z01_c_agencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_agencia"])){
       $sql  .= $virgula." z01_c_agencia = '$this->z01_c_agencia' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_conta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_conta"])){
       $sql  .= $virgula." z01_c_conta = '$this->z01_c_conta' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_banco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_banco"])){
       $sql  .= $virgula." z01_c_banco = '$this->z01_c_banco' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_ufctps)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_ufctps"])){
       $sql  .= $virgula." z01_c_ufctps = '$this->z01_c_ufctps' ";
       $virgula = ",";
     }
     if(trim($this->z01_d_dtemissaoctps)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_d_dtemissaoctps_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z01_d_dtemissaoctps_dia"] !="") ){
       $sql  .= $virgula." z01_d_dtemissaoctps = '$this->z01_d_dtemissaoctps' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["z01_d_dtemissaoctps_dia"])){
         $sql  .= $virgula." z01_d_dtemissaoctps = null ";
         $virgula = ",";
       }
     }
     if(trim($this->z01_c_seriectps)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_seriectps"])){
       $sql  .= $virgula." z01_c_seriectps = '$this->z01_c_seriectps' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_numctps)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_numctps"])){
       $sql  .= $virgula." z01_c_numctps = '$this->z01_c_numctps' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_ufident)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_ufident"])){
       $sql  .= $virgula." z01_c_ufident = '$this->z01_c_ufident' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_escolaridade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_escolaridade"])){
       $sql  .= $virgula." z01_c_escolaridade = '$this->z01_c_escolaridade' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_pis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_pis"])){
       $sql  .= $virgula." z01_c_pis = '$this->z01_c_pis' ";
       $virgula = ",";
     }
     if(trim($this->z01_d_datapais)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_d_datapais_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z01_d_datapais_dia"] !="") ){
       $sql  .= $virgula." z01_d_datapais = '$this->z01_d_datapais' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["z01_d_datapais_dia"])){
         $sql  .= $virgula." z01_d_datapais = null ";
         $virgula = ",";
       }
     }
     if(trim($this->z01_d_dtemissaocnh)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_d_dtemissaocnh_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z01_d_dtemissaocnh_dia"] !="") ){
       $sql  .= $virgula." z01_d_dtemissaocnh = '$this->z01_d_dtemissaocnh' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["z01_d_dtemissaocnh_dia"])){
         $sql  .= $virgula." z01_d_dtemissaocnh = null ";
         $virgula = ",";
       }
     }
     if(!empty($this->z01_codigoibge)){
       $sql  .= $virgula." z01_codigoibge = '$this->z01_codigoibge' ";
       $virgula = ",";
     }
     if(trim($this->z01_orgaoemissoridentidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_orgaoemissoridentidade"])){
       $sql  .= $virgula." z01_orgaoemissoridentidade = '$this->z01_orgaoemissoridentidade' ";
       $virgula = ",";
     }
     if(trim($this->z01_registromunicipio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_registromunicipio"])){
       $sql  .= $virgula." z01_registromunicipio = '$this->z01_registromunicipio' ";
       $virgula = ",";
       if(trim($this->z01_registromunicipio) == null ){
         $this->erro_sql = " Campo CGS do Município não informado.";
         $this->erro_campo = "z01_registromunicipio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($z01_i_cgsund!=null){
       $sql .= " z01_i_cgsund = $this->z01_i_cgsund";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->z01_i_cgsund));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008844,'$this->z01_i_cgsund','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_i_cgsund"]) || $this->z01_i_cgsund != "")
             $resac = db_query("insert into db_acount values($acount,1010144,1008844,'".AddSlashes(pg_result($resaco,$conresaco,'z01_i_cgsund'))."','$this->z01_i_cgsund',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_cgccpf"]) || $this->z01_v_cgccpf != "")
             $resac = db_query("insert into db_acount values($acount,1010144,1008864,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_cgccpf'))."','$this->z01_v_cgccpf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_nome"]) || $this->z01_v_nome != "")
             $resac = db_query("insert into db_acount values($acount,1010144,1008845,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_nome'))."','$this->z01_v_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_ender"]) || $this->z01_v_ender != "")
             $resac = db_query("insert into db_acount values($acount,1010144,1008846,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_ender'))."','$this->z01_v_ender',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_i_numero"]) || $this->z01_i_numero != "")
             $resac = db_query("insert into db_acount values($acount,1010144,1008847,'".AddSlashes(pg_result($resaco,$conresaco,'z01_i_numero'))."','$this->z01_i_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_compl"]) || $this->z01_v_compl != "")
             $resac = db_query("insert into db_acount values($acount,1010144,1008848,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_compl'))."','$this->z01_v_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_bairro"]) || $this->z01_v_bairro != "")
             $resac = db_query("insert into db_acount values($acount,1010144,1008849,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_bairro'))."','$this->z01_v_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_munic"]) || $this->z01_v_munic != "")
             $resac = db_query("insert into db_acount values($acount,1010144,1008850,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_munic'))."','$this->z01_v_munic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_uf"]) || $this->z01_v_uf != "")
             $resac = db_query("insert into db_acount values($acount,1010144,1008851,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_uf'))."','$this->z01_v_uf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_cep"]) || $this->z01_v_cep != "")
             $resac = db_query("insert into db_acount values($acount,1010144,1008852,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_cep'))."','$this->z01_v_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_d_cadast"]) || $this->z01_d_cadast != "")
             $resac = db_query("insert into db_acount values($acount,1010144,1008853,'".AddSlashes(pg_result($resaco,$conresaco,'z01_d_cadast'))."','$this->z01_d_cadast',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_telef"]) || $this->z01_v_telef != "")
             $resac = db_query("insert into db_acount values($acount,1010144,1008854,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_telef'))."','$this->z01_v_telef',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_ident"]) || $this->z01_v_ident != "")
             $resac = db_query("insert into db_acount values($acount,1010144,1008855,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_ident'))."','$this->z01_v_ident',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_i_login"]) || $this->z01_i_login != "")
             $resac = db_query("insert into db_acount values($acount,1010144,1008856,'".AddSlashes(pg_result($resaco,$conresaco,'z01_i_login'))."','$this->z01_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_telcel"]) || $this->z01_v_telcel != "")
             $resac = db_query("insert into db_acount values($acount,1010144,1008857,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_telcel'))."','$this->z01_v_telcel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_email"]) || $this->z01_v_email != "")
             $resac = db_query("insert into db_acount values($acount,1010144,1008858,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_email'))."','$this->z01_v_email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_d_nasc"]) || $this->z01_d_nasc != "")
             $resac = db_query("insert into db_acount values($acount,1010144,1008859,'".AddSlashes(pg_result($resaco,$conresaco,'z01_d_nasc'))."','$this->z01_d_nasc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_sexo"]) || $this->z01_v_sexo != "")
             $resac = db_query("insert into db_acount values($acount,1010144,1008860,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_sexo'))."','$this->z01_v_sexo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_o_oid"]) || $this->z01_o_oid != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11255,'".AddSlashes(pg_result($resaco,$conresaco,'z01_o_oid'))."','$this->z01_o_oid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_foto"]) || $this->z01_c_foto != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11254,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_foto'))."','$this->z01_c_foto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_ufcon"]) || $this->z01_v_ufcon != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11253,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_ufcon'))."','$this->z01_v_ufcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_telcon"]) || $this->z01_v_telcon != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11252,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_telcon'))."','$this->z01_v_telcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_profis"]) || $this->z01_v_profis != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11251,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_profis'))."','$this->z01_v_profis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_pai"]) || $this->z01_v_pai != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11250,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_pai'))."','$this->z01_v_pai',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_muncon"]) || $this->z01_v_muncon != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11249,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_muncon'))."','$this->z01_v_muncon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_mae"]) || $this->z01_v_mae != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11248,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_mae'))."','$this->z01_v_mae',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_hora"]) || $this->z01_v_hora != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11247,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_hora'))."','$this->z01_v_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_fax"]) || $this->z01_v_fax != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11246,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_fax'))."','$this->z01_v_fax',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_endcon"]) || $this->z01_v_endcon != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11245,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_endcon'))."','$this->z01_v_endcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_emailc"]) || $this->z01_v_emailc != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11244,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_emailc'))."','$this->z01_v_emailc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_cxposcon"]) || $this->z01_v_cxposcon != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11243,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_cxposcon'))."','$this->z01_v_cxposcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_contato"]) || $this->z01_v_contato != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11241,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_contato'))."','$this->z01_v_contato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_comcon"]) || $this->z01_v_comcon != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11240,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_comcon'))."','$this->z01_v_comcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_cnh"]) || $this->z01_v_cnh != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11239,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_cnh'))."','$this->z01_v_cnh',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_cepcon"]) || $this->z01_v_cepcon != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11238,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_cepcon'))."','$this->z01_v_cepcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_celcon"]) || $this->z01_v_celcon != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11237,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_celcon'))."','$this->z01_v_celcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_categoria"]) || $this->z01_v_categoria != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11236,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_categoria'))."','$this->z01_v_categoria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_i_numcon"]) || $this->z01_i_numcon != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11235,'".AddSlashes(pg_result($resaco,$conresaco,'z01_i_numcon'))."','$this->z01_i_numcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_i_nacion"]) || $this->z01_i_nacion != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11234,'".AddSlashes(pg_result($resaco,$conresaco,'z01_i_nacion'))."','$this->z01_i_nacion',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_i_estciv"]) || $this->z01_i_estciv != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11233,'".AddSlashes(pg_result($resaco,$conresaco,'z01_i_estciv'))."','$this->z01_i_estciv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_d_ultalt"]) || $this->z01_d_ultalt != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11232,'".AddSlashes(pg_result($resaco,$conresaco,'z01_d_ultalt'))."','$this->z01_d_ultalt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_d_dtvencimento"]) || $this->z01_d_dtvencimento != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11230,'".AddSlashes(pg_result($resaco,$conresaco,'z01_d_dtvencimento'))."','$this->z01_d_dtvencimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_d_dthabilitacao"]) || $this->z01_d_dthabilitacao != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11229,'".AddSlashes(pg_result($resaco,$conresaco,'z01_d_dthabilitacao'))."','$this->z01_d_dthabilitacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_d_dtemissao"]) || $this->z01_d_dtemissao != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11228,'".AddSlashes(pg_result($resaco,$conresaco,'z01_d_dtemissao'))."','$this->z01_d_dtemissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_passivo"]) || $this->z01_c_passivo != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11227,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_passivo'))."','$this->z01_c_passivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_bolsafamilia"]) || $this->z01_c_bolsafamilia != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11226,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_bolsafamilia'))."','$this->z01_c_bolsafamilia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_nis"]) || $this->z01_c_nis != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11225,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_nis'))."','$this->z01_c_nis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_certidaodata"]) || $this->z01_c_certidaodata != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11224,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_certidaodata'))."','$this->z01_c_certidaodata',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_certidaocart"]) || $this->z01_c_certidaocart != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11223,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_certidaocart'))."','$this->z01_c_certidaocart',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_certidaofolha"]) || $this->z01_c_certidaofolha != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11222,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_certidaofolha'))."','$this->z01_c_certidaofolha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_certidaolivro"]) || $this->z01_c_certidaolivro != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11221,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_certidaolivro'))."','$this->z01_c_certidaolivro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_certidaotermo"]) || $this->z01_c_certidaotermo != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11696,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_certidaotermo'))."','$this->z01_c_certidaotermo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_certidaonum"]) || $this->z01_c_certidaonum != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11220,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_certidaonum'))."','$this->z01_c_certidaonum',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_certidaotipo"]) || $this->z01_c_certidaotipo != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11219,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_certidaotipo'))."','$this->z01_c_certidaotipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_zona"]) || $this->z01_c_zona != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11218,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_zona'))."','$this->z01_c_zona',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_transporte"]) || $this->z01_c_transporte != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11217,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_transporte'))."','$this->z01_c_transporte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_t_obs"]) || $this->z01_t_obs != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11216,'".AddSlashes(pg_result($resaco,$conresaco,'z01_t_obs'))."','$this->z01_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_atendesp"]) || $this->z01_c_atendesp != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11215,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_atendesp'))."','$this->z01_c_atendesp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_emailresp"]) || $this->z01_c_emailresp != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11214,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_emailresp'))."','$this->z01_c_emailresp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_nomeresp"]) || $this->z01_c_nomeresp != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11213,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_nomeresp'))."','$this->z01_c_nomeresp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_naturalidade"]) || $this->z01_c_naturalidade != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11212,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_naturalidade'))."','$this->z01_c_naturalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_cxpostal"]) || $this->z01_v_cxpostal != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11209,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_cxpostal'))."','$this->z01_v_cxpostal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_raca"]) || $this->z01_c_raca != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11208,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_raca'))."','$this->z01_c_raca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_baicon"]) || $this->z01_v_baicon != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11256,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_baicon'))."','$this->z01_v_baicon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_i_familiamicroarea"]) || $this->z01_i_familiamicroarea != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11314,'".AddSlashes(pg_result($resaco,$conresaco,'z01_i_familiamicroarea'))."','$this->z01_i_familiamicroarea',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_agencia"]) || $this->z01_c_agencia != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11663,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_agencia'))."','$this->z01_c_agencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_conta"]) || $this->z01_c_conta != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11662,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_conta'))."','$this->z01_c_conta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_banco"]) || $this->z01_c_banco != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11661,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_banco'))."','$this->z01_c_banco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_ufctps"]) || $this->z01_c_ufctps != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11660,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_ufctps'))."','$this->z01_c_ufctps',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_d_dtemissaoctps"]) || $this->z01_d_dtemissaoctps != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11659,'".AddSlashes(pg_result($resaco,$conresaco,'z01_d_dtemissaoctps'))."','$this->z01_d_dtemissaoctps',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_seriectps"]) || $this->z01_c_seriectps != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11658,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_seriectps'))."','$this->z01_c_seriectps',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_numctps"]) || $this->z01_c_numctps != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11657,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_numctps'))."','$this->z01_c_numctps',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_ufident"]) || $this->z01_c_ufident != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11656,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_ufident'))."','$this->z01_c_ufident',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_escolaridade"]) || $this->z01_c_escolaridade != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11655,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_escolaridade'))."','$this->z01_c_escolaridade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_pis"]) || $this->z01_c_pis != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11654,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_pis'))."','$this->z01_c_pis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_d_datapais"]) || $this->z01_d_datapais != "")
             $resac = db_query("insert into db_acount values($acount,1010144,11664,'".AddSlashes(pg_result($resaco,$conresaco,'z01_d_datapais'))."','$this->z01_d_datapais',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_d_dtemissaocnh"]) || $this->z01_d_dtemissaocnh != "")
             $resac = db_query("insert into db_acount values($acount,1010144,16033,'".AddSlashes(pg_result($resaco,$conresaco,'z01_d_dtemissaocnh'))."','$this->z01_d_dtemissaocnh',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_codigoibge"]) || $this->z01_codigoibge != "")
             $resac = db_query("insert into db_acount values($acount,1010144,20729,'".AddSlashes(pg_result($resaco,$conresaco,'z01_codigoibge'))."','$this->z01_codigoibge',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_orgaoemissoridentidade"]) || $this->z01_orgaoemissoridentidade != "")
             $resac = db_query("insert into db_acount values($acount,1010144,21901,'".AddSlashes(pg_result($resaco,$conresaco,'z01_orgaoemissoridentidade'))."','$this->z01_orgaoemissoridentidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_registromunicipio"]) || $this->z01_registromunicipio != "")
             $resac = db_query("insert into db_acount values($acount,1010144,21987,'".AddSlashes(pg_result($resaco,$conresaco,'z01_registromunicipio'))."','$this->z01_registromunicipio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cgs_und não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->z01_i_cgsund;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "cgs_und não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->z01_i_cgsund;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->z01_i_cgsund;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);

        return $this->alterarExt();
       }
     }
   }


  function inserirExt()
  {
    if ( empty($this->z01_d_falecimento) || $this->z01_d_falecimento == 'null') {
      $this->z01_d_falecimento = 'null';
    } else {
      $this->z01_d_falecimento = "'$this->z01_d_falecimento'";
    }

    $this->z01_b_faleceu = 'false';
    if ( $this->z01_d_falecimento != 'null' ) {
      $this->z01_b_faleceu = 'true';
    }

    $this->z01_b_descnomemae = $this->z01_v_mae == 'SEM INFORMAÇÃO' ? 'true' : 'false';

    $sql2 = "INSERT INTO cgs_und_ext (z01_i_cgsund,
                                      z01_b_faleceu,
                                      z01_d_falecimento,
                                      z01_b_descnomemae,
                                      z01_i_naturalidade,
                                      z01_i_paisorigem,
                                      z01_v_municnasc,
                                      z01_v_ufnasc,
                                      z01_codigoibgenasc,
                                      z01_i_escolaridade,
                                      z01_i_cgm,
                                      z01_i_cge,
                                      z01_i_cidadao,
                                      z01_b_inativo)
                          VALUES(
                                      {$this->z01_i_cgsund},
                                      {$this->z01_b_faleceu},
                                      {$this->z01_d_falecimento},
                                      {$this->z01_b_descnomemae},
                                      ".(empty($this->z01_i_naturalidade) ? 'null' : "'".$this->z01_i_naturalidade."'").",
                                      ".(empty($this->z01_i_paisorigem)   ? 'null' : "'".$this->z01_i_paisorigem."'").",
                                      ".(empty($this->z01_v_municnasc)    ? 'null' : "'".$this->z01_v_municnasc."'").",
                                      ".(empty($this->z01_v_ufnasc)  || $this->z01_v_ufnasc == 'null' ? "''" : "'".$this->z01_v_ufnasc."'").",
                                      ".(empty($this->z01_codigoibgenasc) ? 'null' : "'".$this->z01_codigoibgenasc."'").",
                                      ".(empty($this->z01_i_escolaridade) ? 'null' : "'".$this->z01_i_escolaridade."'").",
                                      ".(empty($this->z01_i_cgm) || $this->z01_i_cgm == 'null' ? 'null' : "'".$this->z01_i_cgm."'").",
                                      ".(empty($this->z01_i_cge) || $this->z01_i_cge == 'null' ? 'null' : "'".$this->z01_i_cge."'").",
                                      ".(empty($this->z01_i_cidadao) || $this->z01_i_cidadao == 'null' ? 'null' : "'".$this->z01_i_cidadao."'").",
                                      ".(empty($this->z01_b_inativo)      ? 'false' : "'".$this->z01_b_inativo."'")."
                          )";

    $result2 = db_query($sql2);

    if($result2==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
        $this->erro_sql   = "cgs_und_ext ($this->z01_i_cgsund) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = "cgs_und_ext já Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }else{
        $this->erro_sql   = "cgs_und_ext ($this->z01_i_cgsund) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
         return true;
  }

  function alterarExt()
  {
    $result    = pg_query("SELECT * FROM cgs_und_ext WHERE z01_i_cgsund =" . $this->z01_i_cgsund);
    $num_rows = pg_num_rows($result);

    if($num_rows == 0) {
      return $this->inserirExt();
     }

    $sql     = " update cgs_und_ext set ";
    $virgula = "";

    if(trim($this->z01_i_cgm) != "") {

      $sWhere = $virgula." z01_i_cgm = '$this->z01_i_cgm' ";

      if( trim($this->z01_i_cgm) == 'null' ) {
        $sWhere = $virgula." z01_i_cgm = null ";
   }

      $sql    .= $sWhere;
      $virgula = ",";
    }

    if(trim($this->z01_i_cge) != "") {

      $sWhere = $virgula." z01_i_cge = '$this->z01_i_cge' ";

      if( trim($this->z01_i_cge) == 'null' ) {
        $sWhere = $virgula." z01_i_cge = null ";
      }

      $sql    .= $sWhere;
      $virgula = ",";
    }

    if(trim($this->z01_i_cidadao) != "") {

      $sWhere = $virgula." z01_i_cidadao = '$this->z01_i_cidadao' ";

      if( trim($this->z01_i_cidadao) == 'null' ) {
        $sWhere = $virgula." z01_i_cidadao = null ";
      }

      $sql    .= $sWhere;
      $virgula = ",";
    }

    if(trim($this->z01_v_municnasc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_municnasc"])){

      if($this->z01_i_naturalidade == 0) {
        $sql  .= $virgula." z01_v_municnasc = '$this->z01_v_municnasc' ";
      } else {
        $sql  .= $virgula." z01_v_municnasc = null ";
      }


      $virgula = ",";
      if(trim($this->z01_v_municnasc) == null && $this->z01_i_naturalidade == 0 ){
        $this->erro_sql = " Campo Municipio de nascimento não informado.";
        $this->erro_campo = "z01_v_municnasc";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->z01_v_ufnasc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_ufnasc"])){
      if($this->z01_i_naturalidade == 0 && trim($this->z01_v_ufnasc) != "null") {
        $sql  .= $virgula." z01_v_ufnasc = '$this->z01_v_ufnasc' ";
      } else {
        $sql  .= $virgula." z01_v_ufnasc = null ";
      }

      $virgula = ",";
      if(trim($this->z01_v_ufnasc) == null && $this->z01_i_naturalidade == 0 ){
        $this->erro_sql = " Campo UF não informado.";
        $this->erro_campo = "z01_v_ufnasc";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if( $this->z01_b_faleceu != "" ) {

      $sql    .= $virgula." z01_b_faleceu = '{$this->z01_b_faleceu}' ";
      $virgula = ",";
    }

    if( trim($this->z01_d_falecimento) != "" ) {

      if ( $this->z01_d_falecimento == "null" ) {
        $sql  .= $virgula." z01_d_falecimento = null ";
      } else {
        $sql  .= $virgula." z01_d_falecimento = '$this->z01_d_falecimento' ";
      }

      $virgula = ",";
    } else {

      if($this->z01_b_faleceu == 't') {

        $this->erro_sql = " Campo data do falecimento não informado.";
        $this->erro_campo = "z01_d_falecimento";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";

        return false;
      }
    }

    if(trim($this->z01_b_descnomemae) !="" ) {
      $sql  .= $virgula." z01_b_descnomemae = '{$this->z01_b_descnomemae}' ";
      $virgula = ",";
    }

    if(trim($this->z01_i_naturalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_i_naturalidade"])){
      $sql  .= $virgula." z01_i_naturalidade = $this->z01_i_naturalidade ";
      $virgula = ",";
    }

    if($this->z01_i_naturalidade > 0){
      $sql  .= $virgula." z01_i_paisorigem = '$this->z01_i_paisorigem' ";
      $virgula = ",";
      if($this->z01_i_paisorigem == null){
        $this->erro_sql = " Campo país origem não informado.";
        $this->erro_campo = "z01_i_paisorigem";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    } elseif($this->z01_i_naturalidade == 0) {
      $sql  .= $virgula." z01_i_paisorigem = null ";
      $virgula = ",";
    }

    if ( isset($this->z01_i_escolaridade) && trim($this->z01_i_escolaridade) != "" ) {

      $sql  .= $virgula." z01_i_escolaridade = '$this->z01_i_escolaridade' ";
      $virgula = ",";
    }

    if(!empty($this->z01_codigoibgenasc) && $this->z01_i_naturalidade == 0){
      $sql  .= $virgula." z01_codigoibgenasc = '$this->z01_codigoibgenasc' ";
      $virgula = ",";
    } elseif($this->z01_i_naturalidade > 0) {
      $sql  .= $virgula." z01_codigoibgenasc = null ";
      $virgula = ",";
    }

    if(!empty($this->z01_i_codocupacao)){

      $sWhere = $virgula." z01_i_codocupacao = '$this->z01_i_codocupacao' ";

      if( trim($this->z01_i_codocupacao) == 'null' ) {
        $sWhere = $virgula." z01_i_codocupacao = 0 ";
      }

      $sql    .= $sWhere;
      $virgula = ",";
    }

    if(!empty($this->z01_b_inativo)){
      $sql  .= $virgula." z01_b_inativo = '$this->z01_b_inativo' ";
      $virgula = ",";
    }

    $sql .= " where ";
    $sql .= " z01_i_cgsund = $this->z01_i_cgsund";

    $result = db_query($sql);

    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "cgs_und_ext nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->z01_i_cgsund;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    }
    return true;

  }


   // funcao para exclusao
   public function excluir ($z01_i_cgsund=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($z01_i_cgsund));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008844,'$z01_i_cgsund','E')");
           $resac  = db_query("insert into db_acount values($acount,1010144,1008844,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_i_cgsund'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,1008864,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_cgccpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,1008845,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,1008846,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,1008847,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_i_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,1008848,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,1008849,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,1008850,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,1008851,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,1008852,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,1008853,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_d_cadast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,1008854,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,1008855,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,1008856,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,1008857,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_telcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,1008858,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,1008859,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_d_nasc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,1008860,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11255,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_o_oid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11254,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_foto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11253,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_ufcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11252,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_telcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11251,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_profis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11250,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_pai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11249,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_muncon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11248,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_mae'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11247,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11246,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_fax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11245,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_endcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11244,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_emailc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11243,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_cxposcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11241,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_contato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11240,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_comcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11239,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_cnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11238,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_cepcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11237,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_celcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11236,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_categoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11235,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_i_numcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11234,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_i_nacion'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11233,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_i_estciv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11232,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_d_ultalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11230,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_d_dtvencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11229,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_d_dthabilitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11228,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_d_dtemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11227,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_passivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11226,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_bolsafamilia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11225,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_nis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11224,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_certidaodata'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11223,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_certidaocart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11222,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_certidaofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11221,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_certidaolivro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11696,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_certidaotermo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11220,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_certidaonum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11219,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_certidaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11218,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11217,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_transporte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11216,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11215,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_atendesp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11214,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_emailresp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11213,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_nomeresp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11212,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_naturalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11209,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_cxpostal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11208,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_raca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11256,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_baicon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11314,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_i_familiamicroarea'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11663,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_agencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11662,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11661,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11660,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_ufctps'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11659,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_d_dtemissaoctps'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11658,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_seriectps'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11657,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_numctps'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11656,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_ufident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11655,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_escolaridade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11654,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_pis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,11664,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_d_datapais'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,16033,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_d_dtemissaocnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,20729,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_codigoibge'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,21901,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_orgaoemissoridentidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010144,21987,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_registromunicipio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cgs_und
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($z01_i_cgsund)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " z01_i_cgsund = $z01_i_cgsund ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cgs_und não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$z01_i_cgsund;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "cgs_und não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$z01_i_cgsund;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$z01_i_cgsund;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   public function sql_record($sql) {
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:cgs_und";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($z01_i_cgsund = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from cgs_und ";
     $sql .= "      left  join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
     $sql .= "      inner join cgs  on  cgs.z01_i_numcgs = cgs_und.z01_i_cgsund";
     $sql .= "      left  join familia  on  familia.sd33_i_codigo = familiamicroarea.sd35_i_familia";
     $sql .= "      left  join microarea  on  microarea.sd34_i_codigo = familiamicroarea.sd35_i_microarea";
     $sql .= "      left join cgs_cartaosus     on  s115_i_cgs = cgs_und.z01_i_cgsund and s115_c_tipo='D' ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($z01_i_cgsund)) {
         $sql2 .= " where cgs_und.z01_i_cgsund = $z01_i_cgsund ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql
   public function sql_query_file ($z01_i_cgsund = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from cgs_und ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($z01_i_cgsund)){
         $sql2 .= " where cgs_und.z01_i_cgsund = $z01_i_cgsund ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

   function sql_query_etnia ($z01_i_cgsund = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from cgs_und ";
    $sql .= "      left  join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
    $sql .= "      inner join cgs               on  cgs.z01_i_numcgs = cgs_und.z01_i_cgsund";
    $sql .= "      left  join familia           on  familia.sd33_i_codigo = familiamicroarea.sd35_i_familia";
    $sql .= "      left  join microarea         on  microarea.sd34_i_codigo = familiamicroarea.sd35_i_microarea";
    $sql .= "      left  join cgs_cartaosus     on  s115_i_cgs = cgs_und.z01_i_cgsund and s115_c_tipo='D' ";
    $sql .= "      left  join cgs_undetnia      on  cgs_undetnia.s201_cgs_unid = cgs_und.z01_i_cgsund ";
    $sql .= "      left  join etnia             on  etnia.s200_codigo = cgs_undetnia.s201_etnia";
    $sql2 = "";
    if ($dbwhere == "") {

      if ($z01_i_cgsund != null) {
        $sql2 .= " where cgs_und.z01_i_cgsund = $z01_i_cgsund ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {

      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   // funcao do sql que traz somente o CGS dos pacientes e acompanhantes do pedido de TFD, o que é necessário para lançar a ajuda de custos
   function sql_query_cgs_beneficiadosajudacusto($z01_i_cgsund=null,$campos="*",$ordem=null,$dbwhere="") {
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

     $sql .= ' from ';
     $sql .= '   ((select cgs_und.*, tf01_i_codigo, 1 as tipo,z01_nome,tf17_d_datasaida,tf17_c_horasaida,tf25_i_destino, tf01_i_situacao, 2 as tf13_i_anulado, tf17_tiposaida ';
     $sql .= '       from cgs_und ';
     $sql .= '         inner join tfd_pedidotfd on tfd_pedidotfd.tf01_i_cgsund = cgs_und.z01_i_cgsund ';
     $sql .= '         left join tfd_agendamentoprestadora on tfd_agendamentoprestadora.tf16_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo ';
     $sql .= '         left join tfd_prestadoracentralagend on tfd_prestadoracentralagend.tf10_i_codigo = tfd_agendamentoprestadora.tf16_i_prestcentralagend ';
     $sql .= '         left join tfd_prestadora on tfd_prestadora.tf25_i_codigo = tfd_prestadoracentralagend.tf10_i_prestadora ';
     $sql .= '         left join cgm on cgm.z01_numcgm = tfd_prestadora.tf25_i_cgm ';
     $sql .= '         left  join tfd_agendasaida on tfd_agendasaida.tf17_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo) ';
     $sql .= '             union ';
     $sql .= '    (select cgs_und.*, tf01_i_codigo, 2 as tipo,z01_nome,tf17_d_datasaida,tf17_c_horasaida,tf25_i_destino, tf01_i_situacao, tf13_i_anulado, tf17_tiposaida ';
     $sql .= '       from cgs_und ';
     $sql .= '         inner join tfd_acompanhantes on tfd_acompanhantes.tf13_i_cgsund = cgs_und.z01_i_cgsund ';
     $sql .= '         inner join tfd_pedidotfd on tfd_pedidotfd.tf01_i_codigo = tfd_acompanhantes.tf13_i_pedidotfd ';
     $sql .= '         left join tfd_agendamentoprestadora on tfd_agendamentoprestadora.tf16_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo ';
     $sql .= '         left join tfd_prestadoracentralagend on tfd_prestadoracentralagend.tf10_i_codigo = tfd_agendamentoprestadora.tf16_i_prestcentralagend ';
     $sql .= '         left join tfd_prestadora on tfd_prestadora.tf25_i_codigo = tfd_prestadoracentralagend.tf10_i_prestadora ';
     $sql .= '         left join cgm on cgm.z01_numcgm = tfd_prestadora.tf25_i_cgm ';
     $sql .= '         left  join tfd_agendasaida on tfd_agendasaida.tf17_i_pedidotfd= tfd_pedidotfd.tf01_i_codigo)) as a ';
     $sql2 = '';

     if($dbwhere==""){
       if($z01_i_cgsund!=null ){
         $sql2 .= " where cgs_und.z01_i_cgsund = $z01_i_cgsund ";
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
   function sql_query_prontuarios($z01_i_cgsund=null, $campos="*",$ordem=null,$dbwhere=""){

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
     $sql .= " from cgs_und ";
     $sql .= "      inner join prontuarios on prontuarios.sd24_i_numcgs = cgs_und.z01_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($z01_i_cgsund!=null ){
         $sql2 .= " where cgs_und.z01_i_cgsund = $z01_i_cgsund ";
       }
     }else if($dbwhere != ""){
       $sql2 .= " where $dbwhere";
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
   function sql_query_cgs_profissional($z01_i_cgsund=null, $sd04_i_medico, $sd04_i_unidade, $campos="*",$ordem=null,$dbwhere=""){

     $sql = "select distinct on (z01_i_cgsund) ";
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
     $sql .= " from cgs_und ";
     $sql .= "      left join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
     $sql .= "      inner join cgs  on  cgs.z01_i_numcgs = cgs_und.z01_i_cgsund";
     $sql .= "      left join familia  on  familia.sd33_i_codigo = familiamicroarea.sd35_i_familia";
     $sql .= "      left join microarea  on  microarea.sd34_i_codigo = familiamicroarea.sd35_i_microarea";
     $sql .= "      left join cgs_cartaosus  on  s115_i_cgs = cgs_und.z01_i_cgsund and s115_c_tipo='D' ";
     $sql .= "      inner join prontuarios on prontuarios.sd24_i_numcgs = cgs_und.z01_i_cgsund";
     $sql .= "      inner join unidademedicos on unidademedicos.sd04_i_unidade = prontuarios.sd24_i_unidade ";
     $sql2 = " where sd04_i_medico = $sd04_i_medico and sd04_i_unidade = $sd04_i_unidade ";
     if($dbwhere==""){
       if($z01_i_cgsund!=null ){
         $sql2 .= " and cgs_und.z01_i_cgsund = $z01_i_cgsund ";
       }
     }else if($dbwhere != ""){
       $sql2 .= " and $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by z01_i_cgsund, ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     } else {
       $sql .= " order by z01_i_cgsund ";
     }

     return $sql;

  }

  public function sql_query_cadastro($iCodigoCGS, $sCampos = null) {

    $sCampos    = !!$sCampos ? $sCampos : "*";
    $iCodigoCGS = $iCodigoCGS + 0;

    $sql  = "select {$sCampos}                                                                                        \n";

    /**
     * Tabelas padrão do CGS
     */
    $sql .= "  from cgs_und                                                                                           \n";
    $sql .= "       inner join cgs              on cgs.z01_i_numcgs                 = cgs_und.z01_i_cgsund              \n";
    $sql .= "       left  join cgs_und_ext      on cgs_und_ext.z01_i_cgsund         = cgs_und.z01_i_cgsund              \n";
    $sql .= "       left  join cgs_cartaosus    on cgs_cartaosus.s115_i_cgs         = cgs_und.z01_i_cgsund              \n";
    $sql .= "                                  and cgs_cartaosus.s115_c_tipo        = 'D' /*Definitivo*/                \n";

    /**
     * Dados da Microárea e família
     */
    $sql .= "       left  join familiamicroarea on familiamicroarea.sd35_i_codigo   = cgs_und.z01_i_familiamicroarea    \n";
    $sql .= "       left  join familia          on familia.sd33_i_codigo            = familiamicroarea.sd35_i_familia   \n";
    $sql .= "       left  join microarea        on microarea.sd34_i_codigo          = familiamicroarea.sd35_i_microarea \n";
    /**
     * Dados do CGM
     */
    $sql .= "       left join cgm               on cgm.z01_numcgm                   = cgs_und_ext.z01_i_cgm             \n";

    /**
     * Dados do CGE
     */
    $sql .= "       left join aluno             on aluno.ed47_i_codigo              = cgs_und_ext.z01_i_cge             \n";

    /**
     * Dados do Cidadão
     */
    $sql .= "       left join cidadao           on cidadao.ov02_sequencial          = cgs_und_ext.z01_i_cidadao         \n";

    /**
     * Dados da Ocupação
     */
    $sql .= "       left join rhcbo             on rhcbo.rh70_sequencial            = cgs_und_ext.z01_i_codocupacao    \n";

    /**
     * Dados do Endereço
     */
    $sql .= "       left join cgs_undendereco   on cgs_undendereco.sd109_cgs_und    = cgs_und.z01_i_cgsund              \n";
    $sql .= "       left join endereco          on endereco.db76_sequencial         = cgs_undendereco.sd109_endereco    \n";

    /**
     * Dados da Etnia
     */
    $sql .= "       left join cgs_undetnia      on cgs_undetnia.s201_cgs_unid       = cgs_und.z01_i_cgsund              \n";
    $sql .= "       left join etnia             on etnia.s200_codigo                = cgs_undetnia.s201_etnia           \n";


    $sql .= " where cgs_und.z01_i_cgsund = {$iCodigoCGS} ";
    return $sql;
  }

  /**
   * SQL para buscar cadastros/fichas do ESF vinculadas ao CGS
   * @param $iCodigoCGS
   * @return string
   */
  public function sql_query_fichas_esf($iCodigoCGS) {

    $iCodigoCGS = $iCodigoCGS + 0;

    $fichas = array();

    $fichas['cadastro_individual']      = "select psf5_id                           as id, ";
    $fichas['cadastro_individual']     .= "       psf5_profissional_data            as data, ";
    $fichas['cadastro_individual']     .= "       psf5_profissional_cgm             as cgm,  ";
    $fichas['cadastro_individual']     .= "       'Cadastro Individual'             as tipo  ";
    $fichas['cadastro_individual']     .= "  from cgs_und ";
    $fichas['cadastro_individual']     .= "       inner join plugins.psf_individual     on psf5_codcgs          = z01_i_cgsund ";
    $fichas['cadastro_individual']     .= "                                            and cgs_und.z01_i_cgsund = {$iCodigoCGS}          ";

    $fichas['cadastro_domiciliar']      = "select psf4_id,                      ";
    $fichas['cadastro_domiciliar']     .= "       psf4_profissional_data,       ";
    $fichas['cadastro_domiciliar']     .= "       psf4_profissional_cgm, ";
    $fichas['cadastro_domiciliar']     .= "       'Cadastro Domiciliar'";
    $fichas['cadastro_domiciliar']     .= "  from cgs_und ";
    $fichas['cadastro_domiciliar']     .= "       inner  join plugins.psf_domicilio_familias on psf6_cod_cgs         = z01_i_cgsund  ";
    $fichas['cadastro_domiciliar']     .= "                                                 and cgs_und.z01_i_cgsund = {$iCodigoCGS} ";
    $fichas['cadastro_domiciliar']     .= "       inner  join plugins. psf_domicilios        on psf4_id              = psf6_id_domicilio ";

    $fichas['atividade_coletiva']       = "select psf7_id,              ";
    $fichas['atividade_coletiva']      .= "       psf7_data_atividade,  ";
    $fichas['atividade_coletiva']      .= "       psf7_responsavel_cgm, ";
    $fichas['atividade_coletiva']      .= "       'Atividade Coletiva'";
    $fichas['atividade_coletiva']      .= "  from cgs_und ";
    $fichas['atividade_coletiva']      .= "       inner  join cgs_cartaosus                                      on s115_i_cgs           = z01_i_cgsund ";
    $fichas['atividade_coletiva']      .= "                                                                     and cgs_und.z01_i_cgsund = {$iCodigoCGS}";
    $fichas['atividade_coletiva']      .= "       inner  join plugins.psf_ficha_atividade_coletiva_participantes on psf9_cod_cns         = s115_i_codigo ";
    $fichas['atividade_coletiva']      .= "       inner  join plugins.psf_ficha_atividade_coletiva               on psf7_id              = psf9_id_atividade ";

    $fichas['visita_domiciliar']        = "select psf10_id,                  ";
    $fichas['visita_domiciliar']       .= "       psf10a_profissional_data,  ";
    $fichas['visita_domiciliar']       .= "       psf10a_profissional_cgm,";
    $fichas['visita_domiciliar']       .= "       'Visita Domiciliar'";
    $fichas['visita_domiciliar']       .= "  from cgs_und ";
    $fichas['visita_domiciliar']       .= "       inner  join plugins.psf_ficha_visita_domiciliar              on psf10_cgs            = z01_i_cgsund";
    $fichas['visita_domiciliar']       .= "                                                                   and cgs_und.z01_i_cgsund = {$iCodigoCGS}";
    $fichas['visita_domiciliar']       .= "       inner  join plugins.psf_ficha_visita_domiciliar_profissional on psf10a_id            = psf10_id_profissional";

    $fichas['atendimento_individual']   = "select psf13_id,                       ";
    $fichas['atendimento_individual']  .= "       psf13a_profissional_data,       ";
    $fichas['atendimento_individual']  .= "       psf13a_profissional_cgm,";
    $fichas['atendimento_individual']  .= "       'Atendimento Individual'";
    $fichas['atendimento_individual']  .= "  from cgs_und ";
    $fichas['atendimento_individual']  .= "       inner  join plugins.psf_ficha_atendimento_individual              on psf13_cgs            = z01_i_cgsund ";
    $fichas['atendimento_individual']  .= "                                                                        and cgs_und.z01_i_cgsund = {$iCodigoCGS}";
    $fichas['atendimento_individual']  .= "       inner  join plugins.psf_ficha_atendimento_individual_profissional on psf13a_id            = psf13_id_profissional ";

    $fichas['procedimentos']             = "select psf15_id,                 ";
    $fichas['procedimentos']            .= "       psf15a_profissional_data, ";
    $fichas['procedimentos']            .= "       psf15a_profissional_cgm,";
    $fichas['procedimentos']            .= "       'Procedimentos'";
    $fichas['procedimentos']            .= "  from cgs_und ";
    $fichas['procedimentos']            .= "       inner  join plugins.psf_ficha_procedimentos              on psf15_cgs            = z01_i_cgsund ";
    $fichas['procedimentos']            .= "       inner  join plugins.psf_ficha_procedimentos_profissional on psf15a_id            = psf15_id_profissional ";
    $fichas['procedimentos']            .= "                                                               and cgs_und.z01_i_cgsund = {$iCodigoCGS}          ";

    $fichas['atendimento_odontologico']  = "select psf17_id,                         ";
    $fichas['atendimento_odontologico'] .= "       psf17a_profissional_data,         ";
    $fichas['atendimento_odontologico'] .= "       psf17a_profissional_cgm,";
    $fichas['atendimento_odontologico'] .= "       'Atendimento Odontológico'";
    $fichas['atendimento_odontologico'] .= "  from cgs_und ";
    $fichas['atendimento_odontologico'] .= "       inner  join plugins.psf_ficha_atendimento_odontologico              on psf17_cgs            = z01_i_cgsund ";
    $fichas['atendimento_odontologico'] .= "                                                                          and cgs_und.z01_i_cgsund = {$iCodigoCGS}          ";
    $fichas['atendimento_odontologico'] .= "       inner  join plugins.psf_ficha_atendimento_odontologico_profissional on psf17a_id            = psf17_id_profissional ";

    $fichas['atendimento_domiciliar']   = "select adcgs.sequencial,";
    $fichas['atendimento_domiciliar']  .= "       ad.atendimento,";
    $fichas['atendimento_domiciliar']  .= "       medicos_ad.sd03_i_cgm,";
    $fichas['atendimento_domiciliar']  .= "       'Atendimento Domiciliar'";
    $fichas['atendimento_domiciliar']  .= "  from cgs_und ";
    $fichas['atendimento_domiciliar']  .= "       inner  join cgs_cartaosus                                      on s115_i_cgs                          = z01_i_cgsund ";
    $fichas['atendimento_domiciliar']  .= "                                                                     and cgs_und.z01_i_cgsund                = {$iCodigoCGS}          ";
    $fichas['atendimento_domiciliar']  .= "       inner  join plugins.atendimentodomiciliarcgs as adcgs          on adcgs.cns                           = s115_c_cartaosus ";
    $fichas['atendimento_domiciliar']  .= "       inner  join plugins.atendimentodomiciliar    as ad             on ad.sequencial                       = atendimentodomiciliar ";
    $fichas['atendimento_domiciliar']  .= "       inner  join medicos as medicos_ad                              on medicos_ad.sd03_i_codigo            = ad.medico ";

    $fichas['marcadores_consumo']       = "select mca.sequencial,";
    $fichas['marcadores_consumo']      .= "       mca.atendimento,";
    $fichas['marcadores_consumo']      .= "       medicos_mca.sd03_i_cgm,";
    $fichas['marcadores_consumo']      .= "       'Marcadores de Consumo'";
    $fichas['marcadores_consumo']      .= "  from cgs_und ";
    $fichas['marcadores_consumo']      .= "       inner  join cgs_cartaosus                                      on s115_i_cgs                    = z01_i_cgsund ";
    $fichas['marcadores_consumo']      .= "                                                                     and cgs_und.z01_i_cgsund          = {$iCodigoCGS}          ";
    $fichas['marcadores_consumo']      .= "       inner  join plugins.marcadoresconsumoalimentar as mca          on mca.cns                       = s115_c_cartaosus ";
    $fichas['marcadores_consumo']      .= "       inner  join medicos as medicos_mca                             on medicos_mca.sd03_i_codigo     = mca.medico ";


    $fichas['elegibilidade_admissao']   = "select ea.sequencial,";
    $fichas['elegibilidade_admissao']  .= "       ea.atendimento,";
    $fichas['elegibilidade_admissao']  .= "       medicos_ea.sd03_i_cgm,";
    $fichas['elegibilidade_admissao']  .= "       'Elegibilidade e Admissão'";
    $fichas['elegibilidade_admissao']  .= "  from cgs_und ";
    $fichas['elegibilidade_admissao']  .= "       inner  join cgs_cartaosus                        on s115_i_cgs                          = z01_i_cgsund ";
    $fichas['elegibilidade_admissao']  .= "                                                       and cgs_und.z01_i_cgsund = {$iCodigoCGS}";
    $fichas['elegibilidade_admissao']  .= "       inner  join plugins.elegibilidadeadmissao as ea  on ea.cns                              = s115_c_cartaosus ";
    $fichas['elegibilidade_admissao']  .= "       inner  join medicos as medicos_ea                on medicos_ea.sd03_i_codigo            = ea.medico ";


    $sql   = "select fichas.*, ";
    $sql  .= "       cgm.z01_nome as profissional ";
    $sql  .= "  from( ";
    $sql  .= implode(' UNION ', $fichas);
    $sql  .= ") as fichas inner join cgm on fichas.cgm = cgm.z01_numcgm";

    return $sql;
  }
}
