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

//MODULO: divida
//CLASSE DA ENTIDADE procedparag
class cl_procedparag { 
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
   var $v80_proced = 0; 
   var $v80_docum = 0; 
   var $v80_docmetcalculo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v80_proced = int4 = procedencia 
                 v80_docum = int4 = Código 
                 v80_docmetcalculo = int4 = Cód. Met. Cálculo 
                 ";
   //funcao construtor da classe 
   function cl_procedparag() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("procedparag"); 
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
       $this->v80_proced = ($this->v80_proced == ""?@$GLOBALS["HTTP_POST_VARS"]["v80_proced"]:$this->v80_proced);
       $this->v80_docum = ($this->v80_docum == ""?@$GLOBALS["HTTP_POST_VARS"]["v80_docum"]:$this->v80_docum);
       $this->v80_docmetcalculo = ($this->v80_docmetcalculo == ""?@$GLOBALS["HTTP_POST_VARS"]["v80_docmetcalculo"]:$this->v80_docmetcalculo);
     }else{
       $this->v80_proced = ($this->v80_proced == ""?@$GLOBALS["HTTP_POST_VARS"]["v80_proced"]:$this->v80_proced);
     }
   }
   // funcao para inclusao
   function incluir ($v80_proced){ 
      $this->atualizacampos();
     if($this->v80_docum == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "v80_docum";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v80_docmetcalculo == null ){ 
       $this->erro_sql = " Campo Cód. Met. Cálculo nao Informado.";
       $this->erro_campo = "v80_docmetcalculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->v80_proced = $v80_proced; 
     if(($this->v80_proced == null) || ($this->v80_proced == "") ){ 
       $this->erro_sql = " Campo v80_proced nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into procedparag(
                                       v80_proced 
                                      ,v80_docum 
                                      ,v80_docmetcalculo 
                       )
                values (
                                $this->v80_proced 
                               ,$this->v80_docum 
                               ,$this->v80_docmetcalculo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Procedencia e Parágrafos ($this->v80_proced) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Procedencia e Parágrafos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Procedencia e Parágrafos ($this->v80_proced) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v80_proced;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v80_proced));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7851,'$this->v80_proced','I')");
       $resac = db_query("insert into db_acount values($acount,1316,7851,'','".AddSlashes(pg_result($resaco,0,'v80_proced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1316,7852,'','".AddSlashes(pg_result($resaco,0,'v80_docum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1316,14546,'','".AddSlashes(pg_result($resaco,0,'v80_docmetcalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v80_proced=null) { 
      $this->atualizacampos();
     $sql = " update procedparag set ";
     $virgula = "";
     if(trim($this->v80_proced)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v80_proced"])){ 
       $sql  .= $virgula." v80_proced = $this->v80_proced ";
       $virgula = ",";
       if(trim($this->v80_proced) == null ){ 
         $this->erro_sql = " Campo procedencia nao Informado.";
         $this->erro_campo = "v80_proced";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v80_docum)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v80_docum"])){ 
       $sql  .= $virgula." v80_docum = $this->v80_docum ";
       $virgula = ",";
       if(trim($this->v80_docum) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "v80_docum";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v80_docmetcalculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v80_docmetcalculo"])){ 
       $sql  .= $virgula." v80_docmetcalculo = $this->v80_docmetcalculo ";
       $virgula = ",";
       if(trim($this->v80_docmetcalculo) == null ){ 
         $this->erro_sql = " Campo Cód. Met. Cálculo nao Informado.";
         $this->erro_campo = "v80_docmetcalculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v80_proced!=null){
       $sql .= " v80_proced = $this->v80_proced";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v80_proced));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7851,'$this->v80_proced','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v80_proced"]) || $this->v80_proced != "")
           $resac = db_query("insert into db_acount values($acount,1316,7851,'".AddSlashes(pg_result($resaco,$conresaco,'v80_proced'))."','$this->v80_proced',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v80_docum"]) || $this->v80_docum != "")
           $resac = db_query("insert into db_acount values($acount,1316,7852,'".AddSlashes(pg_result($resaco,$conresaco,'v80_docum'))."','$this->v80_docum',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v80_docmetcalculo"]) || $this->v80_docmetcalculo != "")
           $resac = db_query("insert into db_acount values($acount,1316,14546,'".AddSlashes(pg_result($resaco,$conresaco,'v80_docmetcalculo'))."','$this->v80_docmetcalculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedencia e Parágrafos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v80_proced;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Procedencia e Parágrafos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v80_proced;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v80_proced;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v80_proced=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v80_proced));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7851,'$v80_proced','E')");
         $resac = db_query("insert into db_acount values($acount,1316,7851,'','".AddSlashes(pg_result($resaco,$iresaco,'v80_proced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1316,7852,'','".AddSlashes(pg_result($resaco,$iresaco,'v80_docum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1316,14546,'','".AddSlashes(pg_result($resaco,$iresaco,'v80_docmetcalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from procedparag
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v80_proced != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v80_proced = $v80_proced ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedencia e Parágrafos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v80_proced;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Procedencia e Parágrafos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v80_proced;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v80_proced;
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
        $this->erro_sql   = "Record Vazio na Tabela:procedparag";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $v80_proced=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procedparag ";
     $sql .= "      inner join proced  on  proced.v03_codigo = procedparag.v80_proced";
     $sql .= "      inner join db_documento  on  db_documento.db03_docum = procedparag.v80_docum";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = proced.k00_hist";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = proced.v03_receit";
     $sql .= "      inner join db_config  on  db_config.codigo = proced.v03_instit";
     $sql .= "      inner join db_config  as a on   a.codigo = db_documento.db03_instit";
     $sql .= "      inner join db_tipodoc  on  db_tipodoc.db08_codigo = db_documento.db03_tipodoc";
     $sql2 = "";
     if($dbwhere==""){
       if($v80_proced!=null ){
         $sql2 .= " where procedparag.v80_proced = $v80_proced "; 
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
  
	function sql_query_duplo ( $v80_proced=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procedparag ";
     $sql .= "      inner join proced  on  proced.v03_codigo = procedparag.v80_proced";
     $sql .= "      left  join db_documento as db_d1 on  db_d1.db03_docum = procedparag.v80_docum";
     $sql .= "      left  join db_documento as db_d2 on  db_d2.db03_docum = procedparag.v80_docmetcalculo";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = proced.k00_hist";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = proced.v03_receit";
     $sql .= "      inner join db_config  on  db_config.codigo = proced.v03_instit";
     $sql .= "      inner join db_config  as a on   a.codigo = db_d1.db03_instit";
     $sql .= "      inner join db_tipodoc  on  db_tipodoc.db08_codigo = db_d1.db03_tipodoc";
     $sql2 = "";
     if($dbwhere==""){
       if($v80_proced!=null ){
         $sql2 .= " where procedparag.v80_proced = $v80_proced "; 
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
   function sql_query_file ( $v80_proced=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procedparag ";
     $sql2 = "";
     if($dbwhere==""){
       if($v80_proced!=null ){
         $sql2 .= " where procedparag.v80_proced = $v80_proced "; 
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