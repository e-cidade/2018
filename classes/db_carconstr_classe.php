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

//MODULO: cadastro
//CLASSE DA ENTIDADE carconstr
class cl_carconstr { 
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
   var $j48_matric = 0; 
   var $j48_idcons = 0; 
   var $j48_caract = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j48_matric = int4 = Matricula 
                 j48_idcons = int4 = Codigo Construcao 
                 j48_caract = int4 = Caracteristica 
                 ";
   //funcao construtor da classe 
   function cl_carconstr() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("carconstr"); 
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
       $this->j48_matric = ($this->j48_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j48_matric"]:$this->j48_matric);
       $this->j48_idcons = ($this->j48_idcons == ""?@$GLOBALS["HTTP_POST_VARS"]["j48_idcons"]:$this->j48_idcons);
       $this->j48_caract = ($this->j48_caract == ""?@$GLOBALS["HTTP_POST_VARS"]["j48_caract"]:$this->j48_caract);
     }else{
       $this->j48_matric = ($this->j48_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j48_matric"]:$this->j48_matric);
       $this->j48_idcons = ($this->j48_idcons == ""?@$GLOBALS["HTTP_POST_VARS"]["j48_idcons"]:$this->j48_idcons);
       $this->j48_caract = ($this->j48_caract == ""?@$GLOBALS["HTTP_POST_VARS"]["j48_caract"]:$this->j48_caract);
     }
   }
   // funcao para inclusao
   function incluir ($j48_matric,$j48_idcons,$j48_caract){ 
      $this->atualizacampos();
       $this->j48_matric = $j48_matric; 
       $this->j48_idcons = $j48_idcons; 
       $this->j48_caract = $j48_caract; 
     if(($this->j48_matric == null) || ($this->j48_matric == "") ){ 
       $this->erro_sql = " Campo j48_matric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j48_idcons == null) || ($this->j48_idcons == "") ){ 
       $this->erro_sql = " Campo j48_idcons nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j48_caract == null) || ($this->j48_caract == "") ){ 
       $this->erro_sql = " Campo j48_caract nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into carconstr(
                                       j48_matric 
                                      ,j48_idcons 
                                      ,j48_caract 
                       )
                values (
                                $this->j48_matric 
                               ,$this->j48_idcons 
                               ,$this->j48_caract 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->j48_matric."-".$this->j48_idcons."-".$this->j48_caract) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->j48_matric."-".$this->j48_idcons."-".$this->j48_caract) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j48_matric."-".$this->j48_idcons."-".$this->j48_caract;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j48_matric,$this->j48_idcons,$this->j48_caract));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,159,'$this->j48_matric','I')");
       $resac = db_query("insert into db_acountkey values($acount,160,'$this->j48_idcons','I')");
       $resac = db_query("insert into db_acountkey values($acount,161,'$this->j48_caract','I')");
       $resac = db_query("insert into db_acount values($acount,31,159,'','".AddSlashes(pg_result($resaco,0,'j48_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,31,160,'','".AddSlashes(pg_result($resaco,0,'j48_idcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,31,161,'','".AddSlashes(pg_result($resaco,0,'j48_caract'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j48_matric=null,$j48_idcons=null,$j48_caract=null) { 
      $this->atualizacampos();
     $sql = " update carconstr set ";
     $virgula = "";
     if(trim($this->j48_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j48_matric"])){ 
       $sql  .= $virgula." j48_matric = $this->j48_matric ";
       $virgula = ",";
       if(trim($this->j48_matric) == null ){ 
         $this->erro_sql = " Campo Matricula nao Informado.";
         $this->erro_campo = "j48_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j48_idcons)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j48_idcons"])){ 
       $sql  .= $virgula." j48_idcons = $this->j48_idcons ";
       $virgula = ",";
       if(trim($this->j48_idcons) == null ){ 
         $this->erro_sql = " Campo Codigo Construcao nao Informado.";
         $this->erro_campo = "j48_idcons";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j48_caract)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j48_caract"])){ 
       $sql  .= $virgula." j48_caract = $this->j48_caract ";
       $virgula = ",";
       if(trim($this->j48_caract) == null ){ 
         $this->erro_sql = " Campo Caracteristica nao Informado.";
         $this->erro_campo = "j48_caract";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j48_matric!=null){
       $sql .= " j48_matric = $this->j48_matric";
     }
     if($j48_idcons!=null){
       $sql .= " and  j48_idcons = $this->j48_idcons";
     }
     if($j48_caract!=null){
       $sql .= " and  j48_caract = $this->j48_caract";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j48_matric,$this->j48_idcons,$this->j48_caract));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,159,'$this->j48_matric','A')");
         $resac = db_query("insert into db_acountkey values($acount,160,'$this->j48_idcons','A')");
         $resac = db_query("insert into db_acountkey values($acount,161,'$this->j48_caract','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j48_matric"]))
           $resac = db_query("insert into db_acount values($acount,31,159,'".AddSlashes(pg_result($resaco,$conresaco,'j48_matric'))."','$this->j48_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j48_idcons"]))
           $resac = db_query("insert into db_acount values($acount,31,160,'".AddSlashes(pg_result($resaco,$conresaco,'j48_idcons'))."','$this->j48_idcons',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j48_caract"]))
           $resac = db_query("insert into db_acount values($acount,31,161,'".AddSlashes(pg_result($resaco,$conresaco,'j48_caract'))."','$this->j48_caract',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j48_matric."-".$this->j48_idcons."-".$this->j48_caract;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j48_matric."-".$this->j48_idcons."-".$this->j48_caract;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j48_matric."-".$this->j48_idcons."-".$this->j48_caract;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j48_matric=null,$j48_idcons=null,$j48_caract=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j48_matric,$j48_idcons,$j48_caract));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,159,'$j48_matric','E')");
         $resac = db_query("insert into db_acountkey values($acount,160,'$j48_idcons','E')");
         $resac = db_query("insert into db_acountkey values($acount,161,'$j48_caract','E')");
         $resac = db_query("insert into db_acount values($acount,31,159,'','".AddSlashes(pg_result($resaco,$iresaco,'j48_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,31,160,'','".AddSlashes(pg_result($resaco,$iresaco,'j48_idcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,31,161,'','".AddSlashes(pg_result($resaco,$iresaco,'j48_caract'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from carconstr
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j48_matric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j48_matric = $j48_matric ";
        }
        if($j48_idcons != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j48_idcons = $j48_idcons ";
        }
        if($j48_caract != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j48_caract = $j48_caract ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j48_matric."-".$j48_idcons."-".$j48_caract;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j48_matric."-".$j48_idcons."-".$j48_caract;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j48_matric."-".$j48_idcons."-".$j48_caract;
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
        $this->erro_sql   = "Record Vazio na Tabela:carconstr";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j48_matric=null,$j48_idcons=null,$j48_caract=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from carconstr ";
     $sql .= "      inner join iptuconstr  on  iptuconstr.j39_matric = carconstr.j48_matric and  iptuconstr.j39_idcons = carconstr.j48_idcons";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = iptuconstr.j39_codigo";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = iptuconstr.j39_matric";
     $sql2 = "";
     if($dbwhere==""){
       if($j48_matric!=null ){
         $sql2 .= " where carconstr.j48_matric = $j48_matric "; 
       } 
       if($j48_idcons!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " carconstr.j48_idcons = $j48_idcons "; 
       } 
       if($j48_caract!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " carconstr.j48_caract = $j48_caract "; 
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
   function sql_query_file ( $j48_matric=null,$j48_idcons=null,$j48_caract=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from carconstr ";
     $sql2 = "";
     if($dbwhere==""){
       if($j48_matric!=null ){
         $sql2 .= " where carconstr.j48_matric = $j48_matric "; 
       } 
       if($j48_idcons!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " carconstr.j48_idcons = $j48_idcons "; 
       } 
       if($j48_caract!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " carconstr.j48_caract = $j48_caract "; 
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
  
  function sql_queryCaracteristicas($iMatricula, $iCodigoConstrucao) {
    
    $sSql  = "select *                                          ";
    $sSql .= "  from carconstr                                  ";
    $sSql .= "       left join caracter on j31_codigo = j48_caract  ";
    $sSql .= "       left join cargrup  on j32_grupo  = j31_grupo   ";
    $sSql .= "                         and j32_tipo   = 'C'         "; 
    $sSql .= " where j48_matric = {$iMatricula}               ";
    $sSql .= "   and j48_idcons = {$iCodigoConstrucao}        ";

    return $sSql;
     
  }
  
  /**
   * Pesquisa os grupos de caracteristicas e seuas seleções por matricula e id_CONSTR
   * @param integer $iMatricula
   * @param integer $iIdConstrucao
   * @return string
   */
  function sql_querySelecaoCaracteristicas($iMatricula, $iIdConstrucao) {

    $sSql = "select distinct                                                                                      \n";
    $sSql.= "   j32_grupo,                                                                                        \n";
    $sSql.= "   j32_descr,                                                                                        \n";
    $sSql.= "   j31_codigo,                                                                                       \n";
    $sSql.= "   j31_descr,                                                                                        \n";
    $sSql.= "   j48_idcons,                                                                                       \n";
    $sSql.= "   j48_matric,                                                                                       \n";
    $sSql.= "   case when j48_idcons is not null                                                                  \n";
    $sSql.= "    then true                                                                                        \n";
    $sSql.= "    else case when (exists( select 1                                                                 \n";
    $sSql.= "                             from carpadrao                                                          \n";
    $sSql.= "                            where carpadrao.j33_codcaracter = caracter.j31_codigo )                  \n";
    $sSql.= "                    and                                                                              \n";
    $sSql.= "                    not exists( select 1                                                             \n";
    $sSql.= "                                  from carconstr           as sub_construcao                         \n";
    $sSql.= "                                       inner join caracter as sub_carcteristica                      \n";
    $sSql.= "                                         on sub_carcteristica.j31_codigo = sub_construcao.j48_caract \n";
    $sSql.= "                                 where cargrup.j32_grupo = sub_carcteristica.j31_grupo)              \n";
    $sSql.= "                   )                                                                                 \n";
    $sSql.= "              then true                                                                              \n";
    $sSql.= "              else false                                                                             \n";
    $sSql.= "         end                                                                                         \n";
    $sSql.= "   end as selecionada                                                                                \n";
    $sSql.= "  from caracter                                                                                      \n";
    $sSql.= "   inner join cargrup             on cargrup.j32_grupo = caracter.j31_grupo                          \n";
    $sSql.= "   left  join carconstr           on j31_codigo        = j48_caract                                  \n";
    $sSql.= "                                     and j48_matric        = {$iMatricula}                           \n";
    $sSql.= "                                     and j48_idcons        = {$iIdConstrucao}                        \n";
    $sSql.= " where j32_tipo = 'C'                                                                                \n";
    $sSql.= " order by j32_grupo                                                                                  \n";
    
    return $sSql;
  }
  
}
?>