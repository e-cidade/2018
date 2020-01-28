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

//MODULO: issqn
//CLASSE DA ENTIDADE issvardiv
class cl_issvardiv { 
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
   var $q19_coddiv = 0; 
   var $q19_issvar = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q19_coddiv = int4 = codigo da divida 
                 q19_issvar = int8 = Código 
                 ";
   //funcao construtor da classe 
   function cl_issvardiv() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issvardiv"); 
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
       $this->q19_coddiv = ($this->q19_coddiv == ""?@$GLOBALS["HTTP_POST_VARS"]["q19_coddiv"]:$this->q19_coddiv);
       $this->q19_issvar = ($this->q19_issvar == ""?@$GLOBALS["HTTP_POST_VARS"]["q19_issvar"]:$this->q19_issvar);
     }else{
       $this->q19_coddiv = ($this->q19_coddiv == ""?@$GLOBALS["HTTP_POST_VARS"]["q19_coddiv"]:$this->q19_coddiv);
       $this->q19_issvar = ($this->q19_issvar == ""?@$GLOBALS["HTTP_POST_VARS"]["q19_issvar"]:$this->q19_issvar);
     }
   }
   // funcao para inclusao
   function incluir ($q19_coddiv,$q19_issvar){ 
      $this->atualizacampos();
       $this->q19_coddiv = $q19_coddiv; 
       $this->q19_issvar = $q19_issvar; 
     if(($this->q19_coddiv == null) || ($this->q19_coddiv == "") ){ 
       $this->erro_sql = " Campo q19_coddiv nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->q19_issvar == null) || ($this->q19_issvar == "") ){ 
       $this->erro_sql = " Campo q19_issvar nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issvardiv(
                                       q19_coddiv 
                                      ,q19_issvar 
                       )
                values (
                                $this->q19_coddiv 
                               ,$this->q19_issvar 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Variavel importado para divida ($this->q19_coddiv."-".$this->q19_issvar) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Variavel importado para divida já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Variavel importado para divida ($this->q19_coddiv."-".$this->q19_issvar) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q19_coddiv."-".$this->q19_issvar;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q19_coddiv,$this->q19_issvar));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6539,'$this->q19_coddiv','I')");
       $resac = db_query("insert into db_acountkey values($acount,6540,'$this->q19_issvar','I')");
       $resac = db_query("insert into db_acount values($acount,1075,6539,'','".AddSlashes(pg_result($resaco,0,'q19_coddiv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1075,6540,'','".AddSlashes(pg_result($resaco,0,'q19_issvar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q19_coddiv=null,$q19_issvar=null) { 
      $this->atualizacampos();
     $sql = " update issvardiv set ";
     $virgula = "";
     if(trim($this->q19_coddiv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q19_coddiv"])){ 
       $sql  .= $virgula." q19_coddiv = $this->q19_coddiv ";
       $virgula = ",";
       if(trim($this->q19_coddiv) == null ){ 
         $this->erro_sql = " Campo codigo da divida nao Informado.";
         $this->erro_campo = "q19_coddiv";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q19_issvar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q19_issvar"])){ 
       $sql  .= $virgula." q19_issvar = $this->q19_issvar ";
       $virgula = ",";
       if(trim($this->q19_issvar) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "q19_issvar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q19_coddiv!=null){
       $sql .= " q19_coddiv = $this->q19_coddiv";
     }
     if($q19_issvar!=null){
       $sql .= " and  q19_issvar = $this->q19_issvar";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q19_coddiv,$this->q19_issvar));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6539,'$this->q19_coddiv','A')");
         $resac = db_query("insert into db_acountkey values($acount,6540,'$this->q19_issvar','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q19_coddiv"]))
           $resac = db_query("insert into db_acount values($acount,1075,6539,'".AddSlashes(pg_result($resaco,$conresaco,'q19_coddiv'))."','$this->q19_coddiv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q19_issvar"]))
           $resac = db_query("insert into db_acount values($acount,1075,6540,'".AddSlashes(pg_result($resaco,$conresaco,'q19_issvar'))."','$this->q19_issvar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Variavel importado para divida nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q19_coddiv."-".$this->q19_issvar;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Variavel importado para divida nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q19_coddiv."-".$this->q19_issvar;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q19_coddiv."-".$this->q19_issvar;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q19_coddiv=null,$q19_issvar=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q19_coddiv,$q19_issvar));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6539,'$q19_coddiv','E')");
         $resac = db_query("insert into db_acountkey values($acount,6540,'$q19_issvar','E')");
         $resac = db_query("insert into db_acount values($acount,1075,6539,'','".AddSlashes(pg_result($resaco,$iresaco,'q19_coddiv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1075,6540,'','".AddSlashes(pg_result($resaco,$iresaco,'q19_issvar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issvardiv
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q19_coddiv != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q19_coddiv = $q19_coddiv ";
        }
        if($q19_issvar != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q19_issvar = $q19_issvar ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Variavel importado para divida nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q19_coddiv."-".$q19_issvar;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Variavel importado para divida nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q19_coddiv."-".$q19_issvar;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q19_coddiv."-".$q19_issvar;
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
        $this->erro_sql   = "Record Vazio na Tabela:issvardiv";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q19_coddiv=null,$q19_issvar=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issvardiv ";
     $sql .= "      inner join issvar  on  issvar.q05_codigo = issvardiv.q19_issvar";
     $sql .= "      inner join divida  on  divida.v01_coddiv = issvardiv.q19_coddiv";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = divida.v01_numcgm";
     $sql .= "      inner join proced  on  proced.v03_codigo = divida.v01_proced";
     $sql2 = "";
     if($dbwhere==""){
       if($q19_coddiv!=null ){
         $sql2 .= " where issvardiv.q19_coddiv = $q19_coddiv "; 
       } 
       if($q19_issvar!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " issvardiv.q19_issvar = $q19_issvar "; 
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
   function sql_query_file ( $q19_coddiv=null,$q19_issvar=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issvardiv ";
     $sql2 = "";
     if($dbwhere==""){
       if($q19_coddiv!=null ){
         $sql2 .= " where issvardiv.q19_coddiv = $q19_coddiv "; 
       } 
       if($q19_issvar!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " issvardiv.q19_issvar = $q19_issvar "; 
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