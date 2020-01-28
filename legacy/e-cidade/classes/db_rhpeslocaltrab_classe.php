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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhpeslocaltrab
class cl_rhpeslocaltrab { 
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
   var $rh56_seq = 0; 
   var $rh56_seqpes = 0; 
   var $rh56_localtrab = 0; 
   var $rh56_princ = 'f'; 
   var $rh56_quantidadecusto = 0; 
   var $rh56_percentualcusto = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh56_seq = int8 = Sequencial 
                 rh56_seqpes = int4 = Sequência 
                 rh56_localtrab = int4 = Código 
                 rh56_princ = bool = Local Principal 
                 rh56_quantidadecusto = float8 = Quantidade 
                 rh56_percentualcusto = float8 = Percentual 
                 ";
   //funcao construtor da classe 
   function cl_rhpeslocaltrab() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhpeslocaltrab"); 
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
       $this->rh56_seq = ($this->rh56_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["rh56_seq"]:$this->rh56_seq);
       $this->rh56_seqpes = ($this->rh56_seqpes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh56_seqpes"]:$this->rh56_seqpes);
       $this->rh56_localtrab = ($this->rh56_localtrab == ""?@$GLOBALS["HTTP_POST_VARS"]["rh56_localtrab"]:$this->rh56_localtrab);
       $this->rh56_princ = ($this->rh56_princ == ""?@$GLOBALS["HTTP_POST_VARS"]["rh56_princ"]:$this->rh56_princ);
       $this->rh56_quantidadecusto = ($this->rh56_quantidadecusto == ""?@$GLOBALS["HTTP_POST_VARS"]["rh56_quantidadecusto"]:$this->rh56_quantidadecusto);
       $this->rh56_percentualcusto = ($this->rh56_percentualcusto == ""?@$GLOBALS["HTTP_POST_VARS"]["rh56_percentualcusto"]:$this->rh56_percentualcusto);
     }else{
       $this->rh56_seq = ($this->rh56_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["rh56_seq"]:$this->rh56_seq);
     }
   }
   // funcao para inclusao
   function incluir ($rh56_seq){ 
      $this->atualizacampos();
     if($this->rh56_seqpes == null ){ 
       $this->erro_sql = " Campo Sequência nao Informado.";
       $this->erro_campo = "rh56_seqpes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh56_localtrab == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "rh56_localtrab";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     
     if($this->rh56_princ == null ){ 
       $this->erro_sql = " Campo Local Principal nao Informado.";
       $this->erro_campo = "rh56_princ";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh56_quantidadecusto == null ){ 
       $this->rh56_quantidadecusto = "0";
     }
     if($this->rh56_percentualcusto == null ){ 
       $this->rh56_percentualcusto = "0";
     }
     if($rh56_seq == "" || $rh56_seq == null ){
       $result = db_query("select nextval('rhpeslocaltrab_rh56_seq_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhpeslocaltrab_rh56_seq_seq do campo: rh56_seq"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh56_seq = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhpeslocaltrab_rh56_seq_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh56_seq)){
         $this->erro_sql = " Campo rh56_seq maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh56_seq = $rh56_seq; 
       }
     }
     if(($this->rh56_seq == null) || ($this->rh56_seq == "") ){ 
       $this->erro_sql = " Campo rh56_seq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpeslocaltrab(
                                       rh56_seq 
                                      ,rh56_seqpes 
                                      ,rh56_localtrab 
                                      ,rh56_princ 
                                      ,rh56_quantidadecusto 
                                      ,rh56_percentualcusto 
                       )
                values (
                                $this->rh56_seq 
                               ,$this->rh56_seqpes 
                               ,$this->rh56_localtrab 
                               ,'$this->rh56_princ' 
                               ,$this->rh56_quantidadecusto 
                               ,$this->rh56_percentualcusto 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Local de trabalho dos funcionários ($this->rh56_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Local de trabalho dos funcionários já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Local de trabalho dos funcionários ($this->rh56_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh56_seq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh56_seq));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9041,'$this->rh56_seq','I')");
       $resac = db_query("insert into db_acount values($acount,1543,9041,'','".AddSlashes(pg_result($resaco,0,'rh56_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1543,9017,'','".AddSlashes(pg_result($resaco,0,'rh56_seqpes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1543,9018,'','".AddSlashes(pg_result($resaco,0,'rh56_localtrab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1543,9042,'','".AddSlashes(pg_result($resaco,0,'rh56_princ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1543,15046,'','".AddSlashes(pg_result($resaco,0,'rh56_quantidadecusto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1543,15047,'','".AddSlashes(pg_result($resaco,0,'rh56_percentualcusto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh56_seq=null) { 
      $this->atualizacampos();
     $sql = " update rhpeslocaltrab set ";
     $virgula = "";
     if(trim($this->rh56_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh56_seq"])){ 
       $sql  .= $virgula." rh56_seq = $this->rh56_seq ";
       $virgula = ",";
       if(trim($this->rh56_seq) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh56_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh56_seqpes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh56_seqpes"])){ 
       $sql  .= $virgula." rh56_seqpes = $this->rh56_seqpes ";
       $virgula = ",";
       if(trim($this->rh56_seqpes) == null ){ 
         $this->erro_sql = " Campo Sequência nao Informado.";
         $this->erro_campo = "rh56_seqpes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh56_localtrab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh56_localtrab"])){ 
       $sql  .= $virgula." rh56_localtrab = $this->rh56_localtrab ";
       $virgula = ",";
       if(trim($this->rh56_localtrab) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "rh56_localtrab";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh56_princ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh56_princ"])){ 
       $sql  .= $virgula." rh56_princ = '$this->rh56_princ' ";
       $virgula = ",";
       if(trim($this->rh56_princ) == null ){ 
         $this->erro_sql = " Campo Local Principal nao Informado.";
         $this->erro_campo = "rh56_princ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh56_quantidadecusto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh56_quantidadecusto"])){ 
        if(trim($this->rh56_quantidadecusto)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh56_quantidadecusto"])){ 
           $this->rh56_quantidadecusto = "0" ; 
        } 
       $sql  .= $virgula." rh56_quantidadecusto = $this->rh56_quantidadecusto ";
       $virgula = ",";
     }
     if(trim($this->rh56_percentualcusto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh56_percentualcusto"])){ 
        if(trim($this->rh56_percentualcusto)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh56_percentualcusto"])){ 
           $this->rh56_percentualcusto = "0" ; 
        } 
       $sql  .= $virgula." rh56_percentualcusto = $this->rh56_percentualcusto ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh56_seq!=null){
       $sql .= " rh56_seq = $this->rh56_seq";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh56_seq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9041,'$this->rh56_seq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh56_seq"]) || $this->rh56_seq != "")
           $resac = db_query("insert into db_acount values($acount,1543,9041,'".AddSlashes(pg_result($resaco,$conresaco,'rh56_seq'))."','$this->rh56_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh56_seqpes"]) || $this->rh56_seqpes != "")
           $resac = db_query("insert into db_acount values($acount,1543,9017,'".AddSlashes(pg_result($resaco,$conresaco,'rh56_seqpes'))."','$this->rh56_seqpes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh56_localtrab"]) || $this->rh56_localtrab != "")
           $resac = db_query("insert into db_acount values($acount,1543,9018,'".AddSlashes(pg_result($resaco,$conresaco,'rh56_localtrab'))."','$this->rh56_localtrab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh56_princ"]) || $this->rh56_princ != "")
           $resac = db_query("insert into db_acount values($acount,1543,9042,'".AddSlashes(pg_result($resaco,$conresaco,'rh56_princ'))."','$this->rh56_princ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh56_quantidadecusto"]) || $this->rh56_quantidadecusto != "")
           $resac = db_query("insert into db_acount values($acount,1543,15046,'".AddSlashes(pg_result($resaco,$conresaco,'rh56_quantidadecusto'))."','$this->rh56_quantidadecusto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh56_percentualcusto"]) || $this->rh56_percentualcusto != "")
           $resac = db_query("insert into db_acount values($acount,1543,15047,'".AddSlashes(pg_result($resaco,$conresaco,'rh56_percentualcusto'))."','$this->rh56_percentualcusto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Local de trabalho dos funcionários nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh56_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Local de trabalho dos funcionários nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh56_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh56_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh56_seq=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh56_seq));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9041,'$rh56_seq','E')");
         $resac = db_query("insert into db_acount values($acount,1543,9041,'','".AddSlashes(pg_result($resaco,$iresaco,'rh56_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1543,9017,'','".AddSlashes(pg_result($resaco,$iresaco,'rh56_seqpes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1543,9018,'','".AddSlashes(pg_result($resaco,$iresaco,'rh56_localtrab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1543,9042,'','".AddSlashes(pg_result($resaco,$iresaco,'rh56_princ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1543,15046,'','".AddSlashes(pg_result($resaco,$iresaco,'rh56_quantidadecusto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1543,15047,'','".AddSlashes(pg_result($resaco,$iresaco,'rh56_percentualcusto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhpeslocaltrab
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh56_seq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh56_seq = $rh56_seq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Local de trabalho dos funcionários nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh56_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Local de trabalho dos funcionários nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh56_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh56_seq;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpeslocaltrab";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh56_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpeslocaltrab ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh56_seq!=null ){
         $sql2 .= " where rhpeslocaltrab.rh56_seq = $rh56_seq "; 
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
   function sql_query_file ( $rh56_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpeslocaltrab ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh56_seq!=null ){
         $sql2 .= " where rhpeslocaltrab.rh56_seq = $rh56_seq "; 
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
   function sql_query_descrlocal ( $rh56_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpeslocaltrab ";
     $sql .= "      inner join rhlocaltrab  on  rhlocaltrab.rh55_codigo = rhpeslocaltrab.rh56_localtrab";
     $sql2 = "";
     if($dbwhere==""){
       if($rh56_seq!=null ){
         $sql2 .= " where rhpeslocaltrab.rh56_seq = $rh56_seq "; 
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
   function sql_query_retorno ( $rh56_seq=null,$campos="*",$ordem=null,$dbwhere="",$anonovo,$mesnovo){ 
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
     $sql .= " from rhpeslocaltrab ";
     $sql .= "      inner join rhpessoalmov on rh56_seqpes=rh02_seqpes ";
     $sql .= "      left  join rhpessoal on rh01_regist=rh02_regist ";
     $sql .= "      left  join rhpessoalmov a on a.rh02_regist=rh01_regist
                                             and a.rh02_anousu=".$anonovo."
                                             and a.rh02_mesusu=".$mesnovo;
     $sql2 = "";
     if($dbwhere==""){
       if($rh56_seq!=null ){
         $sql2 .= " where rhpeslocaltrab.rh56_seq = $rh56_seq "; 
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
  function sql_query_rhpessoalmov ( $rh56_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpeslocaltrab ";
     $sql .= "      inner join rhpessoalmov on rhpeslocaltrab.rh56_seqpes = rhpessoalmov.rh02_seqpes ";
     $sql .= "      inner join rhlocaltrab  on  rhlocaltrab.rh55_codigo   = rhpeslocaltrab.rh56_localtrab";
     $sql2 = "";
     if($dbwhere==""){
       if($rh56_seq!=null ){
         $sql2 .= " where rhpeslocaltrab.rh56_seq = $rh56_seq "; 
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
  
 function sql_query_custocriterio ( $rh56_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpeslocaltrab ";
     $sql .= "      inner join rhpessoalmov          on rhpeslocaltrab.rh56_seqpes = rhpessoalmov.rh02_seqpes ";
     $sql .= "      inner join rhlocaltrab           on rhpeslocaltrab.rh56_localtrab    = rhlocaltrab.rh55_codigo";
     $sql .= "      inner join rhlocaltrabcustoplano on rhlocaltrab.rh55_codigo          = rhlocaltrabcustoplano.rh86_rhlocaltrab";
     $sql2 = "";
     if($dbwhere==""){
       if($rh56_seq!=null ){
         $sql2 .= " where rhpeslocaltrab.rh56_seq = $rh56_seq "; 
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
   * setValoresCriterioCusto 
   * 
   * @param mixed $iSeqPes 
   * @access public
   * @return void
   */
  function setValoresCriterioCusto($iSeqPes) {

    $sSqlValores      = $this->sql_query_file(null,"*",null,"rh56_seqpes = $iSeqPes");
    $rsValores        = $this->sql_record($sSqlValores);
    $aValores         = db_utils::getCollectionByRecord($rsValores);
    $nQuantidadeTotal = 0;
    foreach ($aValores as $oValor) {
      $nQuantidadeTotal += $oValor->rh56_quantidadecusto;
    }
    /*
     * Atualizamos o valores conforme cada custo
     */
    foreach ($aValores as $oValor) {
      
      $nPercentual    = 0;
      if ($nQuantidadeTotal > 0) {
        $nPercentual = round(($oValor->rh56_quantidadecusto*100)/$nQuantidadeTotal ,2);
      }
      $oClTeste                   = new cl_rhpeslocaltrab();
      $oClTeste->rh56_seq             = $oValor->rh56_seq;
      $oClTeste->rh56_princ           = $oValor->rh56_princ=="t"?"true":"false";
      $oClTeste->rh56_seqpes          = $oValor->rh56_seqpes;
      $oClTeste->rh56_quantidadecusto = $oValor->rh56_quantidadecusto;
      $oClTeste->rh56_localtrab       = $oValor->rh56_localtrab;
      $oClTeste->rh56_percentualcusto = $nPercentual;
      $oClTeste->alterar($oValor->rh56_seq);
    }
  }

  /**
   * SQL para buscar os servidores pelo Local de Trabalho
   *
   * @param integer $iAnoUsu
   * @param integer $iMesUsu
   * @param integer $iCodigoLocalTrabalho
   * @param string  $sCampos
   * @access public
   * @return void
   */
  function sql_query_servidores($iAnoUsu, $iMesUsu, $iCodigoLocalTrabalho, $sCampos=null, $iInstituicao = null ) {

  	if ( empty($sCampos) ) {
  		$sCampos = "*";
  	}

  	if ( empty($iInstituicao) ) {
  		$iInstituicao = db_getsession('DB_instit');
  	}

  	$sSql = "select $sCampos                                                  \n";
  	$sSql.= "  from rhpessoalmov                                              \n";
  	$sSql.= "       inner join rhpeslocaltrab on rh56_seqpes = rh02_seqpes    \n";
  	$sSql.= "       inner join rhlocaltrab    on rh55_codigo = rh56_localtrab \n";
  	$sSql.= " where rh02_anousu = $iAnoUsu                                    \n";
  	$sSql.= "   and rh02_mesusu = $iMesUsu                                    \n";
  	$sSql.= "   and rh02_instit = $iInstituicao                               \n";
  	$sSql.= "   and rh55_codigo = $iCodigoLocalTrabalho                       \n";

  	return $sSql;
  	
  }
}