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

//MODULO: juridico
//CLASSE DA ENTIDADE inicialnumpre
class cl_inicialnumpre { 
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
   var $v59_inicial = 0; 
   var $v59_numpre = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v59_inicial = int4 = Inicial Numero 
                 v59_numpre = int4 = Numpre 
                 ";
   //funcao construtor da classe 
   function cl_inicialnumpre() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("inicialnumpre"); 
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
       $this->v59_inicial = ($this->v59_inicial == ""?@$GLOBALS["HTTP_POST_VARS"]["v59_inicial"]:$this->v59_inicial);
       $this->v59_numpre = ($this->v59_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["v59_numpre"]:$this->v59_numpre);
     }else{
       $this->v59_inicial = ($this->v59_inicial == ""?@$GLOBALS["HTTP_POST_VARS"]["v59_inicial"]:$this->v59_inicial);
       $this->v59_numpre = ($this->v59_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["v59_numpre"]:$this->v59_numpre);
     }
   }
   // funcao para inclusao
   function incluir (){ 
       $this->atualizacampos();
     //$this->v59_inicial = $v59_inicial; 
     // $this->v59_numpre  = $v59_numpre; 
     if(($this->v59_inicial == null) || ($this->v59_inicial == "") ){ 
       $this->erro_sql = " Campo v59_inicial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->v59_numpre == null) || ($this->v59_numpre == "") ){ 
       $this->erro_sql = " Campo v59_numpre nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into inicialnumpre(
                                       v59_inicial 
                                      ,v59_numpre 
                       )
                values (
                                $this->v59_inicial 
                               ,$this->v59_numpre 
                      )";
     $result = db_query($sql);

     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "inicialnumpre ($this->v59_inicial."-".$this->v59_numpre) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "inicialnumpre já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "inicialnumpre ($this->v59_inicial."-".$this->v59_numpre) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v59_inicial."-".$this->v59_numpre;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
      $resaco = $this->sql_record($this->sql_query_file(null,"*",null,"v59_inicial = $this->v59_inicial and v59_numpre = $this->v59_numpre"));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4661,'$this->v59_inicial','I')");
       $resac = db_query("insert into db_acountkey values($acount,6820,'$this->v59_numpre','I')");
       $resac = db_query("insert into db_acount values($acount,1117,4661,'','".AddSlashes(pg_result($resaco,0,'v59_inicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1117,6820,'','".AddSlashes(pg_result($resaco,0,'v59_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update inicialnumpre set ";
     $virgula = "";
     if(trim($this->v59_inicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v59_inicial"])){ 
       $sql  .= $virgula." v59_inicial = $this->v59_inicial ";
       $virgula = ",";
       if(trim($this->v59_inicial) == null ){ 
         $this->erro_sql = " Campo Inicial Numero nao Informado.";
         $this->erro_campo = "v59_inicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v59_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v59_numpre"])){ 
       $sql  .= $virgula." v59_numpre = $this->v59_numpre ";
       $virgula = ",";
       if(trim($this->v59_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "v59_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v59_inicial!=null){
       $sql .= " v59_inicial = $this->v59_inicial";
     }
     if($v59_numpre!=null){
       $sql .= " and  v59_numpre = $this->v59_numpre";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v59_inicial,$this->v59_numpre));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4661,'$this->v59_inicial','A')");
         $resac = db_query("insert into db_acountkey values($acount,6820,'$this->v59_numpre','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v59_inicial"]))
           $resac = db_query("insert into db_acount values($acount,1117,4661,'".AddSlashes(pg_result($resaco,$conresaco,'v59_inicial'))."','$this->v59_inicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v59_numpre"]))
           $resac = db_query("insert into db_acount values($acount,1117,6820,'".AddSlashes(pg_result($resaco,$conresaco,'v59_numpre'))."','$this->v59_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "inicialnumpre nao Alterado. Alteracao Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->v59_inicial."-".$this->v59_numpre;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "inicialnumpre nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v59_inicial."-".$this->v59_numpre;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v59_inicial."-".$this->v59_numpre;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
//       $resaco = $this->sql_record($this->sql_query_file($v59_inicial,$v59_numpre));
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,"v59_inicial = $v59_inicial and v59_numpre = $v59_numpre"));
     }else{ 
       // $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acount values($acount,1117,4661,'','".AddSlashes(pg_result($resaco,$iresaco,'v59_inicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1117,6820,'','".AddSlashes(pg_result($resaco,$iresaco,'v59_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from inicialnumpre
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v59_inicial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v59_inicial = $v59_inicial ";
        }
        if($v59_numpre != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v59_numpre = $v59_numpre ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "inicialnumpre nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->v59_inicial."-".$this->v59_numpre;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "inicialnumpre nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->v59_inicial."-".$this->v59_numpre;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v59_inicial."-".$this->v59_numpre;
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
        $this->erro_sql   = "Record Vazio na Tabela:inicialnumpre";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $oid = null,$campos="inicialnumpre.oid,*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inicialnumpre ";
     $sql .= "      inner join inicial  on  inicial.v50_inicial = inicialnumpre.v59_inicial";
     $sql .= "      inner join advog  on  advog.v57_numcgm = inicial.v50_advog";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = inicial.v50_id_login";
     $sql .= "      inner join localiza  on  localiza.v54_codlocal = inicial.v50_codlocal";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where inicialnumpre.oid = '$oid'";
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
   function sql_query_file ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inicialnumpre ";
     $sql2 = "";
     if($dbwhere==""){
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