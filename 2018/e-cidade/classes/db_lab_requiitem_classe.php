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

//MODULO: laboratorio
//CLASSE DA ENTIDADE lab_requiitem
class cl_lab_requiitem {
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
   var $la21_i_codigo = 0;
   var $la21_i_requisicao = 0;
   var $la21_d_entrega_dia = null;
   var $la21_d_entrega_mes = null;
   var $la21_d_entrega_ano = null;
   var $la21_d_entrega = null;
   var $la21_d_data_dia = null;
   var $la21_d_data_mes = null;
   var $la21_d_data_ano = null;
   var $la21_d_data = null;
   var $la21_c_hora = null;
   var $la21_i_setorexame = 0;
   var $la21_i_emergencia = 0;
   var $la21_c_situacao = null;
   var $la21_i_quantidade = 0;
   var $la21_observacao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 la21_i_codigo = int4 = Código 
                 la21_i_requisicao = int4 = Requisição 
                 la21_d_entrega = date = Entrega 
                 la21_d_data = date = Coleta 
                 la21_c_hora = char(5) = Hora 
                 la21_i_setorexame = int4 = Exame 
                 la21_i_emergencia = int4 = Emergência 
                 la21_c_situacao = char(50) = Situação 
                 la21_i_quantidade = int4 = Quantidade 
                 la21_observacao = text = Observação 
                 ";
   //funcao construtor da classe
   function cl_lab_requiitem() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_requiitem");
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
       $this->la21_i_codigo = ($this->la21_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la21_i_codigo"]:$this->la21_i_codigo);
       $this->la21_i_requisicao = ($this->la21_i_requisicao == ""?@$GLOBALS["HTTP_POST_VARS"]["la21_i_requisicao"]:$this->la21_i_requisicao);
       if($this->la21_d_entrega == ""){
         $this->la21_d_entrega_dia = ($this->la21_d_entrega_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la21_d_entrega_dia"]:$this->la21_d_entrega_dia);
         $this->la21_d_entrega_mes = ($this->la21_d_entrega_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la21_d_entrega_mes"]:$this->la21_d_entrega_mes);
         $this->la21_d_entrega_ano = ($this->la21_d_entrega_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la21_d_entrega_ano"]:$this->la21_d_entrega_ano);
         if($this->la21_d_entrega_dia != ""){
            $this->la21_d_entrega = $this->la21_d_entrega_ano."-".$this->la21_d_entrega_mes."-".$this->la21_d_entrega_dia;
         }
       }
       if($this->la21_d_data == ""){
         $this->la21_d_data_dia = ($this->la21_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la21_d_data_dia"]:$this->la21_d_data_dia);
         $this->la21_d_data_mes = ($this->la21_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la21_d_data_mes"]:$this->la21_d_data_mes);
         $this->la21_d_data_ano = ($this->la21_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la21_d_data_ano"]:$this->la21_d_data_ano);
         if($this->la21_d_data_dia != ""){
            $this->la21_d_data = $this->la21_d_data_ano."-".$this->la21_d_data_mes."-".$this->la21_d_data_dia;
         }
       }
       $this->la21_c_hora = ($this->la21_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["la21_c_hora"]:$this->la21_c_hora);
       $this->la21_i_setorexame = ($this->la21_i_setorexame == ""?@$GLOBALS["HTTP_POST_VARS"]["la21_i_setorexame"]:$this->la21_i_setorexame);
       $this->la21_i_emergencia = ($this->la21_i_emergencia == ""?@$GLOBALS["HTTP_POST_VARS"]["la21_i_emergencia"]:$this->la21_i_emergencia);
       $this->la21_c_situacao = ($this->la21_c_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["la21_c_situacao"]:$this->la21_c_situacao);
       $this->la21_i_quantidade = ($this->la21_i_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["la21_i_quantidade"]:$this->la21_i_quantidade);
       $this->la21_observacao = ($this->la21_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["la21_observacao"]:$this->la21_observacao);
     }else{
       $this->la21_i_codigo = ($this->la21_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la21_i_codigo"]:$this->la21_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($la21_i_codigo){
      $this->atualizacampos();
     if($this->la21_i_requisicao == null ){
       $this->erro_sql = " Campo Requisição não informado.";
       $this->erro_campo = "la21_i_requisicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la21_d_entrega == null ){
       $this->erro_sql = " Campo Entrega não informado.";
       $this->erro_campo = "la21_d_entrega_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la21_d_data == null ){
       $this->erro_sql = " Campo Coleta não informado.";
       $this->erro_campo = "la21_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la21_c_hora == null ){
       $this->erro_sql = " Campo Hora não informado.";
       $this->erro_campo = "la21_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la21_i_setorexame == null ){
       $this->erro_sql = " Campo Exame não informado.";
       $this->erro_campo = "la21_i_setorexame";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la21_i_emergencia == null ){
       $this->erro_sql = " Campo Emergência não informado.";
       $this->erro_campo = "la21_i_emergencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la21_c_situacao == null ){
       $this->erro_sql = " Campo Situação não informado.";
       $this->erro_campo = "la21_c_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la21_i_quantidade == null ){
       $this->erro_sql = " Campo Quantidade não informado.";
       $this->erro_campo = "la21_i_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($la21_i_codigo == "" || $la21_i_codigo == null ){
       $result = db_query("select nextval('lab_requiitem_la21_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_requiitem_la21_i_codigo_seq do campo: la21_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->la21_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from lab_requiitem_la21_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la21_i_codigo)){
         $this->erro_sql = " Campo la21_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la21_i_codigo = $la21_i_codigo;
       }
     }
     if(($this->la21_i_codigo == null) || ($this->la21_i_codigo == "") ){
       $this->erro_sql = " Campo la21_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_requiitem(
                                       la21_i_codigo 
                                      ,la21_i_requisicao 
                                      ,la21_d_entrega 
                                      ,la21_d_data 
                                      ,la21_c_hora 
                                      ,la21_i_setorexame 
                                      ,la21_i_emergencia 
                                      ,la21_c_situacao 
                                      ,la21_i_quantidade 
                                      ,la21_observacao 
                       )
                values (
                                $this->la21_i_codigo 
                               ,$this->la21_i_requisicao 
                               ,".($this->la21_d_entrega == "null" || $this->la21_d_entrega == ""?"null":"'".$this->la21_d_entrega."'")." 
                               ,".($this->la21_d_data == "null" || $this->la21_d_data == ""?"null":"'".$this->la21_d_data."'")." 
                               ,'$this->la21_c_hora' 
                               ,$this->la21_i_setorexame 
                               ,$this->la21_i_emergencia 
                               ,'$this->la21_c_situacao' 
                               ,$this->la21_i_quantidade 
                               ,'$this->la21_observacao' 
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "lab_requiitem ($this->la21_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "lab_requiitem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "lab_requiitem ($this->la21_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la21_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la21_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15787,'$this->la21_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,2771,15787,'','".AddSlashes(pg_result($resaco,0,'la21_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2771,15788,'','".AddSlashes(pg_result($resaco,0,'la21_i_requisicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2771,15789,'','".AddSlashes(pg_result($resaco,0,'la21_d_entrega'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2771,15790,'','".AddSlashes(pg_result($resaco,0,'la21_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2771,15791,'','".AddSlashes(pg_result($resaco,0,'la21_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2771,16040,'','".AddSlashes(pg_result($resaco,0,'la21_i_setorexame'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2771,15838,'','".AddSlashes(pg_result($resaco,0,'la21_i_emergencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2771,16574,'','".AddSlashes(pg_result($resaco,0,'la21_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2771,17991,'','".AddSlashes(pg_result($resaco,0,'la21_i_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2771,20635,'','".AddSlashes(pg_result($resaco,0,'la21_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($la21_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update lab_requiitem set ";
     $virgula = "";
     if(trim($this->la21_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la21_i_codigo"])){
       $sql  .= $virgula." la21_i_codigo = $this->la21_i_codigo ";
       $virgula = ",";
       if(trim($this->la21_i_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "la21_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la21_i_requisicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la21_i_requisicao"])){
       $sql  .= $virgula." la21_i_requisicao = $this->la21_i_requisicao ";
       $virgula = ",";
       if(trim($this->la21_i_requisicao) == null ){
         $this->erro_sql = " Campo Requisição não informado.";
         $this->erro_campo = "la21_i_requisicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la21_d_entrega)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la21_d_entrega_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la21_d_entrega_dia"] !="") ){
       $sql  .= $virgula." la21_d_entrega = '$this->la21_d_entrega' ";
       $virgula = ",";
       if(trim($this->la21_d_entrega) == null ){
         $this->erro_sql = " Campo Entrega não informado.";
         $this->erro_campo = "la21_d_entrega_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["la21_d_entrega_dia"])){
         $sql  .= $virgula." la21_d_entrega = null ";
         $virgula = ",";
         if(trim($this->la21_d_entrega) == null ){
           $this->erro_sql = " Campo Entrega não informado.";
           $this->erro_campo = "la21_d_entrega_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->la21_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la21_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la21_d_data_dia"] !="") ){
       $sql  .= $virgula." la21_d_data = '$this->la21_d_data' ";
       $virgula = ",";
       if(trim($this->la21_d_data) == null ){
         $this->erro_sql = " Campo Coleta não informado.";
         $this->erro_campo = "la21_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["la21_d_data_dia"])){
         $sql  .= $virgula." la21_d_data = null ";
         $virgula = ",";
         if(trim($this->la21_d_data) == null ){
           $this->erro_sql = " Campo Coleta não informado.";
           $this->erro_campo = "la21_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->la21_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la21_c_hora"])){
       $sql  .= $virgula." la21_c_hora = '$this->la21_c_hora' ";
       $virgula = ",";
       if(trim($this->la21_c_hora) == null ){
         $this->erro_sql = " Campo Hora não informado.";
         $this->erro_campo = "la21_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la21_i_setorexame)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la21_i_setorexame"])){
       $sql  .= $virgula." la21_i_setorexame = $this->la21_i_setorexame ";
       $virgula = ",";
       if(trim($this->la21_i_setorexame) == null ){
         $this->erro_sql = " Campo Exame não informado.";
         $this->erro_campo = "la21_i_setorexame";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la21_i_emergencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la21_i_emergencia"])){
       $sql  .= $virgula." la21_i_emergencia = $this->la21_i_emergencia ";
       $virgula = ",";
       if(trim($this->la21_i_emergencia) == null ){
         $this->erro_sql = " Campo Emergência não informado.";
         $this->erro_campo = "la21_i_emergencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la21_c_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la21_c_situacao"])){
       $sql  .= $virgula." la21_c_situacao = '$this->la21_c_situacao' ";
       $virgula = ",";
       if(trim($this->la21_c_situacao) == null ){
         $this->erro_sql = " Campo Situação não informado.";
         $this->erro_campo = "la21_c_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la21_i_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la21_i_quantidade"])){
       $sql  .= $virgula." la21_i_quantidade = $this->la21_i_quantidade ";
       $virgula = ",";
       if(trim($this->la21_i_quantidade) == null ){
         $this->erro_sql = " Campo Quantidade não informado.";
         $this->erro_campo = "la21_i_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la21_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la21_observacao"])){
       $sql  .= $virgula." la21_observacao = '$this->la21_observacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($la21_i_codigo!=null){
       $sql .= " la21_i_codigo = $this->la21_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la21_i_codigo));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,15787,'$this->la21_i_codigo','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la21_i_codigo"]) || $this->la21_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2771,15787,'".AddSlashes(pg_result($resaco,$conresaco,'la21_i_codigo'))."','$this->la21_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la21_i_requisicao"]) || $this->la21_i_requisicao != "")
             $resac = db_query("insert into db_acount values($acount,2771,15788,'".AddSlashes(pg_result($resaco,$conresaco,'la21_i_requisicao'))."','$this->la21_i_requisicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la21_d_entrega"]) || $this->la21_d_entrega != "")
             $resac = db_query("insert into db_acount values($acount,2771,15789,'".AddSlashes(pg_result($resaco,$conresaco,'la21_d_entrega'))."','$this->la21_d_entrega',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la21_d_data"]) || $this->la21_d_data != "")
             $resac = db_query("insert into db_acount values($acount,2771,15790,'".AddSlashes(pg_result($resaco,$conresaco,'la21_d_data'))."','$this->la21_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la21_c_hora"]) || $this->la21_c_hora != "")
             $resac = db_query("insert into db_acount values($acount,2771,15791,'".AddSlashes(pg_result($resaco,$conresaco,'la21_c_hora'))."','$this->la21_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la21_i_setorexame"]) || $this->la21_i_setorexame != "")
             $resac = db_query("insert into db_acount values($acount,2771,16040,'".AddSlashes(pg_result($resaco,$conresaco,'la21_i_setorexame'))."','$this->la21_i_setorexame',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la21_i_emergencia"]) || $this->la21_i_emergencia != "")
             $resac = db_query("insert into db_acount values($acount,2771,15838,'".AddSlashes(pg_result($resaco,$conresaco,'la21_i_emergencia'))."','$this->la21_i_emergencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la21_c_situacao"]) || $this->la21_c_situacao != "")
             $resac = db_query("insert into db_acount values($acount,2771,16574,'".AddSlashes(pg_result($resaco,$conresaco,'la21_c_situacao'))."','$this->la21_c_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la21_i_quantidade"]) || $this->la21_i_quantidade != "")
             $resac = db_query("insert into db_acount values($acount,2771,17991,'".AddSlashes(pg_result($resaco,$conresaco,'la21_i_quantidade'))."','$this->la21_i_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la21_observacao"]) || $this->la21_observacao != "")
             $resac = db_query("insert into db_acount values($acount,2771,20635,'".AddSlashes(pg_result($resaco,$conresaco,'la21_observacao'))."','$this->la21_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_requiitem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la21_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lab_requiitem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la21_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la21_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($la21_i_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($la21_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,15787,'$la21_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,2771,15787,'','".AddSlashes(pg_result($resaco,$iresaco,'la21_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2771,15788,'','".AddSlashes(pg_result($resaco,$iresaco,'la21_i_requisicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2771,15789,'','".AddSlashes(pg_result($resaco,$iresaco,'la21_d_entrega'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2771,15790,'','".AddSlashes(pg_result($resaco,$iresaco,'la21_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2771,15791,'','".AddSlashes(pg_result($resaco,$iresaco,'la21_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2771,16040,'','".AddSlashes(pg_result($resaco,$iresaco,'la21_i_setorexame'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2771,15838,'','".AddSlashes(pg_result($resaco,$iresaco,'la21_i_emergencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2771,16574,'','".AddSlashes(pg_result($resaco,$iresaco,'la21_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2771,17991,'','".AddSlashes(pg_result($resaco,$iresaco,'la21_i_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2771,20635,'','".AddSlashes(pg_result($resaco,$iresaco,'la21_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from lab_requiitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($la21_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " la21_i_codigo = $la21_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_requiitem nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la21_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lab_requiitem nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la21_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la21_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_requiitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $la21_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from lab_requiitem ";
     $sql .= "      inner join lab_setorexame  on  lab_setorexame.la09_i_codigo = lab_requiitem.la21_i_setorexame";
     $sql .= "      inner join lab_requisicao  on  lab_requisicao.la22_i_codigo = lab_requiitem.la21_i_requisicao";
     $sql .= "      inner join lab_exame  on  lab_exame.la08_i_codigo = lab_setorexame.la09_i_exame";
     $sql .= "      inner join lab_labsetor  on  lab_labsetor.la24_i_codigo = lab_setorexame.la09_i_labsetor";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = lab_requisicao.la22_i_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = lab_requisicao.la22_i_departamento";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = lab_requisicao.la22_i_cgs";
     $sql2 = "";
     if($dbwhere==""){
       if($la21_i_codigo!=null ){
         $sql2 .= " where lab_requiitem.la21_i_codigo = $la21_i_codigo ";
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
   function sql_query_file ( $la21_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from lab_requiitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($la21_i_codigo!=null ){
         $sql2 .= " where lab_requiitem.la21_i_codigo = $la21_i_codigo ";
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
   /* Função SQL utilizada na consulta geral da saúde */
   function sql_query_consulta_geral ( $la21_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from lab_requiitem ";
     $sql .= "   inner join lab_setorexame  on  lab_setorexame.la09_i_codigo = lab_requiitem.la21_i_setorexame";
     $sql .= "   inner join lab_requisicao  on  lab_requisicao.la22_i_codigo = lab_requiitem.la21_i_requisicao";
     $sql .= "   inner join lab_exame  on  lab_exame.la08_i_codigo = lab_setorexame.la09_i_exame";
     $sql .= "   inner join lab_labsetor  on  lab_labsetor.la24_i_codigo = lab_setorexame.la09_i_labsetor";
     $sql .= "   inner join lab_laboratorio  on lab_laboratorio.la02_i_codigo = lab_labsetor.la24_i_laboratorio";
     $sql .= "   inner join db_usuarios  on  db_usuarios.id_usuario = lab_requisicao.la22_i_usuario";
     $sql .= "   inner join db_depart  on  db_depart.coddepto = lab_requisicao.la22_i_departamento";
     $sql .= "   inner join cgs_und  on  cgs_und.z01_i_cgsund = lab_requisicao.la22_i_cgs";
     $sql .= "   left join lab_coletaitem  on  lab_coletaitem.la32_i_requiitem = lab_requiitem.la21_i_codigo";
     $sql .= "   left join lab_entrega  on  lab_entrega.la31_i_requiitem = lab_requiitem.la21_i_codigo";
     $sql .= "   left  join lab_exameproced  on  lab_exameproced.la53_i_exame = lab_exame.la08_i_codigo";
     $sql .= "   and lab_exameproced.la53_i_ativo = 1 ";
     $sql .= "   left  join sau_procedimento  on  sau_procedimento.sd63_i_codigo = lab_exameproced.la53_i_procedimento";
     $sql2 = "";
     if($dbwhere==""){
       if($la21_i_codigo!=null ){
         $sql2 .= " where lab_requiitem.la21_i_codigo = $la21_i_codigo ";
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
   // funcao do sql + lab_laboratorio
   function sql_query2 ( $la21_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from lab_requiitem ";
     $sql .= "      inner join lab_setorexame  on  lab_setorexame.la09_i_codigo = lab_requiitem.la21_i_setorexame";
     $sql .= "      inner join lab_requisicao  on  lab_requisicao.la22_i_codigo = lab_requiitem.la21_i_requisicao";
     $sql .= "      inner join lab_exame  on  lab_exame.la08_i_codigo = lab_setorexame.la09_i_exame";
     $sql .= "      inner join lab_labsetor  on  lab_labsetor.la24_i_codigo = lab_setorexame.la09_i_labsetor";
     $sql .= "      inner join lab_laboratorio on la02_i_codigo = lab_labsetor.la24_i_laboratorio";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = lab_requisicao.la22_i_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = lab_requisicao.la22_i_departamento";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = lab_requisicao.la22_i_cgs";
     $sql2 = "";
     if($dbwhere==""){
       if($la21_i_codigo!=null ){
         $sql2 .= " where lab_requiitem.la21_i_codigo = $la21_i_codigo ";
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
   function sql_query_controle ( $la21_i_codigo=null,$campos="*",$ordem=null,$dbwhere="", $lProcedAtivo = true){
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
     $sql .= " from lab_requiitem ";
     $sql .= "      inner join lab_setorexame  on  lab_setorexame.la09_i_codigo = lab_requiitem.la21_i_setorexame";
     $sql .= "      inner join lab_requisicao  on  lab_requisicao.la22_i_codigo = lab_requiitem.la21_i_requisicao";
     $sql .= "      inner join lab_exame  on  lab_exame.la08_i_codigo = lab_setorexame.la09_i_exame";
     $sql .= "      left  join lab_exameproced  on  lab_exameproced.la53_i_exame = lab_exame.la08_i_codigo";
     if ($lProcedAtivo) {
       $sql .= " and lab_exameproced.la53_i_ativo = 1";
     }
     $sql .= "      left  join sau_procedimento  on  sau_procedimento.sd63_i_codigo = lab_exameproced.la53_i_procedimento";
     $sql .= "      inner join lab_labsetor  on  lab_labsetor.la24_i_codigo = lab_setorexame.la09_i_labsetor";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = lab_requisicao.la22_i_departamento";
     $sql2 = "";
     if($dbwhere==""){
       if($la21_i_codigo!=null ){
         $sql2 .= " where lab_requiitem.la21_i_codigo = $la21_i_codigo ";
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
   function sql_query_nova ( $la21_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from lab_requiitem ";
     $sql .= "      inner join lab_setorexame  on  lab_setorexame.la09_i_codigo = lab_requiitem.la21_i_setorexame";
     $sql .= "      inner join lab_requisicao  on  lab_requisicao.la22_i_codigo = lab_requiitem.la21_i_requisicao";
     $sql .= "      inner join lab_exame  on  lab_exame.la08_i_codigo = lab_setorexame.la09_i_exame";
     $sql .= "      left  join lab_exameatributo  on  lab_exameatributo.la42_i_exame = lab_exame.la08_i_codigo";
     $sql .= "      inner join lab_labsetor  on  lab_labsetor.la24_i_codigo = lab_setorexame.la09_i_labsetor";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = lab_requisicao.la22_i_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = lab_requisicao.la22_i_departamento";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = lab_requisicao.la22_i_cgs";
     $sql .= "      left join lab_conferencia  on  lab_conferencia.la47_i_requiitem = lab_requiitem.la21_i_codigo";
     $sql .= "      left join sau_procedimento  on  sau_procedimento.sd63_i_codigo = lab_conferencia.la47_i_procedimento";
     $sql .= "      left join lab_emissao  on  lab_emissao.la34_i_requiitem = lab_requiitem.la21_i_codigo";
     $sql .= "      left join lab_entrega  on  lab_entrega.la31_i_requiitem = lab_requiitem.la21_i_codigo";
     $sql .= "      left join lab_coletaitem  on  lab_coletaitem.la32_i_requiitem = lab_requiitem.la21_i_codigo";
     $sql .= "      left join lab_resultado  on  lab_resultado.la52_i_requiitem = lab_requiitem.la21_i_codigo";
     $sql .= "      inner join lab_laboratorio  on  lab_laboratorio.la02_i_codigo = lab_labsetor.la24_i_laboratorio";
     $sql .= "      left  join lab_examematerial  on  lab_examematerial.la19_i_exame = lab_exame.la08_i_codigo";
     $sql .= "      left  join lab_materialcoleta  on  lab_materialcoleta.la15_i_codigo = lab_examematerial.la19_i_materialcoleta";
     $sql .= "      inner join lab_setor  on  lab_setor.la23_i_codigo = lab_labsetor.la24_i_setor";
     $sql .= "      inner join  lab_labresp on lab_labresp.la06_i_codigo = lab_labsetor.la24_i_resp";
     $sql .= "      inner join  cgm on cgm.z01_numcgm = lab_labresp.la06_i_cgm";
     $sql2 = "";
     if($dbwhere==""){
       if($la21_i_codigo!=null ){
         $sql2 .= " where lab_requiitem.la21_i_codigo = $la21_i_codigo ";
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

  public function sql_query_requisicao_exames($sCampos = "*", $sWhere = null) {

    $sSql  = "select {$sCampos}";
    $sSql .= "  from lab_requiitem";
    $sSql .= "       inner join lab_setorexame  on lab_setorexame.la09_i_codigo = lab_requiitem.la21_i_setorexame";
    $sSql .= "       inner join lab_exame       on lab_exame.la08_i_codigo = lab_setorexame.la09_i_exame";
    $sSql .= "       inner join lab_exameproced on lab_exameproced.la53_i_exame = lab_exame.la08_i_codigo";
    $sSql .= "       inner join sau_procedimento on sau_procedimento.sd63_i_codigo = lab_exameproced.la53_i_procedimento";
    $sSql .= "       inner join lab_labsetor    on lab_labsetor.la24_i_codigo = lab_setorexame.la09_i_labsetor";
    $sSql .= "       inner join lab_laboratorio on lab_laboratorio.la02_i_codigo = lab_labsetor.la24_i_laboratorio";
    $sSql .= "       inner join lab_controlefisicofinanceiro on lab_controlefisicofinanceiro.la56_i_laboratorio = lab_laboratorio.la02_i_codigo";
    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }
    return $sSql;
  }
  

  public function sql_query_requisicao_exames_autorizados($sCampos = "*", $sWhere = null) {
  
  	$sSql  = "select {$sCampos}";
  	$sSql .= "  from lab_requiitem";
  	$sSql .= "       inner join lab_setorexame  on lab_setorexame.la09_i_codigo = lab_requiitem.la21_i_setorexame";
  	$sSql .= "       inner join lab_exame       on lab_exame.la08_i_codigo = lab_setorexame.la09_i_exame";
  	$sSql .= "       inner join lab_exameproced on lab_exameproced.la53_i_exame = lab_exame.la08_i_codigo";
  	$sSql .= "       inner join sau_procedimento on sau_procedimento.sd63_i_codigo = lab_exameproced.la53_i_procedimento";
  	$sSql .= "       inner join lab_labsetor    on lab_labsetor.la24_i_codigo = lab_setorexame.la09_i_labsetor";
  	$sSql .= "       inner join lab_laboratorio on lab_laboratorio.la02_i_codigo = lab_labsetor.la24_i_laboratorio";
  	$sSql .= "       inner join lab_controlefisicofinanceiro on lab_controlefisicofinanceiro.la56_i_laboratorio = lab_laboratorio.la02_i_codigo";
  	$sSql .= "       inner join lab_autoriza on la48_i_requisicao = la21_i_requisicao ";
  	$sSql .= "       inner join lab_requisicao on la22_i_codigo = la48_i_requisicao ";
  	if (!empty($sWhere)) {
  		$sSql .= " where {$sWhere} ";
  	}
  	return $sSql;
  }
  
}

?>