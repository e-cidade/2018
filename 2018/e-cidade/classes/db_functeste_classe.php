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

//MODULO: pessoal
//CLASSE DA ENTIDADE functeste
class cl_functeste { 
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
   var $funcant = null; 
   var $descrfunc = null; 
   var $padraoant = null; 
   var $funcnova = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 funcant = varchar(6) = Função Anterior 
                 descrfunc = varchar(40) = Descrição 
                 padraoant = varchar(4) = Padrão Anterior 
                 funcnova = varchar(5) = Função Nova 
                 ";
   //funcao construtor da classe 
   function cl_functeste() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("functeste"); 
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
       $this->funcant = ($this->funcant == ""?@$GLOBALS["HTTP_POST_VARS"]["funcant"]:$this->funcant);
       $this->descrfunc = ($this->descrfunc == ""?@$GLOBALS["HTTP_POST_VARS"]["descrfunc"]:$this->descrfunc);
       $this->padraoant = ($this->padraoant == ""?@$GLOBALS["HTTP_POST_VARS"]["padraoant"]:$this->padraoant);
       $this->funcnova = ($this->funcnova == ""?@$GLOBALS["HTTP_POST_VARS"]["funcnova"]:$this->funcnova);
     }else{
       $this->funcant = ($this->funcant == ""?@$GLOBALS["HTTP_POST_VARS"]["funcant"]:$this->funcant);
       $this->descrfunc = ($this->descrfunc == ""?@$GLOBALS["HTTP_POST_VARS"]["descrfunc"]:$this->descrfunc);
     }
   }
   // funcao para inclusao
   function incluir ($funcant,$descrfunc){ 
      $this->atualizacampos();
     if($this->padraoant == null ){ 
       $this->erro_sql = " Campo Padrão Anterior nao Informado.";
       $this->erro_campo = "padraoant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->funcnova == null ){ 
       $this->erro_sql = " Campo Função Nova nao Informado.";
       $this->erro_campo = "funcnova";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->funcant = $funcant; 
       $this->descrfunc = $descrfunc; 
     if(($this->funcant == null) || ($this->funcant == "") ){ 
       $this->erro_sql = " Campo funcant nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->descrfunc == null) || ($this->descrfunc == "") ){ 
       $this->erro_sql = " Campo descrfunc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into functeste(
                                       funcant 
                                      ,descrfunc 
                                      ,padraoant 
                                      ,funcnova 
                       )
                values (
                                '$this->funcant' 
                               ,'$this->descrfunc' 
                               ,'$this->padraoant' 
                               ,'$this->funcnova' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "functeste ($this->funcant."-".$this->descrfunc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "functeste já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "functeste ($this->funcant."-".$this->descrfunc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->funcant."-".$this->descrfunc;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->funcant,$this->descrfunc));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6094,'$this->funcant','I')");
       $resac = db_query("insert into db_acountkey values($acount,6095,'$this->descrfunc','I')");
       $resac = db_query("insert into db_acount values($acount,980,6094,'','".AddSlashes(pg_result($resaco,0,'funcant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,980,6095,'','".AddSlashes(pg_result($resaco,0,'descrfunc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,980,6096,'','".AddSlashes(pg_result($resaco,0,'padraoant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,980,6097,'','".AddSlashes(pg_result($resaco,0,'funcnova'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($funcant=null,$descrfunc=null) { 
      $this->atualizacampos();
     $sql = " update functeste set ";
     $virgula = "";
     if(trim($this->funcant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["funcant"])){ 
       $sql  .= $virgula." funcant = '$this->funcant' ";
       $virgula = ",";
       if(trim($this->funcant) == null ){ 
         $this->erro_sql = " Campo Função Anterior nao Informado.";
         $this->erro_campo = "funcant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->descrfunc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["descrfunc"])){ 
       $sql  .= $virgula." descrfunc = '$this->descrfunc' ";
       $virgula = ",";
       if(trim($this->descrfunc) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "descrfunc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->padraoant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["padraoant"])){ 
       $sql  .= $virgula." padraoant = '$this->padraoant' ";
       $virgula = ",";
       if(trim($this->padraoant) == null ){ 
         $this->erro_sql = " Campo Padrão Anterior nao Informado.";
         $this->erro_campo = "padraoant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->funcnova)!="" || isset($GLOBALS["HTTP_POST_VARS"]["funcnova"])){ 
       $sql  .= $virgula." funcnova = '$this->funcnova' ";
       $virgula = ",";
       if(trim($this->funcnova) == null ){ 
         $this->erro_sql = " Campo Função Nova nao Informado.";
         $this->erro_campo = "funcnova";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($funcant!=null){
       $sql .= " funcant = '$this->funcant'";
     }
     if($descrfunc!=null){
       $sql .= " and  descrfunc = '$this->descrfunc'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->funcant,$this->descrfunc));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6094,'$this->funcant','A')");
         $resac = db_query("insert into db_acountkey values($acount,6095,'$this->descrfunc','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["funcant"]))
           $resac = db_query("insert into db_acount values($acount,980,6094,'".AddSlashes(pg_result($resaco,$conresaco,'funcant'))."','$this->funcant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["descrfunc"]))
           $resac = db_query("insert into db_acount values($acount,980,6095,'".AddSlashes(pg_result($resaco,$conresaco,'descrfunc'))."','$this->descrfunc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["padraoant"]))
           $resac = db_query("insert into db_acount values($acount,980,6096,'".AddSlashes(pg_result($resaco,$conresaco,'padraoant'))."','$this->padraoant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["funcnova"]))
           $resac = db_query("insert into db_acount values($acount,980,6097,'".AddSlashes(pg_result($resaco,$conresaco,'funcnova'))."','$this->funcnova',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "functeste nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->funcant."-".$this->descrfunc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "functeste nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->funcant."-".$this->descrfunc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->funcant."-".$this->descrfunc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($funcant=null,$descrfunc=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($funcant,$descrfunc));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6094,'$funcant','E')");
         $resac = db_query("insert into db_acountkey values($acount,6095,'$descrfunc','E')");
         $resac = db_query("insert into db_acount values($acount,980,6094,'','".AddSlashes(pg_result($resaco,$iresaco,'funcant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,980,6095,'','".AddSlashes(pg_result($resaco,$iresaco,'descrfunc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,980,6096,'','".AddSlashes(pg_result($resaco,$iresaco,'padraoant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,980,6097,'','".AddSlashes(pg_result($resaco,$iresaco,'funcnova'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from functeste
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($funcant != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " funcant = '$funcant' ";
        }
        if($descrfunc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " descrfunc = '$descrfunc' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "functeste nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$funcant."-".$descrfunc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "functeste nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$funcant."-".$descrfunc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$funcant."-".$descrfunc;
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
        $this->erro_sql   = "Record Vazio na Tabela:functeste";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $funcant=null,$descrfunc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from functeste ";
     $sql2 = "";
     if($dbwhere==""){
       if($funcant!=null ){
         $sql2 .= " where functeste.funcant = '$funcant' "; 
       } 
       if($descrfunc!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " functeste.descrfunc = '$descrfunc' "; 
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
   function sql_query_file ( $funcant=null,$descrfunc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from functeste ";
     $sql2 = "";
     if($dbwhere==""){
       if($funcant!=null ){
         $sql2 .= " where functeste.funcant = '$funcant' "; 
       } 
       if($descrfunc!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " functeste.descrfunc = '$descrfunc' "; 
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