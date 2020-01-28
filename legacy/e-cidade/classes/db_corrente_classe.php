<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: caixa
//CLASSE DA ENTIDADE corrente
class cl_corrente {
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
   var $k12_id = 0;
   var $k12_data_dia = null;
   var $k12_data_mes = null;
   var $k12_data_ano = null;
   var $k12_data = null;
   var $k12_autent = 0;
   var $k12_hora = null;
   var $k12_conta = 0;
   var $k12_valor = 0;
   var $k12_estorn = 'f';
   var $k12_instit = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 k12_id = int4 = Autenticação
                 k12_data = date = Data Autenticação
                 k12_autent = int4 = Código Autenticação
                 k12_hora = char(5) = Hora
                 k12_conta = int4 = Código da Conta
                 k12_valor = float8 = Valor Autenticação
                 k12_estorn = bool = Estornado
                 k12_instit = int4 = Instituição
                 ";
   //funcao construtor da classe
   function cl_corrente() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("corrente");
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
       $this->k12_id = ($this->k12_id == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_id"]:$this->k12_id);
       if($this->k12_data == ""){
         $this->k12_data_dia = ($this->k12_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_data_dia"]:$this->k12_data_dia);
         $this->k12_data_mes = ($this->k12_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_data_mes"]:$this->k12_data_mes);
         $this->k12_data_ano = ($this->k12_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_data_ano"]:$this->k12_data_ano);
         if($this->k12_data_dia != ""){
            $this->k12_data = $this->k12_data_ano."-".$this->k12_data_mes."-".$this->k12_data_dia;
         }
       }
       $this->k12_autent = ($this->k12_autent == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_autent"]:$this->k12_autent);
       $this->k12_hora = ($this->k12_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_hora"]:$this->k12_hora);
       $this->k12_conta = ($this->k12_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_conta"]:$this->k12_conta);
       $this->k12_valor = ($this->k12_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_valor"]:$this->k12_valor);
       $this->k12_estorn = ($this->k12_estorn == "f"?@$GLOBALS["HTTP_POST_VARS"]["k12_estorn"]:$this->k12_estorn);
       $this->k12_instit = ($this->k12_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_instit"]:$this->k12_instit);
     }else{
       $this->k12_id = ($this->k12_id == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_id"]:$this->k12_id);
       $this->k12_data = ($this->k12_data == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_data_ano"]."-".@$GLOBALS["HTTP_POST_VARS"]["k12_data_mes"]."-".@$GLOBALS["HTTP_POST_VARS"]["k12_data_dia"]:$this->k12_data);
       $this->k12_autent = ($this->k12_autent == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_autent"]:$this->k12_autent);
     }
   }
   // funcao para inclusao
   function incluir ($k12_id,$k12_data,$k12_autent){
      $this->atualizacampos();
     if($this->k12_hora == null ){
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "k12_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k12_conta == null ){
       $this->erro_sql = " Campo Código da Conta nao Informado.";
       $this->erro_campo = "k12_conta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k12_valor == null ){
       $this->erro_sql = " Campo Valor Autenticação nao Informado.";
       $this->erro_campo = "k12_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k12_estorn == null ){
       $this->erro_sql = " Campo Estornado nao Informado.";
       $this->erro_campo = "k12_estorn";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k12_instit == null ){
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "k12_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->k12_id = $k12_id;
       $this->k12_data = $k12_data;
       $this->k12_autent = $k12_autent;
     if(($this->k12_id == null) || ($this->k12_id == "") ){
       $this->erro_sql = " Campo k12_id nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k12_data == null) || ($this->k12_data == "") ){
       $this->erro_sql = " Campo k12_data nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k12_autent == null) || ($this->k12_autent == "") ){
       $this->erro_sql = " Campo k12_autent nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into corrente(
                                       k12_id
                                      ,k12_data
                                      ,k12_autent
                                      ,k12_hora
                                      ,k12_conta
                                      ,k12_valor
                                      ,k12_estorn
                                      ,k12_instit
                       )
                values (
                                $this->k12_id
                               ,".($this->k12_data == "null" || $this->k12_data == ""?"null":"'".$this->k12_data."'")."
                               ,$this->k12_autent
                               ,'$this->k12_hora'
                               ,$this->k12_conta
                               ,$this->k12_valor
                               ,'$this->k12_estorn'
                               ,$this->k12_instit
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Autenticações ($this->k12_id."-".$this->k12_data."-".$this->k12_autent) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Autenticações já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Autenticações ($this->k12_id."-".$this->k12_data."-".$this->k12_autent) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k12_id."-".$this->k12_data."-".$this->k12_autent;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k12_id,$this->k12_data,$this->k12_autent));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1139,'$this->k12_id','I')");
       $resac = db_query("insert into db_acountkey values($acount,1140,'$this->k12_data','I')");
       $resac = db_query("insert into db_acountkey values($acount,1141,'$this->k12_autent','I')");
       $resac = db_query("insert into db_acount values($acount,200,1139,'','".AddSlashes(pg_result($resaco,0,'k12_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,200,1140,'','".AddSlashes(pg_result($resaco,0,'k12_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,200,1141,'','".AddSlashes(pg_result($resaco,0,'k12_autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,200,1142,'','".AddSlashes(pg_result($resaco,0,'k12_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,200,1143,'','".AddSlashes(pg_result($resaco,0,'k12_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,200,1144,'','".AddSlashes(pg_result($resaco,0,'k12_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,200,1145,'','".AddSlashes(pg_result($resaco,0,'k12_estorn'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,200,6316,'','".AddSlashes(pg_result($resaco,0,'k12_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($k12_id=null,$k12_data=null,$k12_autent=null) {
      $this->atualizacampos();
     $sql = " update corrente set ";
     $virgula = "";
     if(trim($this->k12_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_id"])){
       $sql  .= $virgula." k12_id = $this->k12_id ";
       $virgula = ",";
       if(trim($this->k12_id) == null ){
         $this->erro_sql = " Campo Autenticação nao Informado.";
         $this->erro_campo = "k12_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k12_data_dia"] !="") ){
       $sql  .= $virgula." k12_data = '$this->k12_data' ";
       $virgula = ",";
       if(trim($this->k12_data) == null ){
         $this->erro_sql = " Campo Data Autenticação nao Informado.";
         $this->erro_campo = "k12_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["k12_data_dia"])){
         $sql  .= $virgula." k12_data = null ";
         $virgula = ",";
         if(trim($this->k12_data) == null ){
           $this->erro_sql = " Campo Data Autenticação nao Informado.";
           $this->erro_campo = "k12_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k12_autent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_autent"])){
       $sql  .= $virgula." k12_autent = $this->k12_autent ";
       $virgula = ",";
       if(trim($this->k12_autent) == null ){
         $this->erro_sql = " Campo Código Autenticação nao Informado.";
         $this->erro_campo = "k12_autent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_hora"])){
       $sql  .= $virgula." k12_hora = '$this->k12_hora' ";
       $virgula = ",";
       if(trim($this->k12_hora) == null ){
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "k12_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_conta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_conta"])){
       $sql  .= $virgula." k12_conta = $this->k12_conta ";
       $virgula = ",";
       if(trim($this->k12_conta) == null ){
         $this->erro_sql = " Campo Código da Conta nao Informado.";
         $this->erro_campo = "k12_conta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_valor"])){
       $sql  .= $virgula." k12_valor = $this->k12_valor ";
       $virgula = ",";
       if(trim($this->k12_valor) == null ){
         $this->erro_sql = " Campo Valor Autenticação nao Informado.";
         $this->erro_campo = "k12_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_estorn)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_estorn"])){
       $sql  .= $virgula." k12_estorn = '$this->k12_estorn' ";
       $virgula = ",";
       if(trim($this->k12_estorn) == null ){
         $this->erro_sql = " Campo Estornado nao Informado.";
         $this->erro_campo = "k12_estorn";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_instit"])){
       $sql  .= $virgula." k12_instit = $this->k12_instit ";
       $virgula = ",";
       if(trim($this->k12_instit) == null ){
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "k12_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k12_id!=null){
       $sql .= " k12_id = $this->k12_id";
     }
     if($k12_data!=null){
       $sql .= " and  k12_data = '$this->k12_data'";
     }
     if($k12_autent!=null){
       $sql .= " and  k12_autent = $this->k12_autent";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k12_id,$this->k12_data,$this->k12_autent));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1139,'$this->k12_id','A')");
         $resac = db_query("insert into db_acountkey values($acount,1140,'$this->k12_data','A')");
         $resac = db_query("insert into db_acountkey values($acount,1141,'$this->k12_autent','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_id"]))
           $resac = db_query("insert into db_acount values($acount,200,1139,'".AddSlashes(pg_result($resaco,$conresaco,'k12_id'))."','$this->k12_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_data"]))
           $resac = db_query("insert into db_acount values($acount,200,1140,'".AddSlashes(pg_result($resaco,$conresaco,'k12_data'))."','$this->k12_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_autent"]))
           $resac = db_query("insert into db_acount values($acount,200,1141,'".AddSlashes(pg_result($resaco,$conresaco,'k12_autent'))."','$this->k12_autent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_hora"]))
           $resac = db_query("insert into db_acount values($acount,200,1142,'".AddSlashes(pg_result($resaco,$conresaco,'k12_hora'))."','$this->k12_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_conta"]))
           $resac = db_query("insert into db_acount values($acount,200,1143,'".AddSlashes(pg_result($resaco,$conresaco,'k12_conta'))."','$this->k12_conta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_valor"]))
           $resac = db_query("insert into db_acount values($acount,200,1144,'".AddSlashes(pg_result($resaco,$conresaco,'k12_valor'))."','$this->k12_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_estorn"]))
           $resac = db_query("insert into db_acount values($acount,200,1145,'".AddSlashes(pg_result($resaco,$conresaco,'k12_estorn'))."','$this->k12_estorn',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_instit"]))
           $resac = db_query("insert into db_acount values($acount,200,6316,'".AddSlashes(pg_result($resaco,$conresaco,'k12_instit'))."','$this->k12_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Autenticações nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k12_id."-".$this->k12_data."-".$this->k12_autent;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Autenticações nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k12_id."-".$this->k12_data."-".$this->k12_autent;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k12_id."-".$this->k12_data."-".$this->k12_autent;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($k12_id=null,$k12_data=null,$k12_autent=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k12_id,$k12_data,$k12_autent));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1139,'$k12_id','E')");
         $resac = db_query("insert into db_acountkey values($acount,1140,'$k12_data','E')");
         $resac = db_query("insert into db_acountkey values($acount,1141,'$k12_autent','E')");
         $resac = db_query("insert into db_acount values($acount,200,1139,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,200,1140,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,200,1141,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,200,1142,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,200,1143,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,200,1144,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,200,1145,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_estorn'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,200,6316,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from corrente
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k12_id != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k12_id = $k12_id ";
        }
        if($k12_data != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k12_data = '$k12_data' ";
        }
        if($k12_autent != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k12_autent = $k12_autent ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Autenticações nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k12_id."-".$k12_data."-".$k12_autent;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Autenticações nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k12_id."-".$k12_data."-".$k12_autent;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k12_id."-".$k12_data."-".$k12_autent;
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
        $this->erro_sql   = "Record Vazio na Tabela:corrente";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k12_id=null,$k12_data=null,$k12_autent=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from corrente ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = corrente.k12_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($k12_id!=null ){
         $sql2 .= " where corrente.k12_id = $k12_id ";
       }
       if($k12_data!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " corrente.k12_data = '$k12_data' ";
       }
       if($k12_autent!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " corrente.k12_autent = $k12_autent ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql2 .= ($sql2!=""?" and ":" where ") . " corrente.k12_instit = " . db_getsession("DB_instit");
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
   function sql_query_file ( $k12_id=null,$k12_data=null,$k12_autent=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from corrente ";
     $sql2 = "";
     if($dbwhere==""){
       if($k12_id!=null ){
         $sql2 .= " where corrente.k12_id = $k12_id ";
       }
       if($k12_data!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " corrente.k12_data = '$k12_data' ";
       }
       if($k12_autent!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " corrente.k12_autent = $k12_autent ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql2 .= ($sql2!=""?" and ":" where ") . " corrente.k12_instit = " . db_getsession("DB_instit");
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

  public function sql_query_arrecadacao_receita($iId, $sData, $iAutent, $sCampos = "*") {

    $sql  = " select {$sCampos}                                                                                      ";
    $sql .= "   from (                                                                                       ";
    $sql .= " select k12_conta,                                                                                    ";
    $sql .= "         k02_codrec,                                                                                  ";
    $sql .= "         arrecada as arrecada,                                                                        ";
    $sql .= "         estorna as estorna,                                                                          ";
    $sql .= "         cgm_pago,                                                                                    ";
    $sql .= "         cgm_estornado,                                                                               ";
    $sql .= "         cgm_recibo_avulso,                                                                               ";
    $sql .= "         k12_codcla,                                                                                  ";
    $sql .= "         k12_histcor,                                                                                 ";
    $sql .= "         k12_id,                                                                                      ";
    $sql .= "         k12_autent,                                                                                   ";
    $sql .= "         k12_numpre,                                                        ";
    $sql .= "         k12_numpar                                                         ";
    $sql .= "    from ( select k12_conta,                                                                          ";
    $sql .= "                  k02_codrec,                                                                         ";
    $sql .= "                  k12_histcor,                                                                        ";
    $sql .= "                  ( select k00_numcgm                                                                 ";
    $sql .= "                      from arrepaga                                                                   ";
    $sql .= "                     where k00_numpre = x.k12_numpre                                                  ";
    $sql .= "                       and k00_numpar = x.k12_numpar                                                  ";
    $sql .= "                     limit 1) as cgm_pago,                                                            ";
    $sql .= "                  ( select k00_numcgm                                                                 ";
    $sql .= "                      from arrecad                                                                    ";
    $sql .= "                     where k00_numpre = x.k12_numpre                                                  ";
    $sql .= "                       and k00_numpar = x.k12_numpar                                                  ";
    $sql .= "                     limit 1) as cgm_estornado,                                                       ";
    $sql .= "                  ( select k00_numcgm                                                                 ";
    $sql .= "                      from recibo                                                                    ";
    $sql .= "                     where k00_numpre = x.k12_numnov                                                  ";
    $sql .= "                     limit 1) as cgm_recibo_avulso,                                                       ";
    $sql .= "                  ( select k12_codcla                                                                 ";
    $sql .= "                      from corcla                                                                     ";
    $sql .= "                     where k12_id     = {$iId}                                                        ";
    $sql .= "                       and k12_data   ='{$sData}'                                                    ";
    $sql .= "                       and k12_autent = {$iAutent}                                                    ";
    $sql .= "                     limit 1) as k12_codcla,                                                          ";
    $sql .= "                  arrecada,                                                                           ";
    $sql .= "                  estorna,                                                                            ";
    $sql .= "                  k12_id,                                                                             ";
    $sql .= "                  k12_autent,                                                                          ";
    $sql .= "                  k12_numpre,                                                        ";
    $sql .= "                  k12_numpar                                                         ";
    $sql .= "             from ( select corrente.k12_id,                                                           ";
    $sql .= "                           corrente.k12_autent,                                                       ";
    $sql .= "                           k12_conta,                                                                 ";
    $sql .= "                           k02_codrec,                                                                ";
    $sql .= "                           round ( case when                                                          ";
    $sql .= "                                     corrente.k12_estorn = false                                      ";
    $sql .= "                                       then cornump.k12_valor                                         ";
    $sql .= "                                     else 0::float8                                                   ";
    $sql .= "                                   end,2) as arrecada,                                                ";
    $sql .= "                           round ( case when                                                          ";
    $sql .= "                                     corrente.k12_estorn = true                                       ";
    $sql .= "                                       then cornump.k12_valor                                         ";
    $sql .= "                                     else 0::float8                                                   ";
    $sql .= "                                   end,2) as estorna,                                                 ";
    $sql .= "                           k12_histcor,                                                               ";
    $sql .= "                           cornump.k12_numnov,                                                        ";
    $sql .= "                           cornump.k12_numpre,                                                        ";
    $sql .= "                           cornump.k12_numpar                                                         ";
    $sql .= "                      from corrente                                                                   ";
    $sql .= "                           inner join cornump   on corrente.k12_id     = cornump.k12_id               ";
    $sql .= "                                               and corrente.k12_data   = cornump.k12_data             ";
    $sql .= "                                               and corrente.k12_autent = cornump.k12_autent           ";
    $sql .= "                           left  join corhist   on corrente.k12_id     = corhist.k12_id               ";
    $sql .= "                                               and corrente.k12_data   = corhist.k12_data             ";
    $sql .= "                                               and corrente.k12_autent = corhist.k12_autent           ";
    $sql .= "                           inner join tabrec    on k12_receit          = tabrec.k02_codigo            ";
    $sql .= "                           inner join taborc    on taborc.k02_codigo   = tabrec.k02_codigo            ";
    $sql .= "                                               and taborc.k02_anousu   = ".db_getsession("DB_anousu");


    $sql .= " inner join orcreceita  on orcreceita.o70_codrec = taborc.k02_codrec ";
    $sql .= "                       and orcreceita.o70_anousu = taborc.k02_anousu ";
    $sql .= " inner join orcfontes   on orcfontes.o57_codfon =  orcreceita.o70_codfon ";
    $sql .= "                       and orcfontes.o57_anousu =  orcreceita.o70_anousu ";

    $sql .= " inner join conplanoorcamento          on conplanoorcamento.c60_codcon                    = orcfontes.o57_codfon";
    $sql .= "                                      and conplanoorcamento.c60_anousu                    = orcfontes.o57_anousu";
    $sql .= " inner join conplanoconplanoorcamento  on conplanoconplanoorcamento.c72_conplanoorcamento = conplanoorcamento.c60_codcon";
    $sql .= "                                      and conplanoconplanoorcamento.c72_anousu            = conplanoorcamento.c60_anousu";

    $sql .= " inner join conplano       on conplano.c60_codcon     = conplanoconplanoorcamento.c72_conplano";
    $sql .= "                          and conplano.c60_anousu     = conplanoconplanoorcamento.c72_anousu";
    $sql .= " inner join conplanoreduz  on conplano.c60_codcon     = conplanoreduz.c61_codcon ";
    $sql .= "                          and conplano.c60_anousu     = conplanoreduz.c61_anousu ";
    $sql .= " inner join conplanoexe    on conplanoreduz.c61_reduz = c62_reduz ";
    $sql .= "                          and c62_anousu              = conplanoreduz.c61_anousu ";

    $sql .= "where corrente.k12_id = {$iId}
    and corrente.k12_data          = '{$sData}'
    and corrente.k12_autent        = {$iAutent}
    and taborc.k02_anousu          = ".db_getsession('DB_anousu')."
    and conplanoreduz.c61_instit   = ".db_getsession('DB_instit')."
    ) as x
    ) as xx
    ) as xxx
    inner join orcreceita on o70_anousu = " . db_getsession('DB_anousu') . " and o70_codrec = k02_codrec ";





    return $sql;
  }

  public function sql_query_arrecadacao_desconto($iId, $sData, $iAutent, $sCampos = "*") {

    $sql  = " select {$sCampos}                                                                                      ";
    $sql .= "   from (                                                                                       ";
    $sql .= " select k12_conta,                                                                                    ";
    $sql .= "         k02_codrec,                                                                                  ";
    $sql .= "         arrecada as arrecada,                                                                        ";
    $sql .= "         estorna as estorna,                                                                          ";
    $sql .= "         cgm_pago,                                                                                    ";
    $sql .= "         cgm_estornado,                                                                               ";
    $sql .= "         k12_codcla,                                                                                  ";
    $sql .= "         k12_histcor,                                                                                 ";
    $sql .= "         k12_id,                                                                                      ";
    $sql .= "         k12_autent,                                                                                   ";
    $sql .= "         k12_numpre,                                                        ";
    $sql .= "         k12_numpar                                                         ";
    $sql .= "    from ( select k12_conta,                                                                          ";
    $sql .= "                  k02_codrec,                                                                         ";
    $sql .= "                  k12_histcor,                                                                        ";
    $sql .= "                  ( select k00_numcgm                                                                 ";
    $sql .= "                      from arrepaga                                                                   ";
    $sql .= "                     where k00_numpre = x.k12_numpre                                                  ";
    $sql .= "                       and k00_numpar = x.k12_numpar                                                  ";
    $sql .= "                     limit 1) as cgm_pago,                                                            ";
    $sql .= "                  ( select k00_numcgm                                                                 ";
    $sql .= "                      from arrecad                                                                    ";
    $sql .= "                     where k00_numpre = x.k12_numpre                                                  ";
    $sql .= "                       and k00_numpar = x.k12_numpar                                                  ";
    $sql .= "                     limit 1) as cgm_estornado,                                                       ";
    $sql .= "                  ( select k12_codcla                                                                 ";
    $sql .= "                      from corcla                                                                     ";
    $sql .= "                     where k12_id     = {$iId}                                                        ";
    $sql .= "                       and k12_data   ='{$sData}'                                                    ";
    $sql .= "                       and k12_autent = {$iAutent}                                                    ";
    $sql .= "                     limit 1) as k12_codcla,                                                          ";
    $sql .= "                  arrecada,                                                                           ";
    $sql .= "                  estorna,                                                                            ";
    $sql .= "                  k12_id,                                                                             ";
    $sql .= "                  k12_autent,                                                                          ";
    $sql .= "                  k12_numpre,                                                        ";
    $sql .= "                  k12_numpar                                                         ";
    $sql .= "             from ( select corrente.k12_id,                                                           ";
    $sql .= "                           corrente.k12_autent,                                                       ";
    $sql .= "                           k12_conta,                                                                 ";
    $sql .= "                           k02_codrec,                                                                ";
    $sql .= "                           round ( case when                                                          ";
    $sql .= "                                     corrente.k12_estorn = false                                      ";
    $sql .= "                                       then cornump.k12_valor                                         ";
    $sql .= "                                     else 0::float8                                                   ";
    $sql .= "                                   end,2) as arrecada,                                                ";
    $sql .= "                           round ( case when                                                          ";
    $sql .= "                                     corrente.k12_estorn = true                                       ";
    $sql .= "                                       then cornump.k12_valor                                         ";
    $sql .= "                                     else 0::float8                                                   ";
    $sql .= "                                   end,2) as estorna,                                                 ";
    $sql .= "                           k12_histcor,                                                               ";
    $sql .= "                           cornump.k12_numpre,                                                        ";
    $sql .= "                           cornump.k12_numpar                                                         ";
    $sql .= "                      from corrente                                                                   ";
    $sql .= "                           inner join cornumpdesconto cornump on corrente.k12_id     = cornump.k12_id               ";
    $sql .= "                                               and corrente.k12_data   = cornump.k12_data             ";
    $sql .= "                                               and corrente.k12_autent = cornump.k12_autent           ";
    $sql .= "                           left  join corhist   on corrente.k12_id     = corhist.k12_id               ";
    $sql .= "                                               and corrente.k12_data   = corhist.k12_data             ";
    $sql .= "                                               and corrente.k12_autent = corhist.k12_autent           ";
    $sql .= "                           inner join tabrec    on k12_receit          = tabrec.k02_codigo            ";
    $sql .= "                           inner join taborc    on taborc.k02_codigo   = tabrec.k02_codigo            ";
    $sql .= "                                               and taborc.k02_anousu   = ".db_getsession("DB_anousu");


    $sql .= " inner join orcreceita  on orcreceita.o70_codrec = taborc.k02_codrec ";
    $sql .= "                       and orcreceita.o70_anousu = taborc.k02_anousu ";
    $sql .= " inner join orcfontes   on orcfontes.o57_codfon =  orcreceita.o70_codfon ";
    $sql .= "                       and orcfontes.o57_anousu =  orcreceita.o70_anousu ";

    $sql .= " inner join conplanoorcamento          on conplanoorcamento.c60_codcon                    = orcfontes.o57_codfon";
    $sql .= "                                      and conplanoorcamento.c60_anousu                    = orcfontes.o57_anousu";
    $sql .= " inner join conplanoconplanoorcamento  on conplanoconplanoorcamento.c72_conplanoorcamento = conplanoorcamento.c60_codcon";
    $sql .= "                                      and conplanoconplanoorcamento.c72_anousu            = conplanoorcamento.c60_anousu";

    $sql .= " inner join conplano       on conplano.c60_codcon     = conplanoconplanoorcamento.c72_conplano";
    $sql .= "                          and conplano.c60_anousu     = conplanoconplanoorcamento.c72_anousu";
    $sql .= " inner join conplanoreduz  on conplano.c60_codcon     = conplanoreduz.c61_codcon ";
    $sql .= "                          and conplano.c60_anousu     = conplanoreduz.c61_anousu ";
    $sql .= " inner join conplanoexe    on conplanoreduz.c61_reduz = c62_reduz ";
    $sql .= "                          and c62_anousu              = conplanoreduz.c61_anousu ";

    $sql .= "where corrente.k12_id = {$iId}
    and corrente.k12_data          = '{$sData}'
    and corrente.k12_autent        = {$iAutent}
    and taborc.k02_anousu          = ".db_getsession('DB_anousu')."
        and conplanoreduz.c61_instit   = ".db_getsession('DB_instit')."
        ) as x
    ) as xx
    ) as xxx
    inner join orcreceita on o70_anousu = " . db_getsession('DB_anousu') . " and o70_codrec = k02_codrec ";





