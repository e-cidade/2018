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

//MODULO: escola
//CLASSE DA ENTIDADE aluno
class cl_aluno { 
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
   var $ed47_i_codigo = 0; 
   var $ed47_v_nome = null; 
   var $ed47_v_ender = null; 
   var $ed47_c_numero = null; 
   var $ed47_v_compl = null; 
   var $ed47_v_bairro = null; 
   var $ed47_v_cep = null; 
   var $ed47_c_raca = null; 
   var $ed47_v_cxpostal = null; 
   var $ed47_v_telef = null; 
   var $ed47_d_cadast_dia = null; 
   var $ed47_d_cadast_mes = null; 
   var $ed47_d_cadast_ano = null; 
   var $ed47_d_cadast = null; 
   var $ed47_v_ident = null; 
   var $ed47_i_login = 0; 
   var $ed47_c_nomeresp = null; 
   var $ed47_c_emailresp = null; 
   var $ed47_c_atenddifer = null; 
   var $ed47_t_obs = null; 
   var $ed47_c_transporte = null; 
   var $ed47_c_zona = null; 
   var $ed47_certidaomatricula = null; 
   var $ed47_c_certidaotipo = null; 
   var $ed47_c_certidaonum = null; 
   var $ed47_c_certidaolivro = null; 
   var $ed47_c_certidaofolha = null; 
   var $ed47_c_certidaocart = null; 
   var $ed47_c_certidaodata_dia = null; 
   var $ed47_c_certidaodata_mes = null; 
   var $ed47_c_certidaodata_ano = null; 
   var $ed47_c_certidaodata = null; 
   var $ed47_c_nis = null; 
   var $ed47_c_bolsafamilia = null; 
   var $ed47_c_passivo = null; 
   var $ed47_d_dtemissao_dia = null; 
   var $ed47_d_dtemissao_mes = null; 
   var $ed47_d_dtemissao_ano = null; 
   var $ed47_d_dtemissao = null; 
   var $ed47_d_dthabilitacao_dia = null; 
   var $ed47_d_dthabilitacao_mes = null; 
   var $ed47_d_dthabilitacao_ano = null; 
   var $ed47_d_dthabilitacao = null; 
   var $ed47_d_dtvencimento_dia = null; 
   var $ed47_d_dtvencimento_mes = null; 
   var $ed47_d_dtvencimento_ano = null; 
   var $ed47_d_dtvencimento = null; 
   var $ed47_d_nasc_dia = null; 
   var $ed47_d_nasc_mes = null; 
   var $ed47_d_nasc_ano = null; 
   var $ed47_d_nasc = null; 
   var $ed47_d_ultalt_dia = null; 
   var $ed47_d_ultalt_mes = null; 
   var $ed47_d_ultalt_ano = null; 
   var $ed47_d_ultalt = null; 
   var $ed47_i_estciv = 0; 
   var $ed47_i_nacion = 0; 
   var $ed47_v_categoria = null; 
   var $ed47_v_cnh = null; 
   var $ed47_v_contato = null; 
   var $ed47_v_cpf = null; 
   var $ed47_v_email = null; 
   var $ed47_v_fax = null; 
   var $ed47_v_hora = null; 
   var $ed47_v_mae = null; 
   var $ed47_v_pai = null; 
   var $ed47_v_profis = null; 
   var $ed47_v_sexo = null; 
   var $ed47_v_telcel = null; 
   var $ed47_c_foto = null; 
   var $ed47_o_oid = 0; 
   var $ed47_c_codigoinep = null; 
   var $ed47_i_pais = 0; 
   var $ed47_d_identdtexp_dia = null; 
   var $ed47_d_identdtexp_mes = null; 
   var $ed47_d_identdtexp_ano = null; 
   var $ed47_d_identdtexp = null; 
   var $ed47_v_identcompl = null; 
   var $ed47_c_passaporte = null; 
   var $ed47_i_transpublico = 0; 
   var $ed47_i_filiacao = 0; 
   var $ed47_i_censoufend = 0; 
   var $ed47_i_censomunicend = 0; 
   var $ed47_i_censoorgemissrg = 0; 
   var $ed47_i_censoufident = 0; 
   var $ed47_i_censoufcert = 0; 
   var $ed47_i_censomuniccert = 0; 
   var $ed47_i_censoufnat = 0; 
   var $ed47_i_censomunicnat = 0; 
   var $ed47_i_atendespec = 0; 
   var $ed47_i_censocartorio = 0; 
   var $ed47_celularresponsavel = null; 
   var $ed47_situacaodocumentacao = 0; 
   var $ed47_cartaosus = null; 
   var $ed47_tiposanguineo = null; 
   var $ed47_municipioestrangeiro = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed47_i_codigo = int8 = Código 
                 ed47_v_nome = char(70) = Nome 
                 ed47_v_ender = varchar(100) = Endereço 
                 ed47_c_numero = char(10) = Número 
                 ed47_v_compl = varchar(20) = Complemento 
                 ed47_v_bairro = varchar(40) = Bairro 
                 ed47_v_cep = varchar(8) = CEP 
                 ed47_c_raca = char(20) = Raça 
                 ed47_v_cxpostal = varchar(20) = Caixa Postal 
                 ed47_v_telef = varchar(12) = Telefone 
                 ed47_d_cadast = date = Data do Cadastramento 
                 ed47_v_ident = varchar(20) = N° Identidade 
                 ed47_i_login = int4 = Login 
                 ed47_c_nomeresp = char(70) = Responsável Legal
                 ed47_c_emailresp = char(50) = Email do Responsável 
                 ed47_c_atenddifer = char(30) = Recebe Escolarização em Outro Espaço 
                 ed47_t_obs = text = Observações 
                 ed47_c_transporte = char(20) = Poder Público Responsável 
                 ed47_c_zona = char(20) = Zona 
                 ed47_certidaomatricula = char(32) = Matrícula 
                 ed47_c_certidaotipo = char(1) = Tipo de Certidão 
                 ed47_c_certidaonum = char(8) = Número do Termo 
                 ed47_c_certidaolivro = char(8) = Livro 
                 ed47_c_certidaofolha = char(4) = Folha 
                 ed47_c_certidaocart = char(150) = Cartório 
                 ed47_c_certidaodata = date = Data de Emissão 
                 ed47_c_nis = char(11) = N° NIS 
                 ed47_c_bolsafamilia = char(1) = Bolsa Família 
                 ed47_c_passivo = char(1) = Passivo 
                 ed47_d_dtemissao = date = Emissão CNH 
                 ed47_d_dthabilitacao = date = 1ª CNH 
                 ed47_d_dtvencimento = date = Vencimento CNH 
                 ed47_d_nasc = date = Nascimento 
                 ed47_d_ultalt = date = Última Alteração 
                 ed47_i_estciv = int4 = Estado Civil 
                 ed47_i_nacion = int4 = Nacionalidade 
                 ed47_v_categoria = varchar(2) = Categoria CNH 
                 ed47_v_cnh = varchar(20) = N° CNH 
                 ed47_v_contato = text = Contato 
                 ed47_v_cpf = varchar(11) = CPF 
                 ed47_v_email = varchar(100) = Email 
                 ed47_v_fax = varchar(12) = Fax 
                 ed47_v_hora = varchar(5) = Hora do Cadastramento 
                 ed47_v_mae = char(70) = Filiação 1 
                 ed47_v_pai = char(70) = Filiação 2 
                 ed47_v_profis = varchar(40) = Profissão 
                 ed47_v_sexo = varchar(1) = Sexo 
                 ed47_v_telcel = varchar(12) = Telefone Celular 
                 ed47_c_foto = char(100) = Foto 
                 ed47_o_oid = oid = Imagem 
                 ed47_c_codigoinep = char(12) = Código INEP / ID Aluno 
                 ed47_i_pais = int8 = País 
                 ed47_d_identdtexp = date = Data Expedição Identidade 
                 ed47_v_identcompl = char(4) = Complemento 
                 ed47_c_passaporte = char(20) = N° Passaporte 
                 ed47_i_transpublico = int4 = Transporte Escolar Público 
                 ed47_i_filiacao = int4 = Filiação 
                 ed47_i_censoufend = int4 = UF 
                 ed47_i_censomunicend = int4 = Município 
                 ed47_i_censoorgemissrg = int4 = Órgão Emissor 
                 ed47_i_censoufident = int4 = UF Identidade 
                 ed47_i_censoufcert = int4 = UF Cartório 
                 ed47_i_censomuniccert = int4 = Município 
                 ed47_i_censoufnat = int4 = UF de Nascimento 
                 ed47_i_censomunicnat = int4 = Naturalidade 
                 ed47_i_atendespec = int4 = Atend. Especializado 
                 ed47_i_censocartorio = int4 = Cartórios 
                 ed47_celularresponsavel = varchar(10) = Celular do Responsável 
                 ed47_situacaodocumentacao = int4 = Situação Documentação do aluno 
                 ed47_cartaosus = varchar(15) = Cartão SUS 
                 ed47_tiposanguineo = int8 = Tipo Sanguíneo 
                 ed47_municipioestrangeiro = varchar(255) = Localidade 
                 ";
   //funcao construtor da classe 
   function cl_aluno() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aluno"); 
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
       $this->ed47_i_codigo = ($this->ed47_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_codigo"]:$this->ed47_i_codigo);
       $this->ed47_v_nome = ($this->ed47_v_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_nome"]:$this->ed47_v_nome);
       $this->ed47_v_ender = ($this->ed47_v_ender == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_ender"]:$this->ed47_v_ender);
       $this->ed47_c_numero = ($this->ed47_c_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_numero"]:$this->ed47_c_numero);
       $this->ed47_v_compl = ($this->ed47_v_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_compl"]:$this->ed47_v_compl);
       $this->ed47_v_bairro = ($this->ed47_v_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_bairro"]:$this->ed47_v_bairro);
       $this->ed47_v_cep = ($this->ed47_v_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_cep"]:$this->ed47_v_cep);
       $this->ed47_c_raca = ($this->ed47_c_raca == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_raca"]:$this->ed47_c_raca);
       $this->ed47_v_cxpostal = ($this->ed47_v_cxpostal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_cxpostal"]:$this->ed47_v_cxpostal);
       $this->ed47_v_telef = ($this->ed47_v_telef == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_telef"]:$this->ed47_v_telef);
       if($this->ed47_d_cadast == ""){
         $this->ed47_d_cadast_dia = ($this->ed47_d_cadast_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_cadast_dia"]:$this->ed47_d_cadast_dia);
         $this->ed47_d_cadast_mes = ($this->ed47_d_cadast_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_cadast_mes"]:$this->ed47_d_cadast_mes);
         $this->ed47_d_cadast_ano = ($this->ed47_d_cadast_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_cadast_ano"]:$this->ed47_d_cadast_ano);
         if($this->ed47_d_cadast_dia != ""){
            $this->ed47_d_cadast = $this->ed47_d_cadast_ano."-".$this->ed47_d_cadast_mes."-".$this->ed47_d_cadast_dia;
         }
       }
       $this->ed47_v_ident = ($this->ed47_v_ident == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_ident"]:$this->ed47_v_ident);
       $this->ed47_i_login = ($this->ed47_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_login"]:$this->ed47_i_login);
       $this->ed47_c_nomeresp = ($this->ed47_c_nomeresp == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_nomeresp"]:$this->ed47_c_nomeresp);
       $this->ed47_c_emailresp = ($this->ed47_c_emailresp == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_emailresp"]:$this->ed47_c_emailresp);
       $this->ed47_c_atenddifer = ($this->ed47_c_atenddifer == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_atenddifer"]:$this->ed47_c_atenddifer);
       $this->ed47_t_obs = ($this->ed47_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_t_obs"]:$this->ed47_t_obs);
       $this->ed47_c_transporte = ($this->ed47_c_transporte == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_transporte"]:$this->ed47_c_transporte);
       $this->ed47_c_zona = ($this->ed47_c_zona == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_zona"]:$this->ed47_c_zona);
       $this->ed47_certidaomatricula = ($this->ed47_certidaomatricula == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_certidaomatricula"]:$this->ed47_certidaomatricula);
       $this->ed47_c_certidaotipo = ($this->ed47_c_certidaotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaotipo"]:$this->ed47_c_certidaotipo);
       $this->ed47_c_certidaonum = ($this->ed47_c_certidaonum == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaonum"]:$this->ed47_c_certidaonum);
       $this->ed47_c_certidaolivro = ($this->ed47_c_certidaolivro == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaolivro"]:$this->ed47_c_certidaolivro);
       $this->ed47_c_certidaofolha = ($this->ed47_c_certidaofolha == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaofolha"]:$this->ed47_c_certidaofolha);
       $this->ed47_c_certidaocart = ($this->ed47_c_certidaocart == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaocart"]:$this->ed47_c_certidaocart);
       if($this->ed47_c_certidaodata == ""){
         $this->ed47_c_certidaodata_dia = ($this->ed47_c_certidaodata_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaodata_dia"]:$this->ed47_c_certidaodata_dia);
         $this->ed47_c_certidaodata_mes = ($this->ed47_c_certidaodata_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaodata_mes"]:$this->ed47_c_certidaodata_mes);
         $this->ed47_c_certidaodata_ano = ($this->ed47_c_certidaodata_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaodata_ano"]:$this->ed47_c_certidaodata_ano);
         if($this->ed47_c_certidaodata_dia != ""){
            $this->ed47_c_certidaodata = $this->ed47_c_certidaodata_ano."-".$this->ed47_c_certidaodata_mes."-".$this->ed47_c_certidaodata_dia;
         }
       }
       $this->ed47_c_nis = ($this->ed47_c_nis == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_nis"]:$this->ed47_c_nis);
       $this->ed47_c_bolsafamilia = ($this->ed47_c_bolsafamilia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_bolsafamilia"]:$this->ed47_c_bolsafamilia);
       $this->ed47_c_passivo = ($this->ed47_c_passivo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_passivo"]:$this->ed47_c_passivo);
       if($this->ed47_d_dtemissao == ""){
         $this->ed47_d_dtemissao_dia = ($this->ed47_d_dtemissao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_dtemissao_dia"]:$this->ed47_d_dtemissao_dia);
         $this->ed47_d_dtemissao_mes = ($this->ed47_d_dtemissao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_dtemissao_mes"]:$this->ed47_d_dtemissao_mes);
         $this->ed47_d_dtemissao_ano = ($this->ed47_d_dtemissao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_dtemissao_ano"]:$this->ed47_d_dtemissao_ano);
         if($this->ed47_d_dtemissao_dia != ""){
            $this->ed47_d_dtemissao = $this->ed47_d_dtemissao_ano."-".$this->ed47_d_dtemissao_mes."-".$this->ed47_d_dtemissao_dia;
         }
       }
       if($this->ed47_d_dthabilitacao == ""){
         $this->ed47_d_dthabilitacao_dia = ($this->ed47_d_dthabilitacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_dthabilitacao_dia"]:$this->ed47_d_dthabilitacao_dia);
         $this->ed47_d_dthabilitacao_mes = ($this->ed47_d_dthabilitacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_dthabilitacao_mes"]:$this->ed47_d_dthabilitacao_mes);
         $this->ed47_d_dthabilitacao_ano = ($this->ed47_d_dthabilitacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_dthabilitacao_ano"]:$this->ed47_d_dthabilitacao_ano);
         if($this->ed47_d_dthabilitacao_dia != ""){
            $this->ed47_d_dthabilitacao = $this->ed47_d_dthabilitacao_ano."-".$this->ed47_d_dthabilitacao_mes."-".$this->ed47_d_dthabilitacao_dia;
         }
       }
       if($this->ed47_d_dtvencimento == ""){
         $this->ed47_d_dtvencimento_dia = ($this->ed47_d_dtvencimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_dtvencimento_dia"]:$this->ed47_d_dtvencimento_dia);
         $this->ed47_d_dtvencimento_mes = ($this->ed47_d_dtvencimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_dtvencimento_mes"]:$this->ed47_d_dtvencimento_mes);
         $this->ed47_d_dtvencimento_ano = ($this->ed47_d_dtvencimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_dtvencimento_ano"]:$this->ed47_d_dtvencimento_ano);
         if($this->ed47_d_dtvencimento_dia != ""){
            $this->ed47_d_dtvencimento = $this->ed47_d_dtvencimento_ano."-".$this->ed47_d_dtvencimento_mes."-".$this->ed47_d_dtvencimento_dia;
         }
       }
       if($this->ed47_d_nasc == ""){
         $this->ed47_d_nasc_dia = ($this->ed47_d_nasc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_nasc_dia"]:$this->ed47_d_nasc_dia);
         $this->ed47_d_nasc_mes = ($this->ed47_d_nasc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_nasc_mes"]:$this->ed47_d_nasc_mes);
         $this->ed47_d_nasc_ano = ($this->ed47_d_nasc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_nasc_ano"]:$this->ed47_d_nasc_ano);
         if($this->ed47_d_nasc_dia != ""){
            $this->ed47_d_nasc = $this->ed47_d_nasc_ano."-".$this->ed47_d_nasc_mes."-".$this->ed47_d_nasc_dia;
         }
       }
       if($this->ed47_d_ultalt == ""){
         $this->ed47_d_ultalt_dia = ($this->ed47_d_ultalt_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_ultalt_dia"]:$this->ed47_d_ultalt_dia);
         $this->ed47_d_ultalt_mes = ($this->ed47_d_ultalt_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_ultalt_mes"]:$this->ed47_d_ultalt_mes);
         $this->ed47_d_ultalt_ano = ($this->ed47_d_ultalt_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_ultalt_ano"]:$this->ed47_d_ultalt_ano);
         if($this->ed47_d_ultalt_dia != ""){
            $this->ed47_d_ultalt = $this->ed47_d_ultalt_ano."-".$this->ed47_d_ultalt_mes."-".$this->ed47_d_ultalt_dia;
         }
       }
       $this->ed47_i_estciv = ($this->ed47_i_estciv == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_estciv"]:$this->ed47_i_estciv);
       $this->ed47_i_nacion = ($this->ed47_i_nacion == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_nacion"]:$this->ed47_i_nacion);
       $this->ed47_v_categoria = ($this->ed47_v_categoria == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_categoria"]:$this->ed47_v_categoria);
       $this->ed47_v_cnh = ($this->ed47_v_cnh == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_cnh"]:$this->ed47_v_cnh);
       $this->ed47_v_contato = ($this->ed47_v_contato == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_contato"]:$this->ed47_v_contato);
       $this->ed47_v_cpf = ($this->ed47_v_cpf == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_cpf"]:$this->ed47_v_cpf);
       $this->ed47_v_email = ($this->ed47_v_email == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_email"]:$this->ed47_v_email);
       $this->ed47_v_fax = ($this->ed47_v_fax == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_fax"]:$this->ed47_v_fax);
       $this->ed47_v_hora = ($this->ed47_v_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_hora"]:$this->ed47_v_hora);
       $this->ed47_v_mae = ($this->ed47_v_mae == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_mae"]:$this->ed47_v_mae);
       $this->ed47_v_pai = ($this->ed47_v_pai == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_pai"]:$this->ed47_v_pai);
       $this->ed47_v_profis = ($this->ed47_v_profis == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_profis"]:$this->ed47_v_profis);
       $this->ed47_v_sexo = ($this->ed47_v_sexo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_sexo"]:$this->ed47_v_sexo);
       $this->ed47_v_telcel = ($this->ed47_v_telcel == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_telcel"]:$this->ed47_v_telcel);
       $this->ed47_c_foto = ($this->ed47_c_foto == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_foto"]:$this->ed47_c_foto);
       $this->ed47_o_oid = ($this->ed47_o_oid == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_o_oid"]:$this->ed47_o_oid);
       $this->ed47_c_codigoinep = ($this->ed47_c_codigoinep == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_codigoinep"]:$this->ed47_c_codigoinep);
       $this->ed47_i_pais = ($this->ed47_i_pais == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_pais"]:$this->ed47_i_pais);
       if($this->ed47_d_identdtexp == ""){
         $this->ed47_d_identdtexp_dia = ($this->ed47_d_identdtexp_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_identdtexp_dia"]:$this->ed47_d_identdtexp_dia);
         $this->ed47_d_identdtexp_mes = ($this->ed47_d_identdtexp_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_identdtexp_mes"]:$this->ed47_d_identdtexp_mes);
         $this->ed47_d_identdtexp_ano = ($this->ed47_d_identdtexp_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_identdtexp_ano"]:$this->ed47_d_identdtexp_ano);
         if($this->ed47_d_identdtexp_dia != ""){
            $this->ed47_d_identdtexp = $this->ed47_d_identdtexp_ano."-".$this->ed47_d_identdtexp_mes."-".$this->ed47_d_identdtexp_dia;
         }
       }
       $this->ed47_v_identcompl = ($this->ed47_v_identcompl == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_identcompl"]:$this->ed47_v_identcompl);
       $this->ed47_c_passaporte = ($this->ed47_c_passaporte == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_passaporte"]:$this->ed47_c_passaporte);
       $this->ed47_i_transpublico = ($this->ed47_i_transpublico == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_transpublico"]:$this->ed47_i_transpublico);
       $this->ed47_i_filiacao = ($this->ed47_i_filiacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_filiacao"]:$this->ed47_i_filiacao);
       $this->ed47_i_censoufend = ($this->ed47_i_censoufend == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_censoufend"]:$this->ed47_i_censoufend);
       $this->ed47_i_censomunicend = ($this->ed47_i_censomunicend == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_censomunicend"]:$this->ed47_i_censomunicend);
       $this->ed47_i_censoorgemissrg = ($this->ed47_i_censoorgemissrg == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_censoorgemissrg"]:$this->ed47_i_censoorgemissrg);
       $this->ed47_i_censoufident = ($this->ed47_i_censoufident == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_censoufident"]:$this->ed47_i_censoufident);
       $this->ed47_i_censoufcert = ($this->ed47_i_censoufcert == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_censoufcert"]:$this->ed47_i_censoufcert);
       $this->ed47_i_censomuniccert = ($this->ed47_i_censomuniccert == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_censomuniccert"]:$this->ed47_i_censomuniccert);
       $this->ed47_i_censoufnat = ($this->ed47_i_censoufnat == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_censoufnat"]:$this->ed47_i_censoufnat);
       $this->ed47_i_censomunicnat = ($this->ed47_i_censomunicnat == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_censomunicnat"]:$this->ed47_i_censomunicnat);
       $this->ed47_i_atendespec = ($this->ed47_i_atendespec == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_atendespec"]:$this->ed47_i_atendespec);
       $this->ed47_i_censocartorio = ($this->ed47_i_censocartorio == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_censocartorio"]:$this->ed47_i_censocartorio);
       $this->ed47_celularresponsavel = ($this->ed47_celularresponsavel == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_celularresponsavel"]:$this->ed47_celularresponsavel);
       $this->ed47_situacaodocumentacao = ($this->ed47_situacaodocumentacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_situacaodocumentacao"]:$this->ed47_situacaodocumentacao);
       $this->ed47_cartaosus = ($this->ed47_cartaosus == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_cartaosus"]:$this->ed47_cartaosus);
       $this->ed47_tiposanguineo = ($this->ed47_tiposanguineo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_tiposanguineo"]:$this->ed47_tiposanguineo);
       $this->ed47_municipioestrangeiro = ($this->ed47_municipioestrangeiro == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_municipioestrangeiro"]:$this->ed47_municipioestrangeiro);
     }else{
       $this->ed47_i_codigo = ($this->ed47_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_codigo"]:$this->ed47_i_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($ed47_i_codigo){ 
      $this->atualizacampos();
     if($this->ed47_v_nome == null ){ 
       $this->erro_sql = " Campo Nome não informado.";
       $this->erro_campo = "ed47_v_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed47_v_ender == null ){ 
       $this->erro_sql = " Campo Endereço não informado.";
       $this->erro_campo = "ed47_v_ender";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed47_c_raca == null ){ 
       $this->erro_sql = " Campo Raça não informado.";
       $this->erro_campo = "ed47_c_raca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed47_d_cadast == null ){ 
       $this->ed47_d_cadast = "null";
     }
     if($this->ed47_i_login == null ){ 
       $this->ed47_i_login = "0";
     }
     if($this->ed47_c_atenddifer == null ){ 
       $this->erro_sql = " Campo Recebe Escolarização em Outro Espaço não informado.";
       $this->erro_campo = "ed47_c_atenddifer";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed47_c_certidaodata == null ){ 
       $this->ed47_c_certidaodata = "null";
     }
     if($this->ed47_d_dtemissao == null ){ 
       $this->ed47_d_dtemissao = "null";
     }
     if($this->ed47_d_dthabilitacao == null ){ 
       $this->ed47_d_dthabilitacao = "null";
     }
     if($this->ed47_d_dtvencimento == null ){ 
       $this->ed47_d_dtvencimento = "null";
     }
     if($this->ed47_d_nasc == null ){ 
       $this->erro_sql = " Campo Nascimento não informado.";
       $this->erro_campo = "ed47_d_nasc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed47_d_ultalt == null ){ 
       $this->ed47_d_ultalt = "null";
     }
     if($this->ed47_i_estciv == null ){ 
       $this->ed47_i_estciv = "0";
     }
     if($this->ed47_i_nacion == null ){ 
       $this->erro_sql = " Campo Nacionalidade não informado.";
       $this->erro_campo = "ed47_i_nacion";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed47_v_sexo == null ){ 
       $this->erro_sql = " Campo Sexo não informado.";
       $this->erro_campo = "ed47_v_sexo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed47_o_oid == null ){ 
       $this->ed47_o_oid = 'null';
     }
     if($this->ed47_i_pais == null ){ 
       $this->erro_sql = " Campo País não informado.";
       $this->erro_campo = "ed47_i_pais";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed47_d_identdtexp == null ){ 
       $this->ed47_d_identdtexp = "null";
     }
     if($this->ed47_i_transpublico == null ){ 
       $this->erro_sql = " Campo Transporte Escolar Público não informado.";
       $this->erro_campo = "ed47_i_transpublico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed47_i_filiacao == null ){ 
       $this->erro_sql = " Campo Filiação não informado.";
       $this->erro_campo = "ed47_i_filiacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed47_i_censoufend == null ){ 
       $this->ed47_i_censoufend = "null";
     }
     if($this->ed47_i_censomunicend == null ){ 
       $this->ed47_i_censomunicend = "null";
     }
     if($this->ed47_i_censoorgemissrg == null ){ 
       $this->ed47_i_censoorgemissrg = "null";
     }
     if($this->ed47_i_censoufident == null ){ 
       $this->ed47_i_censoufident = "null";
     }
     if($this->ed47_i_censoufcert == null ){ 
       $this->ed47_i_censoufcert = "null";
     }
     if($this->ed47_i_censomuniccert == null ){ 
       $this->ed47_i_censomuniccert = "null";
     }
     if($this->ed47_i_censoufnat == null || $this->ed47_i_censoufnat == " " ){
       $this->ed47_i_censoufnat = "null";
     }
     if($this->ed47_i_censomunicnat == null || $this->ed47_i_censomunicnat == " " ){
       $this->ed47_i_censomunicnat = "null";
     }
     if($this->ed47_i_atendespec == null ){ 
       $this->ed47_i_atendespec = "null";
     }
     if($this->ed47_i_censocartorio == null ){ 
       $this->ed47_i_censocartorio = "null";
     }
     if($this->ed47_situacaodocumentacao == null ){ 
       $this->erro_sql = " Campo Situação Documentação do aluno não informado.";
       $this->erro_campo = "ed47_situacaodocumentacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed47_tiposanguineo == null ){ 
       $this->ed47_tiposanguineo = "null";
     }
     if($ed47_i_codigo == "" || $ed47_i_codigo == null ){
       $result = db_query("select nextval('aluno_ed47_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aluno_ed47_i_codigo_seq do campo: ed47_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed47_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from aluno_ed47_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed47_i_codigo)){
         $this->erro_sql = " Campo ed47_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed47_i_codigo = $ed47_i_codigo; 
       }
     }
     if(($this->ed47_i_codigo == null) || ($this->ed47_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed47_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aluno(
                                       ed47_i_codigo 
                                      ,ed47_v_nome 
                                      ,ed47_v_ender 
                                      ,ed47_c_numero 
                                      ,ed47_v_compl 
                                      ,ed47_v_bairro 
                                      ,ed47_v_cep 
                                      ,ed47_c_raca 
                                      ,ed47_v_cxpostal 
                                      ,ed47_v_telef 
                                      ,ed47_d_cadast 
                                      ,ed47_v_ident 
                                      ,ed47_i_login 
                                      ,ed47_c_nomeresp 
                                      ,ed47_c_emailresp 
                                      ,ed47_c_atenddifer 
                                      ,ed47_t_obs 
                                      ,ed47_c_transporte 
                                      ,ed47_c_zona 
                                      ,ed47_certidaomatricula 
                                      ,ed47_c_certidaotipo 
                                      ,ed47_c_certidaonum 
                                      ,ed47_c_certidaolivro 
                                      ,ed47_c_certidaofolha 
                                      ,ed47_c_certidaocart 
                                      ,ed47_c_certidaodata 
                                      ,ed47_c_nis 
                                      ,ed47_c_bolsafamilia 
                                      ,ed47_c_passivo 
                                      ,ed47_d_dtemissao 
                                      ,ed47_d_dthabilitacao 
                                      ,ed47_d_dtvencimento 
                                      ,ed47_d_nasc 
                                      ,ed47_d_ultalt 
                                      ,ed47_i_estciv 
                                      ,ed47_i_nacion 
                                      ,ed47_v_categoria 
                                      ,ed47_v_cnh 
                                      ,ed47_v_contato 
                                      ,ed47_v_cpf 
                                      ,ed47_v_email 
                                      ,ed47_v_fax 
                                      ,ed47_v_hora 
                                      ,ed47_v_mae 
                                      ,ed47_v_pai 
                                      ,ed47_v_profis 
                                      ,ed47_v_sexo 
                                      ,ed47_v_telcel 
                                      ,ed47_c_foto 
                                      ,ed47_o_oid 
                                      ,ed47_c_codigoinep 
                                      ,ed47_i_pais 
                                      ,ed47_d_identdtexp 
                                      ,ed47_v_identcompl 
                                      ,ed47_c_passaporte 
                                      ,ed47_i_transpublico 
                                      ,ed47_i_filiacao 
                                      ,ed47_i_censoufend 
                                      ,ed47_i_censomunicend 
                                      ,ed47_i_censoorgemissrg 
                                      ,ed47_i_censoufident 
                                      ,ed47_i_censoufcert 
                                      ,ed47_i_censomuniccert 
                                      ,ed47_i_censoufnat 
                                      ,ed47_i_censomunicnat 
                                      ,ed47_i_atendespec 
                                      ,ed47_i_censocartorio 
                                      ,ed47_celularresponsavel 
                                      ,ed47_situacaodocumentacao 
                                      ,ed47_cartaosus 
                                      ,ed47_tiposanguineo 
                                      ,ed47_municipioestrangeiro 
                       )
                values (
                                $this->ed47_i_codigo 
                               ,'$this->ed47_v_nome' 
                               ,'$this->ed47_v_ender' 
                               ,'$this->ed47_c_numero' 
                               ,'$this->ed47_v_compl' 
                               ,'$this->ed47_v_bairro' 
                               ,'$this->ed47_v_cep' 
                               ,'$this->ed47_c_raca' 
                               ,'$this->ed47_v_cxpostal' 
                               ,'$this->ed47_v_telef' 
                               ,".($this->ed47_d_cadast == "null" || $this->ed47_d_cadast == ""?"null":"'".$this->ed47_d_cadast."'")." 
                               ,'$this->ed47_v_ident' 
                               ,$this->ed47_i_login 
                               ,'$this->ed47_c_nomeresp' 
                               ,'$this->ed47_c_emailresp' 
                               ,'$this->ed47_c_atenddifer' 
                               ,'$this->ed47_t_obs' 
                               ,'$this->ed47_c_transporte' 
                               ,'$this->ed47_c_zona' 
                               ,'$this->ed47_certidaomatricula' 
                               ,'$this->ed47_c_certidaotipo' 
                               ,'$this->ed47_c_certidaonum' 
                               ,'$this->ed47_c_certidaolivro' 
                               ,'$this->ed47_c_certidaofolha' 
                               ,'$this->ed47_c_certidaocart' 
                               ,".($this->ed47_c_certidaodata == "null" || $this->ed47_c_certidaodata == ""?"null":"'".$this->ed47_c_certidaodata."'")." 
                               ,'$this->ed47_c_nis' 
                               ,'$this->ed47_c_bolsafamilia' 
                               ,'$this->ed47_c_passivo' 
                               ,".($this->ed47_d_dtemissao == "null" || $this->ed47_d_dtemissao == ""?"null":"'".$this->ed47_d_dtemissao."'")." 
                               ,".($this->ed47_d_dthabilitacao == "null" || $this->ed47_d_dthabilitacao == ""?"null":"'".$this->ed47_d_dthabilitacao."'")." 
                               ,".($this->ed47_d_dtvencimento == "null" || $this->ed47_d_dtvencimento == ""?"null":"'".$this->ed47_d_dtvencimento."'")." 
                               ,".($this->ed47_d_nasc == "null" || $this->ed47_d_nasc == ""?"null":"'".$this->ed47_d_nasc."'")." 
                               ,".($this->ed47_d_ultalt == "null" || $this->ed47_d_ultalt == ""?"null":"'".$this->ed47_d_ultalt."'")." 
                               ,$this->ed47_i_estciv 
                               ,$this->ed47_i_nacion 
                               ,'$this->ed47_v_categoria' 
                               ,'$this->ed47_v_cnh' 
                               ,'$this->ed47_v_contato' 
                               ,'$this->ed47_v_cpf' 
                               ,'$this->ed47_v_email' 
                               ,'$this->ed47_v_fax' 
                               ,'$this->ed47_v_hora' 
                               ,'$this->ed47_v_mae' 
                               ,'$this->ed47_v_pai' 
                               ,'$this->ed47_v_profis' 
                               ,'$this->ed47_v_sexo' 
                               ,'$this->ed47_v_telcel' 
                               ,'$this->ed47_c_foto' 
                               ,$this->ed47_o_oid 
                               ,'$this->ed47_c_codigoinep' 
                               ,$this->ed47_i_pais 
                               ,".($this->ed47_d_identdtexp == "null" || $this->ed47_d_identdtexp == ""?"null":"'".$this->ed47_d_identdtexp."'")." 
                               ,'$this->ed47_v_identcompl' 
                               ,'$this->ed47_c_passaporte' 
                               ,$this->ed47_i_transpublico 
                               ,$this->ed47_i_filiacao 
                               ,$this->ed47_i_censoufend 
                               ,$this->ed47_i_censomunicend 
                               ,$this->ed47_i_censoorgemissrg 
                               ,$this->ed47_i_censoufident 
                               ,$this->ed47_i_censoufcert 
                               ,$this->ed47_i_censomuniccert 
                               ,$this->ed47_i_censoufnat 
                               ,$this->ed47_i_censomunicnat 
                               ,$this->ed47_i_atendespec 
                               ,$this->ed47_i_censocartorio 
                               ,'$this->ed47_celularresponsavel' 
                               ,$this->ed47_situacaodocumentacao 
                               ,'$this->ed47_cartaosus' 
                               ,$this->ed47_tiposanguineo 
                               ,'$this->ed47_municipioestrangeiro' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Alunos ($this->ed47_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Alunos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Alunos ($this->ed47_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed47_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed47_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008287,'$this->ed47_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010051,1008287,'','".AddSlashes(pg_result($resaco,0,'ed47_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008867,'','".AddSlashes(pg_result($resaco,0,'ed47_v_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008868,'','".AddSlashes(pg_result($resaco,0,'ed47_v_ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008869,'','".AddSlashes(pg_result($resaco,0,'ed47_c_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008870,'','".AddSlashes(pg_result($resaco,0,'ed47_v_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008871,'','".AddSlashes(pg_result($resaco,0,'ed47_v_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008874,'','".AddSlashes(pg_result($resaco,0,'ed47_v_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008289,'','".AddSlashes(pg_result($resaco,0,'ed47_c_raca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008875,'','".AddSlashes(pg_result($resaco,0,'ed47_v_cxpostal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008877,'','".AddSlashes(pg_result($resaco,0,'ed47_v_telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008876,'','".AddSlashes(pg_result($resaco,0,'ed47_d_cadast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008878,'','".AddSlashes(pg_result($resaco,0,'ed47_v_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008879,'','".AddSlashes(pg_result($resaco,0,'ed47_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008295,'','".AddSlashes(pg_result($resaco,0,'ed47_c_nomeresp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008296,'','".AddSlashes(pg_result($resaco,0,'ed47_c_emailresp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008290,'','".AddSlashes(pg_result($resaco,0,'ed47_c_atenddifer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008292,'','".AddSlashes(pg_result($resaco,0,'ed47_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008293,'','".AddSlashes(pg_result($resaco,0,'ed47_c_transporte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008294,'','".AddSlashes(pg_result($resaco,0,'ed47_c_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,18283,'','".AddSlashes(pg_result($resaco,0,'ed47_certidaomatricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008297,'','".AddSlashes(pg_result($resaco,0,'ed47_c_certidaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008298,'','".AddSlashes(pg_result($resaco,0,'ed47_c_certidaonum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008299,'','".AddSlashes(pg_result($resaco,0,'ed47_c_certidaolivro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008300,'','".AddSlashes(pg_result($resaco,0,'ed47_c_certidaofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008301,'','".AddSlashes(pg_result($resaco,0,'ed47_c_certidaocart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008302,'','".AddSlashes(pg_result($resaco,0,'ed47_c_certidaodata'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008303,'','".AddSlashes(pg_result($resaco,0,'ed47_c_nis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008304,'','".AddSlashes(pg_result($resaco,0,'ed47_c_bolsafamilia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008305,'','".AddSlashes(pg_result($resaco,0,'ed47_c_passivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008907,'','".AddSlashes(pg_result($resaco,0,'ed47_d_dtemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008908,'','".AddSlashes(pg_result($resaco,0,'ed47_d_dthabilitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008909,'','".AddSlashes(pg_result($resaco,0,'ed47_d_dtvencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008898,'','".AddSlashes(pg_result($resaco,0,'ed47_d_nasc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008902,'','".AddSlashes(pg_result($resaco,0,'ed47_d_ultalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008894,'','".AddSlashes(pg_result($resaco,0,'ed47_i_estciv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008893,'','".AddSlashes(pg_result($resaco,0,'ed47_i_nacion'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008906,'','".AddSlashes(pg_result($resaco,0,'ed47_v_categoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008905,'','".AddSlashes(pg_result($resaco,0,'ed47_v_cnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008903,'','".AddSlashes(pg_result($resaco,0,'ed47_v_contato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008896,'','".AddSlashes(pg_result($resaco,0,'ed47_v_cpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008881,'','".AddSlashes(pg_result($resaco,0,'ed47_v_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008897,'','".AddSlashes(pg_result($resaco,0,'ed47_v_fax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008904,'','".AddSlashes(pg_result($resaco,0,'ed47_v_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008900,'','".AddSlashes(pg_result($resaco,0,'ed47_v_mae'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008899,'','".AddSlashes(pg_result($resaco,0,'ed47_v_pai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008895,'','".AddSlashes(pg_result($resaco,0,'ed47_v_profis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008901,'','".AddSlashes(pg_result($resaco,0,'ed47_v_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008880,'','".AddSlashes(pg_result($resaco,0,'ed47_v_telcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1009178,'','".AddSlashes(pg_result($resaco,0,'ed47_c_foto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1009184,'','".AddSlashes(pg_result($resaco,0,'ed47_o_oid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,11289,'','".AddSlashes(pg_result($resaco,0,'ed47_c_codigoinep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,11290,'','".AddSlashes(pg_result($resaco,0,'ed47_i_pais'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,11293,'','".AddSlashes(pg_result($resaco,0,'ed47_d_identdtexp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,11294,'','".AddSlashes(pg_result($resaco,0,'ed47_v_identcompl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,11297,'','".AddSlashes(pg_result($resaco,0,'ed47_c_passaporte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,13486,'','".AddSlashes(pg_result($resaco,0,'ed47_i_transpublico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,13482,'','".AddSlashes(pg_result($resaco,0,'ed47_i_filiacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,13635,'','".AddSlashes(pg_result($resaco,0,'ed47_i_censoufend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,13636,'','".AddSlashes(pg_result($resaco,0,'ed47_i_censomunicend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,13485,'','".AddSlashes(pg_result($resaco,0,'ed47_i_censoorgemissrg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,13633,'','".AddSlashes(pg_result($resaco,0,'ed47_i_censoufident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,13634,'','".AddSlashes(pg_result($resaco,0,'ed47_i_censoufcert'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,13639,'','".AddSlashes(pg_result($resaco,0,'ed47_i_censomuniccert'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,1008291,'','".AddSlashes(pg_result($resaco,0,'ed47_i_censoufnat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,13484,'','".AddSlashes(pg_result($resaco,0,'ed47_i_censomunicnat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,13638,'','".AddSlashes(pg_result($resaco,0,'ed47_i_atendespec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,18010,'','".AddSlashes(pg_result($resaco,0,'ed47_i_censocartorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,19269,'','".AddSlashes(pg_result($resaco,0,'ed47_celularresponsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,19802,'','".AddSlashes(pg_result($resaco,0,'ed47_situacaodocumentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,20499,'','".AddSlashes(pg_result($resaco,0,'ed47_cartaosus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,20504,'','".AddSlashes(pg_result($resaco,0,'ed47_tiposanguineo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010051,22180,'','".AddSlashes(pg_result($resaco,0,'ed47_municipioestrangeiro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($ed47_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update aluno set ";
     $virgula = "";
     if(trim($this->ed47_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_codigo"])){ 
       $sql  .= $virgula." ed47_i_codigo = $this->ed47_i_codigo ";
       $virgula = ",";
       if(trim($this->ed47_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed47_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed47_v_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_nome"])){ 
       $sql  .= $virgula." ed47_v_nome = '$this->ed47_v_nome' ";
       $virgula = ",";
       if(trim($this->ed47_v_nome) == null ){ 
         $this->erro_sql = " Campo Nome não informado.";
         $this->erro_campo = "ed47_v_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed47_v_ender)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_ender"])){ 
       $sql  .= $virgula." ed47_v_ender = '$this->ed47_v_ender' ";
       $virgula = ",";
       if(trim($this->ed47_v_ender) == null ){ 
         $this->erro_sql = " Campo Endereço não informado.";
         $this->erro_campo = "ed47_v_ender";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed47_c_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_numero"])){ 
       $sql  .= $virgula." ed47_c_numero = '$this->ed47_c_numero' ";
       $virgula = ",";
     }
     if(trim($this->ed47_v_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_compl"])){ 
       $sql  .= $virgula." ed47_v_compl = '$this->ed47_v_compl' ";
       $virgula = ",";
     }
     if(trim($this->ed47_v_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_bairro"])){ 
       $sql  .= $virgula." ed47_v_bairro = '$this->ed47_v_bairro' ";
       $virgula = ",";
     }
     if(trim($this->ed47_v_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_cep"])){ 
       $sql  .= $virgula." ed47_v_cep = '$this->ed47_v_cep' ";
       $virgula = ",";
     }
     if(trim($this->ed47_c_raca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_raca"])){ 
       $sql  .= $virgula." ed47_c_raca = '$this->ed47_c_raca' ";
       $virgula = ",";
       if(trim($this->ed47_c_raca) == null ){ 
         $this->erro_sql = " Campo Raça não informado.";
         $this->erro_campo = "ed47_c_raca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed47_v_cxpostal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_cxpostal"])){ 
       $sql  .= $virgula." ed47_v_cxpostal = '$this->ed47_v_cxpostal' ";
       $virgula = ",";
     }
     if(trim($this->ed47_v_telef)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_telef"])){ 
       $sql  .= $virgula." ed47_v_telef = '$this->ed47_v_telef' ";
       $virgula = ",";
     }
     if(trim($this->ed47_d_cadast)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_cadast_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed47_d_cadast_dia"] !="") ){ 
       $sql  .= $virgula." ed47_d_cadast = '$this->ed47_d_cadast' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_cadast_dia"])){ 
         $sql  .= $virgula." ed47_d_cadast = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ed47_v_ident)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_ident"])){ 
       $sql  .= $virgula." ed47_v_ident = '$this->ed47_v_ident' ";
       $virgula = ",";
     }
     if(trim($this->ed47_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_login"])){ 
        if(trim($this->ed47_i_login)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_login"])){ 
           $this->ed47_i_login = "0" ; 
        } 
       $sql  .= $virgula." ed47_i_login = $this->ed47_i_login ";
       $virgula = ",";
     }
     if(trim($this->ed47_c_nomeresp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_nomeresp"])){ 
       $sql  .= $virgula." ed47_c_nomeresp = '$this->ed47_c_nomeresp' ";
       $virgula = ",";
     }
     if(trim($this->ed47_c_emailresp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_emailresp"])){ 
       $sql  .= $virgula." ed47_c_emailresp = '$this->ed47_c_emailresp' ";
       $virgula = ",";
     }
     if(trim($this->ed47_c_atenddifer)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_atenddifer"])){ 
       $sql  .= $virgula." ed47_c_atenddifer = '$this->ed47_c_atenddifer' ";
       $virgula = ",";
       if(trim($this->ed47_c_atenddifer) == null ){ 
         $this->erro_sql = " Campo Recebe Escolarização em Outro Espaço não informado.";
         $this->erro_campo = "ed47_c_atenddifer";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed47_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_t_obs"])){ 
       $sql  .= $virgula." ed47_t_obs = '$this->ed47_t_obs' ";
       $virgula = ",";
     }
     if(trim($this->ed47_c_transporte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_transporte"])){ 
       $sql  .= $virgula." ed47_c_transporte = '$this->ed47_c_transporte' ";
       $virgula = ",";
     }
     if(trim($this->ed47_c_zona)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_zona"])){ 
       $sql  .= $virgula." ed47_c_zona = '$this->ed47_c_zona' ";
       $virgula = ",";
     }
     if(trim($this->ed47_certidaomatricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_certidaomatricula"])){ 
       $sql  .= $virgula." ed47_certidaomatricula = '$this->ed47_certidaomatricula' ";
       $virgula = ",";
     }
     if(trim($this->ed47_c_certidaotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaotipo"])){ 
       $sql  .= $virgula." ed47_c_certidaotipo = '$this->ed47_c_certidaotipo' ";
       $virgula = ",";
     }
     if(trim($this->ed47_c_certidaonum)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaonum"])){ 
       $sql  .= $virgula." ed47_c_certidaonum = '$this->ed47_c_certidaonum' ";
       $virgula = ",";
     }
     if(trim($this->ed47_c_certidaolivro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaolivro"])){ 
       $sql  .= $virgula." ed47_c_certidaolivro = '$this->ed47_c_certidaolivro' ";
       $virgula = ",";
     }
     if(trim($this->ed47_c_certidaofolha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaofolha"])){ 
       $sql  .= $virgula." ed47_c_certidaofolha = '$this->ed47_c_certidaofolha' ";
       $virgula = ",";
     }

     $sql    .= $virgula . " ed47_c_certidaocart = '{$this->ed47_c_certidaocart}' ";
       $virgula = ",";

     if(trim($this->ed47_c_certidaodata)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaodata_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaodata_dia"] !="") ){ 
       $sql  .= $virgula." ed47_c_certidaodata = '$this->ed47_c_certidaodata' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaodata_dia"])){ 
         $sql  .= $virgula." ed47_c_certidaodata = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ed47_c_nis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_nis"])){ 
       $sql  .= $virgula." ed47_c_nis = '$this->ed47_c_nis' ";
       $virgula = ",";
     }
     if(trim($this->ed47_c_bolsafamilia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_bolsafamilia"])){ 
       $sql  .= $virgula." ed47_c_bolsafamilia = '$this->ed47_c_bolsafamilia' ";
       $virgula = ",";
     }
     if(trim($this->ed47_c_passivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_passivo"])){ 
       $sql  .= $virgula." ed47_c_passivo = '$this->ed47_c_passivo' ";
       $virgula = ",";
     }
     if(trim($this->ed47_d_dtemissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_dtemissao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed47_d_dtemissao_dia"] !="") ){ 
       $sql  .= $virgula." ed47_d_dtemissao = '$this->ed47_d_dtemissao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_dtemissao_dia"])){ 
         $sql  .= $virgula." ed47_d_dtemissao = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ed47_d_dthabilitacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_dthabilitacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed47_d_dthabilitacao_dia"] !="") ){ 
       $sql  .= $virgula." ed47_d_dthabilitacao = '$this->ed47_d_dthabilitacao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_dthabilitacao_dia"])){ 
         $sql  .= $virgula." ed47_d_dthabilitacao = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ed47_d_dtvencimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_dtvencimento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed47_d_dtvencimento_dia"] !="") ){ 
       $sql  .= $virgula." ed47_d_dtvencimento = '$this->ed47_d_dtvencimento' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_dtvencimento_dia"])){ 
         $sql  .= $virgula." ed47_d_dtvencimento = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ed47_d_nasc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_nasc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed47_d_nasc_dia"] !="") ){ 
       $sql  .= $virgula." ed47_d_nasc = '$this->ed47_d_nasc' ";
       $virgula = ",";
       if(trim($this->ed47_d_nasc) == null ){ 
         $this->erro_sql = " Campo Nascimento não informado.";
         $this->erro_campo = "ed47_d_nasc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_nasc_dia"])){ 
         $sql  .= $virgula." ed47_d_nasc = null ";
         $virgula = ",";
         if(trim($this->ed47_d_nasc) == null ){ 
           $this->erro_sql = " Campo Nascimento não informado.";
           $this->erro_campo = "ed47_d_nasc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed47_d_ultalt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_ultalt_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed47_d_ultalt_dia"] !="") ){ 
       $sql  .= $virgula." ed47_d_ultalt = '$this->ed47_d_ultalt' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_ultalt_dia"])){ 
         $sql  .= $virgula." ed47_d_ultalt = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ed47_i_estciv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_estciv"])){ 
        if(trim($this->ed47_i_estciv)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_estciv"])){ 
           $this->ed47_i_estciv = "0" ; 
        } 
       $sql  .= $virgula." ed47_i_estciv = $this->ed47_i_estciv ";
       $virgula = ",";
     }
     if(trim($this->ed47_i_nacion)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_nacion"])){ 
       $sql  .= $virgula." ed47_i_nacion = $this->ed47_i_nacion ";
       $virgula = ",";
       if(trim($this->ed47_i_nacion) == null ){ 
         $this->erro_sql = " Campo Nacionalidade não informado.";
         $this->erro_campo = "ed47_i_nacion";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed47_v_categoria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_categoria"])){ 
       $sql  .= $virgula." ed47_v_categoria = '$this->ed47_v_categoria' ";
       $virgula = ",";
     }
     if(trim($this->ed47_v_cnh)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_cnh"])){ 
       $sql  .= $virgula." ed47_v_cnh = '$this->ed47_v_cnh' ";
       $virgula = ",";
     }
     if(trim($this->ed47_v_contato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_contato"])){ 
       $sql  .= $virgula." ed47_v_contato = '$this->ed47_v_contato' ";
       $virgula = ",";
     }
     if(trim($this->ed47_v_cpf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_cpf"])){ 
       $sql  .= $virgula." ed47_v_cpf = '$this->ed47_v_cpf' ";
       $virgula = ",";
     }
     if(trim($this->ed47_v_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_email"])){ 
       $sql  .= $virgula." ed47_v_email = '$this->ed47_v_email' ";
       $virgula = ",";
     }
     if(trim($this->ed47_v_fax)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_fax"])){ 
       $sql  .= $virgula." ed47_v_fax = '$this->ed47_v_fax' ";
       $virgula = ",";
     }
     if(trim($this->ed47_v_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_hora"])){ 
       $sql  .= $virgula." ed47_v_hora = '$this->ed47_v_hora' ";
       $virgula = ",";
     }
     if(trim($this->ed47_v_mae)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_mae"])){ 
       $sql  .= $virgula." ed47_v_mae = '$this->ed47_v_mae' ";
       $virgula = ",";
     }
     if(trim($this->ed47_v_pai)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_pai"])){ 
       $sql  .= $virgula." ed47_v_pai = '$this->ed47_v_pai' ";
       $virgula = ",";
     }
     if(trim($this->ed47_v_profis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_profis"])){ 
       $sql  .= $virgula." ed47_v_profis = '$this->ed47_v_profis' ";
       $virgula = ",";
     }
     if(trim($this->ed47_v_sexo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_sexo"])){ 
       $sql  .= $virgula." ed47_v_sexo = '$this->ed47_v_sexo' ";
       $virgula = ",";
       if(trim($this->ed47_v_sexo) == null ){ 
         $this->erro_sql = " Campo Sexo não informado.";
         $this->erro_campo = "ed47_v_sexo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed47_v_telcel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_telcel"])){ 
       $sql  .= $virgula." ed47_v_telcel = '$this->ed47_v_telcel' ";
       $virgula = ",";
     }
     if(trim($this->ed47_c_foto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_foto"])){ 
       $sql  .= $virgula." ed47_c_foto = '$this->ed47_c_foto' ";
       $virgula = ",";
     }
     if(trim($this->ed47_o_oid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_o_oid"])){ 
       $sql  .= $virgula." ed47_o_oid = $this->ed47_o_oid ";
       $virgula = ",";
       if(trim($this->ed47_o_oid) == null ){ 
         $this->erro_sql = " Campo Imagem não informado.";
         $this->erro_campo = "ed47_o_oid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed47_c_codigoinep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_codigoinep"])){ 
       $sql  .= $virgula." ed47_c_codigoinep = '$this->ed47_c_codigoinep' ";
       $virgula = ",";
     }
     if(trim($this->ed47_i_pais)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_pais"])){ 
       $sql  .= $virgula." ed47_i_pais = $this->ed47_i_pais ";
       $virgula = ",";
       if(trim($this->ed47_i_pais) == null ){ 
         $this->erro_sql = " Campo País não informado.";
         $this->erro_campo = "ed47_i_pais";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed47_d_identdtexp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_identdtexp_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed47_d_identdtexp_dia"] !="") ){ 
       $sql  .= $virgula." ed47_d_identdtexp = '$this->ed47_d_identdtexp' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_identdtexp_dia"])){ 
         $sql  .= $virgula." ed47_d_identdtexp = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ed47_v_identcompl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_identcompl"])){ 
       $sql  .= $virgula." ed47_v_identcompl = '$this->ed47_v_identcompl' ";
       $virgula = ",";
     }
     if(trim($this->ed47_c_passaporte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_passaporte"])){ 
       $sql  .= $virgula." ed47_c_passaporte = '$this->ed47_c_passaporte' ";
       $virgula = ",";
     }
     if(trim($this->ed47_i_transpublico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_transpublico"])){ 
       $sql  .= $virgula." ed47_i_transpublico = $this->ed47_i_transpublico ";
       $virgula = ",";
       if(trim($this->ed47_i_transpublico) == null ){ 
         $this->erro_sql = " Campo Transporte Escolar Público não informado.";
         $this->erro_campo = "ed47_i_transpublico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed47_i_filiacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_filiacao"])){ 
       $sql  .= $virgula." ed47_i_filiacao = $this->ed47_i_filiacao ";
       $virgula = ",";
       if(trim($this->ed47_i_filiacao) == null ){ 
         $this->erro_sql = " Campo Filiação não informado.";
         $this->erro_campo = "ed47_i_filiacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed47_i_censoufend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censoufend"])){ 
        if(trim($this->ed47_i_censoufend)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censoufend"])){ 
           $this->ed47_i_censoufend = "0" ; 
        } 
       $sql  .= $virgula." ed47_i_censoufend = $this->ed47_i_censoufend ";
       $virgula = ",";
     }

     if(trim($this->ed47_i_censomunicend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censomunicend"])){ 
        if(trim($this->ed47_i_censomunicend)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censomunicend"])){ 
           $this->ed47_i_censomunicend = "0" ; 
        } 
       $sql  .= $virgula." ed47_i_censomunicend = $this->ed47_i_censomunicend ";
       $virgula = ",";
     }

     if( isset($this->ed47_i_censoorgemissrg) ) {

        if(trim( $this->ed47_i_censoorgemissrg ) == '') {
          $this->ed47_i_censoorgemissrg = 'null';
        } 
        $sql     .= $virgula . " ed47_i_censoorgemissrg = {$this->ed47_i_censoorgemissrg} ";
       $virgula = ",";
     }

     if ( isset($this->ed47_i_censoufident) ) {

        if(trim($this->ed47_i_censoufident) == '') {
          $this->ed47_i_censoufident = 'null';
        } 
        $sql     .= $virgula . " ed47_i_censoufident = {$this->ed47_i_censoufident} ";
       $virgula = ",";
     }

     if ( isset($this->ed47_i_censoufcert) ) {

       if( trim( $this->ed47_i_censoufcert ) == '') {
         $this->ed47_i_censoufcert = 'null';
        } 
       $sql     .= $virgula . " ed47_i_censoufcert = {$this->ed47_i_censoufcert} ";
       $virgula = ",";
     }

     if ( isset($this->ed47_i_censomuniccert) ) {

       if( trim( $this->ed47_i_censomuniccert ) == '' ) {
         $this->ed47_i_censomuniccert = 'null';
        } 
       $sql     .= $virgula . " ed47_i_censomuniccert = {$this->ed47_i_censomuniccert} ";
       $virgula = ",";
     }

     if(trim($this->ed47_i_censoufnat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censoufnat"])){ 
        if(trim($this->ed47_i_censoufnat)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censoufnat"])){ 
           $this->ed47_i_censoufnat = "null" ;
        } 
       $sql  .= $virgula." ed47_i_censoufnat = $this->ed47_i_censoufnat ";
       $virgula = ",";
     }
     if(trim($this->ed47_i_censomunicnat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censomunicnat"])){ 
        if(trim($this->ed47_i_censomunicnat)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censomunicnat"])){ 
           $this->ed47_i_censomunicnat = "null" ;
        } 
       $sql  .= $virgula." ed47_i_censomunicnat = $this->ed47_i_censomunicnat ";
       $virgula = ",";
     }
     if(trim($this->ed47_i_atendespec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_atendespec"])){ 
        if(trim($this->ed47_i_atendespec)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_atendespec"])){ 
           $this->ed47_i_atendespec = "null" ;
        } 
       $sql  .= $virgula." ed47_i_atendespec = $this->ed47_i_atendespec ";
       $virgula = ",";
     }
     if(trim($this->ed47_i_censocartorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censocartorio"])){ 
        if(trim($this->ed47_i_censocartorio)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censocartorio"])){ 
           $this->ed47_i_censocartorio = "null" ;
        } 
       $sql  .= $virgula." ed47_i_censocartorio = $this->ed47_i_censocartorio ";
       $virgula = ",";
     }
     if(trim($this->ed47_celularresponsavel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_celularresponsavel"])){ 
       $sql  .= $virgula." ed47_celularresponsavel = '$this->ed47_celularresponsavel' ";
       $virgula = ",";
     }
     if(trim($this->ed47_situacaodocumentacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_situacaodocumentacao"])){ 
       $sql  .= $virgula." ed47_situacaodocumentacao = $this->ed47_situacaodocumentacao ";
       $virgula = ",";
       if(trim($this->ed47_situacaodocumentacao) == null ){ 
         $this->erro_sql = " Campo Situação Documentação do aluno não informado.";
         $this->erro_campo = "ed47_situacaodocumentacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed47_cartaosus)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_cartaosus"])){ 
       $sql  .= $virgula." ed47_cartaosus = '$this->ed47_cartaosus' ";
       $virgula = ",";
     }
     if(trim($this->ed47_tiposanguineo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_tiposanguineo"])){ 

       $sql  .= $virgula." ed47_tiposanguineo = $this->ed47_tiposanguineo ";
       $virgula = ",";
     }
     if(trim($this->ed47_municipioestrangeiro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_municipioestrangeiro"])){ 
       $sql  .= $virgula." ed47_municipioestrangeiro = '$this->ed47_municipioestrangeiro' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed47_i_codigo!=null){
       $sql .= " ed47_i_codigo = $this->ed47_i_codigo";
     }

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed47_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008287,'$this->ed47_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_codigo"]) || $this->ed47_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008287,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_i_codigo'))."','$this->ed47_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_nome"]) || $this->ed47_v_nome != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008867,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_v_nome'))."','$this->ed47_v_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_ender"]) || $this->ed47_v_ender != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008868,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_v_ender'))."','$this->ed47_v_ender',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_numero"]) || $this->ed47_c_numero != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008869,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_c_numero'))."','$this->ed47_c_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_compl"]) || $this->ed47_v_compl != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008870,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_v_compl'))."','$this->ed47_v_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_bairro"]) || $this->ed47_v_bairro != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008871,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_v_bairro'))."','$this->ed47_v_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_cep"]) || $this->ed47_v_cep != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008874,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_v_cep'))."','$this->ed47_v_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_raca"]) || $this->ed47_c_raca != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008289,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_c_raca'))."','$this->ed47_c_raca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_cxpostal"]) || $this->ed47_v_cxpostal != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008875,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_v_cxpostal'))."','$this->ed47_v_cxpostal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_telef"]) || $this->ed47_v_telef != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008877,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_v_telef'))."','$this->ed47_v_telef',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_cadast"]) || $this->ed47_d_cadast != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008876,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_d_cadast'))."','$this->ed47_d_cadast',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_ident"]) || $this->ed47_v_ident != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008878,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_v_ident'))."','$this->ed47_v_ident',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_login"]) || $this->ed47_i_login != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008879,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_i_login'))."','$this->ed47_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_nomeresp"]) || $this->ed47_c_nomeresp != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008295,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_c_nomeresp'))."','$this->ed47_c_nomeresp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_emailresp"]) || $this->ed47_c_emailresp != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008296,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_c_emailresp'))."','$this->ed47_c_emailresp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_atenddifer"]) || $this->ed47_c_atenddifer != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008290,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_c_atenddifer'))."','$this->ed47_c_atenddifer',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_t_obs"]) || $this->ed47_t_obs != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008292,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_t_obs'))."','$this->ed47_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_transporte"]) || $this->ed47_c_transporte != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008293,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_c_transporte'))."','$this->ed47_c_transporte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_zona"]) || $this->ed47_c_zona != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008294,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_c_zona'))."','$this->ed47_c_zona',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_certidaomatricula"]) || $this->ed47_certidaomatricula != "")
             $resac = db_query("insert into db_acount values($acount,1010051,18283,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_certidaomatricula'))."','$this->ed47_certidaomatricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaotipo"]) || $this->ed47_c_certidaotipo != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008297,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_c_certidaotipo'))."','$this->ed47_c_certidaotipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaonum"]) || $this->ed47_c_certidaonum != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008298,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_c_certidaonum'))."','$this->ed47_c_certidaonum',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaolivro"]) || $this->ed47_c_certidaolivro != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008299,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_c_certidaolivro'))."','$this->ed47_c_certidaolivro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaofolha"]) || $this->ed47_c_certidaofolha != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008300,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_c_certidaofolha'))."','$this->ed47_c_certidaofolha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaocart"]) || $this->ed47_c_certidaocart != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008301,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_c_certidaocart'))."','$this->ed47_c_certidaocart',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaodata"]) || $this->ed47_c_certidaodata != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008302,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_c_certidaodata'))."','$this->ed47_c_certidaodata',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_nis"]) || $this->ed47_c_nis != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008303,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_c_nis'))."','$this->ed47_c_nis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_bolsafamilia"]) || $this->ed47_c_bolsafamilia != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008304,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_c_bolsafamilia'))."','$this->ed47_c_bolsafamilia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_passivo"]) || $this->ed47_c_passivo != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008305,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_c_passivo'))."','$this->ed47_c_passivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_dtemissao"]) || $this->ed47_d_dtemissao != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008907,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_d_dtemissao'))."','$this->ed47_d_dtemissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_dthabilitacao"]) || $this->ed47_d_dthabilitacao != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008908,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_d_dthabilitacao'))."','$this->ed47_d_dthabilitacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_dtvencimento"]) || $this->ed47_d_dtvencimento != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008909,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_d_dtvencimento'))."','$this->ed47_d_dtvencimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_nasc"]) || $this->ed47_d_nasc != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008898,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_d_nasc'))."','$this->ed47_d_nasc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_ultalt"]) || $this->ed47_d_ultalt != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008902,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_d_ultalt'))."','$this->ed47_d_ultalt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_estciv"]) || $this->ed47_i_estciv != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008894,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_i_estciv'))."','$this->ed47_i_estciv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_nacion"]) || $this->ed47_i_nacion != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008893,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_i_nacion'))."','$this->ed47_i_nacion',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_categoria"]) || $this->ed47_v_categoria != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008906,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_v_categoria'))."','$this->ed47_v_categoria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_cnh"]) || $this->ed47_v_cnh != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008905,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_v_cnh'))."','$this->ed47_v_cnh',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_contato"]) || $this->ed47_v_contato != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008903,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_v_contato'))."','$this->ed47_v_contato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_cpf"]) || $this->ed47_v_cpf != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008896,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_v_cpf'))."','$this->ed47_v_cpf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_email"]) || $this->ed47_v_email != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008881,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_v_email'))."','$this->ed47_v_email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_fax"]) || $this->ed47_v_fax != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008897,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_v_fax'))."','$this->ed47_v_fax',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_hora"]) || $this->ed47_v_hora != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008904,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_v_hora'))."','$this->ed47_v_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_mae"]) || $this->ed47_v_mae != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008900,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_v_mae'))."','$this->ed47_v_mae',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_pai"]) || $this->ed47_v_pai != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008899,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_v_pai'))."','$this->ed47_v_pai',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_profis"]) || $this->ed47_v_profis != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008895,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_v_profis'))."','$this->ed47_v_profis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_sexo"]) || $this->ed47_v_sexo != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008901,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_v_sexo'))."','$this->ed47_v_sexo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_telcel"]) || $this->ed47_v_telcel != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008880,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_v_telcel'))."','$this->ed47_v_telcel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_foto"]) || $this->ed47_c_foto != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1009178,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_c_foto'))."','$this->ed47_c_foto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_o_oid"]) || $this->ed47_o_oid != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1009184,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_o_oid'))."','$this->ed47_o_oid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_codigoinep"]) || $this->ed47_c_codigoinep != "")
             $resac = db_query("insert into db_acount values($acount,1010051,11289,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_c_codigoinep'))."','$this->ed47_c_codigoinep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_pais"]) || $this->ed47_i_pais != "")
             $resac = db_query("insert into db_acount values($acount,1010051,11290,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_i_pais'))."','$this->ed47_i_pais',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_identdtexp"]) || $this->ed47_d_identdtexp != "")
             $resac = db_query("insert into db_acount values($acount,1010051,11293,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_d_identdtexp'))."','$this->ed47_d_identdtexp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_identcompl"]) || $this->ed47_v_identcompl != "")
             $resac = db_query("insert into db_acount values($acount,1010051,11294,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_v_identcompl'))."','$this->ed47_v_identcompl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_passaporte"]) || $this->ed47_c_passaporte != "")
             $resac = db_query("insert into db_acount values($acount,1010051,11297,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_c_passaporte'))."','$this->ed47_c_passaporte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_transpublico"]) || $this->ed47_i_transpublico != "")
             $resac = db_query("insert into db_acount values($acount,1010051,13486,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_i_transpublico'))."','$this->ed47_i_transpublico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_filiacao"]) || $this->ed47_i_filiacao != "")
             $resac = db_query("insert into db_acount values($acount,1010051,13482,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_i_filiacao'))."','$this->ed47_i_filiacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censoufend"]) || $this->ed47_i_censoufend != "")
             $resac = db_query("insert into db_acount values($acount,1010051,13635,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_i_censoufend'))."','$this->ed47_i_censoufend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censomunicend"]) || $this->ed47_i_censomunicend != "")
             $resac = db_query("insert into db_acount values($acount,1010051,13636,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_i_censomunicend'))."','$this->ed47_i_censomunicend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censoorgemissrg"]) || $this->ed47_i_censoorgemissrg != "")
             $resac = db_query("insert into db_acount values($acount,1010051,13485,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_i_censoorgemissrg'))."','$this->ed47_i_censoorgemissrg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censoufident"]) || $this->ed47_i_censoufident != "")
             $resac = db_query("insert into db_acount values($acount,1010051,13633,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_i_censoufident'))."','$this->ed47_i_censoufident',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censoufcert"]) || $this->ed47_i_censoufcert != "")
             $resac = db_query("insert into db_acount values($acount,1010051,13634,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_i_censoufcert'))."','$this->ed47_i_censoufcert',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censomuniccert"]) || $this->ed47_i_censomuniccert != "")
             $resac = db_query("insert into db_acount values($acount,1010051,13639,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_i_censomuniccert'))."','$this->ed47_i_censomuniccert',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censoufnat"]) || $this->ed47_i_censoufnat != "")
             $resac = db_query("insert into db_acount values($acount,1010051,1008291,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_i_censoufnat'))."','$this->ed47_i_censoufnat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censomunicnat"]) || $this->ed47_i_censomunicnat != "")
             $resac = db_query("insert into db_acount values($acount,1010051,13484,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_i_censomunicnat'))."','$this->ed47_i_censomunicnat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_atendespec"]) || $this->ed47_i_atendespec != "")
             $resac = db_query("insert into db_acount values($acount,1010051,13638,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_i_atendespec'))."','$this->ed47_i_atendespec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censocartorio"]) || $this->ed47_i_censocartorio != "")
             $resac = db_query("insert into db_acount values($acount,1010051,18010,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_i_censocartorio'))."','$this->ed47_i_censocartorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_celularresponsavel"]) || $this->ed47_celularresponsavel != "")
             $resac = db_query("insert into db_acount values($acount,1010051,19269,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_celularresponsavel'))."','$this->ed47_celularresponsavel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_situacaodocumentacao"]) || $this->ed47_situacaodocumentacao != "")
             $resac = db_query("insert into db_acount values($acount,1010051,19802,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_situacaodocumentacao'))."','$this->ed47_situacaodocumentacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_cartaosus"]) || $this->ed47_cartaosus != "")
             $resac = db_query("insert into db_acount values($acount,1010051,20499,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_cartaosus'))."','$this->ed47_cartaosus',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_tiposanguineo"]) || $this->ed47_tiposanguineo != "")
             $resac = db_query("insert into db_acount values($acount,1010051,20504,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_tiposanguineo'))."','$this->ed47_tiposanguineo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed47_municipioestrangeiro"]) || $this->ed47_municipioestrangeiro != "")
             $resac = db_query("insert into db_acount values($acount,1010051,22180,'".AddSlashes(pg_result($resaco,$conresaco,'ed47_municipioestrangeiro'))."','$this->ed47_municipioestrangeiro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alunos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed47_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Alunos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed47_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed47_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($ed47_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed47_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008287,'$ed47_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008287,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008867,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_v_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008868,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_v_ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008869,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_c_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008870,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_v_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008871,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_v_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008874,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_v_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008289,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_c_raca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008875,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_v_cxpostal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008877,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_v_telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008876,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_d_cadast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008878,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_v_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008879,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008295,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_c_nomeresp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008296,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_c_emailresp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008290,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_c_atenddifer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008292,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008293,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_c_transporte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008294,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_c_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,18283,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_certidaomatricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008297,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_c_certidaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008298,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_c_certidaonum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008299,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_c_certidaolivro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008300,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_c_certidaofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008301,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_c_certidaocart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008302,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_c_certidaodata'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008303,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_c_nis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008304,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_c_bolsafamilia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008305,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_c_passivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008907,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_d_dtemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008908,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_d_dthabilitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008909,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_d_dtvencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008898,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_d_nasc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008902,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_d_ultalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008894,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_i_estciv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008893,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_i_nacion'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008906,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_v_categoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008905,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_v_cnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008903,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_v_contato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008896,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_v_cpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008881,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_v_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008897,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_v_fax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008904,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_v_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008900,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_v_mae'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008899,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_v_pai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008895,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_v_profis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008901,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_v_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008880,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_v_telcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1009178,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_c_foto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1009184,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_o_oid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,11289,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_c_codigoinep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,11290,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_i_pais'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,11293,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_d_identdtexp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,11294,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_v_identcompl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,11297,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_c_passaporte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,13486,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_i_transpublico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,13482,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_i_filiacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,13635,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_i_censoufend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,13636,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_i_censomunicend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,13485,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_i_censoorgemissrg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,13633,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_i_censoufident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,13634,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_i_censoufcert'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,13639,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_i_censomuniccert'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,1008291,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_i_censoufnat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,13484,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_i_censomunicnat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,13638,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_i_atendespec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,18010,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_i_censocartorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,19269,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_celularresponsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,19802,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_situacaodocumentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,20499,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_cartaosus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,20504,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_tiposanguineo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010051,22180,'','".AddSlashes(pg_result($resaco,$iresaco,'ed47_municipioestrangeiro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from aluno
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed47_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed47_i_codigo = $ed47_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alunos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed47_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Alunos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed47_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed47_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:aluno";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed47_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from aluno ";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = aluno.ed47_i_pais";
     $sql .= "      left  join censouf as censoufident on  censoufident.ed260_i_codigo = aluno.ed47_i_censoufident";
     $sql .= "      left  join censouf as censoufnat on  censoufnat.ed260_i_codigo = aluno.ed47_i_censoufnat";
     $sql .= "      left  join censouf as censoufcert on  censoufcert.ed260_i_codigo = aluno.ed47_i_censoufcert";
     $sql .= "      left  join censouf as censoufend on  censoufend.ed260_i_codigo = aluno.ed47_i_censoufend";
     $sql .= "      left  join censomunic as censomunicnat on  censomunicnat.ed261_i_codigo = aluno.ed47_i_censomunicnat";
     $sql .= "      left  join censomunic as censomuniccert on  censomuniccert.ed261_i_codigo = aluno.ed47_i_censomuniccert";
     $sql .= "      left  join censomunic as censomunicend on  censomunicend.ed261_i_codigo = aluno.ed47_i_censomunicend";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = aluno.ed47_i_censoorgemissrg";
     $sql .= "      left  join censocartorio  on  censocartorio.ed291_i_codigo = aluno.ed47_i_censocartorio";
     $sql .= "      left  join tiposanguineo  as d on   d.sd100_sequencial = aluno.ed47_tiposanguineo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed47_i_codigo!=null ){
         $sql2 .= " where aluno.ed47_i_codigo = $ed47_i_codigo ";
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
   // funcao do sql 
   function sql_query_file ( $ed47_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from aluno ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed47_i_codigo!=null ){
         $sql2 .= " where aluno.ed47_i_codigo = $ed47_i_codigo ";
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
   function sql_query_censo($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= " from aluno ";
    $sSql .= "      left join alunocurso on ed56_i_aluno = aluno.ed47_i_codigo";
    $sSql .= "      left join escola on ed18_i_codigo = alunocurso.ed56_i_escola";
    $sSql .= "      inner join pais on pais.ed228_i_codigo = aluno.ed47_i_pais";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where aluno.ed47_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }
   function sql_query_alunotrocaturma($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {
  
    $sSql = 'select ';
    if ($sCampos != '*') {
  
      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){
  
        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";
  
      }
  
    } else {
      $sSql .= $sCampos;
    }
    $sSql .= " from aluno ";
    $sSql .= "      inner join matricula      on ed47_i_codigo     = ed60_i_aluno ";
    $sSql .= "      inner join turma          on ed60_i_turma      = ed57_i_codigo ";
    $sSql .= "      inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
    $sSql .= "                               and ed221_c_origem    = 'S' ";
    $sSql .= "      inner join serie          on ed11_i_codigo     = ed221_i_serie ";
    $sSql .= "      inner join calendario     on ed52_i_codigo     = ed57_i_calendario ";
    $sSql .= "      inner join regencia       on ed59_i_turma      = ed57_i_codigo ";
    $sSql2 = '';
    if ($sDbWhere == '') {
  
      if ($iCodigo != null ){
        $sSql2 .= " where aluno.ed47_i_codigo = $iCodigo ";
      }
  
    } elseif ($sDbWhere != '') {
    $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;
  
    if ($sOrdem != null) {
  
    $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
            $sVirgula   = '';
            for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {
  
            $sSql    .= $sVirgula.$sCamposSql[$iCont];
            $sVirgula = ',';
  
            }
  
    }
    return $sSql;
}
   function sql_query_matr ( $ed47_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from aluno ";
     $sql .= "      inner join alunocurso  on  alunocurso.ed56_i_aluno = aluno.ed47_i_codigo";
     $sql .= "      inner join alunopossib  on  alunopossib.ed79_i_alunocurso = alunocurso.ed56_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed47_i_codigo!=null ){
         $sql2 .= " where aluno.ed47_i_codigo = $ed47_i_codigo ";
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
   function sql_query_atestvaga($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') { 

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= " from aluno ";
    $sSql .= "      left join alunocurso on alunocurso.ed56_i_aluno = aluno.ed47_i_codigo";
    $sSql .= "      left join escola on escola.ed18_i_codigo = alunocurso.ed56_i_escola";
    $sSql .= "      left join calendario on  calendario.ed52_i_codigo = alunocurso.ed56_i_calendario";
    $sSql .= "      left join base on  base.ed31_i_codigo = alunocurso.ed56_i_base";
    $sSql .= "      left join cursoedu on  cursoedu.ed29_i_codigo = base.ed31_i_curso";
    $sSql .= "      left join alunopossib on  alunopossib.ed79_i_alunocurso = alunocurso.ed56_i_codigo";
    $sSql .= "      left join serie on  serie.ed11_i_codigo = alunopossib.ed79_i_serie";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where aluno.ed47_i_codigo = $iCodigo "; 
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }
   function sql_query_matricula($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= " from aluno ";
    $sSql .= "      inner join alunocurso  on  alunocurso.ed56_i_aluno = aluno.ed47_i_codigo";
    $sSql .= "      inner join base on  base.ed31_i_codigo = alunocurso.ed56_i_base";
    $sSql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = base.ed31_i_curso";
    $sSql .= "      inner join calendario  on  calendario.ed52_i_codigo = alunocurso.ed56_i_calendario";
    $sSql .= "      inner join alunopossib  on  alunopossib.ed79_i_alunocurso = alunocurso.ed56_i_codigo";
    $sSql .= "      inner join serie  on  serie.ed11_i_codigo = alunopossib.ed79_i_serie";
    $sSql .= "      inner join ensino  on  ensino.ed10_i_codigo = serie.ed11_i_ensino";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where aluno.ed47_i_codigo = $iCodigo "; 
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }
   function sql_query_alunomatricula($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= " from aluno ";
    $sSql .= "      inner join matricula on ed47_i_codigo=ed60_i_aluno ";
    $sSql .= "      inner join turma on ed60_i_turma=ed57_i_codigo ";
    $sSql .= "      inner join matriculaserie on ed221_i_matricula=ed60_i_codigo ";
    $sSql .= "      inner join serie on ed11_i_codigo=ed221_i_serie ";
    $sSql .= "      inner join calendario on ed52_i_codigo=ed57_i_calendario ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where aluno.ed47_i_codigo = $iCodigo "; 
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }
   function sql_query_censo_inep ($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= " from aluno ";
    $sSql .= "      inner join matricula        on matricula.ed60_i_aluno         = aluno.ed47_i_codigo ";
    $sSql .= "      inner join turma            on turma.ed57_i_codigo            = matricula.ed60_i_turma ";
    $sSql .= "      inner join escola           on escola.ed18_i_codigo           = turma.ed57_i_escola ";
    $sSql .= "      inner join censomunic       on censomunic.ed261_i_codigo      = aluno.ed47_i_censomunicnat ";
    $sSql .= "      inner join censouf          on censouf.ed260_i_codigo         = aluno.ed47_i_censoufnat ";
    $sSql .= "      inner join calendario       on calendario.ed52_i_codigo       = turma.ed57_i_calendario";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where aluno.ed47_i_codigo = $iCodigo "; 
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;
  }
   function sql_query_aluno_curso($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {
    
    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= " FROM aluno ";
    $sSql .= "      left join alunocurso on alunocurso.ed56_i_aluno = aluno.ed47_i_codigo ";
    $sSql .= "      left join escola on escola.ed18_i_codigo = alunocurso.ed56_i_escola ";
    $sSql .= "      left join calendario on  calendario.ed52_i_codigo = alunocurso.ed56_i_calendario ";
    $sSql .= "      left join base on  base.ed31_i_codigo = alunocurso.ed56_i_base ";
    $sSql .= "      left join cursoedu on  cursoedu.ed29_i_codigo = base.ed31_i_curso ";
    $sSql .= "      left join alunopossib on  alunopossib.ed79_i_alunocurso = alunocurso.ed56_i_codigo ";
    $sSql .= "      left join serie on  serie.ed11_i_codigo = alunopossib.ed79_i_serie ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where aluno.ed47_i_codigo = $iCodigo "; 
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }
    return $sSql;
  }

  function sql_query_aluno_historico ( $iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '' ) {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';

      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";
      }
    } else {
      $sSql .= $sCampos;
    }

    $sSql .= " FROM aluno ";
    $sSql  .= "      inner join matricula           on ed60_i_aluno     = ed47_i_codigo          ";
    $sSql  .= "      inner join turma               on ed57_i_codigo    = ed60_i_turma           ";
    $sSql  .= "      inner join turmaserieregimemat on ed220_i_turma    = ed57_i_codigo          ";
    $sSql  .= "      inner join serieregimemat      on ed223_i_codigo   = ed220_i_serieregimemat ";
    $sSql  .= "      inner join serie               on ed11_i_codigo    = ed223_i_serie          ";
    $sSql  .= "      inner join ensino              on ed10_i_codigo    = ed11_i_ensino          ";
    $sSql  .= "      inner join historico           on ed61_i_aluno     = ed60_i_aluno           ";
    $sSql  .= "      left  join historicomps        on ed62_i_historico = ed61_i_codigo          ";
    $sSql  .= "      left  join historicompsfora    on ed99_i_historico = ed61_i_codigo          ";
    $sSql2  = "";

    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where aluno.ed47_i_codigo = $iCodigo ";
      }
    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }

    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';

      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';
      }
    }

    return $sSql;
  }
}
