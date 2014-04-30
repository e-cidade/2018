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
//CLASSE DA ENTIDADE carlote
class cl_carlote { 
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
   var $j35_idbql = 0; 
   var $j35_caract = 0; 
   var $j35_dtlanc_dia = null; 
   var $j35_dtlanc_mes = null; 
   var $j35_dtlanc_ano = null; 
   var $j35_dtlanc = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j35_idbql = int4 = Id lote 
                 j35_caract = int4 = Caracteristica 
                 j35_dtlanc = date = Data de lancamento 
                 ";
   //funcao construtor da classe 
   function cl_carlote() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("carlote"); 
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
       $this->j35_idbql = ($this->j35_idbql == ""?@$GLOBALS["HTTP_POST_VARS"]["j35_idbql"]:$this->j35_idbql);
       $this->j35_caract = ($this->j35_caract == ""?@$GLOBALS["HTTP_POST_VARS"]["j35_caract"]:$this->j35_caract);
       if($this->j35_dtlanc == ""){
         $this->j35_dtlanc_dia = ($this->j35_dtlanc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j35_dtlanc_dia"]:$this->j35_dtlanc_dia);
         $this->j35_dtlanc_mes = ($this->j35_dtlanc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j35_dtlanc_mes"]:$this->j35_dtlanc_mes);
         $this->j35_dtlanc_ano = ($this->j35_dtlanc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j35_dtlanc_ano"]:$this->j35_dtlanc_ano);
         if($this->j35_dtlanc_dia != ""){
            $this->j35_dtlanc = $this->j35_dtlanc_ano."-".$this->j35_dtlanc_mes."-".$this->j35_dtlanc_dia;
         }
       }
     }else{
       $this->j35_idbql = ($this->j35_idbql == ""?@$GLOBALS["HTTP_POST_VARS"]["j35_idbql"]:$this->j35_idbql);
       $this->j35_caract = ($this->j35_caract == ""?@$GLOBALS["HTTP_POST_VARS"]["j35_caract"]:$this->j35_caract);
     }
   }
   // funcao para inclusao
   function incluir ($j35_idbql,$j35_caract){ 
      $this->atualizacampos();
      $this->j35_idbql = $j35_idbql; 
      $this->j35_caract = $j35_caract; 
     if(($this->j35_idbql == null) || ($this->j35_idbql == "") ){ 
       $this->erro_sql = " Campo j35_idbql nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j35_caract == null) || ($this->j35_caract == "") ){ 
       $this->erro_sql = " Campo j35_caract nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into carlote(
                                       j35_idbql 
                                      ,j35_caract 
                                      ,j35_dtlanc 
                       )
                values (
                                $this->j35_idbql 
                               ,$this->j35_caract 
                               ,".($this->j35_dtlanc == "null" || $this->j35_dtlanc == ""?"null":"'".$this->j35_dtlanc."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Caracteristicas ($this->j35_idbql."-".$this->j35_caract) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Caracteristicas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Caracteristicas ($this->j35_idbql."-".$this->j35_caract) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j35_idbql."-".$this->j35_caract;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j35_idbql,$this->j35_caract));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,96,'$this->j35_idbql','I')");
       $resac = db_query("insert into db_acountkey values($acount,97,'$this->j35_caract','I')");
       $resac = db_query("insert into db_acount values($acount,23,96,'','".AddSlashes(pg_result($resaco,0,'j35_idbql'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,23,97,'','".AddSlashes(pg_result($resaco,0,'j35_caract'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,23,8069,'','".AddSlashes(pg_result($resaco,0,'j35_dtlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j35_idbql=null,$j35_caract=null) { 
      $this->atualizacampos();
     $sql = " update carlote set ";
     $virgula = "";
     if(trim($this->j35_idbql)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j35_idbql"])){ 
       $sql  .= $virgula." j35_idbql = $this->j35_idbql ";
       $virgula = ",";
       if(trim($this->j35_idbql) == null ){ 
         $this->erro_sql = " Campo Id lote nao Informado.";
         $this->erro_campo = "j35_idbql";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j35_caract)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j35_caract"])){ 
       $sql  .= $virgula." j35_caract = $this->j35_caract ";
       $virgula = ",";
       if(trim($this->j35_caract) == null ){ 
         $this->erro_sql = " Campo Caracteristica nao Informado.";
         $this->erro_campo = "j35_caract";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j35_dtlanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j35_dtlanc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j35_dtlanc_dia"] !="") ){ 
       $sql  .= $virgula." j35_dtlanc = '$this->j35_dtlanc' ";
       $virgula = ",";
       if(trim($this->j35_dtlanc) == null ){ 
         $this->erro_sql = " Campo Data de lancamento nao Informado.";
         $this->erro_campo = "j35_dtlanc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j35_dtlanc_dia"])){ 
         $sql  .= $virgula." j35_dtlanc = null ";
         $virgula = ",";
         if(trim($this->j35_dtlanc) == null ){ 
           $this->erro_sql = " Campo Data de lancamento nao Informado.";
           $this->erro_campo = "j35_dtlanc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($j35_idbql!=null){
       $sql .= " j35_idbql = $this->j35_idbql";
     }
     if($j35_caract!=null){
       $sql .= " and  j35_caract = $this->j35_caract";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j35_idbql,$this->j35_caract));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,96,'$this->j35_idbql','A')");
         $resac = db_query("insert into db_acountkey values($acount,97,'$this->j35_caract','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j35_idbql"]))
           $resac = db_query("insert into db_acount values($acount,23,96,'".AddSlashes(pg_result($resaco,$conresaco,'j35_idbql'))."','$this->j35_idbql',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j35_caract"]))
           $resac = db_query("insert into db_acount values($acount,23,97,'".AddSlashes(pg_result($resaco,$conresaco,'j35_caract'))."','$this->j35_caract',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j35_dtlanc"]))
           $resac = db_query("insert into db_acount values($acount,23,8069,'".AddSlashes(pg_result($resaco,$conresaco,'j35_dtlanc'))."','$this->j35_dtlanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Caracteristicas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j35_idbql."-".$this->j35_caract;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Caracteristicas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j35_idbql."-".$this->j35_caract;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j35_idbql."-".$this->j35_caract;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j35_idbql=null,$j35_caract=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j35_idbql,$j35_caract));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,96,'$j35_idbql','E')");
         $resac = db_query("insert into db_acountkey values($acount,97,'$j35_caract','E')");
         $resac = db_query("insert into db_acount values($acount,23,96,'','".AddSlashes(pg_result($resaco,$iresaco,'j35_idbql'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,23,97,'','".AddSlashes(pg_result($resaco,$iresaco,'j35_caract'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,23,8069,'','".AddSlashes(pg_result($resaco,$iresaco,'j35_dtlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from carlote
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j35_idbql != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j35_idbql = $j35_idbql ";
        }
        if($j35_caract != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j35_caract = $j35_caract ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Caracteristicas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j35_idbql."-".$j35_caract;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Caracteristicas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j35_idbql."-".$j35_caract;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j35_idbql."-".$j35_caract;
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
        $this->erro_sql   = "Record Vazio na Tabela:carlote";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j35_idbql=null,$j35_caract=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from carlote ";
     $sql .= "      inner join caracter  on  caracter.j31_codigo = carlote.j35_caract";
     $sql .= "      inner join lote  on  lote.j34_idbql = carlote.j35_idbql";
     $sql .= "      inner join cargrup  on  cargrup.j32_grupo = caracter.j31_grupo";
     $sql .= "      inner join bairro  on  bairro.j13_codi = lote.j34_bairro";
     $sql .= "      inner join setor  on  setor.j30_codi = lote.j34_setor";
     $sql2 = "";
     if($dbwhere==""){
       if($j35_idbql!=null ){
         $sql2 .= " where carlote.j35_idbql = $j35_idbql "; 
       } 
       if($j35_caract!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " carlote.j35_caract = $j35_caract "; 
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
   function sql_query_file ( $j35_idbql=null,$j35_caract=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from carlote ";
     $sql2 = "";
     if($dbwhere==""){
       if($j35_idbql!=null ){
         $sql2 .= " where carlote.j35_idbql = $j35_idbql "; 
       } 
       if($j35_caract!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " carlote.j35_caract = $j35_caract "; 
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