      return $sql;
  }


  public function sql_estorno_arrecadacao_receita($iId, $sData, $iAutent, $sCampos = "*") {

    $iAnoSessao = db_getsession("DB_anousu");
    $iInstituicaoSessao = db_getsession("DB_instit");

    $sql  = " select {$sCampos}                                                                                                                                    ";
    $sql .= "   from ( select k12_conta,                                                                                                                                             ";
    $sql .= "                 k02_codrec,                                                                                                                                            ";
    $sql .= "                 sum(arrecada) as arrecada,                                                                                                                             ";
    $sql .= "                 sum(estorna) as estorna,                                                                                                                               ";
    $sql .= "                 cgm_pago,                                                                                                                                              ";
    $sql .= "                 cgm_estornado,                                                                                                                                         ";
    $sql .= "                 cgm_recibo_avulso,                                                                                                                                         ";
    $sql .= "                 k12_codcla,                                                                                                                                            ";
    $sql .= "                 k12_histcor,                                                                                                                                           ";
    $sql .= "                 0 as k12_id,                                                                                                                                           ";
    $sql .= "                 0 as k12_autent                                                                                                                                        ";
    $sql .= "                 from  ( select k12_conta,                                                                                                                              ";
    $sql .= "                                k02_codrec,                                                                                                                             ";
    $sql .= "                                k12_histcor,                                                                                                                            ";
    $sql .= "                                (select k00_numcgm                                                                                                                      ";
    $sql .= "                                   from arrepaga                                                                                                                        ";
    $sql .= "                                  where k00_numpre = x.k12_numpre                                                                                                       ";
    $sql .= "                                    and k00_numpar = x.k12_numpar                                                                                                       ";
    $sql .= "                                  limit 1) as cgm_pago,                                                                                                                 ";
    $sql .= "                                (select k00_numcgm                                                                                                                      ";
    $sql .= "                                   from arrecad                                                                                                                         ";
    $sql .= "                                  where k00_numpre= x.k12_numpre                                                                                                        ";
    $sql .= "                                    and k00_numpar= x.k12_numpar                                                                                                        ";
    $sql .= "                                  limit 1) as cgm_estornado,                                                                                                            ";
    $sql .= "                                (select k00_numcgm                                                                                                                      ";
    $sql .= "                                   from recibo                                                                                                                          ";
    $sql .= "                                  where k00_numpre = x.k12_numnov                                                                                                       ";
    $sql .= "                                  limit 1) as cgm_recibo_avulso,               ";
    $sql .= "                                (select k12_codcla                                                                                                                      ";
    $sql .= "                                   from corcla                                                                                                                          ";
    $sql .= "                                  where k12_id     = {$iId}                                                                                                             ";
    $sql .= "                                    and k12_data   ='{$sData}'                                                                                                          ";
    $sql .= "                                    and k12_autent = {$iAutent}                                                                                                         ";
    $sql .= "                                  limit 1) as k12_codcla,                                                                                                               ";
    $sql .= "                                arrecada,                                                                                                                               ";
    $sql .= "                                estorna                                                                                                                                 ";
    $sql .= "                           from ( select corrente.k12_id,                                                                                                               ";
    $sql .= "                                         corrente.k12_autent,                                                                                                           ";
    $sql .= "                                         k12_conta,                                                                                                                     ";
    $sql .= "                                         k02_codrec,                                                                                                                    ";
    $sql .= "                                         round ( sum ( case when corrente.k12_estorn = false then cornump.k12_valor else 0::float8 end),2) as arrecada,                 ";
    $sql .= "                                         round ( sum ( case when corrente.k12_estorn = true  then cornump.k12_valor else 0::float8 end),2) as estorna,                  ";
    $sql .= "                                         k12_histcor,                                                                                                                   ";
    $sql .= "                                         cornump.k12_numpre,                                                                                                            ";
    $sql .= "                                         cornump.k12_numnov,                                                                                                            ";
    $sql .= "                                         cornump.k12_numpar                                                                                                             ";
    $sql .= "                                    from corrente                                                                                                                       ";
    $sql .= "                                         inner join cornump          on corrente.k12_id     = cornump.k12_id                                                            ";
    $sql .= "                                                                    and corrente.k12_data   = cornump.k12_data                                                          ";
    $sql .= "                                                                    and corrente.k12_autent = cornump.k12_autent                                                        ";
    $sql .= "                                         left  join corhist          on corrente.k12_id     = corhist.k12_id                                                            ";
    $sql .= "                                                                    and corrente.k12_data   = corhist.k12_data                                                          ";
    $sql .= "                                                                    and corrente.k12_autent = corhist.k12_autent                                                        ";
    $sql .= "                                         inner join tabrec           on k12_receit        = tabrec.k02_codigo                                                           ";
    $sql .= "                                         inner join taborc           on taborc.k02_codigo = tabrec.k02_codigo                                                           ";
    $sql .= "                                                                    and taborc.k02_anousu ={$iAnoSessao}                                                                         ";
    $sql .= "                                         inner join orcreceita       on orcreceita.o70_codrec = taborc.k02_codrec                                                       ";
    $sql .= "                                                                    and orcreceita.o70_anousu = taborc.k02_anousu                                                       ";
    $sql .= "                                         inner join orcfontes        on orcfontes.o57_codfon  =  orcreceita.o70_codfon                                                  ";
    $sql .= "                                                                    and orcfontes.o57_anousu  =  orcreceita.o70_anousu                                                  ";
    $sql .= "                                         inner join conplanoorcamento on conplanoorcamento.c60_codcon  = orcfontes.o57_codfon                                           ";
    $sql .= "                                                                    and conplanoorcamento.c60_anousu                    = orcfontes.o57_anousu                          ";
    $sql .= "                                         inner join conplanoconplanoorcamento  on conplanoconplanoorcamento.c72_conplanoorcamento = conplanoorcamento.c60_codcon        ";
    $sql .= "                                                                    and conplanoconplanoorcamento.c72_anousu            = conplanoorcamento.c60_anousu                  ";
    $sql .= "                                         inner join conplano         on conplano.c60_codcon     = conplanoconplanoorcamento.c72_conplano                                ";
    $sql .= "                                                                    and conplano.c60_anousu     = conplanoconplanoorcamento.c72_anousu                                  ";
    $sql .= "                                         inner join conplanoreduz    on conplano.c60_codcon     = conplanoreduz.c61_codcon                                              ";
    $sql .= "                                                                    and conplano.c60_anousu     = conplanoreduz.c61_anousu                                              ";
    $sql .= "                                         inner join conplanoexe      on conplanoreduz.c61_reduz = c62_reduz                                                             ";
    $sql .= "                                                                    and c62_anousu              = conplanoreduz.c61_anousu                                              ";
    $sql .= "                                         where corrente.k12_data        = '{$sData}'                                                                                  ";
    $sql .= "                                           and corrente.k12_id          = {$iId}                                                                                            ";
    $sql .= "                                           and corrente.k12_autent      = {$iAutent}                                                                                            ";
    $sql .= "                                           and taborc.k02_anousu        = {$iAnoSessao}                                                                                          ";
    $sql .= "                                           and conplanoreduz.c61_instit = {$iInstituicaoSessao}                                                                                             ";
    $sql .= "                                           and corhist.k12_id is null                                                                                                   ";
    $sql .= "                                         group by corrente.k12_id,                                                                                                      ";
    $sql .= "                                                  corrente.k12_autent,                                                                                                  ";
    $sql .= "                                                  corrente.k12_conta,                                                                                                   ";
    $sql .= "                                                  taborc.k02_codrec,                                                                                                    ";
    $sql .= "                                                  cornump.k12_numpre,                                                                                                   ";
    $sql .= "                                                  cornump.k12_numpar,                                                                                                   ";
    $sql .= "                                                  corhist.k12_histcor ) as x                                                                                            ";
    $sql .= "                                                  ) as xx                                                                                                               ";
    $sql .= "                                         group by k12_conta,                                                                                                            ";
    $sql .= "                                                  k02_codrec,                                                                                                           ";
    $sql .= "                                                  k12_histcor,                                                                                                          ";
    $sql .= "                                                  cgm_pago,                                                                                                             ";
    $sql .= "                                                  cgm_estornado,                                                                                                        ";
    $sql .= "                                                  k12_codcla,                                                                                                           ";
    $sql .= "                                                  k12_histcor,                                                                                                          ";
    $sql .= "                                                  k12_id,                                                                                                               ";
    $sql .= "                                                  k12_autent ) as xxx                                                                                                   ";
    $sql .= "       inner join orcreceita on o70_anousu = {$iAnoSessao} and o70_codrec = k02_codrec                                                                                           ";

    return $sql;
  }


  public function sql_query_estorno_arrecadacao_extra($iId, $dtData, $iAutent) {

    $iAnoSessao         = db_getsession("DB_anousu");
    $iInstituicaoSessao = db_getsession("DB_instit");

    $sql  = "select k12_conta,                                                                                        ";
    $sql .= "       tabrec.k02_codigo,                                                                                ";
    $sql .= "       k02_reduz,                                                                                        ";
    $sql .= "       sum( case when corrente.k12_estorn = 'f' then cornump.k12_valor else 0::float8 end) as arrecada,  ";
    $sql .= "       sum( case when corrente.k12_estorn = 't' then cornump.k12_valor*-1 else 0::float8 end) as estorna,";
    $sql .= "       k12_histcor,                                                                                      ";
    $sql .= "       0 as k12_id,                                                                                      ";
    $sql .= "       0 as k12_autent                                                                                   ";
    $sql .= "  from corrente                                                                                          ";
    $sql .= "       inner join cornump       on corrente.k12_id      = cornump.k12_id                                 ";
    $sql .= "                               and corrente.k12_data   = cornump.k12_data                                ";
    $sql .= "                               and corrente.k12_autent = cornump.k12_autent                              ";
    $sql .= "       left outer join corhist  on corrente.k12_id = corhist.k12_id                                      ";
    $sql .= "                               and corrente.k12_data   = corhist.k12_data                                ";
    $sql .= "                               and corrente.k12_autent = corhist.k12_autent                              ";
    $sql .= "       inner join tabrec        on k12_receit = tabrec.k02_codigo                                        ";
    $sql .= "       inner join tabplan       on tabplan.k02_codigo = tabrec.k02_codigo                                ";
    $sql .= "                               and k02_anousu = {$iAnoSessao}                                            ";
    $sql .= "       inner join conplanoexe   on k12_conta = c62_reduz                                                 ";
    $sql .= "                               and c62_anousu = {$iAnoSessao}                                            ";
    $sql .= "       inner join conplanoreduz on c62_reduz = c61_reduz                                                 ";
    $sql .= "                               and c61_anousu = c62_anousu                                               ";
    $sql .= "                               and c61_instit = {$iInstituicaoSessao}                                    ";
    $sql .= "       where corrente.k12_instit = {$iInstituicaoSessao}                                                 ";
    $sql .= "         and corrente.k12_data   = '{$dtData}'                                                           ";
    $sql .= "         and corrente.k12_id     = {$iId}                                                                ";
    $sql .= "         and corrente.k12_autent = {$iAutent}                                                            ";
    $sql .= "         and corhist.k12_id is null                                                                      ";
    $sql .= "       group by k12_conta,                                                                               ";
    $sql .= "                tabrec.k02_codigo,                                                                       ";
    $sql .= "                k02_reduz,                                                                               ";
    $sql .= "                k12_histcor                                                                             ";
    return $sql;
  }


  public function sql_query_arrecadacao_extra($iId, $dtData, $iAutent) {

    $iAnoSessao         = db_getsession("DB_anousu");
    $iInstituicaoSessao = db_getsession("DB_instit");
    $sql = "  select k12_conta,
                     tabrec.k02_codigo,
                     k02_reduz,
                     case when corrente.k12_estorn = 'f' then cornump.k12_valor else 0::float8 end as arrecada,
                     case when corrente.k12_estorn = 't' then cornump.k12_valor*-1 else 0::float8 end as estorna,
                     k12_histcor,
                     corrente.k12_id ,
                     corrente.k12_autent
                from corrente
                     inner join cornump  on corrente.k12_id      = cornump.k12_id
                                        and corrente.k12_data   = cornump.k12_data
                                        and corrente.k12_autent = cornump.k12_autent
                     left  join corhist  on corrente.k12_id = corhist.k12_id
                                        and corrente.k12_data   = corhist.k12_data
                                        and corrente.k12_autent = corhist.k12_autent
                     inner join tabrec   on k12_receit = tabrec.k02_codigo
                     inner join tabplan  on tabplan.k02_codigo = tabrec.k02_codigo
                                              and k02_anousu = {$iAnoSessao}
                     inner join conplanoexe    on k12_conta = c62_reduz
                                              and c62_anousu = {$iAnoSessao}
                     inner join conplanoreduz  on c62_reduz = c61_reduz
                                              and c61_anousu = c62_anousu
                                              and c61_instit = {$iInstituicaoSessao}
               where corrente.k12_instit = {$iInstituicaoSessao}
                 and corrente.k12_data   = '{$dtData}'
                 and corrente.k12_autent = {$iAutent}
                 and corrente.k12_id     = {$iId}
                 and corhist.k12_id is not null";
    return $sql;
  }





  function sql_query_autenticacao_receita( $k12_id=null,$k12_data=null,$k12_autent=null,$campos="*",$ordem=null,$dbwhere=""){
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
  	$sql.=  " from corrente                                                                       ";
  	$sql.=  " 		inner join cornump           on cornump.k12_id     = corrente.k12_id             ";
  	$sql.=  " 																and cornump.k12_data   = corrente.k12_data           ";
  	$sql.=  " 																and cornump.k12_autent = corrente.k12_autent         ";
  	$sql.=  "    inner join tabrec           on cornump.k12_receit  = tabrec.k02_codigo              ";
  	$sql.=  "    inner join taborc           on taborc.k02_codigo   = tabrec.k02_codigo              ";
  	$sql.=  "    														and taborc.k02_anousu   =".db_getsession("DB_anousu");
  	$sql.=  "    inner join orcreceita       on orcreceita.o70_anousu = taborc.k02_anousu            ";
  	$sql.=  "    														and orcreceita.o70_codrec = taborc.k02_codrec            ";
  	$sql.=  "    left join corhist          on corhist.k12_id     =  cornump.k12_id      ";
  	$sql.=  "                              and corhist.k12_data   =  cornump.k12_data    ";
  	$sql.=  "                              and corhist.k12_autent =  cornump.k12_autent  ";
  	$sql.=  "    left join arrepaga         on arrepaga.k00_numpre = cornump.k12_numpre              ";
  	$sql.=  "    													 and arrepaga.k00_numpar = cornump.k12_numpar              ";
  	$sql.=  "                              and arrepaga.k00_receit = taborc.k02_codigo               ";
  	$sql.=  "    left join arrecad           on arrecad.k00_numpre = cornump.k12_numpre              ";
  	$sql.=  "    														and arrecad.k00_numpar = cornump.k12_numpar              ";
  	$sql.=  "                              and arrecad.k00_receit = taborc.k02_codigo               ";
  	$sql.=  "    left join recibo           on recibo.k00_numpre = cornump.k12_numpre                ";
  	$sql.=  "    													 and recibo.k00_numpar = cornump.k12_numpar                ";
  	$sql.=  "                              and recibo.k00_receit = taborc.k02_codigo               ";
  	$sql.=  "    left join reciboconcarpeculiar on k130_numpre = recibo.k00_numpre                   ";
  	$sql.=  "	   														 and k130_numpar = recibo.k00_numpar                   ";
  	$sql.=  "	   														 and k130_receit = recibo.k00_receit             ";



  	$sql2 = "";
  	if($dbwhere==""){
  		if($k12_id!=null ){
  			$sql2 .= " where corrente.k12_id = $k12_id ";
  		}
  		if($k12_data!=null ){
  			if($sql2!=""){
  				$sql2 .= " and ";
  			}else{
  				$sql2 .= " where ";
  			}
  			$sql2 .= " corrente.k12_data = '$k12_data' ";
  		}
  		if($k12_autent!=null ){
  			if($sql2!=""){
  				$sql2 .= " and ";
  			}else{
  				$sql2 .= " where ";
  			}
  			$sql2 .= " corrente.k12_autent = $k12_autent ";
  		}
  	}else if($dbwhere != ""){
  		$sql2 = " where $dbwhere";
  	}
  	$sql2 .= ($sql2!=""?" and ":" where ") . " corrente.k12_instit = " . db_getsession("DB_instit");
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

  function sql_query_autenticacao_receita_desconto( $k12_id=null,$k12_data=null,$k12_autent=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql.=  " from corrente                                                                       ";
    $sql.=  "     inner join cornumpdesconto  on cornumpdesconto.k12_id     = corrente.k12_id             ";
    $sql.=  "                                 and cornumpdesconto.k12_data   = corrente.k12_data           ";
    $sql.=  "                                 and cornumpdesconto.k12_autent = corrente.k12_autent         ";
    $sql.=  "    inner join tabrec           on cornumpdesconto.k12_receit  = tabrec.k02_codigo              ";
    $sql.=  "    inner join taborc           on taborc.k02_codigo   = tabrec.k02_codigo              ";
    $sql.=  "                               and taborc.k02_anousu   =".db_getsession("DB_anousu");
    $sql.=  "    inner join orcreceita       on orcreceita.o70_anousu = taborc.k02_anousu            ";
    $sql.=  "                               and orcreceita.o70_codrec = taborc.k02_codrec            ";
    $sql.=  "    left join corhist          on corhist.k12_id     =  cornumpdesconto.k12_id      ";
    $sql.=  "                              and corhist.k12_data   =  cornumpdesconto.k12_data    ";
    $sql.=  "                              and corhist.k12_autent =  cornumpdesconto.k12_autent  ";
    $sql.=  "    left join arrepaga         on arrepaga.k00_numpre = cornumpdesconto.k12_numpre              ";
    $sql.=  "                              and arrepaga.k00_numpar = cornumpdesconto.k12_numpar              ";
    $sql.=  "                              and arrepaga.k00_receit = taborc.k02_codigo               ";
    $sql.=  "    left join arrecad           on arrecad.k00_numpre = cornumpdesconto.k12_numpre              ";
    $sql.=  "                               and arrecad.k00_numpar = cornumpdesconto.k12_numpar              ";
    $sql.=  "                              and arrecad.k00_receit = taborc.k02_codigo               ";
    $sql.=  "    left join recibo           on recibo.k00_numpre = cornumpdesconto.k12_numpre                ";
    $sql.=  "                              and recibo.k00_numpar = cornumpdesconto.k12_numpar                ";
    $sql.=  "                              and recibo.k00_receit = taborc.k02_codigo               ";
    $sql.=  "    left join reciboconcarpeculiar on k130_numpre = recibo.k00_numpre                   ";
    $sql.=  "                                and k130_numpar = recibo.k00_numpar                   ";
    $sql.=  "                                and k130_receit = recibo.k00_receit             ";



    $sql2 = "";
    if($dbwhere==""){
      if($k12_id!=null ){
        $sql2 .= " where corrente.k12_id = $k12_id ";
      }
      if($k12_data!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " corrente.k12_data = '$k12_data' ";
      }
      if($k12_autent!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " corrente.k12_autent = $k12_autent ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql2 .= ($sql2!=""?" and ":" where ") . " corrente.k12_instit = " . db_getsession("DB_instit");
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

  function sql_query_autenticacao_receita_extra( $k12_id=null,$k12_data=null,$k12_autent=null,$campos="*",$ordem=null,$dbwhere=""){
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

    $iAnoSessao         = db_getsession("DB_anousu");
    $iInstituicaoSessao = db_getsession("DB_instit");
    $sql .= " from corrente                                                                ";
    $sql .= "      inner join cornump               on corrente.k12_id     = cornump.k12_id       ";
    $sql .= "                                      and corrente.k12_data   = cornump.k12_data     ";
    $sql .= "                                      and corrente.k12_autent = cornump.k12_autent   ";
    $sql .= "      inner join tabrec                on k12_receit          = tabrec.k02_codigo    ";
    $sql .= "      inner join tabplan               on tabplan.k02_codigo  = tabrec.k02_codigo    ";
    $sql .= "                                      and k02_anousu          = {$iAnoSessao}        ";
    $sql .= "      inner join conplanoexe           on k12_conta           = c62_reduz            ";
    $sql .= "                                      and c62_anousu          = {$iAnoSessao}        ";
    $sql .= "      inner join conplanoreduz         on c62_reduz           = c61_reduz            ";
    $sql .= "                                      and c61_anousu          = c62_anousu           ";
    $sql .= "                                        and c61_instit        = {$iInstituicaoSessao}";
    $sql .= "      left  join corhist               on corrente.k12_id     = corhist.k12_id       ";
    $sql .= "                                      and corrente.k12_data   = corhist.k12_data     ";
    $sql .= "                                      and corrente.k12_autent = corhist.k12_autent   ";
    $sql .= "      left  join recibo                on recibo.k00_numpre   = cornump.k12_numpre     ";
    $sql .= "                                      and recibo.k00_numpar   = cornump.k12_numpar     ";
    $sql .= "                                      and recibo.k00_receit   = cornump.k12_receit     ";
    $sql .= "      left  join empprestarecibo       on empprestarecibo.e170_numpre = recibo.k00_numpre ";
    $sql .= "                                      and empprestarecibo.e170_numpar = recibo.k00_numpar ";
    $sql .= "      left  join emppresta             on emppresta.e45_sequencial = empprestarecibo.e170_emppresta ";
    $sql .= "      left  join reciboconcarpeculiar  on reciboconcarpeculiar.k130_numpre = recibo.k00_numpre ";
    $sql .= "                                      and reciboconcarpeculiar.k130_numpar = recibo.k00_numpar ";
    $sql .= "                                      and reciboconcarpeculiar.k130_receit = recibo.k00_receit ";



    $sql2 = "";
    if($dbwhere==""){
      if($k12_id!=null ){
        $sql2 .= " where corrente.k12_id = $k12_id ";
      }
      if($k12_data!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " corrente.k12_data = '$k12_data' ";
      }
      if($k12_autent!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " corrente.k12_autent = $k12_autent ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql2 .= ($sql2!=""?" and ":" where ") . " corrente.k12_instit = " . db_getsession("DB_instit");
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

  function sql_query_autenticacao_receita_extra_planilha( $k12_id=null,$k12_data=null,$k12_autent=null,$campos="*",$ordem=null,$dbwhere=""){
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

    $iAnoSessao         = db_getsession("DB_anousu");
    $iInstituicaoSessao = db_getsession("DB_instit");
    $sql .= " from corrente                                                                ";
    $sql .= "      inner join cornump               on corrente.k12_id     = cornump.k12_id       ";
    $sql .= "                                      and corrente.k12_data   = cornump.k12_data     ";
    $sql .= "                                      and corrente.k12_autent = cornump.k12_autent   ";
    $sql .= "      inner join tabrec                on k12_receit          = tabrec.k02_codigo    ";
    $sql .= "      inner join tabplan               on tabplan.k02_codigo  = tabrec.k02_codigo    ";
    $sql .= "                                      and k02_anousu          = {$iAnoSessao}        ";
    $sql .= "      inner join conplanoexe           on k12_conta           = c62_reduz            ";
    $sql .= "                                      and c62_anousu          = {$iAnoSessao}        ";
    $sql .= "      inner join conplanoreduz         on c62_reduz           = c61_reduz            ";
    $sql .= "                                      and c61_anousu          = c62_anousu           ";
    $sql .= "                                        and c61_instit        = {$iInstituicaoSessao}";
    $sql .= "      left  join corhist               on corrente.k12_id     = corhist.k12_id       ";
    $sql .= "                                      and corrente.k12_data   = corhist.k12_data     ";
    $sql .= "                                      and corrente.k12_autent = corhist.k12_autent   ";
    $sql .= "      inner join corplacaixa           on corplacaixa.k82_id     = cornump.k12_id       ";
    $sql .= "                                      and corplacaixa.k82_data   = cornump.k12_data     ";
    $sql .= "                                      and corplacaixa.k82_autent = cornump.k12_autent   ";
    $sql .= "      inner join placaixarec           on placaixarec.k81_seqpla = corplacaixa.k82_seqpla ";
    $sql .= "      inner join placaixa              on placaixa.k80_codpla    = placaixarec.k81_codpla ";

    $sql2 = "";
    if($dbwhere==""){
      if($k12_id!=null ){
        $sql2 .= " where corrente.k12_id = $k12_id ";
      }
      if($k12_data!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " corrente.k12_data = '$k12_data' ";
      }
      if($k12_autent!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " corrente.k12_autent = $k12_autent ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql2 .= ($sql2!=""?" and ":" where ") . " corrente.k12_instit = " . db_getsession("DB_instit");
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