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

//MODULO: cadastro
//CLASSE DA ENTIDADE caraliq
class cl_caraliq { 
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
   var $j73_anousu = 0; 
   var $j73_caract = 0; 
   var $j73_aliq = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j73_anousu = int4 = Ano 
                 j73_caract = int8 = Caracteristica 
                 j73_aliq = float8 = Aliquota 
                 ";
   //funcao construtor da classe 
   function cl_caraliq() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("caraliq"); 
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
       $this->j73_anousu = ($this->j73_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j73_anousu"]:$this->j73_anousu);
       $this->j73_caract = ($this->j73_caract == ""?@$GLOBALS["HTTP_POST_VARS"]["j73_caract"]:$this->j73_caract);
       $this->j73_aliq = ($this->j73_aliq == ""?@$GLOBALS["HTTP_POST_VARS"]["j73_aliq"]:$this->j73_aliq);
     }else{
       $this->j73_anousu = ($this->j73_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j73_anousu"]:$this->j73_anousu);
       $this->j73_caract = ($this->j73_caract == ""?@$GLOBALS["HTTP_POST_VARS"]["j73_caract"]:$this->j73_caract);
     }
   }
   // funcao para inclusao
   function incluir ($j73_anousu,$j73_caract){ 
      $this->atualizacampos();
     if($this->j73_aliq == null ){ 
       $this->erro_sql = " Campo Aliquota nao Informado.";
       $this->erro_campo = "j73_aliq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->j73_anousu = $j73_anousu; 
       $this->j73_caract = $j73_caract; 
     if(($this->j73_anousu == null) || ($this->j73_anousu == "") ){ 
       $this->erro_sql = " Campo j73_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j73_caract == null) || ($this->j73_caract == "") ){ 
       $this->erro_sql = " Campo j73_caract nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into caraliq(
                                       j73_anousu 
                                      ,j73_caract 
                                      ,j73_aliq 
                       )
                values (
                                $this->j73_anousu 
                               ,$this->j73_caract 
                               ,$this->j73_aliq 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Aliquotas por caracteristica e ano ($this->j73_anousu."-".$this->j73_caract) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Aliquotas por caracteristica e ano já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Aliquotas por caracteristica e ano ($this->j73_anousu."-".$this->j73_caract) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j73_anousu."-".$this->j73_caract;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j73_anousu,$this->j73_caract));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7616,'$this->j73_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,7617,'$this->j73_caract','I')");
       $resac = db_query("insert into db_acount values($acount,1264,7616,'','".AddSlashes(pg_result($resaco,0,'j73_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1264,7617,'','".AddSlashes(pg_result($resaco,0,'j73_caract'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1264,7618,'','".AddSlashes(pg_result($resaco,0,'j73_aliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j73_anousu=null,$j73_caract=null) { 
      $this->atualizacampos();
     $sql = " update caraliq set ";
     $virgula = "";
     if(trim($this->j73_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j73_anousu"])){ 
       $sql  .= $virgula." j73_anousu = $this->j73_anousu ";
       $virgula = ",";
       if(trim($this->j73_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "j73_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j73_caract)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j73_caract"])){ 
       $sql  .= $virgula." j73_caract = $this->j73_caract ";
       $virgula = ",";
       if(trim($this->j73_caract) == null ){ 
         $this->erro_sql = " Campo Caracteristica nao Informado.";
         $this->erro_campo = "j73_caract";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j73_aliq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j73_aliq"])){ 
       $sql  .= $virgula." j73_aliq = $this->j73_aliq ";
       $virgula = ",";
       if(trim($this->j73_aliq) == null ){ 
         $this->erro_sql = " Campo Aliquota nao Informado.";
         $this->erro_campo = "j73_aliq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j73_anousu!=null){
       $sql .= " j73_anousu = $this->j73_anousu";
     }
     if($j73_caract!=null){
       $sql .= " and  j73_caract = $this->j73_caract";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j73_anousu,$this->j73_caract));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7616,'$this->j73_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,7617,'$this->j73_caract','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j73_anousu"]) || $this->j73_anousu != "")
           $resac = db_query("insert into db_acount values($acount,1264,7616,'".AddSlashes(pg_result($resaco,$conresaco,'j73_anousu'))."','$this->j73_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j73_caract"]) || $this->j73_caract != "")
           $resac = db_query("insert into db_acount values($acount,1264,7617,'".AddSlashes(pg_result($resaco,$conresaco,'j73_caract'))."','$this->j73_caract',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j73_aliq"]) || $this->j73_aliq != "")
           $resac = db_query("insert into db_acount values($acount,1264,7618,'".AddSlashes(pg_result($resaco,$conresaco,'j73_aliq'))."','$this->j73_aliq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Aliquotas por caracteristica e ano nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j73_anousu."-".$this->j73_caract;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Aliquotas por caracteristica e ano nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j73_anousu."-".$this->j73_caract;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j73_anousu."-".$this->j73_caract;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j73_anousu=null,$j73_caract=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j73_anousu,$j73_caract));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7616,'$j73_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,7617,'$j73_caract','E')");
         $resac = db_query("insert into db_acount values($acount,1264,7616,'','".AddSlashes(pg_result($resaco,$iresaco,'j73_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1264,7617,'','".AddSlashes(pg_result($resaco,$iresaco,'j73_caract'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1264,7618,'','".AddSlashes(pg_result($resaco,$iresaco,'j73_aliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from caraliq
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j73_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j73_anousu = $j73_anousu ";
        }
        if($j73_caract != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j73_caract = $j73_caract ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Aliquotas por caracteristica e ano nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j73_anousu."-".$j73_caract;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Aliquotas por caracteristica e ano nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j73_anousu."-".$j73_caract;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j73_anousu."-".$j73_caract;
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
        $this->erro_sql   = "Record Vazio na Tabela:caraliq";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j73_anousu=null,$j73_caract=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from caraliq ";
     $sql .= "      inner join caracter  on  caracter.j31_codigo = caraliq.j73_caract";
     $sql .= "      inner join cargrup  on  cargrup.j32_grupo = caracter.j31_grupo";
     $sql2 = "";
     if($dbwhere==""){
       if($j73_anousu!=null ){
         $sql2 .= " where caraliq.j73_anousu = $j73_anousu "; 
       } 
       if($j73_caract!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " caraliq.j73_caract = $j73_caract "; 
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
   function sql_query_file ( $j73_anousu=null,$j73_caract=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from caraliq ";
     $sql2 = "";
     if($dbwhere==""){
       if($j73_anousu!=null ){
         $sql2 .= " where caraliq.j73_anousu = $j73_anousu "; 
       } 
       if($j73_caract!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " caraliq.j73_caract = $j73_caract "; 
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