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

//MODULO: Ambulatorial
//CLASSE DA ENTIDADE sau_cgserradolog
class cl_sau_cgserradolog { 
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
   var $s129_i_codigo = 0; 
   var $s129_i_numcgs = 0; 
   var $s129_t_log = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s129_i_codigo = int4 = Código 
                 s129_i_numcgs = int4 = CGS 
                 s129_t_log = text = Log 
                 ";
   //funcao construtor da classe 
   function cl_sau_cgserradolog() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_cgserradolog"); 
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
       $this->s129_i_codigo = ($this->s129_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s129_i_codigo"]:$this->s129_i_codigo);
       $this->s129_i_numcgs = ($this->s129_i_numcgs == ""?@$GLOBALS["HTTP_POST_VARS"]["s129_i_numcgs"]:$this->s129_i_numcgs);
       $this->s129_t_log = ($this->s129_t_log == ""?@$GLOBALS["HTTP_POST_VARS"]["s129_t_log"]:$this->s129_t_log);
     }else{
       $this->s129_i_codigo = ($this->s129_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s129_i_codigo"]:$this->s129_i_codigo);
       $this->s129_i_numcgs = ($this->s129_i_numcgs == ""?@$GLOBALS["HTTP_POST_VARS"]["s129_i_numcgs"]:$this->s129_i_numcgs);
     }
   }
   // funcao para inclusao
   function incluir ($s129_i_codigo,$s129_i_numcgs){ 
      $this->atualizacampos();
     if($this->s129_t_log == null ){ 
       $this->erro_sql = " Campo Log nao Informado.";
       $this->erro_campo = "s129_t_log";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->s129_i_codigo = $s129_i_codigo; 
       $this->s129_i_numcgs = $s129_i_numcgs; 
     if(($this->s129_i_codigo == null) || ($this->s129_i_codigo == "") ){ 
       $this->erro_sql = " Campo s129_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->s129_i_numcgs == null) || ($this->s129_i_numcgs == "") ){ 
       $this->erro_sql = " Campo s129_i_numcgs nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_cgserradolog(
                                       s129_i_codigo 
                                      ,s129_i_numcgs 
                                      ,s129_t_log 
                       )
                values (
                                $this->s129_i_codigo 
                               ,$this->s129_i_numcgs 
                               ,'$this->s129_t_log' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "sau_cgserradolog ($this->s129_i_codigo."-".$this->s129_i_numcgs) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "sau_cgserradolog já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "sau_cgserradolog ($this->s129_i_codigo."-".$this->s129_i_numcgs) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s129_i_codigo."-".$this->s129_i_numcgs;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->s129_i_codigo,$this->s129_i_numcgs));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15478,'$this->s129_i_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,15479,'$this->s129_i_numcgs','I')");
       $resac = db_query("insert into db_acount values($acount,2715,15478,'','".AddSlashes(pg_result($resaco,0,'s129_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2715,15479,'','".AddSlashes(pg_result($resaco,0,'s129_i_numcgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2715,15480,'','".AddSlashes(pg_result($resaco,0,'s129_t_log'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($s129_i_codigo=null,$s129_i_numcgs=null) { 
      $this->atualizacampos();
     $sql = " update sau_cgserradolog set ";
     $virgula = "";
     if(trim($this->s129_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s129_i_codigo"])){ 
       $sql  .= $virgula." s129_i_codigo = $this->s129_i_codigo ";
       $virgula = ",";
       if(trim($this->s129_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "s129_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s129_i_numcgs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s129_i_numcgs"])){ 
       $sql  .= $virgula." s129_i_numcgs = $this->s129_i_numcgs ";
       $virgula = ",";
       if(trim($this->s129_i_numcgs) == null ){ 
         $this->erro_sql = " Campo CGS nao Informado.";
         $this->erro_campo = "s129_i_numcgs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s129_t_log)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s129_t_log"])){ 
       $sql  .= $virgula." s129_t_log = '$this->s129_t_log' ";
       $virgula = ",";
       if(trim($this->s129_t_log) == null ){ 
         $this->erro_sql = " Campo Log nao Informado.";
         $this->erro_campo = "s129_t_log";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($s129_i_codigo!=null){
       $sql .= " s129_i_codigo = $this->s129_i_codigo";
     }
     if($s129_i_numcgs!=null){
       $sql .= " and  s129_i_numcgs = $this->s129_i_numcgs";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->s129_i_codigo,$this->s129_i_numcgs));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15478,'$this->s129_i_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,15479,'$this->s129_i_numcgs','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s129_i_codigo"]) || $this->s129_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2715,15478,'".AddSlashes(pg_result($resaco,$conresaco,'s129_i_codigo'))."','$this->s129_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s129_i_numcgs"]) || $this->s129_i_numcgs != "")
           $resac = db_query("insert into db_acount values($acount,2715,15479,'".AddSlashes(pg_result($resaco,$conresaco,'s129_i_numcgs'))."','$this->s129_i_numcgs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s129_t_log"]) || $this->s129_t_log != "")
           $resac = db_query("insert into db_acount values($acount,2715,15480,'".AddSlashes(pg_result($resaco,$conresaco,'s129_t_log'))."','$this->s129_t_log',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_cgserradolog nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s129_i_codigo."-".$this->s129_i_numcgs;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_cgserradolog nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s129_i_codigo."-".$this->s129_i_numcgs;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s129_i_codigo."-".$this->s129_i_numcgs;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($s129_i_codigo=null,$s129_i_numcgs=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($s129_i_codigo,$s129_i_numcgs));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15478,'$s129_i_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,15479,'$s129_i_numcgs','E')");
         $resac = db_query("insert into db_acount values($acount,2715,15478,'','".AddSlashes(pg_result($resaco,$iresaco,'s129_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2715,15479,'','".AddSlashes(pg_result($resaco,$iresaco,'s129_i_numcgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2715,15480,'','".AddSlashes(pg_result($resaco,$iresaco,'s129_t_log'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_cgserradolog
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($s129_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " s129_i_codigo = $s129_i_codigo ";
        }
        if($s129_i_numcgs != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " s129_i_numcgs = $s129_i_numcgs ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_cgserradolog nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s129_i_codigo."-".$s129_i_numcgs;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_cgserradolog nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s129_i_codigo."-".$s129_i_numcgs;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s129_i_codigo."-".$s129_i_numcgs;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_cgserradolog";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $s129_i_codigo=null,$s129_i_numcgs=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_cgserradolog ";
     $sql .= "      inner join sau_cgserrado  on  sau_cgserrado.s128_i_codigo = sau_cgserradolog.s129_i_codigo and  sau_cgserrado.s128_i_numcgs = sau_cgserradolog.s129_i_numcgs";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = sau_cgserrado.s128_i_numcgs";
     $sql2 = "";
     if($dbwhere==""){
       if($s129_i_codigo!=null ){
         $sql2 .= " where sau_cgserradolog.s129_i_codigo = $s129_i_codigo "; 
       } 
       if($s129_i_numcgs!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " sau_cgserradolog.s129_i_numcgs = $s129_i_numcgs "; 
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
   function sql_query_file ( $s129_i_codigo=null,$s129_i_numcgs=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_cgserradolog ";
     $sql2 = "";
     if($dbwhere==""){
       if($s129_i_codigo!=null ){
         $sql2 .= " where sau_cgserradolog.s129_i_codigo = $s129_i_codigo "; 
       } 
       if($s129_i_numcgs!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " sau_cgserradolog.s129_i_numcgs = $s129_i_numcgs "; 
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