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

//MODULO: contrib
//CLASSE DA ENTIDADE contlot
class cl_contlot { 
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
   var $d05_contri = 0; 
   var $d05_idbql = 0; 
   var $d05_testad = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 d05_contri = int4 = Contribuicao 
                 d05_idbql = int4 = Codigo do lote 
                 d05_testad = float8 = Testada 
                 ";
   //funcao construtor da classe 
   function cl_contlot() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("contlot"); 
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
       $this->d05_contri = ($this->d05_contri == ""?@$GLOBALS["HTTP_POST_VARS"]["d05_contri"]:$this->d05_contri);
       $this->d05_idbql = ($this->d05_idbql == ""?@$GLOBALS["HTTP_POST_VARS"]["d05_idbql"]:$this->d05_idbql);
       $this->d05_testad = ($this->d05_testad == ""?@$GLOBALS["HTTP_POST_VARS"]["d05_testad"]:$this->d05_testad);
     }else{
       $this->d05_contri = ($this->d05_contri == ""?@$GLOBALS["HTTP_POST_VARS"]["d05_contri"]:$this->d05_contri);
       $this->d05_idbql = ($this->d05_idbql == ""?@$GLOBALS["HTTP_POST_VARS"]["d05_idbql"]:$this->d05_idbql);
     }
   }
   // funcao para inclusao
   function incluir ($d05_contri,$d05_idbql){ 
      $this->atualizacampos();
     if($this->d05_testad == null ){ 
       $this->erro_sql = " Campo Testada nao Informado.";
       $this->erro_campo = "d05_testad";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->d05_contri = $d05_contri; 
       $this->d05_idbql = $d05_idbql; 
     if(($this->d05_contri == null) || ($this->d05_contri == "") ){ 
       $this->erro_sql = " Campo d05_contri nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->d05_idbql == null) || ($this->d05_idbql == "") ){ 
       $this->erro_sql = " Campo d05_idbql nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into contlot(
                                       d05_contri 
                                      ,d05_idbql 
                                      ,d05_testad 
                       )
                values (
                                $this->d05_contri 
                               ,$this->d05_idbql 
                               ,$this->d05_testad 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->d05_contri."-".$this->d05_idbql) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->d05_contri."-".$this->d05_idbql) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d05_contri."-".$this->d05_idbql;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->d05_contri,$this->d05_idbql));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,699,'$this->d05_contri','I')");
       $resac = db_query("insert into db_acountkey values($acount,700,'$this->d05_idbql','I')");
       $resac = db_query("insert into db_acount values($acount,130,699,'','".AddSlashes(pg_result($resaco,0,'d05_contri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,130,700,'','".AddSlashes(pg_result($resaco,0,'d05_idbql'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,130,701,'','".AddSlashes(pg_result($resaco,0,'d05_testad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($d05_contri=null,$d05_idbql=null) { 
      $this->atualizacampos();
     $sql = " update contlot set ";
     $virgula = "";
     if(trim($this->d05_contri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d05_contri"])){ 
        if(trim($this->d05_contri)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d05_contri"])){ 
           $this->d05_contri = "0" ; 
        } 
       $sql  .= $virgula." d05_contri = $this->d05_contri ";
       $virgula = ",";
       if(trim($this->d05_contri) == null ){ 
         $this->erro_sql = " Campo Contribuicao nao Informado.";
         $this->erro_campo = "d05_contri";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d05_idbql)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d05_idbql"])){ 
        if(trim($this->d05_idbql)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d05_idbql"])){ 
           $this->d05_idbql = "0" ; 
        } 
       $sql  .= $virgula." d05_idbql = $this->d05_idbql ";
       $virgula = ",";
       if(trim($this->d05_idbql) == null ){ 
         $this->erro_sql = " Campo Codigo do lote nao Informado.";
         $this->erro_campo = "d05_idbql";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d05_testad)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d05_testad"])){ 
        if(trim($this->d05_testad)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d05_testad"])){ 
           $this->d05_testad = "0" ; 
        } 
       $sql  .= $virgula." d05_testad = $this->d05_testad ";
       $virgula = ",";
       if(trim($this->d05_testad) == null ){ 
         $this->erro_sql = " Campo Testada nao Informado.";
         $this->erro_campo = "d05_testad";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($d05_contri!=null){
       $sql .= " d05_contri = $this->d05_contri";
     }
     if($d05_idbql!=null){
       $sql .= " and  d05_idbql = $this->d05_idbql";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->d05_contri,$this->d05_idbql));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,699,'$this->d05_contri','A')");
         $resac = db_query("insert into db_acountkey values($acount,700,'$this->d05_idbql','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d05_contri"]))
           $resac = db_query("insert into db_acount values($acount,130,699,'".AddSlashes(pg_result($resaco,$conresaco,'d05_contri'))."','$this->d05_contri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d05_idbql"]))
           $resac = db_query("insert into db_acount values($acount,130,700,'".AddSlashes(pg_result($resaco,$conresaco,'d05_idbql'))."','$this->d05_idbql',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d05_testad"]))
           $resac = db_query("insert into db_acount values($acount,130,701,'".AddSlashes(pg_result($resaco,$conresaco,'d05_testad'))."','$this->d05_testad',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->d05_contri."-".$this->d05_idbql;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->d05_contri."-".$this->d05_idbql;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d05_contri."-".$this->d05_idbql;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($d05_contri=null,$d05_idbql=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($d05_contri,$d05_idbql));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,699,'$d05_contri','E')");
         $resac = db_query("insert into db_acountkey values($acount,700,'$d05_idbql','E')");
         $resac = db_query("insert into db_acount values($acount,130,699,'','".AddSlashes(pg_result($resaco,$iresaco,'d05_contri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,130,700,'','".AddSlashes(pg_result($resaco,$iresaco,'d05_idbql'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,130,701,'','".AddSlashes(pg_result($resaco,$iresaco,'d05_testad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from contlot
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($d05_contri != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d05_contri = $d05_contri ";
        }
        if($d05_idbql != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d05_idbql = $d05_idbql ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$d05_contri."-".$d05_idbql;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$d05_contri."-".$d05_idbql;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$d05_contri."-".$d05_idbql;
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
        $this->erro_sql   = "Record Vazio na Tabela:contlot";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $d05_contri=null,$d05_idbql=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from contlot ";
     $sql .= "      inner join lote  on  lote.j34_idbql = contlot.d05_idbql";
     $sql .= "      inner join editalrua  on  editalrua.d02_contri = contlot.d05_contri";
     $sql .= "      inner join bairro  on  bairro.j13_codi = lote.j34_bairro";
     $sql .= "      inner join setor  on  setor.j30_codi = lote.j34_setor";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = editalrua.d02_codigo";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = editalrua.d02_idlog";
     $sql .= "      inner join edital  as a on   a.d01_codedi = editalrua.d02_codedi";
     $sql2 = "";
     if($dbwhere==""){
       if($d05_contri!=null ){
         $sql2 .= " where contlot.d05_contri = $d05_contri "; 
       } 
       if($d05_idbql!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " contlot.d05_idbql = $d05_idbql "; 
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
   function sql_query_file ( $d05_contri=null,$d05_idbql=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from contlot ";
     $sql2 = "";
     if($dbwhere==""){
       if($d05_contri!=null ){
         $sql2 .= " where contlot.d05_contri = $d05_contri "; 
       } 
       if($d05_idbql!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " contlot.d05_idbql = $d05_idbql "; 
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