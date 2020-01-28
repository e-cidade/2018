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

//MODULO: fiscal
//CLASSE DA ENTIDADE sanitarioinscr
class cl_sanitarioinscr { 
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
   var $y18_codsani = 0; 
   var $y18_inscr = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y18_codsani = int4 = Código do Alvará sanitário 
                 y18_inscr = int4 = Inscrição Municipal 
                 ";
   //funcao construtor da classe 
   function cl_sanitarioinscr() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sanitarioinscr"); 
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
       $this->y18_codsani = ($this->y18_codsani == ""?@$GLOBALS["HTTP_POST_VARS"]["y18_codsani"]:$this->y18_codsani);
       $this->y18_inscr = ($this->y18_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["y18_inscr"]:$this->y18_inscr);
     }else{
       $this->y18_codsani = ($this->y18_codsani == ""?@$GLOBALS["HTTP_POST_VARS"]["y18_codsani"]:$this->y18_codsani);
       $this->y18_inscr = ($this->y18_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["y18_inscr"]:$this->y18_inscr);
     }
   }
   // funcao para inclusao
   function incluir ($y18_codsani,$y18_inscr){ 
      $this->atualizacampos();
       $this->y18_codsani = $y18_codsani; 
       $this->y18_inscr = $y18_inscr; 
     if(($this->y18_codsani == null) || ($this->y18_codsani == "") ){ 
       $this->erro_sql = " Campo y18_codsani nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->y18_inscr == null) || ($this->y18_inscr == "") ){ 
       $this->erro_sql = " Campo y18_inscr nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sanitarioinscr(
                                       y18_codsani 
                                      ,y18_inscr 
                       )
                values (
                                $this->y18_codsani 
                               ,$this->y18_inscr 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Inscricao do sanitario ($this->y18_codsani."-".$this->y18_inscr) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Inscricao do sanitario já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Inscricao do sanitario ($this->y18_codsani."-".$this->y18_inscr) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y18_codsani."-".$this->y18_inscr;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y18_codsani,$this->y18_inscr));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8638,'$this->y18_codsani','I')");
       $resac = db_query("insert into db_acountkey values($acount,8639,'$this->y18_inscr','I')");
       $resac = db_query("insert into db_acount values($acount,1472,8638,'','".AddSlashes(pg_result($resaco,0,'y18_codsani'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1472,8639,'','".AddSlashes(pg_result($resaco,0,'y18_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y18_codsani=null,$y18_inscr=null) { 
      $this->atualizacampos();
     $sql = " update sanitarioinscr set ";
     $virgula = "";
     if(trim($this->y18_codsani)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y18_codsani"])){ 
       $sql  .= $virgula." y18_codsani = $this->y18_codsani ";
       $virgula = ",";
       if(trim($this->y18_codsani) == null ){ 
         $this->erro_sql = " Campo Código do Alvará sanitário nao Informado.";
         $this->erro_campo = "y18_codsani";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y18_inscr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y18_inscr"])){ 
       $sql  .= $virgula." y18_inscr = $this->y18_inscr ";
       $virgula = ",";
       if(trim($this->y18_inscr) == null ){ 
         $this->erro_sql = " Campo Inscrição Municipal nao Informado.";
         $this->erro_campo = "y18_inscr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y18_codsani!=null){
       $sql .= " y18_codsani = $this->y18_codsani";
     }
     if($y18_inscr!=null){
       $sql .= " and  y18_inscr = $this->y18_inscr";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y18_codsani,$this->y18_inscr));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8638,'$this->y18_codsani','A')");
         $resac = db_query("insert into db_acountkey values($acount,8639,'$this->y18_inscr','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y18_codsani"]))
           $resac = db_query("insert into db_acount values($acount,1472,8638,'".AddSlashes(pg_result($resaco,$conresaco,'y18_codsani'))."','$this->y18_codsani',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y18_inscr"]))
           $resac = db_query("insert into db_acount values($acount,1472,8639,'".AddSlashes(pg_result($resaco,$conresaco,'y18_inscr'))."','$this->y18_inscr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Inscricao do sanitario nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y18_codsani."-".$this->y18_inscr;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Inscricao do sanitario nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y18_codsani."-".$this->y18_inscr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y18_codsani."-".$this->y18_inscr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y18_codsani=null,$y18_inscr=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y18_codsani,$y18_inscr));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8638,'$y18_codsani','E')");
         $resac = db_query("insert into db_acountkey values($acount,8639,'$y18_inscr','E')");
         $resac = db_query("insert into db_acount values($acount,1472,8638,'','".AddSlashes(pg_result($resaco,$iresaco,'y18_codsani'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1472,8639,'','".AddSlashes(pg_result($resaco,$iresaco,'y18_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sanitarioinscr
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y18_codsani != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y18_codsani = $y18_codsani ";
        }
        if($y18_inscr != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y18_inscr = $y18_inscr ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Inscricao do sanitario nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y18_codsani."-".$y18_inscr;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Inscricao do sanitario nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y18_codsani."-".$y18_inscr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y18_codsani."-".$y18_inscr;
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
        $this->erro_sql   = "Record Vazio na Tabela:sanitarioinscr";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y18_codsani=null,$y18_inscr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sanitarioinscr ";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = sanitarioinscr.y18_inscr";
     $sql .= "      inner join sanitario  on  sanitario.y80_codsani = sanitarioinscr.y18_codsani";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = issbase.q02_numcgm";
     $sql .= "      inner join bairro  on  bairro.j13_codi = sanitario.y80_codbairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = sanitario.y80_codrua";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = sanitario.y80_numcgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = sanitario.y80_depto";
     $sql2 = "";
     if($dbwhere==""){
       if($y18_codsani!=null ){
         $sql2 .= " where sanitarioinscr.y18_codsani = $y18_codsani "; 
       } 
       if($y18_inscr!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " sanitarioinscr.y18_inscr = $y18_inscr "; 
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
   function sql_query_file ( $y18_codsani=null,$y18_inscr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sanitarioinscr ";
     $sql2 = "";
     if($dbwhere==""){
       if($y18_codsani!=null ){
         $sql2 .= " where sanitarioinscr.y18_codsani = $y18_codsani "; 
       } 
       if($y18_inscr!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " sanitarioinscr.y18_inscr = $y18_inscr "; 
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