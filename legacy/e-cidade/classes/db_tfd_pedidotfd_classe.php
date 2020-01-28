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

//MODULO: tfd
//CLASSE DA ENTIDADE tfd_pedidotfd
class cl_tfd_pedidotfd {
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
   var $tf01_i_codigo = 0;
   var $tf01_i_cgsund = 0;
   var $tf01_i_tipotransporte = 0;
   var $tf01_i_rhcbo = 0;
   var $tf01_d_datapedido_dia = null;
   var $tf01_d_datapedido_mes = null;
   var $tf01_d_datapedido_ano = null;
   var $tf01_d_datapedido = null;
   var $tf01_d_datapreferencia_dia = null;
   var $tf01_d_datapreferencia_mes = null;
   var $tf01_d_datapreferencia_ano = null;
   var $tf01_d_datapreferencia = null;
   var $tf01_i_depto = 0;
   var $tf01_i_situacao = 0;
   var $tf01_i_tipotratamento = 0;
   var $tf01_i_emergencia = 0;
   var $tf01_c_passagemplaca = null;
   var $tf01_d_datasistema_dia = null;
   var $tf01_d_datasistema_mes = null;
   var $tf01_d_datasistema_ano = null;
   var $tf01_d_datasistema = null;
   var $tf01_c_horasistema = null;
   var $tf01_i_login = 0;
   var $tf01_t_obs = null;
   var $tf01_i_profissionalsolic = 0;
   var $tf01_complespec = null;
   var $tf01_rhcbosolicitante = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 tf01_i_codigo = int4 = Código
                 tf01_i_cgsund = int4 = CGS
                 tf01_i_tipotransporte = int4 = Transporte
                 tf01_i_rhcbo = int4 = Especialidade
                 tf01_d_datapedido = date = Data do Pedido
                 tf01_d_datapreferencia = date = Data de Preferência
                 tf01_i_depto = int4 = Departamento
                 tf01_i_situacao = int4 = Situação
                 tf01_i_tipotratamento = int4 = Tipo
                 tf01_i_emergencia = int4 = Emergência
                 tf01_c_passagemplaca = varchar(10) = Passagem / Placa
                 tf01_d_datasistema = date = Data do sistema
                 tf01_c_horasistema = char(5) = Hora do sistema
                 tf01_i_login = int4 = Login
                 tf01_t_obs = text = Observação
                 tf01_i_profissionalsolic = int4 = Profissional Solicitante
                 tf01_complespec = varchar(50) = Complemento
                 tf01_rhcbosolicitante = int4 = Código cbo do solicitante
                 ";
   //funcao construtor da classe
   function cl_tfd_pedidotfd() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tfd_pedidotfd");
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
       $this->tf01_i_codigo = ($this->tf01_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_i_codigo"]:$this->tf01_i_codigo);
       $this->tf01_i_cgsund = ($this->tf01_i_cgsund == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_i_cgsund"]:$this->tf01_i_cgsund);
       $this->tf01_i_tipotransporte = ($this->tf01_i_tipotransporte == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_i_tipotransporte"]:$this->tf01_i_tipotransporte);
       $this->tf01_i_rhcbo = ($this->tf01_i_rhcbo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_i_rhcbo"]:$this->tf01_i_rhcbo);
       if($this->tf01_d_datapedido == ""){
         $this->tf01_d_datapedido_dia = ($this->tf01_d_datapedido_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_d_datapedido_dia"]:$this->tf01_d_datapedido_dia);
         $this->tf01_d_datapedido_mes = ($this->tf01_d_datapedido_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_d_datapedido_mes"]:$this->tf01_d_datapedido_mes);
         $this->tf01_d_datapedido_ano = ($this->tf01_d_datapedido_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_d_datapedido_ano"]:$this->tf01_d_datapedido_ano);
         if($this->tf01_d_datapedido_dia != ""){
            $this->tf01_d_datapedido = $this->tf01_d_datapedido_ano."-".$this->tf01_d_datapedido_mes."-".$this->tf01_d_datapedido_dia;
         }
       }
       if($this->tf01_d_datapreferencia == ""){
         $this->tf01_d_datapreferencia_dia = ($this->tf01_d_datapreferencia_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_d_datapreferencia_dia"]:$this->tf01_d_datapreferencia_dia);
         $this->tf01_d_datapreferencia_mes = ($this->tf01_d_datapreferencia_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_d_datapreferencia_mes"]:$this->tf01_d_datapreferencia_mes);
         $this->tf01_d_datapreferencia_ano = ($this->tf01_d_datapreferencia_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_d_datapreferencia_ano"]:$this->tf01_d_datapreferencia_ano);
         if($this->tf01_d_datapreferencia_dia != ""){
            $this->tf01_d_datapreferencia = $this->tf01_d_datapreferencia_ano."-".$this->tf01_d_datapreferencia_mes."-".$this->tf01_d_datapreferencia_dia;
         }
       }
       $this->tf01_i_depto = ($this->tf01_i_depto == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_i_depto"]:$this->tf01_i_depto);
       $this->tf01_i_situacao = ($this->tf01_i_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_i_situacao"]:$this->tf01_i_situacao);
       $this->tf01_i_tipotratamento = ($this->tf01_i_tipotratamento == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_i_tipotratamento"]:$this->tf01_i_tipotratamento);
       $this->tf01_i_emergencia = ($this->tf01_i_emergencia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_i_emergencia"]:$this->tf01_i_emergencia);
       $this->tf01_c_passagemplaca = ($this->tf01_c_passagemplaca == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_c_passagemplaca"]:$this->tf01_c_passagemplaca);
       if($this->tf01_d_datasistema == ""){
         $this->tf01_d_datasistema_dia = ($this->tf01_d_datasistema_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_d_datasistema_dia"]:$this->tf01_d_datasistema_dia);
         $this->tf01_d_datasistema_mes = ($this->tf01_d_datasistema_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_d_datasistema_mes"]:$this->tf01_d_datasistema_mes);
         $this->tf01_d_datasistema_ano = ($this->tf01_d_datasistema_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_d_datasistema_ano"]:$this->tf01_d_datasistema_ano);
         if($this->tf01_d_datasistema_dia != ""){
            $this->tf01_d_datasistema = $this->tf01_d_datasistema_ano."-".$this->tf01_d_datasistema_mes."-".$this->tf01_d_datasistema_dia;
         }
       }
       $this->tf01_c_horasistema = ($this->tf01_c_horasistema == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_c_horasistema"]:$this->tf01_c_horasistema);
       $this->tf01_i_login = ($this->tf01_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_i_login"]:$this->tf01_i_login);
       $this->tf01_t_obs = ($this->tf01_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_t_obs"]:$this->tf01_t_obs);
       $this->tf01_i_profissionalsolic = ($this->tf01_i_profissionalsolic == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_i_profissionalsolic"]:$this->tf01_i_profissionalsolic);
       $this->tf01_complespec = ($this->tf01_complespec == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_complespec"]:$this->tf01_complespec);
       $this->tf01_rhcbosolicitante = ($this->tf01_rhcbosolicitante == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_rhcbosolicitante"]:$this->tf01_rhcbosolicitante);
     }else{
       $this->tf01_i_codigo = ($this->tf01_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf01_i_codigo"]:$this->tf01_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($tf01_i_codigo){
      $this->atualizacampos();
     if($this->tf01_i_cgsund == null ){
       $this->erro_sql = " Campo CGS não informado.";
       $this->erro_campo = "tf01_i_cgsund";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf01_i_tipotransporte == null ){
       $this->erro_sql = " Campo Transporte não informado.";
       $this->erro_campo = "tf01_i_tipotransporte";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf01_i_rhcbo == null ){
       $this->erro_sql = " Campo Especialidade não informado.";
       $this->erro_campo = "tf01_i_rhcbo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf01_d_datapedido == null ){
       $this->erro_sql = " Campo Data do Pedido não informado.";
       $this->erro_campo = "tf01_d_datapedido_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf01_d_datapreferencia == null ){
       $this->erro_sql = " Campo Data de Preferência não informado.";
       $this->erro_campo = "tf01_d_datapreferencia_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf01_i_depto == null ){
       $this->erro_sql = " Campo Departamento não informado.";
       $this->erro_campo = "tf01_i_depto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf01_i_situacao == null ){
       $this->erro_sql = " Campo Situação não informado.";
       $this->erro_campo = "tf01_i_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf01_i_tipotratamento == null ){
       $this->erro_sql = " Campo Tipo não informado.";
       $this->erro_campo = "tf01_i_tipotratamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf01_i_emergencia == null ){
       $this->erro_sql = " Campo Emergência não informado.";
       $this->erro_campo = "tf01_i_emergencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf01_d_datasistema == null ){
       $this->erro_sql = " Campo Data do sistema não informado.";
       $this->erro_campo = "tf01_d_datasistema_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf01_c_horasistema == null ){
       $this->erro_sql = " Campo Hora do sistema não informado.";
       $this->erro_campo = "tf01_c_horasistema";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf01_i_login == null ){
       $this->erro_sql = " Campo Login não informado.";
       $this->erro_campo = "tf01_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf01_i_profissionalsolic == null ){
       $this->tf01_i_profissionalsolic = "null";
     }
     if($this->tf01_rhcbosolicitante == null ){
       $this->erro_sql = " Campo Código cbo do solicitante não informado.";
       $this->erro_campo = "tf01_rhcbosolicitante";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tf01_i_codigo == "" || $tf01_i_codigo == null ){
       $result = db_query("select nextval('tfd_pedidotfd_tf01_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tfd_pedidotfd_tf01_i_codigo_seq do campo: tf01_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->tf01_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from tfd_pedidotfd_tf01_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $tf01_i_codigo)){
         $this->erro_sql = " Campo tf01_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tf01_i_codigo = $tf01_i_codigo;
       }
     }
     if(($this->tf01_i_codigo == null) || ($this->tf01_i_codigo == "") ){
       $this->erro_sql = " Campo tf01_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tfd_pedidotfd(
                                       tf01_i_codigo
                                      ,tf01_i_cgsund
                                      ,tf01_i_tipotransporte
                                      ,tf01_i_rhcbo
                                      ,tf01_d_datapedido
                                      ,tf01_d_datapreferencia
                                      ,tf01_i_depto
                                      ,tf01_i_situacao
                                      ,tf01_i_tipotratamento
                                      ,tf01_i_emergencia
                                      ,tf01_c_passagemplaca
                                      ,tf01_d_datasistema
                                      ,tf01_c_horasistema
                                      ,tf01_i_login
                                      ,tf01_t_obs
                                      ,tf01_i_profissionalsolic
                                      ,tf01_complespec
                                      ,tf01_rhcbosolicitante
                       )
                values (
                                $this->tf01_i_codigo
                               ,$this->tf01_i_cgsund
                               ,$this->tf01_i_tipotransporte
                               ,$this->tf01_i_rhcbo
                               ,".($this->tf01_d_datapedido == "null" || $this->tf01_d_datapedido == ""?"null":"'".$this->tf01_d_datapedido."'")."
                               ,".($this->tf01_d_datapreferencia == "null" || $this->tf01_d_datapreferencia == ""?"null":"'".$this->tf01_d_datapreferencia."'")."
                               ,$this->tf01_i_depto
                               ,$this->tf01_i_situacao
                               ,$this->tf01_i_tipotratamento
                               ,$this->tf01_i_emergencia
                               ,'$this->tf01_c_passagemplaca'
                               ,".($this->tf01_d_datasistema == "null" || $this->tf01_d_datasistema == ""?"null":"'".$this->tf01_d_datasistema."'")."
                               ,'$this->tf01_c_horasistema'
                               ,$this->tf01_i_login
                               ,'$this->tf01_t_obs'
                               ,$this->tf01_i_profissionalsolic
                               ,'$this->tf01_complespec'
                               ,$this->tf01_rhcbosolicitante
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tfd_pedidotfd ($this->tf01_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tfd_pedidotfd já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tfd_pedidotfd ($this->tf01_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf01_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tf01_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16318,'$this->tf01_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,2857,16318,'','".AddSlashes(pg_result($resaco,0,'tf01_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2857,16319,'','".AddSlashes(pg_result($resaco,0,'tf01_i_cgsund'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2857,16320,'','".AddSlashes(pg_result($resaco,0,'tf01_i_tipotransporte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2857,16323,'','".AddSlashes(pg_result($resaco,0,'tf01_i_rhcbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2857,16325,'','".AddSlashes(pg_result($resaco,0,'tf01_d_datapedido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2857,16326,'','".AddSlashes(pg_result($resaco,0,'tf01_d_datapreferencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2857,16332,'','".AddSlashes(pg_result($resaco,0,'tf01_i_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2857,16321,'','".AddSlashes(pg_result($resaco,0,'tf01_i_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2857,16322,'','".AddSlashes(pg_result($resaco,0,'tf01_i_tipotratamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2857,16327,'','".AddSlashes(pg_result($resaco,0,'tf01_i_emergencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2857,16328,'','".AddSlashes(pg_result($resaco,0,'tf01_c_passagemplaca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2857,16329,'','".AddSlashes(pg_result($resaco,0,'tf01_d_datasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2857,16330,'','".AddSlashes(pg_result($resaco,0,'tf01_c_horasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2857,16331,'','".AddSlashes(pg_result($resaco,0,'tf01_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2857,17124,'','".AddSlashes(pg_result($resaco,0,'tf01_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2857,17593,'','".AddSlashes(pg_result($resaco,0,'tf01_i_profissionalsolic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2857,18194,'','".AddSlashes(pg_result($resaco,0,'tf01_complespec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2857,20312,'','".AddSlashes(pg_result($resaco,0,'tf01_rhcbosolicitante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($tf01_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update tfd_pedidotfd set ";
     $virgula = "";
     if(trim($this->tf01_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf01_i_codigo"])){
       $sql  .= $virgula." tf01_i_codigo = $this->tf01_i_codigo ";
       $virgula = ",";
       if(trim($this->tf01_i_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "tf01_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf01_i_cgsund)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf01_i_cgsund"])){
       $sql  .= $virgula." tf01_i_cgsund = $this->tf01_i_cgsund ";
       $virgula = ",";
       if(trim($this->tf01_i_cgsund) == null ){
         $this->erro_sql = " Campo CGS não informado.";
         $this->erro_campo = "tf01_i_cgsund";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf01_i_tipotransporte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf01_i_tipotransporte"])){
       $sql  .= $virgula." tf01_i_tipotransporte = $this->tf01_i_tipotransporte ";
       $virgula = ",";
       if(trim($this->tf01_i_tipotransporte) == null ){
         $this->erro_sql = " Campo Transporte não informado.";
         $this->erro_campo = "tf01_i_tipotransporte";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf01_i_rhcbo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf01_i_rhcbo"])){
       $sql  .= $virgula." tf01_i_rhcbo = $this->tf01_i_rhcbo ";
       $virgula = ",";
       if(trim($this->tf01_i_rhcbo) == null ){
         $this->erro_sql = " Campo Especialidade não informado.";
         $this->erro_campo = "tf01_i_rhcbo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf01_d_datapedido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf01_d_datapedido_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf01_d_datapedido_dia"] !="") ){
       $sql  .= $virgula." tf01_d_datapedido = '$this->tf01_d_datapedido' ";
       $virgula = ",";
       if(trim($this->tf01_d_datapedido) == null ){
         $this->erro_sql = " Campo Data do Pedido não informado.";
         $this->erro_campo = "tf01_d_datapedido_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf01_d_datapedido_dia"])){
         $sql  .= $virgula." tf01_d_datapedido = null ";
         $virgula = ",";
         if(trim($this->tf01_d_datapedido) == null ){
           $this->erro_sql = " Campo Data do Pedido não informado.";
           $this->erro_campo = "tf01_d_datapedido_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tf01_d_datapreferencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf01_d_datapreferencia_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf01_d_datapreferencia_dia"] !="") ){
       $sql  .= $virgula." tf01_d_datapreferencia = '$this->tf01_d_datapreferencia' ";
       $virgula = ",";
       if(trim($this->tf01_d_datapreferencia) == null ){
         $this->erro_sql = " Campo Data de Preferência não informado.";
         $this->erro_campo = "tf01_d_datapreferencia_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf01_d_datapreferencia_dia"])){
         $sql  .= $virgula." tf01_d_datapreferencia = null ";
         $virgula = ",";
         if(trim($this->tf01_d_datapreferencia) == null ){
           $this->erro_sql = " Campo Data de Preferência não informado.";
           $this->erro_campo = "tf01_d_datapreferencia_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tf01_i_depto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf01_i_depto"])){
       $sql  .= $virgula." tf01_i_depto = $this->tf01_i_depto ";
       $virgula = ",";
       if(trim($this->tf01_i_depto) == null ){
         $this->erro_sql = " Campo Departamento não informado.";
         $this->erro_campo = "tf01_i_depto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf01_i_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf01_i_situacao"])){
       $sql  .= $virgula." tf01_i_situacao = $this->tf01_i_situacao ";
       $virgula = ",";
       if(trim($this->tf01_i_situacao) == null ){
         $this->erro_sql = " Campo Situação não informado.";
         $this->erro_campo = "tf01_i_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf01_i_tipotratamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf01_i_tipotratamento"])){
       $sql  .= $virgula." tf01_i_tipotratamento = $this->tf01_i_tipotratamento ";
       $virgula = ",";
       if(trim($this->tf01_i_tipotratamento) == null ){
         $this->erro_sql = " Campo Tipo não informado.";
         $this->erro_campo = "tf01_i_tipotratamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf01_i_emergencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf01_i_emergencia"])){
       $sql  .= $virgula." tf01_i_emergencia = $this->tf01_i_emergencia ";
       $virgula = ",";
       if(trim($this->tf01_i_emergencia) == null ){
         $this->erro_sql = " Campo Emergência não informado.";
         $this->erro_campo = "tf01_i_emergencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf01_c_passagemplaca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf01_c_passagemplaca"])){
       $sql  .= $virgula." tf01_c_passagemplaca = '$this->tf01_c_passagemplaca' ";
       $virgula = ",";
     }
     if(trim($this->tf01_d_datasistema)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf01_d_datasistema_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf01_d_datasistema_dia"] !="") ){
       $sql  .= $virgula." tf01_d_datasistema = '$this->tf01_d_datasistema' ";
       $virgula = ",";
       if(trim($this->tf01_d_datasistema) == null ){
         $this->erro_sql = " Campo Data do sistema não informado.";
         $this->erro_campo = "tf01_d_datasistema_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf01_d_datasistema_dia"])){
         $sql  .= $virgula." tf01_d_datasistema = null ";
         $virgula = ",";
         if(trim($this->tf01_d_datasistema) == null ){
           $this->erro_sql = " Campo Data do sistema não informado.";
           $this->erro_campo = "tf01_d_datasistema_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tf01_c_horasistema)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf01_c_horasistema"])){
       $sql  .= $virgula." tf01_c_horasistema = '$this->tf01_c_horasistema' ";
       $virgula = ",";
       if(trim($this->tf01_c_horasistema) == null ){
         $this->erro_sql = " Campo Hora do sistema não informado.";
         $this->erro_campo = "tf01_c_horasistema";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf01_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf01_i_login"])){
       $sql  .= $virgula." tf01_i_login = $this->tf01_i_login ";
       $virgula = ",";
       if(trim($this->tf01_i_login) == null ){
         $this->erro_sql = " Campo Login não informado.";
         $this->erro_campo = "tf01_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf01_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf01_t_obs"])){
       $sql  .= $virgula." tf01_t_obs = '$this->tf01_t_obs' ";
       $virgula = ",";
     }
     if(trim($this->tf01_i_profissionalsolic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf01_i_profissionalsolic"])){
        if(trim($this->tf01_i_profissionalsolic)=="" && isset($GLOBALS["HTTP_POST_VARS"]["tf01_i_profissionalsolic"])){
           $this->tf01_i_profissionalsolic = "0" ;
        }
       $sql  .= $virgula." tf01_i_profissionalsolic = $this->tf01_i_profissionalsolic ";
       $virgula = ",";
     }
     if(trim($this->tf01_complespec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf01_complespec"])){
       $sql  .= $virgula." tf01_complespec = '$this->tf01_complespec' ";
       $virgula = ",";
     }
     if(trim($this->tf01_rhcbosolicitante)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf01_rhcbosolicitante"])){
       $sql  .= $virgula." tf01_rhcbosolicitante = $this->tf01_rhcbosolicitante ";
       $virgula = ",";
       if(trim($this->tf01_rhcbosolicitante) == null ){
         $this->erro_sql = " Campo Código cbo do solicitante não informado.";
         $this->erro_campo = "tf01_rhcbosolicitante";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tf01_i_codigo!=null){
       $sql .= " tf01_i_codigo = $this->tf01_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tf01_i_codigo));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,16318,'$this->tf01_i_codigo','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf01_i_codigo"]) || $this->tf01_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2857,16318,'".AddSlashes(pg_result($resaco,$conresaco,'tf01_i_codigo'))."','$this->tf01_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf01_i_cgsund"]) || $this->tf01_i_cgsund != "")
             $resac = db_query("insert into db_acount values($acount,2857,16319,'".AddSlashes(pg_result($resaco,$conresaco,'tf01_i_cgsund'))."','$this->tf01_i_cgsund',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf01_i_tipotransporte"]) || $this->tf01_i_tipotransporte != "")
             $resac = db_query("insert into db_acount values($acount,2857,16320,'".AddSlashes(pg_result($resaco,$conresaco,'tf01_i_tipotransporte'))."','$this->tf01_i_tipotransporte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf01_i_rhcbo"]) || $this->tf01_i_rhcbo != "")
             $resac = db_query("insert into db_acount values($acount,2857,16323,'".AddSlashes(pg_result($resaco,$conresaco,'tf01_i_rhcbo'))."','$this->tf01_i_rhcbo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf01_d_datapedido"]) || $this->tf01_d_datapedido != "")
             $resac = db_query("insert into db_acount values($acount,2857,16325,'".AddSlashes(pg_result($resaco,$conresaco,'tf01_d_datapedido'))."','$this->tf01_d_datapedido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf01_d_datapreferencia"]) || $this->tf01_d_datapreferencia != "")
             $resac = db_query("insert into db_acount values($acount,2857,16326,'".AddSlashes(pg_result($resaco,$conresaco,'tf01_d_datapreferencia'))."','$this->tf01_d_datapreferencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf01_i_depto"]) || $this->tf01_i_depto != "")
             $resac = db_query("insert into db_acount values($acount,2857,16332,'".AddSlashes(pg_result($resaco,$conresaco,'tf01_i_depto'))."','$this->tf01_i_depto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf01_i_situacao"]) || $this->tf01_i_situacao != "")
             $resac = db_query("insert into db_acount values($acount,2857,16321,'".AddSlashes(pg_result($resaco,$conresaco,'tf01_i_situacao'))."','$this->tf01_i_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf01_i_tipotratamento"]) || $this->tf01_i_tipotratamento != "")
             $resac = db_query("insert into db_acount values($acount,2857,16322,'".AddSlashes(pg_result($resaco,$conresaco,'tf01_i_tipotratamento'))."','$this->tf01_i_tipotratamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf01_i_emergencia"]) || $this->tf01_i_emergencia != "")
             $resac = db_query("insert into db_acount values($acount,2857,16327,'".AddSlashes(pg_result($resaco,$conresaco,'tf01_i_emergencia'))."','$this->tf01_i_emergencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf01_c_passagemplaca"]) || $this->tf01_c_passagemplaca != "")
             $resac = db_query("insert into db_acount values($acount,2857,16328,'".AddSlashes(pg_result($resaco,$conresaco,'tf01_c_passagemplaca'))."','$this->tf01_c_passagemplaca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf01_d_datasistema"]) || $this->tf01_d_datasistema != "")
             $resac = db_query("insert into db_acount values($acount,2857,16329,'".AddSlashes(pg_result($resaco,$conresaco,'tf01_d_datasistema'))."','$this->tf01_d_datasistema',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf01_c_horasistema"]) || $this->tf01_c_horasistema != "")
             $resac = db_query("insert into db_acount values($acount,2857,16330,'".AddSlashes(pg_result($resaco,$conresaco,'tf01_c_horasistema'))."','$this->tf01_c_horasistema',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf01_i_login"]) || $this->tf01_i_login != "")
             $resac = db_query("insert into db_acount values($acount,2857,16331,'".AddSlashes(pg_result($resaco,$conresaco,'tf01_i_login'))."','$this->tf01_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf01_t_obs"]) || $this->tf01_t_obs != "")
             $resac = db_query("insert into db_acount values($acount,2857,17124,'".AddSlashes(pg_result($resaco,$conresaco,'tf01_t_obs'))."','$this->tf01_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf01_i_profissionalsolic"]) || $this->tf01_i_profissionalsolic != "")
             $resac = db_query("insert into db_acount values($acount,2857,17593,'".AddSlashes(pg_result($resaco,$conresaco,'tf01_i_profissionalsolic'))."','$this->tf01_i_profissionalsolic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf01_complespec"]) || $this->tf01_complespec != "")
             $resac = db_query("insert into db_acount values($acount,2857,18194,'".AddSlashes(pg_result($resaco,$conresaco,'tf01_complespec'))."','$this->tf01_complespec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf01_rhcbosolicitante"]) || $this->tf01_rhcbosolicitante != "")
             $resac = db_query("insert into db_acount values($acount,2857,20312,'".AddSlashes(pg_result($resaco,$conresaco,'tf01_rhcbosolicitante'))."','$this->tf01_rhcbosolicitante',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_pedidotfd nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf01_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_pedidotfd nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf01_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf01_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($tf01_i_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($tf01_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,16318,'$tf01_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,2857,16318,'','".AddSlashes(pg_result($resaco,$iresaco,'tf01_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2857,16319,'','".AddSlashes(pg_result($resaco,$iresaco,'tf01_i_cgsund'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2857,16320,'','".AddSlashes(pg_result($resaco,$iresaco,'tf01_i_tipotransporte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2857,16323,'','".AddSlashes(pg_result($resaco,$iresaco,'tf01_i_rhcbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2857,16325,'','".AddSlashes(pg_result($resaco,$iresaco,'tf01_d_datapedido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2857,16326,'','".AddSlashes(pg_result($resaco,$iresaco,'tf01_d_datapreferencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2857,16332,'','".AddSlashes(pg_result($resaco,$iresaco,'tf01_i_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2857,16321,'','".AddSlashes(pg_result($resaco,$iresaco,'tf01_i_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2857,16322,'','".AddSlashes(pg_result($resaco,$iresaco,'tf01_i_tipotratamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2857,16327,'','".AddSlashes(pg_result($resaco,$iresaco,'tf01_i_emergencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2857,16328,'','".AddSlashes(pg_result($resaco,$iresaco,'tf01_c_passagemplaca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2857,16329,'','".AddSlashes(pg_result($resaco,$iresaco,'tf01_d_datasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2857,16330,'','".AddSlashes(pg_result($resaco,$iresaco,'tf01_c_horasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2857,16331,'','".AddSlashes(pg_result($resaco,$iresaco,'tf01_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2857,17124,'','".AddSlashes(pg_result($resaco,$iresaco,'tf01_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2857,17593,'','".AddSlashes(pg_result($resaco,$iresaco,'tf01_i_profissionalsolic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2857,18194,'','".AddSlashes(pg_result($resaco,$iresaco,'tf01_complespec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2857,20312,'','".AddSlashes(pg_result($resaco,$iresaco,'tf01_rhcbosolicitante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from tfd_pedidotfd
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tf01_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tf01_i_codigo = $tf01_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_pedidotfd nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tf01_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_pedidotfd nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tf01_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tf01_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tfd_pedidotfd";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $tf01_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from tfd_pedidotfd ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tfd_pedidotfd.tf01_i_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = tfd_pedidotfd.tf01_i_depto";
     $sql .= "      inner join rhcbo as cboespecialidade on cboespecialidade.rh70_sequencial = tfd_pedidotfd.tf01_i_rhcbo ";
     $sql .= "      inner join rhcbo as cbosolicitante   on cbosolicitante.rh70_sequencial = tfd_pedidotfd.tf01_rhcbosolicitante";
     $sql .= "      inner join tfd_tipotratamento  on  tfd_tipotratamento.tf04_i_codigo = tfd_pedidotfd.tf01_i_tipotratamento";
     $sql .= "      inner join tfd_situacaotfd  on  tfd_situacaotfd.tf26_i_codigo = tfd_pedidotfd.tf01_i_situacao";
     $sql .= "      inner join tfd_tipotransporte  on  tfd_tipotransporte.tf27_i_codigo = tfd_pedidotfd.tf01_i_tipotransporte";
     $sql .= "      left  join medicos  on  medicos.sd03_i_codigo = tfd_pedidotfd.tf01_i_profissionalsolic";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = tfd_pedidotfd.tf01_i_cgsund";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = medicos.sd03_i_cgm";
     $sql .= "      left  join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
     $sql .= "      inner join cgs  as a on   a.z01_i_numcgs = cgs_und.z01_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($tf01_i_codigo!=null ){
         $sql2 .= " where tfd_pedidotfd.tf01_i_codigo = $tf01_i_codigo ";
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
   function sql_query_file ( $tf01_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from tfd_pedidotfd ";
     $sql2 = "";
     if($dbwhere==""){
       if($tf01_i_codigo!=null ){
         $sql2 .= " where tfd_pedidotfd.tf01_i_codigo = $tf01_i_codigo ";
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
   // função sql para o grid da aba pedido do procedimento pedido tfd -> lança e manutanção
   function sql_query_grid( $tf01_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from tfd_pedidotfd ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tfd_pedidotfd.tf01_i_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = tfd_pedidotfd.tf01_i_depto";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = tfd_pedidotfd.tf01_i_rhcbo";
     $sql .= "      inner join tfd_tipotratamento  on  tfd_tipotratamento.tf04_i_codigo = tfd_pedidotfd.tf01_i_tipotratamento";
     $sql .= "      inner join tfd_situacaotfd  on  tfd_situacaotfd.tf26_i_codigo = tfd_pedidotfd.tf01_i_situacao";
     $sql .= "      inner join tfd_tipotransporte  on  tfd_tipotransporte.tf27_i_codigo = tfd_pedidotfd.tf01_i_tipotransporte";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = tfd_pedidotfd.tf01_i_cgsund";
     $sql .= "      left join tfd_agendamentoprestadora  on  tfd_agendamentoprestadora.tf16_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo";
     $sql .= "      left join tfd_prestadoracentralagend  on  tfd_prestadoracentralagend.tf10_i_codigo = tfd_agendamentoprestadora.tf16_i_prestcentralagend";
     $sql .= "      left join tfd_prestadora  on  tfd_prestadora.tf25_i_codigo = tfd_prestadoracentralagend.tf10_i_prestadora";
     $sql .= "      left join cgm  on  cgm.z01_numcgm = tfd_prestadora.tf25_i_cgm";
     $sql .= "      left join tfd_destino  on  tfd_destino.tf03_i_codigo = tfd_prestadora.tf25_i_destino";
     $sql .= "      left join tfd_agendasaida  on  tfd_agendasaida.tf17_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($tf01_i_codigo!=null ){
         $sql2 .= " where tfd_pedidotfd.tf01_i_codigo = $tf01_i_codigo ";
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
   function sql_query_protocolo ( $tf01_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from tfd_pedidotfd ";
     $sql .= " inner join db_usuarios on db_usuarios.id_usuario = tfd_pedidotfd.tf01_i_login";
     $sql .= " inner join db_depart on db_depart.coddepto = tfd_pedidotfd.tf01_i_depto ";
     $sql .= " inner join rhcbo on rhcbo.rh70_sequencial = tfd_pedidotfd.tf01_i_rhcbo ";
     $sql .= " inner join tfd_tipotratamento on tfd_tipotratamento.tf04_i_codigo = tfd_pedidotfd.tf01_i_tipotratamento";
     $sql .= " inner join tfd_situacaotfd on tfd_situacaotfd.tf26_i_codigo = tfd_pedidotfd.tf01_i_situacao ";
     $sql .= " inner join tfd_tipotransporte on tfd_tipotransporte.tf27_i_codigo = tfd_pedidotfd.tf01_i_tipotransporte";
     $sql .= " left join tfd_agendamentoprestadora on tfd_agendamentoprestadora.tf16_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo";
     $sql .= " left join tfd_prestadoracentralagend on tfd_prestadoracentralagend.tf10_i_codigo = tfd_agendamentoprestadora.tf16_i_prestcentralagend";
     $sql .= " left join tfd_prestadora on tfd_prestadora.tf25_i_codigo = tfd_prestadoracentralagend.tf10_i_prestadora";
     $sql .= " left join cgm on cgm.z01_numcgm = tfd_prestadora.tf25_i_cgm";
     $sql .= " inner join cgs_und on cgs_und.z01_i_cgsund = tfd_pedidotfd.tf01_i_cgsund ";
     $sql .= " left  join medicos  on  medicos.sd03_i_codigo = tfd_pedidotfd.tf01_i_profissionalsolic";
     $sql .= " left  join cgm cgmmedico on  cgmmedico.z01_numcgm = medicos.sd03_i_cgm";
     $sql .= " left  join cgmdoc  on  cgmdoc.z02_i_cgm = cgmmedico.z01_numcgm";
     $sql .= " left  join sau_medicosforarede  on  sau_medicosforarede.s154_i_medico = medicos.sd03_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($tf01_i_codigo!=null ){
         $sql2 .= " where tfd_pedidotfd.tf01_i_codigo = $tf01_i_codigo ";
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
   function sql_query_regulado ( $tf01_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from tfd_pedidotfd ";
     $sql .= " inner join cgs_und on cgs_und.z01_i_cgsund = tfd_pedidotfd.tf01_i_cgsund ";
     $sql .= " inner join tfd_situacaotfd on tfd_situacaotfd.tf26_i_codigo = tfd_pedidotfd.tf01_i_situacao ";
     $sql .= " inner join tfd_tipotratamento on tfd_tipotratamento.tf04_i_codigo = tfd_pedidotfd.tf01_i_tipotratamento";
     $sql .= " inner join rhcbo on rhcbo.rh70_sequencial = tfd_pedidotfd.tf01_i_rhcbo ";
     $sql .= " left join tfd_agendasaida on tfd_agendasaida.tf17_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo ";
     $sql .= " left join tfd_pedidoregulado on tfd_pedidoregulado.tf34_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo";
     $sql .= " left join db_usuarios on db_usuarios.id_usuario = tfd_pedidoregulado.tf34_i_login";
     $sql .= " left join especmedico on especmedico.sd27_i_codigo = tfd_pedidoregulado.tf34_i_especmedico";
     $sql .= " left join unidademedicos on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
     $sql .= " left join medicos on medicos.sd03_i_codigo = unidademedicos.sd04_i_medico";
     $sql .= " left join cgm on cgm.z01_numcgm = medicos.sd03_i_cgm";
     $sql .= " left join rhcbo as a on a.rh70_sequencial = especmedico.sd27_i_rhcbo ";
     $sql2 = "";
     if($dbwhere==""){
       if($tf01_i_codigo!=null ){
         $sql2 .= " where tfd_pedidotfd.tf01_i_codigo = $tf01_i_codigo ";
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
   function sql_query_pedido($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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

    $sSql .= " from tfd_pedidotfd ";
    $sSql .= "      inner join db_usuarios on db_usuarios.id_usuario = tfd_pedidotfd.tf01_i_login ";
    $sSql .= "      inner join db_depart on db_depart.coddepto = tfd_pedidotfd.tf01_i_depto ";
    $sSql .= "      inner join rhcbo on rhcbo.rh70_sequencial = tfd_pedidotfd.tf01_i_rhcbo ";
    $sSql .= "      inner join tfd_tipotratamento on tfd_tipotratamento.tf04_i_codigo = ";
    $sSql .= "        tfd_pedidotfd.tf01_i_tipotratamento ";
    $sSql .= "      inner join tfd_situacaotfd on tfd_situacaotfd.tf26_i_codigo = tfd_pedidotfd.tf01_i_situacao ";
    $sSql .= "      inner join tfd_tipotransporte on tfd_tipotransporte.tf27_i_codigo = ";
    $sSql .= "        tfd_pedidotfd.tf01_i_tipotransporte ";

    $sSql .= "      left  join medicos on medicos.sd03_i_codigo = tfd_pedidotfd.tf01_i_profissionalsolic ";
    $sSql .= "      inner join cgs_und on cgs_und.z01_i_cgsund = tfd_pedidotfd.tf01_i_cgsund ";
    $sSql .= "      left  join cgm on cgm.z01_numcgm = medicos.sd03_i_cgm ";
    $sSql .= "      left  join sau_medicosforarede on sau_medicosforarede.s154_i_medico = medicos.sd03_i_codigo ";

    $sSql .= "      left join tfd_agendamentoprestadora  on  tfd_agendamentoprestadora.tf16_i_pedidotfd = ";
    $sSql .= "        tfd_pedidotfd.tf01_i_codigo ";
    $sSql .= "      left join tfd_prestadoracentralagend  on  tfd_prestadoracentralagend.tf10_i_codigo = ";
    $sSql .= "        tfd_agendamentoprestadora.tf16_i_prestcentralagend ";
    $sSql .= "      left join tfd_prestadora  on  tfd_prestadora.tf25_i_codigo = ";
    $sSql .= "        tfd_prestadoracentralagend.tf10_i_prestadora  ";
    $sSql .= "      left join cgm as cgmprest on  cgmprest.z01_numcgm = tfd_prestadora.tf25_i_cgm ";
    $sSql .= "      left join tfd_destino  on  tfd_destino.tf03_i_codigo = tfd_prestadora.tf25_i_destino ";
    $sSql .= "      left join tfd_agendasaida  on  tfd_agendasaida.tf17_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo ";

    $sSql .= "      left join tfd_centralagendamento  on  tfd_centralagendamento.tf09_i_codigo = ";
    $sSql .= "        tfd_prestadoracentralagend.tf10_i_centralagend ";
    $sSql .= "      left join cgm as cgmcentral on  cgmcentral.z01_numcgm = tfd_centralagendamento.tf09_i_numcgm ";
    $sSql .= "      left join tfd_passageiroveiculo  on tfd_passageiroveiculo.tf19_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo";
    $sSql .= "                                      and tfd_passageiroveiculo.tf19_i_valido = 1";
    $sSql .= "      left join tfd_veiculodestino on tfd_veiculodestino.tf18_i_codigo = tfd_passageiroveiculo.tf19_i_veiculodestino";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where tfd_pedidotfd.tf01_i_codigo = $iCodigo ";
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

  function sql_query_pedido_prestadora( $tf01_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

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
    $sql  .= " from tfd_pedidotfd ";
    $sql  .= "      left join tfd_agendamentoprestadora  on tfd_agendamentoprestadora.tf16_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo ";
    $sql2 = "";
    if ($dbwhere == "") {

      if ($tf01_i_codigo != null) {
        $sql2 .= " where tfd_pedidotfd.tf01_i_codigo = $tf01_i_codigo ";
      }
    } else if($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {

      $sql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_pedido_fechamento($tf32_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

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
    $sql  .= "  from tfd_pedidotfd ";
    $sql  .= " inner join tfd_agendamentoprestadora  on tfd_agendamentoprestadora.tf16_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo ";
    $sql2  = "";
    if ($dbwhere == "") {

      if ($tf32_i_codigo != null) {
        $sql2 .= " where tfd_fechamento.tf32_i_codigo = $tf32_i_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {

      $sql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_pedido_novo( $iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '' ) {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql     .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula  = ",";
      }
    } else {
      $sSql .= $sCampos;
    }

    $sSql .= " from tfd_pedidotfd ";
    $sSql .= "      inner join db_usuarios                 on db_usuarios.id_usuario = tfd_pedidotfd.tf01_i_login ";
    $sSql .= "      inner join db_depart                   on db_depart.coddepto     = tfd_pedidotfd.tf01_i_depto ";
    $sSql .= "      left  join medicos                     on medicos.sd03_i_codigo  = tfd_pedidotfd.tf01_i_profissionalsolic ";
    $sSql .= "      inner join cgs_und                     on cgs_und.z01_i_cgsund   = tfd_pedidotfd.tf01_i_cgsund ";
    $sSql .= "      left  join cgm                         on cgm.z01_numcgm         = medicos.sd03_i_cgm ";
    $sSql .= "      left  join tfd_agendamentoprestadora   on tfd_agendamentoprestadora.tf16_i_pedidotfd = ";
    $sSql .= "                                                tfd_pedidotfd.tf01_i_codigo ";
    $sSql .= "      left  join tfd_prestadoracentralagend  on tfd_prestadoracentralagend.tf10_i_codigo = ";
    $sSql .= "                                                tfd_agendamentoprestadora.tf16_i_prestcentralagend ";
    $sSql .= "      left  join tfd_prestadora              on tfd_prestadora.tf25_i_codigo = ";
    $sSql .= "                                                tfd_prestadoracentralagend.tf10_i_prestadora  ";
    $sSql .= "      left  join cgm as cgmprest             on cgmprest.z01_numcgm              = tfd_prestadora.tf25_i_cgm ";
    $sSql .= "      left  join tfd_destino                 on tfd_destino.tf03_i_codigo        = tfd_prestadora.tf25_i_destino ";
    $sSql .= "      left  join tfd_agendasaida             on tfd_agendasaida.tf17_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo ";
    $sSql .= "      left  join tfd_centralagendamento      on tfd_centralagendamento.tf09_i_codigo = ";
    $sSql .= "                                                tfd_prestadoracentralagend.tf10_i_centralagend ";
    $sSql .= "      left  join cgm as cgmcentral           on cgmcentral.z01_numcgm = tfd_centralagendamento.tf09_i_numcgm ";

    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where tfd_pedidotfd.tf01_i_codigo = $iCodigo ";
      }
    } else if ($sDbWhere != '') {
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

  /**
   * Query para buscar os dados do agendamento de saida vinculados ao pedido
   */
  function sql_query_pedido_saida($iCodigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sSql = "select ";
    if ($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sSql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sSql .= $campos;
    }
    $sSql  .= " from tfd_pedidotfd ";
    $sSql .= "       inner join cgs_und                     on cgs_und.z01_i_cgsund                       = tfd_pedidotfd.tf01_i_cgsund ";
    $sSql .= "       inner join tfd_agendamentoprestadora   on tfd_agendamentoprestadora.tf16_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo ";
    $sSql .= "       inner join tfd_prestadoracentralagend  on tfd_prestadoracentralagend.tf10_i_codigo   = tfd_agendamentoprestadora.tf16_i_prestcentralagend ";
    $sSql .= "       inner join tfd_prestadora              on tfd_prestadora.tf25_i_codigo               = tfd_prestadoracentralagend.tf10_i_prestadora  ";
    $sSql .= "       inner join cgm as cgmprest             on cgmprest.z01_numcgm                        = tfd_prestadora.tf25_i_cgm ";
    $sSql .= "       inner join tfd_destino                 on tfd_destino.tf03_i_codigo                  = tfd_prestadora.tf25_i_destino ";
    $sSql .= "       left  join  tfd_agendasaida            on tfd_agendasaida.tf17_i_pedidotfd           = tfd_pedidotfd.tf01_i_codigo ";
    $sSql .= "       left  join  tfd_passageiroveiculo      on tfd_passageiroveiculo.tf19_i_pedidotfd     = tfd_pedidotfd.tf01_i_codigo ";
    $sSql .= "                                             and tfd_passageiroveiculo.tf19_i_cgsund        = tfd_pedidotfd.tf01_i_cgsund ";
    $sSql .= "       left  join  tfd_veiculodestino         on tfd_veiculodestino.tf18_i_codigo           = tfd_passageiroveiculo.tf19_i_veiculodestino";
    $sSql .= "       left  join  veiculos                   on veiculos.ve01_codigo                       = tfd_veiculodestino.tf18_i_veiculo";
    $sSql .= "       left  join  veicmotoristas             on veicmotoristas.ve05_codigo                 = tfd_veiculodestino.tf18_i_motorista";
    $sSql .= "       left  join  cgm as cgm_motorista       on cgm_motorista.z01_numcgm                   = veicmotoristas.ve05_numcgm";
    $sSql .= "       left  join  agendasaidapassagemdestino on agendasaidapassagemdestino.tf38_agendasaida= tfd_agendasaida.tf17_i_codigo";
    $sSql .= "       left  join  passagemdestino            on passagemdestino.tf37_destino               = tfd_destino.tf03_i_codigo";
    $sql2  = "";
    if ($dbwhere == "") {

      if ($iCodigo != null) {
        $sql2 .= " where tfd_pedidotfd.tf01_i_codigo = $iCodigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sSql .= $sql2;
    if ($ordem != null ) {

      $sSql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sSql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sSql;
  }

  public function sql_query_andamento_pedido( $iCodigoPedido ) {

    $sSql  = " select * ";
    $sSql .= "   from ";
    $sSql .= "         (select ";
    $sSql .= "           ";
    $sSql .= "                tf26_c_descr        as situacao, ";
    $sSql .= "                tf28_d_datasistema  as data, ";
    $sSql .= "                tf28_c_horasistema  as hora, ";
    $sSql .= "                tf28_c_obs          as observacao,";
    $sSql .= "                tf28_i_login        as usuario";
    $sSql .= "           from tfd_pedidotfd";
    $sSql .= "          left  join tfd_situacaopedidotfd on tf28_i_pedidotfd = tf01_i_codigo";
    $sSql .= "          left  join tfd_situacaotfd       on  tf28_i_situacao = tf26_i_codigo";
    $sSql .= "          where tf01_i_codigo = {$iCodigoPedido} ";
    $sSql .= "         union  ";
    $sSql .= "         select ";
    $sSql .= "                 null             as situacao, ";
    $sSql .= "                 tf21_d_dataaviso as data, ";
    $sSql .= "                 tf21_c_horaaviso as hora, ";
    $sSql .= "                 'Contato -  ' || tf20_c_descr || ' -  '|| tf21_t_obs as observacao, ";
    $sSql .= "                 tf21_i_login as usuario";
    $sSql .= "                ";
    $sSql .= "           from tfd_pedidotfd";
    $sSql .= "          left  join tfd_avisopaciente on tf21_i_pedidotfd = tf01_i_codigo";
    $sSql .= "          left  join tfd_formaaviso    on tf20_i_codigo  =tf21_i_formaaviso";
    $sSql .= "         where tf01_i_codigo = {$iCodigoPedido} ";
    $sSql .= "         ) as x";
    $sSql .= " order by data, hora";

    return $sSql;
  }

  /**
   * Query para buscar os dados do regulador
   */
  function sql_query_pedido_regulado($iCodigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sSql = "select ";
    if ($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sSql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sSql .= $campos;
    }
    $sSql .= "  from tfd_pedidotfd ";
    $sSql .= " left join tfd_pedidoregulado on tfd_pedidoregulado.tf34_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo";
    $sSql .= " left join especmedico        on especmedico.sd27_i_codigo           = tfd_pedidoregulado.tf34_i_especmedico";
    $sSql .= " left join unidademedicos     on unidademedicos.sd04_i_codigo        = especmedico.sd27_i_undmed";
    $sSql .= " left join db_depart          on db_depart.coddepto                  = unidademedicos.sd04_i_unidade";
    $sSql .= " left join medicos            on medicos.sd03_i_codigo               = unidademedicos.sd04_i_medico";
    $sSql .= " left join cgm                on cgm.z01_numcgm                      = medicos.sd03_i_cgm";
    $sSql .= " left join rhcbo              on rhcbo.rh70_sequencial               = especmedico.sd27_i_rhcbo";
    $sql2  = "";
    if ($dbwhere == "") {

      if ($iCodigo != null) {
        $sql2 .= " where tfd_pedidotfd.tf01_i_codigo = $iCodigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sSql .= $sql2;
    if ($ordem != null ) {

      $sSql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sSql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sSql;
  }

  function sql_query_protocolo_emissao ( $tf01_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from tfd_pedidotfd ";
     $sql .= "  inner join db_usuarios on db_usuarios.id_usuario = tfd_pedidotfd.tf01_i_login";
     $sql .= "  inner join db_depart on db_depart.coddepto = tfd_pedidotfd.tf01_i_depto ";
     $sql .= "  inner join rhcbo on rhcbo.rh70_sequencial = tfd_pedidotfd.tf01_i_rhcbo ";
     $sql .= "  inner join tfd_tipotratamento on tfd_tipotratamento.tf04_i_codigo = tfd_pedidotfd.tf01_i_tipotratamento";
     $sql .= "  inner join tfd_situacaotfd on tfd_situacaotfd.tf26_i_codigo = tfd_pedidotfd.tf01_i_situacao ";
     $sql .= "  inner join tfd_tipotransporte on tfd_tipotransporte.tf27_i_codigo = tfd_pedidotfd.tf01_i_tipotransporte";
     $sql .= "  left join tfd_agendamentoprestadora on tfd_agendamentoprestadora.tf16_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo";
     $sql .= "  left join tfd_prestadoracentralagend on tfd_prestadoracentralagend.tf10_i_codigo = tfd_agendamentoprestadora.tf16_i_prestcentralagend";
     $sql .= "  left join tfd_prestadora on tfd_prestadora.tf25_i_codigo = tfd_prestadoracentralagend.tf10_i_prestadora";
     $sql .= "  left join cgm on cgm.z01_numcgm = tfd_prestadora.tf25_i_cgm";
     $sql .= "  inner join cgs_und on cgs_und.z01_i_cgsund = tfd_pedidotfd.tf01_i_cgsund ";
     $sql .= "  left  join medicos  on  medicos.sd03_i_codigo = tfd_pedidotfd.tf01_i_profissionalsolic";
     $sql .= "  left  join cgm cgmmedico on  cgmmedico.z01_numcgm = medicos.sd03_i_cgm";
     $sql .= "  left  join cgmdoc  on  cgmdoc.z02_i_cgm = cgmmedico.z01_numcgm";
     $sql .= "  left  join sau_medicosforarede  on  sau_medicosforarede.s154_i_medico = medicos.sd03_i_codigo";
     $sql .= "  left  join tfd_agendasaida on tfd_agendasaida.tf17_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo";
     $sql .= "  left join  tfd_passageiroveiculo  on tfd_passageiroveiculo.tf19_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo
                                                 and tfd_passageiroveiculo.tf19_i_cgsund    = tfd_pedidotfd.tf01_i_cgsund ";
     $sql .= "  left  join  tfd_veiculodestino on tfd_veiculodestino.tf18_i_codigo = tfd_passageiroveiculo.tf19_i_veiculodestino";
     $sql2 = "";
     if($dbwhere==""){
       if($tf01_i_codigo!=null ){
         $sql2 .= " where tfd_pedidotfd.tf01_i_codigo = $tf01_i_codigo ";
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

  function sql_query_pedido_relatorio($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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

    $sSql .= " from tfd_pedidotfd ";
    $sSql .= "      inner join db_usuarios on db_usuarios.id_usuario = tfd_pedidotfd.tf01_i_login ";
    $sSql .= "      inner join db_depart on db_depart.coddepto = tfd_pedidotfd.tf01_i_depto ";
    $sSql .= "      inner join rhcbo on rhcbo.rh70_sequencial = tfd_pedidotfd.tf01_i_rhcbo ";
    $sSql .= "      inner join tfd_tipotratamento on tfd_tipotratamento.tf04_i_codigo = ";
    $sSql .= "        tfd_pedidotfd.tf01_i_tipotratamento ";
    $sSql .= "      inner join tfd_situacaotfd on tfd_situacaotfd.tf26_i_codigo = tfd_pedidotfd.tf01_i_situacao ";
    $sSql .= "      inner join tfd_tipotransporte on tfd_tipotransporte.tf27_i_codigo = ";
    $sSql .= "        tfd_pedidotfd.tf01_i_tipotransporte ";

    $sSql .= "      left  join medicos on medicos.sd03_i_codigo = tfd_pedidotfd.tf01_i_profissionalsolic ";
    $sSql .= "      inner join cgs_und on cgs_und.z01_i_cgsund = tfd_pedidotfd.tf01_i_cgsund ";
    $sSql .= "      left  join cgm on cgm.z01_numcgm = medicos.sd03_i_cgm ";
    $sSql .= "      left  join sau_medicosforarede on sau_medicosforarede.s154_i_medico = medicos.sd03_i_codigo ";

    $sSql .= "      left join tfd_agendamentoprestadora  on  tfd_agendamentoprestadora.tf16_i_pedidotfd = ";
    $sSql .= "        tfd_pedidotfd.tf01_i_codigo ";
    $sSql .= "      left join tfd_prestadoracentralagend  on  tfd_prestadoracentralagend.tf10_i_codigo = ";
    $sSql .= "        tfd_agendamentoprestadora.tf16_i_prestcentralagend ";
    $sSql .= "      left join tfd_prestadora  on  tfd_prestadora.tf25_i_codigo = ";
    $sSql .= "        tfd_prestadoracentralagend.tf10_i_prestadora  ";
    $sSql .= "      left join cgm as cgmprest on  cgmprest.z01_numcgm = tfd_prestadora.tf25_i_cgm ";
    $sSql .= "      left join tfd_destino  on  tfd_destino.tf03_i_codigo = tfd_prestadora.tf25_i_destino ";
    $sSql .= "      left join tfd_agendasaida  on  tfd_agendasaida.tf17_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo ";

    $sSql .= "      left join tfd_centralagendamento  on  tfd_centralagendamento.tf09_i_codigo = ";
    $sSql .= "        tfd_prestadoracentralagend.tf10_i_centralagend ";
    $sSql .= "      left join cgm as cgmcentral on  cgmcentral.z01_numcgm = tfd_centralagendamento.tf09_i_numcgm ";
    $sSql .= "      left join tfd_passageiroveiculo  on tfd_passageiroveiculo.tf19_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo";
    $sSql .= "                                      and tfd_passageiroveiculo.tf19_i_valido = 1";
    $sSql .= "      left join tfd_veiculodestino    on tfd_veiculodestino.tf18_i_codigo = tfd_passageiroveiculo.tf19_i_veiculodestino";
    $sSql .= "      left join tfd_situacaopedidotfd on tfd_situacaopedidotfd.tf28_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo";

    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where tfd_pedidotfd.tf01_i_codigo = $iCodigo ";
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
