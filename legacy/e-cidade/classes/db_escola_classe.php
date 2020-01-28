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
//CLASSE DA ENTIDADE escola
class cl_escola {
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
   var $ed18_i_codigo = 0;
   var $ed18_i_rua = 0;
   var $ed18_i_numero = 0;
   var $ed18_c_compl = null;
   var $ed18_i_bairro = 0;
   var $ed18_c_nome = null;
   var $ed18_c_abrev = null;
   var $ed18_c_mantenedora = 0;
   var $ed18_i_anoinicio = 0;
   var $ed18_c_email = null;
   var $ed18_c_homepage = null;
   var $ed18_c_tipo = null;
   var $ed18_c_codigoinep = 0;
   var $ed18_c_local = null;
   var $ed18_c_logo = null;
   var $ed18_c_cep = null;
   var $ed18_i_cnpj = null;
   var $ed18_i_locdiferenciada = 0;
   var $ed18_i_educindigena = 0;
   var $ed18_i_tipolinguain = 0;
   var $ed18_i_tipolinguapt = 0;
   var $ed18_i_linguaindigena = 0;
   var $ed18_i_credenciamento = 0;
   var $ed18_i_funcionamento = 0;
   var $ed18_i_censouf = 0;
   var $ed18_i_censomunic = 0;
   var $ed18_i_censodistrito = 0;
   var $ed18_i_censoorgreg = 0;
   var $ed18_i_categprivada = 0;
   var $ed18_i_conveniada = 0;
   var $ed18_i_cnas = 0;
   var $ed18_i_cebas = 0;
   var $ed18_c_mantprivada = null;
   var $ed18_i_cnpjprivada = null;
   var $ed18_i_cnpjmantprivada = null;
   var $ed18_latitude = null;
   var $ed18_longitude = null;
   var $ed18_codigoreferencia = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed18_i_codigo = int8 = Código da Escola
                 ed18_i_rua = int8 = Endereço
                 ed18_i_numero = int4 = Número
                 ed18_c_compl = char(20) = Complemento
                 ed18_i_bairro = int8 = Bairro
                 ed18_c_nome = char(80) = Nome da Escola
                 ed18_c_abrev = char(45) = Nome Abrev.
                 ed18_c_mantenedora = int4 = Dependência Administrativa
                 ed18_i_anoinicio = int4 = Ano Início
                 ed18_c_email = char(50) = E-mail
                 ed18_c_homepage = char(100) = Home Page
                 ed18_c_tipo = char(1) = Tipo de Escola
                 ed18_c_codigoinep = int4 = Código INEP
                 ed18_c_local = char(10) = Zona
                 ed18_c_logo = char(100) = Logotipo
                 ed18_c_cep = char(8) = CEP
                 ed18_i_cnpj = varchar(14) = CNPJ
                 ed18_i_locdiferenciada = int4 = Localização Diferenciada
                 ed18_i_educindigena = int4 = Educação Indígena
                 ed18_i_tipolinguain = int4 = Lingua Indígena
                 ed18_i_tipolinguapt = int4 = Lingua Portuguesa
                 ed18_i_linguaindigena = int4 = Língua Indígena
                 ed18_i_credenciamento = int4 = Credenciamento
                 ed18_i_funcionamento = int4 = Situação de Funcionamento
                 ed18_i_censouf = int4 = Estado
                 ed18_i_censomunic = int4 = Cidade
                 ed18_i_censodistrito = int4 = Distrito
                 ed18_i_censoorgreg = int4 = Órgão de Ensino
                 ed18_i_categprivada = int4 = Categoria da Escola Privada
                 ed18_i_conveniada = int4 = Conveniada Poder Público
                 ed18_i_cnas = int8 = N° Registro no CNAS
                 ed18_i_cebas = int8 = N° CEBAS
                 ed18_c_mantprivada = char(5) = Mantenedora da Escola Privada
                 ed18_i_cnpjprivada = varchar(14) = CNPJ da Escola Privada
                 ed18_i_cnpjmantprivada = varchar(14) = CNPJ Mantenedora Privada
                 ed18_latitude = varchar(20) = Latitude
                 ed18_longitude = varchar(20) = Longitude
                 ed18_codigoreferencia = int4 = Código Referência
                 ";
   //funcao construtor da classe
   function cl_escola() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("escola");
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
       $this->ed18_i_codigo = ($this->ed18_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_codigo"]:$this->ed18_i_codigo);
       $this->ed18_i_rua = ($this->ed18_i_rua == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_rua"]:$this->ed18_i_rua);
       $this->ed18_i_numero = ($this->ed18_i_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_numero"]:$this->ed18_i_numero);
       $this->ed18_c_compl = ($this->ed18_c_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_c_compl"]:$this->ed18_c_compl);
       $this->ed18_i_bairro = ($this->ed18_i_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_bairro"]:$this->ed18_i_bairro);
       $this->ed18_c_nome = ($this->ed18_c_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_c_nome"]:$this->ed18_c_nome);
       $this->ed18_c_abrev = ($this->ed18_c_abrev == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_c_abrev"]:$this->ed18_c_abrev);
       $this->ed18_c_mantenedora = ($this->ed18_c_mantenedora == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_c_mantenedora"]:$this->ed18_c_mantenedora);
       $this->ed18_i_anoinicio = ($this->ed18_i_anoinicio == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_anoinicio"]:$this->ed18_i_anoinicio);
       $this->ed18_c_email = ($this->ed18_c_email == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_c_email"]:$this->ed18_c_email);
       $this->ed18_c_homepage = ($this->ed18_c_homepage == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_c_homepage"]:$this->ed18_c_homepage);
       $this->ed18_c_tipo = ($this->ed18_c_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_c_tipo"]:$this->ed18_c_tipo);
       $this->ed18_c_codigoinep = ($this->ed18_c_codigoinep == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_c_codigoinep"]:$this->ed18_c_codigoinep);
       $this->ed18_c_local = ($this->ed18_c_local == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_c_local"]:$this->ed18_c_local);
       $this->ed18_c_logo = ($this->ed18_c_logo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_c_logo"]:$this->ed18_c_logo);
       $this->ed18_c_cep = ($this->ed18_c_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_c_cep"]:$this->ed18_c_cep);
       $this->ed18_i_cnpj = ($this->ed18_i_cnpj == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_cnpj"]:$this->ed18_i_cnpj);
       $this->ed18_i_locdiferenciada = ($this->ed18_i_locdiferenciada == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_locdiferenciada"]:$this->ed18_i_locdiferenciada);
       $this->ed18_i_educindigena = ($this->ed18_i_educindigena == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_educindigena"]:$this->ed18_i_educindigena);
       $this->ed18_i_tipolinguain = ($this->ed18_i_tipolinguain == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_tipolinguain"]:$this->ed18_i_tipolinguain);
       $this->ed18_i_tipolinguapt = ($this->ed18_i_tipolinguapt == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_tipolinguapt"]:$this->ed18_i_tipolinguapt);
       $this->ed18_i_linguaindigena = ($this->ed18_i_linguaindigena == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_linguaindigena"]:$this->ed18_i_linguaindigena);
       $this->ed18_i_credenciamento = ($this->ed18_i_credenciamento == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_credenciamento"]:$this->ed18_i_credenciamento);
       $this->ed18_i_funcionamento = ($this->ed18_i_funcionamento == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_funcionamento"]:$this->ed18_i_funcionamento);
       $this->ed18_i_censouf = ($this->ed18_i_censouf == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_censouf"]:$this->ed18_i_censouf);
       $this->ed18_i_censomunic = ($this->ed18_i_censomunic == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_censomunic"]:$this->ed18_i_censomunic);
       $this->ed18_i_censodistrito = ($this->ed18_i_censodistrito == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_censodistrito"]:$this->ed18_i_censodistrito);
       $this->ed18_i_censoorgreg = ($this->ed18_i_censoorgreg == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_censoorgreg"]:$this->ed18_i_censoorgreg);
       $this->ed18_i_categprivada = ($this->ed18_i_categprivada == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_categprivada"]:$this->ed18_i_categprivada);
       $this->ed18_i_conveniada = ($this->ed18_i_conveniada == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_conveniada"]:$this->ed18_i_conveniada);
       $this->ed18_i_cnas = ($this->ed18_i_cnas == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_cnas"]:$this->ed18_i_cnas);
       $this->ed18_i_cebas = ($this->ed18_i_cebas == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_cebas"]:$this->ed18_i_cebas);
       $this->ed18_c_mantprivada = ($this->ed18_c_mantprivada == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_c_mantprivada"]:$this->ed18_c_mantprivada);
       $this->ed18_i_cnpjprivada = ($this->ed18_i_cnpjprivada == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_cnpjprivada"]:$this->ed18_i_cnpjprivada);
       $this->ed18_i_cnpjmantprivada = ($this->ed18_i_cnpjmantprivada == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_cnpjmantprivada"]:$this->ed18_i_cnpjmantprivada);
       $this->ed18_latitude = ($this->ed18_latitude == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_latitude"]:$this->ed18_latitude);
       $this->ed18_longitude = ($this->ed18_longitude == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_longitude"]:$this->ed18_longitude);
       $this->ed18_codigoreferencia = ($this->ed18_codigoreferencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_codigoreferencia"]:$this->ed18_codigoreferencia);
     }else{
       $this->ed18_i_codigo = ($this->ed18_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed18_i_codigo"]:$this->ed18_i_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($ed18_i_codigo){
      $this->atualizacampos();
     if($this->ed18_i_rua == null ){
       $this->erro_sql = " Campo Endereço não informado.";
       $this->erro_campo = "ed18_i_rua";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed18_i_numero == null ){
       $this->erro_sql = " Campo Número não informado.";
       $this->erro_campo = "ed18_i_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed18_i_bairro == null ){
       $this->erro_sql = " Campo Bairro não informado.";
       $this->erro_campo = "ed18_i_bairro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed18_c_nome == null ){
       $this->erro_sql = " Campo Nome da Escola não informado.";
       $this->erro_campo = "ed18_c_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed18_c_mantenedora == null ){
       $this->erro_sql = " Campo Dependência Administrativa não informado.";
       $this->erro_campo = "ed18_c_mantenedora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed18_i_anoinicio == null ){
       $this->erro_sql = " Campo Ano Início não informado.";
       $this->erro_campo = "ed18_i_anoinicio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed18_c_codigoinep == null ){
       $this->erro_sql = " Campo Código INEP não informado.";
       $this->erro_campo = "ed18_c_codigoinep";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed18_c_local == null ){
       $this->erro_sql = " Campo Zona não informado.";
       $this->erro_campo = "ed18_c_local";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed18_c_cep == null ){
       $this->erro_sql = " Campo CEP não informado.";
       $this->erro_campo = "ed18_c_cep";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed18_i_cnpj == null ){
       $this->ed18_i_cnpj = "null";
     }
     if($this->ed18_i_locdiferenciada == null ){
       $this->erro_sql = " Campo Localização Diferenciada não informado.";
       $this->erro_campo = "ed18_i_locdiferenciada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed18_i_educindigena == null ){
       $this->erro_sql = " Campo Educação Indígena não informado.";
       $this->erro_campo = "ed18_i_educindigena";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed18_i_tipolinguain == null ){
       $this->ed18_i_tipolinguain = "0";
     }
     if($this->ed18_i_tipolinguapt == null ){
       $this->ed18_i_tipolinguapt = "0";
     }
     if($this->ed18_i_linguaindigena == null ){
       $this->ed18_i_linguaindigena = "null";
     }
     if($this->ed18_i_credenciamento == null ){
       $this->erro_sql = " Campo Credenciamento não informado.";
       $this->erro_campo = "ed18_i_credenciamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed18_i_funcionamento == null ){
       $this->erro_sql = " Campo Situação de Funcionamento não informado.";
       $this->erro_campo = "ed18_i_funcionamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed18_i_censouf == null ){
       $this->erro_sql = " Campo Estado não informado.";
       $this->erro_campo = "ed18_i_censouf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed18_i_censomunic == null ){
       $this->erro_sql = " Campo Cidade não informado.";
       $this->erro_campo = "ed18_i_censomunic";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed18_i_censodistrito == null ){
       $this->erro_sql = " Campo Distrito não informado.";
       $this->erro_campo = "ed18_i_censodistrito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed18_i_censoorgreg == null ){
       $this->ed18_i_censoorgreg = "null";
     }
     if($this->ed18_i_categprivada == null ){
       $this->ed18_i_categprivada = "null";
     }
     if($this->ed18_i_conveniada == null ){
       $this->ed18_i_conveniada = "null";
     }
     if($this->ed18_i_cnas == null ){
       $this->ed18_i_cnas = "null";
     }
     if($this->ed18_i_cebas == null ){
       $this->ed18_i_cebas = "null";
     }
     if($this->ed18_i_cnpjprivada == null ){
       $this->ed18_i_cnpjprivada = "null";
     }
     if($this->ed18_i_cnpjmantprivada == null ){
       $this->ed18_i_cnpjmantprivada = "null";
     }
     if($this->ed18_codigoreferencia == null ){
       $this->ed18_codigoreferencia = "null";
     }
       $this->ed18_i_codigo = $ed18_i_codigo;
     if(($this->ed18_i_codigo == null) || ($this->ed18_i_codigo == "") ){
       $this->erro_sql = " Campo ed18_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into escola(
                                       ed18_i_codigo
                                      ,ed18_i_rua
                                      ,ed18_i_numero
                                      ,ed18_c_compl
                                      ,ed18_i_bairro
                                      ,ed18_c_nome
                                      ,ed18_c_abrev
                                      ,ed18_c_mantenedora
                                      ,ed18_i_anoinicio
                                      ,ed18_c_email
                                      ,ed18_c_homepage
                                      ,ed18_c_tipo
                                      ,ed18_c_codigoinep
                                      ,ed18_c_local
                                      ,ed18_c_logo
                                      ,ed18_c_cep
                                      ,ed18_i_cnpj
                                      ,ed18_i_locdiferenciada
                                      ,ed18_i_educindigena
                                      ,ed18_i_tipolinguain
                                      ,ed18_i_tipolinguapt
                                      ,ed18_i_linguaindigena
                                      ,ed18_i_credenciamento
                                      ,ed18_i_funcionamento
                                      ,ed18_i_censouf
                                      ,ed18_i_censomunic
                                      ,ed18_i_censodistrito
                                      ,ed18_i_censoorgreg
                                      ,ed18_i_categprivada
                                      ,ed18_i_conveniada
                                      ,ed18_i_cnas
                                      ,ed18_i_cebas
                                      ,ed18_c_mantprivada
                                      ,ed18_i_cnpjprivada
                                      ,ed18_i_cnpjmantprivada
                                      ,ed18_latitude
                                      ,ed18_longitude
                                      ,ed18_codigoreferencia
                       )
                values (
                                $this->ed18_i_codigo
                               ,$this->ed18_i_rua
                               ,$this->ed18_i_numero
                               ,'$this->ed18_c_compl'
                               ,$this->ed18_i_bairro
                               ,'$this->ed18_c_nome'
                               ,'$this->ed18_c_abrev'
                               ,$this->ed18_c_mantenedora
                               ,$this->ed18_i_anoinicio
                               ,'$this->ed18_c_email'
                               ,'$this->ed18_c_homepage'
                               ,'$this->ed18_c_tipo'
                               ,$this->ed18_c_codigoinep
                               ,'$this->ed18_c_local'
                               ,'$this->ed18_c_logo'
                               ,'$this->ed18_c_cep'
                               ,'$this->ed18_i_cnpj'
                               ,$this->ed18_i_locdiferenciada
                               ,$this->ed18_i_educindigena
                               ,$this->ed18_i_tipolinguain
                               ,$this->ed18_i_tipolinguapt
                               ,$this->ed18_i_linguaindigena
                               ,$this->ed18_i_credenciamento
                               ,$this->ed18_i_funcionamento
                               ,$this->ed18_i_censouf
                               ,$this->ed18_i_censomunic
                               ,$this->ed18_i_censodistrito
                               ,$this->ed18_i_censoorgreg
                               ,$this->ed18_i_categprivada
                               ,$this->ed18_i_conveniada
                               ,$this->ed18_i_cnas
                               ,$this->ed18_i_cebas
                               ,'$this->ed18_c_mantprivada'
                               ,'$this->ed18_i_cnpjprivada'
                               ,'$this->ed18_i_cnpjmantprivada'
                               ,'$this->ed18_latitude'
                               ,'$this->ed18_longitude'
                               ,$this->ed18_codigoreferencia
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Escola ($this->ed18_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Escola já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Escola ($this->ed18_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed18_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed18_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008196,'$this->ed18_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010031,1008196,'','".AddSlashes(pg_result($resaco,0,'ed18_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,1008197,'','".AddSlashes(pg_result($resaco,0,'ed18_i_rua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,1008198,'','".AddSlashes(pg_result($resaco,0,'ed18_i_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,1008199,'','".AddSlashes(pg_result($resaco,0,'ed18_c_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,1008200,'','".AddSlashes(pg_result($resaco,0,'ed18_i_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,1008201,'','".AddSlashes(pg_result($resaco,0,'ed18_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,1008202,'','".AddSlashes(pg_result($resaco,0,'ed18_c_abrev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,1008204,'','".AddSlashes(pg_result($resaco,0,'ed18_c_mantenedora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,1008205,'','".AddSlashes(pg_result($resaco,0,'ed18_i_anoinicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,1008206,'','".AddSlashes(pg_result($resaco,0,'ed18_c_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,1008207,'','".AddSlashes(pg_result($resaco,0,'ed18_c_homepage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,1008208,'','".AddSlashes(pg_result($resaco,0,'ed18_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,1008955,'','".AddSlashes(pg_result($resaco,0,'ed18_c_codigoinep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,1008956,'','".AddSlashes(pg_result($resaco,0,'ed18_c_local'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,1008962,'','".AddSlashes(pg_result($resaco,0,'ed18_c_logo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,1009068,'','".AddSlashes(pg_result($resaco,0,'ed18_c_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,12619,'','".AddSlashes(pg_result($resaco,0,'ed18_i_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,12622,'','".AddSlashes(pg_result($resaco,0,'ed18_i_locdiferenciada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,12623,'','".AddSlashes(pg_result($resaco,0,'ed18_i_educindigena'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,12624,'','".AddSlashes(pg_result($resaco,0,'ed18_i_tipolinguain'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,13399,'','".AddSlashes(pg_result($resaco,0,'ed18_i_tipolinguapt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,13373,'','".AddSlashes(pg_result($resaco,0,'ed18_i_linguaindigena'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,12621,'','".AddSlashes(pg_result($resaco,0,'ed18_i_credenciamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,13344,'','".AddSlashes(pg_result($resaco,0,'ed18_i_funcionamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,13361,'','".AddSlashes(pg_result($resaco,0,'ed18_i_censouf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,13362,'','".AddSlashes(pg_result($resaco,0,'ed18_i_censomunic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,13363,'','".AddSlashes(pg_result($resaco,0,'ed18_i_censodistrito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,13364,'','".AddSlashes(pg_result($resaco,0,'ed18_i_censoorgreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,13450,'','".AddSlashes(pg_result($resaco,0,'ed18_i_categprivada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,13451,'','".AddSlashes(pg_result($resaco,0,'ed18_i_conveniada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,13452,'','".AddSlashes(pg_result($resaco,0,'ed18_i_cnas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,13453,'','".AddSlashes(pg_result($resaco,0,'ed18_i_cebas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,13454,'','".AddSlashes(pg_result($resaco,0,'ed18_c_mantprivada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,13455,'','".AddSlashes(pg_result($resaco,0,'ed18_i_cnpjprivada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,17985,'','".AddSlashes(pg_result($resaco,0,'ed18_i_cnpjmantprivada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,18917,'','".AddSlashes(pg_result($resaco,0,'ed18_latitude'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,18918,'','".AddSlashes(pg_result($resaco,0,'ed18_longitude'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010031,20689,'','".AddSlashes(pg_result($resaco,0,'ed18_codigoreferencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($ed18_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update escola set ";
     $virgula = "";
     if(trim($this->ed18_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_codigo"])){
       $sql  .= $virgula." ed18_i_codigo = $this->ed18_i_codigo ";
       $virgula = ",";
       if(trim($this->ed18_i_codigo) == null ){
         $this->erro_sql = " Campo Código da Escola não informado.";
         $this->erro_campo = "ed18_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed18_i_rua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_rua"])){
       $sql  .= $virgula." ed18_i_rua = $this->ed18_i_rua ";
       $virgula = ",";
       if(trim($this->ed18_i_rua) == null ){
         $this->erro_sql = " Campo Endereço não informado.";
         $this->erro_campo = "ed18_i_rua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed18_i_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_numero"])){
       $sql  .= $virgula." ed18_i_numero = $this->ed18_i_numero ";
       $virgula = ",";
       if(trim($this->ed18_i_numero) == null ){
         $this->erro_sql = " Campo Número não informado.";
         $this->erro_campo = "ed18_i_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed18_c_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_compl"])){
       $sql  .= $virgula." ed18_c_compl = '$this->ed18_c_compl' ";
       $virgula = ",";
     }
     if(trim($this->ed18_i_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_bairro"])){
       $sql  .= $virgula." ed18_i_bairro = $this->ed18_i_bairro ";
       $virgula = ",";
       if(trim($this->ed18_i_bairro) == null ){
         $this->erro_sql = " Campo Bairro não informado.";
         $this->erro_campo = "ed18_i_bairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed18_c_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_nome"])){
       $sql  .= $virgula." ed18_c_nome = '$this->ed18_c_nome' ";
       $virgula = ",";
       if(trim($this->ed18_c_nome) == null ){
         $this->erro_sql = " Campo Nome da Escola não informado.";
         $this->erro_campo = "ed18_c_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed18_c_abrev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_abrev"])){
       $sql  .= $virgula." ed18_c_abrev = '$this->ed18_c_abrev' ";
       $virgula = ",";
     }
     if(trim($this->ed18_c_mantenedora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_mantenedora"])){
       $sql  .= $virgula." ed18_c_mantenedora = $this->ed18_c_mantenedora ";
       $virgula = ",";
       if(trim($this->ed18_c_mantenedora) == null ){
         $this->erro_sql = " Campo Dependência Administrativa não informado.";
         $this->erro_campo = "ed18_c_mantenedora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed18_i_anoinicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_anoinicio"])){
       $sql  .= $virgula." ed18_i_anoinicio = $this->ed18_i_anoinicio ";
       $virgula = ",";
       if(trim($this->ed18_i_anoinicio) == null ){
         $this->erro_sql = " Campo Ano Início não informado.";
         $this->erro_campo = "ed18_i_anoinicio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed18_c_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_email"])){
       $sql  .= $virgula." ed18_c_email = '$this->ed18_c_email' ";
       $virgula = ",";
     }
     if(trim($this->ed18_c_homepage)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_homepage"])){
       $sql  .= $virgula." ed18_c_homepage = '$this->ed18_c_homepage' ";
       $virgula = ",";
     }
     if(trim($this->ed18_c_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_tipo"])){
       $sql  .= $virgula." ed18_c_tipo = '$this->ed18_c_tipo' ";
       $virgula = ",";
     }
     if(trim($this->ed18_c_codigoinep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_codigoinep"])){
       $sql  .= $virgula." ed18_c_codigoinep = $this->ed18_c_codigoinep ";
       $virgula = ",";
       if(trim($this->ed18_c_codigoinep) == null ){
         $this->erro_sql = " Campo Código INEP não informado.";
         $this->erro_campo = "ed18_c_codigoinep";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed18_c_local)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_local"])){
       $sql  .= $virgula." ed18_c_local = '$this->ed18_c_local' ";
       $virgula = ",";
       if(trim($this->ed18_c_local) == null ){
         $this->erro_sql = " Campo Zona não informado.";
         $this->erro_campo = "ed18_c_local";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed18_c_logo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_logo"])){
       $sql  .= $virgula." ed18_c_logo = '$this->ed18_c_logo' ";
       $virgula = ",";
     }
     if(trim($this->ed18_c_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_cep"])){
       $sql  .= $virgula." ed18_c_cep = '$this->ed18_c_cep' ";
       $virgula = ",";
       if(trim($this->ed18_c_cep) == null ){
         $this->erro_sql = " Campo CEP não informado.";
         $this->erro_campo = "ed18_c_cep";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed18_i_cnpj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_cnpj"])){
       $sql  .= $virgula." ed18_i_cnpj = '$this->ed18_i_cnpj' ";
       $virgula = ",";
     }
     if(trim($this->ed18_i_locdiferenciada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_locdiferenciada"])){
       $sql  .= $virgula." ed18_i_locdiferenciada = $this->ed18_i_locdiferenciada ";
       $virgula = ",";
       if(trim($this->ed18_i_locdiferenciada) == null ){
         $this->erro_sql = " Campo Localização Diferenciada não informado.";
         $this->erro_campo = "ed18_i_locdiferenciada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed18_i_educindigena)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_educindigena"])){
       $sql  .= $virgula." ed18_i_educindigena = $this->ed18_i_educindigena ";
       $virgula = ",";
       if(trim($this->ed18_i_educindigena) == null ){
         $this->erro_sql = " Campo Educação Indígena não informado.";
         $this->erro_campo = "ed18_i_educindigena";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed18_i_tipolinguain)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_tipolinguain"])){
        if(trim($this->ed18_i_tipolinguain)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_tipolinguain"])){
           $this->ed18_i_tipolinguain = "0" ;
        }
       $sql  .= $virgula." ed18_i_tipolinguain = $this->ed18_i_tipolinguain ";
       $virgula = ",";
     }
     if(trim($this->ed18_i_tipolinguapt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_tipolinguapt"])){
        if(trim($this->ed18_i_tipolinguapt)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_tipolinguapt"])){
           $this->ed18_i_tipolinguapt = "0" ;
        }
       $sql  .= $virgula." ed18_i_tipolinguapt = $this->ed18_i_tipolinguapt ";
       $virgula = ",";
     }
     if(trim($this->ed18_i_linguaindigena)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_linguaindigena"])){
        if(trim($this->ed18_i_linguaindigena)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_linguaindigena"])){
           $this->ed18_i_linguaindigena = "null" ;
        }
       $sql  .= $virgula." ed18_i_linguaindigena = $this->ed18_i_linguaindigena ";
       $virgula = ",";
     }
     if(trim($this->ed18_i_credenciamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_credenciamento"])){
       $sql  .= $virgula." ed18_i_credenciamento = $this->ed18_i_credenciamento ";
       $virgula = ",";
       if(trim($this->ed18_i_credenciamento) == null ){
         $this->erro_sql = " Campo Credenciamento não informado.";
         $this->erro_campo = "ed18_i_credenciamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed18_i_funcionamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_funcionamento"])){
       $sql  .= $virgula." ed18_i_funcionamento = $this->ed18_i_funcionamento ";
       $virgula = ",";
       if(trim($this->ed18_i_funcionamento) == null ){
         $this->erro_sql = " Campo Situação de Funcionamento não informado.";
         $this->erro_campo = "ed18_i_funcionamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed18_i_censouf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_censouf"])){
       $sql  .= $virgula." ed18_i_censouf = $this->ed18_i_censouf ";
       $virgula = ",";
       if(trim($this->ed18_i_censouf) == null ){
         $this->erro_sql = " Campo Estado não informado.";
         $this->erro_campo = "ed18_i_censouf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed18_i_censomunic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_censomunic"])){
       $sql  .= $virgula." ed18_i_censomunic = $this->ed18_i_censomunic ";
       $virgula = ",";
       if(trim($this->ed18_i_censomunic) == null ){
         $this->erro_sql = " Campo Cidade não informado.";
         $this->erro_campo = "ed18_i_censomunic";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed18_i_censodistrito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_censodistrito"])){
        if(trim($this->ed18_i_censodistrito)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_censodistrito"])){
           $this->ed18_i_censodistrito = "null" ;
        }
       $sql  .= $virgula." ed18_i_censodistrito = $this->ed18_i_censodistrito ";
       $virgula = ",";
     }
     if(trim($this->ed18_i_censoorgreg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_censoorgreg"])){
        if(trim($this->ed18_i_censoorgreg)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_censoorgreg"])){
           $this->ed18_i_censoorgreg = "0" ;
        }
       $sql  .= $virgula." ed18_i_censoorgreg = $this->ed18_i_censoorgreg ";
       $virgula = ",";
     }
     if(trim($this->ed18_i_categprivada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_categprivada"])){
        if(trim($this->ed18_i_categprivada)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_categprivada"])){
           $this->ed18_i_categprivada = "0" ;
        }
       $sql  .= $virgula." ed18_i_categprivada = $this->ed18_i_categprivada ";
       $virgula = ",";
     }
     if(trim($this->ed18_i_conveniada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_conveniada"])){
        if(trim($this->ed18_i_conveniada)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_conveniada"])){
           $this->ed18_i_conveniada = "0" ;
        }
       $sql  .= $virgula." ed18_i_conveniada = $this->ed18_i_conveniada ";
       $virgula = ",";
     }
     if(trim($this->ed18_i_cnas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_cnas"])){
        if(trim($this->ed18_i_cnas)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_cnas"])){
           $this->ed18_i_cnas = "0" ;
        }
       $sql  .= $virgula." ed18_i_cnas = $this->ed18_i_cnas ";
       $virgula = ",";
     }
     if(trim($this->ed18_i_cebas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_cebas"])){
        if(trim($this->ed18_i_cebas)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_cebas"])){
           $this->ed18_i_cebas = "0" ;
        }
       $sql  .= $virgula." ed18_i_cebas = $this->ed18_i_cebas ";
       $virgula = ",";
     }
     if(trim($this->ed18_c_mantprivada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_mantprivada"])){
       $sql  .= $virgula." ed18_c_mantprivada = '$this->ed18_c_mantprivada' ";
       $virgula = ",";
     }

     if ( empty($this->ed18_i_cnpjprivada) ) {
       $sql  .= $virgula." ed18_i_cnpjprivada = null ";
       $virgula = ",";
     } else if (trim($this->ed18_i_cnpjprivada) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_cnpjprivada"])) {

       $sql  .= $virgula." ed18_i_cnpjprivada = '$this->ed18_i_cnpjprivada' ";
       $virgula = ",";
     }

     if ( empty($this->ed18_i_cnpjmantprivada) ) {
       $sql  .= $virgula." ed18_i_cnpjmantprivada = null ";
       $virgula = ",";
     } else if (trim($this->ed18_i_cnpjmantprivada) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_cnpjmantprivada"])) {

       $sql  .= $virgula." ed18_i_cnpjmantprivada = '$this->ed18_i_cnpjmantprivada' ";
       $virgula = ",";
     }


     if(trim($this->ed18_latitude)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_latitude"])){
       $sql  .= $virgula." ed18_latitude = '$this->ed18_latitude' ";
       $virgula = ",";
     }
     if(trim($this->ed18_longitude)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_longitude"])){
       $sql  .= $virgula." ed18_longitude = '$this->ed18_longitude' ";
       $virgula = ",";
     }
     if(trim($this->ed18_codigoreferencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed18_codigoreferencia"])){
        if(trim($this->ed18_codigoreferencia)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed18_codigoreferencia"])){
           $this->ed18_codigoreferencia = "null" ;
        }
       $sql  .= $virgula." ed18_codigoreferencia = $this->ed18_codigoreferencia ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed18_i_codigo!=null){
       $sql .= " ed18_i_codigo = $this->ed18_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed18_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008196,'$this->ed18_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_codigo"]) || $this->ed18_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010031,1008196,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_codigo'))."','$this->ed18_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_rua"]) || $this->ed18_i_rua != "")
             $resac = db_query("insert into db_acount values($acount,1010031,1008197,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_rua'))."','$this->ed18_i_rua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_numero"]) || $this->ed18_i_numero != "")
             $resac = db_query("insert into db_acount values($acount,1010031,1008198,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_numero'))."','$this->ed18_i_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_compl"]) || $this->ed18_c_compl != "")
             $resac = db_query("insert into db_acount values($acount,1010031,1008199,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_c_compl'))."','$this->ed18_c_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_bairro"]) || $this->ed18_i_bairro != "")
             $resac = db_query("insert into db_acount values($acount,1010031,1008200,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_bairro'))."','$this->ed18_i_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_nome"]) || $this->ed18_c_nome != "")
             $resac = db_query("insert into db_acount values($acount,1010031,1008201,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_c_nome'))."','$this->ed18_c_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_abrev"]) || $this->ed18_c_abrev != "")
             $resac = db_query("insert into db_acount values($acount,1010031,1008202,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_c_abrev'))."','$this->ed18_c_abrev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_mantenedora"]) || $this->ed18_c_mantenedora != "")
             $resac = db_query("insert into db_acount values($acount,1010031,1008204,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_c_mantenedora'))."','$this->ed18_c_mantenedora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_anoinicio"]) || $this->ed18_i_anoinicio != "")
             $resac = db_query("insert into db_acount values($acount,1010031,1008205,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_anoinicio'))."','$this->ed18_i_anoinicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_email"]) || $this->ed18_c_email != "")
             $resac = db_query("insert into db_acount values($acount,1010031,1008206,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_c_email'))."','$this->ed18_c_email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_homepage"]) || $this->ed18_c_homepage != "")
             $resac = db_query("insert into db_acount values($acount,1010031,1008207,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_c_homepage'))."','$this->ed18_c_homepage',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_tipo"]) || $this->ed18_c_tipo != "")
             $resac = db_query("insert into db_acount values($acount,1010031,1008208,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_c_tipo'))."','$this->ed18_c_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_codigoinep"]) || $this->ed18_c_codigoinep != "")
             $resac = db_query("insert into db_acount values($acount,1010031,1008955,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_c_codigoinep'))."','$this->ed18_c_codigoinep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_local"]) || $this->ed18_c_local != "")
             $resac = db_query("insert into db_acount values($acount,1010031,1008956,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_c_local'))."','$this->ed18_c_local',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_logo"]) || $this->ed18_c_logo != "")
             $resac = db_query("insert into db_acount values($acount,1010031,1008962,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_c_logo'))."','$this->ed18_c_logo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_cep"]) || $this->ed18_c_cep != "")
             $resac = db_query("insert into db_acount values($acount,1010031,1009068,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_c_cep'))."','$this->ed18_c_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_cnpj"]) || $this->ed18_i_cnpj != "")
             $resac = db_query("insert into db_acount values($acount,1010031,12619,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_cnpj'))."','$this->ed18_i_cnpj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_locdiferenciada"]) || $this->ed18_i_locdiferenciada != "")
             $resac = db_query("insert into db_acount values($acount,1010031,12622,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_locdiferenciada'))."','$this->ed18_i_locdiferenciada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_educindigena"]) || $this->ed18_i_educindigena != "")
             $resac = db_query("insert into db_acount values($acount,1010031,12623,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_educindigena'))."','$this->ed18_i_educindigena',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_tipolinguain"]) || $this->ed18_i_tipolinguain != "")
             $resac = db_query("insert into db_acount values($acount,1010031,12624,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_tipolinguain'))."','$this->ed18_i_tipolinguain',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_tipolinguapt"]) || $this->ed18_i_tipolinguapt != "")
             $resac = db_query("insert into db_acount values($acount,1010031,13399,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_tipolinguapt'))."','$this->ed18_i_tipolinguapt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_linguaindigena"]) || $this->ed18_i_linguaindigena != "")
             $resac = db_query("insert into db_acount values($acount,1010031,13373,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_linguaindigena'))."','$this->ed18_i_linguaindigena',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_credenciamento"]) || $this->ed18_i_credenciamento != "")
             $resac = db_query("insert into db_acount values($acount,1010031,12621,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_credenciamento'))."','$this->ed18_i_credenciamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_funcionamento"]) || $this->ed18_i_funcionamento != "")
             $resac = db_query("insert into db_acount values($acount,1010031,13344,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_funcionamento'))."','$this->ed18_i_funcionamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_censouf"]) || $this->ed18_i_censouf != "")
             $resac = db_query("insert into db_acount values($acount,1010031,13361,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_censouf'))."','$this->ed18_i_censouf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_censomunic"]) || $this->ed18_i_censomunic != "")
             $resac = db_query("insert into db_acount values($acount,1010031,13362,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_censomunic'))."','$this->ed18_i_censomunic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_censodistrito"]) || $this->ed18_i_censodistrito != "")
             $resac = db_query("insert into db_acount values($acount,1010031,13363,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_censodistrito'))."','$this->ed18_i_censodistrito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_censoorgreg"]) || $this->ed18_i_censoorgreg != "")
             $resac = db_query("insert into db_acount values($acount,1010031,13364,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_censoorgreg'))."','$this->ed18_i_censoorgreg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_categprivada"]) || $this->ed18_i_categprivada != "")
             $resac = db_query("insert into db_acount values($acount,1010031,13450,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_categprivada'))."','$this->ed18_i_categprivada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_conveniada"]) || $this->ed18_i_conveniada != "")
             $resac = db_query("insert into db_acount values($acount,1010031,13451,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_conveniada'))."','$this->ed18_i_conveniada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_cnas"]) || $this->ed18_i_cnas != "")
             $resac = db_query("insert into db_acount values($acount,1010031,13452,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_cnas'))."','$this->ed18_i_cnas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_cebas"]) || $this->ed18_i_cebas != "")
             $resac = db_query("insert into db_acount values($acount,1010031,13453,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_cebas'))."','$this->ed18_i_cebas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_c_mantprivada"]) || $this->ed18_c_mantprivada != "")
             $resac = db_query("insert into db_acount values($acount,1010031,13454,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_c_mantprivada'))."','$this->ed18_c_mantprivada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_cnpjprivada"]) || $this->ed18_i_cnpjprivada != "")
             $resac = db_query("insert into db_acount values($acount,1010031,13455,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_cnpjprivada'))."','$this->ed18_i_cnpjprivada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_i_cnpjmantprivada"]) || $this->ed18_i_cnpjmantprivada != "")
             $resac = db_query("insert into db_acount values($acount,1010031,17985,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_i_cnpjmantprivada'))."','$this->ed18_i_cnpjmantprivada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_latitude"]) || $this->ed18_latitude != "")
             $resac = db_query("insert into db_acount values($acount,1010031,18917,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_latitude'))."','$this->ed18_latitude',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_longitude"]) || $this->ed18_longitude != "")
             $resac = db_query("insert into db_acount values($acount,1010031,18918,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_longitude'))."','$this->ed18_longitude',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed18_codigoreferencia"]) || $this->ed18_codigoreferencia != "")
             $resac = db_query("insert into db_acount values($acount,1010031,20689,'".AddSlashes(pg_result($resaco,$conresaco,'ed18_codigoreferencia'))."','$this->ed18_codigoreferencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Escola não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed18_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Escola não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed18_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed18_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($ed18_i_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed18_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008196,'$ed18_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010031,1008196,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,1008197,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_rua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,1008198,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,1008199,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_c_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,1008200,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,1008201,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,1008202,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_c_abrev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,1008204,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_c_mantenedora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,1008205,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_anoinicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,1008206,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_c_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,1008207,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_c_homepage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,1008208,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,1008955,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_c_codigoinep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,1008956,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_c_local'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,1008962,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_c_logo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,1009068,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_c_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,12619,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,12622,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_locdiferenciada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,12623,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_educindigena'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,12624,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_tipolinguain'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,13399,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_tipolinguapt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,13373,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_linguaindigena'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,12621,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_credenciamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,13344,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_funcionamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,13361,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_censouf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,13362,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_censomunic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,13363,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_censodistrito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,13364,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_censoorgreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,13450,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_categprivada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,13451,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_conveniada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,13452,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_cnas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,13453,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_cebas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,13454,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_c_mantprivada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,13455,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_cnpjprivada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,17985,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_i_cnpjmantprivada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,18917,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_latitude'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,18918,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_longitude'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010031,20689,'','".AddSlashes(pg_result($resaco,$iresaco,'ed18_codigoreferencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from escola
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed18_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed18_i_codigo = $ed18_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Escola não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed18_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Escola não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed18_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed18_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:escola";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($ed18_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from escola ";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     $sql .= "      left join censodistrito  on  censodistrito.ed262_i_codigo = escola.ed18_i_censodistrito";
     $sql .= "      left join censoorgreg  on  censoorgreg.ed263_i_codigo = escola.ed18_i_censoorgreg";
     $sql .= "      left join censolinguaindig  on  censolinguaindig.ed264_i_codigo = escola.ed18_i_linguaindigena";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed18_i_codigo)) {
         $sql2 .= " where escola.ed18_i_codigo = $ed18_i_codigo ";
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
   public function sql_query_file ($ed18_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from escola ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed18_i_codigo)){
         $sql2 .= " where escola.ed18_i_codigo = $ed18_i_codigo ";
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

   function sql_query_dados( $ed18_i_codigo=null,$campos="*",$ordem=null,$dbwhere = "") {
    $sql = "select ";
    if  ($campos != "*" ) {
       $campos_sql = split("#",$campos);
       $virgula = "";
       for ($i = 0; $i < sizeof($campos_sql); $i++) {
          $sql .= $virgula.$campos_sql[$i];
          $virgula = ",";
       }
    } else {
       $sql .= $campos;
    }
    $sql .= " from escola
                    inner join bairro        on bairro.j13_codi           = escola.ed18_i_bairro
                    inner join ruas          on ruas.j14_codigo           = escola.ed18_i_rua
                    inner join db_depart     on db_depart.coddepto        = escola.ed18_i_codigo
                    inner join censouf       on censouf.ed260_i_codigo    = escola.ed18_i_censouf
                    inner join censomunic    on censomunic.ed261_i_codigo = escola.ed18_i_censomunic
                    left join ruascep        on ruascep.j29_codigo        = ruas.j14_codigo
                    left join logradcep      on logradcep.j65_lograd      = ruas.j14_codigo
                    left join ceplogradouros on ceplogradouros.cp06_codlogradouro = logradcep.j65_ceplog
                    left join ceplocalidades on ceplocalidades.cp05_codlocalidades = ceplogradouros.cp06_codlocalidade
                    left join telefoneescola on telefoneescola.ed26_i_escola = escola.ed18_i_codigo      ";

     $sql2 = "";
     if($dbwhere==""){
       if($ed18_i_codigo!=null ){
         $sql2 .= " where escola.ed18_i_codigo = $ed18_i_codigo ";
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
   function sql_query_cardapioescola ( $ed18_i_codigo=null,$campos="*",$ordem=null,$dbwhere="", $iCodUsuario = null){

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
     $sql .= " from escola ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      left join mer_nutricionistaescola on  mer_nutricionistaescola.me31_i_escola = escola.ed18_i_codigo ";
     $sql .= "      left join mer_nutricionista on  mer_nutricionista.me02_i_codigo = mer_nutricionistaescola.me31_i_nutricionista ";
     $sql .= "      left join db_usuacgm on  db_usuacgm.cgmlogin = mer_nutricionista.me02_i_cgm ";
     $sql .= "      left join db_usuarios on  db_usuarios.id_usuario = db_usuacgm.id_usuario ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed18_i_codigo!=null){
         $sql2 .= " where escola.ed18_i_codigo = $ed18_i_codigo ";
         if($iCodUsuario!=null){
           $sql2 .= " and db_usuarios.id_usuario = $iCodUsuario ";
         }
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
       if($iCodUsuario!=null){
         $sql2 .= " and db_usuarios.id_usuario = $iCodUsuario ";
       }
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
   function sql_query_consumoescola ( $ed18_i_codigo=null,$campos="*",$ordem=null,$dbwhere="", $iCodCardapio = ''){

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
     $sql .= " from escola ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm            = db_config.numcgm";
     $sql .= "      left join mer_cardapioescola on  mer_cardapioescola.me32_i_escola    = escola.ed18_i_codigo ";
     $sql .= "                         and  mer_cardapioescola.me32_i_tipocardapio  = {$iCodCardapio}";
     $sql2 = "";
     if($dbwhere==""){
       if($escola!=null ){
         $sql2 .= " where escola.ed18_i_codigo = $escola ";
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
   function sql_query_telefone( $ed18_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from escola
                    inner join telefoneescola on telefoneescola.ed26_i_escola = escola.ed18_i_codigo                    ";

     $sql2 = "";
     if($dbwhere==""){
       if($ed18_i_codigo!=null ){
         $sql2 .= " where escola.ed18_i_codigo = $ed18_i_codigo ";
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
   function sql_query_deptousuario ( $ed18_i_codigo=null,$campos="*",$ordem=null,$dbwhere="", $iNutricionista = ''){

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
     $sql .= " from escola ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm            = db_config.numcgm";
     $sql .= "      left join mer_nutricionistaescola on  mer_nutricionistaescola.me31_i_escola    = escola.ed18_i_codigo ";
     $sql .= "                         and  mer_nutricionistaescola.me31_i_nutricionista  = {$iNutricionista}";
     $sql2 = "";
     if($dbwhere==""){
       if($escola!=null ){
         $sql2 .= " where escola.ed18_i_codigo = $escola ";
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
   function sql_query_estado ( $ed18_i_codigo=null, $campos="*", $ordem=null, $dbwhere="") {

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
    $sql .= " from escola ";
    $sql .= "      inner join bairro          on  bairro.j13_codi                 = escola.ed18_i_bairro";
    $sql .= "      inner join ruas            on  ruas.j14_codigo                 = escola.ed18_i_rua";
    $sql .= "      inner join db_depart       on  db_depart.coddepto              = escola.ed18_i_codigo";
    $sql .= "      inner join db_config       on  db_config.codigo                = db_depart.instit";
    $sql .= "      inner join db_uf           on  db_uf.db12_uf                   = db_config.uf ";
    $sql .= "      inner join censouf         on  censouf.ed260_i_codigo          = escola.ed18_i_censouf";
    $sql .= "      inner join censomunic      on  censomunic.ed261_i_codigo       = escola.ed18_i_censomunic";
    $sql .= "      left join censodistrito    on  censodistrito.ed262_i_codigo    = escola.ed18_i_censodistrito";
    $sql .= "      left join censoorgreg      on  censoorgreg.ed263_i_codigo      = escola.ed18_i_censoorgreg";
    $sql .= "      left join censolinguaindig on  censolinguaindig.ed264_i_codigo = escola.ed18_i_linguaindigena";
    $sql2 = "";
    if($dbwhere==""){
      if($ed18_i_codigo!=null ){
        $sql2 .= " where escola.ed18_i_codigo = $ed18_i_codigo ";
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
   * Query para buscar os procedimentos da escola
   */
  function sql_query_procedimentos ( $ed18_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){

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
     $sql .= " from escola ";
     $sql .= "      inner join procescola        on  procescola.ed86_i_escola                 = escola.ed18_i_codigo";
     $sql .= "      inner join procedimento      on  procedimento.ed40_i_codigo               = procescola.ed86_i_procedimento";
     $sql .= "      inner join procavaliacao     on procavaliacao.ed41_i_procedimento         = procedimento.ed40_i_codigo";
     $sql .= "      inner join periodocalendario on periodocalendario.ed53_i_periodoavaliacao = procavaliacao.ed41_i_periodoavaliacao";
     $sql .= "      inner join calendario        on calendario.ed52_i_codigo                  = periodocalendario.ed53_i_calendario";
     $sql2 = "";
     if($dbwhere==""){
       if($ed18_i_codigo!=null ){
         $sql2 .= " where escola.ed18_i_codigo = $ed18_i_codigo ";
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

  function sql_query_mapa_estatistico($sOrdem = null, $sWhereTurma = null, $sWhereTurmaAC = null ) {

    $sCampos = " ed18_i_codigo as codigo, ed18_c_nome as escola, ed18_c_abrev as escola_abrev ";

    $sql  = " select {$sCampos} ";
    $sql .= "   from escola ";
    $sql .= "  inner join turma     on ed57_i_escola = ed18_i_codigo ";
    $sql .= "  inner join matricula on ed60_i_turma = ed57_i_codigo ";
    if ( !empty($sWhereTurma) ) {
      $sql .= "  where {$sWhereTurma} ";
    }
    $sql .= " union ";
    $sql .= "select {$sCampos} ";
    $sql .= "   from escola ";
    $sql .= "  inner join turmaac          on ed268_i_escola  = ed18_i_codigo ";
    $sql .= "  inner join turmaacmatricula on ed269_i_turmaac = ed268_i_codigo ";

    if ( !empty($sWhereTurmaAC) ) {
      $sql .= "  where {$sWhereTurmaAC} ";
    }

    if ( !empty($sOrdem) ) {
      $sql .= "  order by  {$sOrdem} ";
    }
    return $sql;
  }
}
?>
