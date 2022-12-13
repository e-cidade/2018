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

//MODULO: issqn
//CLASSE DA ENTIDADE issmatric
class cl_issmatric { 
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
   var $q05_inscr = 0; 
   var $q05_matric = 0; 
   var $q05_idcons = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q05_inscr = int4 = inscricao 
                 q05_matric = int4 = Matricula 
                 q05_idcons = int4 = Construção 
                 ";
   //funcao construtor da classe 
   function cl_issmatric() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issmatric"); 
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
       $this->q05_inscr = ($this->q05_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q05_inscr"]:$this->q05_inscr);
       $this->q05_matric = ($this->q05_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["q05_matric"]:$this->q05_matric);
       $this->q05_idcons = ($this->q05_idcons == ""?@$GLOBALS["HTTP_POST_VARS"]["q05_idcons"]:$this->q05_idcons);
     }else{
       $this->q05_inscr = ($this->q05_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q05_inscr"]:$this->q05_inscr);
       $this->q05_matric = ($this->q05_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["q05_matric"]:$this->q05_matric);
     }
   }
   // funcao para inclusao
   function incluir ($q05_inscr,$q05_matric){ 
      $this->atualizacampos();
     if($this->q05_idcons == null ){ 
       $this->q05_idcons = "0";
     }
       $this->q05_inscr = $q05_inscr; 
       $this->q05_matric = $q05_matric; 
     if(($this->q05_inscr == null) || ($this->q05_inscr == "") ){ 
       $this->erro_sql = " Campo q05_inscr nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->q05_matric == null) || ($this->q05_matric == "") ){ 
       $this->erro_sql = " Campo q05_matric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issmatric(
                                       q05_inscr 
                                      ,q05_matric 
                                      ,q05_idcons 
                       )
                values (
                                $this->q05_inscr 
                               ,$this->q05_matric 
                               ,$this->q05_idcons 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->q05_inscr."-".$this->q05_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->q05_inscr."-".$this->q05_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q05_inscr."-".$this->q05_matric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q05_inscr,$this->q05_matric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,325,'$this->q05_inscr','I')");
       $resac = db_query("insert into db_acountkey values($acount,324,'$this->q05_matric','I')");
       $resac = db_query("insert into db_acount values($acount,46,325,'','".AddSlashes(pg_result($resaco,0,'q05_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,46,324,'','".AddSlashes(pg_result($resaco,0,'q05_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,46,4798,'','".AddSlashes(pg_result($resaco,0,'q05_idcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q05_inscr=null,$q05_matric=null) { 
      $this->atualizacampos();
     $sql = " update issmatric set ";
     $virgula = "";
     if(trim($this->q05_inscr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q05_inscr"])){ 
       $sql  .= $virgula." q05_inscr = $this->q05_inscr ";
       $virgula = ",";
       if(trim($this->q05_inscr) == null ){ 
         $this->erro_sql = " Campo inscricao nao Informado.";
         $this->erro_campo = "q05_inscr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q05_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q05_matric"])){ 
        if(trim($this->q05_matric)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q05_matric"])){ 
           $this->q05_matric = "0" ; 
        } 
       $sql  .= $virgula." q05_matric = $this->q05_matric ";
       $virgula = ",";
     }
     if(trim($this->q05_idcons)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q05_idcons"])){ 
        if(trim($this->q05_idcons)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q05_idcons"])){ 
           $this->q05_idcons = "0" ; 
        } 
       $sql  .= $virgula." q05_idcons = $this->q05_idcons ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($q05_inscr!=null){
       $sql .= " q05_inscr = $this->q05_inscr";
     }
     if($q05_matric!=null){
       $sql .= " and  q05_matric = $this->q05_matric";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q05_inscr,$this->q05_matric));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,325,'$this->q05_inscr','A')");
         $resac = db_query("insert into db_acountkey values($acount,324,'$this->q05_matric','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q05_inscr"]))
           $resac = db_query("insert into db_acount values($acount,46,325,'".AddSlashes(pg_result($resaco,$conresaco,'q05_inscr'))."','$this->q05_inscr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q05_matric"]))
           $resac = db_query("insert into db_acount values($acount,46,324,'".AddSlashes(pg_result($resaco,$conresaco,'q05_matric'))."','$this->q05_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q05_idcons"]))
           $resac = db_query("insert into db_acount values($acount,46,4798,'".AddSlashes(pg_result($resaco,$conresaco,'q05_idcons'))."','$this->q05_idcons',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q05_inscr."-".$this->q05_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q05_inscr."-".$this->q05_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q05_inscr."-".$this->q05_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q05_inscr=null,$q05_matric=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q05_inscr,$q05_matric));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,325,'$q05_inscr','E')");
         $resac = db_query("insert into db_acountkey values($acount,324,'$q05_matric','E')");
         $resac = db_query("insert into db_acount values($acount,46,325,'','".AddSlashes(pg_result($resaco,$iresaco,'q05_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,46,324,'','".AddSlashes(pg_result($resaco,$iresaco,'q05_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,46,4798,'','".AddSlashes(pg_result($resaco,$iresaco,'q05_idcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issmatric
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q05_inscr != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q05_inscr = $q05_inscr ";
        }
        if($q05_matric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q05_matric = $q05_matric ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q05_inscr."-".$q05_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q05_inscr."-".$q05_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q05_inscr."-".$q05_matric;
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
        $this->erro_sql   = "Record Vazio na Tabela:issmatric";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q05_inscr=null,$q05_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issmatric ";
     $sql .= "      inner join iptuconstr on  iptuconstr.j39_matric = issmatric.q05_matric  ";
     $sql .= "                           and  iptuconstr.j39_idcons = issmatric.q05_idcons  ";
     $sql .= "      inner join issbase    on  issbase.q02_inscr     = issmatric.q05_inscr   ";
     $sql .= "      inner join ruas       on  ruas.j14_codigo       = iptuconstr.j39_codigo ";
     $sql .= "      inner join iptubase   on  iptubase.j01_matric   = iptuconstr.j39_matric ";
     $sql .= "      inner join cgm        on  cgm.z01_numcgm        = iptubase.j01_numcgm   ";
     $sql2 = "";
     if($dbwhere==""){
       if($q05_inscr!=null ){
         $sql2 .= " where issmatric.q05_inscr = $q05_inscr "; 
       } 
       if($q05_matric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " issmatric.q05_matric = $q05_matric "; 
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
   function sql_query_file ( $q05_inscr=null,$q05_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issmatric ";
     $sql2 = "";
     if($dbwhere==""){
       if($q05_inscr!=null ){
         $sql2 .= " where issmatric.q05_inscr = $q05_inscr "; 
       } 
       if($q05_matric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " issmatric.q05_matric = $q05_matric "; 
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