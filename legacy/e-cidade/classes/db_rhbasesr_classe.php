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
//CLASSE DA ENTIDADE rhbasesr
class cl_rhbasesr { 
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
   var $rh33_base = null; 
   var $rh33_rubric = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh33_base = varchar(4) = Base 
                 rh33_rubric = varchar(4) = C�digo da Rubrica 
                 ";
   //funcao construtor da classe 
   function cl_rhbasesr() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhbasesr"); 
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
       $this->rh33_base = ($this->rh33_base == ""?@$GLOBALS["HTTP_POST_VARS"]["rh33_base"]:$this->rh33_base);
       $this->rh33_rubric = ($this->rh33_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["rh33_rubric"]:$this->rh33_rubric);
     }else{
       $this->rh33_base = ($this->rh33_base == ""?@$GLOBALS["HTTP_POST_VARS"]["rh33_base"]:$this->rh33_base);
       $this->rh33_rubric = ($this->rh33_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["rh33_rubric"]:$this->rh33_rubric);
     }
   }
   // funcao para inclusao
   function incluir ($rh33_base,$rh33_rubric){ 
      $this->atualizacampos();
       $this->rh33_base = $rh33_base; 
       $this->rh33_rubric = $rh33_rubric; 
     if(($this->rh33_base == null) || ($this->rh33_base == "") ){ 
       $this->erro_sql = " Campo rh33_base nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh33_rubric == null) || ($this->rh33_rubric == "") ){ 
       $this->erro_sql = " Campo rh33_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhbasesr(
                                       rh33_base 
                                      ,rh33_rubric 
                       )
                values (
                                '$this->rh33_base' 
                               ,'$this->rh33_rubric' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Rubricas em bases ($this->rh33_base."-".$this->rh33_rubric) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Rubricas em bases j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Rubricas em bases ($this->rh33_base."-".$this->rh33_rubric) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh33_base."-".$this->rh33_rubric;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh33_base,$this->rh33_rubric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7162,'$this->rh33_base','I')");
       $resac = db_query("insert into db_acountkey values($acount,7163,'$this->rh33_rubric','I')");
       $resac = db_query("insert into db_acount values($acount,1188,7162,'','".AddSlashes(pg_result($resaco,0,'rh33_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1188,7163,'','".AddSlashes(pg_result($resaco,0,'rh33_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh33_base=null,$rh33_rubric=null) { 
      $this->atualizacampos();
     $sql = " update rhbasesr set ";
     $virgula = "";
     if(trim($this->rh33_base)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh33_base"])){ 
       $sql  .= $virgula." rh33_base = '$this->rh33_base' ";
       $virgula = ",";
       if(trim($this->rh33_base) == null ){ 
         $this->erro_sql = " Campo Base nao Informado.";
         $this->erro_campo = "rh33_base";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh33_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh33_rubric"])){ 
       $sql  .= $virgula." rh33_rubric = '$this->rh33_rubric' ";
       $virgula = ",";
       if(trim($this->rh33_rubric) == null ){ 
         $this->erro_sql = " Campo C�digo da Rubrica nao Informado.";
         $this->erro_campo = "rh33_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh33_base!=null){
       $sql .= " rh33_base = '$this->rh33_base'";
     }
     if($rh33_rubric!=null){
       $sql .= " and  rh33_rubric = '$this->rh33_rubric'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh33_base,$this->rh33_rubric));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7162,'$this->rh33_base','A')");
         $resac = db_query("insert into db_acountkey values($acount,7163,'$this->rh33_rubric','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh33_base"]))
           $resac = db_query("insert into db_acount values($acount,1188,7162,'".AddSlashes(pg_result($resaco,$conresaco,'rh33_base'))."','$this->rh33_base',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh33_rubric"]))
           $resac = db_query("insert into db_acount values($acount,1188,7163,'".AddSlashes(pg_result($resaco,$conresaco,'rh33_rubric'))."','$this->rh33_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Rubricas em bases nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh33_base."-".$this->rh33_rubric;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Rubricas em bases nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh33_base."-".$this->rh33_rubric;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh33_base."-".$this->rh33_rubric;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh33_base=null,$rh33_rubric=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh33_base,$rh33_rubric));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7162,'$rh33_base','E')");
         $resac = db_query("insert into db_acountkey values($acount,7163,'$rh33_rubric','E')");
         $resac = db_query("insert into db_acount values($acount,1188,7162,'','".AddSlashes(pg_result($resaco,$iresaco,'rh33_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1188,7163,'','".AddSlashes(pg_result($resaco,$iresaco,'rh33_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhbasesr
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh33_base != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh33_base = '$rh33_base' ";
        }
        if($rh33_rubric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh33_rubric = '$rh33_rubric' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Rubricas em bases nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh33_base."-".$rh33_rubric;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Rubricas em bases nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh33_base."-".$rh33_rubric;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh33_base."-".$rh33_rubric;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:rhbasesr";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $rh33_base=null,$rh33_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhbasesr ";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = rhbasesr.rh33_rubric
		                                      and  rhrubricas.rh27_instit = rhbasesr.rh33_instit ";
     $sql .= "      inner join rhbases  on  rhbases.rh32_base   = rhbasesr.rh33_base
		                                   and  rhbases.rh32_instit = rhbasesr.rh33_instit ";
     $sql .= "      inner join rhtipomedia  on  rhtipomedia.rh29_tipo = rhrubricas.rh27_calc1";
     $sql2 = "";
     if($dbwhere==""){
       if($rh33_base!=null ){
         $sql2 .= " where rhbasesr.rh33_base = '$rh33_base' "; 
       } 
       if($rh33_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhbasesr.rh33_rubric = '$rh33_rubric' "; 
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
   function sql_query_file ( $rh33_base=null,$rh33_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhbasesr ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh33_base!=null ){
         $sql2 .= " where rhbasesr.rh33_base = '$rh33_base' "; 
       } 
       if($rh33_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhbasesr.rh33_rubric = '$rh33_rubric' "; 
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