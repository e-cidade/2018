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

//MODULO: cadastro
//CLASSE DA ENTIDADE constrcar
class cl_constrcar { 
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
   var $j53_matric = 0; 
   var $j53_idcons = 0; 
   var $j53_caract = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j53_matric = int4 = Matricula 
                 j53_idcons = int4 = Codigo Construcao 
                 j53_caract = int4 = Caracteristica 
                 ";
   //funcao construtor da classe 
   function cl_constrcar() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("constrcar"); 
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
       $this->j53_matric = ($this->j53_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j53_matric"]:$this->j53_matric);
       $this->j53_idcons = ($this->j53_idcons == ""?@$GLOBALS["HTTP_POST_VARS"]["j53_idcons"]:$this->j53_idcons);
       $this->j53_caract = ($this->j53_caract == ""?@$GLOBALS["HTTP_POST_VARS"]["j53_caract"]:$this->j53_caract);
     }else{
       $this->j53_matric = ($this->j53_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j53_matric"]:$this->j53_matric);
       $this->j53_idcons = ($this->j53_idcons == ""?@$GLOBALS["HTTP_POST_VARS"]["j53_idcons"]:$this->j53_idcons);
       $this->j53_caract = ($this->j53_caract == ""?@$GLOBALS["HTTP_POST_VARS"]["j53_caract"]:$this->j53_caract);
     }
   }
   // funcao para inclusao
   function incluir ($j53_matric,$j53_idcons,$j53_caract){ 
      $this->atualizacampos();
       $this->j53_matric = $j53_matric; 
       $this->j53_idcons = $j53_idcons; 
       $this->j53_caract = $j53_caract; 
     if(($this->j53_matric == null) || ($this->j53_matric == "") ){ 
       $this->erro_sql = " Campo j53_matric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j53_idcons == null) || ($this->j53_idcons == "") ){ 
       $this->erro_sql = " Campo j53_idcons nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j53_caract == null) || ($this->j53_caract == "") ){ 
       $this->erro_sql = " Campo j53_caract nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into constrcar(
                                       j53_matric 
                                      ,j53_idcons 
                                      ,j53_caract 
                       )
                values (
                                $this->j53_matric 
                               ,$this->j53_idcons 
                               ,$this->j53_caract 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->j53_matric."-".$this->j53_idcons."-".$this->j53_caract) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->j53_matric."-".$this->j53_idcons."-".$this->j53_caract) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j53_matric."-".$this->j53_idcons."-".$this->j53_caract;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j53_matric,$this->j53_idcons,$this->j53_caract));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,181,'$this->j53_matric','I')");
       $resac = db_query("insert into db_acountkey values($acount,182,'$this->j53_idcons','I')");
       $resac = db_query("insert into db_acountkey values($acount,183,'$this->j53_caract','I')");
       $resac = db_query("insert into db_acount values($acount,36,181,'','".AddSlashes(pg_result($resaco,0,'j53_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,36,182,'','".AddSlashes(pg_result($resaco,0,'j53_idcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,36,183,'','".AddSlashes(pg_result($resaco,0,'j53_caract'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j53_matric=null,$j53_idcons=null,$j53_caract=null) { 
      $this->atualizacampos();
     $sql = " update constrcar set ";
     $virgula = "";
     if(trim($this->j53_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j53_matric"])){ 
       $sql  .= $virgula." j53_matric = $this->j53_matric ";
       $virgula = ",";
       if(trim($this->j53_matric) == null ){ 
         $this->erro_sql = " Campo Matricula nao Informado.";
         $this->erro_campo = "j53_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j53_idcons)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j53_idcons"])){ 
       $sql  .= $virgula." j53_idcons = $this->j53_idcons ";
       $virgula = ",";
       if(trim($this->j53_idcons) == null ){ 
         $this->erro_sql = " Campo Codigo Construcao nao Informado.";
         $this->erro_campo = "j53_idcons";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j53_caract)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j53_caract"])){ 
       $sql  .= $virgula." j53_caract = $this->j53_caract ";
       $virgula = ",";
       if(trim($this->j53_caract) == null ){ 
         $this->erro_sql = " Campo Caracteristica nao Informado.";
         $this->erro_campo = "j53_caract";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j53_matric!=null){
       $sql .= " j53_matric = $this->j53_matric";
     }
     if($j53_idcons!=null){
       $sql .= " and  j53_idcons = $this->j53_idcons";
     }
     if($j53_caract!=null){
       $sql .= " and  j53_caract = $this->j53_caract";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j53_matric,$this->j53_idcons,$this->j53_caract));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,181,'$this->j53_matric','A')");
         $resac = db_query("insert into db_acountkey values($acount,182,'$this->j53_idcons','A')");
         $resac = db_query("insert into db_acountkey values($acount,183,'$this->j53_caract','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j53_matric"]))
           $resac = db_query("insert into db_acount values($acount,36,181,'".AddSlashes(pg_result($resaco,$conresaco,'j53_matric'))."','$this->j53_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j53_idcons"]))
           $resac = db_query("insert into db_acount values($acount,36,182,'".AddSlashes(pg_result($resaco,$conresaco,'j53_idcons'))."','$this->j53_idcons',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j53_caract"]))
           $resac = db_query("insert into db_acount values($acount,36,183,'".AddSlashes(pg_result($resaco,$conresaco,'j53_caract'))."','$this->j53_caract',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j53_matric."-".$this->j53_idcons."-".$this->j53_caract;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j53_matric."-".$this->j53_idcons."-".$this->j53_caract;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j53_matric."-".$this->j53_idcons."-".$this->j53_caract;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j53_matric=null,$j53_idcons=null,$j53_caract=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j53_matric,$j53_idcons,$j53_caract));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,181,'$j53_matric','E')");
         $resac = db_query("insert into db_acountkey values($acount,182,'$j53_idcons','E')");
         $resac = db_query("insert into db_acountkey values($acount,183,'$j53_caract','E')");
         $resac = db_query("insert into db_acount values($acount,36,181,'','".AddSlashes(pg_result($resaco,$iresaco,'j53_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,36,182,'','".AddSlashes(pg_result($resaco,$iresaco,'j53_idcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,36,183,'','".AddSlashes(pg_result($resaco,$iresaco,'j53_caract'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from constrcar
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j53_matric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j53_matric = $j53_matric ";
        }
        if($j53_idcons != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j53_idcons = $j53_idcons ";
        }
        if($j53_caract != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j53_caract = $j53_caract ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j53_matric."-".$j53_idcons."-".$j53_caract;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j53_matric."-".$j53_idcons."-".$j53_caract;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j53_matric."-".$j53_idcons."-".$j53_caract;
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
        $this->erro_sql   = "Record Vazio na Tabela:constrcar";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j53_matric=null,$j53_idcons=null,$j53_caract=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from constrcar ";
     $sql .= "      inner join constrescr  on  constrescr.j52_matric = constrcar.j53_matric and  constrescr.j52_idcons = constrcar.j53_idcons";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = constrescr.j52_codigo";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = constrescr.j52_matric";
     $sql2 = "";
     if($dbwhere==""){
       if($j53_matric!=null ){
         $sql2 .= " where constrcar.j53_matric = $j53_matric "; 
       } 
       if($j53_idcons!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " constrcar.j53_idcons = $j53_idcons "; 
       } 
       if($j53_caract!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " constrcar.j53_caract = $j53_caract "; 
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
   function sql_query_file ( $j53_matric=null,$j53_idcons=null,$j53_caract=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from constrcar ";
     $sql2 = "";
     if($dbwhere==""){
       if($j53_matric!=null ){
         $sql2 .= " where constrcar.j53_matric = $j53_matric "; 
       } 
       if($j53_idcons!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " constrcar.j53_idcons = $j53_idcons "; 
       } 
       if($j53_caract!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " constrcar.j53_caract = $j53_caract "; 
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