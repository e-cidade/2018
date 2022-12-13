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

//MODULO: tfd
//CLASSE DA ENTIDADE tfd_agendamentoprestadora
class cl_tfd_agendamentoprestadora {
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
   var $tf16_i_codigo = 0;
   var $tf16_i_prestcentralagend = 0;
   var $tf16_i_pedidotfd = 0;
   var $tf16_c_protocolo = null;
   var $tf16_d_dataagendamento_dia = null;
   var $tf16_d_dataagendamento_mes = null;
   var $tf16_d_dataagendamento_ano = null;
   var $tf16_d_dataagendamento = null;
   var $tf16_c_horaagendamento = null;
   var $tf16_c_local = null;
   var $tf16_i_login = 0;
   var $tf16_c_medico = null;
   var $tf16_c_crmmedico = null;
   var $tf16_c_cnsmedico = null;
   var $tf16_d_datasistema_dia = null;
   var $tf16_d_datasistema_mes = null;
   var $tf16_d_datasistema_ano = null;
   var $tf16_d_datasistema = null;
   var $tf16_c_horasistema = null;
   var $tf16_sequencia = null;
   var $tf16_sala = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 tf16_i_codigo = int4 = Código
                 tf16_i_prestcentralagend = int4 = Prestadora - Central
                 tf16_i_pedidotfd = int4 = Pedido
                 tf16_c_protocolo = varchar(10) = Prot./Agend.
                 tf16_d_dataagendamento = date = Data Prot./Agend.
                 tf16_c_horaagendamento = char(5) = Hora
                 tf16_c_local = varchar(40) = Ref. do Local
                 tf16_i_login = int4 = Login
                 tf16_c_medico = varchar(40) = Médico
                 tf16_c_crmmedico = varchar(10) = CRM do Médico
                 tf16_c_cnsmedico = char(15) = CNS do Médico
                 tf16_d_datasistema = date = Data
                 tf16_c_horasistema = char(5) = Hora
                 tf16_sequencia = varchar(20) = Sequencia
                 tf16_sala = varchar(20) = Sala
                 ";
   //funcao construtor da classe
   function cl_tfd_agendamentoprestadora() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tfd_agendamentoprestadora");
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
       $this->tf16_i_codigo = ($this->tf16_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf16_i_codigo"]:$this->tf16_i_codigo);
       $this->tf16_i_prestcentralagend = ($this->tf16_i_prestcentralagend == ""?@$GLOBALS["HTTP_POST_VARS"]["tf16_i_prestcentralagend"]:$this->tf16_i_prestcentralagend);
       $this->tf16_i_pedidotfd = ($this->tf16_i_pedidotfd == ""?@$GLOBALS["HTTP_POST_VARS"]["tf16_i_pedidotfd"]:$this->tf16_i_pedidotfd);
       $this->tf16_c_protocolo = ($this->tf16_c_protocolo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf16_c_protocolo"]:$this->tf16_c_protocolo);
       if($this->tf16_d_dataagendamento == ""){
         $this->tf16_d_dataagendamento_dia = ($this->tf16_d_dataagendamento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf16_d_dataagendamento_dia"]:$this->tf16_d_dataagendamento_dia);
         $this->tf16_d_dataagendamento_mes = ($this->tf16_d_dataagendamento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf16_d_dataagendamento_mes"]:$this->tf16_d_dataagendamento_mes);
         $this->tf16_d_dataagendamento_ano = ($this->tf16_d_dataagendamento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf16_d_dataagendamento_ano"]:$this->tf16_d_dataagendamento_ano);
         if($this->tf16_d_dataagendamento_dia != ""){
            $this->tf16_d_dataagendamento = $this->tf16_d_dataagendamento_ano."-".$this->tf16_d_dataagendamento_mes."-".$this->tf16_d_dataagendamento_dia;
         }
       }
       $this->tf16_c_horaagendamento = ($this->tf16_c_horaagendamento == ""?@$GLOBALS["HTTP_POST_VARS"]["tf16_c_horaagendamento"]:$this->tf16_c_horaagendamento);
       $this->tf16_c_local = ($this->tf16_c_local == ""?@$GLOBALS["HTTP_POST_VARS"]["tf16_c_local"]:$this->tf16_c_local);
       $this->tf16_i_login = ($this->tf16_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["tf16_i_login"]:$this->tf16_i_login);
       $this->tf16_c_medico = ($this->tf16_c_medico == ""?@$GLOBALS["HTTP_POST_VARS"]["tf16_c_medico"]:$this->tf16_c_medico);
       $this->tf16_c_crmmedico = ($this->tf16_c_crmmedico == ""?@$GLOBALS["HTTP_POST_VARS"]["tf16_c_crmmedico"]:$this->tf16_c_crmmedico);
       $this->tf16_c_cnsmedico = ($this->tf16_c_cnsmedico == ""?@$GLOBALS["HTTP_POST_VARS"]["tf16_c_cnsmedico"]:$this->tf16_c_cnsmedico);
       if($this->tf16_d_datasistema == ""){
         $this->tf16_d_datasistema_dia = ($this->tf16_d_datasistema_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf16_d_datasistema_dia"]:$this->tf16_d_datasistema_dia);
         $this->tf16_d_datasistema_mes = ($this->tf16_d_datasistema_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf16_d_datasistema_mes"]:$this->tf16_d_datasistema_mes);
         $this->tf16_d_datasistema_ano = ($this->tf16_d_datasistema_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf16_d_datasistema_ano"]:$this->tf16_d_datasistema_ano);
         if($this->tf16_d_datasistema_dia != ""){
            $this->tf16_d_datasistema = $this->tf16_d_datasistema_ano."-".$this->tf16_d_datasistema_mes."-".$this->tf16_d_datasistema_dia;
         }
       }
       $this->tf16_c_horasistema = ($this->tf16_c_horasistema == ""?@$GLOBALS["HTTP_POST_VARS"]["tf16_c_horasistema"]:$this->tf16_c_horasistema);
       $this->tf16_sequencia = ($this->tf16_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf16_sequencia"]:$this->tf16_sequencia);
       $this->tf16_sala = ($this->tf16_sala == ""?@$GLOBALS["HTTP_POST_VARS"]["tf16_sala"]:$this->tf16_sala);
     }else{
       $this->tf16_i_codigo = ($this->tf16_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf16_i_codigo"]:$this->tf16_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($tf16_i_codigo){
      $this->atualizacampos();
     if($this->tf16_i_prestcentralagend == null ){
       $this->erro_sql = " Campo Prestadora - Central nao Informado.";
       $this->erro_campo = "tf16_i_prestcentralagend";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf16_i_pedidotfd == null ){
       $this->erro_sql = " Campo Pedido nao Informado.";
       $this->erro_campo = "tf16_i_pedidotfd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf16_d_dataagendamento == null ){
       $this->erro_sql = " Campo Data Prot./Agend. nao Informado.";
       $this->erro_campo = "tf16_d_dataagendamento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf16_c_horaagendamento == null ){
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "tf16_c_horaagendamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf16_i_login == null ){
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "tf16_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf16_d_datasistema == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "tf16_d_datasistema_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf16_c_horasistema == null ){
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "tf16_c_horasistema";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tf16_i_codigo == "" || $tf16_i_codigo == null ){
       $result = db_query("select nextval('tfd_agendamentoprestadora_tf16_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tfd_agendamentoprestadora_tf16_i_codigo_seq do campo: tf16_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->tf16_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from tfd_agendamentoprestadora_tf16_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $tf16_i_codigo)){
         $this->erro_sql = " Campo tf16_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tf16_i_codigo = $tf16_i_codigo;
       }
     }
     if(($this->tf16_i_codigo == null) || ($this->tf16_i_codigo == "") ){
       $this->erro_sql = " Campo tf16_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tfd_agendamentoprestadora(
                                       tf16_i_codigo
                                      ,tf16_i_prestcentralagend
                                      ,tf16_i_pedidotfd
                                      ,tf16_c_protocolo
                                      ,tf16_d_dataagendamento
                                      ,tf16_c_horaagendamento
                                      ,tf16_c_local
                                      ,tf16_i_login
                                      ,tf16_c_medico
                                      ,tf16_c_crmmedico
                                      ,tf16_c_cnsmedico
                                      ,tf16_d_datasistema
                                      ,tf16_c_horasistema
                                      ,tf16_sequencia
                                      ,tf16_sala
                       )
                values (
                                $this->tf16_i_codigo
                               ,$this->tf16_i_prestcentralagend
                               ,$this->tf16_i_pedidotfd
                               ,'$this->tf16_c_protocolo'
                               ,".($this->tf16_d_dataagendamento == "null" || $this->tf16_d_dataagendamento == ""?"null":"'".$this->tf16_d_dataagendamento."'")."
                               ,'$this->tf16_c_horaagendamento'
                               ,'$this->tf16_c_local'
                               ,$this->tf16_i_login
                               ,'$this->tf16_c_medico'
                               ,'$this->tf16_c_crmmedico'
                               ,'$this->tf16_c_cnsmedico'
                               ,".($this->tf16_d_datasistema == "null" || $this->tf16_d_datasistema == ""?"null":"'".$this->tf16_d_datasistema."'")."
                               ,'$this->tf16_c_horasistema'
                               ,'$this->tf16_sequencia'
                               ,'$this->tf16_sala'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tfd_agendamentoprestadora ($this->tf16_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tfd_agendamentoprestadora já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tfd_agendamentoprestadora ($this->tf16_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf16_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->tf16_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16394,'$this->tf16_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2872,16394,'','".AddSlashes(pg_result($resaco,0,'tf16_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2872,16395,'','".AddSlashes(pg_result($resaco,0,'tf16_i_prestcentralagend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2872,16396,'','".AddSlashes(pg_result($resaco,0,'tf16_i_pedidotfd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2872,16397,'','".AddSlashes(pg_result($resaco,0,'tf16_c_protocolo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2872,16398,'','".AddSlashes(pg_result($resaco,0,'tf16_d_dataagendamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2872,16399,'','".AddSlashes(pg_result($resaco,0,'tf16_c_horaagendamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2872,16402,'','".AddSlashes(pg_result($resaco,0,'tf16_c_local'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2872,16403,'','".AddSlashes(pg_result($resaco,0,'tf16_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2872,16685,'','".AddSlashes(pg_result($resaco,0,'tf16_c_medico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2872,16400,'','".AddSlashes(pg_result($resaco,0,'tf16_c_crmmedico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2872,16401,'','".AddSlashes(pg_result($resaco,0,'tf16_c_cnsmedico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2872,16404,'','".AddSlashes(pg_result($resaco,0,'tf16_d_datasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2872,16405,'','".AddSlashes(pg_result($resaco,0,'tf16_c_horasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2872,18196,'','".AddSlashes(pg_result($resaco,0,'tf16_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2872,18195,'','".AddSlashes(pg_result($resaco,0,'tf16_sala'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($tf16_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update tfd_agendamentoprestadora set ";
     $virgula = "";
     if(trim($this->tf16_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf16_i_codigo"])){
       $sql  .= $virgula." tf16_i_codigo = $this->tf16_i_codigo ";
       $virgula = ",";
       if(trim($this->tf16_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "tf16_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf16_i_prestcentralagend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf16_i_prestcentralagend"])){
       $sql  .= $virgula." tf16_i_prestcentralagend = $this->tf16_i_prestcentralagend ";
       $virgula = ",";
       if(trim($this->tf16_i_prestcentralagend) == null ){
         $this->erro_sql = " Campo Prestadora - Central nao Informado.";
         $this->erro_campo = "tf16_i_prestcentralagend";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf16_i_pedidotfd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf16_i_pedidotfd"])){
       $sql  .= $virgula." tf16_i_pedidotfd = $this->tf16_i_pedidotfd ";
       $virgula = ",";
       if(trim($this->tf16_i_pedidotfd) == null ){
         $this->erro_sql = " Campo Pedido nao Informado.";
         $this->erro_campo = "tf16_i_pedidotfd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf16_c_protocolo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf16_c_protocolo"])){
       $sql  .= $virgula." tf16_c_protocolo = '$this->tf16_c_protocolo' ";
       $virgula = ",";
     }
     if(trim($this->tf16_d_dataagendamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf16_d_dataagendamento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf16_d_dataagendamento_dia"] !="") ){
       $sql  .= $virgula." tf16_d_dataagendamento = '$this->tf16_d_dataagendamento' ";
       $virgula = ",";
       if(trim($this->tf16_d_dataagendamento) == null ){
         $this->erro_sql = " Campo Data Prot./Agend. nao Informado.";
         $this->erro_campo = "tf16_d_dataagendamento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf16_d_dataagendamento_dia"])){
         $sql  .= $virgula." tf16_d_dataagendamento = null ";
         $virgula = ",";
         if(trim($this->tf16_d_dataagendamento) == null ){
           $this->erro_sql = " Campo Data Prot./Agend. nao Informado.";
           $this->erro_campo = "tf16_d_dataagendamento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tf16_c_horaagendamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf16_c_horaagendamento"])){
       $sql  .= $virgula." tf16_c_horaagendamento = '$this->tf16_c_horaagendamento' ";
       $virgula = ",";
       if(trim($this->tf16_c_horaagendamento) == null ){
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "tf16_c_horaagendamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf16_c_local)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf16_c_local"])){
       $sql  .= $virgula." tf16_c_local = '$this->tf16_c_local' ";
       $virgula = ",";
     }
     if(trim($this->tf16_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf16_i_login"])){
       $sql  .= $virgula." tf16_i_login = $this->tf16_i_login ";
       $virgula = ",";
       if(trim($this->tf16_i_login) == null ){
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "tf16_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf16_c_medico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf16_c_medico"])){
       $sql  .= $virgula." tf16_c_medico = '$this->tf16_c_medico' ";
       $virgula = ",";
     }
     if(trim($this->tf16_c_crmmedico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf16_c_crmmedico"])){
       $sql  .= $virgula." tf16_c_crmmedico = '$this->tf16_c_crmmedico' ";
       $virgula = ",";
     }
     if(trim($this->tf16_c_cnsmedico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf16_c_cnsmedico"])){
       $sql  .= $virgula." tf16_c_cnsmedico = '$this->tf16_c_cnsmedico' ";
       $virgula = ",";
     }
     if(trim($this->tf16_d_datasistema)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf16_d_datasistema_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf16_d_datasistema_dia"] !="") ){
       $sql  .= $virgula." tf16_d_datasistema = '$this->tf16_d_datasistema' ";
       $virgula = ",";
       if(trim($this->tf16_d_datasistema) == null ){
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "tf16_d_datasistema_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf16_d_datasistema_dia"])){
         $sql  .= $virgula." tf16_d_datasistema = null ";
         $virgula = ",";
         if(trim($this->tf16_d_datasistema) == null ){
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "tf16_d_datasistema_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tf16_c_horasistema)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf16_c_horasistema"])){
       $sql  .= $virgula." tf16_c_horasistema = '$this->tf16_c_horasistema' ";
       $virgula = ",";
       if(trim($this->tf16_c_horasistema) == null ){
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "tf16_c_horasistema";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf16_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf16_sequencia"])){
       $sql  .= $virgula." tf16_sequencia = '$this->tf16_sequencia' ";
       $virgula = ",";
     }
     if(trim($this->tf16_sala)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf16_sala"])){
       $sql  .= $virgula." tf16_sala = '$this->tf16_sala' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($tf16_i_codigo!=null){
       $sql .= " tf16_i_codigo = $this->tf16_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->tf16_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16394,'$this->tf16_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf16_i_codigo"]) || $this->tf16_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2872,16394,'".AddSlashes(pg_result($resaco,$conresaco,'tf16_i_codigo'))."','$this->tf16_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf16_i_prestcentralagend"]) || $this->tf16_i_prestcentralagend != "")
           $resac = db_query("insert into db_acount values($acount,2872,16395,'".AddSlashes(pg_result($resaco,$conresaco,'tf16_i_prestcentralagend'))."','$this->tf16_i_prestcentralagend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf16_i_pedidotfd"]) || $this->tf16_i_pedidotfd != "")
           $resac = db_query("insert into db_acount values($acount,2872,16396,'".AddSlashes(pg_result($resaco,$conresaco,'tf16_i_pedidotfd'))."','$this->tf16_i_pedidotfd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf16_c_protocolo"]) || $this->tf16_c_protocolo != "")
           $resac = db_query("insert into db_acount values($acount,2872,16397,'".AddSlashes(pg_result($resaco,$conresaco,'tf16_c_protocolo'))."','$this->tf16_c_protocolo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf16_d_dataagendamento"]) || $this->tf16_d_dataagendamento != "")
           $resac = db_query("insert into db_acount values($acount,2872,16398,'".AddSlashes(pg_result($resaco,$conresaco,'tf16_d_dataagendamento'))."','$this->tf16_d_dataagendamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf16_c_horaagendamento"]) || $this->tf16_c_horaagendamento != "")
           $resac = db_query("insert into db_acount values($acount,2872,16399,'".AddSlashes(pg_result($resaco,$conresaco,'tf16_c_horaagendamento'))."','$this->tf16_c_horaagendamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf16_c_local"]) || $this->tf16_c_local != "")
           $resac = db_query("insert into db_acount values($acount,2872,16402,'".AddSlashes(pg_result($resaco,$conresaco,'tf16_c_local'))."','$this->tf16_c_local',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf16_i_login"]) || $this->tf16_i_login != "")
           $resac = db_query("insert into db_acount values($acount,2872,16403,'".AddSlashes(pg_result($resaco,$conresaco,'tf16_i_login'))."','$this->tf16_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf16_c_medico"]) || $this->tf16_c_medico != "")
           $resac = db_query("insert into db_acount values($acount,2872,16685,'".AddSlashes(pg_result($resaco,$conresaco,'tf16_c_medico'))."','$this->tf16_c_medico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf16_c_crmmedico"]) || $this->tf16_c_crmmedico != "")
           $resac = db_query("insert into db_acount values($acount,2872,16400,'".AddSlashes(pg_result($resaco,$conresaco,'tf16_c_crmmedico'))."','$this->tf16_c_crmmedico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf16_c_cnsmedico"]) || $this->tf16_c_cnsmedico != "")
           $resac = db_query("insert into db_acount values($acount,2872,16401,'".AddSlashes(pg_result($resaco,$conresaco,'tf16_c_cnsmedico'))."','$this->tf16_c_cnsmedico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf16_d_datasistema"]) || $this->tf16_d_datasistema != "")
           $resac = db_query("insert into db_acount values($acount,2872,16404,'".AddSlashes(pg_result($resaco,$conresaco,'tf16_d_datasistema'))."','$this->tf16_d_datasistema',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf16_c_horasistema"]) || $this->tf16_c_horasistema != "")
           $resac = db_query("insert into db_acount values($acount,2872,16405,'".AddSlashes(pg_result($resaco,$conresaco,'tf16_c_horasistema'))."','$this->tf16_c_horasistema',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf16_sequencia"]) || $this->tf16_sequencia != "")
           $resac = db_query("insert into db_acount values($acount,2872,18196,'".AddSlashes(pg_result($resaco,$conresaco,'tf16_sequencia'))."','$this->tf16_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf16_sala"]) || $this->tf16_sala != "")
           $resac = db_query("insert into db_acount values($acount,2872,18195,'".AddSlashes(pg_result($resaco,$conresaco,'tf16_sala'))."','$this->tf16_sala',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_agendamentoprestadora nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf16_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_agendamentoprestadora nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf16_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf16_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($tf16_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($tf16_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16394,'$tf16_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2872,16394,'','".AddSlashes(pg_result($resaco,$iresaco,'tf16_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2872,16395,'','".AddSlashes(pg_result($resaco,$iresaco,'tf16_i_prestcentralagend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2872,16396,'','".AddSlashes(pg_result($resaco,$iresaco,'tf16_i_pedidotfd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2872,16397,'','".AddSlashes(pg_result($resaco,$iresaco,'tf16_c_protocolo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2872,16398,'','".AddSlashes(pg_result($resaco,$iresaco,'tf16_d_dataagendamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2872,16399,'','".AddSlashes(pg_result($resaco,$iresaco,'tf16_c_horaagendamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2872,16402,'','".AddSlashes(pg_result($resaco,$iresaco,'tf16_c_local'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2872,16403,'','".AddSlashes(pg_result($resaco,$iresaco,'tf16_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2872,16685,'','".AddSlashes(pg_result($resaco,$iresaco,'tf16_c_medico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2872,16400,'','".AddSlashes(pg_result($resaco,$iresaco,'tf16_c_crmmedico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2872,16401,'','".AddSlashes(pg_result($resaco,$iresaco,'tf16_c_cnsmedico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2872,16404,'','".AddSlashes(pg_result($resaco,$iresaco,'tf16_d_datasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2872,16405,'','".AddSlashes(pg_result($resaco,$iresaco,'tf16_c_horasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2872,18196,'','".AddSlashes(pg_result($resaco,$iresaco,'tf16_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2872,18195,'','".AddSlashes(pg_result($resaco,$iresaco,'tf16_sala'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tfd_agendamentoprestadora
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tf16_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tf16_i_codigo = $tf16_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_agendamentoprestadora nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tf16_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_agendamentoprestadora nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tf16_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tf16_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tfd_agendamentoprestadora";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $tf16_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from tfd_agendamentoprestadora ";
     $sql .= "      inner join tfd_pedidotfd  on  tfd_pedidotfd.tf01_i_codigo = tfd_agendamentoprestadora.tf16_i_pedidotfd";
     $sql .= "      inner join tfd_prestadoracentralagend  on  tfd_prestadoracentralagend.tf10_i_codigo = tfd_agendamentoprestadora.tf16_i_prestcentralagend";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tfd_pedidotfd.tf01_i_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = tfd_pedidotfd.tf01_i_depto";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = tfd_pedidotfd.tf01_i_rhcbo";
     $sql .= "      inner join tfd_tipotratamento  on  tfd_tipotratamento.tf04_i_codigo = tfd_pedidotfd.tf01_i_tipotratamento";
     $sql .= "      inner join tfd_situacaotfd  on  tfd_situacaotfd.tf26_i_codigo = tfd_pedidotfd.tf01_i_situacao";
     $sql .= "      inner join tfd_tipotransporte  on  tfd_tipotransporte.tf27_i_codigo = tfd_pedidotfd.tf01_i_tipotransporte";
     $sql .= "      left  join medicos  on  medicos.sd03_i_codigo = tfd_pedidotfd.tf01_i_profissionalsolic";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = tfd_pedidotfd.tf01_i_cgsund";
     $sql .= "      inner join tfd_centralagendamento  on  tfd_centralagendamento.tf09_i_codigo = tfd_prestadoracentralagend.tf10_i_centralagend";
     $sql .= "      inner join tfd_prestadora  as a on   a.tf25_i_codigo = tfd_prestadoracentralagend.tf10_i_prestadora";
     $sql2 = "";
     if($dbwhere==""){
       if($tf16_i_codigo!=null ){
         $sql2 .= " where tfd_agendamentoprestadora.tf16_i_codigo = $tf16_i_codigo ";
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
   function sql_query_file ( $tf16_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from tfd_agendamentoprestadora ";
     $sql2 = "";
     if($dbwhere==""){
       if($tf16_i_codigo!=null ){
         $sql2 .= " where tfd_agendamentoprestadora.tf16_i_codigo = $tf16_i_codigo ";
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
   // função que faz ligação com a tabela tfd_destino
   function sql_query_destino ( $tf16_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from tfd_agendamentoprestadora ";
     $sql .= "      inner join tfd_pedidotfd  on  tfd_pedidotfd.tf01_i_codigo = tfd_agendamentoprestadora.tf16_i_pedidotfd";
     $sql .= "      inner join tfd_prestadoracentralagend  on  tfd_prestadoracentralagend.tf10_i_codigo = tfd_agendamentoprestadora.tf16_i_prestcentralagend";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = tfd_pedidotfd.tf01_i_cgsund";
     $sql .= "      inner join tfd_centralagendamento  on  tfd_centralagendamento.tf09_i_codigo = tfd_prestadoracentralagend.tf10_i_centralagend";
     $sql .= "      inner join tfd_prestadora  on   tfd_prestadora.tf25_i_codigo = tfd_prestadoracentralagend.tf10_i_prestadora";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = tfd_prestadora.tf25_i_cgm";
     $sql .= "      inner join tfd_destino  on   tfd_destino.tf03_i_codigo = tfd_prestadora.tf25_i_destino";
     $sql2 = "";
     if($dbwhere==""){
       if($tf16_i_codigo!=null ){
         $sql2 .= " where tfd_agendamentoprestadora.tf16_i_codigo = $tf16_i_codigo ";
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
   * [sql_query_prestadora description]
   * @param  [type] $ed128_codigo [description]
   * @param  string $campos       [description]
   * @param  [type] $ordem        [description]
   * @param  string $dbwhere      [description]
   * @return [type]               [description]
   */
  public function sql_query_prestadora( $ed128_codigo = null, $campos = "*", $ordem = null, $dbwhere = "" ) {

    $sql  = "select {$campos} ";
    $sql .= "  from tfd_agendamentoprestadora ";
    $sql .= "       inner join tfd_pedidotfd               on tfd_pedidotfd.tf01_i_codigo = tfd_agendamentoprestadora.tf16_i_pedidotfd";
    $sql .= "       inner join tfd_prestadoracentralagend  on tfd_prestadoracentralagend.tf10_i_codigo = tfd_agendamentoprestadora.tf16_i_prestcentralagend";
    $sql .= "       inner join cgs_und                     on cgs_und.z01_i_cgsund = tfd_pedidotfd.tf01_i_cgsund";
    $sql .= "       inner join tfd_centralagendamento      on tfd_centralagendamento.tf09_i_codigo = tfd_prestadoracentralagend.tf10_i_centralagend";
    $sql .= "       inner join tfd_prestadora              on tfd_prestadora.tf25_i_codigo = tfd_prestadoracentralagend.tf10_i_prestadora";
    $sql .= "       inner join cgm as cgm_prestadora       on cgm_prestadora.z01_numcgm    = tfd_prestadora.tf25_i_cgm";
    $sql .= "       inner join cgm as cgm_central          on cgm_central.z01_numcgm       = tfd_centralagendamento.tf09_i_numcgm ";
    $sql .= "       inner join tfd_destino                 on tfd_destino.tf03_i_codigo    = tfd_prestadora.tf25_i_destino";
    $sql2 = "";

    if (empty($dbwhere)) {

      if (!empty($ed128_codigo)){
        $sql2 .= " where tipohoratrabalho.ed128_codigo = $ed128_codigo ";
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
}
?>