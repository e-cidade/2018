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
//CLASSE DA ENTIDADE corplacaixa
class cl_corplacaixa {
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
   var $k82_id = 0;
   var $k82_data_dia = null;
   var $k82_data_mes = null;
   var $k82_data_ano = null;
   var $k82_data = null;
   var $k82_autent = 0;
   var $k82_seqpla = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 k82_id = int4 = Autenticação
                 k82_data = date = Data Autenticação
                 k82_autent = int4 = Código Autenticação
                 k82_seqpla = int4 = PLanilha
                 ";
   //funcao construtor da classe
   function cl_corplacaixa() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("corplacaixa");
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
       $this->k82_id = ($this->k82_id == ""?@$GLOBALS["HTTP_POST_VARS"]["k82_id"]:$this->k82_id);
       if($this->k82_data == ""){
         $this->k82_data_dia = ($this->k82_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k82_data_dia"]:$this->k82_data_dia);
         $this->k82_data_mes = ($this->k82_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k82_data_mes"]:$this->k82_data_mes);
         $this->k82_data_ano = ($this->k82_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k82_data_ano"]:$this->k82_data_ano);
         if($this->k82_data_dia != ""){
            $this->k82_data = $this->k82_data_ano."-".$this->k82_data_mes."-".$this->k82_data_dia;
         }
       }
       $this->k82_autent = ($this->k82_autent == ""?@$GLOBALS["HTTP_POST_VARS"]["k82_autent"]:$this->k82_autent);
       $this->k82_seqpla = ($this->k82_seqpla == ""?@$GLOBALS["HTTP_POST_VARS"]["k82_seqpla"]:$this->k82_seqpla);
     }else{
       $this->k82_id = ($this->k82_id == ""?@$GLOBALS["HTTP_POST_VARS"]["k82_id"]:$this->k82_id);
       $this->k82_data = ($this->k82_data == ""?@$GLOBALS["HTTP_POST_VARS"]["k82_data_ano"]."-".@$GLOBALS["HTTP_POST_VARS"]["k82_data_mes"]."-".@$GLOBALS["HTTP_POST_VARS"]["k82_data_dia"]:$this->k82_data);
       $this->k82_autent = ($this->k82_autent == ""?@$GLOBALS["HTTP_POST_VARS"]["k82_autent"]:$this->k82_autent);
     }
   }
   // funcao para inclusao
   function incluir ($k82_id,$k82_data,$k82_autent){
      $this->atualizacampos();
     if($this->k82_seqpla == null ){
       $this->erro_sql = " Campo PLanilha nao Informado.";
       $this->erro_campo = "k82_seqpla";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->k82_id = $k82_id;
       $this->k82_data = $k82_data;
       $this->k82_autent = $k82_autent;
     if(($this->k82_id == null) || ($this->k82_id == "") ){
       $this->erro_sql = " Campo k82_id nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k82_data == null) || ($this->k82_data == "") ){
       $this->erro_sql = " Campo k82_data nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k82_autent == null) || ($this->k82_autent == "") ){
       $this->erro_sql = " Campo k82_autent nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into corplacaixa(
                                       k82_id
                                      ,k82_data
                                      ,k82_autent
                                      ,k82_seqpla
                       )
                values (
                                $this->k82_id
                               ,".($this->k82_data == "null" || $this->k82_data == ""?"null":"'".$this->k82_data."'")."
                               ,$this->k82_autent
                               ,$this->k82_seqpla
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Autenticacao da planilha ($this->k82_id."-".$this->k82_data."-".$this->k82_autent) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Autenticacao da planilha já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Autenticacao da planilha ($this->k82_id."-".$this->k82_data."-".$this->k82_autent) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k82_id."-".$this->k82_data."-".$this->k82_autent;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k82_id,$this->k82_data,$this->k82_autent));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6298,'$this->k82_id','I')");
       $resac = db_query("insert into db_acountkey values($acount,6297,'$this->k82_data','I')");
       $resac = db_query("insert into db_acountkey values($acount,6296,'$this->k82_autent','I')");
       $resac = db_query("insert into db_acount values($acount,1025,6298,'','".AddSlashes(pg_result($resaco,0,'k82_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1025,6297,'','".AddSlashes(pg_result($resaco,0,'k82_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1025,6296,'','".AddSlashes(pg_result($resaco,0,'k82_autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1025,6299,'','".AddSlashes(pg_result($resaco,0,'k82_seqpla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($k82_id=null,$k82_data=null,$k82_autent=null) {
      $this->atualizacampos();
     $sql = " update corplacaixa set ";
     $virgula = "";
     if(trim($this->k82_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k82_id"])){
       $sql  .= $virgula." k82_id = $this->k82_id ";
       $virgula = ",";
       if(trim($this->k82_id) == null ){
         $this->erro_sql = " Campo Autenticação nao Informado.";
         $this->erro_campo = "k82_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k82_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k82_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k82_data_dia"] !="") ){
       $sql  .= $virgula." k82_data = '$this->k82_data' ";
       $virgula = ",";
       if(trim($this->k82_data) == null ){
         $this->erro_sql = " Campo Data Autenticação nao Informado.";
         $this->erro_campo = "k82_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["k82_data_dia"])){
         $sql  .= $virgula." k82_data = null ";
         $virgula = ",";
         if(trim($this->k82_data) == null ){
           $this->erro_sql = " Campo Data Autenticação nao Informado.";
           $this->erro_campo = "k82_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k82_autent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k82_autent"])){
       $sql  .= $virgula." k82_autent = $this->k82_autent ";
       $virgula = ",";
       if(trim($this->k82_autent) == null ){
         $this->erro_sql = " Campo Código Autenticação nao Informado.";
         $this->erro_campo = "k82_autent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k82_seqpla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k82_seqpla"])){
       $sql  .= $virgula." k82_seqpla = $this->k82_seqpla ";
       $virgula = ",";
       if(trim($this->k82_seqpla) == null ){
         $this->erro_sql = " Campo PLanilha nao Informado.";
         $this->erro_campo = "k82_seqpla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k82_id!=null){
       $sql .= " k82_id = $this->k82_id";
     }
     if($k82_data!=null){
       $sql .= " and  k82_data = '$this->k82_data'";
     }
     if($k82_autent!=null){
       $sql .= " and  k82_autent = $this->k82_autent";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k82_id,$this->k82_data,$this->k82_autent));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6298,'$this->k82_id','A')");
         $resac = db_query("insert into db_acountkey values($acount,6297,'$this->k82_data','A')");
         $resac = db_query("insert into db_acountkey values($acount,6296,'$this->k82_autent','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k82_id"]))
           $resac = db_query("insert into db_acount values($acount,1025,6298,'".AddSlashes(pg_result($resaco,$conresaco,'k82_id'))."','$this->k82_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k82_data"]))
           $resac = db_query("insert into db_acount values($acount,1025,6297,'".AddSlashes(pg_result($resaco,$conresaco,'k82_data'))."','$this->k82_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k82_autent"]))
           $resac = db_query("insert into db_acount values($acount,1025,6296,'".AddSlashes(pg_result($resaco,$conresaco,'k82_autent'))."','$this->k82_autent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k82_seqpla"]))
           $resac = db_query("insert into db_acount values($acount,1025,6299,'".AddSlashes(pg_result($resaco,$conresaco,'k82_seqpla'))."','$this->k82_seqpla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Autenticacao da planilha nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k82_id."-".$this->k82_data."-".$this->k82_autent;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Autenticacao da planilha nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k82_id."-".$this->k82_data."-".$this->k82_autent;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k82_id."-".$this->k82_data."-".$this->k82_autent;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($k82_id=null,$k82_data=null,$k82_autent=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k82_id,$k82_data,$k82_autent));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6298,'$k82_id','E')");
         $resac = db_query("insert into db_acountkey values($acount,6297,'$k82_data','E')");
         $resac = db_query("insert into db_acountkey values($acount,6296,'$k82_autent','E')");
         $resac = db_query("insert into db_acount values($acount,1025,6298,'','".AddSlashes(pg_result($resaco,$iresaco,'k82_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1025,6297,'','".AddSlashes(pg_result($resaco,$iresaco,'k82_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1025,6296,'','".AddSlashes(pg_result($resaco,$iresaco,'k82_autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1025,6299,'','".AddSlashes(pg_result($resaco,$iresaco,'k82_seqpla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from corplacaixa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k82_id != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k82_id = $k82_id ";
        }
        if($k82_data != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k82_data = '$k82_data' ";
        }
        if($k82_autent != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k82_autent = $k82_autent ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Autenticacao da planilha nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k82_id."-".$k82_data."-".$k82_autent;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Autenticacao da planilha nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k82_id."-".$k82_data."-".$k82_autent;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k82_id."-".$k82_data."-".$k82_autent;
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
        $this->erro_sql   = "Record Vazio na Tabela:corplacaixa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k82_id=null,$k82_data=null,$k82_autent=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from corplacaixa ";
     $sql .= "      inner join corrente  on  corrente.k12_id = corplacaixa.k82_id and  corrente.k12_data = corplacaixa.k82_data and  corrente.k12_autent = corplacaixa.k82_autent";
     $sql .= "      inner join placaixarec  on  placaixarec.k81_seqpla = corplacaixa.k82_seqpla";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = placaixarec.k81_receita";
     $sql .= "      inner join saltes  on  saltes.k13_conta = placaixarec.k81_conta";
     $sql .= "      inner join placaixa  as a on   a.k80_codpla = placaixarec.k81_codpla";
     $sql2 = "";
     if($dbwhere==""){
       if($k82_id!=null ){
         $sql2 .= " where corplacaixa.k82_id = $k82_id ";
       }
       if($k82_data!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " corplacaixa.k82_data = '$k82_data' ";
       }
       if($k82_autent!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " corplacaixa.k82_autent = $k82_autent ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql2 .= ($sql2!=""?" and ":" where ") . " k12_instit = " . db_getsession("DB_instit");
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
   function sql_query_file ( $k82_id=null,$k82_data=null,$k82_autent=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from corplacaixa ";
     $sql .= "      inner join corrente  on  corrente.k12_id = corplacaixa.k82_id and  corrente.k12_data = corplacaixa.k82_data and  corrente.k12_autent = corplacaixa.k82_autent";
     $sql2 = "";
     if($dbwhere==""){
       if($k82_id!=null ){
         $sql2 .= " where corplacaixa.k82_id = $k82_id ";
       }
       if($k82_data!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " corplacaixa.k82_data = '$k82_data' ";
       }
       if($k82_autent!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " corplacaixa.k82_autent = $k82_autent ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql2 .= ($sql2!=""?" and ":" where ") . " k12_instit = " . db_getsession("DB_instit");
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


  function sql_query_planilha_receita ( $k82_id=null,$k82_data=null,$k82_autent=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from corplacaixa ";
    $sql .= "      inner join corrente  on  corrente.k12_id = corplacaixa.k82_id and  corrente.k12_data = corplacaixa.k82_data and  corrente.k12_autent = corplacaixa.k82_autent";
    $sql .= "      inner join placaixarec  on  placaixarec.k81_seqpla = corplacaixa.k82_seqpla";
    $sql .= "      inner join placaixa  as a on   a.k80_codpla = placaixarec.k81_codpla";
    $sql2 = "";
    if($dbwhere==""){
      if($k82_id!=null ){
        $sql2 .= " where corplacaixa.k82_id = $k82_id ";
      }
      if($k82_data!=null ){
      if($sql2!=""){
      $sql2 .= " and ";
      }else{
          $sql2 .= " where ";
          }
          $sql2 .= " corplacaixa.k82_data = '$k82_data' ";
  }
  if($k82_autent!=null ){
          if($sql2!=""){
          $sql2 .= " and ";
          }else{
          $sql2 .= " where ";
          }
          $sql2 .= " corplacaixa.k82_autent = $k82_autent ";
  }
  }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
  }
  $sql2 .= ($sql2!=""?" and ":" where ") . " k12_instit = " . db_getsession("DB_instit");
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