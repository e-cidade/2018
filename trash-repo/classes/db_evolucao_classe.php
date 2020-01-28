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

//MODULO: dbicms
//CLASSE DA ENTIDADE evolucao
class cl_evolucao { 
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
   var $anousu = 0; 
   var $cgcter = null; 
   var $indice = 0; 
   var $ranking = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 anousu = int4 = Exercício 
                 cgcter = char(3) = Codigo do Município 
                 indice = float8 = Índice 
                 ranking = int4 = Ranking do Munícipio 
                 ";
   //funcao construtor da classe 
   function cl_evolucao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("evolucao"); 
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
       $this->anousu = ($this->anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["anousu"]:$this->anousu);
       $this->cgcter = ($this->cgcter == ""?@$GLOBALS["HTTP_POST_VARS"]["cgcter"]:$this->cgcter);
       $this->indice = ($this->indice == ""?@$GLOBALS["HTTP_POST_VARS"]["indice"]:$this->indice);
       $this->ranking = ($this->ranking == ""?@$GLOBALS["HTTP_POST_VARS"]["ranking"]:$this->ranking);
     }else{
       $this->anousu = ($this->anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["anousu"]:$this->anousu);
       $this->cgcter = ($this->cgcter == ""?@$GLOBALS["HTTP_POST_VARS"]["cgcter"]:$this->cgcter);
     }
   }
   // funcao para inclusao
   function incluir ($anousu,$cgcter){ 
      $this->atualizacampos();
     if($this->indice == null ){ 
       $this->erro_sql = " Campo Índice nao Informado.";
       $this->erro_campo = "indice";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ranking == null ){ 
       $this->erro_sql = " Campo Ranking do Munícipio nao Informado.";
       $this->erro_campo = "ranking";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->anousu = $anousu; 
       $this->cgcter = $cgcter; 
     if(($this->anousu == null) || ($this->anousu == "") ){ 
       $this->erro_sql = " Campo anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->cgcter == null) || ($this->cgcter == "") ){ 
       $this->erro_sql = " Campo cgcter nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into evolucao(
                                       anousu 
                                      ,cgcter 
                                      ,indice 
                                      ,ranking 
                       )
                values (
                                $this->anousu 
                               ,'$this->cgcter' 
                               ,$this->indice 
                               ,$this->ranking 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "evolucao ($this->anousu."-".$this->cgcter) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "evolucao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "evolucao ($this->anousu."-".$this->cgcter) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->anousu."-".$this->cgcter;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->anousu,$this->cgcter));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1019,'$this->anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,2275,'$this->cgcter','I')");
       $resac = db_query("insert into db_acount values($acount,362,1019,'','".AddSlashes(pg_result($resaco,0,'anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,362,2275,'','".AddSlashes(pg_result($resaco,0,'cgcter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,362,2293,'','".AddSlashes(pg_result($resaco,0,'indice'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,362,2362,'','".AddSlashes(pg_result($resaco,0,'ranking'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($anousu=null,$cgcter=null) { 
      $this->atualizacampos();
     $sql = " update evolucao set ";
     $virgula = "";
     if(trim($this->anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["anousu"])){ 
       $sql  .= $virgula." anousu = $this->anousu ";
       $virgula = ",";
       if(trim($this->anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cgcter)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cgcter"])){ 
       $sql  .= $virgula." cgcter = '$this->cgcter' ";
       $virgula = ",";
       if(trim($this->cgcter) == null ){ 
         $this->erro_sql = " Campo Codigo do Município nao Informado.";
         $this->erro_campo = "cgcter";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->indice)!="" || isset($GLOBALS["HTTP_POST_VARS"]["indice"])){ 
       $sql  .= $virgula." indice = $this->indice ";
       $virgula = ",";
       if(trim($this->indice) == null ){ 
         $this->erro_sql = " Campo Índice nao Informado.";
         $this->erro_campo = "indice";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ranking)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ranking"])){ 
       $sql  .= $virgula." ranking = $this->ranking ";
       $virgula = ",";
       if(trim($this->ranking) == null ){ 
         $this->erro_sql = " Campo Ranking do Munícipio nao Informado.";
         $this->erro_campo = "ranking";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($anousu!=null){
       $sql .= " anousu = $this->anousu";
     }
     if($cgcter!=null){
       $sql .= " and  cgcter = '$this->cgcter'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->anousu,$this->cgcter));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1019,'$this->anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,2275,'$this->cgcter','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["anousu"]))
           $resac = db_query("insert into db_acount values($acount,362,1019,'".AddSlashes(pg_result($resaco,$conresaco,'anousu'))."','$this->anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cgcter"]))
           $resac = db_query("insert into db_acount values($acount,362,2275,'".AddSlashes(pg_result($resaco,$conresaco,'cgcter'))."','$this->cgcter',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["indice"]))
           $resac = db_query("insert into db_acount values($acount,362,2293,'".AddSlashes(pg_result($resaco,$conresaco,'indice'))."','$this->indice',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ranking"]))
           $resac = db_query("insert into db_acount values($acount,362,2362,'".AddSlashes(pg_result($resaco,$conresaco,'ranking'))."','$this->ranking',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "evolucao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->anousu."-".$this->cgcter;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "evolucao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->anousu."-".$this->cgcter;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->anousu."-".$this->cgcter;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($anousu=null,$cgcter=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($anousu,$cgcter));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1019,'$anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,2275,'$cgcter','E')");
         $resac = db_query("insert into db_acount values($acount,362,1019,'','".AddSlashes(pg_result($resaco,$iresaco,'anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,362,2275,'','".AddSlashes(pg_result($resaco,$iresaco,'cgcter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,362,2293,'','".AddSlashes(pg_result($resaco,$iresaco,'indice'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,362,2362,'','".AddSlashes(pg_result($resaco,$iresaco,'ranking'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from evolucao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " anousu = $anousu ";
        }
        if($cgcter != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cgcter = '$cgcter' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "evolucao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$anousu."-".$cgcter;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "evolucao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$anousu."-".$cgcter;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$anousu."-".$cgcter;
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
        $this->erro_sql   = "Record Vazio na Tabela:evolucao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>