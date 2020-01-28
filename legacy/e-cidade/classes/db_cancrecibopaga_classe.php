<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: arrecadacao
//CLASSE DA ENTIDADE cancrecibopaga
class cl_cancrecibopaga { 
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
   var $k134_sequencial = 0; 
   var $k134_numnov = 0; 
   var $k134_motivo = null; 
   var $k134_usuario = 0; 
   var $k134_data_dia = null; 
   var $k134_data_mes = null; 
   var $k134_data_ano = null; 
   var $k134_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k134_sequencial = int4 = Sequencial 
                 k134_numnov = int8 = Numnov 
                 k134_motivo = text = Motivo 
                 k134_usuario = int4 = Usuario 
                 k134_data = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_cancrecibopaga() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cancrecibopaga"); 
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
       $this->k134_sequencial = ($this->k134_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k134_sequencial"]:$this->k134_sequencial);
       $this->k134_numnov = ($this->k134_numnov == ""?@$GLOBALS["HTTP_POST_VARS"]["k134_numnov"]:$this->k134_numnov);
       $this->k134_motivo = ($this->k134_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["k134_motivo"]:$this->k134_motivo);
       $this->k134_usuario = ($this->k134_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k134_usuario"]:$this->k134_usuario);
       if($this->k134_data == ""){
         $this->k134_data_dia = ($this->k134_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k134_data_dia"]:$this->k134_data_dia);
         $this->k134_data_mes = ($this->k134_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k134_data_mes"]:$this->k134_data_mes);
         $this->k134_data_ano = ($this->k134_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k134_data_ano"]:$this->k134_data_ano);
         if($this->k134_data_dia != ""){
            $this->k134_data = $this->k134_data_ano."-".$this->k134_data_mes."-".$this->k134_data_dia;
         }
       }
     }else{
       $this->k134_sequencial = ($this->k134_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k134_sequencial"]:$this->k134_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k134_sequencial){ 
      $this->atualizacampos();
     if($this->k134_numnov == null ){ 
       $this->erro_sql = " Campo Numnov nao Informado.";
       $this->erro_campo = "k134_numnov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k134_motivo == null ){ 
       $this->erro_sql = " Campo Motivo nao Informado.";
       $this->erro_campo = "k134_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k134_usuario == null ){ 
       $this->erro_sql = " Campo Usuario nao Informado.";
       $this->erro_campo = "k134_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k134_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "k134_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k134_sequencial == "" || $k134_sequencial == null ){
       $result = db_query("select nextval('cancrecibopaga_k134_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cancrecibopaga_k134_sequencial_seq do campo: k134_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k134_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cancrecibopaga_k134_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k134_sequencial)){
         $this->erro_sql = " Campo k134_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k134_sequencial = $k134_sequencial; 
       }
     }
     if(($this->k134_sequencial == null) || ($this->k134_sequencial == "") ){ 
       $this->erro_sql = " Campo k134_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cancrecibopaga(
                                       k134_sequencial 
                                      ,k134_numnov 
                                      ,k134_motivo 
                                      ,k134_usuario 
                                      ,k134_data 
                       )
                values (
                                $this->k134_sequencial 
                               ,$this->k134_numnov 
                               ,'$this->k134_motivo' 
                               ,$this->k134_usuario 
                               ,".($this->k134_data == "null" || $this->k134_data == ""?"null":"'".$this->k134_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Recibos da CGF Cancelados ($this->k134_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Recibos da CGF Cancelados já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Recibos da CGF Cancelados ($this->k134_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k134_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k134_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18380,'$this->k134_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3254,18380,'','".AddSlashes(pg_result($resaco,0,'k134_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3254,18381,'','".AddSlashes(pg_result($resaco,0,'k134_numnov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3254,18382,'','".AddSlashes(pg_result($resaco,0,'k134_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3254,18383,'','".AddSlashes(pg_result($resaco,0,'k134_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3254,18384,'','".AddSlashes(pg_result($resaco,0,'k134_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k134_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cancrecibopaga set ";
     $virgula = "";
     if(trim($this->k134_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k134_sequencial"])){ 
       $sql  .= $virgula." k134_sequencial = $this->k134_sequencial ";
       $virgula = ",";
       if(trim($this->k134_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k134_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k134_numnov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k134_numnov"])){ 
       $sql  .= $virgula." k134_numnov = $this->k134_numnov ";
       $virgula = ",";
       if(trim($this->k134_numnov) == null ){ 
         $this->erro_sql = " Campo Numnov nao Informado.";
         $this->erro_campo = "k134_numnov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k134_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k134_motivo"])){ 
       $sql  .= $virgula." k134_motivo = '$this->k134_motivo' ";
       $virgula = ",";
       if(trim($this->k134_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo nao Informado.";
         $this->erro_campo = "k134_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k134_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k134_usuario"])){ 
       $sql  .= $virgula." k134_usuario = $this->k134_usuario ";
       $virgula = ",";
       if(trim($this->k134_usuario) == null ){ 
         $this->erro_sql = " Campo Usuario nao Informado.";
         $this->erro_campo = "k134_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k134_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k134_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k134_data_dia"] !="") ){ 
       $sql  .= $virgula." k134_data = '$this->k134_data' ";
       $virgula = ",";
       if(trim($this->k134_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "k134_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k134_data_dia"])){ 
         $sql  .= $virgula." k134_data = null ";
         $virgula = ",";
         if(trim($this->k134_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "k134_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($k134_sequencial!=null){
       $sql .= " k134_sequencial = $this->k134_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k134_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18380,'$this->k134_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k134_sequencial"]) || $this->k134_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3254,18380,'".AddSlashes(pg_result($resaco,$conresaco,'k134_sequencial'))."','$this->k134_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k134_numnov"]) || $this->k134_numnov != "")
           $resac = db_query("insert into db_acount values($acount,3254,18381,'".AddSlashes(pg_result($resaco,$conresaco,'k134_numnov'))."','$this->k134_numnov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k134_motivo"]) || $this->k134_motivo != "")
           $resac = db_query("insert into db_acount values($acount,3254,18382,'".AddSlashes(pg_result($resaco,$conresaco,'k134_motivo'))."','$this->k134_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k134_usuario"]) || $this->k134_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3254,18383,'".AddSlashes(pg_result($resaco,$conresaco,'k134_usuario'))."','$this->k134_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k134_data"]) || $this->k134_data != "")
           $resac = db_query("insert into db_acount values($acount,3254,18384,'".AddSlashes(pg_result($resaco,$conresaco,'k134_data'))."','$this->k134_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Recibos da CGF Cancelados nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k134_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Recibos da CGF Cancelados nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k134_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k134_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k134_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k134_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18380,'$k134_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3254,18380,'','".AddSlashes(pg_result($resaco,$iresaco,'k134_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3254,18381,'','".AddSlashes(pg_result($resaco,$iresaco,'k134_numnov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3254,18382,'','".AddSlashes(pg_result($resaco,$iresaco,'k134_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3254,18383,'','".AddSlashes(pg_result($resaco,$iresaco,'k134_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3254,18384,'','".AddSlashes(pg_result($resaco,$iresaco,'k134_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cancrecibopaga
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k134_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k134_sequencial = $k134_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Recibos da CGF Cancelados nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k134_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Recibos da CGF Cancelados nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k134_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k134_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cancrecibopaga";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k134_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cancrecibopaga ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = cancrecibopaga.k134_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($k134_sequencial!=null ){
         $sql2 .= " where cancrecibopaga.k134_sequencial = $k134_sequencial "; 
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
   function sql_query_file ( $k134_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cancrecibopaga ";
     $sql2 = "";
     if($dbwhere==""){
       if($k134_sequencial!=null ){
         $sql2 .= " where cancrecibopaga.k134_sequencial = $k134_sequencial "; 
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
}
?>