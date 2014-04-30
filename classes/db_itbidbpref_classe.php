<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: itbi
//CLASSE DA ENTIDADE itbidbpref
class cl_itbidbpref { 
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
   var $it23_sequencial = 0; 
   var $it23_itbi = 0; 
   var $it23_data_dia = null; 
   var $it23_data_mes = null; 
   var $it23_data_ano = null; 
   var $it23_data = null; 
   var $it23_hora = null; 
   var $it23_ip = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 it23_sequencial = int4 = codigo identificador 
                 it23_itbi = int4 = codigo da ITBI 
                 it23_data = date = Data 
                 it23_hora = char(5) = Hora 
                 it23_ip = varchar(20) = IP 
                 ";
   //funcao construtor da classe 
   function cl_itbidbpref() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("itbidbpref"); 
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
       $this->it23_sequencial = ($this->it23_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["it23_sequencial"]:$this->it23_sequencial);
       $this->it23_itbi = ($this->it23_itbi == ""?@$GLOBALS["HTTP_POST_VARS"]["it23_itbi"]:$this->it23_itbi);
       if($this->it23_data == ""){
         $this->it23_data_dia = ($this->it23_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["it23_data_dia"]:$this->it23_data_dia);
         $this->it23_data_mes = ($this->it23_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["it23_data_mes"]:$this->it23_data_mes);
         $this->it23_data_ano = ($this->it23_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["it23_data_ano"]:$this->it23_data_ano);
         if($this->it23_data_dia != ""){
            $this->it23_data = $this->it23_data_ano."-".$this->it23_data_mes."-".$this->it23_data_dia;
         }
       }
       $this->it23_hora = ($this->it23_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["it23_hora"]:$this->it23_hora);
       $this->it23_ip = ($this->it23_ip == ""?@$GLOBALS["HTTP_POST_VARS"]["it23_ip"]:$this->it23_ip);
     }else{
       $this->it23_sequencial = ($this->it23_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["it23_sequencial"]:$this->it23_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($it23_sequencial){ 
      $this->atualizacampos();
     if($this->it23_itbi == null ){ 
       $this->erro_sql = " Campo codigo da ITBI nao Informado.";
       $this->erro_campo = "it23_itbi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it23_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "it23_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it23_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "it23_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it23_ip == null ){ 
       $this->erro_sql = " Campo IP nao Informado.";
       $this->erro_campo = "it23_ip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($it23_sequencial == "" || $it23_sequencial == null ){
       $result = db_query("select nextval('itbidbpref_it23_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: itbidbpref_it23_sequencial_seq do campo: it23_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->it23_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from itbidbpref_it23_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $it23_sequencial)){
         $this->erro_sql = " Campo it23_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->it23_sequencial = $it23_sequencial; 
       }
     }
     if(($this->it23_sequencial == null) || ($this->it23_sequencial == "") ){ 
       $this->erro_sql = " Campo it23_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into itbidbpref(
                                       it23_sequencial 
                                      ,it23_itbi 
                                      ,it23_data 
                                      ,it23_hora 
                                      ,it23_ip 
                       )
                values (
                                $this->it23_sequencial 
                               ,$this->it23_itbi 
                               ,".($this->it23_data == "null" || $this->it23_data == ""?"null":"'".$this->it23_data."'")." 
                               ,'$this->it23_hora' 
                               ,'$this->it23_ip' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "ITBI pelo site ($this->it23_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "ITBI pelo site já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "ITBI pelo site ($this->it23_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it23_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->it23_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9625,'$this->it23_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1656,9625,'','".AddSlashes(pg_result($resaco,0,'it23_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1656,9629,'','".AddSlashes(pg_result($resaco,0,'it23_itbi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1656,9626,'','".AddSlashes(pg_result($resaco,0,'it23_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1656,9627,'','".AddSlashes(pg_result($resaco,0,'it23_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1656,9628,'','".AddSlashes(pg_result($resaco,0,'it23_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($it23_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update itbidbpref set ";
     $virgula = "";
     if(trim($this->it23_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it23_sequencial"])){ 
       $sql  .= $virgula." it23_sequencial = $this->it23_sequencial ";
       $virgula = ",";
       if(trim($this->it23_sequencial) == null ){ 
         $this->erro_sql = " Campo codigo identificador nao Informado.";
         $this->erro_campo = "it23_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it23_itbi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it23_itbi"])){ 
       $sql  .= $virgula." it23_itbi = $this->it23_itbi ";
       $virgula = ",";
       if(trim($this->it23_itbi) == null ){ 
         $this->erro_sql = " Campo codigo da ITBI nao Informado.";
         $this->erro_campo = "it23_itbi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it23_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it23_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["it23_data_dia"] !="") ){ 
       $sql  .= $virgula." it23_data = '$this->it23_data' ";
       $virgula = ",";
       if(trim($this->it23_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "it23_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["it23_data_dia"])){ 
         $sql  .= $virgula." it23_data = null ";
         $virgula = ",";
         if(trim($this->it23_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "it23_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->it23_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it23_hora"])){ 
       $sql  .= $virgula." it23_hora = '$this->it23_hora' ";
       $virgula = ",";
       if(trim($this->it23_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "it23_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it23_ip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it23_ip"])){ 
       $sql  .= $virgula." it23_ip = '$this->it23_ip' ";
       $virgula = ",";
       if(trim($this->it23_ip) == null ){ 
         $this->erro_sql = " Campo IP nao Informado.";
         $this->erro_campo = "it23_ip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($it23_sequencial!=null){
       $sql .= " it23_sequencial = $this->it23_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->it23_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9625,'$this->it23_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it23_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1656,9625,'".AddSlashes(pg_result($resaco,$conresaco,'it23_sequencial'))."','$this->it23_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it23_itbi"]))
           $resac = db_query("insert into db_acount values($acount,1656,9629,'".AddSlashes(pg_result($resaco,$conresaco,'it23_itbi'))."','$this->it23_itbi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it23_data"]))
           $resac = db_query("insert into db_acount values($acount,1656,9626,'".AddSlashes(pg_result($resaco,$conresaco,'it23_data'))."','$this->it23_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it23_hora"]))
           $resac = db_query("insert into db_acount values($acount,1656,9627,'".AddSlashes(pg_result($resaco,$conresaco,'it23_hora'))."','$this->it23_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it23_ip"]))
           $resac = db_query("insert into db_acount values($acount,1656,9628,'".AddSlashes(pg_result($resaco,$conresaco,'it23_ip'))."','$this->it23_ip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ITBI pelo site nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->it23_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ITBI pelo site nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->it23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($it23_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($it23_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9625,'$it23_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1656,9625,'','".AddSlashes(pg_result($resaco,$iresaco,'it23_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1656,9629,'','".AddSlashes(pg_result($resaco,$iresaco,'it23_itbi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1656,9626,'','".AddSlashes(pg_result($resaco,$iresaco,'it23_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1656,9627,'','".AddSlashes(pg_result($resaco,$iresaco,'it23_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1656,9628,'','".AddSlashes(pg_result($resaco,$iresaco,'it23_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from itbidbpref
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($it23_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " it23_sequencial = $it23_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ITBI pelo site nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$it23_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ITBI pelo site nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$it23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$it23_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:itbidbpref";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>