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
//CLASSE DA ENTIDADE tfd_fechamento
class cl_tfd_fechamento {
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
   var $tf32_i_codigo = 0;
   var $tf32_i_login = 0;
   var $tf32_i_mescompetencia = 0;
   var $tf32_i_anocompetencia = 0;
   var $tf32_d_datainicio_dia = null;
   var $tf32_d_datainicio_mes = null;
   var $tf32_d_datainicio_ano = null;
   var $tf32_d_datainicio = null;
   var $tf32_d_datafim_dia = null;
   var $tf32_d_datafim_mes = null;
   var $tf32_d_datafim_ano = null;
   var $tf32_d_datafim = null;
   var $tf32_d_datasistema_dia = null;
   var $tf32_d_datasistema_mes = null;
   var $tf32_d_datasistema_ano = null;
   var $tf32_d_datasistema = null;
   var $tf32_c_horasistema = null;
   var $tf32_c_descr = null;
   var $tf32_i_financiamento = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 tf32_i_codigo = int4 = Código
                 tf32_i_login = int4 = Login
                 tf32_i_mescompetencia = int4 = Competência mês
                 tf32_i_anocompetencia = int4 = Ano da competência
                 tf32_d_datainicio = date = Data de início
                 tf32_d_datafim = date = Data de fim
                 tf32_d_datasistema = date = Data sistema
                 tf32_c_horasistema = char(5) = Hora do sistema
                 tf32_c_descr = char(50) = Descrição
                 tf32_i_financiamento = int4 = Financiamento
                 ";
   //funcao construtor da classe
   function cl_tfd_fechamento() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tfd_fechamento");
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
       $this->tf32_i_codigo = ($this->tf32_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf32_i_codigo"]:$this->tf32_i_codigo);
       $this->tf32_i_login = ($this->tf32_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["tf32_i_login"]:$this->tf32_i_login);
       $this->tf32_i_mescompetencia = ($this->tf32_i_mescompetencia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf32_i_mescompetencia"]:$this->tf32_i_mescompetencia);
       $this->tf32_i_anocompetencia = ($this->tf32_i_anocompetencia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf32_i_anocompetencia"]:$this->tf32_i_anocompetencia);
       if($this->tf32_d_datainicio == ""){
         $this->tf32_d_datainicio_dia = ($this->tf32_d_datainicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf32_d_datainicio_dia"]:$this->tf32_d_datainicio_dia);
         $this->tf32_d_datainicio_mes = ($this->tf32_d_datainicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf32_d_datainicio_mes"]:$this->tf32_d_datainicio_mes);
         $this->tf32_d_datainicio_ano = ($this->tf32_d_datainicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf32_d_datainicio_ano"]:$this->tf32_d_datainicio_ano);
         if($this->tf32_d_datainicio_dia != ""){
            $this->tf32_d_datainicio = $this->tf32_d_datainicio_ano."-".$this->tf32_d_datainicio_mes."-".$this->tf32_d_datainicio_dia;
         }
       }
       if($this->tf32_d_datafim == ""){
         $this->tf32_d_datafim_dia = ($this->tf32_d_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf32_d_datafim_dia"]:$this->tf32_d_datafim_dia);
         $this->tf32_d_datafim_mes = ($this->tf32_d_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf32_d_datafim_mes"]:$this->tf32_d_datafim_mes);
         $this->tf32_d_datafim_ano = ($this->tf32_d_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf32_d_datafim_ano"]:$this->tf32_d_datafim_ano);
         if($this->tf32_d_datafim_dia != ""){
            $this->tf32_d_datafim = $this->tf32_d_datafim_ano."-".$this->tf32_d_datafim_mes."-".$this->tf32_d_datafim_dia;
         }
       }
       if($this->tf32_d_datasistema == ""){
         $this->tf32_d_datasistema_dia = ($this->tf32_d_datasistema_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf32_d_datasistema_dia"]:$this->tf32_d_datasistema_dia);
         $this->tf32_d_datasistema_mes = ($this->tf32_d_datasistema_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf32_d_datasistema_mes"]:$this->tf32_d_datasistema_mes);
         $this->tf32_d_datasistema_ano = ($this->tf32_d_datasistema_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf32_d_datasistema_ano"]:$this->tf32_d_datasistema_ano);
         if($this->tf32_d_datasistema_dia != ""){
            $this->tf32_d_datasistema = $this->tf32_d_datasistema_ano."-".$this->tf32_d_datasistema_mes."-".$this->tf32_d_datasistema_dia;
         }
       }
       $this->tf32_c_horasistema = ($this->tf32_c_horasistema == ""?@$GLOBALS["HTTP_POST_VARS"]["tf32_c_horasistema"]:$this->tf32_c_horasistema);
       $this->tf32_c_descr = ($this->tf32_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["tf32_c_descr"]:$this->tf32_c_descr);
       $this->tf32_i_financiamento = ($this->tf32_i_financiamento == ""?@$GLOBALS["HTTP_POST_VARS"]["tf32_i_financiamento"]:$this->tf32_i_financiamento);
     }else{
       $this->tf32_i_codigo = ($this->tf32_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf32_i_codigo"]:$this->tf32_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($tf32_i_codigo){
      $this->atualizacampos();
     if($this->tf32_i_login == null ){
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "tf32_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf32_i_mescompetencia == null ){
       $this->erro_sql = " Campo Competência mês nao Informado.";
       $this->erro_campo = "tf32_i_mescompetencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf32_i_anocompetencia == null ){
       $this->erro_sql = " Campo Ano da competência nao Informado.";
       $this->erro_campo = "tf32_i_anocompetencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf32_d_datainicio == null ){
       $this->erro_sql = " Campo Data de início nao Informado.";
       $this->erro_campo = "tf32_d_datainicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf32_d_datafim == null ){
       $this->erro_sql = " Campo Data de fim nao Informado.";
       $this->erro_campo = "tf32_d_datafim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf32_d_datasistema == null ){
       $this->erro_sql = " Campo Data sistema nao Informado.";
       $this->erro_campo = "tf32_d_datasistema_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf32_c_horasistema == null ){
       $this->erro_sql = " Campo Hora do sistema nao Informado.";
       $this->erro_campo = "tf32_c_horasistema";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf32_c_descr == null ){
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "tf32_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf32_i_financiamento == null ){
       $this->erro_sql = " Campo Financiamento nao Informado.";
       $this->erro_campo = "tf32_i_financiamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tf32_i_codigo == "" || $tf32_i_codigo == null ){
       $result = db_query("select nextval('tfd_fechamento_tf32_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tfd_fechamento_tf32_i_codigo_seq do campo: tf32_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->tf32_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from tfd_fechamento_tf32_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $tf32_i_codigo)){
         $this->erro_sql = " Campo tf32_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tf32_i_codigo = $tf32_i_codigo;
       }
     }
     if(($this->tf32_i_codigo == null) || ($this->tf32_i_codigo == "") ){
       $this->erro_sql = " Campo tf32_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tfd_fechamento(
                                       tf32_i_codigo
                                      ,tf32_i_login
                                      ,tf32_i_mescompetencia
                                      ,tf32_i_anocompetencia
                                      ,tf32_d_datainicio
                                      ,tf32_d_datafim
                                      ,tf32_d_datasistema
                                      ,tf32_c_horasistema
                                      ,tf32_c_descr
                                      ,tf32_i_financiamento
                       )
                values (
                                $this->tf32_i_codigo
                               ,$this->tf32_i_login
                               ,$this->tf32_i_mescompetencia
                               ,$this->tf32_i_anocompetencia
                               ,".($this->tf32_d_datainicio == "null" || $this->tf32_d_datainicio == ""?"null":"'".$this->tf32_d_datainicio."'")."
                               ,".($this->tf32_d_datafim == "null" || $this->tf32_d_datafim == ""?"null":"'".$this->tf32_d_datafim."'")."
                               ,".($this->tf32_d_datasistema == "null" || $this->tf32_d_datasistema == ""?"null":"'".$this->tf32_d_datasistema."'")."
                               ,'$this->tf32_c_horasistema'
                               ,'$this->tf32_c_descr'
                               ,$this->tf32_i_financiamento
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tfd_fechamento ($this->tf32_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tfd_fechamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tfd_fechamento ($this->tf32_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf32_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->tf32_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17312,'$this->tf32_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3065,17312,'','".AddSlashes(pg_result($resaco,0,'tf32_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3065,17313,'','".AddSlashes(pg_result($resaco,0,'tf32_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3065,17314,'','".AddSlashes(pg_result($resaco,0,'tf32_i_mescompetencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3065,17315,'','".AddSlashes(pg_result($resaco,0,'tf32_i_anocompetencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3065,17316,'','".AddSlashes(pg_result($resaco,0,'tf32_d_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3065,17317,'','".AddSlashes(pg_result($resaco,0,'tf32_d_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3065,17318,'','".AddSlashes(pg_result($resaco,0,'tf32_d_datasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3065,17319,'','".AddSlashes(pg_result($resaco,0,'tf32_c_horasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3065,17330,'','".AddSlashes(pg_result($resaco,0,'tf32_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3065,17989,'','".AddSlashes(pg_result($resaco,0,'tf32_i_financiamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($tf32_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update tfd_fechamento set ";
     $virgula = "";
     if(trim($this->tf32_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf32_i_codigo"])){
       $sql  .= $virgula." tf32_i_codigo = $this->tf32_i_codigo ";
       $virgula = ",";
       if(trim($this->tf32_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "tf32_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf32_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf32_i_login"])){
       $sql  .= $virgula." tf32_i_login = $this->tf32_i_login ";
       $virgula = ",";
       if(trim($this->tf32_i_login) == null ){
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "tf32_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf32_i_mescompetencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf32_i_mescompetencia"])){
       $sql  .= $virgula." tf32_i_mescompetencia = $this->tf32_i_mescompetencia ";
       $virgula = ",";
       if(trim($this->tf32_i_mescompetencia) == null ){
         $this->erro_sql = " Campo Competência mês nao Informado.";
         $this->erro_campo = "tf32_i_mescompetencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf32_i_anocompetencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf32_i_anocompetencia"])){
       $sql  .= $virgula." tf32_i_anocompetencia = $this->tf32_i_anocompetencia ";
       $virgula = ",";
       if(trim($this->tf32_i_anocompetencia) == null ){
         $this->erro_sql = " Campo Ano da competência nao Informado.";
         $this->erro_campo = "tf32_i_anocompetencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf32_d_datainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf32_d_datainicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf32_d_datainicio_dia"] !="") ){
       $sql  .= $virgula." tf32_d_datainicio = '$this->tf32_d_datainicio' ";
       $virgula = ",";
       if(trim($this->tf32_d_datainicio) == null ){
         $this->erro_sql = " Campo Data de início nao Informado.";
         $this->erro_campo = "tf32_d_datainicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf32_d_datainicio_dia"])){
         $sql  .= $virgula." tf32_d_datainicio = null ";
         $virgula = ",";
         if(trim($this->tf32_d_datainicio) == null ){
           $this->erro_sql = " Campo Data de início nao Informado.";
           $this->erro_campo = "tf32_d_datainicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tf32_d_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf32_d_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf32_d_datafim_dia"] !="") ){
       $sql  .= $virgula." tf32_d_datafim = '$this->tf32_d_datafim' ";
       $virgula = ",";
       if(trim($this->tf32_d_datafim) == null ){
         $this->erro_sql = " Campo Data de fim nao Informado.";
         $this->erro_campo = "tf32_d_datafim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf32_d_datafim_dia"])){
         $sql  .= $virgula." tf32_d_datafim = null ";
         $virgula = ",";
         if(trim($this->tf32_d_datafim) == null ){
           $this->erro_sql = " Campo Data de fim nao Informado.";
           $this->erro_campo = "tf32_d_datafim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tf32_d_datasistema)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf32_d_datasistema_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf32_d_datasistema_dia"] !="") ){
       $sql  .= $virgula." tf32_d_datasistema = '$this->tf32_d_datasistema' ";
       $virgula = ",";
       if(trim($this->tf32_d_datasistema) == null ){
         $this->erro_sql = " Campo Data sistema nao Informado.";
         $this->erro_campo = "tf32_d_datasistema_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf32_d_datasistema_dia"])){
         $sql  .= $virgula." tf32_d_datasistema = null ";
         $virgula = ",";
         if(trim($this->tf32_d_datasistema) == null ){
           $this->erro_sql = " Campo Data sistema nao Informado.";
           $this->erro_campo = "tf32_d_datasistema_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tf32_c_horasistema)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf32_c_horasistema"])){
       $sql  .= $virgula." tf32_c_horasistema = '$this->tf32_c_horasistema' ";
       $virgula = ",";
       if(trim($this->tf32_c_horasistema) == null ){
         $this->erro_sql = " Campo Hora do sistema nao Informado.";
         $this->erro_campo = "tf32_c_horasistema";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf32_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf32_c_descr"])){
       $sql  .= $virgula." tf32_c_descr = '$this->tf32_c_descr' ";
       $virgula = ",";
       if(trim($this->tf32_c_descr) == null ){
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "tf32_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf32_i_financiamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf32_i_financiamento"])){
       $sql  .= $virgula." tf32_i_financiamento = $this->tf32_i_financiamento ";
       $virgula = ",";
       if(trim($this->tf32_i_financiamento) == null ){
         $this->erro_sql = " Campo Financiamento nao Informado.";
         $this->erro_campo = "tf32_i_financiamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tf32_i_codigo!=null){
       $sql .= " tf32_i_codigo = $this->tf32_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->tf32_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17312,'$this->tf32_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf32_i_codigo"]) || $this->tf32_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3065,17312,'".AddSlashes(pg_result($resaco,$conresaco,'tf32_i_codigo'))."','$this->tf32_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf32_i_login"]) || $this->tf32_i_login != "")
           $resac = db_query("insert into db_acount values($acount,3065,17313,'".AddSlashes(pg_result($resaco,$conresaco,'tf32_i_login'))."','$this->tf32_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf32_i_mescompetencia"]) || $this->tf32_i_mescompetencia != "")
           $resac = db_query("insert into db_acount values($acount,3065,17314,'".AddSlashes(pg_result($resaco,$conresaco,'tf32_i_mescompetencia'))."','$this->tf32_i_mescompetencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf32_i_anocompetencia"]) || $this->tf32_i_anocompetencia != "")
           $resac = db_query("insert into db_acount values($acount,3065,17315,'".AddSlashes(pg_result($resaco,$conresaco,'tf32_i_anocompetencia'))."','$this->tf32_i_anocompetencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf32_d_datainicio"]) || $this->tf32_d_datainicio != "")
           $resac = db_query("insert into db_acount values($acount,3065,17316,'".AddSlashes(pg_result($resaco,$conresaco,'tf32_d_datainicio'))."','$this->tf32_d_datainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf32_d_datafim"]) || $this->tf32_d_datafim != "")
           $resac = db_query("insert into db_acount values($acount,3065,17317,'".AddSlashes(pg_result($resaco,$conresaco,'tf32_d_datafim'))."','$this->tf32_d_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf32_d_datasistema"]) || $this->tf32_d_datasistema != "")
           $resac = db_query("insert into db_acount values($acount,3065,17318,'".AddSlashes(pg_result($resaco,$conresaco,'tf32_d_datasistema'))."','$this->tf32_d_datasistema',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf32_c_horasistema"]) || $this->tf32_c_horasistema != "")
           $resac = db_query("insert into db_acount values($acount,3065,17319,'".AddSlashes(pg_result($resaco,$conresaco,'tf32_c_horasistema'))."','$this->tf32_c_horasistema',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf32_c_descr"]) || $this->tf32_c_descr != "")
           $resac = db_query("insert into db_acount values($acount,3065,17330,'".AddSlashes(pg_result($resaco,$conresaco,'tf32_c_descr'))."','$this->tf32_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf32_i_financiamento"]) || $this->tf32_i_financiamento != "")
           $resac = db_query("insert into db_acount values($acount,3065,17989,'".AddSlashes(pg_result($resaco,$conresaco,'tf32_i_financiamento'))."','$this->tf32_i_financiamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_fechamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf32_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_fechamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf32_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf32_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($tf32_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($tf32_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17312,'$tf32_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3065,17312,'','".AddSlashes(pg_result($resaco,$iresaco,'tf32_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3065,17313,'','".AddSlashes(pg_result($resaco,$iresaco,'tf32_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3065,17314,'','".AddSlashes(pg_result($resaco,$iresaco,'tf32_i_mescompetencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3065,17315,'','".AddSlashes(pg_result($resaco,$iresaco,'tf32_i_anocompetencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3065,17316,'','".AddSlashes(pg_result($resaco,$iresaco,'tf32_d_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3065,17317,'','".AddSlashes(pg_result($resaco,$iresaco,'tf32_d_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3065,17318,'','".AddSlashes(pg_result($resaco,$iresaco,'tf32_d_datasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3065,17319,'','".AddSlashes(pg_result($resaco,$iresaco,'tf32_c_horasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3065,17330,'','".AddSlashes(pg_result($resaco,$iresaco,'tf32_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3065,17989,'','".AddSlashes(pg_result($resaco,$iresaco,'tf32_i_financiamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tfd_fechamento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tf32_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tf32_i_codigo = $tf32_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_fechamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tf32_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_fechamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tf32_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tf32_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tfd_fechamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $tf32_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from tfd_fechamento ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tfd_fechamento.tf32_i_login";
     $sql .= "      inner join sau_financiamento  on  sau_financiamento.sd65_i_codigo = tfd_fechamento.tf32_i_financiamento";
     $sql2 = "";
     if($dbwhere==""){
       if($tf32_i_codigo!=null ){
         $sql2 .= " where tfd_fechamento.tf32_i_codigo = $tf32_i_codigo ";
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
   function sql_query_file ( $tf32_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from tfd_fechamento ";
     $sql2 = "";
     if($dbwhere==""){
       if($tf32_i_codigo!=null ){
         $sql2 .= " where tfd_fechamento.tf32_i_codigo = $tf32_i_codigo ";
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
   * Busca os programas realizados
   * @param string $sWhere
   * @return string
   */
  function sql_query_programas ($sWhere = null) {
  
    $sSql  = " select ";
    $sSql .= "        distinct                                                                                         ";
    /* dados do medico */
    $sSql .= "        medicos.sd03_i_codigo                 as codigo_medico,                                          ";
    $sSql .= "        case                                                                                             ";
    $sSql .= "          when trim(m.z01_nome) = '' or m.z01_nome is null                                               ";
    $sSql .= "            then sau_medicosforarede.s154_c_nome                                                         ";
    $sSql .= "          else m.z01_nome                                                                                ";
    $sSql .= "        end                                   as nome_medico,                                            ";
    $sSql .= "        case                                                                                             ";
    $sSql .= "          when trim(cgmdoc.z02_i_cns) = '' or cgmdoc.z02_i_cns is null                                   ";
    $sSql .= "            then sau_medicosforarede.s154_c_cns                                                          ";
    $sSql .= "          else cgmdoc.z02_i_cns                                                                          ";
    $sSql .= "        end                                   as cnsmedico,                                              ";
    $sSql .= "        rhcbo.rh70_estrutural                 as cbo,                                                    ";
  
    /* dados atendimento */
    $sSql .= "        unidades.sd02_i_codigo                as unidade,                                                ";
    $sSql .= "        unidades.sd02_v_cnes                  as cnes_unidade,                                           ";
    $sSql .= "        tfd_agendamentoprestadora.tf16_d_dataagendamento               as data_atendimento,              ";
    $sSql .= "        sau_procedimento.sd63_i_codigo        as codigo_procedimento,                                    ";
    $sSql .= "        sau_procedimento.sd63_c_procedimento  as procedimento,                                           ";
    $sSql .= "        ''                                    as cid,                                                    ";
    $sSql .= "        ''                                    as faa,                                                    ";
    $sSql .= "        '01'::varchar                         as char_atendimento,                                       ";
    $sSql .= "        floor(tf03_f_distancia / tf24_i_percurso) as quantidade,                                         ";
  
  
    $sSql .= "        ( select array_to_string(array_accum( sau_registro.sd84_c_registro), ',')                        ";
    $sSql .= "            from sau_procregistro                                                                        ";
    $sSql .= "           inner join sau_registro  on sau_registro.sd84_i_codigo = sau_procregistro.sd85_i_registro     ";
    $sSql .= "           where sau_procregistro.sd85_i_procedimento = sau_procedimento.sd63_i_codigo) as tipo_registro,";
    /* dados do paciente */
  
    $sSql .= "        cgs_und.z01_i_cgsund                  as codigo_paciente,           ";
    $sSql .= "        cgs_und.z01_v_nome                    as nome_paciente,             ";
    $sSql .= "        (select s115_c_cartaosus                                            ";
    $sSql .= "           from cgs_cartaosus                                               ";
    $sSql .= "          where s115_i_cgs = cgs.z01_i_numcgs                               ";
    $sSql .= "            and s115_c_cartaosus is not null                                ";
    $sSql .= "          ORDER BY s115_c_tipo ASC LIMIT 1)   as cartao_sus,                ";
    $sSql .= "        cgs_und.z01_v_sexo                    as sexo,                      ";
    $sSql .= "        cgs_und.z01_d_nasc                    as data_nascimento,           ";
    $sSql .= "        CASE                                                                ";
    $sSql .= "          WHEN fc_idade(z01_d_nasc, tf16_d_dataagendamento) > 99            ";
    $sSql .= "            THEN 40                                                         ";
    $sSql .= "          ELSE fc_idade(z01_d_nasc, tf16_d_dataagendamento)                 ";
    $sSql .= "        END                                   as idade_atendimento,         ";
    $sSql .= "        cgs_und.z01_v_email                   as email,                     ";
    $sSql .= "        cgs_und.z01_c_raca                    as raca,                      ";
    $sSql .= "        cgs_und.z01_v_ender                   as endereco_paciente,         ";
    $sSql .= "        cgs_und.z01_v_compl                   as complemento_end_paciente,  ";
    $sSql .= "        cgs_und.z01_i_numero                  as numero_end_paciente,       ";
    $sSql .= "        cgs_und.z01_v_bairro                  as bairro_end_paciente,       ";
    $sSql .= "        cgs_und.z01_v_cep                     as cep_paciente,              ";
    $sSql .= "        cgs_und.z01_v_telef                   as telefone_paciente,         ";
    $sSql .= "        etnia.s200_identificador              as etinia,                    ";
    $sSql .= "        medicos.sd03_i_tipo                   as tipo_profissional          ";

    $sSql .= "   FROM tfd_fechamento                                                                                                            ";
    $sSql .= "        inner join fechamentotfdprocedimento  on tf40_tfd_fechamento                  = tf32_i_codigo                             ";
    $sSql .= "        inner join tfd_pedidotfd              on tf40_tfd_pedidotfd                   = tf01_i_codigo                             ";
    $sSql .= "        inner join unidades                   on unidades.sd02_i_codigo               = tfd_pedidotfd.tf01_i_depto                ";
    $sSql .= "        inner join cgs                        on cgs.z01_i_numcgs                     = tf40_cgs_und                              ";
    $sSql .= "        inner join cgs_und                    on cgs_und.z01_i_cgsund                 = cgs.z01_i_numcgs                          ";
    $sSql .= "        left  join cgs_undetnia               on cgs_undetnia.s201_cgs_unid           = cgs_und.z01_i_cgsund                      ";
    $sSql .= "        left  join etnia                      on etnia.s200_codigo                    = cgs_undetnia.s201_etnia                   ";
    $sSql .= "        inner join sau_procedimento           on sau_procedimento.sd63_i_codigo       = tf40_sau_procedimento                     ";
    $sSql .= "        inner join sau_financiamento          on sau_financiamento.sd65_i_codigo      = sau_procedimento.sd63_i_financiamento     ";
    $sSql .= "        inner join sau_procregistro           on sau_procregistro.sd85_i_procedimento = sau_procedimento.sd63_i_codigo            ";
    $sSql .= "        inner join medicos                    on medicos.sd03_i_codigo                = tf01_i_profissionalsolic                  ";
    $sSql .= "        left  join sau_medicosforarede        on sau_medicosforarede.s154_i_medico    = medicos.sd03_i_codigo                     ";
    $sSql .= "        left  join cgm m                      on m.z01_numcgm                         = medicos.sd03_i_cgm                        ";
    $sSql .= "        left  join cgmdoc                     on cgmdoc.z02_i_cgm                     = m.z01_numcgm                              ";
    $sSql .= "        inner join rhcbo                      on tf01_rhcbosolicitante                = rh70_sequencial                           ";
    $sSql .= "        inner join tfd_agendamentoprestadora  on tf16_i_pedidotfd                     = tf01_i_codigo                             ";
    $sSql .= "        inner join tfd_prestadoracentralagend on tf16_i_prestcentralagend             =  tfd_prestadoracentralagend.tf10_i_codigo ";
    $sSql .= "        inner join tfd_prestadora             on tf10_i_prestadora                    = tfd_prestadora.tf25_i_codigo              ";
    $sSql .= "        inner join tfd_destino                on tf25_i_destino                       = tfd_destino.tf03_i_codigo                 ";
    $sSql .= "        inner join tfd_tipodistancia          on tfd_tipodistancia.tf24_i_codigo      = tfd_destino.tf03_i_tipodistancia          ";
    $sSql .= "  where tf01_i_situacao = 2                                                                                                       ";
  
    if (!empty($sWhere)) {
      $sSql .= " and {$sWhere} " ;
    }
  
    $sSql .= "  order by cnsmedico, cbo, procedimento, codigo_procedimento, idade_atendimento";

    return $sSql;
  }
}
?>