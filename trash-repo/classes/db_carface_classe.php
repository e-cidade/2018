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
//CLASSE DA ENTIDADE carface
class cl_carface { 
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
   var $j38_face = 0; 
   var $j38_caract = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j38_face = int4 = Face 
                 j38_caract = int4 = Caracteristica 
                 ";
   //funcao construtor da classe 
   function cl_carface() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("carface"); 
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
       $this->j38_face = ($this->j38_face == ""?@$GLOBALS["HTTP_POST_VARS"]["j38_face"]:$this->j38_face);
       $this->j38_caract = ($this->j38_caract == ""?@$GLOBALS["HTTP_POST_VARS"]["j38_caract"]:$this->j38_caract);
     }else{
       $this->j38_face = ($this->j38_face == ""?@$GLOBALS["HTTP_POST_VARS"]["j38_face"]:$this->j38_face);
       $this->j38_caract = ($this->j38_caract == ""?@$GLOBALS["HTTP_POST_VARS"]["j38_caract"]:$this->j38_caract);
     }
   }
   // funcao para inclusao
   function incluir ($j38_face,$j38_caract){ 
      $this->atualizacampos();
       $this->j38_face = $j38_face; 
       $this->j38_caract = $j38_caract; 
     if(($this->j38_face == null) || ($this->j38_face == "") ){ 
       $this->erro_sql = " Campo j38_face nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j38_caract == null) || ($this->j38_caract == "") ){ 
       $this->erro_sql = " Campo j38_caract nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into carface(
                                       j38_face 
                                      ,j38_caract 
                       )
                values (
                                $this->j38_face 
                               ,$this->j38_caract 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Caracteristica da Face ($this->j38_face."-".$this->j38_caract) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Caracteristica da Face j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Caracteristica da Face ($this->j38_face."-".$this->j38_caract) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j38_face."-".$this->j38_caract;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j38_face,$this->j38_caract));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,77,'$this->j38_face','I')");
       $resac = db_query("insert into db_acountkey values($acount,78,'$this->j38_caract','I')");
       $resac = db_query("insert into db_acount values($acount,18,77,'','".AddSlashes(pg_result($resaco,0,'j38_face'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,18,78,'','".AddSlashes(pg_result($resaco,0,'j38_caract'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j38_face=null,$j38_caract=null) { 
      $this->atualizacampos();
     $sql = " update carface set ";
     $virgula = "";
     if(trim($this->j38_face)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j38_face"])){ 
       $sql  .= $virgula." j38_face = $this->j38_face ";
       $virgula = ",";
       if(trim($this->j38_face) == null ){ 
         $this->erro_sql = " Campo Face nao Informado.";
         $this->erro_campo = "j38_face";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j38_caract)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j38_caract"])){ 
       $sql  .= $virgula." j38_caract = $this->j38_caract ";
       $virgula = ",";
       if(trim($this->j38_caract) == null ){ 
         $this->erro_sql = " Campo Caracteristica nao Informado.";
         $this->erro_campo = "j38_caract";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j38_face!=null){
       $sql .= " j38_face = $this->j38_face";
     }
     if($j38_caract!=null){
       $sql .= " and  j38_caract = $this->j38_caract";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j38_face,$this->j38_caract));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,77,'$this->j38_face','A')");
         $resac = db_query("insert into db_acountkey values($acount,78,'$this->j38_caract','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j38_face"]))
           $resac = db_query("insert into db_acount values($acount,18,77,'".AddSlashes(pg_result($resaco,$conresaco,'j38_face'))."','$this->j38_face',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j38_caract"]))
           $resac = db_query("insert into db_acount values($acount,18,78,'".AddSlashes(pg_result($resaco,$conresaco,'j38_caract'))."','$this->j38_caract',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Caracteristica da Face nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j38_face."-".$this->j38_caract;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Caracteristica da Face nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j38_face."-".$this->j38_caract;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j38_face."-".$this->j38_caract;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j38_face=null,$j38_caract=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j38_face,$j38_caract));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,77,'$j38_face','E')");
         $resac = db_query("insert into db_acountkey values($acount,78,'$j38_caract','E')");
         $resac = db_query("insert into db_acount values($acount,18,77,'','".AddSlashes(pg_result($resaco,$iresaco,'j38_face'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,18,78,'','".AddSlashes(pg_result($resaco,$iresaco,'j38_caract'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from carface
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j38_face != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j38_face = $j38_face ";
        }
        if($j38_caract != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j38_caract = $j38_caract ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Caracteristica da Face nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j38_face."-".$j38_caract;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Caracteristica da Face nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j38_face."-".$j38_caract;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j38_face."-".$j38_caract;
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
        $this->erro_sql   = "Record Vazio na Tabela:carface";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j38_face=null,$j38_caract=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from carface ";
     $sql .= "      inner join caracter  on  caracter.j31_codigo = carface.j38_caract";
     $sql .= "      inner join face  on  face.j37_face = carface.j38_face";
     $sql .= "      inner join cargrup  on  cargrup.j32_grupo = caracter.j31_grupo";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = face.j37_codigo";
     $sql .= "      inner join setor  on  setor.j30_codi = face.j37_setor";
     $sql2 = "";
     if($dbwhere==""){
       if($j38_face!=null ){
         $sql2 .= " where carface.j38_face = $j38_face "; 
       } 
       if($j38_caract!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " carface.j38_caract = $j38_caract "; 
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
   function sql_query_file ( $j38_face=null,$j38_caract=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from carface ";
     $sql2 = "";
     if($dbwhere==""){
       if($j38_face!=null ){
         $sql2 .= " where carface.j38_face = $j38_face "; 
       } 
       if($j38_caract!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " carface.j38_caract = $j38_caract "; 
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
  
  public function sql_queryCaracteristicasFace($iCodigoLote) {
    
    $sSql  = "select *                                        ";
    $sSql .= "  from testpri                                  ";
    $sSql .= " inner join carface  on j49_face   = j38_face   ";
    $sSql .= " inner join caracter on j31_codigo = j38_caract ";
    $sSql .= " inner join cargrup  on j32_grupo  = j31_grupo  ";
    $sSql .= " where j49_idbql = {$iCodigoLote}               ";
    
    return $sSql;
    
  }
}
?>