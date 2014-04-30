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

//MODULO: protocolo
//CLASSE DA ENTIDADE procprocessodoc
class cl_procprocessodoc { 
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
   var $p81_codproc = 0; 
   var $p81_coddoc = 0; 
   var $p81_doc = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p81_codproc = int4 = Código do processo 
                 p81_coddoc = int4 = Código 
                 p81_doc = bool = documento 
                 ";
   //funcao construtor da classe 
   function cl_procprocessodoc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("procprocessodoc"); 
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
       $this->p81_codproc = ($this->p81_codproc == ""?@$GLOBALS["HTTP_POST_VARS"]["p81_codproc"]:$this->p81_codproc);
       $this->p81_coddoc = ($this->p81_coddoc == ""?@$GLOBALS["HTTP_POST_VARS"]["p81_coddoc"]:$this->p81_coddoc);
       $this->p81_doc = ($this->p81_doc == "f"?@$GLOBALS["HTTP_POST_VARS"]["p81_doc"]:$this->p81_doc);
     }else{
       $this->p81_codproc = ($this->p81_codproc == ""?@$GLOBALS["HTTP_POST_VARS"]["p81_codproc"]:$this->p81_codproc);
       $this->p81_coddoc = ($this->p81_coddoc == ""?@$GLOBALS["HTTP_POST_VARS"]["p81_coddoc"]:$this->p81_coddoc);
     }
   }
   // funcao para inclusao
   function incluir ($p81_codproc,$p81_coddoc){ 
      $this->atualizacampos();
     if($this->p81_doc == null ){ 
       $this->erro_sql = " Campo documento nao Informado.";
       $this->erro_campo = "p81_doc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->p81_codproc = $p81_codproc; 
       $this->p81_coddoc = $p81_coddoc; 
     if(($this->p81_codproc == null) || ($this->p81_codproc == "") ){ 
       $this->erro_sql = " Campo p81_codproc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->p81_coddoc == null) || ($this->p81_coddoc == "") ){ 
       $this->erro_sql = " Campo p81_coddoc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into procprocessodoc(
                                       p81_codproc 
                                      ,p81_coddoc 
                                      ,p81_doc 
                       )
                values (
                                $this->p81_codproc 
                               ,$this->p81_coddoc 
                               ,'$this->p81_doc' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "documentos utilizados no processo ($this->p81_codproc."-".$this->p81_coddoc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "documentos utilizados no processo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "documentos utilizados no processo ($this->p81_codproc."-".$this->p81_coddoc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p81_codproc."-".$this->p81_coddoc;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->p81_codproc,$this->p81_coddoc));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6128,'$this->p81_codproc','I')");
       $resac = db_query("insert into db_acountkey values($acount,6129,'$this->p81_coddoc','I')");
       $resac = db_query("insert into db_acount values($acount,988,6128,'','".AddSlashes(pg_result($resaco,0,'p81_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,988,6129,'','".AddSlashes(pg_result($resaco,0,'p81_coddoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,988,6130,'','".AddSlashes(pg_result($resaco,0,'p81_doc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($p81_codproc=null,$p81_coddoc=null) { 
      $this->atualizacampos();
     $sql = " update procprocessodoc set ";
     $virgula = "";
     if(trim($this->p81_codproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p81_codproc"])){ 
       $sql  .= $virgula." p81_codproc = $this->p81_codproc ";
       $virgula = ",";
       if(trim($this->p81_codproc) == null ){ 
         $this->erro_sql = " Campo Código do processo nao Informado.";
         $this->erro_campo = "p81_codproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p81_coddoc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p81_coddoc"])){ 
       $sql  .= $virgula." p81_coddoc = $this->p81_coddoc ";
       $virgula = ",";
       if(trim($this->p81_coddoc) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "p81_coddoc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p81_doc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p81_doc"])){ 
       $sql  .= $virgula." p81_doc = '$this->p81_doc' ";
       $virgula = ",";
       if(trim($this->p81_doc) == null ){ 
         $this->erro_sql = " Campo documento nao Informado.";
         $this->erro_campo = "p81_doc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($p81_codproc!=null){
       $sql .= " p81_codproc = $this->p81_codproc";
     }
     if($p81_coddoc!=null){
       $sql .= " and  p81_coddoc = $this->p81_coddoc";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->p81_codproc,$this->p81_coddoc));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6128,'$this->p81_codproc','A')");
         $resac = db_query("insert into db_acountkey values($acount,6129,'$this->p81_coddoc','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p81_codproc"]))
           $resac = db_query("insert into db_acount values($acount,988,6128,'".AddSlashes(pg_result($resaco,$conresaco,'p81_codproc'))."','$this->p81_codproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p81_coddoc"]))
           $resac = db_query("insert into db_acount values($acount,988,6129,'".AddSlashes(pg_result($resaco,$conresaco,'p81_coddoc'))."','$this->p81_coddoc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p81_doc"]))
           $resac = db_query("insert into db_acount values($acount,988,6130,'".AddSlashes(pg_result($resaco,$conresaco,'p81_doc'))."','$this->p81_doc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "documentos utilizados no processo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p81_codproc."-".$this->p81_coddoc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "documentos utilizados no processo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p81_codproc."-".$this->p81_coddoc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p81_codproc."-".$this->p81_coddoc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($p81_codproc=null,$p81_coddoc=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($p81_codproc,$p81_coddoc));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6128,'$p81_codproc','E')");
         $resac = db_query("insert into db_acountkey values($acount,6129,'$p81_coddoc','E')");
         $resac = db_query("insert into db_acount values($acount,988,6128,'','".AddSlashes(pg_result($resaco,$iresaco,'p81_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,988,6129,'','".AddSlashes(pg_result($resaco,$iresaco,'p81_coddoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,988,6130,'','".AddSlashes(pg_result($resaco,$iresaco,'p81_doc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from procprocessodoc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($p81_codproc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p81_codproc = $p81_codproc ";
        }
        if($p81_coddoc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p81_coddoc = $p81_coddoc ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "documentos utilizados no processo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p81_codproc."-".$p81_coddoc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "documentos utilizados no processo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p81_codproc."-".$p81_coddoc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p81_codproc."-".$p81_coddoc;
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
        $this->erro_sql   = "Record Vazio na Tabela:procprocessodoc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $p81_codproc=null,$p81_coddoc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procprocessodoc ";
     $sql .= "      inner join procdoc  on  procdoc.p56_coddoc = procprocessodoc.p81_coddoc";
     $sql .= "      inner join protprocesso  on  protprocesso.p58_codproc = procprocessodoc.p81_codproc";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = protprocesso.p58_numcgm";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = protprocesso.p58_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = protprocesso.p58_coddepto";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = protprocesso.p58_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($p81_codproc!=null ){
         $sql2 .= " where procprocessodoc.p81_codproc = $p81_codproc "; 
       } 
       if($p81_coddoc!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " procprocessodoc.p81_coddoc = $p81_coddoc "; 
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
   function sql_query_file ( $p81_codproc=null,$p81_coddoc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procprocessodoc ";
     $sql2 = "";
     if($dbwhere==""){
       if($p81_codproc!=null ){
         $sql2 .= " where procprocessodoc.p81_codproc = $p81_codproc "; 
       } 
       if($p81_coddoc!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " procprocessodoc.p81_coddoc = $p81_coddoc "; 
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