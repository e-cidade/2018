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
//CLASSE DA ENTIDADE editalruaproj
class cl_editalruaproj { 
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
   var $d11_contri = 0; 
   var $d11_codproj = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 d11_contri = int4 = Constribuicao 
                 d11_codproj = int4 = Código da lista de projeto 
                 ";
   //funcao construtor da classe 
   function cl_editalruaproj() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("editalruaproj"); 
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
       $this->d11_contri = ($this->d11_contri == ""?@$GLOBALS["HTTP_POST_VARS"]["d11_contri"]:$this->d11_contri);
       $this->d11_codproj = ($this->d11_codproj == ""?@$GLOBALS["HTTP_POST_VARS"]["d11_codproj"]:$this->d11_codproj);
     }else{
       $this->d11_contri = ($this->d11_contri == ""?@$GLOBALS["HTTP_POST_VARS"]["d11_contri"]:$this->d11_contri);
       $this->d11_codproj = ($this->d11_codproj == ""?@$GLOBALS["HTTP_POST_VARS"]["d11_codproj"]:$this->d11_codproj);
     }
   }
   // funcao para inclusao
   function incluir ($d11_contri,$d11_codproj){ 
      $this->atualizacampos();
       $this->d11_contri = $d11_contri; 
       $this->d11_codproj = $d11_codproj; 
     if(($this->d11_contri == null) || ($this->d11_contri == "") ){ 
       $this->erro_sql = " Campo d11_contri nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->d11_codproj == null) || ($this->d11_codproj == "") ){ 
       $this->erro_sql = " Campo d11_codproj nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into editalruaproj(
                                       d11_contri 
                                      ,d11_codproj 
                       )
                values (
                                $this->d11_contri 
                               ,$this->d11_codproj 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Listas de contribuição ($this->d11_contri."-".$this->d11_codproj) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Listas de contribuição já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Listas de contribuição ($this->d11_contri."-".$this->d11_codproj) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d11_contri."-".$this->d11_codproj;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->d11_contri,$this->d11_codproj));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4727,'$this->d11_contri','I')");
       $resac = db_query("insert into db_acountkey values($acount,4728,'$this->d11_codproj','I')");
       $resac = db_query("insert into db_acount values($acount,629,4727,'','".AddSlashes(pg_result($resaco,0,'d11_contri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,629,4728,'','".AddSlashes(pg_result($resaco,0,'d11_codproj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($d11_contri=null,$d11_codproj=null) { 
      $this->atualizacampos();
     $sql = " update editalruaproj set ";
     $virgula = "";
     if(trim($this->d11_contri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d11_contri"])){ 
       $sql  .= $virgula." d11_contri = $this->d11_contri ";
       $virgula = ",";
       if(trim($this->d11_contri) == null ){ 
         $this->erro_sql = " Campo Constribuicao nao Informado.";
         $this->erro_campo = "d11_contri";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d11_codproj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d11_codproj"])){ 
       $sql  .= $virgula." d11_codproj = $this->d11_codproj ";
       $virgula = ",";
       if(trim($this->d11_codproj) == null ){ 
         $this->erro_sql = " Campo Código da lista de projeto nao Informado.";
         $this->erro_campo = "d11_codproj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($d11_contri!=null){
       $sql .= " d11_contri = $this->d11_contri";
     }
     if($d11_codproj!=null){
       $sql .= " and  d11_codproj = $this->d11_codproj";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->d11_contri,$this->d11_codproj));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4727,'$this->d11_contri','A')");
         $resac = db_query("insert into db_acountkey values($acount,4728,'$this->d11_codproj','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d11_contri"]))
           $resac = db_query("insert into db_acount values($acount,629,4727,'".AddSlashes(pg_result($resaco,$conresaco,'d11_contri'))."','$this->d11_contri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d11_codproj"]))
           $resac = db_query("insert into db_acount values($acount,629,4728,'".AddSlashes(pg_result($resaco,$conresaco,'d11_codproj'))."','$this->d11_codproj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Listas de contribuição nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->d11_contri."-".$this->d11_codproj;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Listas de contribuição nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->d11_contri."-".$this->d11_codproj;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d11_contri."-".$this->d11_codproj;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($d11_contri=null,$d11_codproj=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($d11_contri,$d11_codproj));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4727,'$d11_contri','E')");
         $resac = db_query("insert into db_acountkey values($acount,4728,'$d11_codproj','E')");
         $resac = db_query("insert into db_acount values($acount,629,4727,'','".AddSlashes(pg_result($resaco,$iresaco,'d11_contri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,629,4728,'','".AddSlashes(pg_result($resaco,$iresaco,'d11_codproj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from editalruaproj
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($d11_contri != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d11_contri = $d11_contri ";
        }
        if($d11_codproj != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d11_codproj = $d11_codproj ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Listas de contribuição nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$d11_contri."-".$d11_codproj;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Listas de contribuição nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$d11_contri."-".$d11_codproj;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$d11_contri."-".$d11_codproj;
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
        $this->erro_sql   = "Record Vazio na Tabela:editalruaproj";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $d11_contri=null,$d11_codproj=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from editalruaproj ";
     $sql .= "      inner join editalrua  on  editalrua.d02_contri = editalruaproj.d11_contri";
     $sql .= "      inner join projmelhorias  on  projmelhorias.d40_codigo = editalruaproj.d11_codproj";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = editalrua.d02_codigo";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = editalrua.d02_idlog";
     $sql .= "      inner join edital  as a on   a.d01_codedi = editalrua.d02_codedi";
     $sql .= "      inner join ruas  as b on   b.j14_codigo = projmelhorias.d40_codlog";
     $sql .= "      inner join db_usuarios  as c on   c.id_usuario = projmelhorias.d40_login";
     $sql2 = "";
     if($dbwhere==""){
       if($d11_contri!=null ){
         $sql2 .= " where editalruaproj.d11_contri = $d11_contri "; 
       } 
       if($d11_codproj!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " editalruaproj.d11_codproj = $d11_codproj "; 
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
   function sql_query_file ( $d11_contri=null,$d11_codproj=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from editalruaproj ";
     $sql2 = "";
     if($dbwhere==""){
       if($d11_contri!=null ){
         $sql2 .= " where editalruaproj.d11_contri = $d11_contri "; 
       } 
       if($d11_codproj!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " editalruaproj.d11_codproj = $d11_codproj "; 
